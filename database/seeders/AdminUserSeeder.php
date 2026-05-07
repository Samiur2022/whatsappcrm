<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        //All permission
        $permissions = [
            'view all conversations',
            'view conversations',
            'edit conversations',
            'delete conversations',
            'manage contacts',
            'manage campaigns',
            'view analytics',
            'manage users',
            'manage roles',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ২. role setup 
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // ৩. staff role
        $staffRole = Role::firstOrCreate(['name' => 'staff']);
        $staffRole->syncPermissions([
            'view conversations',
            'edit conversations',
        ]);

        // ৪. অ্যাডমিন ইউজার তৈরি (যদি না থাকে)
        $adminEmail = 'admin@gmail.com';
        $adminPassword = 'password'; // প্রোডাকশনে অবশ্যই জটিল পাসওয়ার্ড দেবে

        $admin = User::firstOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'MD Emon Mir',
                'password' => Hash::make($adminPassword),
                'is_blocked' => false,
            ]
        );

        // ৫. অ্যাডমিনকে রোল দাও (না থাকলে)
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: ' . $adminEmail);
        $this->command->info('🔑 Password: ' . $adminPassword);
    }
}