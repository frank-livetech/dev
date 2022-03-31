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
                        <div class="header-profile-sidebar">
                            <div class="avatar box-shadow-1 avatar-xl avatar-border">
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
                            <textarea data-length="120" class="form-control char-textarea" id="textarea-counter" rows="5" placeholder="About User"> {{auth()->user()->notes}} </textarea>
                            <small class="counter-value float-end"><span class="char-count">108</span> / 120 </small>
                        </div>
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
                                        <div class="refresh_btn">
                                            <button onclick="getAllMessages()" class="btn btn-primary btn-sm mx-1"> refresh chat </button>
                                        </div>
                                        <div class="whatsapp_icon">
                                            <i class="fab fa-whatsapp cursor-pointer d-sm-block d-none font-medium-2 me-1"  style=" font-size: 18px; margin-right: 10px;"></i>
                                        </div>
                                        <i data-feather="search" class="cursor-pointer d-sm-block d-none font-medium-2"></i>
                                        
                                    </div>
                                </header>
                            </div>
                            <!--/ Chat Header -->

                            <!-- User Chat messages -->
                            <div class="user-chats">
                                <div class="chats show_chat_messages">

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
                        </div>
                    </div>

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

        getAllMessages();
    }

    function getAllMessages() {
        let user_id = $("#user_to").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "{{route('whatapp.get')}}",
            data : {id:user_id},
            dataType: 'json',
            beforeSend:function(data) {

            },
            success: function(data) {
                let obj = data.data;
                renderMessages(obj , data.number);
            },
            complete:function(data) {

            },
            error:function(e) {

            }
        });
    }

    function renderMessages(obj , number) {
        console.log(obj , "obj");
        console.log(number , "number");
        let msgs_html = ``;
        $('.show_chat_messages').html('');

        if(obj.length > 0) {

            for(let i =0; i < obj.length; i++) {


                if(obj[i].to == number) {
                    msgs_html +=`
                    <div class="chat">
                        <div class="chat-avatar">
                            <span class="avatar box-shadow-1 cursor-pointer">
                                <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36" />
                            </span>

                        </div>
                        <div class="chat-body">
                            <div class="chat-content">
                                <p> ${obj[i].body != null ? obj[i].body : ''} </p>
                            </div>
                        </div>
                    </div>`;
                }

                if(obj[i].from == number) {
                    msgs_html += `
                    <div class="chat chat-left">
                        <div class="chat-avatar">
                            <span class="avatar box-shadow-1 cursor-pointer">
                                <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg" alt="avatar" height="36" width="36" />
                            </span>
                        </div>
                        <div class="chat-body">
                            <div class="chat-content">
                                <p> ${obj[i].body != null ? obj[i].body : ''} </p>
                            </div>
                        </div>
                    </div>`;
                }

                
            }

            $('.show_chat_messages').html(msgs_html);
        }else{
            $('.show_chat_messages').html('');
        }
    }

    $('#chat_form').submit(function(e) {
        e.preventDefault();
        
        let message = $("#message").val();
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
                toastr.success( data.message , { timeOut: 5000 });
                $("#message").val("");

                let msg = `
                    <div class="chat">
                        <div class="chat-avatar">
                            <span class="avatar box-shadow-1 cursor-pointer">
                                <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="avatar" height="36" width="36">
                            </span>

                        </div>
                        <div class="chat-body">
                            <div class="chat-content">
                                <p> ${message} </p>
                            </div>
                        </div>
                    </div>`;
                $('.show_chat_messages').append(msg);

                $('.user-chats').scrollTop($('.user-chats > .chats').height());

            },
            complete: function(data) {},
            failure: function(errMsg) {}
        });
    });

</script>

@endsection