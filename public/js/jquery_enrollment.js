$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    // $("#instructor").select2({
	//     dropdownParent: $("#ui_content3")
	// });

    // $("#program_id").select2({
	//     dropdownParent: $("#ui_content2")
	// });

});