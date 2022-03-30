<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    
    public function getWhatsAppMessages(Request $request) {
        \Log::debug($request);
    }

}
