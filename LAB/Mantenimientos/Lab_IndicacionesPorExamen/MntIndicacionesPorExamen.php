<?php session_start();
include('../Lab_Areas/clsLab_Areas.php');
$nivel=$_SESSION['NIVEL'];
$corr=$_SESSION['Correlativo'];
$lugar=$_SESSION['Lugar'];
$area=$_SESSION['Idarea']; 
$objeareas=new clsLab_Areas;
$ROOT_PATH = $_SESSION['ROOT_PATH'];
$base_url  = $_SESSION['base_url'];
                                        

?>

<html>
<head> 
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">  
<title>Mantenimiento de Examenes de Laboratorio</title>
<script language="JavaScript" type="text/javascript" src="ajax_Lab_IndicacionesPorExamen.js"></script>
<link rel="stylesheet" type="text/css" href="../../../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../../../Themes/StormyWeather/Style.css">
<?php include_once $ROOT_PATH.'/public/css.php';?>
<?php include_once $ROOT_PATH.'/public/js.php';?>

<script language="JavaScript" >
function Guardar(){
    
     if ((document.getElementById('cmbArea').value == 0)&&
             (document.getElementById('cmbExamen').value == 0)&& 
             (document.getElementById('txtindicacion').value == ""))
             { $(function ()   {
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
                            $("#guardar")
                            $("#dialog1").dialog("open");
                           
                                });
					
		 }
    
   else{	
        IngresarRegistro();
       }
}

function LlenarComboExamen(idarea)
{
    //alert(idarea); 
   LlenarExamenes(idarea);   
}
function Buscar(){
    
    if ((document.getElementById('cmbArea').value == 0)&&
             (document.getElementById('cmbExamen').value == 0)&& 
             (document.getElementById('txtindicacion').value == ""))
		 { $(function ()   {
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
                            $("#abrir")
                            $("#dialog").dialog("open");
                           
                                });
			//alert("Debe de Ingresar un Parámetro de Búsqueda")		
		 }
                 else{
                   BuscarDatos();  
                 }
	
}
function Actualizar(){
	enviarDatos();
}
function Nuevo(){
	MostraFormularioNuevo();
}
function Cancelar()
{
    show_event(1);
    LimpiarCampos();
}
</script>
</head>


<body link="#000000" vlink="#000000" alink="#ff0000" text="#000000" class="CobaltPageBODY" bottommargin="0" leftmargin="0" topmargin="0" rightmargin="0" marginwidth="0" marginheight="0" bgcolor="#fffff7" onLoad="show_event(1);">
<div id="dialog" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Debe de Ingresar al menos un Parámetro de Búsqueda!!</cente></p>
</div>
    <div id="dialog1" style='display:none;' title="¡Aviso!">
    <p> <cente>¡Complete los datos a Ingresar!!</cente></p>
</div>
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
<form name="frmnuevo">
<table width="52%" border="0" align="center" class="StormyWeatherFormTABLE">
	<tr>
		<td colspan="3" align="center" class="CobaltFieldCaptionTD">
                    <h3><strong>Mantenimiento de Indicaciones por Ex&aacute;menes de Laboratorio Cl&iacute;nico</h3></strong>
		</td>
	</tr>		  
	<tr>
        <td class="StormyWeatherFieldCaptionTD">&Aacute;rea</td>
        <td class="StormyWeatherDataTD">
			<select id="cmbArea" name="cmbArea" size="1" onChange="LlenarComboExamen(this.value);" style="width:235px" class="form-control height placeholder">
				<option value="0" >--Seleccione un &Aacute;rea--</option>
				<?php
                                       $consulta= $objeareas->consultaractivas($lugar); 
					while($row = pg_fetch_array($consulta)){
						echo "<option value='" . $row['idarea']. "'>" . $row['nombrearea'] . "</option>";
					}
				?>
			</select>		  
	</td>
    </tr>
    <tr>
        <td class="StormyWeatherFieldCaptionTD">Examen</td>
        <td class="StormyWeatherDataTD">
             <div id="divExamen">
                <select name="cmbExamen" id="cmbExamen" style="width:235px" class="form-control height placeholder"> 
                    <option value="0">--Seleccione un Examen--</option>
                </select>
            </div>
       </td>
    </tr>
	<tr>
        <td class="StormyWeatherFieldCaptionTD">Indicaci&oacute;n</td>
        <td class="StormyWeatherDataTD">
            <textarea name="txtindicacion" cols="60" rows="4" style="width: 100%" id="txtindicacion"  placeholder="Ingrese Indicación..." ></textarea></td>
    </tr>
    <!--<tr>
		<td class="StormyWeatherDataTD" colspan="2" align="right">
			   <input type="button" name="Submit" value="Guardar" Onclick="Guardar() ;">   
			   <input type="button" name="Submit2" value="Buscar" Onclick="Buscar() ;">
			   <input type="button" name="Submit2" value="Cancelar" Onclick="Cancelar() ;">
		</td>
    </tr>-->
    
    
    <tr>
                <td class="StormyWeatherDataTD" colspan="6" align="right">
                                
                                <button type='button' align="center" class='btn btn-primary' id="guardar" onclick='Guardar(); '><span class='glyphicon glyphicon-floppy-disk'></span> Guardar</button>
                                <button type='button' align="center" class='btn btn-primary' id="abrir"  onclick='Buscar(); '><span class='glyphicon glyphicon-search'></span> Buscar </button>
                                <button type='button' align="center" class='btn btn-primary'  onclick="window.location.replace('MntIndicacionesPorExamen.php')"><span class='glyphicon glyphicon-refresh'></span> Nueva Busqueda</button>
                
                </td>
    </tr>  
</table>
</form>
</div>
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
