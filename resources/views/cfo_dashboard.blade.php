@extends('layouts.staff-master-layout')
@section('body-content')
<link href="{{asset('assets/libs/chartist/dist/chartist.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/dist/js/pages/chartist/chartist-init.css')}}" rel="stylesheet">
    <link href="{{asset('assets/extra-libs/css-chart/css-chart.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css')}}" rel="stylesheet">
    <link href="{{asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}" rel="stylesheet" />
<style>
    a:hover{
        color: #009efb;
    }
</style>
<div class="page-breadcrumb">
                <div class="row">
                    <div class="col-md-5 align-self-center">
                        <h4 class="page-title">CFO Dashboard</h4>
                        <div class="d-flex align-items-center">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">CFO Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="col-md-7 d-flex justify-content-end align-self-center d-none d-md-flex">
                        <div class="d-flex">
                            <div class="dropdown mr-2 hidden-sm-down">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> January 2020 </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"> <a class="dropdown-item" href="#">February 2020</a> <a class="dropdown-item" href="#">March 2020</a> <a class="dropdown-item" href="#">April 2020</a> </div>
                            </div>
                            <button class="btn btn-success"><i class="mdi mdi-plus-circle"></i> Create</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">2376 <i class="ti-angle-down font-14 text-danger"></i></span>
                                        <h6>Total Visits</h6></div>
                                    <div class="col-4 align-self-center text-right  pl-0">
                                        <div id="sparklinedash3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">3670 <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h6>Page Views</h6></div>
                                    <div class="col-4 align-self-center text-right pl-0">
                                        <div id="sparklinedash"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">1562 <i class="ti-angle-up font-14 text-success"></i></span>
                                        <h6>Unique Visits</h6></div>
                                    <div class="col-4 align-self-center text-right pl-0">
                                        <div id="sparklinedash2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <!-- Row -->
                                <div class="row">
                                    <div class="col-8"><span class="display-6">35% <i class="ti-angle-down font-14 text-danger"></i></span>
                                        <h6>Bounce Rate</h6></div>
                                    <div class="col-4 align-self-center text-right pl-0">
                                        <div id="sparklinedash4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex align-items-center">
                                    <h4 class="card-title">Total Revenue</h4>
                                    <div class="ml-auto">
                                        <ul class="list-inline">
                                            <li class="list-inline-item">
                                                <h6 class="text-muted"><i class="fa fa-circle mr-1" style="color:#51bdff"></i>2015</h6>
                                            </li>
                                            <li class="list-inline-item">
                                                <h6 class="text-muted"><i class="fa fa-circle mr-1" style="color:#11a0f8"></i>2016</h6>
                                            </li>
                                            <li class="list-inline-item">
                                                <h6 class="text-muted"><i class="fa fa-circle mr-1 text-info"></i>2020</h6>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="clear"></div>
                                <div class="total-sales" style="height: 359px;"></div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Sales Prediction</h4>
                                        <div class="row">
                                            <div class="col-5 col-md-6 align-self-center">
                                                <span class="display-6 text-primary text-truncate d-block">$3528</span>
                                                <h6 class="text-muted text-truncate">10% Increased</h6>
                                                <h5 class="text-truncate">(150-165 Sales)</h5>
                                            </div>
                                            <div class="col-7 col-md-6 d-flex justify-content-end">
                                                <div id="gauge-chart" style=" width:150px; height:150px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title">Sales Difference</h4>
                                        <div class="row">
                                            <div class="col-5 col-md-6 align-self-center">
                                                <span class="display-6 text-success text-truncate d-block">$4316</span>
                                                <h6 class="text-muted text-truncate">10% Increased</h6>
                                                <h5 class="text-truncate">(150-165 Sales)</h5>
                                            </div>
                                            <div class="col-7 col-md-6 d-flex justify-content-end">
                                                <div class="ct-chart" style="width:120px; height: 120px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                 <!-- Row -->
                 <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-md-flex no-block">
                                    <div>
                                        <h4 class="card-title">Sales Overview</h4>
                                        <h6 class="card-subtitle">Check the monthly sales </h6>
                                    </div>
                                    <div class="ml-auto">
                                        <select class="custom-select">
                                            <option selected="">March</option>
                                            <option value="1">February</option>
                                            <option value="2">May</option>
                                            <option value="3">April</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body bg-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h2 class="mb-0">March 2020</h2>
                                        <h4 class="font-weight-light mt-0">Report for this month</h4></div>
                                    <div class="col-md-6 align-self-center display-6 text-info text-left text-md-right">$3,690</div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="text-center border-0">#</th>
                                            <th class="border-0">NAME</th>
                                            <th class="border-0">STATUS</th>
                                            <th class="border-0">DATE</th>
                                            <th class="border-0">PRICE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td class="txt-oflo">Elite admin</td>
                                            <td><span class="badge badge-success py-1">SALE</span> </td>
                                            <td class="txt-oflo">April 18, 2020</td>
                                            <td><span class="text-success">$24</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-info py-1">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2020</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-info py-1">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2020</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">4</td>
                                            <td class="txt-oflo">Medical Pro WP Theme</td>
                                            <td><span class="badge badge-danger py-1">TAX</span></td>
                                            <td class="txt-oflo">April 20, 2020</td>
                                            <td><span class="text-danger">-$24</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">5</td>
                                            <td class="txt-oflo">Hosting press html</td>
                                            <td><span class="badge badge-warning py-1">SALE</span></td>
                                            <td class="txt-oflo">April 21, 2020</td>
                                            <td><span class="text-success">$24</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">6</td>
                                            <td class="txt-oflo">Digital Agency PSD</td>
                                            <td><span class="badge badge-success py-1">SALE</span> </td>
                                            <td class="txt-oflo">April 23, 2020</td>
                                            <td><span class="text-danger">-$14</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">7</td>
                                            <td class="txt-oflo">Elite admin</td>
                                            <td><span class="badge badge-success py-1">SALE</span> </td>
                                            <td class="txt-oflo">April 18, 2020</td>
                                            <td><span class="text-success">$24</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Total Visits</h4>
                                <div id="visitfromworld" style="width:100%!important; height:415px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Browser Stats</h4>
                                <div class="table-responsive">
                                    <table class="table browser mt-4 table-borderless no-wrap">
                                        <tbody>
                                            <tr>
                                                <td style="width:40px"><img src="../assets/images/browser/chrome-logo.png" alt=logo /></td>
                                                <td>Google Chrome</td>
                                                <td class="text-right"><span class="badge badge-light-info text-info">23%</span></td>
                                            </tr>
                                            <tr>
                                                <td><img src="../assets/images/browser/firefox-logo.png" alt=logo /></td>
                                                <td>Mozila Firefox</td>
                                                <td class="text-right"><span class="badge badge-light-success text-success">15%</span></td>
                                            </tr>
                                            <tr>
                                                <td><img src="../assets/images/browser/safari-logo.png" alt=logo /></td>
                                                <td>Apple Safari</td>
                                                <td class="text-right"><span class="badge badge-light-primary text-primary">07%</span></td>
                                            </tr>
                                            <tr>
                                                <td><img src="../assets/images/browser/internet-logo.png" alt=logo /></td>
                                                <td>Internet Explorer</td>
                                                <td class="text-right"><span class="badge badge-light-warning text-warning">09%</span></td>
                                            </tr>
                                            <tr>
                                                <td><img src="../assets/images/browser/opera-logo.png" alt=logo /></td>
                                                <td>Opera mini</td>
                                                <td class="text-right"><span class="badge badge-light-danger text-danger">23%</span></td>
                                            </tr>
                                            <tr>
                                                <td><img src="../assets/images/browser/internet-logo.png" alt=logo /></td>
                                                <td>Microsoft edge</td>
                                                <td class="text-right"><span class="badge badge-light-megna text-megna">09%</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
               
                <!-- Row -->
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Recent Chats</h4>
                                <div class="chat-box scrollable" style="height: 375px;">
                                    <!--chat Row -->
                                    <ul class="chat-list m-0 p-0">
                                        <!--chat Row -->
                                        <li class="mt-4">
                                            <div class="chat-img d-inline-block align-top"><img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle" /></div>
                                            <div class="chat-content pl-3 d-inline-block">
                                                <h5 class="text-muted text-nowrap">James Anderson</h5>
                                                <div class="box mb-2 d-inline-block text-dark rounded p-2 bg-light-info">Lorem Ipsum is simply dummy text of the printing & type setting industry.</div>
                                            </div>
                                            <div class="chat-time d-inline-block text-right text-muted">10:56 am</div>
                                        </li>
                                        <!--chat Row -->
                                        <li class="mt-4">
                                            <div class="chat-img d-inline-block align-top"><img src="../assets/images/users/2.jpg" alt="user" class="rounded-circle" /></div>
                                            <div class="chat-content pl-3 d-inline-block">
                                                <h5 class="text-muted text-nowrap">Bianca Doe</h5>
                                                <div class="box mb-2 d-inline-block text-dark rounded p-2 bg-light-success">It’s Great opportunity to work.</div>
                                            </div>
                                            <div class="chat-time d-inline-block text-right text-muted">10:57 am</div>
                                        </li>
                                        <!--chat Row -->
                                        <li class="odd mt-4">
                                            <div class="chat-content pl-3 d-inline-block text-right">
                                                <div class="box mb-2 d-inline-block text-dark rounded p-2 bg-light-inverse">I would love to join the team.</div>
                                                <br/>
                                            </div>
                                            <div class="chat-time d-inline-block text-right text-muted">10:58 am</div>
                                        </li>
                                        <!--chat Row -->
                                        <li class="odd mt-4">
                                            <div class="chat-content pl-3 d-inline-block text-right">
                                                <div class="box mb-2 d-inline-block text-dark rounded p-2 bg-light-inverse">Whats budget of the new project.</div>
                                                <br/>
                                            </div>
                                            <div class="chat-time d-inline-block text-right text-muted">10:59 am</div>
                                        </li>
                                        <!--chat Row -->
                                        <li class="mt-4">
                                            <div class="chat-img d-inline-block align-top"><img src="../assets/images/users/3.jpg" alt="user" class="rounded-circle" /></div>
                                            <div class="chat-content pl-3 d-inline-block">
                                                <h5 class="text-muted text-nowrap">Angelina Rhodes</h5>
                                                <div class="box mb-2 d-inline-block text-dark rounded p-2 bg-light-primary">Well we have good budget for the project</div>
                                            </div>
                                            <div class="chat-time d-inline-block text-right text-muted">11:00 am</div>
                                        </li>
                                        <!--chat Row -->
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body border-top">
                                <div class="row">
                                    <div class="col-8">
                                        <textarea placeholder="Type your message here" class="form-control border-0"></textarea>
                                    </div>
                                    <div class="col-4 text-right">
                                        <button type="button" class="btn btn-info btn-circle btn-lg"><i class="fas fa-paper-plane"></i> </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Recent Messages</h4>
                                <div class="message-box scrollable" style="height: 476px;">
                                    <div class="message-widget message-scroll">
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle online"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Pavan kumar</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">Lorem Ipsum is simply dummy text of the printing and type setting industry. Lorem Ipsum has been.</span> <span class="font-12 text-nowrap d-block time">9:30 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/2.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle busy"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Sonu Nigam</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">I've sung a song! See you at</span> <span class="font-12 text-nowrap d-block time">9:10 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <span class="round bg-info d-inline-block text-white text-center rounded-circle">A</span> <span class="profile-status rounded-circle away"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Arijit Sinh</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">Simply dummy text of the printing and typesetting industry.</span> <span class="font-12 text-nowrap d-block time">9:08 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/4.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle offline"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Pavan kumar</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">Just see the my admin!</span> <span class="font-12 text-nowrap d-block time">9:02 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/1.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle online"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Pavan kumar</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">Welcome to the Elite Admin</span> <span class="font-12 text-nowrap d-block time">9:30 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/2.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle busy"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Sonu Nigam</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">I've sung a song! See you at</span> <span class="font-12 text-nowrap d-block time">9:10 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center border-bottom py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/3.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle away"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Arijit Sinh</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">I am a singer!</span> <span class="font-12 text-nowrap d-block time">9:08 AM</span> </div>
                                        </a>
                                        <!-- Message -->
                                        <a href="#" class="d-flex align-items-center py-2 px-3">
                                            <div class="user-img position-relative d-inline-block mb-2 mr-0 mr-md-3"> <img src="../assets/images/users/4.jpg" alt="user" class="rounded-circle w-100"> <span class="profile-status rounded-circle offline"></span> </div>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="mb-0 mt-1">Pavan kumar</h5> <span class="font-12 text-nowrap d-block text-truncate mail-desc">Just see the my admin!</span> <span class="font-12 text-nowrap d-block time">9:02 AM</span> </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row -->
            </div>

            @endsection
@section('scripts')
<!--This page JavaScript -->
<script src="{{asset('assets/libs/moment/moment.js')}}"></script>
    <script src="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/pages/calendar/cal-init.js')}}"></script>
    <script src="{{asset('assets/libs/chartist/dist/chartist.min.js')}}"></script>
    <script src="{{asset('assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js')}}"></script>
    <script src="{{asset('assets/libs/echarts/dist/echarts.min.js')}}"></script>
    <script src="{{asset('assets/extra-libs/jvector/jquery-jvectormap-2.0.2.min.js')}}"></script>
    <script src="{{asset('assets/extra-libs/jvector/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{asset('assets/dist/js/pages/dashboards/dashboard4.js')}}"></script>
@endsection
