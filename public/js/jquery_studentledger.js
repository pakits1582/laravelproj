$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#student").select2({
	    // dropdownParent: $("#ui_content3"),
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
        dropdownParent: $("#ui_content4")
    });

    function returnStudentledger(student_id, period_id)
    {
        if(student_id){
            $.ajax({
                url: "/studentledgers/statementofaccounts",
                type: 'POST',
                data: ({ 'student_id':student_id, 'period_id':period_id }),
                success: function(data){
                    console.log(data);
                    $("#return_studentledger").html(data);
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearForm()
                    }
                }
            });
        }
    }

    function clearForm()
    {

    }

    $(document).on("change", "#period", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period").val();

        var period_name = (period_id) ? $("#period option:selected").text() : 'All Periods';
        $("#period_name").text(period_name);

        returnStudentledger(student_id, period_id);

    });

    $(document).on("change", "#student", function(){
        var student_id = $("#student").val();
        var period_id = $("#period").val();

        if(student_id){
            $.ajax({
                url: "/students/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data === false)
                    {
                        showError('Student not found!');
                        clearForm();
                    }else{
                        $("#program").val(response.data.values.program.name);
                        $("#educational_level").val(response.data.values.program.level.code);
                        $("#college").val(response.data.values.program.collegeinfo.code);
                        $("#curriculum").val(response.data.values.curriculum.curriculum);
                        $("#year_level").val(response.data.values.year_level);
                        
                        returnStudentledger(student_id, period_id);
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

    });
});