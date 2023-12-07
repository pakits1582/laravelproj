//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $(".region, .province, .municipality, .barangay").select2({
	    dropdownParent: $("#ui_content4")
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
    
    $("#program_id, #program").select2({
	    dropdownParent: $("#ui_content2")
	});

    $("#picture_preview").mouseenter(function() {
		$(".fileUpload").css("display", "block");
	}).mouseleave(function() {
		$(".fileUpload").css("display", "none");
	});

    $(document).on("change", "#program_id", function(){
		var program = $(this).val();
		if(program !== ""){
            $.ajax({
                url: "/curriculum/returncurricula",
                type: 'POST',
                data: ({ 'program' : program}),
                dataType: 'json',
                success: function(response){
                    //console.log(response);
                    
                    var curriculum = '<option value="">- select curriculum -</option>';
                    $.each(response.data, function(k, v){
                        curriculum += '<option value="'+v.id+'">'+v.curriculum+'</option>';       
                    });
                    $("#curriculum").prop("disabled",false).html(curriculum);
                
                },
                error: function (data) {
                    console.log(data);
                }
            });
		}else{
			$("#curriculum").prop("disabled",true);
			$("#curriculum")[0].selectedIndex = 0; 
		}
		
	});

    $(document).on("click",".pagination a",function(e){
        e.preventDefault();
        //get url and make final url for ajax 
        var url=$(this).attr("href");

        var append=url.indexOf("?")==-1?"?":"&";
        var finalURL=url+append+$("#filter_form").serialize();
        
        filterstudents(finalURL);
    });

    function filterstudents(finalURL)
    {
        $.ajax({
            url: finalURL,
            success: function(data){
                $('#table_data').html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });	
    }

    $(document).on("change", ".dropdownfilter", function(e)
    {
        var finalURL="/students?"+$("#filter_form").serialize();
        filterstudents(finalURL);

        e.preventDefault();
    });

    $(document).on("keyup", "#keyword", function(e)
    {
        var finalURL="/students?"+$("#filter_form").serialize();
        filterstudents(finalURL);

        e.preventDefault();
    });

    $(document).on("submit", "#form_student_profile", function(e){
        var postData = $(this).serializeArray();
        $(".errors").html('');

        $.ajax({
            url: "/students/updateprofile",
            type: 'POST',
            data: postData,
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                // Open the confirmation dialog
                confirmationDialog.dialog("open");
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>');
            },
            success: function(response){
                confirmationDialog.dialog('close');
                //console.log(response);
                if(response.success == true)
                {
                    showSuccess(response.message);

                    window.setTimeout(function(){
                        location.reload();
                    }, 1000);
                }else{
                    showError(response.message);
                }
            },
            error: function (data) {
                //console.log(data);
                confirmationDialog.dialog('close');
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

    $(document).on("change", "#picture", function(){
		$("#changePhoto").submit();
	});
	
	$(document).on("submit", "#changePhoto", function(e){
		$.ajax({
            url: "/students/changephoto",
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response){
                //console.log(response);
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
                if ($.isEmptyObject(errors) === false)
                {
                    var return_errors = '';
                    $.each(errors.errors, function (key, value) {
                        return_errors += value+'</br>';
                    });

                    showError(return_errors);
                }
            }
		});
		e.preventDefault();
	});

});