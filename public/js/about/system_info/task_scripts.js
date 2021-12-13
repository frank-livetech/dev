let windows_table = '';
let office_table = '';
let others_table = '';

$(function () {
    windows_table = $('#tasks-windows-list').DataTable();
    office_table = $('#tasks-office-list').DataTable();
    others_table = $('#tasks-other-list').DataTable();
});

$('#save_script').on('submit', function(ev) {
    ev.preventDefault();
    ev.stopPropagation();

    $('#category').trigger('change');
    
    let formData = new FormData($(this)[0]);

    $.ajax({
        type: $(this).attr('method'),
        url: $(this).attr('action'),
        data: formData,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        success: function (data) {
            if (data.success) {
                let categ = $('#category').val().toLowerCase();
                $('#modalCategory').modal('hide');
                $("#save_script").trigger("reset");
                $("#importCatFile").val("");
                $("#importCatFile").next("label").text("Choose file");
                
                let ct = '';
                if(categ == 'windows') ct = windows_table;
                else if(categ == 'office') ct = office_table;
                else if(categ == 'other') ct = others_table;
                
                ct.row.add( [
                    `<div class="text-center"><input type="checkbox"></div>`,
                    data.script.filename,
                    data.script.size,
                    `<div class="text-center"><a href="{{asset('public/files/task_scripts/${data.script.filename}')}}" target="_blank"><i class="fa fa-download mr-2" style="cursor: pointer;"></i></a><a href="javascript:delTaskScript(${data.script.id}, '${data.script.category}')"><i class="fa fa-trash text-danger"></i></a></div>`,
                ] ).draw( false ).node().id='tr-'+data.script.id;
            }    
            Swal.fire({
                position: 'top-end',
                icon: (data.success) ? 'success' : 'error',
                title: data.message,
                showConfirmButton: false,
                timer: 2500
            })
        },
        failure: function (errMsg) {
            console.log(errMsg);
        }
    });
})

function delTaskScript(id, categ) {
    Swal.fire({
        title: 'Are you sure?',
        text: "All data related to this script will be removed!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type:'post',
                url: del_url,
                data:{id:id},
                dataType: 'json',
                cache: false,
                success: function (data) {

                    if (data.success) {
                        let ct = '';
                        if(categ.toLowerCase() == 'windows') ct = windows_table;
                        else if(categ.toLowerCase() == 'office') ct = office_table;
                        else if(categ.toLowerCase() == 'other') ct = others_table;

                        ct.row('#tr-'+id).remove().draw();
                    } else {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                },
                failure: function (errMsg) {
                    console.log(errMsg);
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: errMsg,
                        showConfirmButton: false,
                        timer: 2500
                    });
                } 
            });
        }
    });
}