<?php session_start();
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">    
<title>Mantenimiento de Cantidad Tincion</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_CantidadTincion.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
<script language="JavaScript" type="text/javascript">

                function buscarcantidadTincion()
                {
                    if (document.getElementById('txtCanTin').value == 0) {
                        alert("Debe de Ingresar un Parámetro de Búsqueda ");
                    }
                 //  ElemTin=document.getElementById('txtElemTincion').value;
                    
                    else {
                        BuscarCodigo();
                    }
                    
                    //idarea=document.getElementById('cmbArea').value;
                    //BuscarObservacion();
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
<table width="50%" border="0" align="center" class="StormyWeatherFormTABLE">
    <tr>
		<td colspan="2" class="CobaltFieldCaptionTD" align="center"><h3><strong>Mantenimiento de Cantidad Tinci&oacute;n</strong></h3>
		</td>
	</tr>
	<!--<tr>
        <td class="StormyWeatherFieldCaptionTD">IdCantidad</td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtidCanTin" id="txtidCanTin" disabled="disabled" /></td>
    </tr> -->
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Cantidad Tinci&oacute;n </td>
        <td class="StormyWeatherDataTD"><input type="text" name="txtCanTin" id="txtCanTin" size="35" />
                                        <input type="hidden" name="txtidCanTin" id="txtidCanTin" disabled="disabled" />
        </td>
    </tr>
    <!--<tr>
        <td class="StormyWeatherDataTD" colspan="2" align="right">
              <input type="submit" name="Submit" value="Guardar">
	      <input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
	      <input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntCantidadTincion.php')">
        </td>
    </tr>-->
    
    
    <tr>
                <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='submit' align="center" class='btn btn-primary'  ><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
                                <button type='button' align="center" class='btn btn-primary'  onclick='buscarcantidadTincion(); '><span class='glyphicon glyphicon-search'></span> Buscar </button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntCantidadTincion.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                
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
