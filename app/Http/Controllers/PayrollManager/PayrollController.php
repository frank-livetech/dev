<?php

namespace App\Http\Controllers\PayrollManager;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Models\StaffAttendance;
use App\Models\Tasks;
use App\Models\SystemSetting;
use Validator;
use Throwable;
use App\Models\Notification;
use App\Http\Controllers\NotifyController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use DB;
use Exception;
use SystemSettings;

class PayrollController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function clockin() {
        try{
            $clock_in = new StaffAttendance;
            $clock_in->user_id = \Auth::user()->id;
            $clock_in->clock_in = Carbon::now();
            $clock_in->date = date_format(Carbon::now(), "Y-m-d");
            $clock_in->save();
    
            $notify = new NotifyController();
            $users_list = User::where('user_type','=',1)->where('is_deleted',0)->get();
            foreach ($users_list as $key => $value) {
                // $allwd_users[] = [$value['email'], $value['name']];
                $sender_id = \Auth::user()->id;
                $receiver_id = $value['id'];
                $slug = 'dashboard';
                $type = 'attendance';
                $data = 'data';
                $title = 'Clock In';
                $icon = 'ti-calendar';
                $class = 'btn-success';
                $desc = 'Clock In by '.\Auth::user()->name;
                
                // try{
                    $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                // }catch(Exception $e) {
                    // $response['message'] = 'Clocked in! Notification Failure';
                    // $response['message'] = $e->getMessage();
                    // $response['status_code'] = 201;
                    // $response['success'] = true;
                    // $response['clock_in_time'] = Carbon::now();
                    // return response()->json($response);
                // }
            }
    
            $response['message'] = 'Clocked in!';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['clock_in_time'] = Carbon::now();
            return response()->json($response);
        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            $response['clock_in_time'] = '';
            return response()->json($response);
        }
    }

    public function clockout() {
        try{
            $clock_in = StaffAttendance::where('user_id',\Auth::user()->id)->where('clock_out', NULL)->orderBy('created_at', 'desc')->first();
            
            $clock_in->clock_out = Carbon::now();
            $startTime = Carbon::parse($clock_in->clock_in);
            $totalDuration = (array) $clock_in->clock_out->diff($startTime);
    
            $clock_in->hours_worked = sprintf("%02s:%02s:%02s", ($totalDuration['d']*24)+$totalDuration['h'], $totalDuration['i'], $totalDuration['s']);
            $clock_in->clocked_out_by = 'user';
    
            $clock_in->save();
            
            $get_tsk_lst = Tasks::where('task_status','default')->where('assign_to', \Auth::user()->id)->get();
            
            foreach($get_tsk_lst as $task){
    
                $strt_time =  $task->started_at; 
                $wrk_time = $task->worked_time;
          
                $end    = Carbon::now();
                $startTime = Carbon::parse($strt_time);
                $endTime = Carbon::parse($end);
          
                $total_sec = $startTime->diffInSeconds($endTime)  + $wrk_time;
    
                $task->task_status = 'danger';
                $task->worked_time = $total_sec;
                $task->save();
                
            }
    
            $notify = new NotifyController();
            $users_list = User::where('user_type','=',1)->where('is_deleted',0)->get();
            foreach ($users_list as $key => $value) {
                $sender_id = \Auth::user()->id;
                $receiver_id = $value['id'];
                $slug = 'dashboard';
                $type = 'attendance';
                $data = 'data';
                $title = 'Clock Out';
                $icon = 'ti-calendar';
                $class = 'btn-success';
                $desc = 'Clock Out by '.\Auth::user()->name;
                
                // try{
                    $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                // }catch(Exception $e) {
                    // $response['message'] = 'Clocked out! Your shift time is '.$clock_in->hours_worked.' Notification Failure!';
                    // $response['message'] = $e->getMessage();
                    // $response['status_code'] = 201;
                    // $response['success'] = true;
                    // $response['clock_in_time'] = $startTime;
                    // $response['clock_out_time'] = Carbon::now();
                    // $response['worked_time'] = $clock_in->hours_worked;
                    // return response()->json($response);
                // }
            }
    
            $response['message'] = 'Clocked out! Your shift time is '.$clock_in->hours_worked;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['clock_in_time'] = $startTime;
            $response['clock_out_time'] = Carbon::now();
            $response['worked_time'] = $clock_in->hours_worked;
    
            return response()->json($response);
        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            $response['clock_in_time'] = '';
            $response['clock_out_time'] = '';
            return response()->json($response);
        }
    }

    public function check_clockins() {
        try {
            $clock_ins = StaffAttendance::where('clock_out', NULL)->orderBy('created_at', 'desc')->get();

            $clockout_time = Carbon::now();

            foreach ($clock_ins as $key => $value) {
                $startTime = Carbon::parse($value->clock_in);
                $totalDuration = (array) $clockout_time->diff($startTime);

                if($totalDuration['h'] > 8 || $totalDuration['d'] > 0 || $totalDuration['m'] > 0 || $totalDuration['y'] > 0) {
                    
                    $value->clock_out = $clockout_time;
                    $value->hours_worked = sprintf("%02s:%02s:%02s", ($totalDuration['d']*24)+$totalDuration['h'], $totalDuration['i'], $totalDuration['s']);
                    $value->clocked_out_by = 'cron';
                    $value->save();

                    $user = User::where('id', $value->user_id)->first();

                    echo 'Clocked out "'.$user->name.'" after '.$value->hours_worked;
                    
                    $get_tsk_lst = Tasks::where('task_status','default')->where('assign_to', $value->user_id)->get();
                    
                    foreach($get_tsk_lst as $task){
            
                        $strt_time =  $task->started_at; 
                        $wrk_time = $task->worked_time;
                  
                        $end    = Carbon::now();
                        $startTime = Carbon::parse($strt_time);
                        $endTime = Carbon::parse($end);
                  
                        $total_sec = $startTime->diffInSeconds($endTime)  + $wrk_time;
            
                        $task->task_status = 'danger';
                        $task->worked_time = $total_sec;
                        $task->save();
                        
                    }
                
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function checkActiveTasksWithClockIn() {
        try {
            $clock_ins = StaffAttendance::where('clock_out', NULL)->orderBy('created_at', 'desc')->get();

            $clockout_time = Carbon::now();

            foreach ($clock_ins as $key => $value) {
                $startTime = Carbon::parse($value->clock_in);
                $totalDuration = (array) $clockout_time->diff($startTime);
                $workingTaskCount = Tasks::where('is_deleted',0)->where('assign_to', $value->user_id)->where('task_status', 'default')->get()->count();
  
                if($workingTaskCount  < 1) {
                    $value->clock_out = $clockout_time;
                    $value->hours_worked = sprintf("%02s:%02s:%02s", ($totalDuration['d']*24)+$totalDuration['h'], $totalDuration['i'], $totalDuration['s']);
                    $value->clocked_out_by = 'cron';
                    $value->save();

                    $user = User::where('id', $value->user_id)->first();

                    echo 'Clocked out "'.$user->name.'" after '.$value->hours_worked;
                }
            }
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function save_payroll_settings(Request $request) {
        try {
            $data = $request->all();
            foreach ($data as $key => $value) {
                $setting = SystemSetting::where('sys_key', $key)->first();
                if(!empty($setting)) {
                    $setting->sys_value = $value;
                    $setting->save();
                } else {
                    SystemSetting::create([
                        'sys_key' => $key,
                        'sys_value' => $value
                    ]);
                }
            }

            $response['message'] = 'Settings saved successfully!';
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
    
    public function update_work_hours(Request $request) {
        try {
            $data = StaffAttendance::findOrFail($request->id);
            $data->hours_worked = $request->worked_hours_value;
            $data->updated_at = Carbon::now();
            $data->save();

            $response['message'] = 'Work Hours updated successfully!';
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