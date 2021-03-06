let notes = [];
let timeouts_list = [];
let g_listFlupsTimer = null; // followups refresh timer
let g_followUp_timeouts = [];
let cc__emails = [];
let prv_clicked = null;
var loggedInUser_id = $("#loggedInUser_id").val();
let edit_reply_mode = false;
let attachments_src = [];

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

$(function() {
    // settle company name and phone values
    setCustomerCompany();

    console.log(companies_list, "companies_list");

    tickets_logs_list = $('#ticket-logs-list').DataTable({
        ordering: false
    });
    getLatestLogs();

    $('#ticket-timestamp').html(moment(ticket.created_at).format('MM/DD/YY hh:mm A'));
    if ($("#mymce").length > 0) {
        tinymce.init({
            selector: "textarea#mymce",
            // theme: "modern",
            height: 300,
            mobile: {
                theme: 'silver'
              },
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",
            // file_picker_types: 'file image media',
            // media_live_embeds: true,
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                if (meta.filetype == 'image') input.setAttribute('accept', 'image/*');
                if (meta.filetype == 'media') input.setAttribute('accept', 'audio/*,video/*');

                input.onchange = function() {
                    console.log({ cb, value, meta });
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = async function() {
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.editors.mymce.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];

                        if (reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                            base64 = await downloadPNGFromAnyImageSrc(reader.result);
                        }

                        console.log(reader);

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
            console.log(error);
            listReplies();
        });
    }
    if ($("#ticket_details_edit").length > 0) {
        tinymce.init({
            selector: "textarea#ticket_details_edit",
            // theme: "modern",
            mobile: {
                theme: 'silver'
              },
            height: 300,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                if (meta.filetype == 'image') input.setAttribute('accept', 'image/*');
                if (meta.filetype == 'media') input.setAttribute('accept', 'audio/*,video/*');

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

    $('#ticket_details_p').html(ticket_details.ticket_detail);

    get_ticket_notes();
    getTicketFollowUp();

    getFormsTemplates();

    $("#save_ticket_follow_up").on('submit', createFollowUp);

    setSlaPlanDeadlines();

    var overdue_color = $("#tkt_overdue_color").val();

    var reply_due = $("#sla-rep_due").text();
    var resolution_due = $("#sla-res_due").text();

    if (reply_due.includes('Overdue')) {
        $("#card-sla").css('background', overdue_color);
        $("#card-sla").css('color', '#fff');
    }

    if (resolution_due.includes('Overdue')) {
        $("#card-sla").css('background', overdue_color);
        $("#card-sla").css('color', '#fff');
    }
});

function setSlaPlanDeadlines(ret = false) {
    let res_due = '';
    let rep_due = '';
    let resetable = false;

    if (ticket_slaPlan.title != 'No SLA Assigned') {
        if (ticket.hasOwnProperty('resolution_deadline') && ticket.resolution_deadline) {
            // use ticket reset deadlines
            res_due = moment(moment(ticket.resolution_deadline).toDate()).local();
        } else if (ticket_slaPlan.due_deadline) {
            // use sla deadlines
            let hm = ticket_slaPlan.due_deadline.split('.');
            res_due = moment(moment(ticket.sla_deadline_from).toDate()).local().add(hm[0], 'hours');
            if (hm.length > 1) res_due.add(hm[1], 'minutes');
        }

        if (res_due) {
            // overdue or change format of the date
            if (res_due.diff(moment(), "seconds") < 0) {
                resetable = true;
                res_due = `<span class="text-center" style="color:red;">Overdue</span>`;
            } else {
                // do date formatting
                // res_due = res_due.format('YYYY-MM-DD hh:mm');
                res_due = getClockTime(res_due, 1);
            }
        }

        if (ticket.hasOwnProperty('reply_deadline') && ticket.reply_deadline) {
            // use ticket reset deadlines
            rep_due = moment(moment(ticket.reply_deadline).toDate()).local();
        } else if (ticket_slaPlan.reply_deadline) {
            let hm = ticket_slaPlan.reply_deadline.split('.');
            rep_due = moment(moment(ticket.sla_deadline_from).toDate()).local().add(hm[0], 'hours');
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
                // rep_due = rep_due.format('YYYY-MM-DD hh:mm');
                rep_due = getClockTime(rep_due, 1);
            }
        }
    }

    if (ret) return { rep_due: rep_due, res_due: res_due };

    if (rep_due) $('#sla-rep_due').html(rep_due);
    if (res_due) $('#sla-res_due').html(res_due);

    if (!rep_due && !res_due) $('.sla-selc').hide();
    else $('.sla-selc').show();

    // any deadline is overdue can be reset
    if (resetable) {
        if (ticket_slaPlan.hasOwnProperty('bg_color') && ticket_slaPlan.bg_color) {
            $("#card-sla").css('background-color', ticket_slaPlan.bg_color);
            $("#card-sla").css('color', '#fff');
        }
    } else {
        $("#card-sla").css('background-color', 'white');
        $("#card-sla").css('color', '#000');
    }
}

function resetSlaPlan() {
    $("#reset_sla_plan_modal").modal("show");
}

function updateDeadlines() {
    let rep_deadline = $('#ticket-rep-due').val();
    let res_deadline = $('#ticket-res-due').val();

    if (!rep_deadline && !res_deadline) {
        Swal.fire({
            position: 'center',
            icon: "error",
            title: "Please enter date to reset!",
            showConfirmButton: false,
            timer: swal_message_time
        });
        return false;
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
                $('#sla_plan_reset_form').trigger("reset");

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
                            let dds = setSlaPlanDeadlines();
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

function changeVisibility(val) {
    if (val == 'Ticket') {
        $('#note-visibilty').prop('disabled', false);
    } else {
        $('#note-visibilty').prop('disabled', true);
    }
}

function editRequest() {
    $('#ticket_subject_heading').css('display', 'none');
    $('#ticket_details_p').css('display', 'none');
    $('#edit_request_btn').css('display', 'none');
    $('#ticket_subject_edit_div').css('display', 'block');
    $('#ticket_details_edit_div').css('display', 'block');
    $('#save_request_btn').css('display', 'block');
    $('#cancel_request_btn').css('display', 'block');

    $('#ticket_subject_edit').val(ticket.subject);
     $('#ticket_details_edit').val(ticket.ticket_detail);
    tinyMCE.editors.ticket_details_edit.setContent($('#ticket_details_p').html());
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
    $('#tinycontenteditor').find('source').each(function(index) {
        let src = $(this).attr('src');
        let ext = 'mp4';

        let validVid = true;

        let marker = '.';

        if (src.includes('base64')) marker = '/';

        if (src.includes(marker + 'mp4') || src.includes(marker + 'MP4')) {
            ext = "mp4";
        } else if (src.includes(marker + 'mpeg4') || src.includes(marker + 'MPEG4')) {
            ext = "mpeg4";
        } else if (src.includes(marker + 'mov') || src.includes(marker + 'MOV')) {
            ext = "mov";
        } else if (src.includes(marker + 'avi') || src.includes(marker + 'AVI')) {
            ext = "avi";
        } else if (src.includes(marker + 'wmv') || src.includes(marker + 'WMV')) {
            ext = "wmv";
        } else if (src.includes(marker + 'flv') || src.includes(marker + 'FLV')) {
            ext = "flv";
        } else if (src.includes(marker + 'mp3') || src.includes(marker + 'MP3')) {
            ext = "mp3";
        } else if (src.includes(marker + 'mpeg') || src.includes(marker + 'MPEG')) {
            ext = "mpeg";
        } else {
            $(this).remove();
            validVid = false;
        }

        if (src.includes('base64')) {
            src = src.replace(/^data:.+;base64,/, '');
        }

        if (validVid) {
            let name = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index + '.' + ext;

            if (src.includes(ticket_attach_path_search + '/' + action + '/' + ticket.id)) {
                name = baseName(src) + '.' + ext;
            } else {
                $(this).attr('src', ticket_attach_path + `/${action}/${ticket.id}/${name}`);
            }
            attachments_src.push([name, src]);
        }
    });
    $('#tinycontenteditor').find('a').each(function(index) {
        let href = $(this).attr('href');
        if (href.includes('blob:')) {
            res = getBlobFromUrl(href).then(fromBlobToBase64).then(src => {
                // result will contain file encoded in base64
                console.log(src);
                let ext = 'txt';
                let validFile = true;
                let marker = '.';
                if (src.includes('base64')) marker = '/';

                if (src.includes(marker + 'plain') || src.includes(marker + 'PLAIN')) {
                    ext = "txt";
                } else if (src.includes(marker + 'octet-stream') || src.includes(marker + 'OCTET-STREAM')) {
                    ext = "sql";
                } else if (src.includes(marker + 'pdf') || src.includes(marker + 'PDF')) {
                    ext = "pdf";
                } else if (src.includes(marker + 'vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                    ext = "docx";
                } else if (src.includes(marker + 'vnd.openxmlformats-officedocument.spreadsheetml.sheet')) {
                    ext = "xlsx";
                } else if (src.includes(marker + 'x-zip-compressed')) {
                    ext = "zip";
                } else if (src.includes(marker + 'x-msdownload')) {
                    ext = "exe";
                } else if (src.includes(marker + 'x-gzip')) {
                    ext = "tar.gz";
                } else if (src.includes(marker + 'msaccess')) {
                    ext = "accdb";
                } else if (src.includes(marker + 'vnd.ms-publisher')) {
                    ext = "pub";
                } else if (src.includes(marker + 'msword')) {
                    ext = "rtf";
                } else {
                    $(this).remove();
                    validFile = false;
                }

                if (src.includes('base64')) {
                    src = src.replace(/^data:.+;base64,/, '');
                }

                if (validFile) {
                    let name = 'Live-tech_' + moment().format('YYYY-MM-DD-HHmmss') + '_' + index + '.' + ext;

                    if (src.includes(ticket_attach_path_search + '/' + action + '/' + ticket.id)) {
                        name = baseName(src) + '.' + ext;
                    } else {
                        $(this).attr('href', ticket_attach_path + `/${action}/${ticket.id}/${name}`);
                    }
                    attachments_src.push([name, src]);
                }
            })
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
                    $('#tinycontenteditor').html('');

                    // send mail notification regarding ticket action
                    ticket_notify('ticket_update', 'Subject updated');

                    // refresh logs
                    getLatestLogs();

                    var d = flashy('Initial Request Updated Successfully!', {
                        type: 'flashy__success',
                        stop: true
                    });

                    $('#ticket_subject_heading').css('display', 'block');
                    $('#ticket_details_p').css('display', 'block');
                    $('#edit_request_btn').css('display', 'block');
                    $('#ticket_subject_edit_div').css('display', 'none');
                    $('#ticket_details_edit_div').css('display', 'none');
                    $('#save_request_btn').css('display', 'none');
                    $('#cancel_request_btn').css('display', 'none');

                    $('#ticket_subject_heading').text('Subject : ' + $('#ticket_subject_edit').val());
                    $('#ticket_details_p').html(content);
                }
            }
        });
    });
}

function listReplies() {
    $('#ticket-replies').html('');
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
            $('#ticket-replies').append(`
                <li class="media">
                    <img class="mr-3" src="${user_photo_url}" width="60" alt="Profile Image">
                    <div class="media-body">
                        <h5 class="mt-0 mb-1">From <span class="text-primary">` + reply.name + `</span> <span style="font-family:Rubik,sans-serif;font-size:12px;font-weight: 100;">on ` + moment(reply.date).format('MM/DD/YYYY hh:mm A') + `</span><span class="fa fa-edit" style="cursor: pointer;" onclick="editReply('${index}')"></span></h5>
                        <div class="" id="reply-html-` + reply.id + `">
                            ` + reply.reply + `
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

function publishReply(ele, type = 'publish') {
    var content = tinyMCE.editors.mymce.getContent();
    tinyContentEditor(content, 'replies').then(function() {
        content = $('#tinycontenteditor').html();

        if (!content || content == '<p></p>') {
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Please type some reply.',
                showConfirmButton: false,
                timer: swal_message_time
            });
            $('#reply').css('display', 'block');
            return false;
        } else {
            $(ele).attr('disabled', true);
            $(ele).find('.spinner-border').show();

            let params = {
                cc: $('#to_mails').val(),
                attachments: attachments_src,
                reply: content,
                ticket_id: ticket.id,
                type: type
            };
            if (edit_reply_mode !== false) {
                params.id = ticketReplies[edit_reply_mode].id;
            }

            console.log(params);

            $.ajax({
                type: "post",
                url: publish_reply_route,
                data: params,
                dataType: 'json',
                enctype: 'multipart/form-data',
                cache: false,
                success: function(data) {

                    $(ele).attr('disabled', false);
                    $(ele).find('.spinner-border').hide();

                    if (data.success) {
                        $('#tinycontenteditor').html('');

                        let draft = false;
                        if (edit_reply_mode !== false) {
                            ticketReplies[edit_reply_mode] = data.data;
                            ticketReplies[edit_reply_mode].reply = content;
                        } else {
                            data.data.reply = content;
                            draft = ticketReplies.push(data.data);
                        }

                        listReplies();

                        // $('#reply-html-' + data.id).find('img').attr('width', 120);
                        // $('#reply-html-' + data.id).find('img').attr('height', 120);
                        // $('#reply-html-' + data.id).find('img').css('margin', '0 8px 8px 0');
                        if (type == 'publish') {
                            tinyMCE.editors.mymce.setContent('');
                            document.getElementById('compose-reply').classList.toggle('d-none');
                            // document.getElementById('to_mails').value = '';
                            $('#to_mails').tagsinput()[0].removeAll();

                            // edit_reply_mode = draft;

                            ticket.sla_deadline_from = moment();
                            setSlaPlanDeadlines();
                        }

                        let msg = 'Added';
                        if (edit_reply_mode !== false) msg = 'Updated';
                        if (type != 'publish') msg = 'saved as draft';
                        var d = flashy('Reply ' + msg + ' Successfully!', {
                            type: 'flashy__success',
                            stop: true
                        });
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
    if (edit_reply_mode !== false) cancelReply();

    document.getElementById('compose-reply').classList.toggle('d-none');

    for (let i in ticketReplies) {
        if (ticketReplies[i].is_published === 0) {
            editReply(i);
            $('#draft-rply').show();
        }
    }
}

function editReply(rindex) {
    tinyMCE.editors.mymce.setContent(ticketReplies[rindex].reply);

    $('#draft-rply').hide();
    $('#cancel-rply').show();

    document.getElementById('compose-reply').classList.remove('d-none');

    edit_reply_mode = rindex;
}

function cancelReply() {
    edit_reply_mode = false;

    document.getElementById('compose-reply').classList.add('d-none');

    tinyMCE.editors.mymce.setContent('');

    $('#cancel-rply').hide();
    $('#draft-rply').show();
}

$('#dept_id').change(function() {
    var dept_id = $(this).val();

    // no dept change to do update
    if (dept_id == ticket.dept_id) return false;

    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {
            dept_id: dept_id,
            id: ticket.id,
            action_performed: 'Department'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.success == true) {
                ticket.dept_id = dept_id;

                // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Deptartment updated');

                // refresh logs
                getLatestLogs();

                var d = flashy('Departments Updated Successfully!', {
                    type: 'flashy__success',
                    stop: true
                });
            } else {
                $('#dept_id').val(ticket.dept_id).trigger('change');

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
});

$('#assigned_to').change(function() {
    var assigned_to = $(this).val() ? $(this).val() : null;

    // no change to do update
    if (assigned_to == ticket.assigned_to) return false;

    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {
            assigned_to: assigned_to,
            id: ticket.id,
            action_performed: 'Ticket Assign Tech'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.success == true) {
                ticket.assigned_to = assigned_to;
                // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Assignment updated');

                // refresh logs
                getLatestLogs();

                var d = flashy('Tech Lead Updated Successfully!', {
                    type: 'flashy__success',
                    stop: true
                });
            }
        }
    });
});

$('#type').change(function() {
    var type = $(this).val();

    // no change to do update
    if (type == ticket.type) return false;

    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {
            type: type,
            id: ticket.id,
            action_performed: 'Ticket Type'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.success == true) {
                ticket.type = type;
                // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Type updated');

                // refresh logs
                getLatestLogs();

                var d = flashy('Type Updated Successfully!', {
                    type: 'flashy__success',
                    stop: true
                });
            }
        }
    });
});

$('#status').change(function() {
    var status = $(this).val();

    // no change to do update
    if (status == ticket.status) return false;

    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {
            status: status,
            id: ticket.id,
            action_performed: 'Ticket Status'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.success == true) {
                ticket.status = status;
                // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Status updated');

                // refresh logs
                getLatestLogs();

                var d = flashy('Status Updated Successfully!', {
                    type: 'flashy__success',
                    stop: true
                });
            }
        }
    });
});

$('#priority').change(function() {
    var priority = $(this).val();

    // no change to do update
    if (priority == ticket.priority) return false;

    $.ajax({
        type: "post",
        url: update_ticket_route,
        data: {
            priority: priority,
            id: ticket.id,
            action_performed: 'Ticket Priority'
        },
        dataType: 'json',
        cache: false,
        success: function(data) {
            if (data.success == true) {
                ticket.priority = priority;
                // send mail notification regarding ticket action
                ticket_notify('ticket_update', 'Priority updated');

                // refresh logs
                getLatestLogs();

                var d = flashy('Priority Updated Successfully!', {
                    type: 'flashy__success',
                    stop: true
                });
            }
        }
    });
});

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

            flups += `<div class="card-header" id="followup-${g_followUps[i].id}" style="color:blackbackground-color: rgba(0, 0, 0, .0);">
                <h5 class="m-0">
                    <a class="custom-accordion-title d-flex align-items-center ${clsp}" data-toggle="collapse" href="#collapse-${g_followUps[i].id}" aria-expanded="${clsp ? false : true}" aria-controls="collapseThree" style="color: inherit;">
                    ${countFlups+1}. Will run a follow-up at ${moment(followUpDate).format('DD MMMM YYYY hh:mm A')} created by ${g_followUps[i].creator_name} ${remTime}</strong>&nbsp;
                    ` + autho + `
                        <span class="ml-auto"><i class="mdi mdi-chevron-down accordion-arrow"></i></span>
                        
                    </a>
                </h5>
            </div>
            <div id="collapse-${g_followUps[i].id}" class="${clsp ? 'collapse' : 'show'}  flpcollapse" aria-labelledby="followup-${g_followUps[i].id}" data-parent="#accordion">
                <div class="card-body">
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
        if (rem && rem.hasOwnProperty('minutes') && rem.minutes > 0) remTime += rem.minutes + 'min';

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
            // console.log(data);

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
            console.log(errMsg);
        }
    });
}

function selectColor(color) {
    // alert("color");
    gl_color_notes = color;
    $('#note').css('background-color', color);
}

function visibilityOptions() {
    let values = $('#note-visibilty').val();
    if (values.indexOf('Everyone') > -1 && values.length > 1) {
        $('#note-visibilty').val('Everyone').trigger('change');
    }
}

$("#save_ticket_note").submit(function(event) {
    event.preventDefault();

    var note = $("#note").val();
    let extract_notes_email = note.match(/([a-zA-Z0-9._-]+@[a-zA-Z0-9._-]+\.[a-zA-Z0-9._-]+)/gi);

    let vis = [];
    if (document.getElementById('note-type-user-org').checked || document.getElementById('note-type-user').checked) {
        $('#note-visibilty').val('');
    } else {
        vis = $('#note-visibilty').val();
        if (vis.indexOf('Everyone') > -1) vis = all_staff_ids;
    }

    var formData = new FormData($(this)[0]);
    formData.append('ticket_id', ticket.id);
    formData.append('color', gl_color_notes);
    formData.append('visibility', vis.toString());
    if ($('#note-id').val()) {
        formData.append('id', $('#note-id').val());
    }

    if (extract_notes_email != null && extract_notes_email != '') {
        formData.append('tag_emails', extract_notes_email.join(','));
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
            // console.log(data);

            if (data.success) {
                // send mail notification regarding ticket action
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
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
                get_ticket_notes();

                ticket.sla_deadline_from = moment();
                setSlaPlanDeadlines();

                $('#notes_manager_modal').modal('hide');

                $('#note-visibilty').val('Everyone').trigger('change');
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
            console.log(errMsg);
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

                if (timeouts_list.length) {
                    for (let i in timeouts_list) {
                        clearTimeout(timeouts_list[i]);
                    }
                }

                timeouts_list = [];

                for (let i in notes) {
                    let timeOut = '';
                    let autho = '';
                    if (notes[i].created_by == loggedInUser_id) {
                        autho = `<div class="ml-auto">
                            <span class="fas fa-edit text-primary ml-2" onclick="editNote(this, ` + (i) + `)" style="cursor: pointer;"></span>
                            
                            <span class="fas fa-trash text-danger" onclick="deleteTicketNote(this, '` + notes[i].id + `')" style="cursor: pointer;"></span>
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

                    let flup = `<div class="col-12 p-2 my-2 d-flex" id="note-div-` + notes[i].id + `" style="background-color: ` + notes[i].color + `">
                        <div class="pr-2">
                            <img src="${profile_img_path}" alt="User" width="40">
                        </div>
                        <div class="w-100">
                            <div class="col-12 p-0 d-flex">
                                <h5 class="note-head">Note by ` + notes[i].name + ` ` + moment(notes[i].created_at).format('YYYY-MM-DD HH:mm:ss') + ` (` + notes[i].type + `)</h5>
                                ` + autho + `
                            </div>
                            <p class="note-details">` + notes[i].note + `</p>
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
            console.log(errMsg);
        }
    });
}

function editNote(ele, index) {
    gl_color_notes = notes[index].color;
    $('#save_ticket_note').find('#note-id').val(notes[index].id);

    if (notes[index].visibility) {
        let vals = all_staff_ids.filter(x => notes[index].visibility.indexOf(x) > -1);
        if (vals.length == all_staff_ids.length) vals = 'Everyone';

        console.log(vals);
        $('#save_ticket_note').find('#note-visibilty').val(vals).trigger('change');
    } else {
        $('#save_ticket_note').find('#note-visibilty').val('').trigger('change');
    }
    $('#save_ticket_note').find('#note').val(notes[index].note);
    $('#save_ticket_note').find('#note').css('background-color', gl_color_notes);
    $('#save_ticket_note').find('.form-check-input[value="' + notes[index].type + '"]').prop('checked', true);

    changeVisibility(notes[index].type);

    $('#notes_manager_modal').modal('show');
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
                        ticket_notify('ticket_update', 'Note removed');

                        // refresh logs
                        getLatestLogs();

                        $(ele).closest('#note-div-' + id).remove();
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

function setCustomerCompany() {
    let cust_cmp = companies_list.filter(item => { return item.id == ticket_customer.company_id });
    if (cust_cmp.length) {
        $('#cst-company').html('Company : ' + cust_cmp[0].name);
        $('#cst-company-name').html('Company Line : ' + cust_cmp[0].phone);
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

                ticket_notify('ticket_update', nn);

                // refresh logs
                getLatestLogs();

                if (ticket.is_flagged === 0) {
                    ticket.is_flagged = 1;
                    flashy('Flagged Successfully!', {
                        type: 'flashy__success',
                        stop: true
                    });
                } else {
                    ticket.is_flagged = 0;
                    flashy('Flag removed Successfully!', {
                        type: 'flashy__success',
                        stop: true
                    });
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
            console.log(errMsg);
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
                console.log(data);
                tickets_logs_list.clear().draw();

                for (let i = 0; i < data.logs.length; i++) {
                    const element = data.logs[i];

                    tickets_logs_list.row.add([
                        element.id,
                        element.action_perform
                    ]).draw(false).node();
                }
            } else {
                console.log(data.message);
            }
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}

function ticket_notify(template, action_name, data_id = '') {
    if (asset_ticket_id && template) {
        $.ajax({
            type: 'POST',
            url: ticket_notify_route,
            data: { id: asset_ticket_id, template: template, action: action_name, data_id: data_id },
            success: function(data) {
                if (!data.success) {
                    console.log(data.message);

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
                console.log(errMsg);
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

            //get image/png from convas
            console.log("Image Loaded");
            resolve(canvas.toDataURL("image/png"));
        };
        img.src = src;
    });
    return await r;
}