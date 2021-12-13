
<script>
    var msgTimer = 2000;
    var users_table_list = '';
    var tags = {!! json_encode($tags) !!};
    var roles = {!! json_encode($roles) !!};
    var usr_id = $("#usr_id").val();

    var user_photo_url = "{{URL::asset('files/user_photos')}}";
    var get_staff_route = "{{asset('/get-staff-users')}}";
    var delete_user_route = "{{asset('/delete-user')}}";
    var user_profile_route = "{{ url('/profile') }}";
</script>
