<script>
// template Blade Script
let counter = 0;
let g_count = 1;
let g_obj_key = null;
let g_temp_code = null;
let temp_fields_list = [];
let fields_list_data = [];
let check_action = null;
let active_edit_field_id = 0;
let template = {
    text: {
        title: 'Input ',
        type: 'text',
        setting: ``
    },
    email: {
        title: 'Email',
        type: 'email',
        setting: ``
    },
    phone: {
        title: 'Phone',
        type: 'phone',
        setting: ``
    },
    password: {
        title: 'Password ',
        type: 'password',
        setting: ``
    },
    ipv4: {
        title: 'IPv4 ',
        type: 'ipv4',
        setting: ``
    },
    url: {
        title: 'URL ',
        type: 'url',
        setting: ``
    },
    address: {
        title: 'Address ',
        type: 'text',
        setting: ``
    },
    textbox: {
        title: 'Textarea',
        type: 'textbox',
        setting: ``
    },
    selectbox: {
        title: 'Select (Dropdown)',
        type: 'selectbox',
        setting: `<div class="form-group">
            <div class="form-check form-check-inline">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="is_single" name="radio-stacked" checked>
                    <label class="custom-control-label" for="is_single">Single</label>
                </div>
            </div>
            <div class="form-check form-check-inline">
                <div class="custom-control custom-radio">
                    <input type="radio" class="custom-control-input" id="is_multi" name="radio-stacked">
                    <label class="custom-control-label" for="is_multi">Multiple</label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Options</label>
            <div class="row" id="select-options">
                <div class="col-12 form-group d-flex">
                    <input type="text" class="form-control optVals" placeholder="Option Value" required>
                    <button class="btn btn-danger waves-effect waves-light ml-2" type="button" onclick="this.parentNode.remove();">
                        <i class="ti-close"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="button" class="btn btn-info waves-effect waves-light float-right" id="addOption">Add Option</button>
                </div>
            </div>
        </div>`
    }
}


$(function() {
    try {
        var btnContainer = document.getElementById("modalContainer");
        var btns = btnContainer.getElementsByClassName("border-cyan");
        for (var i = 0; i < btns.length; i++) {
            btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("activa");
                this.className += " activa";
            });
        }
        $(".buttonPush").on('click', function() {
            if (temp_fields_list.indexOf($(this).data('template_name')) > -1) {
                temp_fields_list.splice(temp_fields_list.indexOf($(this).data('template_name')), 1);
                $(this).find('.activa').removeClass('activa');
            } else {
                temp_fields_list.push($(this).data('template_name'));
            }
        });
    } catch (err) {
        console.log(err)
    }


    $('#fields-form').on('submit', saveFieldSetting);

    $(document).on('click', '#addOption', function() {
        $('#select-options').append(`<div class="col-12 form-group d-flex">
            <input type="text" class="form-control optVals" placeholder="Option Value" required>
            <button class="btn btn-danger waves-effect waves-light ml-2" type="button" onclick="this.parentNode.remove();">
                <i class="ti-close"></i>
            </button>
        </div>`);
    });

    $('.modal-dialog').draggable({
        handle: ".modal-header"
    });

    $(".connectedSortable").sortable({
        connectWith: ".connectedSortable",
        opacity: 1,
        items: '.appends',
        placeholder: "highlight",
        dropOnEmpty: false,
        start: function(event, ui) {
            ui.item.toggleClass("highlight");
        },
        stop: function(event, ui) {
            ui.item.toggleClass("highlight");
        },
    }).disableSelection();

    $(document).on("sortupdate", ".connectedSortable", function(event, ui) {
        // console.log({event, ui});

        let appends = $(this).find('.appends').length;

        if (appends > 0 && appends <= 4) {
            setRowAppends(this);
        } else if (appends > 4) {
            $(ui.item[0]).appendTo(ui.sender[0]);
            $(ui.sender[0]).insertAfter('#' + $(this).attr('id'));

            setRowAppends(ui.sender[0]);
        }
    });
});

function fieldAdd(code) {
    $( /*html*/ `<div class="row connectedSortable border firstfield" id="sortable-row-${g_count}">
        <div class="col-md-12 appends" data-id="${g_count}" data-col="12">
            <div class="card card-hover m-1 style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;"">
                <div class="card-body" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                    <div class="d-flex justify-content-between">
                        <div class="title">
                            <h5 class="card-title small mb-0"><i class="fas fa-grip-vertical pr-2" style="color:grey;"></i> ${template[code].title}</h5>
                        </div>
                        <div class="actions" style="position:absolute; top:18px;right:8px">
                            <i onclick="removeField(${g_count},null, this)" class="fas fa-trash-alt red float-right pl-3" style="cursor: pointer;"></i>
                            <a href="javascript:templateSetting('${code}', ${g_count})" class="float-right">
                            <i class="fas fa-cog"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`).insertBefore('#sortable-row-last');

    $('.connectedSortable').show();

    fields_list_data[g_count] = { col_width: 12 };

    $("#sortable-row-" + g_count).sortable({
        connectWith: ".connectedSortable",
        opacity: 1,
        items: '.appends',
        placeholder: "highlight",
        dropOnEmpty: false,
        start: function(event, ui) {
            ui.item.toggleClass("highlight");
        },
        // revert: true,
        stop: function(event, ui) {
            ui.item.toggleClass("highlight");
            $('.connectedSortable').each(element => {
                if ($(this).find('.appends').length == 0) {
                    $(this).remove();
                }
            });
        },
    }).disableSelection();

    g_count++;
    $('.head').hide();
    $('.tail').show();
    $('.activa').removeClass('activa');
}

function templateSetting(code, obj_key , action = null , id = null) {

    if(action != null) {
        check_action = action
        active_edit_field_id = id
        g_temp_code = code;
        g_obj_key = obj_key;
        let item = fields_list_data.find(item => item.id == id);
        if(item != null) {

            $("#lbl").val( item.label );
            $("#ph").val( item.placeholder );
            $("#desc").val( item.description );

            if(item.required == 1) {
                $("#is_required").prop("checked", true);
            }else{
                $("#is_required").prop("checked", false);
            }
            let dropdown_options = ``;
            if(item.options != null) {

                let options = item.options.split('|');
                if(options.length > 0) {

                    for(let data of options) {
                        dropdown_options+= `
                            <div class="col-12 form-group d-flex">
                                <input type="text" class="form-control optVals" value="${data}" placeholder="Option Value" required="">
                                <button class="btn btn-danger waves-effect waves-light ml-2" type="button" onclick="this.parentNode.remove();">
                                    <i class="ti-close"></i>
                                </button>
                            </div>
                        `;
                    }
                }
                let html = `
                <div id="dyn-data"><div class="form-group">
                    <div class="form-check form-check-inline">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="is_single" name="radio-stacked" ${item.is_multi == 0 ? 'checked' : ''}>
                            <label class="custom-control-label" for="is_single">Single</label>
                        </div>
                    </div>
                    <div class="form-check form-check-inline">
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="is_multi" name="radio-stacked" ${item.is_multi == 1 ? 'checked' : ''}>
                            <label class="custom-control-label" for="is_multi">Multiple</label>
                        </div>
                    </div>
                    </div>
                    <div class="form-group">
                        <label>Options</label>

                        <div class="row" id="select-options"> ${dropdown_options} </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-info waves-effect waves-light float-right" id="addOption">Add Option</button>
                            </div>
                        </div>

                    </div>
                </div>`;

                $("#dyn-data").html(html);
            }else{
                $("#dyn-data").empty();
            }

        }

        $('#fields-modal #headinglabel').html(template[code].title + ' Setting');
        $('#fields-modal #lbl').focus();
        $('#fields-modal').modal('show');

    }else{

        g_obj_key = obj_key;
        g_temp_code = code;

        $('#fields-modal #dyn-data').html('');
        $('#fields-modal #ph').parent().show();
        if (code == 'selectbox') {
            // setting setup
            $('#fields-modal #dyn-data').html(template[code].setting);
        }
        if (Object.keys(fields_list_data[obj_key]).length) {
            if (fields_list_data[obj_key].is_multi) $('#fields-modal #is_multi').prop('checked', true);
            else $('#fields-modal #is_single').prop('checked', true);

            $('#fields-modal #lbl').val(template[code].title);

            $('#fields-modal #ph').val(fields_list_data[obj_key].placeholder);
            $('#fields-modal #desc').val(fields_list_data[obj_key].description);

            if (fields_list_data[obj_key].hasOwnProperty('options') && fields_list_data[obj_key].options != null) {
                if (fields_list_data[obj_key].options.length > 1) {
                    for (let i = 1; i < fields_list_data[obj_key].options.length; i++) {
                        $('#fields-modal #addOption').trigger('click');
                    }
                }

                $('#fields-modal .optVals').each(element => {
                    $('#fields-modal').find('.optVals')[element].value = fields_list_data[obj_key].options[element];
                });
            }

            if (fields_list_data[obj_key].required) $('#fields-modal #is_required').prop('checked', true);
        }

        if (g_temp_code == 'password') {
            $("#is_required").prop("checked", true);
            $("#is_required").attr("disabled", true);
        } else {
            $("#is_required").prop("checked", false);
            $("#is_required").removeAttr("disabled");
        }


        $('#fields-modal #headinglabel').html(template[code].title + ' Setting');
        $('#fields-modal #lbl').focus();
        $('#fields-modal').modal('show');
    }
}

function removeField(f_ind,el_id=null,el) {
    // delete fields_list_data[f_ind];
    if ($(el).closest('.connectedSortable').find('.appends').length == 1) {
        $(el).closest('.connectedSortable').remove();
    } else {
        let e = $(el).closest('.connectedSortable');
        $(el).closest('.appends').remove();
        setRowAppends(e);
    }

    $.ajax({
        type: 'post',
        url: template_update_submit_route,
        data: {form_field:true,field_id:el_id},
        success: function(data) {
            if (data.success) {
                getAllTemplate();
                getFormsTemplates();
                $("#fields-modal").modal('hide');
            }
            Swal.fire({
                position: 'center',
                icon: (data.success) ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: swal_message_time,
            });
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });
}

function setRowAppends(el) {
    let appends = $(el).find('.appends').length;
    if ($(el).attr('id') == 'sortable-row-start' || $(el).attr('id') == 'sortable-row-last') {
        appends -= 1;
    }
    if (appends == 1) {
        $(el).find('.appends').removeClass('col-md-6 col-md-4 col-md-3');
        $(el).find('.appends').addClass('col-md-12');
        $(el).find('.appends').data('col', '12');
    } else if (appends == 2) {
        $(el).find('.appends').removeClass('col-md-12 col-md-4 col-md-3');
        $(el).find('.appends').addClass('col-md-6');
        $(el).find('.appends').data('col', '6');
    } else if (appends == 3) {
        $(el).find('.appends').removeClass('col-md-12 col-md-6 col-md-3');
        $(el).find('.appends').addClass('col-md-4');
        $(el).find('.appends').data('col', '4');
    } else if (appends == 4) {
        $(el).find('.appends').removeClass('col-md-12 col-md-6 col-md-4');
        $(el).find('.appends').addClass('col-md-3');
        $(el).find('.appends').data('col', '3');
    }

    $(el).find('.appends').each(function(item) {
        if ($(this).data('id') >= 0) fields_list_data[$(this).data('id')].col_width = $(this).data('col');
    });
}

function updateFieldSetting(field_id,template_id){

    var formData = {};
    formData['field_id'] = field_id;
    formData['template_id'] = template_id;
    formData['label'] = $('#fields-modal #lbl').val();
    formData['placeholder'] = $('#fields-modal #ph').val();
    formData['desc'] = $('#fields-modal #desc').val();

    if ($('#fields-modal #is_required').prop('checked')) formData['required'] = 1;
    else formData['required'] =  0;

    if ($('#fields-modal #is_multi').prop('checked')) formData['is_multi'] = 1;
    else formData['is_multi'] = 0;

    if (g_temp_code == 'selectbox') {
       var selec_option = [];
        $('#fields-modal').find('.optVals').each(element => {
            selec_option.push($('#fields-modal').find('.optVals')[element].value);
        });

        formData['selec_option'] = selec_option;
    }


    $.ajax({
        type: 'post',
        url: template_update_submit_route,
        data: formData,
        success: function(data) {
            console.log(data)
            if (data.success) {
                getAllTemplate();
                getFormsTemplates();
                $("#fields-modal").modal('hide');
            }
            Swal.fire({
                position: 'center',
                icon: (data.success) ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: swal_message_time,
            });
        },
        failure: function(errMsg) {
            console.log(errMsg);
        }
    });



}

function saveFieldSetting(ev) {
    ev.preventDefault();
    ev.stopPropagation();

    if(check_action != null){
        updateFieldSetting(active_edit_field_id,g_obj_key);
    }else{
        //check action variable will be null after save fields
        check_action = null;
        fields_list_data[g_obj_key].type = g_temp_code;
        if ($('#fields-modal #is_multi').prop('checked')) fields_list_data[g_obj_key].is_multi = 1;
        else fields_list_data[g_obj_key].is_multi = 0;

        fields_list_data[g_obj_key].label = $('#fields-modal #lbl').val();
        fields_list_data[g_obj_key].placeholder = $('#fields-modal #ph').val();
        fields_list_data[g_obj_key].description = $('#fields-modal #desc').val();
        if ($('#fields-modal #is_required').prop('checked')) fields_list_data[g_obj_key].required = 1
        else fields_list_data[g_obj_key].required = 0;

        if (g_temp_code == 'selectbox') {
            fields_list_data[g_obj_key].options = [];
            $('#fields-modal').find('.optVals').each(element => {
                fields_list_data[g_obj_key].options.push($('#fields-modal').find('.optVals')[element].value);
            });
        }
        // update the labeled card
        $('#card-colors').find('div[data-id="' + g_obj_key + '"]').find('.card-title').html('<i class="fas fa-grip-vertical pr-2" style="color:grey;"></i>' + $('#fields-modal #lbl').val());

        // settings set change background to white
        $('#card-colors').find('div[data-id="' + g_obj_key + '"]').find('.card').css('background-color', 'white');

        $('#fields-modal').modal('hide');
        $('#fields-form').trigger('reset');
    }

}

function saveTemplate() {

    if (!$('#tempTitle').val()) {
        alertNotification('error', 'Error' , 'Template Title Required');
        $('#tempTitle').focus();
        $("#tempTitle").css('border','1px solid red')
        return false;
    }

    for (let i in fields_list_data) {
        if (!fields_list_data[i].hasOwnProperty('label')) {
            $('#card-colors').find('div[data-id="' + i + '"]').find('.card').css('background-color', 'rgb(255, 200, 0, 0.43)');
            return false;
        }
    }

    let fields_in_seq = [];
    $('#card-colors').find('.appends').each(function(item) {
        if ($(this).data('id') >= 0) fields_in_seq.push(fields_list_data[$(this).data('id')]);
    });

    if (!fields_in_seq.length) fields_in_seq = fields_list_data;

    let formData = new FormData();
    formData.append('title', $('#tempTitle').val());
    formData.append('fields', JSON.stringify(fields_in_seq));

    let value = $("#sortable-row-start").next().attr('class');

    if( value.includes("firstfield")  ) {

        $.ajax({
            type: 'post',
            url: template_submit_route,
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                $("#tempTitle").removeAttr('style');
                if (data.success) {
                    getFormsTemplates();
                    getAllTemplate();
                    $('.connectedSortable').each(function() {
                        if ($(this).attr('id') == 'sortable-row-start' || $(this).attr('id') == 'sortable-row-last') {
                            $(this).find('.appends').each(function(i) {
                                if (i > 0) {
                                    $(this).remove();
                                }
                            })
                        } else {
                            $(this).remove();
                        }
                    });
                    fields_list_data = [];
                    g_obj_key = null;
                    g_temp_code = null;
                    g_count = 0;
                    $('#tempTitle').val('');
                }
                Swal.fire({
                    position: 'center',
                    icon: (data.success) ? 'success' : 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time,
                });
            },
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });


    }else{
        alertNotification('error', 'Error' , 'Input Field is Required');
        return false;
    }
}
</script>
