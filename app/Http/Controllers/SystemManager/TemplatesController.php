<?php

namespace App\Http\Controllers\SystemManager;

use App\Http\Controllers\Controller;
use App\mailEclipse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use DB;
use Illuminate\Support\Facades\Validator as Validations;

class TemplatesController extends Controller
{
    public static $prifixRoute = 'system_manager.template_builder.';

    public function index()
    {
        $skeletons = mailEclipse::getTemplateSkeletons();

        $templates = mailEclipse::getTemplates();

        $tmp_cats = DB::table('template_categories')->get();
        $tem_cats_modal = DB::table('template_categories')->whereRaw('cat_id !=2')->get();

        return View(self::$prifixRoute . 'sections.templates', compact('skeletons', 'templates','tmp_cats','tem_cats_modal'));
    }

    function new ($type, $name, $skeleton) {
        $type = $type === 'html' ? $type : 'markdown';

        $skeleton = mailEclipse::getTemplateSkeleton($type, $name, $skeleton);

        return response()->json(['skeleton'=>$skeleton]);
        //return View(self::$prifixRoute . 'sections.create-template', compact('skeleton'));
    }

    public function view($templateslug = null)
    {
        $template = mailEclipse::getTemplate($templateslug);

        if (is_null($template)) {
            return redirect()->route('templateList');
        }

        return View(self::$prifixRoute . 'sections.edit-template', compact('template'));
    }

    public function create(Request $request)
    {
        return mailEclipse::createTemplate($request);
    }

    public function select(Request $request)
    {
        $skeletons = mailEclipse::getTemplateSkeletons();

        return View(self::$prifixRoute . 'sections.new-template', compact('skeletons'));
    }

    public function previewTemplateMarkdownView(Request $request)
    {
        return mailEclipse::previewMarkdownViewContent(false, $request->markdown, $request->name, true);
    }

    public function delete(Request $request)
    {
        if (mailEclipse::deleteTemplate($request->templateslug)) {
            return response()->json([
                'status' => 'ok',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {
        return mailEclipse::updateTemplate($request);
    }

    public function saveCustomTemplate(Request $request){
       if(isset($request['catid'])){
            $res = DB::table('templates')->insert([
                'catid' => $request['catid'],
                'name' => $request['template_view_name'],
                'template_html' => $request['content'],
                'subject' => $request['temp_subject'],
                'alert_prefix' => $request['temp_alert_prefix'],
            ]);
            if($res){
                return response()->json(['status' => 'ok','message' => 'template created successfully...']);
            }else{
                return response()->json(['status' => 'error','message' => 'template not created successfully...']);
            }
       }
       return response()->json(['status' => 'error','message' => 'category is required']);
    }

    public function getTemplate(Request $request){
        $template = DB::table('templates')->where('id',$request->id)->first();
        if($template){
            return response()->json(['status' => 'ok','template' => $template]);
        }else{
            return response()->json(['status' => 'error','message' => 'template not found']);
        }
    }

    public function updateCustomTemplate(Request $request){
        $error = Validations::make($request->all(),[
          'templateid' => 'required',
          'template_html' => 'required',
          'templatename' => 'required'
        ]);

        if($error->fails()){
          return response()->json(['status'=>'error','message'=>$error->messages()->first()]);
        }

        $template = DB::table('templates')->where('id',$request['templateid'])->update([
            'name'  => $request['templatename'],
            'template_html' => $request['template_html'],
            'subject' => $request['temp_subject'],
            'alert_prefix' => $request['temp_alert_prefix'],
        ]);
        if($template){
            return response()->json(['status' => 'ok','message' => 'Template updated successfully..']);
        }else{
            return response()->json(['status' => 'error','message' => 'template not found']);
        }

    }

    public function deleteCustomTemplate(Request $request){
        $error = Validations::make($request->all(),[
          'id' => 'required',
        ]);

        if($error->fails()){
          return response()->json(['status'=>'error','message'=>$error->messages()->first()]);
        }

        $template = DB::table('templates')->where('id',$request['id'])->where('catid','!=',2)->delete();
        if($template){
            return response()->json(['status' => 'ok','message' => 'Template deleted successfully..']);
        }else{
            return response()->json(['status' => 'error','message' => 'template not deleted successfully']);
        }
    }






    // =================================
    // short codes crud functions
    //==================================

    public function addShortCodes(Request $request) {

        DB::table("sc_variables")->insert([
            "code" => $request->code,
            "description" => $request->desc,
        ]);

        $response['message'] = 'Record Added Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;

        return response()->json($response);

    }

    public function getAllShortCodes() {
        $code_result=  DB::table("sc_variables")->orderBy("id","desc")->get();

        $response['message'] = 'Code list';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $code_result;

        return response()->json($response);
    }

    public function deleteShortCodes($id) {

        DB::table("sc_variables")->where("id","=",$id)->delete();
        $response['message'] = 'Record Deleted Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function updateShortCodes(Request $request) {

        DB::table("sc_variables")->where("id","=",$request->id)->update([
            "code" => $request->code,
            "description" => $request->desc,
        ]);

        $response['message'] = 'Record Updated Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;

        return response()->json($response);

    }










}
