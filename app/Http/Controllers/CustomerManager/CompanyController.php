<?php

namespace App\Http\Controllers\CustomerManager;

use App\CompanyActivityLog;
use App\Http\Controllers\Controller;
use App\Models\Activitylog;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Company;
use App\Models\Integrations;
use App\Models\TicketNote;
use App\Models\Tickets;
use App\Models\TicketSettings;
use App\Models\Tags;
use Illuminate\Support\Facades\File;
use App\Models\SlaPlan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Collection;
use App\User;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->woocommerce = new Client(
            'https://ltdevdemo.tempurl.host', 
            'ck_0a047661cab377740f44c61240667dfed7af21b3', 
            'cs_c822dc7a24b1cfb790d1dd4ed66fd4ec8a143ce6',
            [
                'version' => 'wc/v3',
                'verify_ssl' => false
            ]
        );

        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }

    public function index() {
        $companies = Company::with('user')->get();

        $is_default = 0;

        $check_company = Company::where("is_default", 1)->first();
        
        $is_default = empty($check_company) ? 0 : 1;

        $google_key = 0;
        $google= DB::Table("integrations")->where("slug", "google-api")->where('status', 1)->first();
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
        
        return view('customer_manager.company_lookup.index-new',compact('google','companies','countries','is_default','google_key'));
    }

    public function get_customer(){

        $data = array();

        $customers = Customer::all();

        foreach($customers as $customer){
            $customer->Id =  $customer->id;
            $customer->{'Woo Id'} =  $customer->woo_id;
            $customer->Username = '<a href="customer-profile/'.$customer["woo_id"].'">'. $customer['woo_username'] .'</a>';
            $customer->Name =  $customer->name;
            $customer->Email =  $customer->email;
            $customer->Address =  $customer->address;
            $customer->Phone =  $customer->phone;
            $customer->Company =  $customer->company_name;

            array_push($data,$customer);

        }

        $response['status_code'] = 200;
        $response['success'] = true;
        $response['customer'] = $data;
        return response()->json($response);
        
          
    }

    public function save_company(Request $request){

        $check_company = DB::table("companies")->where('email',$request->email)->first();

        if($check_company) {

  
            if($check_company->is_deleted == 0) {

                return response()->json([
                    "message" =>  'Email Already Taken try another one!',
                    "status_code" => 500,
                    "success" => false,
                ]);

            }else{

                DB::table("companies")->where('email',$request->email)->where('deleted_at', '!=', null)->delete();

                if($request->is_default == 1) {

                    $check_company = Company::where("is_default","=",1)->first();
        
                    if(empty($check_company)) {
                        $request->validate([
                            "email" => "required|email|unique:companies",
                        ]);
                        $company = new Company();
                        $company->poc_first_name = $request->poc_first_name;
                        $company->poc_last_name = $request->poc_last_name;
                        $company->name = $request->name;
                        $company->email = $request->email;
                        $company->phone = $request->phone;
                        $company->cmp_country = $request->country;
                        $company->cmp_state = $request->state;
                        $company->cmp_city = $request->city;
                        $company->cmp_zip = $request->zip;
                        $company->address = $request->address;
                        $company->apt_address = $request->apt_address;
                        $company->cmp_bill_add = $request->cmp_bill_add;
                        $company->cmp_ship_add = $request->cmp_ship_add;
                        $company->created_by = $request->user_id;
                        $company->is_default = $request->is_default;
            
                        if($company->save()){
                            $cmp_act_log = new CompanyActivityLog();
                            $cmp_act_log->action_perform = auth()->user()->name.' Created '.$company->name;
                            $cmp_act_log->company_id = $company->id;
                            $cmp_act_log->created_by = \auth()->user()->id;
                            $cmp_act_log->save();
            
                            $response['message'] = 'Company Added Successfully!';
                            $response['status_code'] = 200;
                            $response['success'] = true;
                            $response['result'] = $company->id;
                            return response()->json($response);
                        }else{
                            $response['message'] = 'Something Went wrong!';
                            $response['status_code'] = 500;
                            $response['success'] = false;
                            return response()->json($response);
                        }
                    }else{
                        $response['message'] = 'Default Company Already Set!';
                        $response['status_code'] = 500;
                        $response['success'] = false;
                        return response()->json($response);
                    }
                    
                }else{
                    $request->validate([
                        "email" => "required|email|unique:companies",
                    ]);
                    $company = new Company();
                    $company->poc_first_name = $request->poc_first_name;
                    $company->poc_last_name = $request->poc_last_name;
                    $company->name = $request->name;
                    $company->email = $request->email;
                    $company->phone = $request->phone;
                    $company->cmp_country = $request->country;
                    $company->cmp_state = $request->state;
                    $company->cmp_city = $request->city;
                    $company->cmp_zip = $request->zip;
                    $company->address = $request->address;
                    $company->apt_address = $request->apt_address;
                    $company->cmp_bill_add = $request->cmp_bill_add;
                    $company->cmp_ship_add = $request->cmp_ship_add;
                    $company->created_by = $request->user_id;
                    $company->is_default = 0;
        
                    if($company->save()){
                        $cmp_act_log = new CompanyActivityLog();
                        $cmp_act_log->action_perform = auth()->user()->name.' Created '.$company->name;
                        $cmp_act_log->company_id = $company->id;
                        $cmp_act_log->created_by = \auth()->user()->id;
                        $cmp_act_log->save();
        
                        $response['message'] = 'Company Added Successfully!';
                        $response['status_code'] = 200;
                        $response['success'] = true;
                        $response['result'] = $company->id;
                        return response()->json($response);
                    }else{
                        $response['message'] = 'Something Went wrong!';
                        $response['status_code'] = 500;
                        $response['success'] = false;
                        return response()->json($response);
                    }
                }
            }
            
        }else{
            if($request->is_default == 1) {

                $check_company = Company::where("is_default","=",1)->first();
    
                if(empty($check_company)) {
                    $request->validate([
                        "email" => "required|email|unique:companies",
                    ]);
                    $company = new Company();
                    $company->poc_first_name = $request->poc_first_name;
                    $company->poc_last_name = $request->poc_last_name;
                    $company->name = $request->name;
                    $company->email = $request->email;
                    $company->phone = $request->phone;
                    $company->cmp_country = $request->country;
                    $company->cmp_state = $request->state;
                    $company->cmp_city = $request->city;
                    $company->cmp_zip = $request->zip;
                    $company->address = $request->address;
                    $company->apt_address = $request->apt_address;
                    $company->cmp_bill_add = $request->cmp_bill_add;
                    $company->cmp_ship_add = $request->cmp_ship_add;
                    $company->created_by = $request->user_id;
                    $company->is_default = $request->is_default;
        
                    if($company->save()){
                        $cmp_act_log = new CompanyActivityLog();
                        $cmp_act_log->action_perform = auth()->user()->name.' Created '.$company->name;
                        $cmp_act_log->company_id = $company->id;
                        $cmp_act_log->created_by = \auth()->user()->id;
                        $cmp_act_log->save();
        
                        $response['message'] = 'Company Added Successfully!';
                        $response['status_code'] = 200;
                        $response['success'] = true;
                        $response['result'] = $company->id;
                        return response()->json($response);
                    }else{
                        $response['message'] = 'Something Went wrong!';
                        $response['status_code'] = 500;
                        $response['success'] = false;
                        return response()->json($response);
                    }
                }else{
                    $response['message'] = 'Default Company Already Set!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
                
            }else{
                $request->validate([
                    "email" => "required|email|unique:companies",
                ]);
                $company = new Company();
                $company->poc_first_name = $request->poc_first_name;
                $company->poc_last_name = $request->poc_last_name;
                $company->name = $request->name;
                $company->email = $request->email;
                $company->phone = $request->phone;
                $company->cmp_country = $request->country;
                $company->cmp_state = $request->state;
                $company->cmp_city = $request->city;
                $company->cmp_zip = $request->zip;
                $company->address = $request->address;
                $company->apt_address = $request->apt_address;
                $company->cmp_bill_add = $request->cmp_bill_add;
                $company->cmp_ship_add = $request->cmp_ship_add;
                $company->created_by = $request->user_id;
                $company->is_default = $request->is_default;
    
                if($company->save()){
                    $cmp_act_log = new CompanyActivityLog();
                    $cmp_act_log->action_perform = auth()->user()->name.' Created '.$company->name;
                    $cmp_act_log->company_id = $company->id;
                    $cmp_act_log->created_by = \auth()->user()->id;
                    $cmp_act_log->save();
    
                    $response['message'] = 'Company Added Successfully!';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    $response['result'] = $company->id;
                    return response()->json($response);
                }else{
                    $response['message'] = 'Something Went wrong!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
            }
        }
        
    }

    public function deleteCompany(Request $request) {

        $company = Company::where('id',$request->id)->first();
        // return $company;

        if($company->is_default == 1) {
            $response['message'] = 'Default Company Cannot be Deleted!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }else{

            $company->is_deleted = 1;
            DB::table("companies")->where('id',$request->id)->delete();

            CompanyActivityLog::create([
                'action_perform' => auth()->user()->name.' Deleted '.$company->name,
                'company_id' => $company->id,
                'created_by' => auth()->user()->id,
            ]);

            $response['message'] = 'Company Deleted Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }

    }

    public function editOrDelete(Request $request){
        try{
            $company = Company::find($request->input('id'));
            $response = [];
            $response['status_code'] = 200;
            $response['success'] = true;
            if($request->input('action') == 'edit'){
                $company->address = $request->input('address');
                $company->email = $request->input('email');
                $company->phone = $request->input('phone');
                $response['message'] = 'Company details Update Successfully!';
                $response['action'] = 'edit';
                if($company->save()){
                    $save_activity =CompanyActivityLog::create([
                        'action_perform' => auth()->user()->name.' Edited '.$company->name,
                        'company_id' => $company->id,
                        'created_by' => auth()->user()->id,
                    ]);
                    return response()->json($response);
                }
            }elseif($request->input('action') == 'delete'){

                if($company->is_default == 1) {
                    $response['message'] = 'Default Company Cannot be Deleted!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }else{
                    if($company->delete()){
                        $company->is_deleted = 1;

                        $save_activity =CompanyActivityLog::create([
                            'action_perform' => auth()->user()->name.' Deleted '.$company->name,
                            'company_id' => $company->id,
                            'created_by' => auth()->user()->id,
                        ]);
                        $response['message'] = 'Company Delete Successfully!';
                        $response['action'] = 'delete';
                        return response()->json($response);
                    }
                }
                
               
            }
            
        }catch(Throwable $err){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response,500);
        }
    }

    public function company_profile($id){
        $company = Company::with('staff_members')->with('staffs')->findOrFail($id);
        $customer = Customer::where('company_id', $id)->first();

        $company_staff = Customer::where('company_id', $id)->get();    
        $tags = Tags::all();
        $tickets = DB::Table('tickets')
        ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        ->join('ticket_types','ticket_types.id','=','tickets.type')
        ->join('departments','departments.id','=','tickets.dept_id')
        ->join('users','users.id','=','tickets.assigned_to')
        ->whereIn('tickets.assigned_to',$company->staffs->pluck('id'))
        ->where('ticket_priorities.name','<>','Close')
        ->get();

        $activity_logs = CompanyActivityLog::where('company_id', $id)->get();

        $is_default = 0;

        $check_company = Company::where("is_default", 1)->first();
        empty($check_company) ? $is_default = 0 : $is_default = 1;
        
        $google_key = 0;
        $google = DB::Table("integrations")->where("slug","=","google-api")->where('status', 1)->first();
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

        $sla_plans = SlaPlan::where('sla_status',1)->where('is_deleted',0)->get(); 
        $date_format = Session('system_date');


        $notesCount = 0;
        $customers = Customer::where('company_id', $id)->get();
        // foreach($customers as $customer) {
        //     $customer_tickets = Tickets::where('customer_id' , $customer->id)->get();

        //     foreach($customer_tickets as $ticket) {
        //         $notesCount += TicketNote::where('ticket_id', $ticket->id)->where('type','User Organization')->count();
        //     }
        // }
        $notesCount = TicketNote::whereIn('type',['User Organization'])->where('is_deleted',0)->where('company_id',$id)->count();

        return view('customer_manager.company_lookup.companyprofile-new', get_defined_vars());
    }

    public function uploadCompanyImage(Request $request) {

        $image = $request->file('profile_img');
        $imageName = $_FILES['profile_img']['name'];
       
        $imageName = strtolower($imageName);
        $imageName = str_replace(" ","_",$imageName);

        $target_dir = 'storage/companies';

        if (!File::isDirectory($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image->move($target_dir, $imageName);

        $company = Company::where('id' , $request->company_id)->first();
        $company->com_logo = 'storage/companies/' . $imageName;
        $company->save();

        $response['message'] = 'Company Profile Uploaded Successfully';
        $response['status'] = 200;
        $response['success'] = true;
        $response['img'] = $company->com_logo;
        return response()->json($response);
    }

    public function getLog(){
        $activity = CompanyActivityLog::orderBy('created_at','DESC')->get(['id','action_perform','created_at']);
        // dd($activity);
        // // return $activity;
        return response()->json($activity);
    }

    public function get_company_lookup(){
        $companies = DB::table('companies')->where('is_deleted',0)->latest('id')->get();
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['companies'] = $companies;
        $response['date_format'] = Session('system_date');
        return response()->json($response);
    }

    public function update_company(Request $request){
        try{
            $company = Company::find($request->input('id'));
            $response = [];


            $col_name = $request->column;
            $company->$col_name = $request->value;

            if($company->save()){
                CompanyActivityLog::create([
                            'action_perform' => auth()->user()->name.' Edited '.$company->name,
                            'company_id' => $company->id,
                            'created_by' => auth()->user()->id,
                        ]);
                $response['message'] = 'Company Updated Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                }else{
                $response['message'] = 'Something Went wrong!';
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

    public function update_company_profile(Request $request) {

        $data = array(
            "poc_first_name" => $request->poc_first_name,
            "poc_last_name" => $request->poc_last_name,
            "name" => $request->name,
            "email" => $request->email,
            "phone" => $request->phone,

            "cmp_country" => $request->cmp_country,
            "cmp_state" => $request->cmp_state,
            "cmp_city" => $request->cmp_city,
            "cmp_zip" => $request->cmp_zip,
            "fb" => $request->fb,

            "pinterest" => $request->pinterest,
            "twitter" => $request->twitter,
            "insta" => $request->insta,
            "website" => $request->website,
            "address" => $request->address,

            "is_default" => $request->is_default,
            "apt_address" => $request->apt_address,
            "bill_st_add" => $request->bill_st_add,
            "bill_apt_add" => $request->bill_apt_add,
            "bill_add_country" => $request->bill_add_country,

            "bill_add_state" => $request->bill_add_state,
            "bill_add_city" => $request->bill_add_city,
            "bill_add_zip" => $request->bill_add_zip,
            "is_bill_add" => $request->is_bill_add,
            "notes" => $request->notes,

            "cmp_ship_add" => $request->address,
            "cmp_bill_add" => $request->bill_st_add,
        );

        if($request->is_default == 1) { 
            $check_company = Company::where("is_default","=",1)->first();

            if(empty($check_company)) {

                $company = Company::find($request->cmp_id);
            
                if($company->email == $request->email) {

                    Company::where('id',$request->cmp_id)->update($data);
        
                    $response['message'] = 'Company Profile Updated Successfully';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    return response()->json($response);
    
                }else{
    
                    $request->validate([
                        "email" => "required|email|unique:companies",
                    ]);
                    
                    Company::where('id',$request->cmp_id)->update($data);
        
                    $response['message'] = 'Company Profile Updated Successfully';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    return response()->json($response);
    
                }
            }else{
                $response['message'] = 'Default Company Already Set!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

        }else{
            $company = Company::find($request->cmp_id);
            
            if($company->email == $request->email) {

                Company::where('id',$request->cmp_id)->update($data);
    
                $response['message'] = 'Company Profile Updated Successfully';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }else{

                $request->validate([
                    "email" => "required|email|unique:companies",
                ]);

                Company::where('id',$request->cmp_id)->update($data);
    
                $response['message'] = 'Company Profile Updated Successfully';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
        }
      
    }

    public function get_staffs($id){
        $staffs = Company::with(['staffs.tickets','staffs.staffProfile'])->orderBy('created_at','desc')->find($id);
        // $staffs->load('staffProfile');
        return response()->json($staffs);
    }

    public function add_staff(Request $request, $id){
        try{
            $staff = User::where('email',$request->input('staff_email'))->first();
            $response = [];
            if($staff){
                $staff->company()->attach($id);
                $response['message'] = 'Staff added to company Successful!';
                $response['status_code'] = 200;
                $response['success'] = true;
            }else{
                $response['message'] = 'Staff not found!';
                $response['status_code'] = 200;
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

    public function remove_staff(Company $company, $id){
        try{
            $response = [];
            if($company){
                $company->staffs()->detach($id);
                $response['message'] = 'Staff Remove to company Successful!';
                $response['status_code'] = 200;
                $response['success'] = true;
            }else{
                $response['message'] = 'Staff not found!';
                $response['status_code'] = 200;
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

    public function testcompany(Request $request) {


        $payload = @file_get_contents('php://input');
        $payload = json_decode( $payload, true);
        \Log::info(json_encode( $payload));
        // return response()->json([ 'data' => $payload, 'status' => \Symfony\Component\HttpFoundation\Response::HTTP_OK]);

             
        // $data = [
        //     'name' => 'Product created',
        //     'topic' => 'product.created',
        //     'delivery_url' => 'https://webhook.site/9db4191d-d0d0-4a10-9659-c86b2d55232d'
        // ];
        // $results = $this->woocommerce->get('webhooks' , $data);
        // return $results;    
    }

    public function saveCompanySLA(Request $request) {

        $company = Company::find($request->company_id);
        $company->com_sla = $request->com_sla;
        $company->save();

        $response['message'] = 'SLA Assigned Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function saveCompanyStaff(Request $request) {

        if($request->type == 'customer') {

            $customer = Customer::where('email',$request->staff_email)->first();

            if($customer) {
                $title = 'Email Already Registered... Try Another one!';
                $status = 500;
            }else{
                Customer::create([
                    "first_name" => $request->staff_first_name,
                    "last_name" => $request->staff_last_name,
                    "phone" => $request->staff_phone,
                    "email" => $request->staff_email,
                    "username" => $request->staff_email,
                    "company_id" => $request->company_id,
                ]);
                
                $title = 'Customer Created Successfully';
                $status = 200;
            }    
        }else{

            $user = User::where('email',$request->staff_email)->first();

            if($user) {
                
                $title = 'Email Already Registered... Try Another one!';
                $status = 500;

            }else{

                $password = Hash::make($request->staff_password);
                $alt_pwd = Crypt::encryptString($request->staff_password);
                User::create([
                    "name" => $request->staff_name,
                    "email" => $request->staff_email,
                    "phone_number" => $request->staff_phone,
                    "password" => $password,
                    "alt_pwd" => $alt_pwd,
                    "user_type" => 2,
                    "company_id" => $request->company_id,
                    "created_by" => \Auth::user()->id,
                ]);

                $title = 'User Created Successfully';
                $status = 200;

            }
        }

        $response['message'] = $title;
        $response['status_code'] = $status;
        $response['success'] = true;
        $response['type'] = $request->type;
        return response()->json($response);

    }
    

    public function showCompanyStaff(Request $request) {
        $id = $request->company_id;

        $customers = collect(Customer::where('company_id',$id)->select('first_name','last_name','email','phone')->get()->toArray());
        $users = $customers->merge(User::where('company_id',$id)->select('name','email','phone_number')->get()->toArray());

        return $users->all();
        
    }
}
