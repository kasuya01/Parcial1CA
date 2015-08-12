<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsSolicitudesProcesadas
{
 //constructor	
 function clsSolicitudesProcesadas(){
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

function DatosArea($area){
	$con = new ConexionBD;
	if($con->conectar()==true){			  
		$NomAre  = "select NombreArea,Administrativa from lab_areas where IdArea='$area'";
		$resul = mysql_query($NomAre) or die('La consulta fall&oacute;: ' . mysql_error());
	}
 return $resul;
}



function DatosEstablecimiento($lugar){
	$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
		FROM mnt_establecimiento 
		INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
		WHERE IdEstablecimiento=$lugar";
		$resul = mysql_query($conNom) or die('La consulta fall&oacute;: ' . mysql_error());
	}
 return $resul;
}

function ObtenerFechaResultado1($IdSolicitud,$IdExamen,$lugar){
  $con = new ConexionBD;
	 if($con->conectar()==true) 
	 {	$query="SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') FROM lab_resultados 
                        WHERE IdSolicitudEstudio=$IdSolicitud AND IdExamen='$IdExamen' 
                        AND IdEstablecimiento=$lugar";
          $result = mysql_query($query);
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


function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento 
			   WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
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
 
 //FUNCION FECHA DE RESULTADO
function ObtenerFechaResultado($idsolicitud,$idarea,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
      $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado
		FROM lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar
		AND IdExamen like '$idarea%'";
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
 
//FUNCION PARA MOSTRAR DATOS FIJOS DE LA PLANTILLA
 function MostrarDatosFijosPlantillaA($IdExamen,$lugar,$sexo,$idedad)
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	$query="SELECT lab_examenes.IdExamen,NombreExamen,Unidades,RangoInicio,RangoFin 
			FROM lab_datosfijosresultado 
			INNER JOIN lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
			WHERE IdEstablecimiento=$lugar AND  lab_examenes.IdExamen='$IdExamen' 
			AND CURDATE() BETWEEN FechaIni 
                        AND IF(lab_datosfijosresultado.FechaFin='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)
                        AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) 
                        AND (idedad=4 OR idedad=$idedad)";	
	
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }
 
 
 function ImprimirResultadoPlantillaA($IdExamen,$lugar,$idsolicitud){
 $con = new ConexionBD;
	if($con->conectar()==true) 
	{
	 $query="SELECT sec_detallesolicitudestudios.IdDetalleSolicitud,lab_examenes.IdExamen,lab_examenes.NombreExamen,
		lab_resultados.Resultado,lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
		lab_datosfijosresultado.RangoFin,lab_resultados.Lectura,lab_resultados.Interpretacion,lab_resultados.Observacion,
		DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado    
		FROM sec_detallesolicitudestudios 
		INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		INNER JOIN lab_datosfijosresultado ON lab_examenes.IdExamen=lab_datosfijosresultado.IdExamen
		INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
		WHERE lab_datosfijosresultado.IdEstablecimiento=$lugar AND lab_examenesxestablecimiento.IdPlantilla='A' 
		AND sec_detallesolicitudestudios.EstadoDetalle='RC' AND  lab_examenes.IdExamen='$IdExamen'  
		AND sec_detallesolicitudestudios.IdSolicitudEstudio=$idsolicitud AND CURDATE() 
		BETWEEN FechaIni AND IF(lab_datosfijosresultado.FechaFin  = '0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)
		ORDER BY NombreExamen";	
		//echo $query;
		$result = mysql_query($query);
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
		NombreArea,NumeroMuestra,DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,
		sec_solicitudestudios.IdEstablecimiento,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento
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
	$result = mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 //FUNCION BUSCAR EMPLEADO QUE VALIDA RESULTADOS
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
 
  //Funcion para llener resultados de tabulaci�n
 function LlenarResultados()
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	$query="SELECT IdResultado,Resultado FROM lab_codigosresultados";	
	     $result = mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   	   }
 }
 
 
 
/*Funcion para obtener el nombre del Resultado del tabulador*/ 
function ObtenerNombreCodigo($cod){
 	 $con = new ConexionBD;
	 if($con->conectar()==true) 
	 {	$query=" SELECT Resultado FROM lab_codigosresultados WHERE IdResultado=$cod ";	
	     $result = mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	 }
}



 //FUNCION PARA LLAMAR EMPLEADOS DE LABORATORIOS FILTRADOS POR AREA
 function BuscarEmpleados($idarea,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT IdEmpleado, NombreEmpleado
                FROM mnt_empleados
                INNER JOIN mnt_cargoempleados ON mnt_empleados.IdCargoEmpleado = mnt_cargoempleados.IdCargoEmpleado
                WHERE mnt_cargoempleados.idservicio = 'DCOLAB'
                AND IdArea <> 'TMU'
                AND IdArea <> 'INF'
                AND IdArea <> 'REC'
                AND IdEstablecimiento =$lugar ";	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 
 //FUNCION PARA LLAMAR EMPLEADOS DE LABORATORIOS FILTRADOS POR AREA
 function BuscarNombreaArea($idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT NombreArea FROM lab_areas WHERE IdArea='$idarea'";	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 
 //FUNCION PARA MOSTRAR LOS DATOS GENERALES DE LA SOLICITUD PROCESADA
 function MostrarResultadoGenerales($idsolicitud,$idexamen,$lugar)
 {
	$con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT lab_recepcionmuestra.IdSolicitudEstudio, mnt_expediente.IdNumeroExp, 
	CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
	(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
	TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
	NombreArea,NumeroMuestra,DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,
        mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento
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
	WHERE sec_detallesolicitudestudios.IdExamen='$idexamen' AND lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud 
        AND sec_solicitudestudios.IdEstablecimiento=$lugar AND mnt_expediente.IdEstablecimiento=$lugar";
	$result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 //FUNCION PARA MOSTRAR RESULTADOS DE EXAMEN PLANTILLA A
 function MostrarResultadoPlantillaA($idsolicitud,$idexamen)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT C.IdSolicitudEstudio, H.IdNumeroExp,
			CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
			(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
			IF(Sexo=1,'Masculino','Femenino') AS Sexo,
			TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
			D.IdExamen,NombreExamen,NombreArea,NumeroMuestra,RangoInicio,RangoFin,Resultado,A.Lectura,
			A.Interpretacion,Unidades,A.Observacion,Responsable,NombreEmpleado,current_date AS Fecha
			FROM lab_resultados A
			INNER JOIN lab_datosfijosresultado B ON A.IdExamen=B.IdExamen
			INNER JOIN lab_recepcionmuestra C ON C.IdRecepcionMuestra=A.IdRecepcionMuestra
			INNER JOIN lab_examenes D ON D.IdExamen=A.IdExamen
			INNER JOIN lab_areas E ON E.IdArea=D.IdArea
			INNER JOIN mnt_empleados F ON F.IdEmpleado=A.Responsable
			INNER JOIN sec_solicitudestudios G ON G.IdSolicitudEstudio=C.IdSolicitudEstudio
			INNER JOIN sec_historial_clinico H ON H.IdHistorialClinico=G.IdHistorialClinico
			INNER JOIN mnt_expediente I ON I.IdNumeroExp=H.IdNumeroExp
			INNER JOIN mnt_datospaciente J ON J.IdPaciente=I.IdPaciente 
			INNER JOIN mnt_subservicio K ON K.IdSubServicio= H.IdSubServicio
			INNER JOIN mnt_servicio L ON L.IdServicio= K.IdServicio
			WHERE A.IdExamen='$idexamen' AND C.IdSolicitudEstudio=$idsolicitud"	;	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }

 
 //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO de muestras procesadas PM
  function ListadoSolicitudesPorArea($idarea,$estadodetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="SELECT IdPlantilla,A.IdDetalleSolicitud, B.IdSolicitudEstudio,NumeroMuestra,B.IdNumeroExp,IdRecepcionMuestra,
            D.idexamen, nombreexamen,Indicacion,NombreArea,
			CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS Paciente
			FROM sec_detallesolicitudestudios AS A 
			INNER JOIN sec_solicitudestudios  AS B ON A.IdSolicitudEstudio=B.IdSolicitudEstudio
			INNER JOIN lab_recepcionmuestra   AS C ON A.IdSolicitudEstudio=C.IdSolicitudEstudio
			INNER JOIN lab_examenes           AS D ON A.IdExamen=D.IdExamen
			INNER JOIN mnt_expediente         AS F ON F.IdNumeroExp=B.IdNumeroExp
			INNER JOIN mnt_datospaciente      AS G ON G.IdPaciente=F.IdPaciente
			INNER JOIN lab_areas              AS H ON H.IdArea=D.IdArea
			WHERE H.idArea='$idarea' AND estadodetalle='$estadodetalle'";
		
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 function ListadoSolicitudesPorAreaPendientes($query_search)
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
 
 //FUNCION PARA GUARDAR RESULTADOS

function InsertarResultadoPlantillaA($idexamen,$idsolicitud,$iddetalle,$resultado,$lectura,$observacion,$responsable,$lectura,$idrecepcion,$interpretacion,$observacion,$usuario,$codigo,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
  {	$query="INSERT INTO lab_resultados(IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,
            IdRecepcionMuestra,Resultado,Lectura,Interpretacion,Observacion,Responsable,
	IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento)  
	VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$resultado','$lectura',
		'$interpretacion','$observacion','$responsable',$usuario,NOW(),$usuario,NOW(),$codigo,$lugar)"	;	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 /*Funcion para obtener los valores de referencia*/
 function valores_referencia($idexamenes){

   $con = new ConexionBD;
   if($con->conectar()==true) 
  	{$query="select RangoInicio,RangoFin from lab_datosfijosresultado where IdExamen='$idexamenes' AND CURDATE() BETWEEN FechaIni AND IF(lab_datosfijosresultado.FechaFin  = '0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)";
	 $result = @mysql_query($query);
     	if (!$result)
       		return false;
     	else
      		 return $result;	   
	}		
}
/* funcion para ingresar los resultados de las plantillas A solo de Quimica*/
//function InsertarResultadoPlantillaA1($idexamen,$idsolicitud,$iddetalle,$resultado,$lectura,$idrecepcion,$interpretacion,$observacion,$responsable,$usuario,$IdCodigoResultado,$lugar)
  function InsertarResultadoPlantillaA1($idexamen,$idsolicitud,$iddetalle,$resultado,$idrecepcion,$observacion,$responsable,$usuario,$IdCodigoResultado,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
       /*$query="INSERT INTO lab_resultados(IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,
            IdRecepcionMuestra,Resultado,Lectura,Interpretacion,Observacion,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,
			IdEstablecimiento)
			VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$resultado','$lectura','$interpretacion','$observacion','$responsable',$usuario,NOW(),$usuario,NOW(),$IdCodigoResultado,$lugar)";*/
  	{	$query="INSERT INTO lab_resultados(IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,
            IdRecepcionMuestra,Resultado,Observacion,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,
			IdEstablecimiento)
			VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$resultado','$observacion','$responsable',$usuario,NOW(),$usuario,NOW(),$IdCodigoResultado,$lugar)";
	
	$query2="SELECT LAST_INSERT_ID();";

     	$result = mysql_query($query);
	$result2=mysql_query($query2);
			 
     if ($row=mysql_fetch_array($result2)){
	$idultimo=$row[0];
	   return $idultimo;	 
     }else{
         return 0;
     }       

   }
 }
 
 //FUNCION PARA CAMBIAR EL ESTADO DEL DETALLLE DE LA SOLICITUD
 function CambiarEstadoDetalle($iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) //Estado RC--> Resultados Completo
  {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle='RC' WHERE IdDetalleSolicitud=$iddetalle"	;	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
 function CambiarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios 
			WHERE IdSolicitudEstudio=$idsolicitud 
			AND EstadoDetalle <>'RC' AND EstadoDetalle <>'RM' ";
	$detalle=mysql_num_rows(mysql_query($query));
	if(empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud"	;
		$result=@mysql_query($query);		
		return true;	  
    }else
		return false;
   }
   }
 
 
 //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
 //DATOS GENERALES DE LA SOLICITUD
function DatosGeneralesSolicitud($idexpediente)
{  
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	 $query = "SELECT B.IdSolicitudEstudio AS IdSolicitudEstudio, NumeroMuestra,Observacion,C.IdEmpleado AS IdMedico,
				NombreEmpleado AS NombreMedico, NombreSubServicio AS Origen,
				NombreServicio AS Precedencia, D.IdNumeroExp, 
				CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
				CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
				IF(Sexo=1,'Masculino','Femenino') AS Sexo,A.DatosClinicos
				FROM sec_historial_clinico 		 AS A
				INNER JOIN sec_solicitudestudios AS B ON A.IdHistorialClinico= B.IdHistorialClinico
				INNER JOIN mnt_empleados 		 AS C ON A.IDEmpleado= C.IdEmpleado
				INNER JOIN mnt_expediente 		 AS D ON A.IdNumeroExp= D.IdNumeroExp
				INNER JOIN mnt_datospaciente 	 AS E ON D.IdPaciente=E.IdPaciente  
				INNER JOIN mnt_subservicio 		 AS F ON F.IdSubServicio= A.IDSubServicio
				INNER JOIN mnt_servicio 		 AS G ON G.IdServicio= F.IdServicio
				INNER JOIN lab_recepcionmuestra  AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
				WHERE B.IdServicio ='DCOLAB' AND A.IdNumeroExp='$idexpediente'";
				
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
	 $query = "SELECT B.IdExamen,NombreExamen,Indicacion 
			   FROM sec_detallesolicitudestudios AS A
			   INNER JOIN lab_examenes           AS B ON A.IdExamen=B.IdExamen
			   WHERE idSolicitudEstudio = $idsolicitud AND IdArea='$idarea'";
		$result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
  }
  
   function DatosExamenes($idsolicitud,$idarea,$lugar,$sexo,$idedad)
           
	 {
		//echo $idsolicitud;
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
		
			$query = "SELECT  sec_detallesolicitudestudios.IdDetalleSolicitud,
                                  sec_detallesolicitudestudios.IdExamen,lab_examenes.NombreExamen,
                                  lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
                                  lab_datosfijosresultado.RangoFin,lab_examenes.IdArea,lab_examenes.IdEstandar   
			FROM sec_detallesolicitudestudios 
			INNER JOIN lab_examenes on sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
			INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
			INNER JOIN lab_datosfijosresultado on sec_detallesolicitudestudios.IdExamen=lab_datosfijosresultado.IdExamen
			WHERE EstadoDetalle='PM' AND IdSolicitudEstudio=$idsolicitud AND sec_detallesolicitudestudios.IdExamen LIKE'$idarea%'and 		
			lab_examenesxestablecimiento.IdPlantilla='A'  
                        AND lab_datosfijosresultado.IdEstablecimiento=$lugar
			AND CURDATE() BETWEEN FechaIni AND IF(FechaFin  = '0000-00-00',CURDATE(),FechaFin) 
                        AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) 
                            AND (lab_datosfijosresultado.idedad=4 OR lab_datosfijosresultado.idedad=$idedad)
                        ORDER BY NombreExamen";
                        
                       //echo $query;
			
			 $result = @mysql_query($query);
		 if (!$result)
		   return false;
		 else
		
		   return $result;
		}
	 }
  
 function DatosExamenesImprimir($idsolicitud,$idarea,$lugar,$sexo,$idedad)
	{
		//echo $idsolicitud;
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
                    $query = "SELECT  sec_detallesolicitudestudios.IdDetalleSolicitud,
                            sec_detallesolicitudestudios.IdExamen,lab_examenes.NombreExamen,lab_resultados.Resultado,
                            lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
                            lab_datosfijosresultado.RangoFin,lab_resultados.observacion,lab_examenes.IdArea,lab_examenes.IdEstandar,
                            DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s')as FechaResultado    
                            FROM sec_detallesolicitudestudios 
                            INNER JOIN lab_examenes on sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
                            INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
                            INNER JOIN lab_datosfijosresultado on sec_detallesolicitudestudios.IdExamen=lab_datosfijosresultado.IdExamen
                            INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
                            WHERE EstadoDetalle='RC' AND sec_detallesolicitudestudios .IdSolicitudEstudio=$idsolicitud 
                            AND sec_detallesolicitudestudios.IdExamen LIKE'$idarea%'		
                            AND lab_examenesxestablecimiento.IdPlantilla='A'  
                            AND lab_datosfijosresultado.IdEstablecimiento=$lugar
                            AND CURDATE() BETWEEN lab_datosfijosresultado.FechaIni AND IF(lab_datosfijosresultado.FechaFin  = '0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin) 
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
         
function DetalleExamenes($idsolicitud,$idarea,$lugar,$sexo,$idedad)
 {
		//echo $idsolicitud;
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
		
	$query = "SELECT sec_detallesolicitudestudios.IdDetalleSolicitud,lab_examenes.IdExamen,lab_examenes.NombreExamen,
		  lab_resultados.Resultado,lab_datosfijosresultado.Unidades,lab_datosfijosresultado.RangoInicio, 
		  lab_datosfijosresultado.RangoFin,lab_resultados.Lectura,lab_resultados.Interpretacion,lab_resultados.Observacion,
		  lab_resultados.FechaHoraReg AS FechaResultado     
		  FROM sec_detallesolicitudestudios 
		  INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		  INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		  INNER JOIN lab_datosfijosresultado ON lab_examenes.IdExamen=lab_datosfijosresultado.IdExamen
		INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud
		WHERE lab_datosfijosresultado.IdEstablecimiento=$lugar AND lab_examenesxestablecimiento.IdPlantilla='A' 
		AND sec_detallesolicitudestudios.EstadoDetalle='RC' AND  lab_examenes.IdExamen LIKE'$idarea%' 
		AND sec_detallesolicitudestudios.IdSolicitudEstudio=$idsolicitud AND CURDATE() 
		BETWEEN FechaIni AND IF(lab_datosfijosresultado.FechaFin  = '0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)
                AND (lab_datosfijosresultado.idsexo=$sexo OR lab_datosfijosresultado.idsexo=3) 
                AND (lab_datosfijosresultado.idedad=4 OR lab_datosfijosresultado.idedad=$idedad)
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

   
}//CLASE
?>
