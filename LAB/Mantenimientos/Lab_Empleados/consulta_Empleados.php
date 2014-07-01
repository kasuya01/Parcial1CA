<?php session_start();
include_once("clsLab_Empleados.php");
$lugar=$_SESSION['Lugar'];
$corr=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$IdEmpleado=$_POST['idempleado'];
//echo $idempleado;
$obj = new clsLab_Empleados;
$consulta=$obj->consultarid($IdEmpleado,$lugar);
//echo $IdEmpleado;
$row = mysql_fetch_array($consulta);

//valores de las consultas
$codempleado=$row['IdEmpleado'];
$idarea=$row['IdArea'];
$nombreempleado=$row['NombreEmpleado'];
$nombrearea=$row['NombreArea'];
$idcargoempleado=$row['IdCargoEmpleado'];
$cargo=$row['Cargo'];
$login=$row['login'];
//muestra los datos consultados en los campos del formulario

?>

<form name= "frmModificar" >
    <input name="opcion" type="hidden" value="N" />
<table width="57%" border="0" align="center" class="StormyWeatherFormTABLE">
<tr>
    <td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Empleados de Laboratorio Cl&iacute;nico</strong></h3>
    </td>
</tr>         
<tr>
    <td class="StormyWeatherFieldCaptionTD" width="40%">C&oacute;digo del Empleado </td>
    <td class="StormyWeatherDataTD" width="60%"><input type="text" id="txtidempleado" name="txtidempleado" value="<?php echo $codempleado; ?>" disabled="disabled" /></td>
</tr>
<tr>
    <td class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
    <td class="StormyWeatherDataTD">
	<select id="cmbArea" name="cmbArea" size="1" >			 
	<?php
		include('../Lab_Areas/clsLab_Areas.php');
                $objeareas = new clsLab_Areas;
		$consulta= $objeareas->consultaractivas($lugar);
                while($row = mysql_fetch_array($consulta))
		{
			echo "<option value='" . $row['IdArea']. "'>" . htmlentities($row['NombreArea']) . "</option>";
		}						            	
			echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
			?>		  
        </select>
    </td>
</tr>
<tr>
     <td class="StormyWeatherFieldCaptionTD">Nombre del Empleado</td>
     <td class="StormyWeatherDataTD"><input type="text" id="txtnombreempleado" name="txtnombreempleado" size="40" value="<?php echo htmlentities($nombreempleado); ?>" /></td>
</tr>
<tr>
	<td class="StormyWeatherFieldCaptionTD">Cargo</td>
	<td class="StormyWeatherDataTD">
		<select id="cmbCargo" name="cmbCargo" size="1">
             	<option value="0">--Seleccione Cargo--</option>";
             <?php
		 require_once('clsLab_Empleados.php');
		 $objeareas=new clsLab_Empleados;
		 $consulta= $objeareas->LeerCargos();
		 while($row = mysql_fetch_array($consulta)){
		   	echo "<option value='" . $row['IdCargoEmpleado']. "'>" . htmlentities($row['Cargo']) . "</option>";
			}
			echo "<option value='" . $idcargoempleado . "' selected='selected'>" .$cargo. "</option>";
		?>
            	</select>
	</td>
</tr>
<tr>
	<td class="StormyWeatherFieldCaptionTD">Usuario</td>
	<td class="StormyWeatherDataTD"><input type="text" id="txtlogin" name="txtlogin" size="40" value="<?php echo $login;?>" /></td>
</tr>
<tr>
        <td class="StormyWeatherDataTD" colspan="2" align="right" >
             <input type="button" name="btnActualizar" value="Actualizar" onclick="Modificar();" />
             <input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntEmpleados.php')" />
	</td>
</tr>
</table> 
</form>
