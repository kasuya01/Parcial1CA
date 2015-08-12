<?php session_start();
$nivel=$_SESSION['NIVEL'];  
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

?>
<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Cambio de Clave</title>
<script language="JavaScript" type="text/javascript" src="ajax_CambioClave.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="frmnuevo.txtClaveActual.focus();"  >

<?php 

//echo $nivel;

if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
if ($nivel==4){
	include_once ('../../../PaginaPrincipal/index_laboratorio42.php');}   
if ($nivel == 5) {
        include_once ('../../../PaginaPrincipal/index_laboratorio52.php');}
if ($nivel == 6) {
        include_once ('../../../PaginaPrincipal/index_laboratorio62.php');}
if ($nivel == 7) {
        include_once ('../../../PaginaPrincipal/index_laboratorio72.php'); }         
?><br>
	
<table align="center" width="100%">
<tr>
	<td>
		<div  id="divFrmNuevo" >
			<form name="frmnuevo" action="" >
				<table width="50%" border="1" align="center" class="StormyWeatherFormTABLE">
			  		<tr>
				  		<td colspan="2" align="center" class="CobaltFieldCaptionTD"><h3><strong>Cambio de Clave</h3></strong></td>
			  		</tr>
			  		<tr>
			      			<td class="StormyWeatherFieldCaptionTD"><strong>Clave Actual</strong></td>
			      			<td class="StormyWeatherDataTD"><input type="password" name="txtClaveActual" id="txtClaveActual" ></td>
			   		</tr>
			   		<tr>
			       			<td class="StormyWeatherFieldCaptionTD"><strong>Nueva Clave </strong></td>
			       			<td class="StormyWeatherDataTD"><input  size="50" type="password" name="txtNuevaClave" id="txtNuevaClave" ></td>
			   		</tr>
					<tr>
			       			<td class="StormyWeatherFieldCaptionTD"><strong>Confirmar nueva clave </strong></td>
			       			<td class="StormyWeatherDataTD"><input  size="50" type="password" name="txtNuevaClave1" id="txtNuevaClave1" ></td>
			   		</tr>
			     	<tr>
			         		<td colspan="2" align="right" class="StormyWeatherDataTD">
			              			<input type="button" name="Submit" value="Cambiar Clave" onClick="ActualizarClave();">
							<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('CambioClave.php')">
			         		</td>
			        </tr>
		  		</table>
			</form>
		</div>
    </td>
</tr>
<tr>
	<td>
		<div  id="divresultado" >

		</div>
	</td>
</tr>
<tr>
	<td>
		<div  id="divFrmModificar" >

		</div>
		<div  id="divinicial" >

		</div>
	</td>
</tr>
</table>
</body>
</html>
