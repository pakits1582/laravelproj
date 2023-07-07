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

    function returnUnpaidAssessments(postData)
    {
        $.ajax({
            url: "/assessments/filterunpaidassessments",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_unpaid_assessments").html(response);
                
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

    $("#form_filterunpaidassessment").submit(function(e){
        e.preventDefault();
    });

    $(document).on("change", ".filter_item", function(){
        var postData = $("#form_filterunpaidassessment").serializeArray();
        
        returnUnpaidAssessments(postData);
    });

    $(document).on("change", "#period_id", function(){
        var period_name = $("#period_id option:selected").text();
        $("#period_name").text(period_name);
    });

    $(document).on("change",".checkedunpaid", function(){
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
		}else{	
			$(this).closest('tr').removeClass('selected');
		}
	});

    $(document).on("click", "#check_all", function(){
		var enrollments = $(".checkedunpaid").length;

		if(enrollments != 0){
			if($(this).is(':checked')){
				$('.checkedunpaid').each(function(i, obj) {
					$(this).prop('checked',true);
					$(this).closest('tr').addClass('selected');
				});
			}else{
				$('.checkedunpaid').each(function(i, obj) {
					$(this).prop('checked',false);
					$(this).closest('tr').removeClass('selected');
				});
			}	
		}else{
			showError('No enrollments to be selected!');
			$(this).prop("checked",false);
		}
	});

    $(document).on("click", "#delete_selected_unpaid", function(){
        var enrollments = $(".checkedunpaid:checked").length;

		if(enrollments != 0)
        {
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Selection</div><div class="message">Are you sure you want to delete selected enrollments?</div>').dialog({
                show: 'fade',
                resizable: false,	
                draggable: false,
                width: 350,
                height: 'auto',
                modal: true,
                buttons: {
                        'Cancel':function(){
                            $("#confirmation").dialog('close');			
                        },
                        'OK':function(){
                            $("#confirmation").dialog('close');

                            var enrollment_ids = [];
                            $(".checkedunpaid:checked").each(function() {
                                enrollment_id = $(this).attr('value');
                                enrollment_ids.push(enrollment_id);
                            });

                            $.ajax({
                                url: "/assessments/deleteunpaidassessments",
                                type: 'DELETE',
                                data: ({"enrollment_ids": enrollment_ids}),
                                success: function(response){
                                    console.log(response);
                                    if(response.success == false)
                                    {
                                        showError(response.message);
                                        $('.checkedunpaid').each(function(i, obj) {
                                            $(this).prop('checked',false);
                                            $(this).closest('tr').removeClass('selected');
                                        });
                                    }else{
                                        showSuccess(response.message);
                                        var postData = $("#form_filterunpaidassessment").serializeArray();
        
                                        returnUnpaidAssessments(postData);
                                    }
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
                        }//end of ok button	
                    }//end of buttons
            });//end of dialogbox
            $(".ui-dialog-titlebar").hide();
		}else{
			showError('Please select at least one enrollment to be deleted!');
		}
    });

    $(document).on("click", ".view_classes_unpaid", function(e){
        e.stopPropagation();
        var enrollment_id = $(this).attr("id");

        $.ajax({
            url: "/enrolments/"+enrollment_id+"/viewclassesenrolled",
            type: 'GET',
            success: function(data){   
                console.log(data);  
                if(data.success == false)
                {
                    showError(data.message);
                }else{
                    $('#modal_container').html(data);
                    $("#view_classes_modal").modal('show');
                }          
            },
            error: function (data) {
                console.log(data);
            }
        });

        e.preventDefault();
    });
});