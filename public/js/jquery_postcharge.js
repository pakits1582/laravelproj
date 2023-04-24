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

    $("#period, #program, #class, .additional_fee").select2({
        dropdownParent: $("#ui_content4")
    });

    function parseCurrency(num) {
    	return parseFloat( num.replace( /,/g, '') );
	}

	function totalfeeamount(){
		var sum = 0;
		$('.fee_amount').each(function(i, obj) {
			var value = parseCurrency($(this).val());
		    // add only if the value is number
		      if(!isNaN(value) && value.length != 0) {
		        sum += parseFloat(value);
		    }
		});
		var total = sum.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
		$("#totalfee").text(total);
	}

	$(document).on("blur keyup",".fee_amount", function(){
		totalfeeamount();
	});

    $(document).on("click", "#add_fee", function(e){
        var options = $("#additional_fees > option").clone();

        var select =    '<div class="row align-items-center mb-1 addedfeeform">';
            select +=       '<div class="col-md-1">';
            select +=       '   <label for="term" class="m-0 font-weight-bold text-primary">Fee</label>';
            select +=       '</div>';
            select +=       '<div class="col-md-7">';
            select +=       '   <select name="fees[]" class="form-control additional_fee" id="" required>';
                                    options.each(function() {
                                        var optionValue = $(this).val();
                                        var optionText = $(this).text();
                                        select += '<option value="'+optionValue+'">'+optionText+'</option>';
                                    });
            select +=       '   </select>';
            select +=       '</div>';
            select +=       '<div class="col-md-3">';
            select +=       '   <input type="text" name="amount[]" id="" class="fee_amount form-control" placeholder="Amount" pattern="^[0-9]+(?:\.[0-9]{1,2})?$" title="CDA Currency Format - no currency sign and no comma(s) - cents (.##) are optional" autocomplete="off">';
            select +=       '   <div id="error_amount" class="errors"></div>';
            select +=       '</div>';
            select +=       '<div class="col-md-1">';
            select +=       '   <a href="#" id="" class="removeaddfee btn btn-danger btn-circle btn-sm" title="Remove">';
            select +=       '       <i class="fas fa-times"></i>';
            select +=       '   </a>';
            select +=       '</div>';
            select +=   '</div>';

        $('#postcharge_fees').append(select);
        $(".additional_fee").select2();  

        e.preventDefault();
    });

    $(document).on("click", ".removeaddfee", function(e){
		$(this).closest("div.row").remove();
        totalfeeamount();
        e.preventDefault();
	});

    $(document).on("submit", "#form_filterstudent", function(e){
        var postData = $(this).serializeArray();

        $.ajax({
            url: "/postcharges/filterstudents",
            type: 'POST',
            data: postData,
            success: function(data){
                console.log(data);
                $("#return_filteredstudents").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    $("#return_filteredstudents").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');

                }
            }
        });
        e.preventDefault();
    });

    $(document).on("click",".checkstd", function(e){
        if (e.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });
   
    $(document).on("change",".students", function(){
        if($(this).is(':checked')){
            $(this).closest('tr').addClass('selected');
        }else{
            $(this).prop('checked', false);
            $(this).closest('tr').removeClass('selected');
        }
    });

    $(document).on("click", "#checkallcheckbox", function(){
		var stud = $(".students").length;
		if(stud != 0){
			if($(this).is(':checked')){
				$('.students').each(function(i, obj) {
					$(this).prop('checked',true);
					$(this).closest('tr').addClass('selected');
				});
			}else{
				$('.students').each(function(i, obj) {
					$(this).prop('checked',false);
					$(this).closest('tr').removeClass('selected');
				});
			}	
		}else{
			showError('No student/s to be selected please search student first!');
			$(this).prop("checked",false);
		}
	});

    $(document).on("submit", "#form_postcharge", function(e){
        var students = $(".students:checked").length;
        var postData = $("#form_postcharge").serializeArray();
        var period_id = $("#period").val();

        postData.push({name: 'period_id', value: period_id });

        if(students == 0)
        {
			showError('No student selected! Please select students to be charged!');
		}else{
            $("#save_postcharge").prop('disabled', true);
				
            $.ajax({
                url: "/postcharges/",
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
                   console.log(response);
                   $("#confirmation").dialog('close');

                    if(response.data.success === true)
                    {
                        showSuccess(response.data.message);
                        $('.students').each(function(i, obj) {
                            $(this).prop('checked',false);
                            $(this).closest('tr').removeClass('selected');
                        });

                        $("#checkallcheckbox").prop('checked',false);
                        $("#additional_fees, .fee_amount").val("").trigger('change');
                        $(".addedfeeform").remove();
                        $("#totalfee").text('0.00');
                        $("#save_postcharge").prop('disabled', false);
                    }else{
                        showerror(response.data.message);
                    }
                },
                error: function (data) {
                    $("#confirmation").dialog('close');
                    $("#save_postcharge").prop('disabled', false);
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        var errorText = '';
                        $.each(errors.errors, function (key, value) {
                            errorText += value;
                        });

                        showError(errorText);
                    }
                }
            });
        }
        
        e.preventDefault();
    });

    function returnChargedStudents(fee_id)
    {
        $.ajax({
            url: "/postcharges/chargedstudents",
            type: 'POST',
            data: ({ 'fee_id':fee_id }),
            success: function(data){
                console.log(data);
                $("#return_filteredstudents").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    $("#return_filteredstudents").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');

                }
            }
        });
    }

    $(document).on("change", "#fee_to_remove", function(){
        var fee_id = $("#fee_to_remove").val();

        returnChargedStudents(fee_id);
    });

    $(document).on("click", "#remove_postcharge", function(e){
        var students = $(".students:checked").length;
        var fee_id = $("#fee_to_remove").val();
        var period_id = $("#period").val();

        var postData = $("#form_postcharge").serializeArray();
        postData.push({name: 'period_id', value: period_id });

        if(fee_id)
        {
            if(students == 0)
            {
                showError('No student selected! Please select students to be removed!');
            }else{
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to remove charged students?</div>').dialog({
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
                                $("#remove_postcharge").prop('disabled', true);
				
                                $.ajax({
                                    url: "/postcharges/removecharged",
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
                                        console.log(response);
                                        $("#remove_postcharge").prop('disabled', false);
                                        $("#confirmation").dialog('close');
                                        // if(response.data.success === true)
                                        // {
                                        //     showSuccess(response.data.message);
                                        // }else{
                                        //     showerror(response.data.message);
                                        // }
                                        // returnChargedStudents(fee_id);
                                    },
                                    error: function (data) {
                                        $("#confirmation").dialog('close');
                                        $("#remove_postcharge").prop('disabled', false);
                                        console.log(data);
                                        var errors = data.responseJSON;
                                        if ($.isEmptyObject(errors) === false) {
                                            showError('Something went wrong! Can not perform requested action!');
                                        }
                                    }
                                });
                            }//end of ok button	
                        }//end of buttons
                });//end of dialogbox
                $(".ui-dialog-titlebar").hide();
            }
        }else{
            showError('Please select fee to be removed!');
        }

        e.preventDefault();
    });
});