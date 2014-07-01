<?php
class AgendaMedica{
	// Método Constructor de la clase 
	function AgendaMedica(){	
	}	
	
/*****************************************************************************************/
// Funcion para Contar la Cantidad de Citas x Medico
/*****************************************************************************************/
function CantidadCitas($Conectar,$Fecha,$Procedimiento,$establecimiento,$Bandera,$Horario) { 
	if($Conectar==true){
		if($Bandera==1){
		$SQL = "SELECT SUM(if(IdEstado=6,1,0)) AS CitasACupo,SUM(if(IdEstado=1,1,0)) AS CitasTechoMax,count(IdCiq) as TotalCitas
			FROM cit_citasprocedimientos
 			INNER JOIN mnt_expediente
			ON mnt_expediente.IdNumeroExp=cit_citasprocedimientos.IdNumeroExp
			WHERE Fecha='$Fecha' AND IdCiq='$Procedimiento'
			AND cit_citasprocedimientos.IdEstablecimiento=$establecimiento";
		$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
		$Resultado = mysql_fetch_array($Ejecutar);
		}else{
		$SQL = "SELECT SUM(if(IdEstado=6,1,0)) AS CitasACupo,SUM(if(IdEstado=1,1,0)) AS CitasTechoMax,count(IdCiq) as TotalCitas
                        FROM cit_citasprocedimientos
                        INNER JOIN mnt_expediente
                        ON mnt_expediente.IdNumeroExp=cit_citasprocedimientos.IdNumeroExp
                        WHERE Fecha='$Fecha' AND IdCiq='$Procedimiento' AND IdRngHr='$Horario'
                        AND cit_citasprocedimientos.IdEstablecimiento=$establecimiento";
                $Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
                $Resultado = mysql_fetch_array($Ejecutar);
		}
		return $Resultado;
	} // Fin if Conectar		
		
} // Fin Cantidad de Citas
	
function ComprobarDistribucion($Procedimiento,$actual,$IdEstablecimiento){
	$SQL="SELECT COUNT(*) FROM mnt_procedimientosxestablecimiento 
		WHERE IdCiq='$Procedimiento' 
		AND Yrs=YEAR('$actual')
		AND Dia=DAYOFWEEK('$actual') AND
		IdEstablecimiento=$IdEstablecimiento";
	$query=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
  	$total=mysql_fetch_array($query);
	return $total[0];
}

function NombreCIQ($Procedimiento){
	$SQL="SELECT UPPER(Procedimiento) AS Procedimiento FROM mnt_ciq WHERE IdCiq='$Procedimiento'";
	$query=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
  	$total=mysql_fetch_array($query);
	return $total[0];
}
/*****************************************************************************************/
// Funcion para Desplegar los pacientes citados para X dias, según tipo de Citas
/*****************************************************************************************/
	function DetalleCitas($Procedimiento,$Fecha,$Estado,$IdSubServicio,$Horario) { 
	$SQL = "SELECT	cit_citasprocedimientos.IdNumeroExp,
		CONCAT_WS(' ', PrimerApellido,SegundoApellido,PrimerNombre,SegundoNombre, TercerNombre) as Nombre,
		CASE IdEstado 
			WHEN 1 THEN 'Citado'
			WHEN 2 THEN 'Confirmada'
			WHEN 5 THEN 'Recibida'
			WHEN 6 THEN 'Agregado'
			WHEN 7 THEN 'Recibida' 
		END as Control
		FROM cit_citasprocedimientos
		INNER JOIN mnt_expediente ON mnt_expediente.IdNumeroExp=cit_citasprocedimientos.IdNumeroExp
		INNER JOIN mnt_datospaciente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
	 	WHERE Fecha='$Fecha' 
		AND IdCiq='$Procedimiento' 
		AND IdRngHr=$Horario	
		AND IdEstado=$Estado
		ORDER BY Control Desc";
		
			$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());

			echo "<table border='0' width='100%'>
				<tr>
				<td class='encabezadoTabla'>No</td>
				<td class='encabezadoTabla'>No Expediente</td>
				<td class='encabezadoTabla'>Nombre Paciente</td>
				<td class='encabezadoTabla'>Estado Cita</td>
				</tr>	";
			$i=1;
			while($Resultado= mysql_fetch_array($Ejecutar)) {
					echo "<tr>
						<td class='contenidoId'>".$i."</td>
						<td class='contenidoId'>".$Resultado["IdNumeroExp"]."</td>
						<td class='contenidoTabla'>".$Resultado["Nombre"]."</td>
						<td class='contenidoTabla'>".$Resultado["Control"]."</td>
						</tr>	";
					$i++;		
				
		  	} 
			$i--;
			if($i==0)
				echo "<tr>
					<td  colspan='4' class='contenidoId' align='left'> NO HAY PACIENTES ......!</td>			
	 				</tr>
					<tr><td  colspan='4' class='encabezadoTabla' align='left'>&nbsp;</td></tr>	";
			else
					echo "<tr>
					<td  colspan='4' class='encabezadoTabla' align='left'> Total Pacientes: ".$i."</td>			
					 </tr>	";
			
			echo "</table>";			
		//} // Fin if Conectar		
		
	} // Fin Detalle Citas

/*****************************************************************************************/
// Funcion para Formato Fecha dd/mm/yyyy
/*****************************************************************************************/

function FormatoFecha($fecha) {
  return implode("/", array_reverse( preg_split("-\D-", $fecha) ) );
}

function CambioFormatoFecha($fecha){
	$sql="select DATE_FORMAT('$fecha','%Y-%m-%d') as Fecha";
	$var=mysql_fetch_array(mysql_query($sql));
	return $var[0];
}

function DiaActualEstaEnEvento($Conectar,$Fecha,$Procedimiento){
$NuevaFecha=AgendaMedica::CambioFormatoFecha($Fecha);

	if ($Conectar==true){
		
		$SQL = "SELECT count(*) as TotalEventos
				FROM cit_eventos
				WHERE '$Fecha' between FechaIni and FechaFin
				AND (Idempleado='$Procedimiento' OR Idempleado='Todos') ";
		$Ejecutar = mysql_query($SQL) or die('La consulta fall&oacute;: ' . mysql_error());
		$Resultado = mysql_fetch_array($Ejecutar);
		
		$SQLTxt = " SELECT Idempleado FROM cit_eventos
					WHERE '$Fecha' between FechaIni and FechaFin
					AND (Idempleado='$Procedimiento' OR Idempleado='Todos')";
		$Query = mysql_query($SQLTxt) or die('La consulta fall&oacute;: ' . mysql_error());
		$Row = mysql_fetch_array($Query);
		
		$TXT = "SELECT COUNT(*) as CitasProgramadas
				FROM cit_citasprocedimientos
				WHERE Fecha='$Fecha'
				AND IdCiq='$Procedimiento'
				AND (IdEstado=1 OR IdEstado=6)";
		$SQLT = mysql_query($TXT) or die ('La consulta fall&oacute;: '. mysql_error());
		$Rw = mysql_fetch_array($SQLT);
		
		return $Resultado["TotalEventos"].'/'.$Row["Idempleado"].'/'.$Rw["CitasProgramadas"];
		
	}
}

function HorarioMedico($Procedimiento,$actual,$IdSubServicio){
	$SQL="SELECT mnt_procedimientosxestablecimiento.IdRngHr,concat(cast(mnt_rangohoras.Hora_Ini as char),'-',cast(mnt_rangohoras.Hora_Fin as char)) as Hora 
FROM mnt_procedimientosxestablecimiento 
INNER JOIN mnt_rangohoras ON mnt_procedimientosxestablecimiento.IdRngHr=mnt_rangohoras.IdRangohoras 
		WHERE IdCiq='$Procedimiento' 
		AND Yrs=YEAR('$actual')
		AND Dia=DAYOFWEEK('$actual')";
	$resp=mysql_query($SQL);

 	$combo='<select id="Horario" onChange="MostrarId(\''.$Procedimiento.'\',\''.$actual.'\',\''.$IdSubServicio.'\',this.value)">';
		while($row=mysql_fetch_array($resp)){
			$combo.="<option value='".$row[0]."'>".$row[1]."</option>";
		}
			$combo.="</select>";

	return($combo);
}

function PrimerHorario($IdCiq,$Fecha){
	$SQL=	"SELECT mnt_procedimientosxestablecimiento.IdRngHr,
		concat(cast(mnt_rangohoras.Hora_Ini as char),'-',cast(mnt_rangohoras.Hora_Fin as char)) as Hora 
		FROM mnt_procedimientosxestablecimiento 
		INNER JOIN mnt_rangohoras ON mnt_procedimientosxestablecimiento.IdRngHr=mnt_rangohoras.IdRangohoras 
		WHERE IdCiq='$IdCiq' 
		AND Yrs=YEAR('$Fecha')
		AND Dia=DAYOFWEEK('$Fecha')
		LIMIT 1";
	$resp=mysql_query($SQL);

 	$row=mysql_fetch_array($resp);

	return($row[0]);
}

function MostrarDetalleCitas($IdCiq,$Fecha,$IdSubServicio,$Horario){


echo '<fieldset class="detalleEstados">
		<legend class="tituloFieldset">PACIENTES CITADOS</legend>';

	$TipoCita=1;
	AgendaMedica::DetalleCitas($IdCiq,$Fecha,$TipoCita,$IdSubServicio,$Horario);

echo	'</fieldset><br />
	<fieldset class="detalleEstados">
	<legend class="tituloFieldset">PACIENTES AGREGADOS</legend>';

	$TipoCita=6;
	AgendaMedica::DetalleCitas($IdCiq,$Fecha,$TipoCita,$IdSubServicio,$Horario);

echo	'</fieldset>';


}

function ConstructordeComprobante($IdCita,$Bandera){
    
    if($Bandera==2){
        $querySelect=   "SELECT DISTINCT UPPER(mnt_ciq.procedimiento),cit_citasprocedimientos.idnumeroexp,CONCAT_WS(' ', mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido, mnt_datospaciente.PrimerNombre, mnt_datospaciente.SegundoNombre, mnt_datospaciente.TercerNombre) as Nombres,mnt_rangohoras.hora_ini,DATE_FORMAT(fecha,'%d/%m/%Y') as fecha,(select mnt_empleados.NombreEmpleado from mnt_empleados where IdTipoEmpleado='ACL' and Correlativo=cit_citasprocedimientos.IdUsuarioReg) as Usuario,(select mnt_establecimiento.nombre from mnt_establecimiento where mnt_establecimiento.idestablecimiento=idestablecimientoreferencia) as hospitalreferencia,nombreempleado
                    FROM cit_citasprocedimientos
                    INNER JOIN mnt_ciq ON cit_citasprocedimientos.idciq=mnt_ciq.idciq
                    INNER JOIN mnt_procedimientosxestablecimiento ON mnt_procedimientosxestablecimiento.idciq=mnt_ciq.idciq
                    INNER JOIN mnt_rangohoras on cit_citasprocedimientos.IdRngHr=mnt_rangohoras.IdRangohoras
                    INNER JOIN mnt_establecimiento on cit_citasprocedimientos.idestablecimiento=mnt_establecimiento.idestablecimiento
                    LEFT JOIN mnt_empleados ON mnt_procedimientosxestablecimiento.IdEmpleado=mnt_empleados.IdEmpleado
                    INNER JOIN mnt_expediente ON mnt_expediente.IdNumeroExp=cit_citasprocedimientos.IdNumeroExp 
                    INNER JOIN mnt_datospaciente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
                    WHERE IdCitaProc=$IdCita
                    AND DAYOFWEEK(fecha)=dia 
                    AND YEAR(fecha)=yrs
                    AND cit_citasprocedimientos.idrnghr=mnt_procedimientosxestablecimiento.idrnghr";
        $answ=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
            $answs=mysql_query($querySelect);
        if(mysql_fetch_array($answ)!=NULL OR mysql_fetch_array($answ)!=''){
            return($answs);
        }
    }
    
    if($Bandera==3){
        $querySelect=   "SELECT  cit_citasxdia.idnumeroexp,DATE_FORMAT(cit_fechas.fecha,'%d/%m/%Y'),mnt_empleados.NombreEmpleado, 
                    CONCAT_WS(' ', mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido, mnt_datospaciente.PrimerNombre, mnt_datospaciente.SegundoNombre, mnt_datospaciente.TercerNombre) as Nombres,
                    mnt_subservicio.NombreSubServicio,mnt_rangohoras.Hora_Ini,mnt_consultorios.Descripcion,
                    (select mnt_empleados.NombreEmpleado from mnt_empleados where cit_citasxdia.IdUsuarioReg=mnt_empleados.IdEmpleado) as Usuario
                    FROM cit_citasxdia
                    INNER JOIN cit_fechas ON cit_citasxdia.idcita=cit_fechas.idcita
                    INNER JOIN mnt_expediente ON mnt_expediente.IdNumeroExp=cit_citasxdia.IdNumeroExp 
                    INNER JOIN mnt_datospaciente ON mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
                    INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicioxestablecimiento.IdSubServicio=cit_citasxdia.IdSubservicio
                    INNER JOIN mnt_subservicio ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
                    INNER JOIN mnt_empleados ON mnt_empleados.IdEmpleado=cit_citasxdia.Idempleado
                    INNER JOIN cit_distribucion ON cit_distribucion.idempleado=mnt_empleados.IdEmpleado
                    INNER JOIN mnt_rangohoras ON mnt_rangohoras.IdRangohoras=cit_distribucion.idrangohoras
                    INNER JOIN mnt_consultorios ON cit_distribucion.idconsultorio=mnt_consultorios.idconsultorio
                    WHERE cit_citasxdia.IdCita=$IdCita
                    AND DAYOFWEEK(cit_fechas.fecha)=dia 
                    AND YEAR(cit_fechas.fecha)=yrs
                    AND MONTH(cit_fechas.fecha)=mes
                    AND cit_fechas.IdRngHrs=cit_distribucion.idrangohoras";        
        $answ=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
            $answs=mysql_query($querySelect);
        if(mysql_fetch_array($answ)!=NULL OR mysql_fetch_array($answ)!=''){
            return($answs);
        }
    }
                
}

}// Fin de clase Agenda

class DistribucionIdCiq{
	// Método Constructor de la clase 
	function DistribucionIdCiq(){	
	}

function DistribucionProcedimiento($Procedimiento,$Fecha,$horario,$establecimiento){
 
    $querySelect="SELECT * FROM mnt_procedimientosxestablecimiento 
		WHERE IdCiq='$Procedimiento' 
		AND Yrs=YEAR('$Fecha')
		AND Dia=DAYOFWEEK('$Fecha')
		AND IdRngHr=$horario
		AND IdEstablecimiento=$establecimiento";
    $resp=mysql_query($querySelect) or die('La consulta No.1 fall&oacute;:' . mysql_error());
    $answ=mysql_query($querySelect) or die('La consulta No.2 fall&oacute;:' . mysql_error());
        if(mysql_fetch_array($resp)!=NULL OR mysql_fetch_array($resp)!=''){
            return($answ);    
        }else{
            return -1;
        }
}//Distribucion

function FechaHabiles($IdMedico,$Fecha,$idsubesp){
    $Year=clsDarCitas::ConvYear($Fecha);
    $i=0;
    $querySelect="select dia from cit_distribucion where idempleado='$IdMedico' and yrs='$Year' and idtipocon='EXT' and IdSubServicio=$idsubesp";
    $resp=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
        while($row=mysql_fetch_array($resp)){
            $nuevo=clsDarCitas::NombreDia($row[0]);
            $dias[$i]=$nuevo;
            $i++;
        }
        return($dias);
            
}//FechasHabiles

function NombreDia($Dia){
    switch($Dia){
            case 2:
                return("LUNES");
            break;
            case 3:
                return("MARTES");
            break;
            case 4:
                return("MIERCOLES");
            break;
            case 5:
                return("JUEVES");
            break;
            case 6:
                return("VIERNES");
            break;

    }//switch
}//NombreDia

function CantidadHorarios($IdMedico,$Yrs,$Mes,$Dia,$idsubesp){

$consulta  =	"SELECT COUNT(cit_distribucion.idrangohoras),cit_distribucion.idrangohoras 
		FROM cit_distribucion
		INNER JOIN mnt_rangohoras ON cit_distribucion.idrangohoras=mnt_rangohoras.IdRangohoras
		WHERE idempleado='$IdMedico' AND yrs= $Yrs AND (mes=$Mes OR mes=13) AND dia=$Dia 
		AND IdSubServicio=$idsubesp";
        $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
        $rows = mysql_fetch_array($resultado);
	$total=$rows[0].'/'.$rows[1];
    	return ($total);
	
}


function ConstructorFecha($Date_c){
    $citas = new clsCitas;
    $actual=$citas->Fechas($Date_c);
    return($actual);
}

function ObtenerCitasCIQ($IdNumeroExp,$IdUsuarioReg,$IdCiq,$establecimiento){
   $SQL="select IdCitaProc
	from cit_citasprocedimientos
	where IdNumeroExp='$IdNumeroExp'
	and IdUsuarioReg='$IdUsuarioReg'
	and IdEstablecimiento=$establecimiento
	and IdCiq='$IdCiq'
	and (IdEstado=1 or IdEstado=6)
	and Fecha <> date(now())";
   $resp=mysql_query($SQL);
   return($resp);
}

function NombreMes($Mes){
    switch($Mes){
            case 1:
                return("ENERO");
            break;
            case 2:
                return("FEBRERO");
            break;
            case 3:
                return("MARZO");
            break;
            case 4:
                return("ABRIL");
            break;
            case 5:
                return("MAYO");
            break;
	    case 6:
                return("JUNIO");
            break;
            case 7:
                return("JULIO");
            break;
            case 8:
                return("AGOSTO");
            break;
            case 9:
                return("SEPTIEMBRE");
            break;
            case 10:
                return("OCTUBRE");
            break;
	    case 11:
                return("NOVIEMBRE");
            break;
            case 12:
                return("DICIEMBRE");
            break;

    }//switch
}//NombreDia

//Funcion para verificar si este pact tiene cita ya en esa fecha (SOLO UNA CITA POR DIA DE CUALQUIER ESP)
function PoseeCita($Fecha,$Expediente,$Horario){
	$querySelect= "SELECT mnt_subservicio.NombreSubServicio,cit_citasxdia.Idempleado,mnt_rangohoras.Hora_Ini FROM cit_citasxdia 
                        INNER JOIN cit_fechas on cit_citasxdia.IdCita=cit_fechas.IdCita
                        INNER JOIN mnt_rangohoras on cit_fechas.IdRngHrs=mnt_rangohoras.IdRangohoras
                        INNER JOIN mnt_subservicio on cit_citasxdia.IdSubservicio=mnt_subservicio.IdSubServicio
                        WHERE cit_citasxdia.Fecha='$Fecha' 
                        AND IdNumeroExp='$Expediente'
                            
                UNION
                        SELECT UPPER(mnt_ciq.Procedimiento),cit_citasprocedimientos.IdCiq,mnt_rangohoras.Hora_Ini
			FROM cit_citasprocedimientos
			INNER JOIN mnt_ciq ON cit_citasprocedimientos.IdCiq=mnt_ciq.IdCiq
                        INNER JOIN mnt_rangohoras on cit_citasprocedimientos.IdRngHr=mnt_rangohoras.IdRangohoras
			WHERE cit_citasprocedimientos.IdNumeroExp='$Expediente'
			AND cit_citasprocedimientos.Fecha='$Fecha'";
	$resp=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
    	$answ=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
        if(mysql_fetch_array($resp)!=NULL OR mysql_fetch_array($resp)!=''){
            return($answ);    
        }else{
            return 1;
        }  
  
}

function EsCitaDeEseEmpleadoyEspecialidad($Fecha,$IdNumeroExp,$IdEmpleado,$IdSubServicio){
    $querySelect= "SELECT mnt_subservicio.NombreSubServicio,cit_citasxdia.Idempleado,mnt_rangohoras.Hora_Ini FROM cit_citasxdia 
                        INNER JOIN cit_fechas on cit_citasxdia.IdCita=cit_fechas.IdCita
                        INNER JOIN mnt_rangohoras on cit_fechas.IdRngHrs=mnt_rangohoras.IdRangohoras
                        INNER JOIN mnt_subservicio on cit_citasxdia.IdSubservicio=mnt_subservicio.IdSubServicio
                        WHERE cit_citasxdia.Fecha='$Fecha' 
                        AND IdNumeroExp='$IdNumeroExp'
                        AND cit_citasxdia.IdUsuarioReg='$IdEmpleado'
                        AND cit_citasxdia.IdSubServicio=$IdSubServicio
                        AND DATE(cit_citasxdia.FechaHoraReg)=curdate()";
    $resp=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
    	$answ=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
        if(mysql_fetch_array($resp)!=NULL OR mysql_fetch_array($resp)!=''){
            return($answ);    
        }else{
            return 1;
        }
}

}

class AgendaMedicoSinSeguimiento{
	// Método Constructor de la clase 
	function AgendaMedicoSinSeguimiento(){	
	}
        
function DistribucionCitaMedico($IdMedico,$Fecha,$idsubesp,$horario){
$Dia= AgendaMedicoSinSeguimiento::ConvDia($Fecha);
$Year= AgendaMedicoSinSeguimiento::ConvYear($Fecha);
$Mes= AgendaMedicoSinSeguimiento::ConvMonth($Fecha);
    
    $querySelect="select * from cit_distribucion where idempleado='$IdMedico' and dia='$Dia' and yrs='$Year' and mes=$Mes and IdSubServicio=$idsubesp and IdRangohoras=$horario";
    $resp=mysql_query($querySelect) or die('La consulta No.8 fall&oacute;:' . mysql_error());
    $answ=mysql_query($querySelect) or die('La consulta No.9 fall&oacute;:' . mysql_error());
        if(mysql_fetch_array($resp)!=NULL OR mysql_fetch_array($resp)!=''){
            return($answ);    
        }else{
            return -1;
        }
}//Distribucion

function ConvDia($Fecha){
    $querySelect="select dayofweek('$Fecha') as Dia";
    $resp=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
    $row=mysql_fetch_array($resp);
    return ($row["Dia"]);
}

function ConvYear($Fecha){
    $querySelect="select year('$Fecha') as Ano";
    $resp=mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
    $row=mysql_fetch_array($resp);
    return ($row["Ano"]);
}

//funci�n para obtener el mes de 1-12
function ConvMonth($Fecha){
    $querySelect="SELECT month('$Fecha') as Mes";
    $resp =mysql_query($querySelect) or die('La consulta fall&oacute;:' . mysql_error());
    $row=mysql_fetch_array($resp);
    return ($row["Mes"]);
}

function CantidadCitas($IdEmpleado,$Fecha,$TipoCita,$IdSubServicio,$Horario){
    $sqlText = 	"SELECT count(idfecha) AS Total FROM cit_fechas 
                INNER JOIN cit_citasxdia ON cit_fechas.IdCita=cit_citasxdia.IdCita
 		WHERE cit_fechas.idempleado='".$IdEmpleado."' 
                AND cit_fechas.fecha='".$Fecha."' 
                AND tipo_cita=$TipoCita 
                AND cit_citasxdia.IdSubServicio=$IdSubServicio 
                AND IdRngHrs=".$Horario;
    $queryCont = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
    if($rows=mysql_fetch_array($queryCont)){
        $cont=$rows["Total"];
    }else{
 	$cont=0;
    }
    
    return ($cont);
}

function ObtenerCitasMedico($IdNumeroExp,$IdUsuarioReg){
   $SQL="SELECT IdCita
	FROM cit_citasxdia
	WHERE IdNumeroExp='$IdNumeroExp'
	AND IdUsuarioReg='$IdUsuarioReg'
	AND (IdEstado=1 or IdEstado=6)
	AND Fecha <> date(now())";
   $resp=mysql_query($SQL);
   return($resp);
}

}//Fin clase

?>
