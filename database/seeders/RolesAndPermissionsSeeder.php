<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

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

        $admin->syncPermissions(Permission::query()->where('guard_name', 'web')->get());

        $manager->syncPermissions([
            'view workshops',
            'manage workshops',
            'view registrations',
            'manage registrations',
            'cancel registrations',
            'view registration history',
        ]);

        $staff->syncPermissions([
            'view workshops',
            'view registrations',
            'manage registrations',
            'cancel registrations',
            'view registration history',
        ]);

        User::query()
            ->orderBy('id')
            ->first()
            ?->assignRole($admin);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
