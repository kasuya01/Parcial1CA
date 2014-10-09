<?php 
include_once("../../../Conexion/ConexionBD.php");

class clsConsultarElementos {
 //constructor	
   function clsConsultarElementos() {
   }

   function Nombre_Establecimiento($lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true){ 
            $query="SELECT nombre FROM ctl_establecimiento WHERE id = $lugar";
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
                    WHERE (CURRENT_DATE - '$fechanac') BETWEEN edad_minima_dias AND edad_maxima_dias
                        AND (id != 4 OR LOWER(nombre) != 'todos') AND cod_modulo = 'LAB'";
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
            $query="SELECT t03.idestandar, t03.id
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
    function LlenarResultados($IdEstandar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query="SELECT t01.idresultado,
                           t02.resultado
                    FROM lab_codigosxexamen          t01
                    INNER JOIN lab_codigosresultados t02 ON (t02.id = t01.idresultado)
                    WHERE t02.id != 5 AND t01.idestandar = $IdEstandar";	
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
    function DatosEmpleado($idempleado,$lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
           $query = "SELECT nombreempleado FROM mnt_empleado WHERE id = $idempleado and id_establecimiento = $lugar";
           $result = @pg_query($query);
           $no="no existe";
           if (!$result)
                return $no;
            else
                return $result;

        }
    }

    //FUNCION PARA MOSTRAR DATOS GENERALES
    function MostrarDatosGenerales($idsolicitud,$lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "SELECT DISTINCT t01.idsolicitudestudio,
                             t04.numero AS idnumeroexp,
                             CONCAT_WS(' ',t05.primer_nombre,t05.segundo_nombre,t05.tercer_nombre,t05.primer_apellido,t05.segundo_apellido,t05.apellido_casada) AS nombrepaciente,
                             REPLACE(
                                REPLACE(
                                    REPLACE(
                                        REPLACE(
                                            REPLACE(
                                                REPLACE(
                                                    AGE(t05.fecha_nacimiento::timestamp)::text,
                                                'years', 'años'),
                                            'year', 'año'),
                                        'mons', 'meses'),
                                    'mon', 'mes'),
                                'days', 'días'),
                            'day', 'día') AS edad
                             t10.nombre AS sexo,
                             t08.nombre AS origen,
                             t11.nombre AS procedencia,
                             t01.numeromuestra,
                             TO_CHAR(t01.fechahorareg,'DD/MM/YYYY HH:MI:SS AM') AS fecha,
                             t09.nombre,
                             TO_CHAR(t05.fecha_nacimiento::timestamp, 'DD/MM/YYYY') as fechanacimiento,
                             t10.id AS idsexo,
                             t05.fecha_nacimiento as fechanac
                      FROM lab_recepcionmuestra          t01
                      INNER JOIN sec_solicitudestudios   t02 ON (t02.id = t01.idsolicitudestudio)
                      INNER JOIN sec_historial_clinico   t03 ON (t03.id = t02.id_historial_clinico)
                      INNER JOIN mnt_expediente          t04 ON (t04.id = t03.id_expediente)
                      INNER JOIN mnt_paciente            t05 ON (t05.id = t04.id_paciente)
                      INNER JOIN mnt_aten_area_mod_estab t06 ON (t06.id = t03.idsubservicio)
                      INNER JOIN mnt_area_mod_estab      t07 ON (t07.id = t06.id_area_mod_estab)
                      INNER JOIN ctl_atencion            t08 ON (t08.id = t06.id_atencion)
                      INNER JOIN ctl_establecimiento     t09 ON (t09.id = t03.idestablecimiento)
                      INNER JOIN ctl_sexo                t10 ON (t10.id = t05.id_sexo)
                      INNER JOIN ctl_area_atencion       t11 ON (t11.id = t07.id_area_atencion)
                      WHERE  t01.idsolicitudestudio = $idsolicitud AND t02.id_establecimiento = $lugar 
                         AND t04.id_establecimiento = $lugar";
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
        }
    }

    //FUNCION PARA LEER LOS DATOS DEL AREA Y EL EXAMEN
    function LeerDatos($idexamen) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "SELECT t03.nombrearea,
                             t01.nombre_examen AS nombreexamen
                      FROM lab_conf_examen_estab                 t01
                      INNER JOIN mnt_area_examen_establecimiento t02 ON (t02.id = t01.idexamen)
                      INNER JOIN ctl_area_servicio_diagnostico   t03 ON (t03.id = t02.id_area_servicio_diagnostico)
                      WHERE t01.id = $idexamen";
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
            $query = "SELECT t01.id AS idsubelemento,
                             t01.subelemento,
                             t01.unidad,
                             t01.observsubelem,
                             t01.rangoinicio,
                             t01.rangofin  
                      FROM lab_subelementos t01
                      WHERE t01.idelemento = $idelemento AND t01.idestablecimiento = $lugar
                        AND CURRENT_DATE BETWEEN t01.fechaini AND CASE WHEN fechafin IS NULL THEN CURRENT_DATE ELSE t01.fechafin END
                        AND (t01.idsexo = $sexo OR t01.idsexo IS NULL) AND (idedad = 4 OR idedad = $idedad)
                      ORDER BY t01.subelemento";
            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }
    }

}//CLASE
?>
