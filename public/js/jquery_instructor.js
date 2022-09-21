//DOCUMENT READY
$(function(){

    $(document).on("click",".pagination a",function(e){
        e.preventDefault();
        //get url and make final url for ajax 
        var url=$(this).attr("href");

        var append=url.indexOf("?")==-1?"?":"&";
        var finalURL=url+append+$("#filter_form").serialize();
        
        filterinstructors(finalURL);
    })

    function filterinstructors(finalURL)
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
        var finalURL="/instructors?"+$("#filter_form").serialize();
        filterinstructors(finalURL);

        e.preventDefault();
    });

    $(document).on("keyup", "#keyword", function(e)
    {
        var finalURL="/instructors?"+$("#filter_form").serialize();
        filterinstructors(finalURL);

        e.preventDefault();
    });

    $(document).on("click", "#download_excel", function(e)
    {
        $("#filter_form").attr("action","/instructors/export");
		$("#filter_form").submit();

        e.preventDefault();
    });

    $(document).on("click", "#generate_pdf", function(e)
    {
        $("#filter_form").attr("action","/instructors/generatepdf");
		$("#filter_form").submit();

        e.preventDefault();
    });

    $(document).on("click", ".instructor_action", function(e)
    {
        var id = $(this).attr('id');
        var action = $(this).attr('data-action');

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm '+action+'</div><div class="message">Are you sure you want to perform '+action+'?</div>').dialog({
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
							url: 'instructors/'+id+'/instructoraction/'+action,
							type: 'POST',
							dataType: 'json',
							success: function(data){
								showSuccess(data.message);
                                
								var finalURL="/instructors?"+$("#filter_form").serialize();
                                filterinstructors(finalURL);
							},
							error: function (data) {
								var errors = data.responseJSON;
								if ($.isEmptyObject(errors) == false) {
									showError('Something went wrong! Can not perform requested action! '+errors.message);
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