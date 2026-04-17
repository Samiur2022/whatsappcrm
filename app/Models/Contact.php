<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes; // Soft delete added

    protected $fillable = [
        'name',
        'phone',
        'email',
        'file_path',
        'status',
        'assigned_user_id',
        'last_contact_at',
        'status_updated_at',
    ];

    protected $casts = [
        'last_contact_at' => 'datetime',
        'status_updated_at' => 'datetime',
    ];

    public static $statuses = [
        'new' => 'Nuovo',
        'active' => 'Attivo',
        'pending' => 'In attesa',
        'cancelled' => 'Annullato',
        'success' => 'Successo',
    ];

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function messages()
    {
        return $this->hasManyThrough(Message::class, Conversation::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}