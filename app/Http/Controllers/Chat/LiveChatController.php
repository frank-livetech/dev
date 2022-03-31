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

        $users = User::where('is_deleted',0)->where('id','!=', auth()->id())->where('user_type','!=',5)->get();
        $whatsapp_chat = WhatsAppChat::all();

        return view('chat.index-new', get_defined_vars() );

    }


    // get whatsapp messages
    public function getWhatsAppMessage(Request $request) {
        $user = User::where('id', $request->id)->first();

        if( empty($user) || $user->whatsapp == null ) {
            return response()->json([
                "message" => "User Not Found" ,
                "status" => 500 ,
                "success" => false ,
            ]);
        }

        // $messages = WhatsAppChat::where('to',$user->whatsapp)->get();
        $number = $user->whatsapp;
        $messages = WhatsAppChat::where(function($q) use ($number) { 

            $q->where('from', '+14155238886');
            $q->where('to', $number);

        })->orWhere(function($q) use ($number) {

            $q->where('from', $number);
            $q->where('to', '+14155238886');

        })
        ->get();

        return response()->json([
            "data" => $messages,
            "number" => $number,
            "status" => 200 ,
            "success" => true ,
        ]);
    }


    public function sendMessage(Request $request) {
        $user = User::where('id' , $request->user_to)->first();

        if($user->whatsapp == null || empty($user)) {
            return response()->json([
                "message" => "User Not Found" ,
                "status" => 500 ,
                "success" => false ,
            ]);
        }

        $sid = "AC900992cf2053493cb74010084d98d5ed";
        $token = "9a2bf65f24c24a161bee9671a43949a8";
        
        $to = 'whatsapp:'.$user->whatsapp;


        $from_number = '+14155238886';

        $from = 'whatsapp:+'.$from_number;

        $data = array(
            "from" => $from_number ,
            "to" => $user->whatsapp ,
            "body" => $request->message ,
            "num_media" => 0,
            "media_url" => null,
        );

        WhatsAppChat::create($data);


        // return response()->json([
        //     "message" => "Message Sent" ,
        //     "status" => 200 ,
        //     "success" => true ,
        // ]); 

        $client = new Client($sid, $token);
        $message = $client->messages->create( $to , 
        [
            'from' => $from ,
            'body' => $request->message ,
        ]
        );
        
        if($message->sid){
            return response()->json([
                "message" => "Message Sent" ,
                "status" => 200 ,
                "success" => true ,
            ]); 
        }
    }
}

