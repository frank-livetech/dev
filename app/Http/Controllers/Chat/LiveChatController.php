<?php

namespace App\Http\Controllers\Chat;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\User;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use DB;

class LiveChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $users = User::where('is_deleted',0)->get();
        // $users = User::where('is_deleted',0)->where('id','!=',\Auth::id())->get();
        return view('chat.index',compact('users'));
    }
}

