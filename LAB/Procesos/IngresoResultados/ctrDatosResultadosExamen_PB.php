<?php session_start();
include ("clsConsultarElementos.php");

$usuario = $_SESSION['Correlativo'];
$lugar 	 = $_SESSION['Lugar'];
$area 	 = $_SESSION['Idarea'];

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsConsultarElementos;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
$row_estab = pg_fetch_array($Consulta_Estab);

switch ($opcion) {
	case 1:
		$idexamen = $_POST['idexamen'];
		$fechanac = $_POST['FechaNac'];
		$sexo 	  = $_POST['Sexo'];
                $fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];
                
               // echo $fecharealiz."-".$fecharesultado;
                $ConRangos       = $objdatos->ObtenerCodigoRango($fechanac);
		$row_rangos      = pg_fetch_array($ConRangos);
		$idedad          = $row_rangos[0];
		$ConEstandar     = $objdatos->Obtener_Estandar($idexamen);
		$CodEstandar     = pg_fetch_array($ConEstandar);
		$codigo_estandar = $CodEstandar[0];
		$IdEstandar	 = $CodEstandar[1];
               
		switch ($codigo_estandar) {
			case "H50":
				$consulta = $objdatos->LeerElementosExamen($idexamen,$lugar);

				$imprimir="<table width='75%' border='0' align='center' class='StormyWeatherFormTABLE'>";
				$imprimir.="	<tr class='CobaltButton'>
                                                    <td width='30%'> &nbsp;</td>
                                                    <td width='30%' aling='center'>Resultado</td>
                                                    <td width='5%' aling='center'>Unidades</td>
                                                    <td width='30%' colspan='2'aling='center'>Control Normal </td>
						</tr>";
				$pos=0;
				$posele=0;
			   	while($row = pg_fetch_array($consulta)) { //ELEMENTOS
			   		if($row['subelemento'] === "S") {
			   	$imprimir.="<tr>
			   			<td colspan='5' style='font:bold' class='StormyWeatherFieldCaptionTD'>".htmlentities($row['elemento'])."</td>
                                    	</tr>";
			   			
			   			$consulta2=$objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);
						while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
							$imprimir.= "<tr>
									<td width='30%' class='StormyWeatherDataTD'>".htmlentities($rowsub['subelemento'])."</td>
									<td width='30%' class='StormyWeatherDataTD'>
										<input size='20' name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]'>
										<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'>
									</td>
									<td width='5%' class='StormyWeatherDataTD'>".htmlentities($rowsub['unidad'])."</td>
									<td width='30%' class='StormyWeatherDataTD'>
										<input name='txtcontrol[".$pos."]' type='text' id='txtcontrol[".$pos."]'>
									</td>
									<td class='StormyWeatherDataTD'>".htmlentities($rowsub['unidad'])."</td>
								</tr>";
							$pos=$pos + 1;
						}
                                                     
						pg_free_result($consulta2);
						$imprimir.="<tr>
								<td colspan='5' class='StormyWeatherDataTD'>".htmlentities($row['observelem'])."</td>
							</tr>
							<tr>
								<td colspan='5' class='StormyWeatherDataTD'>&nbsp;</td>
							</tr>";
					} else {
					$imprimir.="<tr>
								<td class='StormyWeatherFieldCaptionTD' style='font:bold'>".htmlentities($row['elemento'])."</td>
								<td class='StormyWeatherDataTD'>
									<input size='20' name='txtresultadoele[".$posele."]' type='text' id='txtresultadoele[".$posele."]'>
									<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['idelemento']."'>
								</td>
								<td class='StormyWeatherDataTD'>".htmlentities($row['unidadelem'])."</td>
								<td class='StormyWeatherDataTD' width='30%'><input name='txtcontrolele[".$posele."]' type='text' id='txtcontrolele[".$posele."]'></td>
								<td width='10%' class='StormyWeatherDataTD'>".htmlentities($row['unidadelem'])."</td>
							</tr>";
                                               
						$posele=$posele + 1;
						$imprimir.= "<tr>
								<td rowspan='1' colspan='5' class='StormyWeatherDataTD'>".htmlentities($row['observelem'])."</td>
							</tr>
							<tr>
								<td colspan='5' class='StormyWeatherDataTD'>&nbsp;</td>
							</tr>";
					}
				}
				
				pg_free_result($consulta);
                                $imprimir.= "  <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />";
				$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>";	//numero de cajas de texto dibujadas para subelementos
				$imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>"; //elementos
                                 
				$imprimir.="<tr>
						<td colspan='2'  class='StormyWeatherDataTD'>*Resultado Tabulador</td>
						<td colspan='3' class='StormyWeatherDataTD'>
							<select id='cmbTabulador' name='cmbTabulador' size='1'>
								<option value='0' >Seleccione Resultado...</option>";
									$conResult=$objdatos->LlenarResultados($IdEstandar);
									while ($rows =pg_fetch_array($conResult)) {
										$imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ". htmlentities($rows[1])."</option>";
									}
				$imprimir.="</select></td></tr>";
                                
				$imprimir.="<tr>
						<td colspan='5'><input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaB()'></td>
					</tr>
				</table>";
				
				echo $imprimir;
				break;
			default:
				$consulta=$objdatos->LeerElementosExamen($idexamen,$lugar);
				$imprimir="<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
					<tr  class='CobaltButton'>
						<td>&nbsp;</td>
						<td aling='center'>Resultado</td>
						<td aling='center'>Unidades</td>
						<td aling='center'>Rangos de Referencia</td>
					</tr>";
				$pos=0;
				$posele=0;
				while($row = pg_fetch_array($consulta)) { //ELEMENTOS
					if($row['subelemento']=="S") {
						$imprimir.= "
							<tr class='StormyWeatherFieldCaptionTD'>
								<td colspan='4' style='font:bold'>".htmlentities($row['elemento'])."</td>
							</tr>";
						$consulta2=$objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);

						while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
							$imprimir.= "
								<tr>
									<td width='30%' class='StormyWeatherDataTD'>".htmlentities($rowsub['subelemento'])."</td>
									<td width='25%' class='StormyWeatherDataTD'>
										<input size='30' name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]'>
										<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'>
									</td>
									<td width='20%' align='center' class='StormyWeatherDataTD'>".htmlentities($rowsub['unidad'])."
									<td width='20%' align='center' class='StormyWeatherDataTD'>".$rowsub['rangoinicio']." - ".$rowsub['rangofin']."
										<input name='txtcontrol[".$pos."]' type='hidden' id='txtcontrol[".$pos."]'>
									</td>
								</tr>";
							$pos=$pos + 1;
						} //while interno
						$imprimir.= "<tr>
								<td colspan='4' class='StormyWeatherDataTD'>".htmlentities($row['observelem'])."</td>
							</tr>
							<tr>
								<td colspan='4' class='StormyWeatherDataTD'>&nbsp;</td>
							</tr>";
					} else {
						$imprimir.="<tr>
								<td style='font:bold'>".htmlentities($row['elemento'])."</td>
								<td>
									<input name='txtresultadoele[".$posele."]' type='text' id='txtresultadoele[".$posele."]'>
									<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['idelemento']."'>
								</td>
								<td class='StormyWeatherDataTD'>".htmlentities($row['unidadelem'])."
									<input name='txtcontrol[".$posele."]' type='hidden' id='txtcontrol[".$posele."]'>
								</td>
							</tr>";
						$posele=$posele+1;
						$imprimir.= "
							<tr>
								<td colspan='4' class='StormyWeatherDataTD'>".htmlentities($row['observelem'])."</td>
							</tr>
							<tr>
								<td colspan='4' class='StormyWeatherDataTD'>&nbsp;</td>
							</tr></table>";
					}//else
				}//while externo
                                
				pg_free_result($consulta);
                                 $imprimir.= "  <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />";
				$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
				$imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
				$imprimir.="<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
					<tr>
						<td class='StormyWeatherDataTD'>*Resultado Tabulador</td>
						<td colspan='3' class='StormyWeatherDataTD'>
							<select id='cmbTabulador' name='cmbTabulador' size='1'>
								<option value='0' >--Seleccione Resultado--</option>";
									$conResult=$objdatos->LlenarResultados($IdEstandar);
									while ($rows =pg_fetch_array($conResult)) {
										$imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ".htmlentities($rows[1])."</option>";
									}
				$imprimir.="</select></td></tr></table>
					<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
						<tr class='StormyWeatherDataTD'>
							<td colspan='3' class='StormyWeatherDataTD'>
								<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaB()'>
							</td>
						</tr>
					</table>";
				echo $imprimir;
				break;
		}
		break;
   	case 2: //MOSTRANDO VISTA PREVIA DE LOS RESULTADOS INGRESADOS
   		//var_dump($_POST);
	   	$idexamen	      = $_POST['idexamen'];//*
	   	$idsolicitud 	      = $_POST['idsolicitud'];
	   	$idrecepcion 	      = $_POST['idrecepcion'];
	   	$iddetalle 	      = $_POST['iddetalle'];
	   	$observacion 	      = $_POST['observacion'];
	   	$idempleado	      = $_POST['idempleado'];
	   	$valores_subelementos = $_POST['valores_subelementos'];
	   	$codigos_subelementos = $_POST['codigos_subelementos'];
	   	$valores_elementos    = $_POST['valores_elementos'];
	   	$codigos_elementos    = $_POST['codigos_elementos'];
	   	$controles 	      = $_POST['controles'];
	   	$controles_ele 	      = $_POST['controles_ele'];
	   	$establecimiento      = $_POST['estab'];
	   	$tab 		      = $_POST['tab'];
	   	$fechanac 	      = $_POST['fechanac'];
	   	$sexo 		      = $_POST['sexo'];
                $fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];
               // echo $fecharealiz."-".$fecharesultado;
	   	$Consulta_Estab  = $objdatos->Nombre_Establecimiento($lugar);
	   	$row_estab   	 = pg_fetch_array($Consulta_Estab);
	   	$ConEstandar 	 = $objdatos->Obtener_Estandar($idexamen);
	   	$CodEstandar 	 = pg_fetch_array($ConEstandar);
	   	$codigo_estandar = $CodEstandar[0];
		$IdEstandar	 = $CodEstandar[1];

	   	$ConRangos=$objdatos->ObtenerCodigoRango($fechanac);
	   	$row_rangos=  pg_fetch_array($ConRangos);
	   	$idedad=$row_rangos[0];

	   	switch ($codigo_estandar) {
	   		case "H50":
		   		$cadena 		  	  = $valores_subelementos;
		   		$vector 		  	  = EXPLODE("/",$cadena);
		   		$vector_elementos 	  = EXPLODE("/",$valores_elementos);
		   		$vector_controles 	  = EXPLODE("/",$controles);
		   		$vector_controles_ele = EXPLODE("/",$controles_ele);
		   		$objdatos 		  = new clsConsultarElementos;
		   		$consulta 		  = $objdatos->LeerElementosExamen($idexamen,$lugar);
		   		$consulta_datos 	  = $objdatos->LeerDatos($idexamen);
		   		$datos_generales 	  = $objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
		   		$datos_empleado 	  = $objdatos->DatosEmpleado($idempleado,$lugar);
                                //$datos_rep                =$objdatos->NombreResultado($idexamen);
		   		$row_generales 		  = pg_fetch_array($datos_generales);
		   		$row_area  		  = pg_fetch_array($consulta_datos);
		   		$row_empleado 		  = pg_fetch_array($datos_empleado);
                                echo $row_area['nombre_reporta'];
	   			$imprimir="<table width='92%' align='center' >
				   		<tr>
				   			<td colspan='1' align='left' width='20%'><img id='Image1' style='width: auto; height: 55px;' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
				   			<td align='center' colspan='4' width='60%' class='Estilo5'>
				   				<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				   				<p><strong>".htmlentities($row_estab['nombre'])."</strong></p>
				   				<p><strong>ÁREA DE ".htmlentities($row_area['nombrearea'])." </strong></p>
				   			</td>
				   			<td colspan='1' align='right' width='20%'><img id='Image3' style='width: 110px; height: auto;' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
				   		</tr>
				   		<tr>
				   			<td colspan='1'><strong>Establecimiento Solicitante:</strong></td>
				   			<td colspan='2'>".htmlentities($establecimiento)."</td>
				   			<td colspan='1'><strong>Fecha Recepción:</strong></td>
				   			<td colspan='2'>".$row_generales['fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['fechanacimiento']."'/>
				   		</tr>
				   		<tr>
				   			<td colspan='1'><strong>N&uacute;mero de Expediente:</strong></td>
				   			<td colspan='2'>".$row_generales['idnumeroexp']."</td>
				   		</tr>
			   			<tr>    
                                                        <td colspan='1'><strong>Paciente:</strong></td>
			   				<td colspan='5'>".$row_generales['nombrepaciente']."</td>
			   			</tr>
		   				<tr>
			   				<td colspan='1'><strong>Edad:</strong></td>
			   				<td colspan='2'><div id='divsuedad'></div></td>
			   				<td colspan='1'><strong>Sexo:</strong></td>
			   				<td colspan='2'>".$row_generales['sexo']."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Procedencia:</strong></td>
			   				<td colspan='2'>".$row_generales['procedencia']."</td>
			   				<td colspan='1'><strong>Servicio:</strong></td>
			   				<td colspan='2'>".$row_generales['origen']."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Examen Realizado:</strong></td>
			   				<td colspan='5'>".htmlentities($row_area['nombre_reporta'])."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Validado Por:</strong></td>
			   				<td colspan='5'>".htmlentities($row_empleado['nombreempleado'])."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Observacion:</strong></td>
			   				<td colspan='4'>".htmlentities($observacion)."</td>
			   			</tr>";
	   			
	   			$nomcod = $objdatos->ObtenerNombreCodigo($tab);
	   			$row_codigo = pg_fetch_array($nomcod);
                                 $imprimir.="<tr>
		        			<td>Resultado Tabulador:</td><td colspan='5'>".$row_codigo[0]."</td>
		        		</tr>
		        	</table>";
	   			

			   	$imprimir.="<table width='92%' border='0' align='center' >";
			   	pg_free_result($consulta_datos);
			   	pg_free_result($datos_generales);
			   	$imprimir.="<tr >
			   			<td width='35%'></td>
			   			<td width='25%'>Resultado</td>
			   			<td width='20%'>Unidades</td>
			   			<td width='60%'colspan='2'>Control Normal </td>
			   		</tr>";
			   
			   	$pos    = 0;
			   	$posele = 0;
				
				while($row = pg_fetch_array($consulta)) { //ELEMENTOS
					if($row['subelemento']=="S") {
						$imprimir.= "
							<tr >
								<td colspan='5' style='font:bold'><strong>".htmlentities($row['elemento'])."</strong></td>
							</tr>";
						$consulta2 = $objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);

						while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
							$imprimir.=
								"<tr>
									<td width='35%'>".htmlentities($rowsub['subelemento'])."</td>
									<td width='25%'>".htmlentities($vector[$pos]).
										"<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'>
									</td>
									<td width='20%'>".htmlentities($rowsub['unidad'])."</td>
									<td width='40%'>".htmlentities($vector_controles[$pos])." ".htmlentities($rowsub['unidad'])." </td>
								</tr>";
							$pos = $pos + 1;
						}

						$imprimir.= "
							<tr>
								<td colspan='4'>".htmlentities($row['observelem'])."</td>
							</tr>
							<tr>
								<td colspan='5'>&nbsp;</td>
							</tr>";
					} else {
						$imprimir.= "<tr>
								<td width='40%' style='font:bold' ><strong>".htmlentities($row['elemento'])."</strong></td>
								<td  width='25%'>".htmlentities($vector_elementos[$posele])."<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['idelemento']."'></td>
								<td width='10%'>".htmlentities($row['unidadelem'])."</td>
								<td>".htmlentities($vector_controles_ele[$posele])."  ".htmlentities($row['unidadelem'])."</td>
							</tr>";
						
						$posele=$posele+1;
						$imprimir.= "
							<tr>
								<td colspan='5'>".htmlentities($row['observelem'])."</td>
							</tr>
							<tr>
								<td colspan='6'>&nbsp;
								</td>
							</tr>";
					}

				}
				
				pg_free_result($consulta);
	            $imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
	            $imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>"; //elementos
                    $imprimir.="<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' value='".$fecharealiz."'>
                                 <input type='hidden' name='txtfresultado' id='txtfresultado' value='".$fecharesultado."' />";
	            $imprimir .="<td colspan='3' align='right'>
			                    <input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaB()'>
			                    <input type='button' name='Imprimir'  id='Imprimir' value='Imprimir'
			                    	Onclick='ImprimirPlantillaB(".$idsolicitud.",".$idexamen.",".$idempleado.",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['origen'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities( $establecimiento)."\",\"".htmlentities($row_empleado['nombreempleado'])."\",".$sexo.",\"".$idedad."\") ;'>
			                    <input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
			                </td>
			            </tr>
			        </table>";
	        
	        	echo $imprimir;
	        	break;
		    default:
		        $cadena 		  = $valores_subelementos;
		        $vector 		  = EXPLODE("/",$cadena);
		        $vector_elementos = EXPLODE("/",$valores_elementos);
				//$obj 			  = new clsConsultarElementos;
		        $consulta 		  = $objdatos->LeerElementosExamen($idexamen,$lugar);
		        $consulta_datos   = $objdatos->LeerDatos($idexamen);
		        $datos_generales  = $objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
		        $datos_empleado   = $objdatos->DatosEmpleado($idempleado,$lugar);
		        $row_generales 	  = pg_fetch_array($datos_generales);
		        $row_area 	  = pg_fetch_array($consulta_datos);
		        $row_empleado 	  = pg_fetch_array($datos_empleado);
		        $nombreEmpleado   = $row_empleado['nombreempleado'];

		        $imprimir="<br>
		        	<table width='95%' border='0' align='center' class='StormyWeatherFormTABLE'>
		        		<tr>
			        		<td colspan='1' align='left' width='20%'><img id='Image1' style='width: auto; height: 55px;' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
			        		<td align='center' colspan='4' width='60%' class='Estilo5'>
			        			<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
			        			<p><strong>".$row_estab['nombre']."</strong></p>
			        			<p><strong>&Aacute;REA DE ".htmlentities($row_area['nombrearea'])." </strong></p>
			        		</td>
			        		<td colspan='1' align='right' width='20%'><img id='Image3' style='width: auto; height: 55px;' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
		        		</tr>
		        		<tr>
		        			<td colspan='1'><strong>Establecimiento Solicitante:</strong></td>
		        			<td colspan='2'>".htmlentities($establecimiento)."</td>
		        			<td colspan='1'><strong>Fecha Recepción:</strong></td>
		        			<td colspan='2'>".$row_generales['fecha']."</td>
		        			<input name='suEdad' id='suEdad' type='hidden' value='".$row_generales['fechanacimiento']."'/>
		        		</tr>
		        		<tr>
		        			<td colspan='1'><strong>N&uacute;mero de Expediente:</strong></td>
		        			<td colspan='1'>".$row_generales['idnumeroexp']."</td>
		        		</tr>
		        		<tr>
		        			<td colspan='1'><strong>Paciente:</strong></td>
		        			<td colspan='2'>".htmlentities($row_generales['nombrepaciente'])."</td>
		        		</tr>
		        		<tr>

		        			<td colspan='1'><strong>Edad:</strong></td>
		        			<td colspan='2'><div id='divsuedad'></div></td>
		        			<td colspan='1'><strong>Sexo:</strong></td>
		        			<td colspan='2'>".$row_generales['sexo']."</td>
		        		</tr>
		        		<tr>
		        			<td colspan='1'><strong>Procedencia:</strong></td>
		        			<td colspan='2'>".htmlentities($row_generales['procedencia'])."</td>
		        			<td colspan='1'><strong>Servicio:</strong></td>
		        			<td colspan='2'>".htmlentities($row_generales['origen'])."</td>
		        		</tr>
		        		<tr>
		        			<td colspan='1'><strong>Examen Realizado:</strong></td>
		        			<td colspan='5'>".htmlentities($row_area['nombreexamen'])."</td>
		        		</tr>

		        		<tr>
		        			<td colspan='1'><strong>Validado Por:</strong></td>
		        			<td colspan='5'>".htmlentities($row_empleado['nombreempleado'])."</td>
		        		</tr>
		        		<tr>
		        			<td colspan='1'><strong>Observacion:</strong></td>
		        			<td colspan='5'>".htmlentities($observacion)."</td>
		        		</tr>";
		        
		        $nomcod=$objdatos->ObtenerNombreCodigo($tab);
		        $row_codigo= pg_fetch_array($nomcod);
		        $imprimir.="<tr>
		        			<td>Resultado Tabulador:</td><td colspan='5'>".$row_codigo[0]."</td>
		        		</tr>
		        	</table>";

		        $imprimir.="<table width='95%' border='0' align='center' >";
		        $imprimir.= "<tr>
				        <td width='25%'></td>
				        <td width='25%'>Resultado</td>
				        <td width='10%'>Unidades</td>
				        <td width='15%'>Rangos de referencia</td>
				    </tr>";
		    	
		    	$pos    = 0;
		    	$posele = 0;
				
            while($row = pg_fetch_array($consulta)) { //ELEMENTOS
                if($row['subelemento']=="S") {
			$imprimir.= "<tr>
					<td colspan='4' style='font:bold'><strong>".htmlentities($row['elemento'])."</strong></td>
		  		     </tr>";
					$consulta2 = $objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);
		    while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
  			 $imprimir.="<tr>
					<td width='35%'>".htmlentities($rowsub['subelemento'])."</td>
					<td width='25%'>".htmlentities($vector[$pos])."<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'></td>
					<td width='10%'>".htmlentities($rowsub['unidad'])."</td>";
			if (empty($rowsub['rangoinicio']) AND empty($rowsub['rangofin']))
			    $imprimir.= "<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			else
			    $imprimir.= "<td width='15%'>".$rowsub['rangoinicio']." - ".$rowsub['rangofin']."</td> ";
			  $imprimir.="</tr>";
					$pos = $pos + 1;
		    }
						
			 $imprimir.= "<tr>
			 		 <td colspan='5'>".htmlentities($row['observelem'])."</td>
				      </tr>";
		}else {
			$imprimir.= "<tr>
			 		 <td style='font:bold'  class='StormyWeatherFieldCaptionTD'>".htmlentities($row['elemento'])."</td>
					 <td>".htmlentities($vector_elementos[$posele])."<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['idelemento']."'>			  </td>
					 <td width='25%'>".htmlentities($row['unidadelem'])."</td>
				     </tr>";
						
						$posele = $posele+1;
						$imprimir.= "<tr>
								<td colspan='5'>".htmlentities($row['observelem'])."</td>
							</tr>";
		 }
	    }

		pg_free_result($consulta);
		pg_free_result($consulta_datos);
		pg_free_result($datos_generales);

				$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
				$imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
                                 $imprimir.="<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' value='".$fecharealiz."'>
                                             <input type='hidden' name='txtfresultado' id='txtfresultado' value='".$fecharesultado."' />";
				$imprimir.="<tr>
							<td colspan='5' align='center' >
								<input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaB()'>
								<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir'
									Onclick='ImprimirPlantillaB(".$idsolicitud.",".$idexamen.",".$idempleado.",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['origen'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities( $establecimiento)."\",\"".htmlentities($row_empleado['nombreempleado'])."\",".$sexo.",\"".$idedad."\") ;'>
								<input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
							</td>
						</tr>
					</table>";
			echo $imprimir;
			break;
		}
		break;
	case 3://GUARDANDO DATOS DE LOS RESULTADOS EN LAS TABLAS
		$idexamen 			  = $_POST['idexamen'];
		$idsolicitud 		  = $_POST['idsolicitud'];
		$idrecepcion 		  = $_POST['idrecepcion'];
		$iddetalle 			  = $_POST['iddetalle'];
		$procedencia 		  = $_POST['procedencia'];
		$origen 			  = $_POST['origen'];
		$observacion 		  = $_POST['observacion'];
		$idempleado 		  = $_POST['idempleado'];
		$valores_subelementos = $_POST['valores_subelementos'];
		$codigos_subelementos = $_POST['codigos_subelementos'];
		$valores_elementos 	  = $_POST['valores_elementos'];
		$codigos_elementos 	  = $_POST['codigos_elementos'];
		$controles 			  = $_POST['controles'];
		$controles_ele 		  = $_POST['controles_ele'];
		$vector 			  = EXPLODE("/",$valores_subelementos); //SON LOS SUB-ELEMENTOS
		$vector_elementos 	  = EXPLODE("/",$valores_elementos);
		$v_id_elementos 	  = EXPLODE("/",$codigos_elementos);
		$v_id_subelementos 	  = EXPLODE("/",$codigos_subelementos);
		$tamano_vector 		  = count($vector);
		$tamano_vectorele 	  = count($vector_elementos);
		$vector_controles 	  = EXPLODE("/",$controles);
		$vector_controles_ele = EXPLODE("/",$controles_ele);
		$tab 				  = $_POST['tab'];
  		$fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];
               //
               //  echo $fecharealiz."-".$fecharesultado;
  		//VALIDANDO QUE LA INFORMACION ESTE COMPLETA:
		$ultimo = $objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$idempleado,$usuario,$tab,$fecharealiz,$fecharesultado,$lugar);
              //  echo $ultimo;
		$pos = 0;
		$posele = 0;
		$ban = 0;
		if ($ultimo != "" && $ultimo != false) { //INSERTANDO ELEMENTOS
			$idresultado = $ultimo;
			
			if (($tamano_vectorele-1) > 0) {
				for ($i=0; $i < $tamano_vectorele-1 ; $i++) {
					if ($objdatos->insertar_elementos($idresultado,$v_id_elementos[$posele],$vector_elementos[$posele],$vector_controles_ele[$posele],$lugar)==false) {
						$ban = 1;
					}
					
					$posele = $posele+1;
				}
			}
			
			if (($tamano_vector-1) > 0) {
				for ($i=0; $i < $tamano_vector-1 ; $i++) { //INSERTANDO SUB-ELEMENTOS
					if ($objdatos->insertar_subelementos($idresultado,$v_id_subelementos[$pos],$vector[$pos],$vector_controles[$pos],$lugar)==false) {
						$ban=1;
					}
					
					$pos = $pos+1;
				}
			}
			
			if($ban == 0) {
				echo ("Datos Guardados");
				
				//cambia el estado del detalle de la solicitud que la respuesta ha sido ingresada RC
				if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true)) {
					echo " Correctamente";
				}
			} else {
				echo "Los resultados no pueden ser guardados consulte al Administrador [OBJETOS]...";
			}
		} else {
			echo "Los resultados no pueden ser guardados consulte al Administrador... ultimo->".$ultimo; 
		}
		break;
}
?>
