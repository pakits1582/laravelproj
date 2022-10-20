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

    function enrollmetInfo(student_id)
    {
        $.ajax({
            url: "/enrolments/enrolmentinfo ",
            type: 'POST',
            dataType: 'json',
            data: ({ 'student_id' : student_id }),
            success: function(response){
                console.log(response);
                
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
                        enrollmetInfo(student_id);
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
        var program_id = $(this).val();

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
        var year_level = $(this).val();

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