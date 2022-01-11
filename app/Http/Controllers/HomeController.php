<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandSettings;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Tickets;
use App\Models\TicketStatus;
use App\Models\Orders;
use App\Models\TicketFollowUp;
use App\Models\Notification;
Use App\User;
use App\Models\StaffAttendance;
use Carbon\Carbon;
use Session;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HelpdeskController;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_type = 1;

        // if(\Auth::user()->user_type == 1) {
        //     $role_features = DB::table("ac_features")->where("parent_id","=",0)->get();
        //     foreach($role_features as $feature) {
        //         $sub_menu = DB::table("ac_features")->where('parent_id','=',$feature->f_id)->get();
        //         $feature->sub_menu = $sub_menu;
        //     }
        // }else{
        //     $role_features = DB::table('role_has_permission')
        //     ->join('ac_features', 'role_has_permission.feature_id', '=', 'ac_features.f_id')
        //     ->where('ac_features.parent_id', '=', 0)
        //     ->where('role_has_permission.role_id',\Auth::user()->user_type)->get();
    
        //     foreach ($role_features as $feature) {
        //             $sub_menu = DB::table("ac_features")->where('parent_id','=',$feature->f_id)->get();
        //             $feature->sub_menu = $sub_menu;
        //     }
        // }

        
        
        
        $customers = Customer::count();
        $orders = Orders::count();
        $project = Project::count();
        $notifications = Notification::read();
        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();
        $closed_status_id = $closed_status->id;
        // $helpdesk = new HelpdeskController();
        // echo "<pre>";
        // $tickets = json_decode(json_encode($helpdesk->getTickets()), true);



        // print_r($tickets['original']['open_tickets_count']);exit;
        
        $open_tickets_count = Tickets::where('status', $open_status->id)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $unassigned_tickets_count = Tickets::whereNull('assigned_to')->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $my_tickets_count = Tickets::where('assigned_to',\Auth::user()->id)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $total_tickets_count = Tickets::where('is_deleted', 0)->count()->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id);
        $late_tickets_count = Tickets::where('is_overdue',1)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        // $open_tickets_count = $tickets['original']['open_tickets_count'];
        // $unassigned_tickets_count = $tickets['original']['unassigned_tickets_count'];
        // $my_tickets_count = $tickets['original']['my_tickets_count'];

        // if( array_key_exists('late_tickets_count', $tickets['original'])) {
        //     $late_tickets_count = $tickets['original']['late_tickets_count'];
        // }else{
        //     $late_tickets_count = 0;
        // }


        $clockin = StaffAttendance::where('user_id',\Auth::user()->id)->where('clock_out',NULL)->first();
        $staff_count = User::where('is_deleted',0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff','!=',1)->where('status',1)->count();
        $staff_att_data = StaffAttendance::with('user_clocked')->where('date',date_format(Carbon::now(),"Y-m-d"))->limit(15)->get();
        // $staff_att_data = StaffAttendance::with('user_clocked')->where('clock_out',NULL)->limit(15)->get();

        $staff_active_count = StaffAttendance::where('date',date_format(Carbon::now(),"Y-m-d"))->where('clock_out',NULL)->count();
        $staff_inactive_count = $staff_count - $staff_active_count;
        //return $staff_att_data;

        $users = User::where('is_deleted',0)->where('user_type','!=',5)->where('is_support_staff','!=',1)->get();

        $ticket_follow_ups = TicketFollowUp::where('created_by',\Auth::user()->id)
        ->with(['ticket' => function ($query) {
            $query->with('ticket_customer');
        }])
        ->with(['ticket_user' => function($query) {
            $query->select('id','name');
        }])->get();

        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        // return view('dashboard',compact('staff_inactive_count','staff_active_count','staff_count','staff_att_data','clockin','notifications','customers','project','total_tickets_count','open_tickets_count', 'unassigned_tickets_count', 'my_tickets_count', 'late_tickets_count','orders','users','ticket_follow_ups','live'));
        return view('dashboard-new',compact('staff_inactive_count','staff_active_count','staff_count','staff_att_data','clockin','notifications','customers','project','total_tickets_count','open_tickets_count', 'unassigned_tickets_count', 'my_tickets_count', 'late_tickets_count','orders','users','ticket_follow_ups','live'));
    
    }


    public function getAllStaffAttendance() {
        $staff_count = User::where('is_deleted',0)->where('user_type','!=',5)->where('user_type','!=',4)->where('status',1)->count();
        $staff_att_data = StaffAttendance::with('user_clocked')->where('date',date_format(Carbon::now(),"Y-m-d"))->get();
        $staff_active_count = StaffAttendance::where('date',date_format(Carbon::now(),"Y-m-d"))->where('clock_out',NULL)->count();
        $staff_inactive_count = $staff_count - $staff_active_count;

        $response['message'] = 'Staff Attendance List';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['total_staff'] = $staff_count;
        $response['data'] = $staff_att_data;
        $response['active_staff'] = $staff_active_count;
        $response['inactive_staff'] = $staff_inactive_count;
        return response()->json($response);
    }

    public function markAllRead(){
                
         $id = Auth::user()->id; 
        //  print_r($id);

         $notification =  Notification::where('receiver_id', $id)->update(['read_at' => Carbon::now()]);

        // Notification::where('receiver_id',$id)->first();
        //    if($notification){
        //      $notification->read_at = Carbon::now();
        //      $notification->save();
            
        $response['message'] = "Success Message";
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);


    }
    public function getNotifications(){
                
        $notifications = Notification::orderBy('id','desc')->where('receiver_id',\Auth::user()->id)->where('read_at',NULL)->get();
        foreach($notifications as $notification) {
            $notification->user = User::where('id',$notification->receiver_id)->first();
            $notification->sender = User::where('id',$notification->sender_id)->first();
        }

        $response['message'] = 'Notification List';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $notifications;
        return response()->json($response);

    }
    public function wizard(){
        return view('wizard');
    }
}
