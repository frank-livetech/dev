<?php

namespace App\Http\Controllers\SystemManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\mailEclipse;

class MailablesController extends Controller
{
  public static $prifixRoute = 'system_manager.template_builder.';

   public function toMailablesList()
   {
       return redirect()->route('mailableList');
   }

   public function index()
   {
       $mailables = mailEclipse::getMailables();

       $mailables = (null !== $mailables) ? $mailables->sortBy('name') : collect([]);

       return view(self::$prifixRoute.'sections.mailables', compact('mailables'));
   }

   public function createMailable(Request $request)
   {
       return view(self::$prifixRoute.'createmailable');
   }

   public function generateMailable(Request $request)
   {
       return mailEclipse::generateMailable($request);
   }

   public function viewMailable($name)
   {
       $mailable = mailEclipse::getMailable('name', $name);

       if ($mailable->isEmpty()) {
           return redirect()->route('mailableList');
       }

       $resource = $mailable->first();

       return view(self::$prifixRoute.'sections.view-mailable')->with(compact('resource'));
   }

   public function editMailable($name)
   {
       $templateData = mailEclipse::getMailableTemplateData($name);

       if (! $templateData) {
           return redirect()->route('viewMailable', ['name' => $name]);
       }

       return view(self::$prifixRoute.'sections.edit-mailable-template', compact('templateData', 'name'));
   }

   public function templatePreviewError()
   {
       return view(self::$prifixRoute.'previewerror');
   }

   public function parseTemplate(Request $request)
   {
       $template = $request->has('template') ? $request->template : false;

       $viewPath = $request->has('template') ? $request->viewpath : base64_decode($request->viewpath);

       // ref https://regexr.com/4dflu
       $bladeRenderable = preg_replace('/((?!{{.*?-)(&gt;)(?=.*?}}))/', '>', $request->markdown);

       if (mailEclipse::markdownedTemplateToView(true, $bladeRenderable, $viewPath, $template)) {
           return response()->json([
               'status' => 'ok',
           ]);
       }

       return response()->json([
           'status' => 'error',
       ]);
   }

   public function previewMarkdownView(Request $request)
   {
       return mailEclipse::previewMarkdownViewContent(false, $request->markdown, $request->name, false, $request->namespace);
   }

   public function previewMailable($name)
   {
       $mailable = mailEclipse::getMailable('name', $name);

       if ($mailable->isEmpty()) {
           return redirect()->route('mailableList');
       }

       $resource = $mailable->first();

       if (! is_null(mailEclipse::handleMailableViewDataArgs($resource['namespace']))) {
           // $instance = new $resource['namespace'];
           //
           $instance = mailEclipse::handleMailableViewDataArgs($resource['namespace']);
       } else {
           $instance = new $resource['namespace'];
       }

       if (collect($resource['data'])->isEmpty()) {
           return 'View not found';
       }

       $view = ! is_null($resource['markdown']) ? $resource['markdown'] : $resource['data']->view;

       if (view()->exists($view)) {
           try {
               $html = $instance;

               return $html->render();
           } catch (\ErrorException $e) {
               return view(self::$prifixRoute.'previewerror', ['errorMessage' => $e->getMessage()]);
           }
       }

       return view(self::$prifixRoute.'previewerror', ['errorMessage' => 'No template associated with this mailable.']);
   }

   public function delete(Request $request)
   {
       $mailableFile = config('maileclipse.mailables_dir').'/'.$request->mailablename.'.php';

       if (file_exists($mailableFile)) {
           unlink($mailableFile);

           return response()->json([
               'status' => 'ok',
           ]);
       }

       return response()->json([
           'status' => 'error',
       ]);
   }
}
