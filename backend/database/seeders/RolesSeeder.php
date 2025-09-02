<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin     = Role::firstOrCreate(['name' => 'admin',     'guard_name' => 'api']);
        $organizer = Role::firstOrCreate(['name' => 'organizer', 'guard_name' => 'api']);
        $userRole  = Role::firstOrCreate(['name' => 'user',      'guard_name' => 'api']);

        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin', 'password' => bcrypt('secret123')]
        );

        $organizerUser = User::firstOrCreate(
            ['email' => 'organizer@example.com'],
            ['name' => 'Organizer', 'password' => bcrypt('secret123')]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'User', 'password' => bcrypt('secret123')]
        );

        $adminUser->syncRoles(['admin']);
        $organizerUser->syncRoles(['organizer']);
        $user->syncRoles(['user']);
    }
}
