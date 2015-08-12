<?php session_start();
include_once("clsLab_Examenes.php");
$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idexamen=$_POST['idexamen'];
$obj = new clsLab_Examenes;
$consulta=$obj->consultarid($idexamen,$lugar);
$row = mysql_fetch_array($consulta);

//valores de las consultas
$codexamen=$row['IdExamen'];
$idarea=$row['IdArea'];
$nombreexamen=$row['NombreExamen'];
$idestandar=$row['IdEstandar'];
$nombrearea=$row['NombreArea'];
$nombreestandar=$row['Descripcion'];
$plantilla=$row['IdPlantilla'];
$nomplantilla=$row['Plantilla'];
$observacion=$row['Observacion'];
$ubicacion=$row['Ubicacion'];
$IdPrograma=$row['IdPrograma'];
$IdEstandarResp=$row['IdEstandarRep'];
$Programa=$row['NombrePrograma'];
$IdFormulario=$row['IdFormulario'];
$NombreForm=$row['NombreFormulario'];
     
//echo $IdPrograma."***".$IdFormulario."***";
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" >
<input name="opcion" type="hidden" value="N" />
<table width="65%" border="0" align="center" class="StormyWeatherFormTABLE">
    <tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Ex&aacute;menes de Laboratorio Cl&iacute;nico</strong></h3>
		</td>
	</tr>      
	<tr>
       	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo del Examen</td>
       	<td class="StormyWeatherDataTD"><input type="text" name="txtidexamen" value="<?php echo $idexamen; ?>" disabled="disabled" /></td>
    </tr>
	<tr>
       	<td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
       	<td class="StormyWeatherDataTD">
			<select name="cmbArea" size="1" disabled="disabled">			 
			<?php
			include('../Lab_Areas/clsLab_Areas.php');
			$objeareas = new clsLab_Areas;
			$consulta= $objeareas->consultaractivas($lugar);
			while($row = mysql_fetch_array($consulta))
			{
				echo "<option value='" . $row['IdArea']. "'>" . $row['NombreArea'] . "</option>";
			}						            	
			echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
			?>		  
          	</select>
		</td>
    </tr>
    <tr>
       	<td class="StormyWeatherFieldCaptionTD">Nombre del Examen </td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtnombreexamen" size="50" value="<?php echo htmlentities($nombreexamen); ?>"/>        </td>
   	</tr>
	<tr>
	    <td class="StormyWeatherFieldCaptionTD">Plantilla</td>
	    <td class="StormyWeatherDataTD">
			<select id="cmbPlantilla" name="cmbPlantilla" size="1">
            		<option value="0">--Seleccione una Plantilla--</option>
            		<?php
				include_once('clsLab_Examenes.php');
				$obje=new clsLab_Examenes;
				$consulta= $obje->LeerPlantilla();
				while($row = mysql_fetch_array($consulta)){
			  		echo "<option value='" . $row[0]. "'>" .htmlentities($row[1]). "</option>";
				}
				echo "<option value='" . $plantilla . "' selected='selected'>" .$nomplantilla. "</option>";
			?>
                	</select>
		</td>
	</tr>
	<tr>
        <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del Est&aacute;ndar</td>
        <td class="StormyWeatherDataTD"><select name="cmbEstandar" size="1">
			<option value="0">--Seleccione un Estandar--</option>
				<?php
				include('../Lab_CodigosEstandar/clsLab_CodigosEstandar.php');
				$obje=new clsLab_CodigosEstandar;
				$consulta= $obje->consultar();
				while($row = mysql_fetch_array($consulta)){
					echo "<option value='" . $row['0']. "'>" . $row['0'].'- '.htmlentities($row['1']). "</option>";
				}
		echo "<option value='" . $idestandar . "' selected='selected'>" .$idestandar .'- '.htmlentities($nombreestandar). "</option>";			
			?>		 
          		</select>
		</td>
    </tr>
    <tr>
			<td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n </td>
			<td class="StormyWeatherDataTD"><textarea name="txtobservacion" cols="60" id="txtobservacion"><?php echo htmlentities($observacion); ?></textarea> 
			</td>
	</tr>
	<tr>
        	<td class="StormyWeatherFieldCaptionTD"><strong>Solicitado en Consulta Externa</strong> </td>
        	<td class="StormyWeatherDataTD">
				<select id="cmbUbicacion" name="cmbUbicacion" size="1" >
				<?php
				if ($ubicacion==0){
					echo "<option value='".$ubicacion."' selected='selected'> SI </option>";
					echo "<option value='1' >NO</option>";
				}
				if ($ubicacion==1){
					echo "<option value='".$ubicacion."' selected='selected'> NO </option>";
					echo "<option value='0' >SI</option>";
				}
				?>
				</select>
			</td>
	</tr>
	<tr>
			<td class="StormyWeatherFieldCaptionTD" width="40%"><strong>Programa Nacional de Salud</strong> </td>
			<td class="StormyWeatherDataTD">
				<select id="cmbPrograma" name="cmbPrograma" size="1" onChange="LlenarComboForm(this.value);">
					<option value="0">--Ninguno--</option>
						<?php
							$obje1=new clsLab_Examenes;
							$consulta= $obje1->consultar_programas();
							while($row = mysql_fetch_array($consulta)){
							  echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
								}
							
							  echo "<option value='" . $IdPrograma . "' selected='selected'>".htmlentities($Programa)."</option>";		
						?>	
				</select>  
			</td>
	</tr>
	<tr>
			<td width="40%" class="StormyWeatherFieldCaptionTD">Formulario para Examen</td>
        	<td class="StormyWeatherDataTD">
				<div id="divForm">
					<select name="cmbConForm" id="cmbConForm" size="1"> 
						<?php 
							echo "<option value='".$IdFormulario."' selected='selected'>".htmlentities($NombreForm)."</option>";
						?>
					</select>
				</div>
	</tr>
	<tr>
			<td class="StormyWeatherFieldCaptionTD" >C&oacute;digo en tabulador</td>
			<td class="StormyWeatherDataTD">
					<select id="cmbEstandarRep" name="cmbEstandarRep" size="1">
						<option value="0">--Seleccione un Est&aacute;ndar--</option>
							<?php
									$obje=new clsLab_CodigosEstandar;
									$consulta= $obje->consultar();
							while($row = mysql_fetch_array($consulta)){
							  echo "<option value='" . $row['0']. "'>" .$row['1'] . "</option>";
								}
								     $obje1=new clsLab_Examenes;
									 $consulta2=$obje1->Obtener_NombreEstandar($IdEstandarResp);
									 $rownom= mysql_fetch_array($consulta2);
									 $NombreEstandar=$rownom[0];
							  echo "<option value='".$IdEstandarResp."' selected='selected'>".$IdEstandarResp.'-'.htmlentities($NombreEstandar). "</option>";		
								//mysql_free_result($row);		
							?>		 
					</select>   
			</td>
	</tr>
	
    <tr>
       		<td colspan="2" align="right" class="StormyWeatherDataTD">
           	  	<input type="button" name="btnActualizar" value="Actualizar" onclick="Modificar();" />
				           		<input type="button" name="btnNuevo" value="Nuevo" onClick="window.location.replace('MntExamenes.php')" />
		</td>
    	</tr>
</table> 
</form>

