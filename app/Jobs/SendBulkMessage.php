<?php

namespace App\Jobs;

use App\Models\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class SendBulkMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $campaign;

    /**
     * try 
     */
    public $tries = 3;

    /**
     * job time out(s)
     */
    public $timeout = 600;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function handle(): void
    {
        $twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.token')
        );

        $campaign = $this->campaign;
        $campaign->update(['status' => 'sending']);

        
        $pendingContacts = $campaign->contacts()
            ->wherePivot('status', 'pending')
            ->get();

        foreach ($pendingContacts as $contact) {
            try {
                $twilio->messages->create(
                    "whatsapp:" . $contact->phone,
                    [
                        'from' => config('services.twilio.from'),
                        'body' => $campaign->body,
                    ]
                );

                
                $campaign->contacts()->updateExistingPivot($contact->id, [
                    'status' => 'sent'
                ]);
                $campaign->increment('sent_count');

                Log::info("Bulk message sent to {$contact->phone}");
            } catch (\Exception $e) {
               
                $campaign->contacts()->updateExistingPivot($contact->id, [
                    'status' => 'failed'
                ]);
                $campaign->increment('failed_count');

                Log::error("Bulk message failed for {$contact->phone}: " . $e->getMessage());
            }

            // Twilio Rate Limit: 
            sleep(1);
        }

        
        $campaign->update(['status' => 'completed']);
    }
}