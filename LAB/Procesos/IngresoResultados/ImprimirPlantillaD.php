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
$f_tomamuestra=$_GET['var6'];
$tipomuestra=$_GET['var7'];


?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
<!--<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">-->

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

        $Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
        $row_estab = pg_fetch_array($Consulta_Estab);

	$consulta=$obj->MostrarElementosAgregados($idresultado);
    	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);
	$row_empleado = pg_fetch_array($datos_empleado);
        $FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
	$row_fecha=pg_fetch_array($FechaRes);

	//echo $IdSolicitud."-".$IdExamen."-".$Idresultado."-".$IdArea;
?>

	<table width='100%' align='center' class='StormyWeatherFormTABLE' cellspacing="0">
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="width: auto; height: 55px;" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td width="60%" align='center' colspan='4' class='Estilo6'>
				<span class="Estilo6"><p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				<p><strong><?php echo $row_estab['nombre'] ?></strong></p>
				<p><strong>&Aacute;REA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></span>
			</td>
                        <td colspan="1" align="right" width="20%"><img id="Image3" style="width: auto; height: 55px;" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                    <td colspan="6" align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold' ><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['estabext']?></td>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
                           
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Expediente</strong></td>
			<td colspan='2' class="Estilo7"><?php echo $row_generales['numero']?></td>
                        <td colspan='1' class="Estilo5" ><strong>Fecha Recepción:</strong></td>
                        <td colspan='2' class="Estilo6" ><?php echo $row_generales['fecharecep']?></td>
                </tr>
                <tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Paciente</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
                        <td colspan='1' class="Estilo6" ><strong>Fecha Toma Muestra:</strong></td>
                        <td colspan='2' class="Estilo6" ><?php echo $f_tomamuestra ?></td>
		</tr>
		<tr>    
                        <td colspan='1' class="Estilo5" style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['edad']?></td>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6" style='font:bold'><?php echo htmlentities($row_generales['procedencia'])?></td>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6" style='font:bold' colspan='2'><?php echo $row_generales['subservicio']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6" style='font:bold'><?php echo htmlentities($row_area['nombre_reporta'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_empleado['nombreempleado'])?></td>
		</tr>
		<tr>
			<td colspan='6'>&nbsp;&nbsp;&nbsp;&nbsp</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
                        <td colspan='4' aling='center'  >
				<table width='110%' border='0' aling='center' cellspacing="0">
                                    <tr>
                                        <td width='50%' class="Estilo5" aling='center' ><strong>Elemento de Tinci&oacute;n</strong>
					</td>
					<td width='50%' class="Estilo5" aling='center' ><strong>Cantidad</strong>
					</td>
                                    </tr>
			<?php while($row = pg_fetch_array($consulta)){
				?>
                                    <tr>
                                    	<td width='50%' class="Estilo6" ><?php echo $row[0]?></td>
					<td width='50%' class="Estilo6"><?php echo $row[1]?></td>
                                    </tr>
				<?php	}
						pg_free_result($consulta);
						pg_free_result($consulta_datos);
						pg_free_result($datos_generales);?>
				</table>
			</td>
                        <td colspan='1'>&nbsp;</td>
		</tr>
            </table>
            <div id="boton" >
                <table align="center">
                    <tr>
                        <td colspan="6" align="center" >
                            <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                            <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
                        </td>
                    </tr>
                </table>
            </div>
