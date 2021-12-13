@extends('layouts.staff-master-layout')
@section('body-content')
<style>
    .pPic{
        margin-top:9px !important;
    }
    </style>
<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <h3 class="page-title">Dashboard</h3>
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Project Task</li>
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
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body p-3">
                    <!--<h4 class="card-title"></h4>-->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="w-100">
                                <div id="page_title">
                                    <span style="font-size: x-large;" id="title_span">{{$project->name}}</span>&nbsp;&nbsp;
                                    <span style="font-size: x-large;"><a onclick="editPageTitle()" style="cursor:pointer"><i class="mdi mdi-pencil"></i></a></span>
                                </div>
                                <div class="row align-items-center" style="display:none" id="edit_title_div">
                                    <div class="col-md-5">
                                        <input id="title_input" class="form-control" style="width: 100%;" type="text" name="" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <a onclick="saveTitle()" style="cursor:pointer;font-size: x-large;">
                                            <i class="fa fa-check" aria-hidden="true"></i></a>&nbsp;
                                        <a onclick="cancelEdit()" style="cursor:pointer;font-size: x-large;">
                                            <i class="fa fa-times " aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100">
                                <div id="assigned_customer">
                                
                                    <label for="example-password-input" class="col-form-label" style="font-weight: bold;">Customer : </label>
                                    <input type="hidden" id="project_customer_id" value="{{$project->customer_id != null ? $project->customer_id : '-'}}">
                                    @if($project->projectCustomer == null)
                                    <label for="example-password-input" id="customer_name" class="col-form-label" style="font-weight: bold;">---</label>&nbsp;&nbsp;<span
                                        ><a onclick="editcutomerassign()" style="cursor:pointer"><i
                                                class="mdi mdi-pencil"></i></a></span>
                                    @else
                                    <label for="example-password-input" id="customer_name" class="col-form-label" style="font-weight: bold;">{{$project->projectCustomer->username}}</label>&nbsp;&nbsp;<span
                                        ><a onclick="editcutomerassign()" style="cursor:pointer"><i
                                                class="mdi mdi-pencil"></i></a></span>
                                    @endif
                                    
    
                                </div>
                                <div class="form-group row" id="customer_section" style="display:none">
                                    <label for="example-password-input" class="col-md-3 col-form-label" style="font-weight: bold;">Customer</label>
                                        <div class="col-md-6">
                                            <select class="select2 form-control custom-select" type="search" id="customer_id" name="customer_id" style="width: 100%; height:36px;" required>
                                                <option value="">Select</option>
                                            </select>
                                            
                                        </div>
                                        <div class="col-md-3" style="padding-top: 5px;">
                                        <a onclick="saveCustomer()" style="cursor:pointer;"><i
                                                class="fa fa-check" aria-hidden="true"></i></a>&nbsp;
                                        <a onclick="cancelCustomerEdit()" style="cursor:pointer;"><i
                                                class="fa fa-times " aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>

                            <div class="w-100">
                                <div id="assigned_manager">
                                    <label for="example-password-input" class="col-form-label" style="font-weight: bold;">Project Manager : </label>
                                    <input type="hidden" id="selected_project_manager_id" value="{{$project->project_manager_id != null ? $project->project_manager_id : '-'}}">
                                    @if($project->projectManager == null)
                                    <label for="example-password-input" id="manager_name" class="col-form-label" style="font-weight: bold;">---</label>&nbsp;&nbsp;<span
                                        ><a onclick="editmanagerassign()" style="cursor:pointer"><i
                                                class="mdi mdi-pencil"></i></a></span>
                                    @else
                                    <label for="example-password-input" id="manager_name" class="col-form-label" style="font-weight: bold;">{{$project->projectManager->name}}</label>&nbsp;&nbsp;<span
                                        ><a onclick="editmanagerassign()" style="cursor:pointer"><i
                                                class="mdi mdi-pencil"></i></a></span>
                                        
                                    @endif
                                    
    
                                </div>
                                <div class="form-group row" id="project_manager_section" style="display:none">
                                    <label for="example-password-input" class="col-md-4 col-form-label" style="font-weight: bold;">Project Manager : </label>
                                        <div class="col-md-5">
                                            <select class="select2 form-control custom-select" type="search" id="project_manager_id" name="project_manager_id" style="width: 100%; height:36px;" required>
                                            </select>
                                        </div>
                                        <div class="col-md-3" style="padding-top: 5px;">
                                        <a onclick="saveManager()" style="cursor:pointer;"><i
                                                class="fa fa-check" aria-hidden="true"></i></a>&nbsp;
                                        <a onclick="cancelManagerEdit()" style="cursor:pointer;"><i
                                                class="fa fa-times " aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-start">
                            <div class="w-100 pb-3">
                                <button type="button" style="float:right" class="btn btn-primary" data-toggle="modal" data-target="#asset"><i class="fa fa-key" aria-hidden="true"></i>&nbsp;Server Details</button>
                            </div>
                            <div class="w-100 py-2" id="tasks-filters" style="display: none;">
                                <div class="col-12 col-md-6 col-lg-6 p-0">
                                    <div class="form-inline float-right">
                                      <div class="form-group">
                                        <label style="font-weight: bold; margin-right: 5px;">Version</label>
                                        <select name="version-filter" id="version-filter" class="form-control" style="width: 150px;" onchange="filterTaskList();">
                                            @foreach($versions as $version)
                                                <option value="{{$version->version}}" {{$version->version == $settings->site_version ? 'selected' : '-'}} >{{$version->version}} </option>
                                            @endforeach
                                        </select>
                                      </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6 p-0">
                                    <div class="form-inline float-right">
                                      <div class="form-group">
                                        <label style="font-weight: bold; margin-right: 5px;">Status</label>
                                        <select name="status-filter" id="status-filter" class="form-control" style="width: 150px;" onchange="filterTaskList();">
                                            <option value="danger" selected>Pending</option>
                                            <option value="success">Complete</option>
                                            <option value="default">Working</option>
                                        </select>
                                      </div>
                                    </div>
                                </div>
                            </div>
                            <!--<button type="button" style="float:right;margin-right: 2px;" class="btn btn-primary"-->
                            <!--    data-toggle="modal" data-target="#add-new-task"><i class="fa fa-plus"-->
                            <!--        aria-hidden="true"></i>&nbsp;Add New Task</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive d-none">
        <table id="file_export" class="table table-striped table-bordered no-wrap" style="width: 100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Version</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Due Date</th>
                    <th>Assign To</th>
                    <th>Work Technology</th>
                    <th>Description</th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="row" style="display: none;">
        <div class="col-md-8 mb-3" id="timeline_div" style="height: 900px !important; overflow-y: scroll;">
            <div class="card">
                <div class="card-body p-2" id="tasks-list"></div>
            </div>
        </div>
        <div class="col-md-4" id="timeline_form">
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
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-5">Version :</label>
                                                            <div class="col-sm-12">
                                                                <input name="version" class="form-control" type="text"
                                                                    value="" placeholder="Version"
                                                                    list="rm_title_datalist" required />
                                                                <datalist id="rm_title_datalist">
                                                                    @foreach($versions as $version)
                                                                    <option value="{{$version->version}}">{{$version->version}} </option>
                                                                    @endforeach
                                                                </datalist>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-4">Title
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <input name="title" class="form-control" type="text"
                                                                    value="" placeholder="Title" required />
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-4">Status
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <select name="task_status" class="form-control" style="width:100%" required onchange="checkTaskStatus(this);">
                                                                    <option value="" disabled>Select</option>
                                                                    <option value="success">Complete</option>
                                                                    <option value="danger" selected>Pending</option>
                                                                    <option value="default">Working</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="display: none;">
                                                            <label class="control-label col-sm-12">Completion Time
                                                                :</label>
                                                            <div class="col-sm-12 d-flex">
                                                                <input type="number" min="0" class="form-control" id="completion_time_hours" placeholder="HH">&nbsp;
                                                                <input type="number" min="0" class="form-control" id="completion_time_mins" placeholder="MM">
                                                                <input type="text" style="display: none;" class="form-control" id="completion_time" name="completion_time">
                                                            </div>
                                                        </div>

                                                        <div class="form-group" style="display: none;">
                                                            <label class="control-label col-sm-12">Completion Date :</label>
                                                            <div class="col-sm-12">
                                                                <input type="date" class="form-control" id="completed_at" name="completed_at">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-12">Priority
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <select name="task_priority" class="form-control" style="width:100%" required>
                                                                    <option value="" selected disabled>Select</option>
                                                                    <option value="Low">Low</option>
                                                                    <option value="Normal" selected>Normal</option>
                                                                    <option value="High">High</option>
                                                                    <option value="Urgent">Urgent</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">

                                                            <label class="control-label col-sm-6">Due Date :</label>
                                                            <div class="col-sm-12">
                                                                <input type="date" class="form-control" id="date" name="due_date" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-12">Estimated Time
                                                                :</label>
                                                            <div class="col-sm-12 d-flex">
                                                                <input type="number" min="0" class="form-control" id="estimated_time_hours" placeholder="HH">&nbsp;
                                                                <input type="number" min="0" class="form-control" id="estimated_time_mins" placeholder="MM">
                                                                <input type="text" style="display: none;" class="form-control" id="estimated_time" name="estimated_time">
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-5">Assign To :</label>
                                                            <div class="col-sm-12">
                                                            <select class="select form-control"
                                                                id="assign_to" name="assign_to"
                                                                style="height: 36px;width: 100%;" required>
                                                                <option value="">Select</option>
                                                                @foreach($users as $user)
                                                                 <option value="{{$user->id}}">{{$user->name}}</option>
                                                                @endforeach
                              
                                                            </select>
                                                           </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-8">Work Technology :</label>
                                                            <div class="col-sm-12">
                                                            <select class="select form-control"  id="work_tech" name="work_tech" style="height: 36px;width: 100%;" required>

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

                                                        <div class="form-group">
                                                            <label class="control-label col-sm-8">Task Type :</label>
                                                            <div class="col-sm-12">
                                                                <select class="select form-control"  id="task_type" name="task_type" style="height: 36px;width: 100%;" required>
                                                                    <option value="">Select</option>
                                                                    <option value="bug">Bug</option>
                                                                    <option value="improvemenet">Improvement</option>
                                                                    <option value="new feature">New Feature</option>
                                                                </select>
                                                           </div>
                                                        </div>

                                                        <div class="form-group" id="ot_div" style="display:none">
                                                            <label class="control-label col-sm-6">Other Tech  :</label>
                                                            <div class="col-sm-12">
                                                                <input type="text" class="form-control" id="other_tech" name="other_tech">
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group">
                                                            <label class="control-label col-sm-12">Description
                                                                :</label>
                                                            <div class="col-sm-12">
                                                                <textarea style="height: 300px;" id="task_description" name="task_description"
                                                                    class="form-control"
                                                                    placeholder="Description" required></textarea>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            {{-- <label class="control-label col-sm-12">Attachment:</label> --}}
                                                            <div class="col-sm-12">
                                                                <button class="btn btn-block btn-warning text-white" type="button" onclick="listAttachments();">Attachments</button>
                                                                <div class="myattachments">

                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <div class="col-sm-offset-4 col-sm-12 text-right">
                                                                <button type="button" class="btn pull-right btn-danger btn-sm rounded text-white" onclick="resetForm(this)"><i class="fas fa-history"></i> Reset</button>
                                                                <button type="submit" class="btn btn-success pull-right btn-sm rounded" id="prj_btn"><i class="fas fa-check-circle"></i> Save</button>
                                                                <button type="button" style="display:none" disabled id="prj_process" 
                                                                    class="btn btn-sm rounded btn-success"> 
                                                                    <i class="fas fa-circle-notch fa-spin"> </i> Processing 
                                                                </button>
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
                    <div class="loader_container" id="prj_loader" style="display:none">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="asset" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="blue bigger" style="color:#009efb;">Server Details </h3>
                    <button type="button" class="close btn waves-effect waves-light btn-danger" onclick="hideModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data" action="{{asset('/save-asset')}}" method="post">
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
        
                        <button type="submit" class="btn btn-success mt-3" style="float:right;">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- End Of server details modal -->


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
    </div> <!-- End Of attachments modal -->

    <div class="modal fade" id="listAttachmentsModal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="listAttachmentsModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h5 class="modal-title">Attachments</h5>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row pt-2 pb-0 pr-4">
                    
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal"> Close</button>
                </div>
            </div>
        </div>
    </div> <!-- End Of list attachments modal -->
   
</div>
@endsection
@section('scripts')


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

{{-- Dropify Files --}}
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/dropify-master/dist/css/dropify.min.css')}}">
<script src="{{asset('assets/extra-libs/dropify-master/dist/js/dropify.js')}}"></script>

{{-- Export Files In Excel --}}
<script src="https://cdn.datatables.net/buttons/1.5.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.1/js/buttons.html5.min.js"></script>

<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>

{{-- Linked Assets JS --}}
<script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    let tasks_table_list = '';
    let g_status =  {!! json_encode($status) !!};
    let tasks =  {!! json_encode($tasks) !!};
    console.log(tasks , "tasks");
    let project =  {!! json_encode($project) !!};
    let customers =  {!! json_encode($customers) !!};
    let users =  {!! json_encode($users) !!};
    let versions =  {!! json_encode($versions) !!};
    let settings =  {!! json_encode($settings) !!};
    let project_slug =  {!! json_encode($project_slug) !!};
    
    let g_dropi_index = 1;
    let g_attachments = [];
    let g_attachments_delete = [];
    let g_clickedTask = null;

    // assets data definitions
    let get_assets_route = "{{asset('/get-assets')}}";
    let del_asset_route = "{{asset('/delete-asset')}}";
    let save_asset_records_route = "{{asset('/save-asset-records')}}";
    let templates_fetch_route = "{{asset('/get-asset-templates')}}";
    let get_asset_details_route = "{{asset('/get-asset-details')}}";
    let templates = null;
    let asset_customer_uid = '';
    let asset_company_id = '';
    let asset_project_id = project.id;
    let asset_ticket_id = '';

    $( function() {
        if(g_status == 'complete'){
            $('#status-filter').val('success');
            // filterTaskList();
            $('#timeline_div').addClass('col-12');
            $('#timeline_div').removeClass('col-md-8');
            $('#timeline_form').remove();
            $('#timeline_div').parent().show();
        }else if(g_status == 'pending' || g_status == 'working'){
            if(g_status == 'pending'){
                $('#status-filter').val('danger');
            }
            if(g_status == 'default'){
                $('#status-filter').val('default');
            }
            // filterTaskList();
            $('#timeline_div').parent().show();
        }else{
            $('#tasks-filters').show();
            $('#tasks-filters').addClass('d-flex');
            $('#timeline_div').parent().show();
        }
        
        reDropify(0);

        $('#timeline_div').height($(window).height()-100);
        console.log({tasks});
        console.log({project});
        console.log({versions});

        // Export tasks
        tasks_table_list = $('#file_export').DataTable({
            paging: false,
            dom: 'Bfrtip',
            buttons: [
                'excel'
            ],
        });
        $('.buttons-excel').html('Export Tasks');
        $('.buttons-excel').addClass('btn btn-primary');
        $('#file_export_filter').addClass('d-none');
        $('#file_export').addClass('d-none');
        $('.table-responsive').removeClass('d-none');
        
        template_html_func(tasks);
        if(g_status != null){
            filterTaskList();
        }
        $('#timeline_div').animate({ scrollTop: $("#current_version").offset().top-450}, 1000);

        $('#work_tech').on('change', function() {
            if(this.value == 'Other'){
                $('#ot_div').css('display','block')
            }else{
                $('#ot_div').css('display','none')
            }
        });

        getAssetDetails();
        getFormsTemplates();
    } );

    function checkTaskStatus(ele){
        if($(ele).val() == 'success'){
            $('#completion_time').closest('.form-group').show();
            $('#completed_at').closest('.form-group').show();
            $('#completed_at').attr('required', true);
        }else{
            $('#completion_time').closest('.form-group').hide();
            $('#completed_at').closest('.form-group').hide();
            $('#completed_at').attr('required', false);
        }
    }

    function resetForm(ele){
        Swal.fire({
            title: 'Do you want to reset form?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $('#save-task').trigger('reset');
                $('#save-task').find("input[name='id']").val('');
                $('#task_description').val('');
                reInitailizeAttachmentsDialog();
            }
        })
    }

    function filterTaskList(){
        template_html_func(tasks, $('#status-filter').val(), $('#version-filter').val());
    }


    // *********************************    Dropify     *********************************************** //

    function reInitailizeAttachmentsDialog() {
        g_attachments = [];
        g_attachments_delete = [];
        $('#attachmentsModal').find('.modal-body').html('');
        $('#attachmentsModal').find('.modal-body').html(`<div class="col-4 mb-3">
            <input type="file" id="dropi-0" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M"/>
        </div>`);

        reDropify(0);
    }

    function reDropify(indx, editingTask) {
        $('#dropi-'+indx).dropify().on('dropify.beforeClear', function(event, element){
            if(editingTask){
                g_attachments_delete.push(tasks[g_clickedTask].task_attachments[indx-1].attachment);
                console.log(g_attachments_delete);
                $('#dropi-'+indx).closest('.col-4').remove();
            }else{
                for(let i in g_attachments){
                    if(g_attachments[i].name == event.target.files[0].name){
                        g_attachments.splice(i, 1);
                        break;
                    }
                }
            }
        });
    }

    function loadFile(ele){
        $(ele).attr('onchange', 'replaceFile(\''+ele.id+'\', '+(g_dropi_index-1)+')');

        $('#attachmentsModal').find('.modal-body').append(`<div class="col-4 mb-3">
            <input type="file" id="dropi-`+g_dropi_index+`" data-show-errors="true" onchange="loadFile(this);" data-max-file-size="2M"/>
        </div>`);

        reDropify(g_dropi_index);

        g_dropi_index++;

        let files = $(ele).prop('files');

        g_attachments.push(files[0]);
    }

    function replaceFile(eleId, ind){
        let files = $('#'+eleId).prop('files');

        g_attachments[ind] = files[0];
    }

    function listAttachments(){
        $('#attachmentsModal').modal('show');
    }

    // *********************************    Dropify End     *********************************************** //

    
    var form = $("form.road-map-form");

    var get_task_details = function (id) {
        for(var j = 0 ; j < tasks.length ; j++){
            if(tasks[j].id == id){
                // if(tasks[j].task_status == 'success'){
                //     Swal.fire({
                //         position: 'top-end',
                //         icon: 'warning',
                //         title: 'Task is Complete!',
                //         showConfirmButton: false,
                //         timer: 2500
                //     });
                //     return false;
                // }
                g_clickedTask = j;
                g_dropi_index = 1;
                form.find("input[name='id']").val(tasks[j].id);
                form.find("input[name='version']").val(tasks[j].version);
                form.find("input[name='title']").val(tasks[j].title);
                form.find("input[name='due_date']").val(tasks[j].due_date);
                form.find("textarea[name='task_description']").val(tasks[j].task_description);
                $("select[name='task_status']").val(tasks[j].task_status);
                $("select[name='task_priority']").val(tasks[j].task_priority);
                $("select[name='work_tech']").val(tasks[j].work_tech);
                if(tasks[j].work_tech == 'Other'){
                    form.find("input[name='other_tech']").val(tasks[j].other_tech);
                }else{
                    form.find("input[name='other_tech']").val('');
                }
                $("select[name='work_tech']").trigger('change');
                $("select[name='assign_to']").val(tasks[j].assign_to);

                if(tasks[j].estimated_time){
                    let estimated_time = tasks[j].estimated_time.split(':');
                    $('#estimated_time_hours').val(estimated_time[0]);
                    $('#estimated_time_mins').val(estimated_time[1]);
                }

                reInitailizeAttachmentsDialog();
                for(let k=0; k<tasks[j].task_attachments.length; k++){
                    $('#attachmentsModal').find('.modal-body').prepend(`<div class="col-4 mb-3">
                            <input type="file" id="dropi-`+g_dropi_index+`" data-show-errors="true" data-max-file-size="2M" data-default-file="{{asset('files/Projects/`+project_slug+`/`+tasks[j].id+`/`+tasks[j].task_attachments[k].attachment+`')}}"/>
                        </div>`);

                    reDropify(g_dropi_index, true);
                    $('#dropi-'+g_dropi_index).attr('disabled', 'true');
                    g_dropi_index++;
                }
                break;
            }
        }
        // form.find("input[name='image']").val(roadmapjson[id]['image']);
        // var imagetoform = $('.timeline-images-' + id + ' ').html();
        // if (imagetoform) {
        //     form.find(".myattachments").html(imagetoform);
        // } else {
        //     form.find(".myattachments").html("");
        // }
        //$('#add-new-task').modal('show');

    }


    var template_html_func = function (rm_data, stft="danger", vrft='') {

        let today_date = new Date();
        today_date = moment(today_date).format('MM/DD/YYYY');
        let overdue_badge = `<span class="badge bg-danger text-white badge-pill">overdue</span>`;
  
        // console.log(rm_data);
        rm_data = rm_data.filter(item => item.task_status === stft);
        let l_versions = versions;
        if(vrft != '' && vrft != null){
            l_versions = l_versions.filter(item => item.version === vrft);
        }        
        console.log({rm_data});
        console.log({l_versions});
        var template_inner_html = "";
        var currentVersion = '';
        tasks_table_list.clear().draw();
        for(var j=0; j < l_versions.length; j++){
            
            var currentV = (l_versions[j].version == settings.site_version) ? "id='current_version'" : "";
            template_inner_html += `<ul class="timeline timeline-left"><li class="timeline-inverted timeline-item" ` + currentV + `>
                <div class="timeline-badge warning"><span class="font-12">` + l_versions[j].version + `</span></div>
                <div class="timeline-panel" style=>
                    <div class="timeline-heading">
                        <h4 class="timeline-title" style="text-align: center;">` +
                            l_versions[j].version + `</h4>
                    </div>
                </div>
            </li></ul>
            <ul class="timeline timeline-left connectedSortable" data-version="`+l_versions[j].version+`">`;

            let new_rm_data = rm_data.filter(item => item.version === l_versions[j].version);
          
            // sort array
            new_rm_data = new_rm_data.sort((a,b) => (a.sort_id > b.sort_id) ? 1 : ((b.sort_id > a.sort_id) ? -1 : 0))

            jQuery.each(new_rm_data, function (key, val) {
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
                }else{
                    title = '---';
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
                var overdue_sign = '';
                if(val['due_date'] != null){
                    date = moment(val['due_date']).format('MM/DD/YYYY');
                    overdue_sign = date > today_date ? 1 : '';
                }else{
                    date = '---'; 
                }

                    var creation_date = '';
                if(val['created_at'] != null){
                    creation_date = moment(val['created_at']).format('h:mm a MM/DD/YYYY');
                }else{
                    creation_date = '---'; 
                }
                var tech_name = '';
                if(val['assign_to'] == null){
                    tech_name = '---';
                }else{
                    for(var u = 0 ; u < users.length ; u++){
                        if(users[u].id == val['assign_to']){
                            tech_name = users[u].name;
                            break;
                        }
                    }
                }
                
                let attachs = '';
                let attachsCount = 0;
                if(val.task_attachments != undefined && val.task_attachments.length > 0){
                    attachsCount = val.task_attachments.length;
                    attachs += `<i class="fa fa-file mr-1" onclick="loadAttachments(`+val.id+`)" style="cursor: pointer;" aria-hidden="true" title="Attachments"></i>`;
                    // attachs += `<div class="btn-group">
                    //                 <button class="btn btn-light btn-sm shadow-none dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    //                     <i class="fa fa-file" aria-hidden="true"></i> `+attachsCount+` Files
                    //                 </button>
                    //                 <div class="dropdown-menu">`;
                    // for(let i in val.attachments){
                    //     attachs += `<a href="{{asset('files/Projects/`+project_slug+`/`+val.title+`_`+val.id+`/`+val.attachments[i]+`')}}" target="_blank" class="dropdown-item">`+val.attachments[i].substring(val.attachments[i].indexOf('_')+1, val.attachments[i].length)+`</a>`;
                    // }
                    // attachs += '</div></div>';
                }
                let tStatus = '';
                let removeBtn = '';
                if(val['task_status'] == 'success'){
                    let compTime = '---';
                    if(val['completion_time'] != null) compTime = val['completion_time'];
                    
                    tStatus = `<div class="w-100 d-flex flex-column flex-lg-row">
                                    <div>
                                        <h6> Status :  Completed </h6>
                                    </div>
                                    <div class="ml-lg-auto d-flex flex-column flex-lg-row">
                                        <h6 class="mr-3"> Completed On :  `+compTime+` </h6>
                                    </div>
                                </div>`;
                }else{
                    removeBtn = `<a href="javascript:void(0)" onclick="event.preventDefault();deleteTask(`+val['id']+`);"><i class="mdi mdi-delete"></i></a>`;
                }
                let rv = l_versions[j].version.replaceAll('.', '_')
                template_inner_html += `<li class="timeline-inverted timeline-item task-item-00 item-version-`+rv+`" id="task_card_`+val['id']+`" data-id="`+val['id']+`" item-version="`+l_versions[j].version+`"><span class="handle"></span>
                <div class="timeline-badge ` + css_class + `"><i class="` + icon + `" aria-hidden="true" style="margin-top: 12px;"></i></div>
                    <div class="timeline-panel" onclick="get_task_details(` + val['id'] + `)">
                        <div class="timeline-heading">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="timeline-title"> <strong style="font-weight: 600">` +title+`</strong> </h6>
                                    
                                </div>
                                <div class="col-md-4 p-0">
                                    <h6 class="timeline-title"> <strong style="font-weight: 600">Tech : `+ tech_name +`</strong> </h6>
                                    
                                </div>
                                <div class="col-md-2" style="text-align:right">
                                    `+attachs+`
                                    `+removeBtn+`
                                </div>
                            </div>

                            <div class="w-100 d-flex flex-column flex-lg-row">
                                <div>
                                    <h6></h6>
                                </div>
                                <div class="ml-lg-auto d-flex flex-column flex-lg-row mt-1 mb-1">
                                    `+ (overdue_sign == 1 ? overdue_badge : ' ') +`
                                </div>
                            </div>

                            <div class="w-100 d-flex flex-column flex-lg-row">
                                <div>
                                    <h6> Created on :  `+creation_date+` </h6>
                                </div>
                                <div class="ml-lg-auto d-flex flex-column flex-lg-row">
                                    <h6 class="mr-3"> Due on :  `+date+` </h6>
                                </div>
                            </div>
                            `+tStatus+`
                        </div>
                        <hr style="margin-top:0px;margin-bottom:10px">
                        <div class="timeline-body"><strong style="font-weight: 600">Details : </strong>
                        ` + val['task_description'].substring(0,190) + "....." + `
                        </div>
                    </div>
                </li>`;
                
                make_task_table_row(val);
            
            });
            template_inner_html +='</ul>'; // end of version ul
        }
      
        $("#tasks-list").html('');
        $("#tasks-list").html(template_inner_html);

        $(".connectedSortable").sortable({
            connectWith: ".connectedSortable",
            opacity: 0.5,
        }).disableSelection();

        $(".connectedSortable").on( "sortupdate", function( event, ui ) {
            // console.log({event, ui})
            var orderArray = [];
            if($(event.target).find('.task-item-00').length){
                let pv = $(this).attr('data-version');

                $(this).find('.task-item-00').each(function(indexj) {
                    orderArray.push({
                        version : pv,
                        id : $(this).attr('data-id'),
                        sort_id : indexj+1
                    });
                });
            }

            if(orderArray.length){
                $.ajax({
                    url: "{{url('/update-tasks-order')}}",
                    method: 'POST',
                    data: {order : orderArray, status : $('#status-filter').val()},
                    success: function(data) {
                        console.log('success');
                    }
                });
            }
        });

        return;
    }

    function loadAttachments(id) {
        let indx = tasks.map(function(task){ return task.id; }).indexOf(id);
        let divData = '';
        for(let i in tasks[indx].task_attachments){
            let ext = tasks[indx].task_attachments[i].attachment.substring(tasks[indx].task_attachments[i].attachment.lastIndexOf('.') + 1).toLowerCase();
            let name = tasks[indx].task_attachments[i].attachment.substring(tasks[indx].task_attachments[i].attachment.indexOf('_')+1, tasks[indx].task_attachments[i].attachment.length);
            
            if(ext == 'png' || ext == 'jpg' || ext == 'jpeg' || ext == 'webp' || ext == 'svg' || ext == 'tiff'){
                divData += `<div class="col-4 pb-2 pt-0 pl-2 pr-0" style="height: 150px !important;">
                    <div class="dropify-wrapper h-100">
                        <div class="dropify-preview" style="display: block;">
                            <span class="dropify-render">
                                <img src="{{asset('files/Projects/`+project_slug+`/`+tasks[indx].id+`/`+tasks[indx].task_attachments[i].attachment+`')}}">
                            </span>
                            <div class="dropify-infos">
                                <div class="dropify-infos-inner">
                                    <p class="dropify-filename">
                                        <span class="dropify-filename-inner">`+name+`</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            }else{
                divData += `<div class="col-4 pb-2 pt-0 pl-2 pr-0" style="height: 150px !important;">
                    <div class="dropify-wrapper col-4 h-100">
                        <div class="dropify-preview" style="display: block;">
                            <span class="dropify-render">
                                <i class="dropify-font-file"></i>
                                <span class="dropify-extension">`+ext+`</span>
                            </span>
                            <div class="dropify-infos">
                                <div class="dropify-infos-inner">
                                    <p class="dropify-filename">
                                        <span class="dropify-filename-inner">`+name+`</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
            }
        }
        $('#listAttachmentsModal').find('.modal-body').html(divData);
        $('#listAttachmentsModal').modal('show');
    }


    var road_map_timeline_loader = function () {
        
        $.ajax({
            url: "{{asset('/get-project-tasks')}}",
            type: "GET",
        
            success: function (data) {
                console.log(data)
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

    form.submit(function () {
        event.preventDefault();

        let titleUniq = tasks.map(function(task){ return task.title; }).indexOf(form.find("input[name='title']").val());

        if(titleUniq != -1 && !form.find('input[name="id"]').val()){
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Title already exists!',
                showConfirmButton: false,
                timer: 2500
            });
            return false;
        }
        
        if(form.find('select[name="task_status"]').val() == 'success'){
            if(!$('#completion_time_hours').val() && !$('#completion_time_mins').val()){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Please Enter Task Completion Time!',
                    showConfirmButton: false,
                    timer: 2500
                });
                return false;
            }
            if(!$('#completion_time_hours').val()){
                $('#completion_time_hours').val(0);
            }
            if(!$('#completion_time_mins').val()){
                $('#completion_time_mins').val(0);
            }
            $('#completion_time').val($('#completion_time_hours').val()+':'+$('#completion_time_mins').val());
        }
        if(!$('#estimated_time_hours').val() && !$('#estimated_time_mins').val()){
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: 'Please Enter Task Estimated Time!',
                showConfirmButton: false,
                timer: 2500
            });
            return false;
        }
        if(!$('#estimated_time_hours').val()){
            $('#estimated_time_hours').val(0);
        }
        if(!$('#estimated_time_mins').val()){
            $('#estimated_time_mins').val(0);
        }
        $('#estimated_time').val($('#estimated_time_hours').val()+':'+$('#estimated_time_mins').val());

        var formData = new FormData($(this)[0]);
        formData.append('slug', project_slug);
        formData.append('project_id', project.id);

        for(let i in g_attachments){
            formData.append('attachment_'+i, g_attachments[i]);
        }

        if(g_attachments_delete.length > 0) {
            formData.append('delete_Attachments', g_attachments_delete);
        }

        var url = $(this).attr('action');
        var method = $(this).attr('method');
        $.ajax({
            url: url,
            type: method,
            data: formData,
            async: true,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            beforeSend:function(data) {
                $("#prj_btn").hide();
                $("#prj_process").show();
                $("#prj_loader").show();
            },
            success: function (data) {
                console.log(data)

                if (data['error'] || !data['success']) {

                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })

                } else {
                    reInitailizeAttachmentsDialog();

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })
                    //road_map_timeline_loader();
                    setTimeout(function () { // wait for 1 
                        location.reload(); // then reload the page.(3)
                    }, 1000);
                    $('#timeline_div').animate({
                        scrollTop: $("#current_version").offset().top - 50
                    }, 100);

                    form[0].reset();
                    form.find("input[name='id']").val("");
                    form.find(".myattachments").html("");
                    setTimeout(function () { // wait for 1 
                        location.reload(); // then reload the page.(3)
                    }, 1000);
                }
            },
            complete:function(data) {
                $("#prj_btn").show();
                $("#prj_process").hide();
                $("#prj_loader").hide();
            },  
            failure: function (errMsg) {
                console.log(errMsg);
                InitLoadingSaveDiv("success_save_form", "error");
            }
        });
        return false;
    });

    $("button.reset-form").on("click", function () {
        form.find("input[name='id']").val("");
        form[0].reset();
        form.find(".myattachments").html("");
        form.find("input[name='rm_title']").val("");
        form.find("textarea[name='rm_description']").html("");
        $("select[name='roadmap_status']").val("danger");
        form.find("input[name='order_by']").val("");
        form.find("input[name='image']").val("");
    })

</script>

<script type="text/javascript">
    var delfile = "";
    
    function editcutomerassign(){
        $('#assigned_customer').css('display', 'none');
        $('#customer_section').css('display', 'flex');
        $('#customer_id').empty();

        var project_customer_id = $("#project_customer_id").val();

        let option = `<option value="">Select</option>`;
        $('#customer_id').append(option).trigger('change');
        for(var i = 0; i < customers.length;i++){
            var name = '';

            if(customers[i].name == '' || customers[i].name == null || customers[i].name == ' '){
                name = customers[i].email;
            }else{
                name = customers[i].name;
            }
            
            var newOption = `<option value="`+customers[i].id+`" `+ (project_customer_id == customers[i].id ? "selected" : " ") +` > `+ name +` </option>`;
            $('#customer_id').append(newOption).trigger('change');
        }
    }
    function editmanagerassign(){
        $('#assigned_manager').css('display', 'none');
        $('#project_manager_section').css('display', 'flex');
        $('#project_manager_id').empty();
        
        var project_manager_id = $("#selected_project_manager_id").val();
        
        let option = `<option value="">Select</option>`;
        $('#project_manager_id').append(option).trigger('change');

        for(var i = 0; i<users.length;i++){
            var newOption = `<option value="`+users[i].id+`" `+ (project_manager_id == users[i].id ? "selected" : " ") +` > `+ users[i].name +` </option>`;
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
                        
                        $('#asset').modal('hide');
                        
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
        $('#asset').modal('hide');
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
    
    function saveManager() {
        var project_manager_id = $('#project_manager_id').val();

        $('#manager_name').text($('#project_manager_id option:selected').text());
        $('#assigned_manager').css('display', 'block');
        $('#project_manager_section').css('display', 'none');

        $.ajax({
            url: "{{asset('update-project-manager')}}",
            type: "POST",
            data: {
                manager_id: project_manager_id,
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
                        title: 'Project Manager Saved Successfully!',
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

        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this task will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
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
        })
    }

    function make_task_table_row(task) {
        tasks_table_list.row.add([
            task.id,
            task.version,
            task.title,
            task.task_status,
            task.task_priority,
            task.due_date,
            task.assign_to,
            task.work_tech,
            task.task_description
        ]).draw(false);
    }

</script>

@endsection
<style>
    .timeline-badge.primary {
        background-color: lightgray;
    }

    .timeline-item {
        cursor: pointer;
        transition: .3s ease-in-out;
    }

    .timeline-item:hover {
        box-shadow: 1px 1px 12px 1px #888888;
        padding: 4px;
        border-radius: 3px;
    }

    .overflowscroll {
        max-height: 700px;
        overflow-x: auto;
        padding: 20px 0px;
    }
    /* .dropdown-menu:hover{
        opacity: 1 !important;
    } */
    #tasks-table-list_wrapper {
        display: none !important;
    }
</style>
