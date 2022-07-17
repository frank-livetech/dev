<?php

namespace App\Http\Controllers\CustomerManager;

use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Crypt,DB, Hash, Auth, Cookie, Date, URL};
use Illuminate\Contracts\Encryption\DecryptException;
use App\Models\{Departments, TicketPriority,TicketType,CustomerType,TicketStatus,TicketSettings,TicketNote,TicketView,Customer,Company,CustomerCC,Tickets,
    Subscriptions,BrandSettings,LineItem,Tax,Billing,Shipping,Orders,Integrations,CompanyActivityLog, DepartmentAssignments, TicketReply};
use Illuminate\Support\Facades\File;
use App\User;
use App\Http\Controllers\SystemManager\MailController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\NotifyController;
use Throwable;
use Session;
use Carbon\Carbon;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Tymon\JWTAuth\Claims\Custom;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\{PHPMailer, Exception, SMTP};
// use Srmklive\PayPal\Services\ExpressCheckout;
use PayPal;

class CustomerlookupController extends Controller
{
    protected $woocommerce;

    protected $provider;

    // const ACCOUNTID_FORMAT = 'XXX-999-9999';

    public static $connection = '{mylive-tech.com:995/pop3/ssl}';
    public static $mailserver_hostname = 'mylive-tech.com';
    public static $mailserver_username = 'accounts@mylive-tech.com';
    public static $mailserver_password = 'y7.v9jLy!JLG9!s';

    public function __construct() {
        $this->middleware('auth');

        $this->woocommerce = new Client(
            GeneralController::PROJECT_DOMAIN_NAME,
            'ck_dd8561dd74e2d4aa3cb367810ec981c6b639100d',
            'cs_1f080df2e83691ae7ab586ea86433c00f7f86975',
            [
                'version' => 'wc/v3',
                'verify_ssl' => false
            ]
        );

        $paypal= DB::Table("integrations")->where("name", "PayPal")->first();
        if(!empty($paypal)) {
            if(!empty($paypal->details)) {
                $details = json_decode($paypal->details, true);

                if(!empty($details['client_id'])) {
                    if(!isset($details['enviornment'])) $details['enviornment'] = 'sandbox';

                    $config = [
                        'mode' => env('PAYPAL_MODE', $details['enviornment']), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
                        'sandbox' => [
                            'client_id'     => $details['client_id'],
                            'client_secret' => $details['secret_key'],
                        ],'live' => [
                            'client_id'     => $details['client_id'],
                            'client_secret' => $details['secret_key'],
                        ],
                        'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
                        'currency'       => env('PAYPAL_CURRENCY', 'USD'),
                        'locale'         => env('PAYPAL_LOCALE', 'en_US'),
                        'notify_url'     => env('PAYPAL_NOTIFY_URL', ''),
                        'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', false)
                    ];

                    // PayPal::setProvider();
                    // $this->provider = PayPal::getProvider();
                    // $this->provider->setApiCredentials($config);
                    // $this->provider->setAccessToken($this->provider->getAccessToken());
                }
            }
        }
        // To use express checkout(used by default).


        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }

    public function getExpressCheckout(Request $request,$id) {
        Session::put('order_id',$id);

          $cart = $this->getCheckoutData($id);

        try {
            // $response = $this->provider->setExpressCheckout($cart, $recurring);
         $response=   $this->provider->createOrder([
                "intent"=> "CAPTURE",
                'application_context' =>
                array(
                    'return_url' => $cart['return_url'],
                    'cancel_url' => $cart['cancel_url'],
                    'brand_name' =>Session::get('site_title'),
                    'locale' => 'en-US',
                    'landing_page' => 'BILLING',
                    'user_action' => 'PAY_NOW',
                ),
                "purchase_units"=> [
                    0 => [
                        "amount"=> [
                            "currency_code"=> "USD",
                            "value"=> $cart['total'],
                            'breakdown' =>
                                        [
                                            'item_total' =>
                                                [
                                                    'currency_code' => 'USD',
                                                    'value' =>$cart['total'],
                                                ],],
                        ],
                        "items"=>$cart['items'],
                    ]
                ]
              ]);

               $response['id'];
              Session::put('paypal_order',$response['id']);
              foreach($response['links'] as $link)
              {
                  if($link['rel'] =="approve"){
                    return redirect($link['href']);
                    exit;
                  }



              }


        } catch (\Exception $e) {

            session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $id!"]);
        }
    }

    public function getExpressCheckoutSuccess(Request $request) {
        $request->all();
        $token = $request->get('token');
        $PayerID = $request->get('PayerID');
        $id=Session::get('order_id');
        $paypal_order=Session::get('paypal_order');
        $customer_id=Session::get('customer_id');

        try{
            // Verify Express Checkout Token
            $response = $this->provider->capturePaymentOrder($paypal_order);
            Session::forget('order_id');
            Session::forget('paypal_order');
            Session::forget('customer_id');

            DB::Table("orders")->where("id","=",$id)->update(["payment_method"=>"PayPal","date_completed"=>Carbon::now(),"date_completed"=>Carbon::now(),"transaction_id"=>$paypal_order,"status"=>4,"status_text"=>'Completed']);

                if (true) {
                    session()->put(['code' => 'success', 'message' => "Order $id has been paid successfully!"]);
                } else {
                    session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $id!"]);
                }
        } catch (\Exception $e) {

            session()->put(['code' => 'danger', 'message' => "Error processing PayPal payment for Order $id!"]);
        }

            return redirect('customer-profile/'. $customer_id.'#Success');

    }

    protected function getCheckoutData($id) {
        $data = [];
        $data['items']=[];
        $order = Orders::find($id);
        $customer_id=$order->customer_id;
        Session::put('customer_id',$customer_id);
        $order_items = DB::Table("line_items")->where("order_id","=",$id)->get();

        $total = 0;
        foreach($order_items as $key =>$value){

            $data['items'][]=[
                'name'=>$value->name,
                 'unit_amount' =>
            [
                    'currency_code' => 'USD',
                    'value' => $value->price,
            ],
                'quantity'=>$value->quantity,
            ];
            $total += $value->price * $value->quantity;
        }


        $data['return_url'] = url('/paypal/ec-checkout-success');


        $data['invoice_id'] = Session::get('site_title').'_'.$id;
        $data['invoice_description'] = "Order #$id Invoice";
        $data['cancel_url'] =  url('customer-profile/'. $customer_id.'#Error');



        $data['total'] = $total;

        return $data;
    }

    public function creditCardPyment($orderId,$payment_token){

        $data = [];
        $data['items']=[];
        $order = Orders::find($orderId);
        $account = CustomerCC::where('payment_token',$payment_token)->first();
        $customer_id=$order->customer_id;
        // Session::put('customer_id',$customer_id);
        $customer = Customer::where('id',$customer_id)->first();

        $order_items = DB::Table("line_items")->where("order_id","=",$orderId)->get();

        $total = $order->grand_total;
        // foreach($order_items as $key =>$value){
        //     $total += $value->price * $value->quantity;
        // }
        $nmi_integration= DB::Table("integrations")->where("name","=","NMI Payment Gateway")->first() ;
        $nmi_integration =json_decode($nmi_integration->details,true);
        $query  = "";
        // Login Information
        $query .= "security_key=" . urlencode($nmi_integration['security_key']) . "&";
        // Sales Information
        // $query .= "payment_token=" . urlencode($payment_token) . "&";
        $query .= "amount=" . urlencode($total) . "&";
        $query .= "payment=" . urlencode('creditcard') . "&";

        // Order Information
        $query .= "orderid=" . urlencode($orderId) . "&";
        // Billing Information
        $query .= "customer_vault_id=" . urlencode($account->customer_vault_id) . "&";

        // Shipping Information

        $query .= "type=sale";
        // return $query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.merchantonegateway.com/api/transact.php");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!($data = curl_exec($ch))) {
            return ERROR;
        }
        curl_close($ch);
        unset($ch);
        // print "\n$data\n";
        $responses=[];
        $data = explode("&",$data);
        for($i=0;$i<count($data);$i++) {
            $rdata = explode("=",$data[$i]);
            $responses[$rdata[0]] = $rdata[1];
        }


        if($responses['response']=='1'){
            DB::Table("orders")->where("id","=",$orderId)->update(["payment_method"=>"PayPal","date_completed"=>Carbon::now(),"date_completed"=>Carbon::now(),"transaction_id"=>$responses['transactionid'],"status"=>4,"status_text"=>'Completed']);
            $response['success'] = true;
            $response['message'] = 'Order Paid Successfully!';
        }else{
            $response['success'] = false;
            $response['message'] = $responses['responsetext'];
        }

        return   $response;

    }

    public function customer_lookup(){
        $customers = Customer::with('company')->where('is_deleted', 0)->get();

        $google_key = 0;
        $brand = BrandSettings::first();
        $google = DB::Table("integrations")->where("slug", "google-api")->where('status', 1)->first();

        if(!empty($google)) {
            if(!empty($google->details)) {
                $detail_values = explode(",", $google->details);
                $api = substr($detail_values[1], 1, -1);
                $explode_key = explode(":", $api);
                $key = substr($explode_key[1], 1, -1);

                if(!empty($key)) $google_key = 1;
            }
        }

        $countries = [];
        if($google_key === 0) $countries = DB::Table('countries')->get();

        $wp_integration = Integrations::where("slug", "wordPress")->where('status', 1)->first();
        $wp_value  = 0;

        if(!empty($wp_integration)) {
            if(!empty($wp_integration->details)) $wp_value = 1;
        }

        $date_format = Session('system_date');

        return view('customer_manager.customer_lookup.index-new',compact('google','customers','countries','brand','wp_value','google_key','date_format'));
    }

    public function customersList(){
        $customers = Customer::with('company')->where('is_deleted', 0)->orderBy('id','desc')->get();
        $response['message'] = 'List Fetched.';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['customers'] = $customers;
        $response['date_format'] = Session('system_date');
        return response()->json($response);
    }

    public function deleteCustomers(Request $request) {

        $customer = Customer::find($request->id);

        if($customer->has_account == 1) {
            User::where('email', $customer->email)->delete();
            DB::table("customers")->where('id',$request->id)->delete();
        }else{
            DB::table("customers")->where('id',$request->id)->delete();
        }

        if($customer->woo_id != null) {
            $wordpress = DB::Table("integrations")->where("slug", "wordpress")->where('status', 1)->first();
            if(!empty($wordpress)) {
                if($wordpress->is_verified == 1) {

                    if($wordpress->details != null & $wordpress->details != '') {

                        $detail_values = explode(",",$wordpress->details);
                        $api = substr($detail_values[1], 1, -1);
                        $explode_key = explode(":",$api);
                        $url = $explode_key[1] .':'. $explode_key[2];
                        $api_url = trim(str_replace( '\/', '/', $url ), '"');

                        $ck_key = substr($detail_values[2], 1, -1);
                        $explode_key = explode(":",$ck_key);
                        $secret_key = trim($explode_key[1], '"');

                        $con_key = substr($detail_values[3], 1, -1);
                        $explode_key = explode(":",$con_key);
                        $consumer_key = trim($explode_key[1],'"');


                        if($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {
                            $woocommerce = new Client(
                                $api_url,
                                $secret_key,
                                $consumer_key,
                                [
                                    'version' => 'wc/v3',
                                    'verify_ssl' => false
                                ]
                            );
                            $woocommerce->delete('customers/'.$customer->woo_id, ['force' => true]);
                        }

                    }

                }
            }
        }


        $response['message'] = 'Customer Deleted Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }

    public function service_stats() {
        return view('customer_manager.service_stats.index');
    }

    public function customer_profile($customer_id, $type = null)
    {


        $customer = Customer::with('company')->where('id', $customer_id)->first();
        $company_id = $customer->company_id;

        if(!empty($customer)) {
            $credential = User::where('email', $customer->email)->first();

            if($credential) {
                if($credential->alt_pwd) {
                    $customer->password = Crypt::decryptString($credential->alt_pwd);
                }
            }
        }

        $company = Company::get(['id','name']);
        if($type == 'json'){
            return response()->json(['customer' => $customer, 'company' => $company]);
        }


        $subscriptions = Subscriptions::where('customer_id', $customer->id)->get();

        foreach($subscriptions as $key=>$value){
            $line_items = LineItem::where('subscription_id', $value['id'])->get();
            if($line_items){
                foreach ($line_items as $j => $lt) {
                    $line_items[$j]['taxes'] = Tax::where('lineitem_id', $lt['id'])->get();
                }
            }
            $subscriptions[$key]['line_items'] = $line_items;
        }

        $orders = Orders::where('customer_id', $customer->id)->get();

        $departments = Departments::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();
        $customer_types = CustomerType::all();
        $statuses = TicketStatus::all();
        $ticket_format = TicketSettings::where('tkt_key', 'ticket_format')->first();

        $nmi_integration = DB::Table("integrations")->where("name", "NMI Payment Gateway")->first();
        if(!empty($nmi_integration)) {
            $nmi_integration = !empty($nmi_integration->details) ? json_decode($nmi_integration->details, true) : '';
        }

        $wp_integration = Integrations::where("slug", "wordpress")->where('status', 1)->first();
        $wp_value = 0;
        if(!empty($wp_integration)) {
            if(!empty($wp_integration->details)) $wp_value = 1;
        }

        $google_key = 0;
        $google = DB::Table("integrations")->where("slug", "google-api")->where('status', 1)->first();

        if(!empty($google)) {
            if(!empty($google->details)) {
                $detail_values = explode(",", $google->details);
                $api = substr($detail_values[1], 1, -1);
                $explode_key = explode(":", $api);
                $key = substr($explode_key[1], 1, -1);

                if(!empty($key)) $google_key = 1;

                $google = json_decode($google->details, true);
            }
        }

        $countries = [];

        if($google_key == 0) $countries = DB::Table('countries')->get();

        $date_format = Session('system_date');

        $customers = Customer::where('id','!=', $customer_id)->select('email')->get()->toArray();

        $notesCount = 0;

        $closed_status = TicketStatus::where('name','Closed')->first();
        $tickets = Tickets::where([['customer_id' , $customer_id] ,['is_deleted',0] ])->get();
        // $company_id

        $notesCount = TicketNote::whereIn('type',['User','User Organization'])->where('is_deleted',0)->where('customer_id',$customer_id)->orwhere('company_id',$company_id)->count();
        // return $notesCount;
        // foreach($tickets as $ticket) {
        //     $notesCount += TicketNote::where([['ticket_id' , $ticket->id] ,['type','User'],['is_deleted',0]])->count();
        // }
        $ticketsCount = $tickets->count();

        $ticketView = TicketView::where('user_id' , auth()->id())->first();

        $all_customers = Customer::with('company')->get();
        $all_companies = Company::all();

        $users = User::where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff', 0)->get();
        $noteUsers = [];
        foreach($users as $i => $user){
            $noteUsers[$i]['key'] = $user->name;
            $noteUsers[$i]['value'] = $user->name .' ('.$user->email.')';
        }
        $noteUsers = collect($noteUsers);


        return view('customer_manager.customer_lookup.customerprofile-new', get_defined_vars());
    }

    public function UserORGNote(Request $request) {
        $data = $request->all();

        $response = array();
        try{
            $action_performed = '';

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

            if( $request->id != null ){

                $note = TicketNote::findOrFail($data['id']);
                $note->color = $data['color'];
                $note->type = $data['type'];
                $note->note = $data['note'];
                $note->visibility = (array_key_exists('visibility', $data)) ? $data['visibility'] : '';
                $note->updated_by = Auth::user()->id;

                $note->updated_at = Carbon::now();
                $note->save();

                $data = $note;
                $action_performed = 'User Note updated by '. $name_link;
            }else{
                $data['created_by'] = Auth::user()->id;
                $note = TicketNote::create($data);
                $action_performed = 'User Note added by '. $name_link;
            }

            $sla_updated = false;

            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'ticket_notes' , $note->id , auth()->id() , $action_performed);

            $template = DB::table("templates")->where('code','ticket_common_notification')->first();

            if($request->tag_emails != null && $request->tag_emails != '') {

                $emails = explode(',',$request->tag_emails);

                for( $i = 0; $i < sizeof($emails); $i++ ) {

                    $user = User::where('is_deleted',0)->where('email',$emails[$i])->first();
                    if($user) {
                        $note = TicketNote::with('customer')->where('is_deleted', 0)->where('id',$note->id)->first();

                        $notify = new NotifyController();
                        $sender_id = Auth::user()->id;
                        $receiver_id = $user->id;
                        $slug = url('customer-profile') .'/'. $note->customer_id;
                        $type = 'ticket_notes';
                        $data = 'data';
                        $title = Auth::user()->name.' mentioned You ';
                        $icon = 'at-sign';
                        $class = 'btn-success';
                        $desc = 'You were mentioned by '.Auth::user()->name . ' on Note # ' . $note->id;

                        $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
                        $temp = $this->ticketCommonNotificationShortCodes($template->template_html , $note, '', 'note_mention', $note->note,'add_note');
                        $mail = new MailController();
                        $mail->sendMail( '@'.auth()->user()->name .' has mentioned you for Customer Note (' . $note->customer->first_name .' '.$note->customer->last_name .')' , $temp , 'system_mentioned@mylive-tech.com', $user->email , $user->name);
                    }
                }
            }



            // send notification
            $slug = url('customer-profile') .'/'. $note->customer_id;
            $type = 'ticket_updated';
            $title = ($request->id != null ? 'User Note Updated' : 'User Note Created');
            $desc = 'User (<a href="'.url('/customer-profile').'/' .$note->id.'">'.$note->id.'</a>)' . ($request->id != null ? ' Note Updated By ' : ' Note created by ') . auth()->user()->name;
            // sendNotificationToAdmins($slug , $type , $title ,  $desc);

            $response['message'] = 'User Note Saved Successfully!';
            $response['sla_updated'] = $sla_updated;
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['tkt_update_at'] = $note->updated_at;
            $response['data'] = $note;
            return response()->json($response);

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function ticketCommonNotificationShortCodes($templateHtml , $ticket , $flag , $tempType, $notes = '', $flag_type = '') {

        $template = htmlentities($templateHtml);


        if(str_contains($template, '{Subject}')) {
            $subject = auth()->user()->name . ' ' . ($tempType =='ticket_flag' ? $flag : ' mentioned you in ') . ' Ticket ' .  $ticket->coustom_id;
            $template = str_replace('{Subject}', $subject , $template);
        }

        if(str_contains($template, '{Flag-Image}')) {

            $url = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/');
            $flaggedImage = '<img src="'.$url.'/public/default_imgs/flagged.png" width="20" style="width:20px !important; height:20px !important" />';
            $unflaggedImage = '<img src="'.$url.'/public/default_imgs/unflagged.png" width="20" style="width:20px !important; height:20px !important" />';

            $template = str_replace('{Flag-Image}', ($tempType != 'ticket_flag' ? '' : ( $flag =='Flagged' ? $flaggedImage : $unflaggedImage ) ) , $template);
        }


        if($flag_type == 'add_ticket' || $flag_type == 'ticket_reply'){

            if(str_contains($template, '{Ticket-Subject}')) {
                $template = str_replace('{Ticket-Subject}',  $ticket->subject , $template);
            }

            if(str_contains($template, '{Ticket-Detail}')) {

                $date = new \DateTime($ticket['updated_at']);
                $date->setTimezone(new \DateTimeZone( timeZone() ));
                $ticketUpdated = '<strong>Updated</strong>: ' . $date->format(system_date_format() .' h:i a');

                $data = $this->getReplyDueAndResolutionDeadLine( $ticket );

                $template = str_replace('{Ticket-Detail}', $data[0] .' '. $data[1] . ' '. $ticketUpdated , $template);
            }

            if(str_contains($template, '{Go-To-Ticket}')) {
                $url = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/'). '/ticket-details' . '/' . $ticket->coustom_id;
                $template = str_replace('{Go-To-Ticket}', $url , $template);
            }

            if(str_contains($template, '{Notes}')) {
                $template = str_replace('{Notes}', ($tempType =='ticket_flag' ? '' : '') , $template);
            }

        }else{

            if(str_contains($template, '{Ticket-Subject}')) {
                $template = str_replace('{Ticket-Subject}',  '', $template);
            }

            if(str_contains($template, '{Ticket-Detail}')) {
                $template = str_replace('{Ticket-Detail}', '' , $template);
            }

            if(str_contains($template, '{Go-To-Ticket}')) {
                $template = str_replace('{Go-To-Ticket}', ' ', $template);
            }

            if(str_contains($template, '{Notes}')) {
                $template = str_replace('{Notes}', ($tempType =='ticket_flag' ? '' : $notes) , $template);
                $template = str_replace('Ticket', 'Note' , $template);
            }
        }

        return html_entity_decode($template);
    }

    public function loggedInAsCustomer($email)
    {
        try{
            $user = User::where('email',$email)->first();
            if($user){
                session()->forget('action_clicked_admin');
                session()->put('action_clicked_admin',$user->email);
                return redirect()->route('customer.myProfile');
            }else{
                return redirect()->back()->with('error','User Not Found');
            }
        }catch(Exception $e){

            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function myprofile($name, $type = null) {

        $user = User::where('id', \Auth::user()->id)->first();
        $customer_id = Customer::where('email', $user->email)->first();
        // return $customer_id->id;

        $customer = Customer::with('company')->where('id', $customer_id->id)->first();
        if(!empty($customer)) {
            $credential = User::where('email', $customer->email)->first();
            if(!empty($credential->alt_pwd)) {
                $customer->password = Crypt::decryptString($credential->alt_pwd);
            }
        }

        $company = Company::get(['id', 'name']);
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
        $nmi_integration =json_decode($nmi_integration->details,true);

        $wp_integration = Integrations::where("slug", "wordpress")->where('status', 1)->first();
        $wp_value  = 0;
        if(!empty($wp_integration)) {
            if(!empty($wp_integration->details)) $wp_value = 1;
        }

        $google_key = 0;
        $google = DB::Table("integrations")->where("slug", "google-api")->where('status', 1)->first();
        if(!empty($google)) {
            if(!empty($google->details)) {
                $detail_values = explode(",", $google->details);
                $api = substr($detail_values[1], 1, -1);
                $explode_key = explode(":", $api);
                $key = substr($explode_key[1], 1, -1);

                if(!empty($key)) $google_key = 1;

                $google = json_decode($google->details, true);
            }
        }

        $countries = [];
        if($google_key === 0) $countries = DB::Table('countries')->get();

        // return view('customer_manager.customer_lookup.customerprofile',compact('prof_state','customer','company', 'countries' , 'states' ,'subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format'));
        // return view('customer_manager.customer_lookup.custProfile',compact('prof_state','customer','company', 'countries' , 'states' ,'subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format'));
        return view('customer_manager.customer_lookup.custProfile',compact('google','nmi_integration','customer','company', 'countries' ,'subscriptions', 'orders', 'departments', 'priorities', 'types','customer_types', 'statuses', 'ticket_format','wp_value','google_key'));
    }

    public function checkout($customerId,$orderId) {
        $paypal = DB::Table("integrations")->where("name", "PayPal")->first();
        $nmi_integration = DB::Table("integrations")->where("name", "NMI Payment Gateway")->first();

        if(empty($PayPaldetails) && empty($nmi_integration)) {
            return back()->withErrors(['error', 'No payment method Is enabled or provided']);
        }
        $PayPaldetails = json_decode($paypal->details,true);
        $nmi_integration = json_decode($nmi_integration->details, true);

        $paypal='enable';
        $nmi='enable';
        if($PayPaldetails['client_id'] == '' || $PayPaldetails['secret_key'] == '' ){
            $paypal='disabled';
        }
        if( $nmi_integration['security_key']=='' ){
            $nmi='disabled';
        }
        if( $paypal =='disabled' && $nmi =='disabled'){
            return back()->withErrors(['error', 'No payment method Is enabled or provided']);

        }
        $order = Orders::where('custom_id',$orderId)->first();
        // dd($order);
        $customer = Customer::with('company')->where('id',$customerId)->first();
        // Session::put('customer_id',$customerId);
        $order_items = DB::Table("line_items")->where("order_id","=",$orderId)->get();

        $countries = DB::Table('countries')->get();

        $nmi_integration = DB::Table("integrations")->where("name", "NMI Payment Gateway")->first();
        if(!empty($nmi_integration)) {
            $nmi_integration = json_decode($nmi_integration->details, true);
        }

        return view('customer_manager.customer_lookup.checkout',compact('nmi','paypal','nmi_integration','order','customer','order_items','customerId','countries'));
    }

    public function editOrDelete(Request $request){
        $response = array();
        try{
            if($request->input('action') == 'edit'){
                return $this->update_customer($request);
            }elseif($request->input('action') == 'delete'){
                return $this->delete_customer($request);
            }

        }catch(\Exception $err){
            $response['message'] = 'Something went wrong! 1';
            $response['status_code'] = 500;
            $response['success'] = false;
        }
        return $response;
    }

    public function update_customer(Request $request){
        $response = array();
        try{
            $customer = Customer::find($request->input('id'));

            // $customer->address = $request->input('address');
            // // $customer->company_id = $request->input('0');
            // $customer->email = $request->input('email');
            // $customer->first_name = $request->input('first_name');
            // $customer->last_name = $request->input('last_name');
            // $customer->phone = $request->input('phone');
            $col_name = $request->column;
            $customer->$col_name = $request->value;
            // $customer->business_residential = $request->input('business_residential');
            $customer->updated_at = Carbon::now();

            $data = [
                'email' => $customer->email,
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'role' => 'customer'
            ];

            $integrations = Integrations::where('slug', 'wordpress')->where('status', 1)->first();
            if(!empty($integrations)) {
                if($this->woocommerce->get('customers/'.$customer->woo_id)) {
                    $this->woocommerce->put('customers/'.$customer->woo_id, $data);
                }
            }

            $customer->save();

            $response['success'] = true;
            $response['message'] = 'Customer details Update Successfully.';
        }catch (HttpClientException $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e;
        }
        return $response;
    }

    public function update_user(Request $request) {
        $response = array();
        try{
            $user = User::find($request->id);

            if( $request->password ) {
                $user->password = Hash::make($request->password);
                $user->alt_pwd = Crypt::encryptString($request->password);
            }

            $user->name = $request->update_name;
            $user->email = $request->update_email;
            $user->address = $request->address;
            $user->phone_number = $request->phone_number;
            $user->country = $request->country;

            $user->state = $request->state;
            $user->city = $request->city;
            $user->twitter = $request->twitter;
            $user->fb = $request->fb;
            $user->insta = $request->insta;

            $user->updated_at = Carbon::now();
            $user->save();

            $response['success'] = true;
            $response['message'] = 'User details Update Successfully.';
        }catch (HttpClientException $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e;
        }
        return $response;
    }

    public function delete_customer(Request $request){
        $response = array();
        try{
            $customer = Customer::find($request->input('id'));
            $customer->is_deleted = 1;
            $customer->deleted_at = Carbon::now();

            $integrations = Integrations::where('slug', 'wordpress')->where('status', 1)->first();
            if(!empty($integrations)) {
                if($this->woocommerce->get('customers/'.$customer->woo_id)) {
                    $this->woocommerce->delete('customers/'.$customer->woo_id, ['force' => true]);
                }
            }
            $customer->save();

            $response['success'] = true;
            $response['message'] = 'Customer Deleted Successfully.';
        }catch (HttpClientException $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = 'Something went wrong! 2';
        }
        return $response;
    }

    public function newCustomerReplaceShortCodes($customer, $templateHtml) {
        $template = htmlentities($templateHtml);

        if(str_contains($template, '{Staff-Name}')) {
            $template = str_replace('{Staff-Name}', auth()->user()->name , $template);
        }

        if(str_contains($template, '{Customer-Name}')) {
            $template = str_replace('{Customer-Name}', $customer->first_name . ' ' . $customer->last_name , $template);
        }

        if(str_contains($template, '{User-Name}')) {
            $template = str_replace('{User-Name}', $customer->first_name . ' ' . $customer->last_name , $template);
        }
        if(str_contains($template, '{User-Email}')) {
            $template = str_replace('{User-Email}', $customer->email , $template);
        }
        if(str_contains($template, '{User-Organization}')) {

            if($customer->company_id) {
                $company = Company::where('id', $customer->company_id)->first();
            }
            $template = str_replace('{User-Organization}', $customer->company_id == null ? '' : (empty($company) ? '' : $company->name ) , $template);
        }
        if(str_contains($template, '{User-Profile-URL}')) {
            $url = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/') .'/'. 'customer-profile/'. $customer->id;
            $template = str_replace('{User-Profile-URL}', $url , $template);
        }
        if(str_contains($template, '{Created_at}')) {
            $date = new \DateTime($customer->created_at);
            $date->setTimezone(new \DateTimeZone( timeZone() ));
            $template = str_replace('{Created_at}', $date->format(system_date_format() .' h:i a') , $template);
        }


        return html_entity_decode($template);
    }

    //save customer
    public function save_customer(Request $request){
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email',
                'phone' => 'required'
            ]);

            $random_no = Str::random(15);

            if($request->has('password')) {
                $request->validate([
                    'password' => 'required|required_with:confirm_password|same:confirm_password|min:8',
                    'confirm_password' => 'required|min:8'
                ]);

                $random_no = $request->password;
            }

            $company = [
                "name" => $request->cmp_name,
                "poc_first_name" => $request->poc_first_name,
                "poc_last_name" => $request->poc_last_name,
                "email" => $request->cmp_email,
                "phone" => $request->cmp_phone
            ];

            $customer_data = [
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "phone" => $request->phone,
                "cust_type" => $request->cust_type,
                "country" => $request->country,
                "cust_state" => $request->state,
                "cust_city" => $request->city,
                "cust_zip" => $request->zip,
                "address" => $request->address,
                "apt_address" => $request->apt_address,
                "fb" => $request->has('facebook') ? $request->facebook : null,
                "twitter" => $request->has('twitter') ? $request->twitter : null,
                "insta" => $request->has('insta') ? $request->insta : null,
                "pinterest" => $request->has('pinterest') ? $request->pinterest : null,
                "linkedin" => $request->has('linkedin') ? $request->linkedin : null,
                "username" => $request->email,
                "po" => $request->has('po') ? $request->po : null,
                "alt_phone" => $request->has('alt_phone') ? $request->alt_phone : null
            ];

            $woo_data = array(
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "email" => $request->email,
                "phone" => $request->phone
            );

            $wordpress = DB::Table("integrations")->where("slug","wordpress")->where('status', 1)->first();

            $check_customer = DB::table("customers")->where('email', $request->email)->where('is_deleted', 0)->first();

            $check_user = '';
            if($request->customer_login == 1) {
                $check_user = DB::table("users")->where('email', $request->email)->where('is_deleted', 0)->first();
            }

            if(!empty($check_customer) || !empty($check_user)) {
                return response()->json([
                    "message" => 'Email Already Taken try another one',
                    "status" => 500,
                    "success" => false,
                ]);
            }

            $response = [
                "message" => 'Wordpress plugin not verified so we are saving record in our system only',
                "status" => 201,
                "success" => true
            ];

            if($request->has('cmp_name') && !empty($request->cmp_name) && $request->has('cmp_email') && $request->cmp_email) $customer_data['company_id'] = Company::insertGetId($company);
            else $customer_data['company_id'] = $request->company_id;

            if($request->has_account) $customer_data['has_account'] =  $request->has_account;

            if(!empty($wordpress)) {
                if($wordpress->is_verified == 1) {
                    if(!empty($wordpress->details)) {
                        $detail_values = explode(",",$wordpress->details);
                        $api = substr($detail_values[1], 1, -1);
                        $explode_key = explode(":",$api);
                        $url = $explode_key[1] .':'. $explode_key[2];
                        $api_url = trim(str_replace( '\/', '/', $url ), '"');

                        $ck_key = substr($detail_values[2], 1, -1);
                        $explode_key = explode(":",$ck_key);
                        $secret_key = trim($explode_key[1], '"');

                        $con_key = substr($detail_values[3], 1, -1);
                        $explode_key = explode(":",$con_key);
                        $consumer_key = trim($explode_key[1],'"');

                        if(!empty($api_url) && !empty($secret_key) && !empty($consumer_key)) {
                            $woocommerce = new Client(
                                $api_url,
                                $secret_key,
                                $consumer_key,
                                [
                                    'version' => 'wc/v3',
                                    'verify_ssl' => false
                                ]
                            );

                            $woocommerce_data = $woocommerce->post('customers', $woo_data);
                            $customer_data['woo_id'] = $woocommerce_data->id;
                        }

                        $response = [
                            "message" => 'Customer Added Successfully',
                            "status" => 200,
                            "success" => true
                        ];
                    }
                }
            }

            $customer_ticket = Tickets::where('cust_email' , $request->email)->where('is_pending',0)->get();

            if(!empty($customer_ticket)) {
                foreach($customer_ticket as $ticket) {
                    $ticket->is_pending = 0;
                    $ticket->save();
                    $notify = new HelpdeskController();
                    $notify->sendNotificationMail($ticket->toArray(), 'ticket_create', '', '', 'Ticket Create' , '', $request->email , '' , 1 , 0);
                }
            }

            $newCustomer = Customer::create($customer_data);

            if($request->customer_login == 1) {
                DB::table("users")->insert([
                    "name" => $request->first_name . " " . $request->last_name,
                    "email" => $request->email,
                    "password" => Hash::make($random_no),
                    "alt_pwd" => Crypt::encryptString($random_no),
                    "user_type" => 5,
                    "status" => 1
                ]);

                $mailer = new MailController();
                $mailer->UserRegisteration($request->email,true,'customer');
            }

            $template = DB::table("templates")->where('code','new_customer_notification_to_admins')->first();
            if(!empty($template)) {
                $temp = $this->newCustomerReplaceShortCodes($newCustomer, $template->template_html);

                $mail = new MailController();
                $users = User::where('user_type', 1)->get();
                foreach($users as $user) {
                    $mail->sendMail( 'User Notification: Live-Tech System - New User ' . $newCustomer->email , $temp, 'system_user@mylive-tech.com', $user->email, $user->name);
                }

                // $mail->sendMail( $title , $temp , 'system_notification@mylive-tech.com', $user->email , $user->name);
            }



            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

        // if($check_customer) {
        //     if($request->cmp_name && $request->cmp_email) {
        //         $customer_data['company_id'] = Company::insertGetId($company);

        //         if($request->has_account) {
        //             $customer_data['has_account'] =  $request->has_account;
        //         }

        //         if($wordpress->is_verified == 1) {
        //             if($wordpress->details != NULL & $wordpress->details != '') {

        //                 $detail_values = explode(",",$wordpress->details);
        //                 $api = substr($detail_values[1], 1, -1);
        //                 $explode_key = explode(":",$api);
        //                 $url = $explode_key[1] .':'. $explode_key[2];
        //                 $api_url = trim(str_replace( '\/', '/', $url ), '"');

        //                 $ck_key = substr($detail_values[2], 1, -1);
        //                 $explode_key = explode(":",$ck_key);
        //                 $secret_key = trim($explode_key[1], '"');

        //                 $con_key = substr($detail_values[3], 1, -1);
        //                 $explode_key = explode(":",$con_key);
        //                 $consumer_key = trim($explode_key[1],'"');

        //                 if($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {
        //                     $woocommerce = new Client(
        //                         $api_url,
        //                         $secret_key,
        //                         $consumer_key,
        //                         [
        //                             'version' => 'wc/v3',
        //                             'verify_ssl' => false
        //                         ]
        //                     );

        //                     $woocommerce_data = $woocommerce->post('customers', $woo_data);
        //                     $customer_data['woo_id'] = $woocommerce_data->id;
        //                     // Customer::create($customer_data);
        //                 }

        //             } else {
        //                 // Customer::create($customer_data);
        //                 // if($request->customer_login == 1) {

        //                 //     DB::table("users")->insert([
        //                 //         "name" => $request->first_name . " " . $request->last_name,
        //                 //         "email" => $request->email,
        //                 //         "password" => Hash::make($random_no),
        //                 //         "alt_pwd" => Crypt::encryptString($random_no),
        //                 //         "user_type" => 5,
        //                 //         "status" => 1
        //                 //     ]);

        //                 //     $mailer->UserRegisteration($request->email);
        //                 // }
        //                 $response = [
        //                     "message" => 'Wordpress plugin not verified so we are saving record in our system only',
        //                     "status" => 201,
        //                     "success" => true,
        //                 ];
        //                 // return response()->json([
        //                 //     "message" => 'Wordpress plugin not verified so we are saving record in our system only',
        //                 //     "status" => 201,
        //                 //     "success" => true,
        //                 // ]);
        //             }
        //         }

        //         Customer::create($customer_data);

        //         if($request->customer_login == 1) {
        //             DB::table("users")->insert([
        //                 "name" => $request->first_name . " " . $request->last_name,
        //                 "email" => $request->email,
        //                 "password" => Hash::make($random_no),
        //                 "alt_pwd" => Crypt::encryptString($random_no),
        //                 "user_type" => 5,
        //                 "status" => 1
        //             ]);

        //             $mailer->UserRegisteration($request->email);
        //         }

        //         // return response()->json([
        //         //     "message" =>  'Customer Added Successfully. 1',
        //         //     "status" => 200,
        //         //     "success" => true
        //         // ]);
        //         $response = [
        //             "message" =>  'Customer Added Successfully. 1',
        //             "status" => 200,
        //             "success" => true
        //         ];
        //     }else {

        //         if($request->has_account) {
        //             $customer_data['has_account'] =  $request->has_account;
        //         }

        //         if($wordpress->is_verified == 1) {
        //             if($wordpress->details != NULL & $wordpress->details != '') {

        //                 $detail_values = explode(",",$wordpress->details);
        //                 $api = substr($detail_values[1], 1, -1);
        //                 $explode_key = explode(":",$api);
        //                 $url = $explode_key[1] .':'. $explode_key[2];
        //                 $api_url = trim(str_replace( '\/', '/', $url ), '"');

        //                 $ck_key = substr($detail_values[2], 1, -1);
        //                 $explode_key = explode(":",$ck_key);
        //                 $secret_key = trim($explode_key[1], '"');

        //                 $con_key = substr($detail_values[3], 1, -1);
        //                 $explode_key = explode(":",$con_key);
        //                 $consumer_key = trim($explode_key[1],'"');

        //                 if($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {
        //                     $woocommerce = new Client(
        //                         $api_url,
        //                         $secret_key,
        //                         $consumer_key,
        //                         [
        //                             'version' => 'wc/v3',
        //                             'verify_ssl' => false
        //                         ]
        //                     );

        //                     $woocommerce_data = $woocommerce->post('customers', $woo_data);
        //                     $customer_data['woo_id'] = $woocommerce_data->id;
        //                     Customer::create($customer_data);
        //                 }

        //             }else{
        //                 Customer::create($customer_data);
        //                 if($request->customer_login == 1) {

        //                     $random_no = Str::random(15);
        //                     DB::table("users")->insert([
        //                         "name" => $request->first_name . " " . $request->last_name,
        //                         "email" => $request->email,
        //                         "password" => Hash::make($random_no),
        //                         "alt_pwd" => Crypt::encryptString($random_no),
        //                         "user_type" => 5,
        //                         "status" => 1
        //                     ]);

        //                     $mailer->UserRegisteration($request->email);
        //                 }
        //                 return response()->json([
        //                     "message" => 'Wordpress plugin not verified so we are saving record in our system only',
        //                     "status" => 201,
        //                     "success" => true,
        //                 ]);
        //             }
        //         }else{
        //             Customer::create($customer_data);
        //         }

        //         if($request->customer_login == 1) {

        //             $random_no = Str::random(15);
        //             DB::table("users")->insert([
        //                 "name" => $request->first_name . " " . $request->last_name,
        //                 "email" => $request->email,
        //                 "password" => Hash::make($random_no),
        //                 "alt_pwd" => Crypt::encryptString($random_no),
        //                 "user_type" => 5,
        //                 "status" => 1
        //             ]);

        //             $mailer->UserRegisteration($request->email);
        //         }

        //         return response()->json([
        //             "message" =>  'Customer Added Successfully. 2',
        //             "status" => 200,
        //             "success" => true
        //         ]);

        //     }
        // }else{
            // if($request->cmp_name && $request->cmp_email) {
            //     $company_id = Company::insertGetId($company);

            //     $customer_data['company_id'] = $company_id;
            //     if($request->has_account) {
            //         $customer_data['has_account'] =  $request->has_account;
            //     }

            //     if($wordpress->is_verified == 1) {
            //         if($wordpress->details != NULL & $wordpress->details != '') {

            //             $detail_values = explode(",",$wordpress->details);
            //             $api = substr($detail_values[1], 1, -1);
            //             $explode_key = explode(":",$api);
            //             $url = $explode_key[1] .':'. $explode_key[2];
            //             $api_url = trim(str_replace( '\/', '/', $url ), '"');

            //             $ck_key = substr($detail_values[2], 1, -1);
            //             $explode_key = explode(":",$ck_key);
            //             $secret_key = trim($explode_key[1], '"');

            //             $con_key = substr($detail_values[3], 1, -1);
            //             $explode_key = explode(":",$con_key);
            //             $consumer_key = trim($explode_key[1],'"');

            //             if($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {
            //                 $woocommerce = new Client(
            //                     $api_url,
            //                     $secret_key,
            //                     $consumer_key,
            //                     [
            //                         'version' => 'wc/v3',
            //                         'verify_ssl' => false
            //                     ]
            //                 );

            //                 $woocommerce_data = $woocommerce->post('customers', $woo_data);
            //                 $customer_data['woo_id'] = $woocommerce_data->id;
            //                 Customer::create($customer_data);
            //             }

            //         }else{
            //             Customer::create($customer_data);
            //             if($request->customer_login == 1) {

            //                 $random_no = Str::random(15);
            //                 DB::table("users")->insert([
            //                     "name" => $request->first_name . " " . $request->last_name,
            //                     "email" => $request->email,
            //                     "password" => Hash::make($random_no),
            //                     "alt_pwd" => Crypt::encryptString($random_no),
            //                     "user_type" => 5,
            //                     "status" => 1
            //                 ]);

            //                 $mailer->UserRegisteration($request->email);
            //             }
            //             return response()->json([
            //                 "message" => 'Wordpress plugin not verified so we are saving record in our system only',
            //                 "status" => 201,
            //                 "success" => true,
            //             ]);
            //         }
            //     }else{
            //         Customer::create($customer_data);
            //     }

            //     if($request->customer_login == 1) {
            //         DB::table("users")->insert([
            //             "name" => $request->first_name . " " . $request->last_name,
            //             "email" => $request->email,
            //             "password" => Hash::make($random_no),
            //             "alt_pwd" => Crypt::encryptString($random_no),
            //             "user_type" => 5,
            //             "status" => 1
            //         ]);

            //         $mailer->UserRegisteration($request->email);
            //     }

            //     return response()->json([
            //         "message" =>  'Customer Added Successfully. 3',
            //         "status" => 200,
            //         "success" => true
            //     ]);

            // }else {

            //     $customer_data['company_id'] = $request->company_id;

            //     if($request->has_account) {
            //         $customer_data['has_account'] =  $request->has_account;
            //     }

                // if($wordpress->is_verified == 1) {
                //     if($wordpress->details != NULL & $wordpress->details != '') {

                //         $detail_values = explode(",",$wordpress->details);
                //         $api = substr($detail_values[1], 1, -1);
                //         $explode_key = explode(":",$api);
                //         $url = $explode_key[1] .':'. $explode_key[2];
                //         $api_url = trim(str_replace( '\/', '/', $url ), '"');

                //         $ck_key = substr($detail_values[2], 1, -1);
                //         $explode_key = explode(":",$ck_key);
                //         $secret_key = trim($explode_key[1], '"');

                //         $con_key = substr($detail_values[3], 1, -1);
                //         $explode_key = explode(":",$con_key);
                //         $consumer_key = trim($explode_key[1],'"');

                //         if($api_url != null && $api_url != " " && $secret_key != null && $secret_key != " " && $consumer_key != null && $consumer_key != " ") {
                //             $woocommerce = new Client(
                //                 $api_url,
                //                 $secret_key,
                //                 $consumer_key,
                //                 [
                //                     'version' => 'wc/v3',
                //                     'verify_ssl' => false
                //                 ]
                //             );

                //             $woocommerce_data = $woocommerce->post('customers', $woo_data);
                //             $customer_data['woo_id'] = $woocommerce_data->id;
                //             Customer::create($customer_data);
                //         }

                //     }else{
                //         Customer::create($customer_data);

                //         if($request->customer_login == 1) {

                //             $random_no = Str::random(15);
                //             DB::table("users")->insert([
                //                 "name" => $request->first_name . " " . $request->last_name,
                //                 "email" => $request->email,
                //                 "password" => Hash::make($random_no),
                //                 "alt_pwd" => Crypt::encryptString($random_no),
                //                 "user_type" => 5,
                //                 "status" => 1
                //             ]);

                //             $mailer->UserRegisteration($request->email);
                //         }

                //         return response()->json([
                //             "message" => 'Wordpress plugin not verified so we are saving record in our system only',
                //             "status" => 201,
                //             "success" => true,
                //         ]);
                //     }
                // }else{
                //     Customer::create($customer_data);
                // }

                // if($request->customer_login == 1) {
                //     DB::table("users")->insert([
                //         "name" => $request->first_name . " " . $request->last_name,
                //         "email" => $request->email,
                //         "password" => Hash::make($random_no),
                //         "alt_pwd" => Crypt::encryptString($random_no),
                //         "user_type" => 5,
                //         "status" => 1
                //     ]);

                //     $mailer->UserRegisteration($request->email);
                // }

                // return response()->json([
                //     "message" =>  'Customer Added Successfully 4',
                //     "status" => 200,
                //     "success" => true
                // ]);
            // }
        // }
    }

    // public function UserRegisteration($email,$password,$new) {

    //     $user = User::where("email",$email)->first();
    //     $billing_template = DB::table('templates')->where('code','new_user_signup')->first();

    //     if(empty($billing_template)) {
    //         throw new Exception('Template not found');
    //     }

    //     $order_input = array(
    //         array('module' => 'User', 'values' => $user->toArray()),
    //     );

    //     $template = $this->UserRegisterationParser($order_input, $billing_template->template_html,$user,$password,$new);

    //     $mail = new MailController();
    //     $mail->sendMail("New Registration", $template, 'accounts@mylive-tech.com', $user->email, $user->name);
    // }

    // public function UserRegisterationParser($data_list, $template , $user, $password, $new) {
    //     if(empty($template)) {
    //         throw new Exception('Template is empty!');
    //     }

    //     if(empty($data_list)) {
    //         throw new Exception('Provided data list is empty!');
    //     }

    //     $template = htmlentities($template);

    //     if(str_contains($template, '{User-Name}')) {
    //         $content = DB::table('templates')->where('code', 'new_user_signup')->first();

    //         if(!empty($content)) {
    //             $content = $content->template_html;
    //             $this->replaceShortCodes($data_list, $content,$password,$new);
    //         }
    //     }

    //     $this->replaceShortCodes($data_list, $template,$password,$new);
    //     $sc_vars = DB::table('sc_variables')->get();

    //     foreach ($sc_vars as $key => $value) {

    //         if(str_contains($template, $value->code)) {
    //             $template = str_replace($value->code," ", $template);
    //         }
    //     }
    //     return html_entity_decode($template);
    // }

    // public function replaceShortCodes($data_list, &$template,$password,$new) {
    //     $brand_setting = DB::table("brand_settings")->first();
    //     if($brand_setting) {
    //         // $site_logo = $brand_setting->site_logo != null && $brand_setting->site_logo != '' ? $brand_setting->site_logo : '';
    //         // $current_url = strval(URL::to('/') . "/public/files/brand_files/'.$site_logo.'");
    //         // $img = '<img src="'.$current_url.'" width="150" height="150"/>';
    //         $img = '<img src="'.GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/').'/public/files/brand_files/'.$brand_setting->site_logo .'" width="150" height="150"/>';
    //     }else{
    //         $img = '<img src="{{ asset("files/user_photos/logo.gif")}}" width="150" height="150"/>';
    //     }

    //     foreach ($data_list as $key => $data) {

    //         if($new == "new") {
    //             if(str_contains($template, 'Welcome User')) {
    //                 $template = str_replace('Welcome User', "Welcome " .  $data['values']['name'], $template);
    //             }
    //             if(str_contains($template, 'description')) {
    //                 $msg = "We're excited to have you get started. you account has been created now you can login with following credentials.";
    //                 $template = str_replace('description',  $msg , $template);
    //             }
    //             if($data['module'] == 'User' && str_contains($template, '{User-Name}')) {
    //                 $template = str_replace('{User-Name}', "Name : " .  $data['values']['name'], $template);
    //             }

    //             if($data['module'] == 'User' && str_contains($template, '{User-Email}')) {
    //                 $template = str_replace('{User-Email}', "Email : " . $data['values']['email'], $template);
    //             }

    //             if($data['module'] == 'User' && str_contains($template, '{User-Password}')) {
    //                 $template = str_replace('{User-Password}',"Password : " .   $password, $template);
    //             }

    //             if($data['module'] == 'User' && str_contains($template, '{Company-Logo}')) {
    //                 $template = str_replace('{Company-Logo}', $img, $template);
    //             }
    //         }else{
    //             $template = str_replace('Welcome User', "Dear " .  $data['values']['name'] . ", Your Account Details are updated kindly check your Account", $template);

    //             if(str_contains($template, 'description')) {
    //                 $msg = " ";
    //                 $template = str_replace('description',  $msg , $template);
    //             }
    //             if($data['module'] == 'User' && str_contains($template, '{User-Name}')) {
    //                 $template = str_replace('{User-Name}', " " , $template);
    //             }

    //             if($data['module'] == 'User' && str_contains($template, '{User-Email}')) {
    //                 $template = str_replace('{User-Email}', " ", $template);
    //             }

    //             if($data['module'] == 'User' && str_contains($template, '{User-Password}')) {
    //                 $template = str_replace('{User-Password}'," ", $template);
    //             }

    //         }

    //         if(!is_array($data['values'])) $data['values'] = (array) $data['values'];

    //         foreach ($data['values'] as $key => $value) {
    //             $k = str_replace('_', ' ', $key);
    //             $k = ucwords($k);
    //             $k = str_replace(' ', '-', $k);

    //             if(!is_array($value) && !is_object($value)) {
    //                 $template = str_replace('{'.$data['module'].'-'.$k.'}', $value, $template);
    //             }
    //         }
    //     }
    // }

    // public function sendMail($subject, $recipient,$body, $recipient_name, $reply = false) {
    //     try {
    //         $mail = new PHPMailer();

    //         $mail->setFrom(self::$mailserver_username);
    //         $mail->addAddress($recipient, $recipient_name);

    //         //Recipients
    //         if ($reply) {
    //             $mail->addReplyTo($recipient, $subject);
    //         }

    //         $mail->isHTML(true);
    //         $mail->Subject = $subject;
    //         $mail->Body    = $body;
    //         $mail->AltBody = '';

    //         $mail->send();

    //     } catch (Exception $e) {
    //         throw new Exception($e);
    //     }
    // }

    public function save_cust_card(Request $request){
        $query  = "";
        $check ='new';
        $valt= CustomerCC::where("customer_id","=",$request->customer_id)->first();


        $query .= "customer_vault=add_customer&";


        // Login Information
        $query .= "security_key=" . urlencode('gVH4w9X6GS53MPThD753PNDme3rt4JGf') . "&";


        // $query .= "customer_vault_id=" . urlencode('vaultId-'.$request->customer_id) . "&";
        $query .= "billing_id=" . urlencode('billId'.$request->customer_id) . "&";
        $query .= "payment_token=" . urlencode($request->payment_token) . "&";

        // Billing Information
        $query .= "firstname=" . urlencode($request->fname) . "&";
        $query .= "lastname=" . urlencode($request->lname) . "&";
        // $query .= "company=" . urlencode($this->billing['company']) . "&";
        $query .= "address1=" . urlencode($request->address1) . "&";
        // $query .= "address2=" . urlencode($this->billing['address2']) . "&";
        $query .= "city=" . urlencode($request->city) . "&";
        $query .= "state=" . urlencode($request->state) . "&";
        $query .= "zip=" . urlencode($request->zip) . "&";
        $query .= "email=" . urlencode($request->email) . "&";
        // $query .= "country=" . urlencode($this->billing['country']) . "&";
        // $query .= "phone=" . urlencode($this->billing['phone']) . "&";
        // $query .= "fax=" . urlencode($this->billing['fax']) . "&";
        // $query .= "email=" . urlencode($this->billing['email']) . "&";
        // $query .= "website=" . urlencode($this->billing['website']) . "&";


        //return $query;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://secure.merchantonegateway.com/api/transact.php");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!($data = curl_exec($ch))) {
            return ERROR;
        }
        curl_close($ch);
        unset($ch);
        // print "\n$data\n";
        $responses=[];
        $data = explode("&",$data);
        for($i=0;$i<count($data);$i++) {
            $rdata = explode("=",$data[$i]);
            $responses[$rdata[0]] = $rdata[1];
        }
         $responses;

        if($responses['response']=='1'){

            $valt = new CustomerCC();
            $valt->customer_id = $request->customer_id;
            $valt->payment_token = $request->payment_token;
            $valt->customer_vault_id =$responses['customer_vault_id'];
            $valt->fname = $request->fname;
            $valt->lname = $request->lname;
            $valt->exp = $request->exp;
            $valt->address1 = $request->address1;
            $valt->city = $request->city;
            $valt->state = $request->state;
            $valt->zip = $request->zip;
            $valt->card_type = $request->card_type;
            $valt->cardlastDigits =substr ($request->cardlastDigits, -4) ;
            $valt->created_at = Carbon::now();
            $valt->created_by = \auth()->user()->id;

            if($valt->save()){
                $cmp_act_log = new CompanyActivityLog();
                $cmp_act_log->action_perform = auth()->user()->name.' Created '.$valt->fname.$valt->lname .' valt account';
                $cmp_act_log->created_by = \auth()->user()->id;
                $cmp_act_log->save();

                $response['message'] = 'Card Details Added Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['result'] = $valt->id;
                return response()->json($response);
            }else{
                $response['message'] = 'Something Went wrong!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }



        }else{

                $responses['message'] = 'Something Went wrong!';
                $responses['status_code'] = 500;
                $responses['success'] = False;
                return response()->json($responses);

        }
    }

    public function get_customer_card(Request $request){
        // $types = TicketType::get();
        $types = DB::table('cust_cc')->where('customer_id',$request->customer_id)->get();

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['types']= $types;

        return response()->json($response);
    }


    public function uploadCustomerImage(Request $request) {

        $image = $request->file('profile_img');
        $imageName = $_FILES['profile_img']['name'];

        $imageName = strtolower($imageName);
        $imageName = str_replace(" ","_",$imageName);

        $target_dir = 'storage/customers';

        if (!File::isDirectory($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image->move($target_dir, $imageName);

        $customer = Customer::where('id' , $request->customer_id)->first();
        $customer->avatar_url = 'storage/customers/'. $imageName;
        $customer->save();

        $user = User::where('email', $customer->email)->first();
        if($user) {
            $user->profile_pic = 'storage/customers/'. $imageName;
            $user->save();
        }

        $response['message'] = 'Customer Profile Uploaded Successfully';
        $response['status'] = 200;
        $response['success'] = true;
        $response['img'] = $customer->avatar_url;
        return response()->json($response);
    }



    public function syncCustomers() {
        $integrations = Integrations::where('slug', 'wordpress')->where('status', 1)->first();
        if(empty($integrations)) {
            echo 'Wordpress integration is not enabled';
            return false;
        }

        $perpage = 100;
        $count = 1;
        $sync = true;
        $total = 1;
        $errors = '';
        try {
            while($sync) {
                echo "<pre>";
                echo "<br><br><h3>Page : ".$count."</h3><br><br>";
                $result = json_decode(json_encode($this->woocommerce->get('customers?per_page='.$perpage.'&page='.$count)), true);
                $count++;

                foreach ($result as $key => $value) {

                    if( empty($value['first_name']) || empty($value['email']) ) {
                        continue;
                    }
                    echo $total. " ) ";

                    $company = null;
                    if(!empty($value['billing']['company']) && isset($value['billing']['company'])){
                        $company = Company::where('name', $value['billing']['company'])->first();
                        if(!$company) {
                            $company = Company::create([
                                'poc_first_name' => $value['billing']['first_name'],
                                'poc_last_name' => $value['billing']['last_name'],
                                'name' => $value['billing']['company'],
                                'address' => $value['billing']['address_1'],
                                'cmp_bill_add' => $value['billing']['address_1'],
                                'cmp_ship_add' => $value['shipping']['address_1'],
                                'cmp_city' => $value['billing']['city'],
                                'cmp_zip' => $value['billing']['postcode'],
                                'cmp_country' => $value['billing']['country'],
                                'cmp_state' => $value['billing']['state'],
                                'email' => $value['billing']['email'],
                                'phone' => $value['billing']['phone'],
                            ]);
                            echo "<br><br>Customer ".$value['username']." Company Added.<br><br>";
                        }else{
                            $company->save([
                                'poc_first_name' => $value['billing']['first_name'],
                                'poc_last_name' => $value['billing']['last_name'],
                                'name' => $value['billing']['company'],
                                'address' => $value['billing']['address_1'],
                                'cmp_bill_add' => $value['billing']['address_1'],
                                'cmp_ship_add' => $value['shipping']['address_1'],
                                'cmp_city' => $value['billing']['city'],
                                'cmp_zip' => $value['billing']['postcode'],
                                'cmp_country' => $value['billing']['country'],
                                'cmp_state' => $value['billing']['state'],
                                'email' => $value['billing']['email'],
                                'phone' => $value['billing']['phone'],
                            ]);
                            echo "<br><br>Customer ".$value['username']." Company Updated.<br><br>";
                        }
                        $company = $company->id;
                    }

                    $customer = Customer::where('woo_id', $value['id'])->first();
                    $cust_password = '';
                    if(!empty($company)) {
                        $cust_password = Hash::make('12345678');
                    }
                    if(!$customer) {
                        $customer = Customer::create([
                            "woo_id" => $value['id'],
                            "username" => $value['username'],
                            "first_name" => $value['first_name'],
                            "last_name" => $value['last_name'],
                            "email" => $value['email'],
                            "cust_password" => $cust_password,
                            "is_paying_customer" => $value['is_paying_customer'],
                            "avatar_url" => $value['avatar_url'],
                            "company_id" => $company,
                            "cust_type" => $value['cust_type'],
                        ]);
                        echo "<br><br>Customer ".$value['username']." Added.<br><br>";
                    } else {
                        $customer->save([
                            "woo_id" => $value['id'],
                            "username" => $value['username'],
                            "first_name" => $value['first_name'],
                            "last_name" => $value['last_name'],
                            "email" => $value['email'],
                            "cust_password" => $cust_password,
                            "is_paying_customer" => $value['is_paying_customer'],
                            "avatar_url" => $value['avatar_url'],
                            "company_id" => $company,
                            "cust_type" => $value['cust_type'],
                            "updated_at" => Carbon::now()
                        ]);
                        echo "<br><br>Customer ".$value['username']." Updated.<br><br>";
                    }

                    // if(!empty($value['billing']['city']) && isset($value['billing']['city'])){
                    //     $customerBilling = Billing::where('customer_id', $customer->id)->where('type', 'billing')->first();
                    //     if(!$customerBilling && !empty($value['billing']['city'])){

                    //         $customerBilling = Billing::create([
                    //             "customer_id" => $customer->id,
                    //             "type" => 'billing',
                    //             "address1" => $value['billing']['address_1'],
                    //             "address2" => $value['billing']['address_2'],
                    //             "city" => $value['billing']['city'],
                    //             "state" => $value['billing']['state'],
                    //             "postcode" => $value['billing']['postcode'],
                    //             "country" => $value['billing']['country'],
                    //             "email" => ($value['billing']['email']) ? $value['billing']['email'] : null,
                    //             "phone" => $value['billing']['phone']
                    //         ]);
                    //         echo "<br><br>Customer ".$value['username']." Billing Added.<br><br>";
                    //     }else{
                    //         $customerBilling->save([
                    //             "address1" => $value['billing']['address_1'],
                    //             "address2" => $value['billing']['address_2'],
                    //             "city" => $value['billing']['city'],
                    //             "state" => $value['billing']['state'],
                    //             "postcode" => $value['billing']['postcode'],
                    //             "country" => $value['billing']['country'],
                    //             "email" => ($value['billing']['email']) ? $value['billing']['email'] : null,
                    //             "phone" => $value['billing']['phone'],
                    //             "updated_at" => Carbon::now()
                    //         ]);
                    //         echo "<br><br>Customer ".$value['username']." Billing Updated.<br><br>";
                    //     }
                    // }

                    // if(!empty($value['shipping']['city']) && isset($value['shipping']['city'])) {
                    //     $customerShipping = Shipping::where('customer_id', $customer->id)->where('type', 'shipping')->first();
                    //     if(!$customerShipping){
                    //         $customerShipping = Shipping::create([
                    //             "customer_id" => $customer->id,
                    //             "type" => 'shipping',
                    //             "address1" => $value['shipping']['address_1'],
                    //             "address2" => $value['shipping']['address_2'],
                    //             "city" => $value['shipping']['city'],
                    //             "state" => $value['shipping']['state'],
                    //             "postcode" => $value['shipping']['postcode'],
                    //             "country" => $value['shipping']['country']
                    //         ]);
                    //         echo "<br><br>Customer ".$value['username']." Shipping Added.<br><br>";
                    //     }else{
                    //         $customerShipping->save([
                    //             "address1" => $value['shipping']['address_1'],
                    //             "address2" => $value['shipping']['address_2'],
                    //             "city" => $value['shipping']['city'],
                    //             "state" => $value['shipping']['state'],
                    //             "postcode" => $value['shipping']['postcode'],
                    //             "country" => $value['shipping']['country'],
                    //             "updated_at" => Carbon::now()
                    //         ]);
                    //         echo "<br><br>Customer ".$value['username']." Shipping Updated.<br><br>";
                    //     }
                    // }
                    $total++;
                }


                if(count($result) < $perpage){
                    $sync = false;
                    break;
                }
            }
            echo "<center><h1>Done! Customers Syncing $errors</h1></center>";
        }catch(Exception $e) {
            $errors = '(With errors)';
            echo $e."<br><br><br>";
        }
    }

    public function search_customer(Request $request){
        $data = $request->all();
        $response = array();
        try{
            $id = $data['id'];

            $response = DB::select("SELECT cust.id,cust.username,cust.first_name,cust.last_name,cust.email,cust.phone, comp.name, comp.id as company_id FROM customers cust Left JOIN companies comp ON cust.company_id = comp.id WHERE cust.username LIKE '%$id%' OR CONCAT(cust.first_name, ' ', cust.last_name) LIKE '%$id%' OR cust.email LIKE '%$id%' OR cust.phone LIKE '%$id%' OR comp.name LIKE '%$id%' ");

            foreach($response as $rs){
                $rs->company = Company::where('id',$rs->company_id)->first()->name ?? "company not provided";
            }
            return response()->json($response);
        }catch(Exception $e){
            return response()->json($response);
        }
    }


    // update customer profile
    public function update_customer_profile(Request $request) {
        if($request->has('password')) {
            if(!empty($request->password)) {
                $request->validate([
                    'password' => 'required|min:8',
                ]);
            }
        }

        $woo_data = array(
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "phone" => $request->phone,
        );

        $wordpress = DB::Table("integrations")->where("slug","wordpress")->where('status', 1)->first();

        $customer = Customer::find($request->customer_id);

        $old_email = $customer->email;

        if($old_email != $request->email) {
            $request->validate([
                "email" => "required|email|unique:customers",
            ]);
        }

        // $mailer = new MailController();

        if($request->has('first_name')) {
            if(empty(trim($customer->first_name))) {
                response()->json([
                    'message' => 'Please enter valid first name!',
                    'status_code' => 500,
                    'success' => false
                ]);
            }
            $customer->first_name = $request->first_name;
        }
        if($request->has('last_name')) {
            if(empty(trim($customer->first_name))) {
                response()->json([
                    'message' => 'Please enter valid last name!',
                    'status_code' => 500,
                    'success' => false
                ]);
            }
            $customer->last_name = $request->last_name;
        }
        if($request->has('email')) $customer->email = $request->email;
        if($request->has('phone')) $customer->phone = $request->phone;
        if($request->has('phone_type')) $customer->phone_type = $request->phone_type;
        if($request->has('address')) $customer->address = $request->address;
        if($request->has('apt_address')) $customer->apt_address = $request->apt_address;

        if($request->has('company_id')) $customer->company_id = $request->company_id;
        if($request->has('cust_type')) $customer->cust_type = $request->cust_type;
        if($request->has('country')) $customer->country = $request->country;
        if($request->has('cust_state')) $customer->cust_state = $request->state;
        if($request->has('cust_city')) $customer->cust_city = $request->city;
        if($request->has('cust_zip')) $customer->cust_zip = $request->zip;

        if($request->has('bill_st_add')) $customer->bill_st_add = $request->bill_st_add;
        if($request->has('bill_apt_add')) $customer->bill_apt_add = $request->bill_apt_add;
        if($request->has('bill_add_country')) $customer->bill_add_country = $request->bill_add_country;
        if($request->has('bill_add_state')) $customer->bill_add_state = $request->bill_add_state;
        if($request->has('bill_add_city')) $customer->bill_add_city = $request->bill_add_city;
        if($request->has('bill_add_zip')) $customer->bill_add_zip = $request->bill_add_zip;
        if($request->has('is_bill_add')) $customer->is_bill_add = $request->is_bill_add;

        if($request->has('fb')) $customer->fb = $request->fb;
        if($request->has('twitter')) $customer->twitter = $request->twitter;
        if($request->has('insta')) $customer->insta = $request->insta;
        if($request->has('pinterest')) $customer->pinterest = $request->pinterest;
        if($request->has('linkedin')) $customer->linkedin = $request->linkedin;

        if($request->has('alt_phone')) $customer->alt_phone = $request->alt_phone;
        if($request->has('po')) $customer->po = $request->po;

        if($request->customer_login) {
            $customer->has_account = $request->customer_login;
        }

        if($request->pass_checkbox == 1) {

            if($request->password != $request->confirm_password) {
                return response()->json([
                    "code" => 500,
                    "success" => false,
                    "message" => 'Password not matached!',
                ]);
            }else{
                $data['password'] = Hash::make( $request->password  );
            }
        }

        $response = [
            'message' => 'Customer Detail Updated Successfully!',
            'status_code' => 200,
            'success' => true
        ];

        if(!empty($wordpress)) {
            if($wordpress->is_verified == 1) {
                if(!empty($wordpress->details)) {
                    $detail_values = explode(",",$wordpress->details);
                    $api = substr($detail_values[1], 1, -1);
                    $explode_key = explode(":",$api);
                    $url = $explode_key[1] .':'. $explode_key[2];
                    $api_url = trim(str_replace( '\/', '/', $url ), '"');

                    $ck_key = substr($detail_values[2], 1, -1);
                    $explode_key = explode(":",$ck_key);
                    $secret_key = trim($explode_key[1], '"');

                    $con_key = substr($detail_values[3], 1, -1);
                    $explode_key = explode(":",$con_key);
                    $consumer_key = trim($explode_key[1],'"');
                } else {
                    $response = [
                        "message" => 'Wordpress plugin not verified so we are updating record in our system only',
                        "status" => 201,
                        "success" => true,
                    ];
                }
            }
        }

        if($customer->save()) {
            $is_user = User::where("email", $old_email)->first();

            $pwd = Str::random(15);
            if($request->has('password')) {
                if(!empty($request->password)) {
                    $pwd = $request->password;
                }
            }

            if($is_user) {
                $data = ["email" => $request->email];

                // if($request->has('password')) {
                //     if(!empty($request->password) && $request->password != Crypt::decryptString($is_user->alt_pwd)) {
                //         $data["password"] = Hash::make($request->password);
                //         $data["alt_pwd"] = Crypt::encryptString($request->password);
                //     }
                // }

                if($request->pass_checkbox == 1) {

                    if($request->password != $request->confirm_password) {
                        return response()->json([
                            "code" => 500,
                            "success" => false,
                            "message" => 'Password not matached!',
                        ]);
                    }else{
                        $data['password'] = Hash::make( $request->password  );
                    }
                }

                DB::table("users")->where("email", $old_email)->update($data);

                // $mailer->UserRegisteration($request->email, false);
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

                        // $mailer->UserRegisteration($request->email);
                    }
                }
            }
        }

        return response()->json($response);
    }

    public function syncIntegrations() {
        $integrations = Integrations::where('slug', 'wordpress')->where('status', 1)->first();
        if(empty($integrations)) {
            echo 'Wordpress integration is not enabled';
            return false;
        }

        $perpage = 100;
        $count = ($integrations['page_count']) ? $integrations['page_count'] : 1;
        $sync = true;
        $total = 1;
        $errors = '';
        try {
            while($sync) {
                $result = json_decode(json_encode($this->woocommerce->get('customers?per_page='.$perpage.'&page='.$count)), true);

                if(!empty($result)) {
                    $count++;
                }

                foreach ($result as $key => $value) {

                    if( empty($value['first_name']) || empty($value['email']) ) {
                        continue;
                    }

                    $company = null;
                    if(!empty($value['billing']['company']) && isset($value['billing']['company'])){
                        $company = Company::where('name', $value['billing']['company'])->first();
                        if(!$company) {
                            $company = Company::create([
                                'poc_first_name' => $value['billing']['first_name'],
                                'poc_last_name' => $value['billing']['last_name'],
                                'name' => $value['billing']['company'],
                                'address' => $value['billing']['address_1'],
                                'cmp_bill_add' => $value['billing']['address_1'],
                                'cmp_ship_add' => $value['shipping']['address_1'],
                                'cmp_city' => $value['billing']['city'],
                                'cmp_zip' => $value['billing']['postcode'],
                                'cmp_country' => $value['billing']['country'],
                                'cmp_state' => $value['billing']['state'],
                                'email' => $value['billing']['email'],
                                'phone' => $value['billing']['phone'],
                            ]);
                        }else{
                            $company->save([
                                'poc_first_name' => $value['billing']['first_name'],
                                'poc_last_name' => $value['billing']['last_name'],
                                'name' => $value['billing']['company'],
                                'address' => $value['billing']['address_1'],
                                'cmp_bill_add' => $value['billing']['address_1'],
                                'cmp_ship_add' => $value['shipping']['address_1'],
                                'cmp_city' => $value['billing']['city'],
                                'cmp_zip' => $value['billing']['postcode'],
                                'cmp_country' => $value['billing']['country'],
                                'cmp_state' => $value['billing']['state'],
                                'email' => $value['billing']['email'],
                                'phone' => $value['billing']['phone'],
                            ]);
                        }
                        $company = $company->id;
                    }

                    $customer = Customer::where('woo_id', $value['id'])->first();
                    $cust_password = '';
                    if(!empty($company)) {
                        $cust_password = Hash::make('12345678');
                    }
                    if(!$customer) {
                        $customer = Customer::create([
                            "woo_id" => $value['id'],
                            "username" => $value['username'],
                            "first_name" => $value['first_name'],
                            "last_name" => $value['last_name'],
                            "email" => $value['email'],
                            "cust_password" => $cust_password,
                            "is_paying_customer" => $value['is_paying_customer'],
                            "avatar_url" => $value['avatar_url'],
                            "company_id" => $company,
                            "cust_type" => $value['cust_type'],
                        ]);
                    } else {
                        $customer->save([
                            "woo_id" => $value['id'],
                            "username" => $value['username'],
                            "first_name" => $value['first_name'],
                            "last_name" => $value['last_name'],
                            "email" => $value['email'],
                            "cust_password" => $cust_password,
                            "is_paying_customer" => $value['is_paying_customer'],
                            "avatar_url" => $value['avatar_url'],
                            "company_id" => $company,
                            "cust_type" => $value['cust_type'],
                            "updated_at" => Carbon::now()
                        ]);
                    }
                    $total++;
                }

                $integrations['page_count'] = $count;
                $integrations->save();
                if(count($result) < $perpage){
                    $sync = false;
                    break;
                }
            }
            echo "<center><h1>Done! Customers & Companies Syncing $errors</h1></center>";
        }catch(Exception $e) {
            $errors = '(With errors)';
            echo $e."<br><br><br>";
        }
    }


    // customer orders
    public function getAllCustomerOrders($id) {


        $orders = DB::Table("orders")->where("customer_id","=",$id)->get();

        foreach($orders as $order) {
            $order->order_by = DB::Table("customers")->where("id","=",$order->customer_id)->first();
        }
        $response['message'] = 'List Fetched.';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $orders;
        $response['date_format'] = Session('system_date');
        return response()->json($response);

    }

    public function getCustomerOrderItems($id) {

        $order_items = DB::Table("line_items")->where("order_id","=",$id)->get();

        $response['message'] = 'List Fetched.';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $order_items;
        return response()->json($response);

    }

    public function searchDomain(Request $request) {
        $namecheap = DB::Table("integrations")->where("slug","namecheap")->first();
        if($namecheap->is_verified == 1) {


            $detail_values = explode(",",$namecheap->details);

            $explode_key = explode(":",$detail_values[1]);
            $api_key = trim(str_replace( '\/', '/', $explode_key[1] ), '"');

            $explode_key = explode(":",$detail_values[2]);
            $username = trim(str_replace( '\/', '/', $explode_key[1] ), '"');

            $explode_key = explode(":",$detail_values[3]);
            $ip_add = trim(str_replace( '\/', '/', $explode_key[1] ), '"');

            if( $api_key != null && $api_key != ""
                && $username != null && $username != ""
                && $ip_add !=null && $ip_add != "" ) {

                $url = 'https://api.namecheap.com/xml.response?ApiUser='.$username.'&ApiKey='.$api_key.'&UserName='.$username.'&Command=namecheap.domains.check&ClientIp='.$ip_add.'&DomainList= '.$request->search_domain.' ';

                //Initialize cURL.
                $client = new \GuzzleHttp\Client();
                $resp = $client->request('GET', $url, ['verify' => false]);
                $xml = simplexml_load_string($resp->getBody(),'SimpleXMLElement',LIBXML_NOCDATA);
                $json = json_encode($xml);

                $array = json_decode($json, true);

                $domain_check = array();
                $collection = collect($array);

                array_push($domain_check , $collection['CommandResponse']['DomainCheckResult']['@attributes']['Domain'],$collection['CommandResponse']['DomainCheckResult']['@attributes']['Available']);

                return $domain_check;

            }else{
                return response()->json([
                    "message" =>  'Namecheap plugin is not verified',
                    "status" => 500,
                    "success" => false,
                ]);
            }

        }else{
            return response()->json([
                "message" =>  'Namecheap plugin is not verified',
                "status" => 500,
                "success" => false,
            ]);
        }
    }
}
