<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttachments extends Model
{
    protected $table = 'task_attachments';
    protected $fillable = [
        'project_id','task_id','attachment','created_at','created_by',' updated_at','updated_by',
        'is_deleted','deleted_at','deleted_by'
    ];

    public function task() {
        return $this->belongsTo(Tasks::class);
    }
}
