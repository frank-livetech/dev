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
use App\Http\Controllers\SystemManager\MailController;
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

        $clock_in = new StaffAttendance;
        $clock_in->user_id = \Auth::user()->id;
        $clock_in->clock_in = Carbon::now();
        $clock_in->date = date_format(Carbon::now(), "Y-m-d");
        $clock_in->save();

        session()->put('clockin', 'no');
        session()->put('clockin_time', now() );

        $template = DB::table("templates")->where('code','staff_clockin')->first();
                        $notify = new NotifyController();
        $users_list = User::where([ ['user_type',1] , ['is_deleted',0] ])->get();

        foreach ($users_list as $key => $value) {

            $sender_id = \Auth::user()->id;
            $receiver_id = $value['id'];
            $slug = 'dashboard';
            $type = 'attendance';
            $data = 'data';
            $title = 'Clock In';
            $icon = 'ti-calendar';
            $class = 'btn-success';
            $desc = 'Clock In by '.\Auth::user()->name;
            $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);

            if(!empty($template)) {

                $detail = $value['email'] != auth()->user()->email ? 
                    'Hi ' . auth()->user()->name . ', Staff member ' . $value['name'] . ' just clocked in' : 
                    'Hi you just clock in into LT-CMS, here are the details';
                
                $temp = $this->templateReplaceShortCodes($template->template_html ,$detail, 'clockin' , 0);
                $mail = new MailController();
                $mail->sendMail( auth()->user()->name . ' Clock in' , $temp , 'system_notification@mylive-tech.com', $value['email'] , $value['name']);
            }
        }

        $response['message'] = 'Clocked in!';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['clock_in_time'] = Carbon::now();
        return response()->json($response);
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

            session()->put('clockin', 'yes');
            session()->put('clockin_time', now() );
            
            $get_tsk_lst = Tasks::where('task_status','default')->where('assign_to', \Auth::user()->id)->get();

            $template = DB::table("templates")->where('code','staff_clockin')->first();

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
                
                $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                if(!empty($template)) {

                    $detail = $value['email'] != auth()->user()->email ? 
                    'Hi ' . auth()->user()->name . ', Staff member ' . $value['name'] . ' just clocked out' : 
                    'Hi you just clock out into LT-CMS, here are the details';
                    
                    $temp = $this->templateReplaceShortCodes($template->template_html, $detail , 'clockout' , $clock_in->hours_worked);
                    $mail = new MailController();
                    $mail->sendMail( auth()->user()->name .' Clock out' , $temp , 'system_notification@mylive-tech.com', $value['email'], $value['name']);
                }
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

    public function templateReplaceShortCodes($template_html , $detail,  $type , $totalWorkingHour) {

        $template = htmlentities($template_html);

        if(str_contains($template, '{Staff-name}')) {
            $template = str_replace('{Staff-name}', $detail , $template);
        }

        if(str_contains($template, '{current_date}')) {
            $system_format = DB::table("sys_settings")->where('sys_key','sys_dt_frmt')->first();
            $date_format = empty($system_format) ? 'DD-MM-YYYY' : $system_format->sys_value;

            $todayDateTime = new Carbon( now() , timeZone() );
            $fr = $this->convertFormat($date_format);

            $template = str_replace('{current_date}', $todayDateTime->format($fr) , $template);
        }

        if(str_contains($template, '{current_time}')) {
            $todayDateTime = new Carbon( now() , timeZone() );
            $time = $type == 'clockin' ? '<strong>Clock in time:  </strong>' : '<strong>Clock out time:  </strong>';
            $template = str_replace('{current_time}', $time . $todayDateTime->format('h:i A') , $template);
        }

        if($type == 'clockin') {
            if(str_contains($template, '{Worked_hours}')) {
                $template = str_replace('{Worked_hours}','', $template);
                $template = str_replace('Worked hours:','', $template);
            }
        }else{
            if(str_contains($template, '{Worked_hours}')) {
                $template = str_replace('{Worked_hours}', $totalWorkingHour , $template);
            } 
        }

        return html_entity_decode($template);
    }

    function convertFormat($format) {

        $replacements = [
            'DD'   => 'd', 
            'ddd'  => 'D', 
            'D'    => 'j', 
            'dddd' => 'l', 
            'E'    => 'N', 
            'o'    => 'S',
            'e'    => 'w', 
            'DDD'  => 'z', 
            'W'    => 'W', 
            'MMMM' => 'F', 
            'MM'   => 'm', 
            'MMM'  => 'M',
            'M'    => 'n', 
            'YYYY' => 'Y', 
            'YY'   => 'y', 
            'a'    => 'a', 
            'A'    => 'A', 
            'h'    => 'g',
            'H'    => 'G', 
            'hh'   => 'h', 
            'HH'   => 'H', 
            'mm'   => 'i', 
            'ss'   => 's', 
            'SSS'  => 'u',
            'zz'   => 'e', 'X'    => 'U',
        ];

        $phpFormat = strtr($format, $replacements);
        return $phpFormat;
    }

    function clockInSession(Request $request) {

        if($request->type == 'yes') {
            $this->clockin();
        }

        session()->put('clockin', $request->type);
        session()->put('clockin_time', now() );

        return response()->json([
            "status" => 200 , 
            "success" => true , 
            "message" => "Clocked in Successfully",
        ]);
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