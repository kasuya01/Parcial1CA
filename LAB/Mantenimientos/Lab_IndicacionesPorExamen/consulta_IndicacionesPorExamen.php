
<?php
include_once("clsLab_IndicacionesPorExamen.php");

//consulta los datos por su id
$idindicacion=$_POST['idindicacion'];
$obj = new clsLab_IndicacionesPorExamen;
$consulta=$obj->consultarid_indicacion($idindicacion);
$row = mysql_fetch_array($consulta);

//valores de las consultas
$idexamen=$row['IdExamen'];
$nombreexamen=$row['NombreExamen'];
$idarea=$row['IdArea'];
$nombrearea=$row['NombreArea'];
$indicacion=$row['Indicacion'];
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" action="" >
<table width="52%" border="0" align="center" class="StormyWeatherFormTABLE">
		   <tr>
				<td colspan='2' class='CobaltFieldCaptionTD' align='center'><h3><strong>Mantenimiento de Indicaciones por Ex&aacute;menes de Laboratorio Cl&iacute;nico</strong></h3>
				</td>
		   </tr>
		   <tr>
            <td class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
            <td class="StormyWeatherDataTD">
			<select name="cmbArea" size="1" onChange="LlenarComboExamen(this.value);">			 
			<?php
			include('../Lab_Areas/clsLab_Areas.php');
			$objeareas = new clsLab_Areas;
			$consulta = $objeareas->consultaractivas();
			while($row = mysql_fetch_array($consulta))
			{
				echo "<option value='" . $row['IdArea']. "'>" . htmlentities($row['NombreArea']) . "</option>";
			}						            	
			echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
			?>		  
          </select>		  </td>
          </tr>
          <tr>
            <td class="StormyWeatherFieldCaptionTD">Examen </td>
            <td class="StormyWeatherDataTD"><select name="cmbExamen" size="1">
            <?php
			$consultaex = $obj->ExamenesPorArea($idarea);
			while($row = mysql_fetch_array($consultaex))
			{
				echo "<option value='" . $row['IdExamen']. "'>" . $row['NombreExamen'] . "</option>";
			}						            	
			echo "<option value='" . $idexamen . "' selected='selected'>" .$nombreexamen. "</option>";
			?>	
            </select></td>
          </tr>
		   <tr>
            <td class="StormyWeatherFieldCaptionTD">Indicaci&oacute;n</td>
            <td class="StormyWeatherDataTD">
			<input type="hidden" name="txtidindicacion" id="txtidindicacion" size="50" value="<?php echo $idindicacion; ?>" />
			<textarea name="txtindicacion" cols="60" rows="4" id="txtindicacion"><?php echo htmlentities($indicacion); ?> </textarea>
			</td>
          </tr>
          <tr>
				<td class="StormyWeatherDataTD" colspan="2" align="right">
				<input type="button" name="btnActualizar" value="Actualizar" onclick="Actualizar() ;" /> 
			    <input type="button" name="btnNuevo" value="Nuevo" onclick="Nuevo() ;" />
				<input type="button" name="btnBuscar" value="Buscar" onclick="Buscar() ;" />
			</td>
          </tr>
  </table> 
</form>

