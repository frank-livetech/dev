<script type="text/javascript">

    // Enter a unique channel you wish your users to be subscribed in.
    var channel = pusher.subscribe('notification.'+`{{Auth::id()}}`);
    // var presenceChannel = pusher.subscribe('presenceChannelName');

    if(is_online_notif == 0){
        $.ajax({
            url: "{{route('make.online.user')}}",
            dataType: "json",
            type: "Post",
            async: true,
            data: { _token: "{{csrf_token()}}",online:true},
            success: function (data) {
                console.log(data)
            },
        });
    }else if(is_online_notif == ''){
        $.ajax({
            url: "{{route('make.online.user')}}",
            dataType: "json",
            type: "Post",
            async: true,
            data: { _token: "{{csrf_token()}}",online:false},
            success: function (data) {
                console.log(data)
            },
        });
    }


    channel.bind("notification-event", (data) => {
        let notify = data.message;

        console.log(data,"logged user")

        if(notify.sender_id != null) {
            if(notify.sender_id != notify.receiver_id) {

                let msg_counter = $(".noti_count").text() == '' ? 0 : $(".noti_count").text();
                $(".noti_count").text( parseInt(msg_counter) + 1 );
                $('.noti_count').addClass('badge rounded-pill bg-danger badge-up ');

                if($notify.noti_title == 'LoggedInUser'){
                    $.ajax({
                        url: "{{route('show.all.user')}}",
                        dataType: "json",
                        type: "get",
                        async: true,
                        success: function (users) {
                            for (const user of users) {
                                $('#user-status-'+notify.sender_id).html('<span class="avatar-status-online"></span>');
                            }
                        },

                    });
                    
                }else{
                    appendNotification(notify.noti_icon , notify.noti_title , notify.noti_desc)

                // if(notify.noti_desc != null && notify.noti_title != null) {
                    toastr['info']( notify.noti_desc , notify.noti_title, {
                        closeButton: true,
                        tapToDismiss: false,
                        timeOut: 10000,
                    });

                    jQuery("#msg_my_audio")[0].play();
                }

                
                // }
            }
        }

    });


    function appendNotification(icon , title , desc) {

        let time = moment( new Date().toLocaleString('en-US', { timeZone: "{{Session::get('timezone')}}" })).format('hh:mm A');

        let html = `
        <div class="list-item d-flex align-items-start" style="cursor:pointer">
            <div class="me-1">
                <div class="avatar">
                    <span class="btn-success rounded-circle btn-circle" "="" style="padding:8px 12px">
                        <i data-feather="${icon}"></i>
                    </span>
                </div>
            </div>
            <div class="list-item-body flex-grow-1">
                <p class="media-heading">
                <span class="fw-bolder"> ${title} </span>
                <span class="float-end"> ${time} </span> </p>
                <small class="notification-text"> ${desc} </small>
            </div>
        </div>`;

        $('.list_all_notifications').prepend(html);
        feather.replace();
    }

</script>
