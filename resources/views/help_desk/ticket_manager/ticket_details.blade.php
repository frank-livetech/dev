@extends('layouts.staff-master-layout')
@section('body-content')
<style>
    html {
  scroll-behavior: smooth;
}
a:link, span.MsoHyperlink {
    mso-style-priority: 99;
    color: #009efb;
    text-decoration: none;
}
a{
    text-decoration:none !important;
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
.mt-10{
    margin-top:1rem;
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
    .f-13{
        font-size:13px;
    }
    .mtb-5{
        margin-top:5px;
        margin-bottom:5px;
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
        object-fit: cover;
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
  

    
/*
 *  STYLE 5
 */

#style-5::-webkit-scrollbar-track
{
	-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
	background-color: #F5F5F5;
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

/* reply form buttons */

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
.innerBox{
    font-size: 15px;
    height: 160px;
    overflow-y: scroll;
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
    padding-right:19px;
    top: 7px;
    font-size: 11px;
    display:none;
    color:#fff;
    
}
.downFile{
    position: absolute;
    bottom: 4px;
    right: 55px;
    /* left: 46%; */
    border-radius: 50%;
    color: green;
    padding: 2px 7px;
    border: 1px solid #fff;
    display:none;
}
.downFile i{
    color: #fff;
    font-size:14px;
}
.downFile:hover{
    background:green;
    border: 1px solid #e6e7e8;

}
.downFile:hover i{
    color: #fff;
}
.borderOne{
    border: 1px solid #e6e7e8;
    text-align: center;
    width: 100%;
    height: 94px;
    /* padding: 29px 12px; */
    padding-top: 21%;
    transition: 0.3s ease;
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
.card__corner {
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
}
body[data-theme=dark] .note-details{
    color:#252629;
}
body[data-theme=dark] .card .card__corner .card__corner-triangle {
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 1.5em 1.5em 0 0;
    border-color: #e6e7e8 #252629 #fff #fff;
}
</style>
<input type="text" id="current_url" value="{{url()->current()}}">
<input type="hidden" id="ticket_created_at_val" value="{{date_format($ticket->created_at , 'c')}}">

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <!--<h4 class="page-title">Tickets Manager</h4>-->
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home </a></li>
                        <li class="breadcrumb-item"><a href="{{asset('/ticket-manager')}}">Tickets Manager</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ticket Details</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="loggedInUser_id" value="{{\Auth::user()->id}}">
<input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">
<div class="container-fluid">
                
    <div class="row">
        <div class="col-lg-5">
            <div class="card p-2" style="height:220px;">
                <div class="card-body " style="padding-bottom: 0px !important;">
                    <h5 class="card-title">Ticket ID: <a href="{{asset('/ticket-details')}}/{{$details->coustom_id}}">{{$details->coustom_id}}</a>&nbsp;&nbsp;<a href="javascript:void(0)" onclick="copyToClipBoard()"> 
                        <i class="far fa-copy"></i></a> <span class="small text-success" id="c_url" style="display:none">Url Copied</span>   
                        
                        {{-- <a data-target="#pro_edit" tooltip="Edit" data-toggle="modal" class="link d-flex  font-weight-medium" style="float:right; color:#000; cursor:pointer;"><i class="mdi mdi-lead-pencil"></i></a> --}}
                        <i class="mdi mdi-lead-pencil font-weight-medium" onclick="openProModal();" style="float:right; cursor:pointer;" tooltip="Edit"></i>
                    </h5>
                    <div class="profile-pic mb-3 mt-3">
                        <div class="row">
                            <div class="col-lg-5 col-md-5 text-center">
                                <!-- Image path -->
                                @php
                                    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
                                @endphp
                                @if($ticket_customer->avatar_url != NULL)
                                    @if(file_exists( public_path().'/'. $ticket_customer->avatar_url ))
                                        <img src="../files/user_photos/cust_profile_img/{{$ticket_customer->avatar_url}}" class="rounded-circle" width="100" height="100" id="profile-user-img" />
                                    @else
                                        <img id="login_logo_preview" name="login_logo_preview" class="rounded-circle" width="100" height="100" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                    @endif
                                @else($ticket_customer->avatar_url == NULL)
                                <img id="login_logo_preview" name="login_logo_preview" class="rounded-circle" width="100" height="100" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                @endif
                                <br><br>
                                <div class="row">
                                    <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:15px; text-align:center">
                                        <h3 class="font-weight-bold" style="font-size: 15px; text-align:center"> <a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#tickets" class="text-primary">{{$total_tickets_count}}</a></h3>
                                        <h6 style="font-size: 13px;"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#tickets" class="text-primary">Total</a></h6>
                                    </div>
                                    <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:15px; text-align:center">
                                        <h3 class="font-weight-bold" style="font-size: 15px; text-align:center"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#ticket-open" class="text-primary">{{$open_tickets_count}}</a></h3>
                                        <h6 style="font-size: 13px;"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}#ticket-open" class="text-primary">Open</a></h6>
                                    </div>
                                    <div class="col-4" style="max-width:100% !important; padding:0px !important;font-size:15px; text-align:center">
                                        <h3 class="font-weight-bold" style="font-size: 15px;text-align:center"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" class="text-primary">{{$closed_tickets_count}}</a></h3>
                                        <h6 style="font-size: 13px;"><a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" class="text-primary">Closed</a></h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-7 col-md-7 innerBox" id="style-5" style="">
                                <p style="margin-bottom: 0.2rem; !important">Name : <a href="{{ asset('customer-profile') }}/{{$ticket_customer->id}}" id="cst-name"> {{ $ticket_customer->first_name }} {{ $ticket_customer->last_name }} </a></p>
                                <p style="margin-bottom: 0.2rem; !important" id="cst-company"></p>
                                <p style="margin-bottom: 0.2rem; !important">Direct Line : <a href="tel:{{ $ticket_customer->phone }}" id="cst-direct-line">{{ $ticket_customer->phone }}</a> </p>
                                <p style="margin-bottom: 0.2rem; !important" id="cst-company-name"></p>
                                <p style="margin-bottom: 0.2rem; !important">Email : <a href="mailto:{{ $ticket_customer->email }}" id="cst-email">{{ $ticket_customer->email }}</a>  </p>
                                <p style="margin-bottom: 0.2rem; !important">Client Since : <span id="cust-creation-date"></span></p>
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
            <div class="card p-2" id="style-5" style="height:220px; overflow-y:scroll; overflow-x:auto">
                <div class="card-body">
                    <h4 class="card-title">Initial Request&nbsp;&nbsp;<span id="ticket-timestamp" style="font-size:12px; font-weight:400;"></span><span style="float:right; cursor:pointer" title="Edit Initial Request" id="edit_request_btn"><a onclick="editRequest()"><i class="mdi mdi-lead-pencil"></i></a></span><span style="float:right; cursor:pointer; display:none" title="Cancel" id="cancel_request_btn"><a onclick="cancelEditRequest()"><i class="mdi mdi-window-close text-danger" style="margin-left: 5px;"></i></a></span><span style="float:right;cursor:pointer;display:none" title="Save" id="save_request_btn"><a onclick="saveRequest()"><i class="mdi mdi-floppy text-success"></i></a></span></h4>

                    <h6 id="ticket_subject_heading">Subject : {{$details->subject}}</h6>
                    <div class="row" id="ticket_details_p"></div>
                    <!-- <div class="row " id="">
                        
                         <div class="col-md-3 mt-1">
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                            <span class="overlayAttach"></span>

                                <img src="{{asset ('public/files/file_icon/Pdf.png')}}" alt="">
                                <span class="fileName">0${item}${item}${item}${item}${item}</span>
                                <a href="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>
                        
                    </div> -->
                    <!-- <div class="row" id="">
                      
                        <input type="file" data-height="100" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset ('[public/files/user_docs/1614634414.pdf')}}" />
                        <input type="file" data-height="100" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset ('[public/files/user_docs/1614634390.docx')}}" />
                        <input type="file" data-height="100" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset ('[public/files/user_docs/1614634414.pdf')}}" />
                    </div> -->
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
                            <label class="control-label col-sm-12" required="">Ticket Details</label><span id="ticket-details" style="display:none;color:red">Ticket Details cannot be empty</span>
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
    </div>

    <div class="row">
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
                    <div class="tab-content col-10 p-0" id="v-pills-tabContent style-5" style="max-height: 300px; overflow-y: auto;">
                        <div class="tab-pane fade show active p-2" id="v-pills-notes" role="tabpanel" aria-labelledby="v-pills-notes-tab">
                            <div class="col-12 text-right">
                                <button class="btn btn-success" onclick="openNotesModal()"><i class="mdi mdi-plus-circle"></i> Add Note</button>
                            </div>
                            <div class="col-12" id="v-pills-notes-list"></div>
                        </div>

                        <div class="tab-pane fade show p-2" id="v-pills-assets" role="tabpanel" aria-labelledby="v-pills-assets-tab">
                            <div class="col-12 px-0 text-right">
                                <button type="button" class="btn btn-success" onclick="ShowAssetModel()">
                                    <i class="mdi mdi-plus-circle"></i>&nbsp;Add Asset
                                </button>
                            </div>
                            <div class="col-12 px-0 my-2">
                                <div class="table-responsive">
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
                                <button class="btn btn-success" onclick="showFollowUpModal()">
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


    <div class="row">
        <div class="col-md-12">
            @if (!empty($ticket_slaPlan))
                <div class="card p-0" id="card-sla">
                    <div class="card-body p-0" id="ticket-sla-plan">
                        <div class="row" style="margin-right:-5px; margin-bottom:0 !important;">
                            <div class="col-md-9">
                                <p class="mtb-5 f-13">
                                    <span class="sla-selc">Reply due: <span class="text-red mr-2" id="sla-rep_due"></span></span>
                                    <span class="sla-selc">Resolution due: <span class="text-blue mr-2" id="sla-res_due"></span></span>
                                    SLA plan: <span class="text-red mr-2" id="sla-title">{{$ticket_slaPlan->title}}</span>  
                                    <span class="sla-selc">Created: <span class="text-red mr-2" id="creation-date"></span></span>
                                    <span class="sla-selc">Updated: <span class="text-red mr-2" id="updation-date"></span></span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <p>
                                    <a type="button" class="ml-2 float-right" href="javascript:void(0)" onclick="resetSlaPlan();">Reset</a>

                                    <span class="float-right">&nbsp;&nbsp;|&nbsp;</span>
                                    
                                    <a type="button" href="javascript:changeSlaPlan();" class="float-right">Change SLA</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

                
            <div class="card p-0 dropD">
                <div class="card-body p-0" style="padding:0 !important;background-color: #009efb">
                    <div class="row" id="dropD" style="margin-right:-5px;margin-bottom:0 !important; background-color:{{($current_status == null) ? '' : ($current_status->color != null ? $current_status->color : ' ')}} ">
                        <div class="col-md-2 br-white" id="dep-label">
                            <label class="control-label col-sm-12 end_padding" >Department</label>
                            <h5 class="end_padding selected-label"  id="dep-h5">Selected</h5>
                            <select class="select2 form-control " id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                
                                @foreach($departments as $department)
                                    <option  value="{{$department->id}}" {{ $department->id == $details->dept_id ? 'selected' : '' }} >{{$department->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="tech-label">
                            <label class="control-label col-sm-12 end_padding">Tech Lead</label>
                            <h5 class="end_padding selected-label"  id="tech-h5">Selected</h5>
                            <select class="select2 form-control " id="assigned_to" name="assigned_to" style="width: 100%; height:36px;">
                                <option value="">Unassigned</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}" {{ $user->id == $details->assigned_to ? 'selected' : '' }}>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="type-label">
                            <label class="control-label col-sm-12 end_padding">Type</label>
                            <h5 class="end_padding selected-label"  id="type-h5">Selected</h5>
                            <select class="select2 form-control " id="type" name="type" style="width: 100%; height:36px;">
                                @foreach($types as $type)
                                    <option value="{{$type->id}}" {{ $type->id == $details->type ? 'selected' : '' }}>{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="status-label">
                            <label class="control-label col-sm-12 end_padding">Status</label>
                            <h5 class="end_padding selected-label"  id="status-h5"></h5>
                            <select class="select2 form-control " id="status" name="status" style="width: 100%; height:36px;">
                                
                                @foreach($statuses as $status)
                                    <option value="{{$status->id}}" data-color="{{$status->color}}" {{ $status->id == $details->status ? 'selected' : '' }}>{{$status->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 br-white" id="prio-label" style="background-color:{{($current_priority == null) ? '' : ($current_priority->priority_color != null ? $current_priority->priority_color : ' ')}} ">
                            <label class="control-label col-sm-12 end_padding" >Priority</label>
                            <h5 class="end_padding selected-label" id="prio-h5"></h5>
                            <select class="select2 form-control " id="priority" name="priority" style="width: 100%; height:36px;">
                                {{-- <option value="">Select Priority</option> --}}
                                @foreach($priorities as $priority)
                                    <option value="{{$priority->id}}" data-color="{{$priority->priority_color}}" {{$priority->id == $details->priority ? 'selected' : ''}}>{{$priority->name}}</option>
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
            <div class="card">
                <div class="card-body" >
                    <h4 class="card-title">Ticket Replies
                        <a href="#v-pills-tab" class="btn btn-outline-success float-right" onclick="composeReply()">
                            Compose 
                        </a>
                    </h4>
                    <div class="mt-4 d-none" id="compose-reply">
                        <div class="" style="margin-top: 10px;">
                            <label for="to_mails">CC <span class="help"> e.g. "example@gmail.com"</span></label>
                            <input type="text" id="to_mails" name="to_mails" class="form-control" placeholder="Email"  data-role="tagsinput" value="" required>
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

                    <ul class="list-unstyled mt-5 replies" id="ticket-replies">
                        
                    </ul>
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
                        <h4 class="modal-title" id="note_title">Notes</h4>
                        <button type="button" class="close ml-auto" onclick="notesModalClose()">×</button>
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
                                        <textarea name="note" id="note" class="form-control" rows="10" required style="background-color: rgb(255, 230, 177); color: black;"></textarea>
                                        <div id="menu" class="menu" role="listbox"></div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex py-2">
                                    <label> Note Type </label>
                                    <div class="ml-auto d-flex">
                                        <div class="form-check mr-2">
                                            <input class="form-check-input note-type-ticket" type="radio" name="type" id="note-type-ticket" value="Ticket" checked>
                                            <label class="form-check-label" for="note-type-ticket">
                                                Ticket
                                            </label>
                                        </div>
                                        <div class="form-check mr-2">
                                            <input class="form-check-input note-type-user" type="radio" name="type" id="note-type-user"  value="User" >
                                            <label class="form-check-label" for="note-type-user">
                                                User
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input note-type-user-org" type="radio" name="type" id="note-type-user-org"  value="User Organization">
                                            <label class="form-check-label" for="note-type-user-org">User Organization</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 py-2">
                                    <label> Visibility </label>
                                    <select class="select2 form-control custom-select ml-auto note-visibilty" id="note-visibilty" required style="width: 100%;" multiple onchange="visibilityOptions(this.value)">
                                        <option value="Everyone" selected>--Everyone--</option>
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
                            {{-- <input value="{{$details->id}}" name="ticket_id" hidden> --}}
                            
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
<div class="modal fade" id="pro_edit" role="dialog" data-backdrop="static"  aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h4 class="modal-title" id="" style="color:#009efb;">Update Ticket Customer</h4>
                <button class="close ml-auto" onclick="closeModal()">x</button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="row" id="normal-cut-selc">
                        <form class="d-flex w-100 position-relative" action="{{asset('/search-customer')}}" method="post" id="search-customer" autocomplete="off">
                            <input type="text" style="background-color:white !important; color:#263238 !important;" class="form-control text-dark" id="ct-search" name="ct-search" placeholder="Search Customer">
                            <i class="fas fa-search text-info" style="position: absolute; top: 10px; font-size: 1.2rem; right: 10px; cursor: pointer;" onclick="searchTicketCustomer()"></i>
                            <i class="fas fa-circle-notch fa-spin text-primary" id="cust_loader" style="position: absolute; top:10px;font-size:1.2rem; right:10px;display:none"></i>
                        </form>
                        <div class="col-12 pb-3 px-0" id="search_customer_result" style="max-height: 300px !important; overflow-y: auto;"></div>

                        <!-- <div class="col-12">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="username-fill">Email</label>
                                    <input type="email" class="form-control" name="email-fill" id="username-fill" value="{{$ticket_customer->email}}">
                                    <small style="display: none; color: red;" id="username-fill-err">Please enter email</small>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone-fill">Phone</label>
                                    <input type="tel" class="form-control" name="phone-fill" id="phone-fill" value="{{$ticket_customer->phone}}">
                                    <small style="display: none; color: red;" id="phone-fill-err">Please enter valid phone number upto length 10</small>
                                </div>
                            </div>
                        </div> -->

                        <div class="col-12">
                            <div class="" style="margin-top: 10px;">
                                <label for="cc">CC</label>
                                <input type="text" id="cc" name="cc" class="form-control" placeholder="Email"  data-role="tagsinput" value="" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="" style="margin-top: 10px;">
                                <label for="cc">BCC</label>
                                <input type="text" id="bcc" name="bcc" class="form-control" placeholder="Email"  data-role="tagsinput" value="" required>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="new-cust-cont" style="display: none;">
                        <form class="form-horizontal w-100" id="save_newtickcust_form" enctype="multipart/form-data" action="{{asset('/update-ticket-customer')}}" method="post">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="first_name">First Name</label>
                                    <input type="text" class="form-control" name="first_name" id="first_name" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="last_name">Last Name</label>
                                    <input type="text" class="form-control" name="last_name" id="last_name" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="username">Email</label>
                                    <input type="email" class="form-control" name="email" id="username" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="phone" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="login_account">
                                        <label class="custom-control-label" for="login_account">Create customer login account</label>
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
                    {{-- <button type="button" class="btn btn-info" onclick="closeModal()">Close</button> --}}
                    <button type="button" class="btn btn-primary upt-cust-btn" onclick="setCustomerUpdates()">Update</button>
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
                <button type="button" class="close ml-auto" onclick="closeAssetModal()">×</button>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info p-2">
                <span>
                    <h4 style="color:#fff !important;">Update SLA Plan</h4>
                </span>
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
                        <button class="btn btn-rounded btn-success" type="button" onclick="setSlaPlan()">Save</button>
                        <button class="btn btn-rounded btn-danger" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- reset sla plan modal -->
<div id="reset_sla_plan_modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info p-2">
                <span>
                    <h4 style="color:#fff !important;">Reset SLA Plan</h4>
                </span>
            </div>
            <div class="modal-body">
                <form id="sla_plan_reset_form" enctype="multipart/form-data" onsubmit="return false" method="post" action="{{asset('/update-ticket-deadlines')}}">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="form-group">
                                <label for="ticket-rep-due">Reply Due</label>
                                <input type="datetime-local" id="ticket-rep-due" name="" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" onclick="resetTktSLA(1)"> <i class="fas fa-history"></i> </button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="form-group">
                                <label for="ticket-res-due">Resolution Due</label>
                                <input type="datetime-local" id="ticket-res-due" name="" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" onclick="resetTktSLA(2)"> <i class="fas fa-history"></i> </button>
                        </div>
                    </div>
                    <div class="form-group text-right mt-3">
                        <button class="btn btn-rounded btn-success" type="button" onclick="updateDeadlines();">Save</button>
                        <button class="btn btn-rounded btn-danger" type="button" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="sys_date_format" value="{{$date_format}}">
@endsection   
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
<script type="text/javascript" src="{{asset('assets/dist/js/flashy.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/countdown/countdown.css')}}" />
<script type="text/javascript" src="{{asset('assets/extra-libs/countdown/countdown.js')}}"></script>

@include('js_files.atwho.atwhoJs')
@include('js_files.atwho.caretJs')

@include('js_files.help_desk.ticket_manager.ticket_detailsJs')

<!-- <script type="text/javascript" src="{{asset('public/js/help_desk/ticket_manager/details.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.ticket_manager.detailsJs')

{{-- Linked Assets JS --}}
<!-- <script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script> -->
<!-- {{-- <script src="{{asset('public/js/help_desk/ticket_manager/tickets.js').'?ver='.rand()}}"></script> --}} -->
<!-- <script src="{{asset('js/tagsinput.js')}}"></script> -->
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
    
</script>

@endsection