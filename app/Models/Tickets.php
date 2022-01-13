<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use DB;

class Tickets extends Model
{
    protected $table = 'tickets';
    protected $appends = ['department_name', 'priority_name', 'status_name', 'type_name', 'creator_name','assignee_name'];
    protected $fillable = [
        'dept_id','priority','assigned_to','subject','customer_id','res_updated_at','ticket_detail','status','type','is_flagged','coustom_id','seq_custom_id','deadline','created_by','updated_by','created_at','updated_at','is_deleted','deleted_at','trashed', 'reply_deadline', 'resolution_deadline', 'attachments'
    ];
    
    public function ticketReplies() {
        return $this->hasMany(TicketReply::class,'ticket_id','id')->with('replyUser');
    }

    public function ticket_customer() {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function getDepartmentNameAttribute() {
        // $depts = DB::table('departments')->get();
        // $did = $this->dept_id;

        // if(empty($did) || empty($depts)) {
        //     return null;
        // }

        // foreach ($depts as $key => $value) {
        //     if($value->id == $did) {
        //         return $value->name;
        //     }
        // }

        $id = $this->dept_id;
        if(!empty($id)) {
            $data = DB::table('departments')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getAssigneeNameAttribute() {
        // $pr_list = DB::table('ticket_priorities')->get();
        // $pr = $this->priority;

        // if(empty($pr) || empty($pr_list)) {
        //     return null;
        // }

        // foreach ($pr_list as $key => $value) {
        //     if($value->id == $pr) {
        //         return $value->name;
        //     }
        // }

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
        // $pr_list = DB::table('ticket_priorities')->get();
        // $pr = $this->priority;

        // if(empty($pr) || empty($pr_list)) {
        //     return null;
        // }

        // foreach ($pr_list as $key => $value) {
        //     if($value->id == $pr) {
        //         return $value->name;
        //     }
        // }

        $id = $this->priority;
        if(!empty($id)) {
            $data = DB::table('ticket_priorities')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getStatusNameAttribute() {
        // $st_list = DB::table('ticket_statuses')->get();
        // $st = $this->status;

        // if(empty($st) || empty($st_list)) {
        //     return null;
        // }

        // foreach ($st_list as $key => $value) {
        //     if($value->id == $st) {
        //         return $value->name;
        //     }
        // }

        $id = $this->status;
        if(!empty($id)) {
            $data = DB::table('ticket_statuses')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getTypeNameAttribute() {
        // $types = DB::table('ticket_types')->get();
        // $tid = $this->type;

        // if(empty($tid) || empty($types)) {
        //     return null;
        // }

        // foreach ($types as $key => $value) {
        //     if($value->id == $tid) {
        //         return $value->name;
        //     }
        // }

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
