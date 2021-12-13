<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Departments;
use App\Models\DepartmentPermissions;
use App\Models\DepartmentAssignments;
use Illuminate\Support\Facades\DB;
use Exception;

require 'vendor/autoload.php';

class DepartmentsController extends Controller
{
    private $permissions_list = [
        'd_t_canreply'=>'Reply to tickets', 
        'd_t_canforward'=>'Forward tickets', 
        'd_t_canfollowup'=>'Schedule ticket follow-ups', 
        'd_t_canbilling'=>'Time tracking and billing notes',
        'd_t_canassignment'=>'Ticket Assignment Email Alert',
        'd_t_cannotealerts'=>'Note Alerts',
        'd_t_cantktfollowalerts'=>'Ticket Followup Alerts'
    ];

    public function __construct() {
        $this->middleware('auth');
    }

    public function details($id) {
        $department = Departments::findOrFail($id);
        $dept_assignments = DepartmentAssignments::where('dept_id', $id)->get()->pluck('user_id')->toArray();

        $dept_permissions = DepartmentPermissions::where('dept_id', $id)->whereIn('user_id', $dept_assignments)->get()->toArray();
        
        $webmaster_new_per = ["d_t_notifications" => ["Project manager progress report notifications"]];

        $research = true;
        if(empty($dept_permissions)) {
            $research = false;
        }

        $roles = DB::table('roles')->where([ ['name', '!=', 'Vendor'], ['name', '!=', 'Customer']])->get()->pluck('id')->toArray();

        $users_with_permissions = User::whereIn('user_type', $roles)->where('is_deleted', 0)->get()->toArray();

        $permissions = [];
        foreach ($this->permissions_list as $key => $value) {
            $permissions[$key] = [$value, 0];
        }

        
        
        foreach ($users_with_permissions as $key => $value) {
            
            $users_with_permissions[$key]['permissions'] = $permissions;

            if($department->name == "Webmaster") {
                
                $combine = array_merge($permissions ,$webmaster_new_per);

                if(in_array($value['id'], $dept_assignments) ) {
                    array_push($combine['d_t_notifications'] , 1);
                }else{
                    array_push($combine['d_t_notifications'] , 0);
                }

                $users_with_permissions[$key]['permissions'] = $combine;
            }   

            if(in_array($value['id'], $dept_assignments)) {
                $users_with_permissions[$key]['assignment'] = 1;
            } else {
                $users_with_permissions[$key]['assignment'] = 0;
            }
        }

        if($research) {
            foreach ($users_with_permissions as $i => $user) {
                foreach ($dept_permissions as $j => $dept_p) {
                    if($user['id'] == $dept_p['user_id']) {
                        $users_with_permissions[$i]['permissions'][$dept_p['name']][1] = $dept_p['permitted'];
                    }
                }
            }
        }

        return view('system_manager.settings.department',compact('department', 'users_with_permissions'));
    }

    public function set_permissions(Request $request) {
        $data = $request->all();

        try {
            $permission = DepartmentPermissions::where([
                ['user_id', $request->user_id], ['dept_id', $request->dept_id], ['name', $request->name]
            ])->first();

            if(empty($permission)) {
                $data['updated_by'] = \Auth::user()->id;
                DepartmentPermissions::create($data);
            } else {
                $permission->permitted = $request->permitted;
                $permission->updated_by = \Auth::user()->id;
                $permission->save();
            }

            $response['message'] = 'Permission updated successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function set_assignments(Request $request) {
        $data = $request->all();

        try {
            if($request->assignment == 'set') {
                $data['updated_by'] = \Auth::user()->id;
                DepartmentAssignments::create($data);
            } else {
                DepartmentAssignments::where([
                    ['user_id', $request->user_id], ['dept_id', $request->dept_id]
                ])->delete();
            }

            $response['message'] = 'Assignment updated successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
}