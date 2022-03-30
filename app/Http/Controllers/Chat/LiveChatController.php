<?php

namespace App\Http\Controllers\Chat;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\WhatsAppChat;
use Twilio\Rest\Client;

class LiveChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }
    public function index(){
        // $users = User::where('is_deleted',0)->get();
        $users = User::where('is_deleted',0)->where('id','!=',\Auth::id())->where('user_type','!=',5)->get();
        // return $users;
        // return view('chat.index',compact('users'));
        return view('chat.index-new',compact('users'));

    }


    public function sendMessage(Request $request) {
        $user = User::where('id' , $request->user_to)->first();

        if($user->whatsapp == null) {
            return response()->json([
                "message" => "User Not Found" ,
                "status" => 500 ,
                "success" => false ,
            ]);
        }

        $sid = "AC900992cf2053493cb74010084d98d5ed";
        $token = "9a2bf65f24c24a161bee9671a43949a8";
        
        $to = 'whatsapp:'.$user->whatsapp;
        $from = 'whatsapp:+14155238886';

        $client = new Client($sid, $token);
        $message = $client->messages->create( $to , 
        [
            'from' => $from ,
            'body' => $request->message ,
        ]
        );
        
        // print $message->sid;
        
        $data = array(
            "from" => $from ,
            "to" => $user->whatsapp ,
            "body" => $request->message ,
            "num_media" => 0,
            "media_url" => null,
        );

        WhatsAppChat::create($data);

        if($message->sid){
            return response()->json([
                "message" => "Message Sent" ,
                "status" => 200 ,
                "success" => true ,
            ]); 
        }
    }
}

