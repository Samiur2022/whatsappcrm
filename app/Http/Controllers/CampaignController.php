<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Contact;
use App\Jobs\SendBulkMessage;
use App\Imports\ContactsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::with('user')->latest()->get();
        $contacts = Contact::orderBy('name')->get();
        return view('campaigns.index', compact('campaigns', 'contacts'));
    }

    public function importExcel(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);

        Excel::import(new ContactsImport, $request->file('file'));

        return response()->json(['success' => true, 'message' => 'Contatti importati con successo!']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'body' => 'required|string',
            'contacts' => 'required|array|min:1',
            'contacts.*' => 'exists:contacts,id'
        ]);

        $campaign = Campaign::create([
            'name' => $validated['name'],
            'body' => $validated['body'],
            'status' => 'draft',
            'total_contacts' => count($validated['contacts']),
            'user_id' => auth()->id(),
        ]);

        $campaign->contacts()->attach($validated['contacts'], ['status' => 'pending']);

        SendBulkMessage::dispatch($campaign);

        return response()->json([
            'success' => true,
            'campaign_id' => $campaign->id,
            'message' => 'Campagna creata e in fase di invio!'
        ]);
    }

    public function progress(Campaign $campaign)
    {
        return response()->json([
            'status' => $campaign->status,
            'total' => $campaign->total_contacts,
            'sent' => $campaign->sent_count,
            'failed' => $campaign->failed_count,
        ]);
    }

}