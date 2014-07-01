<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsReporteTabuladores
{
	 //constructor	
	 function clsReporteTabuladores()
	 {
	 }	
	 
	
 
    function LeerCodigos(){
	$con = new ConexionBD;
		if($con->conectar()==true) 
		{ $query="select IdResultado from lab_codigosresultados";
			$result = @mysql_query($query);
			if (!$result)
			return false;
			else
			return $result;	
		}
      }
	  
	function LeerProcedencias(){
	$con = new ConexionBD;
		if($con->conectar()==true) 
		{ $query="SELECT IdProcedencia,NombreServicio 
			  FROM mnt_servicio 
			  WHERE IdTipoServicio='CON' ORDER BY IdProcedencia";
			$result = @mysql_query($query);
			if (!$result)
				return false;
			else
				return $result;	
		}
	
	}
	
	function NumeroDeProcedencias(){
		 //creamos el objeto $con a partir de la clase ConexionBD
		$con = new ConexionBD;
		//usamos el metodo conectar para realizar la conexion
		if($con->conectar()==true){
			$query ="SELECT IdProcedencia 
				  FROM mnt_servicio 
				  WHERE IdTipoServicio='CON'";
			$numreg = mysql_num_rows(mysql_query($query));
			 if (!$numreg )
			   return false;
			 else
			   return $numreg ;
	   }
	}
	
  // consulta las areas de la BD
 function consultaractivas($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea,NombreArea 
               FROM lab_areas 
               INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea= lab_areasxestablecimiento.IdArea
               WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areas.Administrativa='N' 
	       AND lab_areasxestablecimiento.IdEstablecimiento=$lugar 
	       ORDER BY NombreArea";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  	
	
function NumeroDeCodigos(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query ="select IdResultado from lab_codigosresultados";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }

	 //FUNCION PARA MOSTRAR DATOS DE BUSQUEDA
 function BuscarExamenesporCodigo($query_search)
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
			 WHERE Condicion='H' AND Administrativa = 'N' AND IdEstablecimiento=$lugar 
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

	
function CodigosEstardarxarea($IdArea,$lugar)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText = "SELECT IdEstandar FROM lab_examenes 
			    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
			    WHERE IdArea='$IdArea' AND lab_examenesxestablecimiento.Condicion = 'H' 
                            AND IdEstablecimiento=$lugar";

		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
 }
 
}//CLASE
?>
