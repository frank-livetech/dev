<script>
     $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
    // Add Ticket Script Bladde
        let res_templates_list = {!! json_encode($responseTemplates) !!};
        let attachments_src = [];
        var ticket_attach_path = `{{asset('public/files/tickets')}}`;
        var ticket_attach_path_search = 'public/files/tickets';
        let ticket_action_lvl = 'create';
        let ticket_attachments_count = 1;

        $(document).ready(function() {
            tinymce.init({
                selector: "textarea#ticket_detail",
                // theme: "modern",
                height: 300,
                file_picker_types: 'image',
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",
                file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    // if(meta.filetype == 'media') input.setAttribute('accept', 'audio/*,video/*');

                    input.onchange = function() {
                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.onload = async function() {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];

                            if(reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                                base64 = await downloadPNGFromAnyImageSrc(reader.result);
                            }
                            
                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), { title: file.name });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
            });

            $('#dept_id').trigger('change');

            $("#customer_id").on('change', function() {
                if($(this).val() == "") {
                    $("#new_customer_div").show();
                }else{
                    $("#new_customer_div").hide();
                }
            });

            $("#new_customer").click(function() {

                if($('#new_customer').prop('checked')) {
                    $("#new_customer_form").show();
                    $("#select_customer").hide();

                    $('#tkt_first_name').attr('required', true);
                    $('#tkt_last_name').attr('required', true);
                    $('#tkt_phone').attr('required', true);
                    $('#tkt_email').attr('required', true);
                }else{
                    $("#new_customer_form").hide();
                    $("#select_customer").show();

                    $('#tkt_first_name').attr('required', false);
                    $('#tkt_last_name').attr('required', false);
                    $('#tkt_phone').attr('required', false);
                    $('#tkt_email').attr('required', false);
                }
            });


            $("#save_tickets").submit(function(event) {
                event.preventDefault();

                var ticket_detail = $('#ticket_detail').val();                
                if( ticket_detail.includes('&nbsp;') ) {
                    toastr.error('Please Remove spaces from problem details', { timeOut: 5000 });
                    return false;
                }

                let fileSizeErr = false;
                $('.ticket_attaches').each(function(index) {
                    console.log(this.files);
                    if(this.files.length && (this.files[0].size / (1024*1024)).toFixed(2) > 2) fileSizeErr = this.files[0].name;
                });
                if(fileSizeErr !== false) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: "Size Error",
                        text: fileSizeErr+" exceeds 2MB!",
                        showConfirmButton: false,
                        timer: swal_message_time,
                    });
                    return false;
                }

                var subject = $('#subject').val().replace(/\s+/g, " ").trim();
                $('#subject').val(subject);
                if(!subject) {
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: "Please enter subject!",
                        showConfirmButton: false,
                        timer: swal_message_time,
                    });
                    return false;
                }

                var form_Data = {};

                var dept_id = $('#dept_id').val();
                var status = $('#status').val();
                var priority = $('#priority').val();
                var type = $('#type').val();
                var customer_id = $('#customer_id').val();
                // var ticket_detail = $('#ticket_detail').val();
                var assigned_to = $("#assigned_to").val();
                var deadline = $("#deadline").val();

                form_Data = {
                    subject:subject,
                    dept_id:dept_id,
                    status:status,
                    priority:priority,
                    assigned_to:assigned_to,
                    customer_id:customer_id,
                    type:type,
                    deadline:deadline
                };

                if($('#new_customer').prop('checked')) {

                    var first_name = $('#tkt_first_name').val();
                    var last_name = $('#tkt_last_name').val();
                    var phone = $('#tkt_phone').val();
                    var email = $('#tkt_email').val();

                    newcustomer = 'newcustomer';
 
                    form_Data["first_name"] = first_name;
                    form_Data["last_name"] = last_name; 
                    form_Data["phone"] = phone; 
                    form_Data["email"] = email; 
                    form_Data["newcustomer"] = newcustomer; 

                    if( first_name == '' || first_name == null) {
                        $("#first_name_error").html('This field is required');
                        return false;
                    }else{
                        $("#first_name_error").html(' ');
                    }

                    if( last_name == '' || last_name == null) {
                        $("#last_name_error").html('This field is required');
                        return false;
                    }else{
                        $("#last_name_error").html(' ');
                    }
                    
                    if( email == '' || email == null) {
                        $("#email_error").html('This field is required');
                        return false;
                    }else{
                        $("#email_error").html(' ');
                    }

                    if( phone == '' || phone == null) {
                        $("#phone_error").html('This field is required');
                        return false;
                    }else{
                        $("#phone_error").html(' ');
                    }

                }else{
                    newcustomer = '';
                    $("#first_name_error").html(' ');
                    $("#last_name_error").html(' ');
                    $("#phone_error").html(' ');
                    $("#email_error").html(' ');

                    if(!customer_id) {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: "Please select a customer!",
                            showConfirmButton: false,
                            timer: swal_message_time,
                        });
                        return false;
                    }
                }

                if($('#create_customer_login').prop('checked')) {
                    form_Data["customer_login"] = 1; 
                }else{
                    // form_Data["customer_login"] = 0; 
                }

                var for_customer_profile_id = $("#for_customer_profile_id").val();

                if(for_customer_profile_id != "" && for_customer_profile_id!= null) {
                    form_Data['customer_id'] = for_customer_profile_id;
                }

                console.log(form_Data , "form");
                $.ajax({
                    type: "POST",
                    url: "{{url('save-tickets')}}",
                    data: form_Data,
                    dataType: 'json',
                    beforeSend:function(data) {
                        $("#btnSaveTicket").hide();
                        $("#publishing").show();
                        $("#status_modal").show();
                    },
                    success: function(data) {
                        console.log(data);

                        if(data.success) {
                            // upload attachments
                            $('.ticket_attaches').each(function(index) {
                                console.log(this.files);
                                if(this.files.length) {
                                    let fileData = new FormData();
                                    fileData.append('ticket_id', data.id);
                                    fileData.append('fileName', 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index);
                                    fileData.append('attachment', this.files[0]);
                                    fileData.append('module', 'tickets');
    
                                    $.ajax({
                                        type: "post",
                                        url: "{{asset('upload_attachments')}}",
                                        data: fileData,
                                        async: false,
                                        processData: false,
                                        contentType: false,
                                        success: function(res) {
                                            if(!res.success) {
                                                // show error
                                            }
                                        },
                                        error: function(data) {
                                            console.log(data);
                                        }
                                    });
                                }
                            });

                            // update the ticket details with attachments
                            var content = tinyMCE.activeEditor.getContent();
                            tinyContentEditor(content, data.id).then(function() {
                                content = $('#tinycontenteditor').html();

                                $.ajax({
                                    type: "post",
                                    url: "{{asset('update_ticket')}}",
                                    data: {
                                        id: data.id,
                                        subject: subject,
                                        ticket_detail: content,
                                        attachments: attachments_src,
                                    },
                                    dataType: 'json',
                                    cache: false,
                                    success: function(res) {
                                        ticket_notify(data.id, 'ticket_create');

                                        toastr.success(data.message, { timeOut: 5000 });
                                        var preivous_url = $("#previous_url").val();
                                        window.location = preivous_url;
                                        
                                        // Swal.fire({
                                        //     title: 'Success',
                                        //     text: "Ticket created successfully!",
                                        //     icon: 'success',
                                        //     confirmButtonColor: '#3085d6',
                                        //     confirmButtonText: 'OK!'
                                        // }).then((result) => {
                                        //     if (result.value) {
                                        //         var preivous_url = $("#previous_url").val();
                                        //         window.location = preivous_url;
                                        //     }
                                        // });
                                    }
                                });
                            });
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: data.message,
                                showConfirmButton: false,
                                timer: swal_message_time,
                            });
                        }
                    },
                    complete:function(data ) {
                        $("#btnSaveTicket").show();
                        $("#publishing").hide();
                        $("#status_modal").hide();
                    },
                    failure: function(errMsg) {
                        $("#btnSaveTicket").show();
                        $("#publishing").hide();
                        $("#status_modal").hide();
                        console.log(errMsg);
                    }
                });
            });

            $('#res-template').change(function() {
                if( $(this).val() == "" ) {
                    tinyMCE.activeEditor.setContent('');
                }else{
                    let content = tinyMCE.activeEditor.getContent();
                    let res = res_templates_list.filter(item => item.id == $(this).val());
                    if(res.length) {
                        if(!content && content != '<p></p>') {
                            tinyMCE.activeEditor.setContent(res[0].temp_html ? res[0].temp_html : '');
                        } else {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'All template changes will be lost!',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes'
                            }).then((result) => {
                                if (result.value) {
                                    tinyMCE.activeEditor.setContent(res[0].temp_html ? res[0].temp_html + content : '');
                                }
                            });
                        }
                    }
                }
            });


            // disable calender previous dates
            let today = new Date();
            let month = today.getMonth() + 1;
            let day = today.getDate();
            let year = today.getFullYear();

            if(month < 10) {
                month = '0' + month.toString();
            }
            if(day < 10){
                day = '0' + day.toString();
            }
            let maxDate = year + '-' + month + '-' + day;
            $("#deadline").attr('min',maxDate);
        });

        function showDepartStatus(value) {
            $.ajax({
                type: "POST",
                url: "{{url('get_department_status')}}",
                data: {id:value},
                dataType: 'json',
                beforeSend: function(data) {
                    $("#dropdown_loader").show();
                },
                success: function(data) {
                    console.log(data , "assignee");
                    let obj = data.status;
                    let obj_user = data.users;

                    let option = ``;
                    let select = ``;

                    for(var i =0; i < obj.length; i++) {
                        option +=`<option value="`+obj[i].id+`">`+obj[i].name+`</option>`;
                    }
                    $("#status").html(select + option);

                    select = `<option value="">Unassigned</option>`;
                    for(var i =0; i < obj_user.length; i++) {
                        select +=`<option value="`+obj_user[i].id+`">`+obj_user[i].name+`</option>`;
                    }
                    $("#assigned_to").html(select);
                },
                complete: function(data) {
                    $("#dropdown_loader").hide();
                },
                error: function(error) {
                    $("#dropdown_loader").hide();
                    console.log(error);
                }
            });
        }

        function ticket_notify(id, template) {
            $.ajax({
                type: 'POST',
                url: "{{url('ticket_notification')}}",
                data: { id: id, template: template, action: 'Ticket Create' },
                success: function(data) {
                    if (!data.success) {
                        console.log(data.message);
                    }
                },
                failure: function(errMsg) {
                    console.log(errMsg);
                }
            });
        }

        function getBlobFromUrl (bUrl) {
            return new Promise((resolve, reject) => {
                let xhr = new XMLHttpRequest()
                xhr.responseType = 'blob'
                xhr.addEventListener('load', event => {
                    if (xhr.status === 200) {
                        resolve(xhr.response)
                    } else {
                        reject(new Error('Cannot retrieve blob'))
                    }
                })
        
                xhr.open('GET', bUrl, true)
                xhr.send()
            })
        }
        
        function fromBlobToBase64 (blob) {
            return new Promise((resolve, reject) => {
                let reader = new FileReader()
                reader.addEventListener('loadend', event => {
                    resolve(reader.result)
                })
                reader.readAsDataURL(blob)
            })
        }

        async function tinyContentEditor(content, tid) {
            attachments_src = [];
            let res;
            $('#tinycontenteditor').html(content);

            $('#tinycontenteditor').find('img').each(function(index) {
                let src = $(this).attr('src');
                let ext = 'png';

                let validImg = true;

                let marker = '.';

                if (src.includes('base64')) marker = '/';

                if (src.includes(marker + 'jpg') || src.includes(marker + 'JPG')) {
                    ext = "jpg";
                } else if (src.includes(marker + 'ico') || src.includes(marker + 'ICO')) {
                    ext = "ico";
                } else if (src.includes(marker + 'jpeg') || src.includes(marker + 'JPEG')) {
                    ext = "jpeg";
                } else if (src.includes(marker + 'png') || src.includes(marker + 'PNG')) {
                    ext = "png";
                } else if (src.includes(marker + 'gif') || src.includes(marker + 'GIF')) {
                    ext = "gif";
                } else if (src.includes(marker + 'webp') || src.includes(marker + 'WEBP')) {
                    ext = "webp";
                } else if (src.includes(marker + 'svg') || src.includes(marker + 'SVG')) {
                    ext = "svg";
                } else if (src.includes(marker + 'mp4') || src.includes(marker + 'MP4')) {
                    ext = "mp4";
                } else {
                    $(this).remove();
                    validImg = false;
                }

                if (src.includes('base64')) {
                    src = src.replace(/^data:.+;base64,/, '');
                }

                if (validImg) {
                    let name = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index + '.' + ext;

                    if (src.includes(ticket_attach_path_search + '/' + tid)) {
                        name = baseName(src) + '.' + ext;
                    } else {
                        $(this).attr('src', ticket_attach_path + `/${tid}/${name}`);
                    }
                    attachments_src.push([name, src]);
                }
            });

            return await res;
        }

        async function downloadPNGFromAnyImageSrc(src) {
            //recreate the image with src recieved
            var img = new Image;
            //when image loaded (to know width and height)
            let r =  new Promise((resolve, reject) => {
                img.onload = async function() {
                    //drow image inside a canvas
                    var canvas = document.createElement("canvas");
                    canvas.width = img.width; canvas.height = img.height;
                    canvas.getContext("2d").drawImage(img, 0, 0);
                    
                    //get image/png from convas
                    console.log("Image Loaded");
                    resolve(canvas.toDataURL("image/png"));
                };
                img.src = src;
            });
            return await r;
        }

        function addAttachment() {
            $('#ticket_attachments').append(`<div class="input-group mt-3">
                <div class="custom-file text-left">
                    <input type="file" class="form-control ticket_attaches" id="ticket_attachment_${ticket_attachments_count}">
                    <label class="custom-file-label" for="ticket_attachment_${ticket_attachments_count}"></label>
                </div>
                <div class="input-group-append">
                    <button class="btn btn-dark" type="button" title="Remove" onclick="console.log(this.parentElement.parentElement.remove())"><span class="fa fa-window-close"></span></button>
                </div>
            </div>`);

            ticket_attachments_count++;
        }

        $(document).on('change', '.ticket_attaches', function(e){
            let file = e.target.files[0];
            $(this).parent().find('.custom-file-label').text(file.name);
        });
    </script>