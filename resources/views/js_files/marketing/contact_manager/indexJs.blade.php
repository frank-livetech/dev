<script>
    // COntact Manager Script Blade
    let msgTimer = 2000;
    let contacts_table_list = null;
    let contacts = {!! json_encode($contacts) !!};
    console.log(contacts);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $(document).ready(function(){
        $('#tag_id').select2({
            placeholder: 'Tags'
        });
        contacts_table_list = $('#file_export').DataTable();
        $('a.toggle-vis').on( 'click', function (e) {
            e.preventDefault();

            $(this).toggleClass('btn-success');
            $(this).toggleClass('btn-secondary');
    
            // Get the column API object
            var column = contacts_table_list.column( $(this).attr('data-column') );
    
            // Toggle the visibility
            column.visible( ! column.visible() );
        } );


        $('#cm_select').multipleSelect({
                width:300,
                onClick:function(view) {
                    var selectedItems = $('#cm_select').multipleSelect("getSelects");
                    for(var i =0; i < 7; i++) {
                        columns = contacts_table_list.column(i).visible(0);
                    }
                    for(var i = 0; i < selectedItems.length; i++) {
                        var s = selectedItems[i];
                        contacts_table_list.column(s).visible(1);
                    }
                    $('#file_export').css('width','100%');
                },
                onCheckAll:function() {
                    for(var i =0; i < 7; i++) {
                        columns = contacts_table_list.column(i).visible(1);
                    }
                },
                onUncheckAll:function() {
                    for(var i =0; i < 7; i++) {
                        columns = contacts_table_list.column(i).visible(0);
                    }
                    $('#file_export').css('width','100%');
                }
            });




        get_contacts_table_list();
        // $('.overflow-wrap').removeClass('overflow-wrap');
        getTags();
    });

    $("#TagList").select2({
        placeholder: "Select Tag",
        allowClear: true
    });



        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

        /////////////////////////////////////   Contact Functions Start  //////////////////////////////////////////////

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////



    $('#importContacts').change(function(e){
        let file = e.target.files[0];
        console.log(file);

        var reader = new FileReader();

        reader.onload = function(e) {
            var data = e.target.result;
            var workbook = XLSX.read(data, {
                type: 'binary'
            });

            workbook.SheetNames.forEach(function(sheetName) {
                // Here is your object
                var XL_row_object = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheetName]);
                console.log(XL_row_object);
                let formData = new FormData();
                let forms = [];
                for(let i in XL_row_object){
                    forms[i] = JSON.stringify(XL_row_object[i]);
                }
                for(let i in forms){
                    formData.append(i, forms[i]);
                }
                formData.append('import', true);

                $('.custom-file-label').text(file.name);

                $('#submitContactsList').click(function(){
                    saveContact(formData);
                    $('#importContacts').val('');
                    $('.custom-file-label').text('Choose file');
                });
            });
        };

        reader.onerror = function(ex) {
            console.log(ex);
        };

        reader.readAsBinaryString(file);
    });

    function get_contacts_table_list(tag='') {
        contacts_table_list.clear().draw();
        
        for(let i in contacts){
            let listContacts = true;
            let tag_ids = null;
            if(contacts[i].tag_id != null){
                tag_ids = contacts[i].tag_id.split(',');
                if(tag != null && tag != ''){
                    listContacts = false;
                    for(let j in tag_ids){
                        if(tag_ids[j] == tag){
                            listContacts = true;
                            break;
                        }
                    }
                }
            }
            if(!listContacts){
                continue;
            }
            contacts_table_list.row.add([
                contacts[i].id,
                parseInt(i)+1,
                contacts[i].first_name + ' ' + contacts[i].last_name,
                contacts[i].company,
                contacts[i].email_1,
                contacts[i].cell_num,
                '<div class="text-center">\
                    <button class="btn btn-circle btn-success" href="javascript:void(0)" id="btn-edit-'+contacts[i].id+'" class="text-success" title="Update User Info"><i class="fas fa-user-edit" style="font-size: 12px;"></i></button>\
                    <button class="btn btn-circle btn-danger" title="Delete User" onclick="event.stopPropagation();deleteContact(' +contacts[i].id +');return false;"><i class="fa fa-trash " aria-hidden="true" style="font-size: 12px;"></i></button>\
                </div>'

            ]).draw(false);

            $('#btn-edit-'+contacts[i].id).click(function(){
                $('#contact_id').val(contacts[i].id);

                $('#first_name').val(contacts[i].first_name);
                $('#last_name').val(contacts[i].last_name);
                $('#company').val(contacts[i].company);
                $('#email_1').val(contacts[i].email_1);
                $('#email_2').val(contacts[i].email_2);
                $('#office_num').val(contacts[i].office_num);
                $('#cell_num').val(contacts[i].cell_num);
                $('#street_addr_1').val(contacts[i].street_addr_1);
                $('#street_addr_2').val(contacts[i].street_addr_2);
                $('#city_name').val(contacts[i].city_name);
                $('#state').val(contacts[i].state);
                $('#zip_code').val(contacts[i].zip_code);
                $('#country_name').val(contacts[i].country_name);
                $('#notes').text(contacts[i].notes);

                $('#tag_id').val(tag_ids);
                $('#tag_id').trigger('change');

                $('#contactFormModal').modal('show');
            });
        }
    }

    $("#contactFormModal").on('hidden.bs.modal', function(){
        $("#save_contact").trigger("reset");
        $('#tag_id').val('');
        $('#tag_id').trigger('change');
        $('#save_contact').find('#contact_id').val('');
    });

    $("#save_contact").submit(function( event ) {  
 
        event.preventDefault();
        console.log($(this)[0]);
        let index = null;

        if(!$('#contact_id').val()){
            for(let i in contacts){
                if($('#email_1').val() == contacts[i].email_1){
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: $('#email_1').val()+ ' already exists!',
                        showConfirmButton: false,
                        timer: msgTimer
                    });
                    break;
                }
            }
        }else{
            for(let i in contacts){
                if(contacts[i].id == $('#contact_id').val()){
                    index = i;
                    break;
                }
            }
        }

        var formData = new FormData($(this)[0]);
        var action = $(this).attr('action');
        var method = $(this).attr('method');

        var selected = [];
        for (var option of document.getElementById('tag_id').options) {
            if (option.selected) {
            selected.push(option.value);
            }
        }
        formData.append('tag_ids', selected);
        formData.append('import', false);

        saveContact(formData, index);
    });

    function saveContact(formData, index=null){
        $.ajax({
            type: "post",
            url: "{{asset('/contact')}}",
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                console.log(data);
                if(data['success'] == true){
                    $('#contactFormModal').modal('hide');
                    
                    if(Array.isArray(data.data)){
                        for(let i in data.data){
                            contacts.push(data.data[i]);
                        }
                    }else{
                        if(index == null || index == -1){
                            contacts.push(data.data);
                        }else{
                            contacts[index] = data.data;
                        }
                    }

                    get_contacts_table_list();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: msgTimer
                    })

                } else{   
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: data['message'],
                        showConfirmButton: false,
                        timer: msgTimer
                    })
                }
            }
        });
    }

    function deleteContact(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "All data related to this contact will be removed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{asset('/delete_contact')}}",
                    data: {
                        id: id
                    },
                    success: function (data) {
                        if (data) {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: data['message'],
                                showConfirmButton: false,
                                timer: msgTimer
                            })
                            for(let i in contacts){
                                if(contacts[i].id == id){
                                    contacts.splice(i, 1);
                                }
                            }
                            get_contacts_table_list();
                            
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: 'Something went wrong!',
                                showConfirmButton: false,
                                timer: msgTimer
                            })

                        }
                    }
                });
            }
        })
    }



    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /////////////////////////////////////   Contact Functions End /////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////

    function updateContactList(){
        get_contacts_table_list($('#TagList').val());
    }

    let TagArr = '';
    $("#save_tag").submit(function (event) {

        event.preventDefault();

        var id = $('#newTagId').val();''
        var name = $('#newTag').val();

        $.ajax({
            type:'post',
            url: "{{asset('/save_tag')}}",
            data: {name:name,
            id:id },
            success: function (data) {

                if (data.status_code == 200) {
                    $('#newTag').val('');
                    //alert(name);
                    $("#TagList").append("<option value='"+name+"'>"+name+"</option>");
                    $("#tag_id").append("<option value='"+name+"'>"+name+"</option>");
                    $('.modal').modal('hide');
                    $("#save_tag").trigger("reset");
                    //$("#edit_btn").css("display",'inline-block');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Tag Added Successfully!',
                        showConfirmButton: false,
                        timer: 2500
                    })
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

    function getTags(){
        //console.log(data.id);
        $.ajax({
            type: "get",
            url: "{{asset('get-tags')}}",
            success: function(data) {
                //console.log(data.data);
                if(data.success == true){
                    var tagg = data.data;
                    
                    var alerts= "";
                    for(var i = 0; i< tagg.length ; i++){
                        alerts +="<option value='"+tagg[i].id+"'>"+tagg[i].name+"</option>"
                    }
                    $('#TagList').append(alerts);
                    $('#tag_id').append(alerts);
                }
            }
        });
    }
    function editTag(id, name) {

        $('#newTag').val(name);
        $('#newTagId').val(id);
        $('#Save_Add_Tag').modal('show');

    }


   /* (function () {

        var db = {

            loadData: function (filter) {
                return $.grep(this.clients, function (client) {
                    return (!filter.Id || client.Id.indexOf(filter.Id) > -1);
                    // && (!filter.Age || client.Age === filter.Age)
                    // && (!filter.Address || client.Address.indexOf(filter.Address) > -1)
                    // && (!filter.Country || client.Country === filter.Country)
                    // && (filter.Married === undefined || client.Married === filter.Married);
                });
            },

            insertItem: function (insertingClient) {
                this.clients.push(insertingClient);
            },

            updateItem: function (updatingClient) {},

            deleteItem: function (deletingClient) {
                var clientIndex = $.inArray(deletingClient, this.clients);
                this.clients.splice(clientIndex, 1);
            }

        };
        window.db = db;
        var contacts = [];

        $.ajax({

            type: "get",
            url: "{{asset('/get-contacts')}}",
            dataType: 'json',
            cache: false,
            async: false,
            success: function (data) {
                console.log(data.contact);
                contacts = data.contact;

            }
        });


        db.clients = contacts;

        // db.users = [{
        //         "ID": "x",
        //         "Account": "A758A693-0302-03D1-AE53-EEFE22855556",
        //         "Name": "Carson Kelley",
        //         "RegisterDate": "2002-04-20T22:55:52-07:00"
        //     },
        //     {
        //         "Account": "D89FF524-1233-0CE7-C9E1-56EFF017A321",
        //         "Name": "Prescott Griffin",
        //         "RegisterDate": "2011-02-22T05:59:55-08:00"
        //     }
        // ];

    }());*/

</script>
