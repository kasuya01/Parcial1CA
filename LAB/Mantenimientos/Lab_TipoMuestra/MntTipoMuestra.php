<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head>
 <meta http-equiv="content-type" content="text/html;" http-equiv="content-type" charset="utf-8">
<title>Mantenimiento de Tipo de Muestra</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_TipoMuestra.js"></script>
<script language="JavaScript" type="text/javascript">
function GuardarRegistro()
{
  IngresarRegistro();
}

function ModificarRegistro()
{
	enviarDatos();
}
function LimpiarControles()
{
  MostrarFormularioNuevo();
}
function Cancelar()
{
    show_event(1);
}

</script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">

<?php 
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
?><br>

<table align="center" width="100%">
<tr>
<td>
<div  id="divFrmNuevo" >
<form name="frmnuevo">
<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Muestras</strong></h3>
		</td>
	</tr>
<!--    <tr>
        <td class="StormyWeatherFieldCaptionTD">C&oacute;digo Muestra </td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtidtipo" id="txtidtipo" disabled="disabled" /></td>
    </tr>-->
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Nombre de la Muestra </td>
        <td class="StormyWeatherDataTD"><input name="txtnombretipo" id="txtnombretipo" type="text" size="32" /></td>
    </tr>
    <tr>
        <td class="StormyWeatherDataTD" colspan="2" align="right">
            <input type="button" name="Submit" value="Guardar" onClick="GuardarRegistro();">
	    <input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
	   <!-- <input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">-->
	    <input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntTipoMuestra.php')">
        </td>
    </tr>
</table>
</form>
</div>
</td>
</tr>
<tr>
<td>
<div  id="divFrmModificar" >

</div>
<div  id="divinicial" >

</div>
</td>
</tr>
</table>
</body>
</html>
