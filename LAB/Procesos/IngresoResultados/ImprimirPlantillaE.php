<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
$responsable=$_GET['var3'];
$procedencia=$_GET['var4'];
$origen=$_GET['var5'];
$comentarios=$_GET['var6'];
$valores=$_GET['var7'];
$codigos=$_GET['var8'];
$observacion=$_GET['var9'];
$establecimiento=$_GET['var10'];
$sexo=$_GET['var11'];
$idedad=$_GET['var12'];
$valores_combos= $_GET['var13'];
//echo $idsolicitud."Examen= ".$idexamen."Empleado= ".$responsable." procedencia= ".$procedencia."oreigen= ".$origen." comentario= ".$comentarios." valores= ".$valores."codigos= ".$codigos."observacio= ".$observacion."establecimiento= ".$establecimiento."sexo= ".$sexo." idedad= ".$idedad;
//echo $responsable;

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="../SolicitudesPendientes/ajax_SolicitudesProcesadas.js"></script>
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
#boton1{display:none;}
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
include_once("clsPlantillaE.php");
//creando los objetos de las clases
$obj = new clsPlantillaE;
$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
$row_estab = pg_fetch_array($Consulta_Estab);
$ConEstandar=$obj->Obtener_Estandar($idexamen);
$CodEstandar= pg_fetch_array($ConEstandar);
$IdEstandar=$CodEstandar[0];
$FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
$row_fecha=pg_fetch_array($FechaRes);
//echo $row_fecha['FechaResultado'];
if ($IdEstandar=="H15"){

	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	$row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);
	$vector_idprocesos=EXPLODE("/",$codigos);
	$vector_respuesta=EXPLODE("/",$valores);
	$vector_comentarios=EXPLODE("/",$comentarios);?>
        $vector_combos    = EXPLODE("/",$valores_combos);
            <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="width: auto; height: 55px;" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="60%" class="Estilo6">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                <p><strong><?php  echo  $row_estab['nombre'] ?></strong></p>
				<p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
			</td>
                        <td colspan="1" align="right" width="20%"><img id="Image3" style="width: auto; height: 55px;" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                        <td colspan='6'>&nbsp;</td>
                </tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['estabext']?></td>
			<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
				
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>NEC</strong></td>
			<td colspan='2' class="Estilo7"><?php echo $row_generales['numero']?></td>
                        <td colspan='1' class="Estilo5" ><strong>Fecha Recepción:</strong></td>
                        <td colspan='2' class="Estilo6" ><?php echo $row_generales['fecharecep']?></td>
                </tr>
                <tr>
			<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['edad']?></td>
			<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['sexo']?></td>
		</tr>

		<tr>
			<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo $responsable?></td>
		</tr>
                 <tr>
			<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['procedencia'])?></td>

			<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['subservicio'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['nombre_reporta'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Observacion:<strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($observacion)?></td>
		</tr>
			<?php pg_free_result($consulta_datos);
                              pg_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad);
			?>
		<tr>
			<td colspan="6" align="center"></td>
		</tr>
                <tr>
                    <td colspan="6">
			<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE' >
                            <tr class='CobaltButton'>
				<td class="Estilo5"><strong> Prueba </strong></td>
				<td class="Estilo5"><strong> Resultado</strong> </td>
				<td class="Estilo5"><strong> Unidades</strong> </td>
				<td class="Estilo5" colspan='2' ><strong> Control Diario</strong> </td>
                            </tr>
				<?php	$pos=0;
				while($row = pg_fetch_array($consulta))//ELEMENTOS
				{?>
                            <tr>
				<td class="Estilo5"><?PHP echo htmlentities($row['nombreprocedimiento'])?></td>
				<td class="Estilo5" align='center'>
						<input name='oidprueba[<?php $pos ?>]' type='hidden' id='oidprueba[<?php $pos ?>]' value='<?php $row['idprocedimientoporexamen'] ?>'><?php echo htmlentities($vector_respuesta[$pos])?></td>
				<td class="Estilo5"><?php echo $row['unidades']?></td>
				<td class="Estilo5" align='center'><?php echo htmlentities($vector_comentarios[$pos])?></td>
				<td class="Estilo5"><?php echo htmlentities($row['unidades'])?></td>
                            </tr>
				<?php $pos=$pos + 1;
				}
					pg_free_result($consulta);?>
                        </table>
                    </td>
		</tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2' class="Estilo6" width='72%'>&nbsp;&nbsp;</td>
                    <td colspan='1' class="Estilo6" width='6%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELLO:</td>
                    <td colspan='1' class="Estilo6" width='6%'>___________&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
                    <td colspan='1' class="Estilo6" width='6%'>FIRMA:</td>
                    <td colspan='2' class="Estilo6"width='10%'>________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
                </tr>
		<tr>
                    <td colspan="7" align="center" >
                        <div id="boton1">
                            <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                            <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
			</div>
                    </td>
		</tr>
	    </table>

<?php
}else{
	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	//$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);

        $Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
        $row_estab = pg_fetch_array($Consulta_Estab);
	//$row_empleado = mysql_fetch_array($datos_empleado);
	$vector_idprocesos=EXPLODE("/",$codigos);
	$vector_respuesta=EXPLODE("/",$valores);
        $vector_combos    = EXPLODE("/",$valores_combos);
	//echo $responsable;
	?>
	<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="width: auto; height: 55px;"  src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="60%" class="Estilo5">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                                <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
				<p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
			</td>
                        <td colspan="1" align="right" width="20%"><img id="Image3" style="width: auto; height: 55px;"  src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                 <tr>
                        <td colspan='6'>&nbsp;</td>
                </tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Establecimiednto Solicitante:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['estabext']?></td>
			<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
				
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>NEC</strong></td>
			<td colspan='2' class="Estilo7"><?php echo $row_generales['numero'] ?></td>
                        <td colspan='1' class="Estilo5" ><strong>Fecha Recepción:</strong></td>
                        <td colspan='2' class="Estilo6" ><?php echo $row_generales['fecharecep']?></td>
                </tr>
                <tr>
			<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['paciente']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['edad']?></td>
			<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['procedencia']?></td>
			<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['subservicio']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['nombre_reporta'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo $responsable?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Observacion:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo $observacion?></td>
		</tr>
                <tr>
			<td colspan='6' align="center"></td>
		</tr>
		<tr>
		<?php   pg_free_result($consulta_datos);
			pg_free_result($datos_generales);
			$consulta=$obj->LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad);
                      //  print_r($vector_respuesta);
	        ?>

                <tr><td colspan='6'>

                    <table width='90%' border='0' align='left' cellspacing="0">
				<tr >
                                        <td class="Estilo5" width='25%' align='left'  ><strong> Prueba </strong> </td>
					<td class="Estilo5" width='30%' align='left'><strong> Resultado </strong></td>
					<td class="Estilo5" width='20%' align='center'><strong> Unidades </strong></td>
					<td class="Estilo5" width='30%' align='center'><strong> Rango </strong></td>
				</tr>
				<?php $pos=0;
			while($row = pg_fetch_array($consulta))//ELEMENTOS
					{  ?>
				<tr>
                            <?php   if($vector_combos[$pos]== NULL){  ?>
					<td class="Estilo5" width='25%'align='left'><?php echo htmlentities($row['nombreprocedimiento'])?></td>
                                        <td align='center' colspan='1'><?php echo htmlentities($vector_respuesta[$pos])?></td>
                            <?php   }else {
                                      //  echo $pos;
                                        $conresult=$obj->BuscarResultado($vector_respuesta[$pos]);
                                        $row_dresult=  pg_fetch_array($conresult);?>
                                        <td class="Estilo5" width='25%'align='left'><?php echo htmlentities($row['nombreprocedimiento'])?></td>
                                        <td class="Estilo5" width='25%'align="left"><?php echo htmlentities($row_dresult['posible_resultado'])?></td>
                                  
                         <?php      }?>            
				<!--	<td class="Estilo5" width='30%'align='center'><input name='oidprueba[<?php $pos?>]' type='hidden' id='oidprueba[<?php $pos?>]' value='<?php $row['id']?>'><?php //echo htmlentities($vector_respuesta[$pos])?></td>-->
					<td class="Estilo5" width='20%'align='left'><?php echo htmlentities($row['unidades'])?></td>

					<td class="Estilo5" align='left' width='30%'><?php
                                                     if((!empty($row['rangoinicio'])) AND (!empty($row['rangoinicio'])))
                                                           echo $row['rangoinicio']."-".$row['rangofin'];?></td>
				</tr>
					<?php  $pos=$pos + 1;
			}
			pg_free_result($consulta);?>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2' class="Estilo6" width='72%'>&nbsp;&nbsp;</td>
                    <td colspan='1' class="Estilo6" width='6%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELLO:</td>
                    <td colspan='1' class="Estilo6" width='6%'>___________&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
                    <td colspan='1' class="Estilo6" width='6%'>FIRMA:</td>
                    <td colspan='2' class="Estilo6"width='10%'>________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
                </tr>
	     </table>
         <div id="boton">
                    <table align="center">
                        <tr>
                            <td colspan="6" align="center" >
                                <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" />
                            </td>
                        </tr>
                    </table>
                </div>

<?php }?>
