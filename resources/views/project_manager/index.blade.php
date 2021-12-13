@extends('layouts.staff-master-layout')
@section('body-content')

<link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css"/>
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css"/>

<style>
    .hide {
        display:none;
    }
    .dt-buttons {
        position:absolute !important;
        top:0px !important;
    }
    .bg-info {
    background-color: #009efb !important;
}
.bg-warning {
    background-color: #ffbc34!important;
}
</style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h3 class="page-title">Dashboard</h3>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Project Manager</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Left Part -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-12 col-lg-4">
            <div class="card">
                <div class="card-body card_shadow">

                    <div class="p-3 card-title">
                        <a class="waves-effect waves-light btn btn-success d-block" onclick="showProjectModel()" style="color:#fff;"><i class="mdi mdi-plus-circle"  style="padding-right:3px;"></i>&nbsp;Add New Project</a>
                    </div>

                    <div class="row" style="display:flex">
                        <div class="col-md-6" style="display: flex;">
                            <small class="p-3 grey-text text-lighten-1 db"
                                style="padding: 0.3rem!important;"><strong>Folders</strong></small>
                        </div>
                        <div class="col-md-6 btn-list">

                            <a type="submit" onclick="showFolderModel()">+ Add Folder</a>

                        </div>
                    </div>

                    <div class="dd myadmin-dd" id="nestable-menu">
                        <ol class="dd-list">
                            @foreach($projectsfolder as $folder)
                            <!--dd-item dd-collapsed-->
                                <li class="dd-item dd-collapsed" data-id="{{$folder->id}}">
                                    <a class="dd-handle collapsed-back">
                                        <label class="m-0">{{$folder->name}}</label>
                                        <div class="float-right">
                                            <i class="fas fa-edit" onclick="editFolder(this);"></i>
                                            <i class="fas fa-trash text-danger" onclick="removeFolder(this)"></i>
                                        </div>
                                    </a>
                                    
                                    @foreach($projects as $project)
                                        @if($folder->id == $project->folder_id)
                                            <ol class="dd-list">
                                                <li class="dd-item" data-id="{{$project->id}}">
                                                    <div class="dd-handle">
                                                        <a href="{{asset('roadmap')}}/{{$project->project_slug}}">{{$project->name }}</a>
                                                        <div class="float-right">
                                                            <i class="fas fa-edit" onclick="editProject(this, '{{$project->id}}');"></i>
                                                            <i class="fas fa-trash text-danger" onclick="removeProject(this)"></i>
                                                        </div>
                                                    </div>
                                                </li>              
                                            </ol>
                                        @endif
                                    @endforeach
                                </li>
                            @endforeach
                                           
                        </ol>
                    </div>
                    <div class="loader_container" id="folder_card">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>

            <div class="card p-2 card_shadow">
                <h3 class="font-weight-bold">Free Staff</h3>
                <div class="table-responsive">
                    <table class="table table-hover text-center table-striped table-bordered w-100" id="free_staff_tble">
                        <thead class="bg-info text-white">
                            <tr>
                                <th>Sr</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="loader_container" style="display:none" id="free_staff_loader">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-lg-8">

            <div class="col-12 p-0">
                <div class="card">
                    <div class="card-body card_shadow">
                        <h4 class="card-title">Tasks </h4>

                        <div class="row mt-3">
                            <div class="form-group col-6">
                                <select class="form-control" name="users" id="users">
                                    <option value="">All</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-6">
                                <select class="form-control" name="projects" id="projects">
                                    <option value="">All</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->name}}">{{$project->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row ml-2">
                            <div class="custom-control custom-radio">
                                <!-- <label for="customRadio">From</label> -->
                                <input type="radio" id="today" onclick="filterData('today')" name="customRadio" class="custom-control-input" checked>
                                <label class="custom-control-label" for="today">Today</label>
                            </div>
                            <div class="custom-control custom-radio ml-3">
                                <!-- <label for="customRadio">To</label> -->
                                <input type="radio" id="date_range" onclick="filterData('date_range')" name="customRadio" class="custom-control-input">
                                <label class="custom-control-label" for="date_range">Date Range</label>
                            </div>
                        </div>

                        <div id="daterangediv" style="display:none">
                            <div class="row">
                                <div class="col-md-5"><input type="date" class="form-control" id="from"></div>
                                <div class="col-md-5"><input type="date" class="form-control" id="to"></div>
                                <div class="col-md-2"><button onclick="getTaskResults()" class="btn btn-primary btn-circle"><i class="fas fa-search"></i> </button></div>
                            </div>
                        </div>

                        <div class="row mt-3">

                            <div class="col pr-0" style="cursor:pointer">
                                <div class="box card p-2 rounded border-info text-center" id="all_card">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <span style="font-size:1.5rem" id="all_counts">0</span> <br>
                                                <span class="text-info font-weight-bold">All</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col pr-0" style="cursor:pointer">
                                <div class="box card p-2 rounded border-warning text-center" id="pending_card">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <span style="font-size:1.5rem" id="pending_counts">0</span> <br>
                                                <span class="text-warning font-weight-bold">Pending</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pr-0" style="cursor:pointer">
                                <div class="box card p-2 rounded border-success text-center" id="completed_card">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <span style="font-size:1.5rem" id="completed_counts">0</span> <br>
                                                <span class="text-success font-weight-bold">Completed</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col pr-0" style="cursor:pointer">
                                <div class="box card p-2 rounded border-primary text-center" id="working_card">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <span style="font-size:1.5rem" id="working_tasks">0</span> <br>
                                                <span class="text-primary font-weight-bold">Working </span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col pr-0" style="cursor:pointer">
                                <div class="box card p-2 rounded border-danger text-center" onclick="overDueTasks()">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <span style="font-size:1.5rem">{{$overdue_taks}}</span> <br>
                                                <span class="text-danger font-weight-bold">Overdue</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <div class="box card p-2 rounded border-dark text-center" style="padding:0.5rem !important">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <span style="font-size:1.5rem" id="working_tasks">{{$external_project}}</span> <br>
                                                <span class="text-dark font-weight-bold text-dark">Customer Demands</span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" style="cursor:pointer" onclick="getFreeStaffDetail()">
                                <div class="box card p-2 rounded border-danger text-center" style="padding:0.5rem !important">
                                    <div class="card-body" style="padding:0.5rem !important">
                                        <div class="row">
                                            <div class="col-12">
                                                <?php
                                                     $free_staff_count = sizeof($free_staff);
                                                    // foreach($free_staff as $user){
                                                    //     if($user->free_staff_tasks == 0){
                                                    //         $free_staff_count =  $free_staff_count + 1;
                                                    //     }
                                                    // }                                 
                                                ?>
                                                <span style="font-size:1.5rem" id="working_tasks">{{$free_staff_count}}</span> <br>
                                                <span class="text-danger font-weight-bold "> Free Staff</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="loader_container" id="tsk_card">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">

                <div class="card-body card_shadow">
                    <div class="table-responsive">
                        <table class="table table-hover text-center table-striped table-bordered w-100" id="tsk_tble">
                            <thead class="bg-info text-white">
                                <tr>
                                    <th>Sr</th>
                                    <th>Task Id</th>
                                    <th>Task Title</th>
                                    <th>Project Name</th>
                                    <th>Version</th>
                                    <th>Task Duration</th>
                                    <th>Created By</th>
                                    <th>Assign To</th>
                                    <th class="hide">Status</th>
                                    <th>Status</th>
                                    <th>Worked Time</th>
                                    <th>Created At</th>
                                    <th class="hide">overdue</th>
                                    <th class="hide">assginto</th>
                                    <th>Task Priority</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="loader_container" id="all_tsks">
                            <div class="loader"></div>
                        </div>

                    </div>
                
                </div>

            </div>

        </div>
    </div>

    <!-- Modal -->
    <div id="new-project-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="blue bigger" Style="color:#009efb">Add New Project <span class="bulk_loader"></span></h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12">

                            <form id="new-project-add" action="{{asset('save-project')}}" method="Post">
                                <!-- <legend>Form</legend> -->
                                <fieldset>
                                    <div class="form-group">

                                        <div class="col-sm-12">
                                            <label class="control-label ">Project Name</label>
                                            <input type="text" class="form-control" id="project_name" name="name"
                                                required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label ">Select Project
                                                Folder</label>
                                            <select id="folder_id" name="folder_id"
                                                class="select2 form-control custom-select"
                                                style="width: 100%; height:56px;" required>
                                                <option value="">Select</option>
                                                @foreach($projectsfolder as $projectfolder)
                                                <option value="{{$projectfolder->id}}">{{$projectfolder->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <label class="control-label ">Select Project
                                                Type<span id="save-type" style="display :none; color:red;">Title cannot
                                                    be
                                                    empty </span></label>
                                            <select id="project_type" name="project_type"
                                                class="select2 form-control custom-select"
                                                style="width: 100%; height:56px;" required>
                                                <option value="">Select</option>
                                                <option value="external">External</option>
                                                <option value="internal">Internal</option>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="text-right col-md-12">
                                    <button class="btn btn-sm btn-success rounded ml-3" id="done_btn" type="submit"> <i class="mdi mdi-check-circle"></i> Save</button>
                                    <button id="proces_btn" type="button" style="display:none" class="btn btn-sm rounded btn-success ml-3" disabled> <i class="fas fa-circle-notch fa-spin"></i> Processing</button>

                                </div>

                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog"  data-backdrop="static"
        aria-labelledby="addTaskModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white">Add Task</h5>
                    <button type="button" class="close text-white" data-dismiss="modal"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="compose-box">
                        <div class="compose-content" id="addTaskModalAdd FolderTitle">
                            <form>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="d-flex mail-to mb-4">
                                            <div class="w-100">
                                                <input id="task" type="text" placeholder="Task"
                                                    class="form-control" name="task" required>
                                                <span class="validation-text"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex  mail-subject mb-4">
                                    <div class="w-100">
                                        <div id="taskdescription" class=""></div>
                                        <span class="validation-text"></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal"><i
                            class="flaticon-cancel-12"></i>
                        Discard</button>
                    <button class="btn btn-info add-tsk" disabled>Add Task</button>
                    <button class="btn btn-success edit-tsk">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div id="add-folders" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="text-center bg-info p-3">
                    <button type="button" class="close ml-auto" data-dismiss="modal" style="color:#fff;"
                        aria-hidden="true">×</button>
                    <a class="text-success">

                        <span><img class="mr-2" src="https://mylive-tech.com/framework/files/brand_files/logo.png" alt=""
                                height="50"></span>
                        <h2 style="color:#fff">Add Folder 123</h2>

                    </a>
                </div>
                <div class="modal-body">
                    <form id="save_folder" action= "{{asset('save-folder')}}"
                    method="Post">

                    <div class="form-group">
                        <label for="emailaddress1">Title</label>
                        <input class="form-control" type="text" name="name" id="title" required>
                    </div>

                    <button class="btn btn-sm btn-success rounded" id="fldr_btn" type="submit"> <i class="mdi mdi-check-circle"></i> Save</button>
                    <button id="process_btn" type="button" style="display:none" class="btn btn-sm rounded btn-success" disabled> <i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                
                </form>
                </div>

            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <!-- /.modal -->
</div>
<style>
    .btn-list>a:hover {
        color: #009efb;

    }

    /*.dd{*/
    /*        top: -25px;*/
    /*}*/

</style>
 @endsection
@section('scripts')
<script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@9')}}"></script>

<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
@include('js_files.project_manager.indexJs')

@endsection
