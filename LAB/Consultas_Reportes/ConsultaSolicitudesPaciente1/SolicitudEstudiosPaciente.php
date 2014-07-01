<?php session_start();
//if(isset($_SESSION['Correlativo']))
//{
//$usuario=1;//$_SESSION['Correlativo'];
$lugar=584;//$_SESSION['Lugar'];
//$area='BAT';//$_SESSION['Idarea'];
//$nivel=1;//$_SESSION['NIVEL'];
 include_once("clsSolicitudesPorPaciente.php"); 
//consulta los datos por su id
/*$obj = new clsSolicitudesPorPaciente;
$consulta=$obj->DatosEstablecimiento($lugar);
$row = mysql_fetch_array($consulta);
//valores de las consultas
$tipo=$row[0];
$nombrEstab=$row[1];
$nomtipo=$row[2];*/
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Datos del Generales de la Solicitud</title>
<script language="JavaScript" type="text/javascript" src="ajax_SolicitudPorPaciente.js"></script>
<script language="JavaScript" >
function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
	{
		if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
                        //idhistorialclinico = getVars[i].substr(5);
						idhistorialclinico = 21048;
		if ( getVars[i].substr(0,5) == 'var2=' )
			idsolicitud = getVars[i].substr(5);
                    
                    
	}

   
 CargarDatosFormularioSolicitud(idhistorialclinico);
}

</script>
</head>

<body onLoad="RecogeValor();">
<div id="divFormulario">

</div>
<div id="divCambioEstado">
</div>
</body>
</html>
<?php
//}
//else{
?>
<script language="javascript">
	//window.location="../../../login.php";
</script>
<?php //}?>