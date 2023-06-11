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
    });

    $(document).on("click", "#other_assignments", function(){
        $.ajax({
            type: "GET",
            url: "/facultyloads/otherassignments",
            success: function(data){
                console.log(data);
                $('#ui_content').html(data);
                $("#other_assignments_modal").modal('show');

                $("#instructor_id").select2({
                    dropdownParent: $("#other_assignments_modal")
                });
            }
        });
    });

    function returnOtherAssignments(period_id, instructor_id)
    {
        if(period_id && instructor_id)
        {
            $.ajax({
                type: "POST",
                url:  "/facultyloads/saveotherassignment",
                dataType: 'json',
                data:  postData,
                cache: false, 
                success: function(response){
                    console.log(response);
                    $("#confirmation").dialog('close');
                    $(".errors").html('');
    
                    if(response.success == false)
                    {
                        showError(response.message);
                    }else{
                        showSuccess(response.message);
                        //return other assignments
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    $(document).on("submit", "#form_otherassignments", function(e){
        var postData = $("#form_otherassignments").serializeArray();

        $.ajax({
            type: "POST",
            url:  "/facultyloads/saveotherassignment",
            dataType: 'json',
            data:  postData,
            cache: false, 
            beforeSend: function() {
                $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Transaction<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
                    show: 'fade',
                    resizable: false,	
                    width: 350,
                    height: 'auto',
                    modal: true,
                    buttons: false
                });
                $(".ui-dialog-titlebar").hide();
            },
            success: function(response){
                console.log(response);
                $("#confirmation").dialog('close');
                $(".errors").html('');

                if(response.success == false)
                {
                    showError(response.message);
                }else{
                    showSuccess(response.message);
                    //return other assignments
                }
            },
            error: function (data) {
                console.log(data);
                $("#confirmation").dialog('close');
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! System encountered some errors please check entries!');
                    $.each(errors.errors, function (key, value) {
                        $('#error_' + key).html('<p class="text-danger text-xs mt-1 errors">'+value+'</p>');
                    });
                }
            }
        });
        e.preventDefault();
    });
});