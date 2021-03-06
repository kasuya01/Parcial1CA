<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsEliminarResultado
{
	 //constructor	
	 function clsEliminarResultado()
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
			$conNom  = "SELECT 	mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
					FROM mnt_establecimiento 
					INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
					WHERE IdEstablecimiento=$lugar";
			$resul = mysql_query($conNom) or die('La consulta fall&oacute;: ' . mysql_error());
		}
	 return $resul;
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
	   $query ="SELECT DATE_FORMAT(FechaRecepcion,'%e/ %m / %Y')AS fecha FROM lab_recepcionmuestra WHERE IdSolicitudEstudio=$Idsolic";
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
	   $query ="SELECT DATE_FORMAT(Fecha,'%e/ %m / %Y')AS fecha FROM cit_citasxserviciodeapoyo WHERE IdSolicitudEstudio=$Idsolic";
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
	 $query = "SELECT DISTINCT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico, NombreSubServicio AS Origen,DatosClinicos,
			NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
              CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		sec_solicitudestudios.FechaSolicitud,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
		 IF (Sexo=1,'Masculino','Femenino') AS Sexo, sec_solicitudestudios.estado AS Estado,DATE_FORMAT(FechaRecepcion,'%e/ %m / %Y')AS FechaRecepcion,mnt_establecimiento.Nombre
		FROM sec_historial_clinico 
		INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
		INNER JOIN mnt_expediente  ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente  ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
		INNER JOIN mnt_subservicio  ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN lab_recepcionmuestra  ON sec_solicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio 
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_solicitudestudios.IdEstablecimiento=$lugar AND sec_historial_clinico.IdNumeroExp='$idexpediente'
		AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud";
		$result = @mysql_query($query);
     if (!$result)
      return false;
     else
       return $result;	   
	}
 }
  //DATOS DEL DETALLE DE LA SOLICITUD
  function DatosDetalleSolicitud($idexpediente,$idsolicitud,$idarea)
  {
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	   $query = "SELECT sec_solicitudestudios.IdNumeroExp, lab_examenes.IdArea AS IdArea,
				lab_examenes.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud,
				sec_solicitudestudios.IdSolicitudEstudio,sec_detallesolicitudestudios.IdDetalleSolicitud,
				lab_examenesxestablecimiento.IdPlantilla,
				CASE sec_detallesolicitudestudios.EstadoDetalle 
					WHEN 'D'  THEN 'Digitado'
					WHEN 'PM' THEN 'Muestra Procesada'
					WHEN 'RM' THEN 'Muestra Rechazada'    
					WHEN 'RC' THEN 'Resultado Completo' END AS Estado
				FROM sec_detallesolicitudestudios 
				INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
				INNER JOIN lab_examenes ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
				INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
				WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND EstadoDetalle='RC' AND sec_solicitudestudios.IdNumeroExp='$idexpediente'
				AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud AND lab_examenes.IdArea='$idarea'
				ORDER BY lab_examenes.IdArea";
          // echo $query;
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
lab_recepcionmuestra.NumeroMuestra,lab_examenes.NombreExamen,lab_examenes.Impresion
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
WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud
GROUP BY LEFT(lab_examenes.IdExamen,3), lab_tipomuestra.TipoMuestra,lab_examenes.Impresion
"; 
 
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
  /*Para verificar si existe el resultado*/
   function VerificaDetalle($idsolicitud,$iddetalle){
  
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query ="select * from lab_resultados where IdSolicitudEstudio=$idsolicitud and IdDetalleSolicitud=$iddetalle";
  $result = @mysql_query($query);
   if (!$result)
      return -1;
   else
      return 1;	   
   }
  }
  
  
  function EliminarResultado($idsolicitud,$iddetalle){
     $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="delete from lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle";
	 $result = @mysql_query($query);
	 if (!$result)
	   return -1;
	 else 
		return 1;
  }
 } 
  
  
    function ObtenerIdDetalleResultado($idresultado){
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="SELECT * FROM lab_detalleresultado where IdResultado=$idresultado";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
   }
   
 }
 
 
   function ObtenerIdResultado($idsolicitud,$iddetalle){
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="select IdResultado,Resultado from lab_resultados where IdSolicitudEstudio=$idsolicitud and IdDetalleSolicitud=$iddetalle";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
   }
  }
  
 function ObtenerIdDetalleRes($idresultado){
 $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="select IdDetalleResultado from lab_detalleresultado where IdResultado=$idresultado";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
 
 }
 }
 
  function ObtenerTipoResultado($idresultado){
	$con = new ConexionBD;
	if($con->conectar()==true)
	{
     $query ="select Resultado from lab_resultados where IdResultado=$idresultado";
	 $result = @mysql_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
  }
  }
  
  
  function EliminarDetalleResultado($idresultado){
     $con = new ConexionBD;
	if($con->conectar()==true)
	{
		$query ="delete from lab_detalleresultado where IdResultado=$idresultado";
		$result = @mysql_query($query);
		if (!$result)
			return -1;
		else 
			return 1;
  }
  }
 
 
 function EliminarResultadoTarjeta($iddetalleres){
  $con = new ConexionBD;
	if($con->conectar()==true)
	{
		$query ="delete  from lab_resultadosportarjeta where IdDetalleResultado=$iddetalleres";
		$result = @mysql_query($query);
		if (!$result)
			return -1;
		else 
			return 1;
  }
  }
 
 /*Funcion para Actualizar el estado del detalla de la solicitud */
 function ActualizarEstadoDetalle($iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) //Estado PM--> Muestra en Proceso
   {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle='PM' WHERE IdDetalleSolicitud=$iddetalle";	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
 
/*Actualiza el estado de la solicitud*/
 function ActualizarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT  IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios 
			WHERE IdSolicitudEstudio=$idsolicitud and EstadoDetalle <>'RC' and EstadoDetalle <>'RM'";
	$detalle=mysql_num_rows(mysql_query($query));
	if(!empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado='P' WHERE IdSolicitudEstudio=$idsolicitud";
		$result=@mysql_query($query);		
		return true;	  
    }else
		$query="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud";
		$result=@mysql_query($query);
		return false;
   }
   }
 
 
 }//CLASE
?>
