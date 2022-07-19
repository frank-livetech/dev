<script>

var ticket_attach_path = `{{asset('storage')}}`;

    // Staff_profile SCript Blade
    $(document).ready(function(){
        values();


        tickets_table_list = $('#ticket_table').DataTable({
            processing: true,
            // scrollX: true,
            pageLength: 20,
            fixedColumns: true,
            "autoWidth": false,
            'columnDefs': [
                { className: "overflow-wrap", targets: "_all" },
                { width: '10px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: 0 },
                { width: '110px', targets: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13] },
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 }
            ],
            order: [[2, 'asc']],
            createdRow: function(row, data, dataIndex){
                // console.log($(data[1]).attr('class'));
                if($(data[1]).attr('class').match('flagged')){
                    $(row).addClass('flagged-tr');
                }
            }
        });
        // get_ticket_table_list();
    });

function values(){
    var state, country;
    var email = $("#email").val();
    var phone = $("#phone").val();
    var address = $("#address").val();
    var apartment = $("#apt_address").val();
    var city = $("#update_city").val();
    if($("#state option:selected" ).val() == ""){
        state = "";
    }
    else{
        state = $("#state option:selected" ).text();
    }
    if($("#country option:selected" ).val() == ""){
        country = "";
    }
    else{
        country = $("#country option:selected" ).text();
    }
    var zip = $("#update_zip").val();
    $("#adrs").html("");
    $('#adrs').append ('  <small class="text-muted pt-4 db">Phone</small> ' +
                '<h6><a href="tel:'+phone+'">'+phone+'</a></h6>'+
                '<small class="text-muted">Email address </small>'+
                '<h6><a href="tel:'+email+'">'+email+'</a></h6>'+
                '<small class="text-muted">Address </small>'+
                '<h6>'+address+'</h6>'+
                '<h6>'+apartment+'</h6>'+
                '<h6>'+city+' ' +state+' '+zip+'</h6>'+
                '<h6>'+country+'</h6>'+

                '<hr>');
}
function ShowCertificateModel() {
    $("#save-certification").trigger("reset");
    $('#add-new-certificate').modal('show');

}

$("#save-certification").submit(function (event) {

    event.preventDefault();

    var formData = new FormData($(this)[0]);
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
        success: function (data) {

            if (data) {

                $('#add-new-certificate').modal('hide');

                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Certificate Added Successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })

                get_all_certificates();
                $('#add-new-certificate').modal('hide');
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                })
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });

});

// doucment modal shown
function ShowDocumentModel() {
    $("#save-documents").trigger("reset");
    $('#add-new-document').modal('show');
}

// save documents
$("#save-documents").submit(function (event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
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
        success: function (data) {

            if (data) {

                $('#add-new-certificate').modal('hide');

                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Documents Added Successfully!',
                    showConfirmButton: false,
                    timer: 2500
                })
                get_all_documents();
                $('#add-new-document').modal('hide');
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                })
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });
});

$(".user-password-div").on('click', '.show-password-btn', function() {
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $(".user-password-div input[name='password']");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

$(".user-confirm-password-div").on('click','.show-confirm-password-btn',function(){
    $(this).toggleClass("fa-eye fa-eye-slash");
    var input = $(".user-confirm-password-div input[name='confirm_password']");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
    } else {
        input.attr("type", "password");
    }
});

// $("#phone").keyup(function(){
//     var regex = new RegExp("^[0-9]+$");

//     if(!regex.test($(this).val())) {
//         $("#phone_error").html("Only numeric values allowed");
//     }else{
//         $("#phone_error").html(" ");
//     }
//     if($(this).val() == '') {
//         $("#phone_error").html(" ");
//     }
// });


// change password checkbox
$("#change_password_checkbox").click(function() {

    $(this).is(":checked") ?
    $('.change_password_row').show() :
    $('.change_password_row').hide();

});

// upload profile pic -> show modal
$("#uploadProfilePic").click(function () {
    $('#editPicModal').modal('show');
    $('.modalImg').show();
    $("#hung22").hide()
});


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



$("#update_user").submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    let user_id = $("#user_id").val()

    if($('.change_password_checkbox').is(":checked")){
        var update_password = $('#update_password').val();
        let cpwd = $("#confirm_password").val();

        if(update_password != cpwd) {
            alertNotification('error', 'Error' , 'Passwords do not match!');
            return false;
        }
    }


    var fb = $("#update_fb").val();
    var pin = $("#update_pinterest").val();
    var twt = $("#update_twt").val();
    var insta = $("#update_ig").val();
    var link = $("#update_linkedin").val();
    var website = $("#website").val();

    if( fb != '') {
        var FBurl = /^(http|https)\:\/\/www.facebook.com|facebook.com\/.*/i;
        if(!fb.match(FBurl)) {
            alertNotification('error', 'Error' , 'Provide a valid facebook link');
            return false;
        }
    }

    if( pin != '') {
        var FBurl = /^(http|https)\:\/\/www.pinterest.com|pinterest.com\/.*/i;
        if(!pin.match(FBurl)) {
            alertNotification('error', 'Error' , 'Provide a valid Pinterest link');
            return false;
        }
    }
    if( twt != '') {
        var FBurl = /^(http|https)\:\/\/www.twitter.com|twitter.com\/.*/i;
        if(!twt.match(FBurl)) {
            alertNotification('error', 'Error' , 'Provide a valid Twitter link');
            return false;
        }
    }
    if( insta != '') {
        var FBurl = /^(http|https)\:\/\/www.instagram.com|instagram.com\/.*/i;
        if(!insta.match(FBurl)) {
            alertNotification('error', 'Error' , 'Provide a valid Instagram link');
            return false;
        }
    }
    if( link != '') {
        var FBurl = /^(http|https)\:\/\/www.linkedin.com|linkedin.com\/.*/i;
        if(!link.match(FBurl)) {
            alertNotification('error', 'Error' , 'Provide a valid :Linkedin link');
            return false;
        }
    }


    var pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
    var urlregex = new RegExp(pattern);

    if(website != '') {
        if(!urlregex.test(website)) {
            $("#website_error").html("Invalid URL..!");
            return false;
        }else{
        $("#website_error").html(" ");
        }
    }



    // var formData = $("#update_user").serialize()  + "&user_id=" + user_id;
    // var content = tinyMCE.activeEditor.getContent();
    var content = quill.root.innerHTML;

    tinyContentEditor(content, 'signature').then(function() {
        content = $('#tinycontenteditor').html();

        $.ajax({
            type: "post",
            url: "{{asset('upload_editor_docs')}}",
            data: {
                id:user_profile.id,
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



    var formData = new FormData(this);
    formData.append('user_id' , user_id);
    formData.append('signature' , $('#tinycontenteditor').html());

    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        dataType:'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend:function(data) {
            $("#usr_btn").hide();
            $("#usr_process").show();
            $("#usr_loader").show();
            $("#usr_prf_loader").show();
            $("#usr_lnk_loader").show();
        },
        success: function (data) {
            // console.log(data);
            if(data.code == 200 && data.success == true) {
                values();
                alertNotification('success', 'Success' , data.message);



                // hide change password row & uncheck password checkbox
                if($('.change_password_checkbox').is(":checked")){
                    $('.change_password_checkbox').prop("checked" , false);
                    $('.change_password_row').hide()
                }

                $('.change_password_checkbox').prop("checked" , false);
                $('.change_password_row').hide();

                $("#staff_address").text($("#address").val());
                $("#staff_apt_address").text(", " +$("#apt_address").val());
                $("#staff_city").text($("#update_city").val());
                $("#staff_zip").text( ", " +$("#update_zip").val());
                $("#staff_phone").text($("#phone").val());
                $("#staff_name").text($("#full_name").val());
                $("#job_title_bg").text($("#job_title").val())

                var state = $("#state").find("option:selected").text();
                $("#staff_state").text(state == "Select State" ? ' ' : ", " + state);

                var country = $("#country").find("option:selected").text();
                $("#staff_coun").text(country == "Select Country" ? ' ' : ", " + country);

                $("#twt_link").attr('href', $("#update_twt").val());
                $("#fb_link").attr('href', $("#update_fb").val());
                $("#insta_link").attr('href', $("#update_ig").val());
                $("#pint_link").attr('href', $("#update_pinterest").val());
                $("#linkedin_link").attr('href', $("#update_linkedin").val());
                $("#website_link").attr('href', $("#update_website").val());



            }else{
                alertNotification('error', 'Error' , data.message);
            }
        },
        complete:function(data) {
            $("#usr_btn").show();
            $("#usr_process").hide();
            $("#usr_loader").hide();
            $("#usr_prf_loader").hide();
            $("#usr_lnk_loader").hide();
        },
        error: function (e) {
            console.log(e);
            $("#usr_btn").show();
            $("#usr_process").hide();
            $("#usr_loader").hide();
            $("#usr_prf_loader").hide();
            $("#usr_lnk_loader").hide();

            if (e.responseJSON.errors.password != null) {
                alertNotification('error', 'Error' , e.responseJSON.errors.password[0]);
            }
        }
    });


    // var update_country = $("#update_country").val();
    // var update_state = $("#update_state").val();
    // var update_city = $("#update_city").val();
    // var update_zip = $("#update_zip").val();
    // var phone = $("#phone").val();

    // var coun = checkEmptyFields(update_country, $('#err'));
    // var stte = checkEmptyFields(update_state, $('#err1'));
    // var cty = checkEmptyFields(update_city, $('#err2'));
    // var zp = checkZipCode(update_zip, $('#err3'));

    // if(coun && stte && cty && zp == true) {}
});


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

            if (src.includes('storage/' + action + '/' + user_profile.id)) {
                // name = baseName(src) + '.' + ext;
            } else {
                $(this).attr('src', ticket_attach_path + `/${action}/${user_profile.id}/${name}`);
            }
            attachments_src.push([name, src]);
        }

    });


    return await res;
}


function checkEmptyFields(input, err) {
    if(input == '') {
        err.html("this field is required");
        return false;
    }else{
        err.html("");
        return true;
    }
}

function checkZipCode(input, err) {
    if(input == '') {
        err.html("this field is required");
        return false;
    }else if(input.length < 5 || input.length > 5) {
        err.html("5 digit allow only");
        return false;
    }else{
        err.html("");
        return true;
    }
}

function ShowTicketModel(){
    $('#dept_id').val('').trigger("change");
    $('#status').val('').trigger("change");
    $('#priority').val('').trigger("change");
    $('#type').val('').trigger("change");
    $('#customer_id').val('').trigger("change");
    $("#save_tickets").trigger("reset");
    $('#new-customer').css('display', 'none');
    $('#ticket').modal('show');
}

$("#save_tickets").submit(function (event) {
    event.preventDefault();

    var formData = new FormData($(this)[0]);
    var action = $(this).attr('action');
    var method = $(this).attr('method');

    var subject = $('#subject').val().replace(/\s+/g, " ").trim();
    var dept_id = $('#dept_id').val();
    var status = $('#status').val();
    var priority = $('#priority').val();
    var assigned_to = $('#assigned_to').val();
    var type = $('#type').val();
    var customer_id = $('#customer_id').val();
    var ticket_detail = $('#ticket_detail').val();
    if(subject == '' || subject == null){
        $('#select-subject').css('display', 'block');
        return false;
    }else if(dept_id == '' || dept_id == null){
        $('#select-department').css('display', 'block');
        return false;
    }
    else if(status == '' || status == null){
        $('#select-status').css('display', 'block');
        return false;
    }
    else if(priority == '' || priority == null){
        $('#select-priority').css('display', 'block');
        return false;
    }
    else if(assigned_to == '' || assigned_to == null){
        $('#select-assign').css('display', 'block');
        return false;
    }
    else if(type == '' || type == null){
        $('#select-type').css('display', 'block');
        return false;
    }
    else if(ticket_detail == '' || ticket_detail == null){
        $('#pro-details').css('display', 'block');
        return false;
    }
    if(!$('#new-form').prop('checked')){
        if(customer_id == '' || customer_id == null){
        $('#select-customer').css('display', 'block');
        return false;}
    }
    if ($('#new-form').prop('checked')) {

        var first_name = $('#first_name').val();
        var last_name = $('#last_name').val();
        var phone = $('#phone').val();
        var email = $('#email').val();
        var is_new = true;

        if (first_name == '' || first_name == null) {
            $('#save-firstname').css('display', 'block');
            return false;

        } else if (last_name == '' || last_name == null) {
            $('#save-lastname').css('display', 'block');
            return false;
        } else if (phone == '' || phone == null) {
            $('#save-number').css('display', 'block');
            return false;
        } else if (email == '' || email == null) {
            $('#save-email').css('display', 'block');
            return false;
        }
    }

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function (data) {

            if (data.success) {

                $('#ticket').modal('hide');
                $("#save_tickets").trigger("reset");
                $('#dept_id').val('').trigger("change");
                $('#status').val('').trigger("change");
                $('#priority').val('').trigger("change");
                $('#assigned_to').val('').trigger("change");
                $('#type').val('').trigger("change");
                $('#customer_id').val('').trigger("change");
                $('#new-customer').css('display', 'none');
                // get_ticket_table_list();
            }
            Swal.fire({
                position: 'top-end',
                icon: (data.success) ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: 2500
            })
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });

});

function get_ticket_table_list() {
    tickets_table_list.clear().draw();
    $('#btnDelete,#btnBin').show();
    $('#btnBack,#btnRecycle').hide();
    $.ajax({
        type: "get",
        url: get_user_tickets_route,
        data: {user_id: user_profile.id},
        dataType: 'json',
        cache: false,
        success: function (data) {
            // console.log(data.tickets);
            ticketsList = data.tickets;

            // $('#my_tickets_count').html(ticketsList.length);
            // $('#open_tickets_count').html(ticketsList.filter(itm => itm.status == open_status_id).length);
            // $('#closed_tickets_count').html(ticketsList.filter(itm => itm.status == closed_status_id).length);

            listTickets();
        }
    });
}

function listTickets(){
    var count = 1;

    let ticket_arr = ticketsList;

    tickets_table_list.clear().draw();

    $.each(ticket_arr, function (key, val) {
        var json = JSON.stringify(data[key]);

        let prior = val['priority_name'];
        if(val['priority_color']){
            prior = '<div class="text-center text-white" style="background-color: '+val['priority_color']+';">'+val['priority_name']+'</div>';
        }
        let flagged = '';
        if(val['is_flagged'] == 1){
            flagged = 'flagged';
        }

        let custom_id = val['coustom_id'];
        if(Array.isArray(ticket_format)){
            ticket_format = ticket_format[0];
        }

        if(ticket_format.ticket_format == 'sequential'){
            custom_id = val['seq_custom_id'];
        }
        var name = val['subject'];
            var shortname = '';
            if (name.length > 20) {
                shortname = name.substring(0, 20) + " ...";
            }else{
                shortname = name;
            }

            tickets_table_list.row.add([
                '<input type="checkbox" name="chk_list[]" value= "'+val['id']+'">',
                '<div class="text-center '+flagged+'"><span class="fas fa-flag" style="cursor:pointer;" onclick="flagTicket(this, '+val['id']+');"></span></div>',
                val['status_name'],
                '<a href="'+ticket_details_route+'/'+val['id']+ '">' + shortname + '</a>',
                custom_id,
                prior,
                (val.hasOwnProperty('customer_name')) ? val['customer_name'] : '---',
                val['lastReplier'],
                val['replies'],
                '---',
                '---',
                '---',
                (val.hasOwnProperty('tech_name')) ? val['tech_name'] : '---',
                val['department_name'],
                val['created_at'],
            ]).draw(false);
            count++;
    });
}

function flagTicket(ele, id){
    $.ajax({
        type:'post',
        url: flag_ticket_route,
        data:{id:id},
        success: function (data) {

            if (data) {

                $(ele).closest('tr').toggleClass('flagged-tr');

            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Something went wrong!',
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        },
        failure: function (errMsg) {
            console.log(errMsg);
            Swal.fire({
                position: 'top-end',
                icon: 'error',
                title: errMsg,
                showConfirmButton: false,
                timer: 2500
            });
        }
    });
}

// Handle click on "Select all" control
$('#select-all').on('click', function() {
    // Get all rows with search applied
    var rows = customerTable.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
});

// Handle click on checkbox to set state of "Select all" control
$('#ticket_table tbody').on('change', 'input[type="checkbox"]', function() {
    // If checkbox is not checked
    if (!this.checked) {
        var el = $('#select-all').get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if (el && el.checked && ('indeterminate' in el)) {
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
        }
    }
});


// save ticket geenral info
$("#ticketGeneralForm").submit(function (event) {
    event.preventDefault();
    event.stopPropagation();

    let user_id = $("#user_id").val()


    var formData = new FormData(this);
    formData.append('user_id' , user_id);

    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        dataType:'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend:function(data) {
            $("#gen-btn").hide();
            $("#loaderBtn").show();
        },
        success: function (data) {
            // console.log(data);
            if(data.status_code == 200 && data.success == true) {
                alertNotification('success', 'Success' , data.message);

            }else{
                alertNotification('error', 'Error' , data.message);
            }
        },
        complete:function(data) {
            $("#gen-btn").show();
            $("#loaderBtn").hide();
        },
        error: function (e) {
            $("#gen-btn").show();
            $("#loaderBtn").hide();
            console.log(e);
        }
    });
});
</script>
