<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'manage staff',
            'view workshops',
            'manage workshops',
            'view registrations',
            'manage registrations',
            'cancel registrations',
            'view registration history',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('Admin', 'web');
        $manager = Role::findOrCreate('Manager', 'web');
        $staff = Role::findOrCreate('Staff', 'web');

        // Admin manages users but only views workshops and registrations.
        $admin->syncPermissions([
            'manage staff',
            'view workshops',
            'view registrations',
            'view registration history',
        ]);

        // Manager manages workshops and registrations.
        $manager->syncPermissions([
            'view workshops',
            'manage workshops',
            'view registrations',
            'manage registrations',
            'cancel registrations',
            'view registration history',
        ]);

        // Staff manages registrations but cannot manage workshops.
        $staff->syncPermissions([
            'view workshops',
            'view registrations',
            'manage registrations',
            'cancel registrations',
            'view registration history',
        ]);

        $adminUser = User::role('Admin')->first();

        if (! $adminUser) {
            $adminUser = User::query()->orderBy('id')->first();

            $adminUser?->assignRole($admin);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
