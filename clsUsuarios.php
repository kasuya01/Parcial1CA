<?php 
include_once("../Laboratorio/Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsUsuarios
{
 //constructor	
 function clsUsuarios(){
 }
// validaci�n de existencia de usuario
function validarexistencia($login,$password)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "SELECT count(nivel) as numreg FROM mnt_usuarios 
              WHERE login='$login' AND password= md5('$password') AND modulo='LAB'";
     $result = @mysql_query($query);	 
     if (!$result)
       return false;
     else
     {
	$row = mysql_fetch_array($result);
        return $row[0];
		 	  
      } 
   }
 }

 //devuelve el nivel del usuario logeado
 function obtenernivel($login,$password){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select nivel from mnt_usuarios 
          where login='$login' and password= md5('$password') and modulo='LAB'";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else{
	     $row = mysql_fetch_array($result);
         return $row[0];
		 }
   }
  } 


 //devuelve el establecimiento del usuario logeado
 function obtenerlugar($login,$password){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT IdEstablecimiento FROM mnt_usuarios 
       INNER JOIN mnt_empleados ON mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado 
          where login='$login' and password= md5('$password') and modulo='LAB'";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else{
	     $row = mysql_fetch_array($result);
         return $row[0];
		 }
   }
  }

 //devuelve el nivel del usuario logeado
 function obtenerarea($login,$password){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select nivel from mnt_usuarios 
          where login='$login' and password= md5('$password') and modulo='LAB'";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else{
	     $row = mysql_fetch_array($result);
         return $row[0];
		 }
   }
  } 

 //devuelve los datos del usuario logeado
 function datosusuario($login,$password){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT mnt_empleados.IdEstablecimiento, mnt_empleados.IdArea,mnt_usuarios.nivel AS NIVEL,
		mnt_empleados.IdEmpleado,mnt_empleados.Correlativo, mnt_usuarios.iduser, area
		FROM mnt_usuarios 
		INNER JOIN mnt_empleados 
		ON (mnt_usuarios.IdEmpleado=mnt_empleados.IdEmpleado AND mnt_usuarios.IdEstablecimiento=mnt_empleados.IdEstablecimiento) 
		WHERE login='$login' AND password= md5('$password') AND modulo='LAB'";
      $result = @mysql_query($query);
      if (!$result)
	   return false;
      else{
	   return $result;
      }
   }
  } 

 
}//CLASE
?>
