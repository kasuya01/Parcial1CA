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
    function ObtenerFechaResultado($idsolicitud,$IdExamen,$lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "SELECT TO_CHAR(fecha_resultado,'DD/MM/YYYY') AS fecharesultado
                      FROM lab_resultados
                      WHERE idsolicitudestudio = $idsolicitud AND idestablecimiento = $lugar AND idexamen = $IdExamen";
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
  {	$query=" SELECT resultado FROM lab_codigosresultados WHERE id=$tab ";	
$result = pg_query($query);
if (!$result)
    return false;
else
    return $result;	   
}
}

    //FUNCION PARA CAMBIAR EL ESTADO DEL DETALLLE DE LA SOLICITUD
    function CambiarEstadoDetalle($iddetalle) {
        $con = new ConexionBD;
        if($con->conectar()==true) { //Estado RC--> Resultados Completo
            $query="UPDATE sec_detallesolicitudestudios SET estadodetalle = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = 'RC') WHERE id = $iddetalle";	
            
            $result = @pg_query($query);
       
            if (!$result)
                return false;
            else
                return true;	   
        }
    }

    function CambiarEstadoSolicitud($idsolicitud) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query="SELECT id AS iddetallesolicitud,
                           id_conf_examen_estab AS idexamen
                    FROM sec_detallesolicitudestudios 
                    WHERE idsolicitudestudio = $idsolicitud
                        AND estadodetalle NOT IN (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado IN ('RC', 'RM'))";
       
            $detalle = pg_num_rows(pg_query($query));
           
            if(empty($detalle)) {
                $query = "UPDATE sec_solicitudestudios SET estado = (SELECT id FROM ctl_estado_servicio_diagnostico WHERE idestado = 'C') WHERE id = $idsolicitud";
                
                $result=@pg_query($query);
                
                return true;	  
            } else {
                return false;
            }
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
////************************************************************************************************************************************////
//INSERTA RESULTADOS   ENCABEZADO
    function insertar_encabezado($idsolicitud,$iddetalle,$idexamen,$idrecepcion,$observacion,$responsable,$usuario,$tab,$fecharealiz,$fecharesultado,$lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "INSERT INTO lab_resultados (idsolicitudestudio,iddetallesolicitud,idexamen,idrecepcionmuestra,     
                      observacion,idempleado,idusuarioreg,fechahorareg,idestablecimiento,fecha_resultado) 
                      VALUES($idsolicitud,$iddetalle,$idexamen,$idrecepcion,'$observacion',$responsable,$usuario,date_trunc('seconds',NOW()),$lugar,'$fecharesultado')
                      RETURNING id";
           //$query;
            $result = pg_query($query);

            if ($row = pg_fetch_array($result)) {

                $query = "SELECT id FROM lab_examen_metodologia WHERE id_conf_exa_estab = $idexamen AND activo = true";
                $result = pg_query($query);

                if($result && pg_num_rows($result) == 1) {
                    $row_exam_metod = pg_fetch_array($result);

                    $id_exam_metod = $row_exam_metod[0];

                 $query = "INSERT INTO lab_resultado_metodologia(id_examen_metodologia, id_detallesolicitudestudio, id_codigoresultado, idusuarioreg, fechahorareg,fecha_realizacion,fecha_resultado,id_empleado)
                            VALUES($id_exam_metod, $iddetalle, $tab, $usuario, date_trunc('seconds',NOW()),'$fecharealiz','$fecharesultado',$responsable)";

                    $result = pg_query($query);

                    if($result) {
                        $idultimo = $row[0];
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

    // INSERTAR DETALLE DE LOS RESULTADOS POR ELEMENTOS Y SUBELEMENTOS
    //ELEMENTOS
    function insertar_elementos($idresultado,$idelemento,$resultado,$control_ele,$lugar) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            $query = "INSERT INTO lab_detalleresultado(idresultado,idelemento,resultado,observacion,idestablecimiento) 
                      VALUES($idresultado,$idelemento,'$resultado','$control_ele',$lugar)";
            
            $result = @pg_query($query);

            if (!$result)
                return false;
            else
                return true;	   
        }
    }

    //SUBELEMENTOS ,$posresult
    function insertar_subelementos($idresultado,$idsubelemento,$resultado,$control,$lugar,$pos) {
        $con = new ConexionBD;
        if($con->conectar()==true) {
            if ($pos==NULL){
               $query = "INSERT INTO lab_detalleresultado(idresultado,idsubelemento,resultado,observacion,idestablecimiento) 
                          VALUES($idresultado,$idsubelemento,'$resultado','$control',$lugar)";
            }
            else{
                $query1="SELECT posible_resultado FROM lab_posible_resultado where id=$resultado";
                $result1 = pg_query($query1);
                $row= pg_fetch_array($result1);
                
               // $dato = $result1;
                if($result1) {
                    $dato = $row[0];
                    $query = "INSERT INTO lab_detalleresultado(idresultado,idsubelemento,resultado,id_posible_resultado,observacion,idestablecimiento) 
                              VALUES($idresultado,$idsubelemento,'$dato','$resultado','$control',$lugar)";                           
                }
            }    

            $result = @pg_query($query);

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
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t04.numero
                                 ELSE t13.numero
                             END AS idnumeroexp,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN 
                                     CONCAT_WS(' ',t05.primer_nombre,t05.segundo_nombre,t05.tercer_nombre,t05.primer_apellido,t05.segundo_apellido,t05.apellido_casada) 
                                 ELSE
                                     CONCAT_WS(' ',t14.primer_nombre,t14.segundo_nombre,t14.tercer_nombre,t14.primer_apellido,t14.segundo_apellido,t14.apellido_casada) 
                             END AS nombrepaciente,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN 
                                     REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( AGE(t05.fecha_nacimiento::timestamp)::text, 'years', 'años'), 'year', 'año'), 'mons', 'meses'), 'mon', 'mes'), 'days', 'días'), 'day', 'día')
                                 ELSE
                                     REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( REPLACE( AGE(t14.fecha_nacimiento::timestamp)::text, 'years', 'años'), 'year', 'año'), 'mons', 'meses'), 'mon', 'mes'), 'days', 'días'), 'day', 'día')
                             END AS edad,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t10.nombre
                                 ELSE (SELECT nombre FROM ctl_sexo WHERE id = t14.id_sexo)
                             END AS sexo, 
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t08.nombre
                                 ELSE (SELECT ti02.nombre
                                     FROM mnt_aten_area_mod_estab  ti01
                                     INNER JOIN ctl_atencion       ti02 ON (ti02.id = ti01.id_atencion)
                                     WHERE ti01.id = t12.id_aten_area_mod_estab)
                             END AS origen,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t11.nombre
                                 ELSE (SELECT ti03.nombre
                                     FROM mnt_aten_area_mod_estab  ti01
                                     INNER JOIN mnt_area_mod_estab ti02 ON (ti02.id = ti01.id_area_mod_estab)
                                     INNER JOIN ctl_area_atencion  ti03 ON (ti03.id = ti02.id_area_atencion)
                                     WHERE ti01.id = t12.id_aten_area_mod_estab)
                             END AS procedencia, 
                             t01.numeromuestra,
                             TO_CHAR(t01.fechahorareg,'DD/MM/YYYY') AS fecha,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t09.nombre
                                 ELSE (SELECT nombre FROM ctl_establecimiento WHERE id = t12.id_establecimiento)
                             END AS nombre,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN TO_CHAR(t05.fecha_nacimiento::timestamp, 'DD/MM/YYYY')
                                 ELSE TO_CHAR(t14.fecha_nacimiento::timestamp, 'DD/MM/YYYY')
                             END AS fechanacimiento, 
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t10.id
                                 ELSE t14.id_sexo
                             END AS idsexo,
                             CASE WHEN t02.id_historial_clinico IS NOT NULL
                                 THEN t05.fecha_nacimiento
                                 ELSE t14.fecha_nacimiento
                             END AS fechanac
                      FROM lab_recepcionmuestra                t01
                      INNER JOIN sec_solicitudestudios         t02 ON (t02.id = t01.idsolicitudestudio)
                      LEFT OUTER JOIN sec_historial_clinico    t03 ON (t03.id = t02.id_historial_clinico)
                      LEFT OUTER JOIN mnt_expediente           t04 ON (t04.id = t03.id_numero_expediente)
                      LEFT OUTER JOIN mnt_paciente             t05 ON (t05.id = t04.id_paciente)
                      LEFT OUTER JOIN mnt_aten_area_mod_estab  t06 ON (t06.id = t03.idsubservicio)
                      LEFT OUTER JOIN mnt_area_mod_estab       t07 ON (t07.id = t06.id_area_mod_estab)
                      LEFT OUTER JOIN ctl_atencion             t08 ON (t08.id = t06.id_atencion)
                      LEFT OUTER JOIN ctl_establecimiento      t09 ON (t09.id = t03.idestablecimiento)
                      LEFT OUTER JOIN ctl_sexo                 t10 ON (t10.id = t05.id_sexo)
                      LEFT OUTER JOIN ctl_area_atencion        t11 ON (t11.id = t07.id_area_atencion)
                      LEFT OUTER JOIN mnt_dato_referencia      t12 ON (t12.id = t02.id_dato_referencia)
                      LEFT OUTER JOIN mnt_expediente_referido  t13 ON (t13.id = t12.id_expediente_referido)
                      LEFT OUTER JOIN mnt_paciente_referido    t14 ON (t14.id = t13.id_referido)
                      WHERE  t01.idsolicitudestudio = $idsolicitud AND t02.id_establecimiento = $lugar 
                      AND CASE WHEN t02.id_historial_clinico IS NOT NULL THEN t04.id_establecimiento 
                      ELSE t13.id_establecimiento END = $lugar";
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
         $query = "SELECT t03.nombrearea,t04.nombre_reporta 
                     FROM lab_conf_examen_estab                 t01
                     INNER JOIN mnt_area_examen_establecimiento t02 ON (t02.id = t01.idexamen)
                     INNER JOIN ctl_area_servicio_diagnostico   t03 ON (t03.id = t02.id_area_servicio_diagnostico)
                     INNER JOIN lab_examen_metodologia t04 ON ( t04.id_conf_exa_estab = t01.id)
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
                      ORDER BY t01.orden";

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
                      ORDER BY t01.orden";
            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }
    }
    
    function leer_posibles_resultados($idsubelemento){
         $con = new ConexionBD;
        if($con->conectar()==true) {
             $query="SELECT id_posible_resultado,posible_resultado, t01.habilitado
                    FROM lab_subelemento_posible_resultado t01
                    INNER JOIN lab_posible_resultado t02 ON t02.id = t01.id_posible_resultado
                    WHERE id_subelemento=$idsubelemento
                    and t01.habilitado=true
                    order by char_length(posible_resultado),posible_resultado desc;";

            $result = @pg_query($query);
            
            if (!$result)
                return false;
            else
                return $result;
        }    
    }
    function contar_posibles_resultados($idsubelemento){
         $con = new ConexionBD;
        if($con->conectar()==true) {
          $query="SELECT count(*) 
                    FROM lab_subelemento_posible_resultado 
                    INNER JOIN lab_posible_resultado ON lab_posible_resultado.id = lab_subelemento_posible_resultado.id_posible_resultado
                    WHERE id_subelemento=$idsubelemento
                    and lab_subelemento_posible_resultado.habilitado=true";
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
    
    
    function DatosConsulta($IdHistorial,$lugar){
          $con = new ConexionBD;
        if($con->conectar()==true) {
             $query = "SELECT t07.peso,t07.talla,t06.sct_name_es AS diagnostico,especificacion,conocido_por
                        FROM sec_historial_clinico               t01
                        INNER JOIN mnt_expediente                t02 ON (t02.id = t01.id_numero_expediente)
                        INNER JOIN mnt_paciente                  t03 ON (t03.id = t02.id_paciente)
                        LEFT OUTER JOIN sec_diagnostico_paciente t04 ON (t01.id = t04.id_historial_clinico)
                        LEFT OUTER JOIN mnt_snomed_cie10         t06 ON (t06.id = t04.id_snomed)
                        LEFT OUTER JOIN sec_signos_vitales       t07 ON (t01.id = t07.id_historial_clinico)
                        WHERE t01.id = $IdHistorial AND t01.idestablecimiento = $lugar";
             $result = @pg_query($query);
             if (!$result)
                return false;
            else
                return $result;
        }
        
    }
    //Buscar si existen solicitudes anteriores con ese mismo detalle.
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
          order by nombre_examen";
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
