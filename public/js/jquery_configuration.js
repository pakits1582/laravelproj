//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

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

	$(document).on("click", ".applicationaction", function(){
		var action = $(this).attr("id");
		var id = $(this).attr("data-id");

		$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm '+action+'</div><div class="message">Are you sure you want to '+action+' application?</div>').dialog({
			show: 'fade',
			resizable: false,	
			draggable: false,
			width: 350,
			height: 'auto',
			modal: true,
			buttons: {
					'Cancel':function(){
						$(this).dialog('close');
					},
					'OK':function(){
						$(this).dialog('close');
						$.ajax({
							url: 'configurations/'+id+'/applicationaction/'+action,
							type: 'POST',
							dataType: 'json',
							success: function(data){
								showSuccess(data.message);
								window.setTimeout(function(){
									location.reload();
								}, 1000);	
							},
							error: function (data) {
								var errors = data.responseJSON;
								if ($.isEmptyObject(errors) == false) {
									showError('Something went wrong! Can not perform requested action! '+errors.message);
								}
							}
						});
					}//end of ok button	
				}//end of buttons
			});//end of dialogbox
			$(".ui-dialog-titlebar").hide();
		//end of dialogbox
	});

	$(document).on("click", ".deleteconfigsched", function(e){
		var id = $(this).attr("id");

		$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete configuration schedule?</div>').dialog({
			show: 'fade',
			resizable: false,	
			draggable: false,
			width: 350,
			height: 'auto',
			modal: true,
			buttons: {
					'Cancel':function(){
						$(this).dialog('close');
					},
					'OK':function(){
						$(this).dialog('close');
						$.ajax({
							url: 'configurations/'+id,
							type: 'DELETE',
							dataType: 'json',
							success: function(data){
								//console.log(data);
								showSuccess(data.message);
								window.setTimeout(function(){
									location.reload();
								}, 1500);	
							},
							error: function (data) {
								//console.log(data);
								var errors = data.responseJSON;
								if ($.isEmptyObject(errors) == false) {
									showError('Something went wrong! Can not perform requested action! '+errors.message);
								}
							}
						});
					}//end of ok button	
				}//end of buttons
			});//end of dialogbox
			$(".ui-dialog-titlebar").hide();
		//end of dialogbox
		e.preventDefault();
	});

});