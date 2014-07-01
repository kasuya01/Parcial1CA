<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsSolicitudesPorServicioPeriodo
{
	 //constructor	
	 function clsSolicitudesPorServicioPeriodo()
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
	 //FUNCION PARA MOSTRAR SUBESPECIALIDADES
	  function LeerEspecialidades()
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query= "select ";
	   /*"select IdSubServicio,NombreSubServicio from mnt_subservicio
	             where IdServicio='CONEXT' order by NombreSubServicio";*/
	 
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	   }
	 }
 
 	 //FUNCION PARA MOSTRAR DATOS DE BUSQUEDA
	  function BuscarSolicitudesEspecialidad($query_search)
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
	 
	 //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
 //DATOS GENERALES DE LA SOLICITUD
function DatosGeneralesSolicitud($idexpediente,$idsolicitud)
{  
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	 $query = "select DISTINCT C.IdEmpleado as IdMedico,NombreEmpleado as NombreMedico, NombreSubServicio as Origen,DatosClinicos,
			NombreServicio as Precedencia, D.IdNumeroExp, 
              CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) as NombrePaciente,
		B.FechaSolicitud,(year(CURRENT_DATE)-year(FechaNacimiento))as Edad,
		 if(Sexo=1,'Masculino','Femenino') as Sexo, B.estado as Estado
		from sec_historial_clinico as A
		inner join sec_solicitudestudios  as B on A.IdHistorialClinico= B.IdHistorialClinico
		inner join mnt_empleados as C on A.IDEmpleado= C.IdEmpleado
		inner join mnt_expediente D on A.IdNumeroExp= D.IdNumeroExp
		inner join mnt_datospaciente E on D.IdPaciente=E.IdPaciente  
		inner join mnt_subservicio F on F.IdSubServicio= A.IdSubServicio
		inner join mnt_servicio G on G.IdServicio= F.IdServicio
		where B.IdServicio ='DCOLAB' and A.IdNumeroExp='$idexpediente'
		and B.IdSolicitudEstudio=$idsolicitud";
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
	   $query = "select C.IdNumeroExp, B.IdArea as IdArea ,B.IdExamen as IdExamen,NombreExamen,Indicacion,FechaSolicitud,
	   CASE A.EstadoDetalle 
	WHEN 'D'  THEN 'Digitado'
	WHEN 'PM' THEN 'Muestra Procesada'
	WHEN 'RM' THEN 'Muestra Rechazada'    
	WHEN 'RC' THEN 'Resultado Completo' end as Estado
from sec_detallesolicitudestudios A
inner join sec_solicitudestudios C on A.IdSolicitudEstudio=C.IdSolicitudEstudio
inner join lab_examenes B on A.idExamen=B.IdExamen
where C.IdServicio ='DCOLAB' and C.IdNumeroExp='$idexpediente' and C.IdSolicitudEstudio=$idsolicitud 
order by B.IdArea";
	  	$result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }
        
function ObtenerServicio($idSubEsp){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "select  IdServicio from mnt_subservicio where IdSubServicio=$idSubEsp";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbMed($idSubEsp,$lugar)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   INNER JOIN mnt_usuarios ON mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado 
				WHERE mnt_usuarios.IdSubServicio=$idSubEsp  AND mnt_empleados.IdEstablecimiento=$lugar ORDER BY mnt_empleados.NombreEmpleado";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}
	
function LlenarCmbMedicos($lugar)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   WHERE  mnt_empleados.IdEstablecimiento=$lugar  AND IdTipoEmpleado='MED' AND IdEmpleado<>'MED0000'
			   ORDER BY mnt_empleados.NombreEmpleado";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

	
function LlenarMedico($IdSubServicio,$lugar)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
	        $sqlText= "SELECT mnt_empleados.NombreEmpleado, mnt_empleados.IdEmpleado 
		FROM mnt_empleados 
		INNER JOIN mnt_usuarios ON mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado
		WHERE mnt_usuarios.IdEstablecimiento=$lugar AND mnt_usuarios.IdSubServicio=$IdSubServicio ORDER BY NombreEmpleado";			
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
 }
 //}
}//CLASE
?>
