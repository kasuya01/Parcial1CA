<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultaResultados.php");

//variables POST

$opcion=$_POST['opcion'];

//$usuario=1;

//creando los objetos de las clases
$objdatos = new clsConsultaResultados;

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
		$ban=0;  
		
		$query="SELECT DATE_FORMAT(lab_recepcionmuestra.FechaRecepcion,'%e/ %m / %Y') AS 	
			FechaRecepcion,sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp,NombreArea,
			CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente,mnt_subservicio.NombreSubServicio,mnt_servicio.NombreServicio ,
			mnt_establecimiento.Nombre, lab_areas .IdArea,sec_solicitudestudios.FechaSolicitud,
			CASE sec_solicitudestudios.Estado 
				WHEN 'D' THEN 'Digitada'
				WHEN 'P' THEN 'En Proceso'
				WHEN 'R' THEN 'Recibida'    
				WHEN 'C' THEN 'Completa' END AS Estado,
			CASE sec_detallesolicitudestudios.EstadoDetalle 
				WHEN 'RC' THEN 'Resultado Comleto'
				WHEN 'RM' THEN 'Muestra Rechazada'
				WHEN 'D'  THEN 'Digitado por Medico'
				WHEN 'PM' THEN 'En Proceso' END AS EstadoDetalle
			FROM sec_detallesolicitudestudios  
			INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
			INNER JOIN lab_recepcionmuestra ON sec_detallesolicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
			INNER JOIN lab_examenes          ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
			INNER JOIN mnt_expediente       ON mnt_expediente.IdNumeroExp=sec_solicitudestudios.IdNumeroExp
			INNER JOIN mnt_datospaciente   ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
			INNER JOIN lab_areas             ON lab_areas.IdArea=lab_examenes.IdArea
			INNER JOIN sec_historial_clinico  ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico .IdHistorialClinico
			INNER JOIN mnt_subservicio	ON sec_historial_clinico .IdSubServicio=mnt_subservicio.IdSubServicio
			INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
			WHERE  ";  
			
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
				$query_search = $query." GROUP BY IdSolicitudEstudio ORDER BY lab_recepcionmuestra.FechaRecepcion DESC";
			}
		
			//echo $query_search;
			
			$consulta=$objdatos->ListadoResultadosPorArea($query_search);     
		// $consulta=$objdatos->ListadoResultadosPorArea($idarea,$idexpediente);
		echo "<table width='80%' border='1' align='center'>
				<tr class='CobaltFieldCaptionTD'>
					<td>Fecha Recepci&oacute;n</td>
					<td>Muestra</td>
					<td>NEC </td>
					<td>Nombre Paciente</td>
					<td>Servicio</td>
					<td>Procedencia</td>
					<td>Establecimiento</td>
					<td>Estado Solicitud</td>
				</tr>";    
		$pos=0;
		
		while ($row = mysql_fetch_array($consulta))
			{
			$Estado=$row['Estado'];
			$Proceso="DetalleResultado";
			echo "<tr>
					<td>".$row['FechaRecepcion']."</td>
					<td>".$row['NumeroMuestra']."</td>";
				if(($Estado=="Completa")||($Estado=="En Proceso")){
			echo  "		<td>
						<a style ='text-decoration:underline;cursor:pointer;' onclick='javascript:window.open(\"Resultados/ResultadosEstudios.php?IdNumeroExp=".$row['IdNumeroExp']."&IdSolicitudEstudio=".$row['IdSolicitudEstudio']."&FechaRecepcion=".$row['FechaRecepcion']."&pag=1&Proceso=".$Proceso."&IdArea=".$row['IdArea']."&FechaSolicitud=".$row['FechaSolicitud']."&IdEstab=".$IdEstab."&lugar=".$lugar."&Flag=1\")'>".$row['IdNumeroExp']."</a></td>
					<td width='25%'>".htmlentities($row['Paciente'])."</td>";
				}else{
						echo "<td>".$row['IdNumeroExp']."</td>
						      <td width='25%'>".htmlentities($row['Paciente'])."</td>";
					}
			
					echo	"<td>".$row['NombreSubServicio']."</td>
						<td width='10%'>".htmlentities($row['NombreServicio'])."</td>
			 			<td width='15%'>".htmlentities($row['Nombre'])."</td>
						<td>$Estado</td>";
				
			
				"</tr>";
		
			$pos=$pos + 1;
			}
			
			mysql_free_result($consulta);
			
		"<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
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
   
   break;
	
    	
}

?>