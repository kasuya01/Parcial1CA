<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsMuestrasRechazadas {

    //constructor	
    function clsMuestrasRechazadas() {
        
    }
    
        function contaridresultado($idsolicitud,$idsolicitudPadre) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
            $NomAre = "SELECT COUNT(id) 
		        FROM lab_resultados   where  idsolicitudestudio=$idsolicitudPadre
                        and iddetallesolicitud=$idsolicitud";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }

             function idresultado($idsolicitud,$idsolicitudPadre) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
           $NomAre = "select id from lab_resultados where  idsolicitudestudio=$idsolicitudPadre
                        and iddetallesolicitud=$idsolicitud ";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    //select id  from lab_detalleresultado where idresultado=6;
    function id_detalleresultado($idresulta) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
          $NomAre = "select id  from lab_detalleresultado where idresultado=$idresulta ";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    function idexmen_metodologia($idsolicitud,$idsolicitudPadre) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
            $NomAre = "select * from  lab_examen_metodologia
                    where id_conf_exa_estab=(select id_conf_examen_estab from sec_detallesolicitudestudios where id=$idsolicitud and idsolicitudestudio=$idsolicitudPadre) ";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    function idempleado($idsolicitud,$idsolicitudPadre) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
          $NomAre = "select idempleado from lab_resultados
            where idsolicitudestudio=$idsolicitudPadre    and iddetallesolicitud=$idsolicitud  
            and idexamen=(select id_conf_examen_estab from sec_detallesolicitudestudios where id=$idsolicitud and idsolicitudestudio=$idsolicitudPadre)";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    
    function inseresul_metodologia($idexmen_metodologia,$idsolicitud,$estado,$observacion,$usuario,$id_empleado )
           {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_resultado_metodologia(id_examen_metodologia,
					id_detallesolicitudestudio,
					resultado,
					observacion,
					idusuarioreg,
					fechahorareg,
					idusuariomod,
					fechahoramod,
                                        fecha_realizacion,fecha_resultado,id_empleado) 
VALUES($idexmen_metodologia,
	$idsolicitud,
	'$estado',
	'$observacion',
	$usuario,
	NOW(),
	'$usuario',
	NOW(),
        current_date,
        current_date,
        $id_empleado)";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
                
                
                
                
                
                
  
    
    function DatosEstablecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $conNom = "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";

            /* "SELECT 	ces.id_tipo_establecimiento,ces.nombre
              FROM ctl_establecimiento ces
              INNER JOIN ctl_tipo_establecimiento ctes ON ces.id_tipo_establecimiento= ctes.id
              WHERE ces.id=$lugar"; */

            $resul = pg_query($conNom);
        }
        return $resul;
    }

    function DatosArea($area) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            //$NomAre  = "select NombreArea,Administrativa from lab_areas where IdArea='$area'";
            $NomAre = "select nombrearea,administrativa from ctl_area_servicio_diagnostico where id=$area";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }

    function Nombre_Establecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = //"SELECT Nombre FROM mnt_establecimiento WHERE IdEstablecimiento=$lugar";
                    "SELECT nombre FROM ctl_establecimiento WHERE id=$lugar";
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
            //$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
            $sqlText = "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
            $dt = pg_query($sqlText) ;
        }
        return $dt;
    }

    function LlenarCmbServ($IdServ, $lugar) {
        $con = new ConexionBD;

        if ($con->conectar() == true) {
            /* $sqlText = "SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
              FROM mnt_subservicio
              INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
              WHERE mnt_subservicio.IdServicio='$IdServ' AND IdEstablecimiento=$lugar
              ORDER BY NombreSubServicio"; */
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
            $dt = pg_query($sqlText) ;
        }
        return $dt;
    }

    function ExamenesPorArea($idarea, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = /* "SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
                      INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                      WHERE IdEstablecimiento = $lugar AND IdArea='$idarea'
                      AND lab_examenesxestablecimiento.Condicion='H' ORDER BY NombreExamen ASC "; */

                    "SELECT lcee.id,lcee.nombre_examen 
                    FROM mnt_area_examen_establecimiento maees
                    INNER JOIN lab_conf_examen_estab lcee ON maees.id= lcee.idexamen 
                    WHERE maees.id_area_servicio_diagnostico=$idarea
                    AND maees.id_establecimiento=$lugar
                    AND lcee.condicion= 'H'
                    ORDER BY lcee.nombre_examen asc";



            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA ACTUALIZAR OBSERVACION DEL EXAMEN QUE HA SIDO RECHAZADO EN UNA SOLICITUD
    function MarcarObservacionRechazado($idsolicitud, $idarea, $observacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = /* "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
                      WHERE idsolicitudestudio=$idsolicitud AND IdExamen LIKE '%$idarea%'"; */

                    "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE id=$idsolicitud AND idexamen LIKE '%$idarea%'";


            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function MarcarObservacionRechazado1($idsolicitud, $idexamen, $observacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = /* "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
                      WHERE idsolicitudestudio=$idsolicitud AND IdExamen='$idexamen'"; */

                    "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE id=$idsolicitud AND idexamen= $idexamen";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO
    function ListadoSolicitudesPorArea($query_search) { {
            //creamos el objeto $con a partir de la clase ConexionBD
            $con = new ConexionBD;
            //usamos el metodo conectar para realizar la conexion
            if ($con->conectar() == true) {
                $query = $query_search;
                $result = @pg_query($query);
                if (!$result)
                    return false;
                else
                    return $result;
            }
        }
    }
    
      function nombrepaciente($idsolicitud,$idexpediente)
           
  {
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	    $query =  "SELECT  
 CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, 
        t07.apellido_casada) AS paciente,t04.nombre_examen AS nombreexamen
    FROM sec_detallesolicitudestudios t01 
    INNER JOIN sec_solicitudestudios  t02 ON (t02.id = t01.idsolicitudestudio) 
    INNER JOIN lab_conf_examen_estab  t04 ON (t04.id = t01.id_conf_examen_estab) 
    INNER JOIN mnt_expediente         t06 ON (t06.id = t02.id_expediente) 
    INNER JOIN mnt_paciente           t07 ON (t07.id = t06.id_paciente) 
    WHERE t01.id=$idsolicitud 
    and t06.numero='$idexpediente' ";
              
              $result = @pg_query($query);
	    if (!$result)
	       return false;
	    else
	       return $result;	   
   }
 }
   
    
    

    //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
    //DATOS GENERALES DE LA SOLICITUD
    function DatosGeneralesSolicitud($idexpediente, $idsolicitud) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query ="SELECT t02.id,t02.fecha_solicitud AS fechasolicitud, 
                t13.nombre AS nombreservicio, 
                t19.nombre AS sexo, 
                t24.nombreempleado as medico, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, t07.apellido_casada) AS paciente, 
                t07.conocido_por as conocodidox, 
                         REPLACE(
                                                    REPLACE(
                                                        REPLACE(
                                                            REPLACE(
                                                                REPLACE(
                                                                    REPLACE(
                                                                        AGE(t07.fecha_nacimiento::timestamp)::text,
                                                                    'years', 'años'),
                                                                'year', 'año'),
                                                            'mons', 'meses'),
                                                        'mon', 'mes'),
                                                    'days', 'días'),
                                                 'day', 'día') as edad, 
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext, 
                t11.nombre AS nombresubservicio, 
                t22.sct_name_es AS diagnostico, 
                t23.peso as peso, 
                t23.talla as talla,
                t01.observacion observacion
                FROM sec_detallesolicitudestudios 		t01 
                INNER JOIN sec_solicitudestudios 		t02 	ON (t02.id = t01.idsolicitudestudio) 
                INNER JOIN lab_recepcionmuestra 		t03 	ON (t02.id = t03.idsolicitudestudio) 
                INNER JOIN lab_conf_examen_estab 		t04 	ON (t04.id = t01.id_conf_examen_estab) 
                INNER JOIN mnt_area_examen_establecimiento 	t05 	ON (t05.id = t04.idexamen) 
                INNER JOIN mnt_expediente 			t06 	ON (t06.id = t02.id_expediente) 
                INNER JOIN mnt_paciente 			t07 	ON (t07.id = t06.id_paciente) 
                INNER JOIN ctl_area_servicio_diagnostico 	t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
                AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
                INNER JOIN sec_historial_clinico 		t09 	ON (t09.id = t02.id_historial_clinico) 
                INNER JOIN mnt_aten_area_mod_estab 		t10 	ON (t10.id = t09.idsubservicio) 
                INNER JOIN ctl_atencion 			t11 	ON (t11.id = t10.id_atencion) 
                INNER JOIN mnt_area_mod_estab 			t12 	ON (t12.id = t10.id_area_mod_estab) 
                INNER JOIN ctl_area_atencion 			t13 	ON (t13.id = t12.id_area_atencion) 
                INNER JOIN ctl_establecimiento 			t14 	ON (t14.id = t09.idestablecimiento) 
                INNER JOIN cit_citas_serviciodeapoyo 		t15 	ON (t02.id = t15.id_solicitudestudios) 
                INNER JOIN ctl_estado_servicio_diagnostico 	t16 	ON (t16.id = t01.estadodetalle) 
                INNER JOIN lab_tiposolicitud 			t17 	ON (t17.id = t02.idtiposolicitud) 
                INNER JOIN ctl_examen_servicio_diagnostico 	t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
                INNER JOIN ctl_sexo 				t19 	ON (t19.id = t07.id_sexo) 
                left  join sec_diagnostico_paciente		t21     on (t21.id_historial_clinico=t09.id)
                left join mnt_snomed_cie10 			t22	on (t22.id=t21.id_snomed)
                left join sec_signos_vitales  			t23 	on (t23.id_historial_clinico=t09.id)
                left  join mnt_empleado 			t24 	on (t09.id_empleado=t24.id) 
                WHERE t01.id=$idsolicitud 
                and t06.numero='$idexpediente' 
                AND  t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado ='RM')

       UNION

                SELECT t02.id,t02.fecha_solicitud AS fechasolicitud, 
                t13.nombre AS nombreservicio, 
                t19.nombre AS sexo, 
                t24.nombreempleado as medico, 
                CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, t07.apellido_casada) AS paciente,
                t07.primer_nombre, 
                 REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(t07.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') as edad,
                (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext, 
                t11.nombre AS nombresubservicio, 
                t22.sct_name_es AS diagnostico, 
                t23.peso as peso, 
                t23.talla as talla,
                t01.observacion observacion
                FROM sec_detallesolicitudestudios t01 
                INNER JOIN sec_solicitudestudios 		t02 		ON (t02.id = t01.idsolicitudestudio) 
                INNER JOIN lab_recepcionmuestra 		t03 		ON (t02.id = t03.idsolicitudestudio) 
                INNER JOIN lab_conf_examen_estab 		t04 		ON (t04.id = t01.id_conf_examen_estab) 
                INNER JOIN mnt_area_examen_establecimiento 	t05 		ON (t05.id = t04.idexamen) 
                INNER JOIN mnt_dato_referencia 			t09 		ON t09.id=t02.id_dato_referencia 
                INNER JOIN mnt_expediente_referido 		t06 		ON (t06.id = t09.id_expediente_referido) 
                INNER JOIN mnt_paciente_referido 		t07 		ON (t07.id = t06.id_referido) 
                INNER JOIN ctl_area_servicio_diagnostico 	t08 		ON (t08.id = t05.id_area_servicio_diagnostico 
                AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
                INNER JOIN mnt_aten_area_mod_estab 		t10 		ON (t10.id = t09.id_aten_area_mod_estab) 
                INNER JOIN ctl_atencion 			t11 		ON (t11.id = t10.id_atencion) 
                INNER JOIN mnt_area_mod_estab 			t12 		ON (t12.id = t10.id_area_mod_estab) 
                INNER JOIN ctl_area_atencion 			t13 		ON (t13.id = t12.id_area_atencion) 
                INNER JOIN ctl_establecimiento 			t14 		ON (t14.id = t09.id_establecimiento) 
                INNER JOIN ctl_examen_servicio_diagnostico 	t18 		ON (t18.id = t05.id_examen_servicio_diagnostico) 
                INNER JOIN ctl_sexo 				t19 		ON (t19.id = t07.id_sexo) 
                left  join sec_diagnostico_paciente		t21             on (t21.id_historial_clinico=t09.id)
                left join mnt_snomed_cie10 			t22             on (t22.id=t21.id_snomed)
                left join sec_signos_vitales  			t23             on (t23.id_historial_clinico=t09.id)
                left  join mnt_empleado 			t24             on (t09.id_empleado=t24.id) 
                WHERE t01.id=$idsolicitud 
                and t06.numero='$idexpediente'
               AND  t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado ='RM')";
                    
                    
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
            $query = "SELECT lab_examenes.IdExamen,NombreExamen,TipoMuestra,Indicacion 
		     FROM sec_detallesolicitudestudios 
		     INNER JOIN lab_examenes     ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		     INNER JOIN lab_tipomuestra  ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
		     WHERE idSolicitudEstudio = $idsolicitud AND IdArea='$idarea' AND EstadoDetalle='RM'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function DatosExamen($idarea, $idsolicitud, $idexamen) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
        $query = "select lcee.codigo_examen,
                        lcee.nombre_examen,
                        ltm.tipomuestra,
                        sdses.indicacion,
                        sdses.observacion,
                        casd.id
		from ctl_area_servicio_diagnostico casd 
                join mnt_area_examen_establecimiento mnt4           on mnt4.id_area_servicio_diagnostico=casd.id 
                join lab_conf_examen_estab lcee                     on (mnt4.id=lcee.idexamen) 
                INNER JOIN sec_detallesolicitudestudios sdses       ON sdses.id_conf_examen_estab=lcee.id
                inner join lab_tipomuestra ltm                      on ltm.id=sdses.idtipomuestra
            where sdses.id=$idsolicitud ";
         /*  -- AND casd.id=$idarea
           -- AND lcee.id=$idexamen*/
/* "SELECT lab_examenes.IdExamen,NombreExamen,TipoMuestra,Indicacion,sec_detallesolicitudestudios.Observacion 
                      FROM sec_detallesolicitudestudios
                      INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                      INNER JOIN lab_tipomuestra ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
                      WHERE idSolicitudEstudio = $idsolicitud
                      AND IdArea='$idarea' AND lab_examenes.IdExamen='$idexamen'"; */



//echo $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

//FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
    function CambiarEstadoSolicitudProceso3($idexpediente, $fechasolicitud, $estadosolicitud, $idsolicitudPadre) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query = "select  COUNT(id) from  sec_detallesolicitudestudios
		  WHERE idsolicitudestudio=$idsolicitudPadre AND (estadodetalle<>(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='PM'))";
            $detalle = pg_fetch_array(pg_query($query));
            if ($detalle[0] == 0) {
                $query1 = /* "UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud"; */
                        "UPDATE sec_solicitudestudios SET estado=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='C')
                         --fecha_solicitud= now(),
                         --fechahorareg=current_timestamp
                    WHERE id=$idsolicitudPadre";
                $result = pg_query($query1);
                return true;
            }
            if ($detalle[0] >= 1) {
                 $query1 = /* "UPDATE sec_solicitudestudios SET estado='P' WHERE IdSolicitudEstudio=$idsolicitud"; */
                        "UPDATE sec_solicitudestudios SET estado=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='P')
                            --fecha_solicitud= now(),
                            --fechahorareg=current_timestamp
                         WHERE id=$idsolicitudPadre";
                $result = pg_query($query1);
                return true;
            }
        }
    }

    //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
    function CambiarEstadoDetalle($idsolicitud, $estado, $observacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
             $query = /*   "UPDATE sec_detallesolicitudestudios ,sec_solicitudestudios 
                      SET sec_detallesolicitudestudios.estadodetalle=$estado,sec_detallesolicitudestudios.observacion=' '
                      WHERE sec_detallesolicitudestudios.idsolicitudestudio=$idsolicitud AND
                      sec_solicitudestudios.id_atencion=98 AND sec_detallesolicitudestudios.idexamen LIKE '%$idarea%'
                      AND sec_detallesolicitudestudios.estadodetalle='RM'"; */



                    "UPDATE sec_detallesolicitudestudios 
                    SET estadodetalle=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='$estado'),
                    observacion='$observacion'
                    WHERE sec_detallesolicitudestudios.id=$idsolicitud";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
    function CambiarEstadoDetalle1($idsolicitud, $estado, $idexamen, $observacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

            $query = /* "UPDATE sec_detallesolicitudestudios,sec_solicitudestudios
                      SET sec_detallesolicitudestudios.estadodetalle='$estado',  sec_detallesolicitudestudios.observacion=''
                      WHERE sec_detallesolicitudestudios.idsolicitudestudio=$idsolicitud
                      AND  sec_solicitudestudios.id_atencion=98 AND  sec_detallesolicitudestudios.idexamen ='$idexamen'"; */

                    "UPDATE sec_detallesolicitudestudios 
             SET estadodetalle=$estado,
	     observacion='$observacion'
            WHERE sec_detallesolicitudestudios.id=$idsolicitud ";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }

    function NumeroDeRegistros($query) {
        //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = $query;
            $numreg = pg_num_rows(pg_query($query));
            if (!$numreg)
                return false;
            else
                return $numreg;
        }
    }

}

//CLASE
?>
