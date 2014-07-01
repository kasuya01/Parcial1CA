<?php session_start();
include_once("clsLab_CantidadTincion.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idCanTin=$_POST['idCanTin'];
$obj = new clsLab_CantidadTincion;
$consulta=$obj->consultarid($idCanTin);
$row = mysql_fetch_array($consulta);

//valores de las consultas
$CanTin=$row[1];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" 
onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N" />
	<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Cantidad Tinci&oacute;n</strong></h3>
		</td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">IdCantidad</td>
		<td class="StormyWeatherDataTD"><input name="idCanTin" id="idCanTin" type="text" value="<?php echo $idCanTin; ?>" disabled="disabled" /></td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Cantidad Tinci&oacute;n</td>
		<td class="StormyWeatherDataTD"><input name="CanTin" id="CanTin" type="text" value="<?php echo htmlentities($CanTin); ?>" size="35" />
		</td>  
	</tr>
	<tr>	
		<td class="StormyWeatherDataTD" colspan="2" align="right">
			<input type="submit" name="btnActualizar" value="Actualizar" /><input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntCantidadTincion.php')">
		</td>
	</tr>
</table>
</form>

