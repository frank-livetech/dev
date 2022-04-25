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

        let msg_counter = $(".noti_count").text() == '' ? 0 : $(".noti_count").text();
        $(".noti_count").text( parseInt(msg_counter) + 1 );

        if(notify != null) {

            if(notify.sender_id != notify.receiver_id) {



                toastr['info']( notify.noti_desc , notify.noti_title, {
                    closeButton: true,
                    tapToDismiss: false,
                    timeOut: 300000000,
                });

            }

        }

    });

</script>
