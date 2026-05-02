<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Contact;
use App\Models\Campaign;
use App\Models\Message;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // স্ট্যাটিসটিক্স
        $totalConversations = Conversation::count();
        $openConversations = Conversation::where('status', 'open')->count();
        $newConversationsThisWeek = Conversation::where('created_at', '>=', now()->subWeek())->count();
        $conversationIncrease = $this->calculateIncrease($totalConversations, $newConversationsThisWeek);

        $totalContacts = Contact::count();
        $newContactsThisMonth = Contact::where('created_at', '>=', now()->subMonth())->count();
        $contactIncrease = $this->calculateIncrease($totalContacts, $newContactsThisMonth);

        $activeCampaigns = Campaign::where('status', 'sending')->count();
        $plannedCampaigns = Campaign::where('status', 'draft')->count();
        $totalCampaigns = Campaign::count();

        $messagesSentToday = Message::where('direction', 'outbound')
            ->whereDate('created_at', today())->count();
        $messagesReceivedToday = Message::where('direction', 'inbound')
            ->whereDate('created_at', today())->count();
        $totalMessages = $messagesSentToday + $messagesReceivedToday;
        $responseRate = $totalMessages > 0 ? round(($messagesSentToday / $totalMessages) * 100) : 0;

        // চার্ট ডেটা (গত ৭ দিনের ইনবাউন্ড/আউটবাউন্ড)
        $chartData = $this->getChartData();

        // সাম্প্রতিক ক্যাম্পেইন
        $recentCampaigns = Campaign::with('user')->latest()->take(3)->get();

        // অনলাইন অপারেটর (গত ৫ মিনিটে সক্রিয়)
        $onlineOperators = UserActivity::whereNull('logged_out_at')
            ->where('last_activity_at', '>=', now()->subMinutes(5))
            ->distinct('user_id')
            ->count('user_id');

        // আজকের পরিসংখ্যান
        $todayStats = [
            'sent' => $messagesSentToday,
            'received' => $messagesReceivedToday,
            'leads' => $newContactsThisMonth,
            'online' => $onlineOperators,
        ];

        return view('dashboard', compact(
            'totalConversations', 'openConversations', 'conversationIncrease',
            'totalContacts', 'contactIncrease',
            'activeCampaigns', 'plannedCampaigns', 'totalCampaigns',
            'messagesSentToday', 'messagesReceivedToday', 'responseRate',
            'chartData', 'recentCampaigns', 'todayStats'
        ));
    }

    private function calculateIncrease($total, $new)
    {
        if ($total <= 0) return 0;
        return round(($new / $total) * 100);
    }

    private function getChartData(): array
    {
        $days = collect(range(6, 0))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        });

        $outbound = [];
        $inbound = [];

        foreach ($days as $date) {
            $outbound[] = Message::where('direction', 'outbound')->whereDate('created_at', $date)->count();
            $inbound[] = Message::where('direction', 'inbound')->whereDate('created_at', $date)->count();
        }

        return [
            'labels' => $days->map(fn($d) => \Carbon\Carbon::parse($d)->format('D'))->toArray(),
            'outbound' => $outbound,
            'inbound' => $inbound,
        ];
    }
}