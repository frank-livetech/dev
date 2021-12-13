<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShortCodesSeeder extends Seeder
{

    public function run() {
        
        $shortCodeData = array(

            array("code" => '{Customer-Name}', "description" => 'Calls the users full name'),
            array("code" => '{User-Name}', "description" => '---'),
            array("code" => '{Tech-Name}', "description" => '---'),
            array("code" => '{Creator-Name}', "description" => '---'),
            array("code" => '{Customer-Phone}', "description" => '---'),
            array("code" => '{Customer-Email}', "description" => '---'),
            array("code" => '{Customer-Address}', "description" => '---'),
            array("code" => '{Our-Company-Details}', "description" => 'Displays Company name, phone number, and email for...'),
            array("code" => '{Ticket-ID}', "description" => '---'),
            array("code" => '{Ticket-Priority-Name}', "description" => 'Display the tickets priority'),
            array("code" => '{Ticket-Status-Name}', "description" => '---'),
            array("code" => '{Ticket-Department-Name}', "description" => '---'),
            array("code" => '{Ticket-Type-Name}', "description" => '---'),
            array("code" => '{Ticket-Content}', "description" => '---'),
            array("code" => '{Ticket-Subject}', "description" => '---'),
            array("code" => '{Show-System-Errors}', "description" => 'Calls last 50 lines of error logs to display on em...'),
            array("code" => '{Copyright-Message}', "description" => '---'),
            array("code" => '{Our-Company-Phone}', "description" => '---'),
            array("code" => '{Our-Company-WhatsApp}', "description" => 'Displays the WhatsApp icon preset for click to inc...'),
            array("code" => '{Our-Company-SMS}', "description" => '---'),
            array("code" => '{Asset-ID-####}', "description" => 'Change #### for the assets id to display the details from assets profile'),
            array("code" => '{Computer-Specs}', "description" => '---'),
            array("code" => '{Operating-System}', "description" => '---'),
            array("code" => '{User-Email}', "description" => '---'),
            array("code" => '{User-Phone-Number}', "description" => '---'),
            array("code" => '{Our-Company-Name}', "description" => '---'),
            array("code" => '{Our-Company-Email}', "description" => '---'),
            array("code" => '{Items-Row}', "description" => 'Item name of order'),
            array("code" => '{Item-Total}', "description" => 'order item total'),
            array("code" => '{Order-ID}', "description" => 'order id'),
            array("code" => '{Order-Date}', "description" => 'order date'),
            array("code" => '{Company-Logo}', "description" => 'company logo display on invoice'),
            array("code" => '{Invoice-Notes}', "description" => 'invoice notes'),
            array("code" => '{Invoice-Fees}', "description" => 'invoice fees'),
            array("code" => '{Thank You}', "description" => 'invoice thank you message'),
            array("code" => '{Item-SubTotal}', "description" => 'invoice sub total '),
            array("code" => '{Reset-Button}', "description" => 'reset button for reset the password'),
            array("code" => '{User-Password}', "description" => 'user password show here'),
            array("code" => '{Ticket-Reply}', "description" => '---'),
            array("code" => '{Ticket-Action}', "description" => '---'),
            array("code" => '{Ticket-Note}', "description" => '---'),
            array("code" => '{Initial-Request}', "description" => '---'),
            array("code" => '{Staff-Signature}', "description" => '---'),
            array("code" => '{Ticket-Created-At}', "description" => '---'),
            array("code" => '{Creator-Email}', "description" => '---'),
            array("code" => '{URL}', "description" => '---'),
            array("code" => '{Template-Group}', "description" => '---'),
            array("code" => '{Alert-Prefix}', "description" => 'Alert prefix is used for email notifications'),

        );


        foreach($shortCodeData as $key => $value) {

            DB::table("sc_variables")->insert([
                $key => $value,
            ]);

        }
    }
}
