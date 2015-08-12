<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsConsultaMuestrasRechazadas
{
 //constructor	
 function clsMuestrasRechazadas(){
 }	
 
function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT nombre FROM ctl_establecimiento WHERE id=$lugar";
	 $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }

}

 
 function DatosArea($area){
	$con = new ConexionBD;
	if($con->conectar()==true){			  
		$NomAre  = "select nombrearea,administrativa from ctl_area_servicio_diagnostico where id=$area";
		$resul = pg_query($NomAre);
	}
 return $resul;
}

function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
		$resul = pg_query($conNom);
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText=  "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) ;
	}
	return $dt;
}

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText=  "WITH tbl_servicio AS (
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
		$dt = pg_query($sqlText) ;
	}
	return $dt;
}

function ExamenesPorArea($idarea,$lugar)
{
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = "SELECT lcee.id,lcee.nombre_examen 
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

 //FUNCION PARA ACTUALIZAR OBSERVACION DEL EXAMEN QUE HA SIDO RECHAZADO EN UNA SOLICITUD
 function MarcarObservacionRechazado($idsolicitud,$idarea,$observacion)
 {
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query="UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE id=$idsolicitud AND idexamen LIKE '%$idarea%'";
		  
		  		  
	 $result = @pg_query($query);
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
		  
	 $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }
 
 //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO
function ListadoSolicitudesPorArea($query_search)
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
 
 //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
 //DATOS GENERALES DE LA SOLICITUD
function DatosGeneralesSolicitud($idexpediente,$idsolicitud)
{  
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	 $query = "SELECT B.IdSolicitudEstudio AS IdSolicitudEstudio,NumeroMuestra,Observacion,C.IdEmpleado AS IdMedico,
			   NombreEmpleado AS NombreMedico, NombreSubServicio AS Origen,
			   NombreServicio AS Precedencia, D.IdNumeroExp, 
			   CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
				CURDATE() AS Fecha,(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
				IF(Sexo=1,'Masculino','Femenino') AS Sexo,
			    B.FechaSolicitud FROM sec_historial_clinico AS A
				INNER JOIN sec_solicitudestudios AS B ON A.IdHistorialClinico= B.IdHistorialClinico
				INNER JOIN mnt_empleados  		AS C ON A.IDEmpleado= C.IdEmpleado
				INNER JOIN mnt_expediente 		AS D ON A.IdNumeroExp= D.IdNumeroExp
				INNER JOIN mnt_datospaciente	AS E on D.IdPaciente=E.IdPaciente  
				INNER JOIN mnt_subservicio 		AS F ON F.IdSubServicio= A.IDSubServicio
				INNER JOIN mnt_servicio 		AS G ON G.IdServicio= F.IdServicio
				INNER JOIN lab_recepcionmuestra AS H ON B.IdSolicitudEstudio=H.IdSolicitudEstudio
				WHERE B.IdServicio ='DCOLAB' AND A.IdNumeroExp='$idexpediente' AND B.IdSolicitudEstudio=$idsolicitud";
				$result = @pg_query($query);
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
		$result = @pg_query($query);
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
	$result = @pg_query($query);
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
     $result = @pg_query($query);
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
   $query = "UPDATE sec_detallesolicitudestudios AS a,sec_solicitudestudios AS b
			SET a.EstadoDetalle='$estado',a.Observacion=''
			WHERE a.IdSolicitudEstudio='$idsolicitud' AND 
			b.IdServicio='DCOLAB' AND a.IdExamen LIKE '%$idarea%' ";
   $result = @pg_query($query);
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
   
   $result = @pg_query($query);
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
	 $numreg = pg_num_rows(pg_query($query));
	 if (!$numreg )
	   return false;
	 else
	   return $numreg ;
   }
  }
 
}//CLASE
?>
