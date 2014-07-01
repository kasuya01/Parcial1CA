<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsElementosExamen
{
 //constructor	
 function clsElementosExamen(){
 }	

//INSERTA UN REGISTRO          
 function insertar($idexamen,$nomelemento,$subelemento,$usuario,$observacionele,$unidadele,$lugar,$Fechaini,$Fechafin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_elementos(idexamen,elemento,subelemento,UnidadElem,ObservElem,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin) 
    VALUES('$idexamen','$nomelemento','$subelemento','$unidadele','$observacionele',$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin')";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 //ACTUALIZA UN REGISTRO
 function actualizar($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_elementos SET Elemento='$nomelemento' , subelemento='$subelemento',UnidadElem='$unidadele', 
		 ObservElem='$observacionele',IdUsuarioMod='$usuario', FechaHoraMod=NOW(),Fechaini='$Fechaini', 
		 FechaFin='$Fechafin' WHERE idelemento=$idelemento AND IdEstablecimiento=$lugar";
     $result = @mysql_query($query);
	 if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 //ELIMINA UN REGISTRO
 function eliminar($idelemento,$lugar)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_elementos WHERE idelemento='$idelemento' AND IdEstablecimiento=$lugar";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 
 //CONSULTA LOS REGISTROS
function consultar($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea,NombreArea,lab_examenesxestablecimiento.IdExamen,NombreExamen,
                IdElemento,Elemento,SubElemento,UnidadElem,ObservElem,FechaIni,FechaFin 
		FROM lab_elementos 
		INNER JOIN lab_examenes  ON lab_elementos.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_areas     ON lab_areas.IdArea=lab_examenes.IdArea
		INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
		INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
		WHERE lab_examenesxestablecimiento.Condicion='H' 
		AND lab_areasxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdPlantilla='B' 
                AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
		ORDER BY IdExamen,IdElemento";
    
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

//CONSULTA EXAMEN POR EL CODIGO
 function consultarid($idelemento,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {  $query = "SELECT lab_areas.IdArea,NombreArea,lab_examenes.IdExamen,NombreExamen,IdElemento,Elemento,
                UnidadElem,ObservElem,SubElemento, lab_elementos.IdEstablecimiento,
                DATE_FORMAT(FechaIni,'%d/%m/%Y')AS FechaIni,
		DATE_FORMAT(FechaFin,'%d/%m/%Y')AS FechaFin 
                FROM lab_elementos 
                INNER JOIN lab_examenes ON lab_elementos.IdExamen=lab_examenes.IdExamen
                INNER JOIN lab_areas    ON lab_areas.IdArea=lab_examenes.IdArea
                INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
                INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
                WHERE lab_elementos.IdEstablecimiento=$lugar AND IdElemento=$idelemento";
                $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
 
//FUNCION PARA PBTENER ELEMENTOS DE UN EXAMEN PLANTILLA B
 function LeerElementosExamen($idexamen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "select IdElemento,Elemento,SubElemento,UnidadElem,ObservElem from lab_elementos where IdExamen='$idexamen'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

 //FUNCION PARA PBTENER SUB-ELEMENTOS DE UN EXAMEN PLANTILLA B
 function LeerSubElementosExamen($idelemento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "select SubElemento,Unidad from lab_subelementos where idelemento=$idelemento";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  

 //FUNCION PARA BUSCAR EXAMENES POR AREA
 function ExamenesPorArea($idarea,$lugar)
 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true)
		{
	     $query = "SELECT lab_examenes.IdExamen,lab_examenes.NombreExamen 
				   FROM lab_examenes INNER JOIN lab_examenesxestablecimiento 
				   ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
				   WHERE IdArea='$idarea' AND lab_examenesxestablecimiento.IdPlantilla='B' 
				   AND lab_examenesxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
				   ORDER BY lab_examenes.NombreExamen";
				   
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
 }
 
 
 
/*****************************FUNCIONES PARA MANEJO DE PAGINACION********************************/
 //consultando el numero de registros de la tabla
 function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_elementos
	INNER JOIN lab_examenes ON lab_elementos.IdExamen=lab_examenes.IdExamen
	INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen	
	INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
	INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
	WHERE lab_areasxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.Condicion='H' 
	AND lab_examenesxestablecimiento.IdPlantilla='B' AND lab_elementos.IdEstablecimiento=$lugar
	ORDER BY lab_elementos.IdExamen,IdElemento";
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
	  
  function consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.IdArea,NombreArea,lab_examenes.IdExamen,NombreExamen,IdElemento,
	 Elemento,IF(SubElemento='S','SI','NO')AS SubElemento,lab_elementos.IdEstablecimiento,UnidadElem,ObservElem,
         DATE_FORMAT(FechaIni,'%d/%m/%Y')AS FechaIni,
         DATE_FORMAT(FechaFin,'%d/%m/%Y')AS FechaFin  
         FROM lab_elementos
	 INNER JOIN lab_examenes ON lab_elementos.IdExamen=lab_examenes.IdExamen
	 INNER JOIN lab_areas ON lab_areas.IdArea=lab_examenes.IdArea
	 INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
	 INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen	
	 WHERE lab_areasxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.Condicion='H' 
	 AND lab_examenesxestablecimiento.IdPlantilla='B' and lab_elementos.IdEstablecimiento=$lugar
	 ORDER BY IdExamen,IdElemento
	 LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
   
     
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
//**********************************FIN FUNCIONES PARA MANEJO DE PAGINACION*****************************************/
 
}//CLASE

class clsLabor_ElementosExamen
{
	//INSERTA UN REGISTRO          
	 function insertar_labo($idexamen,$nomelemento,$subelemento,$usuario,$observacionele,$unidadele,$lugar,$Fechaini,$Fechafin)
	 {
	    $con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
			$query = "INSERT INTO laboratorio.lab_elementos(idexamen,elemento,subelemento,UnidadElem,ObservElem,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin)VALUES('$idexamen','$nomelemento','$subelemento','$unidadele','$observacionele',$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin')";
			$result = mysql_query($query);
	 
		if (!$result)
			return false;
		else
			return true;	   
		   }
		 }
		 
		 //ACTUALIZA UN REGISTRO
		 function actualizar_labo($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin)
		 {						
								
		  $con2 = new ConexionBDLab;
			if($con2->conectarT()==true){
		   
			 $query = "UPDATE laboratorio.lab_elementos SET Elemento='$nomelemento' , subelemento='$subelemento',UnidadElem='$unidadele', 
				 ObservElem='$observacionele',IdUsuarioMod='$usuario', FechaHoraMod=NOW(),Fechaini='$Fechaini', 
				 FechaFin='$Fechafin' WHERE idelemento=$idelemento AND IdEstablecimiento=$lugar";
			 $result = mysql_query($query);
			 if (!$result)
			   return false;
			 else
			   return true;
			   
		   }
		 }

	 //ELIMINA UN REGISTRO
	function eliminar_labo($idelemento,$lugar)
	{
		 $con2 = new ConexionBDLab;
		 if($con2->conectarT()==true){
			
			 $query = "DELETE FROM laboratorio.lab_elementos WHERE idelemento='$idelemento' AND IdEstablecimiento=$lugar";
			 $result = mysql_query($query);
			 
			 if (!$result)
			   return false;
			 else
			   return true;
			   
		   }
	}


}
?>
