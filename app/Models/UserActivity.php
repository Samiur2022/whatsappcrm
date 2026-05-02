<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'user_agent',
        'device_type',
        'browser',
        'os',
        'country',
        'city',
        'logged_in_at',
        'last_activity_at',
        'logged_out_at',
        'duration_minutes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
    'logged_in_at' => 'datetime',
    'last_activity_at' => 'datetime',
    'logged_out_at' => 'datetime',
];
}
