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
 function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$resultado,$responsable,$usuario,$codigoResultado,$lugar)
 {
   $con = new ConexionBD;
   //echo $resultado;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_resultados
	      (IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,IdRecepcionMuestra,     
              Observacion,Resultado,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento) 
	      VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$observacion','$resultado','$responsable',$usuario,NOW(),$usuario,NOW(),$codigoResultado,$lugar)";
     $query2="SELECT LAST_INSERT_ID();";

     $result = pg_query($query);
	$result2=pg_query($query2);
		
		//$idultimo= pg_insert_id();

	 
     if ($row=pg_fetch_array($result2)){
	$idultimo=$row[0];
	   return $idultimo;	 
     }else{
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
    $query = "INSERT INTO lab_detalleresultado(IdResultado,IdBacteria,IdTarjeta,Cantidad,IdEstablecimiento) 
			  VALUES($idresultado,$ibacteria,$idtarjeta,'$cantidad',$lugar)";
	$query2="SELECT LAST_INSERT_ID();";

     $result = pg_query($query);
	$result2=pg_query($query2);
		
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
function insertar_resultadoantibioticos($iddetalleresultado,$idantibiotico,$resultado,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_resultadosportarjeta(IdDetalleResultado,IdAntibiotico,Resultado,IdEstablecimiento) 
			  VALUES($iddetalleresultado,$idantibiotico,'$resultado',$lugar)";
     $result = pg_query($query);
	//echo $query; 
     if (!$result)
       return false;
     else
       return true;	   
	   //return $query;
   }
 }
////*********************************************************************************************************************************************//// 
//FUNCION PARA CAMBIAR EL ESTADO DEL DETALLLE DE LA SOLICITUD
 function CambiarEstadoDetalle($iddetalle)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) //Estado RC--> Resultados Completo
  {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle=5 WHERE id=$iddetalle";	
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 
 function CambiarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT  IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio=$idsolicitud 
			AND EstadoDetalle <>'RC' AND EstadoDetalle <>'RM'";
	$detalle=pg_num_rows(pg_query($query));
	if(empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud"	;
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
     $query = "SELECT CONCAT_WS(' ',nombre,NULL,apellido) as empleado 
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
   {
      $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado
		FROM lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar AND IdExamen='$IdExamen'";
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
    echo $query = "SELECT distinct lab_recepcionmuestra.IdSolicitudEstudio, sec_historial_clinico.IdNumeroExp,
CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
  (year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
			   TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
			   NumeroMuestra,DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento
			   FROM lab_recepcionmuestra 
			   INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
			   INNER JOIN sec_historial_clinico  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			   INNER JOIN mnt_expediente  ON mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
			   INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
			   INNER JOIN mnt_subservicio  ON mnt_subservicio.IdSubServicio=sec_historial_clinico.IdSubServicio
			   INNER JOIN mnt_servicio  ON mnt_servicio.IdServicio= mnt_subservicio .IdServicio
			   WHERE lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud";
    /*SELECT distinct lab_recepcionmuestra.idsolicitudestudio, sec_historial_clinico.id_numero_expediente,
CONCAT_WS(' ',mnt_paciente.primer_nombre,mnt_paciente.segundo_nombre,mnt_paciente.tercer_nombre,mnt_paciente.primer_apellido,
                       mnt_paciente.segundo_apellido,mnt_paciente.apellido_casada) AS paciente,
mnt_dato_referencia.id_expediente_referido,
CONCAT_WS(' ',mnt_paciente_referido.primer_nombre,mnt_paciente_referido.segundo_nombre,mnt_paciente_referido.tercer_nombre,mnt_paciente_referido.primer_apellido,mnt_paciente_referido.segundo_apellido,
mnt_paciente_referido.apellido_casada) AS paciente_referido, (year(CURRENT_DATE)-year(fechanacimiento))AS Edad
FROM lab_recepcionmuestra
INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.id = lab_recepcionmuestra.idsolicitudestudio
LEFT JOIN sec_historial_clinico  ON sec_historial_clinico.id = sec_solicitudestudios.id_historial_clinico
LEFT JOIN mnt_expediente ON mnt_expediente.id = sec_historial_clinico.id_numero_expediente
LEFT JOIN mnt_paciente  ON mnt_paciente.id=mnt_expediente.id_paciente
LEFT JOIN mnt_dato_referencia ON mnt_dato_referencia.id=sec_solicitudestudios.id_dato_referencia
LEFT JOIN mnt_expediente_referido ON mnt_expediente_referido.id=mnt_dato_referencia.id_expediente_referido
LEFT JOIN mnt_paciente_referido ON mnt_paciente_referido.id=mnt_expediente_referido.id_referido*/
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

//FUNCION PARA OBTENER ANTIBIOTICOS DE UNA TARJETA
 function LeerAntibioticos($idtarjeta)
 {
   $con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT DISTINCT lab_antibioticos.id as idantibiotico,antibiotico 
               FROM lab_antibioticos 
               INNER JOIN lab_antibioticosportarjeta ON lab_antibioticos.id=lab_antibioticosportarjeta.idantibiotico
               WHERE lab_antibioticosportarjeta.idtarjeta=$idtarjeta";
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
     $query = "SELECT id,idarea,observacion 
               FROM lab_observaciones
               WHERE idarea=$idarea AND tiporespuesta='$tiporespuesta'";
     $result = pg_query($query);
     if (!$result)
       return false;
     else
       return $result;
    }
  }
  
}//CLASE
?>
