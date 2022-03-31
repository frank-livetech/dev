<?php

namespace App\Http\Controllers\SystemManager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\HelpdeskController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ActivitylogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\Mail;
use App\Models\Customer;
use App\User;
use Session;
// tickets models
use App\Models\Tickets;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketReply;
use App\Models\TicketSettings;

use App\Models\Activitylog;
use App\Models\AssetForms;
use App\Models\SlaPlan;
use App\Models\SlaPlanAssoc;
use Throwable;
use Illuminate\Support\Facades\File;

use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Genert\BBCode\BBCode;
use PhpParser\Node\Stmt\Continue_;
use Illuminate\Support\Facades\URL;

// require 'vendor/autoload.php';
require '../vendor/autoload.php';

class MailController extends Controller
{
    // *************   PROPERTIES   ****************

    // public static $imap = null;
    public static $connection = '{mylive-tech.com:995/pop3/ssl}';
    public static $mailserver_hostname = 'mylive-tech.com';
    public static $mailserver_username = 'dev_testing@mylive-tech.com';
    public static $mailserver_password = '0C,AQxp,x%%X';
    public $cc_string = '';
    const DEFAULTSLA_TITLE = 'Default SLA';

    const IMAGE_EXTENSIONS = ['png', 'jpg', 'jpeg', 'svg', 'gif', 'webp'];


    // ***************   METHODS   *****************

    
    public function __construct() {
        $this->middleware('auth');
    }

    public function get_mails(Request $request){
        $mails = Mail::orderBy('id','desc')->where('is_deleted', 0)->get();

        foreach($mails as $mail) {
            $mail->mail_type = DB::Table("ticket_types")->where("id","=",$mail->mail_type_id)->first();
            $mail->department = DB::Table("departments")->where("id","=",$mail->mail_dept_id)->first();
        }

        return response()->json([
            "status_code" => 200 ,
            "success" => true, 
            "mails" => $mails,
        ]);
    }

    public function get_email_by_id(Request $request) {

        return Mail::where('id',$request->id)->first();

    }

    public function save_mail(Request $request){
        $data = $request->all();
        // return $data['from_email'];
        $response = array();
    
        try{
            if( array_key_exists('verify', $data) ) {
                $ver = $this->verify_connection($request);
                if(empty($ver) || !$ver['success']){
                    $response['message'] = 'Failed to verify connection!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
            }

            if($data['is_default'] == 'yes') {
                $mails = Mail::where('mail_dept_id', $data['mail_dept_id'])->where('is_default', 'yes')->get();
                foreach ($mails as $key => $value) {
                    $value->is_default = 'no';
                    $value->save();
                }
            }

            if( !empty($data['id']) ){
                $mail = Mail::findOrFail($data['id']);

                $mail->mail_queue_address = $data['mail_queue_address'];
                $mail->queue_type = $data['queue_type'];
                $mail->protocol = $data['protocol'];
                $mail->queue_template = $data['queue_template'];
                $mail->is_enabled = $data['is_enabled'];
                $mail->registration_required = $data['registration_required'];
                $mail->autosend = $data['autosend'];
                $mail->mailserver_hostname = $data['mailserver_hostname'];
                $mail->mailserver_port = $data['mailserver_port'];
                $mail->mailserver_username = $data['mailserver_username'];
                $mail->mailserver_password = $data['mailserver_password'];
                $mail->from_name = $data['from_name'];
                $mail->from_mail = $data['from_mail'];
                $mail->mail_dept_id = $data['mail_dept_id'];
                $mail->mail_type_id = $data['mail_type_id'];
                $mail->mail_status_id = $data['mail_status_id'];
                $mail->mail_priority_id = $data['mail_priority_id'];
                $mail->php_mailer = $data['php_mailer'];
                $mail->outbound = $data['outbound'];
                $mail->is_default = $data['is_default'];
                $mail->created_by = \Auth::user()->id;

                $mail->save();
            }else{
                $mail_host = Mail::where('mailserver_username', $data['mailserver_username'])->first();
                if(!empty($mail_host)) {
                    $response['message'] = 'Mail already exist!';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
                $data['user_id'] = \Auth::user()->id;
                $data['created_by'] = \Auth::user()->id;

                Mail::create($data);
            }
            $response['message'] = 'Mail Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
    
        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function updateEmail(Request $request) {
        $data = $request->all();
        if($request->php_mailer == 'yes') {
            $mail = Mail::find($request->id);
            $mail->mailserver_hostname = $data['mailserver_hostname'];
            $mail->mailserver_port = $data['mailserver_port'];
            $mail->mailserver_username = $data['mailserver_username'];
            $mail->mailserver_password = $data['mailserver_password'];
            $mail->from_name = $data['from_name'];
            $mail->from_mail = $data['from_mail'];
            $mail->updated_by = \Auth::user()->id;
            $mail->is_default = $data['is_default'];
            $mail->php_mailer = $data['php_mailer'];
            $mail->save();
        }else{
            $mail = Mail::find($request->id);
            
            if($data['is_default'] == 'yes') {
                $mails = Mail::where('mail_dept_id', $data['mail_dept_id'])->where('is_default', 'yes')->get();
                foreach ($mails as $key => $value) {
                    $value->is_default = 'no';
                    $value->save();
                }
            }

            $mail->mail_queue_address = $data['mail_queue_address'];
            $mail->queue_type = $data['queue_type'];
            $mail->protocol = $data['protocol'];
            $mail->queue_template = $data['queue_template'];
            $mail->is_enabled = $data['is_enabled'];
            $mail->registration_required = $data['registration_required'];
            $mail->autosend = $data['autosend'];
            $mail->mailserver_hostname = $data['mailserver_hostname'];
            $mail->mailserver_port = $data['mailserver_port'];
            $mail->mailserver_username = $data['mailserver_username'];
            $mail->mailserver_password = $data['mailserver_password'];
            $mail->from_name = $data['from_name'];
            $mail->from_mail = $data['from_mail'];
            $mail->mail_dept_id = $data['mail_dept_id'];
            $mail->mail_type_id = $data['mail_type_id'];
            $mail->mail_status_id = $data['mail_status_id'];
            $mail->mail_priority_id = $data['mail_priority_id'];
            $mail->php_mailer = $data['php_mailer'];
            $mail->outbound = $data['outbound'];
            $mail->is_default = $data['is_default'];
            $mail->updated_by = \Auth::user()->id;
            $mail->save();
        }

        $response['message'] = 'Mail Updated Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function delete_mail(Request $request){
        $data = $request->all();
            
        $response = array();
    
        try{
            $mail = Mail::findOrFail($data['id']);

            $mail->deleted_at = Carbon::now();
            $mail->updated_by = \Auth::user()->id;
            $mail->deleted_by = \Auth::user()->id;
            $mail->is_deleted = 1;

            $mail->save();
            
            $response['message'] = 'Mail Deleted Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
    
        }catch(Exception $e){
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function verify_connection(Request $request){
        $data = $request->all();

        $connection = '{'.$data['mailserver_hostname'].':'.$data['mailserver_port'].'/'.$data['queue_type'].'/'.$data['protocol'].'}';

        try {
            $imap = imap_open($connection, $data['mailserver_username'], $data['mailserver_password']);
            
            if(!empty($imap)) {
                imap_close($imap);
                $response['success'] = true;
            } else {
                // $response['error'] = imap_last_error();
                $response['error'] = imap_errors();
                $response['alerts'] = imap_alerts();
                $response['success'] = false;
            }
            $response['success'] = true;
            return $response;
            
        }catch(Throwable $e) {
            $response['error'] = imap_errors();
            $response['alerts'] = imap_alerts();
            $response['success'] = false;
            return $response;
        }
    }

    public function save_inbox_replies() {
        try {
            $repliesSaved = false;

            $emailQueue = DB::table('email_queues')->where('is_deleted', 0)->get()->toArray();
            
            foreach ($emailQueue as $eq_value) {
                if($eq_value->is_enabled == 'no') continue;

                $conn = sprintf('{%s:%s/%s/%s}', $eq_value->mailserver_hostname, $eq_value->mailserver_port, $eq_value->queue_type, $eq_value->protocol);

                $imap = imap_open($conn, $eq_value->mailserver_username, $eq_value->mailserver_password) or die('Cannot connect to email: ' . imap_last_error());

                $mails = imap_search($imap, 'ALL', SE_UID);

                if(empty($mails)) {
                    imap_errors();
                    imap_alerts();
                    continue;
                }

                $helpDesk = new HelpdeskController();

                foreach ($mails as $key => $message) {
                    
                    $mail = imap_fetchstructure($imap, $message);
                    $type = $mail->subtype;
                    // dd($mail->subtype);exit;
                    
                    // For getting basic info , subject , mail from , mail to etc.
                    $header=imap_fetch_overview($imap, $message);
                    $strAddress_Sender=$header[0]->from;
                    $date = $header[0]->date;
                    $strFromName = explode(' ', $strAddress_Sender);
                    $strAddress_Sender = explode('<',$strAddress_Sender);
                    $strAddress_Sender = trim($strAddress_Sender[1],'>');
                    $email_subject =$header[0]->subject;
                    /////////////////////////////////////////////////////////////
                    
                    $mail = $this->mail_get_parts($imap, $message, $mail, 0);
                    $mail[0]["parsed"] = $this->mail_parse_headers($mail[0]["data"]);
                  
                    // dd($date);exit;
                    if(!empty($email_subject)) {
                    // if(array_key_exists('Authentication-Results', $mail[0]["parsed"]) && !empty($mail[0]["parsed"]['Subject'])) {
                        // get the sender email from headers
                        // dd($email_subject);exit;
                        // $emailFrom =  $this->getCustomerEmailParser($mail[0]["parsed"]['Authentication-Results']);
                        $emailFrom =  $strAddress_Sender;
                        
                        if(!empty($emailFrom)) {
                            if($eq_value->registration_required == 'yes') {
                                // email is from our customer
                                $customer = Customer::where('email', trim($emailFrom))->first();
                            } else {
                                $customer = Customer::where('email', trim($emailFrom))->first();
                                
                                if(empty($customer)) {
                                    $name = $strFromName;
                                    $customer = Customer::create([
                                        'username' => trim($emailFrom),
                                        'first_name' => array_key_exists(0, $name) ? $name[0] : '',
                                        'last_name' => array_key_exists(1, $name) ? $name[1] : '',
                                        'email' => trim($emailFrom)
                                    ]);
                                }
                            }  
                            
                            if(strpos($email_subject, '[') !== false && strpos($email_subject, ']:') !== false && strpos($email_subject, '!') !== false){
                                $id = '';
                                if(strpos($email_subject, $eq_value->mail_queue_address) !== false){
                                    
                                    $pos = strpos($email_subject, '!');
                                    $sub = substr($email_subject,$pos+1);
                                    $pos1 = strpos($sub,']:');
                                    $id = substr($sub,0,$pos1);
                                    
                                    $pattern = '/[A-Z]{3}-[0-9]{3}-[0-9]{4}/';
                                    if(preg_match($pattern, $id, $array)) {
                                        $id = $array[0];
                                    }
                                    
                                }else{
                                    $pos = strpos($email_subject, '!');
                                    $sub = substr($email_subject,$pos+1);
                                    $pos1 = strpos($sub,']:');
                                    $id = substr($sub,0,$pos1);
                                    
                                    $pattern = '/[A-Z]{3}-[0-9]{3}-[0-9]{4}/';
                                    if(preg_match($pattern, $id, $array)) {
                                        $id = $array[0];
                                    }
                                }
                                if($id != ''){
                                    // get ticket custom id from mail body
                                    $ticketID = $id;
                                    // echo $ticketID;exit;
                                    if(empty($ticketID)) {
                                        echo 'Ticket with subject "'.$email_subject. '" not found!<br>';
                                        continue;
                                    }
                                    // save ticket reply
                                    // $sbj = str_replace('Re: ', '', $email_subject);
                                    $sbj = str_replace('Re: ', '', $email_subject);
                                    $cid = (!empty($customer)) ? $customer->id : '';
                                    $sid = '';
                                    
                                    // echo $ticketID;exit;
                                    if(empty($customer)) {
                                        $staff = User::where('email', trim($emailFrom))->first();
                                        if(empty($staff)) {
                                            // reply is not from our system user
                                            
                                            continue;
                                        }
                                        $sid = $staff->id;
                                    }
                                    
    
                                    // $ticket = Tickets::where(DB::raw('concat(coustom_id, " ", subject)'), trim($sbj))->first();
                                    $ticket = Tickets::where('coustom_id', $ticketID)->first();
    
                                    // check for the ticket without coustom id appended in subject
                                    // if(empty($ticket)) {
                                    //     $ticket = Tickets::where('subject', trim($sbj))->where(function($query) use($cid, $sid) {
                                    //         $query->where('customer_id', $cid)->orWhere('assigned_to', $sid);
                                    //     })->first();
                                    // }
                                    $bbcode = new BBCode();
                                    if(!empty($ticket)) {
                                        // $all_parsed = $this->mail_parse_attachments($mail, $ticket->id);
                                        $all_parsed = $mail;
                                        $attaches = $this->mail_parse_attachments($mail, $ticket->id);
                                        $reply = $this->email_body_parser($all_parsed,'reply',$eq_value->mailserver_username);
                                        $html_reply = $bbcode->convertFromHtml($reply);


                                        
                                        //converting html to secure bbcode
                                        
                                        // dd($reply);exit;
                                        
                                        $gmail_str = $eq_value->mailserver_username;
                                        $tkt_str = '['.$eq_value->mail_queue_address.' !'.$ticket->coustom_id.']:';
                                        if($type == 'PLAIN'){
                                            $html_reply = nl2br($html_reply);
                                        }else{
                                         
                                            // // echo $gmail_str;exit;
                                            if (str_contains($html_reply, '<div id="appendonsend"></div>')){
                                                // echo "yes";
                                                $content =  explode('<div id="appendonsend"></div>',$html_reply);
                                                $html_reply = $content[0].'</div></body></html>';
                                                // dd($html_reply);exit;
                                                
                                            }else if(str_contains($html_reply, '<div id="divRplyFwdMsg"></div>')){
                                                echo "yes";
                                                $content =  explode('<div class="divRplyFwdMsg">',$html_reply);
                                                $html_reply = $content[0].'</div></body></html>';
                                                
                                            }else if(str_contains($html_reply, '<div class="gmail_quote">')){
                                                
                                                // echo "yes";exit;
                                                $content =  explode('<div class="gmail_quote">',$html_reply);
                                                $html_reply = $content[0];
                                                // dd($html_reply);exit;
                                                
                                            }else if(str_contains($html_reply,$tkt_str)){
                                                
                                                if(str_contains($html_reply,'From:')){
                                                    if(str_contains($html_reply,'<div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0in 0in 0in">')){
                                                        
                                                        $content =  explode('<div style="border:none;border-top:solid #E1E1E1 1.0pt;padding:3.0pt 0in 0in 0in">',$html_reply);
                                                        $html_reply = $content[0].'</div></body></html>';
                                                    }else{
                                                        // dd($reply);exit;
                                                        if(str_contains($html_reply,'From: '.$eq_value->mailserver_username)){
                                                            // echo "sdfsdf";
                                                        }else{
                                                            $content =  explode('From:',$html_reply);
                                                            $html_reply = $content[0].'</b></p></div></body></html>';
                                                        }
                                                        
                                                    }
                                                    
                                                }
                                                
                                            }else if(str_contains($html_reply,'On') && str_contains($html_reply,'wrote') && str_contains($html_reply,$eq_value->mail_queue_address) && str_contains($html_reply,'<blockquote type="cite">On')){
                                                $content =  explode('<blockquote type="cite">On',$html_reply);
                                                $html_reply = $content[0];
                                            }   
                                            
                                        }
                                        
                                        // dd($html_reply);exit;
                                        // $content = explode($reply,'<div class="gmail_quote">');
                                        // echo nl2br($html_reply);exit;
                                        // dd(nl2br($html_reply));exit;
                                        
                                        
                                        $data = array(
                                            "ticket_id" => $ticket->id,
                                            "type" => 'cron',
                                            "msgno" => $message,
                                            "reply" => nl2br($html_reply),
                                            "date" => new Carbon($date),
                                            "attachments" => $attaches
                                        );
    
                                        $fullname = '';
                                        $user = null;
                                        if(!empty($sid)) {
                                            $data["user_id"] = $sid;
                                        }
                                        
                                        if(!empty($cid)) {
                                            $data["customer_id"] = $cid;

                                            $open_status = TicketStatus::where('name','Open')->first();
                                            $ticket->status = $open_status->id;

                                        }
                                      
                                        $rep = TicketReply::create($data);
    
                                        $sett = TicketSettings::where('tkt_key', 'reply_due_deadline')->first();
                                        if(isset($sett->tkt_value)) {
                                            if($sett->tkt_value === 1) {
                                                $ticket->reply_deadline = null;
                                                $ticket->save();
                                            }
                                        }


                                        $ticket->updated_at = Carbon::now();
                                        $ticket->save();
                                        $ticket = Tickets::where('coustom_id', $ticketID)->first();

                                        if(!empty($sid)) {
                                          
                                            $fullname = $staff->name;
                                            $user = $staff;
                                            $ticket->assigned_to = $sid;
                                            $ticket->save();
                                            try {

                                                $helpDesk->sendNotificationMail($ticket->toArray(), 'ticket_reply', $reply, '', 'cron', $attaches, $staff->email);

                                            } catch(Throwable $e) {
                                                echo 'Reply Notification! '. $e->getMessage();
                                            }
                                        }
                                        
                                        if(!empty($cid)) {
                                            
                                            $fullname = $customer->first_name.' '.$customer->last_name;
                                            $user = $customer;
                                            try {
                                                $helpDesk->sendNotificationMail($ticket->toArray(), 'ticket_reply', $reply, '', 'cust_cron', $attaches, $customer->email);
                                            } catch(Throwable $e) {
                                                echo 'Reply Notification! '. $e->getMessage();
                                            }
                                        }
    
                                        $repliesSaved = true;
                                        // echo 'Saved reply FROM "'.$fullname.' ('.$user->email.')" with SUBJECT "Re: '.$ticket->subject.'" MESSAGE NO# '.$message.'<br>';
                                         echo 'Saved reply FROM "'.$fullname.' ('.$user->email.')" with SUBJECT " '.$ticket->subject.'" MESSAGE NO# '.$message.'<br>';
        

                                        $action_perform = "Saved reply FROM '.$fullname.' with SUBJECT '.$ticket->subject.'";
                                        $log = new ActivitylogController();
                                        // $log->saveActivityLogs('Tickets' , 'ticket_replies' , $rep->id , auth()->id() , $action_perform);
                                        $log->saveActivityLogs('Tickets' , 'sla_rep_deadline_from' , $rep->id , 0 , $action_perform);
                                    }
                                    
                                }
                               
                               
                            }else{
                                
                                    $customer_id = '';
                                    $is_staff_tkt = 0;
                                    $name = '';
                                    $email = '';
                                    if(empty($customer)) {
                                        
                                        $staff = User::where('email', trim($emailFrom))->first();
                                        if(empty($staff)) {
                                            // reply is not from our system user
                                            imap_delete($imap, $message);
                                            continue;
                                        }
                                        $customer_id = $staff->id;
                                        $name = $staff->name;
                                        $email = $staff->email;
                                        $is_staff_tkt = 1;
                                    }else{
                                        $name = $customer->first_name.' '.$customer->last_name;
                                        $email = $customer->email;
                                        $customer_id = $customer->id;
                                    }
                                //  if(!empty($customer)) {
                                    // $ticket = Tickets::where('customer_id', $customer->id)->where('subject', trim($email_subject))->first();
                                    $ticket = Tickets::where('customer_id', $customer_id)->where('coustom_id', $email_subject)->first();
                                    
                                    if(empty($ticket)) {
                                        $ticket_settings = TicketSettings::where('tkt_key','ticket_format')->first();
                                        
                                        // create new ticket
                                        $ticket = Tickets::create([
                                            'dept_id' => $eq_value->mail_dept_id,
                                            'priority' => $eq_value->mail_priority_id,
                                            'subject' => trim($email_subject),
                                            'customer_id' => $customer_id,
                                            'status' => $eq_value->mail_status_id,
                                            'type' => $eq_value->mail_type_id,
                                            'is_staff_tkt' => $is_staff_tkt,
                                            'tkt_crt_type' => 'cron'
                                        ]);
                                        
                                        // $all_parsed = $this->mail_parse_ticket_attachments($mail, $ticket->id);
                                        $all_parsed = $mail;
                                        $attaches = $this->mail_parse_ticket_attachments($mail, $ticket->id);
                                        $body = $this->email_body_parser($all_parsed, 'ticket');
                                        
                                        $tickets_count = Tickets::all()->count();
                                        
                                        $lt = Tickets::orderBy('created_at', 'desc')->first();

                                        $ticket->ticket_detail = $body;
                                        $ticket->attachments = $attaches;
                                        
                                        $newG = new GeneralController();
                                        $ticket->coustom_id = $newG->randomStringFormat($helpDesk::CUSTOMID_FORMAT);
                                        if(!empty($lt)) {
                                            $ticket->seq_custom_id = 'T-'.strval($lt->id + 1);
                                        }else{
                                            $ticket->seq_custom_id = 'T-'.strval($tickets_count+1);
                                        }
                                        $ticket->save();
                                        
                                        // ticket assoc with sla plan
                                        $settings = $helpDesk->getTicketSettings(['default_reply_and_resolution_deadline']);
                                        if(isset($settings['default_reply_and_resolution_deadline'])) {
                                            if($settings['default_reply_and_resolution_deadline'] == 1) {
                                                $sla_plan = SlaPlan::where('title', self::DEFAULTSLA_TITLE)->first();
                                                if(empty($sla_plan)) {
                                                    $sla_plan = SlaPlan::create([
                                                        'title' => self::DEFAULTSLA_TITLE,
                                                        'sla_status' => 1
                                                    ]);
                                                }
                                                SlaPlanAssoc::create([
                                                    'sla_plan_id' => $sla_plan->id,
                                                    'ticket_id' => $ticket->id
                                                ]);
                                            }
                                        }
                                        
                                        $repliesSaved = true;
                                        
                                        echo 'Created Ticket By "'.$name.' ('.$email.')" with SUBJECT "'.$ticket->subject.'" MESSAGE NO# '.$message.'<br>';

                                        self::$mailserver_hostname = $eq_value->mailserver_hostname;
                                        self::$mailserver_username = $eq_value->mailserver_username;
                                        self::$mailserver_password = $eq_value->mailserver_password;
            

                                        $action_perform = 'Ticket (ID <a href="'.url('ticket-details').'/' .$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) Created By CRON';
                                        $log = new ActivitylogController();
                                        $log->saveActivityLogs('Tickets' , 'tickets' , $ticket->id , 0 , $action_perform);
                                        
                                        try {
                                            $ticket = Tickets::where('id',$ticket->id)->first();
                                            $helpDesk->sendNotificationMail($ticket->toArray(), 'ticket_create', '', '', 'cron');
                                        } catch(Throwable $e) {
                                            echo $e->getMessage();
                                        }
                                        // dd('sent');exit;
                                    }
                                // }
                            }
                             
                        }
                    }
                    //  dd('before delete');exit;
                    imap_delete($imap, $message);
                }

                imap_close($imap, CL_EXPUNGE);
                // imap_close($imap);
            }
            
            if(empty($repliesSaved)) {
                // echo "\nNo new mails found.";

                $response['message'] = "No new mails found.";
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);

            }else{
                $response['message'] = "Parser run successfully!";
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
            }
        } catch(Throwable $e) {
            $response['message'] = $e->getMessage();
                $response['status_code'] = 500;
                $response['success'] = true;
                return response()->json($response);
            echo $e->getMessage();
        }
    }

    public function getTicketCustomID($body) {
        $data = '';
        $id = '';

        if(!empty($body)) {
            foreach ($body as $value) {
                if(array_key_exists('charset', $value)) {
                    $data = $value['data'];
                    if(strtolower($value['charset']) == 'iso-8859-1') $data = utf8_encode($value['data']);
                }
            }

            if(!empty($data)) {
                $t_pos = strpos(html_entity_decode($data), 'Ticket ID:');
                if($t_pos === false) $t_pos = strpos(html_entity_decode($data), 'Ticket ID :');

                if($t_pos !== false) {
                    $ticketid = substr(html_entity_decode($data), $t_pos, 50);
                    if($ticketid === false) echo "False";
                    else {
                        // ticket id pattern for regex form
                        $pattern = '/[A-Z]{3}-[0-9]{3}-[0-9]{4}/';
                        if(preg_match($pattern, $ticketid, $array)) {
                            $id = $array[0];
                        }
                    }
                }
            }
        }

        return $id;
    }

    private function getCustomerEmailParser($data) {
        if(empty($data)) {
            return false;
        }

        $data = explode(';', $data);

        if(empty($data)) {
            return false;
        }

        foreach ($data as $value) {
            $value = trim($value);
            if(strpos($value, " ")) {
                $value = explode(" ", $value);

                foreach ($value as $val) {
                    if(strpos($val, "=")  !== false) {
                        $s = explode('=', $val);
        
                        if($s[0] == 'smtp.mailfrom') {
                            return $s[1];
                        }
                    }
                }
            } else {
                if(strpos($value, "=")  !== false) {
                    $s = explode('=', $value);
    
                    if($s[0] == 'smtp.mailfrom') {
                        return $s[1];
                    }
                }
            }
        }

        return false;
    }

    private function email_body_parser($all_parsed, $type="reply",$from = '') {
        $data = '';
        // $attachments = '<br><br>';
        foreach ($all_parsed as $key => $value) {
            if(array_key_exists('charset', $value)) {
                $data = $value;
            }
            // if(array_key_exists('is_attachment', $value)) {
            //     $attachments .= $value['data'];
            // }
        }
        
        if(array_key_exists(2, $all_parsed) && array_key_exists('charset', $all_parsed[2])) {
            $data = $all_parsed[2];
        } else if(array_key_exists('1.2', $all_parsed) && array_key_exists('charset', $all_parsed['1.2'])) {
            $data = $all_parsed['1.2'];
        } else if(array_key_exists('1.1', $all_parsed) && array_key_exists('charset', $all_parsed['1.1'])) {
            $data = $all_parsed['1.1'];
        } else if(array_key_exists(1, $all_parsed) && array_key_exists('charset', $all_parsed[1])) {
            $data = $all_parsed[1];
        }

        if($data['charset'] == 'ISO-8859-1' || $data['charset'] == 'iso-8859-1') $data = utf8_encode($data['data']);
        else $data = $data['data'];
        
        // if($type == 'reply'){
        //     $str = 'From: '.$from.' <'.$from.'>';
        //     $gmail_str = $from.' wrote:';
        //     // echo $gmail_str;exit;
        //     if (str_contains($data, $str)){
        //         echo "yes";exit;
        //     }else if(str_contains($data, $gmail_str)){
        //         echo "yes";exit;
        //     }

        // }
        // return $data.$attachments;
        return $data;
        // if($type == 'ticket') {
        //     return $data.$attachments;
        // }

        // $i = 0;
        // $finish = false;
        // $stack = array();
        // while($i < strlen($data) && !$finish) {
        //     $r = substr($data, $i, 4);
        //     if($r == '<div'){
        //         array_push($stack, 'div');
        //         $i = $i + 3;
        //     }else if($r == '</di') {
        //         array_pop($stack);
        //         $i = $i + 5;
        //         if(empty($stack)) {
        //             $finish = true;
        //         }
        //     }
        //     $i++;
        // }

        // return substr($data, 0, $i).$attachments;
    }

    public function mail_parse_attachments($data, $tid) {
        $attachments = '';
        foreach ($data as $key =>$value) {
            if(array_key_exists('is_attachment', $value) && $value['is_attachment'] == '1'){
                $ext = pathinfo($value['filename'], PATHINFO_EXTENSION);

                if(empty($ext)) $ext = 'svg';

                $filename = 'Live-tech_'.date_format(Carbon::now(), 'Y-m-d_h-i-s').'_'.$key.'.'.$ext;
                
                // $target_src = 'public/files/replies/'.$tid.'/'.$filename;
                // $target_dir = public_path('files/replies/'.$tid);
                // $filepath = 'http://localhost/framework/public/files/replies/'.$tid.'/'.$filename;
                // $filepath = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/').'/public/files/replies/'.$tid.'/'.$filename;
                
                // if (!File::isDirectory($target_dir)) {
                //     mkdir($target_dir, 0777, true);
                // }

                $target_dir = 'storage/tickets-replies/'.$tid;
                $target_src = $target_dir.'/'.$filename;
                    
                if (!File::isDirectory($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                file_put_contents($target_src, $value['data']);

                if(!empty($attachments)) $attachments .= ','.$filename;
                else $attachments = $filename;
                
                // if( in_array($ext, self::IMAGE_EXTENSIONS) ) {
                    
                // }
                // if(strtolower($ext) == 'svg') {
                //     $data[$key]['data'] = '<div class="reply-attachs-container">
                //         <div class="reply-image"><svg height="120" width="120" class="reply-image">
                //             <text x="0" y="15" fill="grey" transform="translate(35 50)" font-size="20">.'.strtoupper($ext).'</text>
                //             Sorry, your browser does not support inline SVG.
                //         </svg></div>
                //         <div class="reply-bottom">
                //             <div class="reply-filename">'.$value['filename'].'</div>
                //             <a href="'.$filepath.'" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                //         </div>
                //     </div>';
                // } else {
                //     $data[$key]['data'] = '<div class="reply-attachs-container">
                //     <div class="reply-image"><img src="'.$filepath.'" alt="'.$value['filename'].'" class="reply-image"></div>
                //         <div class="reply-bottom">
                //             <div class="reply-filename">'.$value['filename'].'</div>
                //             <a href="'.$filepath.'" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                //         </div>
                //     </div>';
                // }
            }
        }
        // return $data;
        return $attachments;
    }

    public function mail_parse_ticket_attachments($data, $tid) {
        $filesNames = '';
        foreach ($data as $key =>$value) {
            if(array_key_exists('is_attachment', $value) && $value['is_attachment'] == '1') {
                $filename = preg_replace('/[^a-zA-Z0-9_.]/', '_', $value['filename']);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);

                // $ext = pathinfo($value['filename'], PATHINFO_EXTENSION);
                // if(empty($ext)) $ext = 'svg';
                // $filename = 'Live-tech_'.date_format(Carbon::now(), 'Y-m-d_h-i-s').'_'.$key.'.'.$ext;
                
                // $target_src = 'public/files/tickets/'.$tid.'/'.$filename;
                // $target_dir = public_path('files/tickets/'.$tid);
                // $filepath = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/').'/public/files/tickets/'.$tid.'/'.$filename;
                
                // if (!File::isDirectory($target_dir)) {
                //     mkdir($target_dir, 0777, true);
                // }

                $target_dir = 'storage/tickets/'.$tid.'/';
                $target_src = $target_dir.$filename;
                    
                if (!File::isDirectory($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                file_put_contents($target_src, $value['data']);

                if(!empty($filesNames)) $filesNames .= ','.$filename;
                else $filesNames = $filename;

                // if(empty($filesNames)) $filesNames = $filename;
                // else $filesNames .= '|'.$filename;
                
                // if( in_array($ext, self::IMAGE_EXTENSIONS) ) {
                //     $data[$key]['data'] = '<div class="reply-attachs-container">
                //     <div class="reply-image"><img src="'.$filepath.'" alt="'.$filename.'" class="reply-image"></div>
                //         <div class="reply-bottom">
                //             <div class="reply-filename">'.$filename.'</div>
                //             <a href="'.$filepath.'" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                //         </div>
                //     </div>';
                // } else {
                //     $data[$key]['data'] = '<div class="reply-attachs-container">
                //         <div class="reply-image"><svg height="120" width="120" class="reply-image">
                //             <text x="0" y="15" fill="grey" transform="translate(35 50)" font-size="20">.'.strtoupper($ext).'</text>
                //             Sorry, your browser does not support inline SVG.
                //         </svg></div>
                //         <div class="reply-bottom">
                //             <div class="reply-filename">'.$filename.'</div>
                //             <a href="'.$filepath.'" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                //         </div>
                //     </div>';
                // }
            }
        }
        // return $data;
        return $filesNames;
    }

    public function mail_parse_headers($headers){
        $headers=preg_replace('/\r\n\s+/m', '',$headers);
        preg_match_all('/([^: ]+): (.+?(?:\r\n\s(?:.+?))*)?\r\n/m', $headers, $matches);
        $result = [];
        foreach ($matches[1] as $key =>$value) $result[$value]=$matches[2][$key];
        return($result);
    }

    public function mail_get_parts($imap,$mid,$part,$prefix){   
        $attachments=array();
        $attachments[$prefix] = $this->mail_decode_part($imap,$mid,$part,$prefix);
        if (isset($part->parts)) // multipart
        {
            $prefix = ($prefix == "0")?"":"$prefix.";
            foreach ($part->parts as $number=>$subpart)
                $attachments=array_merge($attachments, $this->mail_get_parts($imap,$mid,$subpart,$prefix.($number+1)));
        }
        return $attachments;
    }

    public function mail_decode_part($connection,$message_number,$part,$prefix){
        $attachment = array();
        if($part->ifdparameters) {
            foreach($part->dparameters as $object) {
                $attachment[strtolower($object->attribute)]=$object->value;
                if(strtolower($object->attribute) == 'filename') {
                    $attachment['is_attachment'] = true;
                    $attachment['filename'] = $object->value;
                }
            }
        }
    
        if($part->ifparameters) {
            foreach($part->parameters as $object) {
                $attachment[strtolower($object->attribute)]=$object->value;
                if(strtolower($object->attribute) == 'name') {
                    $attachment['is_attachment'] = true;
                    $attachment['name'] = $object->value;
                }
            }
        }
    
        // $attachment['data'] = imap_fetchbody($connection, $message_number, $prefix);
        // dd($attachment['data']);exit;
        $attachment['data'] = ($prefix)?
        imap_fetchbody($connection,$message_number,$prefix):  // multipart
        imap_body($connection,$message_number);
        if($part->encoding == 3) { // 3 = BASE64
            $attachment['data'] = base64_decode($attachment['data']);
        }
        elseif($part->encoding == 4) { // 4 = QUOTED-PRINTABLE
            $attachment['data'] = quoted_printable_decode($attachment['data']);
            // echo $attachment['data'];
            // dd($attachment['data']);exit;
        }
            
        //     if ($part->type==0 && $attachment['data']) {
        //     // Messages may be split in different parts because of inline attachments,
        //     // so append parts together with blank row.
        //     if (strtolower($part->subtype)=='plain'){
        //         $plainmsg .= trim($attachment['data']) ."\n\n";
        //         return $plainmsg;
        //     }
        //     else{
        //         $htmlmsg .= $attachment['data'] ."<br><br>";
        //     $charset = $params['charset'];  // assume all parts are same charset
        //     }
        // }
        
        return($attachment);
    }

    public function sendMail($subject, $body, $from, $recipient, $recipient_name, $reply='', $attachments='', $path='' , $from_email = '') {
        try {
            // $mail = new PHPMailer(true);
            $mail = new PHPMailer();
            $mail->CharSet = "UTF-8";
            //Server settings
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            // $mail->SMTPDebug = 3;
            // $mail->isSMTP();
            // $mail->SMTPAuth  =  true;
            // $mail->Host      =  self::$mailserver_hostname;
            // $mail->Port      =  25;
            // $mail->Username  =  self::$mailserver_username;
            // $mail->Password  =  self::$mailserver_password;
            // $mail->SMTPOptions = [
            //     'ssl' => [
            //         'verify_peer' => false,
            //         'verify_peer_name' => false,
            //         'allow_self_signed' => true,
            //     ]
            // ];
            // $mail->setFrom(self::$mailserver_username);

            $from_name = '';
            if (\Auth::user())  {
                $from_name = auth()->user()->name;
            }else{
                if($from_email != null && $from_email != '') {
                    $user = User::where('email', $from_email)->first();
                    $from_name = $user->name;
                }
                
            }

            $mail->setFrom($from , $from_name);
            
            //Recipients
            if(!empty($this->cc_string)) {
                $ccs = explode(',', $this->cc_string);
                foreach ($ccs as $key => $c) {
                    $mail->addCC($c);
                }
            }
            // if($reply == 'ticket_reply') {
            //     $mail->addReplyTo($recipient, $subject);
            // }

            //Attachments
            if(!empty($attachments) && !empty($path)) {
                $attachments = explode(',', $attachments);
              
                foreach ($attachments as $key => $value) {
                    $path_tmp =  __DIR__."/../../../../$path/$value";
                    
                    if(is_readable($path_tmp)) {
                        // echo $path_tmp;
                        if(!$mail->AddAttachment($path_tmp)) throw new Exception('Add attachment failed '.$mail->ErrorInfo);
                    }
                }
            }
            // dd($mail);
            // exit;
            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = '';

            if(is_array($recipient)) {
                foreach ($recipient as $key => $value) {
                    $mail->clearAllRecipients();
                    $mail->addAddress($value['email'], $value['name']);
                    if(!$mail->send()) throw new Exception('Failed to send mail');
                }
            } else {
                $mail->addAddress($recipient, $recipient_name);
                if(!$mail->send()) throw new Exception('Failed to send mail');
            }


        } catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function UserRegisteration($email, $new=true) {
        $user = User::where("email", $email)->first();
        if(empty($user)) throw new Exception('User not found');

        $mail_template = '';
        $subject = "New Registration";
        if($new == true) {
            if($user->user_type == 5){
                $mail_template = DB::table('templates')->where('code','new_customer_signup')->first();
            }else{
                $mail_template = DB::table('templates')->where('code','new_user_signup')->first();
            }
        } else {
            $subject = "User Info Updated";
            $mail_template = DB::table('templates')->where('code','user_info_update')->first();
        }

        if(empty($mail_template)) {
            throw new Exception('Template not found');
        }

        if(!empty($mail_template->subject)) {
            $subject = $mail_template->subject;
            if(str_contains($subject, '{Alert-Prefix}')) {
                $subject = str_replace('{Alert-Prefix}', $mail_template->alert_prefix, $subject);
            }
        }

        $order_input = array(
            array('module' => 'User', 'values' => $user->toArray()),
        );

        $template = $this->template_parser($order_input, $mail_template->template_html);

        $this->sendMail($subject, $template, 'accounts@mylive-tech.com', $user->email, $user->name);
    }

    public function parseSubject($subject, $ticket, $template, $mail_queue_address) {
        if(!empty($template->subject)) {
            $subject = $template->subject;
            if(str_contains($subject, '{Department-Prefix}')) {
                $subject = str_replace('{Department-Prefix}', $mail_queue_address, $subject);
            }
            if(str_contains($subject, '{Ticket-ID}')) {
                $subject = str_replace('{Ticket-ID}', $ticket['coustom_id'], $subject);
            }
            if(str_contains($subject, '{Alert-Prefix}')) {
                $subject = str_replace('{Alert-Prefix}', $template->alert_prefix, $subject);
            }
            if(str_contains($subject, '{Ticket-Subject}')) {
                $subject = str_replace('{Ticket-Subject}', $ticket['subject'], $subject);
            }
        }

        return $subject;
    }

    public function template_parser($data_list, $template, $reply_content='', $action_name='',$template_code = '',$ticket = '',$old_params = '',$flwup_note = '',$flwup_updated = '') {
        if(empty($template)) {
            return '';
        }

        if(empty($data_list)) {
            throw new Exception('Provided data list is empty!');
        }

        $template = htmlentities($template);

        if(!empty($reply_content)) {
            if(str_contains($template, '{Ticket-Reply}')) {
                // if($action_name == 'Ticket Followup'){
                //     $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                //     $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                // }else if($action_name == 'Ticket Updated'){
                //     $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                //     $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                // }else if($action_name == 'ticket_reply_update'){
                //     if(!empty($reply_content)){
                //         $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                //     }
                //     $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                // }else{
                //     $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                // }
                
                if($template_code == 'auto_res_ticket_reply'){
                    $reply_content = $reply_content;
                }else{
                    if($action_name == 'Ticket Followup'){
                        $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                        $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                    }else if($action_name == 'Ticket Updated'){
                        $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                        $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                    }else if($action_name == 'ticket_reply_update'){
                        if(!empty($reply_content)){
                            $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                        }
                        $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                    }else{
                        $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                    }
                }
            }
        }
        $actions = '';
        if(!empty($action_name)) {
            $user = \Auth::user();
            if(str_contains($template, '{Ticket-Action}')) {
                $action_by = 'System';
                if(!empty($user)) $action_by = \Auth::user()->name;
                if($action_name == 'Ticket Updated'){
                    $actions = '';
                    for($dd = 0 ; $dd < sizeof($old_params) ; $dd++){

                        if($old_params[$dd]['id'] == '1'){
                            $actions .= '<p><strong>Department:</strong> '.$ticket['department_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }elseif($old_params[$dd]['id'] == '2'){
                            $actions .= '<p><strong>Staff:</strong> '.$ticket['assignee_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }elseif($old_params[$dd]['id'] == '3'){
                            $actions .= '<p><strong>Type:</strong> '.$ticket['type_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }elseif($old_params[$dd]['id'] == '4'){
                            $actions .= '<p><strong>Status:</strong> '.$ticket['status_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }elseif($old_params[$dd]['id'] == '5'){
                            $actions .= '<p><strong>Priority:</strong> '.$ticket['priority_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }

                    }
                }else if($action_name == 'Ticket Followup'){
                    $actions = '';
                    for($dd = 0 ; $dd < sizeof($old_params) ; $dd++){

                        if($old_params[$dd]['id'] == '1'){
                            $actions .= '<p><strong>Department:</strong> '.$ticket['department_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }
                        elseif($old_params[$dd]['id'] == '2'){
                            $actions .= '<p><strong>Staff:</strong> '.$ticket['assignee_name'].' (was: '.$old_params[$dd]["data"].')</p>';

                        }elseif($old_params[$dd]['id'] == '3'){
                            $actions .= '<p><strong>Type: </strong>'.$ticket['type_name'].' (was: '.$old_params[$dd]["data"].')</p>';

                        }elseif($old_params[$dd]['id'] == '4'){
                            $actions .= '<p><strong>Status: </strong>'.$ticket['status_name'].' (was: '.$old_params[$dd]["data"].')</p>';

                            
                        }elseif($old_params[$dd]['id'] == '5'){
                            $actions .= '<p><strong>Priority: </strong>'.$ticket['priority_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                          
                        }
                    }                    
                    if(!empty($reply_content)){
                        $reply_content = '<hr>'.'<strong>Reply: </strong>'.$reply_content;
                    }
                    if(!empty($flwup_note)){
                        $flwup_note = '<hr>'.'<strong>Note: </strong> <br>'.$flwup_note;
                    }
                    
                    $template = str_replace('{Ticket-Note}', $flwup_note, $template);
                    $template = str_replace('{Ticket-Reply}', $reply_content, $template);
                }else if($action_name == 'ticket_reply_update'){

                    $actions = '';
                    for($dd = 0 ; $dd < sizeof($old_params) ; $dd++){

                        if($old_params[$dd]['id'] == '1'){
                            $actions .= '<p><strong>Department:</strong> '.$ticket['department_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                        }
                        elseif($old_params[$dd]['id'] == '2'){
                            $actions .= '<p><strong>Staff:</strong> '.$ticket['assignee_name'].' (was: '.$old_params[$dd]["data"].')</p>';

                        }elseif($old_params[$dd]['id'] == '3'){
                            $actions .= '<p><strong>Type:</strong> '.$ticket['type_name'].' (was: '.$old_params[$dd]["data"].')</p>';

                        }elseif($old_params[$dd]['id'] == '4'){
                            $actions .= '<p><strong>Status:</strong> '.$ticket['status_name'].' (was: '.$old_params[$dd]["data"].')</p>';

                            
                        }elseif($old_params[$dd]['id'] == '5'){
                            $actions .= '<p><strong>Priority:</strong> '.$ticket['priority_name'].' (was: '.$old_params[$dd]["data"].')</p>';
                          
                        }

                    }
                    
                }
                if(!empty($actions)){
                    $actions = '<hr>'.$actions;
                }
                $template = str_replace('{Ticket-Action}', $actions, $template);
            }
            if($template_code == 'ticket_update' || $template_code == 'ticket_followup'){
                if(str_contains($template, '{Ticket-Updated-By}')){
                    if(!empty($user)) {
                        $action_by = \Auth::user()->name;
                    }else if(!empty($flwup_updated)){
                        $action_by = $flwup_updated;
                    }
                    
                    $t_id = $ticket['coustom_id'];
                    $template = str_replace('{Ticket-Updated-By}', $action_by .' Updated #'. $t_id, $template);
                }
            }
            
        }
        
        if(str_contains($template, '{Ticket-Content}')) {
            $content = DB::table('templates')->where('code', 'ticket_content')->first();

            if(!empty($content)) {
                $content = $content->template_html;
                
                $this->replaceShortCodes($data_list, $content);
    
                $template = str_replace('{Ticket-Content}', $content, $template);
            }
        }
        
        if(str_contains($template, '{Staff-Signature}')) {
            $staff_data = array_values(array_filter($data_list, function ($var) {
                return ($var['module'] == 'Tech');
            }));

            if($staff_data[0]['values'] != null || $staff_data[0]['values'] != "" || $staff_data[0]['values'] != [] || $staff_data[0]['values'] != '[]') {
                $signature = $staff_data[0]['values']['signature'];
                if($signature != null) {
                    $signture = preg_replace("/\r\n|\r|\n/", '<br/>', $signature);
                    $template = str_replace('{Staff-Signature}', $signture, $template);    
                }else{
                    $template = str_replace('{Staff-Signature}', '' , $template);
                }
            }else{
                $template = str_replace('{Staff-Signature}', '' , $template);
            }
        }

        if(str_contains($template, '{Our-Company-Details}')) {
            $content = DB::table('templates')->where('code', 'company_details')->first();

            if(!empty($content)) {
                $content = $content->template_html;
                
                $this->replaceShortCodes($data_list, $content);
    
                $template = str_replace('{Our-Company-Details}', $content, $template);
            }
        }

        if(str_contains($template, '{Asset-ID-####}')) {
            $asset = array_values(array_filter($data_list, function($value) {
                return ($value['module'] == 'Asset');
            }));

            if(!empty($asset)) {
                $content = $this->getAssetDetails_SC($asset[0]['values']);
    
                if(!empty($content)) {
                    $template = str_replace('{Asset-ID-####}', $content, $template);
                }
            }
        }

        if(str_contains($template, '{Copyright-Message}')) {
            $cm = DB::table('brand_settings')->pluck('site_footer');

            if(!empty($cm)) {
                $template = str_replace('{Copyright-Message}', $cm[0], $template);
            }
        }

        if(str_contains($template, '{Operating-System}')) {
            // if(!empty($cm)) {
            //     $template = str_replace('{Operating-System}', $cm[0], $template);
            // }
        }

        if(str_contains($template, '{Computer-Specs}')) {
            // if(!empty($cm)) {
            //     $template = str_replace('{Computer-Specs}', $cm[0], $template);
            // }
        }
        
        if(str_contains($template, '{Show-System-Errors}')) {
            // if(!empty($cm)) {
            //     $template = str_replace('{Show-System-Errors}', $cm[0], $template);
            // }
        }

        // replace the generic array modules data
        $this->replaceShortCodes($data_list, $template);

        if(str_contains($template, '{Creator-Name}')) {
            $template = str_replace('{Creator-Name}', 'User', $template);
        }
        if(str_contains($template, '{Tech-Name}')) {
            $template = str_replace('{Tech-Name}', 'Unassigned', $template);
        }

        if(str_contains($template, '{User-Name}')) {
            $tckt = array_values(array_filter($data_list, function ($var) {
                return ($var['module'] == 'Ticket');
            }));

            if(sizeof($tckt) > 0) {
                $ticket = Tickets::where('id' , $tckt[0]['values']['id'])->first();
                if($ticket) {
                    $user = User::where('id' , $ticket->assigned_to)->first();
                    if($user){
                        $template = str_replace('{User-Name}', $user->name, $template);
                    }else{
                        $template = str_replace('{User-Name}', 'Unassigned', $template);
                    }
                    // $template = str_replace('{User-Name}', $user->name, $template);
                }   
            }
        }

        $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
        $tm_name = '';
        if($timezone) {
            $tm_name = $timezone->sys_value != null ? $timezone->sys_value : 'America/New_York';
        }else{
            $tm_name = 'America/New_York';
        }

        
        if(str_contains($template, '{Ticket-SLA}') || str_contains($template, '{Ticket-Resolution-Due}') || str_contains($template, '{Ticket-Reply-Due}')) {
            $sla=''; $res=''; $rep = '';
            $tckt = array_values(array_filter($data_list, function ($var) {
                return ($var['module'] == 'Ticket');
            }));
            if(sizeof($tckt) > 0) {
                $helpd = new HelpdeskController();
                $slaPlan = $helpd->getTicketSlaPlan($tckt[0]['values']['id']);
                $sla = $slaPlan['title'];
                
                if($sla !== HelpdeskController::NOSLAPLAN) {
                    $sla_from = $helpd->getSlaDeadlineFrom($tckt[0]['values']['id']);
                    
                    if(!empty($tckt[0]['values']['reply_deadline']) && !empty($tckt[0]['values']['resolution_deadline'])) {
                        if($tckt[0]['values']['resolution_deadline'] != 'cleared'){
                            $res = Carbon::parse($tckt[0]['values']['resolution_deadline']);
                        }
                        
                        $rep = Carbon::parse($sla_from[1]);
                    } else {
                        if($tckt[0]['values']['resolution_deadline'] != 'cleared'){

                            $date = new \DateTime($tckt[0]['values']['created_at']);
                            $date->setTimezone(new \DateTimeZone($tm_name));                            
                            $res = Carbon::parse( $date->format('Y-m-d H:i:s') );

                            $dt = explode('.', $slaPlan['due_deadline']);
                            $res->addHours($dt[0]);
                            if(array_key_exists(1, $dt)) $res->addMinutes($dt[1]);
                        }
                        $date = new \DateTime($sla_from[0] . '+00');
                        $date->setTimezone(new \DateTimeZone($tm_name));
                        
                        $rep = Carbon::parse( $date->format('Y-m-d H:i:s') );

                        $dt = explode('.', $slaPlan['reply_deadline']);
                        $rep->addHours($dt[0]);
                        if(array_key_exists(1, $dt)) $rep->addMinutes($dt[1]);
                    }
                }
            }

            if(!empty($rep) && $rep != 'cleared') {
                // $crb = new Carbon();
                // $date_diff = '';
                // foreach ($crb->diffAsCarbonInterval($rep, false)->toArray() as $key => $value) {
                //     if($value > 0 && $key != 'microseconds') $date_diff .= $value.$key[0].' ';
                // }
                // if(!empty($date_diff)) $date_diff = ' ('.$date_diff.')';

            
                $currentDate =strtotime( date('Y-m-d H:i:s') );
                $futureDate =strtotime( $rep );

                $diff = $this->getDiff($futureDate , $currentDate);
                
                if( str_contains($diff[0] , '-') ) {
                    $rep = '';
                    if(str_contains($template, 'Reply due:')) {
                        $template = str_replace('Reply due:', '' , $template);
                    }
                }else{
                          
                    $fr = $this->convertFormat(\Session::get('system_date')) . ' h:i:s a';
                    $rep = $rep->format( $fr ) . ' ('.$diff[0].')';

                    if(str_contains($template, 'Reply due:')) {

                        $title = '<span style="color:'.$diff[1].' !important"> Reply due: </span>';
                        $template = str_replace('Reply due:', $title , $template);
                    }
                }
                
            }


            if(!empty($res) && $res != 'cleared') {
                // $crb = new Carbon();
                // $date_diff = '';
                // foreach ($crb->diffAsCarbonInterval($res, false)->toArray() as $key => $value) {
                //     if($value > 0 && $key != 'microseconds') $date_diff .= $value.$key[0].' ';
                // }
                // if(!empty($date_diff)) $date_diff = ' ('.$date_diff.')';

            
                $currentDate = strtotime( date('Y-m-d H:i:s') );
                $futureDate = strtotime( $res );

                $diff = $this->getDiff($futureDate , $currentDate);

                if( str_contains($diff[0] , '-') ) {
                    $res = '';
                    if(str_contains($template, 'Resolution due:')) {
                        $template = str_replace('Resolution due:', '', $template);
                    }
                }else{
                    $fr = $this->convertFormat(\Session::get('system_date')) . ' h:i:s a';
                    $res = $res->format( $fr ) . ' ('.$diff[0].')';

                    if(str_contains($template, 'Resolution due:')) {
                        $title = '<span style="color:'.$diff[1].' !important"> Resolution due: </span>';
                        $template = str_replace('Resolution due:', $title , $template);
                    }
                }
            }

            $template = str_replace('{Ticket-SLA}', $sla, $template);
            $template = str_replace('{Ticket-Reply-Due}', $rep, $template);
            $template = str_replace('{Ticket-Resolution-Due}', $res, $template);
        }
        
        $sc_vars = DB::table('sc_variables')->get();

        foreach ($sc_vars as $key => $value) {
            if(str_contains($template, $value->code)) {
                $template = str_replace($value->code, '', $template);
            }
        }

        return html_entity_decode($template);
    }

    public function getDiff($futureDate , $currentDate) {

        $difference=$futureDate- $currentDate;
        $hours=($difference / 3600);
        $minutes=($difference / 60 % 60);
        $seconds=($difference % 60);
        $days=($hours/24);
        $hours=($hours % 24);
        $days = $days < 0 ? ceil($days) . 'd ' : floor($days) > 'd '; 
        $remainTime = $days . $hours . 'h ' . $minutes . 'm ' . $seconds . 's';
        
        $color = '';
        if ( str_contains( $remainTime , 'd') ) { 
            $color = '#8BB467';
        }else if( str_contains( $remainTime , 'h') ) {
            $color = '#5c83b4';
        }else if( str_contains( $remainTime , 'm') ) {
            $color = '#ff8c5a';
        }
        
        $time[0] = '<span style="color:'. $color .'">' . $remainTime .  '</span>';
        $time[1] = $color;
        return $time;
    }

    public function replaceShortCodes($data_list, &$template) {
        $brand_setting = DB::table("brand_settings")->first();
        $img = '<img src="'.GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/').'/public/files/brand_files/'.$brand_setting->site_logo .'" style="display:block;width:100%;max-width:80px;margin:0px;" width="80"/>';

        if(str_contains($template, '{Company-Logo}')) {
            $template = str_replace('{Company-Logo}', $img, $template);
        }

        // checking whole template having {Ticket-Manager} short code
        if(str_contains($template, '{Ticket-Manager}')) {
            $url = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/'). '/ticket-manager';
            $link = '<a href="'.$url.'"> '. $url .'  </a>';
            $template = str_replace('{Ticket-Manager}', $link , $template);
        }            
                    
        foreach ($data_list as $key => $data) {

            if($data['module'] == 'Customer') {

                if(str_contains($template, '{Customer-ID}')) {
                    $template = str_replace('{Customer-ID}', $data['values']['id'], $template);
                }

                if(str_contains($template, '{Customer-Name}')) {
                    $template = str_replace('{Customer-Name}', $data['values']['first_name']. ' ' .$data['values']['last_name'], $template);
                }

                if(str_contains($template, '{Company-Name}')) {
                    
                    if($data['values']['company_id'] != null){
                        
                        $company = DB::table("companies")->where('id' , $data['values']['company_id'])->first();
                        
                        if($company) {
                            $template = str_replace('{Company-Name}', $company->name , $template);
                        }else{
                           $template = str_replace('Company Name: ', '' , $template); 
                           $template = str_replace('{Company-Name}', '' , $template); 
                        }
                        
                    }else{
                        $template = str_replace('Company Name: ', '' , $template);
                        $template = str_replace('{Company-Name}', '' , $template); 
                    }
                }

                if(str_contains($template, '{Site-Link}')) {
                    $val = 'https://mylive-tech.com/dev';
                    $template = str_replace('{Site-Link}', $val , $template);
                }

            } else if($data['module'] == 'Ticket') {

                if(str_contains($template, '{Ticket-ID}')) {
                    $template = str_replace('{Ticket-ID}', $data['values']['coustom_id'], $template);
                }
                if(str_contains($template, '{Initial-Request}')) {
                    if(array_key_exists('ticket_detail', $data['values'])) {
                        $template = str_replace('{Initial-Request}', $data['values']['ticket_detail'], $template);
                    }
                }

                if(str_contains($template, '{Site-Link}')) {
                    $val = 'https://mylive-tech.com/dev';
                    $template = str_replace('{Site-Link}', $val , $template);
                }

                // if module is ticket then replace url to according to customer && user ticket detail urls.
                if(str_contains($template, '{URL}')) {
                    $url = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/'). '/ticket-details' . '/' . $data['values']['coustom_id'];
                    $template = str_replace('{URL}', $url , $template);
                }
                // ends here

                // customer ticket url
                if(str_contains($template, '{Customer-URL}')) {
                    $url = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/'). '/customer-ticket-details' . '/' . $data['values']['coustom_id'];
                    $template = str_replace('{Customer-URL}', $url , $template);
                }


                if(str_contains($template, '{Ticket-Status-Name}')) {
                    $status = TicketStatus::where('id' , $data['values']['status'])->first();    
                    $status_badge = '<span class="badge" style="background:'.$status['color'].'; padding: 2px 12px; border-radius: 20px; color: white; font-size: 12px;"> '. $status['name'] .'</span>';

                    $template = str_replace('{Ticket-Status-Name}', $status_badge, $template);
                }

                if(str_contains($template, '{Ticket-Priority-Name}')) {
                    $priority = TicketPriority::where('id' , $data['values']['priority'])->first();
                    $priority_badge = '<span class="badge" style="background-color:'.$priority['priority_color'].'; padding: 2px 12px; border-radius: 20px; color: white; font-size: 12px;"> '. $priority['name'] .'</span>';

                    $template = str_replace('{Ticket-Priority-Name}', $priority_badge, $template);
                }
                

                if(str_contains($template, '{Ticket-Attachments}') && !empty($data['values']['attachments'])) {
                    $content = '';
                    $attachs = explode('|', $data['values']['attachments']);
                    foreach ($attachs as $att) {
                        $ext = pathinfo($att, PATHINFO_EXTENSION);
                        $filepath = GeneralController::PROJECT_DOMAIN_NAME.'/'.basename(base_path(), '/').'/storage/tickets/'.$data['values']['id'].'/'.$att;
                        if( in_array($ext, self::IMAGE_EXTENSIONS) ) {
                            $content .= '<div class="reply-attachs-container">
                            <div class="reply-image"><img src="'.$filepath.'" alt="'.$att.'" class="reply-image"></div>
                                <div class="reply-bottom">
                                    <div class="reply-filename">'.$att.'</div>
                                    <a href="'.$filepath.'" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                                </div>
                            </div>';
                        } else {
                            $content .= '<div class="reply-attachs-container">
                                <div class="reply-image"><svg height="120" width="120" class="reply-image">
                                    <text x="0" y="15" fill="grey" transform="translate(35 50)" font-size="20">.'.strtoupper($ext).'</text>
                                    Sorry, your browser does not support inline SVG.
                                </svg></div>
                                <div class="reply-bottom">
                                    <div class="reply-filename">'.$att.'</div>
                                    <a href="'.$filepath.'" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                                </div>
                            </div>';
                        }
                    }
        
                    if(!empty($content)) {
                        $template = str_replace('{Ticket-Attachments}', $content, $template);
                    }
                }
            } else if($data['module'] == 'User') {

                if(str_contains($template, '{User-Name}')) {
                    $template = str_replace('{User-Name}', $data['values']['name'], $template);
                }

                if(str_contains($template, '{User-Email}')) {
                    $template = str_replace('{User-Email}', $data['values']['email'], $template);
                }

                if(str_contains($template, '{User-Password}') && !empty($data['values']['alt_pwd'])) {
                    $template = str_replace('{User-Password}', Crypt::decryptString($data['values']['alt_pwd']), $template);
                }

                if(str_contains($template, '{URL}') && $data['values']['user_type'] == 5) {
                    $template = str_replace('{URL}', request()->root().'/user-login', $template);
                }else{
                    $template = str_replace('{URL}', request()->root().'/login', $template);
                }

                if(str_contains($template, '{Site-Link}')) {
                    $val = 'https://mylive-tech.com/dev';
                    $template = str_replace('{Site-Link}', $val , $template);
                }

            }

            if(!is_array($data['values'])) $data['values'] = (array) $data['values'];


            // timezone 
            $timezone = DB::table("sys_settings")->where('sys_key','sys_timezone')->first();
            $tm_name = '';
            if($timezone) {
                $tm_name = $timezone->sys_value != null ? $timezone->sys_value : 'America/New_York';
            }else{
                $tm_name = 'America/New_York';
            }
            
            foreach ($data['values'] as $key => $value) {
                // echo "<pre>$data['module'] : "; print_r($value); echo "<br><br>";
                $k = str_replace('_', ' ', $key);
                $k = ucwords($k);
                $k = str_replace(' ', '-', $k);
    
                if(!is_array($value) && !is_object($value) && !empty($value)) {
                    // if($data['module'] == 'Ticket')
                    // echo '{'.$data['module'].'-'.$k.'}\n';
                    // echo str_replace('{'.$data['module'].'-'.$k.'}', $value, $template);
                    // change created at and updated at according to user timezone if null then timezone will be america/newyork



                    date_default_timezone_set($tm_name);
                    $fr = $this->convertFormat(\Session::get('system_date')) . ' h:i:s a';
                    $date = date($fr);
                    if($k == 'Created-At' || $k == 'Updated-At') $value = $date;
                    $template = str_replace('{'.$data['module'].'-'.$k.'}', $value, $template);


                    // if($k == 'Status-Name') {
                    //     if($data['module'] == 'Ticket') {
                    //         $name = '{'.$data['module'].'-'.$k.'}';
                    //         $status = TicketStatus::where('id' , $data['values'][$key]['status'])->first();
                        
                    //         $value = '<span class="badge" style="background-color='.$status['color'].'"> '. $status['name'] .'</span>';
                    //         $template = str_replace( $name , $value, $template);
                    //     }
                    // }


                    // if($data['module'] == 'Ticket') {
                    //     $name = '{'.$data['module'].'-'.$k.'}';
                    //     $priority = TicketPriority::where('id' , $data['values'][$key]['priority'])->first();
                    
                    //     $value = '<span class="badge" style="background-color='.$priority['priority_color'].'"> '. $priority['name'] .'</span>';
                    //     $template = str_replace( $name , $value, $template);
                    // }
                }
            }
        }
    }

    function convertFormat($format) {

        $replacements = [
            'DD'   => 'd', 
            'ddd'  => 'D', 
            'D'    => 'j', 
            'dddd' => 'l', 
            'E'    => 'N', 
            'o'    => 'S',
            'e'    => 'w', 
            'DDD'  => 'z', 
            'W'    => 'W', 
            'MMMM' => 'F', 
            'MM'   => 'm', 
            'MMM'  => 'M',
            'M'    => 'n', 
            'YYYY' => 'Y', 
            'YY'   => 'y', 
            'a'    => 'a', 
            'A'    => 'A', 
            'h'    => 'g',
            'H'    => 'G', 
            'hh'   => 'h', 
            'HH'   => 'H', 
            'mm'   => 'i', 
            'ss'   => 's', 
            'SSS'  => 'u',
            'zz'   => 'e', 'X'    => 'U',
        ];

        $phpFormat = strtr($format, $replacements);
        return $phpFormat;
    }

    public function getAssetDetails_SC($asset) {
        $form = AssetForms::with('fields')->findOrFail($asset['asset_forms_id'])->toArray();
        
        $records = DB::table('asset_records_'.$form['id'])->where('asset_id', $asset['id'])->get()->toArray();

        $recs = '';

        foreach($records as $record) {
            foreach ($form['fields'] as $key => $field) {
                $recs .= '<tr>
                    <td>' . $field['label'] .' '. $record->{'fl_'.$field['id']} .'</td>
                </tr>';
            }
        }

        return htmlentities('<div style="padding: 40px; background: #fff;">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tbody>
                    <tr>
                        <td>
                        Asset ID :&nbsp;'.$asset['id'].' 
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Asset Template :&nbsp;'.$form['title'].'
                        </td>
                    </tr>
                    '.$recs.'
                </tbody>
            </table>
        </div>');
    }
}