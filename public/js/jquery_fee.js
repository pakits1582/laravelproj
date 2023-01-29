//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    
    $('.datepicker').datepicker(pickerOpts);  

    $("#subject").select2({
	    // dropdownParent: $("#ui_content3"),
        minimumInputLength: 2,
        tags: false,
        minimumResultsForSearch: 20, // at least 20 results must be displayed
        ajax: {
            url: '/subjects/dropdownselectsearch',
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

    $("#program").select2({
	    dropdownParent: $("#ui_content2")
	});

    $("#fee").select2({
	    dropdownParent: $("#ui_content2")
	});

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("change","#fee_type", function(e){
		let val = $(this).val();

		if(val == 'addnewtype'){
			$("#fee_type option:selected").prop('selected', false);
            $.ajax({url: "/fees/addnewtype",success: function(data){
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
    $(document).on("submit",'#addtype_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                // console.log(data);
                $('.alert').remove();

                $("#addtype_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#fee_type option:last').before($("<option></option>").attr("value", data.values.id).text(data.values.type));
                    $('#addtype_form')[0].reset();
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

    $(document).on("click","#iscompound", function(){
		if($(this).is(":checked")){
            $.ajax({url: "/fees/compoundfee",success: function(data){
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');

                    $("#non_assess_fees").select2({
                        dropdownParent: $("#modalll")
                    });
                }
            });	
            $("#name").prop('readonly', true)
        }else{
            $("#name").prop('readonly', false).val('');
        }
    });

    $(document).on("click","#add_fee", function(){
		let fee = $("#non_assess_fees").val();
		let feecode = $("#non_assess_fees option:selected").text();
		let compoundedfee = $("#compoundedfees").val();
		let fee_array = feecode.split(' - ');

		let compoundedfees = (!compoundedfee) ? fee_array[0] : '=$'+fee_array[0];

		$("#compoundedfees").append(compoundedfees);
	});

    $(document).on("click","#save_compounded_fee", function(){
		let compfees = $("#compoundedfees").val();
		if(compfees){
			let compfee = compfees.split('=$');
			if(compfee.length <= 1){
				showError('Please select at least two fees to compound!');
			}else{
				$("#name").val(compfees);
				$("#modalll").modal('hide');
			}
		}else{
			showError('Please select fees to be compounded!');
		}
		
	});

    // $(document).on("click", "#download_excel", function(e)
    // {
    //     $("#filter_form").attr("action","/programs/export");
	// 	$("#filter_form").submit();

    //     e.preventDefault();
    // });

    // $(document).on("click", "#generate_pdf", function(e)
    // {
    //     $("#filter_form").attr("action","/programs/generatepdf");
	// 	$("#filter_form").submit();

    //     e.preventDefault();
    // });

    $(document).on("submit", "#form_setup_fees", function(e){
        var postData = $(this).serializeArray();

        $.ajax({
            url: "/fees/savesetupfee",
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
               console.log(data);
			
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