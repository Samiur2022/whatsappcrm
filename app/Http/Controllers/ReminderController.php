<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Reminder::where('user_id', auth()->id())
            ->orderBy('remind_at')
            ->get();

        return view('reminders.index', compact('reminders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'remind_at' => 'required|date|after:now',
        ]);

        Reminder::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'remind_at' => $validated['remind_at'],
        ]);

        return response()->json(['success' => true, 'message' => 'Promemoria creato!']);
    }

    public function destroy(Reminder $reminder)
    {
        $reminder->delete();
        return response()->json(['success' => true]);
    }
}