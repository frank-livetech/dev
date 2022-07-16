@extends('layouts.master-layout-new')
@section('title', 'Customer Profile | ' . $customer->first_name . ' ' . $customer->last_name)
@section('body')
<style>
    table.dataTable th {
            padding: 0.2rem 1.5rem !important;
        }
        table.dataTable .custom {
            padding-right: 230px !important;
        }

        table.dataTable .pr-ticket {
            min-width: 69px !important;
        }

        .pr-replies {
            min-width: 95px !important;
        }

        .pr-due {
            min-width: 125px !important;
        }

        .pr-activity {
            min-width: 97px !important;
            padding-right: 19px !important;
        }

        .pr-tech {
            min-width: 109px !important;
        }

        table.dataTable .custom-cst {
            padding-right: 37px !important;
        }

        table.dataTable th {
            padding: 0.2rem 1.5rem;
        }

        table.dataTable td {
            padding: 7px !important;
            font-size: 12px;
        }
    .nav-pills .nav-link{
        padding: 0.786rem .5rem !important;
    }
    .float-right{
        float: right
    }
</style>
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-fluid p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-12 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">{{$customer->first_name}} {{$customer->last_name}}</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('customer.lookup')}}">Customer Lookup</a>
                                </li>
                                <li class="breadcrumb-item active"><a href="javascript:location.reload()">{{$customer->first_name}} {{$customer->last_name}}</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($date_format)
        <input type="hidden" id="system_date_format" value="{{$date_format}}">
    @else
        <input type="hidden" id="system_date_format" value="DD-MM-YYYY">
    @endif
    <input type="hidden" id="customer_id" value="{{$customer->id}}">
    <input type="hidden" id="curr_user_name" value="{{Auth::user()->name}}">
    <div class="content-body">
        @if($errors->any())

        <div class="alert alert-dismissable alert-danger">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
            <p><strong>Opps Something went wrong</strong></p>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>

        </div>

        @endif

        @if(session('success'))
            <div class="alert alert-success">{{session('success')}}</div>
        @endif
        <section class="app-user-view-account">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                     <!-- User Pills -->
                     <ul class="nav nav-pills mb-2">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-setting-tab" data-bs-toggle="tab" href="#previous-month" aria-controls="profile" role="tab" aria-selected="false">
                                <i class="fal fa-user font-medium-3 me-50"></i>
                                <span class="fw-bold">Profile</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-timeline-tab" data-bs-toggle="pill" href="#tab-history" role="tab"
                                aria-controls="pills-timeline" aria-selected="true">
                                <i class="fal fa-history font-medium-3 me-50"></i>

                                <span class="me-50">History</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link loadTickets" id="pills-tickets-tab" data-bs-toggle="pill" href="#tickets" role="tab"
                            aria-controls="pills-tickets" aria-selected="false">
                            <i class="fal fa-ticket font-medium-3 me-50"></i>

                            <span class="me-50">Tickets</span>
                            @if($ticketsCount != 0)
                            <span id="tickets_count" class="badge bg-dark text-white fw-bold"> {{$ticketsCount}} </span>
                            @endif
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="orders-profile-tab" data-bs-toggle="pill" href="#orders" role="tab"
                                aria-controls="pills-profile" aria-selected="false">
                                <i class="fal fa-dollar-sign font-medium-3 me-50"></i>
                                <span class="fw-bold">Billing</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <li class="nav-item">
                                <a class="nav-link loadAssets" id="pills-assets-tab" data-bs-toggle="pill" href="#assets" role="tab"
                                    aria-controls="pills-assets" aria-selected="false">
                                    <i class="fal fa-list font-medium-3 me-50"></i>
                                    <span class="fw-bold">Assets</span>
                                </a>
                            </li>

                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="payment-profile-tab" data-bs-toggle="pill" href="#payment" role="tab"
                                aria-controls="pills-profile" aria-selected="false">
                                <i class="fal fa-cash-register font-medium-3 me-50"></i>
                                <span class="fw-bold">Payments</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" onclick="getNotes()" id="notes-profile-tab" data-bs-toggle="pill" href="#ticket_notes" role="tab" aria-controls="pills-profile" aria-selected="false">
                                <i class="fal fa-sticky-note font-medium-3 me-50"></i>
                                <span class="me-50">Notes</span>
                                @if($notesCount != 0)
                                <span id="notes_count" class="badge bg-dark text-light"> {{$notesCount}} </span>
                                @endif
                            </a>
                        </li>
                        {{-- <li class="nav-item">
                            <a class="nav-link" id="domain-profile-tab" data-bs-toggle="pill" href="#ticket_domain" role="tab" aria-controls="pills-profile" aria-selected="false">
                                <i data-feather="link" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Domain</span></a>
                            </a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" id="pills-timeline-tab" data-bs-toggle="pill" href="#current-month" role="tab"
                                aria-controls="pills-timeline" aria-selected="true">
                                <i class="fal fa-bell font-medium-3 me-50"></i>
                                <span class="me-50">Notifications</span>
                            </a>
                        </li>
                    </ul>
                    <!--/ User Pills -->
                </div>
                <!-- User Sidebar -->
                <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0" id="card-1">
                    <!-- User Card -->
                    <div class="card border-primary">
                        @php
                            $path = Session::get('is_live') == 1 ? 'public/' : '/';
                        @endphp
                        <div class="card-body">
                            <div class="user-avatar-section">
                                <div class="d-flex align-items-center flex-column">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editPicModal">

                                        @if($customer->avatar_url != null)


                                            @if(is_file( getcwd() .'/'. $customer->avatar_url))
                                                <img class="img-fluid rounded mt-3 mb-2" src="{{ request()->root() .'/'. $customer->avatar_url }}" height="110" width="110" alt="User avatar" id="customer_curr_img"/>

                                            @else
                                                <img class="img-fluid rounded mt-3 mb-2" src="{{asset( $path . 'default_imgs/customer.png')}}" height="110" width="110" alt="User avatar" id="customer_curr_img"/>

                                            @endif
                                        @else
                                            <img class="img-fluid rounded mt-3 mb-2" src="{{asset( $path . 'default_imgs/customer.png')}}" height="110" width="110" alt="User avatar" id="customer_curr_img"/>

                                        @endif
                                        </a>

                                    <div class="user-info text-center">
                                        <h4>{{$customer->first_name}} {{$customer->last_name}}</h4>
                                        @if($customer->created_at != null)

                                            @php
                                                $date = new \DateTime($customer->created_at);
                                                $date->setTimezone(new \DateTimeZone( timeZone() ));
                                                $created_at =  $date->format(system_date_format() .' h:i a');
                                            @endphp
                                            <p class="badge bg-light-secondary"> Client Since: {{$created_at}} </p>

                                    @endif

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-around my-2 pt-75">
                                <div class="d-flex align-items-start me-2">
                                    <span class="badge bg-light-primary p-75 rounded">
                                        <i data-feather="check" class="font-medium-2"></i>
                                    </span>
                                    <div class="ms-75">
                                        <h4 class="mb-0">1.23k</h4>
                                        <small>Tasks Done</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-start">
                                    <span class="badge bg-light-primary p-75 rounded">
                                        <i data-feather="briefcase" class="font-medium-2"></i>
                                    </span>
                                    <div class="ms-75">
                                        <h4 class="mb-0">568</h4>
                                        <small>Projects Done</small>
                                    </div>
                                </div>
                            </div>
                            <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                            <div class="info-container">
                                <ul class="list-unstyled">
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Username:</span>
                                        <span>{{$customer->first_name}} {{$customer->last_name}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Email:</span>
                                        <span>{{ $customer->email }}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Status:</span>
                                        <span class="badge bg-light-success">Active</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Role:</span>
                                        <span>Customer</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">SSN#:</span>
                                        <span>555-55-555</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Contact:</span>
                                        <span>{{$customer->phone}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Language:</span>
                                        <span>English</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Country:</span>
                                        <span>{{$customer->country}}</span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-center pt-2">
                                    <a href="{{url('logged_in_as_customer').'/'.$customer->email}}" target="__blank" class="btn btn-primary me-1 mt-1 waves-effect waves-float waves-light">Login as user</a>
                                    <a href="javascript:;" class="btn btn-primary mt-1 me-1 text-center " style="background-color: #5e50ee !important">Create Account</a>
                                </div>
                                <div class="d-flex justify-content-center">

                                    <a href="javascript:;" class="btn btn-outline-danger suspend-user mt-1 me-1 text-center ">Suspended</a>
                                    <a href="javascript:;" class="btn btn-outline-danger suspend-user mt-1 text-center "><i class="fal fa-trash me-50"></i>Delete&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /User Card -->
                    <!-- Plan Card -->
                    {{-- <div class="card border-primary">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="badge bg-light-primary">Standard</span>
                                <div class="d-flex justify-content-center">
                                    <sup class="h5 pricing-currency text-primary mt-1 mb-0">$</sup>
                                    <span class="fw-bolder display-5 mb-0 text-primary">99</span>
                                    <sub class="pricing-duration font-small-4 ms-25 mt-auto mb-2">/month</sub>
                                </div>
                            </div>
                            <ul class="ps-1 mb-2">
                                <li class="mb-50">10 Users</li>
                                <li class="mb-50">Up to 10 GB storage</li>
                                <li>Basic Support</li>
                            </ul>
                            <div class="d-flex justify-content-between align-items-center fw-bolder mb-50">
                                <span>Days</span>
                                <span>4 of 30 Days</span>
                            </div>
                            <div class="progress mb-50" style="height: 8px">
                                <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="65" aria-valuemax="100" aria-valuemin="80"></div>
                            </div>
                            <span>4 days remaining</span>
                            <div class="d-grid w-100 mt-2">
                                <button class="btn btn-primary" data-bs-target="#upgradePlanModal" data-bs-toggle="modal">
                                    Upgrade Plan
                                </button>
                            </div>
                        </div>
                    </div> --}}
                    <!-- /Plan Card -->
                </div>
                <!--/ User Sidebar -->

                <!-- User Content -->
                <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1" id="ticket-full">

                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade" id="user-detail" role="tabpanel"
                            aria-labelledby="pills-user-detail">

                            <div class="card">
                                <div class="card-body">
                                    No Data Found.
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade show" id="current-month" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">

                            <div class="card">
                                <div class="card-body">
                                    No Data Found.
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="tab-history" role="tabpanel"
                            aria-labelledby="pills-timeline-tab">

                            <div class="card">
                                <h4 class="card-header">User Activity Timeline</h4>
                                <div class="card-body pt-1">
                                    <ul class="timeline ms-50">
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                    <h6>User login</h6>
                                                    <span class="timeline-event-time me-1">12 min ago</span>
                                                </div>
                                                <p>User login at 2:12pm</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-warning timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                    <h6>Meeting with john</h6>
                                                    <span class="timeline-event-time me-1">45 min ago</span>
                                                </div>
                                                <p>React Project meeting with john @10:15am</p>
                                                <div class="d-flex flex-row align-items-center mb-50">
                                                    <div class="avatar me-50">
                                                        <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="Avatar" width="38" height="38">
                                                    </div>
                                                    <div class="user-info">
                                                        <h6 class="mb-0">Leona Watkins (Client)</h6>
                                                        <p class="mb-0">CEO of pixinvent</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-info timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                    <h6>Create a new react project for client</h6>
                                                    <span class="timeline-event-time me-1">2 day ago</span>
                                                </div>
                                                <p>Add files to new design folder</p>
                                            </div>
                                        </li>
                                        <li class="timeline-item">
                                            <span class="timeline-point timeline-point-danger timeline-point-indicator"></span>
                                            <div class="timeline-event">
                                                <div class="d-flex justify-content-between flex-sm-row flex-column mb-sm-0 mb-1">
                                                    <h6>Create Invoices for client</h6>
                                                    <span class="timeline-event-time me-1">12 min ago</span>
                                                </div>
                                                <p class="mb-0">Create new Invoices and send to Leona Watkins</p>
                                                <div class="d-flex flex-row align-items-center mt-50">
                                                    <img class="me-1" src="../../../app-assets/images/icons/pdf.png" alt="data.json" height="25">
                                                    <h6 class="mb-0">Invoices.pdf</h6>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="orders" role="tabpanel" aria-labelledby="orders-profile-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row ">
                                        <div class="col-md-12">
                                    <div class="d-flex justify-content-between pl-3 pr-3 mt-1">
                                        <h2 class="lead font-weight-bold">01. Orders</h2>

                                        @if($wp_value == 1)
                                            <button class="float-right btn-sm rounded btn btn-info mr-3"><i class="fas fa-sync"></i> Sync WP Orders  </button>
                                        @endif
                                    </div>
                                </div>
                                    </div>
                                    <div class="table-responsive mt-1">
                                        <div id="zero_config_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">

                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="customer_order_table"
                                                        class="table table-striped table-bordered w-100">
                                                        <thead>
                                                            <tr>
                                                                <th>Select</th>
                                                                <th>Order ID</th>
                                                                <th>Customer Name</th>
                                                                <th>Order Status</th>
                                                                <th>Order Date</th>
                                                                <th style="width:200px !important;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                    <div class="loader_container">
                                                        <div class="loader"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between p-3 ">
                                        <h2 class="lead font-weight-bold">02. Subscriptions</h2>
                                    </div>

                                    <div class="table-responsive">
                                        <div id="zero_config_wrapper"
                                            class="dataTables_wrapper container-fluid dt-bootstrap4">

                                            <div class="row ">
                                                <div class="col-sm-12">
                                                    <table id="customer_subscription" class="table table-striped table-bordered w-100">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>ID</th>
                                                                <th>Status</th>
                                                                <th>Currency</th>
                                                                <th>Discount Tax</th>
                                                                <th>Shipping Total</th>
                                                                <th>Shipping Tax</th>
                                                                <th>Total</th>
                                                                <th>Payment Method</th>
                                                                <th>Payment Method Title</th>
                                                                <th>Customer Note</th>
                                                                <th>Created Via</th>
                                                                <th>Billing Period</th>
                                                                <th>Start Date</th>
                                                                <th>Trial End Date</th>
                                                                <th>Next Payment Date</th>
                                                                <th>End Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($subscriptions as $sub)
                                                            <tr>
                                                                <td><a href="#detailsCard">{{$sub->woo_id}}</a>
                                                                </td>
                                                                <td>{{$sub->status}}</td>
                                                                <td>{{$sub->currency}}</td>
                                                                <td>{{$sub->discount_tax}}</td>
                                                                <td>{{$sub->shipping_total}}</td>
                                                                <td>{{$sub->shipping_tax}}</td>
                                                                <td>{{$sub->total}}</td>
                                                                <td>{{$sub->total_tax}}</td>
                                                                <td>{{$sub->payment_method}}</td>
                                                                <td>{{$sub->payment_method_title}}</td>
                                                                <td>{{$sub->created_via}}</td>
                                                                <td>{{$sub->customer_note}}</td>
                                                                <td>{{$sub->start_date}}</td>
                                                                <td>{{$sub->trial_end_date}}</td>
                                                                <td>{{$sub->next_payment_date}}</td>
                                                                <td>{{$sub->end_date}}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>

                        <!-- <div class="tab-pane fade" id="subscription" role="tabpanel"
                            aria-labelledby="subscription-profile-tab">
                            <hr>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="table-responsive">
                                                    <div id="zero_config_wrapper"
                                                        class="dataTables_wrapper container-fluid dt-bootstrap4">

                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <table id="customer_subscription"
                                                                    class="table table-striped table-bordered no-wrap w-100 "
                                                                    role="grid" aria-describedby="zero_config_info">
                                                                    <thead>
                                                                        <tr role="row">
                                                                            <th>ID</th>
                                                                            <th>Status</th>
                                                                            <th>Currency</th>
                                                                            <th>Discount Tax</th>
                                                                            <th>Shipping Total</th>
                                                                            <th>Shipping Tax</th>
                                                                            <th>Total</th>
                                                                            <th>Payment Method</th>
                                                                            <th>Payment Method Title</th>
                                                                            <th>Customer Note</th>
                                                                            <th>Created Via</th>
                                                                            <th>Billing Period</th>
                                                                            <th>Start Date</th>
                                                                            <th>Trial End Date</th>
                                                                            <th>Next Payment Date</th>
                                                                            <th>End Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($subscriptions as $sub)
                                                                        <tr>
                                                                            <td><a href="#detailsCard">{{$sub->woo_id}}</a>
                                                                            </td>
                                                                            <td>{{$sub->status}}</td>
                                                                            <td>{{$sub->currency}}</td>
                                                                            <td>{{$sub->discount_tax}}</td>
                                                                            <td>{{$sub->shipping_total}}</td>
                                                                            <td>{{$sub->shipping_tax}}</td>
                                                                            <td>{{$sub->total}}</td>
                                                                            <td>{{$sub->total_tax}}</td>
                                                                            <td>{{$sub->payment_method}}</td>
                                                                            <td>{{$sub->payment_method_title}}</td>
                                                                            <td>{{$sub->created_via}}</td>
                                                                            <td>{{$sub->customer_note}}</td>
                                                                            <td>{{$sub->start_date}}</td>
                                                                            <td>{{$sub->trial_end_date}}</td>
                                                                            <td>{{$sub->next_payment_date}}</td>
                                                                            <td>{{$sub->end_date}}</td>
                                                                        </tr>
                                                                        @endforeach
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
                        </div> -->

                        <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-profile-tab">

                            <!-- <hr>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">

                                        <button type="button" class="btn btn-info mr-auto" data-bs-toggle="modal"
                                            data-target="#Add-new-card" style="float:right;"><i
                                                class="mdi mdi-plus-circle"></i>&nbsp;Add New Card</button>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">

                                        <div class="card" style="border:1px solid black">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <a><i class="fas fa-trash" style="float:right;"></i></a>
                                                        <a><i class="fas fa-pencil-alt"
                                                                style="float:right;padding-right: 5px;"></i></a>
                                                        <div id="default-star-rating" style="cursor: pointer;">
                                                            <img alt="1" src="../assets/images/rating/star-off.png"
                                                                title="Rate"
                                                                style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                                            <input name="score" type="hidden">

                                                        </div>

                                                    </div>
                                                </div>
                                                <h1 class="mt-0"><i class="fab fa-cc-visa text-info"></i></h1>
                                                <h3>**** **** **** 2150</h3>
                                                <span class="pull-right">Exp date: 10/16</span>
                                                <span class="font-500">Johnathan Doe</span>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <hr>

                            <div class="row col-md-12">

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">ADD New Credit Card</h4>
                                        </div>
                                        <div class="card-body">
                                            <form id="CardForm" class="CardForm" >
                                                <div class="row">
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="First Name" name="fname" id="fname" value="{{$customer->first_name}}" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Last Name" name="lname" id="lname" value="{{$customer->last_name}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Street Address" name="address1" id="address1" value="{{$customer->address}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="City" name="city" id="city" value="{{$customer->cust_city}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            @if ($google_key == 1)
                                                                <input type="text" class="form-control" placeholder="State" name="state" id="state" value="{{$customer->cust_state}}">
                                                            @else
                                                                <select class="select2 form-control" id="state" name="state" style="width: 100%; height:36px;"></select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Zip code" name="zip" id="zip" value="{{$customer->cust_zip}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            @if ($google_key == 1)
                                                                <input type="text" class="form-control" placeholder="Zip code" name="country" id="country" value="{{$customer->cust_zip}}">
                                                            @else
                                                                <select class="form-control" name="country" id="country" onchange="listStates(this.value, 'state', 'cmp_state')">
                                                                    <option value="">Select Country</option>

                                                                    @foreach ($countries as $item)
                                                                        @if (!empty($customer->country))
                                                                            <option value="{{$item->name}}" {{$customer->country == $item->name ? "selected" : ''}}>{{$item->name}}</option>
                                                                        @else
                                                                            <option value="{{$item->name}}" {{'US' == $item->short_name ? "selected" : ''}}>{{$item->name}}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="payment_token" id="payment_token" value="0">
                                                    <input type="hidden" name="card_type" id="card_type">
                                                    <input type="hidden" name="exp" id="exp">
                                                    <input type="hidden" name="cardlastDigits" id="cardlastDigits">


                                                    <div class="col-md-12 mt-1">
                                                        <input type="submit" id="payButton" value="Add Card" class="btn btn-success float-btn">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div id="paymentTokenInfo"></div>
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">Default Billing Address</h4>
                                        </div>
                                        <div class="card-body">
                                            <form id="" class="" >
                                                <div class="row">
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="First Name" name="" id="" value="" autofocus>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Last Name" name="" id="" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Company Name" name="" id="" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Street Address" name="payment_billing_address" id="payment_billing_address" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-1">
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" placeholder="Apartment Address" name="payment_billing_aprt_address" id="payment_billing_aprt_address" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        {{-- <label for=""> CIty </label> --}}
                                                        <input type="text" class=" form-control" placeholder="City" id="payment_billing_city" name="payment_billing_city"  style="width: 100%; height:36px;">
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            {{-- <label for="">State</label> --}}
                                                            @if($google_key == 1)
                                                                <input type="text" class=" form-control" placeholder="State" id="payment_billing_state" name="payment_billing_state" style="width: 100%; height:36px;">
                                                            @else
                                                                <select class="select2 form-control" id="payment_billing_state" name="payment_billing_state" style="width: 100%; height:36px;"></select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            {{-- <label for="">Zip Code</label> --}}
                                                            <input type="text" class="form-control" placeholder="Zip code" name="payment_billing_zipcode"  id="payment_billing_zipcode" value="">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mt-1">
                                                        <div class="form-group">
                                                            {{-- <label for="">Country</label> --}}
                                                            @if($google_key == 1)
                                                                <input type="text" class="form-control" placeholder="Country" id="payment_billing_country" name="payment_billing_country" style="width: 100%; height:36px;">
                                                            @else
                                                                <select class="select2 form-control" id="payment_billing_country" name="payment_billing_country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'payment_billing_state', '')">
                                                                    <option value="">Select Country</option>
                                                                    @foreach ($countries as $cty)
                                                                        <option value="{{$cty->name}}" {{$cty->short_name == 'US' ? 'selected' : ''}}>{{$cty->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-right mt-1">
                                                    <button class="btn btn-success float-btn">Save Address</button>

                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">Accepted Payment Methods</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4 mt-1">
                                                    <p>Credit Cards</p>
                                                </div>
                                                <div class="col-md-8 crd_icons mt-1">
                                                    <img src="https://secure.merchantonegateway.com/shared/images/brand-visa.png" alt="">
                                                    <img src="https://secure.merchantonegateway.com/shared/images/brand-mastercard.png" alt="">
                                                    <img src="https://secure.merchantonegateway.com/shared/images/brand-amex.png" alt="">
                                                    <img src="https://secure.merchantonegateway.com/shared/images/brand-discover.png" alt="">
                                                    <img src="https://secure.merchantonegateway.com/shared/images/brand-jcb.png" alt="">
                                                </div>
                                                <div class="col-md-4">
                                                    <p>Currency's</p>
                                                </div>
                                                <div class="col-md-8">
                                                    <p class="currencies"><b>$</b><b>¥</b><b>£</b><b>€</b><b>₩</b></p>
                                                </div>
                                                <div class="col-md-4">
                                                    <p class="pt-2">Crypto's</p>
                                                </div>
                                                <div class="col-md-8 crypto">
                                                        <img src="../assets/images/icon/bitcoin.png" alt="" style="width:20%;">
                                                        <img src="../assets/images/icon/Ethiriuim.png" alt="" style="width:10%;">
                                                        <img src="../assets/images/icon/zec.png" alt="">
                                                        <img src="../assets/images/icon/doge.png" alt="">
                                                </div>
                                            </div>
                                        </div>
                                        <div id="paymentTokenInfo"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">On File Cards</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-1">
                                                <div class="col-md-12">
                                                    <button class="btn btn-success float-btn">Add New</button>
                                                </div>
                                            </div>
                                            <div class="row mt-3" id="pay-card">
                                                <div class="col-md-6">
                                                    <div class="card payCard" style="border:1px solid black">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12 text-right">
                                                                    <a href="#" class="btn btn-warning text-white btn-circle float-btn ml-1" style="padding: 11px;"><i class="far fa-star" aria-hidden="true"></i></a>
                                                                    <a href="#" class="btn btn-success btn-circle float-btn" style="padding: 11px;margin-right: 4px;margin-left: 4px"><i class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                                    <a href="#" class="btn btn-danger btn-circle float-btn" style="padding: 11px;"><i class="fas fa-trash" style="" aria-hidden="true"></i></a>
                                                                    <!-- <div id="default-star-rating" style="cursor: pointer;">
                                                                        <img alt="1" src="assets/images/rating/star-off.png" title="Rate" style="float:right;padding-right: 5px;position: relative;bottom: 4px;">
                                                                        <input name="score" type="hidden">

                                                                    </div>-->

                                                                </div>
                                                            </div>
                                                            <!--<h1 class="mt-0">
                                                            <i class="fab fa-cc-visa text-info" aria-hidden="true"></i></h1>-->





                                                            <!--<h3>hipercard Ended With 2222</h3>
                                                            <span class="pull-right">Exp date: 1245</span>-->

                                                            <h3 class="payCard-number">**** **** ****  2222</h3>
                                                            <p><span class="payCard-text">Muhammad Kashif</span>
                                                            <span class="payCard-text" style="float:right;"> Exp : 1245</span>
                                                            </p>
                                                            <!--<h4>hipercard ending in 2222<span class="pull-right"> (expires 1245)</span></h4>-->
                                                            <span class="font-500"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header" style="background-color: #f7f7f7;margin-left: 12px">
                                            <h4 class="mb-0">Crypto Wallet Addresses</h4>
                                        </div>
                                        <div class="card-body">
                                            <div class="row mt-1">
                                                <div class="col-md-4">
                                                    <div class="row">
                                                        <div class="col-md-6 text-center">
                                                            <img src="../assets/images/icon/bitcoin.png" alt="" style="width:70%;">
                                                            <p>Coin Name</p>
                                                        </div>
                                                        <div class="col-md-6"></div>
                                                        <div class="col-md-6 text-center">
                                                            <img src="../assets/images/icon/bitcoin.png" alt="" style="width:70%;">
                                                            <p>Coin Name</p>
                                                        </div>
                                                        <div class="col-md-6 "></div>
                                                        <div class="col-md-6 text-center">
                                                            <img src="../assets/images/icon/bitcoin.png" alt="" style="width:70%;">
                                                            <p>Coin Name</p>
                                                        </div>
                                                        <div class="col-md-6"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-6 " style="border-left:1px solid #54667a;">
                                                            <label for="">CUSTOM TERMS/LABELS</label>
                                                            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                                        </div>
                                                        <div class="col-md-6" style="border-left:1px solid #54667a;">
                                                            <label for="">CUSTOM TERMS/LABELS</label>
                                                            <textarea name="" id="" cols="30" rows="10" class="form-control"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade " id="notification" role="tabpanel"
                            aria-labelledby="notifications-profile-tab">
                            <hr>
                            <div class="card-body">
                                <div class="row">

                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="pills-assets-tab">
                            <hr>
                            <div class="card-body">
                                {{-- <div class="row">
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
                                        <label for="example-search-input">Search </label>
                                        <input class="form-control" type="text" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-xlg-4" style="padding-top:25px">
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-target="#assetModal" style="float:right;margin-bottom:5px;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset</button>
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-target="#assetCatModal" style="float:right;top: -35px;position: relative;"><i class="fas fa-plus"></i>&nbsp;Add Asset Category</button>
                                    </div>
                                </div> --}}
                                <div class="row">

                                    <div class="col-sm-12">
                                        <div class="accordion accordion-margin" id="accordionMargin">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingMargintwo">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#accordionMargintwo" aria-expanded="false" aria-controls="accordionMarginOne" style="margin-bottom: 14px;">
                                                        Add Asset
                                                    </button>
                                                </h2>
                                                <div id="accordionMargintwo" class="accordion-collapse collapse pb-3" aria-labelledby="headingMargintwo" data-bs-parent="#accordionMargin">
                                                    <div class="accordion-body">
                                                        <form class="form-horizontal" id="save_asset_form" enctype="multipart/form-data"
                                                        action="{{asset('/save-asset')}}" method="post">
                                                        <div class="form-row">
                                                            <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Template</label>
                                                                    <select class="select2 form-select form-control" onchange="getFields(this.value)" id="form_id" name="form_id" required></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-row mt-1" id="templateTitle" style="display:none;">
                                                            <!-- <div class="col-md-12 form-group">
                                                                <div class="form-group">
                                                                    <label>Asset Title</label>
                                                                        <input type="text" name="asset_title" id="asset_title" class="asset_title form-control">
                                                                </div>
                                                            </div> -->
                                                            <div class="row mt-2">
                                                                <div class="col-md-6">
                                                                    <label for="">Customers</label>
                                                                    <select class="form-control select2 tkt_customer_id" id="tkt_customer_id">
                                                                            <option value="">Choose</option>
                                                                        @foreach($all_customers as $cus)
                                                                            <option value="{{$cus->id}}"> {{$cus->first_name}} {{$cus->last_name}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label for="">Company</label>
                                                                    <select class="form-control select2 tkt_company_id" id="tkt_company_id">
                                                                    <option value="">Choose</option>
                                                                        @foreach($all_companies as $com)
                                                                            <option value="{{$com->id}}"> {{$com->name}} </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row" id="form-fields"></div>

                                                        <button type="submit" class="btn btn-success mt-1" style="float:right;">Save</button>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>


                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <h4 class="card-title">Assets</h4>
                                                    </div>
                                                    <!-- <div class="col-lg-3 col-md-3">
                                                        <button type="button" class="btn btn-success" onclick="ShowAssetModel()"
                                                            style="float:right;"><i class="mdi mdi-plus-circle" ></i>&nbsp;Add
                                                            Asset
                                                        </button>
                                                    </div>
                                                    <div class="col-lg-3 col-md-3">
                                                        <button type="button" class="btn btn-success" onclick="ShowAssetModel()"
                                                            style="float:right;"><i class="mdi mdi-plus-circle"></i>&nbsp;Add
                                                            Asset Template
                                                        </button>
                                                    </div> -->

                                                </div>
                                                <br>


                                                <!-- <div class="row">
                                                    <div class="col-md-12" style="text-align:right;">
                                                        <select class="multiple-select mt-2 mb-2" name="as_select" id="as_select" placeholder="Show/Hide" multiple="multiple" selected="selected">
                                                            <option value="0">Sr#</option>
                                                            <option value="1">AssetID</option>
                                                            <option value="2">Template</option>
                                                            <option value="3">Customer</option>
                                                            <option value="4">Company</option>
                                                            <option value="5">Projects</option>
                                                            <option value="6">Actions</option>
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <div class="table-responsive">
                                                    <table id="asset-table-list"
                                                        class="table table-bordered w-100 no-wrap asset-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th><div class="text-center"><input type="checkbox" id="checkAll" name="assets[]" value="0"></div></th>
                                                                <th></th>
                                                                <th>Asset Title</th>
                                                                <th> Asset Type </th>
                                                                <th>Company</th>
                                                                <th> Customer</th>
                                                                <th>Actions</th>
                                                            </tr>
                                                        </thead>

                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tickets" role="tabpanel" aria-labelledby="pills-tickets-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="float-end">
                                        <a href="{{url('add-ticket')}}/{{$customer->id}}" class="btn btn-success rounded ml-auto mb-auto float-right mb-3">
                                            <i class="fas fa-plus"></i>&nbsp;Add ticket
                                        </a>
                                        <!-- <button type="button" class="btn btn-info ml-auto mb-auto" onclick="ShowTicketsModel()">
                                            <i class="fas fa-plus"></i>&nbsp;Add ticket
                                        </button> -->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 col-4" id="allTicket">
                                            <a href="javascript:listTickets('active')" class="card card-hover border-info">
                                                <div class="box rounded info text-center">
                                                    <h1 class="font-weight-light " id="total_tickets_count"></h1>
                                                    <h6 class="text-info">Active</h6>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3 col-4" id="openTicket">
                                            <a href="javascript:listTickets('open')" class="card card-hover border-warning">
                                                <div class="box  rounded warning text-center">
                                                    <h1 class="font-weight-light " id="open_ticket_count"></h1>
                                                    <h6 class="text-warning">Open</h6>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-md-3 col-4" id="closeTicket">
                                            <a href="javascript:listTickets('closed')" class="card card-hover border-primary">
                                                <div class="box rounded primary text-center">
                                                    <h1 class="font-weight-light " id="closed_tickets_count"></h1>
                                                    <h6 class="text-primary">Closed</h6>
                                                </div>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="table-responsive">

                                        <table id="ticket-table-list" class="table ticket-table-list">
                                            <thead>
                                                <tr>
                                                    <th>
                                                        <div class="">
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
                                                    <!-- <th class='pr-tech custom-cst'>Creation Date</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>

                                        <div class="loading__">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade active show" id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                           <div class="card">
                            <div class="card-body">
                                <form id="update_customer" action="{{url('update_customer_profile')}}" method="POST">
                                    <h2 class="font-weight-bold text-dark">Personal Info</h2>

                                    <div class="row">
                                        <div class="col-md-4 form-group">
                                            <label for="first_name">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name"
                                                value="{{$customer->first_name}}" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name"
                                                value="{{$customer->last_name}}" required>
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" id="prof_email" name="prof_email"
                                                value="{{$customer->email}}" required>
                                        </div>
                                    </div>

                                    @if($customer->has_account != 0)
                                        <div class="row mt-1 mb-2">
                                            <div class="col-md-12">
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="change_password_checkbox" name="change_password_checkbox">
                                                    <label class="form-check-label" for="change_password_checkbox"> Change Password </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row mt-1 mb-2 change_password_row" style="display:none">
                                        <div class="col-md-6 form-group">
                                            <label>Password</label>
                                            <div class="user-password-div">
                                                <span class="block input-icon input-icon-right">
                                                    <input type="password" name="password" id="password"
                                                        placeholder="password" class="form-control form-control-line"
                                                        value="{{$customer->password}}">
                                                    <!-- <span toggle="#password-field"
                                                        class="fa fa-fw fa-eye field-icon show-password-btn mr-2"></span> -->
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Confirm Password</label>
                                            <div class="user-confirm-password-div">
                                                <input name="confirm_password" class="form-control form-control-line"
                                                    type="password" id="confirm_password" placeholder="Confirm Password"
                                                    value="{{$customer->password}}">
                                                <!-- <span toggle="#password-field"
                                                    class="fa fa-fw fa-eye field-icon show-confirm-password-btn mr-2"></span> -->
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Phone #</label>
                                            <div class="d-flex">
                                                <div class="country mt-1" style="padding-right: 8px;"></div>
                                                <input type="tel" class="tel form-control" name="prof_phone" id="prof_phone" value="{{$customer->phone != Null ? $customer->phone : '+'}}" placeholder="" autofocus>
                                            </div>
                                            <small class="text-muted">Please add country code before number e.g (+1) for US</small>
                                            {{-- <input type="text" id="prof_phone" name="prof_phone"
                                                value="{{$customer->phone}}" class="form-control form-control-line">--}}
                                                {{-- <span class="text-danger small" id="phone_error"></span>  --}}
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Phone Type</label>
                                            <select name="phone_type" id="phone_type" class="form-control">
                                                <option value="">Choose</option>
                                                <option value="cellphone" {{$customer->phone_type == "cellphone" ? "selected" : ''}}>Cell Phone</option>
                                                <option value="home" {{$customer->phone_type == "home" ? "selected" : ''}}>Home</option>
                                                <option value="office" {{$customer->phone_type == "office" ? "selected" : ''}}>Office</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Customer Type</label>

                                                <select id="cust_type" name="cust_type"
                                                    class="form-select form-control-line">
                                                    <option value="">Customer Type</option>
                                                    @foreach ($customer_types as $item)
                                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>

                                        </div>
                                        <div class="col-md-4 col-8 form-group">
                                            <label>Company</label>
                                                <select class="select2-data-array form-select form-control form-control-line" id="company_id" name="company_id">
                                                    <option value="">Company</option>
                                                    @foreach ($company as $item)
                                                    <option value="{{$item->id}}"
                                                        {{$item->id == $customer->company_id ? 'selected' : ''}}>{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        <div class="col-md-2 col-4 form-group">
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#addCompanyModal"
                                            id="new-company" class="btn btn-secondary" style="margin-top: 20px;position: relative;right:30px">New</button>
                                        </div>
                                    </div>

                                    @if($customer->has_account == 0)
                                    <div class="row mb-2 mt-2">
                                        <div class="custom-control custom-checkbox mr-sm-2">
                                            <input type="checkbox" class="custom-control-input" id="customer_login">
                                            <label class="custom-control-label" for="customer_login">Create Customer Login
                                                Account</label>
                                        </div>
                                    </div>
                                    @endif

                                    <input type="hidden" name="customer_id" value="{{$customer->id}}">

                                    <div class="row mt-1">
                                        <div class="col-12 form-group">
                                            <label>Street Address</label>
                                            <a type="button" data-bs-toggle="modal" data-bs-target="#Address-Book"
                                                class="float-right" style="color:#009efb;">View Address Book</a>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" class=" form-control" name="address"
                                                        value="{{$customer->address}}" id="prof_address"
                                                        placeholder="House number and street name">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" class=" form-control" name="apt_address"
                                                        value="{{$customer->apt_address}}" id="apt_address"
                                                        placeholder="Apartment, suit, unit etc. (optional)">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-3 form-group">
                                            <label>City</label>
                                            <input type="text" class="form-control" value="{{$customer->cust_city}}"
                                                id="prof_city">
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>State</label>

                                            @if($google_key == 1)
                                                <input type="text" class=" form-control " value="{{$customer->cust_state}}" id="prof_state" name="prof_state"
                                                    style="width: 100%; height:36px;">
                                            @else
                                                <select class="select2 form-control " id="prof_state" name="prof_state" style="width: 100%; height:36px;"></select>
                                            @endif
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>Zip Code</label>
                                            <input type="number" maxlength="5" class="form-control" value="{{$customer->cust_zip}}" id="prof_zip">
                                        </div>

                                        <div class="col-md-3 form-group">
                                            <label>Country</label>
                                            @if($google_key == 1)
                                                <input type="text" class=" form-control " value="{{$customer->country}}" id="prof_country" name="prof_country" style="width: 100%; height:36px;">
                                            @else
                                                <select class="select2 form-control " id="prof_country" name="prof_country" style="width: 100%; height:36px;" onchange="listStates(this.value, 'prof_state', 'cust_state')">
                                                    <option value="">Select Country</option>
                                                        @foreach ($countries as $item)
                                                            @if(!empty($customer->country))
                                                                <option value="{{$item->name}}" {{$customer->country == $item->name ? 'selected' : ''}}>{{$item->name}}</option>
                                                            @else
                                                                <option value="{{$item->name}}" {{'US' == $item->short_name ? 'selected' : ''}}>{{$item->name}}</option>
                                                            @endif
                                                        @endforeach
                                                </select>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body">
                                    <h2 class="font-weight-bold text-dark">Social</h2>

                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Twitter</label>
                                            <input type="text" class="form-control" id="prof_twitter"
                                                value="{{$customer->twitter}}" placeholder="https://twitter.com/username">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Facebook</label>
                                            <input type="text" class="form-control" id="prof_fb" value="{{$customer->fb}}"
                                                placeholder="https://facebook.com/yourprofile">
                                        </div>
                                    </div>

                                    <div class="row mt-1">
                                        <div class="col-md-6 form-group">
                                            <label>Instagram</label>
                                            <input type="text" class="form-control" id="prof_insta"
                                                value="{{$customer->isnta}}" placeholder="https://instagram.com/username">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Pinterest</label>
                                            <input type="text" class="form-control" id="prof_pinterest"
                                                value="{{$customer->pinterest}}"
                                                placeholder="https://pinterest.com/@Username">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Reddit</label>
                                            <input type="text" class="form-control" id="prof_reddit"
                                                value=""
                                                placeholder="https://reddit.com/@Username">
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Quora</label>
                                            <input type="text" class="form-control" id="prof_quora"
                                                value=""
                                                placeholder="https://quora.com/@Username">
                                        </div>
                                    </div>

                                    <div class="row mt-1" >
                                        <div class="col-md-6 form-group">
                                            <label>Linkedin</label>
                                            <input type="text" class="form-control" id="prof_linkedin"
                                                value="{{$customer->linkedin}}"
                                                placeholder="https://linkedin.com/@Username">
                                        </div>
                                    </div>


                                    <input type="hidden" name="customer-id" id="customer-id" value="{{$customer->id}}">
                                    <div class="row mt-1 mb-2 float-right">
                                        <div class="col-md-12 form-group">
                                            <button type="submit" id="saveBtn" class="btn btn-success">Save</button>

                                            <button id="processing" style="display:none" class="btn btn-success"
                                                    type="button" disabled>
                                                    <i class="fas fa-circle-notch fa-spin"></i>
                                                    Processing</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                                </form>
                                <div class="card">
                                    <h4 class="card-header">Change Password</h4>
                                    <div class="card-body">
                                        <form id="formChangePassword" method="POST" onsubmit="return false" novalidate="novalidate">
                                            <div class="alert alert-warning mb-2" role="alert">
                                                <h6 class="alert-heading">Ensure that these requirements are met</h6>
                                                <div class="alert-body fw-normal">Minimum 8 characters long, uppercase &amp; symbol</div>
                                            </div>

                                            <div class="row">
                                                <div class="mb-2 col-md-6 form-password-toggle">
                                                    <label class="form-label" for="newPassword">New Password</label>
                                                    <div class="input-group input-group-merge form-password-toggle">
                                                        <input class="form-control" type="password" id="newPassword" name="newPassword" placeholder="">
                                                        <span class="input-group-text cursor-pointer">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="mb-2 col-md-6 form-password-toggle">
                                                    <label class="form-label" for="confirmPassword">Confirm New Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <input class="form-control" type="password" name="confirmPassword" id="confirmPassword" placeholder="">
                                                        <span class="input-group-text cursor-pointer"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <button type="submit" class="btn btn-primary me-2 waves-effect waves-float waves-light float-end">Change Password</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-50">Two-steps verification</h4>
                                        <span>Keep your account secure with authentication step.</span>
                                        <h6 class="fw-bolder mt-2">SMS</h6>
                                        <div class="d-flex justify-content-between border-bottom mb-1 pb-1">
                                            <span>+1(968) 945-8832</span>
                                            <div class="action-icons">
                                                <a href="javascript:void(0)" class="text-body me-50" data-bs-target="#twoFactorAuthModal" data-bs-toggle="modal">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit font-medium-3"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                <a href="javascript:void(0)" class="text-body"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash font-medium-3"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
                                            </div>
                                        </div>
                                        <p class="mb-0">
                                            Two-factor authentication adds an additional layer of security to your account by requiring more than just a
                                            password to log in.
                                            <a href="javascript:void(0);" class="text-body">Learn more.</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Recent devices</h4>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table text-nowrap text-center">
                                            <thead>
                                                <tr>
                                                    <th class="text-start">BROWSER</th>
                                                    <th>DEVICE</th>
                                                    <th>LOCATION</th>
                                                    <th>RECENT ACTIVITY</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="text-start">
                                                        <div class="avatar me-25">
                                                            <img src="../../../app-assets/images/icons/google-chrome.png" alt="avatar" width="20" height="20">
                                                        </div>
                                                        <span class="fw-bolder">Chrome on Windows</span>
                                                    </td>
                                                    <td>Dell XPS 15</td>
                                                    <td>United States</td>
                                                    <td>10, Jan 2021 20:07</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">
                                                        <div class="avatar me-25">
                                                            <img src="../../../app-assets/images/icons/google-chrome.png" alt="avatar" width="20" height="20">
                                                        </div>
                                                        <span class="fw-bolder">Chrome on Android</span>
                                                    </td>
                                                    <td>Google Pixel 3a</td>
                                                    <td>Ghana</td>
                                                    <td>11, Jan 2021 10:16</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">
                                                        <div class="avatar me-25">
                                                            <img src="../../../app-assets/images/icons/google-chrome.png" alt="avatar" width="20" height="20">
                                                        </div>
                                                        <span class="fw-bolder">Chrome on MacOS</span>
                                                    </td>
                                                    <td>Apple iMac</td>
                                                    <td>Mayotte</td>
                                                    <td>11, Jan 2021 12:10</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-start">
                                                        <div class="avatar me-25">
                                                            <img src="../../../app-assets/images/icons/google-chrome.png" alt="avatar" width="20" height="20">
                                                        </div>
                                                        <span class="fw-bolder">Chrome on iPhone</span>
                                                    </td>
                                                    <td>Apple iPhone XR</td>
                                                    <td>Mauritania</td>
                                                    <td>12, Jan 2021 8:29</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>

                        <div class="tab-pane fade" id="ticket_notes" role="tabpanel" aria-labelledby="notes-profile-tab">
                           <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-end ">
                                    <button class="rounded btn btn-outline-success waves-effect fa fa-plus" style="margin-right: 20px"
                                         data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                         onclick="openNotesModal()" data-bs-original-title="Add Notes"> Add Note</button>
                                </div>

                                <div class="card-body" id="show_ticket_notes">
                                    No Data Found.
                                </div>
                            </div>
                           </div>

                        </div>

                        <div class="tab-pane fade" id="ticket_domain" role="tabpanel" aria-labelledby="domain-profile-tab">
                            <hr>
                            <div class="card-body">
                                <div class="table-responsive mt-3">
                                    <div id="zero_config_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-10 form-group">
                                                        <input type="text" id="search_domain" class="form-control" placeholder="Search your Perfect Domain here!">
                                                    </div>
                                                    <div class="col-md-2 form-group">
                                                        <button class="btn btn-success" onclick="searchDomain()"> Search </button>
                                                    </div>
                                                </div>

                                                <hr>
                                            </div>
                                            <div class="col-sm-12">
                                                <table id="domain_order_table"
                                                    class="table table-striped table-bordered w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Domain</th>
                                                            <th>Pricing</th>
                                                            <th>Status</th>
                                                            <th>Register</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                                <div class="loader_container" id="domain_loader">
                                                    <div class="loader"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <a type="button" class="btn btn-success text-white"> Purchase </a>
                                                <a type="button" class="btn btn-primary text-white" > Show Only </a>
                                            </div>

                                            <div class="col-sm-12 mt-3">
                                                <hr>
                                                <h4>My Domains</h4>
                                                <table id="mydomain_order_table"
                                                    class="table table-striped table-bordered w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>Select</th>
                                                            <th>Domain</th>
                                                            <th>Pricing</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                                    <label class="custom-control-label" for="customCheck1"></label>
                                                                </div>
                                                            </td>
                                                            <td> <a type="button" data-bs-toggle="modal" data-bs-target="#domainModal"><i class="fas fa-angle-right"></i> <span style="font-size:20px;"> www.king.cin</span></a></td>
                                                            <td>$78.22</td>
                                                            <td>Active</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <!-- <div class="loader_container">
                                                    <div class="loader"></div>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Project table -->
                    <div class="card">

                    </div>
    </div>
</div>
        <!--Address Book model-->
        <div class="modal fade" id="Address-Book" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel">ADDRESS BOOK</h4>
                        <button type="button" class="btn-close ml-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12" style="text-align:right;">
                                <div class="col-md-12">
                                    <button id="add_new_add" class=" float-right btn btn-success"
                                        onclick="New_Bill_Add()"><i class="fa fa-plus-circle"></i> Add New </button>
                                </div>
                            </div>
                            <div class="col-md-12 form-row" id="NewBillAdd" style="display:none;">
                                <div class="col-12 form-group">
                                    <label>Street Address</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class=" form-control" value="" id=""
                                                placeholder="House number and street name">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class=" form-control" value="" id=""
                                                placeholder="Apartment, suit, unit etc. (optional)">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                <div class="col-md-3 ">
                                    <label>City</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-3 ">
                                    <label>Zip Code</label>
                                    <input type="text" class="form-control">
                                </div>

                                <div class="col-md-3">
                                    <label>State</label>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="col-md-3">
                                        <label>Country</label>
                                        <input type="text" class="form-control">
                                </div>
                                <div class="col-md-12 float-right mt-2">
                                    <button type="submit" style="float:right;" class="btn btn-success ">Save</button>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="table-responsive mt-3">
                            <table id="zero_config" class="table table-striped table-bordered no-wrap">
                                <thead>
                                    <tr role="row">
                                        <th>Street address</th>
                                        <th>Apt</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip</th>
                                        <th>Country</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                    <tr>
                                        <td>NN2-E</td>
                                        <td>542</td>
                                        <td>Lahore</td>
                                        <td>Punjab</td>
                                        <td>54000</td>
                                        <td>Pakistan</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr role="row">
                                        <th>Street address</th>
                                        <th>Apt</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip</th>
                                        <th>Country</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal end -->

        <!--order number model-->
        <div class="modal fade" id="show-order" tabindex="-1" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">

                        <h4 class="modal-title" id="myLargeModalLabel">Order 1234</h4>
                        <div class="alert alert-success" role="alert" style="position:relative;left:477px;margin:unset;">
                            <i class="dripicons-checkmark "></i><strong>Processing</strong>
                        </div>
                        <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h3>Billing Details</h3>
                                <p>Eugenia karahalias 743 sandra ave west islip, NY 11795</p>
                            </div>
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-3">
                                <p>Subscription ####</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h3>Email</h3>
                                <p>Eve123@gmail.com</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h3>Phone</h3>
                                <p>514-565-3456</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <h3>Payment via</h3>
                                <p>Credit card (123456)</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h3>Product</h3>

                            </div>
                            <div class="col-md-2">
                                <h3>Quality</h3>

                            </div>
                            <div class="col-md-2">
                                <h3>Tax</h3>

                            </div>
                            <div class="col-md-2">
                                <h3>Total</h3>

                            </div>

                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <p>Huntress Cyber security -workstation
                                    its huntress wo</p>

                            </div>
                            <div class="col-md-2">
                                <h3>2</h3>

                            </div>
                            <div class="col-md-2">
                                <h3>$0.00</h3>

                            </div>
                            <div class="col-md-2">
                                <h3>$6.98</h3>

                            </div>

                        </div>
                        <hr>
                        <button class="btn btn-success" style="float:right">Edit</button>
                        <button class="btn btn-info" style="float:right;margin-right:2px;">Duplicate</button>
                        <button class="btn btn-primary">Update Profile</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal end -->

        <!-- add new company -->
        <div class="modal fade" id="addCompanyModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="edit-company">Save Company</h4>
                        <button type="button" class="btn-close ml-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
                    </div>
                    <div class="modal-body">
                        <form id="companyForm">

                            <div class="row">
                                <div class="col-md-4">
                                    <label for="poc_first_name" class="small">Owner First Name</label>
                                    <input type="text" id="poc_first_name" class="form-control">
                                    <span class="text-danger small" id="err"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="poc_last_name" class="small">Owner Last Name</label>
                                    <input type="text" class="form-control" id="poc_last_name">
                                    <span class="text-danger small" id="err1"></span>
                                </div>
                                <div class="col-md-4">
                                    <label for="name" class="small">Company Name</label>
                                    <input type="text" id="name" class="form-control">
                                    <span class="text-danger small" id="err2"></span>
                                </div>
                            </div>

                            <div class="row mt-1">
                                <div class="col-md-6">
                                    <label for="email" class="small">Company Email</label>
                                    <input type="text" class="form-control" id="cemail">
                                    <span class="text-danger small" id="err3"></span>
                                </div>
                                {{-- <div class="col-md-6">
                                    <label for="domain" class="small">Domain</label>
                                    <input type="text" class="form-control" id="domain">
                                    <span class="text-danger small" id="err3"></span>
                                </div> --}}
                                <div class="col-md-6">
                                    <label for="phone" class="small">Phone Number</label>
                                    <input type="text" class="form-control" id="phone">
                                    <span class="text-danger small" id="err4"></span>
                                </div>
                            </div>

                            <!-- <div class="row mt-3">

                            <div class="col-md-6">
                                <label for="country" class="small">Country</label>
                                <input type="text" id="country" class="form-control">
                                <span class="text-danger small" id="err5"></span>
                            </div>
                        </div> -->

                            <!-- <div class="row mt-3">
                            <div class="col-md-4">
                                <label for="state" class="small">State</label>
                                <input type="text" id="state" class="form-control">
                                <span class="text-danger small" id="err6"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="city" class="small">City</label>
                                <input type="text" class="form-control" id="city">
                                <span class="text-danger small" id="err7"></span>
                            </div>
                            <div class="col-md-4">
                                <label for="zip" class="small">Zip Code</label>
                                <input type="number" class="form-control" id="zip">
                                <span class="text-danger small" id="err8"></span>
                            </div>
                        </div>

                        <div class="row mt-3">
                           <div class="col-md-12">
                                <label for="address" class="small">Addres</label>
                                <textarea class="form-control" id="address" cols="30" rows="5"></textarea>
                                <span class="text-danger small" id="err9"></span>
                           </div>
                        </div> -->

                            <!-- <button type="button" style="float:right;" class="btn btn-danger mt-2" data-dismiss="modal">Close</button> -->
                            <button type="submit" style="float:right;" class="btn btn-success mt-2 mr-2">Save</button>

                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!--  Modal content ticket start -->
        <div class="modal fade" id="ticketModal" role="dialog"  data-backdrop="static" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header d-flex align-items-center">
                        <h4 class="modal-title" id="myLargeModalLabel" style="color:#009efb;">Add Ticket</h4>
                        <button type="button" class="close ml-auto" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" id="save_tickets" action="{{asset('save-tickets')}}" method="post">
                            <div class="row">
                                <div class="col-sm-12 col-xs-12">
                                    <fieldset>
                                        <div class="form-group">
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <label class="control-label col-sm-12">Subject<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="select-subject"
                                                        style="display: none; color: red !important;">Subject cannot be
                                                        Empty </span>
                                                    <input class="form-control" type="text" id="subject" name="subject">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Department<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="select-department"
                                                        style="display :none; color:red !important;;">Please Select
                                                        Department</span>
                                                    <select class="select2 form-control custom-select" type="search"
                                                        id="dept_id" name="dept_id" style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($departments as $department)
                                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Priority<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="select-priority"
                                                        style="display :none; color:red !important;">Please Select
                                                        Priority</span>
                                                    <select class="select2 form-control " id="priority" name="priority"
                                                        style="width: 100%; height:36px;">
                                                        <option value="">Select </option>
                                                        @foreach($priorities as $priority)
                                                        <option value="{{$priority->id}}">{{$priority->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-sm-4">
                                                    <label class="control-label col-sm-12">Select Type
                                                        <span style="color:red !important;">*</span></label><span
                                                        id="select-type" style="display :none; color:red !important;">Please
                                                        Select Type</span>
                                                    <select class="select2 form-control" id="type" name="type"
                                                        style="width: 100%; height:36px;">
                                                        <option value="">Select</option>
                                                        @foreach($types as $type)
                                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row mb-3">
                                                <div class="col-sm-12">
                                                    <label class="control-label col-sm-12">Problem Details<span
                                                            style="color:red !important;">*</span></label><span
                                                        id="pro-details" style="display :none; color:red !important;">Please
                                                        provide details</span>
                                                    <textarea class="form-control" rows="3" id="ticket_detail" name="ticket_detail"></textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                    <div class="text-right">
                                        <button type="submit" class="btn waves-effect waves-light btn-success"
                                            id="btnSaveTicket">
                                            <div class="spinner-border text-light" role="status"
                                                style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                                <span class="sr-only">Loading...</span>
                                            </div>Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal content ticket end -->
    </div>

    <!-- Create Template Title modal content -->
    <div id="fields-modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info p-2">
                    <span>
                        <h4 style="color:#fff !important;" id="headinglabel"> Select Setting </h4>
                    </span>
                </div>
                <div class="modal-body">
                    <form id="fields-form" class="pl-3 pr-3">
                        <div class="form-group">
                            <label for="select">Label</label>
                            <input class="form-control" type="text" id="lbl" required>
                        </div>
                        <div class="form-group">
                            <label>Placeholder</label>
                            <input class="form-control" type="text" id="ph">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input class="form-control" type="text" id="desc">
                        </div>
                        <div id="dyn-data"></div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="is_required">
                                <label class="custom-control-label" for="is_required">Set Required </label>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-rounded btn-primary" type="submit">Save</button>
                            <button class="btn btn-rounded btn-secondary" type="button" data-dismiss="modal">Close</button>
                        </div>
                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal -->
    <div class="modal fade" id="editPicModal" tabindex="-1" aria-labelledby="editPicModalLabel" data-backdrop="static" aria-hidden="true" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPicModalLabel">Customer Picture</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="prof-img ">
                            @php

                                $path = Session::get('is_live') == 1 ? '/' : '/';
                            @endphp
                            @if($customer->avatar_url != null)
                                @if(is_file( getcwd() .'/'. $customer->avatar_url))
                                    <img src="{{ request()->root() .'/'. $customer->avatar_url}}" class="modalImg rounded-circle" width="100" height="100" id="customer_curr_img" />
                                @else
                                    <img src="{{asset( $path . 'default_imgs/customer.png')}}" class="modalImg rounded-circle" width="100" height="100" id="customer_curr_img" />
                                @endif
                            @else
                                <img src="{{asset( $path . 'default_imgs/customer.png')}}" class="modalImg rounded-circle" width="100" height="100" id="customer_curr_img" />
                            @endif
                            <img src="{{asset( $path . 'default_imgs/customer.png')}}" id="hung22" alt="" class=" rounded-circle" width="100" height="100"  style="display:none;">

                    </div>
                    <form class="mt-4" id="upload_customer_img">
                        <div class="input-group">
                            <div class="custom-file w-100">
                                <input type="hidden" name="customer_id" id="customer_id" value="{{$customer->id}}">
                                <input type="file" name="profile_img" class="form-control"  onchange="loadFile(event)" id="customFilePP" accept="image/*">
                            </div>
                        </div>
                        <div class="text-right mt-3">
                            <button type="submit" class="btn btn-success float-right">Save changes</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!--Domain Modal -->
    <div class="modal fade" id="domainModal" tabindex="-1" aria-labelledby="domainModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="domainModalLabel">More Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
             <div class="row">
                <div class="col-md-3">
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                </div>
                <div class="col-md-3">
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                </div>
                <div class="col-md-3">
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                    <p>This is Line</p>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success"> Add New Time </button>
                </div>


                <div class="col-md-12">
                    <hr>
                    <button type="button" class="btn btn-warning" >Update Name Servers</button>
                    <button type="button" class="btn btn-danger">Update DNS Zone Records</button>
                    <button type="button" class="btn btn-info" >Set Glue Records</button>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            Header
                        </div>
                        <div class="card-body" style="max-height:200px;scroll-y:auto;">
                            Thinking
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    <div class="card">
                        <div class="card-header">
                            Header
                        </div>
                        <div class="card-body" style="max-height:200px;scroll-y:auto;">
                            Thinking
                        </div>
                    </div>
                </div>
             </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>


    <!-- Payment Modal -->
    <div class="modal fade" id="payNow" tabindex="-1" aria-labelledby="payNowLabel" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payNowLabel">Payment Method</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" style="padding:60px;">
                    <h2>What Payment Method you want to use?</h2>
                    <div class="payBtns ">

                        <button class="btn btn-success"><i class="fab fa-cc-visa"></i> Credit Card</button>
                        <a class="btn bg-pPal btn-info" href="{{url('paypal/ec-checkout')}}" id="paypalHref"> <i
                                class="fab fa-paypal"></i> PayPal</a>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- update asset modal -->
    <div id="update_asset_modal" class="modal fade" tabindex="-1" role="dialog"  data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="headinglabel"> Update - <span id="modal-title"></span>  </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="update_assets_form" enctype="multipart/form-data" onsubmit="return false">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label> Customer </label>
                                <select name="asset_customer_id" onchange="selectCustomer(this.value , 'edit_asst_cust_profile','edit_asst_comp_profile')" id="edit_asst_cust_profile"  class="select2 customerValue asset_customer_id">
                                    <option value=""> Choose </option>
                                    @foreach($all_customers as $c)
                                        <option value="{{$c->id}}"> {{$c->first_name}} {{$c->last_name}} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label> Company </label>
                                <select name="asset_company_id" onchange="selectCompany(this.value , 'edit_asst_cust_profile','edit_asst_comp_profile')" id="edit_asst_comp_profile" class="select2 companyValue asset_company_id">
                                    <option value=""> Choose </option>
                                    @foreach($all_companies as $comp)
                                        <option value="{{$comp->id}}"> {{$comp->name}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <!-- <label for="select">Asset Title</label> <span class="text-danger">*</span>
                            <input class="form-control" type="text" id="up_asset_title" required> -->
                            <input class="form-control" type="hidden" id="asset_title_id" required>
                        </div>

                        <div class="input_fields"></div>
                        <div class="address_fields"></div>
                        <div class="form-group text-end mt-3">
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

    <!-- Notes Modal -->
    <div class="modal fade text-start" id="notes_manager_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="note_title">Notes</h4>
                    <button type="button" class="btn-close text-danger" onclick="notesModalClose()"></button>
                </div>
                <div class="modal-body">
                    <form id="save_ticket_note" action="{{ asset('save-ticket-note') }}" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" id="note-id" name="id">
                        <div class="row">
                            <div class="col-12 d-flex py-2">
                                <label for="">
                                    <h4>Notes:</h4>
                                </label>
                                <div class="" style="margin-left:6px ">
                                    <span class="fas fa-square mr-2"
                                        style="font-size: 26px; color: #FFEFBB; cursor: pointer;"
                                        onclick="selectColor('#FFEFBB')"></span>
                                    <span class="fas fa-square mr-2"
                                        style="font-size: 26px; color: #e5c7ec; cursor: pointer;"
                                        onclick="selectColor('#e5c7ec')"></span>
                                    <span class="fas fa-square mr-2"
                                        style="font-size: 26px; color: #C7D6EC; cursor: pointer;"
                                        onclick="selectColor('#C7D6EC')"></span>
                                    <span class="fas fa-square mr-2"
                                        style="font-size: 26px; color: #E5ECC7; cursor: pointer;"
                                        onclick="selectColor('#E5ECC7')"></span>
                                    <span class="fas fa-square mr-2"
                                        style="font-size: 26px; color: #ECC9C9; cursor: pointer;"
                                        onclick="selectColor('#ECC9C9')"></span>
                                </div>
                            </div>

                            <div class="col-12 py-2">
                                <div class="form-group">
                                    {{-- <textarea name="note" id="note" class="form-control" style="background-color: #FFEFBB; color: black;"></textarea> --}}
                                    <textarea class="form-control d-none" rows="3" id="ticket_details" name="ticket_detail"></textarea>
                                    <div id="ticket_note_field" style="height: 200px"></div>
                                    <div id="menu" class="menu" role="listbox"></div>
                                </div>
                            </div>

                            <div class="col-5"></div>

                            <div class="col-12 pt-3">
                                <button type="button" class="btn btn-success float-right ms-1" disabled
                                    id="note_processing" style="display:none"> Processing ... </button>
                                <button type="submit" class="btn btn-success ms-1" id="note_save_btn"
                                    style="float: right;margin-right: 3px"> Save </button>
                                <button type="button" class="btn btn-secondary ms-1" data-bs-dismiss="modal"
                                    style="float: right">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal Notes Modal -->
 <!-- Edit User Modal -->
 <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-edit-user">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 pt-50">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Edit User Information</h1>
                    <p>Updating user details will receive a privacy audit.</p>
                </div>
                <form id="editUserForm" class="row gy-1 pt-75" onsubmit="return false">
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserFirstName">First Name</label>
                        <input type="text" id="modalEditUserFirstName" name="modalEditUserFirstName" class="form-control" placeholder="John" value="Gertrude" data-msg="Please enter your first name" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserLastName">Last Name</label>
                        <input type="text" id="modalEditUserLastName" name="modalEditUserLastName" class="form-control" placeholder="Doe" value="Barton" data-msg="Please enter your last name" />
                    </div>
                    <div class="col-12">
                        <label class="form-label" for="modalEditUserName">Username</label>
                        <input type="text" id="modalEditUserName" name="modalEditUserName" class="form-control" value="gertrude.dev" placeholder="john.doe.007" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserEmail">Billing Email:</label>
                        <input type="text" id="modalEditUserEmail" name="modalEditUserEmail" class="form-control" value="gertrude@gmail.com" placeholder="example@domain.com" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserStatus">Status</label>
                        <select id="modalEditUserStatus" name="modalEditUserStatus" class="form-select" aria-label="Default select example">
                            <option selected>Status</option>
                            <option value="1">Active</option>
                            <option value="2">Inactive</option>
                            <option value="3">Suspended</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditTaxID">Tax ID</label>
                        <input type="text" id="modalEditTaxID" name="modalEditTaxID" class="form-control modal-edit-tax-id" placeholder="Tax-8894" value="Tax-8894" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserPhone">Contact</label>
                        <input type="text" id="modalEditUserPhone" name="modalEditUserPhone" class="form-control phone-number-mask" placeholder="+1 (609) 933-44-22" value="+1 (609) 933-44-22" />
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserLanguage">Language</label>
                        <select id="modalEditUserLanguage" name="modalEditUserLanguage" class="select2 form-select" multiple>
                            <option value="english">English</option>
                            <option value="spanish">Spanish</option>
                            <option value="french">French</option>
                            <option value="german">German</option>
                            <option value="dutch">Dutch</option>
                            <option value="hebrew">Hebrew</option>
                            <option value="sanskrit">Sanskrit</option>
                            <option value="hindi">Hindi</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label" for="modalEditUserCountry">Country</label>
                        <select id="modalEditUserCountry" name="modalEditUserCountry" class="select2 form-select">
                            <option value="">Select Value</option>
                            <option value="Australia">Australia</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Belarus">Belarus</option>
                            <option value="Brazil">Brazil</option>
                            <option value="Canada">Canada</option>
                            <option value="China">China</option>
                            <option value="France">France</option>
                            <option value="Germany">Germany</option>
                            <option value="India">India</option>
                            <option value="Indonesia">Indonesia</option>
                            <option value="Israel">Israel</option>
                            <option value="Italy">Italy</option>
                            <option value="Japan">Japan</option>
                            <option value="Korea">Korea, Republic of</option>
                            <option value="Mexico">Mexico</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Russia">Russian Federation</option>
                            <option value="South Africa">South Africa</option>
                            <option value="Thailand">Thailand</option>
                            <option value="Turkey">Turkey</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="United Arab Emirates">United Arab Emirates</option>
                            <option value="United Kingdom">United Kingdom</option>
                            <option value="United States">United States</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="customSwitch10" checked />
                                <label class="form-check-label" for="customSwitch10">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                            <label class="form-check-label fw-bolder" for="customSwitch10">Use as a billing address?</label>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                            Discard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--/ Edit User Modal -->
<!-- upgrade your plan Modal -->
<div class="modal fade" id="upgradePlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-upgrade-plan">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-5 pb-2">
                <div class="text-center mb-2">
                    <h1 class="mb-1">Upgrade Plan</h1>
                    <p>Choose the best plan for user.</p>
                </div>
                <form id="upgradePlanForm" class="row pt-50" onsubmit="return false">
                    <div class="col-sm-8">
                        <label class="form-label" for="choosePlan">Choose Plan</label>
                        <select id="choosePlan" name="choosePlan" class="form-select" aria-label="Choose Plan">
                            <option selected>Choose Plan</option>
                            <option value="standard">Standard - $99/month</option>
                            <option value="exclusive">Exclusive - $249/month</option>
                            <option value="Enterprise">Enterprise - $499/month</option>
                        </select>
                    </div>
                    <div class="col-sm-4 text-sm-end">
                        <button type="submit" class="btn btn-primary mt-2">Upgrade</button>
                    </div>
                </form>
            </div>
            <hr />
            <div class="modal-body px-5 pb-3">
                <h6>User current plan is standard plan</h6>
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex justify-content-center me-1 mb-1">
                        <sup class="h5 pricing-currency pt-1 text-primary">$</sup>
                        <h1 class="fw-bolder display-4 mb-0 text-primary me-25">99</h1>
                        <sub class="pricing-duration font-small-4 mt-auto mb-2">/month</sub>
                    </div>
                    <button class="btn btn-outline-danger cancel-subscription mb-1">Cancel Subscription</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ upgrade your plan Modal -->
<!-- two factor auth modal -->
<div class="modal fade" id="twoFactorAuthModal" tabindex="-1" aria-labelledby="twoFactorAuthTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg two-factor-auth">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 mx-50">
                <h1 class="text-center mb-1" id="twoFactorAuthTitle">Select Authentication Method</h1>
                <p class="text-center mb-3">
                    you also need to select a method by which the proxy
                    <br />
                    authenticates to the directory serve
                </p>

                <div class="custom-options-checkable">
                    <input class="custom-option-item-check" type="radio" name="twoFactorAuthRadio" id="twoFactorAuthApps" value="apps-auth" checked />
                    <label for="twoFactorAuthApps" class="custom-option-item d-flex align-items-center flex-column flex-sm-row px-3 py-2 mb-2">
                        <span><i data-feather="settings" class="font-large-2 me-sm-2 mb-2 mb-sm-0"></i></span>
                        <span>
                            <span class="custom-option-item-title h3">Authenticator Apps</span>
                            <span class="d-block mt-75">
                                Get codes from an app like Google Authenticator, Microsoft Authenticator, Authy or 1Password.
                            </span>
                        </span>
                    </label>

                    <input class="custom-option-item-check" type="radio" name="twoFactorAuthRadio" value="sms-auth" id="twoFactorAuthSms" />
                    <label for="twoFactorAuthSms" class="custom-option-item d-flex align-items-center flex-column flex-sm-row px-3 py-2">
                        <span><i data-feather="message-square" class="font-large-2 me-sm-2 mb-2 mb-sm-0"></i></span>
                        <span>
                            <span class="custom-option-item-title h3">SMS</span>
                            <span class="d-block mt-75">We will send a code via SMS if you need to use your backup login method.</span>
                        </span>
                    </label>
                </div>

                <button id="nextStepAuth" class="btn btn-primary float-end mt-3">
                    <span class="me-50">Continue</span>
                    <i data-feather="chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- / two factor auth modal -->

<!-- add authentication apps modal -->
<div class="modal fade" id="twoFactorAuthAppsModal" tabindex="-1" aria-labelledby="twoFactorAuthAppsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg two-factor-auth-apps">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 mx-50">
                <h1 class="text-center mb-2 pb-50" id="twoFactorAuthAppsTitle">Add Authenticator App</h1>

                <h4>Authenticator Apps</h4>
                <p>
                    Using an authenticator app like Google Authenticator, Microsoft Authenticator, Authy, or 1Password, scan the
                    QR code. It will generate a 6 digit code for you to enter below.
                </p>

                <div class="d-flex justify-content-center my-2 py-50">
                    <img class="img-fluid" src="../../../app-assets/images/icons/qrcode.png" width="122" alt="QR Code" />
                </div>

                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">ASDLKNASDA9AHS678dGhASD78AB</h4>
                    <div class="alert-body fw-normal">
                        If you having trouble using the QR code, select manual entry on your app
                    </div>
                </div>

                <form class="row gy-1" onsubmit="return false">
                    <div class="col-12">
                        <input class="form-control" id="authenticationCode" type="text" placeholder="Enter authentication code" />
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary mt-2 me-1" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary mt-2">
                            <span class="me-50">Continue</span>
                            <i data-feather="chevron-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / add authentication apps modal-->

<!-- add authentication sms modal-->
<div class="modal fade" id="twoFactorAuthSmsModal" tabindex="-1" aria-labelledby="twoFactorAuthSmsTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg two-factor-auth-sms">
        <div class="modal-content">
            <div class="modal-header bg-transparent">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pb-5 px-sm-5 mx-50">
                <h1 class="text-center mb-2 pb-50" id="twoFactorAuthSmsTitle">`</h1>
                <h4>Verify Your Mobile Number for SMS</h4>
                <p>Enter your mobile phone number with country code and we will send you a verification code.</p>
                <form class="row gy-1 mt-1" onsubmit="return false">
                    <div class="col-12">
                        <input class="form-control phone-number-mask" type="text" placeholder="Mobile number with country code" />
                    </div>
                    <div class="col-12 d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-secondary mt-1 me-1" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary mt-1">
                            <span class="me-50">Continue</span>
                            <i data-feather="chevron-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- / add authentication sms modal-->
    <input type="hidden" id="loggedInUser_id" value="{{ \Auth::user()->id }}">
    <input type="hidden" id="loggedInUser_t" value="{{ \Auth::user()->user_type }}">
</div>
@endsection
@section('scripts')
<!-- jQuery ui files-->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
<script src="https://rawgit.com/RobinHerbots/jquery.inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>
<script>
    let tkt_arr = [];
    let ticketLengthCount = {!! json_encode($ticketView) !!};
    let noteUsers = {!! json_encode($noteUsers) !!};
    let customers = @json($all_customers);
    let companies = @json($all_companies);
    let cust_id = $("#customer_id").val();

    $(document).ready(function() {
        let item = customers.find(item => item.id == cust_id );
        if(item != null) {
            $("#tkt_customer_id").val(cust_id).trigger("change");
            if(item.company_id != null) {
                let company = companies.find(com => com.id == item.company_id);
                // alert(company.name);
                // let option = `<option value="${company.id}"> ${company.name} </option>`;
                $("#tkt_company_id").val(company.id).trigger('change');
            }
        }
    });
</script>

@include('js_files.help_desk.asset_manager.templateJs')
@include('js_files.help_desk.asset_manager.actionsJs')
@include('js_files.help_desk.asset_manager.assetJs')

<!-- <link rel="stylesheet" type="text/css" href="{{asset('assets/extra-libs/countdown/countdown.css')}}" />
<script type="text/javascript" src="{{asset('assets/extra-libs/countdown/countdown.js')}}"></script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
@include('js_files.customer_lookup.customerprofileJs')

<script>
     function loadFile(event) {
        $('.modalImg').hide();
        $("#hung22").show()
        var output = document.getElementById('hung22');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
        }
    };

    $('.loadAssets').click(function() {
        getFormsTemplates();
        get_asset_table_list();
    });

    $('.content').on('mouseenter', '.ticket_name', function() {
        let id = $(this).data('id');
        let item = tkt_arr.find(item => item.id == id);
        console.log(item , "item");
        if(item != null) {
            let last_reply = ``;
            let type = ``;

            if(item.last_reply != null) {

                let time =convertDate(item.created_at);

                var user_img = ``;

                if(item.last_reply != null){

                    type = 'Staff';
                    if(item.last_reply.reply_user != null) {
                        type = item.last_reply.reply_user.user_type == 5 ? 'User' : 'Staff';
                    }

                    if(item.last_reply.reply_user.profile_pic != null) {
                        let path = root + '/' + item.last_reply.reply_user.profile_pic;
                        user_img += `<img src="${path}" style="border-radius: 50%;" class="rounded-circle " width="40px" height="40px" />`;
                    }else{
                        user_img += `<img src="{{asset('${root}/default_imgs/customer.png')}}" class="rounded-circle"
                                width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                    }
                }

                let html = `
                <ul class="list-unstyled replies">
                    <li class="media" id="reply__0">
                        <span class="mr-3"> ${user_img} </span>
                        <div class="row">

                            <div class="col-md-12">
                            <h5 class="mt-0"><span class="text-primary">
                                <a href="http://127.0.0.1:8000/profile/209"> ${item.lastReplier} </a>
                                </span>&nbsp;<span class="badge badge-secondary">${type}</span>&nbsp;
                            &nbsp;
                            <br>
                            <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;">Posted on ${ time } </span>
                            <div class="my-1 bor-top" id="reply-html-4"> ${item.last_reply.reply} </div>
                        </div>

                    </li>
                    <div class="row mt-1" style="word-break: break-all;"></div>
                </ul>
                `
                last_reply = html;
            }else{

                let user_type = item.ticket_created_by == null ? 'Staff' : 'User';
                let path = root + '/' + item.user_pic;

                let img = `<img src="${path}" style="border-radius: 50%;" class="rounded-circle " width="40px" height="40px" />`;
                let html = `
                <div class="card p-0">
                        <div class="modal-first">
                            <div class="mt-0 mt-0 rounded" style="padding:4px; ">
                                <div class="float-start rounded me-1 bg-none" style="margin-top:5px">
                                    <div class=""> ${img} </div>
                                </div>
                                <div class="more-info">
                                    <div class="" style="display: -webkit-box">
                                        <h6 class="mb-0"> ${item.creator_name != null ? item.creator_name : item.customer_name} <span class="badge badge-secondary"> ${user_type}</span>  </h6>
                                        <span class="ticket-timestamp3 text-muted small" style="margin-left: 9px;">Posted on ${convertDate(item.created_at)}</span>
                                    </div>
                                    <div class="first">
                                        <span style="word-break: break-all;font-size:20px"> ${item.subject} </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class="card-body p-0">
                            <div class="mail-message">
                                <div class="row" id="ticket_details_p"><div class="col-12" id="editor_div"> ${item.ticket_detail} </div>
                            </div>
                        </div>
                    </div>`;


                last_reply = html;
            }
            $('.hover_content_'+id).html(last_reply);
        }
        $('.hover_content_'+id).show();
    }).on('mouseleave', '.ticket_name', function() {
        let id = $(this).data('id');
        $('.hover_content_'+id).hide();
    });

    $("#tkt_customer_id").on("change" , function() {
        $("#tkt_company_id").empty();
        let root = `<option value="">Choose</option>`;
        if($(this).val() != '') {
            let item = customers.find(item => item.id == $(this).val() );
            $("#customer_id").val($(this).val());
            if(item != null) {
                if(item.company_id != null) {
                    let company = companies.find(com => com.id == item.company_id);
                    let option = `<option value="${company.id}"> ${company.name} </option>`;
                    $("#tkt_company_id").append(root + option).trigger('change');
                }
            }
        }else{
            let option = ``;
            for(let data of companies)  {
                option += `<option value="${data.id}"> ${data.name} </option>`;
            }
            $("#tkt_company_id").append(root + option).trigger('change');
        }
    });


    tinymce.init({
        selector: '#note',
        mobile: {
            theme: 'silver'
          },
        plugins: ["advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste"
        ],
        toolbar: 'bold italic underline alignleft link',
        menubar: false,
        statusbar: false,
        relative_urls : 0,
        remove_script_host : 0,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    });


    //Quill Js On Notes
    var quill = new Quill('#ticket_note_field', {
        theme: 'snow',
        modules: {
            'toolbar': [
                [{ 'font': [] }, { 'size': [] }],
                [ 'bold', 'italic', 'underline', 'strike' ],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'super' }, { 'script': 'sub' }],
                [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block' ],
                [{ 'list': 'ordered' }, { 'list': 'bullet'}, { 'indent': '-1' }, { 'indent': '+1' }],
                [ 'direction', { 'align': [] }],
                [ 'link', 'image', 'video', 'formula' ],
                [ 'clean' ]
            ],
            imageResize: {
              displaySize: true
            },
            keyboard: {
                bindings: {
                tributeSelectOnEnter: {
                    key: 13,
                    shortKey: false,
                    handler: (event) => {
                    if (note_tribute.isActive) {
                        note_tribute.selectItemAtIndex(note_tribute.menuSelected, event);
                        note_tribute.hideMenu();
                        return false;
                    }

                    return true;
                    }
                },
                }
            }
            }
    });

    let note_tribute = new Tribute({
        values: noteUsers
    });
    note_tribute.attach($("#ticket_note_field").find(".ql-editor"));


    function openNotesModal() {
        $("#note_title").text("Add Notes");
        $("#notes_manager_modal").modal('show');
        $("#note").val(" ");
        $("#note-type-ticket").prop('checked',true);
        $('#note-visibilty').prop('disabled', false);
        $("#note-visibilty").val("Everyone").trigger('change');
        $("#note-id").val("");
        tinyMCE.get(0).getBody().style.backgroundColor = '#FFEFBB';
        gl_color_notes = '#FFEFBB';
    }

    function notesModalClose() {
        $("#notes_manager_modal").modal('hide');
    }

    function selectColor(color) {
        gl_color_notes = color
        $("#save_ticket_note .ql-editor").css('background-color',color)
        // tinyMCE.get(0).getBody().style.backgroundColor = color;
    }

    $("#save_ticket_note").submit(function(event) {
        event.preventDefault();

        let note = quill.root.innerHTML;


        var formData = new FormData($(this)[0]);

        let extract_notes_email = note.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
        if (extract_notes_email != null && extract_notes_email != '') {
            formData.append('tag_emails', extract_notes_email.join(','));
        }
        formData.append('ticket_id', '');
        formData.append('color', gl_color_notes);
        formData.append('type', 'user');
        formData.append('visibility', all_staff_ids.toString());
        formData.append('customer_id', cust_id);
        formData.append('note', note);
        if (gl_sel_note_index !== null) {
            formData.append('id', notes[gl_sel_note_index].id);
        }


        $.ajax({
            type: "POST",
            url: "{{asset('save-ticket-note_cc')}}" ,
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            beforeSend:function(data) {
                $("#note_save_btn").hide();
                $('#note_processing').attr('style', 'display: block !important');
            },
            success: function(data) {
                // console.log(data);
                if (data.success) {
                    quill.root.innerHTML = ''
                    alertNotification('success', 'Success' , data.message);

                    let b  = new Date(data.tkt_update_at).toLocaleString('en-US', { timeZone: time_zone });
                    let tkt_updted_date = moment(b).format(date_format + ' ' + 'hh:mm A');
                    // send mail notification regarding ticket action
                    $("#updation-date").html(tkt_updted_date);

                    let note_status = 'added';
                    let note_temp = 'ticket_note_create';
                    if ($('#note-id').val()) {
                        note_status = 'updated';
                        note_temp = 'ticket_note_update';
                    }
                    ticket_notify(note_temp, 'Note ' + note_status, data.data.id);


                    $(this).trigger('reset');

                    getNotes();

                    $('#notes_manager_modal').modal('hide');

                } else {
                    alertNotification('error', 'Error' , data.message );
                }
            },
            complete:function(data) {
                $("#note_save_btn").show();
                $('#note_processing').attr('style', 'display: none !important');
            },
            failure: function(errMsg) {
                $("#note_save_btn").show();
                $('#note_processing').attr('style', 'display: none !important');
            }
        });
    });

    // function editNote(id) {
    //     let item = notes.find(item => item.id === id);

    //     if(item != null || item != undefined || item != "") {

    //         $("#note_title").text("Edit Notes");
    //         $('#notes_manager_modal').modal('show');

    //         $('#note-id').val(id);
    //         tinymce.activeEditor.setContent(item.note != null ? item.note : '')
    //         tinyMCE.get(0).getBody().style.backgroundColor = item.color != null ? item.color : '';
    //         gl_color_notes = item.color != null ? item.color : '';

    //     }
    // }

    // function get_ticket_notes() {
    //     $('#show_ticket_notes').html('');
    //     $.ajax({
    //         type: 'GET',
    //         url: ticket_notes_route,
    //         data: { customer: cust_id, type:"User" },
    //         success: function(data) {
    //             if (data.success) {
    //                 if(data.notes_count  != 0) {
    //                     $('.notes_count').addClass('badge badge-light-danger rounded-pill mx-1');
    //                     $('#notes_count').text(data.notes_count);
    //                 }

    //                 notes = data.notes;
    //                 var type = '';

    //                 if (timeouts_list.length) {
    //                     for (let i in timeouts_list) {
    //                         clearTimeout(timeouts_list[i]);
    //                     }
    //                 }

    //                 timeouts_list = [];

    //                 let notes_html = ``;

    //                 for (let i in notes) {

    //                     let timeOut = '';
    //                     let autho = '';
    //                     if (loggedInUser_t == 1) {

    //                         autho = `<div class="mt-2">
    //                                     <span class="btn btn-icon rounded-circle btn-outline-danger waves-effect fa fa-trash"
    //                                         style= "float:right;cursor:pointer;position:relative;bottom:25px"
    //                                         onclick="deleteTicketNote(this, '` + notes[i].id + `')" ></span>

    //                                     <span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit"
    //                                         style="float:right;padding-right:5px;cursor:pointer;position:relative;bottom:25px; margin-right:5px"
    //                                         onclick="editNote(`+ notes[i].id +`)"></span>
    //                                 </div>`;
    //                     }


    //                     type = '<i class="fas fa-user"></i>';


    //                     // else{
    //                     //     type = '<i class="far fa-building"></i>';
    //                     // }

    //                     var user_img = ``;
    //                     let is_live = "{{Session::get('is_live')}}";
    //                     let path = is_live == 0 ? '' : 'public/';

    //                     if(notes[i].profile_pic != null) {

    //                         user_img += `<img src="{{asset('${notes[i].profile_pic}')}}"
    //                         width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;

    //                     }else{

    //                         user_img += `<img src="{{asset('${path}default_imgs/customer.png')}}"
    //                                 width="40px" height="40px" style="border-radius: 50%;" class="rounded-circle" />`;

    //                     }

    //                     let flup = `<div class="col-12 rounded p-2 my-1 d-flex" id="note-div-` + notes[i].id + `" style="background-color: ` + notes[i].color + `">
    //                         <div style="margin-right: 10px; margin-left: -8px;">
    //                             ${user_img}
    //                         </div>
    //                         <div class="w-100">
    //                             <div class="d-flex justify-content-between">
    //                                 <h5 class="note-head" style="margin-top:10px"> <strong> ${notes[i].name} </strong> on <span class="small"> ${jsTimeZone(notes[i].created_at)} </span>  ${type} </h5>
    //                                 ` + autho + `
    //                             </div>
    //                             <blockquote>
    //                             <p class="col text-dark" style="margin-top:-20px; word-break:break-all; color:black !important">
    //                                 ${notes[i].note.replace(/\r\n|\n|\r/g, '<br />')}
    //                             </p>
    //                             </blockquote>
    //                         </div>
    //                     </div>`;

    //                     $('#show_ticket_notes').append(flup);
    //                 }
    //             }
    //         },
    //         failure: function(errMsg) {

    //         }
    //     });
    // }

    jQuery(function($){
      var input = $('[type=tel]')
      input.mobilePhoneNumber({allowPhoneWithoutPrefix: '+1'});
      input.bind('country.mobilePhoneNumber', function(e, country) {
        $('.country').text(country || '')
      })
    });

    // $("#pills-tickets-tab").on('click', function(e){
    //     $("#card-1").hide();
    //     $(this).find('#ticket-full').removeClass("col-xl-8");
    //     $(this).find('#ticket-full').addClass("col-xl-12");
    // });
    $("#pills-tickets-tab").on("click", function () {
        $("#card-1").hide();
    if ($('#ticket-full').hasClass("col-xl-8")) {
        $("#ticket-full").removeClass("col-xl-8");
        $("#ticket-full").addClass("col-xl-12");

    }else{
        $("#card-1").show();
        $("#ticket-full").removeClass("col-xl-12");
        $("#ticket-full").addClass("col-xl-8");

    }


  });
  </script>
@endsection
