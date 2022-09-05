//DOCUMENT READY
$(function(){
    
    $('.datepicker').datepicker(pickerOpts);  

/************************************
*** FUNCTION CLICK ON SIGNATURE   ***
************************************/
    $(document).on("dblclick",".uploadfile", function(e)
    {
		var id = $(this).attr("id");

		$('#'+id+'_file').click();
	});

/*************************************
*** FUNCTION PREVIEW IMAGE UPLOAD  ***
*************************************/
	function readURL(input,preview) 
    {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#'+preview).attr('style', 'background-image:url('+e.target.result+')');
				//$('#imagepreview').attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
/*************************************
*** FUNCTION CHANGE IMAGE UPLOAD  ***
*************************************/
    $(document).on("change", ".hidden", function()
    {
        var id = $(this).attr("id");
        var preview = id.replace('_file','');
        readURL(this,preview);
    });

});