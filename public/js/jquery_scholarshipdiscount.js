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

    $("#scholarshipdiscount").select2({
        dropdownParent: $("#ui_content4")
    });

    function returnScholarshipdiscountGrants(student_id, period_id)
    {
        if(student_id){
            $.ajax({
                url: "/scholarshipdiscounts/scholarshipdiscountgrants",
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

    $(document).on("change", "#student", function(e){
        var student_id = $(this).val();
        var period_id  = $("#period").val();

        e.preventDefault();
    });
});