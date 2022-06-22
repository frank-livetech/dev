
<script>

    // My Task Details Script Blade
    $(document).ready(function() {

        setTimeout(() => {
            $("#tsk_detail").hide();
            $("#tsk_attchmnt").hide();
        },2000);

        // complete task 
        $("#completeTaskForm").submit(function(event) {
            event.preventDefault();
            var url = $(this).attr('action');
            var method = $(this).attr('method');

            var remarks = $("#remarks").val();
            var task_id = $("#task_id").val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: method,
                url: url,
                data: {
                    task_status: 'success',
                    task_id: task_id,
                    remarks: remarks
                },
                dataType: 'json',
                beforeSend:function(data) {
                    $("#cmp_btn").hide();
                    $("#processing").show();
                },  
                success: function(data) {
                    console.log(data);
                    if (data.status_code == 200 & data.success == true) {

                        alertNotification('success', 'Success' ,data.message );
                    } else {
                        alertNotification('error', 'Error' ,data.message );
                    }

                    $("#closingRemarks").modal('hide');

                    setTimeout(() => {
                        location.href = document.referrer;
                    },2000);


                },
                complete:function(data) {
                    $("#cmp_btn").show();
                    $("#processing").hide();
                },
                error: function(e) {
                    console.log(e);
                    $("#cmp_btn").show();
                    $("#processing").hide();
                }
            });
        });     


        // revert task
        $("#revertTaskForm").submit(function(event) {
            event.preventDefault();
            var url = $(this).attr('action');
            var method = $(this).attr('method');

            var remarks = tinymce.get('tinymceEditor').getContent();
            var task_id = $("#tsk_id").val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: method,
                url: url,
                data: {
                    task_id: task_id,
                    remarks: remarks,
                },
                dataType: 'json',
                beforeSend:function(data) {
                    $("#cmp_btn").hide();
                    $("#processing").show();
                },  
                success: function(data) {
                    console.log(data);
                    if (data.status_code == 200 & data.success == true) {

                        alertNotification('success', 'Success' ,data.message );
                        $("#revertTaskModal").modal('hide');
                    } else {
                        alertNotification('error', 'Error' ,data.message );
                    }
                    setTimeout(() => {
                        location.href = document.referrer;
                    },2000);


                },
                complete:function(data) {
                    $("#cmp_btn").show();
                    $("#processing").hide();
                },
                error: function(e) {
                    console.log(e);
                    $("#cmp_btn").show();
                    $("#processing").hide();
                }
            });
        });  
             
        stopTimer();


        // tinymce editor
        tinymce.init({
            selector: "#tinymceEditor",
            plugins: "code",
            toolbar: "undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code image_upload",
            menubar:false,
            statusbar: false,
            content_style: ".mce-content-body {font-size:15px;font-family:Arial,sans-serif;}",
            height: 150,
            setup: function(ed) {
         
                var fileInput = $('<input id="tinymce-uploader" type="file" name="pic" accept="image/*" style="display:none">');
                $(ed.getElement()).parent().append(fileInput);
                
                fileInput.on("change",function(){           
                    var file = this.files[0];
                    var reader = new FileReader();          
                    var formData = new FormData();
                    var files = file;
                    formData.append("file",files);
                    formData.append('filetype', 'image');               
                    jQuery.ajax({
                        url: "{{url('upload-reverted-task-img')}}",
                        type: "post",
                        data: formData,
                        contentType: false,
                        processData: false,
                        async: false,
                        success: function(response){
                            var fileName = response;
                            var base_url = $("#base_url").val();
                            var src = base_url + '/public/files/revert_task/' + fileName
                            if(fileName) {
                                ed.insertContent('<img src="'+src+'"/>');
                            }
                        }
                    });     
                    reader.readAsDataURL(file);  
                });     
                
                ed.addButton('image_upload', {
                    tooltip: 'Upload Image',
                    icon: 'image',
                    onclick: function () {
                        fileInput.trigger('click');
                    }
                });
            }
        });
    });


    function revertTask() {
        $("#revertTaskModal").modal('show');
    }



    function stopTimer() {
        var tsk_type = $("#tsk_type").val();
        $("#taskCounter").show();
        if(tsk_type == "default") {
            var total_worked_time = $("#total_worked_time").val();

            let a = secondsToHMS(total_worked_time);
            $("#showCounter").text(a);

            memeber_status_event_time();
        }else{
            $("#taskCounter").hide();
        }
    }

    function memeber_status_event_time() {
        var stime = $('.mb_event_time').text();
        var ct = stime.split(':');
        console.log(ct);
        s = parseInt(ct[2]);
        m = parseInt(ct[1]);
        h = parseInt(ct[0]);
        var divSpan = $(this).attr("id");
        sSeconds = s + (m * 60) + (h * 3600);

        $(".showhere").timer({
            action: "start",
            seconds: sSeconds
        });
    }

    function ChangeTaskStatus(id, status) {
        $("#tsk_type").val(status);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "POST",
            url: "{{url('change-my-task-status')}}",
            data: {task_status:status,task_id:id},
            dataType:'json',
            beforeSend:function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                console.log(data);
                if(data.status_code == 200 & data.success == true) {
                    alertNotification('success', 'Success' ,data.message );

                    if(status == "danger") {
                        $("#pauseTask_1").hide();
                        $("#startTask_2").show();
                    }else{
                        $("#pauseTask_1").show();
                        $("#startTask_2").hide();
                    }

                    stopTimer();

                }else{
                    alertNotification('error', 'Error' ,data.message );
                }
            },
            complete:function(data) {
                $(".loader_container").hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    }

    function secondsToHMS(totalSeconds) {
        var hours   = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
        var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

        // round seconds
        seconds = Math.round(seconds * 100) / 100

        var result = (hours < 10 ? "0" + hours : hours);
            result += ":" + (minutes < 10 ? "0" + minutes : minutes);
            result += ":" + (seconds  < 10 ? "0" + seconds : seconds);
        return result;
    }

    function completeTask() {
        $("#closingRemarks").modal('show');
    }

</script>
