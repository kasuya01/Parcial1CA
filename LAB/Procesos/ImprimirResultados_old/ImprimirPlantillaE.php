<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//Datos Recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$idexamen=$_GET['var6'];

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_ImprimirResultado.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript">
function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);

  var suEdades=calcular_edad(fecnac2);

  document.getElementById("divsuedad").innerHTML=suEdades;
}

</script>
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}
@media print{
#boton1{display:none;}
}

.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>
</head>

<body>
<?php 
include_once("clsImprimirResultado.php");
//creando los objetos de las clases
$obj = new clsImprimirResultado;

	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
	
	if ($idexamen=="COA006"){   
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
		$row_generales= mysql_fetch_array($datos_generales);
		$row_area = mysql_fetch_array($consulta_datos);
	?>
	<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                <p><strong><?php  echo                        $row_estab['Nombre'] ?></strong></p>
				<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
			</td>
		</tr>
		<tr>	
			<td colspan='1' style='font:bold'>Establecimiento Solicitante:</td>
			<td colspan='3'><?php echo htmlentities($row_generales['Nombre']) ?></td>
			<td colspan='1' style='font:bold'>Fecha Resultado:</td>
			<td colspan='1'><?php echo $row_generales['Fecha']?>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
			</td>
		</tr>
		
		<tr>
			<td colspan='1' style='font:bold'>NEC</td>
			<td colspan='1'><?php echo $row_generales['IdNumeroExp']?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='2'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='1'>
				<div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   	</div>
			</td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='1'><?php echo $row_generales['Sexo']?></td>
		</tr>
		<tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='1' style='font:bold'><?php echo htmlentities($row_generales['Origen'])?></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
			<td colspan='5'><?php echo htmlentities($row_generales['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			<td colspan='5' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>				 
		<tr>
			<td colspan='1' style='font:bold'><strong>Observacion:<strong></td>
			<td colspan='5'><?php echo htmlentities($row_generales['Observacion'])?></td>
		</tr>
			<?php mysql_free_result($consulta_datos);
			      mysql_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idsolicitud,$iddetalle);
			?>		
		<tr>
			<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' >
				<tr class='CobaltButton'>
					<td > Prueba </td>
					<td > Resultado </td>
					<td > Unidades </td>
					<td colspan='2' > Control Diario </td>
				</tr>
					<?php	$pos=0;
					while($row = mysql_fetch_array($consulta))//ELEMENTOS
					{?>
				<tr>
					<td><?PHP echo htmlentities($row['NombreProcedimiento'])?></td>
					<td align='center'>
						<input name='oidprueba[<?php $pos ?>]' type='hidden' id='oidprueba[<?php $pos ?>]' value='<?php $row['IdProcedimientoporexamen'] ?>'><?php echo htmlentities($row['Resultado'])?></td>
					<td><?php echo $row['Unidades']?></td>
					<td align='center'><?php echo htmlentities($row['Observacion'])?></td>	<td><?php echo htmlentities($row['Unidades'])?></td>
				</tr>
					<?php $pos=$pos + 1;		 
					}
						mysql_free_result($consulta);?>
			</table>
		</tr>
		<tr>
			<td colspan="7" align="center" >
				<div id="boton1">	
					<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
					<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
				</div>
			</td>
		</tr>		
	</table>
	
	<?php 	
	}else{		
	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
      
	$row_generales= mysql_fetch_array($datos_generales);
	$row_area = mysql_fetch_array($consulta_datos);
	
	
	?>
	<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				 <p><strong><?php  echo                        $row_estab['Nombre'] ?></strong></p>
				<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
			</td>
		</tr>
		<tr>
			<td colspan='1'><strong>Establecimiednto Solicitante:</strong></td>
			<td colspan='2'><?php echo htmlentities($row_generales['Nombre']) ?></td>
			<td colspan='1'><strong>Fecha Resultado:</strong></td>
			<td colspan='2'><?php echo $row_generales['Fecha']?>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
			</td>
		</tr>
		<tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'><?php echo $row_generales['Procedencia']?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' style='font:bold'><?php echo $row_generales['Origen']?></td>
		</tr>
		<tr>
			<td colspan='1'><strong>NEC</strong></td>
			<td colspan='1'><?php echo $row_generales['IdNumeroExp'] ?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1'><strong>Paciente:</strong></td>
			<td colspan='2'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
			
		</tr>
		<tr>
			<td colspan='1'><strong>Edad:</strong></td>
			<td colspan='1'>
				<div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   	</div>
			</td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1'><strong>Sexo:</strong></td>
			<td colspan='1'><?php echo $row_generales['Sexo']?></td>
		</tr>
		
		<tr>
			<td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			<td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>				 
		<tr>
			<td colspan='1'><strong>Validado Por:</strong></td>
			<td colspan='4'><?php echo htmlentities($row_generales['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='1'><strong>Observacion:</strong></td>
			<td colspan='4'><?php echo htmlentities($row_generales['Observacion'])?></td>
		</tr>
		<tr> 
			<?php mysql_free_result($consulta_datos);
			      mysql_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idsolicitud,$iddetalle);
				
	        ?>
			<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr class='CobaltButton'>
					<td > Prueba </td>
					<td > Resultado </td>
					<td > Unidades </td>
					<td > Rango </td>
				</tr>
					<?php $pos=0;
					while($row = mysql_fetch_array($consulta))//ELEMENTOS
					{  ?>
				<tr>
					<td><?php echo htmlentities($row['NombreProcedimiento'])?></td>
					<td><input name='oidprueba[<?php $pos?>]' type='hidden' id='oidprueba[<?php $pos?>]' value='<?php $row['IdProcedimientoporexamen']?>'><?php echo htmlentities($row['Resultado'])?></td>
					<td><?php echo htmlentities($row['Unidades'])?></td>
					<td align='center'><?php echo $row['RangoInicio']." - ".$row['rangofin']?></td>				   </tr>
					<?php  $pos=$pos + 1;		 
					}
					mysql_free_result($consulta);?>
			<tr>
					<td colspan="7" align="center" >
                                            <div id="boton">	
                                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
                                            </div>
					</td>
				</tr>		
			</table>
		</form>
		<?php }?>

