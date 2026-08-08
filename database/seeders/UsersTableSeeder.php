<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure roles exist
        $roleManager = Role::firstOrCreate(['name' => 'manager']);
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);

        // Create 20 fake users
        $users = User::factory(20)->create();

        // Assign roles randomly
        foreach ($users as $index => $user) {
            if ($index < 4) {
                $user->assignRole($roleManager);
            } else {
                $user->assignRole($roleStaff);
            }
        }

        $this->command->info('20 users added successfully to users table!');
    }
}
