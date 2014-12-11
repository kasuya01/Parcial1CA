<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsImprimirResultado.php");
$obj = new clsImprimirResultado;

//Datos recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$idexamen=$_GET['var6'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_ImprimirResultado.js"></script>
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


.Estilo5 {font-family: Helvetica; font-size: 10pt}
.Estilo6 {font-family: Helvetica; font-size: 9pt}
.Estilo7 {font-family: Helvetica; font-size: 11pt}
-->
</style>

</head>

<body>
<?php


	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = pg_fetch_array($Consulta_Estab);
        $consulta_res=$obj->ObtenerIdResultado($idsolicitud,$iddetalle);
        $row_res=pg_fetch_array($consulta_res);
        $idresultado=$row_res['idresultado'];
        $consulta=$obj->MostrarElementosAgregados($idresultado);
        $consulta_datos=$obj->LeerDatos($idexamen);
       // $idsolicitud="t01.id=$idsolicitud";
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
	$row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);
?>
	<table width='85%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td width="60%" align='center' colspan='4' class='Estilo6'>
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				<p><strong><?php echo $row_estab['nombre'] ?></strong></p>
				<p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
			</td>
                        <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
		<tr>
                    <td colspan="6" align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="1" class="Estilo5"><strong>Establecimiento:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['estabext'])?></td>
			<td colspan="1" class="Estilo5"><strong>Fecha Resultado</strong></td>
			<td colspan="2" class="Estilo6"><?php echo $row_generales['fecharecepcion']?></td>
				<input name='suEdad' id='suEdad' type='hidden' value=<?php echo $row_generales['FechaNacimiento']?>>
		</tr>

		<tr>
			<td colspan="1" class="Estilo5"><strong>NEC</strong></td>
			<td colspan="5" class="Estilo7"><?php echo $row_generales['idnumeroexp']?></td>
                </tr>
                <tr>
			<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan="5" class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
		</tr>
		<tr>
			<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['edad'])?></td>
               				
			<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo $row_generales['sexo']?></td>
		</tr>
		<tr>
			<td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['nombreservicio'])?></td>
			<td colspan="1" class="Estilo5"><strong>Origen:</strong></td>
			<td colspan='2' class="Estilo6" ><?php echo $row_generales['nombresubservicio']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['nombre_examen'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['empleado'])?></td>
		</tr>
		<tr>
			<td colspan='6'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
                        <td colspan='4' aling='center'  >
				<table width='110%' border='0' aling='center' cellspacing="0">
                                    <tr>
                                        <td width='50%' class="Estilo5" aling='center' ><span style='color: #0101DF;'><strong>Elemento de Tinci&oacute;n</span></strong>
					</td>
                                        <td width='50%' class="Estilo5" aling='center' ><span style='color: #0101DF;'><strong>Cantidad</span></strong>
					</td>
                                    </tr>
				<?php while($row = pg_fetch_array($consulta)){
				?>
					<tr>
						<td width='50%' class="Estilo6"><?php echo $row['elemento']?></td>
						<td width='50%' class="Estilo6"><?php echo $row['cantidadtincion']?></td>
					</tr>
				<?php	}
						pg_free_result($consulta);
						pg_free_result($consulta_datos);
						pg_free_result($datos_generales);?>
				</table>
			</td>
                 </tr>
             </table>
             <div id="boton" >
                <table align="center">
                    <tr>
                        <td colspan="6" align="center" >
                            <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                            <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
			</td>

                    </tr></div>
                </table>
