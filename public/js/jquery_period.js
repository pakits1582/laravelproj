//DOCUMENT READY
$(function(){
    var pickerOpts = { 
        dateFormat: $.datepicker.ATOM,
        changeYear: true,
        changeMonth: true,
        dateFormat:'mm-dd-yy'
        };	
    
        var pickerOpts3 = { 
        dateFormat: $.datepicker.ATOM,
        //minDate: new Date(),
        maxDate: new Date(),
        changeYear: true,
        changeMonth: true
        };	
        
        var pickerOpts2 = { 
        dateFormat: $.datepicker.ATOM,
        //minDate: new Date(),
        changeYear: true,
        changeMonth: true,
        maxDate: new Date(),
        dateFormat:'mm-dd-yy'
        //maxDate: "-18y",
        };	

    $('.datepicker').datepicker(pickerOpts);  

    $(document).on("change","#term", function(e){
		let val = $(this).val();
		if(val == 'addterm'){
			$("#term option:selected").prop('selected', false);
            alert('xxxx');
			// $.ajax({url: baseUrl+"/period/addsemester/",success: function(data){
			// 		$("#ui_content").html(data).dialog({
			// 			show: 'fade',
			// 			width: 450,
			// 			height: 'auto',
			// 			resizable: false,	
			// 			draggable: false,
			// 			modal: true
			// 		});//end of dialogbox
			// 		$(".ui-dialog-titlebar").hide();
			// 	}
			// });	
	   }else{
			if($('#year').val() != ""){
				var idmask = $('#syear').val().slice(-2)+$(this).val();
				$("#idmask").val(idmask);
				$('.date_picker').val("");	
			}else{
				$(this).prop("selectedIndex", 0);
				showError('Please fill year first.');
			}
	   }
		e.preventDefault();
	});	
 });