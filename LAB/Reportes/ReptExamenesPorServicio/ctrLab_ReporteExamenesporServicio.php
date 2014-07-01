<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsReporteExamenesporServicio.php");

//variables POST
$opcion=$_POST['opcion'];
//$pag =$_POST['pag'];

//creando los objetos de las clases
$obj = new clsReporteExamenesporServicio;

switch ($opcion) 
{
	case 1: 
  		$procedencia=$_POST['procedencia'];
		$fechainicio=$_POST['fechainicio'];
		$fechafin=$_POST['fechafin'];
		$subservicio=$_POST['subservicio'];
		$ffechaini=$fechainicio." 00:00:00";
		$ffechafin=$fechafin." 23:59:59";
		$j=0;
		$i=0;
		$arrayidareas = array();
		$arrayareas = array();
		$cadena="";
	
                $consulta=$obj->LeerAreas($lugar);
		$NroRegistros= $obj->NumeroDeRegistros($lugar);
		while ($rowareas=mysql_fetch_array($consulta)){
			$arrayidareas[$j]=$rowareas[0];
			$arrayareas[$j]=$rowareas[1];
			$arraynombres[$j]="AREA".$j;
			$j++;
		}
	
		for ($i=0;$i<$NroRegistros;$i++){
		   $cadena=$cadena."sum(if(lab_areas.IdArea='$arrayidareas[$i]',1,0)) AS AREA$i,";
		}

		//print_r($arraynombres);	
		$query = "SELECT $cadena NombreSubServicio as origen,
			sum(if(sec_detallesolicitudestudios.IdExamen<>'',1,0)) AS total ,mnt_servicio.IdServicio, mnt_servicio.NombreServicio 
			FROM sec_detallesolicitudestudios 
			INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud 
			INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen 
			INNER JOIN lab_examenesxestablecimiento ON lab_examenesxestablecimiento.IdExamen=lab_examenes.IdExamen 
			INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea 
			INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
			INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico 
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio 
			INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
			WHERE sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_areasxestablecimiento.Condicion='H' 
			AND lab_areasxestablecimiento.IdEstablecimiento=$lugar AND";

		$ban=0;
		//VERIFICANDO LOS POST ENVIADOS
		if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
		{ $query .= " (lab_resultados.FechaHoraReg >='".$ffechaini."' AND lab_resultados.FechaHoraReg <='".$ffechafin."') AND";}
			
		if (!empty($_POST['procedencia']))
		{ $query .= " mnt_subservicio.IdServicio='".$_POST['procedencia']."' AND";}
		
		if (!empty($_POST['subservicio']))
		{ $query .= " sec_historial_clinico.IdSubServicio='".$_POST['subservicio']."' AND";}
				
		if((empty($_POST['procedencia'])) and (empty($_POST['subservicio'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
		{
			$ban=1;
		}
				
		if ($ban==0)
		{   $query = substr($query ,0,strlen($query)-3);
                    $query_search = $query. " GROUP BY sec_historial_clinico.IdSubServicio
                    ORDER BY sec_historial_clinico.IdSubServicio";
		}
			
		//echo $query_search;
		$consulta=$obj->BuscarExamenesporSubServicio($query_search);
					
	if (!empty($_POST['procedencia'])){// reporte de una especialidad especificada
		//GENERACION DE EXCEL
		$NombreExcel="Rep_pruebas_por_servicio".'_'.date('d_m_Y__h_i_s A');
	      	$nombrearchivo = "../../../Reportes/".$NombreExcel.".ods";
		//echo $nombrearchivo;
	       	$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
			//***********************
	      	$consultaSubServicios=$obj->consultaSubservicios($procedencia);
	       	$consultaServicios=$obj->NombreServicio($procedencia);
	       	$consultaAreas=$obj->consultarareas($lugar);
	
	       	$rowServicio = mysql_fetch_array($consultaServicios);
		$FechaI=explode('-',$_POST['fechainicio']);
		$FechaF=explode('-',$_POST['fechafin']);
		$FechaI2=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
		$FechaF2=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
	      echo"<table width='100%' hight='10%' align='center'>
	           <tr>
	       		<td colspan='28' align='center'>
                            <a href='".$nombrearchivo."'><H5>DESCARGAR REPORTE EXCEL <img src='../../../Imagenes/excel.gif'></H5></a>
                        </td>
	           </tr>";
         $imprimir="<tr>
                        <td colspan='28' align='center'><h3>REGISTRO DE EXAMENES PRACTICADOS A LOS DIFERENTES SERVICIOS SEPARADOS POR SECCION</h3></td>
                    </tr>
                    <tr>
			<td colspan='28' align='center' ><h4>PROCEDENCIA:".$rowServicio['NombreServicio']."</h4></td>
                    </tr>
                    <tr>
			<td colspan='28' align='center'><h4>PERIODO DEL:  ".$FechaI2." AL ".$FechaF2."</h4></td>
                    </tr>
                    </table>";
        $imprimir.="<table width='90%' border='1' align='center' >
		    <tr style='background:#BBBEC9'>
			<td class='CobaltFieldCaptionTD' width='20%'><strong>Servicio</strong></td>";
		while ($rowarea=mysql_fetch_array($consultaAreas)){
	    $imprimir.="<td class='CobaltFieldCaptionTD' width='12%'><strong>".htmlentities($rowarea['NombreArea'])."</strong></td>";
			}
	    $imprimir.="<td class='CobaltFieldCaptionTD' width='12%'><strong>TOTAL</strong></td></tr>";
		while ($row = mysql_fetch_array($consulta)){
	 $imprimir.="<tr>
			<td width='10%'>".$row['origen']."</td>";
			for($x=0;$x<$NroRegistros;$x++){ 	
	    $imprimir.="<td width='10%'>".$row[$arraynombres[$x]]."</td>";
			}
	    $imprimir.="<td width='14%'><strong>".$row['total']."</strong></td>
		    </tr>";
		}
    $imprimir.="</table>";
			
		//CIERRE DE ARCHIVO EXCEL
	    fwrite($punteroarchivo,$imprimir);
	    fclose($punteroarchivo);
		//************************/ 
	      echo $imprimir;
	}
	else{ //reporte de todas las especialidades
 			 //  GENERACION DE EXCEL
		$j=0;
		$i=0;
		$arrayidareas = array();
		$arrayareas = array();
		$cadena="";
		
		$consulta=$obj->LeerAreas($lugar);
		$NroRegistros= $obj->NumeroDeRegistros($lugar);
                
		while ($rowareas=mysql_fetch_array($consulta)){
			$arrayidareas[$j]=$rowareas[0];
			$arrayareas[$j]=$rowareas[1];
			$arraynombres[$j]="AREA".$j;
			$j++;
		}
		
		for ($i=0;$i<$NroRegistros;$i++){
			$cadena=$cadena."sum(if(lab_areas.IdArea='$arrayidareas[$i]',1,0)) AS AREA$i,";
		}
			
     		$NombreExcel="Rep_General_de_pruebas_por_servicio".'_'.date('d_m_Y__h_i_s A');
    		$nombrearchivo = "../../../Reportes/".$NombreExcel.".ods";
     		$punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");
		//***********************
		$FechaI=explode('-',$_POST['fechainicio']);
		$FechaF=explode('-',$_POST['fechafin']);
		$FechaI2=$FechaI[2].'/'.$FechaI[1].'/'.$FechaI[0];
		$FechaF2=$FechaF[2].'/'.$FechaF[1].'/'.$FechaF[0];
		$imprimir1="<table width='100%' hight='10%' align='center'>
				<tr>
					<td colspan='5' align='center'><a href='".$nombrearchivo."'><H5>DESCARGAR REPORTE EXCEL <img src='../../../Imagenes/excel.gif'></H5></a></td>
				</tr>
				<tr>
					<td colspan='25' align='center'><h3>REGISTRO DE EXAMENES PRACTCADOS A LOS DIFERENTES SERVICIOS SEPARADOS POR SECCION</h3></td>
				</tr>
				<tr>
					<td colspan='25' align='center'><h3>PERIODO DEL:  ".$FechaI2." AL ".$FechaF2."</h3></td>
				</tr>
                            </table>";
		echo $imprimir1;
		fwrite($punteroarchivo,$imprimir1);
		$consultaSubServicio=$obj->consultaTodosServicios($lugar); 
		while($rowServicio=mysql_fetch_array($consultaSubServicio)){
		$imprimir="<table colspan='21' width='100%' hight='10%' align='center'>
	        	   <tr>
				<td colspan='25' align='center' ><h3>PROCEDENCIA: ".$rowServicio['NombreServicio']."</h3></td>
			   </tr>
                           </table>";
				//$servicio=$rowServicio['IdServicio'];
			$cantidad=$obj->cantidadxservicio($rowServicio['IdServicio'],$ffechaini,$ffechafin,$lugar);
			if ($cantidad>0){
			//echo $servicio."-".$ffechaini."-".$ffechafin;
					$consulta=$obj->subserviciosxservicio($rowServicio['IdServicio'],$ffechaini,$ffechafin,$cadena,$lugar);
		    			$imprimir.="<table width='100%' border='1' align='center'>
						<tr style='background:#BBBEC9'>
							<td class='CobaltFieldCaptionTD'><strong>Servicio</strong></td>";
					$consultaAreas=$obj->consultarareas($lugar);
					while (	$rowarea=mysql_fetch_array($consultaAreas)){
			    	    		$imprimir.="<td class='CobaltFieldCaptionTD'><strong>".htmlentities($rowarea['NombreArea'])."</strong></td>";}
				   		$imprimir.="<td class='CobaltFieldCaptionTD'><strong>TOTAL</strong></td>
						</tr>";
					//$consulta=$obj->BuscarExamenesporSubServicio($query_search);	
					while ($row = mysql_fetch_array($consulta)){
			     	$imprimir.="<tr>
						<td width='10%'>".$row['origen']."</td>";
	             
		  			for($x=0;$x<$NroRegistros;$x++){ 	
			            $imprimir.="<td width='10%'>".$row[$arraynombres[$x]]."</td>";
	              	       
					}
		              	    $imprimir.="<td width='14%'><strong>".$row['total']."</strong></td>
		                           </tr>";
				   	
					}
				$imprimir.="</table>
				<br><br>"; 
				//CIERRE DE ARCHIVO EXCEL
			}
		//fwrite($punteroarchivo,$imprimir1);
		echo $imprimir;	

		//***********************			
		fwrite($punteroarchivo,$imprimir);
		
		}	//while principal	 
	fclose($punteroarchivo);
	}//else
	break;
    
	
	  
    case 3://LLENANDO COMBO subservicio
	
		$rslts='';
		$proce=$_POST['proce'];
		//echo $IdSubEsp;
		$dtMed=$obj->LlenarSubServ($proce);	
		
		$rslts = '<select name="cboMedicos" id="cmbSubServicio" class="MailboxSelect" style="width:250px">';
		$rslts .='<option value="0">--Seleccione un Servicio--</option>';
			
		while ($rows =mysql_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[1] .'" >'. htmlentities($rows[0]).'</option>';
		}
				
		$rslts .= '</select>';
		echo $rslts;
	
	break;	
    case 4:// Vista Previa Reporte 
		//echo $medico."+".$IdSubEsp."+".$especialidad."-".$fechainicio."-".$fechafin;
    $query = "SELECT  sec_historial_clinico.IdNumeroExp, 
	      sec_solicitudestudios.IdSolicitudEstudio,
	      DATE_FORMAT(sec_solicitudestudios.FechaSolicitud ,'%e/ %m / %Y') AS FechaSolicitud,
	       mnt_subservicio.NombreSubServicio AS origen, mnt_servicio.NombreServicio AS procedencia,
			mnt_empleados.NombreEmpleado AS medico, 
			CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) as NombrePaciente,
			CASE sec_solicitudestudios.Estado 
				WHEN 'D' THEN 'Digitada'
				WHEN 'R' THEN 'Recibida'
				WHEN 'P' THEN 'En Proceso'    
				WHEN 'C' THEN 'Completa' END AS Estado
			FROM sec_historial_clinico 
			INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
			INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio 
			INNER JOIN mnt_empleados ON sec_historial_clinico.IdEmpleado= mnt_empleados.IdEmpleado
			INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp= mnt_expediente.IdNumeroExp
			INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente= mnt_datospaciente.IdPaciente
			WHERE  sec_solicitudestudios.IdServicio ='DCOLAB' AND";
		$ban=0;
			//VERIFICANDO LOS POST ENVIADOS
	if (!empty($_POST['especialidad']))
	{ $query .= " sec_historial_clinico.IdSubEspecialidad='".$_POST['especialidad']."' AND";}
	
	if (!empty($_POST['medico']))
		{ $query .= " sec_historial_clinico.IdEmpleado='".$_POST['medico']."' AND";}
	
	if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
	{ $query .= " sec_solicitudestudios.FechaSolicitud BETWEEN '".$_POST['fechainicio']."' AND '".$_POST['fechafin']."' ";}
  
  
  	if((empty($_POST['especialidad'])) and (empty($_POST['medico'])) and (empty($_POST['fechainicio'])) and (empty($_POST['fechafin'])))
	{
				$ban=1;
	}
			
	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-1);
		$query_search = $query. " order by PrimerApellido";
	}
		
		
			ECHO $query_search;
             $consulta1=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
			 
			$row1 = mysql_fetch_array($consulta1);
  $imprimir=" <table width='90%' higth='10%' border='0' align='center'>
              <tr>
                    <td colspan='7' align='center'><h3><strong>REPORTE DE SOLICITUDES POR ESPECIALIDAD
			</h3></strong></td>
	      </tr>
	      <tr>
			<td colspan='7' align='center'><h3>".htmlentities($row1['procedencia'])."</h3></td>
	               </td>
	      </tr>
			<tr>
			<td colspan='7' align='center'><h4>".htmlentities($row1['origen'])."</h4></td>
			</td>
			</tr>
			</table>";
  $consulta=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
 $imprimir.="<table width='75%' border='1' align='center'>
			
            <tr class='CobaltFieldCaptionTD'>
				<td>Fecha Solicitud </td>
				<td>NEC </td>
				<td>Nombre Paciente</td>
				<td>Medico</td>
				<td>Estado Solicitud</td>
		    </tr>";    
 $pos=0;
    while ($row = mysql_fetch_array($consulta))
	{ 
$imprimir .="<tr>
		   <td width='11%'>".$row['FechaSolicitud']."</td>
		   <td width='8%'>".$row['IdNumeroExp']."</td>". 
		   "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
		   "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
		   "<td width='31%'>".htmlentities($row['NombrePaciente'])."</td>
		 
		  
		    <td width='31%'>".htmlentities($row['medico'])."</td>
		    <td width='15%'>".$row['Estado']."</td>
		   </tr>";

	$pos=$pos + 1;
	}
	
	mysql_free_result($consulta);
	
   $imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
   
		</table>";
    
	echo $imprimir;
	
	echo "<table width='90%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center'>	
				<div id='boton'>	<input type='button' id='btnSalir' value='Cerrar' class='MailboxButton' onClick='cerrar()'></div>
				</td>
				
			</tr></table>";
//	echo $imprimir;


///////////////////////////////////////////////////////////////// Archivo
 //$Nombre=$NEC."_".$Solicitud."_".$Fecha;
	//		 $nombrearchivo = './../../../Solicitudes/'.$Nombre.'.pet';
		//	// "../ReportesExcel/".$NombreExcel.".xls";
	 //$punteroarchivo = fopen($nombrearchivo, "w+") or die.("El archivo de reporte no pudo crearse");
 }