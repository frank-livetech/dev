<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use App\Models\TicketReply;
use App\Models\Activitylog;
use App\Models\TicketSettings;
use App\User;
use App\Models\SlaPlan;
use App\Models\SlaPlanAssoc;
use Session;
use App\Models\Customer;
use DB;
use Genert\BBCode\BBCode;
use Carbon\Carbon;

class Tickets extends Model
{
    protected $table = 'tickets';
    protected $appends = ['sla_plan','sla_rep_deadline_from','sla_res_deadline_from','is_overdue','department_name', 'priority_name','priority_color', 'status_name','status_color', 'type_name', 'creator_name','assignee_name','customer_name','lastReplier','replies','lastActivity','user_pic','last_reply','tkt_notes','tkt_follow_up'];
    protected $fillable = [

        'dept_id','priority','assigned_to','subject','queue_id','customer_id','res_updated_at','ticket_detail','status','type','is_flagged','coustom_id','seq_custom_id','deadline','is_staff_tkt','is_overdue','created_by','updated_by','created_at','updated_at','is_deleted','deleted_at','trashed', 'reply_deadline', 'resolution_deadline', 'attachments','tkt_crt_type','is_pending','cust_email','embed_attachments'

    ];
    
    const DEFAULTSLA_TITLE = 'Default SLA';
    const NOSLAPLAN = 'No SLA Assigned';

    public function ticket_created_by() {
        return $this->hasOne(User::class,'id','created_by');
    }

    public function ticketReplies() {
        return $this->hasMany(TicketReply::class,'ticket_id','id')->with('replyUser');
    }

    public function ticket_customer() {
        return $this->hasOne(Customer::class,'id','customer_id');
    }

    public function activityLog()
    {
        return $this->hasMany(Activitylog::class,'ref_id','id');
    }
    public function getSlaPlanAttribute(){
        $id = $this->id;
        $sla_plan = $this->getTicketSlaPlan($id);
        return $sla_plan;
    }
    public function getSlaRepDeadlineFromAttribute(){
        $id = $this->id;
        $dd = $this->getSlaDeadlineFrom($id);
        $sla_rep_deadline_from = $dd[0];
        return $sla_rep_deadline_from;
    }
    public function getSlaResDeadlineFromAttribute(){
        $id = $this->id;
        $dd = $this->getSlaDeadlineFrom($id);
        $sla_res_deadline_from = $dd[1];
        return $sla_res_deadline_from;
    }

    public function getIsOverdueAttribute(){

        $id = $this->id;
        $sla_plan = $this->sla_plan;
        // if($value->is_overdue == 0){
        // $dd = $this->getSlaDeadlineFrom($id);
        // $value->sla_rep_deadline_from = $dd[0];
        // $value->sla_res_deadline_from = $dd[1];

        $lcnt = false;
 $tm_name = timeZone();
        if($this->sla_plan['title'] != self::NOSLAPLAN) {
            if($this->reply_deadline != 'cleared') {

                $date = new Carbon( Carbon::now() , $tm_name);
                $nowDate = Carbon::parse($date->format('Y-m-d h:i A'));

                if(!empty($this->reply_deadline)) {
                    $timediff = $nowDate->diffInSeconds(Carbon::parse($this->reply_deadline), false);
                    if($timediff < 0) $lcnt = true;
                } else {

                    $rep = Carbon::parse($this->sla_rep_deadline_from);
                    $dt = explode('.', $this->sla_plan['reply_deadline']);
                    $rep->addHours($dt[0]);

                    if(strtotime($rep) < strtotime($nowDate)) {
                        $lcnt = true;
                    }


                }
            }

            if(!$lcnt) {
                if($this->resolution_deadline != 'cleared') {
                    $date = new Carbon( Carbon::now() , $tm_name);
                    $nowDate = Carbon::parse($date->format('Y-m-d h:i A'));

                    if(!empty($this->resolution_deadline)) {
                        $timediff = $nowDate->diffInSeconds(Carbon::parse($this->resolution_deadline), false);
                        if($timediff < 0) $lcnt = true;
                    } else {
                        $res = Carbon::parse($this->sla_res_deadline_from);
                        $dt = explode('.', $this->sla_plan['due_deadline']);
                        $res->addHours($dt[0]);
                        if(strtotime($res) < strtotime($nowDate)) {
                            $lcnt = true;
                        }
                    }
                }else{

                }
            }


            if($lcnt) {
                $this->is_overdue = 1;
                $tkt = Tickets::where('id',$this->id)->first();
                $tkt->is_overdue = 1;
                $tkt->save();
                return 1;
            }
            // $late_tickets_count = Tickets::where([ ['is_overdue',1], ['is_deleted', 0] , ['tickets.trashed', 0] , ['is_pending' ,0] , ['tickets.status', '!=', $closed_status_id] ])->count();
        }
        return 0;

    }

    public function getTicketSlaPlan($ticketID) {
        try {
            $sla_plan = array(
                "id" => "",
                "title" => self::NOSLAPLAN,
                "reply_deadline" => "",
                "due_deadline" => "",
                "bg_color" => "#fff"
            );

            $settings = $this->getTicketSettings(['default_reply_time_deadline', 'default_resolution_deadline', 'overdue_ticket_background_color']);

            $sla_plan['bg_color'] = $settings['overdue_ticket_background_color'];

            $sla_assoc = SlaPlanAssoc::where('ticket_id', $ticketID)->first();
            if(!empty($sla_assoc)) {
                $sla_plann = SlaPlan::where('id', $sla_assoc->sla_plan_id)->first();
                if(!empty($sla_plann)) {
                    $sla_plan['id'] = $sla_plann->id;
                    $sla_plan['title'] = $sla_plann->title;
                    $sla_plan['reply_deadline'] = $sla_plann->reply_deadline;
                    $sla_plan['due_deadline'] = $sla_plann->due_deadline;

                    // use default set deadlines in case of empty
                    if(empty($sla_plan['reply_deadline'])) $sla_plan['reply_deadline'] = $settings['default_reply_time_deadline'];

                    if(empty($sla_plan['due_deadline'])) $sla_plan['due_deadline'] = $settings['default_resolution_deadline'];
                }
            }

            return $sla_plan;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getTicketSettings($settings) {
        try{
            $list = TicketSettings::all();
            $ret = array();

            foreach ($list->toArray() as $value) {
                foreach ($settings as $set) {
                    if($value['tkt_key'] == $set) {
                        $ret[$set] = $value['tkt_value'];
                    }
                }
            }

            return $ret;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getSlaDeadlineFrom($ticketID) {
        try {
            // $ticket = Tickets::findOrFail($ticketID);
            $created_at = $this->created_at;
            $deadlines = [];

            $rep_logs = Activitylog::where('ref_id', $ticketID)->where([ ['module', 'Tickets'], ['table_ref', 'sla_rep_deadline_from'] ])->orderByDesc('id')->first();
            if(!empty($rep_logs)) {
                $deadlines[0] =  strtotime($rep_logs->created_at) < strtotime($created_at) ? $created_at : $rep_logs->created_at;
            }else{
                 $deadlines[0] = $created_at;
            }

            $res_logs = Activitylog::where('ref_id', $ticketID)->where([ ['module', 'Tickets'], ['table_ref', 'sla_res_deadline_from'] ])->orderByDesc('id')->first();
            if(!empty($res_logs)) {
                $deadlines[1] =  strtotime($res_logs->created_at) < strtotime($created_at) ? $created_at : $res_logs->created_at;
            }else{
                $deadlines[1] = $created_at;
            }

            return $deadlines;
        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
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
