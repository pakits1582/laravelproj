$(function(){

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $("#period, #program_id, .region, .province, .municipality, .barangay").select2({
	    dropdownParent: $("#ui_content4")
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

    $(document).on("change","#strand",function(e){
		var selected = $(this).val();

		if(selected == 'TECH-VOC'){
			$('#techvoc_specify').attr("disabled",false).prop('required',true);
		}else{
			$('#techvoc_specify').attr("disabled",true).val("").prop('required',false);
		}
	});

    $(document).on("change","#religion",function(e){
		var selected = $(this).val();

		if(selected == '14'){
			$('#religion_specify').attr("disabled",false);
		}else{
			$('#religion_specify').attr("disabled",true).val("");
		}
	});

    $(document).on("submit", "#form_application", function(e){

        var postData = $("#form_application").serializeArray();

        $.ajax({
            url: "/applications/saveonlineapplication",
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
                console.log(response);
                // $("#confirmation").dialog('close');
                // console.log(response);
                // if(response.data.success == true)
                // {
                //     showSuccess(response.data.message);
        
                // }else{
                //     showError(response.data.message);
                // }

                // returnStatementofaccount(student_id, period_id);
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