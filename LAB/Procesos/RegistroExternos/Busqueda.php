<?php 
@session_start();
//include("../indexCitas2.php");
include_once("../../../Conexion/ConexionBD.php");
//$IdEstablecimiento=$_SESSION['IdEstablecimiento'];
$LugardeAtencion=$_SESSION['Lugar'];
$nec=$_GET['nec'];
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
</head>

<body class="MailboxPageBody">
<center>
<strong>
	<h2 align="center"><img class="MailboxInput" style="WIDTH: 57px; HEIGHT: 38px" height="94" src="../../../Iconos/buscar.gif" width="106">
            &nbsp;Busqueda de paciente Externo</h2>
	</strong> 


<div id="Busqueda" >
<table border="1" cellpadding="2" style="border:steelblue 1px; border-collapse: collapse" class="CobaltFormTABLE">
    <tr>
      <th class="CobaltFieldCaptionTD" align="left">Nombre del Establecimiento Externo:&nbsp;</th> 
      <td colspan="8" class="CobaltDataTD">
          <input class="CobaltInput" maxlength="20" size="80" id="NombreEstablecimiento"></td>
          
    </tr>
    <tr>
        <th class="CobaltFieldCaptionTD" align="left">Nombre Paciente:&nbsp;</th> 
        <td colspan="8" class="CobaltDataTD"><input class="CobaltInput" maxlength="40" size="80" id="NombrePaciente">
        <input type="hidden" id="nec" value= '<?php echo $nec;?>'>
        </td>
    </tr>
    <tr>
        <td class="CobaltDataTD" colspan="4" align="right"> <input  type="button" id="ClearInfo" name="ClearInfo" value="NUEVA BUSQUEDA" onClick="javascript:LimpiarConExpe('<?php echo $nec; ?>');"></td>
    </tr>
    <tr style="border-left: 1px solid white; border-right: 1px white">
        <td class="CobaltDataTD" colspan="4" nowrap><h3 align="center"><br/><br/>Datos de la Busqueda</h3></td> 
    </tr>
    <tr>
        <th class="CobaltDataTD" align="center"><h4 align="center">Numero Correlativo Interno</h4></th>
        <th class="CobaltDataTD" align="center"><h4 align="center">Nombre del Paciente</h4></th>
        <th class="CobaltDataTD" align="center"><h4 align="center">Nombre de la Madre</h4></th>
    </tr> 
    <tr>
        <td class="CobaltDataTD" align="center"><!-- Enviodatos() -->
            <a class="CobaltDataLink" href="javascript: VerificarExistente();"><div id="IdNumeroExp"> </div></a></td>
	<td class="CobaltDataTD" align="center">
            <input type="hidden" id="PrimerNombre" >
            <input type="hidden" id="SegundoNombre" >
            <input type="hidden" id="TercerNombre" >
            <input type="hidden" id="PrimerApellido">
            <input type="hidden" id="SegundoApellido">
            <input type="hidden" id="CasadaApellido">
            <input type="hidden" id="FechaNacimiento">
            <input type="hidden" id="Sexo">
            <input type="hidden" id="id_sexo">
            <input type="hidden" id="NombreMadre">
            <input type="hidden" id="NombrePadre">
            <input type="hidden" id="NombreResponsable">
            <input type="hidden" id="idnumeroexpediente">
            <input type="hidden" id="idpacienteref">
            <input type="hidden" id="edad">
            
	<div id="Paciente"><a class="CobaltDataLink"></a></div></td>
	<td class="CobaltDataTD" align="center">
	<div id="NombreMadre1"><a class="CobaltDataLink"></a></div></td>
	</tr>
<!--<input type="hidden" id="NEC" value="">-->

</table></div>

<div id="Externo" style="display:none">
<table border="0" cellpadding="2" style="border:steelblue 1px; border-collapse: collapse" class="CobaltFormTABLE">

       <tr>
      <td class="CobaltDataTD" colspan="4" nowrap><h3 align="center">Datos del Paciente</h3></td> 
	</tr>
 
    <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Apellidos:<font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="PrimerApellido_Name" id="PrimerApellido_Name" onfocus="" placeholder="Primer Apellido"></td> 
     
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="SegundoApellido_Name" id="SegundoApellido_Name" placeholder="Segundo Apellido"></td> 
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="CasadaApellido_Name" id="CasadaApellido_Name" placeholder="Apellido de Casada"></td> 
    </tr>   
     <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre:<font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="PrimerNombre_Name" id="PrimerNombre_Name" onfocus=""></td> 
<!--      <td nowrap class="CobaltFieldCaptionTD"><strong>Segundo Nombre: <font color="#ff0000">*</font> </strong></td> -->
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="SegundoNombre_Name" id="SegundoNombre_Name" placeholder="Segundo Nombre"></td> 
<!--      <td nowrap class="CobaltFieldCaptionTD"><strong>Tercer Nombre: <font color="#ff0000">*</font> </strong></td> -->
      <td class="CobaltDataTD">
	  <input class="MailboxInput" style="WIDTH: 190px;" maxlength="20" name="TercerNombre_Name" id="TercerNombre_Name" placeholder="Tercer Nombre"></td> 
    </tr>    
    <tr>
       <td nowrap class="CobaltFieldCaptionTD"><strong>Edad <font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD">
	  <input onBlur="esEdadValida(this)" onfocus="limpiarcampoedad('Edad')" id="Edad" class="MailboxInput" maxlength="3" style="width:50px;" size="3" min="0" max="120">
          <input type="hidden" id="Edadini">
          <input id="FechaNacimiento_Name" maxlength="10" class="MailboxInput" style="width:135px;" size="10" disabled="disabled" style="">
		  
      <td nowrap class="CobaltFieldCaptionTD" align="center"><strong>Sexo: <font color="#ff0000">*</font></strong></td> 
      <td class="CobaltDataTD">
      <select id="Sexo_Name" class="CobaltSelect" style="width: 95%">
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
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre del Padre:</strong></td> 
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NombrePadre_Name" style="WIDTH: 527px;"></td>
        </tr>
           <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Nombre Responsable:</strong></td> 
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NombreResponsable_Name" style="WIDTH: 527px;"></td>
        </tr>
    <tr>
      <td nowrap class="CobaltFieldCaptionTD"><strong>Numero de Expediente de Referencia:<font color="#ff0000">*</font> </strong></td> 
      <td class="CobaltDataTD" colspan="4">
        <input class="CobaltInput" maxlength="80" size="21" id="NumeroExpediente_Referencia" style="WIDTH: 190px;"></td> 
    </tr>
  <tr>
    <td colspan="4" align="center" nowrap class="MailboxFooterTD">
    <br/><input type="button" id="GetInfor" name="GetInfo" value=" GUARDAR DATOS " onClick="javascript:GuardarInformacionExterna();">
	<!-- <input type="text" id="Establecimiento" value="<?php// echo $IdEstablecimiento; ?>">-->
	<input type="hidden" id="LugarAtencion" value="<?php echo $LugardeAtencion; ?>">
	<input type="hidden" id="EstablecimientoExterno" value="">

	<input type="button" id="ClearInfo" name="ClearInfo" value="NUEVA BUSQUEDA" onClick="javascript:LimpiarConExpe('<?php echo $nec; ?>');"></td>
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
		var num_exp = document.getElementById('nec').value;
            //    alert (num_exp)
                if(IdEstablecimiento == '')
                {
                  alert("  Seleccione un establecimiento por favor")  
                }
                else{
               //alert('Estab'+IdEstablecimiento+ ' thisval: '+this.value+' num_exp: '+num_exp);
                return 'respuesta.php?Bandera=3&ctr=1&q='+this.value+'&IdEstablecimiento='+IdEstablecimiento+'&num_exp='+num_exp; 
                }
	   });
	</script>

</body>
</center>
</html>



