<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Company;
use App\Models\SystemSetting;
use App\Models\BrandSettings;
use App\Models\SystemManager\Feature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class InstallationController extends Controller
{
    public function index() {

        $setting = SystemSetting::where('sys_key','is_installed')->first();

        $features = Feature::where('parent_id',0)->get()->toArray();

        if($setting) {

            if($setting->sys_value == 1){
                return redirect('/login');
            }else{
                return view('installation.installation',compact('features'));
            }

        }else{
            return view('installation.installation',compact('features'));
        }

    }


    public function saveInstallation(Request $request) {

        $is_install = SystemSetting::where('sys_key','is_installed')->first();

        if($is_install){

            if($is_install->sys_value == 1) {
                return response()->json([
                    "status" => 302,
                    "success" => false,
                ]);
            }else{
                
                if($request->type == 'dbconfig') {

                    $connection = mysqli_connect( $request->server_name , $request->username , $request->password, $request->dbname);
    
                    if($connection) {
    
                        $setting = SystemSetting::where('sys_key','migration')->first();
    
                        if($setting) {
    
                            if($setting->sys_value == 1){
                                return response()->json([
                                    "status" => 200,
                                    "message" => "Connected Successfull",
                                ]);
                            }else{
                                
                                $migration = Artisan::call('migrate:refresh');
                                if($migration) {
                                    $featureclass= 'FeatureAccessSeeder';
                                    $systemclass= 'SystemSettings';
                                    $ticketclass= 'TicketSettings';
                
                                    Artisan::call("db:seed", ['--class' => $featureclass]);
                                    Artisan::call("db:seed", ['--class' => $systemclass]);
                                    Artisan::call("db:seed", ['--class' => $ticketclass]);
                
                                    SystemSetting::create(["sys_key"=> "migration", "sys_value" => 1]);
                                    return response()->json([
                                        "status" => 200,
                                        "message" => "Connected Successfull",
                                    ]);
                                }
                            }
    
                        }else{
    
                            
                            $migration = Artisan::call('migrate:refresh');
                            if($migration) {
                                $featureclass= 'FeatureAccessSeeder';
                                $systemclass= 'SystemSettings';
                                $ticketclass= 'TicketSettings';
            
                                Artisan::call("db:seed", ['--class' => $featureclass]);
                                Artisan::call("db:seed", ['--class' => $systemclass]);
                                Artisan::call("db:seed", ['--class' => $ticketclass]);
    
                                SystemSetting::create(["sys_key"=> "migration", "sys_value" => 1]);       
            
                                return response()->json([
                                    "status" => 200,
                                    "message" => "Connected Successfull",
                                ]);
                            }
                        }
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Failed to Connect with Database",
                        ]);
    
                    }
    
                } else if($request->type == 'adminUser') {
    
                    $users = User::all();
                    $data = array(
                        "name" => $request->name,
                        "email" => $request->email,
                        "alt_pwd" => Crypt::encryptString($request->password),
                        "password" => bcrypt($request->password),
                        "user_type" =>1,
                    );
    
                    if($users) {
    
                        DB::table("users")->delete();
    
                        $user = User::create($data);
    
                        if($user) {
    
                            return response()->json([
                                "status" => 200,
                                "message" => "Admin Created Successfull",
                            ]);
            
                        }else{
                            return response()->json([
                                "status" => 500,
                                "message" => "Something went wrong!",
                            ]);
            
                        }
                        
                    }else{
    
                        $user = User::create($data);
    
                        if($user) {
    
                            return response()->json([
                                "status" => 200,
                                "message" => "Admin Created Successfull",
                            ]);
            
                        }else{
                            return response()->json([
                                "status" => 500,
                                "message" => "Something went wrong!",
                            ]);
            
                        }
    
                    }
    
                } else if($request->type == 'company') {
    
                    $companies = Company::all();
    
                    $data = array(
                        "name" =>  $request->company_name,
                        "poc_first_name" =>  $request->comp_first_name,
                        "poc_last_name" =>  $request->comp_last_name,
                        "email" =>  $request->company_email,
                        "is_default" => 1,
                    );
    
                    if($companies) {
    
                        DB::table("companies")->delete();
    
                        $company = Company::create($data);
    
                        
                        if($company) {
    
                            return response()->json([
                                "status" => 200,
                                "message" => "Company Created Successfull",
                            ]);
            
                        }else{
                            return response()->json([
                                "status" => 500,
                                "message" => "Something went wrong!",
                            ]);
            
                        }
    
                    }else{
    
                        $company = Company::create($data);
                        if($company) {
    
                            return response()->json([
                                "status" => 200,
                                "message" => "Company Created Successfull",
                            ]);
            
                        }else{
                            return response()->json([
                                "status" => 500,
                                "message" => "Something went wrong!",
                            ]);
            
                        }
    
                    }
    
    
    
                }else if( $request->type == 'module') {

                    for($i = 0; $i < sizeof($request->id); $i++) {
                        Feature::where('f_id',$request->id[$i])->update([
                            "is_active" => 1,
                        ]);
                    }

                    return response()->json([
                        "status" => 200,
                        "message" => "System Module Configure Successfull",
                    ]);

                }else{
    
                    $brandSettings = BrandSettings::all();
    
                    $data = array(
                        "site_title" => $request->system_title,
                        "site_domain" => $request->domain_name,
                        "site_footer" => $request->system_footer,
                    );
    
                    if($request->has('system_logo')){
                        $image = $request->file('system_logo');
    
                        $extension = $request->system_logo->getClientOriginalExtension();
                        $filenametostore = 'logo.'.$extension;
                        $filePath = public_path('files/brand_files/');
    
                        $image->move($filePath, $filenametostore);
                        $data['site_logo'] = $filenametostore;
                    }
    
                    if($request->has('system_favicon')){
                        $image = $request->file('system_favicon');
    
                        $extension = $request->system_favicon->getClientOriginalExtension();
                        $filenametostore = 'favicon.'.$extension;
                        $filePath = public_path('files/brand_files/');
    
                        $image->move($filePath, $filenametostore);
                        $data['site_favicon'] = $filenametostore;
                    }
    
                    if($brandSettings) {
    
                        DB::table("brand_settings")->delete();
                        $setting = BrandSettings::create($data);
    
                        if($setting) {
    
                            SystemSetting::create(["sys_key"=> "is_installed", "sys_value" => 1]);
    
                            return response()->json([
                                "status" => 200,
                                "message" => "System Setting Saved Successfull...",
                            ]);
                        }else{
                            return response()->json([
                                "status" => 500,
                                "message" => "Something went wrong!",
                            ]);
                        }
    
                    }else{
                        $setting = BrandSettings::create($data);
    
                        if($setting) {
                            SystemSetting::create(["sys_key"=> "is_installed", "sys_value" => 1]);
                            return response()->json([
                                "status" => 200,
                                "message" => "System Setting Saved Successfull",
                            ]);
                        }else{
                            return response()->json([
                                "status" => 500,
                                "message" => "Something went wrong!",
                            ]);
    
                        }
                    }
    
    
    
    
                }
            }
            
        }else{
        
            if($request->type == 'dbconfig') {

                $connection = mysqli_connect( $request->server_name , $request->username , $request->password, $request->dbname);

                if($connection) {

                    $setting = SystemSetting::where('sys_key','migration')->first();

                    if($setting) {

                        if($setting->sys_value == 1){
                            return response()->json([
                                "status" => 200,
                                "message" => "Connected Successfull",
                            ]);
                        }else{
                            
                            $migration = Artisan::call('migrate:refresh');
                            if($migration) {
                                $featureclass= 'FeatureAccessSeeder';
                                $systemclass= 'SystemSettings';
                                $ticketclass= 'TicketSettings';
            
                                Artisan::call("db:seed", ['--class' => $featureclass]);
                                Artisan::call("db:seed", ['--class' => $systemclass]);
                                Artisan::call("db:seed", ['--class' => $ticketclass]);
            
                                SystemSetting::create(["sys_key"=> "migration", "sys_value" => 1]);
                                return response()->json([
                                    "status" => 200,
                                    "message" => "Connected Successfull",
                                ]);
                            }
                        }

                    }else{

                        
                        $migration = Artisan::call('migrate:refresh');
                        if($migration) {
                            $featureclass= 'FeatureAccessSeeder';
                            $systemclass= 'SystemSettings';
                            $ticketclass= 'TicketSettings';
        
                            Artisan::call("db:seed", ['--class' => $featureclass]);
                            Artisan::call("db:seed", ['--class' => $systemclass]);
                            Artisan::call("db:seed", ['--class' => $ticketclass]);

                            SystemSetting::create(["sys_key"=> "migration", "sys_value" => 1]);       
        
                            return response()->json([
                                "status" => 200,
                                "message" => "Connected Successfull",
                            ]);
                        }
                    }
                }else{
                    return response()->json([
                        "status" => 500,
                        "message" => "Failed to Connect with Database",
                    ]);

                }

            } else if($request->type == 'adminUser') {

                $users = User::all();
                $data = array(
                    "name" => $request->name,
                    "email" => $request->email,
                    "alt_pwd" => Crypt::encryptString($request->password),
                    "password" => bcrypt($request->password),
                    "user_type" =>1,
                );

                if($users) {

                    DB::table("users")->delete();

                    $user = User::create($data);

                    if($user) {

                        return response()->json([
                            "status" => 200,
                            "message" => "Admin Created Successfull",
                        ]);
        
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Something went wrong!",
                        ]);
        
                    }
                    
                }else{

                    $user = User::create($data);

                    if($user) {

                        return response()->json([
                            "status" => 200,
                            "message" => "Admin Created Successfull",
                        ]);
        
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Something went wrong!",
                        ]);
        
                    }

                }

            } else if($request->type == 'company') {

                $companies = Company::all();

                $data = array(
                    "name" =>  $request->company_name,
                    "poc_first_name" =>  $request->comp_first_name,
                    "poc_last_name" =>  $request->comp_last_name,
                    "email" =>  $request->company_email,
                    "is_default" => 1,
                );

                if($companies) {

                    DB::table("companies")->delete();

                    $company = Company::create($data);

                    
                    if($company) {

                        return response()->json([
                            "status" => 200,
                            "message" => "Company Created Successfull",
                        ]);
        
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Something went wrong!",
                        ]);
        
                    }

                }else{

                    $company = Company::create($data);
                    if($company) {

                        return response()->json([
                            "status" => 200,
                            "message" => "Company Created Successfull",
                        ]);
        
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Something went wrong!",
                        ]);
        
                    }

                }



            }else if( $request->type == 'module') {

                for($i = 0; $i < sizeof($request->id); $i++) {
                    Feature::where('f_id',$request->id[$i])->update([
                        "is_active" => 1,
                    ]);
                }

                return response()->json([
                    "status" => 200,
                    "message" => "System Module Configure Successfull",
                ]);

            }else{

                $brandSettings = BrandSettings::all();

                $data = array(
                    "site_title" => $request->system_title,
                    "site_domain" => $request->domain_name,
                    "site_footer" => $request->system_footer,
                );

                if($request->has('system_logo')){
                    $image = $request->file('system_logo');

                    $extension = $request->system_logo->getClientOriginalExtension();
                    $filenametostore = 'logo.'.$extension;
                    $filePath = public_path('files/brand_files/');

                    $image->move($filePath, $filenametostore);
                    $data['site_logo'] = $filenametostore;
                }

                if($request->has('system_favicon')){
                    $image = $request->file('system_favicon');

                    $extension = $request->system_favicon->getClientOriginalExtension();
                    $filenametostore = 'favicon.'.$extension;
                    $filePath = public_path('files/brand_files/');

                    $image->move($filePath, $filenametostore);
                    $data['site_favicon'] = $filenametostore;
                }

                if($brandSettings) {

                    DB::table("brand_settings")->delete();
                    $setting = BrandSettings::create($data);

                    if($setting) {

                        SystemSetting::create(["sys_key"=> "is_installed", "sys_value" => 1]);

                        return response()->json([
                            "status" => 200,
                            "message" => "System Setting Saved Successfull...",
                        ]);
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Something went wrong!",
                        ]);
                    }

                }else{
                    $setting = BrandSettings::create($data);

                    if($setting) {
                        SystemSetting::create(["sys_key"=> "is_installed", "sys_value" => 1]);
                        return response()->json([
                            "status" => 200,
                            "message" => "System Setting Saved Successfull",
                        ]);
                    }else{
                        return response()->json([
                            "status" => 500,
                            "message" => "Something went wrong!",
                        ]);

                    }
                }




            } 

        }

    }


}
