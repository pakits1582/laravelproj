//DOCUMENT READY
$(function(){

/*********************************************
*** FUNCTION ONCLICK SELECT ALL CHECKBOXES ***
*********************************************/	
   $(document).on("click", ".selectall", function()
   {
      var id = $(this).attr("data-id");

      $('.item_'+id+', .read_'+id+', .write_'+id).not(this).prop('checked', this.checked);
   });

/*****************************************
*** FUNCTION ON CLICK SINGLE ACCESS    ***
*****************************************/	
   $(document).on("click", ".uaccess", function()
   {
      var id = $(this).attr("id");
      $('#read_'+id+', #write_'+id).not(this).prop('checked', this.checked);
      
   });

/*****************************************
*** FUNCTION ON SUBMIT FORM SAVE USER  ***
*****************************************/	
   $(document).on("submit", "#create_user_form", function(e)
   {
      submitForm("#create_user_form", 'reset');

      e.preventDefault();
   });

   $(document).on("submit", "#update_user_form", function(e)
   {
      submitForm("#update_user_form", 'reload');

      e.preventDefault();
   });

   function submitForm(formName, action)
   {
      var url = $(formName).attr('action');
      var postData = $(formName).serializeArray();

      
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
            //console.log(data);
            $('.alert').remove();

            $(formName).prepend('<p class="alert '+data.alert+'">'+data.message+'</p>');

            if(action === 'reload')
            {
               window.setTimeout(function(){
                  location.reload();
               }, 1000);
            }
            if(action === 'reset')
            {
               $(formName)[0].reset();
            }			
         },
         error: function (data) {
            //console.log(data);
            var errors = data.responseJSON;
            if ($.isEmptyObject(errors) == false) {
                $.each(errors.errors, function (key, value) {
                    $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                });
            }
        }
      });
   }

});