 /*
  jQuery Document ready
*/
$(function()
{
	$('#basic_example_1').datetimepicker(
	{
		/*
			timeFormat
			Default: "HH:mm",
			A Localization Setting - String of format tokens to be replaced with the time.
		*/
		timeFormat: "hh:mm tt",
		/*
			hourMin
			Default: 0,
			The minimum hour allowed for all dates.
		*/
		hourMin: 8,
		/*
			hourMax
			Default: 23, 
			The maximum hour allowed for all dates.
		*/
		hourMax: 16,
		/*
			numberOfMonths
			jQuery DatePicker option
			that will show two months in datepicker
		*/
		numberOfMonths: 1
		/*
			minDate
			jQuery datepicker option 
			which set today date as minimum date
		*/
		//minDate: 0
		/*
			maxDate
			jQuery datepicker option 
			which set 30 days later date as maximum date
		*/
	//	maxDate: 30
	});
	
	/*
		below code just enable time picker.
	*/	
	$('#basic_example_2').timepicker();
});



//$(function(){
//   
//	
//});

$(function(){
   classdatepick();
   classdate();
});
function classdate() {
       //pongo lo de formato español porque esta es una clase
    //Nose si es lo correcto pero funciona.
    $.datepicker.regional['es'] = {
		closeText: 'Cerrar',
		prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
		currentText: 'Hoy',
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
      $('.date').on('click', function() {
		$(this).datepicker({showOn:'focus', changeMonth: true,showWeek: true,
			changeYear: true,  firstDay: 0, dateFormat: 'yy-mm-dd', yearRange: "-120:+0"}).focus();
                        
	});
}
function classdatepick(){
   $.timepicker.setDefaults( $.timepicker.regional['es']);
      $('.datepicker').on('click', function() {
		$(this).datetimepicker({showOn:'focus', changeMonth: true,showWeek: true,
			changeYear: true,  firstDay: 0, dateFormat: 'yy-mm-dd', yearRange: "-120:+0"}).focus();
                        
	});
}

function mesanio() {

    var dates = $( "#d_fecha" ).datepicker({
      dateFormat: 'yy-mm',
        changeMonth: true,
        changeYear: true,
        numberOfMonths: 1,
        showButtonPanel: true,
        showOn:'focus', 
        autoclose: true,
        yearRange: "-10:+0",
        onSelect: function( selectedDate ) {
				instance = $( this ).data( "datepicker" );
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
				selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		},
 
        onClose: function(dateText, inst) {  
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val(); 
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val(); 
            $(this).val($.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
        },
        onChange:function(dateText, inst){
            $(".ui-datepicker-calendar").hide();
        $("#ui-datepicker-div").position({
            my: "center bottom",
            at: "center bottom",
            of: $('#d_fecha')
        }); 
       
        }
    });

}
