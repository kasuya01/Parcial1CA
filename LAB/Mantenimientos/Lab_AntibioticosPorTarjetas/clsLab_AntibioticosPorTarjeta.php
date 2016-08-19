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
         $query = "INSERT INTO lab_antibioticosportarjeta(idantibiotico,idtarjeta,idusuarioreg,fechahorareg,idestablecimiento) 
		       VALUES($idantibiotico,$idtarjeta,$usuario,date_trunc('seconds', NOW()),$lugar)";
         $result = @pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 function Agregar($idantibiotico,$idtarjeta,$usuario,$lugar)
         
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
       $nextid="select nextval('lab_antibioticosportarjeta_id_seq')";
                    $sql=  pg_query($nextid);
                    $nextseq=  pg_fetch_array($sql);
                    $ultimo=$nextseq[0];
       
       $query =  "INSERT INTO lab_antibioticosportarjeta(id,idantibiotico,idtarjeta,idusuarioreg,fechahorareg,idestablecimiento) 
		  VALUES($ultimo,$idantibiotico,$idtarjeta,$usuario,date_trunc('seconds', NOW()),$lugar)";
     $result = @pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
  function eliminar($idantibiotico,$idtarjeta,$usuario,$lugar)
  {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   // $query = "DELETE FROM lab_antibioticosportarjeta WHERE idantibiotico=$idantibiotico AND idtarjeta=$idtarjeta AND idestablecimiento=$lugar";
       
       $query ="UPDATE lab_antibioticosportarjeta SET habilitado= FALSE,idusuariomod=$usuario,fechahoramod=date_trunc('seconds', NOW())
                WHERE idantibiotico=$idantibiotico AND idtarjeta=$idtarjeta AND idestablecimiento=$lugar";
    
       $result = pg_query($query);
	 
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
	{  $query="SELECT count(*) FROM lab_antibioticosportarjeta WHERE idantibiotico=$idantibiotico and idtarjeta=$idtarjeta and idestablecimiento=$lugar";
	   $result = pg_query($query);
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
     $result = @pg_query($query);
	 $cuenta = pg_num_rows($result);
	 
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
	 $result = @pg_query($query);
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
     $query = "SELECT * FROM lab_antibioticosportarjeta WHERE idestablecimiento=$lugar ORDER BY idantibiotico ";
	 $result = pg_query($query);
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
      /*echo $query = " select A.idantibiotico, A.idtarjeta, antibiotico from lab_antibioticosportarjeta A 
                inner join lab_antibioticos B on A.idantibiotico=A.idtarjeta 
                where idtarjeta=$idtarjeta" ;*/
     $query="select lab_antibioticos.id as 
                antibiotico_id,
                lab_tarjetasvitek.id,
                lab_antibioticos.antibiotico 
		from lab_tarjetasvitek
                inner join lab_antibioticosportarjeta on (lab_tarjetasvitek.id=lab_antibioticosportarjeta.idtarjeta)
                inner join lab_antibioticos 		 on (lab_antibioticosportarjeta.idantibiotico=lab_antibioticos.id)
                   where lab_antibioticosportarjeta.habilitado= TRUE AND lab_tarjetasvitek.id=$idtarjeta 
                   order by antibiotico";
	 $result = @pg_query($query);
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
     $query = "select * from lab_antibioticosportarjeta where lab_antibioticosportarjeta.habilitado= TRUE";
	 $numreg = pg_num_rows(pg_query($query));
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
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }


}//CLASE
?>
