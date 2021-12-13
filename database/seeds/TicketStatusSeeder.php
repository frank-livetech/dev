<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tktStatusesData = array(

            array("name" => 'On Hold / Call Back',"color" => '#da650b'),
            array("name" => 'Open',"color" => '#a982ab'),
            array("name" => 'In Development',"color" => '#a28c02'),
            array("name" => 'Working To Resolve',"color" => '#6dbaa1'),
            array("name" => 'Pending Payment',"color" => '#eb2828'),
            array("name" => 'Closed',"color" => '#ff0000'),
 
        );


        foreach($tktStatusesData as $key => $value) {

            DB::table("ticket_statuses")->insert([
                $key => $value,
            ]);

        }
    }
}
