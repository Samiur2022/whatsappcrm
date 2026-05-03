<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected $cacheTime = 86400; // 24 ঘন্টা

    public function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", $this->cacheTime, function () use ($key, $default) {
            return Setting::getValue($key, $default);
        });
    }

    public function set(string $key, $value): void
    {
        Setting::setValue($key, $value);
        Cache::forget("setting_{$key}");
    }

    public function getTwilioSid(): ?string
    {
        return $this->get('twilio_sid');
    }

    public function getTwilioToken(): ?string
    {
        return $this->get('twilio_token');
    }

    public function getTwilioFrom(): ?string
    {
        return $this->get('twilio_from');
    }



    

    // Mail Settings
    public function getMailMailer(): ?string
    {
        return $this->get('mail_mailer', config('mail.default'));
    }

    public function getMailHost(): ?string
    {
        return $this->get('mail_host', config('mail.mailers.smtp.host'));
    }

    public function getMailPort(): ?string
    {
        return $this->get('mail_port', config('mail.mailers.smtp.port'));
    }

    public function getMailUsername(): ?string
    {
        return $this->get('mail_username', config('mail.mailers.smtp.username'));
    }

    public function getMailPassword(): ?string
    {
        return $this->get('mail_password', config('mail.mailers.smtp.password'));
    }

    public function getMailEncryption(): ?string
    {
        return $this->get('mail_encryption', config('mail.mailers.smtp.encryption'));
    }

    public function getMailFromAddress(): ?string
    {
        return $this->get('mail_from_address', config('mail.from.address'));
    }

    public function getMailFromName(): ?string
    {
        return $this->get('mail_from_name', config('mail.from.name'));
    }
}
