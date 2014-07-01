<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");
class clsLab_ElementosTincion
{
 //constructor	
 function clsLab_ElementosTincion(){
 }	
 
 function insertar($ElemTin,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_elementostincion (ElementoTincion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$ElemTin','$usuario',NOW(),'$usuario',NOW())";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 function actualizar($idElemTin,$ElemTin,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_elementostincion SET ElementoTincion='$ElemTin',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdElementosTincion ='$idElemTin'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
  function eliminar($idElemTin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_elementostincion WHERE IdElementosTincion ='$idElemTin'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idElemTin)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_examenes WHERE IdElementosTincion='$idElemTin'";
     $result = @mysql_query($query);
	 $cuenta = mysql_num_rows($result);
	 
     if ($cuenta > 0)
       return true;
     else
       return false;
    }
}
 // consulta las areas de la BD
 function consultarpag($RegistrosAEmpezar,$RegistrosAMostrar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_elementostincion ORDER BY IdElementosTincion LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
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
     $query = "SELECT * FROM lab_elementostincion ORDER BY IdElementosTincion ";
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
     $query = "SELECT * FROM lab_elementostincion ";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
  // consulta empleado por su codigo
 function consultarid($idElemTin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_elementostincion  WHERE IdElementosTincion='$idElemTin'";
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

class clsLabor_ElementosTincion{

	 function insertar_labo($ElemTin,$usuario)
	 {
		$con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
			$query = "INSERT INTO laboratorio.lab_elementostincion (ElementoTincion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$ElemTin','$usuario',NOW(),'$usuario',NOW())";
			$result = @mysql_query($query);
		 
		if (!$result)
		   return false;
		else
		   return true;	   
	   }
	 }
	 
	 function actualizar_labo($idElemTin,$ElemTin,$usuario)
	 {
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true){
		 $query = "UPDATE laboratorio.lab_elementostincion SET ElementoTincion='$ElemTin',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdElementosTincion ='$idElemTin'";
		 $result = @mysql_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;
		   
	   }
	 }
	 
	 function eliminar_labo($idElemTin)
	 {
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true){
		 $query = "DELETE FROM laboratorio.lab_elementostincion WHERE IdElementosTincion ='$idElemTin'";
		 $result = @mysql_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;
		   
	   }
	 }
 //FUNCION

 }
?>
