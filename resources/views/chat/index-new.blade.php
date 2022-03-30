@extends('layouts.master-layout-new')
@section('Live Support Chat','active')
@section('body')
@php
$file_path = Session::get('is_live') == 1 ? 'public/' : '/';
$path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
@endphp
<div class="app-content content chat-application">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-area-wrapper container-fluid p-0">
        <div class="sidebar-left">
            <div class="sidebar">
                <!-- Admin user profile area -->
                <div class="chat-profile-sidebar">
                    <header class="chat-profile-header">
                        <span class="close-icon">
                            <i data-feather="x"></i>
                        </span>
                        <!-- User Information -->
                        <div class="header-profile-sidebar">
                            <div class="avatar box-shadow-1 avatar-xl avatar-border">
                                <!-- <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="user_avatar" />
                                <span class="avatar-status-online avatar-status-xl"></span> -->
                                @if(auth()->user()->profile_pic != null)
                                @if(file_exists( getcwd(). '/' . auth()->user()->profile_pic))
                                <img src="{{ asset( request()->root() .'/'. auth()->user()->profile_pic)}}" alt="'s Photo" class="rounded-circle" id="login_usr_logo" width="50px" height="50px">
                                @else
                                <img src="{{asset(  $file_path . 'default_imgs/customer.png')}}" id="login_usr_logo" width="50px" height="50px" alt="'s Photo" class="rounded-circle">
                                @endif
                                @else
                                <img src="{{asset( $file_path . 'default_imgs/customer.png')}}" id="login_usr_logo" alt="'s Photo" height="50px" width="50px" class="rounded-circle">
                                @endif
                            </div>
                            <h4 class="chat-user-name">{{Auth::user()->name}}</h4>
                            <span class="user-post">Admin</span>
                        </div>
                        <!--/ User Information -->
                    </header>
                    <!-- User Details start -->
                    <div class="profile-sidebar-area">
                        <h6 class="section-label mb-1">About</h6>
                        <div class="about-user">
                            <textarea data-length="120" class="form-control char-textarea" id="textarea-counter" rows="5" placeholder="About User">
Dessert chocolate cake lemon drops jujubes. Biscuit cupcake ice cream bear claw brownie brownie marshmallow.</textarea>
                            <small class="counter-value float-end"><span class="char-count">108</span> / 120 </small>
                        </div>
                        <!-- To set user status -->
                        <h6 class="section-label mb-1 mt-3">Status</h6>
                        <ul class="list-unstyled user-status">
                            <li class="pb-1">
                                <div class="form-check form-check-success">
                                    <input type="radio" id="activeStatusRadio" name="userStatus" class="form-check-input" value="online" checked />
                                    <label class="form-check-label ms-25" for="activeStatusRadio">Active</label>
                                </div>
                            </li>
                            <li class="pb-1">
                                <div class="form-check form-check-danger">
                                    <input type="radio" id="dndStatusRadio" name="userStatus" class="form-check-input" value="busy" />
                                    <label class="form-check-label ms-25" for="dndStatusRadio">Do Not Disturb</label>
                                </div>
                            </li>
                            <li class="pb-1">
                                <div class="form-check form-check-warning">
                                    <input type="radio" id="awayStatusRadio" name="userStatus" class="form-check-input" value="away" />
                                    <label class="form-check-label ms-25" for="awayStatusRadio">Away</label>
                                </div>
                            </li>
                            <li class="pb-1">
                                <div class="form-check form-check-secondary">
                                    <input type="radio" id="offlineStatusRadio" name="userStatus" class="form-check-input" value="offline" />
                                    <label class="form-check-label ms-25" for="offlineStatusRadio">Offline</label>
                                </div>
                            </li>
                        </ul>
                        <!--/ To set user status -->

                        <!-- User settings -->
                        <h6 class="section-label mb-1 mt-2">Settings</h6>
                        <ul class="list-unstyled">
                            <li class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center">
                                    <i data-feather="check-square" class="me-75 font-medium-3"></i>
                                    <span class="align-middle">Two-step Verification</span>
                                </div>
                                <div class="form-check form-switch me-0">
                                    <input type="checkbox" class="form-check-input" id="customSwitch1" checked />
                                    <label class="form-check-label" for="customSwitch1"></label>
                                </div>
                            </li>
                            <li class="d-flex justify-content-between align-items-center mb-1">
                                <div class="d-flex align-items-center">
                                    <i data-feather="bell" class="me-75 font-medium-3"></i>
                                    <span class="align-middle">Notification</span>
                                </div>
                                <div class="form-check form-switch me-0">
                                    <input type="checkbox" class="form-check-input" id="customSwitch2" />
                                    <label class="form-check-label" for="customSwitch2"></label>
                                </div>
                            </li>
                            <li class="mb-1 d-flex align-items-center cursor-pointer">
                                <i data-feather="user" class="me-75 font-medium-3"></i>
                                <span class="align-middle">Invite Friends</span>
                            </li>
                            <li class="d-flex align-items-center cursor-pointer">
                                <i data-feather="trash" class="me-75 font-medium-3"></i>
                                <span class="align-middle">Delete Account</span>
                            </li>
                        </ul>
                        <!--/ User settings -->

                        <!-- Logout Button -->
                        <div class="mt-3">
                            <button class="btn btn-primary">
                                <span>Logout</span>
                            </button>
                        </div>
                        <!--/ Logout Button -->
                    </div>
                    <!-- User Details end -->
                </div>
                <!--/ Admin user profile area -->

                <!-- Chat Sidebar area -->
                <div class="sidebar-content">
                    <span class="sidebar-close-icon">
                        <i data-feather="x"></i>
                    </span>
                    <!-- Sidebar header start -->
                    <div class="chat-fixed-search">
                        <div class="d-flex align-items-center w-100">
                            <div class="sidebar-profile-toggle">
                                <div class="avatar avatar-border">
                                    <!-- <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="user_avatar" height="42" width="42" />
                                    <span class="avatar-status-online"></span> -->
                                    @if(auth()->user()->profile_pic != null)
                                        @if(file_exists( getcwd(). '/' . auth()->user()->profile_pic))
                                            <img src="{{ asset( request()->root() .'/'. auth()->user()->profile_pic)}}" alt="'s Photo" class="rounded-circle" id="login_usr_logo" width="50px" height="50px">
                                            @else
                                                <img src="{{asset(  $file_path . 'default_imgs/customer.png')}}" id="login_usr_logo" width="50px" height="50px" alt="'s Photo" class="rounded-circle">
                                            @endif
                                        @else
                                        <img src="{{asset( $file_path . 'default_imgs/customer.png')}}" id="login_usr_logo" alt="'s Photo" height="50px" width="50px" class="rounded-circle">
                                    @endif
                                </div>
                            </div>
                            <div class="input-group input-group-merge ms-1 w-100">
                                <span class="input-group-text round"><i data-feather="search" class="text-muted"></i></span>
                                <input type="text" class="form-control round" id="chat-search" placeholder="Search or start a new chat" aria-label="Search..." aria-describedby="chat-search" />
                            </div>
                        </div>
                    </div>
                    <!-- Sidebar header end -->

                    <!-- Sidebar Users start -->
                    <div id="users-list" class="chat-user-list-wrapper list-group">
                        <h4 class="chat-list-title">Chats</h4>
                        <ul class="chat-users-list chat-list media-list">
                            @foreach($users as $user)
                            <li data-id="{{$user->id}}" onclick="showActiveUserChat(this)" data_nm="{{$user->name}}" data_pc="{{$user->profile_pic}}">
                                @if($user->profile_pic != null)
                                    @if(file_exists( getcwd(). '/' . $user->profile_pic))
                                        <span class="avatar"><img height="42" width="42" src="{{ asset( request()->root() .'/'. $user->profile_pic)}}" height="42" width="42"></span>
                                    @else
                                        <span class="avatar"> <img src="{{asset(  $file_path . 'default_imgs/customer.png')}}" height="42" width="42"></span>
                                    @endif
                                @else

                                <span class="avatar"> <img src="{{asset( $file_path . 'default_imgs/customer.png')}}" height="42" width="42"></span>
                                @endif
                                <div class="chat-info">
                                    <h5 class="mb-0">{{$user->name}}</h5>
                                    <span class="badge badge-light-success rounded-pill ms-auto me-1">{{$user->role}}</span>
                                </div>
                            </li>
                            @endforeach
                            <li class="no-results">
                                <h6 class="mb-0">No Chats Found</h6>
                            </li>
                        </ul>
                        <h4 class="chat-list-title">Contacts</h4>
                        <ul class="chat-users-list contact-list media-list">
                            <li class="no-results">
                                <h6 class="mb-0">No Contacts Found</h6>
                            </li>
                        </ul>
                    </div>
                    <!-- Sidebar Users end -->
                </div>
                <!--/ Chat Sidebar area -->

            </div>
        </div>
        <div class="content-right">
            <div class="content-wrapper container-fluid p-0">
                <div class="content-header row">
                </div>
                <div class="content-body">
                    <div class="body-content-overlay"></div>
                    <!-- Main chat area -->
                    <section class="chat-app-window">
                        <!-- To load Conversation -->
                        <div class="start-chat-area">
                            <div class="mb-1 start-chat-icon">
                                <i data-feather="message-square"></i>
                            </div>
                            <h4 class="sidebar-toggle start-chat-text">Start Conversation</h4>
                        </div>
                        <!--/ To load Conversation -->

                        <!-- Active Chat -->
                        <div class="active-chat d-none">
                            <!-- Chat Header -->
                            <div class="chat-navbar">
                                <header class="chat-header">
                                    <div class="d-flex align-items-center">
                                        <div class="sidebar-toggle d-block d-lg-none me-1">
                                            <i data-feather="menu" class="font-medium-5"></i>
                                        </div>
                                        <div class="avatar avatar-border user-profile-toggle m-0 me-1" id="active_user_pic">
                                            <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" id="active_user_img" alt="avatar" height="36" width="36" />
                                            <!-- <span class="avatar-status-busy"></span> -->

                                        </div>
                                        <h6 class="mb-0" id="active_user_name"></h6>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i data-feather="phone-call" class="cursor-pointer d-sm-block d-none font-medium-2 me-1"></i>
                                        <i data-feather="video" class="cursor-pointer d-sm-block d-none font-medium-2 me-1"></i>
                                        <i data-feather="search" class="cursor-pointer d-sm-block d-none font-medium-2"></i>
                                        <div class="dropdown">
                                            <button class="btn-icon btn btn-transparent hide-arrow btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i data-feather="more-vertical" id="chat-header-actions" class="font-medium-2"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions">
                                                <a class="dropdown-item" href="#">View Contact</a>
                                                <a class="dropdown-item" href="#">Mute Notifications</a>
                                                <a class="dropdown-item" href="#">Block Contact</a>
                                                <a class="dropdown-item" href="#">Clear Chat</a>
                                                <a class="dropdown-item" href="#">Report</a>
                                            </div>
                                        </div>
                                    </div>
                                </header>
                            </div>
                            <!--/ Chat Header -->

                            <!-- User Chat messages -->
                            <div class="user-chats">
                                <div class="chats">
                                    <div class="chat">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36" />
                                            </span>

                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>How can we help? We're here for you! 😄</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat chat-left">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="avatar" height="36" width="36" />
                                            </span>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>Hey John, I am looking for the best admin template.</p>
                                                <p>Could you please help me to find it out? 🤔</p>
                                            </div>
                                            <div class="chat-content">
                                                <p>It should be Bootstrap 4 compatible.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divider">
                                        <div class="divider-text">Yesterday</div>
                                    </div>
                                    <div class="chat">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36" />
                                            </span>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>Absolutely!</p>
                                            </div>
                                            <div class="chat-content">
                                                <p>Vuexy admin is the responsive bootstrap 4 admin template.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat chat-left">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="avatar" height="36" width="36" />
                                            </span>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>Looks clean and fresh UI. 😃</p>
                                            </div>
                                            <div class="chat-content">
                                                <p>It's perfect for my next project.</p>
                                            </div>
                                            <div class="chat-content">
                                                <p>How can I purchase it?</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36" />
                                            </span>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>Thanks, from ThemeForest.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat chat-left">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="avatar" height="36" width="36" />
                                            </span>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>I will purchase it for sure. 👍</p>
                                            </div>
                                            <div class="chat-content">
                                                <p>Thanks.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat">
                                        <div class="chat-avatar">
                                            <span class="avatar box-shadow-1 cursor-pointer">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36" />
                                            </span>
                                        </div>
                                        <div class="chat-body">
                                            <div class="chat-content">
                                                <p>Great, Feel free to get in touch on</p>
                                            </div>
                                            <div class="chat-content">
                                                <p>https://pixinvent.ticksy.com/</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- User Chat messages -->

                            <!-- Submit Chat form -->
                            <form class="chat-app-form" id="chat_form">
                                <input type="hidden" name="user_to" id="user_to">
                                <div class="input-group input-group-merge me-1 form-send-message">
                                    <span class="speech-to-text input-group-text"><i data-feather="mic" class="cursor-pointer"></i></span>
                                    <input type="text" id="message" name="message" class="form-control message" placeholder="Type your message or use speech to text" />
                                    <span class="input-group-text">
                                        <label for="attach-doc" class="attachment-icon form-label mb-0">
                                            <i data-feather="image" class="cursor-pointer text-secondary"></i>
                                            <input type="file" id="attach-doc" hidden /> </label></span>
                                </div>
                                <button type="submit" class="btn btn-primary send">
                                    <i data-feather="send" class="d-lg-none"></i>
                                    <span class="d-none d-lg-block">Send</span>
                                </button>
                            </form>
                            <!--/ Submit Chat form -->
                        </div>
                        <!--/ Active Chat -->
                    </section>
                    <!--/ Main chat area -->

                    <!-- User Chat profile right area -->
                    <div class="user-profile-sidebar">
                        <header class="user-profile-header">
                            <span class="close-icon">
                                <i data-feather="x"></i>
                            </span>
                            <!-- User Profile image with name -->
                            <div class="header-profile-sidebar">
                                <div class="avatar box-shadow-1 avatar-border avatar-xl">
                                    <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="user_avatar" height="70" width="70" />
                                    <span class="avatar-status-busy avatar-status-lg"></span>
                                </div>
                                <h4 class="chat-user-name">Kristopher Candy</h4>
                                <span class="user-post">UI/UX Designer 👩🏻‍💻</span>
                            </div>
                            <!--/ User Profile image with name -->
                        </header>
                        <div class="user-profile-sidebar-area">
                            <!-- About User -->
                            <h6 class="section-label mb-1">About</h6>
                            <p>Toffee caramels jelly-o tart gummi bears cake I love ice cream lollipop.</p>
                            <!-- About User -->
                            <!-- User's personal information -->
                            <div class="personal-info">
                                <h6 class="section-label mb-1 mt-3">Personal Information</h6>
                                <ul class="list-unstyled">
                                    <li class="mb-1">
                                        <i data-feather="mail" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">kristycandy@email.com</span>
                                    </li>
                                    <li class="mb-1">
                                        <i data-feather="phone-call" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">+1(123) 456 - 7890</span>
                                    </li>
                                    <li>
                                        <i data-feather="clock" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">Mon - Fri 10AM - 8PM</span>
                                    </li>
                                </ul>
                            </div>
                            <!--/ User's personal information -->

                            <!-- User's Links -->
                            <div class="more-options">
                                <h6 class="section-label mb-1 mt-3">Options</h6>
                                <ul class="list-unstyled">
                                    <li class="cursor-pointer mb-1">
                                        <i data-feather="tag" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">Add Tag</span>
                                    </li>
                                    <li class="cursor-pointer mb-1">
                                        <i data-feather="star" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">Important Contact</span>
                                    </li>
                                    <li class="cursor-pointer mb-1">
                                        <i data-feather="image" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">Shared Media</span>
                                    </li>
                                    <li class="cursor-pointer mb-1">
                                        <i data-feather="trash" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">Delete Contact</span>
                                    </li>
                                    <li class="cursor-pointer">
                                        <i data-feather="slash" class="font-medium-2 me-50"></i>
                                        <span class="align-middle">Block Contact</span>
                                    </li>
                                </ul>
                            </div>
                            <!--/ User's Links -->
                        </div>
                    </div>
                    <!--/ User Chat profile right area -->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script>

    function showActiveUserChat(tag) {

        let user_id  = $(tag).data("id");
        $("#user_to").val(user_id);
        
    }

    $('#chat_form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "{{route('message.index')}}",
            data: new FormData(this),
            async: false,
            processData: false,
            contentType: false,
            beforeSend: function(data) {},
            success: function(data) {
                console.log(data, "response");
            },
            complete: function(data) {},
            failure: function(errMsg) {}
        });
    });

    // $a = 'whatsapp:+923030560951';
    //     $b = explode( ':' , $a);
    //     dd($b[1]);
</script>

@endsection