$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(document).on("change",".select_enrolled_class", function(){
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
		}else{	
			$(this).closest('tr').removeClass('selected');
		}
	});

	$(document).on("change",".checked_offered", function(){
		var takenunits   = parseInt($("#enrolled_units").text());
		var allowedunits = parseInt($("#units_allowed").val());
		
		if($(this).is(':checked'))
		{
			$('.checked_offered:checked').each(function(){
				takenunits += isNaN(parseInt($(this).closest('tr').find('.units').text())) ? 0 : parseInt($(this).closest('tr').find('.units').text());
			});   

			if(takenunits > allowedunits){
				showError('The maximum units allowed exceeded!');
				$(this).prop('checked',false);
				return false;
			}else{
				$(this).closest('tr').addClass('selected');
			}
		}else{
			$(this).closest('tr').removeClass('selected');
		}
	});

	$(document).on("submit", "#form_add_offerings", function(e){
		var section  = $("#section").val();
        var postData = $("#form_add_offerings").serializeArray();

		$.ajax({
			url: "/registration",
			type: 'POST',
			data: postData,
			dataType: 'json',
			beforeSend: function() {
				$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
					show: 'fade',
					resizable: false,	
					width: 350,
					height: 'auto',
					modal: true,
					buttons:false
				});
				$(".ui-dialog-titlebar").hide();
			},
			success: function(response)
			{
				$("#confirmation").dialog('close');
				console.log(response);
				
			},
			error: function (data) {
				$("#confirmation").dialog('close');
				console.log(data);
				var errors = data.responseJSON;
				if ($.isEmptyObject(errors) == false) {
					var error_message = '';
					$.each(errors.errors, function (key, value) {
						error_message += '<p>'+value+'</p>';
					});

					showError(error_message);
				}
			}
		});

		e.preventDefault();
	});
    
});