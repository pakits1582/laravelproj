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

    $("#subject").select2({
	    dropdownParent: $("#ui_content2"),
        minimumInputLength: 2,
        tags: false,
        minimumResultsForSearch: 20, // at least 20 results must be displayed
        ajax: {
            url: '/subjects/dropdownselectsearch',
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
    

    $("#period_id").select2({
	    dropdownParent: $("#ui_content2")
	});

    $("#instructor").select2({
	    dropdownParent: $("#ui_content4")
	});

    function returnGradeInfo(student_id, period_id, origin)
    {
        $.ajax({
            type: "POST",
            url:  "/grades/gradeinfo",
            data:  {'student_id':student_id, 'period_id':period_id, 'origin':origin},
            dataType: 'json',
            cache: false, 
            success: function(response){
                //console.log(response);
                $("#grade_id").val(response.id);
            } 
        });
    }

    $(document).on("change", "#student", function(e){
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

        if(student_id){
            $.ajax({
                url: "/students/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    //console.log(response);
                    if(response.data == false)
                    {
                        $('.actions').prop('disabled', true);
                        showError('Student not found!');
                        clearForm();
                    }else{
                        $('.actions').prop('disabled', false);
                        $("#program").val(response.data.values.program.name ? response.data.values.program.name : "");
                        $("#educational_level").val((response.data.values.program.level) ? response.data.values.program.level.code ? response.data.values.program.level.code : "" : "");
                        $("#college").val((response.data.values.program.collegeinfo) ? response.data.values.program.collegeinfo.code ? response.data.values.program.collegeinfo.code : "" : "");
                        $("#curriculum").val(response.data.values.curriculum.curriculum ? response.data.values.curriculum.curriculum : "");
                        $("#year_level").val(response.data.values.year_level ? response.data.values.year_level : "");
                        
                        returnGradeInfo(student_id, period_id, 0);
                        returnGradefile(student_id, period_id);
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        $('.actions').prop('disabled', true);
                        clearForm();
                    }
                }
            });
        }

        e.preventDefault();
    });

    $(document).on("change", "#period_id", function()
    {
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

        if(student_id && period_id)
        {
            returnGradeInfo(student_id, period_id, 0);
        }

        if(!period_id)
        {
            $("#grade_id").val("");
        }
        returnGradefile(student_id, period_id);

    });

    function returnGradefile(student_id, period_id)
    {
        $.ajax({
            type: "POST",
            url:  "/grades/gradefile",
            data:  {'student_id':student_id, 'period_id': period_id},
            // dataType: 'json',
            cache: false, 
            success: function(response){
                //console.log(response);
                $("#return_gradefile").html(response);
            },
            error: function (data) {
                //console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    $("#return_gradefile").html('<h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>');
                    $('.actions').prop('disabled', true);
                }
            }
        });
    }

});