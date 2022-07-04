<script type="text/javascript">
    // Asset Script Blade
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    let assets_table_list = '';
    let categoriesArr = '';
    let customersArr = '';

    $(document).ready(function() {

        let url = window.location.href;

        if (url.includes('asset-manager')) {
            getFormsTemplates();
            get_asset_table_list();
        }

        assets_table_list = $('#asset-table-list').DataTable();

        $("#asset-table-list").click(function() {
            $('input:checkbox.assets').not(this).prop('checked', this.checked);
        });



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
</script>
