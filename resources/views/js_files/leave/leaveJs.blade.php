<script>
    $(document).ready(function() {
        get_all_leaves();
    });


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
                        columns: [
                            {
                                data: null,
                                defaultContent: ""
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    if(full.staff != null && full.staff != ' ') {
                                        return `<a href="{{url('profile')}}/`+full.staff.id+`">`+full.staff.name+`</a>`;
                                    }
                                }
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
                                    if (full.status == 0) {
                                        return pending;
                                    } else if (full.status == 1) {
                                        return approved;
                                    } else {
                                        return rejected;
                                    }
                                }
                            },
                            {
                                "render": function(data, type, full, meta) {
                                    if (full.status == 0) {
                                        return `
                                            <div class="d-flex justify-content-center">
                                                <button onclick="change_status(` + full.id + `,1)" title="approve leave" class="btn btn-success btn-sm rounded" type="button"> <i class="fas fa-check-circle"></i> </button>
                                                <button onclick="change_status(` + full.id + `, 2)" class="btn btn-danger btn-sm rounded ml-2" type="button" title="reject leave"> <i class="fas fa-times"></i> </button>
                                            </div>
                                        `;
                                    }else{
                                        return `-`;
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
                console.log('Success', data);
            },
            error: function(data) {
                console.log('Error', data);
            }
        });
    }


    function change_status(id, status) {

        $.ajax({
            type: 'POST',
            url: "{{url('change-leave-status')}}",
            data: {id:id, status:status},
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

</script>