<script>
    // Asset Manager Index SCript Blade

    let get_assets_route = "{{asset('/get-assets')}}";
    let del_asset_route = "{{asset('/delete-asset')}}";
    let save_asset_records_route = "{{asset('/save-asset-records')}}";
    let templates_fetch_route = "{{asset('/get-asset-templates')}}";
    let template_submit_route = "{{asset('/save-asset-template')}}";
    let template_update_submit_route = "{{asset('/update-asset-template')}}";
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
                asset_type_arr = data.data;
                $('#asset-temp-table-list').DataTable().destroy();
                $.fn.dataTable.ext.errMode = 'none';
                var tbl = $('#asset-temp-table-list').DataTable({
                    data: obj,
                    "pageLength": 10,
                    "bInfo": false,
                    "paging": true,
                    "fnCreatedRow": function(nRow, aData, iDataIndex) {
                        $(nRow).attr('id', 'row__' + aData.id);
                    },
                    columns: [
                        {
                            "render": function(data, type, full, meta) {
                                return full.title != null ? full.title : '-';
                            }
                        },
                        {
                            "render": function(data, type, full, meta) {
                                return `
                                <div class="d-flex">
                                    <button onclick="editTemplate(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-success waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fas fa-pencil-alt"></i></button>&nbsp;
                                    <button onclick="deleteTemplate(${full.id})" type="button" class="btn btn-icon rounded-circle btn-outline-danger waves-effect" style="padding: 0.715rem 0.936rem !important;">
                                    <i class="fa fa-trash"></i></button>
                                </div>`;
                            }
                        },

                    ],
                });

                // tbl.on('order.dt search.dt', function() {
                //     tbl.column(1, {
                //         search: 'applied',
                //         order: 'applied'
                //     }).nodes().each(function(cell, i) {
                //         cell.innerHTML = i + 1;
                //     });
                // }).draw();

            },
            error: function(f) {
                console.log('get assets error ', f);
            }
        });
    }

    function editTemplate(id) {
        $('#shw_assettype').show();
        let item = asset_type_arr.find(item => item.id == id);
        let single_input = ``;
        let row = ``;
        template_id = id
        //check action variable set id to update template
        check_action = id;
        if (item != null) {
            $("#tempTitle").val(item.title)

            if (item.fields != null && item.fields.length != 0) {
                fields_list_data = item.fields;

                for (let [i,data] of item.fields.entries()) {

                    single_input += `
                        <div class="appends ui-sortable-handle col-md-${data.col_width}" data-id="2" data-col="12" style="opacity: 1;">
                            <div class="card card-hover m-1 style=" box-shadow:="" 0="" 12px="" 24px="" rgb(34="" 41="" 47="" 32%)="" !important;""="">
                                <div class="card-body" style="box-shadow: 0 12px 24px 0 rgb(34 41 47 / 32%) !important;">
                                    <div class="d-flex justify-content-between">
                                        <div class="title">
                                            <h5 class="card-title small mb-0"><i class="fas fa-grip-vertical pr-2" style="color:grey;"></i> ${data.label}</h5>
                                        </div>
                                        <div class="actions" style="position:absolute; top:18px;right:8px">
                                            <i onclick="removeField(${data.asset_forms_id},${data.id},this)" class="fas fa-trash-alt red float-right pl-3" style="cursor: pointer;"></i>
                                            <a href="javascript:templateSetting('${data.type}', ${data.asset_forms_id} , 'update' , ${data.id})" class="float-right">
                                            <i class="fas fa-cog"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                        if(item.fields.length-1 ==  i){
                            single_input +=`<div class="row connectedSortable border" id="sortable-row-last" style="min-height:10px; display: none;">
                                                <div class="appends d-none"></div>
                                            </div>`
                        }

                    if (data.col_width != 12) {
                        row = `<div class="row connectedSortable border firstfield ui-sortable" id="sortable-row-${data.id}">${single_input}</div>`;
                    } else {
                        row = `<div class="row connectedSortable border firstfield ui-sortable" id="sortable-row-${data.id}">${single_input}</div>`;
                    }

                }




                $('.tail').html(row);

            }



        }
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
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {

                        if (data.status == 200 && data.success == true) {
                            alertNotification('success', 'Success', data.message);
                            $("#row__" + id).remove();
                        } else {
                            alertNotification('error', 'Error', data.message);
                        }
                    },
                    error: function(e) {
                        console.log(e)
                    }
                });
            }
        });
    }
</script>
