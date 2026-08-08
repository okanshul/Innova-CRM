<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure admin role exists
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create or update admin user 'anshul'
        $user = User::updateOrCreate(
            ['email' => 'anshul@admin.com'],
            [
                'name' => 'anshul',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'position' => 'Administrator',
                'department' => 'Management',
            ]
        );

        $user->assignRole($adminRole);

        $this->command->info('Admin user anshul created successfully!');
    }
}
