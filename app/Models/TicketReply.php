<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketReply extends Model
{
    // cc => type text
    
    protected $table = 'ticket_replies';

    protected $fillable = [
        'ticket_id', 'user_id', 'customer_id', 'msgno', 'reply', 'cc', 'date', 'is_published', 'created_at', 'updated_at', 'attachments'
    ];
    
    public function ticket() {
        return $this->belongsTo(App\Models\Tickets::class);
    }
    
    public function replyUser(){
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function customerReplies(){
        return $this->hasOne(\App\Customer::class,'id','customer_id');
    }
}
