<?php session_start();
include_once("clsLab_TipoMuestra.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];

//consulta los datos por su id
$idtipo=$_POST['idtipo'];
$obj = new clsLab_TipoMuestra;
$consulta=$obj->consultarid($idtipo);
$row = mysql_fetch_array($consulta);
//valores de las consultas
$nombre=$row['TipoMuestra'];
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" >
  <input name="opcion" type="hidden" value="N" />
<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
<tr>
	<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Muestras</strong></h3>
	</td>
</tr>
<tr>
	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo Muestra</td>
	<td class="StormyWeatherDataTD"><input name="idtipo" id="idtipo" type="text" value="<?php echo $idtipo; ?>" disabled="disabled" />	  </td>
</tr>
<tr>
	<td class="StormyWeatherFieldCaptionTD">Nombre de la Muestra</td>
	<td class="StormyWeatherDataTD"><input name="nombretipo"id="nombretipo" type="text" value="<?php echo htmlentities($nombre); ?>" size="40"  />
	</td>    
</tr>
<tr>
	<td class="StormyWeatherDataTD" colspan="2" align="right">
		<input type="button" name="btnActualizar" value="Actualizar" onclick="ModificarRegistro();" />
		<input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntTipoMuestra.php')">
	</td>
</tr>
</table>
</form>

