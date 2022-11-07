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

    function getStudentExternalGrades(student_id)
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
            //$('#form_enrollment')[0].reset();
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
                success: function(response){
                    console.log(response);
                    
                    $('.alert').remove();
    
                    showMessageInForm('form_externalgrade', response.data.alert, response.data.message);
                    if(response.data.success){
                        $(".clearable").val('');
                        getExternalGradeSubjects(response.data.grade_id);
                    }
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
	});
});