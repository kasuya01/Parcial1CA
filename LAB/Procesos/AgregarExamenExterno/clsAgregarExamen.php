<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsAgregarExamen
{
	 //constructor	
function clsAgregarExamen()
{
}	
	 
function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT Nombre FROM mnt_establecimiento WHERE IdEstablecimiento=$lugar";
	 $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento 
                            ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";
		$resul = mysql_query($conNom) or die('La consulta fall&oacute;: ' . mysql_error());
	}
 return $resul;
}

function DatosArea($area){
	$con = new ConexionBD;
	if($con->conectar()==true){			  
		$NomAre  = "select NombreArea,Administrativa from lab_areas where IdArea='$area'";
		$resul = mysql_query($NomAre) or die('La consulta fall&oacute;: ' . mysql_error());
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' 
		ORDER BY Nombre";		
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
			FROM mnt_subservicio 
			INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
			WHERE mnt_subservicio.IdServicio='$IdServ' AND IdEstablecimiento=$lugar 
			ORDER BY NombreSubServicio";		
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}
/*Funcion que llena el combo de tipos de muestra por examen*/
function LlenarCmbTipoMuestra($IdExamen){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT lab_tipomuestra.IdTipoMuestra,lab_tipomuestra.TipoMuestra
			   FROM lab_tipomuestraporexamen 
			   INNER JOIN lab_tipomuestra ON lab_tipomuestraporexamen.IdTipoMuestra=lab_tipomuestra.IdTipoMuestra
			   WHERE IdExamen='$IdExamen'";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;

}
/*Funcion que llena el combo de origenes de la muestra*/
function LlenarCmbOrigenMuestra($IdTipo){
         
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_origenmuestra.IdOrigenMuestra,mnt_origenmuestra.OrigenMuestra
			   FROM mnt_origenmuestra 
			   WHERE mnt_origenmuestra.IdTipoMuestra=$IdTipo";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	//echo $sqlText;
	return $dt;

}


function BuscarExamen($idsolicitud,$idexamen,$lugar){
	$con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
	   $query = "SELECT count(*) FROM sec_detallesolicitudestudios 
                     WHERE IdExamen='$idexamen' AND IdSolicitudEstudio=$idsolicitud";
	   /*"SELECT count(*) FROM sec_detallesolicitudestudios 
				 WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar AND IdSolicitudEstudio=$idsolicitud";*/
			 
		$result = mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	}
 } 

function insertar_Examen($idsolicitud,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query ="INSERT INTO sec_detallesolicitudestudios(IdSolicitudEstudio,IdExamen,Indicacion,EstadoDetalle,IdTipoMuestra,
             Observacion,IdEstablecimiento,IdEmpleado,IdUsuarioReg,FechaHoraReg) 
             VALUES($idsolicitud,'$IdExamen','$indicacion','PM',$IdTipo,'$Observa',$lugar,'$Empleado',$usuario,NOW())";

    $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

function ObtenerCodigoTecnico($usuario){

$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = "select IdEmpleado from mnt_empleados where IdTipoEmpleado='LAB' AND Correlativo=$usuario";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	}
}


function ExamenesPorArea($idarea,$lugar)
{
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = "SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
		       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		       WHERE IdEstablecimiento = $lugar AND IdArea='$idarea'
		       AND lab_examenesxestablecimiento.Condicion='H' ORDER BY NombreExamen ASC ";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	}
}


	 //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO
	  function LeerEspecialidades()
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query= "SELECT IdSubServicio,NombreSubServicio FROM mnt_subservicio
	             WHERE IdServicio='CONEXT' ORDER BY NombreSubServicio";
	 
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	   }
	 }
 
 	 //FUNCION PARA MOSTRAR DATOS DE BUSQUEDA
	  function BuscarSolicitudesPaciente($query_search)
	 {
	   
  //creamos el objeto $con a partir de la clase ConexionBD
	   $con = new ConexionBD;
	   //usamos el metodo conectar para realizar la conexion
	   if($con->conectar()==true){
	     $query = $query_search;
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
		   
	   }
	 }
	
	 function BuscarRecepcion($Idsolic){
	  $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query ="SELECT DATE_FORMAT(FechaRecepcion,'%e/ %m / %Y')AS fecha 
                    FROM lab_recepcionmuestra 
                    WHERE IdSolicitudEstudio=$Idsolic";
	      $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	
	 }
	 }
	 
	  function  BuscarCita($Idsolic){
	  $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query ="SELECT DATE_FORMAT(Fecha,'%e/ %m / %Y')AS fecha 
                    FROM cit_citasxserviciodeapoyo 
                    WHERE IdSolicitudEstudio=$Idsolic";
	      $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	
	 }
	 }
	
	 //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
 //DATOS GENERALES DE LA SOLICITUD
function DatosGeneralesSolicitud($idexpediente,$idsolicitud,$lugar)
{  
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	$query = "SELECT DISTINCT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,NombreSubServicio AS Origen,
		  DatosClinicos,NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
                  CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		  sec_solicitudestudios .FechaSolicitud,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
                  IF (Sexo=1,'Masculino','Femenino') AS Sexo, sec_solicitudestudios .estado AS Estado,mnt_establecimiento.Nombre,
		  DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento,sec_historial_clinico.IdEstablecimiento
		  FROM sec_historial_clinico 
		  INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		  INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
		  INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		  INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
		  INNER JOIN mnt_subservicio  ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		  INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		  INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		  WHERE sec_solicitudestudios.IdServicio ='DCOLAB'
                  AND sec_solicitudestudios.IdEstablecimiento=$lugar 
                  AND sec_historial_clinico.IdNumeroExp='$idexpediente'
		  AND sec_solicitudestudios .IdSolicitudEstudio=$idsolicitud";
	 $result = @mysql_query($query);
        if (!$result)
          return false;
        else
          return $result;	   
           }
   }
  //DATOS DEL DETALLE DE LA SOLICITUD
  function DatosDetalleSolicitud($idexpediente,$idsolicitud,$lugar)
  {
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	   $query = "SELECT sec_solicitudestudios.IdNumeroExp, lab_examenes.IdArea AS IdArea,lab_examenes.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud,
	   CASE sec_detallesolicitudestudios.EstadoDetalle 
            WHEN 'D'  THEN 'Digitado'
            WHEN 'PM' THEN 'Muestra Procesada'
            WHEN 'RM' THEN 'Muestra Rechazada'    
            WHEN 'RC' THEN 'Resultado Completo' END AS Estado
            FROM sec_detallesolicitudestudios 
INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
INNER JOIN lab_examenes ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea
WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND lab_examenesxestablecimiento.Condicion='H' 
AND lab_areasxestablecimiento.Condicion='H' AND sec_solicitudestudios.IdNumeroExp='$idexpediente' 
AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud AND sec_solicitudestudios.IdEstablecimiento=$lugar
ORDER BY lab_examenes.IdArea";
	  	$result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }

 function ConsultarAreas(){
	$con = new ConexionBD;
	if($con->conectar()==true) 
	{
		$query ="SELECT lab_areas.IdArea, lab_areas.NombreArea
			 FROM lab_areas 
			 INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			 WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areas.Administrativa='N'";
		$result = @mysql_query($query);
	   if (!$result)
	      return false;
	   else
	      return $result;
		}
 
 }
 

  function VinetasRecepcion($idexpediente,$idsolicitud)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query ="SELECT lab_areas.NombreArea,lab_tipomuestra.TipoMuestra,mnt_expediente.IdNumeroExp,
CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
lab_recepcionmuestra.NumeroMuestra,lab_examenes.NombreExamen,lab_examenes.Impresion,lab_areas.IdArea,lab_examenes.IdExamen,
lab_codigosestandar.IdEstandar,sec_historial_clinico.IdEstablecimiento,lab_tipomuestra.TipoMuestra
FROM lab_areas
INNER JOIN lab_examenes
ON lab_examenes.IdArea=lab_areas.IdArea
INNER JOIN sec_detallesolicitudestudios
ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
INNER JOIN lab_tipomuestra
ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
INNER JOIN sec_solicitudestudios
ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
INNER JOIN mnt_expediente
ON sec_solicitudestudios.IdNumeroExp=mnt_expediente.IdNumeroExp
INNER JOIN mnt_datospaciente
ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
INNER JOIN lab_recepcionmuestra
ON lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
INNER JOIN cit_citasxserviciodeapoyo
ON cit_citasxserviciodeapoyo.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud
GROUP BY LEFT(lab_examenes.IdExamen,3), lab_tipomuestra.TipoMuestra,lab_examenes.Impresion"; 
 
   $result = @mysql_query($query);
   if (!$result)
      return false;
   else
      return $result;	   
   }
 } 
  function EstadoSolicitud($idexpediente,$idsolicitud){
  
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query ="SELECT Estado FROM sec_solicitudestudios WHERE IdNumeroExp='$idexpediente' AND IdSolicitudEstudio=$idsolicitud";
  $result = @mysql_query($query);
   if (!$result)
      return false;
   else
      return $result;	   
   }
  }
  
  function consultarpag($query_search,$RegistrosAEmpezar,$RegistrosAMostrar)
 {
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
   $query = $query_search ." LIMIT $RegistrosAEmpezar, $RegistrosAMostrar";
     $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else
	   return $result;
   }
  } 
  
  function NumeroDeRegistros($query){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query = $query;
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
}//CLASE
?>
