<?php

namespace App\Http\Controllers\SystemManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use App\Models\SystemManager\Feature;
use App\Models\{SystemSetting ,Customer, TicketSettings , SlaPlan , ProjectType , BrandSettings , Departments, TicketStatus, TicketPriority, TicketType, CustomerType, ResponseTemplate , ResTemplateCat, Notification, SpamUser, DispatchStatus, Tasks,Tickets, DepartmentAssignments};
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\{Session, DB , Auth, Http};
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use SystemSettings;

class SettingsController extends Controller
{

    public function __construct() {
        $this->middleware('auth');

        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });

    }

    public function settings(){

        $brand_settings = BrandSettings::first();
        $departments = Departments::all();
        $ticket_settings = TicketSettings::first(); 

        if($brand_settings) {
            Session::put('site_title', $brand_settings->site_title);
            Session::put('site_logo', $brand_settings->site_logo);
            Session::put('site_favicon', $brand_settings->site_favicon);
            Session::put('site_logo_title', $brand_settings->site_logo_title);
            Session::put('site_footer', $brand_settings->site_footer);
            Session::put('site_version', $brand_settings->site_version);
        }
        $roles = Role::all();
        $featureLists = Feature::where('parent_id',0)->get();
        $featureListsSub = Feature::where('parent_id', '!=',0)->get();
        $sys_setting = SystemSetting::whereIn('sys_key',['emails','email_recap_notifications','check_off_emails'])->get()->toArray();

        $keys = array(  
            "reply_due_deadline" ,
            "reply_due_deadline_when_adding_ticket_note",
            "default_reply_and_resolution_deadline" ,
            "default_reply_time_deadline" ,
            "default_resolution_deadline",
            "overdue_ticket_background_color",
            "overdue_ticket_text_color",
        );

        $sla_setting = array();

        $ticket_sla = TicketSettings::whereIn('tkt_key',$keys)->get();

        if(sizeOf($ticket_sla) > 0) {
            foreach($ticket_sla as $sla) {
                $sla_setting[$sla->tkt_key] = $sla->tkt_value;
            }   
        }

        $staff_list = User::where('user_type', '!=', 5)->get();
        $general_staff_note = SystemSetting::where('sys_key', 'general_staff_note')->first();
        if(!empty($general_staff_note)) $general_staff_note = $general_staff_note->sys_value;
        $note_for_selected_staff = SystemSetting::where('sys_key', 'note_for_selected_staff')->select('sys_value')->first();
        if(!empty($note_for_selected_staff)) $note_for_selected_staff = $note_for_selected_staff->sys_value;
        $selected_staff_members = SystemSetting::where('sys_key', 'selected_staff_members')->select('sys_value')->first();
        if(!empty($selected_staff_members)) $selected_staff_members = explode(',', $selected_staff_members->sys_value);
        else $selected_staff_members = array();



        $time_zone = SystemSetting::where('sys_key','sys_timezone')->where('created_by', auth()->id())->first();
        if($time_zone) {
            $timeZone = $time_zone->sys_value;
        }else{
            $timeZone = 'America/New_York';
        }

        $dateformat = SystemSetting::where('sys_key','sys_dt_frmt')->where('created_by', auth()->id())->select('sys_value')->first();
        $timeformat = SystemSetting::where('sys_key','sys_tm_frmt')->where('created_by', auth()->id())->select('sys_value')->first();
        
        $datetime = [
            "date" =>  ($dateformat != null ? $dateformat->sys_value : 'MM/DD/YYYY'),
            "time" =>  ($timeformat != null ? $timeformat->sys_value : 'hh:mm:ss'),
        ];
        // return view('system_manager.settings.index',compact('brand_settings','roles','departments','ticket_settings','featureLists','featureListsSub','sys_setting','sla_setting', 'staff_list', 'selected_staff_members', 'note_for_selected_staff', 'general_staff_note'));
       
        // get ticket refresh time
        $tkt_refresh_time = SystemSetting::where('sys_key', 'ticket_refresh_time')->where('created_by', auth()->id())->first();
        $ticket_time = ($tkt_refresh_time == null ? 0 : $tkt_refresh_time->sys_value);


        $types = DB::table('ticket_types')->get();

        // old data
        // return view('system_manager.settings.index', get_defined_vars());
        return view('system_manager.settings.index-new', get_defined_vars());
    }

    public function settingsNew(){

        $brand_settings = BrandSettings::first();
        $departments = Departments::all();
        $ticket_settings = TicketSettings::first(); 

        if($brand_settings) {
            Session::put('site_title', $brand_settings->site_title);
            Session::put('site_logo', $brand_settings->site_logo);
            Session::put('site_favicon', $brand_settings->site_favicon);
            Session::put('site_logo_title', $brand_settings->site_logo_title);
            Session::put('site_footer', $brand_settings->site_footer);
            Session::put('site_version', $brand_settings->site_version);
        }
        $roles = Role::all();
        $featureLists = Feature::where('parent_id',0)->get();
        $featureListsSub = Feature::where('parent_id', '!=',0)->get();
        $sys_setting = SystemSetting::whereIn('sys_key',['emails','email_recap_notifications','check_off_emails'])->get()->toArray();

        $keys = array(  
            "reply_due_deadline" ,
            "reply_due_deadline_when_adding_ticket_note",
            "default_reply_and_resolution_deadline" ,
            "default_reply_time_deadline" ,
            "default_resolution_deadline",
            "overdue_ticket_background_color",
            "overdue_ticket_text_color",
        );

        $sla_setting = array();

        $ticket_sla = TicketSettings::whereIn('tkt_key',$keys)->get();

        if(sizeOf($ticket_sla) > 0) {
            foreach($ticket_sla as $sla) {
                $sla_setting[$sla->tkt_key] = $sla->tkt_value;
            }   
        }

        $staff_list = User::where('user_type', '!=', 5)->get();
        $general_staff_note = SystemSetting::where('sys_key', 'general_staff_note')->first();
        if(!empty($general_staff_note)) $general_staff_note = $general_staff_note->sys_value;
        $note_for_selected_staff = SystemSetting::where('sys_key', 'note_for_selected_staff')->select('sys_value')->first();
        if(!empty($note_for_selected_staff)) $note_for_selected_staff = $note_for_selected_staff->sys_value;
        $selected_staff_members = SystemSetting::where('sys_key', 'selected_staff_members')->select('sys_value')->first();
        if(!empty($selected_staff_members)) $selected_staff_members = explode(',', $selected_staff_members->sys_value);
        else $selected_staff_members = array();



        $time_zone = SystemSetting::where('sys_key','sys_timezone')->where('created_by', auth()->id())->first();
        if($time_zone) {
            $timeZone = $time_zone->sys_value;
        }else{
            $timeZone = 'America/New_York';
        }

        $dateformat = SystemSetting::where('sys_key','sys_dt_frmt')->where('created_by', auth()->id())->select('sys_value')->first();
        $timeformat = SystemSetting::where('sys_key','sys_tm_frmt')->where('created_by', auth()->id())->select('sys_value')->first();
        
        $datetime = [
            "date" =>  ($dateformat != null ? $dateformat->sys_value : 'MM/DD/YYYY'),
            "time" =>  ($timeformat != null ? $timeformat->sys_value : 'hh:mm:ss'),
        ];
        // return view('system_manager.settings.index',compact('brand_settings','roles','departments','ticket_settings','featureLists','featureListsSub','sys_setting','sla_setting', 'staff_list', 'selected_staff_members', 'note_for_selected_staff', 'general_staff_note'));
        return view('system_manager.settings.index-new', get_defined_vars());
    }
    
    public function staff_manager(){
        return view('system_manager.staff_manager.index');
    }

    public function saveBrandSettings(Request $request){

        $data = array(
            "site_title" => $request->site_title, 
            "site_logo_title" => $request->site_logo_title, 
            "site_version" => $request->site_version, 
            "site_domain" => $request->site_domain, 
            "site_footer" => $request->site_footer, 
        );

        $brand_settings = BrandSettings::first();

        if($request->hasFile('site_logo')) {

            $image = $request->file('site_logo');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $filePath = public_path('storage/branding/');
            $image->move($filePath, $filename);
            
            $data['site_logo'] = 'storage/branding/'. $filename;
        }

        if($request->hasFile('site_favicon')) {

            $image = $request->file('site_favicon');
            $filename = (time() + 2) . '.' . $image->getClientOriginalExtension();
            $filePath = public_path('storage/branding/');
            $image->move($filePath, $filename);
            
            $data['site_favicon'] = 'storage/branding/'. $filename;
        }

        if($request->hasFile('login_logo')) {

            $image = $request->file('login_logo');
            $filename = (time() + 4) . '.' . $image->getClientOriginalExtension();
            $filePath = public_path('storage/branding/');
            $image->move($filePath, $filename);
            
            $data['login_logo'] = 'storage/branding/'. $filename;
        }

        if($request->hasFile('customer_logo')) {

            $image = $request->file('customer_logo');
            $filename = (time() + 5) . '.' . $image->getClientOriginalExtension();
            $filePath = public_path('storage/branding/');
            $image->move($filePath, $filename);
            
            $data['customer_logo'] = 'storage/branding/'. $filename;
        }

        if($request->hasFile('company_logo')) {

            $image = $request->file('company_logo');
            $filename = (time() + 7) . '.' . $image->getClientOriginalExtension();
            $filePath = public_path('storage/branding/');
            $image->move($filePath, $filename);
            
            $data['company_logo'] = 'storage/branding/'. $filename;
        }

        if($request->hasFile('user_logo')) {
            $image = $request->file('user_logo');
            $filename = (time() + 8) . '.' . $image->getClientOriginalExtension();
            $filePath = public_path('storage/branding/');
            $image->move($filePath, $filename);
            
            $data['user_logo'] = 'storage/branding/'. $filename;
        }

        if($brand_settings) {
            $data['updated_by'] = auth()->id();
            BrandSettings::where('id',$brand_settings->id)->update($data);
        }else{
            $data['created_by'] = auth()->id();
            BrandSettings::create($data);
        }
    

        $response['message'] = 'Brand Settings Saved Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function save_department(Request $request ){
        
        $data = $request->all();

        if(empty(trim($request->name, " "))) {
            $response['message'] = 'Name is empty!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
        
        $response = array();
        $dept_created_by=\Auth::user()->id;
        try{
            if(!empty($request->dep_id)){
                $dept_counter = 0;
                if($request->has('dept_counter')){
                    $dept_counter = 1;
                }
                $departments_id = Departments::where('id',$request->dep_id)->first();
                $departments_id->name = $data['name'];
                $departments_id->dept_slug = $data['dept_slug'];
                $departments_id->dept_counter = $dept_counter;

                $departments_id->updated_by = \Auth::user()->id;
            
            if($departments_id){

                $departments_id->update();
                $response['message'] = 'Departments Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
         }else{
                $dept_counter = 0;
                if($request->has('dept_counter')){
                    $dept_counter = 1;
                }
                $data['dept_counter'] = $dept_counter;
                $data['created_by']= $dept_created_by;
                $save_department = Departments::create($data);
                $response['message'] = 'department Saved Successfully!';
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

    public function get_departments(){
        
        // $departments = Departments::get();
        $departments = DB::table('departments')->get();
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['departments']= $departments;
        
        return response()->json($response);
        
    }

    public function showDepartmentPermission(Request $request) {
        $departments = DB::table('departments')->get();
        foreach($departments as $dep) {
            $permissions = DB::table("dept_permissions")->where("staff_id",$request->staff_id)->get();
            foreach($permissions as $per) {
                if($dep->id == $per->dept_id) {
                    $dep->dept_permission = DB::table("dept_permissions")
                        ->where("staff_id",$request->staff_id)
                        ->where("dept_id",$dep->id)
                        ->get();
                }
            }
        }

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['permissions']= $departments;
        
        return response()->json($response);
    }
    
    public function save_priorities(Request $request){

        $data = $request->all();
        
        $response = array();
        $priority_created_by= \Auth::user()->id;;
        try{
            if(empty(trim($data['name'], " "))) {
                $response['message'] = 'Name is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            if(!empty($request->priority_id)){
                
                    $priorities_id = TicketPriority::where('id',$request->priority_id)->first();
                    $priorities_id->name = $data['name'];
                    $priorities_id->priority_color = $data['priority_color'];
                    $priorities_id->updated_by = \Auth::user()->id;
                
                if($priorities_id){
    
                    $priorities_id->update();
                    $response['message'] = 'Priority Update Successfully!';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    return response()->json($response);
    
                }
             }else{
                $data['created_by']= $priority_created_by;
                $save_department = TicketPriority::create($data);
                $response['message'] = 'Priority Added Successfully!';
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

    public function get_priorities(){
        // $priorities = TicketPriority::get();
        $priorities = DB::table('ticket_priorities')->get();
            
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['priorities']= $priorities;
        
        return response()->json($response);
    }

    public function save_status(Request $request){
        if(empty(trim($request->name, " "))) {
            $response['message'] = 'Name is empty!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

        if(empty($request->status_id)) {
            $status_counter = 0;
            if($request->has('status_counter')){
                $status_counter = 1;
            }

            TicketStatus::create([
                "name" => $request->name,
                "department_id" => $request->department_id,
                "color" => $request->status_color,
                "seq_no" => $request->seq_no,
                'slug' => $request->slug,
                "status_counter" => $status_counter,

            ]);

            $response['message'] = 'Status Added Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }else{
            $status = TicketStatus::where('id',$request->status_id)->first();

            if($status) {
                $status_counter = 0;
                if($request->has('status_counter')){
                    $status_counter = 1;
                }
                $status->name = $request->name;
                $status->department_id = $request->department_id;
                $status->color = $request->status_color;
                $status->seq_no = $request->seq_no;
                $status->slug = $request->slug;
                $status->status_counter = $status_counter;

                $status->created_by = \Auth::user()->id;
                $status->save();

                $response['message'] = 'Status Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
            }
        }
    }

    public function get_statuses(){
        // $statuses = TicketStatus::get();
        $statuses = DB::table('ticket_statuses')->get();
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['statuses']= $statuses;
        
        return response()->json($response);
    }

    public function save_type(Request $request){

        $data = $request->all();
        
        $response = array();
        $createdby= \Auth::user()->id;
        
        try{
            if(empty(trim($data['name'], " "))) {
                $response['message'] = 'Name is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            if(!empty($request->type_id)){
                
                $types_id = TicketType::where('id',$request->type_id)->first();
                $types_id->name = $data['name'];
                $types_id->updated_by = \Auth::user()->id;
            
            if($types_id){

                $types_id->update();
                $response['message'] = 'Status Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
         }else{
                $data['created_by']= $createdby;
                $save_department = TicketType::create($data);
                $response['message'] = 'Ticket Type Added Successfully!';
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

    public function save_customer_type(Request $request){

        $data = $request->all();
        
        $response = array();
        $createdby= \Auth::user()->id;
        
        try{
            if(empty(trim($data['name'], " "))) {
                $response['message'] = 'Type name is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            if(!empty($request->customer_type_id)){
                
                $types_id = CustomerType::where('id',$request->customer_type_id)->first();
                $types_id->name = $data['name'];
                $types_id->updated_by = \Auth::user()->id;
            
            if($types_id){

                $types_id->update();
                $response['message'] = 'Status Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
         }else{
                $data['created_by']= $createdby;
                $save_department = CustomerType::create($data);
                $response['message'] = 'Customer Type Added Successfully!';
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

    public function save_dispatch_status(Request $request){

        $data = $request->all();
        
        $response = array();
        $createdby= \Auth::user()->id;
        
        try{
            if(empty(trim($data['name'], " "))) {
                $response['message'] = 'Status name is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            if(!empty($request->dispatch_status_id)){
                
                $types_id = DispatchStatus::where('id',$request->dispatch_status_id)->first();
                $types_id->name = $data['name'];
                $types_id->updated_by = \Auth::user()->id;
            
            if($types_id){

                $types_id->update();
                $response['message'] = 'Status Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
         }else{
                $data['created_by']= $createdby;
                $save_department = DispatchStatus::create($data);
                $response['message'] = 'Dispatch Status Added Successfully!';
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


    public function save_project_type(Request $request){

        $data = $request->all();
        
        $response = array();
        $createdby= \Auth::user()->id;
        
        try{
            if(empty(trim($data['name'], " "))) {
                $response['message'] = 'Project type name is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            if(!empty($request->project_type_id)){
                
                $types_id = ProjectType::where('id',$request->project_type_id)->first();
                $types_id->name = $data['name'];
                $types_id->updated_by = \Auth::user()->id;
            
            if($types_id){

                $types_id->update();
                $response['message'] = 'Task Type Updated Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }
         }else{
                $data['created_by']= $createdby;
                $save_department = ProjectType::create($data);
                $response['message'] = 'Project Task Type Added Successfully!';
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

    public function get_types(){
        // $types = TicketType::get();
        $types = DB::table('ticket_types')->get();
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['types']= $types;
        
        return response()->json($response);
    }

    public function get_customer_types(){
        // $types = TicketType::get();
        $types = DB::table('customer_types')->get();
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['types']= $types;
        
        return response()->json($response);
    }

    public function get_dispatch_status(){
        // $types = TicketType::get();
        $types = DB::table('dispatch_status')->get();
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['types']= $types;
        
        return response()->json($response);
    }

    public function get_project_type(){
        // $types = TicketType::get();
        $types = DB::table('project_type')->get();
        
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['types']= $types;
        
        return response()->json($response);
    }

    public function delete_department(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del_department = Departments::destroy($data);
        $response['message'] = 'Department Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }

    public function delete_priority(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del__priority = TicketPriority::destroy($data);
        $response['message'] = 'Priority Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }

    public function delete_status(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del_status = TicketStatus::destroy($data);
        $response['message'] = 'Status Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }

    public function delete_type(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del_type = TicketType::destroy($data);
        $response['message'] = 'Type Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }

    public function delete_customer_type(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del_type = CustomerType::destroy($data);
        $response['message'] = 'Type Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }
    
    public function delete_dispatch_status(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del_type = DispatchStatus::destroy($data);
        $response['message'] = 'Status Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }

    public function delete_project_type(Request $request){
        
        $data = $request->all();
        $response = array();
    
        $del_type = ProjectType::destroy($data);
        $response['message'] = 'Project Type Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    
        
    }

    public function ticket_format(Request $request){
            
            $data = $request->all();
            $response = array();
            
            
            
            try{

                $ticketSettings = TicketSettings::where('tkt_key','ticket_format')->first();

                if($ticketSettings){
                                        
                    TicketSettings::where('tkt_key','ticket_format')->update([
                        "tkt_key" => 'ticket_format',
                        "tkt_value" => $request->ticket_format,
                        "updated_by" => \Auth::user()->id,
                    ]);


                    $response['message'] = 'Ticket ID Format Update Successfully!';
                    $response['status_code'] = 200;
                    $response['success'] = true;
                    return response()->json($response);

                }else{

                    TicketSettings::create([
                        "tkt_key" => 'ticket_format',
                        "tkt_value" => $request->ticket_format,
                        "created_by" => \Auth::user()->id,
                    ]);


                    $response['message'] = 'Ticket ID Format Saved Successfully!';
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

    public function saveColorSettings(Request $request){
        
        try{
            $action = $request->input('action');
            $color = $request->input('color');
            $response = [];
            $brand_settings = BrandSettings::first();
            
            if($brand_settings){
                if($action == 'textDark'){
                    $brand_settings->text_dark = $color;
                }else if($action == 'textLight'){
                    $brand_settings->text_light = $color;
                }else if($action == 'bgLight'){
                    $brand_settings->bg_light = $color;
                }else if($action == 'bgDark'){
                    $brand_settings->bg_dark = $color;
                }

                $brand_settings->save();
                $response['message'] = 'Color Settings Saved Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;

                return response()->json($response);
            }else{
                $response['message'] = 'Color Settings Not Found';
                $response['status_code'] = 404;
                $response['success'] = false;
                return response()->json($response,404);
            }
        }catch(Exception $err){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response,500);
        }
    }

    public function saveSystemDateAndTime(Request $request) {
        $response = array();
        
        $date = $request->sys_dt_frmt;
        $time = $request->sys_tm_frmt;
        $timezone = $request->timezone;

        $datetime = array("sys_dt_frmt" => $date ,"sys_tm_frmt" =>$time , "sys_timezone" => $timezone);
        
        $setting = SystemSetting::where("sys_key","=","sys_dt_frmt")->first();

        if(!$setting ) {

            foreach($datetime as $key=>$value) {
                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = $key;
                $sys_setting->sys_value = $value;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();
            }

            $response['message'] = 'System Setting Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response); 
            
        }else{

            foreach($datetime as $key=>$value) {

                SystemSetting::where('sys_key',$key)->delete();

                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = $key;
                $sys_setting->sys_value = $value;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();
            }

            $response['message'] = 'System Setting Updated Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response); 
        }       

    }
    //Response Template

    public function addResponseTemplate(Request $request) {
        
        $data = array(
            "title" => $request->title,
            "cat_id" => $request->cat_id,
            "temp_html" => $request->temp_html,
            "view_access" => $request->view_access,
            "created_by" => auth()->id(),
        );

        if($request->res_id != null && $request->res_id != " ") {
            $data['updated_by'] = auth()->id();
            ResponseTemplate::where('id',$request->res_id)->update($data);
            $title = 'Updated';
        }else{
            ResponseTemplate::create($data);
            $title = 'Saved';
        }

        $response['message'] = 'Response Template '.$title.' Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function showResponseTemplate(Request $request) {  
        $data = ResponseTemplate::all();

        $response['message'] = 'Category Saved Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $data;
        return response()->json($response);
    }

    public function updateResponseTemplate(Request $request) {
    
        $sla = ResponseTemplate::where("id",$request->id)->first();
        $sla->title = $request->title;
        $sla->cat_id = $request->cat_id;
        $sla->temp_html = $request->temp_html;
        $sla->view_access = $request->view_access;

        $sla['updated_by'] = \Auth::user()->id;
        $sla->save();

        $response['message'] = 'Category Updated Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    // delete response template
    public function deleteResponseTemplate(Request $request) {
        $res = ResponseTemplate::findOrFail($request->id);
        if($res) {
            $res->delete();

            return response()->json([
                'message' => 'Response Template Deleted Successfully',
                'status_code' => 200,
                'success' => true,
            ]);
        }else{
            return response()->json([
                'message' => 'Something went wrong!',
                'status_code' => 500,
                'success' => false,
            ]);
        }
    }

    /// Response Category Template
    public function addResponseCategory(Request $request) {
        if(empty(trim($request->name, " "))) {
            $response['message'] = 'Category name is empty!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
        $sla = new RestemplateCat();
        $sla->name = $request->name;
        $sla['created_by'] = \Auth::user()->id;

        $sla->save();

        $response['message'] = 'Category Saved Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function getallCatResponse() {

        $data = RestemplateCat::where("is_deleted","=",0)->get();

        $response['message'] = 'CAtegory Saved Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $data;
        return response()->json($response);
    }

    public function updateCatResponse(Request $request) {
        if(empty(trim($request->name, " "))) {
            $response['message'] = 'Category name is empty!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }

        $sla = RestemplateCat::where("id",$request->id)->first();
        $sla->name = $request->name;

        $sla['updated_by'] = \Auth::user()->id;
        $sla->save();

        $response['message'] = 'Category Updated Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function delete_catResponse(Request $request) {
        $sla = RestemplateCat::where("id",$request->id)->first();
        $sla->is_deleted = 1;
        $sla->save();
        
        $response['message'] = 'Record Deleted Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }
    // SLA

    public function addSLA(Request $request) {
        // if($request->is_default == 1){

        //     $sla = SlaPlan::where('is_default',1)->first();
        //     if($sla){
        //         $sla->is_default = 0;
        //         $sla->save();
        //     }

        //     $sla2 = new SlaPlan();

        //     $sla2->title = $request->title;
        //     $sla2->reply_deadline = $request->reply_deadline;
        //     $sla2->due_deadline = $request->due_deadline;
        //     $sla2->sla_status = $request->sla_status;
        //     $sla2->is_default = $request->is_default;
        //     $sla2->save();

        //     $response['message'] = 'SLA Saved Successfully';
        //     $response['status_code'] = 200;
        //     $response['success'] = true;
        //     return response()->json($response);
        // }
        // else{
            if(empty(trim($request->title, " "))) {
                $response['message'] = 'SLA title is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            $sla = new SlaPlan();
            $sla->title = $request->title;
            $sla->reply_deadline = $request->reply_deadline;
            $sla->due_deadline = $request->due_deadline;
            $sla->sla_status = $request->sla_status;
            // $sla->is_default = $request->is_default;
            $sla->save();
    
            $response['message'] = 'SLA Saved Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        // }
     

    }

    // get all sla
    public function getAllSLA() {

        $data = SlaPlan::where("is_deleted","=",0)->get();

        $response['message'] = 'SLA Saved Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $data;
        return response()->json($response);
    }

    public function deleteSLA(Request $request) {
        $sla = SlaPlan::where("id",$request->id)->first();
        $sla->is_deleted = 1;
        $sla->save();

        $response['message'] = 'Record Deleted Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function updateSLA(Request $request) {
        // if($request->is_default == 1){

        //     $sla = SlaPlan::where('is_default',1)->first();
        //     $sla->is_default = 0;
        //     $sla->save();


        //     $sla2 = SlaPlan::where("id",$request->id)->first();
        //     $sla2->title = $request->title;
        //     $sla2->reply_deadline = $request->reply_deadline;
        //     $sla2->due_deadline = $request->due_deadline;
        //     $sla2->sla_status = $request->sla_status;
        //     $sla2->is_default = $request->is_default;
        //     $sla2->save();
    
        //     $response['message'] = 'SLA Updated Successfully';
        //     $response['status_code'] = 200;
        //     $response['success'] = true;
        //     return response()->json($response);
        // }
        // else{
            if(empty(trim($request->title, " "))) {
                $response['message'] = 'SLA title is empty!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            $sla = SlaPlan::where("id",$request->id)->first();
            $sla->title = $request->title;
            $sla->reply_deadline = $request->reply_deadline;
            $sla->due_deadline = $request->due_deadline;
            $sla->sla_status = $request->sla_status;
            // $sla->is_default = $request->is_default;
            $sla->save();
    
            $response['message'] = 'SLA Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        // }
     
      

    }

    public function SLASetting(Request $request) {

        $current_date = Carbon::now();
        
        $data = array(  
            "reply_due_deadline" => $request->reply_due_deadline,
            "reply_due_deadline_when_adding_ticket_note" => $request->reply_due_deadline_when_adding_ticket_note,
            "default_reply_and_resolution_deadline" =>$request->default_reply_and_resolution_deadline,
            "default_reply_time_deadline" => $request->default_reply_time_deadline,
            "default_resolution_deadline" => $request->default_resolution_deadline,
            "overdue_ticket_background_color" =>$request->overdue_ticket_background_color,
            "overdue_ticket_text_color" =>$request->overdue_ticket_text_color,
        );

        $keys = array(  
            "reply_due_deadline" ,
            "reply_due_deadline_when_adding_ticket_note",
            "default_reply_and_resolution_deadline" ,
            "default_reply_time_deadline" ,
            "default_resolution_deadline",
            "overdue_ticket_background_color",
            "overdue_ticket_text_color",
        );
        
        $setting = TicketSettings::whereIn('tkt_key',$keys)->get();

        if($setting) {


            TicketSettings::whereIn('tkt_key',$keys)->delete();

            foreach($data as $key=>$value) {
                $sys_setting = new TicketSettings();
                $sys_setting->tkt_key = $key;
                $sys_setting->tkt_value = $value;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->created_at = $current_date;
                $sys_setting->updated_at = $current_date;
                $sys_setting->save();
            }

            $response['message'] = 'SLA Update Saved Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }else{
            
            foreach($data as $key=>$value) {
                $sys_setting = new TicketSettings();
                $sys_setting->tkt_key = $key;
                $sys_setting->tkt_value = $value;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->created_at = $current_date;
                $sys_setting->updated_at = $current_date;
                $sys_setting->save();
            }

            $response['message'] = 'SLA Setting Saved Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }
    }

    public function customerSetting(Request $request) {

        $data = array(  
            "customer_delete" => $request->customer_delete,
            "customer_disable" => $request->customer_disable,
            "customer_create" => $request->customer_create,
            "customer_login" => $request->customer_login,
        );

        $setting = SystemSetting::where('sys_key','customer_delete')->first();

        if(!$setting) {

            foreach($data as $key=>$value) {
                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = $key;
                $sys_setting->sys_value = $value;
                $sys_setting->accounts_from_email = $request->accounts_from_email;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();
            }

            $response['message'] = 'Customer Setting Saved Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }else{

            foreach($data as $key=>$value) {

                SystemSetting::where('sys_key',$key)->delete();

                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = $key;
                $sys_setting->sys_value = $value;
                $sys_setting->accounts_from_email = $request->accounts_from_email;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();
            }

            $response['message'] = 'Customer Setting Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }

    }

    public function SaveEmailRecapNotification(Request $request) {

        $setting = SystemSetting::where('sys_key','email_recap_notifications')->first();

        if($setting) {

            SystemSetting::whereIn('sys_key',['emails','email_recap_notifications','check_off_emails'])->delete();

            foreach($request->data as $key=>$value) {
                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = $key;
                $sys_setting->sys_value = $value;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();
            }

            $response['message'] = 'System Setting Updated Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response); 

        }else{

            foreach($request->data as $key=>$value) {
                $sys_setting = new SystemSetting();
                $sys_setting->sys_key = $key;
                $sys_setting->sys_value = $value;
                $sys_setting->created_by =\Auth::user()->id;
                $sys_setting->save();
            }

            $response['message'] = 'System Setting Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response); 

        }
    }

    public function sendRecapsEmails(Request $request) {

        $tasks = Tasks::where('start_date' , '2021-07-06')->where('is_deleted',0)->get();
        return $tasks;

    }

    public function showAllNotifications(){

        // if( auth()->user()->user_type == 1) {
        //     $notifications = Notification::whereNotNull('noti_title')->orderbyDesc('id')->get();
        // }else{
            $notifications = Notification::whereNotNull('noti_title')->where('receiver_id', auth()->id() )->orderbyDesc('id')->paginate(50);
        // }

        Notification::where('receiver_id', auth()->id() )->update(['read_at' => Carbon::now()]);
        
        return view('notification.notification-new',compact('notifications'));
    }

    public function get_all_counts(){

        $counts = DB::select("SELECT departments.id,departments.name,departments.dept_counter , ticket_statuses.id as sts_id , ticket_statuses.name as sts_name,ticket_statuses.status_counter , (SELECT COUNT(*) from tickets WHERE tickets.is_pending = 0 AND tickets.dept_id = departments.id AND tickets.trashed = 0 And tickets.status <> 33) as tkt_dept_count , (SELECT COUNT(*) from tickets WHERE tickets.is_pending = 0 AND ticket_statuses.id = tickets.status AND tickets.trashed = 0 AND  tickets.dept_id = departments.id) as tkt_sts_count from departments LEFT JOIN ticket_statuses on find_in_Set(departments.id,ticket_statuses.department_id) LEFT JOIN deptartment_assignments on deptartment_assignments.dept_id = departments.id WHERE deptartment_assignments.user_id = ".\Auth::user()->id);
        $response['message'] = 'Data';
        $response['counts'] = $counts;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }


    public function SaveBannedUser(Request $request){

        $data = array(
            "email" => $request->email,
            "banned_by" => \Auth::user()->id,
        );

        if($request->edituser != null && $request->edituser != " ") {
            
            SpamUser::where('id',$request->edituser)->update($data);
            $title = 'Updated';
        }else{
            SpamUser::create($data);
            $title = 'Saved';
        }

        $response['message'] = 'User '.$title.' Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }
    
    public function get_banned_users(){
        
        $banned_users = SpamUser::orderBy('id', 'DESC')->with('banned_by_user')->get();

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['banned_users']= $banned_users;
        
        return response()->json($response);
    }


    public function delete_banned_users(Request $request) {
        // return dd($request->all());
        $users   = SpamUser::whereIn('id',$request->id)->get();
        if( count($users) > 0) {
            SpamUser::whereIn('id',$request->id)->delete();
            return response()->json([
                "status" => 200 , 
                "success" => true ,
                "message" => "Banned User deleted Successfully",
            ]);
        }else{

            return response()->json([
                "status" => 500 , 
                "success" => false ,
                "message" => "Something Went Wrong",
            ]);
        }

    }

}
