<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class ProjectNotes extends Model
{
    protected $table = 'project_notes';
    protected $fillable = [
        'project_id', 'color','note','created_at','updated_at'
    ];
}
