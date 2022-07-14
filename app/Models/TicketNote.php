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
    public $appends = ['profile_pic', 'name', 'tkt_cust_id', 'cdate', 'udate'];
    public $fillable = [
        'ticket_id','followup_id','color','type','note','visibility','customer_id','company_id','created_at',' updated_at','created_by',
        'updated_by','deleted_by','deleted_at','is_deleted'
    ];

    public function getNameAttribute() {

        $id = $this->created_by;
        $user = User::where('id', $id)->first();
        if($user){
            return $user->name;
        }else{
            return '---';
        }
    }

    public function getCdateAttribute() {
        $date = new \DateTime( $this->created_at );
        $date->setTimezone(new \DateTimeZone( timeZone() ));
        return $date->format(system_date_format() .' h:i a');
    }

    public function getUdateAttribute() {

        $date = new \DateTime( $this->updated_at );
        $date->setTimezone(new \DateTimeZone( timeZone() ));
        return $date->format(system_date_format() .' h:i a');
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
