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
            $cond1 .= " t02.idtiposolicitud = '" . $_POST['TipoSolic'] . "' AND";
            $cond2 .= " t02.idtiposolicitud = '" . $_POST['TipoSolic'] . "' AND";
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
            //$query_search = $query . " ORDER BY t03.fecharecepcion DESC";
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
            WHERE (t16.idestado = 'RM')  
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
            WHERE (t16.idestado = 'RM') 
            AND t02.id_establecimiento = $lugar 
                AND $cond2"; 
                  
    
      //echo $query;
         $consulta=$objdatos->ListadoSolicitudesPorArea($query);  
		   $NroRegistros= $objdatos->NumeroDeRegistros($query);
		echo "<table width='95%' border='0' align='center'>
                        <tr>
			    <td colspan='7' align='center' ><h3><strong>TOTAL DE EXAMENES RECHAZADOS:".$NroRegistros."</strong></h3></td>
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
				   <td width='10%'>
					   <a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(".$pos.");'>".
					   $row['idnumeroexp']."</a>". 
					   "</td>". 
                                           "<input name='idsolicitudP[".$pos."]' id='idsolicitud1[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
					   "<input name='idsolicitud[".$pos."]' id='idsolicitud[".$pos."]' type='hidden' size='60' value='".$row[1]."' />".
					   "<input name='idexpediente[".$pos."]' id='idexpediente[".$pos."]' type='hidden' size='60' value='".$row['idnumeroexp']."' />".
					   "<input name='idarea[".$pos."]' id='idarea[".$pos."]' type='hidden' size='60' value='".$idarea."' />".
					   "<input name='idexamen[".$pos."]' id='idexamen[".$pos."]' type='hidden' size='60' value='".$row['idexamen']."' />".
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
		 $idsolicitudP=$_POST['idsolicitudP'];           
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
			/*$idsolicitudPadre=$row[0];
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
                        $ConocidoPor=$row[7];*/
        $idsolicitudPadre=$row[0];
        $medico         = $row['medico'];
        $idmedico       = $row[1];
        $paciente       = $row['paciente'];
        $edad           = $row['edad'];
        $sexo           = $row['sexo'];
        $precedencia    = $row['nombreservicio'];
        $origen         = $row['nombresubservicio'];
        //$DatosClinicos=$row['DatosClinicos'];
        //$fechasolicitud=$row['FechaSolicitud'];
        //$FechaNac=$row['FechaNacimiento'];
        $Talla          = $row['talla'];
        $Peso           = $row['peso'];
        $Diagnostico    = $row['diagnostico'];
        $ConocidoPor    = $row['conocidox'];
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
                                <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
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
	$idexpediente   =$_POST['idexpediente'];
	$idsolicitud    =$_POST['idsolicitud'];
        $idarea         =$_POST['idarea'];
	$estado         =$_POST['estado'];
	$fechasolicitud =$_POST['fechasolicitud'];
	$idexamen       =$_POST['idexamen'];
	$fecharecep     =$_POST['fecharecep'];
       $observacion     =$_POST['observacion'];
       $idsolicitudPadre=$_POST['idsolicitudPadre'];
      include_once("clsMuestrasRechazadas.php");
			//recuperando los valores generales de la solicitud
		
      
      $consulta=$objdatos->contaridresultado($idsolicitud,$idsolicitudPadre);
            $row = pg_fetch_array($consulta);
            $contaridresultado=$row[0];
            
if ($contaridresultado>0) 
              //if ($idresulta>0) 
    {
                        
           $consulta=$objdatos->idresultado($idsolicitud,$idsolicitudPadre);
            $row = pg_fetch_array($consulta);
             $idresulta=$row[0];
             
    
           // echo "si hay resultados!!    ";
            
            $consulta=$objdatos->id_detalleresultado($idresulta);
            $row = pg_fetch_array($consulta);
            $id_detalleresultado=$row[0];  //aca esta la falla.
           
            $consulta1=$objdatos->idexmen_metodologia($idsolicitud,$idsolicitudPadre);
            $row = pg_fetch_array($consulta1);
            $idexmen_metodologia=$row[0];
           
            $consulta1=$objdatos->idempleado($idsolicitud,$idsolicitudPadre);
            $row = pg_fetch_array($consulta1);
            $id_empleado=$row[0];
           
           //$idexmen_metodologia;
          // $id_detalleresultado;
         //  $usuario;
        //   $id_empleado
              
            
            if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$observacion)==true)   
		{
                    echo "Muestra Procesada ";
				//CambiarEstadoSolicitudProceso3
			if($objdatos->CambiarEstadoSolicitudProceso3($idexpediente,$fechasolicitud,$estadosolicitud,$idsolicitudPadre)==true)
				{       echo ", Solicitud  Fue cambiada De Estado..";
                                    if($objdatos->inseresul_metodologia($idexmen_metodologia,$id_detalleresultado,$estado,$observacion,$usuario,$id_empleado)==true)
                                        {
                                                //echo "insertado";
                                        } 
                                }
                                 
                                else
                                    {
					echo "No Se Pudo Actualizar La Solicitud";
				    }
		}
    }
    
    
    else {
            // echo "no hay resultados    ";
        
                if ($objdatos->CambiarEstadoDetalle($idsolicitud,$estado,$observacion)==true)   
		{
                    echo "Muestra Procesada ";
				//CambiarEstadoSolicitudProceso3
			if($objdatos->CambiarEstadoSolicitudProceso3($idexpediente,$fechasolicitud,$estadosolicitud,$idsolicitudPadre)==true)
				{
                                        echo ", Solicitud  Fue cambiada De Estado..";
                                }
                            else
                              {
			         echo "No Se Pudo Actualizar La Solicitud";
                              }
		}
        }
		
	break;
                
                
case 4:// Rechazar Muestra // se ah quitado esta opcion
			$idexpediente   =$_POST['idexpediente'];
			$idsolicitud    =$_POST['idsolicitud'];
			$idarea         =$_POST['idarea'];
			$estado         =$_POST['estado'];
			$fechasolicitud =$_POST['fechasolicitud'];
			$idexamen       =$_POST['idexamen'];
			$fecharecep     =$_POST['fecharecep'];
			$observacion    =$_POST['observacion'];
			if ($idarea=="URI" or $idarea=="BAT")
                        {   
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
		$rslts  ='';
		$idarea =$_POST['idarea'];
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
		$rslts      ='';
		$Idtipoesta =$_POST['idtipoesta'];
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
   	     $rslts ='';
             $IdServ=$_POST['IdServicio'];
	   //  echo $IdServ;
	     $dtserv=$objdatos->LlenarCmbServ($IdServ,$lugar);
	     $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
			$rslts .='<option value="0"> Seleccione Subespecialidad </option>';
			while ($rows =pg_fetch_array($dtserv))
                        {
                                $rslts.= '<option value="' . $rows[0] .'" >'. htmlentities($rows[1]).'</option>';
	       		}
				
	      $rslts .='</select>';
	      echo $rslts;
        break;	


  
}

?>