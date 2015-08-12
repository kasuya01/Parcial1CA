<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsConsultarElementosPlantillaC.php");
$obj = new clsConsultarElementosPlantillaC;

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
$IdArea=$_GET['var3'];
$resultado=$_GET['var4'];
$responsable=$_GET['var5'];
$procedencia=$_GET['var6'];
$origen=$_GET['var7'];
$observacion=$_GET['var8'];
$establecimiento=$_GET['var9'];

$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
$row_estab = mysql_fetch_array($Consulta_Estab);
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


.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>

</script>
</head>

<body>
<?php 
	
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	//$datos_empleado=$obj->DatosEmpleado($idempleado);
	$consulta_datos=$obj->LeerDatos($idexamen);
	$row_area= mysql_fetch_array($consulta_datos);
	$row_generales= mysql_fetch_array($datos_generales);
	//$row_empleado = mysql_fetch_array($datos_empleado);
	//$datos_observacion=$obj->LeerObservacion($observacion);
	//$row_observacion = mysql_fetch_array($datos_observacion);
	$nombre=$row_area['NombreArea'];
?>
<table align="center" width="100%">
<tr>
	<td>
		<div  id="divImpresion" >
		<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
					 <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
					<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
				</td>
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
				<td colspan='3'><?php echo $establecimiento?></td>
				<td colspan='1' style='font:bold'><strong>Fecha:</strong></td>
				<td colspan='1'><?php echo $row_generales['Fecha']?></td>
					<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
			</tr>
			<tr>	
				<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
				<td colspan='1' style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
				<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
				<td colspan='3' style='font:bold'><?php echo htmlentities($row_generales['Origen'])?></td>
			</tr>	
			<tr>
				<td colspan='1' style='font:bold'><strong>NEC</strong></td>
				<td colspan='1'><?php echo $row_generales['IdNumeroExp']?></td>
			       	<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
				<td colspan='1'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
				
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
				<td colspan='1'><div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   		    </div></td>
				<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
				<td colspan='1'><?php echo $row_generales['Sexo']?></td>
			</tr>
						
			<tr>
				 <td style='font:bold'><strong>Examen Realizado:</strong></td>
				 <td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		        </tr>				 
			<tr>
				 <td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
				 <td colspan='4'><?php echo htmlentities($responsable) ?></td>
			</tr>
			<tr>
				 <td colspan='1' style='font:bold'><strong>Resultado:</strong></td>
					<?php 	mysql_free_result($consulta_datos);
						mysql_free_result($datos_generales);	 
					 
						 switch($resultado)
						{
						  case "P";?>
							<td colspan='4'>Positivo</td>
					<?php break;
						  case "N";?>
							<td colspan='4'>Negativo</td>
					<?php break;
					case "O";?>
							<td colspan='4'>--</td>
					<?php break;
	   
					}?>
			</tr>
			<tr>
				<td colspan='1' style='font:bold'><strong>Observaci&oacute;n:</strong></td>
				<td colspan='4'><?php echo htmlentities($observacion)?></td>
			</tr>
			<tr>
				<td colspan='5' align='center'></td>
			</tr>
			<tr>
				<td colspan='5'>&nbsp;</td>
			</tr>
		</table></div></td></tr>
        <tr class="5"><td>		
		 	<div id="boton">	
				<div align="center">
				  <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
				  <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
			      </div>
		 	</div>
		</td></tr>
</table>
