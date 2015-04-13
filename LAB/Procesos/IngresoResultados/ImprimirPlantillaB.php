<?php session_start();
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
include_once("clsConsultarElementos.php");

$idsolicitud          = $_GET['var1'];
$idexamen             = $_GET['var2'];
$resultado            = $_GET['var3'];
$idempleado           = $_GET['var3'];
$procedencia          = $_GET['var4'];
$origen               = $_GET['var5'];
$observacion          = $_GET['var6'];
$valores_subelementos = $_GET['var7'];
$codigos_subelementos = $_GET['var8'];
$valores_elementos    = $_GET['var9'];
$codigos_elementos    = $_GET['var10'];
$controles            = $_GET['var11'];
$controles_ele        = $_GET['var12'];
$nombrearea           = $_GET['var13'];
$establecimietno      = $_GET['var14'];
$responsable          = $_GET['var15'];
$sexo                 = $_GET['var16'];
$idedad               = $_GET['var17'];
$valores_combos       = $_GET['var18'];
$idestab              = $_GET['var19'];
$f_tomamuestra        = $_GET['var20'];
$tipomuestra          = $_GET['var21'];

//echo $idestab ;
?>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
    <title>Resultados de Examenes de Laboratorio </title>
    <script language="JavaScript" type="text/javascript" src="ajax_SolicitudesProcesadas.js"></script>
    <script language="JavaScript">
        function calc_edad() {
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

    .Estilo5 {font-family: Helvetica; font-size: 8pt}
    .Estilo6 {font-family: Helvetica; font-size: 7pt}
    .Estilo7 {font-family: Helvetica; font-size: 9pt}
    -->
</style>
</head>

<body>
    <?php
        $obj            = new clsConsultarElementos;
        $Consulta_Estab = $obj->Nombre_Establecimiento($lugar);
        $ConEstandar    = $obj->Obtener_Estandar($idexamen);
        $CodEstandar    = pg_fetch_array($ConEstandar);
        //var_dump($ConEstandar);exit();
        $codigo_estandar = $CodEstandar[0];
        $IdEstandar      = $CodEstandar[1];
        $row_estab      = pg_fetch_array($Consulta_Estab);
        $FechaRes       = $obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
        $row_fecha      = pg_fetch_array($FechaRes);
        $Consulta_EstabExt = $obj->Nombre_Establecimiento($idestab);
	$row_estabExt    = pg_fetch_array($Consulta_EstabExt);

        switch ($codigo_estandar){
            case "H50":
                $cadena               = $valores_subelementos;
                $vector               = EXPLODE("/",$cadena);
                $vector_elementos     = EXPLODE("/",$valores_elementos);
                $vector_controles     = EXPLODE("/",$controles);
                $vector_controles_ele = EXPLODE("/",$controles_ele);
               
                $consulta             = $obj->LeerElementosExamen($idexamen,$lugar);
                $consulta_datos       = $obj->LeerDatos($idexamen);
                $datos_generales      = $obj->MostrarDatosGenerales($idsolicitud,$lugar);
                $row_generales        = pg_fetch_array($datos_generales);
                $row_area             = pg_fetch_array($consulta_datos);

                $ConRangos  = $obj->ObtenerCodigoRango($row_generales['fechanac']);
                $row_rangos = pg_fetch_array($ConRangos);
                $idedad     = $row_rangos[0];
    ?>
                 <div  id="divImpresion1" >
                    <form name="frmimpresion1" >
                        <table width='100%' align='center' class ='StormyWeatherFormTABLE'  cellspacing="0">
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
                            <tr>
                               <td colspan='1' class='Estilo6' width="26%"><strong> Establecimiento Solicitante:</strong></td>
                               <td colspan='2' class='Estilo6' width="39%"><?php echo $row_estabExt['nombre']?></td>
                               <td colspan='1' class='Estilo6' width="20%"><strong>Fecha Resultado:</strong></td>
                               <td colspan='2' class='Estilo6' width="15%"><?php echo $row_fecha['fecharesultado']?></td>
                               
                            </tr>
                            <tr>
                               <td colspan='1' class='Estilo6'><strong>Expediente:</strong></td>
                               <td colspan='2' class='Estilo7' align='left'><?php echo $row_generales['idnumeroexp']?></td>
                               <td colspan='1' class='Estilo6' ><strong>Fecha Recepción:</strong></td>
                               <td colspan='2' class='Estilo6' align='left'><?php echo $row_generales['fecha']?></td>
                            </tr>
                            <tr>
                               <td colspan='1' class='Estilo6'><strong>Paciente:</strong></td>
                               <td colspan='2' class='Estilo6' align='left'><?php echo utf8_encode($row_generales['nombrepaciente'])?></td>
                               <td colspan='1' class='Estilo6' ><strong>Fecha Toma Muestra:</strong></td>
                               <td colspan='2' class='Estilo6'align='left' ><?php echo $f_tomamuestra ?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo6"><strong>Edad:</strong></td>
                                <td colspan='2' class="Estilo6"><?php echo $row_generales['edad']?></td>
                                <td colspan='1' class="Estilo6"><strong>Sexo:</strong></td>
                                <td colspan='1' class="Estilo6"><?php echo $row_generales['sexo']?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo6"><strong>Procedencia:</strong></td>
                                <td colspan='2' class="Estilo6"><?php echo utf8_encode($row_generales['procedencia'])?></td>
                                <td colspan='1' class="Estilo6"><strong>Servicio:</strong></td>
                                <td colspan='2' class="Estilo6"><?php echo $origen?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo6"><strong>Examen Realizado:</strong></td>
                                <td colspan='5' class="Estilo6"><?php echo utf8_encode($row_area['nombre_reporta'])?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo6"><strong>Validado Por:</strong></td>
                                <td colspan='5' class="Estilo6"><?php echo utf8_encode($responsable)?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo6"><strong>Observacion:</strong></td>
                                <td colspan='5' class="Estilo6"><?php echo utf8_encode($observacion)?></td>
                            </tr>
                            <tr>
                                <td colspan='6'>
                                    <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' cellspacing="0">
                                        <?php pg_free_result($consulta_datos);
                                              pg_free_result($datos_generales);?>
                                        <tr class='CobaltButton'>
                                            <td width='35%' class="Estilo6"></td>
                                            <td width='25%' class="Estilo6"><strong>Resultado</strong></td>
                                            <td width='20%' class="Estilo6"><strong>Unidades</strong></td>
                                            <td width='20%' colspan='2' class="Estilo6"><strong>Control Normal </strong></td>
                                        </tr>
                                        <?php
                                            $pos=0;
                                            $posele=0;
                                            
                                            while($row = pg_fetch_array($consulta)) { //ELEMENTOS
                                                if($row['subelemento']=='S') {
                                        ?>
                                                    <tr class='StormyWeatherFieldCaptionTD'>
                                                        <td colspan='5' class="Estilo6"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                                    </tr>
                                                <?php
                                                    $consulta2 = $obj->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);
                                                    
                                                    while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
                                                ?>
                                                        <tr>
                                                            <td width='35%' class='Estilo6'><?php echo htmlentities($rowsub['subelemento'])?></td>
                                                            <td width='25%' class='Estilo6'><?php echo htmlentities($vector[$pos])?></td>
                                                            <td width='20%' class='Estilo6'><?php echo htmlentities($rowsub['unidad'])?></td>
                                                            <td width='20%' class='Estilo6'><?php echo htmlentities($vector_controles[$pos])." ".htmlentities($rowsub['unidad'])?> </td>
                                                        </tr>
                                                    <?php
                                                        $pos = $pos + 1;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td colspan='6' class='Estilo6'><?php echo htmlentities($row['observelem'])?></td>
                                                    </tr><br>
                                                    <tr>
                                                        <td colspan='6'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    </tr>
                                            <?php
                                                } else { 
                                            ?>
                                                    <tr>
                                                        <td width='35%' class='Estilo6' class='StormyWeatherFieldCaptionTD'><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                                        <td width='25%' class='Estilo6'><?php echo htmlentities($vector_elementos[$posele])?></td>
                                                        <td width='20%' class='Estilo6'><?php echo htmlentities($row['unidadelem'])?></td>
                                                        <td width='20%' class='Estilo6'><?php echo htmlentities($vector_controles_ele[$posele])."  ".htmlentities($row['unidadelem'])?></td>
                                                    </tr>
                                                <?php
                                                    $posele = $posele+1;
                                                ?>
                                                    <tr>
                                                        <td colspan='5' class='Estilo6'><?php echo htmlentities($row['observelem'])?></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan='6' class='Estilo6'>&nbsp;</td>
                                                    </tr>
                                        <?php 
                                                }
        	                                }// del while
                                        ?>
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
                            <tr><td colspan='2' class="Estilo6" width='70%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td colspan='1' class="Estilo6" width='6%'>&nbsp;&nbsp;SELLO:</td>
                                <td colspan='1' class="Estilo6" width='8%'>___________</td>
                                <td colspan='1' class="Estilo6" width='6%'>&nbsp;&nbsp;&nbsp;&nbsp;FIRMA:</td>
                                <td colspan='1' class="Estilo6"width='10%'>________________</td>
                            </tr>
                        </table>
                     
                    <div id="boton1">
                        <table align="center">
                            <tr>
                                <td colspan="7" align="center">
                                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" />

                                </td>
                            </tr>
                        </table>
                    </div></form></div>
        <?php
                break;
            default:
                $cadena           = $valores_subelementos;
                $vector           = EXPLODE("/",$cadena);
                $vector_elementos = EXPLODE("/",$valores_elementos);
                $vector_combos    = EXPLODE("/",$valores_combos);
                $consulta         = $obj->LeerElementosExamen($idexamen,$lugar);
                $consulta_datos   = $obj->LeerDatos($idexamen);
                $datos_generales  = $obj->MostrarDatosGenerales($idsolicitud,$lugar);
                $row_generales    = pg_fetch_array($datos_generales);
                $row_area         = pg_fetch_array($consulta_datos);
                $idsexo           = $row_generales['idsexo'];

                $ConRangos  = $obj->ObtenerCodigoRango($row_generales['fechanac']);
                $row_rangos = pg_fetch_array($ConRangos);
                $idedad     = $row_rangos[0];
        ?>
                
                    
                    
                        <table width='100%' border='0' align='center'  cellspacing="0" >
                            <tr>
                                <td colspan="1" align="left" width="15%"><img id="Image1" style='width: auto; height: 55px;' src="../../../Imagenes/escudo.png"  name="Image1"></td>
                                <td align="center" colspan="4" width="70%" class="Estilo6">
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                                    <p><strong><?php echo $row_estab['nombre'] ?></strong></p>
                                    <p><strong>ÁREA DE <?php echo htmlentities($row_area['nombrearea'])?> </strong></p>
                                </td>
                                <td colspan="1" align="right" width="15%"><img id="Image3" style='width: auto; height: 55px;' src="../../../Imagenes/paisanito.png" name="Image3"></td>
                            </tr>
                            <tr>
                                <td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" width="25%" align="left"><strong>Establecimiento Solicitante:</strong></td>
                                <td colspan='3' class="Estilo6" width="45%" align="left"> <?php echo $row_estabExt['nombre']?></td>
                                <td colspan='1' class="Estilo5" width="20%" align="left"><strong>Fecha Resultado:</strong></td>
                                <td colspan='1' class="Estilo6" width="10%" align="left"><?php echo $row_fecha['fecharesultado']?></td>
                                
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Expediente:</strong></td>
                                <td colspan='3' class="Estilo7" align="left"><?php echo $row_generales['idnumeroexp']?></td>
                                <td colspan='1' class="Estilo5" align="left"><strong>Fecha Recepción:</strong></td>
                                <td colspan='1' class="Estilo6" align="left"><?php echo $row_generales['fecha']?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Paciente:</strong></td>
                                <td colspan='3' class="Estilo6" align="left"><?php echo htmlentities($row_generales['nombrepaciente'])?></td>
                                <td colspan='1' class="Estilo5" align="left"><strong>Fecha Toma Muestra:</strong></td>
                                <td colspan='1' class="Estilo6" align="left"><?php echo $f_tomamuestra ?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Edad:</strong></td>
                                <td colspan='3' class="Estilo6" align="left"><?php echo $row_generales['edad']?></td>
                                <td colspan='1' class="Estilo5" align="left"><strong>Sexo:</strong></td>
                                <td colspan='1' class="Estilo6" align="left"><?php echo $row_generales['sexo']?></td>
                             </tr>
                             <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Procedencia:</strong></td>
                                <td colspan='3' class="Estilo6" align="left"><?php echo htmlentities($row_generales['procedencia'])?></td>
                                <td colspan='1' class="Estilo5" align="left"><strong>Servicio:</strong></td>
                                <td colspan='1' class="Estilo6" align="left"><?php echo htmlentities($origen)?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Examen Realizado:</strong></td>
                                <td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($row_area['nombre_reporta'])?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Validado Por:</strong></td>
                                <td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($responsable)?></td>
                            </tr>
                            <tr>
                                <td colspan='1' class="Estilo5" align="left"><strong>Observacion:</strong></td>
                                <td colspan='5' class="Estilo6" align="left"><?php echo htmlentities($observacion)?></td>
                            </tr>
                            <tr>
                                <td colspan='6'>
                                    <table width='95%' border='0' align='left' Cellpadding="0"  cellspacing="0">
                                       <tr>
                                            <td width='25%' colspan='2' class='Estilo5'></td>
                                            <td width='35%' colspan='1' align="justify" class='Estilo5'><strong>Resultado</strong></td>
                                            <td width='15%' colspan='1' align="justify" class='Estilo5'><strong>Unidades</strong></td>
                                            <td width='20%' colspan='2' align="justify" class='Estilo5'><strong>&nbsp;&nbsp;Rangos de Referencia</strong></td>
                                        </tr>
                                    <?php
                                        $pos=0;
                                        $posele=0;
	                                       
                                while($row = pg_fetch_array($consulta)) { //ELEMENTOS
                                    if($row['subelemento']=="S") {
                                    ?>
                                        <tr class='StormyWeatherFieldCaptionTD'>
                                            <td colspan='6' class="Estilo5" align="justify"><strong><?php echo htmlentities($row['elemento'])?></strong></td>
                                            
                                        </tr>
                                            <?php
                                                $consulta2 = $obj->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);

                                                while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
                                            ?>
                                        <tr>
                                            <td width='25%' class="Estilo5" colspan='2' align="justify"><?php echo htmlentities($rowsub['subelemento'])?></td>
                                              <?php
                                        if($vector_combos[$pos]== NULL){  
                                              ?>
                                            <td width='35%' class="Estilo5" colspan='1' align="justify"><?php echo htmlentities($vector[$pos])?></td>
                                                        
                                            <?php }
                                        else{
                                                        $conresult=$obj->BuscarResultado($vector[$pos]);
                                                        $row_dresult=  pg_fetch_array($conresult);?>
                                            <td width='35%' class="Estilo5" align="justify" colspan='1'><?php echo htmlentities($row_dresult['posible_resultado'])?></td> 
                                  <?php }?>          
                                            <td width='15%' class="Estilo5" align="justify" colspan='1'>&nbsp;&nbsp;<?php echo htmlentities($rowsub['unidad'])?></td>
                                                    <?php
                                        if ((!empty($rowsub['rangoinicio'])) AND (!empty($rowsub['rangofin']))) { 
                                                    ?>
                                            <td width='15%' align="justify" class="Estilo5" colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rowsub['rangoinicio']." - ".$rowsub['rangofin']?></td>
                                                    <?php 
                                        } else { 
                                                    ?>
                                            <td width='20%' align="justify" colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                   <?php
                                        } ?>
                                        </tr>
                                    <?php
                                                    $pos=$pos + 1;
                                        }
                                            ?>
                                            <tr>
                                                <td colspan='6' class="Estilo5"><?php echo htmlentities($row['observelem'])?></td>
                                            </tr>
                                        <?php 
                                            } else {
                                        ?>
                                                <tr>
                                                    <td class="Estilo5"  colspan='3'><?php echo htmlentities($row['elemento'])?></td>
                                                    <td class="Estilo5"  colspan='1'><?php echo htmlentities($vector_elementos[$posele])?></td>
                                                    <td class="Estilo6"  colspan='1'><?php htmlentities($row['unidadelem'])?></td>
                                                </tr>
                                            <?php
                                                $posele=$posele+1;
                                            ?>
                                                <tr>
                                                    <td colspan='5' class="Estilo6" colspan='1'><?php echo htmlentities($row['observelem'])?></td>
                                                </tr>
                                    </table>
                                </td>
                                    <?php
                                            }

                                        }

                                        pg_free_result($consulta);
                                        pg_free_result($consulta_datos);
                                        pg_free_result($datos_generales);
                                    ?>
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
                            <tr><td colspan='1' class="Estilo6" width='52%'>&nbsp;&nbsp;</td>
                                <td colspan='1' class="Estilo6" width='6%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELLO:</td>
                                <td colspan='1' class="Estilo6" width='6%'>___________&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
                                <td colspan='1' class="Estilo6" width='6%'>FIRMA:</td>
                                <td colspan='2' class="Estilo6"width='10%'>________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
                            </tr>
                            <tr>
                                <td colspan='6'>
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
                                </td>
                            </tr>
                        </table>
                    
                    
                
                
    <?php 
                break;
        }
    ?>
