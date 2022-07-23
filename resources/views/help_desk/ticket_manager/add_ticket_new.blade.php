@extends('layouts.master-layout-new')
@section('title', 'Add Ticket')
@section('body')
<style>
.tox-collection__item-icon {
    font-size: 25px !important;
}
@media (max-width: 650px) {
    #check_box_mbl{
        margin-top:8rem !important;
        display: inline !important;
    }
    .form-check-primary{
        margin-left: 0px !important
    }
}
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-7 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Add Ticket</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">Help Desk
                                </li>
                                <li class="breadcrumb-item active"><a href="{{url('ticket-manager')}}">Tickets
                                        Manager</a>
                                </li>
                                <li class="breadcrumb-item active">Add Ticket
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(Auth::user()->user_type == 5)
    <input type="hidden" id="for_customer_profile_id" value="{{Auth::user()->id}}">
    @endif
    <input type="hidden" id="previous_url" value="{{url()->previous()}}">
    <input type="hidden" id="for_customer_role" value="{{auth()->user()->user_type}}">

    <div class="content-body">
        <section id="statistics-card">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="save_tickets">
                                <div class="form-group">
                                    <div class="row g-1">
                                        <div class="col-md-8 col-12 position-relative">
                                            <label class="form-label" for="validationTooltip01">Subject <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="subject" name="subject"
                                                required="">
                                            <span class="text-danger small" id="subject_error"></span>
                                        </div>
                                        <!-- Dept file chanegs  -->
                                        <div class="col-md-4 mb-1">
                                            <label class="form-label" for="dept_id">Select Department<span
                                                    class="text-danger">*</span></label>
                                            <select class="select2 form-select" style="width:100%"
                                                onchange="showDepartStatus(this.value)" name="dept_id" id="dept_id"
                                                required>
                                                @if($departments != null && $departments != "")
                                                <option value="">Select Department</option>

                                                @foreach($departments as $key => $department)
                                                @if ($key == 0)
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                                @else
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger small" id="department_error"></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @if(Auth::user()->user_type != 5)
                                        <div class="col-md-4 col-sm-4" style="position:relative">
                                            <label class="form-label">Select Status<span
                                                    class="text-danger">*</span></label>
                                            <select class="select2 form-control dropdown" style="width:100%" id="status"
                                                name="status" required>
                                                <option value="">Select Status</option>
                                            </select>
                                            <i class="fas fa-circle-notch fa-spin text-primary" id="dropdown_loader"
                                                style="position: absolute; top:34px;font-size:1.2rem; right: 34px; display:none"></i>
                                            <span class="text-danger small" id="status_error"></span>
                                        </div>
                                        @endif

                                        <div class=" col-md-4 col-sm-4">
                                            <label class="form-label">Select Priority<span
                                                    class="text-danger">*</span></label>
                                            <select class="select2 form-control dropdown" style="width:100%"
                                                id="priority" name="priority" required>
                                                {{-- <option value="">Select </option> --}}
                                                @if($priorities != null && $priorities != "")
                                                <option value="">Select Priority</option>

                                                @foreach($priorities as $key=> $priority)
                                                @if ($key == 0)
                                                <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                @else
                                                <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger small" id="priority_error"></span>
                                        </div>


                                        @if(Auth::user()->user_type != 5)
                                        <div class="col-sm-4">
                                            <label class="form-label">Assign Tech </label>
                                            <select class="select2 form-control dropdown" style="width:100%"
                                                id="assigned_to" name="assigned_to">
                                                <option value="">Unassigned</option>
                                                {{-- @foreach($users as $user)
                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach --}}
                                            </select>
                                        </div>
                                        @endif

                                    </div>

                                    @if(Auth::user()->user_type != 5)
                                    <div class="row mt-1">

                                        <div class="col-sm-4">
                                            <label class="form-label">Select Type
                                                <span class="text-danger">*</span></label>
                                            <select class="select2 form-control dropdown" style="width:100%" id="type"
                                                name="type" required>
                                                {{-- <option value="">Select</option> --}}
                                                @if($types != null && $types != "")
                                                <option value="">Select Type</option>
                                                @foreach($types as $key => $type)
                                                @if ($key == 0)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                                @else
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger small" id="type_error"></span>
                                        </div>

                                        <div class="col-sm-4" id="select_customer">
                                            <label class="form-label">Customer Select<span
                                                    class="text-danger">*</span></label>
                                            <select class="select2 form-control custom-select dropdown w-100"
                                                id="customer_id" name="customer_id" style="width:100%">
                                                <option value="">Select</option>
                                                @if($customers != null && $customers != "")
                                                @foreach($customers as $key => $customer)
                                                @if ($key == 0)
                                                <option value="{{$customer->id}}"> {{$customer->first_name}}
                                                    {{$customer->last_name}} (#{{$customer->id}}) | {{$customer->email}}
                                                </option>
                                                @else
                                                <option value="{{$customer->id}}"
                                                    {{$id == $customer->id ? 'selected' : ''}}>{{$customer->first_name}}
                                                    {{$customer->last_name}} (#{{$customer->id}}) | {{$customer->email}}
                                                </option>
                                                @endif
                                                @endforeach
                                                @endif
                                            </select>
                                            <span class="text-danger small" id="customer_id_error"></span>
                                        </div>

                                        <div class="col-sm-4" id="select_customer">
                                            <label class="form-label">Response Template</label>
                                            <select class="select2 form-control custom-select dropdown w-100"
                                                id="res-template" style="width:100%">
                                                <option value="">Select</option>
                                                @if(!empty($responseTemplates))
                                                @foreach($responseTemplates as $res)
                                                <option value="{{$res->id}}">{{$res->title}} ({{$res->category_name}})
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <div class="col-12" id="new_customer_div">
                                            <div class="form-check form-check-primary mt-1">
                                                <input type="checkbox" value="1" class="form-check-input"
                                                    id="new_customer">
                                                {{-- <input type="checkbox" value="1" class="custom-control-input" id="new_customer"> --}}
                                                <label class="custom-form-label" for="new_customer">New Customer</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label class="form-label">Send From</label>
                                            <select class="select2 form-control custom-select dropdown w-100" id="queue_id" style="width:100%">
                                            </select>
                                        </div>
                                    </div>

                                    <div id="new_customer_form" style="display:none" class="p-3 bg-light mt-2">
                                        <div class="form-group">
                                            <h3 class="font-weight-bold">New Customer</h3>
                                            <hr>
                                            <div class="row mt-1">
                                                <div class="col-md-4 col-sm-6">
                                                    <label for="example-search-input" class=" col-form-label">First Name
                                                        <span class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input class="form-control" type="text" id="tkt_first_name"
                                                            name="first_name"
                                                            onkeypress="return event.charCode >= 65 && event.charCode <= 122">
                                                        <span class="text-danger small" id="first_name_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-6">
                                                    <label for="example-search-input" class=" col-form-label">Last Name
                                                        <span class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input class="form-control" type="text" id="tkt_last_name"
                                                            onkeypress="return event.charCode >= 65 && event.charCode <= 122"
                                                            name="last_name">
                                                        <span class="text-danger small" id="last_name_error"></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 col-sm-12">
                                                    <label for="example-search-input" class=" col-form-label">E-mail
                                                        <span class="text-danger">*</span></label>
                                                    <div class="">
                                                        <input class="form-control" type="text" id="tkt_email"
                                                            name="email">
                                                        <span class="text-danger small" id="email_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-1">
                                            <div class="col-md-4">
                                                <label for="example-search-input" class=" col-form-label">Phone Number
                                                </label>
                                                <div class="d-flex">
                                                    <div class="country mt-1" style="padding-right: 8px;"></div>
                                                    <input type="tel" class="tel form-control" name="phone"
                                                        id="tkt_phone" placeholder="" autofocus>
                                                </div>
                                                <small class="text-danger">Please add country code before number e.g
                                                    (+1) for US</small>
                                                {{-- <div class="">
                                                    <input class="form-control" type="text"
                                                        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                                                        id="tkt_phone" name="phone">
                                                    <span class="text-danger small" id="phone_error"></span>
                                                </div> --}}
                                            </div>

                                            <div class="col-md-3">
                                                <div class="custom-control custom-checkbox" style="margin-top:40px">
                                                    <input type="checkbox" value="1" class="custom-control-input"
                                                        id="create_customer_login" name="create_customer_login">
                                                    <label class="custom-form-label" for="create_customer_login">Create
                                                        Customer Login Account</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="row">
                                                    <div class="col-md-10 form-group">
                                                        <label>Company</label>
                                                        <select
                                                            class="select2-data-array form-select form-control form-control-line"
                                                            id="company_id" onchange="selectCompany(this.value)"
                                                            name="company_id">
                                                            <option value="">Select</option>
                                                            @foreach ($companies as $item)
                                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <button type="button" onclick="newCompany()"
                                                            class="btn btn-info"
                                                            style="margin-top: 20px;position: relative;right:30px">New</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row newCompany mt-3" style="display:none">

                                            <input type="hidden" id="new_company" class="form-control">

                                            <h3>New Company</h3>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="poc_first_name" class="small">Owner First Name</label>
                                                    <input type="text" id="poc_first_name" name="poc_first_name"
                                                        class="form-control">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="poc_last_name" class="small">Owner Last Name</label>
                                                    <input type="text" class="form-control" id="poc_last_name"
                                                        name="poc_last_name">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="name" class="small">Company Name</label>
                                                    <input type="text" id="company_name" name="company_name"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="row mt-1">
                                                <div class="col-md-6">
                                                    <label for="email" class="small">Company Domain</label>
                                                    <input type="text" class="form-control" name="company_domain"
                                                        id="company_domain">
                                                </div>

                                                <div class="col-md-6">
                                                    <label for="phone" class="small">Phone Number</label>
                                                    <div class="d-flex">
                                                        <div class="country mt-1" style="padding-right: 8px;"></div>
                                                        <input type="tel" class="tel form-control"
                                                            name="company_phone_number" id="company_phone_number"
                                                            placeholder="" autofocus>
                                                    </div>
                                                    <small class="text-danger">Please add country code before number e.g
                                                        (+1) for US</small>
                                                    {{-- <input type="text" class="form-control" name="company_phone_number"
                                                        id="company_phone_number"> --}}
                                                </div>
                                            </div>

                                        </div>
                                    </div>



                                    @endif
                                    <div class="row mt-2">
                                        <div class="col-md-12 mb-5">
                                            <label class="control-label">Problem Details<span
                                                    class="text-danger">*</span></label>
                                            <textarea class="form-control d-none" rows="3" id="ticket_details" name="ticket_detail"></textarea>
                                            <div id="editor"></div>
                                            <span class="text-danger small" id="ticket_detail_error"></span>
                                        </div>

                                        <div class="d-flex mt-3" id="check_box_mbl">
                                            <div class="form-check form-check-primary">
                                                <input type="checkbox" value="1" class="form-check-input"
                                                    id="send_email" name="send_email" checked>
                                                <label class="custom-form-label" for="send_email"> Autoresponder
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary ms-2">
                                                <input type="checkbox" value="1" class="form-check-input"
                                                    id="send_details" name="send_details" value="1">
                                                <label class="custom-form-label" for="send_details"> Send Details
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary ms-2">
                                                <input type="checkbox" value="1" class="form-check-input"
                                                    id="response_template" name="response_template" value="1">
                                                <label class="custom-form-label" for="response_template"> Save Response
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-12 bg-light p-2 mt-2" id="response_template_fields"
                                            style="display:none">
                                            <strong>
                                                <h4>Response template properties</h4>
                                            </strong>
                                            <hr>
                                            <form id="responseTemplateForm">
                                                <div class="row mt-1">
                                                    <div class="col-md-6">
                                                        <label for="title">Title</label>
                                                        <input type="text" class="form-control" name="title"
                                                            id="res_title">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="category_name">Category Name</label>
                                                        <select name="category_name" id="category_name" class="select2">
                                                            <option value=""> Choose </option>
                                                            @foreach($response_categories as $tem)
                                                            <option value="{{$tem->id}}"> {{$tem->name}} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-start mt-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="view_access"
                                                            id="onlyMe">
                                                        <label class="form-check-label" for="onlyMe"> Show only to Me
                                                        </label>
                                                    </div>
                                                    <div class="form-check mx-2">
                                                        <input class="form-check-input" type="radio" name="view_access"
                                                            id="allStaff">
                                                        <label class="form-check-label" for="allStaff"> Show to all
                                                            Staff </label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mt-3" id="ticket_attachments">
                                        <button class="btn btn-outline-primary btn-sm" type="button"
                                            onclick="addAttachment()"><span class="fa fa-plus"></span> Add
                                            Attachment</button>
                                        <div class="text-right" style="float: right">
                                            <button type="submit" class="btn waves-effect waves-light btn-success"
                                                id="btnSaveTicket"> <i class="fas fa-check-circle"></i> Create </button>
                                            <button type="button" style="display:none" disabled id="publishing"
                                                class="btn rounded btn-success"> <i
                                                    class="fas fa-circle-notch fa-spin"></i>
                                                Creating ... </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="loader_container" id="status_modal" style="display:none">
                                    <div class="loader"></div>
                                </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>
    </section>
</div>
</div>
<div style="display: none;" id="tinycontenteditor"></div>
@endsection
@section('scripts')
<script>
$(document).on('select2:open', () => {
    document.querySelector('.select2-search__field').focus();
});

$(document).on('select2:open', () => {
    var element = document.querySelector('[aria-controls="select2-customer_id-results"]');
    var type_elem = document.querySelector('[aria-controls="select2-type-results"]');
    var temp_elem = document.querySelector('[aria-controls="select2-res-template-results"]');
    var queue_elem = document.querySelector('[aria-controls="select2-queue_id-results"]'); 
    
    if (element) {
        element.focus();
         $('assigned_to').prop('focus',false);
    }else if(type_elem){
        type_elem.focus();
         $('assigned_to').prop('focus',false);
    }else if(temp_elem){
        temp_elem.focus();
         $('assigned_to').prop('focus',false);
    }else if(queue_elem){
        queue_elem.focus();
         $('assigned_to').prop('focus',false);
    }
    // document.querySelector('.select2-search__field').focus();
});

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script src="{{asset('app-assets/js/scripts/forms/form-file-uploader.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
@include('js_files.help_desk.ticket_manager.add_ticketJs')

<script>
    jQuery(function($){
      var input = $('[type=tel]')
      input.mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
      input.bind('country.mobilePhoneNumber', function(e, country) {
        $('.country').text(country || '')
      })
    });

    $('#assigned_to').select2({
      multiple:true,
    });

  </script>
@endsection
