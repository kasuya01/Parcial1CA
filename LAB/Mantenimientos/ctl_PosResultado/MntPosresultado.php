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
<title>Mantenimiento de Posibles Resultados </title>
<script language="JavaScript" type="text/javascript" src="ajax_Posresultado.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>
</head>
<script language="JavaScript" type="text/javascript">
function ActualizarDatos()
{
   
    enviarDatosposresultado();
}

function guardarposiblere()
{
     if (document.getElementById('txtposibleresultado').value == "")								  
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
                     IngresarRegistropos();
                     //ajax.
                 }
    
}

function Buscarposiblere()

{
   // alert("nada");
     if (document.getElementById('txtposibleresultado').value == "")								  
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
                     
                    BuscarPosresultados();
                 }
    
}

function MostrarNuevo()
{ LimpiarContoles();}
</script>
<body onLoad="show_event(1);">

<div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Debe Ingresar Posible Resultado!!</cente></p>
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
?><br>
<table align="center" width="100%">
	<tr>
		<td>
			<div  id="divFrmNuevo" >
			<form name="frmnuevo" action="" onSubmit="IngresarRegistro(); return false">
			<table width="40%" border="0" align="center" class="StormyWeatherFormTABLE">
				<tr>
					<td colspan="3" align="center" class="CobaltFieldCaptionTD"><h3><strong>Mantenimiento de Posible Resultado</h3></strong>
					</td>
				</tr>
				<!--<tr>
					<td class="StormyWeatherFieldCaptionTD">IdAntibiotico</td>
					<td class="StormyWeatherDataTD"><input type="text" name="txtidantibiotico" id="txtidantibiotico" disabled="disabled" /></td>
				</tr>  -->
				<tr>
					<td class="StormyWeatherFieldCaptionTD">Posible Resultado </td>
					<td class="StormyWeatherDataTD"><input type="text" name="txtposibleresultado" id="txtposibleresultado" size="40" placeholder="Ingrese Posible Resultado" class="form-control height placeholder" />
                                           </tr>
				<!--<tr>
					<td class="StormyWeatherDataTD" colspan="2" align="right">
						<input type="submit" name="Submit" value="Guardar" onClick="guardarposiblere()">
						<input type="button" name="Submit" value="Buscar" onClick="BuscarCodigo();">
						<input type="button" name="btnNuevo" id="btnNuevo" value="Cancelar" onClick="window.location.replace('MntPosresultado.php')"></td>
                                                                                                                        
                                </tr>-->
                                
                                 
                         <tr>  
                            <td class="StormyWeatherDataTD" colspan="6" align="right">
                                <button type='button' align="center" class='btn btn-primary'  id="guardar" onclick='guardarposiblere(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar </button>
                                <button type='button' align="center" class='btn btn-primary'  id="buscar" onclick='Buscarposiblere(); '><span class='glyphicon glyphicon-search'></span>  Buscar </button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntPosresultado.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
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
