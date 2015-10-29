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
		$conNom  = /*"SELECT mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";*/
                        
                        "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
		$resul = pg_query($conNom);
	}
 return $resul;
}

/*function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= //"SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
                            "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) ;
	}
	return $dt;
}*/

function LlenarCmbEstablecimiento($Idtipoesta){
    $con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";		
		$dt = pg_query($sqlText) ;

	}
	return $dt;
}

 function LlenarTodosEstablecimientos() {
        
        $con = new ConexionBD;
        if ($con->conectar() == true) {
            $sqlText = "SELECT id, nombre FROM ctl_establecimiento ORDER BY nombre";
            $dt = pg_query($sqlText) ;
        }
        return $dt;
    }


function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= /*"SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
			FROM mnt_subservicio 
			INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
			WHERE mnt_subservicio.IdServicio='$IdServ' AND IdEstablecimiento=$lugar 
			ORDER BY NombreSubServicio";	*/
                        
                        "with tbl_servicio as (select mnt_3.id,
                        CASE
                        WHEN mnt_3.nombre_ambiente IS NOT NULL
                        THEN  	
                                CASE WHEN id_servicio_externo_estab IS NOT NULL
                                        THEN mnt_ser.abreviatura ||'-->' ||mnt_3.nombre_ambiente
                                        ELSE mnt_3.nombre_ambiente
                                END

                        ELSE
                        CASE WHEN id_servicio_externo_estab IS NOT NULL 
                                THEN mnt_ser.abreviatura ||'--> ' || cat.nombre
                             WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=cat.nombre)
                                THEN cmo.nombre||'-'||cat.nombre
                        END
                        END AS servicio 
                        from ctl_atencion cat 
                        join mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                        join mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                        LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                        LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                        join mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                        join ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                        where  mnt_2.id=$IdServ
                        and mnt_3.id_establecimiento=$lugar
                        order by 2)
                        select id, servicio from tbl_servicio where servicio is not null";
                        
		$dt = pg_query($sqlText) ;
	}
	return $dt;
}

function ExamenesPorArea($idarea,$lugar)
{
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = /*"SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
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
	 //FUNCION PARA MOSTRAR SUBESPECIALIDADES
	  function LeerEspecialidades()
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query= "select ";
	   /*"select IdSubServicio,NombreSubServicio from mnt_subservicio
	             where IdServicio='CONEXT' order by NombreSubServicio";*/
	 
	     $result = @pg_query($query);
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
   $query = $query_search ." LIMIT $RegistrosAMostrar  OFFSET $RegistrosAEmpezar";
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
	  	$result = @pg_query($query);
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
		$dt = pg_query($sqlText) ;//;
                return $dt;
	}
	
}

function LlenarCmbMed($idSubEsp,$lugar)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= /*"SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   INNER JOIN mnt_usuarios ON mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado 
				WHERE mnt_usuarios.IdSubServicio=$idSubEsp  AND mnt_empleados.IdEstablecimiento=$lugar ORDER BY mnt_empleados.NombreEmpleado";*/
                        
                       "select mem.id as idemp, (nombre||' '||apellido) as nombre, idempleado  
from mnt_empleado_especialidad_estab empest
join mnt_empleado mem on (empest.id_empleado=mem.id)
where id_aten_area_mod_estab=126";
                        
		$dt = pg_query($sqlText) ;
	}
	return $dt;
}
	
function LlenarCmbMedicos($idSubEsp)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= /*"SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   WHERE  mnt_empleados.IdEstablecimiento=$lugar  AND IdTipoEmpleado='MED' AND IdEmpleado<>'MED0000'
			   ORDER BY mnt_empleados.NombreEmpleado";*/
                        
                         "select mem.id as idemp, nombreempleado as nombre, idempleado  
from mnt_empleado_especialidad_estab empest
join mnt_empleado mem on (empest.id_empleado=mem.id)
where id_aten_area_mod_estab=$idSubEsp  order by nombreempleado ";
                $dt=  pg_query($sqlText);
                if (!$dt)
                    return false;
                else
                    return $dt;
                
		//$dt = pg_query($sqlText) ;
	}
//	return $dt;
}

/*select count (mem.id) as cantidad
from mnt_empleado_especialidad_estab empest 
join mnt_empleado mem on (empest.id_empleado=mem.id) where id_aten_area_mod_estab=39*/

function cantidadMedicos($idSubEsp)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= /*"SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   WHERE  mnt_empleados.IdEstablecimiento=$lugar  AND IdTipoEmpleado='MED' AND IdEmpleado<>'MED0000'
			   ORDER BY mnt_empleados.NombreEmpleado";*/
                        
                         "select count (mem.id) as cantidad
                            from mnt_empleado_especialidad_estab empest 
                            join mnt_empleado mem on (empest.id_empleado=mem.id) where id_aten_area_mod_estab=$idSubEsp";
                $dt=  pg_query($sqlText);
                if (!$dt)
                    return false;
                else
                    return $dt;
                
		//$dt = pg_query($sqlText) ;
	}
//	return $dt;
}

	
function LlenarMedico($IdSubServicio,$lugar)		
 {	$con = new ConexionBD;
	if($con->conectar()==true){
	         $sqlText= /*"SELECT mnt_empleados.NombreEmpleado, mnt_empleados.IdEmpleado 
		FROM mnt_empleados 
		INNER JOIN mnt_usuarios ON mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado
		WHERE mnt_usuarios.IdEstablecimiento=$lugar AND mnt_usuarios.IdSubServicio=$IdSubServicio ORDER BY NombreEmpleado";*/
                                              "select mem.id as idemp, (nombre||' '||apellido) as nombre, idempleado  
from mnt_empleado_especialidad_estab empest
join mnt_empleado mem on (empest.id_empleado=mem.id)
where id_aten_area_mod_estab=$IdSubServicio";
		$dt = pg_query($sqlText) ;
	}
	return $dt;
 }
 //}
}//CLASE
?>
