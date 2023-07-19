$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    // var dataTable = $('#scrollable_table').DataTable({
    //     scrollY: 400,
    //     scrollX: true,
    //     scrollCollapse: true,
    //     paging: false,
    //     ordering: false,
    //     info: false,
    //     searching: false
    // });
    
    // $("#period_id, #program").select2({
	//     dropdownParent: $("#ui_content4")
	// });

    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    function previewImage(event) {
        const imageInput = event.target;
        const previewImage = document.getElementById('previewImage');
  
        if (imageInput.files && imageInput.files[0]) {
          const reader = new FileReader();
          reader.onload = function (e) {
            previewImage.setAttribute('src', e.target.result);
          };
          reader.readAsDataURL(imageInput.files[0]);
        }
    }
});