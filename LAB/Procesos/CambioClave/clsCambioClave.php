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
  /*  $query = "SELECT idempleado from mnt_empleado WHERE id_tipo_empleado='LAB' 
			   AND correlativo=$usuario AND id_establecimiento=$lugar";
    
    */
       
                 $query = "   SELECT mne.id from mnt_empleado mne
                                    join fos_user_user fuu on (fuu.id_empleado=mne.id)
                                    where fuu.id=$usuario
                                    and fuu.id_establecimiento=$lugar";
       
       
	 $result = pg_query($query);
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
   $query = "SELECT * FROM fos_user_user  WHERE id='$cod' AND id_establecimiento=$lugar AND password= MD5('$cActual')";
     $result = pg_query($query);
	 
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
    
        $query = "UPDATE fos_user_user SET password=md5('$nClave') WHERE id='$cod' AND id_establecimiento=$lugar";	
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
 }


}//CLASE

?>
