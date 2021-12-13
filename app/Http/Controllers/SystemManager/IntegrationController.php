<?php

namespace App\Http\Controllers\SystemManager;
use App\Http\Controllers\Controller;
use App\Models\IntegrationCategory;
use Illuminate\Support\Facades\Input;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use App\Models\Customer;
use App\Models\Company;
use App\Models\LineItem;
use App\Models\Integrations;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Tymon\JWTAuth\Claims\Custom;

class IntegrationController extends Controller
{
    protected $woocommerce;

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function integration(){
        
        $categories = IntegrationCategory::with('integrations')->get();
        return view('system_manager.integrations.index',compact('categories'));
    }

    public function save_details(Request $request){
        $details = ''; $response = array();

        try{
            $integration = Integrations::find($request->integration_id);
            // foreach ($request->except(['_token','integration_id']) as $key => $value) {
            //     $details .= $key.':'.$value.',';
            // }
            // $details=rtrim($details, ",");
            // $details .='}';
            $integration->details = json_encode($request->except(['_token','integration_id']));

            if($integration->save()){
                $response['message'] = 'Details Added Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
            }else{
                $response['message'] = 'Field to add Details!';
                $response['status_code'] = 500;
                $response['success'] = false;
            }
            
            return response()->json($response);
        }catch(Exception $err){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
        
    }


    public function integrationsVerify(Request $request){
        // return $request->all();
        
        if($request->name=='PayPal'){
            
            $provider = new PayPalClient;
            $config = [
                'mode'    => env('PAYPAL_MODE', $request->enviornment), // Can only be 'sandbox' Or 'live'. If empty or invalid, 'live' will be used.
                'sandbox' => [
                    'client_id'         => $request->client_id,
                    'client_secret'     =>$request->client_secret
                ],
                'live' => [
                    'client_id'         => $request->client_id,
                    'client_secret'     =>$request->client_secret
                ],
                'payment_action' => env('PAYPAL_PAYMENT_ACTION', 'Sale'),
                'currency'       => env('PAYPAL_CURRENCY', 'USD'),
                'locale'         => env('PAYPAL_LOCALE', 'en_US'),
                'notify_url'     => env('PAYPAL_NOTIFY_URL', ''),
                'validate_ssl'   => env('PAYPAL_VALIDATE_SSL', false)];
            $provider->setApiCredentials($config );
            $response= $provider->getAccessToken();
            if($response['access_token']){
                Integrations::where('name','PayPal')->update(['is_verified'=>1]);
                $response['message'] = 'Verified Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json( $response);
            }else{

                $response['message'] = 'Field to Verify!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json( $response);
            }

    
        }
        if($request->name=='NameCheap'){

            $url = 'https://api.sandbox.namecheap.com/xml.response?ApiUser='.$request->username.'&ApiKey='.$request->api_key.'&UserName='.$request->username.'&Command=namecheap.domains.check&&ClientIp='.$request->ip.'&DomainList=ali.com';

            //Initialize cURL.
            $client = new \GuzzleHttp\Client();
            $resp = $client->request('GET', $url, ['verify' => false]);
            $xml = simplexml_load_string($resp->getBody(),'SimpleXMLElement',LIBXML_NOCDATA);
            $json = json_encode($xml);

            // array
            $array = json_decode($json, true);

            // collection
            $collection = collect($array);

            if($collection['@attributes']['Status'] == "ERROR") {
                $response['message'] = $collection['Errors']['Error'];
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json( $response);
            }else{
                Integrations::where('name','NameCheap')->update(['is_verified'=>1]);
                $response['message'] = 'Verified Successfully';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json( $response);
            }    
        }
        if($request->name=='NMI Payment Gateway'){
            
            $postStr='security_key='.$request->security_key.'&report_type=test_mode_status';
            $url="https://secure.merchantonegateway.com/api/query.php?". $postStr;
            //Initialize cURL.
            $client = new \GuzzleHttp\Client();
            $resp = $client->request('GET', $url, ['verify' => false]);
            
             
            if($resp->getStatusCode() ==200 && $resp->getBody()->getContents()!=''){
                Integrations::where('name','NMI Payment Gateway')->update(['is_verified'=>1]);
                $response['message'] = 'Verified Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
            }
            else{
                $response['message'] = 'Field to Verify!';
                $response['status_code'] = 500;
                $response['success'] = false;
            }

        }
        if($request->name=="Google Api's"){
            Integrations::where('name',"Google Api's")->update(['is_verified'=>1]);
            $response['message'] = 'Verified Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json( $response);            
        }

        if($request->name == "WordPress") {

            $array = array(
                "version" => "wc/v3",
                "verify_ssl" => false,
            );

            $woocommerce = new Client( 
                $request->api_url,
                $request->consumer_key,
                $request->consumer_secret,
                $array
            );

            $results = $woocommerce->get('customers');
            
            if($results) {
                Integrations::where('slug','wordPress')->update(['is_verified' => 1]);
                $response['message'] = 'Verified Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json( $response);  
            }else{
                $response['message'] = 'Verified Failed!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json( $response); 
            }
        }
        
    }


    public function getWPCustomers(Request $request) {

        if($request->page_index == 1) {
            $integration = Integrations::find($request->integration_id);

            if($integration->is_verified == 1) {
                $integration->details = json_encode($request->except(['_token','integration_id']));
                $integration->save();
    
                $woocommerce = new Client(
                    $request->api_url, 
                    $request->consumer_key, 
                    $request->consumer_secret,
                    [
                        'version' => 'wc/v3',
                        'verify_ssl' => false
                    ]
                );

                $perpage = 100;
                $customer_counts = $request->page_index;
                $is_finished = false;

                while($customer_counts <= 5) {
    
                    $customers = $woocommerce->get('customers?per_page='.$perpage.'&page='.$customer_counts);
                    $customer_counts++;
                  
                    // customers  
                    foreach($customers as $key => $value)  {
                        $is_paying_customer = $value->is_paying_customer == true ? 1 : 0;
                        if($value->billing->company != " " && $value->billing->company != null ) {
                            Company::firstOrCreate([
                                "woo_id" => $value->id,
                                'poc_first_name' => $value->billing->first_name,
                                'poc_last_name' => $value->billing->last_name,
                                'name' => $value->billing->company,
                                'address' => $value->billing->address_1,
                                'cmp_bill_add' => $value->billing->address_1,
                                'cmp_ship_add' => $value->shipping->address_1,
                                'cmp_city' => $value->billing->city,
                                'bill_add_zip' => $value->billing->postcode,
                                'cmp_country' => $value->billing->country,
                                'cmp_state' => $value->billing->state,
                                'email' => $value->billing->email,
                                'phone' => $value->billing->phone,
                            ]);
                        }
    
                        if(empty(Customer::where('email', $value->email)->where('is_deleted', 0)->first())) {
                            Customer::firstOrCreate([
                                "woo_id" => $value->id,
                                "email" => $value->email,
                                "first_name" => $value->first_name,
                                "last_name" => $value->last_name,
                                "avatar_url" => $value->avatar_url,
                                "is_paying_customer" => $is_paying_customer,
                                "username" => $value->email,
                                "address" => $value->billing->address_1,
                                "apt_address" => $value->billing->address_2,
                                "cust_city" => $value->billing->city,
                                "country" => $value->billing->country,
                                "phone" => $value->billing->phone,
                                "cust_zip" => $value->billing->postcode,
                                "cust_state" => $value->billing->state,
                            ]);
                        }
    
                        // $companies= Company::where('is_deleted',0)->get();
                        // if($companies->count() > 0) {
                        //     foreach($companies as $company) {
                        //         DB::table("customers")->where('email', $company->email)->update([
                        //             "company_id" => $company->id,
                        //         ]);
                        //     }
                        // } 
                        
                    }
    
                    if(count($customers) < $perpage){
                        $is_finished = true;
                        break;
                    }
                }

                if($is_finished == true) {
                    $response['message'] = 'Customer Fetched Successfully!';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    $response['is_finished'] = true;
                }else{
                    // $response['message'] = 'Fetching Customers Please wait!';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    $response['page_index'] = $customer_counts;
                    $response['is_finished'] = false;
                }
    
                return response()->json( $response); 
            }else{
                $response['message'] = 'Verfication is not done!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json( $response); 
            }
        }else{

            $woocommerce = new Client(
                $request->api_url, 
                $request->consumer_key, 
                $request->consumer_secret,
                [
                    'version' => 'wc/v3',
                    'verify_ssl' => false
                ]
            );

            $perpage = 100;
            $customer_counts = 1;
            $is_finished = false;
            $pageIndex = $request->page_index;

            while($customer_counts <= 5) {

                $customers = $woocommerce->get('customers?per_page='.$perpage.'&page='.$pageIndex);
                $customer_counts ++;
                $pageIndex ++;
              
                // customers  
                foreach($customers as $key => $value)  {
                    $is_paying_customer = $value->is_paying_customer == true ? 1 : 0;
                    if($value->billing->company != " " && $value->billing->company != null ) {
                        Company::firstOrCreate([
                                "woo_id" => $value->id,
                                'poc_first_name' => $value->billing->first_name,
                                'poc_last_name' => $value->billing->last_name,
                                'name' => $value->billing->company,
                                'address' => $value->billing->address_1,
                                'cmp_bill_add' => $value->billing->address_1,
                                'cmp_ship_add' => $value->shipping->address_1,
                                'cmp_city' => $value->billing->city,
                                'bill_add_zip' => $value->billing->postcode,
                                'cmp_country' => $value->billing->country,
                                'cmp_state' => $value->billing->state,
                                'email' => $value->billing->email,
                                'phone' => $value->billing->phone,
                            ]);
                    }

                    Customer::firstOrCreate([
                        "woo_id" => $value->id,
                        "email" => $value->email,
                        "first_name" => $value->first_name,
                        "last_name" => $value->last_name,
                        "avatar_url" => $value->avatar_url,
                        "is_paying_customer" => $is_paying_customer,
                        "username" => $value->email,
                        "address" => $value->billing->address_1,
                        "apt_address" => $value->billing->address_2,
                        "cust_city" => $value->billing->city,
                        "country" => $value->billing->country,
                        "phone" => $value->billing->phone,
                        "cust_zip" => $value->billing->postcode,
                        "cust_state" => $value->billing->state,
                    ]);

                    // $companies= Company::where('is_deleted',0)->get();
                    // if($companies->count() > 0) {
                    //     foreach($companies as $company) {
                    //         DB::table("customers")->where('email', $company->email)->update([
                    //             "company_id" => $company->id,
                    //         ]);
                    //     }
                    // } 
                    
                }

                if(count($customers) < $perpage){
                    $is_finished = true;
                    break;
                }
            }

            if($is_finished == true) {
                // $response['message'] = 'Customer Fetched Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['is_finished'] = true;
            }else{
                // $response['message'] = 'Fetching Customers Please wait!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['page_index'] = $pageIndex;
                $response['is_finished'] = false;
            }

            return response()->json( $response); 
        }

            
            
       

    }

    public function getWPOrders(Request $request) {

        if($request->page_index == 1) {
            $woocommerce = new Client(
                $request->api_url, 
                $request->consumer_key, 
                $request->consumer_secret,
                [
                    'version' => 'wc/v3',
                    'verify_ssl' => false
                ]
            );
    
            $perpage = 100;
            $order_count = $request->page_index;
            $is_finished = false;

            
            // order_count should be 50 on live
            while($order_count <= 20) {
    
                $orders = $woocommerce->get('orders?per_page='.$perpage.'&page='.$order_count);
                $order_count ++;
                
                foreach ($orders as $key => $value) {
                    Orders::firstOrCreate([
                        "woo_id" => $value->id,
                        "custom_id" => $value->id,
                        "status_text" => $value->status,
                        "customer_woo_id" => $value->customer_id,
                    ]);
                    for($t = 0; $t < sizeof($value->line_items); $t++) {
                        $lineitems = $value->line_items[$t];
                        LineItem::firstOrCreate([
                            "woo_order_id" => $value->id,
                            "order_id" => $value->id,
                            "name" => $lineitems->name,
                            "quantity" => $lineitems->quantity,
                            "price" => $lineitems->price,
                            "subtotal" => $lineitems->subtotal,
                            "subtotal_tax" => $lineitems->subtotal_tax,
                            "total" => $lineitems->total,
                            "total_tax" => $lineitems->total_tax,
                        ]);
                    }
                }
    
                if(count($orders) < $perpage){
                    $is_finished = true;
                    break;
                }
            }
    
            if($is_finished == true) {
                $response['message'] = 'Data Fetched Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['is_finished'] = true;
            }else{
                // $response['message'] = 'Orders Fetching Please wait!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['page_index'] = $order_count;
                $response['is_finished'] = false;
            }
    
            return response()->json( $response); 
        }else{
            
            $woocommerce = new Client(
                $request->api_url, 
                $request->consumer_key, 
                $request->consumer_secret,
                [
                    'version' => 'wc/v3',
                    'verify_ssl' => false
                ]
            );
    
            $perpage = 100;
            $order_count = 1;
            $is_finished = false;
            $pageIndex = $request->page_index;
            
            // order_count should be 50 on live
            while($order_count <= 20) {
    
                $orders = $woocommerce->get('orders?per_page='.$perpage.'&page='.$pageIndex);
                $order_count ++;
                $pageIndex ++;
                
                foreach ($orders as $key => $value) {
                    Orders::firstOrCreate([
                        "woo_id" => $value->id,
                        "custom_id" => $value->id,
                        "status_text" => $value->status,
                        "customer_woo_id" => $value->customer_id,
                    ]);
                    for($t = 0; $t < sizeof($value->line_items); $t++) {
                        $lineitems = $value->line_items[$t];
                        LineItem::firstOrCreate([
                            "woo_order_id" => $lineitems->id,
                            "order_id" => $lineitems->id,
                            "name" => $lineitems->name,
                            "quantity" => $lineitems->quantity,
                            "price" => $lineitems->price,
                            "subtotal" => $lineitems->subtotal,
                            "subtotal_tax" => $lineitems->subtotal_tax,
                            "total" => $lineitems->total,
                            "total_tax" => $lineitems->total_tax,
                        ]);
                    }
                }
    
                if(count($orders) < $perpage){
                    $is_finished = true;
                    break;
                }
            }
    
            if($is_finished == true) {
                $response['message'] = 'Data Fetched Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['is_finished'] = true;
            }else{
                // $response['message'] = 'Orders Fetching Please wait!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['page_index'] = $pageIndex;
                $response['is_finished'] = false;
            }
    
            return response()->json( $response); 
        }
        
    }

    public function integrations_status(Request $request) {
        /******
        @params {
            id      ====>  record Id to update status
            status  ====>  status valued enabled|disabled
        }
        ********/

        $response = array();
        $data = $request->all();

        try{
            $integration = Integrations::find($data['id']);
            if( $integration->name == 'PayPal'){
                $details =json_decode($integration->details,true);
                if($details['client_id'] =='' || $details['secret_key'] ==''){
                    $response['message'] = 'Failed To Get Details For verify Credentials';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
            }
            if( $integration->name == 'NMI Payment Gateway'){

                $details =json_decode($integration->details,true);
                if($details['tokenization_key'] =='' || $details['security_key'] ==''){
                    $response['message'] = 'Failed To Get Details For verify Credentials';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
            }
            // return $integration;
            if( $integration->is_verified ==1){
                $integration->status = $data['status'];
                if($integration->save()){
                    $response['message'] = ' Successfully Updated !';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                }else{
                    $response['message'] = 'Field to '.strtoupper($data['status']).' status!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                }
            }else{
                $response['message'] = 'Verify Before Enablied this Integration';
                $response['status_code'] = 500;
                $response['success'] = false;
            }
            
            return response()->json($response);
        }catch(Exception $err){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
}