<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsReporteResultados
{
	 //constructor	
	 function clsReporteResultados()
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
                        
		$resul = pg_query($conNom);
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) ;
	}
	return $dt;
}


function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= "WITH tbl_servicio AS (
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

//FUNCION PARA OBTENER EL CÓDIGO DEL ESTANDAR
function Obtener_Estandar($idexamen){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       $query="SELECT idestandar FROM lab_conf_examen_estab 
                    INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id=lab_conf_examen_estab .idexamen 
                    INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
                    WHERE lab_conf_examen_estab.id=$idexamen";
	 $result = @pg_query($query);
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
		 $query = /*"SELECT lab_examenes.IdExamen,NombreExamen FROM lab_examenes 
		       INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
		       WHERE IdEstablecimiento = $lugar AND IdArea='$idarea'
		       AND lab_examenesxestablecimiento.Condicion='H' ORDER BY NombreExamen ASC ";*/
                         
                    "SELECT lcee.id,lcee.nombre_examen 
                    FROM mnt_area_examen_establecimiento maees
                    INNER JOIN lab_conf_examen_estab lcee ON maees.id= lcee.idexamen 
                        WHERE maees.id_area_servicio_diagnostico=$idarea
                        AND maees.id_establecimiento=$lugar
                        AND lcee.condicion          = 'H'
                        ORDER BY lcee.nombre_examen asc";
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
    {  //creamos el objeto $con a partir de la clase ConexionBD
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
	  $query ="SELECT t09.id as id,
                   t02.id_establecimiento,
                   TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                    t01.id as iddetallesolicitud,
                    t02.id as idsolicitudestudio,
                    t04.idplantilla  as idplantilla,
                    t13.nombre AS nombreservicio, 
		    t19.nombre AS sexo,
                    t24.nombreempleado as medico,
                    CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                    t07.apellido_casada) AS paciente,
                    t07.conocido_por as conocidox,
                    REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(t07.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') as edad,
                                 (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                    t11.nombre AS nombresubservicio,
                    t22.sct_name_es AS diagnostico, 
                    t23.peso AS peso,
                    t23.talla AS talla,
                    t04.nombre_examen AS nombre_examen,
                    t04.codigo_examen AS codigo_examen,
                    t25.idarea AS codigo_area,
                    t25.nombrearea as nombre_area,t25.id AS idarea,
                    CASE t01.estadodetalle 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
			
                    CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado1, 
                    t01.indicacion AS indicacion,
                    t01.idempleado AS idempleado,t01.id_conf_examen_estab as idexamen,t07.fecha_nacimiento as fechanac,
                    t19.id as idsexo, date (current_date)  - date (t07.fecha_nacimiento) as dias,t06.numero as expediente
            FROM sec_detallesolicitudestudios           t01 
            INNER JOIN sec_solicitudestudios            t02 	ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra             t03 	ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab            t04 	ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento  t05 	ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente                   t06 	ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente                     t07 	ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico    t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico            t09 	ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab          t10 	ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion                     t11 	ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab               t12 	ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion                t13 	ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo        t15 	ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico  t16 	ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud                t17 	ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico  t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo                         t19 	ON (t19.id = t07.id_sexo)
            LEFT JOIN sec_diagnostico_paciente		t21     ON (t21.id_historial_clinico=t09.id)
            LEFT JOIN mnt_snomed_cie10 			t22     ON (t22.id=t21.id_snomed)
            LEFT JOIN sec_signos_vitales                t23     ON (t23.id_historial_clinico=t09.id)
            LEFT JOIN mnt_empleado 			t24     ON (t09.id_empleado=t24.id) 
            LEFT JOIN ctl_area_servicio_diagnostico    t25     ON (t25.id=t05.id_area_servicio_diagnostico)
            
            WHERE  t02.id=$idsolicitud and  t06.numero='$idexpediente'

UNION

            SELECT t09.id as iddato, 
                    t02.id_establecimiento,
                    TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
                   t01.id as iddetallesolicitud,
                   t02.id as idsolicitudestudio,
                   t04.idplantilla  as idplantilla,
                   t13.nombre AS nombreservicio, 
		   t19.nombre AS sexo,
                   t24.nombreempleado as medico,
                   CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                   t07.apellido_casada) AS paciente, 
                   t07.primer_nombre,
                    REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(t07.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') as edad,
                   
                   
		   (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
		   t11.nombre AS nombresubservicio, 
		   t22.sct_name_es AS diagnostico, 
                   t23.peso as peso,
                    t23.talla as talla,
                    t04.nombre_examen as nombre_examen,
                   t04.codigo_examen as codigo_examen,
                   t25.idarea as codigo_area,
                   t25.nombrearea as nombre_area,t25.id as idarea, 
                    CASE t01.estadodetalle
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
                        
                    CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado1, 

                    t01.indicacion as indicacion,
                    t01.idempleado  as idempleado,t01.id_conf_examen_estab as idexamen,t07.fecha_nacimiento as fechanac,
                    t19.id as idsexo,
                    date (current_date)  - date (t07.fecha_nacimiento) as dias,t06.numero as expediente
            FROM sec_detallesolicitudestudios           t01 
            INNER JOIN sec_solicitudestudios            t02 	ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra             t03 	ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab            t04	ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento  t05  	ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia              t09 	ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido          t06 	ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido            t07 	ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico    t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab          t10 	ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion                     t11 	ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab               t12 	ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion                t13 	ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t09.id_establecimiento)
	    INNER JOIN ctl_examen_servicio_diagnostico  t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo                         t19 	ON (t19.id = t07.id_sexo)
            left  join sec_diagnostico_paciente		t21     on (t21.id_historial_clinico=t09.id)
            left join mnt_snomed_cie10 			t22     on (t22.id=t21.id_snomed)
            left join sec_signos_vitales  		t23      on (t23.id_historial_clinico=t09.id)
            left  join mnt_empleado 			t24     on (t09.id_empleado=t24.id) 
            inner join ctl_area_servicio_diagnostico    t25     on (t25.id=t05.id_area_servicio_diagnostico)
            
            WHERE   t02.id=$idsolicitud and  t06.numero='$idexpediente'  order by codigo_area";
                 
		//echo $query;
		$result = @pg_query($query);
		
     if (!$result)
      return false;
     else
       return $result;	   
	}
 }

/*//FUNCION PARA MOSTRAR LOS DATOS Resultado Plantilla A  
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
	$result = @pg_query($query);
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
       // echo $query;
	$result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;	   
    }
 }*/


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
	  $result = @pg_query($query);
	  if (!$result)
	       return false;
	  else
	       return $result;	   
   }
 }

//FUNCION PARA MOSTRAR DATOS FIJOS DE LA PLANTILLA
 function MostrarDatosFijosPlantillaA($idexamen,$lugar,$iddetalle, $sexo, $idedad)
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	$query=/*"SELECT lab_examenes.IdExamen,NombreExamen,Unidades,RangoInicio,RangoFin
				FROM lab_datosfijosresultado 
				INNER JOIN lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
				INNER JOIN lab_resultados ON lab_examenes.IdExamen=lab_resultados.IdExamen  
				WHERE lab_datosfijosresultado.IdEstablecimiento=$lugar AND  lab_examenes.IdExamen='$idexamen' 
				AND IdSolicitudEstudio=$idsolicitud
				AND DATE_FORMAT(lab_resultados.FechaHoraReg,'%Y/%m/%d') BETWEEN lab_datosfijosresultado.FechaIni AND IF(lab_datosfijosresultado.FechaFin ='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)";*/
                  
                   
                  
                  "select 
                  t02.id, 
                  t03.id, 
                  t02.nombre_examen as nombre_examen, 
                  t01.unidades as unidades,
                  t01.rangoinicio as rangoinicio, 
                  t01.rangofin as rangofin 
                  from lab_datosfijosresultado 	  t01 
                  inner join lab_conf_examen_estab  t02 on (t02.id=t01.id_conf_examen_estab) 
                  left join lab_resultados 	  t03 on (t02.id=t03.idexamen) 
                  where t01.idestablecimiento=$lugar 
                  and t02.id=$idexamen 
                  and t03.iddetallesolicitud=$iddetalle 
                  and (current_date between fechaini and (case when fechafin is null then current_date else fechafin end))
                and (t01.idsexo is null or t01.idsexo=$sexo)
                and (idedad=4 or idedad=$idedad)";
                                        
	     $result = @pg_query($query);
             //echo $query;
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
   $query = "SELECT lab_elementos.id as idelemento,lab_elementos.elemento,lab_elementos.unidadelem,observelem,nombrearea,lab_elementos.fechaini,fechafin,subelemento  
                FROM lab_elementos 
                INNER JOIN lab_conf_examen_estab ON lab_elementos.id_conf_examen_estab=lab_conf_examen_estab.id
                INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id=lab_conf_examen_estab.idexamen 
                INNER JOIN ctl_area_servicio_diagnostico ON ctl_area_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_area_servicio_diagnostico
                INNER JOIN lab_resultados ON lab_resultados.idexamen= lab_conf_examen_estab.id
                WHERE lab_elementos.id_conf_examen_estab=$idexamen
                AND iddetallesolicitud=$iddetalle AND lab_elementos.idestablecimiento=$lugar
                AND lab_resultados.fecha_resultado BETWEEN lab_elementos.fechaini AND CASE WHEN fechafin IS NULL THEN CURRENT_DATE ELSE lab_elementos.fechafin END ORDER BY orden";
        

//echo $query;
     $result = @pg_query($query);
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
	INNER JOIN lab_detalleresultado ON lab_resultados.id=lab_detalleresultado.idresultado
	WHERE lab_resultados.idsolicitudestudio=$idsolicitud AND lab_resultados.iddetallesolicitud=$iddetalle
        AND lab_detalleresultado.IdElemento=$idelemento"; 
	 $result = @pg_query($query);
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
        $query ="SELECT lab_resultados.id as idresultado, 
            lab_detalleresultado.id as iddetalleresultado,
            lab_subelementos.idelemento,
            lab_detalleresultado.idsubelemento,
            lab_subelementos.subelemento,
            lab_detalleresultado.resultado,
            lab_subelementos.unidad,
            lab_subelementos.rangoinicio,
            lab_subelementos.rangofin, 
            lab_detalleresultado.observacion 
            FROM lab_resultados 
              INNER JOIN lab_detalleresultado   ON lab_resultados.id=lab_detalleresultado.idresultado 
              INNER JOIN lab_subelementos       ON lab_detalleresultado.idsubelemento=lab_subelementos.id
              WHERE idsolicitudestudio=$idsolicitud  
              AND iddetallesolicitud=$iddetalle  
              AND lab_subelementos.idelemento=$idelemento 
              AND (lab_subelementos.idsexo=(select id from ctl_sexo where nombre='$sexo')OR lab_subelementos.idsexo is NULL) 
              AND (idedad=4 OR idedad=$idedad)
              AND lab_subelementos.idestablecimiento=$lugar 
             ORDER BY lab_subelementos.orden ";
         /*"SELECT lab_resultados.IdResultado, lab_detalleresultado.IdDetalleResultado, 
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
	      ORDER BY IdSubElemento ";*/
       //echo $query;

     $result = @pg_query($query);
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
         $query = "SELECT lcee.id,
                    lcee.nombre_examen  as nombre_examen,
                    casd.nombrearea  as nombrearea
                    FROM mnt_area_examen_establecimiento maees
                    INNER JOIN lab_conf_examen_estab lcee           ON  maees.id= lcee.idexamen 
                    inner join ctl_area_servicio_diagnostico casd   on  maees.id_area_servicio_diagnostico=casd.id
                    WHERE lcee.id=$idexamen";
             
     $result = @pg_query($query);
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
        $query ="SELECT 
                    TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY HH12:MI') AS fecharecepcion,
                    t06.numero AS idnumeroexp,
                    t01.id as iddetallesolicitud,
                    t02.id as idsolicitudestudio,
                    t04.idplantilla  as idplantilla,
                    t13.nombre AS nombreservicio, 
		    t19.nombre AS sexo,
                    t24.nombreempleado as empleado,
                    CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
                    t07.apellido_casada) AS paciente,
                    t07.conocido_por as conocodidox,
                    REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(t07.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') as edad,
                                 (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
                    t11.nombre AS nombresubservicio,
                    t22.sct_name_es AS diagnostico,
                    t23.peso as peso,
                    t23.talla as talla,
                    t04.nombre_examen as nombre_examen,
                    t04.codigo_examen as codigo_examen,
                    t25.idarea as codigo_area,
                    t25.nombrearea as nombre_area,
                    CASE t01.estadodetalle 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
			
                    CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado1, 
                    t01.indicacion as indicacion,
                    t01.idempleado as idempleado,
                    t26.resultado as resultado,
                    t26.lectura as lectura,
                    t26.interpretacion as interpretacion,
                    t26.observacion as observacion,
                    TO_CHAR(t01.f_tomamuestra, 'DD/MM/YYYY HH12:MI') AS f_tomamuestra
            FROM sec_detallesolicitudestudios           t01 
            INNER JOIN sec_solicitudestudios            t02 	ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra             t03 	ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab            t04 	ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento  t05 	ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente                   t06 	ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente                     t07 	ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico    t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico            t09 	ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab          t10 	ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion                     t11 	ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab               t12 	ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion                t13 	ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo        t15 	ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico  t16 	ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud                t17 	ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico  t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo                         t19 	ON (t19.id = t07.id_sexo)
            LEFT JOIN sec_diagnostico_paciente		t21     ON (t21.id_historial_clinico=t09.id)
            LEFT JOIN mnt_snomed_cie10 			t22     ON (t22.id=t21.id_snomed)
            LEFT JOIN sec_signos_vitales                t23     ON (t23.id_historial_clinico=t09.id)
            LEFT JOIN ctl_area_servicio_diagnostico    t25      ON (t25.id=t05.id_area_servicio_diagnostico)
            LEFT JOIN lab_resultados                    t26  	ON (t26.iddetallesolicitud=t01.id)
            LEFT JOIN  mnt_empleado                     t24 	ON (t24.id=t26.idempleado)
                WHERE   t02.id                  =$idsolicitud   
                AND     t01.id                  = $iddetalle      
                AND     t02.id_establecimiento  = $lugar 

UNION

        SELECT 
            TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
            t06.numero AS idnumeroexp,
            t01.id as iddetallesolicitud,
            t02.id as idsolicitudestudio,
            t04.idplantilla  as idplantilla,
            t13.nombre AS nombreservicio, 
            t19.nombre AS sexo,
            t24.nombreempleado as empleado,
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido,
            t07.apellido_casada) AS paciente, 
            t07.primer_nombre,
                    REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    REPLACE(
                                                        AGE(t07.fecha_nacimiento::timestamp)::text,
                                                    'years', 'años'),
                                                'year', 'año'),
                                            'mons', 'meses'),
                                        'mon', 'mes'),
                                    'days', 'días'),
                                 'day', 'día') as edad,
                   
                   
	    (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext,
	    t11.nombre AS nombresubservicio, 
	    t22.sct_name_es AS diagnostico,
            t23.peso as peso,
            t23.talla as talla,
            t04.nombre_examen as nombre_examen,
            t04.codigo_examen as codigo_examen,
            t25.idarea as codigo_area,
            t25.nombrearea as nombre_area,
                CASE t01.estadodetalle
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
                        
                CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado1, 

		t01.indicacion as indicacion,
                t01.idempleado  as idempleado,
                t26.resultado as resultado,
                t26.lectura as lectura,
                t26.interpretacion as interpretacion,
                t26.observacion as observacion,TO_CHAR(t01.f_tomamuestra, 'DD/MM/YYYY HH12:MI') AS f_tomamuestra
            FROM sec_detallesolicitudestudios           t01 
            INNER JOIN sec_solicitudestudios            t02 	ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra             t03 	ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab            t04	ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento  t05  	ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia              t09 	ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido          t06 	ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido            t07 	ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico    t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab          t10 	ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion                     t11 	ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab               t12 	ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion                t13 	ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento              t14 	ON (t14.id = t09.id_establecimiento)
	    INNER JOIN ctl_examen_servicio_diagnostico  t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo                         t19 	ON (t19.id = t07.id_sexo)
            left  join sec_diagnostico_paciente		t21     on (t21.id_historial_clinico=t09.id)
            left join mnt_snomed_cie10 			t22	on (t22.id=t21.id_snomed)
            left join sec_signos_vitales  		t23 	on (t23.id_historial_clinico=t09.id)
             inner join ctl_area_servicio_diagnostico   t25     on (t25.id=t05.id_area_servicio_diagnostico)
            left join lab_resultados                    t26  	on (t26.iddetallesolicitud=t01.id)
            left join  mnt_empleado                     t24 	on (t24.id=t26.idempleado)
                    WHERE   t02.id                  =$idsolicitud   
                    and     t01.id                  = $iddetalle      
                    and     t02.id_establecimiento  = $lugar ";
             
             
//echo $query;
     $result = @pg_query($query);
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
	   NumeroMuestra,DATE_FORMAT(lab_resultados.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,mnt_establecimiento.Nombre,
           DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento,
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
 
     $result = @pg_query($query);
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
  	$result = @pg_query($query);
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
      $query = "SELECT lab_examenes.IdArea 
                FROM sec_detallesolicitudestudios 
		INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen 
                WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle";
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

/*Para verificar si existe el resultado*/
function VerificaDetalle($idsolicitud,$iddetalle){
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   	$query ="select * from lab_resultados where IdSolicitudEstudio=$idsolicitud and IdDetalleSolicitud=$iddetalle";
   	$result = @pg_query($query);
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
	 $result = @pg_query($query);
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
	 $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
   }
   
 }
 
 function CalculoDias($fechanac){
     $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query=" SELECT TO_CHAR((now( )::timestamp - '$fechanac'::timestamp),'DD')";
                //"SELECT DATEDIFF(NOW( ),'$fechanac')";
    // echo $query;
	 $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function ObtenerCodigoRango($dias){
   $con = new ConexionBD;
   if($con->conectar()==true){  
      $query="SELECT * FROM ctl_rango_edad WHERE cod_modulo='LAB' AND nombre<>'Todos' AND $dias BETWEEN edad_minima_dias AND edad_maxima_dias ";
              
        $result = @pg_query($query);
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
	$result = @pg_query($query);
	if (!$result)
	   return false;
	else 
	   return $result;
   }
}


function ObtenerObservacion($idsolicitud,$iddetalle){
    $con = new ConexionBD;
    if($con->conectar()==true)
    {
    	$query ="SELECT t02.observacion as observacion from lab_resultados t01 
		LEFT JOIN lab_observaciones t02 on (t02.id=t01.id_observacion)
		LEFT JOIN sec_solicitudestudios  t03 on (t01.idsolicitudestudio=t03.id)
		WHERE t03.id=$idsolicitud and t01.iddetallesolicitud=$iddetalle";
                
	$result = @pg_query($query);
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
			 $result = @pg_query($query);
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
    $query ="SELECT idelemento,cantidad,lab_elementostincion.elementotincion,
            lab_cantidadestincion.cantidadtincion
	    FROM lab_detalleresultado 
	    INNER JOIN lab_elementostincion  ON lab_detalleresultado.id_elementotincion=lab_elementostincion.id
 	    INNER JOIN lab_cantidadestincion  ON lab_cantidadestincion.id=lab_detalleresultado.idcantidad
         WHERE lab_detalleresultado.idresultado=$idresultado";
    
   $result = @pg_query($query);
	 
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
	 $query ="SELECT id as idresultado FROM lab_resultados where idsolicitudestudio=$idsolicitud and iddetallesolicitud=$iddetalle";
	  $result = @pg_query($query);
	 
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
	 $query ="SELECT lab_procedimientosporexamen.id,lab_procedimientosporexamen.nombreprocedimiento, 
                lab_detalleresultado.resultado,lab_procedimientosporexamen.rangoinicio,
                lab_procedimientosporexamen.rangofin,lab_procedimientosporexamen.unidades,lab_detalleresultado.observacion 
                FROM lab_resultados 
                INNER JOIN lab_detalleresultado ON lab_detalleresultado.idresultado=lab_resultados.id 
                INNER JOIN lab_procedimientosporexamen ON lab_detalleresultado.idprocedimiento=lab_procedimientosporexamen.id 
                WHERE lab_resultados.iddetallesolicitud=$iddetalle AND lab_resultados.idsolicitudestudio=$idsolicitud 
                AND CURRENT_DATE BETWEEN lab_procedimientosporexamen.fechaini 
                AND CASE WHEN fechafin IS NULL THEN CURRENT_DATE ELSE lab_procedimientosporexamen.fechafin END
                ORDER BY id ASC ";

	 $result = @pg_query($query);
	 
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
  $result = @pg_query($query);
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
   {  $query = "SELECT Count(*) FROM lab_resultados WHERE idsolicitudestudio=$idsolicitud AND 		
		iddetallesolicitud=$iddetalle";
    $result = @pg_query($query);
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
    $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
 }
 
//FUNCION FECHA DE RESULTADO
function ObtenerFechaResultado($idsolicitud,$IdExamen,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
      $query = "SELECT TO_CHAR(fecha_resultado,'dd/mm/YYYY HH12:MI:SS') AS fecharesultado
                FROM lab_resultados 
                WHERE idsolicitudestudio=$idsolicitud AND idestablecimiento=$lugar 
                AND idexamen=$IdExamen";
     $result = pg_query($query);
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
   { $query = "SELECT resultado FROM lab_resultados WHERE idsolicitudestudio=$idsolicitud AND 		
		iddetallesolicitud=$iddetalle";
    $result = @pg_query($query);
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
   { $query = "SELECT id,resultado FROM lab_resultados WHERE idsolicitudestudio=$idsolicitud AND 		
		iddetallesolicitud=$iddetalle";
    $result = @pg_query($query);
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
	     $result = pg_query($query);
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
    $query = "SELECT idantibiotico,antibiotico,valor,lab_resultadosportarjeta.id_posible_resultado,posible_resultado
              FROM lab_resultados 
              LEFT JOIN lab_detalleresultado ON lab_detalleresultado.idresultado=lab_resultados.id 
              LEFT JOIN lab_resultadosportarjeta ON lab_resultadosportarjeta.iddetalleresultado=lab_detalleresultado.id 
              INNER JOIN lab_posible_resultado ON lab_posible_resultado.id=lab_resultadosportarjeta.id_posible_resultado
              LEFT JOIN lab_antibioticos ON lab_antibioticos.id=lab_resultadosportarjeta.idantibiotico
	      WHERE idsolicitudestudio=$idsolicitud and iddetallesolicitud=$iddetalle";
     
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

  function contar_resultados($idsolicitud,$idexamen){
     $con = new ConexionBD;
    if($con->conectar()==true)
   {
        $query = "SELECT lab_resultados.id as idresultado,resultado,observacion,nombreempleado 
               FROM lab_resultados 
               INNER JOIN mnt_empleado ON mnt_empleado.id= lab_resultados.idempleado
               WHERE idsolicitudestudio=$idsolicitud AND idexamen=$idexamen order by lab_resultados.id asc ";
      $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
    
}

function obtener_detalle_resultado($idresulatado){
    
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT lab_detalleresultado.id as iddetalleresultado,idtarjeta,nombretarjeta,idbacteria,bacteria,cantidad 
               FROM lab_detalleresultado 
               INNER JOIN lab_tarjetasvitek ON  lab_tarjetasvitek.id=lab_detalleresultado.idtarjeta
               INNER JOIN lab_bacterias ON lab_bacterias.id= lab_detalleresultado.idbacteria
               WHERE idresultado=$idresulatado order by lab_detalleresultado.id asc";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function obtener_resultadoxtarjeta($iddetalleresultado){
    $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT lab_resultadosportarjeta.idantibiotico,antibiotico,resultado,valor,id_posible_resultado,posible_resultado 
FROM lab_resultadosportarjeta 
INNER JOIN lab_antibioticos ON lab_antibioticos.id=lab_resultadosportarjeta.idantibiotico
INNER JOIN lab_posible_resultado ON lab_posible_resultado.id=lab_resultadosportarjeta.id_posible_resultado 
WHERE iddetalleresultado=$iddetalleresultado order by lab_resultadosportarjeta.id asc";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    
}
}
  
  
  function LeerResultadosAntibioticosxId($idsolicitud,$iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     echo $query = /*"	SELECT lab_resultados.IdResultado, 						 	 
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
	WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle AND lab_resultados.IdResultado=$Idresultado";*/
             
             "select t06.antibiotico as antibiotico, 
                     t05.resultado as resultado, 
                     t02.cantidad as cantidad, 
                     t04.bacteria as bacteria 
	from lab_resultados 			t01 
	left  join  lab_detalleresultado 	t02  	on (t02.idresultado=t01.id)
	left  join  lab_tarjetasvitek 		t03 	on (t03.id=t02.idtarjeta)
	left  join  lab_bacterias 		t04  	on (t04.id=t02.idbacteria)
        left  join  lab_resultadosportarjeta    t05 	on (t05.iddetalleresultado=t02.id)
        left  join  lab_antibioticos		t06	on (t06.id=t05.idantibiotico)
	left  join  mnt_empleado		t07     on (t07.id=t01.idempleado)
	where t01.idsolicitudestudio=$idsolicitud  and t01.iddetallesolicitud=$iddetalle  and 
	t01.id=(select id from lab_resultados where idsolicitudestudio=$idsolicitud   and iddetallesolicitud=$iddetalle)";
             
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
  function nombre_resultado($idresultado){
      $con = new ConexionBD;
    if($con->conectar()==true)
    {
    $query = "SELECT posible_resultado,descripcion FROM lab_posible_resultado WHERE id=$idresultado";
       $result = pg_query($query);
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
     $query = /*"SELECT lab_resultados.IdExamen,lab_examenes.NombreExamen,lab_resultados.Resultado,
	       lab_detalleresultado.IdTarjeta,lab_tarjetasvitek.NombreTarjeta,lab_detalleresultado.IdBacteria,
	       lab_bacterias.Bacteria,lab_detalleresultado.Cantidad
	       FROM lab_resultados 
	       INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	       INNER JOIN lab_bacterias ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
	       INNER JOIN lab_tarjetasvitek ON lab_detalleresultado.IdTarjeta=lab_tarjetasvitek.IdTarjeta
	       INNER JOIN lab_examenes ON lab_examenes.IdExamen=lab_resultados.IdExamen
	       WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle";*/
             
               "select  t02.cantidad as cantidad , t03.bacteria as bacteria
	        from lab_resultados 		t01  
	        left join lab_detalleresultado 	t02 	on (t02.idresultado=t01.id)
	        left join lab_bacterias 	t03  	on (t02.idbacteria=t03.id)
	        left join lab_tarjetasvitek 	t04 	on (t02.idtarjeta=t04.id)
	        left join lab_conf_examen_estab t05	on (t01.idexamen=t05.id)
	        where t01.idsolicitudestudio=$idsolicitud  and t01.iddetallesolicitud=$iddetalle";
             
     $result = @pg_query($query);
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
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
/**************
 funcion.
 */
 /*
     * DESCRIPCIÓN: Método que permite obtener los resultados de los examenes de la solicitud de estudio de laboratorio
     *
     * PARÁMETROS DE ENTRADA:
     *                  -idHistorialClinico: id del HistorialClinico que generó la solicitud de estudios de laboratorio.
     *                  -idDatoReferencia:   id del DatoReferencia (para pacientes externos - referidos) que generó la solicitud de estudios.
     *                  -idEstablecimiento:  id del establecimiento que ha solicitado los examenes de laboratorio.
     * RETORNA:
     *                  -Los resultados de los examenes asociados a una solicitud de estudio.
     */

function obtenerResultadoSolicitudExamen($idHistorialClinico, $idDatoReferencia, $idEstablecimiento){
$con = new ConexionBD;
	if($con->conectar()==true){
         $sql="SELECT DISTINCT t05.id AS id_area,
                       t05.idarea AS codigo_area,
                       t05.nombrearea AS nombre_area,
                       t10.id AS id_plantilla,
                       t10.idplantilla AS codigo_plantilla,
                       t10.plantilla AS nombre_plantilla,
                       t06.id AS id_examen,
                       t06.idestandar AS codigo_examen,
                       t03.nombre_examen,
                       t07.id AS id_estado_solicitud,
                       t07.idestado AS codigo_estado_solicitud,
                       t07.descripcion AS nombre_estado_solicitud,
                       t08.id AS id_estado_detalle,
                       t08.idestado AS codigo_estado_detalle,
                       t08.descripcion AS nombre_estado_detalle,
                       t01.observacion AS detalle_observacion,
                       t01.f_tomamuestra AS fecha_toma_muestra,
                       t25.id AS id_empleado,
                       t25.nombreempleado AS nombre_empleado,
                       t26.id AS id_estado_rechazo,
                       t26.estado AS nombre_estado_rechazo,
                       t27.id AS id_observacion_rechazo,
                       t27.posible_observacion AS nombre_observacion_rechazo,
                       TO_CHAR(t17.fecha_resultado, 'dd/mm/yyyy') AS fecha_resultado,
                       CASE WHEN t03.urgente = 1 AND t02.estado = 1
                            THEN 'SI'
                            ELSE 'NO'
                       END AS urgente,
                       t17.id AS id_resultado,
                       t17.resultado,
                       t17.lectura,
                       t17.interpretacion,
                       t17.observacion AS resultado_observacion,
                       t18.id AS id_posible_resultado,
                       t18.posible_resultado AS nombre_posible_resultado,
                       CASE t10.idplantilla WHEN 'B'
                            THEN NULL
                            ELSE t19.unidades
                       END AS unidades,
                       CASE t10.idplantilla WHEN 'B'
                            THEN NULL
                            ELSE t19.rangoinicio
                       END AS rango_inicio,
                       CASE t10.idplantilla WHEN 'B'
                            THEN NULL
                            ELSE t19.rangofin
                       END AS rango_fin,
                       t20.id_elemento,
                       t20.nombre_elemento,
                       t20.id_resultado_elemento,
                       t20.id_detalleresultado_elemento,
                       t20.resultado_elemento,
                       t20.id_posible_resultado_elemento,
                       t20.nombre_posible_resultado_elemento,
                       t20.unidad_elemento,
                       t20.control_normal_elemento,
                       t20.pb_elemento_orden,
                       t20.id_subelemento,
                       t20.nombre_subelemento,
                       t20.id_resultado_subelemento,
                       t20.id_detalleresultado_subelemento,
                       t20.resultado_subelemento,
                       t20.id_posible_resultado_subelemento,
                       t20.nombre_posible_resultado_subelemento,
                       t20.unidad_subelemento,
                       t20.rango_inicio_subelemento,
                       t20.rango_fin_subelemento,
                       t20.control_normal_subelemento,
                       t20.pb_subelemento_orden,
                       /*t21.id_elemento_tincion,
                       t21.nombre_elemento_tincion,
                       t21.id_cantidad_tincion,
                       t21.nombre_cantidad_tincion,*/
                       t22.id_procedimiento,
                       t22.nombre_procedimiento,
                       t22.unidad_procedimiento,
                       t22.rango_inicio_procedimiento,
                       t22.rango_fin_procedimiento,
                       t22.resultado_procedimiento,
                       t22.id_posible_resultado_procedimiento,
                       t22.nombre_posible_resultado_procedimiento,
                       t22.control_diario_procedimiento,
                       t23.id_bacteria,
                       t23.nombre_bacteria,
                       t23.cantidad_bacterias,
                       t24.id AS id_observacion_bacteria,
                       t24.observacion AS nombre_observacion_bacteria,
                       t24.tiporespuesta AS codigo_observacion_bacteria,
                       t23.id_tarjeta,
                       t23.nombre_tarjeta,
                       t23.id_antibiotico,
                       t23.nombre_antibiotico,
                       t23.id_resultado_antibiotico,
                       t23.resultado_antibiotico,
                       t23.lectura_antibiotico,
                       t23.id_posible_resultado_antibiotico,
                       t23.nombre_posible_resultado_antibiotico
                FROM sec_detallesolicitudestudios          t01
                INNER JOIN sec_solicitudestudios           t02 ON (t02.id = t01.idsolicitudestudio)
                INNER JOIN lab_conf_examen_estab           t03 ON (t03.id = t01.id_conf_examen_estab)
                INNER JOIN mnt_area_examen_establecimiento t04 ON (t04.id = t03.idexamen)
                INNER JOIN ctl_area_servicio_diagnostico   t05 ON (t05.id = t04.id_area_servicio_diagnostico)
                INNER JOIN ctl_examen_servicio_diagnostico t06 ON (t06.id = t04.id_examen_servicio_diagnostico)
                INNER JOIN ctl_estado_servicio_diagnostico t07 ON (t07.id = t02.estado)
                INNER JOIN ctl_estado_servicio_diagnostico t08 ON (t08.id = t01.estadodetalle)
                INNER JOIN mnt_empleado                    t09 ON (t09.id = t01.idempleado)
                INNER JOIN lab_plantilla                   t10 ON (t10.id = t03.idplantilla)
                LEFT  JOIN sec_historial_clinico           t11 ON (t11.id = t02.id_historial_clinico)
                LEFT  JOIN mnt_expediente                  t12 ON (t12.id = t11.id_numero_expediente)
                LEFT  JOIN mnt_paciente                    t13 ON (t13.id = t12.id_paciente)
                LEFT  JOIN mnt_dato_referencia             t14 ON (t14.id = t02.id_dato_referencia)
                LEFT  JOIN mnt_expediente_referido         t15 ON (t15.id = t14.id_expediente_referido)
                LEFT  JOIN mnt_paciente_referido           t16 ON (t16.id = t15.id_referido)
                LEFT  JOIN lab_resultados                  t17 ON (t01.id = t17.iddetallesolicitud)
                LEFT  JOIN lab_posible_resultado           t18 ON (t18.id = t17.id_posible_resultado)
                LEFT  JOIN lab_datosfijosresultado         t19 ON (t03.id = t19.id_conf_examen_estab
                        AND (t19.idsexo IS NULL OR t19.idsexo = CASE WHEN t11.id IS NOT NULL THEN t13.id_sexo ELSE t16.id_sexo END)
                        AND (t19.idedad = 4 OR t19.idedad = (
                                    SELECT id
                                    FROM ctl_rango_edad
                                    WHERE (DATE(CURRENT_DATE) - CASE WHEN t11.id IS NOT NULL THEN t13.fecha_nacimiento ELSE t16.fecha_nacimiento END BETWEEN edad_minima_dias AND edad_maxima_dias)
                                    AND cod_modulo = 'LAB'
                                    AND id != 4
                                )
                            )
                        AND t19.fechafin IS NULL
                    )
                LEFT JOIN (
                    SELECT ti01.id AS id_elemento,
                           ti01.elemento AS nombre_elemento,
                           ti01.unidadelem AS unidad_elemento,
                           ti04.resultado AS resultado_elemento,
                           ti04.observacion AS control_normal_elemento,
                           ti04.idresultado AS id_resultado_elemento,
                           ti04.id AS id_detalleresultado_elemento,
                           ti06.id AS id_posible_resultado_elemento,
                           ti06.posible_resultado AS nombre_posible_resultado_elemento,
                           ti01.orden AS pb_elemento_orden,
                           ti02.id AS id_subelemento,
                           ti02.subelemento AS nombre_subelemento,
                           ti02.unidad AS unidad_subelemento,
                           ti02.rangoinicio AS rango_inicio_subelemento,
                           ti02.rangofin AS rango_fin_subelemento,
                           ti03.resultado AS resultado_subelemento,
                           ti03.observacion AS control_normal_subelemento,
                           ti03.idresultado AS id_resultado_subelemento,
                           ti03.id AS id_detalleresultado_subelemento,
                           ti05.id AS id_posible_resultado_subelemento,
                           ti05.posible_resultado AS nombre_posible_resultado_subelemento,
                           ti01.id_conf_examen_estab AS id_examen_elemento,
                           ti02.orden AS pb_subelemento_orden
                    FROM lab_elementos  ti01
                    INNER JOIN lab_subelementos      ti02 ON (ti01.id = ti02.idelemento)
                    LEFT  JOIN lab_detalleresultado  ti03 ON (ti02.id = ti03.idsubelemento)
                    LEFT  JOIN lab_detalleresultado  ti04 ON (ti01.id = ti04.idelemento)
                    LEFT  JOIN lab_posible_resultado ti05 ON (ti05.id = ti03.id_posible_resultado)
                    LEFT  JOIN lab_posible_resultado ti06 ON (ti06.id = ti04.id_posible_resultado)
                ) t20 ON (t03.id = t20.id_examen_elemento AND (t17.id = t20.id_resultado_elemento OR t17.id = t20.id_resultado_subelemento))
                /*LEFT JOIN (
                    SELECT ti07.id AS id_elemento_tincion,
                           ti07.elementotincion AS nombre_elemento_tincion,
                           ti11.id AS id_cantidad_tincion,
                           ti11.cantidadtincion AS nombre_cantidad_tincion,
                           ti09.idresultado AS id_resultado_tincion,
                           ti10.id AS id_posible_resultado_tincion,
                           ti10.posible_resultado AS nombre_posible_resultado_tincion,
                           ti08.id_conf_examen_estab AS id_examen
                    FROM lab_elementostincion              ti07
                    INNER JOIN lab_examen_elementostincion ti08 ON (ti07.id = ti08.id_elementostincion)
                    LEFT  JOIN lab_detalleresultado        ti09 ON (ti07.id = ti09.id_elementotincion)
                    LEFT  JOIN lab_posible_resultado       ti10 ON (ti10.id = ti09.id_posible_resultado)
                    LEFT  JOIN lab_cantidadestincion       ti11 ON (ti11.id = ti09.idcantidad)
                ) t21 ON (t03.id = t21.id_examen AND t17.id = t21.id_resultado_tincion)*/
                LEFT JOIN (
                    SELECT ti12.id AS id_procedimiento,
                           ti12.nombreprocedimiento AS nombre_procedimiento,
                           ti12.unidades AS unidad_procedimiento,
                           ti12.rangoinicio AS rango_inicio_procedimiento,
                           ti12.rangofin AS rango_fin_procedimiento,
                           ti13.resultado AS resultado_procedimiento,
                           ti14.id AS id_posible_resultado_procedimiento,
                           ti14.posible_resultado AS nombre_posible_resultado_procedimiento,
                           ti13.observacion AS control_diario_procedimiento,
                           ti13.idresultado AS id_resultado_procedimiento,
                           ti12.id_conf_examen_estab AS id_examen
                    FROM lab_procedimientosporexamen ti12
                    LEFT JOIN lab_detalleresultado   ti13 ON (ti12.id = ti13.idprocedimiento)
                    LEFT JOIN lab_posible_resultado  ti14 ON (ti14.id = ti13.id_posible_resultado)
                ) t22 ON (t03.id = t22.id_examen AND t17.id = t22.id_resultado_procedimiento)
                LEFT JOIN (
                    SELECT ti20.id AS id_bacteria,
                           ti20.bacteria AS nombre_bacteria,
                           ti19.cantidad AS cantidad_bacterias,
                           ti16.id AS id_tarjeta,
                           ti16.nombretarjeta AS nombre_tarjeta,
                           ti18.id AS id_antibiotico,
                           ti18.antibiotico AS nombre_antibiotico,
                           ti21.id AS id_resultado_antibiotico,
                           ti21.resultado AS resultado_antibiotico,
                           ti21.valor AS lectura_antibiotico,
                           ti22.id AS id_posible_resultado_antibiotico,
                           ti22.posible_resultado AS nombre_posible_resultado_antibiotico,
                           ti19.idresultado AS id_resultado
                    FROM lab_tarjetasvitek                ti16
                    INNER JOIN lab_antibioticosportarjeta ti17 ON (ti16.id = ti17.idtarjeta)
                    INNER JOIN lab_antibioticos           ti18 ON (ti18.id = ti17.idantibiotico)
                    LEFT  JOIN lab_detalleresultado       ti19 ON (ti16.id = ti19.idtarjeta)
                    LEFT  JOIN lab_bacterias              ti20 ON (ti20.id = ti19.idbacteria)
                    LEFT  JOIN lab_resultadosportarjeta   ti21 ON (ti19.id = ti21.iddetalleresultado AND ti18.id = ti21.idantibiotico)
                    LEFT  JOIN lab_posible_resultado      ti22 ON (ti22.id = ti21.id_posible_resultado)
                ) t23 ON (t17.id = t23.id_resultado)
                LEFT JOIN lab_observaciones       t24 ON (t24.id = t17.id_observacion)
                LEFT JOIN mnt_empleado            t25 ON (t25.id = t17.idempleado)
                LEFT JOIN lab_estado_rechazo      t26 ON (t26.id = t01.id_estado_rechazo)
                LEFT JOIN lab_posible_observacion t27 ON (t27.id = t01.id_posible_observacion)
                WHERE  t05.id<>14 AND  CASE WHEN $idHistorialClinico:: integer != 0
                          THEN t11.id = $idHistorialClinico
                          ELSE t14.id = $idDatoReferencia
                          END
                         AND t02.id_establecimiento_externo = $idEstablecimiento
                ORDER BY 1,id_estado_detalle, t20.pb_elemento_orden, t20.pb_subelemento_orden;";
           
                $result = pg_query($sql);
      if (!$result)
       return false;
     else
       return $result;
    }  
}



function obtenerDatosGenerales($idHistorialClinico, $idDatoReferencia, $idEstablecimiento)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
    
         $query = "WITH tbl_servicio AS (
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
                    FROM  ctl_atencion                  t01
                    INNER JOIN mnt_aten_area_mod_estab              t02 ON (t01.id = t02.id_atencion)
                    INNER JOIN mnt_area_mod_estab           t03 ON (t03.id = t02.id_area_mod_estab)
                    LEFT  JOIN mnt_servicio_externo_establecimiento t04 ON (t04.id = t03.id_servicio_externo_estab)
                    LEFT  JOIN mnt_servicio_externo             t05 ON (t05.id = t04.id_servicio_externo)
                    WHERE t02.id_establecimiento = $idEstablecimiento
                    ORDER BY 2)

                SELECT DISTINCT t02.nombre AS nombre_establecimiento,
                       TO_CHAR(t01.fecha_solicitud, 'DD/MM/YYYY') AS fecha_solicitud,
                       TO_CHAR(t21.fecharecepcion, 'DD/MM/YYYY') AS fecha_recepcion,
                       CASE WHEN t03.id IS NOT NULL
                            THEN t04.numero
                            ELSE t13.numero
                       END AS numero_expediente,
                       CASE WHEN t03.id IS NOT NULL
                            THEN CONCAT_WS(' ',t05.primer_nombre,t05.segundo_nombre,t05.tercer_nombre,t05.primer_apellido,t05.segundo_apellido,t05.apellido_casada)
                            ELSE CONCAT_WS(' ',t14.primer_nombre,t14.segundo_nombre,t14.tercer_nombre,t14.primer_apellido,t14.segundo_apellido,t14.apellido_casada)
                       END AS nombre_paciente,
                       CASE WHEN t03.id IS NOT NULL
                            THEN t06.id
                            ELSE t15.id
                       END AS id_sexo,
                       CASE WHEN t03.id IS NOT NULL
                            THEN t06.nombre
                            ELSE t15.nombre
                       END AS nombre_sexo,
                       CASE WHEN t03.id IS NOT NULL
                            THEN (DATE(CURRENT_DATE)  - t05.fecha_nacimiento)
                            ELSE (DATE(CURRENT_DATE)  - t14.fecha_nacimiento)
                       END AS edad_dias,
                       CASE WHEN t03.id IS NOT NULL
                            THEN t10.nombre
                            ELSE t19.nombre
                       END AS procedencia,
                       CASE WHEN t03.id IS NOT NULL
                            THEN t08.servicio
                            ELSE t17.servicio
                       END AS servicio,
                       CASE WHEN t03.id IS NOT NULL
                            THEN t11.nombreempleado
                            ELSE t20.nombreempleado
                       END AS nombre_empleado
                FROM sec_solicitudestudios          t01
                INNER JOIN ctl_establecimiento     t02 ON (t02.id = t01.id_establecimiento_externo)
                -- Historial Clinico
                LEFT  JOIN sec_historial_clinico   t03 ON (t03.id = t01.id_historial_clinico)
                LEFT  JOIN mnt_expediente          t04 ON (t04.id = t03.id_numero_expediente)
                LEFT  JOIN mnt_paciente            t05 ON (t05.id = t04.id_paciente)
                LEFT  JOIN ctl_sexo                t06 ON (t06.id = t05.id_sexo)
                LEFT  JOIN mnt_aten_area_mod_estab t07 ON (t07.id = t03.idsubservicio)
                LEFT  JOIN tbl_servicio            t08 ON (t08.id = t07.id AND t08.servicio IS NOT NULL)
                LEFT  JOIN mnt_area_mod_estab      t09 ON (t09.id = t07.id_area_mod_estab)
                LEFT  JOIN ctl_area_atencion       t10 ON (t10.id = t09.id_area_atencion)
                LEFT  JOIN mnt_empleado            t11 ON (t11.id = t03.id_empleado)
                -- Paciente Referido
                LEFT  JOIN mnt_dato_referencia     t12 ON (t12.id = t01.id_dato_referencia)
                LEFT  JOIN mnt_expediente_referido t13 ON (t13.id = t12.id_expediente_referido)
                LEFT  JOIN mnt_paciente_referido   t14 ON (t14.id = t13.id_referido)
                LEFT  JOIN ctl_sexo                t15 ON (t15.id = t14.id_sexo)
                LEFT  JOIN mnt_aten_area_mod_estab t16 ON (t16.id = t12.id_aten_area_mod_estab)
                LEFT  JOIN tbl_servicio            t17 ON (t17.id = t16.id AND t17.servicio IS NOT NULL)
                LEFT  JOIN mnt_area_mod_estab      t18 ON (t18.id = t16.id_area_mod_estab)
                LEFT  JOIN ctl_area_atencion       t19 ON (t19.id = t18.id_area_atencion)
                LEFT  JOIN mnt_empleado            t20 ON (t20.id = t12.id_empleado)
                INNER JOIN lab_recepcionmuestra	   t21 ON(t01.id=t21.idsolicitudestudio)
                WHERE CASE WHEN $idHistorialClinico::integer != 0
                        THEN t03.id = $idHistorialClinico
                        ELSE t12.id = $idDatoReferencia
                      END
                    AND t01.id_establecimiento_externo = $idEstablecimiento";
       
       
       
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

//Fn_PG
function grupoprueconsul($i_idsolicitud, $i_idestablocal, $idHistorialClinico, $idDatoReferencia)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
      $sql="select distinct t06.id as idgrupo, t06.nombregrupo, t06.idgrupo as codgrupo
         from sec_solicitudestudios t01
         join sec_detallesolicitudestudios t02 on (t01.id=t02.idsolicitudestudio)
         join lab_conf_examen_estab t03 on (t03.id=t02.id_conf_examen_estab)
         join mnt_area_examen_establecimiento t04 on (t04.id=t03.idexamen)
         join ctl_examen_servicio_diagnostico t05 on (t05.id=t04.id_examen_servicio_diagnostico)
         join lab_estandarxgrupo 	     t06 on (t06.id=t05.idgrupo)
         where (CASE WHEN $idHistorialClinico:: integer != 0
                          THEN t01.id_historial_clinico = $idHistorialClinico
                          ELSE t01.id_dato_referencia = $idDatoReferencia
                          END)
         and estado!=6
         and estadodetalle!=6
         and t01.id_establecimiento=$i_idestablocal
         order by 2;";
      $result=pg_query($sql);
      if (!$result)
       return false;
      else
       return $result;
   }
}
/********************************************************************************/

 }//CLASE
?>
