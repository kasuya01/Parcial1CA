<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsReporteExamenesporServicio
{
	 //constructor	
	 function clsReporteExamenesporServicio()
	 {
	 }	
	 
	
 
    function LeerAreas($lugar){
	$con = new ConexionBD;
		if($con->conectar()==true) 
		{ $query=/*"SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
			INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			WHERE lab_areasxestablecimiento.IdEstablecimiento=$lugar AND lab_areas.Administrativa = 'N' 
                        AND lab_areasxestablecimiento.Condicion='H' ORDER BY NombreArea";*/
                        
                        "SELECT t01.id AS idarea, t01.nombrearea
                      FROM ctl_area_servicio_diagnostico t01
                      WHERE t01.id IN (
                        SELECT idarea
                        FROM lab_areasxestablecimiento 
                        WHERE condicion = 'H'  AND idestablecimiento = $lugar)
                        AND t01.administrativa = 'N'
                      ORDER BY nombrearea";
                        
			$result = @pg_query($query);
			if (!$result)
			return false;
			else
			return $result;	
		}
      }
	
function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
        $query =/*"SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
	      INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
	      WHERE lab_areasxestablecimiento.IdEstablecimiento=$lugar AND lab_areas.Administrativa='N' 
              AND lab_areasxestablecimiento.Condicion='H' 
              ORDER BY NombreArea";*/
     
     
                "SELECT t01.id AS idarea, t01.nombrearea
                      FROM ctl_area_servicio_diagnostico t01
                      WHERE t01.id IN (
                        SELECT idarea
                        FROM lab_areasxestablecimiento 
                        WHERE condicion = 'H'  AND idestablecimiento = $lugar)
                        AND t01.administrativa = 'N'
                      ORDER BY nombrearea";
     
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }

	 //FUNCION PARA MOSTRAR DATOS DE BUSQUEDA
 function BuscarExamenesporSubServicio($query_search)
 {
  //creamos el objeto $con a partir de la clase ConexionBD
	  $con = new ConexionBD;
	 //usamos el metodo conectar para realizar la conexion
	 if($con->conectar()==true){
	    $query = $query_search;
	    $result = @pg_query($query);
	    if (!$result)
		   return false;
	    else
		   return $result;
	   }  

 }

 
 function BuscarExamenesporSubServicio1($cond1,$cond2,$cadena,$lugar)
 {
  //creamos el objeto $con a partir de la clase ConexionBD
	  $con = new ConexionBD;
	 //usamos el metodo conectar para realizar la conexion
	 if($con->conectar()==true){
	    $query = "select   
                    $cadena  
                    t13.nombre as servicio, 
                    t14.nombre as establecimiento, 
                    t11.nombre as subservicio,
                    SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total
                    FROM sec_detallesolicitudestudios           t01 
                    INNER JOIN   lab_resultados                 t02 	ON (t01.id=t02.iddetallesolicitud) 
                    INNER JOIN lab_conf_examen_estab            t03 	ON (t01.id_conf_examen_estab=t03.id) 
                    INNER JOIN mnt_area_examen_establecimiento  t04 	ON (t04.id=t03.idexamen) 
                    INNER JOIN ctl_area_servicio_diagnostico    t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
                    INNER JOIN lab_areasxestablecimiento        t06 	ON (t06.idarea=t05.id) 
                    INNER JOIN sec_solicitudestudios            t07 	ON (t07.id=t01.idsolicitudestudio) 
                    left JOIN sec_historial_clinico             t08 	ON (t07.id_historial_clinico=t08.id)
                    INNER JOIN  mnt_aten_area_mod_estab         t10 	ON (t10.id = t08.idsubservicio) 
                    INNER JOIN  ctl_atencion                    t11 	ON (t11.id = t10.id_atencion) 
                    INNER JOIN  mnt_area_mod_estab              t12 	ON (t12.id = t10.id_area_mod_estab) 
                    INNER JOIN   ctl_area_atencion              t13 	ON (t13.id = t12.id_area_atencion) 
                    INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t08.idestablecimiento) 
                    WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                    AND (t06.condicion='H') 
                    AND (t06.idestablecimiento=$lugar) $cond1
                    GROUP BY t13.nombre,t14.nombre,t11.nombre 
                    union 
                    select 
                    $cadena  
                    t13.nombre as servicio, 
                    t14.nombre as establecimiento, 
                    t11.nombre as subservicio,
                    SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total
                    from sec_detallesolicitudestudios           t01 
                    INNER JOIN   lab_resultados                 t02 	ON (t01.id=t02.iddetallesolicitud) 
                    INNER JOIN lab_conf_examen_estab            t03 	ON (t01.id_conf_examen_estab=t03.id) 
                    INNER JOIN mnt_area_examen_establecimiento  t04 	ON (t04.id=t03.idexamen) 
                    INNER JOIN ctl_area_servicio_diagnostico    t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
                    INNER JOIN lab_areasxestablecimiento        t06 	ON (t06.idarea=t05.id) 
                    INNER JOIN sec_solicitudestudios            t07 	ON (t07.id=t01.idsolicitudestudio) 
                    left join mnt_dato_referencia               t08		on (t07.id_dato_referencia=t08.id)		
                    INNER JOIN  mnt_aten_area_mod_estab         t10 	ON (t10.id = t08.id_aten_area_mod_estab) 
                    INNER JOIN  ctl_atencion                    t11 	ON (t11.id = t10.id_atencion) 
                    INNER JOIN  mnt_area_mod_estab              t12 	ON (t12.id = t10.id_area_mod_estab) 
                    INNER JOIN   ctl_area_atencion              t13 	ON (t13.id = t12.id_area_atencion) 
                    INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t08.id_establecimiento) 
                    WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                    AND (t06.condicion='H') 
                    AND (t06.idestablecimiento=$lugar) $cond2
                    GROUP BY t13.nombre,t14.nombre,t11.nombre";
	    $result = @pg_query($query);
	    if (!$result)
		   return false;
	    else
		   return $result;
	   }  

 }
 
	 
function consultarareas($lugar){
	$con = new ConexionBD;
	if($con->conectar()==true){
	 $query= "SELECT t01.id AS idarea, t01.nombrearea  as nombrearea
                  FROM ctl_area_servicio_diagnostico t01
                  WHERE t01.id IN (SELECT idarea FROM lab_areasxestablecimiento 
                                   WHERE condicion = 'H'  AND idestablecimiento = $lugar)
                  AND t01.administrativa = 'N'
                  ORDER BY nombrearea";
                 
                 
                 
	 $dt = pg_query($query) ;
	}
	return $dt;
}
	
function consultaSubservicios($procedencia){
$con = new ConexionBD;
	if($con->conectar()==true){
	 $query=              
                  "select id from ctl_area_atencion where id=$procedencia";
                  
                  
	  $dt = pg_query( $query) ;
	}
	return $dt;
}


function NombreServicio($procedencia){
$con = new ConexionBD;
	if($con->conectar()==true){
	   $query= "SELECT ctl_area_atencion.id, CASE WHEN id_servicio_externo_estab IS NOT NULL 
                                                                THEN mnt_servicio_externo.abreviatura ||'--'  || ctl_area_atencion.nombre
                                                                ELSE       ctl_modalidad.nombre ||'--' || ctl_area_atencion.nombre 
                                                                END as servicio 
                                                                FROM mnt_area_mod_estab 
                                                     INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND ctl_area_atencion.id_tipo_atencion=1)
                                                     INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
                                                        INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
                                                     LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab) 
                                                        LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo) 
                                                     where mnt_area_mod_estab.id=$procedencia";
                 
                /* "SELECT t01.id,
       t01.nombre as servicio
       FROM ctl_area_atencion t01
       WHERE t01.id =$procedencia";*/
                  
                  
	  $dt = pg_query( $query) ;
	}
	return $dt;
}

function subserviciosxservicio($cadena,$servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
	 $query=  "select   
                        $cadena  
                        t13.nombre as servicio, 
                        t14.nombre as establecimiento, 
                        t11.nombre as subservicio,
                        SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total
                        FROM sec_detallesolicitudestudios           t01 
                        INNER JOIN   lab_resultados                 t02 	ON (t01.id=t02.iddetallesolicitud) 
                        INNER JOIN lab_conf_examen_estab            t03 	ON (t01.id_conf_examen_estab=t03.id) 
                        INNER JOIN mnt_area_examen_establecimiento  t04 	ON (t04.id=t03.idexamen) 
                        INNER JOIN ctl_area_servicio_diagnostico    t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
                        INNER JOIN lab_areasxestablecimiento        t06 	ON (t06.idarea=t05.id) 
                        INNER JOIN sec_solicitudestudios            t07 	ON (t07.id=t01.idsolicitudestudio) 
                        left JOIN sec_historial_clinico             t08 	ON (t07.id_historial_clinico=t08.id)
                        INNER JOIN  mnt_aten_area_mod_estab         t10 	ON (t10.id = t08.idsubservicio) 
                        INNER JOIN  ctl_atencion                    t11 	ON (t11.id = t10.id_atencion) 
                        INNER JOIN  mnt_area_mod_estab              t12 	ON (t12.id = t10.id_area_mod_estab) 
                        INNER JOIN   ctl_area_atencion              t13 	ON (t13.id = t12.id_area_atencion) 
                        INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t08.idestablecimiento) 
                       WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                            AND (t06.condicion='H')
                            AND (t06.idestablecimiento=$lugar)  
                            AND (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."') 
                            AND  t12.id= $servicio 
                               

                                GROUP BY   
                                        t13.nombre, 
                                        t14.nombre, 
                                        t11.nombre 
        union 
                        select 
                        $cadena  
                        t13.nombre as servicio, 
                        t14.nombre as establecimiento, 
                        t11.nombre as subservicio,
                        SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total
                        from sec_detallesolicitudestudios           t01 
                        INNER JOIN   lab_resultados                 t02 	ON (t01.id=t02.iddetallesolicitud) 
                        INNER JOIN lab_conf_examen_estab            t03 	ON (t01.id_conf_examen_estab=t03.id) 
                        INNER JOIN mnt_area_examen_establecimiento  t04 	ON (t04.id=t03.idexamen) 
                        INNER JOIN ctl_area_servicio_diagnostico    t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
                        INNER JOIN lab_areasxestablecimiento        t06 	ON (t06.idarea=t05.id) 
                        INNER JOIN sec_solicitudestudios            t07 	ON (t07.id=t01.idsolicitudestudio) 
                        left join mnt_dato_referencia               t08		on (t07.id_dato_referencia=t08.id)		
                        INNER JOIN  mnt_aten_area_mod_estab         t10 	ON (t10.id = t08.id_aten_area_mod_estab) 
                        INNER JOIN  ctl_atencion                    t11 	ON (t11.id = t10.id_atencion) 
                        INNER JOIN  mnt_area_mod_estab              t12 	ON (t12.id = t10.id_area_mod_estab) 
                        INNER JOIN   ctl_area_atencion              t13 	ON (t13.id = t12.id_area_atencion) 
                        INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t08.id_establecimiento) 
                        WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                            AND (t06.condicion='H')
                            AND (t06.idestablecimiento=$lugar)  
                            AND (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."') 
                            AND  t12.id= $servicio 
                                GROUP BY   
                                        t13.nombre, 
                                        t14.nombre, 
                                        t11.nombre";
          
                  
 $dt = pg_query( $query) ;
	}
	return $dt;

}








function cantidadxservicio1($servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
     $query=   "select    count(t12.id) as cantidad 
FROM sec_detallesolicitudestudios t01 
INNER JOIN   lab_resultados t02 		ON (t01.id=t02.iddetallesolicitud) 
INNER JOIN lab_conf_examen_estab t03 		ON (t01.id_conf_examen_estab=t03.id) 
INNER JOIN mnt_area_examen_establecimiento t04 	ON (t04.id=t03.idexamen) 
INNER JOIN ctl_area_servicio_diagnostico t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
INNER JOIN lab_areasxestablecimiento t06 	ON (t06.idarea=t05.id) 
INNER JOIN sec_solicitudestudios t07 		ON (t07.id=t01.idsolicitudestudio) 
left JOIN sec_historial_clinico t08 		ON (t07.id_historial_clinico=t08.id)
INNER JOIN  mnt_aten_area_mod_estab t10 	ON (t10.id = t08.idsubservicio) 
INNER JOIN  ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
INNER JOIN  mnt_area_mod_estab t12 		ON (t12.id = t10.id_area_mod_estab) 
INNER JOIN   ctl_area_atencion t13 		ON (t13.id = t12.id_area_atencion) 
INNER JOIN ctl_establecimiento t14 		ON (t14.id = t08.idestablecimiento) 
WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
AND (t06.condicion='H') 
AND (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."') 
AND t12.id=$servicio
AND (t06.idestablecimiento=$lugar) ";
         
         
         
$numreg =pg_fetch_array(pg_query($query));
	 
	  return $numreg[0] ;
 

}
}

function cantidadxservicio2($servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
   $query="select count(t12.id) as cantidad 
FROM sec_detallesolicitudestudios t01 
INNER JOIN   lab_resultados t02 		ON (t01.id=t02.iddetallesolicitud) 
INNER JOIN lab_conf_examen_estab t03 		ON (t01.id_conf_examen_estab=t03.id) 
INNER JOIN mnt_area_examen_establecimiento t04 	ON (t04.id=t03.idexamen) 
INNER JOIN ctl_area_servicio_diagnostico t05 	ON (t05.id=t04.id_area_servicio_diagnostico) 
INNER JOIN lab_areasxestablecimiento t06 	ON (t06.idarea=t05.id) 
INNER JOIN sec_solicitudestudios t07 		ON (t07.id=t01.idsolicitudestudio) 
left join mnt_dato_referencia t08		on (t07.id_dato_referencia=t08.id)		
INNER JOIN  mnt_aten_area_mod_estab t10 	ON (t10.id = t08.id_aten_area_mod_estab) 
INNER JOIN  ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
INNER JOIN  mnt_area_mod_estab t12 		ON (t12.id = t10.id_area_mod_estab) 
INNER JOIN   ctl_area_atencion t13 		ON (t13.id = t12.id_area_atencion) 
INNER JOIN ctl_establecimiento t14 		ON (t14.id = t08.id_establecimiento) 
WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
AND (t06.condicion='H') 
AND (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."') 
AND t13.id=$servicio
AND (t06.idestablecimiento=$lugar) ";
         
         
         
$numreg =pg_fetch_array(pg_query($query));
	 
	  return $numreg[0] ;
 

}
}



/*function consultaTodosServicios(){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT distinct mnt_subservicio.IdServicio, mnt_servicio.NombreServicio FROM   	  mnt_subservicio INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio 
	  ORDER BY mnt_subservicio.IdServicio";
	  $dt = pg_query( $query) ;
	}
	return $dt;
}*/	
function consultaTodosServicios($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
	 $query= "SELECT mnt_area_mod_estab.id as codigo ,CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-->'  || ctl_area_atencion.nombre
                                                      ELSE   ctl_modalidad.nombre ||'-->' || ctl_area_atencion.nombre
                                                       END
                                                       FROM mnt_area_mod_estab
                                                       INNER JOIN  ctl_area_atencion  on (ctl_area_atencion.id = mnt_area_mod_estab.id_area_atencion AND ctl_area_atencion.id_tipo_atencion=1)
                                                       INNER JOIN  mnt_modalidad_establecimiento ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
                                                       INNER JOIN ctl_modalidad ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
                                                       LEFT JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab.id_servicio_externo_estab)
                                                       LEFT JOIN mnt_servicio_externo ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo)
                                                       ORDER by ctl_modalidad.nombre,mnt_servicio_externo.abreviatura,ctl_area_atencion.nombre asc";
                /*"SELECT distinct mnt_subservicio.IdServicio, mnt_servicio.NombreServicio 
FROM  mnt_subservicio 
INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio 
INNER JOIN mnt_servicioxestablecimiento ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
WHERE mnt_servicioxestablecimiento.IdEstablecimiento=$lugar
ORDER BY mnt_subservicio.IdServicio";*/
                  
                /*  "SELECT t01.id,
                          t01.nombre
                    FROM ctl_area_atencion t01
                    --WHERE t01.id IN (SELECT DISTINCT id_area_atencion 
                                     --FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)
                  ";*/
                  
                  
	  $dt = pg_query( $query) ;
	}
	return $dt;
}	


function ConsultaExterna(){
$con = new ConexionBD;
	if($con->conectar()==true){
	 $query="SELECT t01.id,
                          t01.nombre
                    FROM ctl_area_atencion t01
                    WHERE t01.id=1 and t01.nombre='Consulta Externa'";
                  
                  
	  $dt = pg_query( $query) ;
	}
	return $dt;
}

function Emergencia(){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT t01.id,
                          t01.nombre
                    FROM ctl_area_atencion t01
                    WHERE t01.id=2 and t01.nombre='Emergencia'";
                  
                  
	  $dt = pg_query( $query) ;
	}
	return $dt;
}




function consultaHospitalizacion(){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT t01.id,
                          t01.nombre
                    FROM ctl_area_atencion t01
                    WHERE t01.id=3 and t01.nombre='Hospitalización'";
                  
                  
	  $dt = pg_query( $query) ;
	}
	return $dt;
}







function LlenarSubServ($proce,$lugar)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= "with tbl_servicio as (select mnt_3.id,
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
                             WHEN not exists (select nombre_ambiente
                                            from mnt_aten_area_mod_estab maame
                                            join mnt_area_mod_estab mame on (maame.id_area_mod_estab = mame.id)
                                            where nombre_ambiente=cat.nombre
                                            and mame.id_area_atencion=mnt_2.id_area_atencion)
                                THEN cmo.nombre||'-'||cat.nombre
                        END
                        END AS servicio
                        from ctl_atencion cat
                        join mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                        join mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                        JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion=1)
                        LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                        LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                        join mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                        join ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                        where  mnt_2.id=$proce
                        and mnt_3.id_establecimiento=$lugar
                        order by 2)
                        select id, servicio from tbl_servicio where servicio is not null";
                        
                   

		$dt = pg_query($sqlText) ;
	}
	return $dt;
 }
 
}//CLASE
?>
