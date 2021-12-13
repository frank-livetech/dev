
<script>
    // Department Script Blade
    // data
    let department = {!! json_encode($department) !!};
    console.log(department);
    $(function() {
        $(".custom-switch").bootstrapSwitch();
    });

    function dept_assignments(el, uid) {
        var formData = new FormData();

        formData.append('user_id', uid);
        formData.append('dept_id', department.id);
        formData.append('assignment', ($(el).prop('checked')) ? 'set' : 'unset');

        $.ajax({
            type: "POST",
            url: "{{asset('/set-dept-assignment')}}",
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                if($(el).prop('checked')) $('#perm-card-'+uid).show();
                else $('#perm-card-'+uid).hide();

                console.log(data);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        });
    }

    function dept_permissions(el, uid, perm) {
        var formData = new FormData();

        formData.append('user_id', uid);
        formData.append('dept_id', department.id);
        formData.append('name', perm);
        formData.append('permitted', ($(el).prop('checked')) ? 1 : 0);

        $.ajax({
            type: "POST",
            url: "{{asset('/set-dept-permission')}}",
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            enctype: 'multipart/form-data',
            processData: false,
            success: function(data) {
                console.log(data);
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: data['message'],
                    showConfirmButton: false,
                    timer: swal_message_time
                });
            }
        });
    }
</script>