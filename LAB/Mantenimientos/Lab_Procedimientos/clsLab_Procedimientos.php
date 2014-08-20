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
                    (nombreprocedimiento,idarea,idexamen,unidades,rangoinicio,rangofin,idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idestablecimiento,fechaini,fechafin,idsexo,idrangoedad) 
                    VALUES('$proce','$idarea','$idexamen','$unidades',$rangoini,$rangofin,$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
           //echo $query; 
            $result = @pg_query($query);
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
	    $query = "UPDATE lab_procedimientosporexamen SET nombreprocedimiento='$proce',idexamen='$idexamen', idarea='$idarea',
		 unidades='$unidades', rangoinicio=$rangoini , rangofin=$rangofin , idusuariomod=$usuario ,fechahoramod=NOW(),
		 fechaini='$Fechaini', fechafin='$Fechafin', idsexo=$sexo, idrangoedad=$redad 
		 WHERE lab_procedimientosporexamen.id=$idproce AND idestablecimiento=$lugar";
	
	     $result = pg_query($query);
             
             //  echo "SIAP   ".$query;
		 if (!$result)
	       return 0;
	     else
	       return 1;
	 }
      }

	//ELIMINA UN REGISTRO
 function eliminar($idproce,$lugar)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_procedimientosporexamen WHERE id=$idproce 
               AND idestablecimiento=$lugar";
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
           $query = "SELECT id,nombre,abreviatura FROM ctl_sexo";
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }
    }
    
    /* function consultarexamen()
   {
         $con = new ConexionBD;
         if($con->conectar()==true)
         {
           $query = "select id,nombreexamen from lab_examenes";
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }
    }*/

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
	 //CONSULTA LOS REGISTROS
 function consultar($lugar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
        $query = "SELECT lab_examenes.idexamen,nombreexamen,lab_procedimientosporexamen.id,
	nombreprocedimiento,unidades,rangoinicio,rangofin,
        (lab_procedimientosporexamen.fechaini,'%d/%m/%Y')AS fechaini,
	(lab_procedimientosporexamen.fechafin,'%d/%m/%Y')AS fechafin 
        FROM lab_procedimientosporexamen 
	INNER JOIN lab_examenes  ON lab_procedimientosporexamen.idexamen=lab_examenes.id
	INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen
        INNER JOIN lab_areasxestablecimiento ON lab_areasxestablecimiento.id=lab_procedimientosporexamen.idarea
        INNER JOIN ctl_sexo ON lab_procedimientosporexamen.idsexo = ctl_sexo.id
        INNER JOIN ctl_rango_edad ON lab_procedimientosporexamen.idrangoedad = ctl_rango_edad.id
	WHERE lab_examenesxestablecimiento.condicion='H' AND lab_areasxestablecimiento.condicion='H' 
        AND lab_procedimientosporexamen.idestablecimiento=$lugar 
	ORDER BY lab_examenes.id";
       // echo $query;
	$result = pg_query($query);
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
	     $query = "SELECT lab_procedimientosporexamen.id,lab_examenes.id,nombreexamen,lab_areas.id,nombrearea,
	     nombreprocedimiento ,unidades,rangoinicio,rangofin,
             (lab_procedimientosporexamen.fechaini)AS fechaini,
             (lab_procedimientosporexamen.fechafin)AS fechafin,ctl_sexo.id,ctl_sexo.abreviatura,
             ctl_rango_edad.id,ctl_rango_edad.nombre 
	     FROM lab_procedimientosporexamen 
	     INNER JOIN lab_examenes ON lab_procedimientosporexamen.idexamen=lab_examenes.id
	     INNER JOIN lab_areas ON lab_procedimientosporexamen.idarea=lab_areas.id
             INNER JOIN ctl_sexo ON lab_procedimientosporexamen.idsexo = ctl_sexo.id
             INNER JOIN ctl_rango_edad ON lab_procedimientosporexamen.idrangoedad = ctl_rango_edad.id	
	     WHERE lab_procedimientosporexamen.id=$idproce AND idestablecimiento=$lugar
	     ORDER BY lab_examenes.id";
		
	     $result = @pg_query($query);
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
	    /* $query = "SELECT lab_examenes.IdExamen,lab_examenes.NombreExamen 
                       FROM lab_examenes 
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
                       WHERE lab_examenes.IdArea='$idarea'
                       AND lab_examenesxestablecimiento.IdPlantilla='E' AND lab_examenesxestablecimiento.Condicion='H' 
                       AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
                       ORDER BY NombreExamen";
		 $result = @pg_query($query);*/
                

               //pregutar a que equivale "E" de lab_examenesxestablecimiento.idplantilla='E'
                 $query = "SELECT lab_examenes.id,lab_examenes.nombreexamen 
                       FROM lab_examenes 
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen
                       WHERE lab_examenes.idarea='$idarea'
                       AND lab_examenesxestablecimiento.idplantilla=5 AND lab_examenesxestablecimiento.condicion='H' 
                       AND lab_examenesxestablecimiento.idestablecimiento=$lugar
                       ORDER BY nombreexamen";
		 $result = @pg_query($query);
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
                       INNER JOIN lab_examenes ON lab_procedimientosporexamen.idexamen=lab_examenes.id
                       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id= lab_examenesxestablecimiento.idexamen 
                       INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id
                       INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
                       WHERE  lab_examenesxestablecimiento.condicion='H' AND lab_areasxestablecimiento.condicion='H' 
                       AND lab_examenesxestablecimiento.idplantilla=5 AND lab_areasxestablecimiento.idestablecimiento=$lugar";
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
		 $numreg = pg_num_rows(pg_query($query_search));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
	  }	  
	
	function consultarpag($lugar,$RegistrosAEmpezar,$RegistrosAMostrar)
	 {
	  	   
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	    /* $query = " SELECT IdProcedimientoporexamen,lab_examenes.IdExamen,lab_examenes.NombreExamen,
                        lab_procedimientosporexamen.nombreprocedimiento,
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
                        ORDER BY lab_examenes.IdExamen LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
            //echo $query;*/
               
         $query = " SELECT lab_procedimientosporexamen.id,lab_examenes.nombreexamen,
                        lab_procedimientosporexamen.nombreprocedimiento,
                        lab_procedimientosporexamen.unidades,lab_procedimientosporexamen.rangoinicio,
                        lab_procedimientosporexamen.rangofin,(lab_procedimientosporexamen.fechaini)AS fechaini,
                        (lab_procedimientosporexamen.fechafin)AS fechafin,ctl_sexo.abreviatura,ctl_rango_edad.nombre 
                        FROM lab_procedimientosporexamen  
                        INNER JOIN lab_examenes ON lab_procedimientosporexamen.idexamen=lab_examenes.id 
                        INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                        INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id
                        INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
                        INNER JOIN ctl_sexo ON lab_procedimientosporexamen.idsexo = ctl_sexo.id
                        INNER JOIN ctl_rango_edad ON lab_procedimientosporexamen.idrangoedad = ctl_rango_edad.id
                        WHERE lab_examenesxestablecimiento.condicion='H' AND lab_areasxestablecimiento.condicion='H' 
                        AND lab_examenesxestablecimiento.idplantilla=5
                        AND lab_procedimientosporexamen.idestablecimiento=$lugar
                        ORDER BY lab_examenes.id LIMIT $RegistrosAMostrar  OFFSET $RegistrosAEmpezar ";
            
           /* echo "--->".    $query = "SELECT lab_procedimientosporexamen.id,lab_examenes.nombreexamen,
                        lab_procedimientosporexamen.nombreprocedimiento,
                        lab_procedimientosporexamen.unidades,lab_procedimientosporexamen.rangoinicio,
                        lab_procedimientosporexamen.rangofin,(lab_procedimientosporexamen.fechaini)AS fechaini,
                        (lab_procedimientosporexamen.fechafin)AS fechafin,ctl_sexo.abreviatura,ctl_rango_edad.nombre 
                        FROM lab_procedimientosporexamen  
                        INNER JOIN lab_examenes ON lab_procedimientosporexamen.idexamen=lab_examenes.id 
                        INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                        INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id
                        INNER JOIN lab_areasxestablecimiento ON lab_areas.id=lab_areasxestablecimiento.idarea
                        INNER JOIN ctl_sexo ON lab_procedimientosporexamen.idsexo = ctl_sexo.id
                        INNER JOIN ctl_rango_edad ON lab_procedimientosporexamen.idrangoedad = ctl_rango_edad.id
                        WHERE   lab_procedimientosporexamen.idestablecimiento=$lugar ORDER BY id LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar  ";*/
		 $result = @pg_query($query);
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
	     $query = $query_search." LIMIT $RegistrosAMostrar  OFFSET $RegistrosAEmpezar ";
		 $result = @pg_query($query);
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
	/* function insertar_labo($proce,$idarea,$idexamen,$unidades,$rangoini,$rangofin,$usuario,$lugar,$Fechaini,$Fechafin,$sexo,$redad)
	 { 
	   $con2 = new ConexionBDLab;
	   if($con2->conectarT()==true) 
	   {
	    $query ="INSERT INTO laboratorio.lab_procedimientosporexamen
                (nombreprocedimiento,IdArea,IdExamen,unidades,rangoinicio,rangofin,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,idsexo,idedad) 
                VALUES('$proce','$idarea','$idexamen','$unidades',$rangoini,$rangofin,$usuario,NOW(),$usuario,NOW(),$lugar,'$Fechaini','$Fechafin',$sexo,$redad)";
		$result = @pg_query($query);
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
	     $query = "UPDATE laboratorio.lab_procedimientosporexamen SET nombreprocedimiento='$proce',IdExamen='$idexamen', IdArea='$idarea',
                       unidades='$unidades',rangoinicio=$rangoini,rangofin=$rangofin,IdUsuarioMod=$usuario ,FechaHoraMod=NOW(),
                       Fechaini='$Fechaini', FechaFin='$Fechafin',idsexo=$sexo,idedad=$redad 
                       WHERE idprocedimientoporexamen=$idproce AND IdEstablecimiento=$lugar";
	       echo $query;
	     $result = @pg_query($query);
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
             $result = @pg_query($query);

             if (!$result)
               return 0;
             else
               return 1;

           }
         }*/

	
}//CLASE la



?>
                                                                                                                                   