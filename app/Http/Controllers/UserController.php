<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.users.index', compact('users', 'roles'));
    }

    public function activity(User $user)
    {
        
        $activities = UserActivity::where('user_id', $user->id)
            ->latest()
            ->limit(30)
            ->get();

       
        $totalMinutes = UserActivity::where('user_id', $user->id)
            ->whereNotNull('logged_out_at')
            ->sum('duration_minutes');

        
        $mapped = $activities->map(function ($a) {
            return [
                'ip_address' => $a->ip_address ?? 'N/A',
                'device_type' => $a->device_type ?? 'Sconosciuto',
                'browser' => $a->browser ?? 'N/D',
                'os' => $a->os ?? 'N/D',
                'country' => $a->country ?? 'N/D',
                'city' => $a->city ?? 'N/D',
                'logged_in_at' => optional($a->logged_in_at)->format('d/m/Y H:i:s') ?? 'N/A',
                'logged_out_at' => optional($a->logged_out_at)->format('d/m/Y H:i:s') ?? null,
                'duration_minutes' => $a->duration_minutes ?? 0,
                'status' => $a->logged_out_at ? 'Offline' : 'Online', // ← এই লাইনটা এখানেই আছে
            ];
        });

        return response()->json([
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'is_blocked' => (bool) $user->is_blocked,
            ],
            'activities' => $mapped,
            'total_active_hours' => round($totalMinutes / 60, 2) . ' ore',
        ]);
    }

    public function assignRole(Request $request, User $user)
    {
        $this->authorize('manage users');
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);
        $user->syncRoles([$request->role]);
        return response()->json(['success' => true, 'message' => 'Role updated.']);
    }

    public function toggleBlock(User $user)
    {
        $this->authorize('manage users');
        $user->is_blocked = !$user->is_blocked;
        $user->save();

        return response()->json([
            'success' => true,
            'blocked' => $user->is_blocked
        ]);
    }
}