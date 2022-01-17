@extends('customer.layout.customer_master')
@section('body')

<style>
    .tag {
    width: fit-content !important;
    padding: 0.25rem;
    border-radius: 4px;
    margin-left: 4px;
    margin-top: 4px;
}
.bootstrap-tagsinput > .bootstrap-tagsinput {
    width: 100% !important;
    border: 1px solid #ccc;
}
.bootstrap-tagsinput {
    display: flex !important;
    margin-top: 5px !important;
    box-shadow: none !important;
    flex-wrap: wrap !important;
    /* border:0px; */
}
.label-info {
    background-color: #6d5eac !important;
}
</style>

<div class="content-body">

    <input type="hidden" value="{{Session::get('is_live')}}" id="js_path">
    <input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">
    <input type="hidden" id="system_date" value="{{Session::get('system_date')}}">
    <input type="hidden" id="ticket_id" value="{{$ticket->id}}">
    

    <h1>View Ticket : {{$ticket->coustom_id}}</h1>

    <div class="card mt-2">
        <div class="card-body">
            <h3>{{$ticket->subject}}</h3>

            Created : <span id="tkt_created_at"> </span>
            Updated : <span id="tkt_updated_at"> </span>

        </div>
    </div>

    <div class="card">
        <div class="card-body p-0" style="background-color:{{($current_status == null) ? '' : ($current_status->color != null ? $current_status->color : ' ')}}">
            <div class="row">
                <div class="col-md-2 p-2" id="dep-label">
                    <span class="text-muted tw-bold"> Department </span>
                    <h3 class="mt-1"> {{ $department->name != null ? $department->name : '-' }} </h3>
                </div>
                <div class="col-md-2 p-2" id="tech-label">
                    <span class="text-muted tw-bold"> Owner </span>
                    <h3 class="mt-1"> Owner Name </h3>
                </div>
                <div class="col-md-2 p-2" id="type-label">
                    <span class="text-muted tw-bold"> Department </span>
                    <h3 class="mt-1"> {{ $type->name != null ? $type->name : '-' }} </h3>
                </div>
                <div class="col-md-2 p-2" id="status_html" onclick="customer.showdropdown('status')">
                    <span class="text-muted tw-bold"> Status </span>
                    <div id="status_field">
                        <select class="select2 form-control" onchange="ticketDetail.updateTktBtn()" id="status" name="status">
                            @foreach($statuses as $status)
                            <option value="{{$status->id}}" data-color="{{$status->color}}" {{ $status->id == $ticket->status ? 'selected' : '' }}>{{$status->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 p-2" id="priority_html" onclick="customer.showdropdown('priority')" style="background-color:{{($current_priority == null) ? '' : ($current_priority->priority_color != null ? $current_priority->priority_color : ' ')}}">
                    <span class="text-muted tw-bold"> Priority </span>
                    <div id="priority_field">
                        <select class="select2 form-control "  onchange="ticketDetail.updateTktBtn()" id="priority" name="priority" style="display:none">
                            @foreach($priorities as $priority)
                            <option value="{{$priority->id}}" data-color="{{$priority->priority_color}}" {{$priority->id == $ticket->priority ? 'selected' : ''}}>{{$priority->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h3>Ticket Replies</h3>
                <div>
                    <button class="btn btn-success update_tkt_tbn" style="display:none"> <i data-feather='check'></i> Update </button>
                    <button class="btn btn-primary" onclick="ticketDetail.showReplyDiv()"> <i data-feather='plus'></i> add Reply</button>
                </div>
            </div>

            <div class="mt-1 replydiv" style="display:none">
                <form action="">
                    <div class="col-md-12 mt-1">
                        <label for="">CC</label> <br>
                        <!-- <input type="text" class="form-control" id="cc"> -->
                        <input type="text" id="to_mails" name="to_mails" class="form-control border" placeholder="Email"  data-role="tagsinput" value="" required>
                    </div>
                    <div class="col-md-12 mt-1">
                        <label for="">Description</label>
                        <textarea class="form-control" name="desc" id="desc" cols="30" rows="10"></textarea>
                    </div>
                    <div class="row">
                        <div class="d-flex justify-content-between">
                            <div>
                            <button class="btn btn-outline-primary mt-3 btn-sm" type="button" onclick="ticketDetail.addAttachment('replies')"><span class="fa fa-plus"></span> Add Attachment</button>
                                <div class="col-12 p-0" id="replies_attachments"></div>
                            </div>
                            <div>
                            <button id="rply" type="button" class="mt-3 btn waves-effect waves-light btn-success float-right" onclick="ticketDetail.publishReply(this)">
                                <div class="spinner-border text-light" role="status" style="height: 20px; width:20px; margin-right: 8px; display: none;">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                Reply
                            </button>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

            <div class="show_replies"></div>
        </div>
    </div>

</div>

<div style="display: none;" id="tinycontenteditor"></div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
<script>
    let ticket_attachments_count = 1;
    let attachments_src = [];
    let edit_reply_mode = false;
    var ticket_id = $("#ticket_id").val();
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        let ticket = {!!json_encode($ticket) !!};

        $(".meta_tags").tagsinput('items');

        $("#tkt_created_at").html( ticketDetail.date_conversion(ticket.updated_at) );
        $("#tkt_updated_at").html( ticketDetail.date_conversion(ticket.updated_at) );


        ticketDetail.render_tkt_replies(ticket);
        ticketDetail.render_text_editor();
    });


    const ticketDetail = {

        render_tkt_replies: (ticket) => {
            var js_path = $("#js_path").val();
            js_path = (js_path == 1 ? 'public/' : '');

            if (ticket.ticket_replies_count != 0) {

                let obj = ticket.ticket_replies;
                console.log(ticket , "asdasd");
                var row = ``;
                var user_img = ``;
                var customer_name = ``;

                if(ticket.ticket_customer != null || ticket.ticket_customer != "") {

                    let firstname = ticket.ticket_customer.first_name != null ? ticket.ticket_customer.first_name  : '-';
                    let lastname = ticket.ticket_customer.last_name != null ? ticket.ticket_customer.last_name  : '-';
                    customer_name = firstname + ' ' + lastname;
                }else{
                    customer_name = `-`;
                }

                if (obj.length != 0) {

                    for (const item of obj) {
                        let name = item.reply_user == null ? '-' : (item.reply_user.name != null ? item.reply_user.name : '-');

                        if (item.reply_user != null) {
                            if (item.reply_user.profile_pic != null) {
                                user_img = `<span class="avatar"><img src="{{asset('${js_path}files/user_photos/${item.reply_user.profile_pic}')}}" 
                        width="40px" height="40px" class="round"/> </span>`;
                            } else {
                                user_img = `<span class="avatar"><img src="{{asset('${js_path}default_imgs/logo.png')}}" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" /></span>`;
                            }
                        } else {
                            user_img = `<span class="avatar"><img src="{{asset('${js_path}default_imgs/logo.png')}}" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" /></span>`;
                        }

                        row += `
                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                ${user_img}
                            </div>
                            <div class="col-md-8">
                                <h4> ${item.customer_id == null ? name : customer_name}</h4>
                                <span class="small">posted on created at ${ ticketDetail.date_conversion(item.created_at)} </span>
                                <hr>
                                <p> ${item.reply}</p>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-icon rounded-circle btn-outline-primary waves-effect">
                                        <i data-feather='edit'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                    }

                }

                $('.show_replies').html(row);

            }
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
            $('.replydiv').toggle();
        },

        updateTktBtn : () => {
            $('.update_tkt_tbn').show();
        },

        addAttachment : (type, olderAttach='') => {
            if(olderAttach) {
                $('#'+type+'_attachments').append(`<div class="input-group pt-3">
                    <div class="custom-file text-left">
                        <input type="file" class="form-control" id="${type}_attachment_${ticket_attachments_count}" disabled>
                        <label class="custom-file-label" for="${type}_attachment_${ticket_attachments_count}">${olderAttach}</label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" title="Remove" onclick="removeAttachment(this, '${olderAttach}', '${type}')"><span class="fa fa-times"></span></button>
                    </div>
                </div>`);
            } else {
                $('#'+type+'_attachments').append(`<div class="input-group pt-3">
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

        publishReply : (ele) => {

            var content= tinymce.activeEditor.getContent();
            var to_mails = $("#to_mails").val();
            
            if(to_mails == null || to_mails == "") {
                toastr.error( "CC field is required", { timeOut: 5000 });
                return false;
            }

            if(content == null || content == "" || content == "<p></p>") {
                toastr.error( "Description field is required", { timeOut: 5000 });
                return false;
            }

            tinyContentEditor(content, 'replies').then(function() {
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
                    if (edit_reply_mode !== false) {
                        rep_attaches = ticketReplies[edit_reply_mode].attachments;
                    }

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
                        params.id = ticketReplies[edit_reply_mode].id;
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
                            }else{
                                toastr.error( data.message , { timeOut: 5000 });
                            }
                        },
                        error:function(e) {
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

                if (src.includes(ticket_attach_path_search + '/' + action + '/' + ticket.id)) {
                    name = baseName(src) + '.' + ext;
                } else {
                    $(this).attr('src', ticket_attach_path + `/${action}/${ticket.id}/${name}`);
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
@endsection