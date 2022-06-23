<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Models\Role;
use App\Models\Tags;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     * sms => Text field for text messages
     * whatsapp => Text field for whatsapp messages
     */
    protected $fillable = [
        'account_id',
        'name', 
        'email',
        'password',
        'user_type',
        'tags',
        'profile_pic',
        'status',
        'theme',
        'text_dark',
        'bg-dark','text-light','bg-light','created_by','updated_by','is_deleted','deleted_by',
        'sms','whatsapp','address','apt_address','phone_number','country','state','city','twitter',
        'pinterest','fb','insta','job_title','zip','notes','alt_pwd','website','is_support_staff',
        'created_at','updated_at','device_token', 'signature',
    'linkedin','google_id','company_id','privacy_policy','phone_type'
    ];

    protected $appends = ['role','staffTags'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRoleAttribute() {

        $id = $this->user_type;
        $role = Role::where('id',$id)->first();
        return $role->name;
    }

    public function getCreatedAtAttribute($value) {
        if($value != null) {
            $date = new \DateTime($value);
            $date->setTimezone(new \DateTimeZone( timeZone() ));                            
            return $date->format(system_date_format() .' h:i a');
        }
    }

    public function getStaffTagsAttribute() {
        $tags_arr = explode(",",$this->tags);
        $arr = Tags::select('name')->whereIn('id',$tags_arr)->get();
        return $this->attributes['staffTags'] = collect($arr)->implode('name', ', ');
    }

    public function staffProfile() {
        return $this->hasOne(Models\StaffProfile::class);
    }
    
    public function manager() {
        return $this->belongsTo(Models\Project::class);
    }
    
    public function ticketreplyuser() {
        return $this->belongsTo(Models\TicketReply::class);
    }
    
    public function company() {
        return $this->belongsToMany('App\Models\Company','company_user');
    }
    public function tickets(){
        return $this->hasMany(Models\Tickets::class,'assigned_to','id');
    }
    
    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
