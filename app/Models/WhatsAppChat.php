<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppChat extends Model
{
    protected $table = 'whatsapp_chat';
    protected $fillable = [ 'from', 'to','body','num_media' ,'media_url' , 'media_type'];
}
