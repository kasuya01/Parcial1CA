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

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Resultados de Examenes de Laboratorio </title>
<script language="JavaScript" type="text/javascript" src="ajax_ImprimirResultado.js"></script> 
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
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

.Estilo5 {font-size: 10pt}
.Estilo12 {font-size: 6pt}
-->
</style>


</script>
</head>

<body>
<?php 
	 $obj = new clsImprimirResultado;

	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
	$ConEstandar=$obj->Obtener_Estandar($idexamen);
	$CodEstandar= mysql_fetch_array($ConEstandar);
	$IdEstandar=$CodEstandar[0];
        
          $Cuentadias=$obj->CalculoDias($fechanac);
          $Cdias= mysql_fetch_array($Cuentadias);
          $dias=$Cdias[0];
	
          $ConRangos=$obj->ObtenerCodigoRango($dias);
          $row_rangos=  mysql_fetch_array($ConRangos);
          $idedad=$row_rangos[0];  
        //echo  $idedad."   ".$sexo;
	//echo $IdEstandar;
	switch ($IdEstandar){

	case "H50":
	
		$consulta=$obj->LeerElementosExamen($idexamen,$iddetalle,$lugar);
		$consulta_datos=$obj->LeerDatos($idexamen);
		$datos_generales=$obj->MostrarResultadoGenerales($idsolicitud,$idexamen,$lugar);
					
		$row_generales= mysql_fetch_array($datos_generales);
		$row_area = mysql_fetch_array($consulta_datos);
		?>
		<table width='80%' align='center' class ='StormyWeatherFormTABLE' >
		 <tr>
			<td align='center' colspan='6' class='CobaltButton'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                            <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                            <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
		</tr>
                <tr>
			<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
			<td colspan='2'><?php echo htmlentities($row_generales['Nombre'])?></td>
			<td colspan='1' style='font:bold'><strong>Fecha Resultado:</strong></td>
			<td colspan='2'><?php echo $row_generales['Fecha']?></td>
		</tr>
		
		<tr>
			<td colspan='1' style='font:bold'><strong>NEC</strong></td>
			<td colspan='1'><?php echo $row_generales['IdNumeroExp']?></td>
			<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='2'><?php echo $row_generales['NombrePaciente']?></td>
			
				<input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
		</tr>
		<tr>
			
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='1'>
                            <div id="divsuedad">
        			<script language="JavaScript" type="text/javascript">
               				calc_edad();
          			</script>
                            </div>
			</td>
			<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>	
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='1'><?php echo $row_generales['Sexo']?></td>
		</tr>
		<tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'><?php echo $row_generales['Procedencia']?></td>
			<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' style='font:bold'><?php echo $row_generales['Origen']?></td>
		</tr>
		<tr>
			 <td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			 <td colspan='4' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
		</tr>				 
		<tr>
			<td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
			<td colspan='4'><?php echo htmlentities($row_generales['NombreEmpleado'])?></td>
		</tr>
		<tr>
			<td colspan='1' style='font:bold'><strong>Observacion:</strong></td>
			<td colspan='4'><?php echo htmlentities($row_generales['Observacion'])?></td>
		</tr>
		</table>
				 
		<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<?php mysql_free_result($consulta_datos);
			mysql_free_result($datos_generales);?>
			<tr class='CobaltButton'>
				<td width='35%'></td>
				<td width='25%'>Resultado</td>
				<td width='20%'>Unidades</td>
				<td width='60%' colspan='2'>Control Normal </td>
			</tr>
				<?php $pos=0;
			 	$posele=0;
			while($row = mysql_fetch_array($consulta))//ELEMENTOS
			{  
				if($row['SubElemento']=='S')
				{ ?>
			<tr class='StormyWeatherFieldCaptionTD'>
				<td colspan='5' style='font:bold'><?php echo htmlentities($row['Elemento'])?></td>
			</tr>
				<?php	 //echo  $idedad."   ".$sexo;
                                $consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['IdElemento'],$lugar,$idedad,$sexo);
                                                         //LeerSubElementosExamen($idsolicitud,$iddetalle,$idelemento,$lugar,$idedad,$sexo)
				while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
				{?>
			<tr>
				<td width='35%'><?php echo htmlentities($rowsub['SubElemento'])?></td>
				<td width='25%'><?php echo htmlentities($rowsub['Resultado'])?></td>
				<td width='20%'><?php echo htmlentities($rowsub['Unidad'])?></td>
				<td width='40%'><?php echo htmlentities($rowsub['Observacion']) ." ".htmlentities($rowsub['Unidad'])?> </td>
			</tr>
				<?php	$pos=$pos + 1;
				}?>
			<tr>
				<td colspan='4'><?php echo htmlentities($row['ObservElem'])?></td>
			</tr>
			<tr>
				<td colspan='5'>&nbsp;</td>
			</tr>
			<?php	}else{?>
			<tr>
				<?php 
                    $consulta3=$obj->ResulatdoElementoExamen($idsolicitud,$iddetalle,$row['IdElemento']);
					$rowele = mysql_fetch_array($consulta3);
				   
				  // echo htmlentities($rowele['Resultado']);
				  ?>
				<td width='40%' style='font:bold' class='StormyWeatherFieldCaptionTD'><strong><?php echo htmlentities($row['Elemento'])?></strong>
				</td>
				<td  width='25%'><?php echo htmlentities($rowele['Resultado'])?></td>
				<td width='10%'><?php echo htmlentities($row['UnidadElem'])?></td>
                                 
				<td><?php echo htmlentities($rowele['Observacion'])."  ".htmlentities($row['UnidadElem'])?></td>
			</tr>
				<?php	$posele=$posele+1;?>
			<tr>
				<td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
			</tr>
			<tr>
				<td colspan='6'>&nbsp;</td>
			</tr>
			<?php	}	
		
			}// del while
			mysql_free_result($consulta);?>
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
	  	$datos_generales=$obj->MostrarResultadoGenerales($idsolicitud,$idexamen,$lugar);
                $row_generales= mysql_fetch_array($datos_generales);
	  	$row_area = mysql_fetch_array($consulta_datos);
		?>
	  	<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
                    <tr>
                        <td align='center' colspan='6' class='CobaltButton'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong><?php echo $row_estab['Nombre'] ?></strong></p>
                            <p><strong>&Aacute;rea de <?php echo htmlentities($row_area['NombreArea'])?> </strong></p></td>
                    </tr>
                    <tr>
                        <td colspan='1' style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
			<td colspan='2'><?php echo htmlentities($row_generales['Nombre'])?></td>
			<td colspan='1' style='font:bold'><strong>Fecha Resultado:</strong></td>
			<td colspan='2'><?php echo $row_generales['Fecha']?></td>
		    </tr>
			
                    <tr>
                        <td colspan='1' style='font:bold'><strong>NEC</strong></td>
			<td colspan='1'><?php echo $row_generales['IdNumeroExp']?></td>
			<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
                        <td colspan='2'><?php echo htmlentities($row_generales['NombrePaciente'])?></td>
			     <input name='suEdad' id='suEdad'  type='hidden'  value=<?php echo $row_generales['FechaNacimiento']?>>
                    </tr>
                    <tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='1'><div id="divsuedad">
        				<script language="JavaScript" type="text/javascript">
               					calc_edad();
          				</script>
    		   		    </div>
				</td>
			<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='1'><?php echo $row_generales['Sexo']?></td>
                    </tr>
                    <tr>	
			<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
			<td colspan='1' style='font:bold'><?php echo htmlentities($row_generales['Procedencia'])?></td>
			<td colspan='1' >&nbsp;&nbsp;&nbsp;</td>
			<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2' style='font:bold' colspan='2'><?php echo htmlentities($row_generales['Origen'])?></td>
                    </tr>
                    <tr>
			 <td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
			 <td colspan='5' style='font:bold'><?php echo htmlentities($row_area['NombreExamen'])?></td>
                    </tr>				 
                    <tr>
			 <td colspan='1' style='font:bold'><strong>Validado Por:</strong></td>
			 <td colspan='4'><?php echo htmlentities($row_generales['NombreEmpleado'])?></td>
                    </tr>
                    <tr>
			 <td colspan='1' style='font:bold'><strong>Observacion:</strong></td>
			 <td colspan='4'><?php echo htmlentities($row_generales['Observacion'])?></td>
                    </tr>
                    <tr>
			<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
                            <tr class='CobaltButton'>
                                <td></td>
				<td>Resultado</td>
				<td>Unidades</td>
                                <td>Valores Normales</td>
                            </tr>
                            <?php $pos=0;
				  $posele=0;
		while($row = mysql_fetch_array($consulta))//ELEMENTOS
		{  
			if($row['SubElemento']=="S")
			{   ?>
                            <tr class='StormyWeatherFieldCaptionTD'>
                                <td colspan='4' style='font:bold'><strong><?php echo htmlentities($row['Elemento'])?></strong></td>
                            </tr>
				<?php
					$consulta2=$obj->LeerSubElementosExamen($idsolicitud,$iddetalle,$row['IdElemento'],$lugar,$idedad,$sexo);
                                         //echo  $idedad."   ".$sexo;
		 					
                            while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
                            {?>
				<tr>
                                    <td width='30%'><?php echo htmlentities($rowsub['SubElemento'])?></td>
                                    <td width='25%'><?php echo htmlentities($rowsub['Resultado'])?></td>
                                    <td width='15%'><?php echo htmlentities($rowsub['Unidad'])?></td>
                                    <td width='15%'> <?php echo $rowsub['RangoInicio']."  -  ".$rowsub['RangoFin']?>
				</tr>
				<?php	
			$pos=$pos + 1;
				}?>
                                <tr>
                                    <td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
				</tr>
			<?php }
			else
			{?>
				<tr>
                                    <td style='font:bold'  class='StormyWeatherFieldCaptionTD'><?php echo htmlentities($row['Elemento'])?></td>
                                    <td><?php //echo htmlentities($vector_elementos[$posele])?></td>
                                    <td width='25%'><?php htmlentities($row['UnidadElem'])?></td>
				</tr>
					<?php 
						$posele=$posele+1;
						?>
				<tr>
                                    <td colspan='5'><?php echo htmlentities($row['ObservElem'])?></td>
				</tr>
			
			<?php
			}	
		
		}
			
			mysql_free_result($consulta);
			mysql_free_result($consulta_datos);
			mysql_free_result($datos_generales);
			?>
			<tr>
				<td colspan="7" align="center" >
					<div id="boton">
						<input type="button" name="btnImprimir" id="btnImprimir" value="Imprimir" onClick="window.print();" />
						<input type="submit" name="btnSalir" id="btnSalir" value="Cerrar" Onclick="window.close();" />
					</div>
				</td>
				
			</tr></table>
			</tr>
		</table>
	
	<?php break;
}?>