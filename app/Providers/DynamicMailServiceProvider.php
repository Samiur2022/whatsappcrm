<?php

namespace App\Providers;

use App\Services\SettingsService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class DynamicMailServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        try {
            $settings = app(SettingsService::class);

            $mailer = $settings->getMailMailer();
            if ($mailer) {
                Config::set('mail.default', $mailer);
            }

            $host = $settings->getMailHost();
            if ($host) {
                Config::set('mail.mailers.smtp.host', $host);
            }

            $port = $settings->getMailPort();
            if ($port) {
                Config::set('mail.mailers.smtp.port', $port);
            }

            $username = $settings->getMailUsername();
            if ($username) {
                Config::set('mail.mailers.smtp.username', $username);
            }

            $password = $settings->getMailPassword();
            if ($password) {
                Config::set('mail.mailers.smtp.password', $password);
            }

            $encryption = $settings->getMailEncryption();
            if ($encryption) {
                Config::set('mail.mailers.smtp.encryption', $encryption);
            }

            $fromAddress = $settings->getMailFromAddress();
            if ($fromAddress) {
                Config::set('mail.from.address', $fromAddress);
            }

            $fromName = $settings->getMailFromName();
            if ($fromName) {
                Config::set('mail.from.name', $fromName);
            }
        } catch (\Exception $e) {
            // ডাটাবেজ কানেকশন না থাকলে ফলব্যাক .env
            \Illuminate\Support\Facades\Log::warning('DynamicMailServiceProvider: ' . $e->getMessage());
        }
    }
}