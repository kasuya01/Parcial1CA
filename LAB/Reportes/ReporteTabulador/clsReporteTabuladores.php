<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsReporteTabuladores
{
	 //constructor	
	 function clsReporteTabuladores()
	 {
	 }	
	 
	
 
    function LeerCodigosResultados(){
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

     function ExamenesxArea($IdArea,$lugar){
        $con = new ConexionBD;
	//usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
        	$query ="";  
         
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
			  WHERE IdTipoServicio='CON' OR IdTipoServicio='REF' ORDER BY IdProcedencia";
		$numreg = mysql_num_rows(mysql_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	}
        
 function NumeroDeCodigos(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query ="select IdResultado from lab_codigosresultados";
	 $numreg = mysql_num_rows(mysql_query($query));
         //echo $numreg;
	 if (!$numreg )
	   return false;
	 else
         //  echo $numreg;
	   return $numreg ;
   }
  }   
  
  
  function NumeroDeExamenes($IdArea,$lugar){
     //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query ="SELECT * 
              FROM lab_examenes 
              INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
              WHERE IdArea='$IdArea' AND IdEstablecimiento=$lugar AND Condicion='H'";
     $numreg = mysql_num_rows(mysql_query($query));
         //echo $numreg;
     if (!$numreg )
	return false;
     else
         //  echo $numreg;
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
	 
 function ContarDatos($query_search){
       $con = new ConexionBD;
	 //usamos el metodo conectar para realizar la conexion
	 if($con->conectar()==true){
	    $query = $query_search;
	     $numreg = mysql_num_rows(mysql_query($query));
         //echo $numreg;
             if (!$numreg )
                return 0;
            else
              //  echo $numreg;
                 return $numreg ;
            }  
     
 }
	 
function consultarareas($lugar){
    $con = new ConexionBD;
    if($con->conectar()==true){
     $query="SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
             INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
	     WHERE Condicion='H' AND IdEstablecimiento=$lugar 
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

function DatosGenerales($lugar,$IdArea){
        $con = new ConexionBD;
	if($con->conectar()==true){
	  $query="SELECT Nombre,Nombrearea 
                FROM `lab_areasxestablecimiento`
                INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
                INNER JOIN mnt_establecimiento ON lab_areasxestablecimiento.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
                WHERE lab_areasxestablecimiento.IdArea='$IdArea' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar";
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
            AND IdEstablecimiento=$lugar ORDER BY IdEstandar ASC";
            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
 }
 
 function ExamenesPorArea($idarea,$lugar)
{
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = "SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
		       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		       WHERE IdEstablecimiento = $lugar AND IdArea='$idarea'
		       AND lab_examenesxestablecimiento.Condicion='H' ORDER BY NombreExamen ASC ";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	}
}
 
}//CLASE
?>
