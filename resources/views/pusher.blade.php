<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher("{{env('PUSHER_APP_KEY')}}", {
      cluster: 'mt1'
    });

    var channel = pusher.subscribe('test-pusher');
    channel.bind('test-pusher', function(data) {
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <p>
    Try publishing an event to channel <code>test-pusher</code>
    with event name <code>test-pusher</code>.
  </p>
</body>
