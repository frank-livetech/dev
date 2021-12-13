
  var senderName = $("#current_user_name").val();
  var sender_id = $("#current_user_id").val();
$(document).ready(function() {
    // Your web app's Firebase configuration

    getMessages();
});


function sendMessage() {

    var msg = $("#message").val();

    var m = firebase.database().ref('messages').push().set({
        "senderName" : senderName,
        "senderId" : sender_id,
        "message" : msg,
        "date" : moment().format("YYYY-MM-DD HH:mm:ss"),
    });
}


function getMessages() {
    firebase.database().ref("messages").on("child_added" , function(snap) {

        var html = `
            <li class="odd mt-4 right">
                <div class="pr-4 text-right">
                    <h5 class="text-muted small"> ${snap.val().senderName} @ ${snap.val().date} </h5>
                    <div class="box mb-2 d-inline-block text-dark rounded p-2 bg-light-inverse"> ${snap.val().message} </div> <br>
                </div>
                
            </li>
        `;
        $(".chat").append(html);
        console.log(snap , "snap");
    });
}