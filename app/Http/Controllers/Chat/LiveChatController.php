<?php

namespace App\Http\Controllers\Chat;

use App\Events\SupportChat;
use App\Http\Controllers\Controller;
use App\Jobs\SuportChat;
use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Models\WhatsAppChat;
use App\Models\SupportMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;
use Pusher\Pusher;
use Illuminate\Support\Facades\File;


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


        $unreadIds = SupportMessage::select(DB::raw('sender_id, count(`sender_id`) as messages_count'))
            ->where('reciever_id', auth()->id())
            ->where('read_at', null)
            ->groupBy('sender_id')
            ->get();

        // add an unread key to each contact with the count of unread messages
        $users = $users->map(function($user) use ($unreadIds) {
            $contactUnread = $unreadIds->where('sender_id', $user->id)->first();
            $user->unread = $contactUnread ? $contactUnread->messages_count : 0;
            return $user;
        });

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
        $support = SupportMessage::where('sender_id', $id)->where('reciever_id', auth()->id())
                                    ->orWhere('sender_id', Auth::id())
                                    ->orWhere('reciever_id',$id);
        if($support->get() != null){
            $support->update(['read_at' => Carbon::now()]);
            // get all messages between the authenticated user and the selected user
            $messages = SupportMessage::where(function($q) use ($id) {
                $q->where('sender_id', auth()->id());
                $q->where('reciever_id', $id);
            })->orWhere(function($q) use ($id) {
                $q->where('sender_id', $id);
                $q->where('reciever_id', auth()->id());
            })
            ->get();
        }else{
            $messages = null;
        }


        return response()->json([
            "data" => $messages,
            "unread" => $support->where('read_at',null)->count(),
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
    public function sendWebMessages(Request $request)
    {
        if(request()->has('file')){
            $file = request('file');
            $fileName = $file->getClientOriginalName();
            $fileName = strtolower($fileName);
            $fileName = str_replace(" ","_",$fileName);

            $target_dir = 'storage/support';

            if (!File::isDirectory($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file->move($target_dir, $fileName);
            $file_path = $target_dir.'/'.$fileName;

            $msg_type="file";
            $message = SupportMessage::create([
                'sender_id' => auth()->id(),
                'reciever_id' => $request->user_to,
                'msg_body' => $file_path,
                'type '=> 'file',
            ]);

        }else{
            $msg_type = 'text';
            $message = SupportMessage::create([
                'sender_id' => auth()->id(),
                'reciever_id' => $request->user_to,
                'msg_body' => $request->message,
                'type '=> $msg_type,
            ]);

        }

        $user = User::find($message->sender_id);

        $supportJob = (new SuportChat($message,$message->reciever_id,$user));
        dispatch($supportJob);


        return response()->json([
            'data' => $message,
            'status_code' => 200,
            'message_type' => $msg_type,
            'message' => 'Message Sent',
            'success' => true
        ]);
    }

    /**
     * @param null
     * @return integer
     *
     * ==========================================
     * Support Chat message unread count
     * ==========================================
     *
     */
    public function unreadMessages()
    {
        $support = SupportMessage::where('read_at',null)->where('reciever_id',Auth::id())->get();

        return response()->json([
            'data' =>$support,
            'counts' =>$support->count(),
            'status' => 200,
            'success' => true,
        ]);
    }
}

