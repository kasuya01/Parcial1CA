<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Mantenimiento de Observaciones</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_Observaciones.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>

<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">

<?php 
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
<table width="60%" border="0" align="center" class="StormyWeatherFormTABLE">
    <tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><H3><strong>Mantenimiento de Observaciones</strong></H3>
		 <input type="hidden" name="txtidobservacion" id="txtidobservacion" disabled="disabled" />
                 </td>
	</tr>	
	<!-- <tr>
		<td class="StormyWeatherFieldCaptionTD">Idobservacion</td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtidobservacion" id="txtidobservacion" disabled="disabled" />
		</td>
    </tr> -->
	<tr>
        <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del &Aacute;rea</td>
        <td class="StormyWeatherDataTD">
			<select id="cmbArea" name="cmbArea" size="1">
			<option value="0" >--Seleccione un &Aacute;rea--</option>
			<?php
			$objeareas=new clsLab_Areas;
			$consulta= $objeareas->consultaractivas($lugar);
			while($row = pg_fetch_array($consulta)){
			echo "<option value='" . $row['id']. "'>" . $row['nombrearea'] . "</option>";
			}
			?>		  
          </select>
	    </td>
    </tr>
	<tr>
        <td class="StormyWeatherFieldCaptionTD">Tipo Respuesta </td>
        <td class="StormyWeatherDataTD">
			<select id="cmbTipoRespuesta" name="cmbTipoRespuesta" size="1" >
			<option value="0" >--Seleccione--</option>
			<option value="P" >Positivo</option>
			<option value="N" >Negativo</option>
			<option value="O" >Otro</option>
			</select>
		</td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Observaci&oacute;n </td>
        <td class="StormyWeatherDataTD"><textarea name="txtobservacion" cols="60" id="txtobservacion"></textarea></td>
    </tr>
    <tr>
        <td colspan="2" class="StormyWeatherDataTD" align="right">
            <input type="submit" name="Submit" value="Guardar">
	        <input type="button" name="btnbuscar" value="Buscar" onClick="BuscarObservacion();">
			 <input type="button" name="Submit" value="Cancelar" onClick="window.location.replace('MntObservaciones.php')">
        </td>
    </tr>
</table>
</form>

</div>
</td>
</tr>
<tr>
<td>
</td>
</tr>
<tr>
<td>
<div  id="divFrmModificar" >

</div>
</td>
</tr>
<tr>
<td>
<div  id="divinicial" >

</div>

</td>
</tr>
</table>
</body>
</html>
