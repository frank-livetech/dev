<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class StaffLeaves extends Model
{
    protected $table = 'staff_leaves';
    protected $fillable = [ 'start_date','end_date','reason','status','requested_by','processed_by'];

    public function staff() {
        return $this->hasOne(User::class , 'id','requested_by');
    }
}
