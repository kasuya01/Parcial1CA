<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsCambioClave
{
 //constructor	
 function clsCambioClave(){
 }

 function ObtenerCodigoEmpleado($usuario,$lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT IdEmpleado from mnt_empleados WHERE IdTipoEmpleado='LAB' 
			   AND Correlativo=$usuario AND IdEstablecimiento=$lugar";
	 $result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;


   }
} 	



 function VerificarClave($cActual,$cod,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "SELECT * FROM mnt_usuarios  WHERE IdEmpleado='$cod' AND IdEstablecimiento=$lugar AND password= MD5('$cActual')";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
   }
 }

/*Función para actualizar datos de una área*/
function ActualizarClave($nClave,$lugar,$cod)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    
     $query = "UPDATE mnt_usuarios SET password=md5('$nClave') WHERE IdEmpleado='$cod' AND IdEstablecimiento=$lugar";	
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
 }


}//CLASE

?>
