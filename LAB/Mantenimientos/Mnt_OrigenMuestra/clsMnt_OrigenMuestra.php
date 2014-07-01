<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsMnt_OrigenMuestra
{
 //constructor	
 function clsMnt_OrigenMuestra(){
 }	
 
 function insertar($nombreorigen,$tipomuestra,$usuario)
 //insertar($idorigen,$nombreorigen,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO mnt_origenmuestra(origenmuestra,IdTipoMuestra,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$nombreorigen',$tipomuestra,'$usuario',NOW(),'$usuario',NOW())";
    $result = @mysql_query($query);
	//echo $result; 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }


 function actualizar($idorigen,$idtipomuestra,$nombreorigen,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query = "UPDATE mnt_origenmuestra 
   SET IdTipoMuestra='$idtipomuestra', OrigenMuestra='$nombreorigen', IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdOrigenMuestra='$idorigen'";
   
     $result = @mysql_query($query);
	 if (!$result)
       return false;
     else
       return true;
   }
       
 }
  
  
  
 function eliminar($idorigen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM mnt_origenmuestra WHERE idorigenmuestra=$idorigen";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 // consulta las areas de la BD
 function consultar(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from mnt_origenmuestra";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

//*******************************FUNCIONES PARA MANEJO DE PAGINACION************************************/
 //consultando el numero de registros de la tabla
   function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from mnt_origenmuestra";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
		   
  function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from mnt_origenmuestra order by idorigenmuestra LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
  // consulta empleado por su codigo
 function consultarid($idorigen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT mnt_origenmuestra.IdOrigenMuestra,lab_tipomuestra.IdTipoMuestra as idtipomuestra ,lab_tipomuestra.TipoMuestra as tipomuestra,mnt_origenmuestra.OrigenMuestra,mnt_origenmuestra.idusuarioreg FROM mnt_origenmuestra  
inner join lab_tipomuestra on lab_tipomuestra.IdTipoMuestra=mnt_origenmuestra.IdTipoMuestra
WHERE idorigenmuestra=$idorigen";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
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

 //*********************** FUNCION PARA OBTENER LOS NOMBRES DE TIPOS DE MUESTRA*****************************/
 function LeerTipoMuestra()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_tipomuestra";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
}//CLASE
?>
