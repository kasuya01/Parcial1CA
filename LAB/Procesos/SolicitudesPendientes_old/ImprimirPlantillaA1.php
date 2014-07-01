<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$idsolicitud=$_GET['var1'];
$idarea=$_GET['var2'];
$idempleado=$_GET['var3'];
$valores_resultados=$_GET['var4'];
$codigos_resultados=$_GET['var5'];
$valores_lecturas=$_GET['var6'];
$valores_inter=$_GET['var7'];
$valores_obser=$_GET['var8'];
$codigos_examenes=$_GET['var9'];
$establecimiento=$_GET['var10'];
$sexo=$_GET['var11'];
$idedad=$_GET['var12'];
//echo $sexo."###".$idedad;
//echo $idsolicitud."-".$idarea."-".$idempleado."-".$valores_resultados."-".$codigos_resultados."-".$valores_lecturas."-".$valores_inter."-".$valores_obser."-".$codigos_examenes;
//echo $codigos_examenes;
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
include_once("clsSolicitudesProcesadas.php");
  $objdatos = new clsSolicitudesProcesadas;
  $Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
  $row_estab = mysql_fetch_array($Consulta_Estab);	
  $consulta1=$objdatos->MostrarResultadoGenerales1($idsolicitud,$idarea,$lugar);
  $row = mysql_fetch_array($consulta1);
  $nombre=$row['NombreArea'];
  $vector_resultados=explode("/",$valores_resultados);
  $vector_codigos=explode("/",$codigos_resultados);
  $vector_lecturas=explode("/",$valores_lecturas);
  $vector_inter=explode("/",$valores_inter);
  $vector_obser=explode("/",$valores_obser);
  $vector_examenes=explode("/",$codigos_examenes);
 // echo $idsolicitud."**".$idarea."**".$lugar."**".$sexo."**".$idedad;
  $consulta=$objdatos->DatosExamenes($idsolicitud,$idarea,$lugar,$sexo,$idedad);
  $FechaRes=$objdatos->ObtenerFechaResultado($idsolicitud,$idarea,$lugar);
  $row_fecha=mysql_fetch_array($FechaRes);
	
?>
	<table width='100%' align='center' class='StormyWeatherFormTABLE' cellspacing="0">
		<tr>
                     <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo1.png" width="210" name="Image1"></td>
                <td align="center" colspan="4" width="60%" class="Estilo6">
				 <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				 <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
				 <p><strong>&Aacute;rea de <?php echo htmlentities($row['NombreArea'])?></strong></p>
		    </td>
                    <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.gif" width="210" name="Image3"></td>
		</tr>
                 <tr>
                    <td colspan="6" align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
			<td colspan='2' style='font:bold'><?php echo htmlentities($_GET['var10'])?></td>
			<td colspan='1' style='font:bold'><strong>Fecha Resultado: </strong></td>
			<td colspan='2' style='font:bold'><?php echo $row_fecha['FechaResultado']?></td>
		</tr>
		
		<tr>
			<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			<td colspan='1' style='font:bold'><?php echo $row['IdNumeroExp']?></td>
                </tr>
                <tr>
                        <td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='3' style='font:bold'><?php echo htmlentities($row['NombrePaciente'])?></td>
			
			<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
                </tr>        
		<tr>
					
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2' style='font:bold'><div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   		  </div></td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2' style='font:bold'><?php echo $row['Sexo']?></td>					
		</tr>
		</tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='2' style='font:bold'><?php echo htmlentities($row['Procedencia'])?></td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' style='font:bold'><?php echo htmlentities($row['Origen'])?></td>
			
		</tr>
		<tr>
			<?php $consulta_empleado=$objdatos->BuscarEmpleadoValidador($idempleado);
				$fila_empleado = mysql_fetch_array($consulta_empleado);//$fila_empleado['NombreEmpleado'].
			?>
			<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
			<td  colspan='5' style='font:bold'><?php echo htmlentities($fila_empleado['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
		</tr>
                <tr>
                    <td  colspan='6'>
	
                        <table width='100%'  align='center' border='0' cellspacing="0">
                                <tr>
                                        <td align='center'>Prueba Realizada </td>
                                        <td align='center'>Resultado</td>
                                        <td align='center'>Unidades</td>
                                        <td align='center'>Rangos Normales </td>
                                        <td align='center'>Lectura</td>
                                        <td align='center'>Interpretaci&oacute;n</td>
                                        <td align='center'>Observaci&oacute;n</td>

                                </tr>
                                        <?php $pos=0;
                        while($rowdet = mysql_fetch_array($consulta)){?>
                                <tr>
                                        <td><?php echo htmlentities($rowdet['NombreExamen'])?></td>
                                <?php 

                                     if ($rowdet['IdDetalleSolicitud']== $vector_codigos[$pos]){
                                ?>     	<td align='center'><?php echo htmlentities($vector_resultados[$pos])?></td>
                                        <td align='center'><?php echo htmlentities($rowdet['Unidades'])?></td>
                                        <td align='center'><?php echo $rowdet['RangoInicio']." - ".$rowdet['RangoFin']?></td>
                                        <td><?php echo htmlentities($vector_lecturas[$pos])?></td>
                                        <td><?php echo htmlentities($vector_inter[$pos])?></td>
                                        <td><?php echo htmlentities($vector_obser[$pos])?></td>
                                </tr>
                                <?php
                                      }
                                        $pos=$pos + 1;
                                 }
                                ?>					
                                
                                
                        </table>
                    </td>
                </tr>
                </table>
                 <div id="boton">
                     <table align='center' border="0">
                        <tr>
                            <td colspan="7" align="center" >
                                <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" />
                                            
                            </td>
                        </tr>
                      </table>
                 </div>
                                