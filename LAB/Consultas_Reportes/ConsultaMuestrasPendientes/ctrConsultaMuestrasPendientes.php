<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsConsultaMuestrasPendientes.php");

//variables POST
$opcion=$_POST['opcion'];

$estadosolicitud="P";

//creando los objetos de las clases
$objdatos = new clsConsultaMuestrasPendientes;

switch ($opcion) 
{
  /*case 1: 
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
	 $query=/*"SELECT sec_solicitudestudios.IdSolicitudEstudio,NumeroMuestra,sec_solicitudestudios.IdNumeroExp, lab_examenes.idexamen,
	nombreexamen,Indicacion,DATE_FORMAT(lab_recepcionmuestra.FechaRecepcion,'%e/ %m / %Y') AS FechaRecepcion,mnt_subservicio.NombreSubServicio,mnt_servicio.NombreServicio,
	mnt_establecimiento.Nombre,CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente
	FROM sec_detallesolicitudestudios  
	INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
	INNER JOIN lab_recepcionmuestra ON sec_detallesolicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio 
	INNER JOIN lab_examenes   ON sec_detallesolicitudestudios.IdExamen= lab_examenes.IdExamen
	INNER JOIN lab_areas 	ON  lab_examenes.IdArea=lab_areas.IdArea
	INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
	INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
	WHERE estadodetalle='D' AND  lab_recepcionmuestra.FechaRecepcion<CURRENT_DATE AND";*/
                 
                /* "WITH tbl_servicio AS (
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
                    
                    WHERE  estadodetalle=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = 'D')	AND sdses.idestablecimiento = $lugar AND ";




	// $estadodetalle='D';  //estado en que la muestra ha sido tomada
		/*if (!empty($_POST['IdEstab']))
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
		/*if (!empty($_POST['fecharecep']))
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
		{ $query .= " mnt_datospaciente.SegundoApellido='".$_POST['SApellido']."' AND";}*/
        /* if (!empty($_POST['IdEstab']))
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
		
		if((empty($_POST['idexpediente'])) AND (empty($_POST['idarea'])) AND (empty($_POST['fecharecep'])) AND (empty($_POST['IdEstab'])) AND (empty($_POST['IdServ'])) AND (empty($_POST['IdSubServ'])) AND (empty($_POST['PNombre'])) AND (empty($_POST['SNombre'])) AND (empty($_POST['PApellido'])) AND (empty($_POST['SApellido'])) AND (empty($_POST['idexamen'])))
		{
				$ban=1;
		}*/
		
		/*if ($ban==0){
			
			$query = substr($query ,0,strlen($query)-3);
			$query_search = $query."ORDER BY lrc.fecharecepcion DESC";//" ORDER BY lab_recepcionmuestra.FechaRecepcion DESC";
			
		}
	//echo $query_search;
	
	  $consulta=$objdatos->ListadoSolicitudesPorArea($query_search);  
	   $NroRegistros= $objdatos->NumeroDeRegistros($query_search);
  echo "<table width='100%' border='0' align='center'>
            	<tr>
		    <td colspan='7' align='center' ><h3><strong>TOTAL DE EXAMENES PENDIENTES DE PROCESAR:".$NroRegistros."</strong></h3></td>
		</tr>
		<tr>
			<td colspan='7' style='color:#990000' align='center'>
			<a style ='text-decoration:underline;cursor:pointer; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a>
			</td>	
	 	</tr>
	</table> "; 
  echo "<table width='90%' border='1' align='center' class='StormyWeatherFormTABLE'>
 	        <tr class='CobaltFieldCaptionTD'>
	     		<td>Muestra </td>
	                <td>NEC </td>
			<td>Nombre Paciente</td>
			<td>Id Examen</td>
			<td>Examen</td>
			<td>Servicio</td>
			<td>Procedencia</td>
			<td>Establecimiento</td>
			<td>Fecha Recepci&oacute;n</td>
		</tr>";    
	 $pos=0;
	 //$row = pg_fetch_array($consulta);
	 
	 /*  while ($row = pg_fetch_array($consulta))
		{ 
	  echo "<tr>
			<td width='5%'>".$row['NumeroMuestra']."</td>
			<td width='7%'>".$row['IdNumeroExp']."</td>
			<td width='20%'>".htmlentities($row['NombrePaciente'])."</td>". 
				"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row["IdSolicitudEstudio"]."' />".
			   	"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row["IdNumeroExp"]."' />".
			   	"<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
			   	"<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['idexamen']."' />".
			"<td width='8%'>".$row['idexamen']."</td>
			 <td width='15%'>".htmlentities($row['nombreexamen'])."</td>
			 <td width='10%'>".htmlentities($row['NombreSubServicio'])."</td>
			 <td width='10%'>".htmlentities($row['NombreServicio'])."</td>
			 <td width='15%'>".htmlentities($row['Nombre'])."</td>
			 <td width='10%'>".$row['FechaRecepcion']."</td>
		</tr>";*/
        /* while ($row = pg_fetch_array($consulta))
			{ 
		   echo "<tr>
				   <td width='8%'>".$row[12]."</td>
				   <td width='10%'>".$row[9]."</td>".
					   
					   
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
				  <!-- <td width='20%'>".htmlentities($row[6])."</td>-->
				   <td width='15%'>".htmlentities($row[7])."</td>
				   <td width='15%'>".htmlentities($row[15])."</td> 
                                   <td width='20%'>".htmlentities($row[11])."</td>
				   <td width='15%'>".$row[10]."</td>
				  <!-- <td width='10%'>".($row['prioridad'])."</td> -->
                                      
				 </tr>";

		$pos=$pos + 1;
		}
		pg_free_result($consulta);
	   echo "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
	</table>";
   
   break;*/
    
    case 1:
        $ban = 0;
        $IdEstab        = $_POST['IdEstab'];
        $IdServ         = $_POST['IdServ'];
        $IdSubServ      = $_POST['IdSubServ'];
        $idarea         = $_POST['idarea'];
        $idexamen       = $_POST['idexamen'];
        $idexpediente   = $_POST['idexpediente'];
        $fechasolicitud = $_POST['fechasolicitud'];
        $fecharecepcion = $_POST['fecharecepcion'];
        $PNombre        = $_POST['PNombre'];
        $SNomre         = $_POST['SNombre'];
        $PApellido      = $_POST['PApellido'];
        $SApellido      = $_POST['SApellido'];
        $TipoSolic      = $_POST['TipoSolic'];
        $cond1="";
        $cond2="";
        $query="";
        $query2="";
        $where_with="";
      //  echo $IdEstab." - ".$lugar;
        if (!empty($_POST['IdEstab'])) {
           if ($_POST['IdEstab']<>$lugar){
               $cond1 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
               $cond2 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
           }
          
        }

        if (!empty($_POST['IdServ'])) {
            $cond1 .= " t13.id  = " . $_POST['IdServ'] . " AND";
            $cond2 .= " t13.id  = " . $_POST['IdServ'] . " AND";
            $where_with = "id_area_atencion = $IdServ AND ";
        }

        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
            $cond2 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
        }

        if (!empty($_POST['idarea'])) {
            $cond1 .= " t08.id = " . $_POST['idarea'] . " AND";
            $cond2 .= " t08.id = " . $_POST['idarea'] . " AND";
        }

        if (!empty($_POST['idexpediente'])) {
            $cond1 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
            $cond2 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
        }

        if (!empty($_POST['idexamen'])) {
             $cond1 .= " t04.id = " . $_POST['idexamen'] . " AND";
             $cond2 .= " t04.id = " . $_POST['idexamen'] . " AND";
        }

        if (!empty($_POST['fechasolicitud'])) {
             $cond1 .= " t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' AND";
             $cond2 .= " t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' AND";
        }

        if (!empty($_POST['fecharecepcion'])) {
             $cond1 .= " t03.fecharecepcion = '" . $_POST['fecharecepcion'] . "' AND";
             $cond2 .= " t03.fecharecepcion = '" . $_POST['fecharecepcion'] . "' AND";
        }

        if (!empty($_POST['PNombre'])) {
            $cond1 .= " t07.primer_nombre ILIKE '" . $_POST['PNombre'] . "%' AND";
            $cond2 .= " t07.primer_nombre ILIKE '" . $_POST['PNombre'] . "%' AND";
        }

        if (!empty($_POST['SNombre'])) {
            $cond1 .= " t07.segundo_nombre ILIKE '" . $_POST['SNombre'] . "%' AND";
            $cond2 .= " t07.segundo_nombre ILIKE '" . $_POST['SNombre'] . "%' AND";
        }

        if (!empty($_POST['PApellido'])) {
            $cond1 .= " t07.primer_apellido ILIKE '" . $_POST['PApellido'] . "%' AND";
            $cond2 .= " t07.primer_apellido ILIKE '" . $_POST['PApellido'] . "%' AND";
        }

        if (!empty($_POST['SApellido'])) {
            $cond1 .= " t07.segundo_apellido ILIKE '" . $_POST['SApellido'] . "%' AND";
            $cond2 .= " t07.segundo_apellido ILIKE '" . $_POST['SApellido'] . "%' AND";
        }

        if (!empty($_POST['TipoSolic'])) {
            $cond1 .= " t17.idtiposolicitud = '" . $_POST['TipoSolic'] . "' AND";
            $cond2 .= " t17.idtiposolicitud = '" . $_POST['TipoSolic'] . "' AND";
        }

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['idarea'])) AND ( empty($_POST['fechasolicitud']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['PNombre'])) AND ( empty($_POST['SNombre'])) AND ( empty($_POST['PApellido']))
                AND ( empty($_POST['SApellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {
            $ban = 1;
        }
        
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            
          //  echo $query1;
           // $query_search = 
        }     
       // echo $cond2;
         $query="WITH tbl_servicio AS (
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
                    FROM  ctl_atencion                  t01 
                    INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                    INNER JOIN mnt_area_mod_estab           t03 ON (t03.id = t02.id_area_mod_estab)
                    LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                    LEFT  JOIN mnt_servicio_externo             t05 ON (t05.id = t04.id_servicio_externo)
                    WHERE $where_with t02.id_establecimiento = $lugar
                    ORDER BY 2)
            
                    SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t01.id ,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla, 
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra, 
                       t06.numero AS idnumeroexp, 
                       t03.id AS idrecepcionmuestra, 
                       t04.codigo_examen AS idexamen, 
                       t04.nombre_examen AS nombreexamen, 
                       t01.indicacion, t08.nombrearea, 
                       CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                       t07.segundo_apellido,t07.apellido_casada) AS paciente,
                       t20.servicio AS nombresubservicio,
                       t13.nombre AS nombreservicio, 
                       t02.impresiones, 
                       t14.nombre, 
                       t09.id AS idhistorialclinico,
                       TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                       t17.tiposolicitud AS prioridad, 
                       t07.fecha_nacimiento AS fechanacimiento, 
                       t19.nombre AS sexo, 
                       t18.idestandar,
                       t02.id_establecimiento_externo,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                        t01.observacion
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02                ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03                 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04                ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05      ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06                       ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07                         ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08        ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09                ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10              ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11                         ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12                   ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13                    ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14                  ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15            ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16      ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17                    ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18      ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19                             ON (t19.id = t07.id_sexo)
            INNER JOIN tbl_servicio t20                         ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t16.idestado = 'D')  
            AND t02.id_establecimiento = $lugar
            AND $cond1
        
            UNION

            SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                   t01.id ,
                   t02.id AS idsolicitudestudio,
                   t04.idplantilla, 
                   t01.id AS iddetallesolicitud,
                   t03.numeromuestra,
                   t06.numero AS idnumeroexp,
                   t03.id AS idrecepcionmuestra,
                   t04.codigo_examen AS idexamen,
                   t04.nombre_examen AS nombreexamen,
                   t01.indicacion, t08.nombrearea,
                   CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                   t07.apellido_casada) AS paciente, 
                   t11.nombre AS nombresubservicio, 
                   t13.nombre AS nombreservicio, 
                   t02.impresiones, 
                   t14.nombre,
                   t09.id AS idhistorialclinico, 
                   TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                   t17.tiposolicitud AS prioridad, 
                   t07.fecha_nacimiento AS fechanacimiento, 
                   t19.nombre AS sexo, 
                   t18.idestandar,
                   t02.id_establecimiento_externo,
                   (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            t01.observacion
                FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02                    ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03                     ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04                    ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05          ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia t09                      ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06                  ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07                    ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08            ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10                  ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11                             ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12                       ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13                        ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14                      ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15                ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16          ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18          ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19                                 ON (t19.id = t07.id_sexo)
            WHERE (t16.idestado = 'D') 
            AND t02.id_establecimiento = $lugar 
                AND $cond2"; 
                  
  //  $query . " ORDER BY t03.fecharecepcion DESC";
      //echo $query;
        $consulta=$objdatos->ListadoSolicitudesPorArea($query);  
	$NroRegistros= $objdatos->NumeroDeRegistros($query);
 	echo "<table width='100%' border='0' align='center'>
              <tr>
		    <td colspan='7' align='center' ><h3><strong>TOTAL DE MUESTRAS RECHAZADAS:".$NroRegistros."</strong></h3></td>
	      </tr>
	      <tr>
	            <td colspan='7' align='center' style='color:#990000; font:bold'><a style ='text-decoration:underline;cursor:pointer; font:bold; size:36' onclick='VistaPrevia();'>IMPRIMIR REPORTE</a></td>	
	      </tr>
	      </table> "; 
       

        $consulta = $objdatos->ListadoSolicitudesPorArea($query);

        echo "<table width='81%' border='1' align='center'>
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
        if(pg_num_rows($consulta)){
            $pos = 0;

            while ($row = pg_fetch_array($consulta)) {
             
                echo "<tr>
				   <td width='8%'>".$row['numeromuestra']."</td>
				   <td width='10%'>".$row['idnumeroexp']."</td>".
                                    "<input name='idsolicitudP[".$pos."]' id='idsolicitud1[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
                                    "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
			            "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' />".
			            "<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
			            "<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['id']."' />".
			            "<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' />".
				  "<td width='25%'>".$row['paciente']."</td>
				   <td width='10%'>".$row['idexamen']."</td>
				   <td width='25%'>".htmlentities($row['nombreexamen'])."</td>
				   <td width='20%'>".htmlentities($row['observacion'])."</td>
				   <td width='15%'>".htmlentities($row['nombresubservicio'])."</td>
				   <td width='15%'>".htmlentities($row['nombreservicio'])."</td>
                                   <td width='20%'>".htmlentities($row['estabext'])."</td>
				   <td width='15%'>".$row['fecharecepcion']."</td>
				   <td width='10%'>".($row['prioridad'])."</td>
                                      
				 </tr>";
                
              
                $pos = $pos + 1;
            }
            pg_free_result($consulta);
            echo "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />
                </table>";
        } else {
            echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></table>";
        }


        break;
   
   case 2:
	$idexpediente=$_POST['idexpediente'];
	$idsolicitud=$_POST['idsolicitud'];
	$idarea=$_POST['idarea'];
	$estado=$_POST['estado'];
	$fechasolicitud=$_POST['fechasolicitud'];
	$idexamen=$_POST['idexamen'];
	$fecharecep=$_POST['fecharecep'];
   
	if ($idarea=="URI" or $idarea=="BAT" ){   
		//echo $idarea;
		 //echo $idexamen;
		include_once("clsMuestrasPendientes.php");
		//recuperando los valores generales de la solicitud
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
		$medico=$row['NombreMedico'];
		$idmedico=$row['IdMedico'];
		$paciente=$row['NombrePaciente'];
		$edad=$row['Edad'];
		$sexo=$row['Sexo'];
		$precedencia=$row['Precedencia'];
		$origen=$row['Origen'];
		$fechasolicitud=$row['FechaSolicitud'];
		//recuperando los valores del detalle de la solicitud
		$datosexamen=$objdatos->DatosExamen($idarea,$idsolicitud,$idexamen);
		$imprimir="<form name='frmDatos'>
			<table width='60%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		    	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='".$precedencia."' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($origen)."
				<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='".$origen."' disabled='disabled' />
				<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='".$idsolicitud."' disabled='disabled' />
				<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='".$idexpediente."' disabled='disabled' />
				<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
				<input name='idarea' id='idarea'  type='hidden' size='40' value='".$idarea."' disabled='disabled' />
				</td>
		    </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
				<td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($medico)."
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
		    </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($paciente)." 
			     <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		    </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;$edad<input type='hidden' name='txtedad' value='". $edad."' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>
				&nbsp;&nbsp;&nbsp;$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
		    </tr>
			
	    </table>
		</td>
	</tr>
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
			    <td>".$fila['IdExamen']."</td>
			    <td>".htmlentities($fila['NombreExamen'])."</td>	
                            <td>".htmlentities($fila['TipoMuestra'])."</td>";	
                 if (!empty($fila['Indicacion'])){     								
                $imprimir .="<td>".htmlentities($fila['Indicacion'])."</td>";
                  }
				 else
                $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
			</tr>"; 

$pos=$pos + 1;
}
pg_free_result($datosexamen);
//echo $idexamen;
 $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
		</table>
		<table align='center'>
		<tr>
			<td colspan='4' align='center'>&nbsp;</td>
		</tr>
		<tr>
			<td colspan='4' align='center'>
			VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO
			</td>
		</tr>
		<tr>
			<td>Procesar Muestra</td>
			<td><select id='cmbProcesar' name='cmbProcesar' size='1' onChange='MostrarObservacion();' >
					<option value='0' >--Seleccione--</option>
					<option value='S' >Si</option>
			        <option value='N' >No</option>		
			    </select> 
			</td>
					
				<td colspan='2' >
			<input type='button' name='btnProcesar' disabled='disabled' id='btnProcesar'   value='Procesar Muestra' onClick=\"ProcesarMuestra1('".$idexamen."')\">
			<input type='button' name='btnRechazar'  id='btnRechazar' disabled='disabled' value='Rechazar Muestra' onClick=\"RechazarMuestra1('".$idexamen."')\">
			<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>
			</td>
			
		</tr>
		</table>
		<div id='divObservacion' style='display:none' >
		<table align='center' width='55%'>
		<tr>
			<td>Observacion</td>
			<td colspan='3'>
			<textarea cols='60' rows='2' name='txtobservacion' > </textarea>
			</td>
		</tr>
		</table>
		</div>
		</form>";
     echo $imprimir;
	// break;
	}else{	
		include_once("clsMuestrasPendientes.php");
		//recuperando los valores generales de la solicitud
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud);
		$row = pg_fetch_array($consulta);
		//obteniedo los datos generales de la solicitud
		//valores de las consultas
		$medico=$row['NombreMedico'];
		$idmedico=$row['IdMedico'];
		$paciente=$row['NombrePaciente'];
		$edad=$row['Edad'];
		$sexo=$row['Sexo'];
		$precedencia=$row['Precedencia'];
		$origen=$row['Origen'];
		//$DatosClinicos=$row['DatosClinicos'];
		$fechasolicitud=$row['FechaSolicitud'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idarea,$idsolicitud);
$imprimir="<form name='frmDatos'>
          	<table width='60%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
		    		<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		    	</tr>
		  	<tr>
		    		<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
		    		<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='".$precedencia."' disabled='disabled' /></td>
		    		<td class='StormyWeatherFieldCaptionTD'>Origen</td>
		    		<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($origen)."
					<input name='txtorigen' id='txtorigen'  type='hidden'  value='".$origen."' disabled='disabled' />
					<input name='idsolicitud' id='idsolicitud'  type='hidden' value='".$idsolicitud."' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' value='".$idexpediente."' disabled='disabled' />
					<input name='fechasolicitud' id='fechasolicitud' type='hidden' value='".$fechasolicitud."' disabled='disabled' />
					<input name='idarea' id='idarea'  type='hidden' value='".$idarea."' disabled='disabled' />
		    		</td>
		    	</tr>
		 	<tr>
		    		<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
		    		<td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($medico)."
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='".$medico."' disabled='disabled' /></td>
		    	</tr>
		  	<tr>
		    		<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
		    		<td colspan='3' class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;".htmlentities($paciente)." 
			     		<input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='".$paciente."' disabled='disabled' /></td>
		    	</tr>
		  	<tr>
		    		<td class='StormyWeatherFieldCaptionTD'>Edad</td>
		    		<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;$edad<input type='hidden' name='txtedad' value='". $edad."' 
					disabled='disabled' /></td>
		    		<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
		    		<td class='StormyWeatherDataTD'>
				&nbsp;&nbsp;&nbsp;$sexo<input type='hidden' name='txtsexo' value='".$sexo."' disabled='disabled' /></td>
		    	</tr>
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
			while($fila = pg_fetch_array($consultadetalle)){
				   $imprimir .= "<tr>
							<td>".$fila['IdExamen']."</td>
							<td>".htmlentities($fila['NombreExamen'])."</td>	
                					<td>".htmlentities($fila['TipoMuestra'])."</td>";	
							if (!empty($fila['Indicacion'])){    				
					  $imprimir .= "<td>".htmlentities($fila['Indicacion'])."</td></tr>";
            						}else{
					   $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
						</tr>"; 
							}
			
				$pos=$pos + 1;
			}
			pg_free_result($consultadetalle);

 						$imprimir .= "<input type='hidden' name='oculto' id='oculto' value='".$pos."' />
		</table>
		<table align='center'>
			<tr>
				<td colspan='4' align='center'>&nbsp;</td>
			</tr>
			<tr>
				<td colspan='4' align='center'>VALIDACI&Oacute;N DE RECEPCI&Oacute;N DE ESTUDIO</td>
			</tr>
			<tr>
				<td>Procesar Muestra</td>
				<td><select id='cmbProcesar' name='cmbProcesar' size='1' onChange='MostrarObservacion();' >
					<option value='0' >--Seleccione--</option>
					<option value='S' >Si</option>
					<option value='N' >No</option>		
				    </select> 
				</td>
				<td colspan='2' >
					<input type='button' name='btnProcesar' id='btnProcesar' disabled='disabled'  value='Procesar Muestra' onClick='ProcesarMuestra()'>
					<input type='button' name='btnRechazar' id='btnRechazar' disabled='disabled' value='Rechazar Muestra' onClick='RechazarMuestra()'>
					<input type='button' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>
				</td>
			</tr>
		</table>
		<div id='divObservacion' style='display:none' >
			<table align='center' width='55%'>
				<tr>
					<td>Observacion</td>
					<td colspan='3'>
						<textarea cols='60' rows='2' name='txtobservacion' > </textarea>
					</td>
				</tr>
			</table>
		</div>
	</form>";
     echo $imprimir;
	 //break;
}
   break;
    	
    	
   case 3:
$idexpediente=$_POST['idexpediente'];
$idsolicitud=$_POST['idsolicitud'];

$idarea=$_POST['idarea'];
$estado=$_POST['estado'];
$fechasolicitud=$_POST['fechasolicitud'];
$idexamen=$_POST['idexamen'];
$fecharecep=$_POST['fecharecep'];
  // echo $idsolicitud;
 // echo $estado;
  //echo $idexamen;
   
     if ($idarea=="URI" or $idarea=="BAT" ){   
	    if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)==true)   
		{
		//actualizar en la tabla de recepcion
		  if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
			{
			 	 echo "Solicitud Procesada..";
			}
		}
		else{
			echo "Solicitud No fue cambiada de Estado..";
		}
	   }
	   else{
		if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$idarea)==true)   
		  {
			echo "Muestras Recibidas.";	
			if($objdatos->CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)==true)
				{
				 //echo "Solicitud Procesada..";
				 }
				 else{
					echo "Solicitud No fue cambiada de Estado..";
				 }
		}
				//else{
			//echo "No se pudo actualizar";
		}
		//}
	 break;
			
	 
	  case 4:// Rechazar Muestra
$idexpediente=$_POST['idexpediente'];
$idsolicitud=$_POST['idsolicitud'];

$idarea=$_POST['idarea'];
$estado=$_POST['estado'];
$fechasolicitud=$_POST['fechasolicitud'];
$idexamen=$_POST['idexamen'];
$fecharecep=$_POST['fecharecep'];
	        $observacion=$_POST['observacion'];
		   if ($idarea=="URI" or $idarea=="BAT"){   
		     if ($objdatos->CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)==true)   
				{
				if($objdatos->MarcarObservacionRechazado1($idsolicitud,$idexamen,$observacion)==true)
				{
					echo "Muestra Rechazada";
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