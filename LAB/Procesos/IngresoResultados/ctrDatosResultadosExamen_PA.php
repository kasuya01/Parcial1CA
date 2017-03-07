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
        $fechanac=$_POST['FechaNac'];
        $sexo=$_POST['Sexo'];
        $IdEstandar=$_POST['IdEstandar'];
        $IdHistorial=$_POST['idhistorial'];
        $fecha_realizacion=$_POST['fecha_realizacion'];
        $fecha_reporte=$_POST['fecha_reporte'];
        $txtnec=$_POST['txtnec'];
        $observaciongnral=$_POST['observaciongnral'];
        $consulta2 = $objdatos->MostrarResultadoGeneralesPA1($idsolicitud, $lugar, $idarea);
        $row2 = pg_fetch_array($consulta2);
         $nombre = $row2['nombrearea'];
        $id_establecimiento_externo = $row2['id_establecimiento_externo'];
        $idhistoref = $row2['idhistoref'];
       // echo "CTR ".$IdHistorial;
       // echo $fechanac."***".$sexo."***".$idarea."***".$idsolicitud."***".$IdEstandar."***".$IdHistorial;
          $datospaciente=$objdatos->MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $txtnec, $idhistoref);
        $rowpa = pg_fetch_array($datospaciente);
        $dias = $rowpa['dias'];
        echo '<input type="hidden" id="dias" nombre="dias" value="'.$dias.'">';
       // echo 'dias: '.$dias;
   //    $Cuentadias=$objdatos->CalculoDias($fechanac);
   //    $Cdias= mysql_fetch_array($Cuentadias);
   //    $dias=$Cdias[0];

        $ConRangos=$objdatos->ObtenerCodigoRango($dias);
        $row_rangos= pg_fetch_array($ConRangos);
        $idedad=$row_rangos[0];

	$imprimir="";

	$consulta=$objdatos->DatosExamenes($idsolicitud,$idarea,$lugar,$sexo,$idedad, $lugar);
        if (pg_num_rows($consulta) >0){
	//echo "<table width='65%'  align='center' border='0' class='StormyWeatherFormTABLE'><tr>";
	echo "<table style='width:65%'  align='center' border='0' class='table table-bordered table-condensed table-white no-v-border'>";
	$pos=0;
	 while($row = pg_fetch_array($consulta))
	 {
	    $imprimir= "<tr><td colspan='4'><div class='panel panel-info'>"
           . "<div class='panel-heading' style='background-color:#2C81AB; border-color:#37666F; color:#ffffff'>
                                                        <h3 class='panel-title'>".$row['nombre_examen']." <input name='oiddetalle[".$pos."]' type='hidden' id='oiddetalle[".$pos."]' value='".$row['iddetallesolicitud']."'>
                                    <input name='oidexamen[".$pos."]' type='hidden' id='oidexamen[".$pos."]' value='".$row['id_conf_examen_estab']."'>
                                    <input name='oidexametodologia[".$pos."]' type='hidden' id='oidexametodologia[".$pos."]' value='".$row['idexametodologia']."'></h3>
                                                    </div>
                                                    <div class='panel-body'>
                                                        <div class='container-fluid'>
                                                        <table  class='table table-white'>
                                                        <tr>
                                <th width='30%'>Resultado</td>
				<td width='35%' >
                                    <input name='txtresultado[".$pos."]' type='text' size='43' id='txtresultado[".$pos."]' style='width:100%' placeholder='Ingrese el resultado'>
				</td><td>Unidades:</td>
                                <td width='10%' align='left'>".$row['unidades']."</td></tr>
				<tr><td>Rangos Normales:</td>
                                <td width='15%'  align='left' colspan='2'>".$row['rangos']."</td><td colspan='2'></td>
                            </tr>
                            <tr >
                                <th  width='30%' >Observaci&oacute;n</td>
				<td width='30%' colspan='3'>
                                    <textarea name='txtobser[".$pos."]' cols='52' style='width:100%' id='txtobser[".$pos."]'>".$observaciongnral."</textarea>
				</td>
                            </tr>

                                                        </table>
                                                        </div>
                                                    </div></td></tr>";
//           $imprimir.= "
//                            <tr >
//                                <th class='CobaltButton' width='30%'>Prueba Realizada </th>
//				<td width='35%' class='StormyWeatherDataTD'  >".$row['nombre_examen']."
//                                    <input name='oiddetalle[".$pos."]' type='hidden' id='oiddetalle[".$pos."]' value='".$row['iddetallesolicitud']."'>
//                                    <input name='oidexamen[".$pos."]' type='hidden' id='oidexamen[".$pos."]' value='".$row['id_conf_examen_estab']."'>
//                                    <input name='oidexametodologia[".$pos."]' type='hidden' id='oidexametodologia[".$pos."]' value='".$row['idexametodologia']."'>
//                                </td>
//                                <th class='CobaltButton' width='10%' >Unidades</td>
//				<th class='CobaltButton' align='center' width='15%' >Rangos Normales </td>
//                            </tr>
//                            <tr>
//                                <th class='CobaltButton' width='30%'>Resultado</td>
//				<td width='35%' >
//                                    <input name='txtresultado[".$pos."]' type='text' size='43' id='txtresultado[".$pos."]' style='width:100%'>
//				</td>
//                                <td width='10%' align='center'>".$row['unidades']."</td>
//				<td width='15%'  align='center'>".$row['rangoinicio']."-".$row['rangofin']."</td>
//                            </tr>";
                           /* <tr class='CobaltButton'>
                                <td width='30%' class='StormyWeatherFieldCaptionTD'>Lectura</td>
				<td wadministradoridth='30%' class='StormyWeatherDataTD' colspan='7'>
                                    <textarea name='txtlectura[".$pos."]' cols='50' id='txtlectura[".$pos."]'></textarea>
				</td>
                            <tr class='CobaltButton'>
				<td width='30%' class='StormyWeatherFieldCaptionTD'>Interpretaci&oacute;n</td>
				<td width='30%' class='StormyWeatherDataTD' colspan='7'>
                                    <textarea name='txtinter[".$pos."]' cols='50' id='txtinter[".$pos."]'></textarea>
				</td>
                            </tr>*/
//              $imprimir.="  <tr >
//                                <th class='CobaltButton' width='30%' >Observaci&oacute;n</td>
//				<td width='30%' colspan='3'>
//                                    <textarea name='txtobser[".$pos."]' cols='52' style='width:100%' id='txtobser[".$pos."]'></textarea>
//				</td>
//                            </tr>
//						"; //numero de cajas de texto dibujadas para resultados
								  // $imprimir .="</td></tr> ";
					//numero de cajas de texto dibujadas para resultados
					$pos=$pos + 1;

				echo $imprimir;
			 }
				//echo $pos;
			  echo "</tr>
					<tr>
						<td colspan='4' align='right'>
						<input  type='hidden' name='oculto' id='oculto' value='".$pos."'> <br/>
						<button type='button' name='Submit' value='Vista Previa de Resultados' class='btn btn-primary'  onclick='MostrarVistaPreviaPlantillaA1()'><span class='glyphicon glyphicon-file'></span>&nbsp;Vista Previa de Resultados</button>
						</td>
					</tr>
		</table></div>";
        }
        else{
            echo '<center><br><br><h1><img src="../../../Imagenes/warning.png" valign="middle"/>'
            . 'Favor de Revisar la correcta configuración de los exámenes solicitados, tanto en tipo de muestra y datos fijos.</h1> ';
            echo " <button type='submit' class='fg-button ui-state-default ui-corner-all' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-sign'></span>Cerrar</button></center>";

        }
       //echo $imprimir;

   break;

   case 2:
		$responsable=$_POST['idempleado'];
		$idsolicitud=$_POST['idsolicitud'];
		$idarea=$_POST['idarea'];
		$idrecepcion=$_POST['idrecepcion'];
		$procedencia=$_POST['procedencia'];
		$origen=$_POST['origen'];
		$valores_resultados=$_POST['valores_resultados'];
		$codigos_resultados=$_POST['codigos_resultados'];
		//$valores_lecturas=$_POST['valores_lecturas'];
		//$valores_inter=$_POST['valores_inter'];
		$valores_obser=$_POST['valores_obser'];
		$codigos_examenes=$_POST['codigos_examenes'];
		$examen_metodologia=$_POST['examen_metodologia'];
                $establecimiento=$_POST['estab'];
                $fechanac=$_POST['fechanac'];
                $sexo=$_POST['sexo'];
               // $dias=$_POST['dias'];
                $txtnec=$_POST['txtnec'];
                $fecha_realizacion=$_POST['fecha_realizacion'];
                $fecha_reporte=$_POST['fecha_reporte'];
               // $Cuentadias=$objdatos->CalculoDias($fechanac);
               // $Cdias= pg_fetch_array($Cuentadias);
               // $dias=$Cdias[0];

                //$ConRangos=$objdatos->ObtenerCodigoRango($dias);
                //$row_rangos=  pg_fetch_array($ConRangos);
                //$idedad=$row_rangos[0];

                $Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
		$row_estab = pg_fetch_array($Consulta_Estab);

                $consulta = $objdatos->MostrarResultadoGeneralesPA1($idsolicitud, $lugar, $idarea);
                $row = pg_fetch_array($consulta);
                $nombre = $row['nombrearea'];
                $id_establecimiento_externo = $row['id_establecimiento_externo'];
                $idhistoref = $row['idhistoref'];

                $datospaciente=$objdatos->MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $txtnec, $idhistoref);
                $rowpa = pg_fetch_array($datospaciente);
                $dias = $rowpa['dias'];

                $ConRangos = $objdatos->ObtenerCodigoRango($dias);
                $row_rangos = pg_fetch_array($ConRangos);
                $idedad = $row_rangos[0];

                /*$consulta=$objdatos->MostrarResultadoGenerales1($idsolicitud,$idarea,$lugar);
		$row = mysql_fetch_array($consulta);
		$nombre=$row['NombreArea'];*/
		$vector_resultados=EXPLODE("/",$valores_resultados);
		$vector_codigos=EXPLODE("/",$codigos_resultados);
		//$vector_lecturas=EXPLODE("/",$valores_lecturas);
		//$vector_inter=EXPLODE("/",$valores_inter);
		$vector_obser=EXPLODE("/",$valores_obser);
		$vector_examenes=EXPLODE("/",$codigos_examenes);
		$vector_exametodologia=EXPLODE("/",$examen_metodologia);
              //  echo $vector_exametodologia;
		$consulta=$objdatos->DatosExamenes($idsolicitud,$idarea,$lugar,$sexo,$idedad, $lugar);

$Imprimir="<table width='100%' align='center' class='StormyWeatherFormTABLE'>
            <tr>
                <td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                <td align='center' colspan='4' width='60%' class='Estilo5'>
                    <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                    <p><strong>                       ".$row_estab['nombre']."</strong></p>
                    <p><strong>&Aacute;REA DE ".$nombre." </strong></p>
		</td>
                <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
            </tr>
            <tr>
		<td colspan='6' align='center'>&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
		<td colspan='1' style='font:bold'><strong>Establecimiento Solicitante:</strong></td>
		<td colspan='2' style='font:bold'>".$establecimiento."</td>
		<td colspan='1' style='font:bold'><strong>Fecha Recepci&oacute;n:</strong></td>
		<td colspan='2' style='font:bold'>".$row['fecharecepcion']."</td>
                    <input name='suEdad' id='suEdad'  type='hidden'  value='".$rowpa['fecha_nacimiento']."'/>
            </tr>
            <tr>
		<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
		<td colspan='2' style='font:bold'>".$txtnec."</td>
                <td colspan='1' style='font:bold'><strong>Fecha Reporte:</strong></td>
		<td colspan='2' style='font:bold'>" . $fecha_reporte . "</td>
            </tr>
            <tr>
                <td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
		<td colspan='5'style='font:bold'>".$rowpa['nombre']."</td>
            </tr>
            <tr>
		<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
		<td colspan='2' style='font:bold'>".$rowpa['edad']."

		</td>
		<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
		<td colspan='2' style='font:bold'>".$rowpa['sexo']."</td>
            </tr>
            <tr>
            	<td colspan='1' style='font:bold'><strong>Procedencia:</strong></td>
		<td colspan='2' style='font:bold'>".$procedencia."</td>
		<td colspan='1' style='font:bold'><strong>Origen:</strong></td>
		<td colspan='2' style='font:bold'>".$origen."</td>
            </tr>
            <tr>";
		$consulta_empleado=$objdatos->BuscarEmpleadoValidador($responsable, $lugar);
		$fila_empleado = pg_fetch_array($consulta_empleado);//$fila_empleado['NombreEmpleado'].
		$Imprimir.="
		<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
		<td  colspan='5' style='font:bold'>".$fila_empleado['empleado']."</td>
	    </tr>
            <tr>
		<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
		<td colspan='6' align='center' ><hr>&nbsp;DETALLE DE RESULTADOS</td>
            </tr>
            <tr>
                <td colspan='6'>";
	$Imprimir.="
                      <table width='100%'  align='center' border='0' class='StormyWeatherFormTABLE'>
                        <tr >
                            <td align='left'>&nbsp;&nbsp;&nbsp Prueba Realizada </td>
                            <td align='center'>Resultado</td>
                            <td align='center'>Unidades</td>
                            <td align='center'>Rangos Normales </td>";
                        /*    <td align='center'>Lectura</td>
                            <td align='center'>Interpretaci&oacute;n</td>*/
              $Imprimir.="  <td align='left'>Observaci&oacute;n</td>
                        </tr>
                        <tr><td colspan='5'><hr></td></tr>";
                            $pos=0;
                while($row = pg_fetch_array($consulta))
                {
             $Imprimir.="<tr>
                             <td width='35%' align='justify'  style='height: 20px;'>".$row['nombre_reporta']."
                             <input name='oiddetalle[".$pos."]' type='hidden' id='oiddetalle[".$pos."]' value='".$row['iddetallesolicitud']."'>
                             <input name='oidexamen[".$pos."]' type='hidden' id='oidexamen[".$pos."]' value='".$row['id_conf_examen_estab']."'>
                             <input name='oidexametodologia[".$pos."]' type='hidden' id='oidexametodologia[".$pos."]' value='".$row['idexametodologia']."'>
                             </td>";
           if ($row['iddetallesolicitud']== $vector_codigos[$pos]){
                 $Imprimir.="<td align='center'>".$vector_resultados[$pos]."</td>";
           if ($vector_resultados[$pos]>=$row['rangoinicio'] AND $vector_resultados[$pos]<=$row['rangofin']){
                  $tab=1;
                  $Imprimir.="<input name='txttab[".$pos."]' type='hidden' id='txttab[".$pos."]' value='".$tab."'>";
           }
           else{
                  $tab=3;
                  $Imprimir.="<input name='txttab[".$pos."]' type='hidden' id='txttab[".$pos."]' value='".$tab."'>";
           }
                $Imprimir.="<td align='center'>".$row['unidades']."</td>";
                 $Imprimir.="<td align='center'>".$row['rangos']."</td>";
//                    if ((isset($row['rangoinicio'])) && (isset($row['rangofin'])))
//                $Imprimir.="<td width='15%' align='center'>".$row['rangoinicio']."-".$row['rangofin']."</td>";
//                                              else
//                $Imprimir.="<td align='center'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>";
                                       // <td align='center'>".htmlentities($vector_lecturas[$pos])."</td>
                                       // <td align='center'>".htmlentities($vector_inter[$pos])."</td>
                $Imprimir.="<td align='left'>".$vector_obser[$pos]."</td>
                                    </tr>";
                                $pos=$pos + 1;
                            }

             }

            $Imprimir.="<tr>
                                       <td colspan='5' align='center' >&nbsp;&nbsp;&nbsp;</td>
                             </tr>
                                    </tr>
                                    <tr>
                                      <td colspan='5' align='center' >
                                      <input  type='hidden' id='oculto' value='".$pos."'>
                                      <button type='submit' class='btn btn-primary' id='btnGuardar' value='Guardar Resultados' Onclick='GuardarResultadosPlantillaA();' /><span class='glyphicon glyphicon-remove-sign'></span>Guardar Resultados</button>
                                      <button style='display:none' type='button' class='btn btn-primary'  name='Imprimir'  id='Imprimir' value='Imprimir' class='fg-button ui-state-default ui-corner-all'  Onclick='ImprimirPlantillaA1(".$idsolicitud.",\"".$idarea."\",\"".$responsable."\", \"".$valores_resultados."\",\"".$codigos_resultados."\",\"".$valores_obser."\",\"".$codigos_examenes."\",\"".$establecimiento."\",\"".$sexo."\",\"".$idedad."\",\"".$examen_metodologia."\",\"".$txtnec."\",\"".$fecha_reporte."\",\"".$procedencia."\",\"".$origen."\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>
                                         <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='display:none; height:20px'><button type='button' id='modaladdexam' align='center' class='btn btn-primary' title='Agregar Examen' ><span class='glyphicon glyphicon-plus'></span>&nbsp;Agregar Examen</button></a>
                                                    <button type='submit' class='btn btn-primary' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-sign'></span>Cerrar</button><br/>
                                            <br></td>
                                </tr>
                             </table>
                        </td>
                    </tr>
                </table><br>";
		echo $Imprimir;
		//<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaA1(".$idsolicitud.",\"".$idarea."\",\"".$responsable."\", \"".htmlentities($valores_resultados)."\",\"".$codigos_resultados."\",\"".htmlentities($valores_lecturas)."\",\"".htmlentities($valores_inter)."\",\"".htmlentities($valores_obser)."\",\"".$codigos_examenes."\",\"".htmlentities($establecimiento)."\",\"".$sexo."\",\"".$idedad."\") ;'>
   break;

   case 3://GUARDANDO DATOS DE RESULTADOS Y MOSTANDO LISTA ACTUALIZADA PLANTILLA "A"
		//Guardando Resultados
		$idsolicitud=$_POST['idsolicitud'];
		$idarea=$_POST['idarea'];
		$idrecepcion=$_POST['idrecepcion'];
		$valores_resultados=$_POST['valores_resultados'];
		$codigos_resultados=$_POST['codigos_resultados'];
		$idexametodologia=$_POST['examen_metodologia'];
		//$fecha_realizacion=$_POST['fecha_realizacion'];
		$fecha_realizacion=(empty($_POST['fecha_realizacion'])) ? 'NULL' : "'" . pg_escape_string($_POST['fecha_realizacion']) . "'";
		//$fecha_reporte=$_POST['fecha_reporte'];
		$fecha_reporte=(empty($_POST['fecha_reporte'])) ? 'NULL' : "'" . pg_escape_string($_POST['fecha_reporte']) . "'";
		$txtidrecepcion=$_POST['txtidrecepcion'];
		//$valores_lecturas=$_POST['valores_lecturas'];
		//$valores_lecturas=$_POST['valores_lecturas'];
		//$valores_inter=$_POST['valores_inter'];
		$valores_obser=$_POST['valores_obser'];
		$codigos_examenes=$_POST['codigos_examenes'];
		$tabuladores=$_POST['tabuladores'];
		$responsable=$_POST['idempleado'];

   	 	//**************************************************************************
		  $vector_resultados=EXPLODE("/",$valores_resultados);
		  $vector_codigos=EXPLODE("/",$codigos_resultados);
		  //$vector_lecturas=EXPLODE("/",$valores_lecturas);
		  //$vector_inter=EXPLODE("/",$valores_inter);
		  $vector_obser=EXPLODE("/",$valores_obser);
		  //$vector_obser=EXPLODE("/",$valores_obser);
		  $vector_examenes=EXPLODE("/",$codigos_examenes);
		  $vector_idexametodologia=EXPLODE("/",$idexametodologia);
		  $vector_tabuladores=EXPLODE("/",$tabuladores);
		  $obj = new clsSolicitudesProcesadas;
		  $tamano_vector=count($vector_resultados);
		  $pos=0;
		  $ban=0;
		  $ban1=1;

	 if (($tamano_vector-1)>0){
		for ($i=0; $i < $tamano_vector-1 ; $i++) //INSERTANDO ELEMENTOS
		{
			/*$consulta=$obj->valores_referencia($vector_examenes[$pos]);
			$row = mysql_fetch_array($consulta);*/

			// if ($obj->InsertarResultadoPlantillaA1($vector_examenes[$pos],$idsolicitud,$vector_codigos[$pos],$vector_resultados[$pos],$vector_lecturas[$pos],$idrecepcion,$vector_inter[$pos],$vector_obser[$pos],$responsable,$usuario,$vector_tabuladores[$pos],$lugar)==true)
                    $observacion= (empty($vector_obser[$pos])) ? 'NULL' : "'" . pg_escape_string($vector_obser[$pos]) . "'";
                    $resultado=(empty($vector_resultados[$pos])) ? 'NULL' : "'" . pg_escape_string($vector_resultados[$pos]) . "'";

                    $idresultado='NULL';
                    $marca='NULL';
                    $lectura='NULL';
                   if($obj->InsertarResultadoPlantillaAM($vector_examenes[$pos], $vector_idexametodologia[$pos], $responsable,$fecha_realizacion, $fecha_reporte,$resultado, $observacion, $vector_tabuladores[$pos], $idsolicitud, $usuario, $vector_codigos[$pos], $txtidrecepcion, $lugar, $idresultado, $marca, $lectura)==false){
                 // if($obj->InsertarResultadoPlantillaAM($vector_examenes[$pos], $vector_idexametodologia[$pos], $responsable,$fecha_realizacion, $fecha_reporte, $vector_obser[$pos], $vector_tabuladores[$pos], $idsolicitud, $usuario, $vector_codigos[$pos], $txtidrecepcion, $lugar)==false){
                        $ban=1;
                   }
                    else{
                         if($obj->InsertarResultadoPlantillaAF($idsolicitud, $vector_codigos[$pos], $txtidrecepcion,$resultado, 'NULL','NULL',$observacion,$lugar, $usuario,$vector_examenes[$pos], $responsable,$fecha_reporte, $idresultado, $marca)==false){
                             $ban=1;
                         }
                         else{
                             if (($objdatos->CambiarEstadoDetalle($vector_codigos[$pos])==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true)){
					$ban1=0;

				}
                         }
                    }

                    /*
                         if ($obj->InsertarResultadoPlantillaA1($vector_examenes[$pos],$idsolicitud,$vector_codigos[$pos],$vector_resultados[$pos],$idrecepcion,$vector_obser[$pos],$responsable,$usuario,$vector_tabuladores[$pos],$lugar,$vector_idexametodologia[$pos])==true)
			 {
				if (($objdatos->CambiarEstadoDetalle($vector_codigos[$pos])==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true)){
					$ban1=0;

				}
			 }
			 else{
				 $ban=1;

                             }*/
					 $pos=$pos+1;
			 }

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
		else{
		//	echo $ban ."-".$ban1;
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
                                            <p><strong>&Aacute;REA DE ".htmlentities($nombre)." </strong></p>
					</td>
				</tr>
				<tr>
					<td colspan='6' align='center'></td>
				</tr>

				<tr>
					<td style='font:bold'><strong>NEC:</strong></td>
					<td style='font:bold'>".$row['IdNumeroExp']."</td>
					<td colspan='2'>&nbsp;</td>
					<td style='font:bold'><strong>Fecha Recepci�n:</strong></td>
					<td style='font:bold'>".$row['Fecha']."</td>
				<tr>
					<td style='font:bold'><strong>Paciente:</strong></td>
					<td style='font:bold'>".htmlentities($row['NombrePaciente'])."</td>
				</tr>
				<tr>
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
							<td align='justify'>".htmlentities($resultado)."</td>
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
                                        Onclick='Cerrar() ;'
                                         <button type='submit' class='btn btn-primary' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' /><span class='glyphicon glyphicon-remove-sign'></span>Cerrar</button>
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
