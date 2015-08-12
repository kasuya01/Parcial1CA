<?php
 
class clsProcedimientos
{
 //constructor    
 function clsProcedimientos(){
 }

function ComboHorarios(){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$consulta  = "SELECT idrangohoras, concat(cast(hora_ini as char),'-',cast(hora_fin as char)) as dato FROM mnt_rangohoras order by dato";
                $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                              
	}
	return $resultado;
}

function ComboHorariosSegundos($IdRngHr){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$consulta  = "SELECT idrangohoras, concat(cast(hora_ini as char),'-',cast(hora_fin as char)) as dato FROM mnt_rangohoras WHERE IdRangohoras<>$IdRngHr order by dato";
                $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                              
	}
	return $resultado;
}

function InsertarReg($IdCIQ,$Acupo,$DiasLectura,$IdRngHr,$CuposFijos,$ArrayDias,$LugardeAtencion,$IdEstablecimiento,$IdUsuarioReg,$FechaReg,$Yrs,$Empleado){
   $con = new ConexionBD;
   $Dias=explode("/",$ArrayDias);
   $tamano=sizeof($Dias);  
   for ($i=0;$i<($tamano-1);$i++){
	if($con->conectar()==true){
     		$query = "INSERT INTO mnt_procedimientosxestablecimiento(IdCiq,IdRngHr,IdEmpleado,TechoMaximo,CantidadAcupo,TiempoPrevio,Dia,Area,IdEstablecimiento,Yrs,IdUsuarioReg,FechaHoraReg) VALUES('$IdCIQ',$IdRngHr,'$Empleado',$CuposFijos,$Acupo,$DiasLectura,$Dias[$i],$LugardeAtencion,$IdEstablecimiento,$Yrs,$IdUsuarioReg,'$FechaReg')";
     		$valret = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
     		$result=2;             
   		}else {
			$result=-1;
		}
   }
	return $result;
}

function Actualizar($IdProcedimiento,$IdCIQ,$Acupo,$DiasLectura,$IdRngHr,$CuposFijos,$ArrayDias,$LugardeAtencion,$IdEstablecimiento,$IdUsuarioReg,$FechaReg,$Yrs,$IdRngHrViejo,$Empleado){
   $con = new ConexionBD;
   $Dias=explode("/",$ArrayDias);
   $tamano=sizeof($Dias);  
   for ($i=0;$i<($tamano-1);$i++){

	/***************Modificar el Registro Elegido*********************/
	if($con->conectar()==true){
     		$query = "UPDATE  mnt_procedimientosxestablecimiento 
			SET IdRngHr =$IdRngHr, TechoMaximo=$CuposFijos, CantidadAcupo=$Acupo, TiempoPrevio=$DiasLectura, Dia=$Dias[$i],Yrs=$Yrs,IdUsuarioMod=$IdUsuarioReg,FechaHoraMod='$FechaReg',IdEmpleado='$Empleado' 
			WHERE IdProcedimiento=$IdProcedimiento";
		
		
     		$valret = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());

	/********Comparo si el Horario fue modificado para cambiar las citas asignadas ********/
     		if($IdRngHrViejo<>$IdRngHr){
	
		$query1 = "SELECT Dia FROM mnt_procedimientosxestablecimiento WHERE IdProcedimiento=$IdProcedimiento";
		$results = mysql_query($query1);
		$row=mysql_fetch_array($results);
	
		/*Contamos para ver si se hace el update*/
		$re=	"SELECT count(cit_citasprocedimientos.IdCitaProc) 
			FROM cit_citasprocedimientos
			WHERE DAYOFWEEK(Fecha)=$row[0] AND YEAR(Fecha)=$Yrs AND IdCiq='$IdCIQ'  
			AND IdEstablecimiento=$IdEstablecimiento AND Fecha > CURDATE() AND IdRngHr=$IdRngHrViejo";
			$resp = mysql_query($re) or die('La consulta fall&oacute;: ' . mysql_error());
			$vareturn = mysql_fetch_array($resp);

			if($vareturn[0] > 0){
				/*Actualizo tabla cit_citasprocedimientos con nuevo IdRngHr*/
			$rt=	"UPDATE cit_citasprocedimientos
				SET IdRngHr=$IdRngHr
				WHERE DAYOFWEEK(Fecha)=$row[0] AND YEAR(Fecha)=$Yrs AND IdCiq='$IdCIQ'  
				AND IdEstablecimiento=$IdEstablecimiento AND Fecha > CURDATE() AND IdRngHr=$IdRngHrViejo";
			$return = mysql_query($rt);
			}
		$result=2;             
   	}else {
		$result=-1;
	}
}//Conexion
   }//For
	return $result;
	mysql_close();
}

function ComboHorariosBloqueo($IdCiq){
    $con = new ConexionBD;
	if($con->conectar()==true){
		$consulta  =    "SELECT idrangohoras, concat(cast(hora_ini as char),'-',cast(hora_fin as char)) as dato 
                                FROM mnt_rangohoras 
                                INNER JOIN mnt_procedimientosxestablecimiento ON mnt_rangohoras.IdRangoHoras=mnt_procedimientosxestablecimiento.IdRngHr
                                WHERE IdCiq='$IdCiq' AND yrs=year(curdate()) group by mnt_procedimientosxestablecimiento.IdRngHr";
                $resultado = mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
                 
	}
	return $resultado;
}

function InsertarBloqueo($IdCIQ,$FechaIni,$FechaFin,$IdRngHr,$ArrayDias,$IdModalidad,$IdEstablecimiento,$IdUsuarioReg,$FechaReg){
   $con = new ConexionBD;
   $Dias=explode("/",$ArrayDias);
   $tamano=sizeof($Dias);
   
   if($tamano!=1){
   for ($i=0;$i<($tamano-1);$i++){
	if($con->conectar()==true){
     		$query = "INSERT INTO cit_eventos(Idempleado,FechaIni,FechaFin,IdUsuarioReg,FechaHoraReg,IdRngHr,DiaSemana,IdEstablecimiento,IdModalidad) VALUES('$IdCIQ','$FechaIni','$FechaFin',$IdUsuarioReg,'$FechaReg',$IdRngHr,$Dias[$i],$IdEstablecimiento,$IdModalidad)";
     		$valret = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
     		$result=2;             
   		}else {
			$result=-1;
		}
    }
   }else{
       if($con->conectar()==true){
     		$query = "INSERT INTO cit_eventos(Idempleado,FechaIni,FechaFin,IdUsuarioReg,FechaHoraReg,IdRngHr,IdEstablecimiento,IdModalidad) VALUES('$IdCIQ','$FechaIni','$FechaFin',$IdUsuarioReg,'$FechaReg',$IdRngHr,$IdEstablecimiento,$IdModalidad)";
     		$valret = mysql_query($query) or die('La consulta fall&oacute;: ' . mysql_error());
     		$result=2;             
   		}else {
			$result=-1;
		}
   }
   
	return $result;
}

}//Clase
