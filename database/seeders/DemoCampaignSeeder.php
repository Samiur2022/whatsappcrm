<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;

class DemoCampaignSeeder extends Seeder
{
    public function run(): void
    {
        // অ্যাডমিন ইউজার ধরে নিচ্ছি
        $user = User::first() ?? User::factory()->create(['email' => 'admin@example.com']);

        // ৪টি ক্যাম্পেইন
        $campaigns = [
            ['name' => 'Promo Primavera', 'body' => 'Ciao {{1}}, scopri le nostre offerte primaverili!'],
            ['name' => 'Saldi Estivi', 'body' => 'Sconti fino al 50% solo per te {{1}}!'],
            ['name' => 'Lancio Prodotto', 'body' => 'Il nuovo prodotto X è arrivato!'],
            ['name' => 'Follow-up Clienti', 'body' => 'Come possiamo aiutarti oggi {{1}}?'],
        ];

        // ২০টি ডামি কন্টাক্ট
        $contacts = [];
        for ($i = 1; $i <= 20; $i++) {
            $contacts[] = Contact::create([
                'name' => "Cliente Demo $i",
                'phone' => "+39320" . str_pad($i, 6, '0', STR_PAD_LEFT),
            ]);
        }

        foreach ($campaigns as $index => $campaignData) {
            // ক্যাম্পেইন তৈরি
            $campaign = Campaign::create([
                'name' => $campaignData['name'],
                'body' => $campaignData['body'],
                'status' => 'completed',
                'total_contacts' => 20,
                'sent_count' => rand(15, 20),
                'failed_count' => rand(0, 3),
                'user_id' => $user->id,
                'created_at' => now()->subDays(rand(1, 30)),
            ]);

            // সকল কন্টাক্টকে ক্যাম্পেইনে যুক্ত করো
            foreach ($contacts as $contact) {
                $campaign->contacts()->attach($contact->id, [
                    'status' => 'sent',
                ]);
            }

            // প্রতিটি কন্টাক্টের জন্য কনভারসেশন ও মেসেজ তৈরি করো
            foreach ($contacts as $contact) {
                // কনভারসেশন
                $conversation = Conversation::firstOrCreate(
                    ['contact_id' => $contact->id],
                    [
                        'channel' => 'whatsapp',
                        'status' => 'open',
                        'last_message_at' => $campaign->created_at->addMinutes(rand(1, 100)),
                    ]
                );

                // আউটবাউন্ড মেসেজ (ক্যাম্পেইনের)
                Message::create([
                    'conversation_id' => $conversation->id,
                    'contact_id' => $contact->id,
                    'user_id' => $user->id,
                    'direction' => 'outbound',
                    'body' => str_replace('{{1}}', $contact->name, $campaignData['body']),
                    'status' => 'sent',
                    'created_at' => $campaign->created_at,
                ]);

                // ৭০% সম্ভাবনায় ইনবাউন্ড রেসপন্স
                if (rand(1, 100) <= 70) {
                    Message::create([
                        'conversation_id' => $conversation->id,
                        'contact_id' => $contact->id,
                        'user_id' => null,
                        'direction' => 'inbound',
                        'body' => 'Grazie per l\'offerta! Maggiori dettagli?',
                        'status' => 'delivered',
                        'created_at' => $campaign->created_at->addMinutes(rand(5, 60)),
                    ]);
                }

                // ৩০% সম্ভাবনায় আরেকটি আউটবাউন্ড (ফলো-আপ)
                if (rand(1, 100) <= 30) {
                    Message::create([
                        'conversation_id' => $conversation->id,
                        'contact_id' => $contact->id,
                        'user_id' => $user->id,
                        'direction' => 'outbound',
                        'body' => 'Certamente! Ecco i dettagli...',
                        'status' => 'sent',
                        'created_at' => $campaign->created_at->addMinutes(rand(30, 120)),
                    ]);
                }
            }
        }
    }
}