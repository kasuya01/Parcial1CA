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
    $query = "INSERT INTO lab_subelementos(IdElemento,Unidad,SubElemento,rangoinicio,rangofin,FechaIni,FechaFin,IdEstablecimiento,idsexo,idedad) 
	      VALUES($idelemento,'$unidad','$subelemento',$rangoini,$rangofin,'$Fechaini','$Fechafin',$lugar,$sexo,$redad)";
  //echo $query;
    $result = mysql_query($query);
	 
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
     $query = "UPDATE lab_subelementos SET subelemento='$subelemento',unidad='$unidad',rangoinicio='$rangoini',
               rangofin='$rangofin',FechaIni='$Fechaini',FechaFin='$Fechafin',IdEstablecimiento=$lugar,
               idsexo=$sexo,idedad=$redad WHERE IdSubElemento=$idsubelemento";
    //echo $query; 
     $result = mysql_query($query);
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
     $query = "DELETE FROM lab_subelementos WHERE IdSubElemento=$idsubelemento";
     $result = mysql_query($query);
	 
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
	 $result = @mysql_query($query);
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
   { $query = "SELECT IdSubElemento,lab_subelementos.SubElemento,Unidad,lab_elementos.IdElemento,Elemento,
		lab_subelementos.IdEstablecimiento,rangoinicio,rangofin,
                DATE_FORMAT(lab_subelementos.FechaIni,'%d/%m/%Y')AS FechaIni,
                DATE_FORMAT(lab_subelementos.FechaFin,'%d/%m/%Y')AS FechaFin,
                mnt_sexo.idsexo,mnt_sexo.sexovn,mnt_rangoedad.idedad,mnt_rangoedad.nombregrupoedad 
		FROM lab_elementos  
		INNER JOIN lab_subelementos ON lab_elementos.IdElemento=lab_subelementos.IdElemento
                INNER JOIN mnt_sexo ON lab_subelementos.idsexo = mnt_sexo.idsexo
                INNER JOIN mnt_rangoedad ON lab_subelementos.idedad = mnt_rangoedad.idedad
		WHERE IdSubElemento=$idsubelemento";
     $result = @mysql_query($query);
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
     $result = @mysql_query($query);
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
     $result = @mysql_query($query);
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
	 $num = mysql_query($query) or die(mysql_error());
	 $numreg = mysql_num_rows($num);
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
     $query = "SELECT IdSubElemento,lab_subelementos.SubElemento,Unidad,lab_elementos.IdElemento,Elemento,rangoinicio,rangofin,
	       DATE_FORMAT(lab_subelementos.FechaIni,'%d/%m/%Y')AS FechaIni,
	       DATE_FORMAT(lab_subelementos.FechaFin,'%d/%m/%Y')AS FechaFin ,mnt_sexo.idsexo,mnt_sexo.sexovn,
               mnt_rangoedad.idedad,mnt_rangoedad.nombregrupoedad 
	       FROM lab_elementos 
	       INNER JOIN lab_subelementos ON lab_elementos.IdElemento=lab_subelementos.IdElemento
               INNER JOIN mnt_sexo ON lab_subelementos.idsexo = mnt_sexo.idsexo
               INNER JOIN mnt_rangoedad ON lab_subelementos.idedad = mnt_rangoedad.idedad
	       WHERE lab_elementos.IdElemento=$idelemento ORDER BY SubElemento
	       LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
             //  echo $query;
	 $result = mysql_query($query) or die(mysql_error());
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
		 $numreg = mysql_num_rows(mysql_query($query));
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
	     $query = $query_search." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 $result = mysql_query($query);
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
    
     $result = mysql_query($query);
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
     $result = mysql_query($query);
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
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }



}//CLASE
?>
