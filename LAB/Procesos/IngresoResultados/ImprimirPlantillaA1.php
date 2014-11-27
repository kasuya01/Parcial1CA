<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$idsolicitud=$_GET['var1'];
$idarea=$_GET['var2'];
$idempleado=$_GET['var3'];
$valores_resultados=$_GET['var4'];
$codigos_resultados=$_GET['var5'];
//$valores_lecturas=$_GET['var6'];
//$valores_inter=$_GET['var7'];
$valores_obser=$_GET['var8'];
$codigos_examenes=$_GET['var9'];
$establecimiento=$_GET['var10'];
$sexo=$_GET['var11'];
$idedad=$_GET['var12'];
$examen_metodologia =$_GET['var13'];
$txtnec =$_GET['var14'];
$fechareporte =$_GET['var15'];
$procedencia =$_GET['var16'];
$origen =$_GET['var17'];
//echo $sexo."###".$idedad;
//echo $idsolicitud."-".$idarea."-".$idempleado."-".$valores_resultados."-".$codigos_resultados."-".$valores_lecturas."-".$valores_inter."-".$valores_obser."-".$codigos_examenes;
//echo $codigos_examenes;
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
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
include_once("clsSolicitudesProcesadas.php");
  $objdatos = new clsSolicitudesProcesadas;
  $Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
  $row_estab = pg_fetch_array($Consulta_Estab);
  
  $consulta1=$objdatos->MostrarResultadoGeneralesPA1($idsolicitud,$lugar,$idarea);
  $row = pg_fetch_array($consulta1);
  $nombre=$row['nombrearea'];
  $id_establecimiento_externo = $row['id_establecimiento_externo'];
  $idhistoref = $row['idhistoref'];
  $Consulta_Estab2=$objdatos->Nombre_Establecimiento($id_establecimiento_externo);
$row_estab2 = pg_fetch_array($Consulta_Estab2);
  $datpac=$objdatos->MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $txtnec, $idhistoref);
  
$rowpa = pg_fetch_array($datpac);
  //$vector_resultados=explode("/",$valores_resultados);
  //$vector_codigos=explode("/",$codigos_resultados);
 // $vector_lecturas=explode("/",$valores_lecturas);
  //$vector_inter=explode("/",$valores_inter);
  //$vector_obser=explode("/",$valores_obser);
  //$vector_examenes=explode("/",$codigos_examenes);
  $consulta=$objdatos->DatosExamenesImprimir($idsolicitud,$idarea,$lugar,$sexo,$idedad);
 //$rowdet=  pg_fetch_array($consulta);
 // $FechaRes=$objdatos->ObtenerFechaResultado($idsolicitud,$idarea,$lugar);
  //$row_fecha=pg_fetch_array($FechaRes);

?>
	<table width='85%' align='center' class='StormyWeatherFormTABLE'>
		<tr>
                     <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                <td align="center" colspan="4" width="60%" class="Estilo6">
			<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			<p><strong><?php echo $row_estab['nombre'] ?></strong></p>
			<p><strong>ÁREA DE <?php echo $nombre; ?></strong></p>
                    </td>
                    <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
                        <td colspan='2' class="Estilo6"><?php echo utf8_encode($_GET['var10'])?></td>
			<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"'><?php echo $fechareporte;?></td>
		</tr>

		<tr>
			<td colspan='1' class="Estilo5"><strong>NEC:</strong></td>
			<td colspan='5' class="Estilo7"><?php echo $txtnec;?></td>
                </tr>

                <tr>
			<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo $rowpa['nombre']?></td>

                            <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>
		<tr>

			<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6">
                           <?php echo  $rowpa['edad'];?></td>
			<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $rowpa['sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $procedencia;?></td>
			<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $origen?></td>

		</tr>
		<tr>
			<?php 
                            $consulta_empleado = $objdatos->BuscarEmpleadoValidador($idempleado, $lugar);
                            $fila_empleado = pg_fetch_array($consulta_empleado);
                          //  $fila_empleado = mysql_fetch_array($consulta_empleado);//$fila_empleado['NombreEmpleado'].
			?>
			<td  colspan='1' class="Estilo5"><strong>Validado Por: </strong></td>
			<td  colspan='5' class="Estilo6"><?php echo $fila_empleado['empleado']?></td>
		</tr>
		<tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
                 <tr><td colspan="6"><hr></td></tr>
		<tr>
			<td colspan='6' align='center' class="Estilo6"><strong>DETALLE DE RESULTADOS</strong></td>
		</tr>
	</table>
	<table width='85%'  align='center' border='0' class='StormyWeatherFormTABLE'>
		<tr>
			<td align='left' class='Estilo5'><strong>Prueba Realizada</strong></td>
			<td align='center' class='Estilo5'><strong>Resultado</strong></td>
			<td align='center' class='Estilo5'><strong>Unidades</strong></td>
			<td align='center' class='Estilo5'><strong>Rangos Normales</strong></td>
			<!--<td align='center' class='Estilo5'><strong>Lectura</strong></td>
			<td align='center' class='Estilo5'><strong>Interpretaci&oacute;n</strong></td>-->
			<td align='center' class='Estilo5'><strong>Observaci&oacute;n</strong></td>
                        <td align='center' class='Estilo5'><strong>Fecha Resultado</strong></td>

		</tr>
                <tr><td colspan="6"><hr></td></tr>
			<?php $pos=0;
		while($rowdet = pg_fetch_array($consulta)){?>
		<tr>
			<td class="Estilo6" align="justify"><?php echo $rowdet['nombre_reporta']?></td>
		<?php
                    //  echo $rowdet['IdDetalleSolicitud'] ."vector".$vector_codigos[$pos];
		//if ($rowdet['IdDetalleSolicitud']== $vector_codigos[$pos]){
		?>     	<td class='Estilo6' align='center'><?php echo $rowdet['resultado']?></td>
			<td class='Estilo6' align='center'><?php echo $rowdet['unidades']?></td>
			<td class='Estilo6' align='center'><?php echo $rowdet['rangoinicio']."-".$rowdet['rangofin']?></td>
			<!--<td class='Estilo6' align='justify'><?php //echo htmlentities($vector_lecturas[$pos])?></td>
		        <td class='Estilo6' align='justify'><?php //echo htmlentities($vector_inter[$pos])?></td>-->
		        <td class='Estilo6' align='center'><?php echo $rowdet['observacion']?></td>
                        <td class='Estilo6' align='center'><?php echo $rowdet['fecharesultado']?></td>
		</tr>
		<?php
		     // }
			$pos=$pos + 1;
                 }
		?>

                </table>
                <table align='center' border="0">
                    <div id="boton">
                        <tr>
                                <td colspan="7" align="center" >

                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" />

                                </td>
                        </tr>
                   </div>
                </table>
