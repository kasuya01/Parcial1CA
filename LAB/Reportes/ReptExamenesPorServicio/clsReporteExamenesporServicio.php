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
	 
 
	 
function consultarareas($lugar){
	$con = new ConexionBD;
	if($con->conectar()==true){
	 $query=/*"SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
			 INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			 WHERE Condicion='H'  AND IdEstablecimiento=$lugar AND Administrativa = 'N'
			 ORDER BY  NombreArea ASC";*/
                 
                          "SELECT t01.id AS idarea, t01.nombrearea  as nombrearea
                      FROM ctl_area_servicio_diagnostico t01
                      WHERE t01.id IN (SELECT idarea FROM lab_areasxestablecimiento 
                                        WHERE condicion = 'H'  AND idestablecimiento = $lugar)
                      AND t01.administrativa = 'N'
                      ORDER BY nombrearea";
                 
                 
                 
	 $dt = pg_query($query) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}
	
function consultaSubservicios($procedencia){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query=/*"SELECT IdSubServicio,NombreSubservicio,IdServicio FROM mnt_subservicio 
	  WHERE IdServicio='$procedencia'";*/
                  
                  "select id from ctl_area_atencion where id=$procedencia";
                  
                  
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}


function NombreServicio($procedencia){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query=/*"select NombreServicio from mnt_servicio where IdServicio='$procedencia'";*/
                  
                  
                 "SELECT t01.id,
       t01.nombre as servicio
       FROM ctl_area_atencion t01
       WHERE t01.id =$procedencia";
                  
                  
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
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
                            AND  id_area_atencion= $servicio 
                               

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
                            AND  id_area_atencion= $servicio 
                                GROUP BY   
                                        t13.nombre, 
                                        t14.nombre, 
                                        t11.nombre";
          
                  
 $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;

}








function cantidadxservicio1($servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
      $query=/*"SELECT count(mnt_subservicio.IdServicio) as cantidad
FROM sec_detallesolicitudestudios 
INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud 
INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen 
INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea 
INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico 
INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio 
INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio
INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea  
WHERE sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_areasxestablecimiento.Condicion='H' 
AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
AND DATE(lab_resultados.FechaHoraReg) BETWEEN '$ffechaini' AND '$ffechafin' 
AND mnt_subservicio.IdServicio='$servicio'";*/
         
         
         "select    count(t12.id) as cantidad 
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
AND t13.id=$servicio
AND (t06.idestablecimiento=$lugar) ";
         
         
         
$numreg =pg_fetch_array(pg_query($query));
	 
	  return $numreg[0] ;
 

}
}



function cantidadxservicio2($servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
   $query=/*"SELECT count(mnt_subservicio.IdServicio) as cantidad
FROM sec_detallesolicitudestudios 
INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud 
INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen 
INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea 
INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico 
INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio 
INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio
INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea  
WHERE sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_areasxestablecimiento.Condicion='H' 
AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
AND DATE(lab_resultados.FechaHoraReg) BETWEEN '$ffechaini' AND '$ffechafin' 
AND mnt_subservicio.IdServicio='$servicio'";*/
         
         
         "select count(t12.id) as cantidad 
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
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}*/	
function consultaTodosServicios($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query=/*"SELECT distinct mnt_subservicio.IdServicio, mnt_servicio.NombreServicio 
FROM  mnt_subservicio 
INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio 
INNER JOIN mnt_servicioxestablecimiento ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
WHERE mnt_servicioxestablecimiento.IdEstablecimiento=$lugar
ORDER BY mnt_subservicio.IdServicio";*/
                  
                  "SELECT t01.id,
                          t01.nombre
                    FROM ctl_area_atencion t01
                    --WHERE t01.id IN (SELECT DISTINCT id_area_atencion 
                                     --FROM mnt_area_mod_estab WHERE id_establecimiento = $lugar)
                  ";
                  
                  
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
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
                  
                  
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
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
                  
                  
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
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
                  
                  
	  $dt = pg_query( $query) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}







function LlenarSubServ($proce,$lugar)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= /*"SELECT NombreSubServicio,IdSubServicio 
FROM mnt_subservicio where IdServicio='$proce'
order by NombreSubServicio";*/  
                        
                        "WITH tbl_servicio AS (
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
                            WHERE id_area_atencion = $proce and t02.id_establecimiento = $lugar
                            ORDER BY 2)
                        SELECT id, servicio FROM tbl_servicio WHERE servicio IS NOT NULL";

		$dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
 }
 
}//CLASE
?>
