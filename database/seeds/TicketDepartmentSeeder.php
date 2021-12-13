<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TicketDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tktDeptData = array(

            array("name" => 'Billing / Quotes'),
            array("name" => 'Support'),
            array("name" => 'Sales'),
        );


        foreach($tktDeptData as $key => $value) {

            DB::table("departments")->insert([
                $key => $value,
            ]);

        }
    }
}
