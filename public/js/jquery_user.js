//DOCUMENT READY
$(function(){
   $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

/*********************************************
*** FUNCTION ONCLICK SELECT ALL CHECKBOXES ***
*********************************************/	
   $(document).on("click", ".selectall", function()
   {
      var id = $(this).attr("data-id");

      $('.item_'+id+', .read_'+id+', .write_'+id).not(this).prop('checked', this.checked);
   });

/*****************************************
*** FUNCTION ON CLICK SINGLE ACCESS    ***
*****************************************/	
   $(document).on("click", ".uaccess", function()
   {
      var id = $(this).attr("id");
      $('#read_'+id+', #write_'+id).not(this).prop('checked', this.checked);
      
   });

/*****************************************
*** FUNCTION ON SUBMIT FORM SAVE USER  ***
*****************************************/	
   $(document).on("submit", "#create_user_form", function(e)
   {
      submitForm("#create_user_form", 'reload');

      e.preventDefault();
   });

   $(document).on("submit", "#update_user_form", function(e)
   {
      submitForm("#update_user_form", 'reload');

      e.preventDefault();
   });

   function submitForm(formName, action)
   {
      var url = $(formName).attr('action');
      var postData = $(formName).serializeArray();

      
      $('.uaccess:checked').each(function () {
         var id = $(this).attr('id');
         var val = $(this).val();

         var read = ($("#read_"+id).prop('checked')) ? 1 : 0;
         var write = ($("#write_"+id).prop('checked')) ? 1 : 0;

         postData.push({name: 'read[]', value: read });
         postData.push({name: 'write[]', value: write });
         //alert(id+'--'+val+'--'+read+'--'+write);
      });

      $.ajax({
         url: url,
         type: 'POST',
         data: postData,
         dataType: 'json',
         success: function(data){
            //console.log(data);
            $('.alert').remove();

            $(formName).prepend('<p class="alert '+data.alert+'">'+data.message+'</p>');

            if(action === 'reload')
            {
               window.setTimeout(function(){
                  location.reload();
               }, 1000);
            }
            if(action === 'reset')
            {
               $(formName)[0].reset();
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
   }

   $(document).on("click",".pagination a",function(e){
      e.preventDefault();
      //get url and make final url for ajax 
      var url=$(this).attr("href");

      var append=url.indexOf("?")==-1?"?":"&";
      var finalURL=url+append+$("#filter_form").serialize();
      
      filterusers(finalURL);
  })

   $(document).on("change", ".dropdownfilter", function(e)
    {
        var finalURL="/users?"+$("#filter_form").serialize();
        filterusers(finalURL);

        e.preventDefault();
    });

    $(document).on("keyup", "#keyword", function(e)
    {
        var finalURL="/users?"+$("#filter_form").serialize();
        filterusers(finalURL);

        e.preventDefault();
    });

    function filterusers(finalURL)
    {
        $.ajax({
            url: finalURL,
            success: function(data){
               console.log(data);
                $('#table_data').html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });	
    }

    $(document).on("click", ".user_action", function(e)
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
							url: 'users/'+id+'/useraction/'+action,
							type: 'POST',
							dataType: 'json',
							success: function(data){
								showSuccess(data.message);
                                
                                var currentpage=$('.pagination li.active').text();
                                var finalURL="/users?page="+currentpage+"&"+$("#filter_form").serialize();
                                filterusers(finalURL);
							},
							error: function (data) {
                                console.log(data);
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