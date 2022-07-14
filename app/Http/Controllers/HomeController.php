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
use Pusher\Pusher;

class HomeController extends Controller {

    public function __construct() {


            $this->middleware('auth');

            $this->middleware(function (Request $request, $next) {
                if (Auth::user()->user_type == 5) {
                    return redirect()->route('un_auth');
                }
                return $next($request);
            });



    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {


        $user_type = 1;
        $customers = Customer::count();
        $orders = Orders::count();
        $project = Project::count();
        $notifications = Notification::read();
        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();
        $closed_status_id = $closed_status->id;


        $open_tickets_count = Tickets::where('status', $open_status->id)
                                ->where([ ['is_deleted', 0], ['tickets.trashed', 0] , ['tickets.status', '!=', $closed_status_id] , ['is_pending' , 0] ])->count();

        $unassigned_tickets_count = Tickets::whereNull('assigned_to')
                                    ->where([ ['is_deleted', 0], ['tickets.trashed', 0] , ['tickets.status', '!=', $closed_status_id] , ['is_pending' , 0] ])->count();
        $my_tickets_count = Tickets::where('assigned_to', auth()->id() )
                                ->where([ ['is_deleted', 0], ['tickets.trashed', 0] , ['tickets.status', '!=', $closed_status_id] , ['is_pending' , 0] ])->count();

        $total_tickets_count = Tickets::where([ ['is_deleted', 0], ['tickets.trashed', 0] , ['tickets.status', '!=', $closed_status_id] , ['is_pending' , 0] ])->count();

        $late_tickets_count = Tickets::where('is_overdue',1)
        ->where([ ['is_overdue',1] ,  ['is_deleted', 0], ['tickets.trashed', 0] , ['tickets.status', '!=', $closed_status_id] , ['is_pending' , 0] ])->count();



        $clockin = StaffAttendance::with('user_clocked')
                        ->where('user_id', auth()->id())
                        ->where('clock_out',NULL)->first();


        $staff_count = User::where('is_deleted',0)
                        ->where('user_type','!=',5)
                        ->where('user_type','!=',4)
                        ->where('is_support_staff','!=',1)
                        ->where('status',1)->count();

        // $staff_att_data = StaffAttendance::with('user_clocked')
        //                     ->where('date',date_format(Carbon::now(),"Y-m-d"))
        //                     ->limit(15)->get();

        // $staff_att_data = StaffAttendance::with('user_clocked')->orderBy('id', 'DESC')->groupBy('user_id')->get();
        $users = User::where('is_deleted', 0)->where('status',1)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $staff_att_data = array();
        $staff_active_count = 0;

        foreach($users as $user){
            // $staffData = StaffAttendance::where('user_id',$user->id)->where('date','>=',date_format(Carbon::yesterday(),"Y-m-d"))->orderByDesc('id')->first();
            $staffData = StaffAttendance::where('user_id',$user->id)->orderByDesc('id')->first();
            if($staffData){
                if($staffData->clock_out == NULL){
                    $staff_active_count++;
                }
                $staffData->name = $user->name;
                array_push($staff_att_data,$staffData);
            }

        }

        // $staff_active_count = StaffAttendance::where('date',date_format(Carbon::now(),"Y-m-d"))->where('clock_out',NULL)->count();
        // $staff_active_count = StaffAttendance::where('clock_out',NULL)->orderBy('id', 'DESC')->groupBy('user_id')->get();
        // $staff_active_count = $staff_active_count->count();
        $staff_inactive_count = $staff_count - $staff_active_count;

        $ticket_follow_ups = TicketFollowUp::where('created_by', auth()->id() )
        ->with(['ticket' => function ($query) {
            $query->with('ticket_customer');
        }])
        ->with(['ticket_user' => function($query) {
            $query->select('id','name');
        }])->get();

        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();

        $followUps = TicketFollowUp::where('is_deleted', 0)->where('passed', 0)->with('ticket')->get();

        $tickets = Tickets::where('is_deleted',0)->get();

        return view('dashboard-new', get_defined_vars());
    }

    public function onlineUser(Request $request)
    {

        if($request->online == false){
            User::find(\Auth::user()->id)->update([
                'is_online' => 1
            ]);
        }
        Session::put('is_online_notif', 1);

        $admin_users = User::where('user_type', 1)->where('is_deleted',0)->where('status',1)->get()->toArray();

        $notify = new NotifyController();

        foreach ($admin_users as $key => $value) {

            $sender_id = \Auth::user()->id;
            $receiver_id = $value['id'];
            $data = '';
            $slug = 'dashboard';
            $type = 'online_user';
            $title = 'LoggedInUser';
            $icon = 'ti-calendar';
            $class = 'btn-success';
            $desc = 'Login In by '.Auth::user()->name;

            $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
        }

        $response['message'] = 'Status changed!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }

    public function showAllUser(Request $request)
    {
        return User::where('is_deleted',0)->where('is_online',1)->get();
    }

    public function showAllOfflineUser(){
        return User::where('is_deleted',0)->where('is_online',0)->get();
    }

    public function getAllStaffAttendance() {
        $staff_count = User::where('is_deleted',0)->where('user_type','!=',5)->where('user_type','!=',4)->where('status',1)->count();
        // $staff_att_data = StaffAttendance::with('user_clocked')->where('date',date_format(Carbon::now(),"Y-m-d"))->get();
        $staff_att_data = StaffAttendance::with('user_clocked')->orderBy('id', 'DESC')->groupBy('user_id')->get();

        $staff_active_count = StaffAttendance::where('clock_out',NULL)->orderBy('id', 'DESC')->groupBy('user_id')->count();

        // $staff_active_count = StaffAttendance::where('date',date_format(Carbon::now(),"Y-m-d"))->where('clock_out',NULL)->count();

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

        Notification::where('receiver_id', auth()->id())->update(['read_at' => Carbon::now()]);

        $response['message'] = "Success Message";
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }
    public function getNotifications(){

        $notifications = Notification::where([ ['read_at',NULL] , ['sender_id' ,'!=',0], ['receiver_id', auth()->id()] ])
                ->with(['sender','user'])->limit(10)->orderByDesc('id')->get();
        // foreach($notifications as $notification) {
            // $notification->user = User::where('id',$notification->receiver_id)->first();
            // $notification->sender = User::where('id',$notification->sender_id)->first();
        // }

        $settings = BrandSettings::first();
        $version = (empty($settings) ? 'Dashboard' : ($settings->site_logo_title != null ? $settings->site_logo_title  : 'Dashboard') );

        $count = Notification::with(['sender','user'])->orderBy('id','desc')->where('receiver_id',\Auth::user()->id)->where('read_at',NULL)->count();

        // $currentDate = Carbon::now();
        // $staffData = StaffAttendance::where([ ['date', $currentDate->format('Y-m-d')], ['clock_out', null], ['user_id',auth()->user()->id] ])->orderByDesc('id')->first();
        $staffData = StaffAttendance::where('user_id',auth()->user()->id)->orderByDesc('id')->first();

        $response['message'] = 'Notification List';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $notifications;
        $response['total_notification'] = $count;
        $response['system_version'] = $version;
        $response['staff_clock_in'] = $staffData;
        return response()->json($response);

    }
    public function wizard(){
        return view('wizard');
    }

    public function unauth() {
        return view('unauth');
    }
    public function pusher() {
        return view('pusher');
    }
}
