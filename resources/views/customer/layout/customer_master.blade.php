<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{csrf_token()}}" />
    
    <title>{{Session::get('site_title')}}</title>

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
    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/charts/apexcharts.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/extensions/toastr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/tables/datatable/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/forms/select/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/pickers/pickadate/pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/file-uploaders/dropzone.min.css')}}">
 
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <!-- END: Vendor CSS-->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/core/menu/menu-types/vertical-menu.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-invoice-list.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-calendar.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/form-validation.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-chat.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/pickers/form-pickadate.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/plugins/forms/form-file-uploader.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset( $file_path . 'app-assets/css/pages/app-chat-list.css')}}">
     <!-- Begin dashboard css-->
     <link rel="stylesheet" type="text/css" href="{{asset($file_path . 'app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
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

    @stack('css')
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

    <!-- BEGIN: Header-->
    <nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-light navbar-shadow container-xxl">
        <div class="navbar-container d-flex content">
            <div class="bookmark-wrapper d-flex align-items-center">
                <ul class="nav navbar-nav d-xl-none">
                    <li class="nav-item"><a class="nav-link menu-toggle" href="#"><i class="ficon" data-feather="menu"></i></a></li>
                </ul>
            </div>
            <ul class="nav navbar-nav align-items-center ms-auto">
                
                <li class="nav-item d-none d-lg-block"><a class="nav-link nav-link-style"><i class="ficon" data-feather="moon"></i></a></li>
               
                <li class="nav-item dropdown dropdown-notification me-25">
                    <a class="nav-link" href="#" data-bs-toggle="dropdown">
                        <i class="ficon" data-feather="bell"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-media dropdown-menu-end notif_div_ul">
                        <li class="dropdown-menu-header">
                            <div class="dropdown-header d-flex">
                                <h4 class="notification-title mb-0 me-auto">Notifications</h4>
                              
                                <div class="badge rounded-pill badge-light-primary"><a href="{{url('all-notifications')}}" class="small p-2">view all</a></div>
                            </div>
                        </li>
                        <li class="scrollable-container media-list message-center notifications">
                            
                        </li>
                        <li class="dropdown-menu-footer"><a class="btn btn-primary w-100" type="button" onclick="allRead()">Read all notifications</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown dropdown-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="user-nav d-sm-flex d-none">
                            <span class="user-name fw-bolder">{{ Auth::user()->name }}</span>
                            {{-- <span class="user-status">{{ Auth::user()->email }}</span> --}}
                        </div>
                        <span class="avatar">
                            @if(Auth::user()->profile_pic != null)
                                @if(file_exists( public_path(). $file_path . '/files/user_photos/'. \Auth::user()->profile_pic))
                                    <img src="{{ asset( $file_path . 'files/user_photos/'.\Auth::user()->profile_pic)}}"
                                        alt="'s Photo" class="rounded-circle" width="50px">
                                @else
                                    <img src="{{asset(  $file_path . 'default_imgs/logo.png')}}" width="50px" alt="'s Photo" class="rounded-circle">
                                @endif
                            @else
                                <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo"  width="50px" class="rounded-circle">
                            @endif
                            <span class="avatar-status-online"></span></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-user">
                        <a class="dropdown-item" href="{{url('my-profile')}}"><i class="me-50" data-feather="user"></i> Profile</a>
                        @if(Session::get('default_cmp_id') != 0 )
                            <a class="dropdown-item" href="{{url('company-profile')}}/{{Session::get('default_cmp_id')}}">
                                <i i class="me-50" data-feather="credit-card"></i> Company Profile </a>
                        @endif
                        
                        <a class="dropdown-item" type="button" href="{{ url('logout') }}">
                            <i class="me-50" data-feather="power" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"></i> 
                            Logout
                        </a>
                        <form id="logout-form" action="{{ url('logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!-- END: Header-->
    
    @include('customer.layout.new-sidebar')


    <div class="app-content content">
    <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
            <div class="content-wrapper container-xxl p-0">
                <div class="content-header row">
                    <div class="content-header-left col-md-12 col-12 mb-2">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h2 class="content-header-title float-start mb-0">Dashboard</h2>
                                <div class="breadcrumb-wrapper">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item active"><a href="">Profile</a>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-body">
    <!-- BEGIN: Content-->
      
           @yield('body')
      
    <!-- END: Content-->
    </div>
            </div>
        </div>
    </div>
</div>
    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    <!-- BEGIN: Footer-->
    <footer class="footer footer-static footer-light">
        {{Session::get('site_footer')}}
    </footer>
    <button class="btn btn-primary btn-icon scroll-top" type="button" style="background-color: #0075be !important">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
    </button>
    <!-- END: Footer-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{asset($file_path . 'app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->
    <script>
        const colorUrl = "{{asset('get-color')}}";
        const swal_message_time = 5000;
    </script>
    <!-- BEGIN: Page Vendor JS-->
    <script src="../../../app-assets/vendors/js/forms/spinner/jquery.bootstrap-touchspin.js"></script>

    <script src="{{asset($file_path . 'app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/vendors/js/extensions/moment.min.js')}}"></script>
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

    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

    <script src="{{asset($file_path . 'app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-chat.js')}}"></script>

    <!-- BEGIN: Page JS-->
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/dashboard-analytics.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-invoice-list.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/cards/card-statistics.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/tables/table-datatables-advanced.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/pages/app-calendar-events.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/ui/ui-feather.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/forms/form-select2.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/forms/pickers/form-pickers.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/components/components-navs.js')}}"></script>
    <script src="{{asset($file_path . 'app-assets/js/scripts/components/components-modals.js')}}"></script>
    <!-- END: Page JS-->

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
    @yield('scripts')
</body>
<!-- END: Body-->


</html>