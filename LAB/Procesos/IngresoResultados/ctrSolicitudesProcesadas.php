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
        //echo $IdEstab; 
                
        if ($_POST['IdEstab']<>0) {
           if ($_POST['IdEstab']<>$lugar){
               $cond1 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
               $cond2 .= " t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " AND";
            }
            else {
               $cond1 .= " t02.id_establecimiento_externo = " . $lugar . " AND";
               $cond2 .= " t02.id_establecimiento_externo = " . $lugar . " AND";
            }
          
        }
        
        if ($_POST['IdServ'] <> 0) {
            $cond1 .= " t13.id  = " . $_POST['IdServ'] . " AND";
            $cond2 .= " t13.id  = " . $_POST['IdServ'] . " AND";
            $where_with = "id_area_atencion = $IdServ AND ";
        }

        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
            $cond2 .= " t10.id = " . $_POST['IdSubServ'] . " AND";
        }

        if ($_POST['idarea'] <> 0) {
            $cond1 .= " t08.id = " . $_POST['idarea'] . " AND";
            $cond2 .= " t08.id = " . $_POST['idarea'] . " AND";
        }

        if ($_POST['idexpediente'] <> 0) {
            $cond1 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
            $cond2 .= " t06.numero = '" . $_POST['idexpediente'] . "' AND";
        }

        if ($_POST['idexamen'] <> 0) {
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
//echo $TipoSolic;        
        if ($TipoSolic != '0') {
            //echo "Entro". $TipoSolic;  
            $cond1 .= " t17.idtiposolicitud = '" . $TipoSolic . "' AND";
            $cond2 .= " t17.idtiposolicitud = '" . $TipoSolic . "' AND";
           
           //echo " EntroCon1=".$cond1;
        }

        /*if ((empty($_POST['idexpediente'])) AND ( $_POST['idarea']===0) AND ( empty($_POST['fechasolicitud']))
                AND ( empty($_POST['IdEstab'])) AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ']))
                AND ( empty($_POST['PNombre'])) AND ( empty($_POST['SNombre'])) AND ( empty($_POST['PApellido']))
                AND ( empty($_POST['SApellido'])) AND ( empty($_POST['idexamen'])) AND ( empty($_POST['TipoSolic']))) {*/
        if((empty($cond1)) AND (empty($cond2))){
            $ban = 1;
        }
        else{
            $ban = 0;
        }
     //   echo "BAN = ".$ban;
        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
            $var1 = $lugar." AND ".$cond1;
            $var2 = $lugar." AND ".$cond2;
            //  echo $query1;
            //$query_search = $query . " ORDER BY t03.fecharecepcion DESC";
        }   
        else{
            
            $var1 = $lugar;
            $var2 = $lugar;
        }
        //echo $cond2;
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
                    SELECT ordenar.* FROM (
                       SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla, 
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra, 
                       t06.numero AS idnumeroexp, 
                       t03.id AS idrecepcionmuestra, 
                       t04.id AS idexamen,
                       t04.nombre_examen AS nombreexamen,
                       t04.codigo_examen AS codigoexamen,
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
                       t19.id AS sexo, 
                       t18.idestandar,
                       t02.id_establecimiento_externo as IdEstab,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                       false AS referido, to_char(t01.f_tomamuestra,'dd/mm/YYYY HH12:MI' ) as f_tomamuestra,
                       (SELECT tipomuestra FROM lab_tipomuestra WHERE id=t01.idtipomuestra) AS tipomuestra,
                       t17.idtiposolicitud,t08.id as idarea
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
               
                WHERE t16.idestado = 'PM' AND t02.id_establecimiento = $var1
        
                UNION

                SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                       t02.id AS idsolicitudestudio,
                       t04.idplantilla, 
                       t01.id AS iddetallesolicitud,
                       t03.numeromuestra,
                       t06.numero AS idnumeroexp,
                       t03.id AS idrecepcionmuestra,
                       t04.id AS idexamen,
                       t04.nombre_examen AS nombreexamen,
                       t04.codigo_examen AS codigoexamen,
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
                       t19.id AS sexo, 
                       t18.idestandar,
                       t02.id_establecimiento_externo,
                       (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                       true AS referido,TO_CHAR(t01.f_tomamuestra,'dd/mm/YYYY HH12:MI') as f_tomamuestra,
                       (SELECT tipomuestra FROM lab_tipomuestra WHERE id=t01.idtipomuestra) AS tipomuestra,
                       t17.idtiposolicitud,t08.id as idarea
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
               
                WHERE t16.idestado = 'PM' AND t02.id_establecimiento = $var2) ordenar
                ORDER BY to_date(ordenar.fecharecepcion, 'DD/MM/YYYY') DESC"; 
//ECHO $query;
        $consulta = $objdatos->ListadoSolicitudesPorArea($query);
        
        echo "<div class='table-responsive' style='width: 90%;'>
            <table width='100%' border='1' align='center' class='table table-hover table-bordered table-condensed table-white'>
                <thead><tr>
                    <th>Muestra </th>
                    <th>NEC </th>
                    <th>paciente </th>
                    <th>Id Examen</th>
                    <th>Examen</th>
                    <th>Servicio</th>
                    <th>Procedencia</v>
                    <th>Establecimiento</th>
                    <th>Fecha Consulta</th>
                    <th>Fecha Recepcion</th>
                    <th>Prioridad</th>
                </tr></thead><tbody>";
      // echo '<br/>'.$query.'<br/>';
        if(pg_num_rows($consulta)){
            $pos = 0;

            while ($row = pg_fetch_array($consulta)) {
                $consmet=$objdatos->CantMetodologia($row["idexamen"]);
             //   echo 'Consmet: '.$consmet;
                echo "<tr>
                        <td width='3%'>" . $row['numeromuestra'] . "</td>
                        <td width='3%'><a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(" . $pos . ");'>" . $row['idnumeroexp'] . "</a></td>" .
                        "<input name='idsolicitud[" . $pos . "]' id='idsolicitud[" . $pos . "]' type='hidden' size='60' value='" . $row["idsolicitudestudio"] . "' />" .
                        "<input name='idexpediente[" . $pos . "]' id='idexpediente[" . $pos . "]' type='hidden' size='60' value='" . $row["idnumeroexp"] . "' />" .
                        "<input name='paciente[" . $pos . "]' id='paciente[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["paciente"]) . "' />" .
                        "<input name='examen[" . $pos . "]' id='examen[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["nombreexamen"]) . "' />" .
                        "<input name='cant_metodologia[" . $pos . "]' id='cant_metodologia[" . $pos . "]' type='hidden' size='60' value='" . $consmet . "' />" .
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
                        "<input name='referido[" . $pos . "]' id='referido[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["referido"]) . "'/>" .
                        "<input name='estabext[" . $pos . "]' id='estabext[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["estabext"]) . "'/>" .
                        "<input name='idestabext[" . $pos . "]' id='idestabext[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["idestab"]) . "'/>" .
                        "<input name='f_tomamuestra[" . $pos . "]' id='f_tomamuestra[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["f_tomamuestra"]) . "'/>" .
                        "<input name='tipomuestra[" . $pos . "]' id='tipomuestra[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["tipomuestra"]) . "'/>" .
                        "<input name='idareaPA[" . $pos . "]' id='idareaPA[" . $pos . "]' type='hidden' size='60' value='" . htmlentities($row["idarea"]) . "'/>" .
                        "<td width='18%'>" . htmlentities($row['paciente']) . "</td>
                        <td width='3%'>" . $row['codigoexamen'] . "</td>
                        <td width='27%'>" . htmlentities($row['nombreexamen']) . "</td>
                        <td width='10%'>" . htmlentities($row['nombresubservicio']) . "</td>
                        <td width='8%'>" . htmlentities($row['nombreservicio']) . "</td>
                        <td width='21%'>" . htmlentities($row['estabext']) . "</td>
                        <td width='5%'>" . ($row['fechasolicitud']) . "</td>
                        <td width='5%'>" . ($row['fecharecepcion']) . "</td>
                        <td width='3%'>" . ($row['prioridad']) . "</td>
                    </tr>";
                $pos = $pos + 1;
            }
            pg_free_result($consulta);
            echo "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />
                </tbody></table></div>";
        } else {
            echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></tbody></table></div>";
        }


        break;

    case 2://LLENANDO COMBO DE EMPLEADOS
        $idarea = $_POST['idarea'];
       // echo $idarea;
        $resultado = "<select id='cmbEmpleados' name='cmbEmpleados' size='1' style='width:96%' class='form-control height'>
                        <option value='0' >Seleccione...</option>";
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
        $idresultado = $_POST['idresultado'];
        if ($idresultado=='x'){
           $idresultado='NULL';
        }
           
        //$resultado = $_POST['resultado'];
        $resultado = (empty($_POST['resultado'])) ? 'NULL' : "'" . pg_escape_string($_POST['resultado']) . "'";
        //$lectura = $_POST['lectura'];
        $lectura = (empty($_POST['lectura'])) ? 'NULL' : "'" . pg_escape_string($_POST['lectura']) . "'";
        $marca = (empty($_POST['marca'])) ? 'NULL' : "'" . pg_escape_string($_POST['marca']) . "'";
      //$observacion = $_POST['observacion'];
        $observacion = (empty($_POST['observacion'])) ? 'NULL' : "'" . pg_escape_string($_POST['observacion']) . "'";
        $responsable = $_POST['responsable'];
        $idrecepcion = $_POST['idrecepcion'];
        //$interpretacion = $_POST['interpretacion'];
        $interpretacion = (empty($_POST['interpretacion'])) ? 'NULL' : "'" . pg_escape_string($_POST['interpretacion']) . "'";
        $codigo = $_POST['codigo'];
        //$fecha_realizacion = $_POST['fecha_realizacion'];
        $fecha_realizacion = (empty($_POST['fecha_realizacion'])) ? 'NULL' : "'" . pg_escape_string($_POST['fecha_realizacion']) . "'";
        $fecha_reporte = (empty($_POST['fecha_reporte'])) ? 'NULL' : "'" . pg_escape_string($_POST['fecha_reporte']) . "'";
        //fecha_reporte = $_POST['fecha_reporte'];
        //echo $codigo;
        //$idexpediente=$_POST['idexpediente'];
        $cantingresados=0;
        $flag=0;
        $verifica = @pg_num_rows($objdatos->BuscarResultado($idexamen, $idsolicitud, $iddetalle, $lugar));
        $idmetodologia=@pg_fetch_array($objdatos->BuscarExaMetodologia($idexamen));
        // echo $verifica[0];
        if ($verifica == 0) {
            $verdadero = $objdatos->InsertarResultadoPlantillaAM($idexamen, $idmetodologia['idexamet'], $responsable, $fecha_realizacion, $fecha_reporte, $resultado, $observacion, $codigo, $idsolicitud, $usuario, $iddetalle, $idrecepcion, $lugar, $idresultado, $marca, $lectura) ;
            
            if ($verdadero != 0) {
                $cantingresados++;
                if ($objdatos->InsertarResultadoPlantillaAF($idsolicitud, $iddetalle,$idrecepcion, $resultado, $lectura, $interpretacion, $observacion, $lugar, $usuario, $idexamen, $responsable, $fecha_reporte, $idresultado, $marca)==false){
                    echo "Error al momento de validar resultado. Por favor revisar informaci&oacute;n.";
                    $flag=1;
                }
                else{
                    if (($objdatos->CambiarEstadoDetalle($iddetalle) == true) && ($objdatos->CambiarEstadoSolicitud($idsolicitud) == true)) {
                            echo " Resultados de Solicitud Completa. ";
                        }
                }
              //  echo "<center>Datos Guardados</center>";

            } else {
                echo "Error al momento de guardar resultados. Por favor revisar información.";
                $flag=1;
            }
            
            if ($flag==0){	
                    if ($cantingresados>0){
	echo "Registro grabado correctamente";
                    }
                    else{
                        echo "No se ha ingresado ningún resultado";
                    }
	}

	else{
		echo "Error de grabacion";
	}
            
            
         /*   
            $verdadero = $objdatos->InsertarResultadoPlantillaA($idexamen, $idsolicitud, $iddetalle, $resultado, $lectura, $observacion, $responsable, $lectura, $idrecepcion, $interpretacion, $observacion, $usuario, $codigo, $lugar, $fecha_realizacion, $fecha_reporte);

            if ($verdadero != 0) {
               // echo "Datos Guardados";
                if (($objdatos->CambiarEstadoDetalle($iddetalle) == true) && ($objdatos->CambiarEstadoSolicitud($idsolicitud) == true)) {
                    echo " Correctamente";
                }
            } else {
                echo "No guardo";
            }*/
        } else {
            echo "Ya Existe un Resultado para esta prueba";
        }
        //Cambia estado del detalle de la solicitud

        break;

    case 4: //MOSTRAR PREVIAMENTE LOS RESULTADOS  //Plantilla A
        $idsolicitud = $_POST['idsolicitud'];
        $iddetalle = $_POST['iddetalle'];
        $cod = $_POST['codigo'];

        $idexamen = $_POST['idexamen'];
        $resultado = $_POST['resultado'];
        $lectura = $_POST['lectura'];
        $marca = $_POST['marca'];
        $interpretacion = $_POST['interpretacion'];
        $observacion = $_POST['observacion'];
        $responsable = $_POST['responsable'];
        $nombrearea = $_POST['nombrearea'];
        $proce = $_POST['procedencia'];
        $origen = $_POST['origen'];
        $establecimiento = $_POST['establecimiento'];
        $fechanac = $_POST['fechanac'];
        $sexo = $_POST['sexo'];
        $idmetodologia = $_POST['cmbmetodologia'];
        $txtnec = $_POST['nec'];
        $fecha_realizacion = $_POST['fecha_realizacion'];
        $fecha_reporta = $_POST['fecha_reporta'];
        $idresultado = $_POST['idresultado'];

        $Consulta_Estab = $objdatos->Nombre_Establecimiento($lugar);
        $row_estab = pg_fetch_array($Consulta_Estab);
        
        //Buscar Datos Paciente
        
       //  echo $idsolicitud." - ".$idexamen." - ".$lugar." - ".$sexo." - ".$idedad;
        $consulta = $objdatos->MostrarResultadoGenerales($idsolicitud, $idexamen, $lugar);
        $row = pg_fetch_array($consulta);
        $nombre = $row['nombrearea'];
        $id_establecimiento_externo = $row['id_establecimiento_externo'];
        $idhistoref = $row['idhistoref'];
        $datospaciente=$objdatos->MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $txtnec, $idhistoref);
        $rowpa = pg_fetch_array($datospaciente);
        $fecha_tmx=$objdatos->ftomamuestra($iddetalle, $lugar);
        $ftmx = pg_fetch_array($fecha_tmx);
        $f_tomamuestra=$ftmx['f_tomamuestra'];
        
    //    $proce = $row['procedencia'];

       // $Cuentadias = $objdatos->CalculoDias($fechanac);
        //$Cdias = pg_fetch_array($Cuentadias);
        $dias = $rowpa['dias'];

        $ConRangos = $objdatos->ObtenerCodigoRango($dias);
        $row_rangos = pg_fetch_array($ConRangos);
        $idedad = $row_rangos[0];

        //  echo $fechanac."***".$sexo."***".$idedad;
        //cho $idedad;
        $Imprimir = "<table width='100%' align='center' border='0' class='StormyWeatherFormTABLE' style='height: 350px;'>
	            <tr>
			<td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                        <td align='center' colspan='4' width='60%' class='Estilo5'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong>" . $row_estab['nombre'] . "</strong></p>
                            <p><strong>&Aacute;REA DE " . $nombre . " </strong></p>
			</td>
                        <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                    </tr>
                    <tr>
                        <td colspan='6' align='center'><hr><br></td>
                    </tr>
                    <tr>
                    	<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
                    	<td colspan='2' style='font:bold'>" . $establecimiento . "</td>
                        <td colspan='1' style='font:bold'><strong>Fecha Toma Muestra:</strong></td>
			<td colspan='2' style='font:bold'>" . $f_tomamuestra . "</td>    
                    </tr>

                    <tr>
                    	<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			<td colspan='2' style='font:bold'>" . $txtnec . "</td>
                        <td colspan='1'style='font:bold'><strong>Fecha Recepción:</strong></td>
                    	<td colspan='2' style='font:bold'>" . $row['fecharecepcion'] . "<input name='suEdad' id='suEdad'  type='hidden'  value='" . $rowpa['fecha_nacimiento'] . "'/></td>    
                    </tr>
		    <tr>
                        <td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
			<td colspan='2' style='font:bold'>" . $rowpa['nombre'] . "</td>
                        <td colspan='1' style='font:bold'><strong>Fecha Reporte:</strong></td>
			<td colspan='2' style='font:bold'>" . $fecha_reporta . "
                        <input type='hidden' id='fecha_reporte_' name='fecha_reporte_' value='" . $fecha_reporta . "'/>
                        <input type='hidden' id='fecha_realiza_' name='fecha_realiza_' value='" . $fecha_realizacion . "'/></td>
                    </tr>
                    <tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2' style='font:bold'>".$rowpa['edad']."
                            
                           <!-- <div id='divsuedad'>
                            </div>--></td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2' style='font:bold'>" . $rowpa['sexo'] . "</td>
                    </tr>
                     <tr>
                    	<td colspan='1'style='font:bold'><strong>Procedencia:</strong></td>
                    	<td colspan='2''>" . $proce . "</td>
                    	<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2'>" . $origen . "</td>
                    </tr>
                    <tr>";
          $met=$objdatos->buscarexamresult($iddetalle, $idsolicitud, $lugar, $idexamen, $sexo, $idedad);
         $cantmet=pg_num_rows($met);
        $consulta_empleado = $objdatos->BuscarEmpleadoValidador($responsable, $lugar);
        $fila_empleado = pg_fetch_array($consulta_empleado); //$fila_empleado['NombreEmpleado'].
        $Imprimir.="
                    	<td  colspan='1' style='font:bold'><strong>Validado Por: </strong></td>
			<td  colspan='5' style='font:bold'>" . $fila_empleado['empleado'] . "
                            <input type='hidden' id='idempleado_' name='idempleado_' value='" . $responsable . "'/></td>
                    </tr>
                   <tr style='display:none'>
                        <td colspan='1'><strong>Resultado Tabulador:</strong></td>";
        $nomcod = $objdatos->ObtenerNombreCodigo($cod);
        $row_codigo = pg_fetch_array($nomcod);

        $Imprimir.="<td colspan='5'>" . $row_codigo['resultado'] . "<input type='hidden' id='codresultado_' name='codresultado_' value='" . $cod . "'/></td>
	            </tr>
		    <tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		    </tr>

		    </table>";

        $Imprimir.="<table width='100%'  align='center' border='0' class='StormyWeatherFormTABLE'>
                        <tr>";
       if ($cantmet!=0){
            $Imprimir.="<td colspan='8' align='center' >&nbsp;DETALLE DE RESULTADOS<br/><br></td>";
        }
        else{
           $Imprimir.="<td colspan='5' align='center' >&nbsp;DETALLE DE RESULTADOS<br/><br></td>";
        }
        
                            
                            
         $Imprimir.="</tr>
                        <tr >
                            <td align='center'><b>Prueba Realizada </b></td>";
        if ($cantmet!=0){
            $Imprimir.="<td align='center'><b>Metodología </b></td>";
        }
        $Imprimir.="                    
                            <td align='center'><b>Resultado</b></td>
                            <td align='center'><b>Unidades</b></td>
                            <td align='center'><b>Rangos Normales </b></td>";
        if ($cantmet!=0){
         $Imprimir.=      " <td align='center'><b>Marca</b></td>
                            <td align='center'><b>Lectura</b></td></td>";
        }
      else {
         $Imprimir.=    "<td align='justify'><b>Observaci&oacute;n</b></td>
                             </tr>";
      }
	 
         if ($cantmet!=0){
            $Imprimir.="<tr><td colspan='8'><hr style='width:90%'></td></tr>";
        }
        else{
           $Imprimir.="<tr><td colspan='5'><hr style='width:90%'></td></tr>";
        }       
                
                        

        //MOSTRAR DATOS FIJOS Y RESULTADOS DIGITADOS
        $consulta2 = $objdatos->MostrarDatosFijosPlantillaA($idexamen, $lugar, $sexo, $idedad, $idmetodologia);
        $fila = pg_fetch_array($consulta2);

        $Imprimir.="<tr>
                            <td align='center' style='font:bold'>" . $fila['nombre_examen'] . "</td>";
        if ($cantmet!=0){
            $Imprimir.="<td align='center' style='font:bold'><strong>" . $fila['nombre_metodologia'] . "</strong></td>";
        }
        $Imprimir.="   
                            
			    <td align='center'>" . $resultado . "<input type='hidden' id='resultado_' name='resultado_' value='" . $resultado . "'/><input type='hidden' id='idresultado_' name='idresultado_' value=".$idresultado." /></td>
			    <td align='center'>" . $fila['unidades'] . "</td>
			    <td align='center'>" . $fila['rangoinicio'] . " - " . $fila['rangofin'] . "</td>";
        if ($cantmet!=0){
        $Imprimir.=" 
			    <td align='center'>" . $marca . "</td>"
                . "<td align='center'>" . $lectura . "</td>
			    ";
        }
        $Imprimir.=" 
			    <td align='justify'>" . $observacion . "<input type='hidden' id='observacion_' name='observacion_' value='" . $observacion . "'/><input type='hidden' id='marca_' name='marca_' value='" . $marca . "'/><input type='hidden' id='lectura_' name='lectura_' value='" . $lectura . "'/><input type='hidden' id='interpretacion_' name='interpretacion_' value='" . $interpretacion . "'/></td>
			</tr>";
        $Imprimir.="<tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            
                            <td>&nbsp;</td>";
       if ($cantmet!=0){
          $Imprimir.= " <td>&nbsp;</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td></tr>
                        </table>";
       }
       else{
           $Imprimir.="</tr>
                        </table>";
       }
                
			
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
                            <button type='button' id='btnGuardar' align='center' class='btn btn-primary' title='Guardar Resultados'  onclick='GuardarResultados();'><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Guardar Resultados</button>
                            <button style='display:none' type='button' id='Imprimir' name='Imprimir' align='center' class='btn btn-primary' title='Imprimir'  onclick='ImprimirPlantillaA(" . $idsolicitud . ",\"" . $idexamen . "\",\"" . $resultado . "\",\"" . $fecha_reporta . "\", \"" . $lectura . "\",\"" . $interpretacion . "\",\"" . $observacion . "\",\"" . $responsable . "\",\"" . $sexo . "\",\"" . $idedad . "\",\"" . $txtnec . "\",\"" . $proce . "\",\"" . $origen . "\",\"" . $iddetalle . "\",\"" . $marca . "\") ;'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>
                            
                            <button type='button' id='btnSalir' align='center' class='btn btn-primary' title='Cerrar'  onclick='Cerrar();'><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button><br/><br><br/>
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

        $rslts.= '<select name="cmbExamen" id="cmbExamen" style="width:500px" class="form-control height">';
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
        if ($Idtipoesta<>0){
            $dtIdEstab = $objdatos->LlenarCmbEstablecimiento($Idtipoesta);
            $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single">';
           // $rslts .='<option value="0"> Seleccione Establecimiento </option>';
            while ($rows = pg_fetch_array($dtIdEstab)) {
                $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
            }
        }else{
             $dtIdEstab = $objdatos->LlenarTodosEstablecimientos();
              $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" style="width:500px" class="js-example-basic-single">';
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
    case 8:// LLenado de combo de resultados para tabulaci�n tabulaci�n
        $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1' class='form-control height' style='width:100%'>
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
            $jsonresponse['nums'] = pg_num_rows($result);
        } else {
            $jsonresponse['status'] = false;
        }

        echo json_encode($jsonresponse);
        break;
        
    case 10://LLENANDO COMBO DE Metodologia
        $idexamen = $_POST['idexamen'];
      //  echo 'idexamen: '.$idexamen.'--<br\>';
        $consulta = $objdatos->BuscarMetodologia($idexamen);
        $cant=  pg_num_rows($consulta);
        if ($cant>0){
           
        $resultado2 = "<select id='cmbmetodologia' name='cmbmetodologia' size='1' onchange='buscarPosResMet(this.value)' style='width:96%; height:100%' class='form-control height'>";
           // if ($cant>1){
                $resultado2 .="<option value='0' selected>Seleccione...</option>";
            //}
        while ($row = pg_fetch_array($consulta)) {
            $resultado2 .="<option value='" . $row['idexamet'] . "'>" . $row['nombre_metodologia'] . "</option> ";
        }
        pg_free_result($consulta);
        $resultado2.= "</select>";
        $resultado2.= "<input type='hidden' id='cant_metodologia' name='cant_metodologia' value='".$cant."'>   ";
        
        }
        else{
            $resultado2=$cant;
        }
        echo $resultado2;
        break;
              
    case 11://LLENANDO COMBO DE Empleado que emite resultado final
        $idarea = $_POST['idarea'];
        //echo $idarea;
        $resultado = "<select id='cmbEmpleadosfin' name='cmbEmpleadosfin' size='1' style='width:100%' class='form-control height'>
                        <option value='0' >Seleccione...</option>";
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
    //Fn PG    
    case 12://Ingresando los resultados de la boleta.
         // $idexamen = $_POST['idexamen'];
        $idsolicitud = $_POST['idsolicitud'];
        $iddetalle = $_POST['iddetalle'];
        $idrecepcion = $_POST['idrecepcion'];
        $val = $_POST['val'];
       /* if ($val==1){
            $v_resultfin = $_POST['v_resultfin'];
            $v_obseresultfin = $_POST['v_obseresultfin'];
            $cmbEmpleadosfin = $_POST['cmbEmpleadosfin'];
            $d_resultfin = $_POST['d_resultfin'];
        }*/
        $cantingresados=0;
        $cantidadnum=$_POST['cantidadnum'];
        $flag=0;
        
        if ($cantidadnum>0) {
	    for ($i=1; $i<=$cantidadnum; $i++){
		$hdnidexamen_ = $_POST['hdnidexamen_'.$i];
		$hdnIdMetodologia_ = $_POST['hdnIdMetodologia_'.$i];
		$hdnResp_ = $_POST['hdnResp_'.$i];
		$hdnFecProc_ =  (empty($_POST['hdnFecProc_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnFecProc_'.$i]) . "'";
		$hdnFecResu_ =  (empty($_POST['hdnFecResu_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnFecResu_'.$i]) . "'";
		$hdnResult_ =  (empty($_POST['hdnResult_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnResult_'.$i]) . "'";
		$hdnIdResult_ =  $_POST['hdnIdResult_'.$i];
                if ($hdnIdResult_ =='x')
                   $hdnIdResult_='NULL';
		$hdnMarca_=  (empty($_POST['hdnMarca_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnMarca_'.$i]) . "'";
		$hdnLectura_=  (empty($_POST['hdnLectura_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnLectura_'.$i]) . "'";
		$hdnObserva_=  (empty($_POST['hdnObserva_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnObserva_'.$i]) . "'";
		$hdnCodResult_ = $_POST['hdnCodResult_'.$i];
                                
                $verifica = @pg_num_rows($objdatos->BuscarResultado($idexamen, $idsolicitud, $iddetalle, $lugar));
                
             //   echo '<br>idmeto: '.$hdnIdMetodologia_.'<br> resul:'.$hdnResult_.'<br> i: '.$i.' - cant: '.$cantidadnum;
              //   echo '<br>Verificadoooo: '.$verifica;
                if ($verifica == 0) {
            //        echo '    entro a verifica';
                    $verdadero = $objdatos->InsertarResultadoPlantillaAM($hdnidexamen_, $hdnIdMetodologia_, $hdnResp_, $hdnFecProc_, $hdnFecResu_, $hdnResult_, $hdnObserva_, $hdnCodResult_, $idsolicitud, $usuario, $iddetalle, $idrecepcion, $lugar, $hdnIdResult_, $hdnMarca_, $hdnLectura_);
                    

                    if ($verdadero != 0) {
                        $cantingresados++;
                        echo "<center>Datos Guardados</center>";
                        
                    } else {
                        echo "<center>Error al momento de guardar resultados. Por favor revisar informaci&oacute;n.</center>";
                        $flag=1;
                    }
                } else {
                    echo "<center>El examen ya fue validado</center>";
                }
		//$hdnIdProce_ = $_POST['hdnIdProce_'.$i];
		//$hdnMarReac_ = $_POST['hdnMarReac_'.$i];
		//$hdnMarReac_ = (empty($_POST['hdnMarcReac_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnMarcReac_'.$i]) . "'";
		//$hdnLecExa_ = $_POST['hdnLecExa_'.$i];
		//$hdnLecExa_ = (empty($_POST['hdnLectExa_'.$i])) ? 'NULL' : "'" . pg_escape_string($_POST['hdnLectExa_'.$i]) . "'";
		//$examenid= $objeto->crearDetalleResultado($hdnIdpruebaest_,$hdnFecProc_, $hdnFecResu_, $hdnIdPosResult_, $hdnPosResult_,$hdnIdPosObser_,		$hdnPosObser_, $hdnIdTipoRes_,$hdnIdProce_, $hdnMarReac_,$hdnLecExa_, $i_idemppl, $idestabres, $i_iddetorden);
		
	    }
	}
	$estorden=0;
        //Si valido los datos ingresara aca porque val=1;
        if ($val==1 && $cantingresados>0)
	{
		$v_resultfin=(empty($_POST['v_resultfin'])) ? 'NULL' : "'" . pg_escape_string($_POST['v_resultfin']) . "'";
                $idresultadofin=$_POST['idresultadofin'];
                if ($idresultadofin=='x'){
                   $idresultadofin='NULL';
                }
		
		$cmbEmpleadosfin=$_POST['cmbEmpleadosfin'];
               
		$d_resultfin=(empty($_POST['d_resultfin'])) ? 'NULL' : "'" . pg_escape_string($_POST['d_resultfin']) . "'";
               
		$v_obserrecep=(empty($_POST['v_obseresultfin'])) ? 'NULL' : "'" . pg_escape_string($_POST['v_obseresultfin']) . "'";
		$v_interpretacion=(empty($_POST['v_interpretacion'])) ? 'NULL' : "'" . pg_escape_string($_POST['v_interpretacion']) . "'";
		$v_lectura=(empty($_POST['v_lectura'])) ? 'NULL' : "'" . pg_escape_string($_POST['v_lectura']) . "'";
                $marca='NULL';
		if ($objdatos->InsertarResultadoPlantillaAF($idsolicitud, $iddetalle,$idrecepcion, $v_resultfin, $v_lectura, $v_interpretacion, $v_obserrecep, $lugar, $usuario, $hdnidexamen_, $cmbEmpleadosfin, $d_resultfin, $idresultadofin, $marca)==false)                {
                    echo "<font color=red><center>Error al momento de validar resultado. Por favor revisar informaci&oacute;n.</center></font>";
                    $flag=1;
		}
                else{
                    if (($objdatos->CambiarEstadoDetalle($iddetalle) == true) && ($objdatos->CambiarEstadoSolicitud($idsolicitud) == true)) {
                            echo "<center> Resultados de Solicitud Completa. </center>";
                        }
                }
	}
	
        	if ($flag==0){	
                    if ($cantingresados>0){
	echo "<font color=blue><center><img src='../../../Imagenes/ok.png' align='absmiddle'/>Registro grabado correctamente</center></font>";
                    }
                    else{
                        echo "<font color=blue><center><img src='../../../Imagenes/warning.png' align='absmiddle'/>No se ha ingresado ningún resultado</center></font>";
                    }
	}

	else{
		echo "<font color=red><center><img src='../../../Imagenes/error.png' align='absmiddle'/>Error de grabacion</center></font>";
	}
        
       // $verifica = $objdatos->BuscarResultado($idexamen, $idsolicitud, $iddetalle, $lugar);
        // echo $verifica[0];
      /*  if ($verifica == 0) {
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
        }*/
        //Cambia estado del detalle de la solicitud

        break;
     case 13:
        $idsolicitud = $_POST['idsolicitud'];
        $iddetalle = $_POST['iddetalle'];
        $idrecepcion = $_POST['idrecepcion'];
        $idexamen = $_POST['idexamen'];
        $val = $_POST['val'];
        $txtnec = $_POST['txtnec'];
        $sexo = $_POST['sexo'];
        $proce = $_POST['procedencia'];
        $origen = $_POST['origen'];
        $establecimiento = $_POST['establecimiento'];
        $cmbEmpleadosfin=$_POST['cmbEmpleadosfin'];
        $d_resultfin=$_POST['d_resultfin'];
      
        $cantingresados=0;
        $cantidadnum=$_POST['cantidadnum'];
        $flag=0;
        
        $Consulta_Estab = $objdatos->Nombre_Establecimiento($lugar);
        $row_estab = pg_fetch_array($Consulta_Estab);
        $consulta = $objdatos->MostrarResultadoGenerales($idsolicitud, $idexamen, $lugar);
        
        $row = pg_fetch_array($consulta);
        $nombre = $row['nombrearea'];
        $id_establecimiento_externo = $row['id_establecimiento_externo'];
        $idhistoref = $row['idhistoref'];
        $datospaciente=$objdatos->MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $txtnec, $idhistoref);
        $rowpa = pg_fetch_array($datospaciente);
        $dias = $rowpa['dias'];

        $ConRangos = $objdatos->ObtenerCodigoRango($dias);
        $row_rangos = pg_fetch_array($ConRangos);
        $idedad = $row_rangos[0];
        $consulta2 = $objdatos->MostrarDatosFijosPlantillaA($idexamen, $lugar, $sexo, $idedad, 0);
        $fila = pg_fetch_array($consulta2);
        $consultares = $objdatos->MostrarDatoslabresultado($idexamen, $lugar, $idsolicitud, $iddetalle);
        $filares = pg_fetch_array($consultares);
        $d_resultfin=$filares['fecha_resultado'];
        $responsable=$filares['idempleado'];
        $f_tomamuestra=$filares['f_tomamuestra'];
        
        
        $timefresult = strtotime($d_resultfin);
         $d_resultfin = date("Y-m-d", $timefresult);
    //    $proce = $row['procedencia'];

       // $Cuentadias = $objdatos->CalculoDias($fechanac);
        //$Cdias = pg_fetch_array($Cuentadias);
         $consulta_empleado = $objdatos->BuscarEmpleadoValidador($responsable, $lugar);
                $fila_empleado = pg_fetch_array($consulta_empleado); //$fila_empleado['NombreEmpleado'].
       
        $Imprimir = "<hr><table width='100%' align='center' border='0' class='StormyWeatherFormTABLE' style='height: 350px;'>
	            <tr>
			<td colspan='1' align='left' width='20%'><img id='Image1' style='WIDTH: 80px; HEIGHT: 55px' height='86' src='../../../Imagenes/escudo.png' width='210' name='Image1'></td>
                        <td align='center' colspan='4' width='60%' class='Estilo5'>
                            <p><strong>RESULTADOS LABORATORIO CL&Iacute;NICO</strong></p>
                            <p><strong>" . $row_estab['nombre'] . "</strong></p>
                            <p><strong>&Aacute;REA DE " . $nombre . " </strong></p><br>
			</td>
                        <td colspan='1' align='right' width='20%'><img id='Image3' style='WIDTH: 110px; HEIGHT: 55px' height='86' src='../../../Imagenes/paisanito.png' width='210' name='Image3'></td>
                    </tr>
                    
                      <td colspan='6' align='center'><hr><br></td>
                    </tr>
                    <tr>
                         
                    	<td colspan='1' style='font:bold'><strong>Establecimiento:</strong></td>
                    	<td colspan='2' style='font:bold'>" . $establecimiento . "</td>
                        <td colspan='1' style='font:bold'><strong>Fecha Toma Muestra:</strong></td>
			<td colspan='2'>" . $f_tomamuestra . "</td>                    	
                    </tr>

                    <tr>
                    	<td colspan='1' style='font:bold'><strong>NEC:</strong></td>
			<td colspan='2'>" . $txtnec . "<input type='hidden' id='idexpedientenec' name='idexpedientenec' value='".$txtnec."'/></td>
                        <td colspan='1'style='font:bold'><strong>Fecha Recepción:</strong></td>
                    	<td colspan='2' style='font:bold'>" . $row['fecharecepcion'] . "<input name='suEdad' id='suEdad'  type='hidden'  value='" . $rowpa['fecha_nacimiento'] . "'/></td>
                    </tr>
		    <tr>
                        <td colspan='1' style='font:bold'><strong>Paciente:</strong></td>
                        <td colspan='2' >" . $rowpa['nombre'] . "</td>
			<td colspan='1' style='font:bold'><strong>Fecha Resultado:</strong></td>
			<td colspan='2'>" . $d_resultfin . "</td>
</tr>
                    </tr>
                    <tr>
			<td colspan='1' style='font:bold'><strong>Edad:</strong></td>
			<td colspan='2'>".$rowpa['edad']."
                            
                           <!-- <div id='divsuedad'>
                            </div>--></td>
			<td colspan='1' style='font:bold'><strong>Sexo:</strong></td>
			<td colspan='2'>" . $rowpa['sexo'] . "</td>
                    </tr>
                     </tr>
                    	<td colspan='1'style='font:bold'><strong>Procedencia:</strong></td>
                    	<td colspan='2'>" . $proce . "</td>
                    	<td colspan='1' style='font:bold'><strong>Servicio:</strong></td>
			<td colspan='2'>" . $origen . "</td>
                    </tr>
                    <tr>
                      <th style='font:bold;'><strong>Validado por:</strong></th>
                      <td colspan='5'>".$fila_empleado['empleado']."</td>
                    </tr>                      
                     <tr>
			<td colspan='6' align='center' >&nbsp;&nbsp;&nbsp;</td>
		    </tr>

		    </table>";
                         $Imprimir.="<table width='100%'  align='center' border='0' class='StormyWeatherFormTABLE'>
                        <tr>
                        <td colspan='7 align='center'><bold>&nbsp;DETALLE DE RESULTADOS</bold><br/><br></td>
                        </tr>
                        ";
        
        
        
                $v_resultfin=$filares['resultado'];
                $v_examen=$filares['nombre_examen'];
		$v_obserrecep=$filares['observacion'];
		$v_interpretacion=$filares['interpretacion'];
		$v_lectura=$filares['lectura'];
		$v_marca=$filares['marca'];
                
              
      
         $Imprimir.=" <tr>
                        <td align='center'>Prueba Realizada </td>
                        <td align='justify'>Resultado</td>
                        <td align='justify'>Unidades</td>
                        <td align='justify'>Rangos Normales </td>";
         
         $Imprimir.=" <td align='left' colspan='3'>Observaci&oacute;n</td>
                    </tr>
                    <tr><td colspan='7'><hr style='width:90%'></td></tr>
                    <tr>
                        <td align='center' style='font:bold'>".$v_examen."</td>
   <td align='justify'>".$v_resultfin."</td>
                        <td align='justify'>".$fila['unidades']."</td>
                        <td align='justify'>".$fila['rangoinicio']." - ".$fila['rangofin']."</td>
                        <td align='justify' colspan='3'>".$v_obserrecep."</td>
                    </tr>
                    <tr><td colspan='7'>&nbsp;</td></tr>";
          
         $met=$objdatos->buscarexamresult($iddetalle, $idsolicitud, $lugar, $idexamen, $sexo, $idedad);
         $cantmet=pg_num_rows($met);
         if ($cantmet>0){
            $Imprimir.="<tr><td colspan=7><hr style='width:100%'></td>"
                    . "<tr>
                        <td align='center'  style='font:bold'><i>Metodología</i></td>

                        <td align='justify'><i>Marca</i></td>
                        <td align='justify' colspan='2'><i>Lectura</td>
                        <td align='justify' colspan='3'><i>Observación</i></td>
                    </tr><tr><td colspan='7'> <hr style='width:90%'></td></tr>";
            while ($rowme=pg_fetch_array($met)){
                $Imprimir.="<tr>
                        <td align='center' style='font:bold'>".$rowme['nombre_metodologia']."</td>
                        <td align='justify' >".$rowme['marca']."</td>
                        <td align='justify' colspan='2'>".$rowme['lectura']."</td>
                        <td align='justify' colspan='3'>".$rowme['observacion']."</td>
                    </tr>"; 
                
            }
         }
          
         $Imprimir.="     <tr><td colspan='7'>&nbsp;</td></tr>
            </table>  
                    <table align='center' border='0'>
                    
                    <tr><td colspan=8><hr></td>
                    </tr>
                    <tr>
                        <td colspan='8' align='center' >
                        <button name='Imprimir'  id='Imprimir' value='Imprimir' Onclick='ImprimirPlantillaA(" . $idsolicitud . ",\"" . $idexamen . "\",\"" . $v_resultfin. "\",\"" . $d_resultfin . "\", \"" . $v_lectura . "\",\"" . $v_interpretacion . "\",\"" . $v_obserrecep. "\",\"" . $cmbEmpleadosfin . "\",\"" . $sexo . "\",\"" . $idedad . "\",\"" . $txtnec. "\",\"" . $proce. "\",\"" . $origen . "\",\"" . $iddetalle  . "\") ;' class='btn btn-primary'><span class='glyphicon glyphicon-print'></span>&nbsp;Vista Previa</button>
                            <button name='btnSalir' id='btnSalir' value='Cerrar' Onclick='Cerrar() ;' class='btn btn-primary' ><span class='glyphicon glyphicon-remove-circle'></span>&nbsp;Cerrar</button>
                            <button name='btnAdd' id='btnAdd' value='Agregar Examen' Onclick='addexamen() ;' class='btn btn-primary' ><span class='glyphicon glyphicon-plus-sign'></span>&nbsp;Agregar Examen</button><br><br/>
                        </td>
                    </tr></table>";
        echo $Imprimir;
	     
        break;
     
      case 14://LLENANDO COMBO DE CodigoPosibleResultados divCodResultado
        $idresultado = $_POST['idresultado'];
        $idexamen = $_POST['idexamen'];
        $IdEstandar = $_POST['idEstandar'];
        if ($idresultado!='xyz' && $idresultado!='x'){
         $consulta = $objdatos->BuscarCodResult($idresultado, $idexamen);
        $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1' style='width:100%' class='form-control height'>";
                      
        
        while ($row = pg_fetch_array($consulta)) {
            $resultado .="<option value='" . $row[0] . "'>" . $row[0].' - '.$row[1] . "</option>";
        }
        pg_free_result($consulta);
        $resultado.= "</select>"; 
       }
       else{
         // if ($idresultado=='x'){
              $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1' style='width:100%' class='form-control height'>"
                      . " <option value='0' selected >--Seleccione Resultado--</option>
                              ";
             $resscod=$objdatos->BuscarResultados($IdEstandar);

               while ($rows = pg_fetch_array($resscod)){
                       $resultado .= '<option value="' . $rows['idresultado'] . '">' . $rows['idresultado'] . '  -  ' . $rows['resultado'] . '</option>'; 
               }
               $resultado.= "</select>"; 
      //    }
//          else{
         /*   $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1' style='width:29%'>";
          $resultado .= "<option value='0'>Seleccione un Resultado </option>"
                  . "</select>"; */
//          }
//       $buscaEstandar=$objdatos->buscaEstandar($idexamen);
//          $resultado = "<select id='cmbResultado2' name='cmbResultado2' size='1' style='width:29%'>";
//          $resultado .= "<option value='0'>Seleccione un Resultado </option>";
//          $resscod=$objdatos->BuscarResultados($IdEstandar);
//          while ($rows = pg_fetch_array($resscod)){
//            $resultado .= '<option value="' . $rows['idresultado'] . '">' . $rows['idresultado'] . '  -  ' . $rows['resultado'] . '</option>'; 
//          }
//          $resultado .= "</select>"; 
//          
          
       }
        //echo $idresultado.' - '.$idexamen;
        
        echo $resultado;
      break;
      //fn Pg
      
      case 15://LLENANDO COMBO DE CodigoPosibleResultados 
        $idexametodologia = $_POST['idexametodologia'];
     //   $idexamen = $_POST['idexamen'];
       if ($idexametodologia!='0'){
         $consulta = $objdatos->PosibleResMetodo($idexametodologia);
         if (pg_num_rows($consulta)>0){
             $resultado = "<select id='idresultado' name='idresultado' size='1' style='width:96%' onchange='setCodResultado(this.value)' class='form-control height'>"
                     . "<option value='xyz'>Seleccione un resultado</option>";
             
          while ($row = pg_fetch_array($consulta)) {
            $resultado .="<option value='" . $row[0] . "'>" .$row[1] . "</option>";
        }
               $resultado.= "</select>";
         }
         else {
 $resultado = '<textarea  name="txtresultado" cols="50" size="43"  id="txtresultado" style="width:96%"  class="form-control  height placeholder"/></textarea><input type="hidden" id="idresultado" name="idresultado" value="x" style="width:96%"/>'; 
         }
       }
       else{
           $resultado = '<textarea  name="txtresultado" cols="50" size="43"  id="txtresultado" placeholder="Debe seleccionar una metodología" disabled style="width:96%"  class="form-control  height placeholder placeholder"/></textarea><input type="hidden" id="idresultado" name="idresultado" value="x" style="width:96%"/>'; 
       }
        //echo $idresultado.' - '.$idexamen;
        
        echo $resultado;
      break;
}
?>
