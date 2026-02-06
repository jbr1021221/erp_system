<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:role-list', only: ['index']),
            new Middleware('permission:role-create', only: ['create', 'store']),
            new Middleware('permission:role-edit', only: ['edit', 'update']),
            new Middleware('permission:role-delete', only: ['destroy']),
        ];
    }

    function __construct()
    {
         // Constructor if needed
    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('id', 'DESC')->paginate(10);
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
    }

    public function create()
    {
        $permissions = Permission::get();
        
        // Group permissions by their prefix (e.g., 'student', 'payment')
        $permissionGroups = [];
        foreach($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $group = $parts[0];
            $permissionGroups[$group][] = $permission;
        }
        
        return view('roles.create', compact('permissionGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'nullable|array',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        
        if($request->has('permission')) {
            $role->syncPermissions($request->input('permission'));
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        // Group permissions
        $permissionGroups = [];
        foreach($permissions as $permission) {
            $parts = explode('-', $permission->name);
            $group = $parts[0];
            $permissionGroups[$group][] = $permission;
        }

        return view('roles.edit', compact('role', 'permissionGroups', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permission' => 'nullable|array',
        ]);

        $role = Role::find($id);
        
        if($role->name == 'Super Admin') {
             return redirect()->route('roles.index')
            ->with('error', 'Cannot edit Super Admin role name.');
        }
        
        $role->name = $request->input('name');
        $role->save();

        if($request->has('permission')) {
            $role->syncPermissions($request->input('permission'));
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = Role::find($id);
        
        if($role->name == 'Super Admin') {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete Super Admin role.');
        }
        
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
