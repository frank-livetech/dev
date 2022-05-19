<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class SpamUser extends Model
{
    protected $table = 'spam_users';
    protected $fillable = [ 'email','banned_by' ];



    public function banned_by_user() {
        return $this->hasOne(User::class , 'id','banned_by');
    }
}
