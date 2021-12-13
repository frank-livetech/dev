
<script>

    var date_format = $("#system_date_format").val();

    var invoice = "{{url('invoice-maker')}}";
    var customer = "{{url('customer-profile')}}";
    var create_pdf = "{{url('create_pdf_invoice')}}";
    var checkoutpage = "{{url('checkout')}}";

    let orders_tbl = null;
    let subs_tbl = null;
    let orders_list = [];
    let subs_list = [];

    let all_statuses = ['Pending Payment', 'Processing', 'Completed', 'Cancelled', 'Refunded', 'Failed'];
    let all_types = ['Just Orders', 'Related to Subscription'];
    let all_pm_subs = ['None', 'Credit Card', 'PayPal', 'Direct bank transfer', 'Check Payment', 'Bitcoin & Other Cryptocurrencies', 'Manual Renewal'];

    $(document).ready(function() {
        
        get_all_orders();
        get_all_subs();

        let opts = '<option value="">All Statuses</option>';
        all_statuses.forEach(e => {
            opts += `<option value="${e.toLowerCase()}">${e}</option>`;
        });
        $('#all_statuses').html(opts);

        opts = '<option value="">All Orders</option>';
        all_types.forEach(e => {
            opts += `<option value="${e.toLowerCase()}">${e}</option>`;
        });
        $('#all_types').html(opts);
        
        opts = '<option value="">Any Payment Method</option>';
        all_pm_subs.forEach(e => {
            opts += `<option value="${e.toLowerCase()}">${e}</option>`;
        });
        $('#all_pm_subs').html(opts);
    });

    function orderFilters() {
        let sval = $('#all_statuses').val();
        let tval = $('#all_types').val();
        let dval = $('#all_dates').val();
        let cval = $('#reg_customers').val();

        let list = orders_list.filter(item => {
            if(sval && item.status_text.toLowerCase() != sval) return false;
            if(dval) {
                let dd = new Date(item.created_at);
                let mm = dd.toLocaleString('default', { month: 'long' });
                let yy = dd.getFullYear();
                if(mm+' '+yy != dval) return false;
            }
            // if(tval && item.type != tval) return false;
            if(cval) {
                if(!item.customer) return false;
                let cname = item.customer.first_name.toLowerCase()+item.customer.last_name.toLowerCase();
                if(cname.indexOf(cval.toLowerCase()) == -1 && item.customer.email.indexOf(cval.toLowerCase()) == -1) return false;
            }

            return true;
        });
        console.log(list);

        listOrders(list);
    }

    function listOrders(list=orders_list) {
        orders_tbl.clear().draw();

        $.each(list, function(key, full) {
            let firstname = full.customer != null ? full.customer.first_name : '';
            let lastname = full.customer != null ? full.customer.last_name : '';
            let link = `<a href="`+customer+`/`+full.customer_id+`" >`+firstname + " " + lastname +`</a>`;

            let status_class = '';
            let status = full.status_text != null ? full.status_text : '';

            if(full.status_text == "Pending Payment") {
                status_class = "badge text-info badge-light-info";
            }else if(full.status_text== "Cancelled") {
                status_class = "text-danger badge-light-danger";
            }else if(full.status_text == "Failed") {
                status_class = "badge text-danger badge-light-danger";
            }else if(full.status_text == "Processing") {
                status_class = "badge text-warning badge-light-warning";
            }else if(full.status_text == "On hold") {
                status_class = "badge text-primary badge-light-primary";
            }else if(full.status_text == "Completed") {
                status_class = "badge text-success badge-light-success";
            }else if(full.status_text == "Refunded") {
                status_class = "badge text-megna badge-light-megna";
            }

            let pdf_btn = `<a href="`+create_pdf+`/`+full.custom_id+`" class="btn btn-danger btn-circle mr-1" title="download pdf"><i class="far fa-file-pdf mt-1"></i></a><button class="btn btn-success btn-circle mr-1" ><i class="far fa-eye" title="view"></i></button>`;
            
            let checkout_btn = '';


            if(full.customer != null) {
                let url = ``+checkoutpage+`/`+full.customer.id+``;

                if(full.status_text == "Pending Payment") {
                    checkout_btn = `<a href="`+checkoutpage+`/`+full.customer.id + `/`+full.custom_id+`" class="btn btn-info btn-circle mr-1" title="checkout"><i class="fas fa-check-circle mt-1"></i></a>`;
                }
            }

            let row = `<tr>
                <td>${key+1}</div></td>
                <td><a href="`+invoice+`/`+full.custom_id+`" >#`+full.custom_id+`</a></td>
                <td>${link}</td>
                <td>${moment(full.created_at).format(date_format)}</td>
                <td><span class="`+status_class+`">`+status+`</span></td>
                <td>${full.grand_total != null ? (full.grand_total).toFixed(2) : ''}</td>
                <td>${full.woo_id ? `<span class="fab fa-wordpress" style="font-size: 20px;">` : ''}</td>
                <td>${checkout_btn+pdf_btn}</td>
            </tr>`;
            
            orders_tbl.row.add($(row)).draw();
        });
    }

    function get_all_orders() {
        $("#orders_table").DataTable().destroy();
        $.fn.dataTable.ext.errMode = "none";

        $.ajax({
            type: "GET",
            url: "{{url('get-all-orders')}}",
            success: function(result) {
                if(result.success) {
                    let opts = '';
                    let dates = [];
                    
                    orders_list = result.data;

                    result.data.forEach(e => {
                        let dd = new Date(e.created_at);
                        let mm = dd.toLocaleString('default', { month: 'long' });
                        let yy = dd.getFullYear();
                        if(dates.indexOf(mm+' '+yy) == -1) {
                            dates.push(mm+' '+yy);
                            opts += `<option value="${mm+' '+yy}">${mm+' '+yy}</option>`;
                        }
                    });
                    $('#all_dates').html('<option value="">All Dates</option>'+opts);

                    orders_tbl =$("#orders_table").DataTable({
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
                        // ajax: { url: "{{url('get-all-orders')}}"},
                        // columns: [
                        //     {
                        //         data: null,
                        //         defaultContent: ""
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
                        //             let link = `<a href="`+invoice+`/`+full.custom_id+`" >#`+full.custom_id+`</a>`;
                        //             return link;
                        //         }
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
                        //             let firstname = full.customer != null ? full.customer.first_name : '-';
                        //             let lastname = full.customer != null ? full.customer.last_name : '-';
                        //             let link = `<a href="`+customer+`/`+full.customer_id+`" >`+firstname + " " + lastname +`</a>`;
                        //             return link;
            
                        //         }
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
                        //             return moment(full.created_at).format(date_format)
                        //         }
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
                        //             let status_class = '';
                        //             let status = full.status_text != null ? full.status_text : '---';
            
                        //             if(full.status_text == "Pending Payment") {
                        //                 status_class = "badge text-info badge-light-info";
                        //             }else if(full.status_text== "Cancelled") {
                        //                 status_class = "text-danger badge-light-danger";
                        //             }else if(full.status_text == "Failed") {
                        //                 status_class = "badge text-danger badge-light-danger";
                        //             }else if(full.status_text == "Processing") {
                        //                 status_class = "badge text-warning badge-light-warning";
                        //             }else if(full.status_text == "On hold") {
                        //                 status_class = "badge text-primary badge-light-primary";
                        //             }else if(full.status_text == "Completed") {
                        //                 status_class = "badge text-success badge-light-success";
                        //             }else if(full.status_text == "Refunded") {
                        //                 status_class = "badge text-megna badge-light-megna";
                        //             }
                        //             return `<span class="`+status_class+`">`+status+`</span>`;
                        //         }
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
                        //             return full.grand_total != null ? (full.grand_total).toFixed(2) : '---';
                        //         }
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
                        //             return full.woo_id ? `<span class="fab fa-wordpress" style="font-size: 20px;">` : '---';
                        //         }
                        //     },
                        //     {
                        //         "render": function(data, type, full, meta) {
            
                        //             let pdf_btn = `<a href="`+create_pdf+`/`+full.custom_id+`" class="btn btn-danger btn-circle mr-1" title="download pdf"><i class="far fa-file-pdf mt-1"></i></a>`;
            
                        //             let view_btn = `<button class="btn btn-success btn-circle mr-1" ><i class="far fa-eye" title="view"></i></button>`;
            
            
                        //             if(full.customer != null) {
                        //                 let url = ``+checkoutpage+`/`+full.customer.id+``;
                        //                 let checkout_btn = `<a href="`+checkoutpage+`/`+full.customer.id + `/`+full.custom_id+`" class="btn btn-info btn-circle mr-1" title="checkout"><i class="fas fa-check-circle mt-1"></i></a>`;
            
                        //                 if(full.status_text == "Pending Payment") {
                        //                     return checkout_btn + pdf_btn + view_btn;
                        //                 }else{
                        //                     return pdf_btn + view_btn;
                        //                 }
                                        
                        //             }else{
                        //                 return pdf_btn + view_btn;
                        //             }
                                    
                        //         }
                        //     },
                        // ]
                    });

                    listOrders();

                    // $('#orders_table').parent().css('overflow', 'auto');
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
    
    function subsFilters() {
        let dval = $('#all_dates_subs').val();
        let pmval = $('#all_pm_subs').val();
        // let s_pd = $('#s_pd').val();
        let s_cust = $('#s_cust').val();

        let list = subs_list.filter(item => {
            if(pmval == 'none' && item.payment_method_title) return false;
            else {
                if(pmval && pmval != 'none') {
                    if(!item.payment_method_title) return false;
                    if(item.payment_method_title.toLowerCase() != pmval) return false;
                }
            }
            
            if(dval) {
                let dd = new Date(item.created_at);
                let mm = dd.toLocaleString('default', { month: 'long' });
                let yy = dd.getFullYear();
                if(mm+' '+yy != dval) return false;
            }
            if(s_cust) {
                if(!item.customer) return false;
                let cname = item.customer.first_name.toLowerCase()+item.customer.last_name.toLowerCase();
                if(cname.indexOf(s_cust.toLowerCase()) == -1 && item.customer.email.indexOf(s_cust.toLowerCase()) == -1) return false;
            }

            return true;
        });
        console.log(list);

        listSubs(list);
    }

    function listSubs(list=subs_list) {
        subs_tbl.clear().draw();

        $.each(list, function(key, full) {
            let td3 = '';
            if(full.woo_id) td3 = '#'+full.woo_id;
            if(full.customer) td3 += ' for '+full.customer.first_name+' '+full.customer.last_name;

            let td4 = '';
            if(full.lineItem) {
                full.lineItem.forEach(e => {
                    if(!td4) td4 = e.name;
                    else td4 += ' - '+e.name;
                });
            }

            let td5 = '';
            if(full.total) {
                td5 = full.currency+' '+full.total;
                if(full.billing_period) td5 += ' / '+ full.billing_period;
                if(full.payment_method_title) td5 += ' <span class="text-muted">Via '+full.payment_method_title+'</span>';
            }

            let row = `<tr>
                <td>${key+1}</div></td>
                <td>${full.status}</td>
                <td>${td3}</td>
                <td>${td4}</td>
                <td>${td5}</td>
                <td>${full.start_date ? moment(full.start_date).format(date_format) : ''}</td>
                <td>${full.trial_end_date ? moment(full.trial_end_date).format(date_format) : ''}</td>
                <td>${full.next_payment_date ? moment(full.next_payment_date).format(date_format) : ''}</td>
                <td></td>
                <td>${full.end_date ? moment(full.end_date).format(date_format) : ''}</td>
                <td></td>
            </tr>`;
            
            subs_tbl.row.add($(row)).draw();
        });
    }

    function get_all_subs() {
        $("#subs_table").DataTable().destroy();
        $.fn.dataTable.ext.errMode = "none";

        $.ajax({
            type: "GET",
            url: "{{url('get-all-subs')}}",
            success: function(result) {
                if(result.success) {
                    let opts = '';
                    let dates = [];
                    
                    subs_list = result.data;

                    result.data.forEach(e => {
                        let dd = new Date(e.created_at);
                        let mm = dd.toLocaleString('default', { month: 'long' });
                        let yy = dd.getFullYear();
                        if(dates.indexOf(mm+' '+yy) == -1) {
                            dates.push(mm+' '+yy);
                            opts += `<option value="${mm+' '+yy}">${mm+' '+yy}</option>`;
                        }
                    });
                    $('#all_dates_subs').html('<option value="">All Dates</option>'+opts);

                    subs_tbl =$("#subs_table").DataTable({
                        searching: true,
                        pageLength: 10,
                        data: result.data,
                        columnDefs: [
                            {
                                orderable: false,
                                targets: 0
                            }
                        ],
                    });

                    listSubs();
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
</script>