
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

<title>DATOS GENERALES DEL PACIENTE</title>
<link rel="stylesheet" type="text/css" href="../../../webstyle/Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">

<!--referencias del estilo del calendario-->

<link rel="stylesheet" type="text/css" media="all" href="../../../calendarstructure/skins/aqua/theme.css" title="Aqua" />
<link rel="alternate stylesheet" type="text/css" media="all" href="../../../calendarstructure/calendar-blue.css" title="blue" />

<!--llamado al archivo de funciones del calendario-->

<script type="text/javascript" src="../../../calendarstructure/calendar.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-es.js"></script>
<script type="text/javascript" src="../../../calendarstructure/calendar-setup.js"></script>

<script language="JavaScript" type="text/javascript">
var d=new Date();

function validar(){
	if(document.getElementById('PrimerApellido_Name').value==""){
		alert(".: Error: No puede dejar el PRIMER APELLIDO en blanco");
		return false;
	}
		
	if(document.getElementById('PrimerNombre_Name').value==""){
		alert(".: Error: No puede dejar el PRIMER NOMBRE en blanco");
		return false;
	}
}

function enviar(){
accion=1;

	PrimerApellido = document.getElementById('PrimerApellido_Name').value;
	PrimerNombre= document.getElementById('PrimerNombre_Name').value;
	FechaNacimiento = document.getElementById('FechaNacimiento_Name').value;
	//NoExpediente = document.getElementById('NoExpediente').value;
	Sexo_N=document.getElementById('Sexo_Name').value;
	
	if(document.getElementById('PrimerApellido_Name').value==""){
		alert(".: Error: No puede dejar el PRIMER APELLIDO en blanco");
		return false;
	}
		
	if(document.getElementById('PrimerNombre_Name').value==""){
		alert(".: Error: No puede dejar el PRIMER NOMBRE en blanco");
		return false;
	}
	
	if(document.getElementById('Sexo_Name').value==0){
		alert(".: Error: No puede dejar el SEXO del paciente en blanco");
		return false;
	}
	
	if(!IsFecha(document.getElementById('FechaNacimiento_Name').value)){
		alert('Por favor solo introduzca numeros en este campo') 
		document.getElementById('FechaNacimiento_Name').focus();
		return false;
	}	
	
	if(document.getElementById('FechaNacimiento_Name').value==0){
		alert(".: Error: No puede dejar la FECHA DE NACIMIENTO en blanco");
		return false;
	}
	
	if (window.XMLHttpRequest) {
		sendReq = new XMLHttpRequest();
	  } else if(window.ActiveXObject) {
		sendReq = new ActiveXObject("Microsoft.XMLHTTP");
	  } else{
	  	alert("no pudo crearse el objeto")
	}
		
	sendReq.onreadystatechange = procesar;
	sendReq.open("POST", 'ajax_recepcion.php', true);
	sendReq.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
	var param = 'Proceso=enviar';
	param += '&PA='+PrimerApellido+'&PN='+PrimerNombre+'&SXN='+Sexo_N+'&FN='+FechaNacimiento;
	sendReq.send(param);  	
}

function ponExpediente(iddato){ 
    	opener.document.frmdatosexpediente.txtexp.value = iddato;
	opener.document.frmdatosexpediente.btnverificar.disabled ="disabled" ;
	opener.document.getElementById('lyLaboratorio').style.display="block";
	window.opener.searchpac();
        window.close();
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

function procesar(){
	if (sendReq.readyState == 4){
		if (sendReq.status == 200){			
			respuesta = sendReq.responseText;	
			switch(accion){
				case 1:  
					iddato=respuesta;
					ponExpediente(iddato);
				break;
			}
		}
	}
}
	
</script>
<style type="text/css">
<!--
body {
	background-color: #F0F1F6;
}
-->
</style></head>

<body text="#000000" class="CobaltPageBody" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="frmpacfirstime.PrimerApellido_Name.focus()">
<link href="../../../css/paginalab.css" rel="stylesheet" type="text/css" />
<p><br>
<!-- BEGIN Record mnt_datospaciente -->&nbsp;</p>
<form name="frmpacfirstime" action=" " method="post">
  <p align="center"><strong>
  <h2 align="center">&nbsp;REGISTRO
  DE PACIENTES</h2>
 </strong> 
 
<table class="er" cellspacing="1" cellpadding="0" border="1" align="center">
<tr>
	<td class="CobaltFieldCaptionTD" colspan="4" nowrap><h3 align="center">Datos del Paciente</h3></td> 
</tr>
<tr>
	<td nowrap class="StormyWeatherFieldCaptionTD"><strong>Primer Apellido:<font color="#ff0000">*</font> </strong></td> 
	<td  class="StormyWeatherDataTD">
		<input class="CobaltInput" style="WIDTH: 190px;" maxlength="20" name="PrimerApellido_Name" id="PrimerApellido_Name"></td> 
        <td nowrap class="StormyWeatherFieldCaptionTD"><strong>Primer Nombre:<font color="#ff0000">*</font> </strong></td> 
      	<td class="StormyWeatherDataTD">
		<input class="CobaltInput" style="WIDTH: 190px; HEIGHT: 20px" maxlength="20" name="PrimerNombre_Name" id="PrimerNombre_Name"></td> 
</tr>
<tr> 
	<td nowrap class="StormyWeatherFieldCaptionTD"><strong>Sexo: <font color="#ff0000">*</font></strong></td> 
      	<td class="StormyWeatherDataTD">
      		<select id="Sexo_Name" class="CobaltSelect">
          	<option value="0" selected>--Seleccionar--</option>
		  <option value="1">Masculino</option>
		  <option value="2">Femenino</option>
        	</select>
	</td> 
	<td nowrap class="StormyWeatherFieldCaptionTD"><strong>Fecha Nacimiento:<font color="#ff0000">*</font> </strong></td> 
      	<td class="StormyWeatherDataTD">
		<input id="FechaNacimiento_Name" class="CobaltInput" maxlength="10" style="width:188px; height:20px" size="10">
	  	<input type="button" value="..." id="trigger">dd/mm/aaaa</td> 	
 
</tr>
<tr>
	<td class="StormyWeatherDataTD" nowrap align="right" colspan="4">
		<input type="button" value="Registrar Paciente" onClick="enviar()">
	</td>
</tr>
</table>
</form>

<script type="text/javascript">
	Calendar.setup(
		{
		inputField  : "FechaNacimiento_Name",     // ID del campo 
		ifFormat    : "%d/%m/%Y",    // formato de la fecha
		button      : "trigger"      // ID del boton
				
		}
	);
</script>
<!-- END Record mnt_datospaciente -->
</body>
</html>