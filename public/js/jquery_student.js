//DOCUMENT READY
$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        
        $.ajax({
            url: "/students/updateprofile",
            type: 'POST',
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
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
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false)
                {
                    showError('Something went wrong! Can not perform requested action!');
                }
            }
        });
        e.preventDefault();
    });

});