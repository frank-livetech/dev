//=============================================//
//    File export                              //
//=============================================//
var customerTable = $('#file_export').DataTable({
    processing: true,
    "scrollX": true,
    pageLength: 20,
    fixedColumns: true,
    "autoWidth": false,
    // ajax: {
    //     url: hitUrl,
    //     type: 'GET',
    //     dataType: 'json',
    //     dataSrc: 'customer'
    // },
    // columns: [
    //     { data: 'id' },
    //     { data: 'id' },
    //     { data: 'woo_id' },
    //     { data: 'woo_username' },
    //     { data: 'name' },
    //     { data: 'email' },
    //     { data: 'address' },
    //     { data: 'phone' },
    //     { data: 'company_name' },
    //     { data: 'vertical' },
    //     { data: 'business_residential', orderable: false },
    // ],
    'columnDefs': [
        { width: '10px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: 0 },
        { width: '20px', orderable: false, targets: 1 },
        { searchable: false, orderable: false, targets: 10 },
        { width: '50px', targets: [2, 11] },
        { width: '180px', targets: [3, 4, 5, 6] },
        { width: '140px', targets: [7, 8, 9, 10] },
        { searchable: false, targets: 0 },
    ],
    order: [
        [2, 'desc']
    ],
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print',
        {
            text: 'My button',
            className: 'buttons-highlight',
            action: function(e, dt, node, config) {
                alert('Button activated');
            }
        }
    ]
});
// $('.dt-buttons').addClass('d-flex justify-content-end mb-3')
$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

// Handle click on "Select all" control
$('#select-all').on('click', function() {
    // Get all rows with search applied
    var rows = customerTable.rows({ 'search': 'applied' }).nodes();
    // Check/uncheck checkboxes for all rows in the table
    $('input[type="checkbox"]', rows).prop('checked', this.checked);
});

// Handle click on checkbox to set state of "Select all" control
$('#file_export tbody').on('change', 'input[type="checkbox"]', function() {
    // If checkbox is not checked
    if (!this.checked) {
        var el = $('#select-all').get(0);
        // If "Select all" control is checked and has 'indeterminate' property
        if (el && el.checked && ('indeterminate' in el)) {
            // Set visual state of "Select all" control
            // as 'indeterminate'
            el.indeterminate = true;
        }
    }
});