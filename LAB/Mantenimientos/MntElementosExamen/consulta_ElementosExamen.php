<?php session_start();
include_once("clsElementosExamen.php");
include('../Lab_Areas/clsLab_Areas.php');
$objeareas=new clsLab_Areas;
$obj = new clsElementosExamen;

$lugar=$_SESSION['Lugar'];
$usuario=$_SESSION['Correlativo'];
$area=$_SESSION['Idarea'];
//consulta los datos por su id
$idelemento=$_POST['idelemento'];

$consulta=$obj->consultarid($idelemento,$lugar);
$row = mysql_fetch_array($consulta);

//valores de las consultas
$codexamen=$row['IdExamen'];
$subelemento=$row['SubElemento'];
$nombreelemento=$row['Elemento'];

$unidadele=$row['UnidadElem'];
$observacionele=$row['ObservElem'];

$idarea=$row['IdArea'];
$nombreexamen=$row['NombreExamen'];
$nombrearea=$row['NombreArea'];
$Fechaini=$row['FechaIni'];
$Fechafin=$row['FechaFin'];

//echo $Fechaini."".$Fechafin;
//muestra los datos consultados en los campos del formulario
?>

<form name= "frmModificar" >
  <input name="opcion" type="hidden" value="N" />
  <table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
		<tr>
			<td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Elementos de  Examen</h3></strong>
			</td>
		</tr>	
		<tr>
			<td width="17%" class="StormyWeatherFieldCaptionTD">&Aacute;rea
				<input name="idelemento" id="idelemento" type="hidden" value="<?php echo $idelemento ?>" />
			</td>
			<td width="83%" class="StormyWeatherDataTD">
				<select id="cmbArea" name="cmbArea" size="1" onChange="MostrarExamenes(this.value);">
				<option value="0" >--Seleccione un &Aacute;rea--</option>
				<?php
				
				$consulta= $objeareas->consultar();
				while($row = mysql_fetch_array($consulta)){
					echo "<option value='" . $row['IdArea']. "'>" . htmlentities($row['NombreArea']) . "</option>";
				}
				echo "<option value='" . $idarea . "' selected='selected'>" .htmlentities($nombrearea). "</option>";
				?>		  
				</select>		  
			</td>
        </tr>
        <tr>
		    <td width="17%" class="StormyWeatherFieldCaptionTD">Examen </td>
            <td width="83%" class="StormyWeatherDataTD"><select id="cmbExamen" name="cmbExamen" size="1">
				<option value="0">--Seleccione un Examen--</option>
				<?php
				$consultaex = $obj->ExamenesPorArea($idarea);
				while($row = mysql_fetch_array($consultaex))
				{
					echo "<option value='" . $row['IdExamen']. "'>" . $row['NombreExamen'] . "</option>";
				}						            	
					echo "<option value='" . $idexamen . "' selected='selected'>" .htmlentities($nombreexamen). "</option>";
				?>	
				</select>
			</td>
		</tr>
		<tr>
			<td class="StormyWeatherFieldCaptionTD">Elemento</td>
		    <td class="StormyWeatherDataTD"><textarea name="txtelemento" cols="75" rows="2" id="txtelemento"><?php echo htmlentities($nombreelemento); ?></textarea>              
	    </tr>
		<tr>
		    <td class="StormyWeatherFieldCaptionTD">Unidad</td>
		    <td class="StormyWeatherDataTD"><input name="txtunidadele" type="text" id="txtunidadele" value="<?php echo htmlentities($unidadele); ?>" size="15"></td>
	    </tr>
		<tr>
		    <td class="StormyWeatherFieldCaptionTD">Observci&oacute;n o Interpretaci&oacute;n</td>
		    <td class="StormyWeatherDataTD"><textarea name="txtobservacionele" cols="75" rows="4"><?php echo htmlentities($observacionele); ?></textarea>
		</tr>
		<tr>
		    <td width="17%" class="StormyWeatherFieldCaptionTD">SubElementos</td>
		    <td width="83%" class="StormyWeatherDataTD"><select id="select" name="cmbSubElementos" size="1" >
				<option value="0" >--Seleccione--</option>
				<option value="S" >Si</option>
				<option value="N" >No</option>
				<?php
					if ($subelemento =="S")
						{echo "<option value='".$subelemento."' selected='selected'>SI</option>";}
					else
						{echo "<option value='" . $subelemento . "' selected='selected'>NO</option>";}
			  
				?>
				</select>
			</td>
	    </tr>
		<tr>
        	<td colspan="2" class="StormyWeatherDataTD">
				<table width="850" border="0" align="center" class="StormyWeatherFormTABLE">
					<tr>
						<td width="15%" class="StormyWeatherFieldCaptionTD">Fecha Inicio</TD>
						<td width="30%" class="StormyWeatherDataTD">
							<input name="txtFechainicio1" type="text" id="txtFechainicio" value="<?php echo $Fechaini; ?>" size="8" >dd/mm/aaaa</td>
						<td width="13%" class="StormyWeatherFieldCaptionTD">Fecha Final</D>
						<td width="30%" class="StormyWeatherDataTD">
							<input name="txtFechaFin1" type="text" id="txtFechaFin" value="<?php echo $Fechafin; ?>" size="8" >dd/mm/aaaa</td>
					</tr>
				</table>
			</td>		
		</tr>
        <tr>
            <td colspan="2" class="StormyWeatherDataTD" align="right">
				<input type="button" name="Submit" value="Actualizar" onClick="enviarDatos() ;">
				<input type="button" name="Submit2" value="Buscar" Onclick="Buscar() ;">
				<input type="button" name="Submit2" value="Nuevo" onClick="window.location.replace('MntElementosExamen.php')">			
			   	<input type="button" name="btnSubElementos" value="SubElementos" Onclick="MostrarSubElementos() ;">   
			</td>
        </tr>
  </table>
  
</form>

