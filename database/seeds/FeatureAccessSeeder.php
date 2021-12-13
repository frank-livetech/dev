<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeatureAccessSeeder extends Seeder
{
   
    public function run() {

        $feature_data = array(

            // dashboard
            array( "f_id" => "1", "title" => 'Dashboard', "route" => 'home', "sequence"  => '1', "parent_id" => 0, 
            "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => '<i class="mdi mdi-view-dashboard"></i>' ),

            // customer manager start
            array("f_id" => "2", "title" => 'Customer Manager', "route" => 'NULL', "sequence"  => '100',"parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-account-multiple"></i>' ),

            array( "f_id" => "3",  "title" => 'Customer Lookup', "route" => 'customer.lookup', "sequence"  => '101', "parent_id" => 2,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL ),

            array( "f_id" => "4","title" => 'Company Lookup', "route" => 'company.lookup', "sequence"  => '102', "parent_id" => 2,
                    "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL ),

            array( "f_id" => "5","title" => 'Customer Stats', "route" => 'customer.stats', "sequence"  => '103', "parent_id" => 2,
                    "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL ),

            array( "f_id" => "6", "title" => 'Service Stats', "route" => 'service.stats', "sequence"  => '104', "parent_id" => 2,
                    "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),
            // customer manager ends

            // billing start
            array("f_id" => "7", "title" => 'Billing', "route" => 'NULL', "sequence"  => '105', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-table-large"></i>'),

            array("f_id" => "8", "title" => 'Billing Dashboard', "route" => 'billing.home', "sequence"  => '106', "parent_id" => 7,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),

            array("f_id" => "9", "title" => 'RFQ', "route" => 'rfq.index', "sequence"  => '107', "parent_id" => 7,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "10", "title" => 'Invoice Maker', "route" => 'invoice_maker.index', "sequence"  => '108', "parent_id" => 7,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),

            array("f_id" => "11", "title" => 'Reports', "route" => 'reports.index', "sequence" => '109', "parent_id" => 7,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),
            // billing ends

            // helpdesk start
            array("f_id" => "12", "title" => 'Help Desk', "route" => 'NULL', "sequence"  => '110', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-comment-question-outline"></i>'),

            array("f_id" => "13", "title" => 'Ticket Manager', "route" => 'ticket_management.index', "sequence"  => '111', "parent_id" => 12,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "14", "title" => 'Asset Manager', "route" => 'asset_manager.index', "sequence"  => '112', "parent_id" => 12,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),

            array("f_id" => "15", "title" => 'Task Scripts', "route" => 'task_scripts.index', "sequence" => '113', "parent_id" => 12,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),
            // helpdesk ends

            // project list start
            array("f_id" => "16", "title" => 'Project Manager', "route" => 'NULL', "sequence"  => '114', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-view-dashboard"></i>'),

            array("f_id" => "17", "title" => 'List Projects', "route" => 'project_list.index', "sequence"  => '115', "parent_id" => 16,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "18", "title" => 'My Task', "route" => 'my_task.index', "sequence"  => '116', "parent_id" => 16,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),
            // project list  ends

            // dispatch start
            array("f_id" => "19", "title" => 'Dispatch', "route" => 'NULL', "sequence"  => '117', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-truck"></i>'),

            array("f_id" => "20", "title" => 'Watch', "route" => 'watch.index', "sequence"  => '118', "parent_id" => 19,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),
            // dispatch ends

            // marketing start
            array("f_id" => "21", "title" => 'Marketing', "route" => 'NULL', "sequence"  => '119', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-account-multiple"></i>'),

            array("f_id" => "22", "title" => 'Contact Manager', "route" => 'contact_manager.index', "sequence"  => '120', "parent_id" => 21,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "23", "title" => 'Product Manager', "route" => 'product_manager.index', "sequence"  => '121', "parent_id" => 21,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),
            // marketing  ends

            // system manager start
            array("f_id" => "24", "title" => 'System Manager', "route" => 'NULL', "sequence"  => '122', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-table-large"></i>'),

            array("f_id" => "25", "title" => 'Staff Manager', "route" => 'staff_manager.index', "sequence"  => '123', "parent_id" => 24,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "26", "title" => 'Roles', "route" => 'roles.index', "sequence"  => '124', "parent_id" => 24,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),

            // array("f_id" => "27", "title" => 'Feature', "route" => 'features.index', "sequence" => '125', "parent_id" => 24,
            //     "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),

            // array("f_id" => "28", "title" => 'User Access Manager', "route" => 'system_access_manager.index', "sequence"  => '126', "parent_id" => 24,
            //     "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "29", "title" => 'Template Builder', "route" => 'templateList', "sequence"  => '127', "parent_id" => 24,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),

            array("f_id" => "30", "title" => 'Integrations', "route" => 'integrations.index', "sequence" => '128', "parent_id" => 24,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),
            
            array("f_id" => "31", "title" => 'Settings', "route" => 'settings.index', "sequence" => '129', "parent_id" => 24,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => NULL),
            // system manager ends

            // reports start
            array("f_id" => "32", "title" => 'Reports', "route" => 'NULL', "sequence"  => '130', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-wrench"></i>'),

            array("f_id" => "33", "title" => 'Staff Attendance', "route" => 'staff_attendance.index', "sequence"  => '131', "parent_id" =>32,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),
            // reports ends


            // live chat start
            array("f_id" => "35", "title" => 'Live Support Chat', "route" => 'chats.index', "sequence"  => '133', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1', "menu_icon" => '<i class="mdi mdi-wechat"></i>'),
            // live chat ends


            // About start
            array("f_id" => "36", "title" => 'About', "route" => 'NULL', "sequence"  => '134', "parent_id" => 0,
                "is_active" => '1', "role_id" => '1', "feature_type" => '2', "menu_icon" => '<i class="mdi mdi-information" style="color:red;"></i>'),

            array("f_id" => "37", "title" => 'System Info', "route" => 'system_info.index', "sequence"  => '135', "parent_id" => 36,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),

            array("f_id" => "38", "title" => 'Feature Suggestion', "route" => 'feature_suggestions.index', "sequence"  => '136', "parent_id" => 36,
                "is_active" => '1', "role_id" => '1', "feature_type" => '1',"menu_icon" => NULL),
            // about  ends

        );


        foreach($feature_data as $key => $value) {

            DB::table("ac_features")->insert([
                $key => $value,
            ]);

        }
    }
}
