<script type="text/javascript">
    // bind the server event to get the response data and append it to the message div
    var title = $("title").text();

    channel.bind("default-event", (data) => {
        console.log(data , "data");
        let url = window.location.href;

        if(url.includes('chat')) {

            getAllMessages();
            renderPusherMessages(data.message, data.sender)

        }else{

            $("#unread_msgs").removeClass("d-none");
            let msg_counter = $(".unread_msgs").text() == '' ? 0 : $(".unread_msgs").text();
            $(".unread_msgs").text( parseInt(msg_counter) + 1 );

            toastr['success']( data.sender.name + ' Text you ', 'Message', {
                showMethod: 'slideDown',
                hideMethod: 'slideUp',
                timeOut: 3000,
            });

            jQuery("#msg_my_audio")[0].play();
        }

    });

</script>
