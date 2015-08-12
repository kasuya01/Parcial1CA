<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsSolicitudesProcesadas.php");

//variables POST

$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsSolicitudesProcesadas;

switch ($opcion) 
{
  case 1: 
    $idsolicitud=$_POST['idsolicitud']; 
	$idarea=$_POST['idarea'];
	$imprimir="";
    
	$consulta=$objdatos->DatosExamenes($idsolicitud,$idarea,$lugar);
	
	echo "<table width='65%'  align='center' border='0' class='StormyWeatherFormTABLE'><tr>";
	$pos=0;
	 while($row = mysql_fetch_array($consulta))
	{
		$imprimir= "<table width='65%'  align='center' border='0' class='StormyWeatherFormTABLE'>
						<tr class='CobaltButton'>
							<td width='30%' class='StormyWeatherFieldCaptionTD'>Prueba Realizada </td>
							<td width='35%' class='StormyWeatherDataTD'>".htmlentities($row['NombreExamen'])."
								<input name='oiddetalle[".$pos."]' type='hidden' id='oiddetalle[".$pos."]' value='".$row['IdDetalleSolicitud']."'> 
								<input name='oidexamen[".$pos."]' type='hidden' id='oidexamen[".$pos."]' value='".$row['IdExamen']."'> 
							</td>
							<td width='10%' class='StormyWeatherFieldCaptionTD'>Unidades</td>
							<td align='center' width='15%' class='StormyWeatherFieldCaptionTD'>Rangos Normales </td>
						</tr>	
						<tr class='CobaltButton'>	
							<td width='30%' class='StormyWeatherFieldCaptionTD'>Resultado</td>
							<td width='35%' class='StormyWeatherDataTD'>
								<input size='35' name='txtresultado[".$pos."]' type='text' id='txtresultado[".$pos."]'>
							</td>
							<td width='10%' class='StormyWeatherDataTD' align='center'>".htmlentities($row['Unidades'])."</td>
							<td width='15%' class='StormyWeatherDataTD' align='center'>".$row['RangoInicio']."-".$row['RangoFin']."</td>
						</tr>
						<tr class='CobaltButton'>
							<td width='30%' class='StormyWeatherFieldCaptionTD'>Lectura</td>
							<td width='30%' class='StormyWeatherDataTD' colspan='7'>
								<textarea name='txtlectura[".$pos."]' cols='50' id='txtlectura[".$pos."]'></textarea>
							</td>
						<tr class='CobaltButton'>
							<td width='30%' class='StormyWeatherFieldCaptionTD'>Interpretaci&oacute;n</td>
							<td width='30%' class='StormyWeatherDataTD' colspan='7'>
								<textarea name='txtinter[".$pos."]' cols='50' id='txtinter[".$pos."]'></textarea>
							</td>
						</tr>
						<tr class='CobaltButton'>
							<td width='30%' class='StormyWeatherFieldCaptionTD'>Observaci&oacute;n</td>
							<td width='30%' class='StormyWeatherDataTD' colspan='7'>
								<textarea name='txtobser[".$pos."]' cols='50' id='txtobser[".$pos."]'></textarea>
							</td>
						</tr>
						<tr class='CobaltButton'><td class='StormyWeatherDataTD'  colspan='6'>"; //numero de cajas de texto dibujadas para resultados
				           $imprimir .="</td></tr> ";
							//numero de cajas de texto dibujadas para resultados
								$pos=$pos + 1;
									echo $imprimir;
	}
	 //echo $pos;
				  echo "</tr>
						<tr>
							<td colspan='5' align='right'>
							<input  type='hidden' name='oculto' id='oculto' value='".$pos."'>
							<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaA1()'>
							</td>
						</tr>
					</table>";
       //echo $imprimir;
   break;
   case 2:
 // echo $idsolicitud."-".$idarea;
	$responsable=$_POST['idempleado'];
   	$idsolicitud=$_POST['idsolicitud']; 
	$idarea=$_POST['idarea'];
	$idrecepcion=$_POST['idrecepcion'];
	$valores_resultados=$_POST['valores_resultados'];
	$codigos_resultados=$_POST['codigos_resultados'];
	$valores_lecturas=$_POST['valores_lecturas'];
	$valores_inter=$_POST['valores_inter'];
	$valores_obser=$_POST['valores_obser'];
	$codigos_examenes=$_POST['codigos_examenes'];
    $establecimiento=$_POST['estab'];
   
        $Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
  
		  $consulta=$objdatos->MostrarResultadoGenerales1($idsolicitud,$idarea,$lugar);
		  $row = mysql_fetch_array($consulta);
		  $nombre=$row['NombreArea'];
		  $vector_resultados=EXPLODE("/",$valores_resultados);
		  $vector_codigos=EXPLODE("/",$codigos_resultados);
	      $vector_lecturas=EXPLODE("/",$valores_lecturas);
		  $vector_inter=EXPLODE("/",$valores_inter);
		  $vector_obser=EXPLODE("/",$valores_obser);
		  $vector_examenes=EXPLODE("/",$codigos_examenes);
		  $consulta=$objdatos->DatosExamenes($idsolicitud,$idarea,$lugar);
	//echo $valores_resultados;
   $Imprimir="<table width='80%' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					<td align='center' colspan='6' class='CobaltButton'>
						<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
						<p><strong>                       ".$row_estab['Nombre']."</strong></p>
						<p><strong>&Aacute;rea de ".htmlentities($row['NombreArea'])." </strong></p>
					</td>
				</tr>
				<tr>
					<td colspan='6' align='center'></td>
				</tr>
							<tr>
					<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
					<td colspan='3' style='font:bold'>".$establecimiento."</td>
					<td colspan='1' style='font:bold'><strong>Fecha:</strong></td>
					<td colspan='1' style='font:bold'>".$row['Fecha']."</td>
					<input name='suEdad' id='suEdad'  type='hidden'  value='".$row['FechaNacimiento']."'/>
				</tr>
				<tr>	
					<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
					<td colspan='1' style='font:bold'>".htmlentities($row['Procedencia'])."</td>
					<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
					<td colspan='3' style='font:bold'>".htmlentities($row['Origen'])."</td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
					<td colspan='1' style='font:bold'>".$row['IdNumeroExp']."</td>
					<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
					<td colspan='3' style='font:bold'>".htmlentities($row['NombrePaciente'])."</td>
				</tr>
				<tr>
								
					<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
					<td colspan='1' style='font:bold'><div id='divsuedad'>
			  
							</div></td>
					<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
					<td colspan='1' style='font:bold'>".$row['Sexo']."</td>					
				</tr>
				
				<tr>";
					$consulta_empleado=$objdatos->BuscarEmpleadoValidador($responsable);
					$fila_empleado = mysql_fetch_array($consulta_empleado);//$fila_empleado['NombreEmpleado'].
					$Imprimir.="
					<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
					<td  colspan='5' style='font:bold'>".htmlentities($fila_empleado['NombreEmpleado'])."</td>
				</tr>
				<tr>
					<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr>
					<td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
				</tr>
			</table>";
$Imprimir.="<table width='80%'  align='center' border='0' class='StormyWeatherFormTABLE'>
				<tr class='CobaltButton'>
					<td align='center'>Prueba Realizada </td>
					<td align='center'>Resultado</td>
					<td align='center'>Unidades</td>
					<td align='center'>Rangos Normales </td>
					<td align='center'>Lectura</td>
					<td align='center'>Interpretaci&oacute;n</td>
					<td align='center'>Observaci&oacute;n</td>
				</tr>";
				$pos=0;
	while($row = mysql_fetch_array($consulta))
	{
	 $Imprimir.="<tr>
					<td width='20%' >".htmlentities($row['NombreExamen'])."
						<input name='oiddetalle[".$pos."]' type='hidden' id='oiddetalle[".$pos."]' value='".$row['IdDetalleSolicitud']."'> 
						<input name='oidexamen[".$pos."]' type='hidden' id='oidexamen[".$pos."]' value='".$row['IdExamen']."'> 
					</td>";
		if ($row['IdDetalleSolicitud']== $vector_codigos[$pos]){
		 $Imprimir.="<td>".htmlentities($vector_resultados[$pos])."
					</td>";
							if ($vector_resultados[$pos]>=$row['RangoInicio'] AND $vector_resultados[$pos]<=$row['RangoFin']){
								$tab=1;
								$Imprimir.="<input name='txttab[".$pos."]' type='hidden' id='txttab[".$pos."]' value='".$tab."'>"; 
							}
							else{
								$tab=3;
								$Imprimir.="<input name='txttab[".$pos."]' type='hidden' id='txttab[".$pos."]' value='".$tab."'>"; 
							}
	    $Imprimir.="<td align='center'>".htmlentities($row['Unidades'])."</td>
					<td align='center'>".$row['RangoInicio']."-".$row['RangoFin']."</td>
					<td>".htmlentities($vector_lecturas[$pos])."</td>
					<td>".htmlentities($vector_inter[$pos])."</td>
					<td>".htmlentities($vector_obser[$pos])."</td>
				</tr>";
			$pos=$pos + 1;
		}
	
	 }
	
	$Imprimir.="<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>			
				</tr>
				<tr>
					<td colspan='7' align='center' >
						<input  type='hidden' id='oculto' value='".$pos."'>
						<input type='submit' id='btnGuardar' value='Guardar Resultados' Onclick='GuardarResultadosPlantillaA();' />
						<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaA1(".$idsolicitud.",\"".$idarea."\",\"".$responsable."\", \"".htmlentities($valores_resultados)."\",\"".$codigos_resultados."\",\"".htmlentities($valores_lecturas)."\",\"".htmlentities($valores_inter)."\",\"".htmlentities($valores_obser)."\",\"".$codigos_examenes."\",\"".htmlentities($establecimiento)."\") ;'>
						<input type='submit' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' />
					</td>
				</tr>
				</table>";	
		echo $Imprimir;
		//echo  $Imprimir;
   break;
   
   case 3://GUARDANDO DATOS DE RESULTADOS Y MOSTANDO LISTA ACTUALIZADA PLANTILLA "A"
		//Guardando Resultados
		$idsolicitud=$_POST['idsolicitud']; 
		$idarea=$_POST['idarea'];
		$idrecepcion=$_POST['idrecepcion'];
		$valores_resultados=$_POST['valores_resultados'];
		$codigos_resultados=$_POST['codigos_resultados'];
		$valores_lecturas=$_POST['valores_lecturas'];
		$valores_inter=$_POST['valores_inter'];
		$valores_obser=$_POST['valores_obser'];
		$codigos_examenes=$_POST['codigos_examenes'];
		$tabuladores=$_POST['tabuladores'];
		$responsable=$_POST['idempleado'];

	 	//**************************************************************************
		  $vector_resultados=EXPLODE("/",$valores_resultados);
		  $vector_codigos=EXPLODE("/",$codigos_resultados);
		  $vector_lecturas=EXPLODE("/",$valores_lecturas);
		  $vector_inter=EXPLODE("/",$valores_inter);
		  $vector_obser=EXPLODE("/",$valores_obser);
		  $vector_examenes=EXPLODE("/",$codigos_examenes); 
		  $vector_tabuladores=EXPLODE("/",$tabuladores);
		  $obj = new clsSolicitudesProcesadas;
		  $tamano_vector=count($vector_resultados);
		// echo $tamano_vector;
		  $pos=0;
		  $ban=0;
		  $ban1=1;
		   
	if (($tamano_vector-1)>0){
		for ($i=0; $i < $tamano_vector-1 ; $i++) //INSERTANDO ELEMENTOS
		{
			$consulta=$obj->valores_referencia($vector_examenes[$pos]);
			$row = mysql_fetch_array($consulta);
             						
			if ($obj->InsertarResultadoPlantillaA1($vector_examenes[$pos],$idsolicitud,$vector_codigos[$pos],$vector_resultados[$pos],$vector_lecturas[$pos],$idrecepcion,$vector_inter[$pos],$vector_obser[$pos],$responsable,$usuario,$vector_tabuladores[$pos],$lugar)==true)
			 { 
				if (($objdatos->CambiarEstadoDetalle($vector_codigos[$pos])==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true)){
					$ban1=0;
                                        
				}
				
			 }
			 else{
			     $ban=1;}
				 $pos=$pos+1;
		}// del for
		
	}

	if($ban==0){
        if($ban1==0)
		{
			echo ("Datos Guardados correctamente");
		}
		else{
			 echo ("Datos Guardados ");
		}
	}
	else
        {
		//echo $ban ."-".$ban1;
 		echo "Los resultados no pueden ser guardados consulte al Administrador...";
	}  
			
	//*************************************************************************
	break;
	
	case 4: //MOSTRAR PREVIAMENTE LOS RESULTADOS 
	      	$idsolicitud=$_POST['idsolicitud']; 
		$idexamen=$_POST['idexamen'];
		$responsable=$_POST['idempleado'];
		  $consulta=$objdatos->MostrarResultadoGenerales($idsolicitud,$idexamen);
		  $row = mysql_fetch_array($consulta);
		  $nombre=$row['NombreArea'];
		  
		  $Imprimir="
		
			<table width='90%' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					<td align='center' colspan='6' class='CobaltButton'>
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
						<p><strong>&Aacute;rea de ".htmlentities($nombre)." </strong></p>
						</td>
				</tr>
				<tr>
					<td colspan='6' align='center'></td>
				</tr>
				
				<tr>
					<td style='font:bold'><strong>NEC:</strong></td>
					<td style='font:bold'>".$row['IdNumeroExp']."</td>
					<td style='font:bold'><strong>Fecha:</strong></td>
					<td style='font:bold'>".$row['Fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/>
				<tr>
					<td style='font:bold'><strong>Paciente:</strong></td>
					<td style='font:bold'>".htmlentities($row['NombrePaciente'])."</td>			
					<td style='font:bold'><strong>Edad:</strong></td>
					<td style='font:bold'>".$row['Edad']."</td>
					<td style='font:bold'><strong>Sexo:</strong></td>
					<td style='font:bold'>".$row['Sexo']."</td>					
				</tr>
				</tr>	
					<td style='font:bold'><strong>Procedencia:</strong></td>
					<td style='font:bold'>".htmlentities($row['Procedencia'])."</td>
					<td style='font:bold'><strong>Origen:</strong></td>
					<td style='font:bold'>".htmlentities($row['Origen'])."</td>
				</tr>
				<tr>";
					$consulta_empleado=$objdatos->BuscarEmpleadoValidador($responsable);
					$fila_empleado = mysql_fetch_array($consulta_empleado);//$fila_empleado['NombreEmpleado'].
					$Imprimir.="
					<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
					<td  colspan='5' style='font:bold'>".htmlentities($fila_empleado['NombreEmpleado'])."</td>
				</tr>
				<tr >
				<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr >
				<td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
				</tr>
		</table>";
			
			$Imprimir.="
					<table width='90%'  align='center' border='0' class='StormyWeatherFormTABLE'>
						<tr class='CobaltButton'>
							<td align='center'>Prueba Realizada </td>
							<td align='center'>Resultado</td>
							<td align='center'>Unidades</td>
							<td align='center'>Rangos Normales </td>
							<td align='center'>Lectura</td>
							<td align='center'>Interpretaci&oacute;n</td>
							<td align='center'>Observaci&oacute;n</td>
							
						</tr>";
	
    //mysql_free_result($consulta_empleado);            
	//MOSTRAR DATOS FIJOS Y RESULTADOS DIGITADOS
			$consulta2=$objdatos->MostrarDatosFijosPlantillaA($idexamen,$lugar);
			$fila = mysql_fetch_array($consulta2);
			
			$Imprimir.="
						<tr>
							<td align='center' style='font:bold'><strong>".htmlentities($fila['NombreExamen'])."</strong></td>
							<td align='center'>".htmlentities($resultado)."</td>
							<td align='center'>".htmlentities($fila['Unidades'])."</td>
							<td align='justify'>".$fila['RangoInicio']."-".$fila['RangoFin']."</td>
							<td align='justify'>".htmlentities($lectura)."</td>
							<td align='justify'>".htmlentities($interpretacion)."</td>
							<td align='justify'>".htmlentities($observacion)."</td>
						</tr>"; 
			$Imprimir.="					
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>			
			
			</tr>
			<tr>
				<td colspan='7' align='center' >
					<input type='submit' id='btnGuardar' value='Guardar Resultados' Onclick='GuardarResultados();' />
					<input type='submit' id='btnImprimir' value='Imprimir' Onclick='ImprimirA() ;' />
					<input type='submit' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' />
				</td>
			</tr></table>		
		";
		echo $Imprimir;
		
		mysql_free_result($consulta2);
	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos-> ExamenesPorArea($idarea);	
		
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0">--Seleccione Examen--</option>';
			
		while ($rows =mysql_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
		
	
   break;	
   
   case 6:
     $total=0;
	 $pos=0;
	echo $idsolicitud;	
		
	//$total=$objdatos->ContarExamenes($idsolicitud,$idarea)
	 // $consulta=$objdatos->DatosExamenes($idsolicitud,$idarea);
	// while($row = mysql_fetch_array($consulta))
	// {
		 // $imprimir.= "<table width='90%'  align='center' border='0' class='StormyWeatherFormTABLE'>
						// <tr class='CobaltButton'>
							// <td align='center'>Prueba Realizada </td>
							// <td align='center'>Resultado</td>
							// <td align='center'>Unidades</td>
							// <td align='center'>Rangos Normales </td>
							// <td align='center'>Lectura</td>
							// <td align='center'>Interpretaci&oacute;n</td>
							// <td align='center'>Observaci&oacute;n</td>
							
						// </tr>
						// <tr>
							// <td width='30%' class='StormyWeatherDataTD'>".htmlentities($row['NombreExamen'])."</td>
							// <td width='30%' class='StormyWeatherDataTD'>
								// <input size='25' name='txtresultadosub[".$pos."]' type='text' id='txtresultado[".$pos."]'>
								// <input name='oiddetalle[".$pos."]' type='hidden' id='oiddetalle[".$pos."]' value='".$row['IdDetalleSolicitud']."'> 
							// </td>
							// <td width='30%' class='StormyWeatherDataTD'>".htmlentities($row['Unidades'])."</td>
							// <td width='30%' class='StormyWeatherDataTD'>".$row['RangoInicio']."-".$row['RangoFin']."</td>
							// <td width='30%' class='StormyWeatherDataTD'>
						// </tr>
						// ";
							// $pos=$pos + 1;
	// }
	break;
    	
}

?>