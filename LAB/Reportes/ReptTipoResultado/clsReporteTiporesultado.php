<?php
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsReporteTiporesultado
{
	 //constructor	
	 function clsReporteTiporesultado()
	 {
	 }		 
          function codigoresultado(){
	$con = new ConexionBD;
		if($con->conectar()==true) 
		{ $query= "select * from lab_codigosresultados ";
                        
			$result = @pg_query($query);
			if (!$result)
			return false;
			else
			return $result;	
		}
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
    
     function ContarNumeroDeRegistros($query) {
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
    
function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
    $query ="select * from lab_codigosresultados";
     
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
	     $query=/*"SELECT $cadena NombreSubServicio as origen, 
sum(if(sec_detallesolicitudestudios.IdExamen<>'',1,0)) as total ,mnt_servicio.IdServicio, mnt_servicio.NombreServicio 
from sec_detallesolicitudestudios 
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
AND (lab_resultados.FechaHoraReg >='$ffechaini' AND lab_resultados.FechaHoraReg <='$ffechafin') 
AND mnt_subservicio.IdServicio='$servicio' 
GROUP BY sec_historial_clinico.IdSubServicio ORDER BY sec_historial_clinico.IdSubServicio";*/
                  " SELECT   $cadena 
                        SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total,
                        t05.nombrearea nombrearea, 
                        t13.nombre as servicio , 
                        t14.nombre as establecimiento , 
                        t11.nombre as subservicio, 
                        t03.nombre_examen nombre_examen
                        FROM sec_detallesolicitudestudios               t01
                        INNER JOIN lab_resultados  			t02	ON (t01.id=t02.iddetallesolicitud)
                        INNER JOIN lab_conf_examen_estab    		t03	ON (t01.id_conf_examen_estab=t03.id) 
                        INNER JOIN mnt_area_examen_establecimiento 	t04 	ON (t04.id=t03.idexamen)
                        INNER JOIN ctl_area_servicio_diagnostico 	t05	ON (t05.id=t04.id_area_servicio_diagnostico)
                        INNER JOIN lab_areasxestablecimiento 		t06	ON (t06.idarea=t05.id)
                        INNER JOIN sec_solicitudestudios 		t07	ON (t07.id=t01.idsolicitudestudio) 
                        left  JOIN sec_historial_clinico 		t08	ON (t07.id_historial_clinico=t08.id) 
                        INNER JOIN mnt_aten_area_mod_estab 		t10     ON (t10.id = t08.idsubservicio) 
                        INNER JOIN ctl_atencion 			t11     ON (t11.id = t10.id_atencion) 
                        INNER JOIN mnt_area_mod_estab 			t12     ON (t12.id = t10.id_area_mod_estab) 
                        INNER JOIN ctl_area_atencion 			t13     ON (t13.id = t12.id_area_atencion) 
                        INNER JOIN ctl_establecimiento 			t14     ON (t14.id = t08.idestablecimiento)
                        WHERE t01.estadodetalle=(select id from ctl_estado_servicio_diagnostico where idestado='RC') 
                            AND (t06.condicion='H')
                            AND (t06.idestablecimiento=$lugar)  
                            AND (t02 .fechahorareg >='".$ffechaini."' AND t02 .fechahorareg <='".$ffechafin."') 
                            AND  id_area_atencion= $servicio  
                                GROUP BY
                                    t05.nombrearea, 
                                    t13.nombre, 
                                    t14.nombre,
                                    t11.nombre, 
                                    t03.nombre_examen 
                  
                    union 
                    
                        select 
                        $cadena 
                        SUM (CASE WHEN t01.id_conf_examen_estab<>1 THEN 1 else 0 END )AS total,
                        t05.nombrearea nombrearea, 
                        t13.nombre as servicio , 
                        t14.nombre as establecimiento , 
                        t11.nombre as subservicio, 
                        t03.nombre_examen nombre_examen
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
                                    t05.nombrearea, 
                                    t13.nombre, 
                                    t14.nombre, t11.nombre, 
                                    t03.nombre_examen ";
          
                  
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
                    WHERE t01.id=1";
                  
                  
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
                    WHERE t01.id=2";
                  
                  
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
                    WHERE t01.id=3";
                  
                  
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
 //Fn_PG
 function reporte_tiporesultado($idarea, $idexamen, $fechaini, $fechafin, $cond1){
    $con = new ConexionBD;
	if($con->conectar()==true){
           $cadena="";
    $sql1=$this->codigoresultado();
    while ($rowareas=pg_fetch_array($sql1)){
        $si=$rowareas['id'];
        $cadena=$cadena."count (CASE WHEN t02.id=$si THEN '$si'  else null END )AS codigo$si,";
    }
    //echo 'KDENA:'.$cadena.' <br>';
    
//    $sql="select count(*) as total, 
//count (CASE WHEN t02.id=1 THEN 'uno' else null END )AS codigo1,
//count (CASE WHEN t02.id=2 THEN 'dos' else null END )AS codigo2,
//count (CASE WHEN t02.id=3 THEN 'tres' else null END )AS codigo3,
//count (CASE WHEN t02.id=4 THEN '4' else null END )AS codigo4,
//count (CASE WHEN t02.id=5 THEN '5' else null END )AS codigo5,
//count (CASE WHEN t02.id=6 THEN '6' else null END )AS codigo6,
//count (CASE WHEN t02.id=7 THEN '7' else null END )AS codigo7,
//count (CASE WHEN t02.id=8 THEN '8' else null END )AS codigo8,
//count (CASE WHEN t02.id=9 THEN '9' else null END )AS codigo9, 
//id_examen_metodologia, lce.nombre_examen, t04.nombrearea
//from lab_resultado_metodologia lrm 
//join lab_conf_examen_estab lce on (lce.id=lrm.id_examen_metodologia)
//join lab_codigosresultados t02 ON (t02.id=lrm.id_codigoresultado) 
//join mnt_area_examen_establecimiento t03 on (t03.id=lce.idexamen)
//join ctl_area_servicio_diagnostico t04 on (t04.id=t03.id_area_servicio_diagnostico)
//group by id_examen_metodologia, lce.nombre_examen, t04.nombrearea
//order by nombrearea, nombre_examen
//";
    
    $sql="SELECT t03.nombre_examen as v_nombreprueba,$cadena
                 count(*) as total 
                from lab_resultado_metodologia t01 
                JOIN lab_codigosresultados t02 ON (t02.id=t01.id_codigoresultado)
                join lab_conf_examen_estab t03 on (t03.id=t01.id_examen_metodologia)
                JOIN mnt_area_examen_establecimiento t04 ON (t04.id = t03.idexamen) 
                JOIN ctl_area_servicio_diagnostico t05 ON (t05.id = t04.id_area_servicio_diagnostico) 
                where $cond1 

                group by t01.id_examen_metodologia, t03.nombre_examen,t05.nombrearea
                order by t03.nombre_examen";
    $result=pg_query($sql);
//echo 'SQl<br>'.$sql.'<br>';
    if (!$result)
       return false;
    else {
       return $result;
    }
   }
 }
 
}//CLASE
?>