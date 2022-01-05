@extends('layouts.staff-master-layout')
@section('body-content')
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/dropify-master/dist/css/dropify.min.css')}}">

  
<style>

.ui-autocomplete{
z-index: 999999999999999999999;
}
    .theme-bg {
    background: linear-gradient(
-135deg
, #1de9b6 0%, #1dc4e9 100%);
}
.card-i{
    width: 70px;
    height: 70px;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px 0 rgb(0 0 0 / 20%);
    box-shadow: 0 5px 10px 0 rgb(0 0 0 / 20%);
}
.f-22 {
    font-size: 22px;
}
.f-35 {
    font-size: 35px;
}
.w-40p {
    width: 40px!important;
}
.h-40 {
    height: 40px!important;
}
.media {
    border-bottom: 1px solid #eee;
    padding: 6px;
}
.media {
    display: -ms-flexbox;
    display: flex;
    -ms-flex-align: start;
    align-items: flex-start;
}
.f-w-300 {
    font-weight: 300;
}
h5{
    font-size: 18px;
}
h6 {
    font-size: 14px;
}
.f-16 {
    font-size: 16px!important;
}
.f-w-700 {
    font-weight: 700;
}
.tagFill{
    background-color: #3EBFEA;
    color: #fff;
    padding: 3px 16px;
    font-weight: 400;
    margin:5px;
}
.table thead {
    background: #eeeeee;
    color: #97989a;
}
.table thead tr th {
    padding: 10px !important;
}
.table-striped tbody tr:nth-of-type(odd) {
    background-color: #f2fcfe;
}
.table-hover tbody tr:hover {
    color: #212529;
    background-color: #f2fcfe;
}
.icon i{
    color: #fff;
    background: #226291;
    padding: 9px;
    border-radius: 50%;
}
.name13{
    position: absolute;
    padding-top: 8px;
    left: 30px;
    top: -4px;
    padding-bottom: 8px;

}
.icon small{
    position: absolute;
    left: 50px;
    top: 53px;
}
#note {
  font: inherit;
  height: 7rem;
  width: 100%;
  padding: .8rem 1rem
}

.menu {
  background-color: #f3f3f3;
  position: absolute;
}

.menu-item {
  cursor: default;
  padding: 1rem;
}

.menu-item.selected {
  background-color: slateGray;
  color: white;
}

.menu-item:hover:not(.selected) {
  background-color: #fafafa;
}

.ui-menu .ui-menu-item-wrapper {
	position: relative;
	padding: 3px 1em 3px .4em;
}
.ui-menu .ui-menu-divider {
	margin: 5px 0;
	height: 0;
	font-size: 0;
	line-height: 0;
	border-width: 1px 0 0 0;
}
.ui-menu .ui-state-focus,
.ui-menu .ui-state-active {
	margin: -1px;
} 

/* 
@mention dropdown
 */

.atwho-view {
    position:absolute;
    top: 0;
    left: 0;
    display: none;
    margin-top: 18px;
    background: white;
    color: black;
    border: 1px solid #DDD;
    border-radius: 3px;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
    min-width: 120px;
    z-index: 11110 !important;
}

.atwho-view .atwho-header {
    padding: 5px;
    margin: 5px;
    cursor: pointer;
    border-bottom: solid 1px #eaeff1;
    color: #6f8092;
    font-size: 8px;
    font-weight: bold;
}

.atwho-view .atwho-header .small {
    color: #6f8092;
    float: right;
    padding-top: 2px;
    margin-right: -5px;
    font-size: 8px;
    font-weight: normal;
}

.atwho-view .atwho-header:hover {
    cursor: default;
}

.atwho-view .cur {
    background: #3366FF;
    color: white;
}
.atwho-view .cur small {
    color: white;
}
.atwho-view strong {
    color: #3366FF;
}
.atwho-view .cur strong {
    color: white;
    font:bold;
}
.atwho-view ul {
    /* width: 100px; */
    list-style:none;
    padding:0;
    margin:auto;
    max-height: 200px;
    overflow-y: auto;
}
.atwho-view ul li {
    display: block;
    padding: 5px 10px;
    border-bottom: 1px solid #DDD;
    cursor: pointer;
    /* border-top: 1px solid #C8C8C8; */
}
.atwho-view small {
    font-size: smaller;
    color: #777;
    font-weight: normal;
}

#inputcity{
  width: 300px;
  height: 100px;
  background-color: white;
}
.progress-xls{
    height:24px;
}
.progress-xls .progress-bar{
    font-size: 16px;
}
.badge-default{
    width: 82px;
}
    </style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <h3 class="page-title">Dashboard</h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Project Name</a></li>
                        <li class="breadcrumb-item active" aria-current="page"> {{$project->name}} </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>


<input type="hidden" id="project_slug" value="{{$project_slug}}">
<input type="hidden" id="project_id" value="{{$project->id}}">

@if($date_format) 
    <input type="hidden" id="system_date_format" value="{{$date_format}}">
@else
    <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
@endif

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->

    <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                            <li class="nav-item">
                                    <a href="#projectOverview" data-toggle="tab" aria-expanded="false"
                                        class="nav-link rounded-0 active">
                                        <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                        <span class="d-none d-lg-block">Overview</span>
                                    </a>
                                </li>   
                                <li class="nav-item">
                                <a href="#Tasks" data-toggle="tab" aria-expanded="false"
                                    class="nav-link rounded-0">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Tasks</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#Assets" data-toggle="tab" aria-expanded="false"
                                    class="nav-link rounded-0">
                                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Assets</span>
                                </a>
                            </li>
                           
                            <li class="nav-item">
                                <a href="#changeLog" data-toggle="tab" aria-expanded="true"
                                    class="nav-link rounded-0 ">
                                    <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Change log</span>
                                </a>
                            </li>
                           
                            <li class="nav-item" onclick="getAllProjectActivityLogs();">
                                <a href="#activityLogs" data-toggle="tab" aria-expanded="true"
                                    class="nav-link rounded-0 ">
                                    <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Activity logs</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#Files" data-toggle="tab" aria-expanded="false"
                                    class="nav-link rounded-0">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Files</span>
                                </a>
                            </li>
                            <li class="nav-item" onclick="getAllProjectNotes();">
                                <a href="#Notes" data-toggle="tab" aria-expanded="false"
                                    class="nav-link rounded-0">
                                    <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                    <span class="d-none d-lg-block">Notes</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div> 

                <div class="tabsData">
                    <div class="tab-content">

                        <!-- overview tab -->
                        <div class="tab-pane show active" id="projectOverview">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card ">
                                        <div class="row" style="padding-left: 15px;padding-right: 15px;">
                                            <div class="col-md-4 text-center theme-bg">
                                                <div class="p-4">
                                                    <h3><i class="feather icon-clock f-22 w-40p h-40 text-white"></i></h2>
                                                    <h3 class="text-white">0s</h3>
                                                    <h3  class="text-white">Logged Times</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row" style="padding-top:2rem;">
                                                    <div class="col text-center align-self-center">
                                                        <div class="progress progress-xls mb-2">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 38%" aria-valuenow="38" aria-valuemin="0" aria-valuemax="100"> 38%</div>
                                                        </div>
                                                        <!-- <div data-label="20%" class="css-bar mb-0 css-bar-primary css-bar-20">20%</div> -->
                                                        <p>Project Progress</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="row" style="padding-top:2rem;">
                                                    <div class="col text-center align-self-center">
                                                        <div class="progress progress-xls mb-2">
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 56%" aria-valuenow="56" aria-valuemin="0" aria-valuemax="100"> 56%</div>
                                                        </div>
                                                        <!-- <div data-label="20%" class="css-bar mb-0 css-bar-primary css-bar-20">20%</div> -->
                                                        <p>Project Version</p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 p-3">
                                                <div class="media d-flex justify-content-between">
                                                    <div class="photo-table">
                                                        <h6 class="mt-2">Project Name</h6>
                                                    </div>
                                                    <div>
                                                        <div id="page_title">
                                                            <span style="font-size: x-large;" id="title_span">{{$project->name}}</span>&nbsp;&nbsp;
                                                            <span style="font-size: x-large;"><a onclick="editPageTitle()" style="cursor:pointer"><i class="mdi mdi-pencil"></i></a></span>
                                                        </div>
                                                        <div class="row align-items-center" style="display:none" id="edit_title_div">
                                                            <div class="col-9">
                                                                <input id="title_input" class="form-control" style="width: 100%;" type="text" name="" value="">
                                                            </div>
                                                            <div class="col-3">
                                                                <a onclick="saveTitle()" style="cursor:pointer;font-size: x-large;">
                                                                    <i class="fa fa-check" aria-hidden="true"></i></a>&nbsp;
                                                                <a onclick="cancelEdit()" style="cursor:pointer;font-size: x-large;">
                                                                    <i class="fa fa-times " aria-hidden="true"></i></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="media d-flex justify-content-between">
                                                    <div class="photo-table">
                                                        <h6 class="mt-2">Customers</h6>
                                                    </div>
                                                    <div id="assigned_customer">
                                                        <div class="media-body">
                                                            <input type="hidden" id="project_customer_id" value="{{$project->customer_id != null ? $project->customer_id : '-'}}">
                                                            @if($project->projectCustomer == null)
                                                            <label for="example-password-input" id="customer_name" class="col-form-label">---</label>&nbsp;&nbsp;<span
                                                                ><a onclick="editcutomerassign()" style="cursor:pointer"><i
                                                                        class="mdi mdi-pencil"></i></a></span>
                                                            @else
                                                                @if($project->projectCustomer->first_name != null &&  $project->projectCustomer->last_name != null)
                                                                <label for="example-password-input" id="customer_name" class="col-form-label">{{$project->projectCustomer->first_name}} {{$project->projectCustomer->last_name}}</label><span
                                                                    ><a onclick="editcutomerassign()" style="cursor:pointer"><i
                                                                            class="mdi mdi-pencil"></i></a></span>
                                                                @else
                                                                <label for="example-password-input" id="customer_name" class="col-form-label">{{$project->projectCustomer->username}}</label><span
                                                                    ><a onclick="editcutomerassign()" style="cursor:pointer"><i
                                                                            class="mdi mdi-pencil"></i></a></span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="row" id="customer_section" style="display:none">
                                                            <div class="col-9">
                                                                <select class="select2 form-control custom-select" type="search" id="customer_id" name="customer_id" style="width: 100%; height:36px;" required>
                                                                    <option value="">Select</option>
                                                                </select>
                                                                
                                                            </div>
                                                            <div class="col-3" style="padding-top: 5px;">
                                                            <a onclick="saveCustomer()" style="cursor:pointer;"><i
                                                                    class="fa fa-check" aria-hidden="true"></i></a>&nbsp;
                                                            <a onclick="cancelCustomerEdit()" style="cursor:pointer;"><i
                                                                    class="fa fa-times " aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="media d-flex justify-content-between">
                                                    <div class="photo-table">
                                                        <h6>Project Manager</h6>
                                                    </div>
                                                    <div id="assigned_manager">
                                                        <input type="hidden" id="selected_project_manager_id" value="{{$project->project_manager_id != null ? $project->project_manager_id : '-'}}">
                                                        @if($project->projectManager == null)
                                                        <label for="example-password-input" id="manager_name" class="col-form-label">---</label>&nbsp;&nbsp;<span
                                                            ><a onclick="editmanagerassign()" style="cursor:pointer"><i
                                                                    class="mdi mdi-pencil"></i></a></span>
                                                        @else
                                                        <label for="example-password-input" id="manager_name" class="col-form-label">{{$project->projectManager->name}}</label>&nbsp;&nbsp;<span
                                                            ><a onclick="editmanagerassign()" style="cursor:pointer"><i
                                                                    class="mdi mdi-pencil"></i></a></span>
                                                        @endif
                                                    </div>
                                                    <div class="row" id="project_manager_section" style="display:none">
                                                            <div class="col-9">
                                                                <select class="select2 form-control custom-select" type="search" id="project_manager_id" name="project_manager_id" style="width: 100%; height:36px;" required>
                                                                </select>
                                                            </div>
                                                            <div class="col-3" style="padding-top: 5px;">
                                                            <a onclick="saveManager()" style="cursor:pointer;"><i
                                                                    class="fa fa-check" aria-hidden="true"></i></a>
                                                            <a onclick="cancelManagerEdit()" style="cursor:pointer;"><i
                                                                    class="fa fa-times " aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3>Project Description</h3>
                                        </div>
                                        <div class="card-body">
                                            <textarea id="project_desc" class="form-control" cols="30" rows="5">{{$project->description}}</textarea>
                                        </div>
                                        <div class="card-footer">
                                            <button class="btn btn-success btn-sm rounded" onclick="saveProjectDescription()"> <i class="fas fa-check-circle"></i> Save Changes</button>
                                        </div>

                                        <div class="loader_container" id="proj_desc_loader" style="display:none">
                                            <div class="loader"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3>Project Member <a href="" style="float:right;"><i class="mdi mdi-plus-circle f-22"></i></a></h3>
                                                </div>
                                                <div class="card-body text-inline">
                                                        <i class="mdi mdi-account-outline f-35"></i>
                                                        <i class="mdi mdi-account-outline f-35"></i>
                                                        <i class="mdi mdi-account-outline f-35"></i>
                                                        <i class="mdi mdi-account-outline f-35"></i>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Project Statistics</h3>
                                            <hr>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="card border-bottom  border-info">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2>{{$total_tasks}}</h2>
                                                            <h6 class="text-info">Total Tasks</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-info display-6"><i class="ti-notepad"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="card border-bottom border-success">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2>{{$completed_tasks}}</h2>
                                                            <h6 class="text-success">Completed</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-success display-6"><i class="ti-clipboard"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="card border-bottom border-warning">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                        <h2>{{$in_progress_tasks}}</h2>
                                                            <h6 class="text-warning">In Progress</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-warning display-6"><i class="ti-wallet"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <div class="card border-bottom border-danger">
                                                <div class="card-body">
                                                    <div class="d-flex no-block align-items-center">
                                                        <div>
                                                            <h2>{{$pending_tasks}}</h2>
                                                            <h6 class="text-danger">Pending</h6>
                                                        </div>
                                                        <div class="ml-auto">
                                                            <span class="text-danger display-6"><i class="ti-stats-down"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- assets tab -->
                        <div class="tab-pane " id="Assets">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Task Table
                                            
                                        </div>
                                        <div class="card-body">
                                            <div class="col-12 px-0 text-right">
                                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#asset">
                                                    <i class="mdi mdi-plus-circle"></i>&nbsp;Add Asset
                                                </button>  
                                            </div>
                                            <div class="table-responsive mt-3">
                                                <table id="asset-table-list"
                                                    class="table table-striped table-hover w-100 no-wrap asset-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Asset Title</th>
                                                            <th>Template Name</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                </table>                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- change log tab -->
                        <div class="tab-pane" id="changeLog">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header d-flex"> Change Logs <button class="btn btn-primary ml-auto" data-toggle="modal" data-target="#exportLogsModal">Export</button></div>
                                        <div class="card-body">
                                        @foreach($change_logs as $log)
                                            <div class="row" >
                                                <div class="col-md-9">
                                                    <div style="margin-left:50px;border-left:3px solid #E2E2E2;">
                                                            @if( $log->task_type != null )
                                                                <small class="badge bg-danger form-text text-white name13">{{$log->task_type}}  </small>
                                                            @else
                                                                <small class="badge bg-warning form-text text-white name13"> improvement </small>
                                                            @endif
                                                        <div class="icon">
                                                            {{-- <small><i class="fa fa-envelope"></i></small> --}}
                                                            <small><i>{{$log->version}}</i></small>
                                                        </div>
                                                        <div class="pt-3 pl-5">
                                                            <h4 class="p-2">
                                                                {{$log->title}}  @ {{$log->due_date}}
                                                            </h4>
                                                            <hr class="m-0"/>
                                                            <p class="p-2 m-0">
                                                            <?php echo $log->task_description; ?>
                                                            </p>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                                <p> </p>
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- task tab -->
                        <div class="tab-pane" id="Tasks">
                            <div class="row taskTable">


                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            Task Table
                                        </div>
                                        <div class="card-body">
                                            <div class="row ">
                                                <div class="col-md-12 text-right mb-3">
                                                    <button type="button" class="btn btn-success btn-sm rounded" id="newTaskBtn">
                                                        <i class="mdi mdi-plus-circle"></i>&nbsp;Add Task
                                                    </button>
                                                </div>
                                                
                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="start-date">Start Date</label>
                                                            <input type="date" class="form-control" id="start-date">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="end-date">End Date</label>
                                                            <input type="date" class="form-control" id="end-date">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-5">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="selc-st">Status</label>
                                                            <select class="form-control" id="selc-st">
                                                                <option value="">Select Status</option>
                                                                <option value="danger">Pending</option>
                                                                <option value="default">Working</option>
                                                                <option value="success">Complete</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="selc-ver">Version</label>
                                                            <select class="form-control" id="selc-ver">
                                                                <option value="">Select Version</option>
                                                                @foreach($versions as $version)
                                                                    <option value="{{$version->version}}" {{$version->version == $settings->site_version ? 'selected' : '-'}} >{{$version->version}} </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-2 d-flex">
                                                    <button class="btn btn-primary btn-circle mt-auto" onclick="applyTasksFilter()"> <i class="fas fa-search"></i> </button>
                                                </div>
                                            </div>

                                            <div class="table-responsive mt-3">
                                                <table id="project_all_tasks" class="table table-striped w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Order</th>
                                                            <th>Task ID</th>
                                                            <th>Name</th>
                                                            <th>Created By</th>
                                                            <th>Version</th>
                                                            <th>Assigned To</th>
                                                            <th>Start Date</th>
                                                            <th>Due Date</th>
                                                            <th>Priority</th>
                                                            <th>Status</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- add new task div -->
                                <div class="col-md-12 newTask " style="position: absolute;z-index: 3;">
                                    <div class="task-body bg-white" style="margin-top: 58px;float: right;">
                                        <form class="row road-map-form" id="save-task"
                                            action="{{asset('save-project-task')}}" method="POST">
                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                                                <div class="d-flex justify-content-between">
                                                    <h3 class="blue bigger p-3" style="color:#009efb;"></h3>
                                                    <button type="button" style="padding:20px" id="newTaskClose" class="close ml-auto"  aria-hidden="true">×</button>
                                                </div>
                                                <hr>
                                                
                                                <input type="hidden" id="task_id" name="id">                                                    

                                                <div class="form-horizontal row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Version :</label>
                                                        <div class="col-sm-12">
                                                            <input name="version" class="form-control" type="text"
                                                                value="" placeholder="Version"
                                                                list="rm_title_datalist" id="version" />
                                                            <datalist id="rm_title_datalist">
                                                                @foreach($versions as $version)
                                                                    <option value="{{$version->version}}">{{$version->version}} </option>
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Title
                                                            :</label>
                                                        <div class="col-sm-12">
                                                            <input name="title" id="title" class="form-control" type="text"
                                                                value="" placeholder="Title" />
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Status
                                                            :</label>
                                                        <div class="col-sm-12">
                                                            <select name="task_status" id="task_status" class="form-control" style="width:100%" onchange="checkTaskStatus(this);">
                                                                <option value="danger">Pending</option>
                                                                <option value="default">Working</option>
                                                                <option value="success">Complete</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6" style="display: none;">
                                                        <label class="control-label col-sm-12">Completion Time:</label>
                                                        <div class="col-sm-12 d-flex">
                                                            <input type="number" min="0" class="form-control" id="completion_time_hours" placeholder="HH">&nbsp;
                                                            <input type="number" min="0" class="form-control" id="completion_time_mins" placeholder="MM">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6" style="display: none;">
                                                        <label class="control-label col-sm-12">Completion Date :</label>
                                                        <div class="col-sm-12">
                                                            <input type="date" class="form-control" id="completed_at" name="completed_at">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Priority
                                                            :</label>
                                                        <div class="col-sm-12">
                                                            <select name="task_priority" id="task_priority" class="form-control" style="width:100%">
                                                                <option value="" selected disabled>Select</option>
                                                                <option value="Low">Low</option>
                                                                <option value="Normal" selected>Normal</option>
                                                                <option value="High">High</option>
                                                                <option value="Urgent">Urgent</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="row pl-3">
                                                        <div class="form-group col-md-4">
                                                            <label class="control-label col-sm-12">Start Date</label>
                                                            <div class="col-sm-12">
                                                                <input type="date" class="form-control" id="start_date" name="start_date">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <label class="control-label col-sm-12">Due Date </label>
                                                            <div class="col-sm-12">
                                                                <input type="date" class="form-control" id="due_date" name="due_date">
                                                            </div>
                                                        </div>

                                                        <div class="form-group col-md-4">
                                                            <label class="control-label col-sm-12">Estimated Time</label>
                                                            <div class="col-sm-12 d-flex">
                                                                <input type="number" min="0" class="form-control" id="estimated_time_hours" placeholder="HH">&nbsp;
                                                                <input type="number" min="0" class="form-control" id="estimated_time_mins" placeholder="MM">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Assign To :</label>
                                                        <div class="col-sm-12">
                                                        <select class="select form-control"
                                                            id="assign_to" name="assign_to"
                                                            style="height: 36px;width: 100%;">
                                                            <option value="">Select</option>
                                                            @foreach($users as $user)
                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                            @endforeach
                            
                                                        </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Work Technology :</label>
                                                        <div class="col-sm-12">
                                                        <select class="select form-control"  id="work_tech" name="work_tech" style="height: 36px;width: 100%;">

                                                            <option value="">Select</option>
                                                            <option value="Wordpress">Wordpress</option>
                                                            <option value="Shopify">Shopify</option>
                                                            <option value="PHP">PHP</option>
                                                            <option value="JS">JS</option>
                                                            <option value="React Native">React Native</option>
                                                            <option value="Other">Other</option>

                                                        </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Task Type :</label>
                                                        <div class="col-sm-12">
                                                            <select class="select form-control"  id="task_type" name="task_type" style="height: 36px;width: 100%;">
                                                                <option value="">Select</option>
                                                                <option value="bug">Bug</option>
                                                                <option value="improvemenet">Improvement</option>
                                                                <option value="new feature">New Feature</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6" id="ot_div" style="display:none">
                                                        <label class="control-label col-sm-12">Other Tech  :</label>
                                                        <div class="col-sm-12">
                                                            <input type="text" class="form-control" id="other_tech" name="other_tech">
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-6">
                                                        <label class="control-label col-sm-12">Attachment:</label> 
                                                        <div class="col-sm-12">
                                                            <button onclick="listAttachments()" class="btn btn-block btn-warning text-white" type="button">Attachments</button>
                                                            <div class="myattachments">

                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group p-3">
                                                            <label for="desc"> Description </label>
                                                            <textarea id="tsk_desc" class="form-control tinymce" cols="30" rows="10"></textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-group col-md-12">
                                                        <div class="col-sm-offset-4 col-sm-12 text-right">
                                                            <button type="button" class="btn pull-right btn-danger btn-sm rounded text-white" id="newTaskClose2"><i class="fas fa-history"></i> Cancel</button>
                                                            <button type="submit" class="btn btn-success pull-right btn-sm rounded" id="prj_btn"><i class="fas fa-check-circle"></i> Save</button>
                                                        </div>
                                                    </div>

                                                    <br>
                                                    <div class="form-group col-sm-12">
                                                        <div class="form-status"></div>
                                                    </div>

                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                    <div class="loader_container edit_tsk_loader" style="display:none">
                                        <div class="loader"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- activity log tab -->
                        <div class="tab-pane" id="activityLogs">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body" >
                                            <div id="show_activity_logs"></div>
                                        </div>
                                    </div>
                                    <div class="loader_container activity_loader" style="display:none">
                                        <div class="loader"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- files tab -->
                        <div class="tab-pane" id="Files">
                            <div class="card">
                                <div class="card-body">
                                        <div class=" text-right">
                                            <button class="btn btn-success mb-3">
                                                <i class="mdi mdi-plus-circle"></i> Add File
                                            </button>
                                        </div>
                                    <div class="card-columns el-element-overlay">
                                        <div class="card">
                                            <div class="el-card-item ">
                                                <div class="el-card-avatar  el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                                    <a type="button" class="image-popup-vertical-fit"  data-toggle="modal" data-target=".img-edit"> <img src="../assets/images/big/img5.jpg" class="d-block position-relative w-100" alt="user" /> </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="el-card-item ">
                                                <div class="el-card-avatar  el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                                    <a class="image-popup-vertical-fit"  data-toggle="modal" data-target=".img-edit" href="../assets/images/users/1.jpg"> <img src="../assets/images/users/1.jpg" class="d-block position-relative w-100" alt="user" /> </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="el-card-item ">
                                                <div class="el-card-avatar  el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                                    <a class="image-popup-vertical-fit"  data-toggle="modal" data-target=".img-edit" href="../assets/images/users/2.jpg"> <img src="../assets/images/users/2.jpg" class="d-block position-relative w-100" alt="user" /> </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="el-card-item ">
                                                <div class="el-card-avatar  el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                                    <a class="image-popup-vertical-fit"  data-toggle="modal" data-target=".img-edit" href="../assets/images/big/img4.jpg"> <img src="../assets/images/big/img4.jpg" class="d-block position-relative w-100" alt="user" /> </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="el-card-item ">
                                                <div class="el-card-avatar  el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                                    <a class="image-popup-vertical-fit"  data-toggle="modal" data-target=".img-edit" href="../assets/images/big/img2.jpg"> <img src="../assets/images/big/img2.jpg" class="d-block position-relative w-100" alt="user" /> </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="el-card-item ">
                                                <div class="el-card-avatar  el-overlay-1 w-100 overflow-hidden position-relative text-center">
                                                    <a class="image-popup-vertical-fit"  data-toggle="modal" data-target=".img-edit" href="../assets/images/users/1.jpg"> <img src="../assets/images/big/img1.jpg" class="d-block position-relative w-100" alt="user" /> </a>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        

                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <!-- notes tab -->
                        <div class="tab-pane" id="Notes">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header"> Notes </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class="form-control" id="notes_search" placeholder="search notes">
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <button type="button" class="btn btn-success btn-sm rounded" data-toggle="modal" data-target="#add_notes">
                                                        <i class="mdi mdi-plus-circle"></i>&nbsp;Add Note
                                                    </button>
                                                </div>
                                            </div>
                                            

                                            <div class="col-12 mt-2" id="project_notes">

                                            </div>
                                            <div class="loader_container notes_loader" id="notes_loader" style="display:none">
                                                <div class="loader"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add asset manager modal -->
    <div class="modal fade" id="asset" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Asset</h4>
                    <button type="button" onclick="closeAsset()" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data" action="{{asset('/save-asset')}}" method="post">
                    <div class="modal-body">
                            <div class="form-row">
                                <div class="col-md-12 form-group">
                                    <div class="form-group">
                                        <label>Asset Template</label>
                                        <select class="select form-control" onchange="getFields(this.value)" id="form_id" name="form_id" required></select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row" id="templateTitle" style="display:none;">
                                <div class="col-md-12 form-group">
                                    <div class="form-group">
                                        <label>Asset Title</label>
                                            <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="form-fields"></div>            
                    </div>
                    <div class="modal-footer text-right" >
                        <button type="button" class="btn btn-danger my-3" data-dismiss="modal" > <i class="fas fa-times-circle"></i> Close </button>
                        <button type="submit" class="btn btn-success my-3" > <i class="fas fa-check-circle"></i> Save </button>                    
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- update asset modal -->
    <div id="update_asset_modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info p-2">
                    <span>
                        <h4 style="color:#fff !important;" id="headinglabel"> Update - <span id="modal-title"></span>  </h4>
                    </span>
                </div>
                <div class="modal-body">
                    <form id="update_assets_form" enctype="multipart/form-data" onsubmit="return false">
                        <div class="form-group">
                            <label for="select">Asset Title</label> <span class="text-danger">*</span>
                            <input class="form-control" type="text" id="up_asset_title" required>
                            <input class="form-control" type="hidden" id="asset_title_id" required>
                            
                        </div>
                        <div class="input_fields"></div>
                        <div class="address_fields"></div>
                        <div class="form-group text-right mt-3">
                            <button class="btn btn-rounded btn-success" onclick="updateAssets()" id="sve" type="submit">Save</button>
                            <button class="btn btn-rounded btn-danger" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                    <div class="loader_container">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 
    <!-- add notes -->
    <div class="modal fade" id="add_notes" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="notesLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="notesLargeModalLabel">Notes</h4>
                    <button type="button" onclick="noteClose()" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="save_ticket_note">
                        <div class="row">
                            <div class="col-12 d-flex py-2">
                                <label for="">Notes</label>
                                <div class="ml-4">
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(255, 230, 177); cursor: pointer;" onclick="chnageBGColor('rgb(255, 230, 177)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(218, 125, 179); cursor: pointer;" onclick="chnageBGColor('rgb(218, 125, 179)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(195, 148, 255); cursor: pointer;" onclick="chnageBGColor('rgb(195, 148, 255)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(151, 235, 172); cursor: pointer;" onclick="chnageBGColor('rgb(151, 235, 172)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(229, 143, 143); cursor: pointer;" onclick="chnageBGColor('rgb(229, 143, 149)')"></span>
                                </div>
                            </div>

                            <div class="col-12 py-2">
                                <div class="form-group">
                                    <textarea  name="note" id="note" class="form-control" rows="7" required></textarea>
                                    <div id="menu" class="menu" role="listbox"></div>

                                </div>
                                 <!-- <textarea  placeholder="@mention someone"></textarea> -->
                            </div>
                            <div class="col-12 text-right pt-3">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fas fa-times-circle"></i> Close</button>    
                                <button type="button" onclick="saveProjectNotes()" class="btn btn-success mr-2"> <i class="fas fa-check-circle"></i> Save</button>
                            </div>
                        </div>
                    </form>
                    <div class="loader_container" id="proj_notes_loader" style="display:none">
                        <div class="loader"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Image Edit -->
    <div class="modal fade img-edit" id="" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="notesLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header  align-items-center">
                    <h4 class="modal-title" id="notesLargeModalLabel">Attachment Details</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body text-center">
                   <div class="row">
                       <div class="col-md-12 mb-3">
                            <img  src="../assets/images/big/sectigo.png"  alt="" width="150">
                       </div>
                       <div class="col-md-12">
                            <button class="btn btn-primary"> Edit Image </button>
                       </div>
                   </div>


                </div>
            </div>
        </div>
    </div>

    <!-- edit notes -->
    <div class="modal fade" id="edit_notes" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="notesLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="notesLargeModalLabel">Notes</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="save_ticket_note" action="{{asset('save-ticket-note')}}" method="post">
                        <div class="row">
                            <div class="col-12 d-flex py-2">
                                <label for="">Notes</label>
                                <div class="ml-4">
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(255, 230, 177); cursor: pointer;" onclick="chnageBGColor('rgb(255, 230, 177)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(218, 125, 179); cursor: pointer;" onclick="chnageBGColor('rgb(218, 125, 179)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(195, 148, 255); cursor: pointer;" onclick="chnageBGColor('rgb(195, 148, 255)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(151, 235, 172); cursor: pointer;" onclick="chnageBGColor('rgb(151, 235, 172)')"></span>
                                    <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(229, 143, 143); cursor: pointer;" onclick="chnageBGColor('rgb(229, 143, 149)')"></span>
                                </div>
                            </div>

                            <div class="col-12 py-2">
                                <div class="form-group">
                                    <input type="hidden" id="note_id">
                                    <textarea name="note" id="edit_note_field" class="form-control " rows="7" required></textarea>
                                    
                                </div>
                            </div>
                            <div class="col-12 text-right pt-3">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="fas fa-times-circle"></i> Close</button>
                                <button type="button" onclick="updateProjectNotes()" class="btn btn-success mr-2"> <i class="fas fa-check-circle"></i> Save</button>
                            </div>
                        </div>
                    </form>
                    <div class="loader_container" id="update_proj_notes_loader" style="display:none">
                        <div class="loader"></div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="attachmentsModal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="attachmentsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Attachments</h5>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-4 mb-3">
                        <input type="file" id="dropi-0" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" data-dismiss="modal"> Done</button>
                </div>
            </div>
        </div>
    </div>  


    <div class="modal fade" id="exportLogsModal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="exportLogsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Export Change Logs Format</h5>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <table id="changeLog_table" class="table table-striped table-hover text-center table-bordered no-wrap w-100" role="grid" aria-describedby="zero_config_info">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Version</th>
                                <th>Title</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Type</th>
                                <th>Assigned To</th>
                                <th>Completed At</th>
                                <th>Completion Time</th>
                                <th>Due Date</th>
                                <th>Estimated Time</th>
                                <th>Overdue</th>
                                <th>Other Tech</th>
                                <th>Remarks</th>
                                <th>Started At</th>
                                <th>Work Tech</th>
                                <th>Worked Time</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" ></script>
<script src="{{asset('/assets/dist/js/jquery-ui.triggeredAutocomplete.js')}}"></script>
<script src="https://raw.githubusercontent.com/podio/jquery-mentions-input/master/jquery.mentionsInput.js"></script>

<script>
    let customers =  {!! json_encode($customers) !!};
    let users =  {!! json_encode($users) !!};
    let tasks =  {!! json_encode($tasks) !!};
    let change_logs_list = {!! json_encode($change_logs) !!};

    var update_project_title = "{{url('update-project-title')}}";
    var update_customer = "{{url('update-project-customer')}}";
    var update_project_manager = "{{url('update-project-manager')}}";
    var save_project_desc = "{{url('save_project_desc')}}";
    var save_project_notes = "{{url('save_project_notes')}}";
    var get_project_notes = "{{url('get_project_notes')}}";
    var user_photo_path = "{{asset('files/user_photos')}}";
    var del_project_notes = "{{url('del_project_notes')}}";
    var update_project_notes = "{{url('update_project_notes')}}";
    var get_activity_logs = "{{url('get_activity_logs')}}";
    var get_all_tasks = "{{url('task_lists')}}";
    var task_detail = "{{url('task-details')}}";
    let g_dropi_index = 1;
    var g_attachments = [];
    var g_attachments_delete = [];
    var delete_task = "{{url('delete-task')}}";
    var get_task_byid = "{{url('get-task-byid')}}";

    // asset
    let templates_fetch_route = "{{asset('/get-asset-templates')}}";
    let get_assets_route = "{{asset('/get-assets')}}";
    var general_info_route = "{{asset('/general-info')}}";
    let del_asset_route = "{{asset('/delete-asset')}}";
    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";
    var tags_project_notes="{{url('tags_project_notes')}}";
    let asset_project_id = $('#project_id').val();
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

{{-- Dropify Files --}}
<script src="{{asset('assets/extra-libs/dropify-master/dist/js/dropify.js')}}"></script>

{{-- Export Files In Excel --}}
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>

{{-- <link href="https://cdn.datatables.net/1.10.4/css/jquery.dataTables.css" rel="stylesheet"/> --}}
<link href="https://cdn.datatables.net/rowreorder/1.2.8/css/rowReorder.dataTables.min.css" rel="stylesheet"/>
<script src="https://cdn.datatables.net/rowreorder/1.2.8/js/dataTables.rowReorder.min.js"></script>

{{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css"> --}}
{{-- <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script> --}}

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script src="{{asset('assets/libs/moment/moment.js')}}"></script>

<!-- <script src="{{asset('public/js/project_detail/project_detail.js').'?ver='.rand()}}"></script> -->
@include('js_files.project_detail.project_detailJs')

<script src="{{asset('assets/libs/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/assets/libs/tinymce/tinymce.min.js')}}"></script>

<!-- for textarea @ dropdown  -->
<!-- <script src="{{asset('public/js/atwho/atwho.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/atwho/caret.js').'?ver='.rand()}}"></script> -->
@include('js_files.atwho.atwhoJs')
@include('js_files.atwho.caretJs')


<script>
    $(document).ready(function(){
        var clr = $("#name13").text();
        if(clr == 'bug'){
            $("#name13").removeClass("badge-warning badge-success");
            $("#name13").addClass("badge-danger");
            console.log("bug", clr);
        } else if(clr == 'improvement'){
            $("#name13").removeClass("badge-danger badge-success");
            $("#name13").addClass("badge-warning");
            console.log("improvement", clr);
        } else{
            $("#name13").removeClass("badge-danger  badge-warning" );
            $("#name13").addClass("badge-success");
            console.log("success", clr);
        }
    });

    $(function () {
        if ($(".tinymce").length > 0) {
            tinymce.init({
                selector: ".tinymce",
                theme: "modern",
                height: 300,
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
            });
        }
    });
</script>
<script>

    $('.daterange').daterangepicker();
    
    $('.newTask').hide();
    
    
    $("#newTaskClose").click(function(){
        $('.newTask').hide('1000');
        $('.taskTable').css('height','auto');
        $('.taskHeader').css('height','auto');
    });
    $("#newTaskClose2").click(function(){
        $('.newTask').hide('1000');
        $('.taskTable').css('height','auto');
        $('.taskHeader').css('height','auto');
    });

   $('#dropi-0').dropify();
</script>
@endsection