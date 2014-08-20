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
     $result = pg_query($query);
	 
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
     $query = "UPDATE lab_elementos SET elemento='$nomelemento' , subelemento='$subelemento',unidadelem=$unidadele, 
		 observelem=$observacionele,idusuariomod='$usuario', fechahoramod=NOW(),fechaini=$Fechaini, 
		 fechafin=$Fechafin WHERE id=$idelemento AND idestablecimiento=$lugar";
     echo $query;
    $result = pg_query($query);
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
     $result = pg_query($query);
	 
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
     $query = "SELECT lab_areas.id,nombrearea,lab_examenesxestablecimiento.idexamen,nombreexamen,
                idelemento,elemento,subelemento,unidadelem,observelem,fechaini,fechafin 
		FROM lab_elementos 
		INNER JOIN lab_examenes  ON lab_elementos.idexamen=lab_examenes.id
		INNER JOIN lab_areas     ON lab_areas.id=lab_examenes.idarea
		INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
		INNER JOIN lab_examenesxestablecimiento ON lab_examenes.idexamen= lab_examenesxestablecimiento.idexamen
		WHERE lab_examenesxestablecimiento.condicion='H' 
		AND lab_areasxestablecimiento.condicion='H' AND lab_examenesxestablecimiento.idplantilla=2 
                AND lab_examenesxestablecimiento.idestablecimiento=$lugar
		ORDER BY lab_elementos.idexamen,lab_elementos.id";
    
	 $result = pg_query($query);
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
   {  $query = "SELECT lab_areas.idarea,nombrearea,lab_examenes.idexamen,nombreexamen,lab_elementos.id,elemento,
                unidadelem,observelem,subelemento, lab_elementos.idestablecimiento,
                to_char(fechaini,'dd/mm/YYYY')AS fechaini,
                to_char(fechafin,'dd/mm/YYYY')AS fechafin  
		FROM lab_elementos 
                INNER JOIN lab_examenes ON lab_elementos.idexamen=lab_examenes.id
                INNER JOIN lab_areas    ON lab_areas.id=lab_examenes.idarea
                INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
                INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen
                WHERE lab_elementos.idestablecimiento=$lugar AND lab_elementos.id=$idelemento";
                $result = pg_query($query);
                //echo $query;
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
     $query = "SELECT id,elemento,subelemento,unidadelem,observElem 
              FROM lab_elementos WHERE idexamen='$idexamen'";
     $result = pg_query($query);
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
     $query = "SELECT subelemento,unidad 
                FROM lab_subelementos 
                WHERE idelemento=$idelemento";
     $result = pg_query($query);
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
	     $query ="SELECT lab_examenes.id,lab_examenes.nombreexamen FROM lab_examenes 
		        INNER JOIN lab_examenesxestablecimiento ON    
                        lab_examenes.id=lab_examenesxestablecimiento.idexamen
			WHERE lab_examenes.idarea='$idarea'
			AND  lab_examenesxestablecimiento.idplantilla=2 AND lab_examenesxestablecimiento.condicion='H'
			AND lab_examenesxestablecimiento.idestablecimiento=$lugar
			ORDER BY lab_examenes.idarea";
                     
		 $result = pg_query($query);
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
	INNER JOIN lab_examenes ON lab_elementos.idexamen=lab_examenes.id
	INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen	
	INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id
	INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
	WHERE lab_areasxestablecimiento.condicion='H' AND lab_examenesxestablecimiento.condicion='H' 
	AND lab_examenesxestablecimiento.idplantilla=2 AND lab_elementos.idestablecimiento=$lugar
	ORDER BY lab_elementos.idexamen,lab_elementos.id";
     //echo $query;
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
	  
  function consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_areas.idarea,nombrearea,lab_examenes.idexamen,nombreexamen,
         lab_elementos.id,Elemento,
         (case when subelemento='S' 
          then 'SI' 
          else 'NO' end ) as subelemento,
         lab_elementos.idestablecimiento,unidadelem,observelem,
         to_char(fechaini,'dd/mm/YYYY') AS fechaini,
         to_char(fechafin,'dd/mm/YYYY') AS fechafin  
         FROM lab_elementos
	 INNER JOIN lab_examenes ON lab_elementos.idexamen=lab_examenes.id
	 INNER JOIN lab_areas ON lab_areas.id=lab_examenes.idarea
	 INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
	 INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen	
	 WHERE lab_areasxestablecimiento.condicion='H' AND lab_examenesxestablecimiento.condicion='H' 
	 AND lab_examenesxestablecimiento.idplantilla=2 AND lab_elementos.idestablecimiento=$lugar
	 ORDER BY lab_elementos.idexamen,lab_elementos.id
	 LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
      //  echo $query;
     
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
	     $query = $query_search." LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
		 $result = pg_query($query);
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
			$result = pg_query($query);
	 
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
			 $result = pg_query($query);
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
			 $result = pg_query($query);
			 
			 if (!$result)
			   return false;
			 else
			   return true;
			   
		   }
	}


}
?>
