<script>
    // Short Codes Script Blade
    $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });

    // for adding record
    $("#addRecord").submit(function(event) {
        event.preventDefault();

        var method = $(this).attr("method");
        var action = $(this).attr("action");


        var code = $("#code").val();
        var desc = $("#desc").val();

        $.ajax({
            type: method,
            url: action,
            data: { code: code, desc: desc },
            dataType: 'json',
            beforeSend: function(data) {
                $('.loader_container').fadeIn();
                $("#processing").show();
                $("#save").hide();
            },
            success: function(data) {
                console.log(data);
                if (data.status_code == 200 && data.success == true) {
                    alertNotification('success', 'Success' ,data.message );

                    setTimeout(() => {
                        $("#ShowModal").modal('hide');
                    }, 800);

                    $("#addRecord")[0].reset();
                    getAllCodes();

                    $("#processing").hide();
                    $("#save").show();
                } else {
                    alertNotification('error', 'Error' ,data.message );
                    $("#processing").hide();
                    $("#save").show();
                }
            },
            complete: function(data) {
                $('.loader_container').fadeOut(500);
                $("#processing").hide();
                $("#save").show();
            },
            failure: function(errMsg) {
                console.log(errMsg);
                $("#processing").hide();
                $("#save").show();
            }
        });

    });

    getAllCodes();
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
            $('#shortCodeTable').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('#shortCodeTable').DataTable({
                data: obj,
                "pageLength": 50,
                "bInfo": false,
                "paging": true,
                columns: [{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.code != null ? full.code : '-';
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return full.description != null ? full.description : '-';
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            let code = full.code != null ? full.code : '-';
                            let desc = full.description != null ? full.description : '-';
                            return `
                            <div class="d-flex justify-content-center">
                                <a style="border-radius:50px;color:white"  title="Edit" type="button" class="mr-2 shadow-sm btn btn-warning btn-sm" onclick="showRecord(` + full.id + `,'` + code + `','` + desc + `')">
                                    <i class="mdi mdi-grease-pencil" aria-hidden="true"></i>
                                </a>
                                
                                <a title="Delete" style="border-radius:50px;color:white" type="button" class="btn shadow-sm btn-danger btn-sm" onclick="deleteRecord(` + full.id + `)">
                                    <i class="fa fa-trash mt-1" aria-hidden="true"></i>
                                </a>
                            </div>`;
                        }
                    },
                ],
            });

            tbl.on('order.dt search.dt', function() {
                tbl.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

        },
        complete: function(data) {
            $('.loader_container').hide();
        },
        error: function(e) {
            console.log(e)
        }
    });
}

function showRecord(id, name, desc) {

    $("#update_code").val(name);
    $("#update_desc").val(desc);
    $("#ShowUpdateModal").modal('show');

    updateRecord(id);
}


function updateRecord(id) {
    $("#updateRecord").submit(function(event) {
        event.preventDefault();

        var method = $(this).attr("method");
        var action = $(this).attr("action");

        var code = $("#update_code").val();
        var desc = $("#update_desc").val();

        $.ajax({
            type: method,
            url: action,
            data: { id: id, code: code, desc: desc },
            dataType: 'json',
            beforeSend: function(data) {
                $('.loader_container').fadeIn();
                $("#update_processing").show();
                $("#update").hide();
            },
            success: function(data) {
                console.log(data);
                if (data.status_code == 200 && data.success == true) {
                    alertNotification('success', 'Success' ,data.message );

                    setTimeout(() => {
                        $("#ShowUpdateModal").modal('hide');
                    }, 800);

                    $("#updateRecord")[0].reset();
                    getAllCodes();

                    $("#update_processing").hide();
                    $("#update").show();
                } else {
                    alertNotification('error', 'Error' ,data.message );
                    $("#update_processing").hide();
                    $("#update").show();
                }
            },
            complete: function(data) {
                $('.loader_container').fadeOut(500);
                $("#update_processing").hide();
                $("#update").show();
            },
            failure: function(errMsg) {
                console.log(errMsg);
                $("#update_processing").hide();
                $("#update").show();
            }
        });

    });
}

function deleteRecord(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: "GET",
        url: "delete_short_codes/" + id,
        dataType: 'json',
        beforeSend: function(data) {
            $('.loader_container').fadeIn();
        },
        success: function(data) {
            console.log(data);

            if (data.status_code == 200 && data.success == true) {
                alertNotification('success', 'Success' ,data.message );
                getAllCodes();
            } else {
                alertNotification('error', 'Error' ,data.message );
            }

        },
        complete: function(data) {
            $('.loader_container').fadeOut(500);
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
</script>