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

    $(document).on("click", "#reassess_students", function(e){
        var postData = $("#form_filterenrolledstudents").serializeArray();

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to reassess enrollments?</div>').dialog({
			show: 'fade',
			resizable: false,	
			draggable: false,
			width: 350,
			height: 'auto',
			modal: true,
			buttons: {
					'Cancel':function(){
						$(this).dialog('close');
					},
					'OK':function(){
						$(this).dialog('close');
						$.ajax({
                            url: "/reassessments/reassess",
                            type: 'POST',
                            data: postData,
                            dataType: 'json',
                            beforeSend: function() {
                                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take some time, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                                    show: 'fade',
                                    resizable: false,	
                                    width: 350,
                                    height: 'auto',
                                    modal: true,
                                    buttons: false
                                });
                                $(".ui-dialog-titlebar").hide();
                            },
                            success: function(response){
                                $("#confirmation").dialog('close');
                
                                console.log(response);
                            },
                            error: function (data) {
                                $("#confirmation").dialog('close');
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
		//end of dialogbox
        e.preventDefault();
    });

    
});