<?php 
@session_start();
//include("../indexCitas2.php");
include_once("../../../Conexion/ConexionBD.php");
//$IdEstablecimiento=$_SESSION['IdEstablecimiento'];
$LugardeAtencion=$_SESSION['Lugar'];
//echo "lugar de atencion" . $LugardeAtencion;
?>
<html>
<head>
<title>Procedimientos..</title>
<script language="javascript" src="Includes/Funciones.js">
</script>
<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--  
<link rel="stylesheet" type="text/css" href="../../Webstyle/Themes/Cobalt/Style.css">-->
</head>

<body class="MailboxPageBody">
<center>
<strong>
	<h2 align="center"><img class="MailboxInput" style="WIDTH: 57px; HEIGHT: 38px" height="94" src="../../../Iconos/buscar.gif" width="106">
            &nbsp;Busqueda de paciente Externo</h2>
	</strong> 


<div id="Busqueda" >
<table border="1" cellpadding="3" class="CobaltFormTABLE">
    <tr>
      <td class="CobaltFieldCaptionTD">Nombre del Establecimiento Externo:&nbsp;</td> 
      <td colspan="8" class="CobaltDataTD">
          <input class="CobaltInput" maxlength="20" size="80" id="NombreEstablecimiento"></td>
          
    </tr>
    <tr>
        <td class="CobaltFieldCaptionTD">Nombre Paciente:&nbsp;</td> 
        <td colspan="8" class="CobaltDataTD"><input class="CobaltInput" maxlength="40" size="80" id="NombrePaciente"></td>
    </tr>
    <tr>
        <td class="CobaltDataTD" colspan="4" align="right"> <input  type="button" id="ClearInfo" name="ClearInfo" value="NUEVA BUSQUEDA" onClick="javascript:Limpiar();"></td>
    </tr>
    <tr>
        <td class="CobaltDataTD" colspan="4" nowrap><h3 align="center">Datos de la Busqueda</h3></td> 
    </tr>
    <tr>
        <td class="CobaltDataTD" align="center"><h4 align="center">Numero Correlativo Interno</h4>
        <td class="CobaltDataTD" align="center"><h4 align="center">Nombre del Paciente</h4>
        <td class="CobaltDataTD" align="center"><h4 align="center">Nombre de la Madre</h4>
    </tr> 
    <tr>
        <td class="CobaltDataTD" align="center"><!-- Enviodatos() -->
            <a class="CobaltDataLink" href="javascript: VerificarExistente();"><div id="IdNumeroExp"> </div></a></td>
	<td class="CobaltDataTD" align="center">
            <input type="hidden" id="PrimerNombre" >
            <input type="hidden" id="PrimerApellido">
            <input type="hidden" id="FechaNacimiento">
            <input type="hidden" id="Sexo">
            <input type="hidden" id="NombreMadre">
            
	<div id="Paciente"><a class="CobaltDataLink"></a></div></td>
	<td class="CobaltDataTD" align="center">
	<div id="NombreMadre1"><a class="CobaltDataLink"></a></div></td>
	</tr>
<input type="hidden" id="NEC" value="">
</table></div>

<div id="Externo" style="display:none">
<table border="0" cellpadding="3" class="CobaltFormTABLE">

       <tr>
      <td class="CobaltDataTD" colspan="4" nowrap><h3 align="center">Datos del Paciente</h3></td> 
	</tr>
 
    <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Primer Apellido:<font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="PrimerApellido_Name" id="PrimerApellido_Name" onfocus=""></td> 
      <td nowrap class="CobaltFieldCaptionTD"><strong>Primer Nombre: <font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="PrimerNombre_Name" id="PrimerNombre_Name"></td> 
    </tr>    
    <tr>
       <td nowrap class="CobaltFieldCaptionTD"><strong>Edad <font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD">
	  <input onBlur="esEdadValida(this)" id="Edad" class="MailboxInput" maxlength="3" style="width:50px;" size="3">
          <input id="FechaNacimiento_Name" maxlength="10" class="MailboxInput" style="width:135px;" size="10" disabled="disabled">
		  
      <td nowrap class="CobaltFieldCaptionTD"><strong>Sexo: <font color="#ff0000">*</font></strong></td> 
      <td class="CobaltDataTD">
      <select id="Sexo_Name" class="CobaltSelect">
          <option value="0" selected>--Seleccionar--</option>
		  <option value="1">Masculino</option>
		  <option value="2">Femenino</option>
        </select></td>   
	</tr>
	
	<tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre de la Madre:<font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NombreMadre_Name" style="WIDTH: 527px;"></td>
        </tr>
    <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Numero de Expediente de Referencia:<font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NumeroExpediente_Referencia" style="WIDTH: 190px;"></td> 
    </tr>
  <tr>
    <td colspan="2" align="right" nowrap class="MailboxFooterTD">
    <input type="button" id="GetInfor" name="GetInfo" value=" GUARDAR DATOS " onClick="javascript:GuardarInformacionExterna();">
	<!-- <input type="text" id="Establecimiento" value="<?php// echo $IdEstablecimiento; ?>">-->
	<input type="hidden" id="LugarAtencion" value="<?php echo $LugardeAtencion; ?>">
	<input type="hidden" id="EstablecimientoExterno" value="">
	<input type="button" id="ClearInfo" name="ClearInfo" value="NUEVA BUSQUEDA" onClick="javascript:Limpiar();"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;<div id="Datos"></div></td>
  </tr>
</table></div>

<script>
	   new Autocomplete('NombreEstablecimiento', function() { 
		
		   return 'respuesta.php?Bandera=2&ctr=1&q=' + this.value; 
	   });
            new Autocomplete('NombrePaciente', function() { 
		var IdEstablecimiento = document.getElementById('EstablecimientoExterno').value;
                if(IdEstablecimiento == '')
                {
                  alert("  SELECCIONE EL ESTABLECIMIENTO  !!!!")  
                }
                else{
                //alert(IdEstablecimiento);
                return 'respuesta.php?Bandera=3&ctr=1&q='+this.value+'&IdEstablecimiento='+IdEstablecimiento; 
                }
	   });
	</script>

</body>
</center>
</html>



