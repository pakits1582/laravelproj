$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    var enrollment_outside;

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
                                        enrollment_outside = response.data;
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
                        enrollment_outside = response.data;
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
            $("#educational_level_id").val((student_info.data.values.program.level) ? student_info.data.values.program.level.id ? student_info.data.values.program.level.id : "" : "");
            $("#college").val((student_info.data.values.program.collegeinfo) ? student_info.data.values.program.collegeinfo.code ? student_info.data.values.program.collegeinfo.code : "" : "");
            $("#curriculum").val(student_info.data.values.curriculum.curriculum ? student_info.data.values.curriculum.curriculum : "");
            $("#year_level").val(student_info.data.values.year_level ? student_info.data.values.year_level : "");
            var name = student_info.data.values.last_name;
            name += ', ' + student_info.data.values.first_name;
            
            if (student_info.data.values.name_suffix !== null && student_info.data.values.name_suffix !== undefined) {
                name += ' ' + student_info.data.values.name_suffix;
            }
            
            if (student_info.data.values.middle_name !== null && student_info.data.values.middle_name !== undefined) {
                name += ' ' + student_info.data.values.middle_name;
            }
            
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

    function clearFields()
    {
        $("#feestobepayed").html('<tr id="norecord"><td colspan="5" class="mid">No fees selected</td></tr>');
        $("#total_amount, .bank_clearable").val('');
        $("#cancel_receipt").prop("checked", false).prop("disabled", false);
        $("#save_payment").prop("disabled", false);
    }

    function studentInfo(student_id, period_id)
    {
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
                    clearFields();

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
        }else{

        }
    }

    $(document).on("change", "#student", function(){
        var student_id = $("#student").val();
        var period_id = $("#period_id").val();

        studentInfo(student_id, period_id);
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
			data:  {'student_id':student_id, 'period_id':period_id, 'pay_period': pay_period, 'enrollment':enrollment_outside},
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

    function addpaymentfee()
    {
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

                $("#modalll").on('shown.bs.modal', function(){
                    $(this).find('#fee_amount').focus();
                });
            }
        });
    }

    $(document).on("click", "#add_fee", function(e){
        addpaymentfee();       

        e.preventDefault();
    });

    $(document).keyup(function (event) {
	    if (event.keyCode == 113) {
            addpaymentfee();
        }
    });

    function parseCurrency(num) {
    	return parseFloat( num.replace( /,/g, '') );
	}
    
    $(document).on("change", "#fee_id", function(){
        var fee_id = $(this).val();

        $.ajax({
            type: "GET",
            url: "/fees/"+fee_id,
            success: function(data){
                console.log(data);

                var fees_selected = '';
                if(!jQuery.isEmptyObject(data))
                {
                    $("#fee_code").val(data.fee_info.code);
                    $("#fee_name").val(data.fee_info.name);
                    $("#fee_type").val(data.fee_info.feetype.type);
                    $("#fee_amount").val(data.fee_info.default_value).focus();  

                    if(!jQuery.isEmptyObject(data.compounded_fees))
                    {
						$.each(data.compounded_fees, function(key,value) {
                            fees_selected += '<input type="hidden" name="feeselected"';
                            fees_selected += 'value="'+value.default_value+'"'; 
                            fees_selected += 'id="'+value.id+'"';
                            fees_selected += 'data-feecode="'+value.code+'"';
                            fees_selected += 'data-feedesc="'+value.name+'"';
                            fees_selected += 'data-type="'+value.feetype.type+'"'; 
                            fees_selected += 'data-inassess="'+value.feetype.inassess+'"'; 
                            fees_selected += ' data-compound="1" />';
						}); 
                    }else{
                        fees_selected += '<input type="hidden" name="feeselected"';
                        fees_selected += 'value="'+data.fee_info.default_value+'"'; 
                        fees_selected += 'id="'+data.fee_info.id+'"';
                        fees_selected += 'data-feecode="'+data.fee_info.code+'"';
                        fees_selected += 'data-feedesc="'+data.fee_info.name+'"';
                        fees_selected += 'data-type="'+data.fee_info.feetype.type+'"'; 
                        fees_selected += 'data-inassess="'+data.fee_info.feetype.inassess+'"'; 
                        fees_selected += '/>';
                    }
                }
                $("#selectedfeeshidden").html(fees_selected);
            }
        });
    });

    $(document).on("submit","#addpaymentfee_form", function(e){
        var amount = $("#fee_amount").val();

        if(parseFloat(amount) <= 0)
        {
			showError('Amount can not be zero or lower! Please check value and try again!');
		}else{
            let checkbox = $("input:checkbox.checks");
			let selectedfeeshidden = $("input[name='feeselected']");

            if((checkbox.length+selectedfeeshidden.length) > 6){
				showError('Maximum number of fees in a receipt is 6!')
			}else{
				var tablerow = '';
				if(selectedfeeshidden.length > 0)
                {
					selectedfeeshidden.each(function(){
						let feeid    = $(this).attr("id")
						//let defval = ($(this).attr("value") == '') ? amount : $(this).attr("value");
						let defval;
						if($(this).attr("data-compound") == 1){
							defval = $(this).attr("value");
						}else{
							defval = amount;
						}
						let feecode  = $(this).attr("data-feecode")
						let feedesc  = $(this).attr("data-feedesc")
						let feetype  = $(this).attr("data-type")
						let inassess = $(this).attr("data-inassess")
						//alert(feeid+'-'+defval+'-'+feecode+'-'+feedesc+'-'+feetype+'-'+inassess);
						let duplifee = 0;
						let count = 0;

						checkbox.each(function() {
						    var val = $(this).val();
						    duplifee = (feeid == val) ? 1 : 0;
						    count ++;
						});

						var checkid = (duplifee == 1) ? feeid+'_'+count : feeid;
						var num = parseFloat(defval).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");

						    tablerow += '<tr class="label" id="check_'+checkid+'">';
							tablerow += '<td class="mid"><input type="checkbox" id="'+checkid+'" value="'+feeid+'"  class="checks nomargin"/>';
							tablerow += '<input type="hidden" name="fees[]" value="'+feeid+'"/></td>';
							tablerow += '<td><input type="hidden" name="feecodes[]" value="'+feecode+'" />'+feecode+'</td>';
							tablerow += '<td>'+feedesc+'</td>';
							tablerow += '<td>'+feetype+'</td>';
							tablerow += '<td class="amount right"><input type="hidden" name="amount[]" value="'+num+'" />'+num;
							tablerow += '<input type="hidden" name="inassess[]" value="'+inassess+'" /></td>';
							tablerow += '</tr>';
					});
					/*alert(tablerow);
					console.log(tablerow);*/

					$("#norecord").remove();
					$('#feestobepayed').append(tablerow);

					var sum = 0;
					// iterate through each td based on class and add the values
					$(".amount").each(function() {
					    var value = parseCurrency($(this).text());
					    // add only if the value is number
					    if(!isNaN(value) && value.length != 0) {
					        sum += parseFloat(value);
					    }
					});

					var total = sum.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
					$("#total_amount").val(total);
					$('#addpaymentfee_form')[0].reset();
					$('#modalll').modal('toggle'); 
					$("#save_payment").focus();
				}else{
					showError('The system encountered internal problem, please refresh page and try again!');
				}
			}
        }
        e.preventDefault();
    });

    $(document).on("click","#delete_fee", function(e){
		var fee = $(".checks:checked").attr("id");
		if($(".checks:checked").length == 0){
			showError('Please select atleast one checkbox/fee to delete!');	
		}else{
			$("#check_"+fee).remove();
			var sum = 0;
			// iterate through each td based on class and add the values
			$(".amount").each(function() {
			    var value = parseCurrency($(this).text());
			    // add only if the value is number
			    if(!isNaN(value) && value.length != 0) {
			        sum += parseFloat(value);
			    }
			});
			var total = sum.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
			$("#total_amount").val(total);
			if(total == '0.00'){
				$("#feestobepayed").html('<tr id="norecord"><td colspan="5" class="mid">No fees selected</td></tr>');
			}
			$("#delete_fee").prop("disabled",true);
		}
		e.preventDefault();
	});

    $(document).on("submit", "#form_payment", function(e){
        var postData = $("#form_receipt, #form_payment").serializeArray();
        var amount = $("#total_amount").val();
        var receipt_no = $("#receipt_no").val();

        if(parseFloat(amount) <= 0 || amount == '')
        {
			showError('Amount can not be empty, zero or lower! Please check value and try again!');
		}else{
            $.ajax({
                type: "POST",
                url:  "/receipts/",
                dataType: 'json',
                data:  postData,
                cache: false, 
                beforeSend: function() {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Transaction<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
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
                    console.log(response);
                    $("#confirmation").dialog('close');

                    if(response.success == false)
                    {
                        showError(response.message);
                    }else{
                        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">'+response.message+'</div><div class="message">Print payment receipt?</div>').dialog({
							show: 'fade',
							resizable: false,	
							draggable: true,
							width: 350,
							height: 'auto',
							modal: true,
							buttons: {
									'OK':function(){
										window.open("/receipts/printreceipt/"+receipt_no, '_blank'); 
										$(this).dialog('close');
										location.reload();
									},
									'Cancel':function(){
										$(this).dialog('close');
										location.reload();				
									}	
									
								}//end of buttons
							});//end of dialogbox
						$(".ui-dialog-titlebar").hide();
                    }
                },
                error: function (data) {
                    console.log(data);
                    $("#confirmation").dialog('close');
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        showError('Something went wrong! System encountered some errors please check entries!');
                        $.each(errors.errors, function (key, value) {
                            $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                        });
                    }
                }
            });
        }

        e.preventDefault();
    });

    $(document).on("keyup","#receipt_no", function(e){
		if (e.keyCode == '13')  {
			var receipt_no = $("#receipt_no").val();

            if(receipt_no)
            {
                $.ajax({
                    type: "GET",
                    url:  "/receipts/"+receipt_no,
                    dataType: 'json',
                    cache: false, 
                    success: function(response){
                        console.log(response);
                        if(response == false)
                        {
                            location.reload();
                        }else{
                            if(response.student_id)
                            {
                                var $newOption = $("<option selected='selected'></option>").val(response.student_id).text('('+response.student_idno+') '+response.student_name);
                                $("#student").append($newOption).trigger('change');
                            }else{
                                $("#student").val("").trigger('change');
                                clearFormFields();
                            }

                            window.setTimeout(function(){
                                $("#payor_name").val(response.payor_name);
                                $("#receipt_date").val(response.receipt_date);
                                $("#period_id").val(response.period_id);
                                $("#period").val(response.period_name);

                                $("#cancel_receipt").prop("checked", (response.cancelled == 1) ? true : false);
                                $("#cancel_receipt").prop("disabled", (response.cancelled == 1) ? true : false);		

                                $("#bank_id").val(response.bank_id);
                                $("#check_no").val(response.check_no);
                                $("#deposit_date").val(response.deposit_date);

                                $("#total_amount").val(response.total_amount);

                                if(!jQuery.isEmptyObject(response.receipt_details))
                                {
                                    var tablerow = '';
                                    $.each(response.receipt_details, function(k,v) {
                                        tablerow += '<tr class="label" id="check_'+v.id+'">';
                                        tablerow += '<td class="mid"><input type="checkbox" id="'+v.id+'" value="'+v.fee_id+'"  class="checks nomargin"/>';
                                        tablerow += '<input type="hidden" name="fees[]" value="'+v.fee_id+'"/></td>';
                                        tablerow += '<td><input type="hidden" name="feecodes[]" value="'+v.fee_code+'" />'+v.fee_code+'</td>';
                                        tablerow += '<td>'+v.fee_name+'</td>';
                                        tablerow += '<td>'+v.fee_type+'</td>';
                                        tablerow += '<td class="amount right"><input type="hidden" name="amount[]" value="'+v.amount+'" />'+v.amount;
                                        tablerow += '<input type="hidden" name="inassess[]" value="'+v.inassess+'" /></td>';
                                        tablerow += '</tr>';
                                    }); 

                                    $("#norecord").remove();
                                    $('#feestobepayed').html(tablerow);
                                }

                                $("#save_payment").prop("disabled", true);
                            }, 1000);
                        }
                    } 
                });
            }else{
                location.reload();
            }
		}
	});

    $(document).on("change","#cancel_receipt", function(e){
        var receipt_no = $("#receipt_no").val();

        if($(this).is(':checked'))
        {
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Cancellation</div><div class="message">Are you sure you want to cancel receipt transaction? You can not undo this operation<p></p><input type="text" class="form-control text-uppercase" id="cancel_remark" placeholder="Cancellation Remark" /></div>').dialog({
				show: 'fade',
				resizable: false,	
				draggable: true,
				width: 350,
				height: 'auto',
				modal: true,
				buttons: {
						'Cancel':function(){
							$(this).dialog('close');
							$("#cancel_receipt").prop("checked",false);				
						},
                        'OK':function(){
							var cancel_remark = $("#cancel_remark").val();
							if(cancel_remark)
                            {
								$.ajax({
                                        type: "POST",
										url:  "/receipts/cancelreceipt",
										data:  {'receipt_no':receipt_no, 'cancel_remark':cancel_remark},
                                        dataType: 'json',
										cache: false, 
										success: function(response){
											console.log(response);
                                            if(response.success == true)
                                            {
                                                showSuccess(response.message);
                                            }

                                            window.setTimeout(function(){
                                                location.reload();
                                            }, 1500);
										} 
								});
							}else{
								showError('Cancellation remark is required!');
							}
						}	
					}//end of buttons
				});//end of dialogbox
			$(".ui-dialog-titlebar").hide();
        }
    });

    $(document).on("click", "#reprint_receipt", function(e){
        var receipt_no = $("#receipt_no").val();

        if(receipt_no)
        {
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Reprint</div><div class="message">Are you sure you want to reprint receipt transaction?</div>').dialog({
                show: 'fade',
                resizable: false,	
                draggable: true,
                width: 350,
                height: 'auto',
                modal: true,
                buttons: {
                        'Cancel':function(){
                            $(this).dialog('close');
                        },
                        'OK':function(){
                            window.open("/receipts/printreceipt/"+receipt_no, '_blank'); 
                            $(this).dialog('close');
                            location.reload();
                        }	
                    }//end of buttons
                });//end of dialogbox
            $(".ui-dialog-titlebar").hide();
        }else{
            showError('No receipt to be re printed!');
        }

        e.preventDefault();
    });

    $(document).on("submit", "#form_studentadjustment", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period_id").val();
        var educational_level_id = $("#educational_level_id").val();

        var postData = $("#form_studentadjustment").serializeArray();
        postData.push({ name: "student_id", value: student_id });
        postData.push({ name: "period_id", value: period_id });

        if(student_id && period_id)
        {
            $.ajax({
                url: "/studentadjustments",
                type: 'POST',
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        width: 350,
                        height: 'auto',
                        modal: true,
                        buttons:false
                    });
                    $(".ui-dialog-titlebar").hide();
                },
                success: function(response)
                {
                    $("#confirmation").dialog('close');
                    console.log(response);
                    if(response.data.success === true)
                    {
                        showSuccess(response.data.message);
            
                    }else{
                        showError(response.data.message);
                    }
    
                    returnStatementofaccount(student_id, period_id);
                    returnPaymentSchedule(student_id, period_id, educational_level_id, enrollment_outside);
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
        }else{
            showError('Student and period are required when adding student adjustment!');
        }

        e.preventDefault();
    });

    $(document).on("click", ".forward_balance", function(e){
        var period_id = $(this).attr("id");
        var student_id = $("#student").val();

        $.ajax({
            type: "POST",
            url: "/studentledgers/forwardbalance",
            data: ({ 'student_id':student_id, 'period_id':period_id}),
            success: function(data){
                console.log(data);
                $('#ui_content').html(data);
                $("#modalll").modal('show');
            }
        });

        e.preventDefault();
    });

    $(document).on("click", "#forward_balance", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period_id").val();

        $.ajax({
            type: "POST",
            url: "/studentledgers/forwardbalance",
            data: ({ 'student_id':student_id, 'period_id':period_id}),
            success: function(data){
                console.log(data);
                $('#ui_content').html(data);
                $("#modalll").modal('show');
            }
        });
        e.preventDefault();
    });

    $(document).on("submit", "#form_forwardbalance", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period_id").val();
        var educational_level_id = $("#educational_level_id").val();

        var postData = $("#form_forwardbalance").serializeArray();
        var checkboxperiod = $(".checks:checked");

		if(checkboxperiod.length == 0)
        {
			showError('Please select atleast one checkbox/period to forward to!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Balance Forward</div><div class="message">Are you sure you want to forward balance to selected period?<br>You can not undo this, continue? </div>').dialog({
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
								url: "/studentledgers/saveforwardedbalance",
								type: 'post',
								data: postData,
                                dataType: 'json',
								success: function(response){
									console.log(response);
                                    $('#modalll').modal('toggle'); 
                                    if(response.success == true)
                                    {
                                        showSuccess(response.message);
                            
                                    }else{
                                        showError(response.message);
                                    }
                                    returnStatementofaccount(student_id, period_id);
                                    returnPaymentSchedule(student_id, period_id, educational_level_id, enrollment_outside);
                                    returnPreviousbalancerefund(student_id, period_id);
								}
							});
						}//end of ok button	
					}//end of buttons
				});//end of dialogbox
				$(".ui-dialog-titlebar").hide();
        }

        e.preventDefault();
    });
    
});