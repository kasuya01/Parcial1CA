<?php session_start();
include_once("clsLab_Observaciones.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idobservacion=$_POST['idobservacion'];
//echo $idobservacion;
$obj = new clsLab_observaciones;
$consulta=$obj->consultarid($idobservacion);

$row = pg_fetch_array($consulta);

//valores de las consultas
$observacion=$row['observacion'];
$tiporespuesta=$row[1];
$idarea=$row['idarea'];
$nombrearea=$row['nombrearea'];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N"/>
	<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center">
                    <h3><strong>Mantenimiento de Observaciones</strong></h3>
		</td>
	</tr>	
	<!--<tr>
		<td class="StormyWeatherFieldCaptionTD">Idobservacion</td>
		<td class="StormyWeatherDataTD"><input name="idobservacion" id="idobservacion" type="text" value="<?php echo $idobservacion; ?>" disabled="disabled" /></td>
	</tr> -->
	<tr>
        <input name="idobservacion" id="idobservacion" type="hidden" value="<?php echo $idobservacion; ?>" disabled="disabled" />   
        <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
        <td class="StormyWeatherDataTD">
			<select id="cmbArea" name="cmbArea" size="1">
			<option value="0" >--Seleccione un Area--</option>";
			<?php
			include('../Lab_Areas/clsLab_Areas.php');
			$objeareas=new clsLab_Areas;
			$consulta= $objeareas->consultaractivas($lugar);
			while($row = pg_fetch_array($consulta)){
			echo "<option value='" . $row['idarea']. "'>" . htmlentities($row['nombrearea']) . "</option>";
			}
			echo "<option value='" . $idarea . "' selected='selected'>" . htmlentities($nombrearea). "</option>";
			?>		  
			</select>
		</td>
    </tr>
	<tr>
        <td class="StormyWeatherFieldCaptionTD">Tipo Respuesta </td>
        <td class="StormyWeatherDataTD">
			<select id="cmbTipoRespuesta" name="cmbTipoRespuesta" size="1" >
			<option value="0" >--Seleccione--</option>
			<option value="P" >Positivo</option>
			<option value="N" >Negativo</option>
			<option value="O" >Otro</option>
			/*<?php
				if ($tiporespuesta=="P"){
				echo "<option value='P' selected='selected'> Positiva </option>";
				}
				else if($tiporespuesta=="N") {
				echo "<option value='N' selected='selected'> Negativa </option>";
				}
                                else{
				echo "<option value='O' selected='selected'> Otro </option>";
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n</td>
		<td class="StormyWeatherDataTD">
			<textarea name="txtobservacion" cols="60" id="txtobservacion"<span style="color: #0000FF;background-color:#87CEEB;"><?php echo htmlentities($observacion); ?>
			</textarea>
                    
		</td>    
	</tr>
	<!--<tr>
		<td colspan="2" class="StormyWeatherDataTD" align="right">
		<input type="submit" name="btnActualizar" value="Actualizar" />
                <input type="button" name="Submit" value="Nuevo" onClick="window.location.replace('MntObservaciones.php')">    
	</tr>-->
        
        <tr>
                <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='submit' align="center" class='btn btn-primary'  ><span class='glyphicon glyphicon-repeat'></span> Actualizar</button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntObservaciones.php')"><span></span> Nuevo</button>
                
                </td>
        </tr>  
        
        
	</table>
</form>

