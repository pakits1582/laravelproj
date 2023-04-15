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

    function returnStudentadjustments(enrollment_id, period_id)
    {
        if(enrollment_id){
            $.ajax({
                url: "/studentadjustments/studentadjustments",
                type: 'POST',
                data: ({ 'enrollment_id':enrollment_id, 'period_id':period_id }),
                success: function(data){
                    console.log(data);
                    $("#return_studentadjustments").html(data);
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearForm()
                        $("#return_studentadjustment").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');

                    }
                }
            });
        }
    }

    $(document).on("change", "#student, #period", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period").val();

        var period_name = $("#period option:selected").text();
        $("#period_name").text(period_name);

        $.ajax({
            url: "/enrolments/"+student_id+"/"+period_id,
            type: 'GET',
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
            success: function(response){
                $("#confirmation").dialog('close');
                console.log(response);
                if(response.data === false)
                {
                    showError('Student is not enrolled!');
                    clearForm();
                    $("#return_studentadjustment").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');
                }else{
                    if(response.data.acctok === 0)
                    {
                        showError('Student enrollment is not yet saved! Please save enrollment first!');
                        clearForm();
                        $("#return_studentadjustment").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');
                    }else{
                        if(response.data.assessed === 0)
                        {
                            showError('Student enrollment is not yet assessed! Please save assessment first!');
                            clearForm();
                            $("#return_studentadjustment").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');
                        }else{
                            // //DISPLAY ENROLLMENT
                            $("#enrollment_id").val(response.data.id);
                            $("#assessment_id").val(response.data.assessment.id);
                            $("#program").val(response.data.program.code);
                            $("#educational_level").val(response.data.program.level.code);
                            $("#college").val(response.data.program.collegeinfo.code);
                            $("#curriculum").val(response.data.curriculum.curriculum);
                            $("#year_level").val(response.data.year_level);
                            $("#section").val(response.data.section.code);

                            $("#save_adjustment").prop("disabled", false);

                            returnStudentadjustments(response.data.id, period_id);
                        }
                    }
                }
            },
            error: function (data) {
                $("#confirmation").dialog('close');
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm();
                    $("#return_studentadjustment").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');
                }
            }
        });

        e.preventDefault();
    });

    $(document).on("submit", "#form_studentadjustment", function(e){
        var postData   = $(this).serializeArray();
        var enrollment_id = $("#enrollment_id").val();
        var period_id  = $("#period").val();

        $.ajax({
            url: "/studentadjustments",
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
                if(response.data.success === true)
                {
                    showSuccess(response.data.message);
        
                }else{
                    showerror(response.data.message);
                }
                returnStudentadjustments(enrollment_id, period_id)
            },
            error: function (data) {
               console.log(data);
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

    $(document).on("click", ".delete_adjustment", function(e){
		var id = $(this).attr("id");
        var enrollment_id = $("#enrollment_id").val();
        var period_id  = $("#period").val();

		$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete adjustment?</div>').dialog({
			show: 'fade',
			resizable: false,	
			draggable: false,
			width: 350,
			height: 'auto',
			modal: true,
			buttons: {
					'Cancel':function(){
						$(this).dialog('close');
					},
					'OK':function(){
						$(this).dialog('close');
						$.ajax({
							url: '/studentadjustments/'+id,
							type: 'DELETE',
							dataType: 'json',
							success: function(response){
								console.log(response);
								if(response.data.success === true)
                                {
                                    showSuccess(response.data.message);
                        
                                }else{
                                    showerror(response.data.message);
                                }

                                returnStudentadjustments(enrollment_id, period_id);
							},
							error: function (data) {
								//console.log(data);
								var errors = data.responseJSON;
								if ($.isEmptyObject(errors) === false) {
									showError('Something went wrong! Can not perform requested action! '+errors.message);
								}
							}
						});
					}//end of ok button	
				}//end of buttons
			});//end of dialogbox
			$(".ui-dialog-titlebar").hide();
		//end of dialogbox
		e.preventDefault();
	});
});