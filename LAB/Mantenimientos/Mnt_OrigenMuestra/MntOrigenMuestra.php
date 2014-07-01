<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Mantenimiento de Origen de Muestras</title>
<script language="JavaScript" type="text/javascript" src="ajax_Mnt_OrigenMuestra.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<script language="JavaScript" >
function Modificar()
{ 
	enviarDatos(); 
}

function Nuevo()
{ 
	MostrarFormularioNuevo(); 
}

function Cancelar()
{ 
	show_event(1);
}
</script>

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
<table width="60%" border="1" align="center" class="StormyWeatherFormTABLE">
  <tr>
	  <td colspan="2" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de Origen de Muestra</h3></strong></td>
  </tr>
  <tr>
      <td class="StormyWeatherFieldCaptionTD"><strong>C&oacute;digo Origen Muestra </strong></td>
      <td class="StormyWeatherDataTD"><input type="text" disabled="disabled" name="txtidorigen" id="txtidorigen" ></td>
   </tr>
   <tr>
       <td class="StormyWeatherFieldCaptionTD"><strong>Tipo Muestra </strong></td>
       <td class="StormyWeatherDataTD"><select id="cmbTipoMuestra" name="cmbTipoMuestra" size="1">
            <option value="0">--Seleccione un Tipo de Muestra --</option>
               
			<?php
			include('clsMnt_OrigenMuestra.php');
			$obje=new clsMnt_OrigenMuestra;
			$consulta= $obje->LeerTipoMuestra();
			while($row = mysql_fetch_array($consulta)){
			  echo "<option value='" . $row[0]. "'>" . $row[1] . "</option>";
			}
			?>
            </select>
	   
	   </td>
   </tr>
   <tr>
       <td class="StormyWeatherFieldCaptionTD"><strong>Origen de Muestra </strong></td>
       <td class="StormyWeatherDataTD"><input  size="40" type="text" name="txtnombreorigen" id="txtnombreorigen" ></td>
   </tr>
	
     <tr>
         <td colspan="2" align="right" class="StormyWeatherDataTD">
                <input type="submit" name="Submit" value="Guardar">
		<input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
	        <input type="button" name="Submit" value="Cancelar" onClick="window.location.replace('MntOrigenMuestra.php')">
         </td>
         
     </tr>
  </table>
</form>
</div>
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
<tr>
<td>

</div>
</td>
</tr>
</table>
</body>
</html>
