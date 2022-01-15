<script>
    
    var system_date_format = $("#system_date_format").val();
    var timezone = $("#timezone").val();
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        
        window.location.href == "{{route('customer.tickets')}}" ? customer.getTickets() : '';

        customer.render_text_editor();
        
    });


    $("#save_tickets").submit(function(e) {
        e.preventDefault();

        var action = $(this).attr('action');
        var method = $(this).attr('method');
        var form_data =  new FormData(this);

        var ticket_detail = tinymce.activeEditor.getContent();
        form_data.append('ticket_detail' , ticket_detail);

        customer.saveTicket(action , method , form_data);
    });


    const customer = {


        getTickets : ()=> {

            $.ajax({
                url: "{{route('customer.getCustomerTickets')}}",
                type: "GET",
                dataType:"json",
                success:function(response){
                    var obj = response.tickets;
                    console.log(system_date_format)
                    if(response.status_code == 200) {
                        
                        $("#my_tickets_count").text(response.total_tickets_count);
                        $("#open_tickets_count").text(response.open_tickets_count);
                        $("#closed_tickets_count").text(response.late_tickets_count);

                        $('#customer_tickets_table').DataTable().destroy();
                        $.fn.dataTable.ext.errMode = 'none';
                        var tbl = $('#customer_tickets_table').DataTable({
                            data: obj,
                            "pageLength": 10,
                            "bInfo": false,
                            "paging": true,
                            columns: [
                            {
                                "render": function (data, type, full, meta) {
                                    let subject =  full.subject != null ? full.subject : '-';
                                    let coustom_id =  full.coustom_id != null ? full.coustom_id : '-';
                                    let url = "{{route('customer.tkt_dtl', ':id')}}";
                                    url = url.replace(':id', coustom_id);
                                    return `<div> <strong> <a href="${url}">${subject}</a> </strong> <br> ${coustom_id} </div>`;
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.updated_at != null ? customer.region_wise_dateTime(full.updated_at) : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.lastReplier != null && full.lastReplier != "" ? full.lastReplier : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.department_name != null ? full.department_name : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    return full.type_name != null ? full.type_name : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let status = `<span class="badge" style="background-color:${full.status_color != null ? full.status_color : ''}"> ${full.status_name} </span>`;
                                    return full.status_name != null ? status : '-';
                                }
                            },
                            {
                                "render": function (data, type, full, meta) {
                                    let priority = `<span class="badge" style="background-color:${full.priority_color != null ? full.priority_color : ''}"> ${full.priority_name} </span>`;
                                    return full.priority_name != null ? priority : '-';
                                }
                            },
                            ],
                        });
                    }else{
                        toastr.error( 'Something Went Wrong' , { timeOut: 5000 });
                    }
                },
                error:function(e) {
                    toastr.error( 'Something Went Wrong' , { timeOut: 5000 });
                }
            });

        }, 


        region_wise_dateTime : (date) => {
            
            let d = new Date(date);
            var day = d.getDate();
            var year = d.getFullYear();
            var month = d.getMonth();
            
            var hour = d.getHours();
            var min = d.getMinutes();
            var mili = d.getMilliseconds();

            // year , month , day , hour , minutes , seconds , miliseconds;
            let new_date = new Date(Date.UTC(year, month, day, hour, min, mili));
            let converted_date = new_date.toLocaleString("en-US", {timeZone: timezone});
            return moment(converted_date).format(system_date_format + ' ' + 'hh:mm a');
        },

        convertDate : (date) => {
            var d = new Date(date);

            var min = d.getMinutes();
            var dt = d.getDate();
            var d_utc = d.getUTCHours();

            d.setMinutes(min);
            d.setDate(dt);
            d.setUTCHours(d_utc);

            let a = d.toLocaleString("en-US" , {timeZone: timezone});
            // return a;
            var converted_date = moment(a).format(system_date_format + ' ' +'hh:mm a');
            return converted_date;
        },


        showdropdown : (value) => {

            $("#" + value + '_heading').toggle();
            $("#" + value +'_field').toggle();

        },


        saveTicket : (action , method ,form_data) => {
            $.ajax({
                url: action ,
                type: method ,
                data: form_data ,
                cache: false ,
                contentType: false,
                processData: false,
                beforeSend:function(data) {
                    $("#btnSaveTicket").hide();
                    $("#publishing").show();
                },
                success:function(response){
                    if(response.status_code == 200 && response.success == true) {
                        toastr.success(response.message, { timeOut: 5000 });
                        window.location.href = "{{route('customer.tickets')}}";
                    }else{
                        toastr.error( 'Something went wrong' , { timeOut: 5000 });
                    }
                },
                complete:function(data) {
                    $("#btnSaveTicket").show();
                    $("#publishing").hide();
                },
                error:function(e) {
                    $("#btnSaveTicket").show();
                    $("#publishing").hide();
                    toastr.error( 'Something went wrong' , { timeOut: 5000 });
                }
            });
        },

        render_text_editor: () => {
            tinymce.init({
                selector: "textarea#ticket_detail",
                // theme: "modern",
                height: 300,
                file_picker_types: 'image',
                plugins: [
                    "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                    "save table contextmenu directionality emoticons template paste textcolor"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | table | print preview fullpage | forecolor backcolor emoticons",
                // file_picker_types: 'file image media',
                // media_live_embeds: true,
                paste_data_images: true,
                file_picker_callback: function(cb, value, meta) {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    // if (meta.filetype == 'media') input.setAttribute('accept', 'audio/*,video/*');

                    input.onchange = function() {

                        var file = this.files[0];

                        var reader = new FileReader();
                        reader.onload = async function() {
                            var id = 'blobid' + (new Date()).getTime();
                            var blobCache = tinymce.editors.mymce.editorUpload.blobCache;
                            var base64 = reader.result.split(',')[1];

                            if (reader.result.includes('/svg') || reader.result.includes('/SVG')) {
                                base64 = await downloadPNGFromAnyImageSrc(reader.result);
                            }

                            var blobInfo = blobCache.create(id, file, base64);
                            blobCache.add(blobInfo);
                            cb(blobInfo.blobUri(), {
                                title: file.name
                            });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                },
            })
        },

    }


</script>