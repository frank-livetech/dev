<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketNote extends Model
{
    /*************************
        @ Column additions
        
        followup_id => int default null

    ************************/

    protected $table = 'ticket_notes';
    protected $fillable = [
        'ticket_id','followup_id','color','type','note','visibility','created_at',' updated_at','created_by',
        'updated_by','deleted_by','deleted_at','is_deleted'
    ];
}
