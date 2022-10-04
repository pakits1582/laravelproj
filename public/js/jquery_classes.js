$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#instructor, #program_id").select2({
	    dropdownParent: $("#ui_content3")
	});

    $("#program_id").select2({
	    dropdownParent: $("#ui_content2")
	});

    $(document).on("change", "#year_level", function(e){
        var program_id = $("#program_id").val();
        var year_level = $("#year_level").val();

        if(program_id && year_level)
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
				//console.log(data);
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
        }
        e.preventDefault();
    });
});