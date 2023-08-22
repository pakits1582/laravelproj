$(function(){

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#entry_period, #period, #program_id, .region, .province, .municipality, .barangay").select2({
	    dropdownParent: $("#ui_content4")
	});

    var confirmationDialog = $("#confirmation").dialog({
        autoOpen: false,
        show: 'fade',
        resizable: false,
        width: 350,
        height: 'auto',
        modal: true,
        buttons: false,
        closeOnEscape: false, // Prevent dialog from closing on Esc key
        open: function(event, ui) {
            $(".ui-dialog-titlebar", ui.dialog).hide();
        }
    });
  
      //   var dataTable = $('#scrollable_table').DataTable({
      //       scrollY: 400,
      //       scrollX: true,
      //       scrollCollapse: true,
      //       paging: false,
      //       ordering: false,
      //       info: false,
      //       searching: false
      //   });
        
      //   $("#period_id, #program").select2({
      //     dropdownParent: $("#ui_content4")
      // });
  
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").removeClass("selected").html(fileName);
    });

    $(document).on("change","#shs_strand",function(e){
		var selected = $(this).val();

		if(selected == 'TECH-VOC'){
			$('#shs_techvoc_specify').attr("readonly",false).prop('required',true);
		}else{
			$('#shs_techvoc_specify').attr("readonly",true).val("").prop('required',false);
		}
	});

    $(document).on("change","#religion",function(e){
		var selected = $(this).val();

		if(selected == '14'){
			$('#religion_specify').attr("readonly",false);
		}else{
			$('#religion_specify').attr("readonly",true).val("");
		}
	});

    $(document).on("submit", "#form_application", function(e){
        $.ajax({
            url: "/applications/saveonlineapplication",
            type: 'POST',
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                // Open the confirmation dialog
                confirmationDialog.dialog("open");
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Submitting application, please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>');
            },
            success: function(response)
            {
                $('.errors').html('');
                confirmationDialog.dialog('close');
                console.log(response);
                if(response.success == true)
                {
                    $("#ui_content").html('<div class="confirmation"></div><div class="ui_title_confirm">Online Application</div><div class="message">'+response.message+'</div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        draggable: false,
                        width: 350,
                        height: 'auto',
                        modal: true,
                        buttons: {
                                'OK':function(){
                                    $(this).dialog('close');
                                    window.location.reload();
                                }//end of ok button	
                            }//end of buttons
                    });//end of dialogbox
                    $(".ui-dialog-titlebar").hide();
                }else{
                    showError(response.message);
                }
            },
            error: function (data) {
                confirmationDialog.dialog('close');

                $('.errors').html('');
                showError('Can not process form request, please check entries then submit again!');
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    var reportCardErrors = '';
                    $.each(errors.errors, function (key, value) {
                        if (key.includes('report_card.')) {
                            reportCardErrors += value[0] + '<br>';
                        } else {
                            $('#error_' + key).html('<p class="text-danger text-xs mt-1">' + value[0] + '</p>');
                        }
                    });

                    // Display the collated report_card errors in the error_report_card div
                    $('#error_report_card').html('<p class="text-danger text-xs mt-1">' + reportCardErrors + '</p>');
                }
            }
        });

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

    function setCheckedByValue(inputName, dataValue) 
    {
        $('input[name="'+inputName+'"]').each(function() {
          const value = parseInt($(this).val()); // Get the value of the current input and convert to an integer
          const isChecked = value === dataValue; // Check if the value matches the dataValue
                              
          // Set the "checked" property based on the comparison result
          $(this).prop("checked", isChecked);
        });
    }

    $(document).on("blur", "#idno", function(e){
        var idno = $(this).val();

        if(idno)
        {
            $.ajax({
                url: "/students/studentfullinfo",
                type: 'POST',
                data: {'idno':idno},
                dataType: 'json',
                cache: false,
                beforeSend: function() {
                    confirmationDialog.dialog("open");
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Fetching record, please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>');
                },
                success: function(data)
                {
                    confirmationDialog.dialog("close");
                    console.log(data);

                    if(!jQuery.isEmptyObject(data)){
                        $("#last_name").val(data.last_name);
                        $("#first_name").val(data.first_name);
                        $("#middle_name").val(data.middle_name);
                        $("#name_suffix").val(data.name_suffix).trigger('change');
                        $("#sex").val(data.sex).trigger('change');

                        if(data.personal_info)
                        {
                            $("#civil_status").val(data.personal_info.civil_status).trigger('change');
                            $("#birth_date").val(data.personal_info.birth_date);
                            $("#birth_place").val(data.personal_info.birth_place);
                            $("#nationality").val(data.personal_info.nationality).trigger('change');
                            $("#religion").val(data.personal_info.religion).trigger('change');
                            $("#religion_specify").val(data.personal_info.religion_specify);

                            setCheckedByValue('baptism', data.personal_info.baptism);
                            setCheckedByValue('communion', data.personal_info.communion);
                            setCheckedByValue('confirmation', data.personal_info.confirmation);

                            $("#father_name").val(data.personal_info.father_name);
                            $("#father_address").val(data.personal_info.father_address);
                            $("#father_contactno").val(data.personal_info.father_contactno);

                            $("#mother_name").val(data.personal_info.mother_name);
                            $("#mother_address").val(data.personal_info.mother_address);
                            $("#mother_contactno").val(data.personal_info.mother_contactno);

                            $("#guardian_name").val(data.personal_info.guardian_name);
                            $("#guardian_relationship").val(data.personal_info.guardian_relationship);
                            $("#guardian_address").val(data.personal_info.guardian_address);
                            $("#guardian_contactno").val(data.personal_info.guardian_contactno);
                            $("#guardian_occupation").val(data.personal_info.guardian_occupation);
                            $("#guardian_employer").val(data.personal_info.guardian_employer);
                            $("#guardian_employer_address").val(data.personal_info.guardian_employer_address);

                            $("#occupation").val(data.personal_info.occupation);
                            $("#employer").val(data.personal_info.employer);
                            $("#employer_address").val(data.personal_info.employer_address);
                            $("#employer_contact").val(data.personal_info.employer_contact);
                            $("#occupation_years").val(data.personal_info.occupation_years);

                        }

                        if(data.contact_info)
                        {
                            $("#current_region").val(data.contact_info.current_region).trigger('change');
                            setTimeout(function() {
                                $("#current_province").val(data.contact_info.current_province).trigger('change');
                            }, 200);
                            setTimeout(function() {
                                $("#current_municipality").val(data.contact_info.current_municipality).trigger('change');
                            }, 300);
                            setTimeout(function() {
                                $("#current_barangay").val(data.contact_info.current_barangay).trigger('change');
                            }, 400);
                            $("#current_address").val(data.contact_info.current_address);
                            $("#current_zipcode").val(data.contact_info.current_zipcode);

                            setTimeout(function() {
                                $("#permanent_region").val(data.contact_info.permanent_region).trigger('change');
                            }, 500);
                            setTimeout(function() {
                                $("#permanent_province").val(data.contact_info.permanent_province).trigger('change');
                            }, 600);
                            setTimeout(function() {
                                $("#permanent_municipality").val(data.contact_info.permanent_municipality).trigger('change');
                            }, 700);
                            setTimeout(function() {
                                $("#permanent_barangay").val(data.contact_info.permanent_barangay).trigger('change');
                            }, 800);
                            
                            $("#permanent_address").val(data.contact_info.permanent_address);
                            $("#permanent_zipcode").val(data.contact_info.permanent_zipcode);

                            $("#telno").val(data.contact_info.telno);
                            $("#email").val(data.contact_info.email);
                            $("#mobileno").val(data.contact_info.mobileno);

                            $("#contact_email").val(data.contact_info.contact_email);
                            $("#contact_no").val(data.contact_info.contact_no);
                        }
                       
                        if(data.academic_info)
                        {
                            $("#elem_school").val(data.academic_info.elem_school);
                            $("#elem_address").val(data.academic_info.elem_address);
                            $("#elem_period").val(data.academic_info.elem_period);

                            $("#jhs_school").val(data.academic_info.jhs_school);
                            $("#jhs_address").val(data.academic_info.jhs_address);
                            $("#jhs_period").val(data.academic_info.jhs_period);

                            $("#shs_strand").val(data.academic_info.shs_strand).trigger('change');
                            $("#shs_techvoc_specify").val(data.academic_info.shs_techvoc_specify);
                            $("#shs_school").val(data.academic_info.shs_school);
                            $("#shs_address").val(data.academic_info.shs_address);
                            $("#shs_period").val(data.academic_info.shs_period);

                            $("#college_program").val(data.academic_info.college_program);
                            $("#college_school").val(data.academic_info.college_school);
                            $("#college_address").val(data.academic_info.college_address);
                            $("#college_period").val(data.academic_info.college_period);

                            $("#graduate_program").val(data.academic_info.graduate_program);
                            $("#graduate_school").val(data.academic_info.graduate_school);
                            $("#graduate_address").val(data.academic_info.graduate_address);
                            $("#graduate_period").val(data.academic_info.graduate_period);
                        }  
                    }
                },
                error: function (data) {
                    confirmationDialog.dialog("close");                    
                    console.log(data);
                }
            });
        }
        
        e.preventDefault();
    });

});