<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomerManager\CustomerlookupController;
use Illuminate\Http\Request;
use App\Models\Departments;
use App\Models\DepartmentAssignments;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketType;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\Customer;
use App\Models\Tickets;
use App\Models\Vendors;
use App\Models\TicketReply;
use App\Models\TicketFollowUp;
use App\Models\TicketNote;
use App\Models\Assets;
use App\Models\Project;
use Illuminate\Support\Facades\DB;
use App\Models\SystemSetting;
use App\Models\Activitylog;
use App\Models\TicketSettings;
use App\Models\Company;
use App\Models\SlaPlan;
use App\Models\SlaPlanAssoc;
use App\Models\Country;
use App\Models\ResponseTemplate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use Genert\BBCode\BBCode;
use App\Models\Mail;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\CustomerPanel\HomeController;
use App\Http\Controllers\SystemManager\MailController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\ProjectManager\ProjectManagerController;
use App\Models\DepartmentPermissions;
use Faker\Calculator\Ean;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Builder;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\URL;

require '../vendor/autoload.php';

class HelpdeskController extends Controller
{
    // *************   PROPERTIES   ****************

    const DEFAULTSLA_TITLE = 'Default SLA';
    const NOSLAPLAN = 'No SLA Assigned';
    const CUSTOMID_FORMAT = 'XXX-999-9999';


    // ***************   METHODS   *****************


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function ticket_manager($dept,$sts){

        $dept = Departments::where('slug',$dept)->first();
        $dept = $dept->id;
        $sts = TicketStatus::where('slug',$sts)->first();
        $sts = $sts->id;
        
        $departments = Departments::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();
        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();

        $tickets_followups = TicketFollowUp::where('passed', 0)->where('is_deleted', 0)->get();

        foreach ($tickets_followups as $key => $value) {
            if($value->is_recurring == 1) {
                $tickets_followups[$key]->date = $this->follow_up_calculation($value);
            }
        }

        $followUpsNew = [];

        foreach ($tickets_followups as $key => $value) {
            if($value->passed == 0) {
                $followUpsNew[] = $value;
            }
        }
        
        $tickets_followups = $followUpsNew;

        $url_type = '';
        if(isset($request->type)) {
            $url_type = $request->type;
        }

        $loggedInUser = \Auth::user()->id;
        $date_format = Session('system_date');
        $projects = Project::all();

        $staffs = User::where('user_type','!=',5)->where('user_type','!=',4)->get();

        return view('help_desk.ticket_manager.index-new',compact('loggedInUser','departments','statuses','priorities','types','users','customers', 'ticket_format', 'tickets_followups','url_type','date_format','projects','staffs','dept','sts'));

    }

    public function ticket_management(Request $request){
        $departments = Departments::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();
        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();

        $tickets_followups = TicketFollowUp::where('passed', 0)->where('is_deleted', 0)->get();

        foreach ($tickets_followups as $key => $value) {
            if($value->is_recurring == 1) {
                $tickets_followups[$key]->date = $this->follow_up_calculation($value);
            }
        }

        $followUpsNew = [];

        foreach ($tickets_followups as $key => $value) {
            if($value->passed == 0) {
                $followUpsNew[] = $value;
            }
        }
        
        $tickets_followups = $followUpsNew;

        $url_type = '';
        if(isset($request->type)) {
            $url_type = $request->type;
        }

        $loggedInUser = \Auth::user()->id;
        $date_format = Session('system_date');
        $projects = Project::all();

        $staffs = User::where('user_type','!=',5)->where('user_type','!=',4)->get();
        $dept = '';
        $sts = '';

        return view('help_desk.ticket_manager.index-new',compact('dept','sts','loggedInUser','departments','statuses','priorities','types','users','customers', 'ticket_format', 'tickets_followups','url_type','date_format','projects','staffs'));
    }

    public function addTicketPage() {

        $departments = Departments::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();

        $responseTemplates = ResponseTemplate::get();

        $id = \Auth::user()->id;
        // for customers login and add ticket
        $is_customer = User::findOrFail(\Auth::user()->id);
        if($is_customer->user_type == 5) {
            $cus = Customer::where('email', $is_customer->email)->first();
            if(empty($cus)) {
                return "Invalid request user not found!";
            }

            $id = $cus->id;
        }

        $page_control = 'super';

        return view('help_desk.ticket_manager.add_ticket_new',compact('departments','priorities','users','types','customers','id', 'responseTemplates', 'page_control'));
    }

    public function update_ticket(Request $request) {
        
        $data = $request->all();
        $response = array();
        try {
            $ticket = Tickets::where('id',$data['id'])->first();

            $data['action_performed'] = ($request->has('action_performed')) ? $data['action_performed'] : "";
            
            if(!empty($ticket)) {
                if(array_key_exists('attachments', $data)) {
                    // target dir for ticket files against ticket id
                    $target_dir = public_path().'/files/tickets/'.$data['id'];
                    if (!File::isDirectory($target_dir)) {
                        mkdir($target_dir, 0777, true);
                    }

                    // removed files after update
                    // foreach (File::allFiles($target_dir) as $i => $valueO) {
                    //     $found = false;
                    //     foreach ($data['attachments'] as $j => $valueI) {
                    //         if($valueO->getRelativePathname() == basename($valueI[1])) {
                    //             $found = true;
                    //             unset($data['attachments'][$j]);
                    //         }
                    //     }

                    //     if(!$found) {
                    //         File::delete($target_dir.'/'.$valueO->getRelativePathname());
                    //     }
                    // }

                    // set files
                    foreach ($data['attachments'] as $key => $value) {
                        if (filter_var($value[1], FILTER_VALIDATE_URL)) { 
                            $file = file_get_contents($value[1]);
                        }else{
                            $file = base64_decode($value[1]);
                        }
                        
                        $target_src = 'public/files/tickets/'.$data['id'].'/'.$value[0];
                            
                        file_put_contents($target_src, $file);
                    }
                }
                unset($data['id']);
                unset($data['attachments']);
                $data['updated_at'] = Carbon::now();
                $data['updated_by'] = \Auth::user()->id;
                $ticket->update($data);

                $log_data = array();
                $log_data['module'] = 'Tickets';
                $log_data['table_ref'] = 'tickets';
                $log_data['ref_id'] = $request->id;
                $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
                $log_data['action_perform'] = 'Ticket ID # <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> '.$data['action_performed'].' Updated By '. $name_link;
                $log_data['created_by'] = \Auth::user()->id;
                
                Activitylog::create($log_data);

                $response['message'] = 'Ticket Updated Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
            }
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function save_tickets(Request $request){
        $data = $request->all();
        $response = array();
        // $ticket_settings = TicketSettings::where('tkt_key','ticket_format')->first();
        try {
            if ($request->has('newcustomer')) {
                // $customer = Customer::where('email', $data['email'])->first();

                $data['customer_id'] = $this->addTicketCustomer($request);
                unset($data['first_name']);
                unset($data['last_name']);
                unset($data['email']);
                unset($data['phone']);
            }else{
                // if( !empty(Tickets::where('customer_id', $data['customer_id'])->where('subject', $data['subject'])->first()) ) {
                //     throw new Exception('Ticket already exists!');
                // }

                if(Auth::user()->user_type == 5){
                    $customer = Customer::where('email', Auth::user()->email)->first();
                    $data['customer_id'] = $customer->id;
                }
            }

            $tickets_count = Tickets::all()->count();
            
            $data['created_by'] = \Auth::user()->id;
            
            if(!isset($data['status'])) {
                // set default open status
                $os = TicketStatus::where('name','Open')->first();
                $data['status'] = $os->id;
            }
            if(!isset($data['type'])) {
                // set default issue type
                $tt = TicketType::where('name', 'Issue')->first();
                $data['type'] = $tt->id;
            }

            $ticket = Tickets::create($data);

            // ticket assoc with sla plan
            $settings = $this->getTicketSettings(['default_reply_and_resolution_deadline']);
            if(isset($settings['default_reply_and_resolution_deadline'])) {
                if($settings['default_reply_and_resolution_deadline'] == 1) {
                    $sla_plan = SlaPlan::where('title', self::DEFAULTSLA_TITLE)->first();
                    if(empty($sla_plan)) {
                        $sla_plan = SlaPlan::create([
                            'title' => self::DEFAULTSLA_TITLE,
                            'sla_status' => 1
                        ]);
                    }
                    SlaPlanAssoc::create([
                        'sla_plan_id' => $sla_plan->id,
                        'ticket_id' => $ticket->id
                    ]);
                }
            }
            
            $newG = new GeneralController();
            $ticket->coustom_id = $newG->randomStringFormat(self::CUSTOMID_FORMAT);
            $lt = Tickets::orderBy('created_at', 'desc')->first();

            if(!empty($lt)) {
                $ticket->seq_custom_id = 'T-'.strval($lt->id + 1);
            }else{
                $ticket->seq_custom_id = 'T-'.strval($tickets_count+1);
            }
            $ticket->save();
            
            $log_data = array();
            $log_data['module'] = 'Tickets';
            $log_data['table_ref'] = 'tickets';
            $log_data['ref_id'] = $ticket->id;
            $log_data['created_by'] = \Auth::user()->id;
            $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
            $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Created By '. $name_link;
            Activitylog::create($log_data);
            
            $response['id'] = $ticket->id;
            $response['message'] = 'Ticket Added Successfully!';
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

    public function getTicketSlaPlan($ticketID) {
        try {
            $sla_plan = array(
                "id" => "",
                "title" => self::NOSLAPLAN,
                "reply_deadline" => "",
                "due_deadline" => "",
                "bg_color" => "#fff"
            );
    
            $settings = $this->getTicketSettings(['default_reply_time_deadline', 'default_resolution_deadline', 'overdue_ticket_background_color']);

            $sla_plan['bg_color'] = $settings['overdue_ticket_background_color'];

            $sla_assoc = SlaPlanAssoc::where('ticket_id', $ticketID)->first();
            if(!empty($sla_assoc)) {
                $sla_plann = SlaPlan::where('id', $sla_assoc->sla_plan_id)->first();
                if(!empty($sla_plann)) {
                    $sla_plan['id'] = $sla_plann->id;
                    $sla_plan['title'] = $sla_plann->title;
                    $sla_plan['reply_deadline'] = $sla_plann->reply_deadline;
                    $sla_plan['due_deadline'] = $sla_plann->due_deadline;

                    // use default set deadlines in case of empty
                    if(empty($sla_plan['reply_deadline'])) $sla_plan['reply_deadline'] = $settings['default_reply_time_deadline'];
                    
                    if(empty($sla_plan['due_deadline'])) $sla_plan['due_deadline'] = $settings['default_resolution_deadline'];
                }
            }
    
            return $sla_plan;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getTicketSettings($settings) {
        try{
            $list = TicketSettings::all();
            $ret = array();

            foreach ($list->toArray() as $value) {
                foreach ($settings as $set) {
                    if($value['tkt_key'] == $set) {
                        $ret[$set] = $value['tkt_value'];
                    }
                }
            }

            return $ret;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getSlaDeadlineFrom($ticketID) {
        try {
            $ticket = Tickets::findOrFail($ticketID);
            // $logs = Activitylog::where('ref_id', $ticketID)->where('module', 'Tickets')->orWhere([
            //     ['table_ref', 'tickets'], ['table_ref', 'ticket_replies'], ['table_ref', 'ticket_notes']
            // ])->orderBy('created_at', 'desc')->first();
            // $logs = Activitylog::where('ref_id', $ticketID)->where('module', 'Tickets')->where('table_ref', 'sla_deadline_from')->orderBy('created_at', 'desc')->first();
            $deadlines = [];
            $logs = Activitylog::where('ref_id', $ticketID)->where('module', 'Tickets')->where('table_ref', 'sla_rep_deadline_from')->orderBy('created_at', 'desc')->first();
            $deadlines[0] = empty($logs) ? $ticket->created_at : $logs->created_at;

            $logs = Activitylog::where('ref_id', $ticketID)->where('module', 'Tickets')->where('table_ref', 'sla_res_deadline_from')->orderBy('created_at', 'desc')->first();
            $deadlines[1] = empty($logs) ? $ticket->created_at : $logs->created_at;
            
            return $deadlines;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function getFilteredTickets($dept = '' , $sts = ''){

        // $cid = '';
        // $sid = '';
        // if(!empty($id)) {
        //     if($statusOrUser == 'customer') $cid = $id;
        //     else if($statusOrUser == 'staff') $sid = $id;
        // }

        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();
        $closed_status_id = $closed_status->id;
        $cnd = '!=';
        $is_del = 0;
        // if($statusOrUser == 'closed') $cnd = '=';
        // if($statusOrUser == 'trash') $is_del = 1;

        if(\Auth::user()->user_type == 1) {
            $tickets = DB::Table('tickets')
            ->select('tickets.*','ticket_statuses.name as status_name','ticket_statuses.color as status_color','ticket_priorities.name as priority_name','ticket_priorities.priority_color as priority_color','ticket_types.name as type_name','departments.name as department_name',DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'), DB::raw('COALESCE(users.name, NULL) AS creator_name'))
            ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
            ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
            ->join('ticket_types','ticket_types.id','=','tickets.type')
            ->join('departments','departments.id','=','tickets.dept_id')
            ->join('customers','customers.id','=','tickets.customer_id')
            ->leftjoin('users','users.id','=','tickets.created_by')
            ->where('tickets.status',$sts)
            ->where('tickets.dept_id',$dept)
            // ->when($statusOrUser == 'customer', function($q) use($id) {
            //     return $q->where('tickets.customer_id', $id);
            // })
            // ->when($statusOrUser == 'staff', function($q) use($id) {
            //     return $q->where('tickets.assigned_to', $id);
            // })
            // ->when($statusOrUser == 'closed', function($q) use($closed_status_id) {
            //     return $q->where('tickets.trashed', 0)->where('tickets.status', $closed_status_id);
            // })
            // ->when($statusOrUser == 'trash', function($q) {
            //     return $q->where('tickets.trashed', 1);
            // })
            // ->when(empty($statusOrUser), function($q) use($closed_status_id) {
            //     return $q->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id);
            // })
            ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->orderBy('tickets.created_at', 'desc')->get();
        
        } else {
            $aid = \Auth::user()->id;
            $assigned_depts = DepartmentAssignments::where('user_id', $aid)->get()->pluck('dept_id')->toArray();

            $tickets = DB::Table('tickets')
            ->select('tickets.*','ticket_statuses.name as status_name','ticket_statuses.color as status_color','ticket_priorities.name as priority_name','ticket_priorities.priority_color as priority_color','ticket_types.name as type_name','departments.name as department_name',DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'), DB::raw('COALESCE(users.name, NULL) AS creator_name'))
            ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
            ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
            ->join('ticket_types','ticket_types.id','=','tickets.type')
            ->join('departments','departments.id','=','tickets.dept_id')
            ->join('customers','customers.id','=','tickets.customer_id')
            ->leftjoin('users','users.id','=','tickets.created_by')
            // ->when($statusOrUser == 'customer', function($q) use ($id) {
            //     return $q->where('tickets.customer_id', $id);
            // })
            // ->when($statusOrUser == 'closed', function($q) use ($closed_status_id) {
            //     return $q->where('tickets.trashed', 0)->where('tickets.status', $closed_status_id);
            // })
            // ->when($statusOrUser == 'trash', function($q) {
            //     return $q->where('tickets.trashed', 1);
            // })
            // ->when(empty($statusOrUser), function($q) use ($closed_status_id) {
            //     return $q->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id);
            // })
            ->when(\Auth::user()->user_type != 5, function($q) use ($assigned_depts, $aid) {
                return $q->whereIn('tickets.dept_id', $assigned_depts)->orWhere('tickets.assigned_to', $aid)->orWhere('tickets.created_by', $aid);
            })
            ->where('tickets.status',$sts)
            ->where('tickets.dept_id',$dept)
            ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->orderBy('tickets.created_at', 'desc')->get();
        }

        $total_tickets_count = $tickets->count();
        $my_tickets_count = 0;
        $open_tickets_count = 0;
        $unassigned_tickets_count = 0;
        $late_tickets_count = 0;
        $closed_tickets_count = Tickets::where('status', $closed_status->id)->where('is_deleted', 0)->count();
        $trashed_tickets_count = Tickets::where('trashed', 1)->where('is_deleted', 0)->count();
        
        foreach($tickets as $value) {
            $value->tech_name = 'Unassigned';
            if(!empty($value->assigned_to)) {
                $u = User::where('id', $value->assigned_to)->first();
                if(!empty($u)) $value->tech_name = $u->name;
            }
            else $unassigned_tickets_count++;

            $rep = TicketReply::where('ticket_id', $value->id)->orderBy('created_at', 'desc')->first();
            $repCount = TicketReply::where('ticket_id', $value->id)->count();
            $value->lastReplier = '';
            $value->replies = '';
            if(!empty($rep)) {
                if($rep['user_id']) {
                    $user = User::where('id', $rep['user_id'])->first();
                    if(!empty($user)) $value->lastReplier = $user->name;
                } else if($rep['customer_id']) {
                    $user = Customer::where('id', $rep['customer_id'])->first();
                    if(!empty($user)) $value->lastReplier = $user->first_name.' '.$user->last_name;
                }
                $value->replies = $repCount;
            }

            if($value->assigned_to == \Auth::user()->id) $my_tickets_count++;
            if($value->status == $open_status->id) $open_tickets_count++;

            $value->lastActivity = Activitylog::where('module', 'Tickets')->where('ref_id', $value->id)->orderBy('created_at', 'desc')->value('created_at');

            $value->sla_plan = $this->getTicketSlaPlan($value->id);
            
            $dd = $this->getSlaDeadlineFrom($value->id);
            $value->sla_rep_deadline_from = $dd[0];
            $value->sla_res_deadline_from = $dd[1];

            $lcnt = false;
            if($value->sla_plan['title'] != self::NOSLAPLAN) {
                if($value->reply_deadline != 'cleared') {
                    $nowDate = Carbon::now();
                    if(!empty($value->reply_deadline)) {
                        $timediff = $nowDate->diffInSeconds(Carbon::parse($value->reply_deadline), false);
                        if($timediff < 0) $lcnt = true;
                    } else {
                        $rep = Carbon::parse($value->sla_rep_deadline_from);
                        $dt = explode('.', $value->sla_plan['reply_deadline']);
                        $rep->addHours($dt[0]);
                        if(array_key_exists(1, $dt)) $rep->addMinutes($dt[1]);
                        $timediff = $nowDate->diffInSeconds($rep, false);
                        if($timediff < 0) $lcnt = true;
                    }
                }
    
                if(!$lcnt) {
                    $nowDate = Carbon::now();
                    if(!empty($value->resolution_deadline)) {
                        $timediff = $nowDate->diffInSeconds(Carbon::parse($value->resolution_deadline), false);
                        if($timediff < 0) $lcnt = true;
                    } else {
                        $rep = Carbon::parse($value->sla_res_deadline_from);
                        $dt = explode('.', $value->sla_plan['due_deadline']);
                        $rep->addHours($dt[0]);
                        if(array_key_exists(1, $dt)) $rep->addMinutes($dt[1]);
                        $timediff = $nowDate->diffInSeconds($rep, false);
                        if($timediff < 0) $lcnt = true;
                    }
                }
            }
            
            $value->is_overdue = 0;
            if($lcnt) {
                $late_tickets_count++;
                $value->is_overdue = 1;
            }
        }
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['tickets']= $tickets;
        $response['total_tickets_count']= $total_tickets_count;
        $response['my_tickets_count']= $my_tickets_count;
        $response['open_tickets_count']= $open_tickets_count;
        $response['unassigned_tickets_count']= $unassigned_tickets_count;
        $response['late_tickets_count']= $late_tickets_count;
        $response['closed_tickets_count']= $closed_tickets_count;
        $response['trashed_tickets_count']= $trashed_tickets_count;
        $response['date_format'] = Session('system_date');
        
        return response()->json($response);

    }
    public function getTickets($statusOrUser='', $id='') {
        $cid = '';
        $sid = '';
        if(!empty($id)) {
            if($statusOrUser == 'customer') $cid = $id;
            else if($statusOrUser == 'staff') $sid = $id;
        }

        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();
        $closed_status_id = $closed_status->id;
        $cnd = '!=';
        $is_del = 0;
        if($statusOrUser == 'closed') $cnd = '=';
        if($statusOrUser == 'trash') $is_del = 1;

        if(\Auth::user()->user_type == 1) {
            $tickets = DB::Table('tickets')
            ->select('tickets.*','ticket_statuses.name as status_name','ticket_statuses.color as status_color','ticket_priorities.name as priority_name','ticket_priorities.priority_color as priority_color','ticket_types.name as type_name','departments.name as department_name',DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'), DB::raw('COALESCE(users.name, NULL) AS creator_name'))
            ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
            ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
            ->join('ticket_types','ticket_types.id','=','tickets.type')
            ->join('departments','departments.id','=','tickets.dept_id')
            ->join('customers','customers.id','=','tickets.customer_id')
            ->leftjoin('users','users.id','=','tickets.created_by')
            ->when($statusOrUser == 'customer', function($q) use($id) {
                return $q->where('tickets.customer_id', $id);
            })
            ->when($statusOrUser == 'staff', function($q) use($id) {
                return $q->where('tickets.assigned_to', $id);
            })
            ->when($statusOrUser == 'closed', function($q) use($closed_status_id) {
                return $q->where('tickets.trashed', 0)->where('tickets.status', $closed_status_id);
            })
            ->when($statusOrUser == 'trash', function($q) {
                return $q->where('tickets.trashed', 1);
            })
            ->when(empty($statusOrUser), function($q) use($closed_status_id) {
                return $q->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id);
            })
            ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->orderBy('tickets.created_at', 'desc')->get();
        
        } else {
            $aid = \Auth::user()->id;
            $assigned_depts = DepartmentAssignments::where('user_id', $aid)->get()->pluck('dept_id')->toArray();

            $tickets = DB::Table('tickets')
            ->select('tickets.*','ticket_statuses.name as status_name','ticket_statuses.color as status_color','ticket_priorities.name as priority_name','ticket_priorities.priority_color as priority_color','ticket_types.name as type_name','departments.name as department_name',DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'), DB::raw('COALESCE(users.name, NULL) AS creator_name'))
            ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
            ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
            ->join('ticket_types','ticket_types.id','=','tickets.type')
            ->join('departments','departments.id','=','tickets.dept_id')
            ->join('customers','customers.id','=','tickets.customer_id')
            ->leftjoin('users','users.id','=','tickets.created_by')
            ->when($statusOrUser == 'customer', function($q) use ($id) {
                return $q->where('tickets.customer_id', $id);
            })
            ->when($statusOrUser == 'closed', function($q) use ($closed_status_id) {
                return $q->where('tickets.trashed', 0)->where('tickets.status', $closed_status_id);
            })
            ->when($statusOrUser == 'trash', function($q) {
                return $q->where('tickets.trashed', 1);
            })
            ->when(empty($statusOrUser), function($q) use ($closed_status_id) {
                return $q->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id);
            })
            ->when(\Auth::user()->user_type != 5, function($q) use ($assigned_depts, $aid) {
                return $q->whereIn('tickets.dept_id', $assigned_depts)->orWhere('tickets.assigned_to', $aid)->orWhere('tickets.created_by', $aid);
            })
            ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->orderBy('tickets.created_at', 'desc')->get();
        }

        $total_tickets_count = $tickets->count();
        $my_tickets_count = 0;
        $open_tickets_count = 0;
        $unassigned_tickets_count = 0;
        $late_tickets_count = 0;
        $closed_tickets_count = Tickets::where('status', $closed_status->id)->where('is_deleted', 0)->count();
        $trashed_tickets_count = Tickets::where('trashed', 1)->where('is_deleted', 0)->count();
        
        foreach($tickets as $value) {
            $value->tech_name = 'Unassigned';
            if(!empty($value->assigned_to)) {
                $u = User::where('id', $value->assigned_to)->first();
                if(!empty($u)) $value->tech_name = $u->name;
            }
            else $unassigned_tickets_count++;

            $rep = TicketReply::where('ticket_id', $value->id)->orderBy('created_at', 'desc')->first();
            $repCount = TicketReply::where('ticket_id', $value->id)->count();
            $value->lastReplier = '';
            $value->replies = '';
            if(!empty($rep)) {
                if($rep['user_id']) {
                    $user = User::where('id', $rep['user_id'])->first();
                    if(!empty($user)) $value->lastReplier = $user->name;
                } else if($rep['customer_id']) {
                    $user = Customer::where('id', $rep['customer_id'])->first();
                    if(!empty($user)) $value->lastReplier = $user->first_name.' '.$user->last_name;
                }
                $value->replies = $repCount;
            }

            if($value->assigned_to == \Auth::user()->id) $my_tickets_count++;
            if($value->status == $open_status->id) $open_tickets_count++;

            $value->lastActivity = Activitylog::where('module', 'Tickets')->where('ref_id', $value->id)->orderBy('created_at', 'desc')->value('created_at');

            $value->sla_plan = $this->getTicketSlaPlan($value->id);
            
            $dd = $this->getSlaDeadlineFrom($value->id);
            $value->sla_rep_deadline_from = $dd[0];
            $value->sla_res_deadline_from = $dd[1];

            $lcnt = false;
            if($value->sla_plan['title'] != self::NOSLAPLAN) {
                if($value->reply_deadline != 'cleared') {
                    $nowDate = Carbon::now();
                    if(!empty($value->reply_deadline)) {
                        $timediff = $nowDate->diffInSeconds(Carbon::parse($value->reply_deadline), false);
                        if($timediff < 0) $lcnt = true;
                    } else {
                        $rep = Carbon::parse($value->sla_rep_deadline_from);
                        $dt = explode('.', $value->sla_plan['reply_deadline']);
                        $rep->addHours($dt[0]);
                        if(array_key_exists(1, $dt)) $rep->addMinutes($dt[1]);
                        $timediff = $nowDate->diffInSeconds($rep, false);
                        if($timediff < 0) $lcnt = true;
                    }
                }
    
                if(!$lcnt) {
                    $nowDate = Carbon::now();
                    if(!empty($value->resolution_deadline)) {
                        $timediff = $nowDate->diffInSeconds(Carbon::parse($value->resolution_deadline), false);
                        if($timediff < 0) $lcnt = true;
                    } else {
                        $rep = Carbon::parse($value->sla_res_deadline_from);
                        $dt = explode('.', $value->sla_plan['due_deadline']);
                        $rep->addHours($dt[0]);
                        if(array_key_exists(1, $dt)) $rep->addMinutes($dt[1]);
                        $timediff = $nowDate->diffInSeconds($rep, false);
                        if($timediff < 0) $lcnt = true;
                    }
                }
            }
            
            $value->is_overdue = 0;
            if($lcnt) {
                $late_tickets_count++;
                $value->is_overdue = 1;
            }
        }
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['tickets']= $tickets;
        $response['total_tickets_count']= $total_tickets_count;
        $response['my_tickets_count']= $my_tickets_count;
        $response['open_tickets_count']= $open_tickets_count;
        $response['unassigned_tickets_count']= $unassigned_tickets_count;
        $response['late_tickets_count']= $late_tickets_count;
        $response['closed_tickets_count']= $closed_tickets_count;
        $response['trashed_tickets_count']= $trashed_tickets_count;
        $response['date_format'] = Session('system_date');
        
        return response()->json($response);
    }

    public function get_ticket_log(Request $request) {
        try {
            // $logs =  DB::table('activity_log')->select('activity_log.*')->join('tickets', 'tickets.id', '=', 'activity_log.ref_id')->where('activity_log.module', 'Tickets')->where('tickets.is_deleted', 0)->orderBy('created_at', 'desc')->get();
            if($request->has('id')) {
                $logs =  Activitylog::where('ref_id', $request->id)->orderByDesc('created_at')->get();
            } else {
                $logs =  Activitylog::where('module', 'Tickets')->orderByDesc('created_at')->limit(150)->get();
            }

            $response['status_code'] = 200;
            $response['success'] = true;
            $response['logs']= $logs;
            return response()->json($response);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function save_ticket_reply(Request $request) {
        $data = $request->all();
        
        $response = array();
        
        try {
            $ticket = Tickets::findOrFail($data['ticket_id']);
            $customer_role_id = DB::table('roles')->where('name', 'Customer')->value('id');
            $vendor_role_id = DB::table('roles')->where('name', 'Vendor')->value('id');
            if(\Auth::user()->user_type != $customer_role_id && \Auth::user()->user_type != $vendor_role_id) {
                $assigned = DepartmentAssignments::where([
                    ['user_id', \Auth::user()->id], ['dept_id', $ticket->dept_id]
                ])->first();
                if(empty($assigned) || empty(DepartmentPermissions::where([
                    ['user_id', \Auth::user()->id], ['dept_id', $ticket->dept_id], ['name', 'd_t_canreply'], ['permitted', 1]
                ])->first())) {
                    throw new Exception('Do not have department permission to reply.');
                }
            }
            
            if($ticket->trashed === 1) {
                if(\Auth::user()->user_type == $customer_role_id) {
                    $ticket->trashed = 0;
                    $ticket->save();
                    $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                    Activitylog::create([
                        "module" => "Tickets",
                        "table_ref" => "tickets",
                        "ref_id" => $ticket->id,
                        "created_by" => \Auth::user()->id,
                        "action_perform" => 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Recycled By by '. $name_link,
                    ]);
                } else {
                    $response['message'] = 'Please restore this ticket first!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
            }
            
            $data['user_id'] = \Auth::user()->id;
            
            if(array_key_exists('inner_attachments', $data)) {
                // target dir for ticket files against ticket id
                $target_dir = public_path().'/files/replies/'.$data['ticket_id'];
                if (!File::isDirectory($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                // set files
                foreach ($data['inner_attachments'] as $key => $value) {
                    if (filter_var($value[1], FILTER_VALIDATE_URL)) { 
                        $file = file_get_contents($value[1]);
                    }else{
                        $file = base64_decode($value[1]);
                    }
                    
                    $target_src = 'public/files/replies/'.$data['ticket_id'].'/'.$value[0];
                        
                    file_put_contents($target_src, $file);
                }
            }

            $data['is_published'] = 0;
            if($request->has('id')) {
                $save_reply = TicketReply::findOrFail($data['id']);
            }

            if($data['type'] == 'publish') {
                $content = $data['reply'];
                $action = 'ticket_reply';
                $this->sendNotificationMail($ticket->toArray(), 'ticket_reply', $content, $data['cc'], $action, $data['attachments']);
                $data['is_published'] = 1;
            }
            unset($data['type']);

            //converting html to secure bbcode
            $bbcode = new BBCode();
            $data['reply'] = $bbcode->convertFromHtml($data['reply']);

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

            if($request->has('id')) {
                $save_reply['reply'] = $data['reply'];
                $save_reply['cc'] = $data['cc'];
                $save_reply['is_published'] = $data['is_published'];
                $save_reply['attachments'] = $data['attachments'];
                $save_reply['updated_at'] = Carbon::now();
                $save_reply['updated_by'] = \Auth::user()->id;

                $save_reply->save();

                $action_perf = 'Ticket ID # <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> Reply updated by '. $name_link;
            } else {
                $save_reply = TicketReply::create($data);

                $action_perf = 'Ticket ID # <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> Reply added by '. $name_link;
            }

            $ticket->updated_at = Carbon::now();
            $ticket->save();

            $sla_updated = false;

            if($data['is_published'] == 1) {
                Activitylog::create([
                    "module" => "Tickets",
                    "table_ref" => "ticket_replies",
                    "ref_id" => $ticket->id,
                    "created_by" => \Auth::user()->id,
                    "action_perform" => $action_perf
                ]);

                $settings = $this->getTicketSettings(['reply_due_deadline']);
                if(isset($settings['reply_due_deadline'])) {
                    if($settings['reply_due_deadline'] == 1) {
                        if(\Auth::user()->user_type == 5) $ticket->reply_deadline = null;
                        else $ticket->reply_deadline = 'cleared';
                        $ticket->save();

                        $sla_updated = $ticket->reply_deadline;
                        
                        Activitylog::create([
                            "module" => "Tickets",
                            "table_ref" => "sla_rep_deadline_from",
                            "ref_id" => $ticket->id,
                            "created_by" => \Auth::user()->id,
                            "action_perform" => $action_perf
                        ]);
                    }
                }
            }

            $save_reply->name = \Auth::user()->name;
            $response['message'] = ($request->has('id')) ? 'Reply Added Successfully! '.$data['attachments'] : 'Reply Updated Successfully! '.$data['attachments'];
            $response['sla_updated'] = $sla_updated;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['data'] = $save_reply;
            return response()->json($response);
    
        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function get_details($id) {
        if(strpos($id, 'T-') === 0) {
            $ticket = Tickets::where('seq_custom_id', $id)->where('is_deleted', 0)->first();
        } else {
            $ticket = Tickets::where('coustom_id', $id)->where('is_deleted', 0)->first();
        }
        if(empty($ticket)) {
            return view('help_desk.ticket_manager.ticket_404');
        }

        $allusers = User::where('user_type','!=',4)->where('user_type','!=',5)->where('is_deleted',0)->get();
        
        $id = $ticket->id;
        // $details = Tickets::with('ticketReplies')->where('id', $id)->first();
        $details = Tickets::where('id', $id)->first();
        $details['ticketReplies'] = TicketReply::where('ticket_id', $details->id)->orderBy('created_at', 'DESC')->get();
        $departments = Departments::all();
        // $ticket = Tickets::all();
        $ticket_customer = Customer::firstWhere('id',$details->customer_id);
        $vendors = Vendors::all();
        $types = TicketType::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();

        $assigned_users = DepartmentAssignments::where('dept_id', $ticket->dept_id)->get()->pluck('user_id')->toArray();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff', 0)->whereIn('id', $assigned_users)->get();
        // $customers = Customer::where('is_deleted', 0)->get();
        $active_user = \Auth::user();
        $projects = Project::all();
        $companies = Company::all();

        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();

        $home = new HomeController();
        $tickets = $home->getCustomerTickets($ticket_customer->id);
        
        $total_tickets_count = $tickets->count();
        $open_tickets_count = 0;
        $closed_tickets_count = 0;
        foreach ($tickets as $key => $value) {
            if($value->status == $open_status->id) $open_tickets_count++;
            if($value->status == $closed_status->id) $closed_tickets_count++;
        }
        
        $bbcode = new BBCode();

        if(!empty($details->ticket_detail))
            $details->ticket_detail = str_replace('/\r\n/','<br>', $bbcode->convertToHtml($details->ticket_detail));
        
        foreach ($details->ticketReplies as $key => $rep) {
            $rep['reply'] = str_replace('/\r\n/','<br>', $bbcode->convertToHtml($rep['reply']));

            if( empty($rep['user_id']) ){
                $user = Customer::where('id', $rep['customer_id'])->first();
                $rep['name'] = $user['first_name'] . ' ' . $user['last_name'];
            }else{
                $user = User::where('id', $rep['user_id'])->first();
                $rep['name'] = $user['name'];
            }
        }

        $sla_plans = SlaPlan::where('sla_status', 1)->where('is_deleted',0)->get();

        $ticket_slaPlan = (Object) $this->getTicketSlaPlan($id);
        
        $dd = $this->getSlaDeadlineFrom($id);
        $details->sla_rep_deadline_from = $dd[0];
        $details->sla_res_deadline_from = $dd[1];


        $ticket_overdue_bg_color = TicketSettings::where('tkt_key','overdue_ticket_background_color')->first();
        if(isset($ticket_overdue_bg_color->tkt_value)) $ticket_overdue_bg_color = $ticket_overdue_bg_color->tkt_value;
        else $ticket_overdue_bg_color = 'white';

        $ticket_overdue_txt_color = TicketSettings::where('tkt_key','overdue_ticket_text_color')->first();
        if(isset($ticket_overdue_txt_color->tkt_value)) $ticket_overdue_txt_color = $ticket_overdue_txt_color->tkt_value;
        else $ticket_overdue_txt_color = 'black';

        $settings = $this->getTicketSettings(['reply_due_deadline', 'reply_due_deadline_when_adding_ticket_note', 'default_reply_time_deadline', 'default_resolution_deadline']);

        foreach ($sla_plans as $key => $value) {
            if($value->title == self::DEFAULTSLA_TITLE) {
                $sla_plans[$key]['reply_deadline'] = $settings['default_reply_time_deadline'];
                $sla_plans[$key]['due_deadline'] = $settings['default_resolution_deadline'];
            }
        }

        $date_format = Session('system_date');

        if(Auth::user()->user_type == 5) {
            return view('help_desk.ticket_manager.cust_ticket_details',compact('ticket_customer','ticket_overdue_bg_color','active_user','details','departments','vendors','types','statuses','priorities','users','projects','companies','total_tickets_count','open_tickets_count','closed_tickets_count','allusers', 'sla_plans', 'ticket_slaPlan','ticket_overdue_txt_color','date_format'));
        }else{
            return view('help_desk.ticket_manager.ticket_details',compact('ticket_customer','ticket_overdue_bg_color','active_user','details','departments','vendors','types','statuses','priorities','users','projects','companies','total_tickets_count','open_tickets_count','closed_tickets_count','allusers', 'sla_plans', 'ticket_slaPlan','ticket_overdue_txt_color','date_format'));
        }
    }
    
    public function del_tkt(Request $request){
        $data  = $request->tickets;

        try{
            for($i=0; $i< sizeof($data);$i++){
                
                $del_tkt = Tickets::where('id',$data[$i])->first();

                $del_tkt->is_deleted = 1;
                $del_tkt->deleted_at = Carbon::now();
                $del_tkt->save();
                
                // Add Delete log
                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                $log_data = array();
                $log_data['module'] = 'Tickets';
                $log_data['table_ref'] = 'tickets';
                $log_data['ref_id'] = $del_tkt->id;
                $log_data['created_by'] = \Auth::user()->id;
                $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/'.$del_tkt->coustom_id.'">'.$del_tkt->coustom_id.'</a>) Permanently Deleted By '. $name_link;
                Activitylog::create($log_data);
            }

            $response['message'] = 'Data Removed Successfully!';
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

    public function move_to_trash_tkt(Request $request){
        $data  = $request->tickets;

        try{
            for($i=0; $i< sizeof($data);$i++) {
                
                $del_tkt = Tickets::where('id',$data[$i])->first();

                $del_tkt->trashed = 1;
                $del_tkt->updated_at = Carbon::now();
                $del_tkt->save();
                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                $log_data = array();
                $log_data['module'] = 'Tickets';
                $log_data['table_ref'] = 'tickets';
                $log_data['ref_id'] = $del_tkt->id;
                $log_data['created_by'] = \Auth::user()->id;
                $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/'.$del_tkt->coustom_id.'">'.$del_tkt->coustom_id.'</a>) Moved to trash By '. $name_link;
                Activitylog::create($log_data);
            }

            $response['message'] = 'Data Successfully Moved to trash!';
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

    public function recycle_tickets(Request $request){
        $data  = $request->tickets;
        try {
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            for($i=0; $i< sizeof($data);$i++){
                $del_tkt = Tickets::where('id',$data[$i])->first();
                
                $del_tkt->trashed = 0;
                $del_tkt->updated_at = Carbon::now();
                $del_tkt->updated_by = \Auth::user()->id;
                $del_tkt->save();
                
                $log_data = array();
                $log_data['module'] = 'Tickets';
                $log_data['table_ref'] = 'tickets';
                $log_data['ref_id'] = $del_tkt->id;
                $log_data['created_by'] = \Auth::user()->id;
                $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/'.$del_tkt->coustom_id.'">'.$del_tkt->coustom_id.'</a>) Recycled By '. $name_link;
                Activitylog::create($log_data);
            }

            $response['message'] = 'Data Recycled Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function flag_ticket(Request $request) {
        try {
            $flag_tkt = Tickets::where('id', $request->id)->first();

            $msg = 'Flagged By';
            if($flag_tkt->is_flagged){
                $flag_tkt->is_flagged = 0;
                $msg = 'Flag Removed By';
            }else{
                $flag_tkt->is_flagged = 1;
            }
            
            $flag_tkt->updated_at = Carbon::now();
            $flag_tkt->updated_by = \Auth::user()->id;
            $flag_tkt->save();
            
            $log_data = array();
            $log_data['module'] = 'Tickets';
            $log_data['table_ref'] = 'tickets';
            $log_data['ref_id'] = $flag_tkt->id;
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/'.$flag_tkt->coustom_id.'">'.$flag_tkt->coustom_id.'</a>) '.$msg.' '. $name_link;
            $log_data['created_by'] = \Auth::user()->id;
            Activitylog::create($log_data);

            $response['message'] = 'Ticket Flagged Successfully!';
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
  
    public function save_ticket_follow_up(Request $request) {
        $data = $request->all();
        $response = array();
        try {
            $ticket = Tickets::findOrFail($data['ticket_id']);

            $customer_role_id = DB::table('roles')->where('name', 'Customer')->value('id');
            $vendor_role_id = DB::table('roles')->where('name', 'Vendor')->value('id');
            if(\Auth::user()->user_type != $customer_role_id && \Auth::user()->user_type != $vendor_role_id) {
                $assigned = DepartmentAssignments::where([
                    ['user_id', \Auth::user()->id], ['dept_id', $ticket->dept_id]
                ])->first();
                if(empty($assigned) || empty(DepartmentPermissions::where([
                    ['user_id', \Auth::user()->id], ['dept_id', $ticket->dept_id], ['name', 'd_t_canfollowup'], ['permitted', 1]
                ])->first())) {
                    throw new Exception('Do not have department permission to add follow up.');
                }
            }

            $data['created_by'] = \Auth::user()->id;
            $ticket_followUp = TicketFollowUp::create($data);

            $ticket->updated_at = Carbon::now();
            $ticket->updated_by = \Auth::user()->id;
            $ticket->save();
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            Activitylog::create([
                "module" => "Tickets",
                "table_ref" => "ticket_follow_up",
                "ref_id" => $ticket->id,
                "created_by" => \Auth::user()->id,
                "action_perform" => 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up added by '. $name_link,
            ]);

            $response['message'] = 'Ticket Follow Up Added Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function update_ticket_follow_up(Request $request) {
        $response = array();

        try {
            if($request->has('data')) {
                $ticket = Tickets::findOrFail($request->ticket_id);

                $data = json_decode($request->data, true);

                $logData = '';
                
                if(is_array($data)) {
                    foreach ($data as $value) {
                        $flwup = TicketFollowUp::findOrFail($value['id']);
                        
                        if(array_key_exists('date', $value)) {
                            $flwup->date = $value['date'];
                        }
                        if(array_key_exists('recurrence_end_val', $value)) {
                            $flwup->recurrence_end_val = $value['recurrence_end_val'];
                        }
                        if(array_key_exists('passed', $value)) {
                            $flwup->passed = $value['passed'];
                        }
                        if(array_key_exists('recurrence_time', $value)) {
                            $flwup->recurrence_time = $value['recurrence_time'];
                            $flwup->recurrence_time2 = NULL;
                        }
                        
                        if(array_key_exists('ticket_update', $value) || array_key_exists('passed', $value)) {
                            try {
                                $ticket->dept_id = $flwup->follow_up_dept_id;
                                $ticket->priority = $flwup->follow_up_priority;
                                $ticket->assigned_to = $flwup->follow_up_assigned_to;
                                $ticket->status = $flwup->follow_up_status;
                                $ticket->type = $flwup->follow_up_type;
                                $ticket->updated_at = Carbon::now();
                                $ticket->save();
                                
                                $logData = 'ticket updated';
                            } catch (Exception $e) {
                                //
                            }
                        }
                        
                        if(!empty($flwup['follow_up_notes'])) {
                            TicketNote::create([
                                'ticket_id' => $flwup->ticket_id,
                                'followup_id' => $flwup->id,
                                'color' => 'rgb(255, 230, 177)',
                                'type' => 'Ticket',
                                'note' => $flwup->follow_up_notes,
                                'visibility' => 'Everyone',
                                'created_by' => \Auth::user()->id
                            ]);

                            $logData .= (empty($logData)) ? 'added a note' : ', added a note';
                        }
            
                        $flwup->updated_at = Carbon::now();
            
                        // $ticket = Tickets::findOrFail($flwup->ticket_id);
                    }
                }
            }

            $flwup->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

            Activitylog::create([
                "module" => "Tickets",
                "table_ref" => "ticket_follow_up",
                "ref_id" => $ticket->id,
                "created_by" => \Auth::user()->id,
                "action_perform" => 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up "'.$logData.'" by ' . $name_link,
            ]);

            $response['status_code'] = 200;
            $response['success'] = true;
            $response['ticket'] = $ticket;
            return response()->json($response);

        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function get_ticket_follow_up($tkt_id) {
        $followUps = TicketFollowUp::where('ticket_id',$tkt_id)->where('is_deleted', 0)->where('passed', 0)->get();

        foreach ($followUps as $key => $value) {
            if($value->is_recurring == 1) {
                $followUps[$key]->date = $this->follow_up_calculation($value);
            }
        }

        $followUpsNew = [];

        foreach ($followUps as $key => $value) {
            if($value->passed == 0) {
                $followUpsNew[] = $value;
            }
        }

        try {
            $response['data'] = $followUps;
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

    private function follow_up_calculation($followUp) {
        $add_value = 0;
        $add_type = '';

        $startDate = new Carbon($followUp->recurrence_start);

        if(($followUp->schedule_time || $followUp->custom_date) && $followUp->schedule_type != 'time') {
            $add_value = $followUp->schedule_time;
            $add_type = $followUp->schedule_type;

            if($followUp->date) $followUpDate = new Carbon($followUp->date);
            else {
                if($followUp->schedule_type == 'custom') {
                    $followUpDate = new Carbon($followUp->custom_date);
                    $add_type = 'years';
                    $add_value = 1;
                } else $followUpDate = $startDate;
            }
        } else {
            if($followUp->recurrence_pattern && $followUp->recurrence_time) {

                if($followUp->date) $followUpDate = new Carbon($followUp->date);
                else $followUpDate = $startDate;
                
                $rec_time = explode(':', $followUp->recurrence_time);

                // set some timezone for proper hour and mins setting
                $followUpDate->timezone("Asia/Karachi");

                $followUpDate->hour = $rec_time[0];
                $followUpDate->minute = $rec_time[1];

                // convert back to utc for further calculations
                $followUpDate->utcOffset(0);
                
                $pattern = explode('|', $followUp->recurrence_pattern);
                $pattern_type = $pattern[0];
                
                switch($pattern_type) {
                    case 'daily':
                        $d_val = $pattern[1]; // days to occur after
                        $add_value = $d_val;
                        $add_type = 'days';
                        break;
                    case 'weekly':
                        $w_val = $pattern[1]; // weeks to occur after
                        $w_days = explode(',', $pattern[2]); // weekly days

                        $today = (String) $followUpDate->day;
                        
                        if(array_search($today, $w_days) == -1) $w_days[] = $today;

                        sort($w_days);

                        if(sizeof($w_days) == 1) {
                            // set follow up on current day or next week
                            $add_value = $w_val*7;
                            $add_type = 'days';
                        } else {
                            $t_ind = array_search($today, $w_days); // today date index
                            $daytoadd = 0;
                            if($t_ind == (sizeof($w_days)-1)) {
                                // set date to first index
                                $daytoadd = $w_val*((int) $w_days[0]+7-(int) $today);
                            } else {
                                // set follow up on next index
                                $daytoadd = $w_val*((int) $w_days[$t_ind+1] - (int) $today);
                            }
                            $add_value = $daytoadd;
                            $add_type = 'days';
                        }
                        break;
                    case 'monthly':
                        $m_val = $pattern[1]; // month
                        $md_val = $pattern[2]; // month day

                        $add_value = $m_val;
                        $add_type = 'months';

                        $followUpDate->set('day', $md_val);
                        break;
                    case 'yearly':
                        $y_val = $pattern[1];
                        $y_month = $pattern[2];
                        $y_m_day = $pattern[3];

                        $add_value = $y_val;
                        $add_type = 'years';

                        $followUpDate->month = $y_month;
                        $followUpDate->day = $y_m_day;
                        break;
                    default:
                        break;
                }
            }
        }

        // $rc_start = new Carbon($followUp->recurrence_start);

        // if($rc_start->diffInDays(new Carbon($followUp->created_at)) > 0 && $rc_start->diffInDays(new Carbon()) > 0) {
        //     // diff bw creation and start date
        //     $followUpDate->addDays($rc_start->diffInDays(new Carbon($followUp->created_at), 'days'), 'days');
        // }

        $nowDate = Carbon::now();
        $timediff = $nowDate->diffInSeconds($followUpDate, false);

        if($timediff < 0) {
            $idata = array();
            // follow up time to update ticket
            $idata['ticket_update'] = true;

            // update the recurrence time for next all ocurrences
            if($followUp->recurrence_time2) $idata['recurrence_time'] = $followUp->recurrence_time2;
            
            if($followUp->recurrence_end_type == 'count') {
                if((int) $followUp->recurrence_end_val > 0) $idata['recurrence_end_val'] = (int) $followUp->recurrence_end_val-1;
                else $idata['passed'] = 1;
            }
            
            if(!array_key_exists('passed', $idata)) {
                if($add_type == 'minutes') $followUpDate->addMinutes($add_value);
                else if($add_type == 'hours') $followUpDate->addHours($add_value);
                else if($add_type == 'days') $followUpDate->addDays($add_value);
                else if($add_type == 'weeks') $followUpDate->addWeeks($add_value);
                else if($add_type == 'months') $followUpDate->addMonths($add_value);
                else if($add_type == 'years') $followUpDate->addYears($add_value);

                if($followUp->recurrence_end_type == 'date') {
                    $endDate = new Carbon($followUp->recurrence_end_val);
                    if($followUpDate->isAfter($endDate)) $idata['passed'] = 1;
                }

                if(!array_key_exists('passed', $idata)) $idata['date'] = new Carbon($followUpDate);
            }

            if(array_key_exists('passed', $idata)) $followUp->passed = $idata['passed'];
            if(array_key_exists('date', $idata)) $followUp->date = $idata['date'];
            if(array_key_exists('recurrence_time', $idata)) $followUp->recurrence_time = $idata['recurrence_time'];
            if(array_key_exists('recurrence_end_val', $idata)) $followUp->recurrence_end_val = $idata['recurrence_end_val'];

            $followUp->save();

            if(!array_key_exists('passed', $idata)) $this->follow_up_calculation($followUp);
        }

        return $followUpDate;
    }
    
    public function del_ticket_follow_up($flwup_id){
        $followUp = TicketFollowUp::findOrFail($flwup_id);

        try{
            $ticket = Tickets::findOrFail($followUp->ticket_id);

            $followUp->is_deleted = 1;
            $followUp->deleted_by = \Auth::user()->id;
            $followUp->deleted_at = Carbon::now();

            $followUp->save();

            $ticket->updated_at = Carbon::now();
            $ticket->updated_by = \Auth::user()->id;
            $ticket->save();
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            Activitylog::create([
                "module" => "Tickets",
                "table_ref" => "ticket_follow_up",
                "ref_id" => $ticket->id,
                "created_by" => \Auth::user()->id,
                "action_perform" => 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up deleted by '. $name_link,
            ]);

            $response['message'] = 'Follow Up deleted successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
    
    public function save_ticket_note(Request $request) {
        $data = $request->all();
        $response = array();
       
        try{
            $action_performed = '';
            $ticket = Tickets::findOrFail($data['ticket_id']);

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

            if(array_key_exists('id', $data)){
                $note = TicketNote::findOrFail($data['id']);

                $note->color = $data['color'];
                $note->type = $data['type'];
                $note->note = $data['note'];
                $note->visibility = (array_key_exists('visibility', $data)) ? $data['visibility'] : '';
                $note->updated_by = \Auth::user()->id;
                $note->updated_at = Carbon::now();

                $note->save();

                $data = $note;

                $action_performed = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Note updated by '. $name_link;
            }else{
                $data['created_by'] = \Auth::user()->id;
                $note = TicketNote::create($data);

                $action_performed = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Note added by '. $name_link;
            }

            $ticket->updated_at = Carbon::now();
            $ticket->updated_by = \Auth::user()->id;
            $ticket->save();
            
            $sla_updated = false;
            $settings = $this->getTicketSettings(['reply_due_deadline_when_adding_ticket_note']);
            if(isset($settings['reply_due_deadline_when_adding_ticket_note'])) {
                if($settings['reply_due_deadline_when_adding_ticket_note'] == 1) {
                    $ticket->reply_deadline = 'cleared';
                    $ticket->save();

                    $sla_updated = 'cleared';

                    Activitylog::create([
                        "module" => "Tickets",
                        "table_ref" => "sla_rep_deadline_from",
                        "ref_id" => $ticket->id,
                        "created_by" => \Auth::user()->id,
                        "action_perform" => $action_performed
                    ]);
                }
            }

            Activitylog::create([
                "module" => "Tickets",
                "table_ref" => "ticket_notes",
                "ref_id" => $ticket->id,
                "created_by" => \Auth::user()->id,
                "action_perform" => $action_performed
            ]);

            if($request->tag_emails != null && $request->tag_emails != '') {

                $emails = explode(',',$request->tag_emails);
        
                for( $i = 0; $i < sizeof($emails); $i++ ) {
                    
                    $user = User::where('is_deleted',0)->where('email',$emails[$i])->first();
                    $ticket = Tickets::where('is_deleted', 0)->where('id',$request->ticket_id)->first();
        
                    $notify = new NotifyController();
                    $sender_id = \Auth::user()->id;
                    $receiver_id = $user->id;
                    $slug = url('ticket-details') .'/'.$ticket->coustom_id;
                    $type = 'ticket_notes';
                    $data = 'data';
                    $title = 'Ticket Tag Notification';
                    $icon = 'fas fa-tag';
                    $class = 'btn-success';
                    $desc = 'You were tagged by '.\Auth::user()->name . ' on Ticket # ' . $ticket->coustom_id;
        
                    $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                }
        
            }

            $response['message'] = 'Ticket Note Saved Successfully!';
            $response['sla_updated'] = $sla_updated;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['data'] = $note;
            return response()->json($response);

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

                 
    }

    public function addTicketCustomer(Request $request) {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'phone' => 'required'
            ]);

            $data = $request->all();

            // non customer user with same email exists
            if(!empty(Customer::where('email', $data['email'])->first())) throw new Exception('Customer email already exists!');
            if(!empty(User::where('email', $data['email'])->first())) throw new Exception('Email already exists!');

            $c = array(
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name'],
                "email" => $data['email'],
                "username" => $data['email'],
                "phone" => $data['phone']
            );

            // $last = Customer::orderBy('id', 'desc')->first();
            // if(!empty($last)){
            //     $c['woo_id'] = intval($last->id)+1;
            // }else{
            //     $c['woo_id'] = '1';
            // }

            if(isset($data['customer_login'])) {
                $c['has_account'] = 1;

                $random_no = Str::random(15);

                $user = new User();
                $user->name = $data['first_name'] .' ' .  $data['last_name'];
                $user->email = $data['email'];
                $user->phone_number = $data['phone'];
                $user->alt_pwd = Crypt::encryptString($random_no);
                $user->password = Hash::make($random_no);
                $user->status = 1;
                $user->user_type = 5;
                $user->save();
                
                try {
                    $mailer = new MailController();
                    $mailer->UserRegisteration($data['email']);
                } catch(Exception $e) {
                    // maybe mail not sent
                }
            }else{
                $c['has_account'] = 0;
            }
            $c['phone'] = $data['phone'];
            $customer = Customer::create($c);

            return $customer->id;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function update_ticket_customer(Request $request) {
        try {
            $data = $request->all();
            // $customer = Customer::find($data["id"]);
            // $customer->first_name = $data["first_name"];
            // $customer->last_name = $data["last_name"];
            // $customer->phone = $data["phone"];
            // $customer->company_id = $data["company"];
            // $customer->updated_at = Carbon::now();
            // $customer->save();
            if(isset($data['new_customer'])) {
                $data['customer_id'] = $this->addTicketCustomer($request);
                $customer = Customer::find($data["customer_id"]);
            } else {
                $customer = Customer::find($data["customer_id"]);
                if(isset($data['email']) || isset($data['phone'])) {
                    if($data['email'] != $customer->email || $data['phone'] != $customer->phone) {
                        $customer->email = $data['email'];
                        $customer->phone = $data['phone'];
                        $customer->updated_at = Carbon::now();
                        $customer->save();
                    }
                }
            }

            $ticket = Tickets::find($data["ticket_id"]);
            
            $ticket->customer_id = $customer->id;
            $ticket->save();

            $response['message'] = 'Ticket Customer Changed Successfully';
            $response['status_code'] = 200;
            $response['data'] = $customer;
            $response['success'] = true;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function getTicketNotes(Request $request) {
        try {
            if(!$request->has('id')) throw new Exception('Ticket id missing');

            $id = $request->id;
            $type = $request->type;
            if(!is_array($id)) $id = [$id];

            $notes = json_decode(DB::table('users')
            ->join('ticket_notes', 'users.id', '=', 'ticket_notes.created_by')
            ->select('ticket_notes.*', 'users.name', 'users.profile_pic')
            ->where('ticket_notes.is_deleted', 0)->whereIn('ticket_notes.ticket_id', $id)
            ->where(function($q) {
                return $q->where('ticket_notes.visibility', 'like', '%'.\Auth::user()->id.'%')->orWhere('ticket_notes.created_by', \Auth::user()->id);
            })
            ->when($request->has('type'), function($q) use($type) {
                return $q->where('ticket_notes.type', $type);
            })->orderBy('created_at', 'desc')
            ->get(), true);
    
            foreach ($notes as $key => $value) {
                $fwps = TicketFollowUp::where('is_deleted', 0)->where('id', $value['followup_id'])->first();
        
                $notes[$key]['followUp_date'] = null;
                if(!empty($fwps)) {
                    $notes[$key]['followUp_date'] = $fwps->date;
                }
            }
               
            $response['message'] = 'Success';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['notes']= $notes;
            
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            
            return response()->json($response);
        }
    }

    public function del_ticket_note(Request $request){
        try{
            if(!empty($request->id)){
                $note = TicketNote::findOrFail($request->id);
                
                $ticket = Tickets::findOrFail($note->ticket_id);

                $note->is_deleted = 1;
                $note->deleted_by = \Auth::user()->id;
                $note->deleted_at = Carbon::now();

                $note->save();

                $ticket->updated_at = Carbon::now();
                $ticket->updated_by = \Auth::user()->id;
                $ticket->save();
                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                Activitylog::create([
                    "module" => "Tickets",
                    "table_ref" => "ticket_notes",
                    "ref_id" => $ticket->id,
                    "created_by" => \Auth::user()->id,
                    "action_perform" => 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Note deleted by '. $name_link,
                ]);
            }

            $response['message'] = 'Ticket Note Deleted Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function mergeTickets(Request $request) {
        try {
            if($request->has('tickets')) {
                $ids = $request->tickets;
                $ticket = Tickets::whereIn('id', $ids)->where('is_deleted', 0)->latest('updated_at')->first();

                if($ticket->trashed == 1) {
                    $response['message'] = 'Please restore tickets to merge';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }

                // splice the latest ticket from remaining
                array_splice($ids, array_search($ticket->id, $ids), 1);

                $tickets = Tickets::whereIn('id', $ids)->where('is_deleted', 0)->get();

                foreach ($tickets as $i => $value) {
                    if($value->trashed == 1) {
                        $response['message'] = 'Please restore tickets to merge';
                        $response['status_code'] = 500;
                        $response['success'] = false;
                        return response()->json($response);
                    }

                    TicketReply::create([
                        'ticket_id' => $ticket->id,
                        'user_id' => $ticket->created_by,
                        'reply' => $value->ticket_detail,
                        'created_at' => $value->created_at,
                        'updated_at' => $value->updated_at
                    ]);

                    $assets = Assets::where('ticket_id', $value->id)->get();

                    foreach ($assets as $j => $item) {
                        $item->ticket_id = $ticket->id;
                        $item->save();
                    }

                    $followups = TicketFollowUp::where('ticket_id', $value->id)->get();

                    foreach ($followups as $j => $item) {
                        $item->ticket_id = $ticket->id;
                        $item->save();
                    }

                    $notes = TicketNote::where('ticket_id', $value->id)->get();

                    foreach ($notes as $j => $item) {
                        $item->ticket_id = $ticket->id;
                        $item->save();
                    }

                    $replies = TicketReply::where('ticket_id', $value->id)->get();

                    foreach ($replies as $j => $item) {
                        $item->ticket_id = $ticket->id;
                        $item->save();
                    }
                    $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                    $log_data = array();
                    $log_data['module'] = 'Tickets';
                    $log_data['table_ref'] = 'tickets';
                    $log_data['ref_id'] = $ticket->id;
                    $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/'.$value->coustom_id.'">'.$value->coustom_id.'</a>) merged into (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) By '. $name_link;
                    $log_data['created_by'] = \Auth::user()->id;
                    
                    Activitylog::create($log_data);

                    $value->delete();
                }

                $ticket->updated_at = Carbon::now();
                $ticket->updated_by = \Auth::user()->id;
                $ticket->save();
                
                $response['message'] = 'Tickets Merged Successfully!';
                $response['success'] = true;
            } else {
                $response['message'] = 'Missing ids parameter!';
                $response['success'] = false;
            }

            $response['status_code'] = 200;
            return response()->json($response);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function set_sla_plan(Request $request) {
        try {
            $ticket = Tickets::findOrFail($request->ticket_id);
            $sla_plan = SlaPlan::findOrFail($request->sla_plan_id);

            if($sla_plan->sla_status == 0) {
                $response['message'] = 'Please select some other SLA Plan!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }
            
            $assoc_plan = SlaPlanAssoc::where('ticket_id', $ticket->id)->first();
            if(empty($assoc_plan)) {
                SlaPlanAssoc::create([
                    'sla_plan_id' => $sla_plan->id,
                    'ticket_id' => $ticket->id
                ]);
            } else {
                $assoc_plan->sla_plan_id = $sla_plan->id;
                $assoc_plan->save();
            }

            $ticket->reply_deadline = null;
            $ticket->resolution_deadline = null;
            $ticket->save();
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $log_data = array();
            $log_data['module'] = 'Tickets';
            $log_data['table_ref'] = 'sla_rep_deadline_from';
            $log_data['ref_id'] = $request->ticket_id;
            $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Sla Plan Association Updated By '. $name_link;
            $log_data['created_by'] = \Auth::user()->id;
            
            Activitylog::create($log_data);

            $log_data['table_ref'] = 'sla_res_deadline_from';
            Activitylog::create($log_data);

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

    public function updateTicketDeadlines(Request $request) {
        try {
            $ticket = Tickets::findOrFail($request->ticket_id);

            $ticket->reply_deadline = $request->rep_deadline;
            $ticket->resolution_deadline = $request->res_deadline;
            $ticket->updated_at = Carbon::now();
            $ticket->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $log_data = array();
            $log_data['module'] = 'Tickets';
            $log_data['table_ref'] = 'tickets';
            $log_data['ref_id'] = $request->ticket_id;
            $log_data['action_perform'] = 'Ticket (ID <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) reply & resolution deadlines Updated By '. $name_link;
            $log_data['created_by'] = \Auth::user()->id;
            
            Activitylog::create($log_data);

            $response['message'] = 'Deadlines reset successfully';
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

    public function upload_attachments(Request $request) {
        try {
            $ticket = Tickets::findOrFail($request->ticket_id);

            // target dir for ticket files against ticket id
            $target_dir = public_path().'/files'.'/'.$request->module.'/'.$request->ticket_id;

            if (!File::isDirectory($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file = $request->file('attachment');

            //Move Uploaded File
            // $file->move($target_dir, $file->getClientOriginalName());
            if($file->move($target_dir, $request->fileName.'.'.$file->getClientOriginalExtension())) {
                if($request->module == 'tickets') {
                    if(!empty($ticket->attachments)) $ticket->attachments .= ','.$request->fileName.'.'.$file->getClientOriginalExtension();
                    else $ticket->attachments = $request->fileName.'.'.$file->getClientOriginalExtension();
    
                    $response['attachments'] = $ticket->attachments;
                    $ticket->save();
                } else {
                    $response['attachments'] = $request->fileName.'.'.$file->getClientOriginalExtension();
                }
            } else {
                $response['message'] = 'Failed to move file';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

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

    public function delete_attachment(Request $request) {
        try {
            // target dir for ticket files against ticket id
            $target_dir = public_path().'/files'.'/'.$request->module.'/'.$request->id.'/'.$request->fileName;

            if(!is_readable($target_dir)) { 
                $response['message'] = 'File is not readable!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            if($request->module == 'tickets') {
                $ticket = Tickets::findOrFail($request->id);
            } else {
                $ticket = TicketReply::findOrFail($request->reply_id);
            }

            unlink($target_dir);

            $attaches = $ticket->attachments;
            $attaches = str_replace($request->fileName.',', '', $attaches);
            $attaches = str_replace($request->fileName, '', $attaches);

            $ticket->attachments = $attaches;

            $ticket->save();

            $response['status_code'] = 200;
            $response['success'] = true;
            $response['attachments'] = $attaches;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function fetch_followups(Request $request) {
        try {
            $startDate = new Carbon($request->startDate);
            // $startDate->subDay();

            $tickets = [];
            $followUps = [];
            if($request->has('restOfMonth') && $request->restOfMonth === true) {
                $followUps = TicketFollowUp::where('passed', 0)->where('is_deleted', 0)->whereDate('created_at', '>=', $startDate)->get();
            } else {
                $followUps = TicketFollowUp::where('passed', 0)->where('is_deleted', 0)->whereDate('created_at', $startDate)->get();
            }

            $ticketIds = [];
            foreach ($followUps as $key => $value) {
                $ticketIds[] = $value->ticket_id;
                if($value->is_recurring == 1) {
                    $followUps[$key]->date = $this->follow_up_calculation($value);
                }
            }

            $tickets = DB::Table('tickets')->select('tickets.*', DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'), 'customers.phone as customer_phone')->join('customers','customers.id','=','tickets.customer_id')
            ->where('tickets.trashed', 0)->where('tickets.is_deleted', 0)->whereIn('tickets.id', $ticketIds)->get();

            foreach($tickets as $value) {
                $value->tech_name = 'Unassigned';
                if(!empty($value->assigned_to)) {
                    $u = User::where('id', $value->assigned_to)->first();
                    if(!empty($u)) $value->tech_name = $u->name;
                }
            }

            $response['message'] = '';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['tickets'] = $tickets;
            $response['followups'] = $followUps;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function search_ticket(Request $request){
        $data = $request->all();
        $response = array();
        try{
            $id = $data['id'];

            // $response = Tickets::where('id', 'like','%' . $id . '%')
            // ->orWhere('seq_custom_id','like','%' . $id . '%')
            // ->orWhere('coustom_id','like','%' . $id . '%')
            // ->orWhere('subject','like','%' . $id . '%')->get();

            // $response = DB::select("SELECT * from tickets tkt INNER JOIN users user ON tkt.created_by = user.id INNER JOIN customers cust ON tkt.customer_id = cust.id WHERE cust.first_name LIKE '%$id%' OR user.name LIKE '%$id%' OR tkt.subject LIKE '%$id%' OR tkt.coustom_id LIKE '%$id%' OR tkt.seq_custom_id LIKE '%$id%'");

            // $response = DB::select("SELECT cust.id,cust.username,cust.first_name,cust.last_name,cust.email,cust.phone, comp.name FROM customers cust INNER JOIN companies comp ON cust.company_id = comp.id WHERE cust.username LIKE '%$id%' OR cust.first_name LIKE '%$id%' OR cust.last_name LIKE '%$id%' OR cust.email LIKE '%$id%' OR cust.phone LIKE '%$id%' OR comp.name LIKE '%$id%' ");



            $response = (is_numeric($id)) ? DB::select("SELECT * FROM tickets WHERE id=$id OR seq_custom_id=$id;") : DB::select("SELECT * FROM `tickets` WHERE `coustom_id` LIKE '%$id%' OR `subject` LIKE '%$id%';");
             
            return response()->json($response);
        }catch(Exception $e){
            return response()->json($e->getMessage());
        }
    }

    public function ticket_notification(Request $request) {
        try {
            if($request->has('id')) {
                $ticket = Tickets::where('id', $request->id)->where('trashed', 0)->where('is_deleted', 0)->first();
                if(!empty($ticket)) {
                    $data_id = '';
                    if($request->has('data_id')) $data_id = $request->data_id;
    
                    $this->sendNotificationMail($ticket->toArray(), $request->template, '', '', $request->action, $data_id);
                } else {
                    $response['message'] = 'Ticket is either deleted or not exists!';
                }
            }

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

    // Send Ticket mails to users.
    // $data_id is current note saved id
    // tempalte code is when save record it says tempalte_create_note & on update tmeplate_update_note;
    public function sendNotificationMail($ticket, $template_code, $reply_content='', $cc='', $action_name='', $data_id=null, $mail_frm_param='') {
        try {
            /*********** dept mail for email notification ***************/
            $sendingMailServer = Mail::where('mail_dept_id', $ticket['dept_id'])->where('is_deleted', 0)->where('is_default', 'yes')->first();
            if(empty($sendingMailServer)) {
                $sendingMailServer = Mail::where('mail_dept_id', $ticket['dept_id'])->where('is_deleted', 0)->first();
                
                if(empty($sendingMailServer)) {
                    // dept email queue not found
                    throw new Exception('Ticket department email not found!');
                }
            }

            $mail_from = $sendingMailServer->mailserver_username;

            $notification_message = '';
            $notification_title = '';
            $user = null;
            $attachs = '';
            $pathTo = '';
            $customer_send = false;
            $cust_template_code = '';

            if($action_name == 'cron') {
                if($template_code == 'ticket_create') {
                    $notification_message = 'Ticket Generated by System';
                    $notification_title = 'New Ticket Generated';
                } else if($template_code == 'ticket_reply') {
                    $action_name == 'ticket_reply';
                }
            } else {
                $user = DB::table('users')->where('id', \Auth::user()->id)->first();
                $notification_message = 'Ticket Created By' . $user->name;
                $notification_title = 'New Ticket Created';
            }

            if($template_code == 'ticket_create') {
                $customer_send = true;
                $cust_template_code = 'auto_res_ticket_create';

                $attachs = $ticket['attachments'];
                $pathTo = 'tickets/'.$ticket['id'];
            } else if($action_name == 'Subject updated') {
                $attachs = $ticket['attachments'];
                $pathTo = 'tickets/'.$ticket['id'];
            } else if($action_name == "ticket_reply") {
                $customer_send = true;
                $cust_template_code = 'auto_res_ticket_reply';

                if(!empty($user)) $mail_from = $user->email;
                $attachs = $data_id;
                $pathTo = 'replies/'.$ticket['id'];

                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' }  Reply Added by '. $user->name;
                $notification_title = 'Reply Added';
            }else if($action_name == "Type updated") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Type Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Type Updated';
            }else if($action_name == "Deptartment updated") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Department Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Department Updated';
            }else if($action_name == "Assignment updated") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Tech Assigned Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Tech Assigned Updated';
            }else if($action_name == "Status updated") {
                if($ticket['status_name'] == 'Closed') {
                    $customer_send = true;
                    $cust_template_code = 'auto_res_ticket_closed';
                }
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Status Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Status Updated';
            }else if($action_name == "Priority updated") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Priority Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Priority Updated';
            }else if( $action_name == "Flag removed") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Flag removed by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Flag Removed';
            }else if( $action_name == "Flagged") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Flagged by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Ticket Flagged';
            }else if($action_name == "Note added") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Note Added by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Note Added';
            }else if($action_name == "Note updated") {
                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Note Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Note Updated';
            }

            $tech = null;
            if(!empty($ticket['assigned_to'])) {
                $tech = User::where('id', $ticket['assigned_to'])->first();
            }
            
            $customer = null;
            if(!empty($ticket['customer_id'])) {
                $customer = Customer::where('id', $ticket['customer_id'])->first();
            }

            $mail_template = DB::table('templates')->where('code', $template_code)->first();
            $cust_template = DB::table('templates')->where('code', $cust_template_code)->first();
        
            if(empty($mail_template)) throw new Exception('"'.$template_code.'" Template not found');
            if($customer_send && empty($cust_template_code)) throw new Exception('"'.$cust_template_code.'" Template not found');
            
            $template_input = array(
                array('module' => 'Tech', 'values' => (!empty($tech)) ? $tech->attributesToArray() : []),
                array('module' => 'Customer', 'values' => (!empty($customer)) ? $customer->attributesToArray() : []),
                array('module' => 'Ticket', 'values' => (!empty($ticket)) ? $ticket : []),
            );
            
            if(!empty($user)) {
                // $template_input[] = array('module' => 'User', 'values' => $user);
                $template_input[] = array('module' => 'Creator', 'values' => $user);
            }

            if(($template_code == 'ticket_note_create' || $template_code == 'ticket_note_update') && !empty($data_id)) {
                $data = TicketNote::findOrFail($data_id)->toArray();
                $template_input[] = array("module"=>"Ticket", "values"=>$data);
            }

            $mailer = new MailController();
            $mailer->cc_string = $cc;

            $message = $mail_template->template_html;
            $cust_message = empty($cust_template) ? '' : $cust_template->template_html;
            
            $message = $mailer->template_parser($template_input, $message, $reply_content, $action_name);
            $cust_message = $mailer->template_parser($template_input, $cust_message, $reply_content, $action_name);
            
            if(empty($mail_from)) $mail_from = $mail_frm_param;

            if(!empty($cust_message)) {
                $subject = $mailer->parseSubject($ticket['coustom_id'].' '.$ticket['subject'], $ticket, $cust_template, $sendingMailServer->mail_queue_address);

                if(!empty($reply_content)) {
                    // this is a reply
                    $subject = 'Re: '.$subject;
                }

                if($sendingMailServer->outbound == 'yes' && trim($sendingMailServer->autosend) == 'yes') {
                    if(!empty($customer)) $mailer->sendMail($subject, $cust_message, $mail_from, $customer->email, $customer->first_name.' '.$customer->last_name, $action_name, $attachs, $pathTo);
                }
            }

            if(!empty($message)) {
                // parse template subject
                $subject = $mailer->parseSubject($ticket['coustom_id'].' '.$ticket['subject'], $ticket, $mail_template, $sendingMailServer->mail_queue_address);

                if($template_code == 'ticket_note_create' || $template_code == 'ticket_note_update') {
                    // $customer_send = false;
                    $assigned_users = DepartmentPermissions::where('dept_id', $ticket['dept_id'])->where('permitted', 1)->where('name', 'd_t_cannotealerts')->get()->pluck('user_id')->toArray();
                } else if($template_code == 'ticket_reassigned') {
                    $assigned_users = DepartmentPermissions::where('dept_id', $ticket['dept_id'])->where('permitted', 1)->where('name', 'd_t_canassignment')->get()->pluck('user_id')->toArray();
                } else if($template_code == 'ticket_followup') {
                    // $customer_send = false;
                    $assigned_users = DepartmentPermissions::where('dept_id', $ticket['dept_id'])->where('permitted', 1)->where('name', 'd_t_cantktfollowalerts')->get()->pluck('user_id')->toArray();
                } else {
                    $assigned_users = DepartmentAssignments::where('dept_id', $ticket['dept_id'])->get()->pluck('user_id')->toArray();

                    if(!empty($reply_content)) {
                        // this is a reply
                        $subject = 'Re: '.$subject;
                    }
                }
                
                $users_list = User::whereIn('id', $assigned_users)->get()->toArray();

                if($sendingMailServer->outbound == 'yes' || $action_name == "ticket_reply") {
                    if(!empty($tech)) $users_list[] = $tech->attributesToArray();
                    
                    if(sizeof($users_list) > 0) $mailer->sendMail($subject, $message, $mail_from, $users_list, '', '', $attachs, $pathTo);
                }

                $allwd_users = [];

                try {
                    $notify = new NotifyController();
                    foreach ($users_list as $key => $value) {
                        $allwd_users[] = [$value['email'], $value['name']];
                        $sender_id = 0;
                        $receiver_id = $value['id'];
                        $slug = url('ticket-details/'.$ticket['coustom_id']);
                        $type = 'Tickets';
                        $data = 'data';
                        $title = $notification_title;
                        $icon = 'ti-calendar';
                        $class = 'btn-success';
                        $desc = $notification_message;
                        
                        $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                    }
                } catch(Exception $e) {
                    // ignore for now
                } 
            }
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function invoices() {
        $invoice = \ConsoleTVs\Invoices\Classes\Invoice::make()
            ->addItem('Test Item', 10.25, 2, 1412)
            ->addItem('Test Item 2', 5, 2, 923)
            ->addItem('Test Item 3', 15.55, 5, 42)
            ->addItem('Test Item 4', 1.25, 1, 923)
            ->addItem('Test Item 5', 3.12, 1, 3142)
            ->addItem('Test Item 6', 6.41, 3, 452)
            ->addItem('Test Item 7', 2.86, 1, 1526)
            ->addItem('Test Item 8', 5, 2, 923, 'https://dummyimage.com/64x64/000/fff')
            ->number(4021)
            ->with_pagination(true)
            ->duplicate_header(true)
            ->due_date(Carbon::now()->addMonths(1))
            ->notes('Lrem ipsum dolor sit amet, consectetur adipiscing elit.')
            ->customer([
                'name'      => 'Èrik Campobadal Forés',
                'id'        => '12345678A',
                'phone'     => '+34 123 456 789',
                'location'  => 'C / Unknown Street 1st',
                'zip'       => '08241',
                'city'      => 'Manresa',
                'country'   => 'Spain',
            ])
            ->download('demo');
    }

    public function StatesAndCountries() {
        $countries = DB::Table('countries')->get();
        $us = DB::Table('countries')->where('short_name', "US")->first();
        $states = [];

        if(!empty($us)) $states = DB::Table('states')->where('country_id', $us->id)->get();

        $response['message'] = 'States and Countries Lists';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['countries'] = $countries;
        $response['states'] = $states;
        return response()->json($response);
    }
    
    public function listStates(Request $request) {
        try {
            $states = [];
            if($request->has('countryId')) Country::findOrFail($request->countryId);

            $states = DB::Table('states')->where('country_id', $request->countryId)->get();

            $response['message'] = 'Success';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['list'] = $states;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function searchEmails(Request $request) {
        try {
            $list = array();
            if(!empty($request->value)) {
                $list = Customer::where('email', 'like', $request->value.'%')->limit(20)->pluck('email');
            }

            $response['success'] = true;
            $response['list'] = $list;
            $response['status_code'] = 200;
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function getDepartmentStatus(Request $request) {
        $status = TicketStatus::whereRaw("find_in_set($request->id,department_id)")->get();
        $dept_assigns = DepartmentAssignments::where('dept_id', $request->id)->get()->pluck('user_id')->toArray();
        $users = User::whereIn('id', $dept_assigns)->get();

        $response['message'] = 'Department Status List';
        $response['status'] = 200;
        $response['success'] = true;
        $response['status'] = $status;
        $response['users'] = $users;
        return response()->json($response);
    }

    private function buildNotification($data, $type='Created') {
        $nn = new ProjectManagerController();
        if(isset($data['assigned_to'])){
            $sender_id = \Auth::user()->id;
            $receiver_id = $data['assigned_to'];
            $slug = $data['subject'];
            $type = 'notification';
            $data = 'data';
            $title = 'Ticket '.$type;
            $icon = 'ti-calendar';
            $class = 'btn-success';
            $desc = 'Ticket '.$type.' by '.\Auth::user()->name;
            $pm_id = $project->project_manager_id;

            $nn->notify($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc,$pm_id,'Ticket C&A by '.\Auth::user()->name);
            // $nn->notify($sender_id,$pm_id,$slug,$type,$data,$title,$icon,$class,'Ticket C&A by '.\Auth::user()->name);
        } else {

            $sender_id = \Auth::user()->id;
            $pm_id = $project->project_manager_id;
            $slug = 'roadmap/'.$project->project_slug;
            $type = 'notification';
            $data = 'data';
            $title = 'Ticket '.$type;
            $icon = 'ti-calendar';
            $class = 'btn-success';
            $desc = 'Task created by '.\Auth::user()->name;
            
            $nn->notify($sender_id,0,$slug,$type,$data,$title,$icon,$class,$desc, $pm_id,$desc);
        }
    }

    public function send_notification(Request $request) {
        try {
            $admin_users = User::where('user_type', 1)->get()->toArray();
    
            $notify = new NotifyController();
            foreach ($admin_users as $key => $value) {
                $sender_id = \Auth::user()->id;
                $receiver_id = $value['id'];
                $slug = $request->slug;
                $type = $request->type;
                $data = 'data';
                $title = $request->title;
                $icon = $request->icon;
                $class = 'btn-success';
                $desc = $request->description;
                
                $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
            }

            $response['message'] = 'Notification sent successfully!';
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