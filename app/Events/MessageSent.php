<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message->load('user', 'contact'); // Load relationships
    }

    public function broadcastOn()
    {
        // Public channel - no authentication needed
        return new Channel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'body' => $this->message->body,
            'direction' => $this->message->direction,
            'status' => $this->message->status,
            'created_at' => $this->message->created_at->format('H:i'),
            'conversation_id' => $this->message->conversation_id,
            'user' => $this->message->user ? [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
            ] : null,
        ];
    }
}