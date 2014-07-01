<?php session_start();
include ("clsSolicitudesPorArea.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
//variables POST
$opcion=$_POST['opcion'];
if (isset($_POST['IdSubServ'])){$IdSubServ= $_POST['IdSubServ'];}else{$procedencia="";}
//creando los objetos de las clases
$objdatos = new clsSolicitudesPorArea;
switch ($opcion) 
{
  case 1: 
	$IdEstab=$_POST['IdEstab'];
       if (isset($_POST['IdSubServ'])){$IdSubServ= $_POST['IdSubServ'];}else{$IdSubServ="";}
       // $IdSubServ=$POST['IdSubServ'];
        $idarea=$_POST['idarea'];
		$idexpediente=$_POST['idexpediente'];
		$idexamen=$_POST['idexamen'];
		$fechasolicitud=$_POST['fechasolicitud'];
		$PNombre=$_POST['PNombre'];
		$SNomre=$_POST['SNombre'];
		$PApellido=$_POST['PApellido'];
		$SApellido=$_POST['SApellido'];
		$Codigo=$_POST['Codigo'];
		$Orden=$_POST['Orden'];
	//echo $Orden."*".$idarea; 
	//$fechasolicitud=$_POST['fechasolicitud'];
    //echo $IdEstab."-".$IdServ."-".$IdSubServ."-".;
	if ($Codigo==""){
		//echo $Codigo."*".$idarea; 
			$ban=0;  
			$query="SELECT sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp,lab_examenes.idexamen,nombreexamen,
		 		Indicacion,sec_detallesolicitudestudios.IdTipoMuestra,mnt_establecimiento.Nombre, mnt_establecimiento.Nombre,mnt_servicio.NombreServicio, 
				mnt_subservicio.NombreSubServicio,CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente,
				DATE_FORMAT(sec_solicitudestudios.FechaSolicitud,'%d/%m/%Y')as FechaSolicitud ,DATE_FORMAT(FechaRecepcion,'%d-%m-%Y')  as FechaRecepcion     
				FROM sec_detallesolicitudestudios  
				INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
				INNER JOIN lab_recepcionmuestra ON sec_detallesolicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio 
				INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
				INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
				INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
				INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
				INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
				INNER JOIN mnt_servicio ON  mnt_subservicio.IdServicio=mnt_servicio.IdServicio
				INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp=mnt_expediente.IdNumeroExp
		        INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
				WHERE estadodetalle='D' AND lab_recepcionmuestra.FechaRecepcion=CURRENT_DATE AND";

			// $estadodetalle='D';  //estado en que la muestra ha sido tomada
				if (!empty($_POST['IdEstab']))
				{ $query .= " sec_historial_clinico.IdEstablecimiento='".$_POST['IdEstab']."' AND";}
				
				if (!empty($_POST['IdServ']))
				{ $query .= " mnt_subservicio.IdServicio='".$_POST['IdServ']."' AND";}
				
				if (!empty($_POST['IdSubServ']))
				{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}

				if (!empty($_POST['idarea']))
				{ $query .= " lab_areas.IdArea='".$_POST['idarea']."' AND";}	
				
				if (!empty($_POST['idexpediente']))
				{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
				
				if (!empty($_POST['idexamen']))
				{ $query .= " lab_examenes.IdExamen='".$_POST['idexamen']."' AND";}
				
				if (!empty($_POST['Orden']))
				{ $query .= " sec_detallesolicitudestudios.IdSolicitudEstudio='".$_POST['Orden']."' AND";}
				
				if (!empty($fechasolicitud))
				{  //echo $fechasolicitud;
				   $Nfecha=explode("/",$fechasolicitud);
				   //print_r($Nfecha);
		        	   $Nfechasolicitud=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0];
		         	 // echo $Nfechasolicitud;
				  $query .= " sec_solicitudestudios.FechaSolicitud='".$Nfechasolicitud."' AND";}

				if (!empty($_POST['PNombre']))
				{ $query .= " mnt_datospaciente.PrimerNombre='".$_POST['PNombre']."' AND";}
				
				if (!empty($_POST['SNombre']))
				{ $query .= " mnt_datospaciente.SegundoNombre='".$_POST['SNombre']."' AND";}
				
				if (!empty($_POST['PApellido']))
				{ $query .= " mnt_datospaciente.PrimerApellido='".$_POST['PApellido']."' AND";}
				
				if (!empty($_POST['SApellido']))
				{ $query .= " mnt_datospaciente.SegundoApellido='".$_POST['SApellido']."' AND";}
				
			if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fechasolicitud'])) AND (empty($_POST['IdEstab'])) 
			AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['PNombre'])) AND (empty($_POST['SNombre'])) 
			AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido'])) AND (empty($_POST['idexamen'])) AND (empty($_POST['Orden'])))
				{
						$ban=1;
				}
				
				
						
				if ($ban==0){
		                	$query = substr($query ,0,strlen($query)-3);
					$query_search = $query. " ORDER BY NumeroMuestra";
				    // echo $query_search ;
		                                 
				}
			   
				 $consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
				  echo "<table width='90%' border='1' align='center' class='StormyWeatherFormTABLE'>
						<tr class='CobaltFieldCaptionTD'>
							<td>Muestra </td>
							<td>NEC </td>
							<td>Nombre Paciente</td>
		                        		<td>Id Examen</td>
							<td>Examen</td>
							<td>Servicio</td>
							<td>Procedencia</td>
							<td>Establecimiento</td>
							<td>Fecha Consulta</td>
						</tr>";    
					 $pos=0;
			 		//$row = mysql_fetch_array($consulta);
			 
					while ($row = mysql_fetch_array($consulta))
					{ 
			 		  echo "<tr>
							<td width='7%'>".$row['NumeroMuestra']."</td>
							<td width='8%'>
								<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
								$row['IdNumeroExp']."</a>". 
								"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
								"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
					   			"<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
					    			"<input name='idtipo[".$pos."]' id='idtipo[".$pos."]' type='hidden' size='60' value='".$row["IdTipoMuestra"]."' />".
					     			"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['idexamen']."' /></td>
							<td width='25%'>".htmlentities($row['Paciente'])."</td>	 
							<td width='8%'>".$row['idexamen']."</td>
							<td width='20%'>".htmlentities($row['nombreexamen'])."</td>
							<td width='12%'>".htmlentities($row['NombreSubServicio'])."</td>
							<td width='8%'>".htmlentities($row['NombreServicio'])."</td>
							<td width='20%'>".htmlentities($row['Nombre'])."</td>
		                                        <td width='10%'>".($row['FechaSolicitud'])."</td>
						</tr>";
						$pos=$pos + 1;
					}
				
						mysql_free_result($consulta);
						echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
					</table>";
	}else
    { 	
		$Ncod=explode("-",$Codigo);
		//echo $Ncod[0]."-".$Ncod[1]."-".$Ncod[2]; 
		$idsolicitud=$Ncod[0];
		$idarea=$Ncod[1];
		$idestadar=$Ncod[2];
		$ban=0;  
		$query="SELECT sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp,
			lab_examenes.idexamen,nombreexamen,Indicacion,sec_detallesolicitudestudios.IdTipoMuestra,
			mnt_establecimiento.Nombre, mnt_establecimiento.Nombre,mnt_servicio.NombreServicio, 
			mnt_subservicio.NombreSubServicio,
			CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente,
			DATE_FORMAT(sec_solicitudestudios.FechaSolicitud,'%d/%m/%Y')as FechaSolicitud ,
			DATE_FORMAT(FechaRecepcion,'%d-%m-%Y')  as FechaRecepcion,lab_examenes.IdEstandar     
			FROM sec_detallesolicitudestudios  
			INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
			INNER JOIN lab_recepcionmuestra ON sec_detallesolicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio 
			INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
			INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
			INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
			INNER JOIN mnt_servicio ON  mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp=mnt_expediente.IdNumeroExp
			INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
			WHERE estadodetalle='D' AND lab_recepcionmuestra.FechaRecepcion=CURRENT_DATE AND";
			
			if (!empty($idsolicitud))
				{ $query .= " sec_detallesolicitudestudios.IdSolicitudEstudio='".$idsolicitud."' AND";}
			if (!empty($idarea))
				{ $query .= " lab_areas.IdArea='".$idarea."' AND";}	
			if (!empty($idestadar))
				{ $query .= " lab_examenes.IdEstandar='".$idestadar."' AND";}	

			if((empty($idsolicitud)) AND (empty($idarea)) AND (empty($idestadar)))
			{
				$ban=1;
			}				

			if ($ban==0){
			    $query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " ORDER BY NumeroMuestra";
				//echo $query_search ;
			                                 
			}
			 $consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
				  echo "<table width='90%' border='1' align='center' class='StormyWeatherFormTABLE'>
						<tr class='CobaltFieldCaptionTD'>
							<td>Muestra </td>
							<td>NEC </td>
							<td>Nombre Paciente</td>
		                        		<td>Id Examen</td>
							<td>Examen</td>
							<td>Servicio</td>
							<td>Procedencia</td>
							<td>Establecimiento</td>
							<td>Fecha Consulta</td>
						</tr>";    
					 $pos=0;
			 		//$row = mysql_fetch_array($consulta);
			 
					while ($row = mysql_fetch_array($consulta))
					{ 
			 		  echo "<tr>
							<td width='7%'>".$row['NumeroMuestra']."</td>
							<td width='8%'>
								<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
								$row['IdNumeroExp']."</a>". 
								"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
								"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
					   			"<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
					    			"<input name='idtipo[".$pos."]' id='idtipo[".$pos."]' type='hidden' size='60' value='".$row["IdTipoMuestra"]."' />".
					     			"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['idexamen']."' /></td>
							<td width='25%'>".htmlentities($row['Paciente'])."</td>	 
							<td width='8%'>".$row['idexamen']."</td>
							<td width='20%'>".htmlentities($row['nombreexamen'])."</td>
							<td width='12%'>".htmlentities($row['NombreSubServicio'])."</td>
							<td width='8%'>".htmlentities($row['NombreServicio'])."</td>
							<td width='20%'>".htmlentities($row['Nombre'])."</td>
		                                        <td width='10%'>".($row['FechaSolicitud'])."</td>
						</tr>";
						$pos=$pos + 1;
					}
				
						mysql_free_result($consulta);
						echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
					</table>";
	}	
   
   break;
   
   case 2:
		$idsolicitud=$_POST['idsolicitud'];
		$idexpediente=$_POST['idexpediente'];	
		$idtipo=$_POST['idtipo'];
		//echo $idtipo;		
		include_once("clsSolicitudesPorArea.php");
		//recuperando los valores generales de la solicitud
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud);
		$row = mysql_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
		$medico=$row['NombreMedico'];
		$idmedico=$row['IdMedico'];
		$paciente=$row['NombrePaciente'];
		$edad=$row['Edad'];
		$sexo=$row['Sexo'];
		$precedencia=$row['Precedencia'];
		$origen=$row['Origen'];
		//$DatosClinicos=$row['DatosClinicos'];
		$fechasolicitud=$row['FechaSolicitud'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idarea,$idsolicitud,$idtipo);
		$imprimir="<form name='frmDatos'>
           <table width='60%' border='0' align='center' class='StormyWeatherFormTABLE'>
		   <tr>
			<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		   </tr>
		   <tr>
		    <td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
		    <td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;$precedencia <input name='txtprecedencia' id='txtprecedencia' type='hidden' value='".$precedencia."'/></td>
		    <td class='StormyWeatherFieldCaptionTD'>Origen</td>
		    <td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($origen)."
				<input name='txtorigen' id='txtorigen'  type='hidden' value='".$origen."'/>
				<input name='idsolicitud' id='idsolicitud'  type='hidden' value='".$idsolicitud."'/>
				<input name='idexpediente' id='idexpediente'  type='hidden' value='".$idexpediente."'/>
				<input name='fechasolicitud' id='fechasolicitud'  type='hidden' value='".$fechasolicitud."'/>
				<input name='idarea' id='idarea'  type='hidden' value='".$idarea."'/>
			</td>
		   </tr>
		   <tr>
		    <td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
		    <td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($medico)."
					<input name='txtmedico' id='txtmedico'  type='hidden' value='".$medico."' />
			</td>
		   </tr>
		   <tr>
		    <td class='StormyWeatherFieldCaptionTD'>Paciente</td>
		    <td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($paciente)." <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."'/>
			</td>
		   </tr>
		   <tr>
		    <td class='StormyWeatherFieldCaptionTD'>Edad</td>
		    <td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;$edad<input type='hidden' name='txtedad' value='". $edad."'/></td>
		    <td class='StormyWeatherFieldCaptionTD'>Sexo</td>
		    <td class='StormyWeatherDataTD'>
				&nbsp;&nbsp;&nbsp;$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' />
			</td>
		   </tr>
		   </table>
		

		   <table width='90%' border='0' align='center'>
		   <tr>
			<td colspan='4' align='center' >ESTUDIO SOLICITADO</td>
		   </tr>
		   <tr>
	 	    <td>
		      <table border = 1 align='center' class='estilotabla'>
			   <tr class='CobaltFieldCaptionTD'>
			    <td> IdExamen</td>
			    <td> Examen </td>
			    <td> Tipo Muestra </td>
			    <td> Indicaci&oacute;n M&eacute;dica </td>
			   </tr>";
		$pos=0;
		while($fila = mysql_fetch_array($consultadetalle)){
		 $imprimir .= " <tr>
					<td>".$fila['IdExamen']."</td>
					<td>".htmlentities($fila['NombreExamen'])."</td>	
                			<td>".htmlentities($fila['TipoMuestra'])."</td>";	
				if (!empty($fila['Indicacion'])){    				
			  $imprimir .= "<td>".htmlentities($fila['Indicacion'])."</td></tr>";
            			}else{
			   $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
			   	</tr>"; 
				}
				$pos=$pos + 1;
		}

		mysql_free_result($consultadetalle);

 		$imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
						
			</form>";
     echo $imprimir;
	

   	break;
    	case 3:
		$idsolicitud=$_POST['idsolicitud'];
		$idtipo=$_POST['idtipo'];
		$idarea=$_POST['idarea'];
		$estado=$_POST['estado'];
		$idexpediente=$_POST['idexpediente'];
		$estadosolicitud="P";
	 
		if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea,$idtipo)==true)   
		  {            
			echo "Muestras Recibidas.";	
			if($objdatos->CambiarEstadoSolicitud($idexpediente,$idsolicitud,$estadosolicitud)==true)
			{
				 //echo "Solicitud Procesada..";
			 }
			else{
					echo "Solicitud No fue cambiada de Estado..";
			}
		}
				
	 break;
	 
	case 4:// Rechazar Muestra
	    $observacion=$_POST['observacion'];
		$idexpediente=$_POST['idexpediente'];  
		$idtipo=$_POST['idtipo'];
		$idarea=$_POST['idarea'];
		$estado=$_POST['estado'];
		$idsolicitud=$_POST['idsolicitud'];
		$estadosolicitud='P';
	//	$objdatos->insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$responsable,$usuario,$tab,$lugar);
		
		if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea,$idtipo)==true)   
		{			 
			//actualizar en la tabla de recepcion
			if($objdatos->MarcarObservacionRechazado($idsolicitud,$idarea,$observacion,$idtipo)==true){
				if($objdatos->CambiarEstadoSolicitud($idexpediente,$idsolicitud,$estadosolicitud)==true){
				
				}
				echo "Muestras Rechazada";
			}	
		}
		else{
				echo "No se pudo actualizar";
		}
		
	break;
 	case 5://LLENANDO COMBO DE Examenes
		
		$rslts='';
		//$IdSubEsp=$_POST['idsubespecialidad'];
		$idarea=$_POST['idarea'];
		//echo $idarea;
		$dtExam=$objdatos-> ExamenesPorArea($idarea,$lugar);	
		
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:375px">';
		$rslts .='<option value="0">Seleccione Examen</option>';
			
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
		$rslts .='<option value="0">Seleccione Establecimiento</option>';
               while ($rows =mysql_fetch_array( $dtIdEstab)){
		  $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       }
				
		$rslts .= '</select>';
		echo $rslts;
  	break;
   	case 7:
   	     $rslts='';
             $IdServ=$_POST['IdServicio'];
	    //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
			$rslts .='<option value="0">Seleccione Subespecialidad</option>';
			while ($rows =mysql_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
  	break;	

   
}

?>