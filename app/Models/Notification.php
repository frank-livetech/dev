<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use App\User;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = ['sender_id','receiver_id','slug','read_at','noti_type','noti_data','noti_title','noti_desc','noti_icon','btn_class'];

    public function scopeToSingleDevice($query,$token, $title, $body){
        
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder('New message from : kashif' );
        $notificationBuilder->setBody('asdasda')
            ->setSound('default')
            ->setClickAction('http://localhost:8000/home');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'sender_name' => 'Kashif',
            'mesage' => 'asdasdasda'
        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        $tokens = $token;
        
        // print_r($data);exit;
        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
        
        //echo $downstreamResponse->numberSuccess();exit;
        $downstreamResponse->numberFailure();
        $downstreamResponse->numberModification();
        // return Array - you must remove all this tokens in your database
        $downstreamResponse->tokensToDelete();
        // return Array (key : oldToken, value : new token - you must change the token in your database)
        $downstreamResponse->tokensToModify();
        // return Array - you should try to resend the message to the tokens in the array
        $downstreamResponse->tokensToRetry();
        // return Array (key:token, value:error) - in production you should remove from your database the tokens
        $downstreamResponse->tokensWithError();
        return ;
    }

    public function scopeToMultiDevice(){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder($this->noti_title);
        $notificationBuilder->setBody($this->noti_desc)
                            ->setSound('default');
                            // ->setClickAction('home');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'unread_count' => Notification::where('read_at',NULL)->where('receiver_id', $this->receiver_id)->count(),
            'type' => $this->noti_type,
            'slug' => $this->slug,
            'icon' => $this->noti_icon,
            'btn_class' => $this->btn_class,
            'source' => base_path()
        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        // You must change it to get your tokens
        $tokens = array();
        $user = User::where('id', $this->receiver_id)->first();
        
        if($user && !empty($user->device_token)){
            $tokens_obj = $user->device_token;
            // $tokens_obj = $model->pluck('device_token')->toArray();
            $tokens_obj = json_decode($tokens_obj);
            if(!empty($tokens_obj)) {
                for($i = 0; $i < sizeof($tokens_obj) ; $i++ ){
                    if($tokens_obj[$i]->token != null && $tokens_obj[$i]->token != ''){
                        array_push($tokens,$tokens_obj[$i]->token);
                    }
                }
                // print_r($tokens);exit;
                $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
                
                // echo $downstreamResponse->numberSuccess();exit;
                //$downstreamResponse->numberFailure();
                //$downstreamResponse->numberModification();
        
                // return Array - you must remove all this tokens in your database
                $exp_tkns = $downstreamResponse->tokensToDelete();
                
                if($exp_tkns){
                    $new_arr = array();
                    for($k = 0; $k < sizeof($tokens_obj) ; $k++ ){
                        for($t = 0 ;$t<sizeof($exp_tkns);$t++){
                            if($exp_tkns[$t] != $tokens_obj[$k]->token){
                                $fcm_data = array();
                                $fcm_data['token'] = $tokens_obj[$k]->token;
                                $fcm_data['device'] = $tokens_obj[$k]->device;
                                array_push($new_arr,$fcm_data);
                                continue 2;
                            }
                        }
                    }
                    $user->device_token = json_encode($new_arr);
                    $user->save();
                }
            }
            return ;
        }else{
            return ;
        }

        // return Array (key : oldToken, value : new token - you must change the token in your database)
        //$downstreamResponse->tokensToModify();
        // return Array - you should try to resend the message to the tokens in the array
        //$downstreamResponse->tokensToRetry();
        // return Array (key:token, value:error) - in production you should remove from your database the tokens present in this array
        //$downstreamResponse->tokensWithError();

    }

    public function getCreatedAtAttribute($value) {
        $date = new \DateTime($value);
        $date->setTimezone(new \DateTimeZone( timeZone() ));                            
        return $date->format(system_date_format() .' h:i a');
    }

    public function scopeRead(){
        
        return Notification::where('read_at',NULL)->where('receiver_id',\Auth::user()->id)->get();
    }    

    public static function scopeNumberAlert($query,$user_id){
        
        return Notification::where('read_at',NULL)->where('receiver_id',$user_id)->count();
    }

    public function sender() {
        return $this->hasOne(User::class , 'id' , 'sender_id');
    }

    public function user() {
        return $this->hasOne(User::class , 'id' , 'receiver_id');
    }

}
