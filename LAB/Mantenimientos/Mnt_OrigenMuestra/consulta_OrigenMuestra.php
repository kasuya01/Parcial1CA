<?php session_start();
include_once("clsMnt_OrigenMuestra.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idorigen=$_POST['idorigen'];
$objareas = new clsMnt_OrigenMuestra;
$consulta=$objareas->consultarid($idorigen);

$row = mysql_fetch_array($consulta);
//valores de las consultas
$nombreorigen=$row['OrigenMuestra'];
$idtipomuestra=$row['idtipomuestra'];
$tipomuestra=$row['tipomuestra'];

//muestra los datos consultados en los campos del formulario
?>
<form name= "frmModificar" action="" onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N" />
<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Origen de Muestra</strong></h3>
		</td>
	</tr>
	<tr>
	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo Origen Muestra </td>
	<td class="StormyWeatherDataTD"><input name="idorigen" id="idorigen" type="text" value="<?php echo $idorigen; ?>" disabled="disabled"/>
		</td>
	</tr>
	<tr>
	    <td class="StormyWeatherFieldCaptionTD">Tipo Muestra</td>
	    <td class="StormyWeatherDataTD">
			<select id="cmbTipoMuestra" name="cmbTipoMuestra" size="1">
            <option value="0">--Seleccione un Tipo de Muestra--</option>
            <?php
			 //include('clsMnt_OrigenMuestra.php');
			$obje=new clsMnt_OrigenMuestra;
			$consulta= $obje->LeerTipoMuestra();
			while($row = mysql_fetch_array($consulta)){
			  echo "<option value='" . $row[0]. "'>" .htmlentities($row[1]). "</option>";
			}
			echo "<option value='" . $idtipomuestra . "' selected='selected'>" .$tipomuestra. "</option>";
			?>
             </select>
		</td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Nombre del &Aacute;rea</td>
		<td class="StormyWeatherDataTD"><input name="nombreorigen" id="nombreorigen" size="40" type="text" value="<?php echo htmlentities($nombreorigen); ?>" /></td>    
	</tr>
	<tr>
		<td colspan="2" align="right" class="StormyWeatherDataTD">
			<input type="submit" align="right" name="btnActualizar" value="Actualizar"/>	
			<input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntOrigenMuestra.php')">
		</td>
	</tr>
		
</table>
</form>

