<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Notification;

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
        //View::share('numberAlerts',Notification::numberAlert());
    }
}
