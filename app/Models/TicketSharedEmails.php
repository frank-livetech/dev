<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketSharedEmails extends Model
{
    protected $table = 'tkt_shared_mails';
    protected $fillable = [
        'email',
        'ticket_id',
        'mail_type',
    ];

}
