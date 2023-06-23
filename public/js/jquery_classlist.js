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


});