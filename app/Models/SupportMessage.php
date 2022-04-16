<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportMessage extends Model
{
    protected $table = 'web_chats';
    protected $fillable = [
                'sender_id',
                'reciever_id',
                'msg_body',
                'msg_type',
                'read_at'
            ];
}
