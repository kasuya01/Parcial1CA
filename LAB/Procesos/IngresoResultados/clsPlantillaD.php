<?php 
include_once("../../../Conexion/ConexionBD.php");
include_once("../../../Conexion/ConexionBDLab.php");
class clsPlantillaD
{
 //constructor	
 function clsPlantillaD (){
 }	

function Nombre_Establecimiento($lugar){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT nombre FROM ctl_establecimiento WHERE id=$lugar";
	 $result = pg_query($query);
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
	 {	$query=" SELECT resultado FROM lab_codigosresultados WHERE id=$tab ";	
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
  {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle=7 WHERE id=$iddetalle"	;	
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 //FUNCION PARA CAMBIAR EL ESTADO DE LA SOLICITUD
 function CambiarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT id,idexamen 
                FROM sec_detallesolicitudestudios WHERE idsolicitudestudio=$idsolicitud 
		AND EstadoDetalle <> 7 AND EstadoDetalle <> 6";
	$detalle=pg_num_rows(pg_query($query));
	if(empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado= 4 WHERE id=$idsolicitud"	;
		$result=pg_query($query);		
		return true;	  
    }else
		return false;
   }
   }


//FUNCION PARA VALIDAR QUE NO EXISTA ESE ELEMENTO DE TINCION EN EL RESULTADO
function ValidacionElemento($idresultado,$idelemento)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {	$query="SELECT COUNT(idresultado) 
                FROM lab_detalleresultado 
                WHERE idresultado=$idresultado AND idelemento=$idelemento";	
        $result = pg_query($query);
	
        if (!$result)
          {return false;}
        else{
            $row= pg_fetch_array($result);
            return $row[0];				
         }	      
    }
 }

//FUNCION PARA ELIMINAR ELEMENTO DE TINCION DEL DETALLE DE RESULTADOS
function EliminarElementoDetalle($idresultado,$idelemento)
{
  $con = new ConexionBD;
  if($con->conectar()==true)
  {	$query="DELETE FROM lab_detalleresultado 
                WHERE  idresultado=$idresultado AND idelemento=$idelemento";	
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return true;	   
  }
 }
 
// funcion para leer las cantidades de tincion
function LeerCantidadTincion()
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id,cantidadtincion FROM lab_cantidadestincion";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}


// 	funcion para leer los elementos de tincion
function LeerElementosTincion()
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT id,elementotincion FROM lab_elementostincion";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}

////**********************************************************************************************************************************************////
//INSERTA RESULTADOS   ENCABEZADO
 function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$responsable,$usuario,$tab,$fecharealiz,$fecharesultado,$lugar)
 {                           
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
       $query = "INSERT INTO lab_resultados
                (idsolicitudestudio,iddetallesolicitud,idexamen,idrecepcionmuestra,     
                idempleado,idusuarioreg,fechahorareg,idestablecimiento,fecha_resultado) 
	        VALUES($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$responsable,$usuario,NOW(),$lugar,'$fecharesultado')RETURNING id";
              
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
                                  VALUES($id_exam_metod, $iddetalle, $tab, $usuario, NOW(),'$fecharealiz','$fecharesultado',$responsable)";
                        
                    $result = pg_query($query);

                    if($result) {
                        $idultimo = $row[0];
                        return $idultimo;
                    }else{
                        return false;
                    }
            }else{
                            return false; // Aqui va la logica si hay mas de una metodologia en el examen
            }
        }else {
            return false;
                    }
    }
 }
// INSERTAR DETALLE DE LOS RESULTADOS POR ELEMENTOS Y SUBELEMENTOS
//ELEMENTOS
function insertar_elemento($ultimo,$idelemento,$idcantidad,$lugar)
 {    
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
       $query = "INSERT INTO lab_detalleresultado(idresultado,idelemento,idcantidad,idestablecimiento) 
	      VALUES($ultimo,$idelemento,$idcantidad,$lugar)";
     $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }

//MOSTRAR ELEMENTOS DE INCION AGREGADOS A UN RESULTADO
function MostrarElementosAgregados($idresultado)
{
$con = new ConexionBD;
   if($con->conectar()==true) 
   {
   $query ="SELECT elementotincion,cantidadTincion 
                 FROM lab_detalleresultado 
                 INNER JOIN lab_elementostincion  ON lab_detalleresultado.idelemento=lab_elementostincion.id
                 INNER JOIN lab_cantidadestincion ON lab_cantidadestincion.id=lab_detalleresultado.idcantidad 
                 WHERE idresultado=$idresultado" ;
         $result = pg_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;	   
   }
}
 
//FUNCION PARA LEER LOS EM,PLEADOS
function DatosEmpleado($idempleado)
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
    $query = "SELECT nombreempleado FROM mnt_empleado WHERE id=$idempleado";
     $result = pg_query($query);
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
      $query = "SELECT TO_CHAR(fecha_resultado,'dd/mm/YYYY HH12:MI:SS') AS FechaResultado
		FROM lab_resultados where idsolicitudestudio=$idsolicitud AND idestablecimiento=$lugar 
                AND IdExamen=$IdExamen";
     
      $result = pg_query($query);
      if (!$result)
        return false;
      else
        return $result;
    }
}


//FUNCION PARA MOSTRAR DATOS GENERALES
function MostrarDatosGenerales($idsolicitud)
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


//FUNCION PARA LEER LOS DATOS DEL AREA Y EL EXAMEN
function LeerDatos($idexamen)
{
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
          $query = "SELECT nombrearea,nombre_reporta 
                    FROM lab_conf_examen_estab 
                    INNER JOIN mnt_area_examen_establecimiento ON mnt_area_examen_establecimiento.id=lab_conf_examen_estab.idexamen
                    INNER JOIN ctl_area_servicio_diagnostico ON ctl_area_servicio_diagnostico.id = mnt_area_examen_establecimiento.id_area_servicio_diagnostico
                    INNER JOIN lab_examen_metodologia ON ( lab_examen_metodologia.id_conf_exa_estab = lab_conf_examen_estab.id)
                    WHERE lab_conf_examen_estab.id=$idexamen";
     
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
}
 
  
////*********************************************************************************************************************************************//// 

//ACTUALIZAR RESULTADOS
 function actualizar($idelemento,$idarea,$nomelemento,$idestandar,$plantilla)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
     $query = "UPDATE lab_elementos SET nombreExamen='$nomelemento', idestandar='$idestandar', idarea='$idarea', 	
			   IdPlantilla='$plantilla' WHERE idelemento='$idelemento'";
     $result = pg_query($query);
	 if (!$result)
       return false;
     else
       return true;
	   
   }
 }

 
 

}//CLASE
?>
