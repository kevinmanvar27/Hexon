<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\DataTables\RolesDataTable;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    
    public function index(RolesDataTable $dataTable)
    {
        return $dataTable->render('roles.index');
    }

    public function create(): View
    {
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[0]; // 'role-list' => 'role'
        });

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $permissionsID = array_map(function ($value) {
            return (int)$value;
        }, $request->input('permission'));

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show($id)
    {
        $role = $role = Role::with('permissions:id,name')->findOrFail($id);
    
        if (request()->ajax()) {
            return response()->json([
                'role' => $role,
                'permissions' => $role->permissions->toArray(),            
            ]);
        }
    
        return view('roles.show', [
            'role' => $role,
            'rolePermissions' => $role->permissions
        ]);
    }

    public function edit($id): View
    {
        $role = Role::findOrFail($id); // safer than find()
        $permissions = Permission::all()->groupBy(function ($perm) {
            return explode('-', $perm->name)[0]; // group by prefix like 'role', 'user'
        });

        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_id", $id)
            ->pluck('permission_id')
            ->toArray(); // simpler than pluck with keys

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $permissionsID = array_map(function ($value) {
            return (int)$value;
        }, $request->input('permission'));

        $role->syncPermissions($permissionsID);

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id', $id)->delete();
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}