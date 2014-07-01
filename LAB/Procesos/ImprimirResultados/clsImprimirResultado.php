<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsImprimirResultado
{
	 //constructor	
	 function clsImprimirResultado()
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

//FUNCION PARA OBTENER EL CÓDIGO DEL ESTANDAR
function Obtener_Estandar($idexamen){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT IdEstandar FROM lab_examenes WHERE IdExamen='$idexamen'";
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
		 IF (Sexo=1,'Masculino','Femenino') AS Sexo, sec_solicitudestudios.estado AS Estado,
		 DATE_FORMAT(FechaRecepcion,'%d/%m/%Y %H:%i:%s')AS FechaRecepcion,mnt_establecimiento.Nombre,FechaNacimiento,Sexo
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
		//echo $query;
		$result = @mysql_query($query);
		
     if (!$result)
      return false;
     else
       return $result;	   
	}
 }

//FUNCION PARA MOSTRAR LOS DATOS Resultado Plantilla A  
 function MostrarResultadoGenerales($idsolicitud,$idexamen,$lugar)
 {
	$con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT lab_recepcionmuestra.IdSolicitudEstudio, mnt_expediente.IdNumeroExp, CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
	NombreSubServicio AS Origen,NombreServicio AS Procedencia,NombreArea,NumeroMuestra,
	DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,mnt_establecimiento.Nombre,
	DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento, mnt_empleados.NombreEmpleado,
	lab_resultados.Resultado,lab_resultados.Lectura,lab_resultados.Interpretacion, lab_resultados.Observacion,
	lab_resultados.Observacion
	FROM sec_detallesolicitudestudios 
	INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
	INNER JOIN lab_recepcionmuestra  ON  lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
	INNER JOIN sec_historial_clinico  ON  sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
	INNER JOIN mnt_expediente  ON mnt_expediente.IdNumeroExp= sec_historial_clinico.IdNumeroExp
	INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
	INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio=  sec_historial_clinico.IdSubServicio
	INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
	INNER JOIN lab_examenes  ON lab_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
	INNER JOIN lab_areas  ON  lab_areas.IdArea=lab_examenes.IdArea
        INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
	INNER JOIN mnt_empleados ON lab_resultados.Responsable=mnt_empleados.IdEmpleado
	WHERE sec_detallesolicitudestudios.IdExamen='$idexamen' AND lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud AND sec_solicitudestudios.IdEstablecimiento=$lugar AND mnt_expediente.IdEstablecimiento=$lugar";
  //echo $query;
	$result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }


 function MostrarResultadoGenerales1($idsolicitud,$idarea,$lugar)
 {
	$con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT lab_recepcionmuestra.IdSolicitudEstudio, mnt_expediente.IdNumeroExp, 
		CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
		(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
		TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
		NombreArea,NumeroMuestra,DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,sec_solicitudestudios.IdEstablecimiento, DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento,
		sec_historial_clinico.IdEstablecimiento,mnt_establecimiento.Nombre
		FROM sec_detallesolicitudestudios 
		INNER JOIN sec_solicitudestudios ON sec_solicitudestudios.IdSolicitudEstudio=sec_detallesolicitudestudios.IdSolicitudEstudio
		INNER JOIN lab_recepcionmuestra ON lab_recepcionmuestra.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		INNER JOIN sec_historial_clinico ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_expediente ON mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
		INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN lab_examenes ON lab_examenes.IdExamen=sec_detallesolicitudestudios.IdExamen
		INNER JOIN lab_areas ON lab_areas.IdArea=lab_examenes.IdArea
		INNER JOIN mnt_establecimiento ON sec_solicitudestudios.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		WHERE lab_areas.IdArea='$idarea' AND  lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud AND mnt_establecimiento.IdEstablecimiento=$lugar
		AND mnt_expediente.IdEstablecimiento=$lugar";
        //echo $query;
	$result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }


  //DATOS DEL DETALLE DE LA SOLICITUD
function DatosDetalleSolicitud($idexpediente,$idsolicitud)
{
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	   $query = "SELECT sec_solicitudestudios.IdNumeroExp,  lab_examenes.IdArea AS IdArea,
 		lab_examenes.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud,
		sec_solicitudestudios.IdSolicitudEstudio,sec_detallesolicitudestudios.IdDetalleSolicitud,lab_examenesxestablecimiento.IdPlantilla,
		CASE sec_detallesolicitudestudios.EstadoDetalle 
		WHEN 'D'  THEN 'Digitado'
		WHEN 'PM' THEN 'En Proceso'
		WHEN 'RM' THEN 'Muestra Rechazada'    
		WHEN 'RC' THEN 'Resultado Completo' END AS Estado
		FROM sec_detallesolicitudestudios 
		INNER JOIN sec_solicitudestudios  ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio
		INNER JOIN lab_examenes  ON sec_detallesolicitudestudios.idExamen=lab_examenes.IdExamen
		INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB' AND lab_areasxestablecimiento.Condicion='H' AND sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud 
		ORDER BY lab_examenes.IdArea";
	  $result = @mysql_query($query);
	  if (!$result)
	       return false;
	  else
	       return $result;	   
   }
 }

//FUNCION PARA MOSTRAR DATOS FIJOS DE LA PLANTILLA
 function MostrarDatosFijosPlantillaA($idexamen,$lugar,$idsolicitud)
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	$query="SELECT lab_examenes.IdExamen,NombreExamen,Unidades,RangoInicio,RangoFin
				FROM lab_datosfijosresultado 
				INNER JOIN lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
				INNER JOIN lab_resultados ON lab_examenes.IdExamen=lab_resultados.IdExamen  
				WHERE lab_datosfijosresultado.IdEstablecimiento=$lugar AND  lab_examenes.IdExamen='$idexamen' 
				AND IdSolicitudEstudio=$idsolicitud
				AND DATE_FORMAT(lab_resultados.FechaHoraReg,'%Y/%m/%d') BETWEEN lab_datosfijosresultado.FechaIni AND IF(lab_datosfijosresultado.FechaFin ='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)";	
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }
/********************************************************* Plantilla B **************************************/
//FUNCION PARA OBTENER ELEMENTOS DE UN EXAMEN PLANTILLA B
 function LeerElementosExamen($idexamen,$iddetalle,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
      $query = "SELECT IdElemento,Elemento,UnidadElem,SubElemento,NombreArea,NombreExamen,ObservElem 
		FROM lab_elementos INNER JOIN lab_examenesxestablecimiento ON lab_elementos.IdExamen=lab_examenesxestablecimiento.IdExamen 
		INNER JOIN lab_examenes ON lab_examenesxestablecimiento.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea 
		INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
		INNER JOIN lab_resultados ON lab_elementos.IdExamen=lab_resultados.IdExamen 
		WHERE lab_examenesxestablecimiento.IdExamen='$idexamen' AND lab_elementos.IdEstablecimiento=$lugar 
		AND left(lab_resultados.FechaHoraReg,10) 
		BETWEEN lab_elementos.FechaIni AND IF(lab_elementos.FechaFin ='0000-00-00',CURDATE(),lab_elementos.FechaFin) 
		AND IdDetalleSolicitud=$iddetalle ORDER BY IdElemento";
//echo $query;
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }


 function ResulatdoElementoExamen($idsolicitud,$iddetalle,$idelemento){
  
	 $con = new ConexionBD;
   if($con->conectar()==true)
   {
	$query="SELECT lab_detalleresultado.Resultado,lab_detalleresultado.Observacion FROM lab_resultados
	INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	WHERE lab_resultados.IdSolicitudEstudio=$idsolicitud AND lab_resultados.IdDetalleSolicitud=$iddetalle   AND lab_detalleresultado.IdElemento=$idelemento"; 
	 $result = @mysql_query($query);
     	if (!$result)
      		return false;
     	else
       		return $result;
	}
}
 
//FUNCION PARA PBTENER SUB-ELEMENTOS DE UN EXAMEN PLANTILLA B
 function LeerSubElementosExamen($idsolicitud,$iddetalle,$idelemento,$lugar,$idedad,$sexo)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="SELECT lab_resultados.IdResultado, lab_detalleresultado.IdDetalleResultado, 
              lab_subelementos.IdElemento,lab_detalleresultado.IdSubElemento,lab_subelementos.SubElemento,
              lab_detalleresultado.Resultado,lab_subelementos.Unidad,lab_subelementos.RangoInicio,lab_subelementos.RangoFin,
              lab_detalleresultado.Observacion 
              FROM lab_resultados
              INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	      INNER JOIN lab_subelementos ON  lab_detalleresultado.IdSubElemento=lab_subelementos.IdSubElemento
	      WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle 
              AND lab_subelementos.IdElemento=$idelemento AND lab_subelementos.IdEstablecimiento=$lugar 
              AND DATE_FORMAT(lab_resultados.FechaHoraReg,'%Y/%m/%d') BETWEEN FechaIni 
              AND IF(lab_subelementos.FechaFin ='0000-00-00',CURDATE(),lab_subelementos.FechaFin) 
              AND (lab_subelementos.idsexo=$sexo OR lab_subelementos.idsexo=3) AND (idedad=4 OR idedad=$idedad)
	      ORDER BY IdSubElemento ";
        //echo $query;

     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

//FUNCION PARA LEER LOS DATOS DEL AREA Y EL EXAMEN
function LeerDatos($idexamen)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT NombreArea,NombreExamen 
		FROM lab_examenes 
		INNER JOIN lab_areas ON  lab_examenes.IdArea=lab_areas.IdArea
		WHERE  lab_examenes.IdExamen='$idexamen'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}



//FUNCION PARA MOSTRAR DATOS GENERALES
function MostrarDatosGenerales($idsolicitud,$iddetalle,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="SELECT DISTINCT  lab_recepcionmuestra.IdSolicitudEstudio, sec_historial_clinico.IdNumeroExp,
	   CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	   (year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
	   IF(Sexo=1,'Masculino','Femenino') AS Sexo,
	   NombreSubServicio AS Origen,NombreServicio AS Procedencia,
	   NumeroMuestra,DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento,
           lab_resultados.Responsable,mnt_empleados.NombreEmpleado,lab_resultados.Observacion,lab_resultados.IdResultado
	   FROM lab_recepcionmuestra 
	   INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio
	   INNER JOIN sec_historial_clinico  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
	   INNER JOIN mnt_expediente  ON  mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
	   INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
	   INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
	   INNER JOIN mnt_servicio ON mnt_servicio .IdServicio= mnt_subservicio.IdServicio
       INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	   INNER JOIN lab_resultados ON sec_solicitudestudios.IdSolicitudEstudio=lab_resultados.IdSolicitudEstudio
	   INNER JOIN mnt_empleados ON lab_resultados.Responsable=mnt_empleados.IdEmpleado
	   WHERE  lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud AND sec_solicitudestudios.IdEstablecimiento=$lugar 
	   AND lab_resultados.IdDetalleSolicitud=$iddetalle";
//echo $query;
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

function MostrarDatosGeneralesxId($idsolicitud,$iddetalle,$lugar,$IdResultado)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="SELECT DISTINCT  lab_recepcionmuestra.IdSolicitudEstudio, sec_historial_clinico.IdNumeroExp,
	   CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	   (year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
	   IF(Sexo=1,'Masculino','Femenino') AS Sexo,
	   NombreSubServicio AS Origen,NombreServicio AS Procedencia,
	   NumeroMuestra,DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento,
           lab_resultados.Responsable,mnt_empleados.NombreEmpleado,lab_resultados.Observacion,lab_resultados.IdResultado,
	   lab_detalleresultado.IdBacteria,lab_bacterias.Bacteria,lab_detalleresultado.Cantidad
	   FROM lab_recepcionmuestra 
	   INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio
	   INNER JOIN sec_historial_clinico  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
	   INNER JOIN mnt_expediente  ON  mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
	   INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
	   INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
	   INNER JOIN mnt_servicio ON mnt_servicio .IdServicio= mnt_subservicio.IdServicio
       INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
	   INNER JOIN lab_resultados ON sec_solicitudestudios.IdSolicitudEstudio=lab_resultados.IdSolicitudEstudio
	   INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	   INNER JOIN mnt_empleados ON lab_resultados.Responsable=mnt_empleados.IdEmpleado
	   INNER JOIN lab_bacterias ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
	   WHERE  lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud AND sec_solicitudestudios.IdEstablecimiento=$lugar 
	   AND lab_resultados.IdDetalleSolicitud=$iddetalle AND lab_resultados.IdResultado=$IdResultado";
 
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

/*********************************  Plantilla B ***************************/
  
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

function Obtener_datos($idsolicitud,$iddetalle){
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   	$query ="SELECT lab_examenes.IdArea FROM sec_detallesolicitudestudios 
		INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle";
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
     $query ="delete from lab_resultados where IdSolicitudEstudio=$idsolicitud 
         AND IdDetalleSolicitud=$iddetalle";
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
 
 function CalculoDias($fechanac){
     $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT DATEDIFF(NOW( ),'$fechanac')";
      //  echo $query;
	 $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function ObtenerCodigoRango($dias){
   $con = new ConexionBD;
   if($con->conectar()==true){  
       $query="SELECT * FROM mnt_rangoedad WHERE $dias BETWEEN edadini AND edadfin
            AND idedad <>4";
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
   	$query ="SELECT Resultado FROM lab_resultados WHERE IdResultado=$idresultado";
	$result = @mysql_query($query);
	if (!$result)
	   return false;
	else 
	   return $result;
   }
}


function ObtenerObservacion($idsolicitud,$iddetalle,$lugar){
    $con = new ConexionBD;
    if($con->conectar()==true)
    {
    	$query ="SELECT lab_observaciones.Observacion FROM lab_resultados 
		INNER JOIN lab_observaciones ON lab_resultados.Observacion=lab_observaciones.IdObservacion
		INNER JOIN sec_solicitudestudios ON lab_resultados.IdSolicitudEstudio=lab_resultados.IdSolicitudEstudio
		WHERE sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud and lab_resultados.IdDetalleSolicitud=$iddetalle  AND sec_solicitudestudios.IdEstablecimiento=$lugar";
	$result = @mysql_query($query);
	if (!$result)
	   	return false;
	else 
		return $result;
    }  
}


function DetalleExamenes($idsolicitud,$idarea,$lugar,$idedad,$sexo)
 {      
		//echo $idsolicitud;
		$con = new ConexionBD;
                //usamos el metodo conectar para realizar la conexion
                if($con->conectar()==true){
		
			$query = "SELECT sec_detallesolicitudestudios.IdDetalleSolicitud,lab_examenes.IdExamen,lab_examenes.NombreExamen,
                                lab_resultados.Resultado,lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
                                lab_datosfijosresultado.RangoFin,lab_resultados.Lectura,lab_resultados.Interpretacion,
                                lab_resultados.Observacion,mnt_empleados.NombreEmpleado,
                                DATE_FORMAT(lab_resultados.FechaHoraReg ,'%d/%m/%Y %H:%i:%s') AS Fecha   
                                FROM sec_detallesolicitudestudios 
                                INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                                INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                                INNER JOIN lab_datosfijosresultado ON lab_examenes.IdExamen=lab_datosfijosresultado.IdExamen
                                INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
                                INNER JOIN mnt_empleados ON lab_resultados.Responsable=mnt_empleados.IdEmpleado
				WHERE sec_detallesolicitudestudios.IdSolicitudEstudio=$idsolicitud 
				AND lab_examenes.IdExamen LIKE'$idarea%'AND lab_examenesxestablecimiento.IdPlantilla='A' 
				AND sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_datosfijosresultado.IdEstablecimiento=$lugar
				AND DATE_FORMAT(lab_resultados.FechaHoraReg, '%Y/%m/%d') BETWEEN lab_datosfijosresultado.FechaIni 
                                AND IF(lab_datosfijosresultado.FechaFin='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
                                AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) AND (idedad=4 OR idedad=$idedad)
				ORDER BY NombreExamen";
			//echo $query;
			 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		  // echo $query
		   return $result;
		}
 }
 
 /******************************Plantilla D*/
//MOSTRAR ELEMENTOS DE INCION AGREGADOS A UN RESULTADO
function MostrarElementosAgregados($idresultado)
{
$con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query ="SELECT ElementoTincion,CantidadTincion 
	     FROM lab_detalleresultado 
	     INNER JOIN lab_elementostincion  ON lab_detalleresultado.IdElemento=lab_elementostincion.IdElementosTincion
 	     INNER JOIN lab_cantidadestincion  ON lab_cantidadestincion.IdCantidadesTincion=lab_detalleresultado.IdCantidad
         WHERE IdResultado=$idresultado" ;
   $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;	   
   }
}

function ObtenerIdResultado($idsolicitud,$iddetalle)
{
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	 $query ="SELECT IdResultado FROM lab_resultados where IdSolicitudEstudio=$idsolicitud and IdDetalleSolicitud=$iddetalle";
	  $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;
     }

}
/**************************************************************/

/************************* plantilla E*****************************************/
 //FUNCION PARA LEER LOS PROCESO POR EXAMEN
function LeerProcesoExamen($idsolicitud,$iddetalle)
{
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
		$query ="SELECT lab_procedimientosporexamen.IdProcedimientoporexamen,lab_procedimientosporexamen.NombreProcedimiento,
		lab_detalleresultado.Resultado,lab_procedimientosporexamen.RangoInicio,
		lab_procedimientosporexamen.rangofin,lab_procedimientosporexamen.Unidades,lab_detalleresultado.Observacion 
		FROM 	lab_resultados 
		INNER JOIN  lab_detalleresultado ON lab_detalleresultado.IdResultado=lab_resultados.IdResultado
		INNER JOIN lab_procedimientosporexamen ON lab_detalleresultado.IdProcedimiento=lab_procedimientosporexamen.IdProcedimientoporexamen
		WHERE lab_resultados.IdDetalleSolicitud=$iddetalle AND lab_resultados.IdSolicitudEstudio=$idsolicitud 
		AND  DATE_FORMAT(lab_resultados.FechaHoraReg, '%Y/%m/%d' ) BETWEEN lab_procedimientosporexamen.FechaIni AND IF(lab_procedimientosporexamen.FechaFin ='0000-00-00',CURDATE(),lab_procedimientosporexamen.FechaFin)
		ORDER BY IdProcedimiento ASC";

	 $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;   
   }
}

/***********************Plantilla C******************************************/
// FUNCION PARA OBTENER EL NOMBRE DE LA BACTERIA
   function NombreBacteria($idbacteria)
 {
  $query = "SELECT Bacteria FROM lab_bacterias WHERE IdBacteria=$idbacteria";
  $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
  }

 // Obtener la cantidad de resultados para dicho resultado 
function ObtenerCantidadResultados($idsolicitud,$iddetalle)
{
 $con = new ConexionBD;
   if($con->conectar()==true)
   {  $query = "SELECT Count(*) FROM lab_resultados WHERE IdSolicitudEstudio=$idsolicitud AND 		
		IdDetalleSolicitud=$iddetalle";
    $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
} 
 function ObtenerIdResultados($idsolicitud,$iddetalle){
	$con = new ConexionBD;
   if($con->conectar()==true)
   {  $query = "SELECT IdResultado FROM lab_resultados WHERE IdSolicitudEstudio=$idsolicitud AND 		
		IdDetalleSolicitud=$iddetalle";
    $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
 }
 
function ObtenerFechaResultado($idsolicitud,$idarea,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
      $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado, Responsable
				FROM lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar
				AND IdExamen like '$idarea%'";
      
     // echo $query;
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

//función para obtener el valor de resultado P(Positivo) ó N(Negativo)
function ObtenerResultado($idsolicitud,$iddetalle)
{
 $con = new ConexionBD;
   if($con->conectar()==true)
   {  $query = "SELECT Resultado FROM lab_resultados WHERE IdSolicitudEstudio=$idsolicitud AND 		
		IdDetalleSolicitud=$iddetalle";
    $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

function ObtenerResultadoxId($idsolicitud,$iddetalle)
{
 $con = new ConexionBD;
   if($con->conectar()==true)
   {  $query = "SELECT IdResultado,Resultado FROM lab_resultados WHERE IdSolicitudEstudio=$idsolicitud AND 		
		IdDetalleSolicitud=$iddetalle";
    $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

 function BuscarEmpleadoValidador($responsable)
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	$query="SELECT NombreEmpleado FROM mnt_empleados WHERE IdEmpleado='$responsable' "	;	
	     $result = mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }
 
//FUNCION PARA OBTENER RESULTADOS DE ANTIBIOTICOS DE UNA TARJETA
 function LeerResultadosAntibioticos($idsolicitud,$iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT lab_resultados.IdResultado, 						 	 
	lab_detalleresultado.IdDetalleResultado,lab_resultadosportarjeta.IdAntibiotico,lab_antibioticos.Antibiotico,
	lab_resultadosportarjeta.Resultado
	FROM lab_resultados 
	INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	INNER JOIN lab_resultadosportarjeta ON lab_detalleresultado.IdDetalleResultado=lab_resultadosportarjeta.IdDetalleResultado
	INNER JOIN lab_antibioticos ON lab_resultadosportarjeta.IdAntibiotico=lab_antibioticos.IdAntibiotico
	WHERE IdSolicitudEstudio=$idsolicitud and IdDetalleSolicitud=$iddetalle";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

  function LeerResultadosAntibioticosxId($idsolicitud,$iddetalle,$Idresultado)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "	SELECT lab_resultados.IdResultado, 						 	 
	lab_detalleresultado.IdDetalleResultado, lab_detalleresultado.IdTarjeta,lab_tarjetasvitek.NombreTarjeta,lab_detalleresultado.IdBacteria,
	lab_bacterias.Bacteria,lab_resultadosportarjeta.IdAntibiotico,lab_antibioticos.Antibiotico,
	lab_resultadosportarjeta.Resultado, mnt_empleados.NombreEmpleado
	FROM lab_resultados 
	INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	INNER JOIN lab_tarjetasvitek ON lab_detalleresultado.IdTarjeta=lab_tarjetasvitek.IdTarjeta
	INNER JOIN lab_bacterias ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
	INNER JOIN lab_resultadosportarjeta ON lab_detalleresultado.IdDetalleResultado=lab_resultadosportarjeta.IdDetalleResultado
	INNER JOIN lab_antibioticos ON lab_resultadosportarjeta.IdAntibiotico=lab_antibioticos.IdAntibiotico
	INNER JOIN mnt_empleados ON lab_resultados.Responsable=mnt_empleados.IdEmpleado
	WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle AND lab_resultados.IdResultado=$Idresultado";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
//FUNCION PARA MOSTRAR DATOS RESULTADO DE PLANTILLA C POSITIVO
function DatosResultadoPlanCPositivo($idsolicitud,$iddetalle)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT lab_resultados.IdExamen,lab_examenes.NombreExamen,lab_resultados.Resultado,
	       lab_detalleresultado.IdTarjeta,lab_tarjetasvitek.NombreTarjeta,lab_detalleresultado.IdBacteria,
	       lab_bacterias.Bacteria,lab_detalleresultado.Cantidad
	       FROM lab_resultados 
	       INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	       INNER JOIN lab_bacterias ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
	       INNER JOIN lab_tarjetasvitek ON lab_detalleresultado.IdTarjeta=lab_tarjetasvitek.IdTarjeta
	       INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_resultados.IdExamen
	       WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

//FUNCION PARA MOSTRAR DATOS RESULTADO DE PLANTILLA C POSITIVO
function DatosResultadoPlanCPositivoxId($idsolicitud,$iddetalle,$Idresultado)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT lab_resultados.IdExamen,lab_examenes.NombreExamen,lab_resultados.Resultado,
	       lab_detalleresultado.IdTarjeta,lab_tarjetasvitek.NombreTarjeta,lab_detalleresultado.IdBacteria,
	       lab_bacterias.Bacteria,lab_detalleresultado.Cantidad
	       FROM lab_resultados 
	       INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	       INNER JOIN lab_bacterias ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
	       INNER JOIN lab_tarjetasvitek ON lab_detalleresultado.IdTarjeta=lab_tarjetasvitek.IdTarjeta
	       INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_resultados.IdExamen
	       WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle AND lab_detalleresultado.IdResultado=$Idresultado";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}


/********************************************************************************/

 }//CLASE
?>
