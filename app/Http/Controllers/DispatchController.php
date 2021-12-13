<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Tickets;
use App\Models\Dispatch;
use App\User;
use Illuminate\Support\Facades\DB;

class DispatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function watch(){
        $customers = Customer::where("is_deleted","=",0)->get();
        $tickets = Tickets::where("is_deleted","=",0)->get();
        // dd( $customers);
        // dd( $tickets);
        $users = User::with('staffProfile')->where('is_deleted', 0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)->get();
        $google= DB::Table("integrations")->where("slug","=","google-api")->first();
        $google =json_decode($google->details,true);
        return view('dispatch.watch.index',compact('google','customers','tickets','users'));
    } 
     public function coming_soon(){
        return view('dispatch.comingsOon');
    } 
     public function coming_soon1(){
        return view('dispatch.comingsoon');
    } 
    public function save_watch(Request $request){
        // dd($request->all());
        Dispatch::create($request->all());
        return redirect('watch');

    }
}
