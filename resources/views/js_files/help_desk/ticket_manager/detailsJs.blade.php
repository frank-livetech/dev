<script>
// Details Script Blade
let notes = [];
let timeouts_list = [];
let g_listFlupsTimer = null; // followups refresh timer
let g_followUp_timeouts = [];
let cc__emails = [];
let prv_clicked = null;
var loggedInUser_id = $("#loggedInUser_id").val();
var loggedInUser_t = $("#loggedInUser_t").val();

let edit_reply_mode = false;
let edit_reply_id = 0;
let attachments_src = [];
let ticket_attachments_count = 1;
let date_format = {!! json_encode($date_format) !!};
let shared_cc_emails = {!! json_encode($shared_cc_emails) !!};
let shared_bcc_emails = {!! json_encode($shared_bcc_emails) !!};

let update_flag = 0;
let updates_Arr = [];
// var ticket_attach_path = `{{asset('public/files')}}`;
// var ticket_attach_path_search = 'public/files';

let reply_flag = 0;
var ticket_attach_path = `{{asset('storage')}}`;
var ticket_attach_path_search = 'storage';
let check_followup = [];
var time_zone = $("#usrtimeZone").val();
var js_path = "{{Session::get('is_live')}}";
js_path = (js_path == 1 ? 'public/' : '');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});


$(document).ready(function() {


    if(shared_cc_emails != '' && shared_cc_emails != null){
        $('.cc_email_field').show();
        $('#show_cc_email').prop('checked', true);
    }
    if(shared_bcc_emails != '' && shared_bcc_emails != null){
        $('.bcc_email_field').show();
        $('#show_bcc_emails').prop('checked', true);
    }

    if(currentTime.length == 0) {
        let regiondate = convertDate(ticket.created_at);
        currentTime.push( regiondate );

        // console.log(regiondate , "regiondate");
    }
    //composer TinyMce
    tinymce.init({
        selector: "textarea.mymce",
        // theme: "modern",

        auto_focus : "mymce",

        height: 300,
        file_picker_types: 'image',
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor ","tb_variables"
        ],

        contextmenu: "cut copy paste | link image inserttable | cell row column deletetable",
        toolbar: "tb_variables | insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons spellchecker",

        spellchecker_callback: function (method, text, success, failure) {
            var words = text.match(this.getWordCharPattern());
            if (method === "spellcheck") {
            var suggestions = {};
            for (var i = 0; i < words.length; i++) {
                suggestions[words[i]] = ["First", "Second"];
            }
            success({ words: suggestions, dictionary: [ ] });
            } else if (method === "addToDictionary") {
            // Add word to dictionary here
            success();
            }
        },
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
                    cb(blobInfo.blobUri(), { title: file.name });
                };
                reader.readAsDataURL(file);
            };
            input.click();
        },
    }).then(function() {
        listReplies();
    }).catch(function(error) {
        listReplies();
    });




    getAllCodes();


    tinymce.init({
        selector: 'textarea#note',
        plugins: ["advlist autolink lists charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime table contextmenu paste"
        ],
        toolbar: 'bold italic underline alignleft link',
        menubar: false,
        statusbar: false,
        relative_urls : 0,
        remove_script_host : 0,
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    });


    $('#cust-creation-date').html( convertDate(ticket_customer.created_at) );
    $('#creation-date').text( convertDate(ticket.created_at)  );

    // old code
    // console.log(ticket , "ticket")
    // let dt = new Date(ticket.sla_res_deadline_from);
    // if(new Date(ticket.sla_rep_deadline_from) > new Date(ticket.sla_res_deadline_from)) dt = new Date(ticket.sla_rep_deadline_from);

    // // updted dt ---> new code
    // let res = moment(ticket.sla_res_deadline_from).valueOf();
    // let rep = moment(ticket.sla_rep_deadline_from).valueOf();
    // let updt_dt = rep > res ? ticket.sla_rep_deadline_from : ticket.sla_res_deadline_from;

    $('#updation-date').html( convertDate(ticket.updated_at) );

    // settle company name and phone values
    setCustomerCompany();
    getLatestLogs();
    $('#ticket-timestamp').text( 'Posted on ' +  convertDate(ticket.created_at) );
    // $('#ticket-timestamp2').text( convertDate(ticket.created_at) );
    $('.ticket-timestamp3').text('Posted on ' + convertDate( ticket.created_at) );


    if ($("#ticket_details_edit").length > 0) {
        tinymce.init({
            selector: "textarea#ticket_details_edit",
            // theme: "modern",
            height: 300,
            file_picker_types: 'image',
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            contextmenu: "cut copy paste | link image inserttable | cell row column deletetable",
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons spellchecker",
            spellchecker_callback: function (method, text, success, failure) {
                var words = text.match(this.getWordCharPattern());
                if (method === "spellcheck") {
                var suggestions = {};
                for (var i = 0; i < words.length; i++) {
                    suggestions[words[i]] = ["First", "Second"];
                }
                success({ words: suggestions, dictionary: [ ] });
                } else if (method === "addToDictionary") {
                // Add word to dictionary here
                success();
                }
            },
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
                        var blobCache = tinymce.editors.ticket_details_edit.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];

                        if (reader.result.includes('/svg') || reader.result.includes('/SVG')) {
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
    }

    $('#ticket_details_p').html(getTicketDetailsContent());
    $('#ticket_details_p3').html(getTicketDetailsContent());
    parserEmbeddedImages();
    parseAttachments();
    get_ticket_notes();
    getTicketFollowUp();

    getFormsTemplates();

    $("#save_ticket_follow_up").on('submit', createFollowUp);

    // $("#sla_plan_id").select2({
    //     matcher: matchStart,
    //     minimumInputLength: 2,
    //     minimumResultsForSearch: 10
    // });


    setSlaPlanDeadlines();

    $('.note-type-ticket').on('change', function() {
        $('.note-visibilty').removeAttr('disabled',false);
    });
    $('.note-type-user').on('change', function() {
        $('.note-visibilty').attr('disabled',true);
    });
    $('.note-type-user-org').on('change', function() {
        $('.note-visibilty').attr('disabled',true);
    });

});


setInterval(() => {

    if ($(".loaded ").hasClass('dark-layout')) {
        ticketDetailTheme.dark();
    }else{
        ticketDetailTheme.light();
    }

}, 2000);

// show hide cc & bcc email
$("#show_cc_email").click(function() {
    if( $(this).is(":checked") ) {
        $('.cc_email_field').show();
    }else{
        $('.cc_email_field').hide();
    }
});

$("#show_bcc_emails").click(function() {
    if( $(this).is(":checked") ) {
        $('.bcc_email_field').show();
    }else{
        $('.bcc_email_field').hide();
    }
});

function getAllCodes() {
    $.ajax({
        type: "GET",
        url: get_codes_route,
        dataType: 'json',
        success: function(data) {
            var obj = data.data;
            // tinymce custom variables plugin
            addVaribalesPlugin(obj);
            console.log(data, "data");

        },
        complete: function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function addVaribalesPlugin(options) {
    let items = [];
    for (let i in options) {
        items.push({ text: options[i].code, value: options[i].code });
    }

    tinymce.PluginManager.add('tb_variables', function(editor, url) {
        var openDialog = function() {
            return editor.windowManager.open({
                title: 'Template Builder Short Codes',
                body: {
                    type: 'panel',
                    items: [{
                        type: 'selectbox',
                        name: 'variable',
                        label: 'Select',
                        items: items,
                        flex: true
                    }]
                },
                buttons: [{
                        type: 'cancel',
                        text: 'Close'
                    },
                    {
                        type: 'submit',
                        text: 'Save',
                        primary: true
                    }
                ],
                onSubmit: function(api) {
                    var data = api.getData();
                    /* Insert content when the window form is submitted */
                    editor.insertContent(data.variable);
                    api.close();
                }
            });
        };
        /* Add a button that opens a window */
        editor.ui.registry.addButton('tb_variables', {
            text: '{Short Codes}',
            onAction: function() {
                /* Open window */
                openDialog();
            }
        });
        /* Adds a menu item, which can then be included in any menu via the menu/menubar configuration */
        editor.ui.registry.addMenuItem('tb_variables', {
            text: 'Template Builder Short Codes',
            onAction: function() {
                /* Open window */
                openDialog();
            }
        });
        /* Return the metadata for the help plugin */
        return {
            getMetadata: function() {
                return {
                    name: 'Template Builder Short Codes',
                    url: 'http://exampleplugindocsurl.com'
                };
            }
        };
    });
}


function ticket_attachemnt_view() {
    if(ticket_details != null || ticket_details != "") {
        let files = ticket_details.attachments.split(',');
        let file_row = ``;
        let extension_img = ``;
        for(let i =0; i < files.length; i++) {

            let extens = files[i].split('.');

            if(extens[1] == 'jpeg' || extens[1] == 'png' || extens[1] == 'jpg' || extens[1] == 'webp' || extens[1] == 'svg') {
                extension_img = `<img src="{{asset('default_imgs/image.jpeg')}}" width="20px" height="20px">`;
            }

            if(extens[1] == 'pdf') {
                extension_img = `<img src="{{asset('default_imgs/pdf.gif')}}" width="20px" height="20px">`;
            }

            if(extens[1] == 'txt') {
                extension_img = `<img src="{{asset('default_imgs/txt.gif')}}" width="20px" height="20px">`;
            }

            if(extens[1] == 'docm' || extens[1] == 'docx' || extens[1] == 'dot' || extens[1] == 'dotx') {
                extension_img = `<img src="{{asset('default_imgs/word.gif')}}" width="20px" height="20px">`;
            }

            if(extens[1] == 'xls' || extens[1] == 'xlsb' || extens[1] == 'xlsm' || extens[1] == 'xlsx') {
                extension_img = `<img src="{{asset('default_imgs/xlx.gif')}}" width="20px" height="20px">`;
            }

            if(extens[1] == 'pptx' || extens[1] == 'pptm' || extens[1] == 'ppt') {
                extension_img = `<img src="{{asset('default_imgs/ppt.gif')}}" width="20px" height="20px">`;
            }

            file_row +=`
                <div class="col-md-12 mt-1">
                    ${extension_img}
                    <a href="${root}/storage/tickets/${ticket_details.id}/${files[i]}" target="_blank">${files[i]}</a>
                </div> `
        }
        return file_row;
    }

}


$('#res-template').change(function() {
    if( $(this).val() == "" ) {
        // tinymce.activeEditor.setContent('');
    }else{
        let content = tinymce.activeEditor.getContent();
        let res = res_templates_list.find(item => item.id == $(this).val());
        // if(content.length == 0)  {
            // tinymce.activeEditor.setContent(`<p>${res.temp_html}</p>`);
            tinymce.activeEditor.execCommand('mceInsertContent', false, `<p>${res.temp_html}</p>`);
            $('#res-template').val('').trigger('change');
        // }
        // Swal.fire({
        //     title: 'Are you sure?',
        //     text: 'All template changes will be lost!',
        //     icon: 'warning',
        //     showCancelButton: true,
        //     confirmButtonColor: '#3085d6',
        //     cancelButtonColor: '#d33',
        //     confirmButtonText: 'Yes'
        // }).then((result) => {
        //     if (result.value) {
        //         tinymce.activeEditor.setContent(res.temp_html ? res.temp_html : '');
        //     }
        // });
    }
});

// convert current date to provided timezone date with format
function convertDate(date) {
    var d = new Date(date);

    var min = d.getMinutes();
    var dt = d.getDate();
    var d_utc = d.getUTCHours();

    d.setMinutes(min);
    d.setDate(dt);
    d.setUTCHours(d_utc);

    let a = d.toLocaleString("en-US" , {timeZone: time_zone});
    // return a;
    var converted_date = moment(a).format(date_format + ' ' +'hh:mm A');
    return converted_date;
}

function setSlaPlanDeadlines(ret = false) {
    let res_due = '';
    let rep_due = '';
    let resetable = false;

    if (ticket_slaPlan.title != 'No SLA Assigned') {
        if (ticket.hasOwnProperty('resolution_deadline') && ticket.resolution_deadline) {
            // use ticket reset deadlines
            if(ticket.resolution_deadline == 'cleared') {
                $('#sla-res_due').parent().addClass('d-none');
            } else {
                res_due = moment(moment(ticket.resolution_deadline).toDate()).local();
                $('#ticket-res-due').val(ticket.resolution_deadline);
            }

        } else if (ticket_slaPlan.due_deadline) {
            // use sla deadlines
            let hm = ticket_slaPlan.due_deadline.split('.');
            res_due = moment(moment(ticket.sla_res_deadline_from).toDate()).local().add(hm[0], 'hours');
            if (hm.length > 1) res_due.add(hm[1], 'minutes');
        }

        if (res_due) {
            // overdue or change format of the date
            if (res_due.diff(moment(), "seconds") < 0) {
                resetable = true;
                res_due = `<span class="text-center" style="color:red;">Overdue</span>`;
            } else {
                // do date formatting
                // res_due = res_due.format(date_format);
                res_due = getClockTime(res_due, 1);

            }
        }

        $('#sla-rep_due').parent().removeClass('d-none');
        $('#sla-res_due').parent().removeClass('d-none');
        if (ticket.hasOwnProperty('reply_deadline') && ticket.reply_deadline) {

            if(ticket.reply_deadline == 'cleared') {
                $('#sla-rep_due').parent().addClass('d-none');
            } else {
                rep_due = moment(moment(ticket.reply_deadline).toDate()).local();
                $('#ticket-rep-due').val(ticket.reply_deadline);
            }
        } else if (ticket_slaPlan.reply_deadline) {
            let hm = ticket_slaPlan.reply_deadline.split('.');
            rep_due = moment(moment(ticket.sla_rep_deadline_from).toDate()).local().add(hm[0], 'hours');
            if (hm.length > 1) rep_due.add(hm[1], 'minutes');
        }



        if (rep_due) {
            // overdue or change format of the date
            if (rep_due.diff(moment(), "seconds") < 0) {
                resetable = true;
                rep_due = `<span class="text-center" style="color:red;"> Overdue </span>`;
            } else {
                // do date formatting
                resetable = false;
                // rep_due = rep_due.format(date_format);
                rep_due = getClockTime(rep_due, 1);
            }
        }
    }
    if (ret) return { rep_due: rep_due, res_due: res_due };

    let currTime = new Date().toLocaleString('en-US', { timeZone: time_zone });
    let con_currTime = moment(currTime).format('YYYY-MM-DD hh:mm A');
    let endtime = moment('2022-03-08 12:23 PM').format('YYYY-MM-DD hh:mm A');

    if(ticket != null) {

        if(ticket.reply_deadline == null) {
            if (rep_due) $('#sla-rep_due').html(rep_due.replace("60m", "59m"));
        }

        if(ticket.resolution_deadline == null) {
            if (res_due) $('#sla-res_due').html(res_due.replace("60m", "59m"));
        }

        if(ticket.reply_deadline != null){

            let rep_diff = ``;
            if(ticket.reply_deadline != "cleared") {
                let tkt_rep_due = moment(ticket.reply_deadline , "YYYY-MM-DD hh:mm A").format('YYYY-MM-DD hh:mm A');
                let timediff_rep = getDatesSeconds( ticket.reply_deadline  , con_currTime  );
                if(timediff_rep <= 0) {
                    resetable = true;
                    console.log("123");
                    rep_diff = `<span class="text-center" style="color:red;">Overdue</span>`;
                }else{
                    resetable = false;
                    rep_diff = getHoursMinutesAndSeconds( ticket.reply_deadline, con_currTime);
                }
                $('#sla-rep_due').html( rep_diff );
            }else{
                $('#sla-rep_due').parent().addClass('d-none');
            }
        }

        if(ticket.resolution_deadline != null){

            let res_diff = ``;

            if(ticket.resolution_deadline != "cleared") {

                let timediff_res = getDatesSeconds( ticket.resolution_deadline , con_currTime  );
                let tkt_res_due = moment(ticket.resolution_deadline , "YYYY-MM-DD hh:mm A").format('YYYY-MM-DD hh:mm A');
                if(timediff_res <= 0) {
                    resetable = true;
                    res_diff = `<span class="text-center" style="color:red;">Overdue</span>`;
                }else{
                    // resetable = false;
                    res_diff = getHoursMinutesAndSeconds( ticket.resolution_deadline , con_currTime);
                }
                $('#sla-res_due').html(res_diff);
            }else{
                $('#sla-res_due').parent().addClass('d-none');
            }

        }
    }

    // any deadline is overdue can be reset
    let bgcolor = $("#bgcolor").val();
    let textcolor = $("#textcolor").val();
    if (resetable) {
        $('#card-sla').attr('style', `background-color: ${bgcolor} !important; color : ${textcolor} !important`);
        $('#card-sla a').attr('style', `color : ${textcolor} !important`);
    } else {
        $("#card-sla").css('background-color', 'white');
        $("#card-sla").css('color', '#000');
    }
}

function getHoursMinutesAndSeconds(date_one , date_two) {
    let greater = moment(date_one , "YYYY-MM-DD hh:mm A").valueOf();
    let smaller = moment(date_two , "YYYY-MM-DD hh:mm A").valueOf();

    let diff = ((greater - smaller) / 1000).toFixed(2) ;
    let days = Math.floor(diff / 86400);
    let hours = Math.floor(diff / 3600) % 24;
    let minutes = Math.floor(diff / 60) % 60;
    let seconds = diff % 60;


    let remainTime =  (days > 0 ? days +  'd ' : '') + (hours > 0 ? hours +  'h ' : '') + minutes + 'm ' + (seconds > 0 ? seconds +  's ' : '');

    let color = ``;
    if(remainTime.includes('d')) {
        color = `#8BB467`;
    }else if(remainTime.includes('h')) {
        color = `#5c83b4`;
    }else if(remainTime.includes('m')) {
        color = `#ff8c5a`;
    }


    return `(<span style="color: ${color}">${remainTime}</span>)`;
}

function getDatesSeconds(greater_date , smaller_date) {
    let greater = moment(greater_date , "YYYY-MM-DD hh:mm A").valueOf();
    let smaller = moment(smaller_date , "YYYY-MM-DD hh:mm A").valueOf();
    let time = greater - smaller;

    let sec = 1000;
    return (time / sec);
}

function momentDiff(end ,  start) {

    let diff = moment.preciseDiff(end , start);
    diff = diff.replace(" days", "d");
    diff = diff.replace(" day", "d");
    diff = diff.replace(" hour", "h");
    diff = diff.replace("hs", "h");
    diff = diff.replace(" minutes", "m");
    diff = diff.replace(" minute", "m");
    diff = diff.replace(" seconds", "s");
    diff = diff.replace(" second", "s");


    let color = ``;
    if(diff.includes('d')) {
        color = `#8BB467`;
    }else if(diff.includes('h')) {
        color = `#5c83b4`;
    }else if(diff.includes('m')) {
        color = `#ff8c5a`;
    }


    let time = `(<span style="color: ${color}">${diff}</span>)`;
    return time;
}

function resetSlaPlan() {
    // console.log(ticket , "ticket");
    if(ticket != null) {
        if(ticket.reply_deadline == null || ticket.resolution_deadline == null) {
            if(ticket_slaPlan != null && ticket_slaPlan != "") {
                const today = new Date();

                if(ticket.reply_deadline != "cleared") {
                    var reply_deadline =  moment().utc(today).add(ticket_slaPlan.reply_deadline , 'h').format('YYYY-MM-DDThh:mm');
                    $("#ticket-rep-due").val(reply_deadline);
                }

                var deadline_time =   moment().utc(today).add(ticket_slaPlan.due_deadline , 'h').format('YYYY-MM-DDThh:mm')
                $("#ticket-res-due").val(deadline_time);
            }
        }else{
            setSlaPlanDeadlines();
        }
    }

    $("#reset_sla_plan_modal").modal("show");
}

// new function
function SlaPlanReset() {
    console.log(ticket , "ticket");
    if(ticket != null) {

        if( ticket.reply_deadline == null || ticket.resolution_deadline == null ) {

            if( ticket.reply_deadline == null ) {
                if(ticket_slaPlan != null && ticket_slaPlan != "") {
                    let reply_date = moment(currentTime[0]).add(ticket_slaPlan.reply_deadline , 'h');
                    $("#reply_date").val( moment(reply_date , "YYYY-MM-DD").format('YYYY-MM-DD') );
                    $("#reply_hour").val(  reply_date.format('h') );
                    $("#reply_minute").val(  reply_date.format('mm') );
                    $("#reply_type").val(  reply_date.format('A') );
                }
            }

            if( ticket.reply_deadline == null ) {
                if(ticket_slaPlan != null && ticket_slaPlan != "") {
                    let due_deadline = moment(currentTime[0]).add(ticket_slaPlan.due_deadline , 'h');
                    $("#res_date").val( moment(due_deadline , "YYYY-MM-DD").format('YYYY-MM-DD') );
                    $("#res_hour").val(  due_deadline.format('h'));
                    $("#res_minute").val(  due_deadline.format('mm') );
                    $("#res_type").val(  due_deadline.format('A') );
                }
            }
        }

        if(ticket.reply_deadline != null || ticket.resolution_deadline != null ) {

            if(ticket.reply_deadline != "cleared") {
                let rep_deadline = moment(ticket.reply_deadline , "YYYY-MM-DD h:mm A").format("YYYY-MM-DD h:mm A");
                let time  = rep_deadline.split(' ');
                let split_hours = time[1].split(':');

                $("#reply_date").val( time[0] );
                $("#reply_hour").val(  split_hours[0] );
                $("#reply_minute").val( split_hours[1] );
                $("#reply_type").val(  time[2] );
            }else{
                $("#reply_date").val("");
                $("#reply_hour").val(12);
                $("#reply_minute").val('00');
                $("#reply_type").val('PM');
            }

            if(ticket.resolution_deadline != 'cleared') {

                let res_deadline = moment(ticket.resolution_deadline , "YYYY-MM-DD h:mm A").format("YYYY-MM-DD h:mm A");
                let time  = res_deadline.split(' ');
                let split_hours = time[1].split(':');

                $("#res_date").val(time[0] );
                $("#res_hour").val( split_hours[0] );
                $("#res_minute").val(  split_hours[1] );
                $("#res_type").val( time[2] );
            }else{
                $("#res_date").val("");
                $("#res_hour").val(12);
                $("#res_minute").val('00');
                $("#res_type").val('PM');
            }

            setSlaPlanDeadlines();
        }
    }

    $("#reset_sla_plan_modal").modal("show");
}
// new function
function slaPlanDeadlines(ret = false) {
    let res_due = '';
    let rep_due = '';
    let resetable = false;

    if (ticket_slaPlan.title != 'No SLA Assigned') {
        console.log("if");
        if (ticket.hasOwnProperty('resolution_deadline') && ticket.resolution_deadline) {
            // use ticket reset deadlines
            if(ticket.resolution_deadline == 'cleared') {
                $('#sla-res_due').parent().addClass('d-none');
            } else {
                res_due = moment(moment(ticket.resolution_deadline).toDate()).local();
                $('#ticket-res-due').val(ticket.resolution_deadline);

                let newDat = moment(ticket.resolution_deadline);
                $("#res_date").val( newDat.format('YYYY-MM-DD') );
                $("#res_hour").val(  newDat.format('h') );
                $("#res_minute").val(  newDat.format('mm') );
                $("#res_type").val(  newDat.format('A') );
            }

        } else if (ticket_slaPlan.due_deadline) {
            let hm = ticket_slaPlan.due_deadline.split('.');
            res_due = moment(moment(ticket.sla_res_deadline_from).toDate()).local().add(hm[0], 'hours');
            if (hm.length > 1) res_due.add(hm[1], 'minutes');
        }

        if (res_due) {
            // overdue or change format of the date
            console.log(res_due , "res_due 123");
            if (res_due.diff(moment(), "seconds") < 0) {
                resetable = true;
                res_due = `<span class="text-center" style="color:red;">Overdue</span>`;
            } else {
                // do date formatting
                // res_due = res_due.format(date_format);
                res_due = getClockTime(res_due, 1);

            }
        }

        $('#sla-rep_due').parent().removeClass('d-none');
        if (ticket.hasOwnProperty('reply_deadline') && ticket.reply_deadline) {
            // use ticket reset deadlines
            if(ticket.reply_deadline == 'cleared') {
                $('#sla-rep_due').parent().addClass('d-none');
            } else {
                rep_due = moment(moment(ticket.reply_deadline).toDate()).local();
                $('#ticket-rep-due').val(ticket.reply_deadline);
                let newDat = moment(ticket.reply_deadline);
                $("#reply_date").val( newDat.format('YYYY-MM-DD') );
                $("#reply_hour").val(  newDat.format('h') );
                $("#reply_minute").val(  newDat.format('mm') );
                $("#reply_type").val(  newDat.format('A') );
            }
        } else if (ticket_slaPlan.reply_deadline) {
            let hm = ticket_slaPlan.reply_deadline.split('.');
            rep_due = moment(moment(ticket.sla_rep_deadline_from).toDate()).local().add(hm[0], 'hours');
            if (hm.length > 1) rep_due.add(hm[1], 'minutes');
        }

        if (rep_due) {
            // overdue or change format of the date
            if (rep_due.diff(moment(), "seconds") < 0) {
                resetable = true;
                rep_due = `<span class="text-center" style="color:red;"> Overdue </span>`;
            } else {
                // do date formatting
                resetable = false;
                // rep_due = rep_due.format(date_format);
                rep_due = getClockTime(rep_due, 1);
            }
        }
    }
    console.log(ret , "ret");
    console.log(rep_due , "rep_due123");
    console.log(res_due , "res_due123");
    if (ret) return { rep_due: rep_due, res_due: res_due };


    if (rep_due) $('#sla-rep_due').html(rep_due);
    if (res_due) $('#sla-res_due').html(res_due);

    // any deadline is overdue can be reset
    let bgcolor = $("#bgcolor").val();
    let textcolor = $("#textcolor").val();
    if (resetable) {
        $('#card-sla').attr('style', `background-color: ${bgcolor} !important; color : ${textcolor} !important`);
        $('#card-sla a').attr('style', `color : ${textcolor} !important`);
    } else {
        $("#card-sla").css('background-color', 'white');
        $("#card-sla").css('color', '#000');
    }
}

$("#ticket-rep-due").on('change' , function() {

});

$("#response_template").click(function() {
    $(this).is(":checked") ? $('#response_template_fields').show() :  $('#response_template_fields').hide();
    $("#res_title").val("");
    $("#category_name").val("").trigger('change');
    $("#onlyMe").prop("checked", false);
    $("#allStaff").prop("checked", true);
});

function updateDeadlines() {

    let currdate = new Date().toLocaleString('en-US', { timeZone: time_zone });
    currdate = moment(currdate).format("YYYY-MM-DD h:mm A");

    let rp_date = $("#reply_date").val();
    let rp_hour = $("#reply_hour").val();
    let rp_min = $("#reply_minute").val();
    let rp_type = $("#reply_type").val();

    let rep_deadline = rp_date + ' ' +rp_hour + ':' + rp_min + ' ' + rp_type;

    let res_date = $("#res_date").val();
    let res_hour = $("#res_hour").val();
    let res_min = $("#res_minute").val();
    let res_type = $("#res_type").val();

    let res_deadline = res_date + ' ' +res_hour + ':' + res_min + ' ' + res_type;

    let overdue = '';

    let timediff_rep = getDatesSeconds( rep_deadline  , currdate  );
    let timediff_res = getDatesSeconds( res_deadline  , currdate  );

    if(timediff_rep < 0) {
        overdue = 1;
    }else{
        overdue = 0;
    }

    if(overdue == 0) {
        if(timediff_res <= 0) {
            overdue = 1;
        }else{
            overdue = 0;
        }
    }

    if(res_date == '') {
        res_deadline  = 'cleared';
        overdue = 0;
    }

    if(rp_date == '') {
        rep_deadline  = 'cleared';
        overdue = 0;
    }

    let formData = {
        ticket_id: ticket.id,
        rep_deadline: rep_deadline,
        res_deadline: res_deadline,
        overdue : overdue,
    };

    console.log(formData , "formdata");

    $.ajax({
        type: "post",
        url: $('#sla_plan_reset_form').attr("action"),
        data: formData,
        dataType: 'json',
        cache: false,
        success: function(data) {
            Swal.fire({
                position: 'center',
                icon: data.success ? "success" : "error",
                title: data.message,
                showConfirmButton: false,
                timer: swal_message_time
            });

            if (data.success) {
                $("#reset_sla_plan_modal").modal("hide");
                // $('#sla_plan_reset_form').trigger("reset");

                ticket.reply_deadline = formData.rep_deadline;
                ticket.resolution_deadline = formData.res_deadline;

                setSlaPlanDeadlines();
            }
        }
    });
}

function changeSlaPlan() {
    if (ticket_slaPlan.hasOwnProperty('id') && ticket_slaPlan.id) $('#sla_plan_id').val(ticket_slaPlan.id);
    $("#sla_plan_modal").modal("show");
}

function setSlaPlan() {
    Swal.fire({
        title: 'Update ticket SLA plan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: set_sla_plan_route,
                data: {
                    ticket_id: ticket.id,
                    sla_plan_id: $('#sla_plan_id').val(),
                },
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (data.success == true) {
                        $("#sla_plan_modal").modal("hide");

                        let sel_slaplan = sla_plans_list.filter(item => item.id == $('#sla_plan_id').val());
                        if (sel_slaplan.length) {
                            sel_slaplan = sel_slaplan[0];

                            let temp = ticket_slaPlan.bg_color;
                            ticket_slaPlan = sel_slaplan;
                            ticket_slaPlan.bg_color = temp;
                            $('#sla-title').html(sel_slaplan.title);

                            ticket.reply_deadline = null;
                            ticket.resolution_deadline = null;
                            ticket.sla_rep_deadline_from = moment();
                            ticket.sla_res_deadline_from = moment();
                            setSlaPlanDeadlines();
                        }

                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'SLA updated Successfully!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                        alertNotification('success', 'Success' , 'SLA updated Successfully!' );
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    }
                }
            });
        }
    });
}

function editRequest() {
    // toggleReq();

    let sbj = $(".tkt-subject").text();
    $('.ticket_subject_edit').val(sbj);

    $('#ticket_subject_heading').css('display', 'none');
    $('#ticket_details_p').css('display', 'none');
    $('#edit_request_btn').css('display', 'none');
    $('#ticket_subject_edit_div').css('display', 'block');
    $('#ticket_details_edit_div').css('display', 'block');
    $('#save_request_btn').css('display', 'block');
    $('#cancel_request_btn').css('display', 'block');

    if(ticket_details.attachments) {
        let attchs = ticket_details.attachments.split(',');
        $('#tickets_attachments').html('');
        ticket_attachments_count = 0;
        attchs.forEach(item => {
            addAttachment('tickets', item);
        });
    }

    if(ticket != null && ticket != "") {
        tinyMCE.editors.ticket_details_edit.setContent(ticket.ticket_detail);
    }


}

function cancelEditRequest() {
    $('#ticket_subject_heading').css('display', 'block');
    $('#ticket_details_p').css('display', 'block');
    $('#edit_request_btn').css('display', 'block');
    $('#ticket_subject_edit_div').css('display', 'none');
    $('#ticket_details_edit_div').css('display', 'none');
    $('#save_request_btn').css('display', 'none');
    $('#cancel_request_btn').css('display', 'none');
    $('#subject').css('display', 'none');
    $('#ticket-details').css('display', 'none');

    $('#ticket_details_p').html(getTicketDetailsContent());
    $('#ticket_details_p3').html(getTicketDetailsContent());
}

function baseName(str) {
    var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if (base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
    return base;
}

function getBlobFromUrl(bUrl) {
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

function fromBlobToBase64(blob) {
    return new Promise((resolve, reject) => {
        let reader = new FileReader()
        reader.addEventListener('loadend', event => {
            resolve(reader.result)
        })
        reader.readAsDataURL(blob)
    })
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
                // name = baseName(src) + '.' + ext;
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

function saveRequest() {

    subject_div = $('#ticket_subject_edit').val();
    // ticket_details = $('#ticket_details_edit').val();

    var content = tinyMCE.editors.ticket_details_edit.getContent();
    tinyContentEditor(content, 'tickets').then(function() {
        content = $('#tinycontenteditor').html();

        if (subject_div == '' || subject_div == null) {
            $('#subject').css('display', 'block');
            return false;
        } else if (!content || content == '<p></p>') {
            $('#ticket-details').css('display', 'block');
            return false;
        }

        let fileSizeErr = false;
        $('.tickets_attaches').each(function(index) {
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

        $.ajax({
            type: "post",
            url: update_ticket_route,
            data: {
                id: ticket.id,
                subject: $('#ticket_subject_edit').val(),
                ticket_detail: content,
                attachments: attachments_src,
                action: 'ticket_detail_update',
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.success == true) {

                    $("#tkt-subject").text(subject_div);
                    ticket_details.ticket_detail = content;
                    updateTicketDate();

                    let mssg = 'Subject updated';
                    // upload attachments
                    $('.tickets_attaches').each(function(index) {
                        if(this.files.length) {
                            // mssg = 'Subject updated with attachments';

                            let fileData = new FormData();
                            fileData.append('ticket_id', ticket_details.id);
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
                                    } else {
                                        ticket_details.attachments = res.attachments;
                                    }
                                },
                                error: function(data) {
                                }
                            });
                        }
                    });
                    $('#tinycontenteditor').html('');

                    // send mail notification regarding ticket action
                    ticket_notify('ticket_update', mssg);

                    // refresh logs
                    getLatestLogs();

                    alertNotification('success', 'Success' , 'Initial Request Updated Successfully');

                    $('#ticket_subject_heading').css('display', 'block');
                    $('#ticket_details_p').css('display', 'block');
                    $('#edit_request_btn').css('display', 'block');
                    $('#ticket_subject_edit_div').css('display', 'none');
                    $('#ticket_details_edit_div').css('display', 'none');
                    $('#save_request_btn').css('display', 'none');
                    $('#cancel_request_btn').css('display', 'none');

                    $('#ticket_subject_heading').text($('#ticket_subject_edit').val());
                    $('#ticket_details_p').html(getTicketDetailsContent());
                    $('#ticket_details_p3').html(getTicketDetailsContent());
                }
            }
        });
    });
}

function getTicketDetailsContent() {
    let tdet = '';
    var content = ticket_details.ticket_detail;
    if(ticket_details != null || ticket_details != "") {

        // if(ticket_details.tkt_crt_type == 'cron'){
        //     content = content.replace(/<img[^>]*>/g,"");
        // }else{
            content = content;
        // }

        tdet = ``;

    }



    tdet += `<div class="col-12" id="editor_div">${content}</div>`;

    return tdet;
}

function parseAttachments(){
    let tdet = '';
    if(ticket_details.attachments) {
        let attchs = ticket_details.attachments.split(',');

        tdet +=`<div class="row">
                    <h6 style="font-size:.8rem !important"><strong>Attachments</strong></h6>
                </div>`
        attchs.forEach(item => {
            var tech =  `{{asset('/storage/tickets/${ticket_details.id}/${item}')}}`;
            var ter = getExt(tech);
            // return ter;
            if(ter == "pdf" ){
                tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}pdf.png" width="25px">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
            else if(ter == "csv" || ter == "xls" || ter == "xlsx" || ter == "sql"){
                tdet+= `
                <div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}xlx.png" width="25px">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
            else if(ter == "png" || ter == "jpg" || ter == "webp" || ter == "jpeg" || ter == "webp" || ter == "svg" || ter == "psd"){
                tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{asset('storage/tickets/${ticket_details.id}/${item}')}}" class="attImg"  alt="" style="width:40px;height:30px !important">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
            else if(ter == "docs" || ter == "doc" || ter == "txt" || ter == "dotx" || ter == "docx"){
                tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}word.png" width="25px">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
            else if(ter == "ppt" || ter == "pptx" || ter == "pot" || ter == "pptm"){
                tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}pptx.png" width="25px">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
            else if(ter == "zip"){
                tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}zip.png" width="25px">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
            else{
                tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}')" >
                                <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}txt.png" width="25px">
                                                            </div>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>`
            }
        });
    }

    $('#ticket_details_p_attachments').html(tdet);
    $('#ticket_details_p3_attachments').html(tdet);
}

function parserEmbeddedImages(){

    var index = 0;
    $('#ticket_details_p img').each(function () {
        let attchs = '';
        if(ticket_details.embed_attachments != null){

            attchs = ticket_details.embed_attachments.split(',');

        }
        console.log(attchs[index])
        if(attchs[index] == undefined){

        }else{
            $(this).attr('src', "{{asset('storage/tickets')}}/"+ticket_details.id+'/'+attchs[index]);
            $(this).attr("onClick","showAttachedImage("+ticket_details.id+",`" +attchs[index] +"`)");
            index++;
        }


    });
    var index1 = 0;
    $('#ticket_details_p3 img').each(function () {

        let attchs = '';
        if(ticket_details.embed_attachments != null){

            attchs = ticket_details.embed_attachments.split(',');

        }
        if(attchs[index1] != undefined){
            $(this).attr('src', "{{asset('storage/tickets')}}/"+ticket_details.id+'/'+attchs[index1]);
            $(this).attr("onClick","showAttachedImage("+ticket_details.id+"," + attchs[index1]  + ")");
            index1++;
        }


    });

}

function showAttachedImage(id, item , type = '') {
    let img = ``;

    if(type == 'reply'){
        img = `<img src="{{asset('storage/tickets-replies/${id}/${item}')}}" class="w-100 h-100">`;
    }else{
        img = `<img src="{{asset('storage/tickets/${id}/${item}')}}" class="w-100 h-100">`;
    }
    let csv = `<img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}xlx.png"> `;
    let pdf = `<img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}pdf.png">`;
    let doc = `<img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}word.png">` ;
    let pptx = `<img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}pptx.png"> `;
    let zip =   `<img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}zip.png">`
    let downloadimg = '';
    if(type == 'reply'){
        downloadimg = `<a class="btn btn-primary waves-effect waves-float waves-light" href="{{asset('storage/tickets-replies/${id}/${item}')}}" download><svg style="color: #fff" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg><span style="color: #fff"> Download</span></a>`;
    }else{
        downloadimg = `<a class="btn btn-primary waves-effect waves-float waves-light" href="{{asset('storage/tickets/${id}/${item}')}}" download><svg style="color: #fff" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg><span style="color: #fff"> Download</span></a>`;
    }

    var file_type = img.substr(img.lastIndexOf('.')).toLowerCase();

    if (file_type.includes('csv') || file_type.includes('xls') || file_type.includes('xlsx') || file_type.includes('sql')) {
        $('.showDefaultPreview').html(csv);

    }else if(file_type.includes('pdf')){
        $('.showDefaultPreview').html(pdf);

    }else if(file_type.includes('docs') || file_type.includes('doc') || file_type.includes('txt') || file_type.includes('dotx') || file_type.includes('docx')){
        $('.showDefaultPreview').html(doc);

    }else if(file_type.includes('ppt') || file_type.includes('pptx') || file_type.includes('pot') || file_type.includes('pptm')){
        $('.showDefaultPreview').html(pptx);

    } else if(file_type.includes('zip')){
        $('.showDefaultPreview').html(zip);

    }else{
        $('.showDefaultPreview').html(img);
    }
    $('.DownloadImage').html(downloadimg);
    $("#defaultPreview").modal('show');
    $('#defaultPreview').draggable({
        "handle":".modal-header"
    });
}

function showAttachmentPreview(id , item) {
    let path = root + `/storage/tickets/${id}/${item}`;
    $("#full-image").attr("src", path);
    $('#image-viewer').show();
}

function parserReplyEmbeddedImages(reply_id , images , type){

    var index = 0;
    $('#'+reply_id+' img').each(function () {
        let attchs = '';


        if(images != null && images != 'null'){

            attchs = images.split(',');

        }
        var classList = $(this).attr("class");
        // console.log(attchs[index])
        if(attchs[index] == undefined || attchs[index] == null){
            if(classList != 'rounded-circle' && classList != 'attImg' && type == 'cron'){
                $(this).remove();
            }
        }else{

            if(classList != 'rounded-circle' && classList != 'attImg'){
                $(this).attr('src', "{{asset('storage/tickets-replies')}}/"+ticket_details.id+'/'+attchs[index]);
                // $(this).attr("onClick","showAttachedImage("+ticket_details.id+",`" +attchs[index] +"`)");
                index++;
            }

        }


    });

}

function listReplies() {
    $('#ticket-replies').html('');
    // console.log(ticketReplies , "ticketReplies");
    ticketReplies = ticketReplies.sort(function(a, b) {
        var keyA = new Date(a.updated_at),
            keyB = new Date(b.updated_at);
        // Compare the 2 dates
        if (keyA < keyB) return 1;
        if (keyA > keyB) return -1;
        return 0;
    });

    let replies_html = ``;
    if(ticketReplies.length > 0) {
        ticketReplies.forEach(function(reply, index) {
            replies_html = ``;
            if (reply.is_published === 0) {
                editReply(null,index);
                $('#draft-rply').show();
            } else {
                let tdet = '';
                if(reply.attachments) {
                    let attchs = reply.attachments.split(',');
                    tdet +=`<div class="row">
                                <h6 style="font-size:.8rem !important"><strong>Attachments</strong></h6>
                            </div>`
                    attchs.forEach(item => {
                        var tech =  `{{asset('storage/tickets-replies/${ticket_details.id}/${item}')}}`;
                        var ter = getExt(tech);


                        // return ter;
                    if(ter == "pdf" ){
                        tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}pdf.png" width="25px">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>`
                    }
                    else if(ter == "csv" || ter == "xls" || ter == "xlsx" || ter == "sql"){
                        tdet+= `
                        <div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}xlx.png" width="25px">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>`
                    }
                    else if(ter == "png" || ter == "jpg" || ter == "webp" || ter == "jpeg" || ter == "webp" || ter == "svg" || ter == "psd"){
                        tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{asset('storage/tickets-replies/${ticket_details.id}/${item}')}}" class="attImg"  alt="" style="width:40px;height:30px !important">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>`
                    }
                    else if(ter == "docs" || ter == "doc" || ter == "txt" || ter == "dotx" || ter == "docx"){
                        tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}word.png" width="25px">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>`
                    }
                    else if(ter == "ppt" || ter == "pptx" || ter == "pot" || ter == "pptm"){
                        tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}pptx.png" width="25px">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>`
                    }
                    else if(ter == "zip"){
                        tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}zip.png" width="25px">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>`
                    }
                    else{
                        tdet+= `<div class="col-md-2" style='position:relative;cursor:pointer;width: 74px;' >
                                    <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;' onclick="showAttachedImage(${ticket_details.id}, '${item}','reply')" >
                                        <div class="card-body body-hover" style="padding: .1rem .1rem !important;background-color:#dfdcdc1f">
                                            <div class="" style="display: -webkit-box">
                                                        <div class="modal-first w-100">
                                                            <div class="mt-0 rounded" >
                                                                <div class="float-start rounded me-1 bg-none" style="">
                                                                    <div class="">
                                                                        <img src="{{request()->root() . '/' . (Session::get('is_live') == 1 ? 'public/default_imgs/' : 'default_imgs/')}}txt.png" width="25px">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                            </div>`
                    }
                        // tdet += `<p><a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" target="_blank">${item}</a></p>`;
                    });

                    tdet += '';
                }

                let user_type = 'Staff';

                if(reply.user_type == 5){
                    user_type = 'User'
                }

                let new_tag = '' ;
                var now = moment( new Date().toLocaleString('en-US', { timeZone: time_zone }));
                // console.log(now)
                var d = new Date(reply.created_at);

                var min = d.getMinutes();
                var dt = d.getDate();
                var d_utc = d.getUTCHours();

                d.setMinutes(min);
                d.setDate(dt);
                d.setUTCHours(d_utc);

                let a = d.toLocaleString("en-US" , {timeZone: time_zone});

                var end = moment(a); // another date
                var duration = moment.duration(now.diff(end));
                var days = duration.asHours();

                // console.log(days + ' sadasdasd' + a)

                if(days <= 1){
                    new_tag = `<span class="badge badge-primary" style="background-color:#4eafcb">New</span>`;
                }

                var replier_img = ``;

                var customer_img = ``;
                var user_img = ``;
                let link = ``;

                if(reply.customer_replies != null) {
                    if(reply.customer_replies.avatar_url != null) {
                        let path = root +'/'+ reply.customer_replies.avatar_url;
                        customer_img += `<img src="${path}" width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;
                    }else{
                        customer_img += `<img src="{{asset('${js_path}default_imgs/customer.png')}}" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                    }

                    // link = `<a href="{{url('customer-profile')}}/${reply.customer_replies.customer_id}"> ${reply.customer_replies.name} </a>`;

                }else{
                    customer_img += `<img src="{{asset('${js_path}default_imgs/customer.png')}}" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                }

                if(reply.reply_user != null) {
                    if(reply.reply_user.profile_pic != null) {
                        let path = root + '/' + reply.reply_user.profile_pic;
                        user_img += `<img src="${path}" style="border-radius: 50%;" class="rounded-circle" width="40px" height="40px" />`;
                    }else{
                        user_img += `<img src="${js_path}default_imgs/customer.png" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                    }

                    // link = `<a href="{{url('profile')}}/${reply.reply_user.id}"> ${reply.reply_user.name} </a>`;

                }else{
                    user_img += `<img src="${js_path}default_imgs/customer.png" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                }

                var content = '';
                // if(reply.type == 'cron'){
                //     content = reply.reply.replace(/<img[^>]*>/g,"");
                // }else{
                    content = reply.reply;
                // }

                if(reply.hasOwnProperty("user_type")) {
                    if(reply.user_type == 5) {
                        if(reply.customer_replies != null) {
                            link = `<a href="{{url('customer-profile')}}/${reply.customer_id}" class="text-body"> ${reply.customer_replies.first_name != null ? reply.customer_replies.first_name : ''} ${reply.customer_replies.last_name !=null ? reply.customer_replies.last_name :  ''} </a>`;
                        }
                    }else{
                        if(reply.reply_user != null) {
                            link = `<a href="{{url('profile')}}/${reply.reply_user.id}" class="text-body"> ${reply.reply_user.name} </a>`;
                        }

                    }
                }else{

                    if(reply.customer_replies != null) {
                        link = `<a href="{{url('customer-profile')}}/${reply.customer_id}"> ${reply.customer_replies.first_name != null ? reply.customer_replies.first_name : ''} ${reply.customer_replies.last_name !=null ? reply.customer_replies.last_name :  ''} </a>`;
                    }

                    if(reply.reply_user != null) {
                        link = `<a href="{{url('profile')}}/${reply.reply_user.id}"> ${reply.reply_user.name} </a>`;
                    }
                }
                var updated_msg = 'Last edited by:'+ reply.updated_by.name + ' On '+convertDate(reply.updated_by.updated_at)
                replies_html =`
                    <li class="media" id="reply__${index}">
                        <span class="mr-3">${reply.customer_replies == null ? user_img : customer_img }</span>
                        <div class="row">

                            <div class="col-md-12">
                            <h5 class="mt-0"><span class="text-primary">
                                ${link}
                                </span>&nbsp;<span class="badge badge-secondary">`+user_type+`</span>&nbsp;
                            &nbsp; <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical font-medium-2 dropdown-toggle" data-bs-toggle="dropdown" type="button" style="position: absolute;right: 21px;"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                            <div class="dropdown-menu dropdown-menu-end" style="">
                                    <a class="dropdown-item" onclick="editReply(${reply.id},'${index}')" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3 me-1"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
                                        <span class="align-middle" >Edit</span>
                                    </a>

                                    <a class="dropdown-item" onclick="deleteReply(${reply.id},${index})" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2 me-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        <span class="align-middle" >Delete</span>
                                    </a>

                                </h5>
                            </div>

                            <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;">
                                `+ reply.updated_by != null ? updated_msg : 'Posted on' + convertDate(reply.created_at) +`
                            </span>
                            <div class="my-1 bor-top reply-htm" id="reply-html-` + reply.id + `"> ${content} </div>
                            </div>

                        </div>

                    </li>
                    <div class="row mt-1" style="word-break: break-all;">
                            ${tdet}
                    </div>
                    <hr>`;

                if (reply.hasOwnProperty('msgno') && reply.msgno) {
                    $('#reply-html-' + reply.id).find('img').attr('width', 120);
                    $('#reply-html-' + reply.id).find('img').attr('height', 120);
                    $('#reply-html-' + reply.id).find('img').css('margin', '0 8px 8px 0');
                }
            }
            $("#ticket-replies").append(replies_html);
            parserReplyEmbeddedImages(`reply__${index}`,`${reply.embed_attachments}`,`${reply.type}`);
        });

        $('.bor-top').find(' p img').css('width','200px !important');

    }else{
        $("#ticket-replies").html("");
    }
}

function deleteReply(id , index) {
    Swal.fire({
        title: 'Do you want to delete?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: "{{url('delete-ticket-reply')}}",
                data: {  id:id },
                dataType: 'json',
                beforeSend: function(data) {
                    // $("#saveBtn").hide();
                    $("#processbtn").show();
                },
                success: function(data) {
                    if (data.status_code == 200 && data.success == true) {
                        alertNotification('success', 'Success' , data.message);
                        $("#reply__"+index).remove();
                    } else {
                        alertNotification('error', 'Error' , data.message);
                    }
                },
                complete: function(data) {
                    // $("#saveBtn").show();
                    $("#processbtn").hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });
        }
    });
}

function publishReply(ele, reply_btn_id , type = 'publish', modal=null) {

    $("."+reply_btn_id).attr('style','display:none !important');

    var content = tinyMCE.editors.mymce.getContent();
    var queue_id = $('#queue_id').val();

    tinyContentEditor(content, 'tickets-replies').then(function() {
        content = $('#tinycontenteditor').html();

        if (!content || content == '<p></p>') {
            // Swal.fire({
            //     position: 'center',
            //     icon: 'error',
            //     title: 'Please type some reply.',
            //     showConfirmButton: false,
            //     timer: swal_message_time
            // });
            $('#reply').css('display', 'block');
            return false;
        } else {
            let fileSizeErr = false;
            $('.replies_attaches').each(function(index) {
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
                    fileData.append('ticket_id', ticket_details.id);
                    fileData.append('fileName', fname);
                    fileData.append('attachment', this.files[0]);
                    fileData.append('module', 'replies');

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
                bcc: $('#bcc_emails').val(),
                ticket_id: ticket.id,
                type: type,
                attachments: rep_attaches,
                reply: content,
                inner_attachments: attachments_src,
                queue_id:queue_id
            };

            if (edit_reply_mode !== false) {
                params.id = ticketReplies[edit_reply_mode].id;
            }
            if(updates_Arr.length > 0){
                params.dd_Arr = updates_Arr;
                params.data_id = '';
                // updateTicket();
            }

            // getting response template data
            if($('#response_template').is(":checked" , true) ) {
                let access = ``;

                if( $("#onlyMe").is(":checked") ) {
                    access = `only_me`;
                }

                if( $("#allStaff").is(":checked") ) {
                    access = `all_staff`;
                }
                params.title = $("#res_title").val();
                params.cat_id = $("#category_name").val();
                params.temp_html = content;
                params.view_access = access;
                params.res = 1;
            }else{
                params.res = 0;
            }

            $.ajax({
                type: "post",
                url: publish_reply_route,
                data: params,
                dataType: 'json',
                enctype: 'multipart/form-data',
                cache: false,
                success: function(data) {

                    let owner = updates_Arr.find(item => item.id == 2);
                    if(owner != null) {
                        $("#assigned_to").val( owner.new_data).trigger("change");
                    }

                    reply_flag = 0;

                    var new_date  = new Date().toLocaleString('en-US', { timeZone: time_zone });
                    new_date =  moment(new_date).format(date_format + ' ' +'hh:mm A');
                    $("#updation-date").html(new_date);
                    $("#compose_btn").show();
                    $('.reply_btns').attr('style', 'display: none !important');

                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();

                    if (data.success == true) {


                        $("#dropD ").find(".select2").hide();
                        $("#dropD ").find("h5").show();

                        let item = updates_Arr.filter(item => item.id === 4);
                        if(item.length != 0) {

                            let close = 'Closed';
                            let result = item[0].new_text.localeCompare(close);
                            if(result == 0) {
                                $("#sla_reply_due").hide();
                                $("#sla_res_due").hide();

                                if(ticket != null) {
                                    ticket.reply_deadline = 'cleared';
                                    ticket.resolution_deadline = 'cleared';
                                }
                            }
                        }

                        $('#tinycontenteditor').html('');

                        for(var i = 0 ; i < updates_Arr.length ; i++){

                            if(updates_Arr[i]['id'] == 1){
                                ticket.dept_id = updates_Arr[i]['new_data'];
                                ticket.department_name  = updates_Arr[i]['new_text'];
                                $('#follow_up_dept_id').val(ticket.dept_id).trigger("change");
                            }else if(updates_Arr[i]['id'] == 2){
                                ticket.assigned_to = updates_Arr[i]['new_data'];
                                ticket.assignee_name = updates_Arr[i]['new_text'];
                                $('#follow_up_assigned_to').val(ticket.assigned_to).trigger("change");
                            }else if(updates_Arr[i]['id'] == 3){

                                ticket.type = updates_Arr[i]['new_data'];
                                ticket.type_name = updates_Arr[i]['new_text'];
                                $('#follow_up_type').val(ticket.type).trigger("change");

                            }else if(updates_Arr[i]['id'] == 4){

                                if(updates_Arr[i]['new_text'] == 'Closed') {
                                    $("#sla_reply_due").hide();
                                    $("#sla_res_due").hide();

                                    if(ticket != null) {
                                        ticket.reply_deadline = 'cleared';
                                        ticket.resolution_deadline = 'cleared';
                                    }

                                }

                                ticket.status = updates_Arr[i]['new_data'];
                                ticket.status_name = updates_Arr[i]['new_text'];
                                // $("#dropD").css('background-color' ,color + ' !important');
                                $('#follow_up_status').val(ticket.status).trigger("change");

                            }else if(updates_Arr[i]['id'] == 5){

                                ticket.priority = updates_Arr[i]['new_data'];
                                ticket.priority_name = updates_Arr[i]['new_text'];
                                // $("#prio-label").css('background-color' ,color + ' !important');
                                $('#follow_up_priority').val(ticket.priority).trigger("change");

                            }

                        }

                        getTicketReplies(ticket.id);

                        // let draft = false;
                        // if (edit_reply_mode !== false) {
                        //     // console.log("here");
                        //     ticketReplies[edit_reply_mode] = data.data;
                        //     ticketReplies[edit_reply_mode].reply = content;
                        //     ticketReplies[edit_reply_mode].attachments = rep_attaches;
                        // } else {
                        //     // console.log("here 1");
                        //     data.data.reply = content;
                        //     data.data.attachments = rep_attaches;
                        //     draft = ticketReplies.push(data.data);
                        // }

                        // listReplies();

                        if (type == 'publish') {
                            tinyMCE.editors.mymce.setContent('');
                            document.getElementById('compose-reply').classList.toggle('d-none');
                            $('#to_mails').tagsinput()[0].removeAll();
                            if(data.hasOwnProperty('sla_updated') && data.sla_updated !== false) {
                                ticket.reply_deadline = data.sla_updated;
                                ticket.sla_rep_deadline_from = moment();
                            }
                            setSlaPlanDeadlines();
                        }

                        alertNotification('success', 'Success' , data.message);
                        $("#responseTemplateForm").trigger("reset");
                        $("#response_template").is(":checked",false);
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    }
                },
                complete:function (data) {
                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();
                },
                error:function(e) {
                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();
                }
            });
        }
    });
}

function composeReply() {

    reply_flag = 1;
    $("#update_ticket").hide();

    $('.reply_btns').attr('style', 'display: block !important');
    $("#compose_btn").hide();
    $(".ticket_reply_btns").show();

    document.getElementById('compose-reply').classList.toggle('d-none');

    $('#replies_attachments').html('');
    ticket_attachments_count = 0;

    for (let i in ticketReplies) {
        if (ticketReplies[i].is_published === 0) {
            editReply(null,i);
            $('#draft-rply').show();
        }
    }
}

function editReply(id,rindex) {
    // publishReply(this)
    $('#editreply').modal('show');
     tinyMCE.editors.mymce_edit.setContent(ticketReplies[rindex].reply);
     edit_reply_id = id
    // if(ticketReplies[rindex].attachments) {
    //     let attchs = ticketReplies[rindex].attachments.split(',');

    //     $('#replies_attachments').html('');
    //     ticket_attachments_count = 0;

    //     attchs.forEach(item => {
    //         addAttachment('replies', item);
    //     });
    // }
        // console.log(ticket.queue_id)
    // $('#draft-rply').hide();
    // if(ticketReplies[rindex].is_published == 1) $('#cancel-rply').show();

    // document.getElementById('compose-reply').classList.remove('d-none');

    // edit_reply_mode = rindex;
}

function update_edit_reply()
{
    var content = tinyMCE.editors.mymce_edit.getContent();

    let params = {
        cc: $('#to_mails').val(),
        bcc: $('#bcc_emails').val(),
        ticket_id: ticket.id,
        type: 'publish',
        reply: content,
        queue_id:ticket.queue_id,
        id:edit_reply_id
    };

    console.log(params)

    $.ajax({
        type: "post",
        url: publish_reply_route,
        data: params,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function(data) {
            if (data.success == true) {

                $('#editreply').modal('hide');
                $('#mymce_edit').html('');

                // for(var i = 0 ; i < updates_Arr.length ; i++){

                //     if(updates_Arr[i]['id'] == 1){
                //         ticket.dept_id = updates_Arr[i]['new_data'];
                //         ticket.department_name  = updates_Arr[i]['new_text'];
                //         $('#follow_up_dept_id').val(ticket.dept_id).trigger("change");
                //     }else if(updates_Arr[i]['id'] == 2){
                //         ticket.assigned_to = updates_Arr[i]['new_data'];
                //         ticket.assignee_name = updates_Arr[i]['new_text'];
                //         $('#follow_up_assigned_to').val(ticket.assigned_to).trigger("change");
                //     }else if(updates_Arr[i]['id'] == 3){

                //         ticket.type = updates_Arr[i]['new_data'];
                //         ticket.type_name = updates_Arr[i]['new_text'];
                //         $('#follow_up_type').val(ticket.type).trigger("change");

                //     }else if(updates_Arr[i]['id'] == 4){

                //         if(updates_Arr[i]['new_text'] == 'Closed') {
                //             $("#sla_reply_due").hide();
                //             $("#sla_res_due").hide();

                //             if(ticket != null) {
                //                 ticket.reply_deadline = 'cleared';
                //                 ticket.resolution_deadline = 'cleared';
                //             }

                //         }

                //         ticket.status = updates_Arr[i]['new_data'];
                //         ticket.status_name = updates_Arr[i]['new_text'];
                //         // $("#dropD").css('background-color' ,color + ' !important');
                //         $('#follow_up_status').val(ticket.status).trigger("change");

                //     }else if(updates_Arr[i]['id'] == 5){

                //         ticket.priority = updates_Arr[i]['new_data'];
                //         ticket.priority_name = updates_Arr[i]['new_text'];
                //         // $("#prio-label").css('background-color' ,color + ' !important');
                //         $('#follow_up_priority').val(ticket.priority).trigger("change");

                //     }

                // }

                getTicketReplies(ticket.id);
                tinyMCE.editors.mymce_edit.setContent('');
                alertNotification('success', 'Success' , data.message);
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        complete:function (data) {

        },
        error:function(e) {

        }
    });
}

function cancelReply() {

    reply_flag = 0;

    var priority =  $("#prio-label").find(".select2 option:selected").text();
    var dep =  $("#dep-label").find(".select2 option:selected").text();
    var tech =  $("#tech-label").find(".select2 option:selected").text();
    var status =  $("#status-label").find(".select2 option:selected").text();
    var type =  $("#type-label").find(".select2 option:selected").text();

    let obj = {
        "pro" : priority ,
        "dep" : dep ,
        "tech" : tech ,
        "status" : status ,
        "type" : type ,
    }
    console.log(obj , "obj");

    if(updates_Arr.length != 0) {
        $("#update_ticket").show();
    }else{
        $("#update_ticket").hide();
    }

    edit_reply_mode = false;

    document.getElementById('compose-reply').classList.add('d-none');

    tinyMCE.editors.mymce.setContent('');

    // $('#cancel-rply').hide();
    // $('#draft-rply').show();


    $('.reply_btns').attr('style', 'display: none !important');
    $("#compose_btn").show();

    listReplies();
}

function getDeptStatuses(value) {

    showDepartStatus(value , 'followup');
}

$('#dept_id').on('select2:selecting', function(e) {
    console.log('Selecting: ' , e.params.args.data);

    var dept_id = e.params.args.data.id;

    // no dept change to do update
    if (dept_id == ticket.dept_id){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 1;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        showDepartStatus(dept_id , 'nochange');
        $('#dept_id').val(dept_id).trigger("change");

        return false;
    }
    console.log(update_flag , "update_flag");
    update_flag++;
    var obj = {};
    obj = {
        id:1,
        data: ticket.department_name, // Saving old value to show in email notification
        new_data:dept_id,
        new_text:e.params.args.data.text
    }

    let item = updates_Arr.filter(item => item.id === 1);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(1);
    if(item.length > 0) {
        updates_Arr[index].id = 1;
        updates_Arr[index].data = ticket.department_name ;
        updates_Arr[index].new_data = dept_id ;
        updates_Arr[index].new_text = e.params.args.data.text;
    }else{
        updates_Arr.push(obj);
    }

    console.log(updates_Arr , "updates_Arr department_name");

    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

    showDepartStatus(dept_id , 'nochange');

});
// $('#dept_id').change(function() {
//     var dept_id = $(this).val();

//     // no dept change to do update
//     if (dept_id == ticket.dept_id){
//         updates_Arr = $.grep(updates_Arr, function(e){
//             return e.id != 1;
//         });
//         if(update_flag > 0){
//             update_flag--;
//             if(update_flag == 0){
//                 $("#update_ticket").css("display", "none");
//             }
//         }
//         return false;
//     }
//     console.log(update_flag , "update_flag");
//     update_flag++;
//     var obj = {};
//     obj = {
//         id:1,
//         data: ticket.department_name, // Saving old value to show in email notification
//         new_data:dept_id,
//         new_text:$("#dept_id option:selected").text()
//     }

//     let item = updates_Arr.filter(item => item.id === 1);
//     let index = updates_Arr.map(function (item) { return item.id; }).indexOf(1);
//     if(item.length > 0) {
//         updates_Arr[index].id = 1;
//         updates_Arr[index].data = ticket.department_name ;
//         updates_Arr[index].new_data = dept_id ;
//         updates_Arr[index].new_text = $("#dept_id option:selected").text();
//     }else{
//         updates_Arr.push(obj);
//     }

//     console.log(updates_Arr , "updates_Arr department_name");

//     if(reply_flag == 0) {
//         $("#update_ticket").css("display", "block");
//     }

//     showDepartStatus(dept_id , 'nochange');
// });
$('#assigned_to').on('select2:selecting', function(e) {

    // var assigned_to = $(this).val() ? $(this).val() : null;
    console.log('Selecting owner: ' , e.params.args.data);

    var assigned_to = e.params.args.data.id;
    // no change to do update
    if (assigned_to == ticket.assigned_to){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 2;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        $('#assigned_to').val(assigned_to).trigger("change");

        return false;
    }
    console.log(update_flag , "update_flag");

    update_flag++;
    var obj = {};
    obj = {
        id:2,
        data: ticket.assignee_name, // Saving old value to show in email notification
        new_data:assigned_to,
        new_text:e.params.args.data.text
    }

    let item = updates_Arr.filter(item => item.id === 2);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(2);
    if(item.length > 0) {
        updates_Arr[index].id = 2;
        updates_Arr[index].data = ticket.assignee_name ;
        updates_Arr[index].new_data = assigned_to ;
        updates_Arr[index].new_text = e.params.args.data.text;
    }else{
        updates_Arr.push(obj);
    }

    console.log(updates_Arr , "updates_Arr assignee_name");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

});

$('#assigned_to').change(function() {
    var assigned_to = $(this).val() ? $(this).val() : null;

    // no change to do update
    if (assigned_to == ticket.assigned_to){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 2;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        return false;
    }
    console.log(update_flag , "update_flag");

    update_flag++;
    var obj = {};
    obj = {
        id:2,
        data: ticket.assignee_name, // Saving old value to show in email notification
        new_data:assigned_to,
        new_text:$("#assigned_to option:selected").text()
    }

    let item = updates_Arr.filter(item => item.id === 2);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(2);
    if(item.length > 0) {
        updates_Arr[index].id = 2;
        updates_Arr[index].data = ticket.assignee_name ;
        updates_Arr[index].new_data = assigned_to ;
        updates_Arr[index].new_text = $("#assigned_to option:selected").text();
    }else{
        updates_Arr.push(obj);
    }

    console.log(updates_Arr , "updates_Arr assignee_name");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

});
$('#type').on('select2:selecting', function(e) {

    // var type = $(this).val();
    console.log('Selecting type: ' , e.params.args.data);

    var type = e.params.args.data.id;
    // no change to do update
    if (type == ticket.type){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 3;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        $('#type').val(type).trigger("change");

        return false;
    }
    console.log(update_flag , "update_flag");
    update_flag++;
    var obj = {};
    obj = {
        id:3,
        data: ticket.type_name, // Saving old value to show in email notification
        new_data:type,
        new_text:e.params.args.data.text

    }

    let item = updates_Arr.filter(item => item.id === 3);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(3);
    if(item.length > 0) {
        updates_Arr[index].id = 3;
        updates_Arr[index].data = ticket.type_name ;
        updates_Arr[index].new_data = type ;
        updates_Arr[index].new_text = e.params.args.data.text;
    }else{
        updates_Arr.push(obj);
    }

    console.log(updates_Arr , "updates_Arr type");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

});
$('#type').change(function() {
    var type = $(this).val();
    // no change to do update
    if (type == ticket.type){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 3;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        return false;
    }
    console.log(update_flag , "update_flag");
    update_flag++;
    var obj = {};
    obj = {
        id:3,
        data: ticket.type_name, // Saving old value to show in email notification
        new_data:type,
        new_text:$("#type option:selected").text()

    }

    let item = updates_Arr.filter(item => item.id === 3);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(3);
    if(item.length > 0) {
        updates_Arr[index].id = 3;
        updates_Arr[index].data = ticket.type_name ;
        updates_Arr[index].new_data = type ;
        updates_Arr[index].new_text = $("#type option:selected").text();
    }else{
        updates_Arr.push(obj);
    }

    console.log(updates_Arr , "updates_Arr type");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }


});
$('#status').on('select2:selecting', function(e) {

    // var status = $(this).val();
    console.log('Selecting type: ' , e.params.args.data);
    var status = e.params.args.data.id;

    var color = $('#status option:selected').data('color');
    $('.drop-dpt').attr('style', 'background-color: ' + color + ' !important');

    // no change to do update
    if (status == ticket.status) {
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 4;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        $('#status').val(status).trigger("change");

        return false;
    }
    console.log(update_flag , "update_flag");
    update_flag++;
    var obj = {};
    obj = {
        id:4,
        data: ticket.status_name, // Saving old value to show in email notification
        new_data:status,
        new_text:e.params.args.data.text

    }

    let item = updates_Arr.filter(item => item.id === 4);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(4);
    if(item.length > 0) {
        updates_Arr[index].id = 4;
        updates_Arr[index].data = ticket.status_name ;
        updates_Arr[index].new_data = status ;
        updates_Arr[index].new_text = e.params.args.data.text;
    }else{
        updates_Arr.push(obj);
    }
    console.log(updates_Arr , "updates_Arr status");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

});
$('#status').change(function() {
    var status = $(this).val();
    var color = $('#status option:selected').data('color');
    $('.drop-dpt').attr('style', 'background-color: ' + color + ' !important');

    // no change to do update
    if (status == ticket.status) {
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 4;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        return false;
    }
    console.log(update_flag , "update_flag");
    update_flag++;
    var obj = {};
    obj = {
        id:4,
        data: ticket.status_name, // Saving old value to show in email notification
        new_data:status,
        new_text:$("#status option:selected").text()

    }

    let item = updates_Arr.filter(item => item.id === 4);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(4);
    if(item.length > 0) {
        updates_Arr[index].id = 4;
        updates_Arr[index].data = ticket.status_name ;
        updates_Arr[index].new_data = status ;
        updates_Arr[index].new_text = $("#status option:selected").text();
    }else{
        updates_Arr.push(obj);
    }
    console.log(updates_Arr , "updates_Arr status");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

});

$('#priority').on('select2:selecting', function(e) {

    console.log('Selecting type: ' , e.params.args.data);
    var priority = e.params.args.data.id;

    var color = $('#priority option:selected').data('color');
    $('#prio-label').css('background-color',color);
    // no change to do update
    if (priority == ticket.priority){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 5;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        return false;
    }

    console.log(update_flag , "update_flag");

    update_flag++;
    var obj = {};
    obj = {
        id:5,
        data: ticket.priority_name, // Saving old value to show in email notification
        new_data:priority,
        new_text:e.params.args.data.text

    }

    let item = updates_Arr.filter(item => item.id === 5);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(5);
    if(item.length > 0) {
        updates_Arr[index].id = 5;
        updates_Arr[index].data = ticket.priority_name ;
        updates_Arr[index].new_data = priority ;
        updates_Arr[index].new_text = e.params.args.data.text;
    }else{
        updates_Arr.push(obj);
    }
    console.log(updates_Arr , "updates_Arr priority");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }

});

$('#priority').change(function() {
    var priority = $(this).val();
    var color = $('#priority option:selected').data('color');
    $('#prio-label').css('background-color',color);
    // no change to do update
    if (priority == ticket.priority){
        updates_Arr = $.grep(updates_Arr, function(e){
            return e.id != 5;
        });
        if(update_flag > 0){
            update_flag--;
            if(update_flag == 0){
                $("#update_ticket").css("display", "none");
            }
        }
        return false;
    }

    console.log(update_flag , "update_flag");

    update_flag++;
    var obj = {};
    obj = {
        id:5,
        data: ticket.priority_name, // Saving old value to show in email notification
        new_data:priority,
        new_text:$("#priority option:selected").text()

    }

    let item = updates_Arr.filter(item => item.id === 5);
    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(5);
    if(item.length > 0) {
        updates_Arr[index].id = 5;
        updates_Arr[index].data = ticket.priority_name ;
        updates_Arr[index].new_data = priority ;
        updates_Arr[index].new_text = $("#priority option:selected").text();
    }else{
        updates_Arr.push(obj);
    }
    console.log(updates_Arr , "updates_Arr priority");
    if(reply_flag == 0) {
        $("#update_ticket").css("display", "block");
    }
});

function updateTicket(){
    if(updates_Arr.length == 0){
        alertNotification('warning', 'Warning' , 'There is nothing to update');
        return false;
    }
    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {

            // priority: priority,
            id: ticket.id,
            dd_Arr:updates_Arr,
            action: 'ticket_detail_update',
            queue_id : $('#queue_id').val()
            // action_performed: 'Ticket Priority'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            // console.log(data)
            if (data.success == true) {
                all_users = data.allusers;
                userlist = [];
                all_users.forEach(element => {
                    userlist.push(element.name + ' (' + element.email + ')');
                });

                let closeStatus = updates_Arr.find(item => item.new_text == 'Closed');
                if(closeStatus != null) {
                    let closetkt = $('.closeCounter').text();
                    $('.closeCounter').text( parseInt(closetkt) + 1 );

                    let opencounter = $('.openCounter').text();

                    if(opencounter != 0) {
                        $('.openCounter').text( parseInt(opencounter) - 1 );
                    }
                }

                for(var i = 0 ; i < updates_Arr.length ; i++){

                    if(updates_Arr[i]['id'] == 1){
                        ticket.dept_id = updates_Arr[i]['new_data'];
                        ticket.department_name = updates_Arr[i]['new_text'];
                        $('#follow_up_dept_id').val(ticket.dept_id).trigger("change");
                    }else if(updates_Arr[i]['id'] == 2){
                        ticket.assigned_to = updates_Arr[i]['new_data'];
                        ticket.assignee_name = updates_Arr[i]['new_text'];
                        $('#follow_up_assigned_to').val(ticket.assigned_to).trigger("change");
                    }else if(updates_Arr[i]['id'] == 3){

                        ticket.type = updates_Arr[i]['new_data'];
                        ticket.type_name = updates_Arr[i]['new_text'];
                        $('#follow_up_type').val(ticket.type).trigger("change");

                    }else if(updates_Arr[i]['id'] == 4){

                        if(updates_Arr[i]['new_text'] == 'Closed') {
                            $("#sla_reply_due").hide();
                            $("#sla_res_due").hide();

                            if(ticket != null) {
                                ticket.reply_deadline = 'cleared';
                                ticket.resolution_deadline = 'cleared';
                            }

                        }

                        ticket.status = updates_Arr[i]['new_data'];
                        ticket.status_name = updates_Arr[i]['new_text'];
                        // $("#dropD").css('background-color' ,color + ' !important');
                        $('#follow_up_status').val(ticket.status).trigger("change");

                    }else if(updates_Arr[i]['id'] == 5){

                        ticket.priority = updates_Arr[i]['new_data'];
                        ticket.priority_name = updates_Arr[i]['new_text'];
                        // $("#prio-label").css('background-color' ,color + ' !important');
                        $('#follow_up_priority').val(ticket.priority).trigger("change");

                    }

                    console.log(updates_Arr , "updates_Arr");

                }
                updateTicketDate();


                $('#note').atwho({
                    at: "@",
                    data:userlist,
                });
                // // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Ticket Updated','', updates_Arr);
                updates_Arr = [];

                // // refresh logs
                getLatestLogs();
                $("#dropD ").find(".select2").hide();
                $("#dropD ").find("h5").show();
                selectD();
                $("#update_ticket").hide();
                alertNotification('success', 'Success' , 'TIcket Updated Successfully!');
            }
        }
    });

}

function getTicketFollowUp() {
    $.ajax({
        type: "get",
        url: ticket_followup_route + "/" + ticket.id,
        success: function(data) {
            if (data.success == true) {
                let obj = data.data;
                let follow_up_count  = 0;

                for(let i = 0; i < obj.length; i++) {

                    if(obj[i].passed == 0) {
                        if(obj[i].follow_up_logs != null) {
                            if(obj[i].follow_up_logs.passed == 1) {
                                follow_up_count += 0;
                            }else{
                                follow_up_count  += 1;
                            }
                        }else{
                            follow_up_count += 1;
                        }
                    }else{
                        follow_up_count = obj.length;
                    }
                }

                if(follow_up_count  != 0) {
                    $('.followup_count').text(follow_up_count);
                    $('.followup_count').addClass('badge badge-light-danger rounded-pill mx-1');
                }else{
                    $('.followup_count').text('');
                    $('.followup_count').removeClass('badge badge-light-danger rounded-pill mx-1');
                }


                g_followUps = obj;

                // if(obj.length > 0)  {

                //     for(var i =0 ; i < obj.length; i++) {

                //         if(obj[i].follow_up_reply != null) {
                //             publishTicketReply(obj[i].follow_up_reply);
                //         }
                //     }

                // }
                // console.log(g_followUps , "g_followUps");

                // clear followups refresh timer
                if (g_listFlupsTimer) clearTimeout(g_listFlupsTimer);

                listFollowups();
            }
        }
    });
}

function listFollowups() {

    $('#clockdiv').html('');
    if (g_followUps.length < 1) return;
    console.log("test");
    // clear follow up time outs
    if (g_followUp_timeouts.length) {
        for (let i in g_followUp_timeouts) {
            clearTimeout(g_followUp_timeouts[i]);
        }
    }

    var flups = "";
    let countFlups = 0;

    let formData = [];

    let ticketNotes = false;
    let ticket_replies = false;

    for (var i = 0; i < g_followUps.length; i++) {
        let details = '';
        let autho = '';
        if (g_followUps[i].created_by = $("#loggedInUser").val()) {
            autho = `<span class="fa fa-trash" onclick="deleteFollowup(${g_followUps[i].id})" style="cursor: pointer;"></span>`;
        }

        if (g_followUps[i].department_name) details += `<li>Will move the ticket to ${g_followUps[i].department_name}</li>`;
        if (g_followUps[i].tech_name) details += `<li>Will assign the ticket to ${g_followUps[i].tech_name}</li>`;
        if (g_followUps[i].type_name) details += `<li>Will change the ticket type to ${g_followUps[i].type_name}</li>`;
        if (g_followUps[i].status_name) details += `<li>Will change the status to ${g_followUps[i].status_name}</li>`;
        if (g_followUps[i].priority_name) details += `<li>Will change the priority to ${g_followUps[i].priority_name}</li>`;
        if (g_followUps[i].project_name) details += `<li>Will move the ticket to ${g_followUps[i].project_name}</li>`;
        if (g_followUps[i].follow_up_notes) {
            details += `<li>Will add a ticket note</li>`;
            ticketNotes = true;
        }
        if(g_followUps[i].follow_up_reply) {
            ticket_replies = true;
        }

        if (details) details = `<div class="card-body"><ul>${details}</ul></div>`;

        let followUpDate = '';
        let remTime = '';
        let timediff = 0;
        let valid_date = false;
        let idata = {};

        if (g_followUps[i].is_recurring == 1) {

            let currTime = new Date().toLocaleString('en-US', { timeZone: time_zone });
            // console.log(g_followUps[i].date , "g_followUps[i].date");
            // followUpDate = moment(moment.utc(g_followUps[i].date).toDate()).local();
            followUpDate = moment(new Date(g_followUps[i].date).toLocaleString('en-US', { timeZone: time_zone }));


            if (g_followUps[i].schedule_type == 'time' && g_followUps[i].recurrence_time) {
                let rec_time = g_followUps[i].recurrence_time.split(':');
                followUpDate.set('hour', rec_time[0]);
                followUpDate.set('minute', rec_time[1]);
            }

            timediff = followUpDate.diff( moment(currTime) , 'seconds');
            console.log(timediff , "timediff");
            let remainTime = momentDiff(followUpDate ,  moment(currTime));

            if (timediff < 0) idata.ticket_update = true;
            // else remTime = getClockTime(followUpDate, timediff);
            else remTime = remainTime;

            if (remTime) valid_date = true;
        } else {
            if ((g_followUps[i].schedule_time || g_followUps[i].custom_date) && g_followUps[i].schedule_type != 'time') {
                if (g_followUps[i].schedule_type == 'custom') {
                    followUpDate = moment.utc(g_followUps[i].custom_date).toDate();
                    followUpDate = moment(followUpDate).local();
                } else {
                    followUpDate = moment.utc(g_followUps[i].created_at).toDate();
                    followUpDate = moment(followUpDate).local();
                    followUpDate.add(g_followUps[i].schedule_time, g_followUps[i].schedule_type);
                }

                let timediff = moment(followUpDate).diff(moment(), 'seconds');
                if (timediff < 0) {
                    idata.passed = 1;
                    idata.is_recurring = g_followUps[i].is_recurring;
                }
                else remTime = getClockTime(followUpDate, timediff);

                if (remTime) valid_date = true;
            }
        }

        if (valid_date) {
            let clsp = '';

            if (prv_clicked != 'collapse-' + g_followUps[i].id) clsp = 'collapsed';

            flups += `<div class="card-header mb-1" id="followup-${g_followUps[i].id}" style="color:black;background-color: rgba(0, 0, 0, .113);">
                <h5 class="m-0">
                <div class="d-flex justify-content-between">
                    <a class="custom-accordion-title d-flex align-items-center ${clsp}" data-bs-toggle="collapse" href="#collapse-${g_followUps[i].id}" aria-expanded="${clsp ? false : true}" aria-controls="collapseThree" style="color: inherit;">
                        ${countFlups+1}. Will run a follow-up at ${moment(followUpDate).format(date_format)} created by ${g_followUps[i].creator_name} ${remTime}</strong>&nbsp;
                        ` + autho + `
                            <span class="ml-auto"><i class="fas fa-chevron-down accordion-arrow"></i></span>

                    </a>
                    <button onclick="deleteFollowup(${g_followUps[i].id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                    <i class="fa fa-trash"></i></button>
                </div>
                </h5>
            </div>
            <div id="collapse-${g_followUps[i].id}" class="${clsp ? 'collapse' : 'show'}  flpcollapse" aria-labelledby="followup-${g_followUps[i].id}" data-bs-parent="#accordion">
                <div class="">
                    ${details}
                </div>
            </div>`;

            countFlups++;
        }

        if (Object.keys(idata).length) {
            idata.id = g_followUps[i].id;
            formData.push(idata);
            check_followup.push({id : g_followUps[i].id});
        }
    }
    $('#clockdiv').html(flups);

    if (countFlups) {
        $('#v-pills-followup-tab').html('Follow Ups <span class="badge badge-light">' + countFlups + '</span>');
        // 10 seconds refresh timer
        g_listFlupsTimer = setTimeout(function() {
            listFollowups();
        }, (10000));
    } else {
        $('#v-pills-followup-tab').html('Follow Ups');
        if (g_listFlupsTimer) clearTimeout(g_listFlupsTimer);
    }

    if (formData.length) {
        let form = new FormData();
        form.append('data', JSON.stringify(formData));
        form.append('ticket_id', ticket.id);
        // executeFollowUps(check_followup);
        updateFollowUp(form, ticketNotes , ticket_replies );
    }
}

function executeFollowUps(check_followup) {
    // if(data.length > 0) {
    let item = g_followUps.find( item => item.id === check_followup[0].id );
    if(item != null) {

        let depid = item.follow_up_dept_id;
        let assgto = item.follow_up_assigned_to;
        let type = item.follow_up_type;
        let status = item.follow_up_status;
        let prio = item.follow_up_priority;

        ticket.dept_id = depid;
        ticket.assigned_to = assgto;
        ticket.type = type;
        ticket.status = status;
        ticket.priority = prio;

        $('#dept_id').val( depid ).trigger('change');
        $('#assigned_to').val( assgto ).trigger('change');
        $('#type').val( type ).trigger('change');
        $('#status').val( status ).trigger('change');
        $('#priority').val( prio ).trigger('change');

        let is_live = "{{Session::get('is_live')}}";
        let path = is_live == 0 ? '' : 'public/';

        if(item.follow_up_notes != null) {

            let notes_html = ``;
            let n_type = '';
            let user_img = ``;


            if( "{{auth()->user()->profile_pic}}" != null) {
                user_img += `<img src="{{ asset( request()->root() .'/'. auth()->user()->profile_pic)}}"
                width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;
            }else{
                user_img += `<img src="${path}default_imgs/customer.png"
                        width="40px" height="40px" style="border-radius: 50%;" class="rounded-circle" />`;
            }

            if(item.follow_up_notes_type == 'Ticket') {
                n_type = '<i class="fas fa-clipboard-list" data-bs-toggle="tooltip" data-placement="top" title="Ticket"></i>';
            }else if(item.follow_up_notes_type == 'User') {
                n_type = '<i class="fas fa-user" data-bs-toggle="tooltip" data-placement="top" title="User"></i>';
            }else{
                n_type = '<i class="far fa-building" data-bs-toggle="tooltip" data-placement="top" title="Organization"></i>';
            }


            notes_html = `
            <div class="col-12 rounded p-2 my-1 d-flex" id="note-div-${item.id}" style="background-color:${item.follow_up_notes_color != null ? item.follow_up_notes_color : 'rgb(255, 230, 177)'}">
                <div style="margin-right: 10px; margin-left: -8px;">
                    ${user_img}
                </div>
                <div class="w-100">
                    <div class="d-flex justify-content-between">
                        <h5 class="note-head" style="margin-top:10px"> <strong> ${item.creator_name} </strong> on <span class="small"> ${jsTimeZone(item.created_at)} </span>  ${n_type} </h5>
                    </div>
                    <p class="col" style="word-break:break-all">
                        ${item.follow_up_notes != null ? item.follow_up_notes : ''}
                    </p>
                </div>
            </div>
            `
            $('#v-pills-notes-list').prepend(notes_html);
        }

        if(item.follow_up_reply != null || item.follow_up_reply != '<p></p>') {

            $('#sla-rep_due').parent().addClass('d-none');

            let user_img = ``;
            if( "{{auth()->user()->profile_pic}}" != null) {
                user_img += `<img src="{{ asset( request()->root() .'/'. auth()->user()->profile_pic)}}"
                width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;
            }else{
                user_img += `<img src="{{asset('${path}default_imgs/customer.png')}}"
                        width="40px" height="40px" style="border-radius: 50%;" class="rounded-circle" />`;
            }

            let user_type = 'Staff';

            let reply_html = `
            <li class="media" id="reply__${item.id}">
                <span class="mr-3">${user_img }</span>
                <div class="">

                    <h5 class="mt-0"><span class="text-primary">
                    <a href="{{url('profile')}}/{{auth()->user()->id}}"> {{auth()->user()->name}} </a>
                        </span>&nbsp;<span class="badge badge-secondary">`+user_type+`</span>&nbsp; <br>

                    <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;">Posted on ` + convertDate(item.created_at) + `</span>
                    <div class="my-1 bor-top reply-htm" id="reply-html-` + item.id + `"> ${item.follow_up_reply} </div>
                </div>
            </li>
            <hr>`;


            $('#ticket-replies1').prepend(reply_html);
        }

    }

    setTimeout(() => {
        get_ticket_notes();
        getTicketReplies(ticket.id);
    }, 40000);
}

function getClockTime(followUpDate, timediff) {
    if (timediff >= 0) {
        let today = new Date();
        let remTime = '';
        followUpDate = new Date(Date.parse(new Date()) + (followUpDate - today));
        let rem = getTimeRemaining(followUpDate);

        if (rem && rem.hasOwnProperty('years') && rem.years > 0) remTime += rem.years + 'y ';
        if (rem && rem.hasOwnProperty('months') && rem.months > 0) remTime += rem.months + 'm ';
        if (rem && rem.hasOwnProperty('days') && rem.days > 0) remTime += rem.days + 'd ';
        if (rem && rem.hasOwnProperty('hours') && rem.hours > 0) remTime += rem.hours + 'h ';
        if (rem && rem.hasOwnProperty('minutes') && rem.minutes > 0) remTime += rem.minutes + 'm';


        let color = ``;
        if(remTime.includes('d')) {
            color = `#8BB467`;
        }else if(remTime.includes('h')) {
            color = `#5c83b4`;
        }else if(remTime.includes('m')) {
            color = `#ff8c5a`;
        }


        remTime = `(<span style="color: ${color}">${remTime}</span>)`;

        return remTime;
    }
}

function updateFollowUp(data, ticketNotes = false , ticket_replies = false) {
    $.ajax({
        type: 'POST',
        url: "{{route('followup.logs')}}",
        data: data,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            console.log(data);
            if(data.status_code == 200 && data.success == true) {
                console.log("success");

                let fw_cout = $('.followup_count').text();
                $('.followup_count').text( (fw_cout - 1) );

                ticket.dept_id = data.ticket.dept_id;
                ticket.assigned_to = data.ticket.assigned_to;
                ticket.type = data.ticket.type;
                ticket.status = data.ticket.status;
                ticket.priority = data.ticket.priority;

                $('#dept_id').val(data.ticket.dept_id).trigger('change');
                $('#assigned_to').val(data.ticket.assigned_to).trigger('change');
                $('#type').val(data.ticket.type).trigger('change');
                $('#status').val(data.ticket.status).trigger('change');
                $('#priority').val(data.ticket.priority).trigger('change');

                // send mail notification regarding ticket action
                // ticket_notify('ticket_update', 'Follow-up updated','',updates_Arr);

                // refresh logs
                getLatestLogs();

                getTicketFollowUp();
                // get_ticket_notes();
                // getTicketReplies(ticket.id)

                if(ticket_replies) getTicketReplies(ticket.id);
                if (ticketNotes) get_ticket_notes();

            }else{
                console.log("failed")
                alertNotification('error', 'Error' , data.message);
            }
        }
    });
}

$(document).on('shown.bs.collapse', '.flpcollapse', function() {
    prv_clicked = this.id;
});

function deleteFollowup(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this follow-up will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "post",
                url: delete_followup_route + "/" + id,
                success: function(data) {
                    if (data.success) {

                        alertNotification('success', 'Success' , data.message);

                        // $("#followup-"+id).remove();
                        // send mail notification regarding ticket action
                        ticket_notify('ticket_update', 'Follow-up removed');

                        // refresh logs
                        getLatestLogs();

                        getTicketFollowUp();
                    }
                }
            });
        }
    });
}

function showdetails() {
    if ($('#date_picker_div').css('display') === 'block') {
        $('#date_picker_div').css('display', 'none')
        $('#general_div').css('display', 'none')
        $('#notes_div').css('display', 'none')
        $('#general_details_div').css('display', 'none')
        $('#ticket_follow_notes').css('display', 'none')
        $('input:checkbox[id=general]').attr('checked', false);
        $('#add_follow_up_btn').css('display', 'none')
    } else {
        $('#date_picker_div').css('display', 'block')
        $('#general_div').css('display', 'block')
        $('#notes_div').css('display', 'block')
        $('#add_follow_up_btn').css('display', 'block')
    }
}

$('#notes').change(function() {
    if ($(this).is(":checked")) {
        $('#ticket_follow_notes').css('display', 'block')
    } else {
        $('#ticket_follow_notes').css('display', 'none')
    }
});

$('#general').change(function() {
    if ($(this).is(":checked")) {
        $('#general_details_div').css('display', 'block')
    } else {
        $('#general_details_div').css('display', 'none')
    }
});

$('#fu_post_reply').change(function() {
    if ($(this).is(":checked")) {
        $('#fu_post_reply_ttar_div').css('display', 'block')
    } else {
        $('#fu_post_reply_ttar_div').css('display', 'none')
    }
});

$('#is_recurring').click(function() {

    if ($(this).is(":checked")) {

        $('#recurrence-range').show();
        $('#followup-recurrence').show();

        // $('#start-range').show();
        $("#schedule_type").val("time").trigger("change");

    } else {
        $('#followup-recurrence').css('display', 'none');

        $('#recurrence-range').hide();
        $('#followup-recurrence').hide();

        $("#schedule_type").val("minutes").trigger("change");
    }
});

$('input[name="recur_type"]').change(function() {
    $('.recur-container').hide();

    switch (this.value) {
        case 'daily':
            $('#daily-container').show();
            break;
        case 'weekly':
            $('#weekly-container').show();
            break;
        case 'monthly':
            $('#monthly-container').show();
            break;
        case 'yearly':
            $('#yearly-container').show();
            break;
        default:
            break;
    }
});

$('input[name="recurrence_start"]').change(function() {
    $('#recur-start-date').attr('disabled', true);

    if ($('input[name="recurrence_start"]:checked').val() == 'date') {
        $('#recur-start-date').attr('disabled', false);
    }
});

$('input[name="recurrence_end"]').change(function() {
    $('#recur-end-date').attr('disabled', true);
    $('#end-after-occur').attr('disabled', true);

    if ($('input[name="recurrence_end"]:checked').val() == 'date') {
        $('#recur-end-date').attr('disabled', false);
    } else if ($('input[name="recurrence_end"]:checked').val() == 'count') {
        $('#end-after-occur').attr('disabled', false);
    }
});

function showDateTimeDiv(value) {
    document.getElementById('date_picker_div').style.display = 'none';
    // document.getElementById('recurrence_time_div').style.display = 'none';
    document.getElementById('schedule_time_div').style.display = 'none';

    $('#is_recurring').attr('disabled', false);

    if (value == 'custom') {
        document.getElementById('date_picker_div').style.display = 'block';

        $('#is_recurring').prop('checked' , false);
        $('#followup-recurrence').hide();
        $('#recurrence-range').hide();

        if ($('#is_recurring').prop('checked')) {
            $('#followup-recurrence').css('display', 'none');
            $('#recurrence-range').show();
            $('#start-range').hide();
        }
    } else if (value == 'time') {
        // document.getElementById('recurrence_time_div').style.display = 'block';

        $('#is_recurring').prop('checked', true);
        $('#followup-recurrence').show();
        $('#recurrence-range').show();
        // $('#is_recurring').trigger('change');
        // $('#is_recurring').attr('disabled', true);
    } else {
        document.getElementById('schedule_time_div').style.display = 'block';

        $('#is_recurring').prop('checked' , false);
        $('#followup-recurrence').hide();
        $('#recurrence-range').hide();


        if ($('#is_recurring').prop('checked')) {
            $('#followup-recurrence').css('display', 'none');
            $('#recurrence-range').show();
            $('#start-range').show();
        }
    }
}

function createFollowUp(event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    formData.append('ticket_id', ticket.id);

    let timeType = $('#schedule_type').val();
    if (timeType != 'time') {
        if (!$('#custom_date').val() && !$('#schedule_time').val()) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'No Follow up Time or Date Provided!',
                showConfirmButton: false,
                timer: swal_message_time
            });
            return false;
        }

        if (timeType == 'custom') {
            formData.append('custom_date', moment($('#custom_date').val()).utc().format('YYYY-MM-DD HH:mm'));
        }
    } else {
        // show error for non recurring entry for time
        if (!$('#is_recurring').prop('checked')) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Please select recurrence pattern!',
                showConfirmButton: false,
                timer: swal_message_time
            })
            return false;
        }
    }


    if ($('#is_recurring').prop('checked')) {
        formData.append('is_recurring', 1);

        if ($('#schedule_type').val() == 'time') {
            let recurrence_pattern = '';

            let recur_type = $('input[name="recur_type"]:checked').val();

            if (!$('#recurrence_time').val()) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Please enter recurrence time!',
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                return false;
            }

            if (recur_type) {
                switch (recur_type) {
                    case 'daily':
                        recurrence_pattern = 'daily|' + $('#daily-container').find('#recur_after_d').val();
                        break;
                    case 'weekly':
                        if (!$('#week-days-list').find('input[type="checkbox"]:checked').length) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Please choose recurrence days!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                            return false;
                        }
                        let weekdays = [];
                        $('#week-days-list').find('input[type="checkbox"]:checked').each(function() {
                            weekdays.push(this.value);
                        });
                        recurrence_pattern = 'weekly|' + $('#weekly-container').find('#recur_after_w').val() + '|' + weekdays.toString();
                        break;
                    case 'monthly':
                        let m_day = $('#monthly-container').find('#recur_after_m').val();
                        let m_val = $('#monthly-container').find('#recur_after_month').val();

                        let mt_days = moment().set('month', m_val - 1).daysInMonth();

                        if (m_day < 1 || m_day > mt_days) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Please enter day between (1 and ' + mt_days + ')!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                            return false;
                        }

                        recurrence_pattern = 'monthly|' + m_val + '|' + m_day;
                        break;
                    case 'yearly':
                        let y_val = $('#recur_after_y').val();
                        let y_month = $('#recur-month').val();
                        let recur_day = $('#yearly-container').find('#recur_month_day').val();

                        console.log(y_val);
                        console.log(y_month);
                        console.log(recur_day);

                        let month_days = moment().set('month', y_month).daysInMonth();
                        console.log(month_days);
                        if (recur_day < 1 || recur_day > month_days) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Please enter month day between (1 and ' + month_days + ')!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                            return false;
                        }

                        recurrence_pattern = 'yearly|' + y_val + '|' + y_month + '|' + recur_day;
                        break;
                    default:
                        break;
                }

                formData.append('recurrence_time', $('#recurrence_time').val());
                formData.append('recurrence_pattern', recurrence_pattern);
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Please select Recurrence Pattern!',
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                return false;
            }
        }

        let recurrence_start = '';
        if ($('#schedule_type').val() == 'custom') {
            recurrence_start = moment.utc().format('YYYY-MM-DD HH:mm');
        } else {
            let start_type = $('input[name="recurrence_start"]:checked').val();
            if (start_type) {
                switch (start_type) {
                    case 'date':
                        let start_date = $('#recurrence-range').find('#recur-start-date').val();
                        if (!start_date) {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Please enter recurrence start date!',
                                showConfirmButton: false,
                                timer: swal_message_time
                            })
                            return false;
                        }

                        recurrence_start = moment.utc(start_date).format('YYYY-MM-DD HH:mm');
                        break;
                    case 'now':
                        recurrence_start = moment.utc().format('YYYY-MM-DD HH:mm');
                        break;
                    default:
                        break;
                }
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Please select Recurrence Start!',
                    showConfirmButton: false,
                    timer: swal_message_time
                })
                return false;
            }
        }

        let recur_end_type = $('input[name="recurrence_end"]:checked').val();
        let recur_end_val = '';
        if (recur_end_type) {
            switch (recur_end_type) {
                case 'date':
                    let end_date = $('#recurrence-range').find('#recur-end-date').val();
                    if (!end_date) {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Please enter recurrence end date!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })
                        return false;
                    }

                    recur_end_val = moment.utc(end_date).format('YYYY-MM-DD HH:mm');
                    break;
                case 'count':
                    let end_after = $('#recurrence-range').find('#end-after-occur').val();
                    if (!end_after) {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Please enter number of occurences!',
                            showConfirmButton: false,
                            timer: swal_message_time
                        })
                        return false;
                    }

                    recur_end_val = end_after;
                    break;
                default:
                    break;
            }
        } else {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Please select Recurrence End!',
                showConfirmButton: false,
                timer: swal_message_time
            })
            return false;
        }

        formData.append('recurrence_end_type', recur_end_type);
        formData.append('recurrence_end_val', recur_end_val);
        formData.append('recurrence_start', recurrence_start);
    }


    var action = $(this).attr('action');
    var method = $(this).attr('method');

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {

            if (data.success) {
                // send mail notification regarding ticket action
                // ticket_notify('ticket_followup', 'Follow-up added');

                if(data.hasOwnProperty('ticket_close')) {
                    $("#status").val(data.ticket_close).trigger("change");
                }

                // refresh logs
                getLatestLogs();

                $('#follow_up').modal('hide');
                $("#save_ticket_follow_up").trigger("reset");
                $('#ticket_id').val('').trigger("change");
                $('#schedule_type').val('').trigger("change");
                $('#schedule_time').val('').trigger("change");
                $('#custom_date').val('').trigger("change");
                $('#follow_up_dept_id').val(ticket.dept_id).trigger("change");
                $('#follow_up_priority').val(ticket.priority).trigger("change");
                $('#follow_up_assigned_to').val(ticket.assigned_to).trigger("change");
                $('#follow_up_status').val(ticket.status).trigger("change");
                $('#follow_up_type').val(ticket.type).trigger("change");
                $('#follow_up_project').val('').trigger("change");
                $('#follow_up_notes').val('').trigger("change");
                $('#created_at').val('').trigger("change");
                $('#updated_at').val('').trigger("change");
                $('#notes').val('').trigger('change');
                $('#tkt_alert').empty();
                $('#is_recurring').trigger('change');
                $('.allow-req').attr('disabled', true);

                $('#recur-daily').prop('checked', true).trigger('change');
                $('#general').trigger('change');

                getTicketFollowUp();

                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                })
            }
        },
        failure: function(errMsg) {
        }
    });
}

function selectColor(color) {
    gl_color_notes = color;
    console.log(color);
    tinymce.editors.note.contentDocument.body.style.backgroundColor= color;

}

function followUpNoteColor(color) {
    $('#follow_up_notes').css('background-color', color);
    $("#follow_up_notes_color").val(color);
}

function visibilityOptions() {
    let values = $('#note-visibilty').val();
    if (values.indexOf('Everyone') > -1 && values.length > 1) {
        $('#note-visibilty').val('Everyone').trigger('change');
    }
}

function updateTicketDate(){
    var new_date  = new Date().toLocaleString('en-US', { timeZone: time_zone });
    new_date =  moment(new_date).format(date_format + ' ' +'hh:mm A');
    $("#updation-date").html(new_date);
}

$("#save_ticket_note").submit(function(event) {
    event.preventDefault();
    var note = tinymce.editors.note.getContent();
    // var note = $("textarea[name=note]").html();
    let extract_notes_email = note.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);

    let vis = [];
    if (document.getElementById('note-type-user-org').checked || document.getElementById('note-type-user').checked) {
        vis = all_staff_ids;

    } else {
        vis = $('#note-visibilty').val();
        if (vis.indexOf('Everyone') > -1) vis = all_staff_ids;
    }

    var formData = new FormData(this);
    formData.append('ticket_id', ticket.id);
    formData.append('color', gl_color_notes);
    formData.append('visibility', vis.toString());
    formData.append('note', note);

    if(document.getElementById('note-type-user').checked) {
        formData.append('customer_id', temp_sel_customer);
    }

    if(document.getElementById('note-type-user-org').checked) {
        let cust_cmp = companies_list.find(item => { return item.id == ticket_customer.company_id });
        if(cust_cmp != null) {
            formData.append('company_id', cust_cmp.id);
        }
    }

    if (extract_notes_email != null && extract_notes_email != '') {
        formData.append('tag_emails', extract_notes_email.join(','));
    }

    $.ajax({
        type: "POST",
        url: "{{asset('save-ticket-note')}}" ,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend:function(data) {
            $("#note_save_btn").hide();
            $('#note_processing').attr('style', 'display: block !important');
        },
        success: function(data) {
            // console.log(data);
            if (data.success) {

                alertNotification('success', 'Success' , data.message);

                let b  = new Date(data.tkt_update_at).toLocaleString('en-US', { timeZone: time_zone });
                let tkt_updted_date = moment(b).format(date_format + ' ' + 'hh:mm A');
                // send mail notification regarding ticket action
                $("#updation-date").html(tkt_updted_date);

                let note_status = 'added';
                let note_temp = 'ticket_note_create';
                if ($('#note-id').val()) {
                    note_status = 'updated';
                    note_temp = 'ticket_note_update';
                }
                ticket_notify(note_temp, 'Note ' + note_status, data.data.id);

                // refresh logs
                getLatestLogs();

                $(this).trigger('reset');

                get_ticket_notes();

                if(data.hasOwnProperty('sla_updated') && data.sla_updated !== false) {
                    ticket.reply_deadline = data.sla_updated;
                    ticket.sla_rep_deadline_from = moment();
                }
                setSlaPlanDeadlines();

                $('#note-visibilty').val('Everyone').trigger('change');

                $('#notes_manager_modal').modal('hide');

            } else {
                alertNotification('error', 'Error' , data.message );
            }
        },
        complete:function(data) {
            $("#note_save_btn").show();
            $('#note_processing').attr('style', 'display: none !important');
        },
        failure: function(errMsg) {
            $("#note_save_btn").show();
            $('#note_processing').attr('style', 'display: none !important');
        }
    });
});

function get_ticket_notes() {
    $('#v-pills-notes-list').html('');
    $.ajax({
        type: 'GET',
        url: ticket_notes_route,
        data: { id: ticket_details.id },
        success: function(data) {
            if (data.success) {
                if(data.notes_count  != 0) {
                    $('.notes_count').addClass('badge badge-light-danger rounded-pill mx-1');
                    $('.notes_count').text(data.notes_count);
                }

                notes = data.notes;
                var type = '';

                if (timeouts_list.length) {
                    for (let i in timeouts_list) {
                        clearTimeout(timeouts_list[i]);
                    }
                }

                timeouts_list = [];

                let notes_html = ``;

                for (let i in notes) {

                    let timeOut = '';
                    let autho = '';
                    if (loggedInUser_t == 1) {

                        autho = `<div class="mt-2">

                            <span class="btn btn-icon rounded-circle btn-outline-danger waves-effect fa fa-trash"
                                style= "float:right;cursor:pointer;position:relative;bottom:25px"
                                onclick="deleteTicketNote(this, '` + notes[i].id + `')" ></span>

                            <span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit"
                                style="float:right;padding-right:5px;cursor:pointer;position:relative;bottom:25px; margin-right:5px"
                                onclick="editNote(${notes[i].id})"></span>


                        </div>`;
                    }

                    if(notes[i].type == 'Ticket') {

                        type = '<i class="fas fa-clipboard-list" data-bs-toggle="tooltip" data-placement="top" title="Ticket"></i>';

                    }else if(notes[i].type == 'User') {

                        type = '<i class="fas fa-user" data-bs-toggle="tooltip" data-placement="top" title="User"></i>';

                    }else{
                        type = '<i class="far fa-building" data-bs-toggle="tooltip" data-placement="top" title="Organization"></i>';
                    }

                    var user_img = ``;
                    let is_live = "{{Session::get('is_live')}}";
                    let path = is_live == 0 ? '' : 'public/';

                    if(notes[i].profile_pic != null) {

                        user_img += `<img src="{{asset('${notes[i].profile_pic}')}}"
                        width="40px" height="40px" class="rounded-circle" style="border-radius: 50%;"/>`;

                    }else{

                        user_img += `<img src="{{asset('${path}default_imgs/customer.png')}}"
                                width="40px" height="40px" style="border-radius: 50%;" class="rounded-circle" />`;

                    }

                    let flup = `<div class="col-12 rounded p-2 my-1 d-flex" id="note-div-` + notes[i].id + `" style="background-color: ` + notes[i].color + `">
                        <div style="margin-right: 10px; margin-left: -8px;">
                            ${user_img}
                        </div>
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <h5 class="note-head" style="margin-top:10px"> <strong> ${notes[i].name} </strong> on <span class="small"> ${jsTimeZone(notes[i].created_at)} </span>  ${type} </h5>
                                ` + autho + `
                            </div>
                            <blockquote>
                            <p class="col text-dark" style="margin-top:-20px; word-break:break-all; color:black !important">
                                ${notes[i].note.replace(/\r\n|\n|\r/g, '<br />')}
                            </p>
                            </blockquote>
                        </div>
                    </div>`;


                    $('#v-pills-notes-list').append(flup);
                }
            }
        },
        failure: function(errMsg) {

        }
    });
}


function jsTimeZone(date) {
    let d = new Date(date);

    var year =  d.getFullYear();
    var month = d.getMonth();
    var date = d.getDate();
    var hour = d.getHours();
    var min = d.getMinutes();
    var mili = d.getMilliseconds();

    // year , month , day , hour , minutes , seconds , miliseconds;
    let new_date = new Date(Date.UTC(year, (month), date, hour, min, mili));
    let converted_date = new_date.toLocaleString("en-US", {timeZone: time_zone});
    return moment(converted_date).format(date_format + ' ' +'hh:mm A');
}

function editNote(id) {
    let item = notes.find(item => item.id === id);

    if(item != null || item != undefined || item != "") {

        $("#note_title").text("Edit Notes");
        $('#notes_manager_modal').modal('show');

        $('#note-id').val(id);

        $("#note-visibilty").val("Everyone").trigger('change');
        tinymce.activeEditor.setContent(item.note != null ? item.note : '')
        tinyMCE.get(1).getBody().style.backgroundColor = item.color != null ? item.color : '';
        gl_color_notes = item.color != null ? item.color : '';
        if(item.type == 'Ticket') {
            $("#note-type-ticket").prop('checked',true);
            $('.note-visibilty').prop('disabled',false);
        }else if(item.type == 'User'){
            $("#note-type-user").prop('checked',true);
            $('.note-visibilty').prop('disabled',true);
        }else{
            $("#note-type-user-org").prop('checked',true);
            $('.note-visibilty').prop('disabled',true);
        }

    }
}

$('#notes_manager_modal').on('show.bs.modal', function(e) {
    if (!$('#save_ticket_note').find('#note-id').val()) {
        $('#save_ticket_note').trigger('reset');
    }
});

$('#notes_manager_modal').on('hidden.bs.modal', function(e) {
    $('#save_ticket_note').find('#note-id').val('');
});

function deleteTicketNote(ele, id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this note will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                type: 'post',
                url: del_ticket_route,
                data: { id: id , ticket_id : "{{$details->coustom_id}}" },
                success: function(data) {

                    if (data.success) {
                        // send mail notification regarding ticket action
                        // ticket_notify('ticket_update', 'Note removed');

                        // refresh logs
                        getLatestLogs();

                        $(ele).closest('#note-div-' + id).remove();

                        alertNotification('success', 'Success' , data.message);

                        let no_counts = $('.notes_count').text();
                        $('.notes_count').text( (no_counts - 1) );
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    }
                },
                failure: function(errMsg) {
                    // console.log(errMsg);
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: errMsg,
                        showConfirmButton: false,
                        timer: swal_message_time
                    });
                }
            });
        }
    });
}

function ShowAssetModel() {
    $('#asset').modal('show');
}

function closeAssetModal() {
    $('#asset').modal('hide');

    $("#form_id").val("").trigger('change');
}

function setCustomerCompany() {
    let cust_cmp = companies_list.filter(item => { return item.id == ticket_customer.company_id });
    if (cust_cmp.length) {
        let name = `<a class="text-body" href="{{url('company-profile')}}/${cust_cmp[0].id}"> ${cust_cmp[0].name} </a>`;
        $('#cst-company').html('Company : ' + name);
        $('#cst-company-name').html('Company Line : ' + cust_cmp[0].phone);

        // $('#adjustCard1Height').attr('style', 'height: 300px !important');
        $('#adjustCard2Height').attr('style', 'height: 160px !important; overflow-y:scroll');

    } else {
        $('#cst-company').html('');
        $('#cst-company-name').html('');

        // $('#adjustCard1Height').attr('style', 'height: 260px !important');
        $('#adjustCard2Height').attr('style', 'height: 120px !important; overflow-y:scroll');
    }
}

function flagTicket() {
    $.ajax({
        type: 'post',
        url: flag_ticket_route,
        data: { id: asset_ticket_id },
        success: function(data) {

            if (data.success) {
                // send mail notification regarding ticket action
                let nn = (ticket.is_flagged == 1) ? 'Flag removed' : 'Flagged';
                updateTicketDate();
                ticket_notify('ticket_update', nn);

                // refresh logs
                getLatestLogs();

                if (ticket.is_flagged === 0) {
                    ticket.is_flagged = 1;
                    alertNotification('success', 'Success' , 'Flagged Successfully!');
                } else {
                    ticket.is_flagged = 0;
                    alertNotification('success', 'Success' , 'Flagged removed Successfully!');
                }
            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        },
        failure: function(errMsg) {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: errMsg,
                showConfirmButton: false,
                timer: swal_message_time
            });
        }
    });
}

function getLatestLogs() {
    $.ajax({
        type: 'GET',
        url: get_ticket_latest_log,
        data: { id: asset_ticket_id },
        success: function(data) {
            if (data.success) {

                var obj = data.logs;

                let id = "{{$details->coustom_id}}";

                let newLogs = $.grep(obj , function(item , index) {
                    if(item.action_perform != null || item.action_perform != '') {
                        if(item.action_perform.includes(id)) {
                            return item;
                        }
                    }
                });

                $('#ticket-logs-list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#ticket-logs-list').DataTable({
                    "order": [[ 1, "desc" ]],
                    data: newLogs,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
                    "searching": true,
                    columns: [
                        {
                            className : 'd-none',
                            "render": function(data, type, full, meta) {
                                return full.id;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.action_perform != null ? full.action_perform+' at '+ convertDate(full.created_at) : '-';
                            }
                        },
                    ],
                });
            } else {

            }
        },
        failure: function(errMsg) {

        }
    });
}

function showDepartStatus(value , type) {
    $.ajax({
        type: "POST",
        url: "{{url('get_department_status')}}",
        data: {id:value},
        dataType: 'json',
        beforeSend: function(data) {
            $("#dropdown_loader").show();
        },
        success: function(data) {
            // console.log(data , "assignee");
            let obj = data.status;
            let obj_user = data.users;
            let option = ``;
            let select = ``;
            let open_sts = '';
            let obj_queue = data.queue;
            let default_queue = data.default_queue;

            if(default_queue == null){
                default_queue = obj_queue['0'];
            }
            for(var i =0; i < obj.length; i++) {
                if(obj[i].name == 'Open'){
                    open_sts = obj[i].id;
                }
                if(default_queue.mail_status_id == obj[i].id){
                    option +=`<option value="`+obj[i].id+`" data-color="`+obj[i].color+`" selected>`+obj[i].name+`</option>`;
                }else{
                    option +=`<option value="`+obj[i].id+`" data-color="`+obj[i].color+`">`+obj[i].name+`</option>`;
                }
                // option +=`<option value="`+obj[i].id+`" data-color="`+obj[i].color+`">`+obj[i].name+`</option>`;
            }
            if(type == 'followup') {

                $("#follow_up_status").html(option);

            }else{

                $("#status").html(select + option);

                console.log(value +'========'+ticket.dept_id)
                if (value == ticket.dept_id){
                    // alert('asd');
                    updates_Arr = $.grep(updates_Arr, function(e){
                        return e.id != 1;
                    });
                    if(update_flag > 0){
                        update_flag--;
                        if(update_flag == 0){
                            $("#update_ticket").css("display", "none");
                        }
                    }
                    $('#status').val(ticket.status); // Select the option with a value of '1'
                    $('#status').trigger('change');
                    // return false;
                }
                // else{
                //     $('#status').val(open_sts); // Select the option with a value of '1'
                //     $('#status').trigger('change');
                // }
                $('#status').trigger('change');

                if(reply_flag == 0) {
                    $("#update_ticket").css("display", "block");
                }

                let email_option = ``;
                for( let item of obj_queue) {

                    if(item.is_default == 'yes'){
                        email_option += `<option value="${item.id}" selected> ${item.mailserver_username} (${item.from_name}) </option>`;
                    }else{
                        email_option += `<option value="${item.id}"> ${item.mailserver_username} (${item.from_name}) </option>`;
                    }
                }
                $("#queue_id").html(email_option);
                $('#priority').val(default_queue.mail_priority_id);
                $("#priority").trigger('change');

                $('#type').val(default_queue.mail_type_id);
                $("#type").trigger('change');



                let assigned_to = $('#assigned_to').val();
                let ass_obj = {};
                let flg = false;


                select = `<option value="">Unassigned</option>`;

                for(var i =0; i < obj_user.length; i++) {
                    if(assigned_to == obj_user[i].id){
                        select +=`<option value="`+obj_user[i].id+`" selected>`+obj_user[i].name+`</option>`;
                        flg = true;
                    }else{
                        select +=`<option value="`+obj_user[i].id+`" >`+obj_user[i].name+`</option>`;
                    }
                }

                if(flg == false){
                    $('#tech-h5').text('Unassigned');

                        ass_obj = {
                        id:2,
                        data: ticket.assignee_name,
                        new_data: null,
                        new_text: "Unassigned" ,
                    }

                    let item = updates_Arr.filter(item => item.id == 2);
                    let index = updates_Arr.map(function (item) { return item.id; }).indexOf(2);
                    if(item.length > 0) {
                        updates_Arr[index].id = 2;
                        updates_Arr[index].data = ticket.assignee_name ;
                        updates_Arr[index].new_data = null ;
                        updates_Arr[index].new_text = "Unassigned";
                    }else{
                        updates_Arr.push(ass_obj);
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
}

function ticket_notify(template, action_name, data_id = '',oldval) {
    if (asset_ticket_id && template) {
        $.ajax({
            type: 'POST',
            url: ticket_notify_route,
            data: { id: asset_ticket_id, template: template, action: action_name, data_id: data_id ,oldval: oldval},
            success: function(data) {
                if (!data.success) {

                    // try again
                    // $.ajax({
                    //     type: 'POST',
                    //     url: ticket_notify_route,
                    //     data: { id: asset_ticket_id, template: template },
                    //     success: function(data) {
                    //         if (!data.success) {
                    //             console.log(data.message);
                    //         }
                    //     },
                    //     failure: function(errMsg) {
                    //         console.log(errMsg);
                    //     }
                    // });
                }
            },
            failure: function(errMsg) {

            }
        });
    }
}

async function downloadPNGFromAnyImageSrc(src) {
    //recreate the image with src recieved
    var img = new Image;
    //when image loaded (to know width and height)
    let r = new Promise((resolve, reject) => {
        img.onload = async function() {
            //drow image inside a canvas
            var canvas = document.createElement("canvas");
            canvas.width = img.width;
            canvas.height = img.height;
            canvas.getContext("2d").drawImage(img, 0, 0);

            resolve(canvas.toDataURL("image/png"));
        };
        img.src = src;
    });
    return await r;
}

function addAttachment(type, olderAttach='') {
    if(olderAttach) {
        $('#'+type+'_attachments').append(`
                        <div class="col-md-4 pt-1" style='position:relative;cursor:pointer' >
                            <div class="card" style='border:1px solid #c7c7c7;border-radius: 3px !important;margin-bottom: 1rem;'>
                                <div class="card-body" style="padding: .3rem .3rem !important;background-color:#dfdcdc1f">
                                    <div class="" style="display: -webkit-box">
                                                <div class="modal-first w-100">
                                                    <div class="mt-0 rounded" >
                                                        <div class="float-start rounded me-1 bg-none" style="">
                                                            <div class="">
                                                                <img src="{{asset('storage/tickets/${ticket_details.id}/${olderAttach}')}}" class="attImg"  alt="" style="width:40px;height:30px !important">
                                                            </div>
                                                        </div>
                                                        <div class="more-info">
                                                            <a>
                                                                <h6 class="mb-0 fw-bolder" style='font-size:12px;margin-top: 7px;'>
                                                                    ${olderAttach}
                                                                </h6>
                                                            </a>
                                                        </div>

                                                    </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>`);
    } else {
        $('#'+type+'_attachments').append(`<div class="input-group pt-1">
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
}

$(document).on('change', '.tickets_attaches', function(e){
    let file = e.target.files[0];
    $(this).parent().find('.custom-file-label').text(file.name);
});

$(document).on('change', '.replies_attaches', function(e){
    let file = e.target.files[0];
    $(this).parent().find('.custom-file-label').text(file.name);
});

function removeAttachment(el, fileName, mod) {
    Swal.fire({
        title: 'Are you sure?',
        text: "Attachment will be deleted permanently?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            let params = {
                module : mod,
                id : ticket_details.id,
                fileName : fileName
            };
            if(mod == 'replies') params.reply_id = ticketReplies[edit_reply_mode].id;

            $.ajax({
                type: "post",
                url: "{{asset('delete_attachment')}}",
                data: params,
                dataType: 'json',
                cache: false,
                success: function(data) {
                    if (!data.success) {
                        Swal.fire({
                            position: 'center',
                            icon: "error",
                            title: data.message,
                            showConfirmButton: false,
                            timer: swal_message_time
                        });
                    } else {
                        if(mod == 'tickets') ticket_details.attachments = data.attachments;
                        else ticketReplies[edit_reply_mode].attachments = data.attachments;

                        $(el).parent().parent().remove();
                    }
                }
            });
        }
    });
}

function getExt(filename) {
    var ext = filename.split('.').pop();
    if(ext == filename) return "";
    return ext;
}

function openNotesModal() {
    $("#note_title").text("Add Notes");
    $("#notes_manager_modal").modal('show');
    $("#note").val(" ");
    $("#note-type-ticket").prop('checked',true);
    $('#note-visibilty').prop('disabled', false);
    $("#note-visibilty").val("Everyone").trigger('change');
    $("#note-id").val("");
    tinymce.get(1).getBody().style.backgroundColor = '#FFEFBB';
    gl_color_notes = '#FFEFBB';
}

function showFollowUpModal() {

    // disbled previous dates from followup calender
    disablePreviousDates("recur-start-date");
    disablePreviousDates("recur-end-date");

    $("#save_ticket_follow_up").trigger("reset");

    $("#follow_up").modal('show');
    $("#schedule_type").val('minutes').trigger('change');

    $("#general").prop("checked",false);
    $('#general_details_div').css('display', 'none')

    $("#notes").prop("checked",false);
    $('#ticket_follow_notes').css('display', 'none')

    $("#is_recurring").prop("checked",false);
    $('#recurrence-range').css('display', 'none')

    $("#fu_post_reply").prop("checked" , false);
    $("#fu_post_reply_ttar_div").css('display','none');



    // checking darkmood
    if ($(".loaded ").hasClass('dark-layout')) {

        $('.followup_accordin').removeClass('bg-light');

        $('.general_checkbox').attr('style','border:1px solid white !important');
        $('.notes_checkbox').attr('style','border:1px solid white !important');
        $('.reply_checkbox').attr('style','border:1px solid white !important');
        $('.recurring_checkbox').attr('style','border:1px solid white !important');


    }else{
        $('.followup_accordin').addClass('bg-light');

        $('.general_checkbox').removeAttr('style');
        $('.notes_checkbox').removeAttr('style');
        $('.reply_checkbox').removeAttr('style');
        $('.recurring_checkbox').removeAttr('style');
    }
}


const ticketDetailTheme = {
    light : () => {

        $('.bootstrap-tagsinput').removeAttr('style');

        $("#response_template_fields").addClass('bg-light');
        $("#response_template_fields").removeClass('bg-dark');
    },
    dark : () => {

        $('.bootstrap-tagsinput').attr('style','background: #424242; border: 1px solid #424242 !important;');
        $("#response_template_fields").removeClass('bg-light');
        $("#response_template_fields").addClass('bg-dark');

    }
}


function notesModalClose() {
    $("#notes_manager_modal").modal('hide');
}

function matchStart(params, data) {
    // If there are no search terms, return all of the data
    if ($.trim(params.term) === '') {
        return data;
    }

    // Skip if there is no 'children' property
    // if (typeof data.children === 'undefined') {
    //     return null;
    // }
    // Do not display the item if there is no 'text' property
    if (typeof data.text === 'undefined') {
        return null;
    }

    // `data.children` contains the actual options that we are matching against
    // var filteredChildren = [];
    // $.each(data.children, function (idx, child) {
    //     if (child.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
    //         filteredChildren.push(child);
    //     }
    // });

    // If we matched any of the timezone group's children, then set the matched children on the group
    // and return the group object
    // if (filteredChildren.length) {
    //     var modifiedData = $.extend({}, data, true);
    //     modifiedData.children = filteredChildren;

    //     // You can return modified objects from here
    //     // This includes matching the `children` how you want in nested data sets
    //     return modifiedData;
    // }
    // `params.term` should be the term that is used for searching
    // `data.text` is the text that is displayed for the data object
    if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
        var modifiedData = $.extend({}, data, true);
        // modifiedData.text += ' (matched)';

        // You can return modified objects from here
        // This includes matching the `children` how you want in nested data sets
        return modifiedData;
    }

    // Return `null` if the term should not be displayed
    return null;
}

// reset fields
function resetSLA(value) {
    // if(value == 'reply_due') {
    //     $("#ticket-rep-due").val("");
    // }else{
    //     $("#ticket-res-due").val("");
    // }`
    if(value == 'reply_due') {
        $("#reply_date").val("");
        $("#reply_hour").val("12");
        $("#reply_minute").val("00");
        $("#reply_type").val("PM");
    }else{
        $("#res_date").val("");
        $("#res_hour").val("12");
        $("#res_minute").val("00");
        $("#res_type").val("PM");
    }
}

function resetTktSLA(value) {
    if(value  == 1) {
        $("#ticket-rep-due").val("");
    }else{
        $("#ticket-res-due").val("");
    }
}

function toggleReq(){
    $(".frst").toggleClass("d-none");
    $(".sec").toggleClass("d-none");
    let ter = $('#ticket_details_p').html();
    // $('#ticket_details_p2').html(ter);

    // $("#ticket-timestamp2").text($("#ticket-timestamp").text());
}



// trash ticket
function trashTicket(id) {

    $.ajax({
        type: 'post',
        url: "{{url('move_to_trash_tkt')}}",
        data: { tickets : [id] },
        success: function(data) {

            if (data.success == true && data.status_code == 200) {
                alertNotification('success', 'Success' , data.message);
                window.location.href = "{{route('ticket_management.index')}}";
            }else{
                alertNotification('error', 'Error' , data.message );
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    })
}



function publishTicketReply(reply) {
    let params = {
        type : 'publish',
        cc: null,
        ticket_id: ticket.id,
        reply: reply,
    };

    $.ajax({
        type: "post",
        url: publish_reply_route,
        data: params,
        dataType: 'json',
        enctype: 'multipart/form-data',
        cache: false,
        success: function(data) {
            // console.log(data , "publish ticket reply");
        }
    });
}

function getTicketReplies(id) {
    $.ajax({
        type: 'GET',
        url: "{{url('ticket-replies')}}/"+id,
        success: function(data) {
            console.log(data)
            ticketReplies = data.replies;
            listReplies();
            $("#ticket-replies1").remove();
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    })
}

function checkRecurrence(type) {

    if(type == 'daily_check') {
        $("." + type + "_div").show();

        $(".weekly_check_div").hide();
        $(".monthly_check_div").hide();
        $(".yearly_check_div").hide();
    }

    if(type == 'weekly_check') {
        $("." + type + "_div").show();

        $(".daily_check_div").hide();
        $(".monthly_check_div").hide();
        $(".yearly_check_div").hide();
    }

    if(type == 'monthly_check') {
        $("." + type + "_div").show();

        $(".daily_check_div").hide();
        $(".weekly_check_div").hide();
        $(".yearly_check_div").hide();
    }

    if(type == 'yearly_check') {
        $("." + type + "_div").show();

        $(".daily_check_div").hide();
        $(".weekly_check_div").hide();
        $(".monthly_check_div").hide();
    }

}


function disablePreviousDates(id) {
    let region_today_date = new Date().toLocaleString("en-US" , {timeZone: time_zone});
    let region_date = moment(region_today_date).format('YYYY-MM-DD');
    document.getElementById(id).setAttribute("min", region_date);
}
</script>
