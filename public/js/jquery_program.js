//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    
    $('.datepicker').datepicker(pickerOpts);  

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("change","#educational_level_id", function(e){
		let val = $(this).val();

		if(val == 'addnewlevel'){
			$("#educational_level_id option:selected").prop('selected', false);
            $.ajax({url: "/programs/addnewlevel",success: function(data){
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
	    }
	    e.preventDefault();
	});

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("submit",'#addlevel_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                $('.alert').remove();

                $("#addlevel_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#educational_level_id option:last').before($("<option></option>").attr("value", data.level_id).text(data.level));
                    $('#addlevel_form')[0].reset();
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


    $(document).on("click", "#download_excel", function(e)
    {
        $("#filter_form").attr("action","/programs/export");
		$("#filter_form").submit();

        e.preventDefault();
    });

    $(document).on("click", "#generate_pdf", function(e)
    {
        $("#filter_form").attr("action","/programs/generatepdf");
		$("#filter_form").submit();

        e.preventDefault();
    });


});