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
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('files/brand_files')}}/{{Session::get('site_favicon')}}">
    <title>{{Session::get('site_title')}}</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    @php
        $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
        $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
    
    <link rel="stylesheet" href="{{asset( $path . 'css/bootstrap_switch.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/custom_css.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/flashy.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/jquery.steps.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/jsgrid-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/jsgrid.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/steps.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/style.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/tagsinput.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/toastr.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/pickr.min.css')}}">
    <link rel="stylesheet" href="{{asset( $path . 'css/flashy.min.css')}}">

    <link rel="stylesheet" type="text/css" href="{{asset( $path . 'css/countdown.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset( $path . 'css/theme2.css')}}" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    <link rel="stylesheet" href="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.css">

    <!-- font-awesome -->
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>

    <!-- this link for toggel-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css">

   <?php 
    
   if(Auth::user()->user_type == 5) {
       ?>
    <style>
        #main-wrapper[data-layout=vertical][data-sidebartype=full] .page-wrapper {
            margin-left: 0px !important;
        }
    </style>
       <?php
   }
   
   ?>  
    <style>
        body[data-theme=dark] #main-wrapper, body[data-theme=dark] .footer, body[data-theme=dark] .blacknav,body[data-theme=dark] .top-navbar,body[data-theme=dark] .navbar,body[data-theme=dark] .nav-link, body[data-theme=dark] .collapse, body[data-theme=dark]  .nav-item, body[data-theme=dark]  #main-wrapper[data-layout=vertical] .topbar .navbar-collapse[data-navbarbg=skin1], body[data-theme=dark]  .navbar-collapse, body[data-theme=dark] .breadcrumb,body[data-theme=dark] .page-breadcrumb, body[data-theme=dark] .cke_toolbar_separator, body[data-theme=dark] .dropdown-menu, body[data-theme=dark] .email-app .list-group .list-group-item .list-group-item-action.active, body[data-theme=dark] .email-app .list-group .list-group-item .list-group-item-action:hover, body[data-theme=dark] .jumbotron, body[data-theme=dark] .page-wrapper, body[data-theme=dark] .progress, body[data-theme=dark] .wizard-content .wizard>.steps .step, body[data-theme=dark] .wizard:not(.wizard-circle)>.actions .disabled a, body[data-theme=dark] .wizard:not(.wizard-circle)>.actions .disabled a:active, body[data-theme=dark] .wizard:not(.wizard-circle)>.actions .disabled a:hover, body[data-theme=dark] .wizard:not(.wizard-circle)>.content, body[data-theme=dark] .wizard:not(.wizard-circle)>.steps .disabled a, body[data-theme=dark] .wizard:not(.wizard-circle)>.steps .disabled a:active, body[data-theme=dark] .wizard:not(.wizard-circle)>.steps .disabled a:hover {
    background-color: #323743 ;
    color: #d2dae0 ;
    font-family: Rubik,sans-serif;
}
body[data-theme=dark] .left-sidebar :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
    color: #d2dae0 ;
}
    .dd-item.black button:before,
    .breadcrumb.black>.breadcrumb-item:before {
        color: #d2dae0 ;
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
        background-color:#fff ;
        color:#252629  ;
        
    }
   
    body[data-theme=dark] .odd td,
    body[data-theme=dark] .odd td :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
        background-color:#252629  ;
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
    .buttons-pdf{

        background: #CC4438;
        color: #fff;
        border-color: #CC4438;
    }
    .buttons-excel{

        background: #026e39;
        color: #fff;
        border-color: #026e39;
    }
    .buttons-copy {
        background: #238fac;
        color: #fff;
        border-color: #238fac;
    }
    .badge-light{
        /* width: 6px;
        height: 6px;
      */
        position: relative;
        top: -15px;
        right: 10px;
        border-radius: 42%;
        background: repeating-linear-gradient(45deg , black, transparent 100px);
        font-weight: 600;
        font-size:58%;
        color:white
    }
    /* loader code start */
    .loader_container {
      width: 100%;
      height: 100%;
      background-color: #fff;
      opacity: 0.9;
      position: absolute;
      top: 0px;
      right: 0px;
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1;
    }
    .loader {
        width: 50px;
        height: 50px;
        border: 3px solid;
        color: #111;
        border-radius: 50%;
        border-top-color: transparent;
        animation: loader 0.8s linear infinite;
    }
    @keyframes loader {
      25% {
        color: #37b0e9;
      }

      50% {
        color: #6d5eac;
      }

      75% {
        color: #37b0e9;
      }

      100% {
        color: #6d5eac;
      }

      to {
        transform: rotate(360deg);
      }
    }
    table th, table td{
        white-space: nowrap !important;
    }
    .table tbody tr td {
        vertical-align: middle !important;
    }
    #toggle_checkbox {
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

/** Color Changes */

    <?php if(Session('light_mode') != null && Session('light_mode') != "")  {

            $light_mode = Session('light_mode');
            ?>
            .page-wrapper,
            .main_sys_back{
                background: {{$light_mode['main_sys_back']}} !important;
                color:{{$light_mode['main_font']}} !important;

            }
            .topbar,
            .head_back{
                background:{{$light_mode['head_back']}} !important;
            }
            .card,
            .card_back{
                background:  {{$light_mode['card_back']}}  !important;
            }
            table thead,
            .table_head_back{
                background: {{$light_mode['table_head_back']}} !important;
            }
            .table td,
            .table_row{
                background: {{$light_mode['table_row']}} !important;
            }
            /* p,
            .main_font{
                color:{{$light_mode['main_font']}} !important;
            } */
            .page-breadcrumb ,
            .bread_crum_back{
                background:{{$light_mode['bread_crum_back']}} !important;
            }
            .border_thick{
                border-width:{{$light_mode['border_thick']}} !important;
            }
            .card,
            .card_shadow{
                /* box-shadow: -1px 10px 400px 2px rgba(0,0,0,0.75) !important; */
                box-shadow: {{$light_mode['card_shadow']}};
            }
            <?php
    } ?>

     <?php if(Session('dark_mode') != null && Session('dark_mode') != "")  {  
      $dark_mode = Session('dark_mode');
      ?>
      body[data-theme=dark],
      body[data-theme=dark] .page-wrapper,
      body[data-theme=dark] .main_sys_back{

          background: {{$dark_mode['drk_main_sys_back']}} !important;
            color:{{$dark_mode['drk_main_font']}} !important;

      }
      body[data-theme=dark] .topbar,
      body[data-theme=dark] .navbar-collapse,
      body[data-theme=dark] .top-navbar,
      body[data-theme=dark] .nav-link,
      body[data-theme=dark] .nav-item,
      body[data-theme=dark] .head_back{
        background:{{$dark_mode['drk_header_back']}} !important;
    }
    body[data-theme=dark] .card,
    body[data-theme=dark] .card_back{
        background:{{$dark_mode['drk_card_back']}}  !important;
    }
    body[data-theme=dark] .table thead,
    body[data-theme=dark] .table_header{
        background:{{$dark_mode['drk_table_header']}} !important;
    }
    body[data-theme=dark] .odd td, 
    body[data-theme=dark] .odd td :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save),
    body[data-theme=dark] .even td, 
    body[data-theme=dark] .even td :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save),
    body[data-theme=dark] .table td,
    body[data-theme=dark] .table_row{
        background:{{$dark_mode['drk_table_row']}} !important;
    }
    /* body[data-theme=dark]  p,
    body[data-theme=dark] .main_font{
        color:{{$dark_mode['drk_main_font']}} !important;
    } */
    body[data-theme=dark]  .page-breadcrumb ,
    body[data-theme=dark]  .breadcrumb,
    body[data-theme=dark] .bread_crum_back{
        background:{{$dark_mode['drk_bread_crum']}} !important;
    }
    body[data-theme=dark] .page-wrapper,
    body[data-theme=dark] .border_thick{
        border-width:{{$dark_mode['drk_border_thick']}} !important;
    }
    body[data-theme=dark] .card,
    body[data-theme=dark] .card_shadow{
        /* box-shadow: -1px 10px 400px 2px rgba(0,0,0,0.75) !important; */
        box-shadow: {{$dark_mode['drk_card_shadow']}};
    }
    <?php
    } ?>

<?php if(Session('button') != null && Session('button') != "")  {  
    ?>
    .add_btn_back{
        background:#39c449 !important;
    } .add_font_color{
        color:#ffffff !important;
    } .dlt_btn_back{
        background:#f62d51 !important;
    } .dlt_font_clr{
        color:#fff !important;
    } .new_btn_back{
        background:#39c449 !important;
    } .new_font_clr{
        color:#ffff !important;
    } .reg_btn_back{
        background:#39c449 !important;
    } .reg_font_clr{
        color:red !important;
    }.login_btn_btn{
        background:#7460ee !important;
    } .login_font_clr{
        color:#fff !important;
    }
    <?php
    } ?>
    
   
    
/*Color End */

    
    /* loader code ends */
    </style>
    @stack('css')
    @yield('customtheme')
</head>
<body data-theme="{{ \Auth::user()->theme}}" >
    <div id="main-wrapper">
    <input type="hidden" value="{{\Auth::user()->profile_pic}}" id="curr_user_image">
        <header class="topbar head_back">
            <nav class="top-navbar navbar navbar-expand-md navbar-dark">
                <div class="navbar-nav">
                    <a class="navbar-brand sidebartoggler waves-effect waves-light asdabcasd"> 
                        @if(Session::get('site_logo') != null && Session::get('site_logo') != "")
                            @if(file_exists( public_path().'/'. $file_path  . Session::get('site_logo') ))
                                <img  src="{{asset( $file_path . Session::get('site_logo'))}}"
                                    alt="'s Photo" class="rounded-circle" width="65" height="72">
                            @else
                                <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                            @endif
                        @else
                            <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                        @endif
                            &nbsp;
                        <strong id="logo_title">{{Session::get('site_logo_title')}}</strong><span id="version"></span> 
                    </a>

                    <a class="nav-link sidebartoggler waves-effect waves-light mt-2" href="javascript:void(0)"
                        data-sidebartype="mini-sidebar">
                        <i class="icon-arrow-left-circle"
                            style="line-height:inherit !important"></i>
                            <i class="icon-arrow-right-circle"
                            style="line-height:inherit !important;display:none;"></i>
                            </a>
                </div>
                <div class="collapse navbar-collapse head_back" id="navbarSupportedContent">
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
                                        <div class="d-flex justify-content-between border-bottom">
                                            <div class="font-weight-medium rounded-top py-3 px-4">
                                                Notifications
                                            </div>
                                            <div class="p-2">
                                                <a href="{{url('all-notifications')}}" class="small p-2">view all</a>
                                            </div>
                                        </div>
                                        
                                    </li>
                                    <li>
                                        <div class="message-center notifications position-relative"
                                            style="height:250px;">
                                            <!-- Message Coming from js -->
                                            
                                        </div>
                                        <div class="text-center">
                                            <button class="btn btn-success" onclick="allRead()" style="width:100%;">
                                                Mark All as Read
                                            </button>    
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
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if(Auth::user()->profile_pic != null)
                                    @if(file_exists( public_path(). $file_path . '/files/user_photos/'. \Auth::user()->profile_pic))
                                        <img src="{{asset( $file_path . 'files/user_photos/'.\Auth::user()->profile_pic)}}"
                                            alt="'s Photo" class="rounded-circle" width="65" height="72">
                                    @else
                                        <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                                    @endif
                                @else
                                    <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72"> 
                                @endif
                            </a>
                                    <!-- <a href="#">{{ Auth::user()->name }}</a> -->
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 mb-2 text-white" style="background-color: #009efb;">
                                    <div class="">
                                        @if(Auth::user()->profile_pic != null)
                                            @if(file_exists( public_path(). $file_path . '/files/user_photos/'. \Auth::user()->profile_pic))
                                                <img src="{{ asset( $file_path . 'files/user_photos/'.\Auth::user()->profile_pic)}}"
                                                    alt="'s Photo" class="rounded-circle" width="65" height="72">
                                            @else
                                                <img src="{{asset(  $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                                            @endif
                                        @else
                                            <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" width="65" height="72">
                                        @endif
                                        </div>
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

        @include('layouts.sidebar')

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

    <script src="{{asset( $path . 'js/jquery.min.js')}}"></script>
    <script src="{{asset( $path . 'js/popper.min.js')}}"></script>
    <script src="{{asset( $path . 'js/bootstrap.min.js')}}"></script>
    
    <script src="{{asset( $path . 'js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset( $path . 'js/datatable_basic.init.js')}}"></script>
    <script src="{{asset( $path . 'js/custom.min.js')}}"></script>

    <script src="{{asset( $path . 'js/app_color.js')}}"></script>
    <script src="{{asset( $path . 'js/app_style_switcher.js')}}"></script>
    <script src="{{asset( $path . 'js/app.init.js')}}"></script>
    <script src="{{asset( $path . 'js/app.min.js')}}"></script>


    <script src="{{asset( $path . 'js/c3.min.js')}}"></script>
    <script src="{{asset( $path . 'js/chat.js')}}"></script>    
    <script src="{{asset( $path . 'js/custom2.min.js')}}"></script>
    <script src="{{asset( $path . 'js/d3.min.js')}}"></script>
    <script src="{{asset( $path . 'js/feather.min.js')}}"></script>
    <script src="{{asset( $path . 'js/feather2.min.js')}}"></script>

    
    <script src="{{asset( $path . 'js/jquery.nestable.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery.sparkline.min.js')}}"></script>

    <script src="{{asset( $path . 'js/jquery.steps.min.js')}}"></script>
    <script src="{{asset( $path . 'js/jquery.validate.min.js')}}"></script>
    <script src="{{asset( $path . 'js/moment.js')}}"></script>
    <script src="{{asset( $path . 'js/perfect_scrollbar.jquery.min.js')}}"></script>
    
    <script src="{{asset( $path . 'js/select2.full.min.js')}}"></script>
    <script src="{{asset( $path . 'js/select2.init.js')}}"></script>
    <script src="{{asset( $path . 'js/select2.min.js')}}"></script>
    <script src="{{asset( $path . 'js/sidebarmenu.js')}}"></script>
    <script src="{{asset( $path . 'js/toastr.min.js')}}"></script>
    <script src="{{asset( $path . 'js/waves.js')}}"></script>
    <script src="{{asset( $path . 'js/pickr.min.js')}}"></script>
    <script src="{{asset( $path . 'js/bootstrap_switch.min.js')}}"></script>

    <script type="text/javascript" src="{{asset( $path . 'js/countdown.js')}}"></script>
    <script src="{{asset( $path . 'js/calendar.js')}}"></script>
    <script src="{{asset( $path . 'js/tagsinput.js')}}"></script>
 
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-messaging.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-analytics.js"></script>

    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-firestore.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.2.6/firebase-storage.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js" integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="https://unpkg.com/multiple-select@1.5.2/dist/multiple-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

  

    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var usertheme = @json(\Auth::user()->theme);
    var get_all_staff_att = "{{url('get_all_staff_att')}}";
    var user_photo_url = "{{asset('files/user_photos')}}";
    var send_notification = "{{url('send_notification')}}";
    var url = "{{url('mark_all_read')}}";
    var get_notifications = "{{url('getNotifications')}}";
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
        $(".dropify").dropify();
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
            //    Color Scheme
                $('.main_sys_back').toggleClass("main_sys_back drk_main_sys_back");
                $('.head_back').toggleClass("head_back drk_head_back ");
                $('.card_back').toggleClass("card_back drk_card_back ");
                $('.table_header').toggleClass("table_header drk_table_header ");
                $('.table_row').toggleClass("table_row drk_table_row ");
                $('.main_font').toggleClass("main_font drk_main_font ");
                $('.bread_crum_back').toggleClass("bread_crum_back drk_bread_crum_back ");
                $('.border_thick').toggleClass("border_thick drk_border_thick ");
                $('.card_shadow').toggleClass("card_shadow drk_card_shadow ");
            
            
            
            
            
            //    Color Scheme

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

                $('.drk_main_sys_back').toggleClass("drk_main_sys_back main_sys_back ");
                $('.drk_head_back').toggleClass("drk_head_back head_back ");
                $('.drk_card_back').toggleClass("drk_card_back card_back ");
                $('.drk_table_header').toggleClass("drk_table_header table_header ");
                $('.drk_table_row').toggleClass("drk_table_row main_table_row ");
                $('.drk_main_font').toggleClass("drk_main_font main_main_font ");
                $('.drk_bread_crum_back').toggleClass("drk_bread_crum_back bread_crum_back ");
                $('.drk_border_thick').toggleClass("drk_border_thick border_thick ");
                $('.drk_card_shadow').toggleClass("drk_card_shadow card_shadow ");

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

    function allRead(){
        $.ajax({
            url: url,
            type: "get",
            dataType: 'json',
            cache: false,
            async:false,
            success: function(data) {
                getNotifications();
                $('.message-item').remove();
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
        
    }

    function getNotifications(){
        $.ajax({
            url: get_notifications,
            type: "get",
            dataType: 'json',
            cache: false,
            async:false,
            success: function(data) {
                console.log(data , "notification");
                var noti_div = ``;
                var sender = data.data;

                var curr_user_image = $("#curr_user_image").val();
                var user_image = ``;
                var default_icon = ``;

                if(data){
                    notifications = data.data;

                    $("#noti_count").text(notifications.length);

                    if(notifications.length > 0){
                        for(var i = 0 ; i < notifications.length ; i++){

                            if(notifications[i].sender != null) {

                                if(notifications[i].sender.profile_pic != null ) {
                                    user_image = `<img src="`+user_photo_url + '/' + notifications[i].sender.profile_pic  +`" class="img-fluid" style="border-radius: 50%;width: 40px;height: 40px;">`;
                                }else{
                                    user_image = `<img src="`+user_photo_url + '/' + 'user-photo.jpg' +`" class="img-fluid" style="border-radius: 50%;width:40px;height: 40px;">`;
                                }
                            }
                            else{
                                user_image = `<img src="`+user_photo_url + '/' + 'user-photo.jpg' +`" class="img-fluid" style="border-radius: 50%;width:40px;height: 40px;">`;
                            }

                            var date = new Date(notifications[i].created_at);
                            
                            default_icon = `<span class="btn `+notifications[i].btn_class+` rounded-circle btn-circle"">
                                            <i class="`+notifications[i].noti_icon+`"></i></span>`;

                            var title = notifications[i].noti_title != null ? notifications[i].noti_title : 'Notification';
                            var desc = notifications[i].noti_desc != null ? notifications[i].noti_desc : 'Notification Desc';

                            var icon = 'fa fa-link';
                            noti_div += `<a onclick="markRead(`+notifications[i].id+`)" style="cursor: pointer;"
                                            class="message-item d-flex align-items-center border-bottom px-3 py-2">
                                            `+ (notifications[i].noti_type == "attendance" ? user_image : default_icon) +`
                                            <div class="w-75 d-inline-block v-middle pl-2">
                                                <h5 class="message-title mb-0 mt-1">`+title+`</h5> 
                                                <span
                                                    class="font-12 d-block text-muted">`+desc+`
                                                </span> 
                                                <span class="font-12 text-nowrap d-block text-muted">` + moment(notifications[i].created_at).format('LT') + ` </span>
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

    function sendNotification(type,slug,icon,title,description) {
        $.ajax({
            type: 'POST',
            url: send_notification,
            data: { 
                type:type,
                slug:slug,
                icon:icon,
                title: title, 
                description: description},
            success: function(data) {
                console.log(data);
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