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
use App\Models\StaffAttendance;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session as SessionSession;

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
    public function handleGoogleCallback() {
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
        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        return view('auth.passwords.reset',  get_defined_vars());
    }

    public function submitForgetPasswordForm(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = \Str::random(64);

        // $resetLink = DB::table('password_resets')
        //                     ->where([
        //                         'email' => $request->email,
        //                     ])->get();

        // if(count($resetLink) != 0){
        //     return redirect()->back()->with('danger', 'We have already e-mailed you password reset link!');
        // }

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $mail_template = DB::table('templates')->where('code','reset_password')->first();
        if(empty($mail_template)) {
            throw new Exception('Template not found');
        }

        $user = User::where('email',$request->email)->first();

        if($mail_template != null) {

            if(!empty($mail_template->template_html)) {

                $template = htmlentities($mail_template->template_html);

                if(str_contains($template, '{User-Name}')) {
                    $template = str_replace('{User-Name}', $user->name , $template);
                }

                if(str_contains($template, '{User-forget-link}')) {
                    $url = request()->root() .'/user-reset-password' .'/' . $request->email . '/' . $token;
                    $link = '<a href="'.$url.'">Reset Password</a>';
                    $template = str_replace('{User-forget-link}', $link, $template);
                }

                $temp = html_entity_decode($template);

                $mail = new MailController();
                $mail->sendMail("Forget Password", $temp , 'password-reset@mylive-tech.com', $user->email, $user->name);
                return back()->with('success', 'We have e-mailed your password reset link!');

            }else{
                throw new Exception('Template not found');
            }
        }else{
            throw new Exception('Template not found');
        }


        return back()->with('success', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm($email,$token) {
        $tokenCreated = DB::table('password_resets')
                            ->where([
                                'email' => $email,
                                'token' => $token
                            ]);


        if(count($tokenCreated->get())!=0){

            $expired = Carbon::parse($tokenCreated->get()[0]->created_at)->addSeconds(config('auth.passwords.users.expire')*60)->isPast();

            if($expired){
                $tokenCreated->delete();
                return view('auth.userexpired',['url' => 'login','live' =>  SystemSetting::where('sys_key','is_live')->first()]);
            }

            return view('auth.userResetpassword', ['token' => $token,'email' => $email, 'is_live' => 0]);
        }

        return redirect()->to('user-login');

    }

    public function submitResetPasswordForm(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
                            ->where([
                                'email' => $request->email,
                                'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
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

    public function changePasswordPage($email, $code) {
        return view('auth.passwords.confirm', compact('email', 'code'));
    }

    public function ResetPassword(Request $request) {
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
                    // if (isset($request->fcm_token)) {

                    //     if ($user->device_token != NULL && $user->device_token != '') {
                    //         $fcm_array = json_decode($user->device_token);
                    //         $token = $request->fcm_token;
                    //         $entry = current(array_filter($fcm_array, function ($e) use ($token) {
                    //             return $e->token == $token;
                    //         }));
                    //         if ($entry === false) {
                    //             $fcm_data = array();
                    //             $fcm_data['token'] = $request->fcm_token;
                    //             $fcm_data['device'] = 'Windows';
                    //             array_push($fcm_array, $fcm_data);
                    //             $user->device_token = $fcm_array;
                    //             $user->save();
                    //         }
                    //     } else {
                    //         $fcm_data = array();
                    //         $fcm_array = array();
                    //         $fcm_data['token'] = $request->fcm_token;
                    //         $fcm_data['device'] = 'Windows';
                    //         array_push($fcm_array, $fcm_data);
                    //         $user->device_token = json_encode($fcm_array);
                    //         $user->save();
                    //     }
                    // }

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


                    User::find(\Auth::user()->id)->update([
                        'is_online' => 1
                    ]);

                    Session::put('is_online_notif', 0);
                    Session::put('menus', $role_features->sortBy('sequence'));

                    $currentDate = Carbon::now();
                    $currentDate = $currentDate->format('Y-m-d');

                    $staffData = StaffAttendance::where([ ['date', $currentDate], ['clock_out', null], ['user_id',auth()->user()->id]])->orderByDesc('id')->first();
                    if(!empty($staffData)) {
                        Session::put('clockin', 1);
                        Session::put('clockin_time', $staffData->clock_in);
                        Session::put('staff_data', $staffData );
                    }else{
                        Session::put('clockin', 0);
                        Session::put('clockin_time', null);
                        Session::put('staff_data', null );
                    }

                    $system_format = DB::table("sys_settings")->where('sys_key','sys_dt_frmt')->first();
                    if($system_format) {
                        Session::put('system_date', $system_format->sys_value);
                    }else{
                        Session::put('system_date' , 'DD-MM-YYYY');
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

    // customer login
    public function userPostLogin(Request $request) {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        if($request->remember===null){
            setcookie('livetech_ml',base64_encode($request->email),100);
            setcookie('livetech_ps',base64_encode($request->password),100);
         }
         else{
            setcookie('livetech_ml',base64_encode($request->email),time()+60*60*24*100);
            setcookie('livetech_ps',base64_encode($request->password),time()+60*60*24*100);

         }

        // $remember = ($request->remember == 'on') ? true : false;
        $user = User::where('email', $request->email)->first();
        // $user = Customer::where('email', $request->email)->first();
        if ($user) {
            if ($user->is_deleted == 1) {
                return back()->withInput()->withErrors(['email' => 'No such user exist.']);
            } else if ($user->status != 1) {
                return back()->withInput()->withErrors(['email' => 'Contact your admin.']);
            }else if ($user->user_type != 5) {
                return back()->withInput()->withErrors(['email' => 'Contact your admin.']);
            } else {
                if (Auth::attempt($credentials, $request->has('remember') ? true : false )) {
                    // Authentication passed...
                    // if (isset($request->fcm_token)) {
                    //     if ($user->device_token != NULL && $user->device_token != '') {
                    //         $fcm_array = json_decode($user->device_token);
                    //         $token = $request->fcm_token;
                    //         $entry = current(array_filter($fcm_array, function ($e) use ($token) {
                    //             return $e->token == $token;
                    //         }));
                    //         if ($entry === false) {
                    //             $fcm_data = array();
                    //             $fcm_data['token'] = $request->fcm_token;
                    //             $fcm_data['device'] = 'Windows';
                    //             array_push($fcm_array, $fcm_data);
                    //             $user->device_token = $fcm_array;
                    //             $user->save();
                    //         }
                    //     } else {
                    //         $fcm_data = array();
                    //         $fcm_array = array();
                    //         $fcm_data['token'] = $request->fcm_token;
                    //         $fcm_data['device'] = 'Windows';
                    //         array_push($fcm_array, $fcm_data);
                    //         $user->device_token = json_encode($fcm_array);
                    //         $user->save();
                    //     }
                    // }

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

    public function logout(Request $request) {

        $route = auth()->user()->user_type == 5 ? 'user-login' : 'login';
        
        \Session::forget('user_session');
        User::find(Auth::id())->update(['is_online' => 1]);
        if( Auth::check()){
            Auth::logout();
        }
        if(auth()->user()->user_type != 5 && auth()->user()->user_type != 4 ){
            User::find(\Auth::user()->id)->update([
                'is_online' => 0
            ]);

            $admin_users = User::where('user_type', 1)->where('is_deleted',0)->where('status',1)->get()->toArray();
    
            $notify = new NotifyController();
    
            foreach ($admin_users as $key => $value) {
    
                $sender_id = \Auth::user()->id;
                $receiver_id = $value['id'];
                $data = '';
                $slug = 'dashboard';
                $type = 'online_user';
                $title = 'LoggedInUser';
                $icon = 'ti-calendar';
                $class = 'btn-success';
                $desc = 'Login In by '.Auth::user()->name;
                
                $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
            }

        }
        return redirect()->to($route);
    }

    public function userLogin() {
        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        $is_live  = $live != null ?  (int)$live->sys_value : 0;
        return view('auth.userlogin', get_defined_vars());
    }

    public function userRegister() {

        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        $is_live  = $live != null ?  (int)$live->sys_value : 0;
        return view('auth.userRegistration', get_defined_vars());
    }

    public function saveUserDetails(Request $request) {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);

        $customer_data = $request->all();
        $check_customer = DB::table("customers")->where('email', $request->email)->where('is_deleted', 0)->first();

        $check_user = DB::table("users")->where('email', $request->email)->where('is_deleted', 0)->first();

        if(!empty($check_customer) || !empty($check_user)) {
            return redirect()->to('user-register')->with(['message' => 'Email already taken']);
        }

        DB::table("customers")->insert([
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "email" => $request->email,
            "username"=> $request->email,
        ]);

        DB::table("users")->insert([
            "name" => $request->first_name . " " . $request->last_name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "alt_pwd" => Crypt::encryptString($request->password),
            "user_type" => 5,
            "status" => 1
        ]);

        // $cust = new CustomerlookupController();
        // $res = $cust->save_customer($request);

        if('status' == 500) {
            return redirect()->to('user-register')->with(['message' => 'Registration UnSuccessfull']);
        } else {
            return redirect()->to('user-login')->with(['success' => 'Registration Completed Successfully']);
        }
    }

    public function customerforgetpassword(){

        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        $is_live  = $live != null ?  (int)$live->sys_value : 0;

        return view('auth.userforgetpassword' , get_defined_vars());
    }

    public function submitCustomerForgetPasswordForm(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = \Str::random(64);

        $resetLink = DB::table('password_resets')
                            ->where([
                                'email' => $request->email,
                            ])->get();

        if(count($resetLink) != 0){
            return redirect()->back()->with('danger', 'We have already e-mailed you password reset link!');
        }

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $mail_template = DB::table('templates')->where('code','reset_password')->first();
        if(empty($mail_template)) {
            throw new Exception('Template not found');
        }

        $user = User::where('email',$request->email)->first();


        if($mail_template != null) {

            if(!empty($mail_template->template_html)) {

                $template = htmlentities($mail_template->template_html);

                if(str_contains($template, '{User-Name}')) {
                    $template = str_replace('{User-Name}', $user->name , $template);
                }

                if(str_contains($template, '{User-forget-link}')) {
                    $link = '<a href="'.route("user.reset.password.get",[$request->email,$token]).'">Reset Password</a>';
                    $template = str_replace('{User-forget-link}', $link, $template);
                }

                $temp = html_entity_decode($template);

                $mail = new MailController();
                $mail->sendMail("Forget Password", $temp , 'password-reset@mylive-tech.com', $user->email, $user->name);
                return back()->with('success', 'We have e-mailed your password reset link!');

            }else{
                throw new Exception('Template not found');
            }
        }else{
            throw new Exception('Template not found');
        }

    }

    public function showCustomerResetPasswordForm($email,$token) {
        $tokenCreated = DB::table('password_resets')
                            ->where([
                                'email' => $email,
                                'token' => $token
                            ]);

        if(count($tokenCreated->get())!=0){
            $expired = Carbon::parse($tokenCreated->get()[0]->created_at)->addSeconds(config('auth.passwords.users.expire')*60)->isPast();
            if($expired){
                $tokenCreated->delete();
                return view('auth.userexpired',['url' => 'user-login','live' => SystemSetting::where('sys_key','is_live')->first()]);
            }

            return view('auth.userResetpassword', ['token' => $token,'email' => $email, 'is_live' => Session::get('is_live')]);
        }

        return redirect()->to('user-login');

    }

    public function submitCustomerResetPasswordForm(Request $request) {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
                            ->where([
                                'email' => $request->email,
                                'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/user-login')->with('message', 'Your password has been changed!');
    }

    public function reset_password(){

        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        $is_live  = $live != null ?  (int)$live->sys_value : 0;
        return view('auth.reset' , get_defined_vars());
    }

    public function userresetpassword(){

        $settings = BrandSettings::first();
        $live = DB::table("sys_settings")->where('sys_key','is_live')->first();
        $is_live  = $live != null ?  (int)$live->sys_value : 0;
        return view('auth.userResetpassword' , get_defined_vars());
    }


    }

