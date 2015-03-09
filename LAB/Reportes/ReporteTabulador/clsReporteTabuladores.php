<?php 
//include_once("../../../Conexion/ConexionBD.php");
        //include($_SERVER['DOCUMENT_ROOT']."/Laboratorio/Conexion/ConexionBD.php");
include_once("DBManager.php"); 
//implementamos la clase lab_areas
class clsReporteTabuladores
{
	 //constructor	
	 function clsReporteTabuladores()
	 {
	 }	
	
   //FN Pg
   //
   //
   function ExamenesPorArea($idarea,$lugar)
   {
	$con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
            $query = "SELECT t02.id,
                     t02.codigo_examen AS idexamen,
                     t02.nombre_examen AS nombreexamen,
                     t03.idestandar
                     FROM mnt_area_examen_establecimiento t01
                     INNER JOIN lab_conf_examen_estab     t02 ON (t01.id = t02.idexamen)
                     JOIN ctl_examen_servicio_diagnostico t03 ON (t03.id = t01.id_examen_servicio_diagnostico)
                 WHERE t01.id_establecimiento = $lugar AND t01.id_area_servicio_diagnostico = $idarea
                     AND t02.condicion = 'H'
                 ORDER BY t02.nombre_examen";
           // $query;
            $result = @pg_query($query);
            if (!$result)
                return false;
            else
                return $result;
	}
   }
                //Funcion utilizada para el tabulador para Servicio d eProcedencia 
 	public function prxmes($mes, $anio, $lugar){
           $con = new ConexionBD;
    //usamos el metodo conectar para realizar la conexion
	if($con->conectar()==true){
	$sql = "select distinct(t02.idexamen) as id_pruebadetsol
               from sec_detallesolicitudestudios 	t01 
               join lab_resultados 			t02 on (t01.id=t02.iddetallesolicitud)
               join ctl_establecimiento		t03 on (t03.id=t02.idestablecimiento)
               where  extract('year' from fecha_resultado)=$anio	
               and extract('month' from fecha_resultado)=$mes
               and estadodetalle in (6,7)
               and t02.idestablecimiento=$lugar
               order by t02.idexamen";	
     //   echo '<br>'.$sql.'<br/>';
	$result=  @pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}
   }//fin de la funcion consultarTipoResultado


   public function pruebasid($id){
      $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
       $sql = "select t01.id, t02.idestandar as v_codprueba, t02.descripcion 
            from lab_conf_examen_estab 	t01
            join ctl_examen_servicio_diagnostico	t02 on (t02.id=t01.idestandarrep)
            where t01.id=$id";
//       $sql = "select t01.id, t03.idestandar, t03.descripcion  
//            from lab_conf_examen_estab t01
//            join mnt_area_examen_establecimiento t02 on (t02.id=t01.idexamen)
//            join ctl_examen_servicio_diagnostico t03 on (t03.id=t02.id_examen_servicio_diagnostico)
//            where t01.id =$id";
           $result =  @pg_query($sql);
       if (!$result)
         return false;
       else
         return $result;
      }
   }
 
//Funcion utilizada para el tabulador para resultado
 	public function prxdia($idpr, $idcod, $dia, $mes, $anio, $lugar){
           $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
	$sql = "select count (case when id_codigoresultado=$idcod then 'uno' else null end) as res 
               from lab_resultado_metodologia t01
               join lab_examen_metodologia t02 on (t02.id=t01.id_examen_metodologia)
               join sec_detallesolicitudestudios t03 on (t03.id=t01.id_detallesolicitudestudio)
               where id_conf_exa_estab=$idpr
               and extract('year' from fecha_resultado)=$anio
               and extract('month' from fecha_resultado)=$mes
               and extract('day' from fecha_resultado)=$dia
               and estadodetalle in (6,7)
               and t03.idestablecimiento=$lugar;";	
      //  echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
        }
   
    //Funcion utilizada para el tabulador para Servicio d eProcedencia 
   public function prxservicio($idpr, $lugar, $dia, $mes, $anio){
         $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
   $sql = "select 
   count (case when id_area_atencion=1 and id_servicio_externo_estab is null  then 'uno' else null end) as uno,
   count (case when id_area_atencion=3 and id_servicio_externo_estab is null then 'dos' else null end) as dos,
   count (case when id_area_atencion=2 and id_servicio_externo_estab is null  then 'tres' else null end) as tres
   --count (case when id_servicio_externo_estab is not null then 'cinco' else null end) as otros
   from sec_detallesolicitudestudios t00 	
   join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
   join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
   join sec_historial_clinico t02 on (t02.id=t01.id_historial_clinico)
   join mnt_aten_area_mod_estab t03 on (t03.id=t02.idsubservicio)
   join mnt_area_mod_estab	 t04  on (t04.id=t03.id_area_mod_estab)
   left join mnt_servicio_externo_establecimiento t06 on (t06.id=t04.id_servicio_externo_estab)
   where estadodetalle in (6,7)
   --and id_servicio_externo_estab is null
   and t00.idestablecimiento=$lugar
   and id_conf_examen_estab=$idpr
   and extract('year' from fecha_resultado)=$anio
   and extract('month' from fecha_resultado)=$mes
   and extract('day' from fecha_resultado)=$dia";	
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
 }
 
    //Funcion utilizada para el tabulador para Servicio d eProcedencia  de referidos externos
   public function prxservicioref($idpr, $lugar, $dia, $mes, $anio){
         $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
   $sql = "select count (distinct(t0c.id)) as cuatro
         from sec_detallesolicitudestudios t00 
         join sec_solicitudestudios t01 on (t01.id=t00.idsolicitudestudio)
         join lab_resultado_metodologia t0c on (t00.id=t0c.id_detallesolicitudestudio)
         where estadodetalle in (6,7)
         and t00.idestablecimiento=$lugar
         and  id_dato_referencia is not null
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes|
         and extract('day' from fecha_resultado)=$dia
         and id_conf_examen_estab=$idpr";	
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
 }
 
    //Funcion utilizada para el tabulador para Servicio d eProcedencia 
 public function pruebatotallab($idpr, $mes, $anio, $lugar){
            $con = new ConexionBD;
      //usamos el metodo conectar para realizar la conexion
      if($con->conectar()==true){
	$sql = "select count(*) as total
         from lab_resultado_metodologia t01
         join sec_detallesolicitudestudios t02 on (t02.id=t01.id_detallesolicitudestudio) 
         where estadodetalle in (6,7)
         and extract('year' from fecha_resultado)=$anio
         and extract('month' from fecha_resultado)=$mes
         and id_conf_examen_estab= $idpr
         and idestablecimiento=$lugar;";	
    //   echo '<br>'.$sql.'<br/>';
	$result= pg_query($sql);
	if (!$result)
		return false;
	else
		return $result;
	}//fin de la funcion consultarTipoResultado
      }
	
         
   //Fin funcion Postgres
    
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
   
//         
//         
// 
//    function LeerCodigosResultados(){
//	$con = new ConexionBD;
//            if($con->conectar()==true) 
//	{ $query="select IdResultado from lab_codigosresultados";
//			$result = @mysql_query($query);
//			if (!$result)
//			return false;
//			else
//			return $result;	
//		}
//     }
//	  
//    function LeerProcedencias(){
//	$con = new ConexionBD;
//		if($con->conectar()==true) 
//		{ $query="SELECT IdProcedencia,NombreServicio 
//			  FROM mnt_servicio 
//			  WHERE IdTipoServicio='CON' ORDER BY IdProcedencia";
//			$result = @mysql_query($query);
//			if (!$result)
//				return false;
//			else
//				return $result;	
//		}
//	
//     }
//
//     function ExamenesxArea($IdArea,$lugar){
//        $con = new ConexionBD;
//	//usamos el metodo conectar para realizar la conexion
//	if($con->conectar()==true){
//        	$query ="";  
//         
//                 $result = @mysql_query($query);
//            if (!$result)
//                return false;
//            else
//                 return $result;
//         
//     }
//     
//     } 
//     
//     function NumeroDeProcedencias(){
//	//creamos el objeto $con a partir de la clase ConexionBD
//      $con = new ConexionBD;
//	//usamos el metodo conectar para realizar la conexion
//	if($con->conectar()==true){
//        	$query ="SELECT IdProcedencia 
//                	  FROM mnt_servicio 
//			  WHERE IdTipoServicio='CON' OR IdTipoServicio='REF' ORDER BY IdProcedencia";
//		$numreg = mysql_num_rows(mysql_query($query));
//		 if (!$numreg )
//		   return false;
//		 else
//		   return $numreg ;
//	   }
//	}
//        
// function NumeroDeCodigos(){
//   //creamos el objeto $con a partir de la clase ConexionBD
//   $con = new ConexionBD;
//   //usamos el metodo conectar para realizar la conexion
//   if($con->conectar()==true){
//     $query ="select IdResultado from lab_codigosresultados";
//	 $numreg = mysql_num_rows(mysql_query($query));
//         //echo $numreg;
//	 if (!$numreg )
//	   return false;
//	 else
//         //  echo $numreg;
//	   return $numreg ;
//   }
//  }   
//  
//  
//  function NumeroDeExamenes($IdArea,$lugar){
//     //creamos el objeto $con a partir de la clase ConexionBD
//   $con = new ConexionBD;
//   //usamos el metodo conectar para realizar la conexion
//   if($con->conectar()==true){
//     $query ="SELECT * 
//              FROM lab_examenes 
//              INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
//              WHERE IdArea='$IdArea' AND IdEstablecimiento=$lugar AND Condicion='H'";
//     $numreg = mysql_num_rows(mysql_query($query));
//         //echo $numreg;
//     if (!$numreg )
//	return false;
//     else
//         //  echo $numreg;
//        return $numreg ;
//   }
//  }
//  
//  // consulta las areas de la BD
// function consultaractivas($lugar){
//   //creamos el objeto $con a partir de la clase ConexionBD
//   $con = new ConexionBD;
//   //usamos el metodo conectar para realizar la conexion
//   if($con->conectar()==true){
//     $query = "SELECT lab_areas.IdArea,NombreArea 
//	       FROM lab_areas 
//	       INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea= lab_areasxestablecimiento.IdArea
//               WHERE lab_areasxestablecimiento.Condicion='H' AND lab_areas.Administrativa='N' 
//	       AND lab_areasxestablecimiento.IdEstablecimiento=$lugar 
//	       ORDER BY NombreArea";
//	 $result = @mysql_query($query);
//	 if (!$result)
//	   return false;
//	 else
//	   return $result;
//   }
//  } 
//  	
//	
//
//
//	 //FUNCION PARA MOSTRAR DATOS DE BUSQUEDA
// function BuscarExamenesporCodigo($query_search)
// {
//  //creamos el objeto $con a partir de la clase ConexionBD
//	  $con = new ConexionBD;
//	 //usamos el metodo conectar para realizar la conexion
//	 if($con->conectar()==true){
//	    $query = $query_search;
//	    $result = @mysql_query($query);
//	    if (!$result)
//		   return false;
//	    else
//		   return $result;
//	 }  
//
// }
//	 
// function ContarDatos($query_search){
//       $con = new ConexionBD;
//	 //usamos el metodo conectar para realizar la conexion
//	 if($con->conectar()==true){
//	    $query = $query_search;
//	     $numreg = mysql_num_rows(mysql_query($query));
//         //echo $numreg;
//             if (!$numreg )
//                return 0;
//            else
//              //  echo $numreg;
//                 return $numreg ;
//            }  
//     
// }
//	 
//function consultarareas($lugar){
//    $con = new ConexionBD;
//    if($con->conectar()==true){
//     $query="SELECT lab_areas.IdArea,NombreArea FROM lab_areas 
//             INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea
//	     WHERE Condicion='H' AND IdEstablecimiento=$lugar 
//	     ORDER BY  NombreArea ASC";
//	 $dt = mysql_query($query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//	
//function consultaSubservicios($procedencia){
//$con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="SELECT IdSubServicio,NombreSubservicio,IdServicio FROM mnt_subservicio 
//	  WHERE IdServicio='$procedencia'";
//	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//
//function NombreServicio($procedencia){
//$con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="select NombreServicio from mnt_servicio where IdServicio='$procedencia'";
//	  $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//function subserviciosxservicio($servicio,$ffechaini,$ffechafin,$cadena,$lugar){
//$con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="SELECT $cadena NombreSubServicio as origen, 
//sum(if(sec_detallesolicitudestudios.IdExamen<>'',1,0)) as total ,mnt_servicio.IdServicio, mnt_servicio.NombreServicio 
//from sec_detallesolicitudestudios 
//INNER JOIN lab_resultados ON sec_detallesolicitudestudios.IdDetalleSolicitud=lab_resultados.IdDetalleSolicitud 
//INNER JOIN lab_examenes ON sec_detallesolicitudestudios.IdExamen=lab_examenes.IdExamen 
//INNER JOIN lab_areas ON lab_examenes.IdArea=lab_areas.IdArea 
//INNER JOIN sec_solicitudestudios ON sec_detallesolicitudestudios.IdSolicitudEstudio=sec_solicitudestudios.IdSolicitudEstudio 
//INNER JOIN sec_historial_clinico ON sec_solicitudestudios.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico 
//INNER JOIN mnt_subservicio ON sec_historial_clinico.IdSubServicio=mnt_subservicio.IdSubServicio 
//INNER JOIN mnt_servicio ON mnt_subservicio.IdServicio= mnt_servicio.IdServicio
//INNER JOIN lab_areasxestablecimiento ON lab_areas.IdArea=lab_areasxestablecimiento.IdArea  
//WHERE sec_detallesolicitudestudios.EstadoDetalle='RC' AND lab_areasxestablecimiento.Condicion='H' 
//AND lab_areasxestablecimiento.IdEstablecimiento=$lugar 
//AND (lab_resultados.FechaHoraReg >='$ffechaini' AND lab_resultados.FechaHoraReg <='$ffechafin') 
//AND mnt_subservicio.IdServicio='$servicio' 
//GROUP BY sec_historial_clinico.IdSubServicio ORDER BY sec_historial_clinico.IdSubServicio";
// $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//
//}
//
//function DatosGenerales($lugar,$IdArea){
//        $con = new ConexionBD;
//	if($con->conectar()==true){
//	  $query="SELECT Nombre,Nombrearea 
//                FROM `lab_areasxestablecimiento`
//                INNER JOIN lab_areas ON lab_areasxestablecimiento.IdArea=lab_areas.IdArea
//                INNER JOIN mnt_establecimiento ON lab_areasxestablecimiento.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento
//                WHERE lab_areasxestablecimiento.IdArea='$IdArea' AND lab_areasxestablecimiento.IdEstablecimiento=$lugar";
//          $dt = mysql_query( $query) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
//}
//
//
//function CodigosEstardarxarea($IdArea,$lugar)		
// {	$con = new ConexionBD;
//	if($con->conectar()==true){
//            $sqlText = "SELECT IdEstandar FROM lab_examenes 
//	    INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
//	    WHERE IdArea='$IdArea' AND lab_examenesxestablecimiento.Condicion = 'H' 
//            AND IdEstablecimiento=$lugar ORDER BY IdEstandar ASC";
//            $dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
//	}
//	return $dt;
// }
// 
 
 
}//CLASE
?>
