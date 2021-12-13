<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskScript;

class TaskScriptsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function task_scripts() {
        $task_scripts = TaskScript::where('is_deleted', 0)->get();
        return view('about.system_info.task_scripts', compact('task_scripts'));
    }

    public function _save(Request $request) {
        $data = [];
        try {
            if($request->hasFile('file')) {
                $image = $request->file('file');
                $filenamewithextension = $request->file->getClientOriginalName();
                //get filename without extension
                $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
                //get file extension
                $extension = $request->file->getClientOriginalExtension();
        
                // $filenametostores = time().'.'.$extension;
                $filePath = public_path('files/task_scripts/');

                $data['category'] = $request->category;
                $data['filename'] = $filenamewithextension;

                $filesize = $request->file->getSize()/1024;
                if($filesize > 2048) {
                    $response['message'] = 'File size exceeds 2MB';
                    $response['status_code'] = 500;
                    $response['success'] = false;
                    return response()->json($response);
                }
                $data['size'] = number_format((float)($filesize), 2, '.', '') .'KB';
                $data['created_by'] = \Auth::user()->id;
        
                $image->move($filePath, $filenamewithextension);

            } else {
                $response['message'] = 'Misssing file';
                $response['status_code'] = 500;
                $response['success'] = false;
                return response()->json($response);
            }

            $script = TaskScript::create($data);

            $response['message'] = 'Success';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['script']= $script;
        } catch(\Illuminate\Http\Exceptions\PostTooLargeException $e) {
            $response['message'] = 'File too large';
            $response['status_code'] = 500;
            $response['success'] = false;
        } catch(\Exception $e) {
            $response['message'] = 'Something went wrong';
            $response['status_code'] = 500;
            $response['success'] = false;
        }

        return response()->json($response);
    }

    public function _delete(Request $request) {
        $data = $request->all();
        try {

            $script = TaskScript::findOrFail($data['id']);

            $script->is_deleted = '1';
            $script->save();

            $response['message'] = 'Success';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['tickets']= $script;
        } catch(Exception $e) {
            $response['message'] = 'Something went wrong';
            $response['status_code'] = 500;
            $response['success'] = false;
        }

        return response()->json($response);
    }
}
