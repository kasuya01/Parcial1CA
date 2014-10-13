<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsMuestrasRechazadas.php");

//variables POST
$opcion=$_POST['opcion'];
$estadosolicitud='P';
//echo $estado;

//creando los objetos de las clases
$objdatos = new clsMuestrasRechazadas;

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
	$TipoSolic=$_POST['TipoSolic'];

	$ban=0;  
        
       $query=   "WITH tbl_servicio AS (
                        SELECT t02.id,
                            CASE WHEN t02.nombre_ambiente IS NOT NULL THEN  	
                                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->' ||t02.nombre_ambiente
                                     ELSE t02.nombre_ambiente
                                END
                            ELSE
                                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'--> ' || t01.nombre
                                     WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre) THEN t01.nombre
                                END
                            END AS servicio 
                        FROM  ctl_atencion 				    t01 
                        INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                        INNER JOIN mnt_area_mod_estab 	   	    t03 ON (t03.id = t02.id_area_mod_estab)
                        LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                        LEFT  JOIN mnt_servicio_externo 		    t05 ON (t05.id = t04.id_servicio_externo)
                        WHERE id_area_atencion = 3 and t02.id_establecimiento = 49
                        ORDER BY 2)
                    SELECT sdses.id, 
                    sse.id_expediente, 
                    lcee.id,
                    nombre_examen, 
                    casd.id, 
                    casd.nombrearea, 
                    sdses.observacion, 
                    tser.servicio,
                    ce.nombre,
                    case WHEN id_expediente_referido is  null then 
                                                      ( mex.numero)
                                                       else (mer.numero) end as numero,
                    TO_CHAR(lrc.fecharecepcion, 'DD/MM/YYYY'),
                    (SELECT nombre FROM ctl_establecimiento WHERE id = sse.id_establecimiento_externo) AS nombre_establecimiento,
                    lrc.numeromuestra, 
                    case WHEN id_expediente_referido is  null  THEN 
                            CONCAT_WS(' ', pa.primer_nombre, NULL,pa.segundo_nombre,NULL,pa.primer_apellido,NULL,pa.segundo_apellido)
                            else  
                              CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)end as paciente,

                    CASE sse.idtiposolicitud WHEN 1 THEN 'URGENTE' 
                                             WHEN 2 THEN 'NORMAL' 
                                             END AS prioridad,
                    t01.nombre,
                    sse.id,
                    lcee.codigo_examen
                    from ctl_area_servicio_diagnostico casd 
                    INNER JOIN mnt_area_examen_establecimiento mnt4 	ON (mnt4.id_area_servicio_diagnostico=casd.id) 
                    INNER JOIN lab_conf_examen_estab lcee 			ON (mnt4.id=lcee.idexamen) 
                    INNER JOIN sec_detallesolicitudestudios sdses 		ON (sdses.id_conf_examen_estab=lcee.id) 
                    LEFT  JOIN sec_solicitudestudios sse 			ON (sdses.idsolicitudestudio=sse.id) 
                    INNER JOIN lab_recepcionmuestra lrc 			ON (sse.id= lrc.idsolicitudestudio) 
                    LEFT JOIN sec_historial_clinico shc 			ON (sse.id_historial_clinico=shc.id) 
                    INNER JOIN mnt_aten_area_mod_estab mnt3 			ON (shc.idsubservicio=mnt3.id) 
                    INNER JOIN mnt_area_mod_estab m1 				ON (mnt3.id_area_mod_estab=m1.id) 
                    INNER JOIN ctl_atencion ctl 				ON (mnt3.id_atencion=ctl.id)
                    INNER JOIN tbl_servicio tser                                ON (tser.id = mnt3.id AND tser.servicio IS NOT NULL)
                    INNER JOIN ctl_establecimiento ce 				ON (shc.idestablecimiento=ce.id) 
                    INNER JOIN ctl_area_atencion t01 				ON ( m1.id_area_atencion=t01.id) 
                    LEFT  JOIN mnt_dato_referencia  mdr                         on (sse.id_dato_referencia=mdr.id)
                    LEFT JOIN mnt_expediente_referido mer       		on (mdr.id_expediente_referido=mer.id)
                    LEFT JOIN mnt_paciente_referido par   			ON (mer.id_referido=par.id) 
                    INNER JOIN mnt_expediente mex 				ON (shc.id_numero_expediente=mex.id)
                    INNER JOIN mnt_paciente pa 					ON (mex.id_paciente=pa.id)
                    
                    WHERE  estadodetalle=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = 'RM')	AND sdses.idestablecimiento = $lugar AND ";




                        //'RM'
		// $estadodetalle='D';  //estado en que la muestra ha sido tomada
			/*if (!empty($_POST['IdEstab']))
			{ $query .= " shc.id ='".$_POST['IdEstab']."' AND";}	*/
				
			   if (!empty($_POST['IdEstab']))
			{ $query .= " shc.idestablecimiento ='".$_POST['IdEstab']."' AND";}	
			
		if (!empty($_POST['IdServ']))
			{ $query .= " t01.id='".$_POST['IdServ']."' AND";}
		
		if (!empty($_POST['IdSubServ']))
			{ $query .= " mnt3.id ='".$_POST['IdSubServ']."' AND";}

		if (!empty($_POST['idarea']))
			{ $query .= " id_area_servicio_diagnostico='".$_POST['idarea']."' AND";}
			
		if (!empty($_POST['idexamen']))
			{ $query .= " lcee.id='".$_POST['idexamen']."' AND";}
                        
		//case WHEN id_expediente_referido is null then (mex.numero) else
		if (!empty($_POST['idexpediente']))
			{ $query .= " case WHEN id_expediente_referido is null then    
                                (mex.numero='".$_POST['idexpediente']."') ELSE
                                    
                                (mer.numero='".$_POST['idexpediente']."') END AND";}
                        
                       
                        
		if (!empty($_POST['fecharecep']))
			{$Nfecha=explode("/",$fecharecep);
		 	//print_r($Nfecha);
                  	$Nfecharecep=$Nfecha[2]."-".$Nfecha[1]."-".$Nfecha[0]; 
			$query .= " lrc.fecharecepcion='".$Nfecharecep."' AND";}

		if (!empty($_POST['PNombre']))
			
                        
                        { $query .= " case WHEN id_expediente_referido is null then    
                                (pa.primer_nombre ilike '%".$_POST['PNombre']."%') ELSE
                                    
                                (par.primer_nombre ilike '%".$_POST['PNombre']."%') END AND";}
                        
                        
		
		if (!empty($_POST['SNombre']))
			//{ $query .= " mnt_datospaciente.SegundoNombre='".$_POST['SNombre']."' AND";}
                    
                     { $query .= " case WHEN id_expediente_referido is null then    
                                (pa.segundo_nombre ilike '%".$_POST['SNombre']."%') ELSE
                                    
                                (par.segundo_nombre ilike '%".$_POST['SNombre']."%') END AND";}
		
		if (!empty($_POST['PApellido']))
			//{ $query .= " mnt_datospaciente.PrimerApellido='".$_POST['PApellido']."' AND";}
                    
                     { $query .= " case WHEN id_expediente_referido is null then    
                                (pa.primer_apellido ilike '%".$_POST['PApellido']."%') ELSE
                                    
                                (par.primer_apellido ilike '%".$_POST['PApellido']."%') END AND";}
		
		if (!empty($_POST['SApellido']))
			//{ $query .= " mnt_datospaciente.SegundoApellido='".$_POST['SApellido']."' AND";}
                    { $query .= " case WHEN id_expediente_referido is null then    
                                (pa.segundo_apellido ilike '%".$_POST['SApellido']."%') ELSE
                                    
                                (par.segundo_apellido ilike '%".$_POST['SApellido']."%') END AND";}
			
		if (!empty($_POST['TipoSolic']))
		{ $query .= " sse.idtiposolicitud='".$_POST['TipoSolic']."' AND";}

			if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fecharecep'])) 
			AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) 
			AND (empty($_POST['idexamen'])) AND (empty($_POST['PNombre'])) AND (empty($_POST['SNombre'])) 
			AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido'])) AND (empty($_POST['TipoSolic'])))  
			{
					$ban=1;
			}
			
			if ($ban==0){
				$query = substr($query ,0,strlen($query)-3);
				$query_search = $query. " ORDER BY lrc.fecharecepcion DESC";
				}
		//echo $query_search;
		
		
		   $consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
		   $NroRegistros= $objdatos->NumeroDeRegistros($query_search);
		echo "<table width='95%' border='0' align='center'>
                        <tr>
			    <td colspan='7' align='center' ><h3><strong>TOTAL DE EXAMENES RECHAZADOS:".$NroRegistros."</strong></h3></td>
			</tr>
                      </table> "; 
		  
		echo "<table width='95%' border='1' align='center' class='StormyWeatherFormTABLE'>
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
                                    <td>Prioridad</td>
			 </tr>";    
                    $pos=0;
		 //$row = pg_fetch_array($consulta);
		 
		   while ($row = pg_fetch_array($consulta))
			{ 
		   echo "<tr>
				   <td width='8%'>".$row[12]."</td>
				   <td width='10%'>
					   <a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
					   $row[9]."</a>". 
					   "</td>". 
                                           "<input name='idsolicitud1[".$pos."]' id='idsolicitud1[".$pos."]' type='hidden' size='60' value='".$row[16]."' />".
					   "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[0]."' />".
					   "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
					   "<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
					   "<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row[2]."' />".
					   "<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' />".
				  "<td width='25%'>".$row['paciente']."</td>
				   <td width='10%'>".$row[17]."</td>
				   <td width='25%'>".htmlentities($row[3])."</td>
				   <td width='20%'>".htmlentities($row[6])."</td>
				   <td width='15%'>".htmlentities($row[7])."</td>
				   <td width='15%'>".htmlentities($row[15])."</td>
                                   <td width='20%'>".htmlentities($row[11])."</td>
				   <td width='15%'>".$row[10]."</td>
				   <td width='10%'>".($row['prioridad'])."</td>
                                      
				 </tr>";

			$pos=$pos + 1;
			}
		   echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
				</table>";
   
   break;
   
   case 2:
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		$idarea=$_POST['idarea'];
		
                
                
                
                
                
		//$establecimiento=$_POST['establecimiento'];
			//echo $idexpediente."**".$idsolicitud;
		/*if ($idarea=="URI" or $idarea=="BAT" OR $idarea=="TMU" ){  */
			$idexamen=$_POST['idexamen'];		
			include_once("clsMuestrasRechazadas.php");
			//recuperando los valores generales de la solicitud
			$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud);
			$row = pg_fetch_array($consulta);
			//obteniedo los datos generales de la solicitud
			//valores de las consultas
			$idsolicitudPadre=$row[0];
                        $medico=$row[2];
			$idmedico=$row[1];
			$paciente=$row['paciente'];
			$edad=$row['edad'];
			$sexo=$row[5];
			$precedencia=$row[13];
			$origen=$row[8];
			//$DatosClinicos=$row['DatosClinicos'];
			//$fechasolicitud=$row['FechaSolicitud'];
			//$FechaNac=$row['FechaNacimiento'];
                        $Talla=$row[11];
                        $Peso=$row[10];
                        $Diagnostico=$row[9];
                        $ConocidoPor=$row[7];
			//recuperando los valores del detalle de la solicitud
			$datosexamen=$objdatos->DatosExamen($idarea,$idsolicitud,$idexamen);//cambie esta funcion
			
			$imprimir="<form name='frmDatos'>
                            <center>
			<table width='80%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
                        </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
                                <td class='StormyWeatherDataTD' colspan='3'>".$row[3]."</td>
			</tr>
		        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($paciente)." 
                        	     <input name='txtpaciente' id='txtpaciente' type='hidden' size='' value=$paciente disabled='disabled' /></td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Conocido por</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($ConocidoPor)." 
			     		<input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'>".htmlentities($edad)." 
			     		<input name='txtpaciente' id='txtpaciente1' type='hidden' size='35' value='".$edad."' disabled='disabled' /></td>
				
                                    </div>
                                </td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='".$precedencia."' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>".htmlentities($origen)."
					<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
                                        <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='".$idsolicitudPadre."' disabled='disabled' />
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
                                <td class='StormyWeatherFieldCaptionTD'>Diagnostico</td>
                                <td colspan='3' class='StormyWeatherDataTD'>". $Diagnostico."</td>
                        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Peso</td>";
                if(!empty($Peso))
                  $imprimir .= "<td class='StormyWeatherDataTD'>".$Peso."&nbsp;&nbsp;Kg &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                else      
                  $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    
                   $imprimir .="<td class='StormyWeatherFieldCaptionTD'>Talla</td>";
                if(!empty($Talla))
                   $imprimir .="<td class='StormyWeatherDataTD'>".$Talla."&nbsp;&nbsp;mts.</td>";
                else
                  $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $imprimir .=" </tr>
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
        while($fila = pg_fetch_array($datosexamen)){
                      $imprimir .= "<tr>
                                        <td>".$fila[0]."</td>
                                        <td>".htmlentities($fila[1])."</td>	
                                        <td>".htmlentities($fila[2])."</td>";	
                         if (!empty($fila['Indicacion'])){     								
                           $imprimir .="<td>".htmlentities($fila[3])."</td>";
                          }
                         else
                           $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
                                   </tr>"; 

$pos=$pos + 1;
}

pg_free_result($datosexamen);

//echo $idexamen;
 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
			</table>
		<table align='center'>
		<tr>
			<td colspan='4' align='center'>&nbsp;

			</td>
		</tr>
		<tr>
			<center><td colspan='4' align='center'>
			VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO
			</td> </center>
		</tr>
		<tr>
			<!-- <td>Procesar Muestra</td>
			<td><select id='cmbProcesar' name='cmbProcesar' size='1'  >
					<option value='0' >--Seleccione--</option>
					<option value='S' >Si</option>
			        <option value='N' >No</option>		
			    </select> 
			</td> -->
					
				
		</tr>
		</table>
		<div id='divObservacion'  >
		
                  <table align='center' width='45%'>
		<tr>
			<td>Observacion: </td>
			<td colspan='0'>
			<textarea cols='60' rows='2' name='txtobservacion'>".htmlentities($fila['Observacion'])."</textarea>
         			</td>
                        
		</tr>
                            <td colspan='2' >
		<center>  <input type='button' name='btnProcesar'  id='btnProcesar' value='Procesar Muestra' onClick=\"ProcesarMuestra1('".$idexamen."')\"> 
                         <!--<input type='button' name='btnRechazar'  id='btnRechazar' value='Recahazar Muestra' onClick=\"RechazarMuestra1('".$idexamen."')\"> -->
		<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>  </center>
			</td>
                        
			<!--style='display:none' -->
                    
		</table>
                
                            
		</div>
                </center>
		</form>";
 

//$textoDoTextArea = $_POST['txtobservacion'];

     echo $imprimir;
	// break;
	
   break;
    	
  case 3: //procezar muestra
	$idexpediente=$_POST['idexpediente'];
	$idsolicitud=$_POST['idsolicitud'];
        $idarea=$_POST['idarea'];
	$estado=$_POST['estado'];
	$fechasolicitud=$_POST['fechasolicitud'];
	$idexamen=$_POST['idexamen'];
	$fecharecep=$_POST['fecharecep'];
        $observacion=$_POST['observacion'];
        $idsolicitudPadre=$_POST['idsolicitudPadre'];
      
    
  
  // echo $idsolicitud;
 // echo $estado;
  //echo $idexamen;
        
        
      //  asta a ca!!
        
        /*
   
     if ($idarea=="URI" or $idarea=="BAT" ){   
	   /* if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)==true)   
		{
		//actualizar en la tabla de recepcion
		  if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
			{
			 	 echo "Solicitud Procesada..";
			}
		}
		else{
			echo "Solicitud No fue cambiada de Estado..";
		}*/
	/*   }
	   else{
               */
		/*if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea)==true)   
		  {
			echo "Muestras Recibidas.";	
			if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
				{
				 //echo "Solicitud Procesada..";
				 }
				 else{
					echo "Solicitud No fue cambiada de Estado..";
				 }
		}*/
				//else{
			//echo "No se pudo actualizar";
               //--
               
            /*   if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen,$observacion)==true)   
		{
		//actualizar en la tabla de recepcion
		  if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
			{
			 	 echo "Muestra Procesada..";
                                 
                                 
			}
		}
		else{
			echo "Solicitud No fue cambiada de Estado..";
		}
               
               
		}
		//}*/
        
        
      
		if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea,$observacion)==true)   
		  {
                    echo "Muestra Procesada ";
				//CambiarEstadoSolicitudProceso3
			if($objdatos->CambiarEstadoSolicitudProceso3($idexpediente,$fechasolicitud,$estadosolicitud,$idsolicitudPadre)==true)
				{
                                     echo ", Solicitud  Fue cambiada De Estado..";
				 }
				 else{
					
				 }
		}
				//else{
			//echo "No se pudo actualizar";
		
		//}
	 break;
                
                
	 break;
	 
	  case 4:// Rechazar Muestra
			$idexpediente=$_POST['idexpediente'];
			$idsolicitud=$_POST['idsolicitud'];
			$idarea=$_POST['idarea'];
			echo "estado --> ".$estado=$_POST['estado'];
			$fechasolicitud=$_POST['fechasolicitud'];
			$idexamen=$_POST['idexamen'];
			$fecharecep=$_POST['fecharecep'];
			$observacion=$_POST['observacion'];
			if ($idarea=="URI" or $idarea=="BAT"){   
				if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)==true)   
				{
					if($objdatos->MarcarObservacionRechazado1($idsolicitud,$idexamen,$observacion)==true)
					{
						echo "Muestra Rechazadaaaaaaa";
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
		//else{
			//echo "No se pudo actualizar";
			}
		
	break;
	case 5://LLENANDO COMBO DE Examenes
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos-> ExamenesPorArea($idarea,$lugar);	
		
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
              // echo $Idtipoesta;
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
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	


   break;
}

?>