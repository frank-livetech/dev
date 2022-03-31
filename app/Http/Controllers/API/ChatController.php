<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsAppChat;

class ChatController extends Controller
{
    
    public function getWhatsAppMessages(Request $request) {
        \Log::debug($request);

        $data = array(
            // "from" => $request->from ,
            // "to" => $request->to ,
            "from" => $request->to ,
            "to" => $request->from ,
            "body" => $request->body ,
            "num_media" => $request->NumMedia ,
            "media_url" => $request->MediaUrl0 ,
        );

        WhatsAppChat::create($data);
    }

}
