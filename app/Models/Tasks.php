<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Tasks extends Model
{
    // completion_time type = varchar(40) null default
    // estimated_time type = varchar(40) null default
    // task_priority type = varchar(40) null default

    
    protected $table = 'tasks';
    protected $fillable = [
        'version', 'title','project_id','task_description','task_status','task_priority','start_date','due_date',
        'completion_time','completed_at','estimated_time','assign_to','remarks','task_type',
        'remarks_by','other_tech','work_tech','created_by','updated_by','created_at','updated_at',
        'is_deleted','deleted_by','sort_id'
    ];

    protected $appends = ['is_overdue'];
    
    public function taskProject() {
        return $this->hasOne(Project::class , 'id','project_id');
    }

    public function taskCreator() {
        return $this->hasOne(User::class , 'id','created_by');
    }
    public function taskAssignedTo() {
        return $this->hasOne(User::class , 'id','assign_to');
    }

    public function taskAttachments() {
        return $this->hasMany(TaskAttachments::class,'task_id','id');
    }

    public function getIsOverdueAttribute(){

        $today_date = date("Y-m-d"); 
        $task_due_date = $this->due_date;
        $est_tm = $this->estimated_time;
        $work_tm = $this->convertSecondsintoHMS($this->worked_time);

        if($task_due_date <= $today_date && $est_tm < $work_tm){
            return 1;
        }elseif($est_tm < $work_tm){
            return 1;
        }else{
            return 0;
        }

    }

    public function convertSecondsintoHMS($seconds) {
        $t = round($seconds);
        return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
    }
}
