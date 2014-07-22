<?php session_start();
include_once("clsLab_CodigosEstandar.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$id=$_POST['idestandar'];
$obj = new clsLab_CodigosEstandar;
$consulta=$obj->consultarid($id);

$row = pg_fetch_array($consulta);

//valores de las consultas
$idestandar=$row['idestandar'];
$descripcion=$row['descripcion'];
$idgrupo=$row['idgrupo'];
$grupo=$row['nombregrupo'];
//echo $idgrupo." - ".$grupo;
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" 
onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N" />
<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">

<tr>
	<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de C&oacute;digos Est&aacute;ndares de Laboratorio</strong></h3>
	</td>
</tr>
	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo del Est&aacute;ndar</td>
	<td class="StormyWeatherDataTD"><input name="idestandar" id="idestandar" type="text" value="<?php echo $idestandar; ?>" disabled="disabled" /></td>
</tr>
<tr>
	<td class="StormyWeatherFieldCaptionTD">Descripci&oacute;n </td>
	<td class="StormyWeatherDataTD"><input name="descripcion" id="descripcion" type="text" value="<?php echo htmlentities ($descripcion); ?>" size="45" />
	</td>    
</tr>
<tr>
      <td class="StormyWeatherFieldCaptionTD">Grupo</td>
      <td class="StormyWeatherDataTD">
	<select id="cmdgrupo" name="cmbgrupo" size="1">
            <option value="0" >--Seleccione un grupo--</option>
		<?php
                     $obj=new clsLab_CodigosEstandar;
                     $consulta= $obj->Leer_grupos();
		     while($row = pg_fetch_array($consulta)){
		         echo "<option value='" . $row['id']. "'>".$row['idgrupo']."  - ".htmlentities($row['nombregrupo']) . "</option>";
		     }//echo $idgrupo."--".$grupo;
                         echo "<option value='" . $idgrupo . "' selected='selected'>" .htmlentities($grupo). "</option>";
                
		?>		  
        </select>		 
      </td>
</tr>
<tr>
	<td class="StormyWeatherDataTD" colspan="2" align="right">
	    <input type="submit" name="btnActualizar" value="Actualizar" />
	    <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntCodigosEstandar.php')">
	</td>
</tr>
</table>
</form>

