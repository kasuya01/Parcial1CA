<?php session_start();
$usuario=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];
include ("clsSolicitudesPorPaciente.php");

//variables POST
$opcion=$_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsSolicitudesPorPaciente;
//echo $idexpediente;
switch ($opcion) 
{
  	case 1:  
		/*$idexpediente=$_POST['idexpediente'];
	//$idsolicitud=$_POST['idsolicitud'];
		$primernombre=$_POST['primernombre'];
		$segundonombre=$_POST['segundonombre'];
		$primerapellido=$_POST['primerapellido'];
		$segundoapellido=$_POST['segundoapellido'];
		
		$fechaconsulta=$_POST['fechaconsulta'];
		$IdEstab=$_POST['IdEstab'];
		$IdServ=$_POST['IdServ'];
		$IdSubServ=$_POST['IdSubServ'];
			
	
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
		}*/
    
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
                        t01.observacion,
                        CASE t02.estado 
			WHEN 1 THEN 'Digitada'
			WHEN 2 THEN 'Recibida'
			WHEN 3 THEN 'En Proceso'    
			WHEN 4 THEN 'Completa' END AS estado
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
            WHERE  t02.id_establecimiento = $lugar
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
            t01.observacion,
            CASE t02.estado 
			WHEN 1 THEN 'Digitada'
			WHEN 2 THEN 'Recibida'
			WHEN 3 THEN 'En Proceso'    
			WHEN 4 THEN 'Completa' END AS estado
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
            WHERE t02.id_establecimiento = $lugar 
                AND $cond2"; 
              
				
		
	     //echo $query_search;
		$consulta=$objdatos->BuscarSolicitudesPaciente($query); 
		
		$NroRegistros= $objdatos->NumeroDeRegistros($query);				
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
				while ($row = pg_fetch_array($consulta))
				{ 
					//$Idsolic=$row['IdSolicitudEstudio'];
                                         $Idsolic=$row[1];
					$fecha=$objdatos->BuscarRecepcion($Idsolic);
					$recepcion= pg_fetch_array($fecha);
					$fechacita=$objdatos->BuscarCita($Idsolic);
					$cita= pg_fetch_array($fechacita);
					if (!empty($recepcion)){
			$imprimir.="<tr>
					<td width='9%'>".$recepcion['fecharecepcion']."</td>";
						}else{ 		
		$imprimir.="<tr>
						<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
						}
		   $imprimir .="<td width='7%'>
							<a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
							$row['IdNumeroExp']."</a>". 
							"<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
							"<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' /></td>".
							"<input name='idestablecimiento[".$pos."]' id='idestablecimiento[".$pos."]' type='hidden' size='60' value='".$IdEstab."' /></td>".
					   "<td width='25%'>".htmlentities($row['paciente'])."</td>
						<td width='15%'>".htmlentities($row['nombresubservicio'])."</td>
						<td width='12%'>".htmlentities($row['nombreservicio'])."</td>
						<td width='15%'>".htmlentities($row['estabext'])."</td>
						<td width='9%'>".$row['estado']."</td>
						<td width='12%'>".$row['FechaSolicitud']."</td>
				    </tr>";
					$pos=$pos + 1;
				}
				
				pg_free_result($consulta);
				
			$imprimir .= "<input type='hidden' name='oculto' id='text' value='".$pos."' /> 
	
			    </table>";
	
			echo $imprimir;
	
	break;
    	
	case 2:  // solicitud estudios
		$idexpediente=$_POST['idexpediente'];
		$idsolicitud=$_POST['idsolicitud'];
		$IdEstablecimiento=$POST['idestablecimiento'];
		echo $IdEstablecimiento;
		include_once("clsSolicitudesPorPaciente.php");
		//recuperando los valores generales de la solicitud
		
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
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
		$DatosClinicos=$row['DatosClinicos'];
		$Estado=$row['Estado'];
		$fechasolicitud=$row['FechaSolicitud'];
                $FechaNac=$row['FechaNacimiento'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud);
		$estadosolicitud=$objdatos->EstadoSolicitud($idexpediente,$idsolicitud);
		$estado=pg_fetch_array($estadosolicitud);
		
		
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
					<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='".$fechasolicitud."' disabled='disabled' />
					<input name='suEdad' id='suEdad'  type='hidden' size='40' value='".$FechaNac."' disabled='disabled' /></td>
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
			while($fila = pg_fetch_array($consultadetalle)){
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

			pg_free_result($consultadetalle);
			
 	//$imprimir .= "<i/nput type='hidden' name='oculto' id='oculto' value='".$pos."' />
	 $imprimir .="</table>
	<div id='divImpresion' style='display:block' >
		<table>
			<tr >
				<p></p>
			</tr>
			<tr >
				<td align='center'>
					<input type='button' name='btnImprimirSol' id='btnImprimirSol' value='Imprimir Solicitud' onClick='ImprimirSolicitud()'>";
					if($estado['Estado']=='R' OR $estado['Estado']=='P'){
					  
						$imprimir .="<input type='button' name='btnImprimir'  id=btnImprimir' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes();'/>";}
					else{
						$imprimir .="<input type='button' name='btnImprimir' disabled='enabled' id=btnImprimir' value='Imprimir Vi&ntilde;etas' onClick='ImprimirExamenes();'/>";
					}
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
		
  	 include_once("clsSolicitudesPorPaciente.php");
		//recuperando los valores generales de la solicitud
		
		$consulta=$objdatos->DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar);
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
		$DatosClinicos=$row['DatosClinicos'];
		$Estado=$row['Estado'];
		$fechasolicitud=$row['FechaSolicitud'];
		 $FechaNac=$row['FechaNacimiento'];
		//recuperando los valores del detalle de la solicitud
		$consultadetalle=$objdatos->DatosDetalleSolicitud($idexpediente,$idsolicitud);
		$imprimir="<form name='frmDatos'>
    		<table width='80%' border='0' align='center'>
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
		while($fila = pg_fetch_array($consultadetalle)){
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

pg_free_result($consultadetalle);

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
		
}//switch

?>