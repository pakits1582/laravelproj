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

	function returnEnrolledClassSubjects(enrollment_id)
    {
        $.ajax({
            url: "/registration/enrolledclasssubjects",
            type: 'POST',
            data: ({ 'enrollment_id':enrollment_id }),
            success: function(data){
                console.log(data);
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
                console.log(data);
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
				console.log(response);
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
					returnEnrolledClassSubjects(enrollment_id)
					returnSectionOfferings(section_id, student_id, enrollment_id)
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
    
});