<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsEliminarResultado
{
	 //constructor	
	 function clsEliminarResultado()
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
       $conNom  = "SELECT ctl_establecimiento.id_tipo_establecimiento as IdTipoEstablecimiento,ctl_establecimiento.nombre ,ctl_tipo_establecimiento.nombre as NombreTipoEstablecimiento
            FROM ctl_establecimiento 
            INNER JOIN ctl_tipo_establecimiento ON ctl_establecimiento.id_tipo_establecimiento= ctl_tipo_establecimiento.id 
            WHERE ctl_establecimiento.id=$lugar";
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

/*function ExamenesPorArea($idarea,$lugar)
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
}*/



	 //FUNCION PARA MOSTRAR DATOS GENERALES DE LA SOLICITUD PROCESADAS POR AREA Y ESTADO
	/*  function LeerEspecialidades()
	 {
	   $con = new ConexionBD;
	   if($con->conectar()==true) 
	   {
	   $query= "SELECT IdSubServicio,NombreSubServicio FROM mnt_subservicio
	             WHERE IdServicio='CONEXT' ORDER BY NombreSubServicio";
	 
	     $result = @mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	   }
	 }*/
 
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
	   $query ="SELECT TO_CHAR(fecharecepcion,'DD/ MM / YYYY')AS fecha 
                    FROM lab_recepcionmuestra WHERE idsolicitudestudio=$Idsolic";
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
                $query ="SELECT TO_CHAR(fecha,'DD/ MM / YYYY')AS fecha 
                         FROM cit_citas_serviciodeapoyo WHERE id_solicitudestudios=$Idsolic";
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
	 $query = "SELECT lab_recepcionmuestra.idsolicitudestudio, 
             (CASE WHEN sec_solicitudestudios.id_historial_clinico IS NULL THEN 
             (mnt_expediente_referido.numero)
             ELSE (mnt_expediente.numero) end) as numero,
             (CASE WHEN sec_solicitudestudios.id_historial_clinico IS NULL THEN 
             CONCAT_WS(' ',mnt_paciente_referido.primer_nombre,mnt_paciente_referido.segundo_nombre,mnt_paciente_referido.tercer_nombre,mnt_paciente_referido.primer_apellido,mnt_paciente_referido.segundo_apellido,
             mnt_paciente_referido.apellido_casada) 
             ELSE CONCAT_WS(' ',mnt_paciente.primer_nombre,mnt_paciente.segundo_nombre,mnt_paciente.tercer_nombre,mnt_paciente.primer_apellido,
             mnt_paciente.segundo_apellido,mnt_paciente.apellido_casada) end) as paciente,
            (CASE WHEN sec_solicitudestudios.id_historial_clinico IS NULL THEN 
                (REPLACE(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        AGE(mnt_paciente_referido.fecha_nacimiento::timestamp)::text,
                                    'years', 'años'),
                                'year', 'año'),
                            'mons', 'meses'),
                        'mon', 'mes'),
                    'days', 'días'),
                'day', 'día'))
            ELSE (
                REPLACE(
                    REPLACE(
                        REPLACE(
                            REPLACE(
                                REPLACE(
                                    REPLACE(
                                        AGE(mnt_paciente.fecha_nacimiento::timestamp)::text,
                                    'years', 'años'),
                                'year', 'año'),
                            'mons', 'meses'),
                        'mon', 'mes'),
                    'days', 'días'),
                'day', 'día')) end) as edad,
            (CASE WHEN sec_solicitudestudios.id_historial_clinico IS NULL THEN 
                (SELECT nombre from ctl_sexo WHERE id=mnt_paciente_referido.id_sexo)
            ELSE (SELECT nombre from ctl_sexo WHERE id=mnt_paciente.id_sexo) end) AS sexo,

            CASE WHEN mnt_aten_area_mod_estab.nombre_ambiente IS NOT NULL THEN  	
                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-->' ||mnt_aten_area_mod_estab.nombre_ambiente
                    ELSE mnt_aten_area_mod_estab.nombre_ambiente
                END
            ELSE
                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'--> ' || ctl_atencion.nombre
                    WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=ctl_atencion.nombre) THEN ctl_atencion.nombre
                END
            END AS subservicio ,
            ctl_area_atencion.nombre AS procedencia,to_char(lab_recepcionmuestra.fechahorareg,'dd/mm/YYYY HH12:MI:SS' ) AS fecharecep,
            (SELECT nombre FROM ctl_establecimiento WHERE id=sec_solicitudestudios.id_establecimiento_externo) AS estabext,
            (SELECT nombre FROM mnt_empleado WHERE mnt_empleado.id = sec_historial_clinico.id_empleado OR mnt_empleado.id = mnt_dato_referencia.id_empleado) AS medico,
            (CASE WHEN sec_solicitudestudios.id_expediente IS NULL THEN (sec_solicitudestudios.id_dato_referencia) 
ELSE (sec_solicitudestudios.id_expediente) end) as idexpediente
            FROM lab_recepcionmuestra
            INNER JOIN sec_solicitudestudios                ON sec_solicitudestudios.id = lab_recepcionmuestra.idsolicitudestudio
            LEFT JOIN sec_historial_clinico                 ON sec_historial_clinico.id = sec_solicitudestudios.id_historial_clinico
            LEFT JOIN mnt_expediente                        ON mnt_expediente.id = sec_historial_clinico.id_numero_expediente
            LEFT JOIN mnt_paciente                          ON mnt_paciente.id=mnt_expediente.id_paciente
            LEFT JOIN mnt_dato_referencia                   ON mnt_dato_referencia.id=sec_solicitudestudios.id_dato_referencia
            LEFT JOIN mnt_expediente_referido               ON mnt_expediente_referido.id=mnt_dato_referencia.id_expediente_referido
            LEFT JOIN mnt_paciente_referido                 ON mnt_paciente_referido.id=mnt_expediente_referido.id_referido
            INNER JOIN ctl_sexo                             ON (ctl_sexo.id = mnt_paciente.id_sexo OR ctl_sexo.id =mnt_paciente_referido.id_sexo)
            INNER JOIN mnt_aten_area_mod_estab              ON (mnt_aten_area_mod_estab.id = mnt_dato_referencia.id_aten_area_mod_estab 
            OR mnt_aten_area_mod_estab.id= sec_historial_clinico.idsubservicio)
            INNER JOIN mnt_area_mod_estab                   ON (mnt_area_mod_estab.id = mnt_aten_area_mod_estab.id_area_mod_estab)
            LEFT  JOIN mnt_servicio_externo_establecimiento ON (mnt_servicio_externo_establecimiento.id = mnt_area_mod_estab .id_servicio_externo_estab)
            LEFT  JOIN mnt_servicio_externo 		ON (mnt_servicio_externo.id = mnt_servicio_externo_establecimiento.id_servicio_externo)
            INNER JOIN ctl_area_atencion                    ON ctl_area_atencion.id=mnt_area_mod_estab.id_area_atencion
            INNER JOIN ctl_atencion                         ON ctl_atencion.id=mnt_aten_area_mod_estab.id_atencion
            WHERE lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud AND id_expediente=$idexpediente OR id_dato_referencia=$idexpediente";
		$result = @pg_query($query);
     if (!$result)
      return false;
     else
       return $result;	   
	}
 }
  //DATOS DEL DETALLE DE LA SOLICITUD
function DatosDetalleSolicitud($idsolicitud)
{
    $con = new ConexionBD;
    if($con->conectar()==true) 
    {
	   $query = "SELECT ctl_area_servicio_diagnostico.idarea AS IdArea,lab_conf_examen_estab.codigo_examen AS IdExamen,nombre_examen,
            indicacion,fecha_solicitud,idsolicitudestudio,sec_detallesolicitudestudios.id as IdDetalleSolicitud,lab_plantilla.id as idplantilla,
            (CASE sec_detallesolicitudestudios.estadodetalle WHEN 1 THEN 'Digitado' 
             WHEN 5 THEN 'Muestra Procesada' 
             WHEN 6 THEN 'Muestra Rechazada' 
             WHEN 7 THEN 'Resultado Completo' END )AS Estado,idestandar 
             FROM sec_detallesolicitudestudios 
             INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.idsolicitudestudio=sec_solicitudestudios.id 
             INNER JOIN lab_conf_examen_estab ON sec_detallesolicitudestudios.id_conf_examen_estab=lab_conf_examen_estab.id
             INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id = lab_conf_examen_estab.idexamen
             INNER JOIN ctl_area_servicio_diagnostico ON ctl_area_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_area_servicio_diagnostico
             INNER JOIN ctl_examen_servicio_diagnostico ON ctl_examen_servicio_diagnostico.id= mnt_area_examen_establecimiento.id_examen_servicio_diagnostico
             INNER JOIN lab_plantilla ON lab_plantilla.id = lab_conf_examen_estab.idplantilla 
             WHERE sec_solicitudestudios.id_atencion = 98 AND EstadoDetalle=7
             AND sec_solicitudestudios.id=$idsolicitud
             ORDER BY ctl_area_servicio_diagnostico.idarea";
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
   $query ="SELECT estado 
            FROM sec_solicitudestudios WHERE idnumeroexp='$idexpediente' AND id=$idsolicitud";
  $result = pg_query($query);
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
     $result = pg_query($query);
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
   $query ="SELECT * FROM lab_resultados 
            WHERE idsolicitudestudio=$idsolicitud AND iddetalleSolicitud=$iddetalle";
  $result = @pg_query($query);
   if (!$result)
      return -1;
   else
      return 1;	   
   }
  }
  
  
  function EliminarResultado($idresultado){
     $con = new ConexionBD;
    if($con->conectar()==true)
    {
       $query ="DELETE FROM lab_resultados WHERE id=$idresultado";
	 $result = pg_query($query);
	 if (!$result)
	   return -1;
	 else 
	   return 1;
    }
  } 
 
 function EliminarDetalleResultado($idresultado){
     $con = new ConexionBD;
	if($con->conectar()==true)
	{
            $query ="DELETE FROM lab_detalleresultado WHERE idresultado=$idresultado";
		$result = pg_query($query);
		if (!$result)
			return -1;
		else 
			return 1;
        }
  }
 
/*function EliminarResultadoMetodologia($iddetalle){
        
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
      echo "<br>".$query="DELETE FROM lab_resultado_metodologia WHERE id_detallesolicitudestudio=$iddetalle";
       $result = pg_query($query);
	 if (!$result)
	   return -1;
	 else 
	   return 1;
   }
}*/

/* Función para Eliminar el resultado de la mertodología de una prueba*/
  function Eliminar_metodologia($iddetalle){
      $con = new ConexionBD;
	if($con->conectar()==true)
	{
		$query ="DELETE FROM lab_resultado_metodologia 
                         WHERE id_detallesolicitudestudio=$iddetalle";
		$result = pg_query($query);
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
     $query ="SELECT * FROM lab_detalleresultado WHERE idresultado=$idresultado";
	 $result = @pg_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
   }
   
 }
 
 
function ObtenerIdResultado($idsolicitud,$iddetalle){
        $con = new ConexionBD;
        if($con->conectar()==true)
        {
             $query ="SELECT id,resultado 
                  FROM lab_resultados 
                  WHERE idsolicitudestudio=$idsolicitud AND iddetallesolicitud=$iddetalle";
              $result = @pg_query($query);
              if (!$result)
                return false;
              else 
                     return $result;
        }
  }
  
 function ObtenerIdDetalleRes($idresultado){
 $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query ="select id FROM lab_detalleresultado WHERE idresultado=$idresultado";
	 $result = pg_query($query);
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
         $query ="SELECT resultado FROM lab_resultados WHERE id=$idresultado";
	 $result = pg_query($query);
	 if (!$result)
	   return false;
	 else 
		return $result;
  }
  }
  /*Elimina el Resultado de  una Tarjeta Vitek*/
 function EliminarResultadoTarjeta($iddetalleres){
  $con = new ConexionBD;
	if($con->conectar()==true)
	{
		$query ="DELETE FROM lab_resultadosportarjeta 
                         WHERE iddetalleresultado=$iddetalleres";
		$result = pg_query($query);
		if (!$result)
			return -1;
		else 
			return 1;
        }
  }
 
  
 
  /*Funcion para Actualizar el estado del detalla de la solicitud */
 function ActualizarEstadoDetalle($iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) //Estado PM--> Muestra en Proceso
   {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle=5 WHERE id=$iddetalle";	
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
 
/*Actualiza el estado de la solicitud*/
 function ActualizarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT  id,idexamen FROM sec_detallesolicitudestudios 
		WHERE idsolicitudestudio=$idsolicitud AND estadodetalle <> 7 and EstadoDetalle <> 6";
	$detalle=pg_num_rows(pg_query($query));
	if(!empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado= 3 WHERE id=$idsolicitud";
		$result=pg_query($query);		
		return true;	  
    }else
		$query="UPDATE sec_solicitudestudios SET estado= 4 WHERE id=$idsolicitud";
		$result=pg_query($query);
		return false;
   }
   }
 
 
 }//CLASE
?>
