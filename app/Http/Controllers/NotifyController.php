<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Customer;
use App\Jobs\NotificationJob;
use App\User;

use Illuminate\Http\Request;

class NotifyController extends Controller
{

    public function sendNotification($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc) {

        $user_pic = '';
        $user_type = '';
        $sender = User::where('id' , $sender_id)->first();
        if(!$sender){
            $sender = Customer::where('id' , $sender_id)->first();
            $user_pic = $sender->avatar_url;
            $user_type = 5;
        }else{
            $user_pic = $sender->profile_pic;
            $user_type = 1;
        }
        
        if($title != '' && $title !=null){
            $data = array(
                "sender_id" => $sender_id ,
                "receiver_id" => $receiver_id ,
                "slug" => $slug,
                "noti_type" => $type ,
                "noti_data" => $data ,
                "noti_title" => $title, 
                "noti_icon" => $icon ,
                "btn_class" => $class ,
                "noti_desc" => $desc ,
                "user_pic" => $user_pic ,
                "user_type" => $user_type ,

            );

            $notify = Notification::create($data);
            if($notify) {
                $notificationJob = (new NotificationJob($receiver_id, $sender_id, $data));
                dispatch($notificationJob);
            }
        }
        
    }


    public function GeneralNotifi($sender,$receiver,$slug,$type,$data,$title,$icon,$class,$desc){
        $notify = new Notification;
        $notify->sender_id = $sender;
        $notify->receiver_id = $receiver;
        $notify->slug = $slug;
        $notify->noti_type = $type;
        $notify->noti_data = $data;
        $notify->noti_title = $title;
        $notify->noti_icon = $icon;
        $notify->btn_class = $class;
        $notify->noti_desc = $desc;

        if($notify->save()){

        //   $notify->scopeToMultiDevice();

        }
    }
}
