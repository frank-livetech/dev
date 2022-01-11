<?php

namespace App\Http\Controllers;

use App\Http\Controllers\CustomerManager\CustomerlookupController;
use Illuminate\Http\Request;
use Validator, Redirect, Response;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Session;
use Socialite;
use Storage;
use App\Models\BrandSettings;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use App\Http\Controllers\SystemManager\MailController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Models\Company;
use App\Models\Customer;
use App\Models\TicketStatus;
use App\Models\Departments;
use App\Models\DepartmentAssignments;
use Illuminate\Support\Facades\Crypt;
// use DB;

class AuthController extends Controller
{
    // public static $connection = '{mylive-tech.com:995/pop3/ssl}';
    // public static $mailserver_hostname = 'mylive-tech.com';
    // public static $mailserver_username = 'support2@mylive-tech.com';
    // public static $mailserver_password = 'y7.v9jLy!JLG9!s';

    public function index() {
        if (Auth::user()) {
            return redirect()->intended('home');
        }
        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        return view('auth.login',  get_defined_vars());
    }
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()
        ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
        ->redirect();
    }
    public function handleGoogleCallback()
    {
        try {
    
            $user = Socialite::driver('google')->stateless()
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->user();
     
            
            $finduser = User::where('google_id', $user->id)->first();
     
            if($finduser){
     
                Auth::login($finduser);
    
                return redirect()->intended('home');
     
            }else{
                

                
                

               
                                    
                    $url = $user->avatar_original;
                    $contents = file_get_contents($url);
                    $folder ='files/user_photos/';
                    $name = $folder.time().'.jpg';
                    Storage::disk('public')->put($name, $contents,'public');
              


                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'profile_pic'=>$name,
                    'user_type' => 5,
                    'password' => bcrypt('123456dummy')
                ]);
                
    
                Auth::login($newUser);
                
                return redirect()->intended('myprofile/' . $user->name);
     
                // return redirect('/home');
            }
    
        } catch (Exception $e) {
            $settings = BrandSettings::first();
            return view('auth.login', compact('settings'));
        }
    }

    public function forgetPassword() {
        return view('auth.passwords.reset');
    }

    public function recoverPassword(Request $request) {

        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput()->withErrors(['email' => 'No such user exist.']);
        } else {

            DB::table("password_resets")->insert([
                "email" => $request->email,
                "token" => $request->_token,
            ]);

            $reminder = DB::table("password_resets")
                ->where('email', $request->email)
                ->where('token', $request->_token)
                ->first();

            $this->resetPasswordTemplate($user->email,$reminder->token);
            return redirect()->back()->with('success', 'Activation Email sent to your Email Address.');
        }
    }

    public function resetPasswordTemplate($email , $token) {

        $user = User::where("email",$email)->first();
        $billing_template = DB::table('templates')->where('code','reset_password')->first();

        if(empty($billing_template)) {
            throw new Exception('Template not found');
        }

        $order_input = array(
            array('module' => 'User', 'values' => $user->toArray()),
        );

        $template = $this->resetPassword_parser($order_input, $billing_template->template_html , $user, $token);

        $mail = new MailController();
        $mail->sendMail("Forget Password", $template, 'password-reset@mylive-tech.com', $user->email, $user->name);
    }

    public function resetPassword_parser($data_list, $template , $user, $token) {
        if(empty($template)) {
            throw new Exception('Template is empty!');
        }

        if(empty($data_list)) {
            throw new Exception('Provided data list is empty!');
        }

        $template = htmlentities($template);

        if(str_contains($template, '{User-Name}')) {
            $content = DB::table('templates')->where('code', 'reset_password')->first();
            
            if(!empty($content)) {
                $content = $content->template_html;
                $this->replaceShortCodes($data_list, $content,$user, $token);
            }
        }

        $this->replaceShortCodes($data_list, $template, $user, $token);
        $sc_vars = DB::table('sc_variables')->get();

        foreach ($sc_vars as $key => $value) {

            if(str_contains($template, $value->code)) {
                $template = str_replace($value->code," ", $template);
            }
        }    
        return html_entity_decode($template);          
    }

    public function replaceShortCodes($data_list, &$template, $user, $token) {

        $reset_btn = '<a href="' . URL::to('/') . '/' . 'activate/' . $user->email . '/ ' . $token . '">Click Here</a>';
        
        foreach ($data_list as $key => $data) {

            if($data['module'] == 'User' && str_contains($template, '{User-Name}')) {
                $template = str_replace('{User-Name}', $data['values']['name'], $template);
            }

            if($data['module'] == 'User' && str_contains($template, '{User-Email}')) {
                $template = str_replace('{User-Email}', $data['values']['email'], $template);
            }

            if($data['module'] == 'User' && str_contains($template, '{Reset-Button}')) {
                $template = str_replace('{Reset-Button}', $reset_btn, $template);
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

    // public function sendMail($subject, $recipient,$body, $recipient_name, $reply = false) {
    //     try {
    //         $mail = new PHPMailer(true);
    //         $mail->isSMTP();
    //         $mail->SMTPAuth  =  true;

    //         $mail->Host      =  self::$mailserver_hostname;
    //         $mail->Username  =  self::$mailserver_username;
    //         $mail->Password  =  self::$mailserver_password;

    //         $mail->SMTPOptions = [
    //             'ssl' => [
    //                 'verify_peer' => false,
    //                 'verify_peer_name' => false,
    //                 'allow_self_signed' => true,
    //             ]
    //         ];

    //         $mail->setFrom($mail->Username);
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

    public function changePasswordPage($email, $code)
    {
        return view('auth.passwords.confirm', compact('email', 'code'));
    }

    public function ResetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8',
            'confirm_password' => 'required|min:8',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['password' => 'No such user exist.']);
        } else {

            $reminder = DB::table("password_resets")
                ->where('email', $request->email)
                ->where('token', $request->code)
                ->first();


            if ($reminder) {

                if ($request->code == $reminder->token) {

                    DB::table("users")->where('email', '=', $reminder->email)->update([
                        "password" => Hash::make($request->password),
                        "alt_pwd" => Crypt::encryptString($request->password),
                    ]);
                    DB::table("password_resets")
                        ->where('email', $request->email)
                        ->where('token', $request->code)
                        ->delete();
                    return back()->withInput()->withErrors(['password' => 'Password Change You Can login Now']);
                } else {
                    return back()->withInput()->withErrors(['password' => 'UnAuthorized Token..']);
                }
            } else {

                return back()->withInput()->withErrors(['password' => 'Time Expired.']);
            }
        }
    }
    public function postLogin(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        // $remember = ($request->remember == 'on') ? true : false;
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->is_deleted == 1) {
                return back()->withInput()->withErrors(['email' => 'No such user exist.']);
            } else if ($user->status != 1) {
                return back()->withInput()->withErrors(['email' => 'Contact your admin.']);
            }else if($user->user_type == 5){
                return back()->withInput()->withErrors(['email' => 'Contact your admin.']);
            } else {
                if (Auth::attempt($credentials, $request->has('remember'))) {
                    // Authentication passed...
                    if (isset($request->fcm_token)) {
                        
                        if ($user->device_token != NULL && $user->device_token != '') {
                            $fcm_array = json_decode($user->device_token);
                            $token = $request->fcm_token;
                            $entry = current(array_filter($fcm_array, function ($e) use ($token) {
                                return $e->token == $token;
                            }));
                            if ($entry === false) {
                                $fcm_data = array();
                                $fcm_data['token'] = $request->fcm_token;
                                $fcm_data['device'] = 'Windows';
                                array_push($fcm_array, $fcm_data);
                                $user->device_token = $fcm_array;
                                $user->save();
                            }
                        } else {
                            $fcm_data = array();
                            $fcm_array = array();
                            $fcm_data['token'] = $request->fcm_token;
                            $fcm_data['device'] = 'Windows';
                            array_push($fcm_array, $fcm_data);
                            $user->device_token = json_encode($fcm_array);
                            $user->save();
                        }
                    }

                    $settings = BrandSettings::first();

                    if($settings) {
                        Session::put('site_title', $settings->site_title);
                        Session::put('site_logo', $settings->site_logo);
                        Session::put('site_favicon', $settings->site_favicon);
                        Session::put('site_logo_title', $settings->site_logo_title);
                        Session::put('site_footer', $settings->site_footer);
                        Session::put('site_version', $settings->site_version);
                    }else{
                        Session::put('site_logo_title', 'Dashboard');
                    }

                    if (\Auth::user()->user_type == 1) {
                        $role_features = DB::table("ac_features")->where("parent_id", "=", 0)->get();
                        foreach ($role_features as $feature) {
                            $sub_menu = DB::table("ac_features")->where('parent_id', '=', $feature->f_id)->orderBy("sequence")->get();
                            $feature->sub_menu = $sub_menu;
                        }
                    }  else {
                        $role_features = DB::table('role_has_permission')
                            ->join('ac_features', 'role_has_permission.feature_id', '=', 'ac_features.f_id')
                            ->where('ac_features.parent_id', '=', 0)
                            ->where("is_active", "=", 1)
                            ->where('role_has_permission.role_id', \Auth::user()->user_type)->get();

                        foreach ($role_features as $feature) {
                            $sub_menus = DB::table("ac_features")->where('parent_id', '=', $feature->f_id)->where("is_active", "=", 1)->orderBy("sequence")->get();
                            $sub_menu = array();
                            foreach ($sub_menus as $sub) {
                                $ft_prmt = DB::table('role_has_permission')
                                    ->where('role_has_permission.feature_id', $sub->f_id)
                                    ->where('role_has_permission.role_id', \Auth::user()->user_type)->first();
                                if ($ft_prmt) {
                                    array_push($sub_menu, $sub);
                                }
                            }
                            $feature->sub_menu = $sub_menu;
                        }
                    }

                    $depts = $this->listPermissions(\Auth::user()->id);
                    Session::put('depts', $depts);
                    
                    Session::put('menus', $role_features->sortBy('sequence'));

                    $system_format = DB::table("sys_settings")->where('sys_key','sys_dt_frmt')->first();
                    if($system_format) {
                        Session::put('system_date', $system_format->sys_value);
                    }

                    $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
                    if($live) {
                        Session::put('is_live', $live->sys_value);
                    }else{
                        Session::put('is_live', 0);
                    }

                    $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
                    if($timezone) {
                        Session::put('timezone', $timezone->sys_value);
                    }else{
                        Session::put('timezone', 'America/New_York');
                    }

                    $default_comp_id = Company::where("is_default", "=", 1)->first();
                    if ($default_comp_id == '' || $default_comp_id == null) {
                        Session::put('default_cmp_id', 0);
                    } else {
                        Session::put('default_cmp_id', $default_comp_id->id);
                    }

                    $visuals = DB::table("visual_settings")->where('created_by',\Auth::user()->id)->get();
        
                    $light_key = array();
                    $light_value = array();
            
                    $dark_key = array();
                    $dark_value = array();
            
                    $button_key = array();
                    $button_value = array();
            
                    if($visuals) {
            
                        foreach($visuals as $visual) {
            
                            if($visual->mode == "Light") {
                                array_push($light_key, $visual->vs_key);
                                array_push($light_value, $visual->vs_value);
                            } 
                            
                            if($visual->mode == "dark") {
                                array_push($dark_key, $visual->vs_key);
                                array_push($dark_value, $visual->vs_value);
                            } 
            
                            if($visual->mode == "button") {
                                array_push($button_key, $visual->vs_key);
                                array_push($button_value, $visual->vs_value);
                            } 
            
                        }

                        $light_mode = array_combine($light_key, $light_value);
                        $dark_mode = array_combine($dark_key, $dark_value);
                        $button = array_combine($button_key, $button_value);
    
                        Session::put('light_mode',$light_mode);
                        Session::put('dark_mode',$dark_mode);
                        Session::put('button',$button);
                    }
            
                    return redirect()->intended('home');
                } else {
                    return back()->withInput()->withErrors(['email' => 'Invalid credentials.']);
                }
            }
        } else {
            return back()->withInput()->withErrors(['email' => 'No such user exist.']);
        }
    }
    
    private function listPermissions($uid) {
    
        $dept_assignments = DepartmentAssignments::where('user_id', $uid)->get()->pluck('dept_id')->toArray();
        // return $dept_assignments;   
        $departments = Departments::all();    
        $statuses = TicketStatus::orderBy('seq_no', 'Asc')->get();
        $assigned_depts = array();
        foreach ($departments as $dept) {
            if(in_array($dept->id, $dept_assignments)){
                $detp_statuses = array();
                foreach($statuses as $status){

                    $depts = $status->department_id;
                    $depts = explode(',',$depts);
                    if(in_array($dept->id, $depts)) {
                        array_push($detp_statuses,$status);
                    }
                }
                $dept->statuses = $detp_statuses;
                array_push($assigned_depts,$dept);
                
            }else{

            }
        }
            
        return $assigned_depts;
    }

    public function userPostLogin(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        // $remember = ($request->remember == 'on') ? true : false;
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if ($user->is_deleted == 1) {
                return back()->withInput()->withErrors(['email' => 'No such user exist.']);
            } else if ($user->status != 1) {
                return back()->withInput()->withErrors(['email' => 'Contact your admin.']);
            }else if ($user->user_type != 5) {
                return back()->withInput()->withErrors(['email' => 'Contact your admin.']);
            } else {
                if (Auth::attempt($credentials, $request->has('remember'))) {
                    // Authentication passed...
                    if (isset($request->fcm_token)) {
                        if ($user->device_token != NULL && $user->device_token != '') {
                            $fcm_array = json_decode($user->device_token);
                            $token = $request->fcm_token;
                            $entry = current(array_filter($fcm_array, function ($e) use ($token) {
                                return $e->token == $token;
                            }));
                            if ($entry === false) {
                                $fcm_data = array();
                                $fcm_data['token'] = $request->fcm_token;
                                $fcm_data['device'] = 'Windows';
                                array_push($fcm_array, $fcm_data);
                                $user->device_token = $fcm_array;
                                $user->save();
                            }
                        } else {
                            $fcm_data = array();
                            $fcm_array = array();
                            $fcm_data['token'] = $request->fcm_token;
                            $fcm_data['device'] = 'Windows';
                            array_push($fcm_array, $fcm_data);
                            $user->device_token = json_encode($fcm_array);
                            $user->save();
                        }
                    }

                    $settings = BrandSettings::first();

                    if($settings) {
                        Session::put('site_title', $settings->site_title);
                        Session::put('site_logo', $settings->site_logo);
                        Session::put('site_favicon', $settings->site_favicon);
                        Session::put('site_logo_title', $settings->site_logo_title);
                        Session::put('site_footer', $settings->site_footer);
                        Session::put('site_version', $settings->site_version);
                    }else{
                        Session::put('site_logo_title', 'Dashboard');
                    }

                    $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
                    if($live) {
                        Session::put('is_live', $live->sys_value);
                    }else{
                        Session::put('is_live', 0);
                    }

                    $system_format = DB::table("sys_settings")->where('sys_key','sys_dt_frmt')->first();
                    if($system_format) {
                        Session::put('system_date', $system_format->sys_value);
                    }else{
                        Session::put('system_date', 'YYYY-MM-DD');
                    }

                    $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
                    if($timezone) {
                        Session::put('timezone', $timezone->sys_value);
                    }else{
                        Session::put('timezone', 'America/New_York');
                    }

                    if (\Auth::user()->user_type == 5) {
                        return redirect()->intended('myprofile/' . $user->account_id);
                    }
                    
                } else {
                    return back()->withInput()->withErrors(['email' => 'Invalid credentials.']);
                }
            }
        } else {
            return back()->withInput()->withErrors(['email' => 'No such user exist.']);
        }
    }

    public function logout(Request $request)
    {
        $route = '/';
        if(Auth::user()->user_type == 5) {
            $route = '/user-login';
        }
        
        Session::flush();
        if( Auth::check()){
            Auth::logout();
        }
        
        return Redirect($route);
    }

    public function userLogin() {
        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        $is_live  = $live != null ?  (int)$live->sys_value : 0; 
        return view('auth.userlogin', get_defined_vars());
    }
    public function userRegister() {

        $settings = BrandSettings::first();
        return view('auth.userRegistration', compact('settings'));
    }


    public function saveUserDetails(Request $request) {
        try {
            $cust = new CustomerlookupController();
            $res = $cust->save_customer($request);

            if($res->original['status'] == 500) {
                return redirect()->to('user-register')->with(['message' => $res->original['message']]);
            } else {
                return redirect()->to('user-login')->with(['success' => 'Registration Completed Successfully']);
            }
        } catch(Exception $e) {
            return redirect()->to('user-register')->with(['message' => $e->getMessage()]);
        }
        // $company = [];

        // $request->validate([
        //     'first_name' => 'required',
        //     'last_name' => 'required',
        //     'email' => 'required|unique:users|email',
        //     'password' => 'required|required_with:confirm_password|same:confirm_password|min:8',
        //     'confirm_password' => 'required|min:8',
        // ]);
        // $password = Hash::make($request->password);
        // $alt_pwd = Crypt::encryptString($request->password);

        // $customer = array(
        //     "name" => $request->first_name .' '. $request->last_name,
        //     "email" => $request->email,
        //     "password" => $password,
        //     "alt_pwd" => $alt_pwd,
        //     "status" => 1,
        //     "user_type" => 5,
        //     "privacy_policy" => ($request->remember == "on" ? 1 : 0),
        // );

        // $user = User::create($customer);

        // $user_id = $user->id;
        // $random_no = mt_rand(100000,999999); 
        // $account_id = $random_no . $user_id;
        
        // $user->account_id = $account_id;
        // $user->save();

        // $customer_data = [
        //     "account_id" => $account_id,
        //     "first_name" => $request->first_name,
        //     "last_name" => $request->last_name,
        //     "email" => $request->email,     
        //     "username" => $request->email,
        //     "has_account" => 1
        // ];

        // Customer::create($customer_data);

        // if($request->company_name != null && $request->company_name != "") {
        //     $company['name'] = $request->company_name;
        // }
        // if($request->address != null && $request->address != "") {
        //     $company['address'] = $request->address;
        // }
        // if($request->phone != null && $request->phone != "") {
        //     $company['phone'] = $request->phone;
        // }
        // if($request->city != null && $request->city != "") {
        //     $company['cmp_city'] = $request->city;
        // }

        // if(count($company) > 0) {
        //     Company::create($company);
        // }

        // $custCont = new MailController();
        // $custCont->UserRegisteration($request->email);

        // return redirect()->to('user-login')->with(['success' => 'Registration Completed Successfully']);
    }
}
