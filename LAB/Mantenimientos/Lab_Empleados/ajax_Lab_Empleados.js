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
    
    document.getElementById('txtidempleado').value = "";
    document.getElementById('cmbArea').value = "0";
    document.getElementById('txtnombre').value = "";
    document.getElementById('cmbCargo').value = "0";
    document.getElementById('txtlogin').value = "";
    document.getElementById('txtapellido').value = "";
    document.getElementById('cmbPago').value = "0";
    document.getElementById('cmbModalidad').value = "0";
    
    
}

function ValidarCampos()
{

    var resp = true;
     if (document.getElementById('cmbArea').value == "0")
    {
        resp = false;
    }
    if (document.getElementById('cmbModalidad').value == "0")
    {
        resp = false;
    }
     if (document.getElementById('cmbPago').value == "0")
    {
        resp = false;
    }
    
    if (document.getElementById('txtidempleado').value == "")
    {
        resp = false;
    }
    if (document.getElementById('txtnombre').value == "")
    {
        resp = false;
    }
   
    if (document.getElementById('cmbCargo').value == "0")
    {
        resp = false;
    }
    if (document.getElementById('txtlogin').value == "")
    {
        resp = false;
    }
    

    return resp;
}

function IngresarRegistro() { //INGRESAR REGISTROS
   // if (ValidarCampos()) {
        
        idarea = document.getElementById('cmbArea').value;
        idempleado = document.getElementById('txtidempleado').value;
        nomempleado = document.getElementById('txtnombre').value;
        cargo = document.getElementById('cmbCargo').value;
        login = document.getElementById('txtlogin').value;
        modalidad = document.getElementById('cmbModalidad').value;
        txtapellido = document.getElementById('txtapellido').value;
        pagador = document.getElementById('cmbPago').value;
        var opcion = 1;
        Pag = 1;
        //instanciamos el objetoAjax
        ajax = objetoAjax();
        //archivo que realizar� la operacion
        ajax.open("POST", "ctrLab_Empleados.php", true);
        ajax.onreadystatechange = function() {
            if (ajax.readyState == 4) {
                //mostrar resultados en esta capa
                //document.getElementById('divinicial').innerHTML = ajax.responseText;
                alert(ajax.responseText);
                //llamar a funcion para limpiar los inputs
                LimpiarCampos();
                show_event(Pag);
            }
        }
        ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        //enviando los valores
        ajax.send("idempleado=" + idempleado + "&idarea=" + idarea + "&nomempleado=" + nomempleado + "&cargo=" + cargo + "&login=" + login + "&Pag=" + Pag + "&opcion=" + opcion 
                + "&modalidad=" + modalidad+ "&txtapellido=" + txtapellido+"&pagador="+pagador);
    //}

   /* else {
        alert("Complete los datos a Ingresar");
    }*/
}

function pedirDatos(idempleado) { //CARGAR DATOS A MODIFICAR
    //donde se mostrar� el formulario con los datos
    divFormulario = document.getElementById('divFrmModificar');
    divFormularioNuevo = document.getElementById('divFrmNuevo');
    //instanciamos el objetoAjax
    ajax = objetoAjax();

    //uso del medotod POST
    ajax.open("POST", "consulta_Empleados.php");
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar resultados en esta capa
            divFormulario.innerHTML = ajax.responseText
            divFormulario.style.display = "block";
            divFormularioNuevo.style.display = "none";
        }
    }
    //como hacemos uso del metodo POST
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando el codigo
    ajax.send("idempleado=" + idempleado);

}

function enviarDatos() {//FUNCION PARA MODIFICAR
    //donde se mostrar� lo resultados
    divResultado = document.getElementById('divinicial');
    divFormulario = document.getElementById('divFrmModificar');
    divNuevo = document.getElementById('divFrmNuevo');

    //valores de los cajas de texto
    idempleado = document.frmModificar.txtidempleado.value;
    nomempleado = document.frmModificar.txtnombreempleado.value;
    idarea = document.frmModificar.cmbArea.value;
    cargo = document.frmModificar.cmbCargo.value;
    login = document.frmModificar.txtlogin.value;
    modalidad = document.frmModificar.cmbModalidad.value;
    txtapellido = document.frmModificar.txtapellido.value;
    pagador = document.frmModificar.cmbPago.value;
    var opcion = 2;
    Pag = 1;
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //usando del medoto POST
    //archivo que realizar� la operacion ->actualizacion.php
    ajax.open("POST", "ctrLab_Empleados.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idempleado=" + idempleado + "&idarea=" + idarea + "&nomempleado=" + nomempleado + "&cargo=" + escape(cargo) + "&login=" + login +
             "&Pag=" + Pag + "&opcion=" + opcion + "&modalidad=" + modalidad + "&txtapellido=" + txtapellido+"&pagador="+pagador);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            divResultado.style.display = "block";
            //mostrar los nuevos registros en esta capa
            //divResultado.innerHTML = ajax.responseText
            alert(ajax.responseText);
            //una vez actualizacion ocultamos formulario
            divFormulario.style.display = "none";
            divNuevo.style.display = "block";
            LimpiarCampos();
            show_event(1);
        }
    }
}

function AsignarCodigoEmpleado()
{
    Pag = 1;
    opcion = 9;
    idarea = "";
    nomempleado = "";
    idempleado = "";
    cargo = "";
    login = "";
    modalidad = document.getElementById('cmbModalidad').value;
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    ajax.open("POST", "ctrLab_Empleados.php", true);
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idempleado=" + idempleado + "&idarea=" + idarea + "&nomempleado=" + escape(nomempleado) + "&cargo=" + escape(cargo) + "&login=" + login + "&Pag=" + Pag + "&opcion=" + opcion + "&modalidad=" + modalidad);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4)
        {	//mostrar resultados en esta capa
            document.getElementById('divCodigo').innerHTML = ajax.responseText;
        }
    }
}

function show_event(Pag)
{
    opcion = 4;
    idempleado = "";
    idarea = "";
    nomempleado = "";
    cargo = "";
    login = "";
    modalidad = document.getElementById('cmbModalidad').value;
    ajax = objetoAjax();
    ajax.open("POST", 'ctrLab_Empleados.php', true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar resultados en esta capa
            document.getElementById('divinicial').innerHTML = ajax.responseText;
            //alert(ajax.responseText);
        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("idempleado=" + idempleado + "&idarea=" + idarea + "&nomempleado=" + nomempleado + "&cargo=" + cargo + "&login=" + login + "&Pag=" + Pag + "&opcion=" + opcion + "&modalidad=" + modalidad);
}

function show_event_search(Pag)
{
    opcion = 8;
    idarea = document.getElementById('cmbArea').value;
    idempleado = document.getElementById('txtidempleado').value;
    nomempleado = document.getElementById('txtnombre').value;
    cargo = document.getElementById('cmbCargo').value;
    login = document.getElementById('txtlogin').value;
    modalidad = document.getElementById('cmbModalidad').value;
    ajax = objetoAjax();
    ajax.open("POST", 'ctrLab_Empleados.php', true);
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar resultados en esta capa
            document.getElementById('divinicial').innerHTML = ajax.responseText;

        }
    }
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    ajax.send("idempleado=" + idempleado + "&idarea=" + idarea + "&nomempleado=" + nomempleado +
            "&cargo=" + cargo + "&login=" + login + "&Pag=" + Pag + "&opcion=" + opcion + "&modalidad=" + modalidad);
}


function BuscarDatos()

{
    var opcion = 7;
    Pag = 1;
    //valores de los cajas de texto
    idarea      = document.getElementById('cmbArea').value;
    idempleado  = document.getElementById('txtidempleado').value;
    nomempleado = document.getElementById('txtnombre').value;
    cargo       = document.getElementById('cmbCargo').value;
    login       = document.getElementById('txtlogin').value;
    idmodalidad = document.getElementById('cmbModalidad').value;
    txtapellido = document.getElementById('txtapellido').value;
    //instanciamos el objetoAjax
    ajax = objetoAjax();
    //archivo que realizar� la operacion ->actualizacion.php
    ajax.open("POST", "ctrLab_Empleados.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    ajax.send("idempleado=" + idempleado + "&idarea=" + idarea + "&nomempleado=" + nomempleado + "&cargo=" + cargo + "&login=" + login + "&Pag=" + Pag + "&opcion=" + opcion + "&modalidad=" + idmodalidad+ "&txtapellido=" + txtapellido); 
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4) {
            //mostrar los nuevos registros en esta capa
            document.getElementById('divinicial').innerHTML = ajax.responseText;
        }
    }
}


function Estado(idempleado, EstadoCuenta) {

    var opcion = 3;
    modalidad = document.getElementById('cmbModalidad').value;

    ajax = objetoAjax();
    //archivo que realizar� la operacion ->actualizacion.php
    ajax.open("POST", "ctrLab_Empleados.php", true);
    //muy importante este encabezado ya que hacemos uso de un formulario
    ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    //enviando los valores
    // alert (idempleado+"-"+EstadoCuenta);
    ajax.send("idempleado=" + idempleado + "&EstadoCuenta=" + EstadoCuenta + "&opcion=" + opcion + "&modalidad=" + modalidad);

    ajax.onreadystatechange = function() {
        if (ajax.readyState == 1) {
            //document.getElementById('divinicial').innerHTML = ajax.responseText
        }
        if (ajax.readyState == 4) {
            //document.getElementById('divinicial').innerHTML = ajax.responseText;
            show_event(1);
        }
    }
}
