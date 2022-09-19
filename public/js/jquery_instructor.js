//DOCUMENT READY
$(function(){

    $(document).on("click",".pagination a",function(e){
        e.preventDefault();
        //get url and make final url for ajax 
        var url=$(this).attr("href");

        var append=url.indexOf("?")==-1?"?":"&";
        var finalURL=url+append+$("#filter_form").serialize();
        
        filterinstructors(finalURL);
    })

    function filterinstructors(finalURL)
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
        var finalURL="/instructors?"+$("#filter_form").serialize();
        filterinstructors(finalURL);

        e.preventDefault();
    });

    $(document).on("keyup", "#keyword", function(e)
    {
        var finalURL="/instructors?"+$("#filter_form").serialize();
        filterinstructors(finalURL);

        e.preventDefault();
    });

    $(document).on("click", "#download_excel", function(e)
    {
        $("#filter_form").attr("action","/instructors/export");
		$("#filter_form").submit();

        e.preventDefault();
    });

    $(document).on("click", "#generate_pdf", function(e)
    {
        $("#filter_form").attr("action","/instructors/generatepdf");
		$("#filter_form").submit();

        e.preventDefault();
    });


});