$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#student").select2({
	    // dropdownParent: $("#ui_content3"),
        minimumInputLength: 2,
        tags: [],
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

    $("#program").select2({
	    dropdownParent: $("#ui_content2")
	});

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
            $("#year_level").val(response.data.enrollment.year_level).trigger('change');
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
        }
    }

    function studentEnrolledSubjects(enrollment_id)
    {

    }

    function enrollmentScheduleTable(enrollment_id)
    {
        
    }

    function enrollmetInfo(student_id, studentinfo)
    {
        $.ajax({
            url: "/enrolments/enrolmentinfo ",
            type: 'POST',
            dataType: 'json',
            data: ({ 'student_id' : student_id, 'studentinfo' : studentinfo }),
            success: function(response){
                console.log(response);
                if(response.data.enrollment)
                {
                    if(response.data.enrollment.acctok === 0)
                    {
                        displayEnrollment(response);
                    }else{
                        showError('Student is already enrolled!');
                        $("#student").val(null).trigger('change');
                    }
                }else{
                    //INSERT ENROLLMENT
                    if(response.data.student.program_id === null)
                    {
                        showError('Student has no program. Please update student information!');
                        $("#student").val(null).trigger('change');
                        
                    }else{
                        if(response.data.probi === 1 || response.data.balance.hasbal === 1){
                            var message = '<h3>'+response.data.student.last_name+', '+response.data.student.first_name+' '+response.data.student.middle_name+'</h3><ul class="left">';
                            if(response.data.probi === 1){
                                message += '<li>The student was on academic probation in the previous semester enrolled. The student is advised to report to Academic Dean.<p></p></li>';
                            }
                            if(response.data.balance.hasbal === 1){
                                message += '<li>The student has previous balance last semester enrolled. The student is advised to report in Accounting Office.<p>'+response.data.balance.previous_balance.period+'</p><h1 class="nomargin mid" style="color:red">P '+response.data.balance.previous_balance.balance+'</h1></li>';
                            }
                            message += '</ul>';
                    
                            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Continue Enrollment?</div><div class="message">'+message+'<p></p>Continue student enrollment?</div>').dialog({
                                show: 'fade',
                                resizable: false,	
                                draggable: true,
                                width: 500,
                                height: 'auto',
                                modal: true,
                                buttons: {
                                        'Cancel':function(){
                                            $(this).dialog('close');	
                                        },
                                        'OK':function(){
                                            $(this).dialog('close');	
                                            insertStudentEnrollment(response);
                                        }	
                                    }//end of buttons
                                });//end of dialogbox
                            $(".ui-dialog-titlebar").hide();
                        }else{
                            insertStudentEnrollment(response);
                        }
                    }
                }
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    function insertStudentEnrollment(studentinfo)
    {
        $.ajax({
            url: "/enrolments",
            type: 'POST',
            dataType: 'json',
            data: ({ 'studentinfo' : studentinfo }),
            success: function(response){
                console.log(response);
                displayEnrollment(response);
            },
            error: function (data) {
                console.log(data);
            }
        });
    }

    $(document).on("change","#student", function(){
        var student_id = $(this).val();

        if(student_id){
            $.ajax({
                url: "/enrolments/getstudent",
                type: 'POST',
                dataType: 'json',
                data: ({ 'student_id' : student_id }),
                success: function(response){
                    console.log(response);
                    if(response.data.success === false)
                    {
                        showError(response.data.message);
                        $("#student").val(null).trigger('change');
                    }else{
                        //CHECK ENROLMENT INFORMATION
                        enrollmetInfo(student_id, response.data.values);
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }else{
            $('#form_enrollment')[0].reset();
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
                    //console.log(response);
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
                }
            });
        }else{
            $("#curriculum").html('<option value="">- select curriculum -</option>');
            $("#year_level").val('');
            $("#section").html('<option value="">- select section -</option>');
        }

        e.preventDefault();
    });

    $(document).on("change","#year_level", function(e){
        var program_id = $("#program").val();
        var year_level = $("#year_level").val();

        if(program_id && year_level)
        {
            $.ajax({
                url: "/sections/getsections",
                type: 'POST',
                data: ({ 'program_id' : program_id, 'year_level' : year_level}),
                dataType: 'json',
                success: function(response){
                    console.log(response);
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
            $("#section").html('<option value="">- select section -</option>');
        }

        e.preventDefault();
    });

});