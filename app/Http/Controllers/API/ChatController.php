<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WhatsAppChat;

class ChatController extends Controller
{
    
    public function getWhatsAppMessages(Request $request) {
        \Log::debug($request);

        $fromNumber = $request->From;
        $from = explode(':', $fromNumber);


        $toNumber = $request->To;
        $to = explode(':', $toNumber);


        $data = array(
            "from" => $from[1] ,
            "to" => $to[1] ,
            "body" => $request->Body ,
            "num_media" => $request->NumMedia ,
            "media_url" => $request->MediaUrl0 ,
        );

        WhatsAppChat::create($data);
    }

}
