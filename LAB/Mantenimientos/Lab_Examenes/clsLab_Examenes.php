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
    $query = "INSERT INTO lab_examenes(idexamen,idarea,nombreexamen,idestandar,Observacion,idusuarioreg,fechahorareg,idusuariomod,fechahoramod,idsexo) 
              VALUES($idexamen,$idarea,$nomexamen,$idestandar,$observacion,$usuario,NOW(),$usuario,NOW(),$sexo)";
   //echo $query;
    $result = pg_query($query);
	 
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
              (idexamen,idestablecimiento,condicion,idusuarioreg,idusuariomod,fechahorareg,fechahoramod,idformulario,idestandarRep,urgente,idplantilla,impresion,Ubicacion,codigosumi) 
	      VALUES($idexamen,$lugar,'H',$usuario,$usuario,NOW(),NOW(),$IdFormulario,'$IdEstandarResp',$Urgente,$plantilla,'$letra',$ubicacion,NULL)";
     echo $query;	
    $result = pg_query($query);
     
    $sqlText = "INSERT INTO cit_programacionxexams (idexam,rangotiempoprev,idservicio,idestablecimiento,idusuarioreg,fechahorareg) 
                VALUES ($idexamen,$TiempoPrevio,78,$lugar,$usuario,NOW())";
    $dtSub = pg_query($sqlText) or die('La consulta fall&oacute;: ' . pg_error());
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
     $result = pg_query($query);
	 
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
		 $result = pg_query($query);
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
                 $result = pg_query($query);
                
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
			 $result = pg_query($query);
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
			$query = "SELECT * FROM ctl_sexo where id <>3";
			 $result = pg_query($query);
			if (!$result)
                             return false;
			else
                             return  $result;
                        
                }
      
  }
  //CONSULTA LOS FORMULARIOS POR PROGRAMA
  function consultar_formularios($lugar){
	 $con = new ConexionBD;
	 if($con->conectar()==true) 
		{
			$query = "SELECT mnt_formulariosxestablecimiento.idformulario,nombreformulario 
				  FROM mnt_formularios 
				  INNER JOIN mnt_formulariosxestablecimiento    
				  ON mnt_formularios.id=mnt_formulariosxestablecimiento.idformulario
		                  WHERE idestablecimiento=$lugar";
                        //echo $query;
			 $result = pg_query($query);
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
     $query = "SELECT lab_examenes.idexamen,lab_examenes.idestandar,lab_examenes.idarea,nombreexamen,descripcion,nombreArea,
lab_examenesxestablecimiento.idplantilla,observacion,lab_examenes.habilitado,lab_examenesxestablecimiento.condicion,
lab_examenes.ubicacion,urgente 
from lab_examenes 
INNER JOIN lab_areas  on lab_examenes.idarea=lab_areas.id
INNER JOIN lab_codigosestandar ON lab_examenes.idestandar=lab_codigosestandar.id
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
WHERE lab_examenesxestablecimiento.condicion='H' AND lab_examenesxestablecimiento.idestablecimiento=$lugar
ORDER BY lab_examenes.idarea,lab_examenes.idexamen";
	 $result = pg_query($query);
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
		 $result = pg_query($query);
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
	  $query = "SELECT lab_examenes.id,lab_examenes.idexamen,lab_examenes.idestandar,
                    lab_examenes.idarea,lab_examenes.nombreexamen,descripcion,lab_areas.nombrerea,
                    lab_examenesxestablecimiento.idalantilla,lab_plantilla.plantilla,observacion,
                    lab_examenesxestablecimiento.ubicacion,mnt_formulariosxestablecimiento.idformulario,
                    mnt_formularios.nombreformulario,lab_examenesxestablecimiento.idestandarRep,
                    lab_examenesxestablecimiento.impresion,lab_examenesxestablecimiento.urgente,
                    lab_examenes.idsexo,ctl_sexo.nombre as nombresexo,lab_examenesxestablecimiento.condicion,
                    rangotiempoprev 
                    FROM lab_examenes 
                    INNER JOIN lab_areas ON lab_examenes.idarea=lab_areas.id
                    INNER JOIN lab_codigosestandar ON lab_examenes.idestandar=lab_codigosestandar.idestandar
                    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                    LEFT JOIN lab_plantilla  ON lab_plantilla.id=lab_examenesxestablecimiento.idplantilla
                    LEFT JOIN mnt_formulariosxestablecimiento ON lab_examenesxestablecimiento.idaormulario=mnt_formulariosxestablecimiento.idformulario
                    LEFT JOIN mnt_formularios ON mnt_formulariosxestablecimiento.idformulario=mnt_formularios.id
                    INNER JOIN mnt_sexo ON lab_examenes.idsexo= mnt_sexo.id
                    LEFT JOIN cit_programacionxexams ON lab_examenesxestablecimiento.idexamen=cit_programacionxexams.idexam
                    WHERE lab_examenesxestablecimiento.idestablecimiento=$lugar AND lab_examenes.id='$idexamen'";
          
          //echo $query;
     $result = pg_query($query);
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
     $result = pg_query($query);
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
     $query = "SELECT idexamen FROM lab_examenes WHERE idarea='$idarea' ORDER BY id DESC LIMIT 1";
     echo $query;
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
	   $row = pg_fetch_array($result);
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
                INNER JOIN lab_examenes ON lab_examenes.id=lab_examenesxestablecimiento.idexamen
                INNER JOIN lab_areasxestablecimiento ON lab_examenes.idarea=lab_areasxestablecimiento.idarea
                INNER JOIN lab_areas ON lab_areasxestablecimiento.idarea=lab_areas.id
                INNER JOIN lab_codigosestandar ON lab_examenes.idestandar= lab_codigosestandar.id
                LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.idformulario=mnt_formularios.id 
                LEFT JOIN ctl_sexo ON lab_examenes.idsexo= ctl_sexo.id
                WHERE lab_examenesxestablecimiento.idestablecimiento=$lugar 
                AND lab_areasxestablecimiento.condicion='H' AND lab_areasxestablecimiento.idestablecimiento=$lugar
                ORDER BY lab_examenes.idarea,lab_examenes.id"; 
          // echo $query;     
             
	 $numreg = pg_num_rows(pg_query($query));
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
	     $query = $query_search." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	  } 
function ObtenerCodigo($idarea){
     //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
     if($con->conectar()==true){
        $query ="SELECT idarea FROM ctl_area_servicio_diagnostico WHERE id=$idarea";
       // echo $query;
        $result = pg_fetch_array(pg_query($query));
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
                 INNER JOIN lab_examenesxestablecimiento ON lab_examenes.id = lab_examenesxestablecimiento.idexamen
                 WHERE lab_examenes.idexamen = '$idexamen'
                 AND idestablecimiento =$lugar";
              
       // echo $query;
	$result = pg_query($query);
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
		   $query = "SELECT lab_examenes.id,lab_examenes.idexamen,lab_codigosestandar.idestandar,lab_examenes.idarea,nombreexamen,descripcion, nombrearea,
                             lab_examenesxestablecimiento.condicion, (CASE WHEN lab_examenesxestablecimiento.condicion='H' THEN 'Habilitado' ELSE 'Inhabilitado' end )AS Cond, 
                             (CASE WHEN lab_examenesxestablecimiento.ubicacion=0 THEN 'Todas las Procedencias' 
                             WHEN lab_examenesxestablecimiento.ubicacion=1 THEN 'Hospitalización y Emergencia' 
                             WHEN lab_examenesxestablecimiento.ubicacion=4 THEN 'Laboratorio' END ) AS Ubicacion, 
                             lab_plantilla.idplantilla,mnt_formularios.nombreformulario, idestandarrep,
                             (SELECT idestandar FROM lab_codigosestandar where lab_examenesxestablecimiento.idestandarrep=lab_codigosestandar.id) AS estandarrep,
                             lab_examenesxestablecimiento.impresion,urgente, ctl_sexo.nombre AS nombresexo
                             FROM lab_examenesxestablecimiento 
                             INNER JOIN lab_examenes ON lab_examenes.id=lab_examenesxestablecimiento.idexamen 
                             INNER JOIN lab_areasxestablecimiento ON lab_examenes.idarea=lab_areasxestablecimiento.idarea 
                             INNER JOIN lab_areas ON lab_areasxestablecimiento.idarea=lab_areas.id 
                             INNER JOIN lab_codigosestandar ON lab_examenes.idestandar = lab_codigosestandar.id 
                             LEFT JOIN mnt_formularios ON lab_examenesxestablecimiento.idformulario=mnt_formularios.id 
                             LEFT JOIN ctl_sexo ON lab_examenes.idsexo= ctl_sexo.id 
                             INNER JOIN lab_plantilla ON lab_examenesxestablecimiento.idplantilla=lab_plantilla.id
                             WHERE lab_examenesxestablecimiento.idestablecimiento=$lugar 
                             AND lab_areasxestablecimiento.condicion='H' AND lab_areasxestablecimiento.idestablecimiento=$lugar
                             ORDER BY lab_examenes.idarea,lab_examenes.id  
                             LIMIT $RegistrosAMostrar OFFSET $RegistrosAEmpezar";
                      echo $query; 
                            $result = pg_query($query);
                             
                            if (!$result)
                                return false;
                            else
                                return $result;
                     // echo $query;      
	   }
  } 
  
  function EstadoCuenta($idexamen,$cond,$lugar){ 
    $con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
        if($con->conectar()==true){
            if($cond=='H'){
                $query = "UPDATE lab_examenesxestablecimiento SET condicion='I' WHERE idexamen='$idexamen' AND idestablecimiento=$lugar";
                $result = pg_query($query);
              //  $query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
               // $result1 = pg_query($query1);
             }
             if($cond=='I'){
                $query = "UPDATE lab_examenesxestablecimiento SET condicion='H' WHERE idexamen='$idexamen' AND idestablecimiento=$lugar";
                $result = pg_query($query);
               // $query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
                //$result1 = pg_query($query1);
             }
        }
        $con->desconectar();
    }
	
 function obtener_letra($idarea){
	$con = new ConexionBD;
    if($con->conectar()==true) 
	{
		$query = "SELECT ORD(impresion)
		FROM lab_examenesxestablecimiento 
		WHERE idexamen ilike'$idarea%' 
		AND impresion<>'G' ORDER BY Impresion DESC LIMIT 1";
		$result = pg_query($query);
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
			$result = pg_query($query);
					$query1= "UPDATE lab_examenes SET Habilitado='N' WHERE IdExamen='$idexamen'" ;
			$result1 = pg_query($query1);
			 }
			 if($cond=='I'){
				$query = "UPDATE lab_examenesxestablecimiento SET Condicion='H' WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar";
				$result = pg_query($query);
				$query1= "UPDATE lab_examenes SET Habilitado='S' WHERE IdExamen='$idexamen'";
				//$result1 = pg_query($query1);

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
		 $result = pg_query($query);
		 
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
		 $result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return true;
	  }
	}

}

?>
