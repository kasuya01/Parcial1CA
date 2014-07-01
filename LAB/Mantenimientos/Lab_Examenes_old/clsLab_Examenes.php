<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsLab_Examenes
{
 //constructor	
 function clsLab_Examenes(){
 }	

//INSERTA UN REGISTRO          
 function insertar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,$sexo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_examenes(IdExamen,IdArea,nombreExamen,IdEstandar,Observacion,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdSexo) 
	VALUES('$idexamen','$idarea','$nomexamen','$idestandar','$observacion',$usuario,NOW(),$usuario,NOW(),$sexo)";
   //echo $query;
    $result = mysql_query($query);
	 
    if (!$result)
       return false;
    else
       return true;	   
   }
 }

function IngExamenxEstablecimiento($idexamen,$lugar,$cond,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$TiempoPrevio){
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_examenesxestablecimiento
              (IdExamen,IdEstablecimiento,Condicion,IdUsuarioReg,IdUsuarioMod,FechaHoraReg,FechaHoraMod,IdFormulario,IdEstandarRep,Urgente,IdPlantilla,Impresion,Ubicacion,CODIGOSUMI) 
	      VALUES('$idexamen',$lugar,'H',$usuario,$usuario,NOW(),NOW(),$IdFormulario,'$IdEstandarResp',$Urgente,'$plantilla','$letra',$ubicacion,NULL)";
   //  echo $query;	
    $result = mysql_query($query);
     
    $sqlText = "INSERT INTO cit_programacionxexams (IdExam,RangoTiempoPrev,IdServicio,IdEstablecimiento,IdUsuarioReg,FechaHoraReg) 
                VALUES ('".$idexamen."',$TiempoPrevio,'DCOLAB',$lugar,$usuario,NOW())";
    $dtSub = mysql_query($sqlText) or die('La consulta fall&oacute;: ' . mysql_error());
	// echo $sqlText;
     if (!$result && !$dtSub)
       return false;
     else
       return true;
    }
}

function AgregarDatosFijos($idexamen,$idarea,$usuario,$lugar){
    $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_datosfijosresultado (IdArea,IdExamen,IdUsuarioReg,FechaHoraReg,IdEstablecimiento,FechaIni,FechaFin)
              VALUES ('$idarea','$idexamen',$usuario,NOW(),$lugar,CURDATE(),NULL)";
    // echo $query;
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;
    }
    
}

 //ACTUALIZA UN REGISTRO
 function actualizar($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,$sexo)
 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		 $query = "UPDATE lab_examenes SET nombreExamen='$nomexamen',idestandar='$idestandar',idarea='$idarea',Observacion='$observacion',
		 IdUsuarioMod='$usuario',FechaHoraMod=NOW(),IdSexo=$sexo WHERE idexamen='$idexamen'";
		 $result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
			//echo $query;
		   return true;
	  }
 }

 //ACTUALIZA UN REGISTRO
 function ActExamenxEstablecimiento($idexamen,$lugar,$usuario,$IdFormulario,$IdEstandarResp,$plantilla,$letra,$Urgente,$ubicacion,$Hab,$TiempoPrevio)
          
 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
		 $query =   "UPDATE lab_examenesxestablecimiento 
                            INNER JOIN cit_programacionxexams ON (lab_examenesxestablecimiento.idexamen=cit_programacionxexams.idexam AND 
                            lab_examenesxestablecimiento.idestablecimiento=cit_programacionxexams.idestablecimiento)
                            SET IdUsuarioMod='$usuario',FechaHoraMod=NOW(),IdFormulario=$IdFormulario,
                            IdEstandarRep='$IdEstandarResp',IdPlantilla='$plantilla',Impresion='$letra',Urgente=$Urgente,Ubicacion=$ubicacion,
                            Condicion='$Hab', cit_programacionxexams.RangoTiempoPrev=$TiempoPrevio
                            WHERE idexamen='$idexamen' AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar";
		//echo $query;
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
  
  
 //CONSULTA el catalogo de sexo
  function catalogo_sexo(){
       $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT * FROM mnt_sexo ";
			 $result = mysql_query($query);
			if (!$result)
                             return false;
			else
                             return  $result;
                        
                }
      
  }
  //CONSULTA LOS FORMULARIOS POR PROGRAMA
  function consultar_formularios($IdPrograma,$lugar){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT mnt_formulariosxestablecimiento.IdFormulario,NombreFormulario,IdPrograma 
				  FROM mnt_formularios 
				  INNER JOIN mnt_formulariosxestablecimiento 
				  ON mnt_formularios.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario
				
WHERE IdPrograma=$IdPrograma AND IdEstablecimiento=$lugar";
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
lab_examenesxestablecimiento.IdPlantilla,Observacion,lab_examenes.habilitado,lab_examenesxestablecimiento.Condicion,
lab_examenes.Ubicacion,Urgente 
from lab_examenes 
INNER JOIN lab_areas  on lab_examenes.IdArea=lab_areas.IdArea
INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
WHERE lab_examenesxestablecimiento.Condicion='H' AND lab_examenesxestablecimiento.IdEstablecimiento=$lugar
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
		    Descripcion,lab_areas.NombreArea,lab_examenesxestablecimiento.IdPlantilla, 
		    lab_plantilla.Plantilla,Observacion,lab_examenesxestablecimiento.Ubicacion,
		    mnt_formulariosxestablecimiento.IdFormulario,mnt_formularios.NombreFormulario,
		    lab_examenesxestablecimiento.IdEstandarRep,mnt_programas.IdPrograma,
		    mnt_programas.NombrePrograma,lab_examenesxestablecimiento.Impresion,
                    lab_examenesxestablecimiento.Urgente,lab_examenes.IdSexo,mnt_sexo.sexovn,lab_examenesxestablecimiento.Condicion,
                    rangotiempoprev 
                    FROM lab_examenes 
                    INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
                    INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
                    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                    LEFT JOIN lab_plantilla  ON lab_plantilla.IdPlantilla=lab_examenesxestablecimiento.IdPlantilla
                    LEFT JOIN mnt_formulariosxestablecimiento ON lab_examenesxestablecimiento.IdFormulario=mnt_formulariosxestablecimiento.IdFormulario
                    LEFT JOIN mnt_programas ON mnt_formulariosxestablecimiento.IdPrograma= mnt_programas.IdPrograma
                    LEFT JOIN mnt_formularios ON mnt_formulariosxestablecimiento.IdFormulario=mnt_formularios.IdFormulario
                    INNER JOIN mnt_sexo ON lab_examenes.IdSexo= mnt_sexo.IdSexo
                    LEFT JOIN cit_programacionxexams ON lab_examenesxestablecimiento.idexamen=cit_programacionxexams.idexam
                    WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar AND lab_examenes.IdExamen='$idexamen'";
          
          //echo $query;
     $result = mysql_query($query);
     if (!$result)
       return false;
     else
	 //echo $query;
       return $result;
    }
  }

//OBTENER PLANTILLAS
 function LeerPlantilla()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT * from lab_plantilla";
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
       $query ="SELECT *
                FROM lab_examenesxestablecimiento 
                INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea
                INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
                INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar= lab_codigosestandar.IdEstandar
                LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario 
                INNER JOIN mnt_sexo ON lab_examenes.IdSexo= mnt_sexo.IdSexo
                WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar 
                AND lab_areasxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
                ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen"; 
               
             
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
        $query ="SELECT count( * )
                 FROM lab_examenes
                 INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen = lab_examenesxestablecimiento.IdExamen
                 WHERE lab_examenes.idexamen = '$idexamen'
                 AND IdEstablecimiento =$lugar";
              
       // echo $query;
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
                             lab_examenesxestablecimiento.Ubicacion,lab_examenesxestablecimiento.IdPlantilla,mnt_formularios.NombreFormulario,
                             lab_examenesxestablecimiento.IdEstandarRep ,lab_examenesxestablecimiento.Impresion,Urgente,sexovn,rangotiempoprev
                             FROM lab_examenesxestablecimiento 
                             INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                             INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea
                             INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
                             INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar= lab_codigosestandar.IdEstandar
                             LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.IdFormulario=mnt_formularios.IdFormulario
                             INNER JOIN mnt_sexo ON lab_examenes.IdSexo= mnt_sexo.IdSexo
                             LEFT JOIN cit_programacionxexams ON lab_examenesxestablecimiento.idexamen=cit_programacionxexams.idexam
                             WHERE lab_examenesxestablecimiento.IdEstablecimiento=$lugar 
                             AND lab_areasxestablecimiento.Condicion='H' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar
                             ORDER BY lab_examenes.IdArea,lab_examenes.IdExamen  
                             LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
                          //echo $query; 
                            $result = mysql_query($query);
                             
                            if (!$result)
                                return false;
                            else
                                return $result;
                      echo $query;      
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
            $result1 = mysql_query($query1);
	 }
    }
    $con->desconectar();
    }
	
 function obtener_letra($idarea){
	$con = new ConexionBD;
    if($con->conectar()==true) 
	{
		$query = "SELECT ORD(Impresion)
		FROM lab_examenesxestablecimiento 
		WHERE IdExamen like'$idarea%' 
		AND Impresion<>'G' ORDER BY Impresion DESC LIMIT 1";
		$result = mysql_query($query);
		if (!$result)
			return false;
		else
			return  $result;
	}

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
