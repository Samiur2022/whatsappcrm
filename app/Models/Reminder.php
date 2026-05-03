<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'user_id', 'title', 'description', 'remind_at', 'notified_at', 'is_sent'
    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'notified_at' => 'datetime',
        'is_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function scopePending($query)
    {
        return $query->where('is_sent', false)
                     ->where('remind_at', '<=', now());
    }
}