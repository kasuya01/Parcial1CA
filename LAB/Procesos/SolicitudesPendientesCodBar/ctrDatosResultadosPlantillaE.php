<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

include ("clsPlantillaE.php");

//variables POST
$opcion=$_POST['opcion'];

if (isset($_POST['procedencia'])){$procedencia= $_POST['procedencia'];}else{$procedencia="";}
if (isset($_POST['origen'])){$origen= $_POST['origen'];}else{$origen="";}

//creando los objetos de las clases
$obj = new clsPlantillaE;

switch ($opcion) 
{
case 1: 
	$idexamen=$_POST['idexamen'];
	$observacion= $_POST['observacion']; 
	$ConEstandar=$obj->Obtener_Estandar($idexamen);
	$CodEstandar= mysql_fetch_array($ConEstandar);
	$IdEstandar=$CodEstandar[0];
	switch ($IdEstandar){
	
		case "H15":   
			$consulta=$obj->LeerProcesoExamen($idexamen);
	
			$imprimir="
			<table width='86%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr class='CobaltButton'>
					<td > Prueba </td>
						<td> Resultado </td>
						<td> Unidades </td>
						<td colspan='2'> Control Diario </td>
				</tr>";
					$pos=0;
				while($row = mysql_fetch_array($consulta))//ELEMENTOS
				{  
				$imprimir.= "
				<tr>
					<td class='StormyWeatherFieldCaptionTD'>".$row['nombreprocedimiento']."</td>
					<td class='StormyWeatherDataTD' >
						<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimientoporexamen']."'>
						<input name='txtresultado[".$pos."]' type='text' id='txtresultado[".$pos."]'>
					</td>
					<td class='StormyWeatherDataTD'>".$row['unidades']."</td>
					<td class='StormyWeatherDataTD' >
						<input name='txtcomentario[".$pos."]' type='text' id='txtcomentario[".$pos."]'>".$row['unidades']."
					</td>					  
				</tr>";
					$pos=$pos + 1;					
				}
				mysql_free_result($consulta);
				$imprimir.="
				<tr><td class='StormyWeatherFieldCaptionTD'>*Resultado Tabulador</td>
					<TD colspan='3' class='StormyWeatherDataTD'>
						<select id='cmbTabulador' name='cmbTabulador' size='1'>
							<option value='0' >--Seleccione Resultado--</option>";
							$conResult=$obj->LlenarResultados();
							while ($rows =mysql_fetch_array($conResult)){
				$imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ". htmlentities($rows[1])."</option>";
							}
			$imprimir.="</select></TD></tr>";
							
		
				$imprimir.="
				<tr>
					<td colspan='5' align='right' class='StormyWeatherDataTD'>
						<input  type='hidden' id='oculto' value='".$pos."'>
						<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaE()'>
					</td>
				</tr>
				<tr>
					<td colspan='5' >&nbsp;</td>
				</tr>
            </table>";
	
		echo $imprimir;
		break;
  
		default:
   		$idexamen=$_POST['idexamen'];
		$observacion= $_POST['observacion']; 

		$consulta=$obj->LeerProcesoExamen($idexamen);
	    
		$imprimir="	
			<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr class='CobaltButton'>
					<td> Prueba </td>
					<td> Resultado </td>
					<td> Unidades </td>
					<td> Rango </td>
				</tr>";
					$pos=0;
					while($row = mysql_fetch_array($consulta))//ELEMENTOS
					{  
		$imprimir.= "
				<tr>
					<td class='StormyWeatherFieldCaptionTD'>".$row['nombreprocedimiento']."</td>
					<td class='StormyWeatherDataTD'>
						<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimientoporexamen']."'>
						<input name='txtresultado[".$pos."]' type='text' id='txtresultado[".$pos."]'>
					</td>
					<td class='StormyWeatherDataTD' >".$row['unidades']."</td>
					<td class='StormyWeatherDataTD' aligh='center'>".$row['rangoinicio']."-".$row['rangofin']."</td> 
						<input name='txtcomentario[".$pos."]' type='hidden' id='txtcomentario[".$pos."]'>
					</td>					  
				</tr>";
					$pos=$pos + 1;					
					}
					mysql_free_result($consulta);
					$imprimir.="
				<tr><td class='StormyWeatherFieldCaptionTD'>*Resultado Tabulador</td>
					<TD colspan='3' class='StormyWeatherDataTD'>
						<select id='cmbTabulador' name='cmbTabulador' size='1'>
							<option value='0' >--Seleccione Resultado--</option>";
							$conResult=$obj->LlenarResultados();
							while ($rows =mysql_fetch_array($conResult)){
				$imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ". htmlentities($rows[1])."</option>";
							}
			$imprimir.="</select></TD></tr>";
		$imprimir.="
				<tr>
					<td colspan='5'  align='right' class='StormyWeatherDataTD'>
						<input type='hidden' id='oculto' value='".$pos."'>
						<input type='button' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaE()'>
					</td>
				</tr>
				<tr>
					<td colspan='5' >&nbsp;</td>
				</tr>
		   </table>";
		echo $imprimir;
			//mysql_free_result($consulta);
		break;
		}
break;
   
case 2://vista Previa de Resultado
		$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
		$row_estab = mysql_fetch_array($Consulta_Estab);
		$idexamen=$_POST['idexamen'];//*
		//echo $idexamen;
		$idsolicitud= $_POST['idsolicitud'];
		$observacion= $_POST['observacion'];
		$idempleado= $_POST['idempleado'];
		if (isset($_POST['procedencia'])){$procedencia= $_POST['procedencia'];}else{$procedencia="";}
		if (isset($_POST['origen'])){$origen= $_POST['origen'];}else{$origen="";}
		$codigos= $_POST['codigos'];
		$valores= $_POST['valores'];
		$comentarios= $_POST['comentarios'];
		$establecimiento=$_POST['estab'];
		$tab=$_POST['tab'];
		//echo $tab;
		$ConEstandar=$obj->Obtener_Estandar($idexamen);
		$CodEstandar= mysql_fetch_array($ConEstandar);
		$IdEstandar=$CodEstandar[0];
		if ($IdEstandar=="H15"){   
			$consulta_datos=$obj->LeerDatos($idexamen);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud);
			$datos_empleado=$obj->DatosEmpleado($idempleado);
			$row_generales= mysql_fetch_array($datos_generales);
			$row_area = mysql_fetch_array($consulta_datos);
			$row_empleado = mysql_fetch_array($datos_empleado);
			$nombrearea=$row_area['NombreArea'];
			$vector_idprocesos=EXPLODE("/",$codigos);
			$vector_respuesta=EXPLODE("/",$valores);
			$vector_comentarios=EXPLODE("/",$comentarios);
			$imprimir="
			<table width='89%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
						<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
						 <p><strong>                       ".$row_estab['Nombre']."</strong></p>
						<p><strong>Area de ".htmlentities($row_area['NombreArea'])." </strong></p>
					</td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'>Establecimiento Solicitante:</td>
					<td colspan='2'>".$establecimiento."</td>
					<td colspan='1' style='font:bold'>Fecha Recepción:</td>
					<td colspan='2'>".$row_generales['Fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/>
				</tr>
				
				<tr>
					<td colspan='1' style='font:bold'>NEC</td>
					<td colspan='1'>".$row_generales['IdNumeroExp']."</td>
					<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
					<td colspan='3'>".htmlentities($row_generales['NombrePaciente'])."</td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
					<td colspan='1'><div id='divsuedad'>
          
    				  	    </div>
					</td>
					<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
					<td colspan='1'>".$row_generales['Sexo']."</td>
				</tr>
				<tr>	
					<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
					<td colspan='1' style='font:bold'>".htmlentities($row_generales['Procedencia'])."</td>
					<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
					<td colspan='1' style='font:bold'>".htmlentities($row_generales['Origen'])."</td>
				</tr>
				<tr>
					<td style='font:bold'><strong>Validado Por:</strong></td>
					<td colspan='2'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
				</tr>
				<tr>
					<td style='font:bold'><strong>Examen Realizado:</strong></td>
					<td colspan='4'style='font:bold'>".htmlentities($row_area['NombreExamen'])."</td>
				</tr>				 
				<tr>
					<td style='font:bold'><strong>Observacion:<strong></td>
					<td colspan='4'>".htmlentities($observacion)."</td>
				</tr>	";
				mysql_free_result($consulta_datos);
				mysql_free_result($datos_generales);
				$nomcod=$obj->ObtenerNombreCodigo($tab);
                $row_codigo= mysql_fetch_array($nomcod);
									//	echo $row_codigo[0];
	  $imprimir.=" <tr><td style='font:bold' >Resultado Tabulador:</td><td colspan='3'>".$row_codigo[0]."</td></tr>";
				$consulta=$obj->LeerProcesoExamen($idexamen);
                	
				$imprimir.="
				<tr><td colspan='6'>
					<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE' >
					<tr class='CobaltButton'>
						<td > Prueba </td>
						<td > Resultado </td>
						<td > Unidades </td>
						<td colspan='2' > Control Diario </td>
					</tr>";
					$pos=0;
	
					while($row = mysql_fetch_array($consulta))//ELEMENTOS
					{  
			    $imprimir.="<tr>
						<td>".htmlentities($row['nombreprocedimiento'])."</td>
						<td align='center'>
							<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimientoporexamen']."'>".htmlentities($vector_respuesta[$pos]).					  
					  "	</td>
						<td>".$row['unidades']."</td>
						<td align='center'>".htmlentities($vector_comentarios[$pos])."</td>					  
						<td>".htmlentities($row['unidades'])."</td>
					</tr>";
					  $pos=$pos + 1;		 
					}
					mysql_free_result($consulta);
					
	                     $imprimir.="<tr>
						<td colspan='4' align='rigth'>
							<input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarPlantillaE()'>
							<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaE(".$idsolicitud.",\"".$idexamen."\",\"".htmlentities($row_empleado['NombreEmpleado'])."\",\"".htmlentities($procedencia)."\",\"".htmlentities($row_generales['Origen'])."\",\"".htmlentities($comentarios)."\",\"".htmlentities($valores)."\",\"".$codigos."\",\"".htmlentities($observacion)."\") ;'>
							<input type='button' id='btnSalir' value='cerrar' onclick='Cerrar()'>
						</td>
					</tr>
					</table></td>	
				</tr>";
	$imprimir.="</table>";
		
   echo $imprimir;

}else{	
	
	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud);
	$datos_empleado=$obj->DatosEmpleado($idempleado);
	$row_generales= mysql_fetch_array($datos_generales);
	$row_area = mysql_fetch_array($consulta_datos);
	$nombrearea=$row_area['NombreArea'];
	$row_empleado = mysql_fetch_array($datos_empleado);
	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
	$vector_idprocesos=EXPLODE("/",$codigos);
	$vector_respuesta=EXPLODE("/",$valores);
	
	//echo $idsolicitud." - ".$idexamen." - ".$idempleado." - ".$observacion;
	$imprimir=" <table width='89%' border='0' align='center' class='StormyWeatherFormTABLE'>
				
					<tr>
						<td align='center' colspan='6' class='CobaltFieldCaptionTD'>
							<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong>                       ".$row_estab['Nombre']."</strong></p>
							<p><strong>Area de ".htmlentities($row_area['NombreArea'])." </strong></p>
						</td>
					</tr>
					<tr>
						<td colspan='1' style='font:bold'>Establecimiento Solicitante:</td>
						<td colspan='2'>".$establecimiento."</td>
						<td colspan='1'><strong>Fecha Recepción:</strong></td>
						<td colspan='2'>".$row_generales['Fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/>
					</tr>
					
					<tr>
						<td colspan='1'><strong>NEC</strong></td>
						<td colspan='1'>".$row_generales['IdNumeroExp']."</td>
						<td colspan='1'><strong>Paciente:</strong></td>
						<td colspan='3'>".htmlentities($row_generales['NombrePaciente'])."</td>
					</tr>
					<tr>
						<td colspan='1'><strong>Edad:</strong></td>
						<td colspan='1'>
							<div id='divsuedad'>
          
    				  		</div>
						</td>
						<td colspan='1'><strong>Sexo:</strong></td>
						<td colspan='1'>".$row_generales['Sexo']."</td>
					</tr>
					<tr>	
						<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
						<td colspan='1' style='font:bold'>".$row_generales['Procedencia']."</td>
						<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
						<td colspan='1' style='font:bold'>".$row_generales['Origen']."</td>
					</tr>
					<tr>
						<td colspan='1' style='font:bold'><strong>Examen Realizado:</strong></td>
						<td colspan='4'style='font:bold'>".htmlentities($row_area['NombreExamen'])."</td>
					</tr>				 
					<tr>
						<td colspan='1'><strong>Validado Por</strong></td>
						<td colspan='3'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
					</tr>
					<tr>
					<td colspan='1' style='font:bold'><strong>Observacion<strong></td>
					<td colspan='4'>".htmlentities($observacion)."</td>
				</tr>	";
					mysql_free_result($consulta_datos);
					mysql_free_result($datos_generales);
			$nomcod=$obj->ObtenerNombreCodigo($tab);
                $row_codigo= mysql_fetch_array($nomcod);
									//	echo $row_codigo[0];
	  $imprimir.=" <tr><td style='font:bold' >Resultado Tabulador:</td><td colspan='4'>".$row_codigo[0]."</td></tr>";		
					$consulta=$obj->LeerProcesoExamen($idexamen);
	
	$imprimir.="<table width='89%' border='0' align='center' class='StormyWeatherFormTABLE'>
						<tr class='CobaltButton'>
							<td > Prueba </td>
							<td > Resultado </td>
							<td > Unidades </td>
							<td > Rango </td>
						</tr>";
						$pos=0;
	
						while($row = mysql_fetch_array($consulta))//ELEMENTOS
						{  
	$imprimir.= "<tr>
							<td>".htmlentities($row['nombreprocedimiento'])."</td>
							<td>
								<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimientoporexamen']."'>".htmlentities($vector_respuesta[$pos]).					  
					  "		</td>
							<td>".htmlentities($row['unidades'])."</td>
							<td align='center'>".$row['rangoinicio']."-".$row['rangofin']."</td>				  
						</tr>";
						  $pos=$pos + 1;		 
						}
						mysql_free_result($consulta);
   $imprimir.="</table>
					</tr>	
					<tr align='rigth'>
						<td colspan='5' align='rigth'>
							<input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarPlantillaE()'>
							<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaE(".$idsolicitud.",\"".$idexamen."\",\"".htmlentities($row_empleado['NombreEmpleado'])."\",\"".htmlentities($row_generales['Procedencia'])."\",\"".htmlentities($row_generales['Origen'])."\",\"".htmlentities($comentarios)."\",\"".htmlentities($valores)."\",\"".$codigos."\",\"".htmlentities($observacion)."\",\"".htmlentities($establecimiento)."\") ;'>
							<input type='button' id='btnSalir' value='cerrar' onclick='Cerrar()'>
						</td>
				        </tr>";
	$imprimir.="</table>";
		
   echo $imprimir;
   }
   break;
   
   case 3://guardar resultados
	$idexamen=$_POST['idexamen'];//
	$idsolicitud= $_POST['idsolicitud'];
	$idrecepcion= $_POST['idrecepcion'];
	$iddetalle= $_POST['iddetalle'];
	$observacion= $_POST['observacion'];
	$idempleado= $_POST['idempleado'];
	$tab=$_POST['tab'];
	//echo $tab;
	//echo "SOLIC".$idsolicitud."*RECEP= ".$idrecepcion."*DETALLE= ".$iddetalle."*observ= ".$observacion." *Empleado= ".$idempleado."* tab=".$tab ;
	
	$codigos= $_POST['codigos'];
	$valores= $_POST['valores'];
	$comentarios= $_POST['comentarios'];
    	
	$vector_respuesta=EXPLODE("/",$valores);
	$vector_idprocesos=EXPLODE("/",$codigos);
	$vector_comentarios=EXPLODE("/",$comentarios);
	$obj = new clsPlantillaE;
   	$tamano_vector=count($vector_respuesta);
		//$tamano_vectorcodigos=count($vector_idprocesos);
		//$tamano_vectorcomentario=count($vector_comentarios);
	
	   //VALIDANDO QUE LA INFORMACION ESTE COMPLETA:	
	$ultimo= $obj->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$idempleado,$usuario,$tab,$lugar);
   
		$pos=0;
		$ban=0;
		if ($ultimo != "")
		{
			$idresultado=$ultimo;
			if (($tamano_vector-1)>0){
				for ($i=0; $i < $tamano_vector-1 ; $i++) //INSERTANDO PROCEDIMIENTOS
				{
					if ($obj->insertar_detalle($idresultado,$vector_idprocesos[$pos],$vector_respuesta[$pos],$vector_comentarios[$pos],$lugar)==false)
						
					{ 
						$ban=1;
					}
					$pos=$pos+1;
				}
			}	
			if($ban==0)
			{
				echo ("Datos Guardados");
				//cambia el estado del detalle de la solicitud que la respuesta ha sido ingresada RC
				if (($obj->CambiarEstadoDetalle($iddetalle)==true)&&($obj->CambiarEstadoSolicitud($idsolicitud,$lugar)==true))
				{    
					echo " Correctamente";
				}
			}
			else
			{
				echo "Los resultados no pueden ser guardados consulte al Administrador...";
			}
	
		}     	
		else{ echo "Los resultados no pueden ser guardados consulte al Administrador...";}  
   break;
   
  }

?>