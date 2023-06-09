$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $('#scrollable_table').DataTable({
        scrollY: '65vh',
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false
    });

    $("#faculty_id").select2({
	    dropdownParent: $("#ui_content2")
	});

    function returnFacultyload(postData)
    {
        $.ajax({
            url: "/facultyloads/filterfacultyload",
            type: 'POST',
            data: postData,
            success: function(response){
                console.log(response);
                $("#return_facultyload").html(response);
                
                var columnSum = 0;

                $('#return_facultyload td.loadunits').each(function() {
                    var text = $(this).text();
                    var value = parseFloat(text);

                    if (!isNaN(value)) {
                        columnSum += value;
                    }
                });

				$("#totalunits").text(columnSum);
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
        var faculty_id = $("#faculty_id").val();

        var postData = $("#form_filterfacultyload").serializeArray();
        postData.push({name:"faculty_id", value:faculty_id});
        
        //console.log(postData);
        returnFacultyload(postData);
    })
});