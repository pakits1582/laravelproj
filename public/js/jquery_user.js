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
});