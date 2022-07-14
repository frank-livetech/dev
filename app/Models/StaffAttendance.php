<?php

namespace App\Models;
use App\User;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class StaffAttendance extends Model
{
    // clocked_out_by => varchar(191)

    protected $table = 'staff_attendance';
    protected $fillable = [ 'user_id','is_break','date', 'clock_in', 'clock_out', 'hours_worked', 'clocked_out_by' ];

    public function user_clocked() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    // public function getClockInAttribute($value) {
    //     $date = new \DateTime($value);
    //     $date->setTimezone(new \DateTimeZone( timeZone() ));
    //     return $date->format(system_date_format() .' h:i a');
    // }
    // public function getClockOutAttribute($value) {
    //     $date = new \DateTime($value);
    //     $date->setTimezone(new \DateTimeZone( timeZone() ));
    //     return $date->format(system_date_format() .' h:i a');
    // }
    // public function getDateAttribute($value) {
    //     return Carbon::parse($value)->format(system_date_format());
    // }
}
