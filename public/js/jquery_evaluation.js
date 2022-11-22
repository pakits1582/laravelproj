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

				var $table = $('#table');

				$('#modalTable').on('shown.bs.modal', function () {
					$table.bootstrapTable('resetView')
				})
			},
			error: function (data) {
				console.log(data);
			}
		});
		e.preventDefault();
	});

	
});