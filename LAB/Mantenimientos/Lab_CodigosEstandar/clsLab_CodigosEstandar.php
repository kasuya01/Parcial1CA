<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_CodigosEstandar
{
 //constructor	
 function clsLab_CodigosEstandar(){
 }	
 
 function insertar($idestandar,$descripcion, $usuario,$grupo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_codigosestandar(idestandar,descripcion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,idgrupo) 
              VALUES('$idestandar','$descripcion','$usuario',NOW(),'$usuario',NOW(),$grupo)";
    //echo $query;
    
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 function actualizar($idestandar,$descripcion,$grupo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_codigosestandar SET descripcion='$descripcion',idgrupo='$grupo' 
               WHERE idestandar='$idestandar'";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
  function eliminar($idestandar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_codigosestandar WHERE id=$idestandar";
     //echo $query;
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idestandar)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_examenes WHERE id='$idestandar'";
     $result = pg_query($query);
	 $cuenta = pg_num_rows($result);
	 
     //if ($cuenta > 0)
       return $cuenta;
     //else
       //return false;
    //}
	}
}

 
 // consulta las areas de la BD
 function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query ="SELECT lab_codigosestandar.id,lab_codigosestandar.idestandar,lab_codigosestandar.descripcion,
              lab_codigosestandar.idgrupo,lab_estandarxgrupo.nombregrupo 
              FROM lab_codigosestandar 
              LEFT JOIN lab_estandarxgrupo 
              ON lab_codigosestandar.idgrupo=lab_estandarxgrupo.id  
              LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar "; 
           
    // echo $query;
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  //FUNCION PARA LEER grupos de los códigos estandar
function Leer_grupos()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     
     $query = "SELECT id,idgrupo,nombregrupo FROM lab_estandarxgrupo ORDER BY id";
  
     $result = pg_query($query);
    
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
     $query = "SELECT * FROM lab_codigosestandar ORDER BY idEstandar ";
	 $result = pg_query($query);
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
     $query = "SELECT * FROM lab_codigosestandar ";
	 $numreg = pg_num_rows(pg_query($query));
        // echo $numreg;
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
  // consulta empleado por su codigo
 function consultarid($idestandar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT idestandar,descripcion,lab_codigosestandar.idgrupo,nombregrupo 
               FROM lab_codigosestandar 
               LEFT JOIN lab_estandarxgrupo 
               ON lab_codigosestandar.idgrupo=lab_estandarxgrupo.id
               WHERE lab_codigosestandar.id=$idestandar";
     //echo $query;
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
  function ConsultarxGrupo($idgrupo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT IdGrupo, NombreGrupo
            FROM lab_estandarxgrupo ";
     $result = pg_query($query);
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
	     $query = $query." LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
	     $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
  } 	 
}//CLASE
?>
