<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class TicketFollowupLogs extends Model
{

    protected $table = 'tkt_followup_logs';

    protected $fillable = [
        'ticket_id',
        'follow_up_id',
        'is_cron',
        'is_frontend',
        'schedule_type',
        'custom_date',
        'schedule_time',
        'old_dept_id',
        'old_priority',
        'old_assigned_to',
        'old_status',
        'old_type',
        'new_dept_id',
        'new_priority',
        'new_assigned_to',
        'new_status',
        'new_type',
        'follow_up_project',
        'follow_up_notes',
        'follow_up_notes_color',
        'follow_up_notes_type',
        'follow_up_reply',
        'is_recurring',
        'recurrence_time',
        'recurrence_time2',
        'recurrence_pattern',
        'recurrence_start',
        'recurrence_end_type',
        'recurrence_end_val',
        'passed',
        'data',
        'created_at',
        'updated_at',
        'created_by', 
        'updated_by', 
        'is_deleted', 
        'deleted_at', 
        'deleted_by'
    ];

}
