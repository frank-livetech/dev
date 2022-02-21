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
let attachments_src = [];
let ticket_attachments_count = 1;
let date_format = {!! json_encode($date_format) !!};
let update_flag = 0;
let updates_Arr = [];
// var ticket_attach_path = `{{asset('public/files')}}`;
// var ticket_attach_path_search = 'public/files';

var ticket_attach_path = `{{asset('storage')}}`;
var ticket_attach_path_search = 'storage';

var time_zone = $("#usrtimeZone").val();
var js_path = "{{Session::get('is_live')}}";
js_path = (js_path == 1 ? 'public/' : '');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$(document).ready(function() {
       
    tinymce.init({
        selector: "textarea#mymce",
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
    $('#ticket-timestamp').text( convertDate(ticket.created_at) );
    // $('#ticket-timestamp2').text( convertDate(ticket.created_at) );
    $('#ticket-timestamp3').text( convertDate(ticket.created_at) );

    
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
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",
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
    $('#ticket_details_p2').html(getTicketDetailsContent());
    $('#ticket_details_p3').html(getTicketDetailsContent());

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
        tinymce.activeEditor.setContent('');
    }else{
        let content = tinymce.activeEditor.getContent();
        let res = res_templates_list.find(item => item.id == $(this).val());
        if(content.length == 0)  {
            tinymce.activeEditor.setContent(`<p>${res.temp_html}</p>`);
        }
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
                tinymce.activeEditor.setContent(res.temp_html ? res.temp_html : '');
            }
        });
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
        if (ticket.hasOwnProperty('reply_deadline') && ticket.reply_deadline) {
            // use ticket reset deadlines
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

    if (rep_due) $('#sla-rep_due').html(rep_due);
    if (res_due) $('#sla-res_due').html(res_due);

    // if (!rep_due && !res_due) $('.sla-selc').hide();
    // else $('.sla-selc').show();

    // any deadline is overdue can be reset
    if (resetable) {
        $("#card-sla").css('background-color', {!! json_encode($ticket_overdue_bg_color) !!});
        $("#card-sla").css('color', {!! json_encode($ticket_overdue_txt_color) !!});
        $("#card-sla a").css('color', {!! json_encode($ticket_overdue_txt_color) !!});
    } else {
        $("#card-sla").css('background-color', 'white');
        $("#card-sla").css('color', '#000');
    }
}

function resetSlaPlan() {
    if(ticket != null) {
        if(ticket.reply_deadline == null || ticket.resolution_deadline == null) {
            if(ticket_slaPlan != null && ticket_slaPlan != "") {
                var today = new Date();

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
    let rep_deadline = $('#ticket-rep-due').val();
    let res_deadline = $('#ticket-res-due').val();

    if (!rep_deadline && !res_deadline) {
        // Swal.fire({
        //     position: 'center',
        //     icon: "error",
        //     title: "Please enter date to reset!",
        //     showConfirmButton: false,
        //     timer: swal_message_time
        // });
        // return false;
        rep_deadline = 'cleared';
        res_deadline = 'cleared';

    }

    let formData = {
        ticket_id: ticket.id,
        rep_deadline: rep_deadline,
        res_deadline: res_deadline
    };

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
                        toastr.success('SLA updated Successfully!', { timeOut: 5000 });
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
    $('#ticket_subject_heading').css('display', 'none');
    $('#ticket_details_p').css('display', 'none');
    $('#edit_request_btn').css('display', 'none');
    $('#ticket_subject_edit_div').css('display', 'block');
    $('#ticket_details_edit_div').css('display', 'block');
    $('#save_request_btn').css('display', 'block');
    $('#cancel_request_btn').css('display', 'block');

    $('#ticket_subject_edit').val(ticket.subject);

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
    // $('#ticket_details_p2').html(getTicketDetailsContent());
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
    // $('#tinycontenteditor').find('source').each(function(index) {
    //     let src = $(this).attr('src');
    //     let ext = 'mp4';

    //     let validVid = true;

    //     let marker = '.';

    //     if (src.includes('base64')) marker = '/';

    //     if (src.includes(marker + 'mp4') || src.includes(marker + 'MP4')) {
    //         ext = "mp4";
    //     } else if (src.includes(marker + 'mpeg4') || src.includes(marker + 'MPEG4')) {
    //         ext = "mpeg4";
    //     } else if (src.includes(marker + 'mov') || src.includes(marker + 'MOV')) {
    //         ext = "mov";
    //     } else if (src.includes(marker + 'avi') || src.includes(marker + 'AVI')) {
    //         ext = "avi";
    //     } else if (src.includes(marker + 'wmv') || src.includes(marker + 'WMV')) {
    //         ext = "wmv";
    //     } else if (src.includes(marker + 'flv') || src.includes(marker + 'FLV')) {
    //         ext = "flv";
    //     } else if (src.includes(marker + 'mp3') || src.includes(marker + 'MP3')) {
    //         ext = "mp3";
    //     } else if (src.includes(marker + 'mpeg') || src.includes(marker + 'MPEG')) {
    //         ext = "mpeg";
    //     } else {
    //         $(this).remove();
    //         validVid = false;
    //     }

    //     if (src.includes('base64')) {
    //         src = src.replace(/^data:.+;base64,/, '');
    //     }

    //     if (validVid) {
    //         let name = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index + '.' + ext;

    //         if (src.includes(ticket_attach_path_search + '/' + action + '/' + ticket.id)) {
    //             name = baseName(src) + '.' + ext;
    //         } else {
    //             $(this).attr('src', ticket_attach_path + `/${action}/${ticket.id}/${name}`);
    //         }
    //         attachments_src.push([name, src]);
    //     }
    // });
    // $('#tinycontenteditor').find('a').each(function(index) {
    //     let href = $(this).attr('href');
    //     if (href.includes('blob:')) {
    //         res = getBlobFromUrl(href).then(fromBlobToBase64).then(src => {
    //             // result will contain file encoded in base64
    //             console.log(src);
    //             let ext = 'txt';
    //             let validFile = true;
    //             let marker = '.';
    //             if (src.includes('base64')) marker = '/';

    //             if (src.includes(marker + 'plain') || src.includes(marker + 'PLAIN')) {
    //                 ext = "txt";
    //             } else if (src.includes(marker + 'octet-stream') || src.includes(marker + 'OCTET-STREAM')) {
    //                 ext = "sql";
    //             } else if (src.includes(marker + 'pdf') || src.includes(marker + 'PDF')) {
    //                 ext = "pdf";
    //             } else if (src.includes(marker + 'vnd.openxmlformats-officedocument.wordprocessingml.document')) {
    //                 ext = "docx";
    //             } else if (src.includes(marker + 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
    //                 ext = "xlsx";
    //             } else if (src.includes(marker + 'x-zip-compressed')) {
    //                 ext = "zip";
    //             } else if (src.includes(marker + 'x-msdownload')) {
    //                 ext = "exe";
    //             } else if (src.includes(marker + 'x-gzip')) {
    //                 ext = "tar.gz";
    //             } else if (src.includes(marker + 'msaccess')) {
    //                 ext = "accdb";
    //             } else if (src.includes(marker + 'vnd.ms-publisher')) {
    //                 ext = "pub";
    //             } else if (src.includes(marker + 'msword')) {
    //                 ext = "rtf";
    //             } else {
    //                 $(this).remove();
    //                 validFile = false;
    //             }

    //             if (src.includes('base64')) {
    //                 src = src.replace(/^data:.+;base64,/, '');
    //             }

    //             if (validFile) {
    //                 let name = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index + '.' + ext;

    //                 if (src.includes(ticket_attach_path_search + '/' + action + '/' + ticket.id)) {
    //                     name = baseName(src) + '.' + ext;
    //                 } else {
    //                     $(this).attr('href', ticket_attach_path + `/${action}/${ticket.id}/${name}`);
    //                 }
    //                 attachments_src.push([name, src]);
    //             }
    //         })
    //     }
    // });

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
            },
            dataType: 'json',
            cache: false,
            success: function(data) {
                if (data.success == true) {
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

                    toastr.success( 'Initial Request Updated Successfully' , { timeOut: 5000 });

                    $('#ticket_subject_heading').css('display', 'block');
                    $('#ticket_details_p').css('display', 'block');
                    $('#edit_request_btn').css('display', 'block');
                    $('#ticket_subject_edit_div').css('display', 'none');
                    $('#ticket_details_edit_div').css('display', 'none');
                    $('#save_request_btn').css('display', 'none');
                    $('#cancel_request_btn').css('display', 'none');

                    $('#ticket_subject_heading').text('Subject : ' + $('#ticket_subject_edit').val());
                    $('#ticket_details_p').html(getTicketDetailsContent());
                    // $('#ticket_details_p2').html(getTicketDetailsContent());
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

        if(ticket_details.tkt_crt_type == 'cron'){
            content = content.replace(/<img[^>]*>/g,"");
        }else{
            content = content;
        }
      
        tdet = `<div class="col-12">${content}</div>`;
        // if(ticket_details.attachments != null || ticket_details.attachments != ""){

        //     let files = ticket_details.attachments.split(',');
        //     let file_row = ``;
        //     let extension_img = ``;
        //     tdet += '<div class="col-12 row">';
        //     for(let i =0; i < files.length; i++) {

        //         let extens = files[i].split('.');

        //         if(extens[1] == 'jpeg' || extens[1] == 'png' || extens[1] == 'jpg' || extens[1] == 'webp' || extens[1] == 'svg') {
        //             extension_img = `<img src="{{asset('default_imgs/image.jpeg')}}" width="20px" height="20px">`;
        //         }

        //         if(extens[1] == 'pdf') {
        //             extension_img = `<img src="{{asset('default_imgs/pdf.gif')}}" width="20px" height="20px">`;
        //         }

        //         if(extens[1] == 'txt') {
        //             extension_img = `<img src="{{asset('default_imgs/txt.gif')}}" width="20px" height="20px">`;
        //         }

        //         if(extens[1] == 'docm' || extens[1] == 'docx' || extens[1] == 'dot' || extens[1] == 'dotx') {
        //             extension_img = `<img src="{{asset('default_imgs/word.gif')}}" width="20px" height="20px">`;
        //         }

        //         if(extens[1] == 'xls' || extens[1] == 'xlsb' || extens[1] == 'xlsm' || extens[1] == 'xlsx') {
        //             extension_img = `<img src="{{asset('default_imgs/xlx.gif')}}" width="20px" height="20px">`;
        //         }

        //         if(extens[1] == 'pptx' || extens[1] == 'pptm' || extens[1] == 'ppt') {
        //             extension_img = `<img src="{{asset('default_imgs/ppt.gif')}}" width="20px" height="20px">`;
        //         }

        //         tdet +=`
        //             <div class="col-md-12 mt-1">
        //                 ${extension_img}
        //                 <a href="${root}/storage/tickets/${ticket_details.id}/${files[i]}" target="_blank">${files[i]}</a> 
        //             </div> `
        //     }
        //     tdet += '</div>';

        // }
        
    }

    if(ticket_details.attachments) {
        let attchs = ticket_details.attachments.split(',');
        tdet += '<div class="col-12 row">';
        attchs.forEach(item => {
            // tdet += `<p><a href="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" target="_blank">${item}</a></p>`;
            var tech =  `{{asset('/storage/tickets/${ticket_details.id}/${item}')}}`;
            var ter = getExt(tech);
            // return ter;
            if(ter == "pdf" ){
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;'>
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                                <span class="overlayAttach"></span>
                                <img src="{{asset('${js_path}default_imgs/pdf.png')}}"  alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/pdf.png')}}"  alt=""> ${item}</span>
                                <a href="{{asset('files/tickets/${ticket_details.id}/${item}')}}" download="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            else if(ter == "csv" || ter == "xls" || ter == "xlsx" || ter == "sql"){
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;'>
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                                <span class="overlayAttach"></span>

                                <img src="{{asset('${js_path}default_imgs/xlx.png')}}" width="38" alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/xlx.png')}}"  alt=""> ${item}</span>
                                <a href="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" download="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            else if(ter == "png" || ter == "jpg" || ter == "webp" || ter == "jpeg" || ter == "webp" || ter == "svg" || ter == "psd"){
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;' >
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne" style="background:black">
                               <span class="overlayAttach"></span> 
 
                                <img src="{{asset('storage/tickets/${ticket_details.id}/${item}')}}" class=" attImg"  alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/image.jpeg')}}" alt="">  ${item}</span>
                                <a href="{{asset('storage/tickets/${ticket_details.id}/${item}')}}" download="{{asset('storage/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            else if(ter == "docs" || ter == "doc" || ter == "txt" || ter == "dotx" || ter == "docx"){
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;'>
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                                <span class="overlayAttach"></span>

                                <img src="{{asset('${js_path}default_imgs/word.png')}}" width="38" alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/word.png')}}"  alt="">  ${item}</span>
                                <a href="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" download="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            else if(ter == "ppt" || ter == "pptx" || ter == "pot" || ter == "pptm"){
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;'>
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                                <span class="overlayAttach"></span>

                                <img src="{{asset('${js_path}default_imgs/pptx.png')}}" class="imgIcon" width="38" alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/pptx.png')}}"  alt="">  ${item}</span>
                                <a href="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" download="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            else if(ter == "zip"){
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;'>
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                                <span class="overlayAttach"></span>

                                <img src="{{asset('${js_path}default_imgs/zip.jpeg')}}" class="imgIcon" width="38" alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/zip.jpeg')}}"  alt="">  ${item}</span>
                                <a href="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" download="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            else{
                tdet+= `<div class="col-md-3 mt-1" style='position:relative;'>
                                <div class="card__corner">
                                    <div class="card__corner-triangle"></div>
                                </div>
                            <div class="borderOne">
                                <span class="overlayAttach"></span>

                                <img src="{{asset('${js_path}default_imgs/txt.gif')}}" class="imgIcon" width="38" alt="">
                                <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/txt.gif')}}"  alt="">  ${item}</span>
                                <a href="{{asset('storage/tickets/${ticket_details.id}/${item}')}}" download="{{asset('storage/tickets/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                            </div>
                        </div>` 
            }
            // tdet += `
            // <input type="file" data-height="100" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset('public/files/tickets/${ticket_details.id}/${item}')}}" />`;
        });

        tdet += '</div>';
    }

    return tdet;
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
    ticketReplies.forEach(function(reply, index) {
        if (reply.is_published === 0) {
            editReply(index);
            $('#draft-rply').show();
        } else {
            let tdet = '';
            if(reply.attachments) {
                let attchs = reply.attachments.split(',');
                tdet += '';
                attchs.forEach(item => {
                    var tech =  `{{asset('storage/tickets-replies/${ticket_details.id}/${item}')}}`;
                    var ter = getExt(tech);

                    
                    // return ter;
                    if(ter == "pdf" ){
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                    <span class="overlayAttach"></span>

                                        <img src="{{asset('${js_path}default_imgs/pdf.png')}}" alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/pdf.png')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" download="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>` 
                    }
                    else if(ter == "csv" || ter == "xls" || ter == "xlsx" || ter =="sql"){
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="overlayAttach"></span>

                                        <img src="{{asset('${js_path}default_imgs/xlx.png')}}" alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/xlx.png')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" download="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>` 
                    }
                    else if(ter == "png" || ter == "jpg" || ter == "webp" || ter == "jpeg" || ter == "webp" || ter == "svg" || ter == "psd"){
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne">
                                        <span class="overlayAttach"></span>
                                        <img src="{{asset('storage/tickets-replies/${ticket_details.id}/${item}')}}" class=" attImg"  alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/image.jpeg')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('storage/tickets-replies/${ticket_details.id}/${item}')}}" download="{{asset('storage/tickets-replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>` 
                    }
                    else if(ter == "docs" || ter == "doc" || ter == "txt" || ter == "dotx" || ter == "docx"){
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="overlayAttach"></span>

                                        <img src="{{asset('${js_path}default_imgs/word.png')}}" alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/word.png')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" download="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>` 
                    }
                    else if(ter == "ppt" || ter == "pptx" || ter == "pot" || ter == "pptm"){
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="overlayAttach"></span>

                                        <img src="{{asset('${js_path}default_imgs/pptx.png')}}" alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/pptx.png')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" download="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>` 
                    }
                    else if(ter == "zip"){
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="overlayAttach"></span>

                                        <img src="{{asset('${js_path}default_imgs/zip.jpeg')}}" alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/zip.jpeg')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" download="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
                                    </div>
                                </div>` 
                    }
                    else{
                        tdet+= `<div class="col-md-4 mt-1">
                                        <div class="card__corner">
                                            <div class="card__corner-triangle"></div>
                                        </div>
                                    <div class="borderOne" style="display: flex; justify-content: center; align-items: center;">
                                        <span class="overlayAttach"></span>

                                        <img src="{{asset('${js_path}default_imgs/txt.png')}}" alt="">
                                        <span class="fileName"><img style="width:16px;height:16px;" src="{{asset('${js_path}default_imgs/txt.png')}}"  alt=""> ${item}</span>
                                        <a href="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" download="{{asset('public/files/replies/${ticket_details.id}/${item}')}}" class="downFile"><i class="fa fa-download"></i></a>
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

            if(reply.customer_replies != null) {
                if(reply.customer_replies.avatar_url != null) {
                    let path = root +'/'+ reply.customer_replies.avatar_url;
                    customer_img += `<img src="${path}"  width="40px" height="40px" class="rounded-circle " style="border-radius: 50%;"/>`;
                }else{
                    customer_img += `<img src="{{asset('${js_path}default_imgs/customer.png')}}" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                }                
            }else{
                customer_img += `<img src="{{asset('${js_path}default_imgs/customer.png')}}" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
            }

            if(reply.reply_user != null) {
                if(reply.reply_user.profile_pic != null) {
                    let path = root + '/' + reply.reply_user.profile_pic;
                    user_img += `<img src="${path}" style="border-radius: 50%;" class="rounded-circle " width="40px" height="40px" />`;
                }else{
                    user_img += `<img src="{{asset('${js_path}default_imgs/customer.png')}}" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
                }                
            }else{
                user_img += `<img src="{{asset('${js_path}default_imgs/customer.png')}}" class="rounded-circle" width="40px" height="40px" style="border-radius: 50%;" class="img-fluid" />`;
            }
            var content = '';
            if(reply.type == 'cron'){
                content = reply.reply.replace(/<img[^>]*>/g,"");
            }else{
                content = reply.reply;
            }

            let link = ``;
            // console.log(reply , "reply");
            if(reply.user_type == 5) {
                link = `<a href="{{url('customer-profile')}}/${reply.customer_id}"> ${reply.name} </a>`;
            }else{
                link = `<a href="{{url('profile')}}/${reply.id}"> ${reply.name} </a>`;
            }
            
            $('#ticket-replies').append(`
                <li class="media" id="reply__${index}">
                    <span class="mr-3">${reply.customer_replies == null ? user_img : customer_img }</span>
                    <div class="media-body">

                        <h5 class="mt-0"><span class="text-primary">
                            ${link}
                            </span>&nbsp;<span class="badge badge-secondary">`+user_type+`</span>&nbsp;
                        &nbsp; <span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit" style="cursor: pointer;position:absolute;right:63px;" onclick="editReply('${index}')"></span>&nbsp;&nbsp;<span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-trash" onclick="deleteReply(${reply.id},${index})" style="cursor: pointer;cursor: pointer;position:absolute;right:23px;" ></span>&nbsp;</h5> 

                        <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;">Posted on ` + convertDate(reply.created_at) + `</span> 
                        <div class="my-1 bor-top" id="reply-html-` + reply.id + `"> ${content} </div>
                        <div class="row mt-1">
                            ${tdet}
                        </div>
                    </div>
                </li>
                <hr>`);

            if (reply.hasOwnProperty('msgno') && reply.msgno) {
                $('#reply-html-' + reply.id).find('img').attr('width', 120);
                $('#reply-html-' + reply.id).find('img').attr('height', 120);
                $('#reply-html-' + reply.id).find('img').css('margin', '0 8px 8px 0');
            }
        }
    });
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
                        toastr.success(data.message, { timeOut: 5000 });
                        $("#reply__"+index).remove();
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
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

function publishReply(ele, type = 'publish') {
    var content = tinyMCE.editors.mymce.getContent();
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
                ticket_id: ticket.id,
                type: type,
                attachments: rep_attaches,
                reply: content,
                inner_attachments: attachments_src
            };

            if (edit_reply_mode !== false) {
                params.id = ticketReplies[edit_reply_mode].id;
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
                    var new_date  = new Date().toLocaleString('en-US', { timeZone: time_zone });
                    new_date =  moment(new_date).format(date_format + ' ' +'hh:mm A');
                    $("#updation-date").html(new_date);
                    $("#compose_btn").show();
                    $('.reply_btns').attr('style', 'display: none !important');

                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();

                    if (data.success == true) {

                        

                        $('#tinycontenteditor').html('');

                        let draft = false;
                        if (edit_reply_mode !== false) {
                            // console.log("here");
                            ticketReplies[edit_reply_mode] = data.data;
                            ticketReplies[edit_reply_mode].reply = content;
                            ticketReplies[edit_reply_mode].attachments = rep_attaches;
                        } else {
                            // console.log("here 1");
                            data.data.reply = content;
                            data.data.attachments = rep_attaches;
                            draft = ticketReplies.push(data.data);
                        }

                        listReplies();

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

                        if(updates_Arr.length > 0){
                            updateTicket();
                        }
                        // let msg = 'Added';
                        // if (edit_reply_mode !== false) msg = 'Updated';
                        // if (type != 'publish') msg = 'saved as draft';
                        toastr.success( data.message , { timeOut: 5000 });
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
                }
            });
        }
    });
}

function composeReply() {
    
    $('.reply_btns').attr('style', 'display: block !important');
    $("#compose_btn").hide();
    document.getElementById('compose-reply').classList.toggle('d-none');

    $('#replies_attachments').html('');
    ticket_attachments_count = 0;

    for (let i in ticketReplies) {
        if (ticketReplies[i].is_published === 0) {
            editReply(i);
            $('#draft-rply').show();
        }
    }
}

function editReply(rindex) {publishReply(this)
    tinyMCE.editors.mymce.setContent(ticketReplies[rindex].reply);

    if(ticketReplies[rindex].attachments) {
        let attchs = ticketReplies[rindex].attachments.split(',');
        
        $('#replies_attachments').html('');
        ticket_attachments_count = 0;

        attchs.forEach(item => {
            addAttachment('replies', item);
        });
    }

    $('#draft-rply').hide();
    if(ticketReplies[rindex].is_published == 1) $('#cancel-rply').show();

    document.getElementById('compose-reply').classList.remove('d-none');

    edit_reply_mode = rindex;
}

function cancelReply() {
    edit_reply_mode = false;
    
    document.getElementById('compose-reply').classList.add('d-none');

    tinyMCE.editors.mymce.setContent('');

    // $('#cancel-rply').hide();
    // $('#draft-rply').show();

    
    $('.reply_btns').attr('style', 'display: none !important');
    $("#compose_btn").show();

    listReplies();
}

$('#dept_id').change(function() {
    var dept_id = $(this).val();

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
        return false;
    }
    update_flag++;
    var obj = {};
    obj = {
        id:1,
        data: ticket.department_name, // Saving old value to show in email notification
        new_data:dept_id,
        new_text:$("#dept_id option:selected").text()
    }
    updates_Arr.push(obj);
    // console.log(updates_Arr);
    $("#update_ticket").css("display", "block");
  
    showDepartStatus(dept_id);
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

    update_flag++;
    var obj = {};
    obj = {
        id:2,
        data: ticket.assignee_name, // Saving old value to show in email notification
        new_data:assigned_to,
        new_text:$("#assigned_to option:selected").text()
    }
    updates_Arr.push(obj);
    // console.log(updates_Arr);
    $("#update_ticket").css("display", "block");
   
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

    update_flag++;
    var obj = {};
    obj = {
        id:3,
        data: ticket.type_name, // Saving old value to show in email notification
        new_data:type,
        new_text:$("#type option:selected").text()

    }
    updates_Arr.push(obj);
    // console.log(updates_Arr);
    $("#update_ticket").css("display", "block");
    
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

    update_flag++;
    var obj = {};
    obj = {
        id:4,
        data: ticket.status_name, // Saving old value to show in email notification
        new_data:status,
        new_text:$("#status option:selected").text()

    }
    updates_Arr.push(obj);
    // console.log(updates_Arr+'askdkaskjd');
    $("#update_ticket").css("display", "block");

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

    update_flag++;
    var obj = {};
    obj = {
        id:5,
        data: ticket.priority_name, // Saving old value to show in email notification
        new_data:priority,
        new_text:$("#priority option:selected").text()

    }
    updates_Arr.push(obj);
    $("#update_ticket").css("display", "block");

    // console.log(updates_Arr);
    
});

function updateTicket(){

    if(updates_Arr.length == 0){
        toastr.warning( 'There is nothing to update.' , { timeOut: 5000 });
        return false;
    }

    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {

            // priority: priority,
            id: ticket.id,
            dd_Arr:updates_Arr
            // action_performed: 'Ticket Priority'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            // console.log(data)
            if (data.success == true) {

                for(var i = 0 ; i < updates_Arr.length ; i++){

                    if(updates_Arr[i]['id'] == 1){
                        ticket.dept_id = updates_Arr[i]['new_data'];
                        $('#follow_up_dept_id').val(ticket.dept_id).trigger("change");
                    }else if(updates_Arr[i]['id'] == 2){
                        ticket.assigned_to = updates_Arr[i]['new_data'];
                        $('#follow_up_assigned_to').val(ticket.assigned_to).trigger("change");
                    }else if(updates_Arr[i]['id'] == 3){

                        ticket.type = updates_Arr[i]['new_data'];
                        $('#follow_up_type').val(ticket.type).trigger("change");

                    }else if(updates_Arr[i]['id'] == 4){

                        ticket.status = updates_Arr[i]['new_data'];
                        // $("#dropD").css('background-color' ,color + ' !important');
                        $('#follow_up_status').val(ticket.status).trigger("change");

                    }else if(updates_Arr[i]['id'] == 5){

                        ticket.priority = updates_Arr[i]['new_data'];
                        // $("#prio-label").css('background-color' ,color + ' !important');
                        $('#follow_up_priority').val(ticket.priority).trigger("change");

                    }

                }
                updateTicketDate();
                // // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Ticket Updated','', updates_Arr);
                updates_Arr = [];

                // // refresh logs
                getLatestLogs();
                $("#dropD ").find(".select2").hide();
                    $("#dropD ").find("h5").show();
                selectD();
                $("#update_ticket").hide();
                toastr.success( 'Ticket Updated Successfully!' , { timeOut: 5000 });
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
                g_followUps = data.data;

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

        if (details) details = `<div class="card-body"><ul>${details}</ul></div>`;

        let followUpDate = '';
        let remTime = '';
        let timediff = 0;
        let valid_date = false;
        let idata = {};

        if (g_followUps[i].is_recurring == 1) {
            followUpDate = moment(moment.utc(g_followUps[i].date).toDate()).local();
            if (g_followUps[i].schedule_type == 'time' && g_followUps[i].recurrence_time) {
                let rec_time = g_followUps[i].recurrence_time.split(':');
                followUpDate.set('hour', rec_time[0]);
                followUpDate.set('minute', rec_time[1]);
            }
            timediff = moment(followUpDate).diff(moment(), 'seconds');

            if (timediff < 0) idata.ticket_update = true;
            else remTime = getClockTime(followUpDate, timediff);

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

                if (timediff < 0) idata.passed = 1;
                else remTime = getClockTime(followUpDate, timediff);

                if (remTime) valid_date = true;
            }
        }

        if (valid_date) {
            let clsp = '';

            if (prv_clicked != 'collapse-' + g_followUps[i].id) clsp = 'collapsed';

            flups += `<div class="card-header mb-1" id="followup-${g_followUps[i].id}" style="color:black;background-color: rgba(0, 0, 0, .113);">
                <h5 class="m-0">
                    <a class="custom-accordion-title d-flex align-items-center ${clsp}" data-bs-toggle="collapse" href="#collapse-${g_followUps[i].id}" aria-expanded="${clsp ? false : true}" aria-controls="collapseThree" style="color: inherit;">
                    ${countFlups+1}. Will run a follow-up at ${moment(followUpDate).format(date_format)} created by ${g_followUps[i].creator_name} ${remTime}</strong>&nbsp;
                    ` + autho + `
                        <span class="ml-auto"><i class="fas fa-chevron-down accordion-arrow"></i></span>
                        
                    </a>
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
        updateFollowUp(form, ticketNotes);
    }
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

        remTime = `(<span style="color: rgb(139, 180, 103)">${remTime}</span>)`;

        return remTime;
    }
}

function updateFollowUp(data, ticketNotes = false) {
    $.ajax({
        type: 'post',
        url: update_followup_route,
        data: data,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            if (!data.success) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            } else {
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
                ticket_notify('ticket_update', 'Follow-up updated');

                // refresh logs
                getLatestLogs();

                getTicketFollowUp();

                if (ticketNotes) get_ticket_notes();
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
                        // send mail notification regarding ticket action
                        ticket_notify('ticket_update', 'Follow-up removed');

                        // refresh logs
                        getLatestLogs();

                        getTicketFollowUp();
                    }

                    Swal.fire({
                        position: 'center',
                        icon: (data.success) ? 'success' : 'error',
                        title: data.message,
                        showConfirmButton: false,
                        timer: swal_message_time
                    });
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

$('#is_recurring').change(function() {
    if ($(this).is(":checked")) {
        $('#recurrence-range').show();
        $('#start-range').show();

        if ($('#schedule_type').val() == 'time') {
            $('#followup-recurrence').css('display', 'block');
        } else if ($('#schedule_type').val() == 'custom') {
            // for custom no need for start date
            $('#start-range').hide();
        }
    } else {
        $('#followup-recurrence').css('display', 'none');
        $('#recurrence-range').hide();
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
    document.getElementById('recurrence_time_div').style.display = 'none';
    document.getElementById('schedule_time_div').style.display = 'none';

    $('#is_recurring').attr('disabled', false);

    if (value == 'custom') {
        document.getElementById('date_picker_div').style.display = 'block';
        if ($('#is_recurring').prop('checked')) {
            $('#followup-recurrence').css('display', 'none');
            $('#recurrence-range').show();
            $('#start-range').hide();
        }
    } else if (value == 'time') {
        document.getElementById('recurrence_time_div').style.display = 'block';

        $('#is_recurring').prop('checked', true);
        $('#is_recurring').trigger('change');
        $('#is_recurring').attr('disabled', true);
    } else {
        document.getElementById('schedule_time_div').style.display = 'block';
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

                        let month_days = moment().set('month', y_month).daysInMonth();
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
                ticket_notify('ticket_followup', 'Follow-up added');

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
    $('#note').css('background-color', color);
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

    var note = $("#note").val();
    let extract_notes_email = note.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);

    let vis = [];
    if (document.getElementById('note-type-user-org').checked || document.getElementById('note-type-user').checked) {
        vis = all_staff_ids
    } else {
        vis = $('#note-visibilty').val();
        if (vis.indexOf('Everyone') > -1) vis = all_staff_ids;
    }

    var formData = new FormData(this);
    formData.append('ticket_id', ticket.id);
    formData.append('color', gl_color_notes);
    formData.append('visibility', vis.toString());
    if ($('#note-id').val()) {
        formData.append('id', $('#note-id').val());
    }

    if (extract_notes_email != null && extract_notes_email != '') {
        formData.append('tag_emails', extract_notes_email.join(','));
    }

    $.ajax({
        type: "POST",
        url: "{{asset('save-ticket-note')}}" ,
        data: formData,
        // async: false,
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

                toastr.success( data.message , { timeOut: 5000 });

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
                toastr.error( data.message , { timeOut: 5000 });
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
                notes = data.notes;
                // console.log(notes , "notes");
                var type = '';

                if (timeouts_list.length) {
                    for (let i in timeouts_list) {
                        clearTimeout(timeouts_list[i]);
                    }
                }

                timeouts_list = [];

                for (let i in notes) {

                    let timeOut = '';
                    let autho = '';
                    // if (notes[i].created_by == loggedInUser_id) {
                    if (loggedInUser_t == 1) {

                        autho = `<div class="ml-auto">

                            <span class="btn btn-icon rounded-circle btn-outline-danger waves-effect fa fa-trash"
                                style= "float:right;cursor:pointer;position:relative;bottom:25px"
                                onclick="deleteTicketNote(this, '` + notes[i].id + `')" ></span>
                        
                            <span class="btn btn-icon rounded-circle btn-outline-primary waves-effect fa fa-edit" 
                                style="float:right;padding-right:5px;cursor:pointer;position:relative;bottom:25px; margin-right:5px"
                                onclick="editNote(${notes[i].id})"></span>

                        
                        </div>`;
                    }

                    if (notes[i].followup_id && notes[i].followUp_date) {
                        let timeOut2 = moment(notes[i].followUp_date).diff(moment(), 'seconds');

                        // set timeout for only 1 day's followups
                        if (moment(notes[i].followUp_date).diff(moment(), 'hours') > 23) continue;

                        if (timeOut2 > 0) {
                            timeOut = timeOut2 * 1000;
                        }
                    }

                    if(notes[i].type == 'Ticket') {
                    
                        type = '<i class="fas fa-clipboard-list"></i>';
                    
                    }else if(notes[i].type == 'User') {

                        type = '<i class="fas fa-user"></i>';

                    }else{
                        type = '<i class="far fa-building"></i>';
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

                    let flup = `<div class="col-12 p-2 my-2 d-flex" id="note-div-` + notes[i].id + `" style="background-color: ` + notes[i].color + `">
                        <div style="margin-right: 10px; margin-left: -8px;">
                            ${user_img}
                        </div>
                        <div class="w-100">
                            <div class="col-12 p-0">
                                <h5 class="note-head"> <strong> ${notes[i].name} </strong> on <span class="small"> ${jsTimeZone(notes[i].created_at)} </span>  ${type} </h5>
                                ` + autho + `
                            </div>
                            <p class="note-details">
                                ${notes[i].note}
                            </p>
                        </div>
                    </div>`;

                    

                    if (timeOut) {
                        timeouts_list.push(setTimeout(function() {
                            $('#v-pills-notes-list').append(flup);
                        }, timeOut));
                    } else {
                        $('#v-pills-notes-list').append(flup);
                    }
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
    console.log(item ,"item");
    if(item != null || item != undefined || item != "") {

        $("#note_title").text("Edit Notes");
        $('#notes_manager_modal').modal('show');
        
        $('#note-id').val(id);

        $("#note-visibilty").val("Everyone").trigger('change');
        $('#save_ticket_note').find('#note').val(item.note != null ? item.note : '');
        $('#save_ticket_note').find('#note').css('background-color', item.color != null ? item.color : '');
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
                data: { id: id },
                success: function(data) {

                    if (data.success) {
                        // send mail notification regarding ticket action
                        // ticket_notify('ticket_update', 'Note removed');

                        // refresh logs
                        getLatestLogs();

                        $(ele).closest('#note-div-' + id).remove();

                        toastr.success( data.message , { timeOut: 5000 });
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
        let name = `<a href="{{url('company-profile')}}/${cust_cmp[0].id}"> ${cust_cmp[0].name} </a>`;
        $('#cst-company').html('Company : ' + name);
        $('#cst-company-name').html('Company Line : ' + cust_cmp[0].phone);
    } else {
        $('#cst-company').html('');
        $('#cst-company-name').html('');
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
                    toastr.success( 'Flagged Successfully!' , { timeOut: 5000 });
                } else {
                    ticket.is_flagged = 0;
                    toastr.success( 'Flagged removed Successfully!' , { timeOut: 5000 });
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

                $('#ticket-logs-list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#ticket-logs-list').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
                    "searching": true,
                    columns: [
                        {
                            "render": function(data, type, full, meta) {
                                return full.id != null ? full.id : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.action_perform != null ? full.action_perform+' at '+ moment(full.created_at).format($('#sys_date_format').val() + ' ' + 'hh:mm A') : '-';
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
            // console.log(data , "assignee");
            let obj = data.status;
            let obj_user = data.users;

            let option = ``;
            let select = ``;
            let open_sts = '';
            for(var i =0; i < obj.length; i++) {
                if(obj[i].name == 'Open'){
                    open_sts = obj[i].id;
                }
                option +=`<option value="`+obj[i].id+`" data-color="`+obj[i].color+`">`+obj[i].name+`</option>`;
            }
            $("#status").html(select + option);
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
                $('#status').val(ticket.status); // Select the option with a value of '1'
                $('#status').trigger('change');
                // return false;
            }else{
                $('#status').val(open_sts); // Select the option with a value of '1'
                $('#status').trigger('change');
            }
            
            
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
}

function showFollowUpModal() {
    $("#follow_up").modal('show');
    $("#schedule_type").val('minutes').trigger('change');

    $("#general").prop("checked",false);
    $('#general_details_div').css('display', 'none')

    $("#notes").prop("checked",false);
    $('#ticket_follow_notes').css('display', 'none')

    $("#is_recurring").prop("checked",false);
    $('#recurrence-range').css('display', 'none')
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
    if(value == 'reply_due') {
        $("#ticket-rep-due").val("");
    }else{
        $("#ticket-res-due").val("");
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
</script>
