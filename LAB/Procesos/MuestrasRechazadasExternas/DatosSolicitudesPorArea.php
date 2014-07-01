<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<!--<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />-->
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<title>Datos del Generales de la Solicitud</title>
<script language="JavaScript" type="text/javascript" src="ajax_MuestrasRechazadas.js"></script>
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
                        idexpediente = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var2=' )
						idarea= getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var3=' )
						idsolicitud = getVars[i].substr(5);
			if ( getVars[i].substr(0,5) == 'var4=' )
						idexamen = getVars[i].substr(5);
			/*if ( getVars[i].substr(0,5) == 'var5=' )
						idestablecimiento = getVars[i].substr(5);	*/		
		}
//alert(idexamen);
 CargarDatosFormulario(idexpediente,idsolicitud,idarea,idexamen);
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
