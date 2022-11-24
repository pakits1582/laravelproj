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
		var grade_id = $(this).attr("data-grade_id");
		var origin = $(this).attr("data-origin");

		$.ajax({
			url: "/evaluations/taggrade",
			type: 'POST',
			data: ({ 'student_id':student_id, 'curriculum_subject_id':curriculum_subject_id, 'grade_id':grade_id, 'origin':origin }),
			success: function(data){
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
								check.prop("checked", true);		
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
			success: function(data){
				$("#confirmation").dialog('close');
				console.log(data);

				// if(data == 1){
				// 	showSuccess('Changes sucessfully saved!');
				// 	var student   = $("#idno").val();
				// 	var subtocurr = $("#subtocurr").val();
				// 	returnEvaluation(student);
				// 	$.ajax({
				// 		type: "POST",
				// 		url: baseUrl+"/evaluation/returntagsubjecttocurr",
				// 		data: ({"subtocurr": subtocurr, "student":student}),
				// 		cache: false,
				// 		success: function(data1){
				// 			$("#return_tagsubtocurr").html(data1);
				// 		} 
				// 	});
				// }else{
				// 	showError(data);	
				// }
			}
		});

		e.preventDefault();
	});
	
});