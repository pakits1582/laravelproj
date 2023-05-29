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

    $(document).on("change", "#student", function(e){
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

        if(student_id){
            $.ajax({
                url: "/students/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data == false)
                    {
                        showError('Student not found!');
                        clearForm();
                    }else{
                        $("#program").val(response.data.values.program.name ? response.data.values.program.name : "");
                        $("#educational_level").val((response.data.values.program.level) ? response.data.values.program.level.code ? response.data.values.program.level.code : "" : "");
                        $("#college").val((response.data.values.program.collegeinfo) ? response.data.values.program.collegeinfo.code ? response.data.values.program.collegeinfo.code : "" : "");
                        $("#curriculum").val(response.data.values.curriculum.curriculum ? response.data.values.curriculum.curriculum : "");
                        $("#year_level").val(response.data.values.year_level ? response.data.values.year_level : "");
                        
                        returnGradeInfo(student_id, period_id, 0);
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearForm();
                    }
                }
            });
        }

        e.preventDefault();
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
                console.log(response);
                $("#grade_id").val(response.id);
                returnInternalGrades(response.id);
            } 
        });
    }

    function returnInternalGrades(grade_id, nextr="")
    {
        if(grade_id)
        {
            $.ajax({
                type: "POST",
                url:  "/gradeinternals/internalgrades",
                data:  {'grade_id':grade_id},
                cache: false, 
                success: function(response){
                    console.log(response);
                    $("#return_internal_grades").html(response);

                    if (nextr !== "") {
                        $("#"+nextr).focus();
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        $("#return_internal_grades").html('<tr><td class="mid" colspan="13">No records to be displayed!</td></tr>');

                    }
                }
            });
        }else{
            $("#grade_id").val("");
            $("#return_internal_grades").html('<tr><td class="mid" colspan="13">No records to be displayed!</td></tr>');
        }
    }

    $(document).on("change", "#period_id", function()
    {
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

        if(student_id && period_id)
        {
            returnGradeInfo(student_id, period_id, 0);
        }else{
            $("#grade_id").val("");
            $("#return_internal_grades").html('<tr><td class="mid" colspan="13">No records to be displayed!</td></tr>');
        }
    });

    // $(document).on("click", "#add_internal_grade", function(){
    //     var grade_id = $("#grade_id").val();

    //     $.ajax({
    //         type: "GET",
    //         url: "/gradeinternals/create",
    //         data: ({ 'grade_id':grade_id }),
    //         success: function(data){
    //             console.log(data);
    //             $('#ui_content').html(data);
    //             $("#modalll").modal('show');
    //         }
    //     });
    // });

    $(document).on('keydown', ".editable",function(e){  
		if (e.which === 13 && e.shiftKey === false || e.which === 32){
			var td      = $(this);
			var internal_grade_id = $(this).attr("id");
			var grade   = $(this).text(); 
			var origval = $(this).attr("data-value");
            var grade_id = $("#grade_id").val();

			if(grade != origval)
			{
				$.ajax({
					type: "POST",
					url: "/gradeinternals/inlineupdategrade",
					data: ({"internal_grade_id":internal_grade_id, "grade":grade}),
					cache: false,
					success: function(response){
                        console.log(response);

                        if(response.success == true)
                        {
                            showSuccess(response.message);

                            var nextr = $(td).closest('tr').next('tr').attr("id");
							nextr = (typeof nextr !== "undefined") ? nextr.replace('remove_','') : '';

							returnInternalGrades(grade_id, nextr);
                
                        }else{
                            showError(response.message);
                            td.text(origval);
                        }
					}
				});
			}
	        return false;
	    }
	});

    $(document).on("click", "#grade_information", function(){
        var grade_id = $("#grade_id").val();

        if(grade_id)
        {
            $.ajax({
                type: "GET",
                url: "/grades/"+grade_id,
                success: function(data){
                    console.log(data);
                    $('#ui_content').html(data);
                    $("#grade_information_modal").modal('show');

                    $("#school_id, #program_id").select2({
                        dropdownParent: $("#grade_information_modal")
                    });

                    $(".datepicker").datepicker(pickerOpts);
                }
            });
        }else{
            showError('Grade No. is required if adding Grade Information')
        }
    });

    $(document).on("click", "#add_remark", function(e){

        var select =    '<div class="row mb-1 addedremarkform">';
            select +=       '<div class="col-md-2">';
            select +=           '<select name="display[]" class="form-control" class="display">';
            select +=               '<option value="1">After</option>';
            select +=               '<option value="2">Before</option>';
            select +=           '</select>';
            select +=       '</div>';

            select +=       '<div class="col-md-6">';
            select +=           '<textarea name="note" class="form-control text-uppercase" rows="2"></textarea>';
            select +=       '</div>';
            select +=       '<div class="col-md-2">';
            select +=           '<select name="underline[]" class="form-control" class="display">';
            select +=               '<option value="1">Yes</option>';
            select +=               '<option value="0">No</option>';
            select +=           '</select>';
            select +=       '</div>';

            select +=       '<div class="col-md-2">';
            select +=           '<button type="button" id="" class="remove_remark btn btn-danger btn-icon-split mb-2">';
            select +=               '<span class="icon text-white-50">';
            select +=               '<i class="fas fa-trash"></i>';
            select +=               '</span>';
            select +=               '<span class="text">Remove</span>';
            select +=           '</button>';
            select +=       '</div>';
            select +=  '</div>';

        $('#grade_remarks').append(select);

        e.preventDefault();
    });

    $(document).on("click", ".remove_remark", function(e){
		$(this).closest("div.row").remove();
        e.preventDefault();
	});

    $(document).on("change", "#soresolution_id", function(){
        var val = $(this).val();

        if(val == 'addsoresolution')
        {
            $("#soresolution_id option:selected").prop('selected', false);

            $.ajax({
                type: "GET",
                url: "/grades/addsoresolution",
                success: function(data){
                    console.log(data);
                    $('#ui_content2').html(data);
                    $("#soresolution_modal").modal('show');
                }
            });
        }
    });

    $(document).on("submit",'#addsoresolution_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                $('.alert').remove();

                $("#addsoresolution_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#soresolution_id option:last').before($("<option></option>").attr("value", data.soresolution_id).text(data.title));
                    $('#addsoresolution_form')[0].reset();
                }
            },
            error: function (data) {
                //console.log(data);
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

    $(document).on("change", "#issueing_office_id", function(){
        var val = $(this).val();

        if(val == 'addissuedby')
        {
            $("#issueing_office_id option:selected").prop('selected', false);

            $.ajax({
                type: "GET",
                url: "/grades/addissuedby",
                success: function(data){
                    console.log(data);
                    $('#ui_content2').html(data);
                    $("#isseudby_modal").modal('show');
                }
            });
        }
    });

    $(document).on("submit",'#addissuedby_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                $('.alert').remove();

                $("#addissuedby_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#issueing_office_id option:last').before($("<option></option>").attr("value", data.issueing_office_id).text(data.code));
                    $('#addissuedby_form')[0].reset();
                }
            },
            error: function (data) {
                //console.log(data);
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
});