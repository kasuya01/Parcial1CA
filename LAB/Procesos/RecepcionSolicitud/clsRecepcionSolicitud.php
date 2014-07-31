<?php 
include("../../../Conexion/ConexionBD.php");
//include("../../../Conexion/ConexionBDLab.php");
//implementamos la clase lab_areas
class clsRecepcionSolicitud
{
 //constructor	
 function clsRecepcionSolicitud(){ //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD 

 }	

function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";
		$resul = pg_query($conNom) or die('La consulta fall&oacute;: ' . mysql_error());
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

function LlenarEstablecimiento($Idtipo)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento where IdTipoEstablecimiento='$Idtipo'
ORDER BY Nombre";

		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
 }
 
function BuscarSolicitudes($idexpediente,$fechacita,$lugar,$idEstablecimiento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
  	
		$query = "SELECT sec_solicitudestudios.IdSolicitudEstudio FROM sec_solicitudestudios
			  INNER JOIN cit_citasxserviciodeapoyo 
			  ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
                          INNER JOIN sec_historial_clinico 
			  ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
			  WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.Estado='D' AND		
			  cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar 
                          AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento";
                //echo $query;
               
		$result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }

function NumeroDeRegistros($idexpediente,$fechacita,$lugar,$idEstablecimiento){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query ="SELECT sec_solicitudestudios.IdSolicitudEstudio FROM sec_solicitudestudios
	INNER JOIN cit_citasxserviciodeapoyo 
	ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
	INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
	WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.Estado='D' AND 	
	cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar 
            AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento";
	 $numreg = mysql_num_rows(mysql_query($query));
       // echo $numreg;
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }

 function NumeroDeDetalle($idexpediente,$fechacita,$lugar,$idEstablecimiento){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
       $query ="SELECT sec_solicitudestudios.IdNumeroExp, lab_examenes.IdArea AS IdArea ,
		lab_examenes.IdExamen AS IdExamen,
		NombreExamen,Indicacion,FechaSolicitud,sec_historial_clinico.IdSubServicio,
		sec_solicitudestudios.IdsolicitudEstudio
		FROM sec_detallesolicitudestudios 
		INNER JOIN sec_solicitudestudios  ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		INNER JOIN lab_examenes  ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
		INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		INNER JOIN cit_citasxserviciodeapoyo ON cit_citasxserviciodeapoyo .IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_solicitudestudios.IdNumeroExp='$idexpediente' 
		AND cit_citasxserviciodeapoyo .fecha='$fechacita' AND sec_detallesolicitudestudios.EstadoDetalle='D' 
		AND sec_solicitudestudios.IdEstablecimiento=$lugar AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento
		ORDER BY lab_examenes.IdArea";
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
 }


 function DatosGenerales($idexpediente,$fechacita,$lugar)
 {
   	$con = new ConexionBD;
  	 if($con->conectar()==true) 
   	{
  	
		$query = "SELECT DISTINCT mnt_establecimiento.Nombre, mnt_empleados.IdEmpleado AS IdMedico, NombreEmpleado AS NombreMedico,
		NombreSubServicio AS Origen,NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		CURDATE() as Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
		IF(Sexo=1,'Masculino','Femenino') AS Sexo
		FROM sec_historial_clinico
		INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
		INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
		INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'
		AND sec_solicitudestudios.Estado='D' AND cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar";

		$result = @mysql_query($query);
     		if (!$result)
     		  	return false;
    		else
       			return $result;	   
 	}
 }
 
 function BuscarDatosSolicitudes($idexpediente,$fechacita,$IdSolicitud,$lugar)
{
	$con = new ConexionBD;
   	if($con->conectar()==true) 
  	{	
  	
		$query = "SELECT DISTINCT mnt_empleados.IdEmpleado AS IdMedico,NombreEmpleado AS NombreMedico,
                        NombreSubServicio AS Origen,sec_solicitudestudios.IdSolicitudEstudio,
                        NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
                        CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) as NombrePaciente,CURDATE() as Fecha,
                        (YEAR(CURRENT_DATE)-YEAR(FechaNacimiento))AS Edad,
                        IF(Sexo=1,'Masculino','Femenino') AS Sexo, mnt_subservicio.IdSubServicio,sec_examenfisico.Peso, 
                        sec_examenfisico.Talla,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento,
                        sec_historial_clinico.IdEstablecimiento,lab_tiposolicitud.TipoSolicitud,sec_examenfisico.Peso, 
                        sec_examenfisico.Talla,IdDiagnostico1,Diagnostico,ConocidoPor
                        FROM sec_historial_clinico 
                        INNER JOIN sec_solicitudestudios ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
                        LEFT JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
                        INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
                        LEFT JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
                        INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
                        INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
                        INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
                        LEFT JOIN sec_examenfisico ON sec_historial_clinico.IdHistorialClinico=sec_examenfisico.IdHistorialClinico
                        LEFT JOIN sec_diagnosticospaciente ON sec_historial_clinico.IdHistorialClinico=sec_diagnosticospaciente.IdHistorialClinico
                        LEFT JOIN mnt_cie10 ON sec_diagnosticospaciente.IdDiagnostico1=mnt_cie10.IdCie10
                        INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
                        INNER JOIN lab_tiposolicitud ON sec_solicitudestudios.IdTipoSolicitud=lab_tiposolicitud.IdTipoSolicitud
                        WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'
                        AND sec_solicitudestudios.Estado='D' AND sec_solicitudestudios.IdSolicitudEstudio='$IdSolicitud'
                        AND cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar";
              //echo $query;
		$result = @mysql_query($query);
     		if (!$result)
       			return false;
    		else
       			return $result;	   
   	}
 }


 //FUNCION PARA MOSTRAR DETALLE DE LA SOLICITUD 
function DetalleSolicitud($idexpediente,$fechacita)
{
	$con = new ConexionBD;
   	if($con->conectar()==true) 
   	{
  		$query="SELECT C.IdNumeroExp, B.IdArea AS IdArea ,B.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud
			FROM sec_detallesolicitudestudios AS A
			INNER JOIN sec_solicitudestudios AS C ON A.IdSolicitudEstudio=C.IdSolicitudEstudio
			INNER JOIN lab_examenes AS B ON A.idExamen=B.IdExamen
			INNER JOIN cit_citasxserviciodeapoyo  AS D ON D.IdSolicitudEstudio=C.IdSolicitudEstudio
			WHERE C.IdServicio ='DCOLAB' AND C.IdNumeroExp='$idexpediente' AND D.fecha='$fechacita' 
			ORDER BY B.IdArea";
     		$result = @mysql_query($query);
     		if (!$result)
       			return false;
     		else
       			return $result;	   
   	}	
 }
 
 
 //FUNCION PARA IMPRIMIR VI�ETAS EN LA RECEPCION DE SOLICITUDES (UNICAMENTE CUANDO SE RECIBEN MUESTRAS DE ORINA O DE HECES)
   function VinetasRecepcion($idexpediente,$fechacita,$idsolicitud,$IdEstablecimiento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query ="SELECT lab_areas.NombreArea,lab_tipomuestra.TipoMuestra,mnt_expediente.IdNumeroExp,
CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido) AS NombrePaciente,
lab_recepcionmuestra.NumeroMuestra,lab_examenes.NombreExamen,lab_examenesxestablecimiento.Impresion,
lab_areas.IdArea,lab_examenes.IdExamen,
lab_codigosestandar.IdEstandar,sec_historial_clinico.IdEstablecimiento,lab_tipomuestra.TipoMuestra
FROM lab_areas
INNER JOIN lab_examenes ON lab_examenes.IdArea=lab_areas.IdArea
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
INNER JOIN sec_detallesolicitudestudios ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
INNER JOIN lab_tipomuestra ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
INNER JOIN sec_solicitudestudios ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
INNER JOIN mnt_expediente ON sec_solicitudestudios.IdNumeroExp=mnt_expediente.IdNumeroExp
INNER JOIN mnt_datospaciente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
INNER JOIN lab_recepcionmuestra ON lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
INNER JOIN cit_citasxserviciodeapoyo ON cit_citasxserviciodeapoyo.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
INNER JOIN lab_codigosestandar ON lab_examenes.IdEstandar=lab_codigosestandar.IdEstandar
INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND cit_citasxserviciodeapoyo.Fecha='$fechacita' 
AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud AND sec_detallesolicitudestudios.IdEstablecimientoExterno=$IdEstablecimiento
GROUP BY LEFT(lab_examenes.IdExamen,3), lab_tipomuestra.TipoMuestra,lab_examenesxestablecimiento.Impresion"; 
   
//echo $query;
 
 $result = @mysql_query($query);
   if (!$result)
      return false;
   else
      return $result;	   
   }
 }
 
  
 
 //FUNCION PARA MOSTRAR DETALLE DE LA SOLICITUD CUANDO HA SIDO PROCESADA
 //SIRVE TAMBIEN PARA IMPRIMIR LAS VI�ETAS DE LOS EXAMENES DEL DETALLE DE LA SOLICITUD EN EL MOMENTO DE LA TOMA DE MUESTRA
  function Detalle($idexpediente,$fechasolicitud)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query = "SELECT C.IdNumeroExp, B.IdArea AS IdArea ,B.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud
			 FROM sec_detallesolicitudestudios AS A
			 INNER JOIN sec_solicitudestudios AS C ON A.IdSolicitudEstudio=C.IdSolicitudEstudio
			 INNER JOIN lab_examenes B ON A.idExamen=B.IdExamen
			 WHERE IdServicio ='DCOLAB' AND C.IdNumeroExp='$idexpediente' 
			 AND FechaSolicitud='$fechasolicitud' 
			 ORDER BY B.IdArea";
   
  
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }

  //FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
function CambiarEstadoSolicitud($idexpediente,$fechacita,$estado)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query = "UPDATE sec_solicitudestudios SET estado='$estado'
			 WHERE IdNumeroExp='$idexpediente' AND
			 FechaSolicitud='$fechacita' AND 
			 IdServicio='DCOLAB'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

//FUNCION PARA ENCUENTRA DATOS REGISTRADOS PARA LOS PARAMETROS RECIBIDOS
function ResultadoDeBusqueda($idexpediente,$fechasolicitud,$estado)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "SELECT idSolicitudEstudio  	AS numreg
		FROM sec_historial_clinico       AS A
		INNER JOIN sec_solicitudestudios AS B ON A.IdHistorialClinico= B.IdHistorialClinico
		INNER JOIN mnt_empleados 	AS C ON A.IDEmpleado= C.IdEmpleado
		INNER JOIN mnt_expediente        AS D ON A.IdNumeroExp= D.IdNumeroExp
		INNER JOIN mnt_datospaciente 	AS E ON D.IdPaciente=E.IdPaciente  
		INNER JOIN mnt_subservicio 	AS F ON F.IdSubServicio= A.IDSubServicio
		INNER JOIN mnt_servicio 	AS G ON G.IdServicio= F.IdServicio
		WHERE B.IdServicio ='DCOLAB' AND A.IdNumeroExp='$idexpediente' AND
		FechaSolicitud='$fechasolicitud' AND B.Estado='$estado'";
    
	 $numreg = mysql_num_rows(mysql_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
 }
 
//FUNCION PARA VERIFICAR ESTADO DE LA SOLICITUD
function VerificarEstado($idexpediente,$fechacita)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {
  
	$query="SELECT CASE estado 
			WHEN 'D' THEN 'Digitada'
			WHEN 'R' THEN 'Recibida'
			WHEN 'P' THEN 'En Proceso'
			WHEN 'C' THEN 'Resultado de Estudios Completo'
			END AS estado 
			FROM sec_solicitudestudios AS A
			INNER JOIN cit_citasxserviciodeapoyo B ON A.IdSolicitudEstudio=B.IdSolicitudEstudio
			WHERE IdNumeroExp='$idexpediente' AND fecha='$fechacita' 
			AND A.IdServicio='DCOLAB";
    $result = @mysql_query($query);
	
     if (!$result)
       {return false;}
     else
		{
			$row= mysql_fetch_array($result);
			//return $result;	
            return $row[0];				
		}	   
   }
 }

function NumeroMuestra(){
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {
	$query ="SELECT MAX(NumeroMuestra) + 1 AS NumeroMuestra FROM lab_recepcionmuestra 
	         WHERE fecharecepcion=CURRENT_DATE" ;
	$result = @mysql_query($query);
     if (!$result)
       return false;
     else{
	    $row = mysql_fetch_array($result);
    	return $row['NumeroMuestra'];;	   
	   }
   }
  }


 

 
 function BuscarDatosSolicitudesPiloto($idexpediente,$fechacita,$IdSolicitud,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
  	$query = "SELECT DISTINCT mnt_empleados.IdEmpleado as IdMedico,NombreEmpleado as NombreMedico,
	NombreSubServicio AS Origen,sec_solicitudestudios.IdSolicitudEstudio,
	NombreServicio AS Precedencia, mnt_expediente.IdNumeroExp, 
	CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
	IF(Sexo=1,'Masculino','Femenino') AS Sexo,mnt_subservicio.IdSubServicio,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento
	FROM sec_historial_clinico 
	INNER JOIN sec_solicitudestudios ON sec_historial_clinico .IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
	INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp=mnt_expediente.IdNumeroExp
	INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
	INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
	INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
	INNER JOIN cit_citasxserviciodeapoyo ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
	INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_historial_clinico.IdNumeroExp='$idexpediente'
	AND sec_solicitudestudios.Estado='D' AND sec_solicitudestudios.IdSolicitudEstudio=$IdSolicitud
	AND cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar";
//echo $query;
		$result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 
  function BuscarDetalleSolicitud($idexpediente,$fechacita,$IdSolicitud,$idEstablecimiento)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
  	
	$query = "SELECT sec_solicitudestudios.IdNumeroExp, lab_examenes.IdArea AS IdArea ,lab_examenes.IdExamen AS IdExamen, 					
		  NombreExamen,Indicacion,FechaSolicitud,sec_historial_clinico.IdSubServicio,sec_solicitudestudios.IdsolicitudEstudio,
                  lab_examenes.IdEstandar 
		  FROM sec_detallesolicitudestudios 
		  INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		  INNER JOIN lab_examenes ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
		  INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		  INNER JOIN cit_citasxserviciodeapoyo ON cit_citasxserviciodeapoyo.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		  WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND sec_solicitudestudios.IdNumeroExp='$idexpediente' 
		  AND cit_citasxserviciodeapoyo.fecha='$fechacita' 				
		  AND sec_solicitudestudios.IdSolicitudEstudio='$IdSolicitud' 
                      AND sec_detallesolicitudestudios.EstadoDetalle='D' 
		  AND sec_detallesolicitudestudios.IdEstablecimientoExterno=$idEstablecimiento 
                      ORDER BY lab_examenes.IdArea";
		//echo $query;
		$result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }
 
  function DatosSolicitud($idsolicitud){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
  if($con->conectar()==true) 
	{
		 $query ="SELECT DISTINCT C.IdEmpleado,C.NombreEmpleado,F.IdServicio,NombreServicio,F.IdSubServicio,NombreSubServicio,
		 D.IdNumeroExp,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,
		FechaNacimiento,IF(Sexo=1,'M','F') AS Sexo, H.NumeroMuestra
		FROM sec_historial_clinico AS A
		INNER JOIN sec_solicitudestudios  AS B ON A.IdHistorialClinico= B.IdHistorialClinico
		INNER JOIN mnt_empleados AS C ON A.IDEmpleado= C.IdEmpleado
		INNER JOIN mnt_expediente AS D ON A.IdNumeroExp= D.IdNumeroExp
		INNER JOIN mnt_datospaciente AS E ON D.IdPaciente=E.IdPaciente  
		INNER JOIN mnt_subservicio AS F ON F.IdSubServicio= A.IdSubServicio
		INNER JOIN mnt_servicio AS G ON G.IdServicio= F.IdServicio
		INNER JOIN lab_recepcionmuestra AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
		WHERE B.IdServicio ='DCOLAB' AND B.IdSolicitudEstudio='$Solicitud'";
		$result = @mysql_query($query);
		if (!$result )
			return false;
		else
			return $result;
  
       }
  }

function DatosArchivo($idsolicitud){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
 
	$query ="SELECT DISTINCT C.IdEmpleado, F.IdServicio, NombreSubServicio,
			NombreServicio, D.IdNumeroExp,PrimerNombre,SegundoNombre,PrimerApellido,SegundoApellido,
			FechaNacimiento,IF(Sexo=1,'M','F') AS Sexo, H.NumeroMuestra
			FROM sec_historial_clinico AS A
			INNER JOIN sec_solicitudestudios  AS B ON A.IdHistorialClinico= B.IdHistorialClinico
			INNER JOIN mnt_empleados AS C ON A.IDEmpleado= C.IdEmpleado
			INNER JOIN mnt_expediente AS D ON A.IdNumeroExp= D.IdNumeroExp
			INNER JOIN mnt_datospaciente AS E ON D.IdPaciente=E.IdPaciente  
			INNER JOIN mnt_subservicio AS F ON F.IdSubServicio= A.IdSubServicio
			INNER JOIN mnt_servicio AS G ON G.IdServicio= F.IdServicio
			INNER JOIN lab_recepcionmuestra AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
			WHERE B.IdServicio ='DCOLAB' AND B.IdSolicitudEstudio='$Solicitud'";
	  $result = @mysql_query($query);
	  	 if (!$result )
	   return false;
	 else
	   return $result;

  }
  }
  
  
  function Piloto($idexpediente,$fechacita,$lugar,$idEstablecimiento){
   //creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
   if($con->conectar()==true){
     $query ="SELECT sec_historial_clinico.Piloto 
              FROM sec_solicitudestudios
	      INNER JOIN cit_citasxserviciodeapoyo 
	      ON sec_solicitudestudios.IdSolicitudEstudio=cit_citasxserviciodeapoyo.IdSolicitudEstudio
	      INNER JOIN sec_historial_clinico 
	      ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
	      WHERE sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.Estado='D' AND		
	      cit_citasxserviciodeapoyo.Fecha='$fechacita' AND sec_solicitudestudios.IdEstablecimiento=$lugar 
                  AND sec_historial_clinico.IdEstablecimiento=$idEstablecimiento";
	//echo $query;
	   	$result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	
   }
  }
  
  
  
 // $FechaNac
// function cambiarFormatoFecha($FechaNac){ 
    // list($anio,$mes,$dia)=explode("-",$fecha); 
    // return $dia."".$mes."".$anio; 
// }  


  
  
}//CLASE
?>
