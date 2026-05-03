<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\ConversationsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CampaignRoIController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ReminderController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::post('/conversations/receive', [ConversationsController::class, 'receiveMessage'])->name('conversations.receive');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('can:view analytics')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // Conversations - view conversations permission দরকার
    Route::middleware('can:view conversations')->group(function () {
        Route::get('/conversations', [ConversationsController::class, 'index'])->name('conversations.index');
        Route::get('/conversations/{conversation}', [ConversationsController::class, 'show'])->name('conversations.show');
        Route::post('/conversations/{conversation}/send', [ConversationsController::class, 'sendMessage'])->name('conversations.send');
    });

    // Contacts - manage contacts
    Route::middleware('can:manage contacts')->group(function () {
        Route::resource('contacts', ContactsController::class);
        Route::patch('/contacts/{contact}/status', [ContactsController::class, 'updateStatus'])->name('contacts.update-status');
        Route::post('/contacts/{id}/restore', [ContactsController::class, 'restore'])->name('contacts.restore');
    });

    // Campaigns - manage campaigns
    Route::middleware('can:manage campaigns')->prefix('campaigns')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::post('/', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::post('/import-excel', [CampaignController::class, 'importExcel'])->name('campaigns.import-excel');
        Route::get('/{campaign}/progress', [CampaignController::class, 'progress'])->name('campaigns.progress');
        Route::get('/roi', [CampaignRoIController::class, 'index'])->name('campaigns.roi');
    });

    // Reminders
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders', [ReminderController::class, 'store'])->name('reminders.store');
    Route::delete('/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('reminders.destroy');

    // Settings - manage settings
    Route::middleware('can:manage settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::delete('/settings/{key}', [SettingsController::class, 'destroy'])->name('settings.destroy');
    });

    // User Management - manage users
    Route::middleware('can:manage users')->prefix('admin/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::post('/{user}/assign-role', [UserController::class, 'assignRole'])->name('admin.users.assign-role');
        Route::post('/{user}/toggle-block', [UserController::class, 'toggleBlock'])->name('admin.users.toggle-block');
        Route::get('/{user}/activity', [UserController::class, 'activity'])->name('admin.users.activity');
    });

    // Role Management - manage roles
    Route::middleware('can:manage roles')->prefix('admin/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('admin.roles.index');
        Route::get('/create', [RoleController::class, 'create'])->name('admin.roles.create');
        Route::post('/', [RoleController::class, 'store'])->name('admin.roles.store');
        Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
        Route::put('/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    });
});

require __DIR__ . '/auth.php';