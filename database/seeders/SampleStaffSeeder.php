<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SampleStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleManager = Role::firstOrCreate(['name' => 'manager']);
        $roleStaff = Role::firstOrCreate(['name' => 'staff']);

        $staffData = [
            ['name' => 'Michael Smith', 'email' => 'michael.smith@innovacrm.com', 'department' => 'Sales', 'position' => 'Sales Manager', 'phone' => '+1 (555) 123-4567', 'status' => 'active', 'joined_date' => '2024-05-25', 'role' => $roleManager],
            ['name' => 'Sarah Johnson', 'email' => 'sarah.johnson@innovacrm.com', 'department' => 'Marketing', 'position' => 'Marketing Lead', 'phone' => '+1 (555) 234-5678', 'status' => 'active', 'joined_date' => '2024-05-24', 'role' => $roleManager],
            ['name' => 'David Wilson', 'email' => 'david.wilson@innovacrm.com', 'department' => 'Sales', 'position' => 'Sales Executive', 'phone' => '+1 (555) 345-6789', 'status' => 'active', 'joined_date' => '2024-05-23', 'role' => $roleStaff],
            ['name' => 'Emily Brown', 'email' => 'emily.brown@innovacrm.com', 'department' => 'Customer Support', 'position' => 'Support Agent', 'phone' => '+1 (555) 456-7890', 'status' => 'active', 'joined_date' => '2024-05-22', 'role' => $roleStaff],
            ['name' => 'James Taylor', 'email' => 'james.taylor@innovacrm.com', 'department' => 'Finance', 'position' => 'Accountant', 'phone' => '+1 (555) 567-8901', 'status' => 'inactive', 'joined_date' => '2024-05-20', 'role' => $roleStaff],
            ['name' => 'Olivia Martinez', 'email' => 'olivia.martinez@innovacrm.com', 'department' => 'Marketing', 'position' => 'Content Writer', 'phone' => '+1 (555) 678-9012', 'status' => 'active', 'joined_date' => '2024-05-18', 'role' => $roleStaff],
            ['name' => 'Daniel Anderson', 'email' => 'daniel.anderson@innovacrm.com', 'department' => 'IT', 'position' => 'System Admin', 'phone' => '+1 (555) 789-0123', 'status' => 'active', 'joined_date' => '2024-05-15', 'role' => $roleStaff],
            ['name' => 'Sophia Thomas', 'email' => 'sophia.thomas@innovacrm.com', 'department' => 'Customer Support', 'position' => 'Support Lead', 'phone' => '+1 (555) 890-1234', 'status' => 'inactive', 'joined_date' => '2024-05-10', 'role' => $roleManager],
        ];

        foreach ($staffData as $item) {
            $role = $item['role'];
            unset($item['role']);

            $user = User::updateOrCreate(
                ['email' => $item['email']],
                array_merge($item, ['password' => Hash::make('password123')])
            );

            $user->syncRoles([$role]);
        }
    }
}
