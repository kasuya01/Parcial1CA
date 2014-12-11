<?php session_start();
include_once("clsImprimirResultado.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//OBTENIENDO DATOS
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$idexamen=$_GET['var6'];
$sexo=$_GET['var7'];
$fechanac=$_GET['var8'];
//echo $idexamen." fecha=".$fechanac;
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
@media print{
#boton1{display:none;}
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
	 $obj = new clsImprimirResultado;

	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab      = pg_fetch_array($Consulta_Estab);
	$ConEstandar    =$obj->Obtener_Estandar($idexamen);
	$CodEstandar    = pg_fetch_array($ConEstandar);
	$IdEstandar     =$CodEstandar[0];
       // echo $fechanac;
          $Cuentadias=$obj->CalculoDias($fechanac);
          $Cdias= pg_fetch_array($Cuentadias);
          $dias=$Cdias[0];

          $ConRangos=$obj->ObtenerCodigoRango($dias);
          $row_rangos=  pg_fetch_array($ConRangos);
          $idedad=$row_rangos[0];
            //echo  $idedad."   ".$sexo;
            //echo $IdEstandar;
	switch ($IdEstandar){

	case "H50":
                        //echo "caso 1";

		$consulta=$obj->LeerElementosExamen($idexamen,$iddetalle,$lugar); 
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);

		$row_generales= pg_fetch_array($datos_generales);
                $FechaRes  = $obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
                $row_fecha = pg_fetch_array($FechaRes);
		$row_area = pg_fetch_array($consulta_datos);
		?>
		<table width='100%' align='center' class ='StormyWeatherFormTABLE' >
		 <tr>
			 <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                         <td align="center" colspan="4" width="60%" class="Estilo6">
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                            <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                            <p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></td>

                         <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		</tr>
                <tr>
			<td colspan='1' class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo htmlentities($row_generales['estabext'])?></td>
			<td colspan='1' class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
		</tr>

		<tr>
			<td colspan='1' class="Estilo5"><strong>Expediente:</strong></td>
			<td colspan='5' class="Estilo7"><?php echo $row_generales['idnumeroexp']?></td>
                </tr>
                <tr>
			<td colspan='1' class="Estilo5"><strong>Paciente:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo $row_generales['paciente']?></td>

		</tr>
		<tr>

			<td colspan='1' class="Estilo5"><strong>Edad:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['edad']?></td>

			<td colspan='1' class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['sexo']?></td>
		</tr>
		<tr>
			<td colspan='1' class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['nombresubservicio']?></td>

			<td colspan='1' class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan='2' class="Estilo6"><?php echo $row_generales['nombreservicio']?></td>
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
			<td colspan='1' class="Estilo5"><strong>Observacion:</strong></td>
			<td colspan='5' class="Estilo6"><?php echo htmlentities($row_generales['observacion'])?></td>
		</tr>
		</table>

		<table width='100%' border='0' align='center' >
			<?php pg_free_result($consulta_datos);
			pg_free_result($datos_generales);?>
			<tr >
                            
				<td width='25%' class='Estilo5'><span style='color: #0101DF;'> Elemento </span> </td> 
				<td width='10%' class="Estilo5"><span style='color: #0101DF;'> Resultado </span> </td> 
				<td width='10%' class="Estilo5"><span style='color: #0101DF;'> Unidades </span> </td>
				<td width='10%' class="Estilo5" colspan='2'><span style='color: #0101DF;'> Control Normal </span> </td>
			</tr>
				<?php $pos=0;
			 	$posele=0;
			/*while($row = pg_fetch_array($consulta))//ELEMENTOS
			{
				if($row['subelemento']=='S')
				{    //echo "if";
                                    ?>
			<tr >
<<<<<<< HEAD
				<!--<td colspan='5' class="Estilo6" ><?php echo htmlentities($row['elemento'])?></td> -->
=======
				<td colspan='5' class="Estilo6" ><?php echo htmlentities($row['elemento'])?></td>
>>>>>>> roxy
			</tr>
				<?php	 //echo  $idedad."   ".$sexo;
                                $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['idelemento'],$lugar,$idedad,$sexo);
                                                         //LeerSubElementosExamen($idsolicitud,$iddetalle,$idelemento,$lugar,$idedad,$sexo)
				while($rowsub = pg_fetch_array($consulta2))//SUBELEMENTOS
				{?>
			<tr>
				<!--<td width='35%' class="Estilo6"><?php echo htmlentities($rowsub['subelemento'])?></td> -->
				<td width='25%' class="Estilo6"><?php echo htmlentities($rowsub['resultado'])?></td>
				<td width='20%' class="Estilo6"><?php echo htmlentities($rowsub['unidad'])?></td>
			<!--	<td width='40%' class="Estilo6"> <?php echo htmlentities($rowsub['observacion']) ." ".htmlentities($rowsub['unidad'])?> </td> 
			</tr>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
				<?php	$pos=$pos + 1;
				}?>
			<tr>
			 <!--	<td colspan='4' class="Estilo6"><?php echo htmlentities($row['observelem'])?></td>
			--> </tr>
			<tr>
				<td colspan='5'>&nbsp;</td>
			</tr>
			<?php	}
                        
                        else{
                                      //echo "else";  ?>
			<tr>
				<?php
                    $consulta3=$obj->ResulatdoElementoExamen($idsolicitud,$iddetalle,$row['id']);
					$rowele = pg_fetch_array($consulta3);

				  // echo htmlentities($rowele['Resultado']);
				  ?>
				<td width='40%' class="Estilo6" class='StormyWeatherFieldCaptionTD'><strong><?php echo htmlentities($row['elemento'])?></strong>
				</td>
				<td width='25%' class="Estilo6"><?php echo htmlentities($rowele['resultado'])?></td>
				<td width='10%' class="Estilo6"><?php echo htmlentities($row['unidadelem'])?></td>
                               <!-- <td class="Estilo5"><?php echo htmlentities($rowele['observacion'])."  ".htmlentities($row['unidadelem'])?></td> -->
			</tr>
				<?php	$posele=$posele+1;?>
			<tr>
				<td colspan='5' class="Estilo6"><?php echo htmlentities($row['observelem'])?></td>
			</tr>
			<tr>
				<td colspan='6'>&nbsp;</td>
			</tr>
			<?php	}

			}*/ //del while
                                
                                while($row = pg_fetch_array($consulta))//ELEMENTOS
                    {
                            if($row['subelemento']=="S")
                            {   ?>
                                <tr>
                                    <td  width='35%' class="Estilo5"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                </tr>
                                    <?php
                                            $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['idelemento'],$lugar,$idedad,$sexo);
                                             //echo  $idedad."   ".$sexo;

                                while($rowsub = pg_fetch_array($consulta2)){//SUBELEMENTOS
                                ?>
                               <tr>
                                    <td width='35%' class="Estilo5"><?php echo htmlentities($rowsub['subelemento'])?></td>
                                    <td width='25%' class="Estilo5"><?php echo htmlentities($rowsub['resultado'])?></td>
                                    <td width='15%' class="Estilo5"><?php echo htmlentities($rowsub['unidad'])?></td>
                                    <?php if ((!empty($rowsub['rangoinicio'])) AND (!empty($rowsub['rangofin']))){?>
                                    <td width='30%' class="Estilo5"> <?php echo $rowsub['rangoinicio']."  -  ".$rowsub['rangofin']?>
                           <?php }else{ 
                                        ?>  
                                    <td width='30%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</td>
                      <?php  } ?>
                                    
                               </tr>
                               
                                    <?php
                            $pos=$pos + 1;
                                }?>
                               <tr>
                                    <td width='50%'  class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
                               </tr>
                               
                               <tr>
                                   <td> </td>
                               </tr>
                               <tr>
                                   <td> </td>
                               </tr>
                               
                            <?php }
                            else
                            {?>
                                <tr>
                                     <td  width='35%' class="Estilo5"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                    <td><?php echo htmlentities($vector_elementos[$posele])?></td>
                                    <td width='25%' class="Estilo5"><?php htmlentities($row['unidadelem'])?></td>
                                </tr>
                                  <?php
                                         $posele=$posele+1;
                                       ?>
                                <tr>
                                     <td colspan='5' class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
                                </tr>

                            <?php
                            }

                    }
                                
                                
			pg_free_result($consulta);?>
			<tr>
				<td colspan="7" align="center">
					<div id="boton1"><input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" /></div>
				</td>
			</tr>
		</table>
<?php break;

	default:
                //echo "caso 2";

		$consulta=$obj->LeerElementosExamen($idexamen,$iddetalle,$lugar);
	  	$consulta_datos=$obj->LeerDatos($idexamen);
	  	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
                $row_generales= pg_fetch_array($datos_generales);
                $FechaRes  = $obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
                $row_fecha = pg_fetch_array($FechaRes);
	  	$row_area = pg_fetch_array($consulta_datos);
                //$nombrearea=$row_area['nombrearea'];
		?>
	  	<table width="100%" border="0" align="center" cellspacing="0" >
                    <tr>
                         <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                         <td align="center" colspan="4" width="60%" class="Estilo6">
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                            <p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></td>
                         <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
                    </tr>

                    <tr>
                        <td colspan="1" class="Estilo5"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['estabext'])?></td>
			<td colspan="1" class="Estilo5"><strong>Fecha Resultado:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo $row_fecha['fecharesultado']?></td>
		    </tr>

                    <tr>
                        <td colspan="1" class="Estilo6"><strong>Expediente:</strong></td>
                        
			<td colspan="5" class="Estilo5"><?php echo $row_generales['idnumeroexp']?></td>
                    </tr>
                    <tr>

			<td colspan="1" class="Estilo5"><strong>Paciente:</strong></td>
                        <td colspan="5" class="Estilo6"><?php echo htmlentities($row_generales['paciente'])?></td>
			    
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5"><strong>Edad:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo $row_generales['edad']?></td>

			<td colspan="1" class="Estilo5"><strong>Sexo:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo $row_generales['sexo']?></td>
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5"><strong>Procedencia:</strong></td>
			<td colspan="2" class="Estilo6"><?php echo htmlentities($row_generales['nombresubservicio'])?></td>

			<td colspan="1" class="Estilo5"><strong>Servicio:</strong></td>
			<td colspan="2" class="Estilo6" colspan='2'><?php echo htmlentities($row_generales['nombreservicio'])?></td>
                    </tr>
                    <tr>
			 <td colspan="1" class="Estilo5"><strong>Examen Realizado:</strong></td>
			 <td colspan="5" class="Estilo6"><?php echo htmlentities($row_area['nombre_examen'])?></td>
                    </tr>
                    <tr>
			 <td colspan="1" class="Estilo5"><strong>Validado Por:</strong></td>
			 <td colspan="5" class="Estilo6"><?php echo htmlentities($row_generales['empleado'])?></td>
                    </tr>
                    <tr>
			 <td colspan="1" class="Estilo5"><strong>Observacion:</strong></td>
			 <td colspan="5" class="Estilo6"><?php echo htmlentities($row_generales['observacion'])?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <table width='100%' border='0' align='center' cellspacing="0" >
                                <tr>
                                    <td width='35%' class='Estilo5'> <span style='color: #0101DF;'> Elemento </span> </td> 
                                    <td width='20%' class='Estilo5'> <span style='color: #0101DF;'> Resultado </span> </td> 
                                    <td width='15%' class='Estilo5'> <span style='color: #0101DF;'> Unidades </span> </td> 
                                    <td width='30%' class='Estilo5'> <span style='color: #0101DF;'> Rangos de Referencia </span> </td>
                                        
                                </tr>
                                <?php $pos=0;
                                      $posele=0;
                    while($row = pg_fetch_array($consulta))//ELEMENTOS
                    {
                            if($row['subelemento']=="S")
                            {   ?>
                                <tr>
                                    <td colspan='4' class="Estilo5"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                </tr>
                                    <?php
                                            $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['idelemento'],$lugar,$idedad,$sexo);
                                             //echo  $idedad."   ".$sexo;

                                while($rowsub = pg_fetch_array($consulta2)){//SUBELEMENTOS
                                ?>
                               <tr>
                                    <td width='35%' class="Estilo5"><?php echo htmlentities($rowsub['subelemento'])?></td>
                                    <td width='25%' class="Estilo5"><?php echo htmlentities($rowsub['resultado'])?></td>
                                    <td width='15%' class="Estilo5"><?php echo htmlentities($rowsub['unidad'])?></td>
                           <?php if ((!empty($rowsub['rangoinicio'])) AND (!empty($rowsub['rangofin']))){?>
                                    <td width='30%' class="Estilo5"> <?php echo $rowsub['rangoinicio']."  -  ".$rowsub['rangofin']?>
                           <?php }else{ 
                                        ?>  
                                    <td width='30%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</td>
                      <?php  } ?>
                               </tr>
                                    <?php
                            $pos=$pos + 1;
                                }?>
                               <tr>
                                    <td colspan='5' class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
                               </tr>
                            <?php }
                            else
                            {?>
                                <tr>
                                    <td class="Estilo5"><?php echo htmlentities($row['elemento'])?></td>
                                    <td><?php echo htmlentities($vector_elementos[$posele])?></td>
                                    <td width='25%' class="Estilo5"><?php htmlentities($row['unidadelem'])?></td>
                                </tr>
                                  <?php
                                         $posele=$posele+1;
                                       ?>
                                <tr>
                                     <td colspan='5' class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
                                </tr>

                            <?php
                            }

                    }

                            pg_free_result($consulta);
                            pg_free_result($consulta_datos);
                            pg_free_result($datos_generales);
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
				<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" />
                            </td>

			</tr>
                      </table>
                     </div>



	<?php break;
}?>
