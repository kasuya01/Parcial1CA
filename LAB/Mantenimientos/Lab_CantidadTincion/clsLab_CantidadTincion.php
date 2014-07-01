<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_CantidadTincion
{
 //constructor	
 function clsLab_CantidadTincion(){
 }	
 
 function insertar($CanTin,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_cantidadestincion(CantidadTincion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$CanTin','$usuario',NOW(),'$usuario',NOW())";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 function actualizar($idCanTin,$CanTin,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_cantidadestincion SET CantidadTincion='$CanTin',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdCantidadesTincion='$idCanTin'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
  function eliminar($idCanTin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_cantidadestincion WHERE IdCantidadesTincion='$idCanTin'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idCanTin)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_examenes WHERE IdCantidadesTincion='$idCanTin'";
     $result = @mysql_query($query);
	 $cuenta = mysql_num_rows($result);
	 
     if ($cuenta > 0)
       return true;
     else
       return false;
    }
}
 // consulta las areas de la BD
 function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_cantidadestincion order by IdCantidadesTincion LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
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
     $query = "select * from lab_cantidadestincion order by IdCantidadesTincion";
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
     $query = "select * from lab_cantidadestincion ";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
  // consulta empleado por su codigo
 function consultarid($idCanTin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_cantidadestincion WHERE IdCantidadesTincion='$idCanTin'";
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

class clsLabor_CantidadTincion
{

	 function insertar_labo($CanTin,$usuario)
	 {
		$con2 = new ConexionBDLab;
		if($con2->conectarT()==true){ 
		
			$query = "INSERT INTO laboratorio.lab_cantidadestincion(CantidadTincion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$CanTin','$usuario',NOW(),'$usuario',NOW())";
			$result = @mysql_query($query);
		 
			if (!$result)
				return false;
			else
				return true;	   
		}
	}
 
	function actualizar_labo($idCanTin,$CanTin,$usuario)
	{
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true){
			$query = "UPDATE laboratorio.lab_cantidadestincion SET CantidadTincion='$CanTin',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdCantidadesTincion='$idCanTin'";
			$result = @mysql_query($query);
			 
			if (!$result)
				return false;
			else
				return true;
			   
		}
	 }
	 
	 function eliminar_labo($idCanTin)
	 {
		$con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
			$query = "DELETE FROM laboratorio.lab_cantidadestincion WHERE IdCantidadesTincion='$idCanTin'";
			$result = @mysql_query($query);
		 
			if (!$result)
				return false;
			else
				return true;
		   
		}
	 }

}
?>
