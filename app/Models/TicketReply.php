<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\User;

class TicketReply extends Model
{
    // cc => type text

    protected $table = 'ticket_replies';

    protected $fillable = [
        'ticket_id', 'user_id', 'customer_id', 'msgno', 'reply', 'cc', 'date', 'type','is_published', 'created_at', 'updated_at', 'attachments','embed_attachments','is_deleted'
    ];

    public function ticket() {
        return $this->belongsTo(Tickets::class);
    }

    public function replyUser(){
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function customerReplies(){
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function updatedByUser(){
        return $this->belongsto(User::class,'updated_by','id');
    }

}
