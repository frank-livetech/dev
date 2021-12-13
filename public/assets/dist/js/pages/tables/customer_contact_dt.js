//=============================================//
//    File export                              //
//=============================================//
var customerTable = $('#file_export').DataTable({
    processing: true,
    "scrollX": true,
    pageLength: 20,
    fixedColumns: true,
    "autoWidth": false,
    'columnDefs': [{
            'targets': 0,
            width: '10px',
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function(data, type, full, meta) {
                return '<input type="checkbox" name="select_all[' + data + ']" value="' + $('<div/>').text(data).html() + '">';
            }
        }, { className: "overflow-wrap", targets: "_all" },
        { width: '20px', targets: [1] },
        { width: '114px', targets: [2, 3] },
        { width: '170px', targets: [4, 5] },
        {
            'searchable': false,
            width: '60px',
            'orderable': false,
            targets: 6
        },
        {
            'targets': 3,
            'render': function(data, type, full, meta) {
                return `<a href="customer-profile/${full.woo_id}">${data}</a>`;
            }
        },
    ],
    'order': [
        [1, 'asc']
    ],
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ],
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