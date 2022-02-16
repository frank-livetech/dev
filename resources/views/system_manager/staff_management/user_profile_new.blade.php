@extends('layouts.master-layout-new')
@section('title', 'Staff Profile | ' . $profile->name )
@push('css')
<link href="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet" />
    <style>
        .demo-inline-spacing3{
            background-color: unset !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between !important;
            align-items: center;
            padding: 14px;
            border-radius: 5px;
        }
        hr:not([size]) {
            height: 1px;
            margin: 15px;
        }
            .f-btn{
                float: right
            }
            .buttons-copy {
                background: #238fac;
                color: #fff;
                border-color: hsl(193, 66%, 41%);
                font-weight: 700
            }
            .buttons-excel{
                background: #026e39;
                color: #fff;
                border-color: #026e39;
                font-weight: 700
            }
            .buttons-pdf{
                background: #CC4438;
                color: #fff;
                font-weight: 700;
                border-color: #CC4438;
            }
            .float-right{
                float: right
            }
            .mti-2 {
                margin-top: 1.9rem !important;
                }
            .demo-inline-spacing {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between !important;
                align-items: center;
                background-color: rgb(243, 239, 239);
                padding: 14px;
                border-radius: 5px
            }
            .demo-inline-spacing1 {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between !important;
                align-items: center;
               padding: 7px;
                border-radius: 5px
            }
            .demo-inline-spacing > * {
                margin-right: unset;
                margin-top: unset;
}   
.soc-card {
        justify-content: space-between;
        display: flex;
    }

    .soc-ico {
        font-size: 24px;
    }
.picEdit{
    cursor:pointer;
    position: absolute;
    top: 72px;
    left: 139px;
    border: 1px solid #fff;
    padding: 4px;
    border-radius: 100%;
    background: #fbfbfb;
}
/* #curr_user_pic figure {
    background: #4a3753;
} */
#curr_user_pic figure img{
    opacity: 1;
	-webkit-transition: .3s ease-in-out;
	transition: .3s ease-in-out;
}
#curr_user_pic figure:hover img {
	opacity: .5;
}

    </style>
@endpush
@section('body')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-7 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">My Profile</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item">My Profile
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        @if($date_format) 
        <input type="hidden" id="system_date_format" value="{{$date_format}}">
        @else
            <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
        @endif
        <div class="row">
            <div class="col-lg-3 col-xlg-3 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="mt-4 text-center" id="curr_user_pic">
                            @php
                                $path = Session::get('is_live') == 1 ? 'public/' : '/';
                            @endphp
                            <a href="#" id="uploadProfilePic">
                                @if($profile->profile_pic != null)
                                    @if(is_file( getcwd() .'/'. $profile->profile_pic ))
                                    <figure><img src="{{ request()->root() .'/'. $profile->profile_pic }}" class="rounded-circle"
                                        width="100" height="100" id="profile-user-img" /></figure>
                                    @else
                                    <figure><img src="{{asset( $path . 'default_imgs/customer.png')}}" class="rounded-circle" width="100" height="100"
                                        id="profile-user-img" /></figure>
                                    @endif
                                @else
                                <figure><img src="{{asset( $path . 'default_imgs/customer.png')}}" class="rounded-circle" width="100" height="100"
                                    id="profile-user-img" /></figure>
                                @endif
                            </a>
                            <!-- <a type="button" data-bs-toggle="modal" data-bs-target="#editPicModal"><i class="fa fa-pencil-alt picEdit"></i></a> -->
                            <h4 class="card-title mt-2" id="staff_name">{{$profile->name}}</h4>
                            <span class="badge bg-info badge-pill text-white" id="job_title_bg">{{$profile->job_title}}</span>
                        </div>
                    </div>
                    <div>
                        <hr>
                    </div>
                    <div class="card-body">
                        <input type="hidden" id="user_id" value="{{$profile->id}}">
    
                        <small class="text-muted">Email address <a href="#" class="text-muted" onclick="openSettingTab()"><i class="fas 
                        fa-pencil-alt float-right" aria-hidden="true"></i></a></small>
                        
                        <h6> 
                            <a href="mailto:{{$profile->email}}" title="{{$profile->email}}">
                                {{ $profile->email != null ? Str::of($profile->email)->before('@') : '-'}}
                            </a> 
                        </h6>

                        <small class="text-muted pt-4 db">Phone</small>
                        <h6><a href="tel:{{$profile->phone_number}}" id="staff_phone">{{$profile->phone_number}}</a></h6>
                        <small class="text-muted pt-4 db">Address</small> <br>
    
                        <span id="staff_address">{{$profile != null ? $profile->address : ' '}}</span> 
                        <span id="staff_apt_address">{{$profile->apt_address != null ? ','.$profile->apt_address : ''}}</span>
    
                        <span id="staff_city">{{$profile != null ? $profile->city : ' '}}</span>
    
                        @if($profile->state != null && $profile->state != '')
                            <span id="staff_state">{{ ', '. $profile->state }}</span>
                        @else
                            <span id="staff_state"></span>
                        @endif
    
                        <span id="staff_zip">{{$profile->zip != null ? ','. $profile->zip : ' '}}</span>
    
                        @if($profile->country != null && $profile->country != '')
                            <span id="staff_coun">{{ ', ' . $profile->country}}</span>
                        @else
                            <span id="staff_coun"></span>
                        @endif

                        <div class="loader_container" id="usr_prf_loader" style="display:none">
                            <div class="loader"></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                    <div id="map_2" class="gmaps"></div>
                    <input type="hidden" id="google_api_key">
                    <h2 class="mt-4 font-weight-bold text-dark">Social Links</h2>
                        <div class=" soc-card mt-3">
                            <a href="{{$profile->twitter}}" id="twt_link" target="_blank" style="color: #009efb;font-size:24px"><i class="soc-ico  fab fa-twitter"></i></a>
    
                            <a href="{{$profile->fb}}" id="fb_link" target="_blank" style="color:#0570E6;font-size:24px"><i class="soc-ico fab fa-facebook"></i></a>
    
                            <a href="{{$profile->pinterest}}" id="pint_link" target="_blank" style="color:#DF1A26;font-size:24px"><i class="soc-ico  fab fa-pinterest-square"></i></a>
    
                            <a href="{{$profile->insta}}" id="insta_link" target="_blank" style="color:#e1306c;font-size:24px"><i class="soc-ico  fab fa-instagram"></i></a>
    
                            <a href="{{$profile->linkedin}}" id="linkedin_link" target="_blank" style="color:#0e76a8; font-size:24px"><i class="fab fa-linkedin"></i></a>
    
                            <a href="{{$profile->website}}" id="website_link" target="_blank" style="font-size:24px"><i class="fas fa-globe"></i></a>
    
                        </div>
                        
                        <div class="loader_container" id="usr_lnk_loader" style="display:none">
                            <div class="loader"></div>  
                        </div>
                    </div>
                    <div class="row">
                        <span class="text-danger text-center small" style="width:100%" id="social-error"></span>
                    </div>
                </div>
            </div>
    
            <div class="col-lg-9 col-xlg-9 col-md-7 ">
                <div class="card">
                    <!-- Tabs -->
                    <ul class="nav nav-pills custom-pills mt-1 ml-1" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-setting-tab" data-bs-toggle="pill" href="#previous-month" role="tab" aria-controls="pills-setting" aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="my-history-tab" data-bs-toggle="pill" href="#current-month" role="tab" aria-controls="my-history" aria-selected="true">History</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="my-schedule-tab" data-bs-toggle="pill" href="#staff-schedule" role="tab" aria-controls="my-schedule" aria-selected="true">Schedule</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="payroll-tab" data-bs-toggle="pill" href="#last-orders" role="tab" aria-controls="payroll" aria-selected="false">Payroll</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="my-certifications-tab" data-bs-toggle="pill" href="#last-certifications" role="tab" aria-controls="my-certifications" aria-selected="false">Certifications</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-tickets-tab" data-bs-toggle="pill" href="#tickets" role="tab" aria-controls="pills-tickets" aria-selected="false">Tickets</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-documents-tab" data-bs-toggle="pill" href="#user_docs_tab" role="tab" aria-controls="pills-documents" aria-selected="false">Documents</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-bucket-tab" data-bs-toggle="pill" href="#user_bucket_tab" role="tab" aria-controls="pills-bucket" aria-selected="false">Task Bucket</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-bucket-tab" data-bs-toggle="pill" href="#user_notifi_permission" role="tab" aria-controls="pills-bucket" aria-selected="false">Notification Permission</a>
                        </li>
                    </ul>
                    <!-- Tabs -->
                    <div class="tab-content" id="pills-tabContent">

                        <!-- profile -->
                        <div class="tab-pane fade show active" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body p-1">
                                <hr>
                                <form action="{{asset('/update-staff')}}" method="post" enctype="multipart/form-data" id="update_user">
                                    <h2 class="mt-4 font-weight-bold text-dark">Personal Info</h2>
                                    <div class="row">
                                        <input type="hidden" value="{{$profile->id}}" id="profile_id">
    
                                        <div class="col-md-6 form-group">
                                            <label>Name</label> <span class="text-danger">*</span>
                                            <input type="text" name="full_name" id="full_name" placeholder="Name" value="{{$profile->name}}" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Job Title</label>
                                            <input type="text" name="job_title" id="job_title" placeholder="Job Title" value="{{$profile->job_title}}" class="form-control">
                                        </div>
                                    </div>
    
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label for="example-email">Email</label><span class="text-danger">*</span>
                                            <input type="email" value="{{$profile->email}}" placeholder="Email" class="form-control " name="email" id="email" disabled required>
    
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Phone No </label>
                                            <input type="phone" name="phone" id="phone" value="{{$profile->phone_number}}" placeholder="Phone" class="form-control">
                                            <span class="text-danger small" id="phone_error"></span>
                                        </div>
                                    </div>

                                    <div class="row mt-1 mb-2">
                                        <div class="col-md-12">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="change_password_checkbox" name="change_password_checkbox">
                                                <label class="form-check-label" for="change_password_checkbox"> Change Password </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-1 change_password_row" style="display:none">
                                        <div class="col-md-4 form-group">
                                            <label>Old Password</label>
                                            <div class=" input-group form-password-toggle input-group-merge">
                                                <input type="password" name="old_password" id="old_password" class="form-control">
                                                <div class="input-group-text cursor-pointer">
                                                    <i data-feather="eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>New Password</label> <span class="text-danger">*</span>
                                            <div class=" input-group form-password-toggle input-group-merge">
                                                <input type="password" name="password" id="update_password" class="form-control">
                                                <div class="input-group-text cursor-pointer">
                                                    <i data-feather="eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label>Confirm Password</label>
                                            <div class="input-group form-password-toggle input-group-merge">
                                                <input class="form-control " type="password" id="confirm_password" name="confirm_password">
                                                <div class="input-group-text cursor-pointer">
                                                    <i data-feather="eye"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
    
                                    <div class="row mt-1">
                                        <div class="col-12">
                                            <label>Street Address</label>
                                            <div class="row">
                                                <div class="col-md-6 form-group">
                                                    <input type="text" name="address" class=" form-control" value="{{$profile->address}}" id="address" placeholder="House number and street name">
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <input type="text" name="apt_address" id="apt_address" class=" form-control" value="{{$profile->apt_address}}" id="" placeholder="Apartment, suit, unit etc. (optional)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
    
    
                                    <div class="row mt-1">
                                        <div class="col-md-3 form-group">
                                            <label>City</label>
    
                                            <input type="text" name="city" class="form-control" value="{{$profile->city}}" id="update_city">
    
                                            <span class="text-danger" id="err2"></span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>State</label>
                                            @if(isset($google_api) && $google_api == 1) 
                                                <input type="text" name="state" class="form-control" value="{{$profile->state}}" id="state">
                                            @else
                                                <select class="select2 form-control" id="state" name="state" style="width: 100%; height:36px;"></select>
                                            @endif
                                            
                                            <span class="text-danger" id="err1"></span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Zip Code</label>
    
                                            <input type="text" name="zip" class="form-control" value="{{$profile->zip}}" id="update_zip">
    
                                            <span class="text-danger" id="err3"></span>
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Country</label>
                                            @if(isset($google_api) && $google_api == 1) 
                                                <input type="text" name="country" class="form-control" value="{{$profile->country}}" id="country">
                                            @else
                                                <select class="select2 form-control" name="country" id="country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'state', 'state')">
                                                    <option value="">Select Country</option>
                                                    @foreach ($countries as $cty)
                                                        @if(!empty($profile->country) && $profile->country == $cty->name)
                                                            <option value="{{$cty->name}}" selected>{{$cty->name}}</option>
                                                        @else
                                                            <option value="{{$cty->name}}" {{$cty->short_name == 'US' ? 'selected' : ''}}>{{$cty->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            @endif
                                            <span class="text-danger" id="err"></span>
                                        </div>
                                    </div>
    
    
                                    <h2 class="mt-4 font-weight-bold text-dark">Social</h2>
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label class="small">Facebook</label>
                                            <input type="url" name="fb" class="form-control" value="{{$profile->fb}}" placeholder="https://facebook.com/yourprofile" id="update_fb">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="small">Pinterest</label>
                                            <input type="url" name="pinterest" class="form-control" value="{{$profile->pinterest}}" placeholder="https://pinterest.com/@Username" value="{{$profile->pinterest}}" id="update_pinterest">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label class="small">Twitter</label>
                                            <input type="url" name="twitter" class="form-control" value="{{$profile->twitter}}" placeholder="https://twitter.com/username" value="{{$profile->twitter}}" id="update_twt">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="small">Instagram</label>
                                            <input type="url" name="insta" class="form-control" value="{{$profile->insta}}" placeholder="https://instagram.com/username" value="{{$profile->insta}}" id="update_ig">
                                        </div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label class="small">LinkedIn</label>
                                            <input type="url" name="linkedin" class="form-control" value="{{$profile->linkedin}}" placeholder="https://linkedin.com/username" value="{{$profile->linkedin}}" id="update_linkedin">
                                        </div>
                                    
                                    
                                        <div class="col-md-6 form-group">
                                            <label class="small">Website</label>
                                            <input type="text" id="website" name="website" class="form-control" value="{{$profile->website}}" placeholder="https://www.yoursite.com" id="update_website">
                                            <span class="text-danger small" id="website_error"></span>
                                        </div>
                                    </div>
    
    
                                    <div class="row mt-1">
                                        <div class="col-md-12 form-group">
                                            <label>About</label>
    
                                            <textarea class="form-control" name="notes" id="notes" cols="30" rows="5">{{$profile->notes}}</textarea>
    
                                        </div>
                                    </div>
    
                                    <div class="row mt-2">
                                        <div class="col-sm-12 text-right">
                                            <button class="btn btn-success rounded float-right" id="usr_btn" type="submit"><i class="fas fa-check-circle"></i> Save</button>
                                            <button type="button" style="display:none" disabled id="usr_process" 
                                                class="btn  rounded btn-success float-right"> 
                                                <i class="fas fa-circle-notch fa-spin"> </i> Processing 
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="loader_container" id="usr_loader" style="display:none">
                                    <div class="loader"></div>
                                </div>
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="current-month" role="tabpanel" aria-labelledby="my-history-tab">
                            <hr>
                            <div class="card-body">
                                No Data Found
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="staff-schedule" role="tabpanel" aria-labelledby="my-schedule-tab">
                            <hr>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="d-flex justify-content-between p-2">
                                                <h3 class="lead">View Your Schedule here Below</h3>
                                                <button onclick="openScheduleModal()" class="btn btn-success  rounded"> <i class="fas fa-plus-circle"></i> Set Schedule </button>
                                            </div>
    
                                            <div class="card-body">
                                                <div id="calendar" class="dashboard-calendar"></div>
    
                                                <!-- BEGIN MODAL -->
                                                <div class="modal fade none-border" id="my-event">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header d-flex align-items-center">
                                                                <h4 class="modal-title"><strong> Add Work Time </strong></h4>
                                                                <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                            </div>
                                                            <div class="modal-body"></div>
                                                            <!-- <div class="modal-footer">
                                                                <button type="button" class="btn btn-white waves-effect" data-dismiss="modal">Close</button>
                                                                <button type="button" class="btn btn-success save-event waves-effect waves-light">Save</button>
                                                                <button type="button" class="btn btn-danger delete-event waves-effect waves-light" data-dismiss="modal">Delete</button>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
                            </div>
                            <hr>
    
                            <div class="card p-2">
                                <div class="d-flex justify-content-between p-2">
                                    <h3>Leaves Section</h3>
                                    <button onclick="requestLeaveModal()" class="btn btn-success rounded"> <i class="fas fa-plus-circle"></i> Request Leave</button>
                                </div>
    
                                <table class="table table-hover table-bordered w-100 text-center" id="leaves-table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Reason</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                    </tbody>
                                </table>
                            </div>
    
                        </div>
    
                        <div class="tab-pane fade" id="last-month" role="tabpanel" aria-labelledby="my-subscriptions-tab">
                            <div class="card-body">
                                <p>No Subscriptions</p>
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="last-orders" role="tabpanel" aria-labelledby="payroll-tab">
    
                            <div class="card-body" style="overflow: overlay;">
                                    <div class="form-check form-check-inline">
                                        <!-- <label for="customRadio">From</label> -->
                                        <input type="radio" id="today" onclick="filterData('today')" name="customRadio" class="form-check-input" checked>
                                        <label class="form-check-label" for="today">Today</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <!-- <label for="customRadio">To</label> -->
                                        <input type="radio" id="date_range" onclick="filterData('date_range')" name="customRadio" class="form-check-input">
                                        <label class="form-check-label" for="date_range">Date Range</label>
                                    </div>
    
                                <div class="row my-2">
                                    <label class="col-12">{{$general_staff_note}}</label>
                                    <label class="col-12">{{in_array($id, $selected_staff_members) ? $note_for_selected_staff : ''}}</label>
                                </div>
    
                                <div id="daterangediv" style="display:none">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Start Date & Time</label>
                                            <input type="datetime-local" id="start_date" class="form-control">
                                        </div>
                                        <div class="col-md-5">
                                            <label>End Date & Time</label>
                                            <input type="datetime-local" id="end_date" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <button onclick="getStaffDateWise()" class="btn waves-effect waves-light mt-2 btn-success" type="button">Search</button>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="row pt-4">
                                    <div class="col-md-3">
                                        <div class="card border-bottom border-primary">
                                            <div class="box p-2 rounded  text-center">
                                                <h5 class="" id="avg_hours_in_day">00:00</h5>
                                                <h6 class="text-primary">Avg Hours a Day</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-bottom border-warning">
                                            <div class="box p-2 rounded text-center">
                                                <h5 class="" id="avg_hours">00:00</h5>
                                                <h6 class="text-warning">Avg Hours a Week</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-bottom border-info">
                                            <div class="box p-2 rounded text-center">
                                                <h5 class="" id="total_hours">00</h5>
                                                <h6 class="text-info">Total Hours</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="card border-bottom border-success">
                                            <div class="box p-2 rounded  text-center">
                                                <h5 class="">00</h5>
                                                <h6 class="text-success">Days without Checkin</h6>
                                            </div>
                                        </div>
                                    </div>
    
    
                                </div>
                                <table id="payroll_table" class="table table-striped table-hover text-center table-bordered no-wrap w-100" role="grid" aria-describedby="zero_config_info">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Clock In</th>
                                            <th>Clock Out</th>
                                            <th>Hours Worked</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                        <div class="loader_container" style="display:none">
                                            <div class="loader"></div>
                                        </div>
                                </table>
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="last-certifications" role="tabpanel" aria-labelledby="my-certifications-tab">
    
                            <div class="card-body">
                                <button type="submit" style="float:right" class="btn btn-success" onclick="ShowCertificateModel()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add
                                    Certificate </button>
    
                                <!--  -->
                                <div class="mt-5">
                                    <div class="table-responsive pt-3">
                                        <table id="asset_table_list" class="table table-striped table-bordered w-100 asset-table-list">
                                            <thead>
                                                <tr>
                                                    <th>Sr</th>
                                                    <th class="text-center">Image</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th class="text-center">Download</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
    
                                        </table>
                                    </div>
                                </div>
    
                                <!--  -->
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="pills-tickets-tab">
                            <div class="card-body">
                            <div class="text-right mb-3">
                                    <a href="{{url('add-ticket')}}/{{$customer->id}}" class="btn btn-info rounded ml-auto mb-auto float-right mb-3">
                                        <i class="fas fa-plus"></i>&nbsp;Add ticket
                                    </a>
                                    <!-- <button type="button" class="btn btn-info ml-auto mb-auto" onclick="ShowTicketsModel()">
                                        <i class="fas fa-plus"></i>&nbsp;Add ticket
                                    </button> -->
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('total')" class="card card-hover border-info" >
                                            <div class="box p-2 rounded info text-center">
                                                <h1 class="font-weight-light " id="total_tickets_count"></h1>
                                                <h6 class="text-info">All Tickets</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('open')" class="card card-hover border-warning" >
                                            <div class="box p-2 rounded warning text-center">
                                                <h1 class="font-weight-light " id="open_ticket_count"></h1>
                                                <h6 class="text-warning">Open</h6>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="javascript:listTickets('closed')" class="card card-hover border-primary">
                                            <div class="box p-2 rounded primary text-center">
                                                <h1 class="font-weight-light " id="closed_tickets_count"></h1>
                                                <h6 class="text-primary">Closed</h6>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <div class="text-right mb-3">
                                    
                                    <button type="button" class="btn btn-info ml-auto mb-auto f-btn" onclick="ShowTicketModel()">
                                        <i class="fas fa-plus"></i>&nbsp;Add ticket
                                    </button>
                                    <button type="button" id="ShowGeneralSettings" class="btn btn-outline-dark waves-effect f-btn" style="margin-right: 5px" >
                                        <i class="fa fa-eye" aria-hidden="true"></i>&nbsp;View
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <!-- <div class="row">
                                        <div class="col-md-12" style="text-align:right;">
                                            <select class="multiple-select  mb-2" name="tk_select" id="tk_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                                <option value="0">#</option>
                                                <option value="2">Status</option>
                                                <option value="3">Status</option>
                                                <option value="4">Ticket ID</option>
                                                <option value="5">Prioprity</option>
                                                <option value="6">Customer</option>
                                                <option value="7">Last Replier</option>
                                                <option value="8">Replies</option>
                                                <option value="9">Last Activity</option>
                                                <option value="10">Reply Due</option>
                                                <option value="11">Resolution Due</option>
                                                <option value="12">Assigned Tech</option>
                                                <option value="13">Department</option>
                                                <option value="14">Creation Date</option>
                                            </select>
                                        </div>
                                    </div> -->
    
                                    <table id="ticket-table-list" class="table w-100 table-striped table-bordered table-hover display ticket-table-list">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="text-center">
                                                        <input type="checkbox" name="select_all[]" id="select-all">
                                                    </div>
                                                </th>
                                                <th></th>
                                                        <th>Status</th>
                                                        <th class='custom'>Subject</th>
                                                        <th class='pr-ticket'>Ticket ID</th>
                                                        <th >Priority</th>
                                                        <th class='custom-cst'>Customer</th>
                                                        <th class='pr-replies custom-cst'>Last Replier</th>
                                                        <th>Replies</th>
                                                        <th class='pr-activity '>Last Activity</th>
                                                        <th class='pr-ticket'>Reply Due</th>
                                                        <th class='pr-due'>Resolution Due</th>
                                                        <th class='pr-tech custom-cst'>Assigned Tech</th>
                                                        <th class='custom-cst'>Department</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                                <div class="row" id="general-opt" style="display: none">
                                    <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-title">
                                            <h3>GENERAL OPTIONS</h3>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h5>Tickets to display per page</h5>
                                                    <small>Specify the number of tickets to display before breaking the view into multiple pages. Note: 
                                                        This value cannot exceed the default maximum your administrator has set for staff views.
                                                        Automatically refresh the ticket listing</small>
                                                </div>
                                                <div class="col-md-2">
                                                    <input type="number" id="" class="form-control" name="contact" placeholder="">
                                                </div>
                                                <hr>
                                                <div class="col-md-8">
                                                    <h5>Automatically refresh the ticket listing</h5>
                                                    <small>When this view is loaded, the ticket listing can be automatically refreshed. Useful for 
                                                        wallboard type uses.</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <select class="form-select" id="selectDefault">
                                                        <option selected="">Refresh evey 5 minutes</option>
                                                        <option value="1">Refresh every 10 minutes</option>
                                                        <option value="2">Refresh every 20 minutes</option>
                                                        <option value="3">Refresh every 30 minutes</option>
                                                    </select>
                                                </div>
                                                <hr>
                                                <div class="col-md-8">
                                                    <h5>Automatically set ticket owner to active staff user</h5>
                                                    <small>When replying or forwarding a ticket, the ticket owner will be automatically 
                                                        set to the active staff user if this view is in use.</small>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="demo-inline-spacing3">
                                                            <div class="form-check form-check-success">
                                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked />
                                                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                            </div>
                                                            <div class="form-check form-check-danger">
                                                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" />
                                                                <label class="form-check-label" for="inlineRadio2">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <div class="col-md-8">
                                                        <h5>Default ticket status when replying to a ticket</h5>
                                                    <small>Specify which status a ticket will be automatically set to when a staff user 
                                                        who is using this view replies to or forwards a ticket.</small>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <select class="form-select" id="selectDefault">
                                                            <option selected="">-- Unspecified --</option>
                                                            <option value="1">Refresh every 10 minutes</option>
                                                            <option value="2">Refresh every 20 minutes</option>
                                                            <option value="3">Refresh every 30 minutes</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-12 mt-2">
                                                        <button class="btn btn-success rounded float-right" id="gen-btn" type="submit"><strong><i data-feather='refresh-ccw'></i> Update</strong></button>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="user_docs_tab" role="tabpanel" aria-labelledby="pills-documents-tab">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                    <button type="submit" style="float:right" class="btn btn-success" onclick="ShowDocumentModel()"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp;Add
                                        Document </button>
                                </div>
                            </div>
                                <!--  -->
                                <div class="row">
                                <div class="table-responsive pt-3">
                                    <table id="user_docs_table" class="table table-striped table-bordered w-100 asset-table-list">
                                        <thead>
                                            <tr>
                                                <th>Sr</th>
                                                <th class="text-center">Image</th>
                                                <th>Name</th>
                                                <th>Description</th>
                                                <th class="text-center">Download</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            </div>
                        </div>
    
                        <div class="tab-pane fade" id="user_bucket_tab" role="tabpanel" aria-labelledby="pills-bucket-tab">
                            <div class="card-body">
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group mb-4">
                                                <label class="mr-sm-2" for="task_status">Status</label>
                                                <select class="select2 form-control"" id="task_status">
                                                    <option selected="">Choose...</option>
                                                    <option value="danger">Pending</option>
                                                    <option value="default">Work in progress</option>
                                                    <option value="success">Completed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">From Date</label>
                                                <input type="date" id="from" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">To Date</label>
                                                <input type="date" id="to" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3 mt-2">
                                            <div class="form-group">
                                                <button onclick="getAllTasksList()" type="button" class="btn btn-success"> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="tabular mt-3">
                                    <div class="table-responsive">
                                        <table id="staff_tasks_list" class="table table-striped table-bordered w-100">
                                            <thead>
                                                <tr>
                                                    <th>Sr</th>
                                                    <th>Task Id</th>
                                                    <th>Task Title</th>
                                                    <th>Project Name</th>
                                                    <th>Version</th>
                                                    <th>Task Duration</th>
                                                    <th>Created At</th>
                                                    <th>Created By</th>
                                                    <th>Worked Time</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php //dd($tasks) 
                                                ?>
                                                @foreach($tasks as $task)
                                                <tr>
                                                    <td>{{$loop->iteration}}</td>
                                                    <td><a href="{{asset('task_details')}}/{{$task->id}}">{{$task->id}}</a></td>
                                                    <td>
                                                        <a href="{{asset('task_details')}}/{{$task->id}}">{{$task->title}}</a> <br>
                                                        <?php 
                                                            if($task->task_status != "success"){
                                                                $today_date = date("Y-m-d");
                                                                $overdue = '<span class="badge bg-danger text-white">overdue</span>';
    
                                                                if($task->due_date < $today_date) {
                                                                    echo $overdue;
                                                                }else{
                                                                    echo ' ';
                                                                }
                                                            }
                                                        ?>
                                                    </td>
    
                                                    @if($task->taskProject == null)
                                                    <td>---</td>
                                                    @else
                                                    <td>{{$task->taskProject['name']}}</td>
                                                    @endif
    
                                                    <td>{{$task->version}}</td>
                                                    <td>{{$task->estimated_time}}</td>
                                                    <td>{{$task->created_at}}</td>
    
                                                    @if($task->taskCreator == null)
                                                    <td>---</td>
                                                    @else
                                                    <td>{{$task->taskCreator['name']}}</td>
                                                    @endif
    
                                                    <td><?php echo gmdate('H:i:s', $task->worked_time); ?></td>
                                                    <td> <span class="badge text-white {{$task->task_status == 'danger' ? 'bg-danger' : 'bg-warning'}} ">{{$task->task_status == "danger" ? "Pending" : "Work In Progress"}}</span> </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="loader_container" id="task_loader" style="display:none">
                                            <div class="loader"></div>
                                        </div>
                                    </div>
                                </div>
    
                            </div>
                        </div>
    
                        <div class="tab-pane fade show" id="user_notifi_permission" role="tabpanel" aria-labelledby="my-history-tab">
                            <hr>
                            <div class="card-body">
                                {{-- <div class="notification_div"></div> --}}
                                <div>
                                    @foreach ($departments as $obj)
                                    <div id="accordion" class="custom-accordion">
                                        <div class="card mb-0 card_shadow">
                                            <div class="" id="headingOne">
                                                <div class="demo-inline-spacing">
                                                    <h5 class="m-0">
                                                        <a class="custom-accordion-title d-flex align-items-center pt-2 pb-2 {{($obj['assignment'] == 1) ? '' : 'collapsed'}}" data-bs-toggle="collapse" aria-expanded="{{($obj['assignment'] == 1) ? 'true' : 'false'}}" aria-controls="collapseOne">
                                                        {{$obj['name']}}<span class="ml-auto"></span>
                                                        </a>
                                                    </h5>
                                                    <div class="">
                                                        
                                                            <div class="form-check form-check-success form-check-inline">
                                                                <input type="radio" onclick="openThisAccordin({{$obj['id']}})" id="customRadio1_{{$obj['id']}}" name="customRadio_{{$obj['id']}}" class="form-check-input" {{($obj['assignment'] == 1) ? 'checked' : ''}}>
                                                                <label class="form-check-label" for="customRadio1_{{$obj['id']}}">Yes</label>
                                                            </div>
                                                            <div class="form-check form-check-danger form-check-inline">
                                                                <input type="radio" onclick="closeThisAccordin({{$obj['id']}})" id="customRadio2_{{$obj['id']}}" name="customRadio_{{$obj['id']}}" class="form-check-input">
                                                                <label class="form-check-label" for="customRadio2_{{$obj['id']}}">No</label>
                                                            </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="collapseOne_{{$obj['id']}}" class="{{($obj['assignment'] == 1) ? 'show' : 'collapse'}}" aria-labelledby="headingOne" data-bs-parent="#accordion" style="">
                                                <div class="card-body">
                                                    @foreach ($obj['permissions'] as $j => $data)
                                                        @if (array_key_exists(0, $data) && array_key_exists(1, $data))
                                                            <div class="demo-inline-spacing1">
                                                                <div class="text-dark">{{$data[0]}} </div>
                                                                <span>
                                                                    <div class="form-check form-check-success form-check-inline">
                                                                        <input type="radio" onclick="saveNotificationPermission(1, {{$obj['id']}},'{{$j}}')" id="yes_{{$j}}_{{$obj['id']}}" name="perm_{{$j}}_{{$obj['id']}}" dep="{{$j}}" class="form-check-input yes_sub_check_{{$obj['id']}}" {{($data[1] == 1) ? 'checked': ''}}>
                                                                        <label class="form-check-label" for="yes_{{$j}}_{{$obj['id']}}">Yes</label>
                                                                    </div>
                                                                    <div class="form-check form-check-danger form-check-inline">
                                                                        <input type="radio" onclick="saveNotificationPermission(0, {{$obj['id']}},'{{$j}}')" id="no_{{$j}}_{{$obj['id']}}" name="perm_{{$j}}_{{$obj['id']}}" dep="{{$j}}" class="form-check-input no_sub_check_{{$obj['id']}}" {{($data[1] == 1) ? '': 'checked'}}>
                                                                        <label class="form-check-label" for="no_{{$j}}_{{$obj['id']}}">No</label>
                                                                    </div>
                                                                </span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
    
                                {{-- <button id="per_sve_btn" class="btn mb-3 btn-sm btn-success float-right rounded ml-2" onclick="savePermission()"><i class="fas fa-check-circle"></i> Save</button>
                                <button  id="per_process" style="display:none" class="btn mb-3 btn-sm btn-success rounded float-right ml-2" type="button" disabled><i class="fas fa-circle-notch fa-spin"></i> Processing</button>
    
                                <div class="loader_container" id="permission_loader" style="display:none">
                                    <div class="loader"></div>
                                </div> --}}
                            </div>
                        </div>
    
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>
    
        <!-- add new certificate model -->
        <div id="add-new-certificate" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
            <div class="modal-dialog modal-lg" style="width:50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="blue bigger">Add Certificate</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="timeline-1" class="" style="background: white;">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="widget-box widget-color-dark">
                                                <div class="widget-body">
                                                    <div class="widget-main padding-8">
    
                                                        <form class="row road-map-form" id="save-certification" enctype="multipart/form-data" action="{{asset('add-new-certification')}}" method="post">
    
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    
                                                                <input name="id" style="display: none;" class="form-control" type="text" value="" readonly="readonly">
                                                                <input type="hidden" name="user_id" id="user_id" value="{{$profile->id}}">
                                                                <div class="form-horizontal">
                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-4">Name
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input name="name" class="form-control" type="text" value="" placeholder="" />
    
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label col-sm-6">Category Name
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input name="category_name" class="form-control" type="text" value="" placeholder="" />
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label col-sm-12">Details
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <textarea style="height: 100px;" name="details" class="form-control" placeholder=""></textarea>
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label col-sm-12">Attachment
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <div class="input-group mb-3 mt-1" style="border:1px solid #848484 !important; border-radius:4px;">
    
                                                                                <div class="custom-file">
                                                                                    <input class="custom-file-input form-control" name="image" id="cer_image" accept="image/*" type="file">
                                                                                </div>
                                                                            </div>
                                                                            <!-- <input type="file" name="image" id="image" accept="image/*" class="custom-file-input form-control"> -->
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group">
                                                                        <div class="col-sm-offset-4 col-sm-12">
                                                                            <button type="submit" class="btn btn-success pull-right" style="float: right;">Add</button>
                                                                        </div>
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
                    </div>
                </div>
            </div>
        </div>
    
        <!--  Modal content ticket start -->
        <div class="modal fade" id="ticket" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">Add Ticket</h4>
                        <button type="button" class="btn-close " data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal mt-1" id="save_tickets" action="{{asset('save-tickets')}}" method="post">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
    
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <label class="control-label col-sm-12">Subject<span style="color:red !important;">*</span></label><span id="select-subject" style="display: none; color: red !important;">Subject cannot be Empty </span>
                                                    <input class="form-control" type="text" id="subject" name="subject">
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Department<span style="color:red !important;">*</span></label><span id="select-department" style="display :none; color:red !important;;">Please Select Department</span>
                                                    <select class="select2 form-control custom-select" type="search" id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($departments as $department)
                                                        <option value="{{$department['id']}}">{{$department['name']}}</option>
                                                        @endforeach
    
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row mt-1">
    
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Status<span style="color:red !important;">*</span></label><span id="select-status" style="display:none; color:red !important;">Please Select Status</span>
                                                    <select class="select2 form-control " id="status" name="status" style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($statuses as $status)
                                                        <option value="{{$status->id}}">{{$status->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
    
    
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Priority<span style="color:red !important;">*</span></label><span id="select-priority" style="display :none; color:red !important;">Please Select Priority</span>
                                                    <select class="select2 form-control " id="priority" name="priority" style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($priorities as $priority)
                                                        <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
    
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Assign Tech<span style="color:red !important;">*</span></label><span id="select-assign" style="display :none; color:red !important;">Please Select Tech</span>
                                                    <select class="select2 form-control " id="assigned_to" name="assigned_to" style="width: 100%; height:36px;">
                                                        <option value="">Select</option>
                                                        @foreach($users as $user)
                                                        <option value="{{$user->id}}">{{$user->name}}</option>
                                                        @endforeach
    
    
                                                    </select>
                                                </div>
    
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row mt-1">
    
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Type
                                                        <span style="color:red !important;">*</span></label><span id="select-type" style="display :none; color:red !important;">Please Select Type</span>
                                                    <select class="select2 form-control" id="type" name="type" style="width: 100%; height:36px;">
                                                        <option value="">Select</option>
                                                        @foreach($types as $type)
                                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Customer Select<span style="color:red !important;">*</span></label><span id="select-customer" style="display :none; color:red !important;">Please Select Customer</span>
                                                    <select class="select2 form-control custom-select" id="customer_id" name="customer_id" style="width: 100%; height:36px;">
    
                                                        <option value="">Select</option>
                                                        @foreach($customers as $customer)
                                                        <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                                                        @endforeach
    
    
                                                    </select>
                                                </div>
    
                                                <div class="col-sm-4 checkbox checkbox-info mti-2">
                                                    <input id="new-form" value="1" type="checkbox" name="newcustomer">
                                                    <label class="mb-0" for="checkbox4">New Customer</label>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="form-group">
                                            <div class="row ">
    
                                                <div class="col-sm-12" id="new-customer" style="display:none;">
                                                    <div class="row">
                                                        <label for="example-search-input" class="col-sm-3 col-form-label">First
                                                            Name :<span style="color:red !important;">*</span></label><span id="save-firstname" style="display :none; color:red !important;position: relative;top: 10px;">First
                                                            name cannot be Empty </span>
                                                        <div class="col-sm-12">
                                                            <input class="form-control" type="text" id="first_name" name="first_name">
                                                            <input class="form-control" type="text" id="ticket_id" name="ticket_id" hidden>
                                                        </div>
                                                    </div>
    
                                                    <div class="row mt-1">
                                                        <label for="example-search-input" class="col-sm-3 col-form-label">Last
                                                            Name :<span style="color:red !important;">*</span></label><span id="save-lastname" style="display :none; color:red !important;position: relative;top: 10px;">Last
                                                            name cannot be Empty </span>
                                                        <div class="col-sm-12">
                                                            <input class="form-control" type="text" id="last_name" name="last_name">
                                                        </div>
                                                    </div>
    
                                                    <div class="row mt-1">
                                                        <label for="example-search-input" class="col-sm-3 col-form-label">Phone
                                                            Number :<span style="color:red !important;">*</span></label><span id="save-number" style="display :none; color:red !important;position: relative;top: 10px;">Phone
                                                            number cannot be Empty </span>
                                                        <div class="col-sm-12">
                                                            <input class="form-control" type="text" name="phone">
                                                        </div>
                                                    </div>
    
                                                    <div class="row mt-1">
                                                        <label for="example-search-input" class="col-sm-3 col-form-label">E-mail1
                                                            :<span style="color:red !important;">*</span></label><span id="save-email" style="display :none; color:red !important; position: relative;top: 10px;">Email
                                                            cannot be Empty </span>
                                                        <div class="col-sm-12">
                                                            <input class="form-control" type="text" name="email">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
    
                                        <div class="form-group">
                                            <div class="row mt-1">
                                                <!--<div class="col-sm-4">-->
                                                <!--    <input type="file" class="file-upload form-control-file" id="exampleInputFile">-->
                                                <!--                        </div>-->
    
                                                <div class="col-sm-12">
                                                    <label class="control-label col-sm-12">Problem Details<span style="color:red !important;">*</span></label><span id="pro-details" style="display :none; color:red !important;">Please provide details</span>
                                                    <textarea class="form-control" rows="3" id="ticket_detail" name="ticket_detail"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="text-right mt-1">
                                        <button type="submit" class="btn waves-effect waves-light btn-success float-right">Save</button>
                                    </div>
    
    
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal content ticket end -->
    
    
        <!-- add user document modal -->
        <div id="add-new-document" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static">
            <div class="modal-dialog modal-lg" style="width:50%;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="blue bigger" style="color:#009efb;">Add New Document</h3>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="timeline-1" class="" style="background: white;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="widget-box widget-color-dark">
                                                <div class="widget-body">
                                                    <div class="widget-main padding-8">
    
                                                        <form class="row road-map-form" id="save-documents" enctype="multipart/form-data" action="{{asset('add-new-documents')}}" method="post">
                                                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    
                                                                <input name="id" style="display: none;" class="form-control" type="text" value="" readonly="readonly">
    
                                                                <input type="hidden" name="user_id" value="{{$profile->id}}">
    
                                                                <div class="form-horizontal">
    
    
                                                                    <div class="form-group">
                                                                        <label class="control-label col-sm-4">Name
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input name="name" class="form-control" type="text" value="" placeholder="" />
    
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label col-sm-6">Category Name
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input name="category_name" class="form-control" type="text" value="" placeholder="" />
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label col-sm-12">Details
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <textarea style="height: 100px;" name="details" class="form-control" placeholder=""></textarea>
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-1">
                                                                        <label class="control-label col-sm-12">Attachment
                                                                            :</label>
                                                                        <div class="col-sm-12">
                                                                            <input type="file" name="image" id="image" accept="image/*" class="form-control">
                                                                        </div>
                                                                    </div>
    
                                                                    <div class="form-group mt-2">
                                                                        <div class="col-sm-offset-4 col-sm-12">
                                                                            <button type="submit" class="btn btn-success pull-right" style="float: right;">Add</button>
                                                                        </div>
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
                    </div>
                </div>
            </div>
        </div>
    
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="editPicModal" tabindex="-1" aria-labelledby="editPicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPicModalLabel">Profile Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="prof-img">
                        @php
                            $path = Session::get('is_live') == 1 ? 'public/' : '/';
                        @endphp
                        @if($profile->profile_pic != null)
                            @if(is_file( getcwd() .'/'. $profile->profile_pic ))
                            <img src="{{ request()->root() .'/'. $profile->profile_pic }}" class="modalImg rounded-circle"
                                width="100" height="100" id="profile-user-img" />
                            @else
                            <img src="{{asset( $path . 'default_imgs/customer.png')}}" class="modalImg rounded-circle" width="100" height="100"
                                id="profile-user-img" />
                            @endif
                        @else
                        <img src="{{asset( $path . 'default_imgs/customer.png')}}" class="modalImg rounded-circle" width="100" height="100"
                            id="profile-user-img" />
                        @endif
                        <img src="{{asset( $path . 'default_imgs/customer.png')}}" id="hung22" alt="" class=" rounded-circle" width="100" height="100"  style="display:none;">
                    </div>
                    <form class="mt-4" id="upload_user_img">
                        <div class="input">
                            <div class="custom-file w-100">
                                <input type="hidden" name="staff_id" id="staff_id" value="{{$profile->id}}">
                                <input type="file" name="profile_img" class="form-control" onchange="loadFile(event)" id="customFilePP">
                                
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-success rounded float-right"> <i class="fas fa-check-circle"></i> Save changes</button>
                        </div>
    
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- leave Modal -->
    <div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="editPicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="leave-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="leaveForm" method="POST" action="{{url('add-leaves')}}">
                        <input type="hidden" id="requested_by" value="{{$profile->id}}">
                        <input type="hidden" id="leave_id">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Start Date</label>
                                <input type="date" class="form-control" id="leave_start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">End Date</label>
                                <input type="date" class="form-control" id="leave_end_date" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <label for="">Reason</label>
                                <textarea id="leave_reason" cols="30" rows="5" class="form-control" required></textarea>
                            </div>
                        </div>
                        <button class="btn btn-success rounded mt-2 float-right f-btn" id="leave-btn"> <i class="fas fa-check-circle"></i> Save</button>
                        <button type="button" style="display:none" disabled id="leave-process" class="btn btn-success rounded float-right mt-2 mr-2 f-btn"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- schdule Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="editPicModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="schedule-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="schefuleForm" method="POST" action="{{url('add-staff-shift')}}">
                        <input type="hidden" id="staff_id" value="{{$profile->id}}">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Start Time</label>
                                <input type="time" class="form-control" id="schedule_start_time" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">End Time</label>
                                <input type="time" class="form-control" id="schedule_end_time" required>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="">Start Date</label>
                                <input type="date" class="form-control" id="schedule_start_date" required>
                            </div>
                            <div class="col-md-6">
                                <label for="">End Date</label>
                                <input type="date" class="form-control" id="schedule_end_date" required>
                            </div>
                        </div>
                        <button class="btn btn-success  rounded mt-2 float-right f-btn" id="schedule-btn"> <i class="fas fa-check-circle"></i> Save</button>
                        <button type="button" style="display:none" disabled id="schedule-process" class="btn btn-success rounded float-right mt-2 mr-2 f-btn"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- schdule Modal -->
    <div class="modal fade" id="workHoursModal" tabindex="-1" aria-labelledby="workHoursModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Worked Hours</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form id="workHoursForm" method="POST" action="{{url('update-work-hours')}}">
                        <input type="hidden" id="attendance_id" name="id">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="worked_hours_value">Worked Hours</label>
                                <input type="text" class="form-control" id="worked_hours_value" name="worked_hours_value" required>
                            </div>
                        </div>
                        <button class="btn btn-success rounded mt-2 float-right"><i class="fas fa-check-circle"></i> Save</button>
                        <button type="button" style="display:none" disabled id="schedule-process" class="btn btn-success rounded float-right mt-2 mr-2"> <i class="fas fa-circle-notch fa-spin"></i> Processing </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
@section('scripts')

<link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/countdown/countdown.css')}}" />
<script type="text/javascript" src="{{asset('assets/extra-libs/countdown/countdown.js')}}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script type="text/javascript" src="{{asset('assets/extra-libs/pickr/pickr.min.js')}}"></script>
<script src="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<script src="{{asset('assets/dist/js/pages/calendar/cal-init.js').'?ver='.rand()}}"></script>

{{-- Page JS --}}
@include('js_files.system_manager.staff_management.staff_profileJs')

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>

<script>
        var url  = window.location.href;
        if(url.includes('#staff-schedule')) {
            $("#my-schedule-tab").click();
        }
        console.log(js_origin , "js_origin");

        function loadFile(event) {
            $('.modalImg').hide();
            $("#hung22").show()
            var output = document.getElementById('hung22');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
            }
        };
</script>


@include('js_files.ticket_cmmnJs')
@include('js_files.system_manager.staff_management.user_profileJs')



@endsection