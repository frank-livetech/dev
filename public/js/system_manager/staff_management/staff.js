$(document).ready(function() {
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

    $('#sm_select').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#sm_select').multipleSelect("getSelects");
            for (var i = 0; i < 6; i++) {
                columns = users_table_list.column(i).visible(0);
            }
            for (var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                users_table_list.column(s).visible(1);
            }
            $('#contacts_table').css('width', '100%');
        },
        onCheckAll: function() {
            for (var i = 0; i < 6; i++) {
                columns = users_table_list.column(i).visible(1);
            }
        },
        onUncheckAll: function() {
            for (var i = 0; i < 6; i++) {
                columns = users_table_list.column(i).visible(0);
            }
            $('#contacts_table').css('width', '100%');
        }
    });

    $('a.toggle-vis').on('click', function(e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');

        // Get the column API object
        var column = users_table_list.column($(this).attr('data-column'));

        // Toggle the visibility
        column.visible(!column.visible());
    });

    get_users_table_list();

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

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});

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
            console.log(data);
            if (data['success'] == true) {
                $('#addNewUser').modal('hide');
                get_users_table_list();
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
            console.log(e);
            $("#usr_save").show();
            $("#usr_pro").hide();
            $("#usr_loader").hide();
        }
    });
});

$("#update_password").submit(function(event) {
    event.preventDefault();
    console.log($(this)[0]);

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
            console.log(data);
            if (data['success'] == true) {
                $('#updateUserPwd').modal('hide');

                $('#update_password').find("input[name='password']").val('');
                $('#update_password').find("input[name='confirm_password']").val('');

                get_users_table_list();

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

function get_users_table_list() {

    users_table_list.clear().draw();
    $.ajax({
        type: "get",
        url: get_staff_route,
        data: "",
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            var system_date_format = data.date_format;
            var user_arr = data.users;
            console.log(user_arr, "staff data")

            $("#user-table-list tbody").html("");
            var count = 1;
            $.each(user_arr, function(key, val) {
                // console.log(val , "val");
                var status = '';


                var tagsArr = '';
                // if(val['staff_profile'] != null){
                if (val.tags != null) {
                    tagsArr = val.tags.split(",");
                    // }else{
                    //     tagsArr = '';
                    // }
                } else {
                    tagsArr = '';
                }


                var tags_data = '';
                var phone = '---';
                if (!tagsArr.length && !val.tags == null) {
                    tags_data = '---';
                } else {
                    if (!val.tags) {
                        tags_data = '---';
                    } else {
                        var check = 0;
                        //  console.log(tags);
                        for (var j = 0; j < tags.length; j++) {
                            for (var k = 0; k < tagsArr.length; k++) {
                                // console.log(tagsArr[k])
                                if (tagsArr[k] == tags[j]['id']) {

                                    if (check > 0) {
                                        tags_data += ',';
                                    }
                                    tags_data += tags[j]['name'];
                                    check++;
                                    break;
                                }
                            }
                            // echo "<option value='$key'>$val</option>";
                        }
                    }
                }

                if (val.phone_number != null) {
                    phone = val.phone_number;
                }

                let imgsrc = user_photo_url + "/user-photo.jpg";
                if (val.profile_pic) {
                    imgsrc = user_photo_url + "/" + val.profile_pic;
                }
                let role = '';
                for (let i in roles) {
                    if (val.user_type == roles[i]['id']) {
                        role = roles[i]['name'];
                    }
                }

                users_table_list.row.add([

                    // count + '' + status,

                    `<div class="d-flex align-items-center">
                        <img src="` + imgsrc + `" width="35" height="35" class="rounded-circle">\
                        <div class="ml-2">
                            <div class="user-meta-info">
                                <h5 class="user-name mb-0"><a href="` + user_profile_route + `/` + val['id'] + `">` + val['name'] + `</a></h5>
                                <small class="user-work text-muted">` + role + `</small>
                            </div>
                        </div>
                    </div>`,
                    val['email'],
                    phone,
                    tags_data,

                    moment(val['created_at']).format(system_date_format),
                    '<div class="text-center">\
                    <!--<a href="javascript:void(0)" id="btn-edit-' + val.id + '" class="btn btn-circle btn-success" title="Update User Info"><i class="mdi mdi-account-edit pt-1" style="font-weight:900;font-size:20px;"></i></a>\
                    <a href="javascript:void(0)" id="btn-pwd-' + val.id + '" class="btn btn-circle btn-info" title="Update Password"><i class="mdi mdi-account-key pt-1" aria-hidden="true" style="font-weight:900;font-size:20px;"></i></a>-->\
                    <!--<a href="javascript:void(0)" id="btn-pwd-' + val.id + '" class="btn btn-circle btn-info" title="Update Password"><i class="fas fa-user-lock pt-1" aria-hidden="true" ></i></a>-->\
                    <a class="btn btn-circle btn-danger text-white" title="Delete User" onclick="event.stopPropagation();deleteUsers(' + val['id'] + ');return false;"><i class="fa fa-trash pt-1"  aria-hidden="true"></i></a>\
                </div>',

                ]).draw(false);

                $("#user-table-list tbody").on('click', '#btn-edit-' + val.id, function() {
                    $('#save_user').find('.user-password-div').parent().hide();
                    $('#save_user').find('.user-confirm-password-div').parent().hide();

                    $('#save_user').find('.profile_pic_img').attr('src', imgsrc);
                    $('#save_user').find('#full_name').val(val.name);
                    $('#save_user').find('#email').val(val.email);
                    if (val.phone_number) {
                        $('#phone').val(val.phone_number);
                    }
                    if (val.role_id) {
                        $('#role_id').val(val.role_id);
                    }
                    $('#tags').val(tagsArr);
                    $("#tags").trigger("change");
                    $('#status').val(val.status);
                    $('#sms').val(val.sms);
                    $('#whatsapp').val(val.whatsapp);
                    $('#save_user').find('#staff_id').val(val.id);

                    $('#addNewUser').modal('show');
                });

                $("#user-table-list tbody").on('click', '#btn-pwd-' + val.id, function() {
                    $('#update_password').trigger('reset');
                    $('.ace-icon').hide();
                    $('#updateUserPwd').find('.modal-title').html(val.name);
                    $('#update_password').find('#staff_id_pwd').val(val.id);

                    $('#updateUserPwd').modal('show');
                });
                count++;
            });

        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
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
            console.log(errMsg);
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
                    //  console.log(data);
                    if (data) {
                        get_users_table_list();
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