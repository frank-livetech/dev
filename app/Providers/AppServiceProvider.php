<?php

namespace App\Providers;

use App\Models\Customer;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Notification;
use App\User;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        schema::defaultStringLength(191);

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.*', function ($view) {
            $usr_id = \Auth::user()->id;
            $view->with('numberAlerts', Notification::numberAlert($usr_id));
        });
        // View::share('numberAlerts',Notification::numberAlert());

        View::composer('*', function($view){
            if(session()->get('action_clicked_admin')){
                $user = User::where('email', session()->get('action_clicked_admin'))->first();
            }else{
                $user = User::find(Auth::id());
            }
            $view->with('user',$user);
        });

        // View::composer('*', function($view){
        //     $customers = Customer::where('is_deleted', 0)->get();
        //     $view->with('user',$customers);
        // });

        // View::share('layout.master-layout-new',Customer::where('is_deleted', 0)->get());

    }
}
