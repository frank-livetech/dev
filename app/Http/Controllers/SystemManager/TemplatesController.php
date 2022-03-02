<?php

namespace App\Http\Controllers\SystemManager;

use App\Http\Controllers\Controller;
use App\mailEclipse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator as Validations;

class TemplatesController extends Controller
{

    public function __construct()
    {
        $this->middleware(function (Request $request, $next) {
            if (Auth::user()->user_type == 5) {
                return redirect()->route('un_auth');
            }
            return $next($request);
        });
    }

    public static $prifixRoute = 'system_manager.template_builder.';

    public function index()
    {
        $skeletons = mailEclipse::getTemplateSkeletons();

        $templates = mailEclipse::getTemplates();

        $tmp_cats = DB::table('template_categories')->get();
        foreach ($tmp_cats as $cat) {
            $cat->template = DB::table('templates')->where('catid', $cat->cat_id)->get();
        }


        $tem_cats_modal = DB::table('template_categories')->whereRaw('cat_id !=2')->get();

        return view('system_manager.templates.index', get_defined_vars());
    }

    public function saveTemplates(Request $request) {
        DB::table("templates")->insert([
            "catid" => $request->catid,
            "name" => $request->template_name,
            "code" => $request->template_code,
            "subject" => $request->temp_subject,
            "alert_prefix" => $request->temp_alert_prefix,
        ]);

        return response()->json([
            "status_code" => 200,
            "success" => true,
            "message" => "Template Saved.",
        ]);
    }

    public function getTemplates() {
        return response()->json([
            "status" => 200,
            "success" => true,
            "templates" => DB::table("templates")->get(),
        ]);
    }

    public function createTemplate($id) {
        $template = DB::table("templates")->where('id', $id)->first();
        return view('system_manager.templates.create', get_defined_vars());
    }

    public function updateTemp(Request $request) {


        $completeHtml = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <meta name="description" content="">
                    <meta name="author" content="">
                    <title>Document</title>
                    <style>
                        ' . $request->my_css. '
                        ' . $request->my_styles. '
                    </style>
                </head>
            
            <body>
                ' . $request->my_html. '
            </body>
        </html>';

        DB::table("templates")->where('id' , $request->id)->update([
            "template_html" => $completeHtml,
            "components" => $request->my_components,
            "my_assets" => $request->my_assets,
            "my_css" => $request->my_css,
            "my_styles" => $request->my_styles,
            "name" => $request->templateName,
        ]);

        return response()->json([
            "status_code" => 200,
            "success" => true,
            "message" => "Template Saved Successfully.",
        ]);
    }

    public function viewTemplate($id) {
        $data = DB::table("templates")->where('id' , $id)->first();
        return view('system_manager.templates.view', get_defined_vars());
    }

    public function deleteTemp(Request $request) {

        DB::table("templates")->where('id' , $request->id)->delete();

        return response()->json([
            "status_code" => 200,
            "success" => true,
            "message" => "Template Deleted Successfully.",
        ]);
    }

    public function index2() {
        $skeletons = mailEclipse::getTemplateSkeletons();

        $templates = mailEclipse::getTemplates();

        $tmp_cats = DB::table('template_categories')->get();
        foreach ($tmp_cats as $cat) {
            $cat->template = DB::table('templates')->where('catid', $cat->cat_id)->get();
        }

        // dd($tmp_cats->toArray());

        $tem_cats_modal = DB::table('template_categories')->whereRaw('cat_id !=2')->get();

        return View(self::$prifixRoute . 'sections.templates_new', compact('skeletons', 'templates', 'tmp_cats', 'tem_cats_modal'));
    }

    function new($type, $name, $skeleton)
    {
        $type = $type === 'html' ? $type : 'markdown';

        $skeleton = mailEclipse::getTemplateSkeleton($type, $name, $skeleton);

        return response()->json(['skeleton' => $skeleton]);
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

    public function saveCustomTemplate(Request $request)
    {
        if (isset($request['catid'])) {
            $res = DB::table('templates')->insert([
                'catid' => $request['catid'],
                'name' => $request['template_view_name'],
                'template_html' => $request['content'],
                'subject' => $request['temp_subject'],
                'alert_prefix' => $request['temp_alert_prefix'],
            ]);
            if ($res) {
                return response()->json(['status' => 'ok', 'message' => 'template created successfully...']);
            } else {
                return response()->json(['status' => 'error', 'message' => 'template not created successfully...']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'category is required']);
    }

    public function getTemplate(Request $request)
    {
        $template = DB::table('templates')->where('id', $request->id)->first();
        if ($template) {
            return response()->json(['status' => 'ok', 'template' => $template]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'template not found']);
        }
    }

    public function updateCustomTemplate(Request $request)
    {
        $error = Validations::make($request->all(), [
            'templateid' => 'required',
            'template_html' => 'required',
            'templatename' => 'required'
        ]);

        if ($error->fails()) {
            return response()->json(['status' => 'error', 'message' => $error->messages()->first()]);
        }

        $template = DB::table('templates')->where('id', $request['templateid'])->update([
            'name'  => $request['templatename'],
            'template_html' => $request['template_html'],
            'subject' => $request['temp_subject'],
            'alert_prefix' => $request['temp_alert_prefix'],
        ]);
        if ($template) {
            return response()->json(['status' => 'ok', 'message' => 'Template updated successfully..']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'template not found']);
        }
    }

    public function deleteCustomTemplate(Request $request)
    {
        $error = Validations::make($request->all(), [
            'id' => 'required',
        ]);

        if ($error->fails()) {
            return response()->json(['status' => 'error', 'message' => $error->messages()->first()]);
        }

        $template = DB::table('templates')->where('id', $request['id'])->where('catid', '!=', 2)->delete();
        if ($template) {
            return response()->json(['status' => 'ok', 'message' => 'Template deleted successfully..']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'template not deleted successfully']);
        }
    }






    // =================================
    // short codes crud functions
    //==================================

    public function addShortCodes(Request $request)
    {

        DB::table("sc_variables")->insert([
            "code" => $request->code,
            "description" => $request->desc,
        ]);

        $response['message'] = 'Record Added Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;

        return response()->json($response);
    }

    public function getAllShortCodes()
    {
        $code_result =  DB::table("sc_variables")->orderBy("id", "desc")->get();

        $response['message'] = 'Code list';
        $response['status_code'] = 200;
        $response['success'] = true;
        $response['data'] = $code_result;

        return response()->json($response);
    }

    public function deleteShortCodes($id)
    {

        DB::table("sc_variables")->where("id", "=", $id)->delete();
        $response['message'] = 'Record Deleted Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

    public function updateShortCodes(Request $request)
    {

        DB::table("sc_variables")->where("id", "=", $request->id)->update([
            "code" => $request->code,
            "description" => $request->desc,
        ]);

        $response['message'] = 'Record Updated Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;

        return response()->json($response);
    }
}
