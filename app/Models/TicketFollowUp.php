<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketFollowUp extends Model
{
    /******* Columns ********
    
        'is_recurring' type => boolean default 0
        'recurrence_pattern' type => varchar 191 default null
        'recurrence_start' type => timestamp default null
        'recurrence_end_type' type => varchar 100 default null
        'recurrence_end_val' type => varchar 191 default null

    *******/

    protected $table = 'ticket_follow_up';
    protected $appends = ['department_name', 'priority_name', 'status_name', 'type_name', 'tech_name', 'project_name', 'creator_name'];
    protected $fillable = [
        'ticket_id', 'schedule_type', 'custom_date', 'schedule_time', 'follow_up_dept_id', 'follow_up_priority', 'follow_up_assigned_to', 'follow_up_status', 'follow_up_type', 'follow_up_project', 'follow_up_notes','follow_up_notes_color','follow_up_notes_type', 'follow_up_reply','is_recurring', 'recurrence_time', 'recurrence_time2', 'recurrence_pattern', 'recurrence_start', 'recurrence_end_type', 'recurrence_end_val', 'passed', 'created_at', 'updated_at', 'created_by', 'updated_by', 'is_deleted', 'deleted_at', 'deleted_by'
    ];

    public function ticket() {
        return $this->belongsTo(Tickets::class, 'ticket_id','id');
    }

    public function ticket_user() {
        return $this->hasOne('App\User', 'id','created_by');
    }

    public function getDepartmentNameAttribute() {
        $dept = DB::table('departments')->where('id', $this->follow_up_dept_id)->first();
        
        if(!empty($dept)) {
            return $dept->name;
        }

        return null;
    }

    public function getPriorityNameAttribute() {
        $data = DB::table('ticket_priorities')->where('id', $this->follow_up_priority)->first();
        
        if(!empty($data)) {
            return $data->name;
        }

        return null;
    }

    public function getStatusNameAttribute() {
        $data = DB::table('ticket_statuses')->where('id', $this->follow_up_status)->first();
        
        if(!empty($data)) {
            return $data->name;
        }

        return null;
    }

    public function getTypeNameAttribute() {
        $data = DB::table('ticket_types')->where('id', $this->follow_up_type)->first();
        
        if(!empty($data)) {
            return $data->name;
        }

        return null;
    }

    public function getTechNameAttribute() {
        $data = DB::table('users')->where('id', $this->follow_up_assigned_to)->first();
        
        if(!empty($data)) {
            return $data->name;
        }

        return null;
    }
    
    public function getProjectNameAttribute() {
        $data = DB::table('projects')->where('id', $this->follow_up_project)->first();
        
        if(!empty($data)) {
            return $data->name;
        }

        return null;
    }
    
    public function getCreatorNameAttribute() {
        $data = DB::table('users')->where('id', $this->created_by)->first();
        
        if(!empty($data)) {
            return $data->name;
        }

        return null;
    }
}
