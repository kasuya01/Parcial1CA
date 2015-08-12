<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_CodigosxExamen
{
     //CONSTRUCTOR
	 function clsLab_CodigosxExamen(){
	 }	

	//INSERTA UN REGISTRO          
	 function insertar($IdEstandar,$IdResultado)
	 { //echo $idarea;
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	    $query = "INSERT INTO lab_codigosxexamen(IdEstandar, IdResultado) 
                      VALUES ('$IdEstandar',$IdResultado)";
		echo $query;
	     $result = @mysql_query($query);
	
	     if (!$result)
	       return false;
	     else
	       return true;	   
	   }
	 }
         
	 //ACTUALIZA UN REGISTRO
	 function actualizar($IdCodigosxExamen,$IdEstandar,$IdResultado)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	    	$query = "UPDATE lab_codigosxexamen 
                          SET IdEstandar='$IdEstandar', IdResultado='$IdResultado'
		          WHERE IdCodigosxExamen=$IdCodigosxExamen";
		 	
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
	function eliminar($IdCodigosxExamen)
	{
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	     $query = "DELETE FROM lab_codigosxexamen WHERE IdCodigosxExamen=$IdCodigosxExamen";
	     $result = @mysql_query($query);
		 
	     if (!$result)
	       return false;
	     else
	       return true;		   
	   }
	 }

                
      
        
        function consultar_CodigosEstandar($param) {

            $con = new ConexionBD;
            //usamos el metodo conectar para realizar la conexion
            if($con->conectar()==true){
                $query = "SELECT * FROM lab_codigosestandar ORDER BY idEstandar ";
                $result = @mysql_query($query);
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
	     $query = "SELECT lab_codigosxexamen.IdEstandar, lab_codigosestandar.Descripcion, 
                       lab_codigosxexamen.IdResultado, Resultado
                       FROM lab_codigosxexamen
                       INNER JOIN lab_codigosestandar ON lab_codigosestandar.IdEstandar = lab_codigosxexamen.IdEstandar
                       INNER JOIN lab_codigosresultados ON lab_codigosresultados.IdResultado = lab_codigosxexamen.IdResultado
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
	
	
//*************************************************FUNCIONES PARA MANEJO DE PAGINACION******************************************************/
	 //consultando el numero de registros de la tabla
	function NumeroDeRegistros($lugar){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = "SELECT lab_codigosxexamen.IdEstandar, lab_codigosestandar.Descripcion, lab_codigosxexamen.IdResultado, Resultado
                          FROM lab_codigosxexamen
                          INNER JOIN lab_codigosestandar ON lab_codigosestandar.IdEstandar = lab_codigosxexamen.IdEstandar
                          INNER JOIN lab_codigosresultados ON lab_codigosresultados.IdResultado = lab_codigosxexamen.IdResultado";
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
                $query = "SELECT lab_codigosxexamen.IdEstandar, lab_codigosestandar.Descripcion, lab_codigosxexamen.IdResultado,Resultado
                          FROM lab_codigosxexamen
                          INNER JOIN lab_codigosestandar ON lab_codigosestandar.IdEstandar = lab_codigosxexamen.IdEstandar
                          INNER JOIN lab_codigosresultados ON lab_codigosresultados.IdResultado = lab_codigosxexamen.IdResultado
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
        
        function EstandaresxGrupo($idgrupo){
            //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
           if($con->conectar()==true){
               
               $query="SELECT * FROM `lab_codigosestandar`WHERE idgrupo= '$idgrupo'";
               
               $result = @mysql_query($query);
	       if (!$result)
		   return false;
		else
		   return $result;
               
           }
            
        }
        
        /* function LeerResultados(){
            //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
           if($con->conectar()==true){
               
               $query="SELECT IdResultado, Resultado
                       FROM lab_codigosresultados";
               
               $result = @mysql_query($query);
	       if (!$result)
		   return false;
		else
		   return $result;
               
           }
            
        }*/
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
