<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{csrf_token()}}" />
    
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{asset('public/files/brand_files')}}/{{Session::get('site_favicon')}}">
    <title>{{Session::get('site_title')}}</title>

    <!-- Custom CSS -->
    {{-- <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet"> --}}
    <link href="{{asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/jsgrid/jsgrid.min.css')}}" rel="stylesheet">
    <link href="{{asset('assets/libs/jsgrid/jsgrid-theme.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
        href="{{asset('assets/libs/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/libs/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/dist/css/flashy.min.css')}}">

    <link href="{{asset('css/style.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/custom_css.css')}}" rel="stylesheet">
    <link href="{{asset('css/tagsinput.css')}}" rel="stylesheet" type="text/css">
    <!-- <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"> -->

    

    <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">
    <link rel="stylesheet" href="{{asset('/assets/libs/datatables/media/css/jquery.dataTables.min.css')}}">


    <link rel="stylesheet" href="{{asset('public/toastr/toastr.min.css')}}">

 <!-- This page CSS -->
 <link href="{{asset('/assets/extra-libs/jquery-steps/jquery.steps.css')}}" rel="stylesheet">
    <link href="{{asset('/assets/extra-libs/jquery-steps/steps.css')}}" rel="stylesheet">
    {{-- font awesome --}}
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>

    <!-- this link for toggel-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">
    <script src="{{asset('assets/libs/jquery/dist/jquery.min.js')}}"></script>
    
    
    <style>
        body[data-theme=dark] #main-wrapper, body[data-theme=dark] .footer, body[data-theme=dark] .blacknav,body[data-theme=dark] .top-navbar,body[data-theme=dark] .navbar,body[data-theme=dark] .nav-link, body[data-theme=dark] .collapse, body[data-theme=dark]  .nav-item, body[data-theme=dark]  #main-wrapper[data-layout=vertical] .topbar .navbar-collapse[data-navbarbg=skin1], body[data-theme=dark]  .navbar-collapse, body[data-theme=dark] .breadcrumb,body[data-theme=dark] .page-breadcrumb, body[data-theme=dark] .cke_toolbar_separator, body[data-theme=dark] .dropdown-menu, body[data-theme=dark] .email-app .list-group .list-group-item .list-group-item-action.active, body[data-theme=dark] .email-app .list-group .list-group-item .list-group-item-action:hover, body[data-theme=dark] .jumbotron, body[data-theme=dark] .page-wrapper, body[data-theme=dark] .progress, body[data-theme=dark] .wizard-content .wizard>.steps .step, body[data-theme=dark] .wizard:not(.wizard-circle)>.actions .disabled a, body[data-theme=dark] .wizard:not(.wizard-circle)>.actions .disabled a:active, body[data-theme=dark] .wizard:not(.wizard-circle)>.actions .disabled a:hover, body[data-theme=dark] .wizard:not(.wizard-circle)>.content, body[data-theme=dark] .wizard:not(.wizard-circle)>.steps .disabled a, body[data-theme=dark] .wizard:not(.wizard-circle)>.steps .disabled a:active, body[data-theme=dark] .wizard:not(.wizard-circle)>.steps .disabled a:hover {
    background-color: #323743 !important;
    color: #d2dae0 !important;
    font-family: Rubik,sans-serif;
}
body[data-theme=dark] .left-sidebar :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
    color: #d2dae0 !important;
}
    .dd-item.black button:before,
    .breadcrumb.black>.breadcrumb-item:before {
        color: #d2dae0 !important;
    }

    input,
    textarea,
    select,
    .select2-selection--multiple {
        border-color: #848484 !important;
    }

    th {
        font-size: 14px !important;
        padding: 5px !important;
        }
    body[data-theme=dark] .even td,
    body[data-theme=dark] .even td :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
        background-color:#fff !important;
        color:#252629  !important;
        
    }
    body[data-theme=dark] .odd td,
    body[data-theme=dark] .odd td :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
        background-color:#252629  !important;
        color:#fff ;
        
    }
    body[data-theme=dark] .ms-drop ul{
        color: #252629 !important;
    }
    body[data-theme=dark] .menu_active,
    body[data-theme=dark] .nav-pills .nav-link.active,
    {
    background-color: #323743  !important;
    color: #fff !important;
}
body[data-theme=dark] .close{
    color:#b2b9bf !important;
}
    .notify{
        /* position:inherit; */
        margin-top: -20px;
        right: -16px;

    }

    .badge-light{
        /* width: 6px;
        height: 6px;
      */
      position: absolute;
    top: 15px;
    right: 4px;
        border-radius: 42%;
        background: repeating-linear-gradient(45deg , black, transparent 100px);
        font-weight: 600;
        font-size:58%;
        color:white
    }
    /* loader code start */
    .loader_container{
        width:100%;
        height:100%;
        background-color:#cfd8dc;
        opacity:0.7;
        position:absolute;
        top:0px;right:0px;
        display:flex;
        align-items:center;
        justify-content:center;
        z-index:10;
    }
    .loader {
        width:50px;
        height:50px;
        border:5px solid;
        color:#111;
        border-radius:50%;
        border-top-color:transparent;
        animation:loader 0.5s linear infinite;
    }
    @keyframes loader {
        25%{
        color:#ffa726;
        }
        50%{
        color:#d84315;
        }
        75%{
        color:#1565c0;
        }
        100%{
        color:#2e7d32;
        }
        to{
        transform:rotate(360deg);
        }
    }
    #toggle_checkbox
{
    display: none;
}

.switch
{
    display: block;
    top: 33%;
    right: 0;
    left: 0;
    width: 90px;
    height: 34px;
    margin: 0 auto;
    background-color: #0383ba;
    border-radius: 56px;
    transform: translateY(-50%);
    cursor: pointer;
    transition: 0.3s ease background-color;
    overflow: hidden;
}

#star
{
    position: absolute;
    top: 3px;
    left: 10px;
    width: 30px;
    height: 0;
    /* background-color: #fff; */
    transform: scale(1);
    border-radius: 50%;
    transition: 0.3s ease top, 0.3s ease left, 0.3s ease transform, 0.3s ease background-color;
    z-index: 1;
}

#star-1
{
    position: relative;
}

#star-2
{
    position: absolute;
    transform: rotateZ(36deg);
}

.star
{
    top: 0;
    left: -7px;
    font-size: 29px;
    line-height: 28px;
    color: #fff;
    transition: 0.3s ease color;
}
#moon {
    position: absolute;
    bottom: -52px;
    right: 8px;
    width: 28px;
    height: 29px;
    background-color: #fff;
    border-radius: 50%;
    transition: 0.3s ease bottom;
}
#moon:before
{
    content: "";
    position: absolute;
    top: -12px;
    left: -17px;
    width: 39px;
    height: 38px;
    background-color: #03a9f4;
    border-radius: 50%;
    transition: 0.3s ease background-color;
}

#toggle_checkbox:checked + label
{
    background-color: #000;
}
#toggle_checkbox:checked + label #star {
    top: 3px;
    left: 53px;
    transform: scale(0.3);
    /* background-color: yellow; */
}
#toggle_checkbox:checked + label .star
{
    color: yellow;
}

#toggle_checkbox:checked + label #moon
{
    bottom: 4px;
}

#toggle_checkbox:checked + label #moon:before
{
    background-color: #000;
}
#main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
    margin-left: 0 !important;
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
    /* loader code ends */
    </style>
    @stack('css')
    @yield('customtheme')
</head>

<body data-theme="{{ \Auth::user()->theme}}">
    <div id="main-wrapper">

        <header class="topbar">
            <nav class="top-navbar navbar navbar-expand-md navbar-dark">
                <div class="navbar-nav">
                    <a class="navbar-brand sidebartoggler waves-effect waves-light"> <img style="padding-left:15px; height: 50px;"
                            id="logo_image" src="{{asset('files/brand_files/')}}/{{Session::get('site_logo')}}"
                            alt="{{Session::get('site_title')}}" class="dark-logo" />&nbsp;<span
                            id="logo_title">{{Session::get('site_logo_title')}}</span><span id="version"></span></a>
                    <!-- <a class="nav-link sidebartoggler waves-effect waves-light mt-2" href="javascript:void(0)"
                        data-sidebartype="mini-sidebar">
                        <i class="icon-arrow-left-circle"
                            style="line-height:inherit !important;display:none;"></i>
                            <i class="icon-arrow-right-circle"
                            style="line-height:inherit !important;display:none;"></i>
                            </a> -->
                </div>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item d-none d-md-block" style="padding-top: 18px;">
                            <!-- <label class="switch">
                                <input type="checkbox" id="togBtn" class=""
                                    {{ \Auth::user()->theme == 'light' ? 'checked': ''}}>
                                <div class="slider round">
                                    ADDED HTML -->
                                    <!-- <span class="on">Light</span>
                                    <span class="off">Dark</span> -->
                                    <!--END-->
                                <!--</div>
                            </label> -->
                            <input type="checkbox" id="toggle_checkbox"  {{ \Auth::user()->theme == 'dark' ? 'checked': ''}}>

                            <label class="switch" for="toggle_checkbox">
                            <div id="star">
                                <div class="star" id="star-1">★</div>
                                <div class="star" id="star-2">★</div>
                            </div>
                            <div id="moon"></div>
                            </label>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell" style="font-size:25px;"></i>
                                @if($numberAlerts > 0)
                                    <span class="badge badge-light" id="noti_count" >{{$numberAlerts}}</span> 
                                @else    
                                    <span class="badge badge-light" id="noti_count" style="display:none"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu mailbox animated flipInY" style="margin-right:-260px !important">
                                <ul class="list-style-none notif_div_ul">
                                    <li>
                                        <div class="font-weight-medium border-bottom rounded-top py-3 px-4">
                                            Notifications
                                        </div>
                                    </li>
                                    <li>
                                        <div class="message-center notifications position-relative"
                                            style="height:250px;">
                                            <!-- Message Coming from js -->
                                            
                                        </div>
                                    </li>
                                    <!-- <li>
                                        <a class="nav-link border-top text-center text-dark pt-3"
                                            href="#"> <strong>Check all notifications</strong> <i
                                                class="fa fa-angle-right"></i> 
                                        </a>
                                    </li> -->
                                    
                                    
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href=""
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img
                                    src="{{ \Auth::user()->profile_pic ? URL::asset('files/user_photos/'.\Auth::user()->profile_pic): URL::asset('files/user_photos/user-photo.jpg')}}"
                                    alt="'s Photo" class="rounded-circle"
                                    style="width:50px;height:50px; margin: 0px auto !important"></a>
                                    <!-- <a href="#">{{ Auth::user()->name }}</a> -->
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 mb-2 text-white" style="background-color: #009efb;">
                                    <div class=""><img
                                            src="{{ \Auth::user()->profile_pic ? URL::asset('files/user_photos/'.\Auth::user()->profile_pic): URL::asset('files/user_photos/user-photo.jpg')}}"
                                            alt="'s Photo" class="rounded-circle" width="80" height="80"></div>
                                    <div class="ml-2">
                                        <h4 class="mb-1 mt-2 lead  text-white "> {{ Auth::user()->name }}</h4>
                                        <small class="mb-0" style="word-break:break-all;">{{ Auth::user()->email }}</small>
                                        <!-- <a href=""
                                            class="btn btn-rounded btn-danger btn-sm"></a> -->
                                    </div>
                                </div>
                                <a class="dropdown-item" href="{{url('my-profile')}}"><i class="ti-user mr-1 ml-1"></i> My Profile </a>
                                @if(Session::get('default_cmp_id') != 0 )
                                    <a class="dropdown-item" href="{{url('company-profile')}}/{{Session::get('default_cmp_id')}}"><i class="ti-wallet mr-1 ml-1"></i> Company Profile </a>
                                @endif
                                
                                <!--<a class="dropdown-item" href="javascript:void(0)"><i class="ti-wallet mr-1 ml-1"></i> My Balance</a>-->
                                <!--<a class="dropdown-item" href="javascript:void(0)"><i class="ti-email mr-1 ml-1"></i> Inbox</a>-->
                                <!--<div class="dropdown-divider"></div>-->
                                <!--<a class="dropdown-item" href="javascript:void(0)"><i class="ti-settings mr-1 ml-1"></i> Account Setting</a>-->
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item border-bottom" href="{{ url('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off mr-1 ml-1"></i> {{ __('Logout') }}
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
        </header>


        <div class="page-wrapper">

            @yield('body-content')
            <div><div id="proactivechatcontainerdsswausqds"></div><table border="0" cellspacing="2" cellpadding="2" style="margin-left:auto;margin-right: 30px;"><tr><td align="center" id="swifttagcontainerdsswausqds"><div style="display: inline;" id="swifttagdatacontainerdsswausqds"></div></td> </tr><tr><td align="center"><div style="MARGIN-TOP: 2px; WIDTH: 100%; TEXT-ALIGN: center;"><span style="FONT-SIZE: 9px; FONT-FAMILY: Tahoma, Arial, Helvetica, sans-serif;"><a href="http://www.kayako.com/products/live-chat-software/?utm_source=support.mylive-tech.com&utm_medium=chat&utm_content=powered-by-kayako-help-desk-software&utm_campaign=product_links" style="TEXT-DECORATION: none; COLOR: #000000" target="_blank">Live Chat Software</a><span style="COLOR: #000000"> by </span>Kayako</span></div></td></tr></table></div> <script type="text/javascript">var swiftscriptelemdsswausqds=document.createElement("script");swiftscriptelemdsswausqds.type="text/javascript";var swiftrandom = Math.floor(Math.random()*1001); var swiftuniqueid = "dsswausqds"; var swifttagurldsswausqds="https://support.mylive-tech.com/visitor/index.php?/LiveTech/LiveChat/HTML/HTMLButton/cHJvbXB0dHlwZT1jaGF0JnVuaXF1ZWlkPWRzc3dhdXNxZHMmdmVyc2lvbj00LjkzLjAmcHJvZHVjdD1mdXNpb24mZmlsdGVyZGVwYXJ0bWVudGlkPTEzLDE1LDcsOSZza2lwdXNlcmRldGFpbHM9MSZjdXN0b21vbmxpbmU9JmN1c3RvbW9mZmxpbmU9JmN1c3RvbWF3YXk9JmN1c3RvbWJhY2tzaG9ydGx5PQo2MTU0NzIyYWU1MTQ1MDczNTU0ZGUxMGFlOTNhODIxY2Q5ZGQ4MjZi";setTimeout("swiftscriptelemdsswausqds.src=swifttagurldsswausqds;document.getElementById('swifttagcontainerdsswausqds').appendChild(swiftscriptelemdsswausqds);",1);</script><!-- END TAG CODE - DO NOT EDIT! -->
            <footer class="footer" id="footer">
                {{Session::get('site_footer')}}
                <!-- BEGIN TAG CODE - DO NOT EDIT! -->
            </footer>

        </div>

    </div>
    <script>
        const colorUrl = "{{asset('get-color')}}";
        const swal_message_time = 5000;
    </script>
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->

    <!-- Bootstrap tether Core JavaScript -->
    <script src="{{asset('/assets/libs/popper.js/dist/umd/popper.min.js')}}"></script>
    <script src="{{asset('/assets/libs/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- apps -->
    <script src="{{asset('/js/app.min.js')}}"></script>
    <script src="{{asset('/js/app.init.js')}}"></script>
    <script src="{{asset('/js/app-style-switcher.js')}}"></script>
    <script src="{{asset('/js/app-color.js')}}"></script>

    <script src="{{asset('public/toastr/toastr.min.js')}}"></script>
    <script src="{{asset('public/toastr/toastr-init.js')}}"></script>

    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="{{asset('/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js')}}"></script>
    <script src="{{asset('/assets/libs/jquery-sparkline/jquery.sparkline.min.js')}}"></script>
    <!--Wave Effects -->
    <script src="{{asset('/js/waves.js')}}"></script>
    <!--Menu sidebar -->
    <script src="{{asset('/js/sidebarmenu.js')}}"></script>
    <!--Custom JavaScript -->
    <script src="{{asset('/js/feather.min.js')}}"></script>
    <script src="{{asset('/js/custom.min.js')}}"></script>
    <script src="{{asset('/js/pages/chat/chat.js')}}"></script>
   

    <script src="{{asset('/assets/libs/d3/dist/d3.min.js')}}"></script>
    <script src="{{asset('/assets/libs/c3/c3.min.js')}}"></script>
    <!--<script src="dist/js/pages/dashboards/dashboard1.js"></script>-->
    <script src="{{asset('/assets/libs/datatables/media/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/pages/datatable/custom-datatable.js')}}"></script>
    <script src="{{asset('/js/pages/datatable/datatable-basic.init.js')}}"></script>
    <script src="{{asset('/assets/libs/nestable/jquery.nestable.js')}}"></script>


    <script src="{{asset('/assets/libs/select2/dist/js/select2.full.min.js')}}"></script>
    <script src="{{asset('/assets/libs/select2/dist/js/select2.min.js')}}"></script>
    <script src="{{asset('/js/pages/forms/select2/select2.init.js')}}"></script>
    <script src="{{asset('/js/moment.js')}}"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js"></script>
    <script src="{{asset('/assets/dist/js/firebase.js')}}"></script>
    

    <!-- <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script> -->
 <!-- Map JS -->
 <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDoliAneRffQDyA7Ul9cDk3tLe7vaU4yP8"></script> -->
    <!-- <script src="{{asset('/assets/libs/gmaps/gmaps.min.js')}}"></script> -->
    <!-- <script src="{{asset('/assets/dist/js/pages/maps/map-google.init.js')}}"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script> --}}
    <!-- <script type="text/javascript" src="dist/js/flashy.min.js"></script> -->
    <!--Custom JavaScript -->
    <script src="{{asset('/assets/dist/js/feather.min.js')}}"></script>
    <script src="{{asset('/assets/dist/js/custom.min.js')}}"></script>
    <script src="{{asset('/assets/libs/jquery-steps/build/jquery.steps.min.js')}}"></script>
    <script src="{{asset('/assets/libs/jquery-validation/dist/jquery.validate.min.js')}}"></script>
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var usertheme = @json(\Auth::user()->theme);
    let notifications;
       

    $(function() {
        // Nestable
        var updateOutput = function(e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };

        $('#nestable').nestable({
            group: 1
        }).on('change', updateOutput);

        $('#nestable2').nestable({
            group: 1
        }).on('change', updateOutput);

        // updateOutput($('#nestable').data('output', $('#nestable-output')));
        // updateOutput($('#nestable2').data('output', $('#nestable2-output')));

        $('#nestable-menu').on('click', function(e) {
            var target = $(e.target),
                action = target.data('action');
            if (action === 'expand-all') {
                $('.dd').nestable('expandAll');
            }
            if (action === 'collapse-all') {
                $('.dd').nestable('collapseAll');
            }
        });

        $('#nestable-menu').nestable();
    });

    $(document).ready(function() {
        getNotifications();

        if (usertheme == 'light') {

            $('body').attr("data-theme", 'light');
            $(".sidna").removeClass("blacknav");
            $('body').removeClass("blacknav");
            $(".left-sidebar").removeClass("blacknav");
            $(".sidebar-footer").removeClass("blacknav");
            $(".sidebar-link").removeClass("blacknav");
            $(".sidebar-footer").removeClass("sidebar-footer-black");
            $(".dd-item").removeClass("black");
            $(".breadcrumb").removeClass("black");
            $('input[type="date"]').removeClass('dark-calendar');
            light("{{Session::get('text_light')}}", "{{Session::get('bg_light')}}");

        } else {

            $('body').attr("data-theme", 'dark');
            $(".sidna").addClass("blacknav");
            $('body').addClass("blacknav");
            $(".left-sidebar").addClass("blacknav");
            $(".sidebar-footer").addClass("blacknav");
            $(".sidebar-link").addClass("blacknav");
            $(".sidebar-footer").addClass("sidebar-footer-black");
            $(".dd-item").addClass("black");
            $(".breadcrumb").addClass("black");
            $('input[type="date"]').addClass('dark-calendar');
            dark("{{Session::get('text_dark')}}", "{{Session::get('bg_dark')}}");

        }
        $('#toggle_checkbox').change(function() {
            if ($(this).is(":checked")) {
                $('body').attr("data-theme", 'dark');
                $(".sidna").addClass("blacknav");
                $('body').addClass("blacknav");
                $(".left-sidebar").addClass("blacknav");
                $(".sidebar-footer").addClass("blacknav");
                $(".sidebar-link").addClass("blacknav");
                $(".sidebar-footer").addClass(".sidebar-footer-black");
                $(".dd-item").addClass("black");
                $(".breadcrumb").addClass("black");
                $('input[type="date"]').addClass('dark-calendar');
                dark("{{Session::get('text_dark')}}", "{{Session::get('bg_dark')}}");
                $.ajax({
                    url: "{{asset('change_theme_mode')}}",
                    type: "POST",
                    data: {
                        theme: 'dark'
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {

                    },
                    failure: function(errMsg) {

                        console.log(errMsg);
                    }
                });
               

            } else {

                $('body').attr("data-theme", 'light');
                $(".sidna").removeClass("blacknav");
                $('body').removeClass("blacknav");
                $(".left-sidebar").removeClass("blacknav");
                $(".sidebar-footer").removeClass("blacknav");
                $(".sidebar-link").removeClass("blacknav");
                $(".sidebar-footer").removeClass("sidebar-footer-black");
                $(".dd-item").removeClass("black");
                $(".breadcrumb").removeClass("black");
                $('input[type="date"]').removeClass('dark-calendar');
                light("{{Session::get('text_light')}}", "{{Session::get('bg_light')}}");
                $.ajax({
                    url: "{{asset('change_theme_mode')}}",
                    type: "POST",
                    data: {
                        theme: 'light'
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(data) {},
                    failure: function(errMsg) {
                        console.log(errMsg);
                    }
                });
            }
        });

    });
    function markRead(id){
        
        var url = "{{url('read_notification/')}}";
        
        $.ajax({
            url: url+'/'+id,
            type: "get",
            dataType: 'json',
            cache: false,
            async:false,
            success: function(data) {
                console.log(data)
                var slug = '';
                console.log(notifications)
                for(var n = 0 ; n < notifications.length ; n++){
                    if(notifications[n].id == id){
                        slug = notifications[n].slug;
                        break;
                    }
                }
                
                if(data.success == true){
                    // alert(slug)
                    window.location.href = "{!! url('"+slug+"') !!}";
                }else{

                }
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
        
    }
    function getNotifications(){
        $.ajax({
            url: "{{asset('getNotifications')}}",
            type: "get",
            dataType: 'json',
            cache: false,
            async:false,
            success: function(data) {
                console.log(data)
                var noti_div = ``;
                if(data){
                    notifications = data.data;

                    if(notifications.length > 0){
                        for(var i = 0 ; i < notifications.length ; i++){
                            var title = notifications[i].noti_title != null ? notifications[i].noti_title : 'Notification';
                            var desc = notifications[i].noti_desc != null ? notifications[i].noti_desc : 'Notification Desc';
                            var icon = 'fa fa-link';
                            noti_div += `<a onclick="markRead(`+notifications[i].id+`)" style="cursor: pointer;"
                                            class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                            <span class="btn `+notifications[i].btn_class+` rounded-circle btn-circle" style="padding-top:10px"><i
                                                    class="`+notifications[i].noti_icon+`"></i></span>
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="message-title mb-0 mt-1">`+title+`</h5> 
                                                <span
                                                    class="font-12 text-nowrap d-block text-muted text-truncate">`+desc+`
                                                </span> 
                                                <span class="font-12 text-nowrap d-block text-muted">9:30 AM
                                                </span>
                                            </div>
                                        </a>`;
                        }
                        $('.notifications').append(noti_div)

                    }else{
                        noti_div = `<li>
                                        <span class="font-12 text-nowrap d-block text-muted text-truncate" style="text-align:center">No Unread Notifications.</span> 
                                    </li>`;
                        $('.notif_div_ul').append(noti_div)
                        
                    }
                }
                

            },
            failure: function(errMsg) {

                console.log(errMsg);
            }
        });
    }
    </script>
       <script>
    //Basic Example
    $("#example-basic").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true
    });

    // Basic Example with form
    var form = $("#example-form");
    form.validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        rules: {
            confirm: {
                equalTo: "#password"
            }
        }
    });
    form.children("div").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        onStepChanging: function(event, currentIndex, newIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            alert("Submitted!");
        }
    });

    // Advance Example

    var form = $("#example-advanced-form").show();

    form.steps({
        headerTag: "h3",
        bodyTag: "fieldset",
        transitionEffect: "slideLeft",
        onStepChanging: function(event, currentIndex, newIndex) {
            // Allways allow previous action even if the current form is not valid!
            if (currentIndex > newIndex) {
                return true;
            }
            // Forbid next action on "Warning" step if the user is to young
            if (newIndex === 3 && Number($("#age-2").val()) < 18) {
                return false;
            }
            // Needed in some cases if the user went back (clean up)
            if (currentIndex < newIndex) {
                // To remove error styles
                form.find(".body:eq(" + newIndex + ") label.error").remove();
                form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
            }
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
        },
        onStepChanged: function(event, currentIndex, priorIndex) {
            // Used to skip the "Warning" step if the user is old enough.
            if (currentIndex === 2 && Number($("#age-2").val()) >= 18) {
                form.steps("next");
            }
            // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
            if (currentIndex === 2 && priorIndex === 3) {
                form.steps("previous");
            }
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
        },
        onFinished: function(event, currentIndex) {
            alert("Submitted!");
        }
    }).validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        rules: {
            confirm: {
                equalTo: "#password-2"
            }
        }
    });

    // Dynamic Manipulation
    $("#example-manipulation").steps({
        headerTag: "h3",
        bodyTag: "section",
        enableAllSteps: true,
        enablePagination: false
    });

    //Vertical Steps

    $("#example-vertical").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        stepsOrientation: "vertical"
    });

    //Custom design form example
    $(".tab-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onFinished: function(event, currentIndex) {
            swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");

        }
    });


    var form = $(".validation-wizard").show();

    $(".validation-wizard").steps({
        headerTag: "h6",
        bodyTag: "section",
        transitionEffect: "fade",
        titleTemplate: '<span class="step">#index#</span> #title#',
        labels: {
            finish: "Submit"
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            return currentIndex > newIndex || !(3 === newIndex && Number($("#age-2").val()) < 18) && (currentIndex < newIndex && (form.find(".body:eq(" + newIndex + ") label.error").remove(), form.find(".body:eq(" + newIndex + ") .error").removeClass("error")), form.validate().settings.ignore = ":disabled,:hidden", form.valid())
        },
        onFinishing: function(event, currentIndex) {
            return form.validate().settings.ignore = ":disabled", form.valid()
        },
        onFinished: function(event, currentIndex) {
            swal("Form Submitted!", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed lorem erat eleifend ex semper, lobortis purus sed.");
        }
    }), $(".validation-wizard").validate({
        ignore: "input[type=hidden]",
        errorClass: "text-danger",
        successClass: "text-success",
        highlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        unhighlight: function(element, errorClass) {
            $(element).removeClass(errorClass)
        },
        errorPlacement: function(error, element) {
            error.insertAfter(element)
        },
        rules: {
            email: {
                email: !0
            }
        }
    })
    </script>
    @yield('scripts')
</body>

</html>