<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsPlantillaD.php");
$obj = new clsPlantillaD;

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
$idresultado=$_GET['var3'];
$idempleado=$_GET['var4'];
$establecimiento=$_GET['var5'];


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


.Estilo5 {font-family: Helvetica; font-size: 10pt}
.Estilo6 {font-family: Helvetica; font-size: 9pt}
.Estilo7 {font-family: Helvetica; font-size: 11pt}
-->
</style>

</script>
</head>

<body>
<?php 

//$objdatos = new clsPlantillaD;
$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
$row_estab = mysql_fetch_array($Consulta_Estab);

	$consulta=$obj->MostrarElementosAgregados($idresultado);
    	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud);
	$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= mysql_fetch_array($datos_generales);
	$row_area = mysql_fetch_array($consulta_datos);
	$row_empleado = mysql_fetch_array($datos_empleado);
	//echo $IdSolicitud."-".$IdExamen."-".$Idresultado."-".$IdArea;
?>
	
	<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
				<p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
			</td>
		</tr>
		<tr>
			<td style='font:bold'><strong>Establecimiento:</strong></td>
			<td colspan='3'><?php echo $establecimiento?></td>
			<td style='font:bold'><strong>Fecha</strong></td>
			<td><?php echo $row_generales['Fecha']?></td><input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
		</tr>
		<tr>	
			<td style='font:bold'><strong>Procedencia:</strong></td>
			<td style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
			<td style='font:bold'><strong>Servicio:</strong></td>
			<td style='font:bold' colspan='2'><?php echo $row_generales['Origen']?></td>
		</tr>	
		<tr>
			<td style='font:bold'><strong>NEC</strong></td>
			<td><?php echo $row_generales['IdNumeroExp']?></td>
			<td style='font:bold'><strong>Paciente:</strong></td>
			<td><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
		</tr>
		<tr>
			
			<td style='font:bold'><strong>Edad:</strong></td>
			<td><div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
               					calc_edad();
          			</script>
    		   	</div></td>
			<td style='font:bold'><strong>Sexo:</strong></td>
			<td><?php echo $row_generales['Sexo']?></td>
		</tr>
		
		<tr>
			<td style='font:bold'><strong>Examen Realizado:</strong></td>
			<td colspan='5'style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>				 
		<tr>
			<td style='font:bold'><strong>Validado Por:</strong></td>
			<td colspan='5'><?php echo htmlentities($row_empleado['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='6'>&nbsp;</td>
		</tr>
		<tr>
			<td></td>
			<td colspan='4' aling='center' >
				<table width='100%' border='1' aling='center' >
					<tr>
						<td style='font:bold' aling='center' class='StormyWeatherDataTD'><strong>Elemento de Tinci&oacute;n</strong>
						</td>
						<td style='font:bold' aling='center' class='StormyWeatherDataTD'><strong>Cantidad</strong>
						</td>
					</tr>
				<?php while($row = mysql_fetch_array($consulta)){
				?>
					<tr>
						<td><?php echo $row[0]?></td>
						<td><?php echo $row[1]?></td>
					</tr>
				<?php	}
						mysql_free_result($consulta);
						mysql_free_result($consulta_datos);
						mysql_free_result($datos_generales);?>
				</table>
			</td>
		</tr>	
		<tr>
			<td colspan="7" align="center" >
			  <div id="boton">	
				<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
				<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
				 </div></td>
					
		</tr>		
	</table>
