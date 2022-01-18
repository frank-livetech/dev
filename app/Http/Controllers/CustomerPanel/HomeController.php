<?php

namespace App\Http\Controllers\CustomerPanel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\Departments;
use App\Models\TicketPriority;
use App\Models\TicketType;
use App\Models\CustomerType;
use App\Models\TicketStatus;
use App\Models\TicketSettings;
use App\Models\Customer;
use App\Models\Company;
use App\CompanyActivityLog;
use App\Http\Controllers\HelpdeskController;
use App\Models\CustomerCC;
use App\Models\Tickets;
use App\Models\Subscriptions;
use App\Models\BrandSettings;
use App\Models\LineItem;
use App\Models\Tax;
use App\Models\Billing;
use App\Models\Shipping;
use App\Models\Orders;
use App\Models\Integrations;
use App\Models\ResponseTemplate;
use Illuminate\Support\Facades\File;
use App\User;
use App\Models\TicketReply;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemManager\MailController;
use Throwable;
use Session;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Claims\Custom;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Illuminate\Support\Facades\URL;
use App\Models\Vendors;
use App\Models\DepartmentAssignments;
use App\Models\Project;
use Genert\BBCode\BBCode;
use App\Models\SlaPlan;
use App\Models\SlaPlanAssoc;
use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\GeneralController;
use App\Models\Activitylog;
// use Srmklive\PayPal\Services\ExpressCheckout;
use PayPal;

class HomeController
{

     // *************   PROPERTIES   ****************

     const DEFAULTSLA_TITLE = 'Default SLA';
     const NOSLAPLAN = 'No SLA Assigned';
     const CUSTOMID_FORMAT = 'XXX-999-9999';


    public function profile($name,$type = null) {
        $user = User::where('id', \Auth::user()->id)->first();
        $customer_id = Customer::where('email',$user->email)->first();
        $customer = Customer::with('company')->where('id',$customer_id->id)->first();
        if(!empty($customer)) {
            $credential = User::where('email', $customer->email)->first();
            if(!empty($credential->alt_pwd)) {
                $customer->password = Crypt::decryptString($credential->alt_pwd);
            }
        }
        
        $company = Company::get(['id','name']);
        if($type == 'json'){
            return response()->json(['customer' => $customer, 'company' => $company]);
        }
        
        $subscriptions = Subscriptions::where('customer_id', $customer_id->id)->get();

        foreach($subscriptions as $key=>$value){
            $line_items = LineItem::where('subscription_id', $value['id'])->get();
            if($line_items){
                foreach ($line_items as $j => $lt) {
                    $line_items[$j]['taxes'] = Tax::where('lineitem_id', $lt['id'])->get();
                }
            }
            $subscriptions[$key]['line_items'] = $line_items;
        }

        $orders = Orders::where('customer_id', $customer_id->id)->get();

        $departments = Departments::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $customer_types = CustomerType::all();
        $statuses = TicketStatus::all();
        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();
        $nmi_integration= DB::Table("integrations")->where("name","=","NMI Payment Gateway")->first() ;
        if(!empty($nmi_integration)) {
            $nmi_integration = json_decode($nmi_integration->details, true);
        }

        $wp_value = 0;
        $wp_integration = Integrations::where("slug", "wordpress")->where('status', 1)->first();
        if(!empty($wp_integration)) {
            $wp_value  = !empty($wp_integration->details) ?  1 :  0;
        }

        $google_key = 0;
        $google = DB::Table("integrations")->where("slug","=","google-api")->where('status', 1)->first();
        if(!empty($google)) {
            if($google->details != null & $google->details != '') {
                
                $detail_values = explode(",",$google->details);
                $api = substr($detail_values[1], 1, -1);
                $explode_key = explode(":",$api);
                $key = substr($explode_key[1], 1, -1);   

                if($key != null && $key != "" && $key != "null") $google_key = 1;
            }
        }

        $countries = [];
        if($google_key === 0) $countries = DB::Table('countries')->get();
        
        // return view('customer_manager.customer_lookup.customerprofile',compact('prof_state','customer','company', 'countries' , 'states' ,'subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format'));
        // return view('customer_manager.customer_lookup.custProfile',compact('prof_state','customer','company', 'countries' , 'states' ,'subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format'));
        // return view('customer_manager.customer_lookup.custProfile',compact('google','nmi_integration','customer','company', 'countries','subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format','wp_value','google_key'));
        return view('customer.customer_profile.customer_profile',compact('google','nmi_integration','customer','company', 'countries','subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format','wp_value','google_key'));
    }

    public function change_theme_mode(Request $request){
    
        $data = $request->all();
      
        try{
            $user = \Auth::user();
            if($data['theme'] == 'light'){
                
                $user->theme = 'light';
                $user->save();
                
            }else{
                
                $user->theme = 'dark';
                $user->save();
                
            }
            $response['message'] = 'Theme Changed Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
            
        }catch(Exception $e){
            
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
                
        }
    }

    public function viewTicketPage() {
        return view('customer.customer_tkt.cust_ticket_view');
    }

    // add ticket page
    public function addTicketPage(){

        $departments = Departments::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();

        $responseTemplates = ResponseTemplate::get();

        $page_control = 'customer';
        // return view('help_desk.ticket_manager.add_ticket',compact('departments','priorities','users','types','customers', 'responseTemplates', 'page_control'));
        return view('customer.customer_tkt.customer_tkt',compact('departments','priorities','users','types','customers', 'responseTemplates', 'page_control'));
    }

    // save ticket
    function saveTicket(Request $request) {

        $customer = Customer::where('email' , auth()->user()->email)->first();
        
        $data = array(
            "subject" => $request->subject , 
            "priority" => $request->priority , 
            "dept_id" => $request->dept_id,
            "ticket_detail" => $request->ticket_detail,
            "customer_id" => $customer->id,
        );

        $type = TicketType::where('name' ,'Issue')->first();

        if($type) {
            $data['type'] = $type->id;
        }else{
            $data['type'] = NULL;
        }

        $tkt_status= TicketStatus::where('slug','open')->first();

        if($tkt_status) {
            $data['status'] = $tkt_status->id;
        }else{
            $data['status'] = NULL;
        }

        

        $tkt = Tickets::create($data);
        
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
                    'ticket_id' => $tkt->id
                ]);
            }
        }

        $tkt->save();

        $helpDesk = new HelpdeskController();
        try {
            $helpDesk->sendNotificationMail($tkt->toArray(), 'ticket_create', '', '', 'Customer Ticket Create');
        } catch(Throwable $e) {
            echo $e->getMessage();
        }

        return response()->json([
            "status_code" => 200 , 
            "success" => true , 
            "id" =>  $tkt->id,
            "message" => "Ticket Created Successfully!",
        ]);

    }

    // save ticket attachments
    public function saveTicketAttachments (Request $request) {
        try {
            $ticket = Tickets::findOrFail($request->ticket_id);

            if($ticket) {

                $target_dir = public_path().'/storage'.'/'.$request->module.'/'.$request->ticket_id;

                if (!File::isDirectory($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file = $request->file('attachment');

                //Move Uploaded File
                if($file->move($target_dir, $request->fileName.'.'.$file->getClientOriginalExtension())) {
                    if($request->module == 'tickets') {
                        if(!empty($ticket->attachments)) $ticket->attachments .= ','.$request->fileName.'.'.$file->getClientOriginalExtension();
                        else $ticket->attachments = $request->fileName.'.'.$file->getClientOriginalExtension();
        
                        // $response['tkt_updated_at'] = $ticket->attachments;
                        // $response['attachments'] = $ticket->attachments;

                        $ticket->updated_at = Carbon::now();
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

            }

            $response['status_code'] = 200;
            $response['success'] = true;
            $response['message'] = 'Attachment Uploaded Successfully!';
            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    // save ticket replies
    public function saveTicketReply(Request $request) {
        // return dd($request->all());
        $customer = Customer::where('email' , auth()->user()->email)->first();
        $ticket = Tickets::where('id' , $request->ticket_id)->first();

        if($ticket) {

            if($ticket->trashed === 1) {
                return response()->json([
                    "message" => 'Please restore this ticket first!',
                    "status_code" => 500,
                    "success" => false,
                ]);
            }else{

                $data = array(
                    "ticket_id" => $request->ticket_id,
                    "customer_id" => $customer->id ,
                    "cc" => $request->cc,
                    "reply" => $request->reply,
                    "attachments" => $request->attachments,
                    "is_published" => 1,
                );

                TicketReply::create($data); 

                return response()->json([
                    "message" => 'Ticket Reply Added Successfully',
                    "status_code" => 200,
                    "success" => true,
                ]);
            }


        }else{
            return response()->json([
                "message" => 'Something went wrong!',
                "status_code" => 500,
                "success" => false,
            ]);
        }
    }   

    public function getCustomerTickets() {
        
        $customer =  Customer::where('email' , auth()->user()->email)->first();

        $open_status = TicketStatus::where('name','Open')->first();

        $open_tickets_count = 0;
        $late_tickets_count = 0;
        $my_tickets_count = 0;
        $unassigned_tickets_count = 0;
        $tickets = DB::Table('tickets')
        ->select('tickets.*','ticket_statuses.name as status_name','ticket_statuses.color as status_color','ticket_priorities.name as priority_name','ticket_priorities.priority_color as priority_color','ticket_types.name as type_name','departments.name as department_name',DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'))
        ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        ->join('ticket_types','ticket_types.id','=','tickets.type')
        ->join('departments','departments.id','=','tickets.dept_id')
        ->join('customers','customers.id','=','tickets.customer_id')
        ->where('tickets.customer_id', $customer->id)
        ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->get();


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

                        // if(array_key_exists(1, $dt)) {
                        //     $rep->addMinutes($dt[1]);
                        // }

                        if(strtotime($rep) < strtotime($nowDate)) {
                            $lcnt = true;
                        }

                        // $timediff = $nowDate->diffInSeconds($rep, false);
                        
                        // if($timediff < 0){
                        //     $lcnt = true;
                        // }
                        
                    }
                }
    
                if(!$lcnt) {
                    if($value->resolution_deadline != 'cleared') {
                        $nowDate = Carbon::now();
                        if(!empty($value->resolution_deadline)) {
                            $timediff = $nowDate->diffInSeconds(Carbon::parse($value->resolution_deadline), false);
                            if($timediff < 0) $lcnt = true;
                        } else {
                            $rep = Carbon::parse($value->sla_res_deadline_from);
                            $dt = explode('.', $value->sla_plan['due_deadline']);
                            $rep->addHours($dt[0]);
                            // if(array_key_exists(1, $dt)) {
                            //     $rep->addMinutes($dt[1]);
                            // }

                            if(strtotime($rep) < strtotime($nowDate)) {
                                $lcnt = true;
                            }

                            // $timediff = $nowDate->diffInSeconds($rep, false);
                            // if($timediff < 0) $lcnt = true;
                        }
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
        $response['total_tickets_count']= count($tickets);
        $response['open_tickets_count']= $open_tickets_count;
        $response['late_tickets_count']= $late_tickets_count;
        return response()->json($response);
    }

    public function get_tkt_details($id) {
        if(strpos($id, 'T-') === 0) {
            $ticket = Tickets::where('seq_custom_id', $id)->where('is_deleted', 0)->with(['ticketReplies','ticket_customer'])->withCount('ticketReplies')->first();
        } else {
            $ticket = Tickets::where('coustom_id', $id)->where('is_deleted', 0)->with(['ticketReplies','ticket_customer'])->withCount('ticketReplies')->first();
        }

        $department = Departments::where('id' , $ticket->dept_id)->first();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff', 0)->get();
        $type = TicketType::where('id' , $ticket->type)->first();
        $statuses = TicketStatus::orderBy('seq_no', 'desc')->get();
        $priorities = TicketPriority::all();

        $current_status = TicketStatus::where('id' , $ticket->status)->first();
        $current_priority= TicketPriority::where('id' , $ticket->priority)->first();
        $a = $ticket->toArray();

        return view('customer.customer_tkt.cust_tkt_details',get_defined_vars());
     
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


    // save company
    public function saveCompany (Request $request) {
        $check_company = Company::where('email',$request->email)->first();

        if($check_company) {

            return response()->json([
                "message" =>  'Email Already Taken try another one!',
                "status_code" => 500,
                "success" => false,
            ]);

        }else{

            $data = array(
                "poc_first_name" => $request->poc_first_name ,
                "poc_last_name" => $request->poc_last_name ,
                "name" => $request->name ,
                "email" => $request->email ,
                "phone" => $request->phone ,
            );

            $company = Company::create($data);

            $response['message'] = 'Company Added Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['result'] = $company->id;
            return response()->json($response);
        }
    }

    // update customer
    public function update_customer_profile(Request $request) {

        // return dd($request->all());

        $data = array(
            "email" => $request->email,
            "phone" => $request->phone,

            "address" => $request->address,
            "apt_address" => $request->apt_address,
            "company_id" => $request->company_id,
            "cust_type" => $request->cust_type,
            "country" => $request->country,

            "cust_state" => $request->state,
            "cust_city" => $request->city,
            "cust_zip" => $request->zip,
            "fb" => $request->fb,
            "twitter" => $request->twitter,

            "insta" => $request->insta,
            "pinterest" => $request->pinterest,
            "linkedin" => $request->linkedin,
            "bill_st_add" => $request->bill_st_add,
            "bill_apt_add" => $request->bill_apt_add,

            "bill_add_country" => $request->bill_add_country,
            "bill_add_state" => $request->bill_add_state,
            "bill_add_city" => $request->bill_add_city,
            "bill_add_zip" => $request->bill_add_zip,
            "is_bill_add" => $request->is_bill_add,

        );

        $customer = Customer::find($request->customer_id);
        $old_email = $customer->email;

        if($old_email != $request->email) {
            $request->validate([
                "email" => "required|email|unique:customers",
            ]);
        }

        if($request->has('first_name')) {
            if(empty(trim($customer->first_name))) {
                response()->json([
                    'message' => 'Please enter valid first name!',
                    'status_code' => 500,
                    'success' => false
                ]);
            }
            $data['first_name'] = $request->first_name;
        }
        if($request->has('last_name')) {
            if(empty(trim($customer->first_name))) {
                response()->json([
                    'message' => 'Please enter valid last name!',
                    'status_code' => 500,
                    'success' => false
                ]);
            }
            $data['last_name'] = $request->last_name;
        };

        if($request->customer_login) {
            $data['has_account'] = $request->customer_login;
        }
        $customer = Customer::where('id', $request->customer_id)->update($data);
        if($customer) {
            $is_user = User::where("email", $old_email)->first();

            $pwd = Str::random(15);
            if($request->has('password')) {
                if(!empty($request->password)) {
                    $pwd = $request->password;
                }
            }

            if($is_user) {
                $data = ["email" => $request->email];

                if($request->has('password')) {
                    if(!empty($request->password) && $request->password != Crypt::decryptString($is_user->alt_pwd)) {
                        $data["password"] = Hash::make($request->password);
                        $data["alt_pwd"] = Crypt::encryptString($request->password);
                    }
                }
                DB::table("users")->where("email", $old_email)->update($data);
            } else {
                if($request->has('customer_login')) {
                    if($request->customer_login == 1) {
                        DB::table("users")->insert([
                            "name" => $request->first_name . " " . $request->last_name,
                            "email" => $request->email,
                            "password" => Hash::make($pwd),
                            "alt_pwd" => Crypt::encryptString($pwd),
                            "user_type" => 5,
                            "status" => 1
                        ]);
                        
                    }
                }
            }
        }
            
        return response()->json([
            'status_code' => 200, 
            'success' => true, 
            'message' => 'Customer updated successfully!',
        ]);
    }

    // update customer profile pic
    public function saveProfileImage(Request $request) {
        if($request->profile_img != null) {
            if($request->hasFile('profile_img')){

                $customer = Customer::where('email', auth()->user()->email)->first();
                
                if($customer) {
                    $filename = time() . '.' . $request->profile_img->getClientOriginalExtension();                
                    $request->profile_img->storeAs('customer', $filename ,'public'); 
                                        
                    $customer->avatar_url = $filename;
                    $customer->save();

                    User::where('id' , auth()->id())->update([
                        "profile_pic" => $filename,
                    ]);

                    return response()->json([
                        "status" => 200 ,
                        "success" => true ,
                        "filename" => $filename ,
                        "message" => "Profile Image Successfully uploaded",
                    ]);

                }else{
                    return response()->json([
                        "status" => 500 ,
                        "success" => false ,
                        "Something went wrong"
                    ]);
                }
            }
        }
    }


}
