$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#instructor").select2({
	    dropdownParent: $("#ui_content3")
	});

    $("#program_id").select2({
	    dropdownParent: $("#ui_content2")
	});

    $(document).on("change", "#program_id", function(e){
        var program_id = $(this).val();

       
            $("#year_level, #section").val("");
            $("#button_group").addClass('d-none');
            $("#return_classsubjects").html('<tr><td colspan="13" class="mid">No records to be displayed</td></tr>');
        
        e.preventDefault();
    });

    $(document).on("change", "#year_level", function(e){
        var program_id = $("#program_id").val();
        var year_level = $("#year_level").val();

        if(program_id)
        {
            if(year_level)
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
                $("#section").val("");
                $("#button_group").addClass('d-none');
                $("#return_classsubjects").html('<tr><td colspan="13" class="mid">No records to be displayed</td></tr>');
            }
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
				console.log(data);
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
        }else{
            $("#button_group").addClass('d-none');
            $("#return_classsubjects").html('<tr><td colspan="13" class="mid">No records to be displayed</td></tr>');
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

    function editCLassSubject()
    {
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
                        if (response.data.tutorial === 1){ $('#tutorial').prop('checked', true) }
                        if (response.data.dissolved === 1){ $('#dissolved').prop('checked', true) }
                        if (response.data.f2f === 1){ $('#f2f').prop('checked', true) }
                        if (response.data.isprof === 1){ $('#isprof').prop('checked', true) }
                    }else{
                        showError('Oppss! Something went wrong! Can not fetch class subject data!');
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    $(document).keyup(function (event) {
	    if (event.keyCode == 113) {
            editCLassSubject();
	    };
	});

    $(document).on("click","#edit", function(){
		editCLassSubject();
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
		$('#dissolved, #tutorial, #f2f, #isprof').prop("checked", false);
		$('.checks').closest('tr').removeClass('selected');

        $('#instructor').val("").trigger('change'); 
        $('.errors').remove();  
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

    function checkConflicts(postData){
        $.ajax({
            url: "/classes/checkconflicts",
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log(response);
                if ($.isEmptyObject(response.error) === false) {
                    var errors = '<div id="conflicts_table"><table class="table table-sm table-striped table-bordered" style="font-size: 14px;">';
                        errors += '<thead>';
                        errors += '<tr>';
                        errors += '<th class="w100">Conflict</th>';
                        errors += '<th class="w70">Code</th>';
                        errors += '<th class="w120">Section</th>';
                        errors += '<th class="w120">Subcode</th>';
                        errors += '<th class="w300">Schedule</th>';
                        errors += '</tr>';
                        errors += '</thead>';
                        errors += '<tbody>';
                    $.each( response.error, function( key, value ) {
                            errors += '<tr>';
                            errors += '<td>'+value.conflict_from+'</td>';
                            errors += '<td>'+value.class_code+'</td>';
                            errors += '<td>'+value.section_code+'</td>';
                            errors += '<td>'+value.subject_code+'</td>';
                            errors += '<td>'+value.schedule+'</td>';
                            errors += '</tr>';
                    });
                    errors += '</body></table></div>';

                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Conflicts Detected!</div><div class="message">'+errors+'<br>Accept Conflicts?</div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        width: 'auto',
                        height: 'auto',
                        modal: true,
                        buttons: {
                            'Cancel':function(){
                                $(this).dialog('close');						
                            },
                            'OK':function(){
                                $(this).dialog('close');
                                updateCLassSubject(postData);
                                }//end of ok button	
                            }//end of buttons
                        });//end of dialogbox
                    $(".ui-dialog-titlebar").hide();
                }else{
                    console.log(response);
                    updateCLassSubject(postData);
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }
    
/*********************************************************
*** FUNCTION ON SUBMIT FORM SAVE UPDATE CLASS SUBJECT  ***
*********************************************************/
    $(document).on("submit", "#form_classoffering", function(e){
        var checkedbox = $(".checks:checked");
        var postData = $("#form_classoffering").serializeArray();
        var class_id   = checkedbox.attr("data-classid");
        postData.push({ name: "class_id", value: class_id });
        
        if(checkedbox.length == 0)
        {
			showError('Please select checkbox to be updated!');
		}else{
            var schedule = $("#schedule").val();

            if(schedule)
            {
                if(checkScheduleFormat(schedule))
                {
                    showError(checkScheduleFormat(schedule));
                }else{
                    $.ajax({
                        url: "/classes/checkroomschedule",
                        type: 'POST',
                        data: postData,
                        dataType: 'json',
                        success: function(response){
                            console.log(response);
                            if ($.isEmptyObject(response.error) === false) {
                                showError(response.error);
                            }else{
                                //CHECK CONFLICT SECTION, INSTRUCTOR
                                checkConflicts(postData);
                            }
                        },
                        error: function (data) {
                            console.log(data);
                        }
                    });
                }
            }else{
                updateCLassSubject(postData);
            }
        }
        e.preventDefault();
    });

    function updateCLassSubject(postData)
    {
        var class_id   = $(".checks:checked").attr("data-classid");

        if($('#dissolved').is(':checked')){
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Dissolved</div><div class="message">Are you sure you want to dissolved selected subject? <br>The subject/s will be removed from the enrolled subjects and will be subject to reassessment. Continue?</div>').dialog({
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
                            saveUpdatedClassSubject(postData, class_id);
                        }//end of ok button	
                    }//end of buttons
                });//end of dialogbox
                $(".ui-dialog-titlebar").hide();
            //end of dialogbox
        }else{
            saveUpdatedClassSubject(postData, class_id);
        }
    }

    function saveUpdatedClassSubject(postData, class_id)
    {
        $.ajax({
            url: "/classes/"+class_id,
            type: 'PUT',
            data: postData,
            dataType: 'json',
            beforeSend: function() {
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
                    show: 'fade',
                    resizable: false,	
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons:false
                });
                $(".ui-dialog-titlebar").hide();
            },
            success: function(data){
                showSuccess('Class Subject Successfully Updated!');
                $('#cancel').trigger('click');

                $("#confirmation").dialog('close');
                var section = $("#section").val();

                returnClassSubjects(section)
                console.log(data);
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
        });//end of ajax updateclass
    }

    $(document).on("click","#generatecode", function(){
		var section   = $("#section").val();
		
		$.ajax({
			url: "/classes/generatecode/",
			type: 'GET',
            beforeSend: function() {
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
                    show: 'fade',
                    resizable: false,	
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons:false
                });
                $(".ui-dialog-titlebar").hide();
            },
			success: function(data){
                console.log(data);
                
                $("#confirmation").dialog('close');
                showSuccess(data);
                returnClassSubjects(section);
			}
		});	
	});

    $(document).on("click", "#copy_class", function(e){
        var section = $("#section").val();

        if(section){
            $.ajax({url: "/classes/"+section+"/copyclass",success: function(data){
                    //console.log(data);
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
        }else{
            showError('Please select section first!');
        }

        e.preventDefault();
    });

    $(document).on("change", ".copyclass_dropdown", function(e){
        var section = $("#section_copyfrom").val();
        var period  = $("#period_copyfrom").val();

        if(section && period){
            $.ajax({
                url: "/classes/sectionclasssubjects",
                type: 'POST',
                data: ({ 'section' : section, 'period' : period}),
                success: function(response){
                    var table = '';
                    if ($.isEmptyObject(response.data) === false) {
                        $.each( response.data, function( key, value ) {
                            table += '<tr>';
                                table += '<td>'+value.sectioninfo.code+'</td>';
                                table += '<td>'+value.curriculumsubject.subjectinfo.code+'</td>';
                                table += '<td>'+value.curriculumsubject.subjectinfo.name+'</td>';
                                table += '<td class="mid">'+value.units+'</td>';
                                table += '<td class="mid">'+value.tfunits+'</td>';
                                table += '<td class="mid">'+value.loadunits+'</td>';
                                table += '<td class="mid">'+value.lecunits+'</td>';
                                table += '<td class="mid">'+value.labunits+'</td>';
                                table += '<td class="mid">'+value.hours+'</td>';
                                var slots = (value.slots !== null) ? value.slots : '';
                                table += '<td class="mid">'+slots+'</td>';
                            table += '</tr>';
                        });
                    }else{
                        table = 'tr><td colspan="10" class="mid">No records to be displayed</td></tr>';
                    }
                    $("#return_copy_classsubjects").html(table);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
        e.preventDefault();
    });

    $(document).on("submit","#form_copyclass", function(e){
        var section_copyfrom = $("#section_copyfrom").val();
        var period_copyfrom  = $("#period_copyfrom").val();

        var url = $(this).attr('action');
      	var postData = $(this).serializeArray();

        if(section_copyfrom && period_copyfrom){
            var section_copyto = $("#section_copyto").val();
            var period_copyto  = $("#period_copyto").val();

            if(section_copyfrom === section_copyto  && period_copyfrom === period_copyto)
            {
                showError('You are trying to copy from the same section where you want to save!');
            }else{
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: postData,
                    dataType: 'json',
                    beforeSend: function() {
                        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
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
                        if(response.success){
                            var section = $("#section").val();
                            showSuccess(response.message);
                            $("#modalll").modal('hide');
                            returnClassSubjects(section);
                        }
                    },
                    error: function (data) {
                        $("#confirmation").dialog('close');
                        console.log(data);
                        var errors = data.responseJSON;
                        if ($.isEmptyObject(errors) == false) {
                            showError(errors.message);
                        }
                    }
                });
            }
        }else{
            showError('Please select section and period first!');
        }
        e.preventDefault();
    });

    $(document).on("click", "#delete", function(e){

        var class_id = $(".checks:checked").attr("data-classid");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/subject to delete!');	
		}else{
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
                            $('#cancel').trigger('click');
                        },
                        'OK':function(){
                            $("#confirmation").dialog('close');
                            $.ajax({
                                url: '/classes/'+class_id,
                                type: 'DELETE',
                                dataType: 'json',
                                success: function(response)
                                {
                                    if(response.data.success === false)
                                    {
                                        showError(response.data.message);
                                        $('#cancel').trigger('click');
                                    }else{
                                        showSuccess('Class Subject Successfully Deleted!');
                                        $('#cancel').trigger('click');
                                        var section = $("#section").val();

                                        returnClassSubjects(section)
                                        console.log(response);
                                    }
                                    
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
        }
        e.preventDefault();
    });

    $(document).on("click", ".merge", function(e){
        var class_id = $(this).attr("id");

        $.ajax({url: "/classes/"+class_id, success: function(response)
            {
                console.log(response);

                var number_of_enrolled = response.data.enrolledstudents.length

                if(number_of_enrolled > 0)
                {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Merge</div><div class="message">The selected subject has '+number_of_enrolled+'enrolled students. Continue merging?</div>').dialog({
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
                                            url: "/classes/merge",
                                            type: 'POST',
                                            data: {"class":response.data},
                                            success: function(data){
                                                $('#ui_content').html(data);
                                                $("#modalll").modal('show');
                                            }
                                        });	
                                    }//end of ok button	
                                }//end of buttons
                    });//end of dialogbox
                    $(".ui-dialog-titlebar").hide();
                    //end of dialogbox
                }else{
                    $.ajax({
                        url: "/classes/merge",
                        type: 'POST',
                        data: {"class":response.data},
                        success: function(data){
                            $('#ui_content').html(data);
                            $("#modalll").modal('show');
                        }
                    });	
                }

            }
        });	
        e.preventDefault();
    });

    function searchCodeToMerge(searchcode, class_id)
    {
        $.ajax({
            url: "/classes/searchcodetomerge",
            type: 'post',
            data: {'searchcode':searchcode, 'class_id':class_id},
            //dataType: 'json',
            success: function(data)
            {
                //console.log(data);
                $("#return_search_classtomerge").html(data);
            }
        });
    }

    $(document).on("keyup","#search_classtomerge",function(e) {
		var searchcode = $(this).val();
        var class_id = $("#class_id").val();
		
		if (e.keyCode == '13')
        {
			if(searchcode !== "")
            {
               searchCodeToMerge(searchcode, class_id);
			}
		}
	});

    $(document).on("submit", "#form_merge_class", function(e){
        var url = $(this).attr('action');
        var checkbox_merge = $(".checkbox_merge:checked");
      	var postData = $(this).serializeArray();

        if(checkbox_merge.length == 0){
			showError('Please select at least one checkbox or class subject to merge!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to merge selected class subjects? Do you want to continue?</div>').dialog({
                show: 'fade',
                resizable: false,	
                draggable: false,
                width: 350,
                height: 'auto',
                modal: true,
                buttons: {
                        'Cancel':function(){
                            $("#confirmation").dialog('close');
                            $('#cancel').trigger('click');
                        },
                        'OK':function(){
                            $("#confirmation").dialog('close');
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
                                    //console.log(data);
                                    
                                    if(data.success === true)
                                    {
                                        showSuccess(data.message);
                                        returnClassSubjects($("#section").val());
                                        
                                        var searchcode = $("#search_classtomerge").val();
                                        var class_id   = $("#class_id").val();

                                        searchCodeToMerge(searchcode, class_id);
                                        returnMergedClassSubjects($("#class_id").val());

                                    }else{
                                        showError(data.message);
                                    }  
                                },
                                error: function (data) {
                                    $("#confirmation").dialog('close');
                                    console.log(data);
                                }
                            });
                        }//end of ok button	
                    }//end of buttons
            });//end of dialogbox
        }
        
        e.preventDefault()
    });

    function unmergeClassSubject(class_id)
    {
        $.ajax({
            url: "/classes/unmergesubject",
            type: 'POST',
            data: {"class_id":class_id},
            success: function(data){
                console.log(data);
                if(data.success === true)
                {
                    showSuccess(data.message);
                }else{
                    showError(data.message);
                }  
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action! '+errors.message);
                }
            }
        });	
    }

    function returnMergedClassSubjects(class_id)
    {
        $.ajax({
            url: "/classes/"+class_id+"/viewmergedclasses",
            // type: 'POST',
            // data: {"class_id":class_id},
            success: function(data){
                console.log(data);
                $("#return_merged_classes").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action! '+errors.message);
                }
            }
        });
    }

    $(document).on("click",".unmerge", function(e){
		var class_id = $(this).attr("id");
        var mergeto = $(this).attr("data-mergeto");

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Unmerge</div><div class="message">This class subject is currently merged to '+mergeto+'. Are you sure you want to unmerge selected class subject? Do you want to continue?</div>').dialog({
            show: 'fade',
            resizable: false,	
            draggable: false,
            width: 350,
            height: 'auto',
            modal: true,
            buttons: {
                    'Cancel':function(){
                        $(this).dialog('close');

                        $('input.checks').prop('disabled', false).prop('checked', false).closest('tr').removeClass('selected');
                    },
                    'OK':function(){
                        $(this).dialog('close');
                        unmergeClassSubject(class_id);

                        returnClassSubjects($("#section").val());
                    }//end of ok button	
                }//end of buttons
         });//end of dialogbox
        $(".ui-dialog-titlebar").hide();
			
		e.preventDefault();
	});

    $(document).on("click",".unmerge_class_subject", function(e){
		var class_id = $(this).attr("id");

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Unmerge</div><div class="message">Are you sure you want to unmerge selected class subject? Do you want to continue?</div>').dialog({
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

                        unmergeClassSubject(class_id);
                        returnMergedClassSubjects($("#class_id").val());
                        returnClassSubjects($("#section").val());
                    }//end of ok button	
                }//end of buttons
        });//end of dialogbox

        $(".ui-dialog-titlebar").hide();
			
		e.preventDefault();
	});

    $(document).on("click", "#display_enrolled", function(e){
        var class_id = $(".checks:checked").attr("data-classid");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/subject to display!');	
		}else{
            $.ajax({
                url: "/classes/"+class_id+"/displayenrolled",
                type: 'GET',
                success: function(data){
                    console.log(data);
                    
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        showError('Something went wrong! Can not perform requested action! '+errors.message);
                    }
                }
            });
        }

        e.preventDefault();
    });

});