<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\TicketReply;
use App\Models\Activitylog;
use App\User;
use App\Models\Customer;

use DB;

class Tickets extends Model
{
    protected $table = 'tickets';
    protected $appends = ['department_name', 'priority_name','priority_color', 'status_name','status_color', 'type_name', 'creator_name','assignee_name','customer_name','lastReplier','replies','lastActivity'];
    protected $fillable = [
        'dept_id','priority','assigned_to','subject','customer_id','res_updated_at','ticket_detail','status','type','is_flagged','coustom_id','seq_custom_id','deadline','is_staff_tkt','is_overdue','created_by','updated_by','created_at','updated_at','is_deleted','deleted_at','trashed', 'reply_deadline', 'resolution_deadline', 'attachments','tkt_crt_type','is_pending','cust_email'
    ];
    
    public function ticketReplies() {
        return $this->hasMany(TicketReply::class,'ticket_id','id')->with('replyUser');
    }

    public function ticket_customer() {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function getRepliesAttribute() {

        $id = $this->id;
        $repCount = TicketReply::where('ticket_id', $id)->count();
        return $repCount;
    }

    public function getLastActivityAttribute() {

        // $id = $this->id;
        // $lastActivity = Activitylog::where('module', 'Tickets')->where('ref_id', $id)->orderBy('created_at', 'desc')->value('created_at');
        return $this->updated_at;
    }
    public function getStatusColorAttribute() {

        $id = $this->status;
        if(!empty($id)) {
            $data = DB::table('ticket_statuses')->where('id', $id)->first();
            if(!empty($data)) return $data->color;
        }
        return null;
    }
    public function getLastReplierAttribute() {

        $id = $this->id;
        $rep = TicketReply::where('ticket_id', $id)->orderBy('created_at', 'desc')->first();
      
        if(!empty($rep)) {
            if($rep['user_id']) {
                $user = User::where('id', $rep['user_id'])->first();
                if(!empty($user)) return $user->name;
            } else if($rep['customer_id']) {
                $user = Customer::where('id', $rep['customer_id'])->first();
                if(!empty($user)) return  $user->first_name.' '.$user->last_name;
            }
            
        }
    }
    public function getPriorityColorAttribute() {

        $id = $this->priority;
        if(!empty($id)) {
            $data = DB::table('ticket_priorities')->where('id', $id)->first();
            if(!empty($data)) return $data->priority_color;
        }
        return null;
    }

    public function getDepartmentNameAttribute() {

        $id = $this->dept_id;
        if(!empty($id)) {
            $data = DB::table('departments')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getCustomerNameAttribute() {

        $id = $this->customer_id;
        $is_stf_tkt = $this->is_staff_tkt;

        if(!empty($id)) {
            if($is_stf_tkt == 0){
                $data = DB::table('customers')->where('id', $id)->first();
                if(!empty($data)) return $data->first_name .' '. $data->last_name;
            }else{
                $data = DB::table('users')->where('id', $id)->first();
                if(!empty($data)) return $data->name;
            }
            
        }
        return null;
    }

    public function getAssigneeNameAttribute() {

        $id = $this->assigned_to;
        if(!empty($id)) {
            if($id == 'Unassigned'){
                return 'Unassigned';
            }
            $data = DB::table('users')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getPriorityNameAttribute() {

        $id = $this->priority;
        if(!empty($id)) {
            $data = DB::table('ticket_priorities')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getStatusNameAttribute() {

        $id = $this->status;
        if(!empty($id)) {
            $data = DB::table('ticket_statuses')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getTypeNameAttribute() {

        $id = $this->type;
        if(!empty($id)) {
            $data = DB::table('ticket_types')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }
    
    public function getCreatorNameAttribute() {
        $id = $this->created_by;
        if(!empty($id)) {
            $data = DB::table('users')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }
}
