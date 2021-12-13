var companyTable = $('#file_export').DataTable({
    processing: true,
    pageLength: 20,
    fixedColumns: true,
    "scrollX": true,
    "autoWidth": false,
    columnDefs: [
        { className: "overflow-wrap", targets: "_all" },
        { width: '10px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: 0 },
        { width: '20px', orderable: false, targets: 1 },
        { width: '50px', orderable: false, searchable: false, 'className': 'dt-body-center', targets: [9] },
        { width: '150px', targets: [2, 3, 4, 5, 6, 7, 8] },
    ],
    order: [
        [2, 'desc']
    ],
    dom: 'Bfrtip',
    buttons: [
        'copy', 'csv', 'excel', 'pdf', 'print'
    ]
});
// $('.dt-buttons').addClass('d-flex justify-content-end mb-3')
$('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary mr-1');

// Handle click on "Select all" control
$('#select-all').on('click', function() {
    // Get all rows with search applied
    var rows = companyTable.rows({ 'search': 'applied' }).nodes();
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