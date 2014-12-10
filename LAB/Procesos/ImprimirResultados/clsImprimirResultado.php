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
                        
		$dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
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
	  $query ="SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
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
                    t01.idempleado AS idempleado,t01.id_conf_examen_estab as idexamen,t07.fecha_nacimiento as fechanac
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

            SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion,
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
                    t01.idempleado  as idempleado,t01.id_conf_examen_estab as idexamen,t07.fecha_nacimiento as fechanac
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
 function MostrarDatosFijosPlantillaA($idexamen,$lugar,$iddetalle)
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	 $query=/*"SELECT lab_examenes.IdExamen,NombreExamen,Unidades,RangoInicio,RangoFin
				FROM lab_datosfijosresultado 
				INNER JOIN lab_examenes ON lab_datosfijosresultado.IdExamen=lab_examenes.IdExamen
				INNER JOIN lab_resultados ON lab_examenes.IdExamen=lab_resultados.IdExamen  
				WHERE lab_datosfijosresultado.IdEstablecimiento=$lugar AND  lab_examenes.IdExamen='$idexamen' 
				AND IdSolicitudEstudio=$idsolicitud
				AND DATE_FORMAT(lab_resultados.FechaHoraReg,'%Y/%m/%d') BETWEEN lab_datosfijosresultado.FechaIni AND IF(lab_datosfijosresultado.FechaFin ='0000-00-00',CURDATE(),lab_datosfijosresultado.FechaFin)";*/
                  
                   "select t02.nombre_examen as nombre_examen, 
                  t01.unidades as unidades, t01.rangoinicio as rangoinicio, 
                  t01.rangofin as rangofin 
                  from lab_datosfijosresultado t01 
                  inner join lab_conf_examen_estab t02 on (t02.id=t01.id_conf_examen_estab) 
                  left join lab_resultados t03 on (t03.id=t02.idexamen) 
                  where t01.idestablecimiento=$lugar 
                  and t03.idexamen=(select id from lab_conf_examen_estab  where codigo_examen='$idexamen')
                  and t03.iddetallesolicitud=$iddetalle ";
                  
                                        
	     $result = @pg_query($query);
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
                AND lab_resultados.fecha_resultado BETWEEN lab_elementos.fechaini AND (CASE WHEN lab_elementos.fechafin IS NULL THEN CURRENT_DATE ELSE lab_elementos.fechafin END)";
        
/* "SELECT IdElemento,Elemento,UnidadElem,SubElemento,NombreArea,NombreExamen,ObservElem 
		FROM lab_elementos INNER JOIN lab_examenesxestablecimiento ON lab_elementos.IdExamen=lab_examenesxestablecimiento.IdExamen 
		INNER JOIN lab_examenes ON lab_examenesxestablecimiento.IdExamen=lab_examenes.IdExamen
		INNER JOIN lab_areasxestablecimiento ON lab_examenes.IdArea=lab_areasxestablecimiento.IdArea 
		INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
		INNER JOIN lab_resultados ON lab_elementos.IdExamen=lab_resultados.IdExamen 
		WHERE lab_examenesxestablecimiento.IdExamen='$idexamen' AND lab_elementos.IdEstablecimiento=$lugar 
		AND left(lab_resultados.FechaHoraReg,10) 
		BETWEEN lab_elementos.FechaIni AND IF(lab_elementos.FechaFin ='0000-00-00',CURDATE(),lab_elementos.FechaFin) 
		AND IdDetalleSolicitud=$iddetalle ORDER BY IdElemento";*/
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
	INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	WERE lab_resultados.IdSolicitudEstudio=$idsolicitud AND lab_resultados.IdDetalleSolicitud=$iddetalle
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
     $query ="SELECT lab_resultados.id as idresultado, lab_detalleresultado.id as iddetalleresultado,lab_subelementos.idelemento,lab_detalleresultado.idsubelemento,
              lab_subelementos.subelemento,lab_detalleresultado.resultado,lab_subelementos.unidad,lab_subelementos.rangoinicio,lab_subelementos.rangofin, 
              lab_detalleresultado.observacion 
              FROM lab_resultados 
              INNER JOIN lab_detalleresultado ON lab_resultados.id=lab_detalleresultado.idresultado 
              INNER JOIN lab_subelementos ON lab_detalleresultado.idsubelemento=lab_subelementos.id
              WHERE idsolicitudestudio=$idsolicitud AND iddetallesolicitud=$idsolicitud AND lab_subelementos.idelemento=$idelemento 
              AND (lab_subelementos.idsexo=$sexo OR lab_subelementos.idsexo is NULL) AND (idedad=4 OR idedad=$idedad)
              AND lab_subelementos.idestablecimiento=$lugar ORDER BY lab_subelementos.id ";
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
        echo $query;

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
                    t26.observacion as observacion
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
            left  join sec_diagnostico_paciente		t21     on (t21.id_historial_clinico=t09.id)
            left join mnt_snomed_cie10 			t22     on (t22.id=t21.id_snomed)
            left join sec_signos_vitales                t23     on (t23.id_historial_clinico=t09.id)
            inner join ctl_area_servicio_diagnostico    t25     on (t25.id=t05.id_area_servicio_diagnostico)
            left join lab_resultados                    t26  	on (t26.iddetallesolicitud=t01.id)
            left join  mnt_empleado                     t24 	on (t24.id=t26.idempleado)
                WHERE   t02.id                  =$idsolicitud   
                and     t01.id                  = $iddetalle      
                and     t02.id_establecimiento  = $lugar 

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
                t26.observacion as observacion
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
      $query;
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
    $query =/*"SELECT ElementoTincion,CantidadTincion 
	     FROM lab_detalleresultado 
	     INNER JOIN lab_elementostincion  ON lab_detalleresultado.IdElemento=lab_elementostincion.IdElementosTincion
 	     INNER JOIN lab_cantidadestincion  ON lab_cantidadestincion.IdCantidadesTincion=lab_detalleresultado.IdCantidad
         WHERE IdResultado=$idresultado" ;*/
    
            "SELECT idelemento,cantidad 
	     FROM lab_detalleresultado 
	     INNER JOIN lab_elementos   ON lab_detalleresultado.idelemento=lab_elementos.id
 	     INNER JOIN lab_cantidadestincion  ON lab_cantidadestincion.id=lab_detalleresultado.idcantidad
         WHERE lab_detalleresultado.id=$idresultado";
    
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
   {  $query = "SELECT resultado FROM lab_resultados WHERE idsolicitudestudio=$idsolicitud AND 		
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
   {  $query = "SELECT id,resultado FROM lab_resultados WHERE idsolicitudestudio=$idsolicitud AND 		
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
     $query = /*"SELECT lab_resultados.IdResultado, 						 	 
	lab_detalleresultado.IdDetalleResultado,lab_resultadosportarjeta.IdAntibiotico,lab_antibioticos.Antibiotico,
	lab_resultadosportarjeta.Resultado
	FROM lab_resultados 
	INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	INNER JOIN lab_resultadosportarjeta ON lab_detalleresultado.IdDetalleResultado=lab_resultadosportarjeta.IdDetalleResultado
	INNER JOIN lab_antibioticos ON lab_resultadosportarjeta.IdAntibiotico=lab_antibioticos.IdAntibiotico
	WHERE IdSolicitudEstudio=$idsolicitud and IdDetalleSolicitud=$iddetalle";*/
     
            "select t04.antibiotico as antibiotico, t03.resultado as resultado  
	from lab_resultados		     t01
	left join lab_detalleresultado       t02	on (t02.idresultado=t01.id)
	left join lab_resultadosportarjeta   t03	on (t03.iddetalleresultado=t02.id)
	left join lab_antibioticos	     t04	on (t04.id=t03.idantibiotico)
	WHERE idsolicitudestudio=$idsolicitud and iddetallesolicitud=$iddetalle";
     
     $result = @pg_query($query);
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
     $query = /*"	SELECT lab_resultados.IdResultado, 						 	 
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


/********************************************************************************/

 }//CLASE
?>
