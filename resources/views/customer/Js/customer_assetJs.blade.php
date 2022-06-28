<script>
    $(document).ready(function() {
        get_cust_asset_table_list();
    });
</script>
    
<script>
     let asset_arr = [];
     
    function get_cust_asset_table_list() {
        $.ajax({
            type: "GET",
            url: "{{url('/get-customer-asset')}}",
            dataType: 'json',
            success: function(data) {
                var obj = data.assets;
                console.log(obj);
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
                        // {
                        //     "render": function(data, type, full, meta) {
                        //         return `
                        //             <div class="d-flex justify-content-center">
                        //                 <button onclick="editAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                        //                 <i class="fas fa-pencil-alt"></i></button>&nbsp;
                        //                 <button onclick="deleteAsset(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                        //                 <i class="fa fa-trash"></i></button>
                        //             </div>`;
                        //     }
                        // },
                    ],
                });
    
                // Add event listener for opening and closing details
                $('.cust-asset-table-list tbody').on('click', 'td.details-control', function () {
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
    function showAssetDetails(id) {
    
        let item = asset_arr.find(item => item.id == id);
        console.log(item , "item");
        let template_html  = ``;
        let customer_html = ``;
        let company_html = ``;
        let asset_field_html = ``;
        let asset_field_tr = ``;
    
        if(item != null) {
    
            // if(item.customer != null) {
    
            //     customer_html = `
            //     <div class="col-md-6 text-start rounded">
            //         <div class="bg-light p-2 rounded">
            //             <h4 class="fw-bolder"> Customer Detail </h4>
            //             <hr>
            //             <div>
            //                 <table class="table table -hover table-hover">
            //                     <tbody>
            //                         <tr>
            //                             <td class="fw-bolder"> Name </td>
            //                             <td> ${item.customer.first_name != null ? item.customer.first_name : '---' } ${item.customer.last_name != null ? item.customer.last_name : '---' }  ${item.customer.first_name != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.customer.first_name+'`)" style="float:right"></i>': ''}</td>
            //                         </tr>
            //                         <tr>
            //                             <td class="fw-bolder"> Email </td>
            //                             <td> ${item.customer.email != null ? item.customer.email : '---' }  ${item.customer.email != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.customer.email+'`)" style="float:right"></i>': ''}</td>
            //                         </tr>
            //                         <tr>
            //                             <td class="fw-bolder"> Phone </td>
            //                             <td> ${item.customer.phone != null ? item.customer.phone : '---' } ${item.customer.phone!= null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.customer.phone+'`)" style="float:right"></i>': ''}</td>
            //                         </tr>                                                        
            //                     </tbody>
            //                 </table>
            //             </div>
            //         </div>
            //     </div>`;
            // }
    
            // if(item.company != null) {
    
            //     company_html = `
            //     <div class="col-md-6 text-start rounded">
            //         <div class="bg-light p-2 rounded">
                        
            //             <div class="d-flex justify-content-between">
            //                 <h4 class="fw-bolder"> Company Detail </h4>
            //                 <span class="small text-success fw-bolder url_copy_${item.id}"></span>
            //             </div>
            //             <hr>
            //             <div>
            //                 <table class="table table -hover table-hover">
            //                     <tbody>
            //                         <tr>
            //                             <td class="fw-bolder"> Name </td>
            //                             <td> ${item.company.name != null ? item.company.name : '---' } ${item.company.name != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.company.name+'`)" style="float:right"></i>': ''}</td>
            //                         </tr>
            //                         <tr>
            //                             <td class="fw-bolder"> Email </td>
            //                             <td> ${item.company.email != null ? item.company.email : '---'}  ${item.company.email != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.company.email+'`)" style="float:right"></i>': ''}</td>
            //                         </tr>
            //                         <tr>
            //                             <td class="fw-bolder"> Phone </td>
            //                             <td> ${item.company.phone != null ? item.company.phone : '---'} ${item.company.phone != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.company.phone+'`)" style="float:right"></i>': ''}</td>
            //                         </tr>                                                        
            //                     </tbody>
            //                 </table>
            //             </div>
            //         </div>
            //     </div>`;
    
            // }
    
            if(item.asset_fields != null) {
    
                for( let data of item.asset_fields) {
    
                    if(item.asset_record) {
                        let custom_key = 'fl_' + data.id;
                        let key = item.asset_record[custom_key];
    
    
                        asset_field_tr += `
                            <tr>
                                <td class="fw-bolder" width="170"> ${data.label} </td>
                                <td width="300px">${key != null ? key : '-'}</td>
                                <td>${key != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+key+'`)" style="float:left"></i>': ''}</td>
                            </tr>`;
    
                    }
                }
    
                asset_field_html += `
                    <div class="col-md-12 text-start rounded">
                        <div class="bg-light p-2 rounded">
                            
                            <div>
                                <table class="table table -hover table-hover">
                                    <tbody>
                                    
                                        <tr>
                                            <td class="fw-bolder" width="170"> Asset Type </td>
                                            <td width="300px">${item.template.title != null ? item.template.title : '---'} </td>
                                            <td>${item.template.title != null ? '<i class="far fa-copy" onclick="copyToClipBoard(`'+item.template.title+'`)" style="float:left"></i>': ''}</td>
                                        </tr>
                                        ${asset_field_tr}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;
            }
    
    
        }
    
        return `
            <div class="row">
                ${item.asset_fields != null ? asset_field_html : ''}
            </div>
            <div class="row p-1">
                ${template_html}            
                ${customer_html}            
                ${company_html}            
            </div>
            `;
    }
    
    function copyToClipBoard(text) {
    
        let $input = $("<input>");
        $('body').append($input);
    
        $input.val(text).select();
        document.execCommand('copy');
        $input.remove();
    
        alertNotification('success', 'Success' , 'Text copied' );
    }
</script>