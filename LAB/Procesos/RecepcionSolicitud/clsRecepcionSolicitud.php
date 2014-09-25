<?php

include("../../../Conexion/ConexionBD.php");
include("../../../Conexion/ConexionBDLab.php");

//implementamos la clase lab_areas
class clsRecepcionSolicitud {

    //constructor
    function clsRecepcionSolicitud() { //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD
    }

    function DatosEstablecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $conNom = "SELECT t01.id_tipo_establecimiento AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                       FROM ctl_establecimiento t01
                       INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id= t01.id_tipo_establecimiento)
                       WHERE t01.id = $lugar";
            $resul = pg_query($conNom) or die('La consulta fall&oacute;: ' . pg_error());
        }
        return $resul;
    }

    function DatosArea($area) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $NomAre = "SELECT nombrearea, administrativa from ctl_area_servicio_diagnostico where idarea = '$area'";
            $resul = pg_query($NomAre) or die('La consulta fall&oacute;: ' . pg_error());
        }
        return $resul;
    }

    function LlenarEstablecimiento($Idtipo) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT id AS idestablecimiento, nombre FROM ctl_establecimiento where id_tipo_establecimiento = (SELECT id FROM ctl_tipo_establecimiento WHERE codigo = '$Idtipo') ORDER BY nombre";

            $dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
        }
        return $dt;
    }

    function BuscarSolicitudes($idexpediente, $fechacita, $lugar, $idEstablecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT t01.id AS idsolicitudestudio
                      FROM  sec_solicitudestudios                t01
                      INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
                      INNER JOIN sec_historial_clinico           t03 ON (t03.id = t01.id_historial_clinico)
                      INNER JOIN mnt_expediente                  t04 ON (t04.id = t01.id_expediente)
                      INNER JOIN ctl_estado_servicio_diagnostico t05 ON (t05.id = t01.estado)
                      INNER JOIN ctl_atencion                    t06 ON (t06.id = t05.id_atencion)
                      WHERE t04.numero = '$idexpediente' AND t05.idestado = 'D' AND t02.fecha = '$fechacita' AND t01.id_establecimiento = $lugar
                            AND t03.idestablecimiento = $idEstablecimiento AND t06.codigo_busqueda = 'DCOLAB'";
            //echo $query;

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function buscarTodasSolicitudes($idexpediente, $fechacita, $lugar, $idEstablecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT t05.id AS id_expediente,
                             t05.numero AS numero_expediente,
                             TO_CHAR(t02.fecha, 'DD/MM/YYYY') AS fecha_cita,
                             TO_CHAR(t03.fechaconsulta, 'DD/MM/YYYY') AS fecha_consulta,
                             CONCAT_WS(' ', t07.primer_apellido, t07.segundo_apellido, t07.apellido_casada) || ', ' || CONCAT_WS(' ', t07.primer_nombre, t07.segundo_nombre, t07.tercer_nombre) AS nombre_paciente,
                             CASE t04.idestado
                                WHEN 'D' THEN 'Digitada'
                                WHEN 'R' then 'Recibida'
                                WHEN 'P' then 'En Proceso'
                                WHEN 'C' then 'Completa'
                             END AS estado,
                             t03.idestablecimiento AS id_establecimiento
                      FROM sec_solicitudestudios                 t01
                      INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
                      INNER JOIN sec_historial_clinico           t03 ON (t03.id = t01.id_historial_clinico)
                      INNER JOIN ctl_estado_servicio_diagnostico t04 ON (t04.id = t01.estado AND t04.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      INNER JOIN mnt_expediente                  t05 ON (t05.id = t01.id_expediente)
                      INNER JOIN ctl_atencion                    t06 ON (t06.id = t01.id_atencion)
                      INNER JOIN mnt_paciente                    t07 ON (t07.id = t05.id_paciente)";

            $where = " WHERE t01.id_establecimiento = $lugar AND t03.idestablecimiento = $idEstablecimiento
                         AND t04.idestado = 'D'              AND t06.codigo_busqueda = 'DCOLAB'";

            $orderBy = " ORDER BY t05.numero";

            if($idexpediente !== '') {
                $where = $where." AND t05.numero = '$idexpediente'";
            }

            if($fechacita !== '') {
                $where = $where." AND t02.fecha = '$fechacita'";
            }

            $result = @pg_query($query.$where.$orderBy);

            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function NumeroDeRegistros($idexpediente, $fechacita, $lugar, $idEstablecimiento) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t01.id AS idsolicitudestudio
                      FROM sec_solicitudestudios                 t01
                      INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
                      INNER JOIN sec_historial_clinico           t03 ON (t03.id = t01.id_historial_clinico)
                      INNER JOIN mnt_expediente                  t04 ON (t04.id = t01.id_expediente)
                      INNER JOIN ctl_estado_servicio_diagnostico t05 ON (t05.id = t01.estado)
                      INNER JOIN ctl_atencion                    t06 ON (t06.id = t05.id_atencion)
                      WHERE t04.numero = '$idexpediente' AND t05.idestado = 'D' AND t02.fecha = '$fechacita' AND t01.id_establecimiento = $lugar
                            AND t03.idestablecimiento = $idEstablecimiento AND t06.codigo_busqueda = 'DCOLAB'";
            $numreg = pg_num_rows(pg_query($query));
            // echo $numreg;
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    function NumeroDeDetalle($idexpediente, $fechacita, $lugar, $idEstablecimiento) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT sec_solicitudestudios.IdNumeroExp, lab_examenes.IdArea AS IdArea ,
		lab_examenes.IdExamen AS IdExamen,
		NombreExamen,Indicacion,FechaSolicitud,sec_historial_clinico.IdSubServicio,
		sec_solicitudestudios.IdsolicitudEstudio
		FROM sec_detallesolicitudestudios
		INNER JOIN sec_solicitudestudios  ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		INNER JOIN lab_examenes  ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
		INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		INNER JOIN cit_citasxserviciodeapoyo ON cit_citasxserviciodeapoyo .IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_solicitudestudios.IdNumeroExp='$idexpediente'
		AND cit_citasxserviciodeapoyo .fecha='$fechacita' AND sec_detallesolicitudestudios.EstadoDetalle='D'
		AND sec_solicitudestudios.IdEstablecimiento=$lugar AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento
		ORDER BY lab_examenes.IdArea";
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

    function DatosGenerales($idexpediente, $fechacita, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT DISTINCT mnt_establecimiento.Nombre, mnt_empleados.IdEmpleado AS IdMedico, NombreEmpleado AS NombreMedico,
		NombreSubServicio AS Origen,NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp,
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		CURDATE() as Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
		IF(Sexo=1,'Masculino','Femenino') AS Sexo
		FROM sec_historial_clinico
		INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
		INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
		INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'
		AND sec_solicitudestudios.Estado='D' AND cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function BuscarDatosSolicitudes($idexpediente, $fechacita, $IdSolicitud, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT t03.idempleado AS idmedico,
                             t03.nombreempleado AS nombremedico,
                             t08.nombre AS Origen,
                             t02.id AS idsolicitudestudio,
                             (SELECT nombre
                              FROM ctl_atencion
                              WHERE id_atencion_padre = t08.id) AS Precedencia,
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
                             t01.idestablecimiento,
                             t14.tiposolicitud,
                             t12.codigo AS iddiagnostico1,
                             t12.diagnostico,
                             t05.conocido_por AS conocidopor
                      FROM  sec_historial_clinico                t01
                      INNER JOIN sec_solicitudestudios           t02 ON (t01.id = t02.id_historial_clinico)
                      LEFT  JOIN mnt_empleado                    t03 ON (t03.id = t01.id_empleado)
                      INNER JOIN mnt_expediente                  t04 ON (t04.id = t01.id_numero_expediente)
                      LEFT  JOIN mnt_paciente                    t05 ON (t05.id = t04.id_paciente)
                      INNER JOIN ctl_sexo                        t06 ON (t06.id = t05.id_sexo)
                      INNER JOIN mnt_aten_area_mod_estab         t07 ON (t07.id = t01.idsubservicio)
                      INNER JOIN ctl_atencion                    t08 ON (t08.id = t02.id_atencion)
                      INNER JOIN cit_citas_serviciodeapoyo       t09 ON (t02.id = t09.id_solicitudestudios)
                      LEFT  JOIN sec_examenfisico                t10 ON (t01.id = t10.idhistorialclinico)
                      LEFT  JOIN sec_diagnosticospaciente        t11 ON (t01.id = t11.idhistorialclinico)
                      LEFT  JOIN mnt_cie10                       t12 ON (t12.id = t11.iddiagnostico1)
                      INNER JOIN ctl_establecimiento             t13 ON (t13.id = t01.idestablecimiento)
                      INNER JOIN lab_tiposolicitud               t14 ON (t14.id = t02.idtiposolicitud)
                      INNER JOIN ctl_estado_servicio_diagnostico t15 ON (t15.id = t02.estado AND t15.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      WHERE t08.codigo_busqueda = 'DCOLAB' AND t04.numero = '$idexpediente'
                            AND t15.idestado = 'D' AND t02.id = $IdSolicitud AND t09.fecha = '$fechacita' AND t02.id_establecimiento = $lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA MOSTRAR DETALLE DE LA SOLICITUD
    function DetalleSolicitud($idexpediente, $fechacita) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT C.IdNumeroExp, B.IdArea AS IdArea ,B.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud
			FROM sec_detallesolicitudestudios AS A
			INNER JOIN sec_solicitudestudios AS C ON A.IdSolicitudEstudio=C.IdSolicitudEstudio
			INNER JOIN lab_examenes AS B ON A.idExamen=B.IdExamen
			INNER JOIN cit_citasxserviciodeapoyo  AS D ON D.IdSolicitudEstudio=C.IdSolicitudEstudio
			WHERE C.IdServicio ='DCOLAB' AND C.IdNumeroExp='$idexpediente' AND D.fecha='$fechacita'
			ORDER BY B.IdArea";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA IMPRIMIR VI�ETAS EN LA RECEPCION DE SOLICITUDES (UNICAMENTE CUANDO SE RECIBEN MUESTRAS DE ORINA O DE HECES)
    function VinetasRecepcion($idexpediente, $fechacita, $idsolicitud, $IdEstablecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT t01.nombrearea,
                             t05.tipomuestra,
                             t07.numero AS idnumeroexp,
                             CONCAT_WS(' ',t08.primer_nombre,t08.segundo_nombre,t08.tercer_nombre,t08.primer_apellido,t08.segundo_apellido,t08.apellido_casada) AS nombrepaciente,
                             t09.numeromuestra,
                             t03.nombre_examen AS nombreexamen,
                             t03.impresion,
                             t01.idarea,
                             t03.codigo_examen AS idexamen,
                             t11.idestandar,
                             t12.idestablecimiento,
                             t05.tipomuestra
                      FROM  ctl_area_servicio_diagnostico         t01
                      INNER JOIN mnt_area_examen_establecimiento  t02 ON (t01.id = t02.id_area_servicio_diagnostico)
                      INNER JOIN lab_conf_examen_estab            t03 ON (t02.id = t03.idexamen)
                      INNER JOIN sec_detallesolicitudestudios     t04 ON (t02.id = t04.idexamen)
                      INNER JOIN lab_tipomuestra                  t05 ON (t05.id = t04.idtipomuestra)
                      INNER JOIN sec_solicitudestudios            t06 ON (t06.id = t04.idsolicitudestudio)
                      INNER JOIN mnt_expediente                   t07 ON (t07.id = t06.id_expediente)
                      INNER JOIN mnt_paciente                     t08 ON (t08.id = t07.id_paciente)
                      INNER JOIN lab_recepcionmuestra             t09 ON (t06.id = t09.idsolicitudestudio)
                      INNER JOIN cit_citas_serviciodeapoyo        t10 ON (t06.id = t10.id_solicitudestudios)
                      INNER JOIN ctl_examen_servicio_diagnostico t11 ON (t11.id = t02.id_examen_servicio_diagnostico)
                      INNER JOIN sec_historial_clinico            t12 ON (t12.id = t06.id_historial_clinico)
                      INNER JOIN ctl_atencion                     t13 ON (t13.id = t01.id_atencion)
                      WHERE t07.numero = '$idexpediente' AND t10.fecha = '$fechacita'
                            AND t06.id = $idsolicitud    AND t04.idestablecimientoexterno = $IdEstablecimiento
                            AND t13.codigo_busqueda = 'DCOLAB'
                      --GROUP BY SUBSTRING(t03.codigo_examen,1,3), t05.tipomuestra, t03.impresion";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA MOSTRAR DETALLE DE LA SOLICITUD CUANDO HA SIDO PROCESADA
    //SIRVE TAMBIEN PARA IMPRIMIR LAS VI�ETAS DE LOS EXAMENES DEL DETALLE DE LA SOLICITUD EN EL MOMENTO DE LA TOMA DE MUESTRA
    function Detalle($idexpediente, $fechasolicitud) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT C.IdNumeroExp, B.IdArea AS IdArea ,B.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud
        			  FROM sec_detallesolicitudestudios AS A
        			  INNER JOIN sec_solicitudestudios AS C ON A.IdSolicitudEstudio=C.IdSolicitudEstudio
        			  INNER JOIN lab_examenes B ON A.idExamen=B.IdExamen
        			  WHERE IdServicio ='DCOLAB' AND C.IdNumeroExp='$idexpediente'
        			  AND FechaSolicitud='$fechasolicitud'
                      ORDER BY B.IdArea";


            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
    function CambiarEstadoSolicitud($idexpediente, $fechacita, $estado) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "UPDATE sec_solicitudestudios SET estado = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$estado' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      WHERE id_expediente = (SELECT id FROM mnt_expediente WHERE numero = '$idexpediente') AND fecha_solicitud = '$fechacita'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

//FUNCION PARA ENCUENTRA DATOS REGISTRADOS PARA LOS PARAMETROS RECIBIDOS
    function ResultadoDeBusqueda($idexpediente, $fechasolicitud, $estado) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT idSolicitudEstudio  	AS numreg
		FROM sec_historial_clinico       AS A
		INNER JOIN sec_solicitudestudios AS B ON A.IdHistorialClinico= B.IdHistorialClinico
		INNER JOIN mnt_empleados 	AS C ON A.IDEmpleado= C.IdEmpleado
		INNER JOIN mnt_expediente        AS D ON A.IdNumeroExp= D.IdNumeroExp
		INNER JOIN mnt_datospaciente 	AS E ON D.IdPaciente=E.IdPaciente
		INNER JOIN mnt_subservicio 	AS F ON F.IdSubServicio= A.IDSubServicio
		INNER JOIN mnt_servicio 	AS G ON G.IdServicio= F.IdServicio
		WHERE B.IdServicio ='DCOLAB' AND A.IdNumeroExp='$idexpediente' AND
		FechaSolicitud='$fechasolicitud' AND B.Estado='$estado'";

            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

//FUNCION PARA VERIFICAR ESTADO DE LA SOLICITUD
    function VerificarEstado($idexpediente, $fechacita) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT CASE estado
			WHEN 'D' THEN 'Digitada'
			WHEN 'R' THEN 'Recibida'
			WHEN 'P' THEN 'En Proceso'
			WHEN 'C' THEN 'Resultado de Estudios Completo'
			END AS estado
			FROM sec_solicitudestudios AS A
			INNER JOIN cit_citasxserviciodeapoyo B ON A.IdSolicitudEstudio=B.IdSolicitudEstudio
			WHERE IdNumeroExp='$idexpediente' AND fecha='$fechacita'
			AND A.IdServicio='DCOLAB";
            $result = @pg_query($query);

            if (!$result) {
                return false;
            } else {
                $row = pg_fetch_array($result);
                //return $result;
                return $row[0];
            }
        }
    }

    function NumeroMuestra() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT MAX(NumeroMuestra) + 1 AS NumeroMuestra FROM lab_recepcionmuestra
	         WHERE fecharecepcion=CURRENT_DATE";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else {
                $row = pg_fetch_array($result);
                return $row['NumeroMuestra'];
                ;
            }
        }
    }

    function BuscarDatosSolicitudesPiloto($idexpediente, $fechacita, $IdSolicitud, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT DISTINCT mnt_empleados.IdEmpleado as IdMedico,NombreEmpleado as NombreMedico,
	NombreSubServicio AS Origen,sec_solicitudestudios.IdSolicitudEstudio,
	NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp,
	CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
	IF(Sexo=1,'Masculino','Femenino') AS Sexo,mnt_subservicio.IdSubServicio,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento
	FROM sec_historial_clinico
	INNER JOIN sec_solicitudestudios ON sec_historial_clinico .IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
	INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente
	INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
	INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'
	AND sec_solicitudestudios.Estado='D' AND sec_solicitudestudios.IdSolicitudEstudio=$IdSolicitud
	AND cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar";
//echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function BuscarDetalleSolicitud($idexpediente, $fechacita, $IdSolicitud, $idEstablecimiento) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = "SELECT t09.numero AS idnumeroexp,
                             t05.idarea,
                             t03.codigo_examen AS idexamen,
                             t03.nombre_examen AS nombreexamen,
                             t01.indicacion,
                             t02.fecha_solicitud AS fechasolicitud,
                             t06.idsubservicio,
                             t02.id AS idsolicitudestudio,
                             t10.idestandar
                      FROM sec_detallesolicitudestudios 		 t01
                      INNER JOIN sec_solicitudestudios 		     t02 ON (t02.id = t01.idsolicitudestudio)
                      INNER JOIN lab_conf_examen_estab 		     t03 ON (t03.id = t01.id_conf_examen_estab)
                      INNER JOIN mnt_area_examen_establecimiento t04 ON (t04.id = t03.idexamen)
                      INNER JOIN ctl_area_servicio_diagnostico 	 t05 ON (t05.id = t04.id_area_servicio_diagnostico)
                      INNER JOIN sec_historial_clinico 		     t06 ON (t06.id = t02.id_historial_clinico)
                      INNER JOIN cit_citas_serviciodeapoyo 		 t07 ON (t02.id = t07.id_solicitudestudios)
                      INNER JOIN ctl_estado_servicio_diagnostico t08 ON (t08.id = t02.estado AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      INNER JOIN mnt_expediente 			     t09 ON (t09.id = t02.id_expediente)
                      INNER JOIN ctl_examen_servicio_diagnostico t10 ON (t10.id = t04.id_examen_servicio_diagnostico)
                      INNER JOIN ctl_atencion 			         t11 ON (t11.id = t02.id_atencion)
                      WHERE t11.codigo_busqueda = 'DCOLAB' AND t09.numero = '$idexpediente' AND t07.fecha = '$fechacita'
                            AND t02.id = $IdSolicitud      AND t01.idestablecimientoexterno = $idEstablecimiento
                            AND t01.estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = 'D' AND id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      ORDER BY t05.idarea";
            //echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function DatosSolicitud($idsolicitud) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT DISTINCT C.IdEmpleado,C.NombreEmpleado,F.IdServicio,NombreServicio,F.IdSubServicio,NombreSubServicio,
		 D.IdNumeroExp,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,
		FechaNacimiento,IF(Sexo=1,'M','F') AS Sexo, H.NumeroMuestra
		FROM sec_historial_clinico AS A
		INNER JOIN sec_solicitudestudios  AS B ON A.IdHistorialClinico= B.IdHistorialClinico
		INNER JOIN mnt_empleados AS C ON A.IDEmpleado= C.IdEmpleado
		INNER JOIN mnt_expediente AS D ON A.IdNumeroExp= D.IdNumeroExp
		INNER JOIN mnt_datospaciente AS E ON D.IdPaciente=E.IdPaciente
		INNER JOIN mnt_subservicio AS F ON F.IdSubServicio= A.IdSubServicio
		INNER JOIN mnt_servicio AS G ON G.IdServicio= F.IdServicio
		INNER JOIN lab_recepcionmuestra AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
		WHERE B.IdServicio ='DCOLAB' AND B.IdSolicitudEstudio='$Solicitud'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function DatosArchivo($idsolicitud) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {

            $query = "SELECT DISTINCT C.IdEmpleado, F.IdServicio, NombreSubServicio,
			NombreServicio, D.IdNumeroExp,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,
			FechaNacimiento,IF(Sexo=1,'M','F') AS Sexo, H.NumeroMuestra
			FROM sec_historial_clinico AS A
			INNER JOIN sec_solicitudestudios  AS B ON A.IdHistorialClinico= B.IdHistorialClinico
			INNER JOIN mnt_empleados AS C ON A.IDEmpleado= C.IdEmpleado
			INNER JOIN mnt_expediente AS D ON A.IdNumeroExp= D.IdNumeroExp
			INNER JOIN mnt_datospaciente AS E ON D.IdPaciente=E.IdPaciente
			INNER JOIN mnt_subservicio AS F ON F.IdSubServicio= A.IdSubServicio
			INNER JOIN mnt_servicio AS G ON G.IdServicio= F.IdServicio
			INNER JOIN lab_recepcionmuestra AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
			WHERE B.IdServicio ='DCOLAB' AND B.IdSolicitudEstudio='$Solicitud'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function Piloto($idexpediente, $fechacita, $lugar, $idEstablecimiento) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t03.piloto
                      FROM  sec_solicitudestudios                t01
                      INNER JOIN cit_citas_serviciodeapoyo       t02 ON (t01.id = t02.id_solicitudestudios)
                      INNER JOIN sec_historial_clinico           t03 ON (t03.id = t01.id_historial_clinico)
                      INNER JOIN mnt_expediente                  t04 ON (t04.id = t01.id_expediente)
                      INNER JOIN ctl_estado_servicio_diagnostico t05 ON (t05.id = t01.estado AND t05.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB'))
                      WHERE t04.numero = '$idexpediente' AND t05.idestado = 'D' AND t02.fecha='$fechacita' AND t01.id_establecimiento = $lugar
                            AND t03.idestablecimiento = $idEstablecimiento";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function obtenerEstado($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT COUNT(id) AS numero FROM lab_proceso_establecimiento WHERE id_proceso_laboratorio = 3";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
}
//CLASE
?>
