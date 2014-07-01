<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Procedimientos
{
     //CONSTRUCTOR
	 function clsLab_Procedimientos(){
	 }	

	//INSERTA UN REGISTRO          
	 function insertar($proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 { 
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	    $query ="INSERT INTO lab_procedimientosporexamen
                    (nombreprocedimiento,IdArea,IdExamen,unidades,rangoinicio,rangofin,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad) 
                    VALUES('$proce','$idarea','$idexamen','$unidades',$rangoini,$rangofin,$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
           //echo $query; 
            $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	 }
	 
	 //ACTUALIZA UN REGISTRO
	function actualizar($idproce,$proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	{
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	     $query = "UPDATE lab_procedimientosporexamen SET nombreprocedimiento='$proce',IdExamen='$idexamen', IdArea='$idarea',
		 unidades='$unidades', rangoinicio=$rangoini , rangofin=$rangofin , IdUsuarioMod=$usuario ,FechaHoraMod=NOW(),
		 Fechaini='$Fechaini', FechaFin='$Fechafin',idsexo=$sexo,idedad=$redad 
		 WHERE idprocedimientoporexamen=$idproce AND IdEstablecimiento=$lugar";
	echo $query;
	     $result = mysql_query($query);
		 if (!$result)
	       return false;
	     else
	       return true;
	   }
	 }

	//ELIMINA UN REGISTRO
 function eliminar($idproce,$lugar)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_procedimientosporexamen WHERE idprocedimientoporexamen=$idproce 
               AND Idestablecimiento=$lugar";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

   //OBTENER catalogo de sexo
   function consultarsexo()
   {
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "SELECT idsexo,sexovn FROM mnt_sexo";
           $result = mysql_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }
    }

       function RangosEdades(){
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "SELECT idedad, nombregrupoedad FROM mnt_rangoedad";
           $result = mysql_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }

        }
	 //CONSULTA LOS REGISTROS
 function consultar($lugar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
	$query = "SELECT lab_examenes.IdExamen,NombreExamen,lab_procedimientosporexamen.IdProcedimientoporexamen,
	nombreprocedimiento,unidades,rangoinicio,rangofin,
        DATE_FORMAT(lab_procedimientosporexamen.FechaIni,'%d/%m/%Y')AS FechaIni,
	DATE_FORMAT(lab_procedimientosporexamen.FechaFin,'%d/%m/%Y')AS FechaFin 
        FROM lab_procedimientosporexamen 
	INNER JOIN lab_examenes  ON lab_procedimientosporexamen.IdExamen=lab_examenes.IdExamen
	INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
        INNER JOIN lab_areasxestablecimiento ON lab_areasxestablecimiento.IdArea=lab_procedimientosporexamen.IdArea
        INNER JOIN mnt_sexo ON lab_procedimientosporexamen.idsexo = mnt_sexo.idsexo
        INNER JOIN mnt_rangoedad ON lab_procedimientosporexamen.idedad = mnt_rangoedad.idedad
	WHERE lab_examenesxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.Condicion='H' 
        AND lab_procedimientosporexamen.IdEstablecimiento=$lugar 
	ORDER BY lab_examenes.IdExamen";
	$result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
     }
 } 

	//CONSULTA EXAMEN POR EL CODIGO
	function consultarid($idproce,$lugar)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true)
	   {
	     $query = "SELECT idprocedimientoporexamen,lab_examenes.IdExamen,NombreExamen,lab_areas.IdArea,NombreArea,
	     nombreprocedimiento ,unidades,rangoinicio,rangofin,
             DATE_FORMAT(lab_procedimientosporexamen.FechaIni,'%d/%m/%Y')AS FechaIni,
             DATE_FORMAT(lab_procedimientosporexamen.FechaFin,'%d/%m/%Y')AS FechaFin,mnt_sexo.idsexo,mnt_sexo.sexovn,
             mnt_rangoedad.idedad,mnt_rangoedad.nombregrupoedad 
	     FROM lab_procedimientosporexamen 
	     INNER JOIN lab_examenes ON lab_procedimientosporexamen.IdExamen=lab_examenes.IdExamen
	     INNER JOIN lab_areas ON lab_procedimientosporexamen.IdArea=lab_areas.IdArea
             INNER JOIN mnt_sexo ON lab_procedimientosporexamen.idsexo = mnt_sexo.idsexo
             INNER JOIN mnt_rangoedad ON lab_procedimientosporexamen.idedad = mnt_rangoedad.idedad	
	     WHERE idprocedimientoporexamen=$idproce AND IdEstablecimiento=$lugar
	     ORDER BY lab_examenes.IdExamen";
		
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;
	    }
	  }
	
	 //RECUPERAR EXAMENES POR AREA
	 function ExamenesPorArea($idarea,$lugar)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	     $query = "SELECT lab_examenes.IdExamen,lab_examenes.NombreExamen FROM lab_examenes 
		INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
		WHERE lab_examenes.IdArea='$idarea'
		AND  lab_examenesxestablecimiento.IdPlantilla='E' AND lab_examenesxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
		ORDER BY NombreExamen";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
	 //consultando el numero de registros de la tabla
	function NumeroDeRegistros($lugar){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT * FROM lab_procedimientosporexamen 
                       INNER JOIN lab_examenes ON lab_procedimientosporexamen.IdExamen=lab_examenes.IdExamen
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen 
                       INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
                       INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
                       WHERE  lab_examenesxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.Condicion='H' 
                       AND lab_examenesxestablecimiento.Idplantilla='E' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar";
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
		 $numreg = mysql_num_rows(mysql_query($query_search));
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
	     $query = " SELECT IdProcedimientoporexamen,lab_examenes.IdExamen,lab_examenes.NombreExamen,lab_procedimientosporexamen.nombreprocedimiento,
	     lab_procedimientosporexamen.unidades,lab_procedimientosporexamen.rangoinicio,
	     lab_procedimientosporexamen.rangofin,DATE_FORMAT(lab_procedimientosporexamen.FechaIni,'%d/%m/%Y')AS FechaIni,
             DATE_FORMAT(lab_procedimientosporexamen.FechaFin,'%d/%m/%Y')AS FechaFin,mnt_sexo.sexovn,mnt_rangoedad.nombregrupoedad  
	     FROM lab_procedimientosporexamen 
	     INNER JOIN lab_examenes ON lab_procedimientosporexamen.IdExamen=lab_examenes.IdExamen 
             INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
	     INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
             INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
             INNER JOIN mnt_sexo ON lab_procedimientosporexamen.idsexo = mnt_sexo.idsexo
             INNER JOIN mnt_rangoedad ON lab_procedimientosporexamen.idedad = mnt_rangoedad.idedad
             WHERE lab_examenesxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.Condicion='H' 
             AND lab_examenesxestablecimiento.Idplantilla='E'
             AND lab_procedimientosporexamen.IdEstablecimiento=$lugar
             ORDER BY lab_examenes.IdExamen LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
            // echo $query;
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
}  
//************************************************  FIN FUNCIONES PARA MANEJO DE PAGINACION  ***************************************************/
 class clsLabor_Procedimientos
{ 
  //INSERTA UN REGISTRO          
	 function insertar_labo($proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 { 
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true) 
	   {
	    $query ="INSERT INTO laboratorio.lab_procedimientosporexamen
                (nombreprocedimiento,IdArea,IdExamen,unidades,rangoinicio,rangofin,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad) 
                VALUES('$proce','$idarea','$idexamen','$unidades',$rangoini,$rangofin,$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
		$result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	   
	  }
	 
	  //ACTUALIZA UN REGISTRO
	 function actualizar_labo($idproce,$proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 {
	    $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true)
	   {
	     $query = "UPDATE lab_procedimientosporexamen SET nombreprocedimiento='$proce',IdExamen='$idexamen', IdArea='$idarea',
                       unidades='$unidades',rangoinicio=$rangoini,rangofin=$rangofin,IdUsuarioMod=$usuario ,FechaHoraMod=NOW(),
                       Fechaini='$Fechaini', FechaFin='$Fechafin',idsexo=$sexo,idedad=$redad 
                       WHERE idprocedimientoporexamen=$idproce AND IdEstablecimiento=$lugar";
             
            echo $query;
	
	     $result = @mysql_query($query);
		 if (!$result)
	       return false;
	     else
	       return true;
	   }
	 }

	//ELIMINA UN REGISTRO
 function eliminar_labo($idproce,$lugar)
{
    $con2 = new ConexionBDLab;
	if($con2->conectarT()==true){
	   
     $query = "DELETE FROM laboratorio.lab_procedimientosporexamen 
               WHERE idprocedimientoporexamen=$idproce AND Idestablecimiento=$lugar";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

	
}//CLASE la



?>
                                                                                                                                   