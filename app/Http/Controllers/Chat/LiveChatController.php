<?php

namespace App\Http\Controllers\Chat;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use DB;
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
        $users = User::where('is_deleted',0)->where('id','!=',\Auth::id())->get();
        // return $users;
        // return view('chat.index',compact('users'));
        return view('chat.index-new',compact('users'));

    }


    public function sendMessage() {
        // return dd( request()->all() );

        $sid = "AC900992cf2053493cb74010084d98d5ed";
        $token = "9a2bf65f24c24a161bee9671a43949a8";

        $client = new Client($sid, $token);
        $message = $client->messages->create('+92 303 0560951', // Text this number
        [
            'from' => '+14155238886', // From a valid Twilio number
            'body' => 'Hello from Laravel!'
        ]
        );

        print $message->sid;
    }
}

