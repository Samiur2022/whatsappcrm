<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Contact;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class ConversationsController extends Controller
{
    protected $twilio;

   public function __construct()
{
    $this->twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));
}

    public function index()
    {
        $conversations = Conversation::with(['contact'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        return view('conversations.index', compact('conversations'));
    }

    public function show(Request $request, $id)
    {
        $conversation = Conversation::with(['contact', 'messages.user'])->findOrFail($id);

        if ($request->ajax()) {
            return response()->json([
                'conversation' => $conversation,
                'messages' => $conversation->messages->map(function ($msg) {
                    return [
                        'id' => $msg->id,
                        'body' => $msg->body,
                        'direction' => $msg->direction,
                        'status' => $msg->status,
                        'created_at' => $msg->created_at->format('H:i'),
                    ];
                }),
            ]);
        }

        return view('conversations.show', compact('conversation'));
    }

    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate(['body' => 'required|string']);

        $conversation = Conversation::findOrFail($conversationId);
        $contact = $conversation->contact;

        try {
            $twilioMessage = $this->twilio->messages->create(
    "whatsapp:" . $contact->phone,
    [
        'from' => config('services.twilio.from'), // change 
        'body' => $request->body,
    ]
);

            $msg = Message::create([
                'conversation_id' => $conversationId,
                'contact_id' => $contact->id,
                'user_id' => auth()->id(),
                'direction' => 'outbound',
                'body' => $request->body,
                'status' => 'sent',
                'provider_message_id' => $twilioMessage->sid,
                'sent_at' => now(),
            ]);

            $conversation->update([
                'last_message' => $request->body,
                'last_message_at' => now(),
            ]);

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
        } catch (\Exception $e) {
            Log::error('Twilio Send Error: ' . $e->getMessage());

            return response()->json([
                'error' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function receiveMessage(Request $request)
    {
        Log::info('Twilio Webhook Received', $request->all());

        $from = str_replace('whatsapp:', '', $request->From);
        $body = $request->Body;
        $messageSid = $request->MessageSid;

        $contact = Contact::where('phone', $from)->first();
        if (!$contact) {
            $contact = Contact::create([
                'name' => 'WhatsApp User ' . substr($from, -4),
                'phone' => $from
            ]);
        }

        $conversation = Conversation::firstOrCreate(
            ['contact_id' => $contact->id],
            [
                'status' => 'open',
                'channel' => 'whatsapp',
                'last_message_at' => now(),
            ]
        );

        $msg = Message::create([
            'conversation_id' => $conversation->id,
            'contact_id' => $contact->id,
            'direction' => 'inbound',
            'body' => $body,
            'status' => 'delivered',
            'provider_message_id' => $messageSid,
            'sent_at' => now(),
        ]);

        $conversation->update([
            'last_message' => $body,
            'last_message_at' => now(),
            'unread_count' => ($conversation->unread_count ?? 0) + 1,
        ]);

        broadcast(new MessageSent($msg))->toOthers();

        return response('<Response></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }
}