<?php

namespace App\Http\Controllers\ProjectManager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ActivitylogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Projectfolder;
use App\Models\Project;
use App\Models\ProjectNotes;
use App\Models\Tasks;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Activitylog;
use App\Models\BrandSettings;
use App\Models\TaskAttachments;
use App\Models\Notification;
use App\User;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\SystemManager\MailController;
use Validator;
use Throwable;
use Carbon\Carbon;
use Exception;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Schema;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File;


class ProjectManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function projects_list(){
      
        $projectsfolder = Projectfolder::where('is_deleted', 0)->get();
        $projects = Project::where('is_deleted', 0)->get();
        
        $users = User::where('is_deleted',0)->whereNotIn('user_type',['5','4'])->where('is_support_staff',0)->get();
      
        foreach($projects as $project){
          foreach($users as $user){
            $pending = Tasks::where('project_id', $project->id)->where('is_deleted',0)->where('assign_to', $user->id)->where('task_status', 'danger')->get()->count();
            $complete = Tasks::where('project_id', $project->id)->where('is_deleted',0)->where('assign_to', $user->id)->where('task_status', 'success')->get()->count();
            $working = Tasks::where('project_id', $project->id)->where('is_deleted',0)->where('assign_to', $user->id)->where('task_status', 'default')->get()->count();
  
            $user[$project->project_slug] = array("pending"=>$pending, "complete"=>$complete, "working"=>$working);
          }
        }

        $today_date = date("Y-m-d"); 

        $overdue_taks = Tasks::with('taskCreator')
        ->where('task_status','!=','Select')
        ->where("task_status","!=","success")
        ->where('due_date','<', $today_date)
        // ->where('estimated_time','>', $this->convertSecondsintoHMS('worked_time'))
        ->where('is_deleted',0)
        ->count(); 

        $external_project = Project::where('project_type','external')->where('is_deleted',0)->count();

        $tasks =  Tasks::with('taskCreator')
          ->with('taskProject')
          ->where('assign_to',\Auth::user()->id)
          ->where('task_status','!=','success')
          ->where('task_status','!=','Select')
          ->where('is_deleted',0)
          ->orderBy('id','desc')
          ->get();


        // inactive users 

        $inactive_users = DB::table('users')
            ->join('staff_attendance', 'users.id', '=', 'staff_attendance.user_id')
            ->select('users.*')
            ->whereNotIn('users.user_type',['4','5'])
            ->where('users.is_deleted',0)
            ->where('staff_attendance.clock_out',NULL)
            ->get();
            
        $free_staff = array();
        
        foreach($inactive_users as $user ) {  
        
          $tasks = Tasks::where('task_status','default')
          ->where('assign_to',$user->id)
          ->where('is_deleted',0)          
          ->count();
          
          if($tasks == 0){
              array_push($free_staff , $user );
          }
          
        }

        return view('project_manager.index',compact('projectsfolder','projects','free_staff','users','tasks','overdue_taks','external_project'));
    }

    public function task_project_2($status=null, $userid=null){

      $project_slug = strtolower('job-notes');

      $project = Project::with('projectCustomer')->with('projectManager')->where('project_slug',$project_slug)->first();
      
      try {
        if($project->hostname != NULL ){
          $project->hostname = Crypt::decryptString($project->hostname);
        }
        if($project->site_type != NULL ){
          $project->site_type = Crypt::decryptString($project->site_type);
        }
        if($project->url != NULL){
          $project->url = Crypt::decryptString($project->url);
        }
        if($project->username != NULL){
          $project->username = Crypt::decryptString($project->username);
        }
        if($project->password != NULL){
          $project->password = Crypt::decryptString($project->password);
        }
        
        
      } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
        //
      }

      $customers = Customer::where('is_deleted',0)->get();
      $users = User::where('user_type','!=',4)->where('user_type','!=',5)->where('is_deleted',0)->get();
      
      // $versions = DB::table($project_slug.'_prj_roadmap')->select('version')->groupby('version')->orderby('version','ASC')->get();
      $versions = Tasks::select('version')->where('project_id', $project->id)->groupby('version')->orderby('version','ASC')->get();
      $settings = BrandSettings::first();
      
      if($status == null){
        // $tasks = Tasks::with(array('taskAttachments'=>function($query){
        //   $query->where('is_deleted', 0)->orderBy('id','desc');

        // }))->where('project_id', $project->id)->where('is_deleted', 0)->get();

        $tasks = Tasks::where('project_id', $project->id)->where('is_deleted', 0)->get();        

        $total_tasks = $tasks->count();
        $pending_tasks = 0;
        $in_progress_tasks = 0;
        $completed_tasks = 0;
        
        foreach($tasks as $task) {
          $task->created_by = User::where('id',$task->created_by)->where('is_deleted', 0)->first();
          $completed_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->count();
          $pending_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'danger')->where('project_id', $project->id)->count();
          $in_progress_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'default')->where('project_id', $project->id)->count();
        }

        $date_format = Session('system_date');

        $change_logs = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->get();

        return view('project_manager.project_task_2',compact('change_logs','total_tasks','completed_tasks','in_progress_tasks','pending_tasks','tasks','project','customers','users','versions','settings','project_slug', 'status','date_format'));
      }else{
        $filter = '';
        if(strtolower($status) == 'complete'){
          $filter = 'success';
        }elseif(strtolower($status) == 'pending'){
          $filter = 'danger';
        }elseif(strtolower($status) == 'working'){
          $filter = 'default';
        }
        
        $tasks = Tasks::where('project_id', $project->id)->where('is_deleted', 0)->get();

        $total_tasks = $tasks->count();
        $pending_tasks = 0;
        $in_progress_tasks = 0;
        $completed_tasks = 0;

        foreach($tasks as $task) {
          $task->created_by = User::where('id',$task->created_by)->where('is_deleted', 0)->first();
          $completed_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->count();
          $pending_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'danger')->where('project_id', $project->id)->count();
          $in_progress_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'default')->where('project_id', $project->id)->count();
        }
        $change_logs = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->get();
        $date_format = Session('system_date');
        return view('project_manager.project_task_2',compact('change_logs','total_tasks','completed_tasks','in_progress_tasks','pending_tasks','tasks','project','customers','users','versions','settings','project_slug', 'status','date_format'));
      }
      
    }

    public function saveProjectDescription(Request $request) {
      $project = Project::findOrFail($request->project_id);
      $project->description = $request->project_desc;
      $project->save();

      $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
      $action_perform = 'Project '.$project->name.' Description Updated By '. $name_link;
      $log = new ActivitylogController();
      $log->saveActivityLogs('Project Description' , 'project_description' , $project->id , auth()->id() , $action_perform);

      $response['message'] = 'Project Description Saved Successfully';
      $response['status_code'] = 200;
      $response['success'] = true;
      return response()->json($response);

    }

    public function tags_project_notes(Request $request){
     $users = User::select('id','name','profile_pic')->where('name','like','%'.$request->term.'%')->where('is_deleted', 0)->get();
      // $data=[];
      // foreach ($users as $key => $value) {
      //  $html ='';
      //  $photo = $value->profile_pic !='' ? \URL::asset('files/user_photos/'.$value->profile_pic): \URL::asset('files/user_photos/user-photo.jpg');
      //  $html .= `<a><img src="`.$photo.`"alt="'Photo" class="rounded-circle" style="width:50px;height:50px; margin: 0px auto !important"> <span id="logo_title">`.$value->name.`</span><a>`;
      // array_push( $data,$html);
      // }
     return response()->json($users);
    }

    public function saveProjectNotes(Request $request) {
      
      $project_note = new ProjectNotes();
      $project_note->project_id = $request->project_id;
      $project_note->note = $request->project_note;
      $project_note->color = $request->color;
      $project_note->save();

      $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
      $action_perform = 'Project Note # '.$project_note->id.' Created By '. $name_link;
      $log = new ActivitylogController();
      $log->saveActivityLogs('Project Notes' , 'note_created' ,  $project_note->id , auth()->id() , $action_perform);

      if($request->tag_emails != null && $request->tag_emails != '') {

        $emails = explode(',',$request->tag_emails);

        for( $i = 0; $i < sizeof($emails); $i++ ) {
            
            $user = User::where('is_deleted',0)->where('email',$emails[$i])->first();
            $projects = Project::where('is_deleted', 0)->where('id',$request->project_id)->first();

            $notify = new NotifyController();
            $sender_id = \Auth::user()->id;
            $receiver_id = $user->id;
            $slug = 'roadmap/' .$projects->project_slug;
            $type = 'tag_notes';
            $data = 'data';
            $title = 'Tag Notification';
            $icon = 'fas fa-tag';
            $class = 'btn-success';
            $desc = 'You were tagged by '.\Auth::user()->name . ' on ' . $projects->name. ' Project';

            $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
        }

      }

      $response['message'] = 'Project Notes Saved Successfully';
      $response['status_code'] = 200;
      $response['success'] = true;
      return response()->json($response);


    }

    public function getProjectNotes($id) {
      $notes = ProjectNotes::where('project_id',$id)->get();
      foreach($notes as $note) {
        $project = Project::where('id',$id)->where('is_deleted',0)->first();
        $note->created_by = User::where('id',$project->created_by)->select('name','profile_pic')->first();
      }
      $response['message'] = 'Project Notes List';
      $response['status_code'] = 200;
      $response['success'] = true;
      $response['notes'] = $notes;
      return response()->json($response);
    }

    public function updateProjectNotes(Request $request) {
      $project_note = ProjectNotes::findOrFail($request->id);
      $project_note->note = $request->note;
      $project_note->color = $request->color;
      $project_note->save();

      $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
      $action_perform = 'Project Note # '.$project_note->id.' Updated By '. $name_link;
      $log = new ActivitylogController();
      $log->saveActivityLogs('Project Notes' , 'note_updated' ,  $project_note->id , auth()->id() , $action_perform);

      if($request->tag_emails != null && $request->tag_emails != '') {

        $emails = explode(',',$request->tag_emails);

        for( $i = 0; $i < sizeof($emails); $i++ ) {
            
            $user = User::where('is_deleted',0)->where('email',$emails[$i])->first();
            $projects = Project::where('is_deleted', 0)->where('id',$project_note->project_id)->first();

            $notify = new NotifyController();
            $sender_id = \Auth::user()->id;
            $receiver_id = $user->id;
            $slug = 'roadmap/'.$projects->project_slug;
            $type = 'tag_notes';
            $data = 'data';
            $title = 'Tag Notification';
            $icon = 'fas fa-tag';
            $class = 'btn-success';
            $desc = 'Tag by '.\Auth::user()->name . ' in ' . $projects->name . ' Project';

            $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
        }

      }

      $response['message'] = 'Project Notes Updated Successfully';
      $response['status_code'] = 200;
      $response['success'] = true;
      return response()->json($response);
    }

    public function deleteProjectNotes(Request $request) {
      ProjectNotes::where('id',$request->id)->delete();

      $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
      $action_perform = 'Project Note # '.$request->id.' Deleted By '. $name_link;
      $log = new ActivitylogController();
      $log->saveActivityLogs('Project Notes' , 'note_deleted' , $request->ticket_id , auth()->id() , $action_perform);

      $response['message'] = 'Project Notes Deleted Successfully';
      $response['status_code'] = 200;
      $response['success'] = true;
      return response()->json($response);
    }

    public function getProjectActivityLogs() {
      $logs =  Activitylog::whereIn('module',['Project Notes','Project Asset','Project Description','Task Created'])->get();
      // print_r($logs);
      foreach($logs as $log) {
        $log->created_by = User::where('id',$log->created_by)->first();
      }

      $response['message'] = 'Project Logs List';
      $response['status_code'] = 200;
      $response['success'] = true;
      $response['logs'] = $logs;
      return response()->json($response);
      
    }

    public function project_task(){
        
        $users = User::where('is_deleted',0)->where('user_type','!=',5)->where('user_type','!=',4)->where('is_support_staff',0)();
        
        return view('project_manager.project_task',compact('users'));
    }

    public function save_folder(Request $request){

        $data = $request->all();
        
        $response = array();

        $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

        try{
            $validator = Validator::make($request->all(), [ 

                'name' => (array_key_exists('id', $data)) ? ['required', 'string', 'max:255'] : ['required', 'string', 'max:255','unique:projects_folder'],
                
            ]);
            
            if ($validator->fails()) {
    
                $response['message'] = $validator->messages()->first();
                $response['status_code'] = 200;
                $response['success'] = false;
                return response()->json($response);
    
            }else{
              if( array_key_exists('id', $data) ) {
                $folder = Projectfolder::findOrFail($data['id']);
                
                // if( is_dir('/files/Projects Folders/'.$folder->name) ) {
                //   if( !rename('/files/Projects Folder/'.$folder->name, '/files/Projects Folder/'.$data['name']) ) {
                //     throw new \Exception('Failed to rename directory');
                //   }
                // }else{
                //   if( !mkdir('/files/Projects Folders/'.$data['name'], 0777, true) ) {
                //     throw new \Exception('Failed to add directory');
                //   }
                // }

                $folder->name = $data['name'];
                $folder->updated_by = \Auth::user()->id;
                $folder->updated_at = Carbon::now();

                $folder->save();

                $action_perform = 'Project Folder '. $data['name'] .' Updated By '. $name_link;
                $log = new ActivitylogController();
                $log->saveActivityLogs('Project Folder' , 'project_folder' , $data['id'] , auth()->id() , $action_perform);

              }else{
                // if( !is_dir('/files/Projects Folders/'.$data['name']) ) {
                //   if( !mkdir('/files/Projects Folders/'.$data['name'], 0777, true) ) {
                //     throw new \Exception('Failed to add directory');
                //   }
                // }
                $data['created_by'] = \Auth::user()->id;
                $data = Projectfolder::create($data);

                $action_perform =  'Project Folder '. $data['name'] .' Created By '. $name_link;
                $log = new ActivitylogController();
                $log->saveActivityLogs('Project Folder' , 'project_folder' , $data['id'] , auth()->id() , $action_perform);

              }

              
              
              $response['message'] = 'Folder Saved Successfully!';
              $response['status_code'] = 200;
              $response['success'] = true;
              $response['result'] = $data;
              return response()->json($response);
            }

      }catch(Exception $e){
        $response['message'] = 'Something Went wrong!';
        $response['status_code'] = 500;
        $response['success'] = false;
        return response()->json($response);
      }
    }

    public function delete_folder(Request $request){
      $folder = Projectfolder::findOrFail($request->id);
      $projects = Project::where('folder_id',$folder->id)->get();
      
      foreach($projects as $project) {
        DB::table('tasks')->where('project_id',$project->id)->update([
          'is_deleted' => 1,
        ]);
      }
      
      DB::table('projects')->where('folder_id',$folder->id)->delete();
      DB::table('projects_folder')->where('id',$request->id)->delete();

      $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
      $action_perform = 'Project Folder ' . $folder->name . ' Deleted By '. $name_link;
      $log = new ActivitylogController();
      $log->saveActivityLogs( 'Project Folder Deleted' , 'project_folder_deleted' , $request->id , auth()->id() , $action_perform);

      $response['message'] = 'Folder Deleted Successfully!';
      $response['status_code'] = 200;
      $response['success'] = true;
      return response()->json($response);
    }

    public function save_project(Request $request){

      $data = $request->all();
          
      $response = array();
      $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';

      try{
        
        $validator = Validator::make($request->all(), [ 

            'name' => (array_key_exists('id', $data)) ? ['required', 'string', 'max:255'] : ['required', 'string', 'max:255','unique:projects'],
   
        ]);
        
        if ($validator->fails()) {

            $response['message'] = $validator->messages()->first();
            $response['status_code'] = 200;
            $response['success'] = false;
            return response()->json($response);

        }else{
            $slug = explode('.',$data['name']);
            if(array_key_exists('id', $data)) {
              $project = Project::findOrFail($data['id']);
              
              $data['project_slug'] = $slug[0];
              $data['project_roadmap_table'] =  $data['project_slug'].'-prj-roadmap';

              $project->name = $data['name'];
              $project->folder_id = $data['folder_id'];
              $project->project_type = $data['project_type'];
              $project->project_slug = $data['project_slug'];
              $project->project_roadmap_table = $data['project_roadmap_table'];
              $project->updated_at = Carbon::now();
              $project->updated_by = \Auth::user()->id;

              $project->save();

              $action_perform = 'Project Updated By '. $name_link;
              $log = new ActivitylogController();
              $log->saveActivityLogs('Project' , 'project' , $data['id'] , auth()->id() , $action_perform);
            }else{
                $data['project_slug'] = $slug[0];
                $data['created_by'] = \Auth::user()->id;
                $data['project_roadmap_table'] =  $data['project_slug'].'-prj-roadmap';
                $data = Project::create($data);

                $action_perform = 'Project Created By '. $name_link;
                $log = new ActivitylogController();
                $log->saveActivityLogs('Project' , 'project' , $data['id'] , auth()->id() , $action_perform);
               
            }

            $response['message'] = 'Project Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            $response['result'] = $data;
            return response()->json($response);
          }
      }catch(Exception $e){
          $response['message'] = 'Something Went wrong!';
          $response['status_code'] = 500;
          $response['success'] = false;
          return response()->json($response);
      }
    }

  public function delete_project(Request $request) {

    $project = Project::findOrFail($request->id);
    
    DB::table('tasks')->where('project_id',$project->id)->update([
      'is_deleted' => 1,
    ]);

    $project->delete();
    $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
    $action_perform = 'Project Deleted '. $$project->name .'  By '. $name_link;
    $log = new ActivitylogController();
    $log->saveActivityLogs('Project Deleted' , 'project_deleted' , $request->id , auth()->id() , $action_perform);

    $response['message'] = 'Project Deleted Successfully!';
    $response['status_code'] = 200;
    $response['success'] = true;
    return response()->json($response);
  }

  public function projectRoadmap($project_slug, $status=null, $userid=null){
    // return $status;
    $project_slug = strtolower($project_slug);
    
    $project = Project::with('projectCustomer')->with('projectManager')->where('project_slug',$project_slug)->first();
    
    try {
      if($project->hostname != NULL ){
        $project->hostname = Crypt::decryptString($project->hostname);
      }
      if($project->site_type != NULL ){
        $project->site_type = Crypt::decryptString($project->site_type);
      }
      if($project->url != NULL){
        $project->url = Crypt::decryptString($project->url);
      }
      if($project->username != NULL){
        $project->username = Crypt::decryptString($project->username);
      }
      if($project->password != NULL){
        $project->password = Crypt::decryptString($project->password);
      }
      
      
    } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
      //
    }

    $customers = Customer::where('is_deleted',0)->get();
    $users = User::where('user_type','!=',4)->where('user_type','!=',5)->where('is_deleted',0)->get();
    
    // $versions = DB::table($project_slug.'_prj_roadmap')->select('version')->groupby('version')->orderby('version','ASC')->get();
    $versions = Tasks::select('version')->where('project_id', $project->id)->groupby('version')->orderby('version','ASC')->get();
    $settings = BrandSettings::first();
    
    if($status == null){
      // $tasks = Tasks::with(array('taskAttachments'=>function($query){
      //   $query->where('is_deleted', 0)->orderBy('id','desc');

      // }))->where('project_id', $project->id)->where('is_deleted', 0)->get();

      $tasks = Tasks::where('project_id', $project->id)->where('is_deleted', 0)->orderBy('version','desc')->get();

      $total_tasks = $tasks->count();
      $pending_tasks = 0;
      $in_progress_tasks = 0;
      $completed_tasks = 0;
      
      foreach($tasks as $task) {
        $task->created_by = User::where('id',$task->created_by)->where('is_deleted', 0)->first();
        $task->assgined_to = User::where('id',$task->assign_to)->where('is_deleted', 0)->first();
        $completed_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->count();
        $pending_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'danger')->where('project_id', $project->id)->count();
        $in_progress_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'default')->where('project_id', $project->id)->count();
      }

      $date_format = Session('system_date');

      $change_logs = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->orderBy('created_at', 'desc')->get();
      
      return view('project_manager.project_task',compact('change_logs','total_tasks','completed_tasks','in_progress_tasks','pending_tasks','tasks','project','customers','users','versions','settings','project_slug', 'status','date_format'));
    }else{
      $filter = '';
      if(strtolower($status) == 'complete'){
        $filter = 'success';
      }elseif(strtolower($status) == 'pending'){
        $filter = 'danger';
      }elseif(strtolower($status) == 'working'){
        $filter = 'default';
      }
      
      $tasks = Tasks::where('project_id', $project->id)->where('is_deleted', 0)->orderBy('id','desc')->get();

      $total_tasks = $tasks->count();
      $pending_tasks = 0;
      $in_progress_tasks = 0;
      $completed_tasks = 0;

      foreach($tasks as $task) {
        $task->created_by = User::where('id',$task->created_by)->where('is_deleted', 0)->first();
        $completed_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->count();
        $pending_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'danger')->where('project_id', $project->id)->count();
        $in_progress_tasks = Tasks::where('is_deleted', 0)->where('task_status', 'default')->where('project_id', $project->id)->count();
      }
      $change_logs = Tasks::where('is_deleted', 0)->where('task_status', 'success')->where('project_id', $project->id)->orderBy('id','desc')->get();
      $date_format = Session('system_date');
      return view('project_manager.saveProjectNotes',compact('change_logs','total_tasks','completed_tasks','in_progress_tasks','pending_tasks','tasks','project','customers','users','versions','settings','project_slug', 'status','date_format'));
    }
    
  }

  public function delete_task(Request $request){
    //   return $request->id;
    
    // $data = DB::Table($request->project_slug.'_prj_roadmap')->where('id',$request->task_id)->get();
    $data = Tasks::findOrFail($request->task_id);

    if(empty($data)){
        $response['message'] = 'Something Went wrong!';
        $response['status_code'] = 500;
        $response['success'] = false;
        return response()->json($response);
    }else{
        $data->is_deleted = 1;
        $data->deleted_by = \Auth::user()->id;
        $data->update();
        // DB::table($request->project_slug.'_prj_roadmap')
        //     ->where('id', $request->task_id)
        //     ->update(['is_deleted'=>1,'deleted_by'=>\Auth::user()->id]);

        $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
        $action_perform = 'Task #'. $request->task_id .' Deleted By '. $name_link;
        $log = new ActivitylogController();
        $log->saveActivityLogs('Task Deleted' , 'task_deleted' , $request->task_id , auth()->id() , $action_perform);

        $response['message'] = 'Task Deleted Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }
    
  }

  public function getTaskById(Request $request) {
    $data = Tasks::findOrFail($request->task_id);

    $response['message'] = 'Task List';
    $response['status_code'] = 200;
    $response['success'] = true;
    $response['task'] = $data;
    return response()->json($response);

  }
  
  public function update_title(Request $request){
      
      $data = $request->all();
      $project = Project::where('project_slug',$data['project_slug'])->first();
      
      if($project){
        $project->name = $data['title'];
        $project->save();
         $response['message'] = 'Project Title Updated Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
      }else{
        $response['message'] = 'Something Went wrong!';
        $response['status_code'] = 500;
        $response['success'] = false;
        return response()->json($response);
      }
      
  }
    
    public function update_customer(Request $request){
        
      $data = $request->all();
      $project = Project::where('project_slug',$data['project_slug'])->first();
      
      if($project){
        $project->customer_id = $data['customer_id'];
        $project->save();
         $response['message'] = 'Project Customer Saved Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
      }else{
        $response['message'] = 'Something Went wrong!';
        $response['status_code'] = 500;
        $response['success'] = false;
        return response()->json($response);
      }
        
    }
    
    public function update_manager(Request $request){
        
      $data = $request->all();
      $project = Project::where('project_slug',$data['project_slug'])->first();
      
      if($project){
        $project->project_manager_id = $data['manager_id'];
        $project->save();
        $response['message'] = 'Project Manager Saved Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
      }else{
        $response['message'] = 'Something Went wrong!';
        $response['status_code'] = 500;
        $response['success'] = false;
        return response()->json($response);
      }
        
    }
  
    public function save_server_detail(Request $request){

      $data = $request->all();
      
      $project = Project::where('id',$data['id'])->first();

          if($project){
              $project->hostname = Crypt::encryptString($data['hostname']);
              $project->site_type = Crypt::encryptString($data['site_type']);
              $project->url = Crypt::encryptString($data['url']);
              $project->username = Crypt::encryptString($data['username']);
              $project->password = Crypt::encryptString($data['password']);
              $project->save();
      
              $response['message'] = 'Server Details Saved Successfully!';
              $response['status_code'] = 200;
              $response['success'] = true;
              return response()->json($response);
      
          }else{
          
              $response['message'] = 'Something Went wrong!';
              $response['status_code'] = 500;
              $response['success'] = false;
              return response()->json($response);
              
          }  
    }

    public function add_project_task(Request $request){
          
        $data = $request->all();
        // return $_FILES;
        unset($data['slug']);
        
        $id = $request->id;
        
        // $validator = Validator::make($request->all(), [ 
          
        //   'title' => ['required', 'string', 'max:255'],
        //   'version' => ['required', 'string'],
        //   'task_status' => ['required', 'string'],
        //   'work_tech' => ['required', 'string'],
        //   // 'task_description' => ['required', 'string']
          
        // ]);
        
        // if ($validator->fails()) {
          
        //   $response['message'] = $validator->messages()->first();
        //   $response['status_code'] = 200;
        //   $response['success'] = false;
        //   return response()->json($response);
          
        // }
        
        $response = array();
        if(!empty($id)){

            DB::table('tasks')->where('id', $id)->update([
              'version'=>$data['version'],
              'title'=>$data['title'],
              'task_status'=>$data['task_status'],
              'task_priority'=>$data['task_priority'],
              'start_date'=>$data['start_date'],
              'due_date'=>$data['due_date'],
              'completed_at'=>$data['completed_at'],
              'completion_time'=>$data['completion_time'],
              'estimated_time'=>$data['estimated_time'],
              'assign_to' =>$data['assign_to'],
              'task_type' => $data['task_type'],
              'work_tech' =>$data['work_tech'],
              'other_tech' =>$data['other_tech'],
              'task_description'=>$data['task_description'],
              'updated_by'=>\Auth::user()->id
            ]);

            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Task # ' . $id . ' updated By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Task Updated' , 'task_updated' , $id , auth()->id() , $action_perform);
          
            $attachments = [];
            $old_attachs = TaskAttachments::where('project_id', $data['project_id'])->where('task_id', $id)->get();
            $cc = count($old_attachs);

            
            if(isset($data['delete_Attachments']) && !empty($data['delete_Attachments'])){
              $delete_attachs = '';
              if(strpos($data['delete_Attachments'], ',')) {
                $delete_attachs = explode(',', $data['delete_Attachments']);
              }else{
                $delete_attachs = [$data['delete_Attachments']];
              }
              
              for($i=0; $i<$cc; $i++){
                for($j=0; $j<count($delete_attachs); $j++){
                  // echo $old_attachs[$i]->attachment. " == ".$delete_attachs[$j]."<br>";
                  if($old_attachs[$i]->attachment == $delete_attachs[$j]){
                    $old_attachs[$i]->is_deleted = 1;
                    $old_attachs[$i]->updated_by = \Auth::user()->id;
                    $old_attachs[$i]->deleted_by = \Auth::user()->id;

                    $old_attachs[$i]->update();
                  }
                }
              }
            }

            if (isset($_FILES) && !empty($_FILES))
            {
              for($i=0; $i<count($_FILES); $i++){
                $cc++;
                $attachments[] = array("project_id"=>$data['project_id'],"task_id"=>$id,"attachment"=>$cc.'_'.$_FILES['attachment_'.$i]['name'],
                "created_by"=>\Auth::user()->id);
                
                $image = $request->file('attachment_'.$i);
                
                $image->move('files/Projects/'.$request->slug.'/'.$id.'/', $attachments[$i]['attachment']);
              }

              TaskAttachments::insert($attachments);
            }

            $response['message'] = 'Project task Update Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
            
        }else{
            $data['created_by'] = \Auth::user()->id;
            $data['start_date'] = $data['start_date'];
            $attachments = [];
            if (isset($_FILES) && !empty($_FILES))
            {
              for($i=0; $i<count($_FILES); $i++){
                $attachments[] = array("project_id"=>$data['project_id'],"task_id"=>"","attachment"=>$i.'_'.$_FILES['attachment_'.$i]['name'],"created_by"=>$data['created_by']);
                unset($data['attachment_'.$i]);
              }
            }

            $query_insert = Tasks::insertGetId($data);
            
            $name_link = '<a href="'.url('profile').'/' . auth()->id() .'">'. auth()->user()->name .'</a>';
            $action_perform = 'Task # '. $query_insert .' Created By '. $name_link;
            $log = new ActivitylogController();
            $log->saveActivityLogs('Task Created' , 'task_created' , $query_insert , auth()->id() , $action_perform);

            for($i=0; $i<sizeof($attachments); $i++){
                $image = $request->file('attachment_'.$i);
                
                $image->move('files/Projects/'.$request->slug.'/'.$query_insert.'/', $attachments[$i]['attachment']);

                $attachments[$i]['task_id'] = $query_insert;
            }

            TaskAttachments::insert($attachments);
            $project = Project::where('id',$data['project_id'])->first();

            $notify = new NotifyController();

            // Notification for assigned staff

            $sender_id = \Auth::user()->id;
            $receiver_id = $data['assign_to'];
            $slug = 'task_details/'.$id;
            $type = 'PM';
            $data = 'data';
            $title = 'New Task Created';
            $icon = 'fas fa-tasks';
            $class = 'btn-success';
            $desc = 'Task created by '.\Auth::user()->name;
            $pm_id = $project->project_manager_id;

            if($receiver_id != 0 && $receiver_id != null){
              $notify->GeneralNotifi($sender_id,$receiver_id,$slug,$type,$data,$title,$icon,$class,$desc);
            }

            // For the project manager
            if($pm_id != 0 && $pm_id != null){
              $notify->GeneralNotifi($sender_id,$pm_id,$slug,$type,$data,$title,$icon,$class,$desc);
            }

            //Notifications code for admins only
            $Aminusers = User::where('user_type','=',1)->where('is_deleted',0)->get();

            foreach ($Aminusers as $key => $value) {
              if($pm_id != 0 && $pm_id != null && $pm_id != $value->id){

                $admin_id = $value->id;
                $notify->GeneralNotifi($sender_id,$admin_id,$slug,$type,$data,$title,$icon,$class,$desc);
              
              }

            }

            $response['message'] = 'Project Task Added Successfully!';
            $response['status_code'] = 200;
            $response['success'] = true;
            return response()->json($response);
        }
    }

    public function readNotification($id){
      $notification = Notification::where('id',$id)->first();
      if($notification){
        $notification->read_at = Carbon::now();
        $notification->save();
        
        $response['message'] = 'Read Successfully!';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
      }else{
        $response['message'] = 'Not Found!';
        $response['status_code'] = 404;
        $response['success'] = false;
        return response()->json($response);
      }
    }

    public function notify($sender,$receiver,$slug,$type,$data,$title,$icon,$class,$desc,$pm_id=0,$pm_desc=''){
      
     
        //if assign to is provided
      if($receiver !=0){
        //user notificaion
        $notify = new Notification;
        $notify->sender_id = $sender;
        $notify->receiver_id = $receiver;
        $notify->slug = $slug;
        $notify->noti_type = $type;
        $notify->noti_data = $data;
        $notify->noti_title = $title;
        $notify->noti_icon = $icon;
        $notify->btn_class = $class;
        $notify->noti_desc = $desc;
  
        if($notify->save()){
          $notify->scopeToMultiDevice($receiver,$title,$desc, $type ,$slug ,$icon,$class);
        }
         //pm notificaion
        $notify = new Notification;
        $notify->sender_id = $sender;
        $notify->receiver_id = $pm_id;
        $notify->slug = $slug;
        $notify->noti_type = $type;
        $notify->noti_data = $data;
        $notify->noti_title = $title;
        $notify->noti_icon = $icon;
        $notify->btn_class = $class;
        $notify->noti_desc = $pm_desc;
  
        if($notify->save()){
          $notify->scopeToMultiDevice($pm_id,$title,$pm_desc, $type ,$slug ,$icon,$class);
        }
      }
       //if assign to is not provided
      else{
        $notify = new Notification;
        $notify->sender_id = $sender;
        $notify->receiver_id = $pm_id;
        $notify->slug = $slug;
        $notify->noti_type = $type;
        $notify->noti_data = $data;
        $notify->noti_title = $title;
        $notify->noti_icon = $icon;
        $notify->btn_class = $class;
        $notify->noti_desc = $pm_desc;
  
        if($notify->save()){
          $notify->scopeToMultiDevice($pm_id,$title,$pm_desc, $type ,$slug ,$icon,$class);
        }
      }
      //admin notification
      $Aminusers = User::where('user_type','=',1)->where('is_deleted',0)->get();
        foreach ($Aminusers as $key => $value) {
          if($pm_id != 0 && $pm_id != $value->id){

            $admin_id = $value->id;
            $notify = new Notification;
            $notify->sender_id = $sender;
            $notify->receiver_id = $admin_id;
            $notify->slug = $slug;
            $notify->noti_type = $type;
            $notify->noti_data = $data;
            $notify->noti_title = $title;
            $notify->noti_icon = $icon;
            $notify->btn_class = $class;
            $notify->noti_desc = $pm_desc;
      
            if($notify->save()){
              $notify->scopeToMultiDevice($admin_id,$title,$pm_desc, $type ,$slug ,$icon,$class);
            }
          }

        }
      return true;
    }
    
    public function updateTasksOrder(Request $request){
        $input = $request->all();

        try{
          foreach ($input['order'] as $key => $value) {
              // Tasks::where('id',$value['id'])->update(['sort_id'=>$value['sort_id'], 'version'=>$value['version']]);
              Tasks::where('id',$value['id'])->update(['sort_id'=>$value['sort_id']]);
          }
          return response()->json(['success'=>true]);
        } catch(Exception $e) {
          $response['message'] = $e->getMessage();
          $response['status_code'] = 500;
          $response['success'] = false;
          return response()->json($response);
        }
    }

    public function my_tasks(){
        $tasks = Tasks::with('taskProject')->where('assign_to',\Auth::user()->id)->get();
        return view('project_manager.myTask',compact('tasks'));
    }
    
    public function get_all_tasks() {

      $data =  Tasks::with('taskCreator')
      ->with('taskProject')
      ->with('taskAssignedTo')
      ->where('assign_to',\Auth::user()->id)
      ->where('task_status','!=','success')
      ->where('task_status','!=','Select')
      ->where('is_deleted',0)
      ->orderBy('id','desc')->get();

      $response['message'] = 'Tasks List';
      $response['status_code'] = 200;
      $response['data'] = $data;
      $response['success'] = true;
      return response()->json($response);
    }

    public function AllTaskLists($id, Request $request) {
      try{
        $data =  Tasks::with('taskCreator')
        ->with('taskProject')
        ->with('taskAssignedTo')
        ->where('task_status','!=','Select')
        ->where('is_deleted',0)->where('project_id',$id)->orderBy('id', 'DESC')->get();

        // if ($request->ajax()) {
        //     return Datatables::of($data)->make(true);
        // }
        $response['message'] = 'Tasks List';
        $response['data'] = $data;
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
      } catch(Exception $e) {
        $response['message'] = $e->getMessage();
        $response['status_code'] = 500;
        $response['success'] = false;
        return response()->json($response);
      }
    }

    public function my_task_details($task_id){
      $task = Tasks::with('taskProject')->with('taskAttachments')->where('id',$task_id)->first();
      return view('project_manager.myTaskDetails',compact('task'));
    }

  public function taskDetails($task_id){
    $task = Tasks::with('taskProject')->with('taskAttachments')->where('id',$task_id)->first();
    // dd($task);
    return view('project_manager.myTaskDetails',compact('task'));
  }

  public function chnageMyTaskStatus(Request $request) {

    if($request->task_status == 'success') {

      DB::table("tasks")->where("id","=",$request->task_id)->update([
        "task_status" => $request->task_status,
        "remarks" => $request->remarks,
      ]);
  
        $response['message'] = 'Task Completed Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }else if($request->task_status == 'default'){ 

      DB::table("tasks")->where("id","=",$request->task_id)->update([
        "task_status" => $request->task_status,
        "started_at" => Carbon::now(),
      ]);
  
        $response['message'] = 'Task Started Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);

    }else{

      $get_tsk_lst = DB::table("tasks")->where("id","=",$request->task_id)->get();
      $strt_time =  $get_tsk_lst[0]->started_at; 
      $wrk_time = $get_tsk_lst[0]->worked_time;

      $end    = Carbon::now();
      $startTime = Carbon::parse($strt_time);
      $endTime = Carbon::parse($end);

      $total_sec = $startTime->diffInSeconds($endTime)  + $wrk_time;

      DB::table("tasks")->where("id","=",$request->task_id)->update([
        "task_status" => $request->task_status,
        "worked_time" => $total_sec,
      ]);
  
        $response['message'] = 'Task Paused Successfully';
        $response['status_code'] = 200;
        $response['success'] = true;
        return response()->json($response);
    }

   
  }

  public function revertTask(Request $request) {
    DB::table('tasks')->where('id',$request->task_id)->increment('reverted_count',1,['task_status' => 'danger', "remarks" => $request->remarks]);
    $response['message'] = 'Task Reverted Successfully';
    $response['status_code'] = 200;
    $response['success'] = true;
    return response()->json($response);
  }

  public function revertedTaskImage(Request $request) {
    
      $data = $request->all();

      if($request->has('file')){
          
        $image = $request->file('file');
        $filenamewithextension = $data['file']->getClientOriginalName();
        //get filename without extension
        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);
        //get file extension
        $extension = $data['file']->getClientOriginalExtension();

        $filenametostore = time().'.'.$extension;
        $filePath = public_path('files/revert_task/');

        $image->move($filePath, $filenametostore);

        echo $filenametostore;
        
    }

  }

  public function getAllTasksByDates(Request $request) {


    if($request->user_id != "") {
      $from = $request->from;
        $to = $request->to;

        $tasks =  Tasks::with('taskCreator')
              ->with('taskProject')
              ->with('taskAssignedTo')
              ->where('task_status','!=','Select')
              ->where('is_deleted',0)
              ->where('due_date','>=',$from)
              ->where('due_date','<=',$to)
              ->where('assign_to',$request->user_id)
              ->orderBy('id','desc')
              ->get();

        $response['message'] = 'Tasks List';
        $response['status_code'] = 200;
        $response['data'] = $tasks;
        $response['success'] = true;
        $response['date_format'] = Session('system_date');
        return response()->json($response);
    } else{
        $from = $request->from;
        $to = $request->to;

        $tasks =  Tasks::with('taskCreator')
              ->with('taskProject')
              ->with('taskAssignedTo')
              ->where('task_status','!=','Select')
              ->where('is_deleted',0)
              ->where('due_date','>=',$from)
              ->where('due_date','<=',$to)
              ->orderBy('id','desc')
              ->get();

        $response['message'] = 'Tasks List';
        $response['status_code'] = 200;
        $response['data'] = $tasks;
        $response['success'] = true;
        $response['date_format'] = Session('system_date');
        return response()->json($response);
    }
  }

  public function getAllOverDueTasks() {

    $today_date = date("Y-m-d"); 

    $tasks =  Tasks::with('taskCreator')
            ->with('taskProject')
            ->with('taskAssignedTo')
            ->whereNotIn('task_status',['Select','success'])
            ->where('due_date','<', $today_date)
            ->where('estimated_time','<', $this->convertSecondsintoHMS('worked_time'))
            ->where('is_deleted',0)
            ->orderBy('id','desc')
            ->get(); 

      $response['message'] = 'Tasks List';
      $response['status_code'] = 200;
      $response['data'] = $tasks;
      $response['success'] = true;
      $response['date_format'] = Session('system_date');
      return response()->json($response);

  }

  public function convertSecondsintoHMS($seconds) {
    $t = round($seconds);
    return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
  }

  public function todaysTasks() {
    try{
      // $tasks = Tasks::where('created_at', '>', Carbon::now()->subDay()->format('Y-m-d'))->where('is_deleted', 0)->get()->toArray();
      $tasks = DB::Table('tasks')
      ->select('tasks.*','users.name as dev','projects.name as project')
      ->leftjoin('projects','projects.id','=','tasks.project_id')
      ->leftjoin('users','users.id', 'tasks.assign_to')
      ->where('tasks.is_deleted', 0)->where('tasks.created_at', '>', Carbon::now()->subDay()->format('Y-m-d'))->get();

      // $exlude_cols = ['project_id', 'assign_to', 'remarks_by', 'created_by', 'updated_by', 'is_deleted', 'deleted_by', 'sort_id'];

      $spreadsheet = new Spreadsheet();
      $sheet = $spreadsheet->getActiveSheet();

      foreach ($tasks->toArray() as $i => $task) {
        $task = json_decode(json_encode($task), true);
        $keys = array_keys($task);
        if($i == 0) {
          foreach ($keys as $j => $k) {
            // if(in_array($k, $exlude_cols)) continue;
            $sheet->getCellByColumnAndRow($j, 1)->setValue($k);
          }
        }
        foreach ($keys as $j => $k) {
          // if(in_array($k, $exlude_cols)) continue;
          if($k == 'task_status') {
            if($task[$k] == 'danger') $task[$k] = 'Pending';
            else if($task[$k] == 'default') $task[$k] = 'Working';
            else if($task[$k] == 'success') $task[$k] = 'Complete';
          }
          $sheet->getCellByColumnAndRow($j, ($i+2))->setValue($task[$k]);
        }
      }

      $writer = new Xlsx($spreadsheet);
      $tasks_folder = 'tasks/daily_list';
      $target_dir = __DIR__.'/../../../../public/files/'.$tasks_folder;
      if (!File::isDirectory($target_dir)) {
        mkdir($target_dir, 0777, true);
      }
      $file = 'Tasks '.Carbon::now()->format('Y-m-d').'.xlsx';
      $target_dir = $target_dir.'/'.$file;
      $writer->save($target_dir);

      echo $file. " Tasks File exported successfully!.<br><br>";
      
      $admins = User::where('user_type', 1)->where('status', 1)->where('is_deleted', 0)->get()->toArray();

      if(!empty($admins)) {
        $ml = new MailController();
        $ml->sendMail('Work Day Tasks List', 'List of tasks added today.', 'dev_testing@mylive-tech.com', $admins, '', '', $file, $tasks_folder);
      }

      echo $file. " Tasks File successfully mailed.<br><br>";
      // unlink($target_dir);
    } catch(Exception $e) {
      // unlink($target_dir);
      echo $e->getMessage();
    }
  }
}