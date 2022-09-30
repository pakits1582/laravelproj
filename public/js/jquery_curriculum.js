//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    function returnCurriculum(program, curriculum)
    {
		$.ajax({url: "/curriculum/"+program+"/curriculum/"+curriculum, success: function(data){
				$("#view_curriculum").html(data);
			}
		});	
    }

    $(document).on("change","#curriculum", function(e){
		var curriculum  = $(this).val();
		var program     = $("#program_id").val();
	
		if(curriculum != ''){
			if(curriculum == 'addcurriculum'){
				$("#curriculum option:selected").prop('selected', false);
				$.ajax({url: "/curriculum/"+program+"/addnewcurriculum",success: function(data){
						//console.log(data);
						$('#ui_content').html(data);
						$("#modalll").modal('show');
					}
				});	
			}else{
				returnCurriculum(program, curriculum);
			}	
		}else{
			$("#view_curriculum").html("");
		}
		e.preventDefault();
	});

	$(document).on("submit",'#addcurriculum_form', function(e) {
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

                $("#addcurriculum_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#curriculum option:last').before($("<option></option>").attr("value", data.id).text(data.curriculum));
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

	$(document).on("keyup", "#search_subject", function(){
		var keyword = $(this).val();
		$.ajax({
			url: "/curriculum/searchsubject",
			type: 'POST',
			data: ({ 'keyword' : keyword}),
			dataType: 'json',
			success: function(response){
				console.log(response);
				
				var subjects = '';
				$.each(response.data, function(k, v){
					subjects += '<option value="'+v.id+'" id="option_'+v.id+'" title="'+v.name+'">('+v.units+') - [ '+v.code+' ] '+v.name+'</option>';       
				});
				$("#search_result").html(subjects);
			
			},
			error: function (data) {
				console.log(data);
			}
		});
	});

	$(document).on("click","#button_moveright", function(e){
		var selectedsubs = $("#search_result").val();
		if(!selectedsubs){
			showError('Please select atleast one subject to move.');	
		}else{
			$.each(selectedsubs, function(index, value){
				//var textval = $("#options_"+value).text();
				var textval = $("#search_result option[value='"+value+"']").text();
				//alert(value+"-"+textval);
				$("#selected_subjects").append('<option value="'+value+'" title="'+textval+'" id="optionselected_'+value+'" selected>'+textval+'</option>');
				$("#option_"+value).remove();
			});
		}
		e.preventDefault();
	});
	
	$(document).on("click","#button_moveleft ", function(e){
		var selectedsubs = $("#selected_subjects").val();
		if(!selectedsubs){
			showError('Please select atleast one subject to move.');	
		}else{
			$.each(selectedsubs, function(index, value){
				//var textval = $("#optionselected_"+value).text();
				var textval = $("#selected_subjects option[value='"+value+"']").text();
				$("#optionselected_"+value).remove();
				$("#search_result").append('<option value="'+value+'" title="'+textval+'" id="option_'+value+'">'+textval+'</option>');
				
			});
		}
		e.preventDefault();
	});

	$(document).on("submit", "#form_addsubjectincurriculum", function(e){
		var url = $(this).attr('action');
      	var postData = $(this).serializeArray();

		var curriculum  = $("#curriculum").val();
		var program     = $("#program_id").val();

		$.ajax({
			url: url,
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
			success: function(data){
				$("#confirmation").dialog('close');
				console.log(data);
				$('.alert').remove();

				$("#form_addsubjectincurriculum").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>');
				window.scrollTo(0, 0);

				$("#selected_subjects").html("");
				returnCurriculum(program, curriculum);
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

	$(document).on("click", ".manage_curriculum_subject", function(e){
		var curriculum_subject_id = $(this).attr("id");

		$.ajax({url: "/curriculum/managecurriculumsubject/"+curriculum_subject_id, success: function(data){
				$('#ui_content').html(data);
				$("#modalll").modal('show');
				
			}
		});	
		e.preventDefault();
	});

/** CURRICULUM SUBJECT MANAGEMENT  CURRICULUM SUBJECT MANAGEMENT  CURRICULUM SUBJECT MANAGEMENT  CURRICULUM SUBJECT MANAGEMENT  CURRICULUM SUBJECT MANAGEMENT */
	function searchcurriculumsubjects(){
		var keyword = $("#search_subject_currsubmgmt").val();
		var saveto = $("#saveto").val();
		var curriculum_subject = $("#curriculum_subject").val();
		var curriculum_id = $("#curriculum_id").val();

		$.ajax({
			url: "/curriculum/searchcurriculumsubjects",
			type: 'POST',
			data: ({ 'keyword' : keyword, 'saveto' : saveto, 'curriculum_subject' : curriculum_subject, 'curriculum_id' : curriculum_id}),
			dataType: 'json',
			success: function(response){
				console.log(response);
				
				var subjects = '';
				$.each(response.data, function(k, v){
					if(saveto === 'equiv'){
						subjects += '<option value="'+v.id+'" id="option_'+v.id+'" title="'+v.name+'">('+v.units+') - [ '+v.code+' ] '+v.name+'</option>';       
					}else{
						subjects += '<option value="'+v.subjectinfo.id+'" id="option_'+v.subjectinfo.id+'"  title="'+v.subjectinfo.name+'">('+v.subjectinfo.units+') - [ '+v.subjectinfo.code+' ] '+v.subjectinfo.name+'</option>';       
					}
				});
				$("#search_result_currsubmgmt").html(subjects);
			},
			error: function (data) {
				console.log(data);
			}
		});
	}

	$(document).on("keyup", "#search_subject_currsubmgmt", function(e){
		searchcurriculumsubjects();

		e.preventDefault();
	});

	$(document).on("change", "#saveto", function(e){
		searchcurriculumsubjects();

		e.preventDefault();
	});

	$(document).on("click", "#button_moveright_currsubmgmt", function(e){
		var selectedsubs = $("#search_result_currsubmgmt").val();
		if(!selectedsubs){
			showError('Please select atleast one subject to move.');	
		}else{
			$.each(selectedsubs, function(index, value){
				//var textval = $("#options_"+value).text();
				var textval = $("#search_result_currsubmgmt option[value='"+value+"']").text();
				//alert(value+"-"+textval);
				$("#selected_subjects_currsubmgmt").append('<option value="'+value+'" title="'+textval+'" id="optionselected_'+value+'" selected>'+textval+'</option>');
				$("#option_"+value).remove();
			});
		}
		e.preventDefault();
	});

	$(document).on("click", "#button_moveleft_currsubmgmt", function(e){
		var selectedsubs = $("#selected_subjects_currsubmgmt").val();
		if(!selectedsubs){
			showError('Please select atleast one subject to move.');	
		}else{
			$.each(selectedsubs, function(index, value){
				//var textval = $("#optionselected_"+value).text();
				var textval = $("#selected_subjects_currsubmgmt option[value='"+value+"']").text();
				$("#optionselected_"+value).remove();
				$("#search_result_currsubmgmt").append('<option value="'+value+'" title="'+textval+'" id="option_'+value+'">'+textval+'</option>');
				
			});
		}
		e.preventDefault();
	});

	$(document).on("submit", "#form_manage_curriculum_subject", function(e){
		var url = $(this).attr('action');
      	var postData = $(this).serializeArray();

		var curriculum  = $("#curriculum_id").val();
		var program     = $("#program_id").val();
		var curriculum_subject     = $("#curriculum_subject").val();
		var saveto = $("#saveto").val();

		$.ajax({
			url: url,
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
			success: function(data){
				$("#confirmation").dialog('close');
				console.log(data);
				$('.alert').remove();

				$("#form_manage_curriculum_subject").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>');
				window.scrollTo(0, 0);

				$("#selected_subjects").html("");
				returnCurriculum(program, curriculum);
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

});