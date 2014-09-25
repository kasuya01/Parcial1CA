jQuery.ajaxSetup({
    error: function(jqXHR, exception) {
        if (jqXHR.status === 0) {
            alert('Not connect.\n Verify Network.');
        } else if (jqXHR.status == 404) {
            alert('Requested page not found. [404]');
        } else if (jqXHR.status == 500) {
            alert('Internal Server Error [500].');
        } else if (exception === 'parsererror') {
            alert('Requested JSON parse failed.');
        } else if (exception === 'timeout') {
            alert('Time out error.');
        } else if (exception === 'abort') {
            alert('Ajax request aborted.');
        } else {
            alert('Uncaught Error.\n' + jqXHR.responseText);
        }
    }
});

function getLabAreas(funct) {
    jQuery.ajax({
        url: 'ctr_Mnt_AreaExamenEstablecimiento.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { accion: 'getLabAreas' },
        success: function(data) {
            funct(data);
        }
    });
}

function getAreaExamenEstablecimiento(funct) {
	var idarea = document.getElementById('cmb-area').value;
    jQuery.ajax({
        url: 'ctr_Mnt_AreaExamenEstablecimiento.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { accion: 'getAreaExamenEstablecimiento', parameters: {idarea: idarea} },
        success: function(data) {
            funct(data);
        }
    });
}
