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
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script> 
<!--<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
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


.Estilo5 {font-size: 10pt}
.Estilo6 {font-size: 9pt}
.Estilo12 {font-size: 11pt}
-->
</style>

</script>
</head>

<body>
<?php 

        $Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
        $row_estab = mysql_fetch_array($Consulta_Estab);

	$consulta=$obj->MostrarElementosAgregados($idresultado);
    	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud);
	$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= mysql_fetch_array($datos_generales);
	$row_area = mysql_fetch_array($consulta_datos);
	$row_empleado = mysql_fetch_array($datos_empleado);
        $FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
	$row_fecha=mysql_fetch_array($FechaRes);
	
        //echo $IdSolicitud."-".$IdExamen."-".$Idresultado."-".$IdArea;
?>
	
	<table width='100%' align='center' border="0" class='StormyWeatherFormTABLE' cellspacing="0">
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo1.png" width="210" name="Image1"></td>
                        <td width="60%" align='center' colspan='4' >
				<span class="Estilo6"><p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				<p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
				<p><strong>&Aacute;REA DE <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></span
			</td>
                        <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.gif" width="210" name="Image3"></td>
		</tr>
                <tr>
                    <td colspan="6" align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2' class="Estilo6"  ><?php echo $establecimiento?></td>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_fecha['FechaResultado']?></td>
                            <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
		</tr>
		
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>NEC</strong></td>
			<td colspan='5' class="Estilo6" ><?php echo $row_generales['IdNumeroExp']?></td>
                </tr>
                <tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
		</tr>
		<tr>
			
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6">
                            <div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
               					calc_edad();
          			</script>
                            </div>
                        </td>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['Sexo']?></td>
		</tr>
		<tr>	
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6" style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6" style='font:bold' colspan='2'><?php echo $row_generales['Origen']?></td>
		</tr>	
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6" style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>				 
		<tr>
			<td colspan='1' class="Estilo5" style='font:bold'><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_empleado['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='6'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
                        <td colspan='4' aling='center'  >
                            <table width='110%' border='0' aling='center' cellspacing="0">
                                <tr>
                                    <td width='50%' colspan='1' class="Estilo5" style='font:bold' aling='center' ><strong>Elemento de Tinci&oacute;n</strong>
                                    </td>
                                    <td width='50%' colspan='1' class="Estilo5" style='font:bold' aling='center' ><strong>Cantidad</strong>
                                    </td>
				</tr>
				<?php while($row = mysql_fetch_array($consulta)){
				?>
					<tr>
						<td width='50%' colspan='1' class="Estilo6"><?php echo $row[0]?></td>
						<td width='50%' colspan='1' class="Estilo6"><?php echo $row[1]?></td>
					</tr>
				<?php	}
						mysql_free_result($consulta);
						mysql_free_result($consulta_datos);
						mysql_free_result($datos_generales);?>
				</table>
			</td>
                        <td colspan='1'>&nbsp;</td>
		</tr>
            </table>
             <div id="boton" align="center">
                 <table>
                    <tr>
                        <td colspan="6" align="center" >

                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
                                     </td>

                    </tr>
                 </table>
             </div>		
	
