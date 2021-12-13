<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mail extends Model
{
    
    protected $table = 'email_queues';
    protected $fillable = [
        'mail_queue_address','queue_type','protocol','queue_template','is_enabled','registration_required','autosend','mailserver_hostname','mailserver_port','mailserver_username','mailserver_password','from_name','from_mail','mail_dept_id','php_mailer','mail_type_id','mail_status_id','mail_priority_id','created_by','created_at','updated_by','updated_at','deleted_by','deleted_at','is_deleted','outbound','is_default'
    ];
}
