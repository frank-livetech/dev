<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\NotifyController;


if(!function_exists('pusherCredentials')){
    function pusherCredentials($key){
        $pusher = [
                'id' => '1387883',
                'key' => 'e73e6b100edacfb69dc4',
                'secret' => '0b750d9e56ccc50e28cd',
                'cluster' => 'mt1',
        ];

        return $pusher[$key];
    }
}

if(!function_exists('file_path')){
    function file_path(){
       return request()->root() . '/' . Session::get('is_live') == 1 ? 'public/' : '/';
    }
}


if(!function_exists('path')){
    function path(){
       return  Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    }
}


if(!function_exists('getDefaultProfilePic')){
    function getDefaultProfilePic($pic){
        $path = Session::get('is_live') == 1 ? 'public/' : '/';
        
        if($pic != null){
            if(file_exists(getcwd() . '/' . $pic)){
                return request()->root() . $path .  $pic;
            }else{
                return request()->root() . $path .  'default_imgs/customer.png';
            }
        }else{
            return request()->root() . $path . 'default_imgs/customer.png';
        }
    }
}

if(!function_exists('timeZone')){
    function timeZone(){
        $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
        $tm_name = '';
        if($timezone) {
            $tm_name = $timezone->sys_value != null ? $timezone->sys_value : 'America/New_York';
        }else{
            $tm_name = 'America/New_York';
        }

        return $tm_name;
    }
}

if(!function_exists('sendNotificationToAdmins')){
    function sendNotificationToAdmins($slug , $type , $title , $desc){
        $admin_users = User::where('user_type', 1)->get()->toArray();
        $notify = new NotifyController();
        foreach ($admin_users as $key => $value) {
            $sender_id = auth()->id();
            $receiver_id = $value['id'];
            $slug = $slug;
            $type = $type;
            $data = 'data';
            $title = $title;
            $icon = 'calendar';
            $class = 'btn-success';
            $desc = $desc;
            $notify->sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
        }
    }
}


if(!function_exists('system_date_format')) {
    function system_date_format(){
        $system_format = DB::table("sys_settings")->where('sys_key','sys_dt_frmt')->first();
        $format = empty($system_format) ? 'DD-MM-YYYY' : $system_format->sys_value;

        $replacements = [
            'DD'   => 'd',  'ddd'  => 'D',  'D'    => 'j',  'dddd' => 'l',  'E'    => 'N',  'o'    => 'S',
            'e'    => 'w',  'DDD'  => 'z',  'W'    => 'W',  'MMMM' => 'F',  'MM'   => 'm',  'MMM'  => 'M',
            'M'    => 'n',  'YYYY' => 'Y',  'YY'   => 'y',  'a'    => 'a',  'A'    => 'A',  'h'    => 'g',
            'H'    => 'G',  'hh'   => 'h',  'HH'   => 'H',  'mm'   => 'i',  'ss'   => 's',  'SSS'  => 'u',
            'zz'   => 'e', 'X'    => 'U',
        ];

        $phpFormat = strtr($format, $replacements);
        return $phpFormat;

    }
}
