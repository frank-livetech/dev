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

        // WhatsAppChat::create($data);

        // for audio
        // if( str_contains($request->MediaContentType0 , 'audio') ) {
            // $url = $request->MediaUrl0 ;
            // $contents = file_get_contents($url);
            // $name = (time() + 2) . '.mp3';
            // Storage::put('public/whatsapp_chat/video/' . $name, $contents);
        // }
        
        // for image
        // if( str_contains($request->MediaContentType0 , 'image') ) {
        //     if($request->MediaUrl0) {
        //         $url = $request->MediaUrl0 ;
        //         $contents = file_get_contents($url);
        //         $name = time() . '.png';
                
        //         Storage::put('public/whatsapp_chat/' . $name, $contents);
                
        //         $data['media_url'] = 'storage/whatsapp_chat/images/' . $name;
        //     }
        // }

        
        // WhatsAppChat::create($data);
    }

}
