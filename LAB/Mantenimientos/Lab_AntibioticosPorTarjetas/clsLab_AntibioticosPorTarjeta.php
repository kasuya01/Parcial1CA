<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_AntibioticosPorTarjeta
{
 //constructor	
 function clsLab_AntibioticosPorTarjeta(){
 }	
 
 function insertar($idantibiotico,$idtarjeta,$usuario,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   
    $query = "INSERT INTO lab_antibioticosportarjeta(IdAntibiotico,IdTarjeta,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento) 
			  VALUES($idantibiotico,$idtarjeta,$usuario,NOW(),$usuario,NOW(),$lugar)";
    $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 function actualizar($idantibioticoportarjeta,$idantibiotico,$idtarjeta,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_antibioticosportarjeta SET idantibiotico=$idantibiotico, idtarjeta=$idtarjeta,IdUsuarioMod=$usuario,FechaHoraMod=NOW() 
			   WHERE IdAntibioticoPorTarjeta=$idantibioticoportarjeta";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
  function eliminar($idantibiotico,$idtarjeta,$lugar)
  {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_antibioticosportarjeta WHERE IdAntibiotico=$idantibiotico AND IdTarjeta=$idtarjeta AND IdEstablecimiento=$lugar";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
  }
 // FUNCION PARA VERIFICAR SI ANTIBIOTICO EXISTE
function Verificar_Antibiotico($idantibiotico,$idtarjeta,$lugar){
 $con = new ConexionBD;
	if($con->conectar()==true)
	{ $query="SELECT count(*) FROM lab_antibioticosportarjeta WHERE IdAntibiotico=$idantibiotico and Idtarjeta=$idtarjeta and IdEstablecimiento=$lugar";
		$result = mysql_query($query);
		if (!$result)
			return false;
		else
			return $result;
	}
  }
  
 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
/*function VerificarIntegridad($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_antibioticosportarjetaportarjeta WHERE idantibiotico='$idantibiotico'";
     $result = @mysql_query($query);
	 $cuenta = mysql_num_rows($result);
	 
     if ($cuenta > 0)
       return true;
     else
       return false;
    }
}*/
 // consulta las areas de la BD
/* function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_antibioticosportarjeta ORDER BY idantibiotico LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } */
  
  
 function consultar($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_antibioticosportarjeta WHERE IdEstablecimiento=$lugar ORDER BY idantibiotico ";
	 $result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

 function consultarasociados($idtarjeta)
 {
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select IdAntibioticoPorTarjeta,A.IdAntibiotico, A.IdTarjeta, Antibiotico
			    from lab_antibioticosportarjeta A
				inner join lab_antibioticos B on A.IdAntibiotico=B.IdAntibiotico
				where idtarjeta=$idtarjeta" ;
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
     $query = "select * from lab_antibioticosportarjeta ";
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
     $query = "SELECT * FROM lab_antibioticosportarjeta WHERE idantibiotico='$idantibiotico'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }


}//CLASE
?>
