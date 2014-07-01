<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsMuestrasRechazadas
{
 //constructor	
 function clsMuestrasRechazadas(){
 }	
 
function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT 	mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
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

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
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

 //FUNCION PARA ACTUALIZAR OBSERVACION DEL EXAMEN QUE HA SIDO RECHAZADO EN UNA SOLICITUD
 function MarcarObservacionRechazado($idsolicitud,$idarea,$observacion)
 {
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query="UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE idsolicitudestudio=$idsolicitud AND IdExamen LIKE '%$idarea%'";
		  
		  		  
	 $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 function MarcarObservacionRechazado1($idsolicitud,$idexamen,$observacion)
 {
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query="UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
	      WHERE idsolicitudestudio=$idsolicitud AND IdExamen='$idexamen'";
		  
	 $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO
  function ListadoSolicitudesPorArea($query_search)
 {
 
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
  
 }
 
 //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
 //DATOS GENERALES DE LA SOLICITUD
function DatosGeneralesSolicitud($idexpediente,$idsolicitud)
{  
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	 $query = "SELECT sec_solicitudestudios.IdSolicitudEstudio AS IdSolicitudEstudio,NumeroMuestra,mnt_empleados.IdEmpleado AS IdMedico,
	NombreEmpleado AS NombreMedico, NombreSubServicio AS Origen,
	NombreServicio AS Precedencia,mnt_expediente.IdNumeroExp, 
	CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
	IF(Sexo=1,'Masculino','Femenino') AS Sexo,
	sec_solicitudestudios.FechaSolicitud,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento,
        sec_examenfisico.Peso,sec_examenfisico.Talla,Diagnostico,ConocidoPor       
	FROM sec_historial_clinico 
	INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_empleados         ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
	INNER JOIN mnt_expediente 	     ON sec_historial_clinico.IdNumeroExp= mnt_expediente .IdNumeroExp
	INNER JOIN mnt_datospaciente	 ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
	INNER JOIN mnt_subservicio 	ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IDSubServicio
	INNER JOIN mnt_servicio 		ON mnt_servicio.IdServicio=mnt_subservicio.IdServicio
	INNER JOIN lab_recepcionmuestra ON sec_solicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
        LEFT JOIN sec_diagnosticospaciente ON sec_historial_clinico.IdHistorialClinico=sec_diagnosticospaciente.IdHistorialClinico
        LEFT JOIN mnt_cie10 ON sec_diagnosticospaciente.IdDiagnostico1=mnt_cie10.IdCie10
        LEFT JOIN sec_examenfisico ON sec_historial_clinico.IdHistorialClinico=sec_examenfisico.IdHistorialClinico
	WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud";
	//echo $query;
         $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
	}
 }
  //DATOS DEL DETALLE DE LA SOLICITUD
  function DatosDetalleSolicitud($idarea,$idsolicitud)
  {
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	   $query = "SELECT lab_examenes.IdExamen,NombreExamen,TipoMuestra,Indicacion 
		     FROM sec_detallesolicitudestudios 
		     INNER JOIN lab_examenes     ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		     INNER JOIN lab_tipomuestra  ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
		     WHERE idSolicitudEstudio = $idsolicitud AND IdArea='$idarea' AND EstadoDetalle='RM'";
		$result = @mysql_query($query);
	    if (!$result)
	       return false;
	    else
	       return $result;	   
   }
 }

 function DatosExamen($idarea,$idsolicitud,$idexamen)
 {
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	$query="SELECT lab_examenes.IdExamen,NombreExamen,TipoMuestra,Indicacion,sec_detallesolicitudestudios.Observacion 
		FROM sec_detallesolicitudestudios 
		INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_tipomuestra ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
		WHERE idSolicitudEstudio = $idsolicitud 
		AND IdArea='$idarea' AND lab_examenes.IdExamen='$idexamen'";
			//echo $query;
	$result = @mysql_query($query);
	if (!$result)
	   return false;
	else
	   return $result;	   
   }
 }
 
//FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
function CambiarEstadoSolicitud($idexpediente,$fechasolicitud,$estadosolicitud)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query = "UPDATE sec_solicitudestudios SET estado='$estadosolicitud'
             WHERE IdNumeroExp='$idexpediente' AND
             FechaSolicitud='$fechasolicitud' AND IdServicio='DCOLAB' ";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
  //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
function CambiarEstadoDetalle($idsolicitud,$estado,$idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query = "UPDATE sec_detallesolicitudestudios ,sec_solicitudestudios 
	     SET sec_detallesolicitudestudios.EstadoDetalle='$estado',sec_detallesolicitudestudios.Observacion=' '
	     WHERE sec_detallesolicitudestudios.IdSolicitudEstudio=$idsolicitud AND 
	     sec_solicitudestudios.IdServicio='DCOLAB' AND sec_detallesolicitudestudios.IdExamen LIKE '%$idarea%' 
             AND sec_detallesolicitudestudios.EstadoDetalle='RM' ";

   $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
  //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
function CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    
   $query = "UPDATE sec_detallesolicitudestudios,sec_solicitudestudios
	     SET sec_detallesolicitudestudios.EstadoDetalle='$estado',  sec_detallesolicitudestudios.Observacion=''
	     WHERE sec_detallesolicitudestudios.IdSolicitudEstudio=$idsolicitud
	     AND sec_solicitudestudios.IdServicio='DCOLAB' AND  sec_detallesolicitudestudios.IdExamen ='$idexamen'";
   
   $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
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
