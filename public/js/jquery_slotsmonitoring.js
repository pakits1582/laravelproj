$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
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
    
    function returnSlotMonitoring(postData)
    {
        $.ajax({
            url: "/slotmonitoring/filterslotmonitoring",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_slotmonitoring").html(response);
                
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

    $("#form_filterslotmonitoring").submit(function(e){
        e.preventDefault();
    });

    $(document).on("change", ".filter_item", function(){
        var keyword = $("#search_keyword").val();

        var postData = $("#form_filterslotmonitoring").serializeArray();
        postData.push({name:"keyword", value:keyword});
        
        returnSlotMonitoring(postData);
    });

    $(document).on('keyup','#search_keyword',function(e){  
		if (e.keyCode == '13')  
        {
			var keyword = $(this).val();
            var postData = $("#form_filterslotmonitoring").serializeArray();
            postData.push({name:"keyword", value:keyword});
            
            returnSlotMonitoring(postData);
		}
	}); 
    
    $(document).on("change", "#section_id", function(e){
        var section = $("#section_id").val();

        if(section)
        {
            $.ajax({
                url: "/classes/scheduletable",
                type: 'POST',
                data: ({ 'section' : section}),
                success: function(data){
                    //console.log(data);
                    $("#schedule_table").html(data);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }else{
            $("#schedule_table").html('');
        }
    });
});