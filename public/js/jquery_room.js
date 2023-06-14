$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#period_id").select2({
        dropdownParent: $("#ui_content4")
    });

    $(document).on("change", ".filter_item", function(){
        var period_id = $('#period_id').val();
        var room_id = $('#room_id').val();

        $.ajax({
            url: "/rooms/filterroomassignment",
            type: 'POST',
            data: ({ 'period_id' : period_id, 'room_id' : room_id }),
            success: function(data){
                //console.log(data);
                $("#return_roomassignment").html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });
    });

    $(document).on("change", "#room_id", function(){
        var room_id = $("#room_id").val();

        $.ajax({
			url: "/rooms/scheduletable",
			type: 'POST',
			data: ({ 'room_id' : room_id}),
			success: function(data){
				console.log(data);
				$("#schedule_table").html(data);
			},
			error: function (data) {
				console.log(data);
			}
		});
    });
    
});