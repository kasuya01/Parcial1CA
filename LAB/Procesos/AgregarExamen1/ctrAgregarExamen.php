<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsAgregarExamen.php");

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsAgregarExamen;
//echo $idexpediente;
switch ($opcion) 
{
  	case 1:  
		$idexpediente=$_POST['idexpediente'];
	//$idsolicitud=$_POST['idsolicitud'];
		$primernombre=$_POST['primernombre'];
		$segundonombre=$_POST['segundonombre'];
		$primerapellido=$_POST['primerapellido'];
		$segundoapellido=$_POST['segundoapellido'];
		
		$fechaconsulta=$_POST['fechaconsulta'];
		$IdEstab=$_POST['IdEstab'];
		$IdServ=$_POST['IdServ'];
		$IdSubServ=$_POST['IdSubServ'];
		//echo $IdEstab;	
	
		$query = "SELECT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,NombreSubServicio AS Origen,NombreServicio AS Procedencia, mnt_expediente.IdNumeroExp AS IdNumeroExp,CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,	DATE_FORMAT(FechaSolicitud,'%e/ %m / %Y') AS FechaSolicitud,sec_solicitudestudios.IdSolicitudEstudio, 
                        CASE sec_solicitudestudios.Estado 
			WHEN 'D' THEN 'Digitada'
			WHEN 'R' THEN 'Recibida'
			WHEN 'P' THEN 'En Proceso'    
			WHEN 'C' THEN 'Completa' END AS Estado,mnt_establecimiento.Nombre
		FROM sec_historial_clinico 
		INNER JOIN sec_solicitudestudios   ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_empleados  ON sec_historial_clinico.IdEmpleado=mnt_empleados.IdEmpleado
		INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
		INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND";
		$ban=0;	
	
		//VERIFICANDO LOS POST ENVIADOS
		if (!empty($_POST['IdEstab']))
		{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_POST['IdEstab']."' AND";}	
				
		if (!empty($_POST['IdServ']))
		{ $query .= " mnt_subservicio.IdServicio ='".$_POST['IdServ']."' AND";}
			
		if (!empty($_POST['IdSubServ']))
		{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}
				
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
					
		if (!empty($_POST['fechaconsulta']))
		{ $Nfecha=explode("/",$fechaconsulta);
		  //print_r($Nfecha);
                   $Nfechacon=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
		   $query .= " sec_solicitudestudios.FechaSolicitud='".$Nfechacon."' AND";}
                        		
			
		if((empty($_POST['idexpediente'])) and (empty($_POST['primerapellido'])) and (empty($_POST['segundoapellido'])) and (empty($_POST['primernombre'])) and (empty($_POST['segundonombre'])) and (empty($_POST['fechaconsulta'])) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])))
		{
			$ban=1;
		}
				
		if ($ban==0)
		{   $query = substr($query ,0,strlen($query)-3);
		    $query_search = $query. " ORDER BY IdSolicitudEstudio DESC ";
		}
	     //echo $query_search;
		$consulta=$objdatos->BuscarSolicitudesPaciente($query_search); 
		
		$NroRegistros= $objdatos->NumeroDeRegistros($query_search);				
     $imprimir="<table width='96%' border='0' align='center'>
	        <tr>
			<td colspan='7' align='center' ><h3><strong>TOTAL DE SOLICITUDES: ".$NroRegistros."</strong></h3></td>
		</tr>
		<tr>
		</table> "; 
    $imprimir.="<table width='95%' border='1' align='center'>
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
			$imprimir.="<tr>
					<td width='9%'>".$recepcion['0']."</td>";
						}else{ 		
		$imprimir.="<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
						}
		   $imprimir .="<td width='7%'>
							<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
							$row['IdNumeroExp']."</a>". 
							"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
							"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' /></td>".
							"<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' /></td>".
					   "<td width='25%'>".htmlentities($row['NombrePaciente'])."</td>
						<td width='15%'>".htmlentities($row['Origen'])."</td>
						<td width='12%'>".htmlentities($row['Procedencia'])."</td>
						<td width='15%'>".htmlentities($row['Nombre'])."</td>
						<td width='9%'>".$row['Estado']."</td>
						<td width='12%'>".$row['FechaSolicitud']."</td>
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
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
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
        $FechaNac=$row['FechaNacimiento'];
		$IdEstablecimiento=$row['IdEstablecimiento'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud,$lugar);
		$estadosolicitud=$objdatos->EstadoSolicitud($idexpediente,$idsolicitud);
		$estado=mysql_fetch_array($estadosolicitud);
		
		
		$imprimir="<form name='frmDatos'>
		<table width='55%' border='0' align='center'>
			<tr>
				<td  colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp</td>
			</tr>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>
					<h3><strong>DATOS SOLICITUD</strong></h3>
				</td>
			</tr>
			<tr> 
				<td>Establecimiento</td>
				<td colspan='3'>".htmlentities($row['Nombre'])."</td>
			</tr>
			<tr>
				<td>Procedencia</td>
				<td>".htmlentities($precedencia)."</td>
				<td>Origen</td>
				<td>".htmlentities($origen)."
					<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
					<input name='idestablecimiento' id='idestablecimiento'  type='hidden' size='40' value='".$IdEstablecimiento."' disabled='disabled' />
					<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
					<input name='suEdad' id='suEdad' type='hidden' size='40' value='".$FechaNac."' disabled='disabled' /></td>
			</tr>
			<tr>
				<td>Medico</td>
				<td colspan='3'>".htmlentities($medico)."</td>
			</tr>
			<tr>
				<td>Paciente</td>
				<td colspan='3'>".htmlentities($paciente)."</td>
			</tr>
			<tr>
				<td>Edad</td>
				<td><div id='divsuedad'>
          
    			    </div></td>
				<td>Sexo</td>
				<td>".$sexo."</td>
			</tr>
		</table>
		

		<table width='55%' border='0' align='center'>
			<tr>
				<td colspan='4'  class='CobaltFieldCaptionTD' align='center'>ESTUDIOS SOLICITADO</td>
			</tr>
			<tr>
				<td>
					<table border = 1 align='center' class='estilotabla'>
			   		<tr>
						<td width='10%'> IdExamen</td>
						<td width='39%'> Examen </td>
						<td width='7%'> IdArea </td>
						<td width='20%'> Indicacion Medica </td>
						<td width='21%'> Estado </td>
			   		</tr>";
			$pos=0;
	while($fila = mysql_fetch_array($consultadetalle)){
	  $imprimir .= "<tr>
						<td width='10%'>".$fila['IdExamen']."</td>
						<td width='39%'>".htmlentities($fila['NombreExamen'])."</td>	
                		<td width='7%'>".$fila['IdArea']."</td>";	
            	if (!empty($fila['Indicacion'])){     				
		    $imprimir .="<td width='20%'>".htmlentities($fila['Indicacion'])."</td>";
		    $imprimir .="<td width='21%'>".$fila['Estado']."</td>	
		     	    </tr>";
				}else{
	         $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
		                  <td>".$fila['Estado']."</td>
                    </tr>";	
				}
   	}
	 $imprimir .="</table>";

			mysql_free_result($consultadetalle);
			
 	//$imprimir .= "<i/nput type='hidden' name='oculto' id='oculto' value='".$pos."' />
	 $imprimir .="</table>
	<div id='divImpresion' style='display:block' >
		<table>
			<tr >
				<p></p>
			</tr>
			<tr >
				<td align='center'>
					<input type='button' name='btnImprimirSol' id='btnImprimirSol' value='Agregar Examen' onClick='ImprimirSolicitud()'>";
					
				$imprimir .="</td>
			</tr>
		</table>
	</div>
</form>";
     echo $imprimir;
	 
	 
   	break;
   	case 3:
   		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		$idestablecimiento=$_POST['idestablecimiento'];
		include_once("clsAgregarExamen.php");
		//recuperando los valores generales de la solicitud
		echo $idestablecimiento;
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
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
		$FechaNac=$row['FechaNacimiento'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud);
		$imprimir="<form name='frmDatos'>
    		<table width='90%' border='0' align='center'>
		<tr>
			<td colspan='4' align='center' class='CobaltFieldCaptionTD'><h3><strong>DATOS SOLICITUD</strong></h3></td>
		</tr>
		<tr>
			<td>Establecimiento</td>
			<td>".htmlentities($row['Nombre'])."</td>
		</tr>
		<tr>
			<td>Procedencia: </td>
			<td>".htmlentities($precedencia)."</td>
			<td>Origen: </td>
			<td>".htmlentities($origen)."
				<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
				<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
				<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
				<input name='suEdad' id='suEdad'  type='hidden' size='40' value='".$FechaNac."' disabled='disabled' />
			</td>
		</tr>
		<tr>
	    		<td></td>
	    		<td></td>
	    		<td></td>
	    		<td></td>
		</tr>
		<tr>
			<td>Medico: </td>
		    	<td colspan='3'>".htmlentities($medico)."</td>
		</tr>
		<tr>
	    		<td>Paciente: </td>
		    	<td colspan='3'>".htmlentities($paciente)."</td>
		</tr>
		<tr>
			<td>Edad: </td>
		    	<td><div id='divsuedad'>
          
    			    </div></td>
		    	<td>Sexo: </td>
		    	<td>".$sexo."</td>
		</tr>
		  
	    	</table>
		

		<table width='80%' border='0' align='center'>
		<tr>
			<td colspan='4' align='center' class='CobaltFieldCaptionTD'>ESTUDIOS SOLICITADO</td>
		</tr>
		<tr>
			<td>
				<table border = 1 align='center' class='estilotabla' width='100%' >
			   	<tr>
			   		<td> IdExamen</td>
			  		<td> Examen </td>
			   		<td> IdArea </td>
			   		<td> Indicacion Medica </td>
			   		<td> Estado </td>
			   	</tr>";
		$pos=0;
		while($fila = mysql_fetch_array($consultadetalle)){
		  $imprimir .= "<tr>
			    		<td>".$fila['IdExamen']."</td>
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
				}
                     $pos=$pos + 1;
}

mysql_free_result($consultadetalle);

 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
			<div id='divImpresion' style='display:block' >
				<table aling='center'>
				<tr >
				<p></p>
				</tr>
				<td aling='center'><input type='button' name='btnCerrar' id='btnCerrar' value='Cerrar' onClick='Cerrar()'></td>
				<td ><input type='button' name='btnImprimir' id='btnImprimir'  value='Imprimir' onClick='Imprimir()'></td>
				</tr>
				</table>
			</div>
			</form>";
     echo $imprimir;
   
   break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		$rslts = '<select name="cmbExamen" id="cmbExamen"  onchange="LlenarComboMuestra(this.value)" class="MailboxSelect" style="width:250px">';
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
	case 8://Llenar tipo muestra
		 $rslts='';
         $IdExamen=$_POST['IdExamen'];
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbTipoMuestra($IdExamen);
	     $rslts = '<select name="cmbMuestra" id="cmbMuestra" style="width:375px" onchange="LlenarComboOrigen(this.value)">';
		 $rslts .='<option value="0">--Seleccione Tipo de Muestra--</option>';
		 while ($rows =mysql_fetch_array($dtserv)){
				$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	     }
				
	      $rslts .='</select>';
	      echo $rslts;	
	break;
	case 9://Llenar origen muestra
		 $rslts='';
         $IdTipo=$_POST['IdTipo'];
//	    echo $IdTipo;
	     $dtTipo=$objdatos->LlenarCmbOrigenMuestra($IdTipo);
		 $rslts = '<select name="cmbOrigen" id="cmbOrigen" style="width:375px">';
		 $rslts .='<option value="0">--Seleccione Origen de Muestra--</option>';
		 while ($rows =mysql_fetch_array($dtTipo)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	     }
				
	      $rslts .='</select>';
	      echo $rslts;
	break;
	case 10:
		$idsolicitud=$_POST['idsolicitud'];
		$IdExamen=$_POST['idExamen'];
		$indicacion=$_POST['indicacion'];
		$IdTipo=$_POST['tipoMuestra'];
		$Observa=$_POST['Observacion'];
		$Empleado=$_POST['IdEmpleado'];
		
		$conExam=$objdatos->BuscarExamen($idsolicitud,$IdExamen,$lugar);
		$ExisExa=mysql_fetch_array($conExam);
		
		if($ExisExa[0]>=1){	
			echo "El examen ya existe en la solicitud";
		}	
		else{
		// echo $idsolicitud." - ".$IdExamen." - ".$indicacion." - ".$IdTipo." - ".$Observa." - ".$lugar." - ".$Empleado." - ".$usuario;
			if ($objdatos->insertar_Examen($idsolicitud,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario)==true){
				echo "Examen Agregado";
			}
			else{
				echo "No se pudo Agregar el Examen";
			}
		}
		
	break;
		
}//switch

?>