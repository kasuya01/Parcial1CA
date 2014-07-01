<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsLab_Areas
{
 //constructor	
 function clsLab_Areas(){
 }	
 /*Función para ingresar un área*/
 function insertar($idarea,$nombrearea,$usuario,$tipo,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_areas(IdArea,NombreArea,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,Administrativa) 
			VALUES('$idarea','$nombrearea','$usuario',NOW(),'$usuario',NOW(),'$tipo')";
  // echo $query; 
    $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
   }
 }

/* Función para ingresar un área asociada a un establecimiento*/
function ingresarareaxestablecimiento($idarea,$cond,$lugar,$usuario)
{
$con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_areasxestablecimiento(Idarea,IdEstablecimiento,Condicion,IdUsuarioReg,IdUsuarioMod,FechaHoraReg,FechaHoraMod) VALUES('$idarea',$lugar,'$cond','$usuario','$usuario',NOW(),NOW())";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
   }
}

/*Función para actualizar datos de una área*/
function actualizar($idarea,$nom,$usuario,$tipo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    
     $query = "UPDATE lab_areas SET NombreArea='$nom',IdUsuarioMod='$usuario',FechaHoraMod=NOW(),Administrativa='$tipo' WHERE IdArea='$idarea'";
     //echo $query;
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
 }
 /*Función para actualizar datos de una área asociadad a un establecimiento*/
function ActualizarxEstablercimiento($idarea,$cond,$lugar,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    
     $query = "UPDATE lab_areasxestablecimiento SET Condicion='$cond',IdEstablecimiento='$lugar',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdArea='$idarea'";	
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
 }
 /*Función para eliminar un área asociada a un establecimiento*/
function EliminarxEstablecimiento($idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_areasxestablecimiento WHERE IdArea='$idarea'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }


/*Eliminar un área*/
function eliminar($idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_areas WHERE IdArea='$idarea'";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 // consulta las areas de la BD
 function consultar($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea, lab_areas.NombreArea,lab_areasxestablecimiento.Condicion FROM lab_areas 
INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar 
ORDER BY NombreArea";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
   // consulta las areas de la BD
 function consultaractivas($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
	       INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea= lab_areasxestablecimiento.IdArea
	       WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areas.Administrativa='N' 
               AND lab_areasxestablecimiento.IdEstablecimiento=$lugar 
	       ORDER BY NombreArea";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  
     // consulta las areas de la BD
 function consultarareas($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_areas 
INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea= lab_areasxestablecimiento.IdArea
WHERE lab_areasxestablecimiento.IdEstablecimiento=$lugar AND Condicion='H'
ORDER BY NombreArea";
	 $result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
//FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($idarea)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_examenes WHERE IdArea='$idarea'";
     $result = @mysql_query($query);
	 $cuenta = mysql_num_rows($result);
	 
     if ($cuenta > 0)
       return true;
     else
       return false;
    }
}
//************************************************FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 //consultando el numero de registros de la tabla
   function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea, lab_areas.NombreArea  FROM lab_areas
INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
WHERE lab_areasxestablecimiento.IdEstablecimiento=$lugar";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
		   
  function consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea,lab_areas.NombreArea,lab_areasxestablecimiento.Condicion,Administrativa
			   FROM lab_areas 
			   INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			   WHERE IdEstablecimiento=$lugar
			   ORDER BY lab_areas.IdArea LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//******************************FIN FUNCIONES PARA MANEJO DE PAGINACION****************************/
  // consulta empleado por su codigo
 function consultarid($idarea,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT lab_areas.IdArea,Nombrearea,Condicion,Administrativa,IdEstablecimiento 
			   FROM lab_areas 
			   INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea= lab_areasxestablecimiento.IdArea
			   WHERE lab_areas.IdArea='$idarea'AND lab_areasxestablecimiento.IdEstablecimiento=$lugar";
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
		 $result = mysql_query($query);
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
