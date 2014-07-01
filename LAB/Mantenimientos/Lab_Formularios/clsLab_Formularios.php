<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_Formularios
{
 //constructor	
 function clsLab_Formularios(){
 }	
 
 
 function insertar($Formulario,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO mnt_formularios(NombreFormulario,IdusuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
	VALUES('$Formulario','$usuario',NOW(),'$usuario',NOW())";
	 $result = @mysql_query($query);
	 if (!$result)
       return false;
     else
       $idultimo= @mysql_insert_id();
	   return $idultimo;	   
  
   }
 }
 
 
 function IdUltimoProgramaInsertado()
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "SELECT MAX(IdFormulario) AS id FROM mnt_formularios";
     $result = @mysql_query($query);
	  $row_Id=mysql_fetch_array($result);
	  $Id=$row_Id[0];
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 
 function IngFormularioxEstablecimiento($IdForm,$Idprograma,$lugar,$cond,$usuario){
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO mnt_formulariosxestablecimiento(IdFormulario,IdPrograma,IdEstablecimiento,Condicion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
			  VALUES($IdForm,$Idprograma,$lugar,'$cond',$usuario,NOW(),$usuario,NOW())";
	//echo $query;
   $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
}


function actualizar($IdFormulario,$Formulario,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE mnt_formularios SET NombreFormulario='$Formulario',IdUsuarioMod='$usuario',FechaHoraMod=NOW() WHERE IdFormulario='$IdFormulario'";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 
 function actualizarxestablecimiento($IdFormulario,$IdPrograma,$lugar,$usuario){
    $con = new ConexionBD;
    if($con->conectar()==true) 
    {
        $query = "UPDATE mnt_formulariosxestablecimiento 
                  SET IdPrograma=$IdPrograma,IdUsuarioMod=$usuario,FechaHoraMod=NOW() 
                  WHERE IdFormulario=$IdFormulario AND IdEstablecimiento=$lugar";
        $result = mysql_query($query);

      if (!$result)
        return false;
      else
        return true;
           }
 
 }
 
 
  function eliminar($IdFormulario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM mnt_formularios WHERE IdFormulario='$IdFormulario'";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 //FUNCION PARA VERIFICAR LA INTEGRIDAD DE LOS DATOS
function VerificarIntegridad($IdPrograma)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * FROM mnt_formularios WHERE IdFormulario='$IdFormulario'";
     $result = mysql_query($query);
	 $cuenta = mysql_num_rows($result);
	 
     if ($cuenta > 0)
       return true;
     else
       return false;
    }
}

 // consulta las areas de la BD
 function consultarpag($RegistrosAEmpezar,$RegistrosAMostrar,$lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT mnt_formularios.IdFormulario,mnt_formularios.NombreFormulario,mnt_formulariosxestablecimiento.Condicion,
IF(mnt_formulariosxestablecimiento.Condicion='H','Habilitado','Inhabilitado')as Cond, mnt_programas.NombrePrograma
FROM mnt_formularios
INNER JOIN mnt_formulariosxestablecimiento 
ON mnt_formularios.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario
INNER JOIN mnt_programasxestablecimiento 
ON mnt_formulariosxestablecimiento.IdPrograma=mnt_programasxestablecimiento.IdPrograma
INNER JOIN mnt_programas ON mnt_programasxestablecimiento.IdPrograma=mnt_programas.IdPrograma
WHERE mnt_formulariosxestablecimiento.IdEstablecimiento=$lugar
ORDER BY mnt_formularios.IdFormulario LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
	 $result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 


function consultarProgramas($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT mnt_programas.IdPrograma,mnt_programas.NombrePrograma
			   FROM mnt_programas
			   INNER JOIN mnt_programasxestablecimiento  
			   ON mnt_programas.IdPrograma=mnt_programasxestablecimiento.IdPrograma
			   WHERE IdEstablecimiento=$lugar
			   ORDER BY mnt_programas.NombrePrograma";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

 function consultar($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * FROM mnt_formularios
			   INNER JOIN mnt_formulariosxestablecimiento  
			   ON mnt_formularios.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario
			   WHERE IdEstablecimiento=$lugar
			   ORDER BY mnt_formularios.IdFormulario";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

function consultarpagbus($query,$RegistrosAEmpezar, $RegistrosAMostrar)
 {
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
  } 	

function NumeroDeRegistrosbus($query){
	   //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $numreg = mysql_num_rows(mysql_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
 }

  //consultando el numero de registros de la tabla
   function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT * 
			   FROM mnt_formularios
			   INNER JOIN mnt_formulariosxestablecimiento  
			   ON mnt_formularios.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario
			   WHERE IdEstablecimiento=$lugar";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
  
  // consulta empleado por su codigo
 function consultarid($IdFormulario,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT mnt_formularios.IdFormulario,mnt_formularios.NombreFormulario,mnt_programas.IdPrograma,mnt_programas.NombrePrograma FROM mnt_formularios 
INNER JOIN mnt_formulariosxestablecimiento 
ON mnt_formularios.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario
INNER JOIN mnt_programasxestablecimiento 
ON mnt_formulariosxestablecimiento.IdPrograma=mnt_programasxestablecimiento.IdPrograma
INNER JOIN mnt_programas ON mnt_programasxestablecimiento.IdPrograma=mnt_programas.IdPrograma
WHERE mnt_formulariosxestablecimiento.IdFormulario=$IdFormulario
AND mnt_formulariosxestablecimiento.IdEstablecimiento=$lugar";
	           
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
  function EstadoCuenta($IdFormulario,$cond,$lugar){ 
		$con = new ConexionBD;
		   //usamos el metodo conectar para realizar la conexion
		if($con->conectar()==true){
			 if($cond=='H'){
			$query = "UPDATE mnt_formulariosxestablecimiento SET Condicion='I' 
					  WHERE IdFormulario='$IdFormulario' AND IdEstablecimiento=$lugar";
			$result = mysql_query($query);
				/*	$query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
			$result1 = mysql_query($query1);*/
			 }
			 if($cond=='I'){
				$query = "UPDATE mnt_formulariosxestablecimiento SET Condicion='H' 
						  WHERE IdFormulario='$IdFormulario' AND IdEstablecimiento=$lugar";
				$result = mysql_query($query);
				/*$query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";*/
				//$result1 = mysql_query($query1);

			 }
		}
		$con->desconectar();
	}
  
}//CLASE



?>
