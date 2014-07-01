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
		{ $query="SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
			INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			WHERE lab_areasxestablecimiento.IdEstablecimiento=$lugar AND lab_areas.Administrativa = 'N' 
                        AND lab_areasxestablecimiento.Condicion='H' ORDER BY NombreArea";
			$result = @mysql_query($query);
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
     $query ="SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
	      INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
	      WHERE lab_areasxestablecimiento.IdEstablecimiento=$lugar AND lab_areas.Administrativa='N' 
              AND lab_areasxestablecimiento.Condicion='H' 
              ORDER BY NombreArea";
	 $numreg = mysql_num_rows(mysql_query($query));
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
	    $result = @mysql_query($query);
	    if (!$result)
		   return false;
	    else
		   return $result;
	   }  

 }
	 
 
	 
function consultarareas($lugar){
	$con = new ConexionBD;
	if($con->conectar()==true){
	 $query="SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
			 INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			 WHERE Condicion='H'  AND IdEstablecimiento=$lugar AND Administrativa = 'N'
			 ORDER BY  NombreArea ASC";
	 $dt = mysql_query($query) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}
	
function consultaSubservicios($procedencia){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT IdSubServicio,NombreSubservicio,IdServicio FROM mnt_subservicio 
	  WHERE IdServicio='$procedencia'";
	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}


function NombreServicio($procedencia){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="select NombreServicio from mnt_servicio where IdServicio='$procedencia'";
	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function subserviciosxservicio($servicio,$ffechaini,$ffechafin,$cadena,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT $cadena NombreSubServicio as origen, 
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
GROUP BY sec_historial_clinico.IdSubServicio ORDER BY sec_historial_clinico.IdSubServicio";
 $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;

}

/*function cantidadxservicio($servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
 $query="SELECT count(*) as cantidad
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
AND (lab_resultados.FechaHoraReg >='$ffechaini' AND lab_resultados.FechaHoraReg <='$ffechafin') 
AND mnt_subservicio.IdServicio='$servicio' ORDER BY sec_historial_clinico.IdSubServicio";
 $numreg = mysql_query($query);
 if (!$numreg )
   return false;
 else
   return $numreg ;
}
}*/

function cantidadxservicio($servicio,$ffechaini,$ffechafin,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
 $query="SELECT count(mnt_subservicio.IdServicio) as cantidad
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
AND mnt_subservicio.IdServicio='$servicio'";
$numreg =mysql_fetch_array(mysql_query($query));
	 
	  return $numreg[0] ;
 

}
}

/*function consultaTodosServicios(){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT distinct mnt_subservicio.IdServicio, mnt_servicio.NombreServicio FROM   	  mnt_subservicio INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio 
	  ORDER BY mnt_subservicio.IdServicio";
	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}*/	
function consultaTodosServicios($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT distinct mnt_subservicio.IdServicio, mnt_servicio.NombreServicio 
FROM  mnt_subservicio 
INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio=mnt_servicio.IdServicio 
INNER JOIN mnt_servicioxestablecimiento ON mnt_servicio.IdServicio=mnt_servicioxestablecimiento.IdServicio
WHERE mnt_servicioxestablecimiento.IdEstablecimiento=$lugar
ORDER BY mnt_subservicio.IdServicio";
	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}		
function LlenarSubServ($proce)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT NombreSubServicio,IdSubServicio 
FROM mnt_subservicio where IdServicio='$proce'
order by NombreSubServicio";

		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
 }
 
}//CLASE
?>
