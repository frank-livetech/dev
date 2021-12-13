$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
    }
});
let assets_table_list = '';
let categoriesArr = '';
let customersArr = '';

$(document).ready(function () {
    
    assets_table_list = $('#asset-table-list').DataTable();
    $('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();

        $(this).toggleClass('btn-success');
        $(this).toggleClass('btn-secondary');

        // Get the column API object
        var column = assets_table_list.column( $(this).attr('data-column') );

        // Toggle the visibility
        column.visible( ! column.visible() );
    } );


    $('#as_select').multipleSelect({
        width:300,
        onClick:function(view) {
            var selectedItems = $('#as_select').multipleSelect("getSelects");
            for(var i =0; i < 10; i++) {
                columns = assets_table_list.column(i).visible(0);
            }
            for(var i = 0; i < selectedItems.length; i++) {
                var s = selectedItems[i];
                assets_table_list.column(s).visible(1);
            }
            $('#asset-table-list').css('width','100%');
        },
        onCheckAll:function() {
            for(var i =0; i < 10; i++) {
                columns = assets_table_list.column(i).visible(1);
            }
        },
        onUncheckAll:function() {
            for(var i =0; i < 10; i++) {
                columns = assets_table_list.column(i).visible(0);
            }
            $('#asset-table-list').css('width','100%');
        }
    });

    get_asset_table_list();
    $("#checkAll").click(function(){
        $('input:checkbox.assets').not(this).prop('checked', this.checked);
    });

    getFormsTemplates();

    $(document).on('click', '.show-password-btn', function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $(this).parent().find("input");
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});

function ShowAssetModel() {

    var label = $("#edit-asset").text();
    if (label == 'Edit Asset') {
        $("#edit-asset").html('Save Asset');
    }
    $('#asset').modal('show');

    $('#categories').val('').trigger("change");
    $('#companies_assign_to').val('').trigger("change");
    $('#customers_assign_to').val('').trigger("change");
    //$('#image').val('Choose image').trigger("change");
}