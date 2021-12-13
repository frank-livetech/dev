<?php

namespace App\Http\Controllers\CustomerManager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerStatsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        return view('customer_manager.customer_stats.index');
    }


}
