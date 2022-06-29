<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row ">
                <li class="nav-item me-auto">
                    <a class="navbar-brand mt-1" href="">
                        <span class="brand-logo ">
                            @if(Session::get('site_logo') != null && Session::get('site_logo') != " ")
                                @if(file_exists( public_path().'/'. $file_path  . Session::get('site_logo') ))
                                    <img  id="logo_image" src="{{asset( $file_path . Session::get('site_logo'))}}"
                                    alt="{{Session::get('site_title')}}" class="dark-logo" />
                                @else
                                    <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle" >
                                @endif
                            @else
                            <img src="{{asset( $file_path . 'default_imgs/logo.png')}}" alt="'s Photo" class="rounded-circle">
                            @endif    
                        </span>
                        <h6 class="brand-text wrap-text" >{{Session::get('site_logo_title')}} <small id="version"></small>  </h6>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content mt-2" >
            <ul class="navigation navigation-main sidna" id="sidebarnav"  data-menu="menu-navigation">
                <li class=" nav-item  {{ (request()->is('myprofile')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('customer.myProfile') }}">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> </span>
                        <span class="menu-title text-truncate" data-i18n="Profile">Profile</span>
                    </a>
                </li>
                <li class=" nav-item  {{ (request()->is('view-asset')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{ route('customer.myasset') }}">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-command"><path d="M18 3a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3 3 3 0 0 0 3-3 3 3 0 0 0-3-3H6a3 3 0 0 0-3 3 3 3 0 0 0 3 3 3 3 0 0 0 3-3V6a3 3 0 0 0-3-3 3 3 0 0 0-3 3 3 3 0 0 0 3 3h12a3 3 0 0 0 3-3 3 3 0 0 0-3-3z"></path></svg> </span>
                        <span class="menu-title text-truncate" data-i18n="Asset">My Assets</span>
                    </a>
                </li>
                <li class=" nav-item {{ (request()->is('view-tkt')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{route('customer.tickets')}}">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></span>
                        
                        <span class="menu-title text-truncate" data-i18n="Ticket">My Tickets </span>
                    </a>
                </li>
                <li class=" nav-item {{ (request()->is('add-tkt')) ? 'active' : '' }}">
                    <a class="d-flex align-items-center" href="{{route('customer.addTicket')}}">
                        <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mouse-pointer"><path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path><path d="M13 13l6 6"></path></svg></span>
                        
                        <span class="menu-title text-truncate" data-i18n="Ticket">Submit a Ticket</span>
                    </a>
                </li>
            </ul>
        </div>
</div>