<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketPrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tktPriorityData = array(

            array("name" => 'Low','priority_color' => '#a3ddff'),
            array("name" => 'Medium','priority_color' => '#acecbc'),
            array("name" => 'High','priority_color' => '#eee720'),
        );


        foreach($tktPriorityData as $key => $value) {

            DB::table("ticket_priorities")->insert([
                $key => $value,
            ]);

        }
    }
}
