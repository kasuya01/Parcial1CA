/* Spanish translation for the jQuery Timepicker Addon */
/* Written by Ianaré Sévi */
/* Modified by Carlos Martínez */
(function($) {
	$.timepicker.regional['es'] = {
		timeOnlyTitle: 'Elegir una hora',
		timeText: 'Hora',
		hourText: 'Horas',
		minuteText: 'Minutos',
		secondText: 'Segundos',
		millisecText: 'Milisegundos',
		microsecText: 'Microsegundos',
		timezoneText: 'Uso horario',
		currentText: 'Hoy',
		closeText: 'Cerrar',
		timeFormat: 'HH:mm',
		amNames: ['a.m.', 'AM', 'A'],
		pmNames: ['p.m.', 'PM', 'P'],
                prevText: '&#x3c;Ant',
		nextText: 'Sig&#x3e;',
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
		'Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
		weekHeader: 'Sm',
		dateFormat: 'yy-mm-dd',
                showMonthAfterYear: false,
		yearSuffix: '',
                hourGrid: 4,
                minuteGrid: 10,
                timeFormat: 'HH:mm',
              //  controlType: 'select',
              //  timeFormat: 'HH:mm',
		isRTL: false
	};
	$.timepicker.setDefaults($.timepicker.regional['es']);
})(jQuery);
