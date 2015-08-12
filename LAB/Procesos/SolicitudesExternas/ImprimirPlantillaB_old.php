<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsConsultarElementos.php");
$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
//$resultado=$_GET['var3'];
$idempleado=$_GET['var3'];
$procedencia=$_GET['var4'];
$origen=$_GET['var5'];
$observacion=$_GET['var6'];
$valores_subelementos=$_GET['var7'];
$codigos_subelementos=$_GET['var8'];
$valores_elementos=$_GET['var9'];
$codigos_elementos=$_GET['var10'];
$controles=$_GET['var11'];
$controles_ele=$_GET['var12'];
$nombrearea=$_GET['var13'];
$establecimietno=$_GET['var14'];
$responsable=$_GET['var15'];

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript">
function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
//alert (fecnac2);
  var suEdades=calcular_edad(fecnac2);
 // alert(suEdades);
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


</script>
</head>

<body>
<?php 
	
	$obj = new clsConsultarElementos;
	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
	
	$FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
	$row_fecha=mysql_fetch_array($FechaRes);
	
	$ConEstandar=$obj->Obtener_Estandar($idexamen);
	$CodEstandar= mysql_fetch_array($ConEstandar);
	$IdEstandar=$CodEstandar[0];
	
	switch ($IdEstandar){

	case "H50":
		$cadena=$valores_subelementos;
		$vector=EXPLODE("/",$cadena);
		$vector_elementos=EXPLODE("/",$valores_elementos);
		$vector_controles=EXPLODE("/",$controles);
		$vector_controles_ele=EXPLODE("/",$controles_ele);
		//$obj = new clsConsultarElementos;
		$consulta=$obj->LeerElementosExamen($idexamen,$lugar);
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
		//$datos_empleado=$obj->DatosEmpleado($idempleado);
		$row_generales= mysql_fetch_array($datos_generales);
		$row_area = mysql_fetch_array($consulta_datos);
		//$row_empleado = mysql_fetch_array($datos_empleado);?>
		<table width='90%' align='center' class ='StormyWeatherFormTABLE' >
		<tr>
			<td align='center' colspan='6' class='CobaltButton'>
				 <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
				 <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
				 <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
			</td>
		</tr>
        <tr>
			<td colspan='1' style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2'><?php echo $_GET['var14']?></td>
			<td colspan='1' style='font:bold'><strong>Fecha Resultado:</strong></td>
			<td colspan='2'><?php echo $row_fecha['FechaResultado']?></td>
		</tr>
		<tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'><?php echo $row_generales['Procedencia']?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' style='font:bold'><?php echo $row_generales['Origen']?></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>NEC</strong></td>
			<td colspan='1'><?php echo $row_generales['IdNumeroExp']?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Paciente</strong></td>
			<td colspan='2'><?php echo $row_generales['NombrePaciente']?></td>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
		</tr>
		<tr>
			<td style='font:bold'><strong>Edad:</strong></td>
			<td>
				<div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
               			calc_edad();
          			</script>
    		   	</div>
			</td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td style='font:bold'><strong>Sexo:</strong></td>
			<td><?php echo $row_generales['Sexo']?></td>
		</tr>
		<tr>
			 <td style='font:bold'><strong>Examen Realizado:</strong></td>
			 <td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>				 
		<tr>
			<td style='font:bold'><strong>Validado Por:</strong></td>
			<td colspan='4'><?php echo htmlentities($responsable)?></td>
		</tr>
		<tr>
			<td style='font:bold'><strong>Observaci&oacute;n:</strong></td>
			<td colspan='4'><?php echo htmlentities($observacion)?></td>
		</tr>
		</table>
				 
		<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<?php mysql_free_result($consulta_datos);
			mysql_free_result($datos_generales);?>
			<tr class='CobaltButton'>
				<td width='35%'></td>
				<td width='25%'>Resultado</td>
				<td width='20%'>Unidades</td>
				<td width='60%' colspan='2'>Control Normal </td>
			</tr>
				<?php $pos=0;
			 	$posele=0;
			while($row = mysql_fetch_array($consulta))//ELEMENTOS
			{  
				if($row['SubElemento']=='S')
				{ ?>
			<tr class='StormyWeatherFieldCaptionTD'>
				<td colspan='5' style='font:bold'><?php echo htmlentities($row['Elemento'])?></td>
			</tr>
				<?php	$consulta2=$obj->LeerSubElementosExamen($row['IdElemento'],$lugar);
		 
				while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
				{?>
			<tr>
				<td width='35%'><?php echo htmlentities($rowsub['SubElemento'])?></td>
				<td width='25%'><?php echo htmlentities($vector[$pos])?></td>
				<td width='20%'><?php echo htmlentities($rowsub['Unidad'])?></td>
				<td width='40%'><?php echo htmlentities($vector_controles[$pos])." ".htmlentities($rowsub['Unidad'])?> </td>
			</tr>
				<?php	$pos=$pos + 1;
				}?>
			<tr>
				<td colspan='4'><?php echo htmlentities($row['ObservElem'])?></td>
			</tr>
			<tr>
				<td colspan='5'>&nbsp;</td>
			</tr>
			<?php	}else{?>
			<tr>
				<td width='40%' style='font:bold' class='StormyWeatherFieldCaptionTD'><strong><?php echo htmlentities($row['Elemento'])?></strong>
				</td>
				<td  width='25%'><?php echo htmlentities($vector_elementos[$posele])?></td>
				<td width='10%'><?php echo htmlentities($row['UnidadElem'])?></td>
				<td><?php echo htmlentities($vector_controles_ele[$posele])."  ".htmlentities($row['UnidadElem'])?></td>
			</tr>
				<?php	$posele=$posele+1;?>
			<tr>
				<td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
			</tr>
			<tr>
				<td colspan='6'>&nbsp;</td>
			</tr>
			<?php	}	
		
			}// del while
			mysql_free_result($consulta);?>
			<tr>
				<td colspan="7" align="center">
					<div id="boton1"><input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" /></div>
				</td>
			</tr>
		</table>
<?php break;
  
	default:
	
	  $cadena=$valores_subelementos;
	  $vector=EXPLODE("/",$cadena);
	  $vector_elementos=EXPLODE("/",$valores_elementos);
	 // $obj = new clsConsultarElementos;
	  $consulta=$obj->LeerElementosExamen($idexamen,$lugar);
	  $consulta_datos=$obj->LeerDatos($idexamen);
	  $datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
			//$datos_empleado=$obj->DatosEmpleado($idempleado);
			$row_generales= mysql_fetch_array($datos_generales);
			$row_area = mysql_fetch_array($consulta_datos);
			//$row_empleado = mysql_fetch_array($datos_empleado);?>
	  	<table width='89%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				 <td align='center' colspan='6' class='CobaltButton'>
				 <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				 <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
				 <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
			</tr>
                        <tr>
				<td colspan='1' style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
				<td colspan='2'><?php echo $_GET['var14']?></td>
				<td colspan='1' style='font:bold'><strong>Fecha Resultado:</strong></td>
				<td colspan='2'><?php echo $row_fecha['FechaResultado']?></td>
		        </tr>
			<tr>	
				<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
				<td colspan='2' style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
				<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
				<td colspan='2' style='font:bold' colspan='2'><?php echo htmlentities($row_generales['Origen'])?></td>
			</tr>
			<tr>
			    <td colspan='1' style='font:bold'><strong>NEC</strong></td>
				<td colspan='1'><?php echo $row_generales['IdNumeroExp']?></td>
				<td colspan='1' align='center' >&nbsp;&nbsp;&nbsp;</td>
				<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			    <td colspan='2'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
				    <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
				<td colspan='2'>
					<div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   		</div>
				</td>
				<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
				<td colspan='1'><?php echo $row_generales['Sexo']?></td>
			</tr>
			
			<tr>
				 <td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
				 <td colspan='5' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
			</tr>				 
			<tr>
				 <td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
				 <td colspan='3'><?php echo htmlentities($responsable)?></td>
			</tr>
			<tr>
				 <td colspan='1' style='font:bold'><strong>Observaci&oacute;n:</strong></td>
				 <td colspan='4'><?php echo htmlentities($observacion)?></td>
			</tr>
		</table>
		<table width='89%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr  class='CobaltButton'>
				<td width='25%'></td>
				<td width='25%'>Resultado</td>
				<td width='25%'>Unidades</td>
			</tr>
				<?php $pos=0;
				  $posele=0;
	while($row = mysql_fetch_array($consulta))//ELEMENTOS
	{  
		if($row['SubElemento']=="S")
			{   ?>
			<tr class='StormyWeatherFieldCaptionTD'>
				<td colspan='3' style='font:bold'><strong><?php echo htmlentities($row['Elemento'])?></strong></td>
			 </tr>
				<?php	$consulta2=$obj->LeerSubElementosExamen($row['IdElemento'],$lugar);
		 
					while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
					{?>
						<tr >
						  <td width='35%'><?php echo htmlentities($rowsub['SubElemento'])?></td>
						  <td width='25%'><?php echo htmlentities($vector[$pos])?>
						  </td>
						  <td width='15%'><?php echo htmlentities($rowsub['Unidad'])?></td>
						 </tr>
					<?php	
						$pos=$pos + 1;
					}
					?>
					<tr>
					<td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
					</tr>
			<?php }
				else
				{?>
					<tr >
				  <td style='font:bold'  class='StormyWeatherFieldCaptionTD'><?php echo htmlentities($row['Elemento'])?></td>
				  <td><?php echo htmlentities($vector_elementos[$posele])?></td>
				  <td width='25%'><?php htmlentities($row['UnidadElem'])?></td>
				</tr>
				<?php 
					$posele=$posele+1;
					?>
				<tr>
					<td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
				</tr>
			<?php	}	
		
			}
			
			mysql_free_result($consulta);
			mysql_free_result($consulta_datos);
			mysql_free_result($datos_generales);
			?>
			<tr>
				<td colspan="7" align="center" >
					<div id="boton"><input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
					</div>
				</td>
				
			</tr>
	<?php break;
}?>