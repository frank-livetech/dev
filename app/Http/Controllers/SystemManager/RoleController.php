<?php

namespace App\Http\Controllers\SystemManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        return view('system_manager.roles_management.index');
    }

    public function create()
    {
        return Role::all();
        // $permission = Permission::get();
        // return view('system_manager.roles_management.create',compact('permission'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name'
        ]);


        $role = Role::create(['name' => $request->input('name')]);

        $response['message'] = 'Role created successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }


    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();


        return view('system_manager.roles_management.show',compact('role','rolePermissions'));
    }


    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();


        return view('system_manager.roles_management.edit',compact('role','permission','rolePermissions')); 
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);


        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $response['message'] = 'Role updated successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);


        $role->syncPermissions($request->input('permission'));
        // $role->syncPermissions($request->input('permission'));


        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
        // return redirect()->route('roles.index')
        //                 ->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        $response['message'] = 'Role deleted successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
        // return redirect()->route('system_manager.roles_management.index')
        //                 ->with('success','Role deleted successfully');
    }


    public function test() {
        return view('test.test');
    }
}