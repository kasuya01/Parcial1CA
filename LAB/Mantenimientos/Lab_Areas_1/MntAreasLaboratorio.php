<?php session_start();
$nivel=$_SESSION['NIVEL'];  
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea'];

?>
<html>
<head> 
<title>Mantenimiento de Areas de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Areas.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>
<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);frmnuevo.txtidarea.focus();">

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
?><br>
	
<table align="center" width="100%">
<tr>
	<td>
		<div  id="divFrmNuevo" >
		<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
		<table width="50%" border="1" align="center" class="StormyWeatherFormTABLE">
  		<tr>
	  		<td colspan="2" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de &Aacute;rea de Laboratorio Cl&iacute;nicos</h3></strong></td>
  		</tr>
  		<tr>
      			<td class="StormyWeatherFieldCaptionTD"><strong>C&oacute;digo del &Aacute;rea</strong></td>
      			<td class="StormyWeatherDataTD"><input type="text" name="txtidarea" id="txtidarea" ></td>
   		</tr>
   		<tr>
       			<td class="StormyWeatherFieldCaptionTD"><strong>Nombre del &Aacute;rea </strong></td>
       			<td class="StormyWeatherDataTD"><input  size="50" type="text" name="txtnombrearea" id="txtnombrearea" ></td>
   		</tr>
		<tr>
        		<td class="StormyWeatherFieldCaptionTD"><strong>Activa</strong> </td>
        		<td class="StormyWeatherDataTD">
				<select id="cmbActiva" name="cmbActiva" size="1" >
				<option value="0" >--Seleccione--</option>
				<option value="S" >SI</option>
				<option value="N" >NO</option>
				</select>
			</td>
     		</tr>
     		<tr>
         		<td colspan="2" align="right" class="StormyWeatherDataTD">
              			<input type="submit" name="Submit" value="Insertar">
				<input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
				<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntAreasLaboratorio.php')">
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
