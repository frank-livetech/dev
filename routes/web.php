<?php

use Illuminate\Support\Facades\Route;
// use App\Models\BrandSettings;


Route::get('/', function () {
    if (Auth::user()) {
        // if(Auth::user()->user_type != 5 && Auth::user()->user_type != 4){
            return redirect()->intended('/home');
        // }elseif(Auth::user()->user_type == 5){
        //     return redirect()->intended('/myprofile');
        // }
    }else {
        return redirect()->intended('/login');
    }
});

Route::get('/', 'InstallationController@index');
Route::post('/installation-data', 'InstallationController@saveInstallation');
Route::get('/user-login', 'AuthController@userLogin')->name('user-login');
Route::post('/user-login', 'AuthController@userPostLogin');

Route::get('/user-register', 'AuthController@userRegister')->name('user-register');

Route::post('/save-user-register', 'AuthController@saveUserDetails')->name('user.register');


//Auth::routes();
Route::get('/login', 'AuthController@index')->name('login');
Route::post('/login', 'AuthController@postLogin'); 

Route::get('/logout', 'AuthController@logout')->name('logout');

Route::get('/forgetPassword', 'AuthController@forgetPassword');
Route::post('/recoverPassword','AuthController@recoverPassword');
Route::get('/activate/{email}/{code}','AuthController@changePasswordPage');
Route::post('/reset_password','AuthController@ResetPassword');
Route::get('auth/google', 'AuthController@redirectToGoogle');
Route::get('auth/google/callback', 'AuthController@handleGoogleCallback');
Route::get('auth/google/callback', 'AuthController@handleGoogleCallback');
Route::get('check_clockins', 'PayrollManager\PayrollController@check_clockins');
Route::get('checkActiveTasksWithClockIn', 'PayrollManager\PayrollController@checkActiveTasksWithClockIn');


Route::group(['middleware' => ['auth']], function() {
    

//
});

Route::get('/get_all_staff_att', 'HomeController@getAllStaffAttendance');
Route::get('/mark_all_read', 'HomeController@markAllRead');
Route::get('/unauth', 'HomeController@unauth')->name('un_auth');


Route::get('/getNotifications', 'HomeController@getNotifications');
Route::get('/wizard', 'HomeController@wizard');

Route::group ( ['namespace' => 'Chat','middleware' => ['auth','admin']], function () {
    Route::get('/chat', 'LiveChatController@index')->name('chats.index');
});
// new routes

Route::group ( ['namespace' => 'PayrollManager','middleware' => ['auth','admin']], function () {

    Route::post('/add_checkin','PayrollController@clockin');
    Route::post('/add_checkout','PayrollController@clockout');
    Route::post('/save_payroll_settings','PayrollController@save_payroll_settings');
    Route::post('/update-work-hours','PayrollController@update_work_hours');

});

// Home Controller admin or staff  After Login
Route::get('/home', 'HomeController@index')->middleware('auth','admin')->name('home');
//=================================================

Route::get('/staff_attendance','ReportsController@index')->name('staff_attendance.index');
Route::get('/staff_list','ReportsController@staffData');
Route::post('/get_staff_attendance','ReportsController@getStaffAttendance');

Route::group ( ['namespace' => 'SystemManager','middleware' => ['auth','admin']], function () {
    
    //Roles Resource Route
    Route::get('roles','RoleController@index')->name('roles.index');
    Route::post('add_roles','RoleController@store');
    Route::get('get_roles','RoleController@create');
    Route::post('update_roles/{id}','RoleController@update');
    Route::get('delete_roles/{id}','RoleController@destroy');
    Route::get('test','RoleController@test')->name('test.index');
    // features
    Route::get('/features','FeatureController@index')->name('features.index');
    
    Route::post('/add_features','FeatureController@store');
    Route::get('/features_list','FeatureController@get_all_features');
    Route::get('/get_features_by_id/{id}','FeatureController@get_feature_by_id');
    Route::post('/update_feature','FeatureController@update_feature');
    //Setting Routess
    Route::get('/settings','SettingsController@settings')->name('settings.index');
    Route::get('/settings-new','SettingsController@settingsNew')->name('settings.index-new');
    //Response Category Crud

    Route::post('/add_cat','SettingsController@addResponseCategory');
    Route::get('/get_all_resTemplate','SettingsController@getallCatResponse');
    Route::post('/update_cat_response','SettingsController@updateCatResponse');
    Route::post('/delete_cat','SettingsController@delete_catResponse');

    //Response Template Crud
    Route::post('/save-temp-response','SettingsController@addResponseTemplate');
    Route::get('/show_response_template','SettingsController@showResponseTemplate');
    Route::post('/update_response_template','SettingsController@updateResponseTemplate');
    Route::post('/delete_response_template','SettingsController@deleteResponseTemplate');

    Route::get('/all-notifications','SettingsController@showAllNotifications');

    // SLA crud
    Route::post('/add_sla','SettingsController@addSLA');
    Route::get('/get_all_sla','SettingsController@getAllSLA');
    Route::post('/update_sla','SettingsController@updateSLA');
    Route::post('/delete_sla','SettingsController@deleteSLA');

    Route::post('/sla_setting','SettingsController@SLASetting');
    Route::post('/customer_setting','SettingsController@customerSetting');

    Route::post('/save-brand-settings','SettingsController@saveBrandSettings');
    Route::post('/save-color-settings','SettingsController@saveColorSettings');
    Route::post('/save_sys_date_time','SettingsController@saveSystemDateAndTime');
    //Mail Routes
    Route::get('/get-mails','MailController@get_mails');
    Route::post('/get_mail_by_id','MailController@get_email_by_id');
    Route::post('/update_email','MailController@updateEmail');
    Route::post('/save-mail','MailController@save_mail');
    Route::post('/del-mail','MailController@delete_mail');
    //verify mail
    Route::post('/verify-connection','MailController@verify_connection');
    Route::get('/save-inbox-replies','MailController@save_inbox_replies');
    //Staff Management Routes
    Route::get('/staff-manager','UserController@index')->name('staff_manager.index');
    Route::get('/staff-manager-new','UserController@new');
    Route::get('/leave-manager','UserController@leaveManagement')->name('leave_manager.index');
    // Route::get('/system_manager/features','FeatureController@index');
    Route::get('system_manager/access_manager','UserController@index')->name('system_access_manager.index');
    Route::post('/insert_user','UserController@insertUsers');
    Route::post('/upload_user_img','UserController@uploadUserImage');
    Route::post('/update_password','UserController@update_password');
    Route::get('/get_staff_schedule','UserController@getStaffSchedule');
    Route::post('/add_staff_schedule','UserController@addStaffSchedule');
    Route::post('/delete_staff_schedule','UserController@deleteStaffSchedule');
    Route::get('/get-staff-users','UserController@get_users');
    Route::post('/update-staff','UserController@updateStaff');
    //for profile view
    Route::get('/profile/{id}','UserController@profile');
    Route::get('/profile-new/{id}','UserController@newProfile');

    Route::post('/dept_permission','UserController@departmentPermission');

    
    Route::get('/get_staff_tasks','UserController@getStaffTasks');
    Route::get('/my-profile','UserController@my_profile');
    Route::get('/customer_profile','UserController@customer_profile')->name('customer_profile');
    Route::post('/add-new-certification','UserController@add_new_certification');
    Route::post('/add-new-documents','UserController@add_new_documents');
    Route::get('get-all-certificates/{id}','UserController@get_all_certificates');
    Route::get('/get-all-documents/{id}','UserController@get_all_docs');
    Route::post('change_theme_mode','UserController@change_theme_mode');
    Route::post('/save-staff-color','UserController@saveColorSettings');
    Route::post('/ticket-format','SettingsController@ticket_format');
    Route::get('/get-color','UserController@get_color');
    Route::Post('/delete-user','UserController@delete_user');
    
    // leaves
    Route::post('/add-leaves','UserController@addLeaves');
    Route::get('/get-leaves','UserController@get_all_leaves');
    Route::post('/delete-leave','UserController@delete_leaves');
    //leaves change status
    Route::post('/change-leave-status','UserController@leave_status');


    Route::post('/add-staff-shift','UserController@add_staff_shift');


    Route::get('/leave-management','UserController@leave_index')->name('leave_management.index');

    
});

Route::group ( ['namespace' => 'Billing','middleware' => ['auth','admin']], function () {

    //Billing RFQ  Routes
    Route::get('/rfq','BillingController@rfq')->name('rfq.index');
    Route::get('/rfq-new','BillingController@rfqNew')->name('rfq.index-new');
    Route::post('/save_rfq_requests','BillingController@saveRFQRquests');
    Route::post('/save_inst_notes','BillingController@saveInstNotes');
    //Vendor Profile View Route
    Route::get('/vendors_profile/{id}','BillingController@vendorsProfile');
    //Vendors Routes
    Route::post('/save-vendor','BillingController@saveVendor');
    Route::post('/update_vendor','BillingController@updateVendor')->name('update.vendor');
    Route::get('/get-vendors','BillingController@getVendors');
    Route::post('/save-category','BillingController@saveCategory');
    Route::get('/get-categories','BillingController@getCategories');
    Route::get('/get-notes','BillingController@getNotes');
    Route::Post('/delete-vendor','BillingController@delete_vendor');
    
    Route::get('/invoice-maker/{id?}','BillingController@invoice_maker')->name('invoice_maker.index');
    Route::get('/create_pdf_invoice/{id}','BillingController@createPDFInvoice');

    
    Route::post('/billing/published','BillingController@billing_home');
    Route::get('/billing/home','BillingController@billingHomePage')->name('billing.home');
    Route::get('/billing/home-new','BillingController@billingHomePageNew')->name('billing.home-new');
    Route::get('/get-all-orders','BillingController@get_all_orders');
    Route::get('/get-all-subs','BillingController@get_all_subs');


    Route::post('/create_invoice','BillingController@createInvoice');
    
    Route::post('/update_order/{id}','BillingController@updateOrder');

    Route::get('/get_customer_by_id/{id}','BillingController@getCustomerById');
    Route::post('/update_customer_address','BillingController@updateCustomerById');
    Route::get('/reports','BillingController@reports')->name('reports.index');

    Route::post('/save_billing_orderid_format','BillingController@BillingOrderIdFormat');
    Route::post('/save_mode_form','BillingController@SaveModeForm');

});


Route::group ( ['namespace' => 'ProjectManager','middleware' => ['auth','admin']], function () {

    Route::get('/projects-list','ProjectManagerController@projects_list')->name('project_list.index');
    Route::get('/my-tasks','ProjectManagerController@my_tasks')->name('my_task.index');

    Route::get('/get_all_tasks','ProjectManagerController@get_all_tasks');

    Route::get('/task_lists/{id}','ProjectManagerController@AllTaskLists');

    
    Route::post('/get_tasks_by_date','ProjectManagerController@getAllTasksByDates');
    Route::post('/get_overdue_tasks','ProjectManagerController@getAllOverDueTasks');


    Route::get('task-details/{task_id}','ProjectManagerController@my_task_details');
    Route::get('/task_details/{task_id}','ProjectManagerController@taskDetails');


    Route::post('/change-my-task-status','ProjectManagerController@chnageMyTaskStatus');
    Route::post('/revert-task','ProjectManagerController@revertTask');
    Route::post('/upload-reverted-task-img','ProjectManagerController@revertedTaskImage');

    Route::get('/daily-progress','ProjectManagerController@daily_progress');
    Route::Post('/save-folder','ProjectManagerController@save_folder');
    Route::Post('/delete-folder','ProjectManagerController@delete_folder');
    Route::Post('/save-project','ProjectManagerController@save_project');
    Route::Post('/delete-project','ProjectManagerController@delete_project');
    Route::Post('/delete-task','ProjectManagerController@delete_task');
    Route::Post('/get-task-byid','ProjectManagerController@getTaskById');
    
    Route::get('/task_project_2','ProjectManagerController@task_project_2');
    Route::Post('/save_project_desc','ProjectManagerController@saveProjectDescription');

    Route::Post('/save_project_notes','ProjectManagerController@saveProjectNotes');
    Route::get('/tags_project_notes','ProjectManagerController@tags_project_notes');

    Route::get('/get_project_notes/{id}','ProjectManagerController@getProjectNotes');
    Route::post('/del_project_notes','ProjectManagerController@deleteProjectNotes');
    Route::post('/update_project_notes','ProjectManagerController@updateProjectNotes');

    Route::get('/get_activity_logs','ProjectManagerController@getProjectActivityLogs');

    Route::get('/roadmap/{project_slug}','ProjectManagerController@projectRoadmap');
    Route::get('/roadmap/{project_slug}/{status}/{id}','ProjectManagerController@projectRoadmap');
    Route::get('/project-task','ProjectManagerController@project_task');
    Route::Post('/update-project-title','ProjectManagerController@update_title');
    Route::Post('/update-project-customer','ProjectManagerController@update_customer');
    Route::Post('/update-project-manager','ProjectManagerController@update_manager');
    Route::Post('/save-server-detail','ProjectManagerController@save_server_detail');
    Route::Post('/save-project-task','ProjectManagerController@add_project_task');
    Route::Post('/update-tasks-order','ProjectManagerController@updateTasksOrder');
    Route::get('/read_notification/{id}','ProjectManagerController@readNotification');
     
    Route::get('/todays_tasks','ProjectManagerController@todaysTasks');
});

Route::group ( ['namespace' => 'SystemManager','middleware' => ['auth','admin']], function () {


    Route::Post('/save-department','SettingsController@save_department');
    Route::get('/get_all_counts','SettingsController@get_all_counts');

    Route::get('/get-departments','SettingsController@get_departments');

    Route::post('/show_departments','SettingsController@showDepartmentPermission');

    Route::post('/save_email_recap_noti','SettingsController@SaveEmailRecapNotification');
    Route::post('/send_recap_mails','SettingsController@sendRecapsEmails');


    Route::Post('/save-status','SettingsController@save_status');
    Route::get('/get-statuses','SettingsController@get_statuses');
    Route::Post('/save-priority','SettingsController@save_priorities');
    Route::get('/get-priorities','SettingsController@get_priorities');
    Route::Post('/save-type','SettingsController@save_type');
    Route::get('/get-types','SettingsController@get_types');
    Route::Post('/save-customer-type','SettingsController@save_customer_type');
    Route::Post('/save-dispatch-status','SettingsController@save_dispatch_status');
    Route::Post('/save-project-type','SettingsController@save_project_type');
    Route::get('/get-customer-types','SettingsController@get_customer_types');
    Route::get('/get-dispatch-status','SettingsController@get_dispatch_status');
    Route::get('/get-project-type','SettingsController@get_project_type');
    Route::Post('/delete-department','SettingsController@delete_department');
    Route::Post('/delete-priority','SettingsController@delete_priority');
    Route::Post('/delete-status','SettingsController@delete_status');
    Route::Post('/delete-type','SettingsController@delete_type');
    Route::Post('/delete-customer-type','SettingsController@delete_customer_type');
    Route::Post('/delete-dispatch-status','SettingsController@delete_dispatch_status');
    Route::Post('/delete-project-type','SettingsController@delete_project_type');
    
    Route::get('/integrations','IntegrationController@integration');
    Route::post('/integrations','IntegrationController@save_details')->name('integrations.index');
    Route::post('/IntegrationsVerify','IntegrationController@IntegrationsVerify')->name('IntegrationsVerify');
    Route::post('/integrationsStatus','IntegrationController@integrations_status');

    Route::post('/get_wp_customers','IntegrationController@getWPCustomers');
    Route::post('/get_wp_orders','IntegrationController@getWPOrders');

    // short codes
    Route::post('add_short_codes','TemplatesController@addShortCodes');
    Route::get('get_all_short_codes','TemplatesController@getAllShortCodes');
    Route::get('delete_short_codes/{id}','TemplatesController@deleteShortCodes');
    Route::post('update_short_codes','TemplatesController@updateShortCodes');
    //Route::get('/template-builder','TemplatebuilderController@template_builder');
    Route::get('/template-builder', 'MailablesController@toMailablesList');

    Route::group(['prefix' => 'template-builder/mailables'], function () {

        Route::get('/', 'MailablesController@index')->name('mailableList');
        Route::get('view/{name}', 'MailablesController@viewMailable')->name('viewMailable');
        Route::get('edit/template/{name}', 'MailablesController@editMailable')->name('editMailable');
        Route::post('parse/template', 'MailablesController@parseTemplate')->name('parseTemplate');
        Route::post('preview/template', 'MailablesController@previewMarkdownView')->name('previewMarkdownView');
        Route::get('preview/template/previewerror', 'MailablesController@templatePreviewError')->name('templatePreviewError');
        Route::get('preview/{name}', 'MailablesController@previewMailable')->name('previewMailable');
        Route::get('new', 'MailablesController@createMailable')->name('createMailable');
        Route::post('new', 'MailablesController@generateMailable')->name('generateMailable');
        Route::post('delete', 'MailablesController@delete')->name('deleteMailable');

    });

    Route::group(['prefix' => 'template-builder/templates'], function () {


        Route::post('savetemp', 'TemplatesController@saveTemplates')->name('saveTemplates');

        Route::get('/', 'TemplatesController@index')->name('templateList');
        Route::get('getTemplates', 'TemplatesController@getTemplates')->name('getTemplates');
        
        Route::get('createTemplate/{id}', 'TemplatesController@createTemplate')->name('createTemplate');
        Route::get('viewTemplate/{id}', 'TemplatesController@viewTemplate')->name('view.Template');

        Route::post('updateTemp', 'TemplatesController@updateTemp')->name('updateTemp');
        Route::post('deleteTemp', 'TemplatesController@deleteTemp')->name('deleteTemp');


        Route::get('new', 'TemplatesController@select')->name('selectNewTemplate');
        Route::get('new/{type}/{name}/{skeleton}', 'TemplatesController@new')->name('newTemplate');
        Route::get('edit/{templatename}', 'TemplatesController@view')->name('viewTemplate');
        Route::post('new', 'TemplatesController@create')->name('createNewTemplate');
        Route::post('delete', 'TemplatesController@delete')->name('deleteTemplate');
        Route::post('update', 'TemplatesController@update')->name('updateTemplate');
        Route::post('preview', 'TemplatesController@previewTemplateMarkdownView')->name('previewTemplateMarkdownView');
        Route::post('saveCustomTemplate','TemplatesController@saveCustomTemplate')->name('saveCustomTemplate');
        Route::post('getTemplate','TemplatesController@getTemplate')->name('getTemplate');
        Route::post('updateCustomTemplate','TemplatesController@updateCustomTemplate')->name('updateCustomTemplate');
        Route::post('deleteCustomTemplate','TemplatesController@deleteCustomTemplate')->name('deleteCustomTemplate');

    });

});

Route::group ( ['namespace' => 'CustomerPanel','middleware' => ['auth']], function () {

    Route::get('/myprofile','HomeController@profile')->name('customer.myProfile');
    Route::post('/save_profile_img','HomeController@saveProfileImage')->name('customer.saveProfileImage');
    Route::get('/submitTicket','HomeController@addTicketPage')->name('customer.addTicket');
    Route::get('/viewTicketList','HomeController@viewTicketPage')->name('customer.tickets');
    Route::get('/customer-ticket-details/{id}','HomeController@get_tkt_details')->name('customer.tkt_dtl');
    Route::post('change_theme_mode','HomeController@change_theme_mode');
    Route::post('save_company','HomeController@saveCompany')->name('customer.saveCompany');
    Route::post('update_customer','HomeController@update_customer_profile')->name('customer.updateCustomer');
    Route::get('/customer-tickets','HomeController@getCustomerTickets')->name('customer.getCustomerTickets');

    // save ticket
    Route::post('/save-tkt','HomeController@saveTicket')->name('customer.saveTicket');
    Route::post('/update-tkt','HomeController@cstUpdateTicket')->name('customer.cstUpdateTicket');
    
    Route::post('/get-tkt-replies','HomeController@getTktReplies')->name('customer.getTktReplies');

    Route::post('/save_tkt_attachments','HomeController@saveTicketAttachments')->name('customer.saveTicketAttachments');
    Route::post('/save_tkt_reply','HomeController@saveTicketReply')->name('customer.saveTicketReply');
});

Route::group ( ['namespace' => 'CustomerManager','middleware' => ['auth','admin']], function () {

    Route::get('/customer-lookup','CustomerlookupController@customer_lookup')->name('customer.lookup');
    Route::get('/customer-sync','CustomerlookupController@syncCustomers');
    Route::get('/integrations-sync','CustomerlookupController@syncIntegrations');
    Route::get('/get-all-customers','CustomerlookupController@customersList');
    Route::post('/delete_customers','CustomerlookupController@deleteCustomers');
    Route::post('/editOrDelete','CustomerlookupController@editOrDelete');
    // Route::post('/add_customer','CustomerlookupController@add_customer');
    Route::post('/save_customer','CustomerlookupController@save_customer');
    Route::post('/save-cust-card','CustomerlookupController@save_cust_card');
    Route::get('/get-customer-card','CustomerlookupController@get_customer_card');
    Route::get('/customer-profile/{id}/{type?}','CustomerlookupController@customer_profile');
    
    // Route::get('/myprofile/{slug}/{type?}','CustomerlookupController@test');

    Route::get('/checkout/{customerId}/{orderId}','CustomerlookupController@checkout');
    Route::post('/update-customer','CustomerlookupController@update_customer');
    Route::post('/update_customer_profile','CustomerlookupController@update_customer_profile');
    Route::post('/update-user','CustomerlookupController@update_user'); 
    Route::post('/search-customer','CustomerlookupController@search_customer');
    Route::get('/company-lookup','CompanyController@index')->name('company.lookup');
    Route::get('/company-get-staffs/{id}','CompanyController@get_staffs');
    Route::get('/get_company_lookup','CompanyController@get_company_lookup');
    Route::post('/upload_customer_img','CustomerlookupController@uploadCustomerImage');
    Route::get('/customer-stats','CustomerStatsController@index')->name('customer.stats');
    Route::get('/service-stats','CustomerlookupController@service_stats')->name('service.stats');
    Route::Post('/save-company','CompanyController@save_company')->name('admin.saveCompany');

    Route::get('/company-staff','CompanyController@showCompanyStaff');

    Route::get('/testcompany','CompanyController@testcompany');

    Route::Post('/company_delete','CompanyController@deleteCompany');

    Route::post('/save-company-staff','CompanyController@saveCompanyStaff');

    Route::get('/company-profile/{id}','CompanyController@company_profile');
    Route::post('/upload_company_img','CompanyController@uploadCompanyImage');

    Route::post('/save_company_sla','CompanyController@saveCompanySLA');

    Route::get('/get-company-log','CompanyController@getLog');
    Route::post('/update-company','CompanyController@update_company');
    Route::post('/update_company_profile','CompanyController@update_company_profile');
    Route::get('/company-get-staffs/{id}','CompanyController@get_staffs');
    Route::post('/company-add-staff/{id}','CompanyController@add_staff');
    Route::get('/company-remove-staff/{company}/{id}','CompanyController@remove_staff');
    // get customer orders
    Route::get("/get_customer_order/{id}","CustomerlookupController@getAllCustomerOrders");
    Route::get("/get_customer_order_items/{id}","CustomerlookupController@getCustomerOrderItems");
    Route::get('paypal/ec-checkout/{id}', 'CustomerlookupController@getExpressCheckout');
    Route::get('paypal/ec-checkout-success', 'CustomerlookupController@getExpressCheckoutSuccess');
    Route::get("/creditCardPyment/{orderId}/{payment_token}","CustomerlookupController@creditCardPyment");

    Route::Post('/domain_search','CustomerlookupController@searchDomain');

});

Route::group ( ['namespace' => 'Marketing','middleware' => ['auth','admin']], function () {

    Route::get('/contact-manager','MarketingController@contact_manager')->name('contact_manager.index');
    Route::get('/get-contacts','MarketingController@get_contacts');
    Route::post('/contact','MarketingController@contact');
    Route::post('/delete_contact','MarketingController@delete_contact');
    Route::get('/product-manager','MarketingController@product_manager')->name('product_manager.index');
    Route::get('/digital-goods/{id}','MarketingController@digital_goods');
    Route::get('/hard-goods','MarketingController@hard_goods');
    Route::Post('/add-product','MarketingController@addProduct');
    Route::get('/get-products','MarketingController@getProducts');
    Route::POST('/del-product','MarketingController@delProducts');
    Route::get('/edit-product/{id}','MarketingController@edit_goods');


    Route::get('/product-template/{id}','MarketingController@product_template');


    Route::Post('/save_tag','MarketingController@save_tag');
    Route::get('/get-tags','MarketingController@get_Tags');

});

Route::Get('/department-details/{id}','DepartmentsController@details');
Route::Post('/set-dept-permission','DepartmentsController@set_permissions');
Route::Post('/set-dept-assignment','DepartmentsController@set_assignments');

Route::Post('/save-ticket-follow-up','HelpdeskController@save_ticket_follow_up');
Route::Post('/update-ticket-follow-up','HelpdeskController@update_ticket_follow_up');

Route::get('/update_ticket_followup','HelpdeskController@updateFollowupCron');

Route::post('/ticket_refresh','HelpdeskController@ticketRefreshTime')->name('ticketRefreshTime');

Route::Post('/fetch-followups','HelpdeskController@fetch_followups');
Route::Post('/search-ticket','HelpdeskController@search_ticket');
Route::Post('/save-ticket-note','HelpdeskController@save_ticket_note');
Route::Post('/update-ticket-customer','HelpdeskController@update_ticket_customer');
Route::Post('/del-ticket-note','HelpdeskController@del_ticket_note');
Route::get('/system-info','AboutController@System_info')->name('system_info.index');
Route::get('/feature-suggestions','AboutController@feature_suggestions')->name('feature_suggestions.index');
Route::get('/ticket-manager/{type?}','HelpdeskController@ticket_management')->name('ticket_management.index');
Route::get('/ticket-manager/{dept?}/{sts?}','HelpdeskController@ticket_manager')->name('ticket-manager.index');

// get ticket replies
Route::get('/ticket-replies/{id}','HelpdeskController@getTicketReplies')->name('getTicketReplies');

// ticket general
Route::post('/ticket-general-info','HelpdeskController@saveTicketGeneralInfo')->name('saveGeneralInfo');


// Route::get('/add-ticket/{id?}','HelpdeskController@addTicketPage');
Route::get('/add-ticket/{id?}','HelpdeskController@addTicketPage')->name('addTicketPage');

Route::get('/ticket-details','HelpdeskController@ticket_details');
Route::Post('/save-tickets','HelpdeskController@save_tickets');

Route::Post('/upload_attachments','HelpdeskController@upload_attachments');
Route::Post('/delete_attachment','HelpdeskController@delete_attachment');

Route::Post('/get_department_status','HelpdeskController@getDepartmentStatus');

Route::get('/invoices','HelpdeskController@invoices');

Route::post('/move_to_trash_tkt','HelpdeskController@move_to_trash_tkt');
Route::post('/del_tkt','HelpdeskController@del_tkt');
Route::post('/recycle_tickets','HelpdeskController@recycle_tickets');
Route::post('/flag_ticket','HelpdeskController@flag_ticket');
Route::get('/get_ticket_log/{id?}','HelpdeskController@get_ticket_log');
Route::get('/get-ticket-follow-up/{tkt_id}','HelpdeskController@get_ticket_follow_up');
Route::post('/del-ticket-follow-up/{tkt_id}','HelpdeskController@del_ticket_follow_up');
Route::get('/get-ticket-notes','HelpdeskController@getTicketNotes');
Route::get('/task-scripts','TaskScriptsController@task_scripts')->name('task_scripts.index');
Route::post('/save-task-scripts','TaskScriptsController@_save');
Route::post('/del-task-scripts','TaskScriptsController@_delete');


Route::get('/asset-manager','HelpdeskController@asset_manager')->name('asset_manager.index');

Route::post('/list-states','HelpdeskController@listStates');
Route::get('/get_all_statescountries','HelpdeskController@StatesAndCountries');

Route::get('/get_fields/{id}','HelpdeskController@getFields');
Route::get('/asset-template', 'HelpdeskController@asset_template');
Route::get('/detail-asset-template', 'HelpdeskController@detail_asset_template');
Route::get('/field-set', 'HelpdeskController@field_set');
Route::get('/asset_template_manager','HelpDesk\AssetManagerController@index')->name('asset_template_manager');
Route::get('/asset-manager','HelpDesk\AssetManagerController@asset_manager')->name('asset_manager.index');

Route::post('/update_asset_manager','HelpDesk\AssetManagerController@editAssetManager');

Route::get('/get-all-templates','HelpDesk\AssetManagerController@getAllTemplates');

Route::post('/save-asset-template','HelpDesk\AssetManagerController@save_form');
Route::Post('/publish-ticket-reply','HelpdeskController@save_ticket_reply');
Route::post('/delete-ticket-reply','HelpdeskController@delete_ticket_reply');

Route::get('/general-info/{id}', 'HelpDesk\AssetManagerController@gen_info')->name("general.index");

Route::get('/get-tickets/{status?}/{id?}','HelpdeskController@getTickets');
Route::get('/get-filtered-tickets/{dept?}/{sts?}','HelpdeskController@getFilteredTickets');


Route::get('/ticket-details/{id}','HelpdeskController@get_details');
Route::post('/update_ticket','HelpdeskController@update_ticket');

Route::post('/get_flag_tickets','HelpdeskController@get_flag_tickets')->name('admin.flagTickets');

Route::post('/update_selected_ticket','HelpdeskController@update_selected_ticket')->name("admin.updateTkt");


Route::post('/merge_tickets','HelpdeskController@mergeTickets');

Route::post('/searchEmails','HelpdeskController@searchEmails');

Route::post('/ticket_notification','HelpdeskController@ticket_notification');
Route::post('/send_notification','HelpdeskController@send_notification');

Route::post('/set-sla-plan','HelpdeskController@set_sla_plan');
Route::post('/update-ticket-deadlines','HelpdeskController@updateTicketDeadlines');

// Asset methods

Route::get('/asset-manager','HelpDesk\AssetManagerController@asset_manager')->name('asset_manager.index');
Route::get('/asset-template', 'HelpDesk\AssetManagerController@asset_template');
Route::get('/detail-asset-template', 'HelpDesk\AssetManagerController@detail_asset_template');
Route::get('/field-set', 'HelpDesk\AssetManagerController@field_set');
Route::get('/asset_template_manager','HelpDesk\AssetManagerController@index')->name('asset_template_manager');

// Route::get('/get-asset-categories','HelpDesk\AssetManagerController@getAssetcategory');
// Route::post('/save-asset-category','HelpDesk\AssetManagerController@save_asset_category');

Route::get('/get-assets','HelpDesk\AssetManagerController@getAssets');
Route::post('/show-single-assets','HelpDesk\AssetManagerController@showAssets');
Route::post('/update-assets','HelpDesk\AssetManagerController@updateAssets');

Route::get('/get-asset-details/{id}','HelpDesk\AssetManagerController@getAssetDetails');
Route::post('/save-asset','HelpDesk\AssetManagerController@save_asset');
Route::post('/delete-asset','HelpDesk\AssetManagerController@delete_asset');
Route::get('/get-asset-templates','HelpDesk\AssetManagerController@get_templates');
Route::post('/get-asset-templates-by-id','HelpDesk\AssetManagerController@get_templates_by_id');
Route::post('/save-asset-template','HelpDesk\AssetManagerController@save_form');

/*Permission ROute */
Route::get('/permissions','PermissionController@index');


Route::view('/dashboard','dashboard');
Route::view('/change-log','change_log');
Route::view('/cfo-dashboard','cfo_dashboard');
Route::view('/short-codes ','short_codes');
Route::get('/watch','DispatchController@watch')->name('watch.index');
Route::get('/coming-sOon','DispatchController@coming_soon');



Route::get('/coming-soon','DispatchController@coming_soon1');
Route::get('/subscriptions-sync','SubscriptionsController@syncSubscriptions');
Route::get('/orders-sync','OrdersController@syncOrders');
//>>>>>>> e0fb9b63fdfd12714214a28014ccac3494e9ed07
Route::post('save-watch','DispatchController@save_watch');