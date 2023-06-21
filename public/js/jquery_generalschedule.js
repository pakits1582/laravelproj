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
    
    function returnGeneralSchedule(postData)
    {
        $.ajax({
            url: "/generalschedules/filtergeneralschedule",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_generalschedule").html(response);
                
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

    $("#form_filtergeneralschedule").submit(function(e){
        e.preventDefault();
    });

    $(document).on("change", ".filter_item", function(){
        var display = $("#display").val();

        var postData = $("#form_filtergeneralschedule").serializeArray();
        postData.push({name:"display", value:display});
        
        returnGeneralSchedule(postData);
    });

});