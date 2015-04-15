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
$f_tomamuestra = $_GET['var14'];
$tipomuestra   = $_GET['var15'];
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

 .Estilo5 {font-family: Helvetica; font-size: 9pt}
    .Estilo6 {font-family: Helvetica; font-size: 8pt}
    .Estilo7 {font-family: Helvetica; font-size: 10pt}

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
            <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' cellspacing='0'>
		<tr>
			<td colspan="1" align="left" width="15%"><img id="Image1" style="width: auto; height: 55px;" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="70%" class="Estilo6">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                <p><strong><?php  echo  $row_estab['nombre'] ?></strong></p>
				<p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
			</td>
                        <td colspan="1" align="right" width="15%"><img id="Image3" style="width: auto; height: 55px;" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                        <td colspan="6">&nbsp;</td>
                </tr>
		<tr>
			<td colspan="1" class='Estilo6' width="20%"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan="3" class='Estilo6' width="60%" ><?php echo $row_generales['estabext']?></td>
			<td colspan="1" class='Estilo6' width="10%"><strong>Fecha Resultado:</strong></td>
			<td colspan="1" class='Estilo6' width="10%"><?php echo $row_fecha['fecharesultado']?></td>
				
		</tr>
		<tr>
			<td colspan="1" class='Estilo6'><strong>NEC</strong></td>
			<td colspan="3" class='Estilo7'><?php echo $row_generales['numero']?></td>
                        <td colspan="1" class='Estilo6'><strong>Fecha Recepción:</strong></td>
                        <td colspan="1" class='Estilo6'><?php echo $row_generales['fecharecep']?></td>
                </tr>
                <tr>
			<td colspan="1" class='Estilo6'><strong>Paciente:</strong></td>
			<td colspan="3" class='Estilo6' ><?php echo htmlentities($row_generales['paciente'])?></td>
                        <td colspan="1" class='Estilo6' ><strong>Fecha Toma Muestra:</strong></td>
                        <td colspan="1" class='Estilo6' ><?php echo $f_tomamuestra ?></td>
		</tr>
		<tr>
			<td colspan="1" class='Estilo6'><strong>Edad:</strong></td>
			<td colspan="3" class='Estilo6' align="left"><?php echo $row_generales['edad']?></td>
			<td colspan="1" class='Estilo6'><strong>Sexo:</strong></td>
			<td colspan="1" class='Estilo6' align="left"><?php echo $row_generales['sexo']?></td>
		</tr>

		<tr>
			<td colspan="1" class='Estilo6'><strong>Validado Por:</strong></td>
			<td colspan="5" class='Estilo6' align="left"><?php echo $responsable?></td>
		</tr>
                 <tr>
			<td colspan="1" class='Estilo6'><strong>Procedencia:</strong></td>
			<td colspan="3" class='Estilo6'><?php echo htmlentities($row_generales['procedencia'])?></td>

			<td colspan="1" class='Estilo6'><strong>Servicio:</strong></td>
			<td colspan="1" class='Estilo6' align="left"><?php echo htmlentities($row_generales['subservicio'])?></td>
		</tr>
		<tr>
			<td colspan="1" class='Estilo6'><strong>Examen Realizado:</strong></td>
			<td colspan="5" class='Estilo6' align="left"><?php echo htmlentities($row_area['nombre_reporta'])?></td>
		</tr>
		<tr>
			<td colspan="1" class='Estilo6'><strong>Observacion:<strong></td>
			<td colspan="5" class='Estilo6' align="left"><?php echo htmlentities($observacion)?></td>
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
			<table width="100%" border="0" align="center" class="StormyWeatherFormTABLE" >
                            <tr class='CobaltButton'>
				<td class="Estilo6"><strong> Prueba </strong></td>
				<td class="Estilo6"><strong> Resultado</strong> </td>
				<td class="Estilo6"><strong> Unidades</strong> </td>
				<td class="Estilo6" colspan='2' ><strong> Control Diario</strong> </td>
                            </tr>
				<?php	$pos=0;
				while($row = pg_fetch_array($consulta))//ELEMENTOS
				{?>
                            <tr>
				<td class="Estilo6"><?PHP echo htmlentities($row['nombreprocedimiento'])?></td>
				<td class="Estilo6" align="center">
						<input name='oidprueba[<?php $pos ?>]' type='hidden' id='oidprueba[<?php $pos ?>]' value='<?php $row['idprocedimientoporexamen'] ?>'><?php echo htmlentities($vector_respuesta[$pos])?></td>
				<td class="Estilo6"><?php echo $row['unidades']?></td>
				<td class="Estilo6" align='center'><?php echo htmlentities($vector_comentarios[$pos])?></td>
				<td class="Estilo6"><?php echo htmlentities($row['unidades'])?></td>
                            </tr>
				<?php $pos=$pos + 1;
				}
					pg_free_result($consulta);?>
                        </table>
                    </td>
		</tr>
                <tr>
                    <td colspan="6" class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6" class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="6" class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" class="Estilo6" width="72%">&nbsp;&nbsp;</td>
                    <td colspan="1" class="Estilo6" width="6%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELLO:</td>
                    <td colspan="1" class="Estilo6" width="6%">___________&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
                    <td colspan="1" class="Estilo6" width="6%">FIRMA:</td>
                    <td colspan="2" class="Estilo6" width="10%">________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
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
	<table width='100%' border='1' align='center'  cellspacing="0">
		<tr>
		    <td colspan="1" align="left" width="15%"><img id="Image1" style="width: auto; height: 55px;"  src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                    <td align="center" colspan="4" width="70%" class="Estilo5">
			<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                        <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                        <p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
		    </td>
                    <td colspan="1" align="right" width="15%"><img id="Image3" style="width: auto; height: 55px;"  src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                    <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
		<tr>
			<td colspan='1' class="Estilo5" width="30%" align="justify"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='3' class="Estilo6" width="35%" align="justify"><?php echo $row_generales['estabext']?></td>
			<td colspan='1' class="Estilo5" width="25%" align="justify"><strong>Fecha Resultado:</strong></td>
			<td colspan='1' class="Estilo6" width="10%" align="justify"><?php echo $row_fecha['fecharesultado']?></td>
				
		</tr>
		<tr>
                        <td colspan='1' class="Estilo5" align="justify"><strong>Expediente:</strong></td>
                        <td colspan='3' class="Estilo7" align="justify"><?php echo $row_generales['numero']?></td>
                        <td colspan='1' class="Estilo5" align="justify"><strong>Fecha Recepción:</strong></td>
                        <td colspan='1' class="Estilo6" align="justify"><?php echo $row_generales['fecharecep']?></td>
                </tr>
                <tr>
                        <td colspan='1' class="Estilo5" align="justify"><strong>Paciente:</strong></td>
                        <td colspan='3' class="Estilo6" align="justify"><?php echo htmlentities($row_generales['paciente'])?></td>
                        <td colspan='1' class="Estilo5" align="justify"><strong>Fecha Toma Muestra:</strong></td>
                        <td colspan='1' class="Estilo6" align="justify"><?php echo $f_tomamuestra ?></td>
                </tr>
                
		<tr>
			<td colspan='1' class="Estilo5" align="justify"><strong>Edad:</strong></td>
			<td colspan='3' class="Estilo6" align="justify"><?php echo $row_generales['edad']?></td>
			<td colspan='1' class="Estilo5" align="justify"><strong>Sexo:</strong></td>
			<td colspan='1' class="Estilo6" align="justify"><?php echo $row_generales['sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="justify"><strong>Procedencia:</strong></td>
			<td colspan='3' class="Estilo6" aalign="justify"><?php echo $row_generales['procedencia']?></td>
			<td colspan='1' class="Estilo5" align="justify"><strong>Servicio:</strong></td>
			<td colspan='1' class="Estilo6" align="justify"><?php echo $row_generales['subservicio']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="justify"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6" align="justify"><?php echo htmlentities($row_area['nombre_reporta'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="justify"><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6" align="justify"><?php echo $responsable?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="justify"><strong>Observacion:</strong></td>
			<td colspan='5' class="Estilo6" align="justify"><?php echo $observacion?></td>
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

                    <table width='95%' border='0' align='left' Cellpadding="0"  cellspacing="0">
				<tr >
                                        <td class="Estilo5" width='30%' align="justify"><strong> Prueba </strong> </td>
					<td class="Estilo5" width='30%' align="justify"><strong> Resultado </strong></td>
					<td class="Estilo5" width='25%' align="justify"><strong> Unidades </strong></td>
					<td class="Estilo5" width='30%' align="justify"><strong> Rango </strong></td>
				</tr>
                                <tr><td colspan='6'><hr></td></tr>
				<?php $pos=0;
			while($row = pg_fetch_array($consulta))//ELEMENTOS
					{  ?>
				<tr>
                            <?php   if($vector_combos[$pos]== NULL){  ?>
					<td class="Estilo6" width="25%" align="justify"><?php echo htmlentities($row['nombreprocedimiento'])?></td>
                                        <td class="Estilo6" width="30%" align="justify" colspan='1'><?php echo htmlentities($vector_respuesta[$pos])?></td>
                            <?php   }else {
                                      //  echo $pos;
                                        $conresult=$obj->BuscarResultado($vector_respuesta[$pos]);
                                        $row_dresult=  pg_fetch_array($conresult);?>
                                        <td class="Estilo6" width="25%" align="justify"><?php echo htmlentities($row['nombreprocedimiento'])?></td>
                                        <td class="Estilo6" width="30%" align="justify"><?php echo htmlentities($row_dresult['posible_resultado'])?></td>
                                  
                         <?php      }?>            
				<!--	<td class="Estilo5" width='30%'align='center'><input name='oidprueba[<?php $pos?>]' type='hidden' id='oidprueba[<?php $pos?>]' value='<?php $row['id']?>'><?php //echo htmlentities($vector_respuesta[$pos])?></td>-->
					<td class="Estilo6" width="20%" align="justify"><?php echo htmlentities($row['unidades'])?></td>

					<td class="Estilo6" align="left" width="30%"><?php
                                                     if((!empty($row['rangoinicio'])) AND (!empty($row['rangoinicio'])))
                                                           echo $row['rangoinicio']."-".$row['rangofin'];?></td>
				</tr>
					<?php  $pos=$pos + 1;
			}
			pg_free_result($consulta);?>
                          <tr><td colspan='6'><hr></td></tr>      
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
