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
		     (idexamen,idarea,indicacion,IdServicio,idusuarioreg,fechahorareg,idusuarioMod,fechahoraMod) 
		      VALUES('$idexamen','$idarea','$indicacion','DCOLAB',$usuario,NOW(),$usuario,NOW())";
	     $result = pg_query($query);
		 
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
	     $query = "UPDATE mnt_indicacionesporexamen SET indicacion='$indicacion', idexamen='$idexamen', 
		       idarea='$idarea', idusuariomod= $usuario, fechahoramod=NOW() 
		       WHERE id=$idindicacion";
	     $result = pg_query($query);
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
	     $query = "DELETE FROM mnt_indicacionesporexamen WHERE id=$idindicacion";
	     $result = pg_query($query);
		 
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
	     $query = "SELECT idExamen,indicacion FROM mnt_indicacionesporexamen
		       WHERE idServicio='DCOLAB'
		       ORDER BY idExamen";
		 $result = pgl_query($query);
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
	     $query = "SELECT lab_examenes.idexamen,nombreexamen,indicacion,lab_areas.idarea,nombrearea 
                       FROM mnt_indicacionesporexamen 
                       INNER JOIN lab_areas ON mnt_indicacionesporexamen.idarea=lab_areas.idarea
                       INNER JOIN lab_examenes ON mnt_indicacionesporexamen.idexamen=lab_examenes.idexamen
                       WHERE id=$idindicacion
                       ORDER BY lab_examenes.idexamen";
	     $result = pg_query($query);
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
	     $query = "SELECT lab_examenes.id,nombreexamen
                       FROM lab_examenes
                      INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen 
                       WHERE lab_examenes.idarea =$idarea AND condicion = 'H'
                       ORDER BY nombreexamen";
           // echo $query;
		 $result = pg_query($query);
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
		 $numreg = pg_num_rows(pg_query($query));
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
		 $numreg = pg_num_rows(pg_query($query));
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
	     $query = "SELECT mnt_indicacionesporexamen.id,lab_areas.idarea,lab_examenes.idexamen,indicacion 
                       FROM mnt_indicacionesporexamen 
                       INNER JOIN lab_examenes ON mnt_indicacionesporexamen.idexamen=lab_examenes.id
                       INNER JOIN lab_areas ON mnt_indicacionesporexamen.idarea=lab_areas.id
                       LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
            // echo $query;
		 $result = pg_query($query);
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
	     $query = $query_search." 
                 LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 	  
//***************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***********************************/
 
}//CLASE
?>
