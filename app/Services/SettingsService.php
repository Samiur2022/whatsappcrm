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
}