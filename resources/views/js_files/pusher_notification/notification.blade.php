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
    });

</script>
