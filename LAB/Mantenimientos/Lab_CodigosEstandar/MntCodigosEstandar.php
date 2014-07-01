<?php session_start(); 
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
 include('clsLab_CodigosEstandar.php');

?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>Mantenimiento de Estandares de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_CodigosEstandar.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
</head>

<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);frmnuevo.txtidestandar.focus();">
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
<table align="center" width="80%">
<tr>
<td>
<div  id="divFrmNuevo" >
<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false" >
<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
    <tr>
		<td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de C&oacute;digos Est&aacute;ndares de Laboratorio</h3></strong>
		</td>
	</tr>
	<tr>
        <td class="StormyWeatherFieldCaptionTD">C&oacute;digo del Est&aacute;ndar</td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtidestandar" id="txtidestandar" /></td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Descripci&oacute;n </td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtnombreestandar" id="txtnombreestandar" size="45" /></td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Grupo</td>
        <td class="StormyWeatherDataTD">
            <select id="cmbgrupo" name="cmbgrupo" size="1">
		<option value="0" >--Seleccione un grupo--</option>
		<?php
               
                $obj=new clsLab_CodigosEstandar;
		$consulta= $obj->Leer_grupos();
		while($row = mysql_fetch_array($consulta)){
		   echo "<option value='" . $row['idgrupo']. "'>".$row['idgrupo']." - ".htmlentities($row['nombregrupo']) . "</option>";
		}
                
		?>		  
         </select>		 
      </td>
    </tr>
    <tr>
        <td  class="StormyWeatherDataTD" colspan="2" align="right">
            <input type="submit" name="Submit" value="Guardar">
	    <input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
	    <input type="button" name="Submit" value="Cancelar" onClick="Cancelar();">
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
