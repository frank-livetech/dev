@extends('customer.layout.customer_master')
@section('body')


<div class="content-body">

    <input type="hidden" value="{{Session::get('is_live')}}" id="js_path">
    <input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">
    <input type="hidden" id="system_date" value="{{Session::get('system_date')}}">

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


    <div class="d-flex justify-content-between">
        <button class="btn btn-primary" onclick="ticketDetail.showReplyDiv()"> <i data-feather='plus'></i> add Reply</button>
        <button class="btn btn-success update_tkt_tbn" style="display:none"> <i data-feather='check'></i> Update </button>
    </div>

    <div class="card mt-1 replydiv" style="display:none">
        <div class="card-body">
            <form action="">
                <div class="col-md-12 mt-1">
                    <label for="">CC</label>
                    <input type="text" class="form-control" id="cc">
                </div>
                <div class="col-md-12 mt-1">
                    <label for="">Description</label>
                    <textarea class="form-control" name="desc" id="desc" cols="30" rows="10"></textarea>
                </div>
            </form>
        </div>
    </div>

    <div class="show_replies"></div>

</div>


@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
<script>
    $(document).ready(function() {
        let ticket = {!!json_encode($ticket) !!};

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
                var row = ``;
                var user_img = ``;

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
                                <h4> ${name}</h4>
                                <span class="small">posted on created at ${ ticketDetail.date_conversion(item.created_at)} </span>
                                <p> ${item.reply}</p>
                            </div>
                            <div class="col-md-3">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect mx-1">
                                    <i data-feather='trash'></i>
                                    </button>
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
        }
    }
</script>
@endsection