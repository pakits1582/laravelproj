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

    $("#period, #program, #class, .additional_fee").select2({
        dropdownParent: $("#ui_content4")
    });

    function parseCurrency(num) {
    	return parseFloat( num.replace( /,/g, '') );
	}

	function totalfeeamount(){
		var sum = 0;
		$('.fee_amount').each(function(i, obj) {
			var value = parseCurrency($(this).val());
		    // add only if the value is number
		      if(!isNaN(value) && value.length != 0) {
		        sum += parseFloat(value);
		    }
		});
		var total = sum.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
		$("#totalfee").text(total);
	}

	$(document).on("blur keyup",".fee_amount", function(){
		totalfeeamount();
	});

    $(document).on("click", "#add_fee", function(e){
        var options = $("#additional_fees > option").clone();

        var select =    '<div class="row align-items-center mb-1">';
            select +=       '<div class="col-md-1">';
            select +=       '   <label for="term" class="m-0 font-weight-bold text-primary">Fee</label>';
            select +=       '</div>';
            select +=       '<div class="col-md-7">';
            select +=       '   <select name="fees[]" class="form-control additional_fee" id="" required>';
                                    options.each(function() {
                                        var optionValue = $(this).val();
                                        var optionText = $(this).text();
                                        select += '<option value="'+optionValue+'">'+optionText+'</option>';
                                    });
            select +=       '   </select>';
            select +=       '</div>';
            select +=       '<div class="col-md-3">';
            select +=       '   <input type="text" name="amount[]" id="" class="fee_amount form-control" placeholder="Amount" pattern="^[0-9]+(?:\.[0-9]{1,2})?$" title="CDA Currency Format - no currency sign and no comma(s) - cents (.##) are optional" autocomplete="off">';
            select +=       '</div>';
            select +=       '<div class="col-md-1">';
            select +=       '   <a href="#" id="" class="removeaddfee btn btn-danger btn-circle btn-sm" title="Remove">';
            select +=       '       <i class="fas fa-times"></i>';
            select +=       '   </a>';
            select +=       '</div>';
            select +=   '</div>';

        $('#postcharge_fees').append(select);
        $(".additional_fee").select2();  

        e.preventDefault();
    });

    $(document).on("click", ".removeaddfee", function(e){
		$(this).closest("div.row").remove();
        totalfeeamount();
        e.preventDefault();
	});

    $(document).on("submit", "#form_filterstudent", function(e){
        var postData = $(this).serializeArray();

        $.ajax({
            url: "/postcharges/filterstudents",
            type: 'POST',
            data: postData,
            success: function(data){
                console.log(data);
                $("#return_filteredstudents").html(data);
            },
            error: function (data) {
                console.log(data);
                var errors = data.responseJSON;
                if ($.isEmptyObject(errors) === false) {
                    showError('Something went wrong! Can not perform requested action!');
                    $("#return_filteredstudents").html('<tr><td class="mid" colspan="5">No records to be displayed!</td></tr>');

                }
            }
        });
        e.preventDefault();
    });
});