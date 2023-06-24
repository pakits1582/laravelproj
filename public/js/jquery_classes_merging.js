$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    
    $('#scrollable_table, #scrollable_table_merged_classes').DataTable({
        scrollY: 200,
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        "bAutoWidth": false,
        ordering: false,
        info: false,
        searching: false
    });

    function returnClassSubjects(section)
    {
        $.ajax({
			url: "/classes/sectionclasssubjects",
			type: 'POST',
			data: ({ 'section' : section}),
			success: function(data){
				console.log(data);
				$("#return_classsubjects").html(data);

			},
			error: function (data) {
				console.log(data);
			}
		});
    }

    function searchCodeToMerge(searchcode, class_id)
    {
        $.ajax({
            url: "/classes/searchcodetomerge",
            type: 'post',
            data: {'searchcode':searchcode, 'class_id':class_id},
            //dataType: 'json',
            success: function(data)
            {
                //console.log(data);
                $("#return_search_classtomerge").html(data);

                $('#scrollable_table').DataTable({
                    scrollY: 200,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    "bAutoWidth": false,
                    ordering: false,
                    info: false,
                    searching: false
                });
            }
        });
    }

    function returnMergedClassSubjects(class_id)
    {
        $.ajax({
            url: "/classes/"+class_id+"/viewmergedclasses",
            // type: 'POST',
            // data: {"class_id":class_id},
            success: function(data){
                //console.log(data);
                $("#return_merged_classes").html(data);

                $('#scrollable_table_merged_classes').DataTable({
                    scrollY: 200,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    "bAutoWidth": false,
                    ordering: false,
                    info: false,
                    searching: false
                });
            }
        });
    }

    function unmergeClassSubject(class_id)
    {
        $.ajax({
            url: "/classes/unmergesubject",
            type: 'POST',
            data: {"class_id":class_id},
            success: function(data){
                //console.log(data);
                if(data.success === true)
                {
                    showSuccess(data.message);
                }else{
                    showError(data.message);
                }  
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) == false) {
                    showError('Something went wrong! Can not perform requested action! '+errors.message);
                }
            }
        });	
    }

    $(document).on("click", ".merge", function(e){
        var class_id = $(this).attr("id");

        $.ajax({url: "/classes/"+class_id, success: function(response)
            {
                //console.log(response);

                var number_of_enrolled = response.data.enrolledstudents.length

                if(number_of_enrolled > 0)
                {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Merge</div><div class="message">The selected subject has '+number_of_enrolled+'enrolled students. Continue merging?</div>').dialog({
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
                                            url: "/classes/merge",
                                            type: 'POST',
                                            data: {"class":response.data},
                                            success: function(data){
                                                $('#ui_content').html(data);
                                                $("#merge_modal").modal('show');
                                                
                                                $('#merge_modal').on('shown.bs.modal', function (e) {
                                                    //setTimeout(function () {
                                                        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                                                    //},200);
                                                });
                                            }
                                        });	
                                    }//end of ok button	
                                }//end of buttons
                    });//end of dialogbox
                    $(".ui-dialog-titlebar").hide();
                    //end of dialogbox
                }else{
                    $.ajax({
                        url: "/classes/merge",
                        type: 'POST',
                        data: {"class":response.data},
                        success: function(data){
                            $('#ui_content').html(data);
                            $("#merge_modal").modal('show');

                            $('#merge_modal').on('shown.bs.modal', function (e) {
                                //setTimeout(function () {
                                    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                                //},200);
                            });
                        }
                    });	
                }
            }
        });	
        e.preventDefault();
    });

    $(document).on("keyup","#search_classtomerge",function(e) {
		var searchcode = $(this).val();
        var class_id = $("#class_id").val();
		
		if (e.keyCode == '13')
        {
			if(searchcode !== "")
            {
               searchCodeToMerge(searchcode, class_id);
			}
		}
	});

    $(document).on("submit", "#form_merge_class", function(e){
        var url = $(this).attr('action');
        var checkbox_merge = $(".checkbox_merge:checked");
      	var postData = $(this).serializeArray();

        if(checkbox_merge.length == 0){
			showError('Please select at least one checkbox or class subject to merge!');	
		}else{
            $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Delete</div><div class="message">Are you sure you want to merge selected class subjects? Do you want to continue?</div>').dialog({
                show: 'fade',
                resizable: false,	
                draggable: false,
                width: 350,
                height: 'auto',
                modal: true,
                buttons: {
                        'Cancel':function(){
                            $("#confirmation").dialog('close');
                            $('#cancel').trigger('click');
                        },
                        'OK':function(){
                            $("#confirmation").dialog('close');
                            $.ajax({
                                url: url,
                                type: 'POST',
                                data: postData,
                                dataType: 'json',
                                beforeSend: function() {
                                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take some time, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                                        show: 'fade',
                                        resizable: false,	
                                        width: 350,
                                        height: 'auto',
                                        modal: true,
                                        buttons: false
                                    });
                                    $(".ui-dialog-titlebar").hide();
                                },
                                success: function(data){
                                    $("#confirmation").dialog('close');
                                    //console.log(data);
                                    
                                    if(data.success === true)
                                    {
                                        showSuccess(data.message);
                                        returnClassSubjects($("#section").val());
                                        
                                        var searchcode = $("#search_classtomerge").val();
                                        var class_id   = $("#class_id").val();

                                        searchCodeToMerge(searchcode, class_id);
                                        returnMergedClassSubjects($("#class_id").val());

                                    }else{
                                        showError(data.message);
                                    }  
                                },
                                error: function (data) {
                                    $("#confirmation").dialog('close');
                                    console.log(data);
                                }
                            });
                        }//end of ok button	
                    }//end of buttons
            });//end of dialogbox
        }
        
        e.preventDefault()
    });

    $(document).on("click",".unmerge_class_subject", function(e){
		var class_id = $(this).attr("id");

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Unmerge</div><div class="message">Are you sure you want to unmerge selected class subject? Do you want to continue?</div>').dialog({
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

                        unmergeClassSubject(class_id);
                        returnMergedClassSubjects($("#class_id").val());

                        returnClassSubjects($("#section").val());
                    }//end of ok button	
                }//end of buttons
        });//end of dialogbox

        $(".ui-dialog-titlebar").hide();
			
		e.preventDefault();
	});

    $(document).on("click",".unmerge", function(e){
		var class_id = $(this).attr("id");
        var mergeto = $(this).attr("data-mergeto");

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm Unmerge</div><div class="message">This class subject is currently merged to '+mergeto+'. Are you sure you want to unmerge selected class subject? Do you want to continue?</div>').dialog({
            show: 'fade',
            resizable: false,	
            draggable: false,
            width: 350,
            height: 'auto',
            modal: true,
            buttons: {
                    'Cancel':function(){
                        $(this).dialog('close');

                        $('input.checks').prop('disabled', false).prop('checked', false).closest('tr').removeClass('selected');
                    },
                    'OK':function(){
                        $(this).dialog('close');
                        unmergeClassSubject(class_id);

                        returnClassSubjects($("#section").val());
                    }//end of ok button	
                }//end of buttons
         });//end of dialogbox
        $(".ui-dialog-titlebar").hide();
			
		e.preventDefault();
	});
});