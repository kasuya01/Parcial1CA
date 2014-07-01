<?php session_start();
include_once("clsLab_Formularios.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$IdFormulario=$_POST['IdFormulario'];
//echo $IdFormulario;
$obj = new clsLab_Formularios;
$consulta=$obj->consultarid($IdFormulario,$lugar);
$row = mysql_fetch_array($consulta);

//valores de las consultas
$IdFormulario=$row[0];
$Formulario=$row[1];
$IdPrograma=$row[2];
$Programa=$row[3];
//echo $Programa;
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" onsubmit="enviarDatos(); return false">
	<input name="opcion" type="hidden" value="N" />
	<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
		<tr>
			<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Formularios</strong></h3>
			</td>
		</tr><tr>
			<td class="StormyWeatherFieldCaptionTD">IdFormulario</td>
			<td class="StormyWeatherDataTD"><input name="IdFormulario" id="IdFormulario" type="text" value="<?php echo $IdFormulario; ?>" disabled="disabled" /></td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD">Formulario</td>
			<td class="StormyWeatherDataTD"><input name="Formulario" id="Formulario" type="text" value="<?php echo htmlentities($Formulario); ?>" size="50" />
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD">Programa</td>
			<td class="StormyWeatherDataTD"><select id="cmbPrograma" name="cmbPrograma" size="1" >			 
			<?php
										$consulta= $obj->consultarProgramas($lugar);
										while($row = mysql_fetch_array($consulta)){
											echo "<option value='" .$row['IdPrograma']. "'>" . $row['NombrePrograma'] . "</option>";
										}					            	
			echo "<option value='" . $IdPrograma . "' selected='selected'>" .htmlentities($Programa). "</option>";
			?>		  
            </select></td>	
		</tr>
		<tr>
			<td class="StormyWeatherDataTD" colspan="2" align="right">
			    <input type="submit" name="btnActualizar" value="Actualizar" />
			    <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntProgramas.php')">
			</td>
		</tr>	
	</table>
</form>

