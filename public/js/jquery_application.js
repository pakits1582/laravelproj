$(function(){

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
  
      //   var dataTable = $('#scrollable_table').DataTable({
      //       scrollY: 400,
      //       scrollX: true,
      //       scrollCollapse: true,
      //       paging: false,
      //       ordering: false,
      //       info: false,
      //       searching: false
      //   });
        
      //   $("#period_id, #program").select2({
      //     dropdownParent: $("#ui_content4")
      // });
  
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $(document).on("change","#strand",function(e){
		var selected = $(this).val();

		if(selected == 'TECH-VOC'){
			$('#techvocspecify').attr("disabled",false).prop('required',true);
		}else{
			$('#techvocspecify').attr("disabled",true).val("").prop('required',false);
		}
	});

    $(document).on("change","#religion",function(e){
		var selected = $(this).val();

		if(selected == '14'){
			$('#religion_specify').attr("disabled",false);
		}else{
			$('#religion_specify').attr("disabled",true).val("");
		}
	});



});
  