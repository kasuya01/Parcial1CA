<?php session_start();
include_once("clsMnt_OrigenMuestra.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idorigen=$_POST['idorigen'];
$objareas = new clsMnt_OrigenMuestra;
$consulta=$objareas->consultarid($idorigen);

$row = pg_fetch_array($consulta);
//valores de las consultas
$nombreorigen=$row['origenmuestra'];
$idtipomuestra=$row['idtipomuestra'];
$tipomuestra=$row['tipomuestra'];
$consulta= $objareas->LeerTipoMuestra();

//muestra los datos consultados en los campos del formulario
?>
<form name= "frmModificar" action="" onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N" />
<table width="25%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Origen de Muestra</strong></h3>
		</td>
	</tr>      
	<tr>
	    <td class="StormyWeatherFieldCaptionTD">Tipo Muestra</td>
	    <td class="StormyWeatherDataTD">
		<select id="cmbTipoMuestra" name="cmbTipoMuestra" size="1" class="form-control height">
                    <option value="0">--Seleccione un Tipo de Muestra--</option>
                    <?php
			 //include('clsMnt_OrigenMuestra.php');
			while($row = pg_fetch_array($consulta)){
                            if ($row['id']!=$idtipomuestra){
                                echo "<option value='" . $row['id']. "'>" .$row['tipomuestra']. "</option>";
                            }
                        
                        }
                        
			echo "<option value='" . $idtipomuestra . "' selected='selected'>" .$tipomuestra. "</option>";
			?>
                </select>
	    </td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Nombre del &Aacute;rea</td>
		<td class="StormyWeatherDataTD"><input name="nombreorigen" id="nombreorigen" size="40" type="text" value="<?php echo $nombreorigen; ?>" /><input name="idorigen" id="idorigen" type="hidden" value="<?php echo $idorigen; ?>" disabled="disabled"/></td>    
	</tr>
	<tr>
		<td colspan="2" align="right" class="StormyWeatherDataTD">
                        <button type="button"  value="btnActualizar" class="btn btn-primary" onClick="enviarDatos();"><span class="glyphicon glyphicon-repeat"></span> Actualizar </button>
                        <button name="btnNuevo" value="Nuevo" class="btn btn-primary" onClick="window.location.replace('MntOrigenMuestra.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
			<!--<input type="submit" align="right" name="btnActualizar" value="Actualizar"/>	
			<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntOrigenMuestra.php')">-->
		</td>
	</tr>
		
</table>
</form>

