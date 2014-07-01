<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

$IdSolicitud=$_GET['var1'];
$IdExamen=$_GET['var2'];
$resultado=$_GET['var3'];
$lectura=$_GET['var4'];
$interpretacion=$_GET['var5'];
$observacion=$_GET['var6'];
$responsable=$_GET['var7'];
$sexo=$_GET['var8'];
$idedad=$_GET['var9'];
//echo htmlentities($resultado);
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
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


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>

</script>
</head>

<body>
<?php 
include_once("clsSolicitudesProcesadas.php");
$objdatos = new clsSolicitudesProcesadas;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
$row_estab = mysql_fetch_array($Consulta_Estab);	
$consulta=$objdatos->MostrarResultadoGenerales($IdSolicitud,$IdExamen,$lugar);
$row = mysql_fetch_array($consulta);
$nombre=$row['NombreArea'];
 
 $consulta2=$objdatos->ImprimirResultadoPlantillaA($IdExamen,$lugar,$IdSolicitud);
				$fila = mysql_fetch_array($consulta2);

?>
<table align="center" width="100%">
<tr>
	<td>
	<div  id="divImpresion" >
		<form name="frmimpresion" >
		<table width='90%' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td align='center' colspan='6' class='CobaltButton'>
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
					<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
					<p><strong>&Aacute;rea de <?php echo $nombre; ?> </strong></p>
				</td>
			</tr>
			<tr>
				<td colspan='6' align='center'></td>
			</tr>
			<tr>
				<td style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
				<td style='font:bold' colspan='2'><?php echo htmlentities($row['Nombre']);?></td>
				<td style='font:bold'><strong>Fecha Resultado:</strong></td>
				<td style='font:bold' colspan='2'><?php echo $fila['FechaResultado'];?></td>
			</tr>
			
			<tr>
				<td style='font:bold'><strong>NEC:</strong></td>
				<td style='font:bold'><?php echo$row['IdNumeroExp'];?></td>
				<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
				<td style='font:bold'><strong>Paciente:</strong></td>
				<td style='font:bold' colspan='2'><?php echo htmlentities($row['NombrePaciente'])?></td>
				
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
			<tr>
				<td style='font:bold'><strong>Edad:</strong></td>
				<td style='font:bold' colspan='1'>
					<div id="divsuedad">
						<script language="JavaScript" type="text/javascript">
               					calc_edad();
						</script>
    		   		</div>
				</td>
				<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
				<td style='font:bold'><strong>Sexo:</strong></td>
				<td style='font:bold'><?php echo $row['Sexo']?></td>					
			</tr>	
			<tr>
				<td style='font:bold'><strong>Procedencia:</strong></td>
				<td style='font:bold'><?php echo htmlentities($row['Procedencia'])?></td>
				<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
				<td style='font:bold'><strong>Servicio:</strong></td>
				<td style='font:bold'><?php echo htmlentities($row['Origen'])?></td>
			</tr>
			<?php 
				$consulta_empleado=$objdatos->BuscarEmpleadoValidador($responsable);
				$fila_empleado = mysql_fetch_array($consulta_empleado);
				
			?>
			<tr>
				<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
				<td  colspan='5' style='font:bold'><?php echo htmlentities($fila_empleado['NombreEmpleado'])?></td>
			</tr>
			<tr>
				<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
			</tr>
		</table>
		<table width="90%"  align="center" border="0" class="StormyWeatherFormTABLE">
			<tr class="CobaltButton">
				<td align="center">Prueba Realizada </td>
				<td align="center">Resultado</td>
				<td align="center">Unidades</td>
				<td align="center">Rangos Normales </td>
				<td align="center">Lectura</td>
				<td align="center">Interpretaci&oacute;n</td>
				<td align="center">Observaci&oacute;n</td>
			</tr>	
			<tr>
				<td align="center" style="font:bold"><strong><?php echo htmlentities($fila['NombreExamen'])?></strong></td>
				<td align="center"><?php echo htmlentities($fila['Resultado'])?></td>
				<td align="center"><?php echo htmlentities($fila['Unidades']) ?></td>
				<td align="center" ><?php echo $fila['RangoInicio']."-".$fila['RangoFin']?></td>
				<td align="justify" ><?php echo htmlentities($fila['Lectura'])?></td>
				<td align="justify" ><?php echo htmlentities($fila['Interpretacion'])?></td>
				<td align="justify" ><?php echo htmlentities($fila['Observacion'])?></td>
			</tr> 
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>			
			</tr>
			<tr>
				<td colspan="7" align="center" >
				  <div id="boton">	
						<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
				  </div></td>
					
			</tr>		
		</table>
		</form>
	</div>
</tr>
</table>