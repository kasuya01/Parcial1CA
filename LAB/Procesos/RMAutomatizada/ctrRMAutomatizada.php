<?php

session_start();
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
include ("clsRMAutomatizada.php");

//variables POST
//$pag =$_POST['pag'];
//$observacion=$_POST['observacion'];
$estadosolicitud = "P";
//echo $estado;
$opcion = $_POST['opcion'];
//creando los objetos de las clases
$objdatos = new clsRMAutomatizada;

switch ($opcion) {
   case 1:
        $ban = 0;
        $IdEstab        = $_POST['IdEstab'];
        $IdServ         = $_POST['IdServ'];
        $IdSubServ      = $_POST['IdSubServ'];
        $idarea         = $_POST['idarea'];
        $idexamen       = $_POST['idexamen'];
        $idexpediente   = $_POST['idexpediente'];
        $fechasolicitud = $_POST['fechasolicitud'];
      //  $fecharecepcion = (empty($_POST['fecharecepcion'])) ? 'NULL' : "'" . pg_escape_string($_POST['fecharecepcion'])."'";
       $fecharecepcion = $_POST['fecharecep'];
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

       if ($_POST['IdServ'] <> 0) {
            $cond1 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $cond2 .= " t12.id  = " . $_POST['IdServ'] . " AND";
            $where_with = "t03.id = $IdServ AND ";
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

        if (!empty($_POST['fecharecep'])) {
             $cond1 .= " t03.fecharecepcion = '" . $_POST['fecharecep'] . "' AND";
             $cond2 .= " t03.fecharecepcion = '" . $_POST['fecharecep'] . "' AND";
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


        }
       // echo $cond2;
        $query="WITH tbl_servicio AS ( SELECT t02.id,
                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN
                    t02.nombre_ambiente
                    ELSE
                            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura  ||'   -   ' ||  t01.nombre
                                 WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre)

                                    THEN t01.nombre
                    END

                END AS servicio,
               (CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->'  || t06.nombre
                    ELSE   t07.nombre ||'-->' || t06.nombre
                END) as procedencia
                FROM ctl_atencion t01
                INNER JOIN mnt_aten_area_mod_estab t02 ON (t01.id = t02.id_atencion)
                INNER JOIN mnt_area_mod_estab t03 ON (t03.id = t02.id_area_mod_estab)
                LEFT JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                LEFT JOIN mnt_servicio_externo t05 ON (t05.id = t04.id_servicio_externo)
                INNER JOIN  ctl_area_atencion t06  on  t06.id = t03.id_area_atencion
                INNER JOIN ctl_modalidad  t07 ON t07.id = t03.id_modalidad_estab
                WHERE t02.id_establecimiento =  $lugar ORDER BY 2)

                 SELECT ordenar.* FROM (SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t01.id ,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla,
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra,
                       t06.numero AS idnumeroexp,
                       t03.id AS idrecepcionmuestra,
                       t18.idestandar AS idexamen,
                       t04.nombre_examen AS nombreexamen,
                       t01.indicacion, t08.nombrearea,
                       CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                       t07.segundo_apellido,t07.apellido_casada) AS paciente,
                       t20.servicio AS nombresubservicio,
                       t20.procedencia AS nombreservicio,
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
            FROM sec_detallesolicitudestudios           t01
            INNER JOIN sec_solicitudestudios            t02     ON (t02.id = t01.idsolicitudestudio)
            INNER JOIN lab_recepcionmuestra             t03     ON (t02.id = t03.idsolicitudestudio)
            INNER JOIN lab_conf_examen_estab            t04     ON (t04.id = t01.id_conf_examen_estab)
            INNER JOIN mnt_area_examen_establecimiento  t05     ON (t05.id = t04.idexamen)
            INNER JOIN mnt_expediente                   t06     ON (t06.id = t02.id_expediente)
            INNER JOIN mnt_paciente                     t07     ON (t07.id = t06.id_paciente)
            INNER JOIN ctl_area_servicio_diagnostico    t08     ON (t08.id = t05.id_area_servicio_diagnostico
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            INNER JOIN sec_historial_clinico            t09     ON (t09.id = t02.id_historial_clinico)
            INNER JOIN mnt_aten_area_mod_estab          t10     ON (t10.id = t09.idsubservicio)
            INNER JOIN ctl_atencion                     t11     ON (t11.id = t10.id_atencion)
            INNER JOIN mnt_area_mod_estab               t12     ON (t12.id = t10.id_area_mod_estab)
            INNER JOIN ctl_area_atencion                t13     ON (t13.id = t12.id_area_atencion)
            INNER JOIN ctl_establecimiento              t14     ON (t14.id = t09.idestablecimiento)
            INNER JOIN cit_citas_serviciodeapoyo        t15     ON (t02.id = t15.id_solicitudestudios)
            INNER JOIN ctl_estado_servicio_diagnostico  t16     ON (t16.id = t01.estadodetalle)
            INNER JOIN lab_tiposolicitud                t17     ON (t17.id = t02.idtiposolicitud)
            INNER JOIN ctl_examen_servicio_diagnostico  t18     ON (t18.id = t05.id_examen_servicio_diagnostico)
            INNER JOIN ctl_sexo                         t19     ON (t19.id = t07.id_sexo)
            INNER JOIN tbl_servicio                     t20     ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE ((t16.idestado = 'PM') OR (t16.idestado = 'D'))
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
                   t18.idestandar AS idexamen,
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
                FROM sec_detallesolicitudestudios       t01
            INNER JOIN sec_solicitudestudios            t02     ON (t02.id = t01.idsolicitudestudio)
            INNER JOIN lab_recepcionmuestra             t03     ON (t02.id = t03.idsolicitudestudio)
            INNER JOIN lab_conf_examen_estab            t04     ON (t04.id = t01.id_conf_examen_estab)
            INNER JOIN mnt_area_examen_establecimiento  t05     ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia              t09     ON t09.id=t02.id_dato_referencia
            INNER JOIN mnt_expediente_referido          t06     ON (t06.id = t09.id_expediente_referido)
            INNER JOIN mnt_paciente_referido            t07     ON (t07.id = t06.id_referido)
            INNER JOIN ctl_area_servicio_diagnostico    t08     ON (t08.id = t05.id_area_servicio_diagnostico
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            INNER JOIN mnt_aten_area_mod_estab          t10     ON (t10.id = t09.id_aten_area_mod_estab)
            INNER JOIN ctl_atencion                     t11     ON (t11.id = t10.id_atencion)
            INNER JOIN mnt_area_mod_estab               t12     ON (t12.id = t10.id_area_mod_estab)
            INNER JOIN ctl_area_atencion                t13     ON (t13.id = t12.id_area_atencion)
            INNER JOIN ctl_establecimiento              t14     ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo        t15     ON (t02.id = t15.id_solicitudestudios)
            INNER JOIN ctl_estado_servicio_diagnostico  t16     ON (t16.id = t01.estadodetalle)
            INNER JOIN lab_tiposolicitud                t17     ON (t17.id = t02.idtiposolicitud)
            INNER JOIN ctl_examen_servicio_diagnostico  t18     ON (t18.id = t05.id_examen_servicio_diagnostico)
            INNER JOIN ctl_sexo                         t19     ON (t19.id = t07.id_sexo)
            WHERE ((t16.idestado = 'PM') OR (t16.idestado = 'D'))
            AND t02.id_establecimiento = $lugar
            AND $cond2 ) ordenar
                ORDER BY to_date(ordenar.fecharecepcion, 'DD/MM/YYYY') DESC";


   // echo $query;
       $consulta=$objdatos->ListadoSolicitudesPorArea($query);
	$NroRegistros= $objdatos->NumeroDeRegistros($query);

        if ($NroRegistros==""){
            $NroRegistros=0;

            echo "<table width='81%' border='0' align='center'><center>



<tr><td colspan='12' align='center'><span style='color: #0101DF;'> <h3> TOTAL DE EXAMENES : ".$NroRegistros."</h3></span></td></tr>

	</table> ";

        }else{
            echo "<table width='81%' border='0'  align='center'><center>



<tr><td colspan='12' align='center'><span style='color: #0101DF;'> <h3> TOTAL DE EXAMENES : ".$NroRegistros."</h3></span></td></tr>

	</table> ";

        }





        $consulta = $objdatos->ListadoSolicitudesPorArea($query);

        echo "<div class='table-responsive' style='width: 100%;'>
            <table width='95%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                <thead><tr>
			<th>Muestra </th>
		        <th>NEC </th>
			<th>Paciente</th>
			<th>Cod. Examen</th>
			<th>Examen</th>
			<th>Observaci&oacute;n</th>
			<th>Servicio</th>
			<th>Procedencia</th>
			<th>Establecimiento</th>
                        <th>Fecha Consulta</th>
			<th>Fecha Recepci&oacute;n</th>
			<th>Tipo Solicitud</th>
                    </tr></thead><tbody>";
        if(pg_num_rows($consulta))
        {
            $pos = 0;

            while ($row = pg_fetch_array($consulta))
            {
                 echo "<tr>
                           <td width='3%'>" . $row['numeromuestra'] . "</td>
                           <td width='4%'><span style='color: #0101DF;'>
                           <a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(" . $pos . ");'>" .$row['idnumeroexp'] . "</a></td>
                           <input name='idsolicitudP[" . $pos . "]' id='idsolicitudP[" . $pos . "]' type='hidden' size='60' value='" . $row[1] . "' />" .
                            "<input name='idsolicitud[" . $pos . "]' id='idsolicitud[" . $pos . "]' type='hidden' size='60' value='" . $row[1] . "' />" .
                           "<input name='idexpediente[" . $pos . "]' id='idexpediente[" . $pos . "]' type='hidden' size='60' value='" . $row['idnumeroexp'] . "' />" .
                           "<input name='idarea[" . $pos . "]' id='idarea[" . $pos . "]' type='hidden' size='60' value='" . $idarea . "' />" .
                           "<input name='idexamen[" . $pos . "]' id='idexamen[" . $pos . "]' type='hidden' size='60' value='" . $row[1] . "' />" .
                           "<td width='18%'>" . $row['paciente'] . "</td>
                           <td width='3%'>" . $row['idexamen'] . "</td>
                           <td width='18%'>" . htmlentities($row['nombreexamen']) . "</td>";
                  if(!empty($row['observacion']))
                     echo" <td width='8%'>" . htmlentities($row['observacion']) . "</td>";
                  else
                      echo"<td width='10%'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                      echo"<td width='8%'>" . htmlentities($row['nombresubservicio']) . "</td>
                           <td width='12%'>" . htmlentities($row['nombreservicio']) . "</td>
                           <td width='36%'>" . htmlentities($row['estabext']) . "</td>
                           <td width='5%'>" . ($row['fechasolicitud']) . "</td>
                           <td width='10%'>" . $row['fecharecepcion'] . "</td>
                           <td width='10%'>" . ($row['prioridad']) . "</td>
		      </tr>";
                $pos = $pos + 1;
            }
            pg_free_result($consulta);
            echo "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />
                </tbody></table></div>";
        } else
            {
                 echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></tbody></table></div>";
            }


        break;

    case 2:
             $idexpediente   = $_POST['idexpediente'];
             $idarea    = $_POST['idarea'];
             $idsolicitud      = $_POST['idsolicitud'];
             $idexamen  = (empty($_POST['idexamen'])) ? 'NULL' : "'" . pg_escape_string($_POST['idexamen'])."'";

          //  $idsolicitudP       = $_POST['idsolicitudP'];




        //echo $idarea."**".$idsolicitud."**".$idexamen."**".$idexpediente;
        //echo $idexamen;
       // include_once("clsRMAutomatizada.php");
        //recuperando los valores generales de la solicitud

      $nombe=$objdatos->nombrepaciente($idsolicitud,$idexpediente);
      $row1 = pg_fetch_array($nombe);
      $nombrepaciente       = $row1['paciente'];
      $nombreexamen= $row1['nombreexamen'];


	//recuperando los valores generales de la solicitud
	$consulta=$objdatos->DatosGeneralesSolicitud($idsolicitud,$idexpediente);


	$row = pg_fetch_array($consulta);
        if (pg_num_rows($consulta)>0){





        //obteniedo los datos generales de la solicitud
        //valores de las consultas
        $idsolicitudPadre = $row[0];
        $medico         = $row['medico'];
        $idmedico       = $row[1];
        $paciente       = $row['paciente'];
        $edad           = $row['edad'];
        $sexo           = $row['sexo'];
        $precedencia    = $row['nombreservicio'];
        $origen         = $row['nombresubservicio'];
        //$DatosClinicos=$row['DatosClinicos'];
        $fechasolicitud=$row['fechasolicitud'];
        //echo $fechasolicitud;
        //$FechaNac=$row['FechaNacimiento'];
        $Talla          = $row['talla'];
        $Peso           = $row['peso'];
        $Diagnostico    = $row['diagnostico'];
     //$fecharecepcion = (empty($_POST['fecharecepcion'])) ? 'NULL' : "'" . pg_escape_string($_POST['fecharecepcion'])."'";
        $ConocidoPor    = $row['conocidox'];
      //  echo $ConocidoPor;
        //recuperando los valores del detalle de la solicitud
        $datosexamen = $objdatos->DatosExamen( $idsolicitud); //cambie esta funcion
        $imprimir = "<form name='frmDatos'>
                    <table width='65%' border='0' align='center' class='StormyWeatherFormTABLE'>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>DATOS SOLICITUD</td>
		   	</tr>

                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
                                <td class='StormyWeatherDataTD' colspan='3'>".$row['estabext']."</td>
			</tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Expediente</td>
                                <td class='StormyWeatherDataTD' colspan='3'><strong>".$idexpediente."</strong></td>
			</tr>

			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($paciente) . "
				     <input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='" . $paciente . "' disabled='disabled' /></td>
		    	</tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Conocido por</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($ConocidoPor) . " </td>
		    	</tr>
			<tr>



                                    <td class='StormyWeatherFieldCaptionTD'>Edad</td>
				   <td class='StormyWeatherDataTD'>$edad <input name='txtedad' id='txtedad'
				   type='hidden' size='35' value='" . $edad . "' disabled='disabled' /></td>

    				</div></td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>
				$sexo<input type='hidden' name='txtsexo' value='" . $sexo . "' disabled='disabled' /></td>
		    	</tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia'
				type='hidden' size='35' value='" . $precedencia . "' disabled='disabled' /></td>
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>" . htmlentities($origen) . "
                                    <input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='" . $origen . "' disabled='disabled' />
                                   <input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='" . $fechasolicitud . "' disabled='disabled' />
                                    <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='" . $idsolicitudPadre . "' disabled='disabled' />
                                    <input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='" . $idsolicitud . "' disabled='disabled' />
                                    <input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='" . $idexpediente . "' disabled='disabled' />

                                    <input name='idarea' id='idarea'  type='hidden' size='40' value='" . $idarea . "' disabled='disabled' />

				</td>
		        </tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($medico) . "
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='" . $medico . "' disabled='disabled' /></td>
		        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Diagnostico</td>
                                <td colspan='3' class='StormyWeatherDataTD'>" . $Diagnostico . "</td>
                        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Peso</td>";
        //<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='" . $fechasolicitud . "' disabled='disabled' />
        if (!empty($Peso))
            $imprimir .= "<td class='StormyWeatherDataTD'>" . $Peso . "&nbsp;&nbsp;Kg&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        else
            $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";

        $imprimir .="<td class='StormyWeatherFieldCaptionTD'>Talla</td>";
        if (!empty($Talla))
            $imprimir .="<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;" . $Talla . "&nbsp;&nbsp;mts.</td>";
        else
            $imprimir .= "<td class='StormyWeatherDataTD'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
        $imprimir .="</tr>
	   </table>
	<br>

	<table width='90%' border='0' align='center'>
		<tr>
			<td colspan='4' align='center' class='CobaltFieldCaptionTD' >ESTUDIO SOLICITADO</td>
		</tr>
		<tr>
			<td>
                            <table width='50%' border = 1 align='center' class='table table-bordered table-condensed table-white'>
                                <thead><tr class='CobaltFieldCaptionTD'>
                                    <th class='StormyWeatherFieldCaptionTD'> IdExamen</th>
                                    <th class='StormyWeatherFieldCaptionTD'> Examen </th>
                                    <th class='StormyWeatherFieldCaptionTD'> Tipo Muestra </th>
                                    <th class='StormyWeatherFieldCaptionTD'> Indicaci&oacute;n M&eacute;dica </th>
				</tr></thead><tbody>";
        $pos = 0;
        while ($fila = pg_fetch_array($datosexamen)) {

                  $imprimir .= "<tr>
                                    <td>" . $fila['idestandar'] . "</td>
                                    <td>" . htmlentities($fila['nombre_examen']) . "</td>
                                    <td>" . htmlentities($fila['tipomuestra']) . "</td>";
                        if (!empty($fila['Indicacion'])) {
                       $imprimir .="<td>" . htmlentities($fila['indicacion']) . "</td>";
                        } else
                       $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp</td>
                                </tr>";

            $pos = $pos + 1;
        }

        pg_free_result($datosexamen);

        //echo $idexamen;
        $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='" . $pos . "' />
				</tbody></table><br>

				<table align='center' width='85%' class='StormyWeatherFormTABLE'>
                                    <tr class='CobaltFieldCaptionTD'>
                                            <td colspan='4' align='center'>CAMBIO DE ESTADO DE EXAMEN</td>
                                    </tr>
                                    <tr>
                                    <td  class='StormyWeatherFieldCaptionTD'>Fecha de rechazo:</td>
                                    <td class='StormyWeatherDataTD'>
                                   <div class='input-group'>
                                      <span class='input-group-addon' id='basic-addon1' style='width:inherit'>
                                          <i class='fa fa-calendar'></i>
                                      </span>
                                        <input type='text' class='date form-control' id='fecharechazo' name='fecharechazo' style='width:105px' value='".date("Y-m-d")."' aria-describedby='basic-addon1' onchange=\"valdatesolicita(this.value, 'fecharechazo'); fecharechazosol();\">
                                   </div>
                                    </td>

                                    </tr>
                                    <tr>
                                        <td class='StormyWeatherFieldCaptionTD'>Estado de Detalle de examen:</td>
                                        <td class='StormyWeatherDataTD'><select name='CmbRechazo' id='CmbRechazo' style='width:290px'  class='form-control height'  onchange='rechazosolicitud(this.value)'>
                                                <option value='0' selected='selected' align='center'> Seleccione una opcion </option>";
                                                $db = new ConexionBD;
                                                        if ($db->conectar() == true) {
                                                            $consulta = "SELECT id,estado FROM lab_estado_rechazo WHERE id <> 1 AND habilitado=TRUE ";
                                                             $resultado = pg_query($consulta);
                                                            //por cada registro encon trado en la tabla me genera un <option>
                                                            while ($rows = pg_fetch_array($resultado)) {
                                                                $imprimir .= '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
                                                            }
                                                            //echo '<option value="' . $tipo . '" selected="selected">' . htmlentities($nomtipo) . '</option>';
                                                        }

                          $imprimir .= "</td>
                                    </tr>
                                        <td class='StormyWeatherFieldCaptionTD'>Motivo de cambio de estado:</td>
                                        <td class='StormyWeatherDataTD'>
                                            <div id='newreasonsol' style='display: block; width: 100%' >
                                            <select name='CmbObserv' id='CmbObserv' style='width:290px'  class='form-control height'>
                                                <option value='0' selected='selected' align='center'> Seleccione una Observación </option>";
                                                        /*$db = new ConexionBD;
                                                        if ($db->conectar() == true) {
                                                            $consulta = "SELECT id,posible_observacion FROM lab_posible_observacion WHERE habilitado=TRUE";
                                                             $resultado = pg_query($consulta);
                                                            //por cada registro encontrado en la tabla me genera un <option>
                                                            while ($rows = pg_fetch_array($resultado)) {
                                                                $imprimir .= '<option value="' . $rows[0] . '">' . $rows[1] . '</option>';
                                                            }
                                                            //echo '<option value="' . $tipo . '" selected="selected">' . htmlentities($nomtipo) . '</option>';
                                                        }    */

                             $imprimir .= " </select></div>
                                        </td>
                                    </tr>
                                    <tr id='newdatesol' style='display: none;'>
                                    <td class='StormyWeatherFieldCaptionTD'>*Fecha de nueva cita:</td>
                                    <td class='StormyWeatherDataTD'>
                                    <div class='input-group'>
                                    <span class='input-group-addon' id='basic-addon1' style='width:inherit'>
                                        <i class='fa fa-calendar'></i>
                                    </span>
                                      <input type='text' class='date form-control' id='fechanewcitasol' name='fechanewcitasol' style='width:105px'>
                                 </td>
                                    </tr>
                                    <tr>
                                            <td class='StormyWeatherDataTD' colspan='4' align='right'><input type='button' name='btnRechazar' class='btn btn-primary'  id='btnRechazar' value='Rechazar Muestra' onClick=\"RechazarMuestra1('" . $idexamen . "')\">
                                                    <input type='button' class='btn btn-primary' name='btnCerrar'  value='Cerrar' onClick='Cerrar()'>
                                            </td>
                                    </tr>";

                            $imprimir .="    </table>
		</form>";
        echo $imprimir;
        }else {// echo   $consulta;
      //          echo "dentro del else";

     echo '<br><br><br><br><img src="../../../Imagenes/indice.jpeg" valign="middle"  border="0" height="60" width="80" />';

     echo "<center> <h1> El Resultado Del Examen:<span style='color: #0101DF;'> $nombreexamen</span>, De: <span style='color: #0101DF;'> $nombrepaciente</span>, Ya a Sido Rechazado.</h1> ";


     echo " <button type='submit' class='fg-button ui-state-default ui-corner-all' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' />Cerrar</button></center>";

     }

        //<textarea cols='60' rows='2' name='txtobservacion' <span style='color: #0000FF;background-color:#87CEEB;'> " . htmlentities($fila['Observacion']) . "</textarea>

        break;

   case 3: // fue descartado este opcion
        $idexpediente      = $_POST['idexpediente'];
         $idsolicitud      = $_POST['idsolicitud'];
        $estado            = $_POST['estado'];
        $fechasolicitud    = $_POST['fechasolicitud'];
        $idexamen          = $_POST['idexamen'];
        $observacion       = '';


        break;
    case 4:// Rechazar Muestra

        $idsolicitud      = $_POST['idsolicitud'];
        $estado           = $_POST['estado'];
        $idexamen         = $_POST['idexamen'];
        $idobservacion    = $_POST['idobservacion'];
        $idrechazo        = $_POST['idrechazo'];
        $idsolicitudPadre = $_POST['idsolicitudPadre'];
        $idsolicitudP     = $_POST['idsolicitud'];
        $idarea           = $_POST['idarea'];
        $fecharechazo     = $_POST['fecharechazo'];
        $fechanewcitasol  = $_POST['fechanewcitasol'];
        $fechanewcitasol=(empty($_POST['fechanewcitasol'])) ? 'NULL' : "'" . pg_escape_string(trim($_POST['fechanewcitasol'])) . "'";
        $fecharechazo=(empty($fecharechazo)) ? 'NULL' : "'" . pg_escape_string(trim($fecharechazo)) . "'";
        //echo "fecha_rechazo: ".$fecharechazo." fechanewcitasol: ".$fechanewcitasol." idobservacion: ".$idobservacion." idrechazo: ".$idrechazo;
        //echo "Sol=".$idsolicitud." examen=".$idexamen." obser=".$observacion;

            $TotalExamenes=$objdatos->TotalDetallesSolicitud($idsolicitudPadre);
            $row1 = pg_fetch_array($TotalExamenes);
            $TotalGral= $row1[0];

            $TotalEstadoD=$objdatos->TotalEstadosDigitados($idsolicitudPadre);
            $row2 = pg_fetch_array($TotalEstadoD);
            $TotalD= $row2[0];

            // echo " TotalGral=".$TotalGral." TotalD=".$TotalD;
            $consulta=$objdatos->contaridresultado($idsolicitud,$idsolicitudPadre);
            $row = pg_fetch_array($consulta);
            $contaridresultado=$row[0];
            //echo $contaridresultado;

           // $proceso=$objdatos->ObtenerProcesoActivo($lugar);
          //  $rowPro=pg_fetch_array($proceso);
           // $ban=$rowPro[0];
           // echo $proceso;

            $consulta1=$objdatos->idexmen_metodologia($idsolicitud,$idsolicitudPadre);
            $row = pg_fetch_array($consulta1);
            $idexmen_metodologia=$row[0];

            $consulta1=$objdatos->idempleado($usuario);
            $row = pg_fetch_array($consulta1);
            $id_empleado=$row[0];
         // echo $id_empleado;
            $id_codigoresultado=5; //código de muestra inadecuada

            //$print="/";

            if ($objdatos->CambiarEstadoDetalle1($idsolicitud, $estado, $idexamen, $idobservacion, $idrechazo, $fecharechazo, $fechanewcitasol, $idsolicitudP, $usuario) == true)
            {  // echo "Muestra Rechazada, ";
        //        if($TotalD <>$TotalGral){
            //    $print .="estadodetalle"
                    if ($objdatos->CambiarEstadoSolicitud($idsolicitud, $idsolicitudPadre) == true)
                    { // echo "El Estado Solicitud fue Modificado";
                        /*if($objdatos->MarcarObservacionRechazado1($idsolicitud, $idobservacion, $idrechazo,$usuario)==true)
                        {*/
                            if ($objdatos->AgregarResultadoMetodologia($idsolicitudP,$idsolicitud,$id_codigoresultado,$idexmen_metodologia,$id_empleado,$usuario)==true)
                            {
                                echo "Muestra Rechazada";

                            }
                        //}
                    }
            /*    }
                else{
                   if($objdatos->CambiarEstadoSolicitud1($idsolicitudPadre) == true);
                    /* if($objdatos->MarcarObservacionRechazado1($idsolicitud, $idobservacion,$idrechazo,$usuario)==true)
                        {*/
            /*                if ($objdatos->AgregarResultadoMetodologia($idsolicitudP,$idsolicitud,$id_codigoresultado,$idexmen_metodologia,$id_empleado,$usuario)==true)
                            {
                                echo "Muestra Rechazada";

                            }
                    //    }

                }*/

            }
       // }



        break;

    case 5:  //LLENAR COMBO DE EXAMENES
        $rslts = '';

        $idarea = $_POST['idarea'];
        //echo $IdSubEsp;
        $dtExam = $objdatos->ExamenesPorArea($idarea, $lugar);

        $rslts = '<select name="cmbExamen" id="cmbExamen" style="width:500px" class="form-control height">';
        $rslts .='<option value="0">--Seleccione Examen--</option>';

        while ($rows = pg_fetch_array($dtExam)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
        }

        $rslts .= '</select>';
        echo $rslts;


        break;
    case 6:// Llenar Combo Establecimiento
         $rslts = '';
                    $Idtipoesta = $_POST['idtipoesta'];

                    // echo $Idtipoesta;
                    if ($Idtipoesta<>0){
                        $dtIdEstab = $objdatos->LlenarCmbEstablecimiento($Idtipoesta);
                        $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single height">';
                       // $rslts .='<option value="0"> Seleccione Establecimiento </option>';
                        while ($rows = pg_fetch_array($dtIdEstab)) {
                            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                        }
                    }else{
                         $dtIdEstab = $objdatos->LlenarTodosEstablecimientos();
                          $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single height">';
                        $rslts .='<option value="0"> Seleccione Establecimiento </option>';
                        while ($rows = pg_fetch_array($dtIdEstab)) {
                            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
                        }
                    }
                    $rslts .= '</select>';
                    echo $rslts;
    break;
    case 7:// Llenar combo Subservicio
          $rslts = '';
             $IdServ = $_POST['IdServicio'];
            //  echo $IdServ;
            $dtserv = $objdatos->LlenarCmbServ($IdServ, $lugar);
            $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:500px" class="form-control height">';
            $rslts .='<option value="0"> Seleccione un Servicio </option>';
            while ($rows = pg_fetch_array($dtserv)) {
                $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
            }

            $rslts .='</select>';
            echo $rslts;
        break;
    case 8:// Llenar combo Subservicio
    $idrechazo = $_POST['idrechazo'];
    $rslts = "";
    if ($idrechazo!=0){
        $query = $objdatos->obteneropcionesrechazo($idrechazo);
        $rslts = "<select name='CmbObserv' id='CmbObserv' class='form-control height' style='width:100%' >";
        $rslts .='<option value="0" selected>--Seleccione una opción--</option>';
        while ($rows = pg_fetch_array($query)) {
           $rslts.= '<option value="' . $rows["id_posible_observacion"] . '" >' . htmlentities($rows["posible_observacion"]) . '</option>';
        }
        $rslts .= '</select>';
    }
    else{
        $rslts = "<select name='CmbObserv' id='CmbObserv' class='form-control height' style='width:90%' >";
        $rslts .='<option value="0" selected>--Seleccione una opción--</option>';
        $rslts .= '</select>';
    }


    echo $rslts;
    break;
}
?>
