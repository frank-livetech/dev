@extends('layouts.master-layout-new')
@section('Live Support Chat', 'active')
@section('title', 'Chat')
@section('body')
    @php
    $file_path = Session::get('is_live') == 1 ? 'public/' : '/';
    $path = Session::get('is_live') == 1 ? 'public/system_files/' : 'system_files/';
    @endphp
    {{-- <script src="{{asset('js/app.js')}}"></script> --}}
    <style>
        .whatsapp_chat {
            background-image: url('{{ asset('default_imgs/whatsapp_bg.jpg') }} !important');
            background-color: #f2f0f7;
            background-repeat: repeat;
            background-size: 210px;
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
                            <div class="header-profile-sidebar">
                                <div class="avatar box-shadow-1 avatar-xl avatar-border">
                                    @if (auth()->user()->profile_pic != null)
                                        @if (file_exists(getcwd() . '/' . auth()->user()->profile_pic))
                                            <img src="{{ asset(request()->root() . '/' . auth()->user()->profile_pic) }}"
                                                alt="'s Photo" class="rounded-circle" id="login_usr_logo" width="50px"
                                                height="50px">
                                        @else
                                            <img src="{{ asset($file_path . 'default_imgs/customer.png') }}"
                                                id="login_usr_logo" width="50px" height="50px" alt="'s Photo"
                                                class="rounded-circle">
                                        @endif
                                    @else
                                        <img src="{{ asset($file_path . 'default_imgs/customer.png') }}"
                                            id="login_usr_logo" alt="'s Photo" height="50px" width="50px"
                                            class="rounded-circle">
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
                                        <!-- <img src="../../../app-assets/images/portrait/small/avatar-s-11.jpg" alt="user_avatar" height="42" width="42" />
                                        <span class="avatar-status-online"></span> -->
                                        @if (auth()->user()->profile_pic != null)
                                            @if (file_exists(getcwd() . '/' . auth()->user()->profile_pic))
                                                <img src="{{ asset(request()->root() . '/' . auth()->user()->profile_pic) }}"
                                                    alt="'s Photo" class="rounded-circle" id="login_usr_logo" width="50px"
                                                    height="50px">
                                            @else
                                                <img src="{{ asset($file_path . 'default_imgs/customer.png') }}"
                                                    id="login_usr_logo" width="50px" height="50px" alt="'s Photo"
                                                    class="rounded-circle">
                                            @endif
                                        @else
                                            <img src="{{ asset($file_path . 'default_imgs/customer.png') }}"
                                                id="login_usr_logo" alt="'s Photo" height="50px" width="50px"
                                                class="rounded-circle">
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
                                    <li data-id="{{ $user->id }}" onclick="showActiveUserChat(this)"
                                        data_nm="{{ $user->name }}" data-wp="{{ $user->whatsapp }}"
                                        data_pc="{{ $user->profile_pic }}">
                                        @if ($user->profile_pic != null)
                                            @if (file_exists(getcwd() . '/' . $user->profile_pic))
                                                <span class="avatar"><img height="42"
                                                        class="user_image_{{ $user->id }}" width="42"
                                                        src="{{ asset(request()->root() . '/' . $user->profile_pic) }}"
                                                        height="42" width="42"></span>
                                            @else
                                                <span class="avatar"> <img class="user_image_{{ $user->id }}"
                                                        src="{{ asset($file_path . 'default_imgs/customer.png') }}"
                                                        height="42" width="42"></span>
                                            @endif
                                        @else
                                            <span class="avatar"> <img class="user_image_{{ $user->id }}"
                                                    src="{{ asset($file_path . 'default_imgs/customer.png') }}"
                                                    height="42" width="42"></span>
                                        @endif
                                        <div class="chat-info">
                                            <h5 class="mb-0">{{ $user->name }}</h5>
                                            <span
                                                class="badge badge-light-success rounded-pill ms-auto me-1">{{ $user->role }}</span>
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
                                            <div class="avatar avatar-border user-profile-toggle m-0 me-1"
                                                id="active_user_pic">
                                                <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg"
                                                    id="active_user_img" alt="avatar" height="36" width="36" />
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
                                        <span class="speech-to-text input-group-text"><i data-feather="mic"
                                                class="cursor-pointer"></i></span>
                                        <input type="text" id="message" name="message" class="form-control message"
                                            placeholder="Type your message or use speech to text" />
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
                                        <img src="../../../app-assets/images/portrait/small/avatar-s-7.jpg"
                                            alt="user_avatar" height="70" width="70" />
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

    <script src="https://js.pusher.com/7.0.2/pusher.min.js"></script>
    <script>
        // Add API Key & cluster here to make the connection
        var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
        });

        // Enter a unique channel you wish your users to be subscribed in.
        var channel = pusher.subscribe('support-chat.'+`{{Auth::id()}}`);
        // bind the server event to get the response data and append it to the message div
        channel.bind("support-chat-event", (data) => {
            renderPusherMessages(data.message,data.sender)
        });

        //Message Types  1 = Whatsapp,2 = Webchat
        var message_type = 0;

        function showActiveUserChat(tag) {

            let user_id = $(tag).data("id");
            $("#user_to").val(user_id);
            let src = $('.user_image_' + user_id).attr('src');
            $("#image_url").val(src);
            let whatsapp = $(tag).data("wp");

            whatsapp == null || whatsapp == '' ? $('.whatsapp_icon').hide() : $('.whatsapp_icon').show();

            var imageUrl = '{{ asset('default_imgs/webchat_bg.jpg') }}';
            $(".user-chats").css("background-image", "url(" + imageUrl + ")");
            $(".user-chats").css("background-size", "530px");
        }

        function webChat() {
            var imageUrl = '{{ asset('default_imgs/webchat_bg.jpg') }}';
            $(".user-chats").css("background-image", "url(" + imageUrl + ")");
            $(".user-chats").css("background-size", "530px");
            $('.show_chat_messages').html('');
            //Message Types  1 = Whatsapp,2 = Webchat
            message_type = 2;
            getAllMessages(message_type);
        }

        function whatsAppChat() {
            var imageUrl = '{{ asset('default_imgs/whatsapp_bg.jpg') }}';
            $(".user-chats").css("background-image", "url(" + imageUrl + ")");
            $(".user-chats").css("background-size", "900px");
            //Message Types  1 = Whatsapp,2 = Webchat
            message_type = 1;
            getAllMessages(message_type);
        }

        function getAllMessages(type) {
            let user_id = $("#user_to").val();
            let url = '';
            if (type == 1) {
                url = "{{ route('whatapp.get') }}";
            } else if (type == 2) {
                url = "{{ route('webchat.get') }}";
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: url,
                data: {
                    id: user_id
                },
                dataType: 'json',
                beforeSend: function(data) {

                },
                success: function(data) {
                    let obj = data.data;
                    if (data.status == 200 && data.success == true) {
                        if (data.type == 'whatsapp') {
                            renderWhatsappMessages(obj, data.number);
                        } else {
                            renderWebMessages(obj);
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

                        msgs_html += `
                                <div class="chat">
                                    <div class="chat-avatar">
                                        <span class="avatar box-shadow-1 cursor-pointer">
                                            <img src="${login_user_image_url}" alt="avatar" height="36" width="36" />
                                        </span>

                                    </div>
                                    <div class="chat-body">
                                        <div class="chat-content">
                                            <p> ${obj[i].msg_body != null ? obj[i].msg_body : ''} </p>
                                        </div>
                                    </div>
                                </div>`;
                    }
                    if (obj[i].reciever_id == auth) {
                        let message = ``;
                        if(obj[i].msg_type == 'file'){
                            message = `<div  class="chat-content"> ${obj[i].msg_body} </div>`;
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

        function renderPusherMessages(message,sender)
        {
            let msg = ``;
            if(message.msg_type == 'file'){
                msg = `<div  class="chat-content"> ${message.msg_body} </div>`;
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
                        toastr.success(data.message, {
                            timeOut: 5000
                        });
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
            }
        });
    </script>

@endsection
