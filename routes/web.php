<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/campaigns', 'campaigns.index')->name('campaigns.index');
    Route::view('/settings', 'settings.index')->name('settings.index');

    // Contacts
    Route::resource('contacts', ContactsController::class);
    Route::patch('/contacts/{contact}/status', [ContactsController::class, 'updateStatus'])->name('contacts.update-status');
    Route::post('/contacts/{id}/restore', [ContactsController::class, 'restore'])->name('contacts.restore');

    // Conversations
    Route::get('/conversations', [ConversationsController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [ConversationsController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/send', [ConversationsController::class, 'sendMessage'])->name('conversations.send');
});

// Twilio webhook route (no auth, CSRF excluded in bootstrap/app.php)
Route::post('/conversations/receive', [ConversationsController::class, 'receiveMessage'])->name('conversations.receive');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';