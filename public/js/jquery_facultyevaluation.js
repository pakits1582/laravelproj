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

    $('#scrollable_table_respondents').DataTable({
        scrollY: 200,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        "bAutoWidth": false,
        ordering: false,
        info: false,
        searching: false
    });

	var dataTable = $('.scroll_table').DataTable({
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
        var field = $("#instructor_id").attr("data-field");

        $.ajax({
            url: "/facultyevaluations/filter",
            type: 'POST',
            data: {'instructor_id':instructor_id, 'field':field},
            success: function(data){

                //console.log(data);

                $("#return_"+field).html(data);

                var dataTable = $('.scroll_table').DataTable({
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
                                //console.log(response);
                                if(response.success == true)
                                {
                                    showSuccess(response.message);
                                    returnFacultyEvaluations()

                                }else{
                                    showError(response.message);
                                }

                                alert('return results');
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

    $(document).on("change", ".radio_choices", function(e){
        if ($(this).is(":checked")) {
            var id = $(this).attr("data-id");
            // Remove the hidden input with the same name
            $('input[type="hidden"][name="' + $(this).attr("name") + '"]').remove();
            $("#error_choice_"+id).html('');
        }
    });

    $(document).on("submit", "#form_evaluateclass", function(e){
        var postData = $(this).serializeArray();

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to submit evaluation?</div>').dialog({
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
                            url: "/facultyevaluations/saveevaluation",
                            type: 'POST',
                            data: postData,
                            dataType: 'json',
                            beforeSend: function() {
                                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                                    show: 'fade',
                                    resizable: false,	
                                    width: 'auto',
                                    height: 'auto',
                                    modal: true,
                                    buttons: false
                                });
                                $(".ui-dialog-titlebar").hide();
                            },
                            success: function(response)
                            {
                                $(".errors").html('');
                                $("#confirmation").dialog('close');

                                console.log(response);
                                if(response.success == true)
                                {
                                    showSuccess(response.message);
                                    window.setTimeout(function(){
                                        location.href = "/facultyevaluations/studentfacultyevaluation";		
                                    }, 1000);   
                                }else{
                                    showError(response.message);
                                }

                            },
                            error: function (data) {
                                $("#confirmation").dialog('close');
                                $(".errors").html('');
                                console.log(data);
                                var errors = data.responseJSON;
                                if ($.isEmptyObject(errors) == false) {
                                    showError('Something went wrong! Please check entries!');
                                    $.each(errors.errors, function (key, value) {
                                        $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');

                                        var questionId = key.split('.')[1];
                                        $('#error_choice_' + questionId).html('<p class="text-danger text-xs mt-1">' + value + '</p>');
                            
                                    });
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
});
