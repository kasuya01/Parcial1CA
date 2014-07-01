<?php 
include_once("../../../Conexion/ConexionBD.php");
class clsLab_TipoMuestra
{
 //constructor	
 function clsLab_TipoMuestra()
 {
 }	
 
 function insertar($nombretipo,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_tipomuestra(tipomuestra,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$nombretipo','$usuario',NOW(),'$usuario',NOW())";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 function actualizar($idtipo,$nombretipo,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_tipomuestra SET tipomuestra='$nombretipo',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE idtipomuestra=$idtipo";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 
  function eliminar($idtipo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_tipomuestra WHERE idtipomuestra=$idtipo";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 
 function consultar(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_tipomuestra";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idtipo)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_tipomuestraporexamen WHERE idTipoMuestra=$idtipo";
     $result = @mysql_query($query);
	 $cuenta = mysql_num_rows($result);
	 /* if ($cuenta > 0)
       return true;
     else
       return false; */
	   return $cuenta ;
    }
}

//************************************************FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 //consultando el numero de registros de la tabla
   function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_tipomuestra";
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
     $query = "select * from lab_tipomuestra order by Idtipomuestra LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
  // consulta empleado por su codigo
 function consultarid($idtipo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_tipomuestra WHERE idtipomuestra=$idtipo";
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

}//CLASE
?>
