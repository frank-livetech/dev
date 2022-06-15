<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\TicketReply;
use App\Models\Activitylog;
use App\User;
use Session;
use App\Models\Customer;
use DB;
use Genert\BBCode\BBCode;

class Tickets extends Model
{
    protected $table = 'tickets';
    protected $appends = ['department_name', 'priority_name','priority_color', 'status_name','status_color', 'type_name', 'creator_name','assignee_name','customer_name','lastReplier','replies','lastActivity','user_pic','last_reply','tkt_notes','tkt_follow_up'];
    protected $fillable = [

        'dept_id','priority','assigned_to','subject','queue_id','customer_id','res_updated_at','ticket_detail','status','type','is_flagged','coustom_id','seq_custom_id','deadline','is_staff_tkt','is_overdue','created_by','updated_by','created_at','updated_at','is_deleted','deleted_at','trashed', 'reply_deadline', 'resolution_deadline', 'attachments','tkt_crt_type','is_pending','cust_email','embed_attachments'

    ];

    public function ticket_created_by() {
        return $this->hasOne(User::class,'id','created_by');
    }
    
    public function ticketReplies() {
        return $this->hasMany(TicketReply::class,'ticket_id','id')->with('replyUser');
    }

    public function ticket_customer() {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function getRepliesAttribute() {

        $id = $this->id;
        $repCount = TicketReply::where('ticket_id', $id)->count();
        return $repCount;
    }

    public function getLastReplyAttribute() {
        $id = $this->id;
        $last_reply = TicketReply::where('ticket_id', $id)->with('replyUser')->orderByDesc('id')->first();
        
        if($last_reply){
            $bbcode = new BBCode();
            $last_reply->reply = str_replace('/\r\n/','<br>', $bbcode->convertToHtml($last_reply->reply));
        }
        
        return $last_reply;
    }

    public function getTktNotesAttribute() {
        $id = $this->id;
        $tkt_notes = TicketNote::where('ticket_id' , $id)->count();
        return $tkt_notes;
    }

    public function getTktFollowUpAttribute() {
        $id = $this->id;
        $tkt_follow_up = TicketFollowUp::where('ticket_id' , $id)->count();
        return $tkt_follow_up;
    }

    public function getLastActivityAttribute() {

        // $id = $this->id;
        // $lastActivity = Activitylog::where('module', 'Tickets')->where('ref_id', $id)->orderBy('created_at', 'desc')->value('created_at');
        return $this->updated_at;
    }
    public function getStatusColorAttribute() {

        $id = $this->status;
        if(!empty($id)) {
            $data = DB::table('ticket_statuses')->where('id', $id)->first();
            if(!empty($data)) return $data->color;
        }
        return null;
    }
    public function getLastReplierAttribute() {

        $id = $this->id;
        $rep = TicketReply::where('ticket_id', $id)->orderBy('created_at', 'desc')->first();
      
        if(!empty($rep)) {
            if($rep['user_id']) {
                $user = User::where('id', $rep['user_id'])->first();
                if(!empty($user)) return $user->name;
            } else if($rep['customer_id']) {
                $user = Customer::where('id', $rep['customer_id'])->first();
                if(!empty($user)) return  $user->first_name.' '.$user->last_name;
            }
            
        }
    }
    public function getPriorityColorAttribute() {

        $id = $this->priority;
        if(!empty($id)) {
            $data = DB::table('ticket_priorities')->where('id', $id)->first();
            if(!empty($data)) return $data->priority_color;
        }
        return null;
    }

    public function getDepartmentNameAttribute() {

        $id = $this->dept_id;
        if(!empty($id)) {
            $data = DB::table('departments')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getCustomerNameAttribute() {

        $id = $this->customer_id;
        $is_stf_tkt = $this->is_staff_tkt;

        if(!empty($id)) {
            if($is_stf_tkt == 0){
                $data = DB::table('customers')->where('id', $id)->first();
                if(!empty($data)) return $data->first_name .' '. $data->last_name;
            }else{
                $data = DB::table('users')->where('id', $id)->first();
                if(!empty($data)) return $data->name;
            }
            
        }
        return null;
    }

    public function getUserPicAttribute() {

        $data = DB::table('users')->where('id', $this->created_by)->first();
        $path = Session::get('is_live') == 1 ? '/public' . '/' : '/';

        if(!empty($data)) {

            if($data->profile_pic != null) {
                if(file_exists( getcwd() .'/'. $data->profile_pic )) {
                    $image = $data->profile_pic;
                }else{
                    $image = $path . 'default_imgs/customer.png';
                }
            }else{
                $image = $path . 'default_imgs/customer.png';
            }

            return $this->user_pic = $image;
        }else{
            $customer = DB::table('customers')->where('id', $this->created_by)->first();
            if(!empty($customer)) {
                
                if($customer->avatar_url!= null) {
                    if(is_file( getcwd() . $path . $customer->avatar_url)) {
                        $image = $customer->avatar_url;
                    }else{
                        $image = $path . 'default_imgs/customer.png';
                    }
                }else{
                    $image = $path . 'default_imgs/customer.png';
                }
                return $this->user_pic = $image;
            }
        }
    }

    public function getAssigneeNameAttribute() {

        $id = $this->assigned_to;
        if(!empty($id)) {
            if($id == 'Unassigned'){
                return 'Unassigned';
            }
            $data = DB::table('users')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getPriorityNameAttribute() {

        $id = $this->priority;
        if(!empty($id)) {
            $data = DB::table('ticket_priorities')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getStatusNameAttribute() {

        $id = $this->status;
        if(!empty($id)) {
            $data = DB::table('ticket_statuses')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }

    public function getTypeNameAttribute() {

        $id = $this->type;
        if(!empty($id)) {
            $data = DB::table('ticket_types')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }
    
    public function getCreatorNameAttribute() {
        $id = $this->created_by;
        if(!empty($id)) {
            $data = DB::table('users')->where('id', $id)->first();
            if(!empty($data)) return $data->name;
        }
        return null;
    }
}
