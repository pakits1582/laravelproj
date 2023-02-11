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
});