@php
$file_path = Session::get('is_live') == 1 ? 'public/' : '/';
$path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
<div class="card">
    <div class="card-body">

        <h4 class="card-title mb-3">Ticket Settings</h4>

        <ul class="nav nav-pills bg-nav-pills nav-justified mb-2 mt-2">
            <li class="nav-item">
                <a href="#tickets_general" data-bs-toggle="tab" aria-expanded="false" class="nav-link active">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block">General Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#SLA_part" data-bs-toggle="tab" aria-expanded="false" class="nav-link ">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block">SLA</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#tickets_departments" data-bs-toggle="tab" aria-expanded="true" class="nav-link">
                    <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block">Customizations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#tickets_mails" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <i class="mdi mdi-email d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block ">Email Queues</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#response_temp" data-bs-toggle="tab" aria-expanded="false" class="nav-link">
                    <i class="mdi mdi-email d-lg-none d-block mr-1"></i>
                    <span class="d-none d-lg-block ">Response Templates</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane active" id="tickets_general">
                <form class="widget-box widget-color-dark " id="save_ticket_format" action="{{asset('/ticket-format')}}" method="post">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group my-1">
                                <label for="security">Ticket ID Format :</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <select class="select2 form-control select2-hidden-accessible" id="ticket_format" style="width:100%;height:30px;" required>
                                    <option value="random">Random (#JRQ-369-3621,#BHJ-591-1832)</option>
                                    <option value="sequential">Sequential (1#,2#,3#,...,#99999)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row">
                        <div class="col-md-3">
                            <div class="form-group my-1">
                                <label for="security">Ticket Data Refresh <span class="text-danger small fst-italic">(in minutes)</span> :</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <input type="nummber" class="form-control" value="{{$ticket_time != 0 ? $ticket_time : ''}}" id="tkt_refresh" name="tkt_refresh">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-icon rounded-circle mt-1 tkt_btn"  onclick="saveTicketRefreshTime()" > <i data-feather='save'></i>  </button>
                            <button class="btn btn-primary waves-effect tkt_loader btn-icon rounded-circle mt-1" style="display:none" type="button" disabled="">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <span class="visually-hidden">Loading...</span>
                            </button>
                        </div>
                    </div>
            </div>

            <div class="tab-pane " id="SLA_part">
                <div id="accordion" class="accordion accordion-border">
                    <div class="card mb-0">
                        <div class="" id="SLA_body_collapse">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseSLAPlan" aria-expanded="false" aria-controls="collapseThree">
                                    SLA Plans <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseSLAPlan" class="collapse" aria-labelledby="SLA_body_collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <h4 class="widget-title lighter smaller menu_title">SLA Plans Table
                                            </h4>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <button class="btn waves-effect waves-light btn-success rounded btn-sm" data-bs-toggle="modal" data-bs-target="#save-SLA-plan" style="float:right">
                                                <i class="fa fa-plus-circle" style="padding-right:3px;"></i>&nbsp;Add SLA Plan</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="sla_table" class="display table-bordered table-hover" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Status</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-0">
                        <div class="" id="SLA_body_collapse">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2 " data-bs-toggle="collapse" href="#collapseSLASetting" aria-expanded="true" aria-controls="collapseThree">
                                    SLA Settings <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseSLASetting" class="collapse" aria-labelledby="SLA_body_collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <h4 class="widget-title lighter smaller menu_title">SLA Settings
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="setting_body pt-3 p-1">
                                            <form id="sla_setting_form" method="POST" action="{{url('sla_setting')}}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Clear reply due deadline on staff reply</b></p>
                                                        <small>When a staff user reply to a ticket, the ticket reply
                                                            due deadline will be cleared. A new reply due deadline will
                                                            be calculated when a user next replies to a ticket. </small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class=" ">
                                                            <div class="form-check mx-2 form-check-inline">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                @if($sla_setting['reply_due_deadline'] == 1)
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault12" checked>
                                                                <label class="form-check-label" for="flexRadioDefault12"> Yes </label>
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault12">
                                                                <label class="form-check-label" for="flexRadioDefault12"> Yes </label>
                                                                @endif
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault12">
                                                                <label class="form-check-label" for="flexRadioDefault12"> Yes </label>
                                                                @endif

                                                            </div>
                                                            <div class="form-check mx-2 form-check-inline">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                @if($sla_setting['reply_due_deadline'] == 0)
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault22" value="0" checked>
                                                                <label class="form-check-label" for="flexRadioDefault22"> No </label>
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault22" value="0">
                                                                <label class="form-check-label" for="flexRadioDefault22"> No </label>
                                                                @endif
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault2" id="flexRadioDefault22" value="0">
                                                                <label class="form-check-label" for="flexRadioDefault22"> No </label>
                                                                @endif

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Use default reply and resolution deadline</b></p>
                                                        <small>The time below will be used to calculate the ticket reply and resolution deadline if an SLA plan is not assigned to the ticket, user, user group or department. </small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="">
                                                            <div class="form-check mx-2 form-check-inline">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                @if($sla_setting['default_reply_and_resolution_deadline'] == 1)
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault13" checked>
                                                                <label class="form-check-label" for="flexRadioDefault13"> Yes </label>
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault13">
                                                                <label class="form-check-label" for="flexRadioDefault13"> Yes </label>
                                                                @endif
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault13">
                                                                <label class="form-check-label" for="flexRadioDefault13"> Yes </label>
                                                                @endif
                                                            </div>
                                                            <div class="form-check mx-2 form-check-inline">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                @if($sla_setting['default_reply_and_resolution_deadline'] == 0)
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault23" value="0" checked>
                                                                <label class="form-check-label" for="flexRadioDefault23"> No </label>
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault23" value="0">
                                                                <label class="form-check-label" for="flexRadioDefault23"> No </label>
                                                                @endif
                                                                @else
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault3" id="flexRadioDefault23" value="0">
                                                                <label class="form-check-label" for="flexRadioDefault23"> No </label>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Default reply time deadline</b></p>
                                                        <small>If a user is not responded to within these hours, the ticket will be marked as overdue. </small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="row text-end">
                                                            <div class="col-md-6"></div>
                                                            <div class="col-md-6 form-group my-1">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                <input class="form-control" value="{{$sla_setting['default_reply_time_deadline']}}" name="default_reply_time_deadline" type="number" placeholder="24" name="" id="default_reply_time_deadline" placeholder="">
                                                                @else
                                                                <input class="form-control" name="default_reply_time_deadline" type="number" placeholder="24" name="" id="default_reply_time_deadline" placeholder="">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Default resolution deadline</b></p>
                                                        <small>If a ticket is not resolved to within these hours, the ticket will be marked as overdue. </small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="row text-end">
                                                            <div class="col-md-6"></div>
                                                            <div class="col-md-6 form-group my-1">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                <input class="form-control" value="{{$sla_setting['default_resolution_deadline']}}" name="default_resolution_deadline" type="number" placeholder="72" name="" id="default_resolution_deadline">
                                                                @else
                                                                <input class="form-control" name="default_resolution_deadline" type="number" placeholder="72" name="" id="default_resolution_deadline">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Overdue ticket background color</b></p>
                                                        <small>Overdue tickets can be highlighted with a background color. Choose a light color so the text is still readable.</small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="row text-end">
                                                            <div class="col-md-3"></div>

                                                            <div class="col-md-9 form-group my-1 " style="padding-right:9px;">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                <input class="form-control" value="{{$sla_setting['overdue_ticket_background_color']}}" name="overdue_ticket_background_color" type="color" id="overdue_ticket_background_color">
                                                                @else
                                                                <input class="form-control" name="overdue_ticket_background_color" type="color" id="overdue_ticket_background_color">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Overdue ticket text & button color</b></p>
                                                        <small>Overdue tickets can be highlighted with a Text color. Choose a light color so the text is still readable.</small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="row text-end">
                                                            <div class="col-md-3"></div>
                                                            <div class="col-md-9 form-group my-1 " style="padding-right:9px;">
                                                                @if(sizeOf($sla_setting) > 0)
                                                                <input class="form-control" value="{{$sla_setting['overdue_ticket_text_color']}}" name="overdue_ticket_text_color" type="color" id="overdue_ticket_text_color">
                                                                @else
                                                                <input class="form-control" name="overdue_ticket_text_color" type="color" id="overdue_ticket_text_color">
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row ">
                                                    <div class="col-md-8">
                                                        <p><b>Clear reply due deadline when adding a ticket note</b></p>
                                                        <small>When a ticket is updated (i.e. replied to by a staff user), the reply due time clock is reset. By enabling this setting, a ticket note will also count as a ticket update. </small>
                                                        <hr>
                                                    </div>
                                                    <div class="col-md-4 text-end">
                                                        <div class="">
                                                            @if(sizeOf($sla_setting) > 0)
                                                            @if($sla_setting['reply_due_deadline_when_adding_ticket_note'] == 1)
                                                            <div class="form-check mx-2 form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault14" checked>
                                                                <label class="form-check-label" for="flexRadioDefault14"> Yes </label>
                                                            </div>
                                                            @else
                                                            <div class="form-check mx-2 form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault14" checked>
                                                                <label class="form-check-label" for="flexRadioDefault14"> Yes </label>
                                                            </div>
                                                            @endif
                                                            @else
                                                            <div class="form-check mx-2 form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault14" checked>
                                                                <label class="form-check-label" for="flexRadioDefault14"> Yes </label>
                                                            </div>
                                                            @endif

                                                            @if(sizeOf($sla_setting) > 0)
                                                            @if($sla_setting['reply_due_deadline_when_adding_ticket_note'] == 0)
                                                            <div class="form-check mx-2 form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault24" checked>
                                                                <label class="form-check-label" for="flexRadioDefault24"> No </label>
                                                            </div>
                                                            @else
                                                            <div class="form-check mx-2 form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault24">
                                                                <label class="form-check-label" for="flexRadioDefault24"> No </label>
                                                            </div>
                                                            @endif
                                                            @else
                                                            <div class="form-check mx-2 form-check-inline">
                                                                <input class="form-check-input" type="radio" name="flexRadioDefault4" id="flexRadioDefault24">
                                                                <label class="form-check-label" for="flexRadioDefault24"> No </label>
                                                            </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-end">
                                                        <button class="btn btn-success">Save</button>
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

            <div class="tab-pane show" id="tickets_departments">
                <div id="accordion" class="accordion accordion-border">
                    <div class="card mb-0">
                        <div class="" id="departments_collapse">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">Departments<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <h4 class="widget-title lighter smaller menu_title">Department Table</h4>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <button class="btn waves-effect waves-light btn-success" data-bs-toggle="" data-bs-target="" onclick="showDepModel()" style="float:right"><i class="fa fa-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Department</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <!-- <div class="row">
                                                                <div class="col-md-12" style="text-align:right;">
                                                                    <select class="mb-2form-select" name="department_column" id="department_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                                        <option value="0">Sr #</option>
                                                                        <option value="1">Name</option>
                                                                        <option value="2">Actions</option>
                                                                    </select>
                                                                </div>
                                                            </div> -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="ticket-departments-list" class="table display table-bordered table-hover ticket-departments-list" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-0">
                        <div class="" id="Status_collapse">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseStatus" aria-expanded="false" aria-controls="collapseTwo">
                                    Status <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseStatus" class="collapse" aria-labelledby="Status_collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <h4 class="widget-title lighter smaller menu_title">Status Table
                                            </h4>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <button class="btn waves-effect waves-light btn-success" onclick="showStatusModel()" style="float:right">
                                                <i class="fa fa-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Status</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">

                                    <div class="widget-main">
                                        <!-- <div class="row">
                                                                <div class="col-md-12" style="text-align:right;">
                                                                    <select class="mb-2form-select" name="status_column" id="status_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                                        <option value="0">Sr #</option>
                                                                        <option value="1">Name</option>
                                                                        <option value="2">Action</option>
                                                                    </select>
                                                                </div>
                                                            </div> -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="ticket-status-list" class="table display table-hover table-bordered ticket-status-list" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Color</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card mb-0">
                        <div class="" id="Type_collapse">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapseType" aria-expanded="false" aria-controls="collapseThree">
                                    Types <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseType" class="collapse" aria-labelledby="Type_collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <h4 class="widget-title lighter smaller menu_title">Type Table
                                            </h4>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <button class="btn waves-effect waves-light btn-success" onclick="showTypeModel()" style="float:right">
                                                <i class="fa fa-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Type</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <!-- <div class="col-md-12" style="text-align:right;">
                                                                <select class="mb-2form-select" name="type_column" id="type_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                                    <option value="0">Sr #</option>
                                                                    <option value="1">Name</option>
                                                                    <option value="2">Action</option>
                                                                </select>
                                                            </div> -->

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="ticket-type-list" class="table display table-hover table-bordered ticket-type-list" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-0">
                        <div class="" id="Priority_collapse">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2 collapsed" data-bs-toggle="collapse" href="#collapsePriority" aria-expanded="false" aria-controls="collapseThree">
                                    Priorities <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapsePriority" class="collapse" aria-labelledby="Priority_collapse" data-parent="#accordion" style="">
                            <div class="card-body">
                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <h4 class="widget-title lighter smaller menu_title">Priority
                                                Table</h4>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <button class="btn waves-effect waves-light btn-success" onclick="showPriorityModel()" style="float:right">
                                                <i class="fa fa-plus-circle" style="padding-right:3px;"></i>&nbsp;Add Priority</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <!-- <div class="col-md-12" style="text-align:right;">
                                                                <select class="mb-2form-select" name="priority_column" id="priority_column" placeholder="Show/Hide" multiple="multiple" selected="selected" style="text-align:left;">
                                                                    <option value="0">Sr #</option>
                                                                    <option value="1">Name</option>
                                                                    <option value="2">Color</option>
                                                                    <option value="3">Action</option>
                                                                </select>
                                                            </div> -->
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="ticket-priority-list" class="table display table-hover table-bordered ticket-priority-list" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Name</th>
                                                            <th>Color</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tab-pane" id="tickets_mails">
                <div class="card-body">
                    <div class="widget-header widget-header-small">
                        <div class="row">
                            <div class="col-md-12 col-sm-6" style="text-align:right">
                                <button class="btn waves-effect waves-light btn-success" onclick="showPop3Model('tickets')"><i class="fa fa-plus-circle"></i>&nbsp;Add New
                                    Mail</button>
                            </div>
                        </div>
                        <br>
                        <span class="loader_lesson_plan_form"></span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table id="ticket-mails-list" class="table display table-hover table-bordered text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Sr #</th>
                                                    <th> </th>
                                                    <th>Email Queue Address</th>
                                                    <th>Type</th>
                                                    <th>Department</th>
                                                    <th>Registered</th>
                                                    <th>Default</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="loader_container" id="emailtableloader">
                                        <div class="loader"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane" id="response_temp">
                <div id="accordion" class="accordion accordion-border">

                    <div class="mb-0">
                        <div class="" id="headingOne">
                            <h5 class="m-0">
                                <a class="accordion-button d-flex align-items-center pt-2 pb-2" data-bs-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Response templete Category <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion" style="">
                            <div class="card-body">

                                <div class="widget-header widget-header-small">
                                    <div class="row">
                                        <div class="col-md-8 col-sm-6">
                                            <h4 class="widget-title lighter smaller menu_title">Response Template Category
                                            </h4>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <button class="btn waves-effect waves-light btn-success rounded btn-sm" data-bs-toggle="modal" data-bs-target="#save-category-modal" style="float:right">
                                                <i class="fa fa-plus-circle" style="padding-right:3px;"></i>&nbsp;Add new Category</button>
                                        </div>
                                    </div>
                                    <br>
                                    <span class="loader_lesson_plan_form"></span>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="temp_cat_table" class="table display table-bordered table-hover project-type-list" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Id</th>
                                                            <th>Name</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->

                    <div class="card mb-0">
                        <div class="" id="headingTwo">
                            <h5 class="m-0">
                                <a class="accordion-button collapsed d-flex align-items-center pt-2 pb-2" data-bs-toggle="collapse" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Response Template <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                </a>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4 " style="border-right:1px solid grey;">
                                        <div class="row ">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control " placeholder="Search for..." id="search_res_template">
                                                <a class="srh-btn"><i class="ti-search"></i></a>
                                            </div>
                                            <div id="alltempResponse" class="w-100 pr-3 pl-3 pb-1" style="height: 700px; overflow: hidden; overflow-y: scroll;">

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <form id="save_temp_response" action="{{asset('/save-temp-response')}}" method="post">
                                            <div class="form-group my-1">
                                                <input type="hidden" id="res_id" name="res_id">
                                                <label for="departmrnt">Title</label>
                                                <input class="form-control res_title" type="text" name="title" id="title" placeholder="">
                                            </div>
                                            <div class="form-group my-1">
                                                <label for="departmrnt">Category Name</label>
                                                <select class="select2" id="cat_id" name="cat_id">
                                                </select>
                                            </div>

                                            <div class="form-group my-1">
                                                <label>Response Details</label>

                                                <textarea class="form-control" id="mymce" rows="2"></textarea>

                                            </div>

                                            <div class="row ">
                                                <div class="form-check mx-2">
                                                    <input class="form-check-input" type="radio" name="view_access" id="onlyMe">
                                                    <label class="form-check-label" for="onlyMe"> Show only to Me </label>
                                                </div>
                                                <div class="form-check mx-2 mt-1">
                                                    <input class="form-check-input" type="radio" name="view_access" id="allStaff" checked="checked">
                                                    <label class="form-check-label" for="allStaff"> Show to all Staff </label>
                                                </div>
                                            </div>
                                            <!-- <div class="row mt-3">
                                                <div class="col-md-6">
                                                    <select name="" id="" class="form-control">
                                                        <option value="Hung">Hung</option>
                                                        <option value="Hung">Hung</option>
                                                        <option value="Hung">Hung</option>
                                                        <option value="Hung">Hung</option>
                                                    </select>

                                                </div>
                                                <div class="col-md-6">
                                                    <select name="" id="" class="form-control">
                                                        <option value="Hung">Hung</option>
                                                        <option value="Hung">Hung</option>
                                                        <option value="Hung">Hung</option>
                                                        <option value="Hung">Hung</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <div class="col-md-12 mt-3 text-end">
                                                <button class="btn btn-success " type="submit">
                                                    Publish/Update
                                                </button>
                                            </div>

                                        </form>

                                        <div class="col-md-12 text-end mt-3" style="border-top:1px solid grey;">
                                            <p>
                                                Published by Staff Name
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- end card-->
                </div>
            </div>

        </div>
    </div>
</div>

<!--Ticket Modals -->



<!-- Ticket Department Modal -->
<div id="save-department" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dept">Add Department</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="widget-box widget-color-dark user-form" id="save_department" action="{{asset('save-department')}}" method="post">
                    <div class="form-group my-1">
                        <label for="departmrnt" class="d-flex mb-1 align-items-center">
                            <span>Department Name</span>
                            <div class="form-check form-switch" style="position:absolute; right:14px;">
                                <input type="checkbox" class="form-check-input" checked data-on-color="warning" data-off-color="danger" data-on-text="Enabled" data-off-text="Disabled" id="dept_is_enabled" />
                            </div>
                        </label>
                        <input class="form-control mb-1" type="text" name="name" id="dep_name" placeholder="" required>
                        <input class="form-control" type="text" name="dep_id" id="dep_id" hidden>
                    </div>
                    <div class="form-group my-1">
                        <label for="departmrnt" class="d-flex mb-1 align-items-center">
                            <span>Department Slug</span>
                        </label>
                        <input class="form-control mb-1" type="text" name="dept_slug" id="dept_slug" placeholder="" required>
                    </div>
                    <label for="departmrnt" class="d-flex mb-1 align-items-center">
                        <span class="form-check-label">Show Counter</span>
                        <div class="mx-1">
                            <input type="checkbox" class="form-check-input" name="dept_counter" id="dept_counter">
                        </div>
                    </label>
                    <div class="form-group my-1 text-end mt-2">
                        <button type="Submit" class="btn btn-rounded btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Status Modal -->
<div id="save-status" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stat"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="widget-box widget-color-dark user-form" id="save_status" action="{{asset('save-status')}}" method="post">
                    <div class="form-group my-1">
                        <label for="departmrnt">Status Title</label>
                        <input class="form-control" type="text" name="name" id="status_name" placeholder="" required>
                        <input class="form-control" type="text" name="status_id" id="status_id" hidden>
                    </div>

                    <div class="form-group my-1 selectpickertag">
                        <label>Select Department</label>
                        <select class="select2 form-control" id="department_id2" multiple="multiple" style="height: 36px;width: 100%;" required>
                        </select>
                    </div>

                    <div class="form-group my-1">
                        <label for="departmrnt">Status Color</label>
                        <input class="form-control" type="color" name="status_color" id="status_color" placeholder="Priority Color">
                    </div>

                    <div class="form-group my-1">
                        <label for="squence">Status Slug</label>
                        <input class="form-control" type="text" name="slug" id="slug" placeholder="" required>
                    </div>

                    <div class="form-group my-1">
                        <label for="squence">Status SeqNo#</label>
                        <input class="form-control" type="number" name="seq_no" id="seq_no" placeholder="" required>
                    </div>

                    <label for="departmrnt" class="d-flex mb-2 align-items-center">
                        <span>Show Counter</span>
                        <div class="mx-1">
                            <input type="checkbox" class="form-check-input" name="status_counter" id="status_counter">
                        </div>
                    </label>

                    <div class="form-group my-1 text-end mt-2">
                        <button type="submit" class="btn btn-rounded btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Type Modal -->
<div id="save-type" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="typeh2">Add Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form class="widget-box widget-color-dark user-form" id="save_ticket" action="{{asset('save-type')}}" method="post">
                    <div class="form-group my-1">
                        <label for="departmrnt">Type Title</label>
                        <input class="form-control" type="text" name="name" id="type_name" placeholder="" required>
                        <input class="form-control" type="text" name="type_id" id="type_id" hidden>
                    </div>
                    <div class="form-group my-1 selectpickertag">
                        <label class="control-label">Select Department</label>

                        <select class="select2 form-control" id="department_id1" name="department_id" multiple="multiple" style="height: 36px;width: 100%;" required>
                        </select>


                    </div>
                    <div class="form-group my-1 text-end mt-2">
                        <button type="submit" class="btn btn-rounded btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Priority Modal -->
<div id="save-priority" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prior">Add Priority</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="widget-box widget-color-dark" id="save_priority" action="{{asset('save-priority')}}" method="post">
                    <div class="form-group my-1">
                        <label for="departmrnt">Priority Title</label>
                        <input class="form-control" type="text" name="name" id="priority_name" placeholder="" required>
                        <input class="form-control" type="text" name="priority_id" id="priority_id" hidden>
                    </div>

                    <div class="form-group my-1 selectpickertag">
                        <label class="">Select Department</label>

                        <select class="select2 form-control" id="department_id" name="department_id" multiple="multiple" style="height: 36px;width: 100%;" required>
                        </select>
                    </div>

                    <div class="form-group my-1">
                        <label for="departmrnt">Priority Color</label>
                        <input class="form-control" type="color" name="priority_color" id="priority_color" placeholder="Priority Color">
                    </div>

                    <div class="form-group my-1 text-end mt-2">
                        <button class="btn btn-rounded btn-success" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- add SLA Modal -->
<div id="save-SLA-plan" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header ">
                <h5 class="modal-title" id="project_typeh2">Add SLA Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="sla_form" action="{{url('add_sla')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group my-1">
                        <label for="departmrnt">SLA Plan Title</label>
                        <input class="form-control" type="text" name="title" placeholder="" required>
                        <small class="text-end"> For example, Support tickets standard SLA plan.</small>
                        <input class="form-control" type="text" name="" id="" hidden>
                    </div>
                    <div class="form-group my-1">
                        <label for="departmrnt">Reply Deadline</label>
                        <input class="form-control" type="number" name="reply_deadline" placeholder="" id="rep-deadline" required>
                        <small class="text-end"> The number of hours by which a ticket should be replied to (following a reply from an end user). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                    </div>
                    <div class="form-group my-1">
                        <label for="departmrnt">Resolution due Deadline</label>
                        <input class="form-control" type="number" name="due_deadline" placeholder="" id="due-deadline" required>
                        <small>The number of hours by which tickets which have been assigned this SLA plan should be resolved (set to a resolved type status). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                    </div>
                    <div class="form-group my-1">
                        <div class="ml-2 my-1">
                            <div class="form-check form-check-inline">
                                <input type="radio" id="customRadio1" name="customRadio" class="form-check-input">
                                <label class="custom-control-label" for="customRadio1">Activate</label>
                            </div>
                            <div class="form-check form-check-inline ml-2">
                                <input type="radio" id="customRadio2" name="customRadio" class="form-check-input">
                                <label class="custom-control-label" for="customRadio2">Deactivate</label>
                            </div>
                        </div>
                        <small>Whether or not this SLA plan is enabled.</small>

                    </div>
                    <!-- <div class="form-group my-1">
                            <div class="custom-control custom-checkbox ml-2">
                                <input type="checkbox" id="custumCheck" name="custumCheck" class="custom-control-input">
                                <label class="custom-control-label" for="custumCheck">Is Default ?</label>
                            </div>
                            <small class="text-danger" id="war_check">Previous default plan if any will be set to normal and this one set to default plan.</small>

                        </div> -->
                    <div class="form-group my-1 text-end mt-2">
                        <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- add Category Modal -->
<div id="save-category-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="project_typeh2">Add Category Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="cat_form" action="{{url('add_cat')}}" enctype="multipart/form-data" onsubmit="return false;">
                    <div class="form-group my-1">
                        <label for="departmrnt">New Category Name</label>
                        <input class="form-control" type="text" name="name" placeholder="" required>
                    </div>
                    <div class="form-group my-1 text-end mt-2">
                        <button type="submit" class="btn  btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit Category Modal -->
<div id="edit-category-modal" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="project_typeh2">Update Category Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit_cat_form" action="{{url('update_cat_response')}}" enctype="multipart/form-data" onsubmit="return false;">
                    <div class="form-group my-1">
                        <input type="hidden" id="cat_id2" name="id">

                        <label for="departmrnt">New Category Name</label>
                        <input class="form-control" type="text" id="cat_name2" name="name" placeholder="" required>
                    </div>
                    <div class="form-group my-1 text-end mt-2">
                        <button type="submit" class="btn  btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- edit SLA Modal -->
<div id="edit-SLA-plan" class="modal fade" tabindex="-1" role="dialog" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="project_typeh2">Edit SLA Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="edit_sla_form" action="{{url('update_sla')}}" enctype="multipart/form-data" onsubmit="return false;">
                    <div class="form-group my-1">
                        <input type="hidden" id="sla_id" name="id">
                        <label for="departmrnt">SLA Plan Title</label>
                        <input class="form-control" type="text" name="title" id="reply_title" placeholder="">
                        <small class="text-end"> For example, Support tickets standard SLA plan.</small>
                        <input class="form-control" type="text" name="" id="" hidden>
                    </div>
                    <div class="form-group my-1">
                        <label for="departmrnt">Reply Deadline</label>
                        <input class="form-control" type="number" name="reply_deadline" id="reply_deadline" placeholder="">
                        <small class="text-end"> The number of hours by which a ticket should be replied to (following a reply from an end user). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                    </div>
                    <div class="form-group my-1">
                        <label for="departmrnt">Resolution due Deadline</label>
                        <input class="form-control" type="number" name="due_deadline" id="due_deadline" placeholder="">
                        <small>The number of hours by which tickets which have been assigned this SLA plan should be resolved (set to a resolved type status). Please type the number of hours and minutes separated by a decimal point (i.e 1.30 becomes 1 hour and 30 minutes)</small>
                    </div>
                    <div class="form-group my-1">
                        <div class="ml-2 my-1">
                            <div class="form-check form-check-inline">
                                <input type="radio" id="edit_customRadio1" name="customRadio" class="form-check-input">
                                <label class="custom-control-label" for="customRadio1">Activate</label>
                            </div>
                            <div class="form-check form-check-inline ml-2">
                                <input type="radio" id="edit_customRadio2" name="customRadio" class="form-check-input">
                                <label class="custom-control-label" for="customRadio2">Deactivate</label>
                            </div>
                        </div>
                        <small>Whether or not this SLA plan is enabled.</small>
                    </div>
                    <!-- <div class="form-group my-1">
                            <div class="custom-control custom-checkbox ml-2">
                                <input type="checkbox" id="edit_custumCheck" name="edit_custumCheck" class="custom-control-input">
                                <label class="custom-control-label" for="edit_custumCheck">Is Default ?</label>
                            </div>
                            <small class="text-danger" id="war_check_edit">Previous default plan if any will be set to normal and this one set to default plan.</small>
                        </div> -->
                    <div class="form-group my-1 text-end mt-2">
                        <button type="submit" class="btn btn-rounded btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>