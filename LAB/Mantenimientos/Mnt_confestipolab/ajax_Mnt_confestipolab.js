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
        url: 'ctr_Mnt_confestipolab.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { accion: 'getLabAreas' },
        success: function(data) {
            funct(data);
        }
    });
}

function getconfestipolab(funct) {
	var idarea = document.getElementById('cmb-area').value;
    jQuery.ajax({
        url: 'ctr_Mnt_confestipolab.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { accion: 'getconfestipolab', parameters: {idarea: idarea} },
        success: function(data) {
            console.log(data);
            funct(data);
        }
    });
}

function updateRegisters() {
    var idarea = document.getElementById('cmb-area').value;

    var form=[];
    if (jQuery('form#lab-form').serializeArray().length!=0){
       form=jQuery('form#lab-form').serializeArray();
    }
    else{
       form=null;
    }
    jQuery.ajax({
        url: 'ctr_Mnt_confestipolab.php',
        type: 'post',
        dataType: 'json',
        async: false,
        data: {accion: 'updateRegisters', parameters: { idarea: idarea, form: form } },
        success: function(data) {
            if(data.status)
                alert('Registros Ingresados Exitosamente...');
            else
                alert('Error al procesar los registros...');
        }
    });
}
