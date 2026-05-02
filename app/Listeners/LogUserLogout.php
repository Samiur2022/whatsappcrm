<?php

namespace App\Listeners;

use App\Mail\UserLoggedOutMail;
use Illuminate\Auth\Events\Logout;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Mail;

class LogUserLogout
{
    public function handle(Logout $event): void
    {
        $user = $event->user;
        if (!$user) return;

        
        $activity = UserActivity::where('user_id', $user->id)
            ->whereNull('logged_out_at')
            ->latest('logged_in_at')
            ->first();

        if ($activity) {
            $loggedOutAt = now();
            $durationMinutes = (int) $loggedOutAt->diffInMinutes($activity->logged_in_at);

            $activity->update([
                'logged_out_at' => $loggedOutAt,
                'duration_minutes' => $durationMinutes,
                'last_activity_at' => $loggedOutAt,
            ]);
        }

        Mail::to(config('mail.admin_email'))->queue(new UserLoggedOutMail($user, $activity));
    }
}