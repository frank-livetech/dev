<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandSettings;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Tickets;
use App\Models\TicketStatus;
use App\Models\Notification;
use App\Models\StaffAttendance;
use Illuminate\Support\Facades\DB;
use App\User;
use Carbon\Carbon;
use Session;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $settings = BrandSettings::first();
        if(!empty($settings)) {
            Session::put('site_title', $settings->site_title);
            Session::put('site_logo', $settings->site_logo);
            Session::put('site_favicon', $settings->site_favicon);
            Session::put('site_logo_title', $settings->site_logo_title);
            Session::put('site_footer', $settings->site_footer);
            Session::put('site_version', $settings->site_version);
        }
        
        $customers = Customer::count();
        $project = Project::count();
        $notifications = Notification::read();
        $open_status = TicketStatus::where('name', 'Open')->first();
        $open_tickets_count = Tickets::where('status', $open_status->id)->count();
        $my_tickets_count = Tickets::where('assigned_to', \Auth::user()->id)->count();
        $clockin = StaffAttendance::where('user_id', \Auth::user()->id)->where('clock_out', NULL)->first();

        $staff_att_data = StaffAttendance::where('date',date_format(Carbon::now(),"Y-m-d"))->get();
        return view('reports.staff_attendance.index',compact('staff_att_data','clockin','notifications','settings','customers','project','open_tickets_count','my_tickets_count'));;
    }


    public function staffData() {
        $staff_list =  User::where("is_deleted","=",0)->get();

        $response['message'] = 'Staff List';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $staff_list;

        return response()->json($response);
    }


    public function getStaffAttendance(Request $request) { 


        $attendance =  StaffAttendance::where('clock_in',">=",$request->start_date)->where('clock_in',"<=",$request->end_date)->where('user_id','=',$request->user_id)->get();
        // $attendance =  StaffAttendance::whereBetween('clock_in',[$request->start_date, $request->end_date])->where('user_id','=',$request->user_id)->get();
        // $attendance = DB::select("SELECT * FROM `staff_attendance` where clock_in <= '$request->start_date' OR clock_out <= '$request->end_date' AND user_id = $request->user_id");

        foreach($attendance as $att) {
            $att->user = DB::table("users")->where("id","=",$att->user_id)->select('name')->get();
        }

        $response['message'] = 'Staff Attendance';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $attendance;
        $response['date_format'] = Session('system_date');

        return response()->json($response);
    }

}