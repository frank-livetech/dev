<?php 

    if(Auth::user()->user_type != 5) {

        $menus = Session('menus');

        ?>

    <!-- BEGIN: Main Menu-->
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row ">
                <li class="nav-item me-auto">
                    <a class="navbar-brand mt-1" href="">
                        <span class="brand-logo ">
                            @if(Session::get('site_logo') != null && Session::get('site_logo') != " ")
                                <img style=""
                                id="logo_image" src="{{asset('files/brand_files/')}}/{{Session::get('site_logo')}}"
                                alt="{{Session::get('site_title')}}" class="dark-logo" />
                            @else
                                <span style=" margin-left: 20px; background: gray; padding: 8px 14px; border-radius: 100%;">D</span>
                            @endif    
                        </span>
                        <h4 class="brand-text">{{Session::get('site_logo_title')}} <small id="version"></small>  </h4>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main sidna" id="sidebarnav"  data-menu="menu-navigation">

                    @foreach(Session('menus') as $menu )
                        @if($menu->route != "NULL") 
                            @if($menu->is_active == 1) 
                                <li class=" nav-item">
                                    <a class="d-flex align-items-center" href="{{ route($menu->route) }}">
                                        <span> <?php echo $menu->menu_icon; ?> </span>
                                        
                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo $menu->menu_icon; ?>"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> --}}
                                        <span class="menu-title text-truncate" data-i18n="{{$menu->title}}">{{$menu->title}}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            @if($menu->is_active == 1)
                                <li class=" nav-item">
                                    <a class="d-flex align-items-center">
                                        <?php echo $menu->menu_icon; ?>
                                        
                                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="<?php echo $menu->menu_icon; ?>"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg> --}}
                                        <span class="menu-title text-truncate" data-i18n="{{$menu->title}}">{{$menu->title}}</span>
                                        
                                    </a>
                                        
                                    @php $sub_menus = $menu->sub_menu; @endphp
                                    <ul aria-expanded="false" class="menu-content" style="">
                                    @foreach($sub_menus as $sub_menu)
                                        @if($sub_menu->is_active == 1)
                                        <li class=" ">
                                            <a class="d-flex align-items-center" href="{{ route($sub_menu->route) }}">
                                                <i data-feather="circle"></i>
                                                <span class="menu-item text-truncate" data-i18n="{{$sub_menu->title}}">{{$sub_menu->title}}</span>
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                    
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endforeach
                    <li class="nav-item has-sub" style=""><a class="d-flex align-items-center" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg><span class="menu-title text-truncate" data-i18n="Menu Levels">Menu Levels</span></a>
                        <ul class="menu-content">
                            <li><a class="d-flex align-items-center" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span class="menu-item text-truncate" data-i18n="Second Level">Second Level 2.1</span></a>
                            </li>
                            <li class="has-sub open" style=""><a class="d-flex align-items-center" href="#"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg><span class="menu-item text-truncate" data-i18n="Second Level">Second Level 2.2</span></a>
                                <ul class="menu-content">
                                    <li class=""><a class="d-flex align-items-center" href="#"><span class="menu-item text-truncate" data-i18n="Third Level">Third Level 3.1</span></a>
                                    </li>
                                    <li class="active"><a class="d-flex align-items-center" href="#"><span class="menu-item text-truncate" data-i18n="Third Level">Third Level 3.2</span></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
            </ul>
        </div>
        <div class="navbar-footer ">
            <div class="text-center ">
                <a href="{{url('settings')}}" class="link" data-toggle="tooltip" title="Settings"><i class="me-50" data-feather="settings"></i></a>

                <a href="{{ url('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form1').submit();" class="link"
                    data-toggle="tooltip" title="Logout"><i class="me-50" data-feather="power"></i></a>
                <form id="logout-form1" action="{{ url('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            
        </div>
    </div>
    <!-- END: Main Menu-->

    

<?php

        }else{

        }


?>
