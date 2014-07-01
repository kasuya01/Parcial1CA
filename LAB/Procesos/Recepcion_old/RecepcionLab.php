<?php session_start();
include_once("../../../Conexion/ConexionBD.php"); 
//include_once("cls_recepcion.php");
if(isset($_SESSION['Correlativo'])){
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 

/***************/
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
?><br>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
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
<!--referencias del estilo del calendario-->
<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />

<!--llamado al archivo de funciones del calendario-->
<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>

<script language="javascript">
var miPopup
function fillEstablecimiento(idtipoEstab){
  accion=8;
  
	if(idtipoEstab==0){ 
	  alert("Seleccione una tipo de establecimiento!");
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
	  param += '&nec='+nec;
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
	
	  var param = 'Proceso=guardar';
	  param +='&nec='+nec+'&establecimiento='+establecimiento+'&Serv='+Serv+'&SubServ='+SubServ+'&med='+med+'&fcon='+fcon;
	  sendReq.send(param);
}

 
function abreVentana(){ 
    miPopup = window.open("datospacfisttime.php","miwin","width=900,height=300,scrollbars=yes"); 
    miPopup.focus(); 
} 

function MostrarDatos(){

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
	 // alert (respuesta)
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
			if(respuesta == 2){
				alert("No hay registros de este paciente debe ingresar los datos");
				//location.href='RecepcionLab.php';
				abreVentana();
				}else{
					MostrarDatos();
					document.getElementById('lyLaboratorio').style.display="block";
				}
			break;
		case 7:
			document.getElementById('btnguardar').innerHTML = respuesta;
			break;
		case 8:
		  	document.getElementById('lyEstab').innerHTML = respuesta;
			break;
                          lysubserv
		case 9:
		  	document.getElementById('lysubserv').innerHTML = respuesta;
			break;
		}		
	}else {
	  alert('Se han presentado problemas en la petici�n');
    }
  }  
} 

</script>
</head>

<body text="#000000" class="CobaltPageBody" onLoad="frmdatosexpediente.txtexp.focus();">

<link href="../../../css/paginalab.css" rel="stylesheet" type="text/css" />
<form name="frmdatosexpediente" action="" method="post">	
<table border = 1 class="CobaltFormTABLE" cellspacing="0" cellpadding="3" align="center">
	<tr>
		<td colspan="3" align="center" class="CobaltFieldCaptionTD">
			<H3><strong>Verificar Expediente</strong></H3>
		</td>
	  </tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD" align="center">Expediente</td>
		<td class="StormyWeatherDataTD">
			<input id="txtexp" class="CobaltInput" style="width:188px; height:20px" size="26" >
			<input type="button" value="Verificar" id="btnverificar" onClick="searchpac();">
		</td> 
	</tr>
</table>	
</form>
<div id="lyLaboratorio" style="display:none; position:relative;">
	<form name="frmdatosgenerales" action="" method="post">
     		<table cellspacing="0" cellpadding="3" align="center" border=1 class="StormyWeatherFormTABLE">
      			<tr>
				<td colspan="3" align="center" class="CobaltFieldCaptionTD">
					<div>
						<H3><strong>Datos Generales de Boleta de Ex&aacute;menes</strong></H3>
					</div>
				</td>
	  		</tr>
	  		<tr>	
				<td class="StormyWeatherFieldCaptionTD" >Tipo Establecimiento</td>
				<td class="StormyWeatherDataTD">
					<select name="cmbTipoEstab" id="cmbTipoEstab" style="width:350px"  onChange="fillEstablecimiento(this.value)">
          					<option value="0" selected="selected">--Seleccione un tipo de Establecimiento--</option>
						<?php
							$db = new ConexionBD;
							if($db->conectar()==true){
								$consulta  = "SELECT IdTipoEstablecimiento,NombreTipoEstablecimiento FROM mnt_tipoestablecimiento ORDER BY NombreTipoEstablecimiento";
								$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
										
							//por cada registro encontrado en la tabla me genera un <option>
								while ($rows = mysql_fetch_array($resultado)){
									echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
								}
							}
						?>
        				</select>
				</td>
			</tr>
       			<tr>
        			<td class="StormyWeatherFieldCaptionTD">Establecimiento</td>
        			<td class="StormyWeatherDataTD">
					<div id="lyEstab">
          					<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:350px">
            						<option value="0">--Seleccione Establecimiento--</option>
          					</select>
        				</div>
				</td>
      			</tr>
          		<tr>
				<td class="StormyWeatherFieldCaptionTD">Procedencia:&nbsp;</td>
				<td class="StormyWeatherDataTD" >
					<select name="CmbServicio" id="CmbServicio" style="width:350px" onChange="fillservicio(this.value)" >
						<option value="0" selected="selected">--Seleccione Procedencia--</option>
						<?php
							$db = new ConexionBD;
								if($db->conectar()==true){
								$consulta  = "SELECT mnt_servicio.IdServicio,mnt_servicio.NombreServicio FROM mnt_servicio 
								INNER JOIN mnt_servicioxestablecimiento 
								ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
								WHERE IdTipoServicio<>'DCO' AND IdTipoServicio<>'FAR' AND IdEstablecimiento=$lugar";
								$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
										
							//por cada registro encontrado en la tabla me genera un <option>
								while ($rows = mysql_fetch_array($resultado)){
									echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
								}
							}
						?>
                        		</select>
                		</td>
	  		</tr>
	  		<tr>
        			<td class="StormyWeatherFieldCaptionTD">SubServicio:&nbsp;</td>
        			<td class="StormyWeatherDataTD">
					<div id="lysubserv">
						<select name="cmbSubServ" id="cmbSubServ" >
          						<option value="0" selected="selected">--Seleccione Subespecialidad--</option>
          		
        					</select>
					</div>
        			</td>
      			</tr>
      			<tr>
				<td class="StormyWeatherFieldCaptionTD">M&eacute;dico&nbsp;</td>
				<td class="StormyWeatherDataTD">
					<div id="lyMed">
						<select name="cmbMedico" id="cmbMedico" onChange="fillMed(this.value)">
          						<option value="0" selected="selected">--Seleccione M&eacute;dico&nbsp;--</option>
          		
        					</select>
					</div>
				</td>
      			</tr>
     			<tr>
        			<td class="StormyWeatherFieldCaptionTD" align="center">Fecha en que paso Consulta</td>
        			<td class="StormyWeatherDataTD" colspan="2">
					<input name="Input" class="CobaltInput" id="txtconsulta" style="width:188px; height:20px" size="26">
             				<input type="button" value="..." id="trigger">&nbsp;&nbsp;dd/mm/aaaa</td>
						<script type="text/javascript">
							Calendar.setup(
							{
							inputField  : "txtconsulta",         // el ID texto 
							ifFormat    : "%d/%m/%Y",    // formato de la fecha
							button      : "trigger"       // el ID del boton			  	  
							}
							);
						</script>
      			</tr>
    		</table>
  	</form>
  
  	<form name="frmrecepcionlab" action="" method="post">
      
    		<table cellspacing="0" cellpadding="3" style="WIDTH: 550px; HEIGHT: 213px" align="center" border=1>
      <!-- BEGIN Error -->
			<tr>
				<td COLSPAN="4">
					<div align="center" class="CobaltFieldCaptionTD" >
						<H3><strong>Agregar Ex&aacute;menes a Solicitud <strong></H3>
					</div>
				</td>
			</tr>
      <!-- END Error -->
      			<tr>
        			<td  class="StormyWeatherFieldCaptionTD">&Aacute;rea:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
        			<td class="StormyWeatherDataTD" colspan="3">
					<select name="cmbarea" class="SaladSelect" id="cmbarea" style="WIDTH: 169px" onChange="fillestudios(this.value)">
          					<option value="0">--Seleccione &Aacute;rea--</option>
							<?php
								$con = new ConexionBD;
								if($con->conectar()==true){
								$consulta  = "SELECT lab_areas.IdArea, NombreArea 	FROM lab_areas 
								INNER JOIN lab_areasxestablecimiento 
								ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
								WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar ORDER BY NombreArea";
								$resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
											
								//por cada registro encontrado en la tabla me genera un <option>
								while ($rows = mysql_fetch_array($resultado)){
								echo '<option value="' . $rows[0] . '">' . $rows[1] . '</option>'; 
									}
								}
								
							?>
        				</select>
        			</td>
      			</tr>
      			<tr>
        			<td class="StormyWeatherFieldCaptionTD">Estudio:</td>
        			<td class="StormyWeatherDataTD" colspan="3">
					<div id="lyEstudio">
          					<select name="cboEstudio" id="cboEstudio" style="width:250px">
            						<option value="0">--Seleccione Estudios--</option>
          					</select>
        				</div>
				</td>
      			</tr>
      			<tr>
				<td class="StormyWeatherFieldCaptionTD">Tipo Muestra:</td>
				<td class="StormyWeatherDataTD" colspan="3">
					<div id="lyMuestra">
						<select name="select2" id="cboMuestra" style="width:250px">
							<option value="0">--Seleccione Muestra--</option>
						</select>
					</div>
				</td>
      			</tr>
      			<tr>
				<td class="StormyWeatherFieldCaptionTD">Origen Muestra:</td>
				<td class="StormyWeatherDataTD" colspan="3">
					<div id="lyOrigen">
						<select name="select2" id="cboOrigen" style="width:250px">
						<option value="0">--Seleccione Origen Muestra--</option>
						</select>
					</div>
				<td>
     			</tr>
      			<tr>
				<td class="StormyWeatherFieldCaptionTD">Indicaci&oacute;n&nbsp;</td>
				<td class="StormyWeatherDataTD" colspan="3"><textarea name="textarea" cols="58" class="SaladTextarea" id="Indicacion" style="WIDTH: 430px; HEIGHT: 60px">&nbsp;</textarea>
				</td>
      			</tr>
      			<tr>
        			<td class="StormyWeatherDataTD" align="right" colspan="4"><p>
          				<input name="reset" type="reset" class="SaladButton" id="btncancelar" value="Cancelar Examen">&nbsp;
          				<input name="button" type="button" class="SaladButton" id="btnagregar" onClick="agregarexamenes()" value="Agregar Examen">&nbsp; </p>
				</td>
      			</tr>
    		</table>
    <!-- END Record sec_detallesolicitudestud -->
    <!-- BEGIN Grid sec_solicitudestudios_sec -->
    &nbsp;
  	</form>
 
	<p>
		<input type="hidden" name="{IdHistorialClinico_Name}" value="{IdHistorialClinico}" style="WIDTH: 35px; HEIGHT: 18px" size="4">
	</p>
  	<table width="682" border="0" align="center">
    	<tr>
      		<td ><div align="center">
          		<p class="Estilo3">Ex&aacute;menes Solicitados</p>
      			</div>
		</td>
    	</tr>
  	</table>
 
	<div id="gridatos">
    		<table width="700" border="1" align="center" cellpadding="0" cellspacing="0">
      <!--DWLayoutTable-->
			<tr>
				<td class="CobaltFieldCaptionTD" width="121" height="21" valign="top" nowrap ><p align="center"><strong><font color="#FFFFFF">C&oacute;digo
				Examen</font></strong></p></td>
				<td class="CobaltFieldCaptionTD" width="168" valign="top" nowrap ><p align="center"><strong><font color="#FFFFFF">Nombre
				Examen</font> </strong></p></td>
				<td class="CobaltFieldCaptionTD" width="96" valign="top"  nowrap ><p align="center"><strong><font color="#FFFFFF">Tipo
				Muestra</font> </strong></p></td>
				<td class="CobaltFieldCaptionTD" width="88" valign="top" nowrap ><p align="center"><strong><font color="#FFFFFF">Origen </font></strong></p></td>
				<td class="CobaltFieldCaptionTD" width="219" valign="top" nowrap ><p align="center"><strong><font color="#FFFFFF">Indicaci&oacute;n</font></strong></p></td>
	
			</tr>
	<!-- BEGIN Row -->
			<tr>
				<td height="21" valign="top" class="SaladDataTD">&nbsp;&nbsp;&nbsp;</td>
				<td valign="top" class="SaladDataTD">&nbsp;&nbsp;&nbsp;</td>
				<td valign="top" class="SaladDataTD">&nbsp;&nbsp;&nbsp;</td>
				<td valign="top" class="SaladDataTD">&nbsp;&nbsp;&nbsp;</td>
				<td valign="top" class="SaladDataTD">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td height="2"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td width="0"></td>
			</tr>
      
      <!-- END Row -->
      <!-- BEGIN Separator -->
      <!-- END Separator -->
    		</table>
  	</div>
	<p align="center">&nbsp;
	<!-- END Grid sec_solicitudestudios_sec -->
	&nbsp;&nbsp;
	<!-- BEGIN Record NewRecord1 -->
	</p>
	<p align="center">
	<div id="btnguardar" style="visibility:hidden; position:relative;">
		<form name="" action="" method="post">
      			<table class="" cellspacing="0" cellpadding="0" border="0">
        <!-- BEGIN Error -->
				<tr>
					<td class="SaladErrorDataTD">&nbsp;</td>
				</tr>
		<!-- END Error -->
				<tr>
					<td class="SaladDataTD" align="center">&nbsp;
						<input class="" type="button" value="Guardar Solicitud" id="btnguardar" style="WIDTH: 170px; HEIGHT: 33px" onclick="guardar()">
						<input class="" type="button" value="Ingresar otra Solicitud" id="btnsolicitud" style="WIDTH: 170px; HEIGHT: 33px" disabled onclick="cargar()">
				</tr>
      			</table>
    		</form>
  	</div>
</div>
</p>
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