<?php session_start();
include_once("clsLab_ElementosTincion.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idElemTin=$_POST['idElemTin'];
$obj = new clsLab_ElementosTincion;
$consulta=$obj->consultarid($idElemTin);
$row = pg_fetch_array($consulta);


//valores de las consultas
 $ElemTin=$row[0];

//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" 
onsubmit="enviarDatos(); return false">
  <input name="opcion" type="hidden" value="N" />
	<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Elementos de Tinci&oacute;n</strong><h3>
		</td>
	</tr>	
<!--	<tr>
		<td class="StormyWeatherFieldCaptionTD">IdElemento</td>
		<td class="StormyWeatherDataTD"><input name="idElemTin" id="idElemTin" type="text" value="<?php echo $idElemTin; ?>" disabled="disabled" /></td>
	</tr>	-->
	<tr>
		<td class="StormyWeatherFieldCaptionTD">Elemento Tinci&oacute;n </td>
		<td class="StormyWeatherDataTD"><input name="ElemTin" id="ElemTin" type="text" value="<?php echo htmlentities($ElemTin); ?>" size="35"  class="form-control height"/>
                    <input name="idElemTin" id="idElemTin" type="hidden" value="<?php echo $idElemTin; ?>" disabled="disabled" />
                </td>    
	</tr>
		<!--<td class="StormyWeatherDataTD" colspan="2" align="right">
			<input type="submit" name="btnActualizar" value="Actualizar" />
			<input type="button" name="btnNuevo" id="btnNuevo" value="Nuevo" onClick="window.location.replace('MntElementosTincion.php')">

		</td>-->
                
                <tr>
                <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='submit' align="center" class='btn btn-primary'  ><span class='glyphicon glyphicon-repeat'></span> Actualizar</button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntElementosTincion.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                
                </td>
            </tr>  
                
                
	</table>
</form>

