<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsConsultarElementos {
 //constructor	
   function clsConsultarElementos() {
   }

   function Nombre_Establecimiento($lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true){ 
            $query="SELECT Nombre FROM mnt_establecimiento WHERE IdEstablecimiento=$lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    /*function CalculoDias($fechanac) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query="SELECT DATEDIFF(NOW( ),'$fechanac')";
            $result = @pg_query($query);
            if (!$result)
               return false;
            else
               return $result;
        }
    }*/

    function ObtenerCodigoRango($fechanac){
        $con = new ConexionBD;
        if($con->conectar()==true){ 
            $query="SELECT * 
                    FROM ctl_rango_edad
                    WHERE DATE_PART('day',AGE('$fechanac')) BETWEEN edad_minima_dias AND edad_maxima_dias
                        AND id != 4 OR LOWER(nombre) != 'todos' AND cod_modulo = 'LAB'";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    function Obtener_Estandar($idexamen){
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query="SELECT t03.idestandar
            FROM lab_conf_examen_estab                 t01
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


//FUNCION FECHA DE RESULTADO
function ObtenerFechaResultado($idsolicitud,$IdExamen,$lugar)
{
	$con = new ConexionBD;
 if($con->conectar()==true)
 {
  $query = "SELECT DATE_FORMAT(FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS FechaResultado
  FROM lab_resultados where IdSolicitudEstudio=$idsolicitud AND IdEstablecimiento=$lugar AND IdExamen='$IdExamen'";
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
     {	$query="SELECT lab_codigosxexamen.IdResultado,Resultado
 FROM lab_codigosxexamen
 INNER JOIN lab_codigosresultados 
 ON lab_codigosxexamen.IdResultado = lab_codigosresultados.IdResultado
 WHERE lab_codigosresultados.IdResultado<> 5 AND lab_codigosxexamen.IdEstandar = '$IdEstandar'";	
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
  {	$query=" SELECT Resultado FROM lab_codigosresultados WHERE IdResultado=$tab ";	
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
   {	$query="UPDATE sec_detallesolicitudestudios SET estadodetalle='RC' WHERE IdDetalleSolicitud=$iddetalle"	;	
   $result = @pg_query($query);
   if (!$result)
     return false;
 else
     return true;	   
}
}

function CambiarEstadoSolicitud($idsolicitud){
  $con = new ConexionBD;
  if($con->conectar()==true){ 
   $query="SELECT IdDetalleSolicitud,IdExamen FROM sec_detallesolicitudestudios 
   WHERE IdSolicitudEstudio=$idsolicitud AND EstadoDetalle <>'RC' AND EstadoDetalle <>'RM'";
   $detalle=pg_num_rows(pg_query($query));
   if(empty($detalle)){
     $query="UPDATE sec_solicitudestudios SET estado='C' WHERE IdSolicitudEstudio=$idsolicitud"	;
     $result=@pg_query($query);		
     return true;	  
 }else
 return false;
}
}

////************************************************************************************************************************************////
//INSERTA RESULTADOS   ENCABEZADO
function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$responsable,$usuario,$tab,$lugar)
{
 $con = new ConexionBD;
 if($con->conectar()==true) 
 {
    $query = "INSERT INTO lab_resultados
    (IdSolicitudEstudio,IdDetalleSolicitud,IdExamen,IdRecepcionMuestra,     
      Observacion,Responsable,IdUsuarioReg,FechaHoraReg,IdUsuarioMod,FechaHoraMod,IdCodigoResultado,IdEstablecimiento) 
VALUES($idsolicitud,$iddetalle,'$idexamen',$idrecepcion,'$observacion','$responsable',$usuario,NOW(),$usuario,NOW(),$tab,$lugar)";
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

// INSERTAR DETALLE DE LOS RESULTADOS POR ELEMENTOS Y SUBELEMENTOS
//ELEMENTOS
function insertar_elementos($idresultado,$idelemento,$resultado,$control_ele,$lugar)
{
 $con = new ConexionBD;
 if($con->conectar()==true) 
 {
    $query = "INSERT INTO lab_detalleresultado(IdResultado,IdElemento,Resultado,Observacion,IdEstablecimiento) 
    VALUES($idresultado,$idelemento,'$resultado','$control_ele',$lugar)";
    $result = @pg_query($query);
	//echo  $query;
    if (!$result)
     return false;
 else
     return true;	   
}
}

//SUBELEMENTOS
function insertar_subelementos($idresultado,$idsubelemento,$resultado,$control,$lugar)
{
 $con = new ConexionBD;
 if($con->conectar()==true) 
 {
    $query = "INSERT INTO lab_detalleresultado(IdResultado,IdSubElemento,Resultado,Observacion,IdEstablecimiento) 
    VALUES($idresultado,$idsubelemento,'$resultado','$control',$lugar)";
    $result = @pg_query($query);
	// echo  $query;
    if (!$result)
     return false;
 else
     return true;	   
}
}
////********************************************************************************************************************************//// 


//FUNCION PARA LEER DATOS LOS EMPLEADOS
function DatosEmpleado($idempleado,$lugar)
{
    $con = new ConexionBD;
    if($con->conectar()==true)
    {
       $query = "SELECT NombreEmpleado FROM mnt_empleados WHERE idempleado='$idempleado' and IdEstablecimiento=$lugar";
       $result = @pg_query($query);
       $no="no existe";
       if (!$result)
         return $no;
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
   $query = "SELECT DISTINCT  lab_recepcionmuestra.IdSolicitudEstudio, sec_historial_clinico.IdNumeroExp,
   CONCAT_WS(' ',PrimerNombre,NULL,SegundoNombre,NULL,PrimerApellido,NULL,SegundoApellido) AS NombrePaciente,
   (year(CURRENT_DATE)-year(FechaNacimiento))AS Edad,IF(Sexo=1,'Masculino','Femenino') AS Sexo,
   NombreSubServicio AS Origen,NombreServicio AS Procedencia,NumeroMuestra,
   DATE_FORMAT(lab_recepcionmuestra.FechaHoraReg,'%d/%m/%Y %H:%i:%s') AS Fecha,mnt_establecimiento.Nombre,
   DATE_FORMAT(FechaNacimiento,'%d/%m/%Y') as FechaNacimiento,Sexo AS idsexo,FechaNacimiento as fechanac
   FROM lab_recepcionmuestra 
   INNER JOIN sec_solicitudestudios  ON sec_solicitudestudios.IdSolicitudEstudio= lab_recepcionmuestra.IdSolicitudEstudio
   INNER JOIN sec_historial_clinico  ON sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
   INNER JOIN mnt_expediente  ON  mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
   INNER JOIN mnt_datospaciente  ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente 
   INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio= sec_historial_clinico.IdSubServicio
   INNER JOIN mnt_servicio ON mnt_servicio .IdServicio= mnt_subservicio.IdServicio
   INNER JOIN mnt_establecimiento ON sec_historial_clinico.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
   WHERE  lab_recepcionmuestra.IdSolicitudEstudio=$idsolicitud and sec_solicitudestudios.IdEstablecimiento=$lugar 
   AND mnt_expediente.IdEstablecimiento=$lugar";
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
   $query = "SELECT NombreArea,NombreExamen 
   FROM lab_examenes 
   INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea
   WHERE lab_examenes.IdExamen='$idexamen'";
   $result = @pg_query($query);
   if (!$result)
     return false;
 else
     return $result;
}
}

    //FUNCION PARA OBTENER ELEMENTOS DE UN EXAMEN PLANTILLA B
    function LeerElementosExamen($idexamen, $lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "SELECT t01.id AS idelemento,
                             t01.elemento,
                             t01.unidadelem,
                             t01.subelemento,
                             t04.nombrearea,
                             t02.nombre_examen AS nombreexamen,
                             t01.observelem
                      FROM lab_elementos                         t01
                      INNER JOIN lab_conf_examen_estab           t02 ON (t02.id = t01.id_conf_examen_estab)
                      INNER JOIN mnt_area_examen_establecimiento t03 ON (t03.id = t02.idexamen)
                      INNER JOIN ctl_area_servicio_diagnostico   t04 ON (t04.id = t03.id_area_servicio_diagnostico)
                      WHERE t02.id = $idexamen AND t01.idestablecimiento = $lugar  
                        AND CURRENT_DATE BETWEEN t01.fechaini AND CASE WHEN fechafin IS NULL THEN CURRENT_DATE ELSE t01.fechafin END
                      ORDER BY t01.elemento";

            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA PBTENER SUB-ELEMENTOS DE UN EXAMEN PLANTILLA B
    function LeerSubElementosExamen($idelemento,$lugar,$sexo,$idedad) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "SELECT IdSubElemento,SubElemento,Unidad,ObservSubElem,rangoinicio,rangofin  
                      FROM lab_subelementos 
                      WHERE idelemento=$idelemento AND lab_subelementos.IdEstablecimiento=$lugar AND 
                        CURDATE() BETWEEN FechaIni AND 
                        IF(lab_subelementos.FechaFin ='0000-00-00',CURDATE(),lab_subelementos.FechaFin) 
                        AND (lab_subelementos.idsexo=$sexo OR lab_subelementos.idsexo=3) AND (idedad=4 OR idedad=$idedad)
                      ORDER BY IdSubElemento";
            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }
    }

}//CLASE
?>
