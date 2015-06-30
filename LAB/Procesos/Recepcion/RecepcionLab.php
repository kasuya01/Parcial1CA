<?php session_start();
include_once("../../../Conexion/ConexionBD.php"); 
include_once("cls_recepcion.php");
//include_once("cls_recepcion.php");
if(isset($_SESSION['Correlativo'])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$recepcion = new clsRecepcion;
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
			//input { width:250px; border: 2px solid #CCC; line-height:20px;height:20px; border-radius:3px; padding:2px; }
		</style>
<?php include_once $ROOT_PATH."/public/css.php";?>
<?php include_once $ROOT_PATH."/public/js.php";?>
<script  type="text/javascript">
       $(document).ready(function() {
         $("#cmb_establecimiento").select2({
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
}

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
}

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
accion=6;
    
	nec=document.getElementById('txtexp').value;
	idext=document.getElementById('IdEstablecimientoExterno').value;
        
	
        if (idext == '' || idext==null || idext=='""'){
            idext=0;
        }
       // alert(nec+' - '+idext)
	if(!IsNumeric(document.getElementById('txtexp').value)){
		alert('Por favor solo introduzca numeros en este campo') 
		document.getElementById('txtexp').focus();
		return false;
	}	
	
	if(document.getElementById('txtexp').value==""){
		alert(".: Error: Debe Ingresar un Numero de Expediente");
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
	
	  var param = 'Proceso=searchpac';
	  param += '&nec='+nec+'&idext='+idext;
        //  param += '&idext='+idext;
         // alert (param)
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
	document.getElementById('cmbarea').value=0;
	document.getElementById('cboEstudio').value=0;
	document.getElementById('cboMuestra').value=0;
	document.getElementById('cboOrigen').value=0;
	document.getElementById('Indicacion').value="";
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
//	  alert ('RESPUESTA: '+respuesta+':FIN RESPUESTA');
//          return false;
	  switch(accion){
		case 1:
		  	document.getElementById('lyMed').innerHTML = respuesta;
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
                      //  alert ('respuesta:'+respuesta)
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
			break;
		case 10:
		  	document.getElementById('DatosPaciente').innerHTML = respuesta;
                        document.getElementById('lyLaboratorio').style.display="block"; //
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
                    //alert ('case11')
		  	document.getElementById('DatosPaciente').innerHTML = respuesta;
                        document.getElementById('lyLaboratorio').style.display="none";
                        //document.getElementById('cmbTipoEstab').focus();disabled = true
                        break;
                case 12:
		  	document.getElementById('lyTipoEstab').innerHTML = respuesta;
                        document.getElementById('cmbTipoEstab').focus();
			break;
		}		
	}else {
	  alert('Se han presentado problemas en la petición');
    }
  }  
} 

//pegar el correlativo del paciente externo para la busqueda y prescribir los examenes
function pegarExp(IdExpediente,IdCitaServApoyo,IdEstablecimientoExterno,IdNumeroExpRef){
    //alert(IdExpediente+'-'+IdCitaServApoyo+' -'+IdEstablecimientoExterno+' '+IdNumeroExpRef)
//    document.getElementById("txtexp").value = IdExpediente;
    document.getElementById("IdCitaServApoyo").value = IdCitaServApoyo;
    document.getElementById("IdEstablecimientoExterno").value = IdEstablecimientoExterno;
    document.getElementById("txtexp").value = IdNumeroExpRef;
    searchpac();
}


function Examenes(){
    // POP UP DE EXAMENES DE LABORATORIO
    var IdNumeroExp = document.getElementById("IdNumeroExp").value;
    var IdEstablecimiento = document.getElementById("cmbEstablecimiento").value;// establecimiento que solicita el estudio
    var lugar = document.getElementById("lugar").value;
    var IdSubServicio = document.getElementById("cmbSubServ").value;
    var IdEmpleado = document.getElementById("cmbMedico").value;
    var FechaConsulta = document.getElementById("txtconsulta").value;
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
}
//Funcion de cambio de est externo
function cambioestexterno(){
    est=$('#cmb_establecimiento').val();   
   // document.getElementById("IdEstablecimientoExterno").value = est;
    $('#IdEstablecimientoExterno').val($('#cmb_establecimiento').val())
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

<!--<link href="../../../css/paginalab.css" rel="stylesheet" type="text/css" />-->
<form name="frmdatosexpediente" action="" method="post">	
   <div  style="width: 45%">
      <div class="panel panel-primary">                        
         <div class="panel-heading" style="padding: 2px !important"><h3>Verificar Expediente</h3> </div>                        
          <div class="panel-body" id="pb-primervez">  
            <table border = 0 class="table table-white no-v-border table-condensed" border="0" style="border:0px; width: 100%; margin-bottom: 2px !important;" cellspacing="0" cellpadding="3" align="center">
<!--
                     <tr>
                        <td colspan="3" align="center" class="CobaltFieldCaptionTD">
                           <H3><strong>Verificar Expediente</strong></H3>
                        </td>
                     </tr>-->
                     <tr>
                              <th>Establecimiento</th>
                              <td> <select id="cmb_establecimiento" name="cmb_establecimiento" style="width:100%; size: 10" class="height placeholder js-example-basic-single" onchange="cambioestexterno();">
                                                    <?php

                                                        //$obje=new clsLab_CodigosEstandar;
                                                        $consulta= $recepcion->seleccionarestablecimientos();
                                                        while($row = pg_fetch_array($consulta)){
                                                           if ($row['id']==$lugar){
                                                              echo '<option value="'.$lugar.'" selected>'.$row['nombre'].'</option>';
                                                           }
                                                            echo "<option value='" . $row['id']. "'>" . $row['nombre'] . "</option>";
                                                        }
                                                                            //mysql_free_result($row);		
                                                    ?>		 		
                                            </select>   </td>
                     </tr>
                     <tr>
                            <td>Expediente</td>
                            <td>
                                    <input id="txtexp" class="form-control height" style="width:188px; height:20px" size="26"  >
                                    <input type="hidden" id="IdCitaServApoyo">
                                    <input type="hidden" id="IdEstablecimientoExterno" value="<?php echo $lugar; ?>">
                                    
            <!--                        <input type="button" value="Verificar" id="btnverificar" onClick="searchpac();">-->
                            </td> 
                    </tr>     
                    <tr><td colspan="2" align="right">
                       <button type="button" id="btnverificar" name="btnverificar" class='btn btn-primary' onclick="searchpac()"><span class='glyphicon glyphicon glyphicon-search'>&nbsp;Verificar</button>
                       </td></tr>
            </table>	
             </div>
      </div>
    </div>
</form>
<div id="DatosPaciente"></div>
 <div id="lyLaboratorio" style="display:none; position:relative;">
     <form name="frmdatosgenerales" action="" method="post">  
        <table cellspacing="0" cellpadding="0" align="center" border=0 class="StormyWeatherFormTABLE" style="height:275px">
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
                                    <?php //  <option value="0" selected="selected">--Seleccione un tipo de Establecimiento--</option>
                                    $tipoest=$recepcion->tipoestactual($lugar);
                                    $rows=  pg_fetch_array($tipoest);
                                       echo '<option value="' . $rows['idtipoestablecimiento'] . '" selected="selected" >' . $rows['nombretipoestablecimiento'] . '</option>'; 
                                   /*         $db = new ConexionBD;
                                            if($db->conectar()==true){// SELECT IdTipoEstablecimiento,NombreTipoEstablecimiento FROM mnt_tipoestablecimiento ORDER BY NombreTipoEstablecimiento
                                                    $consulta  = "
                                                            SELECT a.IdTipoEstablecimiento, a.NombreTipoEstablecimiento
                                                            FROM mnt_tipoestablecimiento a
                                                            INNER JOIN mnt_establecimiento b ON a.IdTipoEstablecimiento = b.IdTipoEstablecimiento
                                                            WHERE IdEstablecimiento = $lugar";
                                                    $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

                                            //por cada registro encontrado en la tabla me genera un <option>
                                                    while ($rows = mysql_fetch_array($resultado)){
                                                            echo '<option value="' . $rows[0] . '" selected="selected" >' . $rows[1] . '</option>'; 
                                                    }
                                            }*/
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
                        <input id="lugar" type="hidden" value="<?php echo $lugar?>" >
                    </td>
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD">Procedencia:&nbsp;</td>
                    <td class="StormyWeatherDataTD" >
                            <select name="CmbServicio" id="CmbServicio" class="form-control height" style="width:350px" onChange="fillservicio(this.value)" >
                                    <option value="0" selected="selected">--Seleccione Procedencia--</option>
                                    <?php
                                    $tiposerv=$recepcion->tipoestservicio($lugar);
                                    /*$rows=  pg_fetch_array($tiposerv);
                                            $db = new ConexionBD;
                                                    if($db->conectar()==true){
                                                    $consulta  = "SELECT mnt_servicio.IdServicio,mnt_servicio.NombreServicio FROM mnt_servicio 
                                                    INNER JOIN mnt_servicioxestablecimiento 
                                                    ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
                                                    WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' AND IdEstablecimiento=$lugar";
                                                    $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());

                                            //por cada registro encontrado en la tabla me genera un <option>*/
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
                               <select name="cmbSubServ" id="cmbSubServ"  style="width:350px" class="form-control height">
                                            <option value="0" selected="selected">--Seleccione Subespecialidad--</option>

                                    </select>
                            </div>
                    </td>
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD">M&eacute;dico&nbsp;</td>
                    <td class="StormyWeatherDataTD">
                            <div id="lyMed">
                               <select name="cmbMedico" class="form-control height" id="cmbMedico" onChange="fillMed(this.value)" style="width:350px">
                                            <option value="0" selected="selected">--Seleccione M&eacute;dico&nbsp;--</option>

                                    </select>
                            </div>
                    </td>
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD" align="center">Fecha en que paso Consulta</td>
                    <td class="StormyWeatherDataTD" colspan="2">
                        <input name="Input" class="date" id="txtconsulta" style="width:188px; height:20px" size="26" placeholder="aaaa-mm-dd">
<!--                            <input type="button" value="..." id="trigger">&nbsp;&nbsp;aaaa-mm-dd</td>
                                    <script type="text/javascript">
                                            Calendar.setup(
                                            {
                                            inputField  : "txtconsulta",         // el ID texto 
                                            ifFormat    : "%Y-%m-%d",    // formato de la fecha "%d/%m/%Y"
                                            button      : "trigger"       // el ID del boton			  	  
                                            }
                                            );
                                    </script>-->
            </tr>
            <tr>
                    <td class="StormyWeatherFieldCaptionTD">Agregar Examenes</td>
                    <td class="StormyWeatherDataTD">
                            <input type="button" value=" Seleccionar " id="Examen" onclick="Examenes();">
                    </td> 
            </tr>            
        </table>
    </form>


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
