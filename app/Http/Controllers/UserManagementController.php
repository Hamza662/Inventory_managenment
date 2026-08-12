<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Support\PermissionTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\PermissionRegistrar;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'user_name' => 'required|string|max:80|unique:users,user_name',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:40',
            'password' => 'required|string|min:6|confirmed',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,agent,user',
            'spatie_role' => 'nullable|string|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => $data['status'],
            'role' => $data['role'],
            'email_verified_at' => now(),
        ]);

        if (! empty($data['spatie_role'])) {
            $user->syncRoles([$data['spatie_role']]);
        }

        return redirect()->route('users.index')->with([
            'message' => 'User created successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $selectedRole = $user->roles->pluck('name')->first();

        return view('admin.users.edit', compact('user', 'roles', 'selectedRole'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'user_name' => ['required', 'string', 'max:80', Rule::unique('users', 'user_name')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:40',
            'password' => 'nullable|string|min:6|confirmed',
            'status' => 'required|in:active,inactive',
            'role' => 'required|in:admin,agent,user',
            'spatie_role' => 'nullable|string|exists:roles,name',
        ]);

        $user->update([
            'name' => $data['name'],
            'user_name' => $data['user_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'status' => $data['status'],
            'role' => $data['role'],
        ]);

        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }

        if (! empty($data['spatie_role'])) {
            $user->syncRoles([$data['spatie_role']]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with([
            'message' => 'User updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with([
                'message' => 'You cannot delete your own account.',
                'alert-type' => 'error',
            ]);
        }

        if ($user->hasRole('Super Admin')) {
            return redirect()->route('users.index')->with([
                'message' => 'Super Admin user cannot be deleted.',
                'alert-type' => 'error',
            ]);
        }

        $user->delete();

        return redirect()->route('users.index')->with([
            'message' => 'User moved to trash.',
            'alert-type' => 'success',
        ]);
    }

    public function trash()
    {
        $users = User::onlyTrashed()->orderByDesc('deleted_at')->get();

        return view('admin.users.trash', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.trash')->with([
            'message' => 'User restored successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);

        if ($user->hasRole('Super Admin')) {
            return redirect()->route('users.trash')->with([
                'message' => 'Super Admin user cannot be permanently deleted.',
                'alert-type' => 'error',
            ]);
        }

        $user->forceDelete();

        return redirect()->route('users.trash')->with([
            'message' => 'User permanently deleted.',
            'alert-type' => 'success',
        ]);
    }

    public function permissions(User $user)
    {
        $groups = PermissionTree::groups();
        $selected = $user->getDirectPermissions()->pluck('name')->all();
        $viaRoles = $user->getPermissionsViaRoles()->pluck('name')->unique()->values()->all();

        return view('admin.users.permissions', compact('user', 'groups', 'selected', 'viaRoles'));
    }

    public function syncPermissions(Request $request, User $user)
    {
        $data = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->syncPermissions($data['permissions'] ?? []);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('users.index')->with([
            'message' => 'Permissions updated for '.$user->name.'.',
            'alert-type' => 'success',
        ]);
    }
}
