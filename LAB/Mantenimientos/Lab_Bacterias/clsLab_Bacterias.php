<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Bacterias
{
 //constructor	
 function clsLab_Bacterias(){
 }	
 
 function insertar($bacteria,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_bacterias(Bacteria,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$bacteria','$usuario',NOW(),'$usuario',NOW())";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

 function actualizar($idbacteria,$bacteria,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_bacterias SET Bacteria='$bacteria',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdBacteria='$idbacteria'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

  function eliminar($idbacteria)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_bacterias WHERE IdBacteria='$idbacteria'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idbacteria)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_examenes WHERE IdBacteria='$idbacteria'";
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
     $query = "SELECT * FROM lab_bacterias ORDER BY IdBacteria LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
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
     $query = "SELECT * FROM lab_bacterias ORDER BY IdBacteria ";
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

  //consultando el numero de registros de la tabla
   function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_bacterias ";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
  // consulta empleado por su codigo
 function consultarid($idbacteria)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_bacterias WHERE IdBacteria='$idbacteria'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
}//CLASE


class clsLabor_Bacterias
{
/*	function insertar_labo($bacteria,$usuario)
		 {
		    $con2 = new ConexionBDLab;
		    if($con2->conectarT()==true){
			$query = "INSERT INTO laboratorio.lab_bacterias(Bacteria,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$bacteria','$usuario',NOW(),'$usuario',NOW())";
			 $result = @mysql_query($query);
			 
			 if (!$result)
			   return false;
			 else
			   return true;	   
		   }
	 }

	function actualizar_labo($idbacteria,$bacteria,$usuario)
	 {
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true){
			 $query = "UPDATE laboratorio.lab_bacterias SET Bacteria='$bacteria',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdBacteria='$idbacteria'";
			 $result = @mysql_query($query);
			 
			 if (!$result)
			   return false;
			 else
			   return true;
			   
		   }
	   }

	function eliminar_labo($idbacteria)
	 {
	   $con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
			 $query = "DELETE FROM laboratorio.lab_bacterias WHERE IdBacteria='$idbacteria'";
			 $result = @mysql_query($query);
			 
			 if (!$result)
			   return false;
			 else
			   return true;
			   
		 }
	 }*/



}//CLASE

?>
