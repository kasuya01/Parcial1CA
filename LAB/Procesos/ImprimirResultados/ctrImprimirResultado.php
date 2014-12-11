<?php

session_start();
$usuario = $_SESSION['Correlativo'];
$lugar = $_SESSION['Lugar'];
$area = $_SESSION['Idarea'];
include ("clsImprimirResultado.php");

//variables POST

$opcion = $_POST['opcion'];

//creando los objetos de las clases
$objdatos = new clsImprimirResultado;

switch ($opcion) {
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
        $IdEstab = $_POST['IdEstab'];
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



        /* $query =  "SELECT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,NombreSubServicio AS Origen,
          NombreServicio AS Procedencia, mnt_expediente.IdNumeroExp as IdNumeroExp,
          CONCAT_WS(' ',PrimerApellido,NULL,SegundoApellido,',',PrimerNombre,NULL,SegundoNombre) AS NombrePaciente,
          DATE_FORMAT(FechaSolicitud,'%e/ %m / %Y') AS FechaSolicitud,sec_solicitudestudios.IdSolicitudEstudio,
          CASE sec_solicitudestudios.Estado
          WHEN 'D' THEN 'Digitada'
          WHEN 'R' THEN 'Recibida'
          WHEN 'P' THEN 'En Proceso'
          WHEN 'C' THEN 'Completa'
          END AS Estado,
          mnt_establecimiento.Nombre,mnt_establecimiento.IdEstablecimiento,
          FechaNacimiento,Sexo
          FROM sec_historial_clinico
          INNER JOIN sec_solicitudestudios   ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
          INNER JOIN mnt_empleados  ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
          INNER JOIN mnt_expediente  ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
          INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
          INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
          INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
          INNER JOIN lab_recepcionmuestra ON sec_solicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
          INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
          WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND";
          $ban=0;

          //VERIFICANDO LOS POST ENVIADOS

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

          if (!empty($_POST['IdEstab']))
          { $query .= " sec_historial_clinico.IdEstablecimiento ='".$_POST['IdEstab']."' AND";}

          if (!empty($_POST['IdServ']))
          { $query .= " mnt_subservicio.IdServicio ='".$_POST['IdServ']."' AND";}

          if (!empty($_POST['IdSubServ']))
          { $query .= " mnt_subservicio.IdSubServicio ='".$_POST['IdSubServ']."' AND";}

          if (!empty($_POST['fecharecep']))
          { $query .= " lab_recepcionmuestra.fecharecepcion='".$_POST['fecharecep']."' AND";} */

        if (!empty($_POST['IdEstab'])) {
            if ($_POST['IdEstab'] <> $lugar) {
                $cond1 .=$cond0 . "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
                $cond2 .=$cond0 . "  t02.id_establecimiento_externo = " . $_POST['IdEstab'] . " ";
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

        if (!empty($_POST['fechasolicitud'])) {
            $cond1 .= " and t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
            $cond2 .= " and  t02.fecha_solicitud = '" . $_POST['fechasolicitud'] . "' ";
        }

        if (!empty($_POST['fecha'])) {
            $cond1 .= " and t03.fecharecepcion = '" . $_POST['fecha'] . "'       ";
            $cond2 .= " and t03.fecharecepcion = '" . $_POST['fecha'] . "'       ";
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

        if ($ban == 0) {

            $cond1 = substr($cond1, 0, strlen($query) - 3);
            $cond2 = substr($cond2, 0, strlen($query) - 3);
        }

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
                t02.id, 
                TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                t06.numero AS idnumeroexp, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,
                t07.segundo_apellido,t07.apellido_casada) AS paciente,
                t20.servicio AS nombresubservicio,
                t13.nombre AS nombreservicio, 
                t14.nombre, 
                TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                CASE t02.estado 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY') as fecchaconsulta
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
            t02.id, 
            TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
            t06.numero AS idnumeroexp,
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
            t07.apellido_casada) AS paciente, 
            t11.nombre AS nombresubservicio, 
            t13.nombre AS nombreservicio, 
            t14.nombre,
            TO_CHAR(t02.fecha_solicitud, 'DD/MM/YYYY') AS fechasolicitud, 
            (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
            CASE t02.estado 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
            TO_CHAR(t15.fechahorareg, 'DD/MM/YYYY') as fecchaconsulta
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

        $consulta = $objdatos->BuscarSolicitudesPaciente($query);

        $RegistrosAMostrar = 10;
        $RegistrosAEmpezar = ($_POST['pag'] - 1) * $RegistrosAMostrar;
        $PagAct = $_POST['pag'];

        $consulta = $objdatos->consultarpag($query, $RegistrosAEmpezar, $RegistrosAMostrar);
        $NroRegistros = $objdatos->NumeroDeRegistros($query);

        $imprimir = "<table width='85%' border='0' align='center'>
			<tr>
				<td colspan='7' align='center' ><span style='color: #0101DF;'><h3><strong>TOTAL DE SOLICITUDES: " . $NroRegistros . "</strong></h3></span></td>
			</tr>
		</table> ";
        

        
        
        $imprimir.="<table width='85%' border='1' align='center'>
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
        $pos = 0;
        while ($row = pg_fetch_array($consulta)) {
            //$Idsolic=$row['IdSolicitudEstudio'];
            //$fecha=$objdatos->BuscarRecepcion($Idsolic);
            //$recepcion= pg_fetch_array($fecha);
            //$fechacita=$objdatos->BuscarCita($Idsolic);
            //$cita= pg_fetch_array($fechacita);
            //if (!empty($recepcion)){
            $imprimir .="<tr>
				<td>" . $row['fecharecepcion'] . "</td>";
            $imprimir .="<td><span style='color: #0101DF;'><a style ='text-decoration:underline;cursor:pointer;' onclick='MostrarDatos(" . $pos . ");'>" . $row['idnumeroexp'] . "</a>" .
                    "<input name='idsolicitud[" . $pos . "]' id='idsolicitud[" . $pos . "]' type='hidden' size='60' value='" . $row[0] . "' />" .
                    "<input name='idexpediente[" . $pos . "]' id='idexpediente[" . $pos . "]' type='hidden' size='60' value='" . $row['idnumeroexp'] . "' /></td>" .
                    "<input name='idestablecimiento[" . $pos . "]' id='idestablecimiento[" . $pos . "]' type='hidden' size='60' value='" . $IdEstab . "' />" .
                    "<td>" . htmlentities($row['paciente']) . "</td>
				 <td>" . htmlentities($row['nombresubservicio']) . "</td>
				 <td>" . htmlentities($row['nombreservicio']) . "</td>
				 <td>" . htmlentities($row['estabext']) . "</td>
				 <td>" . $row['estado'] . "</td>
				 <td>" . $row['fecchaconsulta'] . "</td>
			</tr>";

            $pos = $pos + 1;
        }

        pg_free_result($consulta);

        $imprimir .= "<input type='hidden' name='oculto' id='text' value='" . $pos . "' /> 
  
		</table>";
        echo $imprimir;

        //determinando el numero de paginas
        $PagAnt = $PagAct - 1;
        $PagSig = $PagAct + 1;

        $PagUlt = $NroRegistros / $RegistrosAMostrar;

        //verificamos residuo para ver si llevar� decimales
        $Res = $NroRegistros % $RegistrosAMostrar;
        //si hay residuo usamos funcion floor para que me
        //devuelva la parte entera, SIN REDONDEAR, y le sumamos
        //una unidad para obtener la ultima pagina
        if ($Res > 0)
            $PagUlt = floor($PagUlt) + 1;
        echo "<table align='center'>
		       <tr>
				<td colspan=3 align='center'> <strong>Pagina " . $PagAct . "/" . $PagUlt . "</strong> </td>
	               </tr>
		       <tr>
				<td><a onclick=\"BuscarDatos('1')\">Primero</a> </td>";
        //// desplazamiento

        if ($PagAct > 1)
            echo "<td> <a onclick=\"BuscarDatos('$PagAnt')\">Anterior</a> </td>";
        if ($PagAct < $PagUlt)
            echo "<td> <a onclick=\"BuscarDatos('$PagSig')\">Siguiente</a> </td>";
        echo "<td> <a onclick=\"BuscarDatos('$PagUlt')\">Ultimo</a></td></tr>
                 </table>";
        echo "<table align='center'>
			<tr align='center'><td  colspan='2' width='25%'>";
        $numPags = '';
        for ($i = 1; $i <= $PagUlt; $i++) {
            if ($pag == $i)
                $numPags .= "<a >$pag</a>";
            else
                $numPags .= "<a  href='javascript: BuscarDatos(" . $i . ")'>$i</a>&nbsp;";
        }
        echo $numPags . "</td></tr>
		</table>";



        break;

    case 2:  // solicitud estudios

        break;
    case 3:// muestra la solicitud
        include_once("clsImprimirResultado.php");
        //recuperando los valores generales de la solicitud
        $idexpediente = $_POST['idexpediente'];
        $idsolicitud = $_POST['idsolicitud'];
        $idEstab = $_POST['IdEstablecimiento'];





        //  echo $idexpediente."  ".$idsolicitud;
        //echo $idEstab;
        $consulta = $objdatos->DatosGeneralesSolicitud($idexpediente, $idsolicitud, $idEstab, $lugar);
        $row = pg_fetch_array($consulta);
        //obteniedo los datos generales de la solicitud
        //valores de las consultas
       
        $idsolicitudPadre = $row[0];
        $medico = $row['medico'];
        $idmedico = $row[1];
        $paciente = $row['paciente'];
        $edad = $row['edad'];
        $sexo = $row['sexo'];
        $precedencia = $row['nombreservicio'];
        $origen = $row['nombresubservicio'];
        $ConocidoPor = $row['conocidox'];
        //$DatosClinicos=$row['fecharecepcion'];
        $Estado = $row['estado'];
        $fecharecepcion=$row['fecharecepcion'];
        $FechaNac=$row['fechanac'];
        //echo $FechaNac;
        //recuperando los valores del detalle de la solicitud
        $consultadetalle = $objdatos->DatosGeneralesSolicitud($idexpediente, $idsolicitud,$lugar);
        $imprimir = "<form name='frmDatos'>
                    <table width='70%' border='0' align='center'>
			<tr>
				<td  colspan='4'>&nbsp;&nbsp;&nbsp;&nbsp</td>
			</tr>
			<tr>
				<td colspan='4' align='center' class='CobaltFieldCaptionTD'>
					<h3><strong>DATOS SOLICITUD</strong></h3></td>
			</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Establecimiento</td>
                                <td class='StormyWeatherDataTD' colspan='3'>" . $row['estabext'] . "</td>
			</tr>
		        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Paciente</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($paciente) . " 
                        	     <input name='txtpaciente' id='txtpaciente' type='hidden' size='' value=$paciente disabled='disabled' /></td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Conocido por</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($ConocidoPor) . " 
			     		<input name='txtpaciente' id='txtpaciente' type='hidden' size='70' value='" . $paciente . "' disabled='disabled' /></td>
		   	</tr>
			<tr>
				<td class='StormyWeatherFieldCaptionTD'>Edad</td>
				<td class='StormyWeatherDataTD'>" . htmlentities($edad) . " 
			     		<input name='txtpaciente' id='txtpaciente1' type='hidden' size='35' value='" . $edad . "' disabled='disabled' /></td>
				
                                    </div>
                                </td>
				<td class='StormyWeatherFieldCaptionTD'>Sexo</td>
				<td class='StormyWeatherDataTD'>$sexo<input type='hidden' name='txtsexo' value='" . $sexo . "' disabled='disabled' /></td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>Procedencia</td>
				<td class='StormyWeatherDataTD'>$precedencia <input name='txtprecedencia' id='txtprecedencia' 
				type='hidden' size='35' value='" . $precedencia . "' disabled='disabled' />
				<td class='StormyWeatherFieldCaptionTD'>Origen</td>
				<td class='StormyWeatherDataTD'>" . htmlentities($origen) . "
					<input name='txtorigen' id='txtorigen'  type='hidden' size='35' value='" . $origen . "' disabled='disabled' />
                                        <input name='idsolicitudPadre' id='idsolicitudPadre'  type='hidden' size='40' value='" . $idsolicitudPadre . "' disabled='disabled' />
					<input name='idsolicitud' id='idsolicitud'  type='hidden' size='40' value='" . $idsolicitud . "' disabled='disabled' />
					<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='" . $idexpediente . "' disabled='disabled' />
					<input name='fechanac' id='fechanac'  type='hidden'  value='" . $FechaNac . "' disabled='disabled' />
					
					
				</td>
                        </tr>
                        <tr>
				<td class='StormyWeatherFieldCaptionTD'>M&eacute;dico</td>
				<td colspan='3' class='StormyWeatherDataTD'>" . htmlentities($medico) . "
					<input name='txtmedico' id='txtmedico'  type='hidden' size='70' value='" . $medico . "' disabled='disabled' /></td>
                        </tr>
                        <tr>
                                <td class='StormyWeatherFieldCaptionTD'>Fecha Recepción</td>
                                <td colspan='3' class='StormyWeatherDataTD'>" . $fecharecepcion . "</td>
                        </tr>
                        <tr>


	<table width='70%' border='0' align='center'>
		<tr>
                            <td colspan='1' align='center' class='CobaltFieldCaptionTD'>
					<h3><strong>ESTUDIOS SOLICITADO</strong></h3>
                        
                        
		</tr>
		<tr>
			<td>
				<table border = 1 align='center' class='estilotabla'>
			   	<tr>
			   		<td class='CobaltFieldCaptionTD'>Imprimir</td>
			   		<td class='CobaltFieldCaptionTD'>IdExamen</td>
			   		<td class='CobaltFieldCaptionTD'>Examen</td>
			   		<td class='CobaltFieldCaptionTD'>IdArea</td>
			   		<td class='CobaltFieldCaptionTD'>Indicacion Medica </td>
			   		<td class='CobaltFieldCaptionTD'>Estado</td>
			   	</tr>";
        //<input name='fechasolicitud' id='fechasolicitud'  type='hidden' size='40' value='" . $fechasolicitud . "' disabled='disabled' />
        //<input name='idarea' id='idarea'  type='hidden' size='40' value='" . $idarea . "' disabled='disabled' />
        //<input name='procedencia[" . $pos . "]' type='hidden' id='procedencia[" . $pos . "]' value='" . $row['Precedencia'] . "'>
	//<input name='idorigen[" . $pos . "]' type='hidden' id='idorigen[" . $pos . "]' value='" . $row['Origen'] . "'>
        $pos = 0;
        while ($fila = pg_fetch_array($consultadetalle)) {
            $imprimir .= "<tr>";
            if ($fila['estado'] == "Resultado Completo") {
                $imprimir .="<td><img src='../../../Iconos/impresion.gif' style=\"text-decoration:underline;cursor:pointer;\" 
				onclick=\"ImprimirDatos('" . $fila['iddetallesolicitud'] . "','" . $fila['idsolicitudestudio'] . "','" . $fila['idplantilla'] . "','$idexpediente','" . $fila['codigo_area'] . "','" . $fila['codigo_examen'] . "','" . $row['sexo'] . "','" . $row['edad'] . "','" . $fila['idexamen'] . "','$FechaNac')\">
					</td>
					<td>" . htmlentities($fila['codigo_examen']) . "</td>
					<td>" . htmlentities($fila['nombre_examen']) . "
						<input name='idexpediente' id='idexpediente'  type='hidden' size='40' value='" . $idexpediente . "' disabled='disabled'/>	
						<input name='idsolicitud[" . $pos . "]' type='hidden' id='idsolicitud[" . $pos . "]' value='" . $fila['idsolicitudestudio'] . "'>
						<input name='idarea[" . $pos . "]' type='hidden' id='idarea[" . $pos . "]' value='" . $fila['idarea'] . "'>
						<input name='paciente[" . $pos . "]' type='hidden' id='paciente[" . $pos . "]' value='" . $row['paciente'] . "'>
						<input name='idexamen[" . $pos . "]' type='hidden' id='idexamen[" . $pos . "]' value='" . $fila['idexamen'] . "' disabled='disabled' />
					</td>	
					<td>" . $fila['codigo_area'] . "</td>";
                if (!empty($fila['indicacion'])) {
                    $imprimir .="<td>" . htmlentities($fila['indicacion']) . "</td>";
                } else {
                    $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>";
                }
                $imprimir .="<td>" . $fila['estado'] . "</td>
				</tr>";
            } else {
                $imprimir .="<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp</td>
					<td>" . $fila['codigo_examen'] . "</td>
					<td>" . htmlentities($fila['nombre_examen']) . "</td>	
					<td>" . $fila['codigo_area'] . "</td>";
                if (!empty($fila['indicacion'])) {
                    $imprimir .="<td>" . htmlentities($fila['indicacion']) . "</td>";
                } else {
                    $imprimir .="<td>&nbsp;&nbsp;&nbsp;&nbsp</td>";
                }
                $imprimir .="<td>" . $fila['estado'] . "</td></tr>";
            }

            $pos = $pos + 1;
        }
//<input name='iddetalle[" . $pos . "]' type='hidden' id='iddetalle[" . $pos . "]' value='" . $fila['IdDetalleSolicitud'] . "'>
        //<input name='IdExamen[" . $pos . "]' type='hidden' id='IdExamen[" . $pos . "]' value='" . $fila['IdExamen'] . "'>
        //<input name='idplantilla[" . $pos . "]' type='hidden' id='idplantilla[" . $pos . "]' value='" . $fila['IdPlantilla'] . "'>
        $imprimir .= "<input type='hidden' name='oculto' id='oculto' value='" . $pos . "' />
				</table></form>";
        echo $imprimir;
        pg_free_result($consultadetalle);
        break;
    case 4:

        include_once("clsImprimirResultado.php");
        $iddetalle = $_POST['iddetalle'];
        $idplantilla = $_POST['idplantilla'];
        $idsolicitud = $_POST['idsolicitud'];
        $idexpediente = $_POST['idexpediente'];

        // if ($dato == 1){	
        switch ($idplantilla) {
            case 'A':
                $Consulta = $objdatos->Obtener_datos($idsolicitud, $iddetalle);
                $area = pg_fetch_array($consulta);
                switch ($area) {
                    case 'QUI':

                        break;
                    default:
                        break;
                }


                echo "es plantilla A";
                break;
            case 'B':
                echo "es plantilla B";
                break;
            case 'C':
                echo "es plantilla C";
                break;
            case 'D':
                echo "es plantilla D";
                break;
            case 'E':
                echo "es plantilla E";
                break;
            //  echo $idsolicitud."--".$iddetalle;
        } /* $r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
          $result=pg_fetch_array($r);
          $idresultado=$result[0];
          if($dr=$objdatos->EliminarDetalleResultado($idresultado)==1){
          if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){
          if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true))
          echo "Resultado Eliminado";
          }
          }
          else{
          echo "No se pudo eliminar el registro";
          }

          // echo "ENTRO B,D,E";
          break;
          case 'C':

          $r=$objdatos->ObtenerIdResultado($idsolicitud,$iddetalle);
          //$idresultado=$result['IdResultado'];

          while($result = pg_fetch_array($r)){
          $idresultado=$result['IdResultado'];
          //$tr=$objetos->ObtenerTipoResultado($idresultado);
          //$tipo=pg_fetch_array($tr);
          $TipoResultado=$result['Resultado'];
          //echo $TipoResultado;
          switch($TipoResultado){
          case 'P':
          //	 while($result = pg_fetch_array($r)){
          $idresultado=$result['IdResultado'];
          $det=$objdatos->ObtenerIdDetalleRes($idresultado);
          $detalle=pg_fetch_array($det);
          $iddetalleres=$detalle[0];
          //		echo $idsolicitud."-".$iddetalle."-".$idresultado."-". $iddetalleres;
          if($dr=$objdatos->EliminarResultadoTarjeta($iddetalleres)==1){
          if($dr=$objdatos->EliminarDetalleResultado($idresultado)==1){
          if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){
          if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true))
          echo "Resultado Eliminado";
          }
          }
          }
          else{
          echo "No se pudo eliminar el registro";
          }

          break;
          case 'N':
          case 'O':
          if ($objdatos->EliminarResultado($idsolicitud,$iddetalle) == 1){
          if (($objdatos->ActualizarEstadoDetalle($iddetalle)==true)||($objdatos->ActualizarEstadoSolicitud($idsolicitud)==true)){
          echo "Resultado Eliminado";}

          }else{
          echo "No se pudo eliminar el registro";
          }
          break;
          }

          }// while

          break;

          }//switch de plantilla
          /*   }else{
          echo "NO HAY DATOS";} */
        // }*/
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
        $rslts .='<option value="0"> Seleccione Subespecialidad </option>';
        while ($rows = pg_fetch_array($dtserv)) {
            $rslts.= '<option value="' . $rows[0] . '" >' . htmlentities($rows[1]) . '</option>';
        }

        $rslts .='</select>';
        echo $rslts;
        break;
}//switch de opciones
?>