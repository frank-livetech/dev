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
                                ->where('is_deleted', 0)
                                ->where('tickets.trashed', 0)
                                ->where('tickets.status', '!=', $closed_status_id)->count();

        $unassigned_tickets_count = Tickets::whereNull('assigned_to')
                                        ->where('is_deleted', 0)
                                        ->where('tickets.trashed', 0)
                                        ->where('tickets.status', '!=', $closed_status_id)->count();

        $my_tickets_count = Tickets::where('assigned_to', auth()->id() )
                                ->where('is_deleted', 0)
                                ->where('tickets.trashed', 0)
                                ->where('tickets.status', '!=', $closed_status_id)->count();

        $total_tickets_count = Tickets::where('is_deleted', 0)
                                ->where('tickets.trashed', 0)
                                ->where('tickets.status', '!=', $closed_status_id)->count();

        $late_tickets_count = Tickets::where('is_overdue',1)
                                ->where('is_deleted', 0)
                                ->where('tickets.trashed', 0)
                                ->where('tickets.status', '!=', $closed_status_id)->count();



        $clockin = StaffAttendance::with('user_clocked')
                        ->where('user_id', auth()->id())
                        ->where('clock_out',NULL)->first();


        $staff_count = User::where('is_deleted',0)
                        ->where('user_type','!=',5)
                        ->where('user_type','!=',4)
                        ->where('is_support_staff','!=',1)
                        ->where('status',1)->count();

        $staff_att_data = StaffAttendance::with('user_clocked')
                            ->where('date',date_format(Carbon::now(),"Y-m-d"))
                            ->limit(15)->get();
        
        foreach($staff_att_data as $data) {
            $data['clock_in'] = Carbon::parse($data['clock_in'])->timezone(\Session::get('timezone'))->format('Y-m-d h:m:s A');
            if($data['clock_out'] != null) {
                $data['clock_out'] = Carbon::parse($data['clock_out'])->timezone(\Session::get('timezone'))->format('Y-m-d h:m:s A');
            }
        }

        $staff_active_count = StaffAttendance::where('date',date_format(Carbon::now(),"Y-m-d"))->where('clock_out',NULL)->count();
        $staff_inactive_count = $staff_count - $staff_active_count;
        

        $users = User::where('is_deleted',0)->where('user_type','!=',5)->where('is_support_staff','!=',1)->get();

        $ticket_follow_ups = TicketFollowUp::where('created_by', auth()->id() )
        ->with(['ticket' => function ($query) {
            $query->with('ticket_customer');
        }])
        ->with(['ticket_user' => function($query) {
            $query->select('id','name');
        }])->get();

        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();

        $followUps = TicketFollowUp::where('is_deleted', 0)->where('passed', 0)->with('ticket')->get();
        return view('dashboard-new', get_defined_vars());
    
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
                
        Notification::where('receiver_id', auth()->id())->update(['read_at' => Carbon::now()]);

        $response['message'] = "Success Message";
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }
    public function getNotifications(){
                
        $notifications = Notification::orderBy('id','desc')->where('receiver_id',\Auth::user()->id)->where('read_at',NULL)->limit(10)->get();
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

    public function unauth() {
        return view('unauth');
    }
}
