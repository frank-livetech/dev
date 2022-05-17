<script src="https://js.pusher.com/7.0.2/pusher.min.js"></script>
<script type="text/javascript">    
    var pusher = new Pusher("{{ pusherCredentials('key') }}", {
        cluster: "{{ pusherCredentials('cluster') }}",
    });
    // Enter a unique channel you wish your users to be subscribed in.
    var channel = pusher.subscribe('notification.'+`{{Auth::id()}}`);
    // bind the server event to get the response data and append it to the message div

    channel.bind("notification-event", (data) => {
        console.log(data , "pusher notification");
        let notify = data.message;

        if(notify.sender_id != null) {

            if(notify.sender_id != notify.receiver_id) {


                let msg_counter = $(".noti_count").text() == '' ? 0 : $(".noti_count").text();
                $(".noti_count").text( parseInt(msg_counter) + 1 );
                $('.noti_count').addClass('badge rounded-pill bg-danger badge-up ');


                appendNotification(notify.icon , notify.noti_title , notify.noti_desc)

                // if(notify.noti_desc != null && notify.noti_title != null) {
                    toastr['info']( notify.noti_desc , notify.noti_title, {
                        closeButton: true,
                        tapToDismiss: false,
                        timeOut: 10000,
                    });

                    jQuery("#msg_my_audio")[0].play();
                // }
            }
        }

    });


    function appendNotification(icon , title , desc) {
        let html = `<a class="d-flex" href="#" onclick="markRead(15)" style="cursor: pointer;">
            <div class="list-item d-flex align-items-start">
                <div class="me-1">
                    <div class="avatar">
                        <span class="btn-success rounded-circle btn-circle" "="" style="padding:8px 12px">
                    <i data-feather="${icon}"></i>
                    </span>
                    </div>
                </div>
                <div class="list-item-body flex-grow-1">
                    <p class="media-heading">
                    <span class="fw-bolder">${title}</span>
                    <span class="float-end">${moment().format('hh:mm A')}</span> </p>
                    <small class="notification-text">${desc}</small>
                </div>
            </div>
        </a>`;

        $('.list_all_notifications').prepend(html);
    }

</script>
