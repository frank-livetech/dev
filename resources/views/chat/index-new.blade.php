@extends('layouts.master-layout-new')
@section('Live Support Chat', 'active')
@section('title', 'Chat')
@section('body')

    <style>
        .whatsapp_chat {
            background-image: url('{{ asset('default_imgs/whatsapp_bg.jpg') }} !important');
            background-color: #f2f0f7;
            background-repeat: repeat;
            background-size: 210px;
        }
        .mt-10{
            margin-top: 81px
        }
        
        .badge-secondary {
            color: #fff;
            background-color: #868e96;
        }
        .badge {
            display: inline-block;
            padding: 0.3rem 0.5rem;
            font-size: 85%;
            font-weight: 600;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.358rem;
        }

    </style>

    <input type="hidden" id="image_url">
    <div class="app-content content chat-application">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-area-wrapper container-fluid p-0 ">
            <div class="sidebar-left">
                <div class="sidebar">
                    <!-- Admin user profile area -->
                    <div class="chat-profile-sidebar">
                        <header class="chat-profile-header">
                            <span class="close-icon">
                                <i data-feather="x"></i>
                            </span>
                            <div class="header-profile-sidebar1">
                                <div class="avatar box-shadow-1 avatar-xl avatar-border">
                                    @php $file_path = Session::get('is_live') == 1 ? 'public/' : '/'; @endphp
                                    @if(Auth::user()->profile_pic != null)
                                        @if(file_exists( getcwd() .'/'. Auth::user()->profile_pic ) )
                                            <img src="{{ asset( request()->root() .'/'. Auth::user()->profile_pic)}}" class="rounded-circle" width="40" height="40" id="profile-user-img" />
                                        @else
                                            <img class="rounded-circle" width="40" height="40" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                        @endif
                                    @else
                                        <img class="rounded-circle" width="40" height="40" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                    @endif
                                </div>
                                <h4 class="chat-user-name">{{ Auth::user()->name }}</h4>
                                <span class="user-post">Admin</span>
                            </div>
                            <!--/ User Information -->
                        </header>
                        <!-- User Details start -->
                        <div class="profile-sidebar-area">
                            <h6 class="section-label mb-1">About</h6>
                            <div class="about-user">
                                <textarea data-length="120" class="form-control char-textarea" id="textarea-counter" rows="5"
                                    placeholder="About User"> {{ auth()->user()->notes }} </textarea>
                                <small class="counter-value float-end"><span class="char-count">108</span> / 120
                                </small>
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
                                            @php $file_path = Session::get('is_live') == 1 ? 'public/' : '/'; @endphp
                                            @if(Auth::user()->profile_pic != null)
                                                @if(file_exists( getcwd() .'/'. Auth::user()->profile_pic ) )
                                                    <img src="{{ asset( request()->root() .'/'. Auth::user()->profile_pic)}}" class="rounded-circle" width="40" height="40" id="profile-user-img" />
                                                @else
                                                    <img class="rounded-circle" width="40" height="40" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                                @endif
                                            @else
                                                <img class="rounded-circle" width="40" height="40" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                            @endif
                                    </div>
                                </div>
                                <div class="input-group input-group-merge ms-1 w-100">
                                    <span class="input-group-text round"><i data-feather="search"
                                            class="text-muted"></i></span>
                                    <input type="text" class="form-control round" id="chat-search"
                                        placeholder="Search or start a new chat" aria-label="Search..."
                                        aria-describedby="chat-search" />
                                </div>
                            </div>
                        </div>
                        <!-- Sidebar header end -->

                        <!-- Sidebar Users start -->
                        <div id="users-list" class="chat-user-list-wrapper list-group">
                            <h4 class="chat-list-title">Chats</h4>
                            <ul class="chat-users-list chat-list media-list">
                                @foreach ($users as $user)
                                    @if (Auth::id() != $user->id)                                           
                                    <li data-id="{{ $user->id }}" onclick="showActiveUserChat(this)"
                                        data_nm="{{ $user->name }}" data-wp="{{ $user->whatsapp }}"
                                        data-pc="{{$user->profile_pic}}"
                                        data-job="{{ $user->job_title }}"
                                        data-about="{{ $user->notes }}">
                                        
                                        <span class="avatar">
                                            @php $file_path = Session::get('is_live') == 1 ? 'public/' : '/'; @endphp
                                            @if($user->profile_pic != null)
                                                @if(file_exists( getcwd() .'/'. $user->profile_pic ) )
                                                    <img src="{{ asset( request()->root() .'/'. $user->profile_pic)}}" class="user_image_{{ $user->id }}" width="40" height="40" id="profile-user-img" />
                                                @else
                                                    <img class="user_image_{{ $user->id }}" width="40" height="40" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                                @endif
                                            @else
                                                <img class="user_image_{{ $user->id }}" width="40" height="40" id="profile-user-img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                            @endif
                                        </span>

                                        <div class="chat-info flex-grow-1">
                                            <h5 class="mb-0"> {{ $user->name }} </h5>
                                            <p class="card-text text-truncate" id="last_msg_{{$user->id}}">
                                                @if($user->last_msg != null)
                                                    @if($user->last_msg->msg_type == 'text')
                                                        {{$user->last_msg->msg_body != null ? $user->last_msg->msg_body : ''}}
                                                    @else
                                                        <i data-feather='camera'></i> <span class="mt-1">Photo</span>
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div class="chat-meta text-nowrap">
                                            
                                                <small>  </small>
                                            
                                            <div class="col-md-12 mt-2" id="unread_specific_user_{{$user->id}}">
                                                @if ($user->unread > 0)
                                                    <span id="unread_{{$user->id}}" style="margin-left: 20px !important;" class="badge bg-danger rounded-pill ms-auto me-1">{{ $user->unread }}</span>
                                                @endif
                                            </div>

                                        </div>
                                    </li>

                                    @endif
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
                                            <div class="avatar avatar-border user-profile-toggle m-0 me-1"
                                                id="active_user_pic">
                                                    @php $file_path = Session::get('is_live') == 1 ? 'public/' : '/'; @endphp
                                                    @if(Auth::user()->profile_pic != null)
                                                        @if(file_exists( getcwd() .'/'. Auth::user()->profile_pic ) )
                                                            <img src="{{ asset( request()->root() .'/'. Auth::user()->profile_pic)}}" class="rounded-circle" width="40" height="40" id="active_user_img" />
                                                        @else
                                                            <img class="rounded-circle" width="40" height="40" id="active_user_img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                                        @endif
                                                    @else
                                                        <img class="rounded-circle" width="40" height="40" id="active_user_img" src="{{asset($file_path .'default_imgs/customer.png')}}" />
                                                    @endif
                                                <!-- <span class="avatar-status-busy"></span> -->

                                            </div>
                                            <h6 class="mb-0" id="active_user_name"></h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="refresh_btn">
                                                <button onclick="getAllMessages()" class="btn btn-primary btn-sm mx-1">
                                                    Refresh Chat </button>
                                            </div>
                                            <div class="web_chat" title="web chat" onclick="webChat()">
                                                <i class="far fa-comment-alt cursor-pointer d-sm-block d-none font-medium-2 me-1"
                                                    style="font-size: 18px; margin-right: 10px;"></i>
                                            </div>
                                            <div class="whatsapp_icon" title="whatsapp chat" onclick="whatsAppChat()">
                                                <i class="fab fa-whatsapp cursor-pointer d-sm-block d-none font-medium-2 me-1"
                                                    style="font-size: 18px; margin-right: 10px;"></i>
                                            </div>
                                            <i data-feather="search"
                                                class="cursor-pointer d-sm-block d-none font-medium-2"></i>

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
                                        
                                        <span class="speech-to-text input-group-text">
                                            <i data-feather="mic" class="cursor-pointer"></i>
                                        </span>

                                        <input type="text" id="message" name="message" class="form-control message" placeholder="Type your message or use speech to text" />

                                        <span class="input-group-text">
                                            <label for="attach-doc" class="attachment-icon form-label mb-0">
                                            <i data-feather="image" class="cursor-pointer text-secondary"></i>
                                            <input type="file"  onchange="loadFile(event)" id="attach-doc" hidden /> </label>
                                        </span>
                                        
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
                                    <div class="d-flex">
                                        <h4 style=" "> 
                                            <a href="" class="chat-user-name"></a>  
                                                <span class="badge badge-secondary type_bdge" style="font-size: 11px"></span> </h4>
                                        {{-- <h4 class="chat-user-name"></h4> --}}
                                    </div>
                                    
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
                                            <span class="align-middle currentChatUserEmail"></span>
                                        </li>
                                        <li class="mb-1">
                                            <i data-feather="phone-call" class="font-medium-2 me-50"></i>
                                            <span class="align-middle currentChatUserNumber"></span>
                                        </li>
                                        <li>
                                            <i data-feather="clock" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Mon - Fri 10AM - 8PM</span>
                                        </li>
                                    </ul>
                                </div>
                                <!--/ User's personal information -->

                                <!-- User's Links -->
                                {{-- <div class="more-options">
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
                                </div> --}}
                                <!--/ User's Links -->
                                <!-- PREFERENCES -->
                                <div class="more-options">
                                    <h6 class="section-label mb-1 mt-3">Preferences</h6>
                                    <ul class="list-unstyled">
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="tag" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Language spoken: English</span>
                                        </li>
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="star" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Assigned account manager: Muhammad Kashif</span>
                                        </li>
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="phone-call" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Best times to call: 1PM TO 4PM</span>
                                        </li>
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="align-right" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Notes: This is note</span>
                                        </li>
                                        <li class="cursor-pointer">
                                            <i data-feather="chevrons-right" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Transfer to: Frank</span>
                                        </li>
                                    </ul>
                                </div>
                                <!--/ PREFERENCES END -->
                                 <!-- Recent activity of users account -->
                                 <div class="more-options">
                                    <h6 class="section-label mb-1 mt-3">Recent activity of users account</h6>
                                    <ul class="list-unstyled">
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="tag" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Last change</span>
                                        </li>
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="star" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Last login attempt failed: 05/05/2022</span>
                                        </li>
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="phone-call" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">User logged in: 10 minutes ago</span>
                                        </li>
                                        <li class="cursor-pointer mb-1">
                                            <i data-feather="align-right" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Muhammad updated ticket abc-123-4567 </span>
                                        </li>
                                        <li class="cursor-pointer">
                                            <i data-feather="chevrons-right" class="font-medium-2 me-50"></i>
                                            <span class="align-middle">Transfer to: Frank</span>
                                        </li>
                                    </ul>
                                </div>
                                <!--/ PREFERENCES END -->
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('scripts')
    <script>
        let users_arr = {!! json_encode($users) !!};
        //Message Types  1 = Whatsapp,2 = Webchat
        var message_type = 2;
        var is_open = 0;
        $(document).ready(function() {
            $("#unread_msgs").addClass('d-none');
        });

        function loadFile(event) {
            let file = event.target.files[0];
            if(file != undefined) {
                $("#attach-doc").attr('name','file');
            }else{
                $("#attach-doc").removeAttr('name','file');
            }
        }

        function showActiveUserChat(tag) {
            let user_id = $(tag).data("id");
            let user = users_arr.find(item => item.id == user_id);
            console.log(user);
            if(user != null) {
            
                $("#active_user_name").text( user.name );
                $('.currentChatUserName').text(user.name);
                $('.currentChatUserNumber').text(user.phone_number);
                $('.currentChatUserEmail').text(user.email);
                $('.currentChatUserCreated').text(user.created_at);
                $('.currentChatUserdesignation').text(user.job_title);
               
            }
            if(user.user_type == 1){
                $(".type_bdge").text( 'Staff' );
            }else{
                $(".type_bdge").text( 'User' );
            }


            let imgsrc = $('.user_image_'+user_id).attr('src');
            $("#active_user_img").attr('src', imgsrc);            

            $("#user_to").val(user_id);
            let src = $('.user_image_' + user_id).attr('src');
            $("#image_url").val(src);
            let whatsapp = $(tag).data("wp");

            $("#unread_"+user_id).empty();

            whatsapp == null || whatsapp == '' ? $('.whatsapp_icon').hide() : $('.whatsapp_icon').show();

            var imageUrl = '{{ asset("default_imgs/webchat_bg.jpg") }}';
            $(".user-chats").css("background-image", "url(" + imageUrl + ")");
            $(".user-chats").css("background-size", "530px");
            webChat();

            $(".header-profile-sidebar .avatar img").attr('src',src)
            $(".user-profile-sidebar .user-profile-header .header-profile-sidebar .chat-user-name").text($(tag).attr("data_nm"))
            $(".user-profile-sidebar .user-profile-header .header-profile-sidebar .user-post").text($(tag).data("job"))
            $(".user-profile-sidebar .user-profile-sidebar-area p").text($(tag).data("about"))

            is_open = user_id;
        }

        function webChat() {
            var imageUrl = '{{ asset("default_imgs/webchat_bg.jpg") }}';
            $(".user-chats").css("background-image", "url(" + imageUrl + ")");
            $(".user-chats").css("background-size", "530px");
            $('.show_chat_messages').html('');
            //Message Types  1 = Whatsapp,2 = Webchat
            message_type = 2;
            getAllMessages();
        }

        function whatsAppChat() {
            var imageUrl = '{{ asset('default_imgs/whatsapp_bg.jpg') }}';
            $(".user-chats").css("background-image", "url(" + imageUrl + ")");
            $(".user-chats").css("background-size", "900px");
            //Message Types  1 = Whatsapp,2 = Webchat
            message_type = 1;
            getAllMessages();
        }

        function getAllMessages() {
            let user_id = $("#user_to").val();
            let url = '';
            if (message_type == 1) {
                url = "{{ route('whatapp.get') }}";
            } else if (message_type == 2) {
                url = "{{ route('webchat.get') }}";
            }

            $("#unread_specific_user").html('')

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: url,
                data: { id: user_id },
                dataType: 'json',
                beforeSend: function(data) {

                },
                success: function(data) {
                    let obj = data.data;
                    // console.log(obj);
                    if (data.status == 200 && data.success == true) {
                        if (data.type == 'whatsapp') {
                            renderWhatsappMessages(obj, data.number);
                        } else {
                            renderWebMessages(obj);
                            $("#unread_msgs").text(data.unread);
                            $("#unread_msgs").addClass('d-none');

                        }
                    } else {
                        $('.show_chat_messages').html('');
                    }

                },
                complete: function(data) {

                },
                error: function(e) {

                }
            });
        }

        function renderWhatsappMessages(obj, number) {

            let img_src = $("#image_url").val();
            let login_user_image_url = $('#login_usr_logo').attr('src');

            let msgs_html = ``;

            if (obj.length > 0) {
                for (let i = 0; i < obj.length; i++) {
                    if (obj[i].to == number) {

                        msgs_html += `
                                <div class="chat">
                                    <div class="chat-avatar">
                                        <span class="avatar box-shadow-1 cursor-pointer">
                                            <img src="${login_user_image_url}" alt="avatar" height="36" width="36" />
                                        </span>

                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
                                            <p> ${obj[i].body != null ? obj[i].body : ''} </p>
                                        </div>
                                    </div>
                                </div>`;
                    }
                    if (obj[i].from == number) {
                        let attachment = ``;
                        if (obj[i].media_type == 'jpeg' || obj[i].media_type == 'png' || obj[i].media_type == 'jpg') {

                            if (obj[i].media_url != null) {
                                attachment = `<img src="${root}/public/${obj[i].media_url}" width="250px" height="250px"/>`;
                            }
                        }


                        if (obj[i].media_type == 'ogg' || obj[i].media_type == 'mp3' || obj[i].media_type == 'wave') {

                            if (obj[i].media_url != null) {
                                attachment = `
                                <audio controls>
                                    <source src="${root}/public/${obj[i].media_url}" type="audio/${obj[i].media_type}" />
                                </audio>
                            `;
                            }
                        }

                        if (obj[i].media_type == 'mp4' || obj[i].media_type == 'mkv' || obj[i].media_type == 'webm' || obj[
                                i].media_type == 'mov') {

                            if (obj[i].media_url != null) {
                                attachment = `
                                <video width="320" height="240" controls>
                                <source src="${root}/public/${obj[i].media_url}" type="video/${obj[i].media_type}">
                                    Your browser does not support the video tag.
                                </video>
                            `;
                            }
                        }

                        let attch = `<div  class="chat-content"> ${attachment} </div>`;
                        let msgbody =
                        `<div class="chat-content"> <p> ${obj[i].body != null ? obj[i].body : ''} </p> </div>`;

                        msgs_html += `
                    <div class="chat chat-left">
                        <div class="chat-avatar">
                            <span class="avatar box-shadow-1 cursor-pointer">
                                <img src="${img_src}" alt="avatar" height="36" width="36" />
                            </span>
                        </div>
                        <div class="chat-body">

                            ${obj[i].body != null ? msgbody : ''}
                            ${obj[i].media_url != null ? attch : ''}

                        </div>
                    </div>`;
                    }
                }
                $('.show_chat_messages').html(msgs_html);
            } else {
                $('.show_chat_messages').html('');
            }
        }

        function renderWebMessages(obj) {

            let img_src = $("#image_url").val();
            let login_user_image_url = $('#login_usr_logo').attr('src');
            let auth = parseInt("{{Auth::id()}}");
            let msgs_html = ``;

            if (obj.length > 0) {
                for (let i = 0; i < obj.length; i++) {
                    if (obj[i].sender_id == auth) {
                        let message = ``;
                        if(obj[i].msg_type.includes('image')){
                            message = `<img src="{{asset('`+obj[i].msg_body+`')}}" alt="file"  width="150px"/>`;
                        }else{
                            message = `<p> ${obj[i].msg_body != null ? obj[i].msg_body : ''} </p>`;
                        }

                        msgs_html += `
                                <div class="chat">
                                    <div class="chat-avatar">
                                        <span class="avatar box-shadow-1 cursor-pointer">
                                            <img src="${login_user_image_url}" alt="avatar" height="36" width="36" />
                                        </span>

                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
                                            ${message}
                                        </div>
                                    </div>
                                </div>`;
                    }
                    if (obj[i].reciever_id == auth) {
                        let message = ``;

                        if(obj[i].msg_type.includes('image')){
                            message = `<div  class="chat-content"><img src="{{asset('`+obj[i].msg_body+`')}}" width="150px" alt="file" /> </div>`;
                        }else{
                            message = `<div class="chat-content"> <p> ${obj[i].msg_body != null ? obj[i].msg_body : ''} </p> </div>`;
                        }

                        msgs_html += `
                            <div class="chat chat-left">
                                <div class="chat-avatar">
                                    <span class="avatar box-shadow-1 cursor-pointer">
                                        <img src="${img_src}" alt="avatar" height="36" width="36" />
                                    </span>
                                </div>
                                <div class="chat-body">
                                    ${message}
                                </div>
                            </div>`;
                    }
                }
                $('.show_chat_messages').html(msgs_html);
            } else {
                $('.show_chat_messages').html('');
            }
        }

        function renderPusherMessages(message,sender) {

            let counter = $("#unread_"+sender.id).text();
            if(counter != '') {
                if(is_open == sender.id) {
                    $("#unread_"+sender.id).text('');
                }else{
                    $("#unread_"+sender.id).text( parseInt(counter) + 1 );
                }
            }else{
                let badge = `<span id="unread_${sender.id}" style="margin-left: 20px !important;" class="badge bg-danger rounded-pill ms-auto me-1">1</span>`;
                if(is_open == sender.id) {
                    $("#unread_specific_user_" + sender.id).html('');
                }else{
                    $("#unread_specific_user_" + sender.id).html(badge);
                }
                
            }

            if(message.msg_type == 'text') {
                $("#last_msg_"+sender.id).text( message.msg_body );
            }else{
                let svg = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-camera"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>`;
                $("#last_msg_"+sender.id).html( svg + ' Photo' );
            }
            
            let msg = ``;
            if(message.type == 'file'){
                msg = `<div  class="chat-content ${message.type == 'file' ? 'p-0' : ''}"> ${message.msg_body} </div>`;
            }else{
                msg = `<div class="chat-content"> <p> ${message.msg_body != null ? message.msg_body : ''} </p> </div>`;
            }

            var  msgs_html = `
                <div class="chat chat-left">
                    <div class="chat-avatar">
                        <span class="avatar box-shadow-1 cursor-pointer">
                            <img src="${sender.profile_pic}" alt="avatar" height="36" width="36" />
                        </span>
                    </div>
                    <div class="chat-body">
                        ${msg}
                    </div>
                </div>`;

            $('.show_chat_messages').append(msgs_html);
        }

        $('#chat_form').submit(function(e) {
            e.preventDefault();

            let message = $("#message").val();

            if(message != ''){
                message = 'true';
            }else if($("#attach-doc").val() != ''){
                message = 'true';
            } else if( $("#message").val() != '' && $("#attach-doc").val() != ''){
                message = 'true';
            }


            let url = '';
            if (message_type == 1) {
                url = "{{ route('message.index') }}";
            } else if (message_type == 2) {
                url = "{{ route('send.webchat') }}";
            }

            if (message == '') {
                toastr.error(' Type your message', {
                    timeOut: 5000
                });
            } else {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    url: url,
                    data: new FormData(this),
                    async: false,
                    processData: false,
                    contentType: false,
                    beforeSend: function(data) {},
                    success: function(data) {
                        // console.log(data);
                        // toastr.success(data.message, {
                        //     timeOut: 5000
                        // });

                        is_open = 0;
                        
                        $("#attach-doc").removeAttr('name','file');

                        $("#message").val("");
                        let output_message = '';
                        if(data.message_type.includes('image')){
                            output_message = `<img src="{{asset('${data.data.msg_body}')}}" width="150px" alt="file" />`;
                        }else{
                            output_message = `<p> ${data.data.msg_body != null ? data.data.msg_body : ''} </p>`;
                        }

                        let msg = `
                        <div class="chat">
                            <div class="chat-avatar">
                                <span class="avatar box-shadow-1 cursor-pointer">
                                    <img src="{{asset(getDefaultProfilePic(Auth::user()->profile_pic))}}" alt="avatar" height="36" width="36">
                                </span>

                            </div>
                            <div class="chat-body">
                                <div class="chat-content">
                                    ${output_message}
                                </div>
                            </div>
                        </div>`;
                        $('.show_chat_messages').append(msg);
                        $('.user-chats').scrollTop($('.user-chats > .chats').height());
                        $('#chat_form').trigger("reset");
                    },
                    complete: function(data) {},
                    failure: function(errMsg) {}
                });
            }
        });
    </script>

@endsection
