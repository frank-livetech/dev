<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activitylog;
use Carbon\Carbon;
use Session;

class ActivitylogController extends Controller
{

    public function saveActivityLogs($module , $table_ref , $ref_id , $created_by , $action) {

        $log_data = array(
            "module" => $module , 
            "table_ref" => $table_ref , 
            "ref_id" => $ref_id ,
            "created_by" => $created_by ,
            "action_perform" => $action ,  
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        );

        Activitylog::create($log_data); 
    }
}
