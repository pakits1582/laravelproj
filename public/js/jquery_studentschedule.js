$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#student").select2({
	    // dropdownParent: $("#ui_content3"),
        minimumInputLength: 2,
        tags: false,
        minimumResultsForSearch: 20, // at least 20 results must be displayed
        ajax: {
            url: '/students/dropdownselectsearch',
            dataType: 'json',
            delay: 250,
            data: function (data) {
                return {
                    searchTerm: data.term // search term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
	});

    $("#period").select2({
        dropdownParent: $("#ui_content4")
    });

    function clearForm()
    {
        $("#form_studentschedule").find('input:text, input:password, input:file, select, textarea').val('');
        $("#form_studentschedule").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        $("#schedule_table, #return_enrolled_subjects, #status").html('');
        $("#student").val(null).trigger('change');
        $(".actions").prop("disabled", true);
    }

    function returnEnrolledClassSubjects(enrollment_id)
    {
        $.ajax({
            url: "/studentschedules/enrolledclasssubjects",
            type: 'POST',
            //dataType: 'json',
            data: ({ 'enrollment_id':enrollment_id }),
            success: function(data){
                //console.log(data);
                $("#return_enrolled_subjects").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm()
                }
            }
        });
    }

    function returnScheduleTable(enrollment_id)
    {
        $.ajax({
			url: "/assessments/scheduletable",
			type: 'POST',
			data: ({ 'enrollment_id' : enrollment_id}),
			success: function(data){
				//console.log(data);
				$("#schedule_table").html(data);
			},
			error: function (data) {
				console.log(data);
			}
		});
    }

    function returnStudentInformation(student_id)
    {
        $.ajax({
            url: "/enrolments/"+student_id,
            type: 'GET',
            dataType: 'json',
            success: function(response){
                //console.log(response);
                if(response.data === false)
                {
                    showError('Student is not enrolled!');
                    clearForm();
                }else{
                    if(response.data.acctok === 0)
                    {
                        showError('Student enrollment is not yet saved! Please save enrollment first!');
                        clearForm();
                    }else{
                        if(response.data.assessed === 0)
                        {
                            showError('Student enrollment is not yet assessed! Please save assessment first!');
                            clearForm();
                        }else{
                            // //DISPLAY ENROLLMENT
                            $("#enrollment_id").val(response.data.id);
                            $("#assessment_id").val(response.data.assessment.id);
                            $("#program").val(response.data.program.name);
                            $("#educational_level").val(response.data.program.level.code);
                            $("#college").val(response.data.program.collegeinfo.code);
                            $("#curriculum").val(response.data.curriculum.curriculum);
                            $("#year_level").val(response.data.year_level);
                            $("#section").val(response.data.section.code);

                            var formattedDate = $.format.date(response.data.created_at, "MM/dd/yyyy hh:mm:ss a");
                            $("#enrollment_date").val(formattedDate);

                            $("#new").prop("checked", (response.data.new === 1) ? true : false);
                            $("#old").prop("checked", (response.data.old === 1) ? true : false);
                            $("#returnee").prop("checked", (response.data.returnee === 1) ? true : false);
                            $("#transferee").prop("checked", (response.data.transferee === 1) ? true : false);      
                            $("#cross").prop("checked", (response.data.cross_enrollee === 1) ? true : false);
                            $("#foreigner").prop("checked", (response.data.foreigner === 1) ? true : false);      
                            $("#probationary").prop("checked", (response.data.probationary === 1) ? true : false); 
                            $(".actions").prop("disabled", false);

                            if(response.data.validated == 1)
                            {
                                $("#status").html('Student\'s enrollment is already validated!').removeClass('text-danger').addClass(' text-primary');
                            }else{
                                $("#status").html('Student\'s enrollment is not yet validated!').removeClass('text-success').addClass(' text-danger');
                            }
                            
                            returnEnrolledClassSubjects(response.data.id);
                            returnScheduleTable(response.data.id);
                        }
                    }
                }
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm();
                }
            }
        });
    }

    $(document).on("change","#student", function(){
        var student_id = $("#student").val();

        if(student_id){
            returnStudentInformation(student_id);
        }
    });

});