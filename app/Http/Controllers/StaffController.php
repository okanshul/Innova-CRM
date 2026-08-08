<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
        
        $roles = Role::all();
        $groupedPermissions = $this->getGroupedPermissions();

        return view('staff.create', compact('roles', 'groupedPermissions'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('staff.create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|string|exists:roles,name',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'joined_date' => 'nullable|date',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? 'Sales',
            'position' => $validated['position'] ?? null,
            'status' => $validated['status'] ?? 'active',
            'joined_date' => $validated['joined_date'] ?? now()->toDateString(),
        ]);

        $user->assignRole($validated['role']);

        if ($request->has('permissions')) {
            $user->syncPermissions($request->input('permissions', []));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff member created successfully.',
                'redirect' => route('staff.index')
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully.');
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
        $roles = Role::all();
        $groupedPermissions = $this->getGroupedPermissions();
        $directPermissions = $staff->getDirectPermissions()->pluck('name')->toArray();
        $rolePermissions = $staff->getPermissionsViaRoles()->pluck('name')->unique()->toArray();

        return view('staff.edit', compact('staff', 'roles', 'groupedPermissions', 'directPermissions', 'rolePermissions'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('staff.edit');

        $staff = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($staff->id)],
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|string|exists:roles,name',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'joined_date' => 'nullable|date',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'department' => $validated['department'] ?? $staff->department,
            'position' => $validated['position'] ?? null,
            'status' => $validated['status'],
            'joined_date' => $validated['joined_date'] ?? $staff->joined_date,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staff->update($updateData);
        $staff->syncRoles([$validated['role']]);

        if ($request->has('permissions')) {
            $staff->syncPermissions($request->input('permissions', []));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Staff member updated successfully.',
                'redirect' => route('staff.index')
            ]);
        }

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy($id)
    {
        Gate::authorize('staff.delete');

        $staff = User::findOrFail($id);
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
    }
}
