<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsRMAutomatizada.php");

//variables POST
//$pag =$_POST['pag'];
//$observacion=$_POST['observacion'];                       
$estadosolicitud=3;
//echo $estado;
$opcion=$_POST['opcion'];
//creando los objetos de las clases
$objdatos = new clsRMAutomatizada;

switch ($opcion) 
{
  case 1: 
	$idexpediente=$_POST['idexpediente'];
	$idsolicitud=$_POST['idsolicitud'];
	$IdEstab=$_POST['IdEstab'];
	$IdServ=$_POST['IdServ'];
	$IdSubServ=$_POST['IdSubServ'];
	$idarea=$_POST['idarea'];
	$estado=$_POST['estado'];
	$fechasolicitud=$_POST['fechasolicitud'];
	$idexamen=$_POST['idexamen'];
	$TipoSolic=$_POST['TipoSolic'];
	$ban=0;  
	$fecharecep=$_POST['fecharecep'];
         include_once("clsRMAutomatizada.php");
        $consulta=$objdatos->siesreferrido();
       if($consulta>=0){
           echo "NO HAY REFERIDO".$consulta;
       $query=    "SELECT 
sdses.id, 
sse.id_expediente, 
lcee.id,
nombre_examen,
casd.id,
casd.nombrearea, 
--DATE_FORMAT( lrc.fecharecepcion, --'%d/%m/%Y') AS fecharecepcion, 
lrc.observacion, ctl.nombre, ce.nombre,mex.numero,lrc.fecharecepcion,ce.nombre, lrc.numeromuestra,
CONCAT_WS(' ', pa.primer_nombre, NULL,pa.segundo_nombre,NULL,pa.primer_apellido,NULL,pa.segundo_apellido)AS paciente, 
CASE sse.idtiposolicitud
     WHEN 1 THEN 'URGENTE'
     WHEN 2 THEN 'NORMAL'
     END AS prioridad,
t01.nombre,sse.id
from ctl_area_servicio_diagnostico casd 
join mnt_area_examen_establecimiento mnt4     on (mnt4.id_area_servicio_diagnostico=casd.id )
join lab_conf_examen_estab lcee 	      on (mnt4.id=lcee.idexamen) 
INNER JOIN sec_detallesolicitudestudios sdses ON (sdses.id_conf_examen_estab=lcee.id)
INNER JOIN sec_solicitudestudios sse          ON (sdses.idsolicitudestudio=sse.id) 
INNER JOIN lab_recepcionmuestra lrc           ON (sse.id= lrc.idsolicitudestudio )
INNER JOIN sec_historial_clinico shc 	      ON (sse.id_historial_clinico=shc.id )
join mnt_aten_area_mod_estab mnt3	      ON (shc.idsubservicio=mnt3.id)
join mnt_area_mod_estab m1		      ON (mnt3.id_area_mod_estab=m1.id)
INNER JOIN ctl_atencion ctl 		      ON (shc.idsubservicio=ctl.id) 
INNER JOIN ctl_establecimiento ce 	      ON (shc.idestablecimiento=ce.id )
inner join ctl_area_atencion t01              on ( m1.id_area_atencion=t01.id) 
INNER JOIN mnt_expediente mex                 ON shc.id_numero_expediente=mex.id 
INNER JOIN mnt_paciente pa                    ON (mex.id_paciente=pa.id) 
WHERE estadodetalle=5 OR estadodetalle=1 AND 
lrc.fecharecepcion<=CURRENT_DATE 
AND sdses.idestablecimiento= $lugar AND  ";
           
           
       

            if (!empty($_POST['IdEstab']))
			{ $query .= " shc.idestablecimiento ='".$_POST['IdEstab']."' AND";}	
			
		if (!empty($_POST['IdServ']))
			{ $query .= " t01.id='".$_POST['IdServ']."' AND";}
		
		if (!empty($_POST['IdSubServ']))
			{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}

		if (!empty($_POST['idarea']))
			{ $query .= " id_area_servicio_diagnostico='".$_POST['idarea']."' AND";}
			
		if (!empty($_POST['idexamen']))
			{ $query .= " lcee.id='".$_POST['idexamen']."' AND";}
		
		if (!empty($_POST['idexpediente']))
			{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
		
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
			
		if (!empty($_POST['TipoSolic']))
		{ $query .= " sec_solicitudestudios.IdTipoSolicitud='".$_POST['TipoSolic']."' AND";}	
		
		if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fecharecep'])) 
		AND (empty($_POST['idexamen'])) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) 
		AND (empty($_POST['IdSubServ'])) AND (empty($_POST['idexamen'])) AND (empty($_POST['PNombre'])) 
		AND (empty($_POST['SNombre'])) AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido']))
		AND (empty($_POST['TipoSolic'])))
		{
				$ban=1;
		}
		
		if ($ban==0){
			
			$query = substr($query ,0,strlen($query)-3);
			echo  $query_search = $query. " ORDER BY lrc.fecharecepcion DESC";
			
		}
           
                
                
          
        }else if ($consulta>=0) {
            echo "SI HAY REFERIDO".$consulta;
            
            $query=    "SELECT 
sdses.id, 
sse.id_expediente, 
lcee.id,
nombre_examen,
casd.id,
casd.nombrearea, 
--DATE_FORMAT( lrc.fecharecepcion, --'%d/%m/%Y') AS fecharecepcion, 
lrc.observacion, ctl.nombre, ce.nombre,mer.numero,lrc.fecharecepcion,ce.nombre, lrc.numeromuestra,
CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)AS paciente, 
CASE sse.idtiposolicitud
     WHEN 1 THEN 'URGENTE'
     WHEN 2 THEN 'NORMAL'
     END AS prioridad,
t01.nombre
from ctl_area_servicio_diagnostico casd 
join mnt_area_examen_establecimiento mnt4     on (mnt4.id_area_servicio_diagnostico=casd.id )
join lab_conf_examen_estab lcee 	      on (mnt4.id=lcee.idexamen) 
INNER JOIN sec_detallesolicitudestudios sdses ON (sdses.id_conf_examen_estab=lcee.id)
INNER JOIN sec_solicitudestudios sse          ON (sdses.idsolicitudestudio=sse.id) 
left JOIN lab_recepcionmuestra lrc            ON (sse.id= lrc.idsolicitudestudio )
left JOIN sec_historial_clinico shc 	      ON (sse.id_historial_clinico=shc.id )
join mnt_aten_area_mod_estab mnt3	      ON (shc.idsubservicio=mnt3.id)
join mnt_area_mod_estab m1		      ON (mnt3.id_area_mod_estab=m1.id)
INNER JOIN ctl_atencion ctl 		      ON (shc.idsubservicio=ctl.id) 
INNER JOIN ctl_establecimiento ce 	      ON (shc.idestablecimiento=ce.id )
inner join ctl_area_atencion t01              on ( m1.id_area_atencion=t01.id) 
--INNER JOIN mnt_expediente mex               ON shc.id_numero_expediente=mex.id 
--INNER JOIN mnt_paciente pa                  ON (mex.id_paciente=pa.id) 
JOIN mnt_expediente_referido mer    	      on (sse.id_expediente_referido=mer.id)
INNER JOIN mnt_paciente_referido par          ON (mer.id_referido=par.id) 
WHERE estadodetalle=5 OR estadodetalle=1 AND
lrc.fecharecepcion<=CURRENT_DATE 
AND sdses.idestablecimiento= $lugar AND  ";
            
             
           if (!empty($_POST['IdEstab']))
			{ $query .= " shc.idestablecimiento ='".$_POST['IdEstab']."' AND";}	
			
		if (!empty($_POST['IdServ']))
			{ $query .= " t01.id='".$_POST['IdServ']."' AND";}
		
		if (!empty($_POST['IdSubServ']))
			{ $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}

		if (!empty($_POST['idarea']))
			{ $query .= " id_area_servicio_diagnostico='".$_POST['idarea']."' AND";}
			
		if (!empty($_POST['idexamen']))
			{ $query .= " lcee.id='".$_POST['idexamen']."' AND";}
		
		if (!empty($_POST['idexpediente']))
			{ $query .= " sec_solicitudestudios.IdNumeroExp='".$_POST['idexpediente']."' AND";}
		
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
			
		if (!empty($_POST['TipoSolic']))
		{ $query .= " sec_solicitudestudios.IdTipoSolicitud='".$_POST['TipoSolic']."' AND";}	
		
		if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fecharecep'])) 
		AND (empty($_POST['idexamen'])) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) 
		AND (empty($_POST['IdSubServ'])) AND (empty($_POST['idexamen'])) AND (empty($_POST['PNombre'])) 
		AND (empty($_POST['SNombre'])) AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido']))
		AND (empty($_POST['TipoSolic'])))
		{
				$ban=1;
		}
		
		if ($ban==0){
			
			$query = substr($query ,0,strlen($query)-3);
			ECHO  $query_search = $query. " ORDER BY lrc.fecharecepcion DESC";
			
		}
           
        }
        
        else if($consulta<=0 ) {
            
            
        }
	 


	
		
	//echo $query_search;
	
	
	$consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
	$NroRegistros= $objdatos->NumeroDeRegistros($query_search);
  echo "<table width='92%' border='0' align='center'>
        	<tr>
			<td colspan='7' align='center' ><h3><strong>TOTAL DE EXAMENES RECHAZADOS:".$NroRegistros."</strong></h3></td>
		</tr>
		<tr>
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
                            <td width='7%'>".$row[12]."</td>
                            <td width='8%'>
                               	<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
				   	$row[9]."</a>". 
                           "</td>". 
				  	 "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[0]."' />".
				  	 "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
				   	 "<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
				   	 "<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row[2]."' />".
                           "<td width='20%'>".$row['paciente']."</td>
                            <td width='10%'>".$row[2]."</td>
                            <td width='22%'>".htmlentities($row[3])."</td>
                            <td width='18%'>".htmlentities($row[6])."</td>
                            <td width='15%'>".htmlentities($row[7])."</td>
                            <td width='10%'>".htmlentities($row[15])."</td>
                            <td width='20%'>".htmlentities($row[11])."</td>
                            <td width='20%'>".$row[10]."</td>
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
            $idexamen=$_POST['idexamen'];
	
	//	echo $idarea."**".$idsolicitud."**".$idexamen."**".$idexpediente;
		 //echo $idexamen;
            include_once("clsRMAutomatizada.php");
		//recuperando los valores generales de la solicitud
          
            
            
		$consulta=$objdatos->DatosGeneralesSolicitud($idsolicitud);
			$row = pg_fetch_array($consulta);
			//obteniedo los datos generales de la solicitud
			//valores de las consultas
                      echo "e-->". $idsolicitudPadre=$row[0];
			$medico=$row[3];
			$idmedico=$row[1];
			$paciente=$row[5];
			$edad=$row['edad'];
			$sexo=$row[12];
			$precedencia=$row[13];
			$origen=$row[7];
			//$DatosClinicos=$row['DatosClinicos'];
			//$fechasolicitud=$row['FechaSolicitud'];
			//$FechaNac=$row['FechaNacimiento'];
                        $Talla=$row[10];
                        $Peso=$row[9];
                        $Diagnostico=$row[8];
                        $ConocidoPor=$row[6];
		//recuperando los valores del detalle de la solicitud
		$datosexamen=$objdatos->DatosExamen($idarea,$idsolicitud,$idexamen);//cambie esta funcion
		$imprimir="<form name='frmDatos'>
                    <table width='65%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		   	</tr>
			
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($paciente)." 
				     <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		    	</tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Conocido por</td>
				<td colspan='3' class='StormyWeatherDataTD'>".htmlentities($ConocidoPor)." </td>
		    	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'><div id='divsuedad'>
          
    				</div></td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>
				$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
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
                                <td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;". $Diagnostico."</td>
                        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Peso</td>";
                  if(!empty($Peso))
                  $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".$Peso."&nbsp;&nbsp;Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                  else      
                  $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                    
                   $imprimir .="<td class='StormyWeatherFieldCaptionTD'>Talla</td>";
                  if(!empty($Talla))
                   $imprimir .="<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".$Talla."&nbsp;&nbsp;mts.</td>";
                  else
                  $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
           $imprimir .="</tr>
	   </table>
	<br>

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
                                        /*$imprimir.="<tr>
						<td>".$fila['IdExamen']."</td>
						<td>".htmlentities($fila['NombreExamen'])."</td>	
						<td>".htmlentities($fila['TipoMuestra'])."</td>";	
					if (!empty($fila['Indicacion'])){     						
					   $imprimir .="<td>".htmlentities($fila['Indicacion'])."</td>";
						}
					else*/
                                                    
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
				</table><br>
			
				<table align='center' width='55%'>
                                    <tr>
                                            <td colspan='4' align='center'>VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO</td>
                                    </tr>
                                    <tr>
                                            <td>Observacion</td>
                                            <td colspan='3'>
                                                    <textarea cols='60' rows='2' name='txtobservacion'>".htmlentities($fila['Observacion'])."</textarea>
                                            </td>
                                    </tr>
                                    <tr>
                                            <td colspan='4' align='center'><input type='button' name='btnRechazar'  id='btnRechazar' value='Rechazar Muestraaaa' onClick=\"RechazarMuestra1('".$idexamen."')\">
                                                    <input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>
                                            </td>
                                    </tr>
                                </table>
		</form>";
     echo $imprimir;
	   break;
    	
   case 3:
		$idexpediente=$_POST['idexpediente'];
		echo $idsolicitud=$_POST['idsolicitud'];
		$estado=$_POST['estado'];
		$fechasolicitud=$_POST['fechasolicitud'];
		$idexamen=$_POST['idexamen'];
		$observacion='';
	  /*  if (($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen,$observacion)==true)&&($objdatos->CambiarEstadoSolicitud($idsolicitud)==true))
		{  echo "Solicitud Procesada..";
		//actualizar en la tabla de recepcion
		//  if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
			/*if($objdatos->CambiarEstadoSolicitud($idsolicitud)==true)
			{
			 	 echo "Solicitud Procesada..";
			}*/
		/*}
		else{
			echo "Solicitud No fue cambiada de Estado..";
		}*/
	 
	 break;
	 case 4:// Rechazar Muestra
		$idsolicitud=$_POST['idsolicitud'];
		$estado=$_POST['estado'];
		$idexamen=$_POST['idexamen'];
                $observacion=$_POST['observacion'];
		$idsolicitudPadre=$_POST['idsolicitudPadre'];
		
		//echo "Sol=".$idsolicitud." examen=".$idexamen." obser=".$observacion;
			
			
		if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen,$observacion)==true)
		{  
                        echo "Muestra Rechazada";
                        
			if($objdatos->CambiarEstadoSolicitud($idsolicitud,$idsolicitudPadre)==true)  
			{	
				echo "El Estado Solicitud fue Modificado";
			/*if($objdatos->MarcarObservacionRechazado1($idsolicitud,$idexamen,$observacion)==true)
				{
					echo "Muestra Rechazada";
				} */
			}
		}
		
	break;
	case 5:  //LLENAR COMBO DE EXAMENES  
		$rslts='';
		
		$idarea=$_POST['idarea'];
		//echo $IdSubEsp;
		$dtExam=$objdatos->ExamenesPorArea($idarea,$lugar);	
		
		$rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:270px">';
		$rslts .='<option value="0">--Seleccione Examen--</option>';
			
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
              	$rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:355px">';
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
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:355px">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	

 

}

?>