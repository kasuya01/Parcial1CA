<?php session_start();
include_once("clsLab_Antibioticos.php");
$lugar=$_SESSION['Lugar'];
$corr=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idantibiotico=$_POST['idantibiotico'];
$obj = new clsLab_Antibioticos;
$consulta=$obj->consultarid($idantibiotico);
$row = pg_fetch_array($consulta);

//valores de las consultas
$antibiotico=$row[1];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar">
  <input name="opcion" type="hidden" value="N" />
	<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
		<tr>
			<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Antibi&oacute;ticos</strong></h3>
			</td>
		</tr>
		<!--<tr>
			<td class="StormyWeatherFieldCaptionTD">IdAntibiotico</td>
			<td class="StormyWeatherDataTD"><input name="idantibiotico" id="idantibiotico" type="text" value="<?php echo $idantibiotico; ?>" disabled="disabled" />
			</td>
		</tr> -->
		<tr>
			<td class="StormyWeatherFieldCaptionTD">Antibi&oacute;tico</td>
			<td class="StormyWeatherDataTD"><input name="antibiotico" id="antibiotico" type="text" value="<?php echo htmlentities($antibiotico); ?>" size="40" />
                            <input name="idantibiotico" id="idantibiotico" type="hidden" value="<?php echo $idantibiotico; ?>" disabled="disabled" />
                        </td>    
		</tr>
			<td class="StormyWeatherDataTD" colspan="2" align="right">
				<button type="button"  value="btnActualizar" class="btn btn-primary" onClick="ActualizarDatos();"><span class="glyphicon glyphicon-repeat"></span> Actualizar </button>
                                <button type="button" name="Cancelar"  class="btn btn-primary" value="Cancelar" onClick="window.location.replace('MntAntibioticos.php'"><span class='glyphicon glyphicon-refresh'></span> Nuevo</button>
                                <!--<input type="button" name="btnActualizar" value="Actualizar" onclick="ActualizarDatos()" />
				<input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntAntibioticos.php')" />-->
			</td>

	</table>
</form>

