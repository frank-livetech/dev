<script>

    // Asset Manager Index SCript Blade
    
    let get_assets_route = "{{asset('/get-assets')}}";
    let del_asset_route = "{{asset('/delete-asset')}}";
    let save_asset_records_route = "{{asset('/save-asset-records')}}";
    let templates_fetch_route = "{{asset('/get-asset-templates')}}";
    let template_submit_route = "{{asset('/save-asset-template')}}";
    let general_info_route = "{{asset('/general-info')}}";
    var show_asset = "{{asset('/show-single-assets')}}";
    var update_asset = "{{asset('/update-assets')}}";
    let templates = null;
    let asset_customer_uid = '';
    let asset_company_id = '';
    let asset_project_id = '';
    let asset_ticket_id = '';
</script>

<!-- <script src="{{asset('public/js/help_desk/asset_manager/actions.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/help_desk/asset_manager/asset.js').'?ver='.rand()}}"></script>
<script src="{{asset('public/js/help_desk/asset_manager/template.js').'?ver='.rand()}}"></script> -->
@include('js_files.help_desk.asset_manager.templateJs')
    @include('js_files.help_desk.asset_manager.actionsJs')
    @include('js_files.help_desk.asset_manager.assetJs')

<script>
    $(document).ready(function() {
        var ipv4_address = $('#ipv4');
        ipv4_address.inputmask({
            alias: "ip",
            greedy: false
        });
        
        getAllTemplate();
    })

    function getAllTemplate() {
        $.ajax({
            type: "GET",
            url: "{{url('/get-all-templates')}}",
            dataType: 'json',
            success: function(data) {
                var obj = data.data;
                $('#asset-temp-table-list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#asset-temp-table-list').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
                    "fnCreatedRow": function( nRow, aData, iDataIndex ) {
                        $(nRow).attr('id', 'row__'+aData.id);
                    },
                    columns: [
                        {
                        "render": function (data, type, full, meta) {
                            return '-';
                        }
                    },{
                        "data": null,
                        "defaultContent": ""
                    },
                    {
                        "render": function (data, type, full, meta) {
                            return full.title != null ? full.title : '-';
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            return `
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button onclick="deleteTemplate(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`;
                        }
                    },

                    ],
                });

                tbl.on('order.dt search.dt', function () {
                    tbl.column(1, {
                        search: 'applied',
                        order: 'applied'
                    }).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();

            },
            error: function(f) {
                console.log('get assets error ', f);
            }
        });
    }

    function deleteTemplate(id) {
        Swal.fire({
            title: 'Do you want to delete?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{route('delete.assetTemplate')}}",
                    data: { id:id },
                    dataType: 'json',
                    success: function(data) {

                        if (data.status == 200 && data.success == true) {
                            toastr.success(data.message, { timeOut: 5000 });
                            $("#row__"+id).remove();
                        } else {
                            toastr.error(data.message, { timeOut: 5000 });
                        }
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            }
        });
    }


    $("#customer_id").on("change" , function() {
        $("#company_id").empty();
        let root = `<option value="">Choose</option>`;
        if($(this).val() != '') {
            let item = customers.find(item => item.id == $(this).val() );
            if(item != null) {
                if(item.company_id != null) {
                    let company = companies.find(com => com.id == item.company_id);
                    let option = `<option value="${company.id}"> ${company.name} </option>`;
                    $("#company_id").append(root + option).trigger('change');
                }
            }
        }else{
            let option = ``;
            for(let data of companies)  {
                option += `<option value="${data.id}"> ${data.name} </option>`;
            }
            $("#company_id").append(root + option).trigger('change');
        }

    });
  

</script>