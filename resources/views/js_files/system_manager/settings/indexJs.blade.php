<script>
// data
let departments_table_list = {!!json_encode($departments) !!};

// routes
let save_colors_route = "{{asset('/save-color-settings')}}";
let get_deps_route = "{{asset('/get-departments')}}";
let get_status_route = "{{asset('/get-statuses')}}";
let get_types_route = "{{asset('/get-types')}}";
let get_customer_types_route = "{{asset('/get-customer-types')}}";
let get_dispatch_status_route = "{{asset('/get-dispatch-status')}}";
let get_project_type_route = "{{asset('/get-project-type')}}";
let get_priorities_route = "{{asset('/get-priorities')}}";
let del_status_route = "{{asset('/delete-status')}}";
let del_type_route = "{{asset('/delete-type')}}";
let del_customer_type_route = "{{asset('/delete-customer-type')}}";
let del_dispatch_status_route = "{{asset('/delete-dispatch-status')}}";
let del_project_type_route = "{{asset('/delete-project-type')}}";
let del_priority_route = "{{asset('/delete-priority')}}";
let del_dept_route = "{{asset('/delete-department')}}";
let mails_route = "{{asset('/get-mails')}}";
let verify_conn_route = "{{asset('verify-connection')}}";
let save_mail_route = "{{asset('save-mail')}}";
let del_mail_route = "{{asset('/del-mail')}}";
let ticket_format_route = "{{asset('ticket-format')}}";
let edit_email_by_id = "{{asset('get_mail_by_id')}}";
let update_email = "{{asset('update_email')}}";
let save_sys_date_time = "{{asset('save_sys_date_time')}}";
let save_billing_orderid_format = "{{asset('save_billing_orderid_format')}}";
let save_mode_form = "{{asset('save_mode_form')}}";
var send_recap_mails = "{{url('send_recap_mails')}}";

let get_all_resTemplate = "{{asset('get_all_resTemplate')}}";
let show_response_template = "{{asset('show_response_template')}}";
let get_all_sla = "{{asset('get_all_sla')}}";
let delete_sla = "{{asset('delete_sla')}}";
let delete_cat = "{{asset('delete_cat')}}";
let update_sla = "{{asset('update_sla')}}";

let dept_details_route = "{{asset('department-details')}}";
// Setting Index Script Blade

$(function() {
    if ($("#mymce").length > 0) {
        tinymce.init({
            selector: "textarea#mymce",
            theme: "modern",
            mobile: {
            theme: 'silver'
          },
            height: 100,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        });
    }
    if ($("#mymce2").length > 0) {
        tinymce.init({
            selector: "textarea#mymce2",
            theme: "modern",
            mobile: {
            theme: 'silver'
          },
            height: 100,
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "save table contextmenu directionality emoticons template paste textcolor"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        });
    }
});

$('.rightForm').hide();
$('.menuBox').hide();
$('.clickMe').click(function() {
    //    alert("puck");
    $(this).siblings('.rightForm').toggle('1000');
    $(this).siblings('.menuBox').hide('1000');

});
$('.editTable').click(function() {
    //    alert("puck");
    $(this).siblings('.rightForm').hide('1000');
    $(this).siblings('.menuBox').toggle('1000');

});

function saveOrderFormat(value, format) {
    $.ajax({
        type: "POST",
        url: save_billing_orderid_format,
        data: {
            sys_key: format,
            value: value,
        },
        success: function(data) {
            // console.log(data);
            if (data.status_code == 200 & data.success == true) {
                // toastr.success(data.message, { timeOut: 5000 });
            } else {
                // toastr.error(data.message, { timeOut: 5000 });
            }
        },
        error: function(e) {
            console.log(e)
        }
    });
}
$(".lightModeForm").submit(function(e) {
    e.preventDefault();

    var form_data = new FormData(this);

    $.ajax({
        type: "post",
        url: save_mode_form,
        data: form_data,
        dataType: 'json',
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
            // alert("In");
            // console.log(data);
            if (data.status_code == 200 & data.success == true) {
                // toastr.success(data.message, { timeOut: 5000 });
            } else {
                // toastr.error(data.message, { timeOut: 5000 });
            }
        },
        error: function(e) {
            console.log(e)
        }
    });

});
$(document).ready(function() {
    // var ipv4_address = $('#ipv4');
    // ipv4_address.inputmask({
    //     alias: "ip",
    //     greedy: false
    // });
    $("#war_check").hide();

    $(".dailyRecap").hide();
    $(".monthlyRecap").hide();
    $(".weeklyRecap").hide();

    $('#dailyRecap').click(function() {
        this.checked ? $('.dailyRecap').show() : $('.dailyRecap').hide(); //time for show
    });
    $('#weeklyRecap').click(function() {
        this.checked ? $('.weeklyRecap').show() : $('.weeklyRecap').hide(); //time for show
    });
    $('#monthlyRecap').click(function() {
        this.checked ? $('.monthlyRecap').show() : $('.monthlyRecap').hide(); //time for show
    });

    $("#recapNoti1").click(function() {
        if ($(this).is(':checked')) {
            $(".recapNotiDiv").show();
        }
    });

    $("#recapNoti2").click(function() {
        if ($(this).is(':checked')) {
            $(".recapNotiDiv").hide();
        }
    });

    // var selectedScheme = 'bg-info';
    // $('#main_sys_back').change(function(){
    //     $('#main_sys_back').removeClass(selectedScheme).addClass($(this).val());
    //     selectedScheme = $(this).val();
    //     // alert("jin");
    // });
    // var selectedScheme1 = 'bg-info';
    // $('#header_back').change(function(){
    //     $('#header_back').removeClass(selectedScheme1).addClass($(this).val());
    //     selectedScheme1 = $(this).val();
    // });
    // var selectedScheme2 = 'bg-info';
    // $('#card_back').change(function(){
    //     $('#card_back').removeClass(selectedScheme2).addClass($(this).val());
    //     selectedScheme2 = $(this).val();
    // });
    // var selectedScheme3 = 'bg-info';
    // $('#tbl_head_back').change(function(){
    //     $('#tbl_head_back').removeClass(selectedScheme3).addClass($(this).val());
    //     selectedScheme3 = $(this).val();
    // });
    // var selectedScheme4 = 'bg-info';
    // $('#table_row_back').change(function(){
    //     $('#table_row_back').removeClass(selectedScheme4).addClass($(this).val());
    //     selectedScheme4 = $(this).val();
    // });
    // var selectedScheme5 = 'bg-info';
    // $('#main_font_back').change(function(){
    //     $('#main_font_back').removeClass(selectedScheme5).addClass($(this).val());
    //     selectedScheme5 = $(this).val();
    // });

    // var selectedScheme6 = 'bg-info';
    // $('#bread_crum_back').change(function(){
    //     $('#bread_crum_back').removeClass(selectedScheme6).addClass($(this).val());
    //     selectedScheme6 = $(this).val();
    // });

    // var selectedSchemeDrk = 'bg-info';
    // $('#drk_main_sys_back').change(function(){
    //     $('#drk_main_sys_back').removeClass(selectedSchemeDrk).addClass($(this).val());
    //     selectedSchemeDrk = $(this).val();
    // });
    // var selectedSchemeDrk1 = 'bg-info';
    // $('#drk_header_back').change(function(){
    //     $('#drk_header_back').removeClass(selectedSchemeDrk1).addClass($(this).val());
    //     selectedSchemeDrk1 = $(this).val();
    // });
    // var selectedSchemeDrk2 = 'bg-info';
    // $('#drk_card_back').change(function(){
    //     $('#drk_card_back').removeClass(selectedSchemeDrk2).addClass($(this).val());
    //     selectedSchemeDrk2 = $(this).val();
    // });
    // var selectedSchemeDrk3 = 'bg-info';
    // $('#drk_tbl_head_back').change(function(){
    //     $('#drk_tbl_head_back').removeClass(selectedSchemeDrk3).addClass($(this).val());
    //     selectedSchemeDrk3 = $(this).val();
    // });
    // var selectedSchemeDrk4 = 'bg-info';
    // $('#drk_table_row_back').change(function(){
    //     $('#drk_table_row_back').removeClass(selectedSchemeDrk4).addClass($(this).val());
    //     selectedSchemeDrk4 = $(this).val();
    // });
    // var selectedSchemeDrk5 = 'bg-info';
    // $('#drk_main_font_back').change(function(){
    //     $('#drk_main_font_back').removeClass(selectedSchemeDrk5).addClass($(this).val());
    //     selectedSchemeDrk5 = $(this).val();
    // });

    // var selectedSchemeDrk6 = 'bg-info';
    // $('#drk_bread_crum_back').change(function(){
    //     $('#drk_bread_crum_back').removeClass(selectedSchemeDrk6).addClass($(this).val());
    //     selectedSchemeDrk6 = $(this).val();
    // });

    // $('#dailyRecap').change(function(){
    //     if (this.checked) {
    //         $('.dailyRecap').show();
    //     }
    //     else {
    //         $('#autoUpdate').hide();
    //     }
    // });

    // $('#dailyRecap').change(function(){
    //     if (this.checked) {
    //         $('.dailyRecap').fadeIn('slow');
    //     }
    //     else {
    //         $('#autoUpdate').fadeOut('slow');
    //     }
    // });

    // $('#dailyRecap').change(function(){
    //     if (this.checked) {
    //         $('.dailyRecap').fadeIn('slow');
    //     }
    //     else {
    //         $('#autoUpdate').fadeOut('slow');
    //     }
    // });
});

$("#custumCheck").change(function(){
    if ($("#custumCheck").is(":checked")) {
        $("#war_check").show();
    } else {
        $("#war_check").hide();

    }
});

$("#edit_custumCheck").change(function(){

    if ($("#edit_custumCheck").is(":checked")) {
        $("#war_check_edit").show();
    } else {
        $("#war_check_edit").hide();

    }
});
$('.demo').each(function() {
    //
    // Dear reader, it's actually very easy to initialize MiniColors. For example:
    //
    //  $(selector).minicolors();
    //
    // The way I've done it below is just for the demo, so don't get confused
    // by it. Also, data- attributes aren't supported at this time...they're
    // only used for this demo.
    //
    $(this).minicolors({
        control: $(this).attr('data-control') || 'hue',
        defaultValue: $(this).attr('data-defaultValue') || '',
        format: $(this).attr('data-format') || 'hex',
        keywords: $(this).attr('data-keywords') || '',
        inline: $(this).attr('data-inline') === 'true',
        letterCase: $(this).attr('data-letterCase') || 'lowercase',
        opacity: $(this).attr('data-opacity'),
        position: $(this).attr('data-position') || 'bottom left',
        swatches: $(this).attr('data-swatches') ? $(this).attr('data-swatches').split('|') : [],
        change: function(value, opacity) {
            if (!value) return;
            if (opacity) value += ', ' + opacity;
            if (typeof console === 'object') {
                // console.log(value);
            }
        },
        theme: 'bootstrap'
    });

});
</script>