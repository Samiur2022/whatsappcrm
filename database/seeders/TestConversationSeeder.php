<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\Conversation;

class TestConversationSeeder extends Seeder
{
    public function run(): void
    {
        // ইতালিয়ান নাম ও ফোন জেনারেট করব
        $names = [
            'Marco Rossi', 'Giulia Bianchi', 'Luca Verdi', 'Alessia Neri', 'Francesco Gallo',
            'Sofia Conti', 'Matteo Bruno', 'Chiara Russo', 'Andrea Ricci', 'Valentina Marino',
            'Davide Esposito', 'Alice De Luca', 'Simone Rinaldi', 'Elena Costa', 'Alessandro Fabbri',
            'Martina Moretti', 'Federico Ferrara', 'Laura Sala', 'Pietro Barbieri', 'Chiara Fontana',
            'Riccardo Benedetti', 'Silvia Giuliani', 'Fabio Testa', 'Giorgia Vitali', 'Antonio Carbone',
            'Roberta Leone', 'Giovanni Grasso', 'Raffaella Martini', 'Stefano Pellegrini', 'Monica Palumbo',
            'Emanuele Lombardi', 'Cristina Valente', 'Massimo Amato', 'Mara Parisi', 'Daniele Santi',
            'Paola Mazza', 'Salvatore Ferri', 'Tiziana Gentile', 'Vincenzo D’Angelo', 'Irene Caputo',
            'Angelo Rizzo', 'Daniela Bellini', 'Claudio Orlando', 'Lara De Santis', 'Roberto Vitale',
            'Giada Cassano', 'Gabriele Boni', 'Michele Salvini', 'Sabrina Negri', 'Enrico Costantini',
        ];

        $basePhone = 320000000; // +39 320-xxxxxx ইতালিয়ান মোবাইল

        foreach ($names as $i => $name) {
            // কন্টাক্ট তৈরি
            $contact = Contact::create([
                'name'  => $name,
                'phone' => '+39' . ($basePhone + $i),
            ]);

            // কনভারসেশন তৈরি
            Conversation::create([
                'contact_id'       => $contact->id,
                'channel'          => 'whatsapp',
                'status'           => 'open',
                'unread_count'     => rand(0, 3),
                'last_message'     => null,
                'last_message_at'  => null,
            ]);
        }

        $this->command->info('50 contatti e conversazioni creati con successo!');
    }
}