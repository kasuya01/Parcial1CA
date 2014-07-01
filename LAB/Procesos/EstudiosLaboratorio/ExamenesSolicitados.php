<?php session_start();
include_once("./ClaseSolicitud.php"); //Agregamos el Archivo con las clases y funciones a utilizar
	// Creamos un objeto Conexion, Paciente, Laboratorio
	$Conexion= new ConexionBD;  
        $conectar=$Conexion->conectar();
	$Paciente= new Paciente;   
	$Laboratorio= new SolicitudLaboratorio; 	
	$_SESSION["conectar"]=$conectar;
	$IdHistorialClinico=$_GET["IdHistorialClinico"];
        $IdCitaServApoyo=$_GET["IdCitaServApoyo"];
        $IdEstablecimiento=$_SESSION["IdEstablecimiento"];
	$IdNumeroExp=$_SESSION["IdNumeroExp"];
        $iduser=$_SESSION["iduser"];
        $LugardeAtencion=$_SESSION["lugar"];
	$NombrePaciente=$Paciente->RecuperarNombre($conectar,$IdNumeroExp);      
        //echo 'IdHistorialClinico:'.$IdHistorialClinico .' IdCitaServApoyo:  '.$IdCitaServApoyo.'  IdNumeroExp: '.$IdNumeroExp. '</br>';
        //exit;
        // recuperar el IdSolicituEstudio
        $IdSolicitudEstudio=$Paciente->RecuperarIdSolicituEstudio($conectar,$IdNumeroExp,$IdHistorialClinico);
        /* HACER AKI EL IF DE VERIFICACION DE IdCitaServApoyo Y ASI HACER EL INSERT O EL UPDATE*/
        if($IdCitaServApoyo == "")
        {
            $IdCitaServApoyo=$Paciente->IdCitaServApoyoInsertUpdate($conectar,$IdSolicitudEstudio,$iduser,$IdNumeroExp,$LugardeAtencion,$IdCitaServApoyo,1);
        }
        else
        {
            $IdCitaServApoyo=$Paciente->IdCitaServApoyoInsertUpdate($conectar,$IdSolicitudEstudio,$iduser,$IdNumeroExp,$LugardeAtencion,$IdCitaServApoyo,0);
        }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Examenes Solicitados a Laboratorio</title>
<link rel="stylesheet" type="text/css" href="./Estilo.css"></link>
<script language="javascript" src="./ajax.js"></script>
</head>

<body onload="MostrarDetalle(<?php echo $IdHistorialClinico;?>);">

<table class='Encabezado'  >
		  <tr>
		  	<td><font color='black'><b>No Expediente:</b> </font></td>
		  	<td><font color='black'><b><?php echo $IdNumeroExp;?></b></font></td></tr>
		  <tr>
		  	<td><font color='black'><b>Nombre Paciente:</b></font></td> 
			<td><font color='black'><b><?php echo $NombrePaciente;?></b></font></td></tr>
	</table><br>
<div id='RespuestaAjax'>
	<?php
		$Laboratorio->DetalleEstudiosLaboratorio($conectar,$IdHistorialClinico,$IdEstablecimiento);
	?>
	</div>
	
<div id='Cambios'></div>
<div id='Cambios'></div>
<form name="Parametros" method="post">
	<input type="hidden" id="IdHistorialClinico" value="<?php echo $_GET["IdHistorialClinico"]; ?>">
	<input type="hidden" id="IdNumeroExp"		 value="<?php echo $_SESSION["IdNumeroExp"]; ?>">
   	<input type="hidden" id="FechaSolicitud" 	 value="<?php echo $_SESSION["Fecha"]; ?>">
   	<input type="hidden" id="IdUsuarioReg" 		 value="<?php echo $_SESSION["IdUsuarioReg"]; ?>">
   	<input type="hidden" id="FechaHoraReg" 		 value="<?php echo $_SESSION["FechaHoraReg"]; ?>">

</form>
</body>
</html>
