<?php session_start();
include_once("clsSolicitudesProcesadas.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
$sexo=$_GET['var8'];
$idedad=$_GET['var9'];
$d_resultado=$_GET['var10'];
$txtnec=$_GET['var11'];
$proce=$_GET['var12'];
$origen=$_GET['var13'];
$iddetalle=$_GET['var14'];
$marca=$_GET['var15'];
//echo $sexo."***".$idedad;
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


.Estilo5 {font-family: Helvetica; font-size: 10pt}
.Estilo6 {font-family: Helvetica; font-size: 9pt}
.Estilo7 {font-family: Helvetica; font-size: 11pt}
-->
</style>

</script>
</head>

<body>
<?php
$objdatos = new clsSolicitudesProcesadas;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
$row_estab = pg_fetch_array($Consulta_Estab);
//echo $sexo."***".$idedad."***".$IdSolicitud."***".$IdExamen;
$consulta=$objdatos->MostrarResultadoGenerales($idsolicitud,$idexamen,$lugar);
$row = pg_fetch_array($consulta);
$nombre = $row['nombrearea'];
$id_establecimiento_externo = $row['id_establecimiento_externo'];
$idhistoref = $row['idhistoref'];
$Consulta_Estab2=$objdatos->Nombre_Establecimiento($id_establecimiento_externo);
$row_estab2 = pg_fetch_array($Consulta_Estab2);
$datpac=$objdatos->MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $txtnec, $idhistoref);
$rowpa = pg_fetch_array($datpac);
$consultares = $objdatos->MostrarDatoslabresultado($idexamen, $lugar, $idsolicitud, $iddetalle);
        $filares = pg_fetch_array($consultares);
       // echo '<br\>'.$filares['idempleado'].'<br\>';
        //$d_resultfin=$filares['d_resultfin'];
//$Consulta_fecha=$objdatos->ObtenerFechaResultado1($IdSolicitud,$IdExamen,$lugar);
//$rowfecha = mysql_fetch_array($Consulta_fecha);
$fechares=$filares['fecha_resultado'];
$resultado=$filares['resultado'];
$marca=$filares['marca'];
$lectura=$filares['lectura'];
$interpretacion=$filares['interpretacion'];
$observacion=$filares['observacion'];
$responsable=$filares['idempleado'];
$nexamen=$filares['nombre_examen'];
 //echo 'emp:'.$responsable;
?>
<div  id="divImpresion" >
    <form name="frmimpresion" >
        <table width='100%' align='center' class='StormyWeatherFormTABLE' cellspacing="0" style='height: 350px;'>
            <tr>
                <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                <td align="center" colspan="4" width="60%" class="Estilo6">
                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                    <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                    <p><strong>&Aacute;REA DE <?php echo $nombre; ?> </strong></p>
		</td>
                <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
            </tr>
            <tr>
                <td colspan='6' align='center'>&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                <td colspan="1" class="Estilo5"><strong>Establecimiento:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo $row_estab2['nombre'];?></td>
		<td colspan="1" class="Estilo5"><strong>Fecha Recepción:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo $row['fecharecepcion'];?></td>
<!--                    <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row['FechaNacimiento']?>>-->
            </tr>
            <tr>
            	<td colspan="1" class="Estilo5"><strong>NEC:</strong></td>
		<td colspan="2" class="Estilo7"><?php echo $txtnec;?></td>
            	<td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo $fechares;?></td>
            </tr>
            <tr>
		<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo  $rowpa['nombre'];?></td>
            </tr>
            <tr>
		<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
		<td colspan="2" class="Estilo6">
                   <?php echo  $rowpa['edad'];?>
                </td>
		<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo  $rowpa['sexo'];?></td>
	     </tr>
             <tr>
		<td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo $proce?></td>
		<td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
		<td colspan="2" class="Estilo6"><?php echo $origen;?></td>
             </tr>
                            <?php
                                 
                                $consulta_empleado = $objdatos->BuscarEmpleadoValidador($responsable, $lugar);
                                $fila_empleado = pg_fetch_array($consulta_empleado);
                                $consulta2 = $objdatos->MostrarDatosFijosPlantillaA($idexamen, $lugar, $sexo, $idedad, 0);
                $fila = pg_fetch_array($consulta2);
                            ?>
             <tr>
		<td  colspan='1' class="Estilo5"><strong>Validado Por: </strong></td>
		<td  colspan='5' class="Estilo6"><?php echo $fila_empleado['empleado'];?></td>
             </tr>
             <tr>
                 <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
             </tr>
             <tr>
                  <td colspan='6' align='center' class="Estilo5"><strong><hr>DETALLE DE RESULTADOS</strong></td>
             </tr>
             <tr>
                <td colspan='6'>
                    <table width="100%"  align="center" border="0" cellspacing="0">
                        <tr >
                            <td align="left" class="Estilo5"><strong>Prueba Realizada</strong></td>
                            <td align="center" class="Estilo5"><strong>Resultado</strong></td>
                            <td align="center" class="Estilo5"><strong>Unidades</strong></td>
                            <td align="center" class="Estilo5"><strong>Rangos Normales</strong> </td>
                            <td align="justify" class="Estilo5"><strong>Marca</strong></td>
                            <td align="justify" class="Estilo5"><strong>Lectura</strong></td>
                            <td align="justify" class="Estilo5"><strong>Interpretación</strong></td>
                            <td align="justify" class="Estilo5"><strong>Observación</strong></td>
                        </tr>
                        <tr><td colspan="8"><hr></td><tr/>
                        <tr>
                            <td align="left" class="Estilo5"><?php echo $nexamen;?></td>
                            <td align="center" class="Estilo5"><?php echo $resultado?></td>
                     <?php if (!empty($fila['unidades'])){ ?>
                            <td align="center" class="Estilo5"><?php echo $fila['unidades'] ?></td>
                     <?php }else{?>
                            <td  align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <?php }  ?>
                            <td align="center" class="Estilo5"><?php echo isset($fila['rangoinicio']) ? $fila['rangoinicio'] : null." - ".isset($fila['rangorin']) ? $fila['rangofin'] : null?></td>
                     <?php if (!empty($marca)){ ?>
                            <td align="justify" class="Estilo5"><?php echo $marca?></td>
                     <?php }else{?>
                            <td  align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <?php }
                            if (!empty($lectura)){ ?>
                            <td align="justify" class="Estilo5"><?php echo $lectura?></td>
                     <?php }else{?>
                            <td  align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <?php }
                            if (!empty($lectura)){    ?>
                            <td align="justify" class="Estilo5"><?php echo $interpretacion?></td>
                     <?php }else{?>
                            <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <?php }
                            if (!empty($observacion)){ ?>
                            <td align="justify" class="Estilo5"><?php echo $observacion?></td>
                     <?php }else{?>
                            <td  align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                     <?php } ?>
                        </tr>
                        <?php
                     echo "<tr><td colspan='8'>&nbsp;</td></tr>";
          
         $met=$objdatos->buscarexamresult($iddetalle, $idsolicitud, $lugar, $idexamen, $sexo, $idedad);
         $cantmet=pg_num_rows($met);
         if ($cantmet>0){
            echo "<tr><td colspan=1><br><i>Metodologías:</i></td></tr>";
            while ($rowme=pg_fetch_array($met)){
                echo "<tr>
                        <td align='left' style='font:bold'>".$rowme['nombre_metodologia']."</td>
   <td align='center'>".$rowme['resultado']."</td>
                        <td align='center'>".$rowme['unidades']."</td>
                        <td align='justify'>".$rowme['rangoinicio']." - ".$rowme['rangofin']."</td>
                        <td align='justify'>".$rowme['marca']."</td>
                        <td align='justify'>".$rowme['lectura']."</td>
                        <td align='justify'></td>
                        <td align='justify'>".$rowme['observacion']."</td>
                    </tr>";
                }
         }
                        ?>
                       
                    </table>
                </td>
             </tr>
        </table>
        <div id="boton">
            <table align="center">
                <tr>
                    <td colspan="6" align="center" >
                        <br><br>
                        <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                        <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
                    </td>
                </tr>
            </table>

	</div>
    </form>
</div>
