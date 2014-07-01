<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_IndicacionesPorExamen
{
     //CONSTRUCTOR
	 function clsLab_IndicacionesPorExamen(){
	 }	

	//INSERTA UN REGISTRO          
	 function insertar($idexamen,$idarea,$indicacion,$usuario)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	    $query = "INSERT INTO mnt_indicacionesporexamen
		     (IdExamen,IdArea,Indicacion,IdServicio,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
		      VALUES('$idexamen','$idarea','$indicacion','DCOLAB',$usuario,NOW(),$usuario,NOW())";
	     $result = @mysql_query($query);
		 
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	 }
	 //ACTUALIZA UN REGISTRO
	 function actualizar($idexamen,$idarea,$indicacion,$idindicacion,$usuario)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	     $query = "UPDATE mnt_indicacionesporexamen SET Indicacion='$indicacion', IdExamen='$idexamen', 
		       IdArea='$idarea', IdUsuarioMod= $usuario, FechaHoraMod=NOW() 
		       WHERE IdIndicacionPorExamen=$idindicacion";
	     $result = @mysql_query($query);
		 if (!$result)
	       return false;
	     else
	       return true;
	   }
	 }

	//ELIMINA UN REGISTRO
	 function eliminar($idindicacion)
	{
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	     $query = "DELETE FROM mnt_indicacionesporexamen WHERE IdIndicacionPorExamen=$idindicacion";
	     $result = @mysql_query($query);
		 
	     if (!$result)
	       return false;
	     else
	       return true;		   
	   }
	 }

	 //CONSULTA LOS REGISTROS
	 function consultar()
	 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT IdExamen,Indicacion FROM mnt_indicacionesporexamen
		       WHERE IdServicio='DCOLAB'
		       ORDER BY IdExamen";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 

	//CONSULTA EXAMEN POR EL CODIGO
	function consultarid_indicacion($idindicacion)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true)
	   {
	     $query = "SELECT lab_examenes.IdExamen,NombreExamen,Indicacion,lab_areas.IdArea,NombreArea 
                       FROM mnt_indicacionesporexamen 
                       INNER JOIN lab_areas ON mnt_indicacionesporexamen.IdArea=lab_areas.IdArea
                       INNER JOIN lab_examenes ON mnt_indicacionesporexamen.IdExamen=lab_examenes.IdExamen
                       WHERE IdIndicacionPorExamen=$idindicacion
                       ORDER BY lab_examenes.IdExamen";
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;
	    }
	  }
	
	 //RECUPERAR EXAMENES POR AREA
	 function ExamenesPorArea($idarea)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	     $query = "SELECT lab_examenes.IdExamen, NombreExamen
                       FROM lab_examenes
                       INNER JOIN lab_examenesxestablecimiento 
                       ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                       WHERE lab_examenes.IdArea ='$idarea' AND Condicion = 'H'
                       ORDER BY IdExamen";
		 $result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
	 
//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
	 //consultando el numero de registros de la tabla
	function NumeroDeRegistros(){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT * FROM mnt_indicacionesporexamen";
		 $numreg = mysql_num_rows(mysql_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	  }

	function NumeroDeRegistrosbus($query_search){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query_search;
		 $numreg = mysql_num_rows(mysql_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	  }	  
	
	function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar)
	 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT IdIndicacionPorExamen,IdArea,IdExamen,Indicacion FROM mnt_indicacionesporexamen LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  }  
   
   function consultarpagbus($query_search,$RegistrosAEmpezar, $RegistrosAMostrar)
	 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query_search." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 	  
//***************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***********************************/
 
}//CLASE
?>
