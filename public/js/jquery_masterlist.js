$(function(){
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});

    $("#period, #program").select2({
	    dropdownParent: $("#ui_content4")
	});

    $.fn.dataTable.Buttons.defaults.dom.button.className = 'btn';

    function getPDFButtonConfig() {
        return {
            extend: 'pdfHtml5',
            download: 'open',
            filename: 'masterlist_pdf',
            orientation: 'landscape', //portrait
            pageSize: 'A4', //A3 , A5 , A6 , legal , letter
            exportOptions: {
            columns: ':visible',
            rows: ':visible'
            },
            customize: customizePDFAlignment,
            className: 'btn btn-danger btn-icon-split mb-2',
            text: '<span class="icon text-white-50"><i class="fas fa-print"></i></span><span class="text">Print PDF</span>',
        };
    }
      
    function customizePDFAlignment(doc) {
        doc.content[1].table.widths = ['5%', '15%', '48%', '20%', '5%', '7%'];
        doc.content[1].table.body[0].forEach(function(h) 
        {
            h.fillColor = 'gray';
        });

        var tableBody = doc.content[1].table.body;

        for (var i = 1; i < tableBody.length; i++) 
        {
            var columnsToAlign = [1, 3, 4, 5];
            columnsToAlign.forEach(function (columnIndex) {
                tableBody[i][columnIndex].alignment = 'center';
            });
        }
    }
      
    $('#scrollable_table_masterlist').DataTable({
        scrollY: 400,
        scrollCollapse: true,
        paging: false,
        ordering: false,
        info: false,
        searching: false,
        // dom: 'Bfrtip',
        // buttons: [
        //     getPDFButtonConfig(),
        // ]
    });

    function returnMasterlist(postData)
    {
        $.ajax({
            url: "/masterlist/filtermasterlist",
            type: 'POST',
            data: postData,
            success: function(response){
                //console.log(response);
                $("#return_masterlist").html(response);

                $('#scrollable_table_masterlist').DataTable({
                    scrollY: 400,
                    scrollCollapse: true,
                    paging: false,
                    ordering: false,
                    info: false,
                    searching: false,
                    // dom: 'Bfrtip',
                    // buttons: [
                    //     getPDFButtonConfig(),
                    // ]
                });
                
                var rowCount = $('#return_masterlist >tr.returned').length;
				$("#totalcount").text(rowCount);
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

    $(document).on("change", ".filter_item", function(){
        var postData = $("#form_filtermasterlist").serializeArray();
       
        returnMasterlist(postData);
    })

    $(document).on("click","#print_masterlist", function(){
		$("#form_filtermasterlist").attr("action", "/masterlist/printmasterlist");
		$("#form_filtermasterlist").submit();
	});

	// $(document).on("click", "#downloadexcel", function(e){
	// 	$("#form_masterlist").attr("action",baseUrl+"/masterlist/downloadexcel");
	// 	$("#form_masterlist").submit();
	// });
});