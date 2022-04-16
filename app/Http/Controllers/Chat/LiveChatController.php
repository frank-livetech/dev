<?php

namespace App\Http\Controllers\Chat;

use App\Events\SupportChat;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\WhatsAppChat;
use App\Models\SupportMessage;
use Carbon\Carbon;
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

        $users = User::where('is_deleted',0)->where('user_type','!=',5)->get();
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
            "type" => 'whatsapp',
            "status" => 200 ,
            "success" => true ,
        ]);
    }

    public function sendMessage(Request $request)
    {
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
        $from = 'whatsapp:'.$from_number;

        $data = array(
            "from" => $from_number ,
            "to" => $user->whatsapp ,
            "body" => $request->message ,
            "num_media" => 0,
            "media_url" => null,
        );

        WhatsAppChat::create($data);

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

    /**
     * @param Object $request
     * @return response
     *
     * ==========================================
     * Get Support Chat Page
     * ==========================================
     *
     */
    public function getWebMessage(Request $request)
    {
        $id = $request->id;
        // mark all messages with the selected contact as read
        SupportMessage::where('sender_id', $id)->where('receiver_id', auth()->id())->update(['is_seen' => true]);

        // get all messages between the authenticated user and the selected user
        $messages = SupportMessage::where(function($q) use ($id) {
            $q->where('sender_id', auth()->id());
            $q->where('receiver_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('sender_id', $id);
            $q->where('receiver_id', auth()->id());
        })
        ->get();

        return response()->json([
            "data" => $messages,
            "type" => 'web',
            "status" => 200 ,
            "success" => true ,
        ]);
    }

    /**
     * @param Object $request
     * @return response
     *
     * ==========================================
     * Support Chat Page
     * ==========================================
     *
     */
    public function webMessages(Request $request)
    {
        if(request()->has('file')){
            $filename = request('file')->store('support','public');
            $message = SupportMessage::create([
                'sender_id' => auth()->id(),
                'reciever_id' => $request->reciever_id,
                'msg_body' => $filename,
                'msg_type '=> 'file',
                'read_at'=> Carbon::now(),
            ]);
            $msg_type="file";
        }else{
            $message = SupportMessage::create([
                'sender_id' => auth()->id(),
                'reciever_id' => $request->reciever_id,
                'msg_body' => $request->text,
                'msg_type '=> 'text',
                'read_at'=> Carbon::now(),
            ]);
            $msg_type = 'text';
        }

        $user = User::find($message->sender_id);

        event(new SupportChat($message,$message->reciever_id,$user));

        return response()->json([
            'data' => $message,
            'status_code' => 200,
            'message_type' => $msg_type,
            'message' => 'Message Sent Successfully',
            'success' => true
        ]);
    }
}

