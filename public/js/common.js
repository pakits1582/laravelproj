/*********************************************
*** VARIABLES FOR WINDOWS HEIGHT AND WIDTH ***
*********************************************/
var wWidth  = $(window).width();
var dWidth  = (wWidth < 650) ?  wWidth * 0.9 : wWidth * 0.5;
var wHeight = $(window).height();
var dHeight = wHeight * 0.9;
var sWidth  = $(window).width();
var aWidth  = (sWidth < 650) ?  wWidth * 0.9 : sWidth * 0.5;	
var xWidth  = wWidth * 0.9;
var zWidth  = wWidth * 0.8;
var Height  = wHeight * 0.8;
/*************************************
*** FUNCTION DISPLAY ERROR MESSAGE ***
*************************************/
function showError(msg){
    $('<div class="popups err"><span class="closebuttons"></span><div class="errorpop"></div><h3 class="ui_title_error">Oooopps! Something went wrong!</h3><div class="message">'+msg+'</div></div>')
        .prependTo("#content")/*.delay( 1500 )
        .fadeOut( 500, function(){
            $(this).remove();
        })*/.center();
}
/*************************************
*** FUNCTION DISPLAY ERROR MESSAGE ***
*************************************/
function showSuccess(msg){
    $('<div class="popups succ"><span class="closebuttons"></span><div class="successpop"></div><h3 class="ui_title_success">Successful!</h3><div class="message">'+msg+'</div></div>')
        .prependTo("#content").delay( 1500 )
        .fadeOut( 500, function(){
            $(this).remove();
        }).center();
}	
/*************************************
*** FUNCTION DISPLAY ERROR MESSAGE ***
*************************************/
function showInfo(msg){
    $('<div class="info"><h3 class="popup">Returned Information</h3> ' +msg+ '</div>')
        .prependTo("#content").delay( 1500 )
        .fadeOut( 500, function(){
            $(this).remove();
        }).center();
}	

/***************************
*** FUNCTION DATEPICKERS ***
***************************/
var pickerOpts = { 
dateFormat: $.datepicker.ATOM,
//minDate: "-1m -2w",
changeYear: true,
changeMonth: true,
//maxDate: new Date()
//maxDate: "-15y",
};	

var pickerOpts3 = { 
dateFormat: $.datepicker.ATOM,
//minDate: new Date(),
maxDate: new Date(),
changeYear: true,
changeMonth: true
};	

var pickerOpts2 = { 
dateFormat: $.datepicker.ATOM,
//minDate: new Date(),
changeYear: true,
changeMonth: true,
maxDate: new Date(),
dateFormat:'mm-dd-yy'
//maxDate: "-18y",
};	

var pickerOpts4 = { 
dateFormat: $.datepicker.ATOM,
//minDate: new Date(),
//maxDate: new Date(),
changeYear: true,
changeMonth: true
};	

/*$("#examto").datepicker('option', 'minDate', new Date($.now()));*/
$("#today, .calendar").datepicker(pickerOpts2);
$(".date_picker").datepicker(pickerOpts3);
$("#bdate, #bday").datepicker(pickerOpts);
$(".picker_date").datepicker(pickerOpts4);
$(".datepicker").datepicker(pickerOpts);

/*************************************
*** FUNCTION POSITION CENTER DIV   ***
*************************************/
jQuery.fn.center = function (){
    this.css("position","fixed");
    this.css("top", ($(window).height() / 2) - (this.outerHeight() / 2));
    this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
    return this;
}
/*************************************
*** FUNCTION CLEAR ALL FORM FIELDS ***
*************************************/
function clearForm(form){
  $(':input',form)
  .not(':button, :submit, :reset, :hidden, :radio, :checkbox')
  .val('');
  $(':checkbox').prop('checked',false);
}	

function clearForm2(form){
  $(':input',form)
  .not(':button, :submit, :reset, :radio, :checkbox')
  .val('');
  $(':checkbox').prop('checked',false);
}	
/******************************************
**** FUNCTION CAPITALIZED FIRST LETTER ****
***************************=**************/
function ucwords(str,force){
    str=force ? str.toLowerCase() : str;
    return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
    function(firstLetter){
    return firstLetter.toUpperCase();
    });
}

//DOCUMENT READY
$(function(){
/************************************
*** FUNCTION CLOSE UI DIALOG BOX  ***
************************************/
    $(document).on("click",".closebuttons", function(){
        $(".popups").fadeOut( 500, function(){
            $(this).remove();
        });
    });
/*************************************
*** FUNCTION CHANGE CURRENT PERIOD ***
*************************************/
    $(document).on("click",".current_period", function(e){
        $.ajax({url: "/periods/changeperiod",success: function(data){
                $('#ui_content').html(data);
                $("#modalll").modal('show');
            }
        });	
        e.preventDefault();
    });

/*************************************
*** FUNCTION UPDATE CURRENT PERIOD ***
*************************************/
    $(document).on("submit",'#changeperiod_form', function(e) {
        var postData = $(this).serializeArray();
        var url = $(this).attr('data-action');

        $.ajax({
        url: url,
        type: 'POST',
        data: postData,
        dataType: 'json',
        success: function(data){
            $('.alert').remove();

            $("#changeperiod_form").prepend('<p class="alert '+data.alert+'">'+data.message+'</p>')
            window.setTimeout(function(){
                location.reload();
            }, 1500);	
        },
        error: function (data) {
            //console.log(data);
            var errors = data.responseJSON;
            if ($.isEmptyObject(errors) == false) {
                $.each(errors.errors, function (key, value) {
                    $('#error_' + key).html('<p class="text-danger text-xs mt-1">'+value+'</p>');
                });
            }
        }
        });	
        e.preventDefault();
    });

    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });

    $(document).on("click",".pagination a",function(e){
        e.preventDefault();
        //get url and make final url for ajax 
        var url=$(this).attr("href");
  
        var append=url.indexOf("?")==-1?"?":"&";
        var finalURL=url+append+$("#filter_form").serialize();
        
        filterRecords(finalURL);
    })
  
    $(document).on("change", ".dropdownfilter", function(e)
    {
        var field = $("#filter_form").attr('data-field');

        var finalURL="/"+field+"?"+$("#filter_form").serialize();
        filterRecords(finalURL);

        e.preventDefault();
    });
  
    $(document).on("keyup", "#keyword", function(e)
    {
        var field = $("#filter_form").attr('data-field');

        var finalURL="/"+field+"?"+$("#filter_form").serialize();
        filterRecords(finalURL);

        e.preventDefault();
    });

    function filterRecords(finalURL)
    {
        $.ajax({
            url: finalURL,
            success: function(data){
                console.log(data);
                $('#table_data').html(data);
            },
            error: function (data) {
                console.log(data);
            }
        });	
    }

    $(document).on("click", ".user_action", function(e)
    {
        var id = $(this).attr('id');
        var action = $(this).attr('data-action');

        $("#confirmation").html('<div class="confirmation"></div><div class="ui_title_confirm">Confirm '+action+'</div><div class="message">Are you sure you want to perform '+action+'?</div>').dialog({
			show: 'fade',
			resizable: false,	
			draggable: false,
			width: 350,
			height: 'auto',
			modal: true,
			buttons: {
					'Cancel':function(){
						$(this).dialog('close');
					},
					'OK':function(){
						$(this).dialog('close');
						$.ajax({
							url: 'users/'+id+'/useraction/'+action,
							type: 'POST',
							dataType: 'json',
							success: function(data){
								showSuccess(data.message);
                                var field = $("#filter_form").attr('data-field');
                                var currentpage=$('.pagination li.active').text();
                                var finalURL="/"+field+"?page="+currentpage+"&"+$("#filter_form").serialize();
                                filterRecords(finalURL);
							},
							error: function (data) {
                                console.log(data);
								var errors = data.responseJSON;
								if ($.isEmptyObject(errors) == false) {
									showError('Something went wrong! Can not perform requested action! '+errors.message);
								}
							}
						});
					}//end of ok button	
				}//end of buttons
		});//end of dialogbox
		$(".ui-dialog-titlebar").hide();
		//end of dialogbox
        e.preventDefault();
    });

});