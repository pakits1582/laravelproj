$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#student").select2({
	    // dropdownParent: $("#ui_content3"),
        minimumInputLength: 2,
        tags: false,
        minimumResultsForSearch: 20, // at least 20 results must be displayed
        ajax: {
            url: '/students/dropdownselectsearch',
            dataType: 'json',
            delay: 250,
            data: function (data) {
                return {
                    searchTerm: data.term // search term
                };
            },
            processResults: function(data) {
                return {
                    results: $.map(data, function(item) {
                        return {
                            text: item.text,
                            id: item.id
                        }
                    })
                };
            },
            cache: true
        }
	});

    $("#period").select2({
        dropdownParent: $("#ui_content4")
    });

    function returnStatementofaccount(student_id, period_id)
    {
        if(student_id){
            $.ajax({
                url: "/studentledgers/statementofaccounts",
                type: 'POST',
                data: ({ 'student_id':student_id, 'period_id':period_id, 'has_adjustment':true }),
                success: function(response){
                    console.log(response);
                    $("#return_soa").html(response);

                    $(".datepicker").datepicker(pickerOpts);
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearForm()
                    }
                }
            });
        }
    }

    function clearForm()
    {
        $('.clearable').val("");
        $("#return_soa").html('<h6 class="m-0 font-weight-bold text-black mid">No records to be displayed!</h6>');
    }

    $(document).on("change", "#period", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period").val();

        var period_name = (period_id) ? $("#period option:selected").text() : 'All Periods';
        $("#period_name").text(period_name);

        returnStatementofaccount(student_id, period_id);

    });

    $(document).on("change", "#student", function(){
        var student_id = $("#student").val();
        var period_id = $("#period").val();

        if(student_id){
            $.ajax({
                url: "/students/"+student_id,
                type: 'GET',
                dataType: 'json',
                success: function(response){
                    console.log(response);
                    if(response.data === false)
                    {
                        showError('Student not found!');
                        clearForm();
                    }else{
                        $("#program").val(response.data.values.program.name ? response.data.values.program.name : "");
                        $("#educational_level").val((response.data.values.program.level) ? response.data.values.program.level.code ? response.data.values.program.level.code : "" : "");
                        $("#college").val((response.data.values.program.collegeinfo) ? response.data.values.program.collegeinfo.code ? response.data.values.program.collegeinfo.code : "" : "");
                        $("#curriculum").val(response.data.values.curriculum.curriculum ? response.data.values.curriculum.curriculum : "");
                        $("#year_level").val(response.data.values.year_level ? response.data.values.year_level : "");
                        
                        returnStatementofaccount(student_id, period_id);
                    }
                },
                error: function (data) {
                    console.log(data);
                    var errors = data.responseJSON;
                    if ($.isEmptyObject(errors) === false) {
                        showError('Something went wrong! Can not perform requested action!');
                        clearForm();
                    }
                }
            });
        }

    });

    $(document).on("submit", "#form_studentadjustment", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period").val();
        var postData = $("#form_studentadjustment").serializeArray();
        postData.push({ name: "student_id", value: student_id });
        postData.push({ name: "period_id", value: period_id });

        if(student_id && period_id)
        {
            $.ajax({
                url: "/studentadjustments",
                type: 'POST',
                data: postData,
                dataType: 'json',
                beforeSend: function() {
                    $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Loading Request</div><div class="message">Saving Changes, Please wait patiently.<br><div clas="mid"><img src="/images/31.gif" /></div></div>').dialog({
                        show: 'fade',
                        resizable: false,	
                        width: 350,
                        height: 'auto',
                        modal: true,
                        buttons:false
                    });
                    $(".ui-dialog-titlebar").hide();
                },
                success: function(response)
                {
                    $("#confirmation").dialog('close');
                    console.log(response);
                    if(response.data.success === true)
                    {
                        showSuccess(response.data.message);
            
                    }else{
                        showError(response.data.message);
                    }
    
                    returnStatementofaccount(student_id, period_id);
                },
                error: function (data) {
                   console.log(data);
                   var errors = data.responseJSON;
                   if ($.isEmptyObject(errors) == false) {
                       $.each(errors.errors, function (key, value) {
                           $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                       });
                   }
               }
            });
        }else{
            showError('Student and period are required when adding student adjustment!');
        }

        e.preventDefault();
    });

    $(document).on("click", "#forward_balance", function(e){
        var student_id = $("#student").val();
        var period_id  = $("#period").val();

        $.ajax({
            type: "POST",
            url: "/studentledgers/forwardbalance",
            data: ({ 'student_id':student_id, 'period_id':period_id}),
            success: function(data){
                console.log(data);
                $('#ui_content').html(data);
                $("#modalll").modal('show');
            }
        });
        e.preventDefault();
    });
});