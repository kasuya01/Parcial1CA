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
$idestandar=$_GET['var9'];
$subservicio=$_GET['var8'];
$idexamen=$_GET['var6'];
//echo "examen= ".$idexamen;

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

  var suEdades=calcular_edad(fecnac2);

  document.getElementById("divsuedad").innerHTML=suEdades;
}

</script>
<style type="text/css">
<!--
@media print{
#boton{display:none;}
}
@media print{
#boton1{display:none;}
}

.Estilo5 {font-family: Helvetica; font-size: 9pt}
.Estilo6 {font-family: Helvetica; font-size: 8pt}
.Estilo7 {font-family: Helvetica; font-size: 10pt}
-->
</style>
</head>

<body>
<?php
include_once("clsImprimirResultado.php");
//creando los objetos de las clases
$obj = new clsImprimirResultado;

	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = pg_fetch_array($Consulta_Estab);
        
	if ($idestandar=="H15"){
          //  echo "dentro del if";
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
		$row_generales= pg_fetch_array($datos_generales);
		$row_area = pg_fetch_array($consulta_datos);
                 $FechaRes       = $obj->ObtenerFechaResultado($idsolicitud,$iddetalle,$lugar);
                $row_fecha      = pg_fetch_array($FechaRes);
	?>
            <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
		<tr>
			<td colspan="1" align="left" width="15%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="70%" class="Estilo6">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                <p><strong><?php  echo                        $row_estab['nombre'] ?></strong></p>
				<p><strong>Area de <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
			</td>
                        <td colspan="1" align="right" width="15%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
                </tr>
            </table>
           
            <table width='100%' border='0' align='center'  cellpadding="0%"  cellspacing="0%">    
                    <tr>
                        <td colspan='6'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan='1' class="Estilo5" width='28%'align="left"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='3' class="Estilo6" width='37%' align="left"><?php echo htmlentities($row_generales['estabext']) ?></td>
                        <td colspan='1' class="Estilo5" width='19%' align="left"><strong>Fecha Resultado:</strong></td>
			<td colspan='1' class="Estilo6" width='16%' align="left"><?php echo $row_fecha['fecharesultado']?></td>
                    </tr>
                    <tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Expediente</strong</td>
			<td colspan='3' class="Estilo7" align="left"><?php echo $row_generales['idnumeroexp']?></td>
                        <td colspan="1" class="Estilo5" align="left"><strong>Fecha Recepción:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['fecharecepcion']?></td>
                    </tr>
                    <tr>        
                        <td colspan='1' class="Estilo5" align="left"><strong>Paciente:</strong></td>
			<td colspan='3' class="Estilo6" align="left"><?php echo htmlentities($row_generales['paciente'])?></td>
                        <td colspan="1" class="Estilo5" align="left"><strong>Fecha Toma Muestra:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['f_tomamuestra']?></td>   
                    </tr>
                    <tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Edad:</strong></td>
			<td colspan='3' class="Estilo6" align="left"><?php echo $row_generales['edad']?></td>
                        <td colspan='1' class="Estilo5" align="left"><strong>Sexo:</strong></td>
			<td colspan='1' class="Estilo6" align="left"><?php echo $row_generales['sexo']?></td>
                    </tr>
                    <tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Procedencia:</strong></td>
			<td colspan='3' class="Estilo6" align="left"><?php echo htmlentities($row_generales['nombresubservicio'])?></td>

			<td colspan='1' class="Estilo5" align="left"><strong>Servicio:</strong></td>
			<td colspan='1' class="Estilo6" align="left"><?php echo htmlentities($subservicio)?></td>
                    </tr>
                    <tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Validado Por:</strong></td>
			<td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($row_generales['empleado'])?></td>
                    </tr>
                    <tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($row_area['nombre_examen'])?></td>
                    </tr>
		
			<?php pg_free_result($consulta_datos);
			      pg_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idsolicitud,$iddetalle);
			?>
                    <tr>
                        <td colspan='6'>
                            <table width="100%" border="0" align="center" cellpadding="0%" cellspacing="0%" >
                                <tr class='CobaltButton'>
                                    <td class="Estilo6" width='25%' align="justify"><strong> Prueba </strong></td>
                                    <td class="Estilo6" width='15%' align="justify"><strong> Resultado</strong> </td>
                                    <td class="Estilo6" width='10%' align="center"><strong> Unidades</strong> </td>
                                    <td class="Estilo6" width='20%' align="justify"><strong> Control Diario</strong> </td>
                                    <td width="30%" class="Estilo6">&nbsp;</td>
                                </tr>
                                <tr><td colspan='6'><hr></td></tr>    

                                            <?php	$pos=0;

                                    while($row = pg_fetch_array($consulta))//ELEMENTOS
                                            {  ?>
                                <tr>
                                    <td class="Estilo5" width='25%' align='justify'><?PHP echo htmlentities($row['nombreprocedimiento'])?></td>
                                    <td class="Estilo5" width='15%' align='justify' ><?php echo htmlentities($row['resultado'])?>
                                        <input name='oidprueba[<?php $pos ?>]' type='hidden' id='oidprueba[<?php $pos ?>]' value='<?php $row['idprocedimiento'] ?>'></td>
                                    <td class="Estilo5" width='10%' align='center'><?php echo $row['unidades']?></td>
                                    <td class="Estilo5" width='20%' align='justify'><?php echo htmlentities($row['comentario'])." ".htmlentities($row['unidades'])?></td>	

                                </tr>

                                            <?php $pos=$pos + 1;

                                            }
                                                    pg_free_result($consulta);?>
                            </table>
                        </td>
                    </tr>
                 <tr><td colspan='6'><hr></td></tr>
                <tr>
                                <td colspan='1' class="Estilo5"><strong>Observacion:<strong></td>
                                <td colspan='5' class="Estilo6" align='justify'><?php echo htmlentities($row_generales['observacion'])?></td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr><td colspan="6" class="Estilo6" align='right'>
                   <br><br>
                   Sello: _______________________ &nbsp;&nbsp;&nbsp;     Firma: _______________________
                </td></tr>
            </table>
            <div id="boton">
                <table align="center">
                    <tr>
                        <td colspan="7" align="center" >
                            <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                            <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
                        </td>
	            </tr>
                </table>
            </div> 
        

	<?php
	}else{
            
            //echo "dentro del else";
	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);

	$row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);
        $FechaRes  = $obj->ObtenerFechaResultado($idsolicitud,$iddetalle,$lugar);
        $row_fecha = pg_fetch_array($FechaRes);

	?>
	<table width='100%' border='0' align='center' cellspacing="0">
		<tr>
			<td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                        <td align="center" colspan="4" width="60%" class="Estilo6">
				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				<p><strong><?php  echo  $row_estab['nombre'] ?></strong></p>
				<p><strong>Area de <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
			</td>
                         <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
                    <td colspan='6'>&nbsp;</td>
                </tr>
        </table>
                <table width='100%' border='0' align='center' cellspacing="0">
		<tr>
			<td colspan='1' class="Estilo5" width="28%" align="left"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='3' class="Estilo6" width="42%" align="left"><?php echo htmlentities($row_generales['estabext']) ?></td>
			<td colspan='1' class="Estilo5" width="18%" align="left"><strong>Fecha Resultado:</strong></td>
			<td colspan='1' class="Estilo6" width="14%" align="left"><?php echo $row_fecha['fecharesultado']?></td>
		</tr>

		<tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Expediente:</strong></td>
			<td colspan='3' class="Estilo7" align="left"><?php echo $row_generales['idnumeroexp'] ?></td>
                        <td colspan="1" class="Estilo5" align="left"><strong>Fecha Recepción:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['fecharecepcion']?></td>
                </tr>
                <tr>

			<td colspan='1' class="Estilo5" align="left"><strong>Paciente:</strong></td>
			<td colspan='3' class="Estilo6" align="left"><?php echo htmlentities($row_generales['paciente'])?></td>
                        <td colspan="1" class="Estilo5" align="left"><strong>Fecha Toma Muestra:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['f_tomamuestra']?></td>   

		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Edad:</strong></td>
			<td colspan='3' class="Estilo6" align="left"><?php echo $row_generales['edad']?></td>

			<td colspan='1' class="Estilo5" align="left"><strong>Sexo:</strong></td>
			<td colspan='1' class="Estilo6" align="left"><?php echo $row_generales['sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Procedencia:</strong></td>
			<td colspan='3' class="Estilo6" align="left"><?php echo $row_generales['nombresubservicio']?></td>

			<td colspan='1' class="Estilo5" align="left"><strong>Servicio:</strong></td>
			<td colspan='1' class="Estilo6" align="left"><?php echo $subservicio?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Examen Realizado:</strong></td>
			<td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($row_area['nombre_examen'])?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5" align="left"><strong>Validado Por:</strong></td>
			<td colspan='4' class="Estilo6" align="left"><?php echo htmlentities($row_generales['empleado'])?></td>
		</tr>
		
                <tr>
                    <td colspan='6'>&nbsp;</td>
                </tr>
		<tr>
                    <td colspan='6'>
			<?php pg_free_result($consulta_datos);
			      pg_free_result($datos_generales);
			      $consulta=$obj->LeerProcesoExamen($idsolicitud,$iddetalle);

	        ?>
			<table width='100%' border='0' align='center' cellspacing="0">
				<tr >
                                    <td class="Estilo5" width="25%" align="justify"><strong>Prueba</strong>  </td>
                                    <td class="Estilo5" width="30%" align="justify"><strong>Resultado</strong></td>
                                    <td class="Estilo5" width="20%" align="justify"><strong>Unidades</strong></td>
                                    <td class="Estilo5" width="25%" align="justify"><strong>Rango </strong></td>
				</tr>
                                 <tr><td colspan='6'><hr></td></tr>  
					<?php $pos=0;
				while($row = pg_fetch_array($consulta))//ELEMENTOS
					{  ?>
				<tr>
					<td width='25%' align='left' class="Estilo5"><?php echo htmlentities($row['nombreprocedimiento'])?></td>
					
                                    <?php if(!empty($row['resultado'])){ ?>    
                                         <td width='30%' align='justify' class="Estilo5"><?php echo htmlentities($row['resultado'])?></td>
                                    <?php }else{ ?>
                                         <td width='30%' class="Estilo6" align='justify'><?php echo htmlentities($row['posible_resultado'])?></td>
                                      <?php } ?>        
					<td width='20%' align='center' class="Estilo5"><?php echo htmlentities($row['unidades'])?></td>
				      <?php 	
                                    if((!empty($row['rangoinicio'])) AND (!empty($row['rangoinicio']))){?>
                                        <td width='25%' align='center' class="Estilo5"align='center'><?php echo $row['rangoinicio']." - ".$row['rangofin']?></td>
                                    <?php }else{ ?>    
                                        <td width='25%' align='center' class="Estilo5"align='center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                    <?php } ?>         
                                </tr>
					<?php  $pos=$pos + 1;
					}
					pg_free_result($consulta);?>
                                </tr>
                                 <tr><td colspan='6'><hr></td></tr>  
                            </table>
                        </td>
                    </tr>
                    <tr>
			<td colspan='1' class="Estilo5" ><strong>Observación:</strong></td>
			<td colspan='4' class="Estilo6" align='justify'><?php echo htmlentities($row_generales['observacion'])?></td>
                    </tr>
                    <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class="Estilo6">&nbsp;</td>
                </tr>
                <tr><td colspan="6" class="Estilo6" align='right'>
                   <br><br>
                   Sello: _______________________ &nbsp;&nbsp;&nbsp;     Firma: _______________________
                </td></tr>
                </table>
                <div id="boton">
                     <table align="center">
                        <tr>
                            <td colspan="7" align="center" >
                                <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
                            </td>
			</tr>
                     </table>
                </div>

		<?php }?>
