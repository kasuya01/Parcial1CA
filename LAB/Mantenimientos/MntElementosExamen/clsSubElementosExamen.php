<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsSubElementosExamen
{
 //constructor	
 function clsSubElementosExamen(){
 }	

//INSERTA UN REGISTRO          
function insertar($idelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)
         
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_subelementos(idelemento,unidad,subelemento,rangoinicio,rangofin,fechaini,fechafin,idestablecimiento,idsexo,idedad) 
	      VALUES($idelemento,$unidad,'$subelemento',$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)";
  //echo $query;
    $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
        return true;	   
        
   }
 }
 

 //ACTUALIZA UN REGISTRO
 function actualizar($idsubelemento,$unidad,$subelemento,$rangoini,$rangofin,$Fechaini,$Fechafin,$lugar,$sexo,$redad)
 {					
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_subelementos SET subelemento='$subelemento',unidad=$unidad,rangoinicio=$rangoini,
               rangofin=$rangofin,fechaini=$Fechaini,fechafin=$Fechafin,idestablecimiento=$lugar,
               idsexo=$sexo,idedad=$redad WHERE id=$idsubelemento";
  //echo $query; 
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
           $query = "SELECT id, nombre FROM ctl_rango_edad";
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
   { $query = "SELECT lab_subelementos.id,lab_subelementos.subelemento,unidad,lab_elementos.id,elemento,
               lab_subelementos.idestablecimiento,rangoinicio,rangofin, 
               to_char(lab_subelementos.fechaini,'dd/mm/YYYY')AS FechaIni, 
               to_char(lab_subelementos.fechafin,'dd/mm/YYYY')AS FechaFin, 
               ctl_sexo.id as idsexo,ctl_sexo.nombre as nombresexo,ctl_rango_edad.id as idedad,
               ctl_rango_edad.nombre as nombreedad FROM lab_elementos 
               INNER JOIN lab_subelementos ON lab_elementos.id=lab_subelementos.idelemento 
               LEFT JOIN ctl_sexo ON lab_subelementos.idsexo = ctl_sexo.id 
               INNER JOIN ctl_rango_edad ON lab_subelementos.idedad = ctl_rango_edad.id 
	       WHERE lab_subelementos.id=$idsubelemento";
   //echo $query;
     $result = pg_query($query);
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
               ctl_rango_edad.nombre AS nombreedad
               FROM lab_elementos 
               INNER JOIN lab_subelementos ON lab_elementos.id=lab_subelementos.idelemento 
               LEFT JOIN ctl_sexo ON lab_subelementos.idsexo = ctl_sexo.id 
               INNER JOIN ctl_rango_edad ON lab_subelementos.idedad = ctl_rango_edad.id 
               WHERE lab_elementos.id=$idelemento ORDER BY subelemento
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
