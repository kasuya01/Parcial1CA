<?php
include_once("../../../Conexion/ConexionBD.php");

class clsRecepcion
{
 //constructor	
 function clsRecepcion(){
 }
 
function DatosEstablecimiento($lugar){
$con = new ConexionBD;
	if($con->conectar()==true){			  
		$conNom  = "SELECT mnt_establecimiento.IdTipoEstablecimiento,Nombre,NombreTipoEstablecimiento 
			    FROM mnt_establecimiento 
			    INNER JOIN mnt_tipoestablecimiento ON mnt_establecimiento.IdTipoEstablecimiento= mnt_tipoestablecimiento.IdTipoEstablecimiento
			    WHERE IdEstablecimiento=$lugar";
		$resul = mysql_query($conNom) or die('La consulta fall&oacute;: ' . mysql_error());
	}
 return $resul;
}

function LlenarCmbEstablecimiento($Idtipo){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdEstablecimiento,Nombre FROM mnt_establecimiento WHERE IdTipoEstablecimiento='$Idtipo' ORDER BY Nombre";		
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbServ($IdServ,$lugar){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_subservicio.IdSubServicio,mnt_subservicio.NombreSubServicio
FROM mnt_subservicio 
INNER JOIN mnt_subservicioxestablecimiento ON mnt_subservicio.IdSubServicio=mnt_subservicioxestablecimiento.IdSubServicio
WHERE mnt_subservicio.IdServicio='$IdServ' AND IdEstablecimiento=$lugar 
ORDER BY NombreSubServicio";		
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbMed($idSubEsp,$lugar)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   INNER JOIN mnt_usuarios ON mnt_empleados.IdEmpleado=mnt_usuarios.IdEmpleado 
				WHERE mnt_usuarios.IdSubServicio=$idSubEsp  AND mnt_empleados.IdEstablecimiento=$lugar ORDER BY mnt_empleados.NombreEmpleado";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbMedicos($lugar)
 {//echo $IdSub;
	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT mnt_empleados.IdEmpleado,mnt_empleados.NombreEmpleado 
			   FROM mnt_empleados 
			   WHERE  mnt_empleados.IdEstablecimiento=$lugar  AND IdTipoEmpleado='MED' AND IdEmpleado<>'MED0000'
			   ORDER BY mnt_empleados.NombreEmpleado
";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}


function ObtenerServicio($idSubEsp){
$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdServicio FROM mnt_subservicio WHERE IdSubServicio=$idSubEsp";
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbOrigen($IdMuestra){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$sqlText= "SELECT IdOrigenMuestra, OrigenMuestra FROM mnt_origenmuestra WHERE IdTipoMuestra='$IdMuestra' ORDER BY OrigenMuestra";		
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbMuestra($IdEstudio){
	$con = new ConexionBD;
	if($con->conectar()==true){		
		$sqlText= "SELECT  TipoMuestra, lab_tipomuestraporexamen.IdTipoMuestra 
			   FROM lab_tipomuestraporexamen  
			   INNER JOIN lab_tipomuestra ON lab_tipomuestra.IdTipoMuestra=lab_tipomuestraporexamen.IdTipoMuestra 
			   WHERE lab_tipomuestraporexamen.idexamen='$IdEstudio' ORDER BY TipoMuestra";			
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}

function LlenarCmbArea($IdArea,$lugar){
	$con = new ConexionBD;
	if($con->conectar()==true){	
		$sqlText= "SELECT nombreexamen,lab_examenes.IdExamen FROM lab_examenes 
INNER JOIN lab_examenesxestablecimiento ON lab_examenes.IdExamen=lab_examenesxestablecimiento.IdExamen
WHERE IdArea='$IdArea'AND  lab_examenesxestablecimiento.Condicion='H'  AND IdEstablecimiento=$lugar ORDER BY nombreexamen ";	
		$dt = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	}
	return $dt;
}



function BuscarOrigen($IdOrigen){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL = "SELECT OrigenMuestra FROM mnt_origenmuestra WHERE IdOrigenMuestra=$IdOrigen";
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
	
		while ($row=mysql_fetch_array($dt)){
			$valret="$row[0]";
		}
	}
	return $valret;
}

function RecuperarTipoMuestra($IdTipoMuestra){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL = "SELECT TipoMuestra FROM lab_tipomuestra WHERE IdTipoMuestra=$IdTipoMuestra";
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
	
	while ($row=mysql_fetch_array($dt)){
		$valret="$row[0]";
	}
	}
	return $valret;
}


function RecupererNombreExamen($IdExamen){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL="SELECT NombreExamen FROM lab_examenes WHERE IdExamen='$IdExamen'";
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
	
	while ($row=mysql_fetch_array($dt)){
		$valret="$row[0]";
	}
	}
	return $valret;
}

function InsertarRecepcionPiloto($idexam,$tipo,$origen,$indicaciones){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL="INSERT INTO recepcionpiloto(Id,IdExamen,IdTipoMuestra,IdOrigenMuestra,Observacion) VALUES(0,'$idexam',$tipo,$origen,'$indicaciones')";	
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
	
	}
	return -1;
}

function RecuperarDatos(){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL="SELECT * FROM recepcionpiloto";
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
	}
	
	return $dt;
}

function RecupererObservacion($id){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL="SELECT Observacion FROM recepcionpiloto WHERE Id=$id";
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
	
	while ($row=mysql_fetch_array($dt)){
		$valret="$row[0]";
	}
	}
	return $valret;
}
function ValidarExpediente($nec){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$query_Search= 	"SELECT e.idnumeroexp, if(d.SegundoApellido IS NULL and d.SegundoNombre IS NULL, CONCAT(d.PrimerApellido,', ',d.PrimerNombre),
IF(d.SegundoApellido IS NOT NULL and d.SegundoNombre IS NOT NULL,CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre,' ',d.SegundoNombre),
IF(d.SegundoNombre IS NULL, CONCAT(d.PrimerApellido,' ',d.SegundoApellido,', ',d.PrimerNombre),CONCAT(d.PrimerApellido,', ',d.PrimerNombre,' ',d.SegundoNombre)))) AS Nombre
FROM mnt_datospaciente d INNER JOIN mnt_expediente AS e ON e.idpaciente=d.idpaciente WHERE e.idnumeroexp ='$nec'";
	$query = mysql_query($query_Search) or die('La consulta fallo&oacute;: ' . mysql_error());
	$totalRegs= mysql_numrows($query);
	}
	return $totalRegs;
}

function ModificacionExpDatosPac($pn,$pa,$sxn,$fn,$fechareg,$lugar){
         
	$con = new ConexionBD;
	if($con->conectar()==true){
	$sqlText = "INSERT INTO mnt_datospaciente(IdPaciente,PrimerApellido,SegundoApellido,PrimerNombre,SegundoNombre,TercerNombre,Sexo,FechaNacimiento,HoraNacimiento,PaisNacimiento,LugarNacimiento,EstadoCivil,DocuLegalIdentidad,NumDocLegalIdentidad,Ocupacion,Direccion,TelefonoCasa,DeptoDomicilio,MunicipioDomicilio,AreaGeografica,LugarTrabajo,TelefonoTrabajo,Asegurado,Noafiliacion,NombrePadre,NombreMadre,NombreConyuge,NombreResponsable,DireccionResponsable,TeleCasaResponsable,ProporcionoDatos,Parentesco,DocLegalProporDatos,NoDocLegalProporDatos,Observaciones,IdUsuarioReg,IdUsuarioMod,FechaHoraReg,FechaHoraMod) VALUES('0',UCASE('$pa'),' ',UCASE('$pn'),' ',' ','$sxn','$fn','00:00:00','0',' ','0','7','-','0',' ',' ','6','601','0',' ',' ','2',' ',' ','n',' ',' ',' ',' ',' ','0','7',' ',' ','200',' ',NOW(),'0000-00-00 00:00:00')"; 
	$queryIns = mysql_query($sqlText) or die('La consulta fall&oacute;:' . mysql_error());
	$iddato=mysql_insert_id();
	 
	$sqlInsert="INSERT INTO mnt_expediente(IdNumeroExp,IdPaciente,IdUsuarioReg,IdUsuarioMod,FechaHoraReg,FechaHoraMod,IdEstablecimiento) VALUES($iddato,$iddato,'200',' ',NOW(),'0000-00-00 00:00:00',$lugar)";
	$queryIns = mysql_query($sqlInsert) or die('La consulta fall&oacute;:' . mysql_error());
		}
	return $iddato;
}
function VerificarDatos($idexam,$tipo,$origen){
	$con = new ConexionBD;
	if($con->conectar()==true){
		$SQL="SELECT * FROM recepcionpiloto WHERE IdExamen='$idexam' AND IdTipoMuestra=$tipo AND IdOrigenMuestra=$origen";
		$dt=mysql_query($SQL) or die('La consulta fall&oacute;:' . mysql_error());
		$totalRegs= mysql_numrows($dt);
	}
	return $totalRegs;
}
}//clase

?>