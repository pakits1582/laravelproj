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

	$(document).on("change", ".checked_offered, .check_searched_class", function () {
        var takenunits = parseInt($("#enrolled_units").text());
        var allowedunits = parseInt($("#units_allowed").val());
    
        if ($(this).is(':checked')) {
            $('.checked_offered:checked, .check_searched_class:checked').each(function () {
                takenunits += isNaN(parseInt($(this).closest('tr').find('.units').text())) ? 0 : parseInt($(this).closest('tr').find('.units').text());
            });
    
            if (takenunits > allowedunits) {
                showError('The maximum units allowed exceeded!');
                $(this).prop('checked', false);
                return false;
            } else {
                $(this).closest('tr').addClass('selected');
            }
        } else {
            $(this).closest('tr').removeClass('selected');
        }
    });
    

	function returnEnrolledClassSubjects(enrollment_id)
    {
        $.ajax({
            url: "/registration/enrolledclasssubjects",
            type: 'POST',
            data: ({ 'enrollment_id':enrollment_id }),
            success: function(data){
                //console.log(data);
                $("#return_enrolled_subjects").html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

	function returnSectionOfferings(section_id, student_id, enrollment_id)
	{
		$.ajax({
            url: "/registration/sectionofferings",
            type: 'POST',
            data: ({ 'section_id':section_id, 'student_id':student_id, 'enrollment_id':enrollment_id }),
            success: function(data){
                //console.log(data);
                $("#return_section_subjects").html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
	}

	function returnScheduleTable(enrollment_id)
    {
        $.ajax({
			url: "/registration/scheduletable",
			type: 'POST',
			data: ({ 'enrollment_id' : enrollment_id}),
			success: function(data){
				$("#schedule_table").html(data);
			},
			error: function (data) {
				console.log(data);
			}
		});
    }

	$(document).on("submit", "#form_add_offerings", function(e){
		var section_id  = $("#section").val();
		var student_id  = $("#student_id").val();
		var enrollment_id = $("#enrollment_id").val();
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
				//console.log(response);
				if(response.success == true)
				{
					if($.isEmptyObject(response.full_slots) == false)
					{
						var full_slots = 'Failed to add the following class subjects:';
						$.each(response.full_slots, function (key, value) {
							full_slots += '<p>'+value.code+' - '+value.subject_code+'</p>';
						});
						full_slots += '<p>Slots was full before saving.</p>';
						showInfo(full_slots);
					}else{
						showSuccess(response.message);
					}

					returnScheduleTable(enrollment_id);
					returnEnrolledClassSubjects(enrollment_id);
					returnSectionOfferings(section_id, student_id, enrollment_id);
				}else{
					showError(response.message);
				}
				
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
							var section_id  = $("#section").val();
							var student_id  = $("#student_id").val();
							var enrollment_id = $("#enrollment_id").val();
							

                            $(".select_enrolled_class:checked").each(function() {
                                class_id = $(this).attr('value');
                                class_ids.push(class_id);
                            });

                            $.ajax({
                                url: '/registration/deleteenrolledsubjects',
                                type: 'DELETE',
                                data: ({ 'class_ids' : class_ids, 'enrollment_id' : enrollment_id }),
                                dataType: 'json',
                                success: function(response)
                                {
                                    if(response.data.success === true)
                                    {
                                        showSuccess(response.data.message);
                                    }
									returnScheduleTable(enrollment_id);
									returnEnrolledClassSubjects(enrollment_id)
									returnSectionOfferings(section_id, student_id, enrollment_id)
                                },
                                error: function (data) {
                                    console.log(data);
                                    var errors = data.responseJSON;
                                    if ($.isEmptyObject(errors) == false) {
                                        showError('Something went wrong! Can not perform requested action!');
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

	$(document).on("change","#section", function(){
        var section_id = $(this).val();
        var enrollment_id = $("#enrollment_id").val();
		var student_id    = $("#student_id").val();

        if(section_id)
        {
            $.ajax({
                url: "/registration/sectionofferingsbysection",
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
                    //console.log(data);
                    $("#return_section_subjects").html(data);
                },
				error: function (data) {
					console.log(data);
					var errors = data.responseJSON;
					if ($.isEmptyObject(errors) == false) {
						showError('Something went wrong! Can not perform requested action!');
					}
				}
            });
        }
    });

	$(document).on("click", "#add_subjects", function(e){
		$.ajax({url: "/registration/searchandaddclasses",success: function(data){
				$('#modal_container').html(data);
				$("#searchandaddclasses_model").modal('show');
			}
		});	
        e.preventDefault();
    });

    $(document).on('keyup', '#search_classes', function(e){
        var searchcodes   = $(this).val();
		var enrollment_id = $("#enrollment_id").val();
		var student_id    = $("#student_id").val();
        var curriculum_id = $("#curriculum_id").val();

        if(e.keyCode == '13')
        {
			if(searchcodes !== "")
            {
				$.ajax({
					url: "/registration/searchclasssubject",
					type: 'POST',
					data: {'searchcodes':searchcodes, 'enrollment_id':enrollment_id, 'student_id':student_id, 'curriculum_id':curriculum_id},
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

                        $('#scrollable_table_searched_classes').DataTable({
                            scrollY: 300,
                            scrollX: true,
                            scrollCollapse: true,
                            paging: false,
                            "bAutoWidth": false,
                            ordering: false,
                            info: false,
                            searching: false
                        });
					}
				});	
			}
		}
    });

    $(document).on("submit", "#form_add_selected_searched_classes", function(e){

        $("#add_selected_searched_classes").attr("disabled", true);
        var selected_classes = $(".check_searched_class:checked");
        
        if(selected_classes.length == 0)
        {
            showError('Please select at least one checkbox/class subject to add!');
            $("#add_selected_searched_classes").attr("disabled", false);	
		}else{
            var student_id = $("#student_id").val();
            var enrollment_id = $("#enrollment_id").val();
            var section_id  = $("#section").val();

            if(student_id && enrollment_id)
            {
                var postData = $("#form_add_selected_searched_classes").serializeArray();
                postData.push({name: 'student_id', value: student_id });		
                postData.push({name: 'enrollment_id', value: enrollment_id });	

                $.ajax({
                    url: "/registration/saveselectedclasses",
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
                        //console.log(response);
                        if(response.success == true)
                        {
                            if($.isEmptyObject(response.full_slots) == false)
                            {
                                var full_slots = 'Failed to add the following class subjects:';
                                $.each(response.full_slots, function (key, value) {
                                    full_slots += '<p>'+value.code+' - '+value.subject_code+'</p>';
                                });
                                full_slots += '<p>Slots was full before saving.</p>';
                                showInfo(full_slots);
                            }else{
                                showSuccess(response.message);
                            }

                            var class_ids = selected_classes.map(function(){ return $(this).attr("value"); }).get();
                            $.each(class_ids, function(i, val){
                                $("#table_row_"+val).remove();
                            });
        
                            returnScheduleTable(enrollment_id);
                            returnEnrolledClassSubjects(enrollment_id);
                            returnSectionOfferings(section_id, student_id, enrollment_id);

                            $("#add_selected_searched_classes").attr("disabled", false);	
                        }else{
                            showError(response.message);
                        }
                    },
                    error: function (data) {
                        $("#confirmation").dialog('close');
                        $("#add_selected_searched_classes").attr("disabled", false);	
                        //console.log(data);
                        var errors = data.responseJSON;
                        if ($.isEmptyObject(errors) == false) {        
                            showError('Oopps! Something went wrong, can not process request!');
                        }
                    }
                });
                
            }else{
                showError('Oopps! Something went wrong, please refresh the page!');
            }
        }

        e.preventDefault();
    });

    $(document).on("submit", "#form_registration", function(e){

        var enrollment_id = $("#enrollment_id").val();
        var has_conflict  = $("#has_conflict").val();
        var enrolled_units = parseInt($("#enrolled_units").text());
        var postData      = $("#form_registration").serializeArray();
        postData.push({name: 'enrolled_units', value: enrolled_units });
        
        $("#assess_registration").attr("disabled", true);

        if(has_conflict > 0)
        {
            showError('There are conflict schedules, please check before saving!');
            $("#assess_registration").attr("disabled", false);
        }else{
            var checkboxes = $(".select_enrolled_class");

            if(checkboxes.length == 0)
            {
                showError('Please add at least one class subject before assessing enrolment!');
                $("#assess_registration").attr("disabled", false);
            }else{
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to assess registration?<br>You can no longer make changes in your registration. Continue?</div>').dialog({
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

                                //console.log(postData);
                                $.ajax({
                                    url: "/registration/"+enrollment_id,
                                    type: 'PUT',
                                    dataType: 'json',
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
                                        //console.log(response);
                                        if(response.success === true)
                                        {
                                            $.ajax({
                                                url: "/registration/"+response.assessment_id+"/assessmentpreview",
                                                type: 'GET',
                                                beforeSend: function() {
                                                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Assessment Preview</div><div class="message">This may take some time, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
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
                                
                                                    var header = '<div class="modal fade" id="assessment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">';
                                                        header += '<div class="modal-dialog modal-xl" role="document" style="max-width: 90% !important">';
                                                        header += '<div class="modal-content"><div class="modal-header"><h1 class="modal-title h3 mb-0 text-primary font-weight-bold" id="exampleModalLabel">Assessment Preview</h1>';
                                                        header += '</div><div class="modal-body">';
                                                    
                                                    var footer = '</div></div></div></div>';

                                                    $('#ui_content4').html(header+data+footer);
                                                    $("#assessment_modal").modal('show');

                                                    $('#assessment_modal').on('shown.bs.modal', function (e) {
                                                        $("#save_assessment").focus();
                                                    });
                                                                        
                                                },
                                                error: function (data) {
                                                    console.log(data);
                                                }
                                            });
                                        }
                                    },
                                    error: function (data) {
                                        $("#confirmation").dialog('close');
                                        $("#assess_registration").attr("disabled", false);	
                                        //console.log(data);
                                        var errors = data.responseJSON;
                                        if ($.isEmptyObject(errors) == false) {        
                                            showError('Oopps! Something went wrong, can not process request!');
                                        }
                                    }
                                });
                            }//end of ok button	
                         }//end of buttons
                });//end of dialogbox
                $(".ui-dialog-titlebar").hide();
            }
        }

        e.preventDefault();
    });

    $(document).on("submit","#assessment_form", function(e){

        var enrolled_units = $("#enrolledunits").text();
        var assessment_id = $("#assessment_id").val();
		var postData = $(this).serializeArray(); 
		postData.push({name: 'enrolled_units', value: enrolled_units });
        $("#save_assessment").prop("disabled", true);
        
        $.ajax({
            url: "/registration/"+assessment_id+"/saveassessment",
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
                                    window.open("/registration/printassessment/"+assessment_id, '_blank'); 
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