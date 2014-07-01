<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_Examenes
{
 //constructor	
 function clsLab_Examenes(){
 }	

//INSERTA UN REGISTRO          
 function insertar($idexamen,$idarea,$nomexamen,$idestandar,$plantilla,$observacion,$activo,$letra,$ubicacion,$usuario)
	
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_examenes(idexamen,IdArea,nombreExamen,IdEstandar,Observacion,IdPlantilla,Habilitado,Impresion,Ubicacion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) 
	VALUES('$idexamen','$idarea','$nomexamen','$idestandar','$observacion','$plantilla','$activo','$letra',$ubicacion,$usuario,NOW(),$usuario,NOW())";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

function  insertar_fijos($idexamen,$idarea,$lugar,$usuario){
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
		$query ="INSERT INTO lab_datosfijosresultado(IdArea,idexamen,IdUsuarioReg,FechaHoraReg,IdEstablecimiento,IdUsuarioMod,FechaHoraMod)
				VALUES('$idarea','$idexamen',$usuario,NOW(),$lugar,$usuario,NOW())";
	     $result = mysql_query($query);
	  if (!$result)
        return false;
	  else
        return true;
   }
  
}

function IngExamenxEstablecimiento($idexamen,$lugar,$cond,$usuario,$IdFormulario,$IdEstandarResp){
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_examenesxestablecimiento(IdExamen,IdEstablecimiento,Condicion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdFormulario,IdEstandarRep) 
			  VALUES('$idexamen',$lugar,'H',$usuario,NOW(),$usuario,NOW(),$IdFormulario,'$IdEstandarResp')";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
}

 //ACTUALIZA UN REGISTRO
 function actualizar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$plantilla,$etiqueta,$ubicacion,$usuario)
 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		 $query = "UPDATE lab_examenes SET nombreExamen='$nomexamen' , idestandar='$idestandar', idarea='$idarea', IdPlantilla='$plantilla',Observacion='$observacion',Impresion='$etiqueta',Ubicacion=$ubicacion,IdUsuarioMod='$usuario', FechaHoraMod=NOW() WHERE idexamen='$idexamen'";
		 $result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return true;
	  }
 }

 //ACTUALIZA UN REGISTRO
 function ActExamenxEstablecimiento($idexamen,$lugar,$usuario,$IdFormulario,$IdEstandarResp)
 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		 $query = "UPDATE lab_examenesxestablecimiento SET IdUsuarioMod='$usuario',FechaHoraMod=NOW(),IdFormulario=$IdFormulario,IdEstandarRep='$IdEstandarResp' WHERE idexamen='$idexamen'AND IdEstablecimiento=$lugar";
		 $result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return true;
	  }
 }

 //CONSULTA LOS PROGRAMAS
  function consultar_programas(){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT * FROM mnt_programas ORDER BY IdPrograma";
			 $result = mysql_query($query);
			if (!$result)
				return false;
			else
				return  $result;
		}
  }

   function obtener_letra($idarea){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT ORD(Impresion) FROM lab_examenes WHERE IdArea='$idarea' AND Impresion<>'G' ORDER BY Impresion DESC LIMIT 1";
			$result = mysql_query($query);
			if (!$result)
				return false;
			else
				return  $result;
		}
   }

  
  //CONSULTA LOS FORMULARIOS POR PROGRAMA
  function consultar_formularios($IdPrograma){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT IdFormulario,NombreFormulario FROM mnt_formularios where IdPrograma=$IdPrograma";
			 $result = mysql_query($query);
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
     $query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,NombreExamen,Descripcion,NombreArea,
IdPlantilla,Observacion,lab_examenes.habilitado,lab_examenesxestablecimiento.Condicion,lab_examenes.Ubicacion from lab_examenes 
INNER JOIN lab_areas  on lab_examenes.IdArea=lab_areas.IdArea
INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
WHERE lab_examenes.Habilitado='S' AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen";
	 $result = mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 

  function Obtener_NombreEstandar($IdEstandarResp){
		$con = new ConexionBD;
	   if($con->conectar()==true)
	   {
		
		 $query = "SELECT Descripcion FROM lab_codigosestandar WHERE  IdEstandar='$IdEstandarResp'";
		 $result = mysql_query($query);
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
	  $query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,lab_examenes.NombreExamen,
				Descripcion,lab_areas.NombreArea,lab_examenes.IdPlantilla, 
				lab_plantilla.Plantilla,Observacion,lab_examenes.Habilitado,lab_examenes.Ubicacion,mnt_formularios.IdFormulario,
				mnt_formularios.NombreFormulario,lab_examenesxestablecimiento.IdEstandarRep,mnt_programas.IdPrograma,
				mnt_programas.NombrePrograma 
				FROM lab_examenes 
				INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
				INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
				INNER JOIN lab_plantilla  ON lab_plantilla.IdPlantilla=lab_examenes.IdPlantilla
				INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
				LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario
				LEFT JOIN mnt_programas ON mnt_formularios.IdPrograma= mnt_programas.IdPrograma
				WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_examenes.IdExamen='$idexamen'";
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

//OBTENER PLANTILLAS
 function LeerPlantilla()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT *  from lab_plantilla";
     $result = mysql_query($query);
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
     $query = "SELECT IdExamen FROM lab_examenes WHERE Idarea='$idarea' ORDER BY IdExamen DESC LIMIT 1";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
	   $row = mysql_fetch_array($result);
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
       $query = "SELECT * FROM lab_examenes 
				INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen= lab_examenesxestablecimiento.IdExamen
				WHERE lab_examenesxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar";
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

 function BuscarExamen($idexamen,$lugar){
	  $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
		   $query = "SELECT count(*) FROM lab_examenesxestablecimiento WHERE idexamen='$idexamen' AND IdEstablecimiento=$lugar";
			$result = mysql_query($query);
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
		   $query = "SELECT lab_examenes.IdExamen,lab_examenes.IdEstandar,lab_examenes.IdArea,NombreExamen,Descripcion,
			NombreArea,lab_examenesxestablecimiento.Condicion,
			IF(lab_examenesxestablecimiento.Condicion='H','Habilitado','Inhabilitado')AS Cond,
			lab_examenes.Ubicacion,lab_examenes.IdPlantilla,mnt_formularios.NombreFormulario,lab_examenesxestablecimiento.IdEstandarRep
			FROM lab_examenes 
			INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
			INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar= lab_codigosestandar.IdEstandar
			INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
			LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario
			WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_areasxestablecimiento.Condicion='H'
			ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen  
			LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
			 $result = mysql_query($query);
			 if (!$result)
			   return false;
			 else
			   return $result;
	   }
  } 
  
  function EstadoCuenta($idexamen,$cond,$lugar){ 
		$con = new ConexionBD;
		   //usamos el metodo conectar para realizar la conexion
		if($con->conectar()==true){
			 if($cond=='H'){
			$query = "UPDATE lab_examenesxestablecimiento SET Condicion='I' WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar";
			$result = mysql_query($query);
					$query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
			$result1 = mysql_query($query1);
			 }
			 if($cond=='I'){
				$query = "UPDATE lab_examenesxestablecimiento SET Condicion='H' WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar";
				$result = mysql_query($query);
				$query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
				//$result1 = mysql_query($query1);

			 }
		}
		$con->desconectar();
	}
 
//*******************************************FIN FUNCIONES PARA MANEJO DE PAGINACION************************************************/
 
}//CLASE

/*class Estado{
	function EstadoCuenta1($idexamen,$cond,$lugar){ 
		$con = new ConexionBD;
		   //usamos el metodo conectar para realizar la conexion
		if($con->conectar()==true){
			 if($cond=='H'){
			$query = "UPDATE lab_examenesxestablecimiento SET Condicion='I' WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar";
			$result = mysql_query($query);
					$query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
			$result1 = mysql_query($query1);
			 }
			 if($cond=='I'){
				$query = "UPDATE lab_examenesxestablecimiento SET Condicion='H' WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar";
				$result = mysql_query($query);
				$query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
				//$result1 = mysql_query($query1);

			 }
		}
		$con->desconectar();
	}

}//CLASE*/

class clsLabor_Examenes
{
	//INSERTA UN REGISTRO          
	 function insertar_labo($idexamen,$idarea,$nomexamen,$idestandar,$plantilla,$observacion,$activo,$ubicacion,$usuario)
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		$query = "INSERT INTO laboratorio.lab_examenes(idexamen,IdArea,nombreExamen,IdEstandar,Observacion,IdPlantilla,Habilitado,Ubicacion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod) VALUES('$idexamen','$idarea','$nomexamen','$idestandar','$observacion','$plantilla','$activo',$ubicacion,$usuario,NOW(),$usuario,NOW())";
		 $result = mysql_query($query);
		 
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
		 $result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return true;
	  }
	}

}

?>
