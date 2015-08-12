<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include_once("clsImprimirResultado.php");
$obj = new clsImprimirResultado;


//Datos Recibidos
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$idexamen=$_GET['var6'];
$subservicio=$_GET['var7'];
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
}</script>

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
$Catidad=$obj->ObtenerCantidadResultados($idsolicitud,$iddetalle);
$row_cantidad=pg_fetch_array($Catidad);

    // $row_cantidad[0];

echo "SOL= ".$idsolicitud." DET=".$iddetalle;
	if ($row_cantidad[0]==1){
          //  echo "--->> 1";
		$tipo=$obj->ObtenerResultado($idsolicitud,$iddetalle);
		$row_tipo=pg_fetch_array($tipo);
		$Resultado=$row_tipo['resultado'];
                echo $Resultado[0];

		If($Resultado[0]=='Positivo'){
                   // echo "dentro del if =P";
                    
                   // echo " if";
			$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
			$row_estab = pg_fetch_array($Consulta_Estab);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
			$row_generales= pg_fetch_array($datos_generales);
			$consulta_area=$obj->LeerDatos($idexamen);
			$row_area= pg_fetch_array($consulta_area);
			$consulta_datos=$obj->DatosResultadoPlanCPositivo($idsolicitud,$iddetalle);
			$row_datos= pg_fetch_array($consulta_datos);
			$consulta=$obj->LeerResultadosAntibioticos($idsolicitud,$iddetalle);
                        $FechaRes = $obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
                        $row_fecha = pg_fetch_array($FechaRes);                                        

		  	$ban=0;

			$nombre=$row_area['nombrearea'];
			?>
			<table width='100%' border='0' align='center' cellspacing="0">
                            <tr>
                                <td colspan="1" align="left" width="20%"><img id="Image1" style="width: auto; height: 55px;"  src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                <td align="center" colspan="4" width="60%" class="Estilo6">
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			            <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                                    <p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></td>
                                <td colspan="1" align="right" width="20%"><img id="Image3" style="width: auto; height: 55px;" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Est. Solicitante:</strong></td>
					<td colspan='3' class="Estilo6"><?php echo $row_generales['estabext']?></td>
					<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			  		<td colspan='1' class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
				</tr>

				<tr>
					<td colspan='1' class="Estilo5"><strong>Expediente:</strong></td>
			  		<td colspan='3' class="Estilo7"><?php echo $row_generales['idnumeroexp']?></td>
                                        <td colspan='1' class="Estilo5" ><strong>Fecha Recepción:</strong></td>
                                        <td colspan='1' class="Estilo6" ><?php echo $row_generales['fecharecepcion']?></td>
                                </tr>
                                <tr>
					<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
					<td colspan='3' class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
						
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			  		<td colspan='3' class="Estilo6"><?php echo htmlentities($row_generales['edad'])?></td>
					</td>

					<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			  		<td colspan='1' class="Estilo6"><?php echo $row_generales['sexo'];?></td>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
					<td colspan='3' class="Estilo6"><?php echo htmlentities($row_generales['nombreservicio']);?></td>

					<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
					<td colspan='1' class="Estilo6" ><?php echo htmlentities($subservicio);?></td>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			  		<td colspan='2' class="Estilo6"><?php echo htmlentities($row_area['nombre_examen']);?></td>
				</tr>
                                <tr>
					<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
					<td colspan='2' class="Estilo6"><?php echo $row_generales['empleado'];?></td>
				</tr>
                                <tr>
                                        <td >&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <table width="75%" border="0" align="left" cellspacing="0" >
                                        <tr>
                                                <td colspan='1' class="Estilo5"><strong>Resultado:</strong></td>
                                                <td colspan='2' class="Estilo6"><?php echo "Positivo"?></td>
                                        </tr>
                                        <tr>
                                                <td colspan='1' class="Estilo5" ><strong>Organismo:</strong></td>
                                                <td colspan='2' class="Estilo6"><?php echo htmlentities($row_datos['bacteria']); ?></td>
                                        </tr>
                                        <tr>
                                                <td class="Estilo5" colspan="1"><strong>Cultivo con Cuenta de Colonias:</strong></td>
                                                <td class="Estilo5" colspan="2"><?php echo htmlentities($row_datos['cantidad'])?></td>

                                        <tr>
                                                <td colspan='3'>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td colspan='5'>
                                                <table width='100%' border='0' align='left' class='StormyWeatherFormTABLE'>
                                                        <tr>
                                                            <td class="Estilo5" colspan="1"><strong>ANTIBIOTICO</strong></td>
                                                            <td class="Estilo5" colspan="1"><strong>LECTURA</strong></td>
                                                            <td class="Estilo5" colspan="2"><strong>INTERPRETACI&Oacute;N</strong>
                                                            </td>
                                                        </tr>
                                                                    <?php	pg_free_result($datos_generales);
                                                                            $pos=0;

                                                    while($row = pg_fetch_array($consulta))//ELEMENTOS)
                                                            {?>
                                                        <tr>
                                                            <td colspan="1" class="Estilo5"><?php echo htmlentities($row['antibiotico'])?></td>
                                                            <td colspan="1" class="Estilo5"><?php echo htmlentities($row['valor'])?></td>
                                                            <td colspan="2" class="Estilo5"><?php echo htmlentities($row['posible_resultado'])?> </td>
                                                        </tr>
                                                            <?php
                                                            }
                                                            pg_free_result($consulta);?>
                                                        <tr>
                                                            <td colspan="7" align="center" >
                                                                <div id="boton">
                                                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;"/>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>


		<?php
		}else{
                    //echo "dentro else";
                    //$total= $row_cantidad[0];
                    // $cont=1;
                    // echo $idsolicitud."-".$iddetalle."-".$lugar;
			$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
			$row_estab = pg_fetch_array($Consulta_Estab);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
					//$datos_empleado=$obj->DatosEmpleado($idempleado);
			$consulta_datos=$obj->LeerDatos($idexamen);
			$row_area= pg_fetch_array($consulta_datos);
			$row_generales= pg_fetch_array($datos_generales);
		        $Observ= $obj->ObtenerObservacion($idsolicitud,$iddetalle);
		        $row_observ= pg_fetch_array($Observ);
		        $Observacion=$row_observ['observacion'];
                        $FechaRes = $obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
                        $row_fecha = pg_fetch_array($FechaRes);
			//$nombre=$row_area['NombreArea'];
			?>


                                <div  id="divImpresion" >
                                    <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
                                        <tr>
                                            <td colspan="1" align="left" width="20%"><img id="Image1" style="width: auto; height: 55px;" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                            <td align="center" colspan="4" width="60%" class="Estilo6">
                                                <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                                <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
						<p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
                                            </td>
                                            <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
					</tr>
                                        <tr>
                                            <td colspan="6">&nbsp;</td>
                                        </tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo $row_generales['estabext']?></td>
                                            <td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
						
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Expediente:</strong></td>
                                            <td colspan="2" class="Estilo7"><?php echo $row_generales['idnumeroexp']?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['edad'])?></td>
                                            </td>

                                            <td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo $row_generales['sexo']?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
                                            <td colspan="2"class="Estilo6"><?php echo htmlentities($row_generales['nombreservicio'])?></td>

                                            <td colspan="1" class="Estilo5"><strong>Origen:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo htmlentities($subservicio)?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Examen Realizado:</strong></td>
                                            <td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['nombre_examen'])?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Validado Por:</strong></td>
                                            <td colspan='5' class="Estilo6"><?php echo $row_generales['empleado']; ?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Resultado:</strong></td>
						<?php 	pg_free_result($consulta_datos);
							pg_free_result($datos_generales);
                                                            //echo "eeerr".$Resultado;
							switch($Resultado)
                                                        
                                                        
							{
								case "N";?>
									<td colspan='5' class="Estilo6">NEGATIVO</td>
							  <?php break;
								case "O";?>
									<td colspan='5' class="Estilo6">--</td>
							  <?php break;
							}?>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Observaci&oacute;n:</strong></td>
                                            <td colspan="4" class="Estilo6"><?php echo htmlentities($Observacion)?></td>
					</tr>
					<tr>
                                            <td colspan='5'>&nbsp;</td>
					</tr>
                                    </table>
				</div>
                                <div id="boton">
                                    <table align="center">
                                        <tr class="6">
                                            <td>
                                                <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar() ;" />
                                            </td>
                                        </tr>
                                    </table>
                                </div>



		<?php }
	}
	else{
        //Mas de un resultado
        //
        //echo "mas de un resulrado";
		// echo $row_cantidad[0];
		 $tipo=$obj->ObtenerResultadoxId($idsolicitud,$iddetalle);
		 while( $row_tipo=pg_fetch_array($tipo)){
			If($row_tipo[1]=='Positivo'){
			//echo $row_tipo[0];
                            $row_tipo[0];
                            //$Conmi="and t26.id="+$row_tipo[0];
				$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
				$row_estab = pg_fetch_array($Consulta_Estab);
				$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
				$row_generales= pg_fetch_array($datos_generales);
				$consulta_area=$obj->LeerDatos($idexamen);
				$row_area= pg_fetch_array($consulta_area);
				//echo $row_tipo[0]."***".$idsolicitud."***".$iddetalle;
				$consulta_datos=$obj->DatosResultadoPlanCPositivoxId($idsolicitud,$iddetalle,$row_tipo[0]);
				$row_datos= pg_fetch_array($consulta_datos);
				//echo $row_datos[0];
				$consulta=$obj->LeerResultadosAntibioticosxId($idsolicitud,$iddetalle);

				$posele=0;
		  	$ban=0;

			//$nombre=$row_area['nombre_area'];
			?>
			<table width="100%" border="0" align="center" class="StormyWeatherFormTABLE">
                            <tr>
                                <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                <td align="center" colspan="4" width="60%" class="Estilo6">
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			            <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                                    <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></td>
                                <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Establecimiento:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo $row_generales['estabext']?></td>
				<td colspan="1" class="Estilo5"><strong>Fecha:</strong></td>
                                <td colspan="2" class="Estilo6"><?php echo $row_generales['fecharecepcion']?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Expediente:</strong></td>
                                <td colspan="2" class="Estilo6"><?php echo $row_generales['idnumeroexp']?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
					<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['edad']?>>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
                               <td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['edad'])?></td>
                                    
				</td>
				<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
			  	<td colspan="2" class="Estilo6"><?php echo $row_generales['sexo'];?></td>
                            </tr>
                            <tr>
                                <td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
				<td colspan="2" class="Estilo6" width='38%'><?php echo htmlentities($row_generales['nombreservicio']);?></td>
				<td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
				<td colspan="2" class="Estilo6" width='6%'><?php echo htmlentities($row_generales['nombresubservicio']);?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Examen Realizado:</strong></td>
                                <td colspan="5" class="Estilo6"><?php echo htmlentities($row_area['nombre_examen']);?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Resultado:</strong></td>
				<td colspan="5" class="Estilo6"><?php echo "Positivo"?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Validado Por:</strong></td>
				<td colspan="5" class="Estilo6"><?php echo $row_generales['empleado'];?></td>
                            </tr>
                            <tr>
                                <td colspan="6">&nbsp;</td>
                            </tr>

                            <tr>
				<td colspan="6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <table width="75%" border="0" align="left" cellspacing="0" >
                                        <tr>
                                            <td colspan="1" class="Estilo5"><strong>Resultado:</strong></td>
                                            <td colspan="5" class="Estilo6"><?php echo "Positivo"?></td>
                                        </tr>
                                            <?php	pg_free_result($datos_generales);
							$pos=0;

						while($row = pg_fetch_array($consulta))//ELEMENTOS)
						{?>
                                        
                                        <tr>
                                            <td colspan="1" class="Estilo5" >Organismo:</td>
                                            <td colspan="5" class="Estilo6"><?php echo htmlentities($row['bacteria']); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="Estilo5">CULTIVO CON CUENTA DE COLONIAS</td>
                                            <td colspan="5" class="Estilo5"><?php echo htmlentities($row['cantidad'])?></td>
					</tr>
                                        <tr>
                                            <td colspan='6'>&nbsp;</td>
                                        </tr>
                                         <?php
						}

						pg_free_result($consulta);?>
                                        <tr>
                                             <td colspan='6'>
                                                <table width="100%" border="0" align="left" cellspacing="0" >
                                                    <tr>
                                                        <td colspan="1" class="Estilo5" ><strong>ANTIBIOTICO</strong></td>
                                                        <td colspan="2" class="Estilo5"  ><strong>INTERPRETACI&Oacute;N</strong>
                                                        </td>
                                                    </tr>
						<?php	pg_free_result($datos_generales);
							$pos=0;

						while($row = pg_fetch_array($consulta))//ELEMENTOS)
						{?>
                                                    <tr>
                                                        <td width='46%'><?php echo htmlentities($row['antibiotico'])?></td>
                                                        <td width='54%'><?php echo htmlentities($row['resultado'])?>
                                                        </td>

                                                    </tr>
                                            <?php
						}

						pg_free_result($consulta);?>
                                                </table>
                                             </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
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
			}?>
			<br style="page-break-after: always;" />
		<?php
		 }?>

	<?php
	}
?>
