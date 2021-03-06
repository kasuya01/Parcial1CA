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
                        
		$resul = pg_query($conNom);
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= //"SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
                            "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";
                        $dt = pg_query($sqlText) ;
	}
	return $dt;
}

function ObtenerAreaAtencion($IdServ,$lugar) {

      $con = new ConexionBD;
      if ($con->conectar() == true) {
         $sqlText = "SELECT id_area_atencion FROM mnt_area_mod_estab where id=$IdServ AND id_establecimiento=$lugar";
         $dt = pg_fetch_array(pg_query($sqlText));
         $r=$dt[0];
      }
      return $r;
   }   
   
   
 function LlenarReferido($IdServ,$lugar){
    $con = new ConexionBD;   
    if ($con->conectar() == true) {
      $sqlText = "SELECT mnt_3.id, cat.nombre AS servicio 
                        FROM ctl_atencion cat JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                        JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                        JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                        LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                        LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                        JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                        JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                        WHERE mnt_3.id_establecimiento=$lugar and mnt_2.id = $IdServ AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento 
                        FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL)";
        $dt = pg_query($sqlText) ;
  
  }
    return $dt;
 }      
   
   
function LlenarCmbServ($IdServ,$lugar,$IdAreaAtencion){
$con = new ConexionBD;
$condicionAmbiente="";
	if($con->conectar()==true){
            if ($IdAreaAtencion==3){
                $condicionAmbiente=' AND mnt_3.nombre_ambiente IS NOT NULL';
                $unionAmbiente="UNION
                    SELECT mnt_3.id,cat.nombre
                    FROM  ctl_atencion cat
                              JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                              JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                              JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4))
                              LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                              LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                              JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                              JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                    WHERE  mnt_2.id=$IdServ  AND mnt_3.id_establecimiento=$lugar
                                    AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento
                                    NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento
                                            FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL)";   
            }
       $sqlText = "WITH tbl_servicio as (SELECT mnt_3.id,
                  CASE
                      WHEN mnt_3.nombre_ambiente IS NOT NULL
                          THEN mnt_3.nombre_ambiente
                          ELSE cat.nombre
                  END AS nombre
                  FROM  ctl_atencion cat
                          JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion)
                          JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id)
                          JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4))
                          LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id
                          LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id
                          JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab)
                          JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad)
                  WHERE  mnt_2.id=$IdServ $condicionAmbiente
                           AND mnt_3.id_establecimiento=$lugar
                  $unionAmbiente
                  ORDER BY 2)
                  SELECT id, nombre FROM tbl_servicio WHERE nombre IS NOT NULL";
                        
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
       
       /*WITH tbl_servicio as (SELECT mnt_3.id, CASE WHEN id_servicio_externo_estab IS NOT NULL
                       THEN mnt_ser.abreviatura ||'--'  || a.nombre
                       ELSE       cmo.nombre ||'--' || a.nombre
                       END as procedencia,

                       CASE WHEN mnt_3.nombre_ambiente IS NOT NULL THEN mnt_3.nombre_ambiente 
                       ELSE cmo.nombre ||'--' ||cat.nombre END AS servicio 

                    FROM ctl_atencion cat 
                    JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.nombre_ambiente IS NOT NULL AND mnt_3.id_establecimiento=$lugar 

                    UNION 

                    SELECT mnt_3.id,CASE WHEN id_servicio_externo_estab IS NOT NULL
                                           THEN mnt_ser.abreviatura ||'--'  || a.nombre
                                           ELSE       cmo.nombre ||'--' || a.nombre
                                           END as procedencia,
                                           cat.nombre AS servicio
                    FROM ctl_atencion cat JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.id_establecimiento=$lugar 
                    AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento 
                    FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL))*/
	// echo "funcion=" .$idexpediente." - ".$idsolicitud;
                  $query ="WITH tbl_servicio as (SELECT mnt_3.id, CASE WHEN id_servicio_externo_estab IS NOT NULL
                       THEN mnt_ser.abreviatura ||' - '  || a.nombre
                       ELSE       cmo.nombre ||' - ' || a.nombre
                       END as procedencia,

                       CASE WHEN mnt_3.nombre_ambiente IS NOT NULL THEN mnt_3.nombre_ambiente 
                       ELSE cmo.nombre ||' - ' ||cat.nombre END AS servicio 

                    FROM ctl_atencion cat 
                    JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.nombre_ambiente IS NOT NULL AND mnt_3.id_establecimiento=$lugar

                    UNION 

                    SELECT mnt_3.id,CASE WHEN id_servicio_externo_estab IS NOT NULL
                                           THEN mnt_ser.abreviatura ||' - '  || a.nombre
                                           ELSE       cmo.nombre ||' - ' || a.nombre
                                           END as procedencia,
                                           cat.nombre AS servicio
                    FROM ctl_atencion cat JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.id_establecimiento=$lugar
                    AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento 
                    FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL))

                  
            SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion, 
            t01.id as iddetallesolicitud, 
            t02.id as idsolicitudestudio, 
            t04.idplantilla as idplantilla, 
            t26.procedencia AS nombreservicio, 
            t19.nombre AS sexo, 
            t24.nombreempleado as medico, 
            CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, t07.apellido_casada) AS paciente, 
            t07.conocido_por as conocidox, 
            REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( AGE(t07.fecha_nacimiento::timestamp)::text, 'years', 'años'), 'year', 'año'), 'mons', 'meses'), 'mon', 'mes'), 'days', 'días'), 'day', 'día') as edad, 
            (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext, 
            t26.servicio AS nombresubservicio, 
            t22.sct_name_es AS diagnostico, 
            t23.peso AS peso, 
            t23.talla AS talla, 
            t04.nombre_examen AS nombre_examen, 
            t04.codigo_examen AS codigo_examen, 
            t25.idarea AS codigo_area, 
            t25.nombrearea as nombre_area,
            t25.id AS idarea, 
            CASE t01.estadodetalle 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Cancelado(a)' END AS estado, 
            CASE t02.estado 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Cancelado(a)' END AS estado1, 
            t01.indicacion AS indicacion, 
            t01.idempleado AS idempleado,
            t01.id_conf_examen_estab as idexamen,
            t07.fecha_nacimiento as fechanac, 
            t19.id as idsexo, date (current_date) - date (t07.fecha_nacimiento) as dias,
            t06.numero as expediente,
            t18.idestandar as idestandar 
            FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_expediente t06 ON (t06.id = t02.id_expediente) 
            INNER JOIN mnt_paciente t07 ON (t07.id = t06.id_paciente) 
            INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico 
            AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN sec_historial_clinico t09 ON (t09.id = t02.id_historial_clinico) 
            INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.idsubservicio) 
            INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.idestablecimiento) 
            INNER JOIN cit_citas_serviciodeapoyo t15 ON (t02.id = t15.id_solicitudestudios) 
            INNER JOIN ctl_estado_servicio_diagnostico t16 ON (t16.id = t01.estadodetalle) 
            INNER JOIN lab_tiposolicitud t17 ON (t17.id = t02.idtiposolicitud) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
            LEFT JOIN sec_diagnostico_paciente t21 ON (t21.id_historial_clinico=t09.id) 
            LEFT JOIN mnt_snomed_cie10 t22 ON (t22.id=t21.id_snomed) 
            LEFT JOIN sec_signos_vitales t23 ON (t23.id_historial_clinico=t09.id) 
            LEFT JOIN mnt_empleado t24 ON (t09.id_empleado=t24.id) 
            LEFT JOIN ctl_area_servicio_diagnostico t25 ON (t25.id=t05.id_area_servicio_diagnostico) 
            INNER JOIN tbl_servicio t26                         ON (t26.id = t10.id AND t26.servicio IS NOT NULL)
                        WHERE  t02.id=$idsolicitud AND t08.idarea <> 'TMU' and t06.numero='$idexpediente' AND b_verresultado=true AND ubicacion <> 3

            UNION

                SELECT TO_CHAR(t03.fecharecepcion, 'DD/MM/YYYY') AS fecharecepcion, 
            t01.id as iddetallesolicitud, 
            t02.id as idsolicitudestudio, 
            t04.idplantilla as idplantilla, 
            t26.procedencia AS nombreservicio, t19.nombre AS sexo, t24.nombreempleado as medico, CONCAT_WS(' ',t07.primer_nombre,t07.segundo_nombre,t07.tercer_nombre,t07.primer_apellido,t07.segundo_apellido, t07.apellido_casada) AS paciente, t07.primer_nombre, 
            REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( AGE(t07.fecha_nacimiento::timestamp)::text, 'years', 'años'), 'year', 'año'), 'mons', 'meses'), 'mon', 'mes'), 'days', 'días'), 'day', 'día') as edad, (SELECT nombre FROM ctl_establecimiento WHERE id=t02.id_establecimiento_externo) AS estabext, 
            t26.servicio AS nombresubservicio, 
            t22.sct_name_es AS diagnostico, 
            t23.peso as peso, 
            t23.talla as talla, 
            t04.nombre_examen as nombre_examen, 
            t04.codigo_examen as codigo_examen, 
            t25.idarea as codigo_area, 
            t25.nombrearea as nombre_area,
            t25.id as idarea, 
            CASE t01.estadodetalle WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Cancelado(a)' END AS estado, 

            CASE t02.estado WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
            WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Cancelado(a)' END AS estado1, 
            t01.indicacion as indicacion, t01.idempleado as idempleado,t01.id_conf_examen_estab as idexamen,t07.fecha_nacimiento as fechanac, t19.id as idsexo, date (current_date) - date (t07.fecha_nacimiento) as dias,t06.numero as expediente,t18.idestandar as idestandar FROM sec_detallesolicitudestudios t01 
            INNER JOIN sec_solicitudestudios t02 ON (t02.id = t01.idsolicitudestudio) 
            INNER JOIN lab_recepcionmuestra t03 ON (t02.id = t03.idsolicitudestudio) 
            INNER JOIN lab_conf_examen_estab t04 ON (t04.id = t01.id_conf_examen_estab) 
            INNER JOIN mnt_area_examen_establecimiento t05 ON (t05.id = t04.idexamen) 
            INNER JOIN mnt_dato_referencia t09 ON t09.id=t02.id_dato_referencia 
            INNER JOIN mnt_expediente_referido t06 ON (t06.id = t09.id_expediente_referido) 
            INNER JOIN mnt_paciente_referido t07 ON (t07.id = t06.id_referido) 
            INNER JOIN ctl_area_servicio_diagnostico t08 ON (t08.id = t05.id_area_servicio_diagnostico AND t08.id_atencion = (SELECT id FROM ctl_atencion WHERE codigo_busqueda = 'DCOLAB')) 
            INNER JOIN mnt_aten_area_mod_estab t10 ON (t10.id = t09.id_aten_area_mod_estab) 
            INNER JOIN ctl_atencion t11 ON (t11.id = t10.id_atencion) 
            INNER JOIN mnt_area_mod_estab t12 ON (t12.id = t10.id_area_mod_estab) 
            INNER JOIN ctl_area_atencion t13 ON (t13.id = t12.id_area_atencion) 
            INNER JOIN ctl_establecimiento t14 ON (t14.id = t09.id_establecimiento) 
            INNER JOIN ctl_examen_servicio_diagnostico t18 ON (t18.id = t05.id_examen_servicio_diagnostico) 
            INNER JOIN ctl_sexo t19 ON (t19.id = t07.id_sexo) 
            left join sec_diagnostico_paciente t21 on (t21.id_historial_clinico=t09.id) 
            left join mnt_snomed_cie10 t22 on (t22.id=t21.id_snomed) 
            left join sec_signos_vitales t23 on (t23.id_historial_clinico=t09.id) 
            left join mnt_empleado t24 on (t09.id_empleado=t24.id) 
            inner join ctl_area_servicio_diagnostico t25 on (t25.id=t05.id_area_servicio_diagnostico) 
            INNER JOIN tbl_servicio t26                         ON (t26.id = t10.id AND t26.servicio IS NOT NULL)
            WHERE   t02.id=$idsolicitud AND t08.idarea <> 'TMU' and t06.numero='$idexpediente' AND b_verresultado=true  AND ubicacion <> 3 order by codigo_area";
      // exit();
                 		//echo $query;
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
	   $query = "SELECT sec_solicitudestudios.IdNumeroExp,  lab_examenes.IdArea AS IdArea,
 		lab_examenes.IdExamen AS IdExamen,NombreExamen,Indicacion,FechaSolicitud,
		sec_solicitudestudios.IdSolicitudEstudio,sec_detallesolicitudestudios.IdDetalleSolicitud,lab_examenesxestablecimiento.IdPlantilla,
		CASE sec_detallesolicitudestudios.EstadoDetalle 
		WHEN 'D'  THEN 'Digitado'
		WHEN 'PM' THEN 'En Proceso'
		WHEN 'RM' THEN 'Muestra Rechazada'    
		WHEN 'RC' THEN 'Resultado Completo' 
                WHEN 'E' THEN 'Cancelado(a)' 
                END AS Estado
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
	  {	$query=
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
                AND date(lab_resultados.fecha_resultado) BETWEEN lab_elementos.fechaini AND 
                CASE WHEN fechafin IS NULL THEN CURRENT_DATE ELSE lab_elementos.fechafin END
                ORDER BY orden";
        

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
            lab_detalleresultado.observacion,lab_detalleresultado.id_posible_resultado,posible_resultado  
            FROM lab_resultados 
              INNER JOIN lab_detalleresultado   ON lab_resultados.id=lab_detalleresultado.idresultado 
              INNER JOIN lab_subelementos       ON lab_detalleresultado.idsubelemento=lab_subelementos.id
              LEFT JOIN lab_posible_resultado  ON lab_posible_resultado.id = lab_detalleresultado.id_posible_resultado
              WHERE idsolicitudestudio=$idsolicitud  
              AND iddetallesolicitud=$iddetalle  
              AND lab_subelementos.idelemento=$idelemento 
              AND (lab_subelementos.idsexo=(select id from ctl_sexo where nombre='$sexo')OR lab_subelementos.idsexo is NULL) 
              AND (idedad=4 OR idedad=$idedad)
              AND lab_subelementos.idestablecimiento=$lugar 
              and (date(lab_resultados.fecha_resultado) between lab_subelementos.fechaini and (case when lab_subelementos.fechafin is null then current_date else lab_subelementos.fechafin end))    
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
     //  echo $query;

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
         $query =" WITH tbl_servicio as (SELECT mnt_3.id, CASE WHEN id_servicio_externo_estab IS NOT NULL
                       THEN mnt_ser.abreviatura ||' - '  || a.nombre
                       ELSE       cmo.nombre ||' - ' || a.nombre
                       END as procedencia,

                       CASE WHEN mnt_3.nombre_ambiente IS NOT NULL THEN mnt_3.nombre_ambiente 
                       ELSE cmo.nombre ||' - ' ||cat.nombre END AS servicio 

                    FROM ctl_atencion cat 
                    JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.nombre_ambiente IS NOT NULL AND mnt_3.id_establecimiento=$lugar

                    UNION 

                    SELECT mnt_3.id,CASE WHEN id_servicio_externo_estab IS NOT NULL
                                           THEN mnt_ser.abreviatura ||' - '  || a.nombre
                                           ELSE       cmo.nombre ||' - ' || a.nombre
                                           END as procedencia,
                                           cat.nombre AS servicio
                    FROM ctl_atencion cat JOIN mnt_aten_area_mod_estab mnt_3 on (cat.id=mnt_3.id_atencion) 
                    JOIN mnt_area_mod_estab mnt_2 on (mnt_3.id_area_mod_estab=mnt_2.id) 
                    JOIN ctl_area_atencion a ON (mnt_2.id_area_atencion=a.id AND a.id_tipo_atencion in (1,4)) 
                    LEFT JOIN mnt_servicio_externo_establecimiento msee on mnt_2.id_servicio_externo_estab = msee.id 
                    LEFT JOIN mnt_servicio_externo mnt_ser on msee.id_servicio_externo = mnt_ser.id 
                    JOIN mnt_modalidad_establecimiento mme on (mme.id=mnt_2.id_modalidad_estab) 
                    JOIN ctl_modalidad cmo on (cmo.id=mme.id_modalidad) 
                    WHERE mnt_3.id_establecimiento=$lugar
                    AND mnt_3.id_atencion ||'-'|| mnt_3.id_area_mod_estab ||'-'||mnt_3.id_establecimiento NOT IN (SELECT id_atencion ||'-'|| id_area_mod_estab ||'-'||id_establecimiento 
                    FROM mnt_aten_area_mod_estab WHERE nombre_ambiente IS NOT NULL))
           
                    SELECT 
                    TO_CHAR(t03.fechahorareg, 'DD/MM/YYYY') AS fecharecepcion,
                    t06.numero AS idnumeroexp,
                    t01.id as iddetallesolicitud,
                    t02.id as idsolicitudestudio,
                    t04.idplantilla  as idplantilla,
                    t27.procedencia AS nombreservicio, 
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
                    t27.servicio AS nombresubservicio,
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
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
                        WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Canceñado(a)' 
                        END AS estado,
			
                    CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Canceñado(a)' 
                       END AS estado1, 
                    t01.indicacion as indicacion,
                    t01.idempleado as idempleado,
                    t26.resultado as resultado,
                    t26.lectura as lectura,
                    t26.interpretacion as interpretacion,
                    t26.observacion as observacion,
                    TO_CHAR(t01.f_tomamuestra, 'DD/MM/YYYY HH12:MI') AS f_tomamuestra,
                    TO_CHAR(t03.fechahorareg, 'DD/MM/YYYY ') AS fecha,
                    TO_CHAR(t26.fecha_resultado, 'DD/MM/YYYY HH12:MI') AS fecha_resultado
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
            INNER JOIN tbl_servicio t27 ON (t27.id = t10.id AND t27.servicio IS NOT NULL)
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
            t27.procedencia AS nombreservicio, 
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
	    t27.servicio AS nombresubservicio, 
            
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
			WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo' 
                        WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Canceñado(a)' END AS estado,
                        
                CASE t02.estado 
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='D') THEN 'Digitada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='R') THEN 'Recibida' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='P') THEN 'En Proceso' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='C') THEN 'Completa' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='PM') THEN 'Procesar Muestra' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RM') THEN 'Muestra Rechazada' 
		       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='RC') THEN 'Resultado Completo'
                       WHEN (select id FROM ctl_estado_servicio_diagnostico where idestado='E') THEN 'Canceñado(a)' END AS estado1, 

		t01.indicacion as indicacion,
                t01.idempleado  as idempleado,
                t26.resultado as resultado,
                t26.lectura as lectura,
                t26.interpretacion as interpretacion,
                t26.observacion as observacion,TO_CHAR(t01.f_tomamuestra, 'DD/MM/YYYY HH12:MI') AS f_tomamuestra,
                TO_CHAR(t03.fechahorareg, 'DD/MM/YYYY HH12:MI') AS fecha,
                TO_CHAR(t26.fecha_resultado, 'DD/MM/YYYY HH12:MI') AS fecha_resultado
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
            INNER JOIN tbl_servicio t27 ON (t27.id = t10.id AND t27.servicio IS NOT NULL)
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
//Fn Pg
    function buscarexamresult($iddetalle, $idsolicitud, $lugar, $idexamen, $sexo, $idedad) {
        $con = new ConexionBD;
        if ($con->conectar() == true) {
         $query="select lce.id  as idexamen, codigo_examen, nombre_examen, unidades, rangoinicio, rangofin,nombre_metodologia, id_metodologia, nombre_reporta, fechafin, fechaini, ldf.id,
lrm.resultado, lrm.observacion, lrm.marca, lrm.lectura
from lab_datosfijosresultado 	ldf
join lab_conf_examen_estab	lce on (lce.id = ldf.id_conf_examen_estab)
join lab_examen_metodologia 	lem on (lce.id = lem.id_conf_exa_estab)
left join lab_metodologia	lme on (lme.id = lem.id_metodologia)
join lab_resultado_metodologia 	lrm on (lem.id = lrm.id_examen_metodologia)
where idestablecimiento=$lugar
and id_conf_examen_estab=$idexamen
and id_detallesolicitudestudio=$iddetalle
and (current_date between fechaini and (case when date(fechafin) is null then current_date else fechafin end))
and (ldf.idsexo is null or ldf.idsexo=$sexo)
and (idedad=4 or idedad=$idedad)
and b_reporta=true order by nombre_metodologia";
            $result = @pg_query($query);
           // $filares = pg_fetch_array($result);
              // echo '<br\>'.$filares['idempleado'].'<br\>';
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
    	 $query ="SELECT t01.observacion as observacion from lab_resultados t01 
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
                lab_procedimientosporexamen.rangofin,lab_procedimientosporexamen.unidades,lab_detalleresultado.observacion as comentario,
                lab_detalleresultado.id_posible_resultado,lab_posible_resultado.posible_resultado 
                FROM lab_resultados 
                INNER JOIN lab_detalleresultado ON lab_detalleresultado.idresultado=lab_resultados.id 
                INNER JOIN lab_procedimientosporexamen ON lab_detalleresultado.idprocedimiento=lab_procedimientosporexamen.id 
                LEFT JOIN lab_posible_resultado ON lab_posible_resultado.id = lab_detalleresultado.id_posible_resultado
                WHERE lab_resultados.iddetallesolicitud=$iddetalle AND lab_resultados.idsolicitudestudio=$idsolicitud 
                AND CURRENT_DATE BETWEEN lab_procedimientosporexamen.fechaini 
                AND CASE WHEN  lab_procedimientosporexamen.fechafin IS NULL THEN CURRENT_DATE ELSE lab_procedimientosporexamen.fechafin END
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
function ObtenerFechaResultado($idsolicitud,$iddetalle,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT TO_CHAR(fecha_resultado,'dd/mm/YYYY') AS fecharesultado
                FROM lab_resultados 
                WHERE idsolicitudestudio=$idsolicitud AND idestablecimiento=$lugar 
                AND iddetallesolicitud =$iddetalle";
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
        $query = "SELECT lab_resultados.id as idresultado
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


function contar_resultadospadre($idresultado){
    $con = new ConexionBD;
    if($con->conectar()==true)
   { 
    $query ="SELECT lab_resultados.id as idresultado,resultado,observacion,nombreempleado 
             FROM lab_resultados 
             INNER JOIN mnt_empleado ON mnt_empleado.id= lab_resultados.idempleado 
             WHERE lab_resultados.id_resultado_padre=$idresultado 
             AND idexamen=(SELECT id FROM lab_conf_examen_estab WHERE 
            idexamen=(SELECT id FROM mnt_area_examen_establecimiento WHERE id_examen_servicio_diagnostico=303 ))";
    
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
     $query = "SELECT lab_resultadosportarjeta.idantibiotico,antibiotico,resultado,valor,resultado,id_posible_resultado,posible_resultado 
FROM lab_resultadosportarjeta 
INNER JOIN lab_antibioticos ON lab_antibioticos.id=lab_resultadosportarjeta.idantibiotico
LEFT JOIN lab_posible_resultado ON lab_posible_resultado.id=lab_resultadosportarjeta.id_posible_resultado 
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


/********************************************************************************/

 }//CLASE
?>
