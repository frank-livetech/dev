function light(color = null, bgColor = null) {
    // if (!color || !bgColor) {
    $.ajax({
            url: colorUrl,
            dataType: 'json',
            success: function(data) {
                color = color !== null ? color : data.text_light;
                bgColor = bgColor !== null ? bgColor : data.bg_light;
                console.log(color, bgColor);
            }
        })
        // }
    let textLight = `
        <style type="text/css" id="text-color-pr">
            :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
                color: ${color} ;
            }

            .dd-item.black button:before,
            .dd-item button:before, .form-control-file,
            .sidebar-link.has-arrow>.hide-menu,
            .breadcrumb.black>.breadcrumb-item:before,
            .breadcrumb>.breadcrumb-item:before {
                color: ${color} ;
            }
        </style>
        `;

    let bgLight = `
        <style type="text/css" id="bg-color-pr">
            #main-wrapper[data-layout=horizontal] .topbar .navbar-collapse[data-navbarbg=skin1],
            #main-wrapper[data-layout=horizontal] .topbar[data-navbarbg=skin1],
            #main-wrapper[data-layout=vertical] .topbar .navbar-collapse[data-navbarbg=skin1],
            #main-wrapper[data-layout=vertical] .topbar[data-navbarbg=skin1],
            .theme-color .theme-item .theme-link[data-navbarbg=skin1] {
                background: ${bgColor} ;
            }
        
            body, .select-box, .road-map-form, .select2-selection__rendered, .card-body,
            .top-navbar,
            .navbar,
            .nav-link,
            .collapse,
            .navbar-collapse,
            .nav-item,
            .left-sidebar,
            .sidebar-footer,
            .sidebar-link,
            .page-breadcrumb,
            .page-wrapper,
            .card,
            .dd-list,
            .footer,
            .dd-handle, .bootstrap-switch-label,
            .modal-content, .form-control-file, .custom-file, .custom-file-label,
            .blacknav, .black,
            .breadcrumb, .pagination, .paginate_button,
            .container-fluid .pcr-current-color {
                background-color: ${bgColor} !important;
            }
        
            .bg-light, .road-map-form, .select2-selection__rendered,
            .chat-list .chat-item .chat-content .box.bg-light-info,
            .custom-file-label::after,
            .customizer,
            .daterangepicker .ranges li:hover,
            .daterangepicker td.available:hover,
            .daterangepicker th.available:hover,
            .f-icon:hover,
            .if-icon:hover,
            .input-group-text,
            .m-icon:hover,
            .mce-panel,
            .myadmin-dd .dd-list .dd-item .dd-handle,
            .myadmin-dd-empty .dd-list .dd3-content,
            .myadmin-dd-empty .dd-list .dd3-handle,
            .ql-snow .ql-picker-options,
            .select2-container--classic .select2-search--dropdown .select2-search__field,
            .select2-container--default .select2-results__option[aria-selected=true],
            .select2-dropdown,
            .sl-icon:hover,
            .t-icon:hover,
            .table .thead-light th,
            .table-hover tbody tr:hover,
            .toast,
            .toast-header, .bootstrap-switch-label,
            .w-icon:hover, .form-control-file, .custom-file, .custom-file-label,
            pre[class*=language-] {
                background-color: ${bgColor} !important;
            }
        </style>
        `;
    $('#text-color-pr').remove();
    $('head').append(textLight);
    $('#bg-color-pr').remove();
    $('head').append(bgLight);
}

function dark(color = null, bgColor = null) {
    // if (!color || !bgColor) {
    $.ajax({
            url: colorUrl,
            dataType: 'json',
            success: function(data) {
                color = color !== null ? color : data.text_dark;
                bgColor = bgColor !== null ? bgColor : data.bg_dark;
                console.log(color, bgColor);
            }
        })
        // }
    let textDark = `
        <style type="text/css" id="text-color-pr">
            // :not(.pcr-button):not(.on):not(.off):not(.picker):not(.pcr-save) {
            //     color: ${color} ;
            // }

            
            body[data-theme=dark] .dataTables_wrapper .dataTables_length, 
            body[data-theme=dark] .dataTables_wrapper .dataTables_filter, 
            body[data-theme=dark] .dataTables_wrapper .dataTables_info, 
            body[data-theme=dark] .dataTables_wrapper .dataTables_processing, 
            body[data-theme=dark] .dataTables_wrapper .dataTables_paginate
            body[data-theme=dark] .dataTables_wrapper .dataTables_filter{
                color:${color} ;
            }
            
            .dd-item.black button:before,
            .dd-item button:before, .form-control-file,
            .sidebar-link.has-arrow>.hide-menu,
            .breadcrumb.black>.breadcrumb-item:before,
            .breadcrumb>.breadcrumb-item:before {
                color: ${color};
            }
        </style>
        `;
    let bgDark = `
        <style type="text/css" id="bg-color-pr">
            #main-wrapper[data-layout=horizontal] .topbar .navbar-collapse[data-navbarbg=skin1],
            #main-wrapper[data-layout=horizontal] .topbar[data-navbarbg=skin1],
            #main-wrapper[data-layout=vertical] .topbar .navbar-collapse[data-navbarbg=skin1],
            #main-wrapper[data-layout=vertical] .topbar[data-navbarbg=skin1],
            .theme-color .theme-item .theme-link[data-navbarbg=skin1] {
                background: ${bgColor} ;
            }
        
            body, .select-box, .road-map-form, .select2-selection__rendered,
            .top-navbar,
            .navbar,
            .nav-link,
            .collapse,
            .navbar-collapse,
            .nav-item,
            .left-sidebar,
            .sidebar-footer,
            .sidebar-link,
            .page-breadcrumb,
            .page-wrapper,
            .card,
            .dd-list,
            .footer,
            .dd-handle,
            .breadcrumb,
            .blacknav,
            .sidebar-footer-black,
            .custom-file-label, .bootstrap-switch-label,
            .modal-content, .form-control-file, .custom-file,
            .pcr-current-color, .pagination, .paginate_button{
                background-color: ${bgColor} !important;
            }
        
            body[data-theme=dark] .bg-light,
            body[data-theme=dark] .road-map-form,
            body[data-theme=dark] .select2-selection__rendered,
            body[data-theme=dark] .chat-list .chat-item .chat-content .box.bg-light-info,
            body[data-theme=dark] .custom-file-label::after,
            body[data-theme=dark] .customizer,
            body[data-theme=dark] .daterangepicker .ranges li:hover,
            body[data-theme=dark] .daterangepicker td.available:hover,
            body[data-theme=dark] .daterangepicker th.available:hover,
            body[data-theme=dark] .f-icon:hover,
            body[data-theme=dark] .if-icon:hover,
            body[data-theme=dark] .input-group-text,
            body[data-theme=dark] .m-icon:hover,
            body[data-theme=dark] .mce-panel,
            body[data-theme=dark] .myadmin-dd .dd-list .dd-item .dd-handle,
            body[data-theme=dark] .myadmin-dd-empty .dd-list .dd3-content,
            body[data-theme=dark] .myadmin-dd-empty .dd-list .dd3-handle,
            body[data-theme=dark] .ql-snow .ql-picker-options,
            body[data-theme=dark] .select2-container--classic .select2-search--dropdown .select2-search__field,
            body[data-theme=dark] .select2-container--default .select2-results__option[aria-selected=true],
            body[data-theme=dark] .select2-dropdown,
            body[data-theme=dark] .sl-icon:hover,
            body[data-theme=dark] .t-icon:hover,
            body[data-theme=dark] .table .thead-light th,
            body[data-theme=dark] .table-hover tbody tr:hover,
            body[data-theme=dark] .toast,
            body[data-theme=dark] .toast-header,
            body[data-theme=dark] .w-icon:hover,
            body[data-theme=dark] .form-control-file,
            body[data-theme=dark] .custom-file,
            body[data-theme=dark] .custom-file-label,
            body[data-theme=dark] .bootstrap-switch-label,
            body[data-theme=dark] pre[class*=language-] {
                background-color: ${bgColor} !important;
            }
        </style>
        `;
    $('#text-color-pr').remove();
    $('head').append(textDark);
    $('#bg-color-pr').remove();
    $('head').append(bgDark);
}