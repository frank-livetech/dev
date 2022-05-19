<?php

namespace App\Http\Controllers\API\Ticket;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivitylogController;
use Illuminate\Http\Request;
use App\User;

use App\Models\Tickets;
use App\Models\Activitylog;
use App\Models\TicketSettings;
use App\Models\Departments;
use App\Models\TicketPriority;

use Validator;
use Throwable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use DB;

class TicketController extends Controller
{
    public function addTicket(Request $request){

        $data = $request->all();
        $response = array();
        $ticket_settings = TicketSettings::where('tkt_key','ticket_format')->first();
        try{

            $tickets_count = Tickets::all()->count();

            $data['created_by'] = \Auth::user()->id;
            $ticket = Tickets::create($data);
            // return $ticket;
            $ticket->coustom_id = $ticket_settings->tkt_value == 'random' ? $this->sernum() : '#'.$ticket->id;
            $ticket->seq_custom_id = $tickets_count+1;
            $ticket->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

            $action_perform = 'Ticket ID'.$ticket->coustom_id.' Created By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'tickets' ,$ticket->id , auth()->id() , $action_perform);
            
           // $this->sendNotificationMail($data,$ticket);
            $response['message'] = 'Tickets Added Successfully!';
            $response['status_code'] = 200;
            $response['data'] = $ticket;
            $response['success'] = true;
            return response()->json($response);

        }catch(Exception $e){
            $response['message'] = 'Something Went wrong!';
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }


    }

    protected function sernum()
    {
        $template =  'XXX-999-9999';
        $k = strlen($template);
        $sernum = '';
        for ($i=0; $i<$k; $i++)
        {
            switch($template[$i])
            {
                case 'X': $sernum .= chr(rand(65,90)); break;
                case '9': $sernum .= rand(0,9); break;
                case '-': $sernum .= '-';  break; 
            }
        }
        return $sernum;
    }

    public function departments(){

        $departments = Departments::all();

        $response['message'] = 'All Departments.';
        $response['data'] = $departments;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }

    public function priorities(){

        $priorities = TicketPriority::all();

        $response['message'] = 'All Priorities.';
        $response['data'] = $priorities;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }

    public function tickets(){

        $tickets = Tickets::all();

        $response['message'] = 'All Tickets.';
        $response['data'] = $tickets;
        $response['status_code'] = 200;
        $response['success'] = true;

        return response()->json($response);

    }
}