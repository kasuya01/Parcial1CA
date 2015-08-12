<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Metodologia
{
 //constructor	
 function clsLab_Metodologia(){
 }	
 
 function insertar($metodologia)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_metodologia(nombre_metodologia,
				activa) 
			VALUES('$metodologia',
				true)";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

function actualizar($idmetodologia,$metodologia,$cmbEstado)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "UPDATE lab_metodologia SET nombre_metodologia='$metodologia',
            activa='$cmbEstado'
            WHERE id=$idmetodologia";
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


 
function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select id, 
                nombre_metodologia,
		(CASE WHEN activa='t' THEN 'Habilitado'
		WHEN activa='f' THEN 'Inhabilitado' END) AS habilitado
		from lab_metodologia ORDER BY nombre_metodologia LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar  ";
	 $result = pg_query($query);
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
     $query = "SELECT * FROM lab_antibioticos ";
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
function consultaridmetodologia($idmetodologia)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "
SELECT nombre_metodologia,activa,(CASE WHEN activa='t' THEN 'Habilitado'
		WHEN activa='f' THEN 'Inhabilitado' END) AS estado
		 FROM lab_metodologia WHERE id='$idmetodologia'";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
  
}//CLASE


?>
