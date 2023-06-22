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

    $(document).on("change", "#period_id", function(){
        var period_name = $("#period_id option:selected").text();
        $("#period_name").text(period_name);

    });


});