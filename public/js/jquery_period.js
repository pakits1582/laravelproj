//DOCUMENT READY
$(function(){
    
    $('.datepicker').datepicker(pickerOpts);  

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("change","#term", function(e){
		let val = $(this).val();
		if(val == 'addterm'){
			$("#term option:selected").prop('selected', false);
            $.ajax({url: "/periods/addterm",success: function(data){
                    $('#ui_content').html(data);
                    $("#modalll").modal('show');
                }
            });	
	   }else{
            var year = $('#year').val();
			if(year != "" && val != ''){
                var term_type = $("#term option:selected").attr('data-type');
                var term_text = $("#term option:selected").text();
                var periodyear = parseInt(year) + 1;
                var periodname = (term_type == 1) ? term_text+', '+year+'-'+periodyear : term_text+' '+year;
                $("#name").val(periodname);

			}else{
				$(this).prop("selectedIndex", 0);
                $("#name").val('');
				//showError('Please fill year or term first.');
			}
	   }
		e.preventDefault();
	});

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("click", "#succeeding_year", function(){
        var term = $("#term").val();
        var term_type = $("#term option:selected").attr('data-type');
        var term_text = $("#term option:selected").text();
        var year = $('#year').val();
        var periodyear = parseInt(year) + 1;
        
        if(year != "" && term != "" ){
            var periodname = ($(this).prop('checked') && term_type == 1) ? term_text+' '+year : term_text+', '+year+'-'+periodyear;
        
            $("#name").val(periodname);
        }else{
            showError('Please select year and term first!');
            $(this).prop('checked', false);
        }
    });	

/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("change", "#class_start", function(e){
        var year = $("#year").val();
        var term = $("#term").val();
        var term_type = $("#term option:selected").attr('data-type');
        var start = $(this).val();

        if(year != "" && term != "" ){
            var addedmonth = (term_type == 1) ? 5 : 2;
            
            $('#class_end, #class_ext').val(moment(start).add(addedmonth, 'M').format("YYYY-MM-DD"));
            $('#enroll_start, #adddrop_start').val(moment(start).subtract(1, 'M').format("YYYY-MM-DD"));
            $('#adddrop_start').val(start);
            $('#enroll_end, #enroll_ext, #adddrop_end, #adddrop_ext').val(moment(start).add(1, 'M').format("YYYY-MM-DD"));
        }else{
            $('.datepicker').val("");
            showError('Please select year and term first!');
        }
        e.preventDefault();
    });
/***********************************************
*** FUNCTION CHANGE SCHOOL START DATE PICKER ***
***********************************************/
    $(document).on("submit",'#addterm_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
            url: url,
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(data){
                $('.alert').remove();

                $("#addterm_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
                if(data.success){
                    $('#term option:last').before($("<option></option>").attr("value", data.term_id).attr("data-type", data.type).text(data.term));
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