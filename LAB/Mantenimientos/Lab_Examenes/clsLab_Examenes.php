<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_Examenes
{
    //constructor	
    function clsLab_Examenes(){
    }	

    //INSERTA UN REGISTRO          
    function IngExamenxEstablecimiento($idexamen,$nomexamen,$Hab,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$TiempoPrevio,$sexo,$idestandar,$lugar,$metodologias_sel){

        $con = new ConexionBD;
       if($con->conectar()==true) 
       {
        $query = "INSERT INTO lab_conf_examen_estab
                (condicion,idformulario,urgente,impresion,ubicacion,codigosumi,
                 idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idexamen,idestandarrep,idplantilla,nombre_examen,
                 idsexo,codigo_examen) 
                 VALUES
                 ('$Hab',$IdFormulario,$Urgente,'$letra',$ubicacion,NULL,$usuario,NOW(),$usuario,NOW(),$idestandar,
                   $IdEstandarResp,$plantilla,'$nomexamen',$sexo,'$idexamen') ";
       // echo $query;	
       $result = pg_query($query);
       
       

        $query2 ="select max(id) from lab_conf_examen_estab";
        $result2 = pg_query($query2);
        $row2=pg_fetch_array($result2);
        $ultimo=$row2[0];
        
        
        /*
        * crear examen - metodologias
        */
        $aMetodologias = explode(',',$metodologias_sel); 
        

        /*
         * actualizar o crear examen metodología
         */
        for ($i=0;$i<count($aMetodologias);$i++){
            $sql="INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin) VALUES ($ultimo, $aMetodologias[$i], true, NOW(), NULL)";
            pg_query($sql);           
        }
        if ($i==0){ // si no se han seleccionado metodologias
            $sql="INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin) VALUES ($ultimo, 0, true, NOW(), NULL)";
            pg_query($sql);
        }
        

        $sqlText = "INSERT INTO cit_programacion_exams (id_examen_establecimiento,rangotiempoprev,id_atencion,id_establecimiento,idusuarioreg,fechahorareg) 
                    VALUES ($ultimo,$TiempoPrevio,98,$lugar,$usuario,NOW())";

       // echo $query2." - ".$ultimo." - ".$sqlText;
       $dtSub = pg_query($sqlText) or die('La consulta fall&oacute;: ' . pg_error());
            // echo $sqlText;
        if (!$result && !$dtSub)
           return false;
         else
           return true;
        }
    }

    //ACTUALIZA UN REGISTRO
     function ActExamenxEstablecimiento($idconf,$nomexamen,$lugar,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$Hab,$TiempoPrevio,$idsexo,$idestandar,$ctlidestandar,$metodologias_sel)

     {
               $con = new ConexionBD;
               if($con->conectar()==true) 
               {
                      $query="UPDATE lab_conf_examen_estab 
                              SET idusuariomod=$usuario,fechahoramod=NOW(),idformulario=$IdFormulario,
                              idestandarrep=$IdEstandarResp,IdPlantilla=$plantilla,impresion='$letra 
                              urgente=$Urgente,ubicacion=$ubicacion,condicion='$Hab',nombre_examen='$nomexamen',idsexo=$idsexo
                              WHERE lab_conf_examen_estab.id=$idconf";
                    //echo $query;
                     $result = pg_query($query);
                     
                     
                     $aMetodologias = explode(',',$metodologias_sel);
                     
                    /*
                    * actualizar las metodoligas del examen
                    */
                                        
                    if ($aMetodologias[0]==""){ // cuando no hay metodologias seleccionadas
                        /*
                         * verificar si hay registro con NULL en metodologia
                         */
                        $sql="SELECT * FROM lab_examen_metodologia WHERE id_conf_exa_estab=$idconf and id_metodologia is NULL";
                        $con = pg_query($sql);
                        /*
                         * evaluar si hay metodologias guardadas
                         */
                        if (pg_num_rows($con)>0){
                            $sql="UPDATE lab_examen_metodologia SET activo=true,fecha_inicio=NOW(),fecha_fin=NULL WHERE id_conf_exa_estab=$idconf AND id_metodologia is null";
                            $upd = pg_query($sql);
                            $sql="UPDATE lab_examen_metodologia SET activo=false,fecha_fin=NOW() WHERE id_conf_exa_estab=$idconf AND id_metodologia is not null";
                            $upd = pg_query($sql);
                        } else { // si no existen registro con metodologia NULL 
                            $sql="INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin) VALUES ($idconf, null, true, NOW(), NULL)";
                            pg_query($sql);
                            /*
                             * Desactivar todas los examen metodologias menos el que solo tiene examen.
                             */
                            $sql="UPDATE lab_examen_metodologia SET activo=false WHERE id_conf_exa_estab=$idconf AND id_metodologia is not null";
                            $upd = pg_query($sql);
                        }
                    } else {
                        $sql="UPDATE lab_examen_metodologia SET activo=false,fecha_fin=NOW() WHERE id_conf_exa_estab=$idconf AND fecha_fin is null";
                        $upd = pg_query($sql);
                        
                        $sql="SELECT * FROM lab_examen_metodologia WHERE id_conf_exa_estab=$idconf and id_metodologia is not NULL";
                        $con = pg_query($sql);
                        
                        for ($i=0;$i<count($aMetodologias)-1;$i++){
                           $sql="UPDATE lab_examen_metodologia SET activo=true,fecha_inicio=NOW(),fecha_fin=NULL WHERE id_conf_exa_estab=$idconf AND id_metodologia=$aMetodologias[$i]";
                           $upd = pg_query($sql) or die('La consulta fall&oacute;: ' . pg_error());
                           if ($upd){
                               $sql="INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin) VALUES ($idconf, $aMetodologias[$i], true, NOW(), NULL)";
                               pg_query($sql);
                           }
                        }  
                    }
                    
                    
                        

                     $query_tiempo="SELECT * FROM cit_programacion_exams 
                                    WHERE id_examen_establecimiento=$idconf ";
                     $tot = pg_num_rows(pg_query($query_tiempo));
                    // $tot=$result_tiempo[0];
                     //echo $tot; 
                     if($tot > 0){
                         $sqlText ="UPDATE cit_programacion_exams
                                     SET rangotiempoprev=$TiempoPrevio
                                     WHERE id_examen_establecimiento=$idconf";
                         // $dtSub = pg_query($sqlText) or die('La consulta fall&oacute;: ' . pg_error());      
                     }else{    
                          $sqlText = "INSERT INTO cit_programacion_exams (id_examen_establecimiento,rangotiempoprev,id_atencion,id_establecimiento,idusuarioreg,fechahorareg) 
                                     VALUES ($idconf,$TiempoPrevio,98,$lugar,$usuario,NOW())"; 


                     }
                     //echo $sqlText;
                     $dtSub = pg_query($sqlText) or die('La consulta fall&oacute;: ' . pg_error());
                     if (!$result && !$dtSub)
                        return false;
                     else {
                                                  
                        return true;
                    }
               }
     }

/*function AgregarDatosFijos($idexamen,$idarea,$usuario,$lugar){
    $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_datosfijosresultado (IdArea,IdExamen,IdUsuarioReg,FechaHoraReg,IdEstablecimiento,FechaIni,FechaFin)
              VALUES ('$idarea','$idexamen',$usuario,NOW(),$lugar,CURDATE(),NULL)";
    // echo $query;
     $result = pg_query($query);
     

	 
     if (!$result)
       return false;
     else
       return true;
    }
    
}*/

 //ACTUALIZA UN REGISTRO
 /*function actualizar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,$sexo)
 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		 $query = "UPDATE lab_examenes SET nombreExamen='$nomexamen',idestandar='$idestandar',idarea='$idarea',Observacion='$observacion',
		 IdUsuarioMod='$usuario',FechaHoraMod=NOW(),IdSexo=$sexo WHERE idexamen='$idexamen'";
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
			//echo $query;
		   return true;
	  }
 }*/

 

 //CONSULTA LOS PROGRAMAS
  function consultar_programas(){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT * FROM mnt_programas ORDER BY IdPrograma";
			 $result = pg_query($query);
			if (!$result)
				return false;
			else
				return  $result;
		}
  }
   function consultar_codigospruebas(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT id,idestandar,descripcion FROM ctl_examen_servicio_diagnostico ORDER BY idEstandar ";
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  
 //CONSULTA el catalogo de sexo
  function catalogo_sexo(){
       $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT id,nombre FROM ctl_sexo where id <>3";
			 $result = pg_query($query);
			if (!$result)
                             return false;
			else
                             return  $result;
                        
                }
      
  }
  
  //CONSULTA LOS FORMULARIOS POR PROGRAMA
  function consultar_formularios($lugar){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT mnt_formulariosxestablecimiento.idformulario,nombreformulario 
				  FROM mnt_formularios 
				  INNER JOIN mnt_formulariosxestablecimiento    
				  ON mnt_formularios.id=mnt_formulariosxestablecimiento.idformulario
		                  WHERE idestablecimiento=$lugar";
                        //echo $query;
			 $result = pg_query($query);
			if (!$result)
				return false;
			else
				return  $result;
		}
  }
  
 //CONSULTA LOS REGISTROS
 function consultar($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
      $query = "SELECT lab_examenes.idexamen,lab_examenes.idestandar,lab_examenes.idarea,nombreexamen,descripcion,nombreArea,
                lab_examenesxestablecimiento.idplantilla,observacion,lab_examenes.habilitado,lab_examenesxestablecimiento.condicion,
                lab_examenes.ubicacion,urgente 
                FROM lab_examenes 
                INNER JOIN lab_areas  on lab_examenes.idarea=lab_areas.id
                INNER JOIN lab_codigosestandar ON lab_examenes.idestandar=lab_codigosestandar.id
                INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                WHERE lab_examenesxestablecimiento.condicion='H' AND lab_examenesxestablecimiento.idestablecimiento=$lugar
                ORDER BY lab_examenes.idarea,lab_examenes.idexamen";
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

  function Obtener_NombreEstandar($IdEstandar){
		$con = new ConexionBD;
	   if($con->conectar()==true)
	   {
		
		 $query = "SELECT Descripcion FROM lab_codigosestandar WHERE  IdEstandar='$IdEstandar'";
		 $result = pg_query($query);
		  if (!$result)
                    return false;
		  else
                    return $result;
	  }
  }
//CONSULTA EXAMEN POR EL CODIGO
 function consultarid($idexamen,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     //$query = "SELECT * FROM lab_examenes WHERE idexamen='$idexamen'";
	  $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen, 
                    lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,
                    lab_plantilla.id as idplantilla,ctl_examen_servicio_diagnostico.idestandar, 
                    (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias' 
                    WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia' 
                    WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion, lab_conf_examen_estab.ubicacion as idubicacion,
                    (SELECT id FROM ctl_examen_servicio_diagnostico 
                    WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS ctlidestandarrep, 
                    (SELECT idestandar
                    FROM ctl_examen_servicio_diagnostico 
                    WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep, 
                    (SELECT descripcion FROM ctl_examen_servicio_diagnostico 
                    WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS descestandarrep, 
                    lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion,
                    (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado' 
                    WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev, 
                    mnt_formularios.nombreformulario,mnt_formularios.id as idformulario,lab_plantilla.plantilla,
                    ctl_examen_servicio_diagnostico.descripcion,ctl_sexo.id as idsexo,ctl_sexo.nombre as sexo,mnt_area_examen_establecimiento.id as mntid,
                    id_area_servicio_diagnostico as idarea,
                    (SELECT ARRAY_AGG(m.id) metodologia
                        FROM lab_examen_metodologia em
                        LEFT JOIN lab_metodologia m ON m.id = em.id_metodologia
                        WHERE em.id_conf_exa_estab = $idexamen AND em.activo=true AND em.id_metodologia is not null
                        GROUP BY em.id_conf_exa_estab) as metodologias
                    FROM lab_conf_examen_estab 
                    INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                    INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                    INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id 
                    LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id 
                    INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id 
                    LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id 
                    INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                    LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento 
                    WHERE lab_areasxestablecimiento.condicion='H' AND lab_areasxestablecimiento.idestablecimiento=$lugar 
                    AND lab_conf_examen_estab.id=$idexamen";
          
        // echo $query;
     $result = pg_query($query);
     if (!$result)
       return false;
     else
	 //echo $query;
       return $result;
    }
  }

//OBTENER PLANTILLAS
 function LeerPlantilla()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id,plantilla from lab_plantilla";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
//FUNCION PARA LEER EL ULTIMO CODIGO INSERTADO
function LeerUltimoCodigo($idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "SELECT codigo_examen FROM lab_conf_examen_estab 
               INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
               INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico = ctl_area_servicio_diagnostico.id
               WHERE ctl_area_servicio_diagnostico.id=$idarea 
               ORDER BY lab_conf_examen_estab.codigo_examen DESC LIMIT 1";
   //  echo $query;
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
	   $row = pg_fetch_array($result);
       return $row[0];
	   
	   }
}

/********************************************FUNCIONES PARA MANEJO DE PAGINACION*******************************/
 //consultando el numero de registros de la tabla
   function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
       $query ="SELECT *  
                FROM lab_conf_examen_estab 
                INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id 
                LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id 
                INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id 
                LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id 
                INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
                WHERE lab_areasxestablecimiento.condicion='H' AND mnt_area_examen_establecimiento.id_establecimiento=$lugar 
                AND lab_areasxestablecimiento.idestablecimiento=$lugar
                ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen";
              
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
          
function ObtenerCodigo($idarea){
     //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
     if($con->conectar()==true){
        $query ="SELECT idarea FROM ctl_area_servicio_diagnostico WHERE id=$idarea";
       // echo $query;
        $result = pg_fetch_array(pg_query($query));
	if (!$result)
	   return false;
	 else
     return $result;
         
     }
}

 function BuscarExamen($idexamen,$lugar){
    $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
    if($con->conectar()==true){
        $query ="SELECT count( * ) FROM ctl_examen_servicio_diagnostico 
                 INNER JOIN mnt_area_examen_establecimiento 
                 ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico 
                 INNER JOIN lab_conf_examen_estab 
                 ON mnt_area_examen_establecimiento.id = lab_conf_examen_estab.idexamen
                 WHERE lab_conf_examen_estab.codigo_examen ='$idexamen'
                 AND mnt_area_examen_establecimiento.id_establecimiento =$lugar";
              
       // echo $query;
	$result = pg_query($query);
	if (!$result)
	   return false;
	 else
	   return $result;
      }
 } 
 function consultar_estandar(){
      $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
    if($con->conectar()==true){
        $query ="SELECT ctl_examen_servicio_diagnostico.id,idestandar,descripcion 
FROM ctl_examen_servicio_diagnostico 
INNER JOIN lab_estandarxgrupo ON lab_estandarxgrupo.id=ctl_examen_servicio_diagnostico.idgrupo
WHERE id_atencion=98 AND ctl_examen_servicio_diagnostico.activo=TRUE AND lab_estandarxgrupo.activo=TRUE
ORDER BY ctl_examen_servicio_diagnostico.id";
        $result = pg_query($query);
	if (!$result)
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
		   $query = "SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.codigo_examen as idexamen,
                             lab_conf_examen_estab.nombre_examen as nombreexamen, ctl_area_servicio_diagnostico.nombrearea,lab_plantilla.idplantilla,
                             ctl_examen_servicio_diagnostico.idestandar, 
                             (CASE WHEN lab_conf_examen_estab.ubicacion=0 THEN 'Todas las Procedencias' 
                             WHEN lab_conf_examen_estab.ubicacion=1 THEN 'Hospitalización y Emergencia' 
                             WHEN lab_conf_examen_estab.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion, 
                             (SELECT idestandar FROM ctl_examen_servicio_diagnostico 
                             WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS estandarrep, 
                             (SELECT descripcion FROM ctl_examen_servicio_diagnostico 
                             WHERE lab_conf_examen_estab.idestandarrep=ctl_examen_servicio_diagnostico.id) AS descestandarrep,
                             lab_conf_examen_estab.impresion,urgente, ctl_sexo.nombre AS nombresexo,lab_conf_examen_estab.condicion,
                             (CASE WHEN lab_conf_examen_estab.condicion='H' THEN 'Habilitado'
                             WHEN lab_conf_examen_estab.condicion='I' THEN 'Inhabilitado' END) AS cond,cit_programacion_exams.rangotiempoprev,
                             ctl_examen_servicio_diagnostico.descripcion,mnt_formularios.id as idformulario  
                             FROM lab_conf_examen_estab 
                             INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                             INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                             INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id 
                             LEFT JOIN mnt_formularios ON lab_conf_examen_estab.idformulario=mnt_formularios.id 
                             INNER JOIN lab_plantilla ON lab_conf_examen_estab.idplantilla=lab_plantilla.id 
                             LEFT JOIN ctl_sexo ON lab_conf_examen_estab.idsexo= ctl_sexo.id 
                             INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                             LEFT JOIN cit_programacion_exams ON lab_conf_examen_estab.id=cit_programacion_exams.id_examen_establecimiento
                             WHERE lab_areasxestablecimiento.condicion='H' AND mnt_area_examen_establecimiento.id_establecimiento=$lugar 
                             ORDER BY ctl_area_servicio_diagnostico.idarea,lab_conf_examen_estab.nombre_examen  
                             LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
                   //AND lab_areasxestablecimiento.condicion='H'
                    // echo $query; 
                            $result = pg_query($query);
                             
                            if (!$result)
                                return false;
                            else
                                return $result;
                     // echo $query;      
	   }
  } 
  
  function EstadoCuenta($idexamen,$cond,$lugar){ 
    $con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
            if($cond=='H'){
                $query = "UPDATE lab_conf_examen_estab SET condicion='I' WHERE id=$idexamen";
                $result = pg_query($query);
              //  $query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
               // $result1 = pg_query($query1);
             }
             if($cond=='I'){
                $query = "UPDATE lab_conf_examen_estab SET condicion='H' WHERE id=$idexamen";
                $result = pg_query($query);
               // $query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
                //$result1 = pg_query($query1);
             }
        }
        $con->desconectar();
    }
	
 function obtener_letra($idarea){
	$con = new ConexionBD;
            if($con->conectar()==true) 
                {
		$query = "SELECT ASCII(impresion)
                          FROM lab_conf_examen_estab
                          INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                          INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id  
                          WHERE ctl_area_servicio_diagnostico.id=$idarea AND impresion<>'G' 
                          ORDER BY Impresion DESC LIMIT 1";
		$result = pg_query($query);
		if (!$result)
			return false;
		else
			return  $result;
                }

 }

 function ExamenesPorArea($idarea,$lugar)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "SELECT mnt_area_examen_establecimiento.id,ctl_examen_servicio_diagnostico.descripcion,ctl_examen_servicio_diagnostico.idestandar
                        FROM  mnt_area_examen_establecimiento
                        INNER JOIN ctl_examen_servicio_diagnostico ON mnt_area_examen_establecimiento.id_examen_servicio_diagnostico=ctl_examen_servicio_diagnostico.id 
                        WHERE  mnt_area_examen_establecimiento.activo=TRUE AND mnt_area_examen_establecimiento.id_area_servicio_diagnostico=$idarea AND id_establecimiento=$lugar 
                        ORDER BY descripcion";
              
          //  echo $query;
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }

 
//*******************************************FIN FUNCIONES PARA MANEJO DE PAGINACION************************************************/
 
        
        function metodologias(){
            /*
            * Julio Castillo
            */
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "SELECT m.id as id_metodologia,
                                m.nombre_metodologia metodologias
                        FROM lab_metodologia m
                        WHERE m.activa is true
                        ORDER BY m.nombre_metodologia";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
        
          function examen_metodologia($id_examen){
            /*
            * Julio Castillo
            */
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "SELECT m.id as id_metodologia,
                                (CASE WHEN (em.id_metodologia IS NULL) then m.nombre_metodologia ELSE '' END) metodologias, 
                                (SELECT nombre_metodologia FROM lab_metodologia WHERE id=em.id_metodologia) metodologias_sel,
                                (SELECT CONCAT(idestandar,'-',descripcion) 
                                    FROM ctl_examen_servicio_diagnostico t01, lab_conf_examen_estab t02 
                                    WHERE t01.id=t02.idestandarrep AND t02.id=$id_examen) nombre_prueba,
                                '$id_examen' AS idexamen
                        FROM lab_metodologia m
                        LEFT JOIN (SELECT id_metodologia from lab_examen_metodologia 
                                    WHERE id_conf_exa_estab =$id_examen) em ON em.id_metodologia = m.id
                        WHERE m.activa IS TRUE
                        GROUP BY m.id, em.id_metodologia
                        ORDER BY m.nombre_metodologia";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
         
         function examen_metodologia_add($id_examen, $id_metodologia){
             /*
              * Julio Castillo
              */
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	       $query = "INSERT INTO lab_examen_metodologia(id_conf_exa_estab,id_metodologia,activo,fecha_inicio,fecha_fin) VALUES ($id_examen, $id_metodologia, true, NOW(), NULL)";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
         
         function examen_metodologia_del($id_examen, $id_metodologia){
             /*
              * Julio Castillo
              */
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "DELETE FROM lab_examen_metodologia WHERE id_conf_exa_estab = $id_examen AND id_metodologia = $id_metodologia";
             
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
}//CLASE



class clsLabor_Examenes
{
	//INSERTA UN REGISTRO          
	 function insertar_labo($idexamen,$idarea,$nomexamen,$idestandar,$plantilla,$observacion,$activo,$ubicacion,$usuario)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		$query = "INSERT INTO laboratorio.lab_examenes(idexamen,IdArea,nombreExamen,IdEstandar,Observacion,IdPlantilla,Habilitado,Ubicacion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$idexamen','$idarea','$nomexamen','$idestandar','$observacion','$plantilla','$activo',$ubicacion,$usuario,NOW(),$usuario,NOW())";
		 $result = pg_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;	   
	   }
	 }

	 //ACTUALIZA UN REGISTRO
	function actualizar_labo($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$plantilla,$ubicacion,$usuario)
	{
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		 $query = "UPDATE laboratorio.lab_examenes SET nombreExamen='$nomexamen' , idestandar='$idestandar', idarea='$idarea', IdPlantilla='$plantilla',Observacion='$observacion',Ubicacion=$ubicacion,IdUsuarioMod='$usuario', FechaHoraMod=NOW() WHERE idexamen='$idexamen'";
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return true;
	  }
	}
        
        }

?>
