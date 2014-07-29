<?php 
include_once("../../../Conexion/ConexionBD.php");
class clsLab_TipoMuestra
{
 //constructor	
 function clsLab_TipoMuestra()
 {
 }	
 //fn_pg
 function insertar($nombretipo,$usuario)
 {
    
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $previo= "select * from lab_tipomuestra where tipomuestra ilike '$nombretipo'";
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
              $sql='UPDATE lab_tipomuestra
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
        $query = "INSERT INTO lab_tipomuestra(tipomuestra,idusuarioreg,fechahorareg)  
            VALUES('$nombretipo','$usuario',NOW())";
         $result = @pg_query($query);
//echo '<br/>'.$query;
         if (!$result)
           return false;
         else
           return true;	   
       }
   }
 }
 //fn_pg
 function actualizar($idtipo,$nombretipo,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query = "UPDATE lab_tipomuestra 
        SET tipomuestra='$nombretipo',
        idusuariomod=$usuario,
        fechahoramod=NOW() 
        WHERE id=$idtipo";
     $result = @pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 //fn pg
  function eliminar($idtipo, $usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_tipomuestra
                set habilitado=false, 
                idusuariomod=$usuario,
                fechamod=now()
                where id=$idtipo";
     $result = @pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 //Fn Pg Consultada  desde MntTipoMuestrasPorExamen.php
 function consultar(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_tipomuestra where habilitado=true order by tipomuestra";
	 $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
  //FN PG
function VerificarIntegridad($idtipo)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_tipomuestraporexamen WHERE idtipomuestra=$idtipo";
     $result = @pg_query($query);
	 $cuenta = pg_num_rows($result);
	 /* if ($cuenta > 0)
       return true;
     else
       return false; */
	   return $cuenta ;
    }
}

//************************************************FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
 //consultando el numero de registros de la tabla
//Fn PG
   function NumeroDeRegistros(){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_tipomuestra where habilitado=true";
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
//Funcion modificada postgres		   
  function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "select * from lab_tipomuestra 
         where habilitado=true
order by tipomuestra 
LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar;";
	 $result = @pg_query($query);
         //echo '<br>'.$query;
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
//************************************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************************************/
  // consulta empleado por su codigo
  //fn pg
 function consultarid($idtipo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM lab_tipomuestra WHERE id=$idtipo and habilitado=true";
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
//fn pg
function consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query." ORDER BY tipomuestra LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
		 $result = @pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
  } 	
//fn pg
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

}//CLASE
?>
