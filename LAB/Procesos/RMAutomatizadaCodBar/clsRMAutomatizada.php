<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsRMAutomatizada
{
 //constructor	
 function clsRMAutomatizada(){
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
	 $query = "SELECT B.IdSolicitudEstudio AS IdSolicitudEstudio,NumeroMuestra,C.IdEmpleado AS IdMedico,
		   NombreEmpleado AS NombreMedico, NombreSubServicio AS Origen,
		   NombreServicio AS Precedencia, D.IdNumeroExp, 
		   CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
                   CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
		   IF(Sexo=1,'Masculino','Femenino') AS Sexo,
		   B.FechaSolicitud,DATE_FORMAT(E.FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento,
                   sec_examenfisico.Peso,sec_examenfisico.Talla,Diagnostico,ConocidoPor 
                   FROM sec_historial_clinico AS A
		   INNER JOIN sec_solicitudestudios AS B ON A.IdHistorialClinico= B.IdHistorialClinico
		   INNER JOIN mnt_empleados  		AS C ON A.IDEmpleado= C.IdEmpleado
		   INNER JOIN mnt_expediente 		AS D ON A.IdNumeroExp= D.IdNumeroExp
		   INNER JOIN mnt_datospaciente	AS E on D.IdPaciente=E.IdPaciente  
		   INNER JOIN mnt_subservicio 		AS F ON F.IdSubServicio= A.IDSubServicio
		   INNER JOIN mnt_servicio 		AS G ON G.IdServicio= F.IdServicio
		   INNER JOIN lab_recepcionmuestra AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
                   LEFT JOIN sec_diagnosticospaciente ON A.IdHistorialClinico=sec_diagnosticospaciente.IdHistorialClinico
                   LEFT JOIN mnt_cie10 ON sec_diagnosticospaciente.IdDiagnostico1=mnt_cie10.IdCie10
                   LEFT JOIN sec_examenfisico ON A.IdHistorialClinico=sec_examenfisico.IdHistorialClinico
		   WHERE B.IdServicio ='DCOLAB' AND A.IdNumeroExp='$idexpediente' AND B.IdSolicitudEstudio=$idsolicitud";
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
	   $query = "SELECT B.IdExamen,NombreExamen,TipoMuestra,Indicacion 
				FROM sec_detallesolicitudestudios A
				INNER JOIN lab_examenes     AS B ON A.IdExamen=B.IdExamen
				INNER JOIN lab_tipomuestra  AS C ON C.IdTipoMuestra=A.IdTipoMuestra
				WHERE idSolicitudEstudio = $idsolicitud 
				AND IdArea='$idarea'";
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
	$query="SELECT B.IdExamen,NombreExamen,TipoMuestra,Indicacion,A.Observacion 
			FROM sec_detallesolicitudestudios AS A
			INNER JOIN lab_examenes       	  AS B ON A.IdExamen=B.IdExamen
			INNER JOIN lab_tipomuestra    	  AS C ON C.IdTipoMuestra=A.IdTipoMuestra
			WHERE idSolicitudEstudio = $idsolicitud 
			AND	IdArea='$idarea' AND B.IdExamen='$idexamen'";
	$result = @mysql_query($query);
	if (!$result)
	   return false;
	else
	   return $result;	   
   }
 }
 
//FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
/*function CambiarEstadoSolicitud1($idexpediente,$fechasolicitud,$estadosolicitud)

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
 }*/
 
 function CambiarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
		$query="SELECT COUNT(IdDetalleSolicitud) FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio=$idsolicitud AND (EstadoDetalle<>'RC' AND EstadoDetalle<>'RM')";
		$detalle=mysql_fetch_array(mysql_query($query));
		if($detalle[0] == 0){
			$query1="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud";
			$result=mysql_query($query1);		
			return true;	  
		}
		if ($detalle[0] >= 1){
			$query1="UPDATE sec_solicitudestudios SET estado='P' WHERE IdSolicitudEstudio=$idsolicitud";
			$result=mysql_query($query1);	
			return true;
		}
		
	   
  }
 }

 //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
function CambiarEstadoDetalle($idsolicitud,$estado,$idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query ="UPDATE sec_detallesolicitudestudios AS a,sec_solicitudestudios AS b
			SET a.EstadoDetalle='$estado',a.Observacion=''
			WHERE a.IdSolicitudEstudio='$idsolicitud' AND 
			b.IdServicio='DCOLAB' AND a.IdExamen LIKE '%$idarea%' ";
   $result = mysql_query($query);
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
    
   $query = "UPDATE sec_detallesolicitudestudios AS a,sec_solicitudestudios AS b
			 SET a.EstadoDetalle='$estado', a.Observacion=''
			 WHERE a.IdSolicitudEstudio=$idsolicitud 
			 AND b.IdServicio='DCOLAB' AND a.IdExamen ='$idexamen'";
   
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
  
   //RECUPERAR EXAMENES POR AREA
	function ExamenesPorArea($idarea)
	 {
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	     $query = "SELECT IdExamen,NombreExamen FROM lab_examenes WHERE IdArea='$idarea'
			 AND Habilitado='S' ORDER BY NombreExamen ASC";
		 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
 
}//CLASE
?>
