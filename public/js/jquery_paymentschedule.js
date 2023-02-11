//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    
    $('.datepicker').datepicker(pickerOpts);  


    $("#period").select2({
	    dropdownParent: $("#ui_content4")
	});

    $(document).on("change","#payment_mode", function(e){
		var val = $(this).val();

		if(val == 'addpaymentmode')
        {
			$("#paymentmode option:selected").prop('selected', false);
            $.ajax({url: "/paymentschedules/addpaymentmode",success: function(data){
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
		}
			e.preventDefault();
	});	

    $(document).on("submit",'#form_add_paymentmode', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                $('.alert').remove();

                $("#form_add_paymentmode").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#payment_mode option:last').before($("<option></option>").attr("value", data.mode_id).text(data.mode));
                    $('#form_add_paymentmode')[0].reset();
                    $("#payment_mode").val("").trigger('change');
                }
            },
            error: function (data) {
                //console.log(data);
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

    function returnPaymentSchedules(period)
    {
        $.ajax({
            url: "/paymentschedules/"+period+"/returnpaymentschedules",
            success: function(data){
                $("#return_paymentschedules").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action! '+errors.message);
                }
            }
        });
    }

    $(document).on("submit", "#form_payment_schedule", function(e)
    {
        var postData = $(this).serializeArray();

        $.ajax({
            url: "/paymentschedules/",
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
            success: function(response){
                $("#confirmation").dialog('close');
                console.log(response);
                if(response.success === false)
                {
                    showError(response.message);
                }else{
                    showSuccess(response.message);

                    var period = $("#period").val();
                    returnPaymentSchedules(period);
                }
            },
            error: function (data) {
                $("#confirmation").dialog('close');
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

    $(document).on("click","#cancel", function(){
        //buttons
        //$('#save_setup_fee').prop("disabled", true);
		$('#delete_selected').prop("disabled", true);
        $('.checkbox').prop("checked", false);
		$('#edit').prop("disabled", true);
        //form fields
		$('.clearable').val("").trigger('change');
		$('input.checks').prop('disabled', false).prop('checked', false);
		$('.checks').closest('tr').removeClass('selected');

        $('.save_paymentschedule_form').attr("id", "form_payment_schedule");
        $('.errors').remove();  
	});

    $(document).on("click", "#edit", function(e){
        var payment_schedule_id = $(".checks:checked").attr("id");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to edit!');	
		}else{
            $.ajax({
                url: "/paymentschedules/"+payment_schedule_id+"/edit",
                type: 'GET',
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
                success: function(response){
                    console.log(response);
                    $("#confirmation").dialog('close');
                    if(!jQuery.isEmptyObject(response)){

                        $('#delete_selected').prop("disabled", true);
                        $('#edit').prop("disabled", true);
                        $('input.checks').prop('disabled', true); 

                        $('#period').val(response.period_id).trigger('change');
                        $('#educational_level_id').val(response.educational_level_id);
                        $('#year_level').val(response.year_level);
                        $('#payment_mode').val(response.payment_mode_id);
                        $('#description').val(response.description);
                        $('#payment_type').val(response.payment_type);
                        $('#tuition').val(response.tuition);
                        $('#miscellaneous').val(response.miscellaneous);
                        $('#others').val(response.others);
                       
                        $('.save_paymentschedule_form').attr("id", "form_update_payment_schedule");
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        showError('Something went wrong! Can not perform requested action! '+errors.message);
                    }
                }
            });
        }
        e.preventDefault();
    });

    $(document).on("submit", "#form_update_payment_schedule", function(e){
        var postData = $(this).serializeArray();
        var period = $("#period").val();
        var payment_schedule_id = $(".checks:checked").attr("id");

        $.ajax({
            url: "/paymentschedules/"+payment_schedule_id,
            type: 'PUT',
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
            success: function(response){
                $("#confirmation").dialog('close');
                console.log(response);
                if(response.success === false)
                {
                    showError(response.message);
                }else{
                    showSuccess(response.message);
                }
                $('#cancel').trigger('click');
                returnPaymentSchedules(period);

            },
            error: function (data) {
                $("#confirmation").dialog('close');
                //console.log(data);
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

    $(document).on("click", "#delete_selected", function(e){
        var payment_schedule_id = $(".checks:checked").attr("id");
        var period = $("#period").val();

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to delete!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete selected schedule?<br>You can not undo this process?</div>').dialog({
                show: 'fade',
                resizable: false,	
                draggable: false,
                width: 350,
                height: 'auto',
                modal: true,
                buttons: {
                        'Cancel':function(){
                            $("#confirmation").dialog('close');
                            $('#cancel').trigger('click');
                        },
                        'OK':function(){
                            $("#confirmation").dialog('close');
                            $.ajax({
                                url: "/paymentschedules/"+payment_schedule_id,
                                type: 'DELETE',
                                dataType: 'json',
                                success: function(response)
                                {
                                    $("#confirmation").dialog('close');
                                    console.log(response);
                                    if(response.success === false)
                                    {
                                        showError(response.message);
                                    }else{
                                        showSuccess(response.message);
                                    }
                                    $('#cancel').trigger('click');
                                    returnPaymentSchedules(period);
                                },
                                error: function (data) {
                                    $("#confirmation").dialog('close');
                                    console.log(data);
                                    var errors = data.responseJSON;
                                    if ($.isEmptyObject(errors) == false) {
                                        showError('Something went wrong! Can not perform requested action! '+errors.message);
                                    }
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