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


});