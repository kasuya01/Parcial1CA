<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_Empleados
{
 //constructor	
 function clsLab_Empleados(){
 }	

//INSERTA UN REGISTRO          
 function insertar($IdEmpleado,$lugar,$idarea,$nomempleado,$cargo,$usuario,$corr,$IdEstabExt)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO mnt_empleados(IdEmpleado,IdEstablecimiento,IdTipoEmpleado,IdArea,NombreEmpleado,IdCargoEmpleado,
        Correlativo,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdEstablecimientoExt) 
        VALUES('$IdEmpleado',$lugar,'LAB','$idarea',UCASE('$nomempleado'),'$cargo','$corr',$usuario,NOW(),$usuario,NOW(),$IdEstabExt)";
     $result = @mysql_query($query);
   //  echo $query;
	 if (!$result)
       return false;
     else
       return true;	   
   }
 }

function Insertar_Usuario($login,$idempleado,$pass,$niv,$lugar)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {  
	$query="INSERT INTO mnt_usuarios(login,password,nivel,modulo,Grupo,IdEmpleado,EstadoCuenta,IdSubServicio,IdEstablecimiento) 
VALUES('$login',md5('$pass'),$niv,'LAB',0,'$idempleado','H',0,$lugar)";
      $result = mysql_query($query);
	 if (!$result)
       return 0;
     else
       return 1;	   
   }
}

 //ACTUALIZA UN REGISTRO
function actualizar($IdEmpleado,$lugar,$idarea,$nomempleado,$cargo,$usuario)
    //actualizar($idempleado,$idarea,$nomempleado,$cargo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE mnt_empleados SET NombreEmpleado=UCASE('$nomempleado'), idarea='$idarea', idcargoempleado=$cargo, IdUsuarioMod ='$usuario',FechaHoraMod=NOW() WHERE IdEmpleado='$IdEmpleado' AND IdEstablecimiento=$lugar";

     $result = @mysql_query($query);
	 if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 //ACTUALIZA UN REGISTRO
function actualizar_Usuario($idempleado,$login,$niv,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE mnt_usuarios SET login='$login',nivel=$niv WHERE IdEmpleado='$idempleado' AND IdEstablecimiento=$lugar";
     $result = mysql_query($query);
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
     $query = "SELECT mnt_empleados.IdEmpleado,mnt_empleados.IdArea ,mnt_empleados.NombreEmpleado,
mnt_cargoempleados.IdCargoEmpleado,mnt_cargoempleados.Cargo,mnt_empleados.IdEstablecimiento 
FROM mnt_empleados 
INNER JOIN mnt_cargoempleados  ON mnt_empleados.IdCargoEmpleado=mnt_cargoempleados.IdCargoEmpleado
INNER JOIN lab_areas  ON mnt_empleados.IdArea=lab_areas.IdArea
WHERE mnt_empleados.IdTipoEmpleado='LAB' AND mnt_empleados.IdEstablecimiento=$lugar
ORDER BY mnt_empleados.IdArea,mnt_empleados.IdEmpleado";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

//CONSULTA EXAMEN POR EL CODIGO
 function consultarid($IdEmpleado,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   { $query = "SELECT mnt_empleados.IdEmpleado,mnt_empleados.IdArea,lab_areas.NombreArea,mnt_empleados.NombreEmpleado,
	       mnt_cargoempleados.IdCargoEmpleado,mnt_cargoempleados.Cargo, mnt_usuarios.login 
	       FROM mnt_empleados 
	       INNER JOIN mnt_cargoempleados ON mnt_empleados.IdCargoEmpleado=mnt_cargoempleados.IdCargoEmpleado
	       INNER JOIN lab_areas ON mnt_empleados.IdArea=lab_areas.IdArea
	       INNER JOIN mnt_usuarios 
	      ON (mnt_empleados.IdEmpleado= mnt_usuarios.IdEmpleado AND mnt_empleados.IdEstablecimiento=mnt_usuarios.IdEstablecimiento)
	       WHERE mnt_empleados.IdEmpleado='$IdEmpleado' AND mnt_empleados.IdEstablecimiento=$lugar";
  // echo $query;
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

//FUNCION PARA LEER EMPLEADOS
function LeerEmpleados($lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     
	 $query = "SELECT IdEmpleado,NombreEmpleado from mnt_empleados a
		   WHERE IdTipoEmpleado='LAB' AND IdEstablecimiento=$lugar";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

 //FUNCION PARA LEER CARGOS
function LeerCargos()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     
	 $query = "SELECT IdCargoEmpleado,Cargo from mnt_cargoempleados a
				WHERE IdServicio='DCOLAB'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
//FUNCION PARA LEER EL ULTIMO CODIGO INSERTADO
function LeerUltimoCodigo($lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    // $query = "select (ultimoregistro + 1) AS ultimoregistro from  mnt_codigosgenerados WHERE idcodigogenerado='EMPLAB'";
  $query="SELECT (Correlativo+1) AS ultimo FROM mnt_empleados where IdTipoEmpleado='LAB' AND IdEstablecimiento=$lugar  ORDER BY Correlativo DESC LIMIT 1";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
	   $row = mysql_fetch_array($result);
       return $row[0];
	   
	   
   }
 }

function BuscarEmpleado($IdEmpleado,$lugar){
  $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
       $query = "SELECT count(*) FROM mnt_empleados WHERE IdEmpleado='$IdEmpleado' 
                 AND IdEstablecimiento=$lugar";
       $result = mysql_query($query);
	 if (!$result)
            return false;
	 else
            return $result;
    }
 } 
 
/*****************************FUNCIONES PARA MANEJO DE PAGINACION*****************************/
 //consultando el numero de registros de la tabla
   function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM mnt_empleados WHERE IdTipoEmpleado='LAB' 
                AND Correlativo<>0 AND IdEstablecimiento=$lugar";
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
	  
	  
  function consultarpag($lugar,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT mnt_empleados.IdEmpleado,mnt_empleados.IdArea,lab_areas.NombreArea,mnt_empleados.NombreEmpleado,
mnt_cargoempleados.IdCargoEmpleado,mnt_cargoempleados.Cargo,mnt_empleados.IdEstablecimiento,
IF(mnt_usuarios.EstadoCuenta='H','Habilitado','Inhabilitado')as Habilitado, mnt_usuarios.EstadoCuenta,
mnt_usuarios.login 
FROM mnt_empleados 
INNER JOIN mnt_cargoempleados ON mnt_empleados.IdCargoEmpleado=mnt_cargoempleados.IdCargoEmpleado
INNER JOIN lab_areas ON mnt_empleados.IdArea=lab_areas.IdArea
INNER JOIN mnt_usuarios ON (mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado AND mnt_empleados.IdEstablecimiento= mnt_usuarios.IdEstablecimiento)
WHERE mnt_empleados.IdTipoEmpleado='LAB' AND mnt_empleados.IdEstablecimiento=$lugar
ORDER BY IdEmpleado LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
     
   //echo $query;
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  function consultarpagbus($query_search,$RegistrosAEmpezar,$RegistrosAMostrar)
	 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query_search." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
             //echo $query;
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 
//***************************FIN FUNCIONES PARA MANEJO DE PAGINACION***************************/
}//CLASE
 class Estado{
function EstadoCuenta($IdEmpleado,$Estado,$lugar){ 
	$con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
	     if($Estado=='H'){
		$query = "UPDATE mnt_usuarios SET EstadoCuenta='I' WHERE IdEmpleado='$IdEmpleado' AND IdEstablecimiento=$lugar";
		$result = mysql_query($query);
	     }
	     if($Estado=='I'){
		$query = "UPDATE mnt_usuarios SET EstadoCuenta='H' WHERE IdEmpleado='$IdEmpleado' AND IdEstablecimiento=$lugar";
		$result = mysql_query($query);
	     }
	}
$con->desconectar();
}

}//CLASE
?>
