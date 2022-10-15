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

});