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
		$ban=0;
		$IdEstab=$_POST['IdEstab'];
                $IdServ=$_POST['IdServ'];
                $IdSubServ=$_POST['IdSubServ'];
   		$idarea=$_POST['idarea'];
		$idexamen=$_POST['idexamen'];
		$idexpediente=$_POST['idexpediente'];
		$fechasolicitud=$_POST['fechasolicitud'];
		$PNombre=$_POST['PNombre'];
		$SNomre=$_POST['SNombre'];
		$PApellido=$_POST['PApellido'];
		$SApellido=$_POST['SApellido'];
		$TipoSolic=$_POST['TipoSolic'];
		//echo $IdEstab;
 		$query = "SELECT lab_examenesxestablecimiento.IdPlantilla,sec_detallesolicitudestudios.IdDetalleSolicitud,
			  sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp,IdRecepcionMuestra,
                          lab_examenes.idexamen, nombreexamen,Indicacion,NombreArea,
			  CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente,
                          NombreSubServicio,NombreServicio, sec_solicitudestudios.Impresiones,mnt_establecimiento.Nombre,
			  sec_historial_clinico.IdHistorialClinico,sec_solicitudestudios.FechaSolicitud,
			  IF(sec_solicitudestudios.IdTipoSolicitud='S','URGENTE','NORMAL') AS prioridad,FechaNacimiento,Sexo,IdEstandar
			  FROM sec_detallesolicitudestudios
			  INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
			  INNER JOIN lab_recepcionmuestra  ON sec_detallesolicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio
			  INNER JOIN lab_examenes          ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                          INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
			  INNER JOIN mnt_expediente        ON mnt_expediente.IdNumeroExp=sec_solicitudestudios.IdNumeroExp
			  INNER JOIN mnt_datospaciente     ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
			  INNER JOIN lab_areas             ON lab_areas.IdArea=lab_examenes.IdArea
			  INNER JOIN sec_historial_clinico ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			  INNER JOIN mnt_subservicio	   ON mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
		 	  INNER JOIN mnt_servicio  	   ON mnt_servicio.IdServicio=mnt_subservicio.IdServicio
			  INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
			  INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
			  WHERE estadodetalle='PM' AND lab_recepcionmuestra.FechaRecepcion=CURRENT_DATE
                          AND sec_solicitudestudios.IdEstablecimiento=$lugar AND ";
		$ban=0;
//AND sec_detallesolicitudestudios.IdEstablecimiento=$IdEstab

		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['IdEstab']))
		{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_POST['IdEstab']."' AND";}

		if (!empty($_POST['IdServ']))
		{ $query .= " mnt_subservicio.IdServicio ='".$_POST['IdServ']."' AND";}

		if (!empty($_POST['IdSubServ']))
		{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}

		if (!empty($_POST['idarea']))
		{ $query .= " lab_areas.IdArea='".$_POST['idarea']."' AND";}

		if (!empty($_POST['idexpediente']))
		{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}

		if (!empty($_POST['idexamen']))
		{ $query .= " lab_examenes.idexamen='".$_POST['idexamen']."' AND";}

		if (!empty($_POST['fechasolicitud']))
		{ $query .= " sec_solicitudestudios.FechaSolicitud='".$_POST['fechasolicitud']."' AND";}

		if (!empty($_POST['PNombre']))
		{ $query .= " mnt_datospaciente.PrimerNombre='".$_POST['PNombre']."' AND";}

		if (!empty($_POST['SNombre']))
		{ $query .= " mnt_datospaciente.SegundoNombre='".$_POST['SNombre']."' AND";}

		if (!empty($_POST['PApellido']))
		{ $query .= " mnt_datospaciente.PrimerApellido='".$_POST['PApellido']."' AND";}

		if (!empty($_POST['SApellido']))
		{ $query .= " mnt_datospaciente.SegundoApellido='".$_POST['SApellido']."' AND";}

		if (!empty($_POST['TipoSolic']))
		{ $query .= " sec_solicitudestudios.IdTipoSolicitud='".$_POST['TipoSolic']."' AND";}

		if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fechasolicitud']))
		AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ']))
		AND (empty($_POST['PNombre'])) AND (empty($_POST['SNombre'])) AND (empty($_POST['PApellido']))
		AND (empty($_POST['SApellido'])) AND (empty($_POST['idexamen'])) AND (empty($_POST['TipoSolic'])))
		{
			$ban=1;
		}

		if ($ban==0){

			$query = substr($query ,0,strlen($query)-3);
			$query_search = $query. " ORDER BY lab_recepcionmuestra.FechaRecepcion DESC";

		}
		//echo $query_search;

  		$consulta=$objdatos->ListadoSolicitudesPorArea($query_search);
		   echo "<table width='81%' border='1' align='center'>
				<tr class='CobaltFieldCaptionTD'>
					<td>Muestra </td>
					<td>NEC </td>
					<td>Paciente </td>
					<td>Id Examen</td>
					<td>Examen</td>
					<td>Servicio</td>
					<td>Procedencia</td>
					<td>Establecimiento</td>
					<td>Fecha Consulta</td>
					<td>Prioridad</td>
				</tr>";
			$pos=0;

			while ($row = mysql_fetch_array($consulta)){
			 echo  "<tr>
					<td width='5%'>".$row['NumeroMuestra']."</td>
					<td width='7%'>
						<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
						$row['IdNumeroExp']."</a>".
				       "</td>".
						"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
						"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
						"<input name='paciente[".$pos."]' id='paciente[".$pos."]' type='hidden' size='60' value='".htmlentities($row["Paciente"])."' />".
						"<input name='examen[".$pos."]' id='examen[".$pos."]' type='hidden' size='60' value='".htmlentities($row["nombreexamen"])."' />".
						"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row["idexamen"]."' />".
						"<input name='iddetalle[".$pos."]' id='iddetalle[".$pos."]' type='hidden' size='60' value='".$row["IdDetalleSolicitud"]."' />".
						"<input name='idrecepcion[".$pos."]' id='idrecepcion[".$pos."]' type='hidden' size='60' value='".$row["IdRecepcionMuestra"]."' />".
						"<input name='plantilla[".$pos."]' id='plantilla[".$pos."]' type='hidden' size='60' value='".$row["IdPlantilla"]."' />".
						"<input name='nombrearea[".$pos."]' id='nombrearea[".$pos."]' type='hidden' size='60' value='".htmlentities($row["NombreArea"])."' />".
						"<input name='procedencia[".$pos."]' id='procedencia[".$pos."]' type='hidden' size='60' value='".htmlentities($row["NombreServicio"])."' />".
						"<input name='origen[".$pos."]' id='origen[".$pos."]' type='hidden' size='60' value='".htmlentities($row["NombreSubServicio"])."' />".
						"<input name='impresion[".$pos."]' id='impresion[".$pos."]' type='hidden' size='60' value='".htmlentities($row["Impresiones"])."' />".
						"<input name='establecimiento[".$pos."]' id='establecimiento[".$pos."]' type='hidden' size='60' value='".htmlentities($row["Nombre"])."'/>".
                                                "<input name='FechaNac[".$pos."]' id='FechaNac[".$pos."]' type='hidden' size='60' value='".htmlentities($row["FechaNacimiento"])."'/>".
                                                "<input name='Sexo[".$pos."]' id='Sexo[".$pos."]' type='hidden' size='60' value='".htmlentities($row["Sexo"])."'/>".
                                                "<input name='IdEstandar[".$pos."]' id='IdEstandar[".$pos."]' type='hidden' size='60' value='".htmlentities($row["IdEstandar"])."'/>".
                                                "<input name='IdHistorial[".$pos."]' id='IdHistorial[".$pos."]' type='hidden' size='60' value='".htmlentities($row["IdHistorialClinico"])."'/>".
                                       "<td width='20%'>".htmlentities($row['Paciente'])."</td>
					<td width='7%'>".$row['idexamen']."</td>
					<td width='20%'>".htmlentities($row['nombreexamen'])."</td>
					<td width='12%'>".htmlentities($row['NombreSubServicio'])."</td>
					<td width='10%'>".htmlentities($row['NombreServicio'])."</td>
					<td width='20%'>".htmlentities($row['Nombre'])."</td>
					<td width='10%'>".($row['FechaSolicitud'])."</td>
					<td width='10%'>".($row['prioridad'])."</td>
				</tr>";
					$pos=$pos + 1;
			}
				mysql_free_result($consulta);
				echo "<input type='hidden' name='oculto' id='text' value='".$pos."' />
			</table>";


   break;

   case 2://LLENANDO COMBO DE EMPLEADOS
	 $idarea=$_POST['idarea'];
         //echo $idarea;
	 $resultado = "<select id='cmbEmpleados' name='cmbEmpleados' size='1'>
                            <option value='0' >--Seleccione--</option>";
                            require_once('clsSolicitudesProcesadas.php');
                            $obje=new clsSolicitudesProcesadas;
                            $consulta= $obje->BuscarEmpleados($idarea,$lugar);
                            while($row = mysql_fetch_array($consulta)){
		  $resultado .="<option value='" . $row[0]. "'>" . htmlentities($row[1]) . "</option>";
					}
						mysql_free_result($consulta);
	$resultado.= "</select>";
		echo $resultado;
   break;
   case 3://GUARDANDO DATOS DE RESULTADOS Y MOSTANDO LISTA ACTUALIZADA PLANTILLA "A"
   //Guardando Resultados
	  	$idexamen=$_POST['idexamen'];
		$idsolicitud=$_POST['idsolicitud'];
		$iddetalle=$_POST['iddetalle'];
		$resultado=$_POST['resultado'];
		$lectura=$_POST['lectura'];
		$observacion=$_POST['observacion'];
		$responsable=$_POST['responsable'];
		$idrecepcion=$_POST['idrecepcion'];
		$interpretacion=$_POST['interpretacion'];
                $codigo=$_POST['codigo'];
       //echo $codigo;
	//$idexpediente=$_POST['idexpediente'];
         $verifica=$objdatos->BuscarResultado($idexamen,$idsolicitud,$iddetalle,$lugar);
        // echo $verifica[0];
         if ($verifica==0){
            $verdadero=$objdatos->InsertarResultadoPlantillaA($idexamen,$idsolicitud,$iddetalle,$resultado,$lectura,$observacion,$responsable,$lectura,$idrecepcion,$interpretacion,$observacion,$usuario,$codigo,$lugar);

		if ($verdadero!=0)
		{ echo "Datos Guardados";
			if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true)){
				echo " Correctamente";
			}
		}else{ echo "No guardo";}
           }
           else{
               echo "Ya Existe un Resultado para esta prueba";
           }
   //Cambia estado del detalle de la solicitud

	break;

	case 4: //MOSTRAR PREVIAMENTE LOS RESULTADOS
                $idsolicitud=$_POST['idsolicitud'];
                $cod=$_POST['codigo'];

                $idexamen=$_POST['idexamen'];
		$resultado=$_POST['resultado'];
		$lectura=$_POST['lectura'];
		$interpretacion=$_POST['interpretacion'];
                $observacion=$_POST['observacion'];
		$responsable=$_POST['responsable'];
		$nombrearea=$_POST['nombrearea'];
		$establecimiento=$_POST['establecimiento'];
		$fechanac=$_POST['fechanac'];
                $sexo=$_POST['sexo'];

                $Consulta_Estab=$objdatos->Nombre_Establecimiento($lugar);
		$row_estab = mysql_fetch_array($Consulta_Estab);

               // echo $idsolicitud." - ".$idexamen." - ".$lugar." - ".$sexo." - ".$idedad;
		$consulta=$objdatos->MostrarResultadoGenerales($idsolicitud,$idexamen,$lugar);
		$row = mysql_fetch_array($consulta);
		$nombre=$row['NombreArea'];
                $proce=$row['Procedencia'];

                $Cuentadias=$objdatos->CalculoDias($fechanac);
                $Cdias= mysql_fetch_array($Cuentadias);
                $dias=$Cdias[0];

                $ConRangos=$objdatos->ObtenerCodigoRango($dias);
                $row_rangos=  mysql_fetch_array($ConRangos);
                $idedad=$row_rangos[0];

                   //  echo $fechanac."***".$sexo."***".$idedad;
		  //cho $idedad;
	 $Imprimir="<table width='100%' align='center' border='0' class='StormyWeatherFormTABLE'>
	            <tr>
			<td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                        <td align='center' colspan='4' width='60%' class='Estilo5'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong>".$row_estab['Nombre']."</strong></p>
                            <p><strong>&Aacute;REA DE ".htmlentities($nombre)." </strong></p>
			</td>
                        <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                    </tr>
                    <tr>
                        <td colspan='6' align='center'></td>
                    </tr>
                    <tr>
                    	<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
                    	<td colspan='2' style='font:bold'>".htmlentities($establecimiento)."</td>
                    	<td colspan='1'style='font:bold'><strong>Fecha:</strong></td>
                    	<td colspan='2' style='font:bold'>".$row['Fecha']."<input name='suEdad' id='suEdad'  type='hidden'  value='".$row['FechaNacimiento']."'/></td>
                    </tr>

                    <tr>
                    	<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			<td colspan='2' style='font:bold'>".$row['IdNumeroExp']."</td></tr>
		    <tr>
                        <td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='5' style='font:bold'>".htmlentities($row['NombrePaciente'])."</td>
                    </tr>
                    <tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2' style='font:bold'>
                            <div id='divsuedad'>
                            </div></td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2' style='font:bold'>".$row['Sexo']."</td>
                    </tr>
                     </tr>
                    	<td colspan='1'style='font:bold'><strong>Procedencia:</strong></td>
                    	<td colspan='2''>".htmlentities($row['Procedencia'])."</td>
                    	<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2'>".htmlentities($row['Origen'])."</td>
                    </tr>
                    <tr>";
			$consulta_empleado=$objdatos->BuscarEmpleadoValidador($responsable,$lugar);
			$fila_empleado = mysql_fetch_array($consulta_empleado);//$fila_empleado['NombreEmpleado'].
			$Imprimir.="
                    	<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
			<td  colspan='5' style='font:bold'>".htmlentities($fila_empleado['NombreEmpleado'])."</td>
                    </tr>
                    <tr>
                        <td colspan='1'>Resultado Tabulador:</td>";
                                $nomcod=$objdatos->ObtenerNombreCodigo($cod);
                                $row_codigo= mysql_fetch_array($nomcod);

            $Imprimir.="<td colspan='5'>".$row_codigo[0]."</td>
	            </tr>
		    <tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		    </tr>

		    </table>";

	$Imprimir.="<table width='100%'  align='center' border='0' class='StormyWeatherFormTABLE'>
                        <tr>
                            <td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
                        </tr>
                        <tr >
                            <td align='center'>Prueba Realizada </td>
                            <td align='center'>Resultado</td>
                            <td align='center'>Unidades</td>
                            <td align='center'>Rangos Normales </td>
                            <td align='center'>Lectura</td>
			    <td align='center'>Interpretaci&oacute;n</td>
			    <td align='center'>Observaci&oacute;n</td>
			</tr>";

	//MOSTRAR DATOS FIJOS Y RESULTADOS DIGITADOS
				$consulta2=$objdatos->MostrarDatosFijosPlantillaA($idexamen,$lugar,$sexo,$idedad);
				$fila = mysql_fetch_array($consulta2);

	    $Imprimir.="<tr>
                            <td align='center' style='font:bold'><strong>".htmlentities($fila['NombreExamen'])."</strong></td>
			    <td align='center'>".htmlentities($resultado)."</td>
			    <td align='center'>".htmlentities($fila['Unidades'])."</td>
			    <td align='center'>".$fila['RangoInicio']." - ".$fila['RangoFin']."</td>
			    <td align='center'>".htmlentities($lectura)."</td>
			    <td align='center'>".htmlentities($interpretacion)."</td>
			    <td align='center'>".htmlentities($observacion)."</td>
			</tr>";
	    $Imprimir.="<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
			</tr>
                        </table>";
	 $Imprimir.="<table align='center' border='0'>
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
				<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaA(".$idsolicitud.",\"".$idexamen."\",\"".htmlentities($resultado)."\", \"".htmlentities($lectura)."\",\"".htmlentities($interpretacion)."\",\"".htmlentities($observacion)."\",\"".$responsable."\",\"".$sexo."\",\"".$idedad."\") ;'>
				<input type='submit' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' />
                            </td>
			</tr>
			</table>";
			echo $Imprimir;

		mysql_free_result($consulta2);
	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';

		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos-> ExamenesPorArea($idarea,$lugar);

		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0"> Seleccione Examen </option>';

		while ($rows =mysql_fetch_array($dtExam)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}

		$rslts .= '</select>';
		echo $rslts;


   	break;
	case 6:// Llenar Combo Establecimiento
		$rslts='';
		$Idtipoesta=$_POST['idtipoesta'];
              // echo $Idtipoesta;
            	$dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
              	$rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:375px">';
		$rslts .='<option value="0"> Seleccione Establecimiento </option>';
                while ($rows =mysql_fetch_array( $dtIdEstab)){
                   $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
                }

		$rslts .= '</select>';
		echo $rslts;
   	break;
	case 7:// Llenar combo Subservicio
   	     $rslts='';
             $IdServ=$_POST['IdServicio'];
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =mysql_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}

	      $rslts .='</select>';
	      echo $rslts;
    break;
	case 8:// LLenado de combo de resultados para tabulaci�n tabulaci�n
	  $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1'>
				<option value='0' >--Seleccione--</option>";
				   require_once('clsSolicitudesProcesadas.php');
					$obje=new clsSolicitudesProcesadas;
					$consulta= $obje->LlenarResultados();
					while($row = mysql_fetch_array($consulta)){
		  $resultado .="<option value='" . $row[0]. "'>" . htmlentities($row[1]) . "</option>";
						}
						mysql_free_result($consulta);
	  $resultado.= "</select>";
		echo $resultado;

	break;


}

?>
