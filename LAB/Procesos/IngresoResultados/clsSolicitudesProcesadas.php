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
            $query = "select date (current_date)  - date ($fechanac) as dias";
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
            $query = "select * from ctl_rango_edad 
                    where cod_modulo='LAB'
                    and $dias BETWEEN edad_minima_dias AND edad_maxima_dias
                    AND id != 4";
            //echo $query;
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
    function MostrarDatosFijosPlantillaA($idexamen, $lugar, $sexo, $idedad, $idmetodologia) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select lce.id  as idexamen, codigo_examen, nombre_examen, unidades, rangoinicio, rangofin,nombre_metodologia, id_metodologia, nombre_reporta
                from lab_datosfijosresultado 	ldf
                join lab_conf_examen_estab	lce on (lce.id = ldf.id_conf_examen_estab)
                join lab_examen_metodologia 	lem on (lce.id = lem.id_conf_exa_estab)
                left join lab_metodologia		lme on (lme.id = lem.id_metodologia)
                where idestablecimiento=$lugar
                and id_conf_examen_estab=$idexamen
                and (current_date between fechaini and (case when fechafin is null then current_date else fechafin end))
                and (ldf.idsexo is null or ldf.idsexo=$sexo)
                and (idedad=4 or idedad=$idedad)
                and  (case $idmetodologia
			when 0 then (lem.id>0 or lem.id is null)
			else lem.id=$idmetodologia
			end)";
             // echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }
//Fn PG
    function DatosExamenes($idsolicitud, $idarea, $lugar, $sexo, $idedad, $idestab) {
        //echo $idsolicitud;
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "select sse.id as idsolicitudestudio, sds.id as iddetallesolicitud, sse.id_establecimiento_externo, 
                      id_area_servicio_diagnostico, sds.id_conf_examen_estab,nombre_examen, id_area_servicio_diagnostico as idarea, ces.idestandar,
                      ldf.unidades, ldf.rangoinicio, ldf.rangofin, lem.id as idexametodologia, nombre_reporta, (case when rangofin is null and rangoinicio is not null then ('Mayor a '||rangoinicio||' '||unidades) 
	     when rangoinicio is null and rangofin is not null then ('Menor a '||rangofin||' '||unidades) 
	else (rangoinicio||' - '||rangofin||' '||unidades) end )as rangos
                      from sec_solicitudestudios 		sse
                      join sec_detallesolicitudestudios	sds on (sse.id=sds.idsolicitudestudio)
                      join lab_conf_examen_estab		lex on (lex.id=sds.id_conf_examen_estab)
                      join mnt_area_examen_establecimiento	mae on (mae.id=lex.idexamen)
                      join ctl_examen_servicio_diagnostico	ces on (ces.id=mae.id_examen_servicio_diagnostico)
            left join lab_datosfijosresultado 	ldf on (lex.id=ldf.id_conf_examen_estab)
            join lab_examen_metodologia 	lem on (lex.id=lem.id_conf_exa_estab)
            where sse.id=$idsolicitud
            and id_area_servicio_diagnostico=$idarea
            and estadodetalle=5 
            and idplantilla=1
            and ldf.idestablecimiento=$idestab
            and (current_date between fechaini and (case when fechafin is null then current_date else (fechafin - 1) end))
            and (ldf.idsexo is null or ldf.idsexo=$sexo)
                            and (idedad=4 or idedad=$idedad)
            and sse.id_establecimiento=$lugar
            and lem.activo=true
            order by nombre_examen;";

           // echo '<br><br>---'.$query.'---s<br><br>';
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
          /*  $query = "SELECT  sec_detallesolicitudestudios.IdDetalleSolicitud,
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
                            ORDER BY NombreExamen";*/
            $query = "select t01.id as iddetallesolicitud, t01.id_conf_examen_estab, t02.nombre_examen, t05.resultado, t04.unidades, 
t04.rangoinicio, t04.rangofin, t05.observacion, t03.id_area_servicio_diagnostico, t07.idestandar,
to_char(t05.fecha_resultado, 'dd/mm/yyyy') as fecharesultado, t06.nombre_reporta,(case when rangofin is null and rangoinicio is not null then ('Mayor a '||rangoinicio||' '||unidades) 
	     when rangoinicio is null and rangofin is not null then ('Menor a '||rangofin||' '||unidades) 
	else (rangoinicio||' - '||rangofin||' '||unidades) end )as rangos
                    from sec_detallesolicitudestudios 	t01
                    join lab_conf_examen_estab 		t02 on (t02.id=t01.id_conf_examen_estab)
                    join mnt_area_examen_establecimiento	t03 on (t03.id=t02.idexamen)
                    join lab_datosfijosresultado		t04 on (t02.id=t04.id_conf_examen_estab)
                    join lab_resultados			t05 on (t01.id=t05.iddetallesolicitud)
                    join lab_examen_metodologia		t06 on (t02.id=t06.id_conf_exa_estab)
                    join ctl_examen_servicio_diagnostico   	t07 on (t07.id=t02.idestandarrep)
                    where estadodetalle=7 --Estado RC=7
                    and t01.idsolicitudestudio=$idsolicitud
                    and t03.id_area_servicio_diagnostico=$idarea
                    and t02.idplantilla=1 --Plantilla A=1
                    and t04.idestablecimiento=$lugar
                    and (current_date between t04.fechaini and (case when fechafin is NULL then current_date else (fechafin -1) end))
                    and (t04.idsexo is null or t04.idsexo=$sexo)
                    and (idedad=4 or idedad=$idedad)
                    and t06.activo=true
                    and t06.fecha_fin is null
                    order by nombre_reporta;";
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
    function BuscarEmpleadoValidador($responsable, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select nombreempleado as empleado  
                from mnt_empleado
                 where id=$responsable
                 and id_establecimiento=$lugar;";
            $result = pg_query($query);
           // echo '<br>'.$query.'<br>';
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
            $query = "SELECT resultado FROM lab_codigosresultados WHERE id=$cod";
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
    //fn pg
    //FUNCION PARA LLAMAR EMPLEADOS DE LABORATORIOS FILTRADOS POR AREA
    function BuscarEmpleados($idarea, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT t02.codigo 
                      FROM ctl_establecimiento            t01
                      INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
                      WHERE t01.id = $lugar";
            $result  = pg_query($query);
            $rowtipo = pg_fetch_array($result);
            $tipo    = $rowtipo[0];
            $where   = "";

            $query = "SELECT t01.id AS idempleado, t01.nombreempleado
                      FROM mnt_empleado                        t01
                      INNER JOIN mnt_cargoempleados            t02 ON (t02.id = t01.id_cargo_empleado)
                      INNER JOIN ctl_area_servicio_diagnostico t03 ON (t03.id = t01.idarea)
                      WHERE t02.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')
                            AND t03.idarea NOT IN ('INF','REC') AND t01.id_establecimiento = $lugar
                            ";

            if($tipo === "H") {
                $where = " AND t01.idarea = $idarea";
            }

            $query = $query.$where;

            $result = pg_query($query);

            if (!$result)
                return false;
            else
                return $result;
        }
    }
   // Fn PG
     //Funcion para llener resultados de tabulación
    function BuscarMetodologia($idexamen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select *, lem.id as idexamet  
                    from lab_examen_metodologia lem
                    join lab_metodologia lmd on (lem.id_metodologia=lmd.id)
                    where id_conf_exa_estab=$idexamen
                    and activo=true
                    order by nombre_metodologia;";
            $result =@pg_query($query);
           // echo 'query: '.$query;
            if (!$result)
                return false;
            else
                return $result;
        }
    }
   // Fn PG
     //Funcion para llener resultados de tabulación
    function BuscarExaMetodologia($idexamen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select *, lem.id as idexamet  
                    from lab_examen_metodologia lem
                    where id_conf_exa_estab=$idexamen
                    and activo=true;";
            $result =@pg_query($query);
           // echo 'query: '.$query;
            if (!$result)
                return false;
            else
                return $result;
        }
    }
// Fn PG
     //Funcion para llener resultados de tabulación
    function BuscarResultados($idestandar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "select idresultado, resultado, lce.id as idcodexa 
                    from lab_codigosxexamen lce
                    join lab_codigosresultados lcr on (lcr.id=lce.idresultado)
                    where lcr.id !=5
                    and lce.idestandar=(select id from ctl_examen_servicio_diagnostico where idestandar='$idestandar' and activo=true);";
            $result =@pg_query($query);
           // echo 'query: '.$query;
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
//Fn_pg
    //FUNCION PARA MOSTRAR LOS DATOS GENERALES DE LA SOLICITUD PROCESADA
    function MostrarResultadoGenerales($idsolicitud, $idexamen, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
          /*  $query = "SELECT lab_recepcionmuestra.IdSolicitudEstudio, mnt_expediente.IdNumeroExp, 
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
        AND sec_solicitudestudios.IdEstablecimiento=$lugar AND mnt_expediente.IdEstablecimiento=$lugar";*/
            $query="select sse.id as idsolicitudestudio, nombrearea, numeromuestra, fecharecepcion, lrm.fechahorareg as fecha,
(case when id_historial_clinico is not null then id_historial_clinico	
      else id_dato_referencia end)as idhistoref, id_historial_clinico, id_dato_referencia, sse.id_establecimiento_externo, id_area_servicio_diagnostico
from sec_solicitudestudios 		sse
join sec_detallesolicitudestudios	sds on (sse.id=sds.idsolicitudestudio)
join lab_recepcionmuestra		lrm on (sse.id=lrm.idsolicitudestudio)
join lab_conf_examen_estab		lex on (lex.id=sds.id_conf_examen_estab)
join mnt_area_examen_establecimiento	mae on (mae.id=lex.idexamen)
join ctl_area_servicio_diagnostico	cas on (cas.id=mae.id_area_servicio_diagnostico)
where sse.id=$idsolicitud
and sds.id_conf_examen_estab=$idexamen
and sse.id_establecimiento=$lugar;";
            $result = @pg_query($query);
           //    echo '<br\>'.$query.'<br\>';
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    
    
    //Fn_pg
    //FUNCION PARA MOSTRAR LOS DATOS GENERALES DE LA SOLICITUD PROCESADA
    function MostrarResultadoGeneralesPA1($idsolicitud, $lugar, $idarea) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query="select sse.id as idsolicitudestudio, nombrearea, numeromuestra, fecharecepcion, lrm.fechahorareg as fecha,
(case when id_historial_clinico is not null then id_historial_clinico	
      else id_dato_referencia end)as idhistoref, id_historial_clinico, id_dato_referencia, sse.id_establecimiento_externo, id_area_servicio_diagnostico
            from sec_solicitudestudios 		sse
            join sec_detallesolicitudestudios	sds on (sse.id=sds.idsolicitudestudio)
            join lab_recepcionmuestra		lrm on (sse.id=lrm.idsolicitudestudio)
            join lab_conf_examen_estab		lex on (lex.id=sds.id_conf_examen_estab)
            join mnt_area_examen_establecimiento	mae on (mae.id=lex.idexamen)
            join ctl_area_servicio_diagnostico	cas on (cas.id=mae.id_area_servicio_diagnostico)
            where sse.id=$idsolicitud
            and id_area_servicio_diagnostico=$idarea
            and sse.id_establecimiento=$lugar;";
            $result = @pg_query($query);
           //    echo '<br\>'.$query.'<br\>';
            if (!$result)
                return false;
            else
                return $result;
        }
    }//fin MostrarResultadosGeneralesPA1
    
    
    
    
    //Fn Pg
    function MostrarDatoslabresultado($idexamen, $lugar, $idsolicitud, $iddetalle) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query="select t01.*, t01.idempleado as empleado, nombre_examen , t02.f_tomamuestra
from lab_resultados t01
join sec_detallesolicitudestudios t02 on (t02.id=t01.iddetallesolicitud)
join lab_conf_examen_estab t03 on (t03.id=t02.id_conf_examen_estab)
where t01.idsolicitudestudio=$idsolicitud
and t01.iddetallesolicitud=$iddetalle
and t01.idestablecimiento=$lugar;";
            $result = @pg_query($query);
           // $filares = pg_fetch_array($result);
              // echo '<br\>'.$filares['idempleado'].'<br\>';
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    
    //Fn Pg
    function ftomamuestra($iddetalle, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query="select * from sec_detallesolicitudestudios where id= $iddetalle and idestablecimiento=$lugar;";
            $result = @pg_query($query);
           // $filares = pg_fetch_array($result);
              // echo '<br\>'.$filares['idempleado'].'<br\>';
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    
    //Fn Pg
    function buscarexamresult($iddetalle, $idsolicitud, $lugar, $idexamen, $sexo, $idedad) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query="select lce.id  as idexamen, codigo_examen, nombre_examen, unidades, rangoinicio, rangofin,nombre_metodologia, id_metodologia, nombre_reporta, fechafin, fechaini, ldf.id,
lrm.resultado, lrm.observacion, lrm.marca, lrm.lectura
from lab_datosfijosresultado 	ldf
join lab_conf_examen_estab	lce on (lce.id = ldf.id_conf_examen_estab)
join lab_examen_metodologia 	lem on (lce.id = lem.id_conf_exa_estab)
left join lab_metodologia	lme on (lme.id = lem.id_metodologia)
join lab_resultado_metodologia 	lrm on (lem.id = lrm.id_examen_metodologia)
where idestablecimiento=$lugar
and id_conf_examen_estab=$idexamen
and id_detallesolicitudestudio=$iddetalle
and (current_date between fechaini and (case when date(fechafin) is null then current_date else fechafin end))
and (ldf.idsexo is null or ldf.idsexo=$sexo)
and (idedad=4 or idedad=$idedad)
and b_reporta=true order by nombre_metodologia";
            $result = @pg_query($query);
           // $filares = pg_fetch_array($result);
              // echo '<br\>'.$filares['idempleado'].'<br\>';
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    
    
    //Fn_pg
    //Funcion utilizado para obtener los datos de la persona
    //FUNCION PARA MOSTRAR LOS DATOS GENERALES DE LA SOLICITUD PROCESADA
    function MostrarDatosPersona($idsolicitud, $lugar, $id_establecimiento_externo, $nec, $idhistoref) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query="with tbl_datos_paciente as(
select e.id as idexpediente, e.numero as numero, 
concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre,  
s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS edad, conocido_por,id_sexo, id_establecimiento as idestab, telefono_casa, direccion, 
fecha_nacimiento, h.id as idhistoref, date (current_date)  - date (fecha_nacimiento) as dias, s.nombre as sexo
FROM mnt_paciente d 
JOIN mnt_expediente e ON (d.id=e.id_paciente) 
JOIN ctl_sexo s on (s.id=d.id_sexo)
JOIN sec_historial_clinico h on (e.id=h.id_numero_expediente)
and habilitado=true
union 
select e.id as idexpediente, e.numero as numero, 
concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada, d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre, 
s.nombre AS sexoconv, extract(year from age(fecha_nacimiento)) AS Edad,'' as conocido_por,  id_sexo, id_establecimiento_origen as idestab, '-', '-', 
fecha_nacimiento, r.id as idhistoref, date (current_date)  - date (fecha_nacimiento) as dias, s.nombre as sexo
FROM mnt_paciente_referido d
JOIN mnt_expediente_referido e on (d.id= e.id_referido)
JOIN ctl_sexo s on (s.id=d.id_sexo)
JOIN mnt_dato_referencia r on (e.id=r.id_expediente_referido)
)
select * from tbl_datos_paciente
where numero='$nec' 
and idestab=$id_establecimiento_externo
and idhistoref=$idhistoref;";
           // echo '<br>'.$query.'<br><br>';
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
//Fn Pg
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
//Fn Pg
//Funcion utilizada para conocer cuantas metodologias tiene un examen
function CantMetodologia($idexamen) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "select * 
            from lab_examen_metodologia 
            where id_conf_exa_estab=$idexamen
            and id_metodologia is not null
            and activo is true
            order by id;";
           // echo $query;
            $result = pg_query($query);
            $cant=  pg_num_rows($result);
            if (!$result) {
                return false;
            } else {
                return $cant;
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
            $query = "select * 
                        from lab_resultados
                        where  idsolicitudestudio=$idsolicitud
                        and iddetallesolicitud=$iddetalle";
           
            $result = pg_query($query);
             
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
	   '$interpretacion','$observacion','$responsable',$usuario,date_trunc('second',NOW()),$usuario,date_trunc('second',NOW()),$codigo,$lugar)";
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
//Fn Pg
    //Fn para ingresar en lab_resultado_metodologia
    
    //FUNCION PARA GUARDAR RESULTADOS
  function InsertarResultadoPlantillaAM($hdnidexamen_, $hdnIdMetodologia_, $hdnResp_, $hdnFecProc_, $hdnFecResu_, $hdnResult_, $hdnObserva_, $hdnCodResult_, $idsolicitud, $usuario, $iddetalle, $idrecepcion, $lugar, $idresultado, $marca, $lectura) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $nextid="select nextval('lab_resultado_metodologia_id_seq')"; 
            $sql=  pg_query($nextid);
            $nextseq=  pg_fetch_array($sql);
            $idnext=$nextseq[0];

            $query = "insert into lab_resultado_metodologia (id, id_examen_metodologia, id_detallesolicitudestudio, id_codigoresultado, resultado, observacion, idusuarioreg, fechahorareg, fecha_realizacion, fecha_resultado, id_empleado, id_posible_resultado, marca, lectura)
            values ($idnext,$hdnIdMetodologia_, $iddetalle, $hdnCodResult_, $hdnResult_,$hdnObserva_, $usuario, date_trunc('second',NOW()), $hdnFecProc_, $hdnFecResu_, $hdnResp_, $idresultado, $marca, $lectura);";
               //echo $query;
       //     $query2 = "SELECT LAST_INSERT_ID();";

            $result = pg_query($query);
         //   $result2 = pg_query($query2);

            if (!$result)
                return false;
            else
                return $idnext;
        }
    }
   
    //Fn Pg
    //Fn para ingresar en lab_resultado final
    
    //FUNCION PARA GUARDAR RESULTADOS
    function InsertarResultadoPlantillaAF($idsolicitud, $iddetalle,$idrecepcion, $v_resultfin, $v_lectura, $v_interpretacion, $v_obserrecep, $lugar, $usuario, $idexamen, $cmbEmpleadosfin, $d_resultfin, $idresultado, $marca) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $nextid="select nextval('lab_resultados_id_seq')"; 
            $sql=  pg_query($nextid);
            $nextseq=  pg_fetch_array($sql);
            $idnext=$nextseq[0];

            $query = "insert into lab_resultados (id, idsolicitudestudio, iddetallesolicitud, idrecepcionmuestra, resultado, lectura, interpretacion, observacion, idestablecimiento, idusuarioreg, fechahorareg, idexamen, idempleado, fecha_resultado, id_posible_resultado, marca) "
                    . "values ($idnext, $idsolicitud, $iddetalle, $idrecepcion, $v_resultfin, $v_lectura,$v_interpretacion, $v_obserrecep, $lugar, $usuario, date_trunc('seconds',NOW()), $idexamen, $cmbEmpleadosfin, $d_resultfin, $idresultado, $marca)";
            
         //   echo '<br/>'.$query;
       //     $query2 = "SELECT LAST_INSERT_ID();";

            $result = @pg_query($query);
         //   $result2 = pg_query($query2);

            if (!$result)
                return false;
            else
                return $idnext;
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
            $query = "update sec_detallesolicitudestudios
                        set estadodetalle= (select id from ctl_estado_servicio_diagnostico where idestado='RC')
                        where id=$iddetalle";
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
            $query = "select id, id_conf_examen_estab 
                    from sec_detallesolicitudestudios
                    where idsolicitudestudio=$idsolicitud
                    and estadodetalle in (select id 
                                            from ctl_estado_servicio_diagnostico 
                                            where idestado not in ('RC', 'RM'));";
       //    echo '<br>cantidad'.$query.'<br>';                            
            $detalle = pg_num_rows(pg_query($query));
          //  echo '<br>empty(detalle) '.$detalle.'<br>';
            if ($detalle==0) {
                $query = "UPDATE sec_solicitudestudios 
                          SET estado=(select id 
                                from ctl_estado_servicio_diagnostico 
                                where idestado ='C') 
                          WHERE id=$idsolicitud";
                $result = @pg_query($query);
             //   echo '<br>Actualizo porque entro: '.$query.'<br>';
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
      //Fn_pg
    //Funcion para consultar datos obligatorios de 
    function condatos($idhistorialclinico, $lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query="select sef.peso, sef.talla, (sct_name_es ||', ' ||especificacion) as diagnostico, conocido_por 
                    from sec_historial_clinico shc 
                    join mnt_expediente mex on (mex.id = shc.id_numero_expediente) 
                    join mnt_paciente mpa on (mpa.id = mex.id_paciente) 
                    left join sec_diagnostico_paciente sdp on (shc.id= sdp.id_historial_clinico)
                    left join mnt_snomed_cie10 mns	on (mns.id= sdp.id_snomed)
                    left join sec_signos_vitales sef on (shc.id = sef.id_historial_clinico)
                    where shc.id=$idhistorialclinico
                    and shc.idestablecimiento=$lugar";
             //echo $query;
             $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
}
//Fn PG  //Para quimica
//Buscar si existen solicitudes anteriores con ese mismo detalle.
   function buscarAnteriores($idsolicitud,$iddetallesolicitud, $idarea){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= "select nombre_examen, sds.id as iddetallesolicitud, lce.id as idexamen 
from sec_solicitudestudios sse
join sec_detallesolicitudestudios sds 	on (sse.id = sds.idsolicitudestudio)
join lab_conf_examen_estab lce 		on (lce.id = sds.id_conf_examen_estab)
join mnt_area_examen_establecimiento mae on(mae.id = lce.idexamen)
where estadodetalle not in (6,7)
and sse.id=$idsolicitud
and mae.id_area_servicio_diagnostico = $idarea
order by nombre_examen;";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
//Fn PG
//Buscar si existen solicitudes anteriores con ese mismo detalle.
   function buscarAnterioresPUnica($idsolicitud,$iddetallesolicitud, $idarea){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query= "select nombre_examen, sds.id as iddetallesolicitud, lce.id as idexamen 
from sec_solicitudestudios sse
join sec_detallesolicitudestudios sds 	on (sse.id = sds.idsolicitudestudio)
join lab_conf_examen_estab lce 		on (lce.id = sds.id_conf_examen_estab)
join mnt_area_examen_establecimiento mae on(mae.id = lce.idexamen)
where estadodetalle not in (6,7)
and sse.id=$idsolicitud
and sds.id=$iddetallesolicitud
and mae.id_area_servicio_diagnostico = $idarea
order by nombre_examen;";
           // echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }//fin BuscarAnterioresPUnica
    
    //Fn PG
    function consultarPosibleRes($idexamen){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= "select t03.id, posible_resultado
                  from lab_examen_metodo_pos_resultado t01
                  join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia)
                  join lab_posible_resultado t03 on (t03.id=t01.id_posible_resultado)
                  where id_conf_exa_estab =$idexamen
                  and t01.habilitado=true
                  and t02.activo=true;";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
    
    //Fn PG
    function BuscarCodResult($idposres, $idexamen){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= " select  t04.id, t04.resultado
                  from lab_examen_metodo_pos_resultado t01 
                 join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia) 
                 join lab_posible_resultado t03 on (t03.id=t01.id_posible_resultado) 
                 join lab_codigosresultados t04 on (t04.id=t01.id_codigoresultado)
                 where id_conf_exa_estab =$idexamen
                 and t03.id=$idposres
                  and t01.habilitado=true 
                  and t02.activo=true
                  and t03.habilitado=true;";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
    
    //Fn PG
    function PosibleResMetodo($idexametodologia){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= " select t02.id, posible_resultado
from lab_examen_metodo_pos_resultado t01
join lab_posible_resultado t02 on (t02.id=t01.id_posible_resultado)
where id_examen_metodologia =$idexametodologia
and t01.habilitado=true 
and t02.habilitado=true
and (date(t01.fechafin) >= current_date or date(t01.fechafin) is null)
and (date(t02.fechafin) >= current_date or date(t02.fechafin) is null);";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
       //Fn PG
    function buscaEstandar($idexamen, $idestablecimiento){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= " select t02.id, posible_resultado
from lab_examen_metodo_pos_resultado t01
join lab_posible_resultado t02 on (t02.id=t01.id_posible_resultado)
where id_examen_metodologia =$idexametodologia
and t01.habilitado=true 
and t02.habilitado=true
and (date(t01.fechafin) >= current_date or date(t01.fechafin) is null)
and (date(t02.fechafin) >= current_date or date(t02.fechafin) is null);";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
    
         //Fn PG
    function buscarresfin($idexamen){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= "  select t01.id, posible_resultado 
              from lab_posible_resultado t01
              join lab_examen_posible_resultado t02 on (t01.id=t02.id_posible_resultado)
              where id_conf_examen_estab=$idexamen
              and t01.habilitado=true 
              and t02.habilitado=true
              and (date(t01.fechafin) >= current_date or date(t01.fechafin) is null)
              and (date(t02.fechafin) >= current_date or date(t02.fechafin) is null);";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
    //Fn PG
    function consfecha($idsolicitud, $iddetallesolicitud,  $lugar){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= "select * 
from sec_detallesolicitudestudios t01
where t01.id=$iddetallesolicitud
and idestablecimiento=$lugar;";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
    //Fn PG
    function consmotivo($idsolicitud, $lugar){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= "select distinct(t04.id), nombre, t04.id_establecimiento, nombre_examen
from sec_historial_clinico 	t01
join tar_solicitud_fvih 	t02 on (t01.id=t02.id_historial_clinico)
join tar_motivo_solicitud	t03 on (t03.id=t02.id_motivo_solicitud)
join sec_solicitudestudios	t04 on (t01.id=t04.id_historial_clinico)
join sec_detallesolicitudestudios t05 on (t04.id=t05.idsolicitudestudio)
join lab_conf_examen_estab	t06 on (t06.id=t05.id_conf_examen_estab)  
where t04.id=$idsolicitud
and t04.id_establecimiento=$lugar
and t06.nombre_examen ilike '%VIH%';";
            //echo $query;
            $result = pg_query($query);
            if (!$result) {
                return false;
            } else {
                return $result;
            }
        }
    }
    //Fn PG
    function ConsDatoFijo($iddetallesolicitud,$lugar){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query= "select (case when rangofin is null and rangoinicio is not null then ('Valores Normales: Mayor a '||rangoinicio||'  Unidades: '||unidades) 
	     when rangoinicio is null and rangofin is not null then ('Valores Normales: Menor a '||rangofin||'  Unidades: '||unidades) 
	else ('Valores Normales: '||rangoinicio||' - '||rangofin||'  Unidades: '||unidades) end )as rangos
from lab_datosfijosresultado t01
join lab_conf_examen_estab t02 on (t02.id=t01.id_conf_examen_estab)
join sec_detallesolicitudestudios t03 on (t02.id=t03.id_conf_examen_estab)
where t03.id=$iddetallesolicitud
and t03.idestablecimiento=$lugar
and (t01.fechafin is null or t01.fechafin between fechaini and current_date);";
            //echo $query;
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
