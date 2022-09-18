//DOCUMENT READY
$(function(){

   $(document).on("click", ".selectall", function()
   {
      var id = $(this).attr("data-id");

      $('.item_'+id+', .read_'+id+', .write_'+id).not(this).prop('checked', this.checked);
   });

   $(document).on("click", ".uaccess", function()
   {
      var id = $(this).attr("id");

      $('#read_'+id+', #write_'+id).not(this).prop('checked', this.checked);
   });

   $(document).on("submit", "#create_user_form", function(e)
   {
      var url = $(this).attr('action');
      var postData = $(this).serializeArray();

      $('.uaccess:checked').each(function () {
         var id = $(this).attr('id');
         var val = $(this).val();

         var read = ($("#read_"+id).prop('checked')) ? 1 : 0;
         var write = ($("#write_"+id).prop('checked')) ? 1 : 0;

         postData.push({name: 'read[]', value: read });
         postData.push({name: 'write[]', value: write });
         //alert(id+'--'+val+'--'+read+'--'+write);
      });

      $.ajax({
         url: url,
         type: 'POST',
         data: postData,
         dataType: 'json',
         success: function(data){
            console.log(data);
         },
         error: function (data) {
            console.log(data);
            // var errors = data.responseJSON;
            // if ($.isEmptyObject(errors) == false) {
            //     $.each(errors.errors, function (key, value) {
            //         $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
            //     });
            // }
        }
      });
      //console.log(postData);
      e.preventDefault();
   });

});