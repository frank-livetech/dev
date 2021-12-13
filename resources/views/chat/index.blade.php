@extends('layouts.staff-master-layout')
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    body {
        background-color: #fff;
    }

    .chat-meta-user {
        height: 64px;
    }

    .headIcon {
        font-size: 29px;
        padding-left: 5px;
        color: grey;
    }

    .rightIcon {
        font-size: 20px;
        padding-left: 5px;
        color: grey;
    }

    /* .right-part {
        width: calc(100% - 620px)
    } */

    /* .right-info p {
        margin-bottom: 2px;
    } */

    .tags {
        padding: 3px 7px;
        border-radius: 22px;
        font-size: 13px;
        color: #fff;
        background: #339AF0;
    }

    .extent-right .card {
        margin-bottom: 2px;

    }

    .prefrence p {
        margin-bottom: 2px;
        font-size: 14px;
    }
</style>
@endpush
@section('body-content')

<input type="hidden" id="current_user_name" value="{{Auth::user()->name}}">
<input type="hidden" id="current_user_id" value="{{Auth::user()->id}}">

<div class="chat-application">
    <div class="left-part  bg-white fixed-left-part user-chat-box" style="position: absolute;
                        overflow: hidden; max-height:603px;">
        
        <a class="ti-menu ti-close btn btn-success show-left-part d-block d-md-none" href="javascript:void(0)"></a>
        
        <div class="p-3">
            <h4>Chat Sidebar</h4>
        </div>
        <div class="card scrollable position-relative" style="height:100%;">
            <div class="p-3 border-bottom">
                <h5 class="card-title">Search Contact</h5>
                <form>
                    <div class="searchbar">
                        <input class="form-control" type="text" placeholder="Search Contact">
                    </div>
                </form>
            </div>
            <ul class="mailbox list-style-none app-chat">
                <li>
                    <div class="message-center chat-scroll chat-users">
                        @foreach($users as $user)
                        <a href="javascript:void(0)" class="chat-user message-item align-items-center border-bottom px-3 py-2" id='chat_user_{{$user->id}}' data-user-id='{{$user->id}}'>
                            <span class="user-img position-relative d-inline-block">
                                <img src="{{ $user->profile_pic ? URL::asset('files/user_photos/'.$user->profile_pic): URL::asset('files/user_photos/user-photo.jpg')}}" style="width:40px; height:42px" alt="user" class="rounded-circle w-100">
                                <span class="profile-status online rounded-circle pull-right"></span>
                            </span>
                            <div class="mail-contnet w-75 d-inline-block v-middle pl-2">
                                <h5 class="message-title mb-0 mt-1" data-username="{{$user->name}}">{{$user->name}}</h5>
                                <span class="font-12 text-nowrap d-block text-muted text-truncate">Chat with me</span> <span class="font-12 text-nowrap d-block text-muted">9:30 AM</span>
                            </div>
                        </a>
                        @endforeach

                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="right-part bg-white chat-container">
        <div class="p-20 chat-box-inner-part">
            
            <div class="chat-not-selected">
                <p style="text-align:center">Advance Chat Features Coming Soon
                    Support for SMS, WhatsApp, FaceBook, Website, Mobile App and more all centerilized in one place. Release Planned April 1st</p>
                <div class="text-center">
                    <span class="display-5 text-info"><i class="mdi mdi-comment-outline"></i></span>
                    <h5>Open chat from the list</h5>
                </div>
            </div>
            
            <div class=" chatting-box mb-0">
                <div class="card-body">
                    <div class="chat-meta-user pb-3 border-bottom">
                        <div class="current-chat-user-name">
                            <div class="row">
                                <div class="col-md-8">
                                    <span>
                                        <img src="../assets/images/users/1.jpg" alt="dynamic-image" class="rounded-circle" width="50" height="45">
                                        <span class="name font-weight-bold ml-2"></span>
                                    </span>
                                </div>
                                <div class="col-md-4 pt-2 text-right">
                                    <span class="">
                                        <a href="#"><i class="fab fa-facebook headIcon"></i></a>
                                        <a href="#"><i class="fab fa-whatsapp headIcon"></i></a>
                                        <a href="#"><i class="fas fa-globe headIcon"></i></a>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="chat-box scrollable" style="height:calc(100vh - 310px);">
                        <ul class="chat-list chat" data-user-id="8">

         
                        </ul>
                    </div>
                </div>
                <div class="card-body border-top border-bottom chat-send-message-footer">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-field mt-0 mb-0">
                                <input id="message" placeholder="Type and hit enter" style="font-family:Arial, FontAwesome" class="message-type-box form-control border-0" type="text">
                                <button class="btn btn-primary btn-sm rounded send-button" onclick="sendMessage()" style="position: absolute; top: 0px; right: 10px;">send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



@endsection
@section('scripts')

<script src="{{asset('public/js/chat/chat.js').'?ver='.rand()}}"></script>
<script>
    
</script>
@endsection