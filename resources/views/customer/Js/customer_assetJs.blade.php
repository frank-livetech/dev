{{-- <script>
    function get_cust_asset_table_list() {
    $.ajax({
        type: "GET",
        url: "{{url('/get-customer-asset')}}",
        data: {
            customer_id: asset_customer_uid,
            company_id: asset_company_id,
            ticket_id: asset_ticket_id,
        },
        dataType: 'json',
        success: function(data) {
            var obj = data.assets;
            asset_arr = obj;

            $('.cust-asset-table-list').DataTable().destroy();
            $.fn.dataTable.ext.errMode = 'none';
            var tbl = $('.cust-asset-table-list').DataTable({
                data: obj,
                "pageLength": 50,
                "bInfo": false,
                "paging": true,
                "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                    $(nRow).attr('id', 'row__'+aData.id);
                },
                'columnDefs': [
                    {
                        'targets': 1,
                        'createdCell':  function (td, cellData, rowData, row, col) {
                            $(td).attr('id',rowData.id); 
                        }
                    }
                ],
                columns: [
                    {
                        "render": function() {
                            return `<div class="text-center"><input type="checkbox" id="" name="" value=""></div>`;
                        }
                    },
                    {
                        "className":'details-control text-left',
                        "orderable":false,
                        "data":null,
                        "defaultContent": ''
                    },
                    {
                        
                        "render": function(data, type, full, meta) {
                            if(full.asset_fields != null) {
                                let general_field = full.asset_fields[0]['id'];
                                let index = 'fl_'+general_field;
                                if(full.asset_record != null) {
                                    let gen_fld_record = full.asset_record[index];
                                    return `<a href="{{url('general-info')}}/` + full.id + `">` + gen_fld_record + `</a>`;
                                }else{
                                    return `-`;
                                }
                            }else{
                                return `-`;
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {

                            if (full.template != null) {
                                if (full.template.title != null) {
                                    return full.template.title;
                                } else {
                                    return '-';
                                }
                            } else {
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if(full.company != null) {
                                let url = `<a href="${root}/company-profile/${full.company.id}">${full.company.name}</a>`;
                                return full.company.name != null ? url : '-';
                            }else{
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            if(full.customer != null) {
                                return `<a href="${root}/customer-profile/${full.customer.id}">${full.customer.first_name} ${full.customer.last_name}</a>`;
                            }else{
                                return '-';
                            }
                        }
                    },
                    {
                        "render": function(data, type, full, meta) {
                            return `
                                <div class="d-flex justify-content-center">
                                    <button onclick="editAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button onclick="deleteAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`;
                        }
                    },
                ],
            });

            // Add event listener for opening and closing details
            $('.asset-table-list tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = tbl.row( tr );
                var id = $(this).attr('id');

                if ( row.child.isShown() ) {
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    row.child( showAssetDetails(id) ).show();
                    tr.addClass('shown');
                }
            });

        },
        error: function(f) {
            console.log('get assets error ', f);
        }
    });

}
</script> --}}