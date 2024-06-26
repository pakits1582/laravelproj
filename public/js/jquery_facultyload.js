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
    
    $("#faculty_id").select2({
	    dropdownParent: $("#ui_content2")
	});

    $("#period").select2({
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

    $(document).on("change", ".filter_item", function(){
        var faculty_id = $("#faculty_id").val();

        var postData = $("#form_filterfacultyload").serializeArray();
        postData.push({name:"faculty_id", value:faculty_id});
        
        returnFacultyload(postData);
    });

    $(document).on("change", "#period", function(){
        var period_name = $("#period option:selected").text();
        $("#period_name").text(period_name);
    });

    $(document).on("change", "#faculty_id", function(e){
        var faculty_id = $("#faculty_id").val();
        var period_id = $("#period").val();

        if(faculty_id)
        {
            $.ajax({
                url: "/facultyloads/scheduletable",
                type: 'POST',
                data: ({ 'period_id' : period_id, 'instructor_id' : faculty_id }),
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

    $(document).on("click", "#other_assignments", function(){
        var period = $("#period").val();

        $.ajax({
            type: "GET",
            url: "/facultyloads/otherassignments/"+period,
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
                url:  "/facultyloads/returnotherassignments",
                data:  ({ 'period_id':period_id, 'instructor_id':instructor_id }),
                cache: false, 
                success: function(response){
                    console.log(response);
                    $("#return_otherassignments").html(response);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    }

    $(document).on("change", "#instructor_id", function(){
        var instructor_id = $("#instructor_id").val();
        var period_id = $("#period_id").val();

        if(instructor_id)
        {
            returnOtherAssignments(period_id, instructor_id);
        }
    });

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

                    $("#assignment, #units").val('');

                    var instructor_id = $("#instructor_id").val();
                    var period_id = $("#period_id").val();

                    returnOtherAssignments(period_id, instructor_id)
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

    $(document).on("click", ".delete_other_assignment", function(e){
        var id = $(this).attr("id");
        var instructor_id = $("#instructor_id").val();
        var period_id = $("#period_id").val();

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to delete other assignment?</div>').dialog({
			show: 'fade',
			resizable: false,	
			draggable: false,
			width: 350,
			height: 'auto',
			modal: true,
			buttons: {
					'Cancel':function(){
						$(this).dialog('close');
					},
					'OK':function(){
						$(this).dialog('close');
						$.ajax({
							url: '/facultyloads/'+id+'/deleteotherassignment',
							type: 'DELETE',
							dataType: 'json',
							success: function(response){
								console.log(response);
								if(response.success === true)
                                {
                                    showSuccess(response.message);
                        
                                }else{
                                    showerror(response.message);
                                }

                                returnOtherAssignments(period_id, instructor_id)
							},
							error: function (data) {
								//console.log(data);
								var errors = data.responseJSON;
								if ($.isEmptyObject(errors) === false) {
									showError('Something went wrong! Can not perform requested action! '+errors.message);
								}
							}
						});
					}//end of ok button	
				}//end of buttons
			});//end of dialogbox
			$(".ui-dialog-titlebar").hide();
		//end of dialogbox

        e.preventDefault();
    });
});