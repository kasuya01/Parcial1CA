<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//Datos Recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$IdExamen=$_GET['var6'];
$subservicio=$_GET['var8'];
$idsexo=$_GET['idsexo'];
$idedad=$_GET['idedad'];
//echo 'idedad:'.$idedad;

//echo $iddetalle."*soli".$idsolicitud."*plantilla".$idplatilla."*exp".$expediente."*area".$idarea."*exam".$IdExamen
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_ImprimirResultado.js"></script>
<!--<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">-->
<script language="JavaScript">
/*function calc_edad()
{
  var fecnac1=document.getElementById("suEdad").value;
  var fecnac2=fecnac1.substring(0,10);
//alert (fecnac2);
  var suEdades=calcular_edad(fecnac2);
 // alert(suEdades);

  document.getElementById("divsuedad").innerHTML=suEdades;
}*/
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


</head>

<body>
<?php
include_once("clsImprimirResultado.php");
$objdatos = new clsImprimirResultado;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
$row_estab = pg_fetch_array($Consulta_Estab);
//$consulta=$objdatos->MostrarResultadoGenerales($idsolicitud,$IdExamen,$lugar);
$consulta=$objdatos->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
$row = pg_fetch_array($consulta);
$nombre=$row['nombre_area'];

?>
<table align="center" width="100%">
<tr>
	<td>
	<div  id="divImpresion" >
            <form name="frmimpresion" >
		<table width='100%' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                <td align="center" colspan="4" width="60%" class="Estilo6">
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
					<p><strong><?php echo $row_estab['nombre'] ?></strong></p>
					<p><strong>ÁREA DE <?php echo $nombre; ?> </strong></p>
				</td>
                                <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
			</tr>
			<tr>
				<td colspan='6' align='center'>&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan="1" class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row['estabext']);?></td>
				<td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo $row['fecharecepcion'];?></td>
				
			</tr>

			<tr>
				<td colspan="1" class="Estilo5"><strong>Expediente:</strong></td>
				<td colspan="5" class="Estilo7"><?php echo $row['idnumeroexp'];?></td>
                        </tr>
                        <tr>

				<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
				<td colspan="5" class="Estilo6"><?php echo htmlentities($row['paciente'])?></td>
			</tr>
			<tr>
				<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row['edad'])?></td>
				</td>

				<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
				<td colspan="5" class="Estilo6"><?php echo $row['sexo']?></td>
			</tr>

			<?php
				$consulta2=$objdatos->MostrarDatosFijosPlantillaA($IdExamen,$lugar,$iddetalle, $idsexo, $idedad);
				$fila = pg_fetch_array($consulta2);
			?>
                        <tr>
				<td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row['nombreservicio'])?></td>
				<td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($subservicio)?></td>
			</tr>
			<tr>
				<td  colspan='1' class="Estilo5"><strong>Validado Por: </strong></td>
				<td  colspan='5' class="Estilo6"><?php echo htmlentities($row['empleado'])?></td>
			</tr>
			<tr>
				<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan='6' align='center' class="Estilo5" ><strong>DETALLE DE RESULTADOS</strong></td>
			</tr>
                        <tr>
                            <td  colspan="6">
                                <table width="100%"  align="center" border="0" class="StormyWeatherFormTABLE">
                                    <tr class="CobaltButton">
                                        <td align="left" class="Estilo5"><strong> Prueba Realizada</span></strong></td>
                                            <td align="center" class="Estilo5"><strong>Resultado</strong></td>
                                            <td align="center" class="Estilo5"><strong>Unidades</strong></td>
                                            <td align="center" class="Estilo5"><strong>Rangos Normales</strong></td>
                                            <td align="center" class="Estilo5"><strong>Lectura</strong></td>
                                            <td align="center" class="Estilo5"><strong>Interpretaci&oacute;n</strong></td>
                                            <td align="center" class="Estilo5"><strong>Observaci&oacute;n</strong></td>
                                    </tr>
                                    <tr>
                                            <td align="left" class="Estilo5"><?php echo htmlentities($fila['nombre_examen'])?></td>
                                            <td align="center" class="Estilo5"><?php echo htmlentities($row['resultado'])?></td>
                                            <td align="center" class="Estilo5"><?php echo htmlentities($fila['unidades']) ?></td>
                                            <td align="center" class="Estilo5"><?php echo $fila['rangoinicio']."-".$fila['rangofin']?></td>
                                            <td align="justify" class="Estilo5"><?php echo htmlentities($row['lectura'])?></td>
                                            <td align="justify" class="Estilo5"><?php echo htmlentities($row['interpretacion'])?></td>
                                            <td align="justify" class="Estilo5"><?php echo htmlentities($row['observacion'])?></td>
                                    </tr>
                                </table>
			  </td>
                        </tr>
                    </table>
                    <div id="boton">
                        <table align='center' border="0">
                            <tr>
				<td colspan="7" align="center" >
                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" />

				</td>
                            </tr>
                        </table>
                      </div>
		</form>
	</div>
</tr>
</table>
