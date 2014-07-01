<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_Observaciones
{
 //constructor	
 function clsLab_Observaciones(){
 }	
 
 function insertar($idarea,$observacion,$tiporespuesta,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_observaciones(Observacion,IdArea,TipoRespuesta,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
	          VALUES('$observacion','$idarea','$tiporespuesta','$usuario',NOW(),'$usuario',NOW())";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 function actualizar($idobservacion,$observacion,$tiporespuesta,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_observaciones SET Observacion='$observacion', TipoRespuesta='$tiporespuesta',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdObservacion=$idobservacion";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
  function eliminar($idobservacion)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_observaciones WHERE IdObservacion='$idobservacion'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 
 function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT IdObservacion,IdArea,Observacion,TipoRespuesta 
			   FROM lab_observaciones order by IdObservacion 
			   LIMIT $RegistrosAEmpezar,$RegistrosAMostrar";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
 function consultar(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_observaciones order by IdObservacion ";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  //consultando el numero de registros de la tabla
   function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_observaciones ";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
  // consulta empleado por su codigo
 function consultarid($idobservacion)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
      $query = "SELECT lab_observaciones.Observacion,lab_observaciones.TipoRespuesta,
		lab_observaciones.IdArea,lab_areas.NombreArea FROM lab_observaciones 
	        INNER JOIN lab_areas ON lab_observaciones.IdArea=lab_areas.IdArea
		WHERE IdObservacion=$idobservacion";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

 function NumeroDeRegistrosbus($query){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $numreg = mysql_num_rows(mysql_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
  }	   

 function consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
  } 	 
  
}//CLASE
?>
