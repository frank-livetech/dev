
<?php 

    if(Auth::user()->user_type != 5) {

        $menus = Session('menus');
        $departments = Session('depts');


        ?>

        <aside class="left-sidebar {{ \Auth::user()->theme == 'dark' ? 'blacknav': ''}}">
                    <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" class="sidna">
                    @foreach(Session('menus') as $menu )
                        @if($menu->route != "NULL") 
                            @if($menu->is_active == 1) 
                                <li class="sidebar-item">
                                    <a href="{{ route($menu->route) }}" class="sidebar-link">
                                        <span> <?php echo $menu->menu_icon; ?> </span>
                                        <span class="hide-menu" style="cursor: pointer;"> {{$menu->title}}</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            @if($menu->is_active == 1)
                                <li class="sidebar-item">
                                    <a class="sidebar-link has-arrow waves-effect waves-dark" aria-expanded="false">
                                        <?php echo $menu->menu_icon; ?>
                                        <span class="hide-menu" style="cursor: pointer;">{{$menu->title}}</span>
                                    </a>
                                        
                                    @php $sub_menus = $menu->sub_menu; @endphp
                                    <ul aria-expanded="false" class="collapse first-level" style="">
                                    @foreach($sub_menus as $sub_menu)
                                        @if($sub_menu->is_active == 1)

                                            <li class="sidebar-item">
                                                <a href="{{ route($sub_menu->route) }}" class="sidebar-link">
                                                    <i class="fa fa-arrow-right" style="display:block !important"></i>
                                                    <span class="hide-menu" style="cursor: pointer;">{{$sub_menu->title}}</span>
                                                </a>
                                            </li>

                                            @if($sub_menu->title == 'Ticket Manager')
                                                @foreach($departments as $depts)
                                                
                                                <li class="sidebar-item">

                                                    <a class="has-arrow sidebar-link" href="javascript:void(0)" aria-expanded="false">
                                                        <i class="mdi mdi-playlist-plus"></i>
                                                        <span class="hide-menu">{{$depts->name}}</span>
                                                    </a>

                                                    <ul aria-expanded="false" class="collapse second-level">
                                                        @foreach($depts->statuses as $sts)
                                                            <li class="sidebar-item">
                                                                <a href="{{route('ticket-manager.index',[$depts->slug,$sts->slug])}}" class="sidebar-link">
                                                                    <i class="mdi mdi-octagram"></i>
                                                                    <span class="hide-menu"> {{$sts->name}}</span>
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
                </nav>
            </div>

            <div class="sidebar-footer {{ \Auth::user()->theme == 'dark' ? 'blacknav': ''}}">
                <a href="{{url('settings')}}" class="link" data-toggle="tooltip" title="Settings"><i
                        class="ti-settings"></i></a>

                <a href="{{ url('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form1').submit();" class="link"
                    data-toggle="tooltip" title="Logout"><i class="mdi mdi-power"></i></a>
                <form id="logout-form1" action="{{ url('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </aside>

        <?php

    }else{

    }


?>

