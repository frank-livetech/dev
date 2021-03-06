<?php 
    if(Auth::user()->user_type != 5) {
        $menus = Session('menus');
        $departments = Session('depts');
        ?>
<style>

.navigation li a{
    overflow: visible !important;
}
li.sidebar-item.thirdlvl{
    font-size:13px !important;
}
</style>
    <!-- BEGIN: Main Menu-->
    @if(\Auth::user()->theme == "dark")
    <div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true">
    @else
    <div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    @endif
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
                        <h6 class="brand-text wrap-text version_title">  <small id="version"></small>  </h6>
                    </a></li>
                <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse" style="position: absolute;right: 3px;top: -15px;"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
            </ul>
        </div>
        <div class="shadow-bottom"></div>
        <div class="main-menu-content">
            <ul class="navigation navigation-main sidna" id="sidebarnav"  data-menu="menu-navigation">
                    @foreach(Session('menus') as $menu )
                        @if($menu->route != "NULL") 
                            @if($menu->is_active == 1) 
                                <li class="nav-item @yield( $menu->title )">
                                    <a class="d-flex align-items-center" href="{{ route($menu->route) }}">
                                        <span> <?php echo $menu->menu_icon; ?> </span>
                                        <span class="menu-title text-truncate" data-i18n="{{$menu->title}}">{{$menu->title}}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            @if($menu->is_active == 1)
                                <li class=" nav-item @yield( $menu->title )">
                                    <a class="d-flex align-items-center">
                                        <?php echo $menu->menu_icon; ?>
                                        <span class="menu-title text-truncate" data-i18n="{{$menu->title}}">{{$menu->title}}</span>
                                    </a>
                                        
                                    @php $sub_menus = $menu->sub_menu; @endphp
                                    <ul aria-expanded="false" class="menu-content" style="">
                                    @foreach($sub_menus as $sub_menu)
                                        @if($sub_menu->is_active == 1)
                                        
                                        @if($sub_menu->title == "Ticket Manager" && Request::path() == 'ticket-manager')
                                            <li class="active">
                                                <a class="d-flex align-items-center" href="{{ route($sub_menu->route) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>
                                                    <span class="menu-item text-truncate" data-i18n="{{$sub_menu->title}}"> {{$sub_menu->title}} </span>
                                                </a>
                                            </li>
                                        @else
                                            <li class="@yield( $sub_menu->title )">
                                                <a class="d-flex align-items-center" href="{{ route($sub_menu->route) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-circle"><circle cx="12" cy="12" r="10"></circle></svg>
                                                    <span class="menu-item text-truncate" data-i18n="{{$sub_menu->title}}"> {{$sub_menu->title}} </span>
                                                </a>
                                            </li>
                                        @endif

                                        @if($sub_menu->title == 'Ticket Manager')
                                                @foreach($departments as $depts)
                                                    {{-- 2nd level --}}
                                                    @php
                                                        $dep_url = 'ticket-manager/' . $depts->dept_slug . '/all';
                                                    @endphp
                                                    <li class="sidebar-item">
                                                        <a class="has-arrow sidebar-link slogan" href="javascript:void(0)" aria-expanded="false">
                                                            {{-- <i data-feather='plus' class='slogan_i_plus'></i> --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus slogan_i_plus" style=""><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                            {{-- <i data-feather='minus' class='slogan_i_minus'></i> --}}
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-minus slogan_i_minus" style="display: none;"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                            <span class="hide-menu">{{$depts->name}}</span>
                                                            <span class="badge badge-light-danger rounded-pill ms-auto me-2" id="dept_cnt_{{$depts->id}}"></span>
                                                        </a>
                                                        <ul aria-expanded="false" class="collapse second-level">
                                                            @foreach($depts->statuses as $sts)
                                                                {{-- 3rd level --}}
                                                                @php
                                                                    $url = 'ticket-manager/' . $depts->dept_slug . '/' . $sts->slug;
                                                                @endphp
                                                                @if ($loop->first) 
                                                                <li class="sidebar-item thirdlvl {{Request::path()}} {{Request::path() == $dep_url ? 'active' : ''}}">
                                                                    <a href="{{route('ticket-manager.index',[$depts->dept_slug , 'all'])}}" class="slogan sidebar-link">
                                                                        <span class="hide-menu"> All </span>
                                                                        <span class="badge badge-light-danger rounded-pill ms-auto me-2" id="sts_cnt_{{$depts->id}}"></span>
                                                                    </a>
                                                                </li>
                                                                @endif
                                                                <li class="sidebar-item thirdlvl {{Request::path() == $url ? 'active' : ''}}">
                                                                    <a href="{{route('ticket-manager.index',[$depts->dept_slug,$sts->slug])}}" class="slogan sidebar-link">
                                                                        <span class="hide-menu"> {{$sts->name}}</span>
                                                                        <span class="badge badge-light-danger rounded-pill ms-auto me-2" id="sts_cnt_{{$depts->id}}_{{$sts->id}}"></span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            @endif
                                        @endif
                                    @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endif
                    @endforeach
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