<?php session_start();
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];

 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<script language="JavaScript" type="text/javascript" src="ajax_RMAutomatizada.js"></script>
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Datos del Generales de la Solicitud por prueba</title>
<script>
    window.onunload = refreshParent;
</script>
<script language="JavaScript" >
jQuery(document).ready(function($) {
	RecogeValor()
});
function RecogeValor()
{
var vtmp=location.search;
var vtmp2 = vtmp.substring(1,vtmp.length);
var query = unescape(top.location.search.substring(1));
var getVars = query.split(/&/);
for ( i = 0; i < getVars.length; i++)
		{
			if ( getVars[i].substr(0,5) == 'var1=' )//loops through this array and extract each name and value
                        idexpediente = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var2=' )
						idarea= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var3=' )
						idsolicitud = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var4=' )
						idexamen = getVars[i].substr(5);
		}

 CargarDatosFormulario1(idexpediente,idsolicitud,idarea,idexamen);
//setfecharechazo();
}

</script>
</head>

<!--<body onLoad="RecogeValor();">-->
<body>
<div id="divFormulario">

</div>
<div id="divCambioEstado">
</div>
</body>
</html>
