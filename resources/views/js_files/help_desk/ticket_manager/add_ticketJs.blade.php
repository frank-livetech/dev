<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
// Add Ticket Script Bladde
    let res_templates_list = {!! json_encode($responseTemplates) !!};
    let attachments_src = [];
    // var ticket_attach_path = `{{asset('public/files/tickets')}}`;
    // var ticket_attach_path_search = 'public/files/tickets';
    var ticket_attach_path = `{{asset('storage/tickets')}}`;
    var ticket_attach_path_search = 'storage/tickets';
    let ticket_action_lvl = 'create';
    let ticket_attachments_count = 1;
    let userlist = {!! json_encode($users) !!};

    userlist.forEach(element => {
                userlist['key'] = element.name
                userlist['value'] = element.name + ' (' + element.email + ')';
            });


    $(document).ready(function() {

        var quill = new Quill('#editor', {
                        theme: 'snow',
                        modules: {
                            'toolbar': [
                                [{ 'font': [] }, { 'size': [] }],
                                [ 'bold', 'italic', 'underline', 'strike' ],
                                [{ 'color': [] }, { 'background': [] }],
                                [{ 'script': 'super' }, { 'script': 'sub' }],
                                [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block' ],
                                [{ 'list': 'ordered' }, { 'list': 'bullet'}, { 'indent': '-1' }, { 'indent': '+1' }],
                                [ 'direction', { 'align': [] }],
                                [ 'link', 'image', 'video', 'formula' ],
                                [ 'clean' ]
                            ],
                            imageResize: {
                                displaySize: true
                            },
                            keyboard: {
                              bindings: {
                                tributeSelectOnEnter: {
                                  key: 13,
                                  shortKey: false,
                                  handler: (event) => {
                                    if (tribute.isActive) {
                                      tribute.selectItemAtIndex(tribute.menuSelected, event);
                                      tribute.hideMenu();
                                      return false;
                                    }

                                    return true;
                                  }
                                },
                              }
                            }
                          }
                    });

        let tribute = new Tribute({
          values: userlist
        });

        tribute.attach($("#editor").find(".ql-editor"));

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

            var content = quill.root.innerHTML;
            var ticket_detail = $('#ticket_detail').val();
            let fileSizeErr = false;
            $('.ticket_attaches').each(function(index) {
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
            var queue_id = $('#queue_id').val();

            var customer_id = $('#customer_id').val();
            var assigned_to = $("#assigned_to").val();
            var deadline = $("#deadline").val();
            var user_role = $("#for_customer_role").val();

            var tag_emails = ''
            let extract_notes_email = content.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);
            if (extract_notes_email != null && extract_notes_email != '') {
                tag_emails = extract_notes_email.join(',');
            }
            // response template


            form_Data = {
                subject:subject,
                dept_id:dept_id,
                status:status,
                priority:priority,
                assigned_to:assigned_to,
                customer_id:customer_id,
                type:type,
                ticket_detail:content,
                tag_emails:tag_emails,
                deadline:deadline,
                queue_id:queue_id,
            };

            // getting response template data
            if($('#response_template').is(":checked" , true) ) {
                let access = ``;

                if( $("#onlyMe").is(":checked") ) {
                    access = `only_me`;
                }

                if( $("#allStaff").is(":checked") ) {
                    access = `all_staff`;
                }

                form_Data.title = $("#res_title").val();
                form_Data.cat_id = $("#category_name").val();
                form_Data.temp_html = ticket_detail;
                form_Data.view_access = access;
                form_Data.res = 1;


            }else{
                form_Data.res = 0;
            }

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

                // if( phone == '' || phone == null) {
                //     $("#phone_error").html('This field is required');
                //     return false;
                // }else{
                //     $("#phone_error").html(' ');
                // }

            }else{
                newcustomer = '';
                $("#first_name_error").html(' ');
                $("#last_name_error").html(' ');
                $("#phone_error").html(' ');
                $("#email_error").html(' ');

                if(!customer_id) {
                    if(user_role != 5) {
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
            }

            if($('#create_customer_login').prop('checked')) {
            }else{
                // form_Data["customer_login"] = 0;
            }

            var for_customer_profile_id = $("#for_customer_profile_id").val();

            if(for_customer_profile_id != "" && for_customer_profile_id!= null) {
                form_Data['customer_id'] = for_customer_profile_id;
            }

            if( $("#new_company").val() == "new_company") {

                form_Data['new_company'] = 'new_company';
                form_Data['poc_first_name'] = $("#poc_first_name").val();
                form_Data['poc_last_name'] = $("#poc_last_name").val();
                form_Data['company_name'] = $("#company_name").val();
                form_Data['company_domain'] = $("#company_domain").val();
                form_Data['company_phone_number'] = $("#company_phone_number").val();

            }else{
                form_Data['company_id'] = $("#company_id").val();
            }

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

                    if(data.success) {
                        // upload attachments
                        $("#responseTemplateForm").trigger("reset");
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
                        // var content = tinyMCE.activeEditor.getContent();

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
                                async:false,
                                success: function(res) {
                                    ticket_notify(data.id, 'ticket_create',tag_emails);
                                    alertNotification('success', 'Success' , data.message);
                                    window.location.href = "{{route('ticket_management.index')}}";
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
                    // $("#btnSaveTicket").show();
                    // $("#publishing").hide();
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
                // tinyMCE.activeEditor.setContent('');
            }else{
                let res = res_templates_list.filter(item => item.id == $(this).val());
                quill.clipboard.dangerouslyPasteHTML(quill.getLength() - 1, `${res[0].temp_html}`);
                $('#res-template').val('').trigger('change');
            }

        });

        $('#assigned_to').on("change",function(){
            var assigned_to_val = $('#assigned_to').val();
            if(assigned_to_val){
                console.log(assigned_to_val)
                for(var i = 0 ; i < assigned_to_val.length ; i++){
                    if(assigned_to_val[i] == ''){
                        $("#assigned_to option[value='']").prop("selected", false);
                        $('#assigned_to').trigger('change');
                    }
                }
                // var arr = assigned_to_val.split(",")
            }
            // alert(arr)
            
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
        if(value != ''){
            $.ajax({
                type: "POST",
                url: "{{url('get_department_status')}}",
                data: {id:value},
                dataType: 'json',
                beforeSend: function(data) {
                    $("#dropdown_loader").show();
                },
                success: function(data) {
                    let obj = data.status;
                    let obj_user = data.users;
                    let obj_queue = data.queue;
                    let default_queue = data.default_queue;

                    let option = ``;
                    let select = ``;
                    if(obj_queue == '' || obj_queue == null){
                        alertNotification('error', 'Error' , 'This department does not have any email queue.');
                        $("#queue_id").html("");
                        return false;
                    }else{
                        let email_option = ``;
                        for( let item of obj_queue) {

                            if(item.is_default == 'yes'){
                                email_option += `<option value="${item.id}" selected> ${item.mailserver_username} (${item.from_name}) </option>`;
                            }else{
                                email_option += `<option value="${item.id}"> ${item.mailserver_username} (${item.from_name}) </option>`;
                            }
                        }
                        $("#queue_id").html(email_option);

                        if(default_queue == null){
                            default_queue = obj_queue['0'];
                        }


                        $("#status").html('');
                        select = `<option value="">Select Status</option>`;
                        for(var i =0; i < obj.length; i++) {
                            if(default_queue.mail_status_id == obj[i].id){
                                option +=`<option value="`+obj[i].id+`" selected>`+obj[i].name+`</option>`;
                            }else{
                                option +=`<option value="`+obj[i].id+`">`+obj[i].name+`</option>`;
                            }
                        }
                        $("#status").html(select + option);

                        $('#priority').val(default_queue.mail_priority_id);
                        $("#priority").trigger('change');

                        $('#type').val(default_queue.mail_type_id);
                        $("#type").trigger('change');

                        select = `<option value="" selected>Unassigned</option>`;
                        if($("#assigned_to :selected").val()){
                            var available_user = obj_user.find(item => item.id == $("#assigned_to :selected").val());
                            $("#assigned_to").html('');
                            for(var i =0; i < obj_user.length; i++) {
                                select +=`<option value="`+obj_user[i].id+`" ${obj_user[i].id == (available_user ? available_user.id : 0 ) ? 'selected' : ''}>`+obj_user[i].name+`</option>`;
                            }
                        }else{
                            $("#assigned_to").html('');
                            for(var i =0; i < obj_user.length; i++) {
                                select +=`<option value="`+obj_user[i].id+`">`+obj_user[i].name+`</option>`;
                            }
                        }
                        $("#assigned_to").html(select);


                    }

                },
                complete: function(data) {
                    $("#dropdown_loader").hide();
                },
                error: function(error) {
                    $("#dropdown_loader").hide();
                    console.log(error);
                }
            });
        }else{
            $("#status").html('');
            // $("#assigned_to").html('');

        }

    }

    function ticket_notify(id, template, email=null) {
        let auto_responder = $("#send_email").is(":checked") ? 1 : 0;
        let send_details = $("#send_details").is(":checked") ? 1 : 0;
        $.ajax({
            type: 'POST',
            url: "{{url('ticket_notification')}}",
            data: { id: id, template: template, action: 'Ticket Create' , auto_responder : auto_responder , send_details : send_details, tag_email:email },
            async:false,
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
                    // name = baseName(src) + '.' + ext;
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

            </div>
            <div class="input-group-append">
                <button class="btn btn-dark" type="button" title="Remove" onclick="console.log(this.parentElement.parentElement.remove())"><span class="fa fa-times"></span></button>
            </div>
        </div>`);

        ticket_attachments_count++;
    }

    $(document).on('change', '.ticket_attaches', function(e){
        let file = e.target.files[0];
        $(this).parent().find('.custom-file-label').text(file.name);
    });

    $("#response_template").click(function() {
        $(this).is(":checked") ? $('#response_template_fields').show() :  $('#response_template_fields').hide();
        $("#res_title").val("");
        $("#category_name").val("").trigger('change');
        $("#onlyMe").prop("checked", false);
        $("#allStaff").prop("checked", true);
    });


    function newCompany() {

        $(".newCompany").toggle();
        $("#new_company").val("new_company");
        $("#company_id").val("").trigger("change");
    }
    function selectCompany() {
        if($("#company_id").val() == ''){
            $("#new_company").val("new_company");
        }else{
            $("#new_company").val("old");
        }

    }
</script>
