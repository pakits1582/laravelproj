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
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
			
		}else{
			$(this).prop('checked', false);
			$(this).closest('tr').removeClass('selected');
		}
	});

	
});