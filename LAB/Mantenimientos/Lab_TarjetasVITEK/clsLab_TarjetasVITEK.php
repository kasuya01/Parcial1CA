<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");

class clsLab_TarjetasVITEK
{
 //constructor	
 function clsLab_TarjetasVITEK(){
 }	
 
 function insertar($nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)
 { 
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
  echo $query = "INSERT INTO lab_tarjetasvitek(nombretarjeta,idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idestablecimiento,fechaini,fechafin) VALUES('$nombretarjeta','$usuario',NOW(),'$usuario',NOW(),$lugar,'$Fechaini','$Fechafin')";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

 function actualizar($idtarjeta,$nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_tarjetasvitek SET nombretarjeta='$nombretarjeta',IdUsuarioMod='$usuario', FechaHoraMod=NOW(),FechaIni='$Fechaini',FechaFin='$Fechafin' WHERE idtarjeta=$idtarjeta AND IdEstablecimiento=$lugar";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }
 
 function eliminar($idtarjeta)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "DELETE FROM lab_tarjetasvitek WHERE idtarjeta='$idtarjeta'";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 // consulta las areas de la BD
 function consultarpag($RegistrosAEmpezar, $RegistrosAMostrar,$lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT IdTarjeta,NombreTarjeta,FechaIni,FechaFin FROM lab_tarjetasvitek 
	 WHERE IdEstablecimiento=$lugar ORDER BY idtarjeta LIMIT $RegistrosAEmpezar, $RegistrosAMostrar ";
	 $result = pg_query($query);
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
     $query = "SELECT * FROM lab_tarjetasvitek WHERE IdEstablecimiento=$lugar ORDER BY IdTarjeta ";
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
 } 
  
  //consultando el numero de registros de la tabla
function NumeroDeRegistros($lugar){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = "SELECT IdTarjeta,NombreTarjeta,FechaIni,FechaFin FROM lab_tarjetasvitek WHERE IdEstablecimiento=$lugar ";
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
}
  
  // consulta empleado por su codigo
 function consultarid($idtarjeta,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
    $query = "SELECT IdTarjeta,NombreTarjeta,DATE_FORMAT(FechaIni,'%d/%m/%Y')AS FechaIni,
			DATE_FORMAT(FechaFin,'%d/%m/%Y')AS FechaFin  FROM lab_tarjetasvitek WHERE IdTarjeta='$idtarjeta' AND IdEstablecimiento=$lugar";
     $result = pg_query($query);
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
		 $result = pg_query($query);
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
	     $numreg = pg_num_rows(pg_query($query));
		 if (!$numreg )
		   return false;
		 else
		   return $numreg ;
	   }
 }
  
}//CLASE

class clsLabor_TarjetasVITEK
{
	function insertar_labo($nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)
	{
		$con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
			echo $query = "INSERT INTO laboratorio.lab_tarjetasvitek(nombretarjeta,idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idestablecimiento,fechaini,fechafin) VALUES('$nombretarjeta','$usuario',NOW(),'$usuario',NOW(),$lugar,'$Fechaini','$Fechafin')";
			$result = pg_query($query);
	 
			if (!$result)
				return false;
			else
				return true;	   
		}
	}

	 function actualizar_labo($idtarjeta,$nombretarjeta,$usuario,$lugar,$Fechaini,$Fechafin)
	 {
	   $con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
		 $query = "UPDATE laboratorio.lab_tarjetasvitek SET nombretarjeta='$nombretarjeta',IdUsuarioReg='$usuario', FechaHoraMod=NOW(),FechaIni='$Fechaini',FechaFin='$Fechafin' WHERE IdTarjeta=$idtarjeta AND IdEstablecimiento=$lugar";
		 $result = pg_query($query);
		 
		 if (!$result)
		   return false;
		 else
		   return true;
		   
	   }
	 }
	 
	  function eliminar_labo($idtarjeta)
	  {
		$con2 = new ConexionBDLab;
		if($con2->conectarT()==true){
			$query = "DELETE FROM laboratorio.lab_tarjetasvitek WHERE idtarjeta='$idtarjeta'";
			$result = pg_query($query);
	 
		if (!$result)
       return false;
     else
       return true;
	   
   }
 }

}//CLASE
?>
