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
.attImg{
    height:100px;
    /* width:auto !important; */
}
br + br { display: none; }

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
.media {
    display: flex;
    align-items: flex-start;
}
.fileName{
    position: absolute;
    padding-left:9px;
    padding-right:9px;
    top: 7px;
    font-size: 11px;
    display:none;
    color:#777;
    text-align:left;
    word-break: break-all;
}
.downFile{
    position: absolute;
    bottom: 4px;
    right: 40%;
    border-radius: 4px;
    color: #fff;
    padding: 2px 10px;
    background: rgba(0,0,0,0.6);
    border: 1px solid #777;
    display:none;
}
.downFile:hover{
    background:rgba(0,0,0,0.7);
    border: 1px solid #777;

}
.downFile:hover i{
    color: #fff;
}
.borderOne{
    border: 1px solid #e6e7e8;
    text-align: center;
    width: 100%;
    min-height: 94px;
    /* padding: 29px 12px; */
    /* padding-top: 21%; */
    transition: 0.3s ease;
    position: relative;
}
.borderOne:hover .downFile
{
   display:block;
}
.borderOne:hover .fileName,
.borderOne:hover .overlayAttach
{
   display:block;
}
.borderOne img{
    width: 100%;
    /* max-width:89px; */
}
.imgIcon{
    width: 48px !important;
    padding-top: 24px;
}
.overlayAttach{
    position: absolute;
    background: #f5f5f5;
    top: 0;
    left: 0;
    width:100%;
    height:100%;
    display:none;
}
</style>

<div class="content-body">

    <input type="hidden" value="{{Session::get('is_live')}}" id="js_path">
    <input type="hidden" id="usrtimeZone" value="{{Session::get('timezone')}}">
    <input type="hidden" id="system_date" value="{{Session::get('system_date')}}">
    <input type="hidden" id="ticket_id" value="{{$ticket->id}}">

    <input type="hidden" id="p_id" >
    <input type="hidden" id="s_id" >

    <h1>View Ticket : {{$ticket->coustom_id}}</h1>

    <div class="row">
        <div class="col-md-8">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="text-muted">Subject</div>
                    <h3>{{$ticket->subject}} -</h3>

                    Created : <span id="tkt_created_at"> </span>
                    Updated : <span id="tkt_updated_at"> </span>

                    <hr>

                    <div class="text-muted" >Description</div>
            
                    <div class="card-body mail-message-wrapper frst">
                        <div class="mail-message">
                            <div class="row" id="ticket_details_p"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mt-2">
                <div class="card-body">
                    <div class="text-muted">Attachments</div>
                    <div class="row show_attachments"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0" id="status_html" style="background-color:{{($current_status == null) ? '' : ($current_status->color != null ? $current_status->color : ' ')}}">
            <div class="row">
                <div class="col-md-2 p-2" id="dep-label">
                    <label class="control-label col-sm-12 end_padding text-white"><strong>Department</strong></label>
                    <h5 class="text-white"> {{ $department->name != null ? $department->name : '-' }} </h5>
                </div>
                <div class="col-md-2 p-2" id="tech-label">
                    <label class="control-label col-sm-12 end_padding text-white"><strong> Owner</strong></label>
                    <h5 class="text-white"> Owner Name </h5>
                </div>
                <div class="col-md-2 p-2" id="type-label">
                    <label class="control-label col-sm-12 end_padding text-white"><strong> Type</strong></label>
                    <h5 class="text-white"> {{ $type->name != null ? $type->name : '-' }} </h5>
                </div>
                <div class="col-md-2 p-2">
                    <label class="control-label col-sm-12 end_padding text-white"><strong> Status</strong></label>
                    <div id="status_field">
                        <select class="select2 form-control" onchange="ticketDetail.updateTktType('status', this.value)" id="status" name="status">
                            @foreach($statuses as $status)
                            <option value="{{$status->id}}" data-color="{{$status->color}}" {{ $status->id == $ticket->status ? 'selected' : '' }}>{{$status->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2 p-2" id="priority_html" style="background-color:{{($current_priority == null) ? '' : ($current_priority->priority_color != null ? $current_priority->priority_color : ' ')}}">
                    <label class="control-label col-sm-12 end_padding text-white"><strong> Priority</strong></label>
                    <div id="priority_field">
                        <select class="select2 form-control "  onchange="ticketDetail.updateTktType('priority', this.value)" id="priority" name="priority" style="display:none">
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
                <h3>Ticket Replies  </h3>
                <div>
                    <button class="btn btn-success update_tkt_tbn" id="update_btn" onclick="ticketDetail.updateTkt(this)" style="display:none"> 
                        <div class="spinner-border text-light" role="status" style="height: 20px; width:20px; margin-right: 8px; display: none;">
                            <span class="sr-only">Loading...</span>
                        </div>
                                Update </button>
                    <button class="btn btn-primary" onclick="ticketDetail.showReplyDiv()"> <i data-feather='plus'></i> Add Reply</button>
                </div>
            </div>

            <div class="mt-1 replydiv" style="display:none">
                <form action="">
                    <div class="col-md-12 mt-1">
                        <label for="">CC</label> <br>
                        <input type="text" id="to_mails" name="to_mails" class="form-control border" placeholder="Email"  data-role="tagsinput" value="" required>
                    </div>
                    <input type="hidden" id="rid">
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

                            
                            <button id="rply" type="button" class="mt-3 btn waves-effect waves-light btn-danger float-right" onclick="ticketDetail.cancelReply(this)">
                                Cancel
                            </button>
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
            

            <!-- <div class="row mt-3">
                <div class="col-md-10">
                    <div class="d-flex align-items-center">
                        <div class="avatar mb-2 me-50">
                            <img src="../../../app-assets/images/portrait/small/avatar-s-9.jpg" alt="Avatar" width="50" height="50">
                        </div>
                        <div class="more-info">
                            <h3 class="mb-0">Carl Roy (Client)</h3>
                            <p class="mb-0">CEO of Infibeam</p>
                            <hr>
                        </div>
                        
                    </div>
                    <hr>
                </div>
                <div class="col-md-2 text-end">
                    <button type="button" class="btn btn-icon rounded-circle btn-outline-primary waves-effect">
                        <i data-feather='edit'></i>
                    </button>
                </div>
            </div> -->
            <!-- <div class="row show_replies mt-3"></div> -->
            <ul class="list-unstyled mt-5 replies show_replies"></ul>
        </div>
    </div>

</div>

<div style="display: none;" id="tinycontenteditor"></div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/5.0.0/tinymce.min.js"></script>
<script>
    var customer_img = ``;
    var customer_name = ``;
    var js_path = $("#js_path").val();
    js_path = (js_path == 1 ? 'public/' : '');
    
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        let ticket = {!!json_encode($ticket) !!};
        console.log(ticket , "ticket");

            // show attachments
            if(ticket != null) {
                var content = content.replace(/<img[^>]*>/g,"");
                tdet = `<div class="col-12">${content}</div>`;
            }
            if(ticket.attachments != null) {
                let attachments = ticket.attachments;
                attachments = attachments.split(',');
                let files = ``;
                let ext = ``;
                let file_name = ``;

                for(var i =0; i < attachments.length; i++) {
                    let extens = attachments[i].split('.');

                    for(var k =0; k < extens.length; k++) {
                        if(extens[1] == 'jpeg' || extens[1] == 'png' || extens[1] == 'jpg' || extens[1] == 'webp' || extens[1] == 'svg') {
                            ext = `<img src="{{asset('storage/tickets/${ticket.id}/${attachments[i]}')}}" class="img-fluid">`;
                            file_name = `image.jpeg`;
                        }

                        if(extens[1] == 'pdf') {
                            ext = `<img src="{{asset('${js_path}default_imgs/pdf.gif')}}" class=" attImg"  alt="" style="width:30px !important; height:30px !important">`;
                            file_name = `pdf.gif`;
                        }

                        if(extens[1] == 'txt') {
                            ext = `<img src="{{asset('${js_path}default_imgs/txt.gif')}}" class=" attImg"  alt="" style="width:30px !important; height:30px !important">`;
                            file_name = `txt.gif`;
                        }

                        if(extens[1] == 'docm' || extens[1] == 'docx' || extens[1] == 'dot' || extens[1] == 'dotx') {
                            ext = `<img src="{{asset('${js_path}default_imgs/word.gif')}}" class=" attImg"  alt="" style="width:30px !important; height:30px !important">`;
                            file_name = `word.gif`;
                        }

                        if(extens[1] == 'xls' || extens[1] == 'xlsb' || extens[1] == 'xlsm' || extens[1] == 'xlsx') {
                            ext = `<img src="{{asset('${js_path}default_imgs/xlx.gif')}}" class=" attImg"  alt="" style="width:30px !important; height:30px !important">`;
                            file_name = `xlx.gif`;
                        }

                        if(extens[1] == 'pptx' || extens[1] == 'pptm' || extens[1] == 'ppt') {
                            ext = `<img src="{{asset('${js_path}default_imgs/ppt.gif')}}" class=" attImg"  alt="" style="width:30px !important; height:30px !important">`;
                            file_name = `ppt.gif`;
                        }

                    }
                    files += `
                        
                        <div class="col-md-6 mt-1" style='position:relative;'>
                            <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                <span class="overlayAttach"></span>
                                ${ext}
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/${file_name}')}}"  alt=""> ${attachments[i]}</span>
                                <a href="" download="{{asset('storage/tickets/${ticket.id}/${attachments[i]}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>`
                }
                $('.show_attachments').html(files);
            }

        }

        $(".meta_tags").tagsinput('items');

        $("#tkt_created_at").html( ticketDetail.date_conversion(ticket.updated_at) );
        $("#tkt_updated_at").html( ticketDetail.date_conversion(ticket.updated_at) );


        // getting customer name
        if(ticket.ticket_customer != null || ticket.ticket_customer != "") {
            let firstname = ticket.ticket_customer.first_name != null ? ticket.ticket_customer.first_name  : '-';
            let lastname = ticket.ticket_customer.last_name != null ? ticket.ticket_customer.last_name  : '-';
            customer_name = firstname + ' ' + lastname;
        }else{
            customer_name = `-`;
        }

        // customer image
        if(ticket.ticket_customer != null) {
            if(ticket.ticket_customer.avatar_url != null) {
                let path = root +'/' + ticket.ticket_customer.avatar_url;
                customer_img = `<span class="avatar"><img src="${path}"  width="40px" height="40px" class="round"/> </span>`;  
            }else{
                customer_img = `<span class="avatar"><img src="{{asset('${js_path}default_imgs/customer.png')}}" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" /></span>`;
            }
        }else{
            customer_img = `<span class="avatar"><img src="{{asset('${js_path}default_imgs/customer.png')}}" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" /></span>`;
        }

    });
</script>
    @include('customer.Js.tkt_Js')
@endsection