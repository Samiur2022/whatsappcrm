<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'body', 'status', 'total_contacts',
        'sent_count', 'failed_count', 'user_id'
    ];

    protected $casts = [
        'total_contacts' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
    ];

    /**
     *
     */
    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'campaign_contact')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}