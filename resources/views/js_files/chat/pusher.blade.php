<script src="https://js.pusher.com/7.0.2/pusher.min.js"></script>
<script type="text/javascript">    
    var pusher = new Pusher("{{ pusherCredentials('key') }}", {
        cluster: "{{ pusherCredentials('cluster') }}",
    });
    var song;

    // Enter a unique channel you wish your users to be subscribed in.
    var channel = pusher.subscribe('support-chat.'+`{{Auth::id()}}`);
    // bind the server event to get the response data and append it to the message div

    var title = $("title").text();

    channel.bind("support-chat-event", (data) => {
        console.log(data , "data");
        let url = window.location.href;
        
        if(url.includes('chat')) {

            getAllMessages();
            renderPusherMessages(data.message, data.sender)

        }else{

            $("#unread_msgs ").removeClass("d-none");
            let msg_counter = $(".unread_msgs").text() == '' ? 0 : $(".unread_msgs").text();
            $(".unread_msgs").text( parseInt(msg_counter) + 1 );
            
            jQuery("#msg_my_audio")[0].play();

        }
        
    });

</script>
