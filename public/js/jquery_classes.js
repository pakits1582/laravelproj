$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#instructor, #program_id").select2({
	    dropdownParent: $("#ui_content3")
	});

    $("#program_id").select2({
	    dropdownParent: $("#ui_content2")
	});

    $(document).on("change", "#year_level", function(e){
        var program_id = $("#program_id").val();
        var year_level = $("#year_level").val();

        if(program_id && year_level)
        {
            $.ajax({
                url: "/sections/getsections",
                type: 'POST',
                data: ({ 'program_id' : program_id, 'year_level' : year_level}),
                dataType: 'json',
                success: function(response){
                    //console.log(response);
                    var sections = '<option value="">- select section -</option>';
                    $.each(response.data, function(k, v){
                        sections += '<option value="'+v.id+'">'+v.code+'</option>';       
                    });
                    $("#section").html(sections);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }else{
            showError('Please select program first!');
            $(this).prop("selectedIndex", 0);
        }
        e.preventDefault();
    });

    function returnClassSubjects(section)
    {
        $.ajax({
			url: "/classes/sectionclasssubjects",
			type: 'POST',
			data: ({ 'section' : section}),
			success: function(data){
				//console.log(data);
				$("#return_classsubjects").html(data);
			},
			error: function (data) {
				console.log(data);
			}
		});
    }

    $(document).on("change", "#section", function(e){
        var section = $(this).val();
        if(section){
            $("#button_group").removeClass('d-none');

            returnClassSubjects(section);
        }
        e.preventDefault();
    });

    $(document).on("click", "#add_subjects", function(e){
        var section = $("#section").val();

        if(section){
            $.ajax({url: "/classes/"+section+"/addclassoffering",success: function(data){
                    console.log(data);
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
        }else{
            showError('Please select section first!');
        }
        e.preventDefault();
    });

/***********************************************
*** FUNCTION MOVE SUBJECT TO SELECTED SELECT ***
***********************************************/	
    $(document).on("click","#button_moveright_offering", function(e){
        var selectedsubs = $("#search_result_offering").val();
        if(!selectedsubs){
            showError('Please select atleast one subject to move.');	
        }else{
            $.each(selectedsubs, function(index, value){
                var textval = $("#search_result_offering option[value='"+value+"']").text();

                $("#selected_subjects_offering").append('<option value="'+value+'" title="'+textval+'" id="optionselected_'+value+'" selected>'+textval+'</option>');
                $("#option_"+value).remove();
            });
        }
        e.preventDefault();
    });

/*************************************************
*** FUNCTION REMOVE SUBJECT TO SELECTED SELECT ***
*************************************************/	
    $(document).on("click","#button_moveleft_offering ", function(e){
        var selectedsubs = $("#selected_subjects_offering").val();
        if(!selectedsubs){
            showError('Please select atleast one subject to move.');	
        }else{
            $.each(selectedsubs, function(index, value){
                var textval = $("#selected_subjects_offering option[value='"+value+"']").text();
                $("#optionselected_"+value).remove();
                $("#search_result_offering").append('<option value="'+value+'" title="'+textval+'" id="option_'+value+'">'+textval+'</option>');
            });
        }
        e.preventDefault();
    });

    $(document).on("change", ".filter_curriculum_subjects", function(e){
        var postData = $("#form_addsubject_class_offering").serializeArray();

        $.ajax({
			url: "/classes/filtercurriculumsubjects",
			type: 'POST',
			data: postData,
			dataType: 'json',
			success: function(response){
				//console.log(response);
				var subjects = '';
				$.each(response.data, function(k, v){
					subjects += '<option value="'+v.id+'" id="option_'+v.id+'"  title="'+v.subjectinfo.name+'">('+v.subjectinfo.units+') - [ '+v.subjectinfo.code+' ] '+v.subjectinfo.name+'</option>';
				});
				$("#search_result_offering").html(subjects);
			},
			error: function (data) {
				console.log(data);
			}
		});
        e.preventDefault();
    });

    $(document).on("submit", "#form_addsubject_class_offering", function(e){
        var url = $(this).attr('action');
      	var postData = $(this).serializeArray();

          $.ajax({
			url: url,
			type: 'POST',
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
			success: function(data){
				$("#confirmation").dialog('close');
				console.log(data);
				$('.alert').remove();

				$("#form_addsubject_class_offering").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>');
				window.scrollTo(0, 0);

				$("#selected_subjects_offering").html("");

                returnClassSubjects($("#section").val());
			},
			error: function (data) {
				$("#confirmation").dialog('close');
				console.log(data);
				var errors = data.responseJSON;
				if ($.isEmptyObject(errors) == false) {
					$.each(errors.errors, function (key, value) {
						$('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
					});
				}
		    }
		});
        e.preventDefault()
    });

    $(document).on("click","#edit", function(){
		var class_id = $(".checks:checked").attr("data-classid");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/subject to edit!');	
		}else{
            
            $.ajax({
                url: "/classes/"+class_id+"/edit",
                dataType: 'json',
                success: function(response){
                    if(!jQuery.isEmptyObject(response)){

                        $('#delete').prop("disabled", true);
                        $('#edit').prop("disabled", true);
                        $('input.checks').prop('disabled', true); 
                        $('#save_class').prop("disabled", false);

                        $('#subject_code').val(response.data.curriculumsubject.subjectinfo.code);
                        $('#subject_name').val(response.data.curriculumsubject.subjectinfo.name);
                        $('#units').val(response.data.units);
                        $('#loadunits').val(response.data.loadunits);
                        $('#tfunits').val(response.data.tfunits);
                        $('#lecunits').val(response.data.lecunits);
                        $('#labunits').val(response.data.labunits);
                        $('#hours').val(response.data.hours);
                        $('#slots').val(response.data.slots);
                        $('#schedule').val(response.data.schedule.schedule);
                        $('#instructor').val(response.data.instructor_id).trigger('change');
                        if (response.datatutorial === 1){ $('#tutorial').prop('checked', true) }
                        if (response.datadissolved === 1){ $('#dissolved').prop('checked', true) }
                        if (response.dataf2f === 1){ $('#f2f').prop('checked', true) }
                    }else{
                        showError('Oppss! Something went wrong! Can not fetch class subject data!');
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });

/******************************************************
*** FUNCTION ONCLICK CANCEL ENABLE/DISABLE BUTTONS  ***
******************************************************/
	$(document).on("click","#cancel", function(){
        //buttons
        $('#save_class').prop("disabled", true);
		$('#delete').prop("disabled", true);
		$('#edit').prop("disabled", true);
        //form fields
		$('.clearable').val("");
		$('input.checks').prop('disabled', false).prop('checked', false);
		$('#dissolved, #tutorial, #f2f').prop("checked", false);
		$('.checks').closest('tr').removeClass('selected');
	});

/**************************************************
*** FUNCTION CHECK IF SCHEDULE FORMAT IS VALID  ***
**************************************************/
    function checkScheduleFormat(schedule){
        var schedule_format = /\b((1[0-2]|0?[1-9]):([0-5]\d) ([AP]M))-((1[0-2]|0?[1-9]):([0-5]\d) ([AP]M)) (?=.)M?T?W?(?:TH)?F?S?(?:SU)? (?=.)[a-z\d]*([-_.][a-z\d]*)?/;
        var schedule_error = "";
        //CHECK SCHEDULE FORMAT
        if(schedule !== ""){
            var schedules = schedule.split(',');
            $.each( schedules, function( key, value ) {
                if(!schedule_format.test(value)) {
                    schedule_error += 'Schedule '+value+" is invalid format!</br>";
                }
            });
        }
        return schedule_error;
    }

    function checkRoomSchedule(schedule, class_id){
        if(schedule){
            $.ajax({
                url: "/classes/checkroomschedule",
                type: 'POST',
                data: ({ 'schedule' : schedule, 'class_id' : class_id}),
                dataType: 'json',
                success: function(response){
                    console.log(response);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

/*********************************************************
*** FUNCTION ON SUBMIT FORM SAVE UPDATE CLASS SUBJECT  ***
*********************************************************/
    $(document).on("submit", "#form_classoffering", function(e){
        var checkedbox = $(".checks:checked");

        if(checkedbox.length == 0){
			showError('Please select checkbox to be updated!');
		}else{
            var postData = $(this).serializeArray();
            var url = $(this).attr('action');
            var class_id   = $(".checks:checked").attr("data-classid");
            var schedule = $("#schedule").val();
            postData.push({name: "class_id", value: class_id });

            if(checkScheduleFormat(schedule)){
                showError(checkScheduleFormat(schedule));
            }else{
                checkRoomSchedule(schedule, class_id);
            }
        }
        e.preventDefault();
    });
});