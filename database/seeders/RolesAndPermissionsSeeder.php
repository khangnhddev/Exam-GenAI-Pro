<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Clear cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions with sanctum guard
        $permissions = [
            // Exam Management
            'exams.view',
            'exams.create',
            'exams.edit',
            'exams.delete',
            
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // Report Management
            'reports.view',
            'reports.generate',
            
            // Settings
            'settings.manage'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
                'guard_name' => 'sanctum'
            ]);
        }

        // Create roles
        $studentRole = Role::create([
            'name' => 'student',
            'guard_name' => 'sanctum'
        ]);

        $instructorRole = Role::create([
            'name' => 'instructor',
            'guard_name' => 'sanctum'
        ]);

        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'sanctum'
        ]);

        $superAdminRole = Role::create([
            'name' => 'super-admin',
            'guard_name' => 'sanctum'
        ]);

        // Assign permissions to roles
        $studentRole->givePermissionTo([
            'exams.view',
            'reports.view'
        ]);

        $instructorRole->givePermissionTo([
            'exams.view',
            'exams.create',
            'exams.edit',
            'reports.view',
            'reports.generate'
        ]);

        $adminRole->givePermissionTo(Permission::all());
        $superAdminRole->givePermissionTo(Permission::all());

        // Assign roles to users (example)
        if ($admin = User::where('email', 'duykhang@jv-it.com.vn')->first()) {
            $admin->assignRole('admin');
        }
    }
}