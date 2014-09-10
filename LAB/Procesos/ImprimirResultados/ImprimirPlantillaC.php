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
$row_cantidad=mysql_fetch_array($Catidad);
//echo $row_cantidad[0];
	if ($row_cantidad[0]==1){
		$tipo=$obj->ObtenerResultado($idsolicitud,$iddetalle);
		$row_tipo=mysql_fetch_array($tipo);
		$Resultado=$row_tipo['Resultado'];

		If($Resultado=='P'){
			$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
			$row_estab = mysql_fetch_array($Consulta_Estab);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
			$row_generales= mysql_fetch_array($datos_generales);
			$consulta_area=$obj->LeerDatos($idexamen);
			$row_area= mysql_fetch_array($consulta_area);
			$consulta_datos=$obj->DatosResultadoPlanCPositivo($idsolicitud,$iddetalle);
			$row_datos= mysql_fetch_array($consulta_datos);
			$consulta=$obj->LeerResultadosAntibioticos($idsolicitud,$iddetalle);


		  	$ban=0;

			$nombre=$row_area['NombreArea'];
			?>
			<table width='100%' border='0' align='center' cellspacing="0">
                            <tr>
                                <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                <td align="center" colspan="4" width="60%" class="Estilo6">
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			            <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                                    <p><strong>ÁREA DE <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
                                <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
					<td colspan='2' class="Estilo6"><?php echo $row_generales['Nombre']?></td>
					<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			  		<td colspan='2' class="Estilo6"><?php echo $row_generales['Fecha']?></td>
				</tr>

				<tr>
					<td colspan='1' class="Estilo5"><strong>NEC:</strong></td>
			  		<td colspan='5' class="Estilo7"><?php echo $row_generales['IdNumeroExp']?></td>
                                </tr>
                                <tr>
					<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
					<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
						<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			  		<td colspan='2' class="Estilo6"><div id="divsuedad">
		        			<script language="JavaScript" type="text/javascript">
		        				calc_edad();
		        			</script>
		    	  		   	</div>
					</td>

					<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			  		<td colspan='2' class="Estilo6"><?php echo $row_generales['Sexo'];?></td>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
					<td colspan='1' class="Estilo6"><?php echo htmlentities($row_generales['Procedencia']);?></td>

					<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
					<td colspan='2' class="Estilo6" width='6%'><?php echo htmlentities($row_generales['Origen']);?></td>
				</tr>
				<tr>
					<td colspan='1' class="Estilo5"><strong>Examen Realizado:</strong></td>
			  		<td colspan='4' class="Estilo6"><?php echo htmlentities($row_area['NombreExamen']);?></td>
				</tr>
                                <tr>
					<td colspan='1' class="Estilo5"><strong>Validado Por:</strong></td>
					<td colspan='4' class="Estilo6"><?php echo $row_generales['NombreEmpleado'];?></td>
				</tr>
                                <tr>
                                        <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="6">
                                        <table width="75%" border="0" align="left" cellspacing="0" >

                                        <tr>
                                                <td colspan='1' class="Estilo5"><strong>Resultado:</strong></td>
                                                <td colspan='4' class="Estilo6"><?php echo "Positivo"?></td>
                                        </tr>
                                        <tr>
                                                <td colspan='1' class="Estilo5" ><strong>Organismo:</strong></td>
                                                <td colspan='4' class="Estilo6"><?php echo htmlentities($row_datos['Bacteria']); ?></td>
                                        </tr>
                                        <tr>
                                                <td class="Estilo5" colspan="1" style="font:bold" ><strong>Cultivo con Cuenta de Colonias:</strong></td>
                                                <td class="Estilo5" colspan="5"><?php echo htmlentities($row_datos['Cantidad'])?></td>

                                        <tr>
                                                <td colspan='6'>&nbsp;</td>
                                        </tr>
                                        <tr>
                                                <td colspan='6'>
                                                    <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>

                                                            <tr>
                                                                    <td class="Estilo5" colspan="1" ><strong>ANTIBIOTICO</strong></td>
                                                                    <td class="Estilo5" colspan="2" style='font:bold'  ><strong>INTERPRETACI&Oacute;N</strong>
                                                                    </td>
                                                            </tr>
                                                                    <?php	mysql_free_result($datos_generales);
                                                                            $pos=0;

                                                            while($row = mysql_fetch_array($consulta))//ELEMENTOS)
                                                            {?>
                                                            <tr>
                                                                    <td colspan="2" class="Estilo5"><?php echo htmlentities($row['Antibiotico'])?></td>
                                                                    <td colspan="2" class="Estilo5"><?php echo htmlentities($row['Resultado'])?>
                                                                    </td>
                                                                    <td width='60%'>&nbsp; </td>
                                                            </tr>
                                                            <?php
                                                            }
                                                            mysql_free_result($consulta);?>
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
	//	     $total= $row_cantidad[0];
          //           $cont=1;

                        // echo $idsolicitud."-".$iddetalle."-".$lugar;
			$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
			$row_estab = mysql_fetch_array($Consulta_Estab);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
					//$datos_empleado=$obj->DatosEmpleado($idempleado);
			$consulta_datos=$obj->LeerDatos($idexamen);
			$row_area= mysql_fetch_array($consulta_datos);
			$row_generales= mysql_fetch_array($datos_generales);
		        $Observ= $obj->ObtenerObservacion($idsolicitud,$iddetalle,$lugar);
		        $row_observ= mysql_fetch_array($Observ);
		        $Observacion=$row_observ['Observacion'];
			$nombre=$row_area['NombreArea'];
			?>


                                <div  id="divImpresion" >
                                    <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
                                        <tr>
                                            <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                            <td align="center" colspan="4" width="60%" class="Estilo6">
                                                <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                                <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
						<p><strong>ÁREA DE <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
                                            </td>
                                            <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
					</tr>
                                        <tr>
                                            <td colspan="6">&nbsp;</td>
                                        </tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo $row_generales['Nombre']?></td>
                                            <td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo $row_generales['Fecha']?></td>
						<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>NEC</strong></td>
                                            <td colspan="2" class="Estilo7"><?php echo $row_generales['IdNumeroExp']?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
                                            <td colspan="2" class="Estilo6">
                                                <div id="divsuedad">
                                                    <script language="JavaScript" type="text/javascript">
		        				calc_edad();
                                                    </script>
                                                </div>
                                            </td>

                                            <td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo $row_generales['Sexo']?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
                                            <td colspan="2"class="Estilo6"><?php echo htmlentities($row_generales['Procedencia'])?></td>

                                            <td colspan="1" class="Estilo5"><strong>Origen:</strong></td>
                                            <td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['Origen'])?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Examen Realizado:</strong></td>
                                            <td colspan='5' class="Estilo6"><?php echo htmlentities($row_area['NombreExamen'])?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Validado Por:</strong></td>
                                            <td colspan='5' class="Estilo6"><?php echo $row_generales['NombreEmpleado']; ?></td>
					</tr>
					<tr>
                                            <td colspan="1" class="Estilo5"><strong>Resultado:</strong></td>
						<?php 	mysql_free_result($consulta_datos);
							mysql_free_result($datos_generales);

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
	else{//Mas de un resultado
		// echo $row_cantidad[0];
		 $tipo=$obj->ObtenerResultadoxId($idsolicitud,$iddetalle);
		 while( $row_tipo=mysql_fetch_array($tipo)){
			If($row_tipo[1]=='P'){
			//echo $row_tipo[0];
				$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
				$row_estab = mysql_fetch_array($Consulta_Estab);
				$datos_generales=$obj->MostrarDatosGeneralesxId($idsolicitud,$iddetalle,$lugar,$row_tipo[0]);
				$row_generales= mysql_fetch_array($datos_generales);
				$consulta_area=$obj->LeerDatos($idexamen);
				$row_area= mysql_fetch_array($consulta_area);
				//echo $row_tipo[0]."***".$idsolicitud."***".$iddetalle;
				$consulta_datos=$obj->DatosResultadoPlanCPositivoxId($idsolicitud,$iddetalle,$row_tipo[0]);
				$row_datos= mysql_fetch_array($consulta_datos);
				//echo $row_datos[0];
				$consulta=$obj->LeerResultadosAntibioticosxId($idsolicitud,$iddetalle,$row_tipo[0]);

				$posele=0;
		  	$ban=0;

			$nombre=$row_area['NombreArea'];
			?>
			<table width="100%" border="0" align="center" class="StormyWeatherFormTABLE">
                            <tr>
                                <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                                <td align="center" colspan="4" width="60%" class="Estilo6">
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			            <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                                    <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
                                <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Establecimiento:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo $row_generales['Nombre']?></td>
				<td colspan="1" class="Estilo5"><strong>Fecha:</strong></td>
                                <td colspan="2" class="Estilo6"><?php echo $row_generales['Fecha']?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>NEC:</strong></td>
                                <td colspan="2" class="Estilo6"><?php echo $row_generales['IdNumeroExp']?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
				<td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
					<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
                                <td colspan="2" class="Estilo6">
                                    <div id="divsuedad">
		        		<script language="JavaScript" type="text/javascript">
		        				calc_edad();
		        		</script>
                                    </div>
				</td>
				<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
			  	<td colspan="2" class="Estilo6"><?php echo $row_generales['Sexo'];?></td>
                            </tr>
                            <tr>
                                <td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
				<td colspan="2" class="Estilo6" width='38%'><?php echo htmlentities($row_generales['Procedencia']);?></td>
				<td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
				<td colspan="2" class="Estilo6" width='6%'><?php echo htmlentities($row_generales['Origen']);?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Examen Realizado:</strong></td>
                                <td colspan="5" class="Estilo6"><?php echo htmlentities($row_area['NombreExamen']);?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Resultado:</strong></td>
				<td colspan="5" class="Estilo6"><?php echo "Positivo"?></td>
                            </tr>
                            <tr>
				<td colspan="1" class="Estilo5"><strong>Validado Por:</strong></td>
				<td colspan="5" class="Estilo6"><?php echo $row_generales['NombreEmpleado'];?></td>
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
                                        <tr>
                                            <td colspan="1" class="Estilo5" >Organismo:</td>
                                            <td colspan="5" class="Estilo6"><?php echo htmlentities($row_generales['Bacteria']); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1" class="Estilo5">CULTIVO CON CUENTA DE COLONIAS</td>
                                            <td colspan="5" class="Estilo5"><?php echo htmlentities($row_generales['Cantidad'])?></td>
					</tr>
                                        <tr>
                                            <td colspan='6'>&nbsp;</td>
                                        </tr>
                                        <tr>
                                             <td colspan='6'>
                                                <table width="100%" border="0" align="left" cellspacing="0" >
                                                    <tr>
                                                        <td colspan="1" class="Estilo5" ><strong>ANTIBIOTICO</strong></td>
                                                        <td colspan="2" class="Estilo5"  ><strong>INTERPRETACI&Oacute;N</strong>
                                                        </td>
                                                    </tr>
						<?php	mysql_free_result($datos_generales);
							$pos=0;

						while($row = mysql_fetch_array($consulta))//ELEMENTOS)
						{?>
                                                    <tr>
                                                        <td width='46%'><?php echo htmlentities($row['Antibiotico'])?></td>
                                                        <td width='54%'><?php echo htmlentities($row['Resultado'])?>
                                                        </td>

                                                    </tr>
                                            <?php
						}

						mysql_free_result($consulta);?>
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
