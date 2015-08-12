<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsSubElementosExamen
{
 //constructor	
 function clsSubElementosExamen(){
 }	

//INSERTA UN REGISTRO          
function insertar($idelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad,$orden)
         
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query = "INSERT INTO lab_subelementos(idelemento,unidad,subelemento,rangoinicio,rangofin,fechaini,fechafin,idestablecimiento,idsexo,idedad,orden) 
	      VALUES($idelemento,$unidad,'$subelemento',$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad,$orden)";
  //echo $query;
    $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
        return true;	   
        
   }
 }
 

 //ACTUALIZA UN REGISTRO
 function actualizar($idsubelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad,$orden)
 {					
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_subelementos SET subelemento='$subelemento',unidad=$unidad,rangoinicio=$rangoini,
               rangofin=$rangofin,fechaini=$Fechaini,fechafin=$Fechafin,idestablecimiento=$lugar,
               idsexo=$sexo,idedad=$redad, orden=$orden WHERE id=$idsubelemento";
 // echo $query; 
     $result = pg_query($query);
	 if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 //ELIMINA UN REGISTRO
 function eliminar($idsubelemento)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_subelementos WHERE id=$idsubelemento";
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
           $query = "SELECT id, nombre FROM ctl_rango_edad where cod_modulo='LAB' ORDER BY nombre";
           
           $result = pg_query($query);
           if (!$result)
             return false;
           else
             return $result;
          }

        }
        
    
        
  //CONSULTA LOS REGISTROS
 function consultar($idelemento){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
      $query = "SELECT IdSubElemento,b.SubElemento,Unidad,a.IdElemento,Elemento
		FROM lab_elementos a 
		INNER JOIN lab_subelementos b on a.IdElemento=b.IdElemento
		WHERE a.IdElemento=$idelemento";
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

//CONSULTA EXAMEN POR EL CODIGO
 function consultarid($idsubelemento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   { 
//       $query = "SELECT lab_subelementos.id,lab_subelementos.subelemento,unidad,lab_elementos.id,elemento,
//               lab_subelementos.idestablecimiento,rangoinicio,rangofin, 
//               to_char(lab_subelementos.fechaini,'dd/mm/YYYY')AS FechaIni, 
//               to_char(lab_subelementos.fechafin,'dd/mm/YYYY')AS FechaFin, 
//               ctl_sexo.id as idsexo,ctl_sexo.nombre as nombresexo,ctl_rango_edad.id as idedad,
//               ctl_rango_edad.nombre as nombreedad,lab_subelementos.orden,lab_conf_examen_estab.nombre_examen 
//               FROM lab_elementos 
//               INNER JOIN lab_subelementos ON lab_elementos.id=lab_subelementos.idelemento 
//               LEFT JOIN ctl_sexo ON lab_subelementos.idsexo = ctl_sexo.id 
//               INNER JOIN ctl_rango_edad ON lab_subelementos.idedad = ctl_rango_edad.id
//               INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
//	       WHERE lab_subelementos.id=$idsubelemento";
       $query = "SELECT lab_subelementos.id,lab_subelementos.subelemento,unidad,lab_elementos.id,elemento,
               lab_subelementos.idestablecimiento,rangoinicio,rangofin, 
               lab_subelementos.fechaini AS FechaIni, 
               lab_subelementos.fechafin AS FechaFin, 
               ctl_sexo.id as idsexo,ctl_sexo.nombre as nombresexo,ctl_rango_edad.id as idedad,
               ctl_rango_edad.nombre as nombreedad,lab_subelementos.orden,lab_conf_examen_estab.nombre_examen 
               FROM lab_elementos 
               INNER JOIN lab_subelementos ON lab_elementos.id=lab_subelementos.idelemento 
               LEFT JOIN ctl_sexo ON lab_subelementos.idsexo = ctl_sexo.id 
               INNER JOIN ctl_rango_edad ON lab_subelementos.idedad = ctl_rango_edad.id
               INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id 
	       WHERE lab_subelementos.id=$idsubelemento";
       // echo $query;
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
 
 /* function RangosSubEle($idelemento) {
		$con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "SELECT orden FROM lab_subelementos where id=$idelemento";
			$result = pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}

	}*/
        
  function existeOrden($idelemento,$idedad,$idsexo){
         $con = new ConexionBD;
		if ( $con->conectar()==true ) {
			$query = "SELECT max(orden)+1 FROM lab_subelementos where idsexo=$idsexo AND idedad=$idedad AND idelemento=$idelemento";
			$result = pg_query( $query );
                                      
                    while ($row=pg_fetch_array($result))
                    {
                                       
                        $hola=$row[0];
                    }
                                    
                    return $hola;          
                    
                }              
    }      
    
    //FUNCION PARA PBTENER ELEMENTOS DE UN EXAMEN PLANTILLA B
 function LeerElementosExamen($idexamen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT IdElemento,Elemento,SubElemento FROM lab_elementos WHERE IdExamen='$idexamen'";
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
     $query = "SELECT SubElemento,Unidad FROM lab_subelementos WHERE idelemento=$idelemento";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
  
/***********************************************FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 //consultando el numero de registros de la tabla
   function NumeroDeRegistros($idelemento){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM lab_subelementos WHERE IdElemento=$idelemento";
	 $num = pg_query($query) or die(mysql_error());
	 $numreg = pg_num_rows($num);
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
 function consultarpag($idelemento,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
     //creamos el objeto $con a partir de la clase ConexionBD
        $con = new ConexionBD;
     //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT lab_subelementos.id,lab_subelementos.subelemento,lab_subelementos.unidad,
               lab_elementos.id as idelemento,elemento,rangoinicio,rangofin, 
               to_char(lab_subelementos.fechaini,'dd/mm/YYYY')AS FechaIni, 
               to_char(lab_subelementos.fechafin,'dd/mm/YYYY')AS FechaFin ,
               ctl_sexo.id as idsexo,ctl_sexo.nombre As nombresexo, ctl_rango_edad.id as idedad,
               ctl_rango_edad.nombre AS nombreedad,
               ARRAY_AGG(lab_subelemento_posible_resultado.id) AS catalogo,lab_subelementos.orden
               FROM lab_elementos 
               INNER JOIN lab_subelementos ON lab_elementos.id=lab_subelementos.idelemento 
               LEFT JOIN ctl_sexo ON lab_subelementos.idsexo = ctl_sexo.id 
               INNER JOIN ctl_rango_edad ON lab_subelementos.idedad = ctl_rango_edad.id
               LEFT JOIN lab_subelemento_posible_resultado ON lab_subelemento_posible_resultado.id_subelemento = lab_subelementos.id 
               AND lab_subelemento_posible_resultado.habilitado=true
               WHERE lab_elementos.id=$idelemento 
               GROUP BY lab_subelementos.id,lab_elementos.id,ctl_sexo.id,ctl_rango_edad.id
               ORDER BY orden
	       LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
              // echo $query;
	 $result = pg_query($query) or die(mysql_error());
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  ///PARA PAGINACION DE BUSQUEDA
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
	     $query = $query_search." LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezarr";
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
  } 
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 
  /*========================================================================================*/
 
    function resultados($id_subelemento){
        /*
        * Julio Castillo
        */
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = "SELECT pr.id as id,
                        pr.posible_resultado resultado
                     FROM lab_posible_resultado pr
                             LEFT JOIN (SELECT id, id_posible_resultado, id_subelemento, habilitado 
                                        FROM lab_subelemento_posible_resultado spr 
                                        WHERE spr.id_subelemento = '$id_subelemento' AND spr.habilitado = true) spr ON spr.id_posible_resultado = pr.id
                     WHERE spr.id is null
                     ORDER BY pr.posible_resultado";

             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }

    function get_subelemento($id_subelemento){
        /*
        * Julio Castillo
        */
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = "SELECT s.id as id,
                        s.subelemento as subelemento_text
                     FROM lab_subelementos s
                     WHERE s.id = '$id_subelemento'";
             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }
    
    function resultados_seleccionados($id_subelemento){
        /*
        * Julio Castillo
        */
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
          $query = "SELECT pr.id as id,
                        pr.posible_resultado as resultado
                    FROM
                        lab_subelemento_posible_resultado spr
                    LEFT JOIN lab_posible_resultado pr ON pr.id = spr.id_posible_resultado
                    WHERE spr.id_subelemento = '$id_subelemento' AND spr.habilitado is true
                    ORDER BY posible_resultado";

             $result = pg_query($query);
             if (!$result)
               return false;
             else
               return $result;
        }
    }
    
    function cambiar_estado($id_subelemento){
        /*
        * Julio Castillo
        */
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
            $query = "UPDATE lab_subelemento_posible_resultado SET habilitado = false WHERE id_subelemento = '$id_subelemento'";
            $result = pg_query($query);
            if (!$result)
              return false;
            else
              return $result;
        }
    }
    
    function cambiar_estado_id($id_posible_resultado,$id_subelemento){
        /*
        * Julio Castillo
        */
        $con = new ConexionBD;
        //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
            $query = "UPDATE lab_subelemento_posible_resultado 
                        SET habilitado = true,
                            fechafin = null,
                            id_user_mod = 8,
                            fecha_mod = now()
                        WHERE id_posible_resultado = '$id_posible_resultado' AND id_subelemento='$id_subelemento'";
            $result=pg_query($query);
            if (pg_affected_rows($result)==0){
                $query = "
                    INSERT INTO lab_subelemento_posible_resultado(
                            id_subelemento, id_posible_resultado, fechainicio, fechafin, 
                            habilitado, id_user, fecha_registro, id_user_mod, fecha_mod)
                    VALUES ('$id_subelemento', '$id_posible_resultado', now(), null, 
                            true, 8, now(), null, null)";
                $result=pg_query($query);
            }
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
         
         function prueba_lab($id_examen){
            /*
            * Julio Castillo
            */
            $con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	      $query = "SELECT nombre_examen as nombre_prueba FROM lab_conf_examen_estab WHERE id=$id_examen";
             
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
 
   function llenarrangosubele($idelemento) {
	$con = new ConexionBD;
	//usamos el metodo conectar para realizar la conexion
	if ( $con->conectar()==true ) {
        /*Aqui entra*/
            $query ="SELECT  orden  
                    FROM lab_subelementos
                    WHERE idelemento=$idelemento
                    ORDER BY orden asc";

			$result = @pg_query( $query );
			if ( !$result )
				return false;
			else
				return $result;
		}
	}
  function BuscarExisteOrden($idelemento,$subelemento){
      $con = new ConexionBD;
	//usamos el metodo conectar para realizar la conexion
	if ( $con->conectar()==true ) {
           $query ="select distinct orden from lab_subelementos  where idelemento=$idelemento and subelemento ilike '$subelemento'";
           $result = @pg_query( $query );
            if ( !$result )
		return false;
            else
		return $result;
        }
  }
  
  function ObtenerNuevoOrden($idelemento){
       $con = new ConexionBD;
	//usamos el metodo conectar para realizar la conexion
	if ( $con->conectar()==true ) {
           $query ="select max(orden)+1 from lab_subelementos  where idelemento=$idelemento ";
          $result = @pg_query( $query );
            if ( !$result )
		return false;
            else
		return $result;
        }    
  }
  
}//CLASE}


class clsLabor_SubElementosExamen{

 
function insertar_labo($idelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)
        
{
   $con2 = new ConexionBDLab;
   if($con2->conectarT()==true) 
   {
              
    $query = "INSERT INTO laboratorio.lab_subelementos(IdElemento,Unidad,SubElemento,rangoinicio,rangofin,FechaIni,FechaFin,IdEstablecimiento,idsexo,idedad)
              VALUES($idelemento,'$unidad','$subelemento',$rangoini,$rangofin,'$Fechaini','$Fechafin',$lugar,$sexo,$redad)";
    
     $result = pg_query($query);
	 if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 
	  


 //ACTUALIZA UN REGISTRO
 function actualizar_labo($idsubelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)
 {			
	$con2 = new ConexionBDLab;
	if($con2->conectarT()==true){
     $query = "UPDATE laboratorio.lab_subelementos SET subelemento='$subelemento',unidad='$unidad',rangoinicio=$rangoini,rangofin=$rangofin,
               IdEstablecimiento=$lugar,FechaIni='$Fechaini',FechaFin='$Fechafin',idsexo=$sexo,idedad=$redad WHERE IdSubElemento=$idsubelemento";
     $result = pg_query($query);
	if (!$result)
	   return 0;
	 else
	   return 1;
	   
   }
 }

 //ELIMINA UN REGISTRO
 function eliminar_labo($idsubelemento)
{
   $con2 = new ConexionBDLab;
	if($con2->conectarT()==true){
     $query = "DELETE FROM laboratorio.lab_subelementos WHERE IdSubElemento=$idsubelemento";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }


 
 

}//CLASE
?>
