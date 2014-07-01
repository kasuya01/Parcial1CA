<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsEliminarResultado.php");

//variables POST

$opcion=$_POST['opcion'];

//$pag =$_POST['pag'];
//creando los objetos de las clases
$objdatos = new clsEliminarResultado;

//echo $idexpediente;
switch ($opcion) 
{
  	case 1:  
		
		$idexpediente=$_POST['idexpediente'];
		$primernombre=$_POST['primernombre'];
		$segundonombre=$_POST['segundonombre'];
		$primerapellido=$_POST['primerapellido'];
		$segundoapellido=$_POST['segundoapellido'];
		$fecharecep=$_POST['fecharecep'];
		$IdEstab=$_POST['IdEstab'];
		$IdServ=$_POST['IdServ'];
 		$IdSubServ=$_POST['IdSubServ'];

		$query = "SELECT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,NombreSubServicio AS Origen,NombreServicio AS Procedencia, mnt_expediente.IdNumeroExp as IdNumeroExp,CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,DATE_FORMAT(FechaSolicitud,'%e/ %m / %Y') AS FechaSolicitud,sec_solicitudestudios.IdSolicitudEstudio,
		CASE sec_solicitudestudios.Estado 
		WHEN 'D' THEN 'Digitada'
		WHEN 'R' THEN 'Recibida'
		WHEN 'P' THEN 'En Proceso'    
		WHEN 'C' THEN 'Completa' END AS Estado,mnt_establecimiento.Nombre
		FROM sec_historial_clinico 
		INNER JOIN sec_solicitudestudios   ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_empleados  ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
		INNER JOIN mnt_expediente  ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
		INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN lab_recepcionmuestra ON sec_solicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
                INNER JOIN sec_detallesolicitudestudios ON sec_solicitudestudios.Idsolicitudestudio=sec_detallesolicitudestudios.Idsolicitudestudio
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB'  AND IdExamen like '$area%' AND EstadoDetalle='RC' AND ";
		$ban=0;	

		//VERIFICANDO LOS POST ENVIADOS
			
		if (!empty($_POST['idexpediente']))
		{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
		
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
				
						
		if (!empty($_POST['fecharecep']))
			{$Nfecha=explode("/",$fecharecep);
		 	//print_r($Nfecha);
                  	$Nfecharecep=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
		$query .= " lab_recepcionmuestra.fecharecepcion='".$Nfecharecep."' AND";}
		
		if((empty($_POST['idexpediente'])) AND (empty($_POST['primerapellido'])) AND (empty($_POST['segundoapellido'])) AND (empty($_POST['primernombre'])) AND (empty($_POST['segundonombre']))AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['fecharecep'])))
		{
				$ban=1;
		}
			
		if ($ban==0)
		{   $query = substr($query ,0,strlen($query)-3);
		    $query_search = $query. " ORDER BY IdSolicitudEstudio DESC";
		}
		//echo $query_search;
		
		$consulta=$objdatos->BuscarSolicitudesPaciente($query_search); 
		$NroRegistros= $objdatos->NumeroDeRegistros($query_search);				

     $imprimir="<table width='85%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center' ><h3><strong>TOTAL DE SOLICITUDES: ".$NroRegistros."</strong></h3></td>
			</tr>
		</table> "; 
    $imprimir.="<table width='85%' border='1' align='center'>
			<tr class='CobaltFieldCaptionTD'>
				<td>Fecha Recepci&oacute;n</td>
				<td>NEC </td>
				<td>Nombre Paciente</td>
				<td>Origen</td>
				<td>Procedencia</td>
				<td>Establecimiento</td>
				<td>Estado Solicitud</td>
				<td>Fecha Consulta</td>
			</tr>";    
		$pos=0;
		while ($row = mysql_fetch_array($consulta))
		{ 
			$Idsolic=$row['IdSolicitudEstudio'];
			$fecha=$objdatos->BuscarRecepcion($Idsolic);
			$recepcion= mysql_fetch_array($fecha);
			$fechacita=$objdatos->BuscarCita($Idsolic);
			$cita= mysql_fetch_array($fechacita);
			if (!empty($recepcion)){
            $imprimir .="<tr>
				<td>".$recepcion['0']."</td>";
			}else{ 		
	   $imprimir .="<tr>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
			}
			
	           $imprimir .="<td><a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".$row['IdNumeroExp']."</a>". 
					"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
					"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' /></td>".
                                        "<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$area."' /></td>".
				"<td>".htmlentities($row['NombrePaciente'])."</td>
				 <td>".htmlentities($row['Origen'])."</td>
				 <td>".htmlentities($row['Procedencia'])."</td>
				 <td>".htmlentities($row['Nombre'])."</td>
				 <td>".$row['Estado']."</td>
				 <td>".$row['FechaSolicitud']."</td>
			</tr>";

				$pos=$pos + 1;
			}
			
			mysql_free_result($consulta);
			
		$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
  
		</table>";
    			echo $imprimir;
		
		
		
	break;
    	
	case 2:  // solicitud estudios
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
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
		$DatosClinicos=$row['DatosClinicos'];
		$Estado=$row['Estado'];
		$fechasolicitud=$row['FechaSolicitud'];
		$fecharecep=$row['FechaRecepcion'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud,$area);
                                                                 
		$estadosolicitud=$objdatos->EstadoSolicitud($idexpediente,$idsolicitud);
		$estado=mysql_fetch_array($estadosolicitud);
		
		
		$imprimir="
	<form name='frmDatos'>
			<table width='60%' border='0' align='center'>
				<tr>
					<td  colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp</td>
				</tr>
				<tr>
					<td colspan='4' align='center' class='CobaltFieldCaptionTD'>
						<h3><strong>DATOS SOLICITUD</strong></h3>
					</td>
				</tr>
				<tr>
					<td>Procedencia</td>
					<td>".$precedencia."</td>
					<td>Origen</td>
					<td>".$origen."
						<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
						<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled'/>
						<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' /></td>
				</tr>
				
				<tr>
					<td>Medico</td>
					<td colspan='2'>".htmlentities($medico)."</td>
					<td>Fecha Recepcion</td>
					<td >".$fecharecep."</td>
				</tr>
				<tr>
					<td>Paciente</td>
					<td colspan='3'>".htmlentities($paciente)."</td>
				</tr>
				<tr>
					<td>Edad</td>
					<td>". $edad."</td>
					<td>Sexo</td>
					<td>".$sexo."</td>
					<td>Fecha Recepcion</td>
					<td >".$fecharecep."</td>
				</tr>
			</table>
			<table width='60%' border='0' align='center'>
				<tr>
					<td colspan='4'  class='CobaltFieldCaptionTD' align='center'>ESTUDIOS SOLICITADO</td>
				</tr>
				<tr>
					<td>
						<table border = 1 align='center' class='estilotabla'>
						<tr>
							<td> IdExamen</td>
							<td> Examen </td>
							<td> IdArea </td>
							<td> Indicacion Medica </td>
							<td> Estado </td>
				                </tr>
					<td>
				</tr>";
			$pos=0;
		while($fila = mysql_fetch_array($consultadetalle)){
                  $imprimir .= "<tr>";
					if (($fila['Estado'])=="Resultado Completo"){
					 // $detalle=$fila['IdDetalleSolicitud'];
			   $imprimir .="<td><a style ='text-decoration:underline;cursor:pointer;' onclick='EliminarDatos(".$pos.");'>".$fila['IdExamen']."</a></td>";
		           $imprimir .="<td>".htmlentities($fila['NombreExamen'])."</td>	
					<td>".$fila['IdArea']."</td>";	
						if (!empty($fila['Indicacion'])){     				
			   $imprimir .="<td>".htmlentities($fila['Indicacion'])."</td>";
		           $imprimir .="<td>".$fila['Estado']."</td>
			        </tr>";
						}else{
		           $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
			                <td>".$fila['Estado']."</td>";
						}
					}else{
			   $imprimir .="<td>".$fila['IdExamen']."</td>
			                <td>".htmlentities($fila['NombreExamen'])."</td>	
					<td>".$fila['IdArea']."</td>";	
						if (!empty($fila['Indicacion'])){     				
			   $imprimir .="<td>".htmlentities($fila['Indicacion'])."</td>";
		           $imprimir .="<td>".$fila['Estado']."</td>
							
		                </tr>";
						}else{
	                   $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
			                <td>".$fila['Estado']."</td>
                                </tr>";	
						}//else de indicacion
					}// del else de estado
              $pos=$pos + 1;
				
		}// del while

		mysql_free_result($consultadetalle);
			
 //$imprimir .= "<i/nput type='hidden' name='oculto' id='oculto' value='".$pos."' />
           $imprimir .="</table>
		<div id='divImpresion' style='display:block' >
			<table>
				<tr>
					<p></p>
				</tr>
				<tr>
					<td align='center'>
						<input type='button' name='btnImprimirSol' id='btnImprimirSol' value='Imprimir Solicitud' onClick='ImprimirSolicitud()'>";
						if($estado['Estado']=='R' OR $estado['Estado']=='P'){
								$imprimir .="<input type='button' name='btnImprimir'  id=btnImprimir' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes();'/>";}
						else{
								$imprimir .="<input type='button' name='btnImprimir' disabled='enabled' id=btnImprimir' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes();'/>";
						}
			$imprimir .="</td>
				</tr>
				<tr>
					<p></p>
				</tr>
			</table>
		</div>
	</form>";
     echo $imprimir;
	 
	 
   break;
   case 3:
		//recuperando los valores generales de la solicitud
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
                $idarea=$_POST['idarea'];
               // echo $idarea;
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
		$row = mysql_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		$medico=$row['NombreMedico'];
		$idmedico=$row['IdMedico'];
		$paciente=$row['NombrePaciente'];
		$edad=$row['Edad'];
		$sexo=$row['Sexo'];
		$precedencia=$row['Precedencia'];
		$origen=$row['Origen'];
		$DatosClinicos=$row['DatosClinicos'];
		$Estado=$row['Estado'];
		$fechasolicitud=$row['FechaSolicitud'];
		$fecharecep=$row['FechaRecepcion'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud,$idarea);
		$imprimir="<form name='frmDatos'>
		<table width='55%' border='0' align='center'>
		<tr>
			<td colspan='4' align='center' class='CobaltFieldCaptionTD'><h3><strong>DATOS SOLICITUD</strong></h3></td>
		</tr>
		<tr><td>Establecimiento:</td>
			<td colspan='3'>".$row['Nombre']."</td>
		</tr>
		<tr>
			<td>Procedencia: </td>
			<td>".$precedencia."</td>
			<td>Origen: </td>
			<td>".htmlentities($origen)."
				<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
				<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
				<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
			</td>
		</tr>
		<tr>
		    <td>Medico: </td>
		    <td >".htmlentities($medico)."</td>
			<td>Fecha Recepcion</td>
			<td >".$fecharecep."</td>
		</tr>
		<tr>
			<td>Paciente: </td>
		    <td colspan='3'>".htmlentities($paciente)."</td>
		</tr>
		<tr>
		    <td>Edad: </td>
		    <td>". $edad."</td>
		    <td>Sexo: </td>
		    <td>".$sexo."</td>
		</tr>
		</table>
		
		<table width='90%' border='0' align='center'>
		<tr>
			<td colspan='4' align='center'>ESTUDIOS SOLICITADO</td>
		</tr>
		<tr>
			<td>
				<table border = 1 align='center' class='estilotabla'>
				<tr>
					<td> Eliminar</td>
					<td> IdExamen</td>
					<td> Examen </td>
					<td> IdArea </td>
					<td> Indicacion Medica </td>
					<td> Estado </td>
			   </tr>";
			$pos=0;
	while($fila = mysql_fetch_array($consultadetalle)){
 $imprimir .= "<tr>";
		if($fila['Estado']=="Resultado Completo"){ 	
       $imprimir .="<td><img src='../../../Iconos/eliminar.gif' style=\"text-decoration:underline;cursor:pointer;\" onclick=\"EliminarDatos('".$fila['IdDetalleSolicitud']."','".$fila['IdSolicitudEstudio']."','".$fila['IdPlantilla']."','$idexpediente')\"></td>
					<td>".htmlentities($fila['IdExamen'])."</td>
					<td>".htmlentities($fila['NombreExamen'])."	
						<input name='iddetalle[".$pos."]' type='hidden' id='iddetalle[".$pos."]' value='".$fila['IdDetalleSolicitud']."'>
						<input name='idsolicitud[".$pos."]' type='hidden' id='idsolicitud[".$pos."]' value='".$fila['IdSolicitudEstudio']."'>
						<input name='idplantilla[".$pos."]' type='hidden' id='idplantilla[".$pos."]' value='".$fila['IdPlantilla']."'>				</td>	
					<td>".$fila['IdArea']."</td>";	
					if (!empty($fila['Indicacion'])){     				
		$imprimir .="<td>".htmlentities($fila['Indicacion'])."</td>";
		$imprimir .="<td>".$fila['Estado']."</td>
					<td>".$fila['IdDetalleSolicitud']."</td>
			</tr>";
		}else{
	    $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
	 				 <td>".$fila['Estado']."</td>
			</tr>";	
		}             }
		else{
	    $imprimir .="<td>".$fila['IdExamen']."</td>
	        		<td>".htmlentities($fila['NombreExamen'])."</td>	
					<td>".$fila['IdArea']."</td>";	
			if (!empty($fila['Indicacion'])){     				
		$imprimir .="<td>".htmlentities($fila['Indicacion'])."</td>";
		$imprimir .="<td>".$fila['Estado']."</td>
			</tr>";
			}else{
		$imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
		  			 <td>".$fila['Estado']."</td>
                     <td>".$fila['IdPlantilla']."</td>
			</tr>";	
			 
							}
				}
				$pos=$pos + 1;
				
}



 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
			
			</form>";
     echo $imprimir;
     mysql_free_result($consultadetalle);
  
  break;
  case 4:

	include_once("clsEliminarResultado.php");
		$iddetalle=$_POST['iddetalle'];
		$idplantilla=$_POST['idplantilla'];
		$idsolicitud=$_POST['idsolicitud'];
	
		$dato=$objdatos->VerificaDetalle($idsolicitud,$iddetalle);
			 if ($dato == 1){	
				switch($idplantilla){
					case 'A':
						if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){
							//if (($objdatos->CambiarEstadoDetalle($iddetalle)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true))
							if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)&&($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
								echo "Resultado Eliminado";}
						}
						else{
							echo "No se pudo eliminar el registro";
						}
							//echo "ENTRO A";
                     break;	
					 case 'B':
					 case 'D':
					 case 'E':
                                                //  echo $idsolicitud."--".$iddetalle;
						 $r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
						 $result=mysql_fetch_array($r);
						 $idresultado=$result[0];
						 if($dr=$objdatos->EliminarDetalleResultado($idresultado)==1){
							if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){
								if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true))
								    echo "Resultado Eliminado";
							}
						}
						else{
							echo "No se pudo eliminar el registro";
						}
						  
					  // echo "ENTRO B,D,E";
					 break; 	
					 case 'C':
								
								$r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
								//$idresultado=$result['IdResultado'];
								
							 while($result = mysql_fetch_array($r)){
							    $idresultado=$result['IdResultado'];
							//	echo $idresultado;
								//$tr=$objetos->ObtenerTipoResultado($idresultado);
								//$tipo=mysql_fetch_array($tr);
								$TipoResultado=$result['Resultado'];
								//echo $TipoResultado;
								switch($TipoResultado){
								case 'P':
						//	 while($result = mysql_fetch_array($r)){
									$idresultado=$result['IdResultado'];
									$det=$objdatos->ObtenerIdDetalleRes($idresultado);
									$detalle=mysql_fetch_array($det);
									$iddetalleres=$detalle[0];
						 //		echo $idsolicitud."-".$iddetalle."-".$idresultado."-". $iddetalleres;
									if($dr=$objdatos->EliminarResultadoTarjeta($iddetalleres)==1){
										if($dr=$objdatos->EliminarDetalleResultado($idresultado)==1){
											if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){
												if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true))
													echo "Resultado Eliminado";
											}
										}
									}
									else{
										echo "No se pudo eliminar el registro";
										}
							
								break;
								case 'N':
								case 'O':
										if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){	 
											if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
												echo "Resultado Eliminado";}
											
										}else{
											echo "No se pudo eliminar el registro";
										}
								break; 
								}	
										
						}// while	
								   
					 break;
			
	          }//switch de plantilla	
	   }else{
		     echo "NO HAY DATOS";}
	// }
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

}//switch de opciones

?>