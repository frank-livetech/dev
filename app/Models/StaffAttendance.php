<?php

namespace App\Models;
use App\User;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    // clocked_out_by => varchar(191)
    
    protected $table = 'staff_attendance';
    protected $fillable = [
        'user_id', 'clock_in', 'clock_out', 'hours_worked', 'clocked_out_by'
    ];

    public function user_clocked() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
