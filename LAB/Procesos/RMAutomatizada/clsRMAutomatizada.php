<?php 
include_once("../../../Conexion/ConexionBD.php");
//implementamos la clase lab_areas
class clsRMAutomatizada
{
 //constructor	
 function clsRMAutomatizada(){
 }	
 
function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		/*$conNom  = "SELECT 	mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";*/
            $conNom  = "SELECT t02.id AS idtipoestablecimiento,
                              t01.nombre,
                              t02.nombre AS nombretipoestablecimiento
                        FROM ctl_establecimiento t01
			INNER JOIN ctl_tipo_establecimiento t02 ON (t02.id = t01.id_tipo_establecimiento)
			WHERE t01.id = $lugar";
		$resul = pg_query($conNom) or die('La consulta fall&oacute;: ' . pg_error());
	}
 return $resul;
}

function DatosArea($area){
	$con = new ConexionBD;
	if($con->conectar()==true){			  
		//$NomAre  ="select nombrearea,administrativa from ctl_area_servicio_diagnostico where id=$area";
                $NomAre  ="select nombrearea,administrativa from ctl_area_servicio_diagnostico where id=$area";
		$resul = pg_query($NomAre) or die('La consulta fall&oacute;: ' . pg_error());
	}
 return $resul;
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

function LlenarCmbEstablecimiento($Idtipoesta){
$con = new ConexionBD;
	if($con->conectar()==true){
		//$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipoesta' ORDER BY Nombre";		
	        $sqlText= "SELECT id,nombre FROM ctl_establecimiento WHERE id_tipo_establecimiento=$Idtipoesta ORDER BY nombre";	
            $dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());
	}
	return $dt;
}

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		/*$sqlText= "SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
			FROM mnt_subservicio 
			INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
			WHERE mnt_subservicio.IdServicio='$IdServ' AND IdEstablecimiento=$lugar 
			ORDER BY NombreSubServicio";		
		$dt = pg_query($sqlText) or die('La consulta fall&oacute;:' . pg_error());*/
        echo    $sqlText = "WITH tbl_servicio AS (
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
	}
	return $dt;
}

 //FUNCION PARA ACTUALIZAR OBSERVACION DEL EXAMEN QUE HA SIDO RECHAZADO EN UNA SOLICITUD
 function MarcarObservacionRechazado($idsolicitud,$idarea,$observacion)
 {
  $con = new ConexionBD;
   if($con->conectar()==true) 
   {
      $query=/*"UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE idsolicitudestudio=$idsolicitud AND IdExamen LIKE '%$idarea%'";*/
              
              "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
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
      $query=/*"UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE idsolicitudestudio=$idsolicitud AND IdExamen='$idexamen'";*/
              
              "UPDATE sec_detallesolicitudestudios SET observacion='$observacion'
			  WHERE id=$idsolicitud AND idexamen= $idexamen";
		  
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
  
 }
 
 //FUNCION PARA DEVOLVER DATOS DE LA SOLICITUD QUE HA DE SER PROCESADA
 //DATOS GENERALES DE LA SOLICITUD
function DatosGeneralesSolicitud($idsolicitud)
{  
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
	 $query = /*"select sse.id, --id solicitudestudios 
mem.id, --id empleado 
mer.numero, -- numero del expediente 
mem.nombreempleado, --nombre empleado 
ce.nombre, --establecimiento 
CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)AS paciente,
par.primer_nombre, 
cat.nombre, --nombre atencion 
mc.diagnostico, sef.peso, sef.talla, age(current_date, par.fecha_nacimiento) AS edad, csex.nombre, t01.nombre 
from ctl_area_servicio_diagnostico casd 
join mnt_area_examen_establecimiento mnt4     on (mnt4.id_area_servicio_diagnostico=casd.id ) 
join lab_conf_examen_estab lcee               on (mnt4.id=lcee.idexamen) 
INNER JOIN sec_detallesolicitudestudios sdses ON (sdses.id_conf_examen_estab=lcee.id) 
INNER JOIN sec_solicitudestudios sse          ON (sdses.idsolicitudestudio=sse.id) 
INNER JOIN lab_recepcionmuestra lrc           ON (sse.id= lrc.idsolicitudestudio ) 
INNER JOIN sec_historial_clinico shc          ON (sse.id_historial_clinico=shc.id ) 
inner join mnt_empleado mem                   on (shc.id_empleado=mem.id) 
JOIN mnt_expediente_referido mer    	      on (sse.id_expediente_referido=mer.id)
INNER JOIN mnt_paciente_referido par          ON (mer.id_referido=par.id)
inner join ctl_sexo csex                      on (csex.id=par.id_sexo) 
inner join mnt_aten_area_mod_estab mnt3       on (shc.idsubservicio=mnt3.id) 
inner join mnt_area_mod_estab m1              on (mnt3.id_area_mod_estab=m1.id) 
inner join ctl_atencion cat                   on (shc.idsubservicio=cat.id) 
inner join ctl_establecimiento ce             on (shc.idestablecimiento=ce.id ) 
inner join ctl_area_atencion t01              on ( m1.id_area_atencion=t01.id) 
inner join lab_recepcionmuestra lrm           on (sse.id=lrm.idsolicitudestudio) 
left join sec_diagnosticospaciente sdp        on (shc.id=sdp.idhistorialclinico) 
left join mnt_cie10 mc                        on (mc.id=sdp.iddiagnostico1) 
left join sec_examenfisico sef                on (sef.idhistorialclinico=shc.id) 
where sse.id_atencion=98 
and sdses.id=$idsolicitud";*/
                  
                "select 
sse.id, --id solicitudestudios 
mem.id, --id empleado 

mem.nombreempleado, --nombre empleado 
ce.nombre, --establecimiento 
case WHEN id_expediente_referido is  null then 
                                  ( mex.numero)
                                   else (mer.numero) end as numero,
case WHEN id_expediente_referido is  null then 
                                  (csex.nombre)
                                   else (csexpar.nombre) end as sexnom,                                  
case WHEN id_expediente_referido is  null  THEN 
	CONCAT_WS(' ', pa.primer_nombre, NULL,pa.segundo_nombre,NULL,pa.primer_apellido,NULL,pa.segundo_apellido)
	else  
	  CONCAT_WS(' ', par.primer_nombre, NULL,par.segundo_nombre,NULL,par.primer_apellido,NULL,par.segundo_apellido)end as paciente,
pa.conocido_por, 
cat.nombre, --nombre atencion 
mc.diagnostico, sef.peso, sef.talla, 
case WHEN id_expediente_referido is  null then 
age(current_date, pa.fecha_nacimiento)
	else age(current_date, par.fecha_nacimiento) end as edad,
t01.nombre 
from ctl_area_servicio_diagnostico casd 
join mnt_area_examen_establecimiento mnt4     	on (mnt4.id_area_servicio_diagnostico=casd.id )
join lab_conf_examen_estab lcee 	      	on (mnt4.id=lcee.idexamen) 
INNER JOIN sec_detallesolicitudestudios sdses 	ON (sdses.id_conf_examen_estab=lcee.id)
INNER JOIN sec_solicitudestudios sse          	ON (sdses.idsolicitudestudio=sse.id) 
INNER JOIN lab_recepcionmuestra lrc           	ON (sse.id= lrc.idsolicitudestudio )
INNER JOIN sec_historial_clinico shc 	      	ON (sse.id_historial_clinico=shc.id )
inner join mnt_empleado mem 			on (shc.id_empleado=mem.id) 
inner join mnt_expediente mex 			on (shc.id_numero_expediente=mex.id) 
inner join mnt_paciente pa 			on (mex.id_paciente=pa.id) 
inner join ctl_sexo csex 			on (csex.id=pa.id_sexo) 
inner join mnt_aten_area_mod_estab mnt3 	on (shc.idsubservicio=mnt3.id) 
inner join mnt_area_mod_estab m1 		on (mnt3.id_area_mod_estab=m1.id) 
inner join ctl_atencion cat 			on (shc.idsubservicio=cat.id) 
inner join ctl_establecimiento ce 		on (shc.idestablecimiento=ce.id ) 
inner join ctl_area_atencion t01 		on ( m1.id_area_atencion=t01.id) 
inner join lab_recepcionmuestra lrm 		on (sse.id=lrm.idsolicitudestudio) 
left join sec_diagnosticospaciente sdp 		on (shc.id=sdp.idhistorialclinico) 
left join mnt_cie10 mc 				on (mc.id=sdp.iddiagnostico1) 
left join sec_examenfisico sef 			on (sef.idhistorialclinico=shc.id) 
LEFT  JOIN mnt_dato_referencia  mdr             on (sse.id_dato_referencia=mdr.id)
LEFT JOIN mnt_expediente_referido mer           on (mdr.id_expediente_referido=mer.id)
LEFT JOIN mnt_paciente_referido par   		ON (mer.id_referido=par.id) 
left join ctl_sexo csexpar 			on (csexpar.id=par.id_sexo)  
where sse.id_atencion=98 
			
			and sdses.id=$idsolicitud";
         $result = @pg_query($query);
                 
                   //         echo $query;
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
	   $query = /*"SELECT B.IdExamen,NombreExamen,TipoMuestra,Indicacion 
				FROM sec_detallesolicitudestudios A
				INNER JOIN lab_examenes     AS B ON A.IdExamen=B.IdExamen
				INNER JOIN lab_tipomuestra  AS C ON C.IdTipoMuestra=A.IdTipoMuestra
				WHERE idSolicitudEstudio = $idsolicitud 
				AND IdArea='$idarea'";*/
		"SELECT lab_examenes.IdExamen,NombreExamen,TipoMuestra,Indicacion 
		     FROM sec_detallesolicitudestudios 
		     INNER JOIN lab_examenes     ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen
		     INNER JOIN lab_tipomuestra  ON lab_tipomuestra.IdTipoMuestra=sec_detallesolicitudestudios.IdTipoMuestra
		     WHERE idSolicitudEstudio = $idsolicitud AND IdArea='$idarea' AND EstadoDetalle='RM'";
                   
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
	 $query=
	"select lcee.id,lcee.nombre_examen,ltm.tipomuestra,sdses.indicacion,sdses.observacion,casd.id
		from ctl_area_servicio_diagnostico casd 
                join mnt_area_examen_establecimiento mnt4 on mnt4.id_area_servicio_diagnostico=casd.id 
                join lab_conf_examen_estab lcee on (mnt4.id=lcee.idexamen) 
                INNER JOIN sec_detallesolicitudestudios sdses ON sdses.id_conf_examen_estab=lcee.id
                inner join lab_tipomuestra ltm on ltm.id=sdses.idtipomuestra
            where sdses.id=$idsolicitud 
            AND casd.id=$idarea
            AND lcee.id=$idexamen";
                
                $result = @pg_query($query);
	if (!$result)
	   return false;
	else
	   return $result;	   
   }
 }
 
//FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
/*function CambiarEstadoSolicitud1($idexpediente,$fechasolicitud,$estadosolicitud)

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
 }*/
 
 function CambiarEstadoSolicitud($idsolicitud,$idsolicitudPadre){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
		 $query=/*"SELECT COUNT(IdDetalleSolicitud) FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio=$idsolicitud AND (EstadoDetalle<>'RC' AND EstadoDetalle<>'RM')";*/
                           "SELECT COUNT(id) 
                                FROM sec_detallesolicitudestudios 
                                WHERE idsolicitudestudio=$idsolicitudPadre AND (estadodetalle<>7 AND estadodetalle<>6)";
                /*  "UPDATE sec_solicitudestudios SET estado=4,
                                              fecha_solicitud= now(),
                                              fechahorareg=current_timestamp
                                WHERE id_expediente=$idexpediente
                                    --and id=$idsolicitud
                                AND id_atencion=98";*/
                
                
                        
                        $detalle=pg_fetch_array(pg_query($query));
		if($detalle[0] == 0){
			$query1= /*"UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud";*/
                                    "UPDATE sec_solicitudestudios SET estado=4 WHERE id=$idsolicitudPadre";
                                $result=pg_query($query1);		
			return true;	  
		}
		if ($detalle[0] >= 1){
			$query1= /*"UPDATE sec_solicitudestudios SET estado='P' WHERE IdSolicitudEstudio=$idsolicitud";*/
                                    "UPDATE sec_solicitudestudios SET estado=3 WHERE id=$idsolicitudPadre";
                                $result=pg_query($query1);	
			return true;
		}
		
	   
  }
 }

 //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
function CambiarEstadoDetalle($idsolicitud,$estado,$idarea)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query =/*"UPDATE sec_detallesolicitudestudios AS a,sec_solicitudestudios AS b
			SET a.EstadoDetalle='$estado',a.Observacion=''
			WHERE a.IdSolicitudEstudio='$idsolicitud' AND 
			b.IdServicio='DCOLAB' AND a.IdExamen LIKE '%$idarea%' ";*/
   
           " UPDATE sec_detallesolicitudestudios 
                    SET estadodetalle=$estado,
                    observacion='$observacion'
                    WHERE sec_detallesolicitudestudios.id=$idsolicitud ";
   
   $result = pg_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
  //FUNCION PARA CAMBIAR EL ESTADO DE PROCESADO AL DETALLE DE LA SOLICITUD
function CambiarEstadoDetalle1($idsolicitud,$estado,$idexamen,$observacion)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    
   $query = /*"UPDATE sec_detallesolicitudestudios AS a,sec_solicitudestudios AS b
			 SET a.EstadoDetalle='$estado',a.observacion='$observacion'
			 WHERE a.IdSolicitudEstudio=$idsolicitud 
			 AND b.IdServicio='DCOLAB' AND a.IdExamen ='$idexamen'";*/
    "UPDATE sec_detallesolicitudestudios 
             SET estadodetalle=$estado,
	     observacion='$observacion'
            WHERE sec_detallesolicitudestudios.id=$idsolicitud ";
           
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
  
   //RECUPERAR EXAMENES POR AREA
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
                        
                      /*"SELECT lcee.id,lcee.nombre_examen 
                    FROM mnt_area_examen_establecimiento maees
                    INNER JOIN lab_conf_examen_estab lcee ON maees.id= lcee.idexamen 
                    WHERE maees.id_area_servicio_diagnostico=$idarea
                    AND maees.id_establecimiento=$lugar
                    AND lcee.condicion= 'H'
                    ORDER BY lcee.nombre_examen asc";*/
		 $result = @pg_query($query);
		 if (!$result)
		   return false;
		 else
		   return $result;
	   }
	 }
         
         function siesreferrido(){
             $respuesta;
		$con = new ConexionBD;
	    //usamos el metodo conectar para realizar la conexion
	    if($con->conectar()==true){
	     $consulta = "select count (id_expediente_referido) from sec_solicitudestudios";
             $result = @pg_query($consulta);
             while ($row = pg_fetch_array($result))
            { 
                $respuesta=$row[0];
            }
         }
         return $respuesta;
       }
 
}//CLASE
?>
