<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


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