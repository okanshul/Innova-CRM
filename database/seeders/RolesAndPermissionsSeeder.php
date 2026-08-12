<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all module permissions
        $permissions = [
            'staff.view', 'staff.create', 'staff.edit', 'staff.delete',
            'contacts.view', 'contacts.create', 'contacts.edit', 'contacts.delete',
            'deals.view', 'deals.create', 'deals.edit', 'deals.delete',
            'pipeline.view', 'pipeline.create', 'pipeline.edit', 'pipeline.delete',
            'reports.view',
            'tasks.view', 'tasks.create', 'tasks.edit', 'tasks.delete',
            'calendar.view', 'calendar.create', 'calendar.edit', 'calendar.delete',
            'meetings.view', 'meetings.create', 'meetings.edit', 'meetings.delete',
            'mail.view', 'mail.create',
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            'settings.view', 'settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // create or update roles and assign created permissions
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleAdmin->syncPermissions(Permission::all());

        $roleManager = Role::firstOrCreate(['name' => 'manager']);
        $roleManager->syncPermissions([
            'staff.view', 'staff.create', 'staff.edit',
            'contacts.view', 'contacts.create', 'contacts.edit',
            'deals.view', 'deals.create', 'deals.edit',
            'pipeline.view', 'pipeline.edit',
            'reports.view',
            'tasks.view', 'tasks.create', 'tasks.edit',
            'calendar.view', 'calendar.create', 'calendar.edit',
            'meetings.view', 'meetings.create', 'meetings.edit',
            'mail.view', 'mail.create',
            'settings.view'
        ]);

        $roleStaff = Role::firstOrCreate(['name' => 'staff']);
        $roleStaff->syncPermissions([
            'staff.view',
            'contacts.view',
            'deals.view',
            'tasks.view',
            'calendar.view',
            'meetings.view',
            'mail.view'
        ]);
    }
}
