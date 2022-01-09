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


    public function viewTicketPage($name,$type = null) {

        $user = User::where('id', \Auth::user()->id)->first();
        $customer_id = Customer::where('email',$user->email)->first();
        // return $customer_id->id;
        
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
        return view('customer.customer_tkt.cust_ticket_view',compact('google','nmi_integration','customer','company', 'countries','subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format','wp_value','google_key'));
    }


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

    public function getCustomerTickets($customer_id) {
        $tickets = DB::Table('tickets')
        ->select('tickets.*','ticket_statuses.name as status_name','ticket_statuses.color as status_color','ticket_priorities.name as priority_name','ticket_priorities.priority_color as priority_color','ticket_types.name as type_name','departments.name as department_name',DB::raw('CONCAT(customers.first_name, " ", customers.last_name) AS customer_name'))
        ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        ->join('ticket_types','ticket_types.id','=','tickets.type')
        ->join('departments','departments.id','=','tickets.dept_id')
        ->join('customers','customers.id','=','tickets.customer_id')
        ->where('tickets.customer_id', $customer_id)
        ->where('tickets.is_deleted', 0)->where('is_enabled', 'yes')->get();
        

        return $tickets;
    }

    
    public function get_tkt_details($id) {
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
        
        $current_status = TicketStatus::where('id' , $details->status)->first();
        $current_priority= TicketPriority::where('id' , $details->priority)->first();

        $details['ticketReplies'] = TicketReply::where('ticket_id', $details->id)->orderBy('created_at', 'DESC')->get();
        $departments = Departments::all();
        // $ticket = Tickets::all();
        $ticket_customer = Customer::firstWhere('id',$details->customer_id);
        $vendors = Vendors::all();
        $types = TicketType::all();
        $statuses = TicketStatus::orderBy('seq_no', 'desc')->get();
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
                $rep['user_type'] = 5;
            }else{
                $user = User::where('id', $rep['user_id'])->first();
                $rep['name'] = $user['name'];
                $rep['user_type'] = $user['user_type'];
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

    
            return view('customer.customer_tkt.cust_tkt_details',get_defined_vars());
            // return view('help_desk.ticket_manager.ticket_details',compact('ticket_customer','ticket_overdue_bg_color','active_user','details','departments','vendors','types','statuses','priorities','users','projects','companies','total_tickets_count','open_tickets_count','closed_tickets_count','allusers', 'sla_plans', 'ticket_slaPlan','ticket_overdue_txt_color','date_format'));
     
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
}
