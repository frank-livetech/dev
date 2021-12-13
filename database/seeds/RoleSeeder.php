<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesData = array(

            array("name" => 'Administrator',"guard_name" => 'web'),
            array("name" => 'Staff',"guard_name" => 'web'),
            array("name" => 'Developer',"guard_name" => 'web'),
            array("name" => 'Vendor',"guard_name" => 'web'),
            array("name" => 'Customer',"guard_name" => 'web'),
            array("name" => 'Project Manager',"guard_name" => 'web'),
            array("name" => 'Billing',"guard_name" => 'web'),
            array("name" => 'Tech Support',"guard_name" => 'web'),
            array("name" => 'Office Manager',"guard_name" => 'web'),
            array("name" => 'Office Staff',"guard_name" => 'web'),
            array("name" => 'Field Agent',"guard_name" => 'web'),
 
        );


        foreach($rolesData as $key => $value) {

            DB::table("roles")->insert([
                $key => $value,
            ]);

        }
    }
}
