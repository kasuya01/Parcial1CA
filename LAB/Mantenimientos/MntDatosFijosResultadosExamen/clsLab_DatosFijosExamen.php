<?php 
include_once("../../../Conexion/ConexionBD.php");
//include_once("../../../Conexion/ConexionBDLab.php");

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
				(idarea,idexamen,unidades,rangoinicio,rangofin,nota,idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idestablecimiento,fechaini,fechafin,idsexo,idedad) 
				VALUES($idarea,$idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,NOW(),$usuario,NOW(),$lugar,$Fechaini,$Fechafin,$sexo,$redad)";
		echo $query;
	     $result = pg_query($query);
	
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
               if ($sexo==0)
               {   $sexo1="NULL";}
               else
                    {   $sexo1=$sexo;}
	   {
	      if(empty($Fechafin)){
				$query = "UPDATE lab_datosfijosresultado SET idexamen=$idexamen, idarea=$idarea,
				unidades=$unidades, rangoinicio=$rangoinicio , rangofin=$rangofin , nota=$nota,idsexo=$sexo1,idedad=$redad, 
                                idusuariomod= $usuario,fechahoramod=NOW(),fechaini=$Fechaini 
                                WHERE id=$iddatosfijosresultado AND idestablecimiento=$lugar";}
		 else{	
				$query = "UPDATE lab_datosfijosresultado SET idexamen=$idexamen, idarea=$idarea,
				unidades=$unidades, rangoinicio=$rangoinicio , rangofin=$rangofin , nota=$nota,idsexo=$sexo1,idedad=$redad,
                                idusuariomod= $usuario, fechahoramod=NOW() ,FechaIni=$Fechaini,fechafin=$Fechafin 
                                WHERE id=$iddatosfijosresultado AND idestablecimiento=$lugar";}
                           //     echo $query;
                                
		 
            $result = pg_query($query);
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
	     $query = "DELETE FROM lab_datosfijosresultado WHERE id=$iddatosfijosresultado AND IdEstablecimiento=$lugar";
	     $result = pg_query($query);
		 
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
           $query = "SELECT id,nombre FROM ctl_sexo where id<>3";
           $result = pg_query($query);
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
           $query = "SELECT id, nombre FROM ctl_rango_edad";
           $result = pg_query($query);
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
		 $result = pg_query($query);
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
	     $query = "SELECT lab_examenes.id as idexamen,lab_examenes.nombreexamen, lab_areas.id as idarea, nombrearea, unidades, rangoinicio, 
                       rangofin, nota,lab_datosfijosresultado.idestablecimiento, 
                        to_char( lab_datosfijosresultado.fechaini, 'dd/mm/YYYY' ) AS FechaIni,
                        to_char( lab_datosfijosresultado.fechafin, 'dd/mm/YYYY' ) AS FechaFin, 
                        ctl_sexo.id as idsexo, ctl_sexo.nombre as sexo,ctl_rango_edad.id as idedad,ctl_rango_edad.nombre as redad,lab_datosfijosresultado.id 
                        FROM lab_datosfijosresultado INNER JOIN lab_examenes ON lab_datosfijosresultado.idexamen = lab_examenes.id 
                        INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen 
                        INNER JOIN lab_areas ON lab_datosfijosresultado.idarea = lab_areas.id 
                        INNER JOIN lab_areasxestablecimiento ON lab_datosfijosresultado.idarea=lab_areasxestablecimiento.idarea 
                        LEFT JOIN ctl_sexo ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                        INNER JOIN ctl_rango_edad ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
		        WHERE lab_datosfijosresultado.id=$iddatosfijosresultado
		        AND lab_datosfijosresultado.idestablecimiento=$lugar
		        ORDER BY lab_examenes.id";
             //echo $query;
	     $result = pg_query($query);
             
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
	      $query = "SELECT lab_examenes.id,lab_examenes.nombreexamen FROM lab_examenes 
		        INNER JOIN lab_examenesxestablecimiento ON    
                        lab_examenes.id=lab_examenesxestablecimiento.idexamen
			WHERE lab_examenes.idarea='$idarea'
			AND  lab_examenesxestablecimiento.idplantilla=1 AND lab_examenesxestablecimiento.condicion='H'
			AND lab_examenesxestablecimiento.idestablecimiento=$lugar
			ORDER BY lab_examenes.idarea ";
              //echo $query;
		 $result = pg_query($query);
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
                       INNER JOIN lab_examenes ON lab_datosfijosresultado.idexamen=lab_examenes.id
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen
                       INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id
                       INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
                       WHERE lab_examenesxestablecimiento.idplantilla=1 
                       AND lab_examenesxestablecimiento.condicion='H' AND lab_areasxestablecimiento.condicion='H' 
		       AND lab_datosfijosresultado.idestablecimiento=$lugar
		       ORDER BY lab_examenes.idarea";
            //  echo $query;
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
                $query = "SELECT lab_datosfijosresultado.id,lab_examenes.id as idexamen,lab_examenes.nombreexamen, 
                          lab_datosfijosresultado.unidades,lab_datosfijosresultado.rangoinicio,rangofin,
                          lab_datosfijosresultado.nota,
                          to_char(lab_datosfijosresultado.fechaini,'YYYY/mm/dd') AS FechaIni,
                          to_char(lab_datosfijosresultado.fechafin,'YYYY-mm-dd') AS FechaFin, 
                          ctl_sexo.nombre as sexo,ctl_rango_edad.nombre as redad
                          FROM lab_datosfijosresultado 
                          INNER join lab_examenes ON lab_datosfijosresultado.idexamen=lab_examenes.id 
                          INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id 
                          INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea 
                          INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen 
                          LEFT JOIN ctl_sexo ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                          INNER JOIN ctl_rango_edad ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                          WHERE lab_examenesxestablecimiento.idplantilla=1 AND lab_examenesxestablecimiento.condicion='H' 
                          AND lab_areasxestablecimiento.condicion='H' AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                          ORDER BY lab_examenes.idarea
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
	     $query = $query_search." LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
		 $result = pg_query($query);
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
