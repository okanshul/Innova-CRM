<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    /**
     * Helper to group permissions by module prefix.
     */
    private function getGroupedPermissions()
    {
        $allPermissions = Permission::all();
        $groupedPermissions = [];
        foreach ($allPermissions as $perm) {
            $parts = explode('.', $perm->name);
            $group = $parts[0] ?? 'general';
            if (!isset($groupedPermissions[$group])) {
                $groupedPermissions[$group] = [];
            }
            $groupedPermissions[$group][] = $perm->name;
        }
        return $groupedPermissions;
    }

    /**
     * Display the staff listing view.
     */
    public function index()
    {
        Gate::authorize('staff.view');
        
        $groupedPermissions = $this->getGroupedPermissions();

        return view('staff.index', compact('groupedPermissions'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        Gate::authorize('staff.create');
        
        $roles = Role::with('permissions')->orderBy('name', 'asc')->get();
        $groupedPermissions = $this->getGroupedPermissions();

        $rolesPermissionsMap = [];
        foreach ($roles as $role) {
            $rolesPermissionsMap[$role->name] = $role->permissions->pluck('name')->toArray();
        }

        $defaultRoleName = old('role', 'staff');
        if (!isset($rolesPermissionsMap[$defaultRoleName]) && $roles->isNotEmpty()) {
            $defaultRoleName = $roles->first()->name;
        }
        $rolePermissions = $rolesPermissionsMap[$defaultRoleName] ?? [];

        return view('staff.create', compact('roles', 'groupedPermissions', 'rolePermissions', 'rolesPermissionsMap'));
    }

    /**
     * Display the specified staff member details.
     */
    public function show($id)
    {
        Gate::authorize('staff.view');

        $staff = User::findOrFail($id);
        $staff->role_name = $staff->roles->first()?->name ?? 'staff';

        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit($id)
    {
        Gate::authorize('staff.edit');

        $staff = User::findOrFail($id);
        $staff->role_name = $staff->roles->first()?->name ?? 'staff';
        $roles = Role::with('permissions')->orderBy('name', 'asc')->get();
        $groupedPermissions = $this->getGroupedPermissions();
        $directPermissions = $staff->getDirectPermissions()->pluck('name')->toArray();
        $rolePermissions = $staff->getPermissionsViaRoles()->pluck('name')->unique()->toArray();

        $rolesPermissionsMap = [];
        foreach ($roles as $role) {
            $rolesPermissionsMap[$role->name] = $role->permissions->pluck('name')->toArray();
        }

        return view('staff.edit', compact('staff', 'roles', 'groupedPermissions', 'directPermissions', 'rolePermissions', 'rolesPermissionsMap'));
    }
}
