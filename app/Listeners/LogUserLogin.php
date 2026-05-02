<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserLoggedInMail;

class LogUserLogin
{
    public function handle(Login $event): void
    {
        $user = $event->user;
        $request = request();

        // জিওআইপি থেকে অবস্থান
        $geo = geoip()->getLocation($request->ip());
        $country = $geo->iso_code ?? null;
        $city = $geo->city ?? null;

        // ব্রাউজার ও ডিভাইস তথ্য
        $browser = \Browser::detect();
        $deviceType = $browser->deviceType();
        $browserName = $browser->browserName();
        $osName = $browser->platformName();

        // অ্যাক্টিভিটি রেকর্ড তৈরি
        $activity = UserActivity::create([
            'user_id'           => $user->id,
            'session_id'        => session()->getId(),
            'ip_address'        => $request->ip(),
            'user_agent'        => $request->userAgent(),
            'device_type'       => $deviceType,
            'browser'           => $browserName,
            'os'                => $osName,
            'country'           => $country,
            'city'              => $city,
            'logged_in_at'      => now(),
            'last_activity_at'  => now(),
        ]);

      
        Mail::to($user->email)->queue(new UserLoggedInMail($user, $activity));

        
        // if (class_exists(\App\Events\UserOnlineStatusChanged::class)) {
        //     broadcast(new \App\Events\UserOnlineStatusChanged($user, 'online'));
        // }
    }
}