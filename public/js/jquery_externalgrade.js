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

    $(document).on("submit", "#form_externalgrade", function(e){
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        var student_id = $("#student").val();
        var period_id = $("#period").val();
        var grade_id = $("#grade_id").val();

        postData.push(
            {name: 'student_id', value: student_id },
            {name: 'period_id', value: period_id },
            {name: 'grade_id', value: grade_id }
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
                    //$("#").prepend('<p class="alert '++'">'++'</p>')
                    if(response.data.success){
                        $(".clearable").val('');
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
});