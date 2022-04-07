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

        
        if( $request->MediaContentType0 ) {
            
            $type = explode( '/' , $request->MediaContentType0 );
            
            // if type is image
            if($type[0] == 'image') {
                
                $url = $request->MediaUrl0 ;
                $contents = file_get_contents($url);
                $name = (time() + 1) .'.'. $type[1];
                
                Storage::put( 'public/whatsapp_chat/images/' . $name, $contents);
                $data['media_url'] = 'public/whatsapp_chat/images/' . $name;
            }
            
            if($type[0] == 'audio') {
             
                $url = $request->MediaUrl0 ;
                $contents = file_get_contents($url);
                $name = (time() + 2) .'.'. $type[1];
                Storage::put( 'public/whatsapp_chat/audio/' . $name, $contents);
                $data['media_url'] = 'public/whatsapp_chat/images/' . $name;
            
            }
            
            if($type[0] == 'video') {
                
                $url = $request->MediaUrl0 ;
                $contents = file_get_contents($url);
                $name = (time() + 5) .'.'. $type[1];
                Storage::put( 'public/whatsapp_chat/video/' . $name, $contents);
                $data['media_url'] = 'public/whatsapp_chat/images/' . $name;
                
            }
            
            
            
        }
        
        
        WhatsAppChat::create($data);
    }

}
