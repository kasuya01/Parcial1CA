<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Mantenimiento de Antibioticos</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Antibioticos.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>
<script language="JavaScript" type="text/javascript">
function ActualizarDatos()
{enviarDatos();}
function MostrarNuevo()
{ LimpiarContoles();}
</script>
<body onLoad="show_event(1);">
<?php 

//echo "*".$corr."-".$lugar."-".$area."-".$nivel;
 
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); } 
?><br>
<table align="center" width="100%">
	<tr>
		<td>
			<div  id="divFrmNuevo" >
			<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
			<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de Antibi&oacute;ticos</h3></strong>
					</td>
				</tr>
				<!--<tr>
					<td class="StormyWeatherFieldCaptionTD">IdAntibiotico</td>
					<td class="StormyWeatherDataTD"><input type="text" name="txtidantibiotico" id="txtidantibiotico" disabled="disabled" /></td>
				</tr>  -->
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Antibi&oacute;tico </td>
					<td class="StormyWeatherDataTD"><input type="text" name="txtantibiotico" id="txtantibiotico" size="40" />
                                            <input type="hidden" name="txtidantibiotico" id="txtidantibiotico" disabled="disabled" /></td>
				</tr>
				<tr>
					<td class="StormyWeatherDataTD" colspan="2" align="right">
						<input type="submit" name="Submit" value="Guardar" onClick="Guardar()">
						<input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
						<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntAntibioticos.php')"></td>
                                                                                                                        
                </tr>
			</table>
			</form>
			</div>
		</td>
	</tr>
	<tr>
		<td>
		</td>
	</tr>
	<tr>
		<td>
			<div  id="divFrmModificar" >

			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div  id="divinicial" >

			</div>

		</td>
	</tr>
</table>
</body>
</html>
