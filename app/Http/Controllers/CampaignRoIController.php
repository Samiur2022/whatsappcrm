<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Message;
use Illuminate\Http\Request;

class CampaignRoIController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('user')->latest()->get();

        $roiData = $campaigns->map(function ($campaign) {
            
            $contactIds = $campaign->contacts()->pluck('contact_id');

            
            $responses = Message::whereIn('contact_id', $contactIds)
                ->where('direction', 'inbound')
                ->where('created_at', '>=', $campaign->created_at)
                ->count();

            
            $uniqueResponders = Message::whereIn('contact_id', $contactIds)
                ->where('direction', 'inbound')
                ->where('created_at', '>=', $campaign->created_at)
                ->distinct('contact_id')
                ->count('contact_id');

            
            $costPerMessage = 0.05; 
            $totalCost = $campaign->sent_count * $costPerMessage;

            
            $conversionRate = $campaign->total_contacts > 0
                ? round(($uniqueResponders / $campaign->total_contacts) * 100, 2)
                : 0;

            
            $valuePerLead = 10; 
            $estimatedProfit = ($uniqueResponders * $valuePerLead) - $totalCost;

            return [
                'name' => $campaign->name,
                'sent' => $campaign->sent_count,
                'responses' => $responses,
                'unique_responders' => $uniqueResponders,
                'conversion_rate' => $conversionRate,
                'total_cost' => round($totalCost, 2),
                'estimated_profit' => round($estimatedProfit, 2),
                'roi' => $totalCost > 0 ? round(($estimatedProfit / $totalCost) * 100, 2) : 0,
            ];
        });

        return view('campaigns.roi', compact('roiData'));
    }
}