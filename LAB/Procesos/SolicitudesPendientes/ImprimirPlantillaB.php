<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include_once("clsConsultarElementos.php");

$idsolicitud=$_GET['var1'];
$idexamen=$_GET['var2'];
//$resultado=$_GET['var3'];
$idempleado=$_GET['var3'];
$procedencia=$_GET['var4'];
$origen=$_GET['var5'];
$observacion=$_GET['var6'];
$valores_subelementos=$_GET['var7'];
$codigos_subelementos=$_GET['var8'];
$valores_elementos=$_GET['var9'];
$codigos_elementos=$_GET['var10'];
$controles=$_GET['var11'];
$controles_ele=$_GET['var12'];
$nombrearea=$_GET['var13'];
$establecimietno=$_GET['var14'];
$responsable=$_GET['var15'];
$sexo=$_GET['var16'];
$idedad=$_GET['var17'];
//echo $sexo." - ".$idedad;
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

	$obj = new clsConsultarElementos;
	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$ConEstandar=$obj->Obtener_Estandar($idexamen);
	$CodEstandar= mysql_fetch_array($ConEstandar);
	$IdEstandar=$CodEstandar[0];
	$row_estab = mysql_fetch_array($Consulta_Estab);
	$FechaRes=$obj->ObtenerFechaResultado($idsolicitud,$idexamen,$lugar);
	$row_fecha=mysql_fetch_array($FechaRes);

       /* $Cuentadias=$obj->CalculoDias($fechanac);
        $Cdias= mysql_fetch_array($Cuentadias);
        $dias=$Cdias[0];

        $ConRangos=$obj->ObtenerCodigoRango($dias);
        $row_rangos=  mysql_fetch_array($ConRangos);
        $idedad=$row_rangos[0];   */



	switch ($idexamen){

	case "H50":
		$cadena=$valores_subelementos;
		$vector=EXPLODE("/",$cadena);
		$vector_elementos=EXPLODE("/",$valores_elementos);
		$vector_controles=EXPLODE("/",$controles);
		$vector_controles_ele=EXPLODE("/",$controles_ele);
		$consulta=$obj->LeerElementosExamen($idexamen,$lugar);
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
		$row_generales= mysql_fetch_array($datos_generales);
		$row_area = mysql_fetch_array($consulta_datos);


                $Cuentadias=$obj->CalculoDias($row_generales['fechanac']);
                $Cdias= mysql_fetch_array($Cuentadias);
                $dias=$Cdias[0];

                $ConRangos=$obj->ObtenerCodigoRango($dias);
                $row_rangos= mysql_fetch_array($ConRangos);
                $idedad=$row_rangos[0];   ?>
		<table width='100%' align='center' cellspacing="0" >
		<tr>
                     <td colspan="1" align="left" width="20%"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                     <td align='center' colspan='4' width="60%" class="Estilo6">
				 <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
				 <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
				 <p><strong>ÁREA DE <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
                     </td>
                     <td colspan="1" align="right" width="20%"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
		</tr>
                <tr>
			<td colspan='1' class='Estilo5'><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2' class='Estilo6'><?php echo $_GET['var14']?></td>
			<td colspan='1' class='Estilo5'><strong>Fecha Resultado:</strong></td>
			<td colspan='2' class='Estilo6'><?php echo $row_fecha['FechaResultado']?></td>
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
		</tr>
		<tr>
			<td colspan='1' class='Estilo5'><strong>NEC</strong></td>
			<td colspan='2' class='Estilo6'><?php echo $row_generales['IdNumeroExp']?></td>
                </tr>
                <tr>
			<td colspan='1' class='Estilo5'><strong>Paciente:</strong></td>
			<td colspan='2' class='Estilo6'><?php echo $row_generales['NombrePaciente']?></td>
           	</tr>
		<tr>
			<td colspan='1' class='Estilo5'><strong>Edad:</strong></td>
			<td colspan='2' class='Estilo6'>
                            <div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
               				calc_edad();
          			</script>
                            </div>
			</td>
			<td colspan='1' class='Estilo5'><strong>Sexo:</strong></td>
			<td colspan='1' class='Estilo6'><?php echo $row_generales['Sexo']?></td>

		</tr>
		<tr>
			<td colspan='1' class='Estilo5'><strong>Procedencia:</strong></td>
			<td colspan='2' class='Estilo6'><?php echo $row_generales['Procedencia']?></td>

			<td colspan='1' class='Estilo5'><strong>Servicio:</strong></td>
			<td colspan='2' class='Estilo6'><?php echo $row_generales['Origen']?></td>
		</tr>
		<tr>
			 <td colspan='1' class='Estilo5'><strong>Examen Realizado:</strong></td>
			 <td colspan='4' class='Estilo6'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>
		<tr>
			 <td colspan='1' class='Estilo5'><strong>Validado Por:</strong></td>
			 <td colspan='4' class='Estilo6'><?php echo htmlentities($responsable)?></td>
		</tr>
		<tr>
			 <td colspan='1' class='Estilo5'><strong>Observacion:</strong></td>
			 <td colspan='4' class='Estilo6'><?php echo htmlentities($observacion)?></td>
		</tr>
                <tr>
                    <td colspan="6">

                        <table width='100%' border='0' align='center' cellspacing="0">
                                <?php mysql_free_result($consulta_datos);
                                mysql_free_result($datos_generales);?>
                            <tr >
                                    <td width='35%' class='Estilo5'></td>
                                    <td width='25%' class='Estilo5'>Resultado</td>
                                    <td width='20%' class='Estilo5'>Unidades</td>
                                    <td width='60%' class='Estilo5'colspan='2'>Control Normal </td>
                            </tr>
                                <?php $pos=0;
                                        $posele=0;
                while($row = mysql_fetch_array($consulta))//ELEMENTOS
                {
                        if($row['SubElemento']=='S')
                        { ?>
                            <tr class='StormyWeatherFieldCaptionTD'>
                                    <td colspan='5' class='Estilo5'><?php echo htmlentities($row['Elemento'])?></td>
                            </tr>
                            <?php	$consulta2=$obj->LeerSubElementosExamen($row['IdElemento'],$lugar,$idsexo,$idedad);
                                    while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
                                    {?>
                            <tr>
                                    <td width='35%' class='Estilo5'><?php echo htmlentities($rowsub['SubElemento'])?></td>
                                    <td width='25%' class='Estilo5'><?php echo htmlentities($vector[$pos])?></td>
                                    <td width='20%' class='Estilo5'><?php echo htmlentities($rowsub['Unidad'])?></td>
                                    <td width='40%' class='Estilo5'><?php echo htmlentities($vector_controles[$pos])." ".htmlentities($rowsub['Unidad'])?> </td>
                            </tr>
                            <?php	$pos=$pos + 1;
                                    }?>
                            <tr>
                                    <td colspan='4' class='Estilo5'><?php echo htmlentities($row['ObservElem'])?></td>
                            </tr>
                            <tr>
                                    <td colspan='5'>&nbsp;</td>
                            </tr>
                <?php	}else{?>
                            <tr>
                                    <td width='40%' class='Estilo5' class='StormyWeatherFieldCaptionTD'><strong><?php echo htmlentities($row['Elemento'])?></strong>
                                    </td>
                                    <td  width='25%' class='Estilo5'><?php echo htmlentities($vector_elementos[$posele])?></td>
                                    <td width='10%' class='Estilo5'><?php echo htmlentities($row['UnidadElem'])?></td>
                                    <td><?php echo htmlentities($vector_controles_ele[$posele])."  ".htmlentities($row['UnidadElem'])?></td>
                            </tr>
                            <?php	$posele=$posele+1;?>
                            <tr>
                                    <td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
                            </tr>

                <?php	}

                }// del while
                mysql_free_result($consulta);?>

                        </table>
            </td>
        </tr>
        </table>
        <div id="boton">
            <table>
                <tr>
                    <td colspan="6" align="center">
			<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
			<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" />
                    </td>
		</tr>
            </table>
        </div>
	<?php
 	break;

	default:

	  $cadena=$valores_subelementos;
	  $vector=EXPLODE("/",$cadena);
	  $vector_elementos=EXPLODE("/",$valores_elementos);

	 // $obj = new clsConsultarElementos;
	  $consulta=$obj->LeerElementosExamen($idexamen,$lugar);
	  $consulta_datos=$obj->LeerDatos($idexamen);
	  $datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
			//$datos_empleado=$obj->DatosEmpleado($idempleado);
	  $row_generales= mysql_fetch_array($datos_generales);
	  $row_area = mysql_fetch_array($consulta_datos);


          $idsexo=$row_generales['idsexo'];
         // $idedad=$row_generales['fechanac'];
      //    echo  $idsexo." ".$idedad;
			//$row_empleado = mysql_fetch_array($datos_empleado);?>
	<table width='100%' border='0' align='center'  cellspacing="0">
            <tr>
                <td colspan="1" align="left"><img id="Image1" style="WIDTH: 80px; HEIGHT: 55px" height="86" src="../../../Imagenes/escudo.png" width="210" name="Image1"></td>
                <td align='center' colspan='4' class='Estilo6' >
                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                    <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                    <p><strong>ÁREA DE <?php echo htmlentities($row_area['NombreArea'])?> </strong></p>
		</td>
                <td colspan="1" align="right"><img id="Image3" style="WIDTH: 110px; HEIGHT: 55px" height="86" src="../../../Imagenes/paisanito.png" width="210" name="Image3"></td>
            </tr>
            <tr>
                <td colspan="6" align='center' >&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>Establecimiento Solicitante:</strong></td>
		<td colspan='2' class='Estilo6'><?php echo $_GET['var14']?></td>
		<td colspan='1' class='Estilo5'><strong>Fecha Resultado:</strong></td>
		<td colspan='2' class='Estilo6'><?php echo $row_fecha['FechaResultado']?></td>
                    <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>NEC</strong></td>
		<td colspan='5' class='Estilo7'><?php echo $row_generales['IdNumeroExp']?></td>
            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>Paciente:</strong></td>
		<td colspan='5' class='Estilo6'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
           </tr>
           <tr>
		<td colspan='1' class='Estilo5'><strong>Edad:</strong></td>
		<td colspan='2' class='Estilo6'>
                    <div id="divsuedad">
                        <script language="JavaScript" type="text/javascript">
                            calc_edad();
                        </script>
                    </div>
		</td>
                 <td colspan='1' class='Estilo5'><strong>Sexo:</strong></td>
		 <td colspan='2' class='Estilo6'><?php echo $row_generales['Sexo']?></td>

            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>Procedencia:</strong></td>
		<td colspan='2' class='Estilo6' ><?php echo htmlentities($row_generales['Procedencia'])?></td>
		<td colspan='1' class='Estilo5'><strong>Servicio:</strong></td>
		<td colspan='2' class='Estilo6'><?php echo htmlentities($row_generales['Origen'])?></td>
            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>Examen Realizado:</strong></td>
		<td colspan='5' class='Estilo6'><?php echo htmlentities($row_area['NombreExamen'])?></td>
            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>Validado Por:</strong></td>
		<td colspan='5' class='Estilo5'><?php echo htmlentities($responsable)?>						</td>
            </tr>
            <tr>
		<td colspan='1' class='Estilo5'><strong>Observacion:</strong></td>
		<td colspan='5' class='Estilo6'><?php echo htmlentities($observacion)?></td>
            </tr>

            <tr>
                <td colspan="6">
                    <table width='100%' border='0' align='center'  cellspacing="0">
                        <tr >
                            <td width='35%' class='Estilo5'></td>
                            <td width='20%' class='Estilo5'><strong>Resultado</strong></td>
                            <td width='15%' class='Estilo5'><strong>Unidades</strong></td>
                            <td width='30%' class='Estilo5'><strong>Rangos de Referencia</strong></td>
			</tr>
			<?php $pos=0;
                              $posele=0;
               while($row = mysql_fetch_array($consulta))//ELEMENTOS
               {
                    if($row['SubElemento']=="S")
                    {   ?>
                        <tr >
                            <td colspan='5' class='Estilo5'><strong><?php echo htmlentities($row['Elemento'])?></strong></td>
			</tr>
		<?php	$consulta2=$obj->LeerSubElementosExamen($row['IdElemento'],$lugar,$sexo,$idedad);

                     while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
                     {?>
			<tr>
                            <td width='35%' class='Estilo5'><?php echo htmlentities($rowsub['SubElemento'])?></td>
                            <td width='25%' class='Estilo5'><?php echo htmlentities($vector[$pos])?>
                            </td>
                            <td width='15%' class='Estilo5'><?php echo htmlentities($rowsub['Unidad'])?></td>
                  <?php   if ((!empty($rowsub['rangoinicio'])) AND (!empty($rowsub['rangofin']))){?>
                            <td width='30%' class='Estilo5'><?php echo $rowsub['rangoinicio']." - ".$rowsub['rangofin']?></td>
                  <?php   }else{ ?>
                            <td width='30%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <?php  } ?>
                     	</tr>
		  <?php
			$pos=$pos + 1;
                     }
			?>
			<tr>
                            <td colspan='5' class='Estilo5'><?php echo htmlentities($row['ObservElem'])?></td>
			</tr>
              <?php }
                    else
                    {?>
			<tr>
                            <td class='Estilo5' ><?php echo htmlentities($row['Elemento'])?></td>
                            <td class='Estilo5'><?php echo htmlentities($vector_elementos[$posele])?></td>
                            <td width='25%' class='Estilo5'><?php htmlentities($row['UnidadElem'])?></td>
			</tr>
			<?php
				$posele=$posele+1;
			?>
			<tr>
                            <td colspan='5' class='Estilo5'><?php echo htmlentities($row['ObservElem'])?></td>
			</tr>
			<?php
                    }

		}

                    mysql_free_result($consulta);
                    mysql_free_result($consulta_datos);
                    mysql_free_result($datos_generales);
		?>

                    </table>
                 </td>
            </tr>

       </table>
     <div id="boton">
         <table align='center' border="0">
             <tr>
                <td colspan="6" align="center" >
                    <input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
                    <input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="Cerrar();" />
                </td>
             </tr>
         </table>
    </div>
			<?php break;
}?>
