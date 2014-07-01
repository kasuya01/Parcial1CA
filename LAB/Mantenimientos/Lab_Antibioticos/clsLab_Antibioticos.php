<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Antibioticos
{
 //constructor	
 function clsLab_Antibioticos(){
 }	
 
 function insertar($antibiotico,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_antibioticos(Antibiotico,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$antibiotico','$usuario',NOW(),'$usuario',NOW())";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

function actualizar($idantibiotico,$antibiotico,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_antibioticos SET Antibiotico='$antibiotico',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdAntibiotico='$idantibiotico'";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

function eliminar($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_antibioticos WHERE IdAntibiotico='$idantibiotico'";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
}

 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_antibioticosportarjeta WHERE IdAntibiotico='$idantibiotico'";
     $result = mysql_query($query);
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
     $query = "SELECT * FROM lab_antibioticos ORDER BY IdAntibiotico LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
	 $result = mysql_query($query);
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
     $query = "SELECT * FROM lab_antibioticos ORDER BY IdAntibiotico";
	 $result = mysql_query($query);
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
		 $result = mysql_query($query);
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
     $query = "SELECT * FROM lab_antibioticos ";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
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

// consulta empleado por su codigo
function consultarid($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_antibioticos WHERE IdAntibiotico='$idantibiotico'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
  
}//CLASE

class clsLabor_Antibioticos
{
	/*function insertar_labo($antibiotico,$usuario)
	 {
	    $con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
		$query = "INSERT INTO laboratorio.lab_antibioticos(Antibiotico,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$antibiotico','$usuario',NOW(),'$usuario',NOW())";
		 $result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return true;	   
	   }
	 }

	function actualizar_labo($idantibiotico,$antibiotico,$usuario)
	 {
	   $con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
		 $query = "UPDATE laboratorio.lab_antibioticos SET Antibiotico='$antibiotico',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdAntibiotico='$idantibiotico'";
		 $result = mysql_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;
		   
	   }
	 }

	function eliminar_labo($idantibiotico)
	{
	    $con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
		 $query = "DELETE FROM laboratorio.lab_antibioticos WHERE IdAntibiotico='$idantibiotico'";
		 $result = mysql_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;
		   
	   }
	}*/
}
?>
