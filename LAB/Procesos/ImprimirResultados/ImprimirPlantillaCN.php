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
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">

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
		<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                <td align="center" colspan="4" width="60%" class="Estilo6">
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
					 <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
					<p><strong>Area de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
				</td>
                                 <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
			</tr>

			<tr>
				<td style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
				<td colspan='2'><?php echo $establecimiento?></td>
				<td style='font:bold'><strong>Fecha Resultado:</strong></td>
				<td colspan='2'><?php echo $row_generales['Fecha']?></td>
			</tr>

			<tr>
				<td style='font:bold'><strong>NEC</strong></td>
				<td><?php echo $row_generales['IdNumeroExp']?></td>
			    <td colspan='1'>&nbsp;</td>
				<td style='font:bold'><strong>Paciente</strong></td>
				<td><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
			</tr>
			<tr>
				<td style='font:bold'><strong>Edad</strong></td>
				<td><?php echo $row_generales['Edad']?></td>
				<td colspan='1'>&nbsp;</td>
				<td style='font:bold'><strong>Sexo</strong></td>
				<td><?php echo $row_generales['Sexo']?></td>
			</tr>
			<tr>
				<td style='font:bold'><strong>Procedencia:</strong></td>
				<td style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
				<td colspan='1'>&nbsp;</td>
				<td style='font:bold'><strong>Origen:</strong></td>
				<td style='font:bold'><?php echo htmlentities($row_generales['Origen'])?></td>
			</tr>
			<tr>
				 <td style='font:bold'><strong>Examen Realizado:</strong></td>
				 <td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		        </tr>
			<tr>
				 <td style='font:bold'><strong>Validado Por</strong></td>
				 <td colspan='2'><?php echo htmlentities($responsable) ?></td>
			</tr>
			<tr>
				 <td style='font:bold'><strong>Resultado</strong></td>
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
				<td style='font:bold'><strong>Observaci&oacute;n</strong></td>
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
