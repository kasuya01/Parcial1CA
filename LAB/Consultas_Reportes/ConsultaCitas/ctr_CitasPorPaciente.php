<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsCitasPorPaciente.php");

//variables POST

//$fechainicio=$_POST['fechainicio'];
$opcion=$_POST['opcion'];
//$fechafin=$_POST['fechafin'];


//echo$idexpediente."".$primernombre."".$segundonombre."".$primerapellido."".$segundoapellido."".$especialidad;
//creando los objetos de las clases
$objdatos = new clsCitasPorPaciente;
//echo $idexpediente;
switch ($opcion) 
{
  	case 1:  
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		$fecha=$_POST['fecha'];
		$primernombre=$_POST['primernombre'];
		$segundonombre=$_POST['segundonombre'];
		$primerapellido=$_POST['primerapellido'];
		$segundoapellido=$_POST['segundoapellido'];
		$IdEstab=$_POST['IdEstab'];
		$IdServ=$_POST['IdServ'];
 		$IdSubServ=$_POST['IdSubServ'];
		$query_search="";
		
  
		$query = "SELECT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,NombreSubServicio AS Origen,
			 NombreServicio AS Procedencia, mnt_expediente.IdNumeroExp AS IdNumeroExp , 
			 CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,
			 DATE_FORMAT(cit_citasxserviciodeapoyo.Fecha,'%e/ %m / %Y') AS Fecha,sec_solicitudestudios.IdSolicitudEstudio, CASE sec_solicitudestudios.Estado 
			 WHEN 'D' THEN 'Digitada'
			 WHEN 'R' THEN 'Recibida'
			 WHEN 'P' THEN 'En Proceso'    
			 WHEN 'C' THEN 'Completa' END AS Estado,mnt_establecimiento.Nombre
			 FROM sec_historial_clinico 
			 INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			 INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
			 INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
			 INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
			 INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
			 INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
			 INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio =cit_citasxserviciodeapoyo.IdSolicitudEstudio
			 INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
			 WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_solicitudestudios.Estado='D' AND sec_solicitudestudios.IdEstablecimiento=$lugar AND";
				
			$ban=0;
				
			//VERIFICANDO LOS POST ENVIADOS
				
			if (!empty($_POST['idexpediente']))
			{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
			
			if (!empty($_POST['fecha']))
			{ $Nfecha=explode("/",$fecha);
		  	 //print_r($Nfecha);
                   	$Nfechacita=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
				$query .= " cit_citasxserviciodeapoyo.Fecha='".$Nfechacita."' AND";}
			
			if (!empty($_POST['primerapellido']))
			{ $query .= " mnt_datospaciente.PrimerApellido='".$_POST['primerapellido']."' AND";}
					
			if (!empty($_POST['segundoapellido']))
			{ $query .= " mnt_datospaciente.SegundoApellido='".$_POST['segundoapellido']."' AND";}
					
			if (!empty($_POST['primernombre']))
			{ $query .= " mnt_datospaciente.PrimerNombre='".$_POST['primernombre']."' AND";}
				
			if (!empty($_POST['segundonombre']))
			{ $query .= " mnt_datospaciente.SegundoNombre='".$_POST['segundonombre']."' AND";}

			if (!empty($_POST['IdEstab']))
			{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_POST['IdEstab']."' AND";}	
			
			if (!empty($_POST['IdServ']))
			{ $query .= " mnt_subservicio.IdServicio ='".$_POST['IdServ']."' AND";}
		
			if (!empty($_POST['IdSubServ']))
			{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}
					
			
			
			if((empty($_POST['idexpediente'])) and (empty($_POST['fecha'])) and (empty($_POST['primerapellido'])) and (empty($_POST['segundoapellido'])) and (empty($_POST['primernombre'])) and (empty($_POST['segundonombre'])) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) )
					{
					$ban=1;
			}
				
			if ($ban==0)
			{    $query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " ORDER BY PrimerApellido";
			}
		
			//ECHO $query_search;

			$consulta=$objdatos->BuscarCitasPaciente($query_search);  
					/*  ----------Datos para  Pacgianción----------------*/
					$RegistrosAMostrar=20;
					$NroRegistros= $objdatos->NumeroDeRegistros($query_search);
	
			$imprimir="<table width='96%' border='0' align='center'>
					<tr>
						<td colspan='7' align='center' ><h3><strong>TOTAL DE SOLICITUDES:".$NroRegistros."</strong></h3></td>
					</tr>
					<tr>
						<td colspan='7' align='center' style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	
					</tr>
				</table> "; 
	
			$imprimir.="<table width='80%' border='1' align='center'>
					<tr class='CobaltFieldCaptionTD'>
						<td>Fecha cita</td>
						<td>NEC </td>
						<td>Nombre Paciente</td>
						<td>Origen</td>
						<td>Procedencia</td>
						<td>Establecimiento</td>
					</tr>";    
				$pos=0;
			while ($row = pg_fetch_array($consulta))
			{ 
			$imprimir .="<tr>
						<td  width='8%'>".$row['Fecha']."</td>
						<td  width='8%'>".$row['IdNumeroExp']."
							<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
							"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />
						</td>
						<td  width='30%'>".htmlentities($row['NombrePaciente'])."</td>
						<td  width='15%'>".htmlentities($row['Origen'])."</td>
						<td  width='15%'>".htmlentities($row['Procedencia'])."</td>
						<td  width='25%'>".htmlentities($row['Nombre'])."</td>
					</tr>";
				$pos=$pos + 1;
			}
		
			pg_free_result($consulta);
		
		$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
			</table>";
		echo $imprimir;
			

	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0"> Seleccione Examen </option>';
			
		while ($rows =pg_fetch_array($dtExam)){
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
               while ($rows =pg_fetch_array( $dtIdEstab)){
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
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	
    	
		
}

?>