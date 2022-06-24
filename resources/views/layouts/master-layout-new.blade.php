<!DOCTYPE html>
    @if(\Auth::user()->theme == "dark")
        <html class="loaded light-layout dark-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
    @else
        <html class="loaded light-layout" lang="en" data-layout="dark-layout" data-textdirection="ltr">
    @endif
<!-- BEGIN: Head-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{csrf_token()}}" />
    <title>@yield('title')</title>
    @php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp

    <!-- Favicon icon -->

        {{-- @if( Session::get('site_favicon') != null)
            @if(file_exists( public_path(). $file_path . Session::get('site_favicon') ) )
                <link rel="icon" type="image/png" sizes="16x16"
            href="{{asset($file_path . Session::get('site_favicon') ) }}">
            @else
                <img src="{{asset(  $file_path . 'default_imgs/favicon.png')}}" width="50px" alt="'s Photo" class="rounded-circle">
            @endif
        @else
            <img src="{{asset( $file_path . 'default_imgs/favicon.png')}}" alt="'s Photo"  width="50px" class="rounded-circle">
        @endif --}}

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/charts/apexcharts.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/extensions/toastr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
    <link rel="stylesheet" type="text/javascript" href="{{asset( $file_path . 'app-assets/js/scripts/components/components-tooltips.js')}}">

    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <!-- END: Vendor CSS-->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/tagsinput.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/themes/semi-dark-layout.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/imagePreview.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-user.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/core/menu/menu-types/vertical-menu.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-invoice-list.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-invoice.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/calendars/fullcalendar.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-calendar.css')}}"> -->



    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-chat.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/pickers/form-pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/form-file-uploader.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-chat-list.css')}}">
     <!-- Begin dashboard css-->
     <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/pages/ui-feather.css')}}">
     <!-- End dashboard css-->

    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/style.css')}}">
    <!-- END: Custom CSS-->


    <!-- BEGIN: Material Design CDNS -->
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
  <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <!-- END: Material Design CDNS-->

    <style>
    #clock_btny{
        color: #ea5455 !important;
    }
        .loading__ {
            background: white !important;
            width: 100%;
            height: 100%;
            top: 0px;
            right: 0px;
            position: absolute;
            z-index: 9;
            border-radius: 3px;
            opacity: 0.8;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        a {
            text-decoration: none !important;
        }

        .whitePlaceholder::-webkit-input-placeholder {
            color: white !important;
        }
        td.details-control {
            background: url('{{url($file_path . "default_imgs/details_open.png")}}') no-repeat center center !important;
            cursor: pointer;
            z-index: 9999 !important;
        }
        tr.shown td.details-control {
            background: url('{{url($file_path ."default_imgs/details_close.png")}}') no-repeat center center !important;
            cursor: pointer;
            z-index: 9999 !important;
        }
        table.dataTable th { padding: 20px 10px !important; vertical-align: middle !important; }
        .dark-layout .dataTables_wrapper .table.dataTable thead .sorting:before, .dark-layout .dataTables_wrapper .table.dataTable thead .sorting:after {
            opacity: 0.5; margin-top: 10px;
        }
    </style>

    @stack('css')
    @yield('customtheme')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->
<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">
    <input type="hidden" value="{{\Auth::user()->profile_pic}}" id="curr_user_image">
    <!-- BEGIN: Header-->
    <div class="audio" style="display:none">
        <audio id="msg_my_audio" class="msg_my_audio">
            <source src='{{asset($file_path . "assets/sound/message.mp3")}}' type="audio/mpeg">
        </audio>
    </div>
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-shadow container-fluid {{auth()->user()->theme == 'dark' ? 'navbar-dark' : 'navbar-light'}}">
        
        @if( session()->get('clockin') != 0 && session()->get('clockin') == NULL)
        <div class="d-flex w-100 fw-bolder clock_in_section">
            <h5 class="ms-1 fw-bolder text-danger">You are not clocked in!</h5>
            <h5 class="mx-2 fw-bolder text-danger">Do you wish to clock in Now?</h5>
            <div class="d-flex">
                <a href="#" class="mx-1 text-danger " id="clock_btny" onclick="sessionClockIn('clockin')"> Yes </a> | <a href="#" onclick="checkClockIn('ignore')" class="ms-1 text-danger" id="clock_btny">Ignore</a>
            </div>
        </div>
        @else
            <div class="showClockInSection w-100"></div>
        @endif
        <div class="loadingText mx-1 fw-bolder text-dark"></div>

        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                @if(\Auth::user()->theme == "dark")
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link nav-link-style"><i class="ficon" data-feather="sun"></i></a>
                    </li>
                @else
                    <li class="nav-item d-none d-lg-block">
                        <a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i>
                    </a></li>
                @endif

                <li class="nav-item dropdown dropdown-notification me-25">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown">
                        <i class="ficon" data-feather="bell"></i>
                        @if($numberAlerts > 0)
                            <span class="badge rounded-pill bg-danger badge-up noti_count" id="noti_count" >{{$numberAlerts}}</span>
                        @else
                            <span class="badge rounded-pill bg-danger badge-up noti_count" id="noti_count"></span>
                        @endif
                    </a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end notif_div_ul">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                                <div class="badge rounded-pill badge-light-primary bg-primary"><a href="{{url('all-notifications')}}" class="small p-2">view all</a></div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list ps notifications list_all_notifications">

                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" type="button" onclick="allRead()"> Mark all as read </a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-notification">
                    <a class="nav-link" href="{{route('chats.index')}}" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square" style="width: 1.5rem;height:1.5rem"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <span class="badge unread_msgs rounded-pill bg-danger badge-up" id="unread_msgs"></span>
                    </a>
                </li>
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none navUserInfo">
                            <span class="user-name fw-bolder">{{ Auth::user()->name }}</span>
                            @if(\Auth::user()->user_type == "1")
                                <span class="user-status">{{Auth::user()->role}}</span>
                            @else
                                <span class="user-status"></span>
                            @endif

                            @if( session()->get('clockin') == 1 || session()->get('clockin') != null && session()->get('clockin') != 0)
                            <span class="badge bg-success clockin_timer" style="margin-top:4px"></span>
                            @endif
                        </div>
                        <span class="avatar">
                            @if(auth()->user()->profile_pic != null)
                                @if(file_exists( getcwd(). '/' . auth()->user()->profile_pic))
                                    <img src="{{ asset( request()->root() .'/'. auth()->user()->profile_pic)}}"
                                        alt="'s Photo" class="rounded-circle" id="login_usr_logo" width="50px" height="50px">
                                @else
                                    <img src="{{asset(  $file_path . 'default_imgs/customer.png')}}" id="login_usr_logo" width="50px" height="50px" alt="'s Photo" class="rounded-circle">
                                @endif
                            @else
                                <img src="{{asset( $file_path . 'default_imgs/customer.png')}}" id="login_usr_logo" alt="'s Photo" height="50px"  width="50px" class="rounded-circle">
                            @endif
                            <span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{url('my-profile')}}"><i class="me-50" data-feather="user"></i> Profile</a>
                        @if(Session::get('default_cmp_id') != 0 )
                            <a class="dropdown-item" href="{{url('company-profile')}}/{{Session::get('default_cmp_id')}}">
                                <i i class="me-50" data-feather="credit-card"></i> Company Profile </a>
                        @endif
                        <a class="dropdown-item" onclick="run_parser()"><i class="me-50" data-feather="refresh-ccw"></i> Run Parser</a>

                        <a class="dropdown-item" type="button" href="{{ route('logout') }}">
                            <i class="me-50" data-feather="power"></i>  Logout
                        </a>

                    </div>
                </li>
            </ul>
        </div>
    </nav>

    
    <!-- END: Header-->
    @include('layouts.new-sidebar')

    <!-- BEGIN: Content-->

           @yield('body')

    <!-- END: Content-->

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <div id="image-viewer">
        <span class="close">&times;</span>
        <img class="modal-content" id="full-image">
    </div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        {{Session::get('site_footer')}}
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button" style="background-color: #0075be !important"><i data-feather="arrow-up"></i></button>
    <!-- END: Footer-->

    <!-- BEGIN: Vendor JS-->
    <script src="{{asset($file_path . 'app-assets/vendors/js/vendors.min.js')}}"></script>

    <!-- BEGIN Vendor JS-->
    
    <script>
        const colorUrl = "{{asset('get-color')}}";
        const swal_message_time = 5000;
    </script>
    <!-- BEGIN: Page Vendor JS-->
    {{-- phone validation --}}
    <script src="{{asset($file_path . 'app-assets/jquery.caret.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/jquery.mobilePhoneNumber.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
    <!-- <script src="{{asset($file_path . 'app-assets/vendors/js/extensions/moment.min.js')}}"></script> -->
    <script src="{{asset($file_path . 'app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/forms/select/select2.full.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/pickers/pickadate/picker.time.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/file-uploaders/dropzone.min.js')}}"></script>

    <!-- <script src="{{asset($file_path . 'app-assets/vendors/js/calendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar.js')}}"></script> -->

    <script src="{{asset($file_path . 'app-assets/js/scripts/imagePreview.js')}}"></script>

    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-storage.js"></script>

    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/components/components-popovers.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-chat.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/tagsinput.js')}}"></script>

    <!-- BEGIN: Page JS-->
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-invoice.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-user-list.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/charts/chart-apex.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/dashboard-analytics.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-invoice-list.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/cards/card-statistics.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/tables/table-datatables-advanced.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/ui/ui-feather.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/forms/form-select2.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/components/components-navs.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/components/components-modals.js')}}"></script>
    <!-- END: Page JS-->

    <script src="{{asset($file_path . 'app-assets/js/scripts/extensions/ext-component-toastr.js')}}"></script>

    <script type="text/javascript" src="{{asset($file_path . 'assets/dist/js/flashy.min.js')}}"></script>
    <script type="text/javascript" src="{{asset($file_path . 'assets/extra-libs/countdown/countdown.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-precise-range-plugin@1.3.0/moment-precise-range.js"></script>
    <script>
        const org_path = "{{Session::get('is_live')}}";
        const root = "{{request()->root()}}";
        const js_origin  = root + (org_path == 1 ? '/public/' : '/');
        const change_theme_url = "{{asset('change_theme_mode')}}";

        $(document).ready(function() {
            $(document).on('select2:open', () => {
                document.querySelector('.select2-search__field').focus();
            });
            getAllCounts();
            getNotifications();
            getUnreadMessages();
            $(this).find(".slogan_i_minus").hide();
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        });
        $(document).ready(function (){

            setInterval(() => {
                LightAndDarkThemeSetting();
            }, 1000);
        });
        var user_photo_url = "{{asset('files/user_photos')}}";
        var url = "{{asset('/get_all_counts')}}";
        var unreadMsg = "{{route('unread.message')}}";
        var get_notifications = "{{url('getNotifications')}}";
        var parser_url = "{{url('save-inbox-replies')}}";
        let clockintime = "{{session()->get('clockin_time')}}";
        let clockinStatus = "{{session()->get('clockin')}}";

        function sendNotification(type,slug,icon,title,description) {
            $.ajax({
                type: 'POST',
                url: "{{url('send_notification')}}",
                data: {
                    type:type,
                    slug:slug,
                    icon:icon,
                    title: title,
                    description: description},
                success: function(data) {
                    // console.log(data);
                    if(!data.success) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });
        }

        function getAllCounts(){
            $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                cache: false,
                async:false,
                success: function(data) {
                    // console.log(data , 'data counts');
                    let counts = data.counts;
                    if(counts != undefined) {
                        for(var i = 0 ; i < counts.length ; i++){
                            // console.log(counts[i].dept_counter)
                            if(counts[i].dept_counter == 1){
                                if(counts[i].tkt_dept_count > 0){
                                    $('#dept_cnt_'+counts[i].id).html(counts[i].tkt_dept_count);
                                }
                            }
                            if(counts[i].status_counter == 1){
                                if(counts[i].tkt_sts_count > 0 && counts[i].sts_name != 'Closed'){
                                    $('#sts_cnt_'+counts[i].id+'_'+counts[i].sts_id).html(counts[i].tkt_sts_count);
                                }
                            }
                        }
                    }


                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });

        }

        function getUnreadMessages(){
            $.ajax({
                url: unreadMsg,
                type: "get",
                dataType: 'json',
                cache: false,
                async:false,
                success: function(data) {
                    let counts = data.counts;
                    if(counts != undefined) {
                        if(counts > 0){
                            $("#unread_msgs").text(counts);
                            $("#unread_msgs").removeClass('d-none');
                        }else{
                            $("#unread_msgs").addClass('d-none');
                        }
                    }
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });

        }

        function run_parser(){
            $.ajax({
                url: parser_url,
                type: "get",
                dataType: 'json',
                cache: false,
                async:false,
                success: function(data) {
                    // console.log(data+' parser')
                    if(data.status_code == 200){
                        toastr.success(data.message, { timeOut: 5000 });
                    }else{
                        toastr.error(data.message, { timeOut: 5000 });
                    }

                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });
        }

        function allRead(){
            $.ajax({
                url: "{{url('mark_all_read')}}",
                type: "get",
                dataType: 'json',
                cache: false,
                async:false,
                success: function(data) {
                    getNotifications();
                    $('.list_all_notifications').remove();
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });

        }

        $('.sidebar-link').click(function(){

            if($(this).parent().hasClass('open')){
                $(".slogan_i_minus").hide()
                $(".slogan_i_plus").show()
                $(this).find(".slogan_i_plus").show();
                $(this).find(".slogan_i_minus").hide();
            }
            else{
                $(".slogan_i_minus").hide()
                $(".slogan_i_plus").show()
                $(this).find(".slogan_i_plus").hide();
                $(this).find(".slogan_i_minus").show();

            }
        });

        function getNotifications(){
            $.ajax({
                url: get_notifications,
                type: "get",
                dataType: 'json',
                cache: false,
                async:false,
                success: function(data) {
                    // console.log(data , "notification");
                    var noti_div = ``;
                    var sender = data.data;

                    var curr_user_image = $("#curr_user_image").val();
                    var user_image = ``;
                    var default_icon = ``;

                    $('.version_title').text(data.system_version);

                    if(data.status_code == 200 && data.success == true){

                        console.log(data.staff_clock_in ,"abcqweoqwiueyr");
                        renderClockIn(data.staff_clock_in);

                        notifications = data.data;
                        let count = data.total_notification;

                        $(".noti_count").addClass('badge rounded-pill bg-danger badge-up ');
                        $(".noti_count").text( count );

                        if(notifications.length > 0) {
                            for(var i = 0 ; i < notifications.length ; i++){


                                if(notifications[i].noti_desc != "") {                                

                                    if(notifications[i].sender != null) {

                                        if(notifications[i].sender.profile_pic != null ) {
                                            user_image = `<img src="${root}/${notifications[i].sender.profile_pic}" alt="avatar" width="32" height="32">`;
                                        }else{
                                            user_image = `<img src="${root}/default_imgs/customer.png" alt="avatar" width="32" height="32">`;
                                        }
                                    }else{
                                        user_image = `<img src="${root}/default_imgs/customer.png" alt="avatar" width="32" height="32">`;
                                    }

                                    var date = new Date(notifications[i].created_at);

                                    default_icon = `<span class="`+notifications[i].btn_class+` rounded-circle btn-circle"" style="padding:8px 12px">
                                                    <i data-feather='${notifications[i].noti_icon}'></i>
                                                    </span>`;

                                    var icon = 'fa fa-link';
                                    noti_div += `<div class="list-item d-flex align-items-start" onclick="markRead(`+notifications[i].id+`)" style ="cursor:pointer">
                                                <div class="me-1">
                                                    <div class="avatar">
                                                        ${notifications[i].noti_type == "attendance" ? user_image : default_icon}
                                                    </div>
                                                </div>
                                                <div class="list-item-body flex-grow-1">
                                                    <p class="media-heading">
                                                    <span class="fw-bolder">${notifications[i].noti_title != null ? notifications[i].noti_title : 'Notification'}</span>
                                                    <span class="float-end">${moment(notifications[i].created_at, "h:mm A").format("h:mm A")}</span> </p>
                                                    <small class="notification-text">${notifications[i].noti_desc != null ? notifications[i].noti_desc : 'Notification Desc'}</small>
                                                </div>
                                            </div>`;
                                }
                            }
                            $('.notifications').append(noti_div)

                        }
                        else{
                            $(".noti_count").removeClass('badge rounded-pill bg-danger badge-up ');
                            $(".noti_count").text('');
                            noti_div = `<li>
                                            <span class="font-12 text-nowrap d-block text-muted text-truncate p-2" style="text-align:center">No Unread Notifications.</span>
                                        </li>`;
                            $('.notifications').html(noti_div)

                        }
                    }


                },
                failure: function(errMsg) {

                    console.log(errMsg);
                }
            });
        }

        function LightAndDarkThemeSetting() {
            if ($(".loaded ").hasClass('dark-layout')) {
                $("#tsearch").css('border','1px solid white');
                $("#csearch").css('border','1px solid white');

                $("#tsearch").addClass("whitePlaceholder");
                $("#csearch").addClass("whitePlaceholder");
            }else{
                $("#tsearch").css('border','1px solid #d8d6de');
                $("#csearch").css('border','1px solid #d8d6de');

                $("#tsearch").removeClass("whitePlaceholder");
                $("#csearch").removeClass("whitePlaceholder");
            }
        }

        function renderClockIn(data) {
            // if data is null then its clock in 
            // alert("a");
            if(data == null || data.clock_out != null) {

                let clockinbtn = `
                <button type="button" class="btn btn-success waves-effect waves-float waves-light clock_btn ml-1" onclick="staffatt('clockin' , this)">
                    <i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock In</button>`;
                $('.clock_btn_div ').html(clockinbtn);
                $('.clock_in_section').show();
                $('.clockin_timer').hide();

                let clockSection = `<div class="d-flex w-100 fw-bolder clock_in_section">
                    <h5 class="ms-1 fw-bolder text-danger">You are not clocked in!</h5>
                    <h5 class="mx-2 fw-bolder text-danger">Do you wish to clock in Now?</h5>
                    <div class="d-flex">
                        <a href="#" class="mx-1 text-danger" id="clock_btny" onclick="sessionClockIn('clockin')"> Yes </a> | <a href="#" onclick="checkClockIn('ignore')" class="ms-1 text-danger" id="clock_btny">Ignore</a>
                    </div>
                </div>`;


                if(clockinStatus != 'ignore') {
                    $('.showClockInSection').html(clockSection);
                }                

            }else{

                let clockoutbtn = `<button type="button" class="btn btn-danger clock_btn" onclick="staffatt('clockout', this)"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>`;
                $('.clock_btn_div').html(clockoutbtn);
                $('.clock_in_section').attr('style','display:none !important');

                if($('.navUserInfo').children().length == 2) {
                    $(".user-status").after(`<span class="badge bg-success clockin_timer" style="margin-top:4px"></span>`);
                    clockintime = moment(data.clock_in , "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
                    clockInTimer(clockintime);
                }else{
                    clockintime = moment(data.clock_in , "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
                    clockInTimer(clockintime);
                }
            }
        }

        function checkClockIn(type) {
            $.ajax({
                url: "{{route('session.clockin')}}",
                type: "POST",
                dataType: 'json',
                data : {type : type},
                success: function(data) {
                    console.log(data , "data");
                    if(data.status == 200 && data.success == true) {
                        toastr.success( data.message , { timeOut: 5000 });


                        if(type != 'yes') {
                            $('.clockin_timer').hide();
                            $('.clock_in_section').attr('style','display:none !important');
                        }
                    }
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });
        }

        function sessionClockIn(btn_text) {
            $.ajax({
                url: "{{asset('add_checkin')}}",
                type: 'POST',
                async: true,
                beforeSend:function(data) {
                    $(".loadingText").text(`Please wait..`);
                    $(".loadingText").addClass(`w-50`);
                    $('.clock_in_section').attr('style','display:none !important');
                },
                success: function(data) {
                    console.log(data);

                    if (data.success == true) {
                        $('.clock_btn').remove();

                        let btn = `<button type="button" class="btn btn-danger clock_btn" onclick="staffatt('clockout', this)"><i class="fa fa-clock" aria-hidden="true"></i>&nbsp;Clock Out</button>`;
                        $(".user-status").after(`<span class="badge bg-success clockin_timer" style="margin-top:4px"></span>`);
                        $('.clock_btn_div').append(btn);

                        var curr_user_name = $("#curr_user_name").val();
                        var system_date_format = $("#system_date_format").val();
                        var today = new Date();
                        let time = moment(today).format('h:mm:ss');
                        let date = moment(today).format(system_date_format);

                        let clock_out_time = ``;
                        
                        if( data.hasOwnProperty('clock_out_time') ) {
                            clock_out_time =convertDate( data.clock_out_time );
                        }else{
                            clock_out_time = `-`;
                        }

                        let clock_in_time = ``;
                        let clock_in = ``;

                        if(btn_text == 'clockin') {
                            clock_in_time = convertDate(new Date());
                            clock_in = `<span class="badge bg-success">Clocked In</span>`;
                        }else{
                            clock_in_time = convertDate( data.clock_in_time );
                            clock_in = `<span class="badge bg-danger">Clocked Out</span>`;
                        }

                        let working_hour = data.hasOwnProperty('worked_time');;

                        if(working_hour) {
                            working_hour = data.worked_time;
                        }else{
                            working_hour = `-`;
                        }

                        let url = window.location.href;

                        if(url.includes('home')) {
                            $("#staff_table tbody").append(
                            `<tr id="new_entry">
                                <td></td>
                                <td>${curr_user_name} </td>
                                <td>${clock_in}</td>
                                <td>${date}</td>
                                <td>${clock_in_time}</td>
                                <td>${clock_out_time}</td>
                                <td>${working_hour}</td>
                            </tr>`);
                        }

                        if(data.status_code == 201) {
                            toastr.warning(data.message, { timeOut: 5000 });
                        } else {
                            toastr.success(data.message, { timeOut: 5000 });
                        }

                        clockintime = moment(data.clock_in_time , "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
                    } else {
                        toastr.error(data.message, {
                            timeOut: 5000
                        });
                    }
                },
                complete:function(data) {
                    $(".loadingText").text(``);
                },
                error: function(data) {
                    $('.clock_in_section').attr('style','display:block !important');
                    console.log(data);
                    toastr.error(data.message, {
                        timeOut: 5000
                    });
                }
            });
        }    
        
        function clockInTimer(clockintime) {
            let clock_in_time = moment(clockintime , "YYYY-MM-DD HH:mm:ss").format("YYYY-MM-DD HH:mm:ss");
            let today = moment.utc().format("YYYY-MM-DD HH:mm:ss");
            let ms = moment(today,"YYY-MM-DD HH:mm:ss").diff(moment(clock_in_time,"YYY-MM-DD HH:mm:ss"));
            let d = moment.duration(ms);
            if(d._data != null) {
                let min = d._data.minutes > 9 ? d._data.minutes : '0' + d._data.minutes;
                let sec = d._data.seconds > 9 ? d._data.seconds : '0' + d._data.seconds;
                let hr = d._data.hours > 9 ? d._data.hours : '0' + d._data.hours;
                let days = d._data.days;
                if(days > 0){
                    let hours = days * 24;
                    hr = parseInt(hours) + parseInt(hr);
                }
                let time = hr + ':' + min + ':' + sec;
                $('.clockin_timer').text(time);
            }
        }

        window.setInterval(() => {
            clockInTimer(clockintime);
        }, 100);
        
        function alertNotification(type , heading , message) {
            toastr[type]( message , heading , {
                positionClass: 'toast-bottom-right',
                progressBar: true,
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                timeOut: 10000,
            });
        }
    </script>
    @include('js_files.chat.pusher')
    @include('js_files.pusher_notification.notification')
    @include('js_files.pusher_notification.user_status')
    @yield('scripts')
</body>
</html>
