<script>
let countries_list = {!! json_encode($countries) !!};

function listStates(country, idt, keyName) {
    if(!country) return false;
    
    let rid = countries_list.filter(item => item.name == country);
    if(rid.length) country = rid[0].id;
    else {
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: 'Country not found!',
            showConfirmButton: false,
            timer: swal_message_time
        });
        return false;
    }

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        },
        type: 'POST',
        url: "{{url('list-states')}}",
        data: {countryId: country},
        success: function(data) {
            if(!data.success) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: data.message,
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            } else {
                if(data.list.length) {
                    $('#'+idt).empty();
                    $('#'+idt).append('<option value="">Select State</option>');
                    let dmn = '';
                    
                    data.list.forEach(item => {
                        if(keyName && country == item.country_id && customer.hasOwnProperty(keyName) && item.name == customer[keyName]) {
                            $('#'+idt).append(`<option value="${item.name}" selected>${item.name}</option>`);
                        } else {
                            $('#'+idt).append(`<option value="${item.name}">${item.name}</option>`);
                            dmn += `<option value="${item.name}">${item.name}</option>`;
                        }
                    });

                    if($('select#domain').length) $('select#domain').html(dmn);
                }
            }
        },
        complete:function(data) {
            // action on complete
        },
        error: function(e) {
            console.log(e);
        }
    });
}
</script>