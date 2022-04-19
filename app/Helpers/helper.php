<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

if(!function_exists('file_path')){
    function file_path(){
       return Session::get('is_live') == 1 ? 'public/' : '/';
    }
}


if(!function_exists('path')){
    function path(){
       return  Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    }
}


if(!function_exists('getDefaultProfilePic')){
    function getDefaultProfilePic($pic){
        if($pic != null){
            if(file_exists(getcwd() . '/' . $pic)){
                return request()->root() . '/' . $pic;
            }else{
                return file_path() . 'default_imgs/customer.png';
            }
        }else{
            return file_path() . 'default_imgs/customer.png';
        }
    }
}
