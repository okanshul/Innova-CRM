<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
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

    public function index()
    {
        Gate::authorize('roles.view');

        $roles = Role::with('permissions')->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        Gate::authorize('roles.create');

        $groupedPermissions = $this->getGroupedPermissions();

        return view('roles.create', compact('groupedPermissions'));
    }

    public function show($id)
    {
        Gate::authorize('roles.view');

        $role = Role::with('permissions')->findOrFail($id);

        return view('roles.show', compact('role'));
    }

    public function edit($id)
    {
        Gate::authorize('roles.edit');

        $role = Role::with('permissions')->findOrFail($id);
        $groupedPermissions = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }
}
