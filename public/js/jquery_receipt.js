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

    $(document).on("change","#bank_id", function(e){
		var val = $(this).val();

		if(val == 'add_bank')
        {
			$("#bank_id option:selected").prop('selected', false);
            $.ajax({url: "/receipts/addbank",success: function(data){
                $('#ui_content').html(data);
                $("#modalll").modal('show');
            }
        });	
	   }
		e.preventDefault();
	});

    $(document).on("submit",'#addbank_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                console.log(data);
                $('.alert').remove();

                $("#addbank_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#bank_id option:last').before($("<option></option>").attr("value", data.bank_id).text(data.bank.toUpperCase()));
                    $('#addbank_form')[0].reset();
                }
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    $.each(errors.errors, function (key, value) {
                        $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                    });
                }
            }
        });	
        
        e.preventDefault();
    });

    function returnEnrollment(student_id, period_id, student_info)
    {
        if(student_id && period_id)
        {
            $.ajax({
                url: "/enrolments/"+student_id+"/"+period_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data == false)
                    {
                        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Payment</div><div class="message">Student is not enrolled this term.<br>Continue payment?</div>').dialog({
							show: 'fade',
							resizable: false,	
							draggable: true,
							width: 350,
							height: 'auto',
							modal: true,
							buttons: {
									'OK':function(){
										$(this).dialog('close');
                                        returnStudentInfo(student_id, period_id, student_info);
									},
									'Cancel':function(){
                                        $(this).dialog('close');
										$(".clearable").val("").trigger('change');
									}	
								}//end of buttons
							});//end of dialogbox
						$(".ui-dialog-titlebar").hide();
                    }else{
                        returnStudentInfo(student_id, period_id, student_info);
                    }
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

    function returnStudentInfo(student_id, period_id, response)
    {
        console.log(response);
        if(response.data.values.program.code != '')
        {
            $("#program").val(response.data.values.program.name ? response.data.values.program.name : "");
            $("#educational_level").val((response.data.values.program.level) ? response.data.values.program.level.code ? response.data.values.program.level.code : "" : "");
            $("#college").val((response.data.values.program.collegeinfo) ? response.data.values.program.collegeinfo.code ? response.data.values.program.collegeinfo.code : "" : "");
            $("#curriculum").val(response.data.values.curriculum.curriculum ? response.data.values.curriculum.curriculum : "");
            $("#year_level").val(response.data.values.year_level ? response.data.values.year_level : "");
            var name = response.data.values.last_name;
                name += ', '+response.data.values.first_name;
                name += (response.data.values.name_suffix != '') ? ' '+response.data.values.name_suffix : '';
                name += (response.data.values.middle_name != '') ? ' '+response.data.values.middle_name : '';
            $("#payor_name").val(name);

            returnStatementofaccount(student_id, period_id);
            returnPreviousbalancerefund(student_id, period_id);
            returnPaymentSchedule(student_id, period_id, response.data.values.program.level.id);
        }else{
            showError('Student has no program. Please add program first!');
            $(".clearable").val("").trigger('change');
        }
    }

    function returnStatementofaccount(student_id, period_id)
    {
        $.ajax({
            url: "/studentledgers/statementofaccounts",
            type: 'POST',
            data: ({ 'student_id':student_id, 'period_id':period_id, 'has_adjustment':false }),
            success: function(response){
                console.log(response);
                $("#return_soa").html(response);
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

    function returnPreviousbalancerefund(student_id, period_id)
    {
        $.ajax({
			url: "/studentledgers/previousbalancerefund",
            type: 'POST',
			data: {'student_id':student_id,'period_id':period_id},
			cache: false, 
			success: function(data){
				console.log(data);
				$("#return_previousbalancerefund").html(data);
				//alert(data);
			} 
		});
    }

    function returnPaymentSchedule(student_id, period_id, educational_level_id)
    {
        $.ajax({
            url: "/studentledgers/paymentschedules",
            type: 'POST',
            data: ({ 'student_id':student_id, 'period_id':period_id, 'educational_level_id':educational_level_id }),
            success: function(response){
                console.log(response);
                $("#return_soa").html(response);
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

    $(document).on("change", "#student", function(){
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

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
                        returnEnrollment(student_id, period_id, response);
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