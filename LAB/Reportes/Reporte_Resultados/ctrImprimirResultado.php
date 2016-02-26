<?php

session_start();
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
include ("clsReporteResultados.php");

//variables POST

$opcion = $_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsReporteResultados;

switch ($opcion) {


    case 11:

        $idEstablecimiento = $_POST['IdEstab'];    // $idEstablecimiento  id_establecimiento_externo
        $expediente = $_POST['idexpediente'];
         getExamnResult(/*$idHistorialClinico, $idDatoReferencia,*/ $expediente, $idEstablecimiento);



     break;
    case 1:
        /* $idexpediente=$_POST['idexpediente'];
          $primernombre=$_POST['primernombre'];
          $segundonombre=$_POST['segundonombre'];
          $primerapellido=$_POST['primerapellido'];
          $segundoapellido=$_POST['segundoapellido'];
          $fecharecep=$_POST['fecharecep'];
          $IdEstab=$_POST['IdEstab'];
          $IdServ=$_POST['IdServ'];
          $IdSubServ=$_POST['IdSubServ']; */



        $pag = $_POST['pag'];
        $registros = 20;
        $pag = $_POST['pag'];
        $inicio = ($pag - 1) * $registros;

        $ban = 0;
        $IdEstab = $_POST['IdEstab'];    // $idEstablecimiento  id_establecimiento_externo
        $IdServ = $_POST['IdServ'];
        $IdSubServ = $_POST['IdSubServ'];
        $idexpediente = $_POST['idexpediente'];
        //$fechasolicitud = $_POST['fechasolicitud'];
        $fecharecepcion = $_POST['fecharecep'];
        $PNombre = $_POST['primernombre'];
        $SNomre = $_POST['segundonombre'];
        $PApellido = $_POST['primerapellido'];
        $SApellido = $_POST['segundoapellido'];
        //  $TipoSolic      = $_POST['TipoSolic'];
        $cond1 = "";
        $cond2 = "";
        $query = "";
        $query2 = "";
        $where_with = "";

        $idexpediente = "'" . $idexpediente . "'";
        $cond0 = "and";



        if (!empty($_POST['IdEstab'])) {
            if ($_POST['IdEstab'] <> $lugar) {
                $cond1 .=$cond0 . "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
                $cond2 .=$cond0 . "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
            }
            else{
                $cond1 .=$cond0 . "  t02.id_establecimiento_externo = " . $lugar . " ";
                $cond2 .=$cond0 . "  t02.id_establecimiento_externo = " . $lugar . " ";
            }
        }

        if (!empty($_POST['IdSubServ'])) {
            $cond1 .= $cond0 . " t10.id = " . $_POST['IdSubServ'] . "    ";
            $cond2 .= $cond0 . " t10.id = " . $_POST['IdSubServ'] . "   ";
        }

        if (!empty($_POST['IdServ'])) {
            $cond1 .=$cond0 . "  t13.id  = " . $_POST['IdServ'] . "     ";
            $cond2 .=$cond0 . "  t13.id  = " . $_POST['IdServ'] . "     ";
            $where_with = "id_area_atencion = $IdServ AND ";
        }



        if (!empty($_POST['idarea'])) {
            $cond1 .= " and t08.id = " . $_POST['idarea'] . " ";
            $cond2 .= " and t08.id = " . $_POST['idarea'] . " ";
        }

        if (!empty($_POST['idexpediente'])) {
            $idexpediente = "'" . $idexpediente . "'";

            $cond1 .= "and t06.numero = '" . $_POST['idexpediente'] . "'    ";
            $cond2 .= "and t06.numero = '" . $_POST['idexpediente'] . "'   ";
        }

        if (!empty($_POST['idexamen'])) {
            $cond1 .= " and t04.id = " . $_POST['idexamen'] . " ";
            $cond2 .= " and t04.id = " . $_POST['idexamen'] . " ";
        }

       /* if (!empty($_POST['fechasolicitud'])) {
            $cond1 .= " and t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
            $cond2 .= " and  t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
        }*/

        if (!empty($_POST['fecharecep'])) {
            $cond1 .= " and t03.fecharecepcion = '" . $_POST['fecharecep'] . "'       ";
            $cond2 .= " and t03.fecharecepcion = '" . $_POST['fecharecep'] . "'       ";
        }

        if (!empty($_POST['primernombre'])) {

            $cond1 .= " and t07.primer_nombre  ILIKE  '" . $_POST['primernombre'] . "%'      ";
            $cond2 .= " and  t07.primer_nombre ILIKE  '" . $_POST['primernombre'] . "%'      ";
        }

        if (!empty($_POST['segundonombre'])) {
            $cond1 .= " and t07.segundo_nombre  ILIKE '" . $_POST['segundonombre'] . "%'       ";
            $cond2 .= " and t07.segundo_nombre  ILIKE '" . $_POST['segundonombre'] . "%'       ";
        }

        if (!empty($_POST['primerapellido'])) {
            $cond1 .= " and  t07.primer_apellido ILIKE '" . $_POST['primerapellido'] . "%'         ";
            $cond2 .="  and  t07.primer_apellido ILIKE '" . $_POST['primerapellido'] . "%'         ";
        }

        if (!empty($_POST['segundoapellido'])) {
            $cond1 .=" and t07.segundo_apellido ILIKE '" . $_POST['segundoapellido'] . "%'       ";
            $cond2 .=" and t07.segundo_apellido ILIKE '" . $_POST['segundoapellido'] . "%'       ";
        }

        if (!empty($_POST['TipoSolic'])) {
            $cond1 .= " and t17.idtiposolicitud = '" . $_POST['TipoSolic'] . "'  ";
            $cond2 .= " and t17.idtiposolicitud = '" . $_POST['TipoSolic'] . "'  ";
        }

        if ((empty($_POST['idexpediente'])) AND ( empty($_POST['primerapellido'])) AND ( empty($_POST['segundoapellido']))
                AND ( empty($_POST['primernombre'])) AND ( empty($_POST['segundonombre']))AND ( empty($_POST['IdEstab']))
                AND ( empty($_POST['IdServ'])) AND ( empty($_POST['IdSubServ'])) AND ( empty($_POST['fecharecep']))) {
            $ban = 1;
        }

//        if ($ban == 0) {
//
//            $cond1 = substr($cond1, 0, strlen($query) - 3);
//            $cond2 = substr($cond2, 0, strlen($query) - 3);
//        }


      $query = "WITH tbl_servicio AS (
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
                 SELECT
                distinct on (t02.id_historial_clinico)t02.id_historial_clinico,
                t02.id,
                t02.id_dato_referencia,
                TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                t06.numero AS idnumeroexp,
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                t07.segundo_apellido,t07.apellido_casada) AS paciente,
                t20.servicio AS nombresubservicio,
                t13.nombre AS nombreservicio,
                t14.nombre,
                TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud,
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
               (select descripcion as estado
               from sec_solicitudestudios t1
               join ctl_estado_servicio_diagnostico t2 on (t2.id=t1.estado)
               where id_historial_clinico=t02.id_historial_clinico
               group by id_historial_clinico, t2.id,descripcion
               order by t2.id asc
               limit 1) AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY') as fecchaconsulta, t02.id_establecimiento_externo
            FROM sec_solicitudestudios t02
            INNER JOIN lab_recepcionmuestra t03                 ON (t03.idsolicitudestudio=t02.id)
	    INNER JOIN mnt_expediente t06                       ON (t06.id = t02.id_expediente)
            INNER JOIN mnt_paciente t07                         ON (t07.id = t06.id_paciente)
	    INNER JOIN sec_historial_clinico t09                ON (t09.id = t02.id_historial_clinico)
            INNER JOIN mnt_aten_area_mod_estab t10              ON (t10.id = t09.idsubservicio)
            INNER JOIN ctl_atencion t11                         ON (t11.id = t10.id_atencion)
            INNER JOIN mnt_area_mod_estab t12                   ON (t12.id = t10.id_area_mod_estab)
            INNER JOIN ctl_area_atencion t13                    ON (t13.id = t12.id_area_atencion)
            INNER JOIN ctl_establecimiento t14                  ON (t14.id = t09.idestablecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15            ON (t15.id_solicitudestudios=t02.id)
            INNER JOIN lab_tiposolicitud t17                    ON (t17.id = t02.idtiposolicitud)
            INNER JOIN tbl_servicio t20                         ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            AND t02.id_establecimiento = $lugar $cond1


           UNION

            SELECT
            distinct on (t02.id_historial_clinico)t02.id_historial_clinico,
            t02.id,
            t02.id_dato_referencia,
            TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
            t06.numero AS idnumeroexp,
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
            t07.apellido_casada) AS paciente,
            t11.nombre AS nombresubservicio,
            t13.nombre AS nombreservicio,
            t14.nombre,
            TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud,
            (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            (select descripcion as estado
            from sec_solicitudestudios t1
            join ctl_estado_servicio_diagnostico t2 on (t2.id=t1.estado)
            where id_historial_clinico=t02.id_historial_clinico
            group by id_historial_clinico, t2.id,descripcion
            order by t2.id asc
            limit 1) AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY') as fecchaconsulta, t02.id_establecimiento_externo
            FROM sec_solicitudestudios t02
            INNER JOIN lab_recepcionmuestra t03                     ON (t03.idsolicitudestudio=t02.id)
            INNER JOIN mnt_dato_referencia t09                      ON t09.id=t02.id_dato_referencia
            INNER JOIN mnt_expediente_referido t06                  ON (t06.id = t09.id_expediente_referido)
            INNER JOIN mnt_paciente_referido t07                    ON (t07.id = t06.id_referido)
            INNER JOIN mnt_aten_area_mod_estab t10                  ON (t10.id = t09.id_aten_area_mod_estab)
            INNER JOIN ctl_atencion t11                             ON (t11.id = t10.id_atencion)
            INNER JOIN mnt_area_mod_estab t12                       ON (t12.id = t10.id_area_mod_estab)
            INNER JOIN ctl_area_atencion t13                        ON (t13.id = t12.id_area_atencion)
            INNER JOIN ctl_establecimiento t14                      ON (t14.id = t09.id_establecimiento)
            INNER JOIN cit_citas_serviciodeapoyo t15                ON (t15.id_solicitudestudios=t02.id)
            INNER JOIN lab_tiposolicitud t17 			    ON (t17.id = t02.idtiposolicitud)
            WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
            AND t02.id_establecimiento =$lugar $cond2   order by fecharecepcion desc  ";

        /* if ($ban==0)
          {   $query = substr($query ,0,strlen($query)-3);
          $query_search = $query. " ORDER BY IdSolicitudEstudio DESC";
          } */
        //echo $query_search;
        //$consulta=$objdatos->BuscarSolicitudesPaciente($query);
        //$NroRegistros= $objdatos->NumeroDeRegistros($query);


        //echo $cond1;
       // echo $cond2;

        $consulta = $objdatos->BuscarSolicitudesPaciente($query);

        $RegistrosAMostrar = 10;
        $RegistrosAEmpezar = ($_POST['pag'] - 1) * $RegistrosAMostrar;
        $PagAct = $_POST['pag'];

        $consulta = $objdatos->consultarpag($query, $RegistrosAEmpezar, $RegistrosAMostrar);
        $NroRegistros = $objdatos->NumeroDeRegistros($query);
if ( $NroRegistros==""){
    $NroRegistros=0;
    echo  "<table width='85%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center' ><span style='color: #0101DF;'><h3><strong>TOTAL DE SOLICITUDES: " . $NroRegistros . "</strong></h3></span></td>
			</tr>
		</table> ";

}else {
       echo  "<table width='85%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center' ><span style='color: #0101DF;'><h3><strong>TOTAL DE SOLICITUDES: " . $NroRegistros . "</strong></h3></span></td>
			</tr>
		</table> ";
}

        //<td>Fecha Recepci&oacute;n</td>

        echo "<div class='table-responsive' style='width: 100%;'>
           <table width='97%' border='1' align='center' data-table-enabled='true' class='table table-hover table-bordered table-condensed table-white'><thead>
                    <tr>
				<th>Fecha Recepci&oacute;n</th>
				<th style='width: 8%;'>NEC </th>
				<th>Nombre Paciente</th>
				<th>Origen</th>
				<th>Procedencia</th>
				<th>Establecimiento</th>
				<th>Estado Solicitud</th>
				<th>Fecha Consulta</th>
		     </tr></thead><tbody>";
        $pos = 0;
        if(@pg_num_rows($consulta))
        {
        while ($row = pg_fetch_array($consulta)) {
            //$Idsolic=$row['IdSolicitudEstudio'];
            //$fecha=$objdatos->BuscarRecepcion($Idsolic);
            //$recepcion= pg_fetch_array($fecha);
            //$fechacita=$objdatos->BuscarCita($Idsolic);
            //$cita= pg_fetch_array($fechacita);
            //if (!empty($recepcion)){
            echo "<tr>
				<td>" .htmlentities($row['fecharecepcion']). "</td>";
            echo "<td align='right'><span style='color: #0101DF; '><a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(" . $pos . ");'>" . $row['idnumeroexp'] . "</a>" .
                    "<input name='idhistorialclinico[" . $pos . "]' id='idhistorialclinico[" . $pos . "]' type='hidden' size='60' value='" . $row['id_historial_clinico'] . "' />" .
                    "<input name='iddatoreferencia[" . $pos . "]' id='iddatoreferencia[" . $pos . "]' type='hidden' size='60' value='" . $row['id_dato_referencia'] . "' />" .
                    "<input name='idsolicitud[" . $pos . "]' id='idsolicitud[" . $pos . "]' type='hidden' size='60' value='" . $row[1] . "' />" .
                    "<input name='idexpediente[" . $pos . "]' id='idexpediente[" . $pos . "]' type='hidden' size='60' value='" . $row['idnumeroexp'] . "' />" .
                    "<input name='idestablecimiento[" . $pos . "]' id='idestablecimiento[" . $pos . "]' type='hidden' size='60' value='" . $row['id_establecimiento_externo'] . "' /></td>" .
                    "<input name='subservicio[".$pos."]' id='subservicio[".$pos."]' type='hidden' size='60' value='".$row['nombresubservicio']."' />".
                    "<td>" . htmlentities($row['paciente']) . "</td>
				 <td>" . htmlentities($row['nombresubservicio']) . "</td>
				 <td>" . htmlentities($row['nombreservicio']) . "</td>
				 <td>" . htmlentities($row['estabext']) . "</td>
				 <td>" . $row['estado'] . "</td>
				 <td>" . $row['fecchaconsulta'] . "</td>
			</tr>";

            $pos = $pos + 1;
        }
         } else
            {
                 echo "<tr><td colspan='11'><span style='color: #575757;'>No se han encontrado resultados...</span></td></tr></tbody></table></div>";
            }

        @pg_free_result($consulta);

        echo  "<input type='hidden' name='oculto' id='text' value='" . $pos . "' />

		</tbody></table></div>";


        //echo $imprimir;


        // //determinando el numero de paginas
        // $PagAnt = $PagAct - 1;
        // $PagSig = $PagAct + 1;
        //
        // $PagUlt = $NroRegistros / $RegistrosAMostrar;
        //
        // //verificamos residuo para ver si llevar� decimales
        // $Res = $NroRegistros % $RegistrosAMostrar;
        // //si hay residuo usamos funcion floor para que me
        // //devuelva la parte entera, SIN REDONDEAR, y le sumamos
        // //una unidad para obtener la ultima pagina
        // if ($Res > 0)
        //     $PagUlt = floor($PagUlt) + 1;
        // echo "<table align='center'>
		//        <tr>
		// 		<td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
	    //            </tr>
		//        <tr>
		// 		<td><a onclick=\"BuscarDatos('1')\">Primero</a> </td>";
        // //// desplazamiento
        //
        // if ($PagAct > 1)
        //     echo "<td> <a onclick=\"BuscarDatos('$PagAnt')\">Anterior</a> </td>";
        // if ($PagAct < $PagUlt)
        //     echo "<td> <a onclick=\"BuscarDatos('$PagSig')\">Siguiente</a> </td>";
        // echo "<td> <a onclick=\"BuscarDatos('$PagUlt')\">Ultimo</a></td></tr>
        //          </table>";
        // echo "<table align='center'>
		// 	<tr align='center'><td  colspan='2' width='25%'>";
        //
        //
        // echo " <center> <ul class='pagination'>";
		//  $numPags ='';
		// 	 for ($i=1 ; $i<=$PagUlt; $i++){
        //
		// 			 echo " <li ><a  href='javascript: BuscarDatos(".$i.")'>$i</a></li>";
        //
		// 	 }
        //
        //          echo " </ul></center>";



        break;

    case 2:  // solicitud estudios

        break;
    case 3:// muestra la solicitud
       // include_once("clsImprimirResultado.php");
        //recuperando los valores generales de la solicitud
        $idexpediente       = $_POST['idexpediente'];
        $idsolicitud        = $_POST['idsolicitud'];
        $idHistorialClinico = $_POST['idHistorialClinico'];
        $idDatoReferencia   = $_POST['idDatoReferencia'];
        $idEstablecimiento  = $_POST['IdEstablecimiento'];
        $subservicio        =$_POST['subservicio'];

        //echo $subservicio;
            if($idDatoReferencia==""){

                $idDatoReferencia=0;

            }else {
                $idDatoReferencia=$idDatoReferencia;
            }

            if($idHistorialClinico==""){
                $idHistorialClinico=0;
            }else{
                $idHistorialClinico=$idHistorialClinico;
            }




       $resultgetExamnResult    =   getExamnResult($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
//       $resultgetDatosGenerales =   getDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);


       $consulta= $objdatos->obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
       $row = @pg_fetch_array($consulta);


        $nombre_establecimiento = $row['nombre_establecimiento'];
        $procedencia            = $row['procedencia'];
        $servicio               = $row['servicio'];
        $nombre_empleado        = $row['nombre_empleado'];
        $numero_expediente      = $row['numero_expediente'];
        $nombre_paciente        = $row['nombre_paciente'];
        $fecha_solicitud        = $row['fecha_solicitud'];


        //  DATOS GENERALES

        $imprimir = "<br> <form name='frmDatos'>
            <div class='table-responsive' style='width: 80%;'>
                <table width='70%' border='0' class='table table-hover table-bordered table-condensed table-white'>
			<thead>
                        <tr><td colspan='4' background-color='white'>";
        $imprimir.='<a href="pdfOrd_x_id.php?idexpediente='.$idexpediente.'&idsolicitud='.$idsolicitud.'&idHistorialClinico='.$idHistorialClinico.'&idDatoReferencia='.$idDatoReferencia.'&IdEstablecimiento='.$idEstablecimiento.'&subservicio='.$subservicio.'&title=\'PDF\'" target="_blank"><img align="right" src="../../../Imagenes/pdf2.png" title="Exportar a PDF" alt="Exportar a PDF" style="padding-right:5px"></a></td></tr>';

        $imprimir.="<tr>
                                            <th colspan='4' align='center' style='background-color: #428bca; color: #ffffff'>
                                                    <h3>  <center>  <strong>DATOS SOLICITUD</strong>   </center>  </h3></th>
                                    </tr>
                        </thead><tbody>
			<tr>

				<td>Establecimiento</td>
                                <td colspan='3'>" . $nombre_establecimiento . "</td>
			</tr>
                          <tr>
				<td>No. Expediente</td>
				<td>".$numero_expediente."</td>
			</tr>
                        <tr>
				<td>Nombre Paciente</td>
				<td>".$nombre_paciente."</td>
			</tr>
		        <tr>
				<td>Procedencia</td>
				<td colspan='1'>" . $procedencia . "
                        </tr>
                        <tr>
                                <td>Origen</td>
                                <td>".$servicio."</td>
                        </tr>
                        <tr>
				<td>M&eacute;dico</td>
				<td colspan='3'>" . $nombre_empleado . "
			</tr>

                        <tr>
                                <td>Fecha Recepción</td>
                                <td colspan='3'>" . $fecha_solicitud . "</td>
                        </tr>
                </tbody>
            </table>
        </div>";

        echo $imprimir;



       /*
        * Impresion de Resutlados
        */
        $print = '';

        $print .= MuestrasRechazadas($resultgetExamnResult['RM']);

        if (count($resultgetExamnResult['RC']) > 0) {
            foreach($resultgetExamnResult['RC'] as $area) {
                //----areas
               /* <div class="panel panel-primary">
                                        <div class="panel-heading mouse-pointer" role="tab" id="heading-{{ area.codigo }}" data-toggle="collapse" data-target="#area-{{ area.codigo }}" aria-expanded="false" aria-controls="area-{{ area.codigo }}">
                                            <h4 class="panel-title">
                                                {{area.nombre}}
                                            </h4>
                                        </div>
                                        <div id="area-{{ area.codigo }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-area-{{ area.codigo }}">
                                            <div class="panel-body">
                                                <div class="table-responsive">
                                                    {% set arrayPlantillas = ['A','B','C','D','E'] %}
                                                    {% for pType in arrayPlantillas %}
                                                        {% if area.plantillas[pType] is defined %}
                                                            {% include 'MinsalLaboratorioBundle:Custom:SecSolicitudestudios/bodyLayout.html.twig' with {'pType': pType} %}
                                                        {% endif %}
                                                    {% endfor %}
                                                </div>
                                            </div>
                                        </div>
                                    </div>*/
               // <div class="panel panel-success">...</div>




                /*$print.= "<div class='panel-heading mouse-pointer' role='tab' id='heading".$area['codigo']."' data-toggle='collapse' data-target='#".$area['codigo']."' ".$aria."-expanded='false' aria-controls='".$area.['codigo']."'>
                                            <h4 class='panel-title'>
                                                ".$area['nombre']."
                                            </h4>
                                        </div>
                                        <div id='".$area['codigo']."' class='panel-collapse collapse in' role='tabpanel' aria-labelledby='heading-".$area['codigo']."'>
                                            <div class='panel-body'> </div>
                                        </div>";*/

               $print.= "<div class='panel panel-info'>
                                        <div class='panel-heading mouse-pointer' role='tab' id='heading-URI' data-toggle='collapse' >
                                            <h4 class='panel-title' style='text-align:left'>
                                                ".$area['nombre']."
                                            </h4>
                                        </div>
                        </div>";

                $arrayPlantillas= ['A','B','C','D','E'] ;
                foreach ($arrayPlantillas as $pType) {
                    if(array_key_exists($pType, $area['plantillas'])) {
                        $print .= bodyLayout($area, $pType);
                    }
                }
            }
        } else {
          //  $print = 'Los examenes no han sido procesados aun...';
             {
                 $print = " <table > <tr><td colspan='11'><span style='color: #575757;'>Los examenes no han sido procesados aun...</span></td></tr></tbody></table></div>";
            }
        }


        echo $print;
        break;
    case 4:


        break;
    case 5://LLENANDO COMBO DE Examenes
        $rslts = '';

        $idarea = $_POST['idarea'];
        //echo $IdSubEsp;
        $dtExam = $objdatos->ExamenesPorArea($idarea, $lugar);
        $rslts = '<select name="cmbExamen" id="cmbExamen" class="MailboxSelect" style="width:250px">';
        $rslts .='<option value="0"> Seleccione Examen </option>';

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
        $dtIdEstab = $objdatos->LlenarCmbEstablecimiento($Idtipoesta);
        $rslts = '<select name="cmbEstablecimiento" id="cmbEstablecimiento" class="form-control height"  style="width:375px">';
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
        $rslts = '<select name="cmbSubServ" id="cmbSubServ" class="form-control height"  style="width:375px">';
        $rslts .='<option value="0"> Seleccione Subespecialidad </option>';
        while ($rows = pg_fetch_array($dtserv)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
        }

        $rslts .='</select>';
        echo $rslts;
        break;
}//switch de opciones


/**********************************


/*
 * funciones array
 */

 function getExamnResult($idHistorialClinico, $idDatoReferencia, $idEstablecimiento) {
        $objdatos = new clsReporteResultados;
        //$em = $this->container->get('doctrine')->getManager();
         //$resultados = $em->getRepository('MinsalSeguimientoBundle:SecSolicitudestudios')->obtenerResultadoSolicitudExamen($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
        if($idHistorialClinico === null || $idHistorialClinico === '') {
            $idHistorialClinico = 0;
        }

        if($idDatoReferencia === null || $idDatoReferencia === '') {
            $idDatoReferencia = 0;
        }

        $result = $objdatos->obtenerResultadoSolicitudExamen($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);
        if($resultados = pg_fetch_all($result)) {
            $result = array();
            $result['RC'] = array();
            $result['RM'] = array();
            //var_dump($resultados);
            foreach ($resultados as $row) {
                if($row['codigo_estado_detalle'] === 'RM') {
                    $newExam = array(
                            $row['id_examen'],
                            $row['codigo_examen'],
                            $row['nombre_examen']
                        );

                    $result['RM'] = addExamnToLayout($result['RM'], $newExam, $row, $row['codigo_plantilla']);
                } else {
        $id = $row['nombre_area'];

        if( ! isset( $result['RC'][$id] ) )
         {
            $result['RC'][$id] = array();
            $result['RC'][$id]['id']     = $row['id_area'];
            $result['RC'][$id]['codigo'] = $row['codigo_area'];
            $result['RC'][$id]['nombre'] = $row['nombre_area'];
        }

        if( ! isset($result['RC'][$id]['plantillas']) ){
        $result['RC'][$id]['plantillas'] = array();
                    }

                    $newPlantilla = array(
                            $row['id_plantilla'],
                            $row['codigo_plantilla'],
                            $row['nombre_plantilla']
                        );

        $result['RC'][$id]['plantillas'] = addLayoutToArea( $result['RC'][$id]['plantillas'], $newPlantilla, $row );
                }
    }

            return $result;
        }

    }



    function getDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento)
    {
       // $em = $this->container->get('doctrine')->getManager();
       //$datosGenerales = $em->getRepository('MinsalSeguimientoBundle:SecSolicitudestudios')->obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);


        // ¿ obtenerDatosGenerales ??
        $objdatos = new clsReporteResultados;
        $datosGenerales= $objdatos->obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento);



        return count($datosGenerales) > 0 ? $datosGenerales[0] : null;
    }





     function addLayoutToArea($plantillas, $newPlantilla, $row) {
if( ! isset($plantillas[ $newPlantilla[1] ]) )
    {
            $plantillas[ $newPlantilla[1] ] = array(
                                        'id'     => $newPlantilla[0],
                                        'codigo' => $newPlantilla[1],
                                        'nombre' => $newPlantilla[2]
                                                    );
    }

        if( ! isset($plantillas[ $newPlantilla[1] ]['examenes']) )
            {
                    $plantillas[ $newPlantilla[1] ]['examenes'] = array();
            }

        $newExam = array(
                $row['id_examen'],
                $row['codigo_examen'],
                $row['nombre_examen']
            );

        $plantillas[ $newPlantilla[1] ]['examenes'] = addExamnToLayout($plantillas[ $newPlantilla[1] ]['examenes'], $newExam, $row, $newPlantilla[1]);

return $plantillas;
}




     function addExamnToLayout($exams, $newExam, $row, $tipoPlantilla) {
        if( ! isset($exams[ $newExam[2] ]) )
        {
                $exams[ $newExam[2] ] = array(
                                        'id'                    => $newExam[0],
                                        'codigo'                => $newExam[1],
                                        'nombre'                => $newExam[2],
                                        'id_estado_detalle'     => $row['id_estado_detalle'],
                                        'codigo_estado_detalle' => $row['codigo_estado_detalle'],
                                        'nombre_estado_detalle' => $row['nombre_estado_detalle']
                                            );
        }

        if( ! isset($exams[ $newExam[2] ]['resultadoFinal']) ){
            $exams[ $newExam[2] ]['resultadoFinal'] = array();
        }

        switch ($row['codigo_estado_detalle']) {
            case 'RM':
                $exams[ $newExam[2] ]['motivo_rechazo'] = $row['nombre_observacion_rechazo'];
                break;
            case 'RC':
    $exams[ $newExam[2] ]['resultadoFinal'] = array(
                                            'id_empleado'           => $row['id_empleado'],
                                            'nombre_empleado'       => $row['nombre_empleado'],
                                            'fecha_resultado'       => $row['fecha_resultado'],
                                            'urgente'               => $row['urgente']
                                        );
                break;

            default:
                # code...
                break;
        }

        switch ($tipoPlantilla) {
            case 'A':
                if($row['codigo_estado_detalle'] === 'RC') {
                    $exams[ $newExam[2] ]['resultadoFinal']['id_resultado']             = $row['id_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['resultado']                = $row['resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['id_posible_resultado']     = $row['id_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['nombre_posible_resultado'] = $row['nombre_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['lectura']                  = $row['lectura'];
                    $exams[ $newExam[2] ]['resultadoFinal']['interpretacion']           = $row['interpretacion'];
                    $exams[ $newExam[2] ]['resultadoFinal']['observacion']              = $row['resultado_observacion'];
                    $exams[ $newExam[2] ]['resultadoFinal']['unidad']                   = $row['unidades'];
                    $exams[ $newExam[2] ]['resultadoFinal']['rango_inicio']             = $row['rango_inicio'];
                    $exams[ $newExam[2] ]['resultadoFinal']['rango_fin']                = $row['rango_fin'];
                }
                break;
            case 'B':
                if( ! isset($exams[ $newExam[2] ]['elementos']) ){
        $exams[ $newExam[2] ]['elementos'] = array();
        }

                if($row['codigo_estado_detalle'] === 'RC') {
                    $newElement = array(
                        $row['id_elemento'],
                        $row['nombre_elemento'],
                        $row['resultado_elemento'],
                        $row['id_posible_resultado_elemento'],
                        $row['nombre_posible_resultado_elemento'],
                        $row['unidad_elemento'],
                        $row['control_normal_elemento']
                    );

                    $exams[ $newExam[2] ]['elementos'] = addElementToExam($exams[ $newExam[2] ]['elementos'], $newElement, $row);
                }
                break;
            case 'C':
                if( ! isset($exams[ $newExam[2] ]['bacterias']) ){
        $exams[ $newExam[2] ]['bacterias'] = array();
        }

                if($row['codigo_estado_detalle'] === 'RC') {
                    $exams[ $newExam[2] ]['resultadoFinal']['id_resultado']             = $row['id_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['resultado']                = $row['resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['id_posible_resultado']     = $row['id_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['nombre_posible_resultado'] = $row['nombre_posible_resultado'];
                    $exams[ $newExam[2] ]['resultadoFinal']['id_observacion']           = $row['id_observacion_bacteria'];
                    $exams[ $newExam[2] ]['resultadoFinal']['nombre_observacion']       = $row['nombre_observacion_bacteria'];
                    $exams[ $newExam[2] ]['resultadoFinal']['codigo_observacion']       = $row['codigo_observacion_bacteria'];

                    if($row['id_observacion_bacteria'] === null) {
                        $newBacter = array(
                            $row['id_bacteria'],
                            $row['nombre_bacteria'],
                            $row['id_resultado'],
                            $row['resultado'],
                            $row['id_posible_resultado'],
                            $row['nombre_posible_resultado'],
                            $row['cantidad_bacterias']
                        );

                        $exams[ $newExam[2] ]['bacterias'] = addBacterToExamn($exams[ $newExam[2] ]['bacterias'], $newBacter, $row);
                    }
                }
                break;
//            case 'D':
//                if( ! isset($exams[ $newExam[2] ]['elementos']) ){
//        $exams[ $newExam[2] ]['elementos'] = array();
//        }
//
//                if($row['codigo_estado_detalle'] === 'RC') {
//                    $newElement = array(
//                        $row['id_elemento_tincion'],
//                        $row['nombre_elemento_tincion'],
//                        $row['nombre_cantidad_tincion'],
//                        $row['id_cantidad_tincion']
//                    );
//
//                    $exams[ $newExam[2] ]['elementos'] = addTincionElementToExam($exams[ $newExam[2] ]['elementos'], $newElement);
//                }
//                break;
            default:
                if( ! isset($exams[ $newExam[2] ]['procedimientos']) ){
        $exams[ $newExam[2] ]['procedimientos'] = array();
        }

                if($row['codigo_estado_detalle'] === 'RC') {
                    $exams[ $newExam[2] ]['resultadoFinal']['observacion'] = $row['resultado_observacion'];

                    $newProcedure = array(
                        $row['id_procedimiento'],
                        $row['nombre_procedimiento'],
                        $row['unidad_procedimiento'],
                        $row['rango_inicio_procedimiento'],
                        $row['rango_fin_procedimiento'],
                        $row['resultado_procedimiento'],
                        $row['id_posible_resultado_procedimiento'],
                        $row['nombre_posible_resultado_procedimiento'],
                        $row['control_diario_procedimiento']
                    );

                    $exams[ $newExam[2] ]['procedimientos'] = addProcedureToExam($exams[ $newExam[2] ]['procedimientos'], $newProcedure);
                }
                break;
        }

        /*Falta Logica para Resultados de la Metodologia*/
        return $exams;
    }

    function addElementToExam($elements, $newElement, $row) {
        if( ! isset($elements[ $newElement[1] ]) )
        {
            $elements[ $newElement[1] ] = array(
                                        'id'                       => $newElement[0],
                                        'nombre'                   => $newElement[1],
                                        'resultado'                => $newElement[2],
                                        'id_posible_resultado'     => $newElement[3],
                                        'nombre_posible_resultado' => $newElement[4],
                                        'unidad'                   => $newElement[5],
                                        'control_normal'           => $newElement[6]
                                                );
        }

        if( ! isset($elements[ $newElement[1] ]['subelementos']) )
            {
                    $elements[ $newElement[1] ]['subelementos'] = array();
            }

        $newSubelement = array(
            $row['id_subelemento'],
            $row['nombre_subelemento'],
            $row['resultado_subelemento'],
            $row['id_posible_resultado_subelemento'],
            $row['nombre_posible_resultado_subelemento'],
            $row['unidad_subelemento'],
            $row['rango_inicio_subelemento'],
            $row['rango_fin_subelemento'],
            $row['control_normal_subelemento']
        );

        $elements[ $newElement[1] ]['subelementos'] = addSubElementToElement($elements[ $newElement[1] ]['subelementos'], $newSubelement);

        return $elements;
    }





     function addSubElementToElement($subelements, $newSubelement) {
        if( ! isset($subelements[ $newSubelement[1] ]) )
            {
                $subelements[ $newSubelement[1] ] = array(
                                        'id'                       => $newSubelement[0],
                                        'nombre'                   => $newSubelement[1],
                                        'resultado'                => $newSubelement[2],
                                        'id_posible_resultado'     => $newSubelement[3],
                                        'nombre_posible_resultado' => $newSubelement[4],
                                        'unidad'                   => $newSubelement[5],
                                        'rango_inicio'             => $newSubelement[6],
                                        'rango_fin'                => $newSubelement[7],
                                        'control_normal'           => $newSubelement[8]
                                        );
            }

        return $subelements;
    }

    function addTincionElementToExam($elements, $newElement) {
        if( ! isset($elements[ $newElement[1] ]) )
            {
                $elements[ $newElement[1] ] = array(
                                        'id'          => $newElement[0],
                                        'nombre'      => $newElement[1],
                                        'cantidad'    => $newElement[2],
                                        'id_cantidad' => $newElement[3]
                                                    );
            }

        return $elements;
    }

    function addProcedureToExam($procedures, $newProcedure) {
        if( ! isset($procedures[ $newProcedure[1] ]) )
        {
                $procedures[ $newProcedure[1] ] = array(
                                        'id'                       => $newProcedure[0],
                                        'nombre'                   => $newProcedure[1],
                                        'unidad'                   => $newProcedure[2],
                                        'rango_inicio'             => $newProcedure[3],
                                        'rango_fin'                => $newProcedure[4],
                                        'resultado'                => $newProcedure[5],
                                        'id_posible_resultado'     => $newProcedure[6],
                                        'nombre_posible_resultado' => $newProcedure[7],
                                        'control_diario'           => $newProcedure[8]
                    );
        }

        return $procedures;
    }





    function addBacterToExamn($bacters, $newBacter, $row) {
        if( ! isset($bacters[ $newBacter[1] ]) )
            {
                    $bacters[ $newBacter[1] ] = array(
                                        'id'                       => $newBacter[0],
                                        'nombre'                   => $newBacter[1],
                                        'id_resultado'             => $newBacter[2],
                                        'resultado'                => $newBacter[3],
                                        'id_posible_resultado'     => $newBacter[4],
                                        'nombre_posible_resultado' => $newBacter[5],
                                        'cantidad'                 => $newBacter[6]
                                                    );
            }

        if( ! isset($bacters[ $newBacter[1] ]['tarjetas']) )
            {
                    $bacters[ $newBacter[1] ]['tarjetas'] = array();
            }

        $newCard = array(
            $row['id_tarjeta'],
            $row['nombre_tarjeta']
        );

        $bacters[ $newBacter[1] ]['tarjetas'] = addCardToBacter($bacters[ $newBacter[1] ]['tarjetas'], $newCard, $row);

        return $bacters;
    }




    function addCardToBacter($cards, $newCards, $row) {
        if( ! isset($cards[ $newCards[1] ]) )
            {
                            $cards[ $newCards[1] ] = array(
                                        'id'             => $newCards[0],
                                        'nombre'         => $newCards[1]);
            }

        if( ! isset($cards[ $newCards[1] ]['antibioticos']) ){
            $cards[ $newCards[1] ]['antibioticos'] = array();
        }

        $newAntibiotic = array(
            $row['id_antibiotico'],
            $row['nombre_antibiotico'],
            $row['resultado_antibiotico'],
            $row['lectura_antibiotico'],
            $row['id_posible_resultado_antibiotico'],
            $row['nombre_posible_resultado_antibiotico']
        );

        $cards[ $newCards[1] ]['antibioticos'] = addAntibioticToCard($cards[ $newCards[1] ]['antibioticos'], $newAntibiotic);

        return $cards;
    }





    function addAntibioticToCard($antibiotics, $newAntibiotic) {
        if( ! isset($antibiotics[ $newAntibiotic[1] ]) ){
$antibiotics[ $newAntibiotic[1] ] = array(
                                        'id'                       => $newAntibiotic[0],
                                        'nombre'                   => $newAntibiotic[1],
                                        'resultado'                => $newAntibiotic[2],
                                        'lectura'                  => $newAntibiotic[3],
                                        'id_posible_resultado'     => $newAntibiotic[4],
                                        'nombre_posible_resultado' => $newAntibiotic[5]
);
}

        return $antibiotics;
    }



    // funciones otras
function bodyLayout($area, $pType) {
    $html = '';



    foreach($area['plantillas'] as $plantilla) {
        if($plantilla['codigo'] === $pType) {
            foreach($plantilla['examenes'] as $examen) {
                $examStatus = $examen['codigo_estado_detalle'];
                $html .= headerLayout($examen, $examStatus);
               // $html .= MuestrasRechazadas($examen,$examStatus);
                if($examStatus === 'RC') {
                    $html .= plantillas($examen, $pType);
                }

                $html .= '</div>';
            }
        }
    }

    return $html;
}

function headerLayout($examen, $examStatus) {
    $header="";
    //$header.=  "headerLayout";

    $header.=  "
            <div class='panel panel-primary'>
                <div class='panel-heading'>
                    <h5 style='margin: 0px;'>
                        <div class='row'>
                            <div class='col-md-3 col-sm-3'>
                                ".$examen['nombre']."
                            </div>
                            <div class='col-md-2 col-sm-2'>
                                Estado: <strong>".$examen['nombre_estado_detalle']."</strong>
                            </div>";

    if($examStatus === 'RC') {
        $header.= "
                            <div class='col-md-3 col-sm-3'>
                                Validado por: <strong>".$examen['resultadoFinal']['nombre_empleado']."</strong>
                            </div>
                            <div class='col-md-2 col-sm-2'>
                                Fecha de Resultado: <strong>".$examen['resultadoFinal']['fecha_resultado']."</strong>
                            </div>
                            <div class='col-md-2 col-sm-2'>
                                Urgente: <strong>".$examen['resultadoFinal']['urgente']."</strong>
                            </div>";
    }

    $header.= "        </div>
                    </h5>
                </div>";

    return $header;
}

function  MuestrasRechazadas($rm) {
    $html = '';
    $html.= "<div class='panel panel-warning'>
                                        <div class='panel-heading mouse-pointer' role='tab' id='heading-URI' data-toggle='collapse' >
                                            <h4 class='panel-title' style='text-align:left;'>MUESTRAS RECHAZADAS
                                            </h4>
                                        </div>
                        </div>";
    $html.= "<div class='panel panel-primary'>
                                        <div class='panel-heading mouse-pointer' role='tab' id='heading- data-toggle='collapse' >


                        </div>";
    $html.="<table class='table table-hover  table-condensed table-white' >
                <thead>
                    <tr>
                        <th style='background-color:#FFFFF2 !important;'>Nombre del Examen</th>
                        <th style='background-color:#FFFFF2 !important;'>Estado</th>
                        <th style='background-color:#FFFFF2 !important;'>Motivo de Rechazo</th>
                    </tr>
                </thead>
                <tbody>";

    if(count($rm) > 0) {
        foreach($rm as $examen) {
            $html.= "<tr>
                        <td> ".$examen['nombre']."</td>
                        <td> ".$examen['nombre_estado_detalle']."</td>
                        <td> ".$examen['motivo_rechazo']."</td>
                    </tr>";
        }
    } else {
        $html.=" <tr>
                    <td>No existen examenes rechazados...</td>
                </tr>";
    }

    $html.= "</tbody>
        </table></div>";

    return $html;
}

function plantillas($examen, $pType){
 //  if ($pType!='C'){
    $plantilla = 'plantilla'.$pType;

    return $plantilla($examen);
 //  }
}
//PLANTILLA A
function plantillaA($examen) {
    $html = '';
    //$html.="plantillaA";
    $html.="
            <table class='table table-white''>
              <thead>
                  <tr>
                      <th>Resultado</th>
                      <th>Unidades</th>
                      <th>Rangos Normales</th>
                      <th>Observacion</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>";

    if (isset($examen['resultadoFinal']['id_posible_resultado']) && $examen['resultadoFinal']['id_posible_resultado'] !== '') {
        $html.= "<td>".$examen['resultadoFinal']['nombre_posible_resultado']."</td>";
      // $html.= "<td>".$examen['resultadoFinal']['resultado']."</td>";
    } else {
       $html.= "<td>".$examen['resultadoFinal']['resultado']."</td>";
        // $html.= "<td>".$examen['resultadoFinal']['nombre_posible_resultado']."</td>";
    }

    $html.= "
                    <td>".$examen['resultadoFinal']['unidad']."</td>
                    <td>".$examen['resultadoFinal']['rango_inicio']."-".$examen['resultadoFinal']['rango_fin']."</td>
                    <td>".$examen['resultadoFinal']['observacion']."</td>
                </tr>
            </tbody>
        </table>";

    return $html;
}
// PLANTILLA B
function plantillaB($examen) {
    $html= '';
   // $html.="plantillaB";
    $html.= "<table class='table table-hover table-bordered table-condensed table-white''>
                <thead>
                    <tr>
                        <th colspan='2'></th>
                        <th colspan=>Resultado</th>
                        <th colspan=>Unidades</th>
                        <th>Rangos de Referencia</th>
                        <th>Control Normal</th>
                    </tr>
                </thead>
                <tbody>";

    foreach ($examen['elementos'] as $elemento){
        $html.= "<tr>";

        if ($examen['codigo'] === 'H50' ){
            $html.= "
                 <td  colspan='2' class='pb-element'>".$elemento['nombre']."</td>
                    <td>";

            if($elemento['id_posible_resultado'] !== null || $elemento['id_posible_resultado'] !== '') {
                $html.= " ".$elemento['nombre_posible_resultado']."";
                $html.="    ".$elemento['resultado']."  ";
           } else {
                $html.="    ".$elemento['resultado']."  ";
            }
            $html.=" </td>
                    <td> ".$elemento['unidad']."</td>
                    <td>-</td>
                    <td>".$elemento['control_normal']."  ' ' ". $elemento['unidad']."</td>";
        } else {
            $html.= '<td colspan="6" class="pb-element">'.$elemento['nombre'].'</td>';
        }

        $html.= '</tr>';

        foreach($elemento['subelementos'] as $subelemento) {
            $html.= '<tr>
                    <td></td>
                    <td class="pb-subelement">'.$subelemento['nombre'].'</td>
                    <td>';

            if($subelemento['id_posible_resultado'] !== null || $subelemento['id_posible_resultado'] !== '') {
              // $html.= $subelemento['nombre_posible_resultado'];
              if ($subelemento['id_posible_resultado']==null){
                 $html.= "".$subelemento['resultado']."";
              }
              else{
                 $html.= $subelemento['nombre_posible_resultado'];
              }

            } else {
                $html.= "".$subelemento['resultado']."";
                 //$html.= $subelemento['nombre_posible_resultado'];
            }

            $html.= '</td>
                        <td>'.$subelemento['unidad'].'</td>';

            if($examen['codigo'] === 'H50') {
                $html.= '<td>-</td>
                <td>'.$subelemento['control_normal'].' - '.$elemento['unidad'].'</td>';
            } else {
                $html.= '<td>'.$subelemento['rango_inicio'].' - '.$subelemento['rango_fin'].'</td>
                <td></td>';
            }

            $html.= '</tr>';
        }
    }

    $html.= '</body>
            </table>';

    return $html;
}
// PLANTILLA C
function plantillaC($examen) {
    $html="";
    //$html.="plantillaC";
    $html.="<div class='row' style='font-size: 17px;padding-top: 20px;padding-bottom: 20px;'>
    <div class='col-md-12 col-sm-12'>
        Resultado: <strong>";
                             if( $examen['resultadoFinal']['id_posible_resultado'] !== null || $examen['resultadoFinal']['id_posible_resultado'] != '' ){
                                 $html.="   ". $examen['resultadoFinal']['nombre_posible_resultado']."   ";
                                } else {
                                          $html.=  "    ".$examen['resultadoFinal']['resultado']."   ";

                                }
               $html.= "</strong>
                </div>";

     if( $examen['resultadoFinal']['id_observacion'] !==(null) ){
             $html.="<div class='col-md-12 col-sm-12'>
                    Observacion: <strong>".$examen['resultadoFinal']['nombre_observacion']."</strong>
                </div>";
            }
            $html.="</div>";
 if (count($examen['bacterias']) > 0) {
 //if ($examen['bacterias'] |length > 0 ){
  $html.=  "<table class='table table-white'>
        <tbody>";
            foreach ($examen['bacterias'] as $bacteria){
           // {% for bacteria in examen.bacterias %}
              $html.="  <tr>
                    <td colspan='4'>
                        <div class='row'>
                            <div class='col-md-12 col-sm-12'>
                                <table class='heading-bact-pc'>
                                    <tbody>
                                        <tr>
                                            <td>Organismo:</td>
                                            <td style='padding-left:15px;'><strong>".$bacteria['nombre']."</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Cultivo con cuenta de Colonias:</td>
                                            <td style='padding-left:15px;'><strong>".$bacteria['cantidad']."</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr style='font-weight: bold;'>
                    <td></td>
                    <td>Antibiotico</td>
                    <td>Lectura</td>
                    <td>Interpretacion</td>
                </tr>";
               foreach ($bacteria['tarjetas'] as $tarjeta){
                        foreach ($tarjeta['antibioticos'] as $antibiotico){
                                $html.="  <tr>
                                      <td></td>
                                      <td>". $antibiotico['nombre']."</td>
                                      <td>".$antibiotico['lectura']."</td>
                                      <td>";
                                          //{% if antibiotico.id_posible_resultado is not null or antibiotico.id_posible_resultado != '' %}
                                           if($antibiotico['id_posible_resultado'] !== null || $antibiotico['id_posible_resultado'] !== '') {
                                              $html.="    ".$antibiotico['nombre_posible_resultado' ]."   ";
                                          }else {
                                            $html.=" ". $antibiotico['resultado']." " ;
                                          }
                                     $html.= "</td>
                                  </tr>";
                        }
                }
            }
       $html.= "</tbody>
                </table>";
       return $html;
}
}
//PLANTILLA D
function plantillaD($examen) {
   $html="";
    //$html.="plantillaD";
            $html.= "<table class='table table-white'>
                        <thead>
                            <tr>
                                <th>Elmento de Tincion</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                    <tbody>";
            foreach ($examen['elementos'] as $elemento){

                $html.="<tr>
                            <td>".$elemento['nombre']."</td>
                            <td>".$elemento['cantidad']."</td>
                        </tr>";
                }
            $html.=" </tbody>
                    </table>";

   return $html;
}
//PLANTILLA E
function plantillaE($examen) {
    //var_dump($examen);
$html="";
    //$html.="plantillaE";
        $html.="<table class='table table-white'>
            <thead>
                <tr>
                    <th>Prueba</th>
                    <th>Resultado</th>
                    <th>Unidades</th>
                    <th>Rango</th>
                    <th>Control Diario</th>
                </tr>
            </thead>
            <tbody>";
                    foreach ($examen['procedimientos'] as $procedimiento){
                        $html.=" <tr>
                        <td>".$procedimiento['nombre']."</td>
                        <td>";
                          if($procedimiento['id_posible_resultado'] !== null && $procedimiento['id_posible_resultado'] !== '') {

                                $html.= "".$procedimiento['nombre_posible_resultado']."  ";
                             } else {
                                $html.= $procedimiento['resultado'];
                            }
                       $html.= '</td>
                        <td>'. $procedimiento['unidad'] .'</td>
                        <td>'. $procedimiento['rango_inicio'].' -  '.$procedimiento['rango_fin'].'</td>
                        <td>'. $procedimiento['control_diario'].'</td>
                    </tr>';
                }
        $html.="  </tbody>
                    </table>
                    <div class='row' style='font-size: 17px;padding-top: 20px;padding-bottom: 20px;'>
                <div class='col-md-12 col-sm-12'>
                    Observaci&oacute;n: <strong>". $examen['resultadoFinal']['observacion']."</strong>
                </div>
            </div>";



     return $html;
}
?>
