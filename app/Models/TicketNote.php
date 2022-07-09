<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use App\User;
use App\Models\Tickets;

class TicketNote extends Model
{
    /*************************
        @ Column additions
        
        followup_id => int default null

    ************************/

    protected $table = 'ticket_notes';
    protected $fillable = [
        'ticket_id','followup_id','color','type','note','visibility','customer_id','company_id','created_at',' updated_at','created_by',
        'updated_by','deleted_by','deleted_at','is_deleted'
    ];
    protected $appends = ['profile_pic', 'name','tkt_cust_id'];

    public function getNameAttribute() {

        $id = $this->created_by;
        $user = User::where('id', $id)->first();
        return $user->name;
    }
    public function getTktCustIdAttribute() {

        $id = $this->ticket_id;
        $ticket = Tickets::where('id', $id)->first();
        return $ticket->coustom_id;
    }
    public function getProfilePicAttribute() {

        $id = $this->created_by;
        $user = User::where('id', $id)->first();
        if($user){
            return $user->profile_pic;
        }else{
            return '---';
        }
        
    }
}
