$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    
    $("#period_id").select2({
	    dropdownParent: $("#ui_content4")
	});

    var dataTable = $('#scrollable_table').DataTable({
        scrollY: 400,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false
    });

    function returnClassList(postData)
    {
        $.ajax({
            url: "/classlists/filterclasslist",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_classlist").html(response);
                
                $('#scrollable_table').DataTable({
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false
                });

            },
            error: function (data) {
                //console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    clearForm()
                }
            }
        });
    }

    $("#form_filterclasslist").submit(function(e){
        e.preventDefault();
    });

    $(document).on("change", ".filter_item", function(){
        var keyword = $("#search_keyword").val();

        var postData = $("#form_filterclasslist").serializeArray();
        postData.push({name:"keyword", value:keyword});
        
        returnClassList(postData);
    });

    $(document).on('keyup','#search_keyword',function(e){  
		if (e.keyCode == '13')  
        {
			var keyword = $(this).val();
            var postData = $("#form_filterclasslist").serializeArray();
            postData.push({name:"keyword", value:keyword});
            
            returnClassList(postData);
		}
	}); 

    $(document).on("change", "#period_id", function(){
        var period_name = $("#period_id option:selected").text();
        $("#period_name").text(period_name);

    });

    $(document).on("change", ".viewclasslist", function(){
        var class_id = $(".viewclasslist:checked").attr("value");

        if($(".viewclasslist:checked").length == 1)
        {
            $.ajax({
                url: "/classlists/"+class_id,
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
                success: function(response){
                    console.log(response);
                    $("#confirmation").dialog('close');
                    
                },
                error: function (data) {
                    console.log(data);
                    $("#confirmation").dialog('close');
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) == false) {
                        showError('Something went wrong! Can not perform requested action! '+errors.message);
                    }
                }
            });
		}else{
            $('#return_class_info').html('');
        }
    });


});