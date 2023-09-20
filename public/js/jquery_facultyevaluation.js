//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#period_id, #instructor_id").select2({
	    dropdownParent: $("#ui_content4")
	});

	var dataTable = $('#scrollable_table_faculty_evaluations').DataTable({
        scrollY: 400,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false
    });

    function returnFacultyEvaluations()
    {
        var instructor_id = $("#instructor_id").val();

        $.ajax({
            url: "/facultyevaluations/filter",
            type: 'POST',
            data: {'instructor_id':instructor_id},
            success: function(data){

                console.log(data);

                $("#return_evaluations").html(data);

                var dataTable = $('#scrollable_table_faculty_evaluations').DataTable({
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
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });
    }

    $(document).on("click", ".evaluation_action", function(e){
        var class_id = $(this).attr("id"); 
        var action   = $(this).attr('data-action');

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to continue action?</div>').dialog({
            show: 'fade',
            resizable: false,	
            draggable: false,
            width: 350,
            height: 'auto',
            modal: true,
            buttons: {
                    'OK':function(){
                        $("#confirmation").dialog('close');
                        $.ajax({
                            url: "/facultyevaluations/"+class_id+"/"+action,
                            type: 'GET',
                            dataType: 'json',
                            success: function(response)
                            {
                                console.log(response);
                                if(response.success == true)
                                {
                                    showSuccess(response.message);
                                    returnFacultyEvaluations()

                                }else{
                                    showError(response.message);
                                }
                            },
                            error: function (data) {
                                console.log(data);
                                var errors = data.responseJSON;
                                if ($.isEmptyObject(errors) == false) {
                                    showError('Something went wrong! Can not perform requested action!');
                                }
                            }
                        });
                    },
                    'Cancel':function(){
                        $("#confirmation").dialog('close');		
                    }
                }//end of buttons
        });//end of dialogbox
        $(".ui-dialog-titlebar").hide();

        e.preventDefault();
    });
	
    $(document).on("change", "#instructor_id", function(){
        returnFacultyEvaluations();
    });

    $(document).on("click", ".reset_evaluation", function(e){
        var class_id = $(this).attr("id"); 

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to continue action?</div>').dialog({
            show: 'fade',
            resizable: false,	
            draggable: false,
            width: 350,
            height: 'auto',
            modal: true,
            buttons: {
                    'OK':function(){
                        $("#confirmation").dialog('close');
                        $.ajax({
                            url: "/facultyevaluations/"+class_id+"/resetevaluation",
                            type: 'GET',
                            dataType: 'json',
                            success: function(response)
                            {
                                console.log(response);
                                // if(response.success == true)
                                // {
                                //     showSuccess(response.message);
                                //     returnFacultyEvaluations()

                                // }else{
                                //     showError(response.message);
                                // }
                            },
                            error: function (data) {
                                console.log(data);
                                var errors = data.responseJSON;
                                if ($.isEmptyObject(errors) == false) {
                                    showError('Something went wrong! Can not perform requested action!');
                                }
                            }
                        });
                    },
                    'Cancel':function(){
                        $("#confirmation").dialog('close');		
                    }
                }//end of buttons
        });//end of dialogbox
        $(".ui-dialog-titlebar").hide();

        e.preventDefault();
    });

    $(document).on("click", ".view_respondents", function(e){
        var class_id = $(this).attr("id"); 

        $.ajax({
            url: "/facultyevaluations/"+class_id+"/viewrespondents",
            type: 'GET',
            success: function(data)
            {
                $('#ui_content').html(data);
                $("#viewRespondentsModal").modal('show');
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });
                   
        e.preventDefault();
    });

});