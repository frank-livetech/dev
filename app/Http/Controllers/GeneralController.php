<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php';

class GeneralController extends Controller {

    // *************   PROPERTIES   ****************

    public $cc_string = '';
    const PROJECT_DOMAIN_NAME = 'https://mylive-tech.com';

    
    // ***************   METHODS   *****************

    
    public function __construct() {
        // 
    }

    public function randomStringFormat($format) {
        $k = strlen($format);
        $sernum = '';
        for ($i=0; $i<$k; $i++)
        {
            switch($format[$i])
            {
                case 'X': $sernum .= chr(rand(65,90)); break;
                case '9': $sernum .= rand(0,9); break;
                case '-': $sernum .= '-';  break; 
            }
        }
        return $sernum;
    }
}
