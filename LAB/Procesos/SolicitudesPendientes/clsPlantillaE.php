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
       	$query="SELECT Nombre FROM mnt_establecimiento WHERE IdEstablecimiento=$lugar";
	 $result = @mysql_query($query);
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
	  {	$query="SELECT lab_codigosxexamen.IdResultado,Resultado
                        FROM lab_codigosxexamen
                        INNER JOIN lab_codigosresultados 
                        ON lab_codigosxexamen.IdResultado = lab_codigosresultados.IdResultado
                        WHERE lab_codigosresultados.IdResultado<> 5 AND lab_codigosxexamen.IdEstandar = '$IdEstandar'"	;	
	     $result = mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
   	   }
 }
 
 //FUNCION FECHA DE RESULTADO
function ObtenerFechaResultado($idsolicitud,$idarea,$lugar)
{
	$con = new ConexionBD;
   if($con->conectar()==true)
   {
      $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado
		FROM lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar
		AND IdExamen like '$idarea%'";
     $result = @mysql_query($query);
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
	 {   $query=" SELECT Resultado FROM lab_codigosresultados WHERE IdResultado=$tab ";	
	     $result = mysql_query($query);
	     if (!$result)
	       return false;
	     else
	       return $result;	   
	 }
}

function Obtener_Estandar($idexamen){
   $con = new ConexionBD;
   if($con->conectar()==true){ 
       	$query="SELECT IdEstandar FROM lab_examenes WHERE IdExamen='$idexamen'";
	 $result = @mysql_query($query);
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
	 $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return $result;
   }
}

function ObtenerCodigoRango($dias){
   $con = new ConexionBD;
   if($con->conectar()==true){  
       $query="SELECT * FROM mnt_rangoedad WHERE $dias BETWEEN edadini AND edadfin
            AND idedad <>4";
        $result = @mysql_query($query);
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
  {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle='RC' WHERE IdDetalleSolicitud=$iddetalle"	;	
     $result = @mysql_query($query);
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 /*FUNCION PARA CAMBIAR ESTADO DE LA SOLICITUD*/
 function CambiarEstadoSolicitud($idsolicitud,$lugar){
  $con = new ConexionBD;
   if($con->conectar()==true){ 
	$query="SELECT IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio=$idsolicitud AND EstadoDetalle <>'RC' AND EstadoDetalle <>'RM'";
	$detalle=mysql_num_rows(mysql_query($query));
	if(empty($detalle)){
		$query="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar"	;
		$result=@mysql_query($query);		
		return true;	  
    }else
		return false;
   }
   }

//INSERTA RESULTADOS ENCABEZADO
 function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$idempleado,$usuario,$tab,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_resultados
	         (IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,IdRecepcionMuestra,Responsable,Observacion,
			  IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento) 
			  VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$idempleado','$observacion',
			  $usuario,NOW(),$usuario,NOW(),$tab,$lugar)";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       $idultimo= @mysql_insert_id();
	   return $idultimo;	   
   }
 }
 
 //FUNCION PARA INSERTAR EL DETALLE DE LOS RESULTADOS DE LOS PROCESOS
 function insertar_detalle($idresultado,$idproceso,$resultado,$comentario,$lugar)
 {
   $con = new ConexionBD;
   if($con->conectar()==true) 
   {
    $query = "INSERT INTO lab_detalleresultado(IdResultado,IdProcedimiento,Resultado,Observacion,IdEstablecimiento) 
			  VALUES($idresultado,$idproceso,'$resultado','$comentario',$lugar)";
     $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
//FUNCION PARA LEER LOS EM,PLEADOS
function DatosEmpleado($idempleado)
{
$con = new ConexionBD;
   if($con->conectar()==true)
   {
     $query = "SELECT NombreEmpleado FROM mnt_empleados WHERE idempleado='$idempleado'";
     $result = @mysql_query($query);
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
     $query = "SELECT DISTINCT lab_recepcionmuestra.IdSolicitudEstudio, sec_historial_clinico.IdNumeroExp,
				CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
				(year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,
				IF(Sexo=1,'Masculino','Femenino') AS Sexo,
				TelefonoCasa,Direccion,NombreSubServicio AS Origen,NombreServicio AS Procedencia,
				NumeroMuestra,
				DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,DATE_FORMAT(mnt_datospaciente.FechaNacimiento,'%d/%m/%Y') as FechaNacimiento
				FROM lab_recepcionmuestra 
				INNER JOIN sec_solicitudestudios ON sec_solicitudestudios .IdSolicitudEstudio=lab_recepcionmuestra.IdSolicitudEstudio
				INNER JOIN sec_historial_clinico  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios .IdHistorialClinico
				INNER JOIN mnt_expediente ON mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
				INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
				INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
				INNER JOIN mnt_servicio ON mnt_servicio.IdServicio= mnt_subservicio.IdServicio
				WHERE lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud";
     $result = @mysql_query($query);
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
     $query = "SELECT NombreArea,NombreExamen 
				FROM lab_examenes AS b
				INNER JOIN lab_areas AS c ON b.IdArea=c.IdArea
				WHERE b.IdExamen='$idexamen'";
     $result = @mysql_query($query);
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
    $query = "INSERT INTO lab_detalleresultado(IdResultado,IdProceso,Resultado,Observacion ) 
			  VALUES($idresultado,$idproceso,$resultadodetalle,$observacion)";
     $result = mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return true;	   
   }
 }
 
 //FUNCION PARA LEER LOS PROCESO POR EXAMEN
function LeerProcesoExamen($idexamen,$sexo,$idedad)
{
	$con = new ConexionBD;
   if($con->conectar()==true) 
   {
	$query ="SELECT idprocedimientoporexamen,nombreprocedimiento,unidades,rangoinicio,rangofin 
                 FROM lab_procedimientosporexamen
                 WHERE idexamen='$idexamen' AND (lab_procedimientosporexamen.idsexo=$sexo OR lab_procedimientosporexamen.idsexo=3) 
                     AND (lab_procedimientosporexamen.idedad=4 OR lab_procedimientosporexamen.idedad=$idedad)";
	 $result = @mysql_query($query);
	 
     if (!$result)
       return false;
     else
       return $result;   
   }
}

 
}//CLASE
?>
