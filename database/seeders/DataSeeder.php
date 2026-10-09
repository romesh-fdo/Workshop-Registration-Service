<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use App\Models\Workshop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DataSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create permissions.
        $permissions = [
            'manage workshops',
            'manage registrations',
            'cancel registrations',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 2. Create roles.
        $adminRole = Role::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
        ]);

        $managerRole = Role::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $staffRole = Role::firstOrCreate([
            'name' => 'Staff',
            'guard_name' => 'web',
        ]);

        // Admin manages staff accounts through the User resource.
        $adminRole->syncPermissions([]);

        $managerRole->syncPermissions([
            'manage workshops',
            'manage registrations',
            'cancel registrations',
        ]);

        $staffRole->syncPermissions([
            'manage registrations',
            'cancel registrations',
        ]);

        // 3. Create sample users.
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'password' => Hash::make('Password123!'),
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@example.com'],
            [
                'name' => 'Workshop Manager',
                'password' => Hash::make('Password123!'),
            ]
        );

        $staff = User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'Registration Staff',
                'password' => Hash::make('Password123!'),
            ]
        );

        $admin->syncRoles([$adminRole]);
        $manager->syncRoles([$managerRole]);
        $staff->syncRoles([$staffRole]);

        // 4. Create sample workshops.
        $workshop1 = Workshop::updateOrCreate(
            ['code' => 'WS-001'],
            [
                'title' => 'Introduction to Laravel',
                'instructor' => 'John Silva',
                'starts_at' => now()->addDays(7)->setTime(9, 0),
                'capacity' => 3,
                'status' => 'scheduled',
            ]
        );

        $workshop2 = Workshop::updateOrCreate(
            ['code' => 'WS-002'],
            [
                'title' => 'Advanced PHP Development',
                'instructor' => 'Sarah Fernando',
                'starts_at' => now()->addDays(14)->setTime(10, 0),
                'capacity' => 2,
                'status' => 'scheduled',
            ]
        );

        $workshop3 = Workshop::updateOrCreate(
            ['code' => 'WS-003'],
            [
                'title' => 'Database Design Fundamentals',
                'instructor' => 'Michael Perera',
                'starts_at' => now()->addDays(21)->setTime(9, 30),
                'capacity' => 10,
                'status' => 'scheduled',
            ]
        );

        // 5. Create registrations for Workshop 1.
        $this->createRegistration(
            $workshop1,
            'Nimal Perera',
            'nimal@example.com',
            'active',
            $staff
        );

        $this->createRegistration(
            $workshop1,
            'Kamal Silva',
            'kamal@example.com',
            'active',
            $staff
        );

        // This attendee was originally waitlisted and later promoted.
        $promotedAt = now()->subHours(2);

        $this->createRegistration(
            $workshop1,
            'Amali Fernando',
            'amali@example.com',
            'active',
            $staff,
            [
                'activated_at' => $promotedAt,
                'activated_by' => $manager->id,
                'registered_at' => now()->subDays(2),
            ]
        );

        // Historical cancellation record.
        $this->createRegistration(
            $workshop1,
            'Kasun Jayawardena',
            'kasun@example.com',
            'cancelled',
            $staff,
            [
                'cancelled_by' => $manager->id,
                'cancelled_at' => now()->subHours(2),
                'cancellation_reason' => 'Unable to attend the workshop.',
            ]
        );

        // 6. Workshop 2: full workshop with a waitlisted attendee.
        $this->createRegistration(
            $workshop2,
            'Tharushi Silva',
            'tharushi@example.com',
            'active',
            $staff
        );

        $this->createRegistration(
            $workshop2,
            'Dinesh Perera',
            'dinesh@example.com',
            'active',
            $staff
        );

        $this->createRegistration(
            $workshop2,
            'Sachini Fernando',
            'sachini@example.com',
            'waitlisted',
            $staff
        );

        // 7. Workshop 3: an available workshop.
        $this->createRegistration(
            $workshop3,
            'Ravindu Silva',
            'ravindu@example.com',
            'active',
            $manager
        );

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    private function createRegistration(
        Workshop $workshop,
        string $name,
        string $email,
        string $status,
        User $registeredBy,
        array $extra = [],
    ): Registration {
        return Registration::updateOrCreate(
            [
                'workshop_id' => $workshop->id,
                'attendee_email' => $email,
            ],
            array_merge([
                'attendee_name' => $name,
                'status' => $status,
                'registered_by' => $registeredBy->id,
                'registered_at' => now()->subDays(3),
                'activated_at' => null,
                'activated_by' => null,
                'cancelled_by' => null,
                'cancelled_at' => null,
                'cancellation_reason' => null,
            ], $extra)
        );
    }
}
