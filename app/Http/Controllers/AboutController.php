<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function (Request $request, $next) {
            if (auth()->user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }
    public function system_info(){
        return view('about.system_info.index');
    }
    
    public function task_scripts(){
        return view('about.system_info.task_scripts');
    }
    
    public function feature_suggestions(){
        return view('about.featuresuggestions.index-new');

    }
}
