$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});


    $("#program").select2({
	    dropdownParent: $("#ui_content2")
	});

	$("#period_id").select2({
	    dropdownParent: $("#ui_content2")
	});

	$(document).on("change", "#period_id", function(){
		var program_id  = $("#program").val();
        var period_id   = $("#period_id").val();
        var period_name = $("#period_id option:selected").text();
        $("#period_name").text(period_name);

        returnSectionMonitoring(period_id, program_id);
    });


    function returnSectionMonitoring(period_id, program_id)
    {
        $.ajax({
            url: "/sectionmonitoring/filtersectionmonitoring",
            type: 'POST',
            data: ({ "period_id": period_id, "program_id": program_id }),
            success: function(response){
                //console.log(response);
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
        var period_id  =  $('#period_id').val();

        returnSectionMonitoring(period_id, program_id);
    });

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
						var period_id  =  $('#period_id').val();
						returnSectionMonitoring(period_id, program_id);
		
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

	$(document).on("keypress",".allowed_units", function(e){
		if (e.which === 13 && e.shiftKey === false || e.which === 32){
			var td = $(this);
			var sectionmonitoring_id = $(this).attr("id");
			var original_value = $(this).attr("data-value");
			var allowed_units = $(this).text(); 

			if(allowed_units != original_value){
				$.ajax({
					type: "PUT",
					url: "/sectionmonitoring/"+sectionmonitoring_id,
					data: ({"allowed_units":allowed_units}),
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
						var period_id  =  $('#period_id').val();
						returnSectionMonitoring(period_id, program_id);
		
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

	$(document).on("click", ".open_section", function(e){
		var sectionmonitoring_id = $(this).attr("id");

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

					$.ajax({
						type: 'PUT',
						url: "/sectionmonitoring/"+sectionmonitoring_id,
						data: ({ "status":1 }),
						cache: false,
						dataType: 'json',
						success: function(response){
							if(response.success == true)
							{
								showSuccess(response.message);            
							}else{
								showError(response.message);
							}
	
							var program_id = $("#program").val();
							var period_id  =  $('#period_id').val();
							returnSectionMonitoring(period_id, program_id);
						},
					});	
					}	
				}//end of buttons
			});//end of dialogbox
		$(".ui-dialog-titlebar").hide();
		//end of dialogbox
		e.preventDefault();
	});

	$(document).on("click", ".close_section", function(e){
		var sectionmonitoring_id = $(this).attr("id");

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

					$.ajax({
						type: 'PUT',
						url: "/sectionmonitoring/"+sectionmonitoring_id,
						data: ({ "status":0 }),
						cache: false,
						dataType: 'json',
						success: function(response){
							if(response.success == true)
							{
								showSuccess(response.message);            
							}else{
								showError(response.message);
							}
	
							var program_id = $("#program").val();
							var period_id  =  $('#period_id').val();
							returnSectionMonitoring(period_id, program_id);
						},
					});	
					}	
				}//end of buttons
			});//end of dialogbox
		$(".ui-dialog-titlebar").hide();
		//end of dialogbox
		e.preventDefault();
	});

	$(document).on("click", ".viewenrolledinsection", function(e){
		var section = $(this).attr("id");

        $.ajax({
            type: "GET",
            url: "/sectionmonitoring/viewenrolledinsection/"+section,
            success: function(data){
                //console.log(data);
                $('#ui_content').html(data);
                $("#enrolledinsection_modal").modal('show');

				$('#scrollable_table').DataTable({
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
					"bAutoWidth": false,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false
                });
				
				$('#enrolledinsection_modal').on('shown.bs.modal', function (e) {
					//setTimeout(function () {
						$.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
					//},200);
				});
            }
        });

		e.preventDefault();
	});

});