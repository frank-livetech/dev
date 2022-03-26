
<script>
    // User Profile Script Blade
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    var staff_id = $("#user_id").val();
    var user_profile = {!!json_encode($profile)!!};
    var customer = user_profile; // for states listing
    var ticket_format = {!!json_encode($ticket_format)!!};
    let in_recycle_mode = false;

    var schedule_route = "{{asset('/add_staff_schedule')}}";
    var delete_schedule = "{{asset('/delete_staff_schedule')}}"
    var getStaffSchedule = "{{asset('/get_staff_schedule')}}";
    var get_all_departments = "{{asset('/get-departments')}}";
    var show_dept_permissions = "{{asset('show_departments')}}";
    var dept_permission = "{{asset('dept_permission')}}";
    $("#staff_tasks_list").DataTable();
    let save_staff_color_route = "{{asset('/save-staff-color')}}";

    let ticketsList = [];
    let get_tickets_route = "{{asset('/get-tickets')}}/staff/"+staff_id;
    let get_filteredtkt_route = "{{asset('/get-filtered-tickets')}}"
    let ticket_details_route = "{{asset('/ticket-details')}}";
    let ticket_notify_route = "{{asset('/ticket_notification')}}";
    let flag_ticket_route = "{{asset('/flag_ticket')}}";
    let loggedInUser = {!! json_encode(\Auth::user()->id) !!};
    let date_format = $("#system_date_format").val();
    let url_type = '';
</script>

<script type="text/javascript">
    var permission = [];

    let autocomplete;
    let address1Field;
    let address2Field;
    let postalField;

    function initMap(){
        address1Field = document.querySelector("#address");
        address2Field = document.querySelector("#apt_address");
        postalField = document.querySelector("#update_zip");
        // Create the autocomplete object, restricting the search predictions to
        // addresses in the US and Canada.
        // console.log('address1Field', address1Field.value);
        if(address1Field.value) {
            autocomplete = new google.maps.places.Autocomplete(address1Field, {
                componentRestrictions: { country: ["us", "ca"] },
                fields: ["address_components", "geometry"],
                types: ["address"],
            });
            address1Field.focus();
            $("#map_2").html('<iframe width="100%" frameborder="0" style="height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + address1Field.value + '&language=en"></iframe>');
        }

        // When the user selects an address from the drop-down, populate the
        // address fields in the form.
        // autocomplete.addListener("place_changed", fillInAddress);
    }

    function fillInAddress() {
        if($("#address").val()) {
            $("#map_2").html('<iframe width="100%" frameborder="0" style="    height: -webkit-fill-available;" src="https://www.google.com/maps/embed/v1/place?key='+  $("#google_api_key").val()+'&q=' + $("#address").val() + '&language=en"></iframe>')
        }

        // Get the place details from the autocomplete object.
        const place = autocomplete.getPlace();
        let address1 = "";
        let postcode = "";

        // Get each component of the address from the place details,
        // and then fill-in the corresponding field on the form.
        // place.address_components are google.maps.GeocoderAddressComponent objects
        // which are documented at http://goo.gle/3l5i5Mr
        for (const component of place.address_components) {
            const componentType = component.types[0];

            switch (componentType) {
            case "street_number": {
                address1 = `${component.long_name} ${address1}`;
                break;
            }

            case "route": {
                address1 += component.short_name;
                break;
            }

            case "postal_code": {
                postcode = `${component.long_name}${postcode}`;
                break;
            }

            case "postal_code_suffix": {
                postcode = `${postcode}-${component.long_name}`;
                break;
            }
            case "locality":
                document.querySelector("#update_city").value = component.long_name;
                break;

            case "administrative_area_level_1": {
                document.querySelector("#state").value = component.short_name;
                break;
            }
            case "country":
                document.querySelector("#country").value = component.long_name;
                break;
            }
        }
        address1Field.value = address1;
        postalField.value = postcode;
        // After filling the form with address components from the Autocomplete
        // prediction, set cursor focus on the second address line to encourage
        // entry of subpremise information such as apartment, unit, or floor number.
        address2Field.focus();
        
    }

    $(document).ready(function() {
        try {
            if(countries_list.length) {
                $('#country').trigger('change');
            }
        } catch (err) {
            console.log(err);
        }

        var googleObject = {!! json_encode($google) !!};
        // console.log(googleObject);
        if(!$.isEmptyObject(googleObject)){
            if( googleObject.hasOwnProperty('api_key')){
                var api_key = googleObject.api_key;
                $("#google_api_key").val(api_key);
                // console.log(api_key)
                if(api_key!=''){
                    var script ="https://maps.googleapis.com/maps/api/js?key="+api_key+"&libraries=places&sensor=false&callback=initMap";
                    var s = document.createElement("script");
                    s.type = "text/javascript";
                    s.src = script;
                    $("head").append(s);

                }
                    const allScripts = document.getElementsByTagName( 'script' );
                        [].filter.call(
                        allScripts, 
                        ( scpt ) => scpt.src.indexOf( 'key='+api_key ) >0
                        )[ 0 ].remove();

                        // window.google = {};
            }
        }
        initializeTicketTable();

        $("#twt_link").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Twitter Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#linkedin_link").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Linkedin Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#fb_link").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Facebook Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#pint_link").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Pinterest Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#insta_link").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Instagram Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        $("#website_link").click(function(e) {
            e.preventDefault();
            var value = $(this).attr('href');
            if (value == '') {
                $("#social-error").html("Website Link is Missing");
                setTimeout(() => {
                    $("#social-error").html("");
                }, 5000);
            } else {
                window.open(value, '_blank');
            }
        });

        // add leave
        $("#leaveForm").submit(function(e) {
            e.preventDefault();

            var form  = {
                requested_by : $("#requested_by").val(),
                leave_id : $("#leave_id").val(),
                start_date : $("#leave_start_date").val(),
                end_date : $("#leave_end_date").val(),
                reason: $("#leave_reason").val(),
            }

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: form,
                beforeSend: function(data) {
                    $("#leave-btn").hide();
                    $("#leave-process").show();
                },
                success: function(data) {
                    if(data.status_code == 200 && data.success == true) {
                        get_all_leaves();
                        toastr.success(data.message, { timeOut: 5000 });
                        $("#leaveModal").modal('hide');

                    }else{
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                complete: function(data) {
                    $("#leave-btn").show();
                    $("#leave-process").hide();
                },
                error: function(e) {
                    $("#leave-btn").show();
                    $("#leave-process").hide();
                }
            });


        });

         // schefuleForm
         $("#schefuleForm").submit(function(e) {
            e.preventDefault();

            var form  = {
                start_time : $("#schedule_start_time").val(),
                end_time : $("#schedule_end_time").val(),
                staff_id: $("#staff_id").val(),
                start_date: $("#schedule_start_date").val(),
                end_date: $("#schedule_end_date").val(),
            }

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: form,
                beforeSend: function(data) {
                    $("#schedule-btn").hide();
                    $("#schedule-process").show();
                },
                success: function(data) {
                    if(data.status_code == 200 && data.success == true) {

                        toastr.success(data.message, { timeOut: 5000 });
                        $("#scheduleModal").modal('hide');

                    }else{
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                complete: function(data) {
                    $("#schedule-btn").show();
                    $("#schedule-process").hide();
                },
                error: function(e) {
                    $("#schedule-btn").show();
                    $("#schedule-process").hide();
                }
            });


        });


        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#ppNew').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#profile-user-img').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#upload_user_img").submit(function(e) {
            e.preventDefault();

            let imgValidate = $('#customFilePP').prop('files');

            if (imgValidate.length > 0) {
                imgValidate = imgValidate[0];
                let ext = imgValidate.name.substring(imgValidate.name.lastIndexOf('.') + 1).toLowerCase();
                if (ext != 'jpeg' && ext != 'png' && ext != 'jpg') {
                    toastr.error('File type not allowed! Only jpeg/jpg/png Allowed', { timeOut: 5000 });
                    return false;
                }

                if (Math.round(imgValidate.size / (1024 * 1024)) > 2  ) {
                    toastr.error('File size exceeds 2MB!', { timeOut: 5000 });
                    return false;
                }
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                url: "{{url('upload_user_img')}}",
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                    if (data.status == 200 && data.success == true) {
                        toastr.success(data.message, { timeOut: 5000 });
                        $("#editPicModal").modal('hide');
                        
                        let path = root + '/' + data.img;
                        let id = "{{auth()->user()->id}}";

                        if(id == data.id) {
                            $('#profile-user-img').attr('src', path);
                            $('#login_usr_logo').attr('src', path);
                            $('#modal_profile_user_img').attr('src', path);
                        }else{
                            $('#profile-user-img').attr('src', path);
                            $('#modal_profile_user_img').attr('src', path);
                        }                
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                error: function(e) {
                    console.log(e)
                }

            });
        });
        
        // save work hours
        $("#workHoursForm").submit(function(event) {
            event.preventDefault();

            let action = $(this).attr('action');
            let method = $(this).attr('method');
            var form_data = new FormData(this);

            $.ajax({
                type: method,
                url: action,
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $(this).find('.btn').attr('disabled', true);
                },
                success: function(data) {
                    $(this).find('.btn').attr('disabled', false);
                    // console.log(data);
                    if (data.success == true) {
                        $('#'+$('#attendance_id').val()+'-hw').html($('#worked_hours_value').val());
                        $('#workHoursModal').modal('hide');
                        $(this).trigger('reset');
                        toastr.success(data.message, { timeOut: 5000 });
                    } else {
                        toastr.error(data.message, { timeOut: 5000 });
                    }
                },
                complete: function() {
                    $(this).find('.btn').attr('disabled', false);
                },
                error: function(e) {
                    $(this).find('.btn').attr('disabled', false);
                    console.log(e);
                    toastr.error(data.message, { timeOut: 5000 });
                }
            });
        });
    });

    function getStaffWorkDetail(start, end, user_id) {

        var staff_data = {
            start_date: start,
            end_date: end,
            user_id: user_id
        }

        $.ajax({
            type: "POST",
            url: "{{url('get_staff_attendance')}}",
            dataType: 'json',
            data: staff_data,
            beforeSend: function(data) {
                $('.loader_container').show();
            },
            success: function(data) {
                var obj = data.data;
                // console.log(obj, "obj")
                var total_hours = 0;

                var date = new Date();

                var day_in_month = moment(date).daysInMonth()

                const countSeconds = (str) => {
                    const [hh = '0', mm = '0', ss = '0'] = (str || '0:0:0').split(':');
                    const hour = parseInt(hh, 10) || 0;
                    const minute = parseInt(mm, 10) || 0;
                    const second = parseInt(ss, 10) || 0;
                    return (hour * 3600) + (minute * 60) + (second);
                };

                obj.forEach(element => {
                    total_hours = total_hours + countSeconds(element.hours_worked);
                });

                $("#total_hours").text(secondsToTime(total_hours));

                var avg_hours = total_hours / obj.length;
                $("#avg_hours").text(secondsToTime(avg_hours / 7));
                $("#avg_hours_in_day").text(secondsToTime(total_hours / day_in_month));


                $('#payroll_table').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#payroll_table').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
                    "searching": true,
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [0, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: [0, 2, 3, 4, 5]
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [0, 2, 3, 4, 5]
                            }
                        },
                    ],
                    columns: [{
                            "render": function(data, type, full, meta) {
                                return full.user[0] != null ? full.user[0].name : '';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if (full.clock_out == null) {
                                    return `<span class="badge badge-success py-1">Clocked In</span>`;
                                } else {
                                    return `<span class="badge badge-danger py-1">Clocked Out</span>`;
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.date != null ? full.date : '';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.clock_in != null ? full.clock_in : '';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.clock_out != null ? full.clock_out : '';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.hours_worked != null ? `<span id="${full.id}-hw">${full.hours_worked}</span>` : `<span id="${full.id}-hw"></span>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<span class="fa fa-pencil-alt" style="cursor: pointer;" onclick="editWorkHours(${full.id}, '${full.hours_worked}')"></span>`;
                            }
                        },

                    ],
                });

            },
            complete: function(data) {
                $('.loader_container').hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    }

    function editWorkHours(id, hours) {
        $('#workHoursForm #attendance_id').val(id);
        $('#workHoursForm #worked_hours_value').val(hours);
        $('#workHoursModal').modal('show');
    }

    function openThisAccordin(dept_id) {
        $("#collapseOne_"+dept_id).removeClass('collapse');
        $("#collapseOne_"+dept_id).addClass('show');

        var formData = new FormData();
        formData.append('user_id', $("#user_id").val());
        formData.append('dept_id', dept_id);
        formData.append('assignment', 'set');
        $('.yes_sub_check_'+dept_id).prop('checked' , true);
        
        updateAssignment(formData);

        // $('.yes_sub_check_'+dept_id).each(function () {
        //     let name = $(this).attr('dep');
        //     saveNotificationPermission(1 , dept_id , name);
        // });

        console.log("openThisAccordin function");
    }

    function closeThisAccordin(dept_id) {
        $("#collapseOne_"+dept_id).removeClass('show');
        $("#collapseOne_"+dept_id).addClass('collapse');

        var formData = new FormData();
        formData.append('user_id', $("#user_id").val());
        formData.append('dept_id', dept_id);
        formData.append('assignment', 'unset');

        $('.no_sub_check' +dept_id).prop('checked' , true);

        updateAssignment(formData);

        $('.no_sub_check'+dept_id).each(function () {
            let name = $(this).attr('dep');
            saveNotificationPermission(0, dept_id , name);
        });
    }

    function updateAssignment(formData) {
        console.log("updateAssignment function");
        $.ajax({
            type: "POST",
            url: "{{asset('/set-dept-assignment')}}",
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                toastr.success( data['message'] , { timeOut: 5000 });
            }
        });
    }

    function showDeptrtmentPermission() {
        var staff_id = $("#user_id").val();

        $.ajax({
            type: "POST",
            url: show_dept_permissions,
            data: {staff_id:staff_id},
            beforeSend: function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                // console.log(data, "permission list 123123");
                var obj = data.permissions;

                var yes = '';
                var no = '';

                for(var i = 0; i < obj.length; i++) {
                    
                    $("#collapseOne_"+obj[i].id).addClass('show');
                    $("#customRadio1_"+obj[i].id).prop('checked',true);

                    for(var k = 0; k < obj[i].dept_permission.length; k++ ) {

                        let per = obj[i].dept_permission[k];

                        yes = "#yes_"+ per.permission_id + '_' + obj[i].id ;
                        no = "#no_"+ per.permission_id + '_' + obj[i].id ;

                        if(per.is_active == 1) {
                            $(yes).prop('checked',true);
                        }else{
                            $(no).prop('checked',true);
                        }
                        
                    }
                }
                
            },
            complete: function(data) {
                $(".loader_container").hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    }


    function saveNotificationPermission(val, dept_id, perm) {
        console.log("saveNotificationPermission function");
        var formData = new FormData();

        formData.append('user_id', $("#user_id").val());
        formData.append('dept_id', dept_id);
        formData.append('name', perm);
        formData.append('permitted', val);

        $.ajax({
            type: "POST",
            url: "{{asset('/set-dept-permission')}}",
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                toastr.success( data['message'] , { timeOut: 5000 });
            }
        });
    }

    function savePermission() {

        $.ajax({
            type: "POST",
            url: dept_permission,
            data: {data:permission,length:permission.length},
            dataType: 'json',
            beforeSend:function(data) {
                $("#per_sve_btn").hide();
                $("#per_process").show();
                $("#permission_loader").show();
            },  
            success: function(data) {
                // console.log(data);
                toastr.success(data.message, { timeOut: 5000 });
            },
            complete:function(data) {
                $("#per_sve_btn").show();
                $("#per_process").hide();
                $("#permission_loader").hide();
            },  
            error: function(e) {
                console.log(e);
                $("#per_sve_btn").show();
                $("#per_process").hide();
                $("#permission_loader").hide();
            }
        });
    }

    function getAllTasksList() {
        var task_status = $("#task_status").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var user_id = $("#user_id").val();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            type: "GET",
            url: "{{url('get_staff_tasks')}}",
            data: {
                id:user_id,
                task_status: task_status,
                from: moment(from).format('YYYY-MM-DD hh:mm:ss'),
                to: moment(to).format('YYYY-MM-DD hh:mm:ss'),
            },
            beforeSend: function(data) {
                $(".loader_container").show();
            },
            success: function(data) {
                // console.log(data , "user task data");
                var obj = data.data;

                $('#staff_tasks_list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#staff_tasks_list').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
                    columns: [{
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<a href="{{asset('task_details')}}/` + full.id + `">` + full.id + `</a>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `<a href="{{asset('task_details')}}/` + full.id + `">` + full.title + `</a>`;
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if (full.task_project != null) {
                                    return full.task_project.name;
                                }
                            }
                        },
                        {
                            "data": "version",
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.estimated_time != null ? full.estimated_time : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.created_at != null ? moment(full.created_at).format("DD-MM-YYYY h:m:s") : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                if (full.task_creator != null) {
                                    return full.task_creator.name;
                                } else {
                                    return '---';
                                }
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.worked_time != null ? `<span class="badge bg-success text-white badge-pill">` + secondsToHMS(full.worked_time) + `</span>` : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                // return full.task_status;
                                if (full.task_status == "danger" && full.started_at == null) {
                                    return "<span class='badge badge-pill bg-danger text-white align-middle'>Pending</span>";
                                } else if (full.task_status == "default" && full.started_at != null) {
                                    return "<span class='badge badge-pill bg-warning text-white'>Work in Progress</span>";
                                } else {
                                    return "<span class='badge badge-pill bg-danger text-white align-middle'>Pending</span>";
                                }
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
                $(".loader_container").hide();
            },
            error: function(e) {
                console.log(e)
            }
        });
    }

    $("#pills-documents-tab").click(function() {
        get_all_documents(staff_id);
    });
    $("#my-certifications-tab").click(function() {
        get_all_certificates(staff_id);
    });
    $("#my-schedule-tab").click(function() {
        get_all_leaves();
        var defaultEvents = [];
            
        get_all_schedules(defaultEvents);
    });

    $("#payroll-tab").click(function() {
        
        let date = new Date();
        let start = moment(date).startOf('month').format('YYYY-MM-DD');
        let end = moment(date).endOf('month').format('YYYY-MM-DD');
        let user_id = $("#user_id").val();

        getStaffWorkDetail(start, end, user_id);

    });

    function get_all_certificates(id) {
        $.ajax({
            type: 'GET',
            url: "{{url('get-all-certificates')}}/" + id,
            dataType: 'json',
            success: function(data) {
                var obj = data.certificates;

                $('#asset_table_list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#asset_table_list').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": true,
                    "paging": true,
                    "searching": true,
                    columns: [{
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            "render": function(data, type, full, meta) {
                                var img = full.image;
                                var img_split_url = img.split('.');

                                if (img_split_url[1] == "png" || img_split_url[1] == "jpeg" || img_split_url[1] == "jpg") {
                                    return `<img src="{{asset('public/files/user_certification/` + full.image + `')}}" style="width:55px; height:55px;" class="img-fluid">`;
                                } else if (img_split_url[1] == "pdf") {
                                    return `<img src="{{asset('public/files/file.svg')}}" style="width:55px; height:55px;" class="img-fluid">`;
                                } else if (img_split_url[1] == "docx" || img_split_url[1] == "dot" || img_split_url[1] == "dotx") {
                                    return `<img src="{{asset('public/files/word.svg')}}" style="width:55px; height:55px;" class="img-fluid">`;
                                }

                            }
                        },
                        {
                            "data": "name",
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.details.substr(0, 30) + "...";
                            }
                        },
                        {
                            "class": "text-center",
                            "render": function(data, type, full, meta) {
                                var img = full.image;
                                var img_split_url = img.split('.');

                                if (img_split_url[1] == "png" || img_split_url[1] == "jpeg" || img_split_url[1] == "jpg") {
                                    return `<a title="download certificate" href="{{asset('public/files/user_certification/` + full.image + `')}}" download><i class=" fas fa-download"></i></a>`;
                                } else if (img_split_url[1] == "pdf") {
                                    return `<a title="download certificate" href="{{asset('public/files/file.svg')}}" download><i class=" fas fa-download"></i></a>`;
                                } else if (img_split_url[1] == "docx" || img_split_url[1] == "dot" || img_split_url[1] == "dotx") {
                                    return `<a title="download certificate" href="{{asset('public/files/word.svg')}}" download><i class=" fas fa-download"></i></a>`;
                                }
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
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }

    function get_all_documents(id) {

        $.ajax({
            type: 'GET',
            url: "{{url('get-all-documents')}}/" + id,
            dataType: 'json',
            success: function(data) {
                var obj = data.docs;

                $('#user_docs_table').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#user_docs_table').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": true,
                    "paging": true,
                    "searching": true,
                    columns: [{
                            "data": null,
                            "defaultContent": ""
                        },
                        {
                            "render": function(data, type, full, meta) {
                                var img = full.image;
                                var img_split_url = img.split('.');

                                if (img_split_url[1] == "png" || img_split_url[1] == "jpeg" || img_split_url[1] == "jpg") {
                                    return `<img src="{{asset('public/files/user_docs/` + full.image + `')}}" style="width:55px; height:55px;" class="img-fluid">`;
                                } else if (img_split_url[1] == "pdf") {
                                    return `<img src="{{asset('public/files/file.svg')}}" style="width:55px; height:55px;" class="img-fluid">`;
                                } else if (img_split_url[1] == "docx" || img_split_url[1] == "dot" || img_split_url[1] == "dotx") {
                                    return `<img src="{{asset('public/files/word.svg')}}" style="width:55px; height:55px;" class="img-fluid">`;
                                }

                            }
                        },
                        {
                            "data": "name",
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return full.details.substr(0, 30) + "...";
                            }
                        },
                        {
                            "class": "text-center",
                            "render": function(data, type, full, meta) {
                                var img = full.image;
                                var img_split_url = img.split('.');

                                if (img_split_url[1] == "png" || img_split_url[1] == "jpeg" || img_split_url[1] == "jpg") {
                                    return `<a title="download document" href="{{asset('public/files/user_docs/` + full.image + `')}}" download><i class=" fas fa-download"></i></a>`;
                                } else if (img_split_url[1] == "pdf") {
                                    return `<a title="download document" href="{{asset('public/files/file.svg')}}" download><i class=" fas fa-download"></i></a>`;
                                } else if (img_split_url[1] == "docx" || img_split_url[1] == "dot" || img_split_url[1] == "dotx") {
                                    return `<a title="download document" href="{{asset('public/files/word.svg')}}" download><i class=" fas fa-download"></i></a>`;
                                }
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
            failure: function(errMsg) {
                console.log(errMsg);
            }
        });
    }

    function openSettingTab() {
        $('.nav li a[href="#previous-month"]').tab('show');
    }

    function secondsToTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds % 3600) / 60);
        var seconds = Math.floor(seconds % 60);
        return (hours < 10 ? "0" + hours : hours) + ":" +
            (minutes < 10 ? "0" + minutes : minutes) + ":" +
            (seconds < 10 ? "0" + seconds : seconds);
    }

    function secondsToHMS(totalSeconds) {
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
        var seconds = totalSeconds - (hours * 3600) - (minutes * 60);

        // round seconds
        seconds = Math.round(seconds * 100) / 100

        var result = (hours < 10 ? "0" + hours : hours);
        result += ":" + (minutes < 10 ? "0" + minutes : minutes);
        result += ":" + (seconds < 10 ? "0" + seconds : seconds);
        return result;
    }

    function filterData(value) {
        var today = new Date();
        switch (value) {
            case "today":
                let date = new Date();
                let start = moment(date).startOf('month').format('YYYY-MM-DD');
                let end = moment(date).endOf('month').format('YYYY-MM-DD');
                let user_id = $("#user_id").val();

                getStaffWorkDetail(start, end, user_id);

                $("#daterangediv").css("display", "none");
                break;
            case "date_range":
                $("#daterangediv").css("display", "block");
                break;
        }
    }

    function getStaffDateWise() {
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        var user_id = $("#user_id").val();

        let start = moment(start_date).format('YYYY-MM-DD hh:mm:ss');
        let end = moment(end_date).format('YYYY-MM-DD hh:mm:ss');

        getStaffWorkDetail(start, end, user_id);
    }


    function requestLeaveModal() {
        $("#leave-title").text("Request Leave");
        $("#leaveForm")[0].reset();
        $("#leaveModal").modal('show');
        $("#leave_id").val(" ");
    }


    function get_all_leaves() {

        $("#leaves-table").DataTable().destroy();
        $.fn.dataTable.ext.errMode = "none";

        $.ajax({
            type: "GET",
            url: "{{url('get-leaves')}}",
            success: function(result) {
                if(result.success) {
                    var tbl =$("#leaves-table").DataTable({
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
                        // ajax: { url: "{{url('get-leaves')}}" },
                        // "fnRowCallback" : function(nRow, aData, iDisplayIndex){
                        //     $("td:first", nRow).html(iDisplayIndex +1);
                        //     return nRow;
                        // },
                        columns: [{
                                data: null,
                                defaultContent: ""
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.reason != null ? full.reason : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.start_date != null ? full.start_date : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    return full.end_date != null ? full.end_date : '-';
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    let pending = `<span class="badge bg-warning badge-pill text-white">pending</span>`;
                                    let approved = `<span class="badge bg-success badge-pill text-white">approved</span>`;
                                    let rejected = `<span class="badge bg-danger badge-pill text-white">rejected</span>`;
                                    if(full.status == 0) {
                                        return pending;
                                    }else if(full.status == 1) {
                                        return approved;
                                    }else{
                                        return rejected;
                                    }
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    if(full.status == 0) {
                                        return `
                                        <div class="d-flex justify-content-center">
                                            <button onclick="edit_leave('`+full.id+`','`+full.start_date+`','`+full.end_date+`','`+full.reason+`')" title="edit leave" class="btn btn-success btn-sm rounded" type="button"> <i class="fas fa-pencil-alt"></i> </button>
                                            <button onclick="delete_leave(`+full.id+`)" class="btn btn-danger btn-sm rounded ml-2" type="button" title="delete leave"> <i class="fas fa-trash"></i> </button>
                                        </div>
                                    `;
                                    }else{
                                        return '-';
                                    }
                                }
                            },
                        ]
                    });
                    // tbl.on('order.dt search.dt', function () {
                    //     tbl.column(0, {
                    //         search: 'applied',
                    //         order: 'applied'
                    //     }).nodes().each(function (cell, i) {
                    //         cell.innerHTML = i + 1;
                    //     });
                    // }).draw();
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
                console.log('Error', data);
            }
        });
    }

    function edit_leave(id, start_date , end_date , reason) {

        $("#leave-title").text("Update Leave");

        $("#leave_id").val(id);

        $("#leave_start_date").val(start_date);
        $("#leave_end_date").val(end_date);
        $("#leave_reason").val(reason);

        $("#leaveModal").modal('show');

    }


    function delete_leave(id) {
        $.ajax({
            type: 'POST',
            url: "{{url('delete-leave')}}",
            data: {id:id},
            success: function(data) {
                if(data.status_code == 200 && data.success == true) {

                    toastr.success(data.message, { timeOut: 5000 });

                    get_all_leaves();

                }else{
                    toastr.error(data.message, { timeOut: 5000 });
                }
            },
            error: function(e) {

            }
        });
    }


    function openScheduleModal() {
        $("#schedule-title").text("Set Schedule");
        $("#scheduleModal").modal('show');
    }
</script>

@include('js_files.statesJs')