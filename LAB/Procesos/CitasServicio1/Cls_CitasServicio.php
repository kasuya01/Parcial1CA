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
	 $query_festivos="SELECT IdEvento FROM cit_eventos WHERE (FechaIni <= '$fech_act' AND FechaFin >= '$fech_act') AND (Idempleado='Todos' OR Idempleado='$emp')";
	$queryfestivos=mysql_query($query_festivos) or die('La consulta fall&oacute;:' . mysql_error());
	$search =mysql_numrows($queryfestivos);

	if($search > 0 and $search <>""){$esEvt=-1;}
		else{$esEvt = 0;}  		
  
	return $esEvt;
  }
}

//Funcion para verificar los dias laborales utilizados para generar un vector y usarlo en el proceso de citas para el servicio de apoyo
function diaslaborales($dia){
	$vector = array(2,3,4,5,6);
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
	
	return $fecha_gen;	
}

function parse_day($fecha_rec){
  $query_day="SELECT dayofweek('$fecha_rec') ";
  $queryDay =mysql_query($query_day) or die('La consulta fall&oacute;:' . mysql_error());
  while($rows = mysql_fetch_array($queryDay)){
	$dia  =	"$rows[0]";
  }
  return $dia;
}

function RESTAR($fecha,$tiempo_max){
  $query_resta="SELECT DATE_FORMAT(adddate('$fecha',interval -$tiempo_max day),'%Y-%m-%d') as Fecha";
  $queryDay =mysql_query($query_resta) or die('La consulta 4fall&oacute;:' . mysql_error());
  while($rows = mysql_fetch_array($queryDay)){
	$diaServ  =	$rows[0];
  }
  return $diaServ;
}

function subdays($fech_act){
	$sql="SELECT DATE_FORMAT(adddate('$fech_act',interval -1 day),'%Y/%m/%d') as Fecha";
        $queryDay =mysql_query($sql) or die('La consulta fall&oacute;:' . mysql_error());
	$var=mysql_fetch_array(mysql_query($sql));
	return $var[0];
}


function Duplos($IdSolicitudEstudio,$IdSubServicio){
	$query="SELECT ca.Fecha,se.Estado 
			FROM cit_citasxserviciodeapoyo ca 
			inner join sec_solicitudestudios se 
			on se.IdSolicitudEstudio=ca.IdSolicitudEstudio
			inner join sec_historial_clinico hc
			on hc.IdHistorialClinico=se.IdHistorialClinico
			WHERE hc.IdSubServicio='$IdSubServicio' and (se.Estado='D' or se.Estado='C' or se.Estado='P' or se.Estado='R') 
                        and ca.IdSolicitudEstudio=$IdSolicitudEstudio
                        and fecha>=curdate()";
	//echo $query;
	$resp=mysql_query($query);
	return($resp);
}


function ValidarExpediente($nec){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search= 	"SELECT e.idnumeroexp, if(d.SegundoApellido is null and d.SegundoNombre is null, CONCAT(d.PrimerApellido,', ',d.PrimerNombre),
if(d.SegundoApellido is not null and d.SegundoNombre is not null,CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre,' ',d.SegundoNombre),
if(d.SegundoNombre is null, CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre),CONCAT(d.PrimerApellido,', ',d.PrimerNombre,' ',d.SegundoNombre)))) as Nombre
FROM mnt_datospaciente d INNER JOIN mnt_expediente e on e.idpaciente=d.idpaciente WHERE e.idnumeroexp ='".$nec."'";
	$query = mysql_query($query_Search) or die('La consulta fallo&oacute;: ' . mysql_error());
	
	}
	return $query;
}

function MostrarDetalleSolicitudes($nec,$idestablecimiento){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search = "select se.IdNumeroExp, se.IdSolicitudEstudio,se.FechaSolicitud,
(Select NombreServicio from mnt_servicio where IdServicio=se.IdServicio) as Servicio,se.IdServicio 
from sec_solicitudestudios se inner join sec_detallesolicitudestudios ds on se.IdSolicitudEstudio=ds.IdSolicitudEstudio 
where IdNumeroExp='".$nec."' and se.estado='D' and se.idtiposolicitud='R' and se.idestablecimiento=$idestablecimiento group by se.IdSolicitudEstudio";

		$valret = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
		
	}
	return $valret;
}

function ListExamenesTiempoPrev($idsolicitudestudio,$IdEstablecimiento){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search = "select ds.IdExamen,(select NombreExamen from lab_examenes le where le.IdExamen=ds.IdExamen) as NombreExam, pe.RangoTiempoPrev 
                                from sec_detallesolicitudestudios ds 
                                inner join cit_programacionxexams pe on pe.idexam=ds.idexamen 
                                inner join sec_solicitudestudios so on ds.idsolicitudestudio=so.idsolicitudestudio
                                inner join sec_historial_clinico hi on so.idhistorialclinico=hi.idhistorialclinico
                                where ds.IdSolicitudEstudio=".$idsolicitudestudio." and hi.idestablecimiento=$IdEstablecimiento";
			//echo $query_Search;
	$valret = mysql_query($query_Search) or die('La consulta 2 fall&oacute;: ' . mysql_error());
	}
	return $valret;
}

function MaxTiempoPrevdeExamenes($idsolicitudestudio,$IdEstablecimiento){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search = "select max( pe.RangoTiempoPrev )
                                from sec_detallesolicitudestudios ds 
                                inner join cit_programacionxexams pe on pe.idexam=ds.idexamen 
                                inner join sec_solicitudestudios so on ds.idsolicitudestudio=so.idsolicitudestudio
                                inner join sec_historial_clinico hi on so.idhistorialclinico=hi.idhistorialclinico
                                where ds.IdSolicitudEstudio=".$idsolicitudestudio." and hi.idestablecimiento=$IdEstablecimiento";
			
	$valret = mysql_query($query_Search) or die('La consulta 3 fall&oacute;: ' . mysql_error());
        $var=mysql_fetch_array($valret);
	return $var[0];
	}
	
}

function ObtenerFechaCitaMedica($nec){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_FechaCitaMedica="Select Fecha from cit_citasxdia where IdNumeroExp='".$nec."' 
                                        and date(FechaHoraReg) >= CURDATE() and IdEstado not in (5,7)";
                //echo $query_FechaCitaMedica;
		$valret =  mysql_query($query_FechaCitaMedica) or die('La consulta fall&oacute;: ' . mysql_error());
	}
	return $valret;
}

function InsertarCitaServicio($actual,$idsolicitudestudio,$FechaReg,$idetallesolicitud,$IdUsuarioReg){
	$con = new ConexionBD;
	if($con->conectar()==true){
			
		$sql_Insert =	"INSERT INTO cit_citasxserviciodeapoyo 
						(IdCitaServApoyo,Fecha,IdSolicitudEstudio,IdUsuarioReg,FechaHoraReg,IdDetalleSolicitud) 
						VALUES ('0','$actual','".$idsolicitudestudio."',$IdUsuarioReg,'".$FechaReg."',$idetallesolicitud)";
		$valret = mysql_query($sql_Insert) or die('La consulta fall&oacute;: ' . mysql_error());

	}
	return $valret;
}

function ValidarFecha($FechaActual){
	$querySelect="select curdate() as FechaActual";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	if($resp[0]==$FechaActual){
		$NewDate=mysql_fetch_array(mysql_query("select adddate('$FechaActual',interval 1 day) as NuevaFecha"));
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
		$query_Existencia="Select count(Fecha) from cit_citasxserviciodeapoyo where Fecha='$actual'";
		$dt_Sql =  mysql_query($query_Existencia) or die('La consulta fall&oacute;: ' . mysql_error());
		while ($row = mysql_fetch_array($dt_Sql)){
			$valret=$row[0];
		}
	}
	return $valret;
}

function ContarCreatinina($actual){
	$con = new ConexionBD;
	$solicitudes = array();
	$detalles = array();
	$i=1;
	
	if($con->conectar()==true){
		$query_Search = "SELECT IdSolicitudEstudio FROM cit_citasxserviciodeapoyo WHERE Fecha='$actual'";
		$valret = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
		$totalRegs= mysql_numrows($valret);
		
		while ($row = mysql_fetch_array($valret)){
			$solicitudes[$i]=$row[0];
			$i++;
		}
		
		for ($i=1;$i<=$totalRegs;$i++){
			$SqlTxt = "SELECT IdDetalleSolicitud FROM sec_detallesolicitudestudios WHERE IdSolicitudEstudio='$solicitudes[$i]' AND IdExamen='QUI045' ";
			$dt_Sql = mysql_query($SqlTxt) or die('La consulta fall&oacute;: ' . mysql_error());
		
			while ($rows = mysql_fetch_array($dt_Sql)){
				$detalles[$i]=$rows[0];
				
			}
		}
		$tamano = sizeof($detalles);
	}

	return $tamano;
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
		$query_Search="SELECT COUNT(ca.IdCitaServApoyo) FROM cit_citasxserviciodeapoyo ca inner join sec_detallesolicitudestudios sd on sd.IdSolicitudEstudio=ca.IdSolicitudEstudio inner join sec_solicitudestudios se on se.IdSolicitudEstudio=sd.IdSolicitudEstudio WHERE se.IdServicio='".$idservicio."' and ca.IdSolicitudEstudio=".$idsolicitudestudio;
		$dt = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
		while($rows=mysql_fetch_array($dt)){
				$encontrados="$rows[0]";
		}
	}			
	return $encontrados;
}


function ObtenerSubServicio($IdSolicitud,$Fecha){

	if($Fecha==''){
		$comp="and Fecha=current_date";
	}else{
		$comp="and Fecha='$Fecha'";
	}
		$query_Search="select distinct cit_citasxdia.IdSubServicio
			from sec_solicitudestudios 
			inner join sec_historial_clinico
			on sec_historial_clinico.IdHistorialClinico=sec_solicitudestudios.IdHistorialClinico
			inner join cit_citasxdia
			on cit_citasxdia.Idempleado=sec_historial_clinico.IdEmpleado
			where IdSolicitudEstudio='$IdSolicitud'
			$comp";
                
                //echo $query_Search;
		$dt = mysql_query($query_Search) or die('La consulta fall&oacute;: ' . mysql_error());
	$resp=mysql_fetch_array($dt);
	return($resp[0]);
}//ObtenerSubServicio

function FechaServer(){
        $SQL ="SELECT now()";
        $db = mysql_query($SQL) or die('La consulta Fall&oacute;:' . mysql_error());
        $vareturn = mysql_fetch_array($db);
   
    return $vareturn[0];
}
 
}//Clase


?>
