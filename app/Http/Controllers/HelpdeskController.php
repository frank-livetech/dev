<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomerManager\CustomerlookupController;
use App\Http\Controllers\ActivitylogController;
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
use App\Models\TicketSharedEmails;
use App\Models\Tickets;
use App\Models\Vendors;
use App\Models\TicketReply;
use App\Models\TicketFollowUp;
use App\Models\TicketFollowupLogs;
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
use App\Models\ResTemplateCat;
use App\Models\Country;
use App\Models\ResponseTemplate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Exception;
use App\Models\TicketView;
use Genert\BBCode\BBCode;
use App\Models\Mail;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\SystemManager\SettingsController;
use App\Http\Controllers\CustomerPanel\HomeController;
use App\Http\Controllers\SystemManager\MailController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\ProjectManager\ProjectManagerController;
use App\Models\DepartmentPermissions;
use Faker\Calculator\Ean;
use Illuminate\Database\Eloquent\Builder;
use PHPMailer\PHPMailer\PHPMailer;
use Illuminate\Support\Facades\URL;
use Session;

require 'vendor/autoload.php';
// require '../vendor/autoload.php';

class HelpdeskController extends Controller
{
    // *************   PROPERTIES   ****************

    const DEFAULTSLA_TITLE = 'Default SLA';
    const NOSLAPLAN = 'No SLA Assigned';
    const CUSTOMID_FORMAT = 'XXX-999-9999';


    // ***************   METHODS   *****************


    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }

    public function ticket_manager($dept,$sts){
        $dept = Departments::where('dept_slug',$dept)->first();
        $dept_name = $dept->name;
        $dept = $dept->id;

        if($sts == 'all') {
            $sts = 'all';
            $status_name = 'All';
        }else{
            $status =  TicketStatus::where('slug',$sts)->first();
            $status_name = $status->name;
            $sts = $status->id;
        }

        
        

          
        $departments = Departments::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();
        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();

        $tickets_followups = TicketFollowUp::where('passed', 0)->where('is_deleted', 0)->get();

        // foreach ($tickets_followups as $key => $value) {
        //     if($value->is_recurring == 1) {
        //         $tickets_followups[$key]->date = $this->follow_up_calculation($value);
        //     }
        // }

        // $followUpsNew = [];

        // foreach ($tickets_followups as $key => $value) {
        //     if($value->passed == 0) {
        //         $followUpsNew[] = $value;
        //     }
        // }
        
        // $tickets_followups = $followUpsNew;

        $url_type = '';
        if(isset($request->type)) {
            $url_type = $request->type;
        }

        $loggedInUser = \Auth::user()->id;
        $date_format = Session('system_date');
        $projects = Project::all();

        $staffs = User::where('user_type','!=',5)->where('user_type','!=',4)->get();
        $tkt_refresh_time = SystemSetting::where('sys_key', 'ticket_refresh_time')->where('created_by', auth()->id())->first();

        $ticket_time = ($tkt_refresh_time == null ? 0 : $tkt_refresh_time->sys_value);

        return view('help_desk.ticket_manager.index-new', get_defined_vars());

    }

    public function ticket_management(Request $request){
        
        $departments = Departments::all();
        $statuses = TicketStatus::orderBy('seq_no')->get();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();
        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();

        $tickets_followups = TicketFollowUp::where('passed', 0)->where('is_deleted', 0)->get();

        // foreach ($tickets_followups as $key => $value) {
        //     if($value->is_recurring == 1) {
        //         $tickets_followups[$key]->date = $this->follow_up_calculation($value);
        //     }
        // }

        // $followUpsNew = [];

        // foreach ($tickets_followups as $key => $value) {
        //     if($value->passed == 0) {
        //         $followUpsNew[] = $value;
        //     }
        // }
        
        // $tickets_followups = $followUpsNew;

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


        // get ticket refresh time
        $tkt_refresh_time = SystemSetting::where('sys_key', 'ticket_refresh_time')->where('created_by', auth()->id())->first();
        $ticket_time = ($tkt_refresh_time == null ? 0 : $tkt_refresh_time->sys_value);

        return view('help_desk.ticket_manager.index-new', get_defined_vars());
    }

    public function addTicketPage($id =null) {

        $departments = Departments::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('status', 1)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();

        $responseTemplates = ResponseTemplate::all();

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
        $response_categories = RestemplateCat::where("is_deleted","=",0)->get();

        return view('help_desk.ticket_manager.add_ticket_new', get_defined_vars());
    }

    public function update_ticket(Request $request) {
        
        $data = $request->all();
        $response = array();
        // return dd($request->all());
        try {
            $ticket = Tickets::where('id',$data['id'])->first();

            $data['action_performed'] = ($request->has('action_performed')) ? $data['action_performed'] : "";
            
            if(!empty($ticket)) {
                if(array_key_exists('attachments', $data)) {
                    // target dir for ticket files against ticket id

                    
                    
                    // $target_dir = public_path().'/files/tickets/'.$data['id'];
                    // if (!File::isDirectory($target_dir)) {
                    //     mkdir($target_dir, 0777, true);
                    // }

                    $file_path = \Session::get('is_live') == 1 ? 'public/' : '';
                    $target_dir = 'storage/tickets/'.$data['id'];

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
                        
                        $target_src = $target_dir.'/'.$value[0];
                            
                        file_put_contents($target_src, $file);
                    }
                }
                unset($data['id']);
                unset($data['attachments']);

                if($request->has('dd_Arr')){
                    $dd_values = $request->dd_Arr;
                    for($dd = 0 ; $dd < sizeof($dd_values) ; $dd++){

                        if($dd_values[$dd]['id'] == 1){
                            $data['dept_id'] = $dd_values[$dd]['new_data'] ;
                            $data['action_performed'] = 'Department Updated';
                        }elseif($dd_values[$dd]['id'] == 2){
                            $data['assigned_to'] = $dd_values[$dd]['new_data'] ;
                            $data['action_performed'] = 'Tech Lead Updated';
                        }elseif($dd_values[$dd]['id'] == 3){
                            $data['type'] = $dd_values[$dd]['new_data'] ;
                            $data['action_performed'] = 'Type Updated';
                        }elseif($dd_values[$dd]['id'] == 4){

                            $data['status'] = $dd_values[$dd]['new_data'] ;
                            $data['action_performed'] = 'Status Updated';
                            $os = TicketStatus::where('id',$dd_values[$dd]['new_data'])->first();
                            if($os && $os->name == 'Closed'){
                                $data['reply_deadline'] = 'cleared';
                                $data['resolution_deadline'] = 'cleared';
                            }
                        }elseif($dd_values[$dd]['id'] == 5){
                            $data['priority'] = $dd_values[$dd]['new_data'] ;
                            $data['action_performed'] = 'Priority Updated';
                            
                        }

                        // save activity logs
                        $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
                        $action_perform = 'Ticket ID # <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> '.$data['action_performed'].' Updated By '. $name_link;

                        $log = new ActivitylogController();
                        $log->saveActivityLogs('Tickets' , 'tickets' , $request->id , auth()->id() , $action_perform);

                    }

                }

                $data['updated_at'] = Carbon::now();
                $data['updated_by'] = \Auth::user()->id;
                $ticket->update($data);

                if($request->has('dd_Arr')){

                //     $dd_values = $request->dd_Arr;
                //     for($dd = 0 ; $dd < sizeof($dd_values) ; $dd++){

                //         if($dd_values[$dd]['id'] == 1){
                //             $data['action_performed'] = 'Department Updated';
                //         }elseif($dd_values[$dd]['id'] == 2){
                //             $data['action_performed'] = 'Tech Lead Updated';
                //         }elseif($dd_values[$dd]['id'] == 3){
                //             $data['action_performed'] = 'Type Updated';
                //         }elseif($dd_values[$dd]['id'] == 4){
                //             $data['action_performed'] = 'Status Updated';
                //         }elseif($dd_values[$dd]['id'] == 5){
                //             $data['action_performed'] = 'Priority Updated';
                //         }

                //         // // save activity logs
                //         // $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
                //         // $action_perform = 'Ticket ID # <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> '.$data['action_performed'].' Updated By '. $name_link;

                //         // $log = new ActivitylogController();
                //         // $log->saveActivityLogs('Tickets' , 'tickets' , $request->id , auth()->id() , $action_perform);

                //     }

                }else{
                    // save activity logs
                    $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
                    $action_perform = 'Ticket ID # <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> '.$data['action_performed'].' Updated By '. $name_link;

                    $log = new ActivitylogController();
                    $log->saveActivityLogs('Tickets' , 'tickets' , $request->id , auth()->id() , $action_perform);
                }
                

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


    // get flagged tickets
    public function get_flag_tickets() {

        $closed_status = TicketStatus::where('name','Closed')->first();

        $tickets = Tickets::where([['is_flagged',1], ['is_pending',0], ['trashed',0], ['status','!=', $closed_status->id]])->with('ticket_customer')->get();

        return response()->json([
            "status_code" => 200 ,
            "success" => true ,
            "tickets" => $tickets,
        ]);
        
    }


    // updated selected ticket
    public function update_selected_ticket(Request $request) {

        // return dd($request->all());
        $tkt_id = explode(',', $request->tkt_id);
        for($i = 0; $i < count($tkt_id); $i++) {

            $tk = Tickets::where('id' , $tkt_id[$i])->first();
            $tk->assigned_to = $request->assigned_to != null ? $request->assigned_to : $tk->assigned_to;
            $tk->type = $request->type != null ? $request->type : $tk->type;
            $tk->status = $request->status != null ? $request->status : $tk->status;
            $tk->priority = $request->priority != null ? $request->priority : $tk->priority;
            $tk->dept_id = $request->dept_id != null ? $request->dept_id : $tk->dept_id;
            $tk->updated_at = Carbon::now();
            $tk->save();
            
            // save activity logs
            $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
            $action_perform = 'Ticket ID # <a href="'.url('ticket-details').'/' .$tk->coustom_id.'">'.$tk->coustom_id.'</a>  Updated By '. $name_link;

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'tickets' , $tkt_id[$i] , auth()->id() , $action_perform);

        }

        return response()->json([
            "status_code" => 200 ,
            "success" => true ,
            "message" => "Updated Successfully!",
        ]);
    }

    public function save_tickets(Request $request){ 
        $current_date = Carbon::now();

        $data = $request->all();
        $response = array();
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

            $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Created By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'tickets' , $ticket->id , auth()->id() , $action_perform);    
            
            
            // saving response template
            if($request->res == 1) {
                $resTemp = new SettingsController();
                $resTemp->addResponseTemplate($request);
            }     

            // return false;
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
            $deadlines = [];
            $logs = Activitylog::where('ref_id', $ticketID)->where('module', 'Tickets')->where('table_ref', 'sla_rep_deadline_from')->orderBy('id', 'desc')->first();
            $deadlines[0] = empty($logs) ? $ticket->created_at : $logs->created_at;

            $logs = Activitylog::where('ref_id', $ticketID)->where('module', 'Tickets')->where('table_ref', 'sla_res_deadline_from')->orderBy('id', 'desc')->first();
            $deadlines[1] = empty($logs) ? $ticket->created_at : $logs->created_at;
            
            return $deadlines;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    
    public function getFilteredTickets($dept = '' , $sts = ''){


        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();
        $closed_status_id = $closed_status->id;
        $cnd = '!=';
        $is_del = 0;

        if(\Auth::user()->user_type == 1) {

            if($sts == 'all') {
                $tickets = Tickets::select("*")
                ->when($dept != '', function($q) use($dept) {
                    return $q->where('tickets.dept_id', $dept);
                })
                ->where([['tickets.is_deleted', 0], ['tickets.trashed', 0], ['tickets.is_pending',0], ['tickets.status','!=', $closed_status_id] ])->orderBy('tickets.updated_at', 'desc')->get();
            }else{
                $tickets = Tickets::select("*")
                ->when($sts != '', function($q) use($sts) {
                    return $q->where('tickets.status', $sts);
                })
                ->when($dept != '', function($q) use($dept) {
                    return $q->where('tickets.dept_id', $dept);
                })
                ->where([['tickets.is_deleted', 0], ['tickets.trashed', 0], ['tickets.is_pending',0] ])->orderBy('tickets.updated_at', 'desc')->get();
                // ->where('tickets.is_deleted', 0)->orderBy('tickets.updated_at', 'desc')->where('tickets.trashed', 0)->get();
            }        
        } else {
            $aid = \Auth::user()->id;
            $assigned_depts = DepartmentAssignments::where('user_id', $aid)->get()->pluck('dept_id')->toArray();

            if($sts == 'all') {
                $tickets = Tickets::select("*")
                ->when(\Auth::user()->user_type != 5, function($q) use ($assigned_depts, $aid) {
                    return $q->whereIn('tickets.dept_id', $assigned_depts)->orWhere('tickets.assigned_to', $aid)->orWhere('tickets.created_by', $aid);
                })
                ->when($dept != '', function($q) use($dept) {
                    return $q->where('tickets.dept_id', $dept);
                })
                ->where([['tickets.is_deleted', 0],['is_enabled', 'yes'],['tickets.trashed', 0], ['tickets.is_pending',0], ['tickets.status','!=', $closed_status_id] ])->orderBy('tickets.updated_at', 'desc')->get();
                // ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->where('tickets.trashed', 0)->orderBy('tickets.updated_at', 'desc')->get();                
            }else{

                $tickets = Tickets::select("*")
                ->when(\Auth::user()->user_type != 5, function($q) use ($assigned_depts, $aid) {
                    return $q->whereIn('tickets.dept_id', $assigned_depts)->orWhere('tickets.assigned_to', $aid)->orWhere('tickets.created_by', $aid);
                })
                ->when($sts != '', function($q) use($sts) {
                    return $q->where('tickets.status', $sts);
                })
                ->when($dept != '', function($q) use($dept) {
                    return $q->where('tickets.dept_id', $dept);
                })
                ->where([['tickets.is_deleted', 0],['is_enabled', 'yes'],['tickets.trashed', 0], ['tickets.is_pending', 0] ])->orderBy('tickets.updated_at', 'desc')->get();
                // ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->where('tickets.trashed', 0)->orderBy('tickets.updated_at', 'desc')->get();
            }
        }

        $total_tickets_count = Tickets::where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        // $total_tickets_count = Tickets::where('dept_id',$dept)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $my_tickets_count = Tickets::where('assigned_to',\Auth::user()->id)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        // $overdue_tickets_count = Tickets::where('is_overdue',1)->count();
        $unassigned_tickets_count = Tickets::whereNull('assigned_to')->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $late_tickets_count = Tickets::where('is_overdue',1)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $closed_tickets_count = Tickets::where('status', $closed_status->id)->where('is_deleted', 0)->count();
        $trashed_tickets_count = Tickets::where('trashed', 1)->where('is_deleted', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        $flagged_tickets_count = Tickets::where('is_flagged', 1)->where('is_deleted', 0)->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->count();
        
        foreach($tickets as $value) {

            $value->sla_plan = $this->getTicketSlaPlan($value->id);
            
            // if($value->is_overdue == 0){
            
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
                    if($value->resolution_deadline != 'cleared') {
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
                }
                
                $value->is_overdue = 0;
                if($lcnt) {
                    // $late_tickets_count++;
                    $value->is_overdue = 1;
                    $tkt = Tickets::where('id',$value->id)->first();
                    $tkt->is_overdue = 1;
                    $tkt->save();
                }
            // }
        }
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['tickets']= $tickets;
        $response['total_tickets_count']= $total_tickets_count;
        $response['my_tickets_count']= $my_tickets_count;
        $response['flagged_tickets_count']= $flagged_tickets_count;
        $response['unassigned_tickets_count']= $unassigned_tickets_count;
        $response['late_tickets_count']= $late_tickets_count;
        $response['closed_tickets_count']= $closed_tickets_count;
        $response['trashed_tickets_count']= $trashed_tickets_count;
        $response['date_format'] = Session('system_date');
        $response['ticket_view'] = TicketView::where('user_id' , auth()->id() )->first();
        
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

            $tickets = Tickets::select("*")
            ->when($statusOrUser == 'self', function($q) use($closed_status_id) {
                return $q->where('tickets.assigned_to', \Auth::user()->id)->where('tickets.status','!=',$closed_status_id)->where('tickets.trashed', 0);
            })
            ->when($statusOrUser == 'customer', function($q) use ($cid) {
                return $q->where('tickets.customer_id',$cid)->where('tickets.trashed', 0);
                // get ticket according to customers
            })
            ->when($statusOrUser == 'staff', function($q) use ($sid) {
                return $q->where('tickets.assigned_to',$sid)->where('tickets.trashed', 0);
                // get ticket according to customers
            })
            ->when($statusOrUser == 'unassigned', function($q) use($closed_status_id) {
                return $q->whereNull('tickets.assigned_to')->where('tickets.status','!=',$closed_status_id)->where('tickets.trashed', 0);
            })
            ->when($statusOrUser == 'overdue', function($q) use($closed_status_id) {
                return $q->where('tickets.is_overdue', 1)->where('tickets.status','!=',$closed_status_id)->where('tickets.trashed', 0);
            })
            ->when($statusOrUser == 'flagged', function($q) use($closed_status_id) {
                return $q->where('tickets.is_flagged',1)->where('tickets.status','!=',$closed_status_id)->where('tickets.trashed', 0);
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
            ->when($statusOrUser == 'total', function($q) use($closed_status_id) {
                
                return $q->where('tickets.trashed', 0)->where('tickets.status', '!=', $closed_status_id)->where('tickets.trashed', 0);
            })
            ->where([['tickets.is_deleted', 0], ['is_pending' ,0] ])->orderBy('tickets.updated_at', 'desc')
            ->get();
        } else {
            $aid = \Auth::user()->id;
            $assigned_depts = DepartmentAssignments::where('user_id', $aid)->get()->pluck('dept_id')->toArray();

            $tickets = Tickets::select("*")
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
            ->where([ ['tickets.is_deleted', 0], ['is_pending' ,0] ])->orderBy('tickets.updated_at', 'desc')
            ->get();
        }

        $total_tickets_count = Tickets::
        when($statusOrUser == 'customer', function($q) use ($cid) {
            return $q->where('tickets.customer_id', $cid);
        })
        ->when($statusOrUser == 'staff', function($q) use ($sid) {
            return $q->where('tickets.assigned_to', $sid);
        })
        ->where([ ['is_deleted', 0], ['is_pending' ,0] ,['tickets.trashed', 0] ,['status', '!=', $closed_status_id] ])->count();
        

        $my_tickets_count = Tickets::where('assigned_to',\Auth::user()->id)
        ->where( [ ['is_deleted', 0] , ['tickets.trashed', 0] , ['is_pending' ,0] , ['tickets.status', '!=', $closed_status_id] ] )->count();
        
        $unassigned_tickets_count = Tickets::whereNull('assigned_to')
        ->where([ ['is_deleted', 0] , ['tickets.trashed', 0] , ['is_pending' ,0] , ['tickets.status', '!=', $closed_status_id] ])->count();
        
        
        $late_tickets_count = Tickets::where([ ['is_overdue',1], ['is_deleted', 0] , ['tickets.trashed', 0] , ['is_pending' ,0] , ['tickets.status', '!=', $closed_status_id] ])->count();
        
        $closed_tickets_count = Tickets::
        when($statusOrUser == 'customer', function($q) use ($cid) {
            return $q->where('tickets.customer_id', $cid);
        })
        ->when($statusOrUser == 'staff', function($q) use ($sid) {
            return $q->where('tickets.assigned_to', $sid);
        })
        ->where([ ['status', $closed_status->id] , ['is_pending' ,0] , ['is_deleted' , 0]])->count();
        
        $trashed_tickets_count = Tickets::where([ ['trashed', 1] , ['is_deleted', 0] , ['is_pending' ,0] ])->count();
        
        $flagged_tickets_count = Tickets::where([ ['is_flagged', 1] , ['is_deleted', 0] ,['tickets.trashed', 0] , ['is_pending' ,0] ,['tickets.status', '!=', $closed_status_id] ])->count();

        $open_ticket_count = Tickets::
        when($statusOrUser == 'customer', function($q) use ($cid , $open_status) {
            return $q->where('tickets.customer_id', $cid)->where('status' , $open_status->id);;
        })
        ->when($statusOrUser == 'staff', function($q) use ($sid) {
            return $q->where('tickets.assigned_to', $sid);
        })
        ->where([['status','!=', $closed_status->id] , ['is_deleted',0], ['trashed',0] , ['is_pending' ,0] ])->where('is_deleted', 0)->where('trashed', 0)->count();


        $tm_name = timeZone();

        foreach($tickets as $value) {
            $value->last_reply = TicketReply::where('ticket_id', $value->id)->orderByDesc('id')->first();
            $value->tkt_notes = TicketNote::where('ticket_id' , $value->id)->count();
            $value->tkt_follow_up = TicketFollowUp::where('ticket_id' , $value->id)->where('passed',0)->count();
            // $value->tech_name = 'Unassigned';
            // if(!empty($value->assigned_to)) {
            //     $u = User::where('id', $value->assigned_to)->first();
            //     if(!empty($u)) $value->tech_name = $u->name;
            // }
            // else $unassigned_tickets_count++;

            // $rep = TicketReply::where('ticket_id', $value->id)->orderBy('created_at', 'desc')->first();
            // $repCount = TicketReply::where('ticket_id', $value->id)->count();
            // $value->lastReplier = '';
            // $value->replies = '';
            // if(!empty($rep)) {
            //     if($rep['user_id']) {
            //         $user = User::where('id', $rep['user_id'])->first();
            //         if(!empty($user)) $value->lastReplier = $user->name;
            //     } else if($rep['customer_id']) {
            //         $user = Customer::where('id', $rep['customer_id'])->first();
            //         if(!empty($user)) $value->lastReplier = $user->first_name.' '.$user->last_name;
            //     }
            //     $value->replies = $repCount;
            // }

            // if($value->assigned_to == \Auth::user()->id) $my_tickets_count++;
            // if($value->status == $open_status->id) $open_tickets_count++;

            // $value->lastActivity = Activitylog::where('module', 'Tickets')->where('ref_id', $value->id)->orderBy('created_at', 'desc')->value('created_at');

            $value->sla_plan = $this->getTicketSlaPlan($value->id);
            // if($value->is_overdue == 0){
                $dd = $this->getSlaDeadlineFrom($value->id);
                $value->sla_rep_deadline_from = $dd[0];
                $value->sla_res_deadline_from = $dd[1];
    
                $lcnt = false;
    
                if($value->sla_plan['title'] != self::NOSLAPLAN) {
                    if($value->reply_deadline != 'cleared') {
                        
                        $date = new Carbon( Carbon::now() , $tm_name);
                        $nowDate = Carbon::parse($date->format('Y-m-d h:i A'));

                        if(!empty($value->reply_deadline)) {
                            $timediff = $nowDate->diffInSeconds(Carbon::parse($value->reply_deadline), false);
                            if($timediff < 0) $lcnt = true;
                        } else {
    
                            $rep = Carbon::parse($value->sla_rep_deadline_from);
                            $dt = explode('.', $value->sla_plan['reply_deadline']);
                            $rep->addHours($dt[0]);
    
                            if(strtotime($rep) < strtotime($nowDate)) {
                                $lcnt = true;
                            }
    
                            
                        }
                    }
        
                    if(!$lcnt) {
                        if($value->resolution_deadline != 'cleared') {
                            $date = new Carbon( Carbon::now() , $tm_name);
                            $nowDate = Carbon::parse($date->format('Y-m-d h:i A'));

                            if(!empty($value->resolution_deadline)) {
                                $timediff = $nowDate->diffInSeconds(Carbon::parse($value->resolution_deadline), false);
                                if($timediff < 0) $lcnt = true;
                            } else {
                                $res = Carbon::parse($value->sla_res_deadline_from);
                                $dt = explode('.', $value->sla_plan['due_deadline']);
                                $res->addHours($dt[0]);
                                if(strtotime($res) < strtotime($nowDate)) {
                                    $lcnt = true;
                                }
                            }
                        }
                    }
                }
                
                // $value->is_overdue = 0;
                if($lcnt) {
                    // $late_tickets_count++;
                    $value->is_overdue = 1;
                    $tkt = Tickets::where('id',$value->id)->first();
                    $tkt->is_overdue = 1;
                    $tkt->save();
                }
            // }
            
        }
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['tickets']= $tickets;
        $response['total_tickets_count']= $total_tickets_count;
        $response['my_tickets_count']= $my_tickets_count;
        $response['flagged_tickets_count']= $flagged_tickets_count;
        $response['open_ticket_count']= $open_ticket_count;
        $response['unassigned_tickets_count']= $unassigned_tickets_count;
        $response['late_tickets_count']= $late_tickets_count;
        $response['closed_tickets_count']= $closed_tickets_count;
        $response['trashed_tickets_count']= $trashed_tickets_count;
        $response['date_format'] = Session('system_date');
        $response['ticket_view'] = TicketView::where('user_id' , auth()->id() )->first();
        
        return response()->json($response);
    }

    public function get_ticket_log(Request $request) {
        try {
            // $logs =  DB::table('activity_log')->select('activity_log.*')->join('tickets', 'tickets.id', '=', 'activity_log.ref_id')->where('activity_log.module', 'Tickets')->where('tickets.is_deleted', 0)->orderBy('created_at', 'desc')->get();
            if($request->has('id')) {
                $logs =  Activitylog::where('ref_id', $request->id)->orderByDesc('id')->get();
            } else {
                $logs =  Activitylog::where('module', 'Tickets')->orderByDesc('id')->limit(150)->get();
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
        
        // try {
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
                    $ticket->updated_at = Carbon::now();
                    $ticket->save();

                    // save activity logs
                    $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                    $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Recycled By by '. $name_link;

                    $log = new ActivitylogController();
                    $log->saveActivityLogs('Tickets' , 'tickets' , $ticket->id , auth()->id() , $action_perform);

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
                // $target_dir = public_path().'/files/replies/'.$data['ticket_id'];
                // if (!File::isDirectory($target_dir)) {
                //     mkdir($target_dir, 0777, true);
                // }

                $target_dir = 'storage/tickets-replies/'.$data['ticket_id'];
                    
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
                    
                    $target_src = $target_dir.'/'.$value[0];
                        
                    file_put_contents($target_src, $file);
                }
            }

            $data['is_published'] = 0;
            if($request->has('id')) {
                $save_reply = TicketReply::findOrFail($data['id']);
            }
            $type = $data['type'];
            if($type == 'publish'){
                $data['is_published'] = 1;
            }
            
            unset($data['type']);

            //converting html to secure bbcode
            $mail_reply = $data['reply'];
            // $bbcode = new BBCode();
            $data['reply'] = $data['reply'];
            // $data['reply'] = $bbcode->convertFromHtml($data['reply']);

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

            if($request->has('dd_Arr')){
                $dd_values = $request->dd_Arr;
                for($dd = 0 ; $dd < sizeof($dd_values) ; $dd++){

                    if($dd_values[$dd]['id'] == 1){
                        $ticket->dept_id = $dd_values[$dd]['new_data'] ;
                        $data['action_performed'] = 'Department Updated';
                    }elseif($dd_values[$dd]['id'] == 2){
                        $ticket->assigned_to = $dd_values[$dd]['new_data'] ;
                        $data['action_performed'] = 'Tech Lead Updated';
                    }elseif($dd_values[$dd]['id'] == 3){
                        $ticket->type = $dd_values[$dd]['new_data'] ;
                        $data['action_performed'] = 'Type Updated';
                    }elseif($dd_values[$dd]['id'] == 4){
                        $ticket->status = $dd_values[$dd]['new_data'] ;
                        $data['action_performed'] = 'Status Updated';
                        $os = TicketStatus::where('id',$dd_values[$dd]['new_data'])->first();
                        if($os && $os->name == 'Closed'){
                            $data['reply_deadline'] = 'cleared';
                            $data['resolution_deadline'] = 'cleared';

                            $ticket->reply_deadline = 'cleared';
                            $ticket->resolution_deadline = 'cleared';
                        }
                    }elseif($dd_values[$dd]['id'] == 5){
                        $ticket->priority = $dd_values[$dd]['new_data'] ;
                        $data['action_performed'] = 'Priority Updated';
                        
                    }

                    // save activity logs
                    $name_link = '<a href="'.url('profile').'/' . auth()->user()->id .'">'.auth()->user()->name.'</a>';
                    $action_perform = 'Ticket ID # <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a> '.$data['action_performed'].' Updated By '. $name_link;

                    $log = new ActivitylogController();
                    $log->saveActivityLogs('Tickets' , 'tickets' , $request->id , auth()->id() , $action_perform);

                }

            }

            $ticket->updated_at = Carbon::now();
            $ticket->assigned_to = \Auth::user()->id;
            $ticket->save();

            $sla_updated = false;

            if($data['is_published'] == 1) {

                // save activity logs
                $log = new ActivitylogController();
                // $log->saveActivityLogs('Tickets' , 'ticket_replies' , $ticket->id , auth()->id() , $action_perf);

                $settings = $this->getTicketSettings(['reply_due_deadline']);
                if(isset($settings['reply_due_deadline'])) {
                    if($settings['reply_due_deadline'] == 1) {
                        if(\Auth::user()->user_type == 5) $ticket->reply_deadline = null;
                        else $ticket->reply_deadline = 'cleared';
                        $ticket->save();

                        $sla_updated = $ticket->reply_deadline;
                        
                        $log = new ActivitylogController();
                        $log->saveActivityLogs('Tickets' , 'sla_rep_deadline_from' , $ticket->id , auth()->id() , $action_perf);
                    }
                }
            }


            if($type == 'publish') {
            
                
                $ticket = Tickets::where('id', $data['ticket_id'])->where('trashed', 0)->where('is_deleted', 0)->first();

                $action = 'ticket_reply';
                if($request->has('dd_Arr') && sizeof($request->dd_Arr) > 0){
                    $action = 'ticket_reply_update';
                    $content = $mail_reply;
                    $this->sendNotificationMail($ticket->toArray(), 'ticket_update', $content, $data['cc'], $action, $request->data_id,'',$request->dd_Arr);
                }else{
                    $content = $mail_reply;
                    $this->sendNotificationMail($ticket->toArray(), 'ticket_reply', $content, $data['cc'], $action, $data['attachments']);
                }
            }


            // saving response template
            if($request->res == 1) {
                $resTemp = new SettingsController();
                $resTemp->addResponseTemplate($request);
            }   

            $up_tkt = Tickets::where('id' , $request->ticket_id)->first();
            $save_reply->name = \Auth::user()->name;
            $save_reply['reply_user'] = User::where('id' , auth()->id())->first();
            $response['message'] = ($request->has('id')) ? 'Reply Added Successfully! '.$data['attachments'] : 'Reply Updated Successfully! '.$data['attachments'];
            $response['sla_updated'] = $sla_updated;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['data'] = $save_reply;
            $response['tkt_updated_at'] =  $up_tkt->updated_at;
            return response()->json($response);
    
        // }catch(Exception $e) {
        //     $response['message'] = $e->getMessage();
        //     $response['status_code'] = 500;
        //     $response['success'] = false;
        //     return response()->json($response);
        // }
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
        
        $shared_emails = TicketSharedEmails::where('ticket_id',$details->id)->get()->toArray();

        $current_status = TicketStatus::where('id' , $details->status)->first();
        $current_priority= TicketPriority::where('id' , $details->priority)->first();

        $details['ticketReplies'] = TicketReply::where('ticket_id', $details->id)->with(['replyUser','customerReplies'])->orderBy('created_at', 'DESC')->get();

        $departments = Departments::all();
        // $ticket = Tickets::all();
        if($details->is_staff_tkt == 1){
            $ticket_customer = User::firstWhere('id',$details->customer_id);
        }else{
            $ticket_customer = Customer::firstWhere('id',$details->customer_id);
        }
        $all_customers = Customer::with('company')->get();
        $all_companies = Company::all();
        $responseTemplates = ResponseTemplate::get();
        $vendors = Vendors::all();
        $types = TicketType::all();
        $statuses = TicketStatus::whereRaw("find_in_set( $details->dept_id ,department_id)")->orderBy('seq_no', 'asc')->get();

        $priorities = TicketPriority::all();

        $assigned_users = DepartmentAssignments::where('dept_id', $ticket->dept_id)->get()->pluck('user_id')->toArray();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff', 0)->whereIn('id', $assigned_users)->get();
        // $customers = Customer::where('is_deleted', 0)->get();
        $active_user = \Auth::user();
        $projects = Project::all();
        $companies = Company::all();

        $open_status = TicketStatus::where('name','Open')->first();
        $closed_status = TicketStatus::where('name','Closed')->first();


        $total_tickets_count = 0;
        $open_tickets_count = 0;
        $closed_tickets_count = 0;

        if($ticket->customer_id != null){
            $total_tickets_count = Tickets::where([ ['customer_id',$ticket->customer_id],['trashed',0], ['is_deleted',0] , ['is_pending',0] ])->count();
            $open_tickets_count = Tickets::where([ ['customer_id',$ticket->customer_id],['status','!=',$closed_status->id], ['status',$open_status->id], ['trashed',0], ['is_deleted',0] , ['is_pending',0] ])->count();
            $closed_tickets_count = Tickets::where([ ['customer_id',$ticket->customer_id],  ['status',$open_status->id], ['trashed',0], ['is_deleted',0] , ['is_pending',0] ])->count();
        }
        
        $bbcode = new BBCode();

        if(!empty($details->ticket_detail))
            $details->ticket_detail = str_replace('/\r\n/','<br>', $bbcode->convertToHtml($details->ticket_detail));
        
        foreach ($details->ticketReplies as $key => $rep) {
            if($rep !=null){

            
            $rep['reply'] = str_replace('/\r\n/','<br>', $bbcode->convertToHtml($rep['reply']));

            if( empty($rep['user_id']) ){
                $user = Customer::where('id', $rep['customer_id'])->first();
                $rep['name'] = $user['first_name'] . ' ' . $user['last_name'];
                $rep['user_type'] = 5;
            }else{
                $user = User::where('id', $rep['user_id'])->first();
                $rep['name'] = $user['name'];
                $rep['user_type'] = $user['user_type'];
            }
        }
        }

        $sla_plans = SlaPlan::where('sla_status', 1)->where('is_deleted',0)->get();

        $ticket_slaPlan = (Object) $this->getTicketSlaPlan($id);
        
        $dd = $this->getSlaDeadlineFrom($id);
        
        $details->sla_rep_deadline_from = $dd[0];
        $details->sla_res_deadline_from = $dd[1];
        // dd($details->toArray());

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

        $response_categories = RestemplateCat::where("is_deleted","=",0)->get();

        if(Auth::user()->user_type == 5) {
            return view('help_desk.ticket_manager.cust_ticket_details', get_defined_vars());
        }else{
            return view('help_desk.ticket_manager.ticket_details_new',get_defined_vars());
        }
    }


    public function delete_ticket_reply(Request $request) {

        $reply = TicketReply::find($request->id);
        $reply->delete();

        return response()->json([
            "message" => "Ticket Reply Deleted Successfully!",
            "status_code" => 200 , 
            "success" => true,
        ]);
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
                $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$del_tkt->coustom_id.'">'.$del_tkt->coustom_id.'</a>) Permanently Deleted By '. $name_link;
                
                $log = new ActivitylogController();
                $log->saveActivityLogs('Tickets' , 'tickets' , $del_tkt->id , auth()->id() , $action_perform);
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
                $current_date = Carbon::now();

                $del_tkt->trashed = 1;
                $del_tkt->updated_at = $current_date;

                if($request->tkt_del) {
                    $del_tkt->is_deleted = 1;
                }


                $del_tkt->save();

                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$del_tkt->coustom_id.'">'.$del_tkt->coustom_id.'</a>) Moved to trash By '. $name_link;

                $log = new ActivitylogController();
                $log->saveActivityLogs('Tickets' , 'tickets' , $del_tkt->id , auth()->id() , $action_perform);
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
            for($i=0; $i< sizeof($data);$i++){
                $del_tkt = Tickets::where('id',$data[$i])->first();
                
                $del_tkt->trashed = 0;
                $del_tkt->updated_at = Carbon::now();
                $del_tkt->updated_by = \Auth::user()->id;
                $del_tkt->save();
                
                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$del_tkt->coustom_id.'">'.$del_tkt->coustom_id.'</a>) Recycled By '. $name_link;

                $log = new ActivitylogController();
                $log->saveActivityLogs('Tickets' , 'tickets' , $del_tkt->id , auth()->id() , $action_perform);
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
            

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$flag_tkt->coustom_id.'">'.$flag_tkt->coustom_id.'</a>) '.$msg.' '. $name_link;

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'tickets' , $flag_tkt->id , auth()->id() , $action_perform);

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
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up added by '. $name_link;

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'ticket_follow_up' , $ticket->id, auth()->id() , $action_perform);

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

    public function getTicketReplies($id) {
        $response = array();
        try {
            
            $replies = TicketReply::where('ticket_id', $id)->with(['replyUser','customerReplies'])->orderBy('created_at', 'DESC')->get();

            $response['replies'] = $replies;
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

    public function calculateFutureDate($followupDate , $schedule_time , $schedule_type) {

        $minutes = 0;
        if($schedule_type == 'minutes') {
            // total seconds
            $minutes = $schedule_time;
        }

        if($schedule_type == 'hours') {
            $minutes = ($schedule_time * 60);
        }

        if($schedule_type == 'days') {
            $minutes = ($schedule_time * 1440);
        }

        if($schedule_type == 'weeks') {
            $minutes = ($schedule_time * 10080);
        }

        if($schedule_type == 'months') {
            $minutes = ($schedule_time * 43800);
        }

        if($schedule_type == 'years') {
            $minutes = ($schedule_time * 525600);
        }

        $futureDate = Carbon::parse( $followupDate->format('Y-m-d H:i:s') );
        $futureDate->addMinutes($minutes);

        return $futureDate;
    }

    public function updateFollowupCron(){

        $response = array();
        $flwups = TicketFollowUp::where('passed',0)->get();

        try {
            if($flwups) {

                $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
                $tm_name = '';
                if($timezone) {
                    $tm_name = $timezone->sys_value != null ? $timezone->sys_value : 'America/New_York';
                }else{
                    $tm_name = 'America/New_York';
                }
                
                $add_value = 0;
                $add_type = '';

                foreach($flwups as $flwup) {
                    
                    $ticket = Tickets::findOrFail($flwup->ticket_id);

                    if( $flwup->is_recurring == 1 ) {
                        
                        $checkFollowUpLogs = TicketFollowupLogs::where('follow_up_id' , $flwup->id)->orderByDesc('id')->first();
                        if($checkFollowUpLogs) {

                            $currentDate = date('Y-m-d H:i:s');
                            $fLogs_created_at = $checkFollowUpLogs->created_at;

                            if($checkFollowUpLogs->is_cron == 1 || $checkFollowUpLogs->is_front_end == 1) {

                                if(strtotime($currentDate) > strtotime($fLogs_created_at) ) {

                                    $startDate = new Carbon( $flwup->recurrence_start , $tm_name);
                            
                                    if($flwup->recurrence_pattern && $flwup->recurrence_time) {
        
                                        if($flwup->date) $followUpDate = new Carbon($flwup->date, $tm_name);
                                        else $followUpDate = $startDate;
        
                                        $rec_time = explode(':', $flwup->recurrence_time);
        
                                        // set some timezone for proper hour and mins setting
                                        $followUpDate->timezone(Session::get('timezone'));
                        
                                        $followUpDate->hour = $rec_time[0];
                                        $followUpDate->minute = $rec_time[1];
                                        
                                        // convert back to utc for further calculations
                                        $followUpDate->utcOffset(0);
                                        
                                        $pattern = explode('|', $flwup->recurrence_pattern);
                                        $pattern_type = $pattern[0];
                                        // daily|2
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
                                    
        
                                        // $currentDateTime = new \DateTime();
                                        // $currentDateTime->setTimezone(new \DateTimeZone($tm_name));
                                        // $nowDate = Carbon::parse( $currentDateTime->format('Y-m-d') );
                                        $nowDate = new Carbon( Carbon::now() , $tm_name);
                                        
                                        // $newfollowUpDate = new \DateTime($followUpDate);
                                        // $newfollowUpDate->setTimezone(new \DateTimeZone($tm_name));
                                        // $followUpDate = Carbon::parse( $newfollowUpDate->format('Y-m-d') );
                                        $followUpDate = new Carbon( $followUpDate , $tm_name);
                                        
                                        
                                        $timediff = $nowDate->diffInSeconds($followUpDate, false);
                                        if($timediff < 0) {
                                    
                                            $idata = array();
                                            // follow up time to update ticket
                                            $idata['ticket_update'] = true;
                                
                                            // update the recurrence time for next all ocurrences
                                            if($flwup->recurrence_time2) $idata['recurrence_time'] = $flwup->recurrence_time2;
                                            
                                            if($flwup->recurrence_end_type == 'count') {
                                                if((int) $flwup->recurrence_end_val > 0) $idata['recurrence_end_val'] = (int) $flwup->recurrence_end_val-1;
                                                else $idata['passed'] = 1;
                                            }
                                        
                                            // if(!array_key_exists('passed', $idata)) {
                                                if($add_type == 'minutes') $followUpDate->addMinutes($add_value);
                                                else if($add_type == 'hours') $followUpDate->addHours($add_value);
                                                else if($add_type == 'days') $followUpDate->addDays($add_value);
                                                else if($add_type == 'weeks') $followUpDate->addWeeks($add_value);
                                                else if($add_type == 'months') $followUpDate->addMonths($add_value);
                                                else if($add_type == 'years') $followUpDate->addYears($add_value);
                                
                                
                                
                                                if($flwup->recurrence_end_type == 'date') {
                                                    // $endDate = new Carbon($flwup->recurrence_end_val);
                                                    $endDate = new \DateTime($flwup->recurrence_end_val);
                                                    $endDate->setTimezone(new \DateTimeZone($tm_name));
                                                    $endDate = Carbon::parse( $endDate->format('Y-m-d') );
                                                    if( strtotime($followUpDate) >=  strtotime($endDate) ) {
                                                        $idata['passed'] = 1;
                                                    }
                                                    
                                                    // if($followUpDate->isAfter($endDate)) $idata['passed'] = 1;
                                                }
                                                // if(!array_key_exists('passed', $idata)) 
                                                $idata['date'] = new Carbon($followUpDate);
                                            // }
        
                                
                                            if(array_key_exists('passed', $idata)) $flwup->passed = $idata['passed'];
                                            if(array_key_exists('date', $idata)) $flwup->date = $idata['date'];
                                            if(array_key_exists('recurrence_time', $idata)) $flwup->recurrence_time = $idata['recurrence_time'];
                                            if(array_key_exists('recurrence_end_val', $idata)) $flwup->recurrence_end_val = $idata['recurrence_end_val'];
                                
                                            $flwup->save();
                                
                                            // if(!array_key_exists('passed', $idata)) $this->follow_up_calculation($followUp);
                                            if($idata['passed'] == 1){
                                                $this->createFollowUpLogs($ticket , $flwup , $value = null,$idata['passed']);
                                            }else{
                                                $this->createFollowUpLogs($ticket , $flwup , $value = null);
                                            }
                                            
                                        }
                                    }

                                }elseif(strtotime($currentDate) == strtotime($fLogs_created_at) ){
                                    $response['status_code'] = 200;
                                    $response['success'] = true;
                                    return response()->json($response);
                                }
                            }
                            
                        }else{
                            $this->createFollowUpLogs($ticket , $flwup , $value = null);
                        }

                    }else{

                        $checkFollowUpLogs = TicketFollowupLogs::where('follow_up_id' , $flwup->id)->orderByDesc('id')->first();
                        if($checkFollowUpLogs) {

                            $currentDate = date('Y-m-d H:i:s');
                            $fLogs_created_at = $checkFollowUpLogs->created_at;

                            if($checkFollowUpLogs->is_cron == 1 || $checkFollowUpLogs->is_front_end == 1) {

                                if(strtotime($currentDate) > strtotime($fLogs_created_at) ) {

                                    if($flwup->schedule_time != null) {

                                        // convert utc time into user timezone
                                        $date = new \DateTime($flwup->created_at);
                                        $date->setTimezone(new \DateTimeZone($tm_name));                            
                                        $convertedDate = Carbon::parse( $date->format('Y-m-d H:i:s') );
        
                                        $schedule_type = $flwup->schedule_type;
                                        $schedule_time = $flwup->schedule_time;
        
                                        // pass converted_date , 
                                        $futureDate = $this->calculateFutureDate($convertedDate , $schedule_time , $schedule_type);
        
                                        // getting region current date and time
                                        $currentDateTime = new \DateTime();
                                        $currentDateTime->setTimezone(new \DateTimeZone($tm_name));                            
                                        $currentDate = Carbon::parse( $currentDateTime->format('Y-m-d H:i:s') );
        
                                        if( strtotime($currentDate) >=  strtotime($futureDate) ) {
        
                                            // $this->triggerFollowUp($ticket , $flwup);
                                            $this->createFollowUpLogs($ticket , $flwup , $value = null );

                                            $flwup->passed = 1;
                                            $flwup->save();
                                        }
        
                                    }

                                    $this->createFollowUpLogs($ticket , $flwup , $value = null);

                                }elseif(strtotime($currentDate) == strtotime($fLogs_created_at) ){
                                    $response['status_code'] = 200;
                                    $response['success'] = true;
                                    return response()->json($response);
                                }
                            }

                            
                        } else{
                            $this->createFollowUpLogs($ticket , $flwup , $value = null);
                        }  
                    }
                }
            }
            
            // $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            // $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up "'.$logData.'" by ' . $name_link;

            // $log = new ActivitylogController();
            // $log->saveActivityLogs('Tickets' , 'ticket_follow_up' , $ticket->id, auth()->id() , $action_perform);

            $response['status_code'] = 200;
            $response['success'] = true;
            // $response['ticket'] = $ticket;
            return response()->json($response);

        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

    }

    public function followUpLogs(Request $request) {
        try {
            if($request->has('data')) {
                $ticket = Tickets::findOrFail($request->ticket_id);
                $data = json_decode($request->data, true);
                $logData = '';
                
                if(is_array($data)) {
                    foreach ($data as $value) {
                        $flwup = TicketFollowUp::findOrFail($value['id']);

                        $checkFollowUpLogs = TicketFollowupLogs::where('follow_up_id' , $flwup->id)->orderByDesc('id')->first();
                        if($checkFollowUpLogs) {
                          
                            if($checkFollowUpLogs->is_recurring == 1){
    
                                $currentDate = date('Y-m-d H:i:s');
                                $fLogs_created_at = $checkFollowUpLogs->created_at;

                                if($checkFollowUpLogs->is_cron == 1 || $checkFollowUpLogs->is_front_end == 1) {
    
                                    if(strtotime($currentDate) > strtotime($fLogs_created_at) ) {

                                        $this->createFollowUpLogs($ticket , $flwup , $value );

                                    }elseif(strtotime($currentDate) == strtotime($fLogs_created_at) ){

                                        $response['status_code'] = 200;
                                        $response['success'] = true;
                                        $response['ticket'] = $ticket;
                                        return response()->json($response);

                                    }
                                }
    
                            }else{

                                if($checkFollowUpLogs->is_cron == 1 ||  $checkFollowUpLogs->is_front_end == 1) {
                                    $response['status_code'] = 200;
                                    $response['success'] = true;
                                    $response['ticket'] = $ticket;
                                    return response()->json($response);
                                }
                            }
                        }else{
                            $this->createFollowUpLogs($ticket , $flwup , $value);
                        }

                        // $ticket = Tickets::findOrFail($flwup->ticket_id);
                    }
                }
            }
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

    public function createFollowUpLogs($ticket , $flwup , $value , $passed = '') {
        
        $fLogsData = array(
            'ticket_id' => $ticket->id,
            'follow_up_id' => $flwup->id,
            'is_cron' => 0,
            'is_frontend' => 1,
            'schedule_type' =>  $flwup->schedule_type,
            'custom_date' => $flwup->custom_date,
            'schedule_time' =>  $flwup->schedule_time,
            'old_dept_id' => $ticket->dept_id,
            'old_priority'  => $ticket->priority,
            'old_assigned_to' => $ticket->assigned_to,
            'old_status' => $ticket->status,
            'old_type' => $ticket->type,
            'new_dept_id' => $flwup->follow_up_dept_id,
            'new_priority' => $flwup->follow_up_priority, 
            'new_assigned_to' => $flwup->follow_up_assigned_to,
            'new_status'=> $flwup->follow_up_status,
            'new_type' => $flwup->follow_up_type,
            'follow_up_project' => $flwup->follow_up_project,
            'follow_up_notes' => $flwup->follow_up_notes,
            'follow_up_notes_color' => $flwup->follow_up_notes_color,
            'follow_up_notes_type' => $flwup->follow_up_notes_type,
            'follow_up_reply' => $flwup->follow_up_reply,
            'is_recurring' => $flwup->is_recurring,
            'recurrence_time' => $flwup->recurrence_time,
            'recurrence_time2' => $flwup->recurrence_time2,
            'recurrence_pattern' => $flwup->recurrence_pattern,
            'recurrence_start' => $flwup->recurrence_start,
            'recurrence_end_type' => $flwup->recurrence_end_type,
            'recurrence_end_val' => $flwup->recurrence_end_val,
            'date' => $flwup->date,
            'created_by' => $flwup->created_by, 
        );

        if(array_key_exists('is_recurring' , $value)) {
            if($value['is_recurring'] == 0) {
                $fLogsData['passed'] = array_key_exists('passed' , $value) ?? $value['passed'];
            }
        }

        TicketFollowupLogs::create($fLogsData);
        
        if($value != null) {

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
            
            $this->triggerFollowUp($ticket , $flwup , $passed);

        }else{
            $this->triggerFollowUp($ticket , $flwup , $passed);
        }

        // $ticket->save();

        // $flwup->updated_at = Carbon::now();
        // $flwup->save();
        return ;
    }

    public function triggerFollowUp($ticket, $flwup , $passed = '') {

        $flwup_note = '';
        $flwup_reply = '';

        $updates_Arr = [];

        if($ticket->dept_id != $flwup->follow_up_dept_id){
            $dept_name = DB::table("departments")->where('id', $flwup->follow_up_dept_id )->first();
            if($dept_name) {

                $obj = array(
                    "id" => 1 ,
                    "data" => $ticket->department_name ,
                    "new_data" => $flwup->follow_up_dept_id ,
                    "new_text" => $dept_name->name , 
                );

                array_push($updates_Arr, $obj);
            }
        }

        if($ticket->assigned_to != $flwup->follow_up_assigned_to){
            $user = User::where('id', $flwup->follow_up_assigned_to)->first();
            if($user) {

                $obj = array(
                    "id" => 2 ,
                    "data" => $ticket->assignee_name ,
                    "new_data" => $flwup->follow_up_assigned_to ,
                    "new_text" => $user->name , 
                );

                array_push($updates_Arr, $obj);
            }
        }

        if($ticket->type != $flwup->follow_up_type){
            $tkt_type = TicketType::where('id', $flwup->follow_up_type )->first();
            if($tkt_type) {

                $obj = array(
                    "id" => 3 ,
                    "data" => $ticket->type_name ,
                    "new_data" => $flwup->follow_up_type ,
                    "new_text" =>$tkt_type->name, 
                );
                
                array_push($updates_Arr, $obj);
            }
        }

        if($ticket->status != $flwup->follow_up_status){
            $tkt_status = TicketStatus::where('id', $flwup->follow_up_status )->first();
            if($tkt_status) {

                $obj = array(
                    "id" => 4 ,
                    "data" => $ticket->status_name ,
                    "new_data" => $flwup->follow_up_status ,
                    "new_text" => $tkt_status->name, 
                );
                
                array_push($updates_Arr, $obj);
            }
        }

        if($ticket->priority != $flwup->follow_up_priority){
            $tkt_priority = TicketPriority::where('id' ,$flwup->follow_up_priority )->first();
            if($tkt_priority) {

                $obj = array(
                    "id" => 5 ,
                    "data" => $ticket->priority_name ,
                    "new_data" => $flwup->follow_up_priority ,
                    "new_text" => $tkt_priority->name, 
                );
                
                array_push($updates_Arr, $obj);
            }
        }

        $logData = '';
        
        $ticket->dept_id = $flwup->follow_up_dept_id;
        $ticket->priority = $flwup->follow_up_priority;
        $ticket->assigned_to = $flwup->follow_up_assigned_to;
        $ticket->status = $flwup->follow_up_status;
        $ticket->type = $flwup->follow_up_type;
        $ticket->updated_at = Carbon::now();
        
        $logData = 'ticket updated';

        if(!empty($flwup['follow_up_reply'])) {
            
            $bbcode = new BBCode();

            TicketReply::create([
                "ticket_id" => $flwup->ticket_id,
                "user_id" => $flwup->created_by, 
                "msgno" => null , 
                "reply" => $flwup->follow_up_reply , 
                // "reply" => $bbcode->convertFromHtml( $flwup->follow_up_reply ) , 
                "cc" => null , 
                "date" => date('Y-m-d H:i:s'), 
                "is_published" => 1 ,
                "attachments" => null ,
            ]);
            if($flwup->follow_up_reply != ''){
                $flwup_reply = $flwup->follow_up_reply ;
            }

            $ticket->reply_deadline = 'cleared';
        
        }
        $ticket->save();

        if(!empty($flwup['follow_up_notes'])) {
            TicketNote::create([
                'ticket_id' => $flwup->ticket_id,
                'followup_id' => $flwup->id,
                'color' => $flwup->follow_up_notes_color == null ? 'rgb(255, 230, 177)' : $flwup->follow_up_notes_color,
                'type' => $flwup->follow_up_notes_type,
                'note' => $flwup->follow_up_notes,
                'visibility' => 'Everyone',
                'created_by' => $flwup->created_by
            ]);
            $flwup_note = $flwup->follow_up_notes;
            $logData .= (empty($logData)) ? 'added a note' : ', added a note';
        }
        $flwup->updated_at = Carbon::now();
    
        $created_by = User::where('id',$flwup->created_by)->first();
        $flwup_updated = $created_by->name;
        if($passed == 1){
            $flwup->passed = 1;
        }
        // $flwup->passed = 1;
        $flwup->save();
        $ticket = Tickets::findOrFail($flwup->ticket_id);
        $this->sendNotificationMail($ticket->toArray(), 'ticket_note_create', $flwup_reply, '', 'Ticket Followup', '' , '' , $updates_Arr,'','',$flwup_note,$flwup_updated);
        $this->sendNotificationMail($ticket->toArray(), 'ticket_reply', $flwup_reply, '', 'Ticket Followup', '' , '' , $updates_Arr,'','',$flwup_note,$flwup_updated);
        return; 
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
                                'color' => $flwup->follow_up_notes_color == null ? 'rgb(255, 230, 177)' : $flwup->follow_up_notes_color,
                                'type' => $flwup->follow_up_notes_type,
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
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up "'.$logData.'" by ' . $name_link;

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'ticket_follow_up' , $ticket->id, auth()->id() , $action_perform);

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
        $followUps = TicketFollowUp::where('ticket_id',$tkt_id)->where('is_deleted', 0)->where('passed', 0)->with('followUpLogs')->get();
        // TicketFollowupLogs

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
                $followUpDate->timezone(Session::get('timezone'));

                $followUpDate->hour = $rec_time[0];
                $followUpDate->minute = $rec_time[1];

                // convert back to utc for further calculations
                $followUpDate->utcOffset(0);
                
                $pattern = explode('|', $followUp->recurrence_pattern);
                $pattern_type = $pattern[0];
                // daily|2
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

            $followUp->delete();

            $ticket->updated_at = Carbon::now();
            $ticket->updated_by = \Auth::user()->id;
            $ticket->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Follow-up deleted by '. $name_link;

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'ticket_follow_up' , $ticket->id , auth()->id() , $action_perform);

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
            
            if( $request->id != null ){

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
                    $ticket->updated_at = Carbon::now();
                    $ticket->save();

                    $sla_updated = 'cleared';

                    $log = new ActivitylogController();
                    $log->saveActivityLogs('Tickets' , 'sla_rep_deadline_from' , $ticket->id , auth()->id() , $action_performed);
                }
            }

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'ticket_notes' , $ticket->id , auth()->id() , $action_performed);

            if($request->tag_emails != null && $request->tag_emails != '') {

                $emails = explode(',',$request->tag_emails);
        
                for( $i = 0; $i < sizeof($emails); $i++ ) {
                    
                    $user = User::where('is_deleted',0)->where('email',$emails[$i])->first();
                    if($user) {
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
            
                        $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                    }
                }
        
            }
            $check =  Tickets::where('id' , $request->ticket_id)->first();
            // dd($check); exit();

            $response['message'] = 'Ticket Note Saved Successfully!';
            $response['sla_updated'] = $sla_updated;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['tkt_update_at'] = $check->updated_at;
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
                // 'phone' => 'required'
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
                // "phone" => $data['phone']
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

            if(isset($data['new_customer'])) {
                $data['customer_id'] = $this->addTicketCustomer($request);
                $customer = Customer::find($data["customer_id"]);

                if($data['new_company'] != 0) {
                    Company::create([ 
                        "name" => $data['company_name'] != null ? $data['company_name']  : '',
                        "poc_first_name" => $data['cmp_first_name'] != null ? $data['cmp_first_name']  : '',
                        "poc_last_name" => $data['cmp_last_name'] != null ? $data['cmp_last_name']  : '',
                        "phone" => $data['cmp_phone'] != null ? $data['cmp_phone']  : '',
                    ]);
                }

            } else {
                $customer = Customer::find($data["customer_id"]);

                $tkt_share = array();

                if($data['tkt_cc'] != null && $data['tkt_cc'] != "") {
                    $tkt_share['email'] = $data['tkt_cc'];
                    $tkt_share['mail_type'] = 1;
                    $tkt_share['ticket_id'] = $data['ticket_id'];

                    $shared_emails = TicketSharedEmails::where('ticket_id',$data['ticket_id'])->where('mail_type' , 1)->first();

                    if($shared_emails) {
                        $shared_emails->email = $data['tkt_cc'];
                        $shared_emails->save();
                    }else{
                        TicketSharedEmails::create($tkt_share);
                    }
                }

                if($data['tkt_bcc'] != null && $data['tkt_bcc'] != "") {
                    $tkt_share['email'] = $data['tkt_bcc'];
                    $tkt_share['mail_type'] = 2;
                    $tkt_share['ticket_id'] = $data['ticket_id'];

                    $shared_emails = TicketSharedEmails::where('ticket_id',$data['ticket_id'])->where('mail_type' , 2)->first();
                    if($shared_emails) {
                        $shared_emails->email = $data['tkt_bcc'];
                        $shared_emails->save();
                    }else{
                        TicketSharedEmails::create($tkt_share);
                    }

                }
            }

            $ticket = Tickets::find($data["ticket_id"]);
            
            $ticket->customer_id = $customer->id;
            $ticket->is_staff_tkt = 0;

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

            $ticket = Tickets::select('customer_id')->where('id', $id)->first();
            $customer = Customer::where('id' , $ticket->customer_id)->first();
            $company_id = $customer->company_id;
            $customer_id = $customer->id;
            if(!is_array($customer)) $customer_id = [$customer_id];
            if(!is_array($customer)) $company_id = [$company_id];


            $notes_arr = DB::table('users')
            ->join('ticket_notes', 'users.id', '=', 'ticket_notes.created_by')
            ->select('ticket_notes.*', 'users.name', 'users.profile_pic')->whereIn('ticket_notes.ticket_id', $id)
            ->orWhere('ticket_notes.customer_id' , $customer_id)
            // ->orWhere('ticket_notes.company_id' , $company_id)
            ->where(function($q) {
                return $q->where('ticket_notes.visibility', 'like', '%'.\Auth::user()->id.'%')->orWhere('ticket_notes.created_by', \Auth::user()->id);
            })
            ->when($request->has('type'), function($q) use($type) {
                return $q->where('ticket_notes.type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->get()->toArray();
            
            $notes = array_filter($notes_arr, function ($notes_arr) {
                if( $notes_arr->is_deleted == 0 ) {
                    return $notes_arr;    
                }
            }); 
                 
            $response['message'] = 'Success';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['notes']= json_decode( json_encode($notes) , true);
            $response['notes_count']= count($notes);
            
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

                $note->is_deleted = 1;
                $note->deleted_by = \Auth::user()->id;
                $note->deleted_at =  Carbon::now();
                $note->save();
                
                $ticket = Tickets::where('id' , $note->ticket_id)->first();

                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

                if(!empty($ticket)) {

                    $ticket->updated_at =  Carbon::now();
                    $ticket->updated_by = \Auth::user()->id;
                    $ticket->save();

                    $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Note deleted by '. $name_link;
        
                }else{
                    $ticket = Tickets::where("coustom_id" , $request->ticket_id)->first();
                    $ticket->updated_at =  Carbon::now();
                    $ticket->updated_by = \Auth::user()->id;
                    $ticket->save();

                    $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$request->ticket_id.'">'.$request->ticket_id.'</a>) Note deleted by '. $name_link;
                }

                $log = new ActivitylogController();
                $log->saveActivityLogs('Tickets' , 'ticket_notes' , $ticket->id, auth()->id() , $action_perform);
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
                    $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/'.$value->coustom_id.'">'.$value->coustom_id.'</a>) merged into (ID <a href="'.url('ticket-details').'/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) By '. $name_link;
        
                    $log = new ActivitylogController();
                    $log->saveActivityLogs('Tickets' , 'tickets' , $ticket->id, auth()->id() , $action_perform);

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

            $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
            $tm_name = '';
            if($timezone) {
                $tm_name = $timezone->sys_value != null ? $timezone->sys_value : 'America/New_York';
            }else{
                $tm_name = 'America/New_York';
            }

            $rep_date = new Carbon( now() , $tm_name);
            $res_date = new Carbon( now() , $tm_name);


            if($sla_plan->reply_deadline == null && $sla_plan->due_deadline == null) {

                $rep = TicketSettings::where('tkt_key','default_reply_time_deadline')->first();
                $res = TicketSettings::where('tkt_key','default_resolution_deadline')->first();

                $ticket->reply_deadline = $rep_date->addHours($rep->tkt_value)->addSeconds(-20)->format('Y-m-d g:i A');
                $ticket->resolution_deadline = $res_date->addHours($res->tkt_value)->addSeconds(-20)->format('Y-m-d g:i A');

            }else{
                $ticket->reply_deadline = $rep_date->addHours($sla_plan->reply_deadline)->addSeconds(-20)->format('Y-m-d g:i A');
                $ticket->resolution_deadline = $res_date->addHours($sla_plan->due_deadline)->addSeconds(-20)->format('Y-m-d g:i A');

            }

            $ticket->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Sla Plan Association Updated By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'sla_rep_deadline_from' , $request->ticket_id , auth()->id() , $action_perform);
            $log->saveActivityLogs('Tickets' , 'sla_res_deadline_from' , $request->ticket_id , auth()->id() , $action_perform);

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
            $current_date = Carbon::now();

            $ticket = Tickets::findOrFail($request->ticket_id);

            $ticket->reply_deadline = $request->rep_deadline;
            $ticket->resolution_deadline = $request->res_deadline;
            $ticket->updated_at = $current_date;
            $ticket->is_overdue = $request->overdue;
            $ticket->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) reply & resolution deadlines Updated By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'tickets' , $request->ticket_id , auth()->id() , $action_perform);

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
            // $target_dir = public_path().'/files'.'/'.$request->module.'/'.$request->ticket_id;

            // if (!File::isDirectory($target_dir)) {
            //     mkdir($target_dir, 0777, true);
            // }
            if($request->module == 'tickets'){
                $target_dir = 'storage/tickets/'.$request->ticket_id;
                    
                if (!File::isDirectory($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
            }else if($request->module == 'replies'){
                $target_dir = 'storage/tickets-replies/'.$request->ticket_id;
                    
                if (!File::isDirectory($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
            }
            

            $file = $request->file('attachment');

            //Move Uploaded File
            if($file->move($target_dir, $request->fileName.'.'.$file->getClientOriginalExtension())) {
                
                if($request->module == 'tickets') {
                    if(!empty($ticket->attachments)) $ticket->attachments .= ','.$request->fileName.'.'.$file->getClientOriginalExtension();
                    else $ticket->attachments = $request->fileName.'.'.$file->getClientOriginalExtension();
    
                    $response['tkt_updated_at'] = $ticket->attachments;
                    $response['attachments'] = $ticket->attachments;

                    $ticket->updated_at =Carbon::now();
                    $ticket->save();
                } else {
                    $response['attachments'] = $request->fileName.'.'.$file->getClientOriginalExtension();
                }
            } else {
                $response['message'] = 'Failed to move file';
                $response['status_code'] = 500;
                $response['success'] = false;
                $response['tkt_updated_at'] = '123';
                return response()->json($response);
            }

            $response['status_code'] = 200;
            $response['success'] = true;
            $response['tkt_updated_at'] = '12';
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
                    $oldval = '';
                    if($request->has('data_id')) $data_id = $request->data_id;
                    if($request->has('oldval')) $oldval = $request->oldval;
                    $email = \Auth::user()->email;

                    if($request->template == 'ticket_create') {
                        $this->sendNotificationMail($ticket->toArray(), $request->template, '', '', $request->action, $data_id,$email,$oldval , $request->auto_responder , $request->send_details );
                    }else{
                        $this->sendNotificationMail($ticket->toArray(), $request->template, '', '', $request->action, $data_id,$email,$oldval);
                    }

                    
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


    // create ticket
    public function createTicket($id) {

        $ticket = Tickets::where('coustom_id' , $id)->where('is_pending',1)->first();

        if(!empty($ticket)) {

            if($ticket->cust_email != null) {

                $customer = Customer::where('email', $ticket->cust_email)->where('is_deleted',0)->first();

                if(!empty($customer)) {
                    
                    $ticket->customer_id = $customer->id;
                    $ticket->is_pending = 0;
                    $ticket->created_at = Carbon::now();
                    $ticket->save();
                    
                    $ticket = Tickets::where('coustom_id' , $id)->where('is_pending',0)->first();
                    $this->sendNotificationMail($ticket->toArray(), 'ticket_create', '', '', 'Ticket Create' , '', $ticket->cust_email , '' , 1 , 0);
                    
                }else{
                    
                    $f_name = $ticket->cust_name;
                    
                    $data = [
                        "username" => $ticket->cust_email , 
                        "first_name" => $f_name ,
                        "email" => $ticket->cust_email , 
                    ];

                    $customer = Customer::create($data);

                    $random_no = Str::random(15);

                    User::create([
                        "name" => $f_name,
                        "email" => $ticket->cust_email ,
                        "password" => Hash::make($random_no),
                        "alt_pwd" => Crypt::encryptString($random_no),
                        "user_type" => 5,
                        "status" => 1
                    ]);

                    $mailer = new MailController();
                    $mailer->UserRegisteration( $ticket->cust_email  ,true,'customer');

                    $ticket->customer_id = $customer->id;
                    $ticket->is_pending = 0;
                    $ticket->save();
                    
                    $ticket = Tickets::where('coustom_id' , $id)->where('is_pending',0)->first();
                    $this->sendNotificationMail($ticket->toArray(), 'ticket_create', '', '', 'Ticket Create' , '', $ticket->cust_email , '' , 1 , 0);
                }
            }
        }


        return redirect()->route('ticket_management.index');
    }

    // Send Ticket mails to users.
    // $data_id is current note saved id
    // tempalte code is when save record it says tempalte_create_note & on update tmeplate_update_note;

    // is_closed for when ticket is closed and customer reply from third party.... then its store today datetime to reply_due & resolution_due
    // reset_tkt for when reply_due & resolution_due is cleared and customer reply from third party... then its store today datetime to reply_due & resolution_due
    public function sendNotificationMail($ticket, $template_code, $reply_content='', $cc='', $action_name='', $data_id=null, $mail_frm_param='',$old_params = '' , $auto_res = '' , $send_detail = '',$flwup_note = '',$flwup_updated = '' , $is_closed = '' , $reset_tkt = '') {

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
            $is_cron = false;
            if($action_name != 'cron' && $action_name != 'cust_cron' && $action_name != 'Ticket Followup'){
                $user = DB::table('users')->where('id', \Auth::user()->id)->first(); 
            }
            

            if($action_name == 'cron') {
                $is_cron = true;
                if($template_code == 'ticket_create') {
                    $notification_message = 'Ticket Generated by System';
                    $notification_title = 'New Ticket Generated';
                } else if($template_code == 'ticket_reply') {
                    $action_name = 'ticket_reply';
                }
            }elseif($action_name == 'cust_cron'){
                $action_name = 'ticket_cus_reply';
                
            }elseif($action_name == 'Customer Ticket Create'){
                
                $user = DB::table('users')->where('id', \Auth::user()->id)->first();
                $notification_message = 'New Ticket (ID <a href="'.url('ticket-details').'/'. $ticket['coustom_id'] .'">'. $ticket['coustom_id'] .'</a>) Created By '.  $user->name;

                $notification_title = 'New Ticket Created';
                

            } else if($action_name == 'Ticket Create') {
                $user = DB::table('users')->where('id', \Auth::user()->id)->first();
                $notification_message = 'New Ticket (ID <a href="'.url('ticket-details').'/'. $ticket['coustom_id'] .'">'. $ticket['coustom_id'] .'</a>) Created By '.  $user->name;
                $notification_title = 'New Ticket Created';
            }
            
            // dd($template_code);exit;

            if($template_code == 'ticket_create') {

                $customer_send = true;
                $cust_template_code = 'auto_res_ticket_create';

                $attachs = $ticket['attachments'];
                $pathTo = 'storage/tickets/'.$ticket['id'];

            } else if($action_name == 'Subject updated') {
                $attachs = $ticket['attachments'];
                $pathTo = 'storage/tickets/'.$ticket['id'];
            } else if($action_name == "ticket_reply") {
                $customer_send = true;
                $cust_template_code = 'auto_res_ticket_reply';

                // if(!empty($user)) $mail_from = $user->email;
                $attachs = $data_id;
                $pathTo = 'storage/tickets-replies/'.$ticket['id'];
                if($is_cron){
                    $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' }  Reply Added by System';
                    $notification_title = 'Reply Added';
                }else{
                    $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' }  Reply Added by '. $user->name;
                    $notification_title = 'Reply Added';
                }
                
            }else if($action_name == "Ticket Followup"){
                
                if(!empty($reply_content)){
                    $customer_send = true;
                    $cust_template_code = 'auto_res_ticket_reply';
    
                    // if(!empty($user)) $mail_from = $user->email;
                    $attachs = $data_id;
                    $pathTo = 'storage/tickets-replies/'.$ticket['id'];
                }
                
            }else if($action_name == 'ticket_reply_update'){

                $customer_send = true;
                $cust_template_code = 'auto_res_ticket_reply';

                // if(!empty($user)) $mail_from = $user->email;
                $attachs = $data_id;
                $pathTo = 'storage/tickets-replies/'.$ticket['id'];

                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' } Updated by '. $user->name;
                $notification_title = 'Ticket # { ' . $ticket['coustom_id']. ' } Updated';

            }else if($action_name == 'ticket_cus_reply'){
                $attachs = $data_id;
                $pathTo = 'storage/tickets-replies/'.$ticket['id'];

                $notification_message = 'Ticket # { ' . $ticket['coustom_id']. ' }  Reply Added by System';
                $notification_title = 'Reply Added';
            }
            else if($action_name == "Type updated") {
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
                // $user_type = 'customer';
            }

            if(!empty($ticket)) {

                if($ticket['is_staff_tkt'] == 1) {

                    $customer = User::where('id' , $ticket['customer_id'])->first();

                    $customer_send = false;

                }else{

                    $customer = Customer::where('id', $ticket['customer_id'])->first();

                    $customer_send = true;
                }


            }
            
            // if customer if null or empty then find user 
            // if(empty($customer)) {
            //     $customer = User::where('id' , $ticket['customer_id'])->first();
            //     $user_type = 'staff';
            // }
            
            

            
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

            
            // return dd($is_closed);
            if($template_code == 'ticket_create' && ($auto_res == 0 || $auto_res == '')) {

                
                $cust_message = '';

            }else{
                
                $cust_message = $mailer->template_parser($template_input, $cust_message, $reply_content, $action_name,$cust_template_code,$ticket,$old_params, '','', $is_closed , $reset_tkt , $ticket['is_staff_tkt']);
                
            }

            $message = $mailer->template_parser($template_input, $message, $reply_content, $action_name,$template_code,$ticket,$old_params,$flwup_note,$flwup_updated , $is_closed , $reset_tkt , $ticket['is_staff_tkt']);
            
            // if(empty($mail_from)) $mail_from = $mail_frm_param;

            if(!empty($cust_message)) {
                if($customer_send){
                    $subject = $mailer->parseSubject($ticket['coustom_id'].' '.$ticket['subject'], $ticket, $cust_template, $sendingMailServer->mail_queue_address);
    
                    if(!empty($reply_content)) {
                        // this is a reply
                        // $subject = 'Re: '.$subject;
                    }
                    
                    if($sendingMailServer->outbound == 'yes' && trim($sendingMailServer->autosend) == 'yes') {
                        if(!empty($customer)) $mailer->sendMail($subject, $cust_message, $mail_from, $customer->email, $customer->first_name.' '.$customer->last_name, $action_name, $attachs, $pathTo , $mail_frm_param);
                    }
                }
            }
            if($send_detail == 1){
                if($customer_send){
                    $cust_template = DB::table('templates')->where('code', 'auto_res_ticket_reply')->first();
                    $reply_content= $ticket['ticket_detail'];
                    $cust_message = empty($cust_template) ? '' : $cust_template->template_html;
                    $cust_message = $mailer->template_parser($template_input, $cust_message, $reply_content, $action_name,$template_code,$ticket,$old_params , '','', '','' , $ticket['is_staff_tkt']);
    
                    if(!empty($cust_message)) {
                    
                        $subject = $mailer->parseSubject($ticket['coustom_id'].' '.$ticket['subject'], $ticket, $cust_template, $sendingMailServer->mail_queue_address);
        
                        if(!empty($reply_content)) {
                            // this is a reply
                            // $subject = 'Re: '.$subject;
                        }
        
                        if($sendingMailServer->outbound == 'yes' && trim($sendingMailServer->autosend) == 'yes') {
                            if(!empty($customer)) $mailer->sendMail($subject, $cust_message, $mail_from, $customer->email, $customer->first_name.' '.$customer->last_name, $action_name, $attachs, $pathTo , $mail_frm_param);
                        }
                    }
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
                        // $subject = 'Re: '.$subject;
                    }
                }
                // echo "in hd";
                // dd($mail_frm_param);exit;
                if($mail_frm_param != null || $mail_frm_param != ''){

                    // $users_list = User::whereIn('id', $assigned_users)->where('email','!=',$mail_frm_param)->get()->toArray();
                    $users_list = User::whereIn('id', $assigned_users)->get()->toArray();
                    //  echo "in hd";
                    // dd($users_list);exit;
                }else{
                //     echo "in hd else";
                // dd($mail_frm_param);exit;
                    $users_list = User::whereIn('id', $assigned_users)->get()->toArray();
                }
                
                
                if($sendingMailServer->outbound == 'yes' || $action_name == "ticket_reply") {
                    // if(!empty($tech)) $users_list[] = $tech->attributesToArray();
                    // echyo "dfs";
                     
                    if(sizeof($users_list) > 0) $mailer->sendMail($subject, $message, $mail_from, $users_list, '', '', $attachs, $pathTo , $mail_frm_param);
                }
                $allwd_users = [];

                try {
                    $notify = new NotifyController();
                    foreach ($users_list as $key => $value) {
                        $allwd_users[] = [$value['email'], $value['name']];
                        $sender_id = auth()->id();
                        $receiver_id = $value['id'];
                        $slug = url('ticket-details/'.$ticket['coustom_id']);
                        $type = 'Tickets';
                        $data = 'data';
                        $title = $notification_title;
                        $icon = 'calendar';
                        $class = 'btn-success';
                        $desc = $notification_message;
                        
                        $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
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
        $status = TicketStatus::whereRaw("find_in_set($request->id,department_id)")->orderBy('seq_no', 'Asc')->get();
        $dept_assigns = DepartmentAssignments::where('dept_id', $request->id)->get()->pluck('user_id')->toArray();
        $users = User::whereIn('id', $dept_assigns)->where('is_deleted',0)->where('status',1)->get();

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
                
                $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
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

    // save ticket general info
    public function saveTicketGeneralInfo(Request $request) {

        $data = array(
            "per_page" => $request->per_page , 
            "user_id" => $request->user_id , 
            "created_by" => auth()->id(),
        );

        $tkt = TicketView::where('user_id' , $request->user_id)->first();

        if($tkt) {
            $data['updated_by'] = auth()->id();
            TicketView::where('user_id' , $request->user_id)->update($data);
        }else{
            TicketView::create($data);
        }

        return response()->json([
            'success' => true,
            'message' => 'Saved Successfully!',
            'status_code' => 200,
        ]);
    }

    // ticket refresh time 
    public function ticketRefreshTime() {

        try {

            $data = SystemSetting::where('sys_key','ticket_refresh_time')->first();

            if($data) {
                $data->sys_value = request()->tkt_refresh;
                $data->save();
                $message = 'Updated';
            }else{
                SystemSetting::create([
                    "sys_key" => 'ticket_refresh_time',
                    "sys_value" => request()->tkt_refresh,
                    "created_by" => auth()->id() ,
                ]);
                $message = 'Saved';
            }

            return response()->json([
                "message" => 'Setting '. $message .' Successfully', 
                "status_code" => 200 ,
                "success" => true ,
            ]);
            
        } catch(Exception $e) {
            return response()->json([
                "message" => $e->getMessage() , 
                "status_code" => 500 ,
                "success" => false ,
            ]);
        }

        
        
    }

}