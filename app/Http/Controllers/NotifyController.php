<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Jobs\NotificationJob;

use Illuminate\Http\Request;

class NotifyController extends Controller
{

    public function sendNotification( $receiver_id , $sender , $message) {

        $notificationJob = (new NotificationJob($receiver_id, $sender, $message));
        dispatch($notificationJob);

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
