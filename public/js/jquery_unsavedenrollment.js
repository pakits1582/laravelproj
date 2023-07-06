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

    function returnUnsavedEnrollments(postData)
    {
        $.ajax({
            url: "/enrolments/filterunsavedenrollments",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_unsaved_enrollment").html(response);
                
                $('#scrollable_table').DataTable({
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false
                });

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

    $("#form_filterunsavedenrollment").submit(function(e){
        e.preventDefault();
    });

    $(document).on("change", ".filter_item", function(){
        var postData = $("#form_filterunsavedenrollment").serializeArray();
        
        returnUnsavedEnrollments(postData);
    });

    $(document).on("change", "#period_id", function(){
        var period_name = $("#period_id option:selected").text();
        $("#period_name").text(period_name);
    });

    $(document).on("change",".checkedunsaved", function(){
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
		}else{	
			$(this).closest('tr').removeClass('selected');
		}
	});

    $(document).on("click", "#check_all", function(){
		var enrollments = $(".checkedunsaved").length;
		if(enrollments != 0){
			if($(this).is(':checked')){
				$('.checkedunsaved').each(function(i, obj) {
					$(this).prop('checked',true);
					$(this).closest('tr').addClass('selected');
				});
			}else{
				$('.checkedunsaved').each(function(i, obj) {
					$(this).prop('checked',false);
					$(this).closest('tr').removeClass('selected');
				});
			}	
		}else{
			showError('No enrollments to be selected!');
			$(this).prop("checked",false);
		}
	});

});