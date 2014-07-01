<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//Datos recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$sexo=$_GET['var6'];
$fechanac=$_GET['var7'];
//echo $fechanac."    ".$sexo;
//echo $iddetalle."*sol".$idsolicitud."*plan".$idplatilla."*exp".$expediente."*area".$idarea 
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
include_once("clsImprimirResultado.php");
  $objdatos = new clsImprimirResultado;

//echo $lugar;
//echo $idsolicitud;
  	
  $consulta1=$objdatos->MostrarResultadoGenerales1($idsolicitud,$idarea,$lugar);
  $row = mysql_fetch_array($consulta1);  
  $Establecimiento=$row['IdEstablecimiento'];
   //echo $Establecimiento; 
  $Consulta_Estab=$objdatos->Nombre_Establecimiento($Establecimiento);
  $row_estab = mysql_fetch_array($Consulta_Estab);
 
  $nombre=$row['NombreArea'];
  
   $Cuentadias=$objdatos->CalculoDias($fechanac);
   $Cdias= mysql_fetch_array($Cuentadias);
   $dias=$Cdias[0];
	
   $ConRangos=$objdatos->ObtenerCodigoRango($dias);
   $row_rangos=  mysql_fetch_array($ConRangos);
   $idedad=$row_rangos[0];  
//echo  $idedad."   ".$sexo;
 $consulta=$objdatos->DetalleExamenes($idsolicitud,$idarea,$lugar,$idedad,$sexo);
	
?>
	<table width='95%' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td align='center' colspan='6' class='CobaltButton'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong><?php echo $row['Nombre'] ?></strong></p>
                            <p><strong>&Aacute;rea de <?php echo htmlentities($row['NombreArea'])?></strong></p>
			</td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2' style='font:bold'><?php echo htmlentities($row_estab['Nombre'])?></td>
			<td colspan="1" style='font:bold'><strong>Fecha Recepci&oacute;n:</strong></td>
			<td colspan="2" style='font:bold'><?php echo $row['Fecha'];?></td>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
		</tr>
		</tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'><?php echo htmlentities($row['Procedencia'])?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' style='font:bold'><?php echo htmlentities($row['Origen'])?></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>NEC</strong></td>
			<td colspan='1' style='font:bold'><?php echo $row['IdNumeroExp']?></td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='2' style='font:bold'><?php echo htmlentities($row['NombrePaciente'])?></td>
 		</tr>		
		<tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='1' style='font:bold'>
                            <div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
                                	calc_edad();
          			</script>
    		   	    </div>
			</td>
			<td colspan='1'>&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='1' style='font:bold'><?php echo $row['Sexo']?></td>					
		</tr>
		<tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
		</tr>
	</table>
	<table width='95%'  align='center' border='0' class='StormyWeatherFormTABLE'>
		<tr class='CobaltButton'>
			<td align='center'>Prueba Realizada </td>
			<td align='center'>Resultado</td>
			<td align='center'>Unidades</td>
			<td align='center'>Rangos Normales </td>
			<!--<td align='center'>Lectura</td>
			<td align='center'>Interpretaci&oacute;n</td>-->
			<td align='center'>Observaci&oacute;n</td>
 			<td align='center'>Validado por</td>
			<td align='center'>Fecha Resultado</td>
		</tr>
			<?php $pos=0;
		while($rowdet = mysql_fetch_array($consulta)){?>
		<tr>
			<td width='25%' ><?php echo htmlentities($rowdet['NombreExamen'])?></td>
		    <td><?php echo htmlentities($rowdet['Resultado'])?></td>
			<td align='center'><?php echo htmlentities($rowdet['Unidades'])?></td>
			<td width='15%' ><?php echo $rowdet['RangoInicio']."-".$rowdet['RangoFin']?></td>
			 <!--  <td><?php //echo htmlentities($rowdet['Lectura'])?></td>
		     <td><?php //echo htmlentities($rowdet['Interpretacion'])?></td>-->
		    <td><?php echo htmlentities($rowdet['Observacion'])?></td>
			<td><?php echo htmlentities($rowdet['NombreEmpleado'])?></td>
			<td><?php echo htmlentities($rowdet['Fecha'])?></td>
		</tr>
		<?php
		      
			$pos=$pos + 1;
                }
		?>					
		<tr>
			<td>&nbsp; </td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>			
		</tr>
		<tr>
			<td colspan="7" align="center" >
				<div id="boton">	
					<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
					<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" />
				</div>
			</td>
		</tr>
	</table>
