<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // if (Auth::check()) {
        //     UserActivity::where('user_id', Auth::id())
        //         ->whereNull('logged_out_at')
        //         ->update(['last_activity_at' => now()]);

        //     $lastActivity = session('last_activity_time') ?? now();
        //     if (now()->diffInSeconds($lastActivity) > 300) {
        //         Auth::guard('web')->logout();

        //         $request->session()->invalidate();
        //         $request->session()->regenerateToken();

        //         return redirect()->route('login')->with('toast', [
        //             'type' => 'warning',
        //             'message' => 'Sei stato disconnesso per inattività.',
        //         ]);
        //     }

        //     session(['last_activity_time' => now()]);
        // }

        return $next($request);
    }
}