<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsMnt_OrigenMuestra
{
 //constructor	
 function clsMnt_OrigenMuestra(){
 }	
 //Fn PG
 function insertar($nombreorigen,$tipomuestra,$usuario)
 //insertar($idorigen,$nombreorigen,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   //nuevo
     $previo= "select * from mnt_origenmuestra where origenmuestra ilike '$nombreorigen' and idtipomuestra=$tipomuestra";
    $resulta =pg_query($previo);  
    $resultado = pg_num_rows($resulta);  
    //echo $previo.' res'.$resultado;
    if (!$resulta)
           return false;
    if  ($resultado>0){
        $row= @pg_fetch_array($resulta);
        if ($row['habilitado']=='t')
            return 0;
        else{
              $sql='UPDATE mnt_origenmuestra
                  set habilitado=true,
                  idusuariomod='.$usuario.',
                  fechahoramod=now()
                  where id='.$row["id"];
              $pgquery=pg_query($sql);  
              if (!$pgquery)
                return false;
              else
                return true;	
        }
          
    }
    else{
        $query = "INSERT INTO mnt_origenmuestra(origenmuestra,idtipomuestra,idusuarioreg,fechahorareg)  
            VALUES('$nombreorigen',$tipomuestra,$usuario,NOW())";
         $result = @pg_query($query);
         if (!$result)
           return false;
         else
           return true;	   
       }
     
     
     
   }
 }

//Fn PG
 function actualizar($idorigen,$idtipomuestra,$nombreorigen,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query = "UPDATE mnt_origenmuestra 
   SET idtipomuestra=$idtipomuestra, 
   origenmuestra='$nombreorigen', 
   idusuariomod=$usuario,
   fechahoramod=NOW() 
   WHERE id=$idorigen";
   
     $result = @pg_query($query);
	 if (!$result)
       return false;
     else
       return true;
   }
       
 }
  
  
 //Fn PG 
 function eliminar($idorigen, $usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "Update mnt_origenmuestra
        set habilitado=false,
        idusuariomod=$usuario,
        fechahoramod=now()
        where id=$idorigen";
     $result = @pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 // consulta las areas de la BD
 function consultar(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from mnt_origenmuestra";
	 $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

//*******************************FUNCIONES PARA MANEJO DE PAGINACION************************************/
 //consultando el numero de registros de la tabla
  //fn pg
   function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from mnt_origenmuestra where habilitado=true";
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
//Fn_pg		   
  function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select mom.*, tipomuestra
                from mnt_origenmuestra mom 
                join lab_tipomuestra ltm on (mom.idtipomuestra=ltm.id) 
                where mom.habilitado=true
                order by origenmuestra 
                LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
	 $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
  // consulta empleado por su codigo
  //Fn PG
 function consultarid($idorigen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT mnt_origenmuestra.id,
lab_tipomuestra.id as idtipomuestra,
lab_tipomuestra.tipomuestra,
mnt_origenmuestra.origenmuestra,
mnt_origenmuestra.idusuarioreg 
FROM mnt_origenmuestra  
inner join lab_tipomuestra on lab_tipomuestra.id=mnt_origenmuestra.idtipomuestra
WHERE mnt_origenmuestra.id=$idorigen";
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
//Fn PG
function consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query." order by origenmuestra
                 LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
		 $result = @pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
} 	
//Fn PG
function NumeroDeRegistrosbus($query){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $numreg = pg_num_rows(pg_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
 }

 //*********************** FUNCION PARA OBTENER LOS NOMBRES DE TIPOS DE MUESTRA*****************************/
 //Fn pg
 function LeerTipoMuestra()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_tipomuestra 
         where habilitado=true
         order by tipomuestra";
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
}//CLASE
?>
