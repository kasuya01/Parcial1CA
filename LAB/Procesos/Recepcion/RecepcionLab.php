<?php session_start();
include_once("../../../Conexion/ConexionBD.php");
include_once("cls_recepcion.php");
include_once("../../Funciones/clsFuncionesGenerales.php");
//include_once("cls_recepcion.php");
if(isset($_SESSION['Correlativo'])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$idtipoestab=$_SESSION['id_tipo_estab'];
$hospital=$_SESSION['hospital'];
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$recepcion = new clsRecepcion;
//$funcGeneral = new clsRecepcion;
$funcGeneral = new clsFuncionesGenerales();

// echo $lugar;
/***************/
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
if ($nivel==4){
	include_once ('../../../PaginaPrincipal/index_laboratorio42.php');}
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); }
?><br>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>...::: Ingreso de Solicitud :::...</title>
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<style type="text/css">

<!--
.Estilo1 {color: #ECE9D8}
.Estilo3 {
	font-size: 18px;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
}
-->
</style>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<style type="text/css">
			*{ font-size:12px; font-family:verdana; }
			h1 { font-size:22px; }
		/*	//input { width:250px; border: 2px solid #CCC; line-height:20px;height:20px; border-radius:3px; padding:2px; }*/
		</style>
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
<script  type="text/javascript">
       $(document).ready(function() {
         $("#cmb_establecimiento").select2({
           allowClear: true,
           dropdownAutoWidth: true
        });
         $("#CmbServicio").select2({
           allowClear: true,
           dropdownAutoWidth: true
        });
        $("#cmbSubServ").select2({
            allowClear: true,
            dropdownAutoWidth: true
        });
         $("#cmbMedico").select2({
            allowClear: true,
            dropdownAutoWidth: true
        });
    });

</script>
<script  type="text/javascript">
var miPopup
function fillEstablecimiento(idtipoEstab){
  accion=8;
        idext=document.getElementById('IdEstablecimientoExterno').value;
        if (idext == '' || idext==null || idext=='""'){
            idext=0;
        }

	if(idtipoEstab==0){
	  alert("Seleccione un tipo de establecimiento!");
	}
        else{

            if (window.XMLHttpRequest) {
                    sendReq = new XMLHttpRequest();
            } else if(window.ActiveXObject) {
                    sendReq = new ActiveXObject("Microsoft.XMLHTTP");
            } else{
                    alert("no pudo crearse el objeto")
            }

              sendReq.onreadystatechange = procesaEsp;
              sendReq.open("POST", 'ajax_recepcion.php', true);
              sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

              var param = 'Proceso=fillEstab';
              param += '&idtipoEstab='+idtipoEstab;
              param += '&idext='+idext;

              sendReq.send(param);
	}
}

function fillTipoEstablecimiento(idest){
  accion=12;

	if(idest==0){
	  return false;
	} else{

	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }
         // alert ('filltipoestablecimiento:'+idest)

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=fillTipoEstab';
	  param += '&idestab='+idest;
       //   alert (param)
	  sendReq.send(param);
	}
}

function fillservicio(idserv){
  accion=9;
//alert(idserv);
   if(idserv==0){
     alert("Seleccione una procedencia");
   }else{
         if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=fillServicio';
	  param += '&idserv='+idserv;
	  sendReq.send(param);

   }

}


function fillMed(idSubEsp){
  accion=1;
  //alert(idSubEsp);
	if(idSubEsp==0){
	 	alert("Seleccione una especialidad!");
	} else{

	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=fillMed';
	  param += '&idSubEsp='+idSubEsp;
	  sendReq.send(param);
	}
}//fillMed

function fillestudios(idarea){
accion=2;

	if(idarea==0){
	  alert("Seleccione una area!");
	} else{

	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=fillestudios';
	  param += '&id_area='+idarea;
	  sendReq.send(param);
	}
}//fillestudios

function fillmuestra(idestudio){
accion=3;

	if(idestudio==0){
	  alert("Seleccione un estudio!");
	} else{

	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=fillmuestra';
	  param += '&id_estudio='+idestudio;
	  sendReq.send(param);
	}
}

function fillorigen(idmuestra){
accion=4;

	if(idmuestra==0){
	  alert("Seleccione un estudio!");
	} else{

	  if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=fillorigen';
	  param += '&id_muestra='+idmuestra;
	  sendReq.send(param);
	}
}

function agregarexamenes(){
accion=5;

	id_area=document.getElementById('cmbarea').value;
	id_examen=document.getElementById('cboEstudio').value;
	id_tipomuestra=document.getElementById('cboMuestra').value;
	id_origenmuestra=document.getElementById('cboOrigen').value;
	indicaciones=document.getElementById('Indicacion').value;

	if ((document.getElementById('cmbarea').value==0) && (document.getElementById('cboEstudio').value==0) && (document.getElementById('cboMuestra').value==0) && (document.getElementById('cboOrigen').value==0)){
		alert("Debe Ingresar al menos un EXAMEN!!");
		return false;
	}

	if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=agregarexamenes';
	  param += '&id_origenmuestra='+id_origenmuestra+'&id_tipomuestra='+id_tipomuestra+'&id_examen='+id_examen+'&id_area='+id_area+'&indicaciones='+indicaciones;
	  sendReq.send(param);

}

function searchpac(){
//accion=6;
//limpiar();
	nec=document.getElementById('txtexp').value;
	idext=document.getElementById('IdEstablecimientoExterno').value;


        if (idext == '' || idext==null || idext=='""'){
            idext=0;
        }
    //    alert(nec+' - '+idext)
//	if(!IsNumeric(document.getElementById('txtexp').value)){
//		alert('Por favor solo introduzca numeros en este campo')
//		document.getElementById('txtexp').focus();
//		return false;
//	}

	if(document.getElementById('txtexp').value==""){
		showDialogMsg("Error: ", "Debe Ingresar un Numero de Expediente", 'dialog-error', 'Aceptar');
		//alert(".: Error: ");
		return false;
	}

	if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }
MostrarDatos(nec, idext);
	/*  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=searchpac';
	  param += '&nec='+nec+'&idext='+idext;
        //  param += '&idext='+idext;
         // alert (param)
	  sendReq.send(param);*/
}


 function BuscarExistenciaSolicitud(){
    accion=13;

    var IdNumeroExp = document.getElementById("IdNumeroExp").value;
    var IdEstablecimiento = document.getElementById("cmbEstablecimiento").value;// establecimiento que solicita el estudio
    var lugar = document.getElementById("lugar").value;
    var IdSubServicio = document.getElementById("cmbSubServ").value;
    var IdEmpleado = document.getElementById("cmbMedico").value;
    var FechaConsulta = document.getElementById("txtconsulta").value;
    var FechaRecepcion = document.getElementById("txtfrecepcion").value;
   // var IdCitaServApoyo = document.getElementById("IdCitaServApoyo").value;
  //  var Sexo = document.getElementById("tiposexo").value;
    var idexpediente = document.getElementById("idexpediente").value;
        // alert ("algo :"+idexpediente);

     var mensaje="";
    if (FechaConsulta=='' || FechaRecepcion==''){
        mensaje +="Ingrese los datos de la Fecha de la consulta y/o recepcion";
    }
    if (IdSubServicio==0){
        mensaje +="\nIngrese el dato del subservicio"
    }
    if (mensaje !=''){
		showDialogMsg("Advertencia: ", mensaje, 'dialog-warning', 'Cerrar');
       //alert (mensaje)
        return false;
    }

    if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	  }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=VerificarSolicitud';
	  param += '&IdNumeroExp='+IdNumeroExp+'&IdEstablecimiento='+IdEstablecimiento+
                   '&lugar='+lugar+'&IdSubServicio='+IdSubServicio+
                   '&FechaConsulta='+FechaConsulta+'&idexpediente='+idexpediente+'&IdEmpleado='+IdEmpleado+"&FechaRecepcion="+FechaRecepcion;
        //  param += '&idext='+idext;
        // alert (param);
	  sendReq.send(param);

}

function guardar(){
accion=7;
	nec=document.getElementById('txtexp').value;
	establecimiento=document.getElementById('cmbEstablecimiento').value;
	Serv=document.getElementById('CmbServicio').value;
	SubServ=document.getElementById('cmbSubServ').value;
	med=document.getElementById('cmbMedico').value;
	fcon=document.getElementById('txtconsulta').value;

        //alert (nec+"-"+establecimiento+"-"+Serv+"-"+SubServ+"-"+med+"-"+fcon);

	if(document.getElementById('txtexp').value==""){
		alert(".: Error: Debe Ingresar un Numero de Expediente");
		return false;
	}

	if(!IsFecha(document.getElementById('txtconsulta').value)){
		alert('Por favor solo introduzca numeros en el campo Fecha de Consulta')
		document.getElementById('txtconsulta').focus();
		return false;
	}

	if (window.XMLHttpRequest)
        {
		sendReq = new XMLHttpRequest();
	}
        else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
                } else{
                      alert("no pudo crearse el objeto")
                }

	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=guardar';
	  param +='&nec='+nec+'&establecimiento='+establecimiento+'&Serv='+Serv+'&SubServ='+SubServ+'&med='+med+'&fcon='+fcon;
	  sendReq.send(param);
}


function abreVentana(nec, idest){ //datospacfisttime
    window.close("../RegistroExternos/Busqueda.php");
    estabnombre=$('#cmb_establecimiento option:selected').text();
    idexpedienteex=$("#idexpediente").val();
     if (idexpedienteex=='' || idexpedienteex==null){
       idexpedienteex=0;
    }
    miPopup = window.open("../RegistroExternos/Busqueda.php?nec="+nec+"&idest="+idest+"&estabnombre="+estabnombre+"&idexpedienteex="+idexpedienteex,"miwin","width=1000,height=550,scrollbars=yes");
    miPopup.focus();
}

function MostrarDatos(nec, idext){
accion=10;
	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');

	  var param = 'Proceso=DatosPaciente';
	  param +='&nec='+nec;
	  param +='&idext='+idext;
         // alert('Param: '+param)
	  sendReq.send(param);

}// fin MostrarDatos

function NoEncontrado(nec)
{
        accion=11;
	  sendReq.onreadystatechange = procesaEsp;
	  sendReq.open("POST", 'ajax_recepcion.php', true);
	  sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
          idext=document.getElementById('IdEstablecimientoExterno').value;
	  var param = 'Proceso=DatosPaciente';
	  param +='&nec='+nec;
	  param +='&idext='+idext;
	  sendReq.send(param);
}

function limpiar(){
	window.location.replace('RecepcionLab.php');
  /* document.getElementById("frmdatosexpediente").reset();
   document.getElementById("frmverificardatospac").reset();
  // document.getElementById("frmdatosgenerales").reset();

    $('#CmbServicio').select2('val', 0);
    $('#cmbSubServ').select2('val', 0);
    $('#cmbMedico').select2('val', 0);
    $('#txtconsulta').val('');
    document.getElementById('lyLaboratorio').style.display="none"
    //document.getElementById('DatosPaciente').style.display="none"

//	document.getElementById('cmbarea').value=0;
//	document.getElementById('cboEstudio').value=0;
//	document.getElementById('cboMuestra').value=0;
//	document.getElementById('cboOrigen').value=0;
//	document.getElementById('Indicacion').value="";*/
}

function IsNumeric(sText){//funcion para verificar si el dato que entra es numerico
   var ValidChars = "0123456789-";

   var IsNumber=true;
   var Char;

   for (i = 0; i < sText.length && IsNumber == true; i++)
      {
      Char = sText.charAt(i); //descubrir que caracter esta llenando una posicion con un string
      if (ValidChars.indexOf(Char) == -1) //Despues utilizamos el metodo indexOf para buscar nuestra lista de caracteres validos(ValidChars), si no existe entonces
         {
			IsNumber = false;//esto significa que el usuario a ingresado un caracter invalido
         }
      }
   return IsNumber;
}

function IsFecha(sText){//funcion para verificar si el dato que entra es numerico
   var ValidChars = "0123456789-/";

   var IsNumber=true;
   var Char;

   for (i = 0; i < sText.length && IsNumber == true; i++)
      {
      Char = sText.charAt(i); //descubrir que caracter esta llenando una posicion con un string
      if (ValidChars.indexOf(Char) == -1) //Despues utilizamos el metodo indexOf para buscar nuestra lista de caracteres validos(ValidChars), si no existe entonces
         {
			IsNumber = false;//esto significa que el usuario a ingresado un caracter invalido
         }
      }
   return IsNumber;
}

function cargar(){
	location.href='RecepcionLab.php';
}



function procesaEsp(){
 if (sendReq.readyState == 4){//4 The request is complete
   if (sendReq.status == 200){//200 means no error.
	  respuesta = sendReq.responseText;
         //return false;
	  switch(accion){
		case 1:
		  	document.getElementById('lyMed').innerHTML = respuesta;
                        $("#cmbMedico").select2({
                           allowClear: true,
                           dropdownAutoWidth: true
                        });
			break;
		case 2:
			document.getElementById('lyEstudio').innerHTML = respuesta;
			break;
		case 3:
			document.getElementById('lyMuestra').innerHTML = respuesta;
			break;
		case 4:
			document.getElementById('lyOrigen').innerHTML = respuesta;
			break;
		case 5:
			if (respuesta == -1){
				alert(".:No puede ingresar el mismo examen dos veces!");
				limpiar();
			}else{
				document.getElementById('gridatos').innerHTML = respuesta;
				if(confirm(".:Desea Agregar otro examen?")){
					limpiar();
				}else{document.getElementById('btnguardar').style.visibility='visible';}}
			break;
		case 6:
                        alert ('respuesta:'+respuesta)
			if(respuesta == 2)
                        {
                            //alert("No hay registros de este paciente debe ingresar los datos");
                            //location.href='RecepcionLab.php';
                            //abreVentana(nec);
                            NoEncontrado(nec);
                        }else{
                            nec=document.getElementById('txtexp').value;
                            idext=document.getElementById('IdEstablecimientoExterno').value;
                                if (idext == ''){
                                    idext=0;
                                }
                            //alert(nec);
                            MostrarDatos(nec, idext);
                            }
			break;
		case 7:
			document.getElementById('btnguardar').innerHTML = respuesta;
			break;
		case 8:
		  	document.getElementById('lyEstab').innerHTML = respuesta;
                        document.getElementById('CmbServicio').focus();
			break;
		case 9:
		  	document.getElementById('lysubserv').innerHTML = respuesta;
                        $("#cmbSubServ").select2({
                           allowClear: true,
                           dropdownAutoWidth: true
                        });
			break;
		case 10:
		var str = "0PCNT?";
		ready = respuesta.split('|');
		if(respuesta.indexOf(str) !== -1){
			abreVentana(ready[1], ready[2]);
		}
		else{
			document.getElementById('DatosPaciente').innerHTML = respuesta;

		}
		if((respuesta.indexOf('....') == -1)){
			document.getElementById('lyLaboratorio').style.display="block";
		}

		  	document.getElementById('DatosPaciente').style.display="block"
                        //
                       // document.getElementById('cmbTipoEstab').focus();

                        idext=document.getElementById('IdEstablecimientoExterno').value;


                        if (idext == '' || idext==null || idext=='""'){
                            document.getElementById('cmbTipoEstab').focus();
                        }
                        else{
                        //    alert('filltipoestablecimiento')
                            fillTipoEstablecimiento(idext)
                        }
                        //document.getElementById('cmbTipoEstab').focus();style.display="block"  enable = true
                        break;
		case 11:
                        $('#DatosPaciente').show();
		  	document.getElementById('DatosPaciente').innerHTML = respuesta;
                        document.getElementById('lyLaboratorio').style.display="none";
                        //document.getElementById('cmbTipoEstab').focus();disabled = true
                        break;
                case 12:
                        $('#lyTipoEstab').show();
		  	document.getElementById('lyTipoEstab').innerHTML = respuesta;
                        document.getElementById('cmbTipoEstab').focus();
			break;

                case 13:
				//alert (respuesta);
                        respuesta= JSON.parse(respuesta);
					//	alert (respuesta.status+'--'+respuesta.data);
						//return false;
                        if(respuesta.status && respuesta.data !== undefined && respuesta.data !== false) {
                            if (respuesta.data[0].estado === '1') { // estado es DIGITADA
                                //redireccionando al paso de recepción de solicitud
                                if(confirm(".:La Solicitud ya fue ingresada por el medico desea recepcionarla?")){
                                    $('#idSolicitud').val(respuesta.data[0].id);
                                    $('#fechaCita').val(respuesta.data[0].fecha_cita);
                                    $('#numeroExpediente').val($('#IdNumeroExp').val());
                                    $('#idExpediente').val($('#idexpediente').val());

                                    $('form#frm_send_recepcion').submit();
                                }
                                else
                                    limpiar();

                            } else {
								if(confirm("Ya existe una solicitud con estos datos y su estado es "+respuesta.data[0].descripcion+" desea ingresar otra?")){
                                    Examenes();
									limpiar();
                                }
                                else
                                    limpiar();
                            }
                        }else {
                            Examenes();
                            limpiar();
                        }
                break;

		}
    }else {
	  alert('Se han presentado problemas en la petición');
    }
  }
}

//pegar el correlativo del paciente externo para la busqueda y prescribir los examenes
function pegarExp(IdExpediente,IdCitaServApoyo,IdEstablecimientoExterno,IdNumeroExpRef){
    //alert('Llego aqui'+IdExpediente+'-'+IdCitaServApoyo+' -'+IdEstablecimientoExterno+' '+IdNumeroExpRef)
    //document.getElementById("txtexp").value = IdExpediente;
    document.getElementById("IdCitaServApoyo").value = IdCitaServApoyo;
    document.getElementById("IdEstablecimientoExterno").value = IdEstablecimientoExterno;
    document.getElementById("txtexp").value = IdNumeroExpRef;
    searchpac();
}



function Examenes()
{
    // POP UP DE EXAMENES DE LABORATORIO
    var IdNumeroExp = document.getElementById("IdNumeroExp").value;
    var IdEstablecimiento = document.getElementById("cmbEstablecimiento").value;// establecimiento que solicita el estudio
    var lugar = document.getElementById("lugar").value;
    var IdSubServicio = document.getElementById("cmbSubServ").value;
    var IdEmpleado = document.getElementById("cmbMedico").value;
    var FechaConsulta = document.getElementById("txtconsulta").value;
    var FechaRecepcion = document.getElementById("txtfrecepcion").value;
    var IdCitaServApoyo = document.getElementById("IdCitaServApoyo").value;
    var Sexo = document.getElementById("tiposexo").value;
    var idexpediente = document.getElementById("idexpediente").value;
    var mensaje="";
    if (FechaConsulta==''){
        mensaje +="Ingrese el dato de la Fecha de la consulta ";
    }
    if (IdSubServicio==0){
        mensaje +="\nIngrese el dato del subservicio"
    }
    if (mensaje !=''){
       alert (mensaje)
        return false;
    }


    /*
    var IdHistorialClinico = document.getElementById("IdHistorialClinico").value;
    var IdSubEspecialidad = document.getElementById("IdSubEspecialidad").value;
    var IdUsuarioReg = document.getElementById("IdUsuarioReg").value;
    var FechaHoraReg = document.getElementById("FechaHoraReg").value;*/
    var Parametros="IdNumeroExp="+IdNumeroExp;
            Parametros+="&IdEstablecimiento="+IdEstablecimiento;
            Parametros+="&lugar="+lugar;
            Parametros+="&IdSubServicio="+IdSubServicio;
            Parametros+="&IdEmpleado="+IdEmpleado;
            Parametros+="&FechaConsulta="+FechaConsulta;
            Parametros+="&FechaRecepcion="+FechaRecepcion;
            Parametros+="&IdCitaServApoyo="+IdCitaServApoyo;
            Parametros+="&Sexo="+Sexo;
            Parametros+="&idexpediente="+idexpediente;/*
var url = "../EstudiosLaboratorio/Solicitud.php"+Parametros;
*/
// alert (IdEstablecimiento+ ' -- ' +lugar+ ' -- ' + IdSubServicio+ ' -- ' +IdEmpleado+ ' -- ' +FechaConsulta);
//alert (Parametros)
//return false;


        var url = "../EstudiosLaboratorio/Solicitud.php?"+Parametros;
        window.open(url,"Solicitudes","fullscreen=yes, toolbar=no, scrollbars=yes");
        limpiar();
}
//Funcion de cambio de est externo
function cambioestexterno(){
    est=$('#cmb_establecimiento').val();
   // document.getElementById("IdEstablecimientoExterno").value = est;
    $('#IdEstablecimientoExterno').val($('#cmb_establecimiento').val())
}

function handle(e){
	if(e.keyCode === 13){
            searchpac();
        }
}
//
//$(document).ready(function() {
//         $("#cmb_establecimiento").select2({
//           placeholder: "Establecimientos",
//           allowClear: true,
//           dropdownAutoWidth: true
//        });
//    });




</script>
</head>

<body text="#000000" class="CobaltPageBody" onLoad="frmdatosexpediente.txtexp.focus();">

	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-5">
			<div class="box box-info">
				<div class="box-header">
                    <div class="col-md-6">
						<h3 class="box-title">Búsqueda de Paciente</h3>
					</div>
                </div>
				<div class="box-body">
					<div class="form-group">
						<form name="frmdatosexpediente" id="frmdatosexpediente" action="" method="post">
						 <table border = 0 class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%; margin-bottom: 2px !important; table-layout:fixed;" cellspacing="0" cellpadding="3" align="center">
						<tr>
                                 <th width="22%">Establecimiento</th>
                                 <td> <select id="cmb_establecimiento" name="cmb_establecimiento" style="width:100%; size: 10" class="height placeholder js-example-basic-single" onchange="cambioestexterno();">
                                                       <?php

                                                           //$obje=new clsLab_CodigosEstandar;
                                                           $consulta= $recepcion->seleccionarestablecimientos();
                                                           while($row = pg_fetch_array($consulta)){
                                                              if ($row['id']==$lugar){
                                                                 echo '<option value="'.$lugar.'" selected>'.$row['nombre'].'</option>';
                                                              }
															  if ($row['id']!=$lugar)
                                                               echo "<option value='" . $row['id']. "'>" . $row['nombre'] . "</option>";
                                                           }
                                                                               //mysql_free_result($row);
                                                       ?>
                                               </select>
                                 </td>
                        </tr>
                        <tr>
                               <td>Expediente</td>
                               <td>
                                  <input id="txtexp" class="form-control height" style="width:188px; height:20px" size="26" maxlength="20" onkeypress="handle(event)"  >
                                       <input type="hidden" id="IdCitaServApoyo">
                                       <input type="hidden" id="IdEstablecimientoExterno" value="<?php echo $lugar; ?>">

               <!--                        <input type="button" value="Verificar" id="btnverificar" onClick="searchpac();">-->
                               </td>
                       </tr>

					   <tr><td colspan="2" align="center"><br><br>
                            <button type="button" id="btnverificar" name="btnverificar" class='btn btn-primary' onclick="searchpac()"><span class='glyphicon glyphicon glyphicon-search'>&nbsp;Verificar</button>
                            <button type="button" id="Nuevo" name="Nuevo" class='btn btn-primary' onclick="window.location.replace('RecepcionLab.php')"><span class='glyphicon glyphicon-refresh'>&nbsp;Nueva Búsqueda</button>
                        </td></tr>
				   </table>
			   </form>
				   <br><br>
                    </div>
				</div>
			</div>
		</div><!--md6-->
		<div class="col-md-5">
			<div class="box box-info">
				<div class="box-header">
					<div class="col-md-5">
						<h3 class="box-title">Datos de Paciente</h3>
					</div>

                </div>
				<div class="box-body">
					<div id="DatosPaciente" >
						<table style="width:90%; background-color:#ffffff; border: none;" border="0" align="center" class="table tableinfo">
			                <tr>
			                    <th class="th-info" style="width:30%">Expediente</th>
								<td></td>

			                </tr>
			                <tr>
			                    <th class="th-info">Nombre Completo</th>
								<td></td>
			                </tr>
			                <tr>
			                    <th class="th-info">Edad</th>
								<td></td>
			                </tr>
			                <tr>
			                    <th class="th-info">Sexo</th>
								<td></td>
			                </tr>
			                <tr>
			                    <th class="th-info">Conocido por</th>
								<td></td>
			                </tr>
						</table>
					</div>
				</div>
			</div>
		</div><!--md6-->
		<br/>
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-header">
					<div class="col-md-5">
						<h3 class="box-title">Datos Generales de Boleta de Ex&aacute;menes </h3>
					</div>
                </div>
				<div class="box-body">
					<div class="col-md-2"></div>
					<div class="col-md-8">
						<div id="lyLaboratorio" class="panel panel-body" style="display:none;">
						    <form name="frmdatosgenerales" id="frmdatosgenerales" action="" method="post">
						        <table style="width:90%; background-color:#ffffff; border: none;" border="0" align="center" class="table tableinfo">

						            <tr>
						                <td class="th-info" >Tipo Establecimiento</td>
						                <td class="td-blue"><?php echo $rows['nombretipoestablecimiento']; ?>
						                    <div id="lyTipoEstab">
						                        <select name="cmbTipoEstab" id="cmbTipoEstab" style="width:350px" class="form-control height"  onFocus="fillEstablecimiento(this.value)">
						                                    <?php
						                                    $tipoest=$recepcion->tipoestactual($lugar);
						                                    $rows=  pg_fetch_array($tipoest);
						                                       echo '<option value="' . $rows['idtipoestablecimiento'] . '" selected="selected" >' . $rows['nombretipoestablecimiento'] . '</option>';

						                                    ?>
						                        </select>
						                    </div>
						                </td>
						            </tr>
						            <tr>
						                <td class="th-info">Establecimiento</td>
						                <td class="td-blue">
						                    <div id="lyEstab">
						                        <select name="cmbEstablecimiento"  id="cmbEstablecimiento" style="width:350px" class="form-control height js-example-basic-single">
						                            <option value="0">--Seleccione Establecimiento--</option>
						                        </select>
						                    </div>
						                        <input id="lugar" type="hidden" value="<?php echo $lugar?>" />
						                </td>
						            </tr>
						            <tr>
						                <td class="th-info">Procedencia:&nbsp;</td>
						                <td class="td-blue" >
						                    <select name="CmbServicio" id="CmbServicio" class="js-example-basic-single" style="width:350px" onChange="fillservicio(this.value)" >
						                        <option value="0" selected="selected">--Seleccione Procedencia--</option>
						                        <?php
						                            $tiposerv=$funcGeneral->LlenarProcedencia($lugar);
						                            while ($rows = pg_fetch_array($tiposerv)){
						                                echo '<option value="' . $rows['0'] . '">' . $rows['nombre'] . '</option>';
						                            }
						                                        //    }
						                        ?>
						                    </select>
						                </td>
						            </tr>
						            <tr>
						                    <td class="th-info">SubServicio:&nbsp;</td>
						                    <td class="td-blue">
						                            <div id="lysubserv">
						                               <select name="cmbSubServ" id="cmbSubServ"  style="width:350px" class="js-example-basic-single">
						                                            <option value="0" selected="selected">--Seleccione Subespecialidad--</option>

						                                    </select>
						                            </div>
						                    </td>
						            </tr>
						            <tr>
						                    <td class="th-info">Indicado por:</td>
						                    <td class="td-blue">
						                            <div id="lyMed">
						                               <select name="cmbMedico" class="js-example-basic-single" id="cmbMedico" onChange="fillMed(this.value)" style="width:350px">
						                                            <option value="0" selected="selected">--Seleccione una opción--</option>

						                                    </select>
						                            </div>
						                    </td>
						            </tr>
						            <tr>
						                    <td class="th-info" align="center">Fecha en que paso Consulta:</td>
						                    <td class="td-blue" colspan="2">
												<?php
												 if ($hospital=='t'){
													 echo "<input type='text' class='datepicker form-control height'  id='txtconsulta' name='txtconsulta'  value='". date('Y-m-d H:i')."' onchange=\"valfechasolicita(this.value, 'txtconsulta'); change_newdate('txtconsulta', 'txtfrecepcion')\" style='width:150px' />";
												 }
												 else{
													 echo '<input name="Input" class="date" id="txtconsulta" style="width:188px; height:20px" size="26" placeholder="aaaa-mm-dd">';
												 }
												 ?>

						                    </td>
						            </tr>
									<tr>
						                    <td class="th-info" align="center">Fecha de Recepción:</td>
						                    <td class="td-blue" colspan="2">
												<?php
													 echo "<input type='text' class='date form-control height'  id='txtfrecepcion' name='txtconsulta'  value='". date('Y-m-d')."' onchange=\"valfechasolicita(this.value, 'txtconsulta');\" style='width:150px' />";

												 ?>

						                    </td>
						            </tr>
						            <tr>

						                <td class="th-info" colspan="2">
						            <center>
						               <button type="button" id="Examen" name="Examen" class='btn btn-primary' onclick="BuscarExistenciaSolicitud()"><span class='glyphicon glyphicon-plus'>&nbsp;Agregar Examenes</button>
						               <button type="button" id="Nuevo" name="Nuevo" class='btn btn-primary' onclick="window.location.replace('RecepcionLab.php')"><span class='glyphicon '>&nbsp;Nueva Búsqueda</button>
						            </center>

						                </td>
						            </tr>
						        </table>
						    </form>

						</div><!--formulario-->
					</div>
				</div>
			</div>
		</div><!--md12-->
	</div>



<!--<link href="../../../css/paginalab.css" rel="stylesheet" type="text/css" />-->
<div  style="width: 100%" >
   <!-- <div class="panel panel-primary"  style="border:0px; height: 100%">
      <div class="panel-heading" style="padding: 2px !important; width: 45%; min-width: 507px;"><h3>Verificar Expediente</h3> </div>
	  <form name="frmdatosexpediente" id="frmdatosexpediente" action="" method="post">


      <div class="panel-body" id="pb-primervez" style="width:45%; border: 1px solid; border-color: #428BCA;min-width: 507px;">
               <table border = 0 class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%; margin-bottom: 2px !important; table-layout:fixed;" cellspacing="0" cellpadding="3" align="center">

                        <tr>
                                 <th width="22%">Establecimiento</th>
                                 <td> <select id="cmb_establecimiento" name="cmb_establecimiento" style="width:100%; size: 10" class="height placeholder js-example-basic-single" onchange="cambioestexterno();">
                                                       <?php
/*
                                                           //$obje=new clsLab_CodigosEstandar;
                                                           $consulta= $recepcion->seleccionarestablecimientos();
                                                           while($row = pg_fetch_array($consulta)){
                                                              if ($row['id']==$lugar){
                                                                 echo '<option value="'.$lugar.'" selected>'.$row['nombre'].'</option>';
                                                              }
															  if ($row['id']!=$lugar)
                                                               echo "<option value='" . $row['id']. "'>" . $row['nombre'] . "</option>";
                                                           }
                                                            */
                                                       ?>
                                               </select>
                                 </td>
                        </tr>
                        <tr>
                               <td>Expediente</td>
                               <td>
                                  <input id="txtexp" class="form-control height" style="width:188px; height:20px" size="26" maxlength="20" >
                                       <input type="hidden" id="IdCitaServApoyo">
                                       <input type="hidden" id="IdEstablecimientoExterno" value="<?php echo $lugar; ?>">


                               </td>
                       </tr>
                       <tr><td colspan="2" align="right">
                            <button type="button" id="btnverificar" name="btnverificar" class='btn btn-primary' onclick="searchpac()"><span class='glyphicon glyphicon glyphicon-search'>&nbsp;Verificar</button>
                            <button type="button" id="Nuevo" name="Nuevo" class='btn btn-primary' onclick="window.location.replace('RecepcionLab.php')"><span class='glyphicon glyphicon-refresh'>&nbsp;Nueva Búsqueda</button>
                        </td></tr>
               </table>
                </div>

   </form> -->
    <form id="frm_send_recepcion" action="../RecepcionSolicitud/Proc_RecepcionSolicitud.php" method="POST" style="display:none;">
        <input type="hidden" id="idSolicitud" name="idSolicitud" value="" />
        <input type="hidden" id="fechaCita" name="fechaCita" value="" />
        <input type="hidden" id="numeroExpediente" name="numeroExpediente" value="" />
        <input type="hidden" id="idExpediente" name="idExpediente" value="" />
    </form>

<!-- <div id="lyLaboratorio" class="panel panel-body" style="display:none;">
    <form name="frmdatosgenerales" id="frmdatosgenerales" action="" method="post">
        <table cellspacing="0" cellpadding="0" align="center" border=1 class="StormyWeatherFormTABLE" style="height:275px">
            <tr>&nbsp;</tr>
            <tr>
                <td colspan="3" align="center" class="CobaltFieldCaptionTD">
                    <div>
                        <h2><strong>Datos Generales de Boleta de Ex&aacute;menes </strong></h2>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD" >Tipo Establecimiento</td>
                <td class="StormyWeatherDataTD">
                    <div id="lyTipoEstab">
                        <select name="cmbTipoEstab" id="cmbTipoEstab" style="width:350px" class="form-control height"  onFocus="fillEstablecimiento(this.value)">
                                    <?php
                                    $tipoest=$recepcion->tipoestactual($lugar);
                                    $rows=  pg_fetch_array($tipoest);
                                       echo '<option value="' . $rows['idtipoestablecimiento'] . '" selected="selected" >' . $rows['nombretipoestablecimiento'] . '</option>';

                                    ?>
                        </select>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
                <td class="StormyWeatherDataTD">
                    <div id="lyEstab">
                        <select name="cmbEstablecimiento"  id="cmbEstablecimiento" style="width:350px" class="form-control height js-example-basic-single">
                            <option value="0">--Seleccione Establecimiento--</option>
                        </select>
                    </div>
                        <input id="lugar" type="hidden" value="<?php echo $lugar?>" />
                </td>
            </tr>
            <tr>
                <td class="StormyWeatherFieldCaptionTD">Procedencia:&nbsp;</td>
                <td class="StormyWeatherDataTD" >
                    <select name="CmbServicio" id="CmbServicio" class="js-example-basic-single" style="width:350px" onChange="fillservicio(this.value)" >
                        <option value="0" selected="selected">--Seleccione Procedencia--</option>
                        <?php
                            $tiposerv=$funcGeneral->LlenarProcedencia($lugar);
                            while ($rows = pg_fetch_array($tiposerv)){
                                echo '<option value="' . $rows['0'] . '">' . $rows['nombre'] . '</option>';
                            }
                                        //    }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD">SubServicio:&nbsp;</td>
                    <td class="StormyWeatherDataTD">
                            <div id="lysubserv">
                               <select name="cmbSubServ" id="cmbSubServ"  style="width:350px" class="js-example-basic-single">
                                            <option value="0" selected="selected">--Seleccione Subespecialidad--</option>

                                    </select>
                            </div>
                    </td>
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD">Indicado por:</td>
                    <td class="StormyWeatherDataTD">
                            <div id="lyMed">
                               <select name="cmbMedico" class="js-example-basic-single" id="cmbMedico" onChange="fillMed(this.value)" style="width:350px">
                                            <option value="0" selected="selected">--Seleccione una opción--</option>

                                    </select>
                            </div>
                    </td>
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD" align="center">Fecha en que paso Consulta:</td>
                    <td class="StormyWeatherDataTD" colspan="2">
						<?php
						 if ($hospital=='t'){
							 echo "<input type='text' class='datepicker form-control height'  id='txtconsulta' name='txtconsulta'  value='". date('Y-m-d H:i')."' onchange=\"valfechasolicita(this.value, 'txtconsulta');\" style='width:150px' />";
						 }
						 else{
							 echo '<input name="Input" class="date" id="txtconsulta" style="width:188px; height:20px" size="26" placeholder="aaaa-mm-dd">';
						 }
						 ?>

                    </td>
            </tr>
            <tr>

                <td class="StormyWeatherDataTD" colspan="2">
            <center>
               <button type="button" id="Examen" name="Examen" class='btn btn-primary' onclick="BuscarExistenciaSolicitud()"><span class='glyphicon glyphicon-plus'>&nbsp;Agregar Examenes</button>
               <button type="button" id="Nuevo" name="Nuevo" class='btn btn-primary' onclick="window.location.replace('RecepcionLab.php')"><span class='glyphicon '>&nbsp;Nueva Búsqueda</button>
            </center>

                </td>
            </tr>
        </table>
    </form>

 </div> -->
</div>

</div>

<p align="center"><!-- END Record NewRecord1 --></p>
<p align="center">&nbsp;</p>
</body>
</html>
<?php
}

else{?>
<script language="javascript">
	window.location="../../../login.php";
</script>
<?php }?>
