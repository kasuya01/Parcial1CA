function objetoAjax() {
    var xmlhttp = false;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

function LimpiarCampos() {

    document.frmnuevo.txtForm.value = "";
    document.frmnuevo.txtForm.focus();
}

function ValidarCampos(tipo)
{
    var error = [];

    switch (tipo) {
        case 1:
            if (document.getElementById('txtForm').value == "") {
                error.push('Nombre del Formulario');
            }
            if (document.getElementById('cmbPrograma').value == "0") {
                error.push('Programa');
            }
            break;
        case 2:
            if (frmModificar.Formulario.value == "") {
                error.push('Nombre del Formulario');
            }
            if (document.frmModificar.cmbPrograma.value == "0") {
                error.push('Programa');
            }
            break;
    }
    return error;
}

function IngresarRegistro() { //INGRESAR REGISTROS
    var error = ValidarCampos(1);
    if (error.length == 0) {//donde se mostrar� lo resultados
        divResultado = document.getElementById('divinicial');
        //valores de los inputs
        //IdPrograma=document.frmnuevo.txtIdPrograma.value;
        Formulario = document.frmnuevo.txtForm.value;
        IdPrograma = document.frmnuevo.cmbPrograma.value;
        //alert(IdPrograma+"****"+Formulario);
        var opcion = 1;
        var Pag = 1;
        //instanciamos el objetoAjax
        ajax = objetoAjax();
        //uso del medotod POST
        //archivo que realizar� la operacion
        ajax.open("POST", "ctrLab_Formularios.php", true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4) {
                //mostrar resultados en esta capa
                //divResultado.innerHTML = ajax.responseText;
                alert(ajax.responseText);
                //llamar a funcion para limpiar los inputs
                show_event(1);
                LimpiarCampos();
            }
        }
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("&Formulario=" + escape(Formulario) + "&IdPrograma=" + IdPrograma + "&Pag=" + Pag + "&opcion=" + opcion);
    } else {
        var errorMessage = "";
        for (i = 0; i < error.length; i++) {
            errorMessage += error[i] + "\n";
        }
        alert("Error...\n\nLos siguientes campos no han sido completados: \n\n" + errorMessage);
    }
}

function pedirDatos(IdFormulario) {   //CARGAR DATOS A MODIFICAR
    //donde se mostrar� el formulario con los datos
    divFormulario = document.getElementById('divFrmModificar');
    divFormularioNuevo = document.getElementById('divFrmNuevo');
    divInicial = document.getElementById('divinicial');
    //alert(IdFormulario);
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //uso del medotod POST
    ajax.open("POST", "consulta_Formularios.php");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar resultados en esta capa
            divFormulario.innerHTML = ajax.responseText
            divFormulario.style.display = "block";
            divFormularioNuevo.style.display = "none";
            divInicial.style.display = "block";
        }
    }
    //como hacemos uso del metodo POST
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando el codigo 
    ajax.send("IdFormulario=" + IdFormulario);

}

function enviarDatos() {    //FUNCION PARA MODIFICAR
    var error = ValidarCampos(2);
    if (error.length == 0) {
        //donde se mostrar� lo resultados
        divResultado = document.getElementById('divinicial');
        divFormulario = document.getElementById('divFrmModificar');
        divNuevo = document.getElementById('divFrmNuevo');

        //valores de los cajas de texto
        IdFormulario = document.frmModificar.IdFormulario.value;
        Formulario = document.frmModificar.Formulario.value;
        IdPrograma = document.frmModificar.cmbPrograma.value;
        var opcion = 2;
        Pag = 1;
        //instanciamos el objetoAjax
        ajax = objetoAjax();
        //usando del medoto POST
        ajax.open("POST", "ctrLab_Formularios.php", true);
        //muy importante este encabezado ya que hacemos uso de un formulario
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        //alert(IdFormulario+"***"+Formulario+"***"+IdPrograma);
        ajax.send("IdFormulario=" + IdFormulario + "&Formulario=" + Formulario + "&IdPrograma=" + IdPrograma + "&Pag=" + Pag + "&opcion=" + opcion);
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4) {
                divResultado.style.display = "block";
                //mostrar los nuevos registros en esta capa
                //divResultado.innerHTML = ajax.responseText
                alert(ajax.responseText);
                //una vez actualizacion ocultamos formulario
                divFormulario.style.display = "none";
                divNuevo.style.display = "block";
                show_event(1);

            }
        };
    } else {
        var errorMessage = "";
        for (i = 0; i < error.length; i++) {
            errorMessage += error[i] + "\n";
        }
        alert("Error...\n\nLos siguientes campos no han sido completados: \n\n" + errorMessage);
    }
}

function eliminarDato(IdFormulario) { //FUNCION PARA ELIMINACION
    //donde se mostrar� el resultado de la eliminacion
    divResultado = document.getElementById('divinicial');
    //divInicio=document.getElementById('divinicial');
    var opcion = 3;
    Pag = 1;
    //usaremos un cuadro de confirmacion	
    var eliminar = confirm("De verdad desea eliminar este dato?")
    if (eliminar) {
        //instanciamos el objetoAjax
        ajax = objetoAjax();
        ajax.open("POST", "ctrLab_Formularios.php", true);
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("IdFormulario=" + IdFormulario + "&Pag=" + Pag + "&opcion=" + opcion);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4) {
                //mostrar resultados en esta capa
                //divResultado.innerHTML = ajax.responseText;
                alert(ajax.responseText);
                show_event(1);
            }
        }

    }
}

function BuscarCodigo()
{
    var opcion = 5;
    Pag = 1;
    //valores de los cajas de texto
    //IdFormulario = document.getElementById('txtIdForm').value;
    Formulario = document.getElementById('txtForm').value;
    cmbFormulario = document.getElementById('cmbPrograma').value;
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //archivo que realizar� la operacion ->actualizacion.php
    ajax.open("POST", "ctrLab_Formularios.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores

    ajax.send("Pag=" + Pag + "&opcion=" + opcion + "&Formulario=" + Formulario + "&cmbFormulario=" + cmbFormulario);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar los nuevos registros en esta capa
            document.getElementById('divinicial').innerHTML = ajax.responseText;
        }
    }
}

function show_event(Pag)
{
    opcion = 4;

    //document.getElementById('divinicial').innerHTML = 'cargando eventos...';
    ajax = objetoAjax();
    ajax.open("POST", "ctrLab_Formularios.php", true);
    ajax.onreadystatechange = function() {
        //ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
        if (ajax.readyState == 4) {
            //mostrar resultados en esta capa
            document.getElementById('divinicial').innerHTML = ajax.responseText
        }
    }
    //alert(Pag);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("Pag=" + Pag + "&opcion=" + opcion);
}

function show_event_search(Pag)
{
    opcion = 5;
    IdFormulario = document.getElementById('txtIdForm').value;
    Formulario = document.getElementById('txtForm').value;
    ajax = objetoAjax();
    ajax.open("POST", "ctrLab_Formularios.php", true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar resultados en esta capa
            document.getElementById('divinicial').innerHTML = ajax.responseText;
        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("Pag=" + Pag + "&opcion=" + opcion + "&Formulario=" + escape(Formulario) + "&IdFormulario=" + IdFormulario);
}

function Estado(idform, condicion) {

    var opcion = 6;


    ajax = objetoAjax();
    //archivo que realizar� la operacion ->actualizacion.php
    ajax.open("POST", "ctrLab_Formularios.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    //alert("aqui"+idform+"-"+condicion+"-"+opcion);
    ajax.send("idform=" + idform + "&condicion=" + condicion + "&opcion=" + opcion);

    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {

        }
        if (ajax.readyState == 4) {
            //alert(ajax.responseText);
            show_event(1);
        }
    }
}
