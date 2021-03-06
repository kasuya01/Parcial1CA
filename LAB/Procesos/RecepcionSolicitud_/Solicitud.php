<?php
@session_start();
$ROOT_PATH = $_SESSION['ROOT_PATH'];
?>
<html>
    <head>
        <meta http-equiv="Content-type" content="text/html;charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
        <link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
        <style type="text/css">
            <!--
            @media print{
                #botonimp{display:none;}
            }
            .Estilo5 {font-size: 10pt}
            .Estilo12 {font-size: 6pt}
            -->
        </style>
        <title>Datos de Solicitud</title>
        <script language="JavaScript" type="text/javascript" src="ajax_RecepcionSolicitud.js"></script>
         <?php include_once $ROOT_PATH."/public/css.php";?>
         <?php include_once $ROOT_PATH."/public/js.php";?>
        <script language="JavaScript" >
            function RecogeValor1()
            {
                var vtmp = location.search;
                var vtmp2 = vtmp.substring(1, vtmp.length);
                var query = unescape(top.location.search.substring(1));
                var getVars = query.split(/&/);
                for (i = 0; i < getVars.length; i++)
                {
                    if (getVars[i].substr(0, 5) == 'var1=')//loops through this array and extract each name and value
                        idexpediente = getVars[i].substr(5);
                    if (getVars[i].substr(0, 5) == 'var2=')
                        fechacita = getVars[i].substr(5);
                    if (getVars[i].substr(0, 5) == 'var3=')
                        idsolicitud = getVars[i].substr(5);
                    if (getVars[i].substr(0, 5) == 'var4=')
                        establecimiento = getVars[i].substr(5);
                }
            }

            function calc_edad()
            {
                var fecnac1 = document.getElementById("suEdad").value;
                var fecnac2 = fecnac1.substring(0, 10);
                var suEdades = calcular_edad(fecnac2);
                document.getElementById("divsuedad").innerHTML = suEdades;
            }
        </script>
    </head>
    <body onLoad="RecogeValor1();">
        <?php
        include_once("clsRecepcionSolicitud.php");
        //variables POST
        $idexpediente = $_GET['var1'];
        $fechacita    = $_GET['var2'];
        $idsolicitud1 = $_GET['var3'];
        $idestablecimiento = $_GET['var4'];

        $Nfecha     = explode("/", $fechacita);
        $Nfechacita = $Nfecha[2] . "-" . $Nfecha[1] . "-" . $Nfecha[0];
        $estado = 'R';

        $con = new ConexionBD;
        if ($con->conectar() == true) {
           
        $estado="select * from sec_solicitudestudios where id=".$idsolicitud1.";";
           $est=@pg_fetch_array(pg_query($estado));
           $estado_solicitud=$est['estado'];
           
            $aux_query  = "SELECT COUNT(id) AS numero FROM lab_proceso_establecimiento WHERE id_proceso_laboratorio = 3 and activo=true";
            $aux_result = @pg_query($aux_query);
            $sirecep=pg_fetch_array($aux_result);

            if($sirecep[0] === '0') {
                $estado_detalle   = "PM";
            } else {
                $estado_detalle   = "D";
            }

            $query = "SELECT DISTINCT t03.idempleado AS idmedico,
                             t03.nombreempleado AS nombremedico,
                             t08.nombre AS origen,
                             t02.id AS idsolicitudestudio,
                             (SELECT nombre
                              FROM ctl_atencion
                              WHERE id = t08.id_atencion_padre) AS precedencia,
                             t04.numero AS idnumeroexp,
                             CONCAT_WS(' ',t05.primer_nombre, t05.segundo_nombre,t05.tercer_nombre, t05.primer_apellido, t05.segundo_apellido, t05.apellido_casada) as nombrepaciente,
                             REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    AGE(t05.fecha_nacimiento::timestamp)::text,
                                                'years', 'años'),
                                            'year', 'año'),
                                        'mons', 'meses'),
                                    'mon', 'mes'),
                                'days', 'días'),
                             'day', 'día') AS edad,
                             t06.nombre AS sexo,
                             t13.nombre,
                             TO_CHAR(t05.fecha_nacimiento, 'DD/MM/YYYY') AS fechanacimiento,
                             t01.idestablecimiento,
                             t10.peso,
                             t10.talla,
                             t12.sct_name_es,
                             t05.conocido_por AS conocidopor
                      FROM  sec_historial_clinico 			     t01
                      INNER JOIN sec_solicitudestudios 		     t02 ON (t01.id = t02.id_historial_clinico)
                      LEFT  JOIN mnt_empleado 				     t03 ON (t03.id = t01.id_empleado)
                      INNER JOIN mnt_expediente 			     t04 ON (t04.id = t01.id_numero_expediente)
                      LEFT  JOIN mnt_paciente 				     t05 ON (t05.id = t04.id_paciente)
                      INNER JOIN ctl_sexo 				         t06 ON (t06.id = t05.id_sexo)
                      INNER JOIN mnt_aten_area_mod_estab 		 t07 ON (t07.id = t01.idsubservicio)
                      INNER JOIN ctl_atencion 			         t08 ON (t08.id = t02.id_atencion)
                      INNER JOIN cit_citas_serviciodeapoyo 		 t09 ON (t02.id = t09.id_solicitudestudios)
                      LEFT JOIN sec_signos_vitales t10 ON (t01.id = t10.id_historial_clinico) 
                      LEFT JOIN sec_diagnostico_paciente t11 ON (t01.id = t11.id_historial_clinico) 
                      LEFT JOIN mnt_snomed_cie10 t12 ON (t12.id = t11.id_snomed) 
                      INNER JOIN ctl_establecimiento 			 t13 ON (t13.id = t01.idestablecimiento)
                      INNER JOIN lab_tiposolicitud 			     t14 ON (t14.id = t02.idtiposolicitud)
                      INNER JOIN ctl_estado_servicio_diagnostico t15 ON (t15.id = t02.estado AND t15.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      WHERE t08.codigo_busqueda = 'DCOLAB' AND t04.numero = '$idexpediente'
                            AND t15.id = $estado_solicitud     AND t02.id = $idsolicitud1
                            AND t09.fecha = '$Nfechacita'";
                $result = @pg_query($query);
                if (pg_num_rows($result)==0){ // busqueda si el paciente es de referencia
                $query = "
                    SELECT t03.idempleado AS idmedico,
                             t03.nombreempleado AS nombremedico,
                             t08.nombre AS Origen,
                             t02.id AS idsolicitudestudio,
                             t17.nombre  AS Precedencia,
                             t04.numero AS idnumeroexp,
                             CONCAT_WS(' ',t05.primer_nombre, t05.segundo_nombre,t05.tercer_nombre, t05.primer_apellido, t05.segundo_apellido, t05.apellido_casada) as nombrepaciente,
                             TO_CHAR(NOW(), 'DD/MM/YYYY') as fecha,
                             REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    AGE(t05.fecha_nacimiento::timestamp)::text,
                                                'years', 'años'),
                                            'year', 'año'),
                                        'mons', 'meses'),
                                    'mon', 'mes'),
                                'days', 'días'),
                             'day', 'día') AS edad,
                             t06.nombre AS sexo,
                             t07.id AS idsubservicio,
                             t10.peso,
                             t10.talla,
                             t13.nombre,
                             TO_CHAR(t05.fecha_nacimiento, 'DD/MM/YYYY') AS fechanacimiento,
                             t01.id_establecimiento,
                             t14.tiposolicitud,
                             t12.codigo AS iddiagnostico1,
                             t12.diagnostico
                      FROM  sec_solicitudestudios                t01
                      INNER JOIN mnt_dato_referencia           	 t02 ON (t02.id = t01.id_dato_referencia)
                      LEFT  JOIN mnt_empleado                    t03 ON (t03.id = t02.id_empleado)
                      INNER JOIN mnt_expediente_referido         t04 ON (t04.id = t02.id_expediente_referido)
                      LEFT  JOIN mnt_paciente_referido           t05 ON (t05.id = t04.id_referido)
                      INNER JOIN ctl_sexo                        t06 ON (t06.id = t05.id_sexo)
                      INNER JOIN mnt_aten_area_mod_estab         t07 ON (t07.id = t02.id_aten_area_mod_estab)
                      INNER JOIN ctl_atencion                    t08 ON (t08.id = t07.id_atencion)
                      LEFT JOIN cit_citas_serviciodeapoyo        t09 ON (t01.id = t09.id_solicitudestudios)
                      LEFT JOIN sec_signos_vitales t10 ON (t01.id = t10.id_historial_clinico) 
                      LEFT JOIN sec_diagnostico_paciente t11 ON (t01.id = t11.id_historial_clinico) 
                      LEFT JOIN mnt_snomed_cie10 t12 ON (t12.id = t11.id_snomed) 
                      INNER JOIN ctl_establecimiento             t13 ON (t13.id = t01.id_establecimiento)
                      INNER JOIN lab_tiposolicitud               t14 ON (t14.id = t01.idtiposolicitud)
                      INNER JOIN ctl_estado_servicio_diagnostico t15 ON (t15.id = t01.estado AND t15.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                        INNER JOIN mnt_area_mod_estab 		 t16 ON (t16.id = t07.id_area_mod_estab)
                      INNER JOIN ctl_area_atencion		 t17 ON (t17.id = t16.id_area_atencion)
                      WHERE t04.numero = '$idexpediente' AND t15.id = $estado_solicitud AND t01.id = $idsolicitud1
                            AND t09.fecha = '$Nfechacita'";
                $result = @pg_query($query);
            }
            $row = pg_fetch_array($result);

            //valores de las consultas
            $medico            = $row['nombremedico'];
            $idmedico          = $row['idmedico'];
            $paciente          = $row['nombrepaciente'];
            $edad              = $row['edad'];
            $FechaNac          = $row['fechanacimiento'];
            $sexo              = $row['sexo'];
            $precedencia       = $row['precedencia'];
            $origen            = $row['origen'];
            $idsolicitud       = $row['idsolicitudestudio'];
            $establecimiento   = $row['nombre'];
            $IdEstablecimiento = $row['idestablecimiento'];
            $Peso              = $row['peso'];
            $Talla             = $row['talla'];
            $Diagnostico       = $row['sct_name_es'];
            $ConocidoPor       = $row['conocidopor'];

           /*$querydetalle = "SELECT t09.numero AS idnumeroexp,
                                    t05.idarea,
                                    t03.codigo_examen AS idexamen,
                                    t03.nombre_examen AS nombreexamen,
                                    t01.indicacion,
                                    t02.fecha_solicitud AS fechasolicitud,
                                    t06.idsubservicio,
                                    t02.id AS idsolicitudestudio,
                                    t10.idestandar
                             FROM sec_detallesolicitudestudios 		    t01
                             INNER JOIN sec_solicitudestudios 		    t02 ON (t02.id = t01.idsolicitudestudio)
                             INNER JOIN lab_conf_examen_estab 		    t03 ON (t03.id = t01.id_conf_examen_estab)
                             INNER JOIN mnt_area_examen_establecimiento t04 ON (t04.id = t03.idexamen)
                             INNER JOIN ctl_area_servicio_diagnostico 	t05 ON (t05.id = t04.id_area_servicio_diagnostico)
                             INNER JOIN sec_historial_clinico 		    t06 ON (t06.id = t02.id_historial_clinico)
                             INNER JOIN cit_citas_serviciodeapoyo 		t07 ON (t02.id = t07.id_solicitudestudios)
                             INNER JOIN ctl_estado_servicio_diagnostico t08 ON (t08.id = t02.estado AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                             INNER JOIN mnt_expediente 			        t09 ON (t09.id = t02.id_expediente)
                             INNER JOIN ctl_examen_servicio_diagnostico t10 ON (t10.id = t04.id_examen_servicio_diagnostico)
                             INNER JOIN ctl_atencion 			        t11 ON (t11.id = t02.id_atencion)
                             LEFT JOIN mnt_dato_referencia                      t20 ON ( t20.id = t02.id_dato_referencia)
                             LEFT JOIN mnt_expediente_referido                  t21 ON (t21.id = t20.id_expediente_referido)
                             WHERE t11.codigo_busqueda = 'DCOLAB' AND (t09.numero = '$idexpediente' OR t21.numero = '$idexpediente') AND t07.fecha = '$Nfechacita'
                               AND t02.id = $idsolicitud
                               AND t01.estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$estado_detalle' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                             ORDER BY t05.idarea";*/

            $querydetalle = "SELECT t09.numero AS idnumeroexp,
                             t05.idarea,
                             t03.codigo_examen AS idexamen,
                             t03.nombre_examen AS nombreexamen,
                             t01.indicacion,
                             t02.fecha_solicitud AS fechasolicitud,
                             t06.idsubservicio,
                             t02.id AS idsolicitudestudio,
                             t10.idestandar, 
                             t01.f_tomamuestra
                      FROM sec_detallesolicitudestudios 		 t01
                      INNER JOIN sec_solicitudestudios                   t02 ON (t02.id = t01.idsolicitudestudio)
                      INNER JOIN lab_conf_examen_estab 		         t03 ON (t03.id = t01.id_conf_examen_estab)
                      INNER JOIN mnt_area_examen_establecimiento         t04 ON (t04.id = t03.idexamen)
                      INNER JOIN ctl_area_servicio_diagnostico 	         t05 ON (t05.id = t04.id_area_servicio_diagnostico)
                      LEFT JOIN sec_historial_clinico 		         t06 ON (t06.id = t02.id_historial_clinico)
                      INNER JOIN cit_citas_serviciodeapoyo 		 t07 ON (t02.id = t07.id_solicitudestudios)
                      INNER JOIN ctl_estado_servicio_diagnostico         t08 ON (t08.id = t02.estado AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      LEFT JOIN mnt_expediente 			         t09 ON (t09.id = t02.id_expediente)
                      
                      INNER JOIN ctl_examen_servicio_diagnostico t10 ON (t10.id = t04.id_examen_servicio_diagnostico)
                      INNER JOIN ctl_atencion 			         t11 ON (t11.id = t02.id_atencion)
                      LEFT JOIN mnt_dato_referencia                      t20 ON (t20.id = t02.id_dato_referencia)
                      LEFT JOIN mnt_expediente_referido                  t21 ON (t21.id = t20.id_expediente_referido)
                      

                      WHERE t11.codigo_busqueda = 'DCOLAB' AND (t09.numero = '$idexpediente' OR t21.numero = '$idexpediente') AND t07.fecha = '$Nfechacita'
                            AND t02.id = $idsolicitud1 AND t01.estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$estado_detalle' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      ORDER BY t05.idarea";
           
            $resultdetalle = @pg_query($querydetalle);
        }
        ?>
       <br>
        <form id="frmSolicitud">
           <table width="80%" border="0" align="center" class="table table-bordered table-condensed table-white no-v-border" >
                <tr>
                    <td colspan="6" class="CobaltFieldCaptionTD" align="center" ><h3>DATOS SOLICITUD<h3></td>
                                </tr>
                                <tr align="right">
                                </tr>
                                <tr>
                                   <td colspan="1"><strong>Establecimiento Solicitante:</strong></td>
                                    <td colspan="3" align="left"><?php echo htmlentities($establecimiento); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="1">No. Orden:</td>
                                    <td colspan="1"><?php echo $idsolicitud ?></td>
                                    <td>Fecha Recepci&oacute;n:</td>
                                    <td><?php echo htmlentities($fechacita); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="1">Paciente:</td>
                                    <td colspan="1"><?php echo htmlentities($paciente); ?>
                                        <input name="txtpaciente" type="hidden" value="<?php echo $paciente; ?>" disabled="disabled" />
                                        <input name="suEdad" id="suEdad"  type="hidden" value="<?php echo $row['fechanacimiento'] ?>"/>
                                    </td>
                                    <td colspan="1">Expediente:</td>
                                    <td colspan="1">
                                        <?php echo htmlentities($idexpediente); ?>
                                        <input name="txtExpediente" id="txtExpediente" type="hidden"  value=" <?php echo $idexpediente; ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1">Conocido Por:</td>
                                    <td colspan="3"><?php echo $ConocidoPor; ?></td>
                                </tr>
                                <tr>
                                    <td >Edad:</td>
                                    <td>
                                        <div id="divsuedad">
                                            <script language="JavaScript" type="text/javascript">
                                                calc_edad();
                                            </script>
                                        </div>
                                    </td>
                                    <td >Sexo:</td>
                                    <td ><?php echo $sexo; ?></td>
                                </tr>
                                <tr>
                                    <td>Procedencia:</td>
                                    <td>
                                        <?php echo htmlentities($precedencia); ?>
                                        <input name="txtprecedencia2" id="txtprecedencia" type="hidden"  value="<?php echo $precedencia; ?>"/>
                                        <input name="txtidsolicitud" id="txtidsolicitud" type="hidden" value="<?php echo $idsolicitud; ?>"/>
                                        <input name="txtfecha" id="txtfecha" type="hidden" value="<?php echo $fechacita; ?>"/>
                                    </td>
                                    <td >Origen:</td>
                                    <td colspan="2"> <?php echo htmlentities($origen); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="1">M&eacute;dico:</td>
                                    <td colspan="4"><?php echo htmlentities($medico); ?></td>
                                </tr>
                                <tr>
                                    <td>Diagnostico:</td>
                                    <td colspan="4"><?php echo htmlentities($Diagnostico); ?></td>
                                </tr>
                                <tr>
                                    <td>Peso:</td>
                                        <?php if (!empty($Peso)) { ?>
                                            <td><?php echo htmlentities($Peso); ?>&nbsp;&nbsp;Kg.c&nbsp;&nbsp;&nbsp;</td>
                                        <?php } else { ?>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <?php } ?>
                                    <td>Talla:</td>
                                        <?php if (!empty($Peso)) { ?>
                                            <td><?php echo htmlentities($Talla); ?>&nbsp;&nbsp;cm.</td>
                                        <?php } else { ?>
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                        <?php } ?>
                                </tr>
                                <tr>
                                    <td colspan="5">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="5" align="center" >ESTUDIOS SOLICITADOS</td></tr>
                                <tr>
                                    <td colspan="5">
                                       <table border = 1 align='center' class='table table-hover table-bordered table-condensed table-white'><thead>
                                            <tr>
                                                <th class="CobaltFieldCaptionTD">Código de prueba</th>
                                                <th class="CobaltFieldCaptionTD">Área</th>
                                                <th class="CobaltFieldCaptionTD">Código Examen</th>
                                                <th class="CobaltFieldCaptionTD">Nombre Examen </th>
                                                <th class="CobaltFieldCaptionTD">Indicación</th>
                                                <th class="CobaltFieldCaptionTD">F. Toma Muestra</th>
                                            </tr></thead><tbody>
                                            <?php
                                                while ($fila = pg_fetch_array($resultdetalle)) {
                                            ?>
                                                <tr>
                                                    <td ><?php echo $fila[8]; ?>  </td>
                                                    <td ><?php echo $fila[1]; ?>  </td>
                                                    <td> <?php echo $fila[2]; ?>  </td>
                                                    <td> <?php echo $fila[3]; ?>  </td>
                                                    <?php if (!empty($fila[4])) { ?>
                                                        <td><?php echo htmlentities($fila[4]); ?>  </td>
                                                    <?php } else { ?>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                                    <?php } ?>
                                                     <td> <?php echo $fila['f_tomamuestra']; ?>  </td>
                                                </tr>
                                            <?php
                                                }
                                            ?>
                                          </tbody>
                                        </table>
                                    </td>
                                </tr>
                                </table>
                                <br>
                                <table align="center">
                                    <tr>
                                        <td>
                                            <div id="botonimp"  >
                                               <button type="button" name="btnImpSolicitud" id="btnImpSolicitud" onclick="window.print();" class="btn btn-primary"><span class="glyphicon glyphicon-print"></span>&nbsp;Imprimir</button>
<!--                                                <input type="button" name="btnImpSolicitud" id="btnImpSolicitud" value="Imprimir" onClick="window.print();"/>-->
                                            </div>
                                        </td>
                                    </tr>
                                </table>
