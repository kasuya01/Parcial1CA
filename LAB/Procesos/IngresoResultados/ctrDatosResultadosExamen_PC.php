<?php session_start();
//variables de SESSION
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultarElementosPlantillaC.php");

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsConsultarElementosPlantillaC;

switch ($opcion)
{
  case 1:  //MOSTRANDO ANTIBIOTICOS ASOCIADOS A LA PLANTILLA
	$idtarjeta=$_POST['idtarjeta'];
	$idexamen=$_POST['idexamen'];
        $fecharealiz=$_POST['fecharealiz'];
        $fecharesultado=$_POST['fecharesultado'];
        $f_tomamuestra=$_POST['f_tomamuestra'];
        $tipomuestra=$_POST['tipomuestra'];
        $f_consulta=$_POST['f_consulta'];
     //  echo $fecharealiz." * ".$fecharesultado;
	$consulta=$objdatos->LeerAntibioticos($idtarjeta);
	$pos=0;
	     $imprimir="<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td width='40%' class='StormyWeatherFieldCaptionTD'> CULTIVO CON CUENTA DE COLONIAS</td>
				<td width='60%' colspan='2' class='StormyWeatherDataTD'>
					<input name='txtcantidad' type='text' id='txtcantidad' size='50' >
				</td>
			</tr>
			<tr class='CobaltButton'>
				<td width='60%' >ANTIBIOTICO</td>
				<td width='20%' >LECTURA</td>
                                <td width='20%' >INTERPRETACIÓN</td>
			</tr>";
	while($row = pg_fetch_array($consulta))//ELEMENTOS)
	{
	    $imprimir.="<tr>
	    	            <td width='60%' class='StormyWeatherFieldCaptionTD'>".$row['antibiotico']."</td>
                            <td width='20%' class='StormyWeatherDataTD'>
					<input name='txtresultado[".$pos."]' type='text' id='txtresultado[".$pos."]' >
					<input name='oidantibiotico[".$pos."]' type='hidden' id='oidantibiotico[".$pos."]' value='".$row['idantibiotico']."'></td>
                            <td width='20%' class='StormyWeatherDataTD'> 
                                <select id='cmbresultado[".$pos."]' name='cmbresultado[".$pos."]' size='1' class='form-control height'>
                                    <option value='0' >--Seleccione Resultado--</option>";
                                    $con_result = $objdatos->consultar_resultados($row['idantibiotico']);
                                    while ($row_result = pg_fetch_array($con_result)) {
                                        $imprimir.="<option value='" . $row_result['id'] . "'>" . htmlentities($row_result['posible_resultado']) . "</option>";
                                    }
                                    
		    $imprimir.="</select>
                            </td>
	        	</tr>";
		$pos=$pos+1;
	}
	pg_free_result($consulta);
        $imprimir.= "<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                     <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."'/>
                     <input type='hidden' name='txttipomuestra' id='txttipomuestra' disabled='disabled' value='".$tipomuestra."' />
                    <input type='hidden' name='f_consulta' id='f_consulta' disabled='disabled' value='".$f_consulta."' />     
                     <input type='hidden' name='txtf_tomamuestra' id='txtf_tomamuestra' disabled='disabled' value='".$f_tomamuestra."' /> ";
	$imprimir .="<input  type='hidden' id='oculto' value='".$pos."'>";

	    $imprimir.="<tr>
                            <td class='StormyWeatherDataTD' colspan='3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr> 
                        <tr>
                            <td class='StormyWeatherFieldCaptionTD'>Observación:</td>
                            <td colspan='2'class='StormyWeatherDataTD'><textarea name='txtobservacion' type='text' id='txtobservacion' size='50' cols='50'  ></textarea></td>
                        </tr>
                         <tr>
                            <td class='StormyWeatherFieldCaptionTD'>Número de Resiembras:</td>
                            <td colspan='2'class='StormyWeatherDataTD'><input name='txtresiembras' type='text' id='txtresiembras' size='10' value='0' /></td>
                        </tr>
                        <tr>
                            <td class='StormyWeatherFieldCaptionTD'>Número de Pruebas Bioquimicas:</td>
                            <td colspan='2'class='StormyWeatherDataTD'><input name='txtbioquimicas' type='text' id='txtbioquimicas' size='10' value='0'/></td>
                        </tr>
                        <tr>
                            <td width='100%' colspan='3' class='StormyWeatherDataTD' align='right'>
                                
                                <button type='button' id='Submit' align='center' class='btn btn-primary' title='Vista Previa de Resultados'  onclick='MostrarVistaPreviaPlantillaC();'>&nbsp;Vista Previa de Resultados</button>
                            </td>
                        </tr>
	           </table>";
//<input type='button' name='Submit' value='Vista Previa de Resultados' onclick='MostrarVistaPreviaPlantillaC()'>
	echo $imprimir;

   break;

   case 2://MOSTRANDO VISTA PREVIA DE LOS RESULTADO POSITIVOS INGRESADOS
   		$idexamen=$_POST['idexamen'];
		$idsolicitud= $_POST['idsolicitud'];
		$idempleado= $_POST['idempleado'];

                $fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];
                $f_tomamuestra=$_POST['f_tomamuestra'];
                $tipomuestra=$_POST['tipomuestra'];
                $f_consulta= $_POST['f_consulta'];               
		$idrecepcion= $_POST['idrecepcion'];
		$iddetalle= $_POST['iddetalle'];
                
		$observacion= $_POST['observacion'];
                $idobservacion= ($_POST['idobservacion']==0) ? 'NULL' : "'" . pg_escape_string($_POST['idobservacion']) . "'";
                //echo $idobservacion;
		$codigos_antibioticos=$_POST['codigos_antibioticos'];
		$valores_antibioticos=$_POST['valores_antibioticos'];
                $valores_interpretacion=$_POST['valores_interpretacion'];
                
              //  print_r($valores_interpretacion);
		
                $idtarjeta=$_POST['idtarjeta'];
		$idbacteria=$_POST['idbacteria'];
		$cantidad=$_POST['cantidad'];
		$resultado="Positivo";
               // echo $resultado;
                $establecimiento=$_POST['estab'];
                $numresiembras=$_POST['resiembras'];
                $numbioquimicas=$_POST['bioquimicas'];
              // echo $numresiembras." - ".$numbioquimicas;
          //echo " Solicitud=".$idsolicitud." empleado=".$idempleado." Examen=".$idexamen." detalle=".$iddetalle." detalle=".$establecimiento;
		$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
		$row_estab = pg_fetch_array($Consulta_Estab);
               // echo $row_estab[0];
		$vector_valores=EXPLODE("/",$valores_antibioticos);
                $vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
                $vector_interpretacion=EXPLODE("/",$valores_interpretacion);
            	$consulta=$objdatos->LeerAntibioticos($idtarjeta);
		$consulta_datos=$objdatos->LeerDatos($idexamen);
		$datos_generales=$objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
		$datos_empleado=$objdatos->DatosEmpleado($idempleado,$lugar);
		$datos_observacion=$objdatos->LeerObservacion($idobservacion);
		$bateria=$objdatos->NombreBacteria($idbacteria);
             
               // print_r($vector_valores);
		
                
                $row_generales= pg_fetch_array($datos_generales);
		$row_area = pg_fetch_array($consulta_datos);
		$row_empleado = pg_fetch_array($datos_empleado);
		$row_observacion = pg_fetch_array($datos_observacion);
		$row_nombrebacteria= pg_fetch_array($bateria);
                $estabext=$row_generales['estabext'];
	     $imprimir="<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
                            <tr>
                                <td colspan='1' align='left' width='15%'><img id='Image1' style='width: auto; height: 55px;'  src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                                <td align='center' colspan='4' width='70%' class='Estilo5'>
                                    <p width='25'><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                                    <p width='50'><strong>".$row_generales['estabext']    ."</strong></p>
                                    <p width='25'><strong>&Aacute;rea de ".htmlentities($row_area['nombrearea'])." </strong></p></td>
                                <td colspan='1' align='right' width='15%'><img id='Image3' style='width: auto; height: 55px;' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                            </tr>
                            <tr>
                                <td colspan='6'>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan='1'><strong>Establecimiento solicitante:</strong></td>
                                <td colspan='3' align='left'>".$row_generales['estabext']."</td>
                                <td colspan='1' ><strong>Fecha Recepción:</strong></td>
                                <td colspan='1' align='left'>".$row_generales['fecharecep']."</td>

                            </tr>
                            <tr>
                                <td colspan='1'><strong>Expediente:</strong></td>
                                <td colspan='3' align='left'>".$row_generales['numero']."</td>
                                <td colspan='1'><strong>Fecha Toma Muestra:</strong></td>
                                <td colspan='1' align='left'>".$f_tomamuestra."</td>       
                           </tr>
                           <tr>
                                <td colspan='1'><strong>Paciente:</strong></td>
                                <td colspan='3' align='left'>".htmlentities($row_generales['paciente'])."</td>
                                <td colspan='1'><strong>Fecha consulta:</strong></td>    
                                <td colspan='1'>".$f_consulta ."</td>    
                           </tr>
                           <tr>
                                <td colspan='1'><strong>Edad:</strong></td>
                                <td colspan='3' align='left'>".htmlentities($row_generales['edad'])."</td>
                                <td colspan='1'><strong>Sexo:</strong></td>
                                <td colspan='1' align='left'>".$row_generales['sexo']."</td>
                           </tr>
                           <tr>
                                <td colspan='1' ><strong>Procedencia:</strong></td>
                                <td colspan='3'align='left'>".htmlentities($row_generales['procedencia'])."</td>
                                <td colspan='1' ><strong>Servicio:</strong></td>
                                <td colspan='1' align='left'>".htmlentities($row_generales['subservicio'])."</td>
                           </tr>
                           <tr>
                                <td colspan='1'><strong>Examen Realizado:</strong></td>
                                <td colspan='5' align='left'>".htmlentities($row_area['nombre_examen'])."</td>
                           </tr>
                           <tr>
                                <td colspan='1'><strong>Validado Por:</strong></td>
                                <td colspan='5' align='left'>".htmlentities($row_empleado['empleado'])."</td>
                           </tr>
                           <tr>
                                <td colspan='6'>&nbsp;</td>
                           </tr>
                           <tr>  <td colspan='6'><hr></td></tr>
                           <tr>
                                  <td colspan='1'><strong>Resultado:</strong></td>
                                  <td colspan='5' align='left'>Positivo</td>
                           </tr>
                           
                           <tr>
                                  <td colspan='1'>Organismo:</td>
                                  <td colspan='5' align='left'>".htmlentities($row_nombrebacteria['bacteria'])."</td>
                           </tr>
                           <tr>
                                  <td colspan='1' ><strong>Cultivo con cuenta de Colonias:</strong></td>
                                  <td colspan='5' align='left' >".htmlentities($cantidad)."</td>
                           </tr>";

               $imprimir.="<tr><td colspan='6'><hr></td></tr>
                            <tr>
                                <td colspan='6'>
                                    <table width='60%' border='0' align='left' cellspacing='0'>
                                        <tr>
                                           <td width='40%' >ANTIBIOTICO</td>
                                           <td width='20%' >LECTURA</td>
                                           <td width='40%' >INTERPRETACIÓN</td>
                                        </tr>";
                                   pg_free_result($consulta_datos);
                                   pg_free_result($datos_generales);
                                   $pos=0;

                           while($row = pg_fetch_array($consulta))//ELEMENTOS)
                           {
                            $imprimir.="<tr>
                                           <td width='40%'>&emsp;".$row['antibiotico']."</td>
                                           <td width='20%'>".$vector_valores[$pos].
                                           "<input name='oidantibiotico[".$pos."]' type='hidden' id='oidantibiotico[".$pos."]' value='".$row['idantibiotico']."'></td>";
                              // echo $vector_interpretacion[$pos];
                                                $consulta_nombre=$objdatos->nombre_resultado($vector_interpretacion[$pos]);
                                                $row_nombre = pg_fetch_array($consulta_nombre);

                                $imprimir.="<td width='40%'>".htmlentities( $row_nombre[0])."</td>
                                        </tr>";
                                           $pos=$pos+1;
                           }
                                   pg_free_result($consulta);

                                    $imprimir.= "<input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                                                 <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."'/>
                                            <input type='hidden' name='txtresiembras' id='txtresiembras' disabled='disabled' value='".$numresiembras."' />
                                            <input type='hidden' name='txtbioquimicas' id='txtbioquimicas' disabled='disabled' value='".$numbioquimicas."' />";
                                                  
                            $imprimir.="<tr>
                                            <td colspan='6'>&nbsp;</td>
                                        </tr>
                                        </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan='1'>Observación:</td>
                                <td colspan='5' align='left'>".$observacion."</td>
                            </tr>
                            <tr> 
                                <td colspan='6'>
                                    <table width='25%' border='0' align='left' cellspacing='0'>
                                    <br>
                                    <tr>
                                        <td colspan='2'>Número de Resiembras:</td>
                                        <td colspan='1' align='left'>".$numresiembras."</td></tr>
                                    <tr>
                                        <td colspan='2'>Número de Pruebas Bioquimicas:</td>
                                        <td colspan='1' align='left'>".$numbioquimicas."</td>
                                    </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                            
                            <tr>
                                <td colspan='6'>
                                     <button type='button' id='btnGuardar' align='center' class='btn btn-primary' title='Guardar Resultados'  onclick='GuardarResultadosPlantillaC();'><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar Resultados</button>
                                     <button type='button' id='Ingresar' align='center' class='btn btn-primary' title='Ingresar otro Resultado'  onclick='IngresarOtro();'>&nbsp;Ingresar otro Resultado</button>     
                                     <button style='display:none' type='button' class='btn btn-primary'  name='Imprimir'  id='Imprimir' value='Imprimir' class='fg-button ui-state-default ui-corner-all'  Onclick='ImprimirPlantillaC(".$idsolicitud.",\"".$idexamen."\",\"".$resultado."\",\"".$row_empleado['empleado']."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_antibioticos)."\",\"".$codigos_antibioticos."\",".$idbacteria.",\"".$cantidad."\",".$idtarjeta.",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".$idobservacion."\",\"".$valores_interpretacion."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\",\"".$f_consulta."\",\"".$f_consulta."\");'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>     
                                      <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='display:none; height:20px'><button type='button' id='modaladdexam' align='center' class='btn btn-primary' title='Agregar Examen' ><span class='glyphicon glyphicon-plus'></span>&nbsp;Agregar Examen</button></a>                                                  
                                      <button type='button' id='btnSalir' align='center' class='btn btn-primary' title='Cerrar'  onclick='Cerrar();'><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button><br/><br><br/>     
                                </td>
                            </tr>
                        </table>";
			   //<td><input type='button' name='Ingresar' id='Ingresar' value='Ingresar otro Resultado' onclick='IngresarOtro()'\><input type='button'  name='Submit' value='Cerrar' Onclick='salir();'></td>
                           // <input type='button' name='btnGuardar'  id='btnGuardar' value='Guardar Resultados' onclick='GuardarResultadosPlantillaC()'>
                           // <input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaC(".$idsolicitud.",\"".$idexamen."\",\"".$resultado."\",\"".$row_empleado['empleado']."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_antibioticos)."\",\"".$codigos_antibioticos."\",".$idbacteria.",\"".$cantidad."\",".$idtarjeta.",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".$idobservacion."\",\"".$valores_interpretacion."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\");' />
                           //<input type='button' name='Ingresar' id='Ingresar' value='Ingresar otro Resultado' onclick='IngresarOtro()'\>   
                           //<input type='button' id='btnSalir' value='cerrar' onclick='Cerrar()'
                           //<button type='button' id='Imprimir' align='center' class='btn btn-primary' title='Imprimir Resultados' Onclick='ImprimirPlantillaC(".$idsolicitud.",\"".$idexamen."\",\"".$resultado."\",\"".$row_empleado['empleado']."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($observacion)."\",\"".htmlentities($valores_antibioticos)."\",\"".$codigos_antibioticos."\",".$idbacteria.",\"".$cantidad."\",".$idtarjeta.",\"".htmlentities($row_area['nombrearea'])."\",\"".htmlentities($establecimiento)."\",\"".$idobservacion."\",\"".$valores_interpretacion."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\");'><span class='glyphicon glyphicon-print'></span>&nbsp;Imprimir Resultado</button>
	echo $imprimir;
	break;

	case 3://GUARDANDO DATOS DE LOS RESULTADOS EN LAS TABLAS
		$idexamen=$_POST['idexamen'];
		$idsolicitud= $_POST['idsolicitud'];
		$idempleado= $_POST['idempleado'];
		$idrecepcion= $_POST['idrecepcion'];
		$iddetalle= $_POST['iddetalle'];
		$observacion= $_POST['observacion'];
                $idobservacion= $_POST['idobservacion'];
                //echo $idobservacion;
                $observacion= $_POST['observacion'];
                $fecharealiz=$_POST['fecharealiz'];
                $fecharesultado=$_POST['fecharesultado'];
		$codigos_antibioticos=$_POST['codigos_antibioticos'];
		$valores_antibioticos=$_POST['valores_antibioticos'];
                $valores_interpretacion=$_POST['valores_interpretacion'];
                $idarea=$_POST['idarea'];
		$tarjeta=$_POST['idtarjeta'];
		$tiporespuesta=$_POST['tiporespuesta'];
		$resultado=$_POST['resultado'];
                //echo $resultado;
	        $idbacteria=$_POST['idbacteria'];
		$cantidad=$_POST['cantidad'];
		$nombrearea=$_POST['nombrearea'];
	        $vector_valores=EXPLODE("/",$valores_antibioticos);
		$vector_antibioticos=EXPLODE("/",$codigos_antibioticos);
                $vector_interpretacion=EXPLODE("/",$valores_interpretacion);
                
                $numresiembras=$_POST['numresiembras'];
                $numbioquimicas=$_POST['numbioquimicas'];
               // print_r($vector_interpretacion);
                
		$tamano_vector=count($vector_valores);
		$tamano_vectoantibiotico=count($vector_antibioticos);
                $tamano_vectointerpretacion=count($vector_interpretacion);
              // echo "tamaño vactor=".$tamano_vectointerpretacion;
                
              //  echo "tamano_vectoantibiotico=".$tamano_vectointerpretacion;
                //echo "Examen=".$idexamen." - soli=".$idsolicitud." - empleado=".$idempleado." - idrecepcion=".$idrecepcion." - iddetalle=".$iddetalle." - observacion=".$observacion." - resultado=".$resultado;
                // echo $fecharealiz." - ".$fecharesultado;

                $posele=0;
                $ban=0;
                //echo $v_id_elementos[1];
                if ($resultado=="P")
                {
                    $codigoResultado=4;// código resultado positivo
                    $CodAntibiograma=6; // resultado otros
                      $con = new ConexionBD;
                if($con->conectar()==true) 
                {
                   $query = "SELECT lab_examen_metodologia.id AS idmetodologia,ctl_examen_servicio_diagnostico.id AS idcatalogo,
                    mnt_area_examen_establecimiento.id AS idmnt,lab_conf_examen_estab.id AS idconf
                    FROM ctl_examen_servicio_diagnostico 
                    INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico = ctl_examen_servicio_diagnostico.id
                    INNER JOIN lab_conf_examen_estab ON lab_conf_examen_estab.idexamen = mnt_area_examen_establecimiento.id
                    INNER JOIN lab_examen_metodologia ON lab_examen_metodologia.id_conf_exa_estab = lab_conf_examen_estab.id
                    WHERE idestandar='M24' ";
                    $result = pg_query($query);
                    $row_exam_metod = pg_fetch_array($result);
                    $id_metod = $row_exam_metod['idmetodologia'];
                    $idcof = $row_exam_metod['idconf'];
                    $idcat = $row_exam_metod['idcatalogo'];
                    $idmnt = $row_exam_metod['idmnt'];
                    
                   $query2 = "SELECT lab_examen_metodologia.id AS idmetodologia,ctl_examen_servicio_diagnostico.id AS idcatalogo,
                    mnt_area_examen_establecimiento.id AS idmnt,lab_conf_examen_estab.id AS idconf
                    FROM ctl_examen_servicio_diagnostico 
                    INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico = ctl_examen_servicio_diagnostico.id
                    INNER JOIN lab_conf_examen_estab ON lab_conf_examen_estab.idexamen = mnt_area_examen_establecimiento.id
                    INNER JOIN lab_examen_metodologia ON lab_examen_metodologia.id_conf_exa_estab = lab_conf_examen_estab.id
                    WHERE idestandar='M73' ";
                    $result2 = pg_query($query2);
                    $row_exam_metodres = pg_fetch_array($result2);
                    $id_metodres = $row_exam_metodres['idmetodologia'];
                    $idcofres = $row_exam_metodres['idconf'];
                    $idcatres = $row_exam_metodres['idcatalogo'];
                    $idmntres = $row_exam_metodres['idmnt'];
                    
                    $query3 = "SELECT lab_examen_metodologia.id AS idmetodologia,ctl_examen_servicio_diagnostico.id AS idcatalogo,
                    mnt_area_examen_establecimiento.id AS idmnt,lab_conf_examen_estab.id AS idconf
                    FROM ctl_examen_servicio_diagnostico 
                    INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico = ctl_examen_servicio_diagnostico.id
                    INNER JOIN lab_conf_examen_estab ON lab_conf_examen_estab.idexamen = mnt_area_examen_establecimiento.id
                    INNER JOIN lab_examen_metodologia ON lab_examen_metodologia.id_conf_exa_estab = lab_conf_examen_estab.id
                    WHERE idestandar='M66' ";
                    $result3 = pg_query($query3);
                    $row_exam_metodbio = pg_fetch_array($result3);
                    $id_metodbio = $row_exam_metodbio['idmetodologia'];
                    $idcofbio = $row_exam_metodbio['idconf'];
                    $idcatbio = $row_exam_metodbio['idcatalogo'];
                    $idmntbio = $row_exam_metodbio['idmnt'];
                   
                }          //  ECHO   $idcofbio;                                                                                     
                  
                 //  echo $idcof." - ".$idmnt ." - ". $idcat;
               // echo "TAR=". $tarjeta;
               $detantib=$objdatos->insertar_detalle_solicitud($idsolicitud,$iddetalle,$usuario,$lugar,$idcat,$idmnt,$idcof);
               $ultimo= $objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$codigoResultado,$lugar,$idobservacion,$fecharealiz,$fecharesultado);
                 // $ultimoantb= $objdatos->_antibiograma($idsolicitud,$detantib,$idcof,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$CodAntibiograma,$lugar,$idobservacion,$fecharealiz,$fecharesultado,$id_metod);
               $ultimoantb= $objdatos->insertar_encabezado_antibiograma($idsolicitud,$detantib,$idcof,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$CodAntibiograma,$lugar,$idobservacion,$fecharealiz,$fecharesultado,$id_metod,$ultimo);                  
                   //  $antibiograma= $objdatos->insertarAntibiograma($iddetalle,$idexamen,$CodAntibiograma,$usuario,$fecharealiz,$fecharesultado,$idempleado);
                      if (($ultimo != "") && ($ultimoantb != ""))
                            /*($antibiograma==TRUE))*/
                      {
                        $idresultado=$ultimoantb;
                              //insertando el detalle
                            $iddetalleresultado=$objdatos->insertar_detalle($idresultado,$idbacteria,$tarjeta,$cantidad,$lugar);
                                                                        
                              //insertando el detalle de resultados de la tarjeta asociada
                              if (($tamano_vector-1)>0)
                              {
                                for ($i=0; $i < $tamano_vectoantibiotico-1 ; $i++) //INSERTANDO ANTIBIOTICOS
                                {  
                                    $consulta_nombre=$objdatos->nombre_resultado($vector_interpretacion[$i]);
                                    $row_nombre = pg_fetch_array($consulta_nombre);
                                    $Vector_nombres[$i]=$row_nombre[0];
                                    if ($vector_valores[$i]<>''){
                                        if ($objdatos->insertar_resultadoantibioticos($iddetalleresultado,$vector_antibioticos[$i],$vector_valores[$i],$vector_interpretacion[$i], $Vector_nombres[$i],$lugar)==false)
                                               
                                                       $ban=1;
                                   
                                        }else{
                                                if ($objdatos->insertar_resultadoantibioticos1($iddetalleresultado,$vector_antibioticos[$i],$vector_interpretacion[$i], $Vector_nombres[$i],$lugar)==false)
                                               
                                                       $ban=1;
                                               
                                        } 
                                         $posele=$posele+1;
                                 }
                                    
                              }
                              
                             // $datos_res=$objdatos-> datos_resiembras($idsolicitud,$iddetalle,$usuario,$numresiembras,$lugar);
                             // $ressiembra=pg_fech_array($datos_res);
                              for ($i=0; $i < $numresiembras; $i++)
                              {
                                  //echo $idcofres;
                                $detres=$objdatos->insertar_detalle_solicitud($idsolicitud,$iddetalle,$usuario,$lugar,$idcatres,$idmntres,$idcofres);
                                if($detres){
                                    $ultimores= $objdatos->insertar_encabezado_antibiograma($idsolicitud,$detres,$idcofres,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$CodAntibiograma,$lugar,$idobservacion,$fecharealiz,$fecharesultado,$id_metodres,$ultimo); 
                                
                                }
                              }
                              
                              for ($i=0; $i < $numbioquimicas; $i++)
                              {
                                  //ECHO $idcofbio;
                                $detbio=$objdatos->insertar_detalle_solicitud($idsolicitud,$iddetalle,$usuario,$lugar,$idcatbio,$idmntbio,$idcofbio);
                                
                                if($detres){
                                    $ultimores= $objdatos->insertar_encabezado_antibiograma($idsolicitud,$detbio,$idcofbio,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$CodAntibiograma,$lugar,$idobservacion,$fecharealiz,$fecharesultado,$id_metodbio,$ultimo); 
                                
                                    if ($ultimo=='false'){
                                        $ban=1;
                                    }
                                }
                              }
                              if($ban==0){
                                      //actualiza el estado del detalle de la solicitud para indicar que el resultado esta completo para el examen

                                      echo "Datos Guardados";
                                      //echo $detantib;

                                    if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoDetalle($detantib)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true))
                                    {
                                        echo " Correctamente";
                                    }

                              }
                              else
                              {

                                    echo "Los resultados no pueden ser guardados consulte al Administrador...";
                              }
                      }
                  
                  
                }
                else{ 
                    echo "Los resultados no pueden ser guardados consulte al Administrador...";
                }
   break;


   case 4://MOSTRANDO COMBO DE OBSERVACIONES SEGUN EL FILTRO
      	$idexamen=$_POST['idexamen'];
	$idarea=$_POST['idarea'];
	$idtarjeta=$_POST['idtarjeta'];
	$tiporespuesta=$_POST['tiporespuesta'];
        $idareaPA=$_POST['idareaPA'];
        $f_consulta=$_POST['f_consulta'];
     //  echo "$idareaPA ".$idareaPA;
        
        //$f_tomamuestra=$_POST['f_tomamuestra'];
        //$tipomuestra=$_POST['tipomuestra'];
        // echo " opcion 4 ".$f_tomamuestra."  ".$tipomuestra;
	$consulta_ob=$objdatos->LeerObservaciones($idareaPA,$tiporespuesta);

        $imprimir="<table class='StormyWeatherFormTABLE' width='100%' >
            	   <tr>
                        <td width='35%' class='StormyWeatherFieldCaptionTD'>Observaci&oacute;n</td>
			<td width='65%' class='StormyWeatherDataTD' >
                            <select id='cmbObservacion' name='cmbObservacion' size='1' style='width:100%' class='form-control height' >
                                <option value='0' >--Seleccione Observaci&oacute;n--</option>";
                                   while($row = pg_fetch_array($consulta_ob)){
                                     $imprimir.="<option value='" . $row['idobservacion']. "'>" . $row['observacion'] . "</option>";
                                   }
                $imprimir.="</select>
                        </td><input type='hidden' name='txtf_consulta' id='txtf_consulta' disabled='disabled' value='".$f_consulta."' />
		   </tr>
                   </table>";
            echo $imprimir;


   break;
   case 5:
   //MOSTRANDO VISTA PREVIA DE LOS RESULTADOS INGRESADOS CASO RESPUESTA NEGATIVOS
   // $objdatos = new clsConsultarElementosPlantillaC;
	$idexamen=$_POST['idexamen'];
	$idtarjeta=$_POST['idtarjeta'];
	$tiporespuesta=$_POST['tiporespuesta'];
	$idarea=$_POST['idarea'];
	$idsolicitud= $_POST['idsolicitud'];
	$idempleado= $_POST['idempleado'];
        $idareaPA=$_POST['idareaPA'];
	//$observacion= (empty($_POST['observacion'])) ? ' ' : "'" . pg_escape_string($_POST['observacion']) . "'";
        
        $idobservacion=(integer) $_POST['idobservacion'];
       // var_dump($idobservacion);
       // echo "id=".$idobservacion; 
	$resultado=$_POST['resultado'];
	$establecimiento=$_POST['estab'];
        $fecharealiz=$_POST['fecharealiz'];
        $fecharesultado=$_POST['fecharesultado'];
        $f_tomamuestra=$_POST['f_tomamuestra'];
        $tipomuestra=$_POST['tipomuestra'];
        $numresiembras=$_POST['resiembras'];
        $observacion=$_POST['observacion'];
        $f_consulta= $_POST['f_consulta'];
      //  echo " opcion 5 ".$f_tomamuestra."  ".$tipomuestra;
	$Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
	$row_estab = pg_fetch_array($Consulta_Estab);

	$datos_generales=$objdatos->MostrarDatosGenerales($idsolicitud,$lugar);
	$datos_empleado=$objdatos->DatosEmpleado($idempleado,$lugar);
	$consulta_datos=$objdatos->LeerDatos($idexamen);
	$row_area= pg_fetch_array($consulta_datos);
	$row_generales= pg_fetch_array($datos_generales);
	$row_empleado = pg_fetch_array($datos_empleado);
	$datos_observacion=$objdatos->LeerObservacion($idobservacion);
	$row_observacion = pg_fetch_array($datos_observacion);
      
         // echo $row_generales['subservicio'];  
	//$observacion="Resultado Negativo";
	$imprimir="<table width='100%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='1' align='left' width='20%'><img id='Image1' style='width: auto; height: 55px;' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                                <td align='center' colspan='4' width='60%' class='Estilo5'>
					<p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO </strong></p>
					<p><strong>".$row_estab['nombre']."</strong></p>
					<p><strong>ÁREA DE ".htmlentities($row_area['nombrearea'])." </strong></p>
				</td>
                                <td colspan='1' align='right' width='20%'><img id='Image3' style='width: auto; height: 55px;' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
			</tr>
                        <tr>
                                <td colspan='6'>&nbsp;</td>
                        </tr>
			<tr>
				<td colspan='1'><strong>Establecimiento Solicitante:</strong></td>
				<td colspan='2'>".$row_generales['estabext']."</td>
				<td colspan='1'><strong>Fecha Recepción:</strong></td>
				<td colspan='2'>".$row_generales['fecharecep']."</td>

			</tr>

			<tr>
				<td colspan='1' style='font:bold'><strong>Expediente:</strong></td>
				<td colspan='2'>".$row_generales['numero']."</td>
                                <td colspan='1'><strong>Fecha Toma Muestra:</strong></td>
				<td colspan='2'>".$f_tomamuestra."</td>    
                        </tr>
                        <tr>
				<td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
				<td colspan='2'>".htmlentities($row_generales['paciente'])."</td>
                                <td colspan='1'><strong>Fecha consulta:</strong></td>    
                                <td colspan='2'>".$f_consulta ."</td>    
			</tr>

			<tr>
				<td colspan='1'><strong>Edad:</strong></td>
				<td colspan='2'>".$row_generales['edad']."</td>

				<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
				<td colspan='2'>".$row_generales['sexo']."</td>
			</tr>
			<tr>
				<td colspan='1' ><strong>Procedencia:</strong></td>
				<td colspan='2' >".htmlentities($row_generales['procedencia'])."</td>
				<td colspan='1' ><strong>Servicio:</strong></td>
				<td colspan='2' >".htmlentities($row_generales['subservicio'])."</td>
			</tr>
			<tr>
			        <td colspan='1'><strong>Examen Realizado:</strong></td>
				<td colspan='5'>".htmlentities($row_area['nombre_examen'])."</td>
			</tr>
			<tr>
				<td colspan='1'><strong>Validado Por:</strong></td>
				<td colspan='5'>".htmlentities($row_empleado['empleado'])."</td>
			</tr>
                        <tr>
                                <td colspan='6'>&nbsp;</td>
                        </tr>
                        <tr>
                                <td colspan='6'><hr></td>
                        </tr>
			<tr>
				<td colspan='1'><strong>Resultado</strong></td>";
	pg_free_result($consulta_datos);
	pg_free_result($datos_generales);

	switch($resultado)
	{
	   	case "P";
			$imprimir.=	"<td colspan='5'>Positivo</td>";
	   	break;
	   	case "N";
			$imprimir.=	"<td colspan='5'>Negativo</td>";
	   	break;
	   	case "O";
			$imprimir.=	"<td colspan='5'>--</td>";
	   	break;
	}
            $imprimir.= "   <input type='hidden' name='txtresultrealiza' id='txtresultrealiza' disabled='disabled' value='".$fecharealiz."'>
                            <input type='hidden' name='txtfresultado' id='txtfresultado' disabled='disabled' value='".$fecharesultado."' />"
                    . "<input type='hidden' name='txtresiembras' id='txtresiembras' disabled='disabled' value='".$numresiembras."' />";
	   $imprimir.= "</tr>
			<tr>
				<td colspan='1'><strong>Observaci&oacute;n</strong></td>";
           
            if($idobservacion<>0){ 
                $observacionf=$row_observacion['observacion'];
                   $imprimir.= "<td colspan='5'>".htmlentities($row_observacion['observacion'])."</td>";}
                       
            else{
                $observacionf=$observacion;
                $imprimir.= "<td colspan='5'>".htmlentities($observacion)."</td>";}
                
	   $imprimir.= "</tr>
                        <tr>
				<td colspan='1'><strong>Resiembras</strong></td>
			        <td colspan='5'>".$numresiembras."</td>

			</tr>
			<tr>
			  	<td colspan='6' align='center'>&nbsp;&nbsp;&nbsp;&nbsp;</td>
			</tr>
                        <br>
		<div id='divBotones'>
			<tr>
                            <td colspan='6' align='center'>
                                <button type='button' id='btnGuardar' align='center' class='btn btn-primary' title='Guardar Resultados'  onclick='GuardarResultadosNegativosPlantillaC();'><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar Resultados</button>";
				// $observacionf;
                                         //<button style='display:none' type='button' class='btn btn-primary'  name='Imprimir' id='Imprimir' value='Imprimir' class='fg-button ui-state-default ui-corner-all'  Onclick='ImprimirPlantillaCN(".$idsolicitud.",\"".$idexamen."\",\"".$idarea."\",\"".$resultado."\",\"".htmlentities($row_empleado['empleado'])."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($row_observacion['observacion'])."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>
                          $imprimir.= "    <button style='display:none' type='button' class='btn btn-primary'  name='Imprimir' id='Imprimir' value='Imprimir' class='fg-button ui-state-default ui-corner-all'  Onclick='ImprimirPlantillaCN(".$idsolicitud.",\"".$idexamen."\",\"".$idarea."\",\"".$resultado."\",\"".htmlentities($row_empleado['empleado'])."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($observacionf)."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\",\"".$f_consulta."\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>                                    
                                <a  href='#myModal' id='addexam_modal' role='button' data-toggle='modal' data-modal-enabled='true' style='display:none; height:20px'><button type='button' id='modaladdexam' align='center' class='btn btn-primary' title='Agregar Examen' ><span class='glyphicon glyphicon-plus'></span>&nbsp;Agregar Examen</button></a>                               
                                <button type='button' id='btnSalir' align='center' class='btn btn-primary' title='Cerrar'  onclick='Cerrar();'><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button>
                            </td>
                        </tr>
		</div>
	 </table>";
           //<input type='button' id='btnGuardar'  value='Guardar Resultados' onclick='GuardarResultadosNegativosPlantillaC()'>
           //<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaCN(".$idsolicitud.",\"".$idexamen."\",\"".$idarea."\",\"".$resultado."\",\"".htmlentities($row_empleado['empleado'])."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($row_observacion['observacion'])."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\") ;' />
           //<input type='button' id='btnSalir' value='Cerrar' onclick='Cerrar()'>
           //\"".htmlentities($establecimiento)."\",
           //<button type='button' id='Imprimir' align='center' class='btn btn-primary' title='Imprimir Resultados' Onclick='ImprimirPlantillaCN(".$idsolicitud.",\"".$idexamen."\",\"".$idarea."\",\"".$resultado."\",\"".htmlentities($row_empleado['empleado'])."\",\"".htmlentities($row_generales['procedencia'])."\",\"".htmlentities($row_generales['subservicio'])."\",\"".htmlentities($row_observacion['observacion'])."\",\"".$f_tomamuestra."\",\"".$tipomuestra."\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Imprimir Resultado</button>        
	echo $imprimir;
	break;

        case 6: // Guardar resultado Negativo u otro
	$idexamen=$_POST['idexamen'];
	//$tiporespuesta=$_POST['tiporespuesta'];
	$idsolicitud= $_POST['idsolicitud'];
	$idempleado= $_POST['idempleado'];
	$idrecepcion= $_POST['idrecepcion'];
	 $iddetalle= $_POST['iddetalle'];
	//echo $idsolicitud ." - ". $iddetalle;
        $idobservacion=(integer) $_POST['idobservacion'];
      //  var_dump($idobservacion);
	$resultado=$_POST['resultado'];
        $fecharealiz=$_POST['fecharealiz'];
        $fecharesultado=$_POST['fecharesultado'];
        $numresiembras=$_POST['resiembras'];
        $observacion1=$_POST['observacion'];
          /*$idarea=$_POST['idarea'];
		$tiporespuesta=$_POST['tiporespuesta'];
		$nombrearea=$_POST['nombrearea'];*/
        
        
        
        
        $datos_observacion=$objdatos->LeerObservacion($idobservacion);
	$row_observacion = pg_fetch_array($datos_observacion);
        //$observacion= (empty($row_observacion['observacion'])) ? 'NULL' : "'" . pg_escape_string($row_observacion['observacion']) . "'";
        $observacion=$row_observacion['observacion'];
        if ($idobservacion==0){
            $observacionf= $observacion1; 
            $idobservacionf=NULL;
            
        }
        else{
            $idobservacionf=$idobservacion;
            $observacionf=$observacion;
        }
       // echo $observacionf;
        $CodAntibiograma=6;
          $con = new ConexionBD;
            if($con->conectar()==true) 
            {
                $query2 = "SELECT lab_examen_metodologia.id AS idmetodologia,ctl_examen_servicio_diagnostico.id AS idcatalogo,
                    mnt_area_examen_establecimiento.id AS idmnt,lab_conf_examen_estab.id AS idconf
                    FROM ctl_examen_servicio_diagnostico 
                    INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico = ctl_examen_servicio_diagnostico.id
                    INNER JOIN lab_conf_examen_estab ON lab_conf_examen_estab.idexamen = mnt_area_examen_establecimiento.id
                    INNER JOIN lab_examen_metodologia ON lab_examen_metodologia.id_conf_exa_estab = lab_conf_examen_estab.id
                    WHERE idestandar='M73' ";
                    $result2 = pg_query($query2);
                    $row_exam_metodres = pg_fetch_array($result2);
                    $id_metodres = $row_exam_metodres['idmetodologia'];
                    $idcofres = $row_exam_metodres['idconf'];
                    $idcatres = $row_exam_metodres['idcatalogo'];
                    $idmntres = $row_exam_metodres['idmnt'];
            }
      //  echo $fecharealiz." - ".$fecharesultado;
     //echo "Examen=".$idexamen." - soli=".$idsolicitud." - empleado=".$idempleado." - idrecepcion=".$idrecepcion." - iddetalle=".$iddetalle." - observacion=".$observacion." - resultado=".$resultado;
           // echo $fecharesultado;
            
	if ($resultado=="N")
	{
            $codigoResultado=2;
            $ultimo=$objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacionf,$resultado,$idempleado,$usuario,$codigoResultado,$lugar,$idobservacionf,$fecharealiz,$fecharesultado);
           // echo  $ultimo;
                for ($i=0; $i < $numresiembras; $i++)
                              {
                  // echo 'i :' . $i;
                                 // echo $idcofres;
                                $detres=$objdatos->insertar_detalle_solicitud($idsolicitud,$iddetalle,$usuario,$lugar,$idcatres,$idmntres,$idcofres);
                                                   
                                if($detres!=0){
                                    $ultimores= $objdatos->insertar_encabezado_antibiograma($idsolicitud,$detres,$idcofres,$idrecepcion,$observacionf,$resultado,$idempleado,$usuario,$CodAntibiograma,$lugar,$idobservacionf,$fecharealiz,$fecharesultado,$id_metodres,$ultimo); 
                                
                                }
                              }
                             
                echo "Datos Guardados";
	}
	else{
                $codigoResultado=5;
                $ultimo=$objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$codigoResultado,$lugar,$idobservacion,$fecharealiz,$fecharesultado);
                for ($i=0; $i < $numresiembras; $i++)
                              {
                                  //echo $idcofres;
                                $detres=$objdatos->insertar_detalle_solicitud($idsolicitud,$iddetalle,$usuario,$lugar,$idcatres,$idmntres,$idcofres);
                                                   
                                if($detres!=0){
                                    $ultimores= $objdatos->insertar_encabezado_antibiograma($idsolicitud,$detres,$idcofres,$idrecepcion,$observacion,$resultado,$idempleado,$usuario,$CodAntibiograma,$lugar,$idobservacion,$fecharealiz,$fecharesultado,$id_metodres,$ultimo); 
                                
                                }
                              }
	   echo "Datos Guardados";
	}

	if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==false))
	{
			echo (" Correctamente");
        }

	break;


 }

?>
