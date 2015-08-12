<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsMuestrasPendientes.php");

//variables POST
$opcion=$_POST['opcion'];
$estadosolicitud="P";
//echo $fecharecep;

//creando los objetos de las clases
$objdatos = new clsMuestrasPendientes;

switch ($opcion) 
{
  case 1: 
	$idexpediente=$_POST['idexpediente'];
	$idarea=$_POST['idarea'];
	$idexamen=$_POST['idexamen'];
	$fecharecep=$_POST['fecharecep'];
	$IdEstab=$_POST['IdEstab'];
    $IdServ=$_POST['IdServ'];
    $IdSubServ=$_POST['IdSubServ'];
	$PNombre=$_POST['PNombre'];
	$SNomre=$_POST['SNombre'];
	$PApellido=$_POST['PApellido'];
	$SApellido=$_POST['SApellido'];
	$Codigo=$_POST['Codigo'];
	$Orden=$_POST['Orden'];;
	//echo $Orden."*".$idarea; 
	
	//$Nfecha=Array[3]
	if ($Codigo==""){
		$ban=0;  
		$query="SELECT sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp, 	
		lab_examenes.idexamen,nombreexamen,Indicacion,DATE_FORMAT(lab_recepcionmuestra.FechaRecepcion,'%d/%m/%Y') AS FechaRecepcion,mnt_establecimiento.Nombre as Establecimiento,
		mnt_subservicio.NombreSubServicio,mnt_servicio.NombreServicio,
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente
		FROM sec_detallesolicitudestudios 
		INNER JOIN sec_solicitudestudios  ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
		INNER JOIN lab_recepcionmuestra ON sec_detallesolicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra .IdSolicitudEstudio 
		INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_areas ON lab_examenes.IdArea= lab_areas.IdArea
		INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
		INNER JOIN mnt_servicio   ON mnt_servicio.IdServicio=mnt_subservicio.IdServicio
		INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
		WHERE estadodetalle='D' AND lab_recepcionmuestra.FechaRecepcion < CURRENT_DATE 
	    AND";

		// $estadodetalle='D';  //estado en que la muestra ha sido tomada
			if (!empty($_POST['IdEstab']))
			{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_POST['IdEstab']."' AND";}	
				
			if (!empty($_POST['IdServ']))
			{ $query .= " mnt_subservicio.IdServicio ='".$_POST['IdServ']."' AND";}
			
			if (!empty($_POST['IdSubServ']))
			{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}

			if (!empty($_POST['idarea']))
			{ $query .= " lab_areas.IdArea='".$_POST['idarea']."' AND";}

			if (!empty($_POST['idexamen']))
			{ $query .= " lab_examenes.idexamen='".$_POST['idexamen']."' AND";}	
			
			if (!empty($_POST['idexpediente']))
			{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
			
			if (!empty($_POST['fecharecep']))
			{ $Nfecha=explode("/",$fecharecep);
			 //print_r($Nfecha);
	                  $Nfecharecep=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0];
	                  $query .= " lab_recepcionmuestra.fecharecepcion='".$Nfecharecep."' AND";}
						
			if (!empty($_POST['PNombre']))
			{ $query .= " mnt_datospaciente.PrimerNombre='".$_POST['PNombre']."' AND";}
			
			if (!empty($_POST['SNombre']))
			{ $query .= " mnt_datospaciente.SegundoNombre='".$_POST['SNombre']."' AND";}
			
			if (!empty($_POST['PApellido']))
			{ $query .= " mnt_datospaciente.PrimerApellido='".$_POST['PApellido']."' AND";}
			
			if (!empty($_POST['SApellido']))
			{ $query .= " mnt_datospaciente.SegundoApellido='".$_POST['SApellido']."' AND";}
			
			if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($Nfecharecep)) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['idexamen'])) AND (empty($_POST['PNombre'])) AND (empty($_POST['SNombre'])) AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido']))) 
			{ 
					$ban=1;
			}
			
			if ($ban==0){
				
				$query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " ORDER BY lab_recepcionmuestra.FechaRecepcion DESC";
			}
		//echo $query_search;
		
		  $consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
		   $NroRegistros= $objdatos->NumeroDeRegistros($query_search);
	 	  echo "<table width='100%' border='0' align='center'>
				<tr>
					<td colspan='7' align='center' ><h3><strong>TOTAL DE EXAMENES PENDIENTES DE PROCESAR:".$NroRegistros."</strong></h3></td>
				</tr>
				<tr>
				</table> "; 
		  echo "<table width='90%' border='1' align='center' class='StormyWeatherFormTABLE'>
				<tr class='CobaltFieldCaptionTD'>
					<td>Muestra </td>
					<td>NEC </td>
					<td>Paciente </td>
					<td>Id Examen</td>
					<td>Examen</td>
					<td>Servicio</td>
					<td>Procedencia</td>
					<td>Establecimiento</td>
					<td>Fecha Recepci&oacute;n</td>
				</tr>";    
		 $pos=0;
		 //$row = mysql_fetch_array($consulta);
		 
		while ($row = mysql_fetch_array($consulta))
		{ 
		   echo"<tr>
					<td width='10%'>".$row['NumeroMuestra']."</td>
					<td width='15%'>
					<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
					$row['IdNumeroExp']."</a>". 
					"</td>". 
					"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
					"<input name='establecimiento[".$pos."]' id='establecimiento[".$pos."]' type='hidden' size='60' value='".$row["Establecimiento"]."' />".
					"<input name='servicio[".$pos."]' id='servicio[".$pos."]' type='hidden' size='60' value='".$row["NombreServicio"]."' />".
					"<input name='subservicio[".$pos."]' id='subservicio[".$pos."]' type='hidden' size='60' value='".$row["NombreSubServicio"]."' />".
					"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
					"<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
					"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['idexamen']."' />".
					" <td width='30%'>".htmlentities($row['Paciente'])."</td>
					  <td width='13%'>".$row['idexamen']."</td>
					 <td width='30%'>".htmlentities($row['nombreexamen'])."</td>
					 <td width='30%'>".htmlentities($row['NombreSubServicio'])."</td>
					 <td width='20%'>".htmlentities($row['NombreServicio'])."</td>
					 <td width='20%'>".htmlentities($row['Establecimiento'])."</td>
					 <td width='17%'>".$row['FechaRecepcion']."</td>
				</tr>";

			$pos=$pos + 1;
			}
			//mysql_free_result($consulta);
		   echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
				</table>";
    }
	else{
		$Ncod=explode("-",$Codigo);
		//echo $Ncod[0]."-".$Ncod[1]."-".$Ncod[2]; 
		$idsolicitud=$Ncod[0];
		$idarea=$Ncod[1];
		$idestadar=$Ncod[2];
		$ban=0; 
		$query="SELECT sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp, 	
		lab_examenes.idexamen,nombreexamen,Indicacion,DATE_FORMAT(lab_recepcionmuestra.FechaRecepcion,'%d/%m/%Y') AS FechaRecepcion,mnt_establecimiento.Nombre as Establecimiento,
		mnt_subservicio.NombreSubServicio,mnt_servicio.NombreServicio,
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente
		FROM sec_detallesolicitudestudios 
		INNER JOIN sec_solicitudestudios  ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
		INNER JOIN lab_recepcionmuestra ON sec_detallesolicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra .IdSolicitudEstudio 
		INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_areas ON lab_examenes.IdArea= lab_areas.IdArea
		INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
		INNER JOIN mnt_servicio   ON mnt_servicio.IdServicio=mnt_subservicio.IdServicio
		INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
		WHERE estadodetalle='D' AND lab_recepcionmuestra.FechaRecepcion < CURRENT_DATE 
	    AND";
		
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
				$query_search = $query. " ORDER BY lab_recepcionmuestra.FechaRecepcion DESC";
			}
			//echo $query_search;
		
			$consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
			$NroRegistros= $objdatos->NumeroDeRegistros($query_search);
	 	  echo "<table width='100%' border='0' align='center'>
					<tr>
						<td colspan='7' align='center' ><h3><strong>TOTAL DE EXAMENES PENDIENTES DE PROCESAR:".$NroRegistros."</strong></h3></td>
					</tr>
				</table> "; 
		  echo "<table width='90%' border='1' align='center' class='StormyWeatherFormTABLE'>
					<tr class='CobaltFieldCaptionTD'>
						<td>Muestra </td>
						<td>NEC </td>
						<td>Paciente </td>
						<td>Id Examen</td>
						<td>Examen</td>
						<td>Servicio</td>
						<td>Procedencia</td>
						<td>Establecimiento</td>
						<td>Fecha Recepci&oacute;n</td>
					</tr>";    
		 $pos=0;
		 //$row = mysql_fetch_array($consulta);
		 
		while ($row = mysql_fetch_array($consulta))
		{ 
				echo"<tr>
						<td width='10%'>".$row['NumeroMuestra']."</td>
						<td width='15%'>
						<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
						$row['IdNumeroExp']."</a>". 
						"</td>". 
						"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
						"<input name='establecimiento[".$pos."]' id='establecimiento[".$pos."]' type='hidden' size='60' value='".$row["Establecimiento"]."' />".
						"<input name='servicio[".$pos."]' id='servicio[".$pos."]' type='hidden' size='60' value='".$row["NombreServicio"]."' />".
						"<input name='subservicio[".$pos."]' id='subservicio[".$pos."]' type='hidden' size='60' value='".$row["NombreSubServicio"]."' />".
						"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
						"<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
						"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['idexamen']."' />".
						" <td width='30%'>".htmlentities($row['Paciente'])."</td>
						  <td width='13%'>".$row['idexamen']."</td>
						 <td width='30%'>".htmlentities($row['nombreexamen'])."</td>
						 <td width='30%'>".htmlentities($row['NombreSubServicio'])."</td>
						 <td width='20%'>".htmlentities($row['NombreServicio'])."</td>
						 <td width='20%'>".htmlentities($row['Establecimiento'])."</td>
						 <td width='17%'>".$row['FechaRecepcion']."</td>
					</tr>";

				$pos=$pos + 1;
		}
			//mysql_free_result($consulta);
			echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
				</table>";		
	}
  	 break;
   
   	case 2:
			$idexpediente=$_POST['idexpediente'];
			$idsolicitud=$_POST['idsolicitud'];
			$idarea=$_POST['idarea'];
			$establecimiento=$_POST['establecimiento'];
			$idexamen=$_POST['idexamen'];
			if($idarea=="URI" or $idarea=="BAT"){   
			
			include_once("clsMuestrasPendientes.php");
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
			$fechasolicitud=$row['FechaSolicitud'];
            $fechaNac=$row['FechaNacimiento'];
           	//recuperando los valores del detalle de la solicitud
			$datosexamen=$objdatos->DatosExamen($idarea,$idsolicitud,$idexamen);
			
			$imprimir="<form name='frmDatos'>
			<table width='75%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		    </tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
				<td class='StormyWeatherDataTD' colspan='3'>".$establecimiento."</td>
            <tr>
			</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='".$precedencia."' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>".htmlentities($origen)."
					<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
					<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
					<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
					<input name='idarea' id='idarea'  type='hidden' size='40' value='".$idarea."' disabled='disabled' />
                    <input name='fechaNac' id='fechaNac'  type='TEXT' size='40' value='".$fechaNac."' disabled='disabled' />
				</td>
		    	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($medico)."
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($paciente)." 
			     		<input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'><div id='divsuedad'>
				
				</div></td>
				
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>
				$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
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
			while($fila = mysql_fetch_array($datosexamen)){
			   $imprimir .= "<tr>
						<td>".$fila['IdExamen']."</td>
						<td>".htmlentities($fila['NombreExamen'])."</td>	
                				<td>".htmlentities($fila['TipoMuestra'])."</td>";	
                 			if (!empty($fila['Indicacion'])){     				
   				   $imprimir .="<td>".htmlentities($fila['Indicacion'])."</td></tr>";
                  			}
				 	else
   				   $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td></tr>"; 
	                           $pos=$pos + 1;
			}
			mysql_free_result($datosexamen);

 		$imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
			<table align='center'>
			<tr>
				<td colspan='4' align='center'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' align='center'>
				VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO</td>
			</tr>
			<tr>
				<td>Procesar Muestra</td>
				<td><select id='cmbProcesar' name='cmbProcesar' size='1' onChange='MostrarObservacion();' >
					<option value='0' >--Seleccione--</option>
					<option value='S' >Si</option>
			        	<option value='N' >No</option>		
			    	     </select> 
				</td>
					
				<td colspan='2' >
					<input type='button' name='btnProcesar' disabled='disabled' id='btnProcesar'   value='Procesar Muestra' onClick=\"ProcesarMuestra1('".$idexamen."')\">
					<input type='button' name='btnRechazar'  id='btnRechazar' disabled='disabled' value='Rechazar Muestra' onClick=\"RechazarMuestra1('".$idexamen."')\">
			<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>
			</td>
			
		</tr>
		</table>
		<div id='divObservacion' style='display:none' >
		<table align='center' width='55%'>
		<tr>
			<td>Observacion</td>
			<td colspan='3'>
			<textarea cols='60' rows='2' name='txtobservacion' > </textarea>
			</td>
		</tr>
		</table>
		</div>
		</form>";
     echo $imprimir;
	// break;
	}else{	
		//echo $idarea;
			// echo $idexamen;
		include_once("clsMuestrasPendientes.php");
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
		$FechaNac=$row['FechaNacimiento'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idarea,$idsolicitud);
		$imprimir="<form name='frmDatos'>
          <table width='75%' border='0' align='center' class='StormyWeatherFormTABLE'>
		  <tr>
		    <td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		    </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
				<td class='StormyWeatherDataTD' colspan='3'>".$establecimiento."</td>
                     	</tr>
		  <tr>
		    <td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
		    <td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='".$precedencia."' disabled='disabled' /></td>
		    <td class='StormyWeatherFieldCaptionTD'>Origen</td>
		    <td class='StormyWeatherDataTD'>".htmlentities($origen)."
				<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
			<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
			<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
			<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
			<input name='idarea' id='idarea'  type='hidden' size='40' value='".$idarea."' disabled='disabled' />
			<input name='suEdad' id='suEdad'  type='hidden' size='40' value='".$FechaNac."' disabled='disabled' />
			</td>
		 </tr>
		 <tr>
		    <td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
		    <td colspan='3' class='StormyWeatherDataTD'>".htmlentities($medico)."
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
		    </tr>
		  <tr>
		    <td class='StormyWeatherFieldCaptionTD'>Paciente</td>
		    <td colspan='3' class='StormyWeatherDataTD'>".htmlentities($paciente)." 
			     <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		    </tr>
		  <tr>
		    <td class='StormyWeatherFieldCaptionTD'>Edad</td>
		    <td class='StormyWeatherDataTD'><div id='divsuedad'>
				
				</div><input type='hidden' name='txtedad' value='". $edad."' disabled='disabled' /></td>
		    <td class='StormyWeatherFieldCaptionTD'>Sexo</td>
		    <td class='StormyWeatherDataTD'>
				&nbsp;&nbsp;&nbsp;$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
		    </tr>
		
	    </table>
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
$imprimir .= "<tr>
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
			<table align='center'>
			<tr>
			<td colspan='4' align='center'>&nbsp;

			</td>
			</tr>
			<tr>
			<td colspan='4' align='center'>
			VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO
			</td>
			</tr>
			<tr>
			<td>Procesar Muestra</td>
			<td><select id='cmbProcesar' name='cmbProcesar' size='1' onChange='MostrarObservacion();' >
					<option value='0' >--Seleccione--</option>
					<option value='S' >Si</option>
			        <option value='N' >No</option>		
			    </select> 
			</td>
			<td colspan='2' >
			<input type='button' name='btnProcesar' id='btnProcesar' disabled='disabled'  value='Procesar Muestra' onClick='ProcesarMuestra()'>
			<input type='button' name='btnRechazar' id='btnRechazar' disabled='disabled' value='Rechazar Muestra' onClick='RechazarMuestra()'>
			<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>
			</td>
			</tr>
			</table>
			<div id='divObservacion' style='display:none' >
			<table align='center' width='55%'>
			<tr>
			<td>Observacion</td>
			<td colspan='3'>
			<textarea cols='60' rows='2' name='txtobservacion' > </textarea>
			</td>
			</tr>
			</table>
			</div>
			</form>";
     echo $imprimir;
	
}
   break;
   case 3:
	$idexpediente=$_POST['idexpediente'];
	$idsolicitud=$_POST['idsolicitud'];
	$idarea=$_POST['idarea'];
	$estado=$_POST['estado'];
	$fechasolicitud=$_POST['fechasolicitud'];
	$idexamen=$_POST['idexamen'];
	$fecharecep=$_POST['fecharecep'];
  
  	if ($idarea=="URI" or $idarea=="BAT" ){   
	    if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)==true)   
		{
		//actualizar en la tabla de recepcion
		  if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
			{
			 	 echo "Solicitud Procesada..";
			}
		}
		else{
			echo "Solicitud No fue cambiada de Estado..";
		}
	}
	else{
		if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea)==true)   
		  {
			echo "Muestras Recibidas.";	
			if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
				{
				 //echo "Solicitud Procesada..";
				 }
				 else{
					echo "Solicitud No fue cambiada de Estado..";
				 }
		}
				//else{
			//echo "No se pudo actualizar";
		}
		//}
	break;
			
	 
	case 4:// Rechazar Muestra
		$idsolicitud=$_POST['idsolicitud'];
		$idarea=$_POST['idarea'];
		$estado=$_POST['estado'];
		$fechasolicitud=$_POST['fechasolicitud'];
		$idexamen=$_POST['idexamen'];
		$fecharecep=$_POST['fecharecep'];
		$observacion=$_POST['observacion'];
		if ($idarea=="URI" or $idarea=="BAT"){   
			if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)==true)   
			{
				if($objdatos->MarcarObservacionRechazado1($idsolicitud,$idexamen,$observacion)==true)
				{
					echo "Muestra Rechazada";
				}
			}
		}
		else{
			if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea)==true)   
			{
				//actualizar en la tabla de recepcion
				if($objdatos->MarcarObservacionRechazado($idsolicitud,$idarea,$observacion)==true)
				{
					echo "Muestras Rechazada";
				}	
			}
		
		}
		
	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		$idarea=$_POST['idarea'];
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
	       $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	       $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
	       $rslts .='<option value="0"> Seleccione Subespecialidad </option>';
	       while ($rows =mysql_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       }
				
	       $rslts .='</select>';
	       echo $rslts;
        break;	



   break;
}

?>