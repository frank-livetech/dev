<script>
    // Roles Management SCript Blade
    let rolestables = '';
$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    rolestables = $('#roles_table').DataTable();

    $('#role_select').multipleSelect({
        width: 300,
        onClick: function(view) {
            var selectedItems = $('#role_select').multipleSelect("getSelects");
            for (var i = 0; i < 3; i++) {
                columns = rolestables.column(i).visible(0);
            }
            for (var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                rolestables.column(s).visible(1);
            }
            $('#roles_table').css('width', '100%');
        },
        onCheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = rolestables.column(i).visible(1);
            }
        },
        onUncheckAll: function() {
            for (var i = 0; i < 3; i++) {
                columns = rolestables.column(i).visible(0);
            }
            $('#roles_table').css('width', '100%');
        }
    });

    $('#add_role_btn').click(function() {
        $('#addRoleModal').modal('show');
    })

    add_role();
    get_all_roles();

    $('a.toggle-vis').on('click', function(e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');

        // Get the column API object
        var column = rolestables.column($(this).attr('data-column'));

        // Toggle the visibility
        column.visible(!column.visible());
    });

});

function add_role() {

    $('#addRoleForm').submit(function(e) {
        e.preventDefault();
        var role_name = $('#role_name').val();

        if (role_name == '') {
            $('#role_error').html("This field is required");
            return false;
        } else {
            $.ajax({
                type: "POST",
                url: "add_roles",
                data: { name: role_name },
                success: function(data) {
                    console.log(data);
                    get_all_roles();
                    alertNotification('success', 'Success' ,data.message );
                    $('#addRoleForm')[0].reset();
                    setTimeout(() => {
                        $('#addRoleModal').modal('hide');
                        $('#role_error').html("");
                    }, 800);
                },
                error: function(e) {
                    console.log(e)
                }
            });
        }
    })
}

function get_all_roles() {
    rolestables.clear().draw();
    $.ajax({
        type: "GET",
        url: "get_roles",
        dataType: 'json',
        success: function(data) {
            console.log(data);
            var row = ``;
            var count = 1;


            for (var i = 0; i < data.length; i++) {
                rolestables.row.add([
                    // count,
                    data[i].name,
                    `<div class="d-flex">
                        <button title="Edit role" onclick="showSingleRecord(` + data[i].id + `,'` + data[i].name + `')" class="btn btn-warning btn-sm mr-2 rounded"><i class="mdi mdi-account-edit font-20"></i></button>
                        <button title="Delete role" onclick="deleteRole(` + data[i].id + `)" class="btn btn-danger rounded btn-sm"><i class="mdi mdi-delete font-20"></i></button>
                    </div>`

                ]).draw(false);
                // row += `
                //     <tr>
                //         <td>` + count + `</td>
                //         <td>` + data[i].name + `</td>
                //         <td>
                // <div class="d-flex">
                //     <button title="Edit role" onclick="updateRole(` + data[i].id + `,'` + data[i].name + `')" class="btn btn-warning btn-sm mr-2 rounded"><i class="mdi mdi-account-edit font-20"></i></button>
                //     <button title="Delete role" onclick="deleteRole(` + data[i].id + `)" class="btn btn-danger rounded btn-sm"><i class="mdi mdi-delete font-20"></i></button>
                // </div>
                //         </td>
                //     </tr>
                // `;
                count++;
            }

            // $('#roles_body').html(row);

        },
        error: function(e) {
            console.log(e)
        }
    });
}

function showSingleRecord(id, name) {

    $('#edit_role_name').val(name);
    $('#editRoleModal').modal('show');

    updateRecord(id);
}

function updateRecord(id) {
    $('#editRoleForm').submit(function(e) {
        e.preventDefault();
        var role_name = $('#edit_role_name').val();

        if (role_name == '') {
            $('#edit_role_error').html("This field is required");
            return false;
        } else {
            $.ajax({
                type: "post",
                url: "update_roles/" + id,
                data: { name: role_name },
                success: function(data) {
                    console.log(data);
                    get_all_roles();
                    alertNotification('success', 'Success' ,data.message );
                    setTimeout(() => {
                        $('#editRoleModal').modal('hide');
                    }, 800);
                },
                error: function(e) {
                    console.log(e)
                }
            });
        }
    })
}

function deleteRole(id) {
    $.ajax({
        type: "GET",
        url: "delete_roles/" + id,
        dataType: 'json',
        success: function(data) {
            console.log(data);
            get_all_roles();
            alertNotification('success', 'Success' ,data.message );
        },
        error: function(e) {
            console.log(e)
        }
    });
}
</script>