<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultaMuestrasRechazadas.php");

//variables POST
$opcion=$_POST['opcion'];

//$observacion=$_POST['observacion'];
$estadosolicitud="P";
//echo $fecharecep;

//creando los objetos de las clases
$objdatos = new clsConsultaMuestrasRechazadas;

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
	//echo $IdEstab;
	$ban=0;  
	 $query="SELECT sec_solicitudestudios.IdSolicitudEstudio,lab_recepcionmuestra.NumeroMuestra,sec_solicitudestudios.IdNumeroExp,lab_examenes.IdExamen,
	lab_examenes.NombreExamen,Indicacion,DATE_FORMAT(lab_recepcionmuestra.FechaRecepcion,'%e/ %m / %Y') AS 			FechaRecepcion,sec_detallesolicitudestudios.observacion,mnt_subservicio.NombreSubServicio,mnt_servicio.NombreServicio,
	mnt_establecimiento.Nombre,CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente 
	FROM sec_detallesolicitudestudios  
	INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
	INNER JOIN lab_recepcionmuestra  ON sec_detallesolicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio 
	INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen= lab_examenes.IdExamen
	INNER JOIN lab_areas 	ON  lab_examenes.IdArea=lab_areas.IdArea
	INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
	INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
	WHERE estadodetalle='RM' AND lab_recepcionmuestra.FechaRecepcion<=CURRENT_DATE AND";

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
			{ $query .= " lab_examenes.IdExamen='".$_POST['idexamen']."' AND";}		
		
	if (!empty($_POST['idexpediente']))
		{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
		
	/*if (!empty($_POST['fecharecep']))
		{ $query .= " lab_recepcionmuestra.fecharecepcion='".$_POST['fecharecep']."' AND";}*/
	if (!empty($_POST['fecharecep']))
		{$Nfecha=explode("/",$fecharecep);
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
		
	if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fecharecep'])) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['PNombre'])) AND (empty($_POST['SNombre'])) AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido'])) AND (empty($_POST['idexamen'])))
		{
				$ban=1;
		}
		
	if ($ban==0){
		
				$query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " ORDER BY NumeroMuestra";
	}
	//echo $query_search;
	
	$consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
	$NroRegistros= $objdatos->NumeroDeRegistros($query_search);
 	echo "<table width='100%' border='0' align='center'>
              <tr>
		    <td colspan='7' align='center' ><h3><strong>TOTAL DE MUESTRAS RECHAZADAS:".$NroRegistros."</strong></h3></td>
	      </tr>
	      <tr>
	            <td colspan='7' align='center' style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	
	      </tr>
	      </table> "; 
	 echo "<table width='100%' border='1' align='center' class='StormyWeatherFormTABLE'>
	            <tr class='CobaltFieldCaptionTD'>
			    <td>Muestra </td>
	            	    <td>NEC </td>
			    <td>Paciente</td>
			    <td>Id Examen</td>
			    <td>Examen</td>
			    <td>Observaci&oacute;n</td>
			    <td>Servicio</td>
			    <td>Procedencia</td>
			    <td>Establecimiento</td>
			    <td>Fecha Recepci&oacute;n</td>
			    </tr>";    
	 $pos=0;
	 //$row = mysql_fetch_array($consulta);
	 
	   while ($row = mysql_fetch_array($consulta))
		{ 
		 echo  "<tr>
			   <td width='6%'>".$row['NumeroMuestra']."</td>
			   <td width='6%'>
			   ".$row['IdNumeroExp']."</td>". 
					"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
					"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
					"<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
					"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['IdExamen']."' />".
			   "<td with='30%'>".$row['NombrePaciente']."</td>
			    <td width='8%'>".$row['IdExamen']."</td>
			    <td width='10%'>".htmlentities($row['NombreExamen'])."</td>
			   ";
			   if(!empty($row['observacion']))
					echo "<td width='10%'>".htmlentities($row['observacion'])."</td>";
				
			  else	
					echo "<td width='10%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
					
			   echo " 
			    	  <td width='10%'>".htmlentities($row['NombreSubServicio'])."</td>
			          <td width='10%'>".htmlentities($row['NombreServicio'])."</td>
			          <td width='12%'>".htmlentities($row['Nombre'])."</td>
				  <td width='8%'>".$row['FechaRecepcion']."</td>
			   </tr>";
		$pos=$pos + 1;
		}
		mysql_free_result($consulta);
		
	   echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
			</table>";
   
   	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
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
   
  
}

?>