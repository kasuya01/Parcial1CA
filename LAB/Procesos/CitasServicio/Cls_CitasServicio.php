<?php

include_once("../../../Conexion/ConexionBD.php");

class clsCitasServicio
{
 //constructor	
 function clsCitasServicio(){
 }


//Funcion para verificar si tiene dias festivos o eventos de un medico en esa fecha festiva

function dias_festivos($fech_act,$emp){
//creamos el objeto $con a partir de la clase ConexionBD
   $con = new ConexionBD;
   //usamos el metodo conectar para realizar la Conexion
   if($con->conectar()==true){	
	// $query_festivos="SELECT IdEvento FROM cit_eventos WHERE (FechaIni <= '$fech_act' AND FechaFin >= '$fech_act') AND (Idempleado='Todos' OR Idempleado='$emp')";
	 $query_festivos="SELECT id  
                        FROM cit_evento
                        WHERE (fechaini <= '$fech_act' AND fechafin >= '$fech_act') 
                        AND (idempleado is null OR idempleado=$emp);";
	//$queryfestivos=mysql_query($query_festivos) or die('La consulta fall&oacute;:' . mysql_error());
        $queryfestivos= pg_query($query_festivos);
	$search = pg_num_rows($queryfestivos);
//echo $query_festivos;
	if($search > 0 and $search <>""){$esEvt=-1;}
		else{$esEvt = 0;}
	return $esEvt;
  }
}

//Funcion para verificar los dias laborales utilizados para generar un vector y usarlo en el proceso de citas para el servicio de apoyo
function diaslaborales($dia){
	//$vector = array(2,3,4,5,6);--en mysql
        $vector = array(1,2,3,4,5); //en postgres
	$indice_encontrado = array_search($dia,$vector);
	for ($i=0;$i<5;$i++){
		if($vector[$i]==$dia){
			$indice_encontrado=TRUE;
			break;	
		}		
	}
	
	if ($indice_encontrado==TRUE){
		$fecha_gen=0;
	}else{$fecha_gen=-1;}
        //echo 'fecha_gen: '.$fecha_gen;
	return $fecha_gen;	
}

function parse_day($fecha_rec){
  $query_day="SELECT EXTRACT(DOW FROM TIMESTAMP '$fecha_rec') as dow";
  $queryDay =pg_query($query_day);
  
  $rows = pg_fetch_array($queryDay);
	$dia  =	$rows['dow'];
  //echo $dia;
  return $dia;
}
//Fn pg
function RESTAR($fecha,$tiempo_max){
    $con = new ConexionBD;
    if ($con->conectar()==TRUE){
    $query_resta="SELECT date( date('$fecha') - INTERVAL '$tiempo_max days' ) as fecha_int, current_date as fecha_actual";
//  $query_resta="SELECT DATE_FORMAT(adddate('$fecha',interval -$tiempo_max day),'%Y-%m-%d') as Fecha";
    $queryDay =pg_query($query_resta);
    //
    //echo '<br>RESTAR: '.$query_resta;
    if (!$queryDay)
        return false;
    else 
        return $queryDay;
    }
}


function subdays($fech_act){
	//$sql="SELECT DATE_FORMAT(adddate('$fech_act',interval -1 day),'%Y/%m/%d') as Fecha";
	$sql="SELECT date(date('$fech_act') - INTERVAL '1 day') as fecha";
       // $queryDay =mysql_query($sql) or die('La consulta fall&oacute;:' . mysql_error());
        $queryDay =pg_query($sql);
	$var=pg_fetch_array($queryDay);
        if (!$queryDay)
            return false;
        else
            return $var[0];
}

//Fn pg
function Duplos($IdSolicitudEstudio,$IdSubServicio){
	/*$query="SELECT ca.Fecha,se.Estado 
			FROM cit_citasxserviciodeapoyo ca 
			inner join sec_solicitudestudios se 
			on se.IdSolicitudEstudio=ca.IdSolicitudEstudio
			inner join sec_historial_clinico hc
			on hc.IdHistorialClinico=se.IdHistorialClinico
			WHERE hc.IdSubServicio='$IdSubServicio' and (se.Estado='D' or se.Estado='C' or se.Estado='P' or se.Estado='R') 
                        and ca.IdSolicitudEstudio=$IdSolicitudEstudio
                        and fecha>=curdate()";*/
        $query="select ccs.fecha, sse.estado
from cit_citas_serviciodeapoyo ccs
join sec_solicitudestudios sse		on (sse.id=ccs.id_solicitudestudios)
join sec_historial_clinico shc		on (shc.id=sse.id_historial_clinico)
where shc.idsubservicio=$IdSubServicio
and sse.estado in (1,2,3,4)
and ccs.id_solicitudestudios=$IdSolicitudEstudio
and fecha>=current_date";
        //--Estados 1:Digitada 2:Recibida 3:En Proceso 4:Completa
	$resp=pg_query($query);
        //echo $query;
        if (!$resp)
            return false;
        else
            return($resp);
}

//Fn PG
function ValidarExpediente($nec, $lugar){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search= 	"SELECT e.numero, e.id, concat_ws (' ',d.primer_apellido,d.segundo_apellido, d.apellido_casada,',', d.primer_nombre, d.segundo_nombre, d.tercer_nombre) as nombre
                                FROM mnt_paciente d
                                join  mnt_expediente e on (d.id= e.id_paciente)
                                where e.numero='$nec'
                                and id_establecimiento=$lugar";
	$query = pg_query($query_Search);
        //echo $query;
        if (!$query)
            return false;
        else
            return $query;
	
	}
        
}
//FN PG
function MostrarDetalleSolicitudes($nec,$idestablecimiento){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search = "select mex.numero, mex.id as idexpediente, sse.id as idsolicitudestudio, 
                    sse.fecha_solicitud, nombre, sse.estado, sse.estado, sse.idtiposolicitud, 
                    sse.id_establecimiento, cat.codigo_busqueda, cat.id as idatencion
                        from sec_solicitudestudios sse
                        join mnt_expediente mex 		on (mex.id=sse.id_expediente)
                        join ctl_atencion cat			on (cat.id=sse.id_atencion)
                        where sse.id_expediente=$nec
                        and sse.estado=1 --D:digitado=1
                        and sse.idtiposolicitud=2 --R:Normal=2
                        and sse.id_establecimiento=$idestablecimiento";
		//$valret = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
            $valret=  pg_query($query_Search);
            //echo $query_Search;
            if (!$valret)
                return false;
            else
                return $valret;	
	}
	
}
//Fn PG
function ListExamenesTiempoPrev($idsolicitudestudio,$IdEstablecimiento){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search = "select  lce.id as idexamen, nombre_examen as nombreexam, rangotiempoprev, sds.id as iddetalle
from sec_detallesolicitudestudios sds
join lab_conf_examen_estab lce		on (lce.id = sds.id_conf_examen_estab)
join cit_programacion_exams cpe 	on (cpe.id_examen_establecimiento = sds.id_conf_examen_estab)
join sec_solicitudestudios sse		on (sse.id = sds.idsolicitudestudio)
join sec_historial_clinico shc		on (shc.id = sse.id_historial_clinico)
where sds.idsolicitudestudio= $idsolicitudestudio"
                        . " and shc.idestablecimiento=$IdEstablecimiento";
	$valret=  pg_query($query_Search);
//echo $query_Search;
        if (!$valret){
            return false;
        }
        else 
            return $valret;
        }
}
//Fn pg
//Buscar id del examen que es QUI045
function buscaridexamen($codexamen){
    $con = new ConexionBD;
	if($con->conectar()==true){
            $query="select id 
                    from lab_conf_examen_estab
                    where codigo_examen='$codexamen'";
           $result= pg_query($query);    
           $row=  pg_fetch_array($result);
           $id=$row['id'];
        if(!$result)
            return false;
        else
            return $id;
        }
}

function MaxTiempoPrevdeExamenes($idsolicitudestudio,$IdEstablecimiento){
	$con = new ConexionBD;
	if($con->conectar()==true){
            $query_Search = "select max(cpe.rangotiempoprev)
                            from sec_detallesolicitudestudios sds
                            join cit_programacion_exams cpe on (sds.id_conf_examen_estab = cpe.id_examen_establecimiento)
                            join sec_solicitudestudios sse	on (sse.id=sds.idsolicitudestudio)
                            join sec_historial_clinico shc on (shc.id=sse.id_historial_clinico)
                            where sds.idsolicitudestudio=$idsolicitudestudio
                            and shc.idestablecimiento=$IdEstablecimiento";
            $valret= pg_query($query_Search);
            //echo 'Maxtiempoprev: '.$query_Search.' /finMaxtiempoprev';
           // $valret = mysql_query($query_Search) or die('La consulta 3 fall&oacute;: ' . mysql_error());
            $var=pg_fetch_array($valret);
        if (!$valret)
            return false;
        else
            return $var[0];
	}
	
}

function ObtenerFechaCitaMedica($nec){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_FechaCitaMedica="select fecha 
                                        from cit_citas_dia 
                                        where id_expediente=$nec
                                        and date(fechahorareg) >=current_date
                                        and id_estado in (1,6)";
               // echo 'Obtener fecha: '.$query_FechaCitaMedica;
		//$valret =  mysql_query($query_FechaCitaMedica) or die('La consulta fall&oacute;: ' . mysql_error());
                $valret = pg_query($query_FechaCitaMedica);
                if (!$valret)
                    return FALSE;
                else
                    return $valret;
	}
}

function InsertarCitaServicio($actual,$idsolicitudestudio,$FechaReg,$IdUsuarioReg){
	$con = new ConexionBD;
	if($con->conectar()==true){
			
		//$sql_Insert =	"INSERT INTO cit_citasxserviciodeapoyo 	(IdCitaServApoyo,Fecha,IdSolicitudEstudio,IdUsuarioReg,FechaHoraReg,IdDetalleSolicitud) 
		//				VALUES ('0','$actual','".$idsolicitudestudio."',$IdUsuarioReg,'".$FechaReg."',$idetallesolicitud)";
		$sql_Insert =	"insert into cit_citas_serviciodeapoyo (fecha, id_solicitudestudios, idusuarioreg, fechahorareg)
                                 values (' $actual', $idsolicitudestudio, $IdUsuarioReg, '$FechaReg');";
		//$valret = mysql_query($sql_Insert) or die('La consulta fall&oacute;: ' . mysql_error());
                $valret = pg_query($sql_Insert);
              // echo ' ..sqlinsert: '.$sql_Insert;
                if (!$valret)
                    return FALSE;
                else 
                    return $valret;                
	}
	//return $valret;
}
//Fn_pg
function ValidarFecha($FechaActual){
	$querySelect="select current_date as FechaActual";
	$resp=pg_fetch_array(pg_query($querySelect));
	if($resp[0]==$FechaActual){
		$NewDate=pg_fetch_array(pg_query(" SELECT date(date('$FechaActual') + INTERVAL '1 day')"));
		return($NewDate[0]);
	}else{
		return ($FechaActual);
	}
		
}

function InsertarCitaServicioRx($actual,$idsolicitudestudio,$fechareg,$array_idetalle,$idtamano){
	$con = new ConexionBD;
	if($con->conectar()==true){
		for($i=1;$i<=$idtamano;$i++){
			$query_Search="insert into cit_citasxserviciodeapoyo 	(IdCitaServApoyo,Fecha,IdSolicitudEstudio,IdUsuarioReg,FechaHoraReg,IdDetalleSolicitud) values ('0','$actual','".$idsolicitudestudio."',1,'$fechareg',$array_idetalle[$i])";
			$dt = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
		}			
	}return $dt;
}

function ContarFechas($actual){
	$con = new ConexionBD;
	if($con->conectar()==true){
		//$query_Existencia="Select count(Fecha) from cit_citasxserviciodeapoyo where Fecha='$actual'";
		$query_Existencia="select count(fecha) 
                            from cit_citas_serviciodeapoyo 
                            where fecha='$actual'";
		//$dt_Sql =  mysql_query($query_Existencia) or die('La consulta fall&oacute;: ' . mysql_error());
		$dt_Sql= pg_query($query_Existencia);
               $row = pg_fetch_array($dt_Sql);
			$valret=$row[0];
//echo 'valret'.$valret.' - dt_sql: '.$dt_Sql.' /--';
        if (!$dt_Sql)
            return false;
        else {
            return $valret;;
        }
	}
	
}

function ContarCreatinina($actual, $id_qui045){
	$con = new ConexionBD;
	$solicitudes = array();
	$detalles = array();
	$i=1;
	
	if($con->conectar()==true){
		$query_Search = "select idsolicitudestudios
                            from cit_citas_serviciodeapoyo
                            where fecha='$actual'";
		$valret = pg_query($query_Search);
		$totalRegs= pg_num_rows($valret);
		
		while ($row = pg_fetch_array($valret)){
			$solicitudes[$i]=$row[0];
			$i++;
		}
		
		for ($i=1;$i<=$totalRegs;$i++){
			//$SqlTxt = "SELECT IdDetalleSolicitud FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio='$solicitudes[$i]' AND IdExamen='QUI045' ";
			$SqlTxt = "select id 
                                    from sec_detallesolicitudestudios 
                                    where  id_conf_examen_estab=$id_qui045";
			$dt_Sql = pg_query($SqlTxt);
		
			while ($rows = pg_fetch_array($dt_Sql)){
				$detalles[$i]=$rows[0];
				
			}
		}
		$tamano = sizeof($detalles);
                //echo '--tamano:'.$tamano.'--';
        if (!$dt_Sql)
            return false;
        else
            return $tamano;
	}

	
}

function ListRx($idsolicitudestudio){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search = "select le.IdExamen, le.NombreExamen,ds.IdDetalleSolicitud from rx_examenes le Inner join sec_detallesolicitudestudios ds on ds.IdExamen=le.IdExamen where ds.IdSolicitudEstudio=".$idsolicitudestudio;
		$valret = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
	}
	return $valret;
}

function ContarFechasRx($actual,$vector,$tamano){
	$con = new ConexionBD;
	if($con->conectar()==true){
		for($i=1;$i<=$tamano;$i++){
			$query_Search="SELECT COUNT(ca.IdCitaServApoyo) FROM cit_citasxserviciodeapoyo ca inner join sec_detallesolicitudestudios sd on sd.IdSolicitudEstudio=ca.IdSolicitudEstudio WHERE sd.IdExamen='".$vector[$i]."' and ca.Fecha='$actual'";
			$dt = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
			while($rows=mysql_fetch_array($dt)){
				$encontrados="$rows[0]";
			}
		}			
	}return $encontrados;
}

function buscar($idsolicitudestudio,$idservicio){
	$con = new ConexionBD;
	if($con->conectar()==true){
		//$query_Search="SELECT COUNT(ca.IdCitaServApoyo) FROM cit_citasxserviciodeapoyo ca inner join sec_detallesolicitudestudios sd on sd.IdSolicitudEstudio=ca.IdSolicitudEstudio inner join sec_solicitudestudios se on se.IdSolicitudEstudio=sd.IdSolicitudEstudio WHERE se.IdServicio='".$idservicio."' and ca.IdSolicitudEstudio=".$idsolicitudestudio;
		$query_Search="select count(ccs.id)  
                            from cit_citas_serviciodeapoyo ccs
                            join sec_solicitudestudios ssd on (ssd.id = ccs.id_solicitudestudios)
                            join sec_detallesolicitudestudios sds on (ssd.id=sds.idsolicitudestudio)
                            where ssd.id_atencion=$idservicio
                            and ccs.id_solicitudestudios=$idsolicitudestudio;";
		//$dt = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
		$dt=pg_query($query_Search);
               // echo $query_Search;
                $rows=pg_fetch_array($dt);
                $encontrados= $rows[0];
                if (!$query_Search)
                    return false;
                else
                    return $encontrados;
	}			
}


function ObtenerSubServicio($IdSolicitud,$Fecha, $Idexpediente){

	if($Fecha==''){
		$comp="and fecha=current_date";
	}else{
		$comp="and fecha='$Fecha'";
	}
		$query_Search="select distinct (id_aten_area_mod_estab) 
                                from sec_solicitudestudios sse
                                join sec_historial_clinico shc 	on (shc.id = sse.id_historial_clinico) 
                                join cit_citas_dia ccd		on (ccd.id_empleado = shc.id_empleado)
                                where sse.id=$IdSolicitud
                                and ccd.id_expediente=$Idexpediente

			$comp";
                
               // echo $query_Search.'<br>';
                $dt = pg_query($query_Search);
                
		//$dt = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
	$resp=pg_fetch_array($dt);
	return($resp[0]);
}//ObtenerSubServicio

function FechaServer(){
        $SQL ="select to_char(current_timestamp, 'YYYY-MM-DD HH24:MI:SS') as fecha";
        $db = pg_query($SQL);
        $vareturn = pg_fetch_array($db);
   
    return $vareturn['fecha'];
}
 
}//Clase


?>
