<?php

namespace App\Models\SystemManager;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
class Feature extends Model
{
    
    protected $table = 'ac_features';
    protected $appends = ['f_rl_arr'];
    protected $fillable = [
        'title','route', 'sequence','parent_id','is_active','role_id','feature_type','menu_icon',
    ];



    public function getfRlArrAttribute($value)
    {

        $feature_roles = $this->role_id;

        if($feature_roles != null) {
            $f_role_id = explode(',', $feature_roles);
            $rl_arr_name = array();

            for($i = 0; $i < sizeof($f_role_id); $i++) {
                $role = Role::where("id","=",$f_role_id[$i])->first();
                array_push($rl_arr_name, $role->name);   
            }

            return (object)$rl_arr_name;
        }
    }

}
