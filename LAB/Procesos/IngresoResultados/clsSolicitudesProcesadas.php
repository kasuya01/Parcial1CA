<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsSolicitudesProcesadas {

    //constructor	
    function clsSolicitudesProcesadas() {
        
    }

    function DatosArea($area) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $NomAre = "SELECT nombrearea, administrativa FROM ctl_area_servicio_diagnostico where id =  $area";
            $resul = pg_query($NomAre) or die('La consulta fall&oacute;: ' . pg_error());
        }
        return $resul;
    }

    function Nombre_Establecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT nombre FROM ctl_establecimiento WHERE id = $lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function DatosEstablecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $conNom =  "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
            $resul = pg_query($conNom) or die('La consulta fall&oacute;: ' . pg_error());
        }
        return $resul;
    }

    function CalculoDias($fechanac) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT DATEDIFF(NOW( ),'$fechanac')";
            //  echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function ObtenerCodigoRango($dias) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT * FROM mnt_rangoedad WHERE $dias BETWEEN edadini AND edadfin
            AND idedad <>4";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function ObtenerIdEstandar($idexamen, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT IdEstandar
               FROM `lab_examenesxestablecimiento`
               INNER JOIN lab_examenes ON lab_examenesxestablecimiento.IdExamen = lab_examenes.IdExamen
               WHERE lab_examenesxestablecimiento.IdExamen = '$idexamen'
               AND IdEstablecimiento =$lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function LlenarCmbEstablecimiento($Idtipoesta) {
        
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT id, nombre FROM ctl_establecimiento where id_tipo_establecimiento = $Idtipoesta ORDER BY nombre";
            $dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
        }
        return $dt;
    }

//FUNCIÓN PARA LLENAR EL COMBO DE SERVICIOS
    function LlenarCmbServ($IdServ, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "WITH tbl_servicio AS (
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
                            WHERE id_area_atencion = $IdServ and t02.id_establecimiento = $lugar
                            ORDER BY 2)
                        SELECT id, servicio FROM tbl_servicio WHERE servicio IS NOT NULL";
            $dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
        }
        return $dt;
    }

    //FUNCION PARA MOSTRAR DATOS FIJOS DE LA PLANTILLA
    function MostrarDatosFijosPlantillaA($idexamen, $lugar, $sexo, $idedad) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT lab_examenes.IdExamen,NombreExamen,Unidades,RangoInicio,RangoFin 
                        FROM lab_datosfijosresultado 
                        INNER JOIN lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
                        WHERE IdEstablecimiento=$lugar AND lab_examenes.IdExamen='$idexamen' 
                        AND CURDATE() BETWEEN FechaIni 
                        AND IF(lab_datosfijosresultado.FechaFin ='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
                        AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) 
                        AND (idedad=4 OR idedad=$idedad)";
            //  echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function DatosExamenes($idsolicitud, $idarea, $lugar, $sexo, $idedad) {
        //echo $idsolicitud;
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT  sec_detallesolicitudestudios.IdDetalleSolicitud,
                            sec_detallesolicitudestudios.IdExamen,lab_examenes.NombreExamen,
                            lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
                            lab_datosfijosresultado.RangoFin,lab_examenes.IdArea,lab_examenes.IdEstandar   
                            FROM sec_detallesolicitudestudios 
                            INNER JOIN lab_examenes on sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                            INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                            INNER JOIN lab_datosfijosresultado on sec_detallesolicitudestudios.IdExamen=lab_datosfijosresultado.IdExamen
                            WHERE EstadoDetalle='PM' AND IdSolicitudEstudio=$idsolicitud AND sec_detallesolicitudestudios.IdExamen LIKE'$idarea%'and 		
                            lab_examenesxestablecimiento.IdPlantilla='A'  
                            AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                            AND CURDATE() BETWEEN lab_datosfijosresultado.FechaIni AND IF(lab_datosfijosresultado.FechaFin  = '0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
                            AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) AND (idedad=4 OR idedad=$idedad)
                            ORDER BY NombreExamen";

            //echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
            // echo $query
                return $result;
        }
    }

    function DatosExamenesImprimir($idsolicitud, $idarea, $lugar, $sexo, $idedad) {
        //echo $idsolicitud;
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT  sec_detallesolicitudestudios.IdDetalleSolicitud,
                            sec_detallesolicitudestudios.IdExamen,lab_examenes.NombreExamen,lab_resultados.Resultado,
                            lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
                            lab_datosfijosresultado.RangoFin,lab_resultados.observacion,lab_examenes.IdArea,lab_examenes.IdEstandar,
                            DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s')as FechaResultado    
                            FROM sec_detallesolicitudestudios 
                            INNER JOIN lab_examenes on sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                            INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                            INNER JOIN lab_datosfijosresultado on sec_detallesolicitudestudios.IdExamen=lab_datosfijosresultado.IdExamen
                            INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
                            WHERE EstadoDetalle='RC' AND sec_detallesolicitudestudios .IdSolicitudEstudio=$idsolicitud 
                            AND sec_detallesolicitudestudios.IdExamen LIKE'$idarea%'		
                            AND lab_examenesxestablecimiento.IdPlantilla='A'  
                            AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                            AND CURDATE() BETWEEN lab_datosfijosresultado.FechaIni AND IF(lab_datosfijosresultado.FechaFin  = '0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
                            AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) AND (idedad=4 OR idedad=$idedad)
                            ORDER BY NombreExamen";

            //echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
            // echo $query
                return $result;
        }
    }

    //FUNCION BUSCAR EMPLEADO QUE VALIDA RESULTADOS
    function BuscarEmpleadoValidador($responsable) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT NombreEmpleado FROM mnt_empleados WHERE IdEmpleado='$responsable' ";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    /* Funcion para obtener el nombre del Resultado del tabulador */

    function ObtenerNombreCodigo($cod) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = " SELECT Resultado FROM lab_codigosresultados WHERE IdResultado=$cod ";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function ObtenerFechaResultado1($IdSolicitud, $IdExamen, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') FROM lab_resultados 
                        WHERE IdSolicitudEstudio=$IdSolicitud AND IdExamen='$IdExamen' 
                        AND IdEstablecimiento=$lugar";
            // echo $query;
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//FUNCION FECHA DE RESULTADO
    function ObtenerFechaResultado($idsolicitud, $idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado
				FROM lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar
				AND IdExamen like '$idarea%'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA LLAMAR EMPLEADOS DE LABORATORIOS FILTRADOS POR AREA
    function BuscarEmpleados($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT mnt_empleado.id, CONCAT_WS(' ',nombre,apellido)
                 FROM mnt_empleado
                 INNER JOIN mnt_cargoempleados ON mnt_empleado.id_cargo_empleado = mnt_cargoempleados.id
                 WHERE mnt_cargoempleados.id_atencion = 98
                 AND IdArea <> 13
                 AND IdArea <> 7
                 AND IdArea <> 14
                 AND id_establecimiento =$lugar";
            // echo $query;
            $result = pg_query($query);

            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //Funcion para llener resultados de tabulación
    function LlenarResultados() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT IdResultado,Resultado FROM lab_codigosresultados";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA LLAMAR EMPLEADOS DE LABORATORIOS FILTRADOS POR AREA
    function BuscarNombreaArea($idarea) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT NombreArea FROM lab_areas WHERE IdArea='$idarea'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA MOSTRAR LOS DATOS GENERALES DE LA SOLICITUD PROCESADA
    function MostrarResultadoGenerales($idsolicitud, $idexamen, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT lab_recepcionmuestra.IdSolicitudEstudio, mnt_expediente.IdNumeroExp, 
        CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
	TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
        NombreArea,NumeroMuestra,DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,
        ctl_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento 
        FROM sec_detallesolicitudestudios 
	INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
	INNER JOIN lab_recepcionmuestra  ON  lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
	INNER JOIN sec_historial_clinico  ON  sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_expediente  ON mnt_expediente.IdNumeroExp= sec_historial_clinico.IdNumeroExp
	INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
	INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio=  sec_historial_clinico.IdSubServicio
	INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
	INNER JOIN lab_examenes  ON lab_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
	INNER JOIN lab_areas  ON  lab_areas.IdArea=lab_examenes.IdArea
        INNER JOIN ctl_establecimiento ON sec_historial_clinico.IdEstablecimiento=ctl_establecimiento.IdEstablecimiento
	WHERE sec_detallesolicitudestudios.IdExamen='$idexamen' AND lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud 
        AND sec_solicitudestudios.IdEstablecimiento=$lugar AND mnt_expediente.IdEstablecimiento=$lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function MostrarResultadoGenerales1($idsolicitud, $idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT lab_recepcionmuestra.IdSolicitudEstudio, mnt_expediente.IdNumeroExp, 
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
		TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
		NombreArea,NumeroMuestra,DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,sec_solicitudestudios.IdEstablecimiento, DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento
		FROM sec_detallesolicitudestudios 
		INNER JOIN sec_solicitudestudios ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
		INNER JOIN lab_recepcionmuestra ON lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		INNER JOIN sec_historial_clinico ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_expediente ON mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
		INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN lab_examenes ON lab_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
		INNER JOIN lab_areas ON lab_areas.IdArea=lab_examenes.IdArea
		INNER JOIN ctl_establecimiento ON sec_solicitudestudios.IdEstablecimiento=ctl_establecimiento.IdEstablecimiento
		WHERE lab_areas.IdArea='$idarea' AND  lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud AND ctl_establecimiento.IdEstablecimiento=$lugar
		AND mnt_expediente.IdEstablecimiento=$lugar ";
            // echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA MOSTRAR RESULTADOS DE EXAMEN PLANTILLA A
    function MostrarResultadoPlantillaA($idsolicitud, $idexamen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT C.IdSolicitudEstudio, H.IdNumeroExp,
                CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
		if(Sexo=1,'Masculino','Femenino') AS Sexo,
		TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
		D.IdExamen,NombreExamen,NombreArea,NumeroMuestra,RangoInicio,RangoFin,Resultado,A.Lectura,A.Interpretacion,
		Unidades,A.Observacion,Responsable,NombreEmpleado,A.FechaHoraReg as Fecha
		FROM lab_resultados AS A
		INNER JOIN lab_datosfijosresultado B ON A.IdExamen=B.IdExamen
		INNER JOIN lab_recepcionmuestra C ON C.IdRecepcionMuestra=A.IdRecepcionMuestra
		INNER JOIN lab_examenes D ON D.IdExamen=A.IdExamen
		INNER JOIN lab_areas E ON E.IdArea=D.IdArea
		INNER JOIN mnt_empleados F ON F.IdEmpleado=A.Responsable
		INNER JOIN sec_solicitudestudios G ON G.IdSolicitudEstudio=C.IdSolicitudEstudio
		INNER JOIN sec_historial_clinico H ON H.IdHistorialClinico=G.IdHistorialClinico
		INNER JOIN mnt_expediente I ON I.IdNumeroExp=H.IdNumeroExp
		INNER JOIN mnt_datospaciente J ON J.IdPaciente=I.IdPaciente 
		INNER JOIN mnt_subservicio K ON K.IdSubServicio= H.IdSubServicio
		INNER JOIN mnt_servicio L ON L.IdServicio= K.IdServicio
		WHERE A.IdExamen='$idexamen' AND C.IdSolicitudEstudio=$idsolicitud  
                AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) 
                AND (idedad=4 OR idedad=$idedad)";

            //echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO de muestras procesadas PM
    function ListadoSolicitudesPorArea($query_search) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = $query_search;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }

    function DetalleExamenes($idsolicitud, $idarea, $lugar, $sexo, $idedad) {
        //echo $idsolicitud;
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {

            $query = "SELECT sec_detallesolicitudestudios.IdDetalleSolicitud,lab_examenes.IdExamen,lab_examenes.NombreExamen,
			lab_resultados.Resultado,lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
			lab_datosfijosresultado.RangoFin   
			FROM sec_detallesolicitudestudios 
			INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
			INNER JOIN lab_examenesxestablecimiento on lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
			INNER JOIN lab_datosfijosresultado ON lab_examenes.IdExamen=lab_datosfijosresultado.IdExamen
			INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
			WHERE sec_detallesolicitudestudios.IdSolicitudEstudio=$idsolicitud AND lab_examenes.IdExamen LIKE'$idarea%'
			AND lab_examenesxestablecimiento.IdPlantilla='A' 
			AND sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_datosfijosresultado.IdEstablecimiento=$lugar AND CURDATE() 
			BETWEEN FechaIni AND IF(lab_datosfijosresultado.FechaFin ='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
                        AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) AND (idedad=4 OR idedad=$idedad)
			AND CURDATE() BETWEEN FechaIni AND IF(lab_datosfijosresultado.FechaFin ='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)
                        ORDER BY NombreExamen";
            //echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
            // echo $query;
                return $result;
        }
    }

    function ExamenesPorArea($idarea, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT t02.id,
                             t02.codigo_examen AS idexamen,
                             t02.nombre_examen AS nombreexamen
                      FROM mnt_area_examen_establecimiento t01
                      INNER JOIN lab_conf_examen_estab     t02 ON (t01.id = t02.idexamen)
                      WHERE t01.id_establecimiento = $lugar AND t01.id_area_servicio_diagnostico = $idarea
                          AND t02.condicion = 'H'
                      ORDER BY t02.nombre_examen";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    /* Función para obtener valores de referencia de los examenes */

    function BuscarResultado($idexamen, $idsolicitud, $iddetalle, $lugar) {

        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT *
                 FROM lab_resultados
                 WHERE IdSolicitudEstudio = $idsolicitud AND IdExamen = '$idexamen'
                 AND IdEstablecimiento =$lugar AND IdDetalleSolicitud=$iddetalle";
            // echo $query;
            $result = pg_fetch_array(pg_query($query));
            ;
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA GUARDAR RESULTADOS
    function InsertarResultadoPlantillaA($idexamen, $idsolicitud, $iddetalle, $resultado, $lectura, $observacion, $responsable, $lectura, $idrecepcion, $interpretacion, $observacion, $usuario, $codigo, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO lab_resultados(IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,
            IdRecepcionMuestra,Resultado,Lectura,Interpretacion,Observacion,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento)  	VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$resultado','$lectura',
	   '$interpretacion','$observacion','$responsable',$usuario,NOW(),$usuario,NOW(),$codigo,$lugar)";
            $query2 = "SELECT LAST_INSERT_ID();";

            $result = pg_query($query);
            $result2 = pg_query($query2);

            if ($row = pg_fetch_array($result2)) {
                $idultimo = $row[0];
                return $idultimo;
            } else {
                return 0;
            }
        }
    }

    /* Función para obtener valores de referencia de los examenes */

    function valores_referencia($idexamenes) {

        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select RangoInicio,RangoFin from lab_datosfijosresultado where IdExamen='$idexamenes' AND NOW() BETWEEN FechaIni AND FechaFin";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    /* funcion para ingresar los resultados de las plantillas A solo de Quimica */

    // function InsertarResultadoPlantillaA1($idexamen,$idsolicitud,$iddetalle,$resultado,$lectura,$idrecepcion,$interpretacion,$observacion,$responsable,$usuario,$tab,$lugar)
    function InsertarResultadoPlantillaA1($idexamen, $idsolicitud, $iddetalle, $resultado, $idrecepcion, $observacion, $responsable, $usuario, $tab, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) { /* $query="INSERT INTO lab_resultados(IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,IdRecepcionMuestra,Resultado,Lectura,
          Interpretacion,Observacion,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento)
          VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$resultado','$lectura','$interpretacion','$observacion','$responsable',
          $usuario,NOW(),$usuario,NOW(),$tab,$lugar)"; */
            $query = "INSERT INTO lab_resultados(IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,IdRecepcionMuestra,Resultado,Lectura,
			Interpretacion,Observacion,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento)
			VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$resultado','NULL','NULL','$observacion','$responsable',
			$usuario,NOW(),$usuario,NOW(),$tab,$lugar)";
            //echo $query;
            $query2 = "SELECT LAST_INSERT_ID();";

            $result = pg_query($query);
            $result2 = pg_query($query2);

            if ($row = pg_fetch_array($result2)) {
                $idultimo = $row[0];
                return $idultimo;
            } else {
                return 0;
            }
        }
    }

    //FUNCION PARA CAMBIAR EL ESTADO DEL DETALLLE DE LA SOLICITUD
    function CambiarEstadoDetalle($iddetalle) {
        $con = new ConexionBD;
        if ($con->conectar() == true) { //Estado RC--> Resultados Completo
            $query = "UPDATE sec_detallesolicitudestudios SET estadodetalle='RC' WHERE IdDetalleSolicitud=$iddetalle";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    function CambiarEstadoSolicitud($idsolicitud) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio=$idsolicitud AND EstadoDetalle <>'RC' AND EstadoDetalle <>'RM' ";
            $detalle = pg_num_rows(pg_query($query));
            if (empty($detalle)) {
                $query = "UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud";
                $result = @pg_query($query);
                return true;
            } else
                return false;
        }
    }

    //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
    //DATOS GENERALES DE LA SOLICITUD
    function DatosGeneralesSolicitud($idexpediente) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = " SELECT sec_solicitudestudios .IdSolicitudEstudio AS IdSolicitudEstudio,NumeroMuestra,Observacion,
                    mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico, NombreSubServicio AS Origen,
                    NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
                    CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
                    IF(Sexo=1,'Masculino','Femenino') AS Sexo, DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento
                    FROM sec_historial_clinico 		 
                    INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
                    INNER JOIN mnt_empleados 		 ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
                    INNER JOIN mnt_expediente 		 ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
                    INNER JOIN mnt_datospaciente 	 ON mnt_expediente .IdPaciente=mnt_datospaciente.IdPaciente  
                    INNER JOIN mnt_subservicio 		 ON mnt_subservicio .IdSubServicio= sec_historial_clinico.IDSubServicio
                    INNER JOIN mnt_servicio 		 ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
                    INNER JOIN lab_recepcionmuestra  ON sec_solicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio
                    WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //DATOS DEL DETALLE DE LA SOLICITUD
    function DatosDetalleSolicitud($idarea, $idsolicitud) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT B.IdExamen,NombreExamen,Indicacion 
				 FROM sec_detallesolicitudestudios AS A
				 INNER JOIN lab_examenes  AS B ON A.IdExamen=B.IdExamen
				 WHERE idSolicitudEstudio = $idsolicitud AND
				IdArea='$idarea'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    
    function consultarTipoSolicitud() {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT idtiposolicitud,
                             tiposolicitud
                      FROM lab_tiposolicitud";
            $result = pg_query($query);
            
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }

}
//CLASE
?>
