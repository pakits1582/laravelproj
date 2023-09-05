$(function(){

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var dataTable = $('#scrollable_table_admission_documents').DataTable({
        scrollY: 400,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false
    });

    $(document).on("change", ".custom-file-input",  function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").removeClass("selected").html(fileName);
    });
    
    function returnAdmissionDocuments()
    {
        $.ajax({
            url: "/admissions/returndocuments",
            type: 'GET',
            success: function(data){
                $("#return_admission_documents").html(data);

                var dataTable = $('#scrollable_table_admission_documents').DataTable({
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false
                });
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

    $(document).on("click","#cancel", function()
    {
        $('.admission_document, .checkbox').prop("checked", false);
		$('.actions').prop("disabled", true);
        //form fields
		$('.clearable').val("").trigger('change');
		$('input.checks').prop('disabled', false).prop('checked', false);
		$('.checks').closest('tr').removeClass('selected');

        $('.form_document').attr("id", "form_add_admission_document");
        $('.errors').html('');  
	});

    $(document).on("click", "#generate_idno", function(e){
        var period_id = $("#period_id").val();

        $.ajax({
            url: "/students/generateidno",
            type: 'POST',
            data: {'period_id': period_id},
            dataType: 'json',
            success: function(response){
                console.log(response);
                if(response.success === false)
                {
                    showError(response.message);
                }else{
                    $("#idno").val(response);
                }
            },
            error: function (data) {
                console.log(data);
                $("#confirmation").dialog('close');
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });

        e.preventDefault();
    });

    $(document).on("submit", "#form_add_admission_document", function(e){
        var postData = $(this).serializeArray();

        $.ajax({
            url: "/admissions/savedocument",
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
                //console.log(response)
                if(response.success == true)
                {
                    showSuccess(response.message);
                }else{
                    showError(response.message);
                }

                returnAdmissionDocuments();
                $('#description').val('');

            },
            error: function (data) {
                console.log(data);
                $("#confirmation").dialog('close');
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

    $(document).on("click", "#edit", function(e){
        var admission_document_id = $(".admission_document:checked").val();

		if($(".admission_document:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to edit!');	
		}else{
            $.ajax({
                url: "/admissions/"+admission_document_id+"/editdocument",
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
                    //console.log(response);
                    $("#confirmation").dialog('close');
                    if(!jQuery.isEmptyObject(response)){

                        $('#edit').prop("disabled", true);
                        $('input.checks').prop('disabled', true); 
                        $('#delete_selected').prop("disabled", true);

                        $('#educational_level_id').val(response.data.educational_level_id);
                        $('#program').val(response.data.program_id);
                        $('#classification').val(response.data.classification);
                        $('#description').val(response.data.description);

                        if (response.data.display === 1){ $('#display').prop('checked', true) }
                        if (response.data.is_required === 1){ $('#is_required').prop('checked', true) }

                        $('.form_document').attr("id", "form_update_admission_document");
                    }else{
                        showError('Oppss! Something went wrong! Can not fetch data!');
                    }
                },
                error: function (data) {
                    console.log(data);
                    $("#confirmation").dialog('close');
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        showError('Something went wrong! Can not perform requested action!');
                    }
                }
            });
        }

        e.preventDefault();
    });

    $(document).on("submit", "#form_update_admission_document", function(e){
        var postData = $(this).serializeArray();
        var admission_document_id = $(".admission_document:checked").val();

		if($(".admission_document:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to edit!');	
		}else{
            $.ajax({
                url: "/admissions/"+admission_document_id+"/updatedocument",
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
                    //console.log(response);
                    if(response.success == true)
                    {
                        showSuccess(response.message);
                    }else{
                        showError(response.message);
                    }
    
                    returnAdmissionDocuments();
                    $('#cancel').trigger('click');
                },
                error: function (data) {
                   console.log(data);
                   $("#confirmation").dialog('close');
                   var errors = data.responseJSON;
                   if ($.isEmptyObject(errors) == false) {
                       $.each(errors.errors, function (key, value) {
                           $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                       });
                   }
               }
            });
        }

        e.preventDefault();
    });

    $(document).on("click", "#delete_selected", function(e){
        var admission_document_id = $(".admission_document:checked").val();

		if($(".admission_document:checked").length === 0)
        {
			showError('Please select atleast one checkbox/fee to delete!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete selected item?<br>You can not undo this process?</div>').dialog({
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
                                url: "/admissions/"+admission_document_id+"/deletedocument",
                                type: 'DELETE',
                                dataType: 'json',
                                success: function(response)
                                {
                                    console.log(response);
                                    if(response.success == true)
                                    {
                                        showSuccess(response.message);
                                    }else{
                                        showError(response.message);
                                    }

                                    returnAdmissionDocuments();
                                    $('#cancel').trigger('click');
                                },
                                error: function (data) {
                                    console.log(data);
                                    var errors = data.responseJSON;
                                    if ($.isEmptyObject(errors) == false) {
                                        showError('Something went wrong! Can not perform requested action!');
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

    $(document).on("submit", "#form_admit_applicant", function(e){
        var postData = $(this).serializeArray();

        if($(".checkbox:checked").length === 0)
        {
			showError('Please select at least one document submitted!');	
		}else{
            $.ajax({
                url: "/admissions/admitapplicant",
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
                    console.log(response)
                    
                    if(response.success === true)
                    {
                        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Admission Saved</div><div class="message">Student was successfully admitted.<br>Print admission form?</div>').dialog({
                            show: 'fade',
                            resizable: false,	
                            draggable: false,
                            width: 350,
                            height: 'auto',
                            modal: true,
                            buttons: {
                                    'OK':function(){
                                        $("#confirmation").dialog('close');
                                        let idno = $("#idno").val();

                                        window.open("/admissions/printadmissionform/"+idno, '_blank'); 
                                        location.href = "/admissions";		
                                    },
                                    'Cancel':function(){
                                        $("#confirmation").dialog('close');
                                        location.href = "/admissions";			
                                    }
                                }//end of buttons
                        });//end of dialogbox
                        $(".ui-dialog-titlebar").hide();
                        //end of dialogbox
                    }else{
                        showError(response.message);
                    }

                },
                error: function (data) {
                    console.log(data);
                    $("#confirmation").dialog('close');
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        $.each(errors.errors, function (key, value) {
                            $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                        });
                    }
               }
            });
        }

        e.preventDefault();
    });

    $(document).on("blur", "#application_no", function(e){
        var application_no = $(this).val();

        if(application_no){
			$.ajax({
				type: "POST",
				url: "/admissions/getapplicantinfo",
				dataType: 'json',
				data: {'application_no':application_no},
				cache: false,
				beforeSend: function() {
					$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Checking student information!<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
						show: 'fade',
						resizable: false,	
						width: 350,
						height: 'auto',
						modal: true,
						buttons: false
					});
					$(".ui-dialog-titlebar").hide();
				},
				success: function(response) {
                    $('.errors').html('');
					$("#confirmation").dialog('close');
                    console.log(response);

                    if(response.success === false)
                    {
                        showError(response.message);
                        $(".displaydata, #admission_requirements").html('&nbsp;');

                    }else{
                        if(response.applicant.admission_status == 1)
                        {
                            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Online Admission</div><div class="message">You have already submitted your online admission application. You will be receiving an email confirmation once admission is processed. Thank You!</div>').dialog({
                                show: 'fade',
                                resizable: false,	
                                draggable: false,
                                width: 350,
                                height: 'auto',
                                modal: true,
                                buttons: {
                                        'OK':function(){
                                            $(this).dialog('close');
                                        }//end of ok button	
                                    }//end of buttons
                                });//end of dialogbox
                            $(".ui-dialog-titlebar").hide();
                        }else{
                            $('#classification').html(response.applicant.classification);
                            $('#program').html(response.applicant.program);
                            $('#name').html(response.applicant.name);
                            $('#civil_status').html(response.applicant.civil_status);
                            $('#birth_date').html(response.applicant.birth_date);
                            $('#birth_place').html(response.applicant.birth_place);
                            $('#sex').html(response.applicant.sex);
                            $('#nationality').html(response.applicant.nationality);
                            $('#email').html(response.applicant.email);
                            $('#mobileno').html(response.applicant.mobileno);
                            $('#contact_email').val(response.applicant.contact_email);
                            $('#contact_no').val(response.applicant.contact_no);

                            if(response.documents)
                            {
                                var document_requirements = '';
                                $.each(response.documents, function(index, document) 
                                {
                                    document_requirements += '<div class="row">';
                                    document_requirements += '<div class="col-md-12">';
                                    document_requirements += '<label for="'+document.name+'" class="m-0 font-weight-bold text-primary">'+document.label+'</label>';
                                    document_requirements += '<div class="custom-file">';
                                    document_requirements += '<input type="file" name="'+document.name+'[]" multiple class="custom-file-input" id="'+document.name+'" '+document.is_required+' accept="image/*,application/pdf">';
                                    document_requirements += '<label class="custom-file-label" for="'+document.name+'">Choose file</label>';
                                    document_requirements += '</div>';
                                    document_requirements += '<div id="error_'+document.name+'" class="errors"></div>'
                                    document_requirements += '</div></div>';
                                });

                                $("#admission_requirements").html(document_requirements);
                            }
                        }
                    }

					},
                    error: function (data) {
                        console.log(data);
                        $("#confirmation").dialog('close');
                        var errors = data.responseJSON;
                        if ($.isEmptyObject(errors) == false) {
                            $.each(errors.errors, function (key, value) {
                                $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                            });
                        }
                   }
				});
		}else{
			showError('Please provide application number!');
			$(".displaydata, #name, #admissionrequirements").html('&nbsp;');
		}	
	});

	$(document).on("keyup","#application_no", function(e){
		if (e.keyCode == '13')  {
            e.preventDefault();
		}
        e.preventDefault();
	});

    $(document).on("submit", "#form_online_admission", function(e){
        
        $.ajax({
            url: "/admissions/saveonlineadmission",
            type: 'POST',
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
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
                console.log(response)
                
                if(response.success === true)
                {
					$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Admission application successfully submitted.</div><div class="message">Your information and documents submitted will be evaluated and you will receive an email acknowledgement from the school during the enrolment schedules once application is approved.</div></div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        draggable: false,
                        width: 350,
                        height: 'auto',
                        modal: true,
                        buttons: {
                                'OK':function(){
                                    $("#confirmation").dialog('close');
                                     window.location.reload();
                                }
                            }//end of buttons
                    });//end of dialogbox
                    $(".ui-dialog-titlebar").hide();
                    //end of dialogbox
                }else{
                    showError(response.message);
                }
            },
            error: function (data) {
                console.log(data);
                $("#confirmation").dialog('close');

                $('.errors').html('');
                showError('Can not process form request, please check entries then submit again!');
                var errors = data.responseJSON;
                if (!$.isEmptyObject(errors)) {
                    $.each(errors.errors, function (key, value) {
                        if (key.includes('.')) {
                            // Split the key into parts to get the field name and index
                            var parts = key.split('.');
                            var fieldName = parts[0];
                            // Display the custom error message
                            $('#error_' + fieldName).html('<p class="text-danger text-xs mt-1">' + value[0] + '</p>');
                        } else {
                            // Handle other errors for fields without indexes
                            $('#error_' + key).html('<p class="text-danger text-xs mt-1">' + value[0] + '</p>');
                        }
                    });
                }
                
            }
        });

        e.preventDefault();
    });
});