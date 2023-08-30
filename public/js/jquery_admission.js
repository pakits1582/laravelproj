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
                $('#decription').val('');

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

});