<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionController extends Controller
{
    //

    public function index(){
        return view("permission.index");
    }

}
