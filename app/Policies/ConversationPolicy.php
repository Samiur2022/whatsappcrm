<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * View conversation
     */
    public function view(User $user, Conversation $conversation): bool
    {
        // Admin can view all
        if ($user->can('view all conversations')) {
            return true;
        }

        // Assigned user
        if ($conversation->assigned_user_id === $user->id) {
            return true;
        }

        // User already participated
        return $conversation->messages()
            ->where('user_id', $user->id)
            ->where('direction', 'outbound')
            ->exists();
    }

    /**
     * Update / Send Message
     */
    public function update(User $user, Conversation $conversation): bool
    {
        // Admin
        if ($user->can('view all conversations')) {
            return true;
        }

        // Allow auto assign
        if (is_null($conversation->assigned_user_id)) {
            return true;
        }

        // Assigned agent
        return $conversation->assigned_user_id === $user->id;
    }
}