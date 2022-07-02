<script>
    let ticket_attachments_count = 1;
    let attachments_src = [];
    var tkt_replies_arr = [];
    let edit_reply_mode = false;
    var ticket_id = $("#ticket_id").val();
    var js_path = $("#js_path").val();
    js_path = (js_path == 1 ? 'public/' : '');
    // var ticket_attach_path = `{{asset('public/files')}}`;
    // var ticket_attach_path_search = 'public/files';
    var ticket_attach_path = `{{asset('storage')}}`;
    var ticket_attach_path_search = 'storage';
    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        

        ticketDetail.render_tkt_replies();
        ticketDetail.render_text_editor();

        

    });


    const ticketDetail = {

        render_tkt_replies: () => {
            
            $.ajax({
                type: "post",
                url: "{{route('customer.getTktReplies')}}",
                data: {id : ticket_id},
                dataType: 'json',  
                success: function(data) {
                    if(data.status_code == 200 && data.success == true) {
                        var obj = data.ticket_replies;
                        var row = ``;
                        var user_img = ``;
                        var attachments_name = ``;
                        var type = ``;
                        $('.show_replies').html("");

                        if (obj.length != 0) {

                            tkt_replies_arr = obj;

                            for (const item of obj) {
                                let name = item.reply_user == null ? '-' : (item.reply_user.name != null ? item.reply_user.name : '-');

                                if (item.reply_user != null) {
                                    type = `staff`;
                                    if (item.reply_user.profile_pic != null) {
                                        let path = root + item.reply_user.profile_pic;
                                        user_img = `<span class="avatar">
                                                        <img src="${path}" width="40px" height="40px" class="round"/> </span>`;
                                    } else {
                                        user_img = `<span class="avatar"><img src="{{asset('${js_path}default_imgs/customer.png')}}" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" /></span>`;
                                    }
                                } else {
                                    user_img = `<span class="avatar"><img src="{{asset('${js_path}default_imgs/customer.png')}}" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" /></span>`;
                                }

                                // attachments code -> mohsin
                                var attachments =  item.attachments;
                                if(attachments != null){
                                    attachments = attachments.split(',');
                                    if(attachments.length != 0) {
                                        for(var i = 0 ; i < attachments.length; i++) {
                                            attachments_name += `<a href="javascript:void(0)"> ${attachments[i]} </a> <br>`;
                                        }
                                    }
                                }

                                let tdet = '';
                                if(item.attachments) {
                                    let attchs = item.attachments.split(',');
                                    tdet += '';
                                    attchs.forEach(attch => {
                                        var tech =  `{{asset('public/files/replies/${ticket_id}/${attch}')}}`;
                                        var ter = getExt(tech);

                                        
                                        // return ter;
                                        if(ter == "pdf" ){
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne"  style="display: flex; justify-content: center; align-items: center;">
                                                        <span class="overlayAttach"></span>
                                                            <img src="{{asset('${js_path}default_imgs/pdf.png')}}" class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/pdf.png')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${ticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        else if(ter == "csv" || ter == "xls" || ter == "xlsx" || ter =="sql"){
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                                            <span class="overlayAttach"></span>
                                                            <img src="{{asset('${js_path}default_imgs/xlx.png')}}" class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/xlx.png')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${ticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        else if(ter == "png" || ter == "jpg" || ter == "webp" || ter == "jpeg" || ter == "webp" || ter == "svg" || ter == "psd"){
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne">
                                                            <span class="overlayAttach"></span>
                                                            <img src="{{asset('public/files/replies/${ticket_id}/${attch}')}}"class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/image.jpeg')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${ticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        else if(ter == "docs" || ter == "doc" || ter == "txt" || ter == "dotx" || ter == "docx"){
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                                            <span class="overlayAttach"></span>

                                                            <img src="{{asset('${js_path}default_imgs/word.png')}}" class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/word.png')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${ticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        else if(ter == "ppt" || ter == "pptx" || ter == "pot" || ter == "pptm"){
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                                            <span class="overlayAttach"></span>

                                                            <img src="{{asset('${js_path}default_imgs/pptx.png')}}" class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/pptx.png')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${tticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        else if(ter == "zip"){
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                                            <span class="overlayAttach"></span>

                                                            <img src="{{asset('${js_path}default_imgs/zip.jpeg')}}" class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/zip.jpeg')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${tticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        else{
                                            tdet+= `<div class="col-md-2 mt-1">
                                                            <div class="card__corner">
                                                                <div class="card__corner-triangle"></div>
                                                            </div>
                                                        <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                                            <span class="overlayAttach"></span>
                                                            <img src="{{asset('${js_path}default_imgs/txt.png')}}" class=" attImg"  alt="">
                                                            <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('assets/images/icon/txt.png')}}"  alt=""> ${attch}</span>
                                                            <a href="{{asset('public/files/replies/${ticket_id}/${attch}')}}" download="{{asset('public/files/replies/${ticket_id}/${attch}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                                        </div>
                                                    </div>` 
                                        }
                                        // tdet += `<p><a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" target="_blank">${item}</a></p>`;
                                    });

                                    tdet += '';
                                }

                                var content = '';
                                if(item.type == 'cron'){
                                    content = item.reply.replace(/<img[^>]*>/g,"");
                                }else{
                                    content = item.reply;
                                }

                                row = `
                                
                                    <li class="media" id="reply__${item.id}">
                                        <span class="mr-3"> ${item.reply_user == null ? customer_img : user_img} </span>
                                        <div class="media-body px-2 w-100">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5 class="mt-0 mb-0">
                                                        <span class="text-primary"> ${item.customer_id == null ? name : customer_name} 
                                                        <span class="badge bg-info"> ${item.customer_id == null ? type : 'user'} </span> </span>
                                                    </h5> 
                                                    <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;"> posted on created at ${ ticketDetail.date_conversion(item.created_at)} </span> 

                                                </div>
                                                
                                                <div class="col-md-6 text-end">
                                                    <button type="button" onclick="ticketDetail.updateTktReply(${item.id})" class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit">
                                                    </button>
                                                </div>
                                                
                                            </div>
                                            <div class="my-1 bor-top" id="reply-html-` + item.id + `">
                                                ${content}
                                            </div>
                                            <div class="row mt-1">
                                                ${tdet}
                                            </div>
                                        </div>
                                    </li>
                                    <hr>

                                `;
                                $('.show_replies').append(row);
                                if (item.hasOwnProperty('msgno') && item.msgno) {
                                    $('#reply-html-' + item.id).find('img').attr('width', 120);
                                    $('#reply-html-' + item.id).find('img').attr('height', 120);
                                    $('#reply-html-' + item.id).find('img').css('margin', '0 8px 8px 0');
                                }
                            }
                        }else{
                            $('.show_replies').html("");
                        }

                    }else{
                        toastr.error( data.message , { timeOut: 5000 });
                    }
                },
                error:function(e) {
                    toastr.error( 'Something went wrong' , { timeOut: 5000 });
                    console.log(e);
                }
            });
        },

        updateTktReply : (rid) => {

            let item = tkt_replies_arr.find(item => item.id === rid);

            if(item != null || item != "" || item != "[]" || item != undefined) {
                
                $("#rid").val(rid);
                tinymce.activeEditor.setContent(item.reply);
                $('.replydiv').show();

                if(item.attachments != null) {
                    let attchs = item.attachments.split(',');

                    $('#replies_attachments').html('');
                    ticket_attachments_count = 0;

                    attchs.forEach(item => {
                        ticketDetail.addAttachment('replies', item);
                    });
                }
                
            }
            edit_reply_mode = rid;
        },

        render_text_editor: () => {
            tinymce.init({
                selector: "textarea#desc",
                // theme: "modern",
                height: 300,
                file_picker_types: 'image',
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",
                // file_picker_types: 'file image media',
                // media_live_embeds: true,
                paste_data_images: true,
                file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    // if (meta.filetype == 'media') input.setAttribute('accept', 'audio/*,video/*');

                    input.onchange = function() {

                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.onload = async function() {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.editors.mymce.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];

                            if (reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                                base64 = await downloadPNGFromAnyImageSrc(reader.result);
                            }

                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
            })
        },

        date_conversion: (date) => {

            var usrtimeZone = $("#usrtimeZone").val();
            var system_date = $("#system_date").val();

            var d = new Date(date);

            var min = d.getMinutes();
            var dt = d.getDate();
            var d_utc = d.getUTCHours();

            d.setMinutes(min);
            d.setDate(dt);
            d.setUTCHours(d_utc);

            let a = d.toLocaleString("en-US", { timeZone: usrtimeZone });
            // return a;
            var converted_date = moment(a).format(system_date + ' ' +'hh:mm a');
            return converted_date;
        },

        showReplyDiv : () => {
            $('.cancel-btn').attr('style', 'display: block !important');
            $('.reply-btn').attr('style', 'display: block !important');
            $('.replydiv').toggle();
            $('#update_btn').attr('style', 'display: none !important');
            $("#reply-btn").hide();
            $(".all_attachs").remove();
            tinymce.activeEditor.setContent('<p></p>');
        },

        updateTktType : (type, id) => {
            var color = $("#"+type).find(':selected').data('color');
            $("#"+type+"_html").css('background-color' , color);
            $('.update_tkt_tbn').show();

            if(type == 'status') {
                $("#s_id").val(id);
            }else{
                $("#p_id").val(id);
            }

        },

        addAttachment : (type, olderAttach='') => {
            if(olderAttach) {
                $('#'+type+'_attachments').append(`<div class="input-group pt-3 all_attachs">
                    <div class="custom-file text-left">
                        <input type="file" class="form-control" id="${type}_attachment_${ticket_attachments_count}" disabled>
                        <label class="custom-file-label" for="${type}_attachment_${ticket_attachments_count}">${olderAttach}</label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" title="Remove" onclick="removeAttachment(this, '${olderAttach}', '${type}')"><span class="fa fa-times"></span></button>
                    </div>
                </div>`);
            } else {
                $('#'+type+'_attachments').append(`<div class="input-group pt-3 all_attachs">
                    <div class="custom-file text-left">
                        <input type="file" class="form-control ${type}_attaches" id="${type}_attachment_${ticket_attachments_count}">
                        <label class="custom-file-label" for="${type}_attachment_${ticket_attachments_count}"></label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" title="Remove" onclick="this.parentElement.parentElement.remove()"><span class="fa fa-times"></span></button>
                    </div>
                </div>`);
            }

            ticket_attachments_count++;
        },

        cancelReply : () => {
            $('.replydiv').hide();
            $('.cancel-btn').attr('style', 'display: none !important');
            $('.reply-btn').attr('style', 'display: none !important');
            $("#reply-btn").show();
            edit_reply_mode = false;
            tinymce.activeEditor.setContent('<p></p>');
        },

        publishReply : (ele) => {

            var content= tinymce.activeEditor.getContent();
            var to_mails = $("#to_mails").val();
            
            if(content == null || content == "" || content == "<p></p>") {
                toastr.error( "Description field is required", { timeOut: 5000 });
                return false;
            }

            tinyContentEditor(content, 'tickets-replies').then(function() {
            content = $('#tinycontenteditor').html();

                if (!content || content == '<p></p>') {
                    $('#reply').css('display', 'block');
                    return false;
                } else {
                    let fileSizeErr = false;
                    $('.replies_attaches').each(function(index) {
                        if(this.files.length && (this.files[0].size / (1024*1024)).toFixed(2) > 2) fileSizeErr = this.files[0].name;
                    });
                    if(fileSizeErr !== false) {
                        toastr.error( fileSizeErr+" exceeds 2MB!" , { timeOut: 5000 });
                        return false;
                    }

                    $(ele).attr('disabled', true);
                    $(ele).find('.spinner-border').show();

                    let rep_attaches = '';
                    console.log(edit_reply_mode , "edit_reply_mode");
                    if (edit_reply_mode !== false) {
                        let item = tkt_replies_arr.find(item => item.id === edit_reply_mode);
                        if(item != null) {
                            rep_attaches = item.attachments;
                        }
                    }

                    console.log(rep_attaches , "attaches");

                    // upload attachments
                    $('.replies_attaches').each(function(index) {
                        if(this.files.length) {
                            // mssg = 'Subject updated with attachments';
                            let fname = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index;

                            let fileData = new FormData();
                            fileData.append('ticket_id', ticket_id);
                            fileData.append('fileName', fname);
                            fileData.append('attachment', this.files[0]);
                            fileData.append('module', 'replies');

                            $.ajax({
                                type: "post",
                                url: "{{route('customer.saveTicketAttachments')}}",
                                data: fileData,
                                async: false,
                                processData: false,
                                contentType: false,
                                success: function(res) {
                                    if(!res.success) {
                                        // show error
                                    } else {
                                        if(!rep_attaches) rep_attaches = res.attachments;
                                        else rep_attaches += ','+res.attachments;
                                    }
                                },
                                error: function(data) {
                                    $(ele).attr('disabled', false);
                                    $(ele).find('.spinner-border').hide();
                                }
                            });
                        }
                    });

                    let params = {
                        cc: $('#to_mails').val(),
                        ticket_id: ticket_id,
                        type: 'publish',
                        attachments: rep_attaches,
                        reply: content,
                        inner_attachments: attachments_src
                    };
                    if (edit_reply_mode !== false) {
                        params.id = edit_reply_mode;
                    }

                    $.ajax({
                        type: "post",
                        url: "{{route('customer.saveTicketReply')}}",
                        data: params,
                        dataType: 'json',
                        enctype: 'multipart/form-data',
                        cache: false,
                        success: function(data) {

                            if(data.status_code == 200 && data.success == true) {
                                toastr.success( data.message , { timeOut: 5000 });
                                $('.replydiv').hide();
                                ticketDetail.render_tkt_replies();
                            }else{
                                toastr.error( data.message , { timeOut: 5000 });
                            }
                        },
                        complete:function() {
                            $(ele).attr('disabled', false);
                            $(ele).find('.spinner-border').hide();
                        },  
                        error:function(e) {
                            $(ele).attr('disabled', false);
                            $(ele).find('.spinner-border').hide();
                            toastr.error( 'Something went wrong' , { timeOut: 5000 });
                            console.log(e);
                        }
                    });
                }
            });

        },

        uploadReplyAttachments : (form_data) => {
            $.ajax({
                type: "POST",
                url: "#",
                data: form_data,
                async: false,
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.success) {
                        toastr.success( res.message , { timeOut: 5000 });
                    }else{
                        toastr.error( res.message , { timeOut: 5000 });
                    }
                },
                error: function(data) {
                    toastr.error( 'Something went wrong' , { timeOut: 5000 });
                    console.log(data);
                }
            });
        },

        // update tkt department & tkt status
        updateTkt : (ele) => {
            var p_id = $("#p_id").val();
            var s_id = $("#s_id").val();

            $.ajax({
                type: "post",
                url: "{{route('customer.cstUpdateTicket')}}",
                data: {p_id :p_id , s_id : s_id, tkt_id : ticket_id},
                dataType: 'json',
                beforeSend:function() {
                    $(ele).attr('disabled', true);
                    $(ele).find('.spinner-border').show();
                },  
                success: function(data) {

                    if(data.status_code == 200 && data.success == true) {
                        toastr.success( data.message , { timeOut: 5000 });
                        // $("#update_btn").hide();

                        $("#tkt_updated_at").html( ticketDetail.date_conversion(data.tkt_updated_at) );
                    }else{
                        toastr.error( data.message , { timeOut: 5000 });
                    }
                    $("#update_btn").hide();
                },
                complete:function() {
                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();
                },
                error:function(e) {
                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();
                    toastr.error( 'Something went wrong' , { timeOut: 5000 });
                    console.log(e);
                }
            });
        }
    }

    function getExt(filename) {
        var ext = filename.split('.').pop();
        if(ext == filename) return "";
        return ext;
    }

    async function tinyContentEditor(content, action) {
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
            } else {
                $(this).remove();
                validImg = false;
            }

            if (src.includes('base64')) {
                src = src.replace(/^data:.+;base64,/, '');
            }

            if (validImg) {
                let name = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index + '.' + ext;

                if (src.includes(ticket_attach_path_search + '/' + action + '/' + ticket_id)) {
                    // name = baseName(src) + '.' + ext;
                } else {
                    $(this).attr('src', ticket_attach_path + `/${action}/${ticket_id}/${name}`);
                    // $(this).attr('height', '120');
                    // $(this).attr('width', '120');

                    // $( /*html*/ `<div class="reply-attachs-container">
                    //     <div class="reply-image"><img src="${ticket_attach_path+'/'+action+'/'+ticket.id+'/'+name}" alt="${name}" class="reply-image"></div>
                    //     <div class="reply-bottom">
                    //         <a href="${ticket_attach_path+'/'+action+'/'+ticket.id+'/'+name}" target="_blank" class="reply-action"><i class="fa fa-download text-white"></i></a>
                    //     </div>
                    // </div>`).insertAfter(this);

                    // $(this).remove();
                    // $(this).attr('src', ticket_attach_path+'/'+action+'/'+ticket.id+'/'+name);
                }
                attachments_src.push([name, src]);
            }
        });
        return await res;
    }
</script>