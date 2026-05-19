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
    $rules = [];

    // Twilio fields validation (only if any twilio field is present)
    if ($request->hasAny(['twilio_sid', 'twilio_token', 'twilio_from'])) {
        $rules['twilio_sid'] = 'required|string';
        $rules['twilio_token'] = 'required|string';
        $rules['twilio_from'] = 'required|string';
    }

    // Mail fields validation (only if any mail field is present)
    if ($request->hasAny(['mail_mailer', 'mail_host', 'mail_port', 'mail_username', 'mail_encryption', 'mail_from_address', 'mail_from_name'])) {
        $rules['mail_mailer'] = 'required|string';
        $rules['mail_host'] = 'required|string';
        $rules['mail_port'] = 'required|string';
        $rules['mail_username'] = 'required|string';
        $rules['mail_password'] = 'nullable|string';
        $rules['mail_encryption'] = 'required|string';
        $rules['mail_from_address'] = 'required|email';
        $rules['mail_from_name'] = 'required|string';
    }

    $validated = $request->validate($rules);

    // Twilio update (only if validated)
    if (isset($validated['twilio_sid'])) {
        $this->settings->set('twilio_sid', $validated['twilio_sid']);
        $this->settings->set('twilio_token', $validated['twilio_token']);
        $this->settings->set('twilio_from', $validated['twilio_from']);
    }

    // Mail update (only if validated)
    if (isset($validated['mail_mailer'])) {
        $this->settings->set('mail_mailer', $validated['mail_mailer']);
        $this->settings->set('mail_host', $validated['mail_host']);
        $this->settings->set('mail_port', $validated['mail_port']);
        $this->settings->set('mail_username', $validated['mail_username']);
        if (!empty($validated['mail_password'])) {
            $this->settings->set('mail_password', $validated['mail_password']);
        }
        $this->settings->set('mail_encryption', $validated['mail_encryption']);
        $this->settings->set('mail_from_address', $validated['mail_from_address']);
        $this->settings->set('mail_from_name', $validated['mail_from_name']);
    }

    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');

    return response()->json(['message' => 'Impostazioni aggiornate con successo!']);
}
}
