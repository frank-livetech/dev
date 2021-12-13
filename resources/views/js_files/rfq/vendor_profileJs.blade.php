<script>
// RFQ Vendor Profile Script Blade


var url = window.location.href;  
var vendor_id = url.split('/');
var id = vendor_id[5];
var status = 1;


$("#update_vendor_profile_form").submit(function (event) {
    event.preventDefault();


    var formData = $("#update_vendor_profile_form").serialize() + "&vendor_id=" + id;
    
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: "POST",
        url: "{{route('update.vendor')}}",
        data: formData,
        dataType:'json',
        success: function (data) {
            console.log(data);
            Swal.fire({
                position: 'top-end',
                icon: data.success ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: 2500
            })
        },
        error: function (e) {
            console.log(e);
        }
    });
});
$("#comp_id").change(function(){
    var ter = $("#comp_id  option:selected").text();
    $("#company").val(ter) ; 
});

$("#phone").keyup(function() {

var regex = new RegExp("^[0-9]+$");

if(!regex.test($(this).val())) {
    $("#phone_error2").html("Only numeric values allowed");
}else{
    $("#phone_error2").html(" ");
}
if($(this).val() == '') {
    $("#phone_error2").html(" ");
}
});


$("#direct_line").keyup(function() {

var regex = new RegExp("^[0-9]+$");

if(!regex.test($(this).val())) {
    $("#phone_error").html("Only numeric values allowed");
}else{
    $("#phone_error").html(" ");
}
if($(this).val() == '') {
    $("#phone_error").html(" ");
}
});

$( "#save_vendor_form" ).submit(function( event ) {  

event.preventDefault(); 
var formData = new FormData($(this)[0]); 
var action = $(this).attr('action');
var method = $(this).attr('method'); 
var selectedCategories = [];
var selectedTags = [];
alert(comp_id);

for (var option of document.getElementById('categories').options) {
    if (option.selected) {
        selectedCategories.push(option.value);
    }
}

for (var option of document.getElementById('tags').options) {
    if (option.selected) {
        selectedTags.push(option.value);
    }
}

formData.append('categories', selectedCategories);
formData.append('tags', selectedTags);


$.ajax({
    type: method,
    url: action,
    data:formData,
    async: false,
    cache: false,
    contentType: false,
    enctype: 'multipart/form-data',
    processData: false,
    success: function(data) {
        console.log(data)
        if(data['success'] == true) {
            $("#save_vendor_form").trigger("reset");
            $('#vendor_modal').modal('hide');
            //  get_vendors_table_list();
            
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: data['message'],
                showConfirmButton: false,
                timer: 2500
                })
        } else{   
        Swal.fire({
            position: 'top-end',
                icon: 'error',
                title: data['message'],
                showConfirmButton: false,
                timer: 2500
            })
        }
    }
});
    
});
    $("#companyForm").submit(function (event) {
        event.preventDefault();
        var poc_first_name = $('#poc_first_name').val();
        var poc_last_name = $('#poc_last_name').val();
        var name = $('#name').val();
        var email = $('#cemail').val();
        var phone = $('#phone').val();
        var country = $("#cmp_country").val();
        var state = $("#cmp_state").val();
        var city = $("#cmp_city").val();
        var zip = $("#cmp_zip").val();
        var address = $('#address').val();
        var user_id = $("#user_id").val()


        var a= checkEmptyFields(poc_first_name, $("#err"));
        var b = checkEmptyFields(poc_last_name, $("#err1"));
        var c = checkEmptyFields(name, $("#err2"));
        var d = checkValidEmail(email, $("#err3"));
        var e = checkEmptyFields(phone, $("#err4"));

        if( a && b && c && d && e == true) {
            
            var formData = {
                poc_first_name: poc_first_name,
                poc_last_name: poc_last_name,
                name:name,
                email:email,
                phone:phone,
                country:country,
                state:state,
                city:city,
                zip:zip,
                address:address,
                user_id: user_id
            }
    
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                },
                type: "POST",
                url: "/framework/save-company",
                data: formData,
                dataType:'json',
                beforeSend:function(data) {
                    $('.loader_container').show();
                },
                success: function(data) {
                    toastr.success(data.message, { timeOut: 5000 });
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                complete:function(data) {
                    $('.loader_container').hide();
                },
                error: function(e) {
                    console.log(e)
                }
            });
        }
    });

    function checkEmptyFields(input, err) {
        if(input == '') {
            err.html("this field is required");
            return false;
        }else{
            err.html("");
            return true;
        }
    }

    function checkValidEmail(input,err) {
        var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i

        if(input == '') {
            err.html("this field is required");
            return false;
        }else if(!pattern.test(input)) {
            err.html("please provide valid email");
            return false;
        }else{
            err.html("");
            return true;
        }
    }

function getCompanyName(value) {
    var c = $( "#cmp_id option:selected" ).text();
    $("#cmp_name").val(c);
}



</script>