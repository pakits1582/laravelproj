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

        if(student_id){
            $.ajax({
                url: "/grades/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data)
                    {
                        $("#curriculum").val(response.data.curriculum);
                        $("#year_level").val(response.data.year_level);            
                        $("#grade_id").val(response.data.grade_id);
                        $("#educational_level").val(response.data.level);
                        $("#college").val(response.data.college);
                        $("#program").val(response.data.program);
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
});