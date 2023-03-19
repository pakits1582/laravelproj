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

    $("#period").select2({
        dropdownParent: $("#ui_content4")
    });

    $("#scholarshipdiscount").select2({
        dropdownParent: $("#ui_content4")
    });

    function returnScholarshipdiscountGrants(enrollment_id)
    {
        if(enrollment_id){
            $.ajax({
                url: "/scholarshipdiscounts/scholarshipdiscountgrants",
                type: 'POST',
                data: ({ 'enrollment_id':enrollment_id }),
                success: function(data){
                    console.log(data);
                    $("#return_scholarshipdiscount_grant").html(data);
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearForm()
                    }
                }
            });
        }
    }

    $(document).on("change", "#student", function(e){
        var student_id = $(this).val();
        var period_id  = $("#period").val();

        $.ajax({
            url: "/enrolments/"+student_id+"/"+period_id,
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
                $("#confirmation").dialog('close');
                console.log(response);
                if(response.data === false)
                {
                    showError('Student is not enrolled!');
                    clearForm();
                }else{
                    if(response.data.acctok === 0)
                    {
                        showError('Student enrollment is not yet saved! Please save enrollment first!');
                        clearForm();
                    }else{
                        if(response.data.assessed === 0)
                        {
                            showError('Student enrollment is not yet assessed! Please save assessment first!');
                            clearForm();
                        }else{
                            // //DISPLAY ENROLLMENT
                            $("#enrollment_id").val(response.data.id);
                            $("#assessment_id").val(response.data.assessment.id);
                            $("#program").val(response.data.program.name);
                            $("#educational_level").val(response.data.program.level.code);
                            $("#college").val(response.data.program.collegeinfo.code);
                            $("#curriculum").val(response.data.curriculum.curriculum);
                            $("#year_level").val(response.data.year_level);
                            $("#enrollment_id").val(response.data.id);
                            $("#save_grant").prop("disabled", false);

                            returnScholarshipdiscountGrants(response.data.id);
                        }
                    }
                }
            },
            error: function (data) {
                $("#confirmation").dialog('close');
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm();
                }
            }
        });

        e.preventDefault();
    });

    $(document).on("submit", "#form_scholarshipdiscountgrant", function(e){
        var postData   = $(this).serializeArray();
        var student_id = $("#student").val();
        var period_id  = $("#period").val();

        $.ajax({
            url: "/scholarshipdiscounts/savegrant",
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
                showMessageInForm('form_scholarshipdiscountgrant', response.data.alert, response.data.message);
                returnScholarshipdiscountGrants(student_id, period_id);
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