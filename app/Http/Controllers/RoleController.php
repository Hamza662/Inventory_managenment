<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Support\PermissionTree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions', 'users')
            ->orderBy('name')
            ->get();

        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $groups = PermissionTree::groups();
        $permissionIds = Permission::where('guard_name', 'web')->pluck('id', 'name');

        return view('admin.roles.create', compact('groups', 'permissionIds'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')->where(fn ($q) => $q->where('guard_name', 'web')->whereNull('deleted_at')),
            ],
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
            'description' => $data['description'] ?? null,
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with([
            'message' => 'Role created successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function edit(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')->with([
                'message' => 'Super Admin role cannot be edited.',
                'alert-type' => 'warning',
            ]);
        }

        $groups = PermissionTree::groups();
        $permissionIds = Permission::where('guard_name', 'web')->pluck('id', 'name');
        $selected = $role->permissions->pluck('name')->all();

        return view('admin.roles.edit', compact('role', 'groups', 'permissionIds', 'selected'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')->with([
                'message' => 'Super Admin role cannot be updated.',
                'alert-type' => 'warning',
            ]);
        }

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'name')
                    ->ignore($role->id)
                    ->where(fn ($q) => $q->where('guard_name', 'web')->whereNull('deleted_at')),
            ],
            'description' => 'nullable|string|max:255',
            'permissions' => 'nullable|array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $role->syncPermissions($data['permissions'] ?? []);
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with([
            'message' => 'Role updated successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['Super Admin'], true)) {
            return redirect()->route('roles.index')->with([
                'message' => 'Super Admin role cannot be deleted.',
                'alert-type' => 'error',
            ]);
        }

        DB::transaction(function () use ($role) {
            $role->original_name = $role->name;
            $role->name = $role->name.'__deleted_'.$role->id.'_'.time();
            $role->save();
            $role->delete();
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with([
            'message' => 'Role moved to trash.',
            'alert-type' => 'success',
        ]);
    }

    public function trash()
    {
        $roles = Role::onlyTrashed()->orderByDesc('deleted_at')->get();

        return view('admin.roles.trash', compact('roles'));
    }

    public function restore($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);

        if ($role->original_name) {
            $exists = Role::where('name', $role->original_name)
                ->where('guard_name', 'web')
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                return redirect()->route('roles.trash')->with([
                    'message' => 'A role with the original name already exists. Rename or remove it first.',
                    'alert-type' => 'error',
                ]);
            }

            $role->name = $role->original_name;
            $role->original_name = null;
            $role->save();
        }

        $role->restore();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.trash')->with([
            'message' => 'Role restored successfully.',
            'alert-type' => 'success',
        ]);
    }

    public function forceDelete($id)
    {
        $role = Role::onlyTrashed()->findOrFail($id);
        $role->forceDelete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.trash')->with([
            'message' => 'Role permanently deleted.',
            'alert-type' => 'success',
        ]);
    }
}
