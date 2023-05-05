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

    function clearFormFields()
    {
        $(".clearable").val("").trigger('change');
        $("#return_soa, #return_previousbalancerefund, #payment_schedule").html('<h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>');
    }

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
                                        returnStudentInfo(student_id, period_id, student_info, response.data);
									},
									'Cancel':function(){
                                        $(this).dialog('close');
										clearFormFields();
									}	
								}//end of buttons
							});//end of dialogbox
						$(".ui-dialog-titlebar").hide();
                    }else{
                        returnStudentInfo(student_id, period_id, student_info, response.data);
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearFormFields()
                    }
                }
            });
        }
    }

    function returnStudentInfo(student_id, period_id, student_info, enrollment)
    {
        console.log(student_info);
        if(student_info.data.values.program.code != '')
        {
            $("#program").val(student_info.data.values.program.name ? student_info.data.values.program.name : "");
            $("#educational_level").val((student_info.data.values.program.level) ? student_info.data.values.program.level.code ? student_info.data.values.program.level.code : "" : "");
            $("#college").val((student_info.data.values.program.collegeinfo) ? student_info.data.values.program.collegeinfo.code ? student_info.data.values.program.collegeinfo.code : "" : "");
            $("#curriculum").val(student_info.data.values.curriculum.curriculum ? student_info.data.values.curriculum.curriculum : "");
            $("#year_level").val(student_info.data.values.year_level ? student_info.data.values.year_level : "");
            var name = student_info.data.values.last_name;
                name += ', '+student_info.data.values.first_name;
                name += (student_info.data.values.name_suffix != '') ? ' '+student_info.data.values.name_suffix : '';
                name += (student_info.data.values.middle_name != '') ? ' '+student_info.data.values.middle_name : '';
            $("#payor_name").val(name);

            returnStatementofaccount(student_id, period_id);
            returnPreviousbalancerefund(student_id, period_id);
            returnPaymentSchedule(student_id, period_id, student_info.data.values.program.level.id, enrollment);
        }else{
            showError('Student has no program. Please add program first!');
            clearFormFields();
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
                    showError('Something went wrong! Can not return statement of account!');
                    clearFormFields();
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

    function returnPaymentSchedule(student_id, period_id, educational_level_id, enrollment)
    {
        $.ajax({
            url: "/studentledgers/paymentschedules",
            type: 'POST',
            data: ({ 'student_id':student_id, 'period_id':period_id, 'educational_level_id':educational_level_id, 'enrollment':enrollment }),
            success: function(response){
                console.log(response);
                $("#payment_schedule").html(response);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not return payment schedule!');
                    clearFormFields();
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
                    if(response.data === false)
                    {
                        showError('Student not found!');
                        clearFormFields();
                    }else{
                        returnEnrollment(student_id, period_id, response);
                    }
                },
                error: function (data) {
                    $("#confirmation").dialog('close');
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearFormFields();
                    }
                }
            });
        }
    });

    $(document).on("click", "#pay_period_default", function(){
        var pay_period = $("#pay_period").val();

        if($(this).is(':checked')){
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to make pay period as default?</div>').dialog({
                show: 'fade',
                resizable: false,	
                draggable: true,
                width: 350,
                height: 'auto',
                modal: true,
                buttons: {
                        'OK':function(){
                            $(this).dialog('close');
                            $.ajax({
                                url: "/studentledgers/defaultpayperiod",
                                type: 'POST',
                                data: {'pay_period': pay_period},
                                dataType: 'json',
                                success: function(response){
                                    console.log(response);
                                    if(response.data.success === true)
                                    {
                                        showSuccess(response.data.message);
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
                        },
                        'Cancel':function(){
                            $(this).dialog('close');
                            $("#pay_period").prop("checked",false);			
                        }	
                    }//end of buttons
                });//end of dialogbox
            $(".ui-dialog-titlebar").hide();
        }
    });

    $(document).on("change", "#pay_period", function(){
        var pay_period = $(this).val();
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

        $.ajax({
			type: "POST",
			url:  "/studentledgers/computepaymentsched",
			dataType: 'json',
			data:  {'student_id':student_id, 'period_id':period_id, 'pay_period': pay_period},
			cache: false, 
			success: function(response){
				console.log(response);
                var optionText = $("#pay_period option:selected").text();
                
                if(pay_period != response.default_pay_period){
                    $("#pay_period_default").prop("checked", false);
                }else{
                    $("#pay_period_default").prop("checked", true);
                }

                $("#pay_period_text").html(optionText);
                $("#balance_due").html(response.balance_due);
			} 
		});
    });

    $(document).on("click", "#add_fee", function(e){
        $.ajax({
            type: "GET",
            url: "/receipts/create",
            success: function(data){
                console.log(data);
                $('#ui_content').html(data);
                $("#modalll").modal('show');

                $("#fee_id").select2({
                    dropdownParent: $("#modalll")
                });
            }
        });

        e.preventDefault();
    });

});