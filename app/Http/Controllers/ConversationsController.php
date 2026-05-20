<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\Message;
use App\Services\SettingsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class ConversationsController extends Controller
{
    use AuthorizesRequests;

    protected Client $twilio;
    protected SettingsService $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
        $this->initializeTwilio();
    }

    /**
     * Initialize Twilio Client
     */
    protected function initializeTwilio(): void
    {
        $sid = $this->settings->getTwilioSid();
        $token = $this->settings->getTwilioToken();

        if ($sid && $token) {
            $this->twilio = new Client($sid, $token);
        } else {
            $this->twilio = new Client(
                config('services.twilio.sid'),
                config('services.twilio.token')
            );
        }
    }

    /**
     * Get Twilio From Number
     */
    protected function getFromNumber(): string
    {
        return $this->settings->getTwilioFrom()
            ?: config('services.twilio.from');
    }

    /**
     * Conversations List
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->can('view all conversations')) {

            $conversations = Conversation::with([
                'contact',
                'assignedUser'
            ])
                ->orderByDesc('last_message_at')
                ->get();

        } else {

            $conversations = Conversation::with([
                'contact',
                'assignedUser'
            ])
                ->where(function ($query) use ($user) {

                    $query->where('assigned_user_id', $user->id)

                        ->orWhereHas('messages', function ($q) use ($user) {
                            $q->where('user_id', $user->id)
                                ->where('direction', 'outbound');
                        });

                })
                ->orderByDesc('last_message_at')
                ->get();
        }

        return view('conversations.index', compact('conversations'));
    }

    /**
     * Single Conversation
     */
    public function show(Request $request, $id)
    {
        $conversation = Conversation::with([
            'contact',
            'messages.user',
            'assignedUser'
        ])->findOrFail($id);

        if ($request->ajax()) {

            return response()->json([
                'conversation' => [
                    'id' => $conversation->id,
                    'contact' => $conversation->contact,

                    'assigned_user' => $conversation->assignedUser
                        ? [
                            'id' => $conversation->assignedUser->id,
                            'name' => $conversation->assignedUser->name,
                        ]
                        : null,

                    'last_message' => $conversation->last_message,
                    'unread_count' => $conversation->unread_count,
                ],

                'messages' => $conversation->messages->map(function ($msg) {

                    return [
                        'id' => $msg->id,
                        'body' => $msg->body,
                        'direction' => $msg->direction,
                        'status' => $msg->status,
                        'created_at' => $msg->created_at->format('H:i'),
                        'user' => $msg->user->name ?? null,
                    ];

                }),
            ]);
        }

        return view('conversations.show', compact('conversation'));
    }

    /**
     * Send WhatsApp Message
     */
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $conversation = Conversation::with('contact')
            ->findOrFail($conversationId);

        $this->authorize('update', $conversation);

        if (!$conversation->contact) {

            return response()->json([
                'error' => 'Contact not found.'
            ], 404);
        }

        if (empty($conversation->contact->phone)) {

            return response()->json([
                'error' => 'Contact phone number missing.'
            ], 422);
        }

        try {

            DB::beginTransaction();

            // Auto assign current user
            if (is_null($conversation->assigned_user_id)) {

                $conversation->update([
                    'assigned_user_id' => auth()->id(),
                ]);
            }

            $contact = $conversation->contact;

            /*
            |--------------------------------------------------------------------------
            | Send WhatsApp Message via Twilio
            |--------------------------------------------------------------------------
            */

            $twilioMessage = $this->twilio->messages->create(
                'whatsapp:' . $contact->phone,
                [
                    'from' => $this->getFromNumber(),
                    'body' => $request->body,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Save Message
            |--------------------------------------------------------------------------
            */

            $msg = Message::create([
                'conversation_id' => $conversation->id,
                'contact_id' => $contact->id,
                'user_id' => auth()->id(),

                'direction' => 'outbound',
                'body' => $request->body,
                'type' => 'text',

                'status' => 'sent',

                'provider_message_id' => $twilioMessage->sid,

                'sent_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Conversation
            |--------------------------------------------------------------------------
            */

            $conversation->update([
                'last_message' => $request->body,
                'last_message_at' => now(),
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | Broadcast Realtime Event
            |--------------------------------------------------------------------------
            */

            broadcast(new MessageSent($msg))->toOthers();

            return response()->json([
                'success' => true,

                'message' => [
                    'id' => $msg->id,
                    'body' => $msg->body,
                    'direction' => $msg->direction,
                    'status' => $msg->status,
                    'created_at' => $msg->created_at->format('H:i'),
                ]
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('Twilio Send Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Receive WhatsApp Webhook
     */
    public function receiveMessage(Request $request)
    {
        Log::info('Twilio Webhook Received', $request->all());

        try {

            $from = str_replace(
                'whatsapp:',
                '',
                $request->input('From')
            );

            $body = $request->input('Body');

            $messageSid = $request->input('MessageSid');

            /*
            |--------------------------------------------------------------------------
            | Contact
            |--------------------------------------------------------------------------
            */

            $contact = Contact::firstOrCreate(
                [
                    'phone' => $from,
                ],
                [
                    'name' => 'WhatsApp User ' . substr($from, -4),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Conversation
            |--------------------------------------------------------------------------
            */

            $conversation = Conversation::firstOrCreate(
                [
                    'contact_id' => $contact->id,
                ],
                [
                    'status' => 'open',
                    'channel' => 'whatsapp',
                    'last_message_at' => now(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Save Message
            |--------------------------------------------------------------------------
            */

            $msg = Message::create([
                'conversation_id' => $conversation->id,
                'contact_id' => $contact->id,

                'direction' => 'inbound',

                'body' => $body,
                'type' => 'text',

                'status' => 'delivered',

                'provider_message_id' => $messageSid,

                'sent_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Conversation
            |--------------------------------------------------------------------------
            */

            $conversation->update([
                'last_message' => $body,
                'last_message_at' => now(),

                'unread_count' => ($conversation->unread_count ?? 0) + 1,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Broadcast Event
            |--------------------------------------------------------------------------
            */

            broadcast(new MessageSent($msg))->toOthers();

            return response(
                '<Response></Response>',
                200
            )->header('Content-Type', 'text/xml');

        } catch (\Throwable $e) {

            Log::error('Webhook Error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response(
                '<Response></Response>',
                500
            )->header('Content-Type', 'text/xml');
        }
    }
}