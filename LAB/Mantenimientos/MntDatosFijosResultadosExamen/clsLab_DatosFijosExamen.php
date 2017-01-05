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
		      (id_conf_examen_estab,unidades,rangoinicio,rangofin,nota,idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idestablecimiento,fechaini,fechafin,idsexo,idedad) 
                      VALUES($idexamen,$unidades,$rangoinicio,$rangofin,$nota,$usuario,date_trunc('seconds',NOW()),$usuario,date_trunc('seconds', NOW()),$lugar,$Fechaini,$Fechafin,$sexo,$redad)";
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
              
	   {
	      
		$query = "UPDATE lab_datosfijosresultado SET id_conf_examen_estab=$idexamen, 
	    		  unidades=$unidades, rangoinicio=$rangoinicio , rangofin=$rangofin , nota=$nota,idsexo=$sexo,idedad=$redad,
                          idusuariomod= $usuario, fechahoramod=date_trunc('seconds', NOW()) ,FechaIni=$Fechaini,fechafin=$Fechafin 
                          WHERE id=$iddatosfijosresultado AND idestablecimiento=$lugar";
                             echo $query;
                                
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
           $query = "SELECT id, nombre FROM ctl_rango_edad WHERE cod_modulo='LAB'";
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
	     $query = "SELECT lab_conf_examen_estab.idexamen,lab_conf_examen_estab.nombre_examen,lab_datosfijosresultado.unidades,
		       lab_datosfijosresultado.rangoinicio,lab_datosfijosresultado.rangofin,nota
	     	       FROM lab_datosfijosresultado 
                       INNER JOIN lab_conf_examen_estab ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                       INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                       INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                       INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
   	               wHERE lab_areasxestablecimiento.Condicion='H' AND lab_conf_examen_estab.condicion='H' AND 
                       lab_datosfijosresultado.IdEstablecimiento=$lugar ORDER BY lab_conf_examen_estab.nombre_examen ";
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
	     $query = "SELECT lab_conf_examen_estab.id as idexamen,lab_conf_examen_estab.nombre_examen, 
                        mnt_area_examen_establecimiento.id_area_servicio_diagnostico as idarea, nombrearea, lab_datosfijosresultado.unidades, rangoinicio, 
                        rangofin,lab_datosfijosresultado.nota,lab_datosfijosresultado.idestablecimiento, 
                        to_char( lab_datosfijosresultado.fechaini, 'dd/mm/YYYY' ) AS FechaIni,
                        to_char( lab_datosfijosresultado.fechafin, 'dd/mm/YYYY' ) AS FechaFin, 
                        ctl_sexo.id as idsexo, ctl_sexo.nombre as sexo,ctl_rango_edad.id as idedad,ctl_rango_edad.nombre as redad,lab_datosfijosresultado.id,
                        ctl_examen_servicio_diagnostico.idestandar
                        FROM lab_datosfijosresultado
                        INNER JOIN lab_conf_examen_estab ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                        INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                        INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                        INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                        INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                        LEFT JOIN ctl_sexo ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                        INNER JOIN ctl_rango_edad ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                        WHERE lab_datosfijosresultado.id=$iddatosfijosresultado
		        AND lab_datosfijosresultado.idestablecimiento=$lugar
		        ORDER BY lab_conf_examen_estab.nombre_examen";
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
	       $query = " SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.nombre_examen,ctl_examen_servicio_diagnostico.idestandar 
                         FROM lab_conf_examen_estab
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                         INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                         WHERE mnt_area_examen_establecimiento.id_area_servicio_diagnostico=$idarea
                         AND mnt_area_examen_establecimiento.activo=TRUE
                         AND ctl_examen_servicio_diagnostico.activo=TRUE    
                         AND lab_conf_examen_estab.idplantilla=1 AND lab_conf_examen_estab.condicion='H'
                         AND mnt_area_examen_establecimiento.id_establecimiento=$lugar
                         ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico";
              
           //  echo $query;
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
                       INNER JOIN lab_conf_examen_estab ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                       INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                       INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                       INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_examen_servicio_diagnostico 
                       INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                       LEFT JOIN ctl_sexo ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                       INNER JOIN ctl_rango_edad ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                       WHERE lab_conf_examen_estab.idplantilla=1 
                       AND lab_conf_examen_estab.condicion='H'
                       AND lab_areasxestablecimiento.condicion='H'
                       AND mnt_area_examen_establecimiento.activo= TRUE
		       AND ctl_examen_servicio_diagnostico.activo= TRUE
                       AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                       ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,lab_conf_examen_estab.nombre_examen";
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
		 $numreg = @pg_num_rows(pg_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	  }	
          
          
           function consultarhabilitado($iddatosfijosexamen)
       {
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "select CASE lab_datosfijosresultado.fechafin 
                     WHEN lab_datosfijosresultado.fechafin THEN 'Inhabilitado'
                     ELSE 'Habilitado' END AS habilitado from  lab_datosfijosresultado   WHERE id=$iddatosfijosexamen";
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }
        }

          
         //fn_pg          
        function Estadohabilitado($idatofijo,$usuario) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			 echo $query = "UPDATE lab_datosfijosresultado SET 
					fechafin=current_date,
					idusuarioreg=$usuario,
					fechahoramod=NOW()
					 WHERE id=$idatofijo ";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
          
         //fn_pg          
        function buscardatosfijo($idsexo,$idedad, $idconf) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
                  $query = "select * 
                     from lab_datosfijosresultado 
                     where id_conf_examen_estab=$idconf
                     and (fechafin is null or date(fechafin)>(current_date))
                     and (idsexo is null or idsexo=$idsexo)
                     and (idedad=4 or idedad=$idedad)";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
          
          
	
	function consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar)
	{
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
            if($con->conectar()==true){
              $query = "SELECT    lab_datosfijosresultado.id,
                                    lab_conf_examen_estab.codigo_examen,
                                    lab_conf_examen_estab.nombre_examen,
                                    lab_datosfijosresultado.unidades,
                                    lab_datosfijosresultado.rangoinicio,
                                    rangofin, 
                                    lab_datosfijosresultado.nota, 
                                    to_char(lab_datosfijosresultado.fechaini,'dd/mm/YYYY') AS FechaIni, 
                                    to_char(lab_datosfijosresultado.fechafin,'dd/mm/YYYY') AS FechaFin,
                                    ctl_sexo.nombre as sexo,
                                    ctl_rango_edad.nombre as redad,
                                    CASE lab_datosfijosresultado.fechafin 
                                    WHEN lab_datosfijosresultado.fechafin THEN 'Inhabilitado'
                                    ELSE 'Habilitado' END AS habilitado,
                                    lab_datosfijosresultado.id as idatofijo,
                                    ctl_examen_servicio_diagnostico.idestandar
                          FROM lab_datosfijosresultado
                          INNER JOIN lab_conf_examen_estab              ON lab_datosfijosresultado.id_conf_examen_estab=lab_conf_examen_estab.id 
                          INNER JOIN mnt_area_examen_establecimiento    ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                          INNER JOIN ctl_area_servicio_diagnostico      ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
                          INNER JOIN lab_areasxestablecimiento          ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
                          LEFT JOIN ctl_sexo                            ON lab_datosfijosresultado.idsexo = ctl_sexo.id 
                          INNER JOIN ctl_rango_edad                     ON lab_datosfijosresultado.idedad = ctl_rango_edad.id 
                          INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                          WHERE lab_conf_examen_estab.idplantilla=1 
                          AND lab_conf_examen_estab.condicion='H'
                          AND lab_areasxestablecimiento.condicion='H'
                          AND mnt_area_examen_establecimiento.activo= TRUE
                          AND ctl_examen_servicio_diagnostico.activo= TRUE
                          AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                          ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico,
                          lab_conf_examen_estab.nombre_examen
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
		 $result = @pg_query($query);
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
