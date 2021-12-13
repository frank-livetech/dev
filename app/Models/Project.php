<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Project extends Model
{
    protected $table = 'projects';
    protected $fillable = [
        'name', 'project_type','project_slug','project_roadmap_table','folder_id','customer_id',
        'project_manager_id','description','created_by','updated_by','created_at','updated_at','is_deleted','deleted_by','deleted_at'
    ];
    
    public function projectCustomer() {
        return $this->hasOne(Customer::class,'id','customer_id');
    }
    
    public function projectManager() {
        return $this->hasOne(User::class,'id','project_manager_id');
    }

    public function projectTasks() {
        return $this->hasMany(Tasks::class,'id','project_id');
    }
}
