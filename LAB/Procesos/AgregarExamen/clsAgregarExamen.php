<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsAgregarExamen
{
	 //constructor	
function clsAgregarExamen()
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
			    INNER JOIN mnt_tipoestablecimiento 
                            ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
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
		$sqlText= /*"SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
                           FROM mnt_subservicio 
                           INNER JOIN mnt_subservicioxestablecimiento 
                           ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
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
/*Funcion que llena el combo de tipos de muestra por examen*/
function LlenarCmbTipoMuestra($IdExamen){
$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= /*"SELECT lab_tipomuestra.IdTipoMuestra,lab_tipomuestra.TipoMuestra
			   FROM lab_tipomuestraporexamen 
			   INNER JOIN lab_tipomuestra ON lab_tipomuestraporexamen.IdTipoMuestra=lab_tipomuestra.IdTipoMuestra
			   WHERE IdExamen='$IdExamen'";*/
                      /*  "select  t01.idtipomuestra, t02.tipomuestra 
		from lab_tipomuestraporexamen  t01
		inner join  lab_tipomuestra t02 on (t01.idtipomuestra=t02.id)
		where idexamen=$IdExamen";*/
                          
                          "select distinct t01.idtipomuestra, t02.tipomuestra 
		from lab_tipomuestraporexamen  t01
		left join  lab_tipomuestra t02 on (t01.idtipomuestra=t02.id)
		left join lab_conf_examen_estab t03 on (t03.id=t01.idexamen)
		where t01.idexamen=$IdExamen"
                         . "and t01.habilitado=true";
                          
                          
                          
		$dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;

}
/*Funcion que llena el combo de origenes de la muestra*/
function LlenarCmbOrigenMuestra($IdTipo){
         
$con = new ConexionBD;
	if($con->conectar()==true){
		 $sqlText= /*"SELECT mnt_origenmuestra.IdOrigenMuestra,mnt_origenmuestra.OrigenMuestra
			   FROM mnt_origenmuestra 
			   WHERE mnt_origenmuestra.IdTipoMuestra=$IdTipo";*/
                        
                       " select  t01.id, t01.origenmuestra
                                        from mnt_origenmuestra t01 
                                        where  t01.idtipomuestra=$IdTipo";
                        
		$dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
	}
	//echo $sqlText;
	return $dt;

}


function BuscarExamen($idsolicitudPa,$idexamen,$lugar){
	$con = new ConexionBD;
   //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
	   $query = "SELECT count(*) FROM sec_detallesolicitudestudios 
                     WHERE id_conf_examen_estab='$idexamen' AND idsolicitudestudio=$idsolicitudPa";
	   /*"SELECT count(*) FROM sec_detallesolicitudestudios 
				 WHERE IdExamen='$idexamen' AND IdEstablecimiento=$lugar AND IdSolicitudEstudio=$idsolicitud";*/
			 
		$result = pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	}
 } 

 
 function opteneridexamen($IdExamen) 
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query ="select idexamen as idexa from lab_conf_examen_estab where id=$IdExamen";
            
            
            $result = @pg_query($query);
    //echo $query;
	 
     if (!$result)
       return false;
     else
       return $result;	   
   }
 }

 
 
function insertar_Examen($idsolicitudPa,$idexamen1,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario,$IdEstab,$origen,$fechatomamuestra)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query =/*"INSERT INTO sec_detallesolicitudestudios(IdSolicitudEstudio,IdExamen,Indicacion,EstadoDetalle,IdTipoMuestra,IdOrigenMuestra,
             Observacion,IdEstablecimiento,IdEstablecimientoExterno,IdEmpleado,IdUsuarioReg,FechaHoraReg) 
             VALUES($idsolicitud,'$IdExamen','$indicacion','PM',$IdTipo,$origen,'$Observa',$lugar,$IdEstab,'$Empleado',$usuario,NOW())";*/
   
            "INSERT  INTO sec_detallesolicitudestudios (idsolicitudestudio,idexamen,
					id_conf_examen_estab,indicacion,estadodetalle,
					idtipomuestra,idorigenmuestra,observacion,idestablecimiento,idestablecimientoexterno,
					    idempleado,idusuarioreg,fechahorareg,'f_tomamuestra')
					    VALUES($idsolicitudPa,
                                                $idexamen1,
					      $IdExamen,
					      '$indicacion',
					      5,
					      $IdTipo,
					      '$origen',
					      '$Observa',
					      $lugar,
					      $IdEstab,
					      $Empleado,
					      $usuario,NOW(),$fechatomamuestra)";
            
            $result = @pg_query($query);
    //echo $query;
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
 
 function insertar_Examensin($idsolicitudPa,$idexamen1,$IdExamen,$indicacion,$IdTipo,$Observa,$lugar,$Empleado,$usuario,$IdEstab,$fechatomamuestra)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
        $query =/*"INSERT INTO sec_detallesolicitudestudios(IdSolicitudEstudio,IdExamen,Indicacion,EstadoDetalle,IdTipoMuestra,IdOrigenMuestra,
             Observacion,IdEstablecimiento,IdEstablecimientoExterno,IdEmpleado,IdUsuarioReg,FechaHoraReg) 
             VALUES($idsolicitud,'$IdExamen','$indicacion','PM',$IdTipo,$origen,'$Observa',$lugar,$IdEstab,'$Empleado',$usuario,NOW())";*/
   
            "INSERT  INTO sec_detallesolicitudestudios (idsolicitudestudio,idexamen,
					id_conf_examen_estab,indicacion,estadodetalle,
					idtipomuestra,observacion,idestablecimiento,idestablecimientoexterno,
					    idempleado,idusuarioreg,fechahorareg,f_tomamuestra)
					    VALUES($idsolicitudPa,$idexamen1,$IdExamen,'$indicacion', 5,
					      $IdTipo,
					      '$Observa',
					      $lugar,
					      $IdEstab,
					      $Empleado,
					      $usuario,NOW(),'$fechatomamuestra')";
            
            $result = @pg_query($query);
    //echo $query;
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

 
 

function ObtenerCodigoTecnico($usuario){

$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
		 $query = "select IdEmpleado from mnt_empleados where IdTipoEmpleado='LAB' AND Correlativo=$usuario";
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
	   $query ="SELECT DATE_FORMAT(FechaRecepcion,'%e/ %m / %Y')AS fecha 
                    FROM lab_recepcionmuestra 
                    WHERE IdSolicitudEstudio=$Idsolic";
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
	   $query ="SELECT DATE_FORMAT(Fecha,'%e/ %m / %Y')AS fecha 
                    FROM cit_citasxserviciodeapoyo 
                    WHERE IdSolicitudEstudio=$Idsolic";
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
	$query ="WITH tbl_servicio AS (
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
                    WHERE  t02.id_establecimiento = $lugar
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
			t01.indicacion as indicacion,
                        t01.idempleado as idempleado,t03.fechahorareg as fechatomamuestra,t06.numero as expediente
                   
		   
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 		ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 		ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 		ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 	ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06 			ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07 			ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09 		ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10 		ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 			ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 			ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 			ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15 		ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 	ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 			ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 				ON (t19.id = t07.id_sexo)
          
            left  join sec_diagnostico_paciente		t21     on (t21.id_historial_clinico=t09.id)
                left join mnt_snomed_cie10 			t22	on (t22.id=t21.id_snomed)
                left join sec_signos_vitales  			t23 	on (t23.id_historial_clinico=t09.id)
                left  join mnt_empleado 			t24 	on (t09.id_empleado=t24.id) 
                inner join ctl_area_servicio_diagnostico t25      on (t25.id=t05.id_area_servicio_diagnostico)
            
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
                   t23.peso AS peso,
                    t23.talla AS talla,
                    t04.nombre_examen AS nombre_examen,
                   t04.codigo_examen AS codigo_examen,
                   t25.idarea AS codigo_area,
                   t25.nombrearea AS nombre_area,
                    CASE t01.estadodetalle
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida'
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso'    
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' END AS estado,
			t01.indicacion AS indicacion,
                        t01.idempleado  AS idempleado,t03.fechahorareg AS fechatomamuestra,t06.numero as expediente
                        
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 		ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 		ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04	 	ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05  	ON (t05.id = t04.idexamen)
            INNER JOIN mnt_dato_referencia t09 			ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06 		ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07 		ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08 	ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10 		ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11 			ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 			ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 			ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 			ON (t14.id = t09.id_establecimiento)
	    INNER JOIN ctl_examen_servicio_diagnostico t18 	ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 				ON (t19.id = t07.id_sexo)
            LEFT JOIN sec_diagnostico_paciente		t21     ON (t21.id_historial_clinico=t09.id)
            LEFT JOIN mnt_snomed_cie10 			t22	ON (t22.id=t21.id_snomed)
            LEFT JOIN sec_signos_vitales  		t23 	ON (t23.id_historial_clinico=t09.id)
            LEFT JOIN mnt_empleado 			t24 	ON (t09.id_empleado=t24.id) 
            INNER JOIN ctl_area_servicio_diagnostico t25      ON (t25.id=t05.id_area_servicio_diagnostico)
            
            WHERE   t02.id=$idsolicitud and  t06.numero='$idexpediente'";
                
                
	 $result = @pg_query($query);
        if (!$result)
          return false;
        else
          return $result;	   
           }
   }
  //DATOS DEL DETALLE DE LA SOLICITUD
  function obtener_fecha_tomamuestra($idexpediente,$idsolicitud,$lugar,$tipomuestra)
  {
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query="SELECT 
sec_detallesolicitudestudios.f_tomamuestra as fechatomamuestra,lab_recepcionmuestra.fechahorareg as fecharecepcion
FROM sec_detallesolicitudestudios 
INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.idsolicitudEstudio=sec_solicitudestudios.id
INNER JOIN lab_conf_examen_estab ON sec_detallesolicitudestudios.idExamen=lab_conf_examen_estab.id
INNER JOIN lab_recepcionmuestra ON lab_recepcionmuestra.idsolicitudestudio=sec_solicitudestudios.id
WHERE sec_solicitudestudios.id=$idsolicitud AND sec_solicitudestudios.id_establecimiento=$lugar AND idtipomuestra=$tipomuestra limit 1 ";
       
       
	  	$result = @pg_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   }
 }
 
 function BuscarFechaRecepcion($idsolicitud,$lugar){
     
     $con = new ConexionBD;
     if($con->conectar()==true)
     {
         $query="SELECT lab_recepcionmuestra.fechahorareg as fecharecepcion
                 FROM sec_solicitudestudios 
                 INNER JOIN lab_recepcionmuestra ON lab_recepcionmuestra.idsolicitudestudio=sec_solicitudestudios.id
                 WHERE sec_solicitudestudios.id=$idsolicitud AND sec_solicitudestudios.id_establecimiento=$lugar";
         
         $result = @pg_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
     }
     
 }

 function ConsultarAreas(){
	$con = new ConexionBD;
	if($con->conectar()==true) 
	{
		echo $query =/*"SELECT lab_areas.IdArea, lab_areas.NombreArea
			 FROM lab_areas 
			 INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
			 WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areas.Administrativa='N'";*/
                        
                        "SELECT t01.id AS idarea, t01.nombrearea
                      FROM ctl_area_servicio_diagnostico t01
                      WHERE t01.id IN (
                        SELECT idarea
                        FROM lab_areasxestablecimiento 
                        WHERE condicion = 'H'  AND idestablecimiento = $lugar)
                        AND t01.administrativa = 'N'
                      ORDER BY nombrearea";
                        
                        
                        
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
   $query = $query_search ." LIMIT $RegistrosAMostrar  OFFSET    $RegistrosAEmpezar";
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
