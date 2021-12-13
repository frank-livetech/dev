<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class DepartmentAssignments extends Model
{
    protected $table = 'deptartment_assignments';
    protected $appends = ['user_name'];
    protected $fillable = [
        'user_id','dept_id','updated_by'
    ];

    public function getUserNameAttribute() {
        $user = DB::table('users')->where('id', $this->user_id)->where('is_deleted', 0)->first();

        if(!empty($user)) {
            return $user->name;
        }

        return null;
    }
}