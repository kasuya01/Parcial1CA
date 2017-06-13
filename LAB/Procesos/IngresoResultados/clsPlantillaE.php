<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsPlantillaE
{
 //constructor	
 function clsPlantillaE (){
 }	

function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT nombre FROM ctl_establecimiento WHERE id =$lugar";
        
	 $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }

}

//Funcion para llener resultados de tabulaci�n
 function LlenarResultados($IdEstandar)
 {
	 $con = new ConexionBD;
	   if($con->conectar()==true) 
	  {	 $query="SELECT t01.idresultado,
                        t02.resultado
                        FROM lab_codigosxexamen  t01
                        INNER JOIN lab_codigosresultados t02 ON (t02.id = t01.idresultado)
                        WHERE t02.id != 5 AND t01.idestandar = $IdEstandar"	;	
	     $result = pg_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   	   }
 }
 
 function Obtener_Estandar($idexamen){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT t03.idestandar, t03.id
                FROM lab_conf_examen_estab  t01
                INNER JOIN mnt_area_examen_establecimiento t02 ON (t02.id = t01.idexamen)
                INNER JOIN ctl_examen_servicio_diagnostico t03 ON (t03.id = t02.id_examen_servicio_diagnostico)
                WHERE t01.id = $idexamen";
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
       	$query="SELECT DATEDIFF(NOW( ),'$fechanac')";
	 $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function ObtenerCodigoRango($fechanac){
   $con = new ConexionBD;
   if($con->conectar()==true){  
       $query="SELECT * 
               FROM ctl_rango_edad
               WHERE (CURRENT_DATE - '$fechanac') BETWEEN edad_minima_dias AND edad_maxima_dias
               AND (id != 4 OR LOWER(nombre) != 'todos') AND cod_modulo = 'LAB'";
            
        $result = @pg_query($query);
         if (!$result)
            return false;
         else
            return $result;
   }     
}
 
 /*Funcion para obtener el nombre del Resultado del tabulador*/ 
function ObtenerNombreCodigo($tab){
 	 $con = new ConexionBD;
	 if($con->conectar()==true) 
	 {   $query=" SELECT resultado FROM lab_codigosresultados WHERE id=$tab ";	
	     $result = pg_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	 }
}




 //FUNCION PARA CAMBIAR EL ESTADO DEL DETALLLE DE LA SOLICITUD
function CambiarEstadoDetalle($iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) //Estado RC--> Resultados Completo
   {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle=7 WHERE id=$iddetalle";	
        $result = pg_query($query);
        if (!$result)
          return false;
        else
          return $result;	   
   }
 }
 
 //FUNCI�N PARA CAMBIAR EL ESTADO DE LA SOLICITUD
  function CambiarEstadoSolicitud($idsolicitud){
    $con = new ConexionBD;
     if($con->conectar()==true){ 
          $query="SELECT id,idexamen 
                  FROM sec_detallesolicitudestudios WHERE idsolicitudestudio=$idsolicitud 
                          AND EstadoDetalle <> 7 AND EstadoDetalle <> 6 AND EstadoDetalle <> 8";
          $detalle=pg_num_rows(pg_query($query));
          if(empty($detalle)){
                  $query="UPDATE sec_solicitudestudios SET estado= 4 WHERE id=$idsolicitud"	;
                  $result=pg_query($query);		
                  return true;	  
      }else
                  return false;
     }
   }

   function VerificarExistencia($idexamen,$idsolicitud,$iddetalle){
       $con = new ConexionBD;
        if($con->conectar()==true) {
          $query = "SELECT count(*) FROM lab_resultados WHERE idsolicitudestudio=$idsolicitud AND iddetallesolicitud= $iddetalle";
            $cantidad = pg_fetch_array(pg_query($query));
            return $cantidad[0];
        }
   }
   
////********************************************************************************************************************************////
//INSERTA RESULTADOS ENCABEZADO
 function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$responsable,$usuario,$tab,$fecharealiz,$fecharesultado,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
       $query = "INSERT INTO lab_resultados (idsolicitudestudio,iddetallesolicitud,idexamen,idrecepcionmuestra,     
                   observacion,idempleado,idusuarioreg,fechahorareg,idestablecimiento,fecha_resultado) 
                   VALUES($idsolicitud,$iddetalle,$idexamen,$idrecepcion,'$observacion',$responsable,$usuario,date_trunc('seconds',NOW()),$lugar,'$fecharesultado')
                   RETURNING id";
                    
         $result = pg_query($query);
         $row = pg_fetch_array($result);
         $idultimo=$row[0];
           //echo $idultimo;
         if ($idultimo>0) {
              $query = "SELECT id FROM lab_examen_metodologia WHERE id_conf_exa_estab = $idexamen AND activo = true";
                         
               $result = pg_query($query);
                
               if($result && pg_num_rows($result) == 1) {
                    $row_exam_metod = pg_fetch_array($result);

                    $id_exam_metod = $row_exam_metod[0];

                    $query = "INSERT INTO lab_resultado_metodologia(id_examen_metodologia, id_detallesolicitudestudio, id_codigoresultado, idusuarioreg, fechahorareg,fecha_realizacion,fecha_resultado,id_empleado)
                              VALUES($id_exam_metod, $iddetalle, $tab, $usuario, date_trunc('seconds',NOW()),'$fecharealiz','$fecharesultado',$responsable)";
                             
                    
                    $result = pg_query($query);

                    if($result) {
                                             
                        return $idultimo;
                    } else {
                        return false;
                    }
                    
                } else {
                    return false; // Aqui va la logica si hay mas de una metodologia en el examen
                }
            } else {
                return false;
            }
        }
    }
 
 //FUNCION PARA INSERTAR EL DETALLE DE LOS RESULTADOS DE LOS PROCESOS
 function insertar_detalle($idresultado,$idproceso,$resultado,$comentario,$lugar,$pos)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {    if ($pos==NULL){
           $query = "INSERT INTO lab_detalleresultado(idresultado,idprocedimiento,resultado,observacion,idestablecimiento) 
		      VALUES($idresultado,$idproceso,'$resultado','$comentario',$lugar)";
            
        }
        else{

             $query1="SELECT posible_resultado FROM lab_posible_resultado where id=$resultado";
                $result1 = pg_query($query1);
                $row= pg_fetch_array($result1);
                
               // $dato = $result1;
                if($result1) {
                    $dato = $row[0];
                    $query = "INSERT INTO lab_detalleresultado(idresultado,idprocedimiento,resultado,id_posible_resultado,observacion,idestablecimiento) 
                              VALUES($idresultado,$idproceso,'$dato','$resultado','$comentario',$lugar)";
                }

        }
       $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
//FUNCION PARA LEER LOS EMPLEADOS
function DatosEmpleado($idempleado,$lugar)
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
   
       $query = "SELECT nombreempleado as empleado 
               FROM mnt_empleado WHERE id=$idempleado AND id_establecimiento=$lugar";
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
 
//FUNCION PARA MOSTRAR DATOS GENERALES
function MostrarDatosGenerales($idsolicitud,$lugar)
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
                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-' ||mnt_aten_area_mod_estab.nombre_ambiente 
                    ELSE mnt_aten_area_mod_estab.nombre_ambiente 
                END 
            ELSE 
                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN ctl_atencion.nombre WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=ctl_atencion.nombre) 
                    THEN ctl_atencion.nombre 
                END 
            END AS subservicio, 
            

            CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-'  || ctl_area_atencion.nombre
            ELSE   ctl_modalidad.nombre ||'-' || ctl_area_atencion.nombre
            END AS procedencia,
            
            to_char(lab_recepcionmuestra.fechahorareg,'dd/mm/YYYY' ) AS fecharecep,
            (SELECT nombre FROM ctl_establecimiento WHERE id=sec_solicitudestudios.id_establecimiento_externo) AS estabext
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
            INNER JOIN  mnt_modalidad_establecimiento       ON mnt_modalidad_establecimiento.id=mnt_area_mod_estab.id_modalidad_estab
            INNER JOIN ctl_modalidad                    ON ctl_modalidad.id = mnt_modalidad_establecimiento.id_modalidad
            WHERE lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud";
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
   {//HH12:MI
      $query = "SELECT TO_CHAR(fecha_resultado,'dd/mm/YYYY') AS fecharesultado
                FROM lab_resultados 
                WHERE idsolicitudestudio=$idsolicitud AND idestablecimiento=$lugar 
                AND idexamen=$IdExamen";
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

//FUNCION PARA LEER AREA Y NORMBRE DEL EXAMEN
function LeerDatos($idexamen)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT nombrearea,nombre_examen,nombre_reporta 
               FROM lab_conf_examen_estab
               INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id=lab_conf_examen_estab.idexamen
               INNER JOIN ctl_area_servicio_diagnostico ON ctl_area_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_area_servicio_diagnostico
               INNER JOIN lab_examen_metodologia ON lab_examen_metodologia.id_conf_exa_estab = lab_conf_examen_estab.id
               WHERE id_atencion=98 AND lab_conf_examen_estab.id=$idexamen";
     $result = @pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
 
// INSERTAR DETALLE DE LOS RESULTADOS POR ELEMENTOS Y SUBELEMENTOS
//ELEMENTOS
function insertar_elemento($idresultado,$idproceso,$resultadodetalle,$observacion)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "INSERT INTO lab_detalleresultado(idresultado,idproceso,resultado,observacion ) 
	      VALUES($idresultado,$idproceso,$resultadodetalle,$observacion)";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 //FUNCION PARA LEER LOS PROCESO POR EXAMEN
function LeerProcesoExamen($idexamen,$lugar,$sexo,$idedad)
{
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
  $query="SELECT lab_procedimientosporexamen.id as idprocedimiento,nombreprocedimiento,unidades,rangoinicio,rangofin 
            FROM lab_procedimientosporexamen 
            INNER JOIN lab_conf_examen_estab ON lab_conf_examen_estab.id=lab_procedimientosporexamen.id_conf_examen_estab 
            INNER JOIN mnt_area_examen_establecimiento ON lab_conf_examen_estab.idexamen=mnt_area_examen_establecimiento.id 
            WHERE lab_procedimientosporexamen.id_conf_examen_estab=$idexamen AND lab_procedimientosporexamen.idestablecimiento=$lugar
            AND CURRENT_DATE BETWEEN lab_procedimientosporexamen.fechaini 
            AND CASE WHEN fechafin IS NULL THEN CURRENT_DATE ELSE lab_procedimientosporexamen.fechafin END
            AND (lab_procedimientosporexamen.idsexo=$sexo OR lab_procedimientosporexamen.idsexo IS NULL)
            AND (lab_procedimientosporexamen.idrangoedad=$idedad OR lab_procedimientosporexamen.idrangoedad=4) ORDER BY orden";
//echo $query;
	
	 $result = @pg_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;   
   }
}

function leer_posibles_resultados_procedimientos($idprocedimiento){
         $con = new ConexionBD;
        if($con->conectar()==true) {
         $query="SELECT id_posible_resultado,posible_resultado 
                    FROM lab_procedimiento_posible_resultado 
                    INNER JOIN lab_posible_resultado ON lab_posible_resultado.id = lab_procedimiento_posible_resultado.id_posible_resultado
                    WHERE lab_posible_resultado.habilitado=TRUE AND lab_procedimiento_posible_resultado.habilitado=TRUE 
                    AND id_procedimientoporexamen=$idprocedimiento";
           // echo $query;

            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }    
    }

    function contar_posibles_resultados_procedimientos($idprocedimiento){
         $con = new ConexionBD;
        if($con->conectar()==true) {
          $query="SELECT count(*)
                    FROM lab_procedimiento_posible_resultado 
                    INNER JOIN lab_posible_resultado ON lab_posible_resultado.id = lab_procedimiento_posible_resultado.id_posible_resultado
                    WHERE id_procedimientoporexamen=$idprocedimiento AND lab_procedimiento_posible_resultado.habilitado=TRUE";

            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }    
    }
 function BuscarResultado($id_posible_resultado){
         $con = new ConexionBD;
        if($con->conectar()==true) {
           $query="SELECt posible_resultado from lab_posible_resultado where id=$id_posible_resultado";
           $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }       
        
    }
    
  function buscarAnterioresPUnica($idsolicitud, $iddetallesolicitud, $idarea) {
      $con = new ConexionBD;
      if ($con->conectar() == true) {
    $query = "select nombre_examen, sds.id as iddetallesolicitud, lce.id as idexamen 
    from sec_solicitudestudios sse
    join sec_detallesolicitudestudios sds 	on (sse.id = sds.idsolicitudestudio)
    join lab_conf_examen_estab lce 		on (lce.id = sds.id_conf_examen_estab)
    join mnt_area_examen_establecimiento mae on(mae.id = lce.idexamen)
    where estadodetalle not in (6,7)
    and sse.id=$idsolicitud
    and sds.id=$iddetallesolicitud
  --  and mae.id_area_servicio_diagnostico = $idarea
    order by nombre_examen;";
         // echo $query;
         $result = pg_query($query);
         if (!$result) {
            return false;
         } else {
            return $result;
         }
      }
   }  
 
}//CLASE
?>
