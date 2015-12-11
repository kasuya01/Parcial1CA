<?php

include_once("../../../Conexion/ConexionBD.php");

//implementamos la clase lab_areas
class clsRMAutomatizada {

    //constructor	
    function clsRMAutomatizada() {
        
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
    
                //SELECT COUNT(id) 
		      //  FROM lab_resultados
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
            
            $NomAre = "SELECT * FROM lab_examen_metodologia
                       WHERE id_conf_exa_estab=(SELECT id_conf_examen_estab FROM sec_detallesolicitudestudios 
                       WHERE id=$idsolicitud AND idsolicitudestudio=$idsolicitudPadre) ";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    function idempleado($usuario) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
            $NomAre = "SELECT id_empleado FROM fos_user_user WHERE id = $usuario";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    function idresultadometodologia($idexmen_metodologia,$idsolicitud,$id_empleado) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
          $NomAre = "select id from lab_resultado_metodologia  where id_examen_metodologia=$idexmen_metodologia
					       and id_detallesolicitudestudio=$idsolicitud
					       and id_empleado='$id_empleado'
                                               and resultado='PM' ";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    
      function contaridresultadometodologia($idexmen_metodologia,$idsolicitud,$id_empleado) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
        $NomAre = "select count(id) from lab_resultado_metodologia  where id_examen_metodologia=$idexmen_metodologia
					       and id_detallesolicitudestudio=$idsolicitud
					       and id_empleado='$id_empleado'
                                               and resultado='PM' ";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    
    
    function contarresultadometodologia($idresultadometodologia) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $NomAre = "SELECT COUNT(id) FROM lab_resultado_metodologia WHERE id=$idresultadometodologia";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    
    
    
    
    function eliminarsultadometodologia($idresultadometodologia) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            
          $NomAre = "DELETE FROM lab_resultado_metodologia WHERE id=$idresultadometodologia";
            $resul = pg_query($NomAre);
        }
        return $resul;
    }
    
    /*function inseresul_metodologia($idresultadometodologia,$idexmen_metodologia,$id_detalleresultado,$estado,$observacion,$usuario,$id_empleado )
           {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE SET lab_resultado_metodologia(id_examen_metodologia,
					id_detalleresultado,
					resultado,
					observacion,
					idusuarioreg,
					fechahorareg,
					
                                        fecha_realizacion,id_empleado) 
VALUES($idexmen_metodologia,
	$id_detalleresultado,
	'$estado',
	'$observacion',
	$usuario,
	NOW(),
	
        current_date,
        $id_empleado)";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }*/
    
    

    function DatosEstablecimiento($lugar) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            /* $conNom  = "SELECT 	mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
              FROM mnt_establecimiento
              INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
              WHERE IdEstablecimiento=$lugar"; */
            $conNom = "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
            $resul = pg_query($conNom);
        }
        return $resul;
    }

    function DatosArea($area) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            //$NomAre  ="select nombrearea,administrativa from ctl_area_servicio_diagnostico where id=$area";
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
         $sqlText = "with tbl_servicio as (select mnt_3.id,
                        CASE
                        WHEN mnt_3.nombre_ambiente IS NOT NULL
                        THEN  	
                                CASE WHEN id_servicio_externo_estab IS NOT NULL
                                        THEN mnt_ser.abreviatura ||'-->' ||mnt_3.nombre_ambiente
                                        ELSE mnt_3.nombre_ambiente
                                END

                        ELSE
                        CASE WHEN id_servicio_externo_estab IS NOT NULL 
                                THEN mnt_ser.abreviatura ||'--> ' || cat.nombre
                             WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=cat.nombre)
                                THEN cmo.nombre||'-'||cat.nombre
                        END
                        END AS servicio 
                        from ctl_atencion cat 
                        join mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                        join mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                        LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                        LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                        join mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                        join ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                        where  mnt_2.id=$IdServ
                        and mnt_3.id_establecimiento=$lugar
                        order by 2)
                        select id, servicio from tbl_servicio where servicio is not null";
         $dt = pg_query($sqlText);
      }
      return $dt;
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

    function MarcarObservacionRechazado1($idsolicitud, $idobservacion, $idrechazo) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = /* "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
                      WHERE idsolicitudestudio=$idsolicitud AND IdExamen='$idexamen'"; */

                    "UPDATE sec_detallesolicitudestudios SET id_estado_rechazo = $idrechazo, id_posible_observacion = $idobservacion
	             WHERE id=$idsolicitud" ;

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function AgregarResultadoMetodologia($idsolicitudP,$idsolicitud,$id_codigoresultado,$idexmen_metodologia,$id_empleado,$usuario){
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "INSERT INTO lab_resultado_metodologia(id_examen_metodologia,id_detallesolicitudestudio,id_codigoresultado,idusuarioreg,fechahorareg,
   		      fecha_realizacion,id_empleado) 
                      VALUES($idexmen_metodologia,$idsolicitud,$id_codigoresultado,$usuario,NOW(),current_date,$id_empleado)";

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
	    $query = "SELECT CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, 
                      t07.apellido_casada) AS paciente,t04.nombre_examen AS nombreexamen
                      FROM sec_detallesolicitudestudios t01 
                      INNER JOIN sec_solicitudestudios  t02 ON (t02.id = t01.idsolicitudestudio) 
                      INNER JOIN lab_conf_examen_estab  t04 ON (t04.id = t01.id_conf_examen_estab) 
                      INNER JOIN mnt_expediente         t06 ON (t06.id = t02.id_expediente) 
                      INNER JOIN mnt_paciente           t07 ON (t07.id = t06.id_paciente) 
                      WHERE t01.id=$idsolicitud 
                      AND t06.numero='$idexpediente' ";

                        $result = @pg_query($query);
                        if (!$result)
                            return false;
                        else
                            return $result;	   
        }
    }
   
    
    
    

    //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
    //DATOS GENERALES DE LA SOLICITUD
    function DatosGeneralesSolicitud($idsolicitudP,$idexpediente) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query = "SELECT t02.id,t02.fecha_solicitud AS fechasolicitud,
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
                t23.talla as talla 
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
                WHERE t01.id=$idsolicitudP 
                and t06.numero='$idexpediente' 
                AND ( (t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado ='PM') )
                     or (t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado ='D') ) )

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
                t23.talla as talla 
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
                WHERE t01.id=$idsolicitudP and t06.numero='$idexpediente' 
                AND ( (t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado ='PM') )
                     or (t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado ='D') ) )";
                      
                      
            $result = @pg_query($query);

            //         echo $query;
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
            $query = 
                    "SELECT lab_examenes.IdExamen,NombreExamen,TipoMuestra,Indicacion 
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

    function DatosExamen( $idsolicitudP ) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = "SELECT lcee.codigo_examen,
                            lcee.nombre_examen,
                            ltm.tipomuestra,
                            sdses.indicacion,
                            sdses.observacion,
                            casd.id
		FROM ctl_area_servicio_diagnostico casd 
                JOIN mnt_area_examen_establecimiento mnt4       ON mnt4.id_area_servicio_diagnostico=casd.id 
                JOIN lab_conf_examen_estab lcee                 ON (mnt4.id=lcee.idexamen) 
                INNER JOIN sec_detallesolicitudestudios sdses   ON sdses.id_conf_examen_estab=lcee.id
                INNER JOIN lab_tipomuestra ltm             ON ltm.id=sdses.idtipomuestra
                WHERE sdses.id=$idsolicitudP ";
           /*-- AND casd.id=$idarea
          --  AND lcee.id=$idexamen";*/

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function CambiarEstadoSolicitud($idsolicitud, $idsolicitudPadre) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query = /* "SELECT COUNT(IdDetalleSolicitud) FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio=$idsolicitud AND (EstadoDetalle<>'RC' AND EstadoDetalle<>'RM')"; */

                "SELECT COUNT(id) 
		FROM sec_detallesolicitudestudios sdse
                WHERE idsolicitudestudio=$idsolicitudPadre AND (estadodetalle <> (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='RC') 
                AND  estadodetalle <> (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='RM'))";
          



            $detalle = pg_fetch_array(pg_query($query));
            if ($detalle[0] == 0) {
                 $query1 = "UPDATE sec_solicitudestudios SET estado=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='C')
                            --fecha_solicitud= now(),
                            -- fechahorareg=current_timestamp
                             WHERE id=$idsolicitudPadre";
                $result = pg_query($query1);
                return true;
            }
            if ($detalle[0] >= 1) {
               $query1 = /* "UPDATE sec_solicitudestudios SET estado='P' WHERE IdSolicitudEstudio=$idsolicitud"; */
                        "UPDATE sec_solicitudestudios SET estado=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='P')
                        --fecha_solicitud= now(),
                        -- fechahorareg=current_timestamp
                         WHERE id=$idsolicitudPadre";
                $result = pg_query($query1);
                return true;
            }
        }
    }

    
    function CambiarEstadoSolicitud1($idsolicitudPadre) {
         $con = new ConexionBD;
        if ($con->conectar() == true) {
          
                $query = "UPDATE sec_solicitudestudios SET estado=(SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='R')
                                --fecha_solicitud= now(),
                                -- fechahorareg=current_timestamp
                                 WHERE id=$idsolicitudPadre";
           
                $result = pg_query($query);
                return true;
        }
         
    }
     
     
     function ObtenerProcesoActivo($lugar){
        if ($con->conectar() == true) {
            $query = "SELECT CASE activo WHEN TRUE THEN 1
                                ELSE 0
                             END
                      FROM lab_proceso_establecimiento 
                      WHERE id_proceso_laboratorio=3 AND id_establecimiento=$lugar";
            $result =pg_fetch_array(pg_query($query));
          // echo $result[0];
                return $result;
           
                
        }
    }
     
     
    //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
    function CambiarEstadoDetalle($idsolicitud,$estado,$idarea) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
           $query =  "UPDATE sec_detallesolicitudestudios 
                       SET estadodetalle=$estado,
                       observacion='$observacion',id_posible_observacion ='NULL'
                       WHERE sec_detallesolicitudestudios.id=$idsolicitud";
            $result = pg_query($query);
            if (!$result)
                return false;
            else
                return true;
        }
    }
    
    function TotalDetallesSolicitud($idsolicitud){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query =  "select count(*) from sec_detallesolicitudestudios where idsolicitudestudio = $idsolicitud"; 
            $result = pg_query($query);
       }
               return $result;
           
        
    }
    
    
    function TotalEstadosDigitados($idsolicitud){
         $con = new ConexionBD;
        if ($con->conectar() == true) {
            $query =  "SELECT count(*) FROM sec_detallesolicitudestudios WHERE idsolicitudestudio = $idsolicitud "
                    . "AND estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado ='D')"; 
            $result = pg_query($query);
            }
               return $result;
        }    
        
     

    //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
    function CambiarEstadoDetalle1($idsolicitud, $estado, $idexamen, $observacion) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {

             $query =  "UPDATE sec_detallesolicitudestudios 
                        SET estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = '$estado'),
                        observacion='$observacion'
                        WHERE sec_detallesolicitudestudios.id=$idsolicitud ";

            $result = @pg_query($query);
           
           return $result;
           
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

    //RECUPERAR EXAMENES POR AREA
    function ExamenesPorArea($idarea, $lugar) {
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $query = "SELECT lcee.id,lcee.nombre_examen 
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

    function siesreferrido() {
        $respuesta;
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if ($con->conectar() == true) {
            $consulta = "select count (id_expediente_referido) from sec_solicitudestudios";
            $result = @pg_query($consulta);
            while ($row = pg_fetch_array($result)) {
                $respuesta = $row[0];
            }
        }
        return $respuesta;
    }

}

//CLASE
?>
