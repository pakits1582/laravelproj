//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    
    $('.datepicker').datepicker(pickerOpts);  

    $("#student").select2({
	    dropdownParent: $("#ui_content2"),
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
                console.log(data);
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
	    dropdownParent: $("#ui_content2")
	});

    function getStudentExternalGrades(student_id, grade_id = null)
    {
        var period_id = $("#period").val();
        var school_id = $("#school").val();
        var program_id = $("#program_id").val();

        if(student_id && period_id)
        {
            $.ajax({
                url: "/gradeexternals/"+student_id+"/"+period_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);

                    var grade_nos = '<option value="">- select grade -</option>';
                    $.each(response.data, function(k, v){
                        grade_nos += '<option value="'+v.id+'"';
                        grade_nos += (v.school_id === school_id && v.program_id === program_id) ? 'selected' : '';
                        grade_nos += (v.id === grade_id) ? 'selected' : '';
                        grade_nos += '>'+v.id+'</option>';       
                    });
                    $("#grade_id").html(grade_nos);
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
    }

    $(document).on("change", "#student", function(e){
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
                        $("#student").val(null).trigger('change');
                    }else{
                        $("#curriculum").val(response.data.values.curriculum.curriculum);
                        $("#year_level").val(response.data.values.year_level);            
                        $("#educational_level").val(response.data.values.program.level.level);
                        $("#college").val(response.data.values.program.collegeinfo.code);
                        $("#program").val(response.data.values.program.name);

                        getStudentExternalGrades(student_id);
                        $("#return_external_grades").html('<tr><td colspan="13" class="mid">No records to be displayed</td></tr>');
                        $("#school, #program_id").val('').trigger('change');
                        $("#cancel").trigger('click');

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
        }else{
            $("#return_external_grades").html('<tr><td colspan="13" class="mid">No records to be displayed</td></tr>');
            $("#school, #program_id").val('').trigger('change');
            $("#cancel").trigger('click');
        }
        
        e.preventDefault();
    });

    $("#school").select2({
        dropdownParent: $("#ui_content3")
    });

    $("#program_id").select2({
        dropdownParent: $("#ui_content4")
    });

    $(document).on("change", "#grade_id", function(e){
        var grade_id = $("#grade_id").val();

        $("#cancel").trigger('click');

        if(grade_id){
            $.ajax({
                url: "/grades/"+grade_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    $("#school").val(response.school_id).trigger('change');
                    $("#program_id").val(response.program_id).trigger('change');

                    getExternalGradeSubjects(grade_id);
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
        }else{
            $("#school").val('').trigger('change');
            $("#program_id").val('').trigger('change');
            $("#return_external_grades").html('<tr><td colspan="13" class="mid">No records to be displayed</td></tr>');
        }

        e.preventDefault();
    });

    function getExternalGradeSubjects(grade_id)
    {
        $.ajax({
            url: "/gradeexternals/externalgradesubjects/"+grade_id,
            type: 'GET',
            success: function(data){
                $("#return_external_grades").html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    $(document).on("submit", "#form_externalgrade", function(e){
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        var student_id = $("#student").val();
        var period_id = $("#period").val();
        
        postData.push(
            {name: 'student_id', value: student_id },
            {name: 'period_id', value: period_id },
        );

        if(student_id && period_id)
        {
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
                    $('.alert').remove();
    
                    showMessageInForm('form_externalgrade', response.data.alert, response.data.message);
                    if(response.data.success){
                        $(".clearable").val('');
                        getExternalGradeSubjects(response.data.grade_id);
                        getStudentExternalGrades(student_id, response.data.grade_id);
                    }
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
        }else{
            showError('Please select student and period first before adding an external grade!');
        }

        e.preventDefault();
    });

    $(document).on("click","#cancel", function(){
        //buttons
		$('#delete').prop("disabled", true);
		$('#edit').prop("disabled", true);
        //form fields
		$('.clearable').val("");
		$('input.checks').prop('disabled', false).prop('checked', false);
		$('.checks').closest('tr').removeClass('selected');
        $('.errors').remove();  

        $('.form_externalgrade').prop("id", 'form_externalgrade');
	});

    $(document).on("click", "#edit", function(e){
        var external_subject_id = $(".checks:checked").attr("data-id");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/subject to edit!');	
		}else{
            $.ajax({
                url: "/gradeexternals/"+external_subject_id+"/edit",
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(!jQuery.isEmptyObject(response)){
                        $('#edit').prop("disabled", true);
                        $('input.checks').prop('disabled', true); 

                        $("#school").val(response.data.gradeinfo.school_id).trigger('change');
                        $("#program_id").val(response.data.gradeinfo.program_id).trigger('change');

                        $('#subject_code').val(response.data.subject_code);
                        $('#subject_description').val(response.data.subject_description);
                        $('#grade').val(response.data.grade);
                        $('#completion_grade').val(response.data.completion_grade);
                        $('#units').val(response.data.units);
                        $('#remark').val(response.data.remark_id);
                        $('#equivalent_grade').val(response.data.equivalent_grade);
                        $('#external_grade_id').val(response.data.id);

                        $('.form_externalgrade').prop("id", 'form_update_externalgrade');
                    }else{
                        showError('Oppss! Something went wrong! Can not fetch grade subject data!');
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

        e.preventDefault();
    });

    $(document).on("submit", "#form_update_externalgrade", function(e){
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        var student_id = $("#student").val();
        var period_id = $("#period").val();
        var external_grade_id = $("#external_grade_id").val();
        
        postData.push(
            {name: 'student_id', value: student_id },
            {name: 'period_id', value: period_id },
        );

        if(student_id && period_id && external_grade_id)
        {
            $.ajax({
                url: url+"/"+external_grade_id,
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
                success: function(response){
                    $("#confirmation").dialog('close');
                    console.log(response);
                    
                    $('.alert').remove();
    
                    showMessageInForm('form_update_externalgrade', response.data.alert, response.data.message);
                    if(response.data.success){
                        $("#cancel").trigger('click');

                        getExternalGradeSubjects(response.data.grade_id);
                    }
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
        }else{
            showError('Please select student and period and subject first before updating an external grade!');
        }
        e.preventDefault();
    });

    $(document).on("click", "#delete", function(e){
		var external_subject_id = $(".checks:checked").attr("data-id");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/subject to delete!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete selected item?</div>').dialog({
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
                                url: "/gradeexternals/"+external_subject_id,
                                type: 'DELETE',
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
                                    console.log(response);
                                    $("#confirmation").dialog('close');

                                    if(response.data.success !== false){
                                        showSuccess(response.data.message);

                                        $("#school").val('').trigger('change');
                                        $("#program_id").val('').trigger('change');
                                        $("#cancel").trigger('click');

                                        var grade_id = $("#grade_id").val();
                                        var student_id = $("#student").val();

                                        getExternalGradeSubjects(grade_id);
                                        getStudentExternalGrades(student_id);

                                    }else{
                                        showError(response.data.message);
                                    }
                                    
                                },
                                error: function (data) {
                                    //console.log(data);
                                    $("#confirmation").dialog('close');
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
        }
		e.preventDefault();
	});
});