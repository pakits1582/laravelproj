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

    function clearFormFields()
    {
        $("#section").find('option').remove().end().append('<option value="">- select section -</option>');
        $("#curriculum").find('option').remove().end().append('<option value="">- select curriculum -</option>');

        $("#form_adddrop").find('input:text, input:password, input:file, select, textarea').val('');
        $("#form_adddrop").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
        $("#return_enrolled_subjects").html('<tr class=""><td class="mid" colspan="10">No records to be displayed</td></tr>');
        $("#schedule_table, #deficiencies").html('');
        $("#student, #program").val(null).trigger('change');
        $(".actions").prop("disabled", true);
    }

    function displayEnrollment(response)
    {
        if(response.data)
        {
            //DISPLAY ENROLLMENT
            $("#program").val(response.data.enrollment.program_id).trigger('change.select2');

            var curricula = '<option value="">- select curriculum -</option>';
            $.each(response.data.student.program.curricula, function(k, v){
                curricula += '<option value="'+v.id+'">'+v.curriculum+'</option>';       
            });
            $("#curriculum").html(curricula).val(response.data.enrollment.curriculum_id);
            $("#year_level").val(response.data.enrollment.year_level);

            getSections(response.data.enrollment.program_id, response.data.enrollment.year_level, response.data.enrollment.section_id);

            $("#enrollment_id").val(response.data.enrollment.id);
            $("#units_allowed").val(response.data.allowed_units);

            $("#educational_level").val(response.data.student.program.level.code);
            $("#college").val(response.data.student.program.collegeinfo.code);

            $("#new").prop("checked", (response.data.enrollment.new === 1) ? true : false);
            $("#old").prop("checked", (response.data.enrollment.old === 1) ? true : false);
            $("#returnee").prop("checked", (response.data.enrollment.returnee === 1) ? true : false);
            $("#transferee").prop("checked", (response.data.enrollment.transferee === 1) ? true : false);      
            $("#cross").prop("checked", (response.data.enrollment.cross_enrollee === 1) ? true : false);
            $("#foreigner").prop("checked", (response.data.enrollment.foreigner === 1) ? true : false);      
            $("#probationary").prop("checked", (response.data.enrollment.probationary === 1) ? true : false); 
            
            $(".actions").prop("disabled", false);
        }else{
            $(".actions").prop("disabled", true);
            $('#form_adddrop')[0].reset();
        }

        returnEnrolledClassSubjects(response.data.enrollment.id);
        enrollmentScheduleTable(response.data.enrollment.id);
    }

    function returnEnrolledClassSubjects(enrollment_id)
    {
        $.ajax({
            url: "/enrolments/enrolledclasssubjects",
            type: 'POST',
            //dataType: 'json',
            data: ({ 'enrollment_id':enrollment_id }),
            success: function(data){
                console.log(data);
                $("#return_enrolled_subjects").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearFormFields()
                }
            }
        });
    }

    function enrollmentScheduleTable(enrollment_id)
    {
        $("#schedule_table").html('display schedule table!');
    }

    function getSections(program_id, year_level, selected_value)
    {
        $.ajax({
            url: "/sections/getsections",
            type: 'POST',
            data: ({ 'program_id' : program_id, 'year_level' : year_level }),
            dataType: 'json',
            success: function(response){
                console.log(response);
                var sections = '<option value="">- select section -</option>';
                $.each(response.data, function(k, v){
                    sections += '<option value="'+v.id+'"';
                    sections += (selected_value === v.id) ? 'selected' : ''; 
                    sections += '>'+v.code+'</option>';       
                });

                $("#section").html(sections).val(selected_value);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearFormFields()
                }
            }
        });
    }

    function unitsAllowed()
    {
        var curriculum_id = $("#curriculum").val();
        var year_level = $("#year_level").val();
        var isprobi = ($('#probationary').is(":checked")) ? true : false;

        $.ajax({
            url: "/enrolments/studentenrollmentunitsallowed",
            type: 'POST',
            data: ({ 'curriculum_id' : curriculum_id, 'year_level' : year_level, 'isprobi' : isprobi }),
            dataType: 'json',
            success: function(response){
                console.log(response);
                $("#units_allowed").val(response.data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });

    }

    $(document).on("change","#curriculum", function(e){
        unitsAllowed();
        e.preventDefault();
    });


    $(document).on("change","#year_level", function(e){
        var program_id = $("#program").val();
        var year_level = $("#year_level").val();

        if(program_id && year_level)
        {
            getSections(program_id, year_level, '');
            unitsAllowed();
        }else{
            $("#section").html('<option value="">- select section -</option>');
            $("#units_allowed").val(21);
        }

        e.preventDefault();
    });


    $(document).on("change",".select_enrolled_class, .check_searched_class", function(){
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
		}else{	
			$(this).closest('tr').removeClass('selected');
		}
	});

    $("#program").select2({
	    dropdownParent: $("#ui_content2")
	});

    function enrollmentInfo(student_id, studentinfo)
    {
        $.ajax({
            url: "/enrolments/enrolmentinfo ",
            type: 'POST',
            dataType: 'json',
            data: ({ 'student_id' : student_id, 'studentinfo' : studentinfo }),
            success: function(response){
                console.log(response);
                if(response.data.success === false)
                {
                    showError(response.data.message);
                    clearFormFields()
                }else{
                    if(response.data.enrollment)
                    {
                        if(response.data.enrollment.acctok == 1 && response.data.enrollment.assessed == 1)
                        {
                            displayEnrollment(response);
                        }else{
                            showError('Student enrollment is not yet saved or assessed!');
                            clearFormFields()
                        }
                    }else{
                        showError('Student is not yet enrolled!')
                        clearFormFields()
                    }
                }
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearFormFields()
                }
            }
        });
    }

    $(document).on("change","#student", function(){
        var student_id = $("#student").val();

        if(student_id){
            $.ajax({
                url: "/students/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data.success === false)
                    {
                        showError(response.data.message);
                        clearFormFields()
                    }else{
                        //CHECK ENROLMENT INFORMATION
                        enrollmentInfo(student_id, response.data.values);
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearFormFields()
                    }
                }
            });
        }else{
            $('#form_adddrop')[0].reset();
        }
    });

    $(document).on("change","#program", function(e){
        var program_id = $("#program").val();

        if(program_id)
        {
            $.ajax({
                url: "/programs/"+program_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);

                    $("#educational_level").val(response.program.level.code);
                    $("#college").val(response.program.collegeinfo.code);

                    var curricula = '<option value="">- select curriculum -</option>';
                    $.each(response.program.curricula, function(k, v){
                        curricula += '<option value="'+v.id+'">'+v.curriculum+'</option>';       
                    });
                    
                    $("#curriculum").html(curricula);
                    $("#year_level").val('');
                    $("#section").html('<option value="">- select section -</option>');
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
        }else{
            $("#curriculum").html('<option value="">- select curriculum -</option>');
            $("#year_level").val('');
            $("#section").html('<option value="">- select section -</option>');
        }

        e.preventDefault();
    });

    $(document).on("click", "#delete_selected", function(){
        if($('.select_enrolled_class:checked').length > 0)
        {
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">All items related to this class subject will also be deleted. Do you want to continue?<br>You can not undo this process?</div>').dialog({
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
                            var class_ids = [];
                            var enrollment_id = $("#enrollment_id").val();

                            $(".select_enrolled_class:checked").each(function() {
                                class_id = $(this).attr('value');
                                class_ids.push(class_id);
                            });

                            $.ajax({
                                url: '/enrolments/deleteenrolledsubjects',
                                type: 'DELETE',
                                data: ({ 'class_ids' : class_ids, 'enrollment_id' : enrollment_id }),
                                dataType: 'json',
                                success: function(response)
                                {
                                    if(response.data.success === true)
                                    {
                                        showSuccess(response.data.message);
                                    }
                                    returnEnrolledClassSubjects(enrollment_id);
                                },
                                error: function (data) {
                                    console.log(data);
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
        }else{
            showError('Please select at least one subject to be deleted!');
        }
    });

    $(document).on("click", "#add_subjects", function(e){
        var section = $("#section").val();

        if(section){
            $.ajax({url: "/enrolments/searchandaddclasses",success: function(data){
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

    $(document).keyup(function (event) {
	    if (event.keyCode === 113) {
	        var section = $("#section").val();
			if(section){
				$.ajax({url: "/enrolments/searchandaddclasses",success: function(data){
                    console.log(data);
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
			}else{
				showError('Please select section first!');
			}
	    };
	});

    $(document).on('keyup', '#search_classes', function(e){
        var searchcodes   = $(this).val();
		var enrollment_id = $("#enrollment_id").val();
		var student_id    = $("#student").val();

        if(e.keyCode == '13')
        {
			if(searchcodes !== "")
            {
				$.ajax({
					url: "/enrolments/searchclasssubject",
					type: 'POST',
					data: {'searchcodes':searchcodes, 'enrollment_id':enrollment_id, 'student_id':student_id},
					cache:false,
					beforeSend: function() {
						$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
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
						$('#confirmation').dialog('close');
						//console.log(data);
						$("#return_searchedclasses").html(data);
					}
				});	
			}
		}
    });

    $(document).on("click", ".check_searched_class", function(){
        var taken_units   = parseInt($("#enrolledunits").text());
		var allowed_units = parseInt($("#units_allowed").val());
        var can_overloadunits = $("#can_overloadunits").val();

        if($(this).is(':checked'))
        {
            var checkbox = $(this);
			var errors   = $(this).attr("data-errors");

			$('.check_searched_class:checked').each(function(){
				taken_units += isNaN(parseInt($(this).parent().siblings(".units").text())) ? 0 : parseInt($(this).parent().siblings(".units").text());
			});
            
            if(errors || (taken_units > allowed_units)){
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Selection</div><div class="message">The subject you are selecting has deficiency!<br>Continue selecting subject?</div>').dialog({
					show: 'fade',
					resizable: false,	
					draggable: false,
					width: 350,
					height: 'auto',
					modal: true,
					buttons: {
                        'Cancel':function(){
                            $(this).dialog('close');
                            $(checkbox).closest('tr').removeClass('selected');
                            $(checkbox).prop("checked", false);
                        },
                        'OK':function(){
                            $(this).dialog('close');
                            var haspermission = checkbox.attr("data-haspermission");

                            if(haspermission == 0){
                                showError('<p class="mid">ACCESS DENIED!</p>Your account does not have enough permission to override deficiency!')
                                $(checkbox).closest('tr').removeClass('selected')
                                $(checkbox).prop("checked", false);
                            }else{
                                if(taken_units > allowed_units)
                                {
                                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Selection</div><div class="message">The maximum units allowed for the student already exceeded.<br>Continue selecting subject?</div>').dialog({
                                        show: 'fade',
                                        resizable: false,	
                                        draggable: false,
                                        width: 350,
                                        height: 'auto',
                                        modal: true,
                                        buttons: {
                                            'Cancel':function(){
                                                $(this).dialog('close');
                                                $(checkbox).closest('tr').removeClass('selected')
                                                $(checkbox).prop("checked", false);
                                            },
                                            'OK':function(){
                                                $(this).dialog('close');
                                                if(can_overloadunits == 0){
                                                    showError('<p class="mid">ACCESS DENIED!</p>Your account does not have enough permission to override deficiency!')
                                                    $(checkbox).closest('tr').removeClass('selected')
                                                    $(checkbox).prop("checked", false);
                                                }else{
                                                    $(checkbox).closest('tr').addClass('selected');
                                                }
                                            }//end of ok button	
                                        }//end of buttons
                                    });//end of dialogbox
                                    $(".ui-dialog-titlebar").hide();
                                }
                            }
                            
                        }//end of ok button	
                    }//end of buttons
				});//end of dialogbox
				$(".ui-dialog-titlebar").hide();
            }else{
                // if(taken_units > allowed_units)
                // {
                //     $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Selection</div><div class="message">The maximum units allowed for the student already exceeded.<br>Continue selecting subject?</div>').dialog({
                //         show: 'fade',
                //         resizable: false,	
                //         draggable: false,
                //         width: 350,
                //         height: 'auto',
                //         modal: true,
                //         buttons: {
                //             'Cancel':function(){
                //                 $(this).dialog('close');
                //                 $(checkbox).closest('tr').removeClass('selected')
                //                 $(checkbox).prop("checked", false);
                //             },
                //             'OK':function(){
                //                 $(this).dialog('close');
                //                 if(can_overloadunits == 0){
                //                     showError('<p class="mid">ACCESS DENIED!</p>Your account does not have enough permission to override deficiency!')
                //                     $(checkbox).closest('tr').removeClass('selected')
                //                     $(checkbox).prop("checked", false);
                //                 }else{
                //                     $(checkbox).closest('tr').addClass('selected');
                //                 }
                //             }//end of ok button	
                //         }//end of buttons
                //     });//end of dialogbox
                //     $(".ui-dialog-titlebar").hide();
                // }
                $(checkbox).closest('tr').addClass('selected');
            }
        }
    });

    $(document).on("click", "#add_selected_classes", function(){
        $(this).attr("disable", true);

        var selected_classes = $(".check_searched_class:checked");
		var enrollment_id    = $("#enrollment_id").val();

        if(selected_classes.length === 0)
        {
			showError('Please select atleast one checkbox/class subject to add!');
			$("#add_selected_classes").attr("disabled", false);	
		}else{
            var class_ids = selected_classes.map(function(){ return $(this).attr("value"); }).get();

            $.ajax({
				url: "/enrolments/addselectedclasses/",
				type: 'POST',
				data: {"class_ids":class_ids, "enrollment_id":enrollment_id},
                dataType: 'json',
				beforeSend: function() {
						$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
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
                    console.log(response);
                    if(response.data.success === true)
                    {
                        showSuccess(response.data.message);
                        $.each(class_ids, function(i, val){
                            $("#searched_class_"+val).remove();
                        });
                        returnEnrolledClassSubjects(enrollment_id);
                        //scheduletable(enrollno);
                        var rowCount = $('#add_classsubjects_table > tbody tr').length;

                        if(rowCount == 0){
                            $('#add_classsubjects_table > tbody').append('<tr><td colspan="9" class="mid">No records to be displayed</td></tr>');
                        }
                    }
				},
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                    }
                }
			});	

            $("#add_selected_classes").attr("disabled",false);
        }
    });

    $(document).on("change","#section_searchclasses", function(){
        var section_id = $(this).val();
        var enrollment_id = $("#enrollment_id").val();
		var student_id    = $("#student").val();

        if(section_id)
        {
            $.ajax({
                url: "/enrolments/searchclasssubjectbysection",
                type: 'POST',
                data: {'enrollment_id':enrollment_id, 'student_id':student_id, 'section_id':section_id},
                cache:false,
                beforeSend: function() {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
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
                    $('#confirmation').dialog('close');
                    console.log(data);
                    $("#return_searchedclasses").html(data);
                }
            });
        }
    });

    $(document).on("click", "#save_enrollment", function(e){
        // var conflicts = $("#mayconflict").val();

		// if(conflicts > 0){
		// 	showError('There are conflict subjects, please check before saving!');
		// }else{
			//alert('saveenrollment');
			var checkboxes = $(".select_enrolled_class");

			if(checkboxes.length == 0)
            {
				showError('Please add at least one subject before saving enrolment!');
			}else{
				$("#save_enrollment").attr("disabled",true);
				$("#form_enrollment").submit();
			}
			
		//}		
        e.preventDefault();
    });

    $(document).on("submit", "#form_enrollment", function(e){

        var enrolledunits  = $("#enrolledunits").text();
		var enrollment_id = $("#enrollment_id").val();
		var postData      = $("#form_enrollment").serializeArray();
        postData.push({name: 'enrolled_units', value: enrolledunits });

        $.ajax({
            //url: "/enrolments/"+enrollment_id+"/saveenrollment",
            //type: 'POST',
            url: "/enrolments/"+enrollment_id,
            type: 'PUT',
            dataType: 'json',
            data: postData,
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
                $.ajax({
                    url: "/assessments/"+response,
                    type: 'GET',
                    // beforeSend: function() {
                    //     $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take some time, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                    //         show: 'fade',
                    //         resizable: false,	
                    //         width: 350,
                    //         height: 'auto',
                    //         modal: true,
                    //         buttons: false
                    //     });
                    //     $(".ui-dialog-titlebar").hide();
                    // },
                    success: function(data){
                        //console.log(response);
        
                        //$("#confirmation").dialog('close');
                        var header = '<div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">';
                            header += '<div class="modal-dialog modal-xl" role="document" style="max-width: 90% !important">';
                            header += '<div class="modal-content"><div class="modal-header"><h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Assessment Preview</h1>';
                            header += '</div><div class="modal-body">';
                        
                        var footer = '</div></div></div></div>';
                        $('#ui_content').html(header+data+footer);
                        $("#modalll").modal('show');
                        $("#save_assessment").focus();
                    }
                });
                //displayAssessmentPreview(response);
            },
            error: function (data) {
                console.log(data);
                $("#confirmation").dialog('close');
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    $.each(errors.errors, function (key, value) {
                        $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                    });
                }
            }
        });
        
        e.preventDefault();
    });

    function displayAssessmentPreview(assessment_id)
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
                //console.log(response);

                $("#confirmation").dialog('close');
                var header = '<div class="modal fade" id="modalll" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">';
                    header += '<div class="modal-dialog modal-xl" role="document" style="max-width: 90% !important">';
                    header += '<div class="modal-content"><div class="modal-header"><h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Assessment Preview</h1>';
                    header += '</div><div class="modal-body">';
                
                var footer = '</div></div></div></div>';
                $('#ui_content').html(header+response+footer);
                $("#modalll").modal('show');
                $("#save_assessment").focus();
            },
            error: function (data) {
                $("#confirmation").dialog('close');
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm()
                }
            }
        });
    }

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
                $('#modalll').modal('hide');
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
                                    location.reload();				
                                },
                                'OK':function(){
                                    $("#confirmation").dialog('close');
                                    window.open("/assessments/printassessment/"+assessment_id, '_blank'); 
                                    location.reload();				
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


});
