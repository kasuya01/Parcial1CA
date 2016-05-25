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
       	$query="SELECT nombre FROM ctl_establecimiento WHERE id=$lugar";
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
		$sqlText= "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) ;
	}
	return $dt;
}

 function LlenarTodosEstablecimientos() {

      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $sqlText = "SELECT id, nombre FROM ctl_establecimiento ORDER BY nombre";
         $dt = pg_query($sqlText);
      }
      return $dt;
   }

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
	$sqlText="WITH tbl_servicio AS (
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
		 $query ="SELECT lcee.id,lcee.nombre_examen 
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
                    WHERE  t02.id_establecimiento = 49
                    ORDER BY 2)
            
                    SELECT 
                    t02.id,
		   t13.nombre AS nombreservicio, 
		   t19.nombre AS sexo,
                   t24.nombreempleado as medico,
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
                 t18.idestandar as codigo_examen,
                 t25.idarea as codigo_area,
                 t25.nombrearea as nombre_area,
                    CASE t01.estadodetalle 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
                        WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='CA') THEN 'Cancelada'
                        END AS estado,
			
                    CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado1, 
                        t01.indicacion as indicacion,
                        t01.idempleado as idempleado
                   
		   
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
            --INNER JOIN tbl_servicio                     t20     ON (t20.id = t10.id AND t20.servicio IS NOT NULL)
            
            left  join sec_diagnostico_paciente		t21     on (t21.id_historial_clinico=t09.id)
            left join mnt_snomed_cie10 			t22	on (t22.id=t21.id_snomed)
            left join sec_signos_vitales                t23 	on (t23.id_historial_clinico=t09.id)
            left  join mnt_empleado 			t24 	on (t09.id_empleado=t24.id)


            inner join ctl_area_servicio_diagnostico     t25   on (t25.id=t05.id_area_servicio_diagnostico)
            
            WHERE  t02.id=$idsolicitud and  t06.numero='$idexpediente'

UNION

            SELECT t02.id,
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
                   t18.idestandar as codigo_examen,
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
                        t01.idempleado  as idempleado
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
            left join sec_signos_vitales                t23 	on (t23.id_historial_clinico=t09.id)
            left  join mnt_empleado 			t24 	on (t09.id_empleado=t24.id)
            
            
            inner join ctl_area_servicio_diagnostico    t25   on (t25.id=t05.id_area_servicio_diagnostico)
            
            WHERE   t02.id=$idsolicitud and  t06.numero='$idexpediente'  order by codigo_area";
                
                
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
     $query = "SELECT 
               CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,
               t07.primer_apellido,t07.segundo_apellido,t07.apellido_casada),
               t03.numeromuestra,
               t02.id,
               t03.idestablecimiento,
               t08.idarea,
               t18.tipomuestra, 
               t04.impresion, 
               t18.tipomuestra 
               FROM sec_detallesolicitudestudios t01 
               INNER JOIN sec_solicitudestudios t02	   	ON (t02.id = t01.idsolicitudestudio) 
               INNER JOIN lab_recepcionmuestra t03 	   	ON (t03.idsolicitudestudio=t02.id) 
               INNER JOIN lab_conf_examen_estab t04 	   	ON (t04.id = t01.id_conf_examen_estab) 
               INNER JOIN mnt_area_examen_establecimiento t05 	ON (t05.id = t04.idexamen) 
               INNER JOIN mnt_expediente t06 			ON (t06.id = t02.id_expediente) 
               INNER JOIN mnt_paciente t07 			ON (t07.id = t06.id_paciente) 
               INNER JOIN ctl_area_servicio_diagnostico t08 	ON (t08.id = t05.id_area_servicio_diagnostico AND t08.id_atencion =(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
               INNER JOIN sec_historial_clinico t09 		ON (t09.id = t02.id_historial_clinico) 
               INNER JOIN mnt_aten_area_mod_estab t10 		ON (t10.id = t09.idsubservicio) 
               INNER JOIN ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
               INNER JOIN mnt_area_mod_estab t12 		ON (t12.id = t10.id_area_mod_estab) 
               INNER JOIN ctl_area_atencion t13 		ON (t13.id = t12.id_area_atencion) 
               INNER JOIN ctl_establecimiento t14 		ON (t14.id = t09.idestablecimiento) 
               INNER JOIN cit_citas_serviciodeapoyo t15 	ON (t15.id_solicitudestudios=t02.id) 
               INNER JOIN ctl_estado_servicio_diagnostico t16 	ON (t16.id = t01.estadodetalle) 
               INNER JOIN lab_tiposolicitud t17 		ON (t17.id = t02.idtiposolicitud) 
               INNER JOIN lab_tipomuestra t18 			ON (t18.id = t01.idtipomuestra) 
               INNER JOIN ctl_examen_servicio_diagnostico t19 	ON (t19.id = t05.id_examen_servicio_diagnostico) 
               WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
               and t06.numero = '$idexpediente' 
               AND t02.id = $idsolicitud
               group by CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,
               t07.primer_apellido,t07.segundo_apellido,t07.apellido_casada),
               t03.numeromuestra,
               t02.id,
               t03.idestablecimiento,
               t08.idarea,
               t18.tipomuestra, 
               t04.impresion, 
               t18.tipomuestra 

               UNION 

               select 
               CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,
               t07.primer_apellido,t07.segundo_apellido,t07.apellido_casada),
               t03.numeromuestra,
               t02.id,
               t03.idestablecimiento,
               t08.idarea,
               t18.tipomuestra, 
               t04.impresion, 
               t18.tipomuestra 
               FROM sec_detallesolicitudestudios t01 
               INNER JOIN sec_solicitudestudios t02 		ON (t02.id = t01.idsolicitudestudio) 
               INNER JOIN lab_recepcionmuestra t03 		ON (t03.idsolicitudestudio=t02.id) 
               INNER JOIN lab_conf_examen_estab t04 		ON (t04.id = t01.id_conf_examen_estab) 
               INNER JOIN mnt_area_examen_establecimiento t05 	ON (t05.id = t04.idexamen) 
               INNER JOIN mnt_dato_referencia t09 		ON t09.id=t02.id_dato_referencia 
               INNER JOIN mnt_expediente_referido t06 		ON (t06.id = t09.id_expediente_referido) 
               INNER JOIN mnt_paciente_referido t07 		ON (t07.id = t06.id_referido) 
               INNER JOIN ctl_area_servicio_diagnostico t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
               AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
               INNER JOIN mnt_aten_area_mod_estab t10 		ON (t10.id = t09.id_aten_area_mod_estab) 
               INNER JOIN ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
               INNER JOIN mnt_area_mod_estab t12 		ON (t12.id = t10.id_area_mod_estab) 
               INNER JOIN ctl_area_atencion t13 		ON (t13.id = t12.id_area_atencion) 
               INNER JOIN ctl_establecimiento t14 		ON (t14.id = t09.id_establecimiento) 
               INNER JOIN cit_citas_serviciodeapoyo t15 	ON (t15.id_solicitudestudios=t02.id) 
               INNER JOIN ctl_estado_servicio_diagnostico t16 	ON (t16.id = t01.estadodetalle) 
               INNER JOIN lab_tiposolicitud t17 		ON (t17.id = t02.idtiposolicitud) 
               INNER JOIN lab_tipomuestra t18 			ON (t18.id = t01.idtipomuestra) 
               INNER JOIN ctl_examen_servicio_diagnostico t19 	ON (t19.id = t05.id_examen_servicio_diagnostico) WHERE (t02.id_atencion=(SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
               and t06.numero = '$idexpediente' 
               AND t02.id = $idsolicitud 
               group by CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,
               t07.primer_apellido,t07.segundo_apellido,t07.apellido_casada),
               t03.numeromuestra,
               t02.id,
               t03.idestablecimiento,
               t08.idarea,
               t18.tipomuestra, 
               t04.impresion, 
               t18.tipomuestra";               
             
   $result = @pg_query($query);
   //echo '<br/>'.$query.'<br/>';
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
}//CLASE
?>
