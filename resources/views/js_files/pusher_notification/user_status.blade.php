<script type="text/javascript">
    // Enter a unique channel you wish your users to be subscribed in.
    var channelUser = pusher.subscribe('online-user');
        // bind the server event to get the response data and append it to the message div

    $.ajax({
        url: "{{route('make.online.user')}}",
        dataType: "json",
        type: "Post",
        async: true,
        data: { _token: "{{csrf_token()}}",online:true},
        success: function (data) {

        },

    });

    channelUser.bind("online-user-event", (data) => {
        if(data.status == true){
            $.ajax({
                url: "{{route('show.all.user')}}",
                dataType: "json",
                type: "get",
                async: true,
                success: function (users) {
                    for (const user of users) {
                        $("#user-"+user.id).html('<span class="avatar-status-online"></span>');
                    }
                },

            });
        }

    });


</script>
y
