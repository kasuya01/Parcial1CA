<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Posresultado
{
 //constructor	
 function clsLab_Posresultado(){
 }	
 
 function insertar($posreusltado,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_posible_resultado(posible_resultado,
				fechainicio,
				id_user,
				fecha_registro,
                                habilitado) 
			VALUES('$posreusltado',
				date_trunc('seconds',NOW()),
				'$usuario',
				date_trunc('seconds',NOW()),
                                true)";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

function actualizar($idposresultado,$posresultado,$cmbEstado,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_posible_resultado SET posible_resultado='$posresultado',
            habilitado='$cmbEstado',
            id_user='$usuario',
            fecha_mod=date_trunc('seconds',NOW()) 
            WHERE id=$idposresultado";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

/*function eliminar($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_antibioticos WHERE id='$idantibiotico'";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
}*/

 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
/*function VerificarIntegridad($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_antibioticosportarjeta WHERE idantibiotico='$idantibiotico'";
     $result = pg_query($query);
	 $cuenta = pg_num_rows($result);
	 
     if ($cuenta > 0)
       return true;
     else
       return false;
    }
}*/
 
function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select id, 
                posible_resultado,
		(CASE WHEN habilitado='t' THEN 'Habilitado'
		WHEN habilitado='f' THEN 'Inhabilitado' END) AS habilitado
		from lab_posible_resultado ORDER BY posible_resultado LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar  ";
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
} 

/*function consultar(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_antibioticos ORDER BY antibiotico";
                $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
} */

function consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar)
{
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query." LIMIT $RegistrosAMostrar  OFFSET $RegistrosAEmpezar ";
		 $result = pg_query($query);
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
     $query = "SELECT * FROM lab_posible_resultado ";
	 $numreg = pg_num_rows(pg_query($query));
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
	     $numreg = pg_num_rows(pg_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
}	

// consulta empleado por su codigo
function consultarid($idposres)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "
SELECT posible_resultado,habilitado,(CASE WHEN habilitado='t' THEN 'Habilitado'
		WHEN habilitado='f' THEN 'Inhabilitado' END) AS estado
		 FROM lab_posible_resultado WHERE id='$idposres'";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
  
}//CLASE


?>
