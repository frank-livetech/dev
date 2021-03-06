@extends('layouts.staff-master-layout')
@section('body-content')
<style>
    .customdropdown  {
        width: 100% !important;
        height:36px !important;
    }
    .error {
        color:red;
    }
</style>

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-md-5 align-self-center">
            <div class="d-flex align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        @if (isset($page_control))
                            <li class="breadcrumb-item">
                            @if ($page_control == 'customer')
                                <a href="{{url()->previous()}}">Customer Profile</a>
                            @else
                                <a href="{{asset('/ticket-manager')}}">Ticket Manager</a>
                            @endif
                            </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">Add Ticket</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

@if(Auth::user()->user_type == 5)
    <input type="hidden" id="for_customer_profile_id" value="{{Auth::user()->id}}">
@endif
<input type="hidden" id="previous_url" value="{{url()->previous()}}">

<div class="container-fluid ">
    <div class="row">
        <div class="col-12">
        <div class="card p-3">
            <form class="form-horizontal mt-4" id="save_tickets">
                <div class="row">
                    <div class="col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="row ">
                                <div class="col-sm-8">
                                    <label class="control-label">Subject <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="subject" name="subject" required>
                                    <span class="text-danger small" id="subject_error"></span>
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label">Select Department<span class="text-danger">*</span></label> <br>
                                    <select  class="select2 form-control customdropdown" style="width:100%" onchange="showDepartStatus(this.value)" id="dept_id" name="dept_id" required>
                                        {{-- <option value="">Select </option> --}}
                                        @if($departments != null && $departments != "")
                                            @foreach($departments as $key => $department)
                                                @if ($key == 0)
                                                    <option value="{{$department->id}}" selected>{{$department->name}}</option>
                                                @else
                                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="text-danger small" id="department_error"></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                @if(Auth::user()->user_type != 5)
                                    <div class="col-sm-4" style="position:relative">
                                        <label class="control-label">Select Status<span class="text-danger">*</span></label>
                                        <select class="select2 form-control dropdown" style="width:100%" id="status" name="status">
                                        </select>
                                        <i class="fas fa-circle-notch fa-spin text-primary" id="dropdown_loader" style="position: absolute; top:34px;font-size:1.2rem; right: 34px; display:none"></i>
                                        <span class="text-danger small" id="status_error"></span>
                                    </div>
                                @endif

                                <div class="col-sm-4">
                                    <label class="control-label">Select Priority<span class="text-danger">*</span></label>
                                    <select class="select2 form-control dropdown" style="width:100%" id="priority" name="priority" required>
                                        {{-- <option value="">Select </option> --}}
                                        @if($priorities != null && $priorities != "")
                                            @foreach($priorities as $key=> $priority)
                                                @if ($key == 0)
                                                    <option value="{{$priority->id}}" selected>{{$priority->name}}</option>
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
                                        <label class="control-label">Assign Tech </label>
                                        <select class="select2 form-control dropdown" style="width:100%" id="assigned_to" name="assigned_to">
                                            <option value="">Unassigned</option>
                                            {{-- @foreach($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                @endif

                            </div>
                        </div>


                        @if(Auth::user()->user_type != 5)
                            <div class="form-group">
                                <div class="row mt-3">

                                    <div class="col-sm-4">
                                        <label class="control-label">Select Type
                                        <span class="text-danger">*</span></label>
                                        <select class="select2 form-control dropdown" style="width:100%" id="type" name="type" required>
                                            {{-- <option value="">Select</option> --}}
                                            @if($types != null && $types != "")
                                                @foreach($types as $key => $type)
                                                    @if ($key == 0)
                                                        <option value="{{$type->id}}" selected>{{$type->name}}</option>
                                                    @else
                                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="text-danger small" id="type_error"></span>
                                    </div>

                                    <div class="col-sm-4" id="select_customer">
                                        <label class="control-label">Customer Select<span class="text-danger">*</span></label>
                                        <select class="select2 form-control custom-select dropdown w-100" id="customer_id" name="customer_id" style="width:100%">
                                            {{-- <option value="">Select</option> --}}
                                            @if($customers != null && $customers != "")
                                                @foreach($customers as $key => $customer)
                                                    @if ($key == 0)
                                                        <option value="{{$customer->id}}" selected>{{$customer->first_name}} {{$customer->last_name}} | {{$customer->email}}</option>
                                                    @else
                                                        <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}} | {{$customer->email}} </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <span class="text-danger small" id="customer_id_error"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="control-label">Due Date </label>
                                        <input class="form-control" type="date" id="deadline" name="deadline" onkeydown="return false">
                                    </div>

                                    <div class="col-12" id="new_customer_div">
                                        <div class="custom-control custom-checkbox mr-sm-2 ml-2 mt-3">
                                            <input type="checkbox" value="1" class="custom-control-input" id="new_customer">
                                            <label class="custom-control-label" for="new_customer">New Customer</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="new_customer_form" style="display:none" class="p-3 bg-light">
                                <div class="form-group">
                                    <h3 class="font-weight-bold">New Customer</h3>
                                    <hr>
                                    <div class="row mt-3">
                                        <div class="col-md-4 col-sm-6">
                                            <label for="example-search-input"
                                                class=" col-form-label">First Name <span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="form-control" type="text" id="tkt_first_name" name="first_name" onkeypress="return event.charCode >= 65 && event.charCode <= 122">
                                                <span class="text-danger small" id="first_name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <label for="example-search-input"
                                                class=" col-form-label">Last Name <span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="form-control" type="text" id="tkt_last_name" onkeypress="return event.charCode >= 65 && event.charCode <= 122"
                                                    name="last_name">
                                                    <span class="text-danger small" id="last_name_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label for="example-search-input"
                                                class=" col-form-label">E-mail <span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="form-control" type="text" id="tkt_email" name="email">
                                                <span class="text-danger small" id="email_error"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row mt-3" >
                                        <div class="col-md-6 col-sm-6">
                                            <label for="example-search-input"
                                                class=" col-form-label">Phone Number <span class="text-danger">*</span></label>
                                            <div class="">
                                                <input class="form-control" type="text" onkeypress="return event.charCode >= 48 && event.charCode <= 57" id="tkt_phone" name="phone">
                                                <span class="text-danger small" id="phone_error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="custom-control custom-checkbox" style="margin-top:40px">
                                                <input type="checkbox" value="1" class="custom-control-input" id="create_customer_login" name="create_customer_login">
                                                <label class="custom-control-label" for="create_customer_login">Create Customer Login Account</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="row mt-3">
                                    <div class="col-sm-4" id="select_customer">
                                        <label class="control-label">Response Template</label>
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
                            </div>
                        @endif
                        <div class="form-group">
                            <div class="col-12 p-0">
                                <label class="control-label">Problem Details<span class="text-danger">*</span></label>
                                <textarea class="form-control" rows="3" id="ticket_detail" name="ticket_detail"></textarea>
                                <span class="text-danger small" id="ticket_detail_error"></span>
                            </div>
                        </div>
                        <div class="form-group" id="ticket_attachments">
                            <div class="col-12 p-0">
                                <button class="btn btn-outline-primary btn-sm" type="button" onclick="addAttachment()"><span class="fa fa-plus"></span> Add Attachment</button>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn waves-effect waves-light btn-success" id="btnSaveTicket"> <i class="fas fa-check-circle"></i> Save</button>
                            <button type="button" style="display:none" disabled id="publishing" class="btn rounded btn-success"> <i class="fas fa-circle-notch fa-spin"></i> Saving... </button>
                        </div>
                    </div>
                </div>

                <div class="loader_container" id="status_modal" style="display:none">
                    <div class="loader"></div>
                </div>

            </form>
        </div>
        </div>
    </div>
</div>

<div style="display: none;" id="tinycontenteditor"></div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
    {{-- <script src="{{asset('assets/libs/tinymce/tinymce.min.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
@include('js_files.help_desk.ticket_manager.add_ticketJs')

@endsection
