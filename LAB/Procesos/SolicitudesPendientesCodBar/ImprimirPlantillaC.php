<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsConsultarElementosPlantillaC.php");
$obj = new clsConsultarElementosPlantillaC;

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
$resultado=$_GET['var3'];
$responsable=$_GET['var4'];
$procedencia=$_GET['var5'];
$origen=$_GET['var6'];
$observacion=$_GET['var7'];
$valores_antibioticos=$_GET['var8'];
$codigos_antibioticos=$_GET['var9'];
$idbacteria=$_GET['var10'];
$cantidad=$_GET['var11'];
$idtarjeta=$_GET['var12'];
$nombrearea=$_GET['var13'];
$establecimiento=$_GET['var14'];

$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
$row_estab = mysql_fetch_array($Consulta_Estab);

$FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
$row_fecha=mysql_fetch_array($FechaRes);

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
	$row_generales= mysql_fetch_array($datos_generales);
	$consulta_datos=$obj->LeerDatos($idexamen);
	$row_area= mysql_fetch_array($consulta_datos);
	$bateria=$obj->NombreBacteria($idbacteria);
	$row_nombrebacteria= mysql_fetch_array($bateria);
	$consulta=$obj->LeerAntibioticos($idtarjeta);
	$vector_valores=EXPLODE("/",$valores_antibioticos);
        $vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
	//$obj = new clsConsultarElementosPlantillaC;
        $tamano_vector=count($vector_valores);
	$tamano_vectoantibiotico=count($vector_antibioticos); 

  
  $posele=0;
  $ban=0;
	//$datos_empleado=$obj->DatosEmpleado($idempleado);
	//$row_empleado = mysql_fetch_array($datos_empleado);
	//$datos_observacion=$obj->LeerObservacion($observacion);
	//$row_observacion = mysql_fetch_array($datos_observacion);
$nombre=$row_area['NombreArea'];
?>
<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
	<tr>
		<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
			<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
			<p><strong>&Aacute;rea de <?php echo htmlentities($nombrearea)?> </strong></p></td>
	</tr>
	<tr>
		<td colspan='1' width='10%' style='font:bold'><strong>Establecimiento:</strong></td>
		<td colspan='3' ><?php echo $establecimiento?></td>
                <td colspan='1' width='6%' style='font:bold'><strong>Fecha:</strong></td>
		<td colspan='1' width='10%' colspan='1'><?php echo $row_generales['Fecha']?></td><input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
	</tr>
	<tr>	
		<td colspan='1' width='10%' style='font:bold'><strong>Procedencia:</strong></td>
		<td colspan='1' style='font:bold' width='25%'><?php echo htmlentities($row_generales['Procedencia']);?></td>
                <td colspan='1'>&nbsp;</td>
		<td  colspan='1' style='font:bold'><strong>Servicio:</strong></td>
		<td colspan='2' style='font:bold' width='6%'><?php echo htmlentities($origen);?></td>
	</tr>
	<tr>
		<td colspan='1' width='10%' style='font:bold'><strong>NEC</strong></td>
	        <td colspan='1' ><?php echo $row_generales['IdNumeroExp']?></td>
		<td colspan='1' width='10%' style='font:bold'><strong>Paciente:</strong></td>
		<td colspan='3' width='30%'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
	</tr>
	<tr>
		<td colspan='1' width='5%' style='font:bold'><strong>Edad:</strong></td>
	 	<td colspan='1'>
			<div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
               					calc_edad();
          			</script>
    		   	</div>
		</td>
		<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
		<td colspan='1'><?php echo $row_generales['Sexo'];?></td>
	</tr>
	<tr>
		<td colspan='1' width='10%' style='font:bold'><strong>Examen Realizado:</strong></td>
	  	<td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen']);?></td>
	</tr>				 
	<tr>
		<td colspan='1' width='15%' style='font:bold'><strong>Resultado:</strong></td>
		<td colspan='4' style='font:bold'><?php echo "Positivo"?></td>
	</tr>
	<tr>
		<td colspan='1'>Organismo:</td>
		<td colspan='4'><?php echo htmlentities($row_nombrebacteria['Bacteria']); ?></td>				 
	</tr>	
	<tr>
		<td colspan='1' width='15%' style='font:bold'><strong>Validado Por:</strong></td>
		<td colspan='5'><?php echo $responsable;?></td>
	</tr>
	<tr>
		<td colspan='5'>&nbsp;</td>
	</tr>
			
	<tr>
		<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td width='46%' class='StormyWeatherFieldCaptionTD'>CULTIVO CON CUENTA DE COLONIAS</td>
				<td colspan='3' width='54%' class='StormyWeatherDataTD'><?php echo htmlentities($cantidad)?></td>
			</tr>
			<tr class='CobaltButton'>
				<td width='46%' style='font:bold' ><strong>ANTIBIOTICO</strong></td>
				<td width='54%' colspan="2" style='font:bold'  ><strong>INTERPRETACI&Oacute;N</strong>
				</td>
			</tr>	
				<?php	//mysql_free_result($consulta_datos);
					mysql_free_result($datos_generales);
					$pos=0;
						
				while($row = mysql_fetch_array($consulta))//ELEMENTOS)
				{?>
			<tr>
				<td width='50%'><?php echo $row['Antibiotico']?></td>
				<td width='50%' ><?php echo htmlentities($vector_valores[$pos])?>
					<input name='oidantibiotico[".$pos."]' type='hidden' id='oidantibiotico[<?php $pos ?>]' value='<?php $row['IdAntibiotico']?>'>
				</td>
				<td width='60%'>&nbsp; </td>
			</tr>
			<?php $pos=$pos+1;		
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
</form>
