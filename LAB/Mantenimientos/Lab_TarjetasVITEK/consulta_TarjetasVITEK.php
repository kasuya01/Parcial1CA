<?php session_start();
include_once("clsLab_TarjetasVITEK.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//echo $lugar;
//consulta los datos por su id
$idtarjeta=$_POST['idtarjeta'];
$obj = new clsLab_TarjetasVITEK;
$consulta=$obj->consultarid($idtarjeta,$lugar);
$row = pg_fetch_array($consulta);
//valores de las consultas
$nombretarjeta=$row[1];
$Fechaini=$row[2];
$Fechafin=$row[3];
//echo $Fechaini."+++".$Fechafin;
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar">
  <input name="opcion" type="hidden" value="N" />
	<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Tarjetas VITEK</strong></h3>
		</td>
	</tr>	
	<!-- <tr>
		<td  width="21%" class="StormyWeatherFieldCaptionTD">IdTarjeta</td>
		<td   class="StormyWeatherDataTD"><input name="idtarjeta" id="idtarjeta" type="text" value="<?php echo $idtarjeta; ?>" disabled="disabled" /></td>
	</tr> -->
	<tr>
		<td   width="21%"class="StormyWeatherFieldCaptionTD">Nombre Tarjeta</td>
		<td class="StormyWeatherDataTD"> <input name="nombretarjeta" id="nombretarjeta" type="text" value="<?php echo $nombretarjeta; ?>" size="40" />
                    <input name="idtarjeta" id="idtarjeta" type="hidden" value="<?php echo $idtarjeta; ?>" disabled="disabled" />
                </td>    
	</tr>
	<tr>
       	<td colspan="2" class="StormyWeatherDataTD">
			<table width="100%" border="0" align="center" class="StormyWeatherFormTABLE">
			<tr>
				<td  width="21%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
				<td width="29%" class="StormyWeatherDataTD">
					<input name="txtFechainicio1" type="text" id="txtFechainicio" value="<?php echo $Fechaini; ?>" size="8" >dd/mm/aaaa</td>
				<td width="21%" class="StormyWeatherFieldCaptionTD">Fecha Final</D>
				<td width="29%" class="StormyWeatherDataTD">
					<input name="txtFechaFin1" type="text" id="txtFechaFin" value="<?php echo $Fechafin; ?>" size="8" >dd/mm/aaaa</td>
			</tr>
			</table>
		</td>		
	</tr>
	<tr>
		<td class="StormyWeatherDataTD" colspan="2" align="right">
			<input type="button" name="btnActualizar" value="Actualizar" onclick="ActualizarDatos();" />
			<input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntTarjetasVITEK.php')" />
		</td>
	</tr>
</table>
</form>

