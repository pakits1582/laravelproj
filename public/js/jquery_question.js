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
                if(response.success)
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
});