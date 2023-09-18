//DOCUMENT READY
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

    $(document).on("click", ".evalaution_action", function(e){
        var class_id = $(this).attr("id"); 
        var action   = $(this).attr('data-action');

        
        e.preventDefault();
    });
	
});