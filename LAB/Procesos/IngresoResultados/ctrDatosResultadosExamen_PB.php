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
                $f_tomamuestra=$_POST['f_tomamuestra'];
               // echo $f_tomamuestra;
                $tipomuestra=$_POST['tipomuestra'];
                $f_consulta=$_POST['f_consulta'];
               // echo $fecharealiz."-".$fecharesultado;
                $ConRangos       = $objdatos->ObtenerCodigoRango($fechanac);
		$row_rangos      = pg_fetch_array($ConRangos);
		$idedad          = $row_rangos[0];
		$ConEstandar     = $objdatos->Obtener_Estandar($idexamen);
		$CodEstandar     = pg_fetch_array($ConEstandar);
		$codigo_estandar = $CodEstandar[0];
		$IdEstandar	 = $CodEstandar[1];
              // echo $idexamen;
		switch ($codigo_estandar) {
                    case "H50":
				$consulta = $objdatos->LeerElementosExamen($idexamen,$lugar);
                             if (pg_num_rows($consulta) >0){ 
				$imprimir="<table width='75%' border='0' align='center' class='StormyWeatherFormTABLE'>";
				$imprimir.="	<tr class='CobaltButton'>
                                                    <td width='30%'> &nbsp;</td>
                                                    <td width='30%' aling='center'>Resultado</td>
                                                    <td width='5%' aling='center'>Unidades</td>
                                                    <td width='30%' colspan='2'aling='center'>Control Normal </td>
						</tr>";
				$pos=0;
				$posele=0;
                                /*asort($valores[58],SORT_NUMERIC);*/
			while($row = pg_fetch_array($consulta)) { //ELEMENTOS
                            if($row['subelemento'] === "S") {
			   	$imprimir.="<tr>
			   			<td colspan='5' style='font:bold' class='StormyWeatherFieldCaptionTD'>".$row['elemento']."</td>
                                            </tr>";

			   	$consulta2=$objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);

				while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
					$otrosproto=$rowsub['siotrosproto'];
				$imprimir.= "<tr>
						<td width='30%' class='StormyWeatherDataTD'>".htmlentities($rowsub['subelemento'])."</td>
						<td width='30%' class='StormyWeatherDataTD'>";

                                 $con_total=$objdatos->contar_posibles_resultados($rowsub['idsubelemento']);
                                                   $total=pg_fetch_array($con_total);
                                                   //echo $total[0];
                                                if($total[0]>=1){
                                                       $imprimir.= "<div id='subele[".$pos."]'><select id='txtresultadosub[".$pos."]' name='txtresultadosub[".$pos."]'  size='1' style='width:260px' class='height js-example-basic-single' onclick='focusselect2(\"txtresultadosub\")'>
                                                                    <option value='0' >--Seleccione Resultado--</option>";
                                                    $con_result=$objdatos->leer_posibles_resultados($rowsub['idsubelemento']);
                                                    while ($row_result=pg_fetch_array($con_result)) {
                                                        $imprimir.="<option value='" . $row_result['id_posible_resultado'] . "'>" . htmlentities($row_result['posible_resultado']) . "</option>";
                                                       }

                                                     $imprimir.= "</select></div>   <input name='totcombo[".$pos."]' type='text' id='totcombo[".$pos."]' value='".$pos."' />  </td>";

                                                }
                                                else{


                                   $imprimir.= "<input size='20' name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]'><span class='glyphicon glyphicon-eye-close' onClick='noseobserva(".$pos.")' style='padding: 0 0 3 8; cursor: pointer' title='No se observa'></span>";
								   if ($otrosproto=='t'){
									   $imprimir.="<span class='glyphicon glyphicon-xbt' onClick='blastocystis(".$pos.")' style='padding: 0 0 3 8; cursor: pointer' title='Blastocystis hominis'></span>";
								   }

								   $imprimir.= "<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'>
                                                <input name='totcombo[".$pos."]' type='hidden' id='totcombo[".$pos."]'  value=''  >
						</td>";
                                              }
				$imprimir.= "<td width='5%' class='StormyWeatherDataTD'>".htmlentities($rowsub['unidad'])."</td>
					     <td width='30%' class='StormyWeatherDataTD'>
							<input name='txtcontrol[".$pos."]' type='text' id='txtcontrol[".$pos."]'></td>
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
                                        $imprimir.= "<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                     <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />
                                                     <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                                                     <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' />";
                                        $imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>";	//numero de cajas de texto dibujadas para subelementos
                                        $imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>"; //elementos

                                    $imprimir.="<tr>
                                                    <td class='StormyWeatherDataTD'>Observaci&oacute;n </td>
                                                    <td class='StormyWeatherDataTD' colspan='4'>
                                                        <textarea name='txtobservacion' cols='60' id='txtobservacion'></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan='1'  class='StormyWeatherDataTD'>*Resultado Tabulador</td>
                                                    <td colspan='2' class='StormyWeatherDataTD'>
                                                        <select id='cmbTabulador' name='cmbTabulador' size='1' class='form-control height'>
                                                            <option value='0' >Seleccione Resultado...</option>";
                                                                $conResult=$objdatos->LlenarResultados($IdEstandar);
                                                                while ($rows =pg_fetch_array($conResult)) {
                                                                    $imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ". htmlentities($rows[1])."</option>";
                                                                }
                                            $imprimir.="</select></td>
                                                    <td colspan='2'  class='StormyWeatherDataTD'>&nbsp;</td>
                                                </tr>";

                                    $imprimir.="<tr>
                                                    <td colspan='5' class='StormyWeatherDataTD'>
                                                        <button type='button' id='Submit' align='center' class='btn btn-primary' title='Vista Previa de Resultados'  onclick='MostrarVistaPreviaPlantillaB();'>&nbsp;Vista Previa de Resultados</button>
                                                    </td>
                                                </tr>
                                        </table>";
                                        //<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaB()'>
                                        echo $imprimir;
                                        
                        }else{
            echo '<center><br><br><h1><img src="../../../Imagenes/warning.png" valign="middle"/>'
            . 'Favor de Revisar la correcta configuración de los exámenes solicitados, tanto en tipo de muestra, Elementos y Subelementos de Examenes.</h1> ';
            echo " <button type='submit' class='fg-button ui-state-default ui-corner-all' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-sign'></span>Cerrar</button></center>";
                                        
        }           
                                       
		    break;
		    default:
			$consulta=$objdatos->LeerElementosExamen($idexamen,$lugar);
                         if (pg_num_rows($consulta) >0){

			$imprimir="<table width='75%' border='0' align='center' class='StormyWeatherFormTABLE'>
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
                                            <td colspan='3' style='font:bold'>".htmlentities($row['elemento'])."</td>
                                            <td style='font:bold; text-align:right; color:black'><span class='glyphicon glyphicon-pushpin' onClick='default_subelementos(".$row['idelemento'].")' style='padding: 0 0 3 8; cursor: pointer' title='Resultados por defecto'>&nbsp;</span></td>
                                        </tr>";
						$consulta2=$objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);

                                    while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
										$otrosproto=$rowsub['siotrosproto'];
                                            $imprimir.= "
                                        <tr>
                                            <td width='30%' class='StormyWeatherDataTD'>".htmlentities($rowsub['subelemento'])."</td>
                                            <td width='30%' class='StormyWeatherDataTD'>";
                                             $imprimir.="<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'>";
                                                   $con_total=$objdatos->contar_posibles_resultados($rowsub['idsubelemento']);
                                                   $total=pg_fetch_array($con_total);
                                                   //echo $total[0];
                                                if($total[0]>=1){
                                                   if ($rowsub['resultadounico']=='true' || $rowsub['resultadounico']=='t'){
                                                   $imprimir.= "<div id='subele'><select id='txtresultadosub[".$pos."]' name='txtresultadosub[".$pos."]'  size='1' style='width:270px' class='js-example-basic-single' onclick='focusselect2(\"txtresultadosub\")'>
                                                                    <option value='0' >--Seleccione Resultado--</option>";
                                                   }
                                                   else{
                                                     $imprimir.= "<div id='subele'><select id='txtresultadosub[".$pos."]' name='txtresultadosub[".$pos."]'  size='1' style='width:270px' class='js-example-basic-multiple' multiple='multiple'>
                                                                    <option value='0' >--Seleccione Resultados--</option>";
                                                   }
                                                    $con_result=$objdatos->leer_posibles_resultados($rowsub['idsubelemento']);
                                                    $orden= pg_fetch_all($con_result);//print_r($orden);
                                                     /*asort($valores[58],SORT_NUMERIC);*/
                                                    //$orden1=asort($orden,SORT_NUMERIC);
                                                    foreach ($orden as $row_result) {
                                                        $imprimir.="<option value='" . $row_result['id_posible_resultado'] . "'>" . htmlentities($row_result['posible_resultado']) . "</option>";
                                                    }

                                                    $imprimir.= "</select></div><input name='totcombo[".$pos."]' type='hidden' id='totcombo[".$pos."]' value='".$pos."'></td> ";
                                                   // print_r($row_result);
                                                    //$tot=  count($row_result);
                                                    //echo $tot;
                                                     //   prit_r($row_result);


                                                //  $imprimir.="<option value='" . $row_result['id_posible_resultado'] . "'>" . htmlentities($row_result['posible_resultado']) . "</option>";
                                                }
                                                else{
												$imprimir.="<table style='width:100%'>";
													$imprimir.="<tr>";
													$imprimir.="<td rowspan='2'>";
													    $imprimir.= "<textarea name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]' cols='30' onKeyPress='return acceptNum(event)'></textarea>";
													$imprimir.="</td>";
													$imprimir.="<td>";
													if ($otrosproto=='t'){
														$imprimir.="<span class='glyphicon glyphicon-flash' onClick='blastocystis(".$pos.")' style='padding: 0 0 3 8; cursor: pointer' title='Blastocystis hominis'></span>";
													}
													$imprimir.="</td>";
													$imprimir.="</tr>";
													$imprimir.="<tr>";
													$imprimir.="<td>";
													$imprimir.= "<span class='glyphicon glyphicon-eye-close' onClick='noseobserva(".$pos.")' style='padding: 0 0 3 8; cursor: pointer' title='No se observa'></span>
								 <input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'>
                                                                 <input name='totcombo[".$pos."]' type='hidden' id='totcombo[".$pos."]'  value=''  >
					    </td>";
													$imprimir.="</td>";
													$imprimir.="</tr>";
													$imprimir.="</table>";
                                                                           //  print_r($row_result);
                                                    // <input size='30' name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]' onKeyPress='return acceptNum(event)'>





                                                }
				$imprimir.="<td  align='center' class='StormyWeatherDataTD'>".htmlentities($rowsub['unidad'])."</td>
                                    	    <td  align='center' class='StormyWeatherDataTD'>".$rowsub['rangoinicio']." - ".$rowsub['rangofin']."
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
					</tr>";
					}//else

				}//while externo

				pg_free_result($consulta);
                                 $imprimir.= "  <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />
                                                <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                                                <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' />
                                                <input type='hidden' name='txtf_consulta' id='txtf_consulta' disabled='disabled' value='".$f_consulta."' />";
				   $imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
				   $imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
				    $imprimir.="
                                        <tr>
                                            <td class='StormyWeatherDataTD'>Observaci&oacute;n </td>
                                            <td class='StormyWeatherDataTD' colspan='4'>
                                                <textarea name='txtobservacion' cols='60' id='txtobservacion'></textarea>
                                            </td>
                                        </tr>
					<tr>
					    <td class='StormyWeatherDataTD'>*Resultado Tabulador</td>
					    <td colspan='3' class='StormyWeatherDataTD'>
					        <select id='cmbTabulador' name='cmbTabulador' size='1' class='form-control height'>
					            <option value='0' >--Seleccione Resultado--</option>";
                                                        $conResult=$objdatos->LlenarResultados($IdEstandar);
                                                        while ($rows =pg_fetch_array($conResult)) {
					$imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ".htmlentities($rows[1])."</option>";
									}
				            $imprimir.="</select>
                                                </td>
                                            </tr>

                                            <tr class='StormyWeatherDataTD'>
						<td colspan='4' class='StormyWeatherDataTD' align='right'>
                                                    <button type='button' id='Submit' align='center' class='btn btn-primary' title='Vista Previa de Resultados'  onclick='MostrarVistaPreviaPlantillaB();'>&nbsp;Vista Previa de Resultados</button>
                                                </td>
					    </tr>
					</table>";
                                            //<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaB();'/>
				 echo $imprimir;}
                        
        else{
            echo '<center><br><br><h1><img src="../../../Imagenes/warning.png" valign="middle"/>'
            . 'Favor de Revisar la correcta configuración de los exámenes solicitados, tanto en tipo de muestra, Elementos y Subelementos de Examenes.</h1> ';
            echo " <button type='submit' class='fg-button ui-state-default ui-corner-all' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-sign'></span>Cerrar</button></center>";
                                        
        }

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
	   	$valores_subelementos = utf8_encode($_POST['valores_subelementos']);
               // echo utf8_encode($valores_subelementos);
	   	$codigos_subelementos = $_POST['codigos_subelementos'];
	   	$valores_elementos    = $_POST['valores_elementos'];
	   	$codigos_elementos    = $_POST['codigos_elementos'];
	   	$controles 	      = $_POST['controles'];
	   	$controles_ele 	      = $_POST['controles_ele'];
                $valores_combos       = $_POST['valores_combos'];
	   	$establecimiento      = $_POST['estab'];
	   	$tab 		      = $_POST['tab'];
	   	$fechanac 	      = $_POST['fechanac'];
	   	$sexo 		      = $_POST['sexo'];
                $fecharealiz          = $_POST['fecharealiz'];
                $fecharesultado       = $_POST['fecharesultado'];
                $subservicio          = $_POST['subservicio'];
                $idestab              = $_POST['idestab'];
                $f_tomamuestra        = $_POST['f_tomamuestra'];
                $tipomuestra          = $_POST['tipomuestra'];
                $f_consulta           = $_POST['f_consulta'];
                
                //echo $idestab;
               // echo "fecha ".$f_tomamuestra."- tipo".$tipomuestra;
	   	$Consulta_Estab  = $objdatos->Nombre_Establecimiento($lugar);
	   	$row_estab   	 = pg_fetch_array($Consulta_Estab);
	   	$ConEstandar 	 = $objdatos->Obtener_Estandar($idexamen);
	   	$CodEstandar 	 = pg_fetch_array($ConEstandar);
	   	$codigo_estandar = $CodEstandar[0];
		$IdEstandar	 = $CodEstandar[1];
                $Consulta_EstabExt = $objdatos->Nombre_Establecimiento($idestab);
	   	$row_estabExt    = pg_fetch_array($Consulta_EstabExt);
	   	$ConRangos=$objdatos->ObtenerCodigoRango($fechanac);
	   	$row_rangos=  pg_fetch_array($ConRangos);
	   	$idedad=$row_rangos[0];
                //echo $idsolicitud;
	   	switch ($codigo_estandar) {
	   		case "H50":
		   		$cadena 		  = $valores_subelementos;
		   		$vector 		  = EXPLODE("|",$cadena);
                                $datos_combos             = $valores_combos;
		   		$vector_elementos 	  = EXPLODE("/",$valores_elementos);
		   		$vector_controles 	  = EXPLODE("/",$controles);
		   		$vector_controles_ele     = EXPLODE("/",$controles_ele);
                                $vector_combos            = EXPLODE("/",$datos_combos);
		   		$objdatos 		  = new clsConsultarElementos;
		   		$consulta 		  = $objdatos->LeerElementosExamen($idexamen,$lugar);
		   		$consulta_datos 	  = $objdatos->LeerDatos($idexamen);
		   		$datos_generales 	  = $objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
		   		$datos_empleado 	  = $objdatos->DatosEmpleado($idempleado,$lugar);
                                //$datos_rep                =$objdatos->NombreResultado($idexamen);
		   		$row_generales 		  = pg_fetch_array($datos_generales);
		   		$row_area  		  = pg_fetch_array($consulta_datos);
		   		$row_empleado 		  = pg_fetch_array($datos_empleado);
                                $row_area['nombre_reporta'];
	   			$imprimir="<table width='100%' border='0' align='center' Cellpadding='0'  cellspacing='0' >
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
				   			<td colspan='2'>". htmlentities($row_estabExt['nombre'])."</td>
				   			<td colspan='1'><strong>Fecha Recepción:</strong></td>
				   			<td colspan='2'>".$row_generales['fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['fechanacimiento']."'/>
				   		</tr>
				   		<tr>
				   		        <td colspan='1'><strong>Expediente:</strong></td>
				   		        <td colspan='2'>".$row_generales['idnumeroexp']."</td>
                                                        <td colspan='1'><strong>Fecha Toma Muestra:</strong></td>
				   			<td colspan='2'>".$f_tomamuestra."</td>
				   		</tr>
			   			<tr>
                                                        <td colspan='1'><strong>Paciente:</strong></td>
			   				<td colspan='5'>".$row_generales['nombrepaciente']."</td>
			   			</tr>
		   				<tr>
			   				<td colspan='1'><strong>Edad:</strong></td>
			   				<td colspan='2'>".$row_generales['edad']."</td>
			   				<td colspan='1'><strong>Sexo:</strong></td>
			   				<td colspan='2'>".$row_generales['sexo']."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Procedencia:</strong></td>
			   				<td colspan='2'>".$row_generales['procedencia']."</td>
			   				<td colspan='1'><strong>Servicio:</strong></td>
			   				<td colspan='2'>".$subservicio."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Examen Realizado:</strong></td>
			   				<td colspan='5'>".htmlentities($row_area['nombre_reporta'])."</td>
			   			</tr>
			   			<tr>
			   				<td colspan='1'><strong>Validado Por:</strong></td>
			   				<td colspan='5'>".htmlentities($row_empleado['nombreempleado'])."</td>
			   			</tr>
			   			";

	   			$nomcod = $objdatos->ObtenerNombreCodigo($tab);
	   			$row_codigo = pg_fetch_array($nomcod);
//                                 $imprimir.="<tr>
//		        			<td>Resultado Tabulador:</td><td colspan='5'>".$row_codigo[0]."</td>
//		        		</tr>
		        	$imprimir.="</table>";


			   	$imprimir.="<table width='100%' border='0' align='center' cellspacing='0'>";
			   	pg_free_result($consulta_datos);
			   	pg_free_result($datos_generales);
			   	$imprimir.="<tr>
			   			<td width='35%'></td>
			   			<td width='25%'>Resultado</td>
			   			<td width='20%'>Unidades</td>
			   			<td width='60%'colspan='2'>Control Normal </td>
			   		    </tr>
                                            <tr><td colspan='6'><hr></td></tr>";

			   	$pos    = 0;
			   	$posele = 0;

				while($row = pg_fetch_array($consulta)) { //ELEMENTOS
					if($row['subelemento']=="S") {
						$imprimir.= "
                                            <tr>
						<td colspan='5' style='font:bold'><strong>".htmlentities($row['elemento'])."</strong></td>
                                            </tr>";
						$consulta2 = $objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);

					while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
                                                $imprimir.=
                                           "<tr>
						<td width='35%'>". htmlentities( $rowsub['subelemento'])."</td>";

                                                 if($vector_combos[$pos]== NULL){
			                            $imprimir.="<td width='25%'>++".htmlentities($vector[$pos])."<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'></td>";
                                                }
                                                else{
                                            $conresult=$objdatos->BuscarResultado($vector[$pos]);
                                            $row_dresult=  pg_fetch_array($conresult);
                                                    $imprimir.="<td width='25%'>--".htmlentities($row_dresult['posible_resultado'])."<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'></td>";

                                                }

                                            $imprimir.="        <td width='20%'>".htmlentities($rowsub['unidad'])."</td>
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
                                            $imprimir .="<tr><td colspan='6'><hr></td></tr><tr>
                                                            <td colspan='1'><strong>Observacion:</strong></td>
                                                            <td colspan='4'>".htmlentities($observacion)."</td>
                                                         </tr>
                                                         <tr>
								<td colspan='6'>&nbsp;
								</td>
							</tr>
                                                        <tr>
								<td colspan='6'>&nbsp;
								</td>
							</tr>";
                                                        pg_free_result($consulta);
                                            $imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
                                            $imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>"; //elementos
                                            $imprimir .="<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' value='".$fecharealiz."'>
                                                         <input type='hidden' name='txtfresultado' id='txtfresultado' value='".$fecharesultado."' />";
                                            $imprimir .="<td colspan='3' align='center'>
                                                <button type='button' id='btnGuardar' align='center' class='btn btn-primary' title='Guardar Resultados'  onclick='GuardarResultadosPlantillaB();'><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar Resultados</button>
                                                <button style='display:none' type='button' class='btn btn-primary'  name='Imprimir'  id='Imprimir' value='Imprimir' class='fg-button ui-state-default ui-corner-all'  Onclick='ImprimirPlantillaB(".$idsolicitud.",".$idexamen.",".$idempleado.",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($subservicio)."\",\"".htmlentities($observacion)."\",\"".utf8_encode($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".htmlentities($row_empleado['nombreempleado'])."\",".$sexo.",\"".$idedad."\",\"".$valores_combos."\",".$idestab.",\"".$f_tomamuestra."\",\"".$tipomuestra."\",\"".$f_consulta."\",\"".$f_consulta."\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>
                                                <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='display:none; height:20px'><button type='button' id='modaladdexam' align='center' class='btn btn-primary' title='Agregar Examen' ><span class='glyphicon glyphicon-plus'></span>&nbsp;Agregar Examen</button></a>
                                                <button type='button' id='btnSalir' align='center' class='btn btn-primary' title='Cerrar'  onclick='Cerrar();'><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button><br/><br><br/>
                                                            </td>
                                                        </tr>
                                                    </table>";
	        //<input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaB()'>
                    //<input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
                    // <input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaB(".$idsolicitud.",".$idexamen.",".$idempleado.",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($subservicio)."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".htmlentities($row_empleado['nombreempleado'])."\",".$sexo.",\"".$idedad."\",\"".$valores_combos."\",".$idestab.",\"".$f_tomamuestra."\",\"".$tipomuestra."\") ;'>
	        	echo $imprimir;
	        	break;
		    default:
		        $cadena 	   = $valores_subelementos;
                        $datos_combos      = $valores_combos;
		        $vector 	   = EXPLODE("|",$cadena);
		        $vector_elementos  = EXPLODE("/",$valores_elementos);
                        $vector_combos     = EXPLODE("/", $datos_combos);
                     /*   print_r($vector);
                        print_r('*****'.$vector_combos[15]);
                        print_r('*****'.$vector_combos[20]);
                        print_r('<br>'.$vector[15]);*/
				//$obj 			  = new clsConsultarElementos;
		        $consulta 	  = $objdatos->LeerElementosExamen($idexamen,$lugar);
		        $consulta_datos   = $objdatos->LeerDatos($idexamen);
		        $datos_generales  = $objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
		        $datos_empleado   = $objdatos->DatosEmpleado($idempleado,$lugar);
		        $row_generales 	  = pg_fetch_array($datos_generales);
		        $row_area 	  = pg_fetch_array($consulta_datos);
		        $row_empleado 	  = pg_fetch_array($datos_empleado);
		        $nombreEmpleado   = $row_empleado['nombreempleado'];

		        $imprimir="<br>
                            <table width='100%' border='0'  align='center' Cellpadding='2'  cellspacing='5' >
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
                                    <td colspan='6'>&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan='1'><strong>Establecimiento Solicitante:</strong></td>
                                    <td colspan='3'>".htmlentities($row_estabExt['nombre'])."</td>
                                    <td colspan='1'><strong>Fecha Recepción:</strong></td>
                                    <td colspan='1'>".$row_generales['fecha']."</td>
                                        <input name='suEdad' id='suEdad' type='hidden' value='".$row_generales['fechanacimiento']."'/>
		        	</tr>
		        	<tr>
                                    <td colspan='1'><strong>Expediente:</strong></td>
                                    <td colspan='3'>".$row_generales['idnumeroexp']."</td>
                                    <td colspan='1'><strong>Fecha Toma Muestra:</strong></td>
                                    <td colspan='1'>".$f_tomamuestra."</td>
		        	</tr>
		        	<tr>
                                    <td colspan='1'><strong>Paciente:</strong></td>
                                    <td colspan='3'>".htmlentities($row_generales['nombrepaciente'])."</td>
                                    <td colspan='1'><strong>Fecha consulta:</strong></td>    
                                    <td colspan='1'>".$f_consulta ."</td>
		        	</tr>
		        	<tr>
                                    <td colspan='1'><strong>Edad:</strong></td>
                                    <td colspan='3'>".$row_generales['edad']."</td>
                                    <td colspan='1'><strong>Sexo:</strong></td>
                                    <td colspan='1'>".$row_generales['sexo']."</td>
		        	</tr>
		        	<tr>
                                    <td colspan='1'><strong>Procedencia:</strong></td>
                                    <td colspan='3'>".htmlentities($row_generales['procedencia'])."</td>
                                    <td colspan='1'><strong>Servicio:</strong></td>
                                    <td colspan='1'>".$subservicio."</td>
		        	</tr>
		        	<tr>
                                    <td colspan='1'><strong>Examen Realizado:</strong></td>
                                    <td colspan='5'>".htmlentities($row_area['nombre_reporta'])."</td>
		        	</tr>
		        	<tr>
                                    <td colspan='1'><strong>Validado Por:</strong></td>
                                    <td colspan='5'>".htmlentities($row_empleado['nombreempleado'])."</td>
                        	</tr>";

		        $nomcod=$objdatos->ObtenerNombreCodigo($tab);
		        $row_codigo= pg_fetch_array($nomcod);
		    $imprimir.="<tr>
                                    <td>Resultado Tabulador:</td><td colspan='5'>".$row_codigo[0]."</td>
		        	</tr>
                            </table>";

		   $imprimir.= "<table width='100%' border='0' align='center' cellspacing='0'>";
		   $imprimir.= "<tr>
                                     <td colspan='6' class='Estilo6'>&nbsp;</td>
                            </tr><tr>
                                    <td width='25%'></td>
				    <td width='25%'><b>Resultado</b></td>
				    <td width='10%'><b>Unidades</b></td>
				    <td width='15%'><b>Rangos de referencia</b></td>
				</tr>
                                <tr>
                                    <td colspan='4'><hr style='width:100%'></td>
                                </tr>";

		    	$pos    = 0;
		    	$posele = 0;

        while($row = pg_fetch_array($consulta)) { //ELEMENTOS
            if($row['subelemento']=="S") {
		   $imprimir.= "<tr>
                                    <td colspan='4' style='font:bold' style='padding-bottom: 0.5em;'><strong>".utf8_decode($row['elemento'])."</strong></td>
                                </tr>";
		    $consulta2 = $objdatos->LeerSubElementosExamen($row['idelemento'],$lugar,$sexo,$idedad);
	        while($rowsub = pg_fetch_array($consulta2)) { //SUBELEMENTOS
  		    $imprimir.="<tr>
		  		    <td width='35%' valign='top' style='paldding-bottom: 0.5em;'>&emsp;". htmlentities($rowsub['subelemento'])."</td>";
                    if  ($vector_combos[$pos]== NULL){
                        $imprimir.="<td width='25%'>".$vector[$pos]."<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."' ></td>";
                        }
                    else{
                        $conresult=$objdatos->BuscarResultado($vector[$pos]);
                        $row_dresult=  pg_fetch_array($conresult);
                        $imprimir.="<td width='25%'>".$row_dresult['posible_resultado']."<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['idsubelemento']."'></td>";

                    }
                        $imprimir.="<td width='10%'>".htmlentities($rowsub['unidad'])."</td>";
		    if (empty($rowsub['rangoinicio']) AND empty($rowsub['rangofin']))
                       $imprimir.= "<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
		    else
                       $imprimir.= "<td width='15%'>".$rowsub['rangoinicio']." - ".$rowsub['rangofin']."</td> ";
                   $imprimir.=" </tr>";
			$pos = $pos + 1;
		    }

		  $imprimir.= " <tr>
		                    <td colspan='5'>".htmlentities($row['observelem'])."</td>
				</tr>";
	    }else {
		    $imprimir.= "<tr>
			        	 <td style='font:bold;padding-bottom: 0.5em;'>".htmlentities($row['elemento'])."</td>
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

				$imprimir .="<tr><td colspan='6'><hr></td></tr>
                                   <tr>
		        			<td colspan='1'><strong>Observacion:</strong></td>
		        			<td colspan='5'>".htmlentities($observacion)."<br></td>
		        		</tr>"	;	//numero de cajas de texto dibujadas para subelementos
				$imprimir.="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
				$imprimir.="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
                                $imprimir.="<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' value='".$fecharealiz."'>
                                             <input type='hidden' name='txtfresultado' id='txtfresultado' value='".$fecharesultado."' />";
				$imprimir.="<tr>
                                                <td colspan='6' align='center' ><br>";

                                $imprimir.= "<button type='button' id='btnGuardar' align='center' class='btn btn-primary' title='Guardar Resultados'  onclick='GuardarResultadosPlantillaB();'><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar Resultados</button>
<button style='display:none' type='button' class='btn btn-primary'  name='Imprimir'  id='Imprimir' value='Imprimir' class='fg-button ui-state-default ui-corner-all'  Onclick='ImprimirPlantillaB(".$idsolicitud.",".$idexamen.",".$idempleado.",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($subservicio)."\",\"".htmlentities($observacion)."\",\"".utf8_encode($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".htmlentities($row_empleado['nombreempleado'])."\",".$sexo.",\"".$idedad."\",\"".$valores_combos."\",".$idestab.",\"".$f_tomamuestra."\",\"".$tipomuestra."\",\"".$f_consulta."\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>
                                         <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='display:none; height:20px'><button type='button' id='modaladdexam' align='center' class='btn btn-primary' title='Agregar Examen' ><span class='glyphicon glyphicon-plus'></span>&nbsp;Agregar Examen</button></a>
                                            <button type='button' id='btnSalir' align='center' class='btn btn-primary' title='Cerrar'  onclick='Cerrar();'><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button><br/><br><br/>

						</td>
                                            </tr>
					</table>";
			echo $imprimir;
                        // <input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaB()'>
                                //        <input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaB(".$idsolicitud.",".$idexamen.",".$idempleado.",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($subservicio)."\",\"".htmlentities($observacion)."\",\"".utf8_encode($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".htmlentities($row_empleado['nombreempleado'])."\",".$sexo.",\"".$idedad."\",\"".$valores_combos."\",".$idestab.",\"".$f_tomamuestra."\",\"".$tipomuestra."\") ;'>
                                // <input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
			break;
		}
		break;
	case 3://GUARDANDO DATOS DE LOS RESULTADOS EN LAS TABLAS
		$idexamen 		  = $_POST['idexamen'];
		$idsolicitud 		  = $_POST['idsolicitud'];
		$idrecepcion 		  = $_POST['idrecepcion'];
		$iddetalle 		  = $_POST['iddetalle'];
		$procedencia 		  = $_POST['procedencia'];
		$origen 		  = $_POST['origen'];
		$observacion 		  = $_POST['observacion'];
		$idempleado 		  = $_POST['idempleado'];
		$valores_subelementos     = $_POST['valores_subelementos'];
		$codigos_subelementos     = $_POST['codigos_subelementos'];
		$valores_elementos 	  = $_POST['valores_elementos'];
		$codigos_elementos 	  = $_POST['codigos_elementos'];
		$controles                = $_POST['controles'];
		$controles_ele 		  = $_POST['controles_ele'];
                $valores_combos           = $_POST['valores_combos'];
		$vector 		  = EXPLODE("|",$valores_subelementos); //SON LOS SUB-ELEMENTOS
		$vector_elementos 	  = EXPLODE("/",$valores_elementos);
		$v_id_elementos 	  = EXPLODE("/",$codigos_elementos);
		$v_id_subelementos 	  = EXPLODE("/",$codigos_subelementos);
		$tamano_vector 		  = count($vector);
		$tamano_vectorele 	  = count($vector_elementos);
		$vector_controles 	  = EXPLODE("/",$controles);
		$vector_controles_ele     = EXPLODE("/",$controles_ele);
                $vector_combos            = explode("/",$valores_combos);
		$tab 			  = $_POST['tab'];
  		$fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];

                $existe = $objdatos->VerificarExistencia($idexamen,$idsolicitud,$iddetalle);

                if ($existe[0] >= 1){
                     echo "Ya hay un resultado para esta muestra!!";
                }else{
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
                                              // $vector_combos[$pos];
                                                if ($objdatos->insertar_subelementos($idresultado,$v_id_subelementos[$pos],$vector[$pos],$vector_controles[$pos],$lugar,$vector_combos[$pos])==false) {
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
                }

		break;
}
?>
