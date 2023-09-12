//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

	$(document).on("click", "#add_question", function(e){

		$.ajax({url: "/facultyevaluations/addquestion",success: function(data){
                $('#ui_content').html(data);
                $("#addQuestionModal").modal('show');
            }
        });	
		e.preventDefault();
	});

    $(document).on("change", ".question_category", function(e){
        var val   = $(this).val();
        var field = $(this).attr("id");

        if(val == 'add_item')
        {
			$(".question_category option:selected").prop('selected', false);

            $.ajax({type: 'POST', url: "/facultyevaluations/addquestioncategory", data: {'field':field}, success: function(data){
                    $('#ui_content2').html(data);
                    $("#addCategoryModal").modal('show');
                }
            });	
        }

        e.preventDefault();
    });

    $(document).on("submit", "#form_addcategory", function(e){
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        var url = 
        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log(response);
                $('.alert').remove();
                $('.errors').html('');
                $("#name").val('');

                $("#form_addcategory").prepend('<p class="alert '+response.alert+'">'+response.message+'</p>')
                if(response.success == true)
                {
                    $('#'+response.field+' option:last').before($("<option></option>").attr("value", response.data.id).text(response.data.name));
                    $('#category_body').append('<tr><td>'+response.data.id+'</td><td>'+response.data.name+'</td></tr>');
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

    $(document).on("submit", "#form_addquestion", function(e){
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log(response);
                $('.alert').remove();
                $('.errors').html('');

                $("#form_addquestion").prepend('<p class="alert '+response.alert+'">'+response.message+'</p>');
                if(response.success == true)
                {
                    $("#question").val('');
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

    $(document).on("click", ".edit_question", function(e){
        var question_id = $(this).attr("id");

        $.ajax({
            url: "/facultyevaluations/"+question_id+"/editquestion",
            success: function(data){
                //console.log(data);
                $('#ui_content').html(data);
                $("#addQuestionModal").modal('show');
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });

        e.preventDefault();
    });

    $(document).on("submit", "#form_editquestion", function(e){
        var postData = $(this).serializeArray();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'PUT',
            data: postData,
            dataType: 'json',
            success: function(response){
                console.log(response);
                $('.alert').remove();
                $('.errors').html('');

                $("#form_editquestion").prepend('<p class="alert '+response.alert+'">'+response.message+'</p>');
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

    $(document).on("click", ".delete_question", function(e){
		var id = $(this).attr("id");

		$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete selected item?</div>').dialog({
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
							url: '/facultyevaluations/'+id+'/deletequestion',
							type: 'DELETE',
							dataType: 'json',
							success: function(response){
								
                                if(response.success == true)
                                {
                                    showSuccess(response.message);
                                }else{
                                    showError(response.message);
                                }
								
							},
							error: function (data) {
								//console.log(data);
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
		//end of dialogbox
		e.preventDefault();
	});
});