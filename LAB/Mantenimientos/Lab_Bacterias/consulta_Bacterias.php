<?php session_start();
include_once("clsLab_Bacterias.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idbacteria=$_POST['idbacteria'];
$obj = new clsLab_Bacterias;
$consulta=$obj->consultarid($idbacteria);
$row = pg_fetch_array($consulta);

//valores de las consultas
$bacteria=$row[1];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" 
onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N" />
<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">

<tr>
	<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Bacterias</strong></h3>
	</td>
</tr>
<!--<tr>  
	<td class="StormyWeatherFieldCaptionTD">IdBacteria</td> 
	<td class="StormyWeatherDataTD"><input name="idbacteria" id="idbacteria" type="text" value="<?php echo $idbacteria; ?>" disabled="disabled" /></td> -->
<!--</tr> -->
<tr>
	<td class="StormyWeatherFieldCaptionTD">Bacteria</td>
	<td class="StormyWeatherDataTD"><input name="bacteria" id="bacteria" type="text" value="<?php echo htmlentities($bacteria); ?>" class="form-control" size="50" />
            <input name="idbacteria" id="idbacteria" type="hidden" value="<?php echo $idbacteria; ?>"  disabled="disabled" />
            
        </td>    
</tr>
	<!--<td class="StormyWeatherDataTD" colspan="2" align="right">
	    <input type="submit" name="btnActualizar" value="Actualizar" />
	    <input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntBacterias.php')">
	</td>-->
        
        
        <tr>
                <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='submit' align="center" class='btn btn-primary'  ><span class='glyphicon glyphicon-repeat'></span> Actualizar</button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntBacterias.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                
                </td>
            </tr>  
</table>
</form>

