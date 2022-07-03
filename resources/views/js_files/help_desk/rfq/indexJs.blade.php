<script>
    let categoriesArr = '';
    let tagsArr = [{
        "id": 1,
        "name": "FortiNet",
        "created_by": 2,
        "updated_by": 0,
        "created_at": "2020-09-02T03:00:00.000000Z",
        "updated_at": "2020-09-05T18:40:44.000000Z"
    }, {
        "id": 2,
        "name": "Internal",
        "created_by": 2,
        "updated_by": 0,
        "created_at": "2020-09-02T03:00:00.000000Z",
        "updated_at": "2020-09-05T18:40:44.000000Z"
    }, {
        "id": 3,
        "name": "BotGuard",
        "created_by": 2,
        "updated_by": 0,
        "created_at": "2020-09-02T03:00:00.000000Z",
        "updated_at": "2020-09-05T18:40:44.000000Z"
    }, {
        "id": 4,
        "name": "MailWasher",
        "created_by": 2,
        "updated_by": 0,
        "created_at": "2020-09-02T03:00:00.000000Z",
        "updated_at": "2020-09-05T18:40:44.000000Z"
    }, {
        "id": 5,
        "name": "Tag1",
        "created_by": 2,
        "updated_by": 2,
        "created_at": "2020-09-08T09:38:38.000000Z",
        "updated_at": "2020-09-08T09:38:38.000000Z"
    }, {
        "id": 6,
        "name": "Tag 2",
        "created_by": 2,
        "updated_by": 2,
        "created_at": "2020-09-08T09:39:10.000000Z",
        "updated_at": "2020-09-08T09:39:10.000000Z"
    }, {
        "id": 7,
        "name": "Tag 3",
        "created_by": 2,
        "updated_by": 2,
        "created_at": "2020-09-08T09:39:45.000000Z",
        "updated_at": "2020-09-08T09:39:45.000000Z"
    }, {
        "id": 8,
        "name": "Cytracom",
        "created_by": 2,
        "updated_by": 2,
        "created_at": "2020-09-08T09:40:04.000000Z",
        "updated_at": "2020-09-08T09:40:04.000000Z"
    }, {
        "id": 9,
        "name": "My Tag Edited",
        "created_by": 2,
        "updated_by": 2,
        "created_at": "2020-09-08T09:40:55.000000Z",
        "updated_at": "2020-09-08T09:40:55.000000Z"
    }, {
        "id": 10,
        "name": "Web Dev",
        "created_by": 2,
        "updated_by": 2,
        "created_at": "2020-09-08T09:41:57.000000Z",
        "updated_at": "2020-09-08T09:41:57.000000Z"
    }];
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $(document).ready(function() {

        tinymce.init({
            selector: '#mymce',
            mobile: {
            theme: 'silver'
          },
        });

        var company = $("#comp_id option:selected").text();
        $("#company").val(company);


        $(".sellInst").hide();

        $("#checkAll").click(function() {
            $('input:checkbox.contacts').not(this).prop('checked', this.checked);
        });
        // contacts_table_list = $('#contacts_table').DataTable({
        //     pageLength: 25
        // });

        // $('#vendor_column').multipleSelect({
        //     width: 300,
        //     onClick: function(view) {
        //         var selectedItems = $('#vendor_column').multipleSelect("getSelects");
        //         for (var i = 0; i < 8; i++) {
        //             columns = contacts_table_list.column(i).visible(0);
        //         }
        //         for (var i = 0; i < selectedItems.length; i++) {
        //             var s = selectedItems[i];
        //             contacts_table_list.column(s).visible(1);
        //         }
        //         $('#contacts_table').css('width', '100%');
        //     },
        //     onCheckAll: function() {
        //         for (var i = 0; i < 8; i++) {
        //             columns = contacts_table_list.column(i).visible(1);
        //         }
        //     },
        //     onUncheckAll: function() {
        //         for (var i = 0; i < 8; i++) {
        //             columns = contacts_table_list.column(i).visible(0);
        //         }
        //         $('#contacts_table').css('width', '100%');
        //     }
        // });


        $('a.toggle-vis').on('click', function(e) {
            e.preventDefault();

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');

            // Get the column API object
            var column = contacts_table_list.column($(this).attr('data-column'));

            // Toggle the visibility
            column.visible(!column.visible());
        });

        get_vendors_table_list();
        get_categories_list();

        $('#add_category_btn').on("click", function(e) {
            var cat = $('#add_category_name').val();

            if (cat == '') {
                var d = flashy('Category name cannot be empty!', {
                    type: 'flashy__danger',
                    stop: true
                });

                return false;
            }

            $.ajax({
                type: "post",
                url: "{{url('save-category')}}",
                data: {
                    name: cat
                },
                dataType: 'json',
                cache: false,
                success: function(data) {

                    if (data['success'] == true) {

                        flashy('Category Added!', {
                            type: 'flashy__success'
                        });
                        $('#add_category_name').val('');
                        $("#categories").append("<option value='" + data["id"] + "' selected>" + cat + "</option>");
                        $('#categories').trigger('change');

                    } else {
                        flashy('Something went wrong!', {
                            type: 'flashy__danger'
                        });
                    }
                }
            });

            e.preventDefault();

        });


    });
    // $("#phone").keyup(function() {

    //     var regex = new RegExp("^[0-9]+$");

    //     if (!regex.test($(this).val())) {
    //         $("#phone_error2").html("Only numeric values allowed");
    //     } else {
    //         $("#phone_error2").html(" ");
    //     }
    //     if ($(this).val() == '') {
    //         $("#phone_error2").html(" ");
    //     }
    // });


    // $("#direct_line").keyup(function() {

    //     var regex = new RegExp("^[0-9]+$");

    //     if (!regex.test($(this).val())) {
    //         $("#phone_error").html("Only numeric values allowed");
    //     } else {
    //         $("#phone_error").html(" ");
    //     }
    //     if ($(this).val() == '') {
    //         $("#phone_error").html(" ");
    //     }
    // });

    function get_categories_list() {

        $.ajax({
            type: "get",
            url: "{{url('get-categories')}}",
            data: "",
            async: false,
            success: function(data) {
                categoriesArr = data['categories'];
            }
        });

    }

    $("#comp_id").change(function() {
        var ter = $("#comp_id option:selected").text();
        $("#company").val(ter);

    });


    $("#rfq_form").submit(function(event) {

        event.preventDefault();

        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var contacts_arr = [];

        $.each($("input[name='contacts[]']:checked"), function() {
            let data = $(this).parents('tr:eq(0)');
            let vendor_email = $(data).find('td:eq(3)').text();
            contacts_arr.push(vendor_email);
        });

        formData.append('contacts', contacts_arr);
        formData.append('rfq_details', tinyMCE.activeEditor.getContent());

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
                    $("#rfq_form").trigger("reset");
                    $(".badge").remove();
                    $('#to_mails').val('');
                    $('input:checkbox:checked').prop('checked', false);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })



                } else {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: 2500
                    })
                }
            }
        });

    });


    $("#instNotesForm").submit(function(event) {
        event.preventDefault();

        var action = $(this).attr('action');

        var notes_value = $("#sell_inst_note").val();
        var form_data = {
            sell_inst_note: notes_value,
        }
        $.ajax({
            type: "POST",
            url: action,
            data: form_data,
            dataType: 'json',
            success: function(data) {
                console.log(data);

                if (data.status_code == 200 && data.success == true) {
                    //  $("#instNotesForm").trigger("reset");
                    $("#displayNotes").text(notes_value);
                    $('.sellInstP').show();
                    $(".sellInst").hide();

                    alertNotification('success', 'Success' , data.message);
                } else {
                    alertNotification('warning', 'Warning' , data.message );
                }
            }
        });

    });

    function showVendorModel() {

        var label = $("#edit-contact").text();

        if (label == 'Edit Vendor') {

            $("#edit-contact").html('Save Vendor');
        }

        $("#categories").empty();
        for (var k = 0; k < categoriesArr.length; k++) {

            $("#categories").append("<option value='" + categoriesArr[k]['id'] + "' >" + categoriesArr[k]['name'] + "</option>");
            $('#categories').trigger('change');


        }
        $("#tags").empty();
        for (var k = 0; k < tagsArr.length; k++) {

            $("#tags").append("<option value='" + tagsArr[k]['id'] + "' >" + tagsArr[k]['name'] + "</option>");
            $('#tags').trigger('change');

        }
        $("#save_vendor_form").trigger("reset");
        $('#vendor_modal').modal('show');
    }

    $("#save_vendor_form").submit(function(event) {
        event.preventDefault();

        if ($("#comp_id").val() == "Select") {
            alertNotification('error', 'Error' , 'Please Select Company');
        } else {
            var formData = new FormData($(this)[0]);
            var action = $(this).attr('action');
            var method = $(this).attr('method');
            var selectedCategories = [];
            var selectedTags = [];

            for (var option of document.getElementById('categories').options) {
                if (option.selected) {
                    selectedCategories.push(option.value);
                }
            }

            for (var option of document.getElementById('tags').options) {
                if (option.selected) {
                    selectedTags.push(option.value);
                }
            }

            formData.append('categories', selectedCategories);
            formData.append('tags', selectedTags);

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
                    console.log(data)
                    if (data['success'] == true) {
                        $("#save_vendor_form").trigger("reset");
                        $('#vendor_modal').modal('hide');
                        get_vendors_table_list();

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: data['message'],
                            showConfirmButton: false,
                            timer: 2500
                        })
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: data['message'],
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                }
            });
        }

    });

    function editContact(id, first_name, last_name, company, email, direct_line, phone, website, tags, categories) {

        var cat_names = categories.split(',');
        var tag_names = tags.split(',');

        if (cat_names.length > 0 && cat_names != '') {

            var cat_count = 0;

            $("#categories").empty();

            for (var m = 0; m < categoriesArr.length; m++) {
                $("#categories").append("<option value='" + categoriesArr[m]['id'] + "'>" + categoriesArr[m]['name'] +
                    "</option>");
                $('#categories').trigger('change');
            }

            for (var k = 0; k < categoriesArr.length; k++) {
                for (var l = 0; l < cat_names.length; l++) {
                    if (categoriesArr[k]['id'] == cat_names[l]) {
                        $('#categories option[value=' + categoriesArr[k]['id'] + ']').attr('selected', 'selected');
                        break;
                    }
                }
            }
        } else {
            cat_names = '---';
        }

        if (tag_names.length > 0 && tag_names != '') {

            var tag_count = 0;
            $("#tags").empty();
            //console.log(tagsArr)

            for (var m = 0; m < tagsArr.length; m++) {
                $("#tags").append("<option value='" + tagsArr[m]['id'] + "'>" + tagsArr[m]['name'] + "</option>");
                $('#tags').trigger('change');
            }

            for (var k = 0; k < tagsArr.length; k++) {
                for (var l = 0; l < tag_names.length; l++) {
                    if (tagsArr[k]['id'] == tag_names[l]) {
                        $('#tags option[value=' + tagsArr[k]['id'] + ']').attr('selected', 'selected');
                        break;
                    }
                }
            }

        } else {
            tags_names = '---';
        }

        $('#first_name').val(first_name);
        $('#last_name').val(last_name);
        $('#comp_id').val(company).trigger('change');
        $('#email').val(email);
        $('#direct_line').val(direct_line);

        $('#phone').val(phone);
        $('#website').val(website);


        $('#contact_id').val(id);
        $('#vendor_modal').modal('show');

        var label = $("#edit-contact").text();
        if (label == 'Save Vendor') {
            $("#edit-contact").html('Edit Vendor');
        }

    }

    function deleteContact(id) {
        console.log(id);
        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this Vendor will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "post",
                    url: "{{url('delete-vendor')}}",
                    data: {
                        id: id
                    },
                    success: function(data) {
                        console.log(data);
                        if (data) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Vendor Deleted!',
                                showConfirmButton: false,
                                timer: 2500
                            })

                            get_vendors_table_list();
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Something went wrong!',
                                showConfirmButton: false,
                                timer: 2500
                            })

                        }
                    }
                });
            }
        })
    }

    $("#InstNote").click(function() {

        var old_notes = $("#displayNotes").text();
        $("#sell_inst_note").val(old_notes);

        // alert("nj");
        $(".sellInst").toggle();
        $(".sellInstP").toggle();
    });

    function get_vendors_table_list() {
        $("#contacts_table").DataTable().destroy();
        $.fn.dataTable.ext.errMode = "none";

        $.ajax({
            type: "GET",
            url: "{{url('get-vendors')}}",
            success: function(result) {
                if(result.success) {
                    var tbl =$("#contacts_table").DataTable({
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
                        // ajax: { url: "{{url('get-vendors')}}"},
                        // "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                        //     $("td:first", nRow).html(iDisplayIndex +1);
                        //     return nRow;
                        // },
                        columns: [
                            {
                                "render": function(data, type, full, meta) {
                                    return `
                                        <div class="text-center">
                                            <input type="checkbox" class="contacts" name="contacts[]" value="`+full.id+`" id="`+full.id+`">
                                        </div>`;
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return `<a href="{{url("vendors_profile")}}/` +full.id + `" target="blank">` + full.first_name + ` ` + full.last_name + `</a>`;
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.company != null ? full.company : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.email != null ? full.email : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    var category_arr = [];
            
                                    if(full.vendor_categories != null && full.vendor_categories != '') {
            
                                        for(var i =0; i < full.vendor_categories.length; i++) {
            
                                            if( full.vendor_categories[i].name != null &&  full.vendor_categories[i].name != '') {
                                                category_arr.push( full.vendor_categories[i].name );
                                            }else{
                                                return '-';
                                            }   
                                        }
                                        return category_arr;
                                    }else{
                                        return '-';
                                    }
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.phone != null ? full.phone : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    var tag_arr = [];
            
                                    if(full.vendor_tags != null && full.vendor_tags != '') {
            
                                        for(var i =0; i < full.vendor_tags.length; i++) {
            
                                            if( full.vendor_tags[i].name != null &&  full.vendor_tags[i].name != '') {
                                                tag_arr.push( full.vendor_tags[i].name );
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
                                    return `
                                    <button class="btn btn-circle btn-icon rounded-circle btn-success" title="Edit Type" 
                                        onclick="editContact(`+ full.id+ `,'`+ full.first_name+ `','` + full.last_name +`',
                                        '`+ full.comp_id +`','`+full.email +`','`+ full.direct_line +`','` + full.phone + `','` +full.website + `','` +full.tags + `','` +full.categories +`')"><i class="fa fa-pencil font-20" aria-hidden="true"></i></button>
                                    
                                    <button class="btn btn-circle btn-icon rounded-circle btn-danger" title="Delete Department" onclick="deleteContact(` +full.id +`)"><i class="fa fa-trash font-20 " aria-hidden="true"></i></button>
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
                console.log('Success', data);
            },
            error: function(data) {
                console.log('Error', data);
            }
        })
    }

</script>