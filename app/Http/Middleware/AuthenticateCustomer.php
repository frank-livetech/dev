<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $action = session()->get('action_clicked_admin');
        if($action){
            return $next($request);
        }else{
            if(Auth::user()->user_type == 5){
                return $next($request);
            }
        }

        return redirect()->route('user-login');
    }
}
