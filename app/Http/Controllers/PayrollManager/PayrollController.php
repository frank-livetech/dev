<?php

namespace App\Http\Controllers\PayrollManager;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use App\Models\{StaffAttendance,Tasks,SystemSetting,Notification,Tickets, TicketStatus};
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\SystemManager\MailController;
use Carbon\Carbon;
use DB;
use Session;
use Exception;
use SystemSettings;

class PayrollController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function clockin() {

        // return Tickets::where([ 
        //     ['assigned_to', auth()->id()], 
        //     ['is_deleted', 0] ,
        //     ['is_overdue', 0] ,
        //     ['trashed', 0] 
        // ])->whereDate('created_at', Carbon::today())->get();

        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');

        $staffData = StaffAttendance::where([ ['clock_out', null], ['user_id',auth()->user()->id] ])->orderByDesc('id')->first();

        $clock_in_arr = [
            "user_id" => auth()->id(),
            "date" => date_format(Carbon::now() , "Y-m-d"),
            "clock_in" => Carbon::now(),
        ];

        if(!empty($staffData)) {

            // clockout user
            $staffData->clock_out = Carbon::now();
            $startTime = Carbon::parse($staffData->clock_in);
            $totalDuration = (array) $staffData->clock_out->diff($startTime);
            $staffData->hours_worked = sprintf("%02s:%02s:%02s", ($totalDuration['d']*24)+$totalDuration['h'], $totalDuration['i'], $totalDuration['s']);
            $staffData->clocked_out_by = 'user';
            $staffData->save();

            // after clockout again clock in 
            StaffAttendance::create($clock_in_arr);

        }else{
            StaffAttendance::create($clock_in_arr);
        }

        Session::put('clockin', 1);
        Session::put('clockin_time', now());
        Session::put('staff_data', $staffData );

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
                    'Hi ' . $value['name'] . ', Staff member ' . auth()->user()->name . ' just clocked in' : 
                    'Hey, you just clocked in into LT-CMS, here are the details';
                
                $temp = $this->templateReplaceShortCodes($template->template_html ,$detail, 'clockin' , 0);
                $mail = new MailController();
                $mail->sendMail( auth()->user()->name . ' Clock in' , $temp , 'system_notification@mylive-tech.com', $value['email'] , $value['name']);
            }
        }

        $staff_att_data = $this->getAllStaffData();
        $response['staff_att_data'] = $staff_att_data;

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

            Session::put('clockin',0);
            Session::put('clockin_time', null );
            Session::put('staff_data', null );
            
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
                    'Hi ' . $value['name'] . ', Staff member ' . auth()->user()->name . ' just clocked out' : 
                    'Hey, you just clocked out from LT-CMS, here are the details';
                    
                    $temp = $this->templateReplaceShortCodes($template->template_html, $detail , 'clockout' , $clock_in->hours_worked);
                    $mail = new MailController();
                    $mail->sendMail( auth()->user()->name .' Clock out' , $temp , 'system_notification@mylive-tech.com', $value['email'], $value['name']);
                }
            }
    
            $staff_att_data = $this->getAllStaffData();

            $response['message'] = 'Clocked out! Your shift time is '.$clock_in->hours_worked;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['clock_in_time'] = $startTime;
            $response['clock_out_time'] = Carbon::now();
            $response['worked_time'] = $clock_in->hours_worked;
            $response['staff_att_data'] = $staff_att_data;
    
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

    public function getAllStaffData(){

        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $staff_att_data = array();
        foreach($users as $user){
            // $staffData = StaffAttendance::where('user_id',$user->id)->where('date','>=',date_format(Carbon::yesterday(),"Y-m-d"))->orderByDesc('id')->first();
            $staffData = StaffAttendance::where('user_id',$user->id)->orderByDesc('id')->first();
            if($staffData){
                $staffData->name = $user->name;
                array_push($staff_att_data,$staffData);
            }
            
        }

        return $staff_att_data;
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

            if(str_contains($template, '{New-Tickets}')) {
                
                $todayTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['is_overdue', 0] ,
                    ['trashed', 0] 
                ])->whereDate('created_at', Carbon::today())->get();

                $newTicket ='<strong> New Tickets </strong>';

                foreach($todayTickets as $tk) {
                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - <span style='color:$tk->status_color'>$tk->status_name</span> - <span style='color:$tk->priority_color'>$tk->priority_name</span></p>";
                }

                $newTicket .='<p>Total Count '. count($todayTickets).'</p>';
                $template = str_replace('{New-Tickets}', count($todayTickets) > 0 ? $newTicket : '' , $template);
            }

            if(str_contains($template, '{Overdue-Tickets}')) {
                $closeStatus = TicketStatus::where('slug','closed')->first();
                
                $overdueTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['is_overdue', 1] ,
                    ['trashed', 0] ,
                    ['status','!=', $closeStatus->id], 

                ])->get();

                $newTicket ='<strong> Overdue Tickets </strong>';

                foreach($overdueTickets as $tk) {

                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - $tk->status_name - $tk->priority_name</p>";
                }

                $newTicket .='<p>Total Count '. count($overdueTickets).'</p>';
                
                $template = str_replace('{Overdue-Tickets}', count($overdueTickets) > 0 ? $newTicket : '' , $template);
            }
            if(str_contains($template, '{Flagged-Tickets}')) {
            
                $flaggedTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['trashed', 0] ,
                    ['is_flagged', 1] 
                ])->get();

                $newTicket ='<strong> Flagged Tickets </strong>';

                foreach($flaggedTickets as $tk) {
                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - <span style='color:$tk->status_color'>$tk->status_name</span> - <span style='color:$tk->priority_color'>$tk->priority_name</span></p>";
                }

                $newTicket .='<p>Total Count '. count($flaggedTickets).'</p>';
                $template = str_replace('{Flagged-Tickets}', count($flaggedTickets) > 0 ? $newTicket : '' , $template);

            }

            $template = str_replace('{Flagged-Tickets}', '' , $template);
            $template = str_replace('{Closed-Tickets}', '' , $template);

        }else{
            if(str_contains($template, '{Worked_hours}')) {
                $template = str_replace('{Worked_hours}', $totalWorkingHour , $template);
            } 
            $template = str_replace('{Overdue-Tickets}', '' , $template);

            if(str_contains($template, '{New-Tickets}')) {
                
                $todayTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['is_overdue', 0] ,
                    ['trashed', 0] 
                ])->whereDate('created_at', Carbon::today())->get();

                $newTicket ='<strong> New Tickets </strong>';

                foreach($todayTickets as $tk) {
                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - <span style='color:$tk->status_color'>$tk->status_name</span> - <span style='color:$tk->priority_color'>$tk->priority_name</span></p>";
                }

                $newTicket .='<p>Total Count '. count($todayTickets).'</p>';
                $template = str_replace('{New-Tickets}', count($todayTickets) > 0 ? $newTicket : '' , $template);
            }

            if(str_contains($template, '{Flagged-Tickets}')) {
                
                $todayFlaggedTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['trashed', 0] ,
                    ['is_flagged', 1] 
                ])->get();

                $newTicket ='<strong> Flagged Tickets </strong>';

                foreach($todayFlaggedTickets as $tk) {
                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - <span style='color:$tk->status_color'>$tk->status_name</span> - <span style='color:$tk->priority_color'>$tk->priority_name</span></p>";
                }

                $newTicket .='<p>Total Count '. count($todayFlaggedTickets).'</p>';
                $template = str_replace('{Flagged-Tickets}', count($todayFlaggedTickets) > 0 ? $newTicket : '' , $template);
            }

            if(str_contains($template, '{Update-Tickets}')) {
            
                $closeStatus = TicketStatus::where('slug','closed')->first();
                
                $todayUpdatedTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['trashed', 0] ,
                    ['status','!=', $closeStatus->id], 
                ])->where('updated_by',auth()->id())->whereDate('updated_at', Carbon::today())->get();
                
                $newTicket ='<strong> Updated Tickets </strong>';
                
                foreach($todayUpdatedTickets as $tk) {
                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - <span style='color:$tk->status_color'>$tk->status_name</span> - <span style='color:$tk->priority_color'>$tk->priority_name</span></p>";
                }

                $newTicket .='<p>Total Count '. count($todayUpdatedTickets).'</p>';
                $template = str_replace('{Update-Tickets}', count($todayUpdatedTickets) > 0 ? $newTicket : '' , $template);

            }
            
            if(str_contains($template, '{Closed-Tickets}')) {

                $closeStatus = TicketStatus::where('slug','closed')->first();
                
                $todayClosedTickets = Tickets::where([ 
                    ['assigned_to', auth()->id()], 
                    ['is_deleted', 0] ,
                    ['trashed', 0] ,
                    ['status', $closeStatus->id], 
                ])->whereDate('updated_at', Carbon::today())->get();

                $newTicket ='<strong> Closed Tickets </strong>';

                foreach($todayClosedTickets as $tk) {
                    $tkUrl = request()->root() . '/ticket-details' .'/'.$tk->coustom_id;
                    $newTicket .= "<p><a href='$tkUrl'>$tk->coustom_id</a> - <span style='color:$tk->status_color'>$tk->status_name</span> - <span style='color:$tk->priority_color'>$tk->priority_name</span></p>";
                }

                $newTicket .='<p>Total Count '. count($todayClosedTickets).'</p>';
                $template = str_replace('{Closed-Tickets}', count($todayClosedTickets) > 0 ? $newTicket : '' , $template);
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

        if($request->type == 'ignore') {
            session()->put('clockin', $request->type);
            session()->put('clockin_time', now() );
            $message = 'Ignored Successfully';
        }else{

            if($request->type == 'yes') {
                $this->clockin();
            }
    
            session()->put('clockin', $request->type);
            session()->put('clockin_time', now() );

            $message = "Clocked in Successfully";
        }

        return response()->json([
            "status" => 200 , 
            "success" => true , 
            "message" => $message,
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