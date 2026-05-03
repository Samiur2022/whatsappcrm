<?php

namespace App\Jobs;

use App\Models\Reminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $reminders = Reminder::pending()->get();

        foreach ($reminders as $reminder) {
            
            Mail::raw(
                "Promemoria: {$reminder->title}\n\n{$reminder->description}",
                function ($message) use ($reminder) {
                    $message->to($reminder->user->email)
                            ->subject('Promemoria: ' . $reminder->title);
                }
            );

            
            $reminder->update([
                'is_sent' => true,
                'notified_at' => now(),
            ]);
        }
    }
}