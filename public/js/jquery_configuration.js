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

	$(document).on("click", ".applicationaction", function(){
		$("#confirmaction").modal('show');
		// var action = $(this).attr("id");
		// $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm '+action+'</div><div class="message">Are you sure you want to '+action+' application?</div>').dialog({
		// 	show: 'fade',
		// 	resizable: false,	
		// 	draggable: false,
		// 	width: 350,
		// 	height: 'auto',
		// 	modal: true,
		// 	buttons: {
		// 			'Cancel':function(){
		// 				$(this).dialog('close');
		// 			},
		// 			'OK':function(){
		// 				$(this).dialog('close');
		// 				var configid = $("#configid").val();

		// 				// $.ajax({url: baseUrl+"/configuration/applicationaction",data: {'configid':configid, 'action':action},success: function(data){
		// 				// 		if(data == 1){
		// 				// 			showSuccess('Application successfully '+action+'ed!');
		// 				// 			window.setTimeout(function(){
		// 				// 				location.reload();
		// 				// 			}, 500);	
		// 				// 		}else{
		// 				// 			showError(data)
		// 				// 		}
		// 				// 	} 
		// 				// });
		// 			}//end of ok button	
		// 		}//end of buttons
		// 	});//end of dialogbox
		// 	$(".ui-dialog-titlebar").hide();
		// //end of dialogbox
	});

});