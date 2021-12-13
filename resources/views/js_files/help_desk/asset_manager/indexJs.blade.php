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
                console.log(data , "data");
                var obj = data.data;
                console.log(obj , "obj")

                $('#asset-temp-table-list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#asset-temp-table-list').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
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
                            return '-';
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
  

</script>