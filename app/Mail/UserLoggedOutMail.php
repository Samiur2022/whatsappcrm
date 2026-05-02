<?php

namespace App\Mail;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Mail\Mailable;

class UserLoggedOutMail extends Mailable
{
    public $user;
    public $activity;

    public function __construct(User $user, UserActivity $activity)
    {
        $this->user = $user;
        $this->activity = $activity;
    }

    public function build(): self
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->subject('Disconnessione - ' . config('app.name'))
                    ->markdown('emails.user-logged-out');
    }
}