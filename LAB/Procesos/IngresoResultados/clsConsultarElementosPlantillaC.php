<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsConsultarElementosPlantillaC
{
 //constructor	
 function clsConsultarElementosPlantillaC(){
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


////**********************************************************************************************************************************************////
//INSERTA RESULTADOS   ENCABEZADO
function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$responsable,$usuario,$codigoResultado,$lugar,$idobservacion,$fecharealiz,$fecharesultado)
{
   $con = new ConexionBD;
   if ($resultado=='P')
       $NomResultado='Positivo';
   else if($resultado=='N')
       $NomResultado='Negativo';
   else if($resultado=='O')
       $NomResultado='---';
   //echo $resultado;
    if($con->conectar()==true) 
    {
        $query = "INSERT INTO lab_resultados
	       (idsolicitudestudio,iddetallesolicitud,idexamen,idrecepcionmuestra,     
               observacion,resultado,idempleado,idusuarioreg,fechahorareg,idestablecimiento,id_observacion,fecha_resultado) 
	       VALUES($idsolicitud,$iddetalle,$idexamen,$idrecepcion,
               '$observacion','$NomResultado',$responsable,$usuario,date_trunc('seconds',NOW()),$lugar,$idobservacion,'$fecharesultado')RETURNING id";
      //echo $query;
                $result = pg_query($query);
                
                if($row = pg_fetch_array($result)) {
                    
                    $query = "SELECT id FROM lab_examen_metodologia WHERE id_conf_exa_estab = $idexamen  AND activo = true";
                    //AND id_metodologia IS NULL
                    $result = pg_query($query);
                    if($result && pg_num_rows($result) == 1) {
                        $row_exam_metod = pg_fetch_array($result);
                        $id_exam_metod = $row_exam_metod[0];
                        $id_exam_metod;
               $query = "INSERT INTO lab_resultado_metodologia(id_examen_metodologia, id_detallesolicitudestudio,id_codigoresultado,idusuarioreg,fechahorareg,fecha_realizacion,fecha_resultado,id_empleado)
                                  VALUES($id_exam_metod, $iddetalle, $codigoResultado, $usuario, date_trunc('seconds',NOW()),'$fecharealiz','$fecharesultado',$responsable)";
                        
                        $result = pg_query($query);

                        if($result) {
                            $idultimo = $row[0];
                            return $idultimo;
                        }else
                            return false;
                    
                    }else
                        return false; // Aqui va la logica si hay mas de una metodologia en el examen
            
                }else 
                    return false;
    }
    


        /*// $query2 ="SELECT MAX(id) FROM lab_resultados";
        //$result2 = pg_query($query2);
      //  $row2=pg_fetch_array($result2);
        //$ultimo=$row2[0];
    
    
    if ($row=pg_fetch_array($result2)){
	$idultimo=$row[0];
	   return $idultimo;	 
     }else{
         return false;
	}  
   }*/
}

function insertarAntibiograma($iddetalle,$idexamen,$codigoResultado,$usuario,$fecharealiz,$fecharesultado,$idempleado){
     $con = new ConexionBD;
   if($con->conectar()==true) 
   {
         $query = "SELECT lab_examen_metodologia.id FROM lab_examen_metodologia where nombre_reporta ilike '%ANTIBIOGRAMA%' AND activo = true";
                    //AND id_metodologia IS NULL
                    $result = pg_query($query);
                    if($result && pg_num_rows($result) == 1) {
                        $row_exam_metod = pg_fetch_array($result);
                        $id_exam_metod = $row_exam_metod[0];
                        $id_exam_metod;
                        $query = "INSERT INTO lab_resultado_metodologia(id_examen_metodologia, id_detallesolicitudestudio,id_codigoresultado,idusuarioreg,fechahorareg,fecha_realizacion,fecha_resultado,id_empleado)
                                 VALUES($id_exam_metod, $iddetalle, $codigoResultado, $usuario, date_trunc('seconds',NOW()),'$fecharealiz','$fecharesultado',$idempleado)";
                        
                        $result = pg_query($query);
                        
                         if($result)  
                            return true;
                        else    
                            return false;
                        
                    }
   }
}


// INSERTAR DETALLE DE LOS RESULTADOS 
function insertar_detalle($idresultado,$ibacteria,$idtarjeta,$cantidad,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
        $query = "INSERT INTO lab_detalleresultado(idresultado,idbacteria,idtarjeta,cantidad,idestablecimiento) 
			  VALUES($idresultado,$ibacteria,$idtarjeta,'$cantidad',$lugar)";
	$query2="SELECT MAX(id) FROM lab_detalleresultado where idresultado=$idresultado;";

      if($result = pg_query($query)){
	    $result2=pg_query($query2);
		     }

		//$idultimo= pg_insert_id();

	 
     if ($row=pg_fetch_array($result2)){
	$idultimo=$row[0];
	   return $idultimo;	 
     }else{
         return false;
	}  	   
   }
 }
 
//INSERTAR LOS RESULTADOS DE LAS TARJETAS
function insertar_resultadoantibioticos($iddetalleresultado,$idantibiotico,$resultado,$valor,$dato,$lugar)
 {  
     $con = new ConexionBD;
   if($con->conectar()==true) 
   {    if(!empty($resultado)){
            if(!empty($valor)){
              $query = "INSERT INTO lab_resultadosportarjeta(iddetalleresultado,idantibiotico,id_posible_resultado,resultado,valor,idestablecimiento) 
	       VALUES($iddetalleresultado,$idantibiotico,$valor,'$resultado','$dato',$lugar)";
            }
        
            else{
                $query = "INSERT INTO lab_resultadosportarjeta(iddetalleresultado,idantibiotico,resultado,idestablecimiento) 
	       VALUES($iddetalleresultado,$idantibiotico,'$resultado',$lugar)";
            }
         }
       /*  else {
             if(!empty($valor)){
           echo     $query = "INSERT INTO lab_resultadosportarjeta(iddetalleresultado,idantibiotico,id_posible_resultado,valor,idestablecimiento) 
                 VALUES($iddetalleresultado,$idantibiotico,$valor,NULL,$lugar)";
             
             } else {
             
            echo     $query = "INSERT INTO lab_resultadosportarjeta(iddetalleresultado,idantibiotico,idestablecimiento) 
                 VALUES($iddetalleresultado,$idantibiotico,$lugar)";
             
            }  
         } */
          
           
   $result = pg_query($query);
	//echo $query; 
     if (!$result)
       return false;
     else
       return true;	   
	   //return $query;
   }
 }
 
 
 function insertar_resultadoantibioticos1($iddetalleresultado,$idantibiotico,$valor,$dato,$lugar)
 {  
     $con = new ConexionBD;
   if($con->conectar()==true) 
   {   
            if(!empty($valor)){
                $query = "INSERT INTO lab_resultadosportarjeta(iddetalleresultado,idantibiotico,id_posible_resultado,valor,idestablecimiento) 
                   VALUES($iddetalleresultado,$idantibiotico,$valor,'$dato',$lugar)";
             
            }
        
            else{
                 $query = "INSERT INTO lab_resultadosportarjeta(iddetalleresultado,idantibiotico,idestablecimiento) 
                   VALUES($iddetalleresultado,$idantibiotico,$lugar)";
            }
                
    $result = pg_query($query);
	//echo $query; 
     if (!$result)
       return false;
     else
       return true;	   
	   //return $query;
   }
 }
 
 function insertar_resultado_metodologia($idexamen){
    $con = new ConexionBD;
    if($con->conectar()==true) //Estado RC--> Resultados Completo
    {
        $query="SELECT id FROM lab_examen_metodologia WHERE id_conf_exa_estab=$idexamen";
        $result = pg_query($query);
         
     if (!$result)
       return false;
     else
       return true;
    } 
    
  }
  
////*********************************************************************************************************************************************//// 
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
   
//FUNCION PARA LEER LOS EM,PLEADOS
function DatosEmpleado($idempleado,$lugar)
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
   $query = "SELECT nombreempleado as empleado 
               FROM mnt_empleado WHERE id=$idempleado AND id_establecimiento=$lugar";
     $result = pg_query($query);
       $no="no existe";
     if (!$result)
       return $no;
     else
       return $result;
      
    }
}

 //FUNCION FECHA DE RESULTADO
function ObtenerFechaResultado($idsolicitud,$IdExamen,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {//fecha_resultado,'dd/mm/YYYY HH12:MI'
      $query = "SELECT TO_CHAR(fecha_resultado,'dd/mm/YYYY') AS fecharesultado
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
                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'-->' ||mnt_aten_area_mod_estab.nombre_ambiente
                    ELSE mnt_aten_area_mod_estab.nombre_ambiente
                END
            ELSE
                CASE WHEN id_servicio_externo_estab IS NOT NULL THEN mnt_servicio_externo.abreviatura ||'--> ' || ctl_atencion.nombre
                    WHEN not exists (select nombre_ambiente from mnt_aten_area_mod_estab where nombre_ambiente=ctl_atencion.nombre) THEN ctl_atencion.nombre
                END
            END AS subservicio ,
            ctl_area_atencion.nombre AS procedencia,to_char(lab_recepcionmuestra.fechahorareg,'dd/mm/YYYY' ) AS fecharecep,
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
            WHERE lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud";

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
     $query = "SELECT lab_resultados.Resultado,lab_detalleresultado.IdTarjeta,lab_detalleresultado.IdBacteria,lab_detalleresultado.Cantidad, lab_tarjetasvitek.NombreTarjeta,lab_bacterias.Bacteria
	FROM lab_resultados 
	INNER JOIN lab_detalleresultado ON lab_resultados.IdResultado=lab_detalleresultado.IdResultado
	INNER JOIN lab_bacterias ON lab_detalleresultado.IdBacteria=lab_bacterias.IdBacteria
	INNER JOIN lab_tarjetasvitek ON lab_detalleresultado.IdTarjeta=lab_tarjetasvitek.IdTarjeta
	WHERE IdSolicitudEstudio=$idsolicitud AND IdDetalleSolicitud=$iddetalle";
     $result = pg_query($query);
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
     $query = "SELECT nombrearea,nombre_examen 
               FROM lab_conf_examen_estab
               INNER JOIN mnt_area_examen_establecimiento 
               ON mnt_area_examen_establecimiento.id=lab_conf_examen_estab.idexamen
               INNER JOIN ctl_area_servicio_diagnostico 
               ON ctl_area_servicio_diagnostico.id=mnt_area_examen_establecimiento.id_area_servicio_diagnostico
               WHERE id_atencion=98 AND lab_conf_examen_estab.id=$idexamen";
     $result = pg_query($query);
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
  $query = "SELECT lab_resultadosportarjeta.idantibiotico,antibiotico,resultado,resultado,valor,id_posible_resultado,posible_resultado 
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
//FUNCION PARA OBTENER ANTIBIOTICOS DE UNA TARJETA
 function LeerAntibioticos($idtarjeta)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT DISTINCT lab_antibioticos.id as idantibiotico,antibiotico 
               FROM lab_antibioticos 
               INNER JOIN lab_antibioticosportarjeta ON lab_antibioticos.id=lab_antibioticosportarjeta.idantibiotico
               WHERE lab_antibioticosportarjeta.idtarjeta=$idtarjeta order by antibiotico";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }

 function consultar_resultados($idantibiotico){
     $con = new ConexionBD;
    if($con->conectar()==true)
    {
      $query = "SELECT lab_posible_resultado.id,posible_resultado,descripcion  
                FROM lab_antibiotico_posible_resultado 
                INNER JOIN lab_posible_resultado ON lab_posible_resultado.id=lab_antibiotico_posible_resultado.id_posible_resultado
                WHERE lab_antibiotico_posible_resultado.habilitado=TRUE AND lab_posible_resultado.habilitado=TRUE 
                AND id_antibiotico=$idantibiotico ";  
    $result = pg_query($query);
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
 
//FUNCION PARA LEER LAS BACTERIAS REGISTRADAS
 function LeerBacterias()
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id,bacteria FROM lab_bacterias";
    // echo $query;
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
// FUNCION PARA OBTENER EL NOMBRE DE LA BACTERIA
   function NombreBacteria($idbacteria)
 {
  $query = "SELECT bacteria FROM lab_bacterias WHERE id=$idbacteria";
  $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
  }
  
//FUNCION PARA LEER OBSERVACIONES POR CODIGO
 function LeerObservacion($idobservacion)
 {
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id,observacion FROM lab_observaciones 
               WHERE id=$idobservacion";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
 }
  
//FUNCION PARA LEER TARJETAS VITEK
function LeerTarjeta($lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id,
               nombretarjeta
               FROM lab_tarjetasvitek 
               WHERE idestablecimiento = $lugar AND CURRENT_DATE BETWEEN fechaini AND 
               CASE WHEN fechafin IS NULL THEN CURRENT_DATE
	       ELSE fechafin END";
     //echo $query; 
     $result = pg_query($query);
     //echo $query;
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
  
    //FUNCION PARA LEER OBSERVACIONES POR RESULTADO
 function LeerObservaciones($idarea,$tiporespuesta)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id AS idobservacion,idarea,observacion 
               FROM lab_observaciones
               WHERE idarea=$idarea AND tiporespuesta='$tiporespuesta'";
     $result = pg_query($query);
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
    --and mae.id_area_servicio_diagnostico = $idarea
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
