@extends('layouts.staff-master-layout')
@section('body-content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <h3 class="page-title">Dashboard</h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">My Task</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- <link rel="stylesheet" href="assets/css/colorbox.min.css"> -->

<div class="container-fluid">
    <!-- ============================================================== -->
    <!-- Start Page Content -->
    <!-- ============================================================== -->
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <!--<h4 class="card-title"></h4>-->
                    <div class="row">
                        <div class="col-md-6">
                            <div id="page_title">
                                <span style="font-size: xx-large;"
                                    id="title_span">{{$project->name}}</span>&nbsp;&nbsp;<span
                                    style="font-size: xx-large;"><a onclick="editPageTitle()" style="cursor:pointer"><i
                                            class="mdi mdi-pencil"></i></a></span>

                            </div>
                            <div class="row" style="display:none" id="edit_title_div">

                                <div class="col-md-5" style="padding-left: 0px !important;">
                                    <input id="title_input" class="form-control" style="width: 100%;" type="text"
                                        name="" value="">
                                </div>
                                <div class="col-md-3" style="padding-left: 0px !important;">
                                    <a onclick="saveTitle()" style="cursor:pointer;font-size: xx-large;"><i
                                            class="fa fa-check" aria-hidden="true"></i></a>&nbsp;
                                    <a onclick="cancelEdit()" style="cursor:pointer;font-size: xx-large;"><i
                                            class="fa fa-times " aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button type="button" style="float:right" class="btn btn-primary" data-toggle="modal"
                                data-target="#asset-modal"><i class="fa fa-key"
                                    aria-hidden="true"></i>&nbsp;Add Asset</button>
                            <!--<button type="button" style="float:right;margin-right: 2px;" class="btn btn-primary"-->
                            <!--    data-toggle="modal" data-target="#add-new-task"><i class="fa fa-plus"-->
                            <!--        aria-hidden="true"></i>&nbsp;Add New Task</button>-->
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 col-lg-3">
                            <div id="assigned_customer">
                                <label for="example-password-input" class="col-form-label"
                                    style="font-weight: bold;">Customer : </label>
                                @if($project->projectCustomer == null)
                                <label for="example-password-input" id="customer_name" class="col-form-label"
                                    style="font-weight: bold;">---</label>&nbsp;&nbsp;<span><a
                                        onclick="editcutomerassign()" style="cursor:pointer"><i
                                            class="mdi mdi-pencil"></i></a></span>
                                @else
                                <label for="example-password-input" id="customer_name" class="col-form-label"
                                    style="font-weight: bold;">{{$project->projectCustomer->name}}</label>&nbsp;&nbsp;<span><a
                                        onclick="editcutomerassign()" style="cursor:pointer"><i
                                            class="mdi mdi-pencil"></i></a></span>
                                @endif


                            </div>
                            <div class="form-group row" id="customer_section" style="display:none">
                                <label for="example-password-input" class="col-md-3 col-form-label"
                                    style="font-weight: bold;">Customer</label>
                                <div class="col-md-6">
                                    <select class="select2 form-control custom-select" type="search" id="customer_id"
                                        name="customer_id" style="width: 100%; height:36px;" required>

                                    </select>

                                </div>
                                <div class="col-md-3" style="padding-top: 5px;">
                                    <a onclick="saveCustomer()" style="cursor:pointer;"><i class="fa fa-check"
                                            aria-hidden="true"></i></a>&nbsp;
                                    <a onclick="cancelCustomerEdit()" style="cursor:pointer;"><i class="fa fa-times "
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="form-inline">
                                <div class="form-group mb-2">
                                    <label for="staticEmail2">Version:</label>
                                    <input name="version" class="form-control" type="text" value=""
                                        placeholder="Version" list="rm_title_datalist" />
                                    <datalist id="rm_title_datalist">
                                        @foreach($versions as $version)
                                        <option value="{{$version->version}}">{{$version->version}} </option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div id="assigned_manager" style="text-align: end;">
                                <label for="example-password-input" class="col-form-label"
                                    style="font-weight: bold;">Project Manager : </label>
                                @if($project->projectManager == null)
                                <label for="example-password-input" id="manager_name" class="col-form-label"
                                    style="font-weight: bold;">---</label>&nbsp;&nbsp;<span><a
                                        onclick="editmanagerassign()" style="cursor:pointer"><i
                                            class="mdi mdi-pencil"></i></a></span>
                                @else
                                <label for="example-password-input" id="manager_name" class="col-form-label"
                                    style="font-weight: bold;">{{$project->projectManager->name}}</label>&nbsp;&nbsp;<span><a
                                        onclick="editmanagerassign()" style="cursor:pointer"><i
                                            class="mdi mdi-pencil"></i></a></span>

                                @endif


                            </div>
                            <div class="form-group row" id="project_manager_section" style="display:none">
                                <label for="example-password-input" class="col-md-4 col-form-label"
                                    style="font-weight: bold;">Project Manager : </label>
                                <div class="col-md-5">
                                    <select class="select2 form-control custom-select" type="search"
                                        id="project_manager_id" name="project_manager_id"
                                        style="width: 100%; height:36px;" required>

                                    </select>
                                </div>
                                <div class="col-md-3" style="padding-top: 5px;">
                                    <a onclick="saveManager()" style="cursor:pointer;"><i class="fa fa-check"
                                            aria-hidden="true"></i></a>&nbsp;
                                    <a onclick="cancelManagerEdit()" style="cursor:pointer;"><i class="fa fa-times "
                                            aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-12" id="timeline_div" style="height: 900px !important;
    overflow-y: scroll;">
            <div class="card">
                <div class="card-body">
                    <ul class="timeline timeline-left">

                        <li class="timeline-inverted timeline-item">
                            <div class="timeline-badge warning"><span class="font-12"></span></div>
                            <div class="timeline-panel" style=>
                                <div class="timeline-heading">
                                    <h4 class="timeline-title" style="text-align: center;"></h4>
                                </div>

                            </div>
                        </li>

                        <li class="timeline-inverted timeline-item">
                            <div class="timeline-badge"><i class=""></i> </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h4 class="timeline-title">Lorem ipsum dolor</h4>
                                </div>
                                <div class="timeline-body">
                                    <p></p>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="card" style="border: 2px solid blue;">

                <div class="card-body">
                    <div id="timeline-1" class="" style="background: white;">

                        <div class="row">
                            <div class="col-md-12">

                                <div class="widget-box widget-color-dark">
                                    <div class="widget-body">
                                        <div class="widget-main padding-8">
                                            <form class="row road-map-form" id="save-task"
                                                action="{{asset('save-project-task')}}" method="post">
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <h3 class="blue bigger" style="color:#009efb;">Add New Task</h3>
                                                    <hr>
                                                    <input name="id" style="display: none;" class="form-control"
                                                        type="text" value="" readonly="readonly">


                                                    <div class="form-horizontal">
                                                        <!-- <input name="rm_table" style="display: none;"
                                    class="form-control" type="text" value="" /> -->

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-4">Version
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <input name="version" class="form-control" type="text"
                                                                    value="" placeholder="Version"
                                                                    list="rm_title_datalist" />
                                                                <datalist id="rm_title_datalist">
                                                                    @foreach($versions as $version)
                                                                    <option value="{{$version->version}}">
                                                                        {{$version->version}} </option>
                                                                    @endforeach
                                                                </datalist>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-4">Title
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <input name="title" class="form-control" type="text"
                                                                    value="" placeholder="Title" />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-4">Status
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <select name="task_status" class="form-control">
                                                                    <option>Select</option>
                                                                    <option value="success">Complete</option>
                                                                    <option value="danger">Pending</option>
                                                                    <option value="default">Processing</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">

                                                            <label class="control-label col-sm-4">Date :</label>
                                                            <div class="col-sm-12">
                                                                <input type="date" class="form-control" id="date"
                                                                    name="work_date">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-5">Assign To :</label>
                                                            <div class="col-sm-12">
                                                                <select class="select2 form-control" id="assign_to"
                                                                    name="assign_to" multiple="multiple"
                                                                    style="height: 36px;width: 100%;">

                                                                    @foreach($users as $user)
                                                                    <option value="{{$user->id}}">{{$user->name}}
                                                                    </option>
                                                                    @endforeach

                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-12">Description
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <textarea style="height: 300px;" id="task_description"
                                                                    name="task_description" class="form-control"
                                                                    placeholder="Description"></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-12">Attachment
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <div class="myattachments">

                                                                </div>
                                                                <!-- <input type="text" name="del" id="del"
                                            style="display:none;"> -->
                                                                <!-- <input type="file" name="img[]" id="img"
                                            multiple onchange="img_file_count()"> -->
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-offset-4 col-sm-12">
                                                                <button type="submit" class="btn btn-primary pull-right"
                                                                    style="float: right;">Save</button>
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
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <div id="server-details-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="blue bigger" style="color:#009efb;">Asset Manager </h3>
                    <button type="button" class="close btn waves-effect waves-light btn-danger"
                        onclick="hideModal()">&times;</button>

                </div>
                <div class="modal-body">
                    <div id="server-details">
                       
                    </div>
                    <div id="edit-server-details" style="display:none">
                        <form id="server-details-frm" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-4">Hostname</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="hostname" name="hostname"
                                                value="{{$project->hostname}}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-8">Site Type</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="site_type" name="site_type"
                                                value="{{$project->site_type}}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-8">Login URL</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="url" name="url"
                                                value="{{$project->url}}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-8">Live-Tech Access Username</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="{{$project->username}}">
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-4">Password</label>

                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                    </br>
                    <div class="text-right">
                        <br />

                        <a class="btn btn-sm btn-success" id="btn-edit" onclick="editServerDetails()"
                            style="color:#FFF;">Edit</a>
                        <a class="btn btn-sm tab-select-btn btn-info " onclick="hideModal()"
                            style="color:#FFF;">Close</a>

                    </div>

                </div>

            </div>
        </div>
    </div>

{{-- 
    <div id="server-details-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="blue bigger" style="color:#009efb;">Server Details </h3>
                    <button type="button" class="close btn waves-effect waves-light btn-danger"
                        onclick="hideModal()">&times;</button>

                </div>
                <div class="modal-body">
                    <div id="server-details">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label class="control-label"><strong>Hostname :</strong></label>

                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <span id="host_read">{{$project->hostname}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label class="control-label"><strong>Site Type :</strong></label>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <span id="site_read">{{$project->site_type}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label class="control-label"><strong>Login URL :</strong></label>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <span id="url_read"> {{$project->url}}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <label class="control-label"><strong>Live-Tech Access Username :</strong></label>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                <span id="username_read">{{$project->username}}</span>
                            </div>
                        </div>
                    </div>
                    <div id="edit-server-details" style="display:none">
                        <form id="server-details-frm" method="POST">
                            <fieldset>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-4">Hostname</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="hostname" name="hostname"
                                                value="{{$project->hostname}}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-8">Site Type</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="site_type" name="site_type"
                                                value="{{$project->site_type}}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-8">Login URL</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="url" name="url"
                                                value="{{$project->url}}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-8">Live-Tech Access Username</label>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="username" name="username"
                                                value="{{$project->username}}">
                                        </div>
                                    </div><br>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label class="control-label col-sm-4">Password</label>

                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="password" name="password">
                                        </div>
                                    </div>
                                </div>

                            </fieldset>
                        </form>
                    </div>
                    </br>
                    <div class="text-right">
                        <br />

                        <a class="btn btn-sm btn-success" id="btn-edit" onclick="editServerDetails()"
                            style="color:#FFF;">Edit</a>
                        <a class="btn btn-sm tab-select-btn btn-info " onclick="hideModal()"
                            style="color:#FFF;">Close</a>

                    </div>

                </div>

            </div>
        </div>
    </div> <!-- End Of server details modal --> --}}

</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });     
    let tasks =  {!! json_encode($tasks) !!};
    let versions =  {!! json_encode($versions) !!};
    let settings =  {!! json_encode($settings) !!};
    
    $( document ).ready(function() {
        
        template_html_func(tasks);
        $('#timeline_div').animate({ scrollTop: $("#current_version").offset().top-50}, 100);
    });
    
    // var form = $("form.road-map-form");

    // var get_task_details = function (id) {
        
    //     for(var j = 0 ; j < tasks.length ; j++){
    //         if(tasks[j].id == id){
    //             form.find("input[name='id']").val(tasks[j].id);
    //             form.find("input[name='version']").val(tasks[j].version);
    //             form.find("input[name='title']").val(tasks[j].title);
    //             form.find("textarea[name='task_description']").html(tasks[j].task_description);
    //             $("select[name='task_status']").val(tasks[j].task_status);
    //         }
    //     }
    //     // form.find("input[name='image']").val(roadmapjson[id]['image']);
    //     // var imagetoform = $('.timeline-images-' + id + ' ').html();
    //     // if (imagetoform) {
    //     //     form.find(".myattachments").html(imagetoform);
    //     // } else {
    //     //     form.find(".myattachments").html("");
    //     // }
    //     //$('#add-new-task').modal('show');

    // }


    var template_html_func = function (rm_data) {
        // console.log(rm_data);
        var template_inner_html = "";
        var currentVersion = '';
        for(var j = 0 ; j<versions.length;j++){
            
            var currentV = (versions[j].version == settings.version) ? "id='current_version'" : "";
            template_inner_html += `<li class="timeline-inverted timeline-item" ` + currentV + `>
                                            <div class="timeline-badge warning"><span class="font-12">` + versions[j].version + `</span></div>
                                                <div class="timeline-panel" style=>
                                                    <div class="timeline-heading">
                                                        <h4 class="timeline-title" style="text-align: center;">` +
                versions[j].version + `</h4>
                                                    </div>
                                                        
                                                </div>
                                        </li>`;
                                        
            jQuery.each(rm_data, function (key, val) {
                // console.log(val)
                if(val['version'] == versions[j].version){
                    var desc = '';
                    var formated_desc = '';
                    if(val['task_description'] != null && val['task_description'] != ''){
                        desc = val['task_description'].split('\n');
                        if (desc.length > 1) {
                            for (var k = 0; k < desc.length; k++) {
        
                                formated_desc += '<p>' + desc[k] + '</p>';
                            }
                        } else {
                            formated_desc = desc;
                        }
                    }else{
                        desc = val['task_description'];
                        formated_desc = desc;
                    }
                    
                    
                    var icon = '';
                    var css_class = '';
                    var title = '';
                    if(val['title'] != '' && val['title'] != null){
                        title = val['title'];
                    }
                    if (val['task_status'] == 'danger') {
                        icon = 'fa fa-ban';
                        css_class = "danger";
                    } else if (val['task_status'] == 'success') {
                        icon = 'fa fa-check';
                        css_class = "success";
                    } else {
                        icon = 'fa fa-hourglass-end';
                        css_class = "primary";
                    }
                    
                    var date = '';
                    date = moment(val['created_at']).format('h:mm a MM/DD/YYYY');
                    template_inner_html += `<li class="timeline-inverted timeline-item" id="task_card_`+val['id']+`">
                                                <div class="timeline-badge ` + css_class + `"><i class="` + icon + `" aria-hidden="true" style="margin-top: 12px;"></i></div>
                                                    <div class="timeline-panel">
                                                        <div class="timeline-heading">
                                                            <div class="row">
                                                                <div class="col-md-8">
                                                                    <h6 class="timeline-title"> <strong style="font-weight: 600">` +title+`</strong> </h6>
                                                                    <h6> `+date+` </h6>
                                                                    
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr style="margin-top:0px;margin-bottom:10px">
                                                        <div class="timeline-body"><strong style="font-weight: 600">Details : </strong>
                                                        ` + formated_desc + `
                                                        </div>
                                                    </div>
                                                </li>`
                }                            
                // var obj = val['image'];
                // if(obj){
                // 	var file_no = 0;
                // 	var sss = 'timeline-images-'+val['id']+'"';
                // 	template_inner_html +='<div class="timeline-images-'+val['id']+'"><ul>';
                // $.each( obj, function( key, value ) {
                // 	var file_class = "image"+file_no;
                // 	var filenames = obj[file_no];
                // template_inner_html += '<li class="ace-thumbnails clearfix" ><a href="tmp_files/about_files/'+obj[file_no]+'" title="'+val['file_name'][file_no]+'" data-rel="colorbox" class="btn btn-primary btn-xs '+ file_class +'" role="button" target="_blank"><i class="fa fa-picture-o" aria-hidden="true"></i> '+val['file_name'][file_no]+' </a><i class="fa fa-close" id="'+filenames+'" aria-hidden="true" onClick="doSomething(this)" ></i></li>';
                // file_no++;
                // });
                // template_inner_html +='</ul></div>';
                // };

                //template_inner_html +='</div></div></div>';

            });                          
        }
      
        $(".timeline").html('');
        $(".timeline").html(template_inner_html);
        return ;
    }


    var road_map_timeline_loader = function () {
        
        $.ajax({
            url: "{{asset('/get-project-tasks')}}",
            type: "GET",
        
            success: function (data) {
                $(".timeline").html('');
                $(".timeline").html(template_html_func(data));
                $('#timeline_div').animate({ scrollTop: $("#current_version").offset().top-50}, 100);
            },
            failure: function (errMsg) {
                console.log(errMsg);
            }
        });
    }

    //road_map_timeline_loader();

    // form.submit(function () {
    //     event.preventDefault()
    //     var formData = new FormData($(this)[0]);
    //     formData.append('slug', project_slug);
    //     var url = $(this).attr('action');
    //     var method = $(this).attr('method');
    //     $.ajax({
    //         url: url,
    //         type: method,
    //         data: formData,
    //         async: false,
    //         cache: false,
    //         contentType: false,
    //         enctype: 'multipart/form-data',
    //         processData: false,
    //         success: function (data) {
    //             console.log(data)

    //             if (data['error']) {

    //                 Swal.fire({
    //                     position: 'top-end',
    //                     icon: 'error',
    //                     title: 'Something went wrong!',
    //                     showConfirmButton: false,
    //                     timer: 2500
    //                 })

    //             } else {
    //                 Swal.fire({
    //                     position: 'top-end',
    //                     icon: 'success',
    //                     title: data['message'],
    //                     showConfirmButton: false,
    //                     timer: 2500
    //                 })
    //                 //road_map_timeline_loader();
    //                 setTimeout(function () { // wait for 1 
    //                     location.reload(); // then reload the page.(3)
    //                 }, 1000);
    //                 $('#timeline_div').animate({
    //                     scrollTop: $("#current_version").offset().top - 50
    //                 }, 100);
    //             }
    //             form[0].reset();
    //             form.find("input[name='id']").val("");
    //             form.find(".myattachments").html("");
    //             setTimeout(function () { // wait for 1 
    //                 location.reload(); // then reload the page.(3)
    //             }, 1000);
    //         },
    //         failure: function (errMsg) {
    //             console.log(errMsg);
    //             InitLoadingSaveDiv("success_save_form", "error");
    //         }
    //     });
    //     return false;
    // });

    // $("button.reset-form").on("click", function () {
    //     form.find("input[name='id']").val("");
    //     form[0].reset();
    //     form.find(".myattachments").html("");
    //     form.find("input[name='rm_title']").val("");
    //     form.find("textarea[name='rm_description']").html("");
    //     $("select[name='roadmap_status']").val("danger");
    //     form.find("input[name='order_by']").val("");
    //     form.find("input[name='image']").val("");
    // })

</script>

{{-- <script type="text/javascript">
    var delfile = "";
    
    function editcutomerassign(){
        $('#assigned_customer').css('display', 'none');
        $('#customer_section').css('display', 'flex');
        $('#customer_id').empty();
        for(var i = 0; i<customers.length;i++){
            var name = '';
            if(customers[i].name == '' || customers[i].name == null || customers[i].name == ' '){
                name = customers[i].email;
            }else{
                name = customers[i].name;
            }
            var newOption = new Option(name, customers[i].id, false, false);
            $('#customer_id').append(newOption).trigger('change');
        }
    }
    function editmanagerassign(){
        $('#assigned_manager').css('display', 'none');
        $('#project_manager_section').css('display', 'flex');
        $('#project_manager_id').empty();
        for(var i = 0; i<users.length;i++){
           
            var newOption = new Option(users[i].name, users[i].id, false, false);
            $('#project_manager_id').append(newOption).trigger('change');
        }
    }
    function editPageTitle() {

        $('#page_title').css('display', 'none');
        $('#edit_title_div').css('display', 'flex');
        
        $('#title_input').val($('#title_span').text())

    }
    
    function editServerDetails() {

        var btn = $("#btn-edit").text();
        if (btn == 'Edit') {
            $('#server-details').css('display', 'none');
            $('#edit-server-details').css('display', 'block');
            $("#btn-edit").html('Save');
        } else {

            var hostname = $('#hostname').val();
            var site = $('#site_type').val();
            var url = $('#url').val();
            var username = $('#username').val();
            
            var formData = new FormData($('#server-details-frm')[0]);
            formData.append('id', project.id);

            $.ajax({
                url: "{{asset('save-server-detail')}}",
                type: "POST",
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                enctype: 'multipart/form-data',
                processData: false,
                
                success: function (data) {
                    console.log(data)
                    if (data) {
                        
                        $('#host_read').text(hostname);
                        $('#site_read').text(site);
                        $('#url_read').text(url);
                        $('#username_read').text(username);

                        $('#hostname').val(hostname);
                        $('#site_type').val(site);
                        $('#url').val(url);
                        $('#username').val(username);
                        
                        $('#server-details-modal').modal('hide');
                        
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Server Details Updated Successfully!',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                },
                failure: function (errMsg) {
                    console.log(errMsg);
                }
            });
        }

    }


    function hideModal() {
        $('#server-details').css('display', 'block');
        $('#edit-server-details').css('display', 'none');
        $("#btn-edit").html('Edit');
        $('#server-details-modal').modal('hide');
    }

    function cancelEdit() {
        $('#page_title').css('display', 'block');
        $('#edit_title_div').css('display', 'none');
    }
    
    function cancelManagerEdit(){
        $('#assigned_manager').css('display', 'block');
        $('#project_manager_section').css('display', 'none');
    }

    function cancelCustomerEdit(){
         $('#assigned_customer').css('display', 'block');
        $('#customer_section').css('display', 'none');
    }
    
    function saveCustomer() {
        
        var customer_id = $('#customer_id').val();
        
        $('#customer_name').text($('#customer_id option:selected').text());
        $('#assigned_customer').css('display', 'block');
        $('#customer_section').css('display', 'none');

        $.ajax({
            url: "{{asset('update-project-customer')}}",
            type: "POST",
            data: {
                customer_id: customer_id,
                project_slug: project.project_slug
            },
            dataType: 'json',
            cache: false,

            success: function (data) {
                console.log(data)
                if (data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Customer Saved Successfully!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
            },
            failure: function (errMsg) {
                console.log(errMsg);
            }
        });

    }
    
    // function saveManager() {
    //     var project_manager_id = $('#project_manager_id').val();

    //     $('#manager_name').text($('#project_manager_id option:selected').text());
    //     $('#assigned_manager').css('display', 'block');
    //     $('#project_manager_section').css('display', 'none');

    //     $.ajax({
    //         url: "{{asset('update-project-manager')}}",
    //         type: "POST",
    //         data: {
    //             manager_id: project_manager_id,
    //             project_slug: project.project_slug
    //         },

    //         dataType: 'json',
    //         cache: false,

    //         success: function (data) {
    //             console.log(data)
    //             if (data) {
    //                 Swal.fire({
    //                     position: 'top-end',
    //                     icon: 'success',
    //                     title: 'Project Manager Saved Successfully!',
    //                     showConfirmButton: false,
    //                     timer: 2500
    //                 })
    //             } else {
    //                 Swal.fire({
    //                     position: 'top-end',
    //                     icon: 'error',
    //                     title: 'Something went wrong!',
    //                     showConfirmButton: false,
    //                     timer: 2500
    //                 })
    //             }
    //         },
    //         failure: function (errMsg) {
    //             console.log(errMsg);
    //         }
    //     });

    // }
    
    function saveTitle() {
        var title = $('#title_input').val();

        $('#title_span').text(title);
        $('#page_title').css('display', 'block');
        $('#edit_title_div').css('display', 'none');

        $.ajax({
            url: "{{asset('update-project-title')}}",
            type: "POST",
            data: {
                title: title,
                project_slug: project.project_slug
            },

            dataType: 'json',
            cache: false,

            success: function (data) {
                console.log(data)
                if (data) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Title Updated Successfully!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
            },
            failure: function (errMsg) {
                console.log(errMsg);
            }
        });

    }

    function doSomething(aaa) {
        var fileclosed = $(aaa).attr('id');
        console.log($(aaa).attr('id'));
        $(aaa).hide();
        $(aaa).parent().hide();
        if (delfile == "") {
            delfile += fileclosed;
        } else {
            delfile += ',' + fileclosed;
        }
        console.log(delfile);
        form.find("input[name='del']").val(delfile);
    }
    
    function deleteTask(id){

        event.preventDefault();
        ps = project_slug;

        $.ajax({
            url: "{{asset('delete-task')}}",
            type: "POST",
            data: {
                task_id: id,
                project_slug: ps
            },

            dataType: 'json',
            cache: false,

            success: function (data) {
                console.log(data)
                if (data.success==true) {

                    //  toastr.options = {
                    //     "closeButton": false,
                    //     "debug": false,
                    //     "positionClass": "toast-bottom-right",
                    //     "onclick": null,
                    //     "showDuration": "300",
                    //     "hideDuration": "1000",
                    //     "timeOut": "5000",
                    //     "extendedTimeOut": "1000",
                    //     "showEasing": "swing",
                    //     "hideEasing": "linear",
                    //     "showMethod": "fadeIn",
                    //     "hideMethod": "fadeOut"
                    // };

                    // toastr.success(msg);
                    form[0].reset();
                    $('#task_description').text('')
                    $('#task_card_'+id).css('display','none');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Task Deleted Successfully!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Something went wrong!',
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
            },
            failure: function (errMsg) {
                console.log(errMsg);
            }
        });
    }


</script> --}}
@endsection
<style>
    .timeline-badge.primary {
        background-color: lightgray;
    }

    .timeline-item {
        cursor: pointer;
    }

    .timeline-item:hover {
        opacity: 0.8;
    }

    .overflowscroll {
        max-height: 700px;
        overflow-x: auto;
        padding: 20px 0px;
    }
</style>