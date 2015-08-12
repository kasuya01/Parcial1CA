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
<title>Mantenimiento de Metodologías  </title>
<script language="JavaScript" type="text/javascript" src="ajax_Metodologia.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
</head>
<script language="JavaScript" type="text/javascript">
function ActualizarDatos()
{
   
    enviarDatosMetodologia();
}

function guardarmetodoligia()
{
     if (document.getElementById('txtmetodologia').value == "")								  
		 {
			//alert("Debe Ingresar Posible Resultado");	
                      $(function ()   {
                                  $("#dialog").dialog({
                                    autoOpen: false,
                                    modal: true,
                                    buttons: {      
                                                "Cerrar": function () 
                                                            {
                                                                $(this).dialog("close");
                                                            }
                                            }
                                        });
                            $("#guardar")
                            //.button()
                            //.click(function () {
                            $("#dialog").dialog("open");
                           // });
                                });
                     
		 }
                 else{
                     IngresarRegistroMetodologia();
                     //ajax.
                 }
    
}

function Buscarmetodologia()

{
   // alert("nada");
     if (document.getElementById('txtmetodologia').value == "")								  
		 {
			//alert("Debe Ingresar Posible Resultado");	
                      $(function ()   {
                                  $("#dialog1").dialog({
                                    autoOpen: false,
                                    modal: true,
                                    buttons: {      
                                                "Cerrar": function () 
                                                            {
                                                                $(this).dialog("close");
                                                            }
                                            }
                                        });
                            $("#buscar")
                            //.button()
                            //.click(function () {
                            $("#dialog1").dialog("open");
                           // });
                                });
                     
		 }
                 else{
                     
                    BuscarMetodologia();
                 }
    
}

function MostrarNuevo()
{ LimpiarContoles();}
</script>
<body onLoad="show_event(1);">

<div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Debe Ingresar Metodología !!</cente></p>
</div>
<div id="dialog1" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Debe de Ingresar al menos un Parámetro de Búsqueda!!</cente></p>
</div>
<?php 

//echo "*".$corr."-".$lugar."-".$area."-".$nivel;
 
if ($nivel==1){
	include_once ('../../../PaginaPrincipal/index_laboratorio2.php');}
if ($nivel==2){
	include_once ('../../../PaginaPrincipal/index_laboratorio22.php');}
if ($nivel==31){
	include_once ('../../../PaginaPrincipal/index_laboratorio31.php');}
if ($nivel==33){
	include_once ('../../../PaginaPrincipal/index_laboratorio33.php');}
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
			<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
			<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de Metodologías </h3></strong>
					</td>
				</tr>
				<!--<tr>
					<td class="StormyWeatherFieldCaptionTD">IdAntibiotico</td>
					<td class="StormyWeatherDataTD"><input type="text" name="txtidantibiotico" id="txtidantibiotico" disabled="disabled" /></td>
				</tr>  -->
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Metodología  </td>
					<td class="StormyWeatherDataTD"><input type="text" name="txtmetodologia" id="txtmetodologia" size="40" placeholder="Ingrese Metodología " class="form-control height placeholder" />
                                           </tr>
				<!--<tr>
					<td class="StormyWeatherDataTD" colspan="2" align="right">
						<input type="submit" name="Submit" value="Guardar" onClick="guardarposiblere()">
						<input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
						<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntPosresultado.php')"></td>
                                                                                                                        
                                </tr>-->
                                
                                 
                         <tr>  
                            <td class="StormyWeatherDataTD" colspan="6" align="right">
                                <button type='button' align="center" class='btn btn-primary'  id="guardar" onclick='guardarmetodoligia(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                <button type='button' align="center" class='btn btn-primary'  id="buscar" onclick='Buscarmetodologia(); '><span class='glyphicon glyphicon-search'></span>  Buscar </button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntMetodologia.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
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
