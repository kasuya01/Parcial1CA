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
        url: 'ctr_Mnt_confexatipolab.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { accion: 'getLabAreas' },
        success: function(data) {
            funct(data);
        }
    });
}

function getconfexatipolab(funct) {
	var idarea = document.getElementById('cmb-area').value;
    jQuery.ajax({
        url: 'ctr_Mnt_confexatipolab.php',
        async: true,
        dataType: 'json',
        type: 'POST',
        data: { accion: 'getconfexatipolab', parameters: {idarea: idarea} },
        success: function(data) {
            console.log(data);
            funct(data);
        }
    });
}

function insertRegisters() {
    var form=[];
    if (jQuery('form#lab-form').serializeArray().length!=0){
       form=jQuery('form#lab-form').serializeArray();
    }
    else{
       form=null;
    }
    jQuery.ajax({
        url: 'ctr_Mnt_confexatipolab.php',
        type: 'post',
        dataType: 'json',
        async: false,
        data: {accion: 'updateRegisters', parameters: { form: form } },
        success: function(data) {
            if(data.status)
                alert('Registros Ingresados Exitosamente...');
            else
                alert('Error al procesar los registros...');
        }
    });
}

function LimpiarCampos(){
    $("[id^=cmbEstablecimiento]").select2("val", "");
}
