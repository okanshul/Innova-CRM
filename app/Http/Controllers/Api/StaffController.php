<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Http\Requests\UpdateStaffPermissionsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('staff.view');

        $query = User::with('roles');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('department')) {
            $query->where('department', $request->get('department'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $perPage = $request->get('per_page', setting('items_per_page', 10));
        $staff = $query->orderBy('id', 'asc')->paginate($perPage);

        // Map roles for the frontend
        $staff->getCollection()->transform(function ($user) {
            $user->role_name = $user->roles->first()->name ?? 'staff';
            return $user;
        });

        return response()->json([
            'success' => true,
            'data' => $staff
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/avatars');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        $user = User::create($data);
        $user->assignRole($request->role);

        if ($permissions !== null) {
            $user->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff created successfully.',
            'data' => $user,
            'redirect' => route('staff.index')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        Gate::authorize('staff.view');
        
        $user = User::with('roles')->findOrFail($id);
        $user->role_name = $user->roles->first()->name ?? '';

        return response()->json([
            'success' => true,
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStaffRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $data = $request->validated();

        $permissions = $data['permissions'] ?? null;
        unset($data['permissions']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                if (file_exists(public_path($user->avatar))) {
                    @unlink(public_path($user->avatar));
                } elseif (Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            $file = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $uploadPath = public_path('uploads/avatars');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $file->move($uploadPath, $filename);
            $data['avatar'] = 'uploads/avatars/' . $filename;
        }

        $user->update($data);
        $user->syncRoles([$request->role]);

        if ($permissions !== null) {
            $user->syncPermissions($permissions);
        }

        return response()->json([
            'success' => true,
            'message' => 'Staff updated successfully.',
            'data' => $user,
            'redirect' => route('staff.index')
        ]);
    }

    /**
     * Get direct and role-inherited permissions for the specified staff member.
     */
    public function getPermissions(string $id)
    {
        Gate::authorize('staff.edit');

        $user = User::with('roles')->findOrFail($id);
        $user->role_name = $user->roles->first()->name ?? 'staff';

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

        $directPermissions = $user->getDirectPermissions()->pluck('name')->values()->all();
        $rolePermissions = $user->getPermissionsViaRoles()->pluck('name')->unique()->values()->all();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role_name' => $user->role_name
                ],
                'grouped_permissions' => $groupedPermissions,
                'direct_permissions' => $directPermissions,
                'role_permissions' => $rolePermissions,
            ]
        ]);
    }

    /**
     * Update direct permissions for the specified staff member.
     */
    public function updatePermissions(UpdateStaffPermissionsRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        
        $user->syncPermissions($request->permissions ?? []);

        return response()->json([
            'success' => true,
            'message' => "Permissions updated successfully for {$user->name}.",
            'data' => [
                'direct_permissions' => $user->getDirectPermissions()->pluck('name')->values()->all()
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Gate::authorize('staff.delete');
        
        $user = User::findOrFail($id);
        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Staff deleted successfully.'
        ]);
    }

    public function bulkDestroy(Request $request)
    {
        Gate::authorize('staff.delete');

        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $validated['ids'])->get();
        foreach ($users as $user) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->delete();
        }

        return response()->json([
            'success' => true,
            'message' => count($validated['ids']) . ' staff members deleted successfully.'
        ]);
    }
}
