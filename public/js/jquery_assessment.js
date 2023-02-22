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

    function returnAssessmentPreview(assessment_id)
    {
        $.ajax({
            url: "/assessments/"+assessment_id,
            type: 'GET',
            beforeSend: function() {
				$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take some time, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
					show: 'fade',
					resizable: false,	
					width: 350,
					height: 'auto',
					modal: true,
					buttons: false
				});
				$(".ui-dialog-titlebar").hide();
			},
            success: function(response){
                $("#confirmation").dialog('close');
                console.log(response);
                $("#assessment_preview").html(response);
                // $("#save_assessment").focus();
            },
            error: function (data) {
                $("#confirmation").dialog('close');
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    $("#student").val(null).trigger('change');
                }
            }
        });
    }

    $(document).on("change","#student", function(){
        var student_id = $("#student").val();

        if(student_id){
            $.ajax({
                url: "/enrolments/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data.success === false)
                    {
                        showError(response.data.message);
                        $("#student").val(null).trigger('change');
                    }else{
                        if(response.data.acctok === 0)
                        {
                            showError('Student enrollment is not yet saved! Please save enrollment first!');
                            $(".actions").prop("disabled", true);
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
                            returnAssessmentPreview(response.data.assessment.id);
                        }
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        $("#student").val(null).trigger('change');
                    }
                }
            });
        }
    });

    $(document).on("submit","#assessment_form", function(e){
        var enrolled_units = $("#enrolledunits").text();
        var assessment_id = $("#assessment_id").val();
		var postData = $(this).serializeArray(); 
		postData.push({name: 'enrolled_units', value: enrolled_units });
        $("#save_assessment").prop("disabled", true);
        
        $.ajax({
            url: "/assessments/"+assessment_id,
            type: 'PUT',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
				$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take some time, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
					show: 'fade',
					resizable: false,	
					width: 350,
					height: 'auto',
					modal: true,
					buttons: false
				});
				$(".ui-dialog-titlebar").hide();
			},
            success: function(response){
                $("#confirmation").dialog('close');
                $("#save_assessment").prop("disabled", false);

                if(response.data === true)
                {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Assessment Saved</div><div class="message">Student was successfully assessed.<br>Print student assessment?</div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        draggable: false,
                        width: 350,
                        height: 'auto',
                        modal: true,
                        buttons: {
                                'Cancel':function(){
                                    $("#confirmation").dialog('close');
                                },
                                'OK':function(){
                                    $("#confirmation").dialog('close');
                                    window.open("/assessments/printassessment/"+assessment_id, '_blank'); 
                                }//end of ok button	
                            }//end of buttons
                    });//end of dialogbox
                    $(".ui-dialog-titlebar").hide();
                    //end of dialogbox
                }
                
            },
            error: function (data) {
                $("#confirmation").dialog('close');
                $("#save_assessment").prop("disabled", false);

                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });
        e.preventDefault();
    });

    $(document).on("click", "#print_assessment", function(e){
        var assessment_id = $("#assessment_id").val();

        if(assessment_id)
        {
            window.open("/assessments/printassessment/"+assessment_id, '_blank'); 
        }else{
            showError('Please select student assessment to print!');
        }
        e.preventDefault();
    });
});