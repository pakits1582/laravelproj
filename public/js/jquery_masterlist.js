$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#period, #program").select2({
	    dropdownParent: $("#ui_content4")
	});

    $('#scrollable_table').DataTable({
        scrollY: 400,
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false
    });


    function returnMasterlist(postData)
    {
        $.ajax({
            url: "/masterlist/filtermasterlist",
            type: 'POST',
            data: postData,
            success: function(response){
                //console.log(response);
                $("#return_masterlist").html(response);

                $('#scrollable_table').DataTable({
                    scrollY: 400,
                    scrollCollapse: true,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false
                });
                
                var rowCount = $('#return_masterlist >tr.returned').length;
				$("#totalcount").text(rowCount);
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

    $(document).on("change", ".filter_item", function(){
        var status = $('input[name="status"]:checked').val();

        var postData = $("#form_filtermasterlist").serializeArray();
        postData.push({name:"status", value:status});
        
        //console.log(postData);
        returnMasterlist(postData);
    })
});