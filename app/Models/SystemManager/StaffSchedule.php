<?php

namespace App\Models\SystemManager;

use Illuminate\Database\Eloquent\Model;

class StaffSchedule extends Model
{
    
    protected $table = 'staff_schedule';
    protected $fillable = [
        'staff_id','schedule_date', 'start_time','end_time','is_holiday','is_leave','created_at','created_by','updated_at','updated_by'
    ];
    
}
