<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsElementosExamen
{
 //constructor	
 function clsElementosExamen(){
 }	

//INSERTA UN REGISTRO          
 function insertar($idexamen,$nomelemento,$subelemento,$usuario,$observacionele,$unidadele,$lugar,$Fechaini,$Fechafin,$orden)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_elementos(id_conf_examen_estab,elemento,subelemento,UnidadElem,ObservElem,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimiento,FechaIni,FechaFin,orden) 
    VALUES($idexamen,'$nomelemento','$subelemento',$unidadele,$observacionele,$usuario,NOW(),$usuario,NOW(),$lugar,$Fechaini,$Fechafin,$orden)";
     $result = pg_query($query);
 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 //ACTUALIZA UN REGISTRO
 function actualizar($idelemento,$nomelemento,$subelemento,$unidadele,$observacionele,$usuario,$lugar,$Fechaini,$Fechafin,$orden)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
         $query = "UPDATE lab_elementos SET elemento='$nomelemento' , subelemento='$subelemento',unidadelem=$unidadele, 
		 observelem=$observacionele,idusuariomod='$usuario', fechahoramod=NOW(),fechaini=$Fechaini, 
		 fechafin=$Fechafin, orden=$orden WHERE id=$idelemento AND idestablecimiento=$lugar";
     //echo $query;
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
     $query = "DELETE FROM lab_elementos WHERE id=$idelemento AND IdEstablecimiento=$lugar";
    // echo $query;
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
   {  
//       $query = "SELECT mnt_area_examen_establecimiento.id_area_servicio_diagnostico as idarea,nombrearea, lab_conf_examen_estab.codigo_examen as codexamen,
//                lab_conf_examen_estab.nombre_examen, lab_elementos.id,elemento, unidadelem,observelem,subelemento, lab_elementos.idestablecimiento, 
//                to_char(fechaini,'dd/mm/YYYY')AS fechaini, to_char(fechafin,'dd/mm/YYYY')AS fechafin,ctl_examen_servicio_diagnostico.id as cod,
//                ctl_examen_servicio_diagnostico.idestandar,lab_elementos.orden,lab_conf_examen_estab.id as idexamen  
//                FROM lab_elementos 
//                INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
//                INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
//                INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
//                INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
//                INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
//                WHERE lab_elementos.idestablecimiento=$lugar AND lab_elementos.id=$idelemento";
//                $result = pg_query($query);
       $query = "SELECT mnt_area_examen_establecimiento.id_area_servicio_diagnostico as idarea,nombrearea, lab_conf_examen_estab.codigo_examen as codexamen,
                lab_conf_examen_estab.nombre_examen, lab_elementos.id,elemento, unidadelem,observelem,subelemento, lab_elementos.idestablecimiento, 
                fechaini AS fechaini, fechafin AS fechafin,ctl_examen_servicio_diagnostico.id as cod,
                ctl_examen_servicio_diagnostico.idestandar,lab_elementos.orden,lab_conf_examen_estab.id as idexamen  
                FROM lab_elementos 
                INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
                INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
                INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id 
                INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea 
                INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
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
                $query ="SELECT lab_conf_examen_estab.id,lab_conf_examen_estab.nombre_examen 
                         FROM lab_conf_examen_estab
                         INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
                         INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                         WHERE mnt_area_examen_establecimiento.id_area_servicio_diagnostico=$idarea
                         AND mnt_area_examen_establecimiento.activo=TRUE
                         AND ctl_examen_servicio_diagnostico.activo=TRUE    
                         AND lab_conf_examen_estab.idplantilla=2 AND lab_conf_examen_estab.condicion='H'
                         AND mnt_area_examen_establecimiento.id_establecimiento=$lugar
                         ORDER BY mnt_area_examen_establecimiento.id_area_servicio_diagnostico";
                   
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
               INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
               INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
               INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
               INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
               WHERE lab_areasxestablecimiento.condicion='H' AND lab_conf_examen_estab.condicion='H' 
               AND lab_conf_examen_estab.idplantilla=2 AND lab_elementos.idestablecimiento=$lugar
	       ORDER BY lab_elementos.id_conf_examen_estab";
    // echo $query;
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
     $query = "SELECT mnt_area_examen_establecimiento.id_area_servicio_diagnostico as idarea,nombrearea,lab_conf_examen_estab.codigo_examen as idexamen,lab_conf_examen_estab.nombre_examen, 
               lab_elementos.id as idelemento,Elemento, 
               (case when subelemento='S' then 'SI' else 'NO' end ) as subelemento,lab_elementos.idestablecimiento,unidadelem,
               observelem, to_char(fechaini,'dd/mm/YYYY') AS fechaini, to_char(fechafin,'dd/mm/YYYY') AS fechafin,lab_elementos.orden 
               FROM lab_elementos 
               INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
               INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id
               INNER JOIN ctl_area_servicio_diagnostico ON mnt_area_examen_establecimiento.id_area_servicio_diagnostico=ctl_area_servicio_diagnostico.id
               INNER JOIN lab_areasxestablecimiento ON ctl_area_servicio_diagnostico.id=lab_areasxestablecimiento.idarea
               WHERE lab_areasxestablecimiento.condicion='H' AND lab_conf_examen_estab.condicion='H' 
               AND lab_conf_examen_estab.idplantilla=2 AND lab_elementos.idestablecimiento=$lugar
               ORDER BY lab_elementos.id_conf_examen_estab,lab_elementos.orden
               LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
       //echo $query;
     
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
 function existeordenele($idexamen){
         $con = new ConexionBD;
		if ( $con->conectar()==true ) {
			ECHO $query = "SELECT max(orden) FROM lab_elementos where id_conf_examen_estab=$idexamen";
			$result = pg_query( $query );
                                      
                    while ($row=pg_fetch_array($result))
                    {
                       $hola=$row[0];
                    }
                                    
                    return $hola;          
                    
                }              
 }
 
 
 
 function llenarrangoele($idexamen) {
	$con = new ConexionBD;
	//usamos el metodo conectar para realizar la conexion
	if ( $con->conectar()==true ) {
          $query ="SELECT orden  
                    FROM lab_elementos
                    WHERE id_conf_examen_estab=$idexamen
                    ORDER BY orden asc";

	$result = @pg_query( $query );
	if ( !$result )
            return false;
	else
            return $result;
	}
 }
 
        function Rangosele($idelemento) {
            $con = new ConexionBD;
            if ( $con->conectar()==true ) {
            	$query = "SELECT orden FROM lab_elementos where id=$idelemento";
		$result = pg_query( $query );
		if ( !$result )
                    return false;
		else
                    return $result;
		}

	}
 
}//CLASE
/*
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


}*/
?>
