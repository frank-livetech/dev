<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class SessionDataCheckMiddleware {

    /**
     * Check session data, if role is not valid logout the request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        
        $bag = Session::all();
        print_r($bag);exit;
        $max = config('session.lifetime') * 60; // min to hours conversion
        
        if (($bag && $max < (time() - $bag->getLastUsed())) || empty($bag)) {
        //     print_r($bag);exit;
        // return $bag;
            //$request->session()->flush(); // remove all the session data
            
            Auth::logout(); // logout user
            // return redirect()->to('/login');
        return $next($request);

            
        }
        return $next($request);


        return $next($request);
    }

}