<?php session_start();
include ("clsConsultarElementos.php");

$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

//variables POST

$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsConsultarElementos;
$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
$row_estab = mysql_fetch_array($Consulta_Estab);

switch ($opcion)
{
  case 1:
	$idexamen=$_POST['idexamen'];
        $fechanac=$_POST['FechaNac'];
        $sexo=$_POST['Sexo'];
       // echo $fechanac."***".$sexo;
        $Cuentadias=$objdatos->CalculoDias($fechanac);
        $Cdias= mysql_fetch_array($Cuentadias);
        $dias=$Cdias[0];

        $ConRangos=$objdatos->ObtenerCodigoRango($dias);
        $row_rangos=  mysql_fetch_array($ConRangos);
        $idedad=$row_rangos[0];

        //   echo $idedad."***".$sexo;

      //  if ($dias > 0) AND ($dias <=)
	$ConEstandar=$objdatos->Obtener_Estandar($idexamen);
	$CodEstandar= mysql_fetch_array($ConEstandar);
	$IdEstandar=$CodEstandar[0];
	//$print_r(CodEstandar);
	//echo $CodEstandar[0];
	switch ($IdEstandar){

		case "H50":

			$consulta=$objdatos->LeerElementosExamen($idexamen,$lugar);

			$imprimir="<table width='75%' border='0' align='center' class='StormyWeatherFormTABLE'>";
                            $imprimir.="<tr class='CobaltButton'>
                                            <td width='30%'> &nbsp;</td>
                                            <td width='30%' aling='center'>Resultado</td>
                                            <td width='5%' aling='center'>Unidades</td>
                                            <td width='30%' colspan='2'aling='center'>Control Normal </td>
					</tr>";
					$pos=0;
					$posele=0;
		   while($row = mysql_fetch_array($consulta))//ELEMENTOS
                    {
                       if($row['SubElemento']=="S")
                            {$imprimir.="<tr>
                                            <td colspan='5' style='font:bold' class='StormyWeatherFieldCaptionTD'>".htmlentities($row['Elemento'])."  </td>
		                         </tr>";
					$consulta2=$objdatos->LeerSubElementosExamen($row['IdElemento'],$lugar,$sexo,$idedad);
					while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
					{
			    $imprimir.= "<tr>
                                            <td width='30%' class='StormyWeatherDataTD'>".htmlentities($rowsub['SubElemento'])."</td>
                                            <td width='30%' class='StormyWeatherDataTD'>
                                                <input size='20' name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]'>
						<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['IdSubElemento']."'>
                                            </td>
                                            <td width='5%' class='StormyWeatherDataTD'>".htmlentities($rowsub['Unidad'])."</td>
                                            <td width='30%' class='StormyWeatherDataTD'>
                                                <input name='txtcontrol[".$pos."]' type='text' id='txtcontrol[".$pos."]'>
                                            </td>
                                            <td class='StormyWeatherDataTD'>".htmlentities($rowsub['Unidad'])."</td>
					</tr>";
                                            $pos=$pos + 1;
					}
					mysql_free_result($consulta2);
                            $imprimir.="<tr>
                                            <td colspan='5' class='StormyWeatherDataTD'>".htmlentities($row['ObservElem'])."</td>
					</tr>
					<tr>
                                            <td colspan='5' class='StormyWeatherDataTD'>&nbsp;</td>
					</tr>";
				}//del if
				else
				{
                            $imprimir.="<tr>
                                            <td class='StormyWeatherFieldCaptionTD' style='font:bold'>".htmlentities($row['Elemento'])."</td>
                                            <td class='StormyWeatherDataTD'>
                                                <input size='20' name='txtresultadoele[".$posele."]' type='text' id='txtresultadoele[".$posele."]'>
						<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['IdElemento']."'>
                                            </td>
                                            <td class='StormyWeatherDataTD'>".htmlentities($row['UnidadElem'])."</td>
                                            <td class='StormyWeatherDataTD' width='30%'>
                                             name='txtcontrolele[".$posele."]' type='text' id='txtcontrolele[".$posele."]'>
                                            </td>
                                            <td width='10%' class='StormyWeatherDataTD'>".htmlentities($row['UnidadElem'])."</td>
					</tr>";
				$posele=$posele + 1;
				$imprimir.= "
					<tr>
                                            <td rowspan='1' colspan='5' class='StormyWeatherDataTD'>".htmlentities($row['ObservElem'])."</td>
					</tr>
					<tr>
                                            <td colspan='5' class='StormyWeatherDataTD'>&nbsp;</td>
					</tr>";
				}//else
			}//while
                        mysql_free_result($consulta);

			$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
			$imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
			$imprimir.="
                                        <tr><td colspan='2'  class='StormyWeatherDataTD'>*Resultado Tabulador</td><TD colspan='3' class='StormyWeatherDataTD'>
                                            <select id='cmbTabulador' name='cmbTabulador' size='1'>
                                                <option value='0' >--Seleccione Resultado--</option>";
							$conResult=$objdatos->LlenarResultados($IdEstandar);
							while ($rows =mysql_fetch_array($conResult)){
                                    $imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ". htmlentities($rows[1])."</option>";
							}
                                $imprimir.="</select></TD></tr>";

                               $imprimir.="<tr>
                                        	<td colspan='5'>
						<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaB()'>
                                                </td>
                                           </tr>
				</table>";
			echo $imprimir;
			break;

	default:

		$consulta=$objdatos->LeerElementosExamen($idexamen,$lugar);

		$imprimir="<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr  class='CobaltButton' >
					<td  >&nbsp;</td>
					<td aling='center'>Resultado</td>
					<td aling='center'>Unidades</td>
                                        <td aling='center'>Rangos de Referencia</td>
				</tr>";
			$pos=0;
			$posele=0;
		while($row = mysql_fetch_array($consulta))//ELEMENTOS
		{
                    if($row['SubElemento']=="S")
                    {   $imprimir.= "
				<tr class='StormyWeatherFieldCaptionTD'>
					<td colspan='4' style='font:bold'>".htmlentities($row['Elemento'])."</td>
				</tr>";
				$consulta2=$objdatos->LeerSubElementosExamen($row['IdElemento'],$lugar,$sexo,$idedad);

				while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
				{
				$imprimir.= "
				<tr>
					<td width='30%' class='StormyWeatherDataTD'>".htmlentities($rowsub['SubElemento'])."</td>
					<td width='25%' class='StormyWeatherDataTD'>
						<input size='30' name='txtresultadosub[".$pos."]' type='text' id='txtresultadosub[".$pos."]'>
						<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['IdSubElemento']."'>
					</td>
					<td width='20%' align='center' class='StormyWeatherDataTD'>".htmlentities($rowsub['Unidad'])."
                                        <td width='20%' align='center' class='StormyWeatherDataTD'>".$rowsub['rangoinicio']." - ".$rowsub['rangofin']."
						<input name='txtcontrol[".$pos."]' type='hidden' id='txtcontrol[".$pos."]'>
					</td>
				</tr>";
					$pos=$pos + 1;
					}//while interno
		   $imprimir.= "<tr>
					<td colspan='4' class='StormyWeatherDataTD'>".htmlentities($row['ObservElem'])."</td>
				</tr>
				<tr>
					<td colspan='4' class='StormyWeatherDataTD'>&nbsp;</td></tr>";
			}//if
			else
			{
			$imprimir.="<tr>
					<td style='font:bold'>".htmlentities($row['Elemento'])."</td>
					<td>
						<input name='txtresultadoele[".$posele."]' type='text' id='txtresultadoele[".$posele."]'>
						<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['IdElemento']."'>
					</td>
					<td class='StormyWeatherDataTD'>".htmlentities($row['UnidadElem'])."
						<input name='txtcontrol[".$posele."]' type='hidden' id='txtcontrol[".$posele."]'>
					</td>
				</tr>";
					$posele=$posele+1;
					$imprimir.= "
				<tr>
					<td colspan='4' class='StormyWeatherDataTD'>".htmlentities($row['ObservElem'])."</td>
				</tr>
				<tr>
					<td colspan='4' class='StormyWeatherDataTD'>&nbsp;</td></tr></table>";
			}//else
		}//while externo
			mysql_free_result($consulta);
			$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
			$imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
			$imprimir.="<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
						<tr><td class='StormyWeatherDataTD'>*Resultado Tabulador</td><TD colspan='3' class='StormyWeatherDataTD'>
						<select id='cmbTabulador' name='cmbTabulador' size='1'>
						<option value='0' >--Seleccione Resultado--</option>";
						$conResult=$objdatos->LlenarResultados($IdEstandar);
						while ($rows =mysql_fetch_array($conResult)){
			$imprimir.="<option value='" . $rows[0] ."' >".$rows[0]." - ".htmlentities($rows[1])."</option>";
							}
			$imprimir.="</select></TD></tr></table>
				<table width='70%' border='0' align='center' class='StormyWeatherFormTABLE'>
					<tr class='StormyWeatherDataTD'><td colspan='3' class='StormyWeatherDataTD'>
							<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaB()'>
						</td>
					</tr>
				</table>";
			echo $imprimir;
			break;
		}

   break;

   case 2://MOSTRANDO VISTA PREVIA DE LOS RESULTADOS INGRESADOS
   	$idexamen=$_POST['idexamen'];//*
	$idsolicitud= $_POST['idsolicitud'];
	$idrecepcion= $_POST['idrecepcion'];
	$iddetalle= $_POST['iddetalle'];
	$observacion= $_POST['observacion'];
	$idempleado= $_POST['idempleado'];
	$valores_subelementos=$_POST['valores_subelementos'];
	$codigos_subelementos=$_POST['codigos_subelementos'];
	$valores_elementos=$_POST['valores_elementos'];
	$codigos_elementos=$_POST['codigos_elementos'];
	$controles=$_POST['controles'];
	$controles_ele=$_POST['controles_ele'];
        $establecimiento=$_POST['estab'];
	$tab=$_POST['tab'];
        $fechanac=$_POST['fechanac'];
        $sexo=$_POST['sexo'];
	//echo $fechanac."-".$sexo;


        $Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
	$row_estab = mysql_fetch_array($Consulta_Estab);
	$ConEstandar=$objdatos->Obtener_Estandar($idexamen);
	$CodEstandar= mysql_fetch_array($ConEstandar);
	$IdEstandar=$CodEstandar[0];

        $Cuentadias=$objdatos->CalculoDias($fechanac);
        $Cdias= mysql_fetch_array($Cuentadias);
        $dias=$Cdias[0];

        $ConRangos=$objdatos->ObtenerCodigoRango($dias);
        $row_rangos=  mysql_fetch_array($ConRangos);
        $idedad=$row_rangos[0];
          // echo $idedad."***".$sexo;
//echo $IdEstandar;
		switch ($IdEstandar){

		case "H50":
			$cadena=$valores_subelementos;
			$vector=EXPLODE("/",$cadena);
			$vector_elementos=EXPLODE("/",$valores_elementos);
			$vector_controles=EXPLODE("/",$controles);
			$vector_controles_ele=EXPLODE("/",$controles_ele);
			$objdatos = new clsConsultarElementos;
			$consulta=$objdatos->LeerElementosExamen($idexamen,$lugar);
			$consulta_datos=$objdatos->LeerDatos($idexamen);
			$datos_generales=$objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
			$datos_empleado=$objdatos->DatosEmpleado($idempleado,$lugar);
			$row_generales= mysql_fetch_array($datos_generales);
			$row_area = mysql_fetch_array($consulta_datos);
			$row_empleado = mysql_fetch_array($datos_empleado);

	$imprimir="<table width='92%' align='center'class='StormyWeatherFormTABLE' >
			   <tr>
				<td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                                    <td align='center' colspan='4' width='60%' class='Estilo5'>
                                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                                    <p><strong>".htmlentities($row_estab['Nombre'])."</strong></p>
                                    <p><strong>ÁREA DE ".htmlentities($row_area['NombreArea'])." </strong></p>
				</td>
                                <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                           </tr>
			   <tr>
                            	<td colspan='1'><strong>Establecimiento Solicitante:</strong></td>
				<td colspan='2'>".htmlentities( $establecimiento)."</td>
                                <td colspan='1'><strong>Fecha Recepción:</strong></td>
                            	<td colspan='2'>".$row_generales['Fecha']."</td><input name='suEdad' id='suEdad'  type='hidden'  value='".$row_generales['FechaNacimiento']."'/>
                           </tr>
			   <tr>
				<td colspan='1'><strong>NEC</strong></td>
				<td colspan='2'>".$row_generales['IdNumeroExp']."</td></tr>

			   <tr> <td colspan='1'><strong>Paciente:</strong></td>
				<td colspan='5'>".$row_generales['NombrePaciente']."</td>
			   </tr>
			   <tr>
				<td colspan='1'><strong>Edad:</strong></td>
				<td colspan='2'>
                                    <div id='divsuedad'>
                                    </div>
				</td>
				<td colspan='1'><strong>Sexo:</strong></td>
				<td colspan='2'>".$row_generales['Sexo']."</td>
                            </tr>
                            <tr>
                                <td colspan='1'><strong>Procedencia:</strong></td>
				<td colspan='2'>".$row_generales['Procedencia']."</td>

				<td colspan='1'><strong>Servicio:</strong></td>
				<td colspan='2'>".$row_generales['Origen']."</td>
			   </tr>
			   <tr>
				<td colspan='1'><strong>Examen Realizado:</strong></td>
				<td colspan='5'>".htmlentities($row_area['NombreExamen'])."</td>
			   </tr>
			   <tr>
				<td colspan='1'><strong>Validado Por:</strong></td>
				<td colspan='5'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
			   </tr>
			   <tr>
				<td colspan='1'><strong>Observacion:</strong></td>
				<td colspan='4'>".htmlentities($observacion)."</td>
			   </tr>";
				$nomcod=$objdatos->ObtenerNombreCodigo($tab);
                                $row_codigo= mysql_fetch_array($nomcod);
										// echo $row_codigo[0];
               $imprimir.="<tr>
                                <tdcolspan='1' >Resultado Tabulador:</td>
                                <td colspan='4'>".$row_codigo[0]."</td>
                           </tr>
		</table>";

    $imprimir.="<table width='89%' border='0' align='center' class='StormyWeatherFormTABLE'>";
							mysql_free_result($consulta_datos);
							mysql_free_result($datos_generales);
		$imprimir.="<tr class='CobaltButton'>
						<td width='35%'></td>
						<td width='25%'>Resultado</td>
						<td width='20%'>Unidades</td>
						<td width='60%'colspan='2'>Control Normal </td>
			    </tr>";
				  $pos=0;
				  $posele=0;
			while($row = mysql_fetch_array($consulta))//ELEMENTOS
			{

				if($row['SubElemento']=="S")
				{   $imprimir.= "
		            <tr class='StormyWeatherFieldCaptionTD'>
                                <td colspan='5' style='font:bold'><strong>".htmlentities($row['Elemento'])."</strong></td>
			    </tr>";
					$consulta2=$objdatos->LeerSubElementosExamen($row['IdElemento'],$lugar,$sexo,$idedad);

					while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
					{
					$imprimir.=
			   "<tr>
				<td width='35%'>".htmlentities($rowsub['SubElemento'])."</td>
				<td width='25%'>".htmlentities($vector[$pos]).
						"<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['IdSubElemento']."'>
				</td>
				<td width='20%'>".htmlentities($rowsub['Unidad'])."</td>
				<td width='40%'>".htmlentities($vector_controles[$pos])." ".htmlentities($rowsub['Unidad'])." </td>
			    </tr>";
						$pos=$pos + 1;
					}

					$imprimir.= "
                            <tr>
				<td colspan='4'>".htmlentities($row['ObservElem'])."</td>
		            </tr>
			    <tr>
                            	<td colspan='5'>&nbsp;</td>
                            </tr>";
				}
				else
				{
	       $imprimir.= "<tr>
				<td width='40%' style='font:bold' class='StormyWeatherFieldCaptionTD'><strong>".htmlentities($row['Elemento'])."</strong>
				</td>
				<td  width='25%'>"
				.htmlentities($vector_elementos[$posele]).
					"<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['IdElemento']."'>
				</td>
                            	<td width='10%'>".htmlentities($row['UnidadElem'])."</td>
				<td>"
			   .htmlentities($vector_controles_ele[$posele])."  ".htmlentities($row['UnidadElem'])."
				</td>
			   </tr>";
					$posele=$posele+1;
					$imprimir.= "
			   <tr>
				<td colspan='5'>".htmlentities($row['ObservElem'])."</td>
			   </tr>
			   <tr>
				<td colspan='6'>&nbsp;</td></tr>";

					}

				}
				mysql_free_result($consulta);
                    $imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
                    $imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>"; //elementos


		  $imprimir .="<td colspan='3' align='right'>
                                    <input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaB()'>
                                    <input type='button' name='Imprimir'  id='Imprimir' value='Imprimir'
                                    Onclick='ImprimirPlantillaB(".$idsolicitud.",\"".$idexamen."\",\"".$idempleado."\",\"".htmlentities($row_generales['Procedencia'])."\",\"".htmlentities($row_generales['Origen'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['NombreArea'])."\",\"".htmlentities( $establecimiento)."\",\"".htmlentities($row_empleado['NombreEmpleado'])."\") ;'>
                                    <input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
                                </td>
                            </tr>
	        	</table>";
			echo $imprimir;
		break;

		default:
			$cadena=$valores_subelementos;
			$vector=EXPLODE("/",$cadena);
			$vector_elementos=EXPLODE("/",$valores_elementos);
			//$obj = new clsConsultarElementos;
			$consulta=$objdatos->LeerElementosExamen($idexamen,$lugar);
			$consulta_datos=$objdatos->LeerDatos($idexamen);
			$datos_generales=$objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
			$datos_empleado=$objdatos->DatosEmpleado($idempleado,$lugar);
			$row_generales= mysql_fetch_array($datos_generales);
			$row_area = mysql_fetch_array($consulta_datos);
			$row_empleado = mysql_fetch_array($datos_empleado);
			$nombreEmpleado=$row_empleado['NombreEmpleado'];
                       // echo $row_generales['FechaNacimiento'];
			$imprimir="<br>
                            <table width='95%' border='0' align='center' class='StormyWeatherFormTABLE'>
				<tr>
                                    <td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                                    <td align='center' colspan='4' width='60%' class='Estilo5'>
				 	<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
				 	<p><strong>".$row_estab['Nombre']."</strong></p>
				 	<p><strong>&Aacute;REA DE ".htmlentities($row_area['NombreArea'])." </strong></p></td>
                                    <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
				</tr>
				<tr>
					<td colspan='1'><strong>Establecimiento Solicitante:</strong></td>
					<td colspan='2'>".htmlentities($establecimiento)."</td>
					<td colspan='1'><strong>Fecha Recepción:</strong></td>
					<td colspan='2'>".$row_generales['Fecha']."</td>
                                            <input name='suEdad' id='suEdad' type='hidden' value='".$row_generales['FechaNacimiento']."'/>
				</tr>
				<tr>
					 <td colspan='1'><strong>NEC</strong></td>
					 <td colspan='1'>".$row_generales['IdNumeroExp']."</td>
                                </tr>
				<tr>
                                        <td colspan='1'><strong>Paciente:</strong></td>
					<td colspan='2'>".htmlentities($row_generales['NombrePaciente'])."</td>
                                </tr>
				<tr>

					<td colspan='1'><strong>Edad:</strong></td>
					<td colspan='2'>
                                            <div id='divsuedad'>

                                            </div>
                                        </td>
					<td colspan='1'><strong>Sexo:</strong></td>
					<td colspan='2'>".$row_generales['Sexo']."</td>
				</tr>
				<tr>
					<td colspan='1'><strong>Procedencia:</strong></td>
					<td colspan='2'>".htmlentities($row_generales['Procedencia'])."</td>
					<td colspan='1'><strong>Servicio:</strong></td>
					<td colspan='2'>".htmlentities($row_generales['Origen'])."</td>
				</tr>
				<tr>
					 <td colspan='1'><strong>Examen Realizado:</strong></td>
					 <td colspan='5'>".htmlentities($row_area['NombreExamen'])."</td>
				</tr>

				<tr>
					 <td colspan='1'><strong>Validado Por:</strong></td>
					 <td colspan='5'>".htmlentities($row_empleado['NombreEmpleado'])."</td>
				 </tr>
				 <tr>
					 <td colspan='1'><strong>Observacion:</strong></td>
					 <td colspan='5'>".htmlentities($observacion)."</td>
				 </tr>";
				$nomcod=$objdatos->ObtenerNombreCodigo($tab);
                                $row_codigo= mysql_fetch_array($nomcod);
							// echo $row_codigo[0];
		     $imprimir.="<tr>
                                         <td>Resultado Tabulador:</td><td colspan='5'>".$row_codigo[0]."</td>
                                 </tr>
                        </table>";

		     $imprimir.="<table width='95%' border='0' align='center' >
				 ";
                    $imprimir.= "<tr>
                                    <td width='25%'></td>
                                    <td width='25%'>Resultado</td>
                                    <td width='10%'>Unidades</td>
                                    <td width='15%'>Rangos de referencia</td>
				</tr>";
			$pos=0;
			$posele=0;
			while($row = mysql_fetch_array($consulta))//ELEMENTOS
			{

		  if($row['SubElemento']=="S")
		  {$imprimir.= "<tr>
                                    <td colspan='4' style='font:bold'><strong>".htmlentities($row['Elemento'])."</strong></td>
                                </tr>";
					$consulta2=$objdatos->LeerSubElementosExamen($row['IdElemento'],$lugar,$sexo,$idedad);

				while($rowsub = mysql_fetch_array($consulta2))//SUBELEMENTOS
				{
                     $imprimir.="<tr>
                                    <td width='35%'>".htmlentities($rowsub['SubElemento'])."</td>

<td width='25%'>".htmlentities($vector[$pos]).
						  "<input name='oidsubelemento[".$pos."]' type='hidden' id='oidsubelemento[".$pos."]' value='".$rowsub['IdSubElemento']."'>
					  </td>
                                	  <td width='10%'>".htmlentities($rowsub['Unidad'])."</td>";
                                      if (empty($rowsub['rangoinicio']) AND empty($rowsub['rangofin']))
                              $imprimir.= "<td >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

                                      else
                              $imprimir.= "<td width='15%'>".$rowsub['rangoinicio']." - ".$rowsub['rangofin']."</td> ";
			$imprimir.="</tr>";
						$pos=$pos + 1;
				}
			$imprimir.= "<tr>
                                           <td colspan='5'>".htmlentities($row['ObservElem'])."</td>
                                    </tr>";
				}
				else
				{
			$imprimir.= "<tr>
                                            <td style='font:bold'  class='StormyWeatherFieldCaptionTD'>".htmlentities($row['Elemento'])."</td>
                                            <td>".htmlentities($vector_elementos[$posele]).
                                            "<input name='oidelemento[".$posele."]' type='hidden' id='oidelemento[".$posele."]' value='".$row['IdElemento']."'>			  </td>
                                            <td width='25%'>".htmlentities($row['UnidadElem'])."</td>
                                     </tr>";
					$posele=$posele+1;
			 $imprimir.= "<tr>
                                            <td colspan='5'>".htmlentities($row['ObservElem'])."</td>
                                      </tr>";
				}

			}

			mysql_free_result($consulta);
			mysql_free_result($consulta_datos);
			mysql_free_result($datos_generales);

			$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>"	;	//numero de cajas de texto dibujadas para subelementos
			$imprimir .="<input  type='hidden' id='ocultoele' value='".$posele."'>" ; //elementos
			$imprimir.="<tr>
                                        <td colspan='5' align='center' >
                                            <input type='button' id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaB()'>
                                            <input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaB(".$idsolicitud.",\"".$idexamen."\",\"".$idempleado."\",\"".$row_generales['Sexo']."\",\"".$row_generales['FechaNacimiento']."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_subelementos)."\",\"".$codigos_subelementos."\",\"".htmlentities($valores_elementos)."\",\"".$codigos_elementos."\",\"".htmlentities($controles)."\",\"".htmlentities($controles_ele)."\",\"".htmlentities($row_area['NombreArea'])."\",\"". $establecimiento."\",\"".htmlentities($row_empleado['NombreEmpleado'])."\",\"".$sexo."\",\"".$idedad."\") ;'>
                                            <input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
                                        </td>
                                    </tr>
                            </table>";
				echo $imprimir;
			break;
		}
	break;

	case 3://GUARDANDO DATOS DE LOS RESULTADOS EN LAS TABLAS
		$idexamen=$_POST['idexamen'];//*
		$idsolicitud= $_POST['idsolicitud'];
		$idrecepcion= $_POST['idrecepcion'];
		$iddetalle= $_POST['iddetalle'];
		$procedencia= $_POST['procedencia'];
		$origen= $_POST['origen'];
		$observacion= $_POST['observacion'];
		$idempleado= $_POST['idempleado'];
		$valores_subelementos=$_POST['valores_subelementos'];
		$codigos_subelementos=$_POST['codigos_subelementos'];
		$valores_elementos=$_POST['valores_elementos'];
		$codigos_elementos=$_POST['codigos_elementos'];
		$controles=$_POST['controles'];
		$controles_ele=$_POST['controles_ele'];
		$vector=EXPLODE("/",$valores_subelementos); //SON LOS SUB-ELEMENTOS
                $vector_elementos=EXPLODE("/",$valores_elementos);
		$v_id_elementos=EXPLODE("/",$codigos_elementos);
		$v_id_subelementos=EXPLODE("/",$codigos_subelementos);
		$tamano_vector=count($vector);
		$tamano_vectorele=count($vector_elementos);
		$vector_controles=EXPLODE("/",$controles);
		$vector_controles_ele=EXPLODE("/",$controles_ele);
		$tab=$_POST['tab'];
  //VALIDANDO QUE LA INFORMACION ESTE COMPLETA:
   $ultimo= $objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$idempleado,$usuario,$tab,$lugar);
  $pos=0;
  $posele=0;
  $ban=0;
  if ($ultimo != "")
  {
	$idresultado=$ultimo;
	if (($tamano_vectorele-1)>0){
		for ($i=0; $i < $tamano_vectorele-1 ; $i++) //INSERTANDO ELEMENTOS
		{
			 if ($objdatos->insertar_elementos($idresultado,$v_id_elementos[$posele],$vector_elementos[$posele],$vector_controles_ele[$posele],$lugar)==false)
			 {
				 $ban=1;
			  }
			$posele=$posele+1;
		}
	}
	if (($tamano_vector-1)>0){
		for ($i=0; $i < $tamano_vector-1 ; $i++)//INSERTANDO SUB-ELEMENTOS
		{
			 if ($objdatos->insertar_subelementos($idresultado,$v_id_subelementos[$pos],$vector[$pos],$vector_controles[$pos],$lugar)==false)
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
		if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true))
	     {
			echo " Correctamente";
		}

	}
	else
	{
		echo "Los resultados no pueden ser guardados consulte al Administrador [OBJETOS]...";
	}

  }
  else{ echo "Los resultados no pueden ser guardados consulte al Administrador... ultimo->".$ultimo;}
   break;

 }

?>
