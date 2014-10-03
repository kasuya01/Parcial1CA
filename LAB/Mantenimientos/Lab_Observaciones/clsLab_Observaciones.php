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
    echo "insertar--> ".$query = "INSERT INTO lab_observaciones(observacion,idarea,tiporespuesta,idusuarioreg,fechahorareg,idusuariomod,fechahoramod) 
	          VALUES('$observacion','$idarea','$tiporespuesta','$usuario',NOW(),'$usuario',NOW())";
     $result = @pg_query($query);
	 
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
     $query = "UPDATE lab_observaciones SET observacion='$observacion', tiporespuesta='$tiporespuesta',idusuariomod='$usuario',fechahoramod=NOW() WHERE id=$idobservacion";
     $result = @pg_query($query);
	 
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
     $query = "DELETE FROM lab_observaciones WHERE id='$idobservacion'";
     $result = @pg_query($query);
	 
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
    $query = /*"SELECT id, observacion, tiporespuesta 
			   FROM lab_observaciones 
                           order by id 
			   LIMIT $RegistrosAMostrar OFFSET  $RegistrosAEmpezar";*/
     
     "SELECT lb1.id, c1.idarea, observacion, tiporespuesta 
			   FROM lab_observaciones lb1
			   inner join ctl_area_servicio_diagnostico c1 on (c1.id=lb1.idarea)
                           order by c1.idarea
			   LIMIT $RegistrosAMostrar OFFSET  $RegistrosAEmpezar";
	 $result = @pg_query($query);
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
     $query = "select * from lab_observaciones order by id ";
	 $result = @pg_query($query);
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
	 $numreg = pg_num_rows(pg_query($query));
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
    $query = "SELECT lab_observaciones.observacion,lab_observaciones.tiporespuesta,
		lab_observaciones.idarea,ctl_area_servicio_diagnostico.nombrearea FROM lab_observaciones 
	        INNER JOIN ctl_area_servicio_diagnostico ON lab_observaciones.idarea=ctl_area_servicio_diagnostico.id
		WHERE lab_observaciones.id=$idobservacion";
             
   
     
     
     $result = @pg_query($query);
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
	     $numreg = pg_num_rows(pg_query($query));
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
	    $query = $query." LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar ";
		 $result = @pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
  } 	 
  
}//CLASE
?>
