<?php session_start();
include_once("clsLab_TipoMuestra.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];

//consulta los datos por su id
$idtipo=$_POST['idtipo'];
$obj = new clsLab_TipoMuestra;
$consulta=$obj->consultarid($idtipo);
$row = pg_fetch_array($consulta);
//valores de las consultas
$nombre=$row['tipomuestra'];
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
	<td class="StormyWeatherFieldCaptionTD">Nombre de la Muestra</td>
	<td class="StormyWeatherDataTD"><input name="nombretipo"id="nombretipo" type="text" value="<?php echo $nombre; ?>" size="40"  /><input name="idtipo" id="idtipo" type="hidden" value="<?php echo $idtipo; ?>" disabled="disabled" />
	</td>    
</tr>
<tr>
	<td class="StormyWeatherDataTD" colspan="2" align="right">
                <button type='button' align="center" class='btn btn-primary' id='btnActualizar' onclick='ModificarRegistro(); '><span class='glyphicon glyphicon-repeat'></span> Actualizar </button>
		<!--<input type="button" name="btnActualizar" value="Actualizar" onclick="ModificarRegistro();" />
		<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntTipoMuestra.php')">-->
                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntTipoMuestra.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
	</td>
</tr>
</table>
</form>

