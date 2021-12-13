<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    
    protected $table = 'staff_profiles';
    protected $fillable = [
        'user_id','role_id','phone','tags'
    ];

    public function user() {
        return $this->belongsTo(App\User::class);
    }

}
