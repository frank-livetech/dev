<script src="https://js.pusher.com/7.0.2/pusher.min.js"></script>
<script>    
    var pusher = new Pusher("{{ pusherCredentials('key') }}", {
        cluster: "{{ pusherCredentials('cluster') }}",
    });

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
            let msg_counter = $("#unread_msgs").text() == '' ? 0 : $("#unread_msgs").text();
            $("#unread_msgs").text( parseInt(msg_counter) + 1 );

            
            // const ctx = new (window.AudioContext || window.webkitAudioContext)();

            // const osc = ctx.createOscillator();

            // osc.connect(ctx.destination);

            // osc.start(0);
            // osc.stop(2);

            // osc.onended = () => {
            //     console.log(ctx.state);
            // }

            document.body.addEventListener("mousemove", function () {
                var buzzer = $('.msg_my_audio')[0];  
                buzzer.play(); 
                console.log("buzzer");
            })

            
        }
        
    });

</script>
