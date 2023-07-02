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

    $(document).on("change",".checkedtransfer", function(){
		if($(this).is(':checked')){
			$(this).closest('tr').addClass('selected');
		}else{	
			$(this).closest('tr').removeClass('selected');
		}
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
                //dataType: 'json',
                beforeSend: function() {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        width: 350,
                        height: 'auto',
                        modal: true,
                        buttons:false
                    });
                    $(".ui-dialog-titlebar").hide();
                },
                success: function(data){
                    //console.log(response);
                    $("#confirmation").dialog('close');
                    $('#return_class_info').html(data);

                    $('#scrollable_table_classinfo').DataTable({
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

    $(document).on("click", "#transfer_student", function(e){
        var class_id = $(".viewclasslist:checked").attr("value");

        if(!class_id)
        {
            showError('Please select class!');
            return false;
        }
		if($(".checkedtransfer:checked").length == 0)
        {
			showError('Please select atleast one checkbox/student to transfer!');	
		}else{
			var enrollment_ids = $(".checkedtransfer:checked").map(function() {
						return $(this).attr("value");
				}).get();

			if(!enrollment_ids)
            {
				showError('Something went wrong! Please refresh page!');
			}else{
                $.ajax({
                    url: "/classlists/transferstudents",
                    type: 'POST',
                    data: ({ 'class_id' : class_id, 'enrollment_ids' : enrollment_ids}),
                    //dataType: 'json',
                    success: function(data){
                        $('#modal_container').html(data);
                        $("#transfer_students").modal('show');

                        $('#scrollable_table_transfer').DataTable({
                            scrollY: 200,
                            scrollX: true,
                            scrollCollapse: true,
                            paging: false,
                            "bAutoWidth": false,
                            ordering: false,
                            info: false,
                            searching: false
                        });
    
                        $('#transfer_students').on('shown.bs.modal', function (e) {
                            //setTimeout(function () {
                                $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
                            //},200);
                        });
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });
			}
		}
		e.preventDefault();
	});

    $(document).on("keyup", "#class_code_keyword", function(e){
		if (e.keyCode == '13')  
        {
			var keyword = $(this).val();

			$.ajax({
				url: "/classlists/searchtransfertoclass/",
				type: 'post',
				data: ({"keyword":keyword}),
				dataType: 'json',
				cache: false,
				beforeSend: function() {
					$("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">This may take several minutes, Please wait patiently.<br><div clas="mid"><img src="images/31.gif" /></div></div>').dialog({
						show: 'fade',
						resizable: false,	
						width: 'auto',
						height: 'auto',
						modal: true,
						buttons: false
					});
					$(".ui-dialog-titlebar").hide();
				},
				success: function(data){
					$('#confirmation').dialog('close'); 
                    console.log(data);

                    if(data.success == false)
                    {
                        showError(data.message);
                        $(".clearable").val('');
                    }else{
                        $("#subject_code").val(data.curriculumsubject.subjectinfo.code ?? '');
                        $("#units").val(data.units);
                        $("#description").val(data.curriculumsubject.subjectinfo.name ?? '');
                        $("#schedule").val(data.schedule.schedule ?? '');
                        $("#instructor").val((data.instructor_id) ? data.instructor.last_name+', '+data.instructor.first_name+' '+data.instructor.name_suffix+' '+data.instructor.middle_name : '');
                        $("#transferto_class_id").val(data.id);
                    }
				}
			});	
		}
	});

    $(document).on("submit", "#form_transferstudents", function(e){
        var postData = $(this).serializeArray();

        console.log(postData);
        
        e.preventDefault();
    });

});