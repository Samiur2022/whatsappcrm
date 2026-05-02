<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        
        $permissions = [
            ['name' => 'view conversations', 'guard_name' => 'web'],
            ['name' => 'edit conversations', 'guard_name' => 'web'],
            ['name' => 'delete conversations', 'guard_name' => 'web'],
            ['name' => 'manage contacts', 'guard_name' => 'web'],
            ['name' => 'manage campaigns', 'guard_name' => 'web'],
            ['name' => 'view analytics', 'guard_name' => 'web'],
            ['name' => 'manage users', 'guard_name' => 'web'],
            ['name' => 'manage roles', 'guard_name' => 'web'],
            ['name' => 'manage settings', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate($perm);
        }

      
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $staff = Role::firstOrCreate(['name' => 'staff']);

        
        $admin->syncPermissions(Permission::all());

        
        $manager->syncPermissions([
            'view conversations',
            'edit conversations',
            'view analytics'
        ]);

        
        $staff->syncPermissions([
            'view conversations',
            'edit conversations'
        ]);
    }
}