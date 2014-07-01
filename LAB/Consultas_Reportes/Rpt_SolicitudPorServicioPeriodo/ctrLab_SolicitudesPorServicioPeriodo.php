<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsSolicitudesPorServicioPeriodo.php");

//variables POST

$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsSolicitudesPorServicioPeriodo;


switch ($opcion) 
{
  case 1:  
    $IdEstab=$_POST['IdEstab'];
	$IdServ=$_POST['IdServ'];
 	$IdSubServ=$_POST['IdSubServ'];
	$fechainicio=$_POST['fechainicio'];
	$fechafin=$_POST['fechafin'];
	$medico=$_POST['medico'];
		
	$pag=$_POST['pag'];
	$registros = 20;
	$pag =$_POST['pag'];
	$inicio = ($pag-1) * $registros;

   	$query = "SELECT  sec_historial_clinico.IdNumeroExp, 
	sec_solicitudestudios.IdSolicitudEstudio,
	DATE_FORMAT(sec_solicitudestudios.FechaSolicitud ,'%e/ %m / %Y') AS FechaSolicitud,
	mnt_subservicio.NombreSubServicio AS origen, mnt_servicio.NombreServicio AS procedencia,
	mnt_empleados.NombreEmpleado AS medico, 
	CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,
	CASE sec_solicitudestudios.Estado 
		WHEN 'D' THEN 'Digitada'
		WHEN 'R' THEN 'Recibida'
		WHEN 'P' THEN 'En Proceso'    
		WHEN 'C' THEN 'Completa' END AS Estado,
	mnt_establecimiento.Nombre
	FROM sec_historial_clinico 
	INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio 
	INNER JOIN mnt_empleados ON sec_historial_clinico.IdEmpleado= mnt_empleados.IdEmpleado
	INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp= mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente= mnt_datospaciente.IdPaciente
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento= mnt_establecimiento.IdEstablecimiento
	WHERE  sec_solicitudestudios.IdServicio ='DCOLAB' 
        AND sec_detallesolicitudestudios.IdEstablecimiento=$lugar AND";
		$ban=0;
	//VERIFICANDO LOS POST ENVIADOS
	
	if (!empty($_POST['IdEstab']))
		{ $query .= " sec_historial_clinico.IdEstablecimiento ='".$_POST['IdEstab']."' AND";}	
			
	if (!empty($_POST['IdServ']))
		{ $query .= " mnt_subservicio.IdServicio ='".$_POST['IdServ']."' AND";}
		
	if (!empty($_POST['IdSubServ']))
	{ $query .= " sec_historial_clinico.IdSubservicio='".$_POST['IdSubServ']."' AND";}
		
	if (!empty($_POST['medico']))
		{ $query .= " sec_historial_clinico.IdEmpleado='".$_POST['medico']."' AND";}
	
	if ((!empty($_POST['fechainicio'])) and (!empty($_POST['fechafin'])))
	{ $Nfechaini=explode("/",$fechainicio);
	  $Nfechafin=explode("/",$fechafin);
		 	//print_r($Nfecha);
        $Nfechaini=$Nfechaini[2]."-".$Nfechaini[1]."-".$Nfechaini[0]; 
		$Nfechafin=$Nfechafin[2]."-".$Nfechafin[1]."-".$Nfechafin[0]; 
		$query .= " sec_solicitudestudios.FechaSolicitud BETWEEN '".$Nfechaini."' AND '".$Nfechafin."' ";}

	if((empty($_POST['especialidad'])) and (empty($_POST['medico'])) and (empty($_POST['fechainicio'])) 
	and (empty($_POST['fechafin'])) and (empty($_POST['IdEstab'])) and (empty($_POST['IdServ'])))
	{
		$ban=1;
	}
			
	if ($ban==0)
	{   $query = substr($query ,0,strlen($query)-1);
		$query_search = $query. " ORDER BY PrimerApellido";
	}
		
		
		//echo $query_search;
        $consulta=$objdatos->BuscarSolicitudesEspecialidad($query_search); 

        /*  ----------Datos para  Pacgianción----------------*/
	$RegistrosAMostrar=20;
	$RegistrosAEmpezar=($_POST['pag']-1)*$RegistrosAMostrar;
	$PagAct=$_POST['pag'];
				
	$consulta=$objdatos->consultarpag($query_search,$RegistrosAEmpezar,$RegistrosAMostrar);
	$NroRegistros= $objdatos->NumeroDeRegistros($query_search);
				
 $imprimir="<table width='92%' border='0' align='center'>
	            <tr>
					<td colspan='7' align='center' ><h3><strong>TOTAL DE SOLICITUDES:".$NroRegistros."</strong></h3></td>
		    	</tr>
		    	<tr>
		    		 <td colspan='7' align='center' style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>			 
		  	    </tr>
	        </table> "; 
$imprimir.="<table width='95%' border='1' align='center'>
                <tr class='CobaltFieldCaptionTD'>
					<td>Fecha Solicitud </td>
					<td>NEC</td>
					<td>Nombre Paciente</td>
					<td>Medico</td>
					<td>Origen</td>
					<td>Procedencia</td>
					<td>Establecimiento</td>
					<td>Estado Solicitud</td>
	    	   </tr>";    
 	$pos=0;
        while ($row = mysql_fetch_array($consulta))
	{ 
    $imprimir .="<tr>
					<td width='%'>".$row['FechaSolicitud']."</td>
					<td width='7%'>".$row['IdNumeroExp']."</td>". 
						"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
						"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
					"<td width='20%'>".$row['NombrePaciente']."</td>
			    	 <td width='18%'>".htmlentities($row['medico'])."</td>
			    	 <td width='10%'>".htmlentities($row['origen'])."</td>
			    	 <td width='10%'>".htmlentities($row['procedencia'])."</td>
			    	 <td width='15%'>".htmlentities($row['Nombre'])."</td>	
			    	 <td width='10%'>".$row['Estado']."</td>
	            </tr>";

	$pos=$pos + 1;
	}
	
	mysql_free_result($consulta);
	
   	$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
   
	            </table>";
    
	echo $imprimir;
	//determinando el numero de paginas
	$PagAnt=$PagAct-1;
	$PagSig=$PagAct+1;
				 
	$PagUlt=$NroRegistros/$RegistrosAMostrar;
				 
	//verificamos residuo para ver si llevar� decimales
	$Res=$NroRegistros%$RegistrosAMostrar;
	//si hay residuo usamos funcion floor para que me
	//devuelva la parte entera, SIN REDONDEAR, y le sumamos
	//una unidad para obtener la ultima pagina
	 if($Res>0) $PagUlt=floor($PagUlt)+1;
	    echo "<table align='center'>
		       <tr>
				<td colspan=3 align='center'> <strong>Pagina ".$PagAct."/".$PagUlt."</strong> </td>
	               </tr>
		       <tr>
				<td><a onclick=\"BuscarDatos('1')\">Primero</a> </td>";
				//// desplazamiento

	if($PagAct>1) 
	 		 echo "<td> <a onclick=\"BuscarDatos('$PagAnt')\">Anterior</a> </td>";
	 if($PagAct<$PagUlt)  
			 echo "<td> <a onclick=\"BuscarDatos('$PagSig')\">Siguiente</a> </td>";
		 	 echo "<td> <a onclick=\"BuscarDatos('$PagUlt')\">Ultimo</a></td></tr>
                 </table>";
	   echo "<table align='center'>
			<tr align='center'><td  colspan='2' width='25%'>";
		 $numPags ='';
			 for ($i=1 ; $i<=$PagUlt; $i++){
				 if ($pag == $i)
					 $numPags .= "<a >$pag</a>";
							
				 else
					 $numPags .= "<a  href='javascript: BuscarDatos(".$i.")'>$i</a>&nbsp;";
			 }
				 echo $numPags."</td></tr>
		</table>";

	break;
    	
	case 2:  // solicitud estudios
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		include_once("clsSolicitudesPorServicioPeriodo.php");
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
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud);
		$imprimir="<form name='frmDatos'>
    		<table width='70%' border='0' align='center'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'><h3><strong>DATOS SOLICITUD</strong></h3></td>
			</tr>
			<tr>
				<td>Procedencia: </td>
				<td>".$precedencia."</td>
				<td>Origen: </td>
				<td>".$origen."
					<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
					<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
				</td>
			</tr>
			<tr>
	    			<td colspan='4'></td>
	    			
			</tr>
			<tr>
		    		<td>Medico: </td>
		    		<td colspan='3'>".$medico."</td>
			</tr>
			<tr>
	    			<td>Paciente: </td>
		   		<td colspan='3'>".$paciente."</td>
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
			<tr>
				<p></p>
			</tr>
			<tr>
				<td>
					<input type='button' name='btnImprimir' id='btnImprimir'  value='Imprimir Reporte' onClick='Imprimir()'>
					<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'></td>
					</td>
				</tr>
				</table>
			</div>
			</form>";
     echo $imprimir;
	 
	 
   	break;
	case 3://LLENANDO COMBO DE EMPLEADOS
                 
		$rslts='';
		$IdSubServicio=$_POST['idsubservicio'];
		$dttipo=$objdatos->ObtenerServicio($IdSubServicio);
               	$row=mysql_fetch_array($dttipo);
                $Servicio=$row[0];
		//echo $IdSubServicio;
                if ($Servicio=='EXTREF' OR $Servicio=='CONEXT'){
			$dtMed=$objdatos->LlenarMedico($IdSubServicio,$lugar);	
		
			$rslts = '<select name="cboMedicos" id="cboMedicos" class="MailboxSelect" style="width:250px">';
			$rslts .='<option value="0">--Seleccione Medico--</option>';
			
			while ($rows =mysql_fetch_array($dtMed)){
			$rslts.= '<option value="' . $rows[1] .'" >'. htmlentities($rows[0]).'</option>';
			}
				
			$rslts .= '</select>';
			echo $rslts;
                }
		else{
			$dtmed=$objdatos->LlenarCmbMedicos($lugar);
			$rslts = '<select name="cmbMedico" id="cmbMedico"  style="width:350px">';
				$rslts .='<option value="0">--Seleccione un Servicio--</option>';
				while ($rows =mysql_fetch_array($dtmed)){
					$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
				}
			$rslts .='</select>';
			echo $rslts;

		}
	
	 
	 
	 
	
   break;	
   case 4:// Vista Previa Reporte 
   //echo $medico."+".$IdSubEsp."+".$especialidad."-".$fechainicio."-".$fechafin;
	$especialidad=$_POST['especialidad'];
	$fechainicio=$_POST['fechainicio'];
	$fechafin=$_POST['fechafin'];
	$medico=$_POST['medico'];
    	$query = "SELECT  sec_historial_clinico.IdNumeroExp,sec_solicitudestudios.IdSolicitudEstudio,
	      DATE_FORMAT(sec_solicitudestudios.FechaSolicitud ,'%e/ %m / %Y') AS FechaSolicitud,mnt_subservicio.NombreSubServicio AS origen, mnt_servicio.NombreServicio AS procedencia,mnt_empleados.NombreEmpleado AS medico, 
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
		
		
		//	ECHO $query_search;
             $consulta1=$objdatos->BuscarSolicitudesEspecialidad($query_search); 
			 
			$row1 = mysql_fetch_array($consulta1);
  	$imprimir=" <table width='90%' border='0' align='center'>
		    <tr>
			<td colspan='7' align='center'><h3><strong>REPORTE DE SOLICITUDES POR ESPECIALIDAD
				</h3></strong></td>
		   </tr>
		   <tr>
			<td colspan='7' align='center'><h3><strong>".htmlentities($row1['procedencia'])."</h3></strong></td>
			</td>
		   </tr>
		   <tr>
			<td colspan='7' align='center'><h3><strong>".htmlentities($row1['origen'])."</h3></strong></td>
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
        break;
	case 6:// Llenar Combo Establecimiento
		$rslts='';
		$Idtipoesta=$_POST['idtipoesta'];
              // echo $Idtipoesta;
            	$dtIdEstab=$objdatos->LlenarCmbEstablecimiento($Idtipoesta);
              	$rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:375px" >';
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
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px" onChange="BuscarMedicos(this.value)">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =mysql_fetch_array($dtserv)){
		  	$rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	

 }