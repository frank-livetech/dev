<?php

namespace App\Http\Controllers\SystemManager;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TemplatebuilderController extends Controller
{
    public function template_builder(){
    return view('system_manager.template_builder.index');
}
}