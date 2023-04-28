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

    $(document).on("change","#bank_id", function(e){
		var val = $(this).val();

		if(val == 'add_bank')
        {
			$("#bank_id option:selected").prop('selected', false);
            $.ajax({url: "/receipts/addbank",success: function(data){
                $('#ui_content').html(data);
                $("#modalll").modal('show');
            }
        });	
	   }
		e.preventDefault();
	});

    $(document).on("submit",'#addbank_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                $('.alert').remove();

                $("#addbank_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#bank_id option:last').before($("<option></option>").attr("value", data.bank_id).text(data.bank));
                    $('#addbank_form')[0].reset();
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