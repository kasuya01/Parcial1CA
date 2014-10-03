<?php

session_start();
$usuario = $_SESSION['Correlativo'];
$lugar   = $_SESSION['Lugar'];
$area    = $_SESSION['Idarea'];
include ("clsSolicitudesProcesadas.php");

//variables POST
$opcion = $_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsSolicitudesProcesadas;

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
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06 ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07 ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09 ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo)
            INNER JOIN tbl_servicio t20 ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE t16.idestado = 'PM' AND t02.id_establecimiento = $lugar AND $cond1
        
            UNION

            SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
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
                   (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia t09 ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06 ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07 ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo)
            WHERE t16.idestado = 'PM' AND t02.id_establecimiento = $lugar AND $cond2"; 
                  
    
       //echo $query;
       

        $consulta = $objdatos->ListadoSolicitudesPorArea($query);

        echo "<table width='81%' border='1' align='center'>
                <tr class='CobaltFieldCaptionTD'>
                    <td>Muestra </td>
                    <td>NEC </td>
                    <td>paciente </td>
                    <td>Id Examen</td>
                    <td>Examen</td>
                    <td>Servicio</td>
                    <td>Procedencia</td>
                    <td>Establecimiento</td>
                    <td>Fecha Consulta</td>
                    <td>Fecha Recepcion</td>
                    <td>Prioridad</td>
                </tr>";
        if(pg_num_rows($consulta)){
            $pos = 0;

            while ($row = pg_fetch_array($consulta)) {
                echo "<tr>
                        <td width='5%'>" . $row['numeromuestra'] . "</td>
                        <td width='7%'><a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(" . $pos . ");'>" . $row['idnumeroexp'] . "</a></td>" .
                        "<input name='idsolicitud[" . $pos . "]' id='idsolicitud[" . $pos . "]' type='hidden' size='60' value='" . $row["idsolicitudestudio"] . "' />" .
                        "<input name='idexpediente[" . $pos . "]' id='idexpediente[" . $pos . "]' type='hidden' size='60' value='" . $row["idnumeroexp"] . "' />" .
                        "<input name='paciente[" . $pos . "]' id='paciente[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["paciente"]) . "' />" .
                        "<input name='examen[" . $pos . "]' id='examen[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["nombreexamen"]) . "' />" .
                        "<input name='idexamen[" . $pos . "]' id='idexamen[" . $pos . "]' type='hidden' size='60' value='" . $row["idexamen"] . "' />" .
                        "<input name='iddetalle[" . $pos . "]' id='iddetalle[" . $pos . "]' type='hidden' size='60' value='" . $row["iddetallesolicitud"] . "' />" .
                        "<input name='idrecepcion[" . $pos . "]' id='idrecepcion[" . $pos . "]' type='hidden' size='60' value='" . $row["idrecepcionmuestra"] . "' />" .
                        "<input name='plantilla[" . $pos . "]' id='plantilla[" . $pos . "]' type='hidden' size='60' value='" . $row["idplantilla"] . "' />" .
                        "<input name='nombrearea[" . $pos . "]' id='nombrearea[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["nombrearea"]) . "' />" .
                        "<input name='procedencia[" . $pos . "]' id='procedencia[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["nombreservicio"]) . "' />" .
                        "<input name='origen[" . $pos . "]' id='origen[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["nombresubservicio"]) . "' />" .
                        "<input name='impresion[" . $pos . "]' id='impresion[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["impresiones"]) . "' />" .
                        "<input name='establecimiento[" . $pos . "]' id='establecimiento[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["nombre"]) . "'/>" .
                        "<input name='FechaNac[" . $pos . "]' id='FechaNac[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["fechanacimiento"]) . "'/>" .
                        "<input name='Sexo[" . $pos . "]' id='Sexo[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["sexo"]) . "'/>" .
                        "<input name='IdEstandar[" . $pos . "]' id='IdEstandar[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["idestandar"]) . "'/>" .
                        "<input name='IdHistorial[" . $pos . "]' id='IdHistorial[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["idhistorialclinico"]) . "'/>" .
                        "<td width='20%'>" . htmlentities($row['paciente']) . "</td>
                        <td width='7%'>" . $row['idexamen'] . "</td>
                        <td width='20%'>" . htmlentities($row['nombreexamen']) . "</td>
                        <td width='12%'>" . htmlentities($row['nombresubservicio']) . "</td>
                        <td width='10%'>" . htmlentities($row['nombreservicio']) . "</td>
                        <td width='20%'>" . htmlentities($row['estabext']) . "</td>
                        <td width='10%'>" . ($row['fechasolicitud']) . "</td>
                        <td width='10%'>" . ($row['fecharecepcion']) . "</td>
                        <td width='10%'>" . ($row['prioridad']) . "</td>
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

    case 2://LLENANDO COMBO DE EMPLEADOS
        $idarea = $_POST['idarea'];
        //echo $idarea;
        $resultado = "<select id='cmbEmpleados' name='cmbEmpleados' size='1'>
                            <option value='0' >--Seleccione--</option>";
        require_once('clsSolicitudesProcesadas.php');
        $obje = new clsSolicitudesProcesadas;
        $consulta = $obje->BuscarEmpleados($idarea, $lugar);
        while ($row = pg_fetch_array($consulta)) {
            $resultado .="<option value='" . $row[0] . "'>" . htmlentities($row[1]) . "</option>";
        }
        pg_free_result($consulta);
        $resultado.= "</select>";
        echo $resultado;
        break;
    case 3://GUARDANDO DATOS DE RESULTADOS Y MOSTANDO LISTA ACTUALIZADA PLANTILLA "A"
        //Guardando Resultados
        $idexamen = $_POST['idexamen'];
        $idsolicitud = $_POST['idsolicitud'];
        $iddetalle = $_POST['iddetalle'];
        $resultado = $_POST['resultado'];
        $lectura = $_POST['lectura'];
        $observacion = $_POST['observacion'];
        $responsable = $_POST['responsable'];
        $idrecepcion = $_POST['idrecepcion'];
        $interpretacion = $_POST['interpretacion'];
        $codigo = $_POST['codigo'];
        //echo $codigo;
        //$idexpediente=$_POST['idexpediente'];
        $verifica = $objdatos->BuscarResultado($idexamen, $idsolicitud, $iddetalle, $lugar);
        // echo $verifica[0];
        if ($verifica == 0) {
            $verdadero = $objdatos->InsertarResultadoPlantillaA($idexamen, $idsolicitud, $iddetalle, $resultado, $lectura, $observacion, $responsable, $lectura, $idrecepcion, $interpretacion, $observacion, $usuario, $codigo, $lugar);

            if ($verdadero != 0) {
                echo "Datos Guardados";
                if (($objdatos->CambiarEstadoDetalle($iddetalle) == true) && ($objdatos->CambiarEstadoSolicitud($idsolicitud) == true)) {
                    echo " Correctamente";
                }
            } else {
                echo "No guardo";
            }
        } else {
            echo "Ya Existe un Resultado para esta prueba";
        }
        //Cambia estado del detalle de la solicitud

        break;

    case 4: //MOSTRAR PREVIAMENTE LOS RESULTADOS
        $idsolicitud = $_POST['idsolicitud'];
        $cod = $_POST['codigo'];

        $idexamen = $_POST['idexamen'];
        $resultado = $_POST['resultado'];
        $lectura = $_POST['lectura'];
        $interpretacion = $_POST['interpretacion'];
        $observacion = $_POST['observacion'];
        $responsable = $_POST['responsable'];
        $nombrearea = $_POST['nombrearea'];
        $establecimiento = $_POST['establecimiento'];
        $fechanac = $_POST['fechanac'];
        $sexo = $_POST['sexo'];

        $Consulta_Estab = $objdatos->Nombre_Establecimiento($lugar);
        $row_estab = pg_fetch_array($Consulta_Estab);

        // echo $idsolicitud." - ".$idexamen." - ".$lugar." - ".$sexo." - ".$idedad;
        $consulta = $objdatos->MostrarResultadoGenerales($idsolicitud, $idexamen, $lugar);
        $row = pg_fetch_array($consulta);
        $nombre = $row['nombrearea'];
        $proce = $row['Procedencia'];

        $Cuentadias = $objdatos->CalculoDias($fechanac);
        $Cdias = pg_fetch_array($Cuentadias);
        $dias = $Cdias[0];

        $ConRangos = $objdatos->ObtenerCodigoRango($dias);
        $row_rangos = pg_fetch_array($ConRangos);
        $idedad = $row_rangos[0];

        //  echo $fechanac."***".$sexo."***".$idedad;
        //cho $idedad;
        $Imprimir = "<table width='100%' align='center' border='0' class='StormyWeatherFormTABLE'>
	            <tr>
			<td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                        <td align='center' colspan='4' width='60%' class='Estilo5'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong>" . $row_estab['nombre'] . "</strong></p>
                            <p><strong>&Aacute;REA DE " . htmlentities($nombre) . " </strong></p>
			</td>
                        <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                    </tr>
                    <tr>
                        <td colspan='6' align='center'></td>
                    </tr>
                    <tr>
                    	<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
                    	<td colspan='2' style='font:bold'>" . htmlentities($establecimiento) . "</td>
                    	<td colspan='1'style='font:bold'><strong>Fecha:</strong></td>
                    	<td colspan='2' style='font:bold'>" . $row['Fecha'] . "<input name='suEdad' id='suEdad'  type='hidden'  value='" . $row['fechanacimiento'] . "'/></td>
                    </tr>

                    <tr>
                    	<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			<td colspan='2' style='font:bold'>" . $row['idnumeroexp'] . "</td></tr>
		    <tr>
                        <td colspan='1' style='font:bold'><strong>paciente:</strong></td>
			<td colspan='5' style='font:bold'>" . htmlentities($row['NombrePaciente']) . "</td>
                    </tr>
                    <tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2' style='font:bold'>
                            <div id='divsuedad'>
                            </div></td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2' style='font:bold'>" . $row['Sexo'] . "</td>
                    </tr>
                     </tr>
                    	<td colspan='1'style='font:bold'><strong>Procedencia:</strong></td>
                    	<td colspan='2''>" . htmlentities($row['Procedencia']) . "</td>
                    	<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2'>" . htmlentities($row['Origen']) . "</td>
                    </tr>
                    <tr>";
        $consulta_empleado = $objdatos->BuscarEmpleadoValidador($responsable, $lugar);
        $fila_empleado = pg_fetch_array($consulta_empleado); //$fila_empleado['NombreEmpleado'].
        $Imprimir.="
                    	<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
			<td  colspan='5' style='font:bold'>" . htmlentities($fila_empleado['NombreEmpleado']) . "</td>
                    </tr>
                    <tr>
                        <td colspan='1'>Resultado Tabulador:</td>";
        $nomcod = $objdatos->ObtenerNombreCodigo($cod);
        $row_codigo = pg_fetch_array($nomcod);

        $Imprimir.="<td colspan='5'>" . $row_codigo[0] . "</td>
	            </tr>
		    <tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		    </tr>

		    </table>";

        $Imprimir.="<table width='100%'  align='center' border='0' class='StormyWeatherFormTABLE'>
                        <tr>
                            <td colspan='6' align='center' >&nbsp;DETALLE DE RESULTADOS</td>
                        </tr>
                        <tr >
                            <td align='center'>Prueba Realizada </td>
                            <td align='center'>Resultado</td>
                            <td align='center'>Unidades</td>
                            <td align='center'>Rangos Normales </td>
                            <td align='center'>Lectura</td>
			    <td align='center'>Interpretaci&oacute;n</td>
			    <td align='center'>Observaci&oacute;n</td>
			</tr>";

        //MOSTRAR DATOS FIJOS Y RESULTADOS DIGITADOS
        $consulta2 = $objdatos->MostrarDatosFijosPlantillaA($idexamen, $lugar, $sexo, $idedad);
        $fila = pg_fetch_array($consulta2);

        $Imprimir.="<tr>
                            <td align='center' style='font:bold'><strong>" . htmlentities($fila['NombreExamen']) . "</strong></td>
			    <td align='center'>" . htmlentities($resultado) . "</td>
			    <td align='center'>" . htmlentities($fila['Unidades']) . "</td>
			    <td align='center'>" . $fila['RangoInicio'] . " - " . $fila['RangoFin'] . "</td>
			    <td align='center'>" . htmlentities($lectura) . "</td>
			    <td align='center'>" . htmlentities($interpretacion) . "</td>
			    <td align='center'>" . htmlentities($observacion) . "</td>
			</tr>";
        $Imprimir.="<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
			</tr>
                        </table>";
        $Imprimir.="<table align='center' border='0'>
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
			</tr>
                        <tr>
                            <td colspan='7' align='center' >
				<input type='submit' id='btnGuardar' value='Guardar Resultados' Onclick='GuardarResultados();' />
				<input type='button' name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaA(" . $idsolicitud . ",\"" . $idexamen . "\",\"" . htmlentities($resultado) . "\", \"" . htmlentities($lectura) . "\",\"" . htmlentities($interpretacion) . "\",\"" . htmlentities($observacion) . "\",\"" . $responsable . "\",\"" . $sexo . "\",\"" . $idedad . "\") ;'>
				<input type='submit' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' />
                            </td>
			</tr>
			</table>";
        echo $Imprimir;

        pg_free_result($consulta2);
        break;
    case 5://LLENANDO COMBO DE Examenes
        $rslts = '';
        $idarea = $_POST['idarea'];

        $dtExam = $objdatos->ExamenesPorArea($idarea, $lugar);

        $rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
        $rslts .='<option value="0"> Seleccione Examen </option>';

        while ($rows = pg_fetch_array($dtExam)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[2]) . '</option>';
        }

        $rslts .= '</select>';
        echo $rslts;


        break;
    case 6:// Llenar Combo Establecimiento
        $rslts = '';
        $Idtipoesta = $_POST['idtipoesta'];
        // echo $Idtipoesta;
        $dtIdEstab = $objdatos->LlenarCmbEstablecimiento($Idtipoesta);
        $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:375px">';
        $rslts .='<option value="0"> Seleccione Establecimiento </option>';
        while ($rows = pg_fetch_array($dtIdEstab)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
        }

        $rslts .= '</select>';
        echo $rslts;
        break;
    case 7:// Llenar combo Subservicio
        $rslts = '';
        $IdServ = $_POST['IdServicio'];
        //  echo $IdServ;
        $dtserv = $objdatos->LlenarCmbServ($IdServ, $lugar);
        $rslts = '<select name="cmbSubServ" id="cmbSubServ" style="width:375px">';
        $rslts .='<option value="0"> Seleccione un Servicio </option>';
        while ($rows = pg_fetch_array($dtserv)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
        }

        $rslts .='</select>';
        echo $rslts;
        break;
    case 8:// LLenado de combo de resultados para tabulaci�n tabulaci�n
        $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1'>
				<option value='0' >--Seleccione--</option>";
        require_once('clsSolicitudesProcesadas.php');
        $obje = new clsSolicitudesProcesadas;
        $consulta = $obje->LlenarResultados();
        while ($row = pg_fetch_array($consulta)) {
            $resultado .="<option value='" . $row[0] . "'>" . htmlentities($row[1]) . "</option>";
        }
        pg_free_result($consulta);
        $resultado.= "</select>";
        echo $resultado;
        break;
    case 9:
        $result = $objdatos->consultarTipoSolicitud();

        if ($result !== false) {
            $jsonresponse['status'] = true;
            $jsonresponse['data'] = pg_fetch_all($result);
        } else {
            $jsonresponse['status'] = false;
        }

        echo json_encode($jsonresponse);
        break;
}
?>
