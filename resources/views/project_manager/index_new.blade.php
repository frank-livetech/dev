@extends('layouts.master-layout-new')
@section('body')

<link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" />
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" />

<style>
    .hide {
        display: none;
    }

    /* .dt-buttons {
        position:absolute !important;
        top:0px !important;
    } */
    .bg-info {
        background-color: #009efb !important;
    }

    .bg-warning {
        background-color: #ffbc34 !important;
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Customer Lookup</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item"> Project Manager
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <!-- ============================================================== -->
        <!-- Left Part -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-body card_shadow">

                        <div class="p-1 card-title">
                            <a class="waves-effect waves-light btn btn-success d-block" onclick="showProjectModel()" style="color:#fff;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Add New Project</a>
                        </div>

                        <div class="d-flex justify-content-between">
                            <small class="p-3 grey-text text-lighten-1 db" style="padding: 0.3rem!important;"><strong>Folders</strong></small>

                            <a href="javascript:void(0)" type="button" onclick="showFolderModel()" class=" waves-effect" data-bs-toggle="modal" data-bs-target="#default">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg> Add Folder
                            </a>

                        </div>

                        <div class="col-12 mt-1">
                            <ul class="list-group">
                                @foreach($projectsfolder as $folder)
                                    <li class="list-group-item" id="main_folder_{{$folder->id}}">
                                        <div class="d-flex justify-content-between">
                                            <div class="icon_text">
                                                <svg data-bs-toggle="collapse" href="#folder_{{$folder->id}}" role="button" aria-expanded="false" aria-controls="folder_{{$folder->id}}" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                                </svg>
                                                {{$folder->name}}
                                            </div>
                                            <div class="action_btn">
                                                <svg onclick="editFolder({{$folder->id}});" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                                <svg onclick="removeFolder({{$folder->id}})" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </div>
                                        </div>
                                    </li>

                                    <div class="collapse mb-1" id="folder_{{$folder->id}}">
                                        @foreach($projects as $project)
                                            @if($folder->id == $project->folder_id)
                                                <div class="mt-1 ms-2" id="project_{{$project->id}}">
                                                    <li class="list-group-item bg-light">
                                                        <div class="d-flex justify-content-between">
                                                            <div class="name">
                                                                <a href="{{asset('roadmap')}}/{{$project->project_slug}}"> {{$project->name }} </a>
                                                            </div>
                                                            <div class="action_btn">
                                                                <svg onclick="editProject({{$project->id}});" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="blue" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2">
                                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                                </svg>
                                                                <svg onclick="removeProject({{$project->id}})" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash">
                                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </ul>
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
                                                    <span class="text-info font-weight-bold">All</span>
                                                </div>
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
                                                    <span class="text-warning font-weight-bold">Pending</span>
                                                </div>
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
                                                    <span class="text-success font-weight-bold">Completed</span>
                                                </div>
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
                                                    <span class="text-primary font-weight-bold">Working </span>
                                                </div>
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
                                                    <span class="text-danger font-weight-bold">Overdue</span>
                                                </div>
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
                                                    <span class="text-dark font-weight-bold text-dark">Customer Demands</span>
                                                </div>
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
    </div>


    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="addTaskModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title text-white">Add Task</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                                                <input id="task" type="text" placeholder="Task" class="form-control" name="task" required>
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
                    <button class="btn btn-danger" data-dismiss="modal"><i class="flaticon-cancel-12"></i>
                        Discard</button>
                    <button class="btn btn-info add-tsk" disabled>Add Task</button>
                    <button class="btn btn-success edit-tsk">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- project folder -->
    <div class="modal fade text-start" id="new-project-modal" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1"> Add New Project </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="new-project-add" action="{{asset('save-project')}}" method="Post">
                        <!-- <legend>Form</legend> -->
                        <input type="hidden" name="id" id="pro_id">
                        <fieldset>
                            <div class="form-group">

                                <div class="col-sm-12">
                                    <label class="control-label ">Project Name</label>
                                    <input type="text" class="form-control" id="project_name" name="name" required>
                                </div>
                            </div>
                            <div class="form-group mt-1">
                                <div class="col-sm-12">
                                    <label class="control-label ">Select Project
                                        Folder</label>
                                    <select id="folder_id" name="folder_id" class="select2 form-control custom-select" style="width: 100%; height:56px;" required>
                                        <option value="">Select</option>
                                        @foreach($projectsfolder as $projectfolder)
                                        <option value="{{$projectfolder->id}}">{{$projectfolder->name}}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>

                            <div class="form-group mt-1">
                                <div class="col-sm-12">
                                    <label class="control-label ">Select Project
                                        Type<span id="save-type" style="display :none; color:red;">Title cannot
                                            be
                                            empty </span></label>
                                    <select id="project_type" name="project_type" class="select2 form-control custom-select" style="width: 100%; height:56px;" required>
                                        <option value="">Select</option>
                                        <option value="external">External</option>
                                        <option value="internal">Internal</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <div class="text-right col-md-12 mt-1">
                            <button class="btn btn-primary rounded ml-3" id="done_btn" type="submit"> <i class="mdi mdi-check-circle"></i> Save</button>
                            <button id="proces_btn" type="button" style="display:none" class="btn btn-primary ml-3" disabled> <i class="fas fa-circle-notch fa-spin"></i> Processing</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- folder modal -->
    <div class="modal fade text-start" id="add-folders" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel1"> Add Folder </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="save_folder" action="{{asset('save-folder')}}" method="Post">
                        <input type="hidden" name="id" id="fld_id">
                        <div class="form-group">
                            <label for="emailaddress1">Title</label>
                            <input class="form-control" type="text" name="name" id="title" required>
                        </div>

                        <button class="btn btn-primary waves-effect mt-1" id="fldr_btn" type="submit"> <i class="mdi mdi-check-circle"></i> Save</button>
                        <button id="process_btn" type="button" style="display:none" class="btn btn-primary mt-1" disabled> <i class="fas fa-circle-notch fa-spin"></i> Processing</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@9')}}"></script>

<script src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
@include('js_files.project_manager.indexJs')
<script>
    $('.buttons-copy').addClass('btn btn-light');
</script>
@endsection