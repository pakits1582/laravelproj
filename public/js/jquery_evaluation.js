//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    
    $('.datepicker').datepicker(pickerOpts);  

    $("#program_id, #program").select2({
	    dropdownParent: $("#ui_content2")
	});

	$(document).on("click", ".manage_evaluation", function(e){
		var student_id = $("#student_id").val();
		var curriculum_subject_id = $(this).attr("id");

		$.ajax({
			url: "/evaluations/taggrade",
			type: 'POST',
			data: ({ 'student_id':student_id, 'curriculum_subject_id':curriculum_subject_id, 'from_return':'no' }),
			success: function(data){
				//console.log(data);
				$('#ui_content').html(data);
				$("#modalTable").modal('show');
			
			},
			error: function (data) {
				console.log(data);
			}
		});
		e.preventDefault();
	});

	$(document).on("change",".cboxtag", function(){
		var check = $(this);

		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
			
			var istagged = check.attr("data-istagged");
			if(istagged == 1){
				$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Selection</div><div class="message">The subject you have selected had already been tagged. Continue selecting?').dialog({
					show: 'fade',
					resizable: false,	
					draggable: true,
					width: 350,
					height: 'auto',
					modal: true,
					buttons: {
							'Cancel':function(){
								$(this).dialog('close');
								check.prop("checked", false);		
							},	
							'OK':function(){
								$(this).dialog('close');
							}
						}//end of buttons
					});//end of dialogbox
				$(".ui-dialog-titlebar").hide();	
			}
		}else{
			$(this).prop('checked', false);
			$(this).closest('tr').removeClass('selected');
		}
	});

	$(document).on("submit", "#form_tag_grade", function(e){
		var postData = $(this).serializeArray();
		var url = $(this).attr("action");
		$.ajax({
			type: "POST",
			url: url,
			data: postData,
			cache:false,
			beforeSend: function() {
					$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
						show: 'fade',
						resizable: false,	
						width: 'auto',
						height: 'auto',
						modal: true,
						buttons: false
					});
					$(".ui-dialog-titlebar").hide();
				},
			success: function(response){
				$("#confirmation").dialog('close');
				//console.log(response);

				if(response.data.success !== false)
				{
					showSuccess(response.data.message);
					var student_id = $("#student_id").val();
					var curriculum_subject_id = $("#curriculum_subject_id").val();

					returnTaggrades(student_id, curriculum_subject_id);
					returnEvaluation(student_id);
				}else{
					showError(response.data.message);
				}
			},
			error: function (data) {
				console.log(data);
			}
			
		});

		e.preventDefault();
	});

	function returnEvaluation(student_id)
	{
		$.ajax({url: "/evaluations/"+student_id, success: function(data){
				console.log(data);
				$("#return_evaluation").html(data);
			}
		});
	}

	function returnTaggrades(student_id, curriculum_subject_id)
	{
		$.ajax({
			url: "/evaluations/taggrade",
			type: 'POST',
			data: ({ 'student_id':student_id, 'curriculum_subject_id':curriculum_subject_id, 'from_return':'yes' }),
			success: function(data){
				//console.log(data);
				$('#return_taggrade').html(data);	
			},
			error: function (data) {
				console.log(data);
			}
		});
	}
	
});