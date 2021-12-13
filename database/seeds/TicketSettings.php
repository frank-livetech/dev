<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TicketSettings extends Seeder
{
    
    public function run() {


        $tkt_setting_data = array(
            "reply_due_deadline" => "0",
            "reply_due_deadline_when_adding_ticket_note" => "0",
            "default_reply_and_resolution_deadline"  => "0",
            "default_reply_time_deadline" => "1",
            "default_resolution_deadline" => "1",
            "overdue_ticket_background_color" => "#1976d2",
            "overdue_ticket_text_color" => "#3949ab",
            "ticket_format" => "random",
        );

        foreach($tkt_setting_data as $key => $value) {
            DB::table("ticket_settings")->insert([
                "tkt_key" => $key,
                "tkt_value" => $value,
            ]);
        }

    }
}
