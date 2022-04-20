<script src="https://js.pusher.com/7.0.2/pusher.min.js"></script>
<script>
    // Add API Key & cluster here to make the connection
    var pusher = new Pusher("{{ pusherCredentials('key') }}", {
        cluster: "{{ pusherCredentials('cluster') }}",
    });

    // Enter a unique channel you wish your users to be subscribed in.
    var channel = pusher.subscribe('support-chat.'+`{{Auth::id()}}`);
    // bind the server event to get the response data and append it to the message div

    var title = $("title").text();

    channel.bind("support-chat-event", (data) => {
        // if(location.pathname != '/chat'){
        //     var first = $("#unread_msgs").text() != '' ? $("#unread_msgs").text() : 0
        //     var unread = parseInt(first)+1
        //     $("#unread_msgs").text(unread)
        //     $("#unread_msgs").removeClass('d-none')
        //     var audio = new Audio('{{asset("assets/sound/whatsapp.mp3")}}');
        //     audio.play()
        //     var html = '('+ unread +') '+ title +
        //     console.log(html,title)
        //     $("title").html(html)
        // }else{
        // }
        getAllMessages();
        renderPusherMessages(data.message, data.sender)
    });


</script>
