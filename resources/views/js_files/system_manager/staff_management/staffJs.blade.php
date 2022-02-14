<script>
    // Staff Script Blade
    var system_date_format = $("#system_date").val();
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        $('#tags').select2({
            placeholder: 'Tags'
        });
        // $('.select2-search__field').parent().addClass('w-100');
        $('.select2-search__field').addClass('form-control mt-0 pl-2 pr-2');

        users_table_list = $('#user-table-list').DataTable({
            "lengthMenu": [
                [15, 30, 100, -1],
                [15, 30, 100, "All"]
            ]
        });

        // $('#sm_select').multipleSelect({
        //     width: 300,
        //     onClick: function(view) {
        //         var selectedItems = $('#sm_select').multipleSelect("getSelects");
        //         for (var i = 0; i < 6; i++) {
        //             columns = users_table_list.column(i).visible(0);
        //         }
        //         for (var i = 0; i < selectedItems.length; i++) {
        //             var s = selectedItems[i];
        //             users_table_list.column(s).visible(1);
        //         }
        //         $('#contacts_table').css('width', '100%');
        //     },
        //     onCheckAll: function() {
        //         for (var i = 0; i < 6; i++) {
        //             columns = users_table_list.column(i).visible(1);
        //         }
        //     },
        //     onUncheckAll: function() {
        //         for (var i = 0; i < 6; i++) {
        //             columns = users_table_list.column(i).visible(0);
        //         }
        //         $('#contacts_table').css('width', '100%');
        //     }
        // });

        $('a.toggle-vis').on('click', function(e) {
            e.preventDefault();

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            // Get the column API object
            var column = users_table_list.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });

        // get_all_staff_members();

        for (let i in tags) {
            $('#tags').append('<option value="' + tags[i].id + '">' + tags[i].name + '</option>');
        }
    });

// function checkPassword(ele){
//     let score = 0;
//     let password = ele.value;
//     score = (password.length > 6) ? score+2 : score;
//     score = ((password.match(/[a-z]/)) && (password.match(/[A-Z]/))) ? score+2 : score;
//     score = (password.match(/\d+/)) ? score+2 : score;
//     score = (password.match(/.[!,@,#,$,%,^,&,*,?,_,~,-,(,)]/)) ? score+2 : score;
//     score = (password.length > 10) ? score+2 : score;
//     $(".user-password-div .progress .progress-bar").css("width", (score*10)+"%");
// }

function confirmPassword(ele, frm) {
    let password = $('#' + frm).find("input[name='password']").val();
    let confirm_password = ele.value;
    $(ele).parent().find('i').show();
    $(ele).parent().find('i').removeClass("fa-times fa-check text-danger");
    if (password == confirm_password) {
        $(ele).parent().find('i').addClass("fa-check text-success");
    } else {
        $(ele).parent().find('i').addClass("fa-times text-danger");
    }
}

// $(".user-password-div").on('click', '.show-password-btn', function() {
//     // $(this).toggleClass("fa-eye fa-eye-slash");
//     var input = $(".user-password-div input[name='password']");
//     if (input.attr("type") === "password") {
//         input.attr("type", "text");
//     } else {
//         input.attr("type", "password");
//     }
// });

// $(".user-confirm-password-div").on('click','.show-confirm-password-btn',function(){
//     // $(this).toggleClass("fa-eye fa-eye-slash");
//     var input = $(".user-confirm-password-div input[name='confirm_password']");
//     if (input.attr("type") === "password") {
//         input.attr("type", "text");
//     } else {
//         input.attr("type", "password");
//     }
// });

$("input[name='user_photo']").change(function() {
    var imgclass = $("img.profile_pic_img");
    readURL(this, imgclass);
});

function readURL(input, imgclass) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imgclass.attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}



$("#addNewUser").on('hidden.bs.modal', function() {
    // $('.ace-icon').hide();
    $('#tags').val('');
    $('#tags').trigger('change');
    $("#save_user").trigger("reset");
    $('#save_user').find('#staff_id').val('');
    $('#save_user').find('.profile_pic_img').attr('src', user_photo_url + "/user-photo.jpg");
    $('#save_user').find('.user-password-div').parent().show();
    // $('#save_user').find('.user-confirm-password-div').parent().show();
});

$('#btn-add-new-user').click(function() {
    $("#save_user").trigger("reset");
    $('#addNewUser').modal('show');
})

$("#phone").keyup(function() {
    var regex = new RegExp("^[0-9]+$");

    if (!regex.test($(this).val())) {
        $("#phone_error").html("Only numeric values allowed");
    } else {
        $("#phone_error").html(" ");
    }
    if ($(this).val() == '') {
        $("#phone_error").html(" ");
    }
});

$("#sms").keyup(function() {
    var regex = new RegExp("^[0-9]+$");

    if (!regex.test($(this).val())) {
        $("#sms_error").html("Only numeric values allowed");
    } else {
        $("#sms_error").html(" ");
    }
    if ($(this).val() == '') {
        $("#sms_error").html(" ");
    }
});

$("#whatsapp").keyup(function() {
    var regex = new RegExp("^[0-9]+$");

    if (!regex.test($(this).val())) {
        $("#wtsapp_error").html("Only numeric values allowed");
    } else {
        $("#wtsapp_error").html(" ");
    }
    if ($(this).val() == '') {
        $("#wtsapp_error").html(" ");
    }
});

function closeModal() {
    $("#sms_error").html(" ");
    $("#wtsapp_error").html(" ");
    $("#phone_error").html(" ");
    $("#password_error").html(" ");
    $("#email_error").html(" ");
}

function UserImgValidation() {
    let imgValidate = $('#profile_pic').prop('files');
    if (imgValidate.length > 0) {
        imgValidate = imgValidate[0];
        let ext = imgValidate.name.substring(imgValidate.name.lastIndexOf('.') + 1).toLowerCase();
        if (ext != 'jpeg' && ext != 'png' && ext != 'jpg') {
            toastr.error('File type not allowed! Only jpeg/jpg/png Allowed', { timeOut: 5000 });
            return false;
        }

        if (Math.round(imgValidate.size / (1024 * 1024)) > 2) {
            toastr.error('File size exceeds 2MB!', { timeOut: 5000 });
            return false;
        }
    }
}

$("#save_user").submit(function(event) {
    event.preventDefault();

    let imgValidate = $('#profile_pic').prop('files');
    var phone = $("#phone").val();
    var sms = $("#sms").val();
    var whatsapp = $("#whatsapp").val();
    var password = $("#staffpassword").val();
    var email = $("#email").val();

    if (imgValidate.length > 0) {
        imgValidate = imgValidate[0];
        let ext = imgValidate.name.substring(imgValidate.name.lastIndexOf('.') + 1).toLowerCase();
        if (ext != 'jpeg' && ext != 'png' && ext != 'jpg') {
            toastr.error('File type not allowed! Only jpeg/jpg/png Allowed', { timeOut: 5000 });
            return false;
        }

        if (Math.round(imgValidate.size / (1024 * 1024)) > 2) {
            toastr.error('File size exceeds 2MB!', { timeOut: 5000 });
            return false;
        }
    }

    var regex = new RegExp("^[0-9]+$");
    var regex_email = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;

    if(!regex_email.test(email)) {
        $("#email_error").html('Enter a valid email address');
        return false;
      }

    if (!regex.test(phone)) {
        $("#phone_error").html("Only numeric values allowed");
        return false;
    }

    if (password.length <= 7) {
        $("#password_error").html("Password Should be Minimum 8 Characters");
        return false;
    }

    var formData = new FormData($(this)[0]);
    delete formData.tags;
    var action = $(this).attr('action');
    var method = $(this).attr('method');
    var selected = [];
    for (var option of document.getElementById('tags').options) {
        if (option.selected) {
            selected.push(option.value);
        }
    }
    formData.append('tags', selected);

    $.ajax({
        type: method,
        url: action,
        data: formData,
        async: true,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function(data) {
            $("#usr_save").hide();
            $("#usr_pro").show();
            $("#usr_loader").show();
        },
        success: function(data) {
            // console.log(data);
            if (data['success'] == true) {
                $('#addNewUser').modal('hide');
                get_all_staff_members();
                toastr.success(data['message'], { timeOut: 5000 });
                $("#phone_error").html("");
            } else {
                toastr.error(data['message'], { timeOut: 5000 });
            }
        },
        complete: function(data) {
            $("#usr_save").show();
            $("#usr_pro").hide();
            $("#usr_loader").hide();
        },
        error: function(e) {
            // console.log(e);
            $("#usr_save").show();
            $("#usr_pro").hide();
            $("#usr_loader").hide();
        }
    });
});

$("#update_password").submit(function(event) {
    event.preventDefault();
    // console.log($(this)[0]);

    if (!validatePassword($('#update_password').find("input[name='password']").val(), $('#update_password').find("input[name='confirm_password']").val())) {
        return false;
    }

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
        success: function(data) {
            // console.log(data);
            if (data['success'] == true) {
                $('#updateUserPwd').modal('hide');

                $('#update_password').find("input[name='password']").val('');
                $('#update_password').find("input[name='confirm_password']").val('');

                get_all_staff_members();

                showAlertMessage(data['message'], 'success');
            } else {
                showAlertMessage(data['message'], 'error');
            }
        }
    });
});

function validatePassword(p1, p2) {
    if (p1.length < 8) {
        showAlertMessage('Password must be of 8 characters!', 'error');
        return false;
    }
    if (p1 !== p2) {
        showAlertMessage('Passwords do not match!', 'error');
        return false;
    }
    return true;
}

var tag_form = $("#new-tag-add");
tag_form.submit(function() {

    var formData = new FormData($(this)[0]);
    var url = $(this).attr('action');
    var method = $(this).attr('method');

    $.ajax({
        url: url,
        type: method,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function(data) {
            alert("Success");
        },
        failure: function(errMsg) {
            // console.log(errMsg);
        }
    });
    return false;
});

function deleteUsers(id) {
    // console.log(id);
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this user will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                url: delete_user_route,
                data: {
                    id: id
                },
                success: function(data) {
                    $('.__row_'+ id).remove();
                    if (data) {
                        showAlertMessage('User Deleted!', 'success');
                    } else {
                        showAlertMessage('Something went wrong!', 'error');
                    }
                }
            });
        }
    })
}

function showAlertMessage(msg, type) {
    Swal.fire({
        position: 'top-end',
        icon: type,
        title: msg,
        showConfirmButton: false,
        timer: msgTimer
    });
}

function generatePassword() {
    $(".user-password-div input[name='password']").val(generateCode());
}

function generateCode(length = 15) {
    var result = '';
    var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789{},:;[]-+)(*&^%$#@!~<>?|=_';
    var charactersLength = characters.length;
    for (var i = 0; i < length; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}

function get_all_staff_members() {

    $("#user-table-list").DataTable().destroy();
    $.fn.dataTable.ext.errMode = "none";
    $.ajax({
        type: "GET",
        url: get_staff_route,
        success: function(result) {
            if(result.success) {
                var tbl =$("#user-table-list").DataTable({
                    // processing: true,
                    // serverSide: true,
                    searching: true,
                    pageLength: 10,
                    data: result.data,
                    columnDefs: [
                        {
                            orderable: false,
                            targets: 0
                        }
                    ],
                    columns: [
                        {
                            "render": function (data, type, full, meta) {
                                return '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                var path = root + '/' + full.profile_pic;
                                if(full.profile_pic != null) {
                                    return `
                                    <div class="d-flex align-items-center">
                                        <img src="${path}" alt="user Photo"width="35" height="35" class="rounded-circle">
                                        <div class="ml-2">
                                            <div class="user-meta-info">
                                                <h5 class="user-name mb-0"><a href="` + user_profile_route + `/` + full.id+ `">` + full.name + `</a></h5>
                                                <small class="user-work text-muted">${full.role && full.role.hasOwnProperty('name') ? full.role.name : ''}</small>
                                            </div>
                                        </div>
                                    </div>`;
                                }else{
                                    return `
                                    <div class="d-flex align-items-center">
                                        <img src="${js_origin}default_imgs/customer.png" alt="user Photo" width="35" height="35" class="rounded-circle">
                                        <div class="ml-2">
                                            <div class="user-meta-info">
                                                <h5 class="user-name mb-0"><a href="` + user_profile_route + `/` + full.id+ `">` + full.name + `</a></h5>
                                                <small class="user-work text-muted">${full.role && full.role.hasOwnProperty('name') ? full.role.name : ''}</small>
                                            </div>
                                        </div>
                                    </div>`
                                }
            
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.email != null ? full.email : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                let link = `<a href="tel:`+full.phone_number+`"> `+full.phone_number+`</a>`;
                                return full.phone_number != null ? link : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                var tag_arr = [];
            
                                if(full.user_tags != null && full.user_tags != '') {
            
                                    for(var i =0; i < full.user_tags.length; i++) {
            
                                        if( full.user_tags[i].name != null &&  full.user_tags[i].name != '') {
                                            tag_arr.push( full.user_tags[i].name );
                                        }else{
                                            return '-';
                                        }
                                        
                                    }
            
                                    return tag_arr;
            
                                }else{
                                    return '-';
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return moment(full.created_at).format(system_date_format);
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `
                                <button type="button" onclick="deleteUsers(`+full.id+`)" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"  aria-hidden="true"></i>
                                </button>
                                `;
                            }
                        },
                    ]
                });
            } else {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: result.message,
                    showConfirmButton: false,
                    timer: 2500
                });
            }
        },
        complete: function(data) {
            // console.log('Success', data);
        },
        error: function(data) {
            // console.log('Error', data);
        }
    });
}

</script>