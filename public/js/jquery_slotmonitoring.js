$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


    $("#program").select2({
	    dropdownParent: $("#ui_content2")
	});

    function returnSectionMonitoring(program_id)
    {
        $.ajax({
            url: "/sectionmonitoring/filtersectionmonitoring",
            type: 'POST',
            data: ({ "program_id": program_id}),
            success: function(response){
                console.log(response);
                $("#return_slotmonitoring").html(response);
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

    $(document).on("change", "#program", function(){
        var program_id = $("#program").val();
        
        returnSectionMonitoring(program_id);
    });

    /**********************************************
*** FUNCTION INLINE UPDATE FOR MIN ENROLLEE ***
**********************************************/	
$(document).on("keypress",".minimum_enrollee", function(e){
    if (e.which === 13 && e.shiftKey === false || e.which === 32){
        var td = $(this);
		var sectionmonitoring_id = $(this).attr("id");
		var original_value = $(this).attr("data-value");
		var minimum_enrollee = $(this).text(); 

		if(minimum_enrollee != original_value){
			$.ajax({
				type: "PUT",
				url: "/sectionmonitoring/"+sectionmonitoring_id,
				data: ({"minimum_enrollees":minimum_enrollee}),
				cache: false,
                dataType: 'json',
				success: function(response){
					if(response.success == true)
                    {
                        showSuccess(response.message);            
                    }else{
                        showError(response.message);
                        td.text(original_value);
                    }

                    var program_id = $("#program").val();
                    returnSectionMonitoring(program_id);
    
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
    	return false;
    }
});

/***********************************************
*** FUNCTION INLINE UPDATE FOR ALLOWED UNITS ***
***********************************************/	
$(document).on("key",".sectionallowedunits", function(e){
	if (e.which == 13){
		var id      = $(this).attr("id");
		var origval = $(this).attr("content");
		var val     = $(this).text(); 

		if(val != origval){
			$.ajax({
				type: "POST",
				url: baseUrl+"/section/updateallowedunits",
				data: ({"id":id,"val":val}),
				cache: false,
				success: function(data){
					if(data == 1){
						showSuccess('Allowed units in section updated successfully.');	
						$(".sectionallowedunits").blur();
					}else{
						showError(data);
					}
				} 
			});
		}
    	
        e.preventDefault();
    }
});

/********************************************
*** FUNCTION VIEW ENROLLED IN THE SECTION ***
********************************************/	
$(document).on("click", ".viewenrolledinsection", function(e){
	var section = $(this).attr("id");

	$.ajax({
		url: baseUrl+"/section/displayenrolled/",
		type: 'post',
		data: {"section":section},
		success: function(data){
			$("#ui_content").html(data).dialog({
				show: 'fade',
				width: xWidth,
				height: dHeight,
				resizable: false,	
				draggable: false,
				modal: true
			});//end of dialogbox
			$(".ui-dialog-titlebar").hide();
		}
	});	
	e.preventDefault();
});

/***********************************
*** FUNCTION MANUAL OPEN SECTION ***
***********************************/	
$(document).on("click", ".opensection", function(e){
	var id = $(this).attr("id");

	$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Opening Section</div><div class="message">Are yous sure you want to open section?</div>').dialog({
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
				//insert subject
				$.ajax({
					url: baseUrl+"/section/opensection",
					type: 'post',
					data: {"id":id},
					success: function(data){
						if(data == 1){
							showSuccess('Section successfully opened.');
							window.setTimeout(function(){
								location.reload();
							}, 800);
						}else{
							showError(data);
						}
					}
				});	
				//END OF INSERT SUBJECT
				}	
			}//end of buttons
		});//end of dialogbox
	$(".ui-dialog-titlebar").hide();
	//end of dialogbox
	e.preventDefault();
});

$(document).on("click", ".closesection", function(e){
	var id = $(this).attr("id");

	$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Closing Section</div><div class="message">Are yous sure you want to close section?</div>').dialog({
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
				//insert subject
				$.ajax({
					url: baseUrl+"/section/closesection",
					type: 'post',
					data: {"id":id},
					success: function(data){
						if(data == 1){
							showSuccess('Section successfully closed.');
							window.setTimeout(function(){
								location.reload();
							}, 800);
						}else{
							showError(data);
						}
					}
				});	
				//END OF INSERT SUBJECT
				}	
			}//end of buttons
		});//end of dialogbox
	$(".ui-dialog-titlebar").hide();
	//end of dialogbox
	e.preventDefault();
});
});