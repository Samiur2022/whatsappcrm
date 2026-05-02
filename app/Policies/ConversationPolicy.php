<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    public function view(User $user, Conversation $conversation): bool
    {
        
        if ($user->can('view all conversations')) {
            return true;
        }

      
        return $conversation->assigned_user_id === $user->id
            || $conversation->messages()
                           ->where('user_id', $user->id)
                           ->where('direction', 'outbound')
                           ->exists();
    }

    public function update(User $user, Conversation $conversation): bool
    {
        
        return $user->can('view all conversations') || $conversation->assigned_user_id === $user->id;
    }
}