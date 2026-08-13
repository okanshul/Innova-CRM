<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('roles.view');

        $query = Role::with('permissions');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $perPage = $request->get('per_page', 10);
        $roles = $query->orderBy('id', 'asc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('roles.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role created successfully.',
            'data' => $role,
            'redirect' => route('roles.index')
        ], 201);
    }

    public function show(string $id)
    {
        Gate::authorize('roles.view');

        $role = Role::with('permissions')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $role
        ]);
    }

    public function update(Request $request, string $id)
    {
        Gate::authorize('roles.edit');

        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
        ]);

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully.',
            'data' => $role,
            'redirect' => route('roles.index')
        ]);
    }

    public function destroy(string $id)
    {
        Gate::authorize('roles.delete');

        $role = Role::findOrFail($id);
        
        if ($role->name === 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'The Admin role cannot be deleted.'
            ], 422);
        }

        $role->delete();

        return response()->json([
            'success' => true,
            'message' => 'Role deleted successfully.'
        ]);
    }
}
