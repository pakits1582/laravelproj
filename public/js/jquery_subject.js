//DOCUMENT READY
$(function(){
    
    $('.datepicker').datepicker(pickerOpts);  

    $(document).on("click",".pagination a",function(e){
        e.preventDefault();
        //get url and make final url for ajax 
        var url=$(this).attr("href");

        var append=url.indexOf("?")==-1?"?":"&";
        var finalURL=url+append+$("#filter_form").serialize();
        
        filterprograms(finalURL);
    })

    function filterprograms(finalURL)
    {
        $.ajax({
            url: finalURL,
            success: function(data){
                $('#table_data').html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });	
    }

    $(document).on("change", ".dropdownfilter", function(e)
    {
        var finalURL="/subjects?"+$("#filter_form").serialize();
        filterprograms(finalURL)

        e.preventDefault();
    });

    $(document).on("keyup", "#keyword", function(e)
    {
     var finalURL="/subjects?"+$("#filter_form").serialize();
        filterprograms(finalURL)

        e.preventDefault();
    });

    $(document).on("click", "#download_excel", function(e)
    {
        $("#filter_form").attr("action","/subjects/export");
		$("#filter_form").submit();

        e.preventDefault();
    });

    $(document).on("click", "#generate_pdf", function(e)
    {
        $("#filter_form").attr("action","/subjects/generatepdf");
		$("#filter_form").submit();

        e.preventDefault();
    });


});