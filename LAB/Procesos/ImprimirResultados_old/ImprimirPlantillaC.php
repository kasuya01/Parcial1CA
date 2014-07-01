<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsImprimirResultado.php");
$obj = new clsImprimirResultado;


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
}</script>

<style type="text/css">
<!--
@media print{
#boton{display:none;}
}


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>


</head>

<body>
<?php 
$Catidad=$obj->ObtenerCantidadResultados($idsolicitud,$iddetalle);
$row_cantidad=mysql_fetch_array($Catidad);
//echo $row_cantidad[0];
	if ($row_cantidad[0]==1){
		$tipo=$obj->ObtenerResultado($idsolicitud,$iddetalle);
		$row_tipo=mysql_fetch_array($tipo);
		$Resultado=$row_tipo['Resultado'];

		If($Resultado=='P'){
			$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
			$row_estab = mysql_fetch_array($Consulta_Estab);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
			$row_generales= mysql_fetch_array($datos_generales);
			$consulta_area=$obj->LeerDatos($idexamen);
			$row_area= mysql_fetch_array($consulta_area);
			$consulta_datos=$obj->DatosResultadoPlanCPositivo($idsolicitud,$iddetalle);
			$row_datos= mysql_fetch_array($consulta_datos);
			$consulta=$obj->LeerResultadosAntibioticos($idsolicitud,$iddetalle);
			  
		  	
		  	$ban=0;
			
			$nombre=$row_area['NombreArea'];
			?>
			<table width='90%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
						<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			            <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
						<p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
					<td colspan='2' ><?php echo $row_generales['Nombre']?></td>
					<td colspan='1' style='font:bold'><strong>Fecha Resultado:</strong></td>
			  		<td colspan='2'><?php echo $row_generales['Fecha']?></td>
				</tr>
				
				<tr>
					<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			  		<td colspan='1' ><?php echo $row_generales['IdNumeroExp']?></td>
					<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
					<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
					<td colspan='2' ><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
						<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			  		<td colspan='1'><div id="divsuedad">
		        			<script language="JavaScript" type="text/javascript">
		        				calc_edad();
		        			</script>
		    	  		   	</div>
					</td>
					<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
					<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			  		<td colspan='1'><?php echo $row_generales['Sexo'];?></td>
				</tr>
				<tr>	
					<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
					<td colspan='1' style='font:bold' width='38%'><?php echo htmlentities($row_generales['Procedencia']);?></td>
					<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
					<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
					<td colspan='2' style='font:bold' width='6%'><?php echo htmlentities($row_generales['Origen']);?></td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			  		<td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen']);?></td>
				</tr>				 
				<tr>
					<td colspan='1' style='font:bold'><strong>Resultado:</strong></td>
					<td colspan='4' style='font:bold'><?php echo "Positivo"?></td>
				</tr>
				<tr>
					<td colspan='1' >Organismo:</td>
					<td colspan='4'><?php echo htmlentities($row_datos['Bacteria']); ?></td>		
				</tr>			
				<tr>
					<td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
					<td colspan='4'><?php echo $row_generales['NombreEmpleado'];?></td>
				</tr>
				<tr>
					<td colspan='6'>&nbsp;</td>
				</tr>
				<tr>
					<table width='90%' border='0' align='center' class='StormyWeatherFormTABLE'>
						<tr>
							<td width='46%' class='StormyWeatherFieldCaptionTD'>CULTIVO CON CUENTA DE COLONIAS</td>
							<td colspan='3' width='54%' class='StormyWeatherDataTD'><?php echo htmlentities($row_datos['Cantidad'])?></td>
						</tr>
						<tr class='CobaltButton'>
							<td width='46%' style='font:bold' ><strong>ANTIBIOTICO</strong></td>
							<td width='54%' colspan="2" style='font:bold'  ><strong>INTERPRETACI&Oacute;N</strong>
							</td>
						</tr>	
							<?php	mysql_free_result($datos_generales);
								$pos=0;
									
						while($row = mysql_fetch_array($consulta))//ELEMENTOS)
						{?>
						<tr>
							<td width='50%'><?php echo htmlentities($row['Antibiotico'])?></td>
							<td width='50%' ><?php echo htmlentities($row['Resultado'])?>
							</td>
							<td width='60%'>&nbsp; </td>
						</tr>
						<?php 		
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
				</tr>
			</table>

			
		<?php 
		}else{
		      // echo $idsolicitud."-".$iddetalle."-".$lugar;
			$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
			$row_estab = mysql_fetch_array($Consulta_Estab);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
					//$datos_empleado=$obj->DatosEmpleado($idempleado);
			$consulta_datos=$obj->LeerDatos($idexamen);
			$row_area= mysql_fetch_array($consulta_datos);
			$row_generales= mysql_fetch_array($datos_generales);
		        $Observ= $obj->ObtenerObservacion($idsolicitud,$iddetalle,$lugar);
		        $row_observ= mysql_fetch_array($Observ);
		        $Observacion=$row_observ['Observacion'];
			$nombre=$row_area['NombreArea'];
			?>

			<table align="center" width="100%">
			<tr>
				<td>
					<div  id="divImpresion" >
					<table width='90%' border='0' align='center' class='StormyWeatherFormTABLE'>
						<tr>
							<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
								<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
							 	<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
								<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
							</td>
						</tr>
						<tr>
							<td colspan="1" style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
							<td colspan="3"><?php echo $row_generales['Nombre']?></td>
							<td colspan="1" style='font:bold'><strong>Fecha Resultado:</strong></td>
							<td colspan="1"><?php echo $row_generales['Fecha']?></td>
								<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
						</tr>
						
						<tr>
							<td colspan="1" style='font:bold'><strong>NEC</strong></td>
							<td colspan="1"><?php echo $row_generales['IdNumeroExp']?></td>
							<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
							<td colspan="1" style='font:bold'><strong>Paciente:</strong></td>
							<td colspan="2"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
						</tr>
						<tr>
							<td colspan="1" style='font:bold'><strong>Edad:</strong></td>
							<td colspan="1"><div id="divsuedad">
		        					<script language="JavaScript" type="text/javascript">
		        						calc_edad();
		        					</script>
		    	  		    			</div>
							</td>
							<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
							<td colspan="1" style='font:bold'><strong>Sexo:</strong></td>
							<td colspan="1"><?php echo $row_generales['Sexo']?></td>
						</tr>
						<tr>	
							<td style='font:bold'><strong>Procedencia:</strong></td>
							<td style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
							<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
							<td style='font:bold'><strong>Origen:</strong></td>
							<td style='font:bold'><?php echo htmlentities($row_generales['Origen'])?></td>
						</tr>				
						<tr>
							<td colspan="1" style='font:bold'><strong>Examen Realizado:</strong></td>
							<td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
						</tr>				 
						<tr>
							<td colspan="1" style='font:bold'><strong>Validado Por:</strong></td>
							<td colspan='4'><?php echo $row_generales['NombreEmpleado']; ?></td>
						</tr>
						<tr>
							<td colspan="1" style='font:bold'><strong>Resultado:</strong></td>
								<?php 	mysql_free_result($consulta_datos);
									mysql_free_result($datos_generales);	 
								
							switch($Resultado)
							{
								case "N";?>
									<td colspan='4'>NEGATIVO</td>
							  <?php break;
								case "O";?>
									<td colspan='4'>--</td>
							  <?php break;
							}?>
						</tr>
						<tr>
							<td colspan="1" style='font:bold'><strong>Observaci&oacute;n:</strong></td>
							<td colspan='4'><?php echo htmlentities($Observacion)?></td>
						</tr>
						<tr>
							<td colspan='5' align='center'></td>
						</tr>
						<tr>
							<td colspan='5'>&nbsp;</td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
		        <tr class="5">
				<td>		
				 	<div id="boton" align="center">	
						<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
					</div>
				</td>
			</tr>
			</table>

		<?php }
	}
	else{//Mas de un resultado 
		// echo $row_cantidad[0];
		 $tipo=$obj->ObtenerResultadoxId($idsolicitud,$iddetalle);
		 while( $row_tipo=mysql_fetch_array($tipo)){
			If($row_tipo[1]=='P'){
			//echo $row_tipo[0];
				$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
				$row_estab = mysql_fetch_array($Consulta_Estab);
				$datos_generales=$obj->MostrarDatosGeneralesxId($idsolicitud,$iddetalle,$lugar,$row_tipo[0]);
				$row_generales= mysql_fetch_array($datos_generales);
				$consulta_area=$obj->LeerDatos($idexamen);
				$row_area= mysql_fetch_array($consulta_area);
				echo $row_tipo[0]."***".$idsolicitud."***".$iddetalle;
				$consulta_datos=$obj->DatosResultadoPlanCPositivoxId($idsolicitud,$iddetalle,$row_tipo[0]);
				$row_datos= mysql_fetch_array($consulta_datos);
				//echo $row_datos[0];
				$consulta=$obj->LeerResultadosAntibioticosxId($idsolicitud,$iddetalle,$row_tipo[0]);
				
				$posele=0;
		  	$ban=0;
			
			$nombre=$row_area['NombreArea'];
			?>
			<table width='90%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
						<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			                	<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
						<p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
					<td colspan='3' ><?php echo $row_generales['Nombre']?></td>
					<td colspan='1' style='font:bold'><strong>Fecha:</strong></td>
			  		<td colspan='1'><?php echo $row_generales['Fecha']?></td>
				</tr>
				
				<tr>
					<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			  		<td colspan='1' ><?php echo $row_generales['IdNumeroExp']?></td>
					<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
					<td colspan='3' ><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
						<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			  		<td colspan='1'><div id="divsuedad">
		        			<script language="JavaScript" type="text/javascript">
		        				calc_edad();
		        			</script>
		    	  		   	</div>
					</td>
					<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			  		<td colspan='1'><?php echo $row_generales['Sexo'];?></td>
				</tr>
				<tr>	
					<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
					<td colspan='1' style='font:bold' width='38%'><?php echo htmlentities($row_generales['Procedencia']);?></td>
					<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
					<td colspan='2' style='font:bold' width='6%'><?php echo htmlentities($row_generales['Origen']);?></td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			  		<td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen']);?></td>
				</tr>				 
				<tr>
					<td colspan='1' style='font:bold'><strong>Resultado:</strong></td>
					<td colspan='4' style='font:bold'><?php echo "Positivo"?></td>
				</tr>
						
				<tr>
					<td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
					<td colspan='4'><?php echo $row_generales['NombreEmpleado'];?></td>
				</tr>
				<tr>
					<td colspan='1' >Organismo:</td>
					<td colspan='4'><?php echo htmlentities($row_generales['Bacteria']); ?></td>		
				</tr>	
				<tr>
					<td colspan='6'>&nbsp;</td>
				</tr>
				<tr>
					<table width='90%' border='0' align='center' class='StormyWeatherFormTABLE'>
						<tr>
							<td width='46%' class='StormyWeatherFieldCaptionTD'>CULTIVO CON CUENTA DE COLONIAS</td>
							<td colspan='3' width='54%' class='StormyWeatherDataTD'><?php echo htmlentities($row_generales['Cantidad'])?></td>
						</tr>
						<tr class='CobaltButton'>
							<td width='46%' style='font:bold' ><strong>ANTIBIOTICO</strong></td>
							<td width='54%' colspan="2" style='font:bold'  ><strong>INTERPRETACI&Oacute;N</strong>
							</td>
						</tr>	
							<?php	mysql_free_result($datos_generales);
								$pos=0;
									
						while($row = mysql_fetch_array($consulta))//ELEMENTOS)
						{?>
						<tr>
							<td width='50%'><?php echo htmlentities($row['Antibiotico'])?></td>
							<td width='50%' ><?php echo htmlentities($row['Resultado'])?>
							</td>
							<td width='60%'>&nbsp; </td>
						</tr>
						<?php 		
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
				</tr>
			</table>
			
			<?php 
			}?>
			<br style="page-break-after: always;" />
		<?php	
		 }?>
	 
	<?php 	
	}	
?>
