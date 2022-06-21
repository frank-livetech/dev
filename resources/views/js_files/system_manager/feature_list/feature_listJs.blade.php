<script>
    // Feature List Script Blade
    let featurestable = '';
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    get_all_feature_list();

    $('#roles').select2({
        placeholder: 'Select',
        allowClear: true
    });
    $('#edit_roles').select2({
        placeholder: 'Select',
        allowClear: true
    });

    $("#flexRadioDefault1").click(function() {
        $('#toggle_title_field').css("display", "none");
        $('#route-title').css("display", "block");
    })

    $("#flexRadioDefault2").click(function() {
        $('#toggle_title_field').css("display", "block");
        $('#route-title').css("display", "none");
    })

    $("#flexRadioDefault4").click(function() {
        $('#toggle_title_field').css("display", "none");
        $('#edit_route').css("display", "block");

        // 
        $(".prnt_box").css("display", "block");
        $(".title_box").css("display", "block");
        $(".icon_box").css("display", "block");
        $(".chk_box").css("display", "block");
    })

    $("#flexRadioDefault5").click(function() {
        $('#toggle_title_field').css("display", "block");
        $('#edit_route').css("display", "none");

        // 
        $(".prnt_box").css("display", "block");
        $(".title_box").css("display", "block");
        $(".icon_box").css("display", "block");
        $(".chk_box").css("display", "block");
    })

    $("#flexRadioDefault6").click(function() {
        $(".prnt_box").css("display", "none");
        $(".title_box").css("display", "none");
        $(".icon_box").css("display", "none");
        $(".chk_box").css("display", "none");

    })


    // add feature 
    $('#addFeatureForm').submit(function(e) {
        e.preventDefault();

        var menu_name = $('#menu_title').val();
        var menu_route = $('#route').val();
        var menu_seq = $('#sequence').val();
        var parent_id = $('#parent_id').val();
        var menu_icon = $('#icon').val();
        var role_id = "";

        var is_active = 0;
        var menu_routes = "NULL";
        var feature_type = 2;

        if ($("#is_active").is(":checked")) {
            is_active = 1;
        }

        if ($("#flexRadioDefault1").is(":checked")) {
            menu_routes = menu_route;
            feature_type = 1;
        }

        $("#role").each(function() {
            role_id += $(this).val() + ",";
        });

        var formData = {
            title: menu_name,
            route: menu_routes,
            sequence: menu_seq,
            parent_id: parent_id,
            is_active: is_active,
            feature_type: feature_type,
            menu_icon: menu_icon,
            role_id:role_id.replace(/,\s*$/, ""),
        }
        $.ajax({
            type: "POST",
            url: "add_features",
            data: formData,
            success: function(data) {
                // console.log(data);
                alertNotification('success', 'Success' ,data.message );
                setTimeout(() => {
                    $('#addFeatureModal').modal('hide');
                    $('#addFeatureForm')[0].reset();
                }, 800);
                get_all_feature_list();
            },
            error: function(e) {
                console.log(e)
                $('#title_error').html(e.responseJSON.errors.title[0]);
                $('#route_error').html(e.responseJSON.errors.route[0]);
                $('#sequence_error').html(e.responseJSON.errors.sequence[0]);
            }
        });
    });

    // update feature form
    $('#editFeatureForm').submit(function(e) {
        e.preventDefault();

        var id = $("#f_id").val();

        var menu_name = $('#edit_title').val();
        var menu_route = $('#edit_route').val();
        var menu_seq = $('#edit_sequence').val();
        var parent_id = $('#edit_parent_id').val();
        var menu_icon = $('#edit_icon').val();
        var role_id = "";

        var is_active = 0;
        var menu_routes = "NULL";
        var feature_type = 2;

        if ($("#edit_is_active").is(":checked")) {
            is_active = 1;
        } else {
            is_active = 0;
        }

        if ($("#flexRadioDefault4").is(":checked")) {
            menu_routes = menu_route;
            feature_type = 1;
        }

        $("#edit_role").each(function() {
            role_id += $(this).val() + ",";
        });        

        var formData = {
            id:id,
            title: menu_name,
            route: menu_routes,
            sequence: menu_seq,
            parent_id: parent_id,
            is_active: is_active,
            feature_type: feature_type,
            menu_icon: menu_icon,
            role_id:role_id.replace(/,\s*$/, ""),
        }

        $.ajax({
            type: "POST",
            url: "update_feature",
            data: formData,
            success: function(data) {
                // console.log(data, "update ");
                alertNotification('success', 'Success' ,data.message );
                $("#editFeatureModal").modal('hide');
                get_all_feature_list();
            },
            error: function(e) {
                console.log(e)
            }
        });
    });

});


function get_all_feature_list() {

    $.ajax({
        type: "GET",
        url: "features_list",
        dataType: 'json',
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            var data = data.data;
            // console.log(data, "feature list")

            var row = ``;
            var count = 1;
            var root = `<option value="0">Root</option>`;
            var title = ``;



            for (var i = 0; i < data.length; i++) {

                title += `<option value="` + data[i].f_id + `">` + data[i].title + `</option>`;
                let yes = `<span class="badge bg-success text-white">Yes</span>`;
                let no = `<span class="badge bg-danger text-white">No</span>`;
                let roles = data[i].f_rl_arr != null ? data[i].f_rl_arr : '-';

                var keys = [];
                keys = Object.values(roles);
                var x = keys.toString();

                row += `
                    <tr id="row_` + data[i].f_id + `" data-title="` + data[i].title + `" data-route="` + data[i].route + `" 
                    data-seq="` + data[i].sequence + `" data-active="` + data[i].is_active + `" data-parent="` + data[i].parent_id + `"
                    data-icon="` + data[i].menu_icon + `">
                        <td>` + count + `</td>
                        <td>` + data[i].title + `</td>
                        <td>` + (data[i].route == "NULL" ? '-' : data[i].route) + `</td>
                        <td>` + (data[i].is_active == 1 ? yes : no) + `</td>
                        <td>` + x + `</td>
                        <td>` + data[i].sequence + `</td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <button onclick="show_single_feature_list(` + data[i].f_id + `)" type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;"> <i class="fas fa-pencil-alt"></i></button>
                            </div>
                        </td>
                    </tr>
                `;

                count++;
            }

            $('#Feature_body').html(row);
            var feature_table = $('#Feature_table').DataTable();

            $('#feature_select').multipleSelect({
                width: 300,
                onClick: function(view) {
                    var selectedItems = $('#feature_select').multipleSelect("getSelects");
                    for (var i = 0; i < 8; i++) {
                        columns = feature_table.column(i).visible(0);
                    }
                    for (var i = 0; i < selectedItems.length; i++) {
                        var s = selectedItems[i];
                        feature_table.column(s).visible(1);
                    }
                    $('#Feature_table').css('width', '100%');
                },
                onCheckAll: function() {
                    for (var i = 0; i < 8; i++) {
                        columns = feature_table.column(i).visible(1);
                    }
                },
                onUncheckAll: function() {
                    for (var i = 0; i < 8; i++) {
                        columns = feature_table.column(i).visible(0);
                    }
                    $('#Feature_table').css('width', '100%');
                }
            });
            $("#parent_id").html(root + title);
            $("#edit_parent_id").html(root + title);
        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}


function show_single_feature_list(id) {
    var row = $('#row_' + id);
    $("#f_id").val(id);

    $('#editFeatureModal').modal('show');

    $.ajax({
        type: "GET",
        url: "get_features_by_id/" + id,
        dataType: "json",
        beforeSend: function(data) {
            $(".loader_container").show();
        },
        success: function(data) {
            // console.log(data, "single menu");

            $("#edit_title").val(data.title);
            $("#edit_route").val(data.route);
            $("#edit_sequence").val(data.sequence);
            $("#edit_icon").val(data.menu_icon == null ? '-' : data.menu_icon);
            $("#edit_parent_id").val(data.parent_id)

            if (data.is_active == 1) {
                $("#edit_is_active").prop("checked", true);
            } else {
                $("#edit_is_active").prop("checked", false);
            }

            if (data.route == "NULL") {
                $("#flexRadioDefault5").prop("checked", true);
                $("#edit_route").css("display", "none")
            } else {
                $("#flexRadioDefault5").prop("checked", false);
                $("#flexRadioDefault4").prop("checked", true);
                $("#edit_route").css("display", "block")
            }

            if(data.role_id != null) {
                var roles = data.role_id.split(',');
                $('#edit_role').val(roles).trigger('change');
            }            

        },
        complete: function(data) {
            $(".loader_container").hide();
        },
        error: function(e) {
            console.log(e);
        }
    });

}



</script>