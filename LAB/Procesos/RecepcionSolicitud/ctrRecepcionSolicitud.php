<?php session_start();
include_once("Clases.php");	
include ("clsRecepcionSolicitud.php");
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
$Clases = new cls_Clases;

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$con = new ConexionBD;
$con2 = new ConexionBDLab;
 switch ($opcion) 
 {
    case 1:  //ACTUALIZA ESTADO DE LA SOLICITUD
		$idexpediente=$_POST['idexpediente'];
		$fechacita=$_POST['fechacita'];
		//$opcion=$_POST['opcion'];
		$estado=$_POST['estado'];
		$idsolicitud=$_POST['idsolicitud'];
		if($con->conectar()==true) 
		{
		$query = "UPDATE sec_solicitudestudios SET estado='$estado'
			  WHERE IdServicio='DCOLAB'AND IdEstablecimiento=$lugar AND
			  IdNumeroExp='$idexpediente' AND  IdSolicitudEstudio=$idsolicitud ";
                $query1 = "UPDATE sec_detallesolicitudestudios 
			  SET EstadoDetalle='PM'
			  WHERE IdSolicitudEstudio=$idsolicitud AND 
			  IdEstablecimiento=$lugar";
                //echo $query;
		$result = @mysql_query($query);
                $result1= @mysql_query($query1);
		 if (!$result)
			echo "N";
		else
			echo "Y";	
			//echo "case I" ;
		}
	    else{
				echo "No se conecta a la base de datos";
		  }
	break;
    case 2:  //verificar existencia de datos para los parametros de una solicitud
		$idexpediente=$_POST['idexpediente'];
		$fechacita=$_POST['fechacita'];
                $idEstablecimiento=$_POST['idEstablecimiento'];
		// echo $fechacita;
                 $Nfecha=explode("/",$fechacita);
		//print_r($Nfecha);
                 $Nfechacita=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0];
		// print_r($Nfechacita);
		if($con->conectar()==true) {
			
			$query="SELECT  count(cit_citasxserviciodeapoyo.IdSolicitudEstudio) AS numreg
				FROM cit_citasxserviciodeapoyo 
				INNER JOIN sec_solicitudestudios ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
				INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
				WHERE fecha='$Nfechacita' AND sec_solicitudestudios.IdNumeroExp='$idexpediente' 
				AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento AND sec_solicitudestudios.IdEstablecimiento=$lugar";
                     //echo $query;
                        $numreg =mysql_fetch_array(mysql_query($query));
				//echo "-".$numreg[0];	
			if ($numreg[0] == 1)
				{//verificando existencia de datos para los parametros de la busqueda
			      	$query_estado = "SELECT CASE sec_solicitudestudios.estado 
                                                    WHEN 'D' THEN 'Digitada'
                                                    WHEN 'R' then 'Recibida'
                                                    WHEN 'P' then 'En Proceso'
                                                    WHEN 'C' then 'Completa'
                                                    END AS estado 
						FROM sec_solicitudestudios 
						INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
						INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico  
						WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND fecha='$Nfechacita' AND  sec_solicitudestudios.IdEStablecimiento=$lugar 
                                                    AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento
						AND sec_solicitudestudios.IdServicio='DCOLAB'";
                                        //echo $query_estado;
				  	$result = @mysql_query($query_estado);	   
				  	$row = mysql_fetch_array($result);
				  	$estadosolicitud=$row[0];
				  	 //echo $estadosolicitud;
				  	if ($estadosolicitud == "Digitada" ) //Mostrar datos de la solicitud
						{
						//echo "La Solicitud tiene un estado:".$estadosolicitud;
						echo "D";
						//echo "CASE II";
						//echo "La Solicitud tiene un estado:".$estadosolicitud;
						}
				   	else {
					   if($estadosolicitud == "Recibida" or $estadosolicitud == "En Proceso" or $estadosolicitud == "Completa")
						{ //echo "N";
							echo "La Solicitud esta: ".$estadosolicitud;
							//echo "La Solicitud no existe";
						}
						/*else{		
							echo "La Solicitud no existe";
							}*/
					}
				}
				if ($numreg[0] > 1){
					$query_estado = "SELECT CASE sec_solicitudestudios.estado 
	 					WHEN 'D' THEN 'Digitada'
	 					WHEN 'R' then 'Recibida'
	 					WHEN 'P' then 'En Proceso'
	 					WHEN 'C' then 'Completa'
        				END AS estado 
						FROM sec_solicitudestudios 
						INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
						INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico  
						WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND fecha='$Nfechacita' AND sec_solicitudestudios.Estado='D' AND sec_solicitudestudios.IdEStablecimiento=$lugar AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento
						AND sec_solicitudestudios.IdServicio='DCOLAB'";
                                       // echo $query_estado;
				  	$result = @mysql_query($query_estado);	   
				  	$row = mysql_fetch_array($result);
				  	$estadosolicitud=$row[0];
				 	 //echo $estadosolicitud;
					if($estadosolicitud!='') {    
						if ($estadosolicitud == "Digitada" ) //Mostrar datos de la solicitud
						{
							echo "D";						
						}
						else {
							if($estadosolicitud == "Recibida" or $estadosolicitud == "En Proceso" or $estadosolicitud == "Completa")
							{ //echo "N";
								echo "La Solicitud esta: ".$estadosolicitud;
									//echo "La Solicitud no existe";
							}
						}
				
					}else{ 
					      echo "No hay solicitudes que procesar de este paciente";
				
					}
				}
				if ($numreg[0] == '0'){
					echo "La Solicitud no Existe";
				}
		}else
			echo "No se conecta a la base de datos";
	      
			   
		 
	break;
	case 4://REGISTRANDO NUEVO NUMERO DE LA MUESTRA
		$idexpediente=$_POST['idexpediente'];
		$fechacita=$_POST['fechacita'];
		//$opcion=$_POST['opcion'];
		//$estado=$_POST['estado'];
		$idsolicitud=$_POST['idsolicitud'];	
			//Asignando el Numero de Muestra y Registrando la recepcion
		if($con->conectar()==true) 
		{
			$query ="SELECT MAX(NumeroMuestra) + 1 AS NumeroMuestra 
				 FROM lab_recepcionmuestra 
				 INNER JOIN sec_solicitudestudios ON lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
				 WHERE fecharecepcion=CURRENT_DATE AND sec_solicitudestudios.IdEstablecimiento=$lugar" ;
			$result = @mysql_query($query);
		     if (!$result)
		        echo "N";
		     else{
			    $row = mysql_fetch_array($result);
		    	$numero= $row['NumeroMuestra'];
				if ($numero=="")
				{
					$numero=1;
				}
					//Registro de la recepcion
					$query_insert="INSERT INTO lab_recepcionmuestra(IdSolicitudEstudio,NumeroMuestra,FechaCita,FechaRecepcion,IdUsuarioReg,FechaHoraReg) VALUES($idsolicitud,$numero,'$fechacita',CURRENT_DATE,$usuario,NOW())";
					$result_insert = @mysql_query($query_insert);
	    		     if (!$result_insert)
			     {  echo "NN"; ; 
									    
			     }
			     else{
				 //Asignando el Numero de la muestra
				 echo "El Numero de Muestra asignado es: ".$numero;
				//echo "Case IV";
			      }
			}
		}
			
	break;
	case 5:
		$idexpediente=$_POST['idexpediente'];
		$fechacita=$_POST['fechacita'];
		$estado=$_POST['estado'];
		$idsolicitud=$_POST['idsolicitud'];
	    //CAMBIO DEL ESTADO DE LA SOLICITUD
		if($con->conectar()==true) 
		{
			   $query =  "UPDATE sec_detallesolicitudestudios SET EstadoDetalle='$estado'
			              WHERE IdSolicitudEstudio='$idsolicitud' AND IdServicio='DCOLAB'";
			     $result = @mysql_query($query);
			     if (!$result)
			       echo "OK";
				  
			     else
			       echo "FF";	   
		}
			
	break;
	/*case 6: 
			  $NEC=$_POST['NEC'];
			  $Solicitud=$_POST['Solicitud'];
			   
		  
			$IdMedico=$fila['IdEmpleado'];
			$Medico=$fila['NombreEmpleado'];
			$IdServicio=$fila['IdServicio'];
			$NomServicio=$fila['NombreServicio'];
			$Origen=$fila['NombreSubServicio'];
			$IdOrigen=$fila['IdSubServicio'];
			$Procedencia= $fila['NombreServicio'];
			$PrimerNombre=$fila['PrimerNombre'];
			$SegundoNombre=$fila['SegundoNombre'];
			$PrimerApellido=$fila['PrimerApellido'];
			$SegundoApellido=$fila['SegundoApellido'];
			$FechaNac=$fila['FechaNacimiento'];
			$Sexo=$fila['Sexo'];
			$Num=$fila['NumeroMuestra'];
			$Status="";
			$Prioridad="";
		
			$Nac=explode("-",$FechaNac);
			$NomMedico=explode("",$Medico);
			
			if ($fila['IdServicio']=="CONEXT"){
				$Prioridad='R';
				$Status='OP';
			}
			elseif (($fila['IdServicio']=="CONHOS") OR ($fila['IdServicio']=="HOSMED") OR ($fila['IdServicio']=="HOSCIR")){
				$Prioridad='R';
				$Status='IP';
			}
			elseif($IdServicio=="CONEMR"){
				$Prioridad=$IdServicio."-"."U";	
				$Status='ER';
			}
		
		 //     GENERACION DE ARCHIVO 
		 
		     $Fecha=date("d-m-Y");
			 $dia=date("d");
			 $mes=date("m");
			 $A=date("Y");
			 $seg=date("s");
			 $min=date("i");
			 $hora=date("H");
			 $todo=$dia."".$mes."".$A."".$hora."".$min."".$seg;
			 $Nombre=$NEC."_".$Solicitud."_".$Fecha;
			 $nombrearchivo = './../../../Solicitudes/'.$Nombre.'.pet';
			// "../ReportesExcel/".$NombreExcel.".xls";
		$punteroarchivo = fopen($nombrearchivo, "w+") or die.("El archivo de reporte no pudo crearse");
	
			$query1 ="select sec_detallesolicitudestudios.IdDetalleSolicitud,sec_detallesolicitudestudios.IdExamen,lab_examenes.NombreExamen, sec_detallesolicitudestudios.IdTipoMuestra,lab_tipomuestra.TipoMuestra
                                  from sec_detallesolicitudestudios 
                                  inner join lab_examenes on sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                                  INNER JOIN lab_tipomuestra on sec_detallesolicitudestudios.IdTipoMuestra=lab_tipomuestra.IdTipoMuestra
                                  where IdSolicitudEstudio='$Solicitud'order by lab_examenes.IdArea,lab_examenes.NombreExamen";
		$resultdet = @mysql_query($query1);
		//$filadet = mysql_fetch_array($resultdet);
		while($filadet = mysql_fetch_array(	$resultdet)){
			$Reporte="H|\^&|||redHNR|||||Roche||P||".$todo."|||||||||||||||||||||";
			$grabar = fwrite($punteroarchivo,$Reporte);
			$grabar =fwrite($punteroarchivo,chr(13).chr(10));
   
	 
			$Reporte1="P|".$Num."|".$NEC."|".$Solicitud."||".$PrimerNombre."^".$PrimerApellido.	
			"^".$SegundoApellido."||".$Nac[0]."".$Nac[1]."".$Nac[2]."|".$Sexo."|H|||".$IdMedico.
			"^".$Medico."^"."||||||||||".$todo."|".$Status."|||".$Prioridad."|||||".$IdServicio.		   "^".$NomServicio."|".$IdOrigen."^".$Origen."|0"; 
			$grabar = fwrite($punteroarchivo,$Reporte1);
			$grabar = fwrite($punteroarchivo,chr(13).chr(10));
		
		
			$Reporte2="O|".$Num."|".$filadet[0]."||".$filadet[1]."^".$filadet[2]."||||||A|||".	
			$filadet[3]."^".$filadet[4]."|||||||||||||||";
			$grabar = fwrite($punteroarchivo,$Reporte2);
			$grabar = fwrite($punteroarchivo,chr(13).chr(10));
		
			$Reporte3="L|1|N||||||||||||||||||||||||||||||||";		
			$grabar = fwrite($punteroarchivo,$Reporte3);	
			$grabar = fwrite($punteroarchivo,chr(13).chr(10));
			$grabar = fwrite($punteroarchivo,chr(13).chr(10));	
		}
		//CIERRE DE ARCHIVO	
			fclose($punteroarchivo);
	break;*/

	case 7:
		$NEC=$_POST['NEC'];
		$Solicitud=$_POST['Solicitud'];
	   	$Fecha=date("Y-m-d");
		//echo $Solicitud;
		if($con->conectar()==true){
			
			$query ="SELECT DISTINCT lab_recepcionmuestra.FechaRecepcion,mnt_subservicio.IdServicio,mnt_subservicio.IdSubServicio,mnt_empleados.IdEmpleado,
			mnt_expediente.IdNumeroExp, CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre) as Nombres,
			PrimerApellido,IF(SegundoApellido is not null,SegundoApellido,'-') as APELL2,FechaNacimiento,IF(Sexo=1,'M','F') AS Sexo,
			sec_detallesolicitudestudios.IdExamen, lab_examenes.CODIGOSUMI,sec_solicitudestudios.Impresiones,sec_detallesolicitudestudios.IdDetalleSolicitud,sec_detallesolicitudestudios.Indicacion,
			sec_detallesolicitudestudios.IdTipoMuestra,sec_detallesolicitudestudios.IdOrigenMuestra, lab_examenes.IdPlantilla,sec_detallesolicitudestudios.Observacion,
			sec_detallesolicitudestudios.EstadoDetalle,lab_recepcionmuestra.IdRecepcionMuestra,lab_recepcionmuestra.NumeroMuestra,sec_solicitudestudios.CAMA
			FROM sec_solicitudestudios 
			INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			INNER JOIN sec_detallesolicitudestudios ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
			INNER JOIN mnt_empleados ON sec_historial_clinico.IdEmpleado=mnt_empleados.IdEmpleado
			INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
			INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
			INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
			INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
			INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
			INNER JOIN lab_recepcionmuestra ON sec_solicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
			WHERE sec_solicitudestudios.IdSolicitudEstudio='$Solicitud'";
			
		echo $query;
$Cla="";
		$result = mysql_query($query);
			while($row = mysql_fetch_array($result)){
			
			$con2->conectarT();	
				$Cla.= $Clases->Query($Solicitud,$row['FechaRecepcion'],$row['IdServicio'],$row['IdSubServicio'],$row['IdEmpleado'],$row['IdNumeroExp'],$row['Nombres'],$row['PrimerApellido'],$row['APELL2'],$row['FechaNacimiento'],$row['Sexo'],$row['IdExamen'],$row['CODIGOSUMI'],$row['Impresiones'],$row['CAMA'],$Fecha,0,$row['IdDetalleSolicitud'],$row['Indicacion'],$row['IdTipoMuestra'],$row['IdOrigenMuestra'],$row['IdPlantilla'],$row['Observacion'],$row['EstadoDetalle'],$row['IdRecepcionMuestra'],$row['NumeroMuestra']);
	$Cla.='\n';
				}
echo $Cla;
			$con2->desconectarT();
			
			
		}
		
		break;
        case 8: //llenar combo estableciminetos
		$rslts='';

		$Idtipo=$_POST['IdTipoEstab'];
		//echo $Idtipo;
                 if($con->conectar()==true) {
			$query ="SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento where IdTipoEstablecimiento='$Idtipo' ORDER BY Nombre";
			$result = mysql_query($query);
		
			
		$rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" class="MailboxSelect" style="width:400px">';
		$rslts .='<option value="0">--Seleccione un Establecimiento--</option>';
			
		while ($rows =mysql_fetch_array($result)){
			$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
		}
		}		
		$rslts .= '</select>';
		echo $rslts;
        break;
       
}

