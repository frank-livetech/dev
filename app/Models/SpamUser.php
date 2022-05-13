<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamUser extends Model
{
    protected $table = 'spam_users';
    protected $fillable = [
        'email','banned_by'
    ];
}
