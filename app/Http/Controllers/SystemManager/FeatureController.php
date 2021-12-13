<?php

namespace App\Http\Controllers\SystemManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Models\SystemManager\Feature;
use Exception;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){
        return view('system_manager.features_list.index');        
    }

    public function store(Request $request)
    {
        try {

            $feature = new Feature();
            $feature->title = $request->title;
            $feature->route = $request->route;
            $feature->sequence = $request->sequence;
            $feature->parent_id = $request->parent_id;
            $feature->is_active = $request->is_active;
            $feature->feature_type = $request->feature_type;
            $feature->menu_icon = $request->menu_icon;
            $feature->role_id = $request->role_id;
            $feature->save();

            $role = explode(",",$request->role_id);

            for ($i = 0; $i < sizeof($role); $i++) {
                DB::table("role_has_permission")->insert([
                    "feature_id" => $feature->id,
                    "role_id" => $role[$i],
                ]);
            }

            $response['message'] = 'Feature Added Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        } catch (Exception $error) {

            $response['message'] = 'Something went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
       
        
    }

    public function get_all_features()
    {
        $features =  Feature::orderby("f_id","desc")->get();  

        $response['message'] = 'Feature List';
        $response['status_code'] = 100;
        $response['success'] = true;
        $response['data'] = $features;
        return response()->json($response);

    }

    
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|unique:ac_features',
            'route' => 'required|unique:ac_features',
            'sequence' => 'required|unique:ac_features',
        ]);
        
        try {
            $fl = Feature::find($id);
            $fl->title = $request->title;
            $fl->route = $request->route;
            $fl->sequence = $request->sequence;
            $fl->save();

            return response()->json([
                'message' => 'Feature List Updated Successfully',
                'status' => 200,
                'success'=> true
            ]);
        } catch (Exception $error) {
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => 500,
                'success'=> false
            ]);
        }
        
    }


    public function get_feature_by_id($id) {
        return Feature::where("f_id", $id)->first();
    }

    public function update_feature(Request $request) {
        Feature::where("f_id",$request->id)->update([
            "title" => $request->title,
            "route" => $request->route,
            "sequence" => $request->sequence,
            "parent_id" => $request->parent_id,
            "is_active" => $request->is_active,
            "feature_type" => $request->feature_type,
            "menu_icon" => $request->menu_icon,
            "role_id" => $request->role_id,
        ]);

        $role = explode(",",$request->role_id);

        DB::table("role_has_permission")->where("feature_id",$request->id)->delete();

        for ($i = 0; $i < sizeof($role); $i++) {
            DB::table("role_has_permission")->insert([
                "feature_id" => $request->id,
                "role_id" => $role[$i],
            ]);
        }

        return response()->json([
            'message' => 'Feature Updated Successfully',
            'status' => 200,
            'success'=> true
        ]);
    }

}