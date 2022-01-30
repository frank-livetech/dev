@extends('layouts.customer-layout')
@section('body-content')
<style>
    html {
  scroll-behavior: smooth;
}
.flagSpot{
    border-right:5px solid red;
}
.flagSpot i{
    color:red;
}
.pt-6{
    padding-top: 1.5rem !important;
}
.innerBox{
    font-size: 15px;
    height: 160px;
    overflow-y: scroll;
}
#dropD label{
    font-size:0.75rem;
}
#dropD{
    padding-left: 15px;
}
.dropD{
    background-color:#009efb;
    color:#fff;
}

.dropD h5{
    color:#fff;
    font-size:0.8rem;
    font-weight:600;
}
.select2-container--default .select2-selection--single .select2-selection__arrow,
.select2-container--default .select2-selection--single,
.select2-container--default .select2-selection--single .select2-selection__rendered {
    border-color: #848484;
    color: #54667a;
    font-size: 0.8rem;
    height: 27px !important;
    line-height: 27px;
    margin-bottom:9px;
}
.br-white{
    border-right:0.5px solid #fff;
}
    .card {
        margin-bottom: 15px !important;
    }
    .row{
        margin-bottom: 5px;
    }
    .mb-3, .mt-3{
        margin-bottom: 0px !important;
        margin-top: 0px !important;
    }
    .card-body{
        padding:0.3rem !important;
    }
    .p-4 {
        padding: 0.2rem!important;
    }
    .end_padding{
        padding-right: 11px !important;
        padding-left: 11px !important;
    }
    .four_pad{
        padding-right: 0px !important;
        padding-left: 0px !important;
    }
    .dorpdown_font{
        font-size:12;
        font-weight:700;
    }
    @media (min-width: 576px){
        .modal-dialog {
            max-width: 700px;
            margin: 1.75rem auto;
        }
    }

    .reply-attachs-container {
        display: inline-block;
        position: relative;
        width: 120px !important;
        height: 120px !important;
        margin: 0 8px 8px 0;
        border: 1px solid silver;
    }

    .reply-image {
        opacity: 1;
        display: block;
        width: 100%;
        height: inherit !important;
        transition: .5s ease;
        backface-visibility: hidden;
        overflow: hidden;
    }

    .reply-bottom {
        transition: .5s ease;
        opacity: 0;
        position: absolute;
        bottom: 1%;
        left: 50%;
        transform: translate(-50%, 2%);
        -ms-transform: translate(-50%, 2%);
        text-align: center;
        width: 100%;
        height: inherit;
    }

    .reply-attachs-container:hover .reply-image {
        opacity: 0.3;
    }

    .reply-attachs-container:hover .reply-bottom {
        opacity: 1;
    }

    .reply-action {
        background-color: #000;
        padding: 5px;
    }

    .reply-filename {
        width: 100%;
        margin-bottom: 10px;
        color: #000;
        font-size: 14px;
        height: 80px;
        overflow: hidden;
        text-overflow: ellipsis;
        overflow-wrap: anywhere;
    }

    #followup-recurrence li {
        flex-grow: 4;
        padding-top: 7px;
        padding-bottom: 7px;
    }

    #week-days-list {
        display: flex;
        flex-wrap: wrap;
        /* padding-bottom: 1rem; */
    }

    #week-days-list .form-group {
        margin-bottom: 0px !important;
        padding: 1rem;
        width: 150px;
    }
    
    .fileName{
    position: absolute;
    padding-left:9px;
    padding-right:9px;
    top: 7px;
    font-size: 11px;
    display:none;
    color:#777;
    text-align:left;
    word-break: break-all;
}
.downFile{
    position: absolute;
    bottom: 4px;
    right: 65px;
    border-radius: 4px;
    color: #fff;
    padding: 2px 10px;
    background: rgba(0,0,0,0.6);
    border: 1px solid #777;
    display:none;
}
.downFile:hover{
    background:rgba(0,0,0,0.7);
    border: 1px solid #777;

}
.downFile:hover i{
    color: #fff;
}
.borderOne{
    border: 1px solid #e6e7e8;
    text-align: center;
    width: 100%;
    min-height: 94px;
    /* padding: 29px 12px; */
    /* padding-top: 21%; */
    transition: 0.3s ease;
    position: relative;
}
.borderOne:hover .downFile
{
   display:block;
}
.borderOne:hover .fileName,
.borderOne:hover .overlayAttach
{
   display:block;
}
.borderOne img{
    width: 35px;
}
.xlIcon{
    width:41px;
}
.imgIcon{
    width:48px;
}
.overlayAttach{
    position: absolute;
    background: rgba(0,0,0,0.5);
    top: 0;
    right: 15px;
    left: 15px;
    bottom: 0;
    display:none;
}
/* .card__corner {
    position: absolute;
    bottom: 0;
    right: 15px;
    z-index: 2;
    width: 1.5em;
    height: 1.5em;
    background-color: #e6e7e8;
}

.card .card__corner .card__corner-triangle {
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 1.5em 1.5em 0 0;
    border-color: #e6e7e8 #fff #fff #fff;
} */

</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Tickets Manager</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{url()->previous()}}">Customer profile</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ticket Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
                
    <div class="row">
        <!-- <input type="hidden" value="{{ $ticket_customer->id }}" id="tkt_cust_id"> -->
        <div class="col-lg-5">
        <input type="hidden" value="{{Auth::user()->id}}" id="loggedInUser">
            <div class="card" style="height:220px;">
                <div class="card-body " style="padding-bottom: 0px !important;">
                    <h5 class="card-title">Ticket ID: {{$details->coustom_id}}</h5>
                    <div class="profile-pic mb-3 mt-3">
                        <div class="row ml-0 mr-0">
                            <div class="col-lg-6 col-md-6 text-center">
                                @if($ticket_customer->avatar_url != NULL)
                                <img src="../files/user_photos/cust_profile_img/{{$ticket_customer->avatar_url}}" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                                @else($ticket_customer->avatar_url == NULL)
                                <img src="../files/user_photos/logo.gif" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                                @endif
                                <!-- <img src="../assets/images/users/5.png" width="100" class="rounded-circle" alt="user"> -->
                                <br><br>
                                <div class="row">
                                    <div class="col-4" style="max-width:100% !important ; padding:0px !important;font-size:15px;text-align:center">
                                        <h3 class="font-weight-bold" style="font-size: 15px;text-align:center"> <a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" style="color:#000;">{{$total_tickets_count}}</a></h3>
                                        <h6 style="font-size: 13px;"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" style="color:#000;">Total</a></h6></div>
                                    <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:15px;text-align:center">
                                        <h3 class="font-weight-bold" style="font-size: 15px;text-align:center"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" style="color:#000;">{{$open_tickets_count}}</a></h3>
                                        <h6 style="font-size: 13px;"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" style="color:#000;">Open</a></h6></div>
                                    <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:15px;text-align:center">
                                        <h3 class="font-weight-bold" style="font-size: 15px;text-align:center"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" style="color:#000;">{{$closed_tickets_count}}</a></h3>
                                        <h6 style="font-size: 13px;"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" style="color:#000;">Closed</a></h6></div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 innerBox" id="style-5" style="font-size: 1rem;">
                                <p style="margin-bottom: 0.2rem; !important">Name : {{ $ticket_customer->first_name }} {{ $ticket_customer->last_name }}</p>
                                <p style="margin-bottom: 0.2rem; !important" id="cst-company"></p>
                                <p style="margin-bottom: 0.2rem; !important">Direct Line : <a href="tel:{{ $ticket_customer->phone }}" id="cst-direct-line">{{ $ticket_customer->phone }}</a> </p>
                                <p style="margin-bottom: 0.2rem; !important" id="cst-company-name"></p>
                                <p style="margin-bottom: 0.2rem; !important">Email : <a href="mailto:{{ $ticket_customer->email }}" id="cst-email">{{ $ticket_customer->email }}</a>  </p>
                                <p style="margin-bottom: 0.2rem; !important">Client Since : {{ \Carbon\Carbon::parse($ticket_customer->created_at)->format('m/d/Y')}}</p>
                            </div>
                        </div>
                        <div class="row">
                            
                            
                        </div>   
                        
                        <!--<a href="mailto:danielkristeen@gmail.com">danielkristeen@gmail.com</a>-->
                    </div>
                    
                </div>
                
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card p-2" style="height:220px;overflow-y:scroll;overflow-x:hidden">
                <div class="card-body">
                    <h4 class="card-title">Initial Request&nbsp;&nbsp;
                            <!-- <span id="ticket-timestamp" style="font-size:12px; font-weight:400;"></span>
                            <span style="float:right;cursor:pointer" title="Edit Initial Request" id="edit_request_btn">
                                <a onclick="editRequest()"><i class="mdi mdi-lead-pencil"></i></a>
                            </span>
                        <span style="float:right;cursor:pointer;display:none" title="Cancel" id="cancel_request_btn">
                            <a onclick="cancelEditRequest()"><i class="mdi mdi-window-close text-danger" style="margin-left: 5px;"></i></a>
                        </span>
                        <span style="float:right;cursor:pointer;display:none" title="Save" id="save_request_btn">
                            <a onclick="saveRequest()"><i class="mdi mdi-floppy text-success"></i></a>
                        </span> -->
                    </h4>
                    <h6 id="ticket_subject_heading">Subject : {{$details->subject}}</h6>
                    <p id="ticket_details_p">Response : {{$details->ticket_detail}}</p>
                    <div class="form-group mb-0" id="ticket_subject_edit_div" style="display:none">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <div class="row " style="padding:10px 15px">
                                    <label class="control-label col-sm-2 pt-2" required="">Subject</label><span id="subject" style="display:none;color:red">subject cannot be empty</span>
                                    <div class=" col-sm-10">
                                        <input type="text" id="ticket_subject_edit" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>    
                    <div class="form-group" id="ticket_details_edit_div" style="display:none">
                        <div class="row mt-3">
                            <div class="col-sm-12">
                                <label class="control-label col-sm-12" required="">Ticket Details</label><span id="ticket-details" style="display:none;color:red">Ticket Details cannot be empty</span>
                                <div class="col-md-12">
                                    <textarea class="form-control col-sm-12" rows="3" id="ticket_details_edit" name="ticket_details_edit" required ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="d-flex">
                    <div class="nav flex-column nav-pills col-2 border-right" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="v-pills-notes-tab" data-toggle="pill" href="#v-pills-notes" role="tab" aria-controls="v-pills-notes" aria-selected="true">Notes</a>
                        <a class="nav-link" id="v-pills-assets-tab" data-toggle="pill" href="#v-pills-assets" role="tab" aria-controls="v-pills-assets" aria-selected="false">Asset Manager</a>
                        <a class="nav-link" id="v-pills-billing-tab" data-toggle="pill" href="#v-pills-billing" role="tab" aria-controls="v-pills-billing" aria-selected="false">Billing Details</a>
                        <a class="nav-link" id="v-pills-audit-tab" data-toggle="pill" href="#v-pills-audit" role="tab" aria-controls="v-pills-audit" aria-selected="false">Audit</a>
                        <a class="nav-link" id="v-pills-followup-tab" data-toggle="pill" href="#v-pills-followup" role="tab" aria-controls="v-pills-followup" aria-selected="false">Follow Ups</a>
                    </div>
                    <div class="tab-content col-10 p-0" id="v-pills-tabContent" style="max-height: 300px; overflow-y: auto;">
                        <div class="tab-pane fade show active p-2" id="v-pills-notes" role="tabpanel" aria-labelledby="v-pills-notes-tab"></div>

                        <div class="tab-pane fade show p-2" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                            <div class="col-12 px-0 text-right">
                                <button type="button" class="btn btn-success" onclick="ShowAssetModel()">
                                    <i class="mdi mdi-plus-circle"></i>&nbsp;Add Asset
                                </button>
                            </div>
                            <div class="col-12 px-0 my-2">
                                <div class="table-responsive">
                                    <table id="asset-table-list"
                                        class="table table-striped table-bordered no-wrap asset-table-list">
                                        <thead>
                                            <tr>
                                                <th><div class="text-center"><input type="checkbox" id="checkAll" name="assets[]" value="0"></div></th>
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
                        
                        <div class="tab-pane fade p-2" id="v-pills-billing" role="tabpanel" aria-labelledby="v-pills-billing-tab"></div>
                        
                        <div class="tab-pane fade p-2" id="v-pills-audit" role="tabpanel" aria-labelledby="v-pills-audit-tab">
                            <div class="col-md-12 audit-log">
                                <div class="table-responsive">
                                    <table id="ticket-logs-list"
                                        class="table table-striped table-bordered no-wrap ticket-table-list w-100">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Activity</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade p-2" id="v-pills-followup" role="tabpanel" aria-labelledby="v-pills-followup-tab">
                            <div class="w-100 text-right">
                                <button class="btn btn-success" data-toggle="modal" data-target="#follow_up">
                                    <span class="fa fa-plus-circle"></span> Add Follow Up
                                </button>
                            </div>
                            <div id="accordion" class="custom-accordion">
                                <div class="card mb-0 shadow-none">
                                    <div class="w-100 pt-4" id="clockdiv"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <div class="row">

        <div class="col-md-12">
                
            <div class="card p-0 dropD">

                <div class="card-body p-0" style="padding:0 !important;">

                    <div class="row" id="dropD" style="margin-right:-5px;margin-bottom:0 !important;">
                        <div class="col-md-2 br-white" id="dep-label">
                            <label class="control-label col-sm-12 end_padding" >Department</label>
                            <h5 class="end_padding selected-label"  id="dep-h5">Selected</h5>
                            <select class="select2 form-control " id="dept_id" name="dept_id" style="width: 100%; height:36px;" disabled> 
                                
                                @foreach($departments as $department)
                                    <option  value="{{$department->id}}" {{ $department->id == $details->dept_id ? 'selected' : '' }} >{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="tech-label">
                            <label class="control-label col-sm-12 end_padding">Tech Lead</label>
                            <h5 class="end_padding selected-label"  id="tech-h5">Selected</h5>
                            <select class="select2 form-control " id="assigned_to" name="assigned_to" style="width: 100%; height:36px;" disabled>
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ $user->id == $details->assigned_to ? 'selected' : '' }}>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="type-label">
                            <label class="control-label col-sm-12 end_padding">Type</label>
                            <h5 class="end_padding selected-label"  id="type-h5">Selected</h5>
                            <select class="select2 form-control " id="type" name="type" style="width: 100%; height:36px;" disabled>
                                @foreach($types as $type)
                                    <option value="{{$type->id}}" {{ $type->id == $details->type ? 'selected' : '' }}>{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="status-label">
                            <label class="control-label col-sm-12 end_padding">Status</label>
                            <h5 class="end_padding selected-label"  id="status-h5"></h5>
                            <select class="select2 form-control " id="status" name="status" style="width: 100%; height:36px;" >
                                
                                @foreach($statuses as $status)
                                    <option value="{{$status->id}}" {{ $status->id == $details->status ? 'selected' : '' }}>{{$status->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="prio-label">
                            <label class="control-label col-sm-12 end_padding" >Priority</label>
                            <h5 class="end_padding selected-label" id="prio-h5"></h5>
                            <select class="select2 form-control " id="priority" name="priority" style="width: 100%; height:36px;">
                                
                                @foreach($priorities as $priority)
                                @if($priority->id == $details->priority)
                                    <option value="{{$priority->id}}">{{$priority->name}} </option>
                                @endif
                                @endforeach

                                @foreach($priorities as $priority)
                                    <option value="{{$priority->id}}">{{$priority->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 chim text-center {{$details->is_flagged == 1 ? 'flagSpot' : ''}}">
                            <a type="button" class="pt-3" id="flag">
                                <i  class="fas fa-flag"></i>
                            </a>    
                            <!-- <span style="cursor:pointer;" onclick="flagTicket(this, 33);" aria-hidden="true"></span> -->
                        </div>

                    </div>
                    <!-- <div clas="row" style="text-align:right">
                        <div class="col-lg-12">
                            <small>*All dropdown saved automatically on change</small>
                        </div>    
                    </div>     -->

                </div>
            </div>
            
        </div>

    </div>
    
    <div class="row">
        <div class="col-lg-12">
            {{-- <div class="card">
                <div class="card-body">
                    
                    <h4 class="mb-3">Write a reply</h4>
                    
                    <textarea id="mymce" name="reply"></textarea>
                    <div class="row mt-4">
                        <div class="col-md-6" style="padding-top: 30px;">
                            <div class="checkbox">
                                <input id="checkbox0" type="checkbox" >
                                <label class="mb-0" for="checkbox0"> Do not mail response </label>
                            </div>
                        </div>    
                        <div class="col-md-6">   
                            <button type="button" class="mt-3 btn waves-effect waves-light btn-success" style="float: right;margin-left:5px;width: 165px;" onclick="publishReply(this)"><div class="spinner-border text-light" role="status" style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                <span class="sr-only">Loading...</span>
                                </div>Reply</button>
                        </div>
                    </div>
                </div>
            </div> --}}
            
            <div class="card">
                <div class="card-body" >
                    <h4 class="card-title">Ticket Replies
                        <a href="#v-pills-tab" class="btn btn-outline-success float-right" onclick="composeReply()">
                            Compose 
                        </a>
                    </h4>

                    <!-- {{-- @if (!empty($replies))
                        @if (!empty($replies))
                            From {{ $replies['header']['From'] }} on {{ $replies['header']['Date'] }}
                            {!! html_entity_decode(nl2br(e($replies['charset']))) !!}
                            @foreach ($replies['attachments'] as $item)
                                {!! html_entity_decode(nl2br(e($item['data']))) !!}
                            @endforeach
                        @endif
                    @endif --}} -->
                    <div class="mt-4 d-none" id="compose-reply">
                        <div class="" style="margin-top: 10px;">
                            <label for="to_mails">CC <span class="help"> e.g. "example@gmail.com"</span></label>
                            <input type="text" id="to_mails" name="to_mails" class="form-control" placeholder="Email"  data-role="tagsinput" value="" required>
                            {{-- <label class="mr-2 mb-0">CC: </label>
                            <select class="select2 form-control" id="cc-contacts" multiple="multiple" style="height: 36px;width: 100%;"></select> --}}
                        </div>

                        <label style="margin-top: 10px;">Write a reply</label>
                        
                        <textarea id="mymce" name="reply"></textarea>
                        
                        <div class="form-group pt-3">
                            <button class="btn btn-outline-primary btn-sm" type="button" onclick="addAttachment('replies')"><span class="fa fa-plus"></span> Add Attachment</button>
                            <div class="col-12 p-0" id="replies_attachments"></div>
                        </div>

                        <div class="row mt-4 reply-btns">
                            <div class="col-md-4" style="padding-top: 30px;">
                                <div class="checkbox">
                                    <input id="checkbox0" type="checkbox" >
                                    <label class="mb-0" for="checkbox0"> Do not mail response </label>
                                </div>
                            </div>    
                            <div class="col-md-8 text-right">
                                <button id="cancel-rply" type="button" class="mt-3 btn waves-effect waves-light btn-secondary" style="display: none;" onclick="cancelReply(this)">Cancel</button>
                                <button id="draft-rply" type="button" class="mt-3 btn waves-effect waves-light btn-info" onclick="publishReply(this, 'draft')">Save As Draft</button>

                                <button id="rply" type="button" class="mt-3 btn waves-effect waves-light btn-success" onclick="publishReply(this)">
                                    <div class="spinner-border text-light" role="status" style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    Reply
                                </button>
                            </div>
                        </div>
                    </div>

                    <ul class="list-unstyled mt-5 replies" id="ticket-replies"></ul>
                </div>
            </div>
        </div>
    </div>
                
                <!---->
                <div class="modal fade" id="asset_manager_modal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="myLargeModalLabel">Asset Manager</h4>
                                    <button type="button" class="close ml-auto" data-dismiss="modal"
                                                    aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                               <div class="row">
                                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                                <div class="card card-hover">
                                                    <div class="box p-2 rounded bg-danger text-center">
                                                        <h1 class="font-weight-light text-white">0</h1>
                                                        <h6 class="text-white">Issues</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-lg-2 col-xlg-2">
                                                <div class="card card-hover">
                                                    <div class="box p-2 rounded bg-success text-center">
                                                        <h1 class="font-weight-light text-white">10</h1>
                                                        <h6 class="text-white">Assets</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-xlg-4">
                                                <label for="example-search-input" >Search </label>
                                                <input class="form-control" type="text"  id="email" name="email" required>
                                            </div>
                                            <div class="col-md-4 col-lg-4 col-xlg-4" style="padding-top:25px">
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ticket" style="float:right;margin-bottom:5px;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset</button>
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#ticket" style="float:right;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset Category</button>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                    		    <div id="accordion" class="custom-accordion mb-4">
                                    		        <div class="card mb-0">
                                                        <div class="card-header" id="departments_collapse">
                                                            <h5 class="m-0">
                                                                <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">
                                                                    Category<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                                            <div class="card-body">
                                                               <div class="widget-header widget-header-small">
                                                                    <div class="row">
                                                                        <div class="col-md-8 col-sm-6">
                            						                        <h4 class="widget-title lighter smaller menu_title" >Category Table</h4>
                            						                    </div>
                            						                    <!--<div class="col-md-4 col-sm-6">-->
                            						                    <!--    <button  class="btn waves-effect waves-light btn-primary" data-toggle="" data-target="" onclick="showDepModel()" ><i class="fas fa-plus"></i>&nbsp;Add Department</button>-->
                            						                    <!--</div>-->
                            						                </div>
                            						                <br>
                            						                <span class="loader_lesson_plan_form"></span>
                        					                    </div>
                                                                <div class="widget-body">
                        						                    <div class="widget-main">
                                                                        <div class="row">
                                								            <div class="col-sm-12">
                                            									<table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                            										<thead>
                                            											<tr>
                                            												<th>No#</th>
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
                                                        <div class="card-header" id="departments_collapse">
                                                            <h5 class="m-0">
                                                                <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">
                                                                    Category<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                                            <div class="card-body">
                                                               <div class="widget-header widget-header-small">
                                                                    <div class="row">
                                                                        <div class="col-md-8 col-sm-6">
                            						                        <h4 class="widget-title lighter smaller menu_title" >Category Table</h4>
                            						                    </div>
                            						                    <!--<div class="col-md-4 col-sm-6">-->
                            						                    <!--    <button  class="btn waves-effect waves-light btn-primary" data-toggle="" data-target="" onclick="showDepModel()" ><i class="fas fa-plus"></i>&nbsp;Add Department</button>-->
                            						                    <!--</div>-->
                            						                </div>
                            						                <br>
                            						                <span class="loader_lesson_plan_form"></span>
                        					                    </div>
                                                                <div class="widget-body">
                        						                    <div class="widget-main">
                                                                        <div class="row">
                                								            <div class="col-sm-12">
                                            									<table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                            										<thead>
                                            											<tr>
                                            												<th>No#</th>
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
                                                        <div class="card-header" id="departments_collapse">
                                                            <h5 class="m-0">
                                                                <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 collapsed" data-toggle="collapse" href="#collapseDepartments" aria-expanded="false" aria-controls="collapseOne">
                                                                    Category<span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                                                                </a>
                                                            </h5>
                                                        </div>
                                                        <div id="collapseDepartments" class="collapse" aria-labelledby="departments_collapse" data-parent="#accordion" style="">
                                                            <div class="card-body">
                                                               <div class="widget-header widget-header-small">
                                                                    <div class="row">
                                                                        <div class="col-md-8 col-sm-6">
                            						                        <h4 class="widget-title lighter smaller menu_title" >Category Table</h4>
                            						                    </div>
                            						                    <!--<div class="col-md-4 col-sm-6">-->
                            						                    <!--    <button  class="btn waves-effect waves-light btn-primary" data-toggle="" data-target="" onclick="showDepModel()" ><i class="fas fa-plus"></i>&nbsp;Add Department</button>-->
                            						                    <!--</div>-->
                            						                </div>
                            						                <br>
                            						                <span class="loader_lesson_plan_form"></span>
                        					                    </div>
                                                                <div class="widget-body">
                        						                    <div class="widget-main">
                                                                        <div class="row">
                                								            <div class="col-sm-12">
                                            									<table id="ticket-departments-list" class="display table-striped table-bordered ticket-departments-list" style="width:100%">
                                            			d							<thead>
                                            											<tr>
                                            												<th>No#</th>
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
                                    		    </div>      
                                            </div>
                                        </div>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <!-- Notes Modal -->
                <div class="modal fade" id="notes_manager_modal" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="notesLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header d-flex align-items-center">
                                <h4 class="modal-title" id="notesLargeModalLabel">Notes</h4>
                                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                            </div>
                            <div class="modal-body">
                                <form id="save_ticket_note" action="{{asset('save-ticket-note')}}" method="post">
                                    <input type="text" id="note-id" style="display: none;">
                                    <div class="row">
                                        <div class="col-12 d-flex py-2">
                                            <label for="">Notes</label>
                                            <div class="ml-4">
                                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(255, 230, 177); cursor: pointer;" onclick="selectColor('rgb(255, 230, 177)')"></span>
                                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(218, 125, 179); cursor: pointer;" onclick="selectColor('rgb(218, 125, 179)')"></span>
                                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(195, 148, 255); cursor: pointer;" onclick="selectColor('rgb(195, 148, 255)')"></span>
                                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(151, 235, 172); cursor: pointer;" onclick="selectColor('rgb(151, 235, 172)')"></span>
                                                <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(229, 143, 143); cursor: pointer;" onclick="selectColor('rgb(229, 143, 149)')"></span>
                                            </div>
                                        </div>

                                        <div class="col-12 py-2">
                                            <div class="form-group">
                                                <textarea name="note" id="note" class="form-control" rows="10" required style="background-color: rgb(255, 230, 177)"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex py-2">
                                            <label> Note Type </label>
                                            <div class="ml-auto d-flex">
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="type" id="note-type-ticket" value="Ticket" checked onchange="changeVisibility(this.value);">
                                                    <label class="form-check-label" for="note-type-ticket">
                                                        Ticket
                                                    </label>
                                                </div>
                                                <div class="form-check mr-2">
                                                    <input class="form-check-input" type="radio" name="type" id="note-type-user" value="User" onchange="changeVisibility(this.value);">
                                                    <label class="form-check-label" for="note-type-user">
                                                        User
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="type" id="note-type-user-org" value="User Organization" onchange="changeVisibility(this.value);">
                                                    <label class="form-check-label" for="note-type-user-org">
                                                        User Organization
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex py-2">
                                            <label> Visibility </label>
                                            <select class="ml-auto" name="visibility" id="note-visibilty" required>
                                                <option value="Everyone">--Everyone--</option>
                                                <option value="Billing Department">Billing Department</option>
                                                
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-12 text-right pt-3">
                                            <button type="submit" class="btn btn-primary mr-2">Save</button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal Notes Modal -->

                <div class="modal fade" id="follow_up" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Follow Up</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="save_ticket_follow_up" action="{{asset('save-ticket-follow-up')}}" method="post">
                                <div class="modal-body">
                                    <input value="{{$details->id}}" name="ticket_id" hidden>
                                    
                                    <div class="form-row">
                                        <div class="col-md-6 form-group">
                                            <select class="form-control" name="schedule_type" id="schedule_type" onchange="showDateTimeDiv(this.value)" required>
                                                <option selected value="minutes">In minutes</option>
                                                <option value="hours">In hours</option>
                                                <option value="days">In days</option>
                                                <option value="weeks">In weeks</option>
                                                <option value="months">In months</option>
                                                <option value="years">In years</option>
                                                <option value="custom">Custom</option>
                                                <option value="time">Time (For recursive pattern)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group" id="schedule_time_div">
                                            <input class="form-control" type="number" min="1" name="schedule_time" id="schedule_time">
                                        </div>
                                        <div class="col-md-6 form-group" id="date_picker_div" style="display: none;">
                                            <input type="datetime-local" id="custom_date" class="form-control">
                                        </div>
                                        <div class="col-md-6 form-group" id="recurrence_time_div" style="display: none;">
                                            <input type="time" id="recurrence_time" class="form-control">
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="col-md-12 form-group border p-2 bg-light">
                                            <div class="form-check form-check-inline" id="general_div" style="">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="general">
                                                    <label class="custom-control-label" for="general">General</label>
                                                </div>
                                            </div>                                       
                                        </div>
                                        <br>
                                        <div class="col-md-12" id="general_details_div" style="display:none">
                                            <div class="form-row">
                                            
                                                <div class="col-md-4 form-group">
                                                    <label class="dorpdown_font">Department</label>
                                                    <select class="select2 form-control " id="follow_up_dept_id" name="follow_up_dept_id" style="width: 100%; height:36px;">
                                                        
                                                        @foreach($departments as $department)
                                                            <option value="{{$department->id}}" {{ $department->id == $details->dept_id ? 'selected' : '' }}>{{$department->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label class="dorpdown_font">Tech Lead</label>
                                                    <select class="select2 form-control " id="follow_up_assigned_to" name="follow_up_assigned_to" style="width: 100%; height:36px;">
                                                        <option value="">Unassigned</option>
                                                        @foreach($users as $user)
                                                            <option value="{{$user->id}}" {{ $user->id == $details->assigned_to ? 'selected' : '' }}>{{$user->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label class="dorpdown_font">Type</label>
                                                    <select class="select2 form-control " id="follow_up_type" name="follow_up_type" style="width: 100%; height:36px;">
                                                        @foreach($types as $type)
                                                            <option value="{{$type->id}}" {{ $type->id == $details->type ? 'selected' : '' }}>{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> 
                                            <div class="form-row">
                                                <div class="col-md-4 form-group">
                                                    <label class="dorpdown_font">Status</label>
                                                    <select class="select2 form-control " id="follow_up_status" name="follow_up_status" style="width: 100%; height:36px;">
                                                        
                                                        @foreach($statuses as $status)
                                                            <option value="{{$status->id}}" {{ $status->id == $details->status ? 'selected' : '' }}>{{$status->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-4 form-group">
                                                    <label class="dorpdown_font">Priority</label>
                                                    
                                                    <select class="select2 form-control " id="follow_up_priority" name="follow_up_priority" style="width: 100%; height:36px;">
                                                    
                                                        @foreach($priorities as $priority)
                                                        @if($priority->id == $details->priority)
                                                        <option value="{{$priority->id}}">{{$priority->name}} </option>
                                                        @endif
                                                        @endforeach
                    
                                                        @foreach($priorities as $priority)
                                                        <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <label class="dorpdown_font">Link Project</label>
                                                    <select class="select2 form-control " id="follow_up_project" name="follow_up_project" style="width: 100%; height:36px;">
                                                        
                                                        <option value="">Select</option>
                                                        @foreach($projects as $project)
                                                            <option value="{{$project->id}}">{{$project->name}} </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row" >
                                        <div class="col-md-12 form-group border p-2 bg-light">
                                            <div class="form-check form-check-inline" id="notes_div" style=""> 
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="notes">
                                                    <label class="custom-control-label" for="notes">Notes</label>
                                                </div>
                                            </div> 
                                        </div> 
                                        <div class="col-md-12 form-group" id="ticket_follow_notes" style="display:none">
                                            <textarea class="form-control" rows="3" id="follow_up_notes" name="follow_up_notes"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-md-12 form-group border p-2 bg-light">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" id="is_recurring">
                                                <label class="custom-control-label" for="is_recurring">Recurrence</label>
                                            </div>
                                        </div>

                                        <div class="col-md-12" id="followup-recurrence" style="display:none">
                                            <h4 for="">Recurrence Pattern</h4>
                                            <div class="form-group">
                                                <label for="new_time">New Time</label>
                                                <input type="time" class="form-control" name="recurrence_time2">
                                            </div>
                                            <ul class="list-group list-group-horizontal-lg pb-3">
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div class="radio radio-primary">
                                                        <input type="radio" name="recur_type" id="recur-daily" value="daily" class="d-none" checked>
                                                        <label class="mb-0" for="recur-daily"> Daily </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div class="radio radio-primary">
                                                        <input type="radio" name="recur_type" id="recur-weekly" value="weekly" class="d-none">
                                                        <label class="mb-0" for="recur-weekly"> Weekly </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div class="radio radio-primary">
                                                        <input type="radio" name="recur_type" id="recur-monthly" value="monthly" class="d-none">
                                                        <label class="mb-0" for="recur-monthly"> Monthly </label>
                                                    </div>
                                                </li>
                                                <li class="list-group-item d-flex align-items-center">
                                                    <div class="radio radio-primary">
                                                        <input type="radio" name="recur_type" id="recur-yearly" value="yearly" class="d-none">
                                                        <label class="mb-0" for="recur-yearly"> Yearly </label>
                                                    </div>
                                                </li>
                                            </ul>

                                            <div id="daily-container" class="recur-container pb-3">
                                                <div class="form-group d-flex align-items-center" style="margin-left: 16px;">
                                                    <label for="recur_after">Every &nbsp;&nbsp;</label>
                                                    <input type="number" class="form-control" id="recur_after_d" value="1" min="1" max="7" style="width: 100px;">
                                                    <label for="recur_after">&nbsp;&nbsp; day(s)</label>
                                                </div>
                                            </div>

                                            <div id="weekly-container" class="recur-container pb-3" style="display: none;">
                                                <div class="form-group d-flex align-items-center" style="margin-left: 16px;">
                                                    <label for="recur_after">Recur every &nbsp;&nbsp;</label>
                                                    <input type="number" class="form-control" id="recur_after_w" value="1" min="1" max="52" style="width: 100px;">
                                                    <label for="recur_after">&nbsp;&nbsp; week(s) on:</label>
                                                </div>
                                                <div id="week-days-list">
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="monday" value="1">
                                                            <label class="custom-control-label" for="monday">Monday</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="tuesday" value="2">
                                                            <label class="custom-control-label" for="tuesday">Tuesday</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="wednesday" value="3">
                                                            <label class="custom-control-label" for="wednesday">Wednesday</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="thursday" value="4">
                                                            <label class="custom-control-label" for="thursday">Thursday</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="friday" value="5">
                                                            <label class="custom-control-label" for="friday">Friday</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="saturday" value="6">
                                                            <label class="custom-control-label" for="saturday">Saturday</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="sunday" value="7">
                                                            <label class="custom-control-label" for="sunday">Sunday</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div id="monthly-container" class="recur-container pb-3" style="display: none;">
                                                <div class="form-group d-flex align-items-center" style="margin-left: 16px;">
                                                    <label>Day &nbsp;&nbsp;</label>
                                                    <input type="number" class="form-control" id="recur_after_m" value="1" min="1" max="31" style="width: 100px;">
                                                    <label>&nbsp;&nbsp; of every &nbsp;&nbsp;</label>
                                                    <input type="number" class="form-control" id="recur_after_month" value="1" min="1" max="12" style="width: 100px;">
                                                    <label>&nbsp;&nbsp; month(s)</label>
                                                </div>
                                            </div>

                                            <div id="yearly-container" class="recur-container pb-3" style="display: none;">
                                                <div class="form-group d-flex align-items-center" style="margin-left: 16px;">
                                                    <label>Recur every &nbsp;&nbsp;</label>
                                                    <input type="number" class="form-control" id="recur_after_y" value="1" min="1" style="width: 100px;">
                                                    <label>&nbsp;&nbsp; year(s)</label>
                                                </div>

                                                <div class="form-group d-flex align-items-center" style="margin-left: 16px;">
                                                    <label>On &nbsp;&nbsp;</label>
                                                    <select class="form-control" id="recur-month" style="width: 200px;">
                                                        <option value="January">January</option>
                                                        <option value="February">February</option>
                                                        <option value="March">March</option>
                                                        <option value="April">April</option>
                                                        <option value="May">May</option>
                                                        <option value="June">June</option>
                                                        <option value="July">July</option>
                                                        <option value="August">August</option>
                                                        <option value="September">September</option>
                                                        <option value="October">October</option>
                                                        <option value="November">November</option>
                                                        <option value="December">December</option>
                                                    </select>&nbsp;&nbsp;
                                                    <input type="number" class="form-control" id="recur_month_day" value="1" min="1" max="31" style="width: 100px;">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="recurrence-range" style="display:none">
                                            <h4 class="col-12">Recurrence Range</h4>
                                            <div class="col-md-6" id="start-range">
                                                <div class="custom-control custom-radio d-flex align-items-center py-2">
                                                    <input type="radio" id="start-after-date" name="recurrence_start" class="custom-control-input" value="date">
                                                    <label class="custom-control-label" for="start-after-date">Start date: </label>&nbsp;&nbsp;
                                                    <input type="date" class="form-control allow-req" id="recur-start-date" style="width: 200px;" disabled>
                                                </div>
                                                <div class="custom-control custom-radio d-flex align-items-center py-2">
                                                    <input type="radio" id="start-now-recur" name="recurrence_start" class="custom-control-input" value="now">
                                                    <label class="custom-control-label" for="start-now-recur">Start now</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="custom-control custom-radio d-flex align-items-center py-2">
                                                    <input type="radio" id="end-after-date" name="recurrence_end" class="custom-control-input" value="date">
                                                    <label class="custom-control-label" for="end-after-date">End by: </label>&nbsp;&nbsp;
                                                    <input type="date" class="form-control allow-req" id="recur-end-date" style="width: 200px;" disabled>
                                                </div>
                                                <div class="custom-control custom-radio d-flex align-items-center py-2">
                                                    <input type="radio" id="end-after-occurences" name="recurrence_end" class="custom-control-input" value="count">
                                                    <label class="custom-control-label" for="end-after-occurences">End after: </label>&nbsp;&nbsp;
                                                    <input type="number" class="form-control allow-req" id="end-after-occur" value="1" min="1" style="width: 100px;" disabled>
                                                    <label>&nbsp;&nbsp; occurences</label>
                                                </div>
                                                <div class="custom-control custom-radio d-flex align-items-center py-2">
                                                    <input type="radio" id="no-end" name="recurrence_end" class="custom-control-input" value="no end">
                                                    <label class="custom-control-label" for="no-end">No end date</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <div class="row">
                                        <div class="col-sm-12" style="text-align:right">
                                            <button class="btn btn-primary" type="submit">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>

            <div style="display: none;" id="tinycontenteditor"></div>

 <!--  Modal Edit pro start -->
    <div class="modal fade" id="pro_edit" role="dialog"  data-backdrop="static" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="" style="color:#009efb;">Update Customer</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form id="update_ticket_customer" action="{{url('update-ticket-customer')}}" method="post">
                        <input type="hidden" value="{{ $ticket_customer->id }}" name="tkt_cust_id" id="tkt_cust_id">
                        <div class="modal-body">
                            <div class="form-row">
                                <div class="col-md-6 form-group">
                                    <label for="">First Name</label>
                                    <input class="form-control" type="text" name="name_update" value="{{ $ticket_customer->first_name }}" id="first_name_update">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Last Name</label>
                                    <input class="form-control" type="text" name="last_name_update" value="{{ $ticket_customer->last_name }}" id="last_name_update">
                                </div>
                                <div class="col-md-6 form-group">
                                    <div class="form-group">
                                        <label>Company</label>

                                        <select id="company_list" name="company_id" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($companies as $company) 
                                                <option value="{{$company->id}}" 
                                                {{$ticket_customer->company_id == $company->id ? "selected" : '-'}}>{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Direct Line</label>
                                    <input class="form-control" type="number" name="phn_update" id="phn_update" value="{{ $ticket_customer->phone }}">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="">Email Address</label>
                                    <input class="form-control" type="email" name="email_update" id="email_update" value="{{ $ticket_customer->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <button class="btn btn-success" id="saveBtn" type="submit" >Save</button>
                                <button type="button" style="display:none" disabled id="processbtn" class="btn btn-success" ><i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal content ticket end -->
    <!--  Modal Edit pro end -->




    <div class="modal fade" id="asset" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Asset</h4>
                    <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
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
                    <button type="submit" class="btn btn-info my-3" data-dismiss="modal" > Close </button>
                        <button type="submit" class="btn btn-success my-3" > Save </button>
                    
                </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal content ticket end -->

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

<input type="hidden" id="sys_date_format" value="{{$date_format}}">

@endsection   
@section('scripts')         
<script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script>
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/moment.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/countdown/countdown.css')}}" />
<script type="text/javascript" src="{{asset('assets/extra-libs/countdown/countdown.js')}}"></script>

@include('js_files.help_desk.ticket_manager.cust_ticket_detailsJs')
@include('js_files.help_desk.ticket_manager.detailsJs')

{{-- Linked Assets JS --}}
@include('js_files.help_desk.asset_manager.actionsJs')
@include('js_files.help_desk.ticket_manager.ticketsJs')
<script src="{{asset('js/tagsinput.js')}}"></script>
@include('js_files.help_desk.asset_manager.assetJs')

@endsection