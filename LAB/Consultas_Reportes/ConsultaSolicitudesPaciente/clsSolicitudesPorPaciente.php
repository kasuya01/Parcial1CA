<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsSolicitudesPorPaciente
{
	 //constructor	
function clsSolicitudesPorPaciente()
{
}	
	 
function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query=//"SELECT Nombre FROM mnt_establecimiento WHERE IdEstablecimiento=$lugar";
	 "SELECT nombre FROM ctl_establecimiento WHERE id=$lugar";
                $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = /*"SELECT 	mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";*/
                        "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
		$resul = pg_query($conNom) or die('La consulta fall&oacute;: ' . pg_error());
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= //"SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
		"SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText=/* "SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
			FROM mnt_subservicio 
			INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
			WHERE mnt_subservicio.IdServicio='$IdServ' AND IdEstablecimiento=$lugar 
			ORDER BY NombreSubServicio";	*/
                         "WITH tbl_servicio AS (
                            SELECT t02.id,
                                CASE WHEN t02.nombre_ambiente IS NOT NULL THEN  	
                                    CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'-->' ||t02.nombre_ambiente
                                         ELSE t02.nombre_ambiente
                                    END
                                ELSE
                                    CASE WHEN id_servicio_externo_estab IS NOT NULL THEN t05.abreviatura ||'--> ' || t01.nombre
                                         WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=t01.nombre) THEN t01.nombre
                                    END
                                END AS servicio 
                            FROM  ctl_atencion 				    t01 
                            INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                            INNER JOIN mnt_area_mod_estab 	   	    t03 ON (t03.id = t02.id_area_mod_estab)
                            LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                            LEFT  JOIN mnt_servicio_externo 		    t05 ON (t05.id = t04.id_servicio_externo)
                            WHERE id_area_atencion = $IdServ and t02.id_establecimiento = $lugar
                            ORDER BY 2)
                        SELECT id, servicio FROM tbl_servicio WHERE servicio IS NOT NULL";
                        
		$dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}

function ExamenesPorArea($idarea,$lugar)
{
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query =/* "SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
		       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		       WHERE IdEstablecimiento = $lugar AND IdArea='$idarea'
		       AND lab_examenesxestablecimiento.Condicion='H' ORDER BY NombreExamen ASC ";*/
                         
                          "SELECT lcee.id,lcee.nombre_examen 
                    FROM mnt_area_examen_establecimiento maees
                    INNER JOIN lab_conf_examen_estab lcee ON maees.id= lcee.idexamen 
                    WHERE maees.id_area_servicio_diagnostico=$idarea
                    AND maees.id_establecimiento=$lugar
                    AND lcee.condicion= 'H'
                    ORDER BY lcee.nombre_examen asc";
                         
		 $result = @pg_query($query);
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
	 
	     $result = @pg_query($query);
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
		 $result = @pg_query($query);
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
	      $result = @pg_query($query);
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
	      $result = @pg_query($query);
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
		IF (Sexo=1,'Masculino','Femenino') AS Sexo, sec_solicitudestudios .estado AS Estado,mnt_establecimiento.Nombre,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') AS FechaNacimiento
		FROM sec_historial_clinico 
		INNER JOIN sec_solicitudestudios  ON sec_historial_clinico.IdHistorialClinico= sec_solicitudestudios.IdHistorialClinico
		INNER JOIN mnt_empleados ON sec_historial_clinico.IDEmpleado= mnt_empleados.IdEmpleado
		INNER JOIN mnt_expediente ON sec_historial_clinico.IdNumeroExp= mnt_expediente.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_expediente.IdPaciente=mnt_datospaciente.IdPaciente  
		INNER JOIN mnt_subservicio  ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
		INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
		INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
		WHERE sec_solicitudestudios.IdServicio ='DCOLAB'  AND sec_solicitudestudios.IdEstablecimiento=$lugar AND sec_historial_clinico.IdNumeroExp='$idexpediente'
		AND sec_solicitudestudios .IdSolicitudEstudio=$idsolicitud";
		$result = @pg_query($query);
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
AND lab_areasxestablecimiento.Condicion='H' AND sec_solicitudestudios.IdNumeroExp='$idexpediente' AND sec_solicitudestudios.IdSolicitudEstudio=$idsolicitud
ORDER BY lab_examenes.IdArea";
	  	$result = @pg_query($query);
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
 
   $result = @pg_query($query);
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
  $result = @pg_query($query);
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
     $result = @pg_query($query);
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
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
}//CLASE
?>
