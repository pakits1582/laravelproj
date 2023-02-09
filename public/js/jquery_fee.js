//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    
    $('.datepicker').datepicker(pickerOpts);  

    $("#subject").select2({
	    // dropdownParent: $("#ui_content3"),
        allowClear: true,
        placeholder: "- search subject -",
        minimumInputLength: 2,
        tags: false,
        minimumResultsForSearch: 20, // at least 20 results must be displayed
        ajax: {
            url: '/subjects/dropdownselectsearch',
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

    $("#program").select2({
	    dropdownParent: $("#ui_content2")
	});

    $("#fee").select2({
	    dropdownParent: $("#ui_content3")
	});

    $("#period").select2({
	    dropdownParent: $("#ui_content4")
	});



/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("change","#fee_type", function(e){
		let val = $(this).val();

		if(val == 'addnewtype'){
			$("#fee_type option:selected").prop('selected', false);
            $.ajax({url: "/fees/addnewtype",success: function(data){
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
	    }
	    e.preventDefault();
	});

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("submit",'#addtype_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                // console.log(data);
                $('.alert').remove();

                $("#addtype_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#fee_type option:last').before($("<option></option>").attr("value", data.values.id).text(data.values.type));
                    $('#addtype_form')[0].reset();
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

    $(document).on("click","#iscompound", function(){
		if($(this).is(":checked")){
            $.ajax({url: "/fees/compoundfee",success: function(data){
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');

                    $("#non_assess_fees").select2({
                        dropdownParent: $("#modalll")
                    });
                }
            });	
            $("#name").prop('readonly', true)
        }else{
            $("#name").prop('readonly', false).val('');
        }
    });

    $(document).on("click","#add_fee", function(){
		let fee = $("#non_assess_fees").val();
		let feecode = $("#non_assess_fees option:selected").text();
		let compoundedfee = $("#compoundedfees").val();
		let fee_array = feecode.split(' - ');

		let compoundedfees = (!compoundedfee) ? fee_array[0] : '=$'+fee_array[0];

		$("#compoundedfees").append(compoundedfees);
	});

    $(document).on("click","#save_compounded_fee", function(){
		let compfees = $("#compoundedfees").val();
		if(compfees){
			let compfee = compfees.split('=$');
			if(compfee.length <= 1){
				showError('Please select at least two fees to compound!');
			}else{
				$("#name").val(compfees);
				$("#modalll").modal('hide');
			}
		}else{
			showError('Please select fees to be compounded!');
		}
		
	});

    // $(document).on("click", "#download_excel", function(e)
    // {
    //     $("#filter_form").attr("action","/programs/export");
	// 	$("#filter_form").submit();

    //     e.preventDefault();
    // });

    // $(document).on("click", "#generate_pdf", function(e)
    // {
    //     $("#filter_form").attr("action","/programs/generatepdf");
	// 	$("#filter_form").submit();

    //     e.preventDefault();
    // });

    $(document).on("change",".copyfees_checkbox", function(){
		if($(this).is(':checked')){
			 $(this).closest('tr').addClass('selected');
		}else{
			$(this).prop('checked', false);
			$(this).closest('tr').removeClass('selected');
		}
	});

    function returnSetupFees(period, selectall)
    {
        $.ajax({
            url: "/fees/"+period+"/returnfeessetup",
            data: ({ 'selectall' : selectall}),
            success: function(data){
                $("#return_setup_fees").html(data);
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

    $(document).on("submit", "#form_setup_fee", function(e){
        var postData = $(this).serializeArray();
        var period = $("#period").val();

        $.ajax({
            url: "/fees/savesetupfee",
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
            success: function(data){
                $("#confirmation").dialog('close');
                showSuccess('Fee successfully added!');
                $("#cancel").trigger("click");
                returnSetupFees(period, 0);
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

        $('.save_setupfee_form').attr("id", "form_setup_fee");
        $('.errors').remove();  
	});

    $(document).on("click", "#edit", function(e){
        var setupfee_id = $(".checks:checked").attr("data-setupfeeid");

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to edit!');	
		}else{
            $.ajax({
                url: "/fees/"+setupfee_id+"/editsetupfee",
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

                        $('#period').val(response.data.period_id).trigger('change');
                        $('#educational_level_id').val(response.data.educational_level_id);
                        $('#college_id').val(response.data.college_id);

                        if(response.data.subject_id !== null)
                        {
                            var $newOption = $("<option selected='selected'></option>").val(response.data.subject.id).text('('+response.data.subject.units+') - ['+response.data.subject.code+'] '+response.data.subject.name);
					        $("#subject").append($newOption).trigger('change');
                        }
                        $('#program').val(response.data.program_id).trigger('change');
                        $('#year_level').val(response.data.year_level);
                        $('#sex').val(response.data.sex);
                        $('#fee').val(response.data.fee_id).trigger('change');
                        $('#rate').val(response.data.rate);
                        $('#payment_scheme').val(response.data.payment_scheme);
                        //$('#instructor').val(response.data.instructor_id).trigger('change');
                        if (response.data.new === 1){ $('#new').prop('checked', true) }
                        if (response.data.old === 1){ $('#old').prop('checked', true) }
                        if (response.data.returnee === 1){ $('#returnee').prop('checked', true) }
                        if (response.data.transferee === 1){ $('#transferee').prop('checked', true) }
                        if (response.data.cross === 1){ $('#cross').prop('checked', true) }
                        if (response.data.foreigner === 1){ $('#foreigner').prop('checked', true) }
                        if (response.data.professional === 1){ $('#professional').prop('checked', true) }

                        $('.save_setupfee_form').attr("id", "form_update_setup_fee");


                    }else{
                        showError('Oppss! Something went wrong! Can not fetch fee data!');
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

    $(document).on("submit", "#form_update_setup_fee", function(e){
        var postData = $(this).serializeArray();
        var period = $("#period").val();
        var setupfee_id = $(".checks:checked").attr("data-setupfeeid");

        //postData.push({ name: "setupfee_id", value: setupfee_id });

        $.ajax({
            url: "/fees/"+setupfee_id+"/updatesetupfee",
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
                if(response.data.success === false)
                {
                    showError(response.data.message);
                }else{
                    showSuccess(response.data.message);
                }
                $('#cancel').trigger('click');
                returnSetupFees(period, 0);
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

    $(document).on("click", "#delete_selected", function(e){
        var setupfee_id = $(".checks:checked").attr("data-setupfeeid");
        var period = $("#period").val();

		if($(".checks:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to delete!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">All items related to this class subject will also be deleted. Do you want to continue?<br>You can not undo this process?</div>').dialog({
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
                                url: "/fees/"+setupfee_id+"/delete",
                                type: 'DELETE',
                                dataType: 'json',
                                success: function(response)
                                {
                                    console.log(response);
                                    if(response.data.success === false)
                                    {
                                        showError(response.data.message);
                                    }else{
                                        showSuccess('Setup Fee Successfully Deleted!');
                                    }

                                    $('#cancel').trigger('click');
                                    returnSetupFees(period, 0);
                                },
                                error: function (data) {
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

    $(document).on("click", "#copy_setup", function(e){
        var period = $("#period").val();

        if(period){
            $.ajax({url: "/fees/"+period+"/copysetup",success: function(data){
                    //console.log(data);
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');

                    $("#period_copyfrom").select2({
                        dropdownParent: $("#modalll")
                    });
                }
            });	
        }else{
            showError('Please select period first!');
        }
        e.preventDefault();
    });

    $(document).on("change", "#period_copyfrom", function(e){
        var period_copyfrom = $(this).val();

        if(period_copyfrom)
        {
            $.ajax({
                url: "/fees/"+period_copyfrom+"/returnfeessetup",
                data: ({ 'selectall' : 1}),
                success: function(data){
                    $("#return_copy_setup_fees").html(data);
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

    $(document).on("click", "#check_all", function(){
		var checks = $(".copyfees_checkbox").length;
		if(checks != 0){
			if($(this).is(':checked')){
				$('.copyfees_checkbox').each(function(i, obj) {
					$(this).prop('checked',true);
					$(this).closest('tr').addClass('selected');
				});
			}else{
				$('.copyfees_checkbox').each(function(i, obj) {
					$(this).prop('checked',false);
					$(this).closest('tr').removeClass('selected');
				});
			}	
		}else{
			showError('No fees to be selected please select period first!');
			$(this).prop("checked",false);
		}
	});

    $(document).on("submit", "#form_copyclass", function(e){
        var period = $("#period").val();
		var postData = $(this).serializeArray();
        var checks = $(".copyfees_checkbox:checked").length;

		if(checks != 0)
        {
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Action</div><div class="message">Are you sure you want to copy selected fee?</div>').dialog({
				show: 'fade',
				resizable: false,	
				draggable: false,
				width: 350,
				height: 'auto',
				modal: true,
				buttons: {
                    'Cancel':function(){
                        $(this).dialog('close');
                        $('.copyfees_checkbox').closest('tr').removeClass('selected');
                        $('input.copyfees_checkbox').prop('checked', false);						
                    },
                    'OK':function(){
                        $(this).dialog('close');
                        $.ajax({
                            url: "/fees/savecopyfees",
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
                                console.log(response);
                                $("#confirmation").dialog('close');
                                if(response.data.success === false)
                                {
                                    showError(response.data.message);
                                    $('.copyfees_checkbox').closest('tr').removeClass('selected');
                                    $('input.copyfees_checkbox').prop('checked', false);						
            
                                }else{
                                    showSuccess(response.data.message);
                                    $('.copyfees_checkbox:checked').closest('tr').remove();
                                }
                                $("#check_all").prop("checked", false);
                                $('#cancel').trigger('click');
                                returnSetupFees(period, 0);
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
                    }	
                }//end of buttons
            });//end of dialogbox
            $(".ui-dialog-titlebar").hide();
        }else{
            showError('Please select atleast one fee to copy!');	
        }
        e.preventDefault();
    });
});