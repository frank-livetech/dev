<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tktTypeData = array(

            array("name" => 'Billing'),
            array("name" => 'Sales'),
            array("name" => 'Lead'),
            array("name" => 'Feedback')
        );


        foreach($tktTypeData as $key => $value) {

            DB::table("ticket_types")->insert([
                $key => $value,
            ]);

        }
    }
}
