
<script>
//   RFQ Index Script Blade 
  $(function () {
          if ($("#mymce").length > 0) {
              tinymce.init({
                  selector: "textarea#mymce",
                  theme: "modern",
                  height: 300,
                  plugins: [
                      "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                      "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                      "save table contextmenu directionality emoticons template paste textcolor"
                  ],
                  toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
              });
          }
      });
    
</script>
<script>

  let categoriesArr = '';
  let tagsArr =  {!! json_encode($tags) !!};
  $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
          }
      });
  $(document).ready(function(){

      var company = $("#comp_id option:selected").text();
  $("#company").val(company);


  $(".sellInst").hide();
  
  $("#checkAll").click(function(){
      $('input:checkbox.contacts').not(this).prop('checked', this.checked);
  });
  contacts_table_list = $('#contacts_table').DataTable({
      pageLength: 25
  });

  $('#vendor_column').multipleSelect({
      width:300,
      onClick:function(view) {
          var selectedItems = $('#vendor_column').multipleSelect("getSelects");
          for(var i =0; i < 8; i++) {
              columns = contacts_table_list.column(i).visible(0);
          }
          for(var i = 0; i < selectedItems.length; i++) {
              var s = selectedItems[i];
              contacts_table_list.column(s).visible(1);
          }
          $('#contacts_table').css('width','100%');
      },
      onCheckAll:function() {
          for(var i =0; i < 8; i++) {
              columns = contacts_table_list.column(i).visible(1);
          }
      },
      onUncheckAll:function() {
          for(var i =0; i < 8; i++) {
              columns = contacts_table_list.column(i).visible(0);
          }
          $('#contacts_table').css('width','100%');
      }
  });




  $('a.toggle-vis').on( 'click', function (e) {
      e.preventDefault();

      $(this).toggleClass('btn-success');
      $(this).toggleClass('btn-secondary');

      // Get the column API object
      var column = contacts_table_list.column( $(this).attr('data-column') );

      // Toggle the visibility
      column.visible( ! column.visible() );
  } );

  get_vendors_table_list();
  
  $('#add_category_btn').on("click", function(e){
      var cat = $('#add_category_name').val();
      
      if(cat == ''){
              var d = flashy('Category name cannot be empty!', {
                  type : 'flashy__danger',
                  stop : true
              });
          
          return false;
      }
      
      $.ajax({
          type: "post",
          url: "{{asset('/save-category')}}",
          data: {name:cat},
          dataType: 'json',
          cache: false,
          success: function(data){
              
              if(data['success'] == true){
                  
                  flashy('Category Added!', {
                      type : 'flashy__success'
                  });
                  $('#add_category_name').val('');
                  $("#categories").append("<option value='"+data["id"]+"' selected>"+cat+"</option>");
                  $('#categories').trigger('change'); 
                  
              }else{
                  flashy('Something went wrong!', {
                      type : 'flashy__danger'
                  });
              }
          }
      });
      
      e.preventDefault();
   
  });
  
  
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
  function get_categories_list(){

      $.ajax({
          type: "get",
          url: "{{asset('/get-categories')}}",
          data: "",
          async: false,
          success: function(data){
              categoriesArr = data['categories'];
          }
      });
      
  }

  $("#comp_id").change(function(){
      var ter = $("#comp_id option:selected").text();
      $("#company").val(ter) ; 

  });

  function get_vendors_table_list(){
      get_categories_list();
      console.log(categoriesArr)
      contacts_table_list.clear().draw();
          $.ajax({
              type: "get",
              url: "{{asset('/get-vendors')}}",
              data: "",
              success: function(data){
                  console.log(data.vendors,"a");
                  var vendor_arr = data.vendors;
                      $("#contacts_table tbody").html("");
                      var count = 1;
                      $.each(vendor_arr, function(key, val){
                          var json = JSON.stringify(data[key]);
                          var web_link = '';
                          var categories = '';
                          var tags='';

                          if(val['tags'] != '' && val['tags'] != null && val['tags'] != undefined){
                              tags = val['tags'].split(',');
                          }

                          if(val['categories'] != '' && val['categories'] != null && val['categories'] != undefined){
                              categories = val['categories'].split(',');
                          }
                          
                          let cat_names = '';
                          let tag_names='';
                          if(categories.length > 0 && categories != ''){
                              var cat_count = 0;
                              for(var k = 0 ; k<categoriesArr.length;k++){
                                  for(var l = 0;l<categories.length;l++){
                                      if(categoriesArr[k]['id'] == categories[l]){
                                          if(cat_count == 0){
                                              cat_names += categoriesArr[k]['name'];
                                              cat_count++;
                                          }else{
                                              cat_names += ' , ' + categoriesArr[k]['name'];
                                              cat_count++;
                                          }
                                          
                                          break;
                                      }
                                  }
                                  
                              }
                          }else{
                              cat_names = '---';
                          }

                          if(tags.length > 0 && tags != ''){
                              
                              var tags_count = 0;

                              for(var j = 0 ; j<tagsArr.length;j++){
                                  for(var l = 0;l<tags.length;l++){
                                      if(tagsArr[j]['id'] == tags[l]){
                                          if(tags_count == 0){
                                              tag_names += tagsArr[j]['name'];
                                              tags_count++;
                                          }else{
                                              tag_names += ' , ' + tagsArr[j]['name'];
                                              tags_count++;
                                          }
                                          
                                          break;
                                      }
                                  }
                                  
                              }
                          }else{
                              tag_names = '---';
                          }
                          if(val['website'] ==  ''){
                              web_link = val['company'];
                          }else{
                              web_link = '<a href="'+val["website"]+'" target="_blank">'+val['company']+'</a>';
                          }
                          contacts_table_list.row.add([
                              '<div class="text-center"><input type="checkbox" class="contacts" name="contacts[]" value='+val['id']+' id='+val['id']+'></div>',
                              val['name']= '<a href="{{url('vendors_profile')}}/'+val['id']+'" target="blank">'+val['first_name']+' '+val['last_name']+'</a>',
                              web_link,
                              val['email'],
                              cat_names,
                              val['phone'],
                              tag_names,                                                                                                            
                              '<button class="btn btn-circle btn-success" title="Edit Type" onclick="event.stopPropagation();editContact('+val['id']+',`'+val['first_name']+'`,`'+val['last_name']+'`,`'+val['company']+'`,`'+val['email']+'`,`'+val['direct_line']+'`,`'+val['phone']+'`,`'+val['website']+'`,`'+val["tags"]+'`,`'+val["categories"]+'`);return false;"><i class="mdi mdi-grease-pencil font-20" aria-hidden="true"></i></button>&nbsp;<button class="btn btn-circle btn-danger" title="Delete Department" onclick="event.stopPropagation();deleteContact('+val['id']+');return false;"><i class="fa fa-trash font-20 " aria-hidden="true"></i></button>'
                  
                          ]).draw( false );
                          count++;
                      });
              }
          });
  }

  $( "#rfq_form" ).submit(function( event ) {  
  
      event.preventDefault(); 
      
      var formData = new FormData($(this)[0]);
      var action = $(this).attr('action');
      var method = $(this).attr('method');
      var contacts_arr = [];

      $.each($("input[name='contacts[]']:checked"), function () {
          let data = $(this).parents('tr:eq(0)');
          let vendor_email = $(data).find('td:eq(3)').text();
          contacts_arr.push(vendor_email);
      });
      
      formData.append('contacts', contacts_arr);
      formData.append('rfq_details', tinyMCE.activeEditor.getContent());

      $.ajax({
          type: method,
          url: action,
          data: formData,
          async: false,
          cache: false,
          contentType: false,
          enctype: 'multipart/form-data',
          processData: false,
          success: function(data) {
              console.log(data);
              if(data['success'] == true){
                  $("#rfq_form").trigger("reset");
                  $( ".badge" ).remove();
                  $('#to_mails').val('');
          $('input:checkbox:checked').prop('checked', false);
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
  

  $( "#instNotesForm" ).submit(function( event ) {  
  event.preventDefault(); 

  var action = $(this).attr('action');
  
  var notes_value = $("#sell_inst_note").val();
  var form_data = {
      sell_inst_note: notes_value,
  }
  $.ajax({
      type: "POST",
      url: action,
      data: form_data,
      dataType:'json',
      success: function(data) {
          console.log(data);

          if(data.status_code == 200 &&  data.success == true){
              //  $("#instNotesForm").trigger("reset");
              $("#displayNotes").text(notes_value);
              $('.sellInstP').show();
              $(".sellInst").hide();
              
              toastr.success(data.message, { timeOut: 5000 });
          } else{   
              toastr.warning(data.message, { timeOut: 5000 });
          }
      }
  });
      
  });
      function showVendorModel(){

              var label = $("#edit-contact").text();

              if(label=='Edit Vendor'){

                  $("#edit-contact").html('Save Vendor');
              }

              $("#categories").empty();
              for(var k = 0 ; k<categoriesArr.length;k++){
                              
              $("#categories").append("<option value='"+categoriesArr[k]['id']+"' >"+categoriesArr[k]['name']+"</option>");
              $('#categories').trigger('change'); 
                          

              }
              $("#tags").empty();
              for(var k = 0 ; k<tagsArr.length;k++){
                              
              $("#tags").append("<option value='"+tagsArr[k]['id']+"' >"+tagsArr[k]['name']+"</option>");
              $('#tags').trigger('change'); 
              
              }
              $("#save_vendor_form").trigger("reset");
              $('#vendor_modal').modal('show');
      }

  $( "#save_vendor_form" ).submit(function( event ) {  
      event.preventDefault(); 

      if($("#comp_id").val() == "Select") {
          toastr.success("Please Select Ccompany", { timeOut: 5000 });
      }else{
          var formData = new FormData($(this)[0]); 
          var action = $(this).attr('action');
          var method = $(this).attr('method'); 
          var selectedCategories = [];
          var selectedTags = [];
          
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
                      get_vendors_table_list();
                      
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
      }

  });

  function editContact(id, first_name, last_name, company, email, direct_line, phone, website, tags, categories) {

          var cat_names = categories.split(',');
          var tag_names = tags.split(',');

          if (cat_names.length > 0 && cat_names != '') {

              var cat_count = 0;

              $("#categories").empty();

              for (var m = 0; m < categoriesArr.length; m++) {
                  $("#categories").append("<option value='" + categoriesArr[m]['id'] + "'>" + categoriesArr[m]['name'] +
                      "</option>");
                  $('#categories').trigger('change');
              }

              for (var k = 0; k < categoriesArr.length; k++) {
                  for (var l = 0; l < cat_names.length; l++) {
                      if (categoriesArr[k]['id'] == cat_names[l]) {
                          $('#categories option[value=' + categoriesArr[k]['id'] + ']').attr('selected', 'selected');
                          break;
                      }
                  }
              }
          } else {
              cat_names = '---';
          }

          if (tag_names.length > 0 && tag_names != '') {

              var tag_count = 0;
              $("#tags").empty();
              //console.log(tagsArr)

              for (var m = 0; m < tagsArr.length; m++) {
                  $("#tags").append("<option value='" + tagsArr[m]['id'] + "'>" + tagsArr[m]['name'] + "</option>");
                  $('#tags').trigger('change');
              }

              for (var k = 0; k < tagsArr.length; k++) {
                  for (var l = 0; l < tag_names.length; l++) {
                      if (tagsArr[k]['id'] == tag_names[l]) {
                          $('#tags option[value=' + tagsArr[k]['id'] + ']').attr('selected', 'selected');
                          break;
                      }
                  }
              }

          } else {
              tags_names = '---';
          }

          $('#first_name').val(first_name);
          $('#last_name').val(last_name);
          $('#company').val(company);
          $('#email').val(email);
          $('#direct_line').val(direct_line);

          $('#phone').val(phone);
          $('#website').val(website);


          $('#contact_id').val(id);
          $('#vendor_modal').modal('show');

          var label = $("#edit-contact").text();
          if (label == 'Save Vendor') {
              $("#edit-contact").html('Edit Vendor');
          }

      }

  function deleteContact(id){
              console.log(id);
              Swal.fire({
              title: 'Are you sure?',
              text: "All data related to this Vendor will be removed!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
              }).then((result) => {
              if (result.value) {
                  $.ajax({
                      type: "post",
                      url: "{{asset('/delete-vendor')}}",
                      data: {id:id},
                      success: function(data){
                          console.log(data);
                          if(data){
                              Swal.fire({
                              position: 'top-end',
                              icon: 'success',
                              title: 'Vendor Deleted!',
                              showConfirmButton: false,
                              timer: 2500
                              })
                              
                              get_vendors_table_list();
                          }else{
                              Swal.fire({
                              position: 'top-end',
                              icon: 'error',
                              title: 'Something went wrong!',
                              showConfirmButton: false,
                              timer: 2500
                              })
                              
                          }
                      }
                  });
              }
          })
  }

  $("#InstNote").click(function(){

      var old_notes  = $("#displayNotes").text(); 
      $("#sell_inst_note").val(old_notes);

      // alert("nj");
      $(".sellInst").toggle();
      $(".sellInstP").toggle();
  });
</script>