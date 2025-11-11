<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // Create or update the admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'akartolga0@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('170104894'),
                'is_admin' => true,
            ]
        );

        // Assign the admin role to the user
        $adminUser->assignRole($adminRole);
    }
}
