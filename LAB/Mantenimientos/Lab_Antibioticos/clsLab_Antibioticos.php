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
       
        $query = "INSERT INTO lab_antibioticos(antibiotico,idusuarioreg,fechahorareg) VALUES('$antibiotico','$usuario',date_trunc('seconds',NOW())) returning id";
        $result =  pg_fetch_array(pg_query($query));
        //$result = pg_fetch_array($result);
        $idantibiotico= $result[0];

        if ($idantibiotico==0)
            return false;
        else
         /* Ingresando posibles resultados para el nuevo antibiotico ingresado 10-Resistente, 11-Intermedio y 12-Sensible */
            $query1 = " INSERT INTO lab_antibiotico_posible_resultado (id, id_antibiotico, id_posible_resultado, fechainicio, fechafin, habilitado, id_user, fecha_registro) 
                     VALUES ((select max(id)+1 from lab_antibiotico_posible_resultado), $idantibiotico, 10,CURRENT_DATE, NULL, true, '$usuario', date_trunc('seconds',NOW()))";
            $query2 = " INSERT INTO lab_antibiotico_posible_resultado (id, id_antibiotico, id_posible_resultado, fechainicio, fechafin, habilitado, id_user, fecha_registro) 
                     VALUES ((select max(id)+1 from lab_antibiotico_posible_resultado), $idantibiotico, 11,CURRENT_DATE, NULL, true, '$usuario', date_trunc('seconds',NOW()))";
            $query3 = " INSERT INTO lab_antibiotico_posible_resultado (id, id_antibiotico, id_posible_resultado, fechainicio, fechafin, habilitado, id_user, fecha_registro) 
                     VALUES ((select max(id)+1 from lab_antibiotico_posible_resultado), $idantibiotico, 12,CURRENT_DATE, NULL, true, '$usuario', date_trunc('seconds',NOW()))";
         
          $result1 = pg_query($query1);
          $result2 = pg_query($query2);
          $result3 = pg_query($query3);
        if (($result1) && ($result2) && ($result3))
            return true;     
        else {
            return false;
        }    	   
    }
 }

function actualizar($idantibiotico,$antibiotico,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_antibioticos SET antibiotico='$antibiotico',idusuariomod ='$usuario',fechahoramod=date_trunc('seconds',NOW()) WHERE id='$idantibiotico'";
     $result = pg_query($query);
	 
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
     $query = "DELETE FROM lab_antibioticos WHERE id='$idantibiotico'";
     $result = pg_query($query);
	 
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
     $query = "SELECT * FROM lab_antibioticosportarjeta WHERE idantibiotico='$idantibiotico'";
     $result = pg_query($query);
	 $cuenta = pg_num_rows($result);
	 
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
     $query = "SELECT * FROM lab_antibioticos ORDER BY antibiotico LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar  ";
	 $result = pg_query($query);
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
     $query = "SELECT * FROM lab_antibioticos ORDER BY antibiotico";
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
function consultarid($idantibiotico)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_antibioticos WHERE id='$idantibiotico'";
     $result = pg_query($query);
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
		 $result = pg_query($query);
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
		 $result = pg_query($query);
		 
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
		 $result = pg_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;
		   
	   }
	}*/
}
?>
