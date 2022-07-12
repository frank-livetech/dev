<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RFQ;
use App\Models\Tags;
use App\Models\Vendors;
use App\Models\Orders;
use App\Models\VendorProfile;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Country;
use App\Models\States;
use App\Models\SystemSetting;
use Validator;
use App\User;
use App\Models\StaffProfile;
use App\Http\Controllers\SystemManager\MailController;
use App\Http\Controllers\GeneralController;
use App\Models\Activitylog;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;
use Dompdf\Dompdf;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
// use Yajra\Datatables\Datatables;

class BillingController extends Controller
{

    public static $connection = '{mylive-tech.com:995/pop3/ssl}';
    public static $mailserver_hostname = 'mylive-tech.com';
    public static $mailserver_username = 'bills@mylive-tech.com';
    public static $mailserver_password = 'y7.v9jLy!JLG9!s';


    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }
    public function rfq(){

        $tags = Tags::all();
        $categories = Category::all();
        $companies = Company::all();

        $setting_notes = SystemSetting::where('sys_key','sell_inst_note')->first();

        return view('billing.rfq.index-new',compact('tags','categories','companies','setting_notes'));
    }
    // public function rfqNew(){

    //     $tags = Tags::all();
    //     $categories = Category::all();
    //     $companies = Company::all();

    //     $setting_notes = SystemSetting::where('sys_key','sell_inst_note')->first();

    //     return view('billing.rfq.index-new',compact('tags','categories','companies','setting_notes'));
    // }
    public function vendorsProfile($id){
        $tags = Tags::all();
        $categories = Category::all();
        $companies = Company::all();
        $countries =  Country::all();
        $states =  States::all();

        // $company = DB::table('companies')->where('deleted_at','=',null)->get();
        $vendor = DB::table('vendors')->where('id','=',$id)->first();
        return view('billing.rfq.vendor_profile_new',compact('vendor','tags','categories','companies','countries','states'));
    }

    public function invoice_maker($id='') {
        $google = '';
        $gooogle = 0;
        $google_api = DB::Table("integrations")->where("slug", "=", "google-api")->where('status', 1)->first();
        if(!empty($google_api)) {
            if(!empty($google_api->details)) {
                $google = json_decode($google_api->details, true);

                $detail_values = explode(",", $google_api->details);
                $api = substr($detail_values[1], 1, -1);
                $explode_key = explode(":", $api);
                $key = substr($explode_key[1], 1, -1);

                if(!empty($key)) $gooogle = 1;
            }
        }

        $countries = [];
        if($gooogle === 0) $countries = DB::Table('countries')->get();

        // $sys_setting = SystemSetting::where("sys_key","bill_order_id_frmt")->first();
        $sys_setting = SystemSetting::get();
        $id_format = '';
        $curr_format = '';
        $inv_format = '';
        $inv_format_size = 0;
        foreach ($sys_setting as $key => $value) {
            if($value['sys_key'] == 'bill_order_id_frmt') {
                $id_format = $value['sys_value'];
            } else if($value['sys_key'] == 'currency_format') {
                $curr_format = $value['sys_value'];
            } else if($value['sys_key'] == 'order_invoice_format') {
                $inv_format_size = strlen($value['sys_value']);
                $inv_format = str_replace('x', 9, strtolower($value['sys_value']));
            }
        }

        $order = '';

        // if($sys_setting) {
            if($id_format == 'random') {
                // $order = (object) array('id' => mt_rand(0,999999));
                $order = (object) array('id' => mt_rand(0, intval($inv_format)));
            }else{
                $order = DB::table('orders')->orderBy('id','desc')->select('id')->first();
                if(!empty($order)) $order->id += 1;
            }
        // }else{
        //     $order = DB::table('orders')->orderBy('id','desc')->select('id')->first();
        // }



        if(!empty($order)) $order->id = sprintf("%0".$inv_format_size."d", $order->id);

        $customers = DB::table("customers")->select('id','first_name','last_name')->where("is_deleted","=",0)->get();
        $billing_statuses = DB::table("billing_statuses")->get();

        $currency_symbol = '';
        $curr_symbol = SystemSetting::where("sys_key","currency_format")->first();

        if($curr_symbol) {
            $currency_symbol = $curr_symbol->sys_value;
        }else{
            $currency_symbol = "<i class='fas fa-dollar-sign'></i>";
        }

        if(!empty($id)) {
            $order = DB::table('orders')->where('custom_id', $id)->first();
            $customer_info = DB::table("customers")->where('id', $order->customer_id)->first();
            $order_line_items = DB::table("line_items")->where("order_id", $order->custom_id)->get();

            return view('billing.invoice_maker.index_new',compact('customers','google','gooogle','billing_statuses','countries','order','id','customer_info','order_line_items','currency_symbol'));
        } else {
            $order_line_items = '';
            $customer_info = '';
            return view('billing.invoice_maker.index_new',compact('customers','google','gooogle','billing_statuses','countries','id','order','customer_info','order_line_items','currency_symbol'));
        }
    }

    public function createPDFInvoice($id) {

        $order = Orders::where("custom_id",$id)->first();

        $customer = null;
        if(!empty($order)) {
            $customer = Customer::where('id', $order["customer_id"])->first();
        }
        $line_items = null;
        if(!empty($order)) {
            $line_items = DB::table("line_items")->where('order_id', $order["custom_id"])->get();
        }

        $notes = null;
        if(!empty($order)) {
            $notes = Orders::where('custom_id',$id)->select("ord_notes")->first();
        }

        $billing_template = DB::table('templates')->where('code','=','billing_invoice')->first();

        if(empty($billing_template)) {
            throw new Exception('Template not found');
        }


        $order_input = array(
            array('module' => 'Customer', 'values' => $customer->toArray()),
            array('module' => 'Item-Name', 'values' => $line_items->toArray()),
            array('module' => 'Notes', 'values' => $notes->toArray()),
        );

        $parse = $this->order_parser($order_input, $billing_template->template_html , $id);

        $dompdf = new Dompdf(array('enable_remote' => true));
        $dompdf->loadHtml($parse);
        $dompdf->set_option('isRemoteEnabled',TRUE);
        $dompdf->setPaper('Letter', 'portrait');
        $dompdf->render();
        $dompdf->stream("invoice_".$id.".pdf");

    }

    public function order_parser($data_list, $template , $id) {
        if(empty($template)) {
            throw new Exception('Template is empty!');
        }

        if(empty($data_list)) {
            throw new Exception('Provided data list is empty!');
        }

        $template = htmlentities($template);

        if(str_contains($template, '{Customer-First-Name}')) {
            $content = DB::table('templates')->where('code', 'billing_invoice')->first();

            if(!empty($content)) {
                $content = $content->template_html;
                $this->replaceShortCodes($data_list, $content,$id);
                // $content = str_replace('{Customer-First-Name}', 'Muzammil', $content);
            }
        }

        if(str_contains($template, '{Items-Row}')) {
            $content = DB::table('templates')->where('code', 'billing_invoice')->first();

            if(!empty($content)) {
                $content = $content->template_html;
                $this->replaceShortCodes($data_list, $content, $id);
            }
        }

        if(str_contains($template, '{Invoice-Notes}')) {
            $content = DB::table('templates')->where('code', 'billing_invoice')->first();

            if(!empty($content)) {
                $content = $content->template_html;
                $this->replaceShortCodes($data_list, $content, $id);
            }
        }

        $this->replaceShortCodes($data_list, $template, $id);
        $sc_vars = DB::table('sc_variables')->get();

        foreach ($sc_vars as $key => $value) {

            if(str_contains($template, $value->code)) {
                $template = str_replace($value->code," ", $template);
            }
        }
        return html_entity_decode($template);

    }


    public function replaceShortCodes($data_list, &$template, $id) {

        foreach ($data_list as $key => $data) {

            if($data['module'] == 'Customer' && str_contains($template, '{Customer-First-Name}')) {
                $template = str_replace('{Customer-First-Name}', $data['values']['first_name'], $template);
            }
            if($data['module'] == 'Customer' && str_contains($template, '{Customer-Address}')) {
                $full_address = $data['values']['address'] . ', ' . $data['values']['apt_address'] . ', ' . $data['values']['cust_city'] . ', ' . $data['values']['cust_state'] . ', ' .  $data['values']['cust_zip'] . ', ' .  $data['values']['country'];
                $template = str_replace('{Customer-Address}', $full_address, $template);
            }

            if($data['module'] == 'Notes' && str_contains($template, '{Order-Notes}')) {
                $template = str_replace('{Order-Notes}', $data['values']['ord_notes'], $template);

            }

            if($data['module'] == 'Item-Name' && str_contains($template, '{Items-Row}')) {

                $order = Orders::where("custom_id",$id)->first();
                $brand_setting = DB::table("brand_settings")->first();
                // $img = '<img src="'.URL::to('/').'/files/brand_files/'.$brand_setting->site_logo .'" width="80" height="80"/>';
                $img = '<img src="'.GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/').'/public/files/brand_files/'.$brand_setting->site_logo .'" width="80" height="80"/>';

                $html_rows = '';

                for($i = 0; $i < sizeOf($data['values']); $i++ ) {

                    $html_rows .='
                        <tr>
                            <td>'.$data['values'][$i]->name.'</td>
                            <td> '.$data['values'][$i]->quantity .' </td>
                            <td>'. $data['values'][$i]->price.'</td>
                        </tr>
                    ';
                }


                $template = str_replace('{Company-Logo}',$img, $template);
                $template = str_replace('{Items-Row}',$html_rows, $template);
                $template = str_replace('{Order-ID}',$id, $template);

                $template = str_replace('{Order-Date}',date_format($order->created_at,"Y/m/d"), $template);

                $template = str_replace('{Item-SubTotal}', $order->total, $template);
                $template = str_replace('{Order-Fees}', $order->fees, $template);
                $template = str_replace('{Order-tax}', $order->tax, $template);
                $template = str_replace('{Order-Discount}', $order->discount, $template);

                $template = str_replace('{Item-Total}', $order->grand_total, $template);

                $thankyou_msg = "Thank You For Your Business!";
                $template = str_replace('{Thank You}', $thankyou_msg, $template);

            }
            if(!is_array($data['values'])) $data['values'] = (array) $data['values'];

            foreach ($data['values'] as $key => $value) {
                $k = str_replace('_', ' ', $key);
                $k = ucwords($k);
                $k = str_replace(' ', '-', $k);

                if(!is_array($value) && !is_object($value)) {
                    $template = str_replace('{'.$data['module'].'-'.$k.'}', $value, $template);
                }
            }
        }
    }


    public function BillingOrderIdFormat(Request $request) {
        $response = array();
        try {
            $data = $request->all();

            foreach ($data as $key => $value) {
                $set = SystemSetting::where("sys_key", $key)->first();
                if(!empty($set)) {
                    if($set->sys_value != $value) {
                        $set->sys_value = $value;
                        $set->save();
                    }
                } else {
                    SystemSetting::create([
                        "sys_key" => $key,
                        "sys_value" => $value
                    ]);
                }
            }

            $response['message'] = 'Order Settings Updated Successfully!';
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

    public function SaveModeForm(Request $request) {
        $data = $request->all();
        // return $data;
        $response = array();
        // return $response;
        $vis_sys = DB::table('visual_settings')->where('created_by',\Auth::user()->id)->where('mode',$request->mode)->get();
        // return $vis_sys;
        if($vis_sys){
            DB::table('visual_settings')->where('created_by',\Auth::user()->id)->where('mode',$request->mode)->delete();
            foreach($data as $key => $value){
                DB::table('visual_settings')->insert([
                    "vs_key" => $key,
                    "vs_value" => $value,
                    "created_by" => \Auth::user()->id,
                    "mode" => $request->mode,
                ]);

            }
            $response['message'] = 'System Setting Update Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }
        else{
            foreach($data as $key => $value){
                DB::table('visual_settings')->insert([
                    "vs_key" => $key,
                    "vs_value" => $value,
                    "created_by" => \Auth::user()->id,
                    "mode" => $request->mode,
                ]);

            }
            $response['message'] = 'System Setting Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }



    }

    public function billing_home(Request $request){


        if($request->odr_id != 0) {

            DB::Table("orders")->where("id","=",$request->odr_id)->update([
                "is_published" => 1,
            ]);

            $response['message'] = 'Order Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }else{

            if($request->order_id == null || $request->order_id == '') {
                $order = new Orders();
                $order->status =  $request->status;
                $order->status_text = $request->status_text;
                $order->customer_id =  $request->customer_id;

                if($order->save()) {
                    foreach($request->name as $key=>$name) {
                        DB::table("line_items")->insert([
                            "order_id" => $order->id,
                            "name" => $name,
                            "quantity" => $request->quantity[$key],
                            "price" => $request->price[$key],
                            "subtotal" => $request->subtotal,
                            "total" => $request->total,
                        ]);
                    }
                }

                $response['message'] = 'Order Saved Successfully';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }else{

                DB::Table("orders")->where("id","=",$request->order_id)->update([
                    "is_published" => 1,
                ]);

                $response['message'] = 'Order Published Successfully';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
        }



    }

    public function billingHomePage() {

        $date_format = Session('system_date');
        $all_order = Orders::all()->count();
        $completed_order = Orders::where("status_text","Completed")->count();
        $pending_payment_order = Orders::where("status_text","Pending Payment")->count();
        $processing_order = Orders::where("status_text","Processing")->count();
        return view('billing.home', compact('date_format','completed_order','pending_payment_order','all_order','processing_order'));
    }
    public function billingHomePageNew() {

        $date_format = Session('system_date');
        $all_order = Orders::all()->count();
        $completed_order = Orders::where("status_text","Completed")->count();
        $pending_payment_order = Orders::where("status_text","Pending Payment")->count();
        $processing_order = Orders::where("status_text","Processing")->count();
        return view('billing.home-new', compact('date_format','completed_order','pending_payment_order','all_order','processing_order'));
    }

    public function get_all_orders(Request $request) {
        $orders  = DB::table('orders')->orderBy('id', 'desc')->get();
        foreach($orders as $order) {
            $order->customer = DB::table('customers')->where('id',"=",$order->customer_id)->first();
            $order->lineItem = DB::table('line_items')->where('order_id',"=",$order->custom_id)->first();
        }

        // if ($request->ajax()) {
        //     return Datatables::of($orders)->make(true);
        // }

        $response['message'] = 'Orders List';
        $response['data'] = $orders;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }


    public function get_all_subs(Request $request) {
        $subscriptions  = DB::table('subscriptions')->orderBy('id', 'desc')->get();
        foreach($subscriptions as $sub) {
            $sub->customer = DB::table('customers')->where('id',"=",$sub->customer_id)->first();
            $sub->lineItem = DB::table('line_items')->where('subscription_id',"=",$sub->id)->first();
        }

        $response['message'] = 'Subscriptions List';
        $response['data'] = $subscriptions;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }


    public function createInvoice(Request $request) {

        if($request->order_id == null || $request->order_id == '') {
            $order = new Orders();
            $order->status =  $request->status;
            $order->status_text = $request->status_text;
            $order->customer_id =  $request->customer_id;
            $order->custom_id =  $request->custom_id;
            $order->ord_notes = $request->notes;
            $order->fees = $request->fees;
            $order->discount = $request->discount;
            $order->tax = $request->tax;
            $order->total = $request->sub_total;
            $order->grand_total = $request->total;

            if($order->save()) {
                foreach($request->name as $key=>$name) {
                    DB::table("line_items")->insert([
                        "order_id" => $request->custom_id,
                        "name" => $name,
                        "quantity" => $request->quantity[$key],
                        "price" => $request->price[$key],
                        "item_details" => $request->details[$key],
                        "routine" => $request->routine[$key],
                        "subscription_cost" => $request->subscription_cost[$key],
                        "item_end_date" => $request->end_date[$key],
                    ]);
                }
            }

            $response['message'] = 'Order Booked Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['order_id'] = $order->custom_id;
            return response()->json($response);

        }else{


            DB::Table("orders")->where("id","=",$request->order_id)->update([
                "status" =>  $request->status,
                "status_text" => $request->status_text,
                "customer_id" =>  $request->customer_id,
                "fees" => $request->fees,
                "discount" => $request->discount,
                "tax" => $request->tax,
                "total" => $request->sub_total,
                "grand_total" => $request->total,
            ]);

            DB::table("line_items")->where("order_id","=", $request->order_id)->delete();

            foreach($request->name as $key=>$name) {
                DB::table("line_items")->insert([
                    "order_id" => $request->order_id,
                    "name" => $name,
                    "quantity" => $request->quantity[$key],
                    "price" => $request->price[$key],
                    "item_details" => $request->details[$key],
                    "routine" => $request->routine[$key],
                    "subscription_cost" => $request->subscription_cost[$key],
                    "item_end_date" => $request->end_date[$key],
                ]);
            }

            $response['message'] = 'Order Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }
    }

    public function updateOrder($id,Request $request) {

        DB::table("orders")->where("custom_id","=",$id)->update([
            "status" => $request->status,
            "status_text" => $request->status_text,
            "ord_notes" => $request->notes,
            "fees" => $request->fees,
            "discount" => $request->discount,
            "tax" => $request->tax,
            "total" => $request->sub_total,
            "grand_total" => $request->total,
        ]);

        DB::table("line_items")->where("order_id","=",$id)->delete();

        foreach($request->name as $key=>$name) {
            DB::table("line_items")->insert([
                "order_id" => $id,
                "name" => $name,
                "quantity" => $request->quantity[$key],
                "price" => $request->price[$key],
                "item_details" => $request->details[$key],
                "routine" => $request->routine[$key],
                "subscription_cost" => $request->subscription_cost[$key],
                "item_end_date" => $request->end_date[$key],
            ]);
        }

        $response['message'] = 'Order Updated Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }


    public function getCustomerById($id) {

        $customers = Customer::where('id',$id)->first();
        $states = DB::table("states")->where("id",$customers->cust_state)->first();
        $country = DB::table("countries")->where("id",$customers->country)->first();

        $response['message'] = 'Customer List';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $customers;
        $response['state'] = $states;
        $response['country'] = $country;
        return response()->json($response);
    }

    public function updateCustomerById(Request $request) {

        $customer = Customer::find($request->id);
        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        if($request->address_type == 'billing') {
            $customer->bill_add_country = $request->country;
            $customer->bill_add_zip = $request->cust_zip;
            $customer->bill_add_state = $request->cust_state;
            $customer->bill_add_city = $request->cust_city;
            $customer->bill_apt_add = $request->apt_address;
            $customer->bill_st_add = $request->address;
            $customer->is_bill_add = 1;
            $customer->save();
        }else{
            $customer->country = $request->country;
            $customer->cust_zip = $request->cust_zip;
            $customer->cust_state = $request->cust_state;
            $customer->cust_city = $request->cust_city;
            $customer->apt_address = $request->apt_address;
            $customer->address = $request->address;
            $customer->save();
        }
        // if($request->address_type == 'billing') {
        //     $customer = Customer::find($request->id);
        //     $customer->bill_add_country = $request->country;
        //     $customer->bill_add_zip = $request->cust_zip;
        //     $customer->bill_add_state = $request->cust_state;
        //     $customer->bill_add_city = $request->cust_city;
        //     $customer->bill_apt_add = $request->apt_address;
        //     $customer->bill_st_add = $request->address;
        //     $customer->save();
        // }else{
        //     $customer = Customer::find($request->id);
        //     $customer->shipping_add_country = $request->country;
        //     $customer->shipping_add_zip = $request->cust_zip;
        //     $customer->shipping_add_state = $request->cust_state;
        //     $customer->shipping_add_city = $request->cust_city;
        //     $customer->shipping_apt_add = $request->apt_address;
        //     $customer->shipping_st_add = $request->address;
        //     $customer->save();
        // }

        $response['message'] = 'Customer Detail Updated Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['type'] = $request->address_type;
        return response()->json($response);
    }

    public function reports(){

        $staffs = User::where('user_type','!=',5)->where('is_deleted',0)->get();

        return view('billing.reports.index', get_defined_vars());
    }

    public function getActivityLogReport(Request $request) {
        try {

            if($request->has('filter') && $request->filter == 'today' || $request->has('filter') && $request->filter == null) {
                $logs =  Activitylog::with(['ticket','createdBy','updatedBy'])->where('module', 'Tickets')
                ->whereDate('created_at', Carbon::today());
            }
            if($request->has('filter') && $request->filter == 'date_range') {
                $logs =  Activitylog::with(['ticket','createdBy','updatedBy'])->where('module', 'Tickets');

                if($request->staff != null){
                    $logs->where('created_by',$request->staff);
                }

                if($request->start_date != 'Invalid date' && $request->end_date != 'Invalid date'){
                    $logs->whereBetween('created_at', [$request->start_date,$request->end_date]);
                }

            }

            if($request->has('filter') && $request->filter == 'date_range' && $request->staff != null) {

            }


            $data = $logs->orderByDesc('id')->limit(150)->get();



            $response['status_code'] = 200;
            $response['success'] = true;
            $response['logs']= $data;
            return response()->json($response);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function saveRFQRquests(Request $request){

        $data = $request->all();
        //return $request;
        $response = array();
        try{

            $data['created_by'] = \Auth::user()->id;
            $rfq_table = RFQ::create($data);

            if($data['to_mails'] != ''){
                $this->mailtoTempalte($data);
            }
            if(!empty($request->contacts)){
                $contacts_Arr = explode(',',$request->contacts);
               // return $contacts_Arr;
                for($k = 0 ; $k<sizeof($contacts_Arr);$k++){
                    $vendor = Vendors::where('id',$contacts_Arr[$k])->first();
                    $this->sendMailToVendor($data,$vendor->email);

                }
            }


            $response['message'] = 'RFQ Saved & Sent Successfully!';
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

    public function saveInstNotes(Request $request){

        $data = $request->all();
        //return $request;
        $response = array();
        try{

            $noteSetting = SystemSetting::where('sys_key','sell_inst_note')->first();

            if($noteSetting) {

                SystemSetting::where('sys_key','sell_inst_note')->delete();

                    $sys_setting = new SystemSetting();
                    $sys_setting->sys_key = 'sell_inst_note';
                    $sys_setting->sys_value = $request->sell_inst_note;
                    $sys_setting->created_by =\Auth::user()->id;
                    $sys_setting->save();


                $response['message'] = 'Notes Updated Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }else{

                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = 'sell_inst_note';
                $sys_setting->sys_value = $request->sell_inst_note;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();

                $response['message'] = 'Notes Saved Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
        }catch(Exception $e){

            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

    }

    public function sendMailToVendor($details , $contact_mail){
        $user = DB::table('users')
            ->join('staff_profiles', 'users.id', '=', 'staff_profiles.user_id')
            ->select('users.*', 'staff_profiles.*')
            ->where('users.id',\Auth::user()->id)
            ->get();
        try{
            $message = '<body style="margin:0px; background: #f8f8f8; ">
                            <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                                <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                        <tbody>
                                            <tr>
                                                <td style="vertical-align: top;" align="center">
                                                    <a href="#" target="_blank"><img src="'.GeneralController::PROJECT_DOMAIN_NAME.'/marketing/assets/images/livetech-logo-100px.png" alt="Live-Tech" style="border:none"><br/>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="background:#2962FF; padding:20px; color:#fff; text-align:center;"> Live-Tech Quote Request </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="padding: 40px; background: #fff;">
                                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        '.$details['rfq_details'] .'

                                                        <b>Thank you<b><br>
                                                        <b>This RFQ has been submitted by '.\Auth::user()->name.' | '.$user[0]->phone.'.</b>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="text-align: center; font-size: 12px; color: black; margin-top: 20px">
                                        <p> Live-Tech requests all shipment are sent as blind shipped. Billing address is 2868 East Slater Drive, Deltona FL 32738. Please send receipts to bills@mylive-tech.com and product tracking to tracking@mylive-tech.com
                                            <br>
                                            </p>
                                    </div>
                                </div>
                            </div>
                        </body>';
            $subject = $details['purchase_order'] .' | RFQ - ' .$details['subject'];
            return $this->sendEmail($subject, $contact_mail , $message , '-','-' );
        }catch(Exception $e){
            $response['message'] = 'Error sending Mail!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

    }

    public function mailtoTempalte($details){
        // return $details;

        $user = DB::table('users')
            ->join('staff_profiles', 'users.id', '=', 'staff_profiles.user_id')
            ->select('users.*', 'staff_profiles.*')
            ->where('users.id',\Auth::user()->id)
            ->get();

        try{
            $message = '<body style="margin:0px; background: #f8f8f8; ">
                            <div width="100%" style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
                                <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
                                        <tbody>
                                            <tr>
                                                <td style="vertical-align: top;" align="center">
                                                    <a href="#" target="_blank"><img src="'.GeneralController::PROJECT_DOMAIN_NAME.'/marketing/assets/images/livetech-logo-100px.png" alt="Live-Tech" style="border:none"><br/>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                        <tbody>
                                            <tr>
                                                <td style="background:#2962FF; padding:20px; color:#fff; text-align:center;"> Live-Tech Quote Request </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div style="padding: 40px; background: #fff;">
                                        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        '.$details['rfq_details'] .'

                                                        <b>Thank you<b><br>
                                                        <b>This RFQ has been submitted by '.\Auth::user()->name.' | '.$user[0]->phone.'.</b>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div style="text-align: center; font-size: 12px; color: black; margin-top: 20px">
                                        <p> Live-Tech requests all shipment are sent as blind shipped. Billing address is 2868 East Slater Drive, Deltona FL 32738. Please send receipts to bills@mylive-tech.com and product tracking to tracking@mylive-tech.com
                                            <br>
                                            </p>
                                    </div>
                                </div>
                            </div>
                        </body>';
            $subject = $details['purchase_order'] .' | RFQ - ' .$details['subject'];
            return $this->sendEmail($subject, $details['to_mails'] , $message , '-','-' );

        }catch(Exception $e){
            $response['message'] = 'Error sending Mail!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function sendEmail($subject, $recipient,$body, $recipient_name, $reply = false) {
        try {
            $mail = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();

            //Set the hostname of the mail server
            $mail->Host = 'mylive-tech.com';
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 25;
            //We don't need to set this as it's the default value
            //$mail->SMTPAuth = false;
            //Set who the message is to be sent from
            $mail->setFrom('accounts@mylive-tech.com', 'First Last');
            //Set an alternative reply-to address
            $mail->addReplyTo('muhammadkashif70000@gmail.com', 'First Last');
            //Set who the message is to be sent to
            $mail->addAddress($recipient, $recipient_name);
            //Set the subject line
            $mail->Subject = 'PHPMailer SMTP without auth test';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //Replace the plain text body with one created manually
            $mail->Body = $body;
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file

            //send the message, check for errors
            $mail->send();

            // $mail = new PHPMailer(true);
            // $mail->isSMTP();
            // $mail->SMTPAuth  =  true;

            // $mail->Host      =  self::$mailserver_hostname;
            // $mail->Username  =  self::$mailserver_username;
            // $mail->Password  =  self::$mailserver_password;

            // $mail->SMTPOptions = [
            //     'ssl' => [
            //         'verify_peer' => false,
            //         'verify_peer_name' => false,
            //         'allow_self_signed' => true,
            //     ]
            // ];

            // $mail->setFrom($mail->Username);
            // $mail->addAddress($recipient, $recipient_name);

            // //Recipients
            // if ($reply) {
            //     $mail->addReplyTo($recipient, $subject);
            // }

            // $mail->isHTML(true);
            // $mail->Subject = $subject;
            // $mail->Body    = $body;
            // $mail->AltBody = '';

            // $mail->send();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function saveVendor(Request $request){

        $data = $request->all();

        $response = array();
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // generate a pin based on 2 * 7 digits + a random character
        $pin = mt_rand(10000000, 99999999)

            . $characters[rand(0, strlen($characters) - 1)];

        // shuffle the result
        $string = str_shuffle($pin);

        try{
            if ($request->has('vendor_account')) {

                $data['name']= ($data['first_name'].' '.$data['last_name']);
                $data['password']= bcrypt($string);

            	$create_vendor_account = User::create($data);

            }
            if(!empty($request->contact_id)){


                $contacts_id = Vendors::where('id',$request->contact_id)->first();

                $contacts_id->first_name = $data['first_name'];
                $contacts_id->last_name= $data['last_name'];
                $contacts_id->company= $data['comp_name'];
                $contacts_id->comp_id= $data['company'];
                $contacts_id->website= $data['website'];
                $contacts_id->email= $data['email'];
                $contacts_id->phone= $data['phone'];
                $contacts_id->categories= $data['categories'];
                $contacts_id->tags= $data['tags'];

            if($contacts_id){

                $contacts_id->update();
                $response['message'] = 'Vendor Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
         }else{


                $data['created_by'] = \Auth::user()->id;
            	$save_vendors = Vendors::create($data);

                $response['message'] = 'Vendor Save Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
         }
        }catch(Exception $e){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function delete_vendor(Request $request){

        $data = $request->all();
        $response = array();

        $del_department = Vendors::destroy($data);
        $response['message'] = 'Vendor Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function getVendors(Request $request){


        $contacts = Vendors::orderBy('id','desc')->get();

        foreach($contacts as $contact) {

            $tags_arr = [];
            $tags_arr = explode(",",$contact->tags);

            $contact->vendor_tags = Tags::whereIn('id',$tags_arr)->get();

            $category_arr = [];
            $category_arr = explode(",",$contact->categories);

            $contact->vendor_categories = Category::whereIn('id',$category_arr)->get();

        }

        // if ($request->ajax()) {
        //     return Datatables::of($contacts)->make(true);
        // }

        $response['message'] = 'Contacts List';
        $response['data'] = $contacts;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function getNotes(){


        $notes = SystemSetting::where('sys_key','sell_inst_note')->get();
        // $tags = Tags::get();

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['notes'] = $notes;

        return response()->json($response);
    }

    public function saveCategory(Request $request){
        $data = $request->all();

        $response = array();

        try{
                $data['created_by'] = \Auth::user()->id;

                $save_category = Category::create($data);
                $response['message'] = 'Category Added Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['id'] = $save_category->id;

                return response()->json($response);

        }catch(Exception $e){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function getCategories(){
        $categories = Category::get();

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['categories']= $categories;

        return response()->json($response);
    }

    public function updateVendor(Request $request) {
        $data = $request->all();

        $vendor = Vendors::find($data["vendor_id"]);
        $vendor->first_name = $data["first_name"];
        $vendor->last_name = $data["last_name"];
        $vendor->email = $data["email"];
        $vendor->phone = $data["phone"];
        $vendor->address = $data["address"];
        $vendor->comp_id = $data["comp_id"];
        $vendor->comp_name = $data["comp_name"];
        $vendor->country = $data["country"];
        $vendor->state = $data["state"];
        $vendor->city = $data["city"];
        $vendor->zip = $data["zip"];
        $vendor->twitter = $data["twitter"];
        $vendor->fb = $data["fb"];
        $vendor->insta = $data["insta"];
        $vendor->pinterest = $data["pinterest"];
        $vendor->cmp_bill_add = $data["cmp_bill_add"];
        $vendor->cmp_ship_add = $data["cmp_ship_add"];
        $vendor->cmp_pr_add = $data["cmp_pr_add"];
        $vendor->notes = $data["notes"];
        $vendor->save();

        return response()->json([
            'message' => 'profile updated successfully',
            'status'=> 200,
            'success' => true
        ]);


    }

    public function wp_order_create() {
        $payload = @file_get_contents('php://input');
       $payload = json_decode( $payload, true);

       if($payload != null && $payload != '') {
           \Log::info($payload);

           $shipping_cost = 0;
           $fee_cost = 0;

           $shipping = $payload['shipping_lines'];
           if($shipping != '' &&  $shipping!= null ) {
               for($s = 0; $s < sizeOf($shipping); $s++) {
                   $shipping_cost = $shipping_cost + $shipping[$s]['total'];
               }
           }

           $fees = $payload['fee_lines'];
           if($fees != '' &&  $fees != null ) {
               for($f = 0; $f < sizeOf($fees); $f++) {
                   $fee_cost = $fee_cost + $fees[$f]['total'];
               }
           }

           DB::table("orders")->insert([
               "woo_id" => $payload['id'],
               "custom_id" => $payload['id'],
               "currency" => $payload['currency'],
               "version" => $payload['version'],
           ]);


           $line_items = $payload['line_items'];
           for($i = 0; $i < sizeOf($line_items); $i++) {
               DB::table("line_items")->insert([
                   "id" => $line_items[$i]['id'],
                   "order_id" => $payload['id'],
                   "name" => $line_items[$i]['name'],
                   "quantity" => $line_items[$i]['quantity'],
                   "total" => $line_items[$i]['subtotal'],
                   "price" => $line_items[$i]['price'],
                   "fees" => $fee_cost,
                   "shipping" =>  $shipping_cost,
               ]);
           }

       }

   }

   public function wp_order_update() {
       $payload = @file_get_contents('php://input');
       $payload = json_decode( $payload, true);

       if($payload != null && $payload != '') {
           \Log::info( $payload);

           DB::table("orders")->where("custom_id",$payload['id'])->update([
               "woo_id" => $payload['id'],
               "custom_id" => $payload['id'],
               "currency" => $payload['currency'],
               "version" => $payload['version'],
           ]);

           DB::table("line_items")->where("order_id",$payload['id'])->delete();

           $shipping_cost = 0;
           $fee_cost = 0;

           $shipping = $payload['shipping_lines'];
           if($shipping != '' &&  $shipping!= null ) {
               for($s = 0; $s < sizeOf($shipping); $s++) {
                   $shipping_cost = $shipping_cost + $shipping[$s]['total'];
               }
           }

           $fees = $payload['fee_lines'];
           if($fees != '' &&  $fees != null ) {
               for($f = 0; $f < sizeOf($fees); $f++) {
                   $fee_cost = $fee_cost + $fees[$f]['total'];
               }
           }

           $line_items = $payload['line_items'];
           for($i = 0; $i < sizeOf($line_items); $i++) {
               DB::table("line_items")->insert([
                   "id" => $line_items[$i]['id'],
                   "order_id" => $payload['id'],
                   "name" => $line_items[$i]['name'],
                   "quantity" => $line_items[$i]['quantity'],
                   "total" => $line_items[$i]['subtotal'],
                   "price" => $line_items[$i]['price'],
                   "fees" => $fee_cost,
                   "shipping" =>  $shipping_cost,
               ]);
           }

       }


   }

   public function wp_order_delete() {
       $payload = @file_get_contents('php://input');
       $payload = json_decode( $payload, true);

       if($payload != null && $payload != '') {
           \Log::info( $payload);

           DB::table("orders")->where("custom_id",$payload['id'])->delete();
           DB::table("line_items")->where("order_id",$payload['id'])->delete();

           \Log::info("success");
       }

   }



}



