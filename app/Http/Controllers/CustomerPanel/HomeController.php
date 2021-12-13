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
// use Srmklive\PayPal\Services\ExpressCheckout;
use PayPal;

class HomeController
{
    public function profile($name,$type = null) {

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
        return view('customer_manager.customer_lookup.custProfile',compact('google','nmi_integration','customer','company', 'countries','subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format','wp_value','google_key'));
    }

    public function addTicketPage(){

        $departments = Departments::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $customers = Customer::where('is_deleted', 0)->get();

        $responseTemplates = ResponseTemplate::get();

        $page_control = 'customer';

        return view('help_desk.ticket_manager.add_ticket',compact('departments','priorities','users','types','customers', 'responseTemplates', 'page_control'));
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
}
