$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    function returnEnrollmentSummary(postData)
    {
        $.ajax({
            url: "/enrolmentsummary/filtersummary",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_enrollmentsummary").html(response);
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

    $(document).on("change", ".filter_item", function(){
        var postData = $("#form_filtersummary").serializeArray();
        
        //console.log(postData);
        returnEnrollmentSummary(postData);
    })
});