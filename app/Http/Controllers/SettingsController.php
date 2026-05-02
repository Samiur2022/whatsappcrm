<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settings;

    public function __construct(SettingsService $settings)
    {
        $this->settings = $settings;
    }

    public function index()
    {
        $settings = Setting::whereIn('key', ['twilio_sid', 'twilio_token', 'twilio_from'])->get();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'twilio_sid' => 'required|string',
            'twilio_token' => 'required|string',
            'twilio_from' => 'required|string',
        ]);

        // replace, no duplicate rows
        $this->settings->set('twilio_sid', $request->twilio_sid);
        $this->settings->set('twilio_token', $request->twilio_token);
        $this->settings->set('twilio_from', $request->twilio_from);

        return response()->json(['message' => 'Impostazioni Twilio aggiornate!']);
    }

    public function destroy($key)
    {
        Setting::where('key', $key)->delete();
        return response()->json(['message' => 'Impostazione eliminata!']);
    }
}