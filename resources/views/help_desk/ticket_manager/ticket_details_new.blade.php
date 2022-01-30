@extends('layouts.master-layout-new')
@section('body')
<style>
    #dropD {
    padding-left: 15px;
}
    .mt-0{
        margin: unset
    }
    .badge-primary{
        background-color:#4eafcb
    }
    .card-body.drop-dpt{
        padding: 0 !important;
    }
    span.select2-container.select2-container--default.select2-container--open{
        top: 3.9844px !important
    }
    .badge-secondary {
    color: #fff;
    background-color: #868e96;
    }
    .media-body{
        width:575px
    }
    .btn-outline-bt{
        border: 1px solid #e6e6e6;
        text-decoration: none;
        margin-left: 4px;
        color: #666;
        padding: 4px 13px 4px 13px;
        transition: all 0.25s ease;
        border-radius: 4px;
        background-color: #f4f5f5;
        vertical-align: top;
    }
    .mr-3{
        margin-right: 1rem !important;
    }
    .media {
    display: flex;
    align-items: flex-start;
}
    .innerBox{
        font-size: 15px;
        height: 100px;
        overflow-y: scroll;
    }
    #style-5::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
}
.text-white{
    color:white;
}
#style-5::-webkit-scrollbar
{
	width: 3px;
    height: 10px;
	background-color: #F5F5F5;
}

#style-5::-webkit-scrollbar-thumb
{
	background-color: #0ae;
	
	background-image: -webkit-gradient(linear, 0 0, 0 100%,
	                   color-stop(.5, rgba(255, 255, 255, .2)),
					   color-stop(.5, transparent), to(transparent));
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
.reply-btns .btn {
    margin-left:5px; width: 165px;
}

#current_url {
    background: transparent !important;
    border: transparent !important;
    color: transparent !important;
}
#current_url::selection {
  color: transparent !important;
  background: transparent !important;
}

.badge-light {
    position: relative; 
    top: 5px;
     right: 0;
     float: right !important;
}
.dropify-wrapper{
    width:124px;
    margin-left:15px;
    margin-top:10px;
}
.dropify-clear{
    display: none !important;
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
    width: 100%;
    /* max-width:89px; */
}
.xlIcon{
    width: 41px !important;
    padding-top: 20px;
}
.imgIcon{
    width: 48px !important;
    padding-top: 24px;
}
.overlayAttach{
    position: absolute;
    background: #f5f5f5;
    top: 0;
    left: 0;
    width:100%;
    height:100%;
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
.nav-tabs{
    margin-bottom: unset !important
}
.flagSpot{
    border-right:5px solid red;
}
.flagSpot i{
    color:red;
}
.float-right{
    float: right
}
.attImg{
    height:100px;
    /* width:auto !important; */
}
.modal-slide-in .modal-dialog.sidebar-lg {
    width: 55rem;
}
.tag {
    width: fit-content !important;
    padding: 0.25rem;
    border-radius: 4px;
    margin-left: 4px;
    margin-top: 4px;
}
.bootstrap-tagsinput > .bootstrap-tagsinput {
    width: 100% !important;
    border: 1px solid #ccc;
}
.bootstrap-tagsinput {
    display: flex !important;
    margin-top: 5px !important;
    box-shadow: none !important;
    flex-wrap: wrap !important;
    border:0px;
}
.label-info {
    background-color: #6d5eac !important;
}
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <input type="hidden" id="current_url" value="{{url()->current()}}">
        <div class="content-header row">
            <div class="content-header-left col-md-7 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Ticket Detail</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">Help Desk
                                </li>
                                <li class="breadcrumb-item active"><a href="{{url('ticket-manager')}}">Tickets Manager</a>
                                </li>
                                <li class="breadcrumb-item active">Ticket Detail
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="loggedInUser_id" value="{{\Auth::user()->id}}">
        <input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">
        <div class="content-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title mb-0">Ticket ID: <a href="{{asset('/ticket-details')}}/{{$details->coustom_id}}">{{$details->coustom_id}}</a><a href="javascript:void(0)" onclick="copyToClipBoard()"> 
                                <i class="far fa-copy"></i></a> <span class="small text-success" id="c_url" style="display:none">Url Copied</span>   
                                
                                {{-- <a data-target="#pro_edit" tooltip="Edit" data-toggle="modal" class="link d-flex  font-weight-medium" style="float:right; color:#000; cursor:pointer;"><i class="mdi mdi-lead-pencil"></i></a> --}}
                                <i data-feather='edit-3' onclick="openProModal();" style="position: absolute;right:21px;top:24px; cursor:pointer;" tooltip="Edit"></i>
                                <button class="btn btn-outline-bt btn-sm" type="button" style="position:absolute;right:48px;cursor:pointer;" onclick=""><i data-feather='trash-2'></i> Trash</button>
                                <button class="btn btn-outline-bt btn-sm" type="button" style="cursor:pointer;right:130px;position: absolute" onclick=""><i data-feather='alert-triangle'></i> Spam</button>
                            
                            </h5>
                            <div class="profile-pic mt-2">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 text-center">
                                        @php
                                            $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
                                        @endphp
                                        @if($details->is_staff_tkt == 0)
                                            @if($ticket_customer->avatar_url != null)
                                                @if(file_exists( getcwd() .'/'. $ticket_customer->avatar_url ))
                                                    <img src=" {{ asset( request()->root() .'/'. $ticket_customer->avatar_url)}}" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                                                @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded-circle" width="100" height="100" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                                @endif
                                            @else($ticket_customer->avatar_url == NULL)
                                            <img id="login_logo_preview" name="login_logo_preview" class="rounded-circle" width="80" height="80" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                            @endif
                                            <span class="badge badge-secondary type_bdge">User</span>
                                        @else
                                            @if($ticket_customer->profile_pic != null)
                                                @if(file_exists( getcwd() .'/'. $ticket_customer->profile_pic ) )
                                                    <img src="{{ asset( request()->root() .'/'. $ticket_customer->profile_pic)}}" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                                                @else
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded-circle" width="100" height="100" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                                @endif
                                            @else($ticket_customer->profile_pic == NULL)
                                                    <img id="login_logo_preview" name="login_logo_preview" class="rounded-circle" width="80" height="80" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                            @endif
                                            <span class="badge badge-secondary type_bdge">Staff</span>
                                        
                                        @endif
                                        <br><br>
                                        
                                    </div>
                                    @php

                                        $name = '';
                                        $phone = '';
                                        $email = '';

                                        if($details->is_staff_tkt == 0){
                                            $name = $ticket_customer->first_name .' '. $ticket_customer->last_name;
                                            $phone = $ticket_customer->phone;
                                            $email = $ticket_customer->email;
                                        }else{
                                            $name = $ticket_customer->name;
                                            $phone = $ticket_customer->phone_number;
                                            $email = $ticket_customer->email;
                                        }

                                    @endphp
                                    <div class="col-lg-9 col-md-8 innerBox" id="style-5" style="">
                                        <p style="margin-bottom: 0.2rem; !important">Name : <a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" id="cst-name"> {{ $name }} </a></p>
                                        <p style="margin-bottom: 0.2rem; !important" id="cst-company"></p>
                                        <p style="margin-bottom: 0.2rem; !important">Direct Line : <a href="tel:{{ $phone }}" id="cst-direct-line">{{ $phone }}</a> </p>
                                        <p style="margin-bottom: 0.2rem; !important" id="cst-company-name"></p>
                                        <p style="margin-bottom: 0.2rem; !important">Email : <a href="mailto:{{ $email }}" id="cst-email">{{ $email }}</a>  </p>
                                        <p style="margin-bottom: 0.2rem; !important">Client Since : <span id="cust-creation-date"></span></p>
                                    </div>
                                    <hr>
                                    <div class="row ">
                                        <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:12px; text-align:center">
                                            <h3 class="font-weight-bold" 
                                            style="text-align:center"> 
                                            <a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#tickets" class="text-primary">{{$total_tickets_count}}</a></h3>
                                            <h6 class="mb-0"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#tickets" class="text-primary">Total</a></h6>
                                        </div>
                                        <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:12px; text-align:center">
                                            <h3 class="font-weight-bold" style="text-align:center"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#ticket-open" class="text-primary">{{$open_tickets_count}}</a></h3>
                                            <h6 class="mb-0"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#ticket-open" class="text-primary">Open</a></h6>
                                        </div>
                                        <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:12px; text-align:center">
                                            <h3 class="font-weight-bold" style="text-align:center"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" class="text-primary">{{$closed_tickets_count}}</a></h3>
                                            <h6 class="mb-0"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" class="text-primary">Closed</a></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    
                                </div>   
                                
                                <!--<a href="mailto:danielkristeen@gmail.com">danielkristeen@gmail.com</a>-->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card" id="style-5" style="height:270px; overflow-y:auto; overflow-x:hidden">
                        <div class="card-header frst">
                            <div class="align-items-center ">
                                <div class="mail-items">
                                    <h3 class="mb-0">Initial Request&nbsp;&nbsp;
                                        <span id="ticket-timestamp" style="font-size:12px; font-weight:400;"></span>
                                        <a onClick="hung()" title="View Details" style="position:absolute;right:62px;cursor:pointer;">
                                            <i data-feather='maximize'></i>
                                        </a>
                                        <!-- <a class=" " style="position:absolute;right:50px;cursor:pointer;"  data-bs-toggle="modal" data-bs-target="#viewFullDetails" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details" data-bs-original-title="View Details"><i data-feather='maximize'></i></a> -->
                                        <span class="float-end" style="float:right; cursor:pointer" title="Edit Initial Request" id="edit_request_btn">
                                        <a onclick="editRequest()"><i data-feather='edit-3'></i></a></span>
                                        <span style="float:right; cursor:pointer; display:none" title="Cancel" id="cancel_request_btn">
                                        <a onclick="cancelEditRequest()">
                                            <i data-feather='x' class="text-danger" style="margin-left: 5px;"></i></a></span>
                                        <span style="float:right;cursor:pointer;display:none" title="Save" id="save_request_btn">
                                            <a onclick="saveRequest()">
                                                <i data-feather='save'></i>
                                            </a>
                                        </span>
                                    </h3>
                                    <br>
                                    <h4 id="ticket_subject_heading">Subject : {{$details->subject}}</h4>
                                    <hr>
                                    <div class="form-group mb-0" id="ticket_subject_edit_div" style="display:none">
                                        <div class="row mt-3">
                                            <div class="col-sm-12">
                                                <div class="row " >
                                                    <h4 class="control-label col-sm-12" required="">Subject</h4><span id="subject" style="display:none;color:red">subject cannot be empty</span>
                                                    <div class=" col-sm-10">
                                                        <input type="text" id="ticket_subject_edit" class="form-control" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    <div class="form-group" id="ticket_details_edit_div" style="display:none">
                                        <div class="row mt-3">
                                            <h4 class="control-label col-sm-12" required="">Ticket Details</h4><span id="ticket-details" style="display:none;color:red">Ticket Details cannot be empty</span>
                                            <div class="col-md-12">
                                                <textarea class="form-control col-sm-12" rows="3" id="ticket_details_edit" name="ticket_details_edit" required ></textarea>
                                            </div>
                                            <div class="col-12 pt-3">
                                                
                                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="addAttachment('tickets')"><span class="fa fa-plus"></span> Add Attachment</button>
                                                <div class="form-group" id="tickets_attachments"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="card-header email-detail-head sec d-none" >
                            <div class="user-details d-flex justify-content-between align-items-center flex-wrap pb-1" style="border-bottom:1px solid #ebe9f1;">
                                <div class="mail-items">
                                    <h5 class="mb-0">Subject : {{$details->subject}}</h5>
                                </div>
                                <div class="mail-meta-item d-flex align-items-center">
                                    <small class="mail-date-time text-muted" id="ticket-timestamp2"></small>
                                    <div class="dropdown ms-50">
                                        <div role="button" class="dropdown-toggle hide-arrow" id="email_more" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i data-feather="more-vertical" class="font-medium-2"></i>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="email_more">
                                            <div class="dropdown-item" onclick="editRequest()"><i data-feather="corner-up-left" class="me-50"></i>Edit</div>
                                            <div class="dropdown-item" onclick="toggleReq()"><i data-feather="eye-off" class="me-50"></i>Close</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div> -->
                        <div class="card-body mail-message-wrapper pt-2 sec d-none">
                            <div class="mail-message">
                                <div class="row" id="ticket_details_p2"></div>
                            </div>
                        </div>
                        <div class="card-body mail-message-wrapper frst">
                            <div class="mail-message">
                                <div class="row" id="ticket_details_p"></div>
                            </div>
                        </div>
                       
                        {{-- <div class="card-footer">
                            <div class="mail-attachments">
                                <div class="d-flex align-items-center mb-1">
                                    <i data-feather='paperclip'></i>&nbsp;
                                    <h5 class="fw-bolder text-body mb-0">1 Attachments</h5>
                                </div>
                                <div class="row d-flex flex-column">
                                    <a href="#" class="mb-50">
                                        <img src="../../../app-assets/images/icons/doc.png" class="me-25" alt="png" height="18">
                                        <small class="text-muted fw-bolder">interdum.docx</small>
                                    </a>
                                    
                                </div>
                            </div>
                        </div> --}}
                    </div>  
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class=" nav-vertical">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="nav nav-tabs nav-left w-100" id="v-pills-tab" role="tablist" style="border-right:1px solid rgb(185, 183, 183);">
                                        <a class="nav-link active" id="v-pills-notes-tab" data-bs-toggle="tab" href="#v-pills-notes" role="tab" aria-controls="tabVerticalLeft1" aria-selected="true">Notes</a>
                                        <a class="nav-link" id="v-pills-assets-tab" data-bs-toggle="tab" href="#v-pills-assets" role="tab" aria-controls="v-pills-assets" aria-selected="false">Asset Manager</a>
                                        <a class="nav-link" id="v-pills-billing-tab" data-bs-toggle="tab" href="#v-pills-billing" role="tab" aria-controls="v-pills-billing" aria-selected="false">Billing Details</a>
                                        <a class="nav-link" id="v-pills-audit-tab" data-bs-toggle="tab" href="#v-pills-audit" role="tab" aria-controls="v-pills-audit" aria-selected="false">Audit</a>
                                        <a class="nav-link" id="v-pills-followup-tab" data-bs-toggle="tab" href="#v-pills-followup" role="tab" aria-controls="v-pills-followup" aria-selected="false">Follow Ups</a>
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="tab-content" id="v-pills-tabContent style-5" style="max-height: 187px; overflow-y: auto;">
                                        <div class="tab-pane fade show active p-2" id="v-pills-notes" role="tabpanel" aria-labelledby="v-pills-notes-tab">
                                            <div class="col-12 text-right">
                                                <button class="btn btn-success" data-bs-toggle="modal" onclick="openNotesModal()" style="float: right; margin-bottom: 3px"><i class="fa fa-plus-circle"></i> Add Note</button>
                                            </div>
                                            <div class="col-12" id="v-pills-notes-list"></div>
                                        </div>
                
                                        <div class="tab-pane fade show p-2" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                                            <div class="row">
                                            <div class="col-12 px-0 text-right">
                                                <button type="button" class="btn btn-success" onclick="ShowAssetModel()" style="float: right">
                                                    <i class="fa fa-plus-circle"></i>&nbsp;Add Asset
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 px-0 my-2">
                                                <div class="table-responsive" width='200px'>
                                                    <table id="asset-table-list"
                                                        class="table table-striped w-100 table-bordered no-wrap asset-table-list">
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
                                        </div>
                                        
                                        <div class="tab-pane fade p-2" id="v-pills-billing" role="tabpanel" aria-labelledby="v-pills-billing-tab"></div>
                                        
                                        <div class="tab-pane fade p-2" id="v-pills-audit" role="tabpanel" aria-labelledby="v-pills-audit-tab">
                                            <div class="col-md-12 audit-log">
                                                <div class="table-responsive">
                                                    <table id="ticket-logs-list"
                                                        class="table table-striped table-bordered no-wrap ticket-table-list w-100">
                                                        <thead>
                                                            <tr>
                                                                <th width='20'>ID</th>
                                                                <th>Activity</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="tab-pane fade p-2" id="v-pills-followup" role="tabpanel" aria-labelledby="v-pills-followup-tab">
                                            <div class="w-100">
                                                <button class="btn btn-success float-right" onclick="showFollowUpModal()">
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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    @if (!empty($ticket_slaPlan))
                        <div class="card p-0" id="card-sla" style="background-color: unset !important">
                            <div class="card-body p-1" id="ticket-sla-plan">
                                <div class="row" >
                                    <div class="col-md-9">
                                        <p style="font-size: 13px;margin-bottom: unset !important"">
                                            <span class="sla-selc"><strong>Reply due: </strong> <span class="text-red mr-2" id="sla-rep_due"></span></span>
                                            <span class="sla-selc"><strong>Resolution due: </strong><span class="text-blue mr-2" id="sla-res_due"></span></span>
                                            <strong>SLA plan: </strong><span class="text-red mr-2" id="sla-title">{{$ticket_slaPlan->title}}</span>  
                                            <span class="sla-selc"><Strong>Created: </Strong><span class="text-red mr-2" id="creation-date"></span></span>
                                            <span class="sla-selc"><strong>Updated: </strong><span class="text-red mr-2" id="updation-date"></span></span>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <p style="margin-left: 70px;margin-bottom: unset !important">
                                            <a type="button" class="float-right" href="javascript:resetSlaPlan();">Reset</a>
                                            <span class="float-right">&nbsp;&nbsp;|&nbsp;</span>
                                            <a type="button" href="javascript:changeSlaPlan();" class="float-right">Change SLA</a>
                                           
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
        
                        
                    <div class="card" >
                        <div class="card-body drop-dpt " style="background-color:{{($current_status == null) ? '' : ($current_status->color != null ? $current_status->color : ' ')}}">
                            <div class="row" id="dropD" style="margin-right:-5px;margin-bottom:0 !important;">
                                <div class="col-md-2 br-white" id="dep-label" style="border-right: 1px solid white;padding: 12px;">
                                    <label class="control-label col-sm-12 end_padding text-white" ><strong>Department</strong></label>
                                    <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important"  id="dep-h5">Selected</h5>
                                    <select class="select2 form-control  " id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                        
                                        @foreach($departments as $department)
                                            <option  value="{{$department->id}}" {{ $department->id == $details->dept_id ? 'selected' : '' }} >{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 br-white" id="tech-label" style="border-right: 1px solid white;padding: 12px;">
                                    <label class="control-label col-sm-12 end_padding text-white "><strong>Owner</strong></label>
                                    <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="tech-h5">Selected</h5>
                                    <select class="select2 form-control " id="assigned_to" name="assigned_to" style="width: 100%; height:36px;">
                                        <option value="">Unassigned</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}" {{ $user->id == $details->assigned_to ? 'selected' : '' }}>{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 br-white" id="type-label" style="border-right: 1px solid white;padding: 12px;">
                                    <label class="control-label col-sm-12 end_padding text-white "><strong>Type</strong></label>
                                    <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="type-h5">Selected</h5>
                                    <select class="select2 form-control " id="type" name="type" style="width: 100%; height:36px;">
                                        @foreach($types as $type)
                                            <option value="{{$type->id}}" {{ $type->id == $details->type ? 'selected' : '' }}>{{$type->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 br-white" id="status-label" style="border-right: 1px solid white;padding: 12px;">
                                    <label class="control-label col-sm-12 end_padding text-white "><strong>Status</strong></label>
                                    <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="status-h5"></h5>
                                    <select class="select2 form-control " id="status" name="status" style="width: 100%; height:36px;">
                                        
                                        @foreach($statuses as $status)
                                            <option value="{{$status->id}}" data-color="{{$status->color}}" {{ $status->id == $details->status ? 'selected' : '' }}>{{$status->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 br-white" id="prio-label" style="border-right: 1px solid white;;padding: 12px;background-color:{{($current_priority == null) ? '' : ($current_priority->priority_color != null ? $current_priority->priority_color : ' ')}}">
                                    <label class="control-label col-sm-12 end_padding text-white " ><strong>Priority</strong></label>
                                    <h5 class="end_padding mb-0 selected-label text-white" style="font-size: 0.87rem; !important" id="prio-h5"></h5>
                                    <select class="select2 form-control " id="priority" name="priority" style="width: 100%; height:36px;">
                                        {{-- <option value="">Select Priority</option> --}}
                                        @foreach($priorities as $priority)
                                             <option value="{{$priority->id}}" data-color="{{$priority->priority_color}}" {{$priority->id == $details->priority ? 'selected' : ''}}>{{$priority->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 chim text-center {{$details->is_flagged == 1 ? 'flagSpot' : ''}}" style=";padding: 12px;">
                                    <a type="button" class="text-white" id="flag" style="font-size: 20px">
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
                    <div class="card">
                        <div class="card-body" >
                            <h4 class="card-title mb-0">Ticket Replies
                                   
                                <a href="#v-pills-tab" class="btn btn-success float-right" onclick="composeReply()">
                                    Compose 
                                </a>
                               
                                <a id="update_ticket" style="display:none" class="btn btn-success float-right mx-2" onclick="updateTicket()">
                                    Update 
                                </a>    
                            </h4>
                            <div class="mt-4 d-none" id="compose-reply">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="" style="margin-top: 10px;">
                                            <label for="to_mails">CC <span class="help"> e.g. "example@gmail.com"</span></label>
                                            @if( array_key_exists(0 , $shared_emails) )
                                                <input type="text" id="to_mails" name="to_mails"
                                                 class="form-control border" placeholder="Email" 
                                                 data-role="tagsinput" value="{{$shared_emails[0]['mail_type'] == 1 ? $shared_emails[0]['email'] : '' }}" required>
                                            @else
                                                <input type="text" id="to_mails" name="to_mails" class="form-control border" placeholder="Email"  data-role="tagsinput" value="" required>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-sm-4" id="select_customer">
                                        <label class="form-label">Response Template</label>
                                        <select class="select2 form-control custom-select dropdown w-100" id="res-template" style="width:100%">
                                            <option value="">Select</option>
                                            @if(!empty($responseTemplates))
                                                @foreach($responseTemplates as $res)
                                                    <option value="{{$res->id}}">{{$res->title}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                
        
                                <label style="margin-top: 10px;">Write a reply</label>
                                <textarea id="mymce" name="reply"></textarea>
                                
                                <div class="form-group pt-3">
                                    <button class="btn btn-outline-primary btn-sm" type="button" onclick="addAttachment('replies')"><span class="fa fa-plus"></span> Add Attachment</button>
                                    <div class="col-12 p-0" id="replies_attachments"></div>
                                </div>
        
                                <div class="row mt-4 reply-btns">
                                    <div class="col-md-4" style="padding-top: 30px;">
                                        <div class="form-check form-check-inline">
                                            <input id="checkbox0" class="form-check-input" type="checkbox" >
                                            <label class="mb-0" for="checkbox0"> Do not mail response </label>
                                        </div>
                                    </div>    
                                    <div class="col-md-8">
                                        <button id="rply" type="button" class="mt-3 btn waves-effect waves-light btn-success float-right" onclick="publishReply(this)">
                                            <div class="spinner-border text-light" role="status" style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            Reply
                                        </button>
                                        <button id="cancel-rply" type="button" class="mt-3 btn waves-effect waves-light btn-secondary float-right" style="display: none;" onclick="cancelReply(this)">Cancel</button>
                                        <button id="draft-rply" type="button" class="mt-3 btn waves-effect waves-light btn-info float-right" onclick="publishReply(this, 'draft')">Save As Draft</button>
                                    </div>
                                </div>
                            </div>
        
                            <ul class="list-unstyled mt-5 replies" id="ticket-replies">
                                
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- Notes Modal -->
        <div class="modal fade text-start" id="notes_manager_modal"  tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="note_title">Notes</h4>
                        <button type="button" class="btn-close text-danger" onclick="notesModalClose()"></button>
                    </div>
                    <div class="modal-body">
                        <form id="save_ticket_note" action="{{asset('save-ticket-note')}}" method="post">
                            <input type="text" id="note-id" style="display: none;">
                            <div class="row">
                                <div class="col-12 d-flex py-2">
                                    <label for=""><h4>Notes:</h4></label>
                                    <div class="" style="margin-left:6px ">
                                        <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(255, 230, 177); cursor: pointer;" onclick="selectColor('rgb(255, 230, 177)')"></span>
                                        <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(218, 125, 179); cursor: pointer;" onclick="selectColor('rgb(218, 125, 179)')"></span>
                                        <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(195, 148, 255); cursor: pointer;" onclick="selectColor('rgb(195, 148, 255)')"></span>
                                        <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(151, 235, 172); cursor: pointer;" onclick="selectColor('rgb(151, 235, 172)')"></span>
                                        <span class="fas fa-square mr-2" style="font-size: 26px; color: rgb(229, 143, 143); cursor: pointer;" onclick="selectColor('rgb(229, 143, 149)')"></span>
                                    </div>
                                </div>

                                <div class="col-12 py-2">
                                    <div class="form-group">
                                        <textarea name="note" id="note" class="form-control" rows="5" required style="background-color: rgb(255, 230, 177); color: black;"></textarea>
                                        <div id="menu" class="menu" role="listbox"></div>
                                    </div>
                                </div>
                                <div class="col-5"></div>
                                <div class="col-7 d-flex py-2" style="" >
                                    <label style="margin-right: 6px"> Note Type: </label>
                                    <div class="ml-auto d-flex" >
                                        <div class="form-check mr-2" style="margin-right:12px ">
                                            <input class="form-check-input note-type-ticket" type="radio" name="type" id="note-type-ticket" value="Ticket" checked>
                                            <label class="form-check-label" for="note-type-ticket">
                                                Ticket
                                            </label>
                                        </div>
                                        <div class="form-check mr-2" style="margin-right:12px ">
                                            <input class="form-check-input note-type-user" type="radio" name="type" id="note-type-user"  value="User" >
                                            <label class="form-check-label" for="note-type-user">
                                                User
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input note-type-user-org" type="radio" name="type" id="note-type-user-org"  value="User Organization">
                                            <label class="form-check-label" for="note-type-user-org">
                                                User Organization
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 py-2">
                                    <label> Visibility </label>
                                    <select class="select2 form-control form-select note-visibilty" id="note-visibilty" required style="width: 100%;" multiple onchange="visibilityOptions(this.value)">
                                        <option value="Everyone" selected>--Everyone--</option>
                                        @foreach ($users as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 pt-3" >
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="float: right">Close</button>
                                    <button type="submit" class="btn btn-success" style="float: right;margin-right: 3px">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal Notes Modal -->

        <div class="modal fade text-start" id="follow_up" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Follow Up</h5>
                        <button type="button"class="btn-close text-danger" onclick="notesModalClose()" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <form id="save_ticket_follow_up" action="{{asset('save-ticket-follow-up')}}" method="post">
                        <div class="modal-body">
                            {{-- <input value="{{$details->id}}" name="ticket_id" hidden> --}}
                            
                            <div class="form-row">
                                <div class="row mb-2">
                                <div class="col-md-6 form-group">
                                    <select class="select2 form-control" name="schedule_type" id="schedule_type" onchange="showDateTimeDiv(this.value)" required>
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
                            </div>
                                <div class="col-md-6 form-group" id="date_picker_div" style="display: none;">
                                    <input type="datetime-local" id="custom_date" class="form-control">
                                </div>
                                <div class="col-md-6 form-group" id="recurrence_time_div" style="display: none;">
                                    <input type="time" id="recurrence_time" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="col-md-12 form-group border p-1 bg-light">
                                    <div class="form-check-inline" id="general_div" style="">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="general">
                                            <label class="custom-control-label" for="general">General</label>
                                        </div>
                                    </div>                                       
                                </div>
                                <br>
                                <div class="col-md-12" id="general_details_div" style="display:none">
                                    <div class="form-row">
                                    <div class="row">
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
                                    </div> 
                                    <div class="form-row">
                                        <div class="row">
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
                                                    <option value="{{$priority->id}}" {{$priority->id == $details->priority ? 'selected' : ''}}>{{$priority->name}}</option>
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
                            </div>

                            <div class="form-row mt-2" >
                                <div class="col-md-12 form-group border p-1 bg-light">
                                    <div class="form-check-inline" id="notes_div" style=""> 
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="notes">
                                            <label class="custom-control-label" for="notes">Notes</label>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="col-md-12 form-group mt-1" id="ticket_follow_notes" style="display:none">
                                    <textarea class="form-control" rows="3" id="follow_up_notes" name="follow_up_notes"></textarea>
                                </div>
                            </div>

                            <div class="form-row mt-3">
                                <div class="col-md-12 form-group border p-2 bg-light">
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" class="form-check-input" id="is_recurring">
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
                                        <div class="form-check form-check-inline d-flex align-items-center py-2">
                                            <input type="radio" id="start-after-date" name="recurrence_start" class="form-check-input" value="date">
                                            <label class="custom-control-label" for="start-after-date">Start date: </label>&nbsp;&nbsp;
                                            <input type="date" class="form-control allow-req" id="recur-start-date" style="width: 200px;" disabled>
                                        </div>
                                        <div class="custom-control custom-radio d-flex align-items-center py-2">
                                            <input type="radio" id="start-now-recur" name="recurrence_start" class="form-check-input" value="now">
                                            <label class="custom-control-label" for="start-now-recur">Start now</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="custom-control custom-radio d-flex align-items-center py-2">
                                            <input type="radio" id="end-after-date" name="recurrence_end" class="form-check-input" value="date">
                                            <label class="custom-control-label" for="end-after-date">End by: </label>&nbsp;&nbsp;
                                            <input type="date" class="form-control allow-req" id="recur-end-date" style="width: 200px;" disabled>
                                        </div>
                                        <div class="custom-control custom-radio d-flex align-items-center py-2">
                                            <input type="radio" id="end-after-occurences" name="recurrence_end" class="form-check-input" value="count">
                                            <label class="custom-control-label" for="end-after-occurences">End after: </label>&nbsp;&nbsp;
                                            <input type="number" class="form-control allow-req" id="end-after-occur" value="1" min="1" style="width: 100px;" disabled>
                                            <label>&nbsp;&nbsp; occurences</label>
                                        </div>
                                        <div class="custom-control custom-radio d-flex align-items-center py-2">
                                            <input type="radio" id="no-end" name="recurrence_end" class="form-check-input" value="no end">
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
<div class="modal fade" id="pro_edit" role="dialog" data-backdrop="static"  aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="up_tkt_cust_title" style="color:#009efb;">Update Ticket Properties</h4>
                <button class="btn-close ml-auto" onclick="closeModal()"></button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <input type="hidden" id="tkt_cust_id">
                    <input type="hidden" id="tkt_cust_comp_id">

                    <div id="normal-cut-selc">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label" for="select2-basic">Select Customer</label>
                                <select class="select2 form-select" id="tkt_all_customers" onchange="ticketCustomer.select_customer(this.value)">
                                    <option value ="">Select Cusotmer</option>
                                    @foreach($all_customers as $customer)
                                        @php
                                            $company_name = $customer->company == null ? 'Company not provided' : ($customer->company->name != null ? $customer->company->name : 'Company not provided');
                                            $company_id = $customer->company == null ? '' : ($customer->company->id != null ? $customer->company->id : '');
                                        @endphp
                                        <option value="{{$customer->id}}" data-comp="{{$company_id}}" {{$customer->id == $ticket->customer_id ? 'selected' : ''}}>
                                                {{$customer->first_name}} {{$customer->last_name}} (ID: {{$customer->id}}) | {{$company_name}} 
                                                {{$customer->email != null ? '| ' . $customer->email : ''}} 
                                                {{$customer->phone != null ? '| ' . $customer->phone : ''}} 
                                        </option>
                                    @endforeach
                                </select>
                                @if($details->is_staff_tkt == 1)
                                    <div id="staff_as_customer">
                                        <a href="#">
                                            <div style="font-size:14px" class="bg-light text-left font-weight-bold text-dark mt-2 p-2 border shadow-sm rounded">{{$name}} <span class="badge badge-secondary">Staff</span> (ID : {{$ticket_customer->id}}) | company not provided | {{$email}} | {{$phone}} </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="form-group form-group-default required">
                                <label>CC</label>
                                <input class="tagsinput custom-tag-input" type="text" style="display: none;">
                                <div class="bootstrap-tagsinput" style="display:flex !important">
                                    @if( array_key_exists(0 , $shared_emails) )
                                        <input id="tkt_cc" class="meta_tags" size="2" type="text" value="{{$shared_emails[0]['mail_type'] == 1 ? $shared_emails[0]['email'] : ''}}">
                                    @else
                                        <input id="tkt_cc" class="meta_tags" size="2" type="text">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            <div class="form-group form-group-default required">
                                <label>BCC</label>
                                <input class="tagsinput custom-tag-input" type="text" style="display: none;">
                                <div class="bootstrap-tagsinput" style="display:flex !important">
                                    @if( array_key_exists(1 , $shared_emails) )
                                        <input id="tkt_bcc" class="meta_tags" size="2" type="text" value="{{$shared_emails[1]['mail_type'] == 2 ? $shared_emails[1]['email'] : ''}}">
                                    @else
                                        <input id="tkt_bcc" class="meta_tags" size="2" type="text">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="new-cust-cont" style="display: none;">
                        <form class="form-horizontal w-100" id="save_newtickcust_form" enctype="multipart/form-data" action="{{asset('/update-ticket-customer')}}" method="post">
                            <div class="form-row">
                                <div class="row mt-1">
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                                </div>
                            </div>
                        </div>
                            <div class="form-row">
                                <div class="row mt-1">
                                <div class="form-group col-md-6">
                                    <label for="username">Email</label>
                                    <input type="email" class="form-control" name="email" id="username" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mt-1">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="login_account">
                                    <label class="custom-control-label" for="login_account">Create customer login account</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-2" id="new_company_input">
                            <div class="col-md-8 form-group " id="customer_email">
                                <label for="company" class="small">Company Name</label>
                                <select id="company_id" name="company_id"  class="select2 form-select">
                                    <option value=""> Choose Company </option>
                                    @foreach($all_companies as $company)
                                        <option value="{{$company->id}}"> {{$company->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1" id="reverting">
                                <button type="button" onclick="ticketCustomer.openCompany()" id="new-Company-button" 
                                    class="btn btn-success cmp_btn" style="margin-top:20px;;margin-right: 10px"> New </button>
                            </div>
                        </div>

                        <div class="row" id="exists_comp" style="display:none">
                            <div class="col-md-4">
                                <button type="button" onclick="ticketCustomer.closeCompany()" id="existing_comp" class="btn btn-danger " style="margin-top:20px;;margin-right: 10px"> Existing Company </button>
                            </div>
                        </div>
                            

                            <div class="col-sm-12 p-2 mt-2 bg-light" id="new_company" style="display:none">
                                <h3>Create New Company </h3>
                                <input type="hidden" name="new_company" id="new_company_field" value="0">
                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label for="name" class="small">Company Name <span class="text-danger">*</span> </label>
                                        <input type="text" name="company_name" id="cmp_name" class="form-control">
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label for="poc_first_name" class="small">Owner First Name</label>
                                        <input type="text" name="cmp_first_name" id="poc_first_name" class="form-control">
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="poc_last_name" class="small">Owner Last Name</label>
                                        <input type="text" name="cmp_last_name" class="form-control" id="poc_last_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone" class="small">Phone</label>
                                        <input type="tel" name="cmp_phone" class="form-control" id="cmp_phone">
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success upt-cust-btn" onclick="newCustomer('new')">New Customer</button>
                    <button type="button" class="btn btn-success newcustbtn" style="display: none;" onclick="newCustomer('save')">Save</button>
                    <button type="button" class="btn btn-secondary newcustbtn" style="display: none;" onclick="newCustomer('cancel')">Cancel</button>
                    <button type="button" class="btn btn-primary upt-cust-btn" onclick="ticketCustomer.update_customer()">Update</button>
                </div>
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
                <button type="button" class="btn-close ml-auto" onclick="closeAssetModal()"></button>
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
                    <div class="form-row asst_temp_title" id="templateTitle" style="display:none;">
                        <div class="col-md-12 form-group">
                            <div class="form-group">
                                <label>Asset Title</label>
                                    <input type="text" name="asset_title" id="asset_title" class="asset_title form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row asst_form_fields" id="form-fields"></div>
                </div>
                <div class="modal-footer text-right" >
                    <button type="submit" class="btn btn-info my-3" onclick="closeAssetModal()" > Close </button>
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
                    <button type="button" class="btn-close ml-auto" onclick="closeAssetModal()"></button>
               
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

<!-- update sla plan modal -->
<div id="sla_plan_modal" class="modal fade" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                    <h4 class="modal-title">Update SLA Plan</h4>
                    <button type="button" data-bs-dismiss="modal" class="btn-close" onclick="closeAssetModal()"></button>
            </div>
            <div class="modal-body">
                <form id="sla_plan_form" enctype="multipart/form-data" onsubmit="return false">
                    <div class="form-group">
                        <label for="select">SLA Plan</label>
                        <select class="form-control select2" id="sla_plan_id" required style="width: 100%; height: 36px;">
                            @foreach ($sla_plans as $item)
                                @if ($item->title == 'Default SLA')
                                    <option value="{{$item->id}}" selected>{{$item->title}}</option>
                                @endif
                            @endforeach
                            @foreach ($sla_plans as $item)
                                @if ($item->title != 'Default SLA')
                                    <option value="{{$item->id}}" {{$item->id == $ticket_slaPlan->id ? 'selected' : ''}}>{{$item->title}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group text-right mt-3">
                        <button class="btn btn-rounded btn-success float-right" type="button" onclick="setSlaPlan()">Save</button>
                        <button class="btn btn-rounded btn-danger float-right" style="margin-right: 5px" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- reset sla plan modal -->
<div id="reset_sla_plan_modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                    <h4 class="modal-title">Reset SLA Plan</h4>
                    <button type="button" data-bs-dismiss="modal" class="btn-close" onclick="closeAssetModal()"></button>
            </div>
            <div class="modal-body">
                <form id="sla_plan_reset_form" enctype="multipart/form-data" onsubmit="return false" method="post" action="{{asset('/update-ticket-deadlines')}}">
                    <div class="form-group">
                        <label for="ticket-rep-due">Reply Due</label>
                        <input type="datetime-local" id="ticket-rep-due" name="" class="form-control">
                    </div>
                    <div class="form-group pt-1">
                        <label for="ticket-res-due">Resolution Due</label>
                        <input type="datetime-local" id="ticket-res-due" name="" class="form-control">
                    </div>
                    <div class="form-group text-right mt-3">
                        <button class="btn btn-rounded btn-success float-right" type="button" onclick="updateDeadlines();">Save</button>
                        <button class="btn btn-rounded btn-danger float-right" style="margin-right: 5px" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="sys_date_format" value="{{$date_format}}">

<div class="modal fade" id="viewFullDetails" tabindex="-1" aria-labelledby="viewFullDetailsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize" id="viewFullDetailsTitle">Subject : {{$details->subject}}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <div class="alert alert-primary" role="alert">
                <div class="alert-body" id="ticket-timestamp2">
                     
                </div>
            </div>
                <div class="row" id="ticket_details_p2"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Okay</button>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-slide-in event-sidebar fade" id="viewFullDetails2">
    <div class="modal-dialog sidebar-lg">
        <div class="modal-content p-0">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-header mb-1">
                <h5 class="text-capitalize" style="opacity:0;" >Subject : {{$details->subject}}</h5>
                
            </div>
            <div class="modal-body flex-grow-1 pb-sm-0 pb-3">
                <h5 class="text-capitalize" >Subject : {{$details->subject}}</h5>
                <div class="alert alert-primary" role="alert">
                    <div class="alert-body" id="ticket-timestamp3">
                        
                    </div>
                </div>
                <div class="row" id="ticket_details_p3"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>


@include('js_files.atwho.atwhoJs')
@include('js_files.atwho.caretJs')

@include('js_files.help_desk.ticket_manager.ticket_detailsJs')

<!-- <script type="text/javascript" src="{{asset('public/js/help_desk/ticket_manager/details.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.ticket_manager.detailsJs')

{{-- Linked Assets JS --}}
<!-- <script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script> -->
<!-- {{-- <script src="{{asset('public/js/help_desk/ticket_manager/tickets.js').'?ver='.rand()}}"></script> --}} -->

<!-- <script src="{{asset('public/js/help_desk/asset_manager/asset.js').'?ver='.rand()}}"></script> -->

    @include('js_files.help_desk.asset_manager.actionsJs')
    {{-- @include('js_files.help_desk.ticket_manager.ticketsJs') --}}
    @include('js_files.help_desk.asset_manager.assetJs')
<script>
        $('[data-dismiss=modal]').on('click', function(e) {
        var $t = $(this),
            target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];

        $(target)
            .find("input,textarea,select")
            .val('')
            .end()
            .find("input[type=checkbox], input[type=radio]")
            .prop("checked", "")
            .end();
    });

    $(".meta_tags").tagsinput('items');

function hung(){
    $("#viewFullDetails2").modal("show");
    $(this).find("col-md-3").addClass("col-md-6");
    $(this).find("col-md-6").removeClass("col-md-3");
}

    
</script>

@endsection
