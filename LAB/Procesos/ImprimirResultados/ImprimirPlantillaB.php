<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsImprimirResultado.php");
//OBTENIENDO DATOS
$iddetalle=$_GET['var1'];
$idsolicitud=$_GET['var2'];
$idplatilla=$_GET['var3'];
$expediente=$_GET['var4'];
$idarea=$_GET['var5'];
$idexamen=$_GET['var6'];
$sexo=$_GET['var7'];
$fechanac=$_GET['var8'];
$subservicio=$_GET['var9'];
$idestandar=$_GET['var10'];
//echo $iddetalle;
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

    .Estilo5 {font-family: Helvetica; font-size: 8pt}
    .Estilo6 {font-family: Helvetica; font-size: 7.5pt}
    .Estilo7 {font-family: Helvetica; font-size: 9pt}
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
          $Cuentadias = $obj->CalculoDias($fechanac);
          $Cdias = pg_fetch_array($Cuentadias);
          $dias = $Cdias[0];

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
                $FechaRes  = $obj->ObtenerFechaResultado($idsolicitud,$iddetalle,$lugar);
                $row_fecha = pg_fetch_array($FechaRes);
		$row_area = pg_fetch_array($consulta_datos);
		?>
		
                <table width="100%" border="0" align="center" cellpadding="0%"  cellspacing="0%" >
                    <tr>
                        <td colspan="1" align="left" width="15%"><img id="Image1" style='width: auto; height: 55px;' src="../../../Imagenes/escudo.png" name="Image1"></td>
                        <td align="center" colspan="4" width="70%" class="Estilo6">
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                    <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                                    <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
                        </td>
                        <td colspan="1" align="right" width="15%"><img id="Image3" style='width: auto; height: 55px;' src="../../../Imagenes/paisanito.png" name="Image3"></td>
                    </tr>
                    <tr>
                        <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    </tr>
                </table>
    
                <table width="100%" border="0" align="center"  cellpadding="0%"  cellspacing="0%" >   
                    
		    <tr>
                        <td colspan="1" class="Estilo5" width="28%" align="left"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan="3" class="Estilo6" width="37%" align="left"><?php echo htmlentities($row_generales['estabext'])?></td>
			<td colspan="1" class="Estilo5" width="19%" align="left"><strong>Fecha Resultado:</strong></td>
			<td colspan="1" class="Estilo6" width="16%" align="left"><?php echo $row_fecha['fecharesultado']?></td>
		    </tr>
                    <tr>
                        <td colspan="1" class="Estilo5" align="left"><strong>Expediente:</strong></td>
                        <td colspan="3" class="Estilo7" align="left"><?php echo $row_generales['idnumeroexp']?></td>
                        <td colspan="1" class="Estilo5" align="left"><strong>Fecha Recepción:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['fecharecepcion']?></td>
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5" align="left"><strong>Paciente:</strong></td>
                        <td colspan="3" class="Estilo6" align="left"><?php echo htmlentities($row_generales['paciente'])?></td>
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
                            <td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($row_generales['empleado'])?></td>
                    </tr>
                    <tr>
                            
                            <td colspan='5' class="Estilo6" align="left">&nbsp;</td>
                    </tr>
		</table>

		<table width='100%' border='0' align='center' cellpadding="0%"  cellspacing="0%" >
			<?php pg_free_result($consulta_datos);
			pg_free_result($datos_generales);?>
		    <tr>
                        <td width="35%" class="Estilo5"></td> 
                        <td width="25%" class="Estilo5"> <strong> Resultado </stron> </td> 
                        <td width="20%" class="Estilo5"> <strong> Unidades </strong> </td> 
                        <td width='20%' class="Estilo5" colspan='2'><stron> Control Normal </stron> </td>
		    </tr>
                    <tr>
                        <td colspan="6"><hr></td>
                    </tr>
				<?php $pos=0;
			 	$posele=0;
	    while($row = pg_fetch_array($consulta))//ELEMENTOS
	    {
                if($row['subelemento']=='S')
                {    //echo "if";
                ?>
		    <tr>
                        <td colspan='5' class="Estilo5" ><strong><?php echo htmlentities($row['elemento'])?></strong></td>
		    </tr>
			<?php	 //echo  $idedad."   ".$sexo;
                        $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['idelemento'],$lugar,$idedad,$sexo);
                                                         //LeerSubElementosExamen($idsolicitud,$iddetalle,$idelemento,$lugar,$idedad,$sexo)
                         while($rowsub = pg_fetch_array($consulta2))//SUBELEMENTOS
			{?>
                    <tr>
		        <td width='35%' class="Estilo5"><?php echo htmlentities($rowsub['subelemento'])?></td>
                                <?php
                    if(!empty($rowsub['resultado'])){ ?>
			<td width='25%' class="Estilo5"><?php echo htmlentities($rowsub['resultado'])?></td>
                                <?php
                    }else{ ?>
                        <td width='25%' class="Estilo5"><?php echo htmlentities($rowsub['posible_resultado'])?></td>
                               <?php
                    } ?>
                    	<td width='20%' class="Estilo5"><?php echo htmlentities($rowsub['unidad'])?></td>
			<td width='45%' class="Estilo5"> <?php echo htmlentities($rowsub['observacion']) ." ".htmlentities($rowsub['unidad'])?> </td> 
		    </tr>
                        
                        
				<?php	$pos=$pos + 1;
				}?>
		    <tr>
                        <td colspan='5' class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
		    </tr>
		    <tr>
		 	<td colspan='5'>&nbsp;</td>
		    </tr>
	  <?php }else{
                                     //echo "else";  ?>
		    <tr>
			<?php
                            $consulta3=$obj->ResulatdoElementoExamen($idsolicitud,$iddetalle,$row['idelemento']);
		            $rowele = pg_fetch_array($consulta3);

				  // echo htmlentities($rowele['Resultado']);
			  ?>
			<td width='35%' class="Estilo5" class='StormyWeatherFieldCaptionTD'><strong><?php echo htmlentities($row['elemento'])?></strong></td>
			<td width='25%' class="Estilo5"><?php echo htmlentities($rowele['resultado'])?></td>
			<td width='20%' class="Estilo5"><?php echo htmlentities($row['unidadelem'])?></td>
                        <td width='20%' class="Estilo5"><?php echo htmlentities($rowele['observacion'])."  ".htmlentities($row['unidadelem'])?></td> 
		    </tr>
				<?php	$posele=$posele+1;?>
		    <tr>
			<td colspan='5' class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
		    </tr>
		    <tr>
			<td colspan='6'>&nbsp;</td>
		    </tr>
	<?php	}

            } //del while
                                
            while($row = pg_fetch_array($consulta))//ELEMENTOS
            {
                if($row['subelemento']=="S")
                { ?>
                    <tr>
                        <td  width='35%' class="Estilo5" align="justify"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                    </tr>
                             <?php
                                $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['idelemento'],$lugar,$idedad,$sexo);
                               //echo  $idedad."   ".$sexo;
                    while($rowsub = pg_fetch_array($consulta2)){//SUBELEMENTOS
                           ?>
                    <tr>
                        <td width='35%' class="Estilo5"  align="justify"><?php echo htmlentities($rowsub['subelemento'])?></td>
                        <td width='25%' class="Estilo5"  align="justify"><?php echo htmlentities($rowsub['resultado'])?></td>
                        <td width='15%' class="Estilo5"  align="justify"><?php echo htmlentities($rowsub['unidad'])?></td>
                  <?php if ((!empty($rowsub['rangoinicio'])) AND (!empty($rowsub['rangofin']))){?>
                        <td width='20%' class="Estilo5"  align="justify"> <?php echo $rowsub['rangoinicio']."  -  ".$rowsub['rangofin']?>
                  <?php }else{ 
                       ?>  
                        <td width='20%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</td>
                  <?php } ?>
                        
                        </tr>
            <?php
                  $pos=$pos + 1;
                        }?>
                    <tr>
                        <td width='50%'  class="Estilo5"  align="justify"><?php echo htmlentities($row['observelem'])?></td>
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
                                     <td  width='35%' class="Estilo5" align="justify"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                    <td><?php echo htmlentities($vector_elementos[$posele])?></td>
                                    <td width='25%' class="Estilo5" align="justify"><?php htmlentities($row['unidadelem'])?></td>
                                </tr>
                                  <?php
                                         $posele=$posele+1;
                                       ?>
                                <tr>
                                     <td colspan='5' class="Estilo5" align="justify"><?php echo htmlentities($row['observelem'])?></td>
                                </tr>

                            <?php
                            }

                    }
                                
                           
			pg_free_result($consulta);?>
                        <tr>
                            <td colspan="6"><hr></td>
                        </tr>
                        <tr>
                            <td colspan='1' class="Estilo5" align="left"><strong>Observacion:</strong></td>
                            <td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($row_generales['observacion'])?></td>
                        </tr>
                        <tr>
                                <td colspan="6" class="Estilo6">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="Estilo6">&nbsp;</td>
                            </tr>
                           <tr>
                                <td colspan="6" class="Estilo6" align="right">
                                    <br><br>Sello: _______________________ &nbsp;&nbsp;&nbsp;     Firma: _______________________
                                </td>
                            </tr>
			<tr>
				<td colspan="7" align="center">
					<div id="boton1"><input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" /></div>
				</td>
			</tr>
		</table>
<?php break;

      default:
               	$consulta=$obj->LeerElementosExamen($idexamen,$iddetalle,$lugar);
	  	$consulta_datos=$obj->LeerDatos($idexamen);
	  	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar);
                $row_generales= pg_fetch_array($datos_generales);
                $FechaRes  = $obj->ObtenerFechaResultado($idsolicitud,$iddetalle,$lugar);
                $row_fecha = pg_fetch_array($FechaRes);
	  	$row_area = pg_fetch_array($consulta_datos);
                //$nombrearea=$row_area['nombrearea'];
		?>
	  	<table width="100%" border="0" align="center" cellpadding="0%"  cellspacing="0%" >
                    <tr>
                        <td colspan="1" align="left" width="15%"><img id="Image1" style="width: auto; height: 55px;"  src="../../../Imagenes/escudo.png"  name="Image1"></td>
                        <td align="center" colspan="4" width="70%" class="Estilo6">
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                            <p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p></td>
                        <td colspan="1" align="right" width="15%"><img id="Image3" style="width: auto; height: 55px;"  src="../../../Imagenes/paisanito.png" name="Image3"></td>
                    </tr>
                </table>
                <table width="100%" border="0" align="center"  cellpadding="0%"  cellspacing="0%" >    
                    <tr>
                        <td colspan="1" class="Estilo5" width="28%" align="left"><strong>Establecimiento Solicitante:</strong></td>
			<td colspan="3" class="Estilo6" width="37%" align="left"><?php echo htmlentities($row_generales['estabext'])?></td>
			<td colspan="1" class="Estilo5" width="19%" align="left"><strong>Fecha Resultado:</strong></td>
			<td colspan="1" class="Estilo6" width="16%" align="left"><?php echo $row_fecha['fecharesultado']?></td>
		    </tr>
                    <tr>
                        <td colspan="1" class="Estilo5" align="left"><strong>Expediente:</strong></td>
                        <td colspan="3" class="Estilo7" align="left"><?php echo $row_generales['idnumeroexp']?></td>
                        <td colspan="1" class="Estilo5" align="left"><strong>Fecha Recepción:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['fecharecepcion']?></td>
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5" align="left"><strong>Paciente:</strong></td>
                        <td colspan="3" class="Estilo6" align="left"><?php echo htmlentities($row_generales['paciente'])?></td>
			<td colspan="1" class="Estilo5" align="left"><strong>Fecha Toma Muestra:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['f_tomamuestra']?></td>    
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5" align="left"><strong>Edad:</strong></td>
			<td colspan="3" class="Estilo6" align="left"><?php echo $row_generales['edad']?></td>
			<td colspan="1" class="Estilo5" align="left"><strong>Sexo:</strong></td>
			<td colspan="1" class="Estilo6" align="left"><?php echo $row_generales['sexo']?></td>
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5" align="left"><strong>Procedencia:</strong></td>
			<td colspan="3" class="Estilo6" align="left"><?php echo htmlentities($row_generales['nombreservicio'])?></td>
			<td colspan="1" class="Estilo5" align="left"><strong>Servicio:</strong></td>
			<td colspan="1" class="Estilo6" align="left" ><?php echo htmlentities($row_generales['nombresubservicio'])?></td>
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5" align="left"><strong>Examen Realizado:</strong></td>
			<td colspan="5" class="Estilo6" align="left"><?php echo htmlentities($row_area['nombre_examen'])?></td>
                    </tr>
                    <tr>
			<td colspan="1" class="Estilo5" align="left"><strong>Validado Por:</strong></td>
			<td colspan="5" class="Estilo6" align="left"><?php echo htmlentities($row_generales['empleado'])?></td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
                    <tr>
                        <td colspan="6">
                            <table width="100%" border="0" align="center" cellpadding="0%"  cellspacing="0%" >
                                <tr>
                                    <td width="25%" class="Estilo5"></td> 
                                    <td width="20%" class="Estilo5" align="justify"> <strong> Resultado </stron> </td> 
                                    <td width="15%" class="Estilo5" align="justify"> <strong> Unidades </strong> </td> 
                                    <td width="40%" class="Estilo5" align="justify"> <strong> &nbsp;&nbsp;Rangos de Referencia </strong> </td>
                                        
                                </tr>
                                <tr>
                                    <td colspan="6"><hr></td>
                                </tr>
                                <?php $pos=0;
                                      $posele=0;
                    while($row = pg_fetch_array($consulta))//ELEMENTOS
                    {
                            if($row['subelemento']=="S")
                            {   ?>
                                <tr>
                                    <td colspan="4" class="Estilo5" align="justify"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                </tr>
                                    <?php
                                            $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['idelemento'],$lugar,$idedad,$sexo);
                                             //echo  $idedad."   ".$sexo;

                                while($rowsub = pg_fetch_array($consulta2)){//SUBELEMENTOS
                                ?>
                               <tr>
                                    <td width="25%" class="Estilo5" align="justify">&emsp;<?php echo htmlentities($rowsub['subelemento'])?></td>
                               <?php 
                                 if(!empty($rowsub['resultado'])){ ?>
                                    <td width="25%" class="Estilo5" align="justify"><?php echo htmlentities($rowsub['resultado'])?></td>
                                <?php
                                 }else{?>    
                                    <td width="25%" class="Estilo5" align="justify"><?php echo htmlentities($rowsub['posible_resultado'])?></td>
                                 <?php } ?>   
                                    <td width="15%" class="Estilo5" align="justify"><?php echo htmlentities($rowsub['unidad'])?></td>
                       
                           <?php if ((!empty($rowsub['rangoinicio'])) AND (!empty($rowsub['rangofin']))){?>   
                                    <td width="15%" class="Estilo5" align="justify"> <?php echo $rowsub['rangoinicio']."  -  ".$rowsub['rangofin']?>
                           <?php }else{ 
                                        ?>  
                                    <td width="15%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                      <?php  } ?>
                               </tr>
                                    <?php
                            $pos=$pos + 1;
                                }?>
                               <tr>
                                    <td colspan="6" class="Estilo5" align="justify"><?php echo htmlentities($row['observelem'])?></td>
                               </tr>
                                <tr><td colspan="6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
                            <?php }
                            else
                            {?>
                                <tr>
                                    <td colspan="3" class="Estilo5" align="justify"><?php echo utf8_encode($row['elemento'])?></td>
                                    <td colspan="1" class="Estilo5" align="justify"><?php echo htmlentities($vector_elementos[$posele])?></td>
                                    <td colspan="1" class="Estilo5" align="justify"><?php utf8_encode($row['unidadelem'])?></td>
                                </tr>
                                  <?php
                                         $posele=$posele+1;
                                       ?>
                                <tr>
                                     <td colspan="5" class="Estilo5" align="justify"><?php echo utf8_encode($row['observelem'])?></td>
                                </tr>

                            <?php
                            }

                    }?>
                                <tr><td colspan="6"><hr></td></tr>
                                <tr>
                                    <td colspan="1" class="Estilo5" width="20%"><strong>Observacion:</strong></td>
                                    <td colspan="5" class="Estilo5"  width="80%"lign="justify"><?php echo $row_generales['observacion']?></td>
                                </tr>
                      <?php pg_free_result($consulta);
                            pg_free_result($consulta_datos);
                            pg_free_result($datos_generales);
                            ?>
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
                                <td colspan="6" class="Estilo6" align="right">
                                    <br><br>Sello: _______________________ &nbsp;&nbsp;&nbsp;     Firma: _______________________
                                </td>
                            </tr>
                </table>

                     <div id="boton">
                      <table align="center" border="0">
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
