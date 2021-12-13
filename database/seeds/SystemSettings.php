<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SystemSettings extends Seeder
{

    public function run() {
        
        $system_setting_data = array(
            "sys_dt_frmt" => "DD-MM-YYYY",
            "sys_tm_frmt" => "LT",
            "currency_format"  => "<i class='fas fa-dollar-sign'></i>",
            "bill_order_id_frmt" => "random",
            "sell_inst_note" => "this is the notes",
            "currency_format" => "<i class='fas fa-dollar-sign'></i>",
            "is_installed" => "1",
            "migration" => "1",
            "is_live" => "1",
        );

        foreach($system_setting_data as $key => $value) {
            DB::table("sys_settings")->insert([
                "sys_key" => $key,
                "sys_value" => $value,
            ]);
        }

    }
}
