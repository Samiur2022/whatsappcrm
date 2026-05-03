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
        $settings = Setting::whereIn('key', [
            'twilio_sid',
            'twilio_token',
            'twilio_from',
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name'
        ])->get();

        $twilioSid = $this->settings->getTwilioSid();
        $twilioToken = $this->settings->getTwilioToken();
        $twilioFrom = $this->settings->getTwilioFrom();

        $mailMailer = $this->settings->getMailMailer();
        $mailHost = $this->settings->getMailHost();
        $mailPort = $this->settings->getMailPort();
        $mailUsername = $this->settings->getMailUsername();
        $mailPassword = $this->settings->getMailPassword();
        $mailEncryption = $this->settings->getMailEncryption();
        $mailFromAddress = $this->settings->getMailFromAddress();
        $mailFromName = $this->settings->getMailFromName();

        return view('settings.index', compact(
            'settings',
            'twilioSid',
            'twilioToken',
            'twilioFrom',
            'mailMailer',
            'mailHost',
            'mailPort',
            'mailUsername',
            'mailPassword',
            'mailEncryption',
            'mailFromAddress',
            'mailFromName'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            // Twilio
            'twilio_sid' => 'required|string',
            'twilio_token' => 'required|string',
            'twilio_from' => 'required|string',

            // Mail
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|string',
            'mail_username' => 'required|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);

        // Twilio
        $this->settings->set('twilio_sid', $request->twilio_sid);
        $this->settings->set('twilio_token', $request->twilio_token);
        $this->settings->set('twilio_from', $request->twilio_from);

        // Mail
        $this->settings->set('mail_mailer', $request->mail_mailer);
        $this->settings->set('mail_host', $request->mail_host);
        $this->settings->set('mail_port', $request->mail_port);
        $this->settings->set('mail_username', $request->mail_username);
        if ($request->filled('mail_password')) {
            $this->settings->set('mail_password', $request->mail_password);
        }
        $this->settings->set('mail_encryption', $request->mail_encryption);
        $this->settings->set('mail_from_address', $request->mail_from_address);
        $this->settings->set('mail_from_name', $request->mail_from_name);

        // ক্যাশ ক্লিয়ার করে ফেলো যাতে সাথে সাথে কাজ করে
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');

        return response()->json(['message' => 'Impostazioni aggiornate con successo!']);
    }
}
