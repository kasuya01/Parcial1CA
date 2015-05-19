<?php session_start();
include ("clsPlantillaE.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

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
	//$observacion= $_POST['observacion'];
        $fechanac=$_POST['fechanac'];
        $sexo=$_POST['sexo'];
      //  echo $idexamen;
        $fecharealiz=$_POST['fecharealiz'];
        $fecharesultado=$_POST['fecharesultado'];
        $f_tomamuestra=$_POST['f_tomamuestra'];
        $tipomuestra=$_POST['tipomuestra'];
      //  echo $fecharealiz." - ".$fecharesultado;
        $ConEstandar=$obj->Obtener_Estandar($idexamen);
	$CodEstandar= pg_fetch_array($ConEstandar);
	$codigo_estandar  = $CodEstandar[0];
	$IdEstandar	 = $CodEstandar[1];

       /* $Cuentadias=$obj->CalculoDias($fechanac);
        $Cdias= pg_fetch_array($Cuentadias);
        $dias=$Cdias[0];*/
        
        $ConRangos=$obj->ObtenerCodigoRango($fechanac);
        $row_rangos=  pg_fetch_array($ConRangos);
        $idedad=$row_rangos[0];


	switch ($codigo_estandar){

		case "H15":
			$consulta=$obj->LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad);

			$imprimir="
			<table width='86%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr class='CobaltButton'>
					<td > Prueba </td>
						<td> Resultado </td>
						<td> Unidades </td>
						<td colspan='2'> Control Diario </td>
				</tr>";
					$pos=0;
				while($row = pg_fetch_array($consulta))//ELEMENTOS
				{
				$imprimir.= "
				<tr>
					<td class='StormyWeatherFieldCaptionTD'>".$row['nombreprocedimiento']."</td>
					<td class='StormyWeatherDataTD' >
						<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['id']."'>
						<input name='txtresultado[".$pos."]' type='hidden' id='txtresultado[".$pos."]'>
					</td>
					<td class='StormyWeatherDataTD'>".$row['unidades']."</td>
					<td class='StormyWeatherDataTD' >
						<input name='txtcomentario[".$pos."]' type='hidden' id='txtcomentario[".$pos."]'>".$row['unidades']."
					</td>
				</tr>";
					$pos=$pos + 1;
				}
				pg_free_result($consulta);
                                $imprimir.= "  <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />
                                               <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                                             <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' />";     
				$imprimir.="
				<tr><td class='StormyWeatherFieldCaptionTD'>*Resultado Tabulador</td>
					<TD colspan='3' class='StormyWeatherDataTD'>
						<select id='cmbTabulador' name='cmbTabulador' size='1'>
							<option value='0' >--Seleccione Resultado--</option>";
							$conResult=$obj->LlenarResultados($IdEstandar);
							while ($rows =pg_fetch_array($conResult)){
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
   		/*$idexamen=$_POST['idexamen'];
		$observacion= $_POST['observacion']; */

		$consulta=$obj->LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad);

		$imprimir="
                        <table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			    <tr class='CobaltButton'>
				<td> Prueba </td>
				<td> Resultado </td>
				<td> Unidades </td>
				<td> Rango </td>
			    </tr>";
					$pos=0;
			while($row = pg_fetch_array($consulta))//ELEMENTOS
			{
	       $imprimir.= "<tr>
				<td class='StormyWeatherFieldCaptionTD'>".$row['nombreprocedimiento']."</td>
				<td class='StormyWeatherDataTD'>
                                    <input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimiento']."'>";
                                              $con_total=$obj->contar_posibles_resultados_procedimientos($row['idprocedimiento']);
                                                   $total=pg_fetch_array($con_total);
                                                   //echo $total[0];
                                if($total[0]>=1){  
                           $imprimir.= "<select id='txtresultado[".$pos."]' name='txtresultado[".$pos."]'  size='1' style='width:260px'>
                                            <option value='0' >--Seleccione Resultado--</option>";
                                                    $con_result=$obj->leer_posibles_resultados_procedimientos($row['idprocedimiento']);
                                      while ($row_result=pg_fetch_array($con_result)) {
                                $imprimir.="<option value='" . $row_result['id_posible_resultado'] . "'>" . htmlentities($row_result['posible_resultado']) . "</option>";
                                                    }   
                                $imprimir.= "   <input name='totcombo[".$pos."]' type='hidden' id='totcombo[".$pos."]' value='".$pos."'></td> ";                
                                                    
                                }
                                else{
                                                                                                       
			        $imprimir.= "
                                        	<textarea name='txtresultado[".$pos."]' type='text' id='txtresultado[".$pos."]' cols='50' ></textarea>
                                                <input name='totcombo[".$pos."]' type='hidden' id='totcombo[".$pos."]'  value=''></td>";
                                }
                    $imprimir.="<td class='StormyWeatherDataTD' >".$row['unidades']."</td>";
		    $imprimir.="<td class='StormyWeatherDataTD' aligh='center'>".$row['rangoinicio']."-".$row['rangofin']."
					<input name='txtcomentario[".$pos."]' type='hidden' id='txtcomentario[".$pos."]'>
				</td> ";            
                                                   
					$pos=$pos + 1;
			}
                        pg_free_result($consulta);
                                    $imprimir.= "<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                 <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />
                                                 <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                                                 <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' />"; 
                                  
					
		$imprimir.="<tr>
                                <td class='StormyWeatherFieldCaptionTD'>Observaci&oacute;n </td>
                                <td class='StormyWeatherDataTD' colspan='5'>
                                    <textarea name='txtobservacion' cols='75' id='txtobservacion'></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td class='StormyWeatherFieldCaptionTD'>*Resultado Tabulador</td>
				<td colspan='3' class='StormyWeatherDataTD'>
                                    <select id='cmbTabulador' name='cmbTabulador' size='1'>
					<option value='0' >--Seleccione Resultado--</option>";
					$conResult=$obj->LlenarResultados($IdEstandar);
					while ($rows =pg_fetch_array($conResult)){
			    $imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ". htmlentities($rows[1])."</option>";
							}
			$imprimir.="</select></td>"
                         . "</tr>";
		$imprimir.="<tr>
				<td colspan='5'  align='right' class='StormyWeatherDataTD'>
                                    <input type='hidden' id='oculto' value='".$pos."'>
                                    <input type='button' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaE()'>
				</td>
			    </tr>
			    <tr>
				<td colspan='5'>&nbsp;</td>
			    </tr>
		        </table>";
		echo $imprimir;
			//mysql_free_result($consulta);
		break;
		}
break;

case 2://vista Previa de Resultado
                $fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];
                $f_tomamuestra=$_POST['f_tomamuestra'];
                $tipomuestra=$_POST['tipomuestra'];
                
		$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
		$row_estab = pg_fetch_array($Consulta_Estab);
		$idexamen=$_POST['idexamen'];//*
		//echo $idexamen;
		$idsolicitud= $_POST['idsolicitud'];
		$observacion= $_POST['observacion'];
		$idempleado= $_POST['idempleado'];
		if (isset($_POST['procedencia'])){$procedencia= $_POST['procedencia'];}else{$procedencia="";}
		if (isset($_POST['origen'])){$origen= $_POST['origen'];}else{$origen="";}
		$codigos= $_POST['codigos'];
		$valores= $_POST['valores'];
                $comentarios     = $_POST['comentarios'];
              //  $valores_combos  = $_POST['valores_combos'];
                $valores_combos  = isset($_POST['valores_combos']) ? $_POST['valores_combos'] : null;
		$establecimiento = $_POST['estab'];
		$tab=$_POST['tab'];
		$fechanac=$_POST['fechanac'];
                $sexo=$_POST['sexo'];
             //   echo  $valores_combos;
		$ConEstandar=$obj->Obtener_Estandar($idexamen);
		$CodEstandar= pg_fetch_array($ConEstandar);
		$codigo_estandar  = $CodEstandar[0];
		$IdEstandar	 = $CodEstandar[1];

                /* $Cuentadias=$obj->CalculoDias($fechanac);
                 $Cdias= pg_fetch_array($Cuentadias);
                 $dias=$Cdias[0];*/

                 $ConRangos=$obj->ObtenerCodigoRango($fechanac);
                 $row_rangos=  pg_fetch_array($ConRangos);
                 $idedad=$row_rangos[0];
                // echo $sexo."-".$idedad;
		if ($codigo_estandar=="H15"){
			$consulta_datos=$obj->LeerDatos($idexamen);
			$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
			$datos_empleado=$obj->DatosEmpleado($idempleado,$lugar);
			$row_generales= pg_fetch_array($datos_generales);
			$row_area = pg_fetch_array($consulta_datos);
			$row_empleado = pg_fetch_array($datos_empleado);
			$nombrearea=$row_area['nombrearea'];
			$vector_idprocesos=EXPLODE("/",$codigos);
			$vector_respuesta=EXPLODE("/",$valores);
			$vector_comentarios=EXPLODE("/",$comentarios);
			$imprimir="
                            <table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr>
					 <td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                                    <td align='center' colspan='4' width='60%' class='Estilo5'>
                                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
                                            <p><strong>                       ".$row_estab['nombre']."</strong></p>
                                            <p><strong> ÁREA DE ".htmlentities($row_area['nombrearea'])." </strong></p>
					</td>
                                        <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
				</tr>
                                <tr>
					<td colspan='6' align='center'></td>
                                </tr>
				<tr>
					<td colspan='1' style='font:bold'>Establecimiento Solicitante:</td>
					<td colspan='2'>".$row_generales['estabext']."</td>
					<td colspan='1' style='font:bold'>Fecha Recepción:</td>
					<td colspan='2'>".$row_generales['fecharecep']."</td>
				</tr>
                                <tr>
					<td colspan='1' style='font:bold'>NEC</td>
					<td colspan='5'>".$row_generales['numero']."</td>
                                </tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
					<td colspan='5'>".htmlentities($row_generales['paciente'])."</td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
					<td colspan='2'>".$row_generales['edad']."</td>
					<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
					<td colspan='1'>".$row_generales['sexo']."</td>
				</tr>
				<tr>
					<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
					<td colspan='2' style='font:bold'>".htmlentities($row_generales['procedencia'])."</td>
					<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
					<td colspan='2' style='font:bold'>".htmlentities($row_generales['subservicio'])."</td>
				</tr>
				<tr>
					<td style='font:bold'><strong>Validado Por:</strong></td>
					<td colspan='5'>".htmlentities($row_empleado['empleado'])."</td>
				</tr>
				<tr>
					<td style='font:bold'><strong>Examen Realizado:</strong></td>
					<td colspan='5'style='font:bold'>".htmlentities($row_area['nombre_examen'])."</td>
				</tr>
				";
				pg_free_result($consulta_datos);
				pg_free_result($datos_generales);
				$nomcod=$obj->ObtenerNombreCodigo($tab);
                                $row_codigo= pg_fetch_array($nomcod);
								//	echo $row_codigo[0];
                   $imprimir.=" <tr><td style='font:bold' >Resultado Tabulador:</td><td colspan='3'>".$row_codigo[0]."</td></tr>";
				$consulta=$obj->LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad);

				$imprimir.="
				<tr>
                                    <td colspan='6'>
					<table width='100%' border='0' align='center'  >
                                            <tr >
						<td > Prueba </td>
						<td > Resultado </td>
						<td > Unidades </td>
						<td colspan='2' > Control Diario </td>
                                            </tr>";
					$pos=0;

					while($row = pg_fetch_array($consulta))//ELEMENTOS
					{
                                $imprimir.="<tr>
						<td align='justify'>".htmlentities($row['nombreprocedimiento'])."</td>
						<td align='justify'>
							<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['id']."'>".htmlentities($vector_respuesta[$pos]).
					  "	</td>
						<td align='justify'>".$row['unidades']."</td>
						<td align='justify'>".htmlentities($vector_comentarios[$pos])."</td>
						<td>".htmlentities($row['unidades'])."</td>
                                            </tr>";
					  $pos=$pos + 1;
					}
					pg_free_result($consulta);
                                        // echo $idedad;
                              $imprimir.= " <tr>
					<td style='font:bold'><strong>Observacion:<strong></td>
					<td colspan='5'>".htmlentities($observacion)."</td>
				</tr>
                                <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                       <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />
                                                        <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                                                        <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' />";    
                                $imprimir.="<tr>
						<td colspan='4' align='rigth'>
							<input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarPlantillaE()'>
							<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaE(".$idsolicitud.",\"".$idexamen."\",\"".$row_empleado['empleado']."\",\"".$procedencia."\",\"".$row_generales['subservicio']."\",\"".$comentarios."\",\"".$valores."\",\"".$codigos."\",\"".$observacion."\",\"".htmlentities($establecimiento)."\",\"".$sexo."\",\"".$idedad."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\") ;'>
							<input type='button' id='btnSalir' value='cerrar' onclick='Cerrar()'>
						</td>                                                                                                                                                                                                      
                                            </tr>
					</table>
                                    </td>
				</tr>";
                $imprimir.="</table>";

         echo $imprimir;

}else{

	$consulta_datos=$obj->LeerDatos($idexamen);
	$datos_generales=$obj->MostrarDatosGenerales($idsolicitud,$lugar);
	$datos_empleado=$obj->DatosEmpleado($idempleado,$lugar);
        $row_generales= pg_fetch_array($datos_generales);
	$row_area = pg_fetch_array($consulta_datos);
	$nombrearea=$row_area['nombrearea'];
	$row_empleado = pg_fetch_array($datos_empleado);
	$Consulta_Estab=$obj->Nombre_Establecimiento($lugar);
	$row_estab = pg_fetch_array($Consulta_Estab);
	$vector_idprocesos = EXPLODE("/",$codigos);
	$vector_respuesta = EXPLODE("/",$valores);
       // $datos_combos= $valores_combos;
        $vector_combos = EXPLODE("/",$valores_combos);
//print_r($vector_respuesta);

	$imprimir=" <table width='100%' border='0' align='center' >
                        <tr>
                            <td colspan='1' align='left' width='15%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                            <td align='center' colspan='4' width='70%' class='Estilo5'>
                                <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                                <p><strong>                       ".$row_estab['nombre']."</strong></p>
				<p><strong>ÁREA DE ".htmlentities($row_area['nombrearea'])." </strong></p>
                            </td>
                            <td colspan='1' align='right' width='15%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
			</tr>
                    </table>
                        <table width='100%' border='0' align='center' >
                            <td colspan='6' align='center'></td>
			</tr>
			<tr>
                            <td colspan='1' width='32%' >Establecimiento Solicitante:</td>
                            <td colspan='3' width='36%' align='left'>".$row_generales['estabext']."</td>
                            <td colspan='1' width='19%' ><strong>Fecha de Recepción:</strong></td>
                            <td colspan='1' width='13%' align='left'>".$row_generales['fecharecep']."</td>
			</tr>
			<tr>
                            <td colspan='1'><strong>Expediente</strong></td>
                            <td colspan='3' align='left'>".$row_generales['numero']."</td>
                            <td colspan='1'><strong>Fecha Toma Muestra:</strong></td>
			    <td colspan='1' align='left'>".$f_tomamuestra."</td>    
			</tr>
                        <tr>
                            <td colspan='1'><strong>Paciente:</strong></td>
                            <td colspan='5' align='left'>".htmlentities($row_generales['paciente'])."</td>
			</tr>
			<tr>
                            <td colspan='1'><strong>Edad:</strong></td>
                            <td colspan='3' align='left'>".$row_generales['edad']."</td>
                            <td colspan='1'><strong>Sexo:</strong></td>
                            <td colspan='1' align='left'>".$row_generales['sexo']."</td>
			</tr>
			<tr>
                            <td colspan='1' ><strong>Procedencia:</strong></td>
                            <td colspan='3' align='left'>".$row_generales['procedencia']."</td>
                            <td colspan='1' ><strong>Servicio:</strong></td>
                            <td colspan='1' align='left'>".$row_generales['subservicio']."</td>
			</tr>
			<tr>
                            <td colspan='1'><strong>Examen Realizado:</strong></td>
                            <td colspan='5' align='left'>".htmlentities($row_area['nombre_reporta'])."</td>
			</tr>
			<tr>
                            <td colspan='1'><strong>Validado Por:</strong></td>
                            <td colspan='5' align='left'>".htmlentities($row_empleado['empleado'])."</td>
			</tr>";
				pg_free_result($consulta_datos);
				pg_free_result($datos_generales);
                                $nomcod=$obj->ObtenerNombreCodigo($tab);
                                $row_codigo= pg_fetch_array($nomcod);
									//	echo $row_codigo[0];
            $imprimir.="<tr>
                            <td  >Resultado Tabulador:</td>
                            <td colspan='5' align='left'>".$row_codigo[0]."</td>
                        </tr>
                        <tr>
                            <td colspan='6' align='center'></td>
			</tr>
                        <tr>
                            <td colspan='6'>";
				$consulta=$obj->LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad);
	 //echo $establecimiento."$$ ".$sexo."%%".$idedad;
                    $imprimir.="<table width='100%' border='0' align='center' cellspacing='0'>
                                    <tr>
                                        <td colspan='2' width='40%' align='justify'><strong> Prueba </strong></td>
                                        <td colspan='1' width='25%' align='justify'><strong> Resultado </strong> </td>
                                        <td colspan='1' width='10%' align='justify'><strong> Unidades </strong></td>
                                        <td colspan='2' width='10%' align='justify'><strong> Rango </strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan='6'><hr style='width:100%'></td>
                                    </tr>";
                                        $pos=0;

                    while($row = pg_fetch_array($consulta))//ELEMENTOS
                    {
                       $imprimir.= "<tr>
                                        <td align='justify' colspan='2'>".htmlentities($row['nombreprocedimiento'])."</td>
                                        ";
                        if($vector_combos[$pos]== NULL){  
                            $imprimir.= "<td aalign='justify' colspan='1'><input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimiento']."'>".htmlentities($vector_respuesta[$pos]).
                                        "</td>";
                        }else{
                                $conresult=$obj->BuscarResultado($vector_respuesta[$pos]);
                                $row_dresult=  pg_fetch_array($conresult);
                            $imprimir.="<td align='justify' colspan='1'>".htmlentities($row_dresult['posible_resultado'])."<input name='oidprueba[".$pos."]' type='hidden' id='oidprueba[".$pos."]' value='".$row['idprocedimiento']."'></td>";
                        }                                                                                   
                           $imprimir.= "<td align='center' colspan='1'>".htmlentities($row['unidades'])."</td>";
                           if((!empty($row['rangoinicio'])) AND (!empty($row['rangoinicio'])))
                           $imprimir.= "<td align='center' colspan='2'>".$row['rangoinicio']."-".$row['rangofin']."</td>
                                    </tr>";
                                        $pos=$pos + 1;
                        }
                                pg_free_result($consulta);
                                $imprimir.= "  <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />
                                                 <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                                             <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' />";     
                   $imprimir.="</table>
                            <td>
                        </tr>
                        <tr>
                            <td colspan='6'><hr style='width:100%'></td>
                        </tr>
                        <tr>
                            <td colspan='1' style='font:bold'><strong>Observacion:<strong></td>
                            <td colspan='5'>".$observacion."</td>
			</tr>
                         
                        <tr>
                    <td colspan='6' class='Estilo6'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class='Estilo6'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='6' class='Estilo6'>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan='2' class='Estilo6'>&nbsp;&nbsp;</td>
                    <td colspan='1' class='Estilo6' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SELLO:</td>
                    <td colspan='1' class='Estilo6' >___________&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp</td>
                    <td colspan='1' class='Estilo6' >FIRMA:</td>
                    <td colspan='2' class='Estilo6' >________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
                </tr>
                        <tr>
                            <td colspan='5' align='center'>
                                <input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarPlantillaE()'>
				<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaE(".$idsolicitud.",\"".$idexamen."\",\"".$row_empleado['empleado']."\",\"".$row_generales['procedencia']."\",\"".$row_generales['subservicio']."\",\"".$comentarios."\",\"".$valores."\",\"".$codigos."\",\"".$observacion."\",\"".htmlentities($establecimiento)."\",\"".$sexo."\",\"".$idedad."\",\"".$valores_combos."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\");'>
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
        $fecharealiz=$_POST['fecharealiz'];
        $fecharesultado=$_POST['fecharesultado'];
        
       // echo $fecharealiz." - ".$fecharesultado;
	//echo "SOLIC".$idsolicitud."*RECEP= ".$idrecepcion."*DETALLE= ".$iddetalle."*observ= ".$observacion."*Empleado= ".$idempleado."* tab=".$tab ;

	$codigos= $_POST['codigos'];
	$valores= $_POST['valores'];
	$comentarios= $_POST['comentarios'];
        $valores_combos = $_POST['valores_combos'];
	$vector_respuesta=EXPLODE("/",$valores);
	$vector_idprocesos=EXPLODE("/",$codigos);
	$vector_comentarios=EXPLODE("/",$comentarios);
        $vector_combos = explode("/",$valores_combos);
	$obj = new clsPlantillaE;
   	$tamano_vector=count($vector_respuesta);
		//$tamano_vectorcodigos=count($vector_idprocesos);
		//$tamano_vectorcomentario=count($vector_comentarios);
        $existe = $obj->VerificarExistencia($idexamen,$idsolicitud,$iddetalle);
        if ($existe[0] >= 1){
                     echo "Ya hay un resultado para esta muestra!!";
        }else{  
	   //VALIDANDO QUE LA INFORMACION ESTE COMPLETA:
            $ultimo= $obj->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$idempleado,$usuario,$tab,$fecharealiz,$fecharesultado,$lugar);
        //echo $ultimo;
            $pos=0;
            $ban=0;
            if ($ultimo != "")
            {
                $idresultado=$ultimo;
                if (($tamano_vector-1)>0){
                    for ($i=0; $i < $tamano_vector-1 ; $i++) //INSERTANDO PROCEDIMIENTOS
                    {
                        if ($obj->insertar_detalle($idresultado,$vector_idprocesos[$pos],$vector_respuesta[$pos],$vector_comentarios[$pos],$lugar,$vector_combos[$pos])==false)
                        {
                            $ban=1;
                        }
                            $pos=$pos+1;
                    }
                }
                if($ban==0){
                                //actualiza el estado del detalle de la solicitud para indicar que el resultado esta completo para el examen
                    echo "Datos Guardados";
                    if (($obj->CambiarEstadoDetalle($iddetalle)==true)&&($obj->CambiarEstadoSolicitud($idsolicitud)==true))
                    {
                        echo " Correctamente";
                    }
                }
                else{
                    echo "Los resultados no pueden ser guardados consulte al Administrador...";
                }
            }else{ echo "Los resultados no pueden ser guardados consulte al Administrador...";}
        }
   break;

  }

?>
