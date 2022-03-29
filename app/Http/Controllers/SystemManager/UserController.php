<?php

namespace App\Http\Controllers\SystemManager;
use App\Http\Controllers\Controller;
use App\Models\BrandSettings;
use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Support\Facades\Crypt;
use App\Models\TicketView;
use App\Models\Customer;
use App\Models\DepartmentAssignments;
use App\Models\Departments;
use App\Models\DepartmentPermissions;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\StaffLeaves;
use App\Models\TicketType;
use Illuminate\Support\Facades\File;
use App\Models\TicketSettings;
use App\Models\SystemSetting;
use App\Models\SystemManager\StaffSchedule;
use App\Http\Controllers\SystemManager\MailController;
use App\Models\Tags;
use App\Models\Tickets;
use App\Models\StaffProfile;
use Spatie\Permission\Models\Role;
use App\Models\Usercertification;
use App\Models\Integrations;
use App\Models\Tasks;
use App\Models\UserDocuments;
use App\Models\StaffAttendance;
use Exception;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Hash;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
// use Yajra\Datatables\Datatables;

class UserController extends Controller
{
    public static $connection = '{mylive-tech.com:995/pop3/ssl}';
    public static $mailserver_hostname = 'mylive-tech.com';
    public static $mailserver_username = 'support2@mylive-tech.com';
    public static $mailserver_password = 'y7.v9jLy!JLG9!s';

    // private $permissions_list = ['d_t_canreply'=>'Reply to tickets', 
    // 'd_t_canforward'=>'Forward tickets', 
    // 'd_t_canfollowup'=>'Schedule ticket follow-ups', 
    // 'd_t_canbilling'=>'Time tracking and billing notes'];

    private $permissions_list = [
        'd_t_canreply'=>'Reply to tickets', 
        'd_t_canforward'=>'Forward tickets', 
        'd_t_canfollowup'=>'Schedule ticket follow-ups', 
        'd_t_canbilling'=>'Time tracking and billing notes',
        'd_t_canassignment'=>'Ticket Assignment Email Alert',
        'd_t_cannotealerts'=>'Note Alerts',
        'd_t_cantktfollowalerts'=>'Ticket Followup Alerts'
    ];

    public function __construct(){
        $this->middleware('auth');

        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }

    public function index(){
        $tags = Tags::all();
        $roles = Role::all();

        $users = User::with('staffProfile')
                    ->where('is_deleted', 0)
                    ->where('is_support_staff',0)
                    ->whereNotIn('user_type', [4 , 5])
                    ->orderByDesc('id')->get()->toArray();

        // if(array_key_exists('rolesad' , $users[0]->toArray() )) {
        //     dd("has");
        // }else{
        //     dd("not");
        // }
        
        // dd($users->toArray());

        return view('system_manager.staff_management.index-new', get_defined_vars());
    }
    public function new(){
        $tags = Tags::all();
        $roles = Role::all();
        // $roles = Role::where('id','!=','1')->get();

        return view('system_manager.staff_management.index-new',compact('tags','roles'));
    }
    public function insertUsers(Request $request) {
        $data = $request->all();
        $response = array();
        $user = [];

        try{
            if(isset($request->staff_id)) {
                $validatorRule = [];
                // $user = User::with('staffProfile')->findOrFail($request->input('staff_id'));
                $user = User::findOrFail($request->input('staff_id'));
                if($user->name != $data['full_name']) {
                    $validatorRule['full_name'] = ['required', 'string', 'max:255'];
                } else if($user->email != $data['email']) {
                    $validatorRule['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
                }
                // elseif($data['password']) {
                //     $validatorRule['password'] = ['required', 'string', 'min:8'];
                // }
                $validator = Validator::make($request->all(), $validatorRule);
                if ($validator->fails()) {
                    $response['message'] = $validator->messages()->first();
                    $response['status_code'] = 200;
                    $response['success'] = false;
                    return response()->json($response);
                } else {
                    $user->name = $data['full_name'];
                    $user->email = $data['email'];
                    $user->status = $data['status'];
                    $user->sms = $data['sms'];
                    $user->whatsapp = $data['whatsapp'];
                    $user->created_by = \Auth::user()->id;

                    // $user->staffProfile->role_id = $data['role_id'];
                    
                    $user->staffProfile->phone = $data['phone_number'];
                    if(!empty($data['tags'])) {
                        $user->staffProfile->tags = $data['tags'];
                        $user->tags = $data['tags'];
                    }
                    // if($data['password'] != '' || $data['password'] != null){
                    //     $user->password = bcrypt($data['password']);
                    // }
                    if($request->has('user_photo')) {
                        
                        $image = $request->file('user_photo');
                        $filenamewithextension = $data['user_photo']->getClientOriginalName();
                        //get filename without extension
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                        //get file extension
                        $extension = $data['user_photo']->getClientOriginalExtension();

                        if($user->profile_pic){
                            $ext = pathinfo($user->profile_pic, PATHINFO_EXTENSION);
                            $ext = basename($user->profile_pic, '.'.$ext);
                            $user->profile_pic = $ext;
                        }

                        $filenametostore = $user->profile_pic ? $user->profile_pic.'.'.$extension : time().'.'.$extension;
                        // $filePath = public_path('');
                        // echo $extension;
                        
                        $image->move('files/user_photos/', $filenametostore);
                        $user->profile_pic = $filenametostore;
                        // return response()->json($user);
                    }
                    $user->user_type = $data['role_id'];

                    $user->save();
                    $user->staffProfile->save();
                    $response['message'] = 'User Update Successfully!';
                }
            } else {
                $check_user = User::where('email', $data['email'])->where('is_deleted', 0)->first();
                if(!empty($check_user)) {
                    $response['message'] = 'Email Already Taken try another one!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }

                $mailer = new MailController();

                if($check_user) {
                    User::where('email',$data['email'])->where('is_deleted',1)->delete();
                    
                    $data['alt_pwd'] = Crypt::encryptString($data['password']);
                    $data['password'] = bcrypt($data['password']);
                    $data['name'] = $data['full_name'];
                    $data['created_by'] = \Auth::user()->id;

                    if($request->has('user_photo')){

                        $image = $request->file('user_photo');
                        $filenamewithextension = $data['user_photo']->getClientOriginalName();
                        //get filename without extension
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                        //get file extension
                        $extension = $data['user_photo']->getClientOriginalExtension();

                        $filenametostore = time().'.'.$extension;

                        $image->move('files/user_photos/', $filenametostore);
                        $data['profile_pic'] = $filenametostore;
                    }

                    $data['user_type'] = $data['role_id'];
                    $save_users = User::create($data);

                    $profile_data['user_id'] = $save_users->id;
                    $profile_data['phone_number'] = $data['phone_number'];
                    $profile_data['role_id'] = $data['role_id'];
                    
                    $profile_data['tags'] = !empty($data['tags']) ? $data['tags'] : null;
                    
                    $user_profile = StaffProfile::create($profile_data);
                    
                    if($user_profile) {
                        $response['message'] = 'User Saved Successfully!';
                        // email send code
                        $mailer->UserRegisteration($data['email']);

                    }else{
                        $response['message'] = 'Some error ocurred while saving!';
                    }
                }else{

                    $data['alt_pwd'] = Crypt::encryptString($data['password']);
                    $data['password'] = bcrypt($data['password']);
                    $data['name'] = $data['full_name'];
                    $data['created_by'] = \Auth::user()->id;

                    if($request->has('user_photo')){

                        $image = $request->file('user_photo');
                        $filenamewithextension = $data['user_photo']->getClientOriginalName();
                        //get filename without extension
                        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                        //get file extension
                        $extension = $data['user_photo']->getClientOriginalExtension();

                        $filenametostore = time().'.'.$extension;

                        $image->move('files/user_photos/', $filenametostore);
                        $data['profile_pic'] = $filenametostore;
                    }

                    $data['user_type'] = $data['role_id'];
                    $save_users = User::create($data);

                    $profile_data['user_id'] = $save_users->id;
                    $profile_data['phone_number'] = $data['phone_number'];
                    $profile_data['role_id'] = $data['role_id'];
                    if($data['tags'] != '' || $data['tags'] != null){
                        $profile_data['tags'] = $data['tags'];
                    }else{
                        $profile_data['tags'] = null;
                    }
                    $user_profile = StaffProfile::create($profile_data);
                    
                    if($user_profile){
                        $response['message'] = 'User Saved Successfully!';
                        // email send code
                        
                        $mailer->UserRegisteration($data['email']);
                    }else{
                        $response['message'] = 'Some error ocurred while saving!';
                    }
                }
            }

            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }catch(Exception $e){
            $response['message'] = $e->getMessage();//'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
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

    // public function UserRegisterationParser($data_list, $template , $user,$password,$new) {
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
    //                 $template = str_replace('{User-Name}',"Name : " . $data['values']['name'], $template);
    //             }
    
    //             if($data['module'] == 'User' && str_contains($template, '{User-Email}')) {
    //                 $template = str_replace('{User-Email}', "Email : " . $data['values']['email'], $template);
    //             }
    
    //             if($data['module'] == 'User' && str_contains($template, '{User-Password}')) {
    //                 $template = str_replace('{User-Password}', "Email : " . $password, $template);
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
    //                 $template = str_replace('{User-Name}', " ", $template);
    //             }
    
    //             if($data['module'] == 'User' && str_contains($template, '{User-Email}')) {
    //                 $template = str_replace('{User-Email}', " ", $template);
    //             }
    
    //             if($data['module'] == 'User' && str_contains($template, '{User-Password}')) {
    //                 $template = str_replace('{User-Password}', " ", $template);
    //             }
    
    //             if($data['module'] == 'User' && str_contains($template, '{Company-Logo}')) {
    //                 $template = str_replace('{Company-Logo}', $img, $template);
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

    public function sendMail($subject, $recipient,$body, $recipient_name, $reply = false) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->SMTPAuth  =  true;

            $mail->Host      =  self::$mailserver_hostname;
            $mail->Username  =  self::$mailserver_username;
            $mail->Password  =  self::$mailserver_password;

            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ]
            ];

            $mail->setFrom($mail->Username);
            $mail->addAddress($recipient, $recipient_name);

            //Recipients
            if ($reply) {
                $mail->addReplyTo($recipient, $subject);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = '';

            $mail->send();
        } catch (Exception $e) {
            throw new Exception($e);
        }
    }

    public function update_password(Request $request){
        $data = $request->all();
        $response = array();
        
        if($request->input('staff_id')){
            $validatorRule = [];
            $user = User::findOrFail($request->input('staff_id'));
            if($data['password']){
                $validatorRule['password'] = ['required', 'string', 'min:8'];
            }
            $validator = Validator::make($request->all(), $validatorRule);
            if ($validator->fails()) {
                $response['message'] = $validator->messages()->first();
                $response['status_code'] = 200;
                $response['success'] = false;
                return response()->json($response);
            }
            else{
                $user->updated_by = \Auth::user()->id;
                if($data['password'] != '' || $data['password'] != null){
                    $user->password = bcrypt($data['password']);
                    $user->alt_pwd = Crypt::encryptString($data['password']);
                }
                $user->save();
                $response['message'] = 'User Password Updated Successfully!';
            }
        }else{
            $response['message'] = 'Staff Id Missing!';
        }
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function get_users(Request $request){
        $users = User::with('staffProfile')->where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->orderBy('id','desc')->get();

        foreach($users as $user) {
            $user->role = Role::where('id',$user->user_type)->first();

            $tags_arr = [];
            $tags_arr = explode(",",$user->tags);

            $user->user_tags = Tags::whereIn('id',$tags_arr)->get();
        }


        // if ($request->ajax()) {
        //     return Datatables::of($users)->make(true);
        // }
        
        $response['message'] = 'Users List';
        $response['data'] = $users;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }
    
    public function my_profile(Request $request){
        if($request->wantsJson()){
            $profile = User::with('staffProfile')->where('email',$request->input('email'))->first();
            return response()->json($profile);
        }
        $id = auth()->id();
        $profile = User::with('staffProfile')->where('id',$id)->first();
        if(!empty($profile->alt_pwd)) {
            $profile->alt_pwd = Crypt::decryptString($profile->alt_pwd);
        }

        $tickets = Tickets::select("*")
            ->where('assigned_to',$id)
            ->where('is_deleted', 0)->orderBy('updated_at', 'desc')
            ->get();
        
        // $tickets = DB::Table('tickets')
        // ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        // ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        // ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        // ->join('ticket_types','ticket_types.id','=','tickets.type')
        // ->join('departments','departments.id','=','tickets.dept_id')
        // ->join('users','users.id','=','tickets.assigned_to')
        // ->where('tickets.assigned_to',$id)
        // ->get();

        
        $certificates = DB::table("user_certification")->where("user_id","=",$id)->get();
        $docs = DB::table("user_docs")->where("user_id","=",$id)->get();
        $staff_att_data = StaffAttendance::with('user_clocked')->where('user_id',$id)->get();

        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();
        $customers = Customer::all();
        $users = User::all();
        // $departments = Departments::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();

        $staff_state = DB::Table('states')->where('id',"=",$profile->state)->first();
        $tasks =  Tasks::with('taskCreator')->with('taskProject')->where('assign_to',$id)->where('task_status','!=','success')->where('task_status','!=','Select')->where('is_deleted',0)->get();

        foreach($tasks as $task) {
            $task->assign_to = DB::Table("users")->where("id","=",$id)->first();
        }

        $google_api = 0;
        $google = Integrations::where("slug","google-api")->where('status', 1)->first();
        if(!empty($google)) {
            $google_api  = $google->details != null && $google->details != '' ?  1 :  0 ;
            $google = json_decode($google->details, true);
        }

        $countries = [];
        if($google_api === 0) $countries = DB::Table('countries')->get();

        $departments = $this->listPermissions($id);
        
        $date_format = Session('system_date');

        $general_staff_note = SystemSetting::where('sys_key', 'general_staff_note')->first();
        if(!empty($general_staff_note)) $general_staff_note = $general_staff_note->sys_value;
        $note_for_selected_staff = SystemSetting::where('sys_key', 'note_for_selected_staff')->select('sys_value')->first();
        if(!empty($note_for_selected_staff)) $note_for_selected_staff = $note_for_selected_staff->sys_value;
        $selected_staff_members = SystemSetting::where('sys_key', 'selected_staff_members')->select('sys_value')->first();
        if(!empty($selected_staff_members)) $selected_staff_members = explode(',', $selected_staff_members->sys_value);
        else $selected_staff_members = array();
    
        $ticketView = TicketView::where('user_id' , $id)->first();

        return view('system_manager.staff_management.user_profile_new', get_defined_vars());
        // return view('system_manager.staff_management.user_profile',compact('id', 'priorities','date_format','types','departments','statuses','customers','users','ticket_format','docs', 'tickets','certificates','profile','staff_att_data','countries','tasks','staff_state','google', 'google_api','selected_staff_members', 'note_for_selected_staff', 'general_staff_note'));
    }

    public function profile($id) {
        $profile = User::with('staffProfile')->where('id',$id)->first();
        if(!empty($profile->alt_pwd)) {
            $profile->alt_pwd = Crypt::decryptString($profile->alt_pwd);
        }

        $tickets = Tickets::select("*")
            ->where('assigned_to',$id)
            ->where('is_deleted', 0)->orderBy('updated_at', 'desc')
            ->get();
        
        // $tickets = DB::Table('tickets')
        // ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        // ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        // ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        // ->join('ticket_types','ticket_types.id','=','tickets.type')
        // ->join('departments','departments.id','=','tickets.dept_id')
        // ->join('users','users.id','=','tickets.assigned_to')
        // ->where('tickets.assigned_to',$id)
        // ->get();

        $certificates = DB::table("user_certification")->where("user_id","=",$id)->get();
        $docs = DB::table("user_docs")->where("user_id","=",$id)->get();
        $staff_att_data = StaffAttendance::with('user_clocked')->where('user_id',$id)->get();

        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();
        $customers = Customer::all();
        $users = User::all();
        // $departments = Departments::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();

        $staff_state = DB::Table('states')->where('id',"=",$profile->state)->first();
        $tasks =  Tasks::with('taskCreator')->with('taskProject')->where('assign_to',$id)->where('task_status','!=','success')->where('task_status','!=','Select')->where('is_deleted',0)->get();

        foreach($tasks as $task) {
            $task->assign_to = DB::Table("users")->where("id","=",$id)->first();
        }

        $google_api = 0;
        $google = Integrations::where("slug","google-api")->where('status', 1)->first();
        if(!empty($google)) {
            $google_api  = $google->details != null && $google->details != '' ?  1 :  0 ;
            $google = json_decode($google->details, true);
        }

        $countries = [];
        if($google_api === 0) $countries = DB::Table('countries')->get();

        $departments = $this->listPermissions($id);
        
        $date_format = Session('system_date');

        $general_staff_note = SystemSetting::where('sys_key', 'general_staff_note')->first();
        if(!empty($general_staff_note)) $general_staff_note = $general_staff_note->sys_value;
        $note_for_selected_staff = SystemSetting::where('sys_key', 'note_for_selected_staff')->select('sys_value')->first();
        if(!empty($note_for_selected_staff)) $note_for_selected_staff = $note_for_selected_staff->sys_value;
        $selected_staff_members = SystemSetting::where('sys_key', 'selected_staff_members')->select('sys_value')->first();
        if(!empty($selected_staff_members)) $selected_staff_members = explode(',', $selected_staff_members->sys_value);
        else $selected_staff_members = array();  

        $ticketView = TicketView::where('user_id' , $id)->first();
          
        return view('system_manager.staff_management.user_profile_new', get_defined_vars());
    }

    public function newProfile($id) {
        $profile = User::with('staffProfile')->where('id',$id)->first();
        if(!empty($profile->alt_pwd)) {
            $profile->alt_pwd = Crypt::decryptString($profile->alt_pwd);
        }

        $tickets = Tickets::select("*")
            ->where('assigned_to',$id)
            ->where('is_deleted', 0)->orderBy('updated_at', 'desc')
            ->get();
        
        // $tickets = DB::Table('tickets')
        // ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        // ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        // ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        // ->join('ticket_types','ticket_types.id','=','tickets.type')
        // ->join('departments','departments.id','=','tickets.dept_id')
        // ->join('users','users.id','=','tickets.assigned_to')
        // ->where('tickets.assigned_to',$id)
        // ->get();

        $certificates = DB::table("user_certification")->where("user_id","=",$id)->get();
        $docs = DB::table("user_docs")->where("user_id","=",$id)->get();
        $staff_att_data = StaffAttendance::with('user_clocked')->where('user_id',$id)->get();

        $ticket_format = TicketSettings::where('tkt_key','ticket_format')->first();
        $customers = Customer::all();
        $users = User::all();
        // $departments = Departments::all();
        $statuses = TicketStatus::all();
        $priorities = TicketPriority::all();
        $types = TicketType::all();

        $staff_state = DB::Table('states')->where('id',"=",$profile->state)->first();
        $tasks =  Tasks::with('taskCreator')->with('taskProject')->where('assign_to',$id)->where('task_status','!=','success')->where('task_status','!=','Select')->where('is_deleted',0)->get();

        foreach($tasks as $task) {
            $task->assign_to = DB::Table("users")->where("id","=",$id)->first();
        }

        $google_api = 0;
        $google = Integrations::where("slug","google-api")->where('status', 1)->first();
        if(!empty($google)) {
            $google_api  = $google->details != null && $google->details != '' ?  1 :  0 ;
            $google = json_decode($google->details, true);
        }

        $countries = [];
        if($google_api === 0) $countries = DB::Table('countries')->get();

        $departments = $this->listPermissions($id);
        
        $date_format = Session('system_date');

        $general_staff_note = SystemSetting::where('sys_key', 'general_staff_note')->first();
        if(!empty($general_staff_note)) $general_staff_note = $general_staff_note->sys_value;
        $note_for_selected_staff = SystemSetting::where('sys_key', 'note_for_selected_staff')->select('sys_value')->first();
        if(!empty($note_for_selected_staff)) $note_for_selected_staff = $note_for_selected_staff->sys_value;
        $selected_staff_members = SystemSetting::where('sys_key', 'selected_staff_members')->select('sys_value')->first();
        if(!empty($selected_staff_members)) $selected_staff_members = explode(',', $selected_staff_members->sys_value);
        else $selected_staff_members = array();
    
        return view('system_manager.staff_management.user_profile_new',compact('id','google','staff_state','profile','tickets','staff_att_data', 'certificates','docs', 'types', 'priorities', 'statuses', 'departments', 'users', 'customers', 'ticket_format', 'countries', 'tasks', 'google_api', 'date_format', 'selected_staff_members', 'note_for_selected_staff', 'general_staff_note'));
    }

    private function listPermissions($uid) {
        // departments and user permissions
        $dept_permissions = DepartmentPermissions::where('user_id', $uid)->get()->toArray();
        $dept_assignments = DepartmentAssignments::where('user_id', $uid)->get()->pluck('dept_id')->toArray();

        $research = true;
        if(empty($dept_permissions)) {
            $research = false;
        }

        $departments = Departments::all()->toArray();
        // $webmaster_new_per = ["d_t_notifications" => ["Project manager progress report notifications"]];
        
        $roles = DB::table('roles')->where([
            ['name', '!=', 'Vendor'], ['name', '!=', 'Customer']
        ])->get()->pluck('id')->toArray();

        $permissions = [];
        foreach ($this->permissions_list as $key => $value) {
            $permissions[$key] = [$value, 0];
        }      

        foreach ($departments as $key => $value) {

            $departments[$key]['permissions'] = $permissions;

            // if($departments[$key]['name'] == "Webmaster") {
            //     $combine = array_merge($permissions ,$webmaster_new_per);
            //     if(in_array($departments[$key]['id'] , $dept_assignments) ) {
            //         array_push($combine['d_t_notifications'] , 1);
            //     }else{
            //         array_push($combine['d_t_notifications'] , 0);
            //     }
            //     $departments[$key]['permissions'] = $combine;
            // }

            if(in_array($value['id'], $dept_assignments)) {
                $departments[$key]['assignment'] = 1;
            } else {
                $departments[$key]['assignment'] = 0;
            }
        }

        if($research) {
            foreach ($departments as $i => $dept) {
                foreach ($dept_permissions as $j => $dept_p) {
                    if($dept['id'] == $dept_p['dept_id']) {
                        $departments[$i]['permissions'][$dept_p['name']][1] = $dept_p['permitted'];
                    }
                }
            }
        }

        return $departments;
    }

    public function departmentPermission(Request $request) {
        
        $dept_permission_data =  DB::table("dept_permissions")->count();

        if($dept_permission_data > 0) {

            DB::table("dept_permissions")->where("staff_id",$request->data[0]['user_id'])->delete();
            
            for($k = 0; $k < $request->length; $k++ ) {

                DB::table("dept_permissions")->insert([
                    "dept_id" => $request->data[$k]['dept_id'],
                    "staff_id" => $request->data[$k]['user_id'],
                    "permission_id" => $request->data[$k]['position'],
                    "is_active" => $request->data[$k]['is_active'],
                ]);
            }

        }else{

            for($i = 0; $i < $request->length; $i++ ) {

                DB::table("dept_permissions")->insert([
                    "dept_id" => $request->data[$i]['dept_id'],
                    "staff_id" => $request->data[$i]['user_id'],
                    "permission_id" => $request->data[$i]['position'],
                    "is_active" => $request->data[$i]['is_active'],
                ]);

            }

        }

        $response['message'] = 'Permission Saved Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }

    public function user_profile(Request $request , $id){
        
        if($request->wantsJson()){
            $profile = User::with('staffProfile')->where('email',$request->input('email'))->first();
            return response()->json($profile);
        }

        $profile = User::with('staffProfile')->where('id',\Auth::user()->id)->first();
        $tickets = DB::Table('tickets')
        ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        ->join('ticket_types','ticket_types.id','=','tickets.type')
        ->join('departments','departments.id','=','tickets.dept_id')
        ->join('users','users.id','=','tickets.assigned_to')
        ->where('tickets.assigned_to',\Auth::user()->id)
        ->get();
        return view('system_manager.staff_management.user_profile',compact('profile','tickets'));
        
    }

    public function uploadUserImage(Request $request){
        
        $image = $request->file('profile_img');
        $imageName = $_FILES['profile_img']['name'];

        $imageName = strtolower($imageName);
        $imageName = str_replace(" ","_",$imageName);
       
        $target_dir = 'storage/users';

        if (!File::isDirectory($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image->move($target_dir, $imageName);

        $user = User::where("id", $request->staff_id)->first();
        $user->profile_pic = 'storage/users/'. $imageName;
        $user->save();
        
        $response['message'] = 'Staff Profile Uploaded Successfully';
        $response['status'] = 200;
        $response['success'] = true;
        $response['img'] = $user->profile_pic;
        $response['id'] = $user->id;
        return response()->json($response);
    }

    public function update_my_profile(Request $request){
        echo "update my profile";
    }

    public function add_new_certification(Request $request){
        
        $data = $request->all();
        $response = array();

        try{
            
            $data['created_by'] = \Auth::user()->id;
                        
            if($request->has('image')){
        
                $image = $request->file('image');
                $filenamewithextension = $data['image']->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $data['image']->getClientOriginalExtension();
        
                $filenametostore = time().'.'.$extension;
                $filePath = public_path('files/user_certification/');
        
                $image->move($filePath, $filenametostore);
                $data["image"] = $filenametostore;
                
            }
           
            $save_users = Usercertification::create($data);
            
            $response['message'] = 'Certification Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
           }
            catch(Exception $e){
                $response['message'] = 'Something Went wrong!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
        }
    }
    
    public function delete_user(Request $request){
        $data = $request->all();
        $response = array();

        $user = User::with('staffProfile')->findOrFail($data['id']);
        $user->delete();
       
        // $del_department = User::destroy($data);
        $response['message'] = 'User Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
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

    public function saveColorSettings(Request $request){
        
        try{
            $action = $request->input('action');
            $color = $request->input('color');
            $response = [];
            $user = \Auth::user();
            
                if($action == 'textDark'){
                    $user->text_dark = $color;
                }else if($action == 'textLight'){
                    $user->text_light = $color;
                }else if($action == 'bgLight'){
                    $user->bg_light = $color;
                }else if($action == 'bgDark'){
                    $user->bg_dark = $color;
                }

                $user->save();
                $response['message'] = 'Color Settings Saved Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;

                return response()->json($response);
        }catch(Exception $err){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response,500);
        }
    }

    public function get_color(){
        $user = User::find(auth()->user()->id);
        $brand = BrandSettings::first();
        Session::put('text_light',$user->text_light ? $user->text_light : $brand->text_light);
        Session::put('bg_light',$user->bg_light ? $user->bg_light : $brand->bg_light);
        Session::put('text_dark',$user->text_dark ? $user->text_dark : $brand->text_dark);
        Session::put('bg_dark',$user->bg_dark ? $user->bg_dark : $brand->bg_dark);
        return response()->json('hi');
    }


    function get_all_certificates($id) {
        $certificates =  Usercertification::where("user_id","=",$id)->get();
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['certificates']=$certificates;
        return response()->json($response);
    }

    function get_all_docs($id) {

        $docs = UserDocuments::where("user_id","=",$id)->get();

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['docs']=$docs;
        return response()->json($response);
    }

    public function getStaffTasks(Request $request) {

        $tasks = DB::Table("tasks")
            ->where("assign_to","=",$request->id)
            ->whereBetween('created_at',[$request->from, $request->to])
            ->where("task_status","=",$request->task_status)
            ->where("is_deleted","=",0)
            ->get();

        foreach($tasks as $task) {
            $project = DB::Table("projects")->where("id",$task->project_id)->first();
            $user = DB::Table("users")->where("id",$task->created_by)->first();
            $assign_to = DB::Table("users")->where("id","=",$task->assign_to)->first();
            $task->task_project = $project;
            $task->task_creator = $user;
            $task->assign_to = $assign_to;
        }

        $response['message'] = 'Task List!';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $tasks;
        return response()->json($response);

    }

    public function customer_profile(){

        $customer = User::where('id',\Auth::user()->id)->first();
        $company = array();
        
        $tickets = DB::Table('tickets')
        ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        ->join('ticket_types','ticket_types.id','=','tickets.type')
        ->join('departments','departments.id','=','tickets.dept_id')
        ->join('users','users.id','=','tickets.customer_id')
        ->where('tickets.customer_id',\Auth::user()->id)
        ->get();

        foreach($tickets as $value){
            $rep = TicketReply::where('ticket_id', $value->id)->orderBy('updated_at', 'desc')->first();
            $repCount = TicketReply::where('ticket_id', $value->id)->count();
            if(!empty($rep)){
                $user = User::where('id', $rep['user_id'])->first();
                if(is_array($user)){
                    $value->lastReplier = $user['name'];
                }else if(is_object($user)){
                    $value->lastReplier = $user->name;
                }else{
                    $value->lastReplier = '';
                }
                $value->replies = $repCount;
            }else{
                $value->lastReplier = '';
                $value->replies = '';
            }
        }
        
        $orders = array();
        $subscriptions = array();

        return view('customer_manager.customer_lookup.customerprofile',compact('customer','tickets','company', 'subscriptions', 'orders'));
        // return view('customer_manager.customer_lookup.custProfile',compact('customer','tickets','company', 'subscriptions', 'orders'));

    }

    public function test(){

        $customer = User::where('id',\Auth::user()->id)->first();
        $company = array();
        
        $tickets = DB::Table('tickets')
        ->select('tickets.*','ticket_statuses.name as status_name','ticket_priorities.name as priority_name','ticket_types.name as type_name','departments.name as department_name','users.name as tech_name')
        ->join('ticket_statuses','ticket_statuses.id','=','tickets.status')
        ->join('ticket_priorities','ticket_priorities.id','=','tickets.priority')
        ->join('ticket_types','ticket_types.id','=','tickets.type')
        ->join('departments','departments.id','=','tickets.dept_id')
        ->join('users','users.id','=','tickets.customer_id')
        ->where('tickets.customer_id',\Auth::user()->id)
        ->get();

        foreach($tickets as $value){
            $rep = TicketReply::where('ticket_id', $value->id)->orderBy('updated_at', 'desc')->first();
            $repCount = TicketReply::where('ticket_id', $value->id)->count();
            if(!empty($rep)){
                $user = User::where('id', $rep['user_id'])->first();
                if(is_array($user)){
                    $value->lastReplier = $user['name'];
                }else if(is_object($user)){
                    $value->lastReplier = $user->name;
                }else{
                    $value->lastReplier = '';
                }
                $value->replies = $repCount;
            }else{
                $value->lastReplier = '';
                $value->replies = '';
            }
        }
        
        $orders = array();
        $subscriptions = array();

        // return view('customer_manager.customer_lookup.customerprofile',compact('customer','tickets','company', 'subscriptions', 'orders'));
        return view('customer_manager.customer_lookup.custProfile',compact('customer','tickets','company', 'subscriptions', 'orders'));

    }

    public function add_new_documents(Request $request){
        
        $data = $request->all();
        $response = array();
        
        try{
            
            $data['created_by'] = \Auth::user()->id;
                        
            if($request->has('image')){
        
                $image = $request->file('image');
                $filenamewithextension = $data['image']->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $data['image']->getClientOriginalExtension();
        
                $filenametostore = time().'.'.$extension;
                $filePath = public_path('files/user_docs/');
        
                $image->move($filePath, $filenametostore);
                $data["image"] = $filenametostore;
                
            }
           
            $save_users = UserDocuments::create($data);
            
            $response['message'] = 'Documents Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
           }
            catch(Exception $e){
                $response['message'] = 'Something Went wrong!';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
        }
    }

    public function addStaffSchedule(Request $request){

        $data = array(
            "staff_id" => $request->staff_id,
            "schedule_date" => $request->schedule_date,
            "start_time" => $request->start_time,
            "end_time" => $request->end_time,
            "is_holiday" => $request->is_holiday,
            "is_leave" => $request->is_leave,
            "created_by" => \Auth::user()->id,
        );
        
        if($request->id == '' || $request->id == null) {

            $reserve_date = StaffSchedule::where('schedule_date',$request->schedule_date)->first();

            if($reserve_date) {

                if($reserve_date->is_holiday == 0 && $reserve_date->is_leave == 0 && $reserve_date->start_time == "00:00:00" && $reserve_date->end_time == "00:00:00") {

                    StaffSchedule::where('schedule_date',$request->schedule_date)->update([
                        "is_holiday" => $request->is_holiday,
                        "is_leave" => $request->is_leave,
                        "start_time" => "00:00:00",
                        "end_time" =>  "00:00:00",
                    ]);

                    return response()->json([
                        "message" => 'Staff Schedule Added',
                        "status_code" => 200,
                        "success" => true,
                        "id" => $reserve_date->id,
                    ]);
                    
                }else if($reserve_date->is_holiday == 0 && $reserve_date->is_leave == 0 && $reserve_date->start_time != "00:00:00" && $reserve_date->end_time != "00:00:00"){

                    if($request->is_holiday != 0 || $request->is_leave !=0) {
                        StaffSchedule::where('schedule_date',$request->schedule_date)->update([
                            "is_holiday" => $request->is_holiday,
                            "is_leave" => $request->is_leave,
                        ]);
    
                        return response()->json([
                            "message" => 'Staff Schedule Added',
                            "status_code" => 200,
                            "success" => true,
                            "id" => $reserve_date->id,
                        ]);
                    }else{
                        return response()->json([
                            "message" => 'Date already Reserved... Try another date 1',
                            "status_code" => 500,
                            "success" => false,
                        ]);
                    }

                }else if($request->is_holiday != 0 || $request->is_leave !=0){
                    StaffSchedule::where('schedule_date',$request->schedule_date)->update([
                        "is_holiday" => $request->is_holiday,
                        "is_leave" => $request->is_leave,
                    ]);

                    return response()->json([
                        "message" => 'Staff Schedule Added',
                        "status_code" => 200,
                        "success" => true,
                        "id" => $reserve_date->id,
                    ]);
                }else{
                    return response()->json([
                        "message" => 'Date already Reserved... Try another date 2',
                        "status_code" => 500,
                        "success" => false,
                    ]);
                }
                
            }else{
                $staff = StaffSchedule::create($data);

                return response()->json([
                    "message" => 'Staff Schedule Added',
                    "status_code" => 200,
                    "success" => true,
                    "id" => $staff->id,
                ]);
            }       

        }else{

            $staff = StaffSchedule::where('id',$request->id)->update($data);
            $staff = $staff->id;

            return response()->json([
                "message" => 'Staff Schedule Updated',
                "status_code" => 200,
                "success" => true,
                "id" => $staff->id,
            ]);

        }

    }

    public function deleteStaffSchedule(Request $request) {
        StaffSchedule::find($request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Schedule Deleted Successfully',
            'status_code' => 200,
        ]);
    }

    public function getStaffSchedule(Request $request){
        $timings = StaffSchedule::where('staff_id',$request->staff_id)->get();
        $response['message'] = 'Staff Schedule List!';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $timings;

        return response()->json($response);
    }

    public function updateStaff(Request $request) {

        if($request->password) {
            $request->validate([
                'password' => 'required|min:8',
            ]);
        }

        $data = $request->all();

        try {   
            
            $user = User::where('id', $request->user_id)->first();
            $user->phone_number = $data['phone'];
            $user->country = $data['country'];
            $user->state = $data['state'];
            $user->city = $data['city'];
            $user->job_title = $data['job_title'];
            $user->notes = $data['notes'];
            $user->pinterest = $data['pinterest'];
            $user->twitter = $data['twitter'];
            $user->fb = $data['fb'];
            $user->insta = $data['insta'];
            $user->zip = $data['zip'];
            $user->address = $data['address'];
            $user->apt_address = $data['apt_address'];
            $user->notes = $data['notes'];
            $user->website = $data['website'];
            $user->name = $data['full_name'];
            $user->signature = $data['signature'];

            if($request->change_password_checkbox) {

                if($data['password'] != $data['confirm_password']) {
                    return response()->json([
                        "code" => 500,
                        "success" => false,
                        "message" => 'Password not matached!',
                    ]);
                }else{
                    // if(password_verify($data['old_password'], auth()->user()->password)) {
                        if (Hash::check($data['old_password'] , $user->password )) {
                            $user->password = Hash::make($data['password']);
                        }else{
                            return response()->json([
                                "code" => 500,
                                "success" => false,
                                "message" => 'Old password not match!',
                            ]);
                        } 
                    // }else{
                    //     return response()->json([
                    //         "code" => 500,
                    //         "success" => false,
                    //         "message" => 'old password not match!',
                    //     ]);  
                    // }
                }
            }

            $user->save();
            
            $mailer = new MailController();
            $mailer->UserRegisteration($user->email, false);

            return response()->json([
                'success' => true,
                'message' => 'Profile Updated Successfully',
                'code' => 200,
            ]);
           
            
        } catch (\Exception $th) {
            echo $th;
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'code' => 500,
            ]);
        }
    }


    public function addLeaves(Request $request) {

        $data = array(
            "requested_by" => $request->requested_by,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
            "reason" => $request->reason,
        );

        if($request->leave_id == '' && $request->leave_id == null) {

            StaffLeaves::create($data);
            $title = 'Leave Requested Successfully';

        }else{

            StaffLeaves::where('id',$request->leave_id)->update($data);
            $title = 'Leave Requested Updated';

        }

        return response()->json([
            'success' => true,
            'message' => $title,
            'status_code' => 200,
        ]);

    }

    public function get_all_leaves(Request $request) {

        $leaves = StaffLeaves::where('requested_by',\Auth::user()->id)->get();

        foreach($leaves as $leave) {
            $leave->staff = User::where('id',$leave->requested_by)->select('id','name')->first();
        }
        
        // if ($request->ajax()) {
        //     return Datatables::of($leaves)->make(true);
        // }
        
        $response['message'] = 'Leaves List';
        $response['data'] = $leaves;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function delete_leaves(Request $request) {
        StaffLeaves::find($request->id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'Leaves Request Deleted Successfully',
            'status_code' => 200,
        ]);
    }


    public function leave_index() {
        return view('system_manager.leave.leave');
    }

    public function leave_status(Request $request) {

        StaffLeaves::find($request->id)->update([
            "status" => $request->status
        ]);

        if($request->status == 1) {
            $leave =  StaffLeaves::find($request->id);

            $period = CarbonPeriod::create($leave->start_date, $leave->end_date);

            foreach ($period as $date) {
                StaffSchedule::create([
                    "staff_id" => $leave->requested_by,
                    "schedule_date" =>  $date->format('Y-m-d'),
                    "is_holiday" => 0,
                    "is_leave" => 1,
                    "start_time" => '00:00',
                    "end_time" => '00:00',
                    "created_by" => $leave->requested_by,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Leave '. ($request->status == 1 ? ' Approved ' : ' Rejected ') . ' Successfully',
            'status_code' => 200,
        ]);

    }

    public function add_staff_shift(Request $request) {

        $period = CarbonPeriod::create($request->start_date, $request->end_date);
        foreach ($period as $date) {
            StaffSchedule::create([
                "staff_id" => $request->staff_id,
                "start_time" => $request->start_time,
                "end_time" => $request->end_time,
                "schedule_date" => $date->format('Y-m-d'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Schedule Set Successfully',
            'status_code' => 200,
        ]);
    }

}