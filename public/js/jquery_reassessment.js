$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    
    $("#period_id, #program").select2({
	    dropdownParent: $("#ui_content4")
	});

    var dataTable = $('#scrollable_table').DataTable({
        scrollY: 400,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false
    });

    function returnEnrolledStudents(postData)
    {
        $.ajax({
            url: "/reassessments/filterenrolled",
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log(response);
                //$("#return_unpaid_assessments").html(response);
                $("#enrolled_students").html(response.enrolled_count);
            },
            error: function (data) {
                //console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm()
                }
            }
        });
    }

    $("#form_filterenrolledstudents").submit(function(e){
        e.preventDefault();
    });

    $(document).on("change", ".filter_item", function(){
        var postData = $("#form_filterenrolledstudents").serializeArray();
        
        returnEnrolledStudents(postData);
    });

    $(document).on("change", "#period_id", function(){
        var period_name = $("#period_id option:selected").text();
        $("#period_name").text(period_name);
    });

    
});