@extends('layouts.master-layout-new')
@section('title', 'Staff Memebers')
@section('System Manager','open')
@section('Staff Manager','active')
@section('body')
<style>
    .full_name{
        width: 400px !important;
    }
    
    .ml-2{
        padding-left: 5px
    }
    .reqField{
        position: relative;
        /* right: 12px; */
        float: right;
        margin-top: -24px;
        margin-right: 8px;
        font-size: 6px;
    }
    .table th {
    padding: 0.72rem 2rem !important;
}
a {
    color: #7367f0;
    text-decoration: none;}
    .dataTables_length ,.dataTables_info{
        margin-left: 1rem
    }
    #DataTables_Table_0_filter ,#DataTables_Table_0_paginate{
        margin-right: 1rem
    }
</style>
@php
    $path = Session::get('is_live') == 1 ? 'public/' : '/';
@endphp
<input type="hidden" value="{{Session('system_date')}}" id="system_date">

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left  col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-md-10">
                        <h2 class="content-header-title float-start mb-0">Staff Manager</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">System Manager
                                </li>
                                <li class="breadcrumb-item active">Staff Manager
                                </li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button class="btn btn-success btn-sm rounded" id="btn-add-new-user">
                            <i class="mdi mdi-plus-circle"></i> Create
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="content-body">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form class="widget-box widget-color-dark user-table-form"
                            action="?p=schedule-employee-form-get&json=1" method="post">
                            <div class="widget-header widget-header-small">
                                <span class="loader_lesson_plan_form"></span>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="row">
                                        <div class="col-12">
                                            
                                            <div class="table-responsive">
                                            <!-- id="user-table-list" -->
                                                <table
                                                    class="table table-striped table-bordered table-hover w-100 staff_table">
                                                    <thead>
                                                        <tr style="height:50px !important">
                                                            <th>#</th>
                                                            <th>Full Name</th>
                                                            <th>Username</th>
                                                            <th>Phone</th>
                                                            <th>Tags Assigned</th>
                                                            <!-- <th>Created At </th> -->
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($users as $user)
                                                            <tr class="__row_{{$user['id']}}">
                                                                <td> {{$loop->iteration}} </td>
                                                                <td> 
                                                                    <div class="d-flex align-items-center">
                                                                        @if($user['profile_pic'] != null)
                                                                            @if(is_file( getcwd() .'/'. $user['profile_pic'] ))
                                                                                <img src="{{ request()->root() .'/'. $user['profile_pic'] }}" alt="user Photo" width="35" height="35" class="rounded-circle"/>
                                                                            @else
                                                                                <img src="{{asset( $path . 'default_imgs/customer.png')}}"  alt="user Photo" width="35" height="35" class="rounded-circle"/>
                                                                            @endif
                                                                        @else
                                                                            <img src="{{asset( $path . 'default_imgs/customer.png')}}" alt="user Photo" width="35" height="35" class="rounded-circle" />
                                                                        @endif
                                                                        <div class="ml-2">
                                                                            <div class="user-meta-info">
                                                                                <h5 class="user-name mb-0"><a href=" {{url('profile')}}/{{$user['id']}} "> {{$user['name'] != null ? $user['name'] : '-'}} </a></h5>
                                                                                <small class="user-work text-muted">
                                                                                {{ array_key_exists('role' , $user) ? ($user['role'] == null && $user['role'] == "" ? '-' : $user['role']) : '-'}}
                                                                                </small>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td> {{$user['email'] != null ? $user['email'] : '-'}}  </td>
                                                                <td>
                                                                    @if($user['phone_number'] != null) 
                                                                        <a href="tel:{{$user['phone_number']}}"> {{$user['phone_number']}} <a/>
                                                                    @else
                                                                        <span> - </span>
                                                                    @endif
                                                                <td>
                                                                    {{ array_key_exists('staffTags' , $user) ? ($user['staffTags'] == null && $user['staffTags'] == "" ? '-' : $user['staffTags']) : '-'}}
                                                                </td>
                                                                <!-- <td>
                                                                    <div class="show_date" id="show_{{$user['id']}}" data-date="{{\Carbon\Carbon::parse($user['created_at'])}}" data-id="{{$user['id']}}">
                                                                    </div>
                                                                </td> -->
                                                                <td class="text-center">
                                                                    <button type="button" onclick="deleteUsers({{$user['id']}})" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                                                        <i class="fa fa-trash"  aria-hidden="true"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach    
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    
        </div>
    </div> --}}
    <div class="content-body">
        <!-- users list start -->
        <section class="app-user-list">
            <!-- users filter start -->
            {{-- <div class="card">
                <h5 class="card-header">Search Filter</h5>
                <div class="d-flex justify-content-between align-items-center mx-50 row pt-0 pb-2">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_plan"></div>
                    <div class="col-md-4 user_status"></div>
                </div>
            </div> --}}
            <!-- users filter end -->
            <!-- list section start -->
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <table class="staff_table table dataTables_wrapper dt-bootstrap5 no-footer">
                        <thead class="table-light">
                            <tr style="height:50px !important">
                                {{-- <th>#</th> --}}
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Phone</th>
                                <th>Tags Assigned</th>
                                <th>Status</th>
                                <!-- <th>Created At </th> -->
                                <th>Actions</th>
                            </tr>
                            
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="__row_{{$user['id']}}">
                                <td>
                                    <div class="d-flex justify-content-left align-items-center"> 
                                        <div class="avatar-wrapper">
                                        <div class="avatar me-1">
                                            @if($user['profile_pic'] != null)
                                            @if(is_file( getcwd() .'/'. $user['profile_pic'] ))
                                                <img src="{{ request()->root() .'/'. $user['profile_pic'] }}" alt="user Photo" width="35" height="35" class="rounded-circle"/>
                                            @else
                                                <img src="{{asset( $path . 'default_imgs/customer.png')}}"  alt="user Photo" width="35" height="35" class="rounded-circle"/>
                                            @endif
                                        @else
                                            <img src="{{asset( $path . 'default_imgs/customer.png')}}" alt="user Photo" width="35" height="35" class="rounded-circle" />
                                        @endif
                                        </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                        <a href="{{url('profile')}}/{{$user['id']}}" class="user_name text-truncate">
                                        <span class="fw-bold">
                                            {{$user['name'] != null ? $user['name'] : '-'}}
                                        </span></a>
                                        <small class="emp_post text-muted"> 
                                        </small>
                                        </div>
                                        </div>
                                </td>
                               
                                <td>
                                    <span class='text-truncate align-middle'>{{$user['email'] != null ? $user['email'] : '-'}} </span>
                                </td>
                                
                                <td>
                                    <span class='text-truncate align-middle'><svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-50 feather feather-server text-danger"><rect data-v-32017d0f="" x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect data-v-32017d0f="" x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line data-v-32017d0f="" x1="6" y1="6" x2="6.01" y2="6"></line><line data-v-32017d0f="" x1="6" y1="18" x2="6.01" y2="18"></line></svg>{{ array_key_exists('role' , $user) ? ($user['role'] == null && $user['role'] == "" ? '-' : $user['role']) : '-'}}</span> 
                                </td>
                                <td>
                                    @if($user['phone_number'] != null) 
                                        <a href="tel:{{$user['phone_number']}}"> {{$user['phone_number']}} </a>
                                    @else
                                        <span> - </span>
                                    @endif
                                <td>
                                    {{ array_key_exists('staffTags' , $user) ? ($user['staffTags'] == null && $user['staffTags'] == "" ? '-' : $user['staffTags']) : '-'}}
                                </td>
                                <!-- <td>
                                    <div class="show_date" id="show_{{$user['id']}}" data-date="{{\Carbon\Carbon::parse($user['created_at'])}}" data-id="{{$user['id']}}">
                                    </div>
                                </td> -->
                                <td>
                                    <span class="badge rounded-pill badge-light-success">
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                            <svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="align-middle text-body feather feather-more-vertical"><circle data-v-32017d0f="" cx="12" cy="12" r="1"></circle><circle data-v-32017d0f="" cx="12" cy="5" r="1"></circle><circle data-v-32017d0f="" cx="12" cy="19" r="1"></circle></svg>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{url('profile')}}/{{$user['id']}}" class="dropdown-item"><svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text me-50"><path data-v-32017d0f="" d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline data-v-32017d0f="" points="14 2 14 8 20 8"></polyline><line data-v-32017d0f="" x1="16" y1="13" x2="8" y2="13"></line><line data-v-32017d0f="" x1="16" y1="17" x2="8" y2="17"></line><polyline data-v-32017d0f="" points="10 9 9 9 8 9"></polyline></svg> Details</a>
                                        
                                        <a href="javascript:void(0)" onclick="deleteUsers({{$user['id']}})" class="dropdown-item delete-record"><svg data-v-32017d0f="" xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline data-v-32017d0f="" points="3 6 5 6 21 6"></polyline><path data-v-32017d0f="" d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>Delete</a></div>
                                        </div>
                                        </div>
                                    {{-- <button type="button" onclick="deleteUsers({{$user['id']}})" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                        <i class="fa fa-trash"  aria-hidden="true"></i>
                                    </button> --}}
                                </td>
                            </tr>    
                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Modal to add new user starts-->
                <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
                    <div class="modal-dialog">
                        <form class="add-new-user modal-content pt-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                            <div class="modal-header mb-1">
                                <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                            </div>
                            <div class="modal-body flex-grow-1">
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-fullname">Full Name</label>
                                    <input type="text" class="form-control dt-full-name" id="basic-icon-default-fullname" placeholder="John Doe" name="user-fullname" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-uname">Username</label>
                                    <input type="text" id="basic-icon-default-uname" class="form-control dt-uname" placeholder="Web Developer" aria-label="jdoe1" aria-describedby="basic-icon-default-uname2" name="user-name" />
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="basic-icon-default-email">Email</label>
                                    <input type="text" id="basic-icon-default-email" class="form-control dt-email" placeholder="john.doe@example.com" aria-label="john.doe@example.com" aria-describedby="basic-icon-default-email2" name="user-email" />
                                    <small class="form-text"> You can use letters, numbers & periods </small>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="user-role">User Role</label>
                                    <select id="user-role" class="form-select">
                                        <option value="subscriber">Subscriber</option>
                                        <option value="editor">Editor</option>
                                        <option value="maintainer">Maintainer</option>
                                        <option value="author">Author</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label" for="user-plan">Select Plan</label>
                                    <select id="user-plan" class="form-select">
                                        <option value="basic">Basic</option>
                                        <option value="enterprise">Enterprise</option>
                                        <option value="company">Company</option>
                                        <option value="team">Team</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary me-1 data-submit">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal to add new user Ends-->
            </div>
            <!-- list section end -->
        </section>
        <!-- users list ends -->

    </div>
    <div class="modal fade" id="addNewUser" tabindex="-1" aria-labelledby="addNewUserTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="">User Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="widget-box widget-color-dark user-form mb-0" id="save_user" action="{{asset('/insert_user')}}" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="staff_id" id="staff_id">
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-row row">
                                    <input name="emp_ids" style="display: none;" class="form-control" type="text" value="" readonly="readonly">

                                    <div class="col-12">
                                        <div class="form-group">
                                            <figure style="text-align: center;">
                                                <label for="profile_pic">
                                                    <img class="profile_pic_img" src="{{URL::asset('files/user_photos/user-photo.jpg')}}" height="100" width="100" style="border-radius:50%">
                                                </label>
                                            </figure>
                                            <input accept="image/*" onchange="UserImgValidation()" name="user_photo" type="file" style="display: none;" id="profile_pic" />

                                            <small class="text-muted">
                                                Max. file size: 2MB & of type: jpeg/jpg/png
                                            </small>
                                        </div>
                                    </div>

                                    <hr>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="block input-icon input-icon-right">
                                                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                                <input name="full_name" id="full_name" class="form-control" type="text" value="" placeholder="" required>
                                                {{-- <span class="fa fa-asterisk field-icon text-danger reqField"></span> --}}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <span class="block input-icon input-icon-right">
                                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                                <input name="email" id="email" class="form-control" type="text" value="" placeholder="" required>
                                                {{-- <span class="fa fa-asterisk field-icon text-danger reqField"></span> --}}
                                            </span>
                                            <span class="small text-danger" id="email_error"></span>
                                            <small class="text-muted">
                                                Username e.g username@example.com.
                                            </small>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group user-password-div w-100">
                                            <label class="form-label">Password <span class="text-danger">*</span></label>
                                            <span class="block input-icon input-icon-right d-flex">
                                                
                                                <div class=" input-group form-password-toggle ">
                                                
                                                    <input type="password" name="password" id="staffpassword" class="form-control" value="" placeholder="" required>
                                                    <div class="input-group-text cursor-pointer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    </div>
                                                </div>
                                                {{-- <input name="password" id="staffpassword" class="form-control" type="password" value="" placeholder="Password" required> --}}
                                                <a class="btn btn-primary ml-auto" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Generate" type="button" onclick="generatePassword()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shuffle"><polyline points="16 3 21 3 21 8"></polyline><line x1="4" y1="20" x2="21" y2="3"></line><polyline points="21 16 21 21 16 21"></polyline><line x1="15" y1="15" x2="21" y2="21"></line><line x1="4" y1="4" x2="9" y2="9"></line></svg></a>
                                                <a class="btn btn-success" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Copy" onclick="copyPassword()"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-copy"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg></a>
                                            </span>
                                            <span class="small text-danger" id="password_error"></span>
                                            <small class="text-muted">
                                                Must be 8 characters long.
                                            </small>
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="form-group user-confirm-password-div">
                                            <span class="block input-icon input-icon-right">
                                                <input name="confirm_password" class="form-control" type="password" value="" placeholder="Confirm Password" onkeyup="confirmPassword(this, 'save_user');">
                                                <i class="ace-icon fa fa-check check-match field-icon" style="margin-right: 48px; font-size: small; display: none;"></i>
                                                <span class="fa fa-asterisk field-icon text-danger reqField"></span>
                                            </span>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-6 form-group hire-input-country_box position-relative">
                                        <div class="form-group">
                                            <label class="form-label">Enter Phone Number</label>
                                            <div class="d-flex">
                                            <div class="country mt-1" style="padding-right: 8px;"></div>
                                            
                                            <input type="tel" class="tel form-control" name="phone_number" id="phone" placeholder="" autofocus>
                                            
                                        </div>
                                        <small class="text-danger">Please add country code before number e.g (+1) for US</small>
                                    </div>
                                    </div>
                                    

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">User Role <span class="text-danger">*</span></label>

                                            <select class="select2 form-select" id="role_id" name="role_id">
                                                @foreach($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mt-1 mb-1">
                                        <div class="form-group">
                                            <label class="form-label">Select Tag</label>
                                            <select class="tags-select select2 form-control" id="tags" name="tags" multiple="multiple" style="height: 36px;width: 100%;">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group" style="display:none;">
                                            <select name="status" id="status" class="form-control">
                                                <option value='1' selected>Active</option>
                                                <option value='0'>Deactive</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-1">
                                        <div class="form-group">
                                            <label class="form-label">SMS</label>
                                            <input name="sms" id="sms" class="form-control" type="text">
                                            <span class="text-danger small" id="sms_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-1">
                                        <div class="form-group">
                                            <label class="form-label">WhatsApp </label>
                                            <input type="text" name="whatsapp" id="whatsapp" class="form-control" placeholder="">
                                            <span class="text-danger small" id="wtsapp_error"></span>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end">
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal" >Close</button>
                                    <button type="Submit" id="usr_save" class="btn waves-effect waves-light btn-sm rounded btn-success"><i class="fas fa-check-circle"></i> Save</button>
                                    
                                    <button type="button" style="display:none" disabled id="usr_pro" class="btn btn-sm rounded btn-success"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="loader_container" id="usr_loader" style="display:none">
                        <div class="loader"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection
@section('scripts')

    <script>
        $('#addNewUser').draggable();
        function copyPassword() {
            
            var copyText = document.getElementById("staffpassword");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);

        }

        // $(function() {
        //     $("#country").change(function() {
        //         let countryCode = $(this).find('option:selected').data('country-code');
        //         let value = "+" + $(this).val();
        //         $('#phone').val(value).intlTelInput("setCountry", countryCode);
               
        //     });
            
        //     var code = "+1";
        //     $('#phone').val(code).intlTelInput();
        // });

        $('.staff_table').DataTable();
        
        // let a = "{{Session('system_date')}}";
        // console.log(a , "a");

        // $('.show_date').each(function() {
        //     let date = $(this).data('date');
        //     let id = $(this).data('id');
        //     $("#show_"+id).html( moment(date).format("{{Session('system_date')}}") );
        // });
    </script>
    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
    @include('js_files.system_manager.staff_management.staffJs')
    @include('js_files.system_manager.staff_management.indexJs')
    <script>
        jQuery(function($){
          var input = $('[type=tel]')
          input.mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
          input.bind('country.mobilePhoneNumber', function(e, country) {
            $('.country').text(country || '')
          })
        });
      </script>
@endsection