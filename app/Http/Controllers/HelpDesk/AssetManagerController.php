<?php

namespace App\Http\Controllers\HelpDesk;

use App\Exports\AssetFieldsExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivitylogController;
use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Models\Assets;
use App\Models\AssetForms;
use App\Models\AssetFields;

use App\Models\Customer;
use App\Models\Company;

use App\Models\Tickets;
use App\Models\Activitylog;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

use Carbon\Carbon;

use App\Models\Mail;
use App\Http\Controllers\SystemManager\MailController;
use App\Imports\AssetFieldsImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class AssetManagerController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index(){
        return view('help_desk.asset_template_manager.index');
    }

    public function asset_manager(){

        $customers = customer::all();
        $companies = Company::with('staff_members')->get();

        return view('help_desk.asset_manager.index-new',compact('customers','companies'));
    }

    public function assetExport(Request $request)
    {

        $assetFields = AssetFields::where('asset_forms_id',$request->asset_type)->get();


        if($request->has(['customer','company']) && $request->customer != 0 && $request->company != 0){
            $asst = Assets::where('customer_id',$request->customer)->where('company_id',$request->company)->get()->pluck('id')->toArray();
        }else if($request->has('customer') && $request->customer != 0){
            $asst = Assets::where('customer_id',$request->customer)->get()->pluck('id')->toArray();
        }else if($request->has('company') && $request->company != 0){
            $asst = Assets::where('company_id',$request->company)->get()->pluck('id')->toArray();
        }


        if(isset($asst)){
            $record = DB::table("asset_records_".$request->asset_type)->whereIn('asset_id',$asst);
        }else{
            $record = DB::table("asset_records_".$request->asset_type);
        }


        $asset_record = DB::table("asset_records_".$request->asset_type);

        $data = [];
        $headings = [];

        foreach($assetFields as $i => $fl){
            foreach($record->get() as $j => $d){
                $data[$j][$fl->label] = $record->select('fl_'.$fl->id)->get()[$j]->{'fl_'.$fl->id} ?? '';
            }
            $headings[] = $fl->label;
        }


        foreach($assetFields as $i => $fl){
            foreach($record->get() as $j => $d){
                if($request->has(['customer','company']) && $request->customer != 0 && $request->company != 0){
                    $data[$j]['Customer'] = Customer::find($request->customer) != null ? Customer::find($request->customer)->email : '';
                    $data[$j]['Company'] = Company::find($request->company) != null ? Company::find($request->company)->name : '';
                }else if($request->has('customer') && $request->customer != 0){
                    $data[$j]['Customer'] = Customer::find($request->customer) != null ? Customer::find($request->customer)->email : '';
                }else if($request->has('company') && $request->company != 0){
                    $data[$j]['Company'] = Company::find($request->company) != null ? Company::find($request->company)->name : '';
                }else{
                    $customer = Assets::find($asset_record->get()[$j]->asset_id ) != null ? Assets::find($asset_record->get()[$j]->asset_id )->customer : '';
                    $company = Assets::find($asset_record->get()[$j]->asset_id ) != null ? Assets::find($asset_record->get()[$j]->asset_id )->company : '';
                    $data[$j]['Customer'] = $customer->email ?? '';
                    $data[$j]['Company'] = $company->name ?? '';
                }
            }
        }
        $headings[] = 'Customer';
        $headings[] = 'Company';

        $assetForm = AssetForms::find($request->asset_type);


        $ext = ($assetForm->title ?? 'untitled').'-'. $assetForm->id .'.csv';

        if($request->type == 'sample'){
            return Excel::download(new AssetFieldsExport($headings,[]), ($assetForm->title ?? 'untitled').' - Sample.csv');
        }else{
            return Excel::download(new AssetFieldsExport($headings,$data), $ext);
        }
    }
    public function assetImport(Request $request)
    {

        try{
            Excel::import(new AssetFieldsImport($request->asset_type), $request->file('file'));
            $response['success'] = true;
            $response['message'] = "Asset Imported Successfully";
            $response['status'] = 200;

        }catch(Exception $e){
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            $response['status'] = 500;
        }

        return response($response);

    }

    public function asset_template(){
        return view('help_desk.asset_manager.asset_template');
    }

    public function detail_asset_template(){
        return view('help_desk.asset_manager.detail_asset_template');
    }

    public function field_set(){
        return view('help_desk.asset_manager.field_set');
    }

    public function get_templates() {
        try{
            $templates = AssetForms::with('fields')->where('is_deleted', 0)->get();

            $response['success'] = true;
            $response['templates'] = $templates;
            return response()->json($response);
        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function get_templates_by_id(Request $request) {
        try{
            $templates = AssetForms::with('fields')->where('id',$request->id)->where('is_deleted', 0)->first();

            $response['success'] = true;
            $response['templates'] = $templates;
            return response()->json($response);
        }catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    public function save_form(Request $request) {
        // return \json_decode($request->fields);
        try{
            $assetForm = AssetForms::where('title',$request->title)->where('is_deleted',0)->first();

            if($assetForm) {
                $response['message'] = "Template Already define";
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }else{

                $form = AssetForms::create([
                    'title' => $request->title,
                    'created_by' => \Auth::user()->id
                ]);

                $fields = \json_decode($request->fields, true);
                $fieldsAdded = [];
                foreach ($fields as $key => $value) {
                    if(array_key_exists('options', $value)) {
                        $value['options'] = $value['options'] = implode('|',$value['options']);
                    }
                    $value['asset_forms_id'] = $form->id;
                    $value['created_by'] = \Auth::user()->id;
                    $fieldsAdded[] = AssetFields::create($value);
                }

                $table_name = 'asset_records_'.$form->id;
                Schema::create($table_name, function(Blueprint $table) use ($fieldsAdded, $table_name) {
                    $table->engine = 'InnoDB';

                    $table->increments('id');
                    $table->integer('form_id');
                    $table->integer('asset_id');
                    if (count($fieldsAdded) > 0) {
                        foreach ($fieldsAdded as $field) {
                            $table->string('fl_'.$field->id)->nullable();
                        }
                    }
                    $table->timestamps();
                    $table->timestamp('deleted_at')->nullable();
                    $table->integer('created_by');
                    $table->integer('updated_by')->nullable();
                    $table->integer('deleted_by')->nullable();
                    $table->tinyInteger('is_deleted')->default(0);
                    $table->foreign('form_id')->references('id')->on('asset_templates_form');
                    $table->foreign('asset_id')->references('id')->on('assets');
                });

                $response['message'] = 'Template Saved Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                return response()->json($response);
            }
        }catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    // public function save_asset_category(Request $request){

    //     $data = $request->all();
    //     $response = array();

    //     try{
    //         $data['created_by'] = \Auth::user()->id;

    //         $save_category = Assetcategory::create($data);
    //         $response['message'] = 'Category Added Successfully!';
    //         $response['status_code'] = 200;
    //         $response['success'] = true;
    //         $response['id'] = $save_category->id;

    //         return response()->json($response);
    //     }catch(Exception $e){
    //         $response['message'] = 'Something Went wrong!';
    //         $response['status_code'] = 500;
    //         $response['success'] = false;
    //         return response()->json($response);
    //     }
    // }

    /*
        @params
            form_id => integer,
            asset_id => integer,
            fl_1 => string,
            fl_2 => string
            ...

            form fields design will be 'fl_' string appended with form fields id like fl_1 and fl_2 are fields names and 1 and 2 are field ids in fields table
    */

    public function save_asset(Request $request) {
        try {
            $data = $request->all();

            $asset = [
                'asset_forms_id' => $data['form_id'],
                'created_by' => auth()->id(),
            ];

            $module = '';
            $ref = '';

            if(!empty($data['customer_id']) && $data['customer_id'] != 'null') {
                $asset['customer_id'] = $data['customer_id'];
                $module = 'Customer Asset';
                $ref = 'customer_asset_created';
            }
            if(!empty($data['company_id']) && $data['company_id'] != 'null') {
                $asset['company_id'] = $data['company_id'];
                $module = 'Company Asset';
                $ref = 'company_asset_created';
            }
            if(!empty($data['project_id'])) {
                $asset['project_id'] = $data['project_id'];
                $module = 'Project Asset';
                $ref = 'project_asset_created';
            }
            if(!empty($data['ticket_id'])) {
                $asset['ticket_id'] = $data['ticket_id'];

                $module = 'Ticket Asset';
                $ref = 'ticket_asset_created';

                $ticket = Tickets::findOrFail($asset['ticket_id']);

                $sendingMailServer = Mail::where('mail_dept_id', $ticket->dept_id)->first();

                if(empty($sendingMailServer)) {
                    throw new Exception('Ticket department email not found!');
                }

                MailController::$mailserver_hostname  =  $sendingMailServer->mailserver_hostname;
                MailController::$mailserver_username  =  $sendingMailServer->mailserver_username;
                MailController::$mailserver_password  =  $sendingMailServer->mailserver_password;
            }

            if(!empty($data['asset_title'])) {
                $asset['asset_title'] = $data['asset_title'];
            }

            if(isset($request->tkt_customer_id)) {
                $asset['customer_id'] = $request->tkt_customer_id;
            }
            if(isset($request->tkt_company_id) ) {
                $asset['company_id'] = $request->tkt_company_id;
            }
            $assetRes = Assets::create($asset);
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = $module . ' # '. $assetRes->id .' Created By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs($module , $ref , $assetRes->id , auth()->id() , $action_perform);

            if(!empty($assetRes->ticket_id)) {
                $ticket = Tickets::findOrFail($assetRes->ticket_id);
                $ticket->updated_at = Carbon::now();
                $ticket->updated_by = auth()->id() ;
                $ticket->save();

                $action_perform = 'Ticket (<a href="ticket-details/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) asset added By '. $name_link;
                $log = new ActivitylogController();
                $log->saveActivityLogs('Tickets' , 'tickets' ,  $ticket->ticket_id , auth()->id() , $action_perform);
            }

            unset($data['customer_id']);
            unset($data['company_id']);
            unset($data['project_id']);
            unset($data['ticket_id']);
            unset($data['asset_title']);

            $fields = AssetFields::where('asset_forms_id', $data['form_id'])->get();


            $data['asset_id'] = $assetRes->id;
            $data['created_by'] = auth()->id();


            $check = Schema::hasTable('asset_records_' . $data['form_id']);

            if($check) {

                DB::table('asset_records_'.$data['form_id'])->insert($data);

            }else{

                $fieldsAdded = [];
                foreach ($fields as $key => $value) {
                    if(array_key_exists('options', $value)) {
                        $value['options'] = $value['options'] = implode('|',$value['options']);
                    }
                    $value['asset_forms_id'] = $data['form_id'];
                    $value['created_by'] = auth()->id();
                    $fieldsAdded[] = AssetFields::create($value);
                }

                $table_name = 'asset_records_'.$data['form_id'];
                Schema::create($table_name, function(Blueprint $table) use ($fieldsAdded, $table_name) {
                    $table->engine = 'InnoDB';

                    $table->increments('id');
                    $table->integer('form_id');
                    $table->integer('asset_id');
                    if (count($fieldsAdded) > 0) {
                        foreach ($fieldsAdded as $field) {
                            $table->string('fl_'.$field->id)->nullable();
                        }
                    }
                    $table->timestamps();
                    $table->timestamp('deleted_at')->nullable();
                    $table->integer('created_by');
                    $table->integer('updated_by')->nullable();
                    $table->integer('deleted_by')->nullable();
                    $table->tinyInteger('is_deleted')->default(0);
                    $table->foreign('form_id')->references('id')->on('asset_templates_form');
                    $table->foreign('asset_id')->references('id')->on('assets');
                });


                DB::table('asset_records_'.$data['form_id'])->insert($data);

            }


            $response['message'] = 'Asset Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        } catch(\Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function update_form(Request $request) {
        try {


            if($request->has('title') && $request->has('fields')){
               $asstForm =  AssetForms::find($request->template_id);
               if($asstForm->where('title','!=',$request->title)->get())
                {
                    $asstForm->title = $request->title;
                    $asstForm->save();
                }else{
                    $asstForm = $asstForm->first();
                }

                $fields = \json_decode($request->fields, true);

                $fieldsAdded = [];
                foreach ($fields as $key => $value) {
                    if($value != null && !isset($value['id'])){
                        if(array_key_exists('options', $value)) {
                            $value['options'] = $value['options'] = implode('|',$value['options']);
                        }
                        $value['asset_forms_id'] = $asstForm->id;
                        $value['created_by'] = Auth::user()->id;
                        $flds = AssetFields::create($value);
                        $fieldsAdded[] = $flds->id;
                    }
                }

                foreach($fieldsAdded as $field){
                    $column_name = 'fl_'.$field;
                    $table_name = 'asset_records_'.$asstForm->id;
                    DB::statement("ALTER TABLE $table_name ADD COLUMN $column_name VARCHAR(200) Null AFTER asset_id; ");
                }


                $response['message'] = 'Asset Type Updated Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['title'] = $asstForm->title;
                $response['form_field'] = AssetFields::whereIn('id',$fieldsAdded)->get();
                $response['data'] = AssetFields::where('asset_forms_id', $request->template_id)->where('is_deleted',0)->get();
                return response()->json($response);
            }


            if($request->has('form_field')){
                AssetFields::find($request->field_id)->update([
                    'is_deleted' => 1,
                ]);

                $response['message'] = 'Form Field Removed Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['data'] = AssetFields::where('asset_forms_id', $request->template_id)->get();
                return response()->json($response);
            }else{
                if($request->has('field_id') && $request->field_id == 0){

                    $table_name = 'asset_records_'.$request->template_id;

                    $fieldsAdded =  AssetFields::create([
                                        'label' => $request->label,
                                        'placeholder' => $request->placeholder,
                                        'asset_forms_id' => $request->template_id,
                                        'description' => $request->desc,
                                        'required' => $request->required,
                                        'is_multi' => $request->is_multi,
                                        'copy_icon' => $request->copy_icon,
                                        'type' => $request->code,
                                        'created_by' => Auth::id(),
                                    ]);

                    $column_name = 'fl_'.$fieldsAdded->id;
                    DB::statement("ALTER TABLE $table_name ADD COLUMN $column_name VARCHAR(200) Null AFTER asset_id; ");


                }else{

                     AssetFields::find($request->field_id)->update([
                        'label' => $request->label,
                        'placeholder' => $request->placeholder,
                        'description' => $request->desc,
                        'required' => $request->required,
                        'is_multi' => $request->is_multi,
                        'copy_icon' => $request->copy_icon,
                    ]);

                    $fieldsAdded = AssetFields::find($request->field_id);
                }


                $response['message'] = 'Asset Type Update Successfully!';
                $response['status_code'] = 200;
                $response['success'] = true;
                $response['title'] = AssetForms::find($fieldsAdded->asset_forms_id)->title ?? '';
                $response['form_field'] = AssetFields::find($fieldsAdded->id);
                $response['data'] = AssetFields::where('asset_forms_id', $request->template_id)->where('is_deleted',0)->get();
                return response()->json($response);
            }

        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }

    public function delete_asset(Request $request){
        $data = $request->all();
        $response = array();

        $asset = Assets::findOrFail($data['id']);

        $asset->deleted_by = \Auth::user()->id;
        $asset->deleted_at = Carbon::now();
        $asset->is_deleted = 1;

        if($asset->save()){
            $asst_record = DB::table('asset_records_'.$asset->asset_forms_id)->where('asset_id',$asset->id);
            if($asst_record->get()->count() != 0){
                $asst_record->update(['is_deleted' => 1]);
            }
        }
        $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
        if(!empty($asset->ticket_id)) {
            $ticket = Tickets::findOrFail($asset->ticket_id);
            $ticket->updated_at = Carbon::now();
            $ticket->updated_by = \Auth::user()->id;
            $ticket->save();

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Ticket (<a href="ticket-details/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) asset deleted By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Tickets' , 'tickets' , $ticket->ticket_id , auth()->id() , $action_perform);
        }

        $action_perform = ' Asset # '.$data['id'].' Deleted By '. $name_link;
        $log = new ActivitylogController();
        $log->saveActivityLogs('Asset Deleted' , 'asset_templates_form' , $data['id'] , auth()->id() , $action_perform);

        $response['message'] = 'Asset Delete Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }

    public function getAssets(Request $request) {
        $query = Assets::query();

        if($request->has('customer_id')) {
            $cid = $request->customer_id;
            $query->when(!empty($cid), function($q) use($cid) {
                return $q->where('customer_id', $cid);
            });
        }

        if($request->has('company_id')) {
            $comp_id = $request->company_id;
            $query->when(!empty($comp_id), function($q) use($comp_id) {
                return $q->where('company_id', $comp_id);
            });
        }

        if($request->has('project_id')) {
            $p_id = $request->project_id;
            $query->when(!empty($p_id), function($q) use($p_id) {
                return $q->where('project_id', $p_id);
            });
        }

        // if($request->has('ticket_id')) {
        //     $t_id = $request->ticket_id;
        //     $query->when(!empty($t_id), function($q) use($t_id) {
        //         return $q->where('ticket_id', $t_id);
        //     });
        // }

        $assets = $query->where('is_deleted', 0)->with(['template','asset_fields','customer','company'])->get();

        foreach($assets as $asset) {
            $asset->asset_record = DB::table("asset_records_".$asset->asset_forms_id)->where("asset_id",$asset->id)->first();
            $asset->created_by_name = User::find($asset->created_by) != null ? User::find($asset->created_by)->name : '';
            $asset->updated_by_name = User::find($asset->updated_by) != null ? User::find($asset->updated_by)->name : '';
        }

        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['assets']= $assets;

        return response()->json($response);
    }

    public function showAssets(Request $request) {

        $asset =  Assets::find($request->id);
        $AssetForm =  AssetForms::find($asset->asset_forms_id);
        $AssetFields = AssetFields::where("asset_forms_id","=",$asset->asset_forms_id)->where('is_deleted',0)->get();
        $asset_record = DB::table("asset_records_".$asset->asset_forms_id)->where("asset_id",$request->id)->where('is_deleted',0)->first();

        $response['message'] = 'Asset List';
        $response['status_code'] = 200;
        $response['asset'] = $asset;
        $response['AssetForm'] = $AssetForm;
        $response['AssetFields'] = $AssetFields;
        $response['asset_record'] = $asset_record;
        $response['success'] = true;
        return response()->json($response);

    }

    public function getAssetDetails($id) {
        try {
            $asset = Assets::findOrFail($id);

            $details = AssetForms::with('fields')->where('id', $asset->asset_forms_id)->first();

            $details['records'] = DB::table('asset_records_'.$asset->asset_forms_id)->where('asset_id', $id)->get();

            $response['message'] = 'Success';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['details']= $details;

            return response()->json($response);
        } catch(Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;

            return response()->json($response);
        }
    }

    // Send Ticket mails to users.
    private function sendNotificationMail($ticket, $template_code){
        try{
            $user = DB::table('users')->where('id', \Auth::user()->id)->first();

            $tech = null;
            if(!empty($ticket['assigned_to'])) {
                $tech = User::where('id', $ticket['assigned_to'])->first();
            }

            $customer = null;
            if(!empty($ticket['customer_id'])) {
                $customer = Customer::where('id', $ticket['customer_id'])->first();
            }

            $mail_template = DB::table('templates')->where('code', $template_code)->first();

            if(empty($mail_template)) {
                throw new Exception('Template not found');
            }

            $template_input = array(
                array('module' => 'User', 'values' => $user),
                array('module' => 'Creator', 'values' => $user),
                array('module' => 'Tech', 'values' => (!empty($tech)) ? $tech->attributesToArray() : []),
                array('module' => 'Customer', 'values' => (!empty($customer)) ? $customer->attributesToArray() : []),
                array('module' => 'Ticket', 'values' => (!empty($ticket)) ? $ticket : []),
            );

            $mailer = new MailController();

            $message = $mailer->template_parser($template_input, $mail_template->template_html);

            // echo $message;

            if(!empty($message)) {
                $subject = $ticket['subject'];
                // $header = "From:web_dev2@mylive-tech.com \r\n";
                // // $header .= "Cc:no-reply@mylive-tech.com \r\n";
                // $header .= "MIME-Version: 1.0\r\n";
                // $header .= "Content-type: text/html\r\n";

                if(!empty($tech)) {
                    $mailer->sendMail($subject, $message, 'web_dev2@mylive-tech.com', $tech->email, $tech->name);
                    // $retval = mail ($tech->email, $subject, $message, $header);
                }

                if(!empty($customer)) $mailer->sendMail($subject, $message, 'web_dev2@mylive-tech.com', $customer->email, $customer->first_name.' '.$customer->last_name);
            }
        }catch(Exception $e){
            throw new Exception($e);
        }
    }


    public function getAllTemplates() {
        $data = AssetForms::with('fields')->where("is_deleted",0)->get();
        $response['message'] = 'Success';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data']= $data;
        return response()->json($response);
    }

    public function deleteTemplates() {
        $data = AssetForms::find(request()->id);
        if(!empty($data)) {
            $data->is_deleted = 1;
            $data->save();
            return response()->json([ "messages" => 'Asset Template Deleted',  "status" => 200 , "success" => true ]);
        }else{
            return response()->json([ "messages" => 'Asset not found.. Something went wrong',  "status" => 500 , "success" => false ]);
        }
    }

    public function editAssetManager(Request $request) {

        if($request->address == 'address') {

            for($i = 0; $i < sizeof($request->field_id); $i++) {

                DB::table("asset_records_".$request->asset_forms_id)->where("asset_id","=",$request->asset_id)->update([
                    "fl_" . $request->field_id[$i] => $request->data[$i],
                ]);
            }

            // DB::table("assets")->where("asset_forms_id","=",$request->asset_forms_id)->update([
            //     "asset_title" => $request->asset_title,
            // ]);

            $asset = DB::table("assets")->where("asset_forms_id","=",$request->asset_forms_id)->first();

            $asset->asset_title = $request->asset_title;
            $asset->updated_at = Carbon::now();
            $asset->updated_by = \Auth::user()->id;
            $asset->save();

            if(!empty($asset->ticket_id)) {
                $ticket = Tickets::findOrFail($asset->ticket_id);
                $ticket->updated_at = Carbon::now();
                $ticket->updated_by = \Auth::user()->id;
                $ticket->save();

                $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
                $action_perform = 'Ticket (<a href="ticket-details/'.$ticket->coustom_id.'">'.$ticket->coustom_id.'</a>) asset updated By '. $name_link;
                $log = new ActivitylogController();
                $log->saveActivityLogs('Tickets' , 'ticket_replies' , $asset->ticket_id , auth()->id() , $action_perform);
            }

            $response['message'] = 'Record Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);

        }else{

            for($i = 0; $i < $request->template; $i++) {
                DB::table("asset_records_".$request->asset_forms_id)->where("asset_id","=",$request->asset_id)->update([
                    $request->data[$i]['keys'] => $request->data[$i]['value'],
                ]);
                DB::table("assets")->where("asset_forms_id","=",$request->asset_forms_id)->update([
                    "asset_title" => $request->asset_title,
                ]);
            }

            $response['message'] = 'Record Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }

    }

    public function gen_info($id){

        $field_data = array();

        //assets table
        $asset = Assets::where('id',$id)->with(['created_by_user','updated_by_user'])->first();

        //asset_templates_fields
        $asset_templates_fields =  DB::table("asset_templates_fields")->where("asset_forms_id","=",$asset->asset_forms_id)->get();

        //asset_templates_form
        $asset_templates_form =  DB::table("asset_templates_form")->where("id","=",$asset->asset_forms_id)->get();

        $records = DB::table("asset_records_". $asset->asset_forms_id)->where("asset_id","=",$id)->get();

        foreach($records as $record) {

            foreach ($asset_templates_fields as $key => $field) {


                array_push($field_data, [$record->{'fl_'.$field->id} ]);

                for($i =0; $i < sizeof($field_data); $i ++) {

                    $field->input_data = $field_data[$i];

                    // dd($field->input_data);

                    if($field->type ==  'address') {

                        $broken = explode('|', $field->input_data[0]);

                        $countries = DB::Table('countries')->where('id',"=",$broken[5])->get()->toArray();
                        $states = DB::Table('states')->where('id','=',$broken[3])->first();

                        $field->country  = $countries;
                        $field->state  = $states;

                    }

                }

            }
        }

        $customer = DB::table("customers")->where("id","=",$asset->customer_id)->first();
        $company = DB::table("companies")->where("id","=",$asset->company_id)->first();

        return view('help_desk.asset_manager.gen_info',compact('asset','asset_templates_form','asset_templates_fields','customer','company'));
    }

    public function updateAssets(Request $request) {
        try {
            $asset =  Assets::find($request->asset_id);
            $asset->asset_title = $request->asset_title;
            $asset->company_id = ($request->asset_company_id != null ? $request->asset_company_id : $asset->company_id);
            $asset->customer_id = ($request->asset_customer_id != null ? $request->asset_customer_id : $asset->customer_id);
            $asset->save();

            for($i = 0 ; $i <sizeof($request->data); $i++ ) {

                DB::table("asset_records_" . $asset->asset_forms_id)
                ->where("asset_id",$request->asset_id)
                ->where("form_id",$asset->asset_forms_id)
                ->update([
                    $request->data[$i]['keys'] => $request->data[$i]['value'],
                ]);

            }

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $module = $request->has('module') ? $request->module : 'Assets';
            $table_ref = $request->has('ref') ? $request->ref : 'Assets';
            $action_perform = ' '. $request->module .' # '. $request->asset_id.' Updated By '. $name_link;

            $log = new ActivitylogController();
            $log->saveActivityLogs( $module , $table_ref , $request->asset_id , auth()->id() , $action_perform);

            $response['message'] = 'Asset Updated Successfully';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
            $response['status_code'] = 500;
            $response['success'] = false;
            return response()->json($response);
        }
    }
}
