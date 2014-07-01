<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_DatosFijosExamen
{
     //CONSTRUCTOR
	 function clsLab_DatosFijosExamen(){
	 }	

	//INSERTA UN REGISTRO          
	 function insertar($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 { //echo $idarea;
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	    $query = "INSERT INTO lab_datosfijosresultado
				(IdArea,IdExamen,Unidades,RangoInicio,RangoFin,Nota,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad) 
				VALUES('$idarea','$idexamen','$unidades',$rangoinicio,$rangofin,'$nota',$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
		//echo $query;
	     $result = @mysql_query($query);
	
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	 }
	 //ACTUALIZA UN REGISTRO
	 function actualizar($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	      if(empty($Fechafin)){
				$query = "UPDATE lab_datosfijosresultado SET IdExamen='$idexamen', IdArea='$idarea',
				Unidades='$unidades', RangoInicio=$rangoinicio , RangoFin=$rangofin , Nota='$nota',idsexo=$sexo,idedad=$redad, 
                                    IdUsuarioMod= $usuario, FechaHoraMod=NOW() ,FechaIni='$Fechaini' 
		        WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";}
		 else{	
				$query = "UPDATE lab_datosfijosresultado SET IdExamen='$idexamen', IdArea='$idarea',
				Unidades='$unidades', RangoInicio=$rangoinicio , RangoFin=$rangofin , Nota='$nota',idsexo=$sexo,idedad=$redad,
                                    IdUsuarioMod= $usuario, FechaHoraMod=NOW() ,FechaIni='$Fechaini', FechaFin='$Fechafin' 
		        WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";}
		 
	     $result = @mysql_query($query);
		 if (!$result)
	       return false;
				//  return $query; 
	     else
			return true;
			// return $query; 
	   }
	 }

	//ELIMINA UN REGISTRO
	 function eliminar($iddatosfijosresultado,$lugar)
	{
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	     $query = "DELETE FROM lab_datosfijosresultado WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";
	     $result = @mysql_query($query);
		 
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
        //   }
	 //CONSULTA LOS REGISTROS
	 function consultar($lugar)
	 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT lab_examenes.IdExamen,lab_examenes.NombreExamen,lab_datosfijosresultado.Unidades,
		       lab_datosfijosresultado.RangoInicio,lab_datosfijosresultado.RangoFin,Nota
	     	       FROM lab_datosfijosresultado 
	     	       INNER JOIN lab_examenes  ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
                       INNER JOIN lab_areasxestablecimiento ON lab_datosfijosresultado.IdArea=lab_areasxestablecimiento.IdArea
 	     	       WHERE lab_areasxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.Condicion='H' AND 
                       lab_datosfijosresultado.IdEstablecimiento=$lugar ORDER BY lab_examenes.IdExamen ";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 

	//CONSULTA EXAMEN POR EL CODIGO
	function consultarid($iddatosfijosresultado,$lugar)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true)
	   {
	     $query = "SELECT lab_examenes.IdExamen, lab_examenes.NombreExamen, lab_areas.IdArea, NombreArea, 
                       Unidades, RangoInicio, RangoFin, Nota,lab_datosfijosresultado.Idestablecimiento, 
                       DATE_FORMAT( lab_datosfijosresultado.FechaIni, '%d/%m/%Y' ) AS FechaIni, 
                       DATE_FORMAT( lab_datosfijosresultado.FechaFin, '%d/%m/%Y' ) AS FechaFin, 
                       mnt_sexo.idsexo, mnt_sexo.sexovn,mnt_rangoedad.idedad,mnt_rangoedad.nombregrupoedad
                       FROM lab_datosfijosresultado
                       INNER JOIN lab_examenes ON lab_datosfijosresultado.IdExamen = lab_examenes.IdExamen
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
                       INNER JOIN lab_areas ON lab_datosfijosresultado.IdArea = lab_areas.IdArea
                       INNER JOIN lab_areasxestablecimiento ON lab_datosfijosresultado.IdArea=lab_areasxestablecimiento.IdArea
                       INNER JOIN mnt_sexo ON lab_datosfijosresultado.`idsexo` = mnt_sexo.idsexo
                       INNER JOIN mnt_rangoedad ON lab_datosfijosresultado.idedad = mnt_rangoedad.idedad
		       WHERE lab_datosfijosresultado.IdDatosFijosResultado=$iddatosfijosresultado
		       AND lab_datosfijosresultado.IdEstablecimiento=$lugar
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
		        INNER JOIN lab_examenesxestablecimiento ON    
                        lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
			WHERE IdArea='$idarea'
			AND  lab_examenesxestablecimiento.IdPlantilla='A' AND lab_examenesxestablecimiento.Condicion='H'
			AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
			ORDER BY lab_examenes.NombreExamen ";
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
	     $query = "SELECT * FROM lab_datosfijosresultado 
                       INNER JOIN lab_examenes On lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
                       INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
                       INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
                       WHERE lab_examenesxestablecimiento.IdPlantilla='A' 
                       AND lab_examenesxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.Condicion='H' 
		       AND lab_datosfijosresultado.IdEstablecimiento=$lugar
		       ORDER BY lab_examenes.IdExamen";
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
                $query = "SELECT iddatosfijosresultado,lab_examenes.IdExamen,lab_examenes.NombreExamen,
                    lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio,RangoFin,
                    lab_datosfijosresultado.Nota,DATE_FORMAT(lab_datosfijosresultado.FechaIni,'%d/%m/%Y')
                    AS FechaIni,DATE_FORMAT(lab_datosfijosresultado.FechaFin,'%d/%m/%Y') AS FechaFin,
                    mnt_sexo.sexovn,mnt_rangoedad.nombregrupoedad 
                    FROM lab_datosfijosresultado 
                    INNER join lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
                    INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
                    INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
                    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                    INNER JOIN mnt_sexo ON lab_datosfijosresultado.idsexo = mnt_sexo.idsexo
                    INNER JOIN mnt_rangoedad ON lab_datosfijosresultado.idedad = mnt_rangoedad.idedad
                    WHERE lab_examenesxestablecimiento.IdPlantilla='A' AND lab_examenesxestablecimiento.Condicion='H' 
                    AND lab_areasxestablecimiento.Condicion='H' AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                    ORDER BY lab_examenes.IdArea
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
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 
}//CLASE

class clsLabor_DatosFijosExamen
{

//INSERTA UN REGISTRO         
	/*function insertar_labo($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 { $con2 = new ConexionBDLab;
		if($con2->conectarT()==true) 
		{
			$query = "INSERT INTO laboratorio.lab_datosfijosresultado
			(IdArea,IdExamen,Unidades,RangoInicio,RangoFin,Nota,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad) 
			VALUES('$idarea','$idexamen','$unidades',$rangoinicio,$rangofin,'$nota',$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
		
	     $result = mysql_query($query);
	
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	}
	
	 
	function actualizar_labo($iddatosfijosresultado,$idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	{
	    $con2 = new ConexionBDLab;
	    if($con2->conectarT()==true) 
	    {
	        if(empty($Fechafin)){
			$query = "UPDATE laboratorio.lab_datosfijosresultado SET IdExamen='$idexamen', IdArea='$idarea',
                                  Unidades='$unidades', RangoInicio=$rangoinicio , RangoFin=$rangofin , Nota='$nota', 
                                  idsexo=$sexo,idedad=$redad,IdUsuarioMod= $usuario, 
                                  FechaHoraMod=NOW(),FechaIni='$Fechaini' 
                                  WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";}
		    else{	
			$query = "UPDATE laboratorio.lab_datosfijosresultado SET IdExamen='$idexamen', IdArea='$idarea',
                                  Unidades='$unidades', RangoInicio=$rangoinicio , RangoFin=$rangofin , Nota='$nota', 
                                  idsexo=$sexo,idedad=$redad,IdUsuarioMod= $usuario, 
                                  FechaHoraMod=NOW() ,FechaIni='$Fechaini', FechaFin='$Fechafin' 
                                  WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";}
		 
	     $result = @mysql_query($query);
		 if (!$result)
	       return false;
				//  return $query; 
	     else
			return true;
			// return $query; 
	   }
	}


	//ELIMINA UN REGISTRO
	 function eliminar_labo($iddatosfijosresultado,$lugar)
	{
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true) 
	   {
	    $query = "DELETE FROM laboratorio.lab_datosfijosresultado WHERE IdDatosFijosResultado=$iddatosfijosresultado AND IdEstablecimiento=$lugar";
	     $result = @mysql_query($query);
		 
	     if (!$result)
	       return false;
	     else
	       return true;		   
	   }
	 }*/
	 
	
	

}
?>
